<?php
/**
 * Resource loader module for populating mediawiki.jqueryMsg data.
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
 * @author Brad Jorsch
 */

/**
 * ResourceLoader module for populating mediawiki.jqueryMsg data.
 */
class ResourceLoaderJqueryMsgDataModule extends ResourceLoaderModule {

	protected $targets = array( 'desktop', 'mobile' );

	/**
	 * @param ResourceLoaderContext $context
	 * @return string JavaScript code
	 */
	public function getScript( ResourceLoaderContext $context ) {
		$jsData = array();

		$tagData = Sanitizer::getRecognizedTagData();
		$jsData['allowedHtmlElements'] = array_merge(
			array_keys( $tagData['htmlpairs'] ),
			array_diff(
				array_keys( $tagData['htmlsingle'] ),
				array_keys( $tagData['htmlsingleonly'] )
			)
		);

		return "if ( !mw.jqueryMsg ) {\n" .
			"\tmw.jqueryMsg = {};\n" .
			"}\n" .
			"mw.jqueryMsg.data = " . Xml::encodeJsVar( $jsData ) . ";\n";
	}

	/**
	 * @return bool
	 */
	public function enableModuleContentVersion() {
		return true;
	}
}
