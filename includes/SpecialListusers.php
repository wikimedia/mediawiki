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
 * This class is used to get a list of user. The ones with specials
 * rights (sysop, bureaucrat, developer) will have them displayed
 * next to their names.
 *
 * @package MediaWiki
 * @subpackage SpecialPage
 */
class ListUsersPage extends QueryPage {

	function getName() {
		return "Listusers";
	}

	function getSQL() {
		$dbr =& wfGetDB( DB_SLAVE );
		$user = $dbr->tableName( 'user' );
		$user_rights = $dbr->tableName( 'user_rights' );
		$userspace = Namespace::getUser();
		return "SELECT ur_rights as type, $userspace as namespace, user_name as title, " .
			"user_name as value FROM $user LEFT JOIN $user_rights ON user_id = ur_uid";
	}
	
	function sortDescending() {
		return false;
	}

	function formatResult( $skin, $result ) {
		global $wgContLang;
		$name = $skin->makeLink( $wgContLang->getNsText($result->namespace) . ':' . $result->title, $result->title );
		if( '' != $result->type ) {
			$name .= ' (' .
			$skin->makeLink( wfMsgForContent( "administrators" ), $result->type) .
			')';
		}
		return $name;
	}
}

/**
 * constructor
 */
function wfSpecialListusers() {
	global $wgUser, $wgOut, $wgLang;

	list( $limit, $offset ) = wfCheckLimits();

	$slu = new ListUsersPage();

	return $slu->doQuery( $offset, $limit );
}

?>
