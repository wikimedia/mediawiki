<?php
/* The least sophisticated profiler output class possible, view your source! :)

	Put it to StartProfiler.php like this:

	require_once( dirname(__FILE__).'/includes/ProfilerSimpleText.php' );
	$wgProfiler = new ProfilerSimpleText;
	$wgProfiler->visible=true;

*/
require_once(dirname(__FILE__).'/ProfilerSimple.php');
class ProfilerSimpleText extends ProfilerSimple {
	public $visible=false; /* Show as <PRE> or <!-- ? */
	function getFunctionReport() {
		if ($this->visible) print "<pre>";
			else print "<!--\n";
		/* Sort destroys keys, we have to tag objects with their names before sorting or outputing */
		array_walk($this->mCollated,array('self','tag'));
		usort($this->mCollated,array('self','sort'));
		array_walk($this->mCollated,array('self','format'));
		if ($this->visible) print "</pre>\n";
			else print "-->\n";
	}
	/* dense is good */
	function sort($a,$b) { return $a['real']<$b['real']; /* sort descending by time elapsed */ }
	function tag(&$item,$key) { $item['function']=$key; }
	function format($item,$key) { printf("%3.6f %6d - %s\n",$item['real'],$item['count'], $item['function']); }	
}
?>
