<?php
/**
 * See deferred.doc
 * @version # $Id$
 * @package MediaWiki
 */

/**
 *
 * @version # $Id$ 
 * @package MediaWiki
 */
class ViewCountUpdate {

	var $mPageID;

	/**
	 *
	 */
	function ViewCountUpdate( $pageid ) {
		$this->mPageID = $pageid;
	}

	/**
	 *
	 */
	function doUpdate() {
		global $wgDisableCounters;
		if ( $wgDisableCounters ) { return; }
		$db =& wfGetDB( DB_MASTER );
		$lowpri = $db->lowPriorityOption();
		$sql = "UPDATE $lowpri cur SET cur_counter=(1+cur_counter)," .
		  "cur_timestamp=cur_timestamp WHERE cur_id={$this->mPageID}";
		$res = $db->query( $sql, "ViewCountUpdate::doUpdate" );
	}
}
?>
