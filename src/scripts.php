<?php

namespace OrderboxOrderCodeFollowUp;



class scripts
{

    public static function define_hooks()
    {

        add_action('wp_enqueue_scripts', array(__CLASS__ , 'load_front_end_scripts'));

        add_action('admin_enqueue_scripts', array(__CLASS__ , 'load_admin_scripts'));

    }


    public static function load_front_end_scripts()
    {

        wp_enqueue_style(
            'oofu-front-style',
            WP_OOFU_PLUGIN_CSS_FOLDER_URL . 'front-styles.min.css',
            [],
            WP_OOFU_PLUGIN_VERSION
        );


        if(is_rtl()){

            wp_enqueue_style(
                'oofu-front-style',
                WP_OOFU_PLUGIN_CSS_FOLDER_URL . 'front-styles-rtl.min.css',
                [],
                WP_OOFU_PLUGIN_VERSION
            );

        }

    }


    public static function load_admin_scripts()
    {

            wp_enqueue_style(
                'oofu-admin-style',
                WP_OOFU_PLUGIN_CSS_FOLDER_URL . 'admin-styles.min.css',
                [],
                WP_OOFU_PLUGIN_VERSION
            );






    }


}