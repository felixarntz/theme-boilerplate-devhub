<?php
/**
 * Template part for displaying a post of type 'wp-parser-class'
 *
 * @package Super_Awesome_Theme
 * @license GPL-2.0-or-later
 * @link    https://super-awesome-author.org/themes/super-awesome-theme/
 */

if ( function_exists( 'super_awesome_theme_devhub_get_post_types' ) ) {
	get_template_part( 'template-parts/content/devhub/content', get_post_type() );
} else {
	get_template_part( 'template-parts/content/content' );
}
