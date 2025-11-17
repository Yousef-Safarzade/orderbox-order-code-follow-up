<?php

namespace OrderboxOrderCodeFollowUp;

class helper
{


    public static function define_hooks(){

        add_action('wp_ajax_handle_upload_payment_document', array(__CLASS__,'handle_upload_payment_document') );

        add_action('wp_ajax_nopriv_handle_upload_payment_document', array(__CLASS__,'handle_upload_payment_document') );

        add_action('wp_ajax_accept_payment_document', array(__CLASS__,'accept_payment_document') );

        add_action('wp_ajax_nopriv_accept_payment_document', array(__CLASS__,'accept_payment_document') );


    }





    public static function accept_payment_document(){

        check_ajax_referer('orderbox_payment_document_nonce');

        if(empty($_POST['postID'])){

            wp_send_json_error('No Post ID received.');

        }


        if (empty($_POST['paymentDocument'])) {

            wp_send_json_error('No files received.');

        }



        if(!filter_var($_POST['paymentDocument'], FILTER_VALIDATE_URL)){

            wp_send_json_error('Invalid URL Has Been Provided.');

        }


        $current_payment = get_post_meta($_POST['postID'], 'payment_document',true);

        if( empty($current_payment) ){

            update_post_meta($_POST['postID'], 'payment_document', $_POST['paymentDocument']);

        } else {

            $updated = false;

            for($i = 2 ; $i < 5; $i++){

                if ( $updated ){

                    break;

                }

                $current_payment = get_post_meta($_POST['postID'], 'payment_document_'.$i,true);

                if( empty($current_payment) ){

                    update_post_meta($_POST['postID'], 'payment_document_'.$i, $_POST['paymentDocument']);

                    $updated = true;

                }

            }

        }

        update_post_meta($_POST['postID'], 'payment_status', 'waiting_for_approval');

        $fields = get_fields($_POST['postID']);

        $fields['payment_document'] = $_POST['paymentDocument'];

        whatsappHelper::send_payment_data_updated_message($fields);

        wp_send_json_success('Data Has Been Updated');

    }


    public static function handle_upload_payment_document(){

        check_ajax_referer('orderbox_payment_document_nonce');

        if(empty($_POST['postID'])){

            wp_send_json_error('No Post ID received.');

        }

        if (empty($_FILES['paymentDocument'])) {

            wp_send_json_error('No files received.');

        }

        if (!function_exists('wp_handle_upload')) {

            require_once(ABSPATH . 'wp-admin/includes/file.php');

        }


        $uploaded_files = [];

        $files = $_FILES['paymentDocument'];

        $file = [
            'name'     => $files['name'],
            'type'     => $files['type'],
            'tmp_name' => $files['tmp_name'],
            'error'    => $files['error'],
            'size'     => $files['size']
        ];

        $upload_overrides = ['test_form' => false];

        $movefile = wp_handle_upload($file, $upload_overrides);

        if ($movefile && !isset($movefile['error'])) {

            $uploaded_files[] = [
                'name' => $file['name'],
                'url'  => $movefile['url']
            ];

        }


        wp_send_json_success($uploaded_files);

    }





    public static function convert_date_to_shamsi($date_string){

        $date = \DateTime::createFromFormat('d/m/Y', $date_string);

        $jDate = \Morilog\Jalali\Jalalian::fromDateTime($date);

        return $jDate->format('j / F / Y');

    }


    /**
     * Auto-generates a secure random password for the 'order_password' field if empty.
     *
     * @param array $field The ACF field array.
     * @return array The modified field array.
     */
    public static function generate_random_password( $field ){

        if (
            !is_array($field) ||
            !isset($field['_name']) ||
            $field['_name'] != 'order_password'
        ) {

            return $field;

        }


        if( empty($field['value']) ) {

            $field['value'] = rand(10000,99999);

        }

        return $field;

    }




    public static function convert_persian_number_to_english($string) {

        $persian = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];

        $arabic = ['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'];

        $num = range(0, 9);

        $convertedPersianNums = str_replace($persian, $num, $string);

        $englishNumbersOnly = str_replace($arabic, $num, $convertedPersianNums);

        return $englishNumbersOnly;
    }




    public static function generate_report_table_row($label,$value,$description = '',$extra_class=''){

        ob_start();

        ?>

        <div class="orderbox-order-follow-up-report-item <?php echo $extra_class ?>">

            <div class="orderbox-order-follow-up-report-item-title">

                <?php echo $label ?? '' ?>

            </div>

            <div class="orderbox-order-follow-up-report-value">

                <?php echo $value ?? '' ?>

                <?php if(!empty($description) ){ ?>

                    <br />

                    <span class="orderbox-order-follow-up-report-value-description">

                    <?php echo $description ?>

                </span>

                <?php } ?>

            </div>

        </div>

        <?php

        echo ob_get_clean();

    }




    public static function get_aed_to_tooman_value($value){

        if ( $value <= 0 ){

            return 0;

        }

        $api_url = 'https://orderbox.ae/wp-json/mnswmc/v1/currency/9f8e7adfcdb7c395d33d08fcd968ade8';

        $response = wp_remote_get(

                esc_url_raw( $api_url ),

                array(

                    'headers' => array('referer' => home_url())

                )
        );

        try {

            $json = json_decode( $response['body'] , true );

            $aed_to_tooman_exchange_rate = $json[ '26619' ]['rate'];

        } catch ( Exception $ex ) {

            return 0;

        }

        $final_value = round((int)$value * $aed_to_tooman_exchange_rate);

        $final_value = number_format( $final_value, 0);

        return $final_value;

    }



    public static function get_latest_update_date($fields){

        $date = '';

        foreach ($fields['order_status'] as $date_item){

            if(!empty($date_item)){

                $date = $date_item;

            } else {

                break;

            }

        }

        return $date;

    }




    public static function get_user_all_order_follow_up_items($user_id){

        if( empty($user_id) ){

            return [];

        }

        $user = get_user($user_id);

        if( $user == false ){

            return [];

        }

        $user_phone = get_user_meta( $user_id, 'digits_phone_no', true );

        if ( empty( $user_phone ) ) {

            return [];

        }

        $args = array(
            'posts_per_page' => -1,
            'post_type'      => 'orderbox_order',
            'post_status'    => 'publish',
            'fields'         => 'ids',
            'meta_query' => array(
                'relation' => 'OR',
                array(
                    'key' => 'customer_phone_number',
                    'value' => $user_phone,
                    'compare' => 'LIKE',
                ),
                array(
                    'key' => 'customer_second_phone_number',
                    'value' => $user_phone,
                    'compare' => 'LIKE',
                ),
                array(
                    'key' => 'customer_third_phone_number',
                    'value' => $user_phone,
                    'compare' => 'LIKE',
                )

            )
        );

        return get_posts($args);

    }

}