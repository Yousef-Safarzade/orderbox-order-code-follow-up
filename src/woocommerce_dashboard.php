<?php

namespace OrderboxOrderCodeFollowUp;

class woocommerce_dashboard
{


    public static function define_hooks(){

        add_action( 'init', array(__CLASS__,'add_woocommerce_new_dashboard_items_endpoint') );

        add_filter ( 'woocommerce_account_menu_items', array(__CLASS__,'manage_woocommerce_my_account_dashboard_navigation_items') );

        add_action( 'woocommerce_account_user-orders-followup_endpoint', array(__CLASS__,'user_order_followup_dashboard_content') );

        add_filter( 'query_vars', array(__CLASS__,'add_custom_woocommerce_query_vars') , 0 );



    }


    public static function add_woocommerce_new_dashboard_items_endpoint(){

        add_rewrite_endpoint( 'user-orders-followup', EP_ROOT | EP_PAGES );

    }



    public static function manage_woocommerce_my_account_dashboard_navigation_items($menu_links){


        $newKey = 'user-orders-followup';

        $newValue = __('Order Codes Followup', 'orderbox-order-code-follow-up');

        // Insert as the 3rd item
        $position = 2;

        $before = array_slice($menu_links, 0, $position, true);

        $after  = array_slice($menu_links, $position, null, true);

        $menu_links = $before + [$newKey => $newValue] + $after;

        return $menu_links;

    }


    public static function add_custom_woocommerce_query_vars($vars){

        $vars[] = 'user-orders-followup';


        return $vars;
    }



    public static function user_order_followup_dashboard_content(){


        $user_id = apply_filters( 'determine_current_user', false) ;

        $order_follow_up_items = helper::get_user_all_order_follow_up_items($user_id);

        if ( empty( $order_follow_up_items ) ){ ?>

            <div class="user-order-follow-ups-container">

                <h5><?php _e('Showing List of Your Order Followups','orderbox-order-code-follow-up'); ?></h5>

                <hr />  <br />

                <?php _e('<h5>There is No Order Code With Your Phone Number</h5>' , 'orderbox-order-code-follow-up'); ?>

            </div>

        <?php } else {




            $order_follow_up_items_formatted = array();

            foreach ($order_follow_up_items as $order_follow_up_item){

                $order_follow_up_items_formatted[ $order_follow_up_item ] = get_fields($order_follow_up_item);

                $order_follow_up_items_formatted[ $order_follow_up_item ]['permalink'] = get_permalink($order_follow_up_item);

                $order_follow_up_items_formatted[ $order_follow_up_item ]['signed_url'] =
                    $order_follow_up_items_formatted[ $order_follow_up_item ]['permalink'] .
                    '?order-pass=' .
                    md5( $order_follow_up_items_formatted[ $order_follow_up_item ]['order_password'] );

            }

            ?>

            <div class="user-order-follow-ups-container">

                <h5><?php _e('Showing List of Your Order Followups','orderbox-order-code-follow-up'); ?></h5>

                <hr />

                <ul class="user-order-follow-ups">

                    <?php foreach ($order_follow_up_items_formatted as $follow_up_item){ ?>

                        <li>

                        <span>

                            <?php printf( "<strong>%s</strong> : %s <br/>" , __('Order Code'  , 'orderbox-order-code-follow-up') , $follow_up_item['order_code'] )  ?>

                        </span>

                            <span>

                            <?php printf( "<strong>%s</strong> : %s <br/>" , __('Order Date'  , 'orderbox-order-code-follow-up') , helper::convert_date_to_shamsi( $follow_up_item['order_status']['order_received'] )  ) ?>

                        </span>


                            <span>

                            <?php printf( "<strong>%s</strong> : %s <br/>" , __('Payment Status'  , 'orderbox-order-code-follow-up') , $follow_up_item['payment_status']['label'] ) ?>

                        </span>

                            <span>

                            <a href="<?php echo $follow_up_item['signed_url']; ?>">

                                <?php _e('Show Details', 'orderbox-order-code-follow-up'); ?>

                            </a>

                        </span>

                            <hr/>

                        </li>

                    <?php } ?>


                </ul>

            </div>

        <?php }

    }

}