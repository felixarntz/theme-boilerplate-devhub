# DevHub for Super Awesome Theme (Boilerplate)

This repository contains some files to merge into my [theme boilerplate](https://github.com/felixarntz/theme-boilerplate) in case it should support the [WordPress PHPDoc parser plugin](https://github.com/WordPress/phpdoc-parser) and display its generated contents beautifully like [the WordPress code reference](https://developer.wordpress.org/reference/) does itself. In fact, all the files in this repository are basically a port of [the theme used on that site](https://meta.svn.wordpress.org/sites/trunk/wordpress.org/public_html/wp-content/themes/pub/wporg-developer/).

## Why is this useful?

Using all these things will allow you to easily present documentation for your own plugin or theme, on a WordPress site of course.

## Why should I be cautious?

Unlike my theme boilerplate, this is a private project at this point. Feel free to play around with it, but be cautious and don't expect miracles. Note that the parser plugin itself also has several issues that you may run into. For example it doesn't support traits and interfaces (this repository does though!).

## How do I use it?

Download my [theme boilerplate](https://github.com/felixarntz/theme-boilerplate) to get started with a new theme. Merge all files of this repository (except `.editorconfig.php` and `README.md`) in there. After that, add the following to the theme's `functions.php`:

```php
/**
 * DevHub functionality.
 */
require get_template_directory() . '/inc/devhub.php';
```

Following that, proceed as described in the readme of the theme boilerplate.

**Important:** Merge the files from here in *before* you run the `gulp init-replace` command!
