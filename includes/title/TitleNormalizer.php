<?php
/**
 * A title normalization service for %MediaWiki.
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
 * A title normalization service for MediaWiki.
 *
 * This is designed to encapsulate the conventions for the title forms to be used in the
 * database, in links, and in wikitext.
 *
 * @see https://www.mediawiki.org/wiki/Requests_for_comment/TitleValue
 */
class TitleNormalizer {

	/**
	 * Returns the title in the form conventionally used in human readable text.
	 *
	 * @param string $titleText
	 *
	 * @return string
	 */
	public function getTextForm( $titleText ) {
		$titleText = $this->getSafe( $titleText );

		return str_replace( '_', ' ', $titleText );
	}

	/**
	 * Returns the title in the form conventionally used in the database.
	 * The result is not yet escaped for use in queries.
	 *
	 * @param string $titleText
	 *
	 * @return string
	 */
	public function getDBForm( $titleText ) {
		$titleText = $this->getSafe( $titleText );

		return str_replace( ' ', '_', $titleText );
	}

	/**
	 * Returns the title in the form conventionally used in URLs.
	 * The result is not yet URLEncoded.
	 *
	 * @param string $titleText
	 *
	 * @return string
	 */
	public function getURLForm( $titleText ) {
		$titleText = $this->getSafe( $titleText );

		return $this->getDBForm( $titleText );
	}

	/**
	 * @param $titleText
	 *
	 * @return string
	 */
	public function getSafe( $titleText ) {
		//TODO: Title::secureAndSplit
	}
}
 