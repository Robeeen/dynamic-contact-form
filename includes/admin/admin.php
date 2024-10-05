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

    }

}