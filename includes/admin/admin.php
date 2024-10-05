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
        'Manage Form Fields', 
        null, 
        'dynamic-form-fields'
    );

    add_settings_field(
        'dff_field_list', 
        'Form Fields', 
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
                        <td><input type="text" name="dff_fields[<?php echo $index; ?>][name]" value="<?php echo esc_attr($field['name']); ?>" /></td>
                        <td>
                            <select name="dff_fields[<?php echo $index; ?>][type]" class="field-type-selector">
                                <option value="text" <?php selected($field['type'], 'text'); ?>>Text</option>
                                <option value="email" <?php selected($field['type'], 'email'); ?>>Email</option>
                                <option value="number" <?php selected($field['type'], 'number'); ?>>Number</option>
                                <option value="radio" <?php selected($field['type'], 'radio'); ?>>Radio</option>
                                <option value="select" <?php selected($field['type'], 'select'); ?>>Dropdown</option>
                            </select>
                        </td>
                        <td>
                            <input type="text" name="dff_fields[<?php echo $index; ?>][options]" value="<?php echo isset($field['options']) ? esc_attr($field['options']) : ''; ?>" placeholder="Comma separated options" />
                        </td>
                        <td><button class="remove-field">Remove</button></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
    <button id="add-new-field">Add New Field</button>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.getElementById('add-new-field').addEventListener('click', function (e) {
                e.preventDefault();
                var table = document.getElementById('dynamic-field-list').getElementsByTagName('tbody')[0];
                var rowCount = table.rows.length;
                var row = table.insertRow(rowCount);
                row.innerHTML = `
                    <td><input type="text" name="dff_fields[${rowCount}][name]" /></td>
                    <td>
                        <select name="dff_fields[${rowCount}][type]" class="field-type-selector">
                            <option value="text">Text</option>
                            <option value="email">Email</option>
                            <option value="number">Number</option>
                            <option value="radio">Radio</option>
                            <option value="select">Dropdown</option>
                        </select>
                    </td>
                    <td><input type="text" name="dff_fields[${rowCount}][options]" placeholder="Comma separated options" /></td>
                    <td><button class="remove-field">Remove</button></td>
                `;
            });

            document.addEventListener('click', function(e) {
                if (e.target && e.target.className === 'remove-field') {
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
                    <input type="<?php echo esc_attr($field['type']); ?>" name="<?php echo esc_attr($field['name']); ?>" />
                
                <?php elseif ($field['type'] == 'radio'): ?>
                    <?php
                    $options = explode(',', $field['options']);
                    foreach ($options as $option): ?>
                        <label>
                            <input type="radio" name="<?php echo esc_attr($field['name']); ?>" value="<?php echo esc_attr(trim($option)); ?>" />
                            <?php echo esc_html(trim($option)); ?>
                        </label>
                    <?php endforeach; ?>
                
                <?php elseif ($field['type'] == 'select'): ?>
                    <select name="<?php echo esc_attr($field['name']); ?>">
                        <?php
                        $options = explode(',', $field['options']);
                        foreach ($options as $option): ?>
                            <option value="<?php echo esc_attr(trim($option)); ?>"><?php echo esc_html(trim($option)); ?></option>
                        <?php endforeach; ?>
                    </select>
                <?php endif; ?>
            </p>
        <?php endforeach; ?>
        <p><input type="submit" value="Submit"></p>
    </form>
    <?php
    return ob_get_clean();
}

add_action('init', 'dff_handle_form_submission');

function dff_handle_form_submission() {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $fields = get_option('dff_fields', []);
        foreach ($fields as $field) {
            $value = sanitize_text_field($_POST[$field['name']]);
            // Process the submitted values here, e.g., save to database or send an email
        }
    }
}
