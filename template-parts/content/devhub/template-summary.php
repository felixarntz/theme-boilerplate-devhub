<?php
/**
 * DevHub Template: Summary
 *
 * @package Super_Awesome_Theme
 * @license GPL-2.0-or-later
 * @link    https://super-awesome-author.org/themes/super-awesome-theme/
 */

$summary = super_awesome_theme_devhub_get_summary();

if ( empty( $summary ) ) {
	return;
}

?>

<div class="summary">
	<?php echo $summary; // WPCS: XSS OK. ?>
</div>
