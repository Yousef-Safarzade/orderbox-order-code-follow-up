<?php

namespace OrderboxOrderCodeFollowUp;

class plugin_activation_hooks
{

    public static function define_hooks($parent_plugin){

        register_activation_hook( $parent_plugin, function(){

            custom_post_types::register_orderbox_orders_post_types();

            flush_rewrite_rules();

        } );

    }

}