<?php

namespace OrderboxOrderCodeFollowUp;

class whatsappHelper
{




    public static function format_whatsapp_number($number){


        $pos = strpos($number ,  "0");

        if($pos == "0"){

            $number = substr($number, 1);

        }

        return "+98" . $number;



    }


    public static function send_whatsapp_message($values){

        $api_key = get_option('wp_oofu_whatsiplus_api_key', '');

        if( empty( $api_key ) ){

            return false;

        }

        $message = sprintf(
            __("Dear Customer : %s \n\nYour Order Follow Up Code Has Been Added to Orderbox Website \n\nDate : %s \n\nCode :  %s \n\nPassword : %s \n\nPlease Visit Website For More Information \n\nhttps://OrderBox.ae" , 'orderbox-order-code-follow-up') ,
            $values['customer_name'],
            $values['order_status']['order_received'],
            $values['order_code'],
            $values['order_password']
        );


        $options = array(
            'api_key' => $api_key,
            'phonenumber' => self::format_whatsapp_number($values['customer_phone_number']),
            'message' => $message,
        );


        self::init_curl_request($options);

        if ( !empty($values['customer_second_phone_number']) ){

            $options['phonenumber'] = self::format_whatsapp_number($values['customer_second_phone_number']);

            self::init_curl_request($options);

        }

    }



    public static function init_curl_request($options){


        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.whatsiplus.com/sendMsg/' . $options['api_key'],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => "phonenumber={$options['phonenumber']}&message={$options['message']}",
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/x-www-form-urlencoded'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);


    }



}