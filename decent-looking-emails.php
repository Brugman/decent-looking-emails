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

function set_wp_mail_content_type_html()
{
    return 'text/html';
}

function build_html_email_message( $args )
{
    $html = file_get_contents( __DIR__.'/template-01.html' );

    // replace plain characters with formatted entitites
    $args['message'] = wptexturize( $args['message'] );
    // make links clickable
    $args['message'] = make_clickable( $args['message'] );
    // convert lone & characters into &#038;
    $args['message'] = convert_chars( $args['message'] );
    // convert entities
    $args['message'] = htmlentities2( $args['message'] );
    // make paragraphs
    $args['message'] = wpautop( $args['message'] );

    $logo_html = '';

    $logo_url = apply_filters( 'dle_logo_url', false );
    $logo_link = apply_filters( 'dle_logo_link', false );

    if ( $logo_url )
    {
        if ( $logo_link )
        {
            $logo_html .= '<a href="'.$logo_link.'" target="_blank">';
            $logo_html .= '<img src="'.$logo_url.'">';
            $logo_html .= '</a>';
        }
        else
        {
            $logo_html .= '<img src="'.$logo_url.'">';
        }
    }

    $footer_html = apply_filters( 'dle_footer_html', '' );

    $replacements = [
        '[SUBJECT]'   => $args['subject'],
        '[BODY]'      => $args['message'],
        '[LOGO]'      => $logo_html,
        '[PREHEADER]' => '',
        '[FOOTER]'    => $footer_html,
    ];

    return strtr( $html, $replacements );
}

/**
 * Before sending email.
 */

add_filter( 'wp_mail', function ( $args ) {

    // enable HTML mails
    add_filter( 'wp_mail_content_type', 'set_wp_mail_content_type_html' );

    // place message inside HTML template
    $args['message'] = build_html_email_message( $args );

    return $args;

}, 10, 1 );

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

