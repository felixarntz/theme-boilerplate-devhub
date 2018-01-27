<?php
/**
 * DevHub Template: Return
 *
 * @package Super_Awesome_Theme
 * @license GPL-2.0-or-later
 * @link    https://super-awesome-author.org/themes/super-awesome-theme/
 */

$return = super_awesome_theme_devhub_get_return();

if ( empty( $return ) ) {
	return;
}

?>
<div class="return">
	<h3><?php esc_html_e( 'Return', 'super-awesome-theme' ); ?></h3>
	<p><?php echo $return; // WPCS: XSS OK. ?></p>
</div>
