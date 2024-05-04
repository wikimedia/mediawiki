<?php
/**
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
 * @ingroup Json
 */

namespace MediaWiki\Json;

/**
 * Defines JSON-related constants.
 * @internal
 * @package MediaWiki\Json
 */
interface JsonConstants {

	/**
	 * Name of the property where the class information is stored.
	 */
	public const TYPE_ANNOTATION = '_type_';
	/**
	 * Name of the marker property to indicate that array contents
	 * need to be examined during deserialization.
	 */
	public const COMPLEX_ANNOTATION = '_complex_';
}
