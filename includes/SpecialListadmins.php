<?php
/**
 *
 * @package MediaWiki
 * @subpackage SpecialPage
 */

/**
 *
 */
require_once("QueryPage.php");

/**
 * This class is used to get a list of users flagged with "sysop" right.
 *
 * @package MediaWiki
 * @subpackage SpecialPage
 */
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
		return "SELECT ur_rights as type,{$userspace} as namespace,".
		       "user_name as title, user_name as value ".
		       "FROM {$user} ,{$user_rights} WHERE user_id=ur_uid AND ur_rights LIKE '%sysop%'";
	}
}

/**
 * constructor
 */
function wfSpecialListadmins() {
	list( $limit, $offset ) = wfCheckLimits();

	$sla = new ListAdminsPage();

	return $sla->doQuery( $offset, $limit );
}

?>
