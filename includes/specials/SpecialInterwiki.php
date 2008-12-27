<?php

/**
 * implements Special:Interwiki
 * @ingroup SpecialPage
 */
class SpecialInterwiki extends SpecialPage {
	
	function __construct() {
		parent::__construct( 'Interwiki', 'interwiki' );
	}
	
	function execute( $par ) {
		global $wgRequest, $wgOut, $wgUser;

		$this->setHeaders();
		$admin = $this->userCanExecute( $wgUser );
		if ( $admin ) {
			$wgOut->setPagetitle( wfMsg( 'interwiki' ) );
		} else {
			$wgOut->setPagetitle( wfMsg( 'interwiki-title-norights' ) );
		}
		$action = $wgRequest->getVal( 'action', $par );

		// checking
		$selfTitle = $this->getTitle();

		// Protect administrative actions against malicious requests
		$safePost = $wgRequest->wasPosted() &&
			$wgUser->matchEditToken( $wgRequest->getVal( 'wpEditToken' ) );

		switch( $action ){
		case "delete":
			if( !$admin ){
				$wgOut->permissionRequired('interwiki');
				return;
			}

			$prefix = $wgRequest->getVal( 'prefix' );
			$actionUrl = $selfTitle->getLocalURL( "action=submit" );
			$button = wfMsg( 'delete' );
			$topmessage = wfMsg( 'interwiki_delquestion', $encPrefix );
			$deletingmessage = wfMsgHtml( 'interwiki_deleting', $encPrefix );
			$reasonmessage = wfMsg( 'deletecomment' );
			$defaultreason = wfMsgForContent( 'interwiki_defaultreason' );
			$token = $wgUser->editToken();

			$wgOut->addHTML(
				Xml::openElement( 'fieldset' ) .
				Xml::element( 'legend', null, $topmessage ) .
				Xml::openElement( 'form', array('id'=> 'mw-interwiki-deleteform', 'method'=> 'post', 'action'=>$actionUrl) ) .
				Xml::openElement( 'table' ) .
				"<tr><td>$deletingmessage</td></tr>".
				'<tr><td class="mw-label">' . Xml::label( $reasonmessage, 'mw-interwiki-deletereason') . '</td>' .
				'<td class="mw-input">' .
				Xml::input( 'wpInterwikiReason', 60, $defaultreason, array('tabindex'=>'1', 'id'=>'mw-interwiki-deletereason', 'maxlength'=>'200') ) . 
				'</td></tr>' . 
				'<tr><td class="mw-submit">' . Xml::submitButton( $button, array('id'=>'mw-interwiki-submit') ) .
				Xml::hidden( 'wpInterwikiPrefix', $prefix ) .
				Xml::hidden( 'wpInterwikiAction', $action ) .
				Xml::hidden( 'wpEditToken', $token ) .
				'</td></tr>' .
				Xml::closeElement( 'table' ) .
				Xml::closeElement( 'form' ) .
				Xml::closeElement( 'fieldset' )
			);
			break;
		case "edit" :
		case "add" :
			if( !$admin ){
				$wgOut->permissionRequired( 'interwiki' );
				return;
			}
			if( $action == "edit" ){
				$prefix = $wgRequest->getVal( 'prefix' );
				$dbr = wfGetDB( DB_SLAVE );
				$row = $dbr->selectRow( 'interwiki', '*', array( 'iw_prefix' => $prefix ) );
				if( !$row ){
					$wgOut->wrapWikiMsg( '<div class="errorbox">$1</div>', array( 'interwiki_editerror', $prefix ) );
					return;
				}
				$prefix = '<tt>' . htmlspecialchars( $row->iw_prefix ) . '</tt>';
				$defaulturl = $row->iw_url;
				$trans = $row->iw_trans;
				$local = $row->iw_local;
				$old = Xml::hidden( 'wpInterwikiPrefix', $row->iw_prefix );
				$topmessage = wfMsgExt( 'interwiki_edittext', array('parseinline') );
				$intromessage = wfMsgExt( 'interwiki_editintro', array('parseinline') );
				$button = wfMsg( 'edit' );
			} else {
				$prefix = Xml::input( 'wpInterwikiPrefix', 20, false, array( 'tabindex'=>'1', 'id'=>'mw-interwiki-prefix', 'maxlength'=>'20') );
				$local = false;
				$trans = false;
				$old = '';
				$defaulturl = wfMsg( 'interwiki_defaulturl' );
				$topmessage = wfMsgExt( 'interwiki_addtext', array('parseinline') );
				$intromessage = wfMsgExt( 'interwiki_addintro', array('parseinline') );
				$button = wfMsg( 'interwiki_addbutton' );
			}

			$actionUrl = $selfTitle->getLocalURL( 'action=submit' );
			$prefixmessage = wfMsgHtml( 'interwiki_prefix' );
			$localmessage = wfMsg( 'interwiki_local' );
			$transmessage = wfMsg( 'interwiki_trans' );
			$reasonmessage = wfMsg( 'interwiki_reasonfield' );
			$urlmessage = wfMsg( 'interwiki_url' );
			$token = $wgUser->editToken();
			$defaultreason = wfMsgForContent( 'interwiki_defaultreason' );

			$wgOut->addHTML(
				Xml::openElement( 'fieldset' ) .
				Xml::element( 'legend', null, $topmessage ) .
				$intromessage .
				Xml::openElement( 'form', array('id'=> 'mw-interwiki-editform', 'method'=> 'post', 'action'=>$actionUrl) ) .
				Xml::openElement( 'table', array('id'=>"mw-interwiki-$action") ) .
				"<tr><td class='mw-label'>$prefixmessage</td><td><tt>$prefix</tt></td></tr>" .
				"<tr><td class='mw-label'>" . Xml::label( $localmessage, 'mw-interwiki-local' ) . '</td>' .
				"<td class='mw-input'>" . Xml::check( 'wpInterwikiLocal', $local, array('id'=>'mw-interwiki-local') ) . '</td></tr>' .
				'<tr><td class="mw-label">' . Xml::label( $transmessage, 'mw-interwiki-trans' ) . '</td>' .
				'<td class="mw-input">' .  Xml::check( 'wpInterwikiTrans', $trans, array('id'=>'mw-interwiki-trans') ) . '</td></tr>' .
				'<tr><td class="mw-label">' . Xml::label( $urlmessage, 'mw-interwiki-url' ) . '</td>' .
				'<td class="mw-input">' . Xml::input( 'wpInterwikiURL', 60, $defaulturl, array('tabindex'=>'1', 'maxlength'=>'200', 'id'=>'mw-interwiki-url') ) . '</td></tr>' .
				'<tr><td class="mw-label">' . Xml::label( $reasonmessage, 'mw-interwiki-editreason' ) . '</td>' .
				'<td class="mw-input">' . Xml::input( 'wpInterwikiReason', 60, $defaultreason, array( 'tabindex'=>'1', 'id'=>'mw-interwiki-editreason', 'maxlength'=>'200') ) .
				Xml::hidden( 'wpInterwikiAction', $action ) .
				$old .
				Xml::hidden( 'wpEditToken', $token ) .
				'</td></tr>' .
				'<tr><td class="mw-submit">' . Xml::submitButton( $button, array('id'=>'mw-interwiki-submit') ) . '</td></tr>' .
				Xml::closeElement( 'table' ) .
				Xml::closeElement( 'form' ) .
				Xml::closeElement( 'fieldset' )
			);
			break;
		case "submit":
			if( !$admin ){
				$wgOut->permissionRequired('interwiki');
				return;
			}
			if( !$safePost ){
				$wgOut->addWikiText( wfMsg('sessionfailure') );
				return;
			}

			$prefix = $wgRequest->getVal( 'wpInterwikiPrefix' );
			$reason = $wgRequest->getText( 'wpInterwikiReason' );
			$do = $wgRequest->getVal( 'wpInterwikiAction' );
			$dbw = wfGetDB( DB_MASTER );
			switch( $do ){
			case "delete":
				$dbw->delete( 'interwiki', array( 'iw_prefix' => $prefix ), __METHOD__ );

				if ($dbw->affectedRows() == 0) {
					$wgOut->addWikiText( '<span class="error">' . wfMsg( 'interwiki_delfailed', $prefix ) . '</span>' );
				} else {
					$wgOut->addWikiText( wfMsg( 'interwiki_deleted', $prefix ));
					$wgOut->returnToMain( false, $selfTitle );
					$log = new LogPage( 'interwiki' );
					$log->addEntry( 'iw_delete', $selfTitle, $reason, array( $prefix ) );
				}
				break;
			case "edit":
			case "add":
				$theurl = $wgRequest->getVal( 'wpInterwikiURL' );
				$local = $wgRequest->getCheck( 'wpInterwikiLocal' ) ? 1 : 0;
				$trans = $wgRequest->getCheck( 'wpInterwikiTrans' ) ? 1 : 0;
				$data = array( 'iw_prefix' => $prefix, 'iw_url'    => $theurl,
					'iw_local'  => $local, 'iw_trans'  => $trans );

				if( $do == 'add' ){
					$dbw->insert( 'interwiki', $data, __METHOD__, 'IGNORE' );
				} else {
					$dbw->update( 'interwiki', $data, array( 'iw_prefix' => $prefix ), __METHOD__, 'IGNORE' );
				}

				if( $dbw->affectedRows() == 0 ) {
					$wgOut->addWikiText( '<span class="error">' . wfMsg( "interwiki_{$do}failed", $prefix ) . '</span>' );
				} else {
					$wgOut->addWikiText( wfMsg( "interwiki_{$do}ed", $prefix ));
					$wgOut->returnToMain( false, $selfTitle );
					$log = new LogPage( 'interwiki' );
					$log->addEntry( 'iw_'.$do, $selfTitle, $reason, array( $prefix, $theurl, $trans, $local ) );
				}
				break;
			}
			break;
		default:
			$prefixmessage = wfMsgHtml( 'interwiki_prefix' );
			$urlmessage = wfMsgHtml( 'interwiki_url' );
			$localmessage = wfMsgHtml( 'interwiki_local' );
			$transmessage = wfMsgHtml( 'interwiki_trans' );

			$wgOut->addWikiText( wfMsg( 'interwiki_intro' ) );

			if ($admin) {
				$skin = $wgUser->getSkin();
				$addtext = wfMsgHtml( 'interwiki_addtext' );
				$addlink = $skin->makeLinkObj( $selfTitle, $addtext, 'action=add' );
				$wgOut->addHTML( '<ul>' . '<li>' . $addlink . '</li>' . '</ul>' );
			}

			$out = "
			<br />
			<table width='100%' border='2' id='interwikitable'>
			<tr id='interwikitable-header'><th>$prefixmessage</th> <th>$urlmessage</th> <th>$localmessage</th> <th>$transmessage</th>";
			if( $admin ) {
				$deletemessage = wfMsgHtml( 'delete' );
				$editmessage = wfMsgHtml( 'edit' );
				$out .= "<th>$editmessage</th>";
			}

			$out .= "</tr>\n";

			$dbr = wfGetDB( DB_SLAVE );
			$res = $dbr->select( 'interwiki', '*' );
			$numrows = $dbr->numRows( $res );
			if ($numrows == 0) {
				$errormessage = wfMsgHtml('interwiki_error');
				$out .= "<br /><div class=\"error\">$errormessage</div><br />";
			}
			while( $s = $dbr->fetchObject( $res ) ) {
				$prefix = htmlspecialchars( $s->iw_prefix );
				$url = htmlspecialchars( $s->iw_url );
				$trans = htmlspecialchars( $s->iw_trans );
				$local = htmlspecialchars( $s->iw_local );
				$out .= "<tr class='interwikitable-row'>
					<td class='mw-interwikitable-prefix'>$prefix</td>
					<td class='mw-interwikitable-url'>$url</td>
					<td class='mw-interwikitable-local'>$local</td>
					<td class='mw-interwikitable-trans'>$trans</td>";
				if( $admin ) {
					$out .= '<td class="mw-interwikitable-modify">';
					$out .= $skin->makeLinkObj( $selfTitle, $editmessage,
						'action=edit&prefix=' . urlencode( $s->iw_prefix ) );
					$out .= ', ';
					$out .= $skin->makeLinkObj( $selfTitle, $deletemessage,
						'action=delete&prefix=' . urlencode( $s->iw_prefix ) );
					$out .= '</td>';
				}

				$out .= "\n</tr>\n";
			}
			$dbr->freeResult( $res );
			$out .= "</table><br />";
			$wgOut->addHTML($out);
		}
	}
}
