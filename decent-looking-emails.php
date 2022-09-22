<?php

/*
Plugin Name: Decent Looking Emails
*/

if ( !defined( 'ABSPATH' ) )
    exit;

// temp
include 'test-email.php';

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
    // replace plain characters with formatted entitites
    // $args['message'] = wptexturize( $args['message'] );
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
            $logo_html .= '<img class="img-responsive" src="'.$logo_url.'">';
            $logo_html .= '</a>';
        }
        else
        {
            $logo_html .= '<img class="img-responsive" src="'.$logo_url.'">';
        }
    }

    $top_image_html = '';
    $bottom_image_html = '';

    $top_image_url = apply_filters( 'dle_top_image_url', false );
    $bottom_image_url = apply_filters( 'dle_bottom_image_url', false );

    if ( $top_image_url )
        $top_image_html = '<img class="img-responsive" src="'.$top_image_url.'" style="width: 100%;">';
    if ( $bottom_image_url )
        $bottom_image_html = '<img class="img-responsive" src="'.$bottom_image_url.'" style="width: 100%;">';

    $footer_html = apply_filters( 'dle_footer_html', '' );

    $replacements = [
        '[SUBJECT]'      => $args['subject'],
        '[BODY]'         => $args['message'],
        '[LOGO]'         => $logo_html,
        '[TOP_IMAGE]'    => $top_image_html,
        '[BOTTOM_IMAGE]' => $bottom_image_html,
        '[PREHEADER]'    => '',
        '[FOOTER]'       => $footer_html,
    ];

    $html = file_get_contents( __DIR__.'/template-01.html' );

    return strtr( $html, $replacements );
}

/**
 * Before sending email.
 */

add_filter( 'wp_mail', function ( $args ) {

    // enable HTML mails
    add_filter( 'wp_mail_content_type', 'set_wp_mail_content_type_html' );

    // if the message is plain text
    // place message inside HTML template
    if ( strpos( $args['message'], '<html' ) === false )
        $args['message'] = build_html_email_message( $args );

    return $args;

}, 10, 1 );

/**
 * Configuration.
 */

add_filter( 'dle_logo_url', function ( $logo_url ) {
    return 'https://i.imgur.com/RmBsEcf.png';
    // return 'http://wptest2.test/wp-content/plugins/decent-looking-emails/example-logo.svg';
});

// add_filter( 'dle_logo_link', function ( $logo_link ) {
//     return 'https://tweakers.net/';
// });

// add_filter( 'dle_top_image_url', function ( $top_image_url ) {
//     return 'https://i.imgur.com/WB9VbP0.jpg';
// });

// add_filter( 'dle_bottom_image_url', function ( $bottom_image_url ) {
//     return 'https://i.imgur.com/T6vBwjM.jpg';
// });

add_filter( 'dle_footer_html', function ( $footer_html ) {
    return 'A new footer was configured.<br>And a great footer it was.';
});

