<?php
/**
 * Handler for Microsoft's bitmap format.
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
 * Handler for Microsoft's bitmap format; getimagesize() doesn't
 * support these files
 *
 * @ingroup Media
 */
class BmpHandler extends BitmapHandler {
	/**
	 * @param File $file
	 * @return bool
	 */
	public function mustRender( $file ) {
		return true;
	}

	/**
	 * Render files as PNG
	 *
	 * @param string $text
	 * @param string $mime
	 * @param array|null $params
	 * @return array
	 */
	function getThumbType( $text, $mime, $params = null ) {
		return [ 'png', 'image/png' ];
	}

	/**
	 * Get width and height from the bmp header.
	 *
	 * @param File|FSFile $image
	 * @param string $filename
	 * @return array
	 */
	function getImageSize( $image, $filename ) {
		$f = fopen( $filename, 'rb' );
		if ( !$f ) {
			return false;
		}
		$header = fread( $f, 54 );
		fclose( $f );

		// Extract binary form of width and height from the header
		$w = substr( $header, 18, 4 );
		$h = substr( $header, 22, 4 );

		// Convert the unsigned long 32 bits (little endian):
		try {
			$w = wfUnpack( 'V', $w, 4 );
			$h = wfUnpack( 'V', $h, 4 );
		} catch ( Exception $e ) {
			return false;
		}

		return [ $w[1], $h[1] ];
	}
}
