<?php
/**
 * DevHub Template: Description
 *
 * @package Super_Awesome_Theme
 * @license GPL-2.0-or-later
 * @link    https://super-awesome-author.org/themes/super-awesome-theme/
 */

$description = super_awesome_theme_devhub_get_description();
$see_tags    = super_awesome_theme_devhub_get_see();

if ( 'wp-parser-method' === get_post_type() && ! empty( $post->post_parent ) ) {
	$see_tags[] = array(
		'refers' => '<a href="' . esc_url( get_permalink( $post->post_parent ) ) . '">' . get_the_title( $post->post_parent ) . '</a>',
	);
}

if ( empty( $description ) && empty( $see_tags ) ) {
	return;
}

?>

<div class="description">
	<?php if ( ! empty( $description ) ) : ?>
		<?php echo $description; // WPCS: XSS OK. ?>
	<?php endif; ?>

	<?php if ( ! empty( $see_tags ) ) : ?>
		<h3><?php esc_html_e( 'See also', 'super-awesome-theme' ); ?></h3>

		<ul>
			<?php foreach ( $see_tags as $see_tag ) : ?>
				<li>
					<?php echo $see_tag['refers'] . ( ! empty( $tag['content'] ) ? ': ' . $tag['content'] : '' ); // WPCS: XSS OK. ?>
				</li>
			<?php endforeach; ?>
		</ul>
	<?php endif; ?>
</div>
