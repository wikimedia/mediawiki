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
		$dbr =& wfGetDB( DB_SLAVE );
		$user = $dbr->tableName( 'user' );
		$user_rights = $dbr->tableName( 'user_rights' );
		$userspace = Namespace::getUser();
		return "SELECT r.user_rights as type,{$userspace} as namespace,".
		       "u.user_name as title, u.user_name as value ".
		       "FROM {$user} u,{$user_rights} r WHERE r.user_id=u.user_id AND r.user_rights LIKE \"%sysop%\"";
	}
}

function wfSpecialListadmins() {
	list( $limit, $offset ) = wfCheckLimits();

	$sla = new ListAdminsPage();

	return $sla->doQuery( $offset, $limit );
}

?>
