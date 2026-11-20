<?php

/** Destination related. @package Ghumnajaun */
defined( 'ABSPATH' ) || exit;

$data = $args['data'];
$details = $args['details'];
$guide = $args['guide'];
echo gj_destination_cards_html( gj_related_destinations( $args['id'] ), $data );
