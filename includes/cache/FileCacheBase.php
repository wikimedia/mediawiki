<?php
/**
 * Data storage in the file system.
 *
 * @license GPL-2.0-or-later
 * @file
 * @ingroup Cache
 */

namespace MediaWiki\Cache;

use MediaWiki\Config\ServiceOptions;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Request\WebRequest;
use Wikimedia\AtEase\AtEase;
use Wikimedia\IPUtils;
use Wikimedia\ObjectCache\BagOStuff;

/**
 * Base class for data storage in the file system.
 *
 * @ingroup Cache
 */
abstract class FileCacheBase {

	private const CONSTRUCTOR_OPTIONS = [
		MainConfigNames::CacheEpoch,
		MainConfigNames::FileCacheDepth,
		MainConfigNames::FileCacheDirectory,
		MainConfigNames::MimeType,
		MainConfigNames::UseGzip,
	];

	/** @var string */
	protected $mKey;
	/** @var string */
	protected $mType = 'object';
	/** @var string */
	protected $mExt = 'cache';
	/** @var string|null */
	protected $mFilePath;
	/** @var bool */
	protected $mUseGzip;
	/** @var bool|null lazy loaded */
	protected $mCached;
	/** @var ServiceOptions */
	protected $options;

	/* @todo configurable? */
	private const MISS_FACTOR = 15; // log 1 every MISS_FACTOR cache misses
	private const MISS_TTL_SEC = 3600; // how many seconds ago is "recent"

	protected function __construct() {
		$this->options = new ServiceOptions(
			self::CONSTRUCTOR_OPTIONS,
			MediaWikiServices::getInstance()->getMainConfig()
		);
		$this->mUseGzip = (bool)$this->options->get( MainConfigNames::UseGzip );
	}

	/**
	 * Get the base file cache directory
	 * @return string
	 */
	final protected function baseCacheDirectory() {
		return $this->options->get( MainConfigNames::FileCacheDirectory );
	}

	/**
	 * Get the base cache directory (not specific to this file)
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
		# Build directories (methods include the trailing "/")
		$subDirs = $this->typeSubdirectory() . $this->hashSubdirectory();
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
		$this->mCached ??= is_file( $this->cachePath() );

		return $this->mCached;
	}

	/**
	 * Get the last-modified timestamp of the cache file
	 * @return string|bool TS_MW timestamp
	 */
	public function cacheTimestamp() {
		$timestamp = filemtime( $this->cachePath() );

		return ( $timestamp !== false )
			? wfTimestamp( TS_MW, $timestamp )
			: false;
	}

	/**
	 * Check if up to date cache file exists
	 * @param string $timestamp MW_TS timestamp
	 *
	 * @return bool
	 */
	public function isCacheGood( $timestamp = '' ) {
		$cacheEpoch = $this->options->get( MainConfigNames::CacheEpoch );

		if ( !$this->isCached() ) {
			return false;
		}

		$cachetime = $this->cacheTimestamp();
		$good = ( $timestamp <= $cachetime && $cacheEpoch <= $cachetime );
		wfDebug( __METHOD__ .
			": cachetime $cachetime, touched '{$timestamp}' epoch {$cacheEpoch}, good " . wfBoolToStr( $good ) );

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
			$fh = gzopen( $this->cachePath(), 'rb' );

			return stream_get_contents( $fh );
		} else {
			return file_get_contents( $this->cachePath() );
		}
	}

	/**
	 * Save and compress text to the cache
	 * @param string $text
	 * @return string|false Compressed text
	 */
	public function saveText( $text ) {
		if ( $this->useGzip() ) {
			$text = gzencode( $text );
		}

		$this->checkCacheDirs(); // build parent dir
		if ( !file_put_contents( $this->cachePath(), $text, LOCK_EX ) ) {
			wfDebug( __METHOD__ . "() failed saving " . $this->cachePath() );
			$this->mCached = null;

			return false;
		}

		$this->mCached = true;

		return $text;
	}

	/**
	 * Clear the cache for this page
	 * @return void
	 */
	public function clearCache() {
		AtEase::suppressWarnings();
		unlink( $this->cachePath() );
		AtEase::restoreWarnings();
		$this->mCached = false;
	}

	/**
	 * Create parent directors of $this->cachePath()
	 * @return void
	 */
	protected function checkCacheDirs() {
		wfMkdirParents( dirname( $this->cachePath() ), null, __METHOD__ );
	}

	/**
	 * Get the cache type subdirectory (with trailing slash)
	 * An extending class could use that method to alter the type -> directory
	 * mapping. See {@link HTMLFileCache::typeSubdirectory} for an example.
	 *
	 * @return string
	 */
	protected function typeSubdirectory() {
		return $this->mType . '/';
	}

	/**
	 * Return relative multi-level hash subdirectory (with trailing slash)
	 * or the empty string if not $wgFileCacheDepth
	 * @return string
	 */
	protected function hashSubdirectory() {
		$fileCacheDepth = $this->options->get( MainConfigNames::FileCacheDepth );

		$subdir = '';
		if ( $fileCacheDepth > 0 ) {
			$hash = md5( $this->mKey );
			for ( $i = 1; $i <= $fileCacheDepth; $i++ ) {
				$subdir .= substr( $hash, 0, $i ) . '/';
			}
		}

		return $subdir;
	}

	/**
	 * Roughly increments the cache misses in the last hour by unique visitors
	 * @param WebRequest $request
	 * @return void
	 */
	public function incrMissesRecent( WebRequest $request ) {
		if ( mt_rand( 1, self::MISS_FACTOR ) == 1 ) {
			# Get a large IP range that should include the user  even if that
			# person's IP address changes
			$ip = $request->getIP();
			if ( !IPUtils::isValid( $ip ) ) {
				return;
			}

			$ip = IPUtils::isIPv6( $ip )
				? IPUtils::sanitizeRange( "$ip/32" )
				: IPUtils::sanitizeRange( "$ip/16" );

			# Bail out if a request already came from this range...
			$cache = MediaWikiServices::getInstance()->getObjectCacheFactory()
				->getLocalClusterInstance();
			$key = $cache->makeKey( static::class, 'attempt', $this->mType, $this->mKey, $ip );
			if ( !$cache->add( $key, 1, self::MISS_TTL_SEC ) ) {
				return; // possibly the same user
			}

			# Increment the number of cache misses...
			$cache->incrWithInit( $this->cacheMissKey( $cache ), self::MISS_TTL_SEC );
		}
	}

	/**
	 * Roughly gets the cache misses in the last hour by unique visitors
	 * @return int
	 */
	public function getMissesRecent() {
		$cache = MediaWikiServices::getInstance()->getObjectCacheFactory()
			->getLocalClusterInstance();

		return self::MISS_FACTOR * $cache->get( $this->cacheMissKey( $cache ) );
	}

	/**
	 * @param BagOStuff $cache Instance that the key will be used with
	 * @return string
	 */
	protected function cacheMissKey( BagOStuff $cache ) {
		return $cache->makeKey( static::class, 'misses', $this->mType, $this->mKey );
	}
}

/** @deprecated class alias since 1.42 */
class_alias( FileCacheBase::class, 'FileCacheBase' );
