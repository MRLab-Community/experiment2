<?php
$dashboard_settings = (!empty($form_details['dashboard'])) ? $form_details['dashboard'] : array();
$default_display_fields = array(
    'sn' => array('show_on_table' => 1, 'display_label' => esc_html__('SN', 'frontend-post-submission-manager')),
    'post_title' => array('show_on_table' => 1, 'display_label' => esc_html__('Post Title', 'frontend-post-submission-manager')),
    'post_status' => array('show_on_table' => 1, 'display_label' => esc_html__('Post Status', 'frontend-post-submission-manager')),
    'last_modified' => array('show_on_table' => 1, 'display_label' => esc_html__('Last Modified', 'frontend-post-submission-manager')),
);
$display_fields = (!empty($dashboard_settings['display_fields'])) ? $dashboard_settings['display_fields'] : $default_display_fields;
?>
<div class="fpsm-settings-each-section" data-tab="dashboard" style="display:none;">
    <div class="fpsm-field-wrap">
        <label><?php esc_html_e('Enable SN', 'frontend-post-submission-manager'); ?></label>
        <div class="fpsm-field">
            <input type="checkbox" name="form_details[dashboard][sn]" value="1" <?php echo (!empty($dashboard_settings['sn'])) ? 'checked="checked"' : ''; ?> class="fpsm-checkbox-toggle-trigger" data-toggle-class="fpsm-sn-ref" />
        </div>
    </div>
    <div class="fpsm-field-wrap fpsm-sn-ref <?php echo (empty($dashboard_settings['sn'])) ? 'fpsm-display-none' : ''; ?>">
        <label><?php esc_html_e('SN Label', 'frontend-post-submission-manager'); ?></label>
        <div class="fpsm-field">
            <input type="text" name="form_details[dashboard][sn_label]" value="<?php echo (!empty($dashboard_settings['sn_label'])) ? esc_attr($dashboard_settings['sn_label']) : ''; ?>" />
        </div>
    </div>

    <div class="fpsm-field-wrap">
        <label><?php esc_html_e('Post Title Label', 'frontend-post-submission-manager'); ?></label>
        <div class="fpsm-field">
            <input type="text" name="form_details[dashboard][post_title_label]" value="<?php echo (!empty($dashboard_settings['post_title_label'])) ? esc_attr($dashboard_settings['post_title_label']) : ''; ?>" />
        </div>
    </div>
    <div class="fpsm-field-wrap">
        <label><?php esc_html_e('Enable Post Image', 'frontend-post-submission-manager'); ?></label>
        <div class="fpsm-field">
            <input type="checkbox" name="form_details[dashboard][post_image]" value="1" <?php echo (!empty($dashboard_settings['post_image'])) ? 'checked="checked"' : ''; ?> class="fpsm-checkbox-toggle-trigger" data-toggle-class="fpsm-post_image-ref" />
        </div>
    </div>
    <div class="fpsm-field-wrap fpsm-post_image-ref <?php echo (empty($dashboard_settings['post_image'])) ? 'fpsm-display-none' : ''; ?>">
        <label><?php esc_html_e('Post Image Label', 'frontend-post-submission-manager'); ?></label>
        <div class="fpsm-field">
            <input type="text" name="form_details[dashboard][post_image_label]" value="<?php echo (!empty($dashboard_settings['post_image_label'])) ? esc_attr($dashboard_settings['post_image_label']) : ''; ?>" />
        </div>
    </div>
    <div class="fpsm-field-wrap">
        <label><?php esc_html_e('Post Status Label', 'frontend-post-submission-manager'); ?></label>
        <div class="fpsm-field">
            <input type="text" name="form_details[dashboard][post_status_label]" value="<?php echo (!empty($dashboard_settings['post_status_label'])) ? esc_attr($dashboard_settings['post_status_label']) : ''; ?>" />
        </div>
    </div>
    <div class="fpsm-field-wrap">
        <label><?php esc_html_e('Enable Last Modified', 'frontend-post-submission-manager'); ?></label>
        <div class="fpsm-field">
            <input type="checkbox" name="form_details[dashboard][last_modified]" value="1" <?php echo (!empty($dashboard_settings['last_modified'])) ? 'checked="checked"' : ''; ?> class="fpsm-checkbox-toggle-trigger" data-toggle-class="fpsm-last-modified-ref" />
        </div>
    </div>
    <div class="fpsm-field-wrap fpsm-last-modified-ref <?php echo (empty($dashboard_settings['last_modified'])) ? 'fpsm-display-none' : ''; ?>">
        <label><?php esc_html_e('Last Modified Label', 'frontend-post-submission-manager'); ?></label>
        <div class="fpsm-field">
            <input type="text" name="form_details[dashboard][last_modified_label]" value="<?php echo (!empty($dashboard_settings['last_modified_label'])) ? esc_attr($dashboard_settings['last_modified_label']) : ''; ?>" />
        </div>
    </div>
    <?php
    if (function_exists('pvc_get_post_views')) {
    ?>
        <div class="fpsm-field-wrap">
            <label><?php esc_html_e('Enable Post Views', 'frontend-post-submission-manager'); ?></label>
            <div class="fpsm-field">
                <input type="checkbox" name="form_details[dashboard][post_views]" value="1" <?php echo (!empty($dashboard_settings['post_views'])) ? 'checked="checked"' : ''; ?> class="fpsm-checkbox-toggle-trigger" data-toggle-class="fpsm-post-views-ref" />
            </div>
        </div>
        <div class="fpsm-field-wrap fpsm-post-views-ref <?php echo (empty($dashboard_settings['post_views'])) ? 'fpsm-display-none' : ''; ?>">
            <label><?php esc_html_e('Post Views Label', 'frontend-post-submission-manager'); ?></label>
            <div class="fpsm-field">
                <input type="text" name="form_details[dashboard][post_views_label]" value="<?php echo (!empty($dashboard_settings['post_views_label'])) ? esc_attr($dashboard_settings['post_views_label']) : ''; ?>" />
            </div>
        </div>
    <?php } ?>
    <div class="fpsm-field-wrap">
        <label><?php esc_html_e('Action Label', 'frontend-post-submission-manager'); ?></label>
        <div class="fpsm-field">
            <input type="text" name="form_details[dashboard][action_label]" value="<?php echo (!empty($dashboard_settings['action_label'])) ? esc_attr($dashboard_settings['action_label']) : ''; ?>" />
        </div>
    </div>
    <div class="fpsm-field-wrap">
        <label><?php esc_html_e('Posts Per Page', 'frontend-post-submission-manager'); ?></label>
        <div class="fpsm-field">
            <input type="number" name="form_details[dashboard][posts_per_page]" value="<?php echo (!empty($dashboard_settings['posts_per_page'])) ? intval($dashboard_settings['posts_per_page']) : 20 ?>" />
        </div>
    </div>
    <div class="fpsm-field-wrap">
        <label><?php esc_html_e('Next Page Label', 'frontend-post-submission-manager'); ?></label>
        <div class="fpsm-field">
            <input type="text" name="form_details[dashboard][next_page_label]" value="<?php echo (!empty($dashboard_settings['next_page_label'])) ? esc_attr($dashboard_settings['next_page_label']) : ''; ?>" />
        </div>
    </div>
    <div class="fpsm-field-wrap">
        <label><?php esc_html_e('Previous Page Label', 'frontend-post-submission-manager'); ?></label>
        <div class="fpsm-field">
            <input type="text" name="form_details[dashboard][previous_page_label]" value="<?php echo (!empty($dashboard_settings['previous_page_label'])) ? esc_attr($dashboard_settings['previous_page_label']) : ''; ?>" />
        </div>
    </div>
    <div class="fpsm-field-wrap">
        <label><?php esc_html_e('Disable Post Edit', 'frontend-post-submission-manager'); ?></label>
        <div class="fpsm-field fpsm-post-status-edit-disable">
            <?php
            $default_checked_status = (!empty($dashboard_settings['disable_post_edit'])) ? array('publish') : array();
            $checked_disable_post_edit_status = (!empty($dashboard_settings['disable_post_edit_status'])) ? $dashboard_settings['disable_post_edit_status'] : $default_checked_status;
            global $fpsm_library_obj;
            $post_statuses = $fpsm_library_obj->get_post_statuses();

            foreach ($post_statuses as $post_status => $post_status_label) {
            ?>
                <label class="fpsm-display-block">
                    <input class="fpsm-disable-checkbox-toggle" type="checkbox" name="form_details[dashboard][disable_post_edit_status][]" value="<?php echo esc_attr($post_status); ?>" <?php echo (in_array($post_status, $checked_disable_post_edit_status)) ? 'checked="checked"' : ''; ?> />
                    <span><?php echo esc_html($post_status_label); ?></span>
                </label>
            <?php
            }
            ?>
            <?php /* <input type="checkbox" name="form_details[dashboard][disable_post_edit]" value="1" <?php echo (!empty( $dashboard_settings['disable_post_edit'] )) ? 'checked="checked"' : ''; ?>/> */ ?>
            <p class="description"><?php esc_html_e('Please check if you want to disable post edit for above post statuses.', 'frontend-post-submission-manager'); ?></p>
        </div>
    </div>
    <div class="fpsm-field-wrap">
        <label><?php esc_html_e('Disable Post Delete', 'frontend-post-submission-manager'); ?></label>
        <div class="fpsm-field fpsm-post-status-edit-disable">
            <?php
            $post_statuses = $fpsm_library_obj->get_post_statuses();
            $default_checked_status = (!empty($dashboard_settings['disable_post_delete'])) ? array_keys($post_statuses) : array();

            $checked_disable_post_delete_status = (!empty($dashboard_settings['disable_post_delete_status'])) ? $dashboard_settings['disable_post_delete_status'] : $default_checked_status;
            foreach ($post_statuses as $post_status => $post_status_label) {
            ?>
                <label class="fpsm-display-block">
                    <input class="fpsm-disable-checkbox-toggle" type="checkbox" name="form_details[dashboard][disable_post_delete_status][]" value="<?php echo esc_attr($post_status); ?>" <?php echo (in_array($post_status, $checked_disable_post_delete_status)) ? 'checked="checked"' : ''; ?> />
                    <span><?php echo esc_html($post_status_label); ?></span>
                </label>
            <?php
            }
            ?>
            <p class="description"><?php esc_html_e('Please check if you want to disable post delete for above post statuses.', 'frontend-post-submission-manager'); ?></p>
        </div>
        
    </div>
    <div class="fpsm-field-wrap">
        <label><?php esc_html_e('Post Delete Warning Message', 'frontend-post-submission-manager'); ?></label>
        <div class="fpsm-field">
            <input type="text" name="form_details[dashboard][post_delete_warning_message]" value="<?php echo (!empty($dashboard_settings['post_delete_warning_message'])) ? esc_attr($dashboard_settings['post_delete_warning_message']) : ''; ?>" />
        </div>
    </div>
    <div class="fpsm-field-wrap">
        <label><?php esc_html_e('Post Not Found Message', 'frontend-post-submission-manager'); ?></label>
        <div class="fpsm-field">
            <textarea name="form_details[dashboard][post_not_found_message]"><?php echo (!empty($dashboard_settings['post_not_found_message'])) ? $fpsm_library_obj->output_converting_br($dashboard_settings['post_not_found_message']) : ''; ?></textarea>
            <p class="description"><?php esc_html_e('Please enter the message to be displayed to the user when they don\'t have any posts in the frontend dashboard.', 'frontend-post-submission-manager'); ?></p>
        </div>
    </div>
    <div class="fpsm-field-wrap">
        <label><?php esc_html_e('List all posts for Administrator', 'frontend-post-submission-manager'); ?></label>
        <div class="fpsm-field">
            <input type="checkbox" name="form_details[dashboard][list_all_administrator]" value="1" <?php echo (!empty($dashboard_settings['list_all_administrator'])) ? 'checked="checked"' : ''; ?> />
            <p><?php esc_html_e('Please check if you want to list the post from all the authors when adminstrator is logged in.', 'frontend-post-submission-manager'); ?></p>
        </div>
    </div>
    <div class="fpsm-field-wrap">
        <label><?php esc_html_e('List all posts from author', 'frontend-post-submission-manager'); ?></label>
        <div class="fpsm-field">
            <input type="checkbox" name="form_details[dashboard][list_all_from_author]" value="1" <?php echo (!empty($dashboard_settings['list_all_from_author'])) ? 'checked="checked"' : ''; ?> />
            <p><?php esc_html_e('Please check if you want to list all the post from the logged in author even if the post has not been submitted from this form', 'frontend-post-submission-manager'); ?></p>
        </div>
    </div>
    <div class="fpsm-field-wrap">
        <label><?php esc_html_e('Post Status Filter', 'frontend-post-submission-manager'); ?></label>
        <div class="fpsm-field">
            <input type="checkbox" name="form_details[dashboard][post_status_filter]" value="1" <?php echo (!empty($dashboard_settings['post_status_filter'])) ? 'checked="checked"' : ''; ?> />
            <p><?php esc_html_e('Please check if you want to display post status filter above the frontend dashboard posts list.', 'frontend-post-submission-manager'); ?></p>
        </div>
    </div>
    <div class="fpsm-field-wrap">
        <label><?php esc_html_e('Post Search', 'frontend-post-submission-manager'); ?></label>
        <div class="fpsm-field">
            <input type="checkbox" name="form_details[dashboard][post_search]" value="1" <?php echo (!empty($dashboard_settings['post_search'])) ? 'checked="checked"' : ''; ?> class="fpsm-checkbox-toggle-trigger" data-toggle-class="fpsm-post-search" />
            <p><?php esc_html_e('Please check if you want to display post search field above the frontend dashboard posts list.', 'frontend-post-submission-manager'); ?></p>
        </div>
    </div>
    <div class="fpsm-post-search <?php echo (empty($dashboard_settings['post_search'])) ? 'fpsm-display-none' : ''; ?>">
        <div class="fpsm-field-wrap">
            <label><?php esc_html_e('Search Field Placeholder', 'frontend-post-submission-manager'); ?></label>
            <div class="fpsm-field">
                <input type="text" name="form_details[dashboard][search_field_placeholder]" value="<?php echo (!empty($dashboard_settings['search_field_placeholder'])) ? esc_attr($dashboard_settings['search_field_placeholder']) : ''; ?>" />
            </div>
        </div>
        <div class="fpsm-field-wrap">
            <label><?php esc_html_e('Search Submit Label', 'frontend-post-submission-manager'); ?></label>
            <div class="fpsm-field">
                <input type="text" name="form_details[dashboard][search_submit_label]" value="<?php echo (!empty($dashboard_settings['search_submit_label'])) ? esc_attr($dashboard_settings['search_submit_label']) : ''; ?>" />
            </div>
        </div>
    </div>
    <div class="fpsm-field-wrap">
        <label><?php esc_html_e('Post Edit Button', 'frontend-post-submission-manager'); ?></label>
        <div class="fpsm-field">
            <input type="checkbox" name="form_details[dashboard][post_edit_button]" value="1" <?php echo (!empty($dashboard_settings['post_edit_button'])) ? 'checked="checked"' : ''; ?> class="fpsm-checkbox-toggle-trigger" data-toggle-class="fpsm-post-search" />
            <p><?php esc_html_e('Please check if you want to display post edit button below the post content in each post to let users directly edit the article.', 'frontend-post-submission-manager'); ?></p>
        </div>
    </div>
    <div class="fpsm-post-search <?php echo (empty($dashboard_settings['post_edit_button'])) ? 'fpsm-display-none' : ''; ?>">
        <div class="fpsm-field-wrap">
            <label><?php esc_html_e('Frontend Dashboard URL', 'frontend-post-submission-manager'); ?></label>
            <div class="fpsm-field">
                <input type="text" name="form_details[dashboard][frontend_dashboard_url]" value="<?php echo (!empty($dashboard_settings['frontend_dashboard_url'])) ? esc_attr($dashboard_settings['frontend_dashboard_url']) : ''; ?>" />
                <p class="description"><?php esc_html_e('Please enter the URL where you are going to or you have already setup frontend dashboard.', 'frontend-post-submission-manager'); ?></p>
            </div>
        </div>
        <div class="fpsm-field-wrap">
            <label><?php esc_html_e('Edit Button Label', 'frontend-post-submission-manager'); ?></label>
            <div class="fpsm-field">
                <input type="text" name="form_details[dashboard][edit_button_label]" value="<?php echo (!empty($dashboard_settings['edit_button_label'])) ? esc_attr($dashboard_settings['edit_button_label']) : ''; ?>" />
            </div>
        </div>
    </div>



</div>