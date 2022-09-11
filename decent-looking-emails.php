<?php

/*
Plugin Name: Decent Looking Emails
*/

if ( !defined( 'ABSPATH' ) )
    exit;

/**
 * Dev helpers.
 */

function d( $var )
{
    echo "<pre style=\"max-height: 800px; z-index: 9999; position: relative; overflow-y: scroll; white-space: pre-wrap; word-wrap: break-word; padding: 10px 15px; border: 1px solid #fff; background-color: #161616; text-align: left; line-height: 1.5; font-family: Courier; font-size: 16px; color: #fff; \">";
    print_r( $var );
    echo "</pre>";
}

/**
 * Helpers.
 */

// function set_wp_mail_content_type( $content_type )
// {
//     if ( $content_type == 'text/plain' )
//         return 'text/html';

//     return $content_type;
// }

function set_wp_mail_content_type_html()
{
    return 'text/html';
}

// function set_wp_mail_content_type_plain()
// {
//     return 'text/plain';
// }

function build_html_email_message( $args )
{
    $html = file_get_contents( __DIR__.'/template-01.html' );

    // $args['message'] = htmlentities( $args['message'] );
    // $args['message'] = preg_replace( '/http(.*)</', '<a href="http$1">http$1</a><', $args['message'] );
    // $args['message'] = preg_replace( '#<(https?://[^*]+)>#', '$1', $args['message'] );
    // $args['message'] = nl2br( $args['message'] );

    // make links clickable
    $args['message'] = make_clickable( $args['message'] );
    // make paragraphs
    $args['message'] = wpautop( $args['message'] );

    $replacements = [
        '[SUBJECT]'   => $args['subject'],
        '[BODY]'      => $args['message'],
        '[PREHEADER]' => '',
        '[FOOTER]'    => 'This email was powered by timbr.',
    ];

    return strtr( $html, $replacements );
}

/**
 * Before sending email.
 */

add_filter( 'wp_mail', function ( $args ) {
    // d( 'intercepting' );
    // d( $args );
    // remove_filter( 'wp_mail_content_type', 'set_wp_mail_content_type_html' );

    add_filter( 'wp_mail_content_type', 'set_wp_mail_content_type_html' );

    $args['message'] = build_html_email_message( $args );

    return $args;

}, 10, 1 );

/**
 * After sending email.
 */

// add_action( 'wp_mail_succeeded', function ( $mail_data ) {
//     d( 'this is wp_mail_succeeded' );
// });

// add_action( 'wp_mail_failed', function ( $error ) {
//     d( 'this is wp_mail_failed' );
// });

/**
 * (TEMP) Trigger email manually.
 */

add_action( 'admin_init', function () {

    if ( $_SERVER['QUERY_STRING'] != 'sendmail' )
        return;

    d( 'sending some regular ass email' );

    $message = "Line 1.\nLine 2.\n\nLine 3 is longer.\n\nLine 4.\nLine 5.";

    wp_mail(
        'example@example.org', // to
        'i am the subject', // subject
        $message, // message
    );

    d( 'done' );
});

