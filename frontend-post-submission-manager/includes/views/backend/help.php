<div class="wrap fpsm-wrap">
    <div class="fpsm-header fpsm-clearfix">
        <h1 class="fpsm-floatLeft"><?php esc_html_e('Frontend Post Submission Manager', 'frontend-post-submission-manager'); ?>
        </h1>
        <div class="fpsm-add-wrap">
            <a href="<?php echo admin_url('admin.php?page=fpsm-add-new-form'); ?>"><input type="button" class="fpsm-button-primary" value="<?php echo esc_html_e('Add New Form', 'frontend-post-submission-manager'); ?>"></a>
        </div>
    </div>

    <div class="fpsm-block-wrap">
        <div class="fpsm-content-block">
            <h2><?php esc_html_e('Documentation', 'frontend-post-submission-manager'); ?>
            </h2>
            <p><?php esc_html_e('You can check our detailed documentation from below link.', 'frontend-post-submission-manager'); ?>
            </p>
            <p><a href="http://wpshuffle.com/wordpress-documentations/frontend-post-submission-manager" target="_blank">http://wpshuffle.com/wordpress-documentations/frontend-post-submission-manager</a>
            </p>
        </div>
        <div class="fpsm-content-block">
            <h2><?php esc_html_e('Developer Documentation', 'frontend-post-submission-manager'); ?>
            </h2>
            <p><?php esc_html_e('If you are developer and trying to add any functionality or customize our plugin through hooks then below are the list of actions and filters available in the plugin.', 'frontend-post-submission-manager'); ?>
            </p>
        </div>
        <div class="fpsm-content-block">
            <h2><?php esc_html_e('Available Actions', 'frontend-post-submission-manager'); ?>
            </h2>
            <div class="fpsm-hooks-wrap">
                <pre>
/**
* Fires on init hook
*
* @since 1.0.0
*/
do_action('fpsm_init');
                </pre>
                <pre>
 /**
* Fires on starting of form add ajax
*
* @since 1.0.0
*/
do_action('fpsm_before_form_add_ajax');
                </pre>
                <pre>
 /**
* Fires on starting of form edit ajax
*
* @since 1.0.0
*/
do_action('fpsm_before_form_edit_ajax');
                </pre>
                <pre>
/**
* Fires when the successful form submission is complete
*
* @param int $insert_update_post_id
* @param array $form_row
* @param string $action
*/
do_action( 'fpsm_form_submission_success', $insert_update_post_id, $form_row, $action );
                </pre>
                <pre>
/**
* Fires at the end of all the custom field option has been printed
*
* @param type string $field_key
* @param type array $field_details
*
* @since 1.0.0
*/
do_action('fpsm_custom_field_admin_end', $field_key, $field_details);
                </pre>
                <pre>
/**
* Fires while building the nav
*
* @since 1.0.0
*/
do_action('fpsm_form_nav');
                </pre>
                <pre>
/**
* Fires on start of the form sections
*
* @since 1.0.0
*
* @param array $form_row
*
*/
do_action('fpsm_form_sections_start', $form_row);
                </pre>
                <pre>
/**
* Fires on end of the form sections
*
* @since 1.0.0
*
* @param array $form_row
*
*/
do_action('fpsm_form_sections_end', $form_row);
                </pre>
                <pre>
/**
* Fires at the start of form
*
* @since 1.0.0
*/
do_action( 'fpsm_form_start', $form_row );
                </pre>
                <pre>
/**
* Fires at the end of form
*
* @since 1.0.0
*/
do_action('fpsm_form_end', $form_row);
                </pre>
                <pre>
/**
* Fires just before displaying the login form
*
* @param obj $form_row
* @param array $form_details
*
* @since 1.0.3
*/
do_action('fpsm_login_form_before', $form_row, $form_details);
                </pre>
                <pre>
/**
* Fires just after displaying the login form
*
* @param obj $form_row
* @param array $form_details
*
* @since 1.0.3
*/
do_action('fpsm_login_form_after', $form_row, $form_details);
                </pre>
                <pre>
/**
* Triggers after displaying each field
* 
* @param string $field_key
* @param mixed $field_details
* @param mixed $form_row
* 
* @since 1.3.2
*/
do_action('fpsm_after_form_field', $field_key, $field_details, $form_row);
                    </pre>
                <pre>
/**
 * Triggers before starting the ajax form process
 * 
 * @since 1.3.7
 */
do_action('fpsm_before_form_process');
                    </pre>

            </div>
        </div>
        <div class="fpsm-content-block">
            <h2><?php esc_html_e('Available Filters', 'frontend-post-submission-manager'); ?>
            </h2>
            <div class="fpsm-hooks-wrap">
                <pre>
/**
* Filters allowed extensions for image field type
*
* @param array $allowed_extensions
*
* @since 1.0.0
*/
$default_allowed_extensions = apply_filters('fpsm_image_allowed_extensions', $default_allowed_extensions);
                </pre>
                <pre>
/**
* Filters allowed html for processing form data
*
* @param array $allowed_html
*
* @since 1.0.0
*/
$allowed_html = apply_filters( 'fpsm_allowed_html', $allowed_html );
                </pre>
                <pre>
/**
* Filter the default fields for form
* @param array $default_fields
*
* @since 1.0.0
*/
return apply_filters( 'fpsm_default_fields', $default_fields );
                </pre>
                <pre>
/**
* Filters the post array before inserting the post into db
*
* @param array $postarr
* @param array $form_data
* @param obj $form_row
*
* @since 1.0.0
*/
$postarr = apply_filters( 'fpsm_insert_postdata', $postarr, $form_data, $form_row );
                </pre>
                <pre>
/**
* Filters the custom field value before storing it in the database
*
* @param mixed $custom_field_value
* @param string $custom_field_key
* @param obj $form_row
*
* @since 1.0.0
*/
$custom_field_value = apply_filters( 'fpsm_custom_field_value', $custom_field_value, $custom_field_key, $form_row );
                </pre>
                <pre>
/**
* Filters the form process response array
*
* @param array $response
* @param array $form_data
* @param obj $form_row
*
* @since 1.0.0
*/
$response = apply_filters( 'fpsm_form_response', $response, $form_data, $form_row );
                </pre>
                <pre>
/**
 * Filters custom field type list
 *
 * @param array $custom_field_type_list
 *
 * @since 1.0.0
 */
$custom_field_type_list = apply_filters('fpsm_custom_field_type_list', $custom_field_type_list);
                </pre>
                <pre>
/**
* Filters datepicker formats as an option
*
* @param array $fpsm_datepicker_formats
*
* @since 1.0.0
*/
$fpsm_datepicker_formats = apply_filters( 'fpsm_datepicker_formats', $fpsm_datepicker_formats );
                </pre>
                <pre>
/**
* Filters user arguments while fetching the users
*
* @param array $user_args
*
* @since 1.0.0
*/
$user_args = apply_filters( 'fpsm_user_list_args', $user_args );
                </pre>
                <pre>
/**
* Filters custom field type list
*
* @param array $custom_field_type_list
*
* @since 1.0.0
*/
$custom_field_type_list = apply_filters('fpsm_custom_field_type_list', $custom_field_type_list);
                </pre>
                <pre>
/**
 * Filters allowed extensions for image field type
 *
 * @param array $default_allowed_extensions
 *
 * @since 1.0.0
 */
$default_allowed_extensions = apply_filters('fpsm_image_allowed_extensions', $default_allowed_extensions);
                </pre>
                <pre>
/**
* Filters custom field value html being printed
*
* @param mixed $custom_field_value
* @param string $custom_field_meta_key
*
* @since 1.0.4
*/
$filterd_custom_field_value = apply_filters('fpsm_custom_field_html', $custom_field_value, $custom_field_meta_key);
                </pre>
                <pre>
/**
* Filters the post status before inserting/updating post
*
* @param string $dynamic_post_status
* @param mixed $form_row
* @param mixed $form_data
*
* @since 1.1.1
*/
$post_status = apply_filters('fpsm_post_status', $dynamic_post_status, $form_row, $form_data);
                </pre>
                <pre>
/**
* Filters the query args for fetching dashboard posts
*
* @param array $dashboard_posts_args
* @param mixed $form_row
*
* @since 1.1.1
*/
            $dashboard_posts_args = apply_filters('fpsm_dashboard_args', $dashboard_posts_args, $form_row);
                </pre>
                <pre>
/**
* Filters the redirect time after form submission
*
* @param int
*
* @since 1.1.1
*/
$response['redirect_delay'] = apply_filters('fpsm_redirect_wait', 5);
                </pre>
                <pre>
/**
* Filter Post Status array
*
* @param array $post_status_array
*
* @since 1.2.1
*/
return apply_filters('fpsm_post_statuses', $post_status_array);
                </pre>
                <pre>
/**
* Filters the submit button label before printing
*
* @param string $button_label
* @param string $form_post_button_status
* @param array $form_post_button_details
*
* @since 1.2.1
*/
$button_label = apply_filters('fpsm_submit_label', $button_label, $form_post_button_status, $form_post_button_details);
                </pre>
                <pre>
/**
* Filters author total number of posts fetched from DB
*
* @param int $author_total_posts
* @param mixed $form_row
*/
$author_total_posts = apply_filters('fpsm_author_total_posts', $author_total_posts, $form_row);
                </pre>
                <pre>
/**
* Filters Editor Settings
*
* @param array $editor_settings
* @param array $form_row
*
* @since 1.2.8
*/
    $editor_settings = apply_filters( 'fpsm_editor_settings', $editor_settings, $form_row );

                </pre>
                <pre>
/**
* Filters last modified date
*
* @param type string $last_modified_date
*
* @since 1.2.8
*/
$last_modified_date = apply_filters( 'fpsm_last_modified_date', $last_modified_date );
                </pre>
                <pre>
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
                </pre>
                <pre>
/**
* Filters sanitize rule array before processing the form data
* 
* @param array $sanitize_rule_array
* @param array $form_row
* 
* @since 1.3.2
*/
$sanitize_rule = apply_filters('fpsm_front_sanitize_rule', $sanitize_rule_array, $form_row);
                </pre>
                <pre>
/**
* Filters post content before displaying it in the post content field in the form
* 
* @param string $post_content
* @param mix $form_row
* @param bool $edit_flag
* @since 1.3.9
*/
$post_content = apply_filters('fpsm_wp_editor_post_content', $post_content, $form_row, $edit_flag);
                </pre>
                <pre>
/**
* Filters post types list
* 
* @param mixed $post_types
* @since 1.3.9
*/
$post_types = apply_filters('fpsm_post_types_list', $post_types);
                </pre>
                <pre>
/**
* Filters post statuses array fetched
* 
* @param array
* 
* @since 1.4.0
*/
$post_statuses = apply_filters('fpsm_post_statuses', get_post_statuses());
                </pre>
                <pre>
/**
* Filters the args being sent on pagination for frontend dashboard posts
* 
* @args array $pagination_args
* @args array $form_row
* 
* @since 1.4.1
*/
$pagination_args = apply_filters('fpsm_pagination_args', $pagination_args, $form_row);
</pre>
                <pre>
/**
* 
* 
* Filters edit flag varaible
* This variable is true when current user matches with the user of the post that is being edited
* Useful when you want to enable cross user editing of post
*
* @param boolean $edit_flag
* @param array $form_row
* 
* @since 1.4.2
*/
$edit_flag = apply_filters('fpsm_edit_flag', $edit_flag, $form_row);
</pre>
                <pre>
/**
* fpsm_delete_flag
* This variable is true when current user matches with the user of the post that is being deleted
* Useful when you want to enable cross user deleting of post
* Filters delete flag varaible 
* 
* @param boolean $delete_flag
* 
* @since 1.4.2
*/
$delete_flag = apply_filters('fpsm_delete_flag', $delete_flag);
</pre>
<pre>
/**
* fpsm_max_term_return
* 
* Filters the total number of terms to be loaded
* 
* @param array $taxonomy_details
* 
* @since 1.4.6
*/
$terms = get_terms($taxonomy, array('hide_empty' => 0, 'number' => apply_filters('fpsm_max_term_return', 200, $taxonomy_details)));
</pre>
            </div>
            <p><?php esc_html_e('If you think there are any missing action or filters then please let us know from below link.', 'frontend-post-submission-manager'); ?>
            </p>
            <a href="https://codecanyon.net/user/wpshuffle#contact" target="_blank">https://codecanyon.net/user/wpshuffle#contact</a>
        </div>



    </div>
</div>