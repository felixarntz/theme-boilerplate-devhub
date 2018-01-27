<?php
/**
 * DevHub: Formatting
 *
 * @package Super_Awesome_Theme
 * @license GPL-2.0-or-later
 * @link    https://super-awesome-author.org/themes/super-awesome-theme/
 */

/**
 * Handles formatting of a summary.
 *
 * @since 1.0.0
 *
 * @param string $text Original summary.
 * @return string Formatted summary.
 */
function super_awesome_theme_devhub_format_summary( $text ) {
	// Backticks in excerpts are not automatically wrapped in code tags, so do so.
	if ( false !== strpos( $text, '`' ) ) {
		$text = preg_replace_callback( '/`([^`]*)`/', 'super_awesome_theme_devhub_format_summary_replace_backticks_callback', $text );
	}

	// Fix https://developer.wordpress.org/reference/functions/get_extended/
	// until the 'more' delimiter in summary is backticked.
	$text = str_replace( array( '<!--', '-->' ), array( '<code>&lt;!--', '--&gt;</code>' ), $text );

	// Fix standalone HTML tags that were not backticked.
	if ( false !== strpos( $text, '<' ) ) {
		$text = preg_replace_callback( '/(\s)(<[^ >]+>)(\s)/', 'super_awesome_theme_devhub_format_summary_replace_tags_callback', $text );
	}

	return $text;
}

/**
 * Handles formatting of a parameter description.
 *
 * @since 1.0.0
 *
 * @param string $text Original parameter description.
 * @return string Formatted parameter description.
 */
function super_awesome_theme_devhub_format_param_description( $text ) {
	// Undo parser's Markdown conversion of '*' to `<em>` and `</em>`.
	// In pretty much all cases, the docs mean literal '*' and never emphasis.
	$text = str_replace( array( '<em>', '</em>' ), '*', $text );

	// Encode all htmlentities (but don't double-encode).
	if ( version_compare( phpversion(), '5.4.0', '>=' ) ) {
		// @codingStandardsIgnoreStart
		$text = htmlentities( $text, ENT_COMPAT | ENT_HTML401, 'UTF-8', false );
		// @codingStandardsIgnoreEnd
	} else {
		$text = htmlentities( $text, ENT_COMPAT, 'UTF-8' );
	}

	// Simple allowable tags that should get unencoded.
	// Note: This precludes them from being able to be used in an encoded fashion
	// within a parameter description.
	$allowable_tags = array( 'code' );
	foreach ( $allowable_tags as $tag ) {
		$text = str_replace( array( "&lt;{$tag}&gt;", "&lt;/{$tag}&gt;" ), array( "<{$tag}>", "</{$tag}>" ), $text );
	}

	// Convert asterisks to a list.
	// Inline lists in param descriptions aren't handled by parser.
	if ( false !== strpos( $text, ' * ' ) ) {
		// Display as simple plaintext list.
		$text = str_replace( ' * ', '<br /> * ', $text );
	}

	$text = super_awesome_theme_devhub_make_doclink_clickable( $text );
	$text = super_awesome_theme_devhub_fix_param_hash_formatting( $text );

	return $text;
}

/**
 * Makes phpDoc @see and @link references clickable.
 *
 * Handles these six different types of links:
 *
 * - {@link http://en.wikipedia.org/wiki/ISO_8601}
 * - {@see WP_Rewrite::$index}
 * - {@see WP_Query::query()}
 * - {@see esc_attr()}
 * - {@see 'pre_get_search_form'}
 * - {@link http://codex.wordpress.org/The_Loop Use new WordPress Loop}
 *
 * Note: Though @see and @link are semantically different in meaning, that isn't always
 * the case with use so this function handles them identically.
 *
 * @since 1.0.0
 *
 * @param string $content The content to modify.
 * @return string The modified content.
 */
function super_awesome_theme_devhub_make_doclink_clickable( $content ) {
	if ( false === strpos( $content, '{@link ' ) && false === strpos( $content, '{@see ' ) ) {
		return $content;
	}

	return preg_replace_callback( '/\{@(?:link|see) ([^\}]+)\}/', 'super_awesome_theme_devhub_make_doclink_clickable_replace_callback', $content );
}

/**
 * Internal replace callback used by super_awesome_theme_devhub_format_summary().
 *
 * @since 1.0.0
 * @access private
 *
 * @param array $matches Regular expression matches.
 * @return string Replacement for the match.
 */
function super_awesome_theme_devhub_format_summary_replace_backticks_callback( $matches ) {
	return '<code>' . htmlentities( $matches[1] ) . '</code>';
}

/**
 * Internal replace callback used by super_awesome_theme_devhub_format_summary().
 *
 * @since 1.0.0
 * @access private
 *
 * @param array $matches Regular expression matches.
 * @return string Replacement for the match.
 */
function super_awesome_theme_devhub_format_summary_replace_tags_callback( $matches ) {
	return $matches[1] . '<code>' . htmlentities( $matches[2] ) . '</code>' . $matches[3];
}

/**
 * Internal replace callback used by super_awesome_theme_devhub_make_doclink_clickable().
 *
 * @since 1.0.0
 * @access private
 *
 * @param array $matches Regular expression matches.
 * @return string Replacement for the match.
 */
function super_awesome_theme_devhub_make_doclink_clickable_replace_callback( $matches ) {
	$link = $matches[1];

	// We may have encoded a link, so unencode if so.
	if ( 0 === strpos( $link, '&lt;a ' ) ) {
		$link = html_entity_decode( $link );
	}

	// Undo links made clickable during initial parsing.
	if ( 0 === strpos( $link, '<a ' ) ) {
		if ( preg_match( '/^<a .*href=[\'\"]([^\'\"]+)[\'\"]>(.*)<\/a>(.*)$/', $link, $parts ) ) {
			$link = $parts[1];
			if ( $parts[3] ) {
				$link .= ' ' . $parts[3];
			}
		}
	}

	if ( 0 === strpos( $link, 'https://' ) || 0 === strpos( $link, 'http://' ) ) { // Link to an external resource.
		$parts = explode( ' ', $link, 2 );

		if ( 1 === count( $parts ) ) {
			// Link without linked text: {@link http://en.wikipedia.org/wiki/ISO_8601}.
			$link = '<a href="' . esc_url( $link ) . '">' . esc_html( $link ) . '</a>';
		} else {
			// Link with linked text: {@link http://codex.wordpress.org/The_Loop Use new WordPress Loop}.
			$link = '<a href="' . esc_url( $parts[0] ) . '">' . esc_html( $parts[1] ) . '</a>';
		}
	} else { // Link to an internal resource.
		$link = super_awesome_theme_devhub_resolve_internal_link( $link );
	}

	return $link;
}

/**
 * Attempts to resolve an internal link to DevHub content.
 *
 * @since 1.0.0
 * @access private
 *
 * @param string $link Content to resolve as link.
 * @return string Link tag, or unmodified content if nothing could be resolved.
 */
function super_awesome_theme_devhub_resolve_internal_link( $link ) {
	$post_types = array();
	$slug       = '';
	$url        = '';

	if ( false !== strpos( $link, '::$' ) ) {
		// Link to class variable: {@see WP_Rewrite::$index}.
		return $link;
	} elseif ( false !== strpos( $link, '::' ) ) {
		// Link to class method: {@see WP_Query::query()}.
		$post_types = array( 'wp-parser-method' );
		$slug       = str_replace( array( '::', '()' ), array( '/', '' ), $link );
	} elseif ( 1 === preg_match( '/^(?:\'|(?:&#8216;))([\$\w-&;]+)(?:\'|(?:&#8217;))$/', $link, $hook ) ) {
		// Link to hook: {@see 'pre_get_search_form'}.
		$post_types = array( 'wp-parser-hook' );
		if ( ! empty( $hook[1] ) ) {
			$slug = sanitize_title_with_dashes( html_entity_decode( $hook[1] ) );
		}
	} elseif ( 1 === preg_match( '/^_?[A-Z][a-zA-Z0-9]+_\w+/', $link ) ) {
		// Link to class, trait or interface: {@see WP_Query}.
		$post_types = array( 'wp-parser-class', 'wp-parser-trait', 'wp-parser-interface' );
		$slug       = sanitize_key( $link );
	} else {
		// Link to function: {@see esc_attr()}.
		$post_types = array( 'wp-parser-function' );
		$slug       = sanitize_title_with_dashes( html_entity_decode( $link ) );
	}

	if ( ! empty( $post_types ) && ! empty( $slug ) ) {
		/**
		 * Filters whether to perform possibly heavy lookups for content to ensure internal links are resolved correctly.
		 *
		 * @since 1.0.0
		 *
		 * @param bool $strict_resolve Whether to do lookups to resolve internal links strictly. Default true.
		 */
		if ( apply_filters( 'super_awesome_theme_devhub_do_resolve_internal_links_strict', true ) ) {
			$url = super_awesome_theme_devhub_detect_internal_link_content( $slug, $post_types );
		} else {
			if ( count( $post_types ) === 1 && in_array( $post_types[0], array( 'wp-parser-function', 'wp-parser-hook' ), true ) ) {
				$url = get_post_type_archive_link( $post_types[0] ) . $slug . '/';
			}
		}
	}

	if ( ! empty( $url ) ) {
		$link = '<a href="' . esc_url( $url ) . '">' . esc_html( $link ) . '</a>';
	}

	return $link;
}

/**
 * Attempts to get the permalink for DevHub content of a certain slug.
 *
 * Since this method is relatively performance heavy if executed often, it should be used with caution.
 *
 * @since 1.0.0
 * @access private
 *
 * @param string $slug       Post slug to look for.
 * @param array  $post_types Optional. Post types to include in the lookup. Default all DevHub post types.
 * @return string Permalink for the found content, or empty string if nothing found.
 */
function super_awesome_theme_devhub_detect_internal_link_content( $slug, $post_types = array() ) {
	$post_types = (array) $post_types;
	if ( empty( $post_types ) ) {
		$post_types = super_awesome_theme_devhub_get_post_types();
	}

	$posts = get_posts( array(
		'post_name'   => $slug,
		'post_type'   => $post_types,
		'post_status' => 'publish',
		'numberposts' => 1,
	) );

	if ( empty( $posts ) ) {
		return '';
	}

	return get_permalink( array_shift( $posts ) );
}

/**
 * Formats the output of params defined using hash notation.
 *
 * This is a temporary measure until the parser parses the hash notation
 * into component elements that the theme could then handle and style
 * properly.
 *
 * Also, as a stopgap this is going to begin as a barebones hack to simply
 * keep the text looking like one big jumble.
 *
 * @since 1.0.0
 * @access private
 *
 * @param string $text The content for the param.
 * @return string The content with formatting fixed.
 */
function super_awesome_theme_devhub_fix_param_hash_formatting( $text ) {
	// Don't do anything if this isn't a hash notation string.
	if ( ! $text || '{' != $text[0] ) {
		return $text;
	}

	$new_text = '';
	$text     = trim( substr( $text, 1, -1 ) );
	$text     = str_replace( '@type', "\n@type", $text );

	$in_list = false;
	$parts = explode( "\n", $text );
	foreach ( $parts as $part ) {
		$part = preg_replace( '/\s+/', ' ', $part );
		list( $wordtype, $type, $name, $description ) = explode( ' ', $part, 4 );
		$description = trim( $description );

		$skip_closing_li = false;

		// Handle nested hashes.
		if ( ( $description && '{' === $description[0] ) || '{' === $name ) {
			$description = ltrim( $description, '{' ) . '<ul class="param-hash">';
			$skip_closing_li = true;
		} elseif ( '}' === substr( $description, -1 ) ) {
			$description = substr( $description, 0, -1 ) . "</li></ul>\n";
		}

		if ( '@type' != $wordtype ) {
			if ( $in_list ) {
				$in_list = false;
				$new_text .= "</li></ul>\n";
			}

			$new_text .= $part;
		} else {
			if ( $in_list ) {
				$new_text .= '<li>';
			} else {
				$new_text .= '<ul class="param-hash"><li>';
				$in_list = true;
			}

			// Normalize argument name.
			if ( '{' === $name ) {
				// No name is specified, generally indicating an array of arrays.
				$name = '';
			} else {
				// The name is defined as a variable, so remove the leading '$'.
				$name = ltrim( $name, '$' );
			}
			if ( $name ) {
				$new_text .= "<b>'{$name}'</b><br />";
			}
			$new_text .= "<i><span class='type'>({$type})</span></i> {$description}";
			if ( ! $skip_closing_li ) {
				$new_text .= '</li>';
			}
			$new_text .= "\n";
		}
	}

	if ( $in_list ) {
		$new_text .= "</li></ul>\n";
	}

	return $new_text;
}
