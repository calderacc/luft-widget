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
            $intro = esc_attr($instance['intro']);
        } else {
            $title = '';
            $station = '';
            $intro = '';
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

<p>
    <label for="<?php echo $this->get_field_id('intro'); ?>"><?php _e('Intro:', 'caldera_luft_widget'); ?></label>
    <textarea class="widefat" id="<?php echo $this->get_field_id('intro'); ?>" name="<?php echo $this->get_field_name('intro'); ?>"><?php echo $intro; ?></textarea>
</p>
<?php
    }

    public function update($new_instance, $old_instance)
    {
        $instance = $old_instance;

        $instance['title'] = strip_tags($new_instance['title']);
        $instance['intro'] = strip_tags($new_instance['intro']);
        $instance['station'] = strip_tags($new_instance['station']);

        return $instance;
    }

    function widget($args, $instance)
    {
        extract($args);
        $title = apply_filters('widget_title', $instance['title']);
        $intro = $instance['intro'];
        $station = $instance['station'];

        $luftData = $this->fetchData($station);

        echo $before_widget;

        if ($title) {
            echo $before_title . $title . $after_title;
        }

        echo '<div class="widget-text wp_widget_plugin_box">';

        if ($intro) {
            echo '<p class="widget-text">'.$intro.'</p>';
        }

        echo '<table>';

        foreach ($luftData as $data) {
            $pollutionLevelClass = sprintf('luft-pollutant luft-pollution-level-%d', $data->pollution_level);

            $row = '<tr class="%s"><td>%s</td><td ><a href="https://luft.jetzt/DEHH008">%s %s</a></td></tr>';

            echo sprintf($row, $pollutionLevelClass, $data->pollutant->name, $data->data->value, $data->pollutant->unit_html);
        }

        echo '</table>';

        echo '<p style="text-align: center;"><small>Luftdaten vom <a href="https://www.umweltbundesamt.de/daten/luftbelastung/aktuelle-luftdaten" title="Umweltbundesamt">Umweltbundesamt</a>, aufbereitet von <a href="https://luft.jetzt/">Luft<sup>jetzt</sup></a></small>';

        echo '</div>';
        echo $after_widget;
    }

    protected function fetchData(string $stationCode): array
    {
        $apiUrl = sprintf('https://luft.jetzt/api/%s', $stationCode);

        $response = wp_remote_get($apiUrl);

        $data = json_decode($response['body']);

        return $data;
    }
}

add_action('widgets_init', function() {
    register_widget('LuftWidget');
});
