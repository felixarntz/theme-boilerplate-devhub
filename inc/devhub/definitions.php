<?php
/**
 * DevHub: Definitions
 *
 * @package Super_Awesome_Theme
 * @license GPL-2.0-or-later
 * @link    https://super-awesome-author.org/themes/super-awesome-theme/
 */

/**
 * Gets all DevHub post types.
 *
 * @since 1.0.0
 *
 * @param bool $with_labels   Optional. Whether to include a plural label for each post type. Default false.
 * @param bool $existing_only Optional. Whether to only include post types that are registered. Default false.
 * @return array List of post types, or map of $post_type => $label pairs if $with_labels is true.
 */
function super_awesome_theme_devhub_get_post_types( $with_labels = false, $existing_only = false ) {
	$post_types = array(
		'wp-parser-class',
		'wp-parser-trait',
		'wp-parser-interface',
		'wp-parser-function',
		'wp-parser-method',
		'wp-parser-hook',
	);

	if ( $existing_only ) {
		$post_types = array_filter( $post_types, 'post_type_exists' );
	}

	if ( $with_labels ) {
		return super_awesome_theme_devhub_get_post_type_labels( $post_types );
	}

	return $post_types;
}

/**
 * Gets post type labels for DevHub post types.
 *
 * @since 1.0.0
 *
 * @param array $post_types List of post types.
 * @return array Map of $post_type => $label pairs.
 */
function super_awesome_theme_devhub_get_post_type_labels( $post_types ) {
	$post_type_labels = array(
		'wp-parser-class'     => _x( 'Classes', 'post type general name', 'super-awesome-theme' ),
		'wp-parser-trait'     => _x( 'Traits', 'post type general name', 'super-awesome-theme' ),
		'wp-parser-interface' => _x( 'Interfaces', 'post type general name', 'super-awesome-theme' ),
		'wp-parser-function'  => _x( 'Functions', 'post type general name', 'super-awesome-theme' ),
		'wp-parser-method'    => _x( 'Methods', 'post type general name', 'super-awesome-theme' ),
		'wp-parser-hook'      => _x( 'Hooks', 'post type general name', 'super-awesome-theme' ),
	);

	return array_intersect_key( $post_type_labels, array_flip( $post_types ) );
}

/**
 * Adjusts post type registration arguments for DevHub post types.
 *
 * @since 1.0.0
 *
 * @param array  $args      Post type registration arguments.
 * @param string $post_type Post type.
 * @return array Modified post type registration arguments.
 */
function super_awesome_theme_devhub_adjust_post_type_registrations( $args, $post_type ) {
	if ( ! in_array( $post_type, super_awesome_theme_devhub_get_post_types(), true ) ) {
		return $args;
	}

	$prefix = '';
	if ( get_theme_mod( 'devhub_use_prefix', true ) ) {
		$prefix = _x( 'reference', 'rewrite slug', 'super-awesome-theme' ) . '/';
	}

	switch ( $post_type ) {
		case 'wp-parser-class':
			add_rewrite_rule( $prefix . _x( 'classes', 'rewrite slug', 'super-awesome-theme' ) . '/page/([0-9]{1,})/?$', 'index.php?post_type=wp-parser-class&paged=$matches[1]', 'top' );
			add_rewrite_rule( $prefix . _x( 'classes', 'rewrite slug', 'super-awesome-theme' ) . '/([^/]+)/([^/]+)/?$', 'index.php?post_type=wp-parser-method&name=$matches[1]-$matches[2]', 'top' );

			$args['labels'] = array(
				'name'                  => _x( 'Classes', 'post type general name', 'super-awesome-theme' ),
				'singular_name'         => _x( 'Class', 'post type singular name', 'super-awesome-theme' ),
				'add_new'               => _x( 'Add New', 'class', 'super-awesome-theme' ),
				'add_new_item'          => __( 'Add New Class', 'super-awesome-theme' ),
				'edit_item'             => __( 'Edit Class', 'super-awesome-theme' ),
				'new_item'              => __( 'New Class', 'super-awesome-theme' ),
				'view_item'             => __( 'View Class', 'super-awesome-theme' ),
				'view_items'            => __( 'View Classes', 'super-awesome-theme' ),
				'search_items'          => __( 'Search Classes', 'super-awesome-theme' ),
				'not_found'             => __( 'No classes found.', 'super-awesome-theme' ),
				'not_found_in_trash'    => __( 'No classes found in Trash.', 'super-awesome-theme' ),
				'parent_item_colon'     => __( 'Parent Class:', 'super-awesome-theme' ),
				'all_items'             => __( 'All Classes', 'super-awesome-theme' ),
				'archives'              => __( 'Class Archives', 'super-awesome-theme' ),
				'attributes'            => __( 'Class Attributes', 'super-awesome-theme' ),
				'insert_into_item'      => __( 'Insert into class', 'super-awesome-theme' ),
				'uploaded_to_this_item' => __( 'Uploaded to this class', 'super-awesome-theme' ),
				'filter_items_list'     => __( 'Filter classes list', 'super-awesome-theme' ),
				'items_list_navigation' => __( 'Classes list navigation', 'super-awesome-theme' ),
				'items_list'            => __( 'Classes list', 'super-awesome-theme' ),
			);
			if ( ! empty( $args['has_archive'] ) ) {
				$args['has_archive'] = $prefix . _x( 'classes', 'rewrite slug', 'super-awesome-theme' );
			}
			if ( ! empty( $args['rewrite']['slug'] ) ) {
				$args['rewrite']['with_front'] = false;
				$args['rewrite']['slug']       = $prefix . _x( 'classes', 'rewrite slug', 'super-awesome-theme' );
			}
			break;
		case 'wp-parser-trait':
			add_rewrite_rule( $prefix . _x( 'traits', 'rewrite slug', 'super-awesome-theme' ) . '/page/([0-9]{1,})/?$', 'index.php?post_type=wp-parser-trait&paged=$matches[1]', 'top' );
			add_rewrite_rule( $prefix . _x( 'traits', 'rewrite slug', 'super-awesome-theme' ) . '/([^/]+)/([^/]+)/?$', 'index.php?post_type=wp-parser-method&name=$matches[1]-$matches[2]', 'top' );

			$args['labels'] = array(
				'name'                  => _x( 'Traits', 'post type general name', 'super-awesome-theme' ),
				'singular_name'         => _x( 'Trait', 'post type singular name', 'super-awesome-theme' ),
				'add_new'               => _x( 'Add New', 'trait', 'super-awesome-theme' ),
				'add_new_item'          => __( 'Add New Trait', 'super-awesome-theme' ),
				'edit_item'             => __( 'Edit Trait', 'super-awesome-theme' ),
				'new_item'              => __( 'New Trait', 'super-awesome-theme' ),
				'view_item'             => __( 'View Trait', 'super-awesome-theme' ),
				'view_items'            => __( 'View Traits', 'super-awesome-theme' ),
				'search_items'          => __( 'Search Traits', 'super-awesome-theme' ),
				'not_found'             => __( 'No traits found.', 'super-awesome-theme' ),
				'not_found_in_trash'    => __( 'No traits found in Trash.', 'super-awesome-theme' ),
				'parent_item_colon'     => __( 'Parent Trait:', 'super-awesome-theme' ),
				'all_items'             => __( 'All Traits', 'super-awesome-theme' ),
				'archives'              => __( 'Trait Archives', 'super-awesome-theme' ),
				'attributes'            => __( 'Trait Attributes', 'super-awesome-theme' ),
				'insert_into_item'      => __( 'Insert into trait', 'super-awesome-theme' ),
				'uploaded_to_this_item' => __( 'Uploaded to this trait', 'super-awesome-theme' ),
				'filter_items_list'     => __( 'Filter traits list', 'super-awesome-theme' ),
				'items_list_navigation' => __( 'Traits list navigation', 'super-awesome-theme' ),
				'items_list'            => __( 'Traits list', 'super-awesome-theme' ),
			);
			if ( ! empty( $args['has_archive'] ) ) {
				$args['has_archive'] = $prefix . _x( 'traits', 'rewrite slug', 'super-awesome-theme' );
			}
			if ( ! empty( $args['rewrite']['slug'] ) ) {
				$args['rewrite']['with_front'] = false;
				$args['rewrite']['slug']       = $prefix . _x( 'traits', 'rewrite slug', 'super-awesome-theme' );
			}
			break;
		case 'wp-parser-interface':
			add_rewrite_rule( $prefix . _x( 'interfaces', 'rewrite slug', 'super-awesome-theme' ) . '/page/([0-9]{1,})/?$', 'index.php?post_type=wp-parser-interface&paged=$matches[1]', 'top' );
			add_rewrite_rule( $prefix . _x( 'interfaces', 'rewrite slug', 'super-awesome-theme' ) . '/([^/]+)/([^/]+)/?$', 'index.php?post_type=wp-parser-method&name=$matches[1]-$matches[2]', 'top' );

			$args['labels'] = array(
				'name'                  => _x( 'Interfaces', 'post type general name', 'super-awesome-theme' ),
				'singular_name'         => _x( 'Interface', 'post type singular name', 'super-awesome-theme' ),
				'add_new'               => _x( 'Add New', 'interface', 'super-awesome-theme' ),
				'add_new_item'          => __( 'Add New Interface', 'super-awesome-theme' ),
				'edit_item'             => __( 'Edit Interface', 'super-awesome-theme' ),
				'new_item'              => __( 'New Interface', 'super-awesome-theme' ),
				'view_item'             => __( 'View Interface', 'super-awesome-theme' ),
				'view_items'            => __( 'View Interfaces', 'super-awesome-theme' ),
				'search_items'          => __( 'Search Interfaces', 'super-awesome-theme' ),
				'not_found'             => __( 'No interfaces found.', 'super-awesome-theme' ),
				'not_found_in_trash'    => __( 'No interfaces found in Trash.', 'super-awesome-theme' ),
				'parent_item_colon'     => __( 'Parent Interface:', 'super-awesome-theme' ),
				'all_items'             => __( 'All Interfaces', 'super-awesome-theme' ),
				'archives'              => __( 'Interface Archives', 'super-awesome-theme' ),
				'attributes'            => __( 'Interface Attributes', 'super-awesome-theme' ),
				'insert_into_item'      => __( 'Insert into interface', 'super-awesome-theme' ),
				'uploaded_to_this_item' => __( 'Uploaded to this interface', 'super-awesome-theme' ),
				'filter_items_list'     => __( 'Filter interfaces list', 'super-awesome-theme' ),
				'items_list_navigation' => __( 'Interfaces list navigation', 'super-awesome-theme' ),
				'items_list'            => __( 'Interfaces list', 'super-awesome-theme' ),
			);
			if ( ! empty( $args['has_archive'] ) ) {
				$args['has_archive'] = $prefix . _x( 'interfaces', 'rewrite slug', 'super-awesome-theme' );
			}
			if ( ! empty( $args['rewrite']['slug'] ) ) {
				$args['rewrite']['with_front'] = false;
				$args['rewrite']['slug']       = $prefix . _x( 'interfaces', 'rewrite slug', 'super-awesome-theme' );
			}
			break;
		case 'wp-parser-function':
			$args['labels'] = array(
				'name'                  => _x( 'Functions', 'post type general name', 'super-awesome-theme' ),
				'singular_name'         => _x( 'Function', 'post type singular name', 'super-awesome-theme' ),
				'add_new'               => _x( 'Add New', 'function', 'super-awesome-theme' ),
				'add_new_item'          => __( 'Add New Function', 'super-awesome-theme' ),
				'edit_item'             => __( 'Edit Function', 'super-awesome-theme' ),
				'new_item'              => __( 'New Function', 'super-awesome-theme' ),
				'view_item'             => __( 'View Function', 'super-awesome-theme' ),
				'view_items'            => __( 'View Functions', 'super-awesome-theme' ),
				'search_items'          => __( 'Search Functions', 'super-awesome-theme' ),
				'not_found'             => __( 'No functions found.', 'super-awesome-theme' ),
				'not_found_in_trash'    => __( 'No functions found in Trash.', 'super-awesome-theme' ),
				'parent_item_colon'     => __( 'Parent Function:', 'super-awesome-theme' ),
				'all_items'             => __( 'All Functions', 'super-awesome-theme' ),
				'archives'              => __( 'Function Archives', 'super-awesome-theme' ),
				'attributes'            => __( 'Function Attributes', 'super-awesome-theme' ),
				'insert_into_item'      => __( 'Insert into function', 'super-awesome-theme' ),
				'uploaded_to_this_item' => __( 'Uploaded to this function', 'super-awesome-theme' ),
				'filter_items_list'     => __( 'Filter functions list', 'super-awesome-theme' ),
				'items_list_navigation' => __( 'Functions list navigation', 'super-awesome-theme' ),
				'items_list'            => __( 'Functions list', 'super-awesome-theme' ),
			);
			if ( ! empty( $args['has_archive'] ) ) {
				$args['has_archive'] = $prefix . _x( 'functions', 'rewrite slug', 'super-awesome-theme' );
			}
			if ( ! empty( $args['rewrite']['slug'] ) ) {
				$args['rewrite']['with_front'] = false;
				$args['rewrite']['slug']       = $prefix . _x( 'functions', 'rewrite slug', 'super-awesome-theme' );
			}
			break;
		case 'wp-parser-method':
			$args['labels'] = array(
				'name'                  => _x( 'Methods', 'post type general name', 'super-awesome-theme' ),
				'singular_name'         => _x( 'Method', 'post type singular name', 'super-awesome-theme' ),
				'add_new'               => _x( 'Add New', 'method', 'super-awesome-theme' ),
				'add_new_item'          => __( 'Add New Method', 'super-awesome-theme' ),
				'edit_item'             => __( 'Edit Method', 'super-awesome-theme' ),
				'new_item'              => __( 'New Method', 'super-awesome-theme' ),
				'view_item'             => __( 'View Method', 'super-awesome-theme' ),
				'view_items'            => __( 'View Methods', 'super-awesome-theme' ),
				'search_items'          => __( 'Search Methods', 'super-awesome-theme' ),
				'not_found'             => __( 'No methods found.', 'super-awesome-theme' ),
				'not_found_in_trash'    => __( 'No methods found in Trash.', 'super-awesome-theme' ),
				'parent_item_colon'     => __( 'Parent Method:', 'super-awesome-theme' ),
				'all_items'             => __( 'All Methods', 'super-awesome-theme' ),
				'archives'              => __( 'Method Archives', 'super-awesome-theme' ),
				'attributes'            => __( 'Method Attributes', 'super-awesome-theme' ),
				'insert_into_item'      => __( 'Insert into method', 'super-awesome-theme' ),
				'uploaded_to_this_item' => __( 'Uploaded to this method', 'super-awesome-theme' ),
				'filter_items_list'     => __( 'Filter methods list', 'super-awesome-theme' ),
				'items_list_navigation' => __( 'Methods list navigation', 'super-awesome-theme' ),
				'items_list'            => __( 'Methods list', 'super-awesome-theme' ),
			);
			if ( ! empty( $args['has_archive'] ) ) {
				$args['has_archive'] = $prefix . _x( 'methods', 'rewrite slug', 'super-awesome-theme' );
			}
			if ( ! empty( $args['rewrite']['slug'] ) ) {
				$args['rewrite']['with_front'] = false;
				$args['rewrite']['slug']       = $prefix . _x( 'methods', 'rewrite slug', 'super-awesome-theme' );
			}
			break;
		case 'wp-parser-hook':
			$args['labels'] = array(
				'name'                  => _x( 'Hooks', 'post type general name', 'super-awesome-theme' ),
				'singular_name'         => _x( 'Hook', 'post type singular name', 'super-awesome-theme' ),
				'add_new'               => _x( 'Add New', 'hook', 'super-awesome-theme' ),
				'add_new_item'          => __( 'Add New Hook', 'super-awesome-theme' ),
				'edit_item'             => __( 'Edit Hook', 'super-awesome-theme' ),
				'new_item'              => __( 'New Hook', 'super-awesome-theme' ),
				'view_item'             => __( 'View Hook', 'super-awesome-theme' ),
				'view_items'            => __( 'View Hooks', 'super-awesome-theme' ),
				'search_items'          => __( 'Search Hooks', 'super-awesome-theme' ),
				'not_found'             => __( 'No hooks found.', 'super-awesome-theme' ),
				'not_found_in_trash'    => __( 'No hooks found in Trash.', 'super-awesome-theme' ),
				'parent_item_colon'     => __( 'Parent Hook:', 'super-awesome-theme' ),
				'all_items'             => __( 'All Hooks', 'super-awesome-theme' ),
				'archives'              => __( 'Hook Archives', 'super-awesome-theme' ),
				'attributes'            => __( 'Hook Attributes', 'super-awesome-theme' ),
				'insert_into_item'      => __( 'Insert into hook', 'super-awesome-theme' ),
				'uploaded_to_this_item' => __( 'Uploaded to this hook', 'super-awesome-theme' ),
				'filter_items_list'     => __( 'Filter hooks list', 'super-awesome-theme' ),
				'items_list_navigation' => __( 'Hooks list navigation', 'super-awesome-theme' ),
				'items_list'            => __( 'Hooks list', 'super-awesome-theme' ),
			);
			if ( ! empty( $args['has_archive'] ) ) {
				$args['has_archive'] = $prefix . _x( 'hooks', 'rewrite slug', 'super-awesome-theme' );
			}
			if ( ! empty( $args['rewrite']['slug'] ) ) {
				$args['rewrite']['with_front'] = false;
				$args['rewrite']['slug']       = $prefix . _x( 'hooks', 'rewrite slug', 'super-awesome-theme' );
			}
			break;
	}

	return $args;
}
add_filter( 'register_post_type_args', 'super_awesome_theme_devhub_adjust_post_type_registrations', 10, 2 );

/**
 * Gets all DevHub taxonomies.
 *
 * @since 1.0.0
 *
 * @param bool $with_labels   Optional. Whether to include a plural label for each taxonomy. Default false.
 * @param bool $existing_only Optional. Whether to only include taxonomies that are registered. Default false.
 * @return array List of taxonomies, or map of $taxonomy => $label pairs if $with_labels is true.
 */
function super_awesome_theme_devhub_get_taxonomies( $with_labels = false, $existing_only = false ) {
	$taxonomies = array(
		'wp-parser-source-file',
		'wp-parser-package',
		'wp-parser-since',
		'wp-parser-namespace',
	);

	if ( $existing_only ) {
		$taxonomies = array_filter( $taxonomies, 'taxonomy_exists' );
	}

	if ( $with_labels ) {
		return super_awesome_theme_devhub_get_taxonomy_labels( $taxonomies );
	}

	return $taxonomies;
}

/**
 * Gets taxonomy labels for DevHub taxonomies.
 *
 * @since 1.0.0
 *
 * @param array $taxonomies List of taxonomies.
 * @return array Map of $taxonomy => $label pairs.
 */
function super_awesome_theme_devhub_get_taxonomy_labels( $taxonomies ) {
	$taxonomy_labels = array(
		'wp-parser-source-file' => _x( 'Files', 'taxonomy general name', 'super-awesome-theme' ),
		'wp-parser-package'     => _x( '@package', 'taxonomy general name', 'super-awesome-theme' ),
		'wp-parser-since'       => _x( '@since', 'taxonomy general name', 'super-awesome-theme' ),
		'wp-parser-namespace'   => _x( 'Namespaces', 'taxonomy general name', 'super-awesome-theme' ),
	);

	return array_intersect_key( $taxonomy_labels, array_flip( $taxonomies ) );
}

/**
 * Adjusts taxonomy registration arguments for DevHub taxonomies.
 *
 * @since 1.0.0
 *
 * @param array  $args      Taxonomy registration arguments.
 * @param string $taxonomy Taxonomy.
 * @return array Modified taxonomy registration arguments.
 */
function super_awesome_theme_devhub_adjust_taxonomy_registrations( $args, $taxonomy ) {
	if ( ! in_array( $taxonomy, super_awesome_theme_devhub_get_taxonomies(), true ) ) {
		return $args;
	}

	$prefix = '';
	if ( get_theme_mod( 'devhub_use_prefix', true ) ) {
		$prefix = _x( 'reference', 'rewrite slug', 'super-awesome-theme' ) . '/';
	}

	switch ( $taxonomy ) {
		case 'wp-parser-source-file':
			$args['labels'] = array(
				'name'                       => _x( 'Files', 'taxonomy general name', 'super-awesome-theme' ),
				'singular_name'              => _x( 'File', 'taxonomy singular name', 'super-awesome-theme' ),
				'search_items'               => __( 'Search Files', 'super-awesome-theme' ),
				'popular_items'              => __( 'Popular Files', 'super-awesome-theme' ),
				'all_items'                  => __( 'All Files', 'super-awesome-theme' ),
				'parent_item'                => __( 'Parent File', 'super-awesome-theme' ),
				'parent_item_colon'          => __( 'Parent File:', 'super-awesome-theme' ),
				'edit_item'                  => __( 'Edit File', 'super-awesome-theme' ),
				'view_item'                  => __( 'View File', 'super-awesome-theme' ),
				'update_item'                => __( 'Update File', 'super-awesome-theme' ),
				'add_new_item'               => __( 'Add New File', 'super-awesome-theme' ),
				'new_item_name'              => __( 'New File Name', 'super-awesome-theme' ),
				'separate_items_with_commas' => __( 'Separate files with commas', 'super-awesome-theme' ),
				'add_or_remove_items'        => __( 'Add or remove files', 'super-awesome-theme' ),
				'choose_from_most_used'      => __( 'Choose from the most used files', 'super-awesome-theme' ),
				'not_found'                  => __( 'No files found.', 'super-awesome-theme' ),
				'no_terms'                   => __( 'No files', 'super-awesome-theme' ),
				'items_list_navigation'      => __( 'Files list navigation', 'super-awesome-theme' ),
				'items_list'                 => __( 'Files list', 'super-awesome-theme' ),
				'most_used'                  => _x( 'Most Used', 'files', 'super-awesome-theme' ),
				'back_to_items'              => __( '&larr; Back to Files', 'super-awesome-theme' ),
			);
			if ( ! empty( $args['rewrite']['slug'] ) ) {
				$args['rewrite']['with_front'] = false;
				$args['rewrite']['slug']       = $prefix . _x( 'files', 'rewrite slug', 'super-awesome-theme' );
			}
			break;
		case 'wp-parser-package':
			$args['label'] = _x( '@package', 'taxonomy general name', 'super-awesome-theme' );
			if ( ! empty( $args['rewrite']['slug'] ) ) {
				$args['rewrite']['with_front'] = false;
				$args['rewrite']['slug']       = $prefix . _x( 'package', 'rewrite slug', 'super-awesome-theme' );
			}
			break;
		case 'wp-parser-since':
			$args['label'] = _x( '@since', 'taxonomy general name', 'super-awesome-theme' );
			if ( ! empty( $args['rewrite']['slug'] ) ) {
				$args['rewrite']['with_front'] = false;
				$args['rewrite']['slug']       = $prefix . _x( 'since', 'rewrite slug', 'super-awesome-theme' );
			}
			break;
		case 'wp-parser-namespace':
			$args['labels'] = array(
				'name'                       => _x( 'Namespaces', 'taxonomy general name', 'super-awesome-theme' ),
				'singular_name'              => _x( 'Namespace', 'taxonomy singular name', 'super-awesome-theme' ),
				'search_items'               => __( 'Search Namespaces', 'super-awesome-theme' ),
				'popular_items'              => __( 'Popular Namespaces', 'super-awesome-theme' ),
				'all_items'                  => __( 'All Namespaces', 'super-awesome-theme' ),
				'parent_item'                => __( 'Parent Namespace', 'super-awesome-theme' ),
				'parent_item_colon'          => __( 'Parent Namespace:', 'super-awesome-theme' ),
				'edit_item'                  => __( 'Edit Namespace', 'super-awesome-theme' ),
				'view_item'                  => __( 'View Namespace', 'super-awesome-theme' ),
				'update_item'                => __( 'Update Namespace', 'super-awesome-theme' ),
				'add_new_item'               => __( 'Add New Namespace', 'super-awesome-theme' ),
				'new_item_name'              => __( 'New Namespace Name', 'super-awesome-theme' ),
				'separate_items_with_commas' => __( 'Separate namespaces with commas', 'super-awesome-theme' ),
				'add_or_remove_items'        => __( 'Add or remove namespaces', 'super-awesome-theme' ),
				'choose_from_most_used'      => __( 'Choose from the most used namespaces', 'super-awesome-theme' ),
				'not_found'                  => __( 'No namespaces found.', 'super-awesome-theme' ),
				'no_terms'                   => __( 'No namespaces', 'super-awesome-theme' ),
				'items_list_navigation'      => __( 'Namespaces list navigation', 'super-awesome-theme' ),
				'items_list'                 => __( 'Namespaces list', 'super-awesome-theme' ),
				'most_used'                  => _x( 'Most Used', 'namespaces', 'super-awesome-theme' ),
				'back_to_items'              => __( '&larr; Back to Namespaces', 'super-awesome-theme' ),
			);
			if ( ! empty( $args['rewrite']['slug'] ) ) {
				$args['rewrite']['with_front']   = false;
				$args['rewrite']['slug']         = $prefix . _x( 'namespaces', 'rewrite slug', 'super-awesome-theme' );
				$args['rewrite']['hierarchical'] = true;
			}
			break;
	}

	return $args;
}
add_filter( 'register_taxonomy_args', 'super_awesome_theme_devhub_adjust_taxonomy_registrations', 10, 2 );

/**
 * Adjusts permalinks for methods, to include their respective parent.
 *
 * @since 1.0.0
 *
 * @param string  $link The original permalink.
 * @param WP_Post $post Post object.
 * @return string The modified permalink.
 */
function super_awesome_theme_devhub_adjust_method_permalinks( $link, $post ) {
	global $wp_rewrite;

	if ( 'wp-parser-method' !== $post->post_type || 0 === (int) $post->post_parent ) {
		return $link;
	}

	if ( ! $wp_rewrite->using_permalinks() ) {
		return $link;
	}

	$parent = get_post( $post->post_parent );
	if ( ! $parent ) {
		return $link;
	}

	if ( 0 !== strpos( $post->post_name, $parent->post_name . '-' ) ) {
		return $link;
	}

	$method_name = str_replace( $parent->post_name . '-', '', $post->post_name );

	switch ( $parent->post_type ) {
		case 'wp-parser-class':
			$type = _x( 'classes', 'rewrite slug', 'super-awesome-theme' );
			break;
		case 'wp-parser-trait':
			$type = _x( 'traits', 'rewrite slug', 'super-awesome-theme' );
			break;
		case 'wp-parser-interface':
			$type = _x( 'interfaces', 'rewrite slug', 'super-awesome-theme' );
			break;
		default:
			return $link;
	}

	$prefix = '';
	if ( get_theme_mod( 'devhub_use_prefix', true ) ) {
		$prefix = _x( 'reference', 'rewrite slug', 'super-awesome-theme' ) . '/';
	}

	$link = home_url( user_trailingslashit( $prefix . $type . '/' . $parent->post_name . '/' . $method_name ) );

	return $link;
}
add_filter( 'post_type_link', 'super_awesome_theme_devhub_adjust_method_permalinks', 10, 2 );
