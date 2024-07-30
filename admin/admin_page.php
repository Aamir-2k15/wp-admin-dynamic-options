<?php
    add_action('admin_head', function(){
        ?>
<style>
.special-form {
    /* width: 80% !important; */
    margin: 0 auto;
    background: #fff;
    padding: 20px;
    border: 1px solid #ccc;
    border-radius: 5px;
    box-shadow: 0 0 5px #ccc;
    margin-top: 20px;
}
.special-form .wp-form-builder-field {
    width: 100% !important;
}
.wrap form {
    width: 100% !important;
}

.wp-form-builder-field {
    padding: 3px 5px !important;
    width: 72% !important;
    max-width: 73%;
}
</style>
<?php
    }); 
function add_admin_settings() {
    ?>
<?php  
$path = 'pages/admin_settings.php';
require_once $path; 
?>
<?php
}


$args = [
    'page_title' => 'Dynamic Options',
    'menu_title' => 'Dynamic Options',
    'capability' => 'manage_options',
    'menu_slug' => 'dynamic-options',
    'callback' => 'add_admin_settings',
    'icon' => 'dashicons-database-add', // Change this line
];


$main_page = new CustomAdminPage($args);

