<?php

namespace OrderboxOrderCodeFollowUp;

class acf_helper
{

    public static function define_hooks(){

        add_action('admin_init',array(__CLASS__ ,'check_if_acf_is_active'));

        add_filter( 'acf/settings/load_json', array(__CLASS__ ,'acf_json_load_point')  );

        add_filter( 'acf/json/save_paths', array(__CLASS__ ,'custom_acf_json_save_paths') , 10, 2 );

        add_filter( 'acf/settings/load_json', array(__CLASS__ ,'custom_acf_json_load_point')  );

        add_action( 'acf/save_post', array(__CLASS__ ,'send_initial_notifications')  );

        add_filter('acf/prepare_field', array('OrderboxOrderCodeFollowUp\helper' ,'generate_random_password')  );

    }






    public static function check_if_acf_is_active(){

         if(!class_exists('ACF')){

             $message = __('This Plugin Requires <strong>Advance Custom Field</strong> Plugin to Work With , Please Install That Plugin First', 'orderbox-order-code-follow-up');

             wp_admin_notice($message , [ 'type' => 'error' ]);

         }

    }




    
    public static function acf_json_load_point( $paths ) {

        // Append the new path and return it.
        $paths[] = WP_OOFU_PLUGIN_FOLDER_PATH . '/acf-json';

        return $paths;

    }


    public static function custom_acf_json_save_paths( $paths, $post ) {


        if( $post['title'] === 'Orderbox Order Follow Up Settings'){

            $paths = array();

            $paths[] = WP_OOFU_PLUGIN_FOLDER_PATH . "acf-json";
        }


        return $paths;

    }

    public static function custom_acf_json_load_point($paths) {

        $paths[] = WP_OOFU_PLUGIN_FOLDER_PATH . 'acf-json';

        return $paths;

    }



    public static function send_initial_notifications($post_id){

        if( get_post_type($post_id) != 'orderbox_order'){

            return;

        }

        $values = get_fields( $post_id );

        if(
            $values['send_first_sms'] === true &&
            $values['first_sms_sent'] === false &&
            !empty($values['customer_phone_number'])
        ){

            melipayamak::send_orderbox_order_follow_up_sms($values);

            whatsappHelper::send_whatsapp_message($values);

            update_post_meta($post_id , 'first_sms_sent' , true);

        }

    }


}