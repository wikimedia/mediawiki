<?php
/**
 * English specific code.
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
 * @ingroup Language
 */

require_once __DIR__ . '/../LanguageConverter.php';

class EnConverter extends LanguageConverter {

	function loadDefaultTables() {
		$this->mTables = array(
			'en' => new ReplacementArray(),
			'en-x-piglatin' => new ReplacementArray(),
		);
	}

	/**
	 *  It translates text into Pig Latin.
	 *
	 * @param $text string
	 * @param $toVariant string
	 *
	 * @throws MWException
	 * @return string
	 */
	function translate( $text, $toVariant ) {
		if ( $toVariant === 'en-x-piglatin' ) {
			return '!PigLatin^' . strtoupper( $text ) . '^PigLatin!';
		} else {
			return $text;
		}
	}

}

/**
 * English
 *
 * @ingroup Language
 */
class LanguageEn extends Language {

	function __construct() {
		global $wgHooks;

		parent::__construct();

		$this->mConverter = new EnConverter( $this, 'en', array( 'en', 'en-x-piglatin' ) );
		$wgHooks['PageContentSaveComplete'][] = $this->mConverter;
	}

}
