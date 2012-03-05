<?php
/**
 * @file
 * @ingroup Profiler
 */

require_once( dirname( __FILE__ ) . '/ProfilerSimple.php' );

/**
 * The least sophisticated profiler output class possible, view your source! :)
 *
 * Put the following 3 lines in StartProfiler.php:
 *
 * require_once( dirname( __FILE__ ) . '/includes/ProfilerSimpleText.php' );
 * $wgProfiler = new ProfilerSimpleText;
 * $wgProfiler->visible=true;
 *
 * @ingroup Profiler
 */
class ProfilerSimpleText extends ProfilerSimple {
	public $visible=false; /* Show as <PRE> or <!-- ? */
	static private $out;

	function getFunctionReport() {
		if($this->mTemplated) {
			uasort($this->mCollated,array('self','sort'));
			array_walk($this->mCollated,array('self','format'));
			if ($this->visible) {
				print '<pre>'.self::$out.'</pre>';
			} else {
				print "<!--\n".self::$out."\n-->\n";
			}
		}
	}

	/* dense is good */
	static function sort($a,$b) { return $a['real']<$b['real']; /* sort descending by time elapsed */ }
	static function format($item,$key) { self::$out .= sprintf("%3.6f %6d - %s\n",$item['real'],$item['count'], $key); }
}
