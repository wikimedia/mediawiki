<?php
/**
 * Prioritized list of file repositories.
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
 * @ingroup FileRepo
 */

/**
 * Prioritized list of file repositories
 *
 * @ingroup FileRepo
 */
class RepoGroup {
	/**
	 * @var LocalRepo
	 */
	var $localRepo;

	var $foreignRepos, $reposInitialised = false;
	var $localInfo, $foreignInfo;
	var $cache;

	/**
	 * @var RepoGroup
	 */
	protected static $instance;
	const MAX_CACHE_SIZE = 500;

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
	 * Used by extensions which hook into the Repo chain.
	 * It's not enough to just create a superclass ... you have
	 * to get people to call into it even though all they know is RepoGroup::singleton()
	 *
	 * @param $instance RepoGroup
	 */
	static function setSingleton( $instance ) {
		self::$instance = $instance;
	}

	/**
	 * Construct a group of file repositories.
	 *
	 * @param $localInfo array Associative array for local repo's info
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
	 * @param $title Title|string Title object or string
	 * @param $options array Associative array of options:
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
		$title = File::normalizeTitle( $title );
		if ( !$title ) {
			return false;
		}

		# Check the cache
		if ( empty( $options['ignoreRedirect'] )
			&& empty( $options['private'] )
			&& empty( $options['bypassCache'] ) )
		{
			$time = isset( $options['time'] ) ? $options['time'] : '';
			$dbkey = $title->getDBkey();
			if ( isset( $this->cache[$dbkey][$time] ) ) {
				wfDebug( __METHOD__.": got File:$dbkey from process cache\n" );
				# Move it to the end of the list so that we can delete the LRU entry later
				$this->pingCache( $dbkey );
				# Return the entry
				return $this->cache[$dbkey][$time];
			}
			$useCache = true;
		} else {
			$useCache = false;
		}

		# Check the local repo
		$image = $this->localRepo->findFile( $title, $options );

		# Check the foreign repos
		if ( !$image ) {
			foreach ( $this->foreignRepos as $repo ) {
				$image = $repo->findFile( $title, $options );
				if ( $image ) {
					break;
				}
			}
		}

		$image = $image ? $image : false; // type sanity
		# Cache file existence or non-existence
		if ( $useCache && ( !$image || $image->isCacheable() ) ) {
			$this->trimCache();
			$this->cache[$dbkey][$time] = $image;
		}

		return $image;
	}

	/**
	 * @param $inputItems array
	 * @return array
	 */
	function findFiles( $inputItems ) {
		if ( !$this->reposInitialised ) {
			$this->initialiseRepos();
		}

		$items = array();
		foreach ( $inputItems as $item ) {
			if ( !is_array( $item ) ) {
				$item = array( 'title' => $item );
			}
			$item['title'] = File::normalizeTitle( $item['title'] );
			if ( $item['title'] ) {
				$items[$item['title']->getDBkey()] = $item;
			}
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
	 * @param $title Title
	 * @return bool
	 */
	function checkRedirect( Title $title ) {
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

	/**
	 * Find an instance of the file with this key, created at the specified time
	 * Returns false if the file does not exist.
	 *
	 * @param $hash String base 36 SHA-1 hash
	 * @param $options array Option array, same as findFile()
	 * @return File object or false if it is not found
	 */
	function findFileFromKey( $hash, $options = array() ) {
		if ( !$this->reposInitialised ) {
			$this->initialiseRepos();
		}

		$file = $this->localRepo->findFileFromKey( $hash, $options );
		if ( !$file ) {
			foreach ( $this->foreignRepos as $repo ) {
				$file = $repo->findFileFromKey( $hash, $options );
				if ( $file ) break;
			}
		}
		return $file;
	}

	/**
	 * Find all instances of files with this key
	 *
	 * @param $hash String base 36 SHA-1 hash
	 * @return Array of File objects
	 */
	function findBySha1( $hash ) {
		if ( !$this->reposInitialised ) {
			$this->initialiseRepos();
		}

		$result = $this->localRepo->findBySha1( $hash );
		foreach ( $this->foreignRepos as $repo ) {
			$result = array_merge( $result, $repo->findBySha1( $hash ) );
		}
		usort( $result, 'File::compare' );
		return $result;
	}

	/**
	 * Find all instances of files with this keys
	 *
	 * @param $hashes Array base 36 SHA-1 hashes
	 * @return Array of array of File objects
	 */
	function findBySha1s( array $hashes ) {
		if ( !$this->reposInitialised ) {
			$this->initialiseRepos();
		}

		$result = $this->localRepo->findBySha1s( $hashes );
		foreach ( $this->foreignRepos as $repo ) {
			$result = array_merge_recursive( $result, $repo->findBySha1s( $hashes ) );
		}
		//sort the merged (and presorted) sublist of each hash
		foreach( $result as $hash => $files ) {
			usort( $result[$hash], 'File::compare' );
		}
		return $result;
	}

	/**
	 * Get the repo instance with a given key.
	 * @param $index string|int
	 * @return bool|LocalRepo
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
	 * @param $name string
	 * @return bool
	 */
	function getRepoByName( $name ) {
		if ( !$this->reposInitialised ) {
			$this->initialiseRepos();
		}
		foreach ( $this->foreignRepos as $repo ) {
			if ( $repo->name == $name ) {
				return $repo;
			}
		}
		return false;
	}

	/**
	 * Get the local repository, i.e. the one corresponding to the local image
	 * table. Files are typically uploaded to the local repository.
	 *
	 * @return LocalRepo
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
	 * @return bool
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
	 * @param $url string
	 * @throws MWException
	 * @return array containing repo, zone and rel
	 */
	function splitVirtualUrl( $url ) {
		if ( substr( $url, 0, 9 ) != 'mwrepo://' ) {
			throw new MWException( __METHOD__.': unknown protocol' );
		}

		$bits = explode( '/', substr( $url, 9 ), 3 );
		if ( count( $bits ) != 3 ) {
			throw new MWException( __METHOD__.": invalid mwrepo URL: $url" );
		}
		return $bits;
	}

	/**
	 * @param $fileName string
	 * @return array
	 */
	function getFileProps( $fileName ) {
		if ( FileRepo::isVirtualUrl( $fileName ) ) {
			list( $repoName, /* $zone */, /* $rel */ ) = $this->splitVirtualUrl( $fileName );
			if ( $repoName === '' ) {
				$repoName = 'local';
			}
			$repo = $this->getRepo( $repoName );
			return $repo->getFileProps( $fileName );
		} else {
			return FSFile::getPropsFromPath( $fileName );
		}
	}

	/**
	 * Move a cache entry to the top (such as when accessed)
	 */
	protected function pingCache( $key ) {
		if ( isset( $this->cache[$key] ) ) {
			$tmp = $this->cache[$key];
			unset( $this->cache[$key] );
			$this->cache[$key] = $tmp;
		}
	}

	/**
	 * Limit cache memory
	 */
	protected function trimCache() {
		while ( count( $this->cache ) >= self::MAX_CACHE_SIZE ) {
			reset( $this->cache );
			$key = key( $this->cache );
			wfDebug( __METHOD__.": evicting $key\n" );
			unset( $this->cache[$key] );
		}
	}

	/**
	 * Clear RepoGroup process cache used for finding a file
	 * @param $title Title|null Title of the file or null to clear all files
	 */
	public function clearCache( Title $title = null ) {
		if ( $title == null ) {
			$this->cache = array();
		} else {
			$dbKey = $title->getDBkey();
			if ( isset( $this->cache[$dbKey] ) ) {
				unset( $this->cache[$dbKey] );
			}
		}
	}
}
