<?php
$layout_settings = (!empty($form_details['layout'])) ? $form_details['layout'] : array();
?>
<div class="fpsm-settings-each-section fpsm-display-none" data-tab="layout">
    <div class="fpsm-field-wrap">
        <label><?php esc_html_e('Form Template', 'frontend-post-submission-manager'); ?></label>
        <div class="fpsm-field">
            <select name="form_details[layout][template]" class="fpsm-form-template">
                <?php
                $selected_template = (!empty($layout_settings['template'])) ? $layout_settings['template'] : 'template-1';
                for ($i = 1; $i <= 22; $i++) {
                    ?>
                    <option value="template-<?php echo intval($i); ?>" <?php selected($selected_template, 'template-' . $i); ?>><?php esc_html_e(sprintf('Template %d', $i), 'frontend-post-submission-manager'); ?></option>
                    <?php
                }
                ?>
            </select>
            <div class="fpsm-form-template-preview">
                <?php
                for ($i = 1; $i <= 22; $i++) {
                    ?>
                    <img src="<?php echo FPSM_URL . '/assets/images/form-template-previews/template-' . $i . '.jpg'; ?>" data-template-id="<?php echo 'template-' . $i; ?>" class="fpsm-form-template-preview-img <?php echo ($selected_template != 'template-' . $i) ? 'fpsm-display-none' : ''; ?>" loading="lazy"/>
                    <?php
                }
                ?>
            </div>
        </div>
    </div>
    <div class="fpsm-field-wrap">
        <label><?php esc_html_e('Custom Fields Display Template', 'frontend-post-submission-manager'); ?></label>
        <div class="fpsm-field">
            <select name="form_details[layout][custom_field_display_template]" class="fpsm-custom-field-template-trigger">
                <?php
                $selected_template = (!empty($layout_settings['custom_field_display_template'])) ? $layout_settings['custom_field_display_template'] : 'template-1';
                for ($i = 1; $i <= 6; $i++) {
                    ?>
                    <option value="template-<?php echo intval($i); ?>" <?php selected($selected_template, 'template-' . $i); ?>><?php esc_html_e(sprintf('Template %d', $i), 'frontend-post-submission-manager'); ?></option>
                    <?php
                }
                ?>
            </select>
            <div class="fpsm-post-template-preview">
                <?php
                for ($i = 1; $i <= 6; $i++) {
                    ?>
                    <img src="<?php echo FPSM_URL . '/assets/images/post-field-previews/template-' . $i . '.jpg'; ?>" data-template-id="<?php echo 'template-' . $i; ?>" class="fpsm-post-template-preview-img <?php echo ($selected_template != 'template-' . $i) ? 'fpsm-display-none' : ''; ?>" loading="lazy"/>
                    <?php
                }
                ?>
            </div>
        </div>
    </div>
</div>