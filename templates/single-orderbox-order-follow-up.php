<?php

global $post;

$fields = get_fields($post->ID);

\OrderboxOrderCodeFollowUp\single_template::can_user_access_this_order_code_detail_page();

$image_url = \OrderboxOrderCodeFollowUp\single_template::get_qr_code_image_url();

$gallery_image = \OrderboxOrderCodeFollowUp\single_template::get_gallery_images();

$sticker_url = \OrderboxOrderCodeFollowUp\single_template::get_sticker_image_urls();

$image_count = count($gallery_image);

$button_title = empty($gallery_image) ? '' : __('Show Images' , 'orderbox-order-code-follow-up'  ) ;

$button_subtitle = $button_text = empty($gallery_image) ? '' : sprintf ( __( ' ( %s Images ) ' , 'orderbox-order-code-follow-up'  ) , $image_count);



?>

<?php load_template(WP_OOFU_ACTIVE_THEME_DIRECTORY_PATH . '/header.php'); ?>

<div class="order-box-order-follow-up-container">

    <?php require_once (WP_OOFU_PLUGIN_FOLDER_PATH . "/templates/single-orderbox-order-follow-up-progress-bar.php"); ?>

    <div class="orderbox-order-follow-up-main-container">



            <div class="orderbox-order-follow-up-sidebar-container">


                <?php if(is_array($sticker_url) && !empty($sticker_url['full'] ) ){ ?>

                    <a href="<?php echo $sticker_url['full'] ?>" data-lightbox="image-1">

                        <img src="<?php echo $sticker_url['thumb'] ?>">

                    </a>

                <?php } ?>


                <?php if(!empty($gallery_image)){ ?>

                    <div class="toggle-gallery-button">

                        <span class="button-icon">

                            <img src="<?php echo WP_OOFU_PLUGIN_MEDIA_FOLDER_URL . "iconw.png"; ?>" >

                        </span>

                        <span class="button-texts">

                             <span class="button-title"><?php echo $button_title . $button_subtitle  ?></span>

                            <span class="button-order-code"> <?php echo $fields['order_code'] ?></span>

                        </span>

                    </div>

                <?php } ?>

            </div>











        <div class="orderbox-order-follow-up-report-container">

            <div class="orderbox-order-follow-up-report-container">

                <?php \OrderboxOrderCodeFollowUp\single_template::generate_single_order_meta_data_seciton($fields); ?>

                <?php \OrderboxOrderCodeFollowUp\single_template::maybe_generate_order_purchase_codes_list(); ?>

                <?php \OrderboxOrderCodeFollowUp\single_template::generate_single_order_product_list_section(); ?>

                <?php \OrderboxOrderCodeFollowUp\single_template::maybe_generate_single_order_cost_seciton(); ?>

                <?php \OrderboxOrderCodeFollowUp\single_template::generate_upload_payment_document_form(); ?>

                <?php \OrderboxOrderCodeFollowUp\single_template::generate_payment_document_preview(); ?>

                <?php \OrderboxOrderCodeFollowUp\single_template::generate_single_order_description_section(); ?>

            </div>

        </div>

    </div>

</div>




<div class="order-follow-up-image-gallery-popup hidden">

    <div class="popup-close-icon">X</div>

    <div class="image-gallery-popup-overlay"></div>

    <div class="gallery-image-main-container">

        <?php foreach($gallery_image as $image){ ?>

            <a class="gallery-image" href="<?php echo $image ?>" data-lightbox="image-1">

                <img src="<?php echo $image ?>">

            </a>

        <?php } ?>

    </div>

</div>

<?php load_template(WP_OOFU_ACTIVE_THEME_DIRECTORY_PATH . '/footer.php'); ?>