<?php
$default_allowed_extensions = array('jpg', 'jpeg', 'png', 'gif', 'bmp', 'JPG', 'JPEG', 'PNG', 'BMP');
/**
 * Filters allowed extensions for image field type
 *
 * @param array $default_allowed_extensions
 *
 * @since 1.0.0
 */
$default_allowed_extensions = apply_filters('fpsm_image_allowed_extensions', $default_allowed_extensions);
$upload_file_size_limit = (!empty($field_details['upload_file_size_limit'])) ? $field_details['upload_file_size_limit'] : 5;
$uploader_label = (!empty($field_details['upload_button_label'])) ? $field_details['upload_button_label'] : esc_html__('Upload Image', 'frontend-post-submission-manager');
$multiple_upload = (!empty($field_details['multiple_upload'])) ? 'true' : 'false';
$max_number_uploads = (!empty($field_details['max_number_uploads'])) ? $field_details['max_number_uploads'] : -1;
$allowed_extensions = (!empty($field_details['file_extensions'])) ? $field_details['file_extensions'] : $default_allowed_extensions;
$file_extension_error_message = (!empty($field_details['file_extension_error_message'])) ? $field_details['file_extension_error_message'] : '';
$media_id_array = (!empty($custom_field_saved_value)) ? explode(',', $custom_field_saved_value) : array();
$upload_limit_error_message = $field_details['upload_limit_error_message'];
$upload_filesize_error_message = $field_details['upload_filesize_error_message'];
?>
<div
    class="fpsm-file-uploader"
    id="fpms-file-uploader-<?php echo esc_attr($fpsm_library_obj->generate_random_string()); ?>"
    data-extensions="<?php echo esc_attr(implode('|', $allowed_extensions)); ?>"
    data-extensions-error-message="<?php echo esc_attr($file_extension_error_message); ?>"
    data-file-size-limit="<?php echo esc_attr($upload_file_size_limit); ?>"
    data-upload-filesize-error-message="<?php echo esc_attr($upload_filesize_error_message); ?>"
    data-label="<?php echo esc_attr($uploader_label); ?>"
    data-field-name="<?php echo esc_attr($field_key); ?>"
    data-multiple='<?php echo esc_attr($multiple_upload); ?>'
    data-multiple-upload-limit="<?php echo esc_attr($max_number_uploads); ?>"
    data-multiple-upload-error-message = "<?php echo esc_attr($upload_limit_error_message); ?>">
</div>
<input type="hidden" class="fpsm-upload-count" value="<?php echo count($media_id_array); ?>"/>
<input type="hidden" name="<?php echo esc_attr($field_key); ?>" class="fpsm-media-id" value="<?php echo esc_attr($custom_field_saved_value); ?>"/>
<div class="fpsm-file-preview-wrap">
    <?php
    if (!empty($custom_field_saved_value)) {
        $media_id_array = explode(',', $custom_field_saved_value);
        foreach ($media_id_array as $media_id) {
            $thumbnail_url_obj = wp_get_attachment_image_src($media_id, 'thumbnail');
            $image_title = get_the_title($media_id);
            $attachment_date = get_the_date("U", $media_id);
            $attachment_code = md5($attachment_date);
            $thumbnail_file = get_attached_file($media_id);
            $thumbnail_file_size = $fpsm_library_obj->format_file_size(filesize($thumbnail_file));
            ?>
            <div class="fpsm-file-preview-row" data-media-id="<?php echo esc_attr($media_id); ?>">
                <span class="fpsm-file-preview-column"><img src="<?php echo esc_url($thumbnail_url_obj[0]); ?>"></span>
                <span class="fpsm-file-preview-column"><?php echo esc_html($image_title); ?></span>
                <span class="fpsm-file-preview-column"><?php echo esc_html($thumbnail_file_size); ?></span>
                <span class="fpsm-file-preview-column"><input type="button" class="fpsm-media-delete-button" data-media-id="<?php echo intval($media_id); ?>" data-media-key="<?php echo esc_attr($attachment_code); ?>" value="<?php esc_attr_e('Delete', 'frontend-post-submission-manager'); ?>" data-edit="<?php echo (!empty($edit_post)) ? 'no' : 'no'; ?>"></span>
            </div>
            <?php
        }
    }
    ?>

</div>