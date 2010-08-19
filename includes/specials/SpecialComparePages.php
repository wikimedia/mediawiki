<?php
/**
 * Implements Special:ComparePages
 *
 * Copyright © 2010 Derk-Jan Hartman <hartman@videolan.org>
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
 * Implements Special:ComparePages
 *
 * @ingroup SpecialPage
 */
class SpecialComparePages extends SpecialPage {

	// Stored objects
	protected $opts, $skin;

	// Some internal settings
	protected $showNavigation = false;

	public function __construct() {
		parent::__construct( 'ComparePages' );
	}

	protected function setup( $par ) {
		global $wgRequest, $wgUser;

		// Options
		$opts = new FormOptions();
		$this->opts = $opts; // bind
		$opts->add( 'page1', '' );
		$opts->add( 'page2', '' );
		$opts->add( 'rev1', '' );
		$opts->add( 'rev2', '' );
		$opts->add( 'action', '' );

		// Set values
		$opts->fetchValuesFromRequest( $wgRequest );

		$title1 = Title::newFromText( $opts->getValue( 'page1' ) );
		$title2 = Title::newFromText( $opts->getValue( 'page2' ) );

		if( $title1 && $title1->exists() && $opts->getValue( 'rev1' ) == '' ) {
			$pda = new Article( $title1 );
			$pdi = $pda->getID();
			$pdLastRevision = Revision::loadFromPageId( wfGetDB( DB_SLAVE ), $pdi );
			$opts->setValue( 'rev1', $pdLastRevision->getId() );
		} elseif ( $opts->getValue( 'rev1' ) != '' ) {
			$pdrev = Revision::newFromId( $opts->getValue( 'rev1' ) );
			if( $pdrev ) $opts->setValue( 'page1', $pdrev->getTitle()->getPrefixedText() );
		}
		if( $title2 && $title2->exists() && $opts->getValue( 'rev2' ) == '' ) {
			$pda = new Article( $title2 );
			$pdi = $pda->getID();
			$pdLastRevision = Revision::loadFromPageId( wfGetDB( DB_SLAVE ), $pdi );
			$opts->setValue('rev2', $pdLastRevision->getId() );
		} elseif ( $opts->getValue( 'rev2' ) != '' ) {
			$pdrev = Revision::newFromId( $opts->getValue( 'rev2' ) );
			if( $pdrev ) $opts->setValue( 'page2', $pdrev->getTitle()->getPrefixedText() );
		}

		// Store some objects
		$this->skin = $wgUser->getSkin();
	}

	/**
	 * Show a form for filtering namespace and username
	 *
	 * @param $par String
	 * @return String
	 */
	public function execute( $par ) {
		$this->setHeaders();
		$this->outputHeader();

		$this->setup( $par );

		// Settings
		$this->form();

		if( $this->opts->getValue( 'rev1' ) && $this->opts->getValue( 'rev2' ) ) {
			$title = Title::newFromText( $this->opts->getValue( 'page2' ) );
			$de = new DifferenceEngine( null,
				$this->opts->getValue( 'rev1' ),
				$this->opts->getValue( 'rev2' ),
				null, // rcid
				( $this->opts->getValue( 'action' ) == 'purge' ),
				false );
			$de->showDiffPage( true );
		}
	}

	protected function form() {
		global $wgOut, $wgScript;

		// Consume values
		$page1 = $this->opts->consumeValue( 'page1' );
		$page2 = $this->opts->consumeValue( 'page2' );
		$rev1 = $this->opts->consumeValue( 'rev1' );
		$rev2 = $this->opts->consumeValue( 'rev2' );

		// Store query values in hidden fields so that form submission doesn't lose them
		$hidden = array();
		foreach ( $this->opts->getUnconsumedValues() as $key => $value ) {
			$hidden[] = Xml::hidden( $key, $value );
		}
		$hidden = implode( "\n", $hidden );

		$form = Html::openElement( 'form', array( 'action' => $wgScript ) ) .
			Html::hidden( 'title', $this->getTitle()->getPrefixedDBkey() ) .
			Xml::fieldset( wfMsg( 'compare-selector' ) ) .
			Html::openElement( 'table', array( 'id' => 'mw-diff-table', 'style' => 'width:100%' ) ) .
			"<tr>
				<td class='mw-label' style='width:10%'>" .
					Html::element( 'label', array( 'for' => 'page1' ), wfMsg( 'compare-page1' ) ) .
				"</td>
				<td class='mw-input' style='width:40%'>" .
					Html::input( 'page1', $page1, 'text', array( 'size' => 40, 'id' => 'page1' ) ) .
				"</td>
				<td class='mw-label' style='width:10%'>" .
					Html::element( 'label', array( 'for' => 'page2' ), wfMsg( 'compare-page2' ) ) .
				"</td>
				<td class='mw-input' style='width:40%'>" .
					Html::input( 'page2', $page2, 'text', array( 'size' => 40, 'id' => 'page2' ) ) .
				"</td>
			</tr>" . 
			"<tr>
				<td class='mw-label'>" .
					Html::element( 'label', array( 'for' => 'rev1' ), wfMsg( 'compare-rev1' ) ) .
				"</td>
				<td class='mw-input'>" .
					Html::input( 'rev1', $rev1, 'text', array( 'size' => 8, 'id' => 'rev1' ) ) .
				"</td>
				<td class='mw-label'>" .
					Html::element( 'label', array( 'for' => 'rev2' ), wfMsg( 'compare-rev2' ) ) .
				"</td>
				<td class='mw-input'>" .
					Html::input( 'rev2', $rev2, 'text', array( 'size' => 8, 'id' => 'rev2' ) ) .
				"</td>
			</tr>" . 
			"<tr> <td></td>
				<td class='mw-submit' colspan='3'>" .
					Xml::submitButton( wfMsg( 'compare-submit' ) ) .
				"</td>
			</tr>" .
			Html::closeElement( 'table' ) .
			Html::closeElement( 'fieldset' ) .
			$hidden .
			Html::closeElement( 'form' );

		$wgOut->addHTML( $form );
	}
}
