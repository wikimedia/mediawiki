<?
# See deferred.doc

class ViewCountUpdate {

	var $mPageID;

	function ViewCountUpdate( $pageid )
	{
		$this->mPageID = $pageid;
	}

	function doUpdate()
	{
		$sql = "UPDATE LOW_PRIORITY cur SET cur_counter=(1+cur_counter)," .
		  "cur_timestamp=cur_timestamp WHERE cur_id={$this->mPageID}";
		$res = wfQuery( $sql, "ViewCountUpdate::doUpdate" );
	}
}

?>
