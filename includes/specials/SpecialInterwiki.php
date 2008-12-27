<?php

/**
 * implements Special:Interwiki
 * @ingroup SpecialPage
 */
class SpecialInterwiki extends SpecialPage {
	
	function __construct() {
		parent::__construct( 'Interwiki' );
	}

	
	function execute( $par = null ) {
		global $wgRequest, $wgOut, $wgUser;

		$admin = $wgUser->isAllowed( 'interwiki' );

		$this->setHeaders();
		if( $admin ){
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
			$encPrefix = htmlspecialchars( $prefix );
			$actionUrl = $selfTitle->escapeLocalURL( "action=submit" );
			$button = wfMsgHtml( 'delete' );
			$topmessage = wfMsgHtml( 'interwiki_delquestion', $encPrefix );
			$deletingmessage = wfMsgHtml( 'interwiki_deleting', $encPrefix );
			$reasonmessage = wfMsgHtml( 'deletecomment' );
			$defaultreason = wfMsgForContent( 'interwiki_defaultreason' );
			$token = htmlspecialchars( $wgUser->editToken() );

			$wgOut->addHTML(
				"<fieldset>
				<legend>$topmessage</legend>
				<form id=\"delete\" method=\"post\" action=\"{$actionUrl}\">
				<table><tr>
				<td>$deletingmessage</td>
				</tr><tr>
				<td>$reasonmessage &nbsp; <input tabindex='1' type='text' name=\"reason\" maxlength='200' size='60' value='$defaultreason' /></td>
				</tr><tr><td>
				<input type='submit' name='delete' id=\"interwikideletebutton\" value='{$button}' />
				<input type='hidden' name='prefix' value='{$encPrefix}' />
				<input type='hidden' name='do' value='delete' />
				<input type='hidden' name='wpEditToken' value='{$token}' />
				</td></tr></table>
				</form>
				</fieldset>\n"
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
				$prefix = htmlspecialchars( $row->iw_prefix );
				$defaulturl = htmlspecialchars( $row->iw_url );
				$trans = $row->iw_trans ? " checked='checked'" : '' ;
				$local = $row->iw_local ? " checked='checked'" : '';
				$old = "<input type='hidden' name='prefix' value='" . htmlspecialchars( $row->iw_prefix ) . "' />";
				$topmessage = wfMsgHtml( 'interwiki_edittext' );
				$intromessage = wfMsgHtml( 'interwiki_editintro' );
				$button = wfMsgHtml( 'edit' );
			} else {
				$prefix = "<input tabindex='1' type='text' name='prefix' maxlength='20' size='20' />";
				$local = '';
				$trans = '';
				$old = '';
				$defaulturl = wfMsgHtml( 'interwiki_defaulturl' );
				$topmessage = wfMsgHtml( 'interwiki_addtext' );
				$intromessage = wfMsgHtml( 'interwiki_addintro' );
				$button = wfMsgHtml( 'interwiki_addbutton' );
			}

			$actionUrl = $selfTitle->escapeLocalURL( 'action=submit' );
			$prefixmessage = wfMsgHtml( 'interwiki_prefix' );
			$localmessage = wfMsgHtml( 'interwiki_local' );
			$transmessage = wfMsgHtml( 'interwiki_trans' );
			$reasonmessage = wfMsgHtml( 'interwiki_reasonfield' );
			$urlmessage = wfMsgHtml( 'interwiki_url' );
			$token = htmlspecialchars( $wgUser->editToken() );
			$defaultreason = htmlspecialchars( wfMsgForContent( 'interwiki_defaultreason' ) );

			$wgOut->addHTML(
				"<fieldset>
				<legend>$topmessage</legend>
				$intromessage
				<form id='{$action}' method='post' action='{$actionUrl}'>
				<table id='interwikitable-{$action}'><tr>
				<td>$prefixmessage</td>
				<td>$prefix</td>
				</tr><tr>
				<td>$localmessage</td>
				<td><input type='checkbox' id='local' name='local' {$local}/></td>
				</tr><tr>
				<td>$transmessage</td>
				<td><input type='checkbox' id='trans' name='trans' {$trans}/></td>
				</tr><tr>
				<td>$urlmessage</td>
				<td><input tabindex='1' type='text' name='theurl' maxlength='200' size='60' value='$defaulturl' /></td>
				</tr><tr>
				<td>$reasonmessage</td>
				<td><input tabindex='1' type='text' name='reason' maxlength='200' size='60' value='$defaultreason' /></td>
				</tr></table>
				<input type='submit' name='{$action}'  id='interwiki{$action}button' value='{$button}' />
				<input type='hidden' name='do' value='{$action}' />
				$old
				<input type='hidden' name='wpEditToken' value='{$token}' />
				</form>
				</fieldset>\n"
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

			$prefix = $wgRequest->getVal('prefix');
			$reason = $wgRequest->getText('reason');
			$do = $wgRequest->getVal('do');
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
				$theurl = $wgRequest->getVal('theurl');
				$local = $wgRequest->getCheck('local') ? 1 : 0;
				$trans = $wgRequest->getCheck('trans') ? 1 : 0;
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

			$wgOut->addWikiText( wfMsg( 'interwiki_intro', '[http://www.mediawiki.org/wiki/Interwiki_table MediaWiki.org]' ) );

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
				$out .= "<tr id='interwikitable-row'>
					<td id='interwikitable-prefix'>$prefix</td>
					<td id='interwikitable-url'>$url</td>
					<td id='interwikitable-local'>$local</td>
					<td id='interwikitable-trans'>$trans</td>";
				if( $admin ) {
					$out .= '<td id="interwikitable-modify">';
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
