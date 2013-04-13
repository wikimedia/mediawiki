<?php
/**
 * Profiler showing output in page source.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
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
			uasort( $this->mCollated, array( 'self', 'sort' ) );
			array_walk( $this->mCollated, array( 'self', 'format' ), $totalReal );
			if ( PHP_SAPI === 'cli' ) {
				print "<!--\n" . self::$out . "\n-->\n";
			} elseif ( $this->getContentType() === 'text/html' ) {
				if ( $this->visible ) {
					print '<pre>' . self::$out . '</pre>';
				} else {
					print "<!--\n" . self::$out . "\n-->\n";
				}
			} elseif ( $this->getContentType() === 'text/javascript' ) {
				print "\n/*\n" . self::$out . "*/\n";
			} elseif ( $this->getContentType() === 'text/css' ) {
				print "\n/*\n" . self::$out . "*/\n";
			}
		}
	}

	static function sort( $a, $b ) {
		return $a['real'] < $b['real']; /* sort descending by time elapsed */
	}

	static function format( $item, $key, $totalReal ) {
		$perc = $totalReal ? $item['real'] / $totalReal * 100 : 0;
		self::$out .= sprintf( "%6.2f%% %3.6f %6d - %s\n",
			$perc, $item['real'], $item['count'], $key );
	}
}
