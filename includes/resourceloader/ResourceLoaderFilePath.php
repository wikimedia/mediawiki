<?php
/**
 * An object to represent a path to a JavaScript/CSS file, along with a remote
 * and local base path, for use with ResourceLoaderFileModule.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 */

/**
 * An object to represent a path to a JavaScript/CSS file, along with a remote
 * and local base path, for use with ResourceLoaderFileModule.
 */
class ResourceLoaderFilePath {
	/* Protected Members */

	/** @var string Local base path */
	protected $localBasePath;

	/** @var string Remote base path */
	protected $remoteBasePath;

	/**
	 * @var string Path to the file */
	protected $path;

	/* Methods */

	/**
	 * @param string $path Path to the file.
	 * @param string $localBasePath Base path to prepend when generating a local path.
	 * @param string $remoteBasePath Base path to prepend when generating a remote path.
	 */
	public function __construct( $path, $localBasePath, $remoteBasePath ) {
		$this->path = $path;
		$this->localBasePath = $localBasePath;
		$this->remoteBasePath = $remoteBasePath;
	}

	/**
	 * @return string
	 */
	public function getLocalPath() {
		return "{$this->localBasePath}/{$this->path}";
	}

	/**
	 * @return string
	 */
	public function getRemotePath() {
		return "{$this->remoteBasePath}/{$this->path}";
	}

	/**
	 * @return string
	 */
	public function getPath() {
		return $this->path;
	}
}
