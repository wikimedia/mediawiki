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
 * @addtogroup SpecialPage
 */

class SpecialCreatePage extends SpecialPage {

	public function __construct() {
		parent::__construct( 'CreatePage', 'createpage' );
	}

	public function execute( $params ) {
		global $wgOut, $wgRequest, $wgUser;

		$this->setHeaders();

		if ( !$this->userCanExecute( $wgUser ) ) {
			$this->displayRestrictionError();
			return;
		}

		$wgOut->addWikiMsg( 'createpage-summary' );

		// check to see if we are trying to create a page
		$target = $wgRequest->getVal ( 'target' );
		$title = Title::newFromText ( $target );

		// check for no title
		if ( $wgRequest->wasPosted() && $target === '' ) {
			$this->error( wfMsg( 'createpage-entertitle' ) );
		}
		// check for invalid title
		elseif ( $wgRequest->wasPosted() && is_null( $title ) ) {
			$this->error( wfMsg( 'createpage-badtitle', $target ) );
		}
		elseif ( $target != null ) {
			if ( $title->getArticleID() > 0 ) {
				// if the title exists then let the user know and give other options
				$wgOut->addWikiText ( wfMsg ( 'createpage-titleexists', $title->getFullText() ) . "<br />" );
				$skin = $wgUser->getSkin();
				$editlink = $skin->makeLinkObj( $title, wfMsg ( 'createpage-editexisting' ), 'action=edit' );
				$thisPage = Title::newFromText ( 'CreatePage', NS_SPECIAL );
				$wgOut->addHTML ( $editlink . '<br />'
					. $skin->makeLinkObj ( $thisPage, wfMsg ( 'createpage-tryagain' ) )
				);
				return;
			} else {
				/* TODO - may want to search for closely named pages and give
				 * other options here... */

				// otherwise, redirect them to the edit page for their title
				$wgOut->redirect ( $title->getEditURL() );
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
		$form = Xml::openElement( 'fieldset' ) .
			Xml::element( 'legend', null, wfMsg( 'createpage' ) ) . # This should really use a different message
			wfMsgWikiHtml( 'createpage-instructions' ) .
			Xml::openElement( 'form', array( 'method' => 'post', 'name' => 'createpageform', 'action' => '' ) ) .
			Xml::element( 'input', array( 'type' => 'text', 'name' => 'target', 'size' => 50, 'value' => $newTitle ) ) .
			'<br />' .
			Xml::element( 'input', array( 'type' => 'submit', 'value' => wfMsgHtml( 'createpage-submitbutton' ) ) ) .
			Xml::closeElement( 'form' ) .
			Xml::closeElement( 'fieldset' );
		$wgOut->addHTML( $form );
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
