<?php

defined('ABSPATH') or die('No script kiddies please!!');
global $fpsm_library_obj;
if (!empty($form_details['notification']['admin']['enable'])) {
    $from_name = (!empty($form_details['notification']['admin']['from_name'])) ? $form_details['notification']['admin']['from_name'] : esc_html__('No Reply', 'frontend-post-submission-manager');
    $from_email = (!empty($form_details['notification']['admin']['from_email'])) ? $form_details['notification']['admin']['from_email'] : $fpsm_library_obj->default_from_email();
    $subject = (!empty($form_details['notification']['admin']['subject'])) ? $form_details['notification']['admin']['subject'] : $fpsm_library_obj->default_from_email();

    if ($form_row->form_type == 'login_require') {
        $post_author_id = get_post_field('post_author', $insert_update_post_id);
        $author_name = get_the_author_meta('display_name', $post_author_id);
        $author_email = get_the_author_meta('user_email', $post_author_id);
    } else {
        $author_name = get_post_meta($insert_update_post_id, 'fpsm_author_name', true);
        $author_email = get_post_meta($insert_update_post_id, 'fpsm_author_email', true);
    }
    $subject = str_replace('[post_title]', get_the_title($insert_update_post_id), $subject);
    $subject = str_replace('[author_name]', $author_name, $subject);
    $from_name = str_replace('[author_name]', $author_name, $from_name);
    $from_email = str_replace('[author_email]', $author_email, $from_email);
    $notification_message = (!empty($form_details['notification']['admin']['notification_message'])) ? $form_details['notification']['admin']['notification_message'] : $fpsm_library_obj->sanitize_escaping_linebreaks($fpsm_library_obj->default_admin_notification());
    $notification_message = str_replace('[post_title]', get_the_title($insert_update_post_id), $notification_message);
    $post_edit_link = admin_url('post.php?post=' . $insert_update_post_id . '&action=edit');
    $notification_message = str_replace('[post_admin_link]', '<a href="' . $post_edit_link . '">' . $post_edit_link . '</a>', $notification_message);
    $notification_type = 'admin';
    /**
     * Filters Post Notification
     *
     * @param string $notification_message
     * @param string $notification_type [post_publish, post_submit, admin, post_trash]
     * @param int $post_id
     *
     * @since 1.2.9
     */
    $notification_message = apply_filters('fpsm_notification_message', $notification_message, $notification_type, $insert_update_post_id);
    $admin_emails = (!empty($form_details['notification']['admin']['notification_emails'])) ? explode(',', $form_details['notification']['admin']['notification_emails']) : get_bloginfo('admin_email');
    $headers = array();
    $charset = get_option('blog_charset');
    $headers[] = 'Content-Type: text/html; charset=' . $charset;
    $headers[] = "From: $from_name <$from_email>";
    if (is_array($admin_emails)) {
        foreach ($admin_emails as $admin_email) {
            wp_mail($admin_email, $subject, $notification_message, $headers);
        }
    } else {
        wp_mail($admin_emails, $subject, $notification_message, $headers);
    }
}
