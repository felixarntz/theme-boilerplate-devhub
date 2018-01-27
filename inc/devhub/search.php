<?php
/**
 * DevHub: Search
 *
 * @package Super_Awesome_Theme
 * @license GPL-2.0-or-later
 * @link    https://super-awesome-author.org/themes/super-awesome-theme/
 */

/**
 * Registers the query var to detect whether the current request is a reference request.
 *
 * @since 1.0.0
 *
 * @param array $query_vars The original query vars.
 * @return array The modified query vars.
 */
function super_awesome_theme_devhub_add_query_var( $query_vars ) {
	$query_vars[] = 'reference';

	return $query_vars;
}
add_filter( 'query_vars', 'super_awesome_theme_devhub_add_query_var' );

/**
 * Adjusts the main search query if it is considered a DevHub search query.
 *
 * @since 1.0.0
 *
 * @param WP_Query $query Query to adjust.
 */
function super_awesome_theme_devhub_adjust_search( $query ) {
	if ( is_admin() || ! $query->is_main_query() || ! $query->is_search() ) {
		return;
	}

	$search = htmlentities( $query->get( 's' ) );
	if ( '()' === substr( $search, -2 ) ) {
		// If '()' at the end of search string, consider it a function or method lookup.
		$query->set( 'exact', true );
		$query->set( 's', substr( $search, 0, -2 ) );
		$query->set( 'post_type', array( 'wp-parser-function', 'wp-parser-method' ) );
	} elseif ( get_query_var( 'reference' ) ) {
		// If 'reference' is active, consider it a DevHub search.
		$query->set( 'post_type', super_awesome_theme_devhub_get_post_types() );
	} else {
		// Otherwise, only adjust query if it is solely for DevHub post types.
		$post_types = array_filter( (array) $query->get( 'post_type' ) );
		$post_types = array_map( 'sanitize_key', $post_types );

		if ( empty( $post_types ) ) {
			return;
		}

		$devhub_post_types = super_awesome_theme_devhub_get_post_types();

		foreach ( $post_types as $post_type ) {
			if ( ! in_array( $post_type, $devhub_post_types, true ) ) {
				return;
			}
		}
	}

	$query->set( 'orderby', 'title' );
	$query->set( 'order', 'ASC' );
}
add_action( 'pre_get_posts', 'super_awesome_theme_devhub_adjust_search', 20, 1 );

/**
 * Reruns an exact search with the same criteria except exactness if no posts were found.
 *
 * @since 1.0.0
 *
 * @param array    $posts Original array of posts.
 * @param WP_Query $query Query object.
 * @return array Modified array of posts.
 */
function super_awesome_theme_devhub_rerun_empty_exact_search( $posts, $query ) {
	if ( is_search() && true === $query->get( 'exact' ) && ! $query->found_posts ) {
		$query->set( 'exact', false );
		$posts = $query->get_posts();
	}

	return $posts;
}
add_filter( 'the_posts', 'super_awesome_theme_devhub_rerun_empty_exact_search', 10, 2 );
