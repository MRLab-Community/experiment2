<div class="fpsm-custom-field-add-form">
    <h3><?php esc_html_e('Custom Field', 'frontend-post-submission-manager'); ?></h3>
    <div class="fpsm-field-wrap">
        <label><?php esc_html_e('Label', 'frontend-post-submission-manager'); ?></label>
        <div class="fpsm-field">
            <input type="text" id="fpsm-custom-field-label"/>
        </div>
    </div>
    <div class="fpsm-field-wrap">
        <label><?php esc_html_e('Meta Key', 'frontend-post-submission-manager'); ?></label>
        <div class="fpsm-field">
            <input type="text" id="fpsm-custom-field-meta-key"/>
            <p class="description"><?php esc_html_e('Please use plain text without any special characters for meta key and use underscore(_) instead of white space.', 'frontend-post-submission-manager'); ?></p>
        </div>
    </div>
    <div class="fpsm-field-wrap">
        <label><?php esc_html_e('Field Type', 'frontend-post-submission-manager'); ?></label>
        <div class="fpsm-field">
            <select id="fpsm-custom-field-type" style="display:none">
                <?php
                $custom_field_type_list = FPSM_CUSTOM_FIELD_TYPE_LIST;
                foreach ($custom_field_type_list as $custom_field_type => $custom_field_details) {
                    $custom_field_label = $custom_field_details['label'];
                    ?>
                    <option value="<?php echo esc_attr($custom_field_type); ?>"><?php echo esc_html($custom_field_label); ?></option>
                    <?php
                }
                ?>

            </select>
            <div class="fpsm-custom-field-btns-wrap">
                <?php
                foreach ($custom_field_type_list as $custom_field_type => $custom_field_details) {
                    $custom_field_label = $custom_field_details['label'];
                    ?>
                    <div class="fpsm-custom-fld-btn fpsm-custom-field-type-trigger-btn <?php echo ($custom_field_type == 'textfield') ? 'btn-selected' : ''; ?>" data-field-type="<?php echo esc_attr($custom_field_type); ?>">
                        <i class="<?php echo esc_attr($custom_field_details['icon']); ?>"></i> <?php echo esc_html($custom_field_label); ?>
                    </div>

                <?php }
                ?>
            </div>
        </div>
    </div>

    <div class="fpsm-field-wrap">
        <div class="fpsm-field">
            <input type="button" class="fpsm-button-secondary fpsm-custom-field-add-trigger" value="<?php esc_attr_e('Add', 'frontend-post-submission-manager'); ?>"/>
        </div>

    </div>
</div>