<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!!' );
/**
 * Frontend Post Submission Preview Template File
 *
 * @since 1.0.0
 */
get_header();
?>
<div class="fpsm-page-title">
    <h2><?php esc_html_e( 'Form Preview', 'frontend-post-submission-manager' ); ?></h2>
</div>
<div class="fpsm-preview-note"><?php esc_html_e( 'Please note that this preview may differ when used in frontend as per your active theme\'s layout.', 'frontend-post-submission-manager' ); ?></div>
<div id="fpsm-preview-wrap">
    <?php
    $form_alias = sanitize_text_field( $_GET['fpsm_form_alias'] );
    echo do_shortcode( '[fpsm alias="' . $form_alias . '"]' );
    ?>
</div>
<?php
get_footer();

