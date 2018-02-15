<?php
/**
 * File without associated database record.
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
 * @ingroup FileAbstraction
 */

/**
 * A file object referring to either a standalone local file, or a file in a
 * local repository with no database, for example an FileRepo repository.
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

	/** @var bool|string */
	protected $mime;

	/** @var array Dimension data */
	protected $dims;

	/** @var bool|string Handler-specific metadata which will be saved in the img_metadata field */
	protected $metadata;

	/** @var MediaHandler */
	public $handler;

	/**
	 * @param string $path Storage path
	 * @param string $mime
	 * @return UnregisteredLocalFile
	 */
	static function newFromPath( $path, $mime ) {
		return new self( false, false, $path, $mime );
	}

	/**
	 * @param Title $title
	 * @param FileRepo $repo
	 * @return UnregisteredLocalFile
	 */
	static function newFromTitle( $title, $repo ) {
		return new self( $title, $repo, false, false );
	}

	/**
	 * Create an UnregisteredLocalFile based on a path or a (title,repo) pair.
	 * A FileRepo object is not required here, unlike most other File classes.
	 *
	 * @throws MWException
	 * @param Title|bool $title
	 * @param FileRepo|bool $repo
	 * @param string|bool $path
	 * @param string|bool $mime
	 */
	function __construct( $title = false, $repo = false, $path = false, $mime = false ) {
		if ( !( $title && $repo ) && !$path ) {
			throw new MWException( __METHOD__ .
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
		$this->dims = [];
	}

	/**
	 * @param int $page
	 * @return bool
	 */
	private function cachePageDimensions( $page = 1 ) {
		$page = (int)$page;
		if ( $page < 1 ) {
			$page = 1;
		}

		if ( !isset( $this->dims[$page] ) ) {
			if ( !$this->getHandler() ) {
				return false;
			}
			$this->dims[$page] = $this->handler->getPageDimensions( $this, $page );
		}

		return $this->dims[$page];
	}

	/**
	 * @param int $page
	 * @return int
	 */
	function getWidth( $page = 1 ) {
		$dim = $this->cachePageDimensions( $page );

		return $dim['width'];
	}

	/**
	 * @param int $page
	 * @return int
	 */
	function getHeight( $page = 1 ) {
		$dim = $this->cachePageDimensions( $page );

		return $dim['height'];
	}

	/**
	 * @return bool|string
	 */
	function getMimeType() {
		if ( !isset( $this->mime ) ) {
			$magic = MediaWiki\MediaWikiServices::getInstance()->getMimeAnalyzer();
			$this->mime = $magic->guessMimeType( $this->getLocalRefPath() );
		}

		return $this->mime;
	}

	/**
	 * @param string $filename
	 * @return array|bool
	 */
	function getImageSize( $filename ) {
		if ( !$this->getHandler() ) {
			return false;
		}

		return $this->handler->getImageSize( $this, $this->getLocalRefPath() );
	}

	/**
	 * @return int
	 */
	function getBitDepth() {
		$gis = $this->getImageSize( $this->getLocalRefPath() );

		if ( !$gis || !isset( $gis['bits'] ) ) {
			return 0;
		}
		return $gis['bits'];
	}

	/**
	 * @return bool
	 */
	function getMetadata() {
		if ( !isset( $this->metadata ) ) {
			if ( !$this->getHandler() ) {
				$this->metadata = false;
			} else {
				$this->metadata = $this->handler->getMetadata( $this, $this->getLocalRefPath() );
			}
		}

		return $this->metadata;
	}

	/**
	 * @return bool|string
	 */
	function getURL() {
		if ( $this->repo ) {
			return $this->repo->getZoneUrl( 'public' ) . '/' .
				$this->repo->getHashPath( $this->name ) . rawurlencode( $this->name );
		} else {
			return false;
		}
	}

	/**
	 * @return bool|int
	 */
	function getSize() {
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
