<?php
/**
 * @file
 * @ingroup SpecialPage
 *
 * @author Brion Vibber
 * @copyright Copyright © 2005-2008, Brion Vibber
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License 2.0 or later
 */

/**
 * implements Special:Nuke
 */

class SpecialNuke extends SpecialPage {
	function __construct() {
		parent::__construct( 'Nuke', 'nuke' );
	}

	function execute( $par ){
		global $wgUser, $wgRequest;

		if( !$this->userCanExecute( $wgUser ) ){
			$this->displayRestrictionError();
			return;
		}

		$this->setHeaders();
		$this->outputHeader();

		$target = $wgRequest->getText( 'target', $par );
		$reason = $wgRequest->getText( 'wpReason',
			wfMsgForContent( 'nuke-defaultreason', $target ) );
		$posted = $wgRequest->wasPosted() &&
			$wgUser->matchEditToken( $wgRequest->getVal( 'wpEditToken' ) );
		if( $posted ) {
			$pages = $wgRequest->getArray( 'pages' );
			if( $pages ) {
				return $this->doDelete( $pages, $reason );
			}
		}
		if( $target != '' ) {
			$this->listForm( $target, $reason );
		} else {
			$this->promptForm();
		}
	}

	function promptForm() {
		global $wgUser, $wgOut;
		$sk =& $wgUser->getSkin();

		$nuke = Title::makeTitle( NS_SPECIAL, 'Nuke' );
		$submit = Xml::element( 'input', array( 'type' => 'submit', 'value' => wfMsgHtml( 'nuke-submit-user' ) ) );

		$wgOut->addWikiText( wfMsg( 'nuke-tools' ) );
		$wgOut->addHTML( Xml::element( 'form', array(
				'action' => $nuke->getLocalURL( 'action=submit' ),
				'method' => 'post' ),
				null ) .
			Xml::element( 'input', array(
				'type' => 'text',
				'size' => 40,
				'name' => 'target' ) ) .
			"\n$submit\n" );

		$wgOut->addHTML( "</form>" );
	}

	function listForm( $username, $reason ) {
		global $wgUser, $wgOut, $wgLang;

		$pages = $this->getNewPages( $username );
		$escapedName = wfEscapeWikiText( $username );
		if( count( $pages ) == 0 ) {
			$wgOut->addWikiText( wfMsg( 'nuke-nopages', $escapedName ) );
			return $this->promptForm();
		}
		$wgOut->addWikiText( wfMsg( 'nuke-list', $escapedName ) );

		$nuke = $this->getTitle();
		$submit = Xml::element( 'input', array( 'type' => 'submit', 'value' => wfMsgHtml( 'nuke-submit-delete' ) ) );

		$wgOut->addHTML( Xml::element( 'form', array(
			'action' => $nuke->getLocalURL( 'action=delete' ),
			'method' => 'post' ),
			null ) .
			"\n<div>" .
			wfMsgHtml( 'deletecomment' ) . ' ' .
			Xml::element( 'input', array(
				'name' => 'wpReason',
				'value' => $reason,
				'size' => 60 ) ) .
			"</div><br />" .
			$submit .
			Xml::element( 'input', array(
				'type' => 'hidden',
				'name' => 'wpEditToken',
				'value' => $wgUser->editToken() ) ) .
			"\n<ul>\n" );

		$sk =& $wgUser->getSkin();
		foreach( $pages as $info ) {
			list( $title, $edits ) = $info;
			$image = $title->getNamespace() == NS_IMAGE ? wfLocalFile( $title ) : false;
			$thumb = $image && $image->exists() ? $image->getThumbnail( 120, 120 ) : false;

			$wgOut->addHTML( '<li>' .
				Xml::element( 'input', array(
					'type' => 'checkbox',
					'name' => "pages[]",
					'value' => $title->getPrefixedDbKey(),
					'checked' => 'checked' ) ) .
				'&nbsp;' .
				( $thumb ? $thumb->toHtml( array( 'desc-link' => true ) ) : '' ) .
				$sk->makeKnownLinkObj( $title ) .
				'&nbsp;(' .
				$sk->makeKnownLinkObj( $title, wfMsgExt( 'nchanges', array( 'parsemag' ), $wgLang->formatNum( $edits ) ), 'action=history' ) .
				")</li>\n" );
		}
		$wgOut->addHTML( "</ul>\n$submit</form>" );
	}

	function getNewPages( $username ) {
		$dbr = wfGetDB( DB_SLAVE );
		$result = $dbr->select( 'recentchanges',
			array( 'rc_namespace', 'rc_title', 'rc_timestamp', 'COUNT(*) AS edits' ),
			array(
				'rc_user_text' => $username,
				'(rc_new = 1) OR (rc_log_type = "upload" AND rc_log_action = "upload")'
			),
			__METHOD__,
			array(
				'ORDER BY' => 'rc_timestamp DESC',
				'GROUP BY' => 'rc_namespace, rc_title'
			)
		);
		$pages = array();
		while( $row = $dbr->fetchObject( $result ) ) {
			$pages[] = array( Title::makeTitle( $row->rc_namespace, $row->rc_title ), $row->edits );
		}
		$dbr->freeResult( $result );
		return $pages;
	}

	function doDelete( $pages, $reason ) {
		foreach( $pages as $page ) {
			$title = Title::newFromUrl( $page );
			$file = $title->getNamespace() == NS_IMAGE ? wfLocalFile( $title ) : false;
			if ( $file ) {
				$oldimage = null; // Must be passed by reference
				FileDeleteForm::doDelete( $title, $file, $oldimage, $reason, false );
			} else {
				$article = new Article( $title );
				$article->doDelete( $reason );
			}
		}
	}
}
