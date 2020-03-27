# About
This plugin captures all file uploads (images and attachments) and stores files in DigitalOcean Spaces container. Files are deleted from Wordpress upload directory to save disk space. File locations are changed accordingly to point to DigitalOcean Spaces container.
When deleting files from media library, they will be removed from Spaces bucket as well.

# Installation

1. Upload the plugin files to the `/wp-content/plugins/dosync` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the `Plugins` screen in WordPress.
3. Use the `Settings -> DOSync` page to configure the plugin (Digital Ocean API key required).

# Credits
* @bitkidd - Original version of this plugin (https://github.com/bitkidd/DigitalOcean-Spaces-Sync)
