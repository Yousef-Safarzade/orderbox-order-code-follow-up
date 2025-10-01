<?php

namespace OrderboxOrderCodeFollowUp;

class plugin_deactivation_hooks
{

    public static function define_hooks($parent_plugin){

        register_deactivation_hook( $parent_plugin, function(){

            unregister_post_type( 'orderbox_order' );

            flush_rewrite_rules();

        } );

    }

}