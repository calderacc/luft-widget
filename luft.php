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

    public function form($instance)
    {
        if( $instance) {
            $title = esc_attr($instance['title']);
            $station = esc_attr($instance['station']);
        } else {
            $title = '';
            $station = '';
        }

        ?>
<p>
    <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Widget Title', 'caldera_luft_widget'); ?></label>
    <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
</p>

<p>
    <label for="<?php echo $this->get_field_id('station'); ?>"><?php _e('Station:', 'caldera_luft_widget'); ?></label>
    <input class="widefat" id="<?php echo $this->get_field_id('station'); ?>" name="<?php echo $this->get_field_name('station'); ?>" type="text" value="<?php echo $station; ?>" />
</p>
<?php
    }

    public function update($new_instance, $old_instance)
    {
        $instance = $old_instance;

        $instance['title'] = strip_tags($new_instance['title']);
        $instance['station'] = strip_tags($new_instance['station']);

        return $instance;
    }

    function widget($args, $instance)
    {
    }
}

add_action('widgets_init', function() {
    register_widget('LuftWidget');
});
