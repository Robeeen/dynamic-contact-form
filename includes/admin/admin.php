<?php

// Hook to add a new admin menu
add_action('admin_menu', 'dff_create_menu');

function dff_create_menu() {
    add_menu_page(
        'Dynamic Form Fields', // Page title
        'Form Fields',         // Menu title
        'manage_options',      // Capability
        'dynamic-form-fields', // Menu slug
        'dff_settings_page'    // Function to display the page content
    );
}

function dff_settings_page() {
    ?>
    <div class="wrap">
        <h1>Dynamic Form Fields</h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('dff_settings_group');
            do_settings_sections('dynamic-form-fields');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

// Register settings
add_action('admin_init', 'dff_register_settings');

function dff_register_settings() {
    register_setting('dff_settings_group', 'dff_fields');
    
    add_settings_section(
        'dff_main_section', 
        '', 
        null, 
        'dynamic-form-fields'
    );

    add_settings_field(
        'dff_field_list', 
        '', 
        'dff_field_list_callback', 
        'dynamic-form-fields', 
        'dff_main_section'
    );
}

function dff_field_list_callback() {
    $fields = get_option('dff_fields', []);
    ?>
    <table id="dynamic-field-list" class="table">
        <thead>
            <tr>
                <th>Field Name</th>
                <th>Field Type</th>
                <th>Options (for Radio/Select)</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($fields)): ?>
                <?php foreach ($fields as $index => $field): ?>
                    <tr>
                        <td><input type="text" class="form-control" name="dff_fields[<?php echo $index; ?>][name]" value="<?php echo esc_attr($field['name']); ?>" /></td>
                        <td>
                            <select name="dff_fields[<?php echo $index; ?>][type]" class="field-type-selector form-control">
                                <option value="text" <?php selected($field['type'], 'text'); ?>>Text</option>
                                <option value="email" <?php selected($field['type'], 'email'); ?>>Email</option>
                                <option value="number" <?php selected($field['type'], 'number'); ?>>Number</option>
                                <option value="radio" <?php selected($field['type'], 'radio'); ?>>Radio</option>
                                <option value="select" <?php selected($field['type'], 'select'); ?>>Dropdown</option>
                                <option value="checkbox" <?Php selected($field['type'], 'checkbox'); ?>>Checkbox</option>
                            </select>
                        </td>
                        <td>
                            <input type="text" class="form-control" name="dff_fields[<?php echo $index; ?>][options]" value="<?php echo isset($field['options']) ? esc_attr($field['options']) : ''; ?>" placeholder="Comma separated options" />
                        </td>
                        <td><button class="remove-field btn btn-primary">Remove</button></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
    <button id="add-new-field" class="btn btn-success">Add New Field</button>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.getElementById('add-new-field').addEventListener('click', function (e) {
                e.preventDefault();
                var table = document.getElementById('dynamic-field-list').getElementsByTagName('tbody')[0];
                var rowCount = table.rows.length;
                var row = table.insertRow(rowCount);
                row.innerHTML = `
                    <td><input type="text" name="dff_fields[${rowCount}][name]" class="form-control" /></td>
                    <td>
                        <select name="dff_fields[${rowCount}][type]" class="field-type-selector form-control">
                            <option value="text">Text</option>
                            <option value="email">Email</option>
                            <option value="number">Number</option>
                            <option value="radio">Radio</option>
                            <option value="select">Dropdown</option>
                            <option value="checkbox">Checkbox</option>
                        </select>
                    </td>
                    <td><input type="text" name="dff_fields[${rowCount}][options]" placeholder="Comma separated options" class="form-control"  /></td>
                    <td><button class="remove-field btn-primary">Remove</button></td>
                `;
            });

            document.addEventListener('click', function(e) {
                if (e.target && e.target.className === 'remove-field btn btn-primary') {
                    e.preventDefault();
                    var row = e.target.closest('tr');
                    row.remove();
                }
            });
        });
    </script>
    <?php
}


add_shortcode('dynamic_form', 'dff_display_form');

function dff_display_form() {
    $fields = get_option('dff_fields', []);
    ob_start();
    ?>
    <form action="" method="post">
        <?php foreach ($fields as $field): ?>
            <p>
                <label><?php echo esc_html($field['name']); ?></label>
                <?php if ($field['type'] == 'text' || $field['type'] == 'email' || $field['type'] == 'number'): ?>
                    <input type="<?php echo esc_attr($field['type']); ?>" name="<?php echo esc_attr($field['name']); ?>" class="form-control" />
                
                <?php elseif ($field['type'] == 'radio'): ?>
                    <?php
                    $options = explode(',', $field['options']);
                    foreach ($options as $option): ?>
                        <div class="form-check"><label>
                            <input type="radio" name="<?php echo esc_attr($field['name']); ?>" value="<?php echo esc_attr(trim($option)); ?>" class="form-check-input" />
                            <?php echo esc_html(trim($option)); ?>
                        </label></div>
                    <?php endforeach; ?>
                
                <?php  elseif ($field['type'] == 'checkbox'): ?>
                    <?php 
                    $options = explode(',', $field['options']);
                    foreach ($options as $option) : ?>
                        <div class="form-check"><label>
                            <input type="checkbox" name="<?php echo esc_attr($field['name']) ;?>" value="<?php echo esc_attr(trim($option));?>" class="form-check-input"/>
                            <?php echo esc_html(trim($option)); ?>
                    </label></div>
                   <?php endforeach; ?>
                
                <?php elseif ($field['type'] == 'select'): ?>
                    <select name="<?php echo esc_attr($field['name']); ?>" class="form-control">
                        <?php
                        $options = explode(',', $field['options']);
                        foreach ($options as $option): ?>
                            <option value="<?php echo esc_attr(trim($option)); ?>"><?php echo esc_html(trim($option)); ?></option>
                        <?php endforeach; ?>
                    </select>
                <?php endif; ?>
            </p>
        <?php endforeach; ?>
        <p><input type="submit" value="Submit" class="btn btn-primary"></p>
    </form>
    <?php
    return ob_get_clean();
}

add_action('init', 'dff_handle_form_submission');

function dff_handle_form_submission() {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        global $wpdb;
        $table_name = $wpdb->prefix . 'dynamic_form_submissions';  // Reference the custom table

        $fields = get_option('dff_fields', []);
        foreach ($fields as $field) {
            if (isset($_POST[$field['name']])) {
                $value = sanitize_text_field($_POST[$field['name']]);

                // Insert each field value into the custom table
                $wpdb->insert(
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
            }
        }
    }
}
