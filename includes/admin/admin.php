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
    register_setting(
        'dynamic-form-group', 'form_fields'
    );

    add_settings_section(
        'dynamic_form_main_section',
        'Manage Form Fields',
        null,
        'dynamic_form_fields'
    );
    add_settings_field(
        'dynamic_form_list',
        'Form Fields',
        'dynamic_form_list_callback',
        'dynamic_form_fields',
        'dynamic_form_main_section'
    );
}

function dynamic_form_list_callback(){
    $fields = get_options('dynamic_fields', []);?>
        <table id="dynamic-field-list" class="table">
        <thead>
            <tr>
                <th>Field Name</th>
                <th>Field Type</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($fields)): ?>
                <?php foreach ($fields as $index => $field): ?>

                    <tr>
                            <td><input type="text" name="form_fields[<?php echo $index; ?>][name]" value="<?php echo esc_attr($field['name']); ?>" /></td>
                            <td>
                                <select name="form_fields[<?php echo $index; ?>][type]">
                                    <option value="text" <?php selected($field['type'], 'text'); ?>>Text</option>
                                    <option value="email" <?php selected($field['type'], 'email'); ?>>Email</option>
                                    <option value="number" <?php selected($field['type'], 'number'); ?>>Number</option>
                                </select>
                            </td>
                            <td><button class="remove-field">Remove</button></td>
                    </tr>
            <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
        </table>
        <button id="add-new-field">Add New Field</button>
<?php
}