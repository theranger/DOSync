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

class Attachment {

	/**
	 * @var Filesystem
	 */
	private $filesystem;

	public function __construct($filesystem) {
		$this->filesystem = $filesystem;
	}

	/**
	 * @param $metadata
	 * @return mixed
	 * @throws Exception
	 */
	public function handleMetadata($metadata) {
		if (empty($metadata["file"])) return $metadata;

		$base = dirname($metadata["file"]);
		$this->filesystem->upload($metadata["file"]);

		foreach ($metadata["sizes"] as $size) {
			$this->filesystem->upload($base . DIRECTORY_SEPARATOR . $size["file"]);
		}

		return $metadata;
	}

	/**
	 * @param $postID
	 * @throws Exception
	 */
	public function addAttachment($postID) {
		if (wp_attachment_is_image($postID)) return;

		$this->filesystem->upload(get_attached_file($postID));
	}

	/**
	 * @param $postID
	 * @throws Exception
	 */
	public function deleteAttachment($postID) {
		if (!wp_attachment_is_image($postID)) {
			$this->filesystem->delete(get_attached_file($postID));
			return;
		}

		$metadata = wp_get_attachment_metadata($postID);
		if (empty($metadata["file"])) return;

		$base = dirname($metadata["file"]);
		$this->filesystem->delete($metadata["file"]);

		foreach ($metadata["sizes"] as $size) {
			$this->filesystem->delete($base . DIRECTORY_SEPARATOR . $size["file"]);
		}
	}

	/**
	 * @param $filename
	 * @param null $dir
	 * @return mixed
	 * @throws Exception
	 */
	public function handleFilename($filename, $dir = null) {
		if ($dir == null) {
			$dir = trim(wp_get_upload_dir()["subdir"], "/");
		}

		if (!$this->filesystem->exists($dir . DIRECTORY_SEPARATOR . $filename)) return $filename;

		$ret = preg_replace_callback('/-(\d+)\.(\w+)$/', function ($m) {
			return "-" . ($m[1] + 1) . "." . $m[2];
		}, $filename);
		if ($ret != $filename) return $this->handleFilename($ret, $dir);

		$ret = preg_replace('/\.(\w+)$/', '-1.$1', $filename);
		if ($ret != $filename) return $this->handleFilename($ret, $dir);

		throw new Exception("could not compute unique file name");
	}
}

