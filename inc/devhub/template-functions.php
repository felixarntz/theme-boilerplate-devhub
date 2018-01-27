<?php
/**
 * DevHub: Template functions
 *
 * @package Super_Awesome_Theme
 * @license GPL-2.0-or-later
 * @link    https://super-awesome-author.org/themes/super-awesome-theme/
 */

/**
 * Gets the summary.
 *
 * @since 1.0.0
 *
 * @param int|WP_Post|null $post Optional. Post object. Default is the current post.
 * @return string Summary created from the excerpt.
 */
function super_awesome_theme_devhub_get_summary( $post = null ) {
	$post = get_post( $post );

	$summary = $post->post_excerpt;

	if ( $summary ) {
		$summary = super_awesome_theme_devhub_format_summary( $summary );
		/** This filter is documented in wp-includes/post-template.php */
		$summary = apply_filters( 'the_excerpt', apply_filters( 'get_the_excerpt', $summary, $post ) );
	}

	return $summary;
}

/**
 * Gets the description.
 *
 * @since 1.0.0
 *
 * @param int|WP_Post|null $post Optional. Post object. Default is the current post.
 * @return string Description created from the content.
 */
function super_awesome_theme_devhub_get_description( $post = null ) {
	$post = get_post( $post );

	$description = $post->post_content;

	if ( $description ) {
		/** This filter is documented in wp-includes/post-template.php */
		$description = apply_filters( 'the_content', apply_filters( 'get_the_content', $description ) );
	}

	return $description;
}

/**
 * Gets the function name and arguments as signature string.
 *
 * @since 1.0.0
 *
 * @param int|WP_Post|null $post Optional. Post object. Default is the current post.
 * @return string Signature based on the post data.
 */
function super_awesome_theme_devhub_get_signature( $post = null ) {
	$post = get_post( $post );

	$signature = get_the_title( $post->ID );

	if ( in_array( $post->post_type, array( 'wp-parser-class', 'wp-parser-trait', 'wp-parser-interface' ), true ) ) {
		return $signature;
	}

	$args   = get_post_meta( $post->ID, '_wp-parser_args', true );
	$tags   = get_post_meta( $post->ID, '_wp-parser_tags', true );
	$types  = array();

	if ( $tags ) {
		foreach ( $tags as $tag ) {
			if ( is_array( $tag ) && 'param' == $tag['name'] ) {
				$types[ $tag['variable'] ] = implode( '|', $tag['types'] );
			}
		}
	}

	// Decorate and return hook arguments.
	if ( 'wp-parser-hook' === $post->post_type ) {
		$hook_args = array();
		foreach ( $types as $arg => $type ) {
			$hook_args[] = ' <nobr><span class="arg-type">' . esc_html( $type ) . '</span> <span class="arg-name">' . esc_html( $arg ) . '</span></nobr>';
		}

		$hook_type = super_awesome_theme_devhub_get_hook_type( $post );
		if ( false !== strpos( $hook_type, 'action' ) ) {
			if ( 'action_reference' === $hook_type ) {
				$hook_type = 'do_action_ref_array';
			} elseif ( 'action_deprecated' === $hook_type ) {
				$hook_type = 'do_action_deprecated';
			} else {
				$hook_type = 'do_action';
			}
		} else {
			if ( 'filter_reference' === $hook_type ) {
				$hook_type = 'apply_filters_ref_array';
			} elseif ( 'filter_deprecated' === $hook_type ) {
				$hook_type = 'apply_filters_deprecated';
			} else {
				$hook_type = 'apply_filters';
			}
		}

		$delimiter = false !== strpos( $signature, '$' ) ? '"' : "'";
		$signature = $delimiter . $signature . $delimiter;
		$signature = '<span class="hook-func">' . $hook_type . '</span>( ' . $signature;
		if ( $hook_args ) {
			$signature .= ', ';
			$signature .= implode( ', ', $hook_args );
		}
		$signature .= ' )';

		return $signature;
	}

	$args_strings = array();

	// Decorate and return function/class arguments.
	if ( $args ) {
		foreach ( $args as $arg ) {
			$arg = (array) $arg;
			$arg_string = '';
			if ( ! empty( $arg['name'] ) && ! empty( $types[ $arg['name'] ] ) ) {
				$arg_string .= ' <span class="arg-type">' . $types[ $arg['name'] ] . '</span>';
			}

			if ( ! empty( $arg['name'] ) ) {
				$arg_string .= '&nbsp;<span class="arg-name">' . $arg['name'] . '</span>';
			}

			if ( ! empty( $arg['default'] ) ) {
				$arg_string .= '&nbsp;=&nbsp;<span class="arg-default">' . htmlentities( $arg['default'] ) . '</span>';
			}

			$args_strings[] = $arg_string;
		}
	}

	$args = implode( ', ', $args_strings );

	$signature .= '(';
	if ( ! empty( $args ) ) {
		$signature .= $args . '&nbsp;';
	}
	$signature .= ')';

	return wp_kses_post( $signature );
}

/**
 * Gets the function parameters as an array.
 *
 * @since 1.0.0
 *
 * @param int|WP_Post|null $post Optional. Post object. Default is the current post.
 * @return array Parameters based on the post data.
 */
function super_awesome_theme_devhub_get_params( $post = null ) {
	$post = get_post( $post );

	$params = array();

	$args = get_post_meta( $post->ID, '_wp-parser_args', true );
	$tags = get_post_meta( $post->ID, '_wp-parser_tags', true );

	if ( $tags ) {
		$encountered_optional = false;
		foreach ( $tags as $tag ) {
			// Fix unintended markup introduced by parser.
			$tag = str_replace( array( '<strong>', '</strong>' ), '__', $tag );

			if ( ! empty( $tag['name'] ) && 'param' === $tag['name'] ) {
				$params[ $tag['variable'] ] = $tag;
				$types = array();
				foreach ( $tag['types'] as $i => $v ) {
					$types[ $i ] = sprintf( '<span class="%s">%s</span>', $v, $v );
				}

				// Normalize spacing at beginning of hash notation params.
				if ( $tag['content'] && '{' == $tag['content'][0] ) {
					$tag['content'] = '{ ' . trim( substr( $tag['content'], 1 ) );
				}

				$params[ $tag['variable'] ]['types'] = implode( '|', $types );
				if ( strtolower( substr( $tag['content'], 0, 8 ) ) === 'optional' ) {
					$params[ $tag['variable'] ]['required'] = false;
					$params[ $tag['variable'] ]['content'] = substr( $tag['content'], 9 );
					$encountered_optional = true;
				} elseif ( strtolower( substr( $tag['content'], 2, 9 ) ) === 'optional.' ) { // Hash notation param.
					$params[ $tag['variable'] ]['required'] = false;
					$params[ $tag['variable'] ]['content'] = '{ ' . substr( $tag['content'], 12 );
					$encountered_optional = true;
				} elseif ( $encountered_optional ) {
					$params[ $tag['variable'] ]['required'] = false;
				} else {
					$params[ $tag['variable'] ]['required'] = true;
				}
				$params[ $tag['variable'] ]['content'] = super_awesome_theme_devhub_format_param_description( $params[ $tag['variable'] ]['content'] );
			}
		}
	}

	if ( $args ) {
		foreach ( $args as $arg ) {
			if ( ! empty( $arg['name'] ) && ! empty( $params[ $arg['name'] ] ) ) {
				$params[ $arg['name'] ]['default'] = $arg['default'];

				if ( ! empty( $arg['default'] ) ) {
					$params[ $arg['name'] ]['required'] = false;

					$default = htmlentities( $arg['default'] );
					$params[ $arg['name'] ]['content'] = str_replace( "default is {$default}.", '', $params[ $arg['name'] ]['content'] );
					$params[ $arg['name'] ]['content'] = str_replace( "Default {$default}.", '', $params[ $arg['name'] ]['content'] );

					if ( "''" === $arg['default'] ) {
						// When the default is '', docs sometimes say "Default empty." or similar.
						$params[ $arg['name'] ]['content'] = str_replace( 'Default empty.', '', $params[ $arg['name'] ]['content'] );
						$params[ $arg['name'] ]['content'] = str_replace( 'Default empty string.', '', $params[ $arg['name'] ]['content'] );

						// Only a few cases of this. Remove once core is fixed.
						$params[ $arg['name'] ]['content'] = str_replace( 'default is empty string.', '', $params[ $arg['name'] ]['content'] );
					} elseif ( 'array()' === $arg['default'] ) {
						// When the default is array(), docs sometimes say "Default empty array." or similar.
						$params[ $arg['name'] ]['content'] = str_replace( 'Default empty array.', '', $params[ $arg['name'] ]['content'] );
						// Not as common.
						$params[ $arg['name'] ]['content'] = str_replace( 'Default empty.', '', $params[ $arg['name'] ]['content'] );
					}
				}
			}
		}
	}

	return $params;
}

/**
 * Gets the function arguments as an array.
 *
 * @since 1.0.0
 *
 * @param int|WP_Post|null $post Optional. Post object. Default is the current post.
 * @return array Arguments based on the post data.
 */
function super_awesome_theme_devhub_get_arguments( $post = null ) {
	$post = get_post( $post );

	$arguments = array();

	$args = get_post_meta( $post->ID, '_wp-parser_args', true );

	if ( $args ) {
		foreach ( $args as $arg ) {
			if ( ! empty( $arg['type'] ) ) {
				$arguments[ $arg['name'] ] = $arg['type'];
			}
		}
	}

	return $arguments;
}

/**
 * Gets the return type and description as a string.
 *
 * @since 1.0.0
 *
 * @param int|WP_Post|null $post Optional. Post object. Default is the current post.
 * @return string Return type information, or empty string if not available.
 */
function super_awesome_theme_devhub_get_return( $post = null ) {
	$post = get_post( $post );

	$tags   = get_post_meta( $post->ID, '_wp-parser_tags', true );
	$return = wp_filter_object_list( $tags, array( 'name' => 'return' ) );

	if ( empty( $return ) ) {
		return '';
	}

	$return      = array_shift( $return );
	$description = empty( $return['content'] ) ? '' : super_awesome_theme_devhub_format_param_description( $return['content'] );
	$type        = empty( $return['types'] ) ? '' : esc_html( implode( '|', $return['types'] ) );

	return '<span class="return-type">(' . esc_html( $type ) . ')</span> ' . esc_html( $description );
}

/**
 * Gets the changelog data as an array.
 *
 * @since 1.0.0
 *
 * @param int|WP_Post|null $post Optional. Post object. Default is the current post.
 * @return array Changelog based on the post data.
 */
function super_awesome_theme_devhub_get_changelog( $post = null ) {
	$post = get_post( $post );

	$since_terms = wp_get_post_terms( $post->ID, 'wp-parser-since' );

	// Since data stored in meta.
	$since_meta = get_post_meta( $post->ID, '_wp-parser_tags', true );

	$since_tags = wp_filter_object_list( $since_meta, array( 'name' => 'since' ) );
	$deprecated = wp_filter_object_list( $since_meta, array( 'name' => 'deprecated' ) );

	// If deprecated, add the since version to the term and meta lists.
	if ( $deprecated ) {
		$deprecated = array_shift( $deprecated );

		$deprecated_term = get_term_by( 'name', $deprecated['content'], 'wp-parser-since' );
		if ( $deprecated_term ) {
			// Terms.
			$since_terms[] = $deprecated_term;

			// Meta.
			$since_tags[] = $deprecated;
		}
	}

	$data = array();

	// Pair the term data with meta data.
	foreach ( $since_terms as $since_term ) {
		foreach ( $since_tags as $index => $meta ) {
			if ( is_array( $meta ) && $since_term->name === $meta['content'] ) {
				// Handle deprecation notice if deprecated.
				if ( empty( $meta['description'] ) ) {
					if ( $deprecated ) {
						$description = super_awesome_theme_devhub_get_deprecated( $post, false );
					} elseif ( 0 === $index ) {
						$description = 'Introduced.';
					} else {
						$description = '';
					}
				} else {
					$description = '<span class="since-description">' . super_awesome_theme_devhub_format_param_description( $meta['description'] ) . '</span>';
				}

				$data[ $since_term->name ] = array(
					'version'     => $since_term->name,
					'description' => $description,
					'since_url'   => get_term_link( $since_term ),
				);
			}
		}
	}

	return $data;
}

/**
 * Gets a list of additional resources linked via `@see` tags.
 *
 * @since 1.0.0
 *
 * @param int|WP_Post|null $post Optional. Post object. Default is the current post.
 * @return array List of see tags with 'refers' and 'content' keys.
 */
function super_awesome_theme_devhub_get_see( $post = null ) {
	$post = get_post( $post );

	$tags = get_post_meta( $post->ID, '_wp-parser_tags', true );

	$see_tags = array();

	foreach ( $tags as $tag ) {
		if ( 'see' !== $tag['name'] ) {
			continue;
		}

		if ( empty( $tag['refers'] ) ) {
			continue;
		}

		$see_tags[] = array(
			'refers'  => super_awesome_theme_devhub_resolve_internal_link( $tag['refers'] ),
			'content' => ! empty( $tag['content'] ) ? $tag['content'] : '',
		);
	}

	return $see_tags;
}

/**
 * Gets a deprecated notice as a string.
 *
 * @since 1.0.0
 *
 * @param int|WP_Post|null $post      Optional. Post object. Default is the current post.
 * @param bool             $formatted Optional. Whether to format the deprecation message. Default true.
 * @return string Deprecated notice, or empty string if not applicable.
 */
function super_awesome_theme_devhub_get_deprecated( $post = null, $formatted = true ) {
	$post = get_post( $post );

	$types = explode( '-', $post->post_type );
	$type  = array_pop( $types );
	$tags  = get_post_meta( $post->ID, '_wp-parser_tags', true );

	$deprecated = wp_filter_object_list( $tags, array( 'name' => 'deprecated' ) );
	$deprecated = array_shift( $deprecated );

	if ( ! $deprecated ) {
		return '';
	}

	$deprecation_info = '';

	$referral = wp_filter_object_list( $tags, array( 'name' => 'see' ) );
	$referral = array_shift( $referral );

	// Construct message pointing visitor to preferred alternative, as provided via @see, if present.
	if ( ! empty( $referral['refers'] ) ) {
		$refers = sanitize_text_field( $referral['refers'] );

		if ( $refers ) {
			// For some reason, the parser may have dropped the parentheses, so add them.
			if ( in_array( $type, array( 'function', 'method' ) ) && false === strpos( $refers, '()' ) ) {
				$refers .= '()';
			}

			/* translators: %s: Linked internal element name */
			$deprecation_info = sprintf( __( 'Use %s instead.', 'super-awesome-theme' ), super_awesome_theme_devhub_resolve_internal_link( $refers ) );
		}
	}

	// If no alternative resource was referenced, use the deprecation string, if present.
	if ( ! $deprecation_info && ! empty( $deprecated['description'] ) ) {
		$deprecation_info = sanitize_text_field( $deprecated['description'] );
		// Many deprecation strings use the syntax "Use function()" instead of the preferred "Use function() instead." Add it in if missing.
		if ( false === strpos( $deprecation_info, 'instead' ) ) {
			$deprecation_info = rtrim( $deprecation_info, '. ' );
			$deprecation_info .= ' instead.'; // Not making translatable since rest of string is not translatable.
		}
	}

	switch ( $type ) {
		case 'class':
			$contents = __( 'This class has been deprecated.', 'super-awesome-theme' );
			break;
		case 'trait':
			$contents = __( 'This trait has been deprecated.', 'super-awesome-theme' );
			break;
		case 'interface':
			$contents = __( 'This interface has been deprecated.', 'super-awesome-theme' );
			break;
		case 'function':
			$contents = __( 'This function has been deprecated.', 'super-awesome-theme' );
			break;
		case 'method':
			$contents = __( 'This method has been deprecated.', 'super-awesome-theme' );
			break;
		case 'hook':
			$contents = __( 'This hook has been deprecated.', 'super-awesome-theme' );
			break;
		default:
			$contents = __( 'This has been deprecated.', 'super-awesome-theme' );
	}

	if ( ! empty( $deprecation_info ) ) {
		$contents .= ' ' . $deprecation_info;
	}

	if ( true === $formatted ) {
		$message  = '<div class="deprecated notice notice-warning">';
		/** This filter is documented in wp-includes/post-template.php */
		$message .= apply_filters( 'the_content', $contents );
		$message .= '</div>';
	} else {
		$message = $contents;
	}

	return $message;
}

/**
 * Gets a private access message as a string.
 *
 * @since 1.0.0
 *
 * @param int|WP_Post|null $post Optional. Post object. Default is the current post.
 * @return string Private access message, or empty string if not applicable.
 */
function super_awesome_theme_devhub_get_private_access_message( $post = null ) {
	$post = get_post( $post );

	$tags = get_post_meta( $post->ID, '_wp-parser_tags', true );
	if ( empty( $tags ) ) {
		return '';
	}

	$access_tags = wp_filter_object_list( $tags, array(
		'name'    => 'access',
		'content' => 'private',
	) );
	if ( empty( $access_tags ) && 'private' !== get_post_meta( $post->ID, '_wp-parser_visibility', true ) ) {
		return '';
	}

	$referral = wp_filter_object_list( $tags, array( 'name' => 'see' ) );
	$referral = array_shift( $referral );

	if ( ! empty( $referral['refers'] ) ) {
		$refers = sanitize_text_field( $referral['refers'] );

		if ( ! empty( $refers ) ) {
			/* translators: 1: Linked internal element name */
			$alternative_string = sprintf( __( 'Use %s instead.', 'super-awesome-theme' ), super_awesome_theme_devhub_resolve_internal_link( $refers ) );
		}
	} else {
		$alternative_string = '';
	}

	$contents = __( 'This function&#8217;s access is marked private. This means it is not intended for use by other plugin or theme developers, only in this plugin itself. It is listed here for completeness.', 'super-awesome-theme' );
	if ( ! empty( $alternative_string ) ) {
		$contents .= ' ' . $alternative_string;
	}

	$message  = '<div class="private-access notice notice-error">';
	/** This filter is documented in wp-includes/post-template.php */
	$message .= apply_filters( 'the_content', $contents );
	$message .= '</div>';

	return $message;
}

/**
 * Gets the posts that the specified post uses.
 *
 * @since 1.0.0
 *
 * @param int|WP_Post|null $post Optional. Post object. Default is the current post.
 * @return array Array of post objects.
 */
function super_awesome_theme_devhub_get_uses( $post = null ) {
	if ( ! function_exists( 'p2p_type' ) ) {
		return array();
	}

	$post = get_post( $post );

	switch ( $post->post_type ) {
		case 'wp-parser-class':
			$connection_types = array( 'classes_to_traits' );
			break;
		case 'wp-parser-function':
			$connection_types = array( 'functions_to_functions', 'functions_to_methods', 'functions_to_hooks' );
			break;
		case 'wp-parser-method':
			$connection_types = array( 'methods_to_functions', 'methods_to_methods', 'methods_to_hooks' );
			break;
		default:
			$connection_types = array();
	}

	$connection_types = array_filter( $connection_types, 'p2p_type' );

	if ( empty( $connection_types ) ) {
		return array();
	}

	return get_posts( array(
		'post_type'           => super_awesome_theme_devhub_get_post_types(),
		'connected_type'      => $connection_types,
		'connected_direction' => array_fill( 0, count( $connection_types ), 'from' ),
		'connected_items'     => $post->ID,
		'suppress_filters'    => false,
		'nopaging'            => true,
	) );
}

/**
 * Gets the posts that the specified post extends.
 *
 * @since 1.0.0
 *
 * @param int|WP_Post|null $post Optional. Post object. Default is the current post.
 * @return array Array of post objects.
 */
function super_awesome_theme_devhub_get_extends( $post = null ) {
	if ( ! function_exists( 'p2p_type' ) ) {
		return array();
	}

	$post = get_post( $post );

	if ( 'wp-parser-class' !== $post->post_type ) {
		return array();
	}

	return get_posts( array(
		'post_type'           => 'wp-parser-class',
		'connected_type'      => 'classes_to_classes',
		'connected_direction' => 'from',
		'connected_items'     => $post->ID,
		'suppress_filters'    => false,
		'nopaging'            => true,
	) );
}

/**
 * Gets the posts that the specified post implements.
 *
 * @since 1.0.0
 *
 * @param int|WP_Post|null $post Optional. Post object. Default is the current post.
 * @return array Array of post objects.
 */
function super_awesome_theme_devhub_get_implements( $post = null ) {
	if ( ! function_exists( 'p2p_type' ) ) {
		return array();
	}

	$post = get_post( $post );

	if ( 'wp-parser-class' !== $post->post_type ) {
		return array();
	}

	return get_posts( array(
		'post_type'           => 'wp-parser-interface',
		'connected_type'      => 'classes_to_interfaces',
		'connected_direction' => 'from',
		'connected_items'     => $post->ID,
		'suppress_filters'    => false,
		'nopaging'            => true,
	) );
}

/**
 * Gets the posts that use the specified post.
 *
 * @since 1.0.0
 *
 * @param int|WP_Post|null $post Optional. Post object. Default is the current post.
 * @return array Array of post objects.
 */
function super_awesome_theme_devhub_get_used_by( $post = null ) {
	if ( ! function_exists( 'p2p_type' ) ) {
		return array();
	}

	$post = get_post( $post );

	switch ( $post->post_type ) {
		case 'wp-parser-trait':
			$connection_types = array( 'classes_to_traits' );
			break;
		case 'wp-parser-function':
			$connection_types = array( 'functions_to_functions', 'methods_to_functions' );
			break;
		case 'wp-parser-method':
			$connection_types = array( 'functions_to_methods', 'methods_to_methods' );
			break;
		case 'wp-parser-hook':
			$connection_types = array( 'functions_to_hooks', 'methods_to_hooks' );
			break;
		default:
			$connection_types = array();
	}

	$connection_types = array_filter( $connection_types, 'p2p_type' );

	if ( empty( $connection_types ) ) {
		return array();
	}

	return get_posts( array(
		'post_type'           => super_awesome_theme_devhub_get_post_types(),
		'connected_type'      => $connection_types,
		'connected_direction' => array_fill( 0, count( $connection_types ), 'to' ),
		'connected_items'     => $post->ID,
		'suppress_filters'    => false,
		'nopaging'            => true,
	) );
}

/**
 * Gets the posts that extend the specified post.
 *
 * @since 1.0.0
 *
 * @param int|WP_Post|null $post Optional. Post object. Default is the current post.
 * @return array Array of post objects.
 */
function super_awesome_theme_devhub_get_extended_by( $post = null ) {
	if ( ! function_exists( 'p2p_type' ) ) {
		return array();
	}

	$post = get_post( $post );

	if ( 'wp-parser-class' !== $post->post_type ) {
		return array();
	}

	return get_posts( array(
		'post_type'           => 'wp-parser-class',
		'connected_type'      => 'classes_to_classes',
		'connected_direction' => 'to',
		'connected_items'     => $post->ID,
		'suppress_filters'    => false,
		'nopaging'            => true,
	) );
}

/**
 * Gets the posts that implement the specified post.
 *
 * @since 1.0.0
 *
 * @param int|WP_Post|null $post Optional. Post object. Default is the current post.
 * @return array Array of post objects.
 */
function super_awesome_theme_devhub_get_implemented_by( $post = null ) {
	if ( ! function_exists( 'p2p_type' ) ) {
		return array();
	}

	$post = get_post( $post );

	if ( 'wp-parser-interface' !== $post->post_type ) {
		return array();
	}

	return get_posts( array(
		'post_type'           => 'wp-parser-class',
		'connected_type'      => 'classes_to_interfaces',
		'connected_direction' => 'to',
		'connected_items'     => $post->ID,
		'suppress_filters'    => false,
		'nopaging'            => true,
	) );
}

/**
 * Gets the methods that are part of a post.
 *
 * @since 1.0.0
 *
 * @param int|WP_Post|null $post Optional. Post object. Default is the current post.
 * @return array Array of post objects.
 */
function super_awesome_theme_devhub_get_methods( $post = null ) {
	$post = get_post( $post );

	if ( ! in_array( $post->post_type, array( 'wp-parser-class', 'wp-parser-trait', 'wp-parser-interface' ), true ) ) {
		return array();
	}

	return get_children( array(
		'post_parent' => $post->ID,
		'post_type'   => 'wp-parser-method',
		'post_status' => 'publish',
		'orderby'     => 'post_name',
		'order'       => 'ASC',
	) );
}

/**
 * Gets the specific type of a hook.
 *
 * @since 1.0.0
 *
 * @param int|WP_Post|null $post Optional. Post ID or post object. Default is the current post.
 * @return string Either 'action', 'filter', or an empty string if not a hook post type.
 */
function super_awesome_theme_devhub_get_hook_type( $post = null ) {
	$post = get_post( $post );

	$hook = '';
	if ( 'wp-parser-hook' === $post->post_type ) {
		$hook = get_post_meta( $post->ID, '_wp-parser_hook_type', true );
	}

	return $hook;
}

/**
 * Gets the source code for a DevHub post.
 *
 * @since 1.0.0
 *
 * @param int|WP_Post|null $post        Optional. Post ID or post object. Default is the current post.
 * @param bool             $force_parse Optional. Whether to force reparsing the source code. Default false.
 * @return string Source code, or empty string if not available.
 */
function super_awesome_theme_devhub_get_source_code( $post = null, $force_parse = false ) {
	$post = get_post( $post );

	$meta_key = '_wp-parser_source_code';

	if ( ! $force_parse ) {
		$source_code = get_post_meta( $post->ID, $meta_key, true );
		if ( $source_code ) {
			return $source_code;
		}
	}

	$source_file = super_awesome_theme_devhub_get_source_file( $post );
	$start_line  = (int) get_post_meta( $post->ID, '_wp-parser_line_num', true ) - 1;
	$end_line    = (int) get_post_meta( $post->ID, '_wp-parser_end_line_num', true );

	if ( ! $source_file || ! $start_line || ! $end_line || $start_line > $end_line ) {
		return '';
	}

	$source_root_dir = get_option( 'wp_parser_root_import_dir' );
	if ( ! $source_root_dir ) {
		return '';
	}

	$source_code = '';

	$handle = @fopen( trailingslashit( $source_root_dir ) . $source_file, 'r' );
	if ( $handle ) {
		$line = -1;
		while ( ! feof( $handle ) ) {
			$line++;
			$source_line = fgets( $handle );

			// Stop reading file once end_line is reached.
			if ( $line >= $end_line ) {
				break;
			}

			// Skip lines until start_line is reached.
			if ( $line < $start_line ) {
				continue;
			}

			$source_code .= $source_line;
		}
		fclose( $handle );
	}

	update_post_meta( $post->ID, $meta_key, addslashes( $source_code ) );

	return $source_code;
}

/**
 * Gets the link to the source file.
 *
 * @since 1.0.0
 *
 * @param int|WP_Post|null $post        Optional. Post ID or post object. Default is the current post.
 * @param bool             $line_number Optional. Whether to link to the specific line number. Default true.
 * @return string Source file link, or empty string if not found.
 */
function super_awesome_theme_devhub_get_source_file_link( $post = null, $line_number = true ) {
	$github_repository = get_theme_mod( 'devhub_github_repository', '' );
	if ( ! $github_repository ) {
		return '';
	}

	$source_file = super_awesome_theme_devhub_get_source_file( $post );
	if ( empty( $source_file ) ) {
		return '';
	}

	$github_root_directory = trim( get_theme_mod( 'devhub_github_root_dir', '' ), '/' );
	if ( ! empty( $github_root_directory ) ) {
		$github_root_directory .= '/';
	}

	$project_version = get_theme_mod( 'devhub_project_version', '' );

	if ( ! empty( $project_version ) ) {
		$url = 'https://github.com/' . trim( $github_repository, '/' ) . '/tree/' . $project_version . '/' . $github_root_directory . $source_file;
	} else {
		$url = 'https://github.com/' . trim( $github_repository, '/' ) . '/tree/master/' . $github_root_directory . $source_file;
	}

	if ( $line_number ) {
		$line_number = super_awesome_theme_devhub_get_line_number( $post );
		if ( $line_number ) {
			$url .= '#L' . $line_number;
		}
	}

	return $url;
}

/**
 * Gets the name of the source file.
 *
 * @since 1.0.0
 *
 * @param int|WP_Post|null $post Optional. Post ID or post object. Default is the current post.
 * @return string Source file name, or empty string if not found.
 */
function super_awesome_theme_devhub_get_source_file( $post = null ) {
	$post = get_post( $post );

	$source_files = wp_get_post_terms( $post->ID, 'wp-parser-source-file', array(
		'fields' => 'names',
	) );

	if ( empty( $source_files ) ) {
		return '';
	}

	return array_shift( $source_files );
}

/**
 * Gets the line number.
 *
 * @since 1.0.0
 *
 * @param int|WP_Post|null $post Optional. Post ID or post object. Default is the current post.
 * @return string Line number as numeric string, or empty string if none available.
 */
function super_awesome_theme_devhub_get_line_number( $post = null ) {
	$post = get_post( $post );

	return get_post_meta( $post->ID, '_wp-parser_line_num', true );
}

/**
 * Gets the archive link to the source file.
 *
 * @since 1.0.0
 *
 * @param int|WP_Post|null $post Optional. Post ID or post object. Default is the current post.
 * @return string Source file archive link, or empty string if not found.
 */
function super_awesome_theme_devhub_get_source_file_archive_link( $post = null ) {
	$post = get_post( $post );

	$source_files = wp_get_post_terms( $post->ID, 'wp-parser-source-file' );

	if ( empty( $source_files ) ) {
		return '';
	}

	$link = get_term_link( array_shift( $source_files ) );
	if ( ! $link || is_wp_error( $link ) ) {
		return '';
	}

	return $link;
}

/**
 * Gets the archive link for the since annotation.
 *
 * @since 1.0.0
 *
 * @param int|WP_Post|null $post Optional. Post ID or post object. Default is the current post.
 * @return string since archive link, or empty string if not found.
 */
function super_awesome_theme_devhub_get_since_archive_link( $post = null ) {
	$post = get_post( $post );

	$since = wp_get_post_terms( $post->ID, 'wp-parser-since' );

	if ( empty( $since ) ) {
		return '';
	}

	$link = get_term_link( array_shift( $since ) );
	if ( ! $link || is_wp_error( $link ) ) {
		return '';
	}

	return $link;
}

/**
 * Gets the archive link for the package.
 *
 * @since 1.0.0
 *
 * @param int|WP_Post|null $post Optional. Post ID or post object. Default is the current post.
 * @return string Package archive link, or empty string if not found.
 */
function super_awesome_theme_devhub_get_package_archive_link( $post = null ) {
	$post = get_post( $post );

	$packages = wp_get_post_terms( $post->ID, 'wp-parser-package' );

	if ( empty( $packages ) ) {
		return '';
	}

	$link = get_term_link( array_shift( $packages ) );
	if ( ! $link || is_wp_error( $link ) ) {
		return '';
	}

	return $link;
}

/**
 * Gets the archive link for the namespace.
 *
 * @since 1.0.0
 *
 * @param int|WP_Post|null $post Optional. Post ID or post object. Default is the current post.
 * @return string Namespace archive link, or empty string if not found.
 */
function super_awesome_theme_devhub_get_namespace_archive_link( $post = null ) {
	$post = get_post( $post );

	$namespaces = wp_get_post_terms( $post->ID, 'wp-parser-namespace' );

	if ( empty( $namespaces ) ) {
		return '';
	}

	$link = get_term_link( array_shift( $namespaces ) );
	if ( ! $link || is_wp_error( $link ) ) {
		return '';
	}

	return $link;
}

/**
 * Gets the namespace objects.
 *
 * @since 1.0.0
 *
 * @param int|WP_Post|null $post     Optional. Post ID or post object. Default is the current post.
 * @param bool             $sorted   Optional. Whether the namespaces should return in a sorted way. Default false.
 * @param bool             $relative Optional. Whether to only include namespaces relative to the current one.
 *                                   This only takes effect if the current request is for a namespace archive
 *                                   and $sorted is true. Default false.
 * @return array Array of term objects.
 */
function super_awesome_theme_devhub_get_namespaces( $post = null, $sorted = false, $relative = false ) {
	$namespaces = wp_get_post_terms( $post->ID, 'wp-parser-namespace' );
	if ( empty( $namespaces ) || is_wp_error( $namespaces ) ) {
		return array();
	}

	if ( ! $sorted ) {
		return $namespaces;
	}

	$result    = array();
	$ids       = array();
	$reference = 0;

	// Sort namespaces so that parents go before their children.
	while ( ! empty( $namespaces ) ) {
		$remove = array();
		foreach ( $namespaces as $index => $namespace ) {
			if ( $namespace->parent === $reference ) {
				$result[]  = $namespace;
				$ids[]     = $namespace->term_id;
				$reference = $namespace->term_id;
				$remove[]  = $index;
			}
		}

		if ( ! empty( $remove ) ) {
			foreach ( $remove as $rem ) {
				unset( $namespaces[ $rem ] );
			}
		} else {
			break;
		}
	}

	if ( $relative && ! empty( $result ) && is_tax( 'wp-parser-namespace' ) ) {
		$index = array_search( (int) get_queried_object_id(), $ids, true );
		if ( false !== $index ) {
			$result = array_slice( $result, $index + 1 );
		}
	}

	return $result;
}

/**
 * Gets the full namespace.
 *
 * @since 1.0.0
 *
 * @param int|WP_Post|null $post          Optional. Post ID or post object. Default is the current post.
 * @param bool             $include_links Optional. Whether to include archive links for each namespace part. Default false.
 * @param bool             $relative      Optional. Whether to only include the namespace relative to the current one.
 *                                        This only takes effect if the current request is for a namespace archive.
 *                                        Default false.
 * @return string Full namespace including links.
 */
function super_awesome_theme_devhub_get_namespace( $post = null, $include_links = false, $relative = false ) {
	$post = get_post( $post );

	$namespaces = super_awesome_theme_devhub_get_namespaces( $post, true, $relative );

	if ( empty( $namespaces ) ) {
		return '';
	}

	$full_namespace = array();
	foreach ( $namespaces as $namespace ) {
		if ( $include_links ) {
			$full_namespace[] = '<a href="' . esc_url( get_term_link( $namespace ) ) . '">' . esc_html( $namespace->name ) . '</a>';
		} else {
			$full_namespace[] = esc_html( $namespace->name );
		}
	}

	return '<div class="namespace">' . implode( '\\', $full_namespace ) . '</div>';
}
