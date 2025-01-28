# NexusLearn LMS 
## Description
NexusLearn LMS is a comprehensive Learning Management System plugin for WordPress that enables course creation, quiz management, and student progress tracking. With features like content dripping, certificates, and detailed analytics, it provides everything needed to create and manage an online learning platform.

## Features

### Course Management
- Custom post type for courses
- Course categories and tags
- Lesson organization with drag-and-drop reordering
- Course prerequisites
- Content dripping capabilities
- Multiple content types (video, audio, text, PDF)

### Quiz System
- Multiple question types:
  - Multiple choice
  - True/False
  - Essay
  - Matching
  - Fill in the blanks
- Quiz timers
- Question randomization
- Score calculation
- Quiz results analytics
- Certificate generation

### Progress Tracking
- Course completion status
- Quiz scores and attempts
- Time spent on lessons
- Progress visualization
- Student analytics dashboard

## Installation

1. Upload the 'nexuslearn' folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Configure plugin settings via NexusLearn > Settings in admin dashboard

## Requirements

- WordPress 5.8+
- PHP 7.4+
- MySQL 5.7+

## Configuration

### General Settings
- Platform name and description
- User registration options
- Currency settings
- Date format and timezone

### Email Settings
- Notification templates
- Sender information
- Email triggers

### Course Settings
- Display options
- Content types
- Student capacity
- Instructor capabilities

### Quiz Settings
- Default passing scores
- Time limits
- Question types
- Result display options

### Certificate Settings
- Certificate templates
- Design customization
- Font settings
- Border styles

## Usage

### Shortcodes
```
[nexuslearn_course id="course_id"]
[nexuslearn_lesson id="lesson_id"]
[nexuslearn_quiz id="quiz_id"]
[nexuslearn_progress user_id="user_id"]
```

### Actions and Filters

#### Actions
```php
do_action('nexuslearn_course_created', $course_id);
do_action('nexuslearn_course_updated', $course_id);
do_action('nexuslearn_course_deleted', $course_id);
do_action('nexuslearn_quiz_submitted', $quiz_id, $user_id, $score);
do_action('nexuslearn_quiz_completed', $quiz_id, $user_id);
do_action('nexuslearn_lesson_completed', $lesson_id, $user_id);
do_action('nexuslearn_course_completed', $course_id, $user_id);
```

#### Filters
```php
apply_filters('nexuslearn_course_data', $course_data, $course_id);
apply_filters('nexuslearn_course_access', $has_access, $course_id, $user_id);
apply_filters('nexuslearn_quiz_score', $score, $quiz_id, $user_id);
apply_filters('nexuslearn_quiz_passing_score', $passing_score, $quiz_id);
apply_filters('nexuslearn_progress_status', $status, $user_id, $course_id);
```

## Security Features
- Data sanitization and validation
- Nonce verification
- Capability checks
- XSS protection
- CSRF protection
- SQL injection prevention

## Future Enhancements
- Integration with payment gateways
- Advanced reporting system
- Gamification features
- Social learning capabilities
- Mobile app integration

## Support
For support queries, feature requests, or bug reports, please visit our support forum or create an issue on GitHub.


## License
This plugin is licensed under the GPL v2 or later.

## Changelog

### 1.0.0
- Initial release
- Course management system
- Quiz functionality
- Progress tracking
- Certificate generation
- Settings panel
- Shortcode support

## FAQ

### Can I use this plugin with any WordPress theme?
Yes, NexusLearn LMS is designed to work with any properly coded WordPress theme.

### Does it support multiple instructors?
Yes, you can have multiple instructors with different levels of access and capabilities.

### Can I sell courses with this plugin?
The basic version doesn't include payment integration, but it's on our roadmap for future releases.

### Is it GDPR compliant?
Yes, the plugin follows WordPress privacy guidelines and includes tools for GDPR compliance.

### Can I import courses from other LMS platforms?
Currently, this feature is not available but is planned for future releases.