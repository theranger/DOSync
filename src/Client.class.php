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


use Aws\S3\S3Client;
use Exception;

define("DO_TEST_FILE", "wordpress_do_spaces_test.txt");
define("DO_TEST_TYPE", "text/plain");

class Client {

	private $client;
	private $prefix;
	private $bucket;

	public function __construct($endpoint, $key, $secret, $prefix) {
		if (empty($key) || empty($secret) || empty($endpoint)) return;

		$url = parse_url($endpoint);
		$host = explode(".", $url["host"], 2);

		$this->client = new S3Client([
			"credentials" => [
				"key" => $key,
				"secret" => $secret,
			],
			"endpoint" => $url["scheme"] . "://" . $host[1],
			"version" => "latest",
			// region means nothing for DO Spaces, but aws client may drop and error without it
			"region" => "us-east-1",
		]);

		$this->bucket = $host[0];
		$this->prefix = empty($prefix) ? "" : trim($prefix, "/ ") . "/";
	}

	public function testConnection() { // AJAX call, must die() at the end
		try {
			$this->put(DO_TEST_FILE, DO_TEST_TYPE);
			$this->delete(DO_TEST_FILE);

			show_message("Tests are successful, please save the settings");
		} catch (Exception $e) {
			show_message("Spaces test failed: " . $e->getMessage());
		}

		wp_die();
	}

	/**
	 * @param $path
	 * @param $contentType
	 * @param $contents
	 * @return string
	 * @throws Exception
	 */
	public function put($path, $contentType, &$contents = null) {
		if (!$this->client) throw new Exception("required parameters missing, client not initialized");
		if (empty($this->bucket)) throw new Exception(("could not determine bucket, aborting"));

		$data = array(
			"Key" => $this->prefix . $path,
			"Bucket" => $this->bucket,
			"ContentType" => $contentType,
			"ACL" => "public-read"
		);

		$this->client->putObject(empty($contents) ? $data : $data + ["Body" => $contents]);
		unset($contents);
	}

	/**
	 * @param $path
	 * @throws Exception
	 */
	public function delete($path) {
		if (!$this->client) throw new Exception("required parameters missing, client not initialized");
		if (empty($this->bucket)) throw new Exception(("could not determine bucket, aborting"));

		$this->client->deleteObject(array("Key" => $this->prefix . $path, "Bucket" => $this->bucket));
	}

	/**
	 * @param $path
	 * @throws Exception
	 */
	public function acl($path) {
		if (!$this->client) throw new Exception("required parameters missing, client not initialized");
		if (empty($this->bucket)) throw new Exception(("could not determine bucket, aborting"));

		$this->client->getObjectAcl(array("Key" => $this->prefix . $path, "Bucket" => $this->bucket));
	}
}
