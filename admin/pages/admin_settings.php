<div class="wrap">
    <h1>Main Page</h1>
    <p>Welcome to the main page!</p>
    <!-- . -->
    <?php     $types =  field_types();    ?>
    <div class="special-form">
        <form method="post" action="" style="display:inline;" id="reset_form">
            <?php 

                if (isset($_POST['reset_admin_settings_button'])) {
                    // Get the existing options from the database
                    $admin_settings = get_option('admin_settings', []);
                    $admin_fields = get_option('admin_fields', []);
                
                    // Update the options in the database
                    delete_option('admin_settings', $admin_settings); 
                
                    // Display a success message
                    echo '<div class="updated"><p>Fields Reset</p></div>';
                }                             
            ?>
            <?php submit_button('Reset & Remove Admin Settings & Fields', 'primary', 'reset_admin_settings_button'); ?>
        </form>
    </div>
    <div class="special-form">
        <h2>Add Admin Field</h2>

        <form method="post" action="" id="save-fields">
            <?php wp_nonce_field('admin_fields', 'admin_fields_nonce'); ?>
            <?php 
            if (isset($_POST['admin_fields_button'])) {
                
                if (check_admin_referer('admin_fields', 'admin_fields_nonce')) {
                    $field_name =  (convertToFormat($_POST['field_name'], 'name'));
                    $field_type =  ($_POST['field_type']);
                    $options_input =  ($_POST['options']);
                    $option_types = ['radio', 'checkbox', 'checkbox_multiselect', 'select'];
                  
                    // Check if fields are empty
                    if (empty($field_name) || empty($field_type)) {
                        echo '<div class="error"><p>Both fields are required.</p></div>';
                    } else {
                        $options = get_option('admin_fields', []);
                        if (!in_array($field_name, array_column($options, 'name'))) {
                            
                            if (in_array($field_type, $option_types)) {
                                // Convert comma-separated values into an associative array
                                $options_array = array_map('trim', explode(',', $options_input));
                                $associative_options_array = [];

                                foreach ($options_array as $option) {
                                    $uppercase_key = ucwords($option);
                                    $lowercase_value = strtolower($option);
                                    $associative_options_array[$uppercase_key] = $lowercase_value;
                                }

                                // Add the associative options array to the field data
                                $options[] = ['name' => $field_name, 'type' => $field_type, 'options' => $associative_options_array];
                            }else{
                                $options[] = ['name' =>  $field_name, 'type' => $field_type, 'options' => $options_input];
                            }
                            update_option('admin_fields', $options);
                            echo '<div class="updated"><p>Field saved.</p></div>';
                        } else {
                            echo '<div class="error"><p>Field name already exists.</p></div>';
                        }
                    }
                }
            }            
            ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row"><label for="field_name">Field Name</label></th>
                    <td><input type="text" id="field_name" name="field_name" class="regular-text wp-form-builder-field" /></td>
                <!-- </tr>
                <tr valign="top"> -->
                    <th scope="row"><label for="field_type">Field Type</label></th>
                    <td>
                        <select id="field_type" name="field_type" class=" wp-form-builder-field">
                            <?php foreach ($types as $key => $value) {
                                echo '<option value="' . $key . '">' . $value . '</option>';
                            } ?>
                        </select>
                    </td>
                </tr>
                <tr valign="top" colspan="2">
                    <th scope="row"><label for="options">Options</label></th>
                    <td><input type="text" id="options" name="options" class="regular-text wp-form-builder-field" /></td>
                </tr>
            </table>

            <?php submit_button('Save Admin Field', 'primary', 'admin_fields_button'); ?>

            <strong>Note: <em>For Dynamic Fields, Options Field string like:
                    <code>all_users</code>,
                    <code>all_custom_post_types</code>,
                    <code>all_post_types</code>,
                    <code>all_taxonomies</code>,
                    <code>all_posts</code>,
                    <code>all_terms</code>,
                    <code>all_pages</code>,
                    <code>all_post_type_titles</code> <br />
                    For ['radio', 'checkbox', 'checkbox_multiselect', 'select',] Use comma seperated values in options
                    field.
                </em></strong>
        </form>
    </div>
    <!-- ./ -->

    <div class="admin-settings">

        <form method="post" action="" id="save-admin-settings">

            <?php 
            if (isset($_POST['save_admin_settings_button'])) {
                    // Get the existing options from the database
                    $admin_settings = get_option('admin_settings', []);
                    
                    // Sanitize and update settings from POST data
                    foreach ($_POST as $key => $value) {
                        // Sanitize the value based on expected data type
                        $admin_settings[$key] = ($value);
                    }
                    // Update the options in the database
                    update_option('admin_settings', $admin_settings);
                
                    // Display a success message
                    echo '<div class="updated"><p>Field saved.</p></div>';                            
            }            
            ?>
            <?php

$saved_fields = get_option('admin_fields', []);

$saved_settings = get_option('admin_settings', []);

if (class_exists('WpFormBuilder')) {

     $fb = new WpFormBuilder;
    if(!empty($saved_fields)){ 
         wp_nonce_field('save_admin_settings', 'save_admin_settings_nonce'); 
    foreach ($saved_fields as $field){ 
// pre_dump($field);
        $type = $field['type'];
        $label =  convertToFormat($field['name'], 'label');
        $name =  convertToFormat($field['name'], 'name');
        $id =  convertToFormat($field['name'], 'id');
        $option_types = ['radio', 'checkbox', 'checkbox_multiselect', 'select', 'dynamic_select'];
        if (in_array($type, $option_types)) {
            $options = $field['options'];
        }
        else {
            $options = false;
        }
             $fb->field([
                'label'=> $label,
                'name' => $name,
                'type' => $type,
                'id' =>  $id,
                'options' =>  $options,
                'value' => !empty($saved_settings[$name]) ? $saved_settings[$name] : '', 
                'description'=> '<div><strong>Shortcode: </strong><code>['.$name.']</code></div><div>'.display_delete_option_form('admin_fields', $name).'</div>',
            ]);      
        }
    
    }  
    $fb->render();
} 
?>
            <?php submit_button('Save Admin Settings', 'primary', 'save_admin_settings_button'); ?>
        </form>

    </div>
</div> <!-- Ends wrap -->