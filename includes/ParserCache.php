<?php

class ParserCache
{
	function get( &$article, &$user ){
		$hash = $user->getPageRenderingHash();
		$pageid = intval( $id );
		$res = wfQuery("SELECT pc_data FROM parsercache WHERE pc_pageid = {$pageid} ".
			" AND pc_prefhash = '{$hash}' AND pc_expire > NOW()", DB_WRITE);
		$row = wfFetchObject ( $res );
		if( $row ){
			$retVal = unserialize( gzuncompress($row->pc_data) );
			wfProfileOut( $fname );
		} else {
			$retVal = false;
		}
		return $retVal;
	}

	function save( $parserOutput, &$article, &$user ){
		$hash = $user->getPageRenderingHash();
		$pageid = intval( $article->getID() );
		$title = wfStrencode( $article->mTitle->getPrefixedDBKey() );
		$ser = addslashes( gzcompress( serialize( $parserOutput ) ) );
		if( $parserOutput->containsOldMagic() ){
			$expire = "1 HOUR";
		} else {
			$expire = "7 DAY";
		}

		wfQuery("REPLACE INTO parsercache (pc_prefhash,pc_pageid,pc_title,pc_data, pc_expire) ".
			"VALUES('{$hash}', {$pageid}, '{$title}', '{$ser}', ".
				"DATE_ADD(NOW(), INTERVAL {$expire}))", DB_WRITE);

		if( rand() % 50 == 0 ){ // more efficient to just do it sometimes
			$this->purgeParserCache();
		}
	}
	
	function purge(){
		wfQuery("DELETE FROM parsercache WHERE pc_expire < NOW() LIMIT 250", DB_WRITE);
	}

	function clearLinksTo( $pid ){
		$pid = intval( $pid );
		wfQuery("DELETE parsercache FROM parsercache,links ".
			"WHERE pc_title=links.l_from AND l_to={$pid}", DB_WRITE);
		wfQuery("DELETE FROM parsercache WHERE pc_pageid='{$pid}'", DB_WRITE);
	}

	# $title is a prefixed db title, for example like Title->getPrefixedDBkey() returns.
	function clearBrokenLinksTo( $title ){
		$title = wfStrencode( $title );
		wfQuery("DELETE parsercache FROM parsercache,brokenlinks ".
			"WHERE pc_pageid=bl_from AND bl_to='{$title}'", DB_WRITE);
	}

	# $pid is a page id
	function clearPage( $pid, $namespace ){
		$pid = intval( $pid );
		if( $namespace == NS_MEDIAWIKI ){
			$this->clearLinksTo( $pid );
		} else {
			wfQuery("DELETE FROM parsercache WHERE pc_pageid='{$pid}'", DB_WRITE);
		}
	}
}


?>
