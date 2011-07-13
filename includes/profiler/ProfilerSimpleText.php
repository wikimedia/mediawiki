<?php
/**
 * @file
 * @ingroup Profiler
 */

/**
 * The least sophisticated profiler output class possible, view your source! :)
 *
 * Put the following 2 lines in StartProfiler.php:
 *
 * $wgProfiler['class'] = 'ProfilerSimpleText';
 * $wgProfiler['visible'] = true;
 *
 * @ingroup Profiler
 */
class ProfilerSimpleText extends ProfilerSimple {
	public $visible = false; /* Show as <PRE> or <!-- ? */
	static private $out;

	public function __construct( $profileConfig ) {
		if ( isset( $profileConfig['visible'] ) && $profileConfig['visible'] ) {
			$this->visible = true;
		}
		parent::__construct( $profileConfig );
	}

	public function logData() {
		if ( $this->mTemplated ) {
			$this->close();
			$totalReal = isset( $this->mCollated['-total'] )
				? $this->mCollated['-total']['real']
				: 0; // profiling mismatch error?
			uasort( $this->mCollated, array('self','sort') );
			array_walk( $this->mCollated, array('self','format'), $totalReal );
			if ( $this->visible ) {
				print '<pre>'.self::$out.'</pre>';
			} else {
				print "<!--\n".self::$out."\n-->\n";
			}
		}
	}

	/* dense is good */
	static function sort( $a, $b ) {
		return $a['real'] < $b['real']; /* sort descending by time elapsed */
	}

	static function format( $item, $key, $totalReal ) {
		$perc = $totalReal ? $item['real']/$totalReal*100 : 0;
		self::$out .= sprintf( "%6.2f%% %3.6f %6d - %s\n",
			$perc, $item['real'], $item['count'], $key );
	}
}
