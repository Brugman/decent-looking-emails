# Decent Looking Emails

Work in progress. Use at your own risk.

A WordPress plugin that upgrades your system emails to HTML, with templates, without a UI.

It's what I wanted from [WP Better Emails](https://wordpress.org/plugins/wp-better-emails/) and [Email Template Designer](https://wordpress.org/plugins/wp-html-mail/) without all the fancy UI, with modern default templates, and without the bugs. This plugin does not fuck with your the senders' name or email address. You can blindly throw this at a fresh install and know your system emails are now actually decent looking, and not from 1999.

## Configuration

### Logo

Default: No logo.

```php
add_filter( 'dle_logo_url', function ( $logo_url ) {
    return 'https://example.org/path-to-your-logo.png';
});
```

Default: No link.

```php
add_filter( 'dle_logo_link', function ( $logo_url ) {
    return 'https://example.org/';
});
```

### Footer

Default: No footer.

```php
add_filter( 'dle_footer_html', function ( $footer_html ) {
    return 'This is an example footer.<br>It takes HTML, so it can span multiple lines.';
});
```

### Top Image

A full-width image between the logo and the body content.

Default: No image.

```php
add_filter( 'dle_top_image_url', function ( $top_image_url ) {
    return 'https://example.org/path-to-your-image.png';
});
```

### Bottom Image

A full-width image between the body content and the footer.

Default: No image.

```php
add_filter( 'dle_bottom_image_url', function ( $bottom_image_url ) {
    return 'https://example.org/path-to-your-image.png';
});
```

