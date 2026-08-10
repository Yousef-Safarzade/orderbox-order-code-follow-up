<div class="header-container">

    <h5><?php _e('Showing List of Your Order Followups','orderbox-order-code-follow-up'); ?></h5>

    <div class="search-from-container">

        <form action="#" method="get">

            <div class="search-text-input-container">

                <input type="text" placeholder="<?php  _e('Search Code'  , 'orderbox-order-code-follow-up') ?>" name="key" value="<?php echo !empty($_GET['key']) ? $_GET['key'] : '' ?>">

                <?php if (!empty($_GET['key'])){ ?>

                    <a href="<?php echo  OrderboxOrderCodeFollowUp\helper::get_current_page_url(); ?>" class="reset-search-form">
                        X
                    </a>

                <?php } ?>

            </div>

            <input type="submit" value="<?php  _e('Search'  , 'orderbox-order-code-follow-up') ?>">

        </form>

    </div>


</div>