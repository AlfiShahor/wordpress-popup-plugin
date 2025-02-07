<?php
/**
 * Plugin Name: Custom Popup
 * Description: A customizable pop-up plugin with image resizing, dark mode, and settings confirmation.
 * Version: 1.3
 * Author: MD ALFI SHAHOR
 */

require plugin_dir_path(__FILE__) . 'plugin-update-checker/plugin-update-checker.php';
use YahnisElsts\PluginUpdateChecker\v5\PucFactory;

$updateChecker = PucFactory::buildUpdateChecker(
    'https://github.com/AlfiShahor/wordpress-popup-plugin', 'https://github.com/AlfiShahor/wordpress-popup-plugin', // Correct GitHub repository link
// Your correct GitHub repository link
    __FILE__,
    'popup-plugin'
);

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Enqueue scripts and styles
function popup_plugin_enqueue_scripts() {
    wp_enqueue_style('popup-plugin-style', plugin_dir_url(__FILE__) . 'popup.css');
    wp_enqueue_script('popup-plugin-script', plugin_dir_url(__FILE__) . 'popup.js', array('jquery'), null, true);
}
add_action('wp_enqueue_scripts', 'popup_plugin_enqueue_scripts');

// Create settings menu
function popup_plugin_menu() {
    add_menu_page('Popup Settings', 'Popup Settings', 'manage_options', 'popup-settings', 'popup_plugin_settings_page');
}
add_action('admin_menu', 'popup_plugin_menu');

// Settings page content
function popup_plugin_settings_page() {
    ?>
    <div class="wrap">
        <h1>Popup Settings</h1>
        <?php if (isset($_GET['settings-updated'])): ?>
            <div id="setting-error-settings_updated" class="updated notice is-dismissible">
                <p><strong>Settings saved.</strong></p>
            </div>
        <?php endif; ?>
        <form method="post" action="options.php">
            <?php
            settings_fields('popup-settings-group');
            do_settings_sections('popup-settings-group');
            ?>
            <table class="form-table">
                <tr>
                    <th>Popup Image URL:</th>
                    <td><input type="text" name="popup_image" value="<?php echo esc_attr(get_option('popup_image')); ?>" style="width: 400px;"></td>
                </tr>
                <tr>
                    <th>Popup Text:</th>
                    <td><textarea name="popup_text" rows="4" style="width: 400px;"><?php echo esc_attr(get_option('popup_text')); ?></textarea></td>
                </tr>
                <tr>
                    <th>Button Link:</th>
                    <td><input type="text" name="popup_button_link" value="<?php echo esc_attr(get_option('popup_button_link')); ?>" style="width: 400px;"></td>
                </tr>
                <tr>
                    <th>Enable Dark Mode:</th>
                    <td><input type="checkbox" name="popup_dark_mode" value="1" <?php checked(1, get_option('popup_dark_mode'), true); ?>></td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

// Register settings
function popup_plugin_register_settings() {
    register_setting('popup-settings-group', 'popup_image');
    register_setting('popup-settings-group', 'popup_text');
    register_setting('popup-settings-group', 'popup_button_link');
    register_setting('popup-settings-group', 'popup_dark_mode');
}
add_action('admin_init', 'popup_plugin_register_settings');

// Resize image before saving
function popup_plugin_resize_image($image_url) {
    if (!$image_url) return '';
    $resized_url = $image_url . '?resize=400x400'; // Example resize
    return $resized_url;
}

// Display popup on the frontend
function popup_plugin_display_popup() {
    $image = popup_plugin_resize_image(get_option('popup_image'));
    $text = get_option('popup_text');
    $button_link = get_option('popup_button_link');
    $dark_mode = get_option('popup_dark_mode') ? 'popup-dark' : '';
    ?>
    <div id="custom-popup" class="popup-container <?php echo esc_attr($dark_mode); ?>">
        <div class="popup-content">
            <span class="popup-close">&times;</span>
            <img src="<?php echo esc_url($image); ?>" alt="Popup Image">
            <p><?php echo esc_html($text); ?></p>
            <a href="<?php echo esc_url($button_link); ?>" class="popup-button" target="_blank">Go Link</a>
        </div>
    </div>
    <?php
}
add_action('wp_footer', 'popup_plugin_display_popup');
?>
