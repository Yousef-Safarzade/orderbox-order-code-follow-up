<?php

namespace OrderboxOrderCodeFollowUp;


class constants
{

    public static function define_constants($parent_path)
    {

        define('WP_OOFU_PLUGIN_VERSION', '1.2.2');

        define('WP_OOFU_PLUGIN_FOLDER_PATH', plugin_dir_path($parent_path));

        define('WP_OOFU_PLUGIN_FOLDER_URL', plugin_dir_url($parent_path));

        define('WP_OOFU_PLUGIN_ASSETS_FOLDER_URL', WP_OOFU_PLUGIN_FOLDER_URL . "assets");

        define('WP_OOFU_PLUGIN_CSS_FOLDER_URL', WP_OOFU_PLUGIN_ASSETS_FOLDER_URL . "/css/");

        define('WP_OOFU_PLUGIN_JS_FOLDER_URL', WP_OOFU_PLUGIN_ASSETS_FOLDER_URL . "/js/");

        define('WP_OOFU_PLUGIN_MEDIA_FOLDER_URL', WP_OOFU_PLUGIN_ASSETS_FOLDER_URL . "/media/");

        define('WP_OOFU_ACTIVE_THEME_DIRECTORY_PATH',get_template_directory());

    }


}

