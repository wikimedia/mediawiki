<?php
/**
 * Implements Special:PageLanguage
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
 * @ingroup SpecialPage
 */

/**
 * A special page that allows to set page language
 *
 * @ingroup SpecialPage
 * @since 1.24
 */

// This is only a temporary front end implementation
// for page language selection meant for testing.
// Not to be merged.

class SpecialPageLanguage extends SpecialPage {
	function __construct() {
		parent::__construct( 'PageLanguage' );
	}

	function execute( $par ) {
		wfProfileIn( __METHOD__ );

		$request = $this->getRequest();
		$this->setHeaders();
		$this->outputHeader();
		$out = $this->getOutput();
		$out->addHTML( $this->buildForm() );

		wfProfileOut( __METHOD__ );
		// GET values
		$page = $request->getVal( 'page' );
		$lang = $request->getVal( 'lang' );

		if ( $page && $lang ) {
			$title = Title::newFromText( $page );
			$title->setPageLanguage( $lang );
		}
	}

	function buildForm() {
		global $wgScript;
		$out = '';

		$title = $this->getTitle();

		$langSelect = Xml::languageSelector( 'en', false, null, array( 'name' => 'lang' ) );

		$out .= Xml::openElement( 'form', array( 'method' => 'get', 'action' => $wgScript ) );
		$out .= Html::hidden( 'title', $title->getPrefixedText() );
		$out .= Xml::openElement( 'fieldset' );
		$out .= Xml::element( 'legend', null, 'Language Selector' );
		$out .= Xml::openElement( 'table', array( 'id' => 'lang-select', 'class' => 'allpages' ) );
		$out .= "<tr>
			<td class='mw-label'>" .
			Xml::label( 'Page name', 'page-name' ) .
			"	</td>
			<td class='mw-input'>" .
			Xml::input( 'page', 30 ) .
			"	</td>\n
			</tr>" .
			"	<tr>\n
			<td class=\"mw-label\">" . $langSelect[0] . "</td>\n
			<td class=\"mw-input\">" . $langSelect[1] . "</td>\n
			</tr>" .
			"	<td class='mw-input'>" .
			Xml::submitButton( 'Submit' ) .
			"	</td>
			</tr>";
		$out .= Xml::closeElement( 'table' );
		$out .= Xml::closeElement( 'fieldset' );
		$out .= Xml::closeElement( 'form' );

		return $out;
	}
}
