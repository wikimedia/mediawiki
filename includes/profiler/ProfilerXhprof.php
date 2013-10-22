<?php
/**
 * XHProf enabled profiler.  Does not report through standard mediawiki
 * channels.  By default xhprof profiles are written to
 *   /tmp/{uniqid}.mw.xhprof
 * The xhprof extension needs to be installed via pecl.  This extension
 * includes a profile viewer that expects this location at
 *   /usr/share/php/xhprof_html
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
 * Generates a single profile per execution via the xhprof extension.
 * This is considered a stub by mediawiki and does not provide any
 * data directly back to mediawiki.  View profiles with the viewer
 * provided with xhprof.
 *
 * @ingroup Profiler
 */
class ProfilerXhprof extends Profiler {

	public function __construct() {
		static $enabled = false;
		// We really should only do this once per execution
		if ( !$enabled ) {
			$enabled = true;
			xhprof_enable( XHPROF_FLAGS_CPU );

			register_shutdown_function( function() {
				global $wgProfileXhprofDir;
				$profile = serialize( xhprof_disable() );
				do {
					$uniqid = uniqid();
					$filename = "$wgProfileXhprofDir/$uniqid.mw.xhprof";
				} while ( file_exists( $filename ) );
				file_put_contents( $filename, $profile );
			} );
		}
	}

	public function isStub() {
		return true;
	}
	public function isPersistent() {
		return false;
	}
	public function profileIn( $fn ) {}
	public function profileOut( $fn ) {}
	public function getOutput() {}
	public function close() {}
	public function logData() {}
	public function getCurrentSection() { return ''; }
	public function transactionWritingIn( $server, $db ) {}
	public function transactionWritingOut( $server, $db ) {}
}
