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
 * Content handler for code content such as css, js, json, etc
 * @since 1.24
 * @ingroup Content
 */
abstract class CodeContentHandler extends TextContentHandler {

	/**
	 * Returns the english language, because code is english, and should be handled as such.
	 *
	 * @param Title $title
	 * @param Content $content
	 *
	 * @return Language Return of wfGetLangObj( 'en' )
	 *
	 * @see ContentHandler::getPageLanguage()
	 */
	public function getPageLanguage( Title $title, Content $content = null ) {
		return wfGetLangObj( 'en' );
	}

	/**
	 * Returns the english language, because code is english, and should be handled as such.
	 *
	 * @param Title $title
	 * @param Content $content
	 *
	 * @return Language Return of wfGetLangObj( 'en' )
	 *
	 * @see ContentHandler::getPageViewLanguage()
	 */
	public function getPageViewLanguage( Title $title, Content $content = null ) {
		return wfGetLangObj( 'en' );
	}
}

/**
 * Content handler for CSS pages.
 * @since 1.21
 * @ingroup Content
 */
class CssContentHandler extends CodeContentHandler {

	public function __construct( $modelId = CONTENT_MODEL_CSS ) {
		parent::__construct( $modelId, array( CONTENT_FORMAT_CSS ) );
	}

	protected function getContentClass() {
		return 'CssContent';
	}
}

/**
 * Content handler for JavaScript pages.
 * @since 1.21
 * @ingroup Content
 * @todo make ScriptContentHandler base class, do highlighting stuff there?
 */
class JavaScriptContentHandler extends CodeContentHandler {

	public function __construct( $modelId = CONTENT_MODEL_JAVASCRIPT ) {
		parent::__construct( $modelId, array( CONTENT_FORMAT_JAVASCRIPT ) );
	}

	protected function getContentClass() {
		return 'JavaScriptContent';
	}
}

/**
 * Content handler for json pages.
 * @since 1.24
 * @ingroup Content
 */
class JSONContentHandler extends CodeContentHandler {

	public function __construct( $modelId = CONTENT_MODEL_JSON ) {
		parent::__construct( $modelId, array( CONTENT_FORMAT_JSON ) );
	}

	protected function getContentClass() {
		return 'JSONContent';
	}
}
