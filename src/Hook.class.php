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

namespace DOSync;

class Hook {

	public static function scripts() {
		wp_enqueue_script("dos-core-js", plugin_dir_url(__FILE__) . "/assets/scripts/core.js", array("jquery"), "1.4.0", true);
	}

	public function settings() {
		register_setting("dos_settings", "dos_key");
		register_setting("dos_settings", "dos_secret");
		register_setting("dos_settings", "dos_endpoint");
		register_setting("dos_settings", "dos_prefix");
		new Settings();
	}

	public function settingsPage() {
		include_once("pages/settings.php");
	}

	public function menu() {
		add_options_page(
			"DigitalOcean Spaces Sync",
			"DOSync",
			"manage_options",
			"settings.php",
			array($this, "settingsPage")
		);
	}

	public function attachmentUrl($path) {
		if (empty(Settings::$endpoint)) return $path;

		$path["baseurl"] = Settings::$endpoint . "/" . Settings::$prefix;
		return $path;
	}
}
