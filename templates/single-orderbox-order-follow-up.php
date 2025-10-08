<?php

$report_items = \OrderboxOrderCodeFollowUp\single_template::get_report_items_of_post();

\OrderboxOrderCodeFollowUp\single_template::can_user_access_this_order_code_detail_page();

$image_url = \OrderboxOrderCodeFollowUp\single_template::get_qr_code_image_url();

?>

<?php load_template(WP_OOFU_ACTIVE_THEME_DIRECTORY_PATH . '/header.php'); ?>

<div class="order-box-order-follow-up-container">

    <?php require_once (WP_OOFU_PLUGIN_FOLDER_PATH . "/templates/single-orderbox-order-follow-up-progress-bar.php"); ?>


    <div class="orderbox-order-follow-up-main-container">

        <?php if($image_url){ ?>

            <div class="orderbox-order-follow-up-qr-code-container">

                <img src="<?php echo esc_url($image_url); ?>">

            </div>


        <?php } ?>


        <div class="orderbox-order-follow-up-report-container">

            <div class="orderbox-order-follow-up-report-container">

                <?php foreach ($report_items as $report_item_title => $report_item_value ) { ?>

                    <?php if($report_item_title == 'order_status' || empty($report_item_value['value'])){

                        continue;

                    } ?>

                    <div class="orderbox-order-follow-up-report-item">

                        <div class="orderbox-order-follow-up-report-item-title">

                            <?php echo $report_item_value['label'] ?>

                        </div>

                        <div class="orderbox-order-follow-up-report-value">

                            <?php echo $report_item_value['value'] ?>

                            <?php if(!empty($report_item_value['description'])){ ?>

                                <br />

                                <span class="orderbox-order-follow-up-report-value-description">

                                <?php echo $report_item_value['description'] ?>

                            </span>

                            <?php } ?>

                        </div>

                    </div>

                <?php } ?>
            </div>

        </div>

    </div>

</div>

<?php load_template(WP_OOFU_ACTIVE_THEME_DIRECTORY_PATH . '/footer.php'); ?>