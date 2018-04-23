<?php
/**
 * Foreign file accessible through api.php requests.
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
 * @ingroup FileAbstraction
 */

/**
 * Foreign file accessible through api.php requests.
 * Very hacky and inefficient, do not use :D
 *
 * @ingroup FileAbstraction
 */
class ForeignAPIFile extends File {
	/** @var bool */
	private $mExists;
	/** @var array */
	private $mInfo = [];

	protected $repoClass = ForeignApiRepo::class;

	/**
	 * @param Title|string|bool $title
	 * @param ForeignApiRepo $repo
	 * @param array $info
	 * @param bool $exists
	 */
	function __construct( $title, $repo, $info, $exists = false ) {
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
	static function newFromTitle( Title $title, $repo ) {
		$data = $repo->fetchImageQuery( [
			'titles' => 'File:' . $title->getDBkey(),
			'iiprop' => self::getProps(),
			'prop' => 'imageinfo',
			'iimetadataversion' => MediaHandler::getMetadataVersion(),
			// extmetadata is language-dependant, accessing the current language here
			// would be problematic, so we just get them all
			'iiextmetadatamultilang' => 1,
		] );

		$info = $repo->getImageInfo( $data );

		if ( $info ) {
			$lastRedirect = isset( $data['query']['redirects'] )
				? count( $data['query']['redirects'] ) - 1
				: -1;
			if ( $lastRedirect >= 0 ) {
				$newtitle = Title::newFromText( $data['query']['redirects'][$lastRedirect]['to'] );
				$img = new self( $newtitle, $repo, $info, true );
				if ( $img ) {
					$img->redirectedFrom( $title->getDBkey() );
				}
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
	static function getProps() {
		return 'timestamp|user|comment|url|size|sha1|metadata|mime|mediatype|extmetadata';
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
	 * @return bool|MediaTransformOutput
	 */
	function transform( $params, $flags = 0 ) {
		if ( !$this->canRender() ) {
			// show icon
			return parent::transform( $params, $flags );
		}

		// Note, the this->canRender() check above implies
		// that we have a handler, and it can do makeParamString.
		$otherParams = $this->handler->makeParamString( $params );
		$width = isset( $params['width'] ) ? $params['width'] : -1;
		$height = isset( $params['height'] ) ? $params['height'] : -1;

		$thumbUrl = $this->repo->getThumbUrlFromCache(
			$this->getName(),
			$width,
			$height,
			$otherParams
		);
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
	 * @return int|number
	 */
	public function getWidth( $page = 1 ) {
		return isset( $this->mInfo['width'] ) ? intval( $this->mInfo['width'] ) : 0;
	}

	/**
	 * @param int $page
	 * @return int
	 */
	public function getHeight( $page = 1 ) {
		return isset( $this->mInfo['height'] ) ? intval( $this->mInfo['height'] ) : 0;
	}

	/**
	 * @return bool|null|string
	 */
	public function getMetadata() {
		if ( isset( $this->mInfo['metadata'] ) ) {
			return serialize( self::parseMetadata( $this->mInfo['metadata'] ) );
		}

		return null;
	}

	/**
	 * @return array|null Extended metadata (see imageinfo API for format) or
	 *   null on error
	 */
	public function getExtendedMetadata() {
		if ( isset( $this->mInfo['extmetadata'] ) ) {
			return $this->mInfo['extmetadata'];
		}

		return null;
	}

	/**
	 * @param mixed $metadata
	 * @return mixed
	 */
	public static function parseMetadata( $metadata ) {
		if ( !is_array( $metadata ) ) {
			return $metadata;
		}
		$ret = [];
		foreach ( $metadata as $meta ) {
			$ret[$meta['name']] = self::parseMetadata( $meta['value'] );
		}

		return $ret;
	}

	/**
	 * @return bool|int|null
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

	/**
	 * @param string $type
	 * @return int|null|string
	 */
	public function getUser( $type = 'text' ) {
		if ( $type == 'text' ) {
			return isset( $this->mInfo['user'] ) ? strval( $this->mInfo['user'] ) : null;
		} else {
			return 0; // What makes sense here, for a remote user?
		}
	}

	/**
	 * @param int $audience
	 * @param User|null $user
	 * @return null|string
	 */
	public function getDescription( $audience = self::FOR_PUBLIC, User $user = null ) {
		return isset( $this->mInfo['comment'] ) ? strval( $this->mInfo['comment'] ) : null;
	}

	/**
	 * @return null|string
	 */
	function getSha1() {
		return isset( $this->mInfo['sha1'] )
			? Wikimedia\base_convert( strval( $this->mInfo['sha1'] ), 16, 36, 31 )
			: null;
	}

	/**
	 * @return bool|string
	 */
	function getTimestamp() {
		return wfTimestamp( TS_MW,
			isset( $this->mInfo['timestamp'] )
				? strval( $this->mInfo['timestamp'] )
				: null
		);
	}

	/**
	 * @return string
	 */
	function getMimeType() {
		if ( !isset( $this->mInfo['mime'] ) ) {
			$magic = MediaWiki\MediaWikiServices::getInstance()->getMimeAnalyzer();
			$this->mInfo['mime'] = $magic->guessTypesForExtension( $this->getExtension() );
		}

		return $this->mInfo['mime'];
	}

	/**
	 * @return int|string
	 */
	function getMediaType() {
		if ( isset( $this->mInfo['mediatype'] ) ) {
			return $this->mInfo['mediatype'];
		}
		$magic = MediaWiki\MediaWikiServices::getInstance()->getMimeAnalyzer();

		return $magic->getMediaType( null, $this->getMimeType() );
	}

	/**
	 * @return bool|string
	 */
	function getDescriptionUrl() {
		return isset( $this->mInfo['descriptionurl'] )
			? $this->mInfo['descriptionurl']
			: false;
	}

	/**
	 * Only useful if we're locally caching thumbs anyway...
	 * @param string $suffix
	 * @return null|string
	 */
	function getThumbPath( $suffix = '' ) {
		if ( $this->repo->canCacheThumbs() ) {
			$path = $this->repo->getZonePath( 'thumb' ) . '/' . $this->getHashPath( $this->getName() );
			if ( $suffix ) {
				$path = $path . $suffix . '/';
			}

			return $path;
		} else {
			return null;
		}
	}

	/**
	 * @return string[]
	 */
	function getThumbnails() {
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

	function purgeCache( $options = [] ) {
		$this->purgeThumbnails( $options );
		$this->purgeDescriptionPage();
	}

	function purgeDescriptionPage() {
		global $wgContLang;

		$url = $this->repo->getDescriptionRenderUrl( $this->getName(), $wgContLang->getCode() );
		$key = $this->repo->getLocalCacheKey( 'RemoteFileDescription', 'url', md5( $url ) );

		ObjectCache::getMainWANInstance()->delete( $key );
	}

	/**
	 * @param array $options
	 */
	function purgeThumbnails( $options = [] ) {
		$key = $this->repo->getLocalCacheKey( 'ForeignAPIRepo', 'ThumbUrl', $this->getName() );
		ObjectCache::getMainWANInstance()->delete( $key );

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
