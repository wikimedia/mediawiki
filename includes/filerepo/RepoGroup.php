<?php
/**
 * Prioritized list of file repositories
 *
 * @file
 * @ingroup FileRepo
 */

/**
 * @defgroup FileRepo FileRepo
 */

/**
 * Prioritized list of file repositories
 *
 * @ingroup FileRepo
 */
class RepoGroup {
	var $localRepo, $foreignRepos, $reposInitialised = false;
	var $localInfo, $foreignInfo;
	var $cache;

	protected static $instance;
	const MAX_CACHE_SIZE = 1000;

	/**
	 * Get a RepoGroup instance. At present only one instance of RepoGroup is
	 * needed in a MediaWiki invocation, this may change in the future.
	 * @return RepoGroup
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
	 * Set the singleton instance to a given object
	 */
	static function setSingleton( $instance ) {
		self::$instance = $instance;
	}

	/**
	 * Construct a group of file repositories.
	 *
	 * @param $localInfo Associative array for local repo's info
	 * @param $foreignInfo Array of repository info arrays.
	 *     Each info array is an associative array with the 'class' member
	 *     giving the class name. The entire array is passed to the repository
	 *     constructor as the first parameter.
	 */
	function __construct( $localInfo, $foreignInfo ) {
		$this->localInfo = $localInfo;
		$this->foreignInfo = $foreignInfo;
		$this->cache = array();
	}

	/**
	 * Search repositories for an image.
	 * You can also use wfFindFile() to do this.
	 *
	 * @param $title Mixed: Title object or string
	 * @param $options Associative array of options:
	 *     time:           requested time for an archived image, or false for the
	 *                     current version. An image object will be returned which was
	 *                     created at the specified time.
	 *
	 *     ignoreRedirect: If true, do not follow file redirects
	 *
	 *     private:        If true, return restricted (deleted) files if the current
	 *                     user is allowed to view them. Otherwise, such files will not
	 *                     be found.
	 *
	 *     bypassCache:    If true, do not use the process-local cache of File objects
	 * @return File object or false if it is not found
	 */
	function findFile( $title, $options = array() ) {
		if ( !is_array( $options ) ) {
			// MW 1.15 compat
			$options = array( 'time' => $options );
		}
		if ( !$this->reposInitialised ) {
			$this->initialiseRepos();
		}
		if ( !($title instanceof Title) ) {
			$title = Title::makeTitleSafe( NS_FILE, $title );
			if ( !is_object( $title ) ) {
				return false;
			}
		}

		if ( $title->getNamespace() != NS_MEDIA && $title->getNamespace() != NS_FILE ) {
			throw new MWException( __METHOD__ . ' received an Title object with incorrect namespace' );
		}

		# Check the cache
		if ( empty( $options['ignoreRedirect'] )
			&& empty( $options['private'] )
			&& empty( $options['bypassCache'] )
			&& $title->getNamespace() == NS_FILE )
		{
			$useCache = true;
			$time = isset( $options['time'] ) ? $options['time'] : '';
			$dbkey = $title->getDBkey();
			if ( isset( $this->cache[$dbkey][$time] ) ) {
				wfDebug( __METHOD__.": got File:$dbkey from process cache\n" );
				# Move it to the end of the list so that we can delete the LRU entry later
				$tmp = $this->cache[$dbkey];
				unset( $this->cache[$dbkey] );
				$this->cache[$dbkey] = $tmp;
				# Return the entry
				return $this->cache[$dbkey][$time];
			} else {
				# Add a negative cache entry, may be overridden
				$this->trimCache();
				$this->cache[$dbkey][$time] = false;
				$cacheEntry =& $this->cache[$dbkey][$time];
			}
		} else {
			$useCache = false;
		}

		# Check the local repo
		$image = $this->localRepo->findFile( $title, $options );
		if ( $image ) {
			if ( $useCache ) {
				$cacheEntry = $image;
			}
			return $image;
		}

		# Check the foreign repos
		foreach ( $this->foreignRepos as $repo ) {
			$image = $repo->findFile( $title, $options );
			if ( $image ) {
				if ( $useCache ) {
					$cacheEntry = $image;
				}
				return $image;
			}
		}
		# Not found, do not override negative cache
		return false;
	}

	function findFiles( $inputItems ) {
		if ( !$this->reposInitialised ) {
			$this->initialiseRepos();
		}

		$items = array();
		foreach ( $inputItems as $item ) {
			if ( !is_array( $item ) ) {
				$item = array( 'title' => $item );
			}
			if ( !( $item['title'] instanceof Title ) )
				$item['title'] = Title::makeTitleSafe( NS_FILE, $item['title'] );
			if ( $item['title'] )
				$items[$item['title']->getDBkey()] = $item;
		}

		$images = $this->localRepo->findFiles( $items );

		foreach ( $this->foreignRepos as $repo ) {
			// Remove found files from $items
			foreach ( $images as $name => $image ) {
				unset( $items[$name] );
			}

			$images = array_merge( $images, $repo->findFiles( $items ) );
		}
		return $images;
	}

	/**
	 * Interface for FileRepo::checkRedirect()
	 */
	function checkRedirect( $title ) {
		if ( !$this->reposInitialised ) {
			$this->initialiseRepos();
		}

		$redir = $this->localRepo->checkRedirect( $title );
		if( $redir ) {
			return $redir;
		}
		foreach ( $this->foreignRepos as $repo ) {
			$redir = $repo->checkRedirect( $title );
			if ( $redir ) {
				return $redir;
			}
		}
		return false;
	}

	function findBySha1( $hash ) {
		if ( !$this->reposInitialised ) {
			$this->initialiseRepos();
		}

		$result = $this->localRepo->findBySha1( $hash );
		foreach ( $this->foreignRepos as $repo )
			$result = array_merge( $result, $repo->findBySha1( $hash ) );
		return $result;
	}

	/**
	 * Get the repo instance with a given key.
	 */
	function getRepo( $index ) {
		if ( !$this->reposInitialised ) {
			$this->initialiseRepos();
		}
		if ( $index === 'local' ) {
			return $this->localRepo;
		} elseif ( isset( $this->foreignRepos[$index] ) ) {
			return $this->foreignRepos[$index];
		} else {
			return false;
		}
	}
	/**
	 * Get the repo instance by its name
	 */
	function getRepoByName( $name ) {
		if ( !$this->reposInitialised ) {
			$this->initialiseRepos();
		}
		foreach ( $this->foreignRepos as $repo ) {
			if ( $repo->name == $name)
				return $repo;
		}
		return false;
	}

	/**
	 * Get the local repository, i.e. the one corresponding to the local image
	 * table. Files are typically uploaded to the local repository.
	 */
	function getLocalRepo() {
		return $this->getRepo( 'local' );
	}

	/**
	 * Call a function for each foreign repo, with the repo object as the
	 * first parameter.
	 *
	 * @param $callback Callback: the function to call
	 * @param $params Array: optional additional parameters to pass to the function
	 */
	function forEachForeignRepo( $callback, $params = array() ) {
		foreach( $this->foreignRepos as $repo ) {
			$args = array_merge( array( $repo ), $params );
			if( call_user_func_array( $callback, $args ) ) {
				return true;
			}
		}
		return false;
	}

	/**
	 * Does the installation have any foreign repos set up?
	 * @return Boolean
	 */
	function hasForeignRepos() {
		return (bool)$this->foreignRepos;
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

	/**
	 * Limit cache memory
	 */
	function trimCache() {
		while ( count( $this->cache ) >= self::MAX_CACHE_SIZE ) {
			reset( $this->cache );
			$key = key( $this->cache );
			wfDebug( __METHOD__.": evicting $key\n" );
			unset( $this->cache[$key] );
		}
	}
}
