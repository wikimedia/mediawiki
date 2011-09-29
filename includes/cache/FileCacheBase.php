<?php
/**
 * Contain the HTMLFileCache class
 * @file
 * @ingroup Cache
 */
abstract class FileCacheBase {
	protected $mKey;
	protected $mType;
	protected $mExt;
	protected $mFilePath;
	protected $mUseGzip;

	protected function __construct() {
		global $wgUseGzip;

		$this->mUseGzip = (bool)$wgUseGzip;
		$this->mExt = 'cache';
	}

	/**
	 * Get the base cache directory (not speficic to this file)
	 * @return string
	 */
	abstract protected function cacheDirectory();

	/**
	 * Get the path to the cache file
	 * @return string
	 */
	protected function cachePath() {
		if ( $this->mFilePath !== null ) {
			return $this->mFilePath;
		}

		$dir = $this->cacheDirectory();
		$subDirs = $this->mType . '/' . $this->hashSubdirectory(); // includes '/'
		# Avoid extension confusion
		$key = str_replace( '.', '%2E', urlencode( $this->mKey ) );
		# Build the full file path
		$this->mFilePath = "{$dir}/{$subDirs}{$key}.{$this->mExt}";
		if ( $this->useGzip() ) {
			$this->mFilePath .= '.gz';
		}

		return $this->mFilePath;
	}

	/**
	 * Check if the cache file exists
	 * @return bool
	 */
	public function isCached() {
		return file_exists( $this->cachePath() );
	}

	/**
	 * Get the last-modified timestamp of the cache file
	 * @return string|false TS_MW timestamp
	 */
	public function cacheTimestamp() {
		$timestamp = filemtime( $this->cachePath() );
		return ( $timestamp !== false )
			? wfTimestamp( TS_MW, $timestamp )
			: false;
	}

	/**
	 * Check if up to date cache file exists
	 * @param $timestamp string MW_TS timestamp
	 *
	 * @return bool
	 */
	public function isCacheGood( $timestamp = '' ) {
		global $wgCacheEpoch;

		if ( !$this->isCached() ) {
			return false;
		}

		$cachetime = $this->cacheTimestamp();
		$good = ( $timestamp <= $cachetime && $wgCacheEpoch <= $cachetime );
		wfDebug( __METHOD__ . ": cachetime $cachetime, touched '{$timestamp}' epoch {$wgCacheEpoch}, good $good\n");

		return $good;
	}

	/**
	 * Check if the cache is gzipped
	 * @return bool
	 */
	protected function useGzip() {
		return $this->mUseGzip;
	}

	/**
	 * Get the uncompressed text from the cache
	 * @return string
	 */
	public function fetchText() {
		if ( $this->useGzip() ) {
			/* Why is there no gzfile_get_contents() or gzdecode()? */
			return implode( '', gzfile( $this->cachePath() ) );
		} else {
			return file_get_contents( $this->cachePath() );
		}
	}

	/**
	 * Save and compress text to the cache
	 * @return string compressed text
	 */
	public function saveText( $text ) {
		global $wgUseFileCache;
		if ( !$wgUseFileCache ) {
			return false;
		}

		if ( $this->useGzip() ) {
			$text = gzencode( $text );
		}

		$this->checkCacheDirs(); // build parent dir
		if ( !file_put_contents( $this->cachePath(), $text ) ) {
			return false;
		}

		return $text;
	}

	/*
	 * Clear the cache for this page
	 * @return void
	 */
	public function clearCache() {
		wfSuppressWarnings();
		unlink( $this->cachePath() );
		wfRestoreWarnings();
	}

	/*
	 * Create parent directors of $this->cachePath()
	 * @TODO: why call wfMkdirParents() twice?
	 * @return void
	 */
	protected function checkCacheDirs() {
		$filename = $this->cachePath();
		$mydir2 = substr( $filename, 0, strrpos( $filename, '/') ); # subdirectory level 2
		$mydir1 = substr( $mydir2, 0, strrpos( $mydir2, '/') ); # subdirectory level 1

		wfMkdirParents( $mydir1, null, __METHOD__ );
		wfMkdirParents( $mydir2, null, __METHOD__ );
	}

	/*
	 * Return relative multi-level hash subdirectory with the trailing
	 * slash or the empty string if $wgFileCacheDepth is off
	 * @return string
	 */
	protected function hashSubdirectory() {
		global $wgFileCacheDepth;

		$subdir = '';
		if ( $wgFileCacheDepth > 0 ) {
			$hash = md5( $this->mKey );
			for ( $i = 1; $i <= $wgFileCacheDepth; $i++ ) {
				$subdir .= substr( $hash, 0, $i ) . '/';
			}
		}

		return $subdir;
	}
}
