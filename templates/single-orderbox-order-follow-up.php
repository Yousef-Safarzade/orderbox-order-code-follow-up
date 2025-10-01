<?php $current_theme_path = get_template_directory(); ?>

<?php $report_items = \OrderboxOrderCodeFollowUp\single_template::get_report_items_of_post(); ?>

<?php load_template($current_theme_path . '/header.php'); ?>

<div class="orderbox-order-follow-up-main-container">

    <h1 class="orderbox-order-follow-up-main-title">

        <?php _e('Orderbox Order Follow Up','orderbox-order-code-follow-up'); ?>

    </h1>

    <div class="orderbox-order-follow-up-report-container">

            <?php foreach ($report_items as $report_item_title => $report_item_value ) { ?>

                <?php if(empty($report_item_value)){

                    continue;

                } ?>

                <div class="orderbox-order-follow-up-report-item">

                    <div class="orderbox-order-follow-up-report-item-title">

                        <?php echo $report_item_title ?>

                    </div>

                    <div class="orderbox-order-follow-up-report-value">

                        <?php echo $report_item_value ?>

                    </div>

                </div>

            <?php } ?>
    </div>

</div>

<?php load_template($current_theme_path . '/footer.php'); ?>