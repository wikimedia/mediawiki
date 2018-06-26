<?php
/**
 * ResourceLoader module for populating language specific data.
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
 * ResourceLoader module for populating language specific data, such as grammar forms.
 */
class ResourceLoaderLanguageDataModule extends ResourceLoaderFileModule {

	protected $targets = [ 'desktop', 'mobile' ];

	/**
	 * Get all the dynamic data for the content language to an array.
	 *
	 * @param ResourceLoaderContext $context
	 * @return array
	 */
	protected function getData( ResourceLoaderContext $context ) {
		$language = Language::factory( $context->getLanguage() );
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
		$fileScript = parent::getScript( $context );
		$langDataScript = Xml::encodeJsCall(
			'mw.language.setData',
			[
				$context->getLanguage(),
				$this->getData( $context )
			],
			ResourceLoader::inDebugMode()
		);
		return $fileScript . $langDataScript;
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
