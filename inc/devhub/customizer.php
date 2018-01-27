<?php
/**
 * DevHub: Customizer
 *
 * @package Super_Awesome_Theme
 * @license GPL-2.0-or-later
 * @link    https://super-awesome-author.org/themes/super-awesome-theme/
 */

/**
 * Registers DevHub Customizer functionality.
 *
 * @since 1.0.0
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function super_awesome_theme_devhub_customize_register( $wp_customize ) {
	$wp_customize->add_section( 'devhub', array(
		'title'           => __( 'DevHub', 'super-awesome-theme' ),
		'priority'        => 200,
	) );

	$wp_customize->add_setting( 'devhub_use_prefix', array(
		'default'           => true,
		'transport'         => 'refresh',
	) );
	$wp_customize->add_control( 'devhub_use_prefix', array(
		'section'         => 'devhub',
		'label'           => __( 'Use URL Prefix for Reference?', 'super-awesome-theme' ),
		/* translators: %s: URL fragment */
		'description'     => sprintf( __( 'If this checkbox is enabled, a &#8220;%s&#8221; prefix is added to all DevHub reference URLs.', 'super-awesome-theme' ), _x( 'reference', 'rewrite slug', 'super-awesome-theme' ) ),
		'type'            => 'checkbox',
	) );

	$wp_customize->add_setting( 'devhub_root_namespace', array(
		'default'           => '',
		'transport'         => 'refresh',
	) );
	$wp_customize->add_control( 'devhub_root_namespace', array(
		'section'         => 'devhub',
		'label'           => __( 'Root Namespace', 'super-awesome-theme' ),
		'description'     => __( 'If the source code was parsed with a root namespace to exclude, enter that namespace here.', 'super-awesome-theme' ),
		'type'            => 'text',
	) );

	$wp_customize->add_setting( 'devhub_github_repository', array(
		'default'           => '',
		'transport'         => 'refresh',
	) );
	$wp_customize->add_control( 'devhub_github_repository', array(
		'section'         => 'devhub',
		'label'           => __( 'GitHub Repository', 'super-awesome-theme' ),
		'description'     => __( 'Enter the GitHub repository for the source code, in <code>vendor/project</code> format.', 'super-awesome-theme' ),
		'type'            => 'text',
	) );

	$wp_customize->add_setting( 'devhub_github_root_dir', array(
		'default'           => '',
		'transport'         => 'refresh',
	) );
	$wp_customize->add_control( 'devhub_github_root_dir', array(
		'section'         => 'devhub',
		'label'           => __( 'GitHub Root Directory', 'super-awesome-theme' ),
		'description'     => __( 'If the source code was not generated from the entire GitHub repository, enter the name of the relative directory which was parsed.', 'super-awesome-theme' ),
		'type'            => 'text',
	) );

	$wp_customize->add_setting( 'devhub_project_version', array(
		'default'           => '',
		'transport'         => 'refresh',
	) );
	$wp_customize->add_control( 'devhub_project_version', array(
		'section'         => 'devhub',
		'label'           => __( 'Project Version', 'super-awesome-theme' ),
		'description'     => __( 'Enter the project version for the source code. A tag of the same version should exist in the GitHub repository provided above.', 'super-awesome-theme' ),
		'type'            => 'text',
	) );
}
add_action( 'customize_register', 'super_awesome_theme_devhub_customize_register' );
