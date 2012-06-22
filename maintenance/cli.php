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
 * @file
 * @ingroup Maintenance
 */

require_once( dirname( __FILE__ ) . '/Maintenance.php' );

/**
$optionsWithArgs = array( 'd' );
require_once( dirname( __FILE__ ) . "/commandLine.inc" );

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
	if ( $d > 2 ) {
		$wgDebugFunctionEntry = true;
	}
}
**/

class MWEval extends Maintenance {

	public function execute() {
		global $wgDBname;
		$prompt = "$wgDBname> ";

		$useReadline = function_exists( 'readline_add_history' )
					&& Maintenance::posix_isatty( 0 /*STDIN*/ );

		if ( $useReadline ) {
			$historyFile = isset( $_ENV['HOME'] ) ?
				"{$_ENV['HOME']}/.mweval_history" : "$IP/maintenance/.mweval_history";
			readline_read_history( $historyFile );
			readline_completion_function( array( $this, 'completion' ) );
		}

		while ( ( $line = Maintenance::readconsole( $prompt ) ) !== false ) {
			if( $line != '' ) {
			if ( $useReadline ) {
					readline_add_history( $line );
					readline_write_history( $historyFile );
			}
			$this->evaluate( $line );
			}
		}
		print "\n";
	}

	/** Main entry point. Evaluate the user input */
	public function evaluate( $line ) {
		if( !$this->doInternalCommand( $line ) ) {
			$this->doPHPEvaluationOf( $line );
		}
	}

	private static function doPHPEvaluationOf( $line ) {
		$val = eval( $line . ";" );
		if ( wfIsHipHop() || is_null( $val ) ) {
			echo "\n";
		} elseif ( is_string( $val ) || is_numeric( $val ) ) {
			echo "$val\n";
		} else {
			var_dump( $val );
		}
	}

	private function doInternalCommand( $line ) {
		$words = explode( ' ', $line );
		$args = array();

		while( $words ) {
			$candidate_method = array( $this, "_" . join( '_', $words ) );
			if( is_callable( $candidate_method ) ) {
				call_user_func( $candidate_method, $args );
				return true;
			}
			array_unshift( $args, array_pop( $words ) );
		}

		// no internal command found
		$this->error("'$line' did not match an internal command\n" );
		return false;
	}

	/**
	 * Get calling method internal documentation block
	 */
	function getDoc( ) {
		$caller = $this->getLastButTwoCaller();
		print $caller->getDocComment( );
	}

	/**
	 * Completion handler for readline
	 */
	function completion( $partial ) {
		# Disabled, partial is the last word, not the whole line :-/
		//return array( 'foo' , 'bar', $partial );
	}

	/**
	 * Called in a function to find out which method called it
	 */
	function getLastButTwoCaller() {
		$stack = debug_backtrace();
		$caller_object   = $stack[2]['object'];
		$caller_function = $stack[2]['function'];
		return new ReflectionMethod( $caller_object, $caller_function );
	}

	# Commands from commandline, prefixed with an underscore

	/**
	 * Exit the cli
	 */
	public function _exit( $args ) {
		print "Au revoir!\n";
		exit();
	}

	/** the help command!! */
	public function _help( $args ) {
		$this->getDoc( );
	}
	public function _show( $args ) {
		print "Called '".__METHOD__."'\n";
		var_dump( $args );
	}

	public function _show_database( $args ) {
		print "Called '".__METHOD__."'\n";
		var_dump( $args );
	}
	public function _show_database_timestamp( $arg ) {
		if( empty( $arg ) ) {
			$arg[] = 0;
		}
		if( !is_numeric( $arg[0] ) || count( $arg ) > 2 ) {
			print "USAGE> show database timestamp [unixTS]\n";
			return false;
		}
		print wfGetDB( DB_SLAVE )->timestamp( $arg[0] ) . "\n";
	}

	public function _show_database_master() {
		self::showDatabase( wfGetDB( DB_MASTER ) );
	}
	public function _show_database_slave() {
		self::showDatabase( wfGetDB( DB_SLAVE ) );
	}
	private static function showDatabase( DatabaseBase $db ) {
		print <<<EOT
Type: {$db->getType()}
WikiID: {$db->getWikiID()}
DBName: {$db->getDBname()}
Server: {$db->getServer()}
ServerInfo: {$db->getServerInfo()}

EOT;
	}

	public function _show_global( $args ) {
		$globals = array_keys( $GLOBALS );

		// TODO: normalize user input to strip leading $

		$invalids = array_diff( $args, $globals );
		if( $invalids ) {
			$this->error( "Global(s) not found: $" . join( ', $', $invalids ) );
			$this->error( "Aborting..." );
			return false;
		}

		$valids   = array_intersect( $globals, $args );
		foreach( $valids as $valid ) {
			print "\${$valid}:\n";
			var_dump( $GLOBALS[$valid] );
		}
	}

	/**
	 * Dump a sorted list of globals variables
	 */
	public function _show_globals( $args ) {
		# alias 'show globals <args>'
		if( $args ) {
			return $this->_show_global( $args );
		}

		$globals = array_keys( $GLOBALS );
		sort( $globals );
		foreach( $globals as $global ) {
			print "$global\n";
		}
	}

	public function _show_title( $args ) {
		$t = null;
		switch( count( $args ) ) {
			case 1:
				$t = Title::newFromText( $args[0] );
				if( !$t ) {
					print "VER> No title named '{$args[0]}' trying by id..\n";
					$t = Title::newFromId( $args[0] );
				}
				array_shift( $args );
			break;
			default:
				if( $args[0] == 'text' ) {
					$t = Title::newFromText( $args[1] );
				} elseif( $args[0] == 'id' ) {
					$t = Title::newFromId( $args[1] );
				}
				array_shift( $args );
				array_shift( $args );
		}
		if( !$t instanceof Title ) {
			$this->error( "ERR> no such Title.\n" );
			return;
		}

		# FIXME, stuff below should be factored out
		if( !$args ) {
			print $t->getPrefixedDBKey() . "\n";
		} else {
			$accessor = $args[0];
			if( !is_callable( array( $t, $accessor ) ) ) {
				$accessor = "get".ucfirst( $args[0] );
			}
			if( !is_callable( array( $t, $accessor ) ) ) {
				$accessor = "is".ucfirst( $args[0] );
			}

			if( is_callable( array( $t, $accessor ) ) ) {
				$result = call_user_func( array( $t, $accessor ) );
				var_dump( $result );
			} else {
				print "no callable '$accessor'.\n";
			}
		}
	}

	public function _show_namespace( $args ) {
		if( count( $args ) == 0 ) {
			$args = array( 'all' );
		} elseif( count( $args ) > 1 ) {
			print "USAGE> show namespace [all|NS number]\n";
			return false;
		}

		if( $args[0] === 'all' ) {
			$ns = MWNamespace::getCanonicalNamespaces();
		} else {
			$cn = MWNamespace::getCanonicalName($args[0]);
			if( $cn === false ) {
				$this->error( "ERR> no such namespace\n" );
				return false;
			}
			$ns = array( $args[0] => $cn );
		}

		foreach( $ns as $num => $name ) {
			printf( " %3s - %s\n", $num, $name );
		}
	}
}

$maintClass = 'MWEval';
require_once( RUN_MAINTENANCE_IF_MAIN );
