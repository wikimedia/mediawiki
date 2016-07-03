<?php
/**
 * Content handler for CSS pages.
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
 * @ingroup Content
 */

/**
 * Content handler for CSS pages.
 *
 * @since 1.21
 * @ingroup Content
 */
class CssContentHandler extends CodeContentHandler {

	/**
	 * @param string $modelId
	 */
	public function __construct( $modelId = CONTENT_MODEL_CSS ) {
		parent::__construct( $modelId, [ CONTENT_FORMAT_CSS ] );
	}

	protected function getContentClass() {
		return 'CssContent';
	}

	public function supportsRedirects() {
		return true;
	}

	/**
	 * Create a redirect that is also valid CSS
	 *
	 * @param Title $destination
	 * @param string $text ignored
	 * @return CssContent
	 */
	public function makeRedirectContent( Title $destination, $text = '' ) {
		// The parameters are passed as a string so the / is not url-encoded by wfArrayToCgi
		$url = $destination->getFullURL( 'action=raw&ctype=text/css', false, PROTO_RELATIVE );
		$class = $this->getContentClass();
		return new $class( '/* #REDIRECT */@import ' . CSSMin::buildUrlValue( $url ) . ';' );
	}

}
