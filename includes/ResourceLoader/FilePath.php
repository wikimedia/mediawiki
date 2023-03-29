<?php
/**
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

namespace MediaWiki\ResourceLoader;

use RuntimeException;

/**
 * A path to a bundled file (such as JavaScript or CSS), along with a remote and local base path.
 *
 * This is for use with FileModule. Base path may be `null`, which indicates that the
 * path is expanded relative to the corresponding base path of the FileModule object instead.
 *
 * @ingroup ResourceLoader
 * @since 1.17
 */
class FilePath {
	/** @var string|null Local base path */
	protected ?string $localBasePath;

	/** @var string|null Remote base path */
	protected ?string $remoteBasePath;

	/** @var string Path to the file */
	protected string $path;

	/**
	 * @param string $path Relative path to the file, no leading slash.
	 * @param string|null $localBasePath Base path to prepend when generating a local path.
	 * @param string|null $remoteBasePath Base path to prepend when generating a remote path.
	 *   Should not have a trailing slash unless at web document root.
	 */
	public function __construct( string $path, ?string $localBasePath = null, ?string $remoteBasePath = null ) {
		$this->path = $path;
		$this->localBasePath = $localBasePath;
		$this->remoteBasePath = $remoteBasePath;
	}

	/**
	 * @return string
	 * @throws RuntimeException If the base path was not provided. You must either provide the base
	 *   path in the constructor, or use getPath() instead and add the base path from a FileModule.
	 */
	public function getLocalPath(): string {
		if ( $this->localBasePath === null ) {
			throw new RuntimeException( 'Base path was not provided' );
		}
		return "{$this->localBasePath}/{$this->path}";
	}

	/**
	 * @return string
	 * @throws RuntimeException If the base path was not provided. You must either provide the base
	 *   path in the constructor, or use getPath() instead and add the base path from a FileModule.
	 */
	public function getRemotePath(): string {
		if ( $this->remoteBasePath === null ) {
			throw new RuntimeException( 'Base path was not provided' );
		}
		if ( $this->remoteBasePath === '/' ) {
			// In document root
			// Don't insert another slash (T284391).
			return $this->remoteBasePath . $this->path;
		}
		return "{$this->remoteBasePath}/{$this->path}";
	}

	/** @return string|null */
	public function getLocalBasePath(): ?string {
		return $this->localBasePath;
	}

	/** @return string|null */
	public function getRemoteBasePath(): ?string {
		return $this->remoteBasePath;
	}

	/** @return string */
	public function getPath(): string {
		return $this->path;
	}

	/**
	 * Set the base path if it has not already been set.
	 *
	 * @param string $localBasePath
	 * @param string $remoteBasePath
	 */
	public function initBasePaths( string $localBasePath, string $remoteBasePath ) {
		if ( $this->localBasePath === null ) {
			$this->localBasePath = $localBasePath;
		}
		if ( $this->remoteBasePath === null ) {
			$this->remoteBasePath = $remoteBasePath;
		}
	}
}

/** @deprecated since 1.39 */
class_alias( FilePath::class, 'ResourceLoaderFilePath' );
