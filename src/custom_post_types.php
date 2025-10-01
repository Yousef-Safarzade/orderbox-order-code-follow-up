<?php

namespace OrderboxOrderCodeFollowUp;

class custom_post_types
{

    public static function define_hooks(){

        add_action( 'init', array( __CLASS__, 'register_orderbox_orders_post_types' ) );

    }


    public static function register_orderbox_orders_post_types(){


        if( post_type_exists('orderbox_order')){

            return true;

        }


        $labels = array(
            'name'                  => _x( 'Orderbox Orders', 'Post type general name', 'orderbox-order-code-follow-up' ),
            'singular_name'         => _x( 'Orderbox Order', 'Post type singular name', 'orderbox-order-code-follow-up' ),
            'menu_name'             => _x( 'Orderbox Orders', 'Admin Menu text', 'orderbox-order-code-follow-up' ),
            'name_admin_bar'        => _x( 'Orderbox Orders', 'Add New on Toolbar', 'orderbox-order-code-follow-up' ),
            'add_new'               => __( 'Add New', 'orderbox-order-code-follow-up' ),
            'add_new_item'          => __( 'Add New Orderbox Orders', 'orderbox-order-code-follow-up' ),
            'new_item'              => __( 'New Orderbox Orders', 'orderbox-order-code-follow-up' ),
            'edit_item'             => __( 'Edit Orderbox Orders', 'orderbox-order-code-follow-up' ),
            'view_item'             => __( 'View Orderbox Orders', 'orderbox-order-code-follow-up' ),
            'all_items'             => __( 'All Orderbox Orders', 'orderbox-order-code-follow-up' ),
            'search_items'          => __( 'Search Orderbox Orders', 'orderbox-order-code-follow-up' ),
            'parent_item_colon'     => __( 'Parent Orderbox Orders:', 'orderbox-order-code-follow-up' ),
            'not_found'             => __( 'No Orderbox Orders found.', 'orderbox-order-code-follow-up' ),
            'not_found_in_trash'    => __( 'No Orderbox Orders  found in Trash.', 'orderbox-order-code-follow-up' ),
            'featured_image'        => _x( 'Orderbox Orders Cover Image', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', 'orderbox-order-code-follow-up' ),
            'set_featured_image'    => _x( 'Set cover image', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', 'orderbox-order-code-follow-up' ),
            'remove_featured_image' => _x( 'Remove cover image', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', 'orderbox-order-code-follow-up' ),
            'use_featured_image'    => _x( 'Use as cover image', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', 'orderbox-order-code-follow-up' ),
            'archives'              => _x( 'Orderbox Orders archives', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', 'orderbox-order-code-follow-up' ),
            'insert_into_item'      => _x( 'Insert into Orderbox Orders', 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4', 'orderbox-order-code-follow-up' ),
            'uploaded_to_this_item' => _x( 'Uploaded to this Orderbox Orders', 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', 'orderbox-order-code-follow-up' ),
            'filter_items_list'     => _x( 'Filter boOrderbox Ordersoks list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', 'orderbox-order-code-follow-up' ),
            'items_list_navigation' => _x( 'Orderbox Orders list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4', 'orderbox-order-code-follow-up' ),
            'items_list'            => _x( 'Orderbox Orders list', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4', 'orderbox-order-code-follow-up' ),
        );

        $args = array(
            'labels'             => $labels,
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => true,
            'rewrite'            => array( 'slug' => 'orderbox-order' ),
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'menu_position'      => null,
            'supports'           => array( 'title'),
        );

        register_post_type( 'orderbox_order', $args );


    }

}