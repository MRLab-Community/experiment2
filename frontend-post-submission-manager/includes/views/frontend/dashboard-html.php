<?php
defined('ABSPATH') or die('No script kiddies please!!');
$current_user_id = get_current_user_id();
global $fpsm_library_obj;
$post_statuses = $fpsm_library_obj->get_post_statuses();
if (!empty($current_user_id)) {
?>
    <div class="fpsm-dashboard-wrap">
        <?php
        if (!empty($form_details['dashboard']['post_status_filter']) || !empty($form_details['dashboard']['post_search'])) {
        ?>
            <form class="fpsm-posts-filter-head">
                <?php
                if (!empty($form_details['dashboard']['post_status_filter'])) {
                ?>
                    <select name="post_status" class="fpsm-dashboard-post-status-filter">
                        <option value=""><?php esc_html_e('All', 'frontend-post-submission-manager'); ?></option>
                        <?php
                        $selected_post_status = (!empty($_GET['post_status'])) ? $_GET['post_status'] : '';
                        foreach ($post_statuses as $post_status_key => $post_status_label) {
                        ?>
                            <option value="<?php echo esc_attr($post_status_key); ?>" <?php selected($selected_post_status, $post_status_key); ?>><?php echo esc_html($post_status_label); ?></option>
                        <?php
                        }
                        ?>
                    </select>
                <?php
                }
                if (!empty($form_details['dashboard']['post_search'])) {
                ?>
                    <input type="search" name="keyword" value="<?php echo (!empty($_GET['keyword'])) ? esc_attr($_GET['keyword']) : ''; ?>" placeholder="<?php echo (!empty($form_details['dashboard']['search_field_placeholder'])) ? esc_attr($form_details['dashboard']['search_field_placeholder']) : ''; ?>" />
                    <input type="submit" value="<?php echo (!empty($form_details['dashboard']['search_submit_label'])) ? esc_attr($form_details['dashboard']['search_submit_label']) : __('Filter', 'frontend-post-submission-manager'); ?>" />
                <?php
                }
                ?>
            </form>
        <?php }
        ?>
        <div class="fpsm-dashboard-header">
            <div class="fpsm-dashboard-row">
                <?php if (!empty($form_details['dashboard']['sn'])) { ?>
                    <div class="fpsm-dashboard-column fpsm-dashboard-sn"><?php echo (!empty($form_details['dashboard']['sn_label'])) ? esc_html($form_details['dashboard']['sn_label']) : esc_html__('SN', 'frontend-post-submission-manager'); ?></div>
                <?php } ?>
                <div class="fpsm-dashboard-column fpsm-dashboard-post-title"><?php echo (!empty($form_details['dashboard']['post_title_label'])) ? esc_html($form_details['dashboard']['post_title_label']) : esc_html__('Post Title', 'frontend-post-submission-manager'); ?></div>
                <?php if (!empty($form_details['dashboard']['post_image'])) { ?>
                    <div class="fpsm-dashboard-column fpsm-dashboard-post_image"><?php echo (!empty($form_details['dashboard']['post_image_label'])) ? esc_html($form_details['dashboard']['post_image_label']) : esc_html__('Post Image', 'frontend-post-submission-manager'); ?></div>
                <?php } ?>
                <div class="fpsm-dashboard-column fpsm-dashboard-post-status"><?php echo (!empty($form_details['dashboard']['post_status_label'])) ? esc_html($form_details['dashboard']['post_status_label']) : esc_html__('Post Status', 'frontend-post-submission-manager'); ?></div>
                <?php if (!empty($form_details['dashboard']['last_modified'])) { ?>
                    <div class="fpsm-dashboard-column fpsm-dashboard-last-modified"><?php echo (!empty($form_details['dashboard']['last_modified_label'])) ? esc_html($form_details['dashboard']['last_modified_label']) : esc_html__('Last Modified', 'frontend-post-submission-manager'); ?></div>
                <?php } ?>
                <?php if (function_exists('pvc_get_post_views') && !empty($form_details['dashboard']['post_views'])) { ?>
                    <div class="fpsm-dashboard-column fpsm-dashboard-post-views"><?php echo (!empty($form_details['dashboard']['post_views_label'])) ? esc_html($form_details['dashboard']['post_views_label']) : esc_html__('Post Views', 'frontend-post-submission-manager'); ?></div>
                <?php } ?>
                <div class="fpsm-dashboard-column fpsm-dashboard-action"><?php echo (!empty($form_details['dashboard']['action_label'])) ? esc_html($form_details['dashboard']['action_label']) : esc_html__('Action', 'frontend-post-submission-manager'); ?></div>
            </div>
        </div>
        <div class="fpsm-dashboard-body">
            <?php
            $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
            $posts_per_page = (!empty($form_details['dashboard']['posts_per_page'])) ? $form_details['dashboard']['posts_per_page'] : 20;
            $post_status_keys = array_keys($post_statuses);
            $post_status_keys[] = 'future';
            $dashboard_posts_args = array(
                'post_type' => $form_row->post_type,
                'posts_per_page' => $posts_per_page,
                'orderby' => 'date',
                'order' => 'desc',
                'author' => $current_user_id,
                'post_status' => $post_status_keys,
                'meta_key' => '_fpsm_form_alias',
                'meta_value' => $alias,
                'paged' => $paged
            );

            if (!empty($_GET['post_status'])) {
                $post_status = sanitize_text_field($_GET['post_status']);
                $dashboard_posts_args['post_status'] = $post_status;
            }
            if (!empty($_GET['keyword'])) {
                $dashboard_posts_args['s'] = sanitize_text_field($_GET['keyword']);
            }
            $user_meta = get_userdata($current_user_id);
            $user_roles = $user_meta->roles;
            if (!empty($form_details['dashboard']['list_all_administrator']) && in_array('administrator', $user_roles)) {
                unset($dashboard_posts_args['author']);
            }

            if (!empty($form_details['dashboard']['list_all_from_author'])) {
                unset($dashboard_posts_args['meta_key']);
                unset($dashboard_posts_args['meta_value']);
            }

            /**
             * Filters the query args for fetching dashboard posts
             *
             * @param array $dashboard_posts_args
             * @param mixed $form_row
             *
             * @since 1.1.1
             */
            $dashboard_posts_args = apply_filters('fpsm_dashboard_args', $dashboard_posts_args, $form_row);
            $dashboard_posts_query = new WP_Query($dashboard_posts_args);

            if ($dashboard_posts_query->have_posts()) {
                $sn = 1;
                while ($dashboard_posts_query->have_posts()) {
                    $dashboard_posts_query->the_post();
            ?>
                    <div class="fpsm-dashboard-row">
                        <?php if (!empty($form_details['dashboard']['sn'])) { ?>
                            <div class="fpsm-dashboard-column fpsm-dashboard-sn"><?php echo esc_html($sn++); ?></div>
                        <?php } ?>
                        <div class="fpsm-dashboard-column fpsm-dashboard-post-title">
                            <?php
                            if (!empty($form_details['dashboard']['list_all_administrator']) && in_array('administrator', $user_roles)) {
                            ?>
                                <a href="<?php echo admin_url('post.php?post=' . get_the_ID() . '&action=edit') ?>" target="_blank"><?php the_title(); ?></a>
                            <?php
                            } else {
                                the_title();
                            }
                            ?>

                        </div>
                        <?php if (!empty($form_details['dashboard']['post_image'])) {
                        ?>
                            <div class="fpsm-dashboard-column fpsm-dashboard-post_image">
                                <?php
                                if (has_post_thumbnail()) {
                                    $post_image_size = apply_filters('fpsm_dashboard_post_image_size', 'thumbnail', $form_row);
                                ?>
                                    <img src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'thumbnail'); ?>" loading="lazy" />
                                <?php } ?>
                            </div>
                        <?php } ?>
                        <div class="fpsm-dashboard-column fpsm-dashboard-post-status">
                            <span class="fpsm-status-<?php echo esc_attr(get_post_status()); ?> fpsm-post-status">
                                <?php echo esc_html($post_statuses[get_post_status()]); ?>
                            </span>
                            <?php if (get_post_status() == 'future') {
                            ?>
                                <span class="fpsm-scheduled-date"><?php the_date('d-m-Y g:i a'); ?></span>
                            <?php
                            }
                            ?>
                        </div>
                        <?php
                        if (!empty($form_details['dashboard']['last_modified'])) {
                            $last_modified_date = get_the_modified_date('d-m-Y g:i a');
                            /**
                             * Filters last modified date
                             *
                             * @param type string $last_modified_date
                             *
                             * @since 1.2.8
                             */
                            $last_modified_date = apply_filters('fpsm_last_modified_date', $last_modified_date);
                        ?>
                            <div class="fpsm-dashboard-column fpsm-dashboard-last-modified"><?php echo esc_html($last_modified_date); ?></div>
                        <?php } ?>
                        <?php if (function_exists('pvc_get_post_views') && !empty($form_details['dashboard']['post_views'])) { ?>
                            <div class="fpsm-dashboard-column fpsm-dashboard-post-views"><?php echo esc_html(pvc_get_post_views(get_the_ID())); ?></div>
                        <?php } ?>
                        <div class="fpsm-dashboard-column fpsm-dashboard-action">
                            <?php
                            $current_page_url = $fpsm_library_obj->get_current_page_url();
                            $post_id = get_the_ID();
                            $post_edit_url = $fpsm_library_obj->get_post_edit_url($post_id);
                            $post_edit_flag = true;
                            $post_status = get_post_status();
                            if (empty($form_details['dashboard']['disable_post_edit_status'])) {
                                if (!empty($form_details['dashboard']['disable_post_edit'])) {
                                    $disabled_post_edit_status = array('publish');
                                } else {
                                    $disabled_post_edit_status = array();
                                }
                            } else {
                                $disabled_post_edit_status = $form_details['dashboard']['disable_post_edit_status'];
                            }
                            $post_edit_flag = (in_array($post_status, $disabled_post_edit_status)) ? false : true;
                            if (empty($form_details['dashboard']['disable_post_delete_status'])) {
                                if (!empty($form_details['dashboard']['disable_post_delete'])) {
                                    $disabled_post_delete_status = array('publish');
                                } else {
                                    $disabled_post_delete_status = array();
                                }
                            } else {
                                $disabled_post_delete_status = $form_details['dashboard']['disable_post_delete_status'];
                            }
                            $post_delete_flag = (in_array($post_status, $disabled_post_delete_status)) ? false : true;
                            if (!empty($form_details['dashboard']['list_all_administrator']) && in_array('administrator', $user_roles)) {
                                $post_edit_flag = true;
                                $post_delete_flag = true;
                            }
                            if ($post_edit_flag) {
                            ?>
                                <a href="<?php echo esc_url($post_edit_url); ?>" title="<?php esc_html_e('Edit', 'frontend-post-submission-manager'); ?>" class="fpsm-edit-post"><i class="fas fa-pencil-alt"></i></a>
                            <?php
                            }
                            if ($post_delete_flag) {
                                $post_delete_warning_message = (!empty($form_details['dashboard']['post_delete_warning_message'])) ? $form_details['dashboard']['post_delete_warning_message'] : esc_html__('Are you sure you want to delete this post?', 'frontend-post-submission-manager');
                                $delete_key = md5(get_the_date('d-m-y H:i a'));
                            ?>
                                <a href="javascript:void(0);" title="<?php esc_html_e('Delete', 'frontend-post-submission-manager'); ?>" class="fpsm-delete-post" data-warning-message="<?php echo esc_attr($post_delete_warning_message); ?>" data-delete-key="<?php echo esc_attr($delete_key); ?>" data-post-id="<?php the_ID(); ?>"><i class="far fa-trash-alt"></i></a>
                            <?php } ?>
                            <a href="<?php the_permalink(); ?>" title="<?php esc_html_e('View', 'frontend-post-submission-manager'); ?>" class="fpsm-view-post"><i class="far fa-eye"></i></a>
                        </div>
                    </div>
                <?php
                }
            } else {
                if (!empty($form_details['dashboard']['post_not_found_message'])) {
                ?>
                    <div class="fpsm-post-not-found"><?php echo $fpsm_library_obj->output_converting_br($form_details['dashboard']['post_not_found_message']); ?></div>
            <?php
                }
            }
            ?>
        </div>
        <?php if ($dashboard_posts_query->max_num_pages > 1) { ?>
            <div class="fpsm-pagination-wrap">
                <?php
                $big = 999999999; // need an unlikely integer
                $translated = __('Page', 'frontend-post-submission-manager'); // Supply translatable string
                $page_num_link = explode('?', esc_url(get_pagenum_link($big)));
                if (!empty($_GET)) {
                    $page_num_link = $page_num_link[0] . '?' . http_build_query($_GET);
                } else {
                    $page_num_link = $page_num_link[0];
                }
                $previous_text = (!empty($form_details['dashboard']['previous_page_label'])) ? esc_html($form_details['dashboard']['previous_page_label']) : esc_html__('Previous', 'frontend-post-submission-manager');
                $next_text = (!empty($form_details['dashboard']['next_page_label'])) ? esc_html($form_details['dashboard']['next_page_label']) : esc_html__('Next', 'frontend-post-submission-manager');
                $pagination_args = array(
                    'base' => str_replace($big, '%#%', $page_num_link),
                    'format' => '?%#%',
                    'current' => max(1, get_query_var('paged')),
                    'total' => $dashboard_posts_query->max_num_pages,
                    'before_page_number' => '<span class="screen-reader-text">' . $translated . ' </span>',
                    'prev_text' => $previous_text,
                    'next_text' => $next_text,
                );
                /**
                 * Filters the args being sent on pagination for frontend dashboard posts
                 * 
                 * @args array $pagination_args
                 * @args array $form_row
                 * 
                 * @since 1.4.1
                 */
                $pagination_args = apply_filters('fpsm_pagination_args', $pagination_args, $form_row);
                echo paginate_links($pagination_args);
                ?>
            </div>
        <?php
        }
        wp_reset_query();
        wp_reset_postdata();
        ?>
    </div>
<?php } ?>