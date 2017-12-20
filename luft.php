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
        extract( $args );
        // these are the widget options
        $title = apply_filters('widget_title', $instance['title']);
        $text = $instance['text'];
        $station = $instance['station'];

        echo $before_widget;
        // Display the widget
        echo '<div class="widget-text wp_widget_plugin_box">';

        // Check if title is set
        if ( $title ) {
            echo $before_title . $title . $after_title;
        }

        // Check if text is set
        if( $station ) {
            echo '<p class="wp_widget_plugin_text">'.$station.'</p>';
        }

        echo '</div>';
        echo $after_widget;
    }
}

add_action('widgets_init', function() {
    register_widget('LuftWidget');
});
