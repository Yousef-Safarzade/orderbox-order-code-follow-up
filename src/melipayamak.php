<?php

namespace OrderboxOrderCodeFollowUp;

class melipayamak
{

    public static function send_orderbox_order_follow_up_sms($values){

        $config = self::get_required_configs();

        if(empty($config['phone_number']) || empty($config['username']) || empty($config['password'])){

            return false;

        }


        $message = sprintf(
            __("Dear Customer : %s \nYour Order Follow Up Code Has Been Added to Orderbox Website \nDate : %s \nCode :  %s \n Password : %s \nPlease Visit OrderBox Website For More Information" , 'orderbox-order-code-follow-up') ,
            $values['customer_name'],
            \OrderboxOrderCodeFollowUp\helper::convert_date_to_shamsi($values['order_status']['order_received']),
            $values['order_code'],
            $values['order_password']
        );


        $options = array(
            'username'  => $config['username'] ,
            'password'  => $config['password'],
            'from'      => $config['phone_number'],
            'to'        => $values['customer_phone_number'],
            'isflash'   => false,
            'text'      => $message
        );



        self::init_curl_request($options);

        if ( !empty($values['customer_second_phone_number']) ){

            $options['to'] = $values['customer_second_phone_number'];

            self::init_curl_request($options);

        }


        if ( !empty($values['customer_third_phone_number']) ){

            $options['to'] = $values['customer_third_phone_number'];

            self::init_curl_request($options);

        }


    }



    public static function get_required_configs(){

        return array(
            'phone_number' => get_option("wp_oofu_melipayamak_phone_number"),
            'username' => get_option("wp_oofu_melipayamak_username"),
            'password' => get_option("wp_oofu_melipayamak_password")
        );

    }




    public static function init_curl_request($options){

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://rest.payamak-panel.com/api/SendSMS/SendSMS',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => http_build_query($options),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/x-www-form-urlencoded',
                'Cache-Control: no-cache'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

    }

    
}