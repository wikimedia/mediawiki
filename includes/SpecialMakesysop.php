<?php
include_once( "LinksUpdate.php" );

function wfSpecialMakesysop()
{
	global $wgUser, $wgOut, $action, $target;

	if ( 0 == $wgUser->getID() or $wgUser->isBlocked() ) {
		$wgOut->errorpage( "movenologin", "movenologintext" );
		return;
	}
	if (! $wgUser->isBureaucrat()){
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
		global $wpNewTitle, $wpOldTitle, $wpMovetalk, $target;

		$wgOut->setPagetitle( wfMsg( "makesysoptitle" ) );

		$wgOut->addWikiText( wfMsg( "makesysoptext" ) );

		$action = wfLocalUrlE( $wgLang->specialPage( "Makesysop" ),
		  "action=submit" );

		if ( "" != $err ) {
			$wgOut->setSubtitle( wfMsg( "formerror" ) );
			$wgOut->addHTML( "<p><font color='red' size='+1'>{$err}</font>\n" );
		}
		$namedesc = wfMsg( "makesysopname" );
		$wgOut->addHTML( "<p>
			<form id=\"makesysop\" method=\"post\" action=\"{$action}\">
			<table border=0>
			<tr>
				<td align=right>$namedesc</td>
				<td align=left>
					<input type=text size=40 name=\"wpMakesysopUser\">
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

		$mss = wfMsg( "makesysopsubmit" );
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
		global $wgDBname, $wgMemc;
		/*
		$parts = explode( "@", $wpMakesysopUser );
		if( count( $parts ) == 2){
			$username = addslashes( $parts[0] );
			$usertable = $parts[1] . "wiki.user";
			$dbName = $parts[1] . "wiki";
		} else {*/
		$username = addslashes( $wpMakesysopUser );
			$usertable = "user";
			$dbName = $wgDBname;
		#}
		$prev = wfIgnoreSQLErrors( TRUE );
		$res = wfQuery("SELECT user_id, user_rights FROM user WHERE user_name = '{$username}'", DB_WRITE);
		wfIgnoreSQLErrors( $prev );

		if( wfLastErrno() || ! $username || wfNumRows( $res ) == 0 ){
			$this->showFail();
			return;
		}

		$row = wfFetchObject( $res );
		$id = intval( $row->user_id );
		$rightsNotation = array();

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
		
		if ( count( $rightsNotation ) == 0 ) {
			$this->showFail();
		} else {
			$sql = "UPDATE user SET user_rights = '{$newrights}' WHERE user_id = $id LIMIT 1";
			wfQuery($sql, DB_WRITE);
			$wgMemc->delete( "$wgDBname:user:id:$id" );
			
			$bureaucratLog = wfMsg( "bureaucratlog" );
			$action = wfMsg( "bureaucratlogentry", $wpMakesysopUser, implode( " ", $rightsNotation ) );
			
			$log = new LogPage( $bureaucratLog );
			$log->addEntry( $action, "" );
			
			$this->showSuccess();
		}
	}

	function showSuccess()
	{
		global $wgOut, $wpMakesysopUser;

		$wgOut->setPagetitle( wfMsg( "makesysoptitle" ) );
		$text = wfMsg( "makesysopok", $wpMakesysopUser );
		$wgOut->addWikiText( $text );
		$this->showForm();

	}

	function showFail()
	{
		global $wgOut, $wpMakesysopUser;

		$wgOut->setPagetitle( wfMsg( "makesysoptitle" ) );
		$this->showForm( wfMsg( "makesysopfail", $wpMakesysopUser ) );
	}
}
?>
