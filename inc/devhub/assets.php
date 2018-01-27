<?php
/**
 * DevHub: Assets
 *
 * @package Super_Awesome_Theme
 * @license GPL-2.0-or-later
 * @link    https://super-awesome-author.org/themes/super-awesome-theme/
 */

/**
 * Enqueues DevHub scripts and styles.
 *
 * @since 1.0.0
 */
function super_awesome_theme_devhub_enqueue_assets() {
	$post_types = super_awesome_theme_devhub_get_post_types();
	$taxonomies = super_awesome_theme_devhub_get_taxonomies();

	if ( is_search() || is_singular( $post_types ) || is_post_type_archive( $post_types ) || is_tax( $taxonomies ) ) {
		wp_enqueue_style( 'super-awesome-theme-devhub-style', get_theme_file_uri( '/devhub.css' ), array( 'super-awesome-theme-style' ), SUPER_AWESOME_THEME_VERSION );

		if ( is_singular() ) {
			// Load syntax highlighter assets if plugin is active.
			if ( wp_script_is( 'syntaxhighlighter-core', 'registered' ) ) {
				wp_enqueue_style( 'syntaxhighlighter-core' );
				wp_enqueue_style( 'syntaxhighlighter-theme-default' );
				wp_enqueue_script( 'syntaxhighlighter-core' );
				wp_enqueue_script( 'syntaxhighlighter-brush-php' );
			}

			wp_enqueue_script( 'super-awesome-theme-devhub-script', get_theme_file_uri( '/devhub.js' ), array( 'super-awesome-theme-script' ), SUPER_AWESOME_THEME_VERSION, true );
		}
	}
}
add_action( 'wp_enqueue_scripts', 'super_awesome_theme_devhub_enqueue_assets' );
