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
		return "SELECT r.user_rights as type, $userspace as namespace, u.user_name as title, " .
			"u.user_name as value FROM $user u LEFT JOIN $user_rights r ON u.user_id = r.user_id";
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
