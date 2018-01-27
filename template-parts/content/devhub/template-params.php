<?php
/**
 * DevHub Template: Params
 *
 * @package Super_Awesome_Theme
 * @license GPL-2.0-or-later
 * @link    https://super-awesome-author.org/themes/super-awesome-theme/
 */

$params = super_awesome_theme_devhub_get_params();

if ( empty( $params ) ) {
	return;
}

?>
<div class="parameters">
	<h3><?php esc_html_e( 'Parameters', 'super-awesome-theme' ); ?></h3>

	<dl>
		<?php foreach ( $params as $param ) : ?>
			<?php if ( ! empty( $param['variable'] ) ) : ?>
				<dt><?php echo esc_html( $param['variable'] ); ?></dt>
			<?php endif; ?>
			<dd>
				<p class="desc">
					<?php if ( ! empty( $param['types'] ) ) : ?>
						<span class="type">
							<?php
							/* translators: %s: parameter type */
							printf( esc_html__( '(%s)', 'super-awesome-theme' ), wp_kses_post( $param['types'] ) );
							?>
						</span>
					<?php endif; ?>
					<?php if ( ! empty( $param['required'] ) && 'wp-parser-hook' !== get_post_type() ) : ?>
						<span class="required"><?php esc_html_e( '(Required)', 'super-awesome-theme' ); ?></span>
					<?php else : ?>
						<span class="required"><?php esc_html_e( '(Optional)', 'super-awesome-theme' ); ?></span>
					<?php endif; ?>
					<?php if ( ! empty( $param['content'] ) ) : ?>
						<span class="description"><?php echo wp_kses_post( $param['content'] ); ?></span>
					<?php endif; ?>
				</p>
				<?php if ( ! empty( $param['default'] ) ) : ?>
					<p class="default"><?php esc_html_e( 'Default value:', 'super-awesome-theme' ); ?> <?php echo esc_html( $param['default'] ); ?></p>
				<?php endif; ?>
			</dd>
		<?php endforeach; ?>
	</dl>
</div>
