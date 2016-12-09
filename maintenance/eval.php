<?php
/**
 * This script lets a command-line user start up the wiki engine and then poke
 * about by issuing PHP commands directly.
 *
 * Unlike eg Python, you need to use a 'return' statement explicitly for the
 * interactive shell to print out the value of the expression. Multiple lines
 * are evaluated separately, so blocks need to be input without a line break.
 * Fatal errors such as use of undeclared functions can kill the shell.
 *
 * To get decent line editing behavior, you should compile PHP with support
 * for GNU readline (pass --with-readline to configure).
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
 * @ingroup Maintenance
 */

$optionsWithArgs = [ 'd' ];

require_once __DIR__ . "/commandLine.inc";

if ( isset( $options['d'] ) ) {
	$d = $options['d'];
	if ( $d > 0 ) {
		$wgDebugLogFile = '/dev/stdout';
	}
	if ( $d > 1 ) {
		$lb = wfGetLB();
		$serverCount = $lb->getServerCount();
		for ( $i = 0; $i < $serverCount; $i++ ) {
			$server = $lb->getServerInfo( $i );
			$server['flags'] |= DBO_DEBUG;
			$lb->setServerInfo( $i, $server );
		}
	}
}

$__useReadline = function_exists( 'readline_add_history' )
	&& Maintenance::posix_isatty( 0 /*STDIN*/ );

if ( $__useReadline ) {
	$__historyFile = isset( $_ENV['HOME'] ) ?
		"{$_ENV['HOME']}/.mweval_history" : "$IP/maintenance/.mweval_history";
	readline_read_history( $__historyFile );
}

$__e = null; // PHP exception
while ( ( $__line = Maintenance::readconsole() ) !== false ) {
	if ( $__e && !preg_match( '/^(exit|die);?$/', $__line ) ) {
		// Internal state may be corrupted or fatals may occur later due
		// to some object not being set. Don't drop out of eval in case
		// lines were being pasted in (which would then get dumped to the shell).
		// Instead, just absorb the remaning commands. Let "exit" through per DWIM.
		echo "Exception was thrown before; please restart eval.php\n";
		continue;
	}
	if ( $__useReadline ) {
		readline_add_history( $__line );
		readline_write_history( $__historyFile );
	}
	try {
		$__val = eval( $__line . ";" );
	} catch ( Exception $__e ) {
		echo "Caught exception " . get_class( $__e ) .
			": {$__e->getMessage()}\n" . $__e->getTraceAsString() . "\n";
		continue;
	}
	if ( wfIsHHVM() || is_null( $__val ) ) {
		echo "\n";
	} elseif ( is_string( $__val ) || is_numeric( $__val ) ) {
		echo "$__val\n";
	} else {
		var_dump( $__val );
	}
}

print "\n";
