<?php


add_shortcode('dynamic_form', 'dff_display_form');

function dff_display_form() {

    $fields = get_option('dff_fields', []);
    if(!is_array($fields)){
        $fields = [];
    }
    ob_start();
    ?>
    <form action="" method="POST">
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
                            <input type="checkbox" name="<?php echo esc_attr($field['name']) ;?>[]" value="<?php echo esc_attr(trim($option));?>" class="form-check-input"/>
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
        <p><input type="submit" value="Submit" class="btn btn-primary">  <input type="reset" value="reset" class="btn btn-primary"></p>
    </form>
    
    <?php   
    return ob_get_clean();
}

add_action('init', 'dff_handle_form_submission');