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
class ProfilerSimpleText extends ProfilerStandard {
	public $visible = false; /* Show as <PRE> or <!-- ? */

	public function __construct( $profileConfig ) {
		if ( isset( $profileConfig['visible'] ) && $profileConfig['visible'] ) {
			$this->visible = true;
		}
		parent::__construct( $profileConfig );
	}

	public function logData() {
		$out = '';
		if ( $this->templated ) {
			$this->close();
			$totalReal = isset( $this->collated['-total'] )
				? $this->collated['-total']['real']
				: 0; // profiling mismatch error?

			uasort( $this->collated, function( $a, $b ) {
				// sort descending by time elapsed
				return $a['real'] < $b['real'];
			} );

			array_walk( $this->collated,
				function( $item, $key ) use ( &$out, $totalReal ) {
					$perc = $totalReal ? $item['real'] / $totalReal * 100 : 0;
					$out .= sprintf( "%6.2f%% %3.6f %6d - %s\n",
						$perc, $item['real'], $item['count'], $key );
				}
			);

			$contentType = $this->getContentType();
			if ( PHP_SAPI === 'cli' ) {
				print "<!--\n{$out}\n-->\n";
			} elseif ( $contentType === 'text/html' ) {
				if ( $this->visible ) {
					print "<pre>{$out}</pre>";
				} else {
					print "<!--\n{$out}\n-->\n";
				}
			} elseif ( $contentType === 'text/javascript' ) {
				print "\n/*\n${$out}*/\n";
			} elseif ( $contentType === 'text/css' ) {
				print "\n/*\n{$out}*/\n";
			}
		}
	}
}
