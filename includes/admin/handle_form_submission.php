<?php

function dff_handle_form_submission() {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        global $wpdb;
        $table_name = $wpdb->prefix . 'dynamic_form_submissions';  // Reference the custom table

        $fields = get_option('dff_fields', []);
        if(!is_array($fields)){
            $fields = [];
        }
        $submission_success = '';
        foreach ($fields as $field) {
            if (isset($_POST[$field['name']])) {
                $value = $_POST[$field['name']];
                
                if ($field['type'] === 'email') {
                    // Sanitize email field
                    $value = sanitize_email($value);  
                } elseif($field['type'] === 'checkbox' && is_array($value)){
                    // Sanitize checkbox field
                    $value = implode(',', array_map('sanitize_text_field', $value));
                } elseif ($field['type'] === 'select') {
                    // Sanitize dropdown (select) field
                    $value = sanitize_text_field($value);
                } else {
                    // Sanitize all other fields as text
                    $value = sanitize_text_field($value);
                }                

                // Insert each field value into the custom table
               $result = $wpdb->insert(
                    $table_name,
                    array(
                        'field_name'   => $field['name'],
                        'field_value'  => $value,
                        'submission_date' => current_time('mysql'),
                    ),
                    array(
                        '%s',   // field_name data type
                        '%s',   // field_value data type
                        '%s'    // submission_date data type
                    )
                ); 
                if ($result !== false) {
                    $submission_success = true;  // Mark submission as successful
                }               
            }
        }
        if ($submission_success) {
            // Optional: Redirect to avoid re-submitting on refresh
            wp_redirect(add_query_arg('form_submitted', 'true', $_SERVER['REQUEST_URI']));
            exit;
        }
    }    
}

//var_dump($_POST);
