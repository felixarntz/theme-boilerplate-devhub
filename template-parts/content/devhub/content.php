<?php
/**
 * Template part for displaying a DevHub post
 *
 * @package Super_Awesome_Theme
 * @license GPL-2.0-or-later
 * @link    https://super-awesome-author.org/themes/super-awesome-theme/
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php get_template_part( 'template-parts/content/devhub/entry-header', get_post_type() ); ?>

	<div class="entry-content">

		<?php get_template_part( 'template-parts/content/devhub/template-summary', get_post_type() ); ?>

		<?php if ( is_singular() ) : ?>
			<div class="devhub-content-wrap">
				<div class="devhub-content-sidebar">

				</div>
				<div class="devhub-content-main">
					<h2><?php esc_html_e( 'Description', 'super-awesome-theme' ); ?></h2>
					<?php

					get_template_part( 'template-parts/content/devhub/template-description', get_post_type() );
					get_template_part( 'template-parts/content/devhub/template-params', get_post_type() );
					get_template_part( 'template-parts/content/devhub/template-return', get_post_type() );
					get_template_part( 'template-parts/content/devhub/template-source', get_post_type() );
					get_template_part( 'template-parts/content/devhub/template-changelog', get_post_type() );
					get_template_part( 'template-parts/content/devhub/template-related', get_post_type() );
					get_template_part( 'template-parts/content/devhub/template-methods', get_post_type() );

					?>
				</div>
			</div>
		<?php endif; ?>

	</div>

	<?php get_template_part( 'template-parts/content/devhub/entry-footer', get_post_type() ); ?>

</article><!-- #post-<?php the_ID(); ?> -->
