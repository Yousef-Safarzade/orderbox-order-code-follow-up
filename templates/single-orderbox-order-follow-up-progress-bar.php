<?php

$current_position = (int)$report_items['order_status']['value'];

$labels = array(

       1 =>  __('DUBAI / PACKING','orderbox-order-code-follow-up'),
       2 =>  __('SHIPPED','orderbox-order-code-follow-up'),
       3 =>  __('IRAN PORT','orderbox-order-code-follow-up'),
       4 =>  __('SHIRAZ','orderbox-order-code-follow-up'),
       5 =>  __('TIPAX','orderbox-order-code-follow-up'),
       6 =>  __('DELIVERED','orderbox-order-code-follow-up'),


);


?>

<div class="orderbox-order-follow-up-progress-bar">

    <ul>

        <?php foreach ($labels as $label_key => $label_value){ ?>


            <li class="orderbox-order-follow-up-progress-bar-item <?php echo $label_key <= $current_position ? 'activated' : 'not-activated' ?>">

                <div class="orderbox-order-follow-up-progress-bar-item-indicator">

                    <img class="activated-icon" src="<?php echo WP_OOFU_PLUGIN_MEDIA_FOLDER_URL . "activated-icon.svg" ?>">

                    <img class="not-activated-icon" src="<?php echo WP_OOFU_PLUGIN_MEDIA_FOLDER_URL . "not-activated-icon.svg" ?>">

                </div>

                <div class="orderbox-order-follow-up-progress-bar-item-label">

                    <?php echo $label_value ?>

                </div>

            </li>

        <?php } ?>

    </ul>


</div>