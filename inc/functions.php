<?php

function pre_dump($test){
    echo '<pre>';
    print_r($test);
    echo '</pre>';
}





function convertToFormat($input, $type) {
    switch ($type) {
        case 'label':
            // Capitalize the first letter and remove '-' and '_'
            $transformed = str_replace(['-', '_'], ' ', $input);
            $transformed = ucwords($transformed);
            return   $transformed ;
        case 'id':
                        // Remove spaces, add '_' and make the string lowercase
                        $transformed = str_replace(' ', '-', $input);
                        return strtolower($transformed);
        case 'name':
            // Remove spaces, add '_' and make the string lowercase
            $transformed = str_replace(' ', '_', $input);
            return strtolower($transformed);
        
        default:
            throw new InvalidArgumentException("Unsupported type provided. Use 'label', 'id', or 'name'.");
    }
}


add_action('admin_enqueue_scripts', 'enqueue_mmp_scripts');
function enqueue_mmp_scripts($hook_suffix) {
    // Enqueue necessary scripts and styles
    wp_enqueue_script('wp-mediaelement');
    wp_enqueue_script('media-views');
    wp_enqueue_media();
    wp_enqueue_script('wp-color-picker');
    wp_enqueue_style('wp-color-picker');
    wp_enqueue_script('jquery');

    // Enqueue your custom script
    wp_enqueue_script('mmp-custom-script', plugin_dir_url(__FILE__) . '/lib/wp-upload-colorpicker.js', array('jquery', 'wp-mediaelement', 'media-views', 'wp-color-picker'), null, true);
}



 
// Hook into WordPress initialization to register the dynamic shortcodes
add_action('init', 'register_dynamic_shortcodes_admin_settings');

function register_dynamic_shortcodes_admin_settings() {
    // Retrieve all the saved option names
    $options = get_option('admin_settings', []);
    
    // Loop through each option and register a shortcode
    foreach ($options as $key => $value) {
        $option_key = str_replace(' ', '_', strtolower($key)); // Replace spaces with underscores

        add_shortcode($option_key, function() use ($option_key) {
            // Retrieve the saved value for this option
            $option_values = get_option('admin_settings', []);
            return isset($option_values[$option_key]) ? ($option_values[$option_key]) : '';
        });
    }
}
register_dynamic_shortcodes_admin_settings();








/**
 * Deletes a specific instance from a serialized option data.
 *
 * @param string $option_name The name of the option storing serialized data.
 * @param string $instance_name The name of the instance to be deleted.
 * @return string The result message indicating the success or failure of the operation.
 */
function delete_serialized_option_instance($option_name, $instance_name) {
    // Retrieve the existing data
    $options = get_option($option_name, []);

    if ($options) {
        // Loop through the data and remove the specific entry
        foreach ($options as $key => $value) {
            if (isset($value['name']) && $value['name'] === $instance_name) {
                unset($options[$key]);
                break;
            }
        }

        // Reindex the array to maintain sequential keys
        $options = array_values($options);

        // Update the option with the modified data
        update_option($option_name, $options);

        return 'Option updated successfully.';
    } else {
        return 'Option not found.';
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_option_button'])) {
    // Verify nonce for security
    // if (isset($_POST['delete_option_nonce']) && wp_verify_nonce($_POST['delete_option_nonce'], 'delete_option')) {
        $option_name = sanitize_text_field($_POST['option_name']);
        $instance_name = sanitize_text_field($_POST['instance_name']);

        // Call the function to delete the instance
        $message = delete_serialized_option_instance($option_name, $instance_name);
        
        // Display the result message
        echo '<div class="updated"><p>' . esc_html($message) . '</p></div>';
    // } else {
    //     echo '<div class="error"><p>Security check failed.</p></div>';
    // }
}



/**
 * Displays the delete option form in HTML for a given option and instance.
 *
 * @param string $option_name The name of the option.
 * @param string $instance_name The name of the instance.
 */
function display_delete_option_form($option_name, $instance_name) {
    ob_start();
    ?>
    <form method="post" action="" style="display:inline;">
        <?php wp_nonce_field('delete_option', 'delete_option_nonce'); ?>
        <input type="hidden" name="option_name" value="<?php echo ($option_name); ?>" />
        <input type="hidden" name="instance_name" value="<?php echo ($instance_name); ?>" />
        <button type="submit" class="button" name="delete_option_button"
            onclick="return confirm('Are you sure you want to delete this option?');">Delete</button>
    </form>
    <?php
    $form = ob_get_clean();
    return $form;
}


function reset_options_form($options_field,$button,$button_lable){
    ob_start();?>
            <form method="post" action="" style="display:inline;" id="reset_form">
            <?php 

                if (isset($_POST[$button])) {
                    // Get the existing options from the database
                    $options = get_option($options_field, []); 
                
                    // Update the options in the database
                    delete_option($options_field, $options); 
                
                    ?><div class="updated"><p>Fields Reset</p></div><?php 
                }                             
            ?>
            <?php submit_button($button_lable, 'primary', $button); ?>
        </form>
    <?php
    $html = ob_get_clean();
    return $html;
}

function field_types(){
    $types = [
        'text' => 'Text',
        'email' => 'Email',
        'number' => 'Number',
        'textarea' => 'Textarea',
        'wysiwyg' => 'Wysiwyg',
        'radio' => 'Radio',
        'checkbox' => 'Checkbox',
        'checkbox_multiselect' => 'Checkbox Multiselect',
        'select' => 'Select',
        'colorpicker' => 'Colorpicker',
        'upload' => 'Upload',
        'date' => 'Date',
        'dynamic_select' => 'Dynamic Select'
    ];
    return $types;
}