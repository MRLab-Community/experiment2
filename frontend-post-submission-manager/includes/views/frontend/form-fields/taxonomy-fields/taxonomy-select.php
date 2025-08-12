<div class="fpsm-select-field <?php echo (!empty($field_details['select_multiple'])) ? 'fpsm-multiple-select' : ''; ?>">
    <select name="<?php echo esc_attr($field_key) ?><?php if (!empty($field_details['select_multiple'])) { ?>[]<?php } ?>" <?php if (!empty($field_details['select_multiple'])) { ?>multiple="multiple" <?php } ?> id="<?php echo esc_attr($field_id); ?>">
        <option value=""><?php echo (!empty($field_details['first_option_label'])) ? esc_html($field_details['first_option_label']) : esc_html__(sprintf('Choose %s', $taxonomy_details->label), 'frontend-post-submission-manager'); ?></option>
        <?php
        $args = array(
            'terms' => $terms_hierarchy,
            'exclude' => $terms_exclude,
            'hierarchical' => $taxonomy_details->hierarchical,
            'html' => '',
            'selected_terms' => (!empty($edit_post_terms_id)) ? $edit_post_terms_id : array()
        );
        if (count($terms_hierarchy) > 0) {
            $option = $fpsm_library_obj->print_terms_as_option($args);
            echo $fpsm_library_obj->sanitize_html($option);
        }
        ?>
    </select>
</div>