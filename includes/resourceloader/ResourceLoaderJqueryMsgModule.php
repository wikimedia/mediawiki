<?php
/**
 * ResourceLoader module for mediawiki.jqueryMsg that provides generated data.
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
 * ResourceLoader module for mediawiki.jqueryMsg and its generated data
 */
class ResourceLoaderJqueryMsgModule extends ResourceLoaderFileModule {

	/**
	 * @param ResourceLoaderContext $context
	 * @return string JavaScript code
	 */
	public function getScript( ResourceLoaderContext $context ) {
		$fileScript = parent::getScript( $context );

		$tagData = Sanitizer::getRecognizedTagData();
		$allowedHtmlElements = array_merge(
			array_keys( $tagData['htmlpairs'] ),
			array_diff(
				array_keys( $tagData['htmlsingle'] ),
				array_keys( $tagData['htmlsingleonly'] )
			)
		);

		$magicWords = [
			'SITENAME' => $this->getConfig()->get( 'Sitename' ),
		];
		Hooks::run( 'ResourceLoaderJqueryMsgModuleMagicWords', [ $context, &$magicWords ] );

		$parserDefaults = [
			'allowedHtmlElements' => $allowedHtmlElements,
			'magic' => $magicWords,
		];

		$setDataScript = Xml::encodeJsCall( 'mw.jqueryMsg.setParserDefaults', [
			$parserDefaults,
			// Pass deep=true because mediawiki.jqueryMsg.js contains
			// page-specific magic words that must not be overwritten.
			true,
		] );

		return $fileScript . $setDataScript;
	}

	/**
	 * @param ResourceLoaderContext $context
	 * @return array
	 */
	public function getScriptURLsForDebug( ResourceLoaderContext $context ) {
		// Bypass file module urls
		return ResourceLoaderModule::getScriptURLsForDebug( $context );
	}

	/**
	 * @return bool
	 */
	public function enableModuleContentVersion() {
		return true;
	}
}
