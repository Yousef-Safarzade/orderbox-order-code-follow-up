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


        if( is_single() && get_post_type( get_the_ID() ) == 'orderbox_order' ){

            wp_enqueue_style(
                'oofu-lightbox-style',
                WP_OOFU_PLUGIN_CSS_FOLDER_URL . 'lightbox.min.css',
                [],
                WP_OOFU_PLUGIN_VERSION
            );


            wp_enqueue_script(
                'oofu-lightbox-script',
                WP_OOFU_PLUGIN_JS_FOLDER_URL . 'lightbox-plus-jquery.js',
                ['jquery'],
                WP_OOFU_PLUGIN_VERSION,
                array('in_footer' => true)
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