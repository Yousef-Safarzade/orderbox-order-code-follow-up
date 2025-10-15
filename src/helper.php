<?php

namespace OrderboxOrderCodeFollowUp;

class helper
{

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

}