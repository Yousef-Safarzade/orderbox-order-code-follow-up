<?php

namespace OrderboxOrderCodeFollowUp;

class single_template
{


	public static function define_hooks(){

		add_filter( 'single_template', array(__CLASS__,'override_single_template') );

        add_action( 'template_redirect', array(__CLASS__,'handle_404_order_code') );

        add_action( 'template_redirect', array(__CLASS__,'check_for_order_code') );


    }



    public static function check_for_order_code(){

        if(!empty($_GET['order-code']) && !empty($_GET['order-pass'])){

            $order_hash_pass = md5($_GET['order-pass']);

            $meta_query = array(
                'relation' => 'AND',
                array(
                    'key' => 'order_code',
                    'value' => $_GET['order-code']
                ),
                array(
                    'key' => 'order_password',
                    'value' => $_GET['order-pass']
                )
            );


            $order_code_post_id = get_posts(
                array(
                    'post_type' => 'orderbox_order',
                    'meta_query' => $meta_query,
                    'fields' => 'ids'
                )
            );


           if( empty($order_code_post_id) ){

               $base_url = get_post_type_archive_link('orderbox_order');

               $full_url = $base_url . "/not-found";

           } else {

               $full_url = get_the_permalink( $order_code_post_id[0] ) . "?order-pass=" . $order_hash_pass;

           }


            wp_redirect($full_url);

            exit();

        }

    }


    public static function handle_404_order_code(){


        if (is_404()){

            global $wp;

            $post_type_object = get_post_type_object('orderbox_order');

            $post_type_url = $post_type_object->rewrite['slug'];

            if(strpos($wp->request, $post_type_url) !== false){

                $file_path = WP_OOFU_PLUGIN_FOLDER_PATH .'/templates/single-orderbox-order-follow-up-not-found.php';

                include$file_path;

                die();

            }


        }


    }




    public static function override_single_template( $single_template ){

		global $post;

        if($post->post_type == 'orderbox_order'){

            $file = WP_OOFU_PLUGIN_FOLDER_PATH .'/templates/single-orderbox-order-follow-up.php';

            if( file_exists( $file ) ) $single_template = $file;


        }

		return $single_template;

	}





    public static function get_report_items_of_post($post_id = ''){

        $post_id = empty($post_id) ? get_the_ID() : $post_id;

        $result = array();

        $weight_appendix = __("KG",'orderbox-order-code-follow-up');




        $result['order_code'] = array(
            'label' =>  __('Order Code' , 'orderbox-order-code-follow-up') ,
            'value' =>  get_field('order_code' , $post_id)
        );


        $result['customer_name'] = array(
            'label' =>  __('Customer Name' , 'orderbox-order-code-follow-up')  ,
            'value' =>  get_field('customer_name' , $post_id)
        );



        $result['order_status'] = array(
            'label'=>   __('Order Status' , 'orderbox-order-code-follow-up')   ,
            'value' =>  get_field('order_status' , $post_id)
        );


        $result['order_weight'] = array(
            'label' =>  __('Order Weight' , 'orderbox-order-code-follow-up')   ,
            'value' =>  get_field('order_weight' , $post_id)
        );




        if( !empty( $result['order_weight']['value'] ) ) {

            $result['order_weight']['value'] = $result['order_weight']['value'] . " " . $weight_appendix;

        }




        $result['tipax_code'] = array(
            'label' =>  __('Tipax Code', 'orderbox-order-code-follow-up')   ,
            'value' =>  get_field('tipax_code' , $post_id) ,
            'description' => __(
                '<a href="https://tipaxco.com/tracking" target="_blank">Check your Tipax Code</a>' ,
                'orderbox-order-code-follow-up'
            )
        );



        $result['product_name_1'] = array(
            'label' =>  __('Product Name 1' , 'orderbox-order-code-follow-up')  ,
            'value' =>  get_field('product_name_1' , $post_id)
        );


        $result['product_name_2'] = array(
            'label' =>  __('Product Name 2' , 'orderbox-order-code-follow-up')  ,
            'value' =>  get_field('product_name_2' , $post_id)
        );


        $result['product_name_3'] = array(
            'label' =>  __('Product Name 3' , 'orderbox-order-code-follow-up')  ,
            'value' => get_field('product_name_3' , $post_id)
        );


        $result['product_name_4'] = array(
            'label' =>  __('Product Name 4' , 'orderbox-order-code-follow-up')  ,
            'value' => get_field('product_name_4' , $post_id)
        );


        $result['product_name_5'] = array(
            'label' =>  __('Product Name 5' , 'orderbox-order-code-follow-up')  ,
            'value' => get_field('product_name_5' , $post_id)
        );



        $result['description'] = array(
            'label' =>  __('Description' , 'orderbox-order-code-follow-up')  ,
            'value' =>  get_field('description' , $post_id)
        );


        return $result;

    }


    public static function get_qr_code_image_url($post_id = ''){

        $post_id = empty($post_id) ? get_the_ID() : $post_id;

        $url = get_field('qr_code_image' , $post_id);

        return !empty( $url ) ? $url : false;

    }




    public static function get_sticker_image_urls($post_id = ''){

        $post_id = empty($post_id) ? get_the_ID() : $post_id;

        $image_id = get_field('sticker_image' , $post_id);

        if ( empty( $image_id ) ) {

            return false;

        } else {

            return array(
              'full' => wp_get_attachment_image_url( $image_id, 'full' ),
              'thumb' =>   wp_get_attachment_image_url( $image_id, 'small' ),
            );
        }

        return !empty( $url ) ? $url : false;

    }





    public static function can_user_access_this_order_code_detail_page(){

        $user = wp_get_current_user();

        if(in_array('administrator' , (array) $user->roles) || in_array('shop_manager' , (array) $user->roles) ){

            return true;

        }


        $post_pass = get_field('order_password' , get_the_ID() );

        if ( md5($post_pass) !== $_GET['order-pass'] ) {

            $base_url = get_post_type_archive_link('orderbox_order');

            $full_url = $base_url . "/not-found";

            wp_redirect($full_url);

            exit();

        }


    }






}