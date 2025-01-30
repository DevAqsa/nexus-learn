<?php
namespace NexusLearn\Admin\Views;

class CourseTemplate {
    public function render() {
        $post_type = 'nl_course';
        
        if (!current_user_can('edit_posts')) {
            wp_die(__('You do not have sufficient permissions to access this page.'));
        }
        
        ?>
        <div class="wrap nl-course-template">
            <h1><?php _e('Add New Course', 'nexuslearn'); ?></h1>
            
            <div class="nl-course-container">
                <form id="nl-course-form" method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
                    <input type="hidden" name="action" value="nl_add_course">
                    <?php wp_nonce_field('nl_add_course', 'nl_course_nonce'); ?>
                    
                    <!-- Navigation Tabs -->
                    <div class="nl-tabs">
                        <button type="button" class="nl-tab-button active" data-tab="basic">
                            <span class="dashicons dashicons-welcome-write-blog"></span>
                            <?php _e('Basic Info', 'nexuslearn'); ?>
                        </button>
                        <button type="button" class="nl-tab-button" data-tab="details">
                            <span class="dashicons dashicons-list-view"></span>
                            <?php _e('Course Details', 'nexuslearn'); ?>
                        </button>
                        <button type="button" class="nl-tab-button" data-tab="curriculum">
                            <span class="dashicons dashicons-book"></span>
                            <?php _e('Curriculum', 'nexuslearn'); ?>
                        </button>
                        <button type="button" class="nl-tab-button" data-tab="pricing">
                            <span class="dashicons dashicons-money-alt"></span>
                            <?php _e('Pricing', 'nexuslearn'); ?>
                        </button>
                    </div>

                    <!-- Basic Info Tab -->
                    <div class="nl-tab-content active" data-tab="basic">
                        <div class="nl-form-section">
                            <label for="course_title" class="nl-label"><?php _e('Course Title', 'nexuslearn'); ?> *</label>
                            <input type="text" id="course_title" name="course_title" class="nl-input large-text" required 
                                   placeholder="<?php _e('Enter an engaging title for your course', 'nexuslearn'); ?>">
                        </div>

                        <div class="nl-form-section">
                            <label for="course_subtitle" class="nl-label"><?php _e('Course Subtitle', 'nexuslearn'); ?></label>
                            <input type="text" id="course_subtitle" name="course_subtitle" class="nl-input large-text"
                                   placeholder="<?php _e('A brief description that appears below the title', 'nexuslearn'); ?>">
                        </div>

                        <div class="nl-form-section">
                            <label for="course_description" class="nl-label"><?php _e('Course Description', 'nexuslearn'); ?> *</label>
                            <?php 
                            wp_editor('', 'course_description', [
                                'media_buttons' => true,
                                'textarea_rows' => 10,
                                'teeny' => false,
                                'quicktags' => true
                            ]); 
                            ?>
                        </div>

                        <div class="nl-form-section">
                            <label class="nl-label"><?php _e('Featured Image', 'nexuslearn'); ?></label>
                            <div class="nl-media-upload">
                                <input type="hidden" id="course_featured_image" name="course_featured_image">
                                <div class="nl-image-preview-container">
                                    <div class="nl-image-preview"></div>
                                    <button type="button" class="button nl-upload-button">
                                        <span class="dashicons dashicons-upload"></span>
                                        <?php _e('Upload Course Image', 'nexuslearn'); ?>
                                    </button>
                                </div>
                                <p class="description"><?php _e('Recommended size: 1200x800 pixels', 'nexuslearn'); ?></p>
                            </div>
                        </div>
                    </div>

                    <!-- Course Details Tab -->
                    <div class="nl-tab-content" data-tab="details">
                        <div class="nl-grid-2">
                            <div class="nl-form-section">
                                <label for="course_level" class="nl-label"><?php _e('Difficulty Level', 'nexuslearn'); ?></label>
                                <select id="course_level" name="course_level" class="nl-input">
                                    <option value="beginner"><?php _e('Beginner', 'nexuslearn'); ?></option>
                                    <option value="intermediate"><?php _e('Intermediate', 'nexuslearn'); ?></option>
                                    <option value="advanced"><?php _e('Advanced', 'nexuslearn'); ?></option>
                                    <option value="all-levels"><?php _e('All Levels', 'nexuslearn'); ?></option>
                                </select>
                            </div>

                            <div class="nl-form-section">
                                <label for="course_duration" class="nl-label"><?php _e('Estimated Duration', 'nexuslearn'); ?></label>
                                <div class="nl-duration-inputs">
                                    <input type="number" id="course_duration_hours" name="course_duration_hours" 
                                           class="nl-input" min="0" placeholder="Hours">
                                    <input type="number" id="course_duration_minutes" name="course_duration_minutes" 
                                           class="nl-input" min="0" max="59" placeholder="Minutes">
                                </div>
                            </div>
                        </div>

                        <div class="nl-grid-2">
                            <div class="nl-form-section">
                                <label for="course_language" class="nl-label"><?php _e('Course Language', 'nexuslearn'); ?></label>
                                <select id="course_language" name="course_language" class="nl-input">
                                    <option value="english">English</option>
                                    <option value="spanish">Spanish</option>
                                    <option value="french">French</option>
                                    <option value="german">German</option>
                                    <option value="chinese">Chinese</option>
                                </select>
                            </div>

                            <div class="nl-form-section">
                                <label for="course_capacity" class="nl-label"><?php _e('Student Capacity', 'nexuslearn'); ?></label>
                                <input type="number" id="course_capacity" name="course_capacity" 
                                       class="nl-input" min="0" placeholder="Leave empty for unlimited">
                            </div>
                        </div>

                        <div class="nl-form-section">
                            <label for="course_prerequisites" class="nl-label"><?php _e('Prerequisites', 'nexuslearn'); ?></label>
                            <textarea id="course_prerequisites" name="course_prerequisites" class="nl-input large-text" 
                                    rows="3" placeholder="<?php _e('What should students know before starting?', 'nexuslearn'); ?>"></textarea>
                        </div>

                        <div class="nl-form-section">
                            <label for="course_outcomes" class="nl-label"><?php _e('Learning Outcomes', 'nexuslearn'); ?></label>
                            <div id="learning-outcomes-container">
                                <div class="nl-outcome-item">
                                    <input type="text" name="course_outcomes[]" class="nl-input" 
                                           placeholder="<?php _e('Enter a learning outcome', 'nexuslearn'); ?>">
                                    <button type="button" class="nl-remove-outcome">×</button>
                                </div>
                            </div>
                            <button type="button" class="button nl-add-outcome">
                                <span class="dashicons dashicons-plus-alt2"></span>
                                <?php _e('Add Learning Outcome', 'nexuslearn'); ?>
                            </button>
                        </div>

                        <div class="nl-form-section">
                            <label class="nl-label"><?php _e('Categories & Tags', 'nexuslearn'); ?></label>
                            <div class="nl-grid-2">
                                <div class="nl-taxonomy-box">
                                    <h4><?php _e('Course Categories', 'nexuslearn'); ?></h4>
                                    <div class="nl-checkbox-group">
                                        <?php
                                        $categories = get_terms([
                                            'taxonomy' => 'course_category',
                                            'hide_empty' => false,
                                        ]);
                                        
                                        foreach ($categories as $category) {
                                            printf(
                                                '<label><input type="checkbox" name="course_categories[]" value="%s"> %s</label>',
                                                esc_attr($category->term_id),
                                                esc_html($category->name)
                                            );
                                        }
                                        ?>
                                    </div>
                                </div>

                                <div class="nl-taxonomy-box">
                                    <h4><?php _e('Course Tags', 'nexuslearn'); ?></h4>
                                    <input type="text" id="course_tags" name="course_tags" class="nl-input" 
                                           placeholder="<?php _e('Enter tags separated by commas', 'nexuslearn'); ?>">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Pricing Tab -->
                    <div class="nl-tab-content" data-tab="pricing">
                        <div class="nl-grid-2">
                            <div class="nl-form-section">
                                <label for="course_pricing_type" class="nl-label"><?php _e('Pricing Type', 'nexuslearn'); ?></label>
                                <select id="course_pricing_type" name="course_pricing_type" class="nl-input">
                                    <option value="free"><?php _e('Free', 'nexuslearn'); ?></option>
                                    <option value="one-time"><?php _e('One-time Payment', 'nexuslearn'); ?></option>
                                    <option value="subscription"><?php _e('Subscription', 'nexuslearn'); ?></option>
                                </select>
                            </div>

                            <div class="nl-form-section price-fields" style="display: none;">
                                <label for="course_price" class="nl-label"><?php _e('Price', 'nexuslearn'); ?></label>
                                <div class="nl-price-input">
                                    <span class="nl-currency">$</span>
                                    <input type="number" id="course_price" name="course_price" 
                                           class="nl-input" min="0" step="0.01" placeholder="0.00">
                                </div>
                            </div>
                        </div>

                        <div class="nl-form-section price-fields" style="display: none;">
                            <label for="course_sale_price" class="nl-label"><?php _e('Sale Price', 'nexuslearn'); ?></label>
                            <div class="nl-price-input">
                                <span class="nl-currency">$</span>
                                <input type="number" id="course_sale_price" name="course_sale_price" 
                                       class="nl-input" min="0" step="0.01" placeholder="0.00">
                            </div>
                        </div>

                        <div class="nl-form-section subscription-fields" style="display: none;">
                            <label for="course_subscription_interval" class="nl-label">
                                <?php _e('Subscription Interval', 'nexuslearn'); ?>
                            </label>
                            <select id="course_subscription_interval" name="course_subscription_interval" class="nl-input">
                                <option value="monthly"><?php _e('Monthly', 'nexuslearn'); ?></option>
                                <option value="quarterly"><?php _e('Quarterly', 'nexuslearn'); ?></option>
                                <option value="yearly"><?php _e('Yearly', 'nexuslearn'); ?></option>
                            </select>
                        </div>
                    </div>

                    <!-- Curriculum Tab -->
                    <div class="nl-tab-content" data-tab="curriculum">
                        <div class="nl-curriculum-builder">
                            <div id="nl-sections-container">
                                <div class="nl-section">
                                    <div class="nl-section-header">
                                        <input type="text" name="sections[0][title]" class="nl-input" 
                                               placeholder="<?php _e('Section Title', 'nexuslearn'); ?>">
                                        <button type="button" class="nl-remove-section">×</button>
                                    </div>
                                    <div class="nl-lessons-container">
                                        <div class="nl-lesson">
                                            <input type="text" name="sections[0][lessons][]" class="nl-input" 
                                                   placeholder="<?php _e('Lesson Title', 'nexuslearn'); ?>">
                                            <button type="button" class="nl-remove-lesson">×</button>
                                        </div>
                                    </div>
                                    <button type="button" class="button nl-add-lesson">
                                        <span class="dashicons dashicons-plus-alt2"></span>
                                        <?php _e('Add Lesson', 'nexuslearn'); ?>
                                    </button>
                                </div>
                            </div>
                            <button type="button" class="button nl-add-section">
                                <span class="dashicons dashicons-plus-alt2"></span>
                                <?php _e('Add Section', 'nexuslearn'); ?>
                            </button>
                        </div>
                    </div>

                    <div class="nl-form-actions">
                        <button type="submit" class="button button-primary button-hero">
                            <span class="dashicons dashicons-welcome-write-blog"></span>
                            <?php _e('Create Course', 'nexuslearn'); ?>
                        </button>
                        <a href="<?php echo admin_url('edit.php?post_type=' . $post_type); ?>" class="button button-hero">
                            <?php _e('Cancel', 'nexuslearn'); ?>
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <style>
            .nl-course-template {
                max-width: 1200px;
                margin: 20px auto;
                background: #fff;
                box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            }

            .nl-course-container {
                padding: 20px;
            }

            .nl-tabs {
                display: flex;
                border-bottom: 1px solid #ddd;
                margin-bottom: 20px;
                gap: 5px;
            }

            .nl-tab-button {
                padding: 10px 20px;
                border: none;
                background: none;
                cursor: pointer;
                border-bottom: 3px solid transparent;
                display: flex;
                align-items: center;
                gap: 5px;
                color: #50575e;
            }

            .nl-tab-button:hover {
                color: #2271b1;
            }

            .nl-tab-button.active {
                border-bottom-color: #2271b1;
                color: #2271b1;
            }

            .nl-tab-content {
                display: none;
                padding: 20px;
                background: #fff;
            }

            .nl-tab-content.active {
                display: block;
            }

            .nl-form-section {
                margin-bottom: 25px;
            }

            .nl-label {
                display: block;
                font-weight: 600;
                margin-bottom: 8px;
                color: #1d2327;
            }

            .nl-input {
                width: 100%;
                padding: 8px 12px;
                border: 1px solid #ddd;
                border-radius: 4px;
                transition: all 0.3s ease;
            }

            .nl-input:focus {
                border-color: #2271b1;
                box-shadow: 0 0 0 1px #2271b1;
                outline: none;
            }

            .nl-grid-2 {
                display: grid;
                grid-template-columns: repeat(2, 1fr);
                gap: 20px;
                margin-bottom: 20px;
            }

            .nl-media-upload {
                margin-top: 10px;
            }

            .nl-image-preview-container {
                border: 2px dashed #ddd;
                padding: 20px;
                text-align: center;
                border-radius: 4px;
                margin-bottom: 10px;
            }

            .nl-image-preview {
                margin-bottom: 15px;
            }

            .nl-image-preview img {
                max-width: 100%;
                max-height: 300px;
                height: auto;
            }

            .nl-upload-button {
                display: inline-flex !important;
                align-items: center;
                gap: 5px;
            }

            .nl-duration-inputs {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 10px;
            }

            .nl-taxonomy-box {
                background: #f6f7f7;
                padding: 15px;
                border-radius: 4px;
            }

            .nl-taxonomy-box h4 {
                margin-top: 0;
                margin-bottom: 10px;
            }

            .nl-checkbox-group {
                max-height: 200px;
                overflow-y: auto;
                padding: 10px;
                background: #fff;
                border: 1px solid #ddd;
                border-radius: 4px;
            }

            .nl-checkbox-group label {
                display: block;
                margin-bottom: 8px;
            }

            .nl-curriculum-builder {
                background: #f6f7f7;
                padding: 20px;
                border-radius: 4px;
            }

            .nl-section {
                background: #fff;
                margin-bottom: 20px;
                border: 1px solid #ddd;
                border-radius: 4px;
                padding: 15px;
            }

            .nl-section-header {
                display: flex;
                gap: 10px;
                margin-bottom: 15px;
            }

            .nl-remove-section,
            .nl-remove-lesson,
            .nl-remove-outcome {
                background: #dc3545;
                color: #fff;
                border: none;
                border-radius: 50%;
                width: 24px;
                height: 24px;
                line-height: 20px;
                text-align: center;
                cursor: pointer;
            }

            .nl-form-actions {
                margin-top: 30px;
                padding-top: 20px;
                border-top: 1px solid #ddd;
                display: flex;
                gap: 15px;
                justify-content: flex-end;
            }

            @media screen and (max-width: 782px) {
                .nl-grid-2 {
                    grid-template-columns: 1fr;
                }

                .nl-tabs {
                    flex-wrap: wrap;
                }

                .nl-tab-button {
                    flex: 1 1 auto;
                }
            }
        </style>
        <?php
    }
}