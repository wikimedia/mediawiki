<?php

/* This code was adapted from CreatePage.php from: Travis Derouin <travis@wikihow.com> for the Uniwiki extension CreatePage
 * Originally licensed as: GNU GPL v2.0 or later
 *
 * This page has been copied and adapted from the Uniwiki extension CreatePage
 * Originally licensed as: http://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @license GNU GPL v3.0 http://www.gnu.org/licenses/gpl-3.0.txt
 * @author Travis Derouin
 * @author Merrick Schaefer
 * @author Mark Johnston
 * @author Evan Wheeler
 * @author Adam Mckaig (at UNICEF)
 * @author Siebrand Mazeland (integrated into MediaWiki core)
 * @ingroup SpecialPage
 */
class SpecialCreatePage extends SpecialPage {

	function __construct() {
		SpecialPage::SpecialPage( 'CreatePage', 'createpage' );
	}

	public function execute( $par ) {
		global $wgOut, $wgRequest, $wgUser;

		$this->setHeaders();

		if ( !$this->userCanExecute( $wgUser ) ) {
			$this->displayRestrictionError();
			return;
		}

		$this->outputHeader();

		// check to see if we are trying to create a page
		$target = $wgRequest->getVal( 'target', $par );
		$title = Title::newFromText( $target );

		// check for no title
		if ( $wgRequest->wasPosted() && $target === '' ) {
			$this->error( wfMsg( 'createpage-entertitle' ) );
		} elseif ( $target !== null ) {
			if ( !$title instanceof Title ) {
				// check for invalid title
				$this->error( wfMsg( 'createpage-badtitle', $target ) );
			} else if ( $title->getArticleID() > 0 ) {
				// if the title exists then let the user know and give other options
				$wgOut->addWikiMsg( 'createpage-titleexists', $title->getFullText() );
				$wgOut->addHTML( '<br />' );
				$skin = $wgUser->getSkin();
				$editlink = $skin->makeLinkObj( $title, wfMsgHTML( 'createpage-editexisting' ), 'action=edit' );
				$thislink = $skin->makeLinkObj( $this->getTitle(), wfMsgHTML( 'createpage-tryagain' ) );
				$wgOut->addHTML( $editlink . '<br />' . $editlink );
				return;
			} else {
				/* TODO - may want to search for closely named pages and give
				 * other options here... */

				// otherwise, redirect them to the edit page for their title
				$wgOut->redirect( $title->getEditURL() );
			}
		}

		// if this is just a normal GET, then output the form

		// prefill the input with the title, if it was passed along
		$newTitle = false;
		$newTitleText = $wgRequest->getVal( 'newtitle', null );
		if ( $newTitleText != null ) {
			$newTitle = Title::newFromURL( $newTitleText );
			if ( is_null( $newTitle ) )
				$newTitle = $newTitleText;
			else
				$newTitle = $newTitle->getText();
		}

		// output the form
		$wgOut->addHTML(
			Xml::openElement( 'fieldset' ) .
			Xml::element( 'legend', null, wfMsg( 'createpage' ) ) . # This should really use a different message
			wfMsgWikiHtml( 'createpage-instructions' ) .
			Xml::openElement( 'form', array( 'method' => 'post', 'name' => 'createpageform', 'action' => $this->getTitle()->getLocalUrl() ) ) .
			Xml::element( 'input', array( 'type' => 'text', 'name' => 'target', 'size' => 50, 'value' => $newTitle ) ) .
			'<br />' .
			Xml::element( 'input', array( 'type' => 'submit', 'value' => wfMsg( 'createpage-submitbutton' ) ) ) .
			Xml::closeElement( 'form' ) .
			Xml::closeElement( 'fieldset' )
		);
	}
	/*
	 * Function to output an error message
	 * @param $msg String: message text or HTML
	*/
	function error( $msg ) {
		global $wgOut;
		$wgOut->addHTML( Xml::element( 'p', array( 'class' => 'error' ), $msg ) );
	}
}
