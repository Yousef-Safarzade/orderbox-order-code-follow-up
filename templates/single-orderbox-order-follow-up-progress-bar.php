<?php

$status = $items = get_field('order_status');

$labels = array(
        'order_received'        =>  __('ORDER RECEIVED','orderbox-order-code-follow-up'),
        'received_in_dubai'     =>  __('DUBAI / PACKING','orderbox-order-code-follow-up'),
        'send_to_iran'          =>  __('SHIPPED','orderbox-order-code-follow-up'),
        'received_in_iran'      =>  __('IRAN PORT','orderbox-order-code-follow-up'),
        'received_in_shiraz'    =>  __('SHIRAZ','orderbox-order-code-follow-up'),
        'sent_by_tipax'         =>  __('TIPAX','orderbox-order-code-follow-up'),
        'recieved_by_customer'  =>  __('DELIVERED','orderbox-order-code-follow-up'),
);


?>

<div class="orderbox-order-follow-up-progress-bar">

    <ul>

        <?php foreach ($status as $status_item_name => $status_item_value ){



            ?>


            <li class="orderbox-order-follow-up-progress-bar-item <?php echo !empty($status_item_value) ? 'activated' : 'not-activated' ?>">

                <div class="orderbox-order-follow-up-progress-bar-item-indicator">

                    <img class="activated-icon" src="<?php echo WP_OOFU_PLUGIN_MEDIA_FOLDER_URL . "activated-icon.svg" ?>">

                    <img class="not-activated-icon" src="<?php echo WP_OOFU_PLUGIN_MEDIA_FOLDER_URL . "not-activated-icon.svg" ?>">

                </div>

                <div class="orderbox-order-follow-up-progress-bar-item-label">


                    <span class="orderbox-order-follow-up-progress-bar-item-label-name">

                        <?php echo $labels[ $status_item_name ] ?>

                    </span>

                    <div class="orderbox-order-follow-up-progress-bar-item-label-separator"></div>

                    <span class="orderbox-order-follow-up-progress-bar-item-label-date">

                        <?php


                        if(!empty($status_item_value)){

                            $date = DateTime::createFromFormat('d/m/Y', $status_item_value);

                            $jDate = \Morilog\Jalali\Jalalian::fromDateTime($date);

                            echo $jDate->format('j / F / Y');

                        }



                        ?>

                    </span>

                </div>

            </li>

        <?php } ?>

    </ul>


</div>