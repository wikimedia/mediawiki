<?php
/**
 * HTML validation and correction
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
 * @ingroup Parser
 */

use MediaWiki\MediaWikiServices;

/**
 * Class to interact with and configure Remex tidy
 *
 * @ingroup Parser
 */
class MWTidy {
	/**
	 * Interface with Remex tidy.
	 * If tidy isn't able to correct the markup, the original will be
	 * returned in all its glory with a warning comment appended.
	 *
	 * @param string $text HTML input fragment. This should not contain a
	 *                     <body> or <html> tag.
	 * @return string Corrected HTML output
	 * @throws MWException
	 * @deprecated since 1.36; use MediaWikiServices::getTidy()->tidy() instead
	 */
	public static function tidy( $text ) {
		return MediaWikiServices::getInstance()->getTidy()->tidy( $text );
	}
}
