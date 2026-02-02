<?php

namespace OrderboxOrderCodeFollowUp;

class settings
{

    public static function define_hooks(){

        add_action('admin_init', array(__CLASS__,'register_plugin_settings') );

    }


    public static function register_plugin_settings(){

        $settings = [
            'wp_oofu_melipayamak_phone_number' => [
                'label'    => 'Melipayamak Phone Number',
                'sanitize' => 'sanitize_text_field',
                'callback' => function(){
                    self::render_text_setting_input('wp_oofu_melipayamak_phone_number');
                },
            ],
            'wp_oofu_melipayamak_username' => [
                'label'    => 'Melipayamak Username',
                'sanitize' => 'sanitize_text_field',
                'callback' => function(){
                    self::render_text_setting_input('wp_oofu_melipayamak_username');
                }
            ],
            'wp_oofu_melipayamak_password' => [
                'label'    => 'Melipayamak Password',
                'sanitize' => 'sanitize_text_field',
                'callback' => function(){
                    self::render_text_setting_input('wp_oofu_melipayamak_password');
                }
            ],
            'wp_oofu_whatsiplus_api_key' => [
                'label'    => 'whatsiplus API Key',
                'sanitize' => 'sanitize_text_field',
                'callback' => function(){
                    self::render_text_setting_input('wp_oofu_whatsiplus_api_key');
                }
            ],
            'wp_oofu_accountant_whatsapp_number' => [
                'label'    => 'Orderbox Accountant Whatsapp Number',
                'sanitize' => 'sanitize_text_field',
                'callback' => function(){
                    self::render_text_setting_input('wp_oofu_accountant_whatsapp_number');
                }
            ],
            'wp_oofu_logistic_admin_whatsapp_number_key' => [
                'label'    => 'Orderbox Logistic Admin Whatsapp Number',
                'sanitize' => 'sanitize_text_field',
                'callback' => function(){
                    self::render_text_setting_input('wp_oofu_logistic_admin_whatsapp_number_key');
                }
            ],
            'wp_oofu_master_password' => [
                'label'    => 'Orderbox Master Password',
                'sanitize' => 'sanitize_text_field',
                'callback' => function(){
                    self::render_text_setting_input('wp_oofu_master_password');
                }
            ],
        ];


        foreach ($settings as $option_name => $meta) {
            register_setting(
                'general',
                $option_name,
                [
                    'sanitize_callback' => $meta['sanitize'],
                ]
            );

            add_settings_field(
                $option_name,
                __( $meta['label'], 'orderbox-order-code-follow-up' ),
                $meta['callback'],
                'general'
            );
        }



    }



    public static function render_text_setting_input($option_name){

        $value = get_option($option_name, ''); ?>

        <input type="text"
               id="<?php echo esc_attr($option_name); ?>"
               name="<?php echo esc_attr($option_name); ?>"
               value="<?php echo esc_attr($value); ?>"
               class="regular-text" />

        <p class="description"></p>

        <?php
    }


}