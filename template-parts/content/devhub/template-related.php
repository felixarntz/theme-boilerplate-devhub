<?php
/**
 * DevHub Template: Related
 *
 * @package Super_Awesome_Theme
 * @license GPL-2.0-or-later
 * @link    https://super-awesome-author.org/themes/super-awesome-theme/
 */

$uses       = super_awesome_theme_devhub_get_uses();
$extends    = super_awesome_theme_devhub_get_extends();
$implements = super_awesome_theme_devhub_get_implements();

$used_by        = super_awesome_theme_devhub_get_used_by();
$extended_by    = super_awesome_theme_devhub_get_extended_by();
$implemented_by = super_awesome_theme_devhub_get_implemented_by();

if ( empty( $uses ) && empty( $extends ) && empty( $implements ) && empty( $used_by ) && empty( $extended_by ) && empty( $implemented_by ) ) {
	return;
}

?>

<div class="related">
	<h2><?php esc_html_e( 'Related', 'super-awesome-theme' ); ?></h2>

	<?php if ( ! empty( $uses ) ) : ?>
		<div class="uses">
			<h3><?php esc_html_e( 'Uses', 'super-awesome-theme' ); ?></h3>

			<ul>
				<?php foreach ( $uses as $post ) : ?>
					<li>
						<span><?php echo esc_html( super_awesome_theme_devhub_get_source_file( $post ) ); ?>:</span>
						<a href="<?php echo esc_url( get_permalink( $post ) ); ?>"><?php echo get_the_title(); // WPCS: XSS OK. ?><?php echo in_array( $post->post_type, array( 'wp-parser-function', 'wp-parser-method' ), true ) ? '()' : ''; ?></a>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
	<?php endif; ?>

	<?php if ( ! empty( $extends ) ) : ?>
		<div class="extends">
			<h3><?php esc_html_e( 'Extends', 'super-awesome-theme' ); ?></h3>

			<ul>
				<?php foreach ( $extends as $post ) : ?>
					<li>
						<span><?php echo esc_html( super_awesome_theme_devhub_get_source_file( $post ) ); ?>:</span>
						<a href="<?php echo esc_url( get_permalink( $post ) ); ?>"><?php echo get_the_title(); // WPCS: XSS OK. ?><?php echo in_array( $post->post_type, array( 'wp-parser-function', 'wp-parser-method' ), true ) ? '()' : ''; ?></a>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
	<?php endif; ?>

	<?php if ( ! empty( $implements ) ) : ?>
		<div class="implements">
			<h3><?php esc_html_e( 'Implements', 'super-awesome-theme' ); ?></h3>

			<ul>
				<?php foreach ( $implements as $post ) : ?>
					<li>
						<span><?php echo esc_html( super_awesome_theme_devhub_get_source_file( $post ) ); ?>:</span>
						<a href="<?php echo esc_url( get_permalink( $post ) ); ?>"><?php echo get_the_title(); // WPCS: XSS OK. ?><?php echo in_array( $post->post_type, array( 'wp-parser-function', 'wp-parser-method' ), true ) ? '()' : ''; ?></a>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
	<?php endif; ?>

	<?php if ( ! empty( $used_by ) ) : ?>
		<div class="used-by">
			<h3><?php esc_html_e( 'Used By', 'super-awesome-theme' ); ?></h3>

			<ul>
				<?php foreach ( $used_by as $post ) : ?>
					<li>
						<span><?php echo esc_html( super_awesome_theme_devhub_get_source_file( $post ) ); ?>:</span>
						<a href="<?php echo esc_url( get_permalink( $post ) ); ?>"><?php echo get_the_title(); // WPCS: XSS OK. ?><?php echo in_array( $post->post_type, array( 'wp-parser-function', 'wp-parser-method' ), true ) ? '()' : ''; ?></a>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
	<?php endif; ?>

	<?php if ( ! empty( $extended_by ) ) : ?>
		<div class="extended-by">
			<h3><?php esc_html_e( 'Extended By', 'super-awesome-theme' ); ?></h3>

			<ul>
				<?php foreach ( $extended_by as $post ) : ?>
					<li>
						<span><?php echo esc_html( super_awesome_theme_devhub_get_source_file( $post ) ); ?>:</span>
						<a href="<?php echo esc_url( get_permalink( $post ) ); ?>"><?php echo get_the_title(); // WPCS: XSS OK. ?><?php echo in_array( $post->post_type, array( 'wp-parser-function', 'wp-parser-method' ), true ) ? '()' : ''; ?></a>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
	<?php endif; ?>

	<?php if ( ! empty( $implemented_by ) ) : ?>
		<div class="implemented-by">
			<h3><?php esc_html_e( 'Implemented By', 'super-awesome-theme' ); ?></h3>

			<ul>
				<?php foreach ( $implemented_by as $post ) : ?>
					<li>
						<span><?php echo esc_html( super_awesome_theme_devhub_get_source_file( $post ) ); ?>:</span>
						<a href="<?php echo esc_url( get_permalink( $post ) ); ?>"><?php echo get_the_title(); // WPCS: XSS OK. ?><?php echo in_array( $post->post_type, array( 'wp-parser-function', 'wp-parser-method' ), true ) ? '()' : ''; ?></a>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
	<?php endif; ?>
</div>
