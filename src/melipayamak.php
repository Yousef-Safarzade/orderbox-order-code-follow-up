<?php

namespace OrderboxOrderCodeFollowUp;

class melipayamak
{

    public static function send_orderbox_order_follow_up_sms($values){

        $curl = curl_init();

        $message = sprintf(
            "Dear Customer : %s \nYour Order Follow Up Code Has Been Added to Orderbox Website \nDate : %s \nCode :  %s \nPlease Visit %s For More Information" ,
            $values['customer_name'],
            $values['order_date'],
            $values['order_code'],
            'Orderbox Website'

        );

        $phone_number = get_option("wp_oofu_melipayamak_phone_number");

        $username = get_option("wp_oofu_melipayamak_username");

        $password = get_option("wp_oofu_melipayamak_password");


        if(empty($phone_number) || empty($username) || empty($password)){

            return false;

        }

        $options = array(
            'username'  => $username ,
            'password'  => $password,
            'from'      => $phone_number,
            'to'        => $values['customer_phone_number'],
            'isflash'   => false,
            'text'      => $message

        );


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

        var_dump($response);

        curl_close($curl);


    }
}