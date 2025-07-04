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

use MediaWiki\FileRepo\File\File;
use Wikimedia\AtEase\AtEase;

/**
 * Media handler abstract base class for images
 *
 * @stable to extend
 *
 * @ingroup Media
 */
abstract class ImageHandler extends MediaHandler {
	/**
	 * @inheritDoc
	 * @stable to override
	 * @param File $file
	 * @return bool
	 */
	public function canRender( $file ) {
		return ( $file->getWidth() && $file->getHeight() );
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 * @return string[]
	 */
	public function getParamMap() {
		return [ 'img_width' => 'width' ];
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function validateParam( $name, $value ) {
		return in_array( $name, [ 'width', 'height' ] ) && $value > 0;
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 * @throws MediaTransformInvalidParametersException
	 */
	public function makeParamString( $params ) {
		if ( isset( $params['physicalWidth'] ) ) {
			$width = $params['physicalWidth'];
		} elseif ( isset( $params['width'] ) ) {
			$width = $params['width'];
		} else {
			throw new MediaTransformInvalidParametersException( 'No width specified to ' . __METHOD__ );
		}

		# Removed for ProofreadPage
		# $width = intval( $width );
		return "{$width}px";
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function parseParamString( $str ) {
		$m = false;
		if ( preg_match( '/^(\d+)px$/', $str, $m ) ) {
			return [ 'width' => $m[1] ];
		}
		return false;
	}

	/**
	 * @stable to override
	 * @param array $params
	 * @return array
	 */
	protected function getScriptParams( $params ) {
		return [ 'width' => $params['width'] ];
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 * @param File $image
	 * @param array &$params @phan-ignore-reference
	 * @return bool
	 * @phan-assert array{width:int,physicalWidth:int,height:int,physicalHeight:int,page:int} $params
	 */
	public function normaliseParams( $image, &$params ) {
		if ( !isset( $params['width'] ) ) {
			return false;
		}

		if ( !isset( $params['page'] ) ) {
			$params['page'] = 1;
		} else {
			$params['page'] = (int)$params['page'];
			if ( $params['page'] > $image->pageCount() ) {
				$params['page'] = $image->pageCount();
			}

			if ( $params['page'] < 1 ) {
				$params['page'] = 1;
			}
		}

		$srcWidth = $image->getWidth( $params['page'] );
		$srcHeight = $image->getHeight( $params['page'] );

		if ( isset( $params['height'] ) && $params['height'] !== -1 ) {
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
			// @phan-suppress-next-line PhanTypePossiblyInvalidDimOffset False positive, checked above
			$params['physicalWidth'] = $params['width'];
		}

		# Because thumbs are only referred to by width, the height always needs
		# to be scaled by the width to keep the thumbnail sizes consistent,
		# even if it was set inside the if block above
		$params['physicalHeight'] = File::scaleHeight( $srcWidth, $srcHeight,
			$params['physicalWidth'] );

		# Set the height if it was not validated in the if block higher up
		if ( !isset( $params['height'] ) || $params['height'] === -1 ) {
			$params['height'] = $params['physicalHeight'];
		}

		if ( !$this->validateThumbParams( $params['physicalWidth'],
			$params['physicalHeight'], $srcWidth, $srcHeight )
		) {
			return false;
		}

		return true;
	}

	/**
	 * Validate thumbnail parameters and fill in the correct height
	 *
	 * @param int &$width Specified width (input/output)
	 * @param int &$height Height (output only)
	 * @param int $srcWidth Width of the source image
	 * @param int $srcHeight Height of the source image
	 * @return bool False to indicate that an error should be returned to the user.
	 */
	private function validateThumbParams( &$width, &$height, $srcWidth, $srcHeight ) {
		$width = (int)$width;

		if ( $width <= 0 ) {
			wfDebug( __METHOD__ . ": Invalid destination width: $width" );

			return false;
		}
		if ( $srcWidth <= 0 ) {
			wfDebug( __METHOD__ . ": Invalid source width: $srcWidth" );

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
	 * @inheritDoc
	 * @stable to override
	 * @param File $image
	 * @param string $script
	 * @param array $params
	 * @return MediaTransformOutput|false
	 */
	public function getScriptedTransform( $image, $script, $params ) {
		if ( !$this->normaliseParams( $image, $params ) ) {
			return false;
		}
		$url = wfAppendQuery( $script, $this->getScriptParams( $params ) );

		if ( $image->mustRender() || $params['width'] < $image->getWidth() ) {
			return new ThumbnailImage( $image, $url, false, $params );
		}
	}

	/** @inheritDoc */
	public function getImageSize( $image, $path ) {
		AtEase::suppressWarnings();
		$gis = getimagesize( $path );
		AtEase::restoreWarnings();

		return $gis;
	}

	/** @inheritDoc */
	public function getSizeAndMetadata( $state, $path ) {
		// phpcs:ignore Generic.PHP.NoSilencedErrors.Discouraged
		$gis = @getimagesize( $path );
		if ( $gis ) {
			$info = [
				'width' => $gis[0],
				'height' => $gis[1],
			];
			if ( isset( $gis['bits'] ) ) {
				$info['bits'] = $gis['bits'];
			}
		} else {
			$info = [];
		}
		return $info;
	}

	/**
	 * Function that returns the number of pixels to be thumbnailed.
	 * Intended for animated GIFs to multiply by the number of frames.
	 *
	 * If the file doesn't support a notion of "area" return 0.
	 * @stable to override
	 *
	 * @param File $image
	 * @return int
	 */
	public function getImageArea( $image ) {
		return $image->getWidth() * $image->getHeight();
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 * @param File $file
	 * @return string
	 */
	public function getShortDesc( $file ) {
		global $wgLang;
		$nbytes = htmlspecialchars( $wgLang->formatSize( $file->getSize() ), ENT_QUOTES );
		$widthheight = wfMessage( 'widthheight' )
			->numParams( $file->getWidth(), $file->getHeight() )
			->escaped();

		return "$widthheight ($nbytes)";
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 * @param File $file
	 * @return string
	 */
	public function getLongDesc( $file ) {
		$pages = $file->pageCount();
		if ( $pages === false || $pages <= 1 ) {
			$msg = wfMessage( 'file-info-size' )
				->numParams( $file->getWidth(), $file->getHeight() )
				->sizeParams( $file->getSize() )
				->params( '<span class="mime-type">' . $file->getMimeType() . '</span>' )
				->parse();
		} else {
			$msg = wfMessage( 'file-info-size-pages' )
				->numParams( $file->getWidth(), $file->getHeight() )
				->sizeParams( $file->getSize() )
				->params( '<span class="mime-type">' . $file->getMimeType() . '</span>' )->numParams( $pages )
				->parse();
		}

		return $msg;
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 * @param File $file
	 * @return string
	 */
	public function getDimensionsString( $file ) {
		$pages = $file->pageCount();
		if ( $pages > 1 ) {
			return wfMessage( 'widthheightpage' )
				->numParams( $file->getWidth(), $file->getHeight(), $pages )->text();
		}
		return wfMessage( 'widthheight' )
			->numParams( $file->getWidth(), $file->getHeight() )->text();
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function sanitizeParamsForBucketing( $params ) {
		$params = parent::sanitizeParamsForBucketing( $params );

		// We unset the height parameters in order to let normaliseParams recalculate them
		// Otherwise there might be a height discrepancy
		unset( $params['height'] );
		unset( $params['physicalHeight'] );

		return $params;
	}
}
