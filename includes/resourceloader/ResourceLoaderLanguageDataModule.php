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
 * @author Santhosh Thottingal
 */

use MediaWiki\MediaWikiServices;

/**
 * Module for populating language specific data, such as grammar forms.
 *
 * @ingroup ResourceLoader
 * @internal
 */
class ResourceLoaderLanguageDataModule extends ResourceLoaderFileModule {
	protected $targets = [ 'desktop', 'mobile' ];

	/**
	 * Get all the dynamic data for the content language to an array.
	 *
	 * @internal Only public for use by GenerateJqueryMsgData (tests)
	 * @param string $langCode
	 * @return array
	 */
	public static function getData( $langCode ) : array {
		$language = MediaWikiServices::getInstance()->getLanguageFactory()
			->getLanguage( $langCode );
		return [
			'digitTransformTable' => $language->digitTransformTable(),
			'separatorTransformTable' => $language->separatorTransformTable(),
			'minimumGroupingDigits' => $language->minimumGroupingDigits(),
			'grammarForms' => $language->getGrammarForms(),
			'grammarTransformations' => $language->getGrammarTransformations(),
			'pluralRules' => $language->getPluralRules(),
			'digitGroupingPattern' => $language->digitGroupingPattern(),
			'fallbackLanguages' => $language->getFallbackLanguages(),
			'bcp47Map' => LanguageCode::getNonstandardLanguageCodeMapping(),
		];
	}

	/**
	 * @param ResourceLoaderContext $context
	 * @return string JavaScript code
	 */
	public function getScript( ResourceLoaderContext $context ) {
		return parent::getScript( $context )
			. 'mw.language.setData('
			. $context->encodeJson( $context->getLanguage() ) . ','
			. $context->encodeJson( self::getData( $context->getLanguage() ) )
			. ');';
	}

	/**
	 * @return bool
	 */
	public function enableModuleContentVersion() {
		return true;
	}

	/**
	 * @return bool
	 */
	public function supportsURLLoading() {
		return false;
	}
}
