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
 * @ingroup Profiler
 * @since 1.25
 */
class ProfilerOutputText extends ProfilerOutput {
	/** @var float Min real time display threshold */
	private $thresholdMs;

	/** @var bool Whether to use visible text or a comment (for HTML responses) */
	private $visible;

	function __construct( Profiler $collector, array $params ) {
		parent::__construct( $collector, $params );
		$this->thresholdMs = $params['thresholdMs'] ?? 1.0;
		$this->visible = $params['visible'] ?? false;
	}

	public function logsToOutput() {
		return true;
	}

	public function log( array $stats ) {
		$out = '';

		// Filter out really tiny entries
		$min = $this->thresholdMs;
		$stats = array_filter( $stats, function ( $a ) use ( $min ) {
			return $a['real'] > $min;
		} );
		// Sort descending by time elapsed
		usort( $stats, function ( $a, $b ) {
			return $b['real'] <=> $a['real'];
		} );

		array_walk( $stats,
			function ( $item ) use ( &$out ) {
				$out .= sprintf( "%6.2f%% %3.3f %6d - %s\n",
					$item['%real'], $item['real'], $item['calls'], $item['name'] );
			}
		);

		$contentType = $this->collector->getContentType();
		if ( wfIsCLI() ) {
			print "<!--\n{$out}\n-->\n";
		} elseif ( $contentType === 'text/html' ) {
			if ( $this->visible ) {
				print "<pre>{$out}</pre>";
			} else {
				print "<!--\n{$out}\n-->\n";
			}
		} elseif ( $contentType === 'text/javascript' || $contentType === 'text/css' ) {
			print "\n/*\n{$out}*/\n";
		}
	}
}
