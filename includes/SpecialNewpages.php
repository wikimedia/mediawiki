<?php

include_once( "QueryPage.php" );

class NewPagesPage extends QueryPage {

	function getName() {
		return "Newpages";
	}

	function isExpensive() {
		return parent::isExpensive();
	}

	function getSQL( $offset, $limit ) {
		return "SELECT rc_title AS cur_title,rc_user AS cur_user,rc_user_text AS cur_user_text,rc_comment as cur_comment," .
		  "rc_timestamp AS cur_timestamp,length(cur_text) as cur_length FROM recentchanges,cur " .
		  "WHERE rc_cur_id=cur_id AND rc_new=1 AND rc_namespace=0 AND cur_is_redirect=0 " .
		  "ORDER BY rc_timestamp DESC LIMIT {$offset}, {$limit}";
	}

	function formatResult( $skin, $result ) {

		global $wgLang;

		$u = $result->cur_user;
		$ut = $result->cur_user_text;

		$length = wfmsg( "nbytes", $result->cur_length );
		$c = wfEscapeHTML( $result->cur_comment );

		if ( 0 == $u ) { # not by a logged-in user
			$ul = $ut;
		}
		else {
			$ul = $skin->makeLink( $wgLang->getNsText(2) . ":{$ut}", $ut );
		}

		$d = $wgLang->timeanddate( $result->cur_timestamp, true );
		$link = $skin->makeKnownLink( $result->cur_title, "" );
		$s = "{$d} {$link} ({$length}) . . {$ul}";

		if ( "" != $c && "*" != $c ) {
			$s .= " <em>({$c})</em>";
		}

		return $s;
	}
}

function wfSpecialNewpages()
{
    list( $limit, $offset ) = wfCheckLimits();
    
    $npp = new NewPagesPage();
    
    $npp->doQuery( $offset, $limit );
}

?>
