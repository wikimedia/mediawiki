<?php
/**
 * PHP lacks an interactive mode, but this can be very helpful when debugging.
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
 * @ingroup Maintenance
 */

require_once( "Maintenance.php" );

class EvalPrompt extends Maintenance {

	public function __construct() {
		parent::__construct();
		$this->mDescription = "This script lets a command-line user start up the wiki engine and then poke\n" .
								"about by issuing PHP commands directly.";
		$this->addOption( 'd', "Enable MediaWiki debug output", false, true );
	}
	
	public function execute() {
		global $wgUseNormalUser, $wgDebugFunctionEntry, $wgDebugLogFile;
		$wgUseNormalUser = (bool)getenv('MW_WIKIUSER');
		if ( $this->hasOption('d') ) {
			$d = $this->getOption('d');
			if ( $d > 0 ) {
				$wgDebugLogFile = '/dev/stdout';
			}
			if ( $d > 1 ) {
				$lb = wfGetLB();
				foreach ( $lb->mServers as $i => $server ) {
					$lb->mServers[$i]['flags'] |= DBO_DEBUG;
				}
			}
			if ( $d > 2 ) {
				$wgDebugFunctionEntry = true;
			}
		}
	
		if ( function_exists( 'readline_add_history' ) 
			&& function_exists( 'posix_isatty' ) && posix_isatty( 0 /*STDIN*/ ) ) 
		{
			$useReadline = true;
		} else {
			$useReadline = false;
		}
	
		if ( $useReadline ) {
			$historyFile = "{$_ENV['HOME']}/.mweval_history";
			readline_read_history( $historyFile );
		}
	
		while ( ( $line = readconsole( '> ' ) ) !== false ) {
			if ( $useReadline ) {
				readline_add_history( $line );
				readline_write_history( $historyFile );
			}
			$val = eval( $line . ";" );
			if( is_null( $val ) ) {
				echo "\n";
			} elseif( is_string( $val ) || is_numeric( $val ) ) {
				echo "$val\n";
			} else {
				var_dump( $val );
			}
		}
		print "\n";
	}
}

$maintClass = "EvalPrompt";
require_once( DO_MAINTENANCE );
