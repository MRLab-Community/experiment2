<?php

$field_key_array = explode('|', $field_key);
$taxonomy = end($field_key_array);
$taxonomy_field_type = $field_details['field_type'];
$taxonomy_details = get_taxonomy($taxonomy);

if (!empty($edit_post)) {
    $edit_post_terms = get_the_terms($edit_post, $taxonomy);
    if (!empty($edit_post_terms)) {
        if ($taxonomy_details->hierarchical == 1) {
            $edit_post_terms_id = array_column($edit_post_terms, 'term_id');
        } else {
            $edit_post_terms_id = array_column($edit_post_terms, 'name');
        }
    } else {
        $edit_post_terms_id = array();
    }
}
$child_of = !empty($field_details['child_of']) ? $field_details['child_of'] : 0;
$terms_include = !empty($field_details['include_terms']) ? explode(',', $field_details['include_terms']) : array();
$terms_include_ids = [];
if (!empty($terms_include)) {

    foreach ($terms_include as $term_include_slug) {
        $term_include_obj = get_term_by('slug', $term_include_slug, $taxonomy);
        if (!empty($term_include_obj)) {
            $terms_include_ids[] = $term_include_obj->term_id;
        }
    }
}
$term_args = [
    'hide_empty' => 0,
    'child_of' => $child_of
];
if (!empty($terms_include_ids)) {
    $term_args['include'] = $terms_include_ids;
}
$terms = get_terms($taxonomy, $term_args);
$terms_hierarchy = array();
$fpsm_library_obj->sort_terms_hierarchicaly($terms, $terms_hierarchy, $child_of);
$terms_exclude = !empty($field_details['exclude_terms']) ? explode(',', $field_details['exclude_terms']) : array();
switch ($taxonomy_field_type) {
    case 'checkbox':
        include(FPSM_PATH . '/includes/views/frontend/form-fields/taxonomy-fields/taxonomy-checkbox.php');
        break;
    case 'select':
        include(FPSM_PATH . '/includes/views/frontend/form-fields/taxonomy-fields/taxonomy-select.php');
        break;
    case 'textfield':
        include(FPSM_PATH . '/includes/views/frontend/form-fields/taxonomy-fields/taxonomy-textfield.php');
        break;
}
