<?php
/**
 * Woocommerce Amazon Product Import
 *
 * Plugin Name: Orderbox Order Code Follow Up
 * Description: Store users offline orders code and give the ability to follow the order status
 * Version:     1.0.0
 * Author:      Yousef Safarzade
 * Author URI:  https://github.com/Yousef-Safarzade/
 * License:     GPLv2 or later
 * License URI: http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * Text Domain: orderbox-order-code-follow-up
 * Requires Plugins: advanced-custom-fields
 * Domain Path: /languages
 * Requires at least: 6.0
 * Requires PHP: 7.4
 *
 */


if ( ! defined( 'ABSPATH' ) ) {

    die( 'Invalid request.' );

}


if ( file_exists( __DIR__ . '/vendor/autoload.php' ) ) {

    require __DIR__ . '/vendor/autoload.php';

}

\OrderboxOrderCodeFollowUp\constants::define_constants(__FILE__ );

\OrderboxOrderCodeFollowUp\plugin_activation_hooks::define_hooks(__FILE__);

\OrderboxOrderCodeFollowUp\plugin_deactivation_hooks::define_hooks(__FILE__);

\OrderboxOrderCodeFollowUp\custom_post_types::define_hooks();

\OrderboxOrderCodeFollowUp\scripts::define_hooks();

\OrderboxOrderCodeFollowUp\single_template::define_hooks();

\OrderboxOrderCodeFollowUp\acf_helper::define_hooks();

\OrderboxOrderCodeFollowUp\settings::define_hooks();