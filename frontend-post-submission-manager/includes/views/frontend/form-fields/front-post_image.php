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
$file_extension_error_message = (!empty($field_details['file_extension_error_message'])) ? $field_details['file_extension_error_message'] : '';
$upload_filesize_error_message = $field_details['max_size_error_message'];
if (!empty($edit_post) && has_post_thumbnail($post_id)) {
    $post_thumbnail_id = get_post_thumbnail_id($edit_post);
    $post_thumbnail_url = get_the_post_thumbnail_url($edit_post);
    $post_image_title = get_the_title($post_thumbnail_id);
    $attachment_date = get_the_date("U", $post_thumbnail_id);
    $attachment_code = md5($attachment_date);
    $post_thumbnail_file = get_attached_file($post_thumbnail_id);
    $post_thumbnail_file_size = $fpsm_library_obj->format_file_size(filesize($post_thumbnail_file));
}
$uploader_type = (!empty($field_details['uploader_type'])) ? $field_details['uploader_type'] : 'custom';
if ($uploader_type == 'custom') {
?>

    <div class="fpsm-file-uploader" id="fpms-file-uploader-<?php echo esc_attr($fpsm_library_obj->generate_random_string()); ?>" data-extensions="<?php echo esc_attr(implode('|', $default_allowed_extensions)); ?>" data-extensions-error-message="<?php echo esc_attr($file_extension_error_message); ?>" data-file-size-limit="<?php echo intval($upload_file_size_limit); ?>" data-upload-filesize-error-message="<?php echo esc_attr($upload_filesize_error_message); ?>" data-label="<?php echo esc_attr($uploader_label); ?>" data-field-name="<?php echo esc_attr($field_key); ?>">
    </div>
<?php } else {
?>
    <div class="qq-uploader fpsm-wp-media-uploader-wrap">
        <div class="qq-upload-button fpsm-wp-media-uploader" data-extension-error="<?php echo esc_attr($file_extension_error_message); ?>">
            <?php echo esc_attr($uploader_label); ?>
        </div>
    </div>
<?php
} ?>
<input type="hidden" class="fpsm-upload-count" value="<?php echo (!empty($post_thumbnail_id)) ? 1 : 0; ?>" />
<input type="hidden" name="<?php echo esc_attr($field_key); ?>" class="fpsm-media-id" value="<?php echo (!empty($post_thumbnail_id)) ? intval($post_thumbnail_id) : ''; ?>" />
<div class="fpsm-file-preview-wrap">
    <?php
    if (!empty($edit_post) && has_post_thumbnail($post_id)) {
    ?>
        <div class="fpsm-file-preview-row">
            <span class="fpsm-file-preview-column"><img src="<?php echo esc_url($post_thumbnail_url); ?>"></span>
            <span class="fpsm-file-preview-column"><?php echo esc_html($post_image_title); ?></span>
            <span class="fpsm-file-preview-column"><?php echo esc_html($post_thumbnail_file_size); ?></span>
            <span class="fpsm-file-preview-column"><input type="button" class="fpsm-media-delete-button" data-media-id="<?php echo intval($post_thumbnail_id); ?>" data-media-key="<?php echo esc_attr($attachment_code); ?>" value="<?php esc_attr_e('Delete', 'frontend-post-submission-manager'); ?>"></span>
        </div>
    <?php } ?>
</div>