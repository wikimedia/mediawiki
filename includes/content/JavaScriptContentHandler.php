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
 */

/**
 * Content handler for JavaScript pages.
 *
 * @todo Create a ScriptContentHandler base class, do highlighting stuff there?
 *
 * @since 1.21
 * @ingroup Content
 */
class JavaScriptContentHandler extends CodeContentHandler {

	/**
	 * @param string $modelId
	 */
	public function __construct( $modelId = CONTENT_MODEL_JAVASCRIPT ) {
		parent::__construct( $modelId, [ CONTENT_FORMAT_JAVASCRIPT ] );
	}

	/**
	 * @return string
	 */
	protected function getContentClass() {
		return 'JavaScriptContent';
	}

	public function supportsRedirects() {
		return true;
	}

	/**
	 * Create a redirect that is also valid JavaScript
	 *
	 * @param Title $destination
	 * @param string $text ignored
	 * @return JavaScriptContent
	 */
	public function makeRedirectContent( Title $destination, $text = '' ) {
		// The parameters are passed as a string so the / is not url-encoded by wfArrayToCgi
		$url = $destination->getFullURL( 'action=raw&ctype=text/javascript', false, PROTO_RELATIVE );
		$class = $this->getContentClass();
		return new $class( '/* #REDIRECT */' . Xml::encodeJsCall( 'mw.loader.load', [ $url ] ) );
	}
}
