<?php
/**
 * Cache of file objects, wrapping some RepoGroup functions to avoid redundant
 * queries.  Loosely inspired by the LinkCache / LinkBatch classes for titles.
 *
 * ISSUE: Merge with RepoGroup?
 *
 * @ingroup FileRepo
 */
class FileCache {
	var $repoGroup;
	var $cache = array(), $notFound = array();

	protected static $instance;

	/**
	 * Get a FileCache instance.  Typically, only one instance of FileCache
	 * is needed in a MediaWiki invocation.
	 */
	static function singleton() {
		if ( self::$instance ) {
			return self::$instance;
		}
		self::$instance = new FileCache( RepoGroup::singleton() );
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
	 * @param RepoGroup $repoGroup
	 */
	function __construct( $repoGroup ) {
		$this->repoGroup = $repoGroup;
	}


	/**
	 * Add some files to the cache.  This is a fairly low-level function,
	 * which most users should not need to call.  Note that any existing
	 * entries for the same keys will not be replaced.  Call clearFiles()
	 * first if you need that.
	 * @param array $files array of File objects, indexed by DB key
	 */
	function addFiles( $files ) {
		wfDebug( "FileCache adding ".count( $files )." files\n" );
		$this->cache += $files;
	}

	/**
	 * Remove some files from the cache, so that their existence will be
	 * rechecked.  This is a fairly low-level function, which most users
	 * should not need to call.
	 * @param array $remove array indexed by DB keys to remove (the values are ignored)
	 */
	function clearFiles( $remove ) {
		wfDebug( "FileCache clearing data for ".count( $remove )." files\n" );
		$this->cache = array_diff_keys( $this->cache, $remove );
		$this->notFound = array_diff_keys( $this->notFound, $remove );
	}

	/**
	 * Mark some DB keys as nonexistent.  This is a fairly low-level
	 * function, which most users should not need to call.
	 * @param array $dbkeys array of DB keys
	 */
	function markNotFound( $dbkeys ) {
		wfDebug( "FileCache marking ".count( $dbkeys )." files as not found\n" );
		$this->notFound += array_fill_keys( $dbkeys, true );
	}


	/**
	 * Search the cache for a file.
	 * @param mixed $title Title object or string
	 * @return File object or false if it is not found
	 * @todo Implement searching for old file versions(?)
	 */
	function findFile( $title ) {
		if( !( $title instanceof Title ) ) {
			$title = Title::makeTitleSafe( NS_FILE, $title );
		}
		if( !$title ) {
			return false;  // invalid title?
		}

		$dbkey = $title->getDBkey();
		if( array_key_exists( $dbkey, $this->cache ) ) {
			wfDebug( "FileCache HIT for $dbkey\n" );
			return $this->cache[$dbkey];
		}
		if( array_key_exists( $dbkey, $this->notFound ) ) {
			wfDebug( "FileCache negative HIT for $dbkey\n" );
			return false;
		}

		// Not in cache, fall back to a direct query
		$file = $this->repoGroup->findFile( $title );
		if( $file ) {
			wfDebug( "FileCache MISS for $dbkey\n" );
			$this->cache[$dbkey] = $file;
		} else {
			wfDebug( "FileCache negative MISS for $dbkey\n" );
			$this->notFound[$dbkey] = true;
		}
		return $file;
	}

	/**
	 * Search the cache for multiple files.
	 * @param array $titles Title objects or strings to search for
	 * @return array of File objects, indexed by DB key
	 */
	function findFiles( $titles ) {
		$titleObjs = array();
		foreach ( $titles as $title ) {
			if ( !( $title instanceof Title ) ) {
				$title = Title::makeTitleSafe( NS_FILE, $title );
			}
			if ( $title ) {
				$titleObjs[$title->getDBkey()] = $title;
			}
		}

		$result = array_intersect_key( $this->cache, $titleObjs );

		$unsure = array_diff_key( $titleObjs, $result, $this->notFound );
		if( $unsure ) {
			wfDebug( "FileCache MISS for ".count( $unsure )." files out of ".count( $titleObjs )."...\n" );
			// XXX: We assume the array returned by findFiles() is
			// indexed by DBkey; this appears to be true, but should
			// be explicitly documented.
			$found = $this->repoGroup->findFiles( $unsure );
			$result += $found;
			$this->addFiles( $found );
			$this->markNotFound( array_keys( array_diff_key( $unsure, $found ) ) );
		}

		wfDebug( "FileCache found ".count( $result )." files out of ".count( $titleObjs )."\n" );
		return $result;
	}
}
