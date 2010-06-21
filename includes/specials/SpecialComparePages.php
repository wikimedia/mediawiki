<?php
/**
 * Copyright (C) 2010 Derk-Jan Hartman <hartman@videolan.org>
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
 */

/**
 * implements Special:ComparePages
 * @ingroup SpecialPage
 */
class SpecialComparePages extends SpecialPage {

	// Stored objects
	protected $opts, $skin;

	// Some internal settings
	protected $showNavigation = false;

	public function __construct() {
		parent::__construct( 'ComparePages' );
		$this->includable( false );	
	}

	protected function setup( $par ) {
		global $wgRequest, $wgUser, $wgEnableNewpagesUserFilter;

		// Options
		$opts = new FormOptions();
		$this->opts = $opts; // bind
		$opts->add( 'page1', '' );
		$opts->add( 'page2', '' );
		$opts->add( 'rev1', '' );
		$opts->add( 'rev2', '' );
		$opts->add( 'action', '' );
		$opts->add( 'diffonly', '' );

		// Set values
		$opts->fetchValuesFromRequest( $wgRequest );

		$title1 = Title::newFromText( $opts->getValue( 'page1' ) );
		$title2 = Title::newFromText( $opts->getValue( 'page2' ) );

		if( $title1 && $title1->exists() && $opts->getValue( 'rev1' ) == '' ) {
			$pda = new Article( $title1 );
			$pdi = $pda->getID();
               		$pdLastRevision = Revision::loadFromPageId( wfGetDB( DB_SLAVE ), $pdi );
			$opts->setValue('rev1', $pdLastRevision->getId() );
		} elseif ( $opts->getValue( 'rev1' ) != '' ) {
			$pdrev = Revision::newFromId( $opts->getValue( 'rev1' ) );
			if( $pdrev ) $opts->setValue( 'page1', $pdrev->getTitle()->getPrefixedDBkey() );
		}
		if( $title2 && $title2->exists() && $opts->getValue( 'rev2' ) == '' ) {
			$pda = new Article( $title2 );
			$pdi = $pda->getID();
               		$pdLastRevision = Revision::loadFromPageId( wfGetDB( DB_SLAVE ), $pdi );
			$opts->setValue('rev2', $pdLastRevision->getId() );
		} elseif ( $opts->getValue( 'rev2' ) != '' ) {
			$pdrev = Revision::newFromId( $opts->getValue( 'rev2' ) );
			if( $pdrev ) $opts->setValue( 'page2', $pdrev->getTitle()->getPrefixedDBkey() );
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
		global $wgLang, $wgOut;

		$this->setHeaders();
		$this->outputHeader();

		$this->setup( $par );

		// Settings
		$this->form();

		if( $this->opts->getValue( 'rev1' ) && $this->opts->getValue( 'rev2' ) ) {
			$de = new DifferenceEngine( null, 
				$this->opts->getValue( 'rev1' ),
				$this->opts->getValue( 'rev2' ),
				null, // rcid
				($this->opts->getValue( 'action') == "purge" ? true : false ),
				false );
			$de->showDiffPage( (bool)$this->opts->getValue( 'diffonly' ) );
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

		$form = Xml::openElement( 'form', array( 'action' => $wgScript ) ) .
			Xml::hidden( 'title', $this->getTitle()->getPrefixedDBkey() ) .
			Xml::fieldset( wfMsg( 'compare-selector') ) .
			Xml::openElement( 'table', array( 'id' => 'mw-diff-table', 'width' => '100%' ) ) .
			"<tr>
				<td class='mw-label' width='10%'>" .
					Xml::label( wfMsg( 'compare-page1' ), 'page1' ) .
				"</td>
				<td class='mw-input' width='40%'>" .
					Xml::input( 'page1', 40, $page1, array( 'type' => 'text' ) ) .
				"</td>
				<td class='mw-label' width='10%'>" .
					Xml::label( wfMsg( 'compare-page2' ), 'page2' ) .
				"</td>
				<td class='mw-input' width='40%'>" .
					Xml::input( 'page2', 40, $page2, array( 'type' => 'text' ) ) .
				"</td>
			</tr>" . 
			"<tr>
				<td class='mw-label'>" .
					Xml::label( wfMsg( 'compare-rev1' ), 'rev1' ) .
				"</td>
				<td class='mw-input'>" .
					Xml::input( 'rev1', 8, $rev1, array( 'type' => 'text' ) ) .
				"</td>
				<td class='mw-label'>" .
					Xml::label( wfMsg( 'compare-rev2' ), 'rev2' ) .
				"</td>
				<td class='mw-input'>" .
					Xml::input( 'rev2', 8, $rev2, array( 'type' => 'text' ) ) .
				"</td>
			</tr>" . 
			"<tr> <td></td>
				<td class='mw-submit' colspan='3'>" .
					Xml::submitButton( wfMsg( 'compare-submit') ) .
				"</td>
			</tr>" .
			Xml::closeElement( 'table' ) .
			Xml::closeElement( 'fieldset' ) .
			$hidden .
			Xml::closeElement( 'form' );

		$wgOut->addHTML( $form );
	}
}
