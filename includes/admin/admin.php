<?php

add_action('admin_menu', 'dynamic_menu_fields');

function dynamic_menu_fields(){
    add_menu_page(
        'Dynamic Forms Plugin',
        'Dynamic Form',
        'manage_options',
        'dynamic-forms-plugins',
        'callback_dynamic_form'
    );

    function callback_dynamic_form(){
        ?>
        <div class="new_form">
            <h1>Dynamic Form Fields</h1>
            <form method="post" action="options.php">
                <?php
                settings_fields('dff_settings_group');
                do_settings_sections('dynamic-form-fields');
                submit_button();
                ?>
            </form>
        </div><?php
    }

}

//register settings page
add_action('admin_init', 'dynamic_form_register_settings');

function dynamic_form_register_settings(){
    register_settings(
        'dynamic-form-group', 'form_fields'
    );

    add_settings_section(
        'dynamic_form_main_section',
        'Manage Form Fields',
        null,
        'dynamic_form_fields'
    )
    add_settings_field(
        'dynamic_form_list',
        'Form Fields',
        'dynamic_form_list_callback',
        'dynamic_form_fields',
        'dynamic_form_main_section'
    )
}