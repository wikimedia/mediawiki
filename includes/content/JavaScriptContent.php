<?php
/**
 * Content for JavaScript pages.
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
 * @since 1.21
 *
 * @file
 * @ingroup Content
 *
 * @author Daniel Kinzler
 */

/**
 * Content for JavaScript pages.
 *
 * @ingroup Content
 */
class JavaScriptContent extends TextContent {
	/** @var JSParser */
	private static $jsParser;

	/**
	 * @param string $text JavaScript code.
	 * @param string $modelId the content model name
	 */
	public function __construct( $text, $modelId = CONTENT_MODEL_JAVASCRIPT ) {
		parent::__construct( $text, $modelId );
	}

	/**
	 * @since 1.25
	 * @return JSParser
	 */
	protected static function getParser() {
		if ( !self::$jsParser ) {
			self::$jsParser = new JSParser();
		}
		return self::$jsParser;
	}

	/**
	 * @since 1.25
	 * @return Status
	 */
	protected function validate( $fileName = '[inline]' ) {
		$parser = self::getParser();
		try {
			$parser->parse( $this->getNativeData(), $fileName, /* lineNr */ 1 );
		} catch ( Exception $e ) {
			$err = $e->getMessage();
			return Status::newFatal( 'javascript-error-syntax', $err );
		}
		return Status::newGood();
	}

	/**
	 * @since 1.25
	 * @return bool Whether content is valid javascript.
	 */
	public function isValid() {
		return $this->validate()->isOK();
	}

	/**
	 * @since 1.25
	 * @return Status
	 */
	public function prepareSave( WikiPage $page, $flags, $baseRevId, User $user ) {
		return $this->validate( $page->getTitle()->getSubpageText() );
	}

	/**
	 * Returns a Content object with pre-save transformations applied using
	 * Parser::preSaveTransform().
	 *
	 * @param Title $title
	 * @param User $user
	 * @param ParserOptions $popts
	 *
	 * @return JavaScriptContent
	 */
	public function preSaveTransform( Title $title, User $user, ParserOptions $popts ) {
		global $wgParser;
		// @todo Make pre-save transformation optional for script pages
		// See bug #32858

		$text = $this->getNativeData();
		$pst = $wgParser->preSaveTransform( $text, $title, $user, $popts );

		return new static( $pst );
	}

	/**
	 * @return string JavaScript wrapped in a <pre> tag.
	 */
	protected function getHtml() {
		$html = "";
		$html .= "<pre class=\"mw-code mw-js\" dir=\"ltr\">\n";
		$html .= htmlspecialchars( $this->getNativeData() );
		$html .= "\n</pre>\n";

		return $html;
	}

}
