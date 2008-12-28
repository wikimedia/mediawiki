<?php

/**
 * implements Special:Interwiki
 * @ingroup SpecialPage
 */
class SpecialInterwiki extends SpecialPage {
	
	function __construct() {
		parent::__construct( 'Interwiki' );
	}
	
	function execute( $par ) {
		global $wgRequest, $wgOut, $wgUser;

		$this->setHeaders();
		$this->outputHeader();

		$admin = $wgUser->isAllowed( 'interwiki' );
		if ( $admin ) {
			$wgOut->setPagetitle( wfMsg( 'interwiki' ) );
		} else {
			$wgOut->setPagetitle( wfMsg( 'interwiki-title-norights' ) );
		}
		$action = $wgRequest->getVal( 'action', $par );

		switch( $action ){
		case "delete":
		case "edit" :
		case "add" :
			if( !$admin ){
				$wgOut->permissionRequired( 'interwiki' );
				return;
			}
			$this->showForm( $action );
			break;
		case "submit":
			if( !$admin ){
				$wgOut->permissionRequired( 'interwiki' );
				return;
			}
			if( !$wgRequest->wasPosted() || !$wgUser->matchEditToken( $wgRequest->getVal( 'wpEditToken' ) ) ) {
				$wgOut->addWikiMsg( 'sessionfailure' );
				return;
			}
			$this->doSubmit();
			break;
		default:
			$this->showList( $admin );
			break;
		}
	}

	function showForm( $action ) {
		global $wgRequest, $wgUser, $wgOut;
		
		$actionUrl = $this->getTitle()->getLocalURL( 'action=submit' );
		$token = $wgUser->editToken();
		$defaultreason = $wgRequest->getVal( 'wpInterwikiReason' ) ? $wgRequest->getVal( 'wpInterwikiReason' ) : wfMsgForContent( 'interwiki_defaultreason' );
		
		switch( $action ){
		case "delete":

			$prefix = $wgRequest->getVal( 'prefix' );
			$button = wfMsg( 'delete' );
			$topmessage = wfMsg( 'interwiki_delquestion', $prefix );
			$deletingmessage = wfMsgExt( 'interwiki_deleting', array( 'parseinline' ), $prefix );
			$reasonmessage = wfMsg( 'deletecomment' );

			$wgOut->addHTML(
				Xml::openElement( 'fieldset' ) .
				Xml::element( 'legend', null, $topmessage ) .
				Xml::openElement( 'form', array('id'=> 'mw-interwiki-deleteform', 'method'=> 'post', 'action' => $actionUrl ) ) .
				Xml::openElement( 'table' ) .
				"<tr><td>$deletingmessage</td></tr>".
				'<tr><td class="mw-label">' . Xml::label( $reasonmessage, 'mw-interwiki-deletereason') . '</td>' .
				'<td class="mw-input">' .
				Xml::input( 'wpInterwikiReason', 60, $defaultreason, array( 'tabindex' => '1', 'id' => 'mw-interwiki-deletereason', 'maxlength' => '200' ) ) . 
				'</td></tr>' . 
				'<tr><td class="mw-submit">' . Xml::submitButton( $button, array( 'id' => 'mw-interwiki-submit' ) ) .
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
			if( $action == "edit" ){
				$prefix = $wgRequest->getVal( 'prefix' );
				$dbr = wfGetDB( DB_SLAVE );
				$row = $dbr->selectRow( 'interwiki', '*', array( 'iw_prefix' => $prefix ) );
				if( !$row ){
					$this->error( wfMsg( 'interwiki_editerror', $prefix ) );
					return;
				}
				$prefix = '<tt>' . htmlspecialchars( $row->iw_prefix ) . '</tt>';
				$defaulturl = $row->iw_url;
				$trans = $row->iw_trans;
				$local = $row->iw_local;
				$old = Xml::hidden( 'wpInterwikiPrefix', $row->iw_prefix );
				$topmessage = wfMsgExt( 'interwiki_edittext', array( 'parseinline' ) );
				$intromessage = wfMsgExt( 'interwiki_editintro', array( 'parseinline' ) );
				$button = wfMsg( 'edit' );
			} else {
				$prefix = $wgRequest->getVal( 'wpInterwikiPrefix' ) ? $wgRequest->getVal( 'wpInterwikiPrefix' ) : $wgRequest->getVal( 'prefix' );
				$prefix = Xml::input( 'wpInterwikiPrefix', 20, $prefix, array( 'tabindex'=>'1', 'id'=>'mw-interwiki-prefix', 'maxlength'=>'20' ) );
				$local = $wgRequest->getCheck( 'wpInterwikiLocal' );
				$trans = $wgRequest->getCheck( 'wpInterwikiTrans' );
				$old = '';
				$defaulturl = $wgRequest->getVal( 'wpInterwikiURL' ) ? $wgRequest->getVal( 'wpInterwikiURL' ) : wfMsg( 'interwiki_defaulturl' );
				$topmessage = wfMsgExt( 'interwiki_addtext', array( 'parseinline' ) );
				$intromessage = wfMsgExt( 'interwiki_addintro', array( 'parseinline' ) );
				$button = wfMsg( 'interwiki_addbutton' );
			}

			$prefixmessage = wfMsgHtml( 'interwiki_prefix' );
			$localmessage = wfMsg( 'interwiki_local' );
			$transmessage = wfMsg( 'interwiki_trans' );
			$reasonmessage = wfMsg( 'interwiki_reasonfield' );
			$urlmessage = wfMsg( 'interwiki_url' );

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
				'<tr><td class="mw-submit">' . Xml::submitButton( $button, array( 'id'=>'mw-interwiki-submit' ) ) . '</td></tr>' .
				Xml::closeElement( 'table' ) .
				Xml::closeElement( 'form' ) .
				Xml::closeElement( 'fieldset' )
			);
			break;
		}
	}

	function doSubmit() {
		global $wgRequest, $wgOut;
		$prefix = $wgRequest->getVal( 'wpInterwikiPrefix' );
		$do = $wgRequest->getVal( 'wpInterwikiAction' );
		if( preg_match( '/[\s:&=]/', $prefix ) ) {
			$this->error( wfMsg( 'interwiki-badprefix', $prefix ) );
			$this->showForm( $do );
			return;
		}
		$reason = $wgRequest->getText( 'wpInterwikiReason' );
		$selfTitle = $this->getTitle();
		$dbw = wfGetDB( DB_MASTER );
		switch( $do ){
		case "delete":
			$dbw->delete( 'interwiki', array( 'iw_prefix' => $prefix ), __METHOD__ );

			if ( $dbw->affectedRows() == 0 ) {
				$this->error( wfMsg( 'interwiki_delfailed', $prefix ) );
				$this->showForm( $do );
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
			$data = array( 'iw_prefix' => $prefix, 'iw_url' => $theurl,
				'iw_local'  => $local, 'iw_trans'  => $trans );

			if( $do == 'add' ){
				$dbw->insert( 'interwiki', $data, __METHOD__, 'IGNORE' );
			} else {
				$dbw->update( 'interwiki', $data, array( 'iw_prefix' => $prefix ), __METHOD__, 'IGNORE' );
			}

			if( $dbw->affectedRows() == 0 ) {
				$this->error( wfMsg( "interwiki_{$do}failed", $prefix ) );
				$this->showForm( $do );
			} else {
				$wgOut->addWikiMsg( "interwiki_{$do}ed", $prefix );
				$wgOut->returnToMain( false, $selfTitle );
				$log = new LogPage( 'interwiki' );
				$log->addEntry( 'iw_'.$do, $selfTitle, $reason, array( $prefix, $theurl, $trans, $local ) );
			}
			break;
		}	
	}

	function showList( $admin ) {
		global $wgUser, $wgOut; 
		$prefixmessage = wfMsgHtml( 'interwiki_prefix' );
		$urlmessage = wfMsgHtml( 'interwiki_url' );
		$localmessage = wfMsgHtml( 'interwiki_local' );
		$transmessage = wfMsgHtml( 'interwiki_trans' );

		$wgOut->addWikiMsg( 'interwiki_intro' );
		$selfTitle = $this->getTitle();

		if ( $admin ) {
			$skin = $wgUser->getSkin();
			$addtext = wfMsgHtml( 'interwiki_addtext' );
			$addlink = $skin->link( $selfTitle, $addtext, array(), array( 'action' => 'add' ) );
			$wgOut->addHTML( '<ul>' . '<li>' . $addlink . '</li>' . '</ul>' );
		}

		$dbr = wfGetDB( DB_SLAVE );
		$res = $dbr->select( 'interwiki', '*' );
		$numrows = $res->numRows();
		if ( $numrows == 0 ) {
			$this->error( wfMsgWikiHtml( 'interwiki_error' ) );
			return;
		}
		
		$out = "
		<br />
		<table width='100%' style='border:1px solid #aaa;' class='wikitable'>
		<tr id='interwikitable-header'><th>$prefixmessage</th> <th>$urlmessage</th> <th>$localmessage</th> <th>$transmessage</th>";
		if( $admin ) {
			$deletemessage = wfMsgHtml( 'delete' );
			$editmessage = wfMsgHtml( 'edit' );
			$out .= "<th>$editmessage</th>";
		}
		$out .= "</tr>\n";
		
		while( $s = $res->fetchObject() ) {
			$prefix = htmlspecialchars( $s->iw_prefix );
			$url = htmlspecialchars( $s->iw_url );
			$trans = htmlspecialchars( $s->iw_trans );
			$local = htmlspecialchars( $s->iw_local );
			$out .= "<tr class='mw-interwikitable-row'>
				<td class='mw-interwikitable-prefix'>$prefix</td>
				<td class='mw-interwikitable-url'>$url</td>
				<td class='mw-interwikitable-local'>$local</td>
				<td class='mw-interwikitable-trans'>$trans</td>";
			if( $admin ) {
				$out .= '<td class="mw-interwikitable-modify">';
				$out .= $skin->link( $selfTitle, $editmessage, array(),
					array( 'action' => 'edit', 'prefix' => $s->iw_prefix ) );
				$out .= ', ';
				$out .= $skin->link( $selfTitle, $deletemessage, array(),
					array( 'action' => 'delete', 'prefix' => $s->iw_prefix ) );
				$out .= '</td>';
			}

			$out .= "\n</tr>\n";
		}
		$res->free();
		$out .= "</table><br />";
		$wgOut->addHTML( $out );
	}
	
	function error( $msg ) {
		global $wgOut;
		$wgOut->addHTML( Xml::tags( 'p', array( 'class' => 'error' ), $msg ) );
	}
}
