<?php
declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');
header('X-Content-Type-Options: nosniff');

function respond(int $status, string $message, array $extra = []): void
{
    http_response_code($status);
    echo json_encode(array_merge(['ok' => $status === 200, 'message' => $message], $extra));
    exit;
}

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    header('Allow: POST');
    respond(405, 'Please send your message using the contact form.');
}
if (($_SERVER['HTTP_X_GHUMNAJAUN_CONTACT'] ?? '') !== '1') {
    respond(403, 'Please send your message using the contact form.');
}
$origin = $_SERVER['HTTP_ORIGIN'] ?? '';
$allowedOrigins = ['https://ghumnajaun.com', 'https://www.ghumnajaun.com'];
if (PHP_SAPI === 'cli-server') {
    $allowedOrigins[] = 'http://' . ($_SERVER['HTTP_HOST'] ?? '');
}
if ($origin !== '' && !in_array($origin, $allowedOrigins, true)) {
    respond(403, 'Please reload the contact page and try again.');
}
if ((int) ($_SERVER['CONTENT_LENGTH'] ?? 0) > 24000) {
    respond(413, 'Your message is too long. Please use 3,000 characters or fewer.');
}

$values = [];
foreach (['name', 'email', 'subject', 'body', 'website'] as $field) {
    if (isset($_POST[$field]) && !is_string($_POST[$field])) {
        respond(422, 'Please check the form fields and try again.');
    }
    $values[$field] = trim($_POST[$field] ?? '');
    if (preg_match('//u', $values[$field]) !== 1) {
        respond(422, 'Please check the form fields and try again.');
    }
}
if ($values['website'] !== '') {
    respond(422, 'Your message could not be submitted. Please contact hello@ghumnajaun.com.');
}
$errors = [];
$length = static fn(string $value): int => preg_match_all('/./us', $value);
if ($values['name'] === '' || $length($values['name']) > 100 || preg_match('/[\r\n\x00]/', $values['name'])) {
    $errors['name'] = 'Enter your name using 100 characters or fewer.';
}
if (strlen($values['email']) > 254 || !filter_var($values['email'], FILTER_VALIDATE_EMAIL) || preg_match('/[\r\n\x00]/', $values['email'])) {
    $errors['email'] = 'Enter a valid email address.';
}
$topics = ['Suggest a place', 'Update a guide', 'Listings and collaborations', 'General enquiry'];
if (!in_array($values['subject'], $topics, true)) {
    $errors['subject'] = 'Please choose a topic.';
}
if ($values['body'] === '' || $length($values['body']) > 3000 || str_contains($values['body'], "\0")) {
    $errors['body'] = 'Enter your message using 3,000 characters or fewer.';
}
if ($errors) {
    respond(422, 'Please check the highlighted fields.', ['errors' => $errors]);
}

// Store only a hash and attempt timestamps outside the public website directory.
$rateDirectory = sys_get_temp_dir() . '/ghumnajaun-contact-' . substr(hash('sha256', __DIR__), 0, 16);
if (!is_dir($rateDirectory) && !@mkdir($rateDirectory, 0700, true) && !is_dir($rateDirectory)) {
    respond(503, 'Messages are temporarily unavailable. Please email hello@ghumnajaun.com.');
}
$rateFile = $rateDirectory . '/' . hash('sha256', $_SERVER['REMOTE_ADDR'] ?? 'unknown') . '.json';
$handle = @fopen($rateFile, 'c+');
if (!$handle || !flock($handle, LOCK_EX)) {
    respond(503, 'Messages are temporarily unavailable. Please email hello@ghumnajaun.com.');
}
$attempts = json_decode(stream_get_contents($handle), true);
$now = time();
$attempts = is_array($attempts) ? array_values(array_filter($attempts, static fn($stamp) => is_int($stamp) && $stamp > $now - 900)) : [];
if (count($attempts) >= 5) {
    flock($handle, LOCK_UN);
    fclose($handle);
    header('Retry-After: 900');
    respond(429, 'Too many messages. Please wait 15 minutes before trying again.');
}
$attempts[] = $now;
rewind($handle);
ftruncate($handle, 0);
fwrite($handle, json_encode($attempts));
fflush($handle);
flock($handle, LOCK_UN);
fclose($handle);
// Expired records are removed periodically without retaining enquiry content.
if (random_int(1, 20) === 1) {
    foreach (glob($rateDirectory . '/*.json') ?: [] as $oldFile) {
        if (filemtime($oldFile) < $now - 86400) {
            @unlink($oldFile);
        }
    }
}

$body = "Name: {$values['name']}\nReply email: {$values['email']}\nTopic: {$values['subject']}\n\n{$values['body']}\n";
$headers = [
    'From' => 'Ghumna Jaun <hello@ghumnajaun.com>',
    'Reply-To' => $values['email'],
    'MIME-Version' => '1.0',
    'Content-Type' => 'text/plain; charset=UTF-8',
    'Content-Transfer-Encoding' => 'quoted-printable',
];
try {
    $accepted = @mail('ghumnajaun@gmail.com', 'Ghumna Jaun - ' . $values['subject'], quoted_printable_encode($body), $headers);
} catch (Throwable $error) {
    $accepted = false;
}
if (!$accepted) {
    respond(503, 'Your message could not be sent. Please try again later or email hello@ghumnajaun.com.');
}
respond(200, 'Thank you. Your message has been submitted.');
