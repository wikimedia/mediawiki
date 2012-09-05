<?php
/**
 * Media-handling base classes and generic functionality.
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
 * Media handler abstract base class for images
 *
 * @ingroup Media
 */
abstract class ImageHandler extends MediaHandler {

	/**
	 * @param $file File
	 * @return bool
	 */
	function canRender( $file ) {
		return ( $file->getWidth() && $file->getHeight() );
	}

	function getParamMap() {
		return array( 'img_width' => 'width' );
	}

	function validateParam( $name, $value ) {
		if ( in_array( $name, array( 'width', 'height' ) ) ) {
			if ( $value <= 0 ) {
				return false;
			} else {
				return true;
			}
		} else {
			return false;
		}
	}

	function makeParamString( $params ) {
		if ( isset( $params['physicalWidth'] ) ) {
			$width = $params['physicalWidth'];
		} elseif ( isset( $params['width'] ) ) {
			$width = $params['width'];
		} else {
			throw new MWException( 'No width specified to '.__METHOD__ );
		}
		# Removed for ProofreadPage
		#$width = intval( $width );
		return "{$width}px";
	}

	function parseParamString( $str ) {
		$m = false;
		if ( preg_match( '/^(\d+)px$/', $str, $m ) ) {
			return array( 'width' => $m[1] );
		} else {
			return false;
		}
	}

	function getScriptParams( $params ) {
		return array( 'width' => $params['width'] );
	}

	/**
	 * @param $image File
	 * @param  $params
	 * @return bool
	 */
	function normaliseParams( $image, &$params ) {
		$mimeType = $image->getMimeType();

		if ( !isset( $params['width'] ) ) {
			return false;
		}

		if ( !isset( $params['page'] ) ) {
			$params['page'] = 1;
		} else  {
			if ( $params['page'] > $image->pageCount() ) {
				$params['page'] = $image->pageCount();
			}

			if ( $params['page'] < 1 ) {
				$params['page'] = 1;
			}
		}

		$srcWidth = $image->getWidth( $params['page'] );
		$srcHeight = $image->getHeight( $params['page'] );

		if ( isset( $params['height'] ) && $params['height'] != -1 ) {
			# Height & width were both set
			if ( $params['width'] * $srcHeight > $params['height'] * $srcWidth ) {
				# Height is the relative smaller dimension, so scale width accordingly
				$params['width'] = self::fitBoxWidth( $srcWidth, $srcHeight, $params['height'] );

				if ( $params['width'] == 0 ) {
					# Very small image, so we need to rely on client side scaling :(
					$params['width'] = 1;
				}

				$params['physicalWidth'] = $params['width'];
			} else {
				# Height was crap, unset it so that it will be calculated later
				unset( $params['height'] );
			}
		}

		if ( !isset( $params['physicalWidth'] ) ) {
			# Passed all validations, so set the physicalWidth
			$params['physicalWidth'] = $params['width'];
		}

		# Because thumbs are only referred to by width, the height always needs
		# to be scaled by the width to keep the thumbnail sizes consistent,
		# even if it was set inside the if block above
		$params['physicalHeight'] = File::scaleHeight( $srcWidth, $srcHeight,
			$params['physicalWidth'] );

		# Set the height if it was not validated in the if block higher up
		if ( !isset( $params['height'] ) || $params['height'] == -1 ) {
			$params['height'] = $params['physicalHeight'];
		}


		if ( !$this->validateThumbParams( $params['physicalWidth'],
				$params['physicalHeight'], $srcWidth, $srcHeight, $mimeType ) ) {
			return false;
		}
		return true;
	}

	/**
	 * Validate thumbnail parameters and fill in the correct height
	 *
	 * @param $width Integer: specified width (input/output)
	 * @param $height Integer: height (output only)
	 * @param $srcWidth Integer: width of the source image
	 * @param $srcHeight Integer: height of the source image
	 * @param $mimeType
	 * @return bool False to indicate that an error should be returned to the user.
	 */
	function validateThumbParams( &$width, &$height, $srcWidth, $srcHeight, $mimeType ) {
		$width = intval( $width );

		# Sanity check $width
		if( $width <= 0) {
			wfDebug( __METHOD__.": Invalid destination width: $width\n" );
			return false;
		}
		if ( $srcWidth <= 0 ) {
			wfDebug( __METHOD__.": Invalid source width: $srcWidth\n" );
			return false;
		}

		$height = File::scaleHeight( $srcWidth, $srcHeight, $width );
		if ( $height == 0 ) {
			# Force height to be at least 1 pixel
			$height = 1;
		}
		return true;
	}

	/**
	 * @param $image File
	 * @param  $script
	 * @param  $params
	 * @return bool|ThumbnailImage
	 */
	function getScriptedTransform( $image, $script, $params ) {
		if ( !$this->normaliseParams( $image, $params ) ) {
			return false;
		}
		$url = $script . '&' . wfArrayToCGI( $this->getScriptParams( $params ) );

		if( $image->mustRender() || $params['width'] < $image->getWidth() ) {
			return new ThumbnailImage( $image, $url, false, $params );
		}
	}

	function getImageSize( $image, $path ) {
		wfSuppressWarnings();
		$gis = getimagesize( $path );
		wfRestoreWarnings();
		return $gis;
	}

	/**
	 * @param $file File
	 * @return string
	 */
	function getShortDesc( $file ) {
		global $wgLang;
		$nbytes = htmlspecialchars( $wgLang->formatSize( $file->getSize() ) );
		$widthheight = wfMessage( 'widthheight' )->numParams( $file->getWidth(), $file->getHeight() )->escaped();

		return "$widthheight ($nbytes)";
	}

	/**
	 * @param $file File
	 * @return string
	 */
	function getLongDesc( $file ) {
		global $wgLang;
		$pages = $file->pageCount();
		$size = htmlspecialchars( $wgLang->formatSize( $file->getSize() ) );
		if ( $pages === false || $pages <= 1 ) {
			$msg = wfMessage( 'file-info-size' )->numParams( $file->getWidth(),
				$file->getHeight() )->params( $size,
				$file->getMimeType() )->parse();
		} else {
			$msg = wfMessage( 'file-info-size-pages' )->numParams( $file->getWidth(),
				$file->getHeight() )->params( $size,
				$file->getMimeType() )->numParams( $pages )->parse();
		}
		return $msg;
	}

	/**
	 * @param $file File
	 * @return string
	 */
	function getDimensionsString( $file ) {
		$pages = $file->pageCount();
		if ( $pages > 1 ) {
			return wfMessage( 'widthheightpage' )->numParams( $file->getWidth(), $file->getHeight(), $pages )->text();
		} else {
			return wfMessage( 'widthheight' )->numParams( $file->getWidth(), $file->getHeight() )->text();
		}
	}
}
