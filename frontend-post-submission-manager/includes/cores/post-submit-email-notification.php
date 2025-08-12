<?php

defined('ABSPATH') or die('No script kiddies please!!');
global $fpsm_library_obj;
if (!empty($form_details['notification']['post_submit']['enable'])) {
    $from_name = (!empty($form_details['notification']['post_submit']['from_name'])) ? $form_details['notification']['post_submit']['from_name'] : esc_html__('No Reply', 'frontend-post-submission-manager');
    $from_email = (!empty($form_details['notification']['post_submit']['from_email'])) ? $form_details['notification']['post_submit']['from_email'] : $fpsm_library_obj->default_from_email();
    $subject = (!empty($form_details['notification']['post_submit']['subject'])) ? $form_details['notification']['post_submit']['subject'] : $fpsm_library_obj->default_from_email();

    if ($form_row->form_type == 'login_require') {
        $post_author_id = get_post_field('post_author', $insert_update_post_id);
        $notification_email = get_the_author_meta('user_email', $post_author_id);
        $author_name = get_the_author_meta('display_name', $post_author_id);
    } else {
        $notification_email = get_post_meta($insert_update_post_id, 'fpsm_author_email', true);
        if (empty($notification_email)) {
            return;
        }
        $author_name = get_post_meta($insert_update_post_id, 'fpsm_author_name', true);
    }
    $subject = str_replace('[post_title]', get_the_title($insert_update_post_id), $subject);
    $subject = str_replace('[author_name]', $author_name, $subject);
    $notification_message = (!empty($form_details['notification']['post_submit']['notification_message'])) ? $form_details['notification']['post_submit']['notification_message'] : $fpsm_library_obj->sanitize_escaping_linebreaks($fpsm_library_obj->default_submit_notification());
    $notification_message = str_replace('[post_title]', get_the_title($insert_update_post_id), $notification_message);
    $notification_message = str_replace('[author_name]', $author_name, $notification_message);

    $notification_type = 'post_submit';
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
    $headers = array();
    $charset = get_option('blog_charset');
    $headers[] = 'Content-Type: text/html; charset=' . $charset;
    $headers[] = "From: $from_name <$from_email>";
    wp_mail($notification_email, $subject, $notification_message, $headers);
}
