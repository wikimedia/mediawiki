<?php
/**
 * Handler for DjVu images.
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
 * @ingroup Media
 */

/**
 * Handler for DjVu images
 *
 * @ingroup Media
 */
class DjVuHandler extends ImageHandler {
	/**
	 * @return bool
	 */
	function isEnabled() {
		global $wgDjvuRenderer, $wgDjvuDump, $wgDjvuToXML;
		if ( !$wgDjvuRenderer || ( !$wgDjvuDump && !$wgDjvuToXML ) ) {
			wfDebug( "DjVu is disabled, please set \$wgDjvuRenderer and \$wgDjvuDump\n" );

			return false;
		} else {
			return true;
		}
	}

	/**
	 * @param File $file
	 * @return bool
	 */
	function mustRender( $file ) {
		return true;
	}

	/**
	 * @param File $file
	 * @return bool
	 */
	function isMultiPage( $file ) {
		return true;
	}

	/**
	 * @return array
	 */
	function getParamMap() {
		return array(
			'img_width' => 'width',
			'img_page' => 'page',
		);
	}

	/**
	 * @param string $name
	 * @param mixed $value
	 * @return bool
	 */
	function validateParam( $name, $value ) {
		if ( in_array( $name, array( 'width', 'height', 'page' ) ) ) {
			if ( $value <= 0 ) {
				return false;
			} else {
				return true;
			}
		} else {
			return false;
		}
	}

	/**
	 * @param array $params
	 * @return bool|string
	 */
	function makeParamString( $params ) {
		$page = isset( $params['page'] ) ? $params['page'] : 1;
		if ( !isset( $params['width'] ) ) {
			return false;
		}

		return "page{$page}-{$params['width']}px";
	}

	/**
	 * @param string $str
	 * @return array|bool
	 */
	function parseParamString( $str ) {
		$m = false;
		if ( preg_match( '/^page(\d+)-(\d+)px$/', $str, $m ) ) {
			return array( 'width' => $m[2], 'page' => $m[1] );
		} else {
			return false;
		}
	}

	/**
	 * @param array $params
	 * @return array
	 */
	function getScriptParams( $params ) {
		return array(
			'width' => $params['width'],
			'page' => $params['page'],
		);
	}

	/**
	 * @param File $image
	 * @param string $dstPath
	 * @param string $dstUrl
	 * @param array $params
	 * @param int $flags
	 * @return MediaTransformError|ThumbnailImage|TransformParameterError
	 */
	function doTransform( $image, $dstPath, $dstUrl, $params, $flags = 0 ) {
		global $wgDjvuRenderer, $wgDjvuPostProcessor;

		// Fetch XML and check it, to give a more informative error message than the one which
		// normaliseParams will inevitably give.
		$xml = $image->getMetadata();
		if ( !$xml ) {
			$width = isset( $params['width'] ) ? $params['width'] : 0;
			$height = isset( $params['height'] ) ? $params['height'] : 0;

			return new MediaTransformError( 'thumbnail_error', $width, $height,
				wfMessage( 'djvu_no_xml' )->text() );
		}

		if ( !$this->normaliseParams( $image, $params ) ) {
			return new TransformParameterError( $params );
		}
		$width = $params['width'];
		$height = $params['height'];
		$page = $params['page'];
		if ( $page > $this->pageCount( $image ) ) {
			return new MediaTransformError(
				'thumbnail_error',
				$width,
				$height,
				wfMessage( 'djvu_page_error' )->text()
			);
		}

		if ( $flags & self::TRANSFORM_LATER ) {
			$params = array(
				'width' => $width,
				'height' => $height,
				'page' => $page
			);

			return new ThumbnailImage( $image, $dstUrl, $dstPath, $params );
		}

		if ( !wfMkdirParents( dirname( $dstPath ), null, __METHOD__ ) ) {
			return new MediaTransformError(
				'thumbnail_error',
				$width,
				$height,
				wfMessage( 'thumbnail_dest_directory' )->text()
			);
		}

		// Get local copy source for shell scripts
		// Thumbnail extraction is very inefficient for large files.
		// Provide a way to pool count limit the number of downloaders.
		if ( $image->getSize() >= 1e7 ) { // 10MB
			$work = new PoolCounterWorkViaCallback( 'GetLocalFileCopy', sha1( $image->getName() ),
				array(
					'doWork' => function() use ( $image ) {
						return $image->getLocalRefPath();
					}
				)
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
				wfMessage( 'filemissing' )->text()
			);
		}

		# Use a subshell (brackets) to aggregate stderr from both pipeline commands
		# before redirecting it to the overall stdout. This works in both Linux and Windows XP.
		$cmd = '(' . wfEscapeShellArg(
			$wgDjvuRenderer,
			"-format=ppm",
			"-page={$page}",
			"-size={$params['physicalWidth']}x{$params['physicalHeight']}",
			$srcPath );
		if ( $wgDjvuPostProcessor ) {
			$cmd .= " | {$wgDjvuPostProcessor}";
		}
		$cmd .= ' > ' . wfEscapeShellArg( $dstPath ) . ') 2>&1';
		wfProfileIn( 'ddjvu' );
		wfDebug( __METHOD__ . ": $cmd\n" );
		$retval = '';
		$err = wfShellExec( $cmd, $retval );
		wfProfileOut( 'ddjvu' );

		$removed = $this->removeBadFile( $dstPath, $retval );
		if ( $retval != 0 || $removed ) {
			$this->logErrorForExternalProcess( $retval, $err, $cmd );
			return new MediaTransformError( 'thumbnail_error', $width, $height, $err );
		} else {
			$params = array(
				'width' => $width,
				'height' => $height,
				'page' => $page
			);

			return new ThumbnailImage( $image, $dstUrl, $dstPath, $params );
		}
	}

	/**
	 * Cache an instance of DjVuImage in an Image object, return that instance
	 *
	 * @param File $image
	 * @param string $path
	 * @return DjVuImage
	 */
	function getDjVuImage( $image, $path ) {
		if ( !$image ) {
			$deja = new DjVuImage( $path );
		} elseif ( !isset( $image->dejaImage ) ) {
			$deja = $image->dejaImage = new DjVuImage( $path );
		} else {
			$deja = $image->dejaImage;
		}

		return $deja;
	}

	/**
	 * Get metadata, unserializing it if neccessary.
	 *
	 * @param File $file The DjVu file in question
	 * @return String XML metadata as a string.
	 */
	private function getUnserializedMetadata( File $file ) {
		$metadata = $file->getMetadata();
		if ( substr( $metadata, 0, 3 ) === '<?xml' ) {
			// Old style. Not serialized but instead just a raw string of XML.
			return $metadata;
		}

		wfSuppressWarnings();
		$unser = unserialize( $metadata );
		wfRestoreWarnings();
		if ( is_array( $unser ) ) {
			return $unser['xml'];
		}

		// unserialize failed. Guess it wasn't really serialized after all,
		return $metadata;
	}

	/**
	 * Cache a document tree for the DjVu XML metadata
	 * @param File $image
	 * @param bool $gettext DOCUMENT (Default: false)
	 * @return bool|SimpleXMLElement
	 */
	function getMetaTree( $image, $gettext = false ) {
		if ( $gettext && isset( $image->djvuTextTree ) ) {
			return $image->djvuTextTree;
		}
		if ( !$gettext && isset( $image->dejaMetaTree ) ) {
			return $image->dejaMetaTree;
		}

		$metadata = $this->getUnserializedMetadata( $image );
		if ( !$this->isMetadataValid( $image, $metadata ) ) {
			wfDebug( "DjVu XML metadata is invalid or missing, should have been fixed in upgradeRow\n" );

			return false;
		}
		wfProfileIn( __METHOD__ );

		wfSuppressWarnings();
		try {
			// Set to false rather than null to avoid further attempts
			$image->dejaMetaTree = false;
			$image->djvuTextTree = false;
			$tree = new SimpleXMLElement( $metadata );
			if ( $tree->getName() == 'mw-djvu' ) {
				/** @var SimpleXMLElement $b */
				foreach ( $tree->children() as $b ) {
					if ( $b->getName() == 'DjVuTxt' ) {
						// @todo File::djvuTextTree and File::dejaMetaTree are declared
						// dynamically. Add a public File::$data to facilitate this?
						$image->djvuTextTree = $b;
					} elseif ( $b->getName() == 'DjVuXML' ) {
						$image->dejaMetaTree = $b;
					}
				}
			} else {
				$image->dejaMetaTree = $tree;
			}
		} catch ( Exception $e ) {
			wfDebug( "Bogus multipage XML metadata on '{$image->getName()}'\n" );
		}
		wfRestoreWarnings();
		wfProfileOut( __METHOD__ );
		if ( $gettext ) {
			return $image->djvuTextTree;
		} else {
			return $image->dejaMetaTree;
		}
	}

	/**
	 * @param File $image
	 * @param string $path
	 * @return bool|array False on failure
	 */
	function getImageSize( $image, $path ) {
		return $this->getDjVuImage( $image, $path )->getImageSize();
	}

	function getThumbType( $ext, $mime, $params = null ) {
		global $wgDjvuOutputExtension;
		static $mime;
		if ( !isset( $mime ) ) {
			$magic = MimeMagic::singleton();
			$mime = $magic->guessTypesForExtension( $wgDjvuOutputExtension );
		}

		return array( $wgDjvuOutputExtension, $mime );
	}

	function getMetadata( $image, $path ) {
		wfDebug( "Getting DjVu metadata for $path\n" );

		$xml = $this->getDjVuImage( $image, $path )->retrieveMetaData();
		if ( $xml === false ) {
			return false;
		} else {
			return serialize( array( 'xml' => $xml ) );
		}
	}

	function getMetadataType( $image ) {
		return 'djvuxml';
	}

	function isMetadataValid( $image, $metadata ) {
		return !empty( $metadata ) && $metadata != serialize( array() );
	}

	function pageCount( $image ) {
		$tree = $this->getMetaTree( $image );
		if ( !$tree ) {
			return false;
		}

		return count( $tree->xpath( '//OBJECT' ) );
	}

	function getPageDimensions( $image, $page ) {
		$tree = $this->getMetaTree( $image );
		if ( !$tree ) {
			return false;
		}

		$o = $tree->BODY[0]->OBJECT[$page - 1];
		if ( $o ) {
			return array(
				'width' => intval( $o['width'] ),
				'height' => intval( $o['height'] )
			);
		} else {
			return false;
		}
	}

	/**
	 * @param File $image
	 * @param int $page Page number to get information for
	 * @return bool|string Page text or false when no text found.
	 */
	function getPageText( $image, $page ) {
		$tree = $this->getMetaTree( $image, true );
		if ( !$tree ) {
			return false;
		}

		$o = $tree->BODY[0]->PAGE[$page - 1];
		if ( $o ) {
			$txt = $o['value'];

			return $txt;
		} else {
			return false;
		}
	}
}
