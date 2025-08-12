<?php

defined('ABSPATH') or die('No script kiddies please!!');
if (!class_exists('FPSM_Ajax_Admin')) {

    class FPSM_Ajax_Admin {

        function __construct() {
            add_action('wp_ajax_fpsm_form_add_action', array($this, 'process_form_add'));
            add_action('wp_ajax_nopriv_fpsm_form_add_action', array($this, 'permission_denied'));
            add_action('wp_ajax_fpsm_form_edit_action', array($this, 'process_form_edit'));
            add_action('wp_ajax_nopriv_fpsm_form_edit_action', array($this, 'permission_denied'));
            add_action('wp_ajax_fpsm_form_delete_action', array($this, 'process_form_delete'));
            add_action('wp_ajax_nopriv_fpsm_form_delete_action', array($this, 'permission_denied'));
            add_action('wp_ajax_fpsm_settings_save_action', array($this, 'save_global_settings'));
            add_action('wp_ajax_nopriv_fpsm_settings_save_action', array($this, 'permission_denied'));
            /**
             * Form copy ajax
             *
             */
            add_action('wp_ajax_fpsm_form_copy_action', array($this, 'form_copy_action'));
            add_action('wp_ajax_nopriv_fpsm_form_copy_action', array($this, 'permission_denied'));
        }

        function process_form_add() {
            if ($this->admin_ajax_nonce_verify()) {
                /**
                 * Fires on starting of form add ajax
                 *
                 * @since 1.0.0
                 */
                do_action('fpsm_before_form_add_ajax');
                $form_data = stripslashes_deep($_POST['form_data']);
                parse_str($form_data, $form_data);
                global $fpsm_library_obj;
                $form_data = $fpsm_library_obj->sanitize_array($form_data);
                $form_title = $form_data['form_title'];
                $form_alias = $form_data['form_alias'];
                $post_type = $form_data['post_type'];
                $form_type = $form_data['form_type'];
                $form_status = (!empty($form_data['form_status'])) ? 1 : 0;
                if (empty($form_title) || empty($form_alias)) {
                    $response['status'] = 403;
                    $response['message'] = esc_html__('Form title or Alias cannot be empty.', 'frontend-post-submission-manager');
                } else {
                    if ($fpsm_library_obj->is_alias_available($form_alias)) {
                        global $wpdb;
                        $form_details = $fpsm_library_obj->get_default_form_details($post_type, $form_type);

                        $insert_check = $wpdb->insert(
                            FPSM_FORM_TABLE,
                            array(
                                'form_title' => $form_title,
                                'form_alias' => $form_alias,
                                'form_details' => maybe_serialize($form_details),
                                'form_status' => $form_status,
                                'form_type' => $form_type,
                                'post_type' => $post_type
                            ),
                            array('%s', '%s', '%s', '%d', '%s', '%s')
                        );
                        if ($insert_check) {
                            $form_id = $wpdb->insert_id;
                            $response['status'] = 200;
                            $response['message'] = esc_html__('Form added successfully. Redirecting...', 'frontend-post-submission-manager');
                            $response['redirect_url'] = admin_url('admin.php?page=fpsm&action=edit_form&form_id=' . $form_id);
                        } else {
                            $response['status'] = 403;
                            $response['message'] = esc_html__('Something went wrong. Please try again later.', 'frontend-post-submission-manager');
                        }
                    } else {
                        $response['status'] = 403;
                        $response['message'] = esc_html__('Form alias already used. Please use some other alias.', 'frontend-post-submission-manager');
                    }
                }

                die(json_encode($response));
            } else {
                $this->permission_denied();
            }
        }

        function permission_denied() {
            die('No script kiddies please!!');
        }

        /**
         * Ajax nonce verification for ajax in admin
         *
         * @return bolean
         * @since 1.0.0
         */
        function admin_ajax_nonce_verify() {
            if (!empty($_POST['_wpnonce']) && wp_verify_nonce($_POST['_wpnonce'], 'fpsm_backend_ajax_nonce')) {
                return true;
            } else {
                return false;
            }
        }

        /**
         * Process form edit
         *
         * @return void
         * @since 1.0.0
         */
        function process_form_edit() {
            if ($this->admin_ajax_nonce_verify()) {
                /**
                 * Fires on starting of form edit ajax
                 *
                 * @since 1.0.0
                 */
                do_action('fpsm_before_form_edit_ajax');
                $form_data = stripslashes_deep($_POST['form_data']);
                parse_str($form_data, $form_data);
                global $fpsm_library_obj;
                $sanitize_rule = array('field_label' => 'html', 'notification_message' => 'to_br', 'login_note' => 'html', 'field_note' => 'html', 'custom_css' => 'to_br', 'post_not_found_message' => 'to_br');
                $form_data = $fpsm_library_obj->sanitize_array($form_data, $sanitize_rule);
                $form_id = $form_data['form_id'];
                $form_title = $form_data['form_title'];
                $form_alias = $form_data['form_alias'];
                $post_type = $form_data['post_type'];
                $form_type = $form_data['form_type'];
                $form_status = (!empty($form_data['form_status'])) ? 1 : 0;
                if (empty($form_title) || empty($form_alias)) {
                    $response['status'] = 403;
                    $response['message'] = esc_html__('Form title or Alias cannot be empty.', 'frontend-post-submission-manager');
                } else {
                    if ($fpsm_library_obj->is_alias_available($form_alias, $form_id)) {
                        global $wpdb;

                        $wpdb->update(
                            FPSM_FORM_TABLE,
                            array(
                                'form_title' => $form_title,
                                'form_alias' => $form_alias,
                                'post_type' => $post_type,
                                'form_details' => maybe_serialize($form_data['form_details']),
                                'form_status' => $form_status,
                            ),
                            array('form_id' => $form_id),
                            array('%s', '%s', '%s', '%s', '%d'),
                            array('%d')
                        );

                        $response['status'] = 200;
                        $response['message'] = esc_html__('Form updated successfully.', 'frontend-post-submission-manager');
                    } else {
                        $response['status'] = 403;
                        $response['message'] = esc_html__('Form alias already used. Please use some other alias.', 'frontend-post-submission-manager');
                    }
                }
                die(json_encode($response));
            } else {
                $this->permission_denied();
            }
        }

        /**
         * Process form delete
         *
         * @since 1.0.0
         */
        function process_form_delete() {
            if ($this->admin_ajax_nonce_verify()) {
                /**
                 * Fires on starting of form edit ajax
                 *
                 * @since 1.0.0
                 */
                do_action('fpsm_before_form_delete_ajax');
                $form_id = intval($_POST['form_id']);
                global $wpdb;
                $delete_check = $wpdb->delete(FPSM_FORM_TABLE, array('form_id' => $form_id), array('%d'));
                if ($delete_check) {
                    $response['status'] = 200;
                    $response['message'] = esc_html__('Form deleted successfully.', 'frontend-post-submission-manager');
                } else {
                    $response['status'] = 403;
                    $response['message'] = esc_html__('Something went wrong. Please try again.', 'frontend-post-submission-manager');
                }
                die(json_encode($response));
            } else {
                $this->permission_denied();
            }
        }

        /**
         * Save global settings
         *
         * @since 1.0.0
         */
        function save_global_settings() {
            if ($this->admin_ajax_nonce_verify()) {
                /**
                 * Fires on starting of global settings save ajax
                 *
                 * @since 1.0.0
                 */
                do_action('fpsm_before_global_settings_save_ajax');
                global $fpsm_library_obj;
                $form_data = stripslashes_deep($_POST['form_data']);
                parse_str($form_data, $form_data);
                $form_data = $fpsm_library_obj->sanitize_array($form_data);
                $fpsm_settings = $form_data['fpsm_settings'];
                update_option('fpsm_settings', $fpsm_settings);
                $response['status'] = 200;
                $response['message'] = esc_html__('Settings saved successfully', 'frontend-post-submission-manager');
                die(json_encode($response));
            } else {
                $this->permission_denied();
            }
        }

        function form_copy_action() {
            if ($this->admin_ajax_nonce_verify()) {
                /**
                 * Fires on starting of form copy ajax
                 *
                 * @since 1.0.0
                 */
                do_action('fpsm_before_form_copy_ajax');
                $form_id = intval($_POST['form_id']);
                global $wpdb;
                global $fpsm_library_obj;
                $form_row = $fpsm_library_obj->get_form_row_by_id($form_id);
                $form_alias = $form_row->form_alias . '_' . $fpsm_library_obj->generate_random_string(4);
                $copy_check = $wpdb->insert(
                    FPSM_FORM_TABLE,
                    array(
                        'form_title' => $form_row->form_title . ' - Copy',
                        'form_alias' => $form_alias,
                        'form_details' => $form_row->form_details,
                        'form_status' => $form_row->form_status,
                        'post_type' => $form_row->post_type,
                        'form_type' => $form_row->form_type
                    ),
                    array('%s', '%s', '%s', '%d', '%s', '%s')
                );
                if ($copy_check) {
                    $response['status'] = 200;
                    $response['message'] = esc_html__('Form copied successfully.Redirecting..', 'frontend-post-submission-manager');
                    $response['redirect_url'] = admin_url('admin.php?page=fpsm');
                } else {
                    $response['status'] = 403;
                    $response['message'] = esc_html__('There occurred some error. Please try again later.', 'frontend-post-submission-manager');
                }
                echo json_encode($response);
                die();
            } else {
                $this->permission_denied();
            }
        }
    }

    new FPSM_Ajax_Admin();
}
