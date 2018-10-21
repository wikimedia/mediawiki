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

use MediaWiki\MediaWikiServices;

/**
 * Prioritized list of file repositories
 *
 * @ingroup FileRepo
 */
class RepoGroup {
	/** @var LocalRepo */
	protected $localRepo;

	/** @var FileRepo[] */
	protected $foreignRepos;

	/** @var bool */
	protected $reposInitialised = false;

	/** @var array */
	protected $localInfo;

	/** @var array */
	protected $foreignInfo;

	/** @var ProcessCacheLRU */
	protected $cache;

	/** @var RepoGroup */
	protected static $instance;

	/** Maximum number of cache items */
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
		/** @var array $wgLocalFileRepo */
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
	 * @param RepoGroup $instance
	 */
	static function setSingleton( $instance ) {
		self::$instance = $instance;
	}

	/**
	 * Construct a group of file repositories.
	 *
	 * @param array $localInfo Associative array for local repo's info
	 * @param array $foreignInfo Array of repository info arrays.
	 *   Each info array is an associative array with the 'class' member
	 *   giving the class name. The entire array is passed to the repository
	 *   constructor as the first parameter.
	 */
	function __construct( $localInfo, $foreignInfo ) {
		$this->localInfo = $localInfo;
		$this->foreignInfo = $foreignInfo;
		$this->cache = new MapCacheLRU( self::MAX_CACHE_SIZE );
	}

	/**
	 * Search repositories for an image.
	 * You can also use wfFindFile() to do this.
	 *
	 * @param Title|string $title Title object or string
	 * @param array $options Associative array of options:
	 *   time:           requested time for an archived image, or false for the
	 *                   current version. An image object will be returned which was
	 *                   created at the specified time.
	 *   ignoreRedirect: If true, do not follow file redirects
	 *   private:        If true, return restricted (deleted) files if the current
	 *                   user is allowed to view them. Otherwise, such files will not
	 *                   be found.
	 *   latest:         If true, load from the latest available data into File objects
	 * @return File|bool False if title is not found
	 */
	function findFile( $title, $options = [] ) {
		if ( !is_array( $options ) ) {
			// MW 1.15 compat
			$options = [ 'time' => $options ];
		}
		if ( isset( $options['bypassCache'] ) ) {
			$options['latest'] = $options['bypassCache']; // b/c
		}
		$options += [ 'time' => false ];

		if ( !$this->reposInitialised ) {
			$this->initialiseRepos();
		}

		$title = File::normalizeTitle( $title );
		if ( !$title ) {
			return false;
		}

		# Check the cache
		$dbkey = $title->getDBkey();
		$timeKey = is_string( $options['time'] ) ? $options['time'] : '';
		if ( empty( $options['ignoreRedirect'] )
			&& empty( $options['private'] )
			&& empty( $options['latest'] )
		) {
			if ( $this->cache->hasField( $dbkey, $timeKey, 60 ) ) {
				return $this->cache->getField( $dbkey, $timeKey );
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

		$image = $image instanceof File ? $image : false; // type sanity
		# Cache file existence or non-existence
		if ( $useCache && ( !$image || $image->isCacheable() ) ) {
			$this->cache->setField( $dbkey, $timeKey, $image );
		}

		return $image;
	}

	/**
	 * Search repositories for many files at once.
	 *
	 * @param array $inputItems An array of titles, or an array of findFile() options with
	 *    the "title" option giving the title. Example:
	 *
	 *     $findItem = [ 'title' => $title, 'private' => true ];
	 *     $findBatch = [ $findItem ];
	 *     $repo->findFiles( $findBatch );
	 *
	 *    No title should appear in $items twice, as the result use titles as keys
	 * @param int $flags Supports:
	 *     - FileRepo::NAME_AND_TIME_ONLY : return a (search title => (title,timestamp)) map.
	 *       The search title uses the input titles; the other is the final post-redirect title.
	 *       All titles are returned as string DB keys and the inner array is associative.
	 * @return array Map of (file name => File objects) for matches
	 */
	function findFiles( array $inputItems, $flags = 0 ) {
		if ( !$this->reposInitialised ) {
			$this->initialiseRepos();
		}

		$items = [];
		foreach ( $inputItems as $item ) {
			if ( !is_array( $item ) ) {
				$item = [ 'title' => $item ];
			}
			$item['title'] = File::normalizeTitle( $item['title'] );
			if ( $item['title'] ) {
				$items[$item['title']->getDBkey()] = $item;
			}
		}

		$images = $this->localRepo->findFiles( $items, $flags );

		foreach ( $this->foreignRepos as $repo ) {
			// Remove found files from $items
			foreach ( $images as $name => $image ) {
				unset( $items[$name] );
			}

			$images = array_merge( $images, $repo->findFiles( $items, $flags ) );
		}

		return $images;
	}

	/**
	 * Interface for FileRepo::checkRedirect()
	 * @param Title $title
	 * @return bool|Title
	 */
	function checkRedirect( Title $title ) {
		if ( !$this->reposInitialised ) {
			$this->initialiseRepos();
		}

		$redir = $this->localRepo->checkRedirect( $title );
		if ( $redir ) {
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
	 * @param string $hash Base 36 SHA-1 hash
	 * @param array $options Option array, same as findFile()
	 * @return File|bool File object or false if it is not found
	 */
	function findFileFromKey( $hash, $options = [] ) {
		if ( !$this->reposInitialised ) {
			$this->initialiseRepos();
		}

		$file = $this->localRepo->findFileFromKey( $hash, $options );
		if ( !$file ) {
			foreach ( $this->foreignRepos as $repo ) {
				$file = $repo->findFileFromKey( $hash, $options );
				if ( $file ) {
					break;
				}
			}
		}

		return $file;
	}

	/**
	 * Find all instances of files with this key
	 *
	 * @param string $hash Base 36 SHA-1 hash
	 * @return File[]
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
	 * @param array $hashes Base 36 SHA-1 hashes
	 * @return array Array of array of File objects
	 */
	function findBySha1s( array $hashes ) {
		if ( !$this->reposInitialised ) {
			$this->initialiseRepos();
		}

		$result = $this->localRepo->findBySha1s( $hashes );
		foreach ( $this->foreignRepos as $repo ) {
			$result = array_merge_recursive( $result, $repo->findBySha1s( $hashes ) );
		}
		// sort the merged (and presorted) sublist of each hash
		foreach ( $result as $hash => $files ) {
			usort( $result[$hash], 'File::compare' );
		}

		return $result;
	}

	/**
	 * Get the repo instance with a given key.
	 * @param string|int $index
	 * @return bool|FileRepo
	 */
	function getRepo( $index ) {
		if ( !$this->reposInitialised ) {
			$this->initialiseRepos();
		}
		if ( $index === 'local' ) {
			return $this->localRepo;
		}
		return $this->foreignRepos[$index] ?? false;
	}

	/**
	 * Get the repo instance by its name
	 * @param string $name
	 * @return FileRepo|bool
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
		/** @var LocalRepo $repo */
		$repo = $this->getRepo( 'local' );

		return $repo;
	}

	/**
	 * Call a function for each foreign repo, with the repo object as the
	 * first parameter.
	 *
	 * @param callable $callback The function to call
	 * @param array $params Optional additional parameters to pass to the function
	 * @return bool
	 */
	function forEachForeignRepo( $callback, $params = [] ) {
		if ( !$this->reposInitialised ) {
			$this->initialiseRepos();
		}
		foreach ( $this->foreignRepos as $repo ) {
			if ( $callback( $repo, ...$params ) ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Does the installation have any foreign repos set up?
	 * @return bool
	 */
	function hasForeignRepos() {
		if ( !$this->reposInitialised ) {
			$this->initialiseRepos();
		}
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
		$this->foreignRepos = [];
		foreach ( $this->foreignInfo as $key => $info ) {
			$this->foreignRepos[$key] = $this->newRepo( $info );
		}
	}

	/**
	 * Create a repo class based on an info structure
	 * @param array $info
	 * @return FileRepo
	 */
	protected function newRepo( $info ) {
		$class = $info['class'];

		$cache = MediaWikiServices::getInstance()->getMainWANObjectCache();
		$info['wanCache'] = $cache;

		return new $class( $info );
	}

	/**
	 * Split a virtual URL into repo, zone and rel parts
	 * @param string $url
	 * @throws MWException
	 * @return string[] Containing repo, zone and rel
	 */
	function splitVirtualUrl( $url ) {
		if ( substr( $url, 0, 9 ) != 'mwrepo://' ) {
			throw new MWException( __METHOD__ . ': unknown protocol' );
		}

		$bits = explode( '/', substr( $url, 9 ), 3 );
		if ( count( $bits ) != 3 ) {
			throw new MWException( __METHOD__ . ": invalid mwrepo URL: $url" );
		}

		return $bits;
	}

	/**
	 * @param string $fileName
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
			$mwProps = new MWFileProps( MediaWiki\MediaWikiServices::getInstance()->getMimeAnalyzer() );

			return $mwProps->getPropsFromPath( $fileName, true );
		}
	}

	/**
	 * Clear RepoGroup process cache used for finding a file
	 * @param Title|null $title Title of the file or null to clear all files
	 */
	public function clearCache( Title $title = null ) {
		if ( $title == null ) {
			$this->cache->clear();
		} else {
			$this->cache->clear( $title->getDBkey() );
		}
	}
}
