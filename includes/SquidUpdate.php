<?php
# See deferred.doc

class SquidUpdate {
	var $title, $urlArr;
	
	function SquidUpdate( $title, $urlArr = Array() ) {
		$this->title = $title;
		$this->urlArr = $urlArr;
	}


	function doUpdate() {
		if( count( $this->urlArr ) == 0) {
			# newly created Article
			# prepare the list of urls to purge
			$id= $this->title->getArticleID();
			$sql = "SELECT cur_namespace,cur_title FROM links,cur WHERE l_to={$id} AND l_from=cur_id" ;
			$res = wfQuery( $sql, DB_READ );
			while( $row = wfFetchObject ( $res ) ) {
				$t = Title::MakeTitle( $row->cur_namespace, $row->cur_title );
				$this->urlArr[] = $t->getInternalURL();
			}
			wfFreeResult( $res );
		}

		wfPurgeSquidServers( $this->urlArr );
	}
}

?>
