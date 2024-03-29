<?php

/**
 * Plugin Name:       Decent Looking Emails
 * Plugin URI:        https://github.com/Brugman/decent-looking-emails/
 * Description:       Convert the plain text emails that WordPress sends into decent looking HTML emails.
 * Version:           0.1.0
 * Requires at least: 6.0
 * Requires PHP:      7.4
 * Author:            Medium Rare
 * Author URI:        https://mediumrare.dev/
 * License:           GPLv3
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain:       decent-looking-emails
 * Domain Path:       /languages
 */

defined( 'ABSPATH' ) || exit;

/**
 * Dev helpers.
 */

if ( !function_exists( 'd' ) )
{
    function d( $var )
    {
        echo "<pre style=\"max-height: 800px; z-index: 9999; position: relative; overflow-y: scroll; white-space: pre-wrap; word-wrap: break-word; padding: 10px 15px; border: 1px solid #fff; background-color: #161616; text-align: left; line-height: 1.5; font-family: Courier; font-size: 16px; color: #fff; \">";
        print_r( $var );
        echo "</pre>";
    }
}

if ( !function_exists( 'dd' ) )
{
    function dd( $var )
    {
        d( $var );
        exit;
    }
}

// temp
if ( file_exists( 'test-email.php' ) )
    include 'test-email.php';

/**
 * Helpers.
 */

function set_wp_mail_content_type_html()
{
    return 'text/html';
}

function build_html_email_message( $args )
{
    // make backup
    $original_message = $args['message'];

    // make links clickable
    $args['message'] = make_clickable( $args['message'] );
    // convert lone & characters into &#038;
    $args['message'] = convert_chars( $args['message'] );
    // make paragraphs
    $args['message'] = wpautop( $args['message'] );

    // build logo html
    $logo_html = '';

    $logo_url  = apply_filters( 'dle_logo_url', false );
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

    // build top and bottom image html
    $top_image_html    = '';
    $bottom_image_html = '';

    $top_image_url    = apply_filters( 'dle_top_image_url', false );
    $bottom_image_url = apply_filters( 'dle_bottom_image_url', false );

    if ( $top_image_url )
        $top_image_html = '<img class="img-responsive" src="'.$top_image_url.'" style="width: 100%;">';
    if ( $bottom_image_url )
        $bottom_image_html = '<img class="img-responsive" src="'.$bottom_image_url.'" style="width: 100%;">';

    // get footer html
    $footer_html = apply_filters( 'dle_footer_html', '' );

    // get the template
    $template_path = apply_filters( 'dle_template', __DIR__.'/template-01.html' );

    if ( !file_exists( $template_path ) )
        return $original_message;

    $html = file_get_contents( $template_path );

    // prepare replacements
    $replacements = [
        '[SUBJECT]'      => $args['subject'],
        '[BODY]'         => $args['message'],
        '[LOGO]'         => $logo_html,
        '[TOP_IMAGE]'    => $top_image_html,
        '[BOTTOM_IMAGE]' => $bottom_image_html,
        '[PREHEADER]'    => '',
        '[FOOTER]'       => $footer_html,
    ];

    // make replacements
    return strtr( $html, $replacements );
}

/**
 * Before sending email.
 */

add_filter( 'wp_mail', function ( $args ) {

    // enable HTML mails
    add_filter( 'wp_mail_content_type', 'set_wp_mail_content_type_html' );

    // if the message is already html, do nothing
    if ( strpos( strtolower( $args['message'] ), '<html' ) !== false )
        return $args;

    // place message inside HTML template
    $args['message'] = build_html_email_message( $args );

    return $args;

}, 10, 1 );

