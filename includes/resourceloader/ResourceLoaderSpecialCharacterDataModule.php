<?php
/**
 * Resource loader module for populating special characters data for some
 * editing extensions to use.
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
 * Resource loader module for populating special characters data for some
 * editing extensions to use.
 */
class ResourceLoaderSpecialCharacterDataModule extends ResourceLoaderModule {
	private $path = "resources/src/mediawiki.language/specialcharacters.json";
	protected $targets = array( 'desktop', 'mobile' );

	/**
	 * Get all the dynamic data.
	 *
	 * @return array
	 */
	protected function getData() {
		global $IP;
		return json_decode( file_get_contents( "$IP/{$this->path}" ) );
	}

	/**
	 * @param ResourceLoaderContext $context
	 * @return string JavaScript code
	 */
	public function getScript( ResourceLoaderContext $context ) {
		return Xml::encodeJsCall(
			'mw.language.setSpecialCharacters',
			array(
				$this->getData()
			),
			ResourceLoader::inDebugMode()
		);
	}

	/**
	 * @return bool
	 */
	public function enableModuleContentVersion() {
		return true;
	}

	/**
	 * @param ResourceLoaderContext $context
	 * @return array
	 */
	public function getDependencies( ResourceLoaderContext $context = null ) {
		return array( 'mediawiki.language' );
	}

	/**
	 * @return array
	 */
	public function getMessages() {
		return array(
			'special-characters-group-latin',
			'special-characters-group-latinextended',
			'special-characters-group-ipa',
			'special-characters-group-symbols',
			'special-characters-group-greek',
			'special-characters-group-cyrillic',
			'special-characters-group-arabic',
			'special-characters-group-arabicextended',
			'special-characters-group-persian',
			'special-characters-group-hebrew',
			'special-characters-group-bangla',
			'special-characters-group-tamil',
			'special-characters-group-telugu',
			'special-characters-group-sinhala',
			'special-characters-group-devanagari',
			'special-characters-group-gujarati',
			'special-characters-group-thai',
			'special-characters-group-lao',
			'special-characters-group-khmer',
			'special-characters-title-endash',
			'special-characters-title-emdash',
			'special-characters-title-minus'
		);
	}
}
