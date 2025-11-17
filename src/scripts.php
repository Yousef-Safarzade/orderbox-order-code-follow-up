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



            wp_enqueue_script(
                'oofu-front-script',
                WP_OOFU_PLUGIN_JS_FOLDER_URL . 'front-scripts.js',
                ['jquery','wp-i18n'],
                WP_OOFU_PLUGIN_VERSION,
                array('in_footer' => true)
            );


            wp_set_script_translations( 'oofu-front-script', 'orderbox-order-code-follow-up' );


            $messages = array(
                'general_error' => __('Something Went Wrong Please Try Again.', 'orderbox-order-code-follow-up'),
                'document_uploaded_success' => __('Document uploaded successfully.', 'orderbox-order-code-follow-up'),
                'document_uploaded_failed' => __('Document uploaded Failed , Please Try Again.', 'orderbox-order-code-follow-up'),
                'document_accepted_success' => __('Document accepted successfully.', 'orderbox-order-code-follow-up'),
                'document_accepted_failed' => __('Document accepted not successfully , please try again', 'orderbox-order-code-follow-up'),
            );

            wp_localize_script('oofu-front-script', 'oofuAjaxData', [
                'ajax_url' => admin_url('admin-ajax.php'),
                'postID'  => get_the_ID(),
            ]);


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