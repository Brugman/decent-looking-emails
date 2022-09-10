<?php

/*
Plugin Name: Decent Looking Emails
*/

if ( !defined( 'ABSPATH' ) )
    exit;

function d( $var )
{
    echo "<pre style=\"max-height: 800px; z-index: 9999; position: relative; overflow-y: scroll; white-space: pre-wrap; word-wrap: break-word; padding: 10px 15px; border: 1px solid #fff; background-color: #161616; text-align: left; line-height: 1.5; font-family: Courier; font-size: 16px; color: #fff; \">";
    print_r( $var );
    echo "</pre>";
}

function set_wp_mail_content_type_html()
{
    return 'text/html';
}

add_action( 'admin_init', function () {

    if ( $_SERVER['QUERY_STRING'] != 'sendmail' )
        return;

    d( 'sending some regular ass email' );

    $message = "Line 1.\nLine 2.\n\nLine 3 is longer.\n\nLine 4.\nLine 5.";
    // $message = "Line 1.\nLine 2.\n\nLine 3 is longer.\n\nLine 4.\nLine 5.";

    // wp_mail(
    //     'example@example.org', // to
    //     'test', // subject
    //     $message, // message
    // );

    // sleep( 3 );

    wp_mail(
        'example@example.org', // to
        'i am the subject', // subject
        $message, // message
    );

    d( 'done' );
});

function use_template( $args )
{
    $html = file_get_contents( __DIR__.'/template-01.html' );

    // $args['message'] = htmlentities( $args['message'] );
    $args['message'] = wpautop( $args['message'] );
    // UNTESTED
    // https://github.com/johnbillion/wp_mail
    // GOOD
    // to user, password reset
    $args['message'] = preg_replace( '/http(.*)</', '<a href="http$1">http$1</a><', $args['message'] );

    $replacements = [
        '[SUBJECT]'   => $args['subject'],
        '[BODY]'      => $args['message'],
        '[PREHEADER]' => '',
        '[FOOTER]'    => 'This email was powered by timbr.',
    ];

    return strtr( $html, $replacements );
}

add_filter( 'wp_mail', function ( $args ) {
    d( 'intercepting' );

    d( $args );

    // remove_filter( 'wp_mail_content_type', 'set_wp_mail_content_type_html' );

    // if ( $args['subject'] != 'test' )
    //     return $args;

    $args['message'] = use_template( $args );

    add_filter( 'wp_mail_content_type', 'set_wp_mail_content_type_html' );

    return $args;

}, 10, 1 );

