<?php

defined('ABSPATH') or die('No script kiddies please!!');
if (is_user_logged_in()) {
    $current_user_id = get_current_user_id();
    $user_meta = get_userdata($current_user_id);
    $user_roles = $user_meta->roles;
    if (isset($_GET['action']) && sanitize_text_field($_GET['action']) == 'edit_post' && !empty($_GET['post_id'])) {
        $post_id = intval($_GET['post_id']);
        $edit_form_alias = get_post_meta($post_id, '_fpsm_form_alias', true);
        if (!empty($edit_form_alias)) {
            $edit_form_row = $fpsm_library_obj->get_form_row_by_alias($edit_form_alias);
            if (!empty($edit_form_row)) {
                $form_row = $edit_form_row;
                $form_details = maybe_unserialize($form_row->form_details);
                $GLOBALS['fpsm_form_details'] = $form_details;
                $GLOBALS['fpsm_form_alias'] = $edit_form_alias;
            }
        }
        $author_id = get_post_field('post_author', $post_id);
        $edit_post = get_post($post_id);
        $edit_flag = ($current_user_id == $author_id || in_array('administrator', $user_roles)) ? true : false;
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
            include(FPSM_PATH . '/includes/views/frontend/form-html.php');
        } else {
            echo esc_html__('Unauthorized access', 'frontend-post-submission-manager');
        }
    } else {
        include(FPSM_PATH . '/includes/views/frontend/dashboard-html.php');
    }
} else {
    global $login_form_render;
    if (empty($login_form_render)) {
        include(FPSM_PATH . '/includes/views/frontend/login-html.php');
        $GLOBALS['login_form_render'] = true;
    }
}
