<?php
include_once( "LinksUpdate.php" );

function wfSpecialMakesysop()
{
	global $wgUser, $wgOut, $action, $target;

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

	$f = new MakesysopForm();

	if ( $_POST['wpMakesysopSubmit'] ) { 
		$f->doSubmit(); 
	} else { 
		$f->showForm( "" ); 
	}
}

class MakesysopForm {	
	function showForm( $err = "")
	{
		global $wgOut, $wgUser, $wgLang;
		global $wpNewTitle, $wpOldTitle, $wpMovetalk, $target, $wpRights, $wpMakesysopUser;

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
			$wgOut->addHTML( "<p><font color='red' size='+1'>{$err}</font>\n" );
		}
		$namedesc = wfMsg( "makesysopname" );
		if ( isset( $wpMakesysopUser ) ) {
			$encUser = htmlspecialchars( $wpMakesysopUser );
		} else {
			$encUser = "";
		}

		$wgOut->addHTML( "<p>
			<form id=\"makesysop\" method=\"post\" action=\"{$action}\">
			<table border=0>
			<tr>
				<td align=right>$namedesc</td>
				<td align=left>
					<input type=text size=40 name=\"wpMakesysopUser\" value=\"$encUser\">
				</td>
			</tr>" 
		);
		/*
		$makeburo = wfMsg( "setbureaucratflag" );
		$wgOut->addHTML(
			"<tr>
				<td>&nbsp;</td><td align=left>
					<input type=checkbox name=\"wpSetBureaucrat\" value=1>$makeburo
				</td>
			</tr>"
		);*/

		if ( $wgUser->isDeveloper() ) {
			$rights = wfMsg( "rights" );
			if ( isset( $wpRights ) ) {
				$encRights = htmlspecialchars( $wpRights );
			} else {
				$encRights = "sysop";
			}

			$wgOut->addHTML( "
				<tr>
					<td align=right>$rights</td>
					<td align=left>
						<input type=text size=40 name=\"wpRights\" value=\"$encRights\">
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
				<td>&nbsp;</td><td align=left>
					<input type=submit name=\"wpMakesysopSubmit\" value=\"{$mss}\">
				</td></tr></table>
			</form>\n" 
		);

	}

	function doSubmit()
	{

		global $wgOut, $wgUser, $wgLang, $wpMakesysopUser, $wpSetBureaucrat;
		global $wgDBname, $wgMemc, $wpRights, $wgLocalDatabases;

		$parts = explode( "@", $wpMakesysopUser );
		if( count( $parts ) == 2 && $wgUser->isDeveloper() ){
			$username = $parts[0];
			if ( array_key_exists( $parts[1], $wgLocalDatabases ) ) {
				$dbName = $wgLocalDatabases[$parts[1]];
				$usertable = $dbName . ".user";
			} else {
				$this->showFail();
				return;
			}
		} else {
			$username = $wpMakesysopUser;
			$usertable = "user";
			$dbName = $wgDBname;
		}
		if ( $username{0} == "#" ) {
			$id = intval( substr( $username, 1 ) );
			$sql = "SELECT user_id,user_rights FROM $usertable WHERE user_id=$id";
		} else {
			$encName = wfStrencode( $username );
			$sql = "SELECT user_id, user_rights FROM $usertable WHERE user_name = '{$encName}'";
		}

		$prev = wfIgnoreSQLErrors( TRUE );
		$res = wfQuery( $sql, DB_WRITE);
		wfIgnoreSQLErrors( $prev );

		global $wgOut, $wgUser, $wgLang, $wpMakesysopUser, $wpSetBureaucrat;
		global $wgDBname, $wgMemc, $wpRights, $wgLocalDatabases;

		$parts = explode( "@", $wpMakesysopUser );
		if( count( $parts ) == 2 && $wgUser->isDeveloper() ){
			$username = wfStrencode( $parts[0] );
			if ( array_key_exists( $parts[1], $wgLocalDatabases ) ) {
				$dbName = $wgLocalDatabases[$parts[1]];
				$usertable = $dbName . ".user";
			} else {
				$this->showFail();
				return;
			}
		} else {
			$username = wfStrencode( $wpMakesysopUser );
			$usertable = "user";
			$dbName = $wgDBname;
		}
		if ( $username{0} == "#" ) {
                        $id = intval( substr( $username, 1 ) );
                        $sql = "SELECT user_id,user_rights FROM $usertable WHERE user_id=$id";
                } else {
                        $encName = wfStrencode( $username );
                        $sql = "SELECT user_id, user_rights FROM $usertable WHERE user_name = '{$username}'";
                }
		
		
		$prev = wfIgnoreSQLErrors( TRUE );
		$res = wfQuery("SELECT user_id, user_rights FROM $usertable WHERE user_name = '{$username}'", DB_WRITE);
		wfIgnoreSQLErrors( $prev );

		if( wfLastErrno() || ! $username || wfNumRows( $res ) == 0 ){
			$this->showFail();
			return;
		}

		$row = wfFetchObject( $res );
		$id = intval( $row->user_id );
		$rightsNotation = array();

		if ( $wgUser->isDeveloper() ) {
			$newrights = (string)$wpRights;
			$rightsNotation[] = "=$wpRights";
		} else {
			if( $row->user_rights ){
				$rights = explode(",", $row->user_rights );
				if(! in_array("sysop", $rights ) ){
					$rights[] = "sysop";
					$rightsNotation[] = "+sysop ";
				}
				if ( $wpSetBureaucrat && !in_array( "bureaucrat", $rights ) ) {
					$rights[] = "bureaucrat";
					$rightsNotation[] = "+bureaucrat ";
				}
				$newrights = addslashes( implode( ",", $rights ) );
			} else {
				$newrights = "sysop";
				$rightsNotation[] = "+sysop";
				if ( $wpSetBureaucrat ) {
					$rightsNotation[] = "+bureaucrat";
					$newrights .= ",bureaucrat";
				}
			}
		}
		
		if ( count( $rightsNotation ) == 0 ) {
			$this->showFail();
		} else {
			$sql = "UPDATE $usertable SET user_rights = '{$newrights}' WHERE user_id = $id LIMIT 1";
			wfQuery($sql, DB_WRITE);
			$wgMemc->delete( "$dbName:user:id:$id" );
			
			$bureaucratLog = wfMsg( "bureaucratlog" );
			$action = wfMsg( "bureaucratlogentry", $wpMakesysopUser, implode( " ", $rightsNotation ) );
			
			$log = new LogPage( $bureaucratLog );
			$log->addEntry( $action, "" );
			
			$this->showSuccess();
		}
	}

	function showSuccess()
	{
		global $wgOut, $wpMakesysopUser, $wgUser;

		$wgOut->setPagetitle( wfMsg( "makesysoptitle" ) );

		if ( $wgUser->isDeveloper() ) {
			$text = wfMsg( "user_rights_set", $wpMakesysopUser );
		} else {
			$text = wfMsg( "makesysopok", $wpMakesysopUser );
		}
		$text .= "\n\n";
		$wgOut->addWikiText( $text );
		$this->showForm();

	}

	function showFail()
	{
		global $wgOut, $wpMakesysopUser, $wgUser;

		$wgOut->setPagetitle( wfMsg( "makesysoptitle" ) );
		if ( $wgUser->isDeveloper() ) {
			$this->showForm( wfMsg( "set_rights_fail", $wpMakesysopUser ) );
		} else {
			$this->showForm( wfMsg( "makesysopfail", $wpMakesysopUser ) );
		}
	}
}
?>
