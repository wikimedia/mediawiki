<?php
/**
 * File without associated database record
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
 * TODO: Currently it doesn't really work in the repository role, there are
 * lots of functions missing. It is used by the WebStore extension in the
 * standalone role.
 *
 * @ingroup FileAbstraction
 */
class UnregisteredLocalFile extends File {
	var $title, $path, $mime, $dims;

	/**
	 * @var MediaHandler
	 */
	var $handler;

	/**
	 * @param $path string Storage path
	 * @param $mime string
	 * @return UnregisteredLocalFile
	 */
	static function newFromPath( $path, $mime ) {
		return new self( false, false, $path, $mime );
	}

	/**
	 * @param $title
	 * @param $repo
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
	 * @param $title Title|false
	 * @param $repo FileRepo
	 * @param $path string
	 * @param $mime string
	 */
	function __construct( $title = false, $repo = false, $path = false, $mime = false ) {
		if ( !( $title && $repo ) && !$path ) {
			throw new MWException( __METHOD__.': not enough parameters, must specify title and repo, or a full path' );
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
		$this->dims = array();
	}

	private function cachePageDimensions( $page = 1 ) {
		if ( !isset( $this->dims[$page] ) ) {
			if ( !$this->getHandler() ) {
				return false;
			}
			$this->dims[$page] = $this->handler->getPageDimensions( $this, $page );
		}
		return $this->dims[$page];
	}

	function getWidth( $page = 1 ) {
		$dim = $this->cachePageDimensions( $page );
		return $dim['width'];
	}

	function getHeight( $page = 1 ) {
		$dim = $this->cachePageDimensions( $page );
		return $dim['height'];
	}

	function getMimeType() {
		if ( !isset( $this->mime ) ) {
			$magic = MimeMagic::singleton();
			$this->mime = $magic->guessMimeType( $this->getLocalRefPath() );
		}
		return $this->mime;
	}

	function getImageSize( $filename ) {
		if ( !$this->getHandler() ) {
			return false;
		}
		return $this->handler->getImageSize( $this, $this->getLocalRefPath() );
	}

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

	function getURL() {
		if ( $this->repo ) {
			return $this->repo->getZoneUrl( 'public' ) . '/' .
				$this->repo->getHashPath( $this->name ) . rawurlencode( $this->name );
		} else {
			return false;
		}
	}

	function getSize() {
		$this->assertRepoDefined();
		$props = $this->repo->getFileProps( $this->path );
		if ( isset( $props['size'] ) ) {
			return $props['size'];
		}
		return false; // doesn't exist
	}
}
