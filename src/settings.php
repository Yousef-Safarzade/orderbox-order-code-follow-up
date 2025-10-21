<?php

namespace OrderboxOrderCodeFollowUp;

class settings
{

    public static function define_hooks(){

        add_action('admin_init', array(__CLASS__,'register_plugin_settings') );

    }


    public static function register_plugin_settings(){

        register_setting(
            'general',              // settings group (use 'general' for General Settings page)
            'wp_oofu_melipayamak_phone_number' // option name (stored in wp_options)
        );

        register_setting(
            'general',              // settings group (use 'general' for General Settings page)
            'wp_oofu_melipayamak_username' // option name (stored in wp_options)
        );

        register_setting(
            'general',              // settings group (use 'general' for General Settings page)
            'wp_oofu_melipayamak_password' // option name (stored in wp_options)
        );

        register_setting(
            'general',              // settings group (use 'general' for General Settings page)
            'wp_oofu_whatsiplus_api_key' // option name (stored in wp_options)
        );

        register_setting(
            'general',              // settings group (use 'general' for General Settings page)
            'wp_oofu_accountant_whatsapp_number' // option name (stored in wp_options)
        );

        register_setting(
            'general',              // settings group (use 'general' for General Settings page)
            'wp_oofu_logistic_admin_whatsapp_number_key' // option name (stored in wp_options)
        );



        add_settings_field(
            'wp_oofu_melipayamak_phone_number',                   // field ID
            __('Melipayamak Phone Number', 'orderbox-order-code-follow-up'),         // field title (label)
            array(__CLASS__,'render_wp_oofu_melipayamak_phone_number_field'),            // callback to render field HTML
            'general'                                  // settings page slug (general page)
        );


        add_settings_field(
            'wp_oofu_melipayamak_username',                   // field ID
            __('Melipayamak Username', 'orderbox-order-code-follow-up'),         // field title (label)
            array(__CLASS__,'render_wp_oofu_melipayamak_username_plugin_field'),            // callback to render field HTML
            'general'                                  // settings page slug (general page)
        );


        add_settings_field(
            'wp_oofu_melipayamak_password',                   // field ID
            __('Melipayamak Password', 'orderbox-order-code-follow-up'),         // field title (label)
            array(__CLASS__,'render_wp_oofu_melipayamak_password_plugin_field'),            // callback to render field HTML
            'general'                                  // settings page slug (general page)
        );



        add_settings_field(
            'wp_oofu_whatsiplus_api_key',                   // field ID
            __('whatsiplus API Key', 'orderbox-order-code-follow-up'),         // field title (label)
            array(__CLASS__,'render_wp_oofu_whatsiplus_api_key_plugin_field'),            // callback to render field HTML
            'general'                                  // settings page slug (general page)
        );





        add_settings_field(
            'wp_oofu_accountant_whatsapp_number',                   // field ID
            __('Orderbox Accountant Whatsapp Number', 'orderbox-order-code-follow-up'),         // field title (label)
            array(__CLASS__,'render_wp_oofu_accountant_whatsapp_number_plugin_field'),            // callback to render field HTML
            'general'                                  // settings page slug (general page)
        );


        add_settings_field(
            'wp_oofu_logistic_admin_whatsapp_number_key',                   // field ID
            __('Orderbox Logistic Admin Whatsapp Number', 'orderbox-order-code-follow-up'),         // field title (label)
            array(__CLASS__,'render_wp_oofu_logistic_admin_whatsapp_number_key_plugin_field'),            // callback to render field HTML
            'general'                                  // settings page slug (general page)
        );



    }



    public static function render_wp_oofu_melipayamak_phone_number_field(){

        $value = get_option('wp_oofu_melipayamak_phone_number', '');
        ?>
        <input type="text"
               id="wp_oofu_melipayamak_phone_number"
               name="wp_oofu_melipayamak_phone_number"
               value="<?php echo esc_attr($value); ?>"
               class="regular-text" />
        <p class="description"></p>
        <?php
    }



    public static function render_wp_oofu_melipayamak_username_plugin_field(){

        $value = get_option('wp_oofu_melipayamak_username', '');
        ?>
        <input type="text"
               id="wp_oofu_melipayamak_username"
               name="wp_oofu_melipayamak_username"
               value="<?php echo esc_attr($value); ?>"
               class="regular-text" />
        <p class="description"></p>
        <?php
    }



    public static function render_wp_oofu_melipayamak_password_plugin_field(){

        $value = get_option('wp_oofu_melipayamak_password', '');
        ?>
        <input type="password"
               id="wp_oofu_melipayamak_password"
               name="wp_oofu_melipayamak_password"
               value="<?php echo esc_attr($value); ?>"
               class="regular-text" />
        <p class="description"></p>
        <?php
    }



    public static function render_wp_oofu_whatsiplus_api_key_plugin_field(){

        $value = get_option('wp_oofu_whatsiplus_api_key', '');
        ?>
        <input type="text"
               id="wp_oofu_whatsiplus_api_key"
               name="wp_oofu_whatsiplus_api_key"
               value="<?php echo esc_attr($value); ?>"
               class="regular-text" />
        <p class="description"></p>
        <?php


    }




    public static function render_wp_oofu_accountant_whatsapp_number_plugin_field(){


        $value = get_option('wp_oofu_accountant_whatsapp_number', '');
        ?>
        <input type="text"
               id="wp_oofu_accountant_whatsapp_number"
               name="wp_oofu_accountant_whatsapp_number"
               value="<?php echo esc_attr($value); ?>"
               class="regular-text" />
        <p class="description"></p>
        <?php


    }




    public static function render_wp_oofu_logistic_admin_whatsapp_number_key_plugin_field(){

        $value = get_option('wp_oofu_logistic_admin_whatsapp_number_key', '');
        ?>
        <input type="text"
               id="wp_oofu_logistic_admin_whatsapp_number_key"
               name="wp_oofu_logistic_admin_whatsapp_number_key"
               value="<?php echo esc_attr($value); ?>"
               class="regular-text" />
        <p class="description"></p>
        <?php

    }

}