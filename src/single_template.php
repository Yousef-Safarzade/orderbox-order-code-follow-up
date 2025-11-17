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





    public static function generate_single_order_meta_data_seciton($fields){

        $items = array(
            'order_code' => array(
                'label' =>  __('Order Code' , 'orderbox-order-code-follow-up') ,
                'value' =>  $fields['order_code']
            ),
            'customer_name' => array(
                'label' =>  __('Customer Name' , 'orderbox-order-code-follow-up')  ,
                'value' =>  $fields['customer_name']
            ),
            'order_weight' => array(
                'label' =>  __('Order Weight' , 'orderbox-order-code-follow-up')   ,
                'value' =>  $fields['order_weight']
            ),
            'tipax_code' => array(
                'label' =>  __('Tipax Code', 'orderbox-order-code-follow-up')   ,
                'value' =>  $fields['tipax_code'] ,
                'description' => __(
                    '<a href="https://tipaxco.com/tracking" target="_blank">Check your Tipax Code</a>' ,
                    'orderbox-order-code-follow-up'
                )
            ),
        );



        if( !empty( $items['order_weight']['value'] ) ) {

            $items['order_weight']['value'] = $items['order_weight']['value'] . " " . __("KG",'orderbox-order-code-follow-up');

        }



        foreach ($items as $item_value) {

            $value = $item_value['value'];

            $description = !empty($item_value['description']) ? $item_value['description'] : '';

            $label = $item_value['label'];

            helper::generate_report_table_row( $label , $value , $description);

        }



    }





    public static function maybe_generate_single_order_cost_seciton(){

        $total_additional_const = 0;

        $total_additional_const += self::get_and_generate_total_cost_of_transport_costs();

        $total_additional_const += self::get_and_generate_total_cost_of_order_additional_costs();

        $total_additional_const += self::get_total_cost_of_order_products();


        if( $total_additional_const > 0 ) {

            $label = __('Total Additional Cost ( AED )', 'orderbox-order-code-follow-up');

            $value_formatted = number_format($total_additional_const);

            helper::generate_report_table_row( $label , $value_formatted , '' , 'no-border-bottom large-text-value' );

            $label = __('Total Additional Cost (Tooman)', 'orderbox-order-code-follow-up');

            $value = helper::get_aed_to_tooman_value($total_additional_const);

            helper::generate_report_table_row( $label , $value , ''  , 'large-text-value' );

        }

        self::maybe_generate_payment_section();

    }







    public static function maybe_generate_payment_section(){

        global $post;

        $label = __('Payment Status' , 'orderbox-order-code-follow-up');

        $payment_status = get_field('payment_status' , $post->ID );

        helper::generate_report_table_row( $label , $payment_status['label']  );

        if( $payment_status['value'] == 'not_payed' ) {

            if( get_field('show_pasargad_bank_payment_info' , $post->ID ) ){

                self::show_pasargad_bank_table_row();

            }

            if( get_field('show_meli_bank_payment_info' , $post->ID ) ){

                self::show_meli_bank_table_row();

            }

            if( get_field('show_emirate_bank_payment_info' , $post->ID ) ){

                self::show_emirate_bank_table_row();

            }

            if( get_field('show_custom_bank_payment_info' , $post->ID ) ){

                self::show_custom_bank_table_row();

            }

        }

    }




    public static function get_and_generate_total_cost_of_transport_costs(){

            global $post;

            $value = 0;

            $transport_cost = get_field('transport_cost', $post->ID );

            $weight = get_field('order_weight', $post->ID );

            $paid_status = false;

            if( !empty($transport_cost) && !empty($weight) ){

                $label = __('Transport Cost' , 'orderbox-order-code-follow-up');

                $value = $transport_cost * $weight;

                $paid_status = get_field('transport_cost_is_paid' , $post->ID );

                $paid_status_string = !empty( $paid_status ) && $paid_status == true ?
                    __('<br/><br/><span class="payment-status paid">Payment Status : Paid</span>', 'orderbox-order-code-follow-up') :
                    __('<br/><br/><span class="payment-status not-paid">Payment Status : Not Paid</span>', 'orderbox-order-code-follow-up') ;

                $value .= $paid_status_string;

                helper::generate_report_table_row( $label , $value);

            }


            return $paid_status ? 0 : (int)helper::convert_persian_number_to_english($value) ;

    }






    public static function get_and_generate_total_cost_of_order_additional_costs(){

        global $post;

        $result = 0;

        for($i = 1 ; $i < 5 ; $i++){

            $value = get_field('additional_cost_'.$i.'_value' , $post->ID);

            $label = sprintf( __('Additional Cost %s', 'orderbox-order-code-follow-up'), $i );

            if(empty($value)){

                continue;

            }


            $paid_status = get_field('additional_cost_'.$i.'_is_paid' , $post->ID );

            $paid_status_string = !empty( $paid_status ) && $paid_status == true ?
                __('<br/><br/><span class="payment-status paid">Payment Status : Paid</span>', 'orderbox-order-code-follow-up') :
                __('<br/><br/><span class="payment-status not-paid">Payment Status : Not Paid</span>', 'orderbox-order-code-follow-up') ;


            $value .= $paid_status_string;

            helper::generate_report_table_row(  $label , $value );

            if ( !$paid_status ){

                $result += (int)helper::convert_persian_number_to_english($value);

            }


        }

        return $result;


    }


    public static function get_total_cost_of_order_products(){

        global $post;

        $result = 0;

        for($i = 1 ; $i <= 5 ; $i++){

            $price = get_field('product_price_'.$i , $post->ID);

            $is_paid = get_field('product_price_'.$i.'_is_paid' , $post->ID ) == true;

            if( empty($price) || $is_paid ){

                continue;

            }

            $result += (int)helper::convert_persian_number_to_english($price);

        }

        return $result;

    }



    public static function show_pasargad_bank_table_row(){

        $label = __('Pasargad Bank Info' , 'orderbox-order-code-follow-up');

        $value = __("Card : 5022291317869681 <br /><br />SHABA Number : IR720570140380000653673001 <br/><br />Rezvan Arabzade", 'orderbox-order-code-follow-up');

        helper::generate_report_table_row( $label , $value  );

    }




    public static function show_meli_bank_table_row(){

        $label = __('Meli Bank Info' , 'orderbox-order-code-follow-up');

        $value = __("Card : 6037998139142383<br/><br/>SHABA Number : IR880170000000226242298004 <br/><br/>Ramin Arabzade", 'orderbox-order-code-follow-up');

        helper::generate_report_table_row( $label , $value  );

    }



    public static function show_emirate_bank_table_row(){

        $label = __('Emirate Bank Info' , 'orderbox-order-code-follow-up');

        $value = __("Ramin Arabzadeh <br/><br/>IBAN: AE53 0260 0002 1578 0959 501 <br/><br/>Account number: 0215780959501 <br/><br/>Currency: AED <br/><br/>Swift code: EBILAEAD <br/><br/>Routing number: 302620122 <br/><br/>NBD", 'orderbox-order-code-follow-up');

        helper::generate_report_table_row( $label , $value  );

    }


    public static function show_custom_bank_table_row(){

        global $post;

        $label = __('Other Payment Inro' , 'orderbox-order-code-follow-up');

        $value = get_field('custom_bank_payment_info' , $post->ID );

        helper::generate_report_table_row( $label , $value  );

    }




    public static function generate_single_order_product_list_section(){

        global $post;

        for ($i = 1; $i < 6; $i++) {

            $value = get_field('product_name_' . $i, $post->ID);

            $price = get_field('product_price_' . $i, $post->ID);

            if($price != '' &&  !empty($price) ){

                $price_string = sprintf( __('<br/><br/>Product Price : %s', 'orderbox-order-code-follow-up') , $price);

                $paid_status = get_field('product_price_'. $i .'_is_paid', $post->ID);

                $price_string .= !empty($paid_status) && $paid_status == true ?
                    __('<br/><br/><span class="payment-status paid">Payment Status : Paid</span>', 'orderbox-order-code-follow-up') :
                    __('<br/><br/><span class="payment-status not-paid">Payment Status : Not Paid</span>', 'orderbox-order-code-follow-up') ;


                $value .= $price_string;

            }

            $label = sprintf(__('Product Name %s', 'orderbox-order-code-follow-up'), $i);

            if (empty($value)) {

                continue;

            }

            helper::generate_report_table_row($label, $value);

        }

    }




    public static function generate_single_order_description_section(){

        global $post;

        $value = get_field('description', $post->ID);

        $label = __('Description', 'orderbox-order-code-follow-up');

        if ( empty( $value ) ) {

            return;

        }

        helper::generate_report_table_row( $label, $value );


    }



    public static function generate_upload_payment_document_form(){

        global $post;

        $payment_status = get_field('payment_status' , $post->ID );

        $label = __('Upload Payment <br />Document' , 'orderbox-order-code-follow-up');


        $nonce = wp_nonce_field( 'orderbox_payment_document_nonce' );

        $value = sprintf('
                    
                    <div class="doing-ajax-overlay">
                            <img src="%s">
                    </div>

                    <form id="orderboxPaymentUploadForm" action="#" method="post" enctype="multipart/form-data">
                    
                        %s
                        
                        <label id="uploadFormLabel" for="paymentDocument">%s</label>
                        
                        <input type="file" id="paymentDocument" name="paymentDocument" accept="image/png, image/jpeg" style="display: none" />
                                                
                        <input id="submitButton" type="submit" value="%s" />
                                                
                        <input id="paymentDocumentURL" name="paymentDocumentURL" type="hidden">
                        
                        <input id="acceptButton" type="button" value="%s" disabled/>
                        
                        <input id="resetButton" type="reset" value="%s" />
                        
                         <span class="paymentDocumentFileName"> </span>
                        
                  </form>
                 
                  <div class="orderbox-payment-document-preview"></div>
                  ',
            WP_OOFU_PLUGIN_MEDIA_FOLDER_URL . "/loading.gif",
            $nonce,
        __('1 - Choose', 'orderbox-order-code-follow-up'),
        __('2 - Upload' , 'orderbox-order-code-follow-up'),
        __('3 - Accept' , 'orderbox-order-code-follow-up'),
        __('Reset Form' , 'orderbox-order-code-follow-up')
        );

        helper::generate_report_table_row( $label, $value );

    }



    public static function generate_payment_document_preview(){

        global $post;

        $uploaded_document = get_field('payment_document', $post->ID);

        if( empty( $uploaded_document ) ) {

            return false;

        }

        $label = __('Payment Document Preview' , 'orderbox-order-code-follow-up');

        $value = sprintf("<img src='%s' class='uploaded-document-preview' />'" , $uploaded_document);

        helper::generate_report_table_row( $label, $value );

        for($i=2 ; $i < 5 ; $i++){

            $uploaded_document = get_field('payment_document_' . $i, $post->ID);

            if( empty( $uploaded_document ) ) {

                continue;

            }

            $label = __('Payment Document Preview' , 'orderbox-order-code-follow-up');

            $value = sprintf("<img src='%s' class='uploaded-document-preview' />'" , $uploaded_document);

            helper::generate_report_table_row( $label, $value );

        }



    }









}