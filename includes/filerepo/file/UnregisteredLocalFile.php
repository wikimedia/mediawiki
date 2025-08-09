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

namespace MediaWiki\FileRepo\File;

use BadMethodCallException;
use MediaHandler;
use MediaWiki\FileRepo\FileRepo;
use MediaWiki\MediaWikiServices;
use MediaWiki\Title\Title;
use Wikimedia\FileBackend\FSFile\FSFile;

/**
 * File without associated database record.
 *
 * Represents a standalone local file, or a file in a local repository
 * with no database, for example a FileRepo repository.
 *
 * Read-only.
 *
 * @todo Currently it doesn't really work in the repository role, there are
 * lots of functions missing. It is used by the WebStore extension in the
 * standalone role.
 *
 * @ingroup FileAbstraction
 */
class UnregisteredLocalFile extends File {
	/** @var Title */
	protected $title;

	/** @var string */
	protected $path;

	/** @var string|false|null */
	protected $mime;

	/** @var array[]|bool[] Dimension data */
	protected $pageDims;

	/** @var array|null */
	protected $sizeAndMetadata;

	/** @var MediaHandler */
	public $handler;

	/**
	 * @param string $path Storage path
	 * @param string $mime
	 */
	public static function newFromPath( $path, $mime ): static {
		return new static( false, false, $path, $mime );
	}

	/**
	 * @param Title $title
	 * @param FileRepo $repo
	 */
	public static function newFromTitle( $title, $repo ): static {
		return new static( $title, $repo, false, false );
	}

	/**
	 * Create an UnregisteredLocalFile based on a path or a (title,repo) pair.
	 * A FileRepo object is not required here, unlike most other File classes.
	 *
	 * @param Title|false $title
	 * @param FileRepo|false $repo
	 * @param string|false $path
	 * @param string|false $mime
	 */
	public function __construct( $title = false, $repo = false, $path = false, $mime = false ) {
		if ( !( $title && $repo ) && !$path ) {
			throw new BadMethodCallException( __METHOD__ .
				': not enough parameters, must specify title and repo, or a full path' );
		}
		if ( $title instanceof Title ) {
			$this->title = File::normalizeTitle( $title, 'exception' );
			$this->name = $repo->getNameFromTitle( $title );
		} else {
			$this->name = basename( $path );
			$this->title = File::normalizeTitle( $this->name, 'exception' );
		}
		$this->repo = $repo;
		if ( $path ) {
			$this->path = $path;
		} else {
			$this->assertRepoDefined();
			$this->path = $repo->getRootDirectory() . '/' .
				$repo->getHashPath( $this->name ) . $this->name;
		}
		if ( $mime ) {
			$this->mime = $mime;
		}
		$this->pageDims = [];
	}

	/**
	 * @param int $page
	 * @return array|false
	 */
	private function cachePageDimensions( $page = 1 ) {
		$page = (int)$page;
		if ( $page < 1 ) {
			$page = 1;
		}

		if ( !isset( $this->pageDims[$page] ) ) {
			if ( !$this->getHandler() ) {
				return false;
			}
			if ( $this->getHandler()->isMultiPage( $this ) ) {
				$this->pageDims[$page] = $this->handler->getPageDimensions( $this, $page );
			} else {
				$info = $this->getSizeAndMetadata();
				return [
					'width' => $info['width'],
					'height' => $info['height']
				];
			}
		}

		return $this->pageDims[$page];
	}

	/**
	 * @param int $page
	 * @return int
	 */
	public function getWidth( $page = 1 ) {
		$dim = $this->cachePageDimensions( $page );

		return $dim['width'] ?? 0;
	}

	/**
	 * @param int $page
	 * @return int
	 */
	public function getHeight( $page = 1 ) {
		$dim = $this->cachePageDimensions( $page );

		return $dim['height'] ?? 0;
	}

	/**
	 * @return string|false
	 */
	public function getMimeType() {
		if ( $this->mime === null ) {
			$refPath = $this->getLocalRefPath();
			if ( $refPath !== false ) {
				$magic = MediaWikiServices::getInstance()->getMimeAnalyzer();
				$this->mime = $magic->guessMimeType( $refPath );
			} else {
				$this->mime = false;
			}
		}

		return $this->mime;
	}

	/**
	 * @return int
	 */
	public function getBitDepth() {
		$info = $this->getSizeAndMetadata();
		return $info['bits'] ?? 0;
	}

	/**
	 * @return string|false
	 */
	public function getMetadata() {
		$info = $this->getSizeAndMetadata();
		return $info['metadata'] ? serialize( $info['metadata'] ) : false;
	}

	public function getMetadataArray(): array {
		$info = $this->getSizeAndMetadata();
		return $info['metadata'];
	}

	private function getSizeAndMetadata(): array {
		if ( $this->sizeAndMetadata === null ) {
			if ( !$this->getHandler() ) {
				$this->sizeAndMetadata = [ 'width' => 0, 'height' => 0, 'metadata' => [] ];
			} else {
				$this->sizeAndMetadata = $this->getHandler()->getSizeAndMetadataWithFallback(
					$this, $this->getLocalRefPath() );
			}
		}

		return $this->sizeAndMetadata;
	}

	/**
	 * @return string|false
	 */
	public function getURL() {
		if ( $this->repo ) {
			return $this->repo->getZoneUrl( 'public' ) . '/' .
				$this->repo->getHashPath( $this->name ) . rawurlencode( $this->name );
		} else {
			return false;
		}
	}

	/**
	 * @return false|int
	 */
	public function getSize() {
		$this->assertRepoDefined();

		return $this->repo->getFileSize( $this->path );
	}

	/**
	 * Optimize getLocalRefPath() by using an existing local reference.
	 * The file at the path of $fsFile should not be deleted (or at least
	 * not until the end of the request). This is mostly a performance hack.
	 *
	 * @param FSFile $fsFile
	 * @return void
	 */
	public function setLocalReference( FSFile $fsFile ) {
		$this->fsFile = $fsFile;
	}
}

/** @deprecated class alias since 1.44 */
class_alias( UnregisteredLocalFile::class, 'UnregisteredLocalFile' );
