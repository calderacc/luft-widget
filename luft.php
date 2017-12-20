<?php
/*
Plugin Name: Luft.jetzt Widget
Plugin URI: https://luft.jetzt/
Description: Show pollution data in your blog
Version: 0.1
Author: Malte Hübner
Author URI: https://maltehuebner.de
License: MIT
*/

class LuftWidget extends WP_Widget
{
    public function __construct()
    {
        parent::__construct(false,  __('Luft', 'caldera_luft_widget'));
    }

    function form($instance)
    {
    }

    function update($new_instance, $old_instance)
    {
    }

    function widget($args, $instance)
    {
    }
}

add_action('widgets_init', function() {
    register_widget('LuftWidget');
});
