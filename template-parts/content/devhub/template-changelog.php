<?php
/**
 * DevHub Template: Changelog
 *
 * @package Super_Awesome_Theme
 * @license GPL-2.0-or-later
 * @link    https://super-awesome-author.org/themes/super-awesome-theme/
 */

$changelog = super_awesome_theme_devhub_get_changelog();

if ( empty( $changelog ) ) {
	return;
}

?>

<div class="changelog">
	<h3><?php esc_html_e( 'Changelog', 'super-awesome-theme' ); ?></h3>

	<table>
		<caption class="screen-reader-text"><?php esc_html_e( 'Changelog', 'super-awesome-theme' ); ?></caption>
		<thead>
			<tr>
				<th class="changelog-version"><?php esc_html_e( 'Version', 'super-awesome-theme' ); ?></th>
				<th class="changelog-desc"><?php esc_html_e( 'Description', 'super-awesome-theme' ); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ( $changelog as $version => $data ) : ?>
				<tr>
					<td><a href="<?php echo esc_url( $data['since_url'] ); ?>"><?php echo esc_html( $version ); ?></a></td>
					<td><?php echo $data['description']; // WPCS: XSS OK. ?></td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>
