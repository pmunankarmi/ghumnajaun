<?php
defined('ABSPATH') || exit;
function gj_contact_form(){ ?>
<form class="wp-contact" id="gj-contact-form" method="post" action="<?php echo esc_url(admin_url('admin-ajax.php')); ?>"><input type="hidden" name="action" value="gj_contact"><?php wp_nonce_field('gj_contact','gj_nonce'); ?>
<label>Name<input name="name" required maxlength="100" autocomplete="name"></label><label>Email<input name="email" type="email" required maxlength="254" autocomplete="email"></label><label>Topic<select name="subject"><option>Suggest a place</option><option>Update a guide</option><option>Listings and collaborations</option><option>General enquiry</option></select></label><label>Message<textarea name="body" required maxlength="3000" rows="6"></textarea></label><label hidden aria-hidden="true">Website<input name="website" tabindex="-1" autocomplete="off"></label><button class="primary" type="submit">Send message</button><p id="gj-contact-status" role="status"></p></form>
<?php }
add_action('wp_ajax_gj_contact','gj_send_contact');add_action('wp_ajax_nopriv_gj_contact','gj_send_contact');
function gj_send_contact(){
 if(!check_ajax_referer('gj_contact','gj_nonce',false))wp_send_json_error(['message'=>'Please reload the page and try again.'],403);
 foreach(['name','email','subject','body','website'] as$key)if(isset($_POST[$key])&&!is_string($_POST[$key]))wp_send_json_error(['message'=>'Invalid form fields.'],422);
 $name=sanitize_text_field(wp_unslash($_POST['name']??''));$email=sanitize_email(wp_unslash($_POST['email']??''));$subject=sanitize_text_field(wp_unslash($_POST['subject']??''));$body=sanitize_textarea_field(wp_unslash($_POST['body']??''));
 if(!empty($_POST['website'])||!$name||mb_strlen($name)>100||!is_email($email)||!$body||mb_strlen($body)>3000||!in_array($subject,['Suggest a place','Update a guide','Listings and collaborations','General enquiry'],true))wp_send_json_error(['message'=>'Please check your name, email, topic and message.'],422);
 $key='gj_contact_'.hash_hmac('sha256',$_SERVER['REMOTE_ADDR']??'',wp_salt());$count=(int)get_transient($key);if($count>=5)wp_send_json_error(['message'=>'Please wait 15 minutes before trying again.'],429);set_transient($key,$count+1,15*MINUTE_IN_SECONDS);
 $sent=wp_mail(gj_option('contact_recipient',get_option('admin_email')),'Ghumna Jaun: '.$subject,$name."\n".$email."\n\n".$body,['Reply-To: '.$email]);
 if(!$sent)wp_send_json_error(['message'=>'Unable to send. Your message is still here; please try again.'],503);
 wp_send_json_success(['message'=>'Your message was accepted for delivery. Thank you.']);
}
