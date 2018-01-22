<?php

require_once __DIR__ . '/../Util/LuftData.php';
require_once __DIR__ . '/../Util/StationList.php';

class LuftWidget extends WP_Widget
{
    public function __construct()
    {
        parent::__construct(false,  __('Luft', 'caldera_luft'));
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
            <label for="<?php echo $this->get_field_id('intro'); ?>"><?php _e('Intro:', 'caldera_luft_widget'); ?></label>
            <textarea class="widefat" id="<?php echo $this->get_field_id('intro'); ?>" name="<?php echo $this->get_field_name('intro'); ?>"><?php echo $intro; ?></textarea>
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('station'); ?>"><?php _e('Station:', 'caldera_luft_widget'); ?></label>
            <select id="<?php echo $this->get_field_id('station'); ?>" name="<?php echo $this->get_field_name('station'); ?>" class="widefat" style="width:100%;">
                <?php

                $this->createStationSelectList($instance);

                ?>
            </select>
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

        $luftDataUtil = new LuftData();
        $luftData = $luftDataUtil->fetchData($station);

        if (!$luftData) {
            return;
        }

        echo $args['before_widget'];

        if ($title) {
            echo sprintf('%s %s %s', $args['before_title'], $title, $args['after_title']);
        }

        echo '<div class="widget-text wp_widget_plugin_box">';

        if ($intro) {
            echo sprintf('<p class="widget-text">%s</p>', $intro);
        }

        echo '<table>';

        foreach ($luftData as $data) {
            $pollutionLevelClass = sprintf('pollutant pollution-level-%d', $data->pollution_level);

            $row = '<tr class="%s"><td>%s</td><td ><a href="https://luft.jetzt/%s">%s %s</a></td></tr>';

            echo sprintf($row, $pollutionLevelClass, $data->pollutant->name, $station, $data->data->value, $data->pollutant->unit_html);
        }

        echo '</table>';

        echo '<p style="text-align: center;"><small>Luftdaten vom <a href="https://www.umweltbundesamt.de/daten/luftbelastung/aktuelle-luftdaten" title="Umweltbundesamt">Umweltbundesamt</a>, aufbereitet von <a href="https://luft.jetzt/">Luft<sup>jetzt</sup></a></small>';

        echo '</div>';

        echo $args['after_widget'];
    }
    
    protected function createStationSelectList($instance): void
    {
        $stationListUtil = new StationList();
        $stateList = $stationListUtil->getStateStationList();

        foreach ($stateList as $stateCode => $stationList) {
            echo sprintf('<optgroup label="%s">', $stateCode);

            foreach ($stationList as $stationCode => $stationName) {
                echo sprintf('<option %s value="%s">%s</option>', selected($instance['station'], $stationCode), $stationCode, $stationName);
            }

            echo '</optgroup>';
        }
    }
}
