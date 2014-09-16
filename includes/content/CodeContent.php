<?php
/**
 * Content object for the pages with code, such as CSS, JavaScript, JSON.
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
 * @since 1.24
 *
 * @file
 * @ingroup Content
 *
 * @author Yuri Astrakhan
 */

/**
 * Content object for the pages with code, such as CSS, JavaScript, JSON.
 *
 * @ingroup Content
 */
abstract class CodeContent extends TextContent {

	/**
	 * Returns a Content object with pre-save transformations applied using
	 * Parser::preSaveTransform().
	 *
	 * @param Title $title
	 * @param User $user
	 * @param ParserOptions $popts
	 *
	 * @return CodeContent
	 *
	 * @see TextContent::preSaveTransform
	 */
	public function preSaveTransform( Title $title, User $user, ParserOptions $popts ) {
		global $wgParser;
		// @todo Make pre-save transformation optional for script pages

		$text = $this->getNativeData();
		$pst = $wgParser->preSaveTransform( $text, $title, $user, $popts );

		return new static( $pst );
	}

	/**
	 * Converts data to a <pre>...</pre> rendering of the code
	 * @param string $cssClass CSS class, such as 'mw-css' or 'mw-js'
	 * @param string $text code to display
	 * @return string code wrapped in a <pre> tag with the specified css class
	 */
	protected function codeToPreElement( $cssClass, $text ) {
		$html = "<pre class='mw-code $cssClass' dir='ltr'>\n";
		$html .= htmlspecialchars( $text );
		$html .= "\n</pre>\n";

		return $html;
	}
}
