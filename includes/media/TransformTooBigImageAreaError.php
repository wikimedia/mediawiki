<?php
/**
 * Base class for the output of file transformation methods.
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
 * Shortcut class for parameter file size errors
 *
 * @ingroup Media
 * @since 1.25
 */
class TransformTooBigImageAreaError extends MediaTransformError {
	function __construct( $params, $maxImageArea ) {
		$msg = wfMessage( 'thumbnail_toobigimagearea' );
		$msg->params(
			$msg->getLanguage()->formatComputingNumbers( $maxImageArea, 1000, "size-$1pixel" )
		);

		parent::__construct( 'thumbnail_error',
			max( $params['width'] ?? 0, 120 ),
			max( $params['height'] ?? 0, 120 ),
			$msg
		);
	}

	function getHttpStatusCode() {
		return 400;
	}
}
