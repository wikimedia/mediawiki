<?php
#
# This class is used to get a list of user. The ones with specials
# rights (sysop, bureaucrat, developer) will have them displayed
# next to their names.

require_once("QueryPage.php");

class ListUsersPage extends QueryPage {

	function getName() {
		return "Listusers";
	}

	function getSQL() {
		$dbr =& wfGetDB( DB_SLAVE );
		$usertable = $dbr->tableName( 'user' );
		$userspace = Namespace::getUser();
		return "SELECT user_rights as type, $userspace as namespace, user_name as title, " .
			"user_name as value FROM $usertable";
	}
	
	function sortDescending() {
		return false;
	}

	function formatResult( $skin, $result ) {
		global $wgLang;
		$name = $skin->makeLink( $wgLang->getNsText($result->namespace) . ':' . $result->title, $result->title );
		if( '' != $result->type ) {
			$name .= ' (' .
			$skin->makeLink( wfMsg( "administrators" ), $result->type) .
			')';
		}
		return $name;
	}
}

function wfSpecialListusers() {
	global $wgUser, $wgOut, $wgLang;

	list( $limit, $offset ) = wfCheckLimits();

	$slu = new ListUsersPage();

	return $slu->doQuery( $offset, $limit );
}

?>
