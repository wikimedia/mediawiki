<?php

require_once( "QueryPage.php" );

class NewPagesPage extends QueryPage {

	function getName() {
		return "Newpages";
	}
	
	function isExpensive() {
		# Indexed on RC, and will *not* work with querycache yet.
		return false;
		#return parent::isExpensive();
	}

	function getSQL() {
		return
			"SELECT 'Newpages' as type,
			        rc_namespace AS namespace,
			        rc_title AS title,
			        rc_cur_id AS value,
			        
			        rc_user AS user,
			        rc_user_text AS user_text,
			        rc_comment as comment,
			        rc_timestamp AS timestamp,
			        length(cur_text) as length,
			        cur_text as text
			FROM recentchanges,cur
			WHERE rc_cur_id=cur_id AND rc_new=1
			  AND rc_namespace=0 AND cur_is_redirect=0";
	}

	function formatResult( $skin, $result ) {
		global $wgLang;
		$u = $result->user;
		$ut = $result->user_text;

		$length = wfMsg( "nbytes", $wgLang->formatNum( $result->length ) );
		$c = $skin->formatComment($result->comment );

		if ( 0 == $u ) { # not by a logged-in user
			$ul = $ut;
		}
		else {
			$ul = $skin->makeLink( $wgLang->getNsText(NS_USER) . ":{$ut}", $ut );
		}

		$d = $wgLang->timeanddate( $result->timestamp, true );
		$link = $skin->makeKnownLink( $result->title, "" );
		$s = "{$d} {$link} ({$length}) . . {$ul}";

		if ( "" != $c && "*" != $c ) {
			$s .= " <em>({$c})</em>";
		}

		return $s;
	}
}

function wfSpecialNewpages()
{
	global $wgRequest;
    list( $limit, $offset ) = wfCheckLimits();
    
    $npp = new NewPagesPage();

    if( !$npp->doFeed( $wgRequest->getVal( 'feed' ) ) ) {
	    $npp->doQuery( $offset, $limit );
	}
}

?>
