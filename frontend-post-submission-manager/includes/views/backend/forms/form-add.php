<?php
defined('ABSPATH') or die('No script kiddies please!!');
global $fpsm_library_obj;
?>
<div class="wrap fpsm-wrap">
    <div class="fpsm-header fpsm-clearfix">
        <h1 class="fpsm-floatLeft"><?php esc_html_e('Frontend Post Submission Manager', 'frontend-post-submission-manager'); ?></h1>
        <div class="fpsm-add-wrap">
            <input type="button" value="<?php esc_html_e('Save Form', 'frontend-post-submission-manager'); ?>" class="fpsm-primary-button fpsm-form-save" data-form="fpsm-add-form" />
            <a href="<?php echo admin_url('admin.php?page=fpsm'); ?>" class="fpsm-button-primary btn-cancel">Cancel</a>
        </div>
    </div>
    <form class="fpsm-form fpsm-add-form">
        <h2 class="fpsm-floatRight"><?php esc_html_e('Add New Form', 'frontend-post-submission-manager'); ?></h2>
        <div class="fpsm-form-element-wrap">
            <div class="fpsm-field-wrap">
                <label><?php esc_html_e('Form Status', 'frontend-post-submission-manager'); ?></label>
                <div class="fpsm-field">
                    <input type="checkbox" name="form_status" value="1" />
                </div>
            </div>
            <div class="fpsm-field-wrap">
                <label><?php esc_html_e('Form Title', 'frontend-post-submission-manager'); ?></label>
                <div class="fpsm-field">
                    <input type="text" name="form_title" />
                </div>
            </div>
            <div class="fpsm-field-wrap">
                <label><?php esc_html_e('Form Alias', 'frontend-post-submission-manager'); ?></label>
                <div class="fpsm-field">
                    <input type="text" name="form_alias" />
                    <p class="description"><?php esc_html_e('Alias should be unique and shouldn\'t contain any special characters and please use _ instead of space.', 'frontend-post-submission-manager'); ?>
                    </p>
                </div>
            </div>
            <div class="fpsm-field-wrap">
                <label><?php esc_html_e('Post Type', 'frontend-post-submission-manager'); ?></label>
                <div class="fpsm-field">
                    <select name="post_type">
                        <?php
                        $post_types = $fpsm_library_obj->get_registered_post_types();
                        /**
                         * Filters post types list
                         * 
                         * @param mixed $post_types
                         * @since 1.3.9
                         */
                        $post_types = apply_filters('fpsm_post_types_list', $post_types);
                        if (!empty($post_types)) {
                            foreach ($post_types as $post_type) {
                        ?>
                                <option value="<?php echo esc_attr($post_type->name); ?>"><?php echo esc_html($post_type->label); ?></option>

                        <?php
                            }
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="fpsm-field-wrap">
                <label><?php esc_html_e('Form Type', 'frontend-post-submission-manager'); ?></label>
                <div class="fpsm-field">
                    <select name="form_type">
                        <option value="login_require"><?php esc_html_e('Login require form', 'frontend-post-submission-manager'); ?></option>
                        <option value="guest"><?php esc_html_e('Guest form', 'frontend-post-submission-manager'); ?></option>
                    </select>
                </div>
            </div>
        </div>
    </form>
</div>