<?php
/**
 * DevHub: Template hooks
 *
 * @package Super_Awesome_Theme
 * @license GPL-2.0-or-later
 * @link    https://super-awesome-author.org/themes/super-awesome-theme/
 */

/**
 * Adjusts the document title to add context based on what is being viewed.
 *
 * @since 1.0.0
 *
 * @param array $parts Original document title parts.
 * @return array Modified document title parts.
 */
function super_awesome_theme_adjust_document_title( $parts ) {
	if ( is_feed() ) {
		return $parts;
	}

	$post_type = get_query_var( 'post_type' );
	if ( ! $post_type || ! is_singular( $post_type ) || ! in_array( $post_type, super_awesome_theme_devhub_get_post_types(), true ) ) {
		return $parts;
	}

	$index = array_search( 'title', array_keys( $parts ), true );

	return array_merge(
		array_slice( $parts, 0, $index + 1 ),
		array(
			'type' => get_post_type_object( $post_type )->labels->name,
		),
		array_slice( $parts, $index + 1 )
	);
}
add_filter( 'document_title_parts', 'super_awesome_theme_adjust_document_title' );

/**
 * Appends parentheses to titles in archive view for functions and methods.
 *
 * @since 1.0.0
 *
 * @param string      $title The original title.
 * @param int|WP_Post $post  Optional. The post ID or post object. Default null.
 * @return string The modified title.
 */
function super_awesome_theme_devhub_adjust_archive_post_title( $title, $post = null ) {
	if ( ! is_admin() && $post && ( ! is_single() || doing_filter( 'single_post_title' ) ) && in_array( get_post_type( $post ), array( 'wp-parser-function', 'wp-parser-method' ) ) ) {
		$title .= '()';
	}

	return $title;
}
add_filter( 'the_title', 'super_awesome_theme_devhub_adjust_archive_post_title', 10, 2 );
add_filter( 'single_post_title', 'super_awesome_theme_devhub_adjust_archive_post_title', 10, 2 );

/**
 * Transforms namespaces titles into fully qualified namespaces including links to the parents.
 *
 * @since 1.0.0
 *
 * @param string $title The original title.
 * @return string The modified title.
 */
function super_awesome_theme_devhub_adjust_archive_term_title( $title ) {
	$term = get_queried_object();

	if ( is_admin() || 'wp-parser-namespace' !== $term->taxonomy ) {
		return $title;
	}

	$parts = array(
		$term->name,
	);

	while ( $term->parent ) {
		$term = get_term( $term->parent );
		if ( ! $term || is_wp_error( $term ) ) {
			break;
		}

		if ( did_action( 'wp_enqueue_scripts' ) ) { // This prevents link tags in the document title.
			$parts[] = '<a href="' . esc_url( get_term_link( $term ) ) . '">' . esc_html( $term->name ) . '</a>';
		} else {
			$parts[] = esc_html( $term->name );
		}
	}

	return implode( '\\', array_reverse( $parts ) );
}
add_filter( 'single_term_title', 'super_awesome_theme_devhub_adjust_archive_term_title' );

/**
 * Adjusts excerpts to provide hook type information in archive view for hooks.
 *
 * @since 1.0.0
 *
 * @param string $excerpt Original excerpt.
 * @return string Modified excerpt.
 */
function super_awesome_theme_devhub_adjust_archive_excerpt( $excerpt ) {
	if ( ! is_singular() ) {
		$hook_type = super_awesome_theme_devhub_get_hook_type();
		if ( ! empty( $hook_type ) ) {
			switch ( $hook_type ) {
				case 'action':
				case 'action_reference':
					$type = __( 'Action Hook', 'super-awesome-theme' );
					break;
				case 'filter':
				case 'filter_reference':
					$type = __( 'Filter Hook', 'super-awesome-theme' );
					break;
				case 'action_deprecated':
					$type = __( 'Action Hook (deprecated)', 'super-awesome-theme' );
					break;
				case 'filter_deprecated':
					$type = __( 'Filter Hook (deprecated)', 'super-awesome-theme' );
					break;
			}

			$excerpt = '<strong>' . esc_html( $type ) . ':</strong> ' . $excerpt;
		}
	}

	return $excerpt;
}
add_filter( 'get_the_excerpt', 'super_awesome_theme_devhub_adjust_archive_excerpt' );

/**
 * Adds custom classes to the array of post classes.
 *
 * @since 1.0.0
 *
 * @param array $classes Classes for the post's element.
 * @param array $class   An array of additional classes added to the post.
 * @param int   $post_id The post ID.
 * @return array Modified classes.
 */
function super_awesome_theme_devhub_add_post_class( $classes, $class, $post_id ) {
	if ( in_array( get_post_type( $post_id ), super_awesome_theme_devhub_get_post_types(), true ) ) {
		$classes[] = 'devhub-post';
	}

	return $classes;
}
add_filter( 'post_class', 'super_awesome_theme_devhub_add_post_class', 10, 3 );

/**
 * Prevents the sidebar from showing for DevHub content.
 *
 * @since 1.0.0
 *
 * @param bool $result Original flag whether the sidebar is allowed.
 * @return bool Modified flag whether the sidebar is allowed.
 */
function super_awesome_theme_devhub_prevent_sidebar( $result ) {
	$post_types = super_awesome_theme_devhub_get_post_types();
	$taxonomies = super_awesome_theme_devhub_get_taxonomies();

	if ( is_singular( $post_types ) || is_post_type_archive( $post_types ) || is_tax( $taxonomies ) ) {
		$result = false;
	}

	return $result;
}
add_filter( 'super_awesome_theme_allow_display_sidebar', 'super_awesome_theme_devhub_prevent_sidebar' );
