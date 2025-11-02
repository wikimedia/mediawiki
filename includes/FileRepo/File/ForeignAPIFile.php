<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\FileRepo\File;

use MediaHandler;
use MediaTransformOutput;
use MediaWiki\FileRepo\ForeignAPIRepo;
use MediaWiki\MediaWikiServices;
use MediaWiki\Permissions\Authority;
use MediaWiki\Title\Title;
use MediaWiki\User\UserIdentity;
use MediaWiki\User\UserIdentityValue;

/**
 * Foreign file accessible through api.php requests.
 *
 * @ingroup FileAbstraction
 */
class ForeignAPIFile extends File {
	/** @var bool */
	private $mExists;
	/** @var array */
	private $mInfo;

	/** @inheritDoc */
	protected $repoClass = ForeignAPIRepo::class;

	/**
	 * @param Title|string|false $title
	 * @param ForeignApiRepo $repo
	 * @param array $info
	 * @param bool $exists
	 */
	public function __construct( $title, $repo, $info, $exists = false ) {
		parent::__construct( $title, $repo );

		$this->mInfo = $info;
		$this->mExists = $exists;

		$this->assertRepoDefined();
	}

	/**
	 * @param Title $title
	 * @param ForeignApiRepo $repo
	 * @return ForeignAPIFile|null
	 */
	public static function newFromTitle( Title $title, $repo ) {
		$data = $repo->fetchImageQuery( [
			'titles' => 'File:' . $title->getDBkey(),
			'iiprop' => self::getProps(),
			'prop' => 'imageinfo',
			'iimetadataversion' => MediaHandler::getMetadataVersion(),
			// extmetadata is language-dependent, accessing the current language here
			// would be problematic, so we just get them all
			'iiextmetadatamultilang' => 1,
		] );

		$info = $repo->getImageInfo( $data );

		if ( $info ) {
			$lastRedirect = count( $data['query']['redirects'] ?? [] ) - 1;
			if ( $lastRedirect >= 0 ) {
				// @phan-suppress-next-line PhanTypeArraySuspiciousNullable
				$newtitle = Title::newFromText( $data['query']['redirects'][$lastRedirect]['to'] );
				$img = new self( $newtitle, $repo, $info, true );
				$img->redirectedFrom( $title->getDBkey() );
			} else {
				$img = new self( $title, $repo, $info, true );
			}

			return $img;
		} else {
			return null;
		}
	}

	/**
	 * Get the property string for iiprop and aiprop
	 * @return string
	 */
	public static function getProps() {
		return 'timestamp|user|comment|url|size|sha1|metadata|mime|mediatype|extmetadata';
	}

	/**
	 * @return ForeignAPIRepo|false
	 */
	public function getRepo() {
		return $this->repo;
	}

	// Dummy functions...

	/**
	 * @return bool
	 */
	public function exists() {
		return $this->mExists;
	}

	/**
	 * @return bool
	 */
	public function getPath() {
		return false;
	}

	/**
	 * @param array $params
	 * @param int $flags
	 * @return MediaTransformOutput|false
	 */
	public function transform( $params, $flags = 0 ) {
		if ( !$this->canRender() ) {
			// show icon
			return parent::transform( $params, $flags );
		}

		// Note, the this->canRender() check above implies
		// that we have a handler, and it can do makeParamString.
		$otherParams = $this->handler->makeParamString( $params );
		$width = $params['width'] ?? -1;
		$height = $params['height'] ?? -1;
		$thumbUrl = false;

		if ( $width > 0 || $height > 0 ) {
			// Only query the remote if there are dimensions
			$thumbUrl = $this->repo->getThumbUrlFromCache(
				$this->getName(),
				$width,
				$height,
				$otherParams
			);
		} elseif ( $this->getMediaType() === MEDIATYPE_AUDIO ) {
			// This has no dimensions, but we still need to pass a value to getTransform()
			$thumbUrl = '/';
		}
		if ( $thumbUrl === false ) {
			global $wgLang;

			return $this->repo->getThumbError(
				$this->getName(),
				$width,
				$height,
				$otherParams,
				$wgLang->getCode()
			);
		}

		return $this->handler->getTransform( $this, 'bogus', $thumbUrl, $params );
	}

	// Info we can get from API...

	/**
	 * @param int $page
	 * @return int
	 */
	public function getWidth( $page = 1 ) {
		return (int)( $this->mInfo['width'] ?? 0 );
	}

	/**
	 * @param int $page
	 * @return int
	 */
	public function getHeight( $page = 1 ) {
		return (int)( $this->mInfo['height'] ?? 0 );
	}

	/**
	 * @return string|false
	 */
	public function getMetadata() {
		if ( isset( $this->mInfo['metadata'] ) ) {
			return serialize( self::parseMetadata( $this->mInfo['metadata'] ) );
		}

		return false;
	}

	public function getMetadataArray(): array {
		if ( isset( $this->mInfo['metadata'] ) ) {
			return self::parseMetadata( $this->mInfo['metadata'] );
		}

		return [];
	}

	/**
	 * @return array|null Extended metadata (see imageinfo API for format) or
	 *   null on error
	 */
	public function getExtendedMetadata() {
		return $this->mInfo['extmetadata'] ?? null;
	}

	/**
	 * @param mixed $metadata
	 * @return array
	 */
	public static function parseMetadata( $metadata ) {
		if ( !is_array( $metadata ) ) {
			return [ '_error' => $metadata ];
		}
		'@phan-var array[] $metadata';
		$ret = [];
		foreach ( $metadata as $meta ) {
			$ret[$meta['name']] = self::parseMetadataValue( $meta['value'] );
		}

		return $ret;
	}

	/**
	 * @param mixed $metadata
	 * @return mixed
	 */
	private static function parseMetadataValue( $metadata ) {
		if ( !is_array( $metadata ) ) {
			return $metadata;
		}
		'@phan-var array[] $metadata';
		$ret = [];
		foreach ( $metadata as $meta ) {
			$ret[$meta['name']] = self::parseMetadataValue( $meta['value'] );
		}

		return $ret;
	}

	/**
	 * @return int|null|false
	 */
	public function getSize() {
		return isset( $this->mInfo['size'] ) ? intval( $this->mInfo['size'] ) : null;
	}

	/**
	 * @return null|string
	 */
	public function getUrl() {
		return isset( $this->mInfo['url'] ) ? strval( $this->mInfo['url'] ) : null;
	}

	/**
	 * Get short description URL for a file based on the foreign API response,
	 * or if unavailable, the short URL is constructed from the foreign page ID.
	 *
	 * @return null|string
	 * @since 1.27
	 */
	public function getDescriptionShortUrl() {
		if ( isset( $this->mInfo['descriptionshorturl'] ) ) {
			return $this->mInfo['descriptionshorturl'];
		} elseif ( isset( $this->mInfo['pageid'] ) ) {
			$url = $this->repo->makeUrl( [ 'curid' => $this->mInfo['pageid'] ] );
			if ( $url !== false ) {
				return $url;
			}
		}
		return null;
	}

	public function getUploader( int $audience = self::FOR_PUBLIC, ?Authority $performer = null ): ?UserIdentity {
		if ( isset( $this->mInfo['user'] ) ) {
			return UserIdentityValue::newExternal( $this->getRepoName(), $this->mInfo['user'] );
		}
		return null;
	}

	/**
	 * @param int $audience
	 * @param Authority|null $performer
	 * @return null|string
	 */
	public function getDescription( $audience = self::FOR_PUBLIC, ?Authority $performer = null ) {
		return isset( $this->mInfo['comment'] ) ? strval( $this->mInfo['comment'] ) : null;
	}

	/**
	 * @return null|string
	 */
	public function getSha1() {
		return isset( $this->mInfo['sha1'] )
			? \Wikimedia\base_convert( strval( $this->mInfo['sha1'] ), 16, 36, 31 )
			: null;
	}

	/**
	 * @return string|false
	 */
	public function getTimestamp() {
		return wfTimestamp( TS_MW,
			isset( $this->mInfo['timestamp'] )
				? strval( $this->mInfo['timestamp'] )
				: null
		);
	}

	/**
	 * @return string
	 */
	public function getMimeType() {
		if ( !isset( $this->mInfo['mime'] ) ) {
			$magic = MediaWikiServices::getInstance()->getMimeAnalyzer();
			$this->mInfo['mime'] = $magic->getMimeTypeFromExtensionOrNull( $this->getExtension() );
		}

		return $this->mInfo['mime'];
	}

	/**
	 * @return int|string
	 */
	public function getMediaType() {
		if ( isset( $this->mInfo['mediatype'] ) ) {
			return $this->mInfo['mediatype'];
		}
		$magic = MediaWikiServices::getInstance()->getMimeAnalyzer();

		return $magic->getMediaType( null, $this->getMimeType() );
	}

	/**
	 * @return string|false
	 */
	public function getDescriptionUrl() {
		return $this->mInfo['descriptionurl'] ?? false;
	}

	/**
	 * Only useful if we're locally caching thumbs anyway...
	 * @param string $suffix
	 * @return null|string
	 */
	public function getThumbPath( $suffix = '' ) {
		if ( !$this->repo->canCacheThumbs() ) {
			return null;
		}

		$path = $this->repo->getZonePath( 'thumb' ) . '/' . $this->getHashPath();
		if ( $suffix ) {
			$path .= $suffix . '/';
		}
		return $path;
	}

	/**
	 * @return string[]
	 */
	protected function getThumbnails() {
		$dir = $this->getThumbPath( $this->getName() );
		$iter = $this->repo->getBackend()->getFileList( [ 'dir' => $dir ] );

		$files = [];
		if ( $iter ) {
			foreach ( $iter as $file ) {
				$files[] = $file;
			}
		}

		return $files;
	}

	/** @inheritDoc */
	public function purgeCache( $options = [] ) {
		$this->purgeThumbnails( $options );
		$this->purgeDescriptionPage();
	}

	private function purgeDescriptionPage() {
		$services = MediaWikiServices::getInstance();
		$langCode = $services->getContentLanguageCode()->toString();

		// Key must match File::getDescriptionText
		$key = $this->repo->getLocalCacheKey( 'file-remote-description', $langCode, md5( $this->getName() ) );
		$services->getMainWANObjectCache()->delete( $key );
	}

	/**
	 * @param array $options
	 */
	public function purgeThumbnails( $options = [] ) {
		$key = $this->repo->getLocalCacheKey( 'file-thumb-url', sha1( $this->getName() ) );
		MediaWikiServices::getInstance()->getMainWANObjectCache()->delete( $key );

		$files = $this->getThumbnails();
		// Give media handler a chance to filter the purge list
		$handler = $this->getHandler();
		if ( $handler ) {
			$handler->filterThumbnailPurgeList( $files, $options );
		}

		$dir = $this->getThumbPath( $this->getName() );
		$purgeList = [];
		foreach ( $files as $file ) {
			$purgeList[] = "{$dir}{$file}";
		}

		# Delete the thumbnails
		$this->repo->quickPurgeBatch( $purgeList );
		# Clear out the thumbnail directory if empty
		$this->repo->quickCleanDir( $dir );
	}

	/**
	 * The thumbnail is created on the foreign server and fetched over internet
	 * @since 1.25
	 * @return bool
	 */
	public function isTransformedLocally() {
		return false;
	}
}

/** @deprecated class alias since 1.44 */
class_alias( ForeignAPIFile::class, 'ForeignAPIFile' );
