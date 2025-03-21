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

namespace MediaWiki\FileRepo;

use InvalidArgumentException;
use MediaWiki\FileRepo\File\File;
use MediaWiki\Linker\LinkTarget;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Title\Title;
use MWFileProps;
use Wikimedia\MapCacheLRU\MapCacheLRU;
use Wikimedia\Mime\MimeAnalyzer;
use Wikimedia\ObjectCache\WANObjectCache;

/**
 * Prioritized list of file repositories.
 *
 * @ingroup FileRepo
 */
class RepoGroup {
	/** @var LocalRepo */
	protected $localRepo;

	/** @var FileRepo[] */
	protected $foreignRepos;

	/** @var WANObjectCache */
	protected $wanCache;

	/** @var bool */
	protected $reposInitialised = false;

	/** @var array */
	protected $localInfo;

	/** @var array */
	protected $foreignInfo;

	/** @var MapCacheLRU */
	protected $cache;

	/** Maximum number of cache items */
	private const MAX_CACHE_SIZE = 500;

	/** @var MimeAnalyzer */
	private $mimeAnalyzer;

	/**
	 * Construct a group of file repositories. Do not call this -- use
	 * MediaWikiServices::getRepoGroup.
	 *
	 * @param array $localInfo Associative array for local repo's info
	 * @param array $foreignInfo Array of repository info arrays.
	 *   Each info array is an associative array with the 'class' member
	 *   giving the class name. The entire array is passed to the repository
	 *   constructor as the first parameter.
	 * @param WANObjectCache $wanCache
	 * @param MimeAnalyzer $mimeAnalyzer
	 */
	public function __construct(
		$localInfo,
		$foreignInfo,
		WANObjectCache $wanCache,
		MimeAnalyzer $mimeAnalyzer
	) {
		$this->localInfo = $localInfo;
		$this->foreignInfo = $foreignInfo;
		$this->cache = new MapCacheLRU( self::MAX_CACHE_SIZE );
		$this->wanCache = $wanCache;
		$this->mimeAnalyzer = $mimeAnalyzer;
	}

	/**
	 * Search repositories for an image.
	 *
	 * @param PageIdentity|LinkTarget|string $title The file to find
	 * @param array $options Associative array of options:
	 *   time:           requested time for an archived image, or false for the
	 *                   current version. An image object will be returned which was
	 *                   created at the specified time.
	 *   ignoreRedirect: If true, do not follow file redirects
	 *   private:        If Authority object, return restricted (deleted) files if the
	 *                   performer is allowed to view them. Otherwise, such files will not
	 *                   be found. Authority is only accepted since 1.37, User was required
	 *                   before.
	 *   latest:         If true, load from the latest available data into File objects
	 * @phpcs:ignore Generic.Files.LineLength
	 * @phan-param array{time?:mixed,ignoreRedirect?:bool,private?:bool|\MediaWiki\Permissions\Authority,latest?:bool} $options
	 * @return File|false False if title is not found
	 */
	public function findFile( $title, $options = [] ) {
		if ( !is_array( $options ) ) {
			// MW 1.15 compat
			$options = [ 'time' => $options ];
		}
		if ( isset( $options['bypassCache'] ) ) {
			$options['latest'] = $options['bypassCache']; // b/c
		}
		if ( isset( $options['time'] ) && $options['time'] !== false ) {
			$options['time'] = wfTimestamp( TS_MW, $options['time'] );
		} else {
			$options['time'] = false;
		}

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

		$image = $image instanceof File ? $image : false; // type check
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
	 * @return array Map of (file name => File objects) for matches or (search title => (title,timestamp))
	 */
	public function findFiles( array $inputItems, $flags = 0 ) {
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
			$items = array_diff_key( $items, $images );
			$images = array_merge( $images, $repo->findFiles( $items, $flags ) );
		}

		return $images;
	}

	/**
	 * Interface for FileRepo::checkRedirect()
	 * @param PageIdentity|LinkTarget|string $title
	 * @return Title|false
	 */
	public function checkRedirect( $title ) {
		if ( !$this->reposInitialised ) {
			$this->initialiseRepos();
		}

		$title = File::normalizeTitle( $title );

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
	 * @return File|false File object or false if it is not found
	 */
	public function findFileFromKey( $hash, $options = [] ) {
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
	public function findBySha1( $hash ) {
		if ( !$this->reposInitialised ) {
			$this->initialiseRepos();
		}

		$result = $this->localRepo->findBySha1( $hash );
		foreach ( $this->foreignRepos as $repo ) {
			$result = array_merge( $result, $repo->findBySha1( $hash ) );
		}
		usort( $result, [ File::class, 'compare' ] );

		return $result;
	}

	/**
	 * Find all instances of files with this keys
	 *
	 * @param string[] $hashes Base 36 SHA-1 hashes
	 * @return File[][]
	 */
	public function findBySha1s( array $hashes ) {
		if ( !$this->reposInitialised ) {
			$this->initialiseRepos();
		}

		$result = $this->localRepo->findBySha1s( $hashes );
		foreach ( $this->foreignRepos as $repo ) {
			$result = array_merge_recursive( $result, $repo->findBySha1s( $hashes ) );
		}
		// sort the merged (and presorted) sublist of each hash
		foreach ( $result as $hash => $files ) {
			usort( $result[$hash], [ File::class, 'compare' ] );
		}

		return $result;
	}

	/**
	 * Get the repo instance with a given key.
	 * @param string|int $index
	 * @return FileRepo|false
	 */
	public function getRepo( $index ) {
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
	 * @return FileRepo|false
	 */
	public function getRepoByName( $name ) {
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
	public function getLocalRepo() {
		// @phan-suppress-next-line PhanTypeMismatchReturnSuperType
		return $this->getRepo( 'local' );
	}

	/**
	 * Call a function for each foreign repo, with the repo object as the
	 * first parameter.
	 *
	 * @param callable $callback The function to call
	 * @param array $params Optional additional parameters to pass to the function
	 * @return bool
	 */
	public function forEachForeignRepo( $callback, $params = [] ) {
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
	public function hasForeignRepos() {
		if ( !$this->reposInitialised ) {
			$this->initialiseRepos();
		}
		return (bool)$this->foreignRepos;
	}

	/**
	 * Initialise the $repos array
	 */
	public function initialiseRepos() {
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
	 * Create a local repo with the specified option overrides.
	 *
	 * @param array $info
	 * @return LocalRepo
	 */
	public function newCustomLocalRepo( $info = [] ) {
		// @phan-suppress-next-line PhanTypeMismatchReturnSuperType
		return $this->newRepo( $info + $this->localInfo );
	}

	/**
	 * Create a repo class based on an info structure
	 * @param array $info
	 * @return FileRepo
	 */
	protected function newRepo( $info ) {
		$class = $info['class'];

		$info['wanCache'] = $this->wanCache;

		return new $class( $info );
	}

	/**
	 * Split a virtual URL into repo, zone and rel parts
	 * @param string $url
	 * @return string[] Containing repo, zone and rel
	 */
	private function splitVirtualUrl( $url ) {
		if ( !str_starts_with( $url, 'mwrepo://' ) ) {
			throw new InvalidArgumentException( __METHOD__ . ': unknown protocol' );
		}

		$bits = explode( '/', substr( $url, 9 ), 3 );
		if ( count( $bits ) != 3 ) {
			throw new InvalidArgumentException( __METHOD__ . ": invalid mwrepo URL: $url" );
		}

		return $bits;
	}

	/**
	 * @param string $fileName
	 * @return array
	 */
	public function getFileProps( $fileName ) {
		if ( FileRepo::isVirtualUrl( $fileName ) ) {
			[ $repoName, /* $zone */, /* $rel */ ] = $this->splitVirtualUrl( $fileName );
			if ( $repoName === '' ) {
				$repoName = 'local';
			}
			$repo = $this->getRepo( $repoName );

			return $repo->getFileProps( $fileName );
		} else {
			$mwProps = new MWFileProps( $this->mimeAnalyzer );

			return $mwProps->getPropsFromPath( $fileName, true );
		}
	}

	/**
	 * Clear RepoGroup process cache used for finding a file
	 * @param PageIdentity|string|null $title File page or file name, or null to clear all files
	 */
	public function clearCache( $title = null ) {
		if ( $title == null ) {
			$this->cache->clear();
		} elseif ( is_string( $title ) ) {
			$this->cache->clear( $title );
		} else {
			$this->cache->clear( $title->getDBkey() );
		}
	}
}

/** @deprecated class alias since 1.44 */
class_alias( RepoGroup::class, 'RepoGroup' );
