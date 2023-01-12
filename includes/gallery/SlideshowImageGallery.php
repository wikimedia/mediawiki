<?php
/**
 * A slideshow gallery shows one image at a time with controls to move around.
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
 */

class SlideshowImageGallery extends TraditionalImageGallery {
	public function __construct( $mode = 'traditional', IContextSource $context = null ) {
		parent::__construct( $mode, $context );
		// Does not support per row option.
		$this->mPerRow = 0;
	}

	/**
	 * @inheritDoc
	 */
	protected function getHtmlItemAttributes( $thumb, $width, $alt = '' ) {
		if ( !$thumb ) {
			return parent::getHtmlItemAttributes( $thumb, $width, $alt );
		}

		return parent::getHtmlItemAttributes( $thumb, $width, $alt ) + [
			'data-alt' => $alt,
			'data-src' => $thumb->getUrl(),
		];
	}

	/**
	 * @inheritDoc
	 */
	protected function getGBWidthOverwrite( $thumb ) {
		return '100%';
	}

	/**
	 * @inheritDoc
	 */
	protected function getVPad( $boxHeight, $thumbHeight ) {
		return 0;
	}

	/**
	 * Get the transform parameters for a thumbnail.
	 *
	 * @param File|false $img The file in question. May be false for invalid image
	 * @return array
	 */
	protected function getThumbParams( $img ) {
		$params = parent::getThumbParams( $img );
		return [
			// This value should be >= than the width defined for `.gallerycarousel`
			// otherwise the image will appear pixelated. It should correspond with one
			// of the pregenerated thumbnail sizes on T211661#8377883.
			'width' => 640,
		];
	}

	/**
	 * Add javascript adds interface elements
	 * @return array
	 */
	protected function getModules() {
		return [ 'mediawiki.page.gallery.slideshow' ];
	}

	public function setAdditionalOptions( $params ) {
		$this->mAttribs['data-showthumbnails'] = isset( $params['showthumbnails'] );
	}
}
