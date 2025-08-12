<?php
defined('ABSPATH') or die('No script kiddies please!!');

global $wp_query;
if (empty($wp_query->queried_object_id)) {
    return $content;
}
$post_id = $wp_query->queried_object_id;
$form_alias = get_post_meta($post_id, '_fpsm_form_alias', true);
if (empty($form_alias)) {
    return;
}
global $fpsm_library_obj;
$form_row = $fpsm_library_obj->get_form_row_by_alias($form_alias);
if (empty($form_row)) {
    return;
}
if ($form_row->form_type != 'login_require') {
    return $content;
}
if (empty($form_row->form_details)) {
    return $content;
}
$form_details = maybe_unserialize($form_row->form_details);
if (is_user_logged_in()) {
    $current_user_id = get_current_user_id();
    if (!empty($form_details['dashboard']['post_edit_button']) && !empty($form_details['dashboard']['frontend_dashboard_url'])) {
        $dashboard_url = $form_details['dashboard']['frontend_dashboard_url'];
        $edit_button_label = (!empty($form_details['dashboard']['edit_button_label'])) ? $form_details['dashboard']['edit_button_label'] : esc_html__('Edit', 'frontend-post-submission-manager');
        $edit_button_url =  $dashboard_url . '?action=edit_post&post_id=' . $post_id;
        $post_author_id = get_post_field('post_author', $post_id);
        $edit_flag = ($current_user_id == $post_author_id) ? true : false;
        /**
         * fpsm_edit_flag
         * 
         * Filters edit flag varaible 
         * 
         * @param boolean $edit_flag
         * @param array $form_row
         * 
         * @since 1.4.2
         */
        $edit_flag = apply_filters('fpsm_edit_flag', $edit_flag, $form_row);
        if ($edit_flag) {
?>
            <a href="<?php echo esc_url($edit_button_url); ?>" class="fpsm-edit-post-button"><?php echo esc_html($edit_button_label); ?></a>
        <?php
        }
        ?>

<?php
    }
}
