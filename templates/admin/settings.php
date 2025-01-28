<?php
// Ensure this isn't accessed directly
if (!defined('ABSPATH')) {
    exit;
}
?>
<div class="wrap">
    <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
    <?php settings_errors(); ?>

    <nav class="nav-tab-wrapper">
        <a href="?page=nexuslearn-settings&tab=general" class="nav-tab <?php echo empty($_GET['tab']) || $_GET['tab'] === 'general' ? 'nav-tab-active' : ''; ?>">
            <?php _e('General', 'nexuslearn'); ?>
        </a>
    </nav>

    <form action="options.php" method="post">
        <?php
        settings_fields('nexuslearn_options');
        do_settings_sections('nexuslearn-settings');
        submit_button('Save Settings');
        ?>
    </form>
</div>

<style>
.form-table th {
    padding: 20px 10px;
}
.form-table td {
    padding: 15px 10px;
}
.description {
    font-style: italic;
    color: #666;
}
</style>