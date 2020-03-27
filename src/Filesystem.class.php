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

use Exception;

class Filesystem {

	/**
	 * @var Client
	 */
	private $client;
	private $root;

	/**
	 * Filesystem constructor.
	 * @param $root array
	 * @param $client Client
	 * @throws Exception
	 */
	public function __construct($root, $client) {
		$this->client = $client;

		if (empty($root["basedir"])) throw new Exception("could not locate upload root directory");
		$this->root = rtrim($root["basedir"], DIRECTORY_SEPARATOR . " ");
	}

	/**
	 * @param $file
	 * @return string
	 * @throws Exception
	 */
	public function upload($file) {
		$path = $this->root . DIRECTORY_SEPARATOR . $file;
		$contentType = wp_check_filetype($path);

		$this->client->put($file, $contentType["type"], file_get_contents($path));
		wp_delete_file($path);
	}

	/**
	 * @param $file
	 * @throws Exception
	 */
	public function delete($file) {
		$this->client->delete($file);
	}

}
