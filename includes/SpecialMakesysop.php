<?php
/**
 *
 * @package MediaWiki
 * @subpackage SpecialPage
 */

/**
 *
 */
require_once( "LinksUpdate.php" );

/**
 * Constructor
 */
function wfSpecialMakesysop() {
	global $wgUser, $wgOut, $wgRequest;

	if ( 0 == $wgUser->getID() or $wgUser->isBlocked() ) {
		$wgOut->errorpage( "movenologin", "movenologintext" );
		return;
	}
	if (! $wgUser->isBureaucrat() && ! $wgUser->isDeveloper() ){
		$wgOut->errorpage( "bureaucrattitle", "bureaucrattext" );
		return;
	}
	
	if ( wfReadOnly() ) {
		$wgOut->readOnlyPage();
		return;
	}

	$f = new MakesysopForm( $wgRequest );

	if ( $f->mSubmit ) { 
		$f->doSubmit(); 
	} else { 
		$f->showForm( '' ); 
	}
}

/**
 *
 * @package MediaWiki
 * @subpackage SpecialPage
 */
class MakesysopForm {
	var $mTarget, $mAction, $mRights, $mUser, $mSubmit;

	function MakesysopForm( &$request ) {
		$this->mAction = $request->getText( 'action' );
		$this->mRights = $request->getVal( 'wpRights' );
		$this->mUser = $request->getText( 'wpMakesysopUser' );
		$this->mSubmit = $request->getBool( 'wpMakesysopSubmit' ) && $request->wasPosted();
		$this->mBuro = $request->getBool( 'wpSetBureaucrat' );
	}

	function showForm( $err = '') {
		global $wgOut, $wgUser, $wgLang;

		if ( $wgUser->isDeveloper() ) {
			$wgOut->setPageTitle( wfMsg( "set_user_rights" ) );
		} else {
			$wgOut->setPagetitle( wfMsg( "makesysoptitle" ) );
		}

		$wgOut->addWikiText( wfMsg( "makesysoptext" ) );

		$titleObj = Title::makeTitle( NS_SPECIAL, "Makesysop" );
		$action = $titleObj->escapeLocalURL( "action=submit" );

		if ( "" != $err ) {
			$wgOut->setSubtitle( wfMsg( "formerror" ) );
			$wgOut->addHTML( "<p class='error'>{$err}</p>\n" );
		}
		$namedesc = wfMsg( "makesysopname" );
		if ( !is_null( $this->mUser ) ) {
			$encUser = htmlspecialchars( $this->mUser );
		} else {
			$encUser = "";
		}

		$wgOut->addHTML( "
			<form id=\"makesysop\" method=\"post\" action=\"{$action}\">
			<table border='0'>
			<tr>
				<td align='right'>$namedesc</td>
				<td align='left'>
					<input type='text' size='40' name=\"wpMakesysopUser\" value=\"$encUser\" />
				</td>
			</tr>" 
		);
		
		$makeburo = wfMsg( "setbureaucratflag" );
		$wgOut->addHTML(
			"<tr>
				<td>&nbsp;</td><td align=left>
					<input type=checkbox name=\"wpSetBureaucrat\" value=1>$makeburo
				</td>
			</tr>"
		);

		if ( $wgUser->isDeveloper() ) {
			$rights = wfMsg( "rights" );
			if ( !is_null( $this->mRights ) ) {
				$encRights = htmlspecialchars( $this->mRights );
			} else {
				$encRights = "sysop";
			}

			$wgOut->addHTML( "
				<tr>
					<td align='right'>$rights</td>
					<td align='left'>
						<input type='text' size='40' name=\"wpRights\" value=\"$encRights\" />
					</td>
				</tr>" 
			);
		}

		if ( $wgUser->isDeveloper() ) {
			$mss = wfMsg( "set_user_rights" );
		} else {
			$mss = wfMsg( "makesysopsubmit" );
		}
		$wgOut->addHTML(
			"<tr>
				<td>&nbsp;</td><td align='left'>
					<input type='submit' name=\"wpMakesysopSubmit\" value=\"{$mss}\" />
				</td></tr></table>
			</form>\n" 
		);

	}

	function doSubmit() {
		global $wgOut, $wgUser, $wgLang;
		global $wgDBname, $wgMemc, $wgLocalDatabases;

		$fname = 'MakesysopForm::doSubmit';
		
		$dbw =& wfGetDB( DB_MASTER );
		$parts = explode( '@', $this->mUser );
		$user_rights = $dbw->tableName( 'user_rights' );
		$usertable   = $dbw->tableName( 'user' );

		if( count( $parts ) == 2 && $wgUser->isDeveloper() && strpos( '.', $user_rights ) === false ){
			$username = $dbw->strencode( $parts[0] );
			if ( array_key_exists( $parts[1], $wgLocalDatabases ) ) {
				$dbName = $wgLocalDatabases[$parts[1]];
				$user_rights = $dbName . '.' . $user_rights;
				$usertable   = $usertable . '.' . $usertable;
			} else {
				$this->showFail();
				return;
			}
		} else {
			$username = wfStrencode( $this->mUser );
			$dbName = $wgDBname;
		}
		if ( $username{0} == "#" ) {
			$id = intval( substr( $username, 1 ) );
			$sql = "SELECT ur_user,ur_rights FROM $user_rights WHERE ur_user=$id FOR UPDATE";
		} else {
			$encName = $dbw->strencode( $username );
			$sql = "SELECT ur_user, ur_rights FROM $usertable LEFT JOIN $user_rights ON user_id=ur_user WHERE user_name = '{$username}' FOR UPDATE";
		}
		
		$prev = $dbw->ignoreErrors( TRUE );
		$res = $dbw->query( $sql );
		$dbw->ignoreErrors( $prev );

		if( $dbw->lastErrno() || ! $username || $dbw->numRows( $res ) == 0 ){
			$this->showFail();
			return;
		}

		$row = $dbw->fetchObject( $res );
		$id = intval( $row->ur_user );
		$rightsNotation = array();

		if ( $wgUser->isDeveloper() ) {
			$newrights = (string)$this->mRights;
			$rightsNotation[] = "=$this->mRights";
		} else {
			if( $row->ur_rights ){
				$rights = explode(",", $row->ur_rights );
				if(! in_array("sysop", $rights ) ){
					$rights[] = "sysop";
					$rightsNotation[] = "+sysop ";
				}
				if ( $this->mBuro && !in_array( "bureaucrat", $rights ) ) {
					$rights[] = "bureaucrat";
					$rightsNotation[] = "+bureaucrat ";
				}
				$newrights = addslashes( implode( ",", $rights ) );
			} else {
				$newrights = "sysop";
				$rightsNotation[] = "+sysop";
				if ( $this->mBuro ) {
					$rightsNotation[] = "+bureaucrat";
					$newrights .= ",bureaucrat";
				}
			}
		}
		
		if ( count( $rightsNotation ) == 0 ) {
			$this->showFail();
		} else {
			#$sql = "UPDATE $user_rights SET user_rights = '{$newrights}' WHERE user_id = $id LIMIT 1";
			#$dbw->query($sql);
			$dbw->replace( $user_rights, array( array( 'ur_user', 'ur_rights' )),
				array( 'ur_user' => $id, 'ur_rights' => $newrights ) , $fname );
			$wgMemc->delete( "$dbName:user:id:$id" );
			
			$log = new LogPage( 'rights' );
			$log->addEntry( 'rights', Title::makeTitle( NS_USER, $this->mUser ),
				implode( " ", $rightsNotation ) );
			
			$this->showSuccess();
		}
	}

	function showSuccess() {
		global $wgOut, $wgUser;

		$wgOut->setPagetitle( wfMsg( "makesysoptitle" ) );

		if ( $wgUser->isDeveloper() ) {
			$text = wfMsg( "user_rights_set", $this->mUser );
		} else {
			$text = wfMsg( "makesysopok", $this->mUser );
		}
		$text .= "\n\n";
		$wgOut->addWikiText( $text );
		$this->showForm();

	}

	function showFail() {
		global $wgOut, $wgUser;

		$wgOut->setPagetitle( wfMsg( "makesysoptitle" ) );
		if ( $wgUser->isDeveloper() ) {
			$this->showForm( wfMsg( "set_rights_fail", $this->mUser ) );
		} else {
			$this->showForm( wfMsg( "makesysopfail", $this->mUser ) );
		}
	}
}
?>
