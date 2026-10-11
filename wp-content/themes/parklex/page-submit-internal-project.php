<?php
/**
 * Template Name: Submit Internal Project
 */
defined( 'ABSPATH' ) || exit;

acf_form_head();
get_header();
?>

<main class="entry-content is-layout-constrained has-global-padding">
	<div class="c-internal-project-form">
		<?php the_content(); ?>

		<?php
		acf_form( array(
			'post_id'         => 'new_post',
			'field_groups'    => array( 'bisiesto_cpt_project_internal' ),
			'new_post'        => array(
				'post_status' => 'pending',
				'post_type'   => 'project_internal',
			),
			'submit_value'    => get_field( 'submit_button_text', 'option' ) ?: __( 'Submit', 'parklex' ),
			'updated_message' => get_field( 'updated_form_message', 'option' ) ?: __( 'Project successfully created and pending to review.', 'parklex' ),
			'html_updated_message' => '<div id="message" class="c-internal-project-form__notice" role="status" tabindex="-1"><p>%s</p></div>',
			'uploader'        => 'basic',
		) );
		?>
	</div>
</main>

<?php get_footer(); ?>
