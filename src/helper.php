<?php

namespace OrderboxOrderCodeFollowUp;

class helper
{


    public static function define_hooks(){

        add_action('wp_ajax_handle_upload_payment_document', array(__CLASS__,'handle_upload_payment_document') );

        add_action('wp_ajax_nopriv_handle_upload_payment_document', array(__CLASS__,'handle_upload_payment_document') );

        add_action('wp_ajax_accept_payment_document', array(__CLASS__,'accept_payment_document') );

        add_action('wp_ajax_nopriv_accept_payment_document', array(__CLASS__,'accept_payment_document') );


        add_action('restrict_manage_posts', array(__CLASS__,'add_payment_status_filter_in_admin_panel_list') );
        add_action('pre_get_posts', array(__CLASS__,'filter_admin_panel_list_by_payment_status') );




    }





    public static function accept_payment_document(){

        //check_ajax_referer('orderbox_payment_document_nonce');

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

        //check_ajax_referer('orderbox_payment_document_nonce');

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

        if( empty($value) ){

            return;

        }

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




        /*$api_url = 'https://orderbox.ae/wp-json/mnswmc/v1/currency/9f8e7adfcdb7c395d33d08fcd968ade8';

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

        }*/


        $aed_to_tooman_exchange_rate = self::get_cached_tgju_aed_price();

        $final_value = round((int)$value * $aed_to_tooman_exchange_rate) * 100;

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

        if(!empty($_GET['key'])){

            $meta_query = array(
                    'relation' => 'AND',

                // Ensure order_code is 123
                    array(
                            'key'     => 'order_code',
                            'value'   => $_GET['key'],
                            'compare' => '=',
                    ),

                // Match any of the phone number fields
                    array(
                            'relation' => 'OR',
                            array(
                                    'key'     => 'customer_phone_number',
                                    'value'   => $user_phone,
                                    'compare' => 'LIKE',
                            ),
                            array(
                                    'key'     => 'customer_second_phone_number',
                                    'value'   => $user_phone,
                                    'compare' => 'LIKE',
                            ),
                            array(
                                    'key'     => 'customer_third_phone_number',
                                    'value'   => $user_phone,
                                    'compare' => 'LIKE',
                            ),
                    ),
            );

        } else {

            $meta_query = array(

                    array(
                            'relation' => 'OR',
                            array(
                                    'key'     => 'customer_phone_number',
                                    'value'   => $user_phone,
                                    'compare' => 'LIKE',
                            ),
                            array(
                                    'key'     => 'customer_second_phone_number',
                                    'value'   => $user_phone,
                                    'compare' => 'LIKE',
                            ),
                            array(
                                    'key'     => 'customer_third_phone_number',
                                    'value'   => $user_phone,
                                    'compare' => 'LIKE',
                            ),
                    ),
            );




        }


        $args = array(
            'posts_per_page' => -1,
            'post_type'      => 'orderbox_order',
            'post_status'    => 'publish',
            'fields'         => 'ids',
            'meta_query' => $meta_query
        );


        return get_posts($args);

    }




    public static function get_tgju_aed_price() {

        $url = 'https://www.tgju.org/profile/price_aed';

        $ch = curl_init();

        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_USERAGENT => 'Mozilla/5.0',
            CURLOPT_TIMEOUT => 15,
        ]);

        $html = curl_exec($ch);

        if (curl_errno($ch)) {
            curl_close($ch);
            return false;
        }

        curl_close($ch);


        if (!$html) {
            return false;
        }

        libxml_use_internal_errors(true);

        $dom = new \DOMDocument();
        $dom->loadHTML($html);

        $xpath = new \DOMXPath($dom);

        $nodes = $xpath->query(
            '//span[@data-col="info.last_trade.PDrCotVal"]'
        );


        if ($nodes->length === 0) {
            return false;
        }

        return trim($nodes->item(0)->textContent);
    }






    public static function get_cached_tgju_aed_price() {

        $transient_key = 'tgju_aed_price';

        $cached_value = get_transient($transient_key);



        // transient exists
        if ($cached_value !== false) {
            return (float)str_replace(',', '.', $cached_value);
        }

        // transient missing → fetch fresh value
        $fresh_value = self::get_tgju_aed_price();

        if ($fresh_value === false) {
            return false;
        }

        // cache for 1 hour
        set_transient(
            $transient_key,
            $fresh_value,
            HOUR_IN_SECONDS
        );

        return  (float)str_replace(',', '.', $fresh_value);
    }




    public static function get_current_page_url(): string
    {
        $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
                ? 'https'
                : 'http';

        $host = $_SERVER['HTTP_HOST'];
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        return "{$scheme}://{$host}{$path}";
    }


    public static function add_payment_status_filter_in_admin_panel_list(){

        global $typenow;

        // Only show on your desired post type
        if ($typenow !== 'orderbox_order') {
            return;
        }

        $selected = $_GET['payment_status'] ?? '';

        ?>
        <select name="payment_status">
            <option value=""> <?php _e('All Statuses', 'orderbox-order-code-follow-up'); ?></option>

            <option value="payed" <?php selected($selected, 'payed'); ?>>
                <?php _e('Paid', 'orderbox-order-code-follow-up'); ?>
            </option>

            <option value="not_payed" <?php selected($selected, 'not_payed'); ?>>
                <?php _e('Not Paid', 'orderbox-order-code-follow-up'); ?>
            </option>

            <option value="waiting_for_approval" <?php selected($selected, 'waiting_for_approval'); ?>>
                <?php _e('Waiting For Approval', 'orderbox-order-code-follow-up'); ?>
            </option>
        </select>
        <?php

    }



    public static function filter_admin_panel_list_by_payment_status($query){

        global $pagenow;

        if (
                !is_admin() ||
                $pagenow !== 'edit.php' ||
                !$query->is_main_query()
        ) {
            return;
        }

        // Only for Posts
        if (($query->get('post_type') ?: 'orderbox_order') !== 'orderbox_order') {
            return;
        }


        if (!empty($_GET['payment_status'])) {

            $query->set('meta_query', [
                    "relation" => "OR",
                    [
                            'key'     => 'payment_status',
                            'value'   => $_GET['payment_status'],
                            'compare' => '='
                    ]
            ]);

        }

    }




}