<?php
defined('ABSPATH') or die('No script kiddies please!!');
?>
<div class="fpsm-login-message-wrap fpsm-login-message-<?php echo esc_attr($form_template); ?>">
    <div class="fpsm-login-message"><?php echo $fpsm_library_obj->sanitize_html($login_settings['login_message']); ?></div>
    <a href="<?php echo esc_url($login_settings['login_link_url']); ?>" class="fpsm-login-link-button"><?php echo esc_html($login_settings['login_link_label']); ?></a>
</div>