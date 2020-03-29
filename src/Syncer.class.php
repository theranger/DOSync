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

define("KILO", 1024);
define("MEGA", 1024 * KILO);
define("GIGA", 1024 * MEGA);
define("TERA", 1024 * GIGA);

class Syncer {

	/**
	 * @var Filesystem
	 */
	private $filesystem;
	private $fileCount;
	private $totalSize;

	public function __construct($filesystem) {
		$this->filesystem = $filesystem;
	}

	public function handleSync() { // AJAX call, must die() at the end
		try {
			$this->countFiles();
			if ($this->fileCount == 0) {
				self::log("<b>Found no files to sync</b>", 3);
				wp_die();
			}

			self::log("<b>Found $this->fileCount files with total size " . $this->getSize() . "</b>", 3);
			$this->syncFiles();
		} catch (Exception $e) {
			self::log($e, 5);
		}

		wp_die();
	}

	/**
	 * @param $path
	 * @throws Exception
	 */
	private function countFiles($path = "") {
		$path = trim($path, DIRECTORY_SEPARATOR);

		if (!$this->filesystem->isDirectory($path)) {
			$this->fileCount++;
			$this->totalSize += $this->filesystem->getSize($path);
			return;
		}

		self::log("Scanning " . $path);
		$this->filesystem->parseDirectory($path, function ($dir, $f) {
			$this->countFiles($dir . DIRECTORY_SEPARATOR . $f);
		});
	}

	private static function log($message, $sleep_sec = 0) {
		print "<p>$message</p>";
		ob_flush();
		flush();
		sleep($sleep_sec);
	}

	private function getSize() {
		switch (true) {
			case $this->totalSize > TERA:
				return round($this->totalSize / TERA) . " TB";

			case $this->totalSize > GIGA:
				return round($this->totalSize / GIGA) . " GB";

			case $this->totalSize > MEGA:
				return round($this->totalSize / MEGA) . " MB";

			case $this->totalSize > KILO:
				return round($this->totalSize / KILO) . " KB";

			default:
				return $this->totalSize . " B";

		}
	}

	/**
	 * @param $path
	 * @throws Exception
	 */
	private function syncFiles($path = "") {
		$path = trim($path, DIRECTORY_SEPARATOR);

		if (!$this->filesystem->isDirectory($path)) {
			$size = $this->filesystem->getSize($path);
			$this->filesystem->upload($path);

			$this->fileCount--;
			$this->totalSize -= $size;
			self::log("Synced " . $path . ", left $this->fileCount files, " . $this->getSize());
			return;
		}

		$this->filesystem->parseDirectory($path, function ($dir, $f) {
			$this->syncFiles($dir . DIRECTORY_SEPARATOR . $f);
		});
	}
}
