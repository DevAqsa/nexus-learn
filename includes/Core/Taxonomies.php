<?php
namespace NexusLearn\Core;

class Taxonomies {
    public function __construct() {
        add_action('init', [$this, 'register_taxonomies']);
        add_filter('manage_nl_course_posts_columns', [$this, 'add_difficulty_column']);
        add_action('manage_nl_course_posts_custom_column', [$this, 'display_difficulty_column'], 10, 2);
    }

    public function register_taxonomies() {
        // Register Categories Taxonomy
        register_taxonomy('course_category', 'nl_course', [
            'labels' => [
                'name' => __('Categories', 'nexuslearn'),
                'singular_name' => __('Category', 'nexuslearn'),
                'search_items' => __('Search Categories', 'nexuslearn'),
                'all_items' => __('All Categories', 'nexuslearn'),
                'edit_item' => __('Edit Category', 'nexuslearn'),
                'update_item' => __('Update Category', 'nexuslearn'),
                'add_new_item' => __('Add New Category', 'nexuslearn'),
                'new_item_name' => __('New Category Name', 'nexuslearn'),
                'menu_name' => __('Categories', 'nexuslearn'),
            ],
            'hierarchical' => true,
            'show_ui' => true,
            'show_admin_column' => true,
            'query_var' => true,
            'rewrite' => ['slug' => 'course-category'],
        ]);

        // Register Tags Taxonomy
        register_taxonomy('course_tag', 'nl_course', [
            'labels' => [
                'name' => __('Tags', 'nexuslearn'),
                'singular_name' => __('Tag', 'nexuslearn'),
                'search_items' => __('Search Tags', 'nexuslearn'),
                'all_items' => __('All Tags', 'nexuslearn'),
                'edit_item' => __('Edit Tag', 'nexuslearn'),
                'update_item' => __('Update Tag', 'nexuslearn'),
                'add_new_item' => __('Add New Tag', 'nexuslearn'),
                'new_item_name' => __('New Tag Name', 'nexuslearn'),
                'menu_name' => __('Tags', 'nexuslearn'),
            ],
            'hierarchical' => false,
            'show_ui' => true,
            'show_admin_column' => true,
            'query_var' => true,
            'rewrite' => ['slug' => 'course-tag'],
        ]);

        // Register Difficulty Taxonomy
        register_taxonomy('course_difficulty', 'nl_course', [
            'labels' => [
                'name' => __('Difficulties', 'nexuslearn'),
                'singular_name' => __('Difficulty', 'nexuslearn'),
                'search_items' => __('Search Difficulties', 'nexuslearn'),
                'all_items' => __('All Difficulties', 'nexuslearn'),
                'edit_item' => __('Edit Difficulty', 'nexuslearn'),
                'update_item' => __('Update Difficulty', 'nexuslearn'),
                'add_new_item' => __('Add New Difficulty', 'nexuslearn'),
                'new_item_name' => __('New Difficulty Name', 'nexuslearn'),
                'menu_name' => __('Difficulties', 'nexuslearn'),
            ],
            'hierarchical' => true,
            'show_ui' => true,
            'show_admin_column' => true,
            'show_in_quick_edit' => true,
            'query_var' => true,
            'rewrite' => ['slug' => 'difficulty'],
            'default_term' => [
                'name' => 'Intermediate',
                'slug' => 'intermediate',
            ],
            'capabilities' => [
                'manage_terms' => 'manage_categories',
                'edit_terms' => 'manage_categories',
                'delete_terms' => 'manage_categories',
                'assign_terms' => 'edit_posts'
            ],
        ]);

        // Add default difficulty terms
        $this->add_default_difficulties();
    }

    private function add_default_difficulties() {
        if (!get_option('nexuslearn_difficulties_added')) {
            $default_difficulties = [
                'beginner' => __('Beginner', 'nexuslearn'),
                'intermediate' => __('Intermediate', 'nexuslearn'),
                'advanced' => __('Advanced', 'nexuslearn'),
                'expert' => __('Expert', 'nexuslearn')
            ];

            foreach ($default_difficulties as $slug => $name) {
                if (!term_exists($name, 'course_difficulty')) {
                    wp_insert_term($name, 'course_difficulty', [
                        'slug' => $slug
                    ]);
                }
            }

            update_option('nexuslearn_difficulties_added', true);
        }
    }

    public function add_difficulty_column($columns) {
        $new_columns = [];
        foreach ($columns as $key => $value) {
            $new_columns[$key] = $value;
            if ($key === 'title') {
                $new_columns['difficulty'] = __('Difficulty', 'nexuslearn');
            }
        }
        return $new_columns;
    }

    public function display_difficulty_column($column, $post_id) {
        if ($column === 'difficulty') {
            $terms = get_the_terms($post_id, 'course_difficulty');
            if (!empty($terms) && !is_wp_error($terms)) {
                foreach ($terms as $term) {
                    printf(
                        '<span class="difficulty-badge difficulty-%s">%s</span>',
                        esc_attr($term->slug),
                        esc_html($term->name)
                    );
                }
            } else {
                echo '—';
            }
        }
    }
}