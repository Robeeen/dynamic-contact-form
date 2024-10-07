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
                <th>Options (for Radio/Select/Checkbox)</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody class="wraps">
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
                                <option value="date" <?Php selected($field['type'], 'date'); ?>>Date Field</option>
                            </select>
                        </td>
                        <td>
                            <input type="text" class="form-control" name="dff_fields[<?php echo $index; ?>][options]" value="<?php echo isset($field['options']) ? esc_attr($field['options']) : ''; ?>" placeholder="Comma separated options" />
                        </td>
                        <td><button id="remove-field" class="btn btn-danger">Remove</button></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
    <button id="add-new-field" class="btn btn-success">Add New Field</button>

    <script>
       const dragarea = document.querySelector(".wraps");
       new Sortable(dragarea, {
        animation: 350
       });
    </script>
    <?php
}


