# Decent Looking Emails

Work in progress.

A WordPress plugin that upgrades your system emails to HTML, with templates, without a UI.

It's what I wanted from [WP Better Emails](https://wordpress.org/plugins/wp-better-emails/) and [Email Template Designer](https://wordpress.org/plugins/wp-html-mail/) without all the fancy UI, with modern default templates, and without the bugs. This plugin does not fuck with your the senders' name or email address. You can blindly throw this at a fresh install and know your system emails are now actually decent looking, and not from 1999.

## Emails

https://github.com/johnbillion/wp_mail

### Tested

- (A-01) Comment is awaiting moderation
- (A-02) Comment is published

- (B-03) Change of site admin email address is attempted
- (B-04) Site admin email address is changed

- (C-07) User or Administrator requests a password reset
- (C-08) User resets their password
- (C-09) User changes their password
- (C-10) User attempts to change their email address
- (C-11) User changes their email address

- (F-23) A new user is created (x2)

- (H-29) A fatal error occurs

### Untestable

- (H-28) Installation

### Untested

- (B-05) (MS) Change of network admin email address is attempted
- (B-06) (MS) Network admin email address is changed

- (D-12) Personal data export or erasure request is created or resent
- (D-13) User confirms personal data export or erasure request
- (D-14) Site admin sends link to a personal data export
- (D-15) Site admin erases personal data to fulfill a data erasure request

- (E-16) Automatic plugin or theme updates
- (E-17) Automatic core update
- (E-18) Full log of background update results

- (F-19) (MS) An existing user is invited to a site
- (F-20) (MS) A new user is invited to join a site
- (F-21) (MS) A new user account is created
- (F-22) (MS) A user is added, or their account activation is successful

- (G-24) (MS) A new site is created
- (G-25) (MS) User registers for a new site
- (G-26) (MS) User activates their new site (to network admin)
- (G-27) (MS) User activates their new site (to site admin)

- (H-30) (MS) Site admin requests to delete site

## Configuration

### Logo

Default: No logo.

```php
add_filter( 'dle_logo_url', function ( $logo_url ) {
    return 'https://i.imgur.com/RmBsEcf.png';
});
```

```php
add_filter( 'dle_logo_link', function ( $logo_url ) {
    return 'https://tweakers.net/';
});
```

### Top Image

Default: No image.

```php
add_filter( 'dle_top_image_url', function ( $top_image_url ) {
    return 'https://i.imgur.com/WB9VbP0.jpg';
});
```

### Bottom Image

Default: No image.

```php
add_filter( 'dle_bottom_image_url', function ( $bottom_image_url ) {
    return 'https://i.imgur.com/T6vBwjM.jpg';
});
```

### Footer

Default: No footer.

```php
add_filter( 'dle_footer_html', function ( $footer_html ) {
    return 'A new footer was configured.<br>And a great footer it was.';
});
```

