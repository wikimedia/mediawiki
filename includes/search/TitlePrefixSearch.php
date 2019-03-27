<?php
/**
 * Prefix search of page names.
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

/**
 * Performs prefix search, returning Title objects
 * @deprecated Since 1.27, Use SearchEngine::defaultPrefixSearch or SearchEngine::completionSearch
 * @ingroup Search
 */
class TitlePrefixSearch extends PrefixSearch {

	protected function titles( array $titles ) {
		return $titles;
	}

	protected function strings( array $strings ) {
		$titles = array_map( 'Title::newFromText', $strings );
		$lb = new LinkBatch( $titles );
		$lb->setCaller( __METHOD__ );
		$lb->execute();
		return $titles;
	}
}
