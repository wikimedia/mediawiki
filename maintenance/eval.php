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

use MediaWiki\HookContainer\HookRunner;
use MediaWiki\Logger\ConsoleSpi;
use MediaWiki\Logger\LoggerFactory;
use MediaWiki\MediaWikiServices;

require_once __DIR__ . '/Maintenance.php';

/**
 * Maintenance script providing an interactive console for evaluating php commands
 * in the context of an initialized MediaWiki instance.
 *
 * @ingroup Maintenance
 */
// phpcs:disable MediaWiki.Files.ClassMatchesFilename.NotMatch
class MWEval extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->addDescription(
			'Maintenance script providing an interactive console for evaluating php' .
			'commands in the context of an initialized MediaWiki instance.'
		);

		$this->addOption(
			'd',
			'Enable (some) debug output',
			false,
			true
		);

		$this->addOption(
			'ignore-errors',
			'Ignore (some) errors'
		);
	}

	public function canExecuteWithoutLocalSettings(): bool {
		return true;
	}

	public function execute() {
		if ( $this->hasOption( 'd' ) ) {
			$d = $this->getOption( 'd' );
			if ( $d > 0 ) {
				LoggerFactory::registerProvider( new ConsoleSpi );
				// Some services hold Logger instances in object properties
				MediaWikiServices::resetGlobalInstance();
			}
			if ( $d > 1 ) {
				wfGetDB( DB_PRIMARY )->setFlag( DBO_DEBUG );
				wfGetDB( DB_REPLICA )->setFlag( DBO_DEBUG );
			}
		}

		// pull all globals into local scope
		foreach ( $GLOBALS as $name => $unused ) {
			// phpcs:disable MediaWiki.NamingConventions.ValidGlobalName.allowedPrefix
			// phpcs:disable MediaWiki.VariableAnalysis.UnusedGlobalVariables.UnusedGlobal$name
			global $$name;
		}

		$__ignoreErrors = $this->hasOption( 'ignore-errors' );

		$__useReadline = function_exists( 'readline_add_history' )
			&& Maintenance::posix_isatty( 0 /*STDIN*/ );

		if ( $__useReadline ) {
			$__historyFile = isset( $_ENV['HOME'] ) ?
				"{$_ENV['HOME']}/.mweval_history" : ( MW_INSTALL_PATH . "/maintenance/.mweval_history" );
			readline_read_history( $__historyFile );
		} else {
			$__historyFile = null;
		}

		( new HookRunner( $this->getServiceContainer()->getHookContainer() ) )->onMaintenanceShellStart();

		$__e = null; // PHP exception
		while ( ( $__line = Maintenance::readconsole() ) !== false ) {
			if ( !$__ignoreErrors && $__e && !preg_match( '/^(exit|die);?$/', $__line ) ) {
				// Internal state may be corrupted or fatals may occur later due
				// to some object not being set. Don't drop out of eval in case
				// lines were being pasted in (which would then get dumped to the shell).
				// Instead, just absorb the remaining commands. Let "exit" through per DWIM.
				echo "Exception was thrown before; please restart eval.php\n";
				continue;
			}
			if ( $__useReadline ) {
				readline_add_history( $__line );
				// @phan-suppress-next-line PhanTypeMismatchArgumentNullableInternal
				readline_write_history( $__historyFile );
			}
			try {
				// @phan-suppress-next-next-line SecurityCheck-RCE
				// phpcs:ignore MediaWiki.Usage.ForbiddenFunctions.eval
				$__val = eval( $__line . ";" );
			} catch ( Exception $__e ) {
				fwrite( STDERR, "Caught exception " . get_class( $__e ) .
					": {$__e->getMessage()}\n" . $__e->getTraceAsString() . "\n" );
				continue;
			} catch ( Throwable $__e ) {
				if ( $__ignoreErrors ) {
					fwrite( STDERR, "Caught " . get_class( $__e ) .
						": {$__e->getMessage()}\n" . $__e->getTraceAsString() . "\n" );
					continue;
				} else {
					throw $__e;
				}
			}
			if ( $__val === null ) {
				echo "\n";
			} elseif ( is_string( $__val ) || is_numeric( $__val ) ) {
				echo "$__val\n";
			} else {
				var_dump( $__val );
			}
		}

		echo "\n";
	}
}

$maintClass = MWEval::class;
require_once RUN_MAINTENANCE_IF_MAIN;
