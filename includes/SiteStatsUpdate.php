<?php
# $Id$
# See deferred.doc

class SiteStatsUpdate {

	var $mViews, $mEdits, $mGood;

	function SiteStatsUpdate( $views, $edits, $good )
	{
		$this->mViews = $views;
		$this->mEdits = $edits;
		$this->mGood = $good;
	}

	function doUpdate()
	{
		global $wgIsMySQL;
		$a = array();

		if ( $this->mViews < 0 ) { $m = "-1"; }
		else if ( $this->mViews > 0 ) { $m = "+1"; }
		else $m = "";
		array_push( $a, "ss_total_views=(ss_total_views$m)" );

		if ( $this->mEdits < 0 ) { $m = "-1"; }
		else if ( $this->mEdits > 0 ) { $m = "+1"; }
		else $m = "";
		array_push( $a, "ss_total_edits=(ss_total_edits$m)" );

		if ( $this->mGood < 0 ) { $m = "-1"; }
		else if ( $this->mGood > 0 ) { $m = "+1"; }
		else $m = "";
		array_push( $a, "ss_good_articles=(ss_good_articles$m)" );
		$lowpri=$wgIsMySQL?"LOW_PRIORITY":"";
		$sql = "UPDATE $lowpri site_stats SET " . implode ( ",", $a ) .
		  " WHERE ss_row_id=1";
		wfQuery( $sql, DB_WRITE, "SiteStatsUpdate::doUpdate" );
	}
}

?>
