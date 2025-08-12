<?php

defined('ABSPATH') or die('No script kiddies please!!');
if ($this->admin_ajax_nonce_verify()) {
    /**
     * Triggers before starting the ajax form process
     * 
     * @since 1.3.7
     */
    do_action('fpsm_before_form_process');


    global $fpsm_library_obj;

    $form_data = $_POST['form_data'];
    $form_data = stripslashes_deep($form_data);
    parse_str($form_data, $form_data);

    $form_alias = sanitize_text_field($form_data['form_alias']);

    $form_row = $fpsm_library_obj->get_form_row_by_alias($form_alias);
    if (empty($form_row)) {
        die(esc_html__('No form found for this alias.', 'frontend-post-submission-manager'));
    }
    $form_details = maybe_unserialize($form_row->form_details);
    $form_fields = $form_details['form']['fields'];
    $sanitize_rule_array = array('post_content' => 'html');
    if (!empty($form_fields)) {
        foreach ($form_fields as $temp_field_key => $temp_field_details) {
            if ($fpsm_library_obj->is_custom_field_key($temp_field_key)) {
                if ($temp_field_details['field_type'] == 'textarea' || $temp_field_details['field_type'] == 'wp_editor') {
                    $sanitize_rule_array[$temp_field_key] = 'html';
                }
            }
        }
    }

    /**
     * Filters sanitize rule array before processing the form data
     * 
     * @param array $sanitize_rule_array
     * @param array $form_row
     * 
     * @since 1.3.2
     */
    $sanitize_rule = apply_filters('fpsm_front_sanitize_rule', $sanitize_rule_array, $form_row);

    $form_data = $fpsm_library_obj->sanitize_array($form_data, $sanitize_rule);


    $dynamic_post_status = (!empty($form_data['dynamic_post_status'])) ? $form_data['dynamic_post_status'] : $form_details['basic']['post_status'];

    $error_flag = 0;
    $error_details = array();
    $response = array();
    $post_id = (!empty($form_data['post_id'])) ? intval($form_data['post_id']) : 0;
    if ($form_row->form_type == 'login_require' && !empty($form_details['basic']['limit_post_submission']) && !empty($form_details['basic']['allowed_number_posts']) && empty($post_id)) {
        //if the form is login require form and user is logged in
        if (is_user_logged_in()) {
            $post_author_id = get_current_user_id();
            if (!empty($post_id)) {
                $post_author_id = get_post_field('post_author', $post_id);
            }

            if (!empty($form_details['basic']['submission_limit_duration_type'])) {
                $submission_limit_duration_type = $form_details['basic']['submission_limit_duration_type'];
                $submission_limit_duration = $form_details['basic']['submission_limit_duration'];
                $time_duration = $submission_limit_duration . '  ' . $submission_limit_duration_type;
                $author_total_posts = $fpsm_library_obj->get_user_post_count_by_period($time_duration, $form_alias, $post_author_id);
            } else {
                $author_total_posts = $fpsm_library_obj->get_total_author_posts($post_author_id, $form_row->form_alias, $post_id);
            }

            /**
             * Filters author total number of posts fetched from DB
             *
             * @param int $author_total_posts
             * @param mixed $form_row
             */
            $author_total_posts = apply_filters('fpsm_author_total_posts', $author_total_posts, $form_row);
            $allowed_number_posts = intval($form_details['basic']['allowed_number_posts']);
            if ($allowed_number_posts <= intval($author_total_posts)) {
                $response['status'] = 403;
                $response['total_posts'] = $author_total_posts;
                $response['message'] = (!empty($form_details['basic']['post_limit_message'])) ? $form_details['basic']['post_limit_message'] : esc_html__('User post limit reached.', 'frontend-post-submission-manager');
                die(json_encode($response));
            }
        } else {
            // if  the form is login require form but users are not logged in
            $response['status'] = 403;
            $response['message'] = esc_html__('Invalid form submission', 'frontend-post-submission-manager');
            die(json_encode($response));
        }
    }
    if (!empty($form_fields)) {
        $taxonomy_lists = array();
        $custom_field_lists = array();
        $required_check = true;
        if ($form_row->form_type == 'login_require' && $dynamic_post_status == 'draft') {
            if (!empty($form_details['form']['post_status']['draft']['disable_field_required_check'])) {
                $required_check = false;
            }
        }
        foreach ($form_fields as $field_key => $field_details) {
            if ($fpsm_library_obj->is_taxonomy_key($field_key)) {
                $taxonomy_lists[] = $field_key;
            }
            // if field is enabled in backend
            if (!empty($field_details['show_on_form'])) {
                $required_message = (!empty($field_details['required_error_message'])) ? esc_html__($field_details['required_error_message']) : esc_html__('This field is requied', 'frontend-post-submission-manager');
                // if the field is required
                if (!empty($field_details['required']) && empty($form_data[$field_key]) && $required_check) {
                    $error_flag = 1;
                    $error_details[$field_key] = $required_message;
                } else {
                    // Other validations are done here
                    $field_recog_key = $field_key;
                    if ($fpsm_library_obj->is_custom_field_key($field_key)) {
                        $field_recog_key = 'custom_field';
                    }
                    switch ($field_recog_key) {
                        case 'post_title':
                        case 'post_content':
                        case 'post_excerpt':
                        case 'author_name':
                        case 'author_email':
                            if (!empty($field_details['character_limit']) && $required_check) {
                                $form_received_value = sanitize_text_field($form_data[$field_key]);
                                $form_clean_received_value =  str_replace(array("\r", "\n", ' '), '', $form_received_value);
                                $field_value_length = strlen($form_clean_received_value);
                                if ($field_value_length > $field_details['character_limit']) {
                                    $character_limit_error_message = (!empty($field_details['character_limit_error_message'])) ? esc_html__($field_details['character_limit_error_message']) : esc_html__(sprintf('Max characters allowed is %d', $field_details['character_limit']), 'frontend-post-submission-manager');
                                    $error_flag = 1;
                                    $error_details[$field_key] = $character_limit_error_message;
                                }
                            }
                            if (!empty($field_details['min_character_limit']) && $required_check) {
                                $field_value_length = strlen(sanitize_text_field($form_data[$field_key]));
                                if ($field_value_length < $field_details['min_character_limit']) {
                                    $character_limit_error_message = (!empty($field_details['character_limit_error_message'])) ? esc_html__($field_details['character_limit_error_message']) : esc_html__(sprintf('Max characters allowed is %d', $field_details['character_limit']), 'frontend-post-submission-manager');
                                    $error_flag = 1;
                                    $error_details[$field_key] = $character_limit_error_message;
                                }
                            }
                            break;
                        case 'custom_field':
                            if (!empty($field_details['character_limit']) && $required_check) {
                                $field_value_length = strlen(sanitize_text_field($form_data[$field_key]));
                                if ($field_value_length > $field_details['character_limit']) {
                                    $character_limit_error_message = (!empty($field_details['character_limit_error_message'])) ? esc_html__($field_details['character_limit_error_message']) : esc_html__(sprintf('Max characters allowed is %d', $field_details['character_limit']), 'frontend-post-submission-manager');
                                    $error_flag = 1;
                                    $error_details[$field_key] = $character_limit_error_message;
                                } else {
                                    $custom_field_lists[] = $field_key;
                                }
                            }
                            if (!empty($field_details['min_character_limit']) && $required_check) {
                                $field_value_length = strlen(sanitize_text_field($form_data[$field_key]));
                                if ($field_value_length < $field_details['min_character_limit']) {
                                    $character_limit_error_message = (!empty($field_details['character_limit_error_message'])) ? esc_html__($field_details['character_limit_error_message']) : esc_html__(sprintf('Max characters allowed is %d', $field_details['character_limit']), 'frontend-post-submission-manager');
                                    $error_flag = 1;
                                    $error_details[$field_key] = $character_limit_error_message;
                                } else {
                                    $custom_field_lists[] = $field_key;
                                }
                            }
                            if (empty($error_flag)) {
                                $custom_field_lists[] = $field_key;
                            }
                            break;
                    }
                }
            }
        }

        if (!empty($form_details['security']['frontend_form_captcha'])) {
            $captcha = sanitize_text_field($form_data['g-recaptcha-response']); // get the captchaResponse parameter sent from our ajax
            $required = esc_html__('This field is required', 'frontend-post-submission-manager');
            if (empty($captcha)) {
                $error_details['captcha'] = (!empty($form_details['security']['error_message'])) ? esc_attr($form_details['security']['error_message']) : $required_message;
                $error_flag = 1;
            } else {
                $secret_key = (!empty($form_details['security']['secret_key'])) ? esc_attr($form_details['security']['secret_key']) : '';
                $captcha_response = wp_remote_get("https://www.google.com/recaptcha/api/siteverify?secret=" . $secret_key . "&response=" . $captcha);

                if (is_wp_error($captcha_response)) {
                    $error_details['security'] = esc_html__('Captcha Validation failed.', 'frontend-post-submission-manager');
                    $error_flag = 1;
                } else {
                    $captcha_response = json_decode($captcha_response['body']);
                    if ($captcha_response->success == false) {
                        $error_details['security'] = (!empty($form_details['security']['error_message'])) ? esc_attr($form_details['security']['error_message']) : $required_message;
                        $error_flag = 1;
                    }
                }
            }
        }

        if ($error_flag == 1) {
            $response['status'] = 403;
            $response['error_details'] = $error_details;
            $response['message'] = (!empty($form_details['basic']['validation_error_message'])) ? esc_html($form_details['basic']['validation_error_message']) : esc_html__('Form validation error occurred.', 'frontend-post-submission-manager');
        } else {
            //Lets process the form


            $post_title = (!empty($form_data['post_title'])) ? $form_data['post_title'] : '';
            $post_content = (!empty($form_data['post_content'])) ? $form_data['post_content'] : '';

            if (!empty($post_id) && empty($post_content)) {
                $post_content = get_post_field('post_content', $post_id);
            }
            $post_type = $form_row->post_type;
            $post_excerpt = (!empty($form_data['post_excerpt'])) ? $form_data['post_excerpt'] : '';
            $post_status = $form_details['basic']['post_status'];
            if ($form_row->form_type == 'login_require') {
                //if the form is login require form and user is logged in
                if (is_user_logged_in()) {
                    $post_author_id = get_current_user_id();
                    if (!empty($post_id)) {
                        $post_author_id = get_post_field('post_author', $post_id);
                    }
                } else {
                    // if  the form is login require form but users are not logged in
                    $response['status'] = 403;
                    $response['message'] = esc_html__('Invalid form submission', 'frontend-post-submission-manager');
                    die(json_encode($response));
                }
            } else {
                $post_author_id = intval($form_details['basic']['post_author']);
            }
            //Lets check the post status of the post for edited post
            //$post_status = (!empty($post_id)) ? get_post_status($post_id) : $post_status;

            /**
             * Filters the post status before inserting/updating post
             *
             * @param string $dynamic_post_status
             * @param mixed $form_row
             * @param mixed $form_data
             * @since 1.1.1
             */
            $post_status = apply_filters('fpsm_post_status', $dynamic_post_status, $form_row, $form_data);
            // Lets insert post into DB
            $postarr = array(
                'ID' => $post_id,
                'post_author' => $post_author_id,
                'post_content' => $post_content,
                'post_title' => (!empty($post_title)) ? $post_title : esc_html__('Untitled Post', 'frontend-post-submission-manager'),
                'post_excerpt' => $post_excerpt,
                'post_status' => $post_status,
                'post_type' => $post_type,
            );



            /**
             * Filters the post array before inserting the post into db
             *
             * @param array $postarr
             * @param array $form_data
             * @param obj $form_row
             *
             * @since 1.0.0
             */
            $postarr = apply_filters('fpsm_insert_postdata', $postarr, $form_data, $form_row);
            if (empty($post_id)) {
                $insert_update_post_id = wp_insert_post($postarr);
            } else {

                $insert_update_post_id = wp_update_post($postarr);
            }

            $update = (empty($post_id)) ? false : true;
            if (!empty($insert_update_post_id)) {
                if (!empty($basic_settings['fire_save_post'])) {
                    $post_obj = get_post($insert_update_post_id);
                    $update = (empty($post_id)) ? false : true;
                    do_action('save_post', $insert_update_post_id, $post_obj, $update);
                }

                //Lets assign the post image to the post
                if (isset($form_data['post_image'])) {
                    if (!empty($post_id) && empty($form_data['post_image'])) {
                        delete_post_thumbnail($post_id);
                    } else {
                        if (!empty($form_data['post_image'])) {

                            set_post_thumbnail($insert_update_post_id, intval($form_data['post_image']));

                            $media_attachment_obj = get_post(intval($form_data['post_image']));
                            if ($media_attachment_obj) {
                                $attachment_path = get_attached_file(intval($form_data['post_image'])); // Full path
                                wp_insert_attachment($media_attachment_obj, $attachment_path, $insert_update_post_id);
                            }
                        }
                    }
                }

                //Lets assign post format
                if (!empty($form_details['basic']['post_format'])) {
                    set_post_format($insert_update_post_id, $form_details['basic']['post_format']);
                }

                // Lets assign taxonomy terms
                if (!empty($taxonomy_lists)) {
                    foreach ($taxonomy_lists as $taxonomy_key) {
                        $taxonomy_settings = $form_details['form']['fields'][$taxonomy_key];
                        // If taxonomy is enabled in the form
                        $taxonomy_array = explode('|', $taxonomy_key);
                        $taxonomy_name = end($taxonomy_array);
                        if (!empty($taxonomy_settings['show_on_form'])) {
                            $form_data[$taxonomy_key] = (!empty($form_data[$taxonomy_key])) ? $form_data[$taxonomy_key] : '';
                            if (is_array($form_data[$taxonomy_key])) {
                                $post_assign_terms = implode(',', $form_data[$taxonomy_key]);
                            } else {
                                $post_assign_terms = $form_data[$taxonomy_key];
                            }
                            wp_set_post_terms($insert_update_post_id, $post_assign_terms, $taxonomy_name);
                        }

                        // If explicit auto assign of the terms is enabled
                        if (!empty($taxonomy_settings['auto_assign'])) {
                            $auto_assign_terms = implode(',', $taxonomy_settings['auto_assign']);
                            wp_set_post_terms($insert_update_post_id, $auto_assign_terms, $taxonomy_name, true);
                        }
                    }
                }
                if (!empty($form_data['author_email']) && !empty($form_details['form']['fields']['author_email']['show_on_form'])) {
                    update_post_meta($insert_update_post_id, 'fpsm_author_email', $form_data['author_email']);
                }
                if (!empty($form_data['author_name']) && !empty($form_details['form']['fields']['author_name']['show_on_form'])) {
                    update_post_meta($insert_update_post_id, 'fpsm_author_name', $form_data['author_name']);
                }
                //Lets work on custom fields here
                if (!empty($custom_field_lists)) {
                    foreach ($custom_field_lists as $custom_field_key) {
                        $custom_field_value = (!empty($form_data[$custom_field_key])) ? $form_data[$custom_field_key] : '';
                        $custom_field_settings = $form_details['form']['fields'][$custom_field_key];
                        $custom_field_array = explode('|', $custom_field_key);
                        $custom_field_meta_key = end($custom_field_array);
                        $custom_field_type = $custom_field_settings['field_type'];
                        if ($custom_field_type == 'datepicker' && !empty($custom_field_settings['string_format'])) {
                            $custom_field_value = strtotime($custom_field_value);
                        }


                        /**
                         * Filters the custom field value before storing it in the database
                         *
                         * @param mixed $custom_field_value
                         * @param string $custom_field_key
                         * @param obj $form_row
                         *
                         * @since 1.0.0
                         */
                        $custom_field_value = apply_filters('fpsm_custom_field_value', $custom_field_value, $custom_field_key, $form_row);
                        update_post_meta($insert_update_post_id, $custom_field_meta_key, $custom_field_value);
                        if ($custom_field_type == 'file_uploader') {

                            // If attach to post is enabled
                            if (!empty($custom_field_settings['attach_to_post']) && !empty($custom_field_value)) {
                                $media_ids_array = explode(',', $custom_field_value);
                                foreach ($media_ids_array as $media_id) {
                                    $media_obj = get_post($media_id);
                                    if ($media_obj) {
                                        $fullsize_path = get_attached_file($media_id); // Full path
                                        wp_insert_attachment($media_obj, $fullsize_path, $insert_update_post_id);
                                    }
                                }
                            }
                        }
                    }
                }
                // Storing form alias for the reference
                update_post_meta($insert_update_post_id, '_fpsm_form_alias', $form_alias);
                $response['status'] = 200;
                if (($dynamic_post_status == 'draft' && !empty($form_details['form']['post_status'])) || !empty($form_data['post_id'])) {
                    $response['draft_post_id'] = $insert_update_post_id;
                }
                $default_success_message = (!empty($form_details['basic']['form_success_message'])) ? esc_html($form_details['basic']['form_success_message']) : esc_html__('Form submission successful.', 'frontend-post-submission-manager');
                $post_status_message = (!empty($form_details['form']['post_status'][$dynamic_post_status]['success_message'])) ? $form_details['form']['post_status'][$dynamic_post_status]['success_message'] : $default_success_message;
                $response['message'] = $post_status_message;
                // If redirection is enabled for post submission
                if (empty($post_id)) {
                    if (!empty($form_details['basic']['redirection'])) {
                        if ($form_details['basic']['redirection_type'] == 'url') {
                            if (!empty($form_details['basic']['redirection_url'])) {
                                $response['redirect_url'] = esc_url($form_details['basic']['redirection_url']);
                            }
                        } else {
                            $post_url = get_the_permalink($insert_update_post_id);
                            $response['redirect_url'] = $post_url;
                        }
                    }
                } else {
                    if (!empty($form_details['basic']['edit_redirection'])) {
                        if ($form_details['basic']['edit_redirection_type'] == 'url') {
                            if (!empty($form_details['basic']['edit_redirection_url'])) {
                                $response['redirect_url'] = esc_url($form_details['basic']['edit_redirection_url']);
                            }
                        } else {
                            $post_url = get_the_permalink($insert_update_post_id);
                            $response['redirect_url'] = $post_url;
                        }
                    }
                    if (empty($form_details['dashboard']['disable_post_edit_status'])) {
                        if (!empty($form_details['dashboard']['disable_post_edit'])) {
                            $disabled_post_edit_status = array('publish');
                        } else {
                            $disabled_post_edit_status = array();
                        }
                    } else {
                        $disabled_post_edit_status = $form_details['dashboard']['disable_post_edit_status'];
                    }
                    $post_edit_flag = (in_array($dynamic_post_status, $disabled_post_edit_status)) ? false : true;
                    if (!$post_edit_flag && !empty($form_data['dashboard_url'])) {
                        $response['redirect_url'] = esc_url($form_data['dashboard_url']);
                        /**
                         * Filters the redirect time after form submission
                         *
                         * @param int
                         *
                         * @since 1.1.1
                         */
                        $response['redirect_delay'] = apply_filters('fpsm_redirect_wait', 2000);
                    }
                }
                $action = (empty($post_id)) ? 'insert' : 'update';
                /**
                 * Fires when the successful form submission is complete
                 *
                 * @param int $insert_update_post_id
                 * @param array $form_row
                 * @param string $action
                 */
                do_action('fpsm_form_submission_success', $insert_update_post_id, $form_row, $action);
            } else {
                $response['status'] = 403;
                $response['message'] = esc_html__('There occurred some error.', 'frontend-post-submission-manager');
            }
        }
    } else {
        $response['status'] = 403;
        $response['message'] = esc_html__('Invalid form submission', 'frontend-post-submission-manager');
    }
    /**
     * Filters the form process response array
     *
     * @param array $response
     * @param array $form_data
     * @param obj $form_row
     *
     * @since 1.0.0
     */
    $response = apply_filters('fpsm_form_response', $response, $form_data, $form_row);
    echo json_encode($response);
    die();
} else {
    $this->permission_denied();
}
