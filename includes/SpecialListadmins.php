<?php
#
# This class is used to get a list of users flagged with "sysop"
# right.

require_once("QueryPage.php");

class ListAdminsPage extends PageQueryPage {

	function getName() {
		return 'Listadmins';
	}

	function sortDescending() {
		return false;
	}

	function getSQL() {
		global $wgIsPg;
		$usertable = $wgIsPg?'"user"':'user';
		$userspace = Namespace::getUser();
		return 'SELECT user_rights as type,'.$userspace.' as namespace,'.
		       'user_name as title, user_name as value '.
		       "FROM $usertable ".
			   'WHERE user_rights LIKE "%sysop%"';
	}
}

function wfSpecialListadmins() {
	list( $limit, $offset ) = wfCheckLimits();

	$sla = new ListAdminsPage();

	return $sla->doQuery( $offset, $limit );
}

?>
