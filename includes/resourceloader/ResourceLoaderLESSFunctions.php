<?php
/**
 * PHP-provided functions for LESS; see docs for $wgResourceLoaderLESSFunctions
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

class ResourceLoaderLESSFunctions {
	/**
	 * Check if an image file reference is suitable for embedding.
	 * An image is embeddable if it (a) exists, (b) has a suitable MIME-type,
	 * (c) does not exceed IE<9 size limit of 32kb. This is a LESS predicate
	 * function; it returns a LESS boolean value and can thus be used as a
	 * mixin guard.
	 *
	 * @par Example:
	 * @code
	 *   .background-image(@url) when(embeddable(@url)) {
	 *       background-image: url(@url) !ie;
	 *   }
	 * @endcode
	 */
	public static function embeddable( $frame, $less ) {
		$base = pathinfo( $less->parser->sourceName, PATHINFO_DIRNAME );
		$url = $frame[2][0];
		$file = realpath( $base . '/' . $url );
		return $less->toBool( $file
			&& strpos( $url, '//' ) === false
			&& filesize( $file ) < CSSMin::EMBED_SIZE_LIMIT
			&& CSSMin::getMimeType( $file ) !== false );
	}

	/**
	 * Convert an image URI to a base64-encoded data URI.
	 *
	 * @par Example:
	 * @code
	 *   .fancy-button {
	 *       background-image: embed('../images/button-bg.png');
	 *   }
	 * @endcode
	 */
	public static function embed( $frame, $less ) {
		$base = pathinfo( $less->parser->sourceName, PATHINFO_DIRNAME );
		$url = $frame[2][0];
		$file = realpath( $base . '/' . $url );

		$data = CSSMin::encodeImageAsDataURI( $file );
		$less->addParsedFile( $file );
		return 'url(' . $data . ')';
	}
}
