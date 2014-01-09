<?php
/**
 * Represents a link rendering service for %MediaWiki.
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
 * @license GPL 2+
 * @author Daniel Kinzler
 */

/**
 * Represents a link rendering service for MediaWiki.
 *
 * This is designed to encapsulate the knowledge about how page titles map to
 * URLs, and how links are encoded in a given output format.
 *
 * @see https://www.mediawiki.org/wiki/Requests_for_comment/TitleValue
 */
interface PageLinkRenderer {

	/**
	 * Returns the renderer's target format, e.g. 'text/html'.
	 *
	 * @return string
	 */
	public function getTargetFormat();

	/**
	 * Returns the renderer's target format, e.g. 'text/html'.
	 *
	 * @param TitleValue $page The link's target
	 * @param string $text The link's surface text
	 *
	 * @return string
	 */
	public function renderLink( TitleValue $page, $text );

}