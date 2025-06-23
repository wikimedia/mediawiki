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

use LogicException;
use MediaTransformError;
use MediaWiki\FileRepo\File\File;
use MediaWiki\FileRepo\File\ForeignAPIFile;
use MediaWiki\Json\FormatJson;
use MediaWiki\Linker\LinkTarget;
use MediaWiki\Logger\LoggerFactory;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Title\Title;
use RuntimeException;
use Wikimedia\FileBackend\FileBackend;
use Wikimedia\ObjectCache\WANObjectCache;

/**
 * A foreign repository for a remote MediaWiki accessible through api.php requests.
 *
 * @par Example config:
 * @code
 * $wgForeignFileRepos[] = [
 *   'class'                  => ForeignAPIRepo::class,
 *   'name'                   => 'shared',
 *   'apibase'                => 'https://en.wikipedia.org/w/api.php',
 *   'fetchDescription'       => true, // Optional
 *   'descriptionCacheExpiry' => 3600,
 * ];
 * @endcode
 *
 * @ingroup FileRepo
 */
class ForeignAPIRepo extends FileRepo implements IForeignRepoWithMWApi {
	/* This version string is used in the user agent for requests and will help
	 * server maintainers in identify ForeignAPI usage.
	 * Update the version every time you make breaking or significant changes. */
	private const VERSION = "2.1";

	/**
	 * List of iiprop values for the thumbnail fetch queries.
	 */
	private const IMAGE_INFO_PROPS = [
		'url',
		'timestamp',
	];

	/** @var callable */
	protected $fileFactory = [ ForeignAPIFile::class, 'newFromTitle' ];
	/** @var int Check back with Commons after this expiry */
	protected $apiThumbCacheExpiry = 24 * 3600; // 1 day

	/** @var int Redownload thumbnail files after this expiry */
	protected $fileCacheExpiry = 30 * 24 * 3600; // 1 month

	/**
	 * @var int API metadata cache time.
	 * @since 1.38
	 *
	 * This is often the performance bottleneck for ForeignAPIRepo. For
	 * each file used, we must fetch file metadata for it and every high-DPI
	 * variant, in serial, during the parse. This is slow if a page has many
	 * files, with RTT of the handshake often being significant. The metadata
	 * rarely changes, but if a new version of the file was uploaded, it might
	 * be displayed incorrectly until its metadata entry falls out of cache.
	 */
	protected $apiMetadataExpiry = 4 * 3600; // 4 hours

	/** @var array */
	protected $mFileExists = [];

	/** @var string */
	private $mApiBase;

	/**
	 * @param array|null $info
	 */
	public function __construct( $info ) {
		$localFileRepo = MediaWikiServices::getInstance()->getMainConfig()
			->get( MainConfigNames::LocalFileRepo );
		parent::__construct( $info );

		// https://commons.wikimedia.org/w/api.php
		$this->mApiBase = $info['apibase'] ?? null;

		if ( isset( $info['apiThumbCacheExpiry'] ) ) {
			$this->apiThumbCacheExpiry = $info['apiThumbCacheExpiry'];
		}
		if ( isset( $info['fileCacheExpiry'] ) ) {
			$this->fileCacheExpiry = $info['fileCacheExpiry'];
		}
		if ( isset( $info['apiMetadataExpiry'] ) ) {
			$this->apiMetadataExpiry = $info['apiMetadataExpiry'];
		}
		if ( !$this->scriptDirUrl ) {
			// hack for description fetches
			$this->scriptDirUrl = dirname( $this->mApiBase );
		}
		// If we can cache thumbs we can guess sensible defaults for these
		if ( $this->canCacheThumbs() && !$this->url ) {
			$this->url = $localFileRepo['url'];
		}
		if ( $this->canCacheThumbs() && !$this->thumbUrl ) {
			$this->thumbUrl = $this->url . '/thumb';
		}
	}

	/**
	 * Per docs in FileRepo, this needs to return false if we don't support versioned
	 * files. Well, we don't.
	 *
	 * @param PageIdentity|LinkTarget|string $title
	 * @param string|false $time
	 * @return File|false
	 */
	public function newFile( $title, $time = false ) {
		if ( $time ) {
			return false;
		}

		return parent::newFile( $title, $time );
	}

	/**
	 * @param string[] $files
	 * @return array
	 */
	public function fileExistsBatch( array $files ) {
		$results = [];
		foreach ( $files as $k => $f ) {
			if ( isset( $this->mFileExists[$f] ) ) {
				$results[$k] = $this->mFileExists[$f];
				unset( $files[$k] );
			} elseif ( self::isVirtualUrl( $f ) ) {
				# @todo FIXME: We need to be able to handle virtual
				# URLs better, at least when we know they refer to the
				# same repo.
				$results[$k] = false;
				unset( $files[$k] );
			} elseif ( FileBackend::isStoragePath( $f ) ) {
				$results[$k] = false;
				unset( $files[$k] );
				wfWarn( "Got mwstore:// path '$f'." );
			}
		}

		$data = $this->fetchImageQuery( [
			'titles' => implode( '|', $files ),
			'prop' => 'imageinfo' ]
		);

		if ( isset( $data['query']['pages'] ) ) {
			# First, get results from the query. Note we only care whether the image exists,
			# not whether it has a description page.
			foreach ( $data['query']['pages'] as $p ) {
				$this->mFileExists[$p['title']] = ( $p['imagerepository'] !== '' );
			}
			# Second, copy the results to any redirects that were queried
			if ( isset( $data['query']['redirects'] ) ) {
				foreach ( $data['query']['redirects'] as $r ) {
					$this->mFileExists[$r['from']] = $this->mFileExists[$r['to']];
				}
			}
			# Third, copy the results to any non-normalized titles that were queried
			if ( isset( $data['query']['normalized'] ) ) {
				foreach ( $data['query']['normalized'] as $n ) {
					$this->mFileExists[$n['from']] = $this->mFileExists[$n['to']];
				}
			}
			# Finally, copy the results to the output
			foreach ( $files as $key => $file ) {
				$results[$key] = $this->mFileExists[$file];
			}
		}

		return $results;
	}

	/**
	 * @param string $virtualUrl
	 * @return array
	 */
	public function getFileProps( $virtualUrl ) {
		return [];
	}

	/**
	 * Make an API query in the foreign repo, caching results
	 *
	 * @param array $query
	 * @return array|null
	 */
	public function fetchImageQuery( $query ) {
		$languageCode = MediaWikiServices::getInstance()->getMainConfig()
			->get( MainConfigNames::LanguageCode );

		$query = array_merge( $query,
			[
				'format' => 'json',
				'action' => 'query',
				'redirects' => 'true'
			] );

		if ( !isset( $query['uselang'] ) ) { // uselang is unset or null
			$query['uselang'] = $languageCode;
		}

		$data = $this->httpGetCached( 'Metadata', $query, $this->apiMetadataExpiry );

		if ( $data ) {
			return FormatJson::decode( $data, true );
		} else {
			return null;
		}
	}

	/**
	 * @param array $data
	 * @return array|false
	 */
	public function getImageInfo( $data ) {
		if ( $data && isset( $data['query']['pages'] ) ) {
			foreach ( $data['query']['pages'] as $info ) {
				if ( isset( $info['imageinfo'][0] ) ) {
					$return = $info['imageinfo'][0];
					if ( isset( $info['pageid'] ) ) {
						$return['pageid'] = $info['pageid'];
					}
					return $return;
				}
			}
		}

		return false;
	}

	/**
	 * @param string $hash
	 * @return ForeignAPIFile[]
	 */
	public function findBySha1( $hash ) {
		$results = $this->fetchImageQuery( [
			'aisha1base36' => $hash,
			'aiprop' => ForeignAPIFile::getProps(),
			'list' => 'allimages',
		] );
		$ret = [];
		if ( isset( $results['query']['allimages'] ) ) {
			foreach ( $results['query']['allimages'] as $img ) {
				// 1.14 was broken, doesn't return name attribute
				if ( !isset( $img['name'] ) ) {
					continue;
				}
				$ret[] = new ForeignAPIFile( Title::makeTitle( NS_FILE, $img['name'] ), $this, $img );
			}
		}

		return $ret;
	}

	/**
	 * @param string $name
	 * @param int $width
	 * @param int $height
	 * @param array|null &$result Output-only parameter, guaranteed to become an array
	 * @param string $otherParams
	 *
	 * @return string|false
	 */
	private function getThumbUrl(
		$name, $width = -1, $height = -1, &$result = null, $otherParams = ''
	) {
		$data = $this->fetchImageQuery( [
			'titles' => 'File:' . $name,
			'iiprop' => self::getIIProps(),
			'iiurlwidth' => $width,
			'iiurlheight' => $height,
			'iiurlparam' => $otherParams,
			'prop' => 'imageinfo' ] );
		$info = $this->getImageInfo( $data );

		if ( $data && $info && isset( $info['thumburl'] ) ) {
			wfDebug( __METHOD__ . " got remote thumb " . $info['thumburl'] );
			$result = $info;

			return $info['thumburl'];
		} else {
			return false;
		}
	}

	/**
	 * @param string $name
	 * @param int $width
	 * @param int $height
	 * @param string $otherParams
	 * @param string|null $lang Language code for language of error
	 * @return MediaTransformError|false
	 * @since 1.22
	 */
	public function getThumbError(
		$name, $width = -1, $height = -1, $otherParams = '', $lang = null
	) {
		$data = $this->fetchImageQuery( [
			'titles' => 'File:' . $name,
			'iiprop' => self::getIIProps(),
			'iiurlwidth' => $width,
			'iiurlheight' => $height,
			'iiurlparam' => $otherParams,
			'prop' => 'imageinfo',
			'uselang' => $lang,
		] );
		$info = $this->getImageInfo( $data );

		if ( $data && $info && isset( $info['thumberror'] ) ) {
			wfDebug( __METHOD__ . " got remote thumb error " . $info['thumberror'] );

			return new MediaTransformError(
				'thumbnail_error_remote',
				$width,
				$height,
				$this->getDisplayName(),
				$info['thumberror'] // already parsed message from foreign repo
			);
		} else {
			return false;
		}
	}

	/**
	 * Return the imageurl from cache if possible
	 *
	 * If the url has been requested today, get it from cache
	 * Otherwise retrieve remote thumb url, check for local file.
	 *
	 * @param string $name Is a dbkey form of a title
	 * @param int $width
	 * @param int $height
	 * @param string $params Other rendering parameters (page number, etc)
	 *   from handler's makeParamString.
	 * @return string|false
	 */
	public function getThumbUrlFromCache( $name, $width, $height, $params = "" ) {
		// We can't check the local cache using FileRepo functions because
		// we override fileExistsBatch(). We have to use the FileBackend directly.
		$backend = $this->getBackend(); // convenience

		if ( !$this->canCacheThumbs() ) {
			$result = null; // can't pass "null" by reference, but it's ok as default value

			return $this->getThumbUrl( $name, $width, $height, $result, $params );
		}

		$key = $this->getLocalCacheKey( 'file-thumb-url', sha1( $name ) );
		$sizekey = "$width:$height:$params";

		/* Get the array of urls that we already know */
		$knownThumbUrls = $this->wanCache->get( $key );
		if ( !$knownThumbUrls ) {
			/* No knownThumbUrls for this file */
			$knownThumbUrls = [];
		} elseif ( isset( $knownThumbUrls[$sizekey] ) ) {
			wfDebug( __METHOD__ . ': Got thumburl from local cache: ' .
				"{$knownThumbUrls[$sizekey]}" );

			return $knownThumbUrls[$sizekey];
		}

		$metadata = null;
		$foreignUrl = $this->getThumbUrl( $name, $width, $height, $metadata, $params );

		if ( !$foreignUrl ) {
			wfDebug( __METHOD__ . " Could not find thumburl" );

			return false;
		}

		// We need the same filename as the remote one :)
		$fileName = rawurldecode( pathinfo( $foreignUrl, PATHINFO_BASENAME ) );
		if ( !$this->validateFilename( $fileName ) ) {
			wfDebug( __METHOD__ . " The deduced filename $fileName is not safe" );

			return false;
		}
		$localPath = $this->getZonePath( 'thumb' ) . "/" . $this->getHashPath( $name ) . $name;
		$localFilename = $localPath . "/" . $fileName;
		$localUrl = $this->getZoneUrl( 'thumb' ) . "/" . $this->getHashPath( $name ) .
			rawurlencode( $name ) . "/" . rawurlencode( $fileName );

		if ( $backend->fileExists( [ 'src' => $localFilename ] )
			&& isset( $metadata['timestamp'] )
		) {
			wfDebug( __METHOD__ . " Thumbnail was already downloaded before" );
			$modified = (int)wfTimestamp( TS_UNIX, $backend->getFileTimestamp( [ 'src' => $localFilename ] ) );
			$remoteModified = (int)wfTimestamp( TS_UNIX, $metadata['timestamp'] );
			$current = (int)wfTimestamp( TS_UNIX );
			$diff = abs( $modified - $current );
			if ( $remoteModified < $modified && $diff < $this->fileCacheExpiry ) {
				/* Use our current and already downloaded thumbnail */
				$knownThumbUrls[$sizekey] = $localUrl;
				$this->wanCache->set( $key, $knownThumbUrls, $this->apiThumbCacheExpiry );

				return $localUrl;
			}
			/* There is a new Commons file, or existing thumbnail older than a month */
		}

		$thumb = self::httpGet( $foreignUrl, 'default', [], $mtime );
		if ( !$thumb ) {
			wfDebug( __METHOD__ . " Could not download thumb" );

			return false;
		}

		# @todo FIXME: Delete old thumbs that aren't being used. Maintenance script?
		$backend->prepare( [ 'dir' => dirname( $localFilename ) ] );
		$params = [ 'dst' => $localFilename, 'content' => $thumb ];
		if ( !$backend->quickCreate( $params )->isOK() ) {
			wfDebug( __METHOD__ . " could not write to thumb path '$localFilename'" );

			return $foreignUrl;
		}
		$knownThumbUrls[$sizekey] = $localUrl;

		$ttl = $mtime
			? $this->wanCache->adaptiveTTL( $mtime, $this->apiThumbCacheExpiry )
			: $this->apiThumbCacheExpiry;
		$this->wanCache->set( $key, $knownThumbUrls, $ttl );
		wfDebug( __METHOD__ . " got local thumb $localUrl, saving to cache" );

		return $localUrl;
	}

	/**
	 * @see FileRepo::getZoneUrl()
	 * @param string $zone
	 * @param string|null $ext Optional file extension
	 * @return string
	 */
	public function getZoneUrl( $zone, $ext = null ) {
		switch ( $zone ) {
			case 'public':
				return $this->url;
			case 'thumb':
				return $this->thumbUrl;
			default:
				return parent::getZoneUrl( $zone, $ext );
		}
	}

	/**
	 * Get the local directory corresponding to one of the basic zones
	 * @param string $zone
	 * @return null|string|false
	 */
	public function getZonePath( $zone ) {
		$supported = [ 'public', 'thumb' ];
		if ( in_array( $zone, $supported ) ) {
			return parent::getZonePath( $zone );
		}

		return false;
	}

	/**
	 * Are we locally caching the thumbnails?
	 * @return bool
	 */
	public function canCacheThumbs() {
		return ( $this->apiThumbCacheExpiry > 0 );
	}

	/**
	 * The user agent the ForeignAPIRepo will use.
	 * @return string
	 */
	public static function getUserAgent() {
		return MediaWikiServices::getInstance()->getHttpRequestFactory()->getUserAgent() .
			" ForeignAPIRepo/" . self::VERSION;
	}

	/**
	 * Get information about the repo - overrides/extends the parent
	 * class's information.
	 * @return array
	 * @since 1.22
	 */
	public function getInfo() {
		$info = parent::getInfo();
		$info['apiurl'] = $this->mApiBase;

		$query = [
			'format' => 'json',
			'action' => 'query',
			'meta' => 'siteinfo',
			'siprop' => 'general',
		];

		$data = $this->httpGetCached( 'SiteInfo', $query, 7200 );

		if ( $data ) {
			$siteInfo = FormatJson::decode( $data, true );
			$general = $siteInfo['query']['general'];

			$info['articlepath'] = $general['articlepath'];
			$info['server'] = $general['server'];
			if ( !isset( $info['favicon'] ) && isset( $general['favicon'] ) ) {
				$info['favicon'] = $general['favicon'];
			}
		}

		return $info;
	}

	/**
	 * @param string $url
	 * @param string $timeout
	 * @param array $options
	 * @param int|false &$mtime Resulting Last-Modified UNIX timestamp if received
	 * @return string|false
	 */
	public static function httpGet(
		$url, $timeout = 'default', $options = [], &$mtime = false
	) {
		$options['timeout'] = $timeout;
		$url = MediaWikiServices::getInstance()->getUrlUtils()
			->expand( $url, PROTO_HTTP );
		wfDebug( "ForeignAPIRepo: HTTP GET: $url" );
		if ( !$url ) {
			return false;
		}
		$options['method'] = "GET";

		if ( !isset( $options['timeout'] ) ) {
			$options['timeout'] = 'default';
		}

		$options['userAgent'] = self::getUserAgent();

		$req = MediaWikiServices::getInstance()->getHttpRequestFactory()
			->create( $url, $options, __METHOD__ );
		$status = $req->execute();

		if ( $status->isOK() ) {
			$lmod = $req->getResponseHeader( 'Last-Modified' );
			$mtime = $lmod ? (int)wfTimestamp( TS_UNIX, $lmod ) : false;

			return $req->getContent();
		} else {
			$logger = LoggerFactory::getInstance( 'http' );
			$logger->warning(
				$status->getWikiText( false, false, 'en' ),
				[ 'caller' => 'ForeignAPIRepo::httpGet' ]
			);

			return false;
		}
	}

	/**
	 * @return string
	 * @since 1.23
	 */
	protected static function getIIProps() {
		return implode( '|', self::IMAGE_INFO_PROPS );
	}

	/**
	 * HTTP GET request to a mediawiki API (with caching)
	 * @param string $attribute Used in cache key creation, mostly
	 * @param array $query The query parameters for the API request
	 * @param int $cacheTTL Time to live for the memcached caching
	 * @return string|null
	 */
	public function httpGetCached( $attribute, $query, $cacheTTL = 3600 ) {
		if ( $this->mApiBase ) {
			$url = wfAppendQuery( $this->mApiBase, $query );
		} else {
			$url = $this->makeUrl( $query, 'api' );
		}

		return $this->wanCache->getWithSetCallback(
			// Allow reusing the same cached data across wikis (T285271).
			// This does not use getSharedCacheKey() because caching here
			// is transparent to client wikis (which are not expected to issue purges).
			$this->wanCache->makeGlobalKey( "filerepo-$attribute", sha1( $url ) ),
			$cacheTTL,
			function ( $curValue, &$ttl ) use ( $url ) {
				$html = self::httpGet( $url, 'default', [], $mtime );
				// FIXME: This should use the mtime from the api response body
				// not the mtime from the last-modified header which usually is not set.
				if ( $html !== false ) {
					$ttl = $mtime ? $this->wanCache->adaptiveTTL( $mtime, $ttl ) : $ttl;
				} else {
					$ttl = $this->wanCache->adaptiveTTL( $mtime, $ttl );
					$html = null; // caches negatives
				}

				return $html;
			},
			[ 'pcGroup' => 'http-get:3', 'pcTTL' => WANObjectCache::TTL_PROC_LONG ]
		);
	}

	/**
	 * @param callable $callback
	 * @return never
	 */
	public function enumFiles( $callback ): never {
		throw new RuntimeException( 'enumFiles is not supported by ' . static::class );
	}

	protected function assertWritableRepo(): never {
		throw new LogicException( static::class . ': write operations are not supported.' );
	}
}

/** @deprecated class alias since 1.44 */
class_alias( ForeignAPIRepo::class, 'ForeignAPIRepo' );
