<?php
/**
 * DevHub Template: Source
 *
 * @package Super_Awesome_Theme
 * @license GPL-2.0-or-later
 * @link    https://super-awesome-author.org/themes/super-awesome-theme/
 */

$source_file  = super_awesome_theme_devhub_get_source_file();
$archive_link = super_awesome_theme_devhub_get_source_file_archive_link();
$file_link    = super_awesome_theme_devhub_get_source_file_link();
$source_code  = 'wp-parser-hook' !== get_post_type() ? super_awesome_theme_devhub_get_source_code() : '';
$line_number  = super_awesome_theme_devhub_get_line_number();

if ( empty( $source_file ) || empty( $archive_link ) ) {
	return;
}

?>

<div class="source-content">
	<h3><?php esc_html_e( 'Source', 'super-awesome-theme' ); ?></h3>

	<p>
		<?php esc_html_e( 'File:', 'super-awesome-theme' ); ?>
		<a href="<?php echo esc_url( $archive_link ); ?>"><?php echo esc_html( $source_file ); ?></a>
	</p>

	<?php if ( ! empty( $source_code ) ) : ?>
		<div class="source-code-container">
			<pre class="brush: php; toolbar: false; first-line: <?php echo esc_attr( $line_number ); ?>"><?php echo htmlentities( $source_code ); // WPCS: XSS OK. ?></pre>
		</div>
		<p class="source-code-links">
			<span>
				<button type="button" class="show-complete-source button button-link"><?php esc_html_e( 'Expand full source code', 'super-awesome-theme' ); ?></button>
				<button type="button" class="less-complete-source button button-link"><?php esc_html_e( 'Collapse full source code', 'super-awesome-theme' ); ?></button>
			</span>
			<?php if ( ! empty( $file_link ) ) : ?>
				<span>
					<a href="<?php echo esc_url( $file_link ); ?>" class="button button-link"><?php esc_html_e( 'View on GitHub', 'super-awesome-theme' ); ?></a>
				</span>
			<?php endif; ?>
		</p>
	<?php elseif ( ! empty( $file_link ) ) : ?>
		<p>
			<a href="<?php echo esc_url( $file_link ); ?>"><?php esc_html_e( 'View on GitHub', 'super-awesome-theme' ); ?></a>
		</p>
	<?php endif; ?>
</div>
