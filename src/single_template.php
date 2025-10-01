<?php

namespace OrderboxOrderCodeFollowUp;

class single_template
{


	public static function define_hooks(){

		add_filter( 'single_template', array(__CLASS__,'override_single_template') );


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

        $result[ __('Order Code' , 'orderbox-order-code-follow-up') ] = get_field('order_code' , $post_id);

        $result[ __('Customer Name' , 'orderbox-order-code-follow-up') ] = get_field('customer_name' , $post_id);

        $result[ __('Order Date' , 'orderbox-order-code-follow-up') ] = get_field('order_date' , $post_id);

        $result[ __('Order Status' , 'orderbox-order-code-follow-up') ] = get_field('order_status' , $post_id);

        $result[ __('Product Name 1' , 'orderbox-order-code-follow-up') ] = get_field('product_name_1' , $post_id);

        $result[ __('Product Name 2' , 'orderbox-order-code-follow-up') ] = get_field('product_name_2' , $post_id);

        $result[ __('Product Name 3' , 'orderbox-order-code-follow-up') ] = get_field('product_name_3' , $post_id);

        $result[ __('Product Name 4' , 'orderbox-order-code-follow-up') ] = get_field('product_name_4' , $post_id);

        $result[ __('Description' , 'orderbox-order-code-follow-up') ] = get_field('description' , $post_id);

        return $result;


    }



}