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
	private $mExists;

	protected $repoClass = 'ForeignApiRepo';

	/**
	 * @param $title
	 * @param $repo ForeignApiRepo
	 * @param $info
	 * @param bool $exists
	 */
	function __construct( $title, $repo, $info, $exists = false ) {
		parent::__construct( $title, $repo );

		$this->mInfo = $info;
		$this->mExists = $exists;

		$this->assertRepoDefined();
	}

	/**
	 * @param $title Title
	 * @param $repo ForeignApiRepo
	 * @return ForeignAPIFile|null
	 */
	static function newFromTitle( Title $title, $repo ) {
		$data = $repo->fetchImageQuery( array(
			'titles'            => 'File:' . $title->getDBKey(),
			'iiprop'            => self::getProps(),
			'prop'              => 'imageinfo',
			'iimetadataversion' => MediaHandler::getMetadataVersion()
		) );

		$info = $repo->getImageInfo( $data );

		if( $info ) {
			$lastRedirect = isset( $data['query']['redirects'] )
				? count( $data['query']['redirects'] ) - 1
				: -1;
			if( $lastRedirect >= 0 ) {
				$newtitle = Title::newFromText( $data['query']['redirects'][$lastRedirect]['to']);
				$img = new self( $newtitle, $repo, $info, true );
				if( $img ) {
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
		return 'timestamp|user|comment|url|size|sha1|metadata|mime';
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
	 * @param Array $params
	 * @param int $flags
	 * @return bool|MediaTransformOutput
	 */
	function transform( $params, $flags = 0 ) {
		if( !$this->canRender() ) {
			// show icon
			return parent::transform( $params, $flags );
		}

		// Note, the this->canRender() check above implies
		// that we have a handler, and it can do makeParamString.
		$otherParams = $this->handler->makeParamString( $params );

		$thumbUrl = $this->repo->getThumbUrlFromCache(
			$this->getName(),
			isset( $params['width'] ) ? $params['width'] : -1,
			isset( $params['height'] ) ? $params['height'] : -1,
			$otherParams );
		return $this->handler->getTransform( $this, 'bogus', $thumbUrl, $params );
	}

	// Info we can get from API...

	/**
	 * @param $page int
	 * @return int|number
	 */
	public function getWidth( $page = 1 ) {
		return isset( $this->mInfo['width'] ) ? intval( $this->mInfo['width'] ) : 0;
	}

	/**
	 * @param $page int
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
	 * @param $metadata array
	 * @return array
	 */
	public static function parseMetadata( $metadata ) {
		if( !is_array( $metadata ) ) {
			return $metadata;
		}
		$ret = array();
		foreach( $metadata as $meta ) {
			$ret[ $meta['name'] ] = self::parseMetadata( $meta['value'] );
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
	 * @param string $method
	 * @return int|null|string
	 */
	public function getUser( $method='text' ) {
		return isset( $this->mInfo['user'] ) ? strval( $this->mInfo['user'] ) : null;
	}

	/**
	 * @return null|string
	 */
	public function getDescription( $audience = self::FOR_PUBLIC, User $user = null ) {
		return isset( $this->mInfo['comment'] ) ? strval( $this->mInfo['comment'] ) : null;
	}

	/**
	 * @return null|String
	 */
	function getSha1() {
		return isset( $this->mInfo['sha1'] )
			? wfBaseConvert( strval( $this->mInfo['sha1'] ), 16, 36, 31 )
			: null;
	}

	/**
	 * @return bool|Mixed|string
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
		if( !isset( $this->mInfo['mime'] ) ) {
			$magic = MimeMagic::singleton();
			$this->mInfo['mime'] = $magic->guessTypesForExtension( $this->getExtension() );
		}
		return $this->mInfo['mime'];
	}

	/**
	 * @todo FIXME: May guess wrong on file types that can be eg audio or video
	 * @return int|string
	 */
	function getMediaType() {
		$magic = MimeMagic::singleton();
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
	 * @param $suffix string
	 * @return null|string
	 */
	function getThumbPath( $suffix = '' ) {
		if ( $this->repo->canCacheThumbs() ) {
			$path = $this->repo->getZonePath('thumb') . '/' . $this->getHashPath( $this->getName() );
			if ( $suffix ) {
				$path = $path . $suffix . '/';
			}
			return $path;
		} else {
			return null;
		}
	}

	/**
	 * @return array
	 */
	function getThumbnails() {
		$dir = $this->getThumbPath( $this->getName() );
		$iter = $this->repo->getBackend()->getFileList( array( 'dir' => $dir ) );

		$files = array();
		foreach ( $iter as $file ) {
			$files[] = $file;
		}

		return $files;
	}

	/**
	 * @see File::purgeCache()
	 */
	function purgeCache( $options = array() ) {
		$this->purgeThumbnails( $options );
		$this->purgeDescriptionPage();
	}

	function purgeDescriptionPage() {
		global $wgMemc, $wgContLang;

		$url = $this->repo->getDescriptionRenderUrl( $this->getName(), $wgContLang->getCode() );
		$key = $this->repo->getLocalCacheKey( 'RemoteFileDescription', 'url', md5($url) );

		$wgMemc->delete( $key );
	}

	/**
	 * @param $options array
	 */
	function purgeThumbnails( $options = array() ) {
		global $wgMemc;

		$key = $this->repo->getLocalCacheKey( 'ForeignAPIRepo', 'ThumbUrl', $this->getName() );
		$wgMemc->delete( $key );

		$files = $this->getThumbnails();
		// Give media handler a chance to filter the purge list
		$handler = $this->getHandler();
		if ( $handler ) {
			$handler->filterThumbnailPurgeList( $files, $options );
		}

		$dir = $this->getThumbPath( $this->getName() );
		$purgeList = array();
		foreach ( $files as $file ) {
			$purgeList[] = "{$dir}{$file}";
		}

		# Delete the thumbnails
		$this->repo->quickPurgeBatch( $purgeList );
		# Clear out the thumbnail directory if empty
		$this->repo->quickCleanDir( $dir );
	}
}
