<div class="fpsm-form-message"></div>
<?php
$custom_field_type_list = FPSM_CUSTOM_FIELD_TYPE_LIST;
if (!empty($custom_field_type_list)) {
    foreach ($custom_field_type_list as $custom_field_type => $custom_field_details) {
        $custom_field_type_label = $custom_field_details['label'];
        $field_name_prefix = 'form_details[form][fields][{{data.field_key}}]';
        $show_hide_toggle_class = '{{data.meta_key}}';
        $field_details['field_label'] = '{{data.label}}';
        $field_type = $custom_field_type;
        $field_key = '{{data.field_key}}';
?>
        <script type="text/html" id="tmpl-custom-<?php echo esc_attr($custom_field_type); ?>">
            <?php include(FPSM_PATH . '/includes/views/backend/js-templates/tmpl-custom-field-holder.php'); ?>
        </script>
<?php
    }
}
?>

<script type="text/html" id="tmpl-option">
    <?php include(FPSM_PATH . '/includes/views/backend/js-templates/tmpl-option.php'); ?>
</script>
<script type="text/html" id="tmpl-fpsm-metabox-file-preview">
    <div class="fpsm-file-preview-row">
        <span class="fpsm-file-preview-column"><img src="{{data.media_icon}}" /></span>
        <span class="fpsm-file-preview-column"><a href="{{data.media_edit_url}}" target="_blank">{{data.media_name}}</a></span>
        <span class="fpsm-file-preview-column">{{data.media_size}}</span>
        <span class="fpsm-file-preview-column">
            <a href="{{data.media_url}}" target="_blank" class="fpsm-file-view-button"><span class="dashicons dashicons-download"></span></a>
            <input type="button" class="fpsm-media-remove-button" data-media-id='{{data.media_id}}' value="<?php esc_html_e('Remove', 'frontend-post-submission-manager'); ?>" />
        </span>
    </div>
</script>