<?php

defined('ABSPATH') or die('No script kiddies please!!');
$form_type = $form_row->form_type;
if ($form_type == 'login_require') {
    if (is_user_logged_in()) {
        include(FPSM_PATH . '/includes/views/frontend/form-html.php');
    } else {
        global $login_form_render;
        if (empty($login_form_render)) {
            include(FPSM_PATH . '/includes/views/frontend/login-html.php');
            $GLOBALS['login_form_render'] = true;
        }
    }
} else {
    include(FPSM_PATH . '/includes/views/frontend/form-html.php');
}
