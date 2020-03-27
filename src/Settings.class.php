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


class Settings {
	public static $key;
	public static $secret;
	public static $endpoint;
	public static $prefix;

	public static function init() {
		self::$key = empty($key) ? get_option('dos_key') : $key;
		self::$secret = empty($secret) ? get_option('dos_secret') : $secret;
		self::$endpoint = empty($endpoint) ? get_option('dos_endpoint') : $endpoint;
		self::$prefix = empty($prefix) ? get_option('dos_prefix') : $prefix;
	}
}

Settings::init();
