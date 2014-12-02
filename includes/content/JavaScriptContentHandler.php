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
	/** @var JSParser */
	private $jsParser;

	/**
	 * @param string $modelId
	 */
	public function __construct( $modelId = CONTENT_MODEL_JAVASCRIPT ) {
		parent::__construct( $modelId, array( CONTENT_FORMAT_JAVASCRIPT ) );
	}

	/**
	 * @return string
	 */
	protected function getContentClass() {
		return 'JavaScriptContent';
	}

	/**
	 * @since 1.25
	 * @return JSParser
	 */
	private function getParser() {
		if ( !$this->jsParser ) {
			$this->jsParser = new JSParser();
		}
		return $this->jsParser;
	}

	/**
	 * @since 1.25
	 * @param Content $content
	 * @param string $fileName Used for display purposes in any error message (e.g. "foo.js")
	 * @return Status
	 */
	public function validateContent( Content $content, $fileName = '[inline]' ) {
		$parser = $this->getParser();
		try {
			$parser->parse( $content->getNativeData(), $fileName, /* lineNr */ 1 );
		} catch ( Exception $e ) {
			$err = $e->getMessage();
			return Status::newFatal( 'javascript-error-syntax', $err );
		}
		return Status::newGood();
	}
}
