<?php

/**
 * Prioritized list of file repositories
 * @addtogroup filerepo
 */
class RepoGroup {
	var $localRepo, $foreignRepos, $reposInitialised = false;
	var $localInfo, $foreignInfo;

	protected static $instance;

	/**
	 * Get a RepoGroup instance. At present only one instance of RepoGroup is
	 * needed in a MediaWiki invocation, this may change in the future.
	 */
	static function singleton() {
		if ( self::$instance ) {
			return self::$instance;
		}
		global $wgLocalFileRepo, $wgForeignFileRepos;
		self::$instance = new RepoGroup( $wgLocalFileRepo, $wgForeignFileRepos );
		return self::$instance;
	}

	/**
	 * Destroy the singleton instance, so that a new one will be created next
	 * time singleton() is called.
	 */
	static function destroySingleton() {
		self::$instance = null;
	}

	/**
	 * Construct a group of file repositories. 
	 * @param array $data Array of repository info arrays. 
	 *     Each info array is an associative array with the 'class' member 
	 *     giving the class name. The entire array is passed to the repository
	 *     constructor as the first parameter.
	 */
	function __construct( $localInfo, $foreignInfo ) {
		$this->localInfo = $localInfo;
		$this->foreignInfo = $foreignInfo;
	}

	/**
	 * Search repositories for an image.
	 * You can also use wfGetFile() to do this.
	 * @param mixed $title Title object or string
	 * @param mixed $time The 14-char timestamp before which the file should 
	 *                    have been uploaded, or false for the current version
	 * @return File object or false if it is not found
	 */
	function findFile( $title, $time = false ) {
		if ( !$this->reposInitialised ) {
			$this->initialiseRepos();
		}

		$image = $this->localRepo->findFile( $title, $time );
		if ( $image ) {
			return $image;
		}
		foreach ( $this->foreignRepos as $repo ) {
			$image = $repo->findFile( $title, $time );
			if ( $image ) {
				return $image;
			}
		}
		return false;
	}

	/**
	 * Get the repo instance with a given key.
	 */
	function getRepo( $index ) {
		if ( !$this->reposInitialised ) {
			$this->initialiseRepos();
		}
		if ( $index == 'local' ) {
			return $this->localRepo;
		} elseif ( isset( $this->foreignRepos[$index] ) ) {
			return $this->foreignRepos[$index];
		} else {
			return false;
		}
	}

	/**
	 * Get the local repository, i.e. the one corresponding to the local image
	 * table. Files are typically uploaded to the local repository.
	 */
	function getLocalRepo() {
		return $this->getRepo( 'local' );
	}

	/**
	 * Initialise the $repos array
	 */
	function initialiseRepos() {
		if ( $this->reposInitialised ) {
			return;
		}
		$this->reposInitialised = true;

		$this->localRepo = $this->newRepo( $this->localInfo );
		$this->foreignRepos = array();
		foreach ( $this->foreignInfo as $key => $info ) {
			$this->foreignRepos[$key] = $this->newRepo( $info );
		}
	}

	/**
	 * Create a repo class based on an info structure
	 */
	protected function newRepo( $info ) {
		$class = $info['class'];
		return new $class( $info );
	}

	/**
	 * Split a virtual URL into repo, zone and rel parts
	 * @return an array containing repo, zone and rel
	 */
	function splitVirtualUrl( $url ) {
		if ( substr( $url, 0, 9 ) != 'mwrepo://' ) {
			throw new MWException( __METHOD__.': unknown protoocl' );
		}

		$bits = explode( '/', substr( $url, 9 ), 3 );
		if ( count( $bits ) != 3 ) {
			throw new MWException( __METHOD__.": invalid mwrepo URL: $url" );
		}
		return $bits;
	}

	function getFileProps( $fileName ) {
		if ( FileRepo::isVirtualUrl( $fileName ) ) {
			list( $repoName, /* $zone */, /* $rel */ ) = $this->splitVirtualUrl( $fileName );
			if ( $repoName === '' ) {
				$repoName = 'local';
			}
			$repo = $this->getRepo( $repoName );
			return $repo->getFileProps( $fileName );
		} else {
			return File::getPropsFromPath( $fileName );
		}
	}
}


