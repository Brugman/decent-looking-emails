<?php

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

