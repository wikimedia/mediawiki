<?php
/**
 * Resource loader module for populating language specific data.
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
 * @author Santhosh Thottingal
 * @author Timo Tijhof
 */

/**
 * ResourceLoader module for populating language specific data.
 */
class ResourceLoaderLanguageDataModule extends ResourceLoaderModule {

	protected $language;
	/**
	 * Get the grammar forms for the site content language.
	 *
	 * @return array
	 */
	protected function getSiteLangGrammarForms() {
		return $this->language->getGrammarForms();
	}

	/**
	 * Get the plural forms for the site content language.
	 *
	 * @return array
	 */
	protected function getPluralRules() {
		return $this->language->getPluralRules();
	}

	/**
	 * Get the digit transform table for the content language
	 * Seperator transform table also required here to convert
	 * the . and , sign to appropriate forms in content language.
	 *
	 * @return array
	 */
	protected function getDigitTransformTable() {
		$digitTransformTable = $this->language->digitTransformTable();
		$separatorTransformTable = $this->language->separatorTransformTable();
		if ( $digitTransformTable ) {
			array_merge( $digitTransformTable, (array)$separatorTransformTable );
		} else {
			return $separatorTransformTable;
		}
		return $digitTransformTable;
	}

	/**
	 * Get all the dynamic data for the content language to an array
	 *
	 * @return array
	 */
	protected function getData() {
		return array(
			'digitTransformTable' => $this->getDigitTransformTable(),
			'grammarForms' => $this->getSiteLangGrammarForms(),
			'pluralRules' => $this->getPluralRules(),
		);
	}

	/**
	 * @param $context ResourceLoaderContext
	 * @return string: JavaScript code
	 */
	public function getScript( ResourceLoaderContext $context ) {
		$this->language = Language::factory( $context->getLanguage() );
		return Xml::encodeJsCall( 'mw.language.setData', array(
			$this->language->getCode(),
			$this->getData()
		) );
	}

	/**
	 * @param $context ResourceLoaderContext
	 * @return array|int|Mixed
	 */
	public function getModifiedTime( ResourceLoaderContext $context ) {
		$this->language = Language::factory( $context ->getLanguage() );
		$cache = wfGetCache( CACHE_ANYTHING );
		$key = wfMemcKey( 'resourceloader', 'langdatamodule', 'changeinfo' );

		$data = $this->getData();
		$hash = md5( serialize( $data ) );

		$result = $cache->get( $key );
		if ( is_array( $result ) && $result['hash'] === $hash ) {
			return $result['timestamp'];
		}
		$timestamp = wfTimestamp();
		$cache->set( $key, array(
			'hash' => $hash,
			'timestamp' => $timestamp,
		) );
		return $timestamp;
	}

	/**
	 * @return array
	 */
	public function getDependencies() {
		return array( 'mediawiki.language.init' );
	}
}
