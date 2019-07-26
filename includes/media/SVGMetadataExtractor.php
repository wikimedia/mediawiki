<?php
/**
 * Extraction of SVG image metadata.
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
 * @author "Derk-Jan Hartman <hartman _at_ videolan d0t org>"
 * @author Brion Vibber
 * @copyright Copyright © 2010-2010 Brion Vibber, Derk-Jan Hartman
 * @license GPL-2.0-or-later
 */

/**
 * @ingroup Media
 * @deprecated since 1.34
 */
class SVGMetadataExtractor {
	/**
	 * @param string $filename
	 * @return array
	 * @deprecated since 1.34, use SVGReader->getMetadata() directly
	 */
	public static function getMetadata( $filename ) {
		wfDeprecated( __METHOD__, '1.34' );

		$svg = new SVGReader( $filename );

		return $svg->getMetadata();
	}
}
