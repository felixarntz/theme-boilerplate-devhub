<?php
/**
 * Template part for displaying a DevHub post footer
 *
 * @package Super_Awesome_Theme
 * @license GPL-2.0-or-later
 * @link    https://super-awesome-author.org/themes/super-awesome-theme/
 */

$source_file = super_awesome_theme_devhub_get_source_file();
$line_number = super_awesome_theme_devhub_get_line_number();
$source_link = super_awesome_theme_devhub_get_source_file_link();

?>
<footer class="entry-footer">

	<div class="sourcefile">
		<?php esc_html_e( 'Source:', 'super-awesome-theme' ); ?>

		<?php if ( $source_link ) : ?>
			<a href="<?php echo esc_url( $source_link ); ?>"><?php echo esc_html( $source_file . ':' . $line_number ); ?></a>
		<?php else : ?>
			<?php echo esc_html( $source_file . ':' . $line_number ); ?>
		<?php endif; ?>
	</div>

</footer><!-- .entry-footer -->
