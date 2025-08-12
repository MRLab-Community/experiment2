<?php
defined('ABSPATH') or die('No script kiddies please!!');
$fpsm_form_alias = get_post_meta($post->ID, '_fpsm_form_alias', true);
if (!empty($fpsm_form_alias)) {
    global $fpsm_library_obj;
    $form_row = $fpsm_library_obj->get_form_row_by_alias($fpsm_form_alias);
    if (empty($form_row)) {
        return;
    }
    if (empty($form_row->form_details)) {
        return;
    }
    $fpsm_disable_metabox_update = get_post_meta($post->ID, 'fpsm_disable_metabox_update', true);
?>
    <div class="fpsm-field-wrap">
        <label><?php esc_html_e('Frontend Form', 'frontend-post-submission-manager'); ?></label>
        <div class="fpsm-field"><a href="<?php echo admin_url('admin.php?page=fpsm&action=edit_form&form_id=' . $form_row->form_id); ?>" target="_blank" class="button-secondary"><?php echo esc_html($form_row->form_title); ?></a></div>
    </div>
    <div class="fpsm-field-wrap">
        <label for=""><?php esc_html_e('Disable Update', 'frontend-post-submission-manager'); ?></label>
        <div class="fpsm-field">
            <input type="checkbox" name="fpsm_disable_metabox_update" value="1" <?php checked($fpsm_disable_metabox_update, true); ?> />
            <p class="description"><?php esc_html_e('Please check this if you want to disable the updating of the fields received from the form', 'frontend-post-submission-manager'); ?></p>
        </div>

    </div>
    <?php
    $form_details = maybe_unserialize($form_row->form_details);

    //if fields are empty
    if (empty($form_details['form']['fields'])) {
        return;
    }
    $form_fields = $form_details['form']['fields'];
    foreach ($form_fields as $field_key => $field_details) {
        // If field is enabled in the form


        if ($fpsm_library_obj->is_custom_field_key($field_key)) {
            if (!empty($field_details['show_on_form'])) {
                $custom_field_meta_key = $fpsm_library_obj->get_meta_key_by_field_key($field_key);
                $custom_field_value = get_post_meta($post->ID, $custom_field_meta_key, true);
                $custom_field_name = "fpsm_custom_fields[$custom_field_meta_key]";
                $field_type = $field_details['field_type'];
    ?>
                <div class="fpsm-field-wrap">
                    <label><?php echo esc_html($field_details['field_label']); ?></label>
                    <div class="fpsm-field fpsm-custom-<?php echo esc_attr($field_type); ?>">
                        <input type="hidden" name="fpsm_included_custom_fields[]" value="<?php echo esc_attr($custom_field_meta_key); ?>" />
                        <?php
                        switch ($field_type) {
                            case 'textfield':
                            case 'hidden':
                        ?>
                                <input type="text" name="<?php echo esc_attr($custom_field_name); ?>" value="<?php echo esc_attr($custom_field_value); ?>" />
                            <?php
                                break;
                            case 'textarea':
                            ?>
                                <textarea name="<?php echo esc_attr($custom_field_name); ?>"><?php echo esc_html($custom_field_value); ?></textarea>
                            <?php
                                break;
                            case 'select':
                            ?>
                                <select name="<?php echo esc_attr($custom_field_name); ?>">
                                    <?php
                                    if (!empty($field_details['options'])) {
                                        foreach ($field_details['options'] as $option_count => $option) {
                                    ?>
                                            <option value="<?php echo esc_attr($field_details['values'][$option_count]) ?>" <?php echo ($custom_field_value == $field_details['values'][$option_count]) ? 'selected="selected"' : ''; ?>><?php echo esc_html($option); ?></option>
                                    <?php
                                        }
                                    }
                                    ?>
                                </select>
                            <?php
                                break;
                            case 'checkbox':
                                $display_type = $field_details['display_type'];
                                $display_class = 'fpsm-checkbox-' . $display_type;
                                $custom_field_value = (empty($custom_field_value)) ? array() : $custom_field_value;
                            ?>
                                <div class="fpsm-checkbox-list-wrap <?php echo esc_attr($display_class); ?>">
                                    <?php
                                    if (!empty($field_details['options'])) {
                                        foreach ($field_details['options'] as $option_count => $option) {
                                            $attr_id = 'fpsm-' . $custom_field_meta_key . '-' . $option_count;
                                    ?>
                                            <div class="fpsm-checkbox">
                                                <input class="fpsm-disable-checkbox-toggle" type="checkbox" name="<?php echo esc_attr($custom_field_name); ?>[]" value="<?php echo esc_attr($field_details['values'][$option_count]); ?>" <?php echo (in_array($field_details['values'][$option_count], $custom_field_value)) ? 'checked="checked"' : ''; ?> id="<?php echo esc_attr($attr_id); ?>" />
                                                <label for="<?php echo esc_attr($attr_id); ?>"><?php echo esc_html($option); ?></label>
                                            </div>
                                    <?php
                                        }
                                    }
                                    ?>
                                </div>
                            <?php
                                break;
                            case 'radio':
                            ?>
                                <?php
                                $display_type = $field_details['display_type'];
                                $display_class = 'fpsm-radio-' . $display_type;
                                ?>
                                <div class="fpsm-checkbox-list-wrap <?php echo esc_attr($display_class); ?>">
                                    <?php
                                    if (!empty($field_details['options'])) {
                                    ?>

                                        <?php
                                        foreach ($field_details['options'] as $option_count => $option) {
                                            $attr_id = 'fpsm-' . $custom_field_meta_key . '-' . $option_count;
                                        ?>
                                            <div class="fpsm-radio">
                                                <input type="radio" name="<?php echo esc_attr($custom_field_name); ?>" value="<?php echo esc_attr($field_details['values'][$option_count]); ?>" <?php checked($custom_field_value, $field_details['values'][$option_count]); ?> id="<?php echo esc_attr($attr_id); ?>" />
                                                <label for="<?php echo esc_attr($attr_id); ?>"><?php echo esc_html($option); ?></label>
                                            </div>
                                        <?php
                                        }
                                        ?>

                                    <?php
                                    }
                                    ?>
                                </div>
                            <?php
                                break;
                            case 'number':
                            ?>
                                <input type="number" name="<?php echo esc_attr($custom_field_name); ?>" min="<?php echo esc_attr($field_details['min_limit']); ?>" max="<?php echo esc_attr($field_details['max_limit']); ?>" value="<?php echo esc_attr($custom_field_value); ?>" />
                            <?php
                                break;
                            case 'email':
                            ?>
                                <input type="email" name="<?php echo esc_attr($custom_field_name); ?>" value="<?php echo esc_attr($custom_field_value); ?>" />
                            <?php
                                break;
                            case 'datepicker':
                            ?>
                                <div class="fpsm-date-picker">
                                    <?php
                                    if (empty($field_details['string_format'])) {
                                    ?>
                                        <input type="text" name="<?php echo esc_attr($custom_field_name); ?>" class="fpsm-front-datepicker" data-date-format="<?php echo esc_attr($field_details['date_format']); ?>" value="<?php echo esc_attr($custom_field_value); ?>" />
                                    <?php
                                    } else {
                                    ?>
                                        <input type="text" name="<?php echo esc_attr($custom_field_name); ?>" value="<?php echo esc_attr($custom_field_value); ?>" />
                                        <p class="description"><?php esc_html_e('Note: Date is showing up in the string format because you had enabled the string format option.', 'frontend-post-submission-manager'); ?></p>
                                    <?php
                                    }
                                    ?>

                                </div>
                            <?php
                                break;
                            case 'file_uploader':
                                $uploader_label = (!empty($field_details['upload_button_label'])) ? $field_details['upload_button_label'] : esc_html__('Upload Image', 'frontend-post-submission-manager');
                            ?>
                                <input type="button" class="fpsm-metabox-fileuploader button-secondary" value="<?php echo esc_attr($uploader_label); ?>" />
                                <div class="fpsm-file-preview-wrap">
                                    <div class="fpsm-uploaded-files-list">
                                        <?php
                                        if (!empty($custom_field_value)) {
                                            $media_ids = explode(',', $custom_field_value);
                                            foreach ($media_ids as $media_id) {
                                                $media_thumbnail_url = wp_get_attachment_image_src($media_id, 'thumbnail', true);
                                                $media_url = wp_get_attachment_url($media_id);
                                        ?>
                                                <div class="fpsm-file-preview-row">
                                                    <span class="fpsm-file-preview-column"><img src="<?php echo esc_url($media_thumbnail_url[0]); ?>" /></span>
                                                    <span class="fpsm-file-preview-column"><a href="<?php echo admin_url() . 'upload.php?item=' . $media_id; ?>" target="_blank"><?php echo get_the_title($media_id); ?></a></span>
                                                    <span class="fpsm-file-preview-column"><?php echo esc_html($fpsm_library_obj->get_attachment_filesize($media_id)); ?></span>
                                                    <span class="fpsm-file-preview-column">
                                                        <a href="<?php echo esc_url($media_url); ?>" target="_blank" class="fpsm-file-view-button"><span class="dashicons dashicons-download"></span></a>
                                                        <input type="button" class="fpsm-media-remove-button" data-media-id='<?php echo intval($media_id); ?>' value="<?php esc_html_e('Remove', 'frontend-post-submission-manager'); ?>" />
                                                    </span>
                                                </div>

                                        <?php
                                            }
                                        }
                                        ?>
                                    </div>

                                    <input type="hidden" name="<?php echo esc_attr($custom_field_name); ?>" value="<?php echo esc_attr($custom_field_value); ?>" class="fpsm-fileuploader-value" />
                                </div>
                            <?php
                                break;
                            case 'url':
                            ?>
                                <input type="url" name="<?php echo esc_attr($custom_field_name); ?>" value="<?php echo esc_url($custom_field_value); ?>" />
                            <?php
                                break;
                            case 'tel':
                            ?>
                                <input type="tel" name="<?php echo esc_attr($custom_field_name); ?>" value="<?php echo esc_attr($custom_field_value); ?>" />
                            <?php
                                break;
                            case 'youtube':
                                if (strpos('embed', $custom_field_value)) {
                                    $youtube_embed_url = $custom_field_value;
                                } else {
                                    $url_array = explode('=', $custom_field_value);
                                    $youtube_embed_url = 'https://www.youtube.com/embed/' . end($url_array);
                                }
                                $width = $field_details['embed_width'];
                                $height = $field_details['embed_height'];
                            ?>
                                <iframe class="fpsm-youtube-embed-iframe" width="<?php echo esc_attr($width); ?>" height="<?php echo esc_attr($height); ?>" src="<?php echo esc_url($youtube_embed_url); ?>" allowfullscreen>
                                </iframe>
                            <?php
                                break;
                            case 'wp_editor':
                                $custom_field_saved_value = $custom_field_value;
                                $field_key = $custom_field_name;
                            ?>
                                <input type="hidden" name="fpsm_sanitize_rule[<?php echo esc_attr($custom_field_meta_key); ?>]" value="html" />
                        <?php
                                include(FPSM_PATH . '/includes/views/frontend/form-fields/custom-field-types/front-wp_editor.php');
                                break;
                        }
                        ?>
                    </div>
                </div>
            <?php
            }
        } else if ($field_key == 'author_name' && !empty($field_details['show_on_form'])) {
            ?>
            <div class="fpsm-field-wrap">
                <label><?php echo esc_html($field_details['field_label']); ?></label>
                <div class="fpsm-field">
                    <input type="text" name="fpsm_custom_fields[fpsm_author_name]" value="<?php echo esc_attr(get_post_meta($post->ID, 'fpsm_author_name', true)); ?>" />
                    <input type="hidden" name="fpsm_included_custom_fields[]" value="fpsm_author_name" />
                </div>
            </div>
        <?php
        } else if ($field_key == 'author_email' && !empty($field_details['show_on_form'])) {
        ?>
            <div class="fpsm-field-wrap">
                <label><?php echo esc_html($field_details['field_label']); ?></label>
                <div class="fpsm-field">
                    <input type="text" name="fpsm_custom_fields[fpsm_author_email]" value="<?php echo esc_attr(get_post_meta($post->ID, 'fpsm_author_email', true)); ?>" />
                    <input type="hidden" name="fpsm_included_custom_fields[]" value="fpsm_author_email" />
                </div>
            </div>
<?php
        }
    }
    wp_nonce_field('fpsm_metabox_nonce', 'fpsm_metabox_nonce_field');
}
