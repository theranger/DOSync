=== Plugin Name ===
Contributors: theranger
Tags: digitalocean, spaces, cloud, storage, object, s3
Requires at least: 4.6
Tested up to: 5.3.2
Stable tag: 1.1.0
License: Apache 2.0
License URI: https://www.apache.org/licenses/LICENSE-2.0

Plugin to synchronize Wordpress media library with a DigitalOcean Spaces container.

== Description ==

This plugin captures all file uploads (images and attachments) and stores files in DigitalOcean Spaces container. Files are deleted from Wordpress upload directory to save disk space. File locations are changed accordingly to point to DigitalOcean Spaces container.
When deleting files from media library, they will be removed from Spaces bucket as well.

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/dosync` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the `Plugins` screen in WordPress.
3. Use the `Settings -> DOSync` page to configure the plugin (Digital Ocean API key required).

== Screenshots ==

== Changelog ==

= 1.1.0 =
* Added preliminary support to upload existing files to Spaces
* Added duplicate file name detection

= 1.0.0 =
* Initial release
