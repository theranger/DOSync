<?php
/**
 * Copyright 2020 The Ranger
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

/**
 * Plugin Name: DOSync
 * Plugin URI: https://github.com/theranger/DOSync
 * Description: Plugin to synchronize Wordpress media library to DigitalOcean Spaces container.
 * Version: 1.1.0
 * Author: theranger
 * Author URI: https://github.com/theranger
 * License: Apache 2.0
 * Text Domain: dos
 * Domain Path: /src/languages
 */

spl_autoload_register(function ($class_name) {
	$parts = explode('\\', $class_name);
	include "src" . DIRECTORY_SEPARATOR . end($parts) . ".class.php";
});

require_once("vendor/autoload.php");

use DOSync\Attachment;
use DOSync\Client;
use DOSync\Filesystem;
use DOSync\Hook;
use DOSync\Settings;
use DOSync\Syncer;

try {
	$hook = new Hook();
	$client = new Client(Settings::$endpoint, Settings::$key, Settings::$secret, Settings::$prefix);
	$filesystem = new Filesystem(wp_get_upload_dir(), $client);
	$attachment = new Attachment($filesystem);
	$syncer = new Syncer($filesystem);
} catch (Exception $e) {
	show_message("Upload failed: " . $e->getMessage());
}

add_action("admin_menu", array($hook, "menu"));
add_action("admin_init", array($hook, "settings"));
add_action("admin_enqueue_scripts", array($hook, "scripts"));

add_action("wp_ajax_dos_test_connection", array($client, "testConnection"));
add_action("wp_ajax_dos_sync", array($syncer, "handleSync"));

add_action("add_attachment", array($attachment, "addAttachment"), 10, 1);
add_action("delete_attachment", array($attachment, "deleteAttachment"), 10, 1);

add_filter("wp_generate_attachment_metadata", array($attachment, "handleMetadata"), 20, 2);
// add_filter("wp_save_image_editor_file", array($this,"filter_wp_save_image_editor_file"), 10, 5 );
add_filter("wp_unique_filename", array($attachment, "handleFilename"));
add_filter("upload_dir", array($hook, "attachmentUrl"));
