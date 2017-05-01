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
	function __construct( $mode = 'traditional', IContextSource $context = null ) {
		parent::__construct( $mode, $context );
		// Does not support per row option.
		$this->mPerRow = 0;
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
