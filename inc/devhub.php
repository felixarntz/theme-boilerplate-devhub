<?php
/**
 * DevHub: Integration with PHPDoc Parser
 *
 * @package Super_Awesome_Theme
 * @license GPL-2.0-or-later
 * @link    https://super-awesome-author.org/themes/super-awesome-theme/
 */

if ( class_exists( 'WP_Parser\Plugin' ) ) {
	require get_template_directory() . '/inc/devhub/definitions.php';
	require get_template_directory() . '/inc/devhub/search.php';
	require get_template_directory() . '/inc/devhub/assets.php';
	require get_template_directory() . '/inc/devhub/formatting.php';
	require get_template_directory() . '/inc/devhub/template-hooks.php';
	require get_template_directory() . '/inc/devhub/template-functions.php';
	require get_template_directory() . '/inc/devhub/customizer.php';
}
