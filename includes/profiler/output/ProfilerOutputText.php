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
class ProfilerOutputText extends ProfilerOutput {
	protected function logStandardData( array $collated ) {
		$out = '';
		if ( $this->collector->getTemplated() ) {
			$totalReal = isset( $collated['-total'] )
				? $collated['-total']['real']
				: 0; // profiling mismatch error?

			uasort( $collated, function( $a, $b ) {
				// sort descending by time elapsed
				return $a['real'] < $b['real'];
			} );

			array_walk( $collated,
				function( $item, $key ) use ( &$out, $totalReal ) {
					$perc = $totalReal ? $item['real'] / $totalReal * 100 : 0;
					$out .= sprintf( "%6.2f%% %3.6f %6d - %s\n",
						$perc, $item['real'], $item['count'], $key );
				}
			);

			$contentType = $this->collector->getContentType();
			if ( PHP_SAPI === 'cli' ) {
				print "<!--\n{$out}\n-->\n";
			} elseif ( $contentType === 'text/html' ) {
				$visible = isset( $this->params['visible'] ) ?
					$this->params['visible'] : false;
				if ( $visible ) {
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
