<?php
/**
 * Handler for DjVu images.
 *
 * @license GPL-2.0-or-later
 * @file
 * @ingroup Media
 */

use MediaWiki\FileRepo\File\File;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\PoolCounter\PoolCounterWorkViaCallback;
use MediaWiki\Shell\Shell;

/**
 * Handler for DjVu images
 *
 * @ingroup Media
 */
class DjVuHandler extends ImageHandler {
	private const EXPENSIVE_SIZE_LIMIT = 10_485_760; // 10MiB

	// Constants for getHandlerState
	private const STATE_DJVU_IMAGE = 'djvuImage';
	private const STATE_TEXT_TREE = 'djvuTextTree';
	private const STATE_META_TREE = 'djvuMetaTree';
	private const CACHE_VERSION = 'v2';

	/**
	 * @return bool
	 */
	public function isEnabled() {
		$djvuRenderer = MediaWikiServices::getInstance()->getMainConfig()->get( MainConfigNames::DjvuRenderer );
		$djvuDump = MediaWikiServices::getInstance()->getMainConfig()->get( MainConfigNames::DjvuDump );
		if ( !$djvuRenderer || !$djvuDump ) {
			wfDebug( "DjVu is disabled, please set \$wgDjvuRenderer and \$wgDjvuDump" );

			return false;
		}
		return true;
	}

	/**
	 * @param File $file
	 * @return bool
	 */
	public function mustRender( $file ) {
		return true;
	}

	/**
	 * True if creating thumbnails from the file is large or otherwise resource-intensive.
	 * @param File $file
	 * @return bool
	 */
	public function isExpensiveToThumbnail( $file ) {
		return $file->getSize() > static::EXPENSIVE_SIZE_LIMIT;
	}

	/**
	 * @param File $file
	 * @return bool
	 */
	public function isMultiPage( $file ) {
		return true;
	}

	/**
	 * @return array
	 */
	public function getParamMap() {
		return [
			'img_width' => 'width',
			'img_page' => 'page',
		];
	}

	/**
	 * @param string $name
	 * @param mixed $value
	 * @return bool
	 */
	public function validateParam( $name, $value ) {
		if ( $name === 'page' && trim( $value ) !== (string)intval( $value ) ) {
			// Extra junk on the end of page, probably actually a caption
			// e.g. [[File:Foo.djvu|thumb|Page 3 of the document shows foo]]
			return false;
		}
		return in_array( $name, [ 'width', 'height', 'page' ] ) && $value > 0;
	}

	/**
	 * @param array $params
	 * @return string|false
	 */
	public function makeParamString( $params ) {
		$page = $params['page'] ?? 1;
		if ( !isset( $params['width'] ) ) {
			return false;
		}

		return "page{$page}-{$params['width']}px";
	}

	/**
	 * @param string $str
	 * @return array|false
	 */
	public function parseParamString( $str ) {
		$m = false;
		if ( preg_match( '/^page(\d+)-(\d+)px$/', $str, $m ) ) {
			return [ 'width' => $m[2], 'page' => $m[1] ];
		}
		return false;
	}

	/**
	 * @param array $params
	 * @return array
	 */
	protected function getScriptParams( $params ) {
		return [
			'width' => $params['width'],
			'page' => $params['page'],
		];
	}

	/**
	 * @param File $image
	 * @param string $dstPath
	 * @param string $dstUrl
	 * @param array $params
	 * @param int $flags
	 * @return MediaTransformError|ThumbnailImage|TransformParameterError
	 */
	public function doTransform( $image, $dstPath, $dstUrl, $params, $flags = 0 ) {
		$djvuRenderer = MediaWikiServices::getInstance()->getMainConfig()->get( MainConfigNames::DjvuRenderer );
		$djvuPostProcessor = MediaWikiServices::getInstance()->getMainConfig()
			->get( MainConfigNames::DjvuPostProcessor );
		if ( !$this->normaliseParams( $image, $params ) ) {
			return new TransformParameterError( $params );
		}
		$width = $params['width'];
		$height = $params['height'];
		$page = $params['page'];

		if ( $flags & self::TRANSFORM_LATER ) {
			$params = [
				'width' => $width,
				'height' => $height,
				'page' => $page
			];

			return new ThumbnailImage( $image, $dstUrl, $dstPath, $params );
		}

		if ( !wfMkdirParents( dirname( $dstPath ), null, __METHOD__ ) ) {
			return new MediaTransformError(
				'thumbnail_error',
				$width,
				$height,
				wfMessage( 'thumbnail_dest_directory' )
			);
		}

		// Get local copy source for shell scripts
		// Thumbnail extraction is very inefficient for large files.
		// Provide a way to pool count limit the number of downloaders.
		if ( $image->getSize() >= 1e7 ) { // 10 MB
			$work = new PoolCounterWorkViaCallback( 'GetLocalFileCopy', sha1( $image->getName() ),
				[
					'doWork' => static function () use ( $image ) {
						return $image->getLocalRefPath();
					}
				]
			);
			$srcPath = $work->execute();
		} else {
			$srcPath = $image->getLocalRefPath();
		}

		if ( $srcPath === false ) { // Failed to get local copy
			wfDebugLog( 'thumbnail',
				sprintf( 'Thumbnail failed on %s: could not get local copy of "%s"',
					wfHostname(), $image->getName() ) );

			return new MediaTransformError( 'thumbnail_error',
				$params['width'], $params['height'],
				wfMessage( 'filemissing' )
			);
		}

		# Use a subshell (brackets) to aggregate stderr from both pipeline commands
		# before redirecting it to the overall stdout. This works in both Linux and Windows XP.
		$cmd = '(' . Shell::escape(
			$djvuRenderer,
			"-format=ppm",
			"-page={$page}",
			"-size={$params['physicalWidth']}x{$params['physicalHeight']}",
			$srcPath );
		if ( $djvuPostProcessor ) {
			$cmd .= " | {$djvuPostProcessor}";
		}
		$cmd .= ' > ' . Shell::escape( $dstPath ) . ') 2>&1';
		wfDebug( __METHOD__ . ": $cmd" );
		$retval = 0;
		$err = wfShellExec( $cmd, $retval );

		$removed = $this->removeBadFile( $dstPath, $retval );
		if ( $retval !== 0 || $removed ) {
			$this->logErrorForExternalProcess( $retval, $err, $cmd );
			return new MediaTransformError( 'thumbnail_error', $width, $height, $err );
		}
		$params = [
			'width' => $width,
			'height' => $height,
			'page' => $page
		];

		return new ThumbnailImage( $image, $dstUrl, $dstPath, $params );
	}

	/**
	 * Cache an instance of DjVuImage in a MediaHandlerState object, return
	 * that instance
	 *
	 * @param MediaHandlerState $state
	 * @param string $path
	 * @return DjVuImage
	 */
	private function getDjVuImage( $state, $path ) {
		$deja = $state->getHandlerState( self::STATE_DJVU_IMAGE );
		if ( !$deja ) {
			$deja = new DjVuImage( $path );
			$state->setHandlerState( self::STATE_DJVU_IMAGE, $deja );
		}
		return $deja;
	}

	/**
	 * Get metadata, unserializing it if necessary.
	 *
	 * @param File $file The DjVu file in question
	 * @param bool $gettext
	 * @return string|false|array metadata
	 */
	private function getMetadataInternal( File $file, $gettext ) {
		$itemNames = [ 'error', '_error', 'data' ];
		if ( $gettext ) {
			$itemNames[] = 'text';
		}
		$unser = $file->getMetadataItems( $itemNames );

		if ( isset( $unser['error'] ) ) {
			return false;
		}
		if ( isset( $unser['_error'] ) ) {
			return false;
		}
		return $unser;
	}

	/**
	 * Cache a document tree for the DjVu metadata
	 * @param File $image
	 * @param bool $gettext DOCUMENT (Default: false)
	 * @return false|array
	 */
	public function getMetaTree( $image, $gettext = false ) {
		if ( $gettext && $image->getHandlerState( self::STATE_TEXT_TREE ) ) {
			return $image->getHandlerState( self::STATE_TEXT_TREE );
		}
		if ( !$gettext && $image->getHandlerState( self::STATE_META_TREE ) ) {
			return $image->getHandlerState( self::STATE_META_TREE );
		}

		$metadata = $this->getMetadataInternal( $image, $gettext );
		if ( !$metadata ) {
			return false;
		}

		if ( !$gettext ) {
			unset( $metadata['text'] );
		}
		return $metadata;
	}

	/** @inheritDoc */
	public function getThumbType( $ext, $mime, $params = null ) {
		$djvuOutputExtension = MediaWikiServices::getInstance()->getMainConfig()
			->get( MainConfigNames::DjvuOutputExtension );
		static $djvuMime = null;
		if ( $djvuMime === null ) {
			$magic = MediaWikiServices::getInstance()->getMimeAnalyzer();
			$djvuMime = $magic->getMimeTypeFromExtensionOrNull( $djvuOutputExtension );
		}

		return [ $djvuOutputExtension, $djvuMime ];
	}

	/** @inheritDoc */
	public function getSizeAndMetadata( $state, $path ) {
		wfDebug( "Getting DjVu metadata for $path" );

		$djvuImage = $this->getDjVuImage( $state, $path );
		$metadata = $djvuImage->retrieveMetaData();
		if ( $metadata === false ) {
			// Special value so that we don't repetitively try and decode a broken file.
			$metadata = [ 'error' => 'Error extracting metadata' ];
		}
		return [ 'metadata' => $metadata ] + $djvuImage->getImageSize();
	}

	/** @inheritDoc */
	public function getMetadataType( $image ) {
		// historical reasons
		return 'djvuxml';
	}

	/** @inheritDoc */
	public function isFileMetadataValid( $image ) {
		return $image->getMetadataArray() ? self::METADATA_GOOD : self::METADATA_BAD;
	}

	/** @inheritDoc */
	public function pageCount( File $image ) {
		$info = $this->getDimensionInfo( $image );

		return $info ? $info['pageCount'] : false;
	}

	/** @inheritDoc */
	public function getPageDimensions( File $image, $page ) {
		$index = $page - 1; // MW starts pages at 1

		$info = $this->getDimensionInfo( $image );
		if ( $info && isset( $info['dimensionsByPage'][$index] ) ) {
			return $info['dimensionsByPage'][$index];
		}

		return false;
	}

	/** @inheritDoc */
	protected function getDimensionInfo( File $file ) {
		$cache = MediaWikiServices::getInstance()->getMainWANObjectCache();
		return $cache->getWithSetCallback(
			$cache->makeKey( 'file-djvu', 'dimensions', self::CACHE_VERSION, $file->getSha1() ),
			$cache::TTL_INDEFINITE,
			function () use ( $file ) {
				$tree = $this->getMetaTree( $file );
				return $this->getDimensionInfoFromMetaTree( $tree );
			},
			[ 'pcTTL' => $cache::TTL_INDEFINITE ]
		);
	}

	/**
	 * Given the metadata, returns dimension information about the document
	 * @param false|array $metatree The file's metadata tree
	 * @return array|false
	 */
	protected function getDimensionInfoFromMetaTree( $metatree ) {
		if ( !$metatree ) {
			return false;
		}
		$dimsByPage = [];

		if ( !isset( $metatree['data'] ) || !$metatree['data'] ) {
			return false;
		}
		foreach ( $metatree['data']['pages'] as $page ) {
			if ( !$page ) {
				$dimsByPage[] = false;
			} else {
				$dimsByPage[] = [
					'width' => (int)$page['width'],
					'height' => (int)$page['height'],
				];
			}
		}
		return [
			'pageCount' => count( $metatree['data']['pages'] ),
			'dimensionsByPage' => $dimsByPage
		];
	}

	/**
	 * @param File $image
	 * @param int $page Page number to get information for
	 * @return string|false Page text or false when no text found.
	 */
	public function getPageText( File $image, $page ) {
		$tree = $this->getMetaTree( $image, true );
		if ( !$tree ) {
			return false;
		}
		if ( isset( $tree['text'] ) && isset( $tree['text'][$page - 1] ) ) {
			return $tree['text'][$page - 1];
		}
		return false;
	}

	/** @inheritDoc */
	public function useSplitMetadata() {
		return true;
	}
}
