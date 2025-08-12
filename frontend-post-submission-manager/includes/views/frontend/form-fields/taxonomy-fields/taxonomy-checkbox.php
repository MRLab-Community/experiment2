
<?php
defined('ABSPATH') or die('No script kiddies please!!');


$display_type = $field_details['display_type'];
$display_class = 'fpsm-' . $display_type . '-checkbox';
$args = array(
    'terms' => $terms_hierarchy,
    'exclude' => $terms_exclude,
    'hierarchical' => $taxonomy_details->hierarchical,
    'html' => '',
    'field_name' => $field_key,
    'checked' => array(),
    'class' => $display_class,
    'checked_terms' => (!empty($edit_post_terms_id)) ? $edit_post_terms_id : array()
);



if (count($terms_hierarchy) > 0) {
    $checkbox_html = $fpsm_library_obj->print_terms_as_checkbox($args);
    echo $fpsm_library_obj->sanitize_html($checkbox_html);
}
