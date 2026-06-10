<?php

global $post;

$fields = get_fields($post->ID);

\OrderboxOrderCodeFollowUp\single_template::can_user_access_this_order_code_detail_page();

$image_url = \OrderboxOrderCodeFollowUp\single_template::get_qr_code_image_url();

$gallery_image = \OrderboxOrderCodeFollowUp\single_template::get_gallery_images();

$sticker_url = \OrderboxOrderCodeFollowUp\single_template::get_sticker_image_urls();

?>

<?php load_template(WP_OOFU_ACTIVE_THEME_DIRECTORY_PATH . '/header.php'); ?>

<div class="order-box-order-follow-up-container">

    <?php require_once (WP_OOFU_PLUGIN_FOLDER_PATH . "/templates/single-orderbox-order-follow-up-progress-bar.php"); ?>

    <div class="orderbox-order-follow-up-main-container">

        <?php if($image_url || !empty($gallery_image) ){ ?>

            <div class="orderbox-order-follow-up-sidebar-container">


                <?php if(!empty($image_url)){ ?>

                    <img src="<?php echo esc_url($image_url); ?>">

                <?php } ?>



                <?php if(is_array($sticker_url) && !empty($sticker_url['full'] ) ){ ?>

                    <a href="<?php echo $sticker_url['full'] ?>" data-lightbox="image-1">

                        <img class="sticker-image" src="<?php echo $sticker_url['thumb'] ?>">

                    </a>

                <?php } ?>



                <?php if(!empty($gallery_image)){ ?>


                    <?php foreach($gallery_image as $image){ ?>

                        <a class="gallery-image" href="<?php echo $image ?>" data-lightbox="image-1">

                            <img src="<?php echo $image ?>">

                        </a>

                    <?php } ?>

                    <div class="clearfix"></div>


                <?php } ?>

            </div>


        <?php } ?>









        <div class="orderbox-order-follow-up-report-container">

            <div class="orderbox-order-follow-up-report-container">

                <?php \OrderboxOrderCodeFollowUp\single_template::generate_single_order_meta_data_seciton($fields); ?>

                <?php \OrderboxOrderCodeFollowUp\single_template::generate_single_order_product_list_section(); ?>

                <?php \OrderboxOrderCodeFollowUp\single_template::maybe_generate_single_order_cost_seciton(); ?>

                <?php \OrderboxOrderCodeFollowUp\single_template::generate_upload_payment_document_form(); ?>

                <?php \OrderboxOrderCodeFollowUp\single_template::generate_payment_document_preview(); ?>

                <?php \OrderboxOrderCodeFollowUp\single_template::generate_single_order_description_section(); ?>

            </div>

        </div>

    </div>

</div>

<?php load_template(WP_OOFU_ACTIVE_THEME_DIRECTORY_PATH . '/footer.php'); ?>