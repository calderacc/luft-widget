<?php
/*
Plugin Name: Luft.jetzt
Plugin URI: https://luft.jetzt/
Description: Show pollution data in your blog
Version: 0.1
Author: Malte Hübner
Author URI: https://maltehuebner.de
License: GPL2+
License URI: https://www.gnu.org/licenses/gpl-2.0.txt
*/

require_once 'LuftWidget/LuftWidget.php';

add_action('widgets_init', function() {
    register_widget(LuftWidget::class);

    wp_register_style('luft_widget', plugins_url('style.css',__FILE__ ));
    wp_enqueue_style('luft_widget');
});
