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
	protected $targets = array( 'desktop', 'mobile' );
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
	 * Get the digit groupin Pattern for the site content language.
	 *
	 * @return array
	 */
	protected function getDigitGroupingPattern() {
		return $this->language->digitGroupingPattern();
	}

	/**
	 * Get the digit transform table for the content language
	 *
	 * @return array
	 */
	protected function getDigitTransformTable() {
		return $this->language->digitTransformTable();
	}

	/**
	 * Get seperator transform table required for converting
	 * the . and , sign to appropriate forms in site content language.
	 *
	 * @return array
	 */
	protected function getSeparatorTransformTable() {
		return $this->language->separatorTransformTable();
	}

	/**
	 * Get all the dynamic data for the content language to an array.
	 *
	 * NOTE: Before calling this you HAVE to make sure $this->language is set.
	 *
	 * @return array
	 */
	protected function getData() {
		return array(
			'digitTransformTable' => $this->getDigitTransformTable(),
			'separatorTransformTable' => $this->getSeparatorTransformTable(),
			'grammarForms' => $this->getSiteLangGrammarForms(),
			'pluralRules' => $this->getPluralRules(),
			'digitGroupingPattern' => $this->getDigitGroupingPattern(),
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
	 * @return int: UNIX timestamp
	 */
	public function getModifiedTime( ResourceLoaderContext $context ) {
		return max( 1, $this->getHashMtime( $context ) );
	}

	/**
	 * @param $context ResourceLoaderContext
	 * @return string: Hash
	 */
	public function getModifiedHash( ResourceLoaderContext $context ) {
		$this->language = Language::factory( $context->getLanguage() );

		return md5( serialize( $this->getData() ) );
	}

	/**
	 * @return array
	 */
	public function getDependencies() {
		return array( 'mediawiki.language.init' );
	}
}
