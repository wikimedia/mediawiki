<?php
/**
 * @license GPL-2.0-or-later
 * @file
 * @ingroup Maintenance
 */

use MediaWiki\Maintenance\Maintenance;

// @codeCoverageIgnoreStart
require_once __DIR__ . '/Maintenance.php';
// @codeCoverageIgnoreEnd

/**
 * Ad-hoc run ResourceLoader validation for user-supplied JavaScript.
 *
 * Matches the behaviour of ResourceLoader\Module::validateScriptFile, currently
 * powered by the the Peast library.
 *
 * @ingroup Maintenance
 */
class JSParseHelper extends Maintenance {
	/** @var int */
	public $errs = 0;

	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Validate syntax of JavaScript files' );
		$this->addArg( 'file(s)', 'JavaScript files or "-" to read stdin', true, true );
	}

	public function execute() {
		$files = $this->getArgs();

		foreach ( $files as $filename ) {
			$js = $filename === '-'
				? stream_get_contents( STDIN )
				// phpcs:ignore Generic.PHP.NoSilencedErrors
				: @file_get_contents( $filename );
			if ( $js === false ) {
				$this->output( "$filename ERROR: could not read file\n" );
				$this->errs++;
				continue;
			}

			try {
				Peast\Peast::ES2017( $js )->parse();
			} catch ( Exception $e ) {
				$this->errs++;
				$this->output( "$filename ERROR: " . get_class( $e ) . ": " . $e->getMessage() . "\n" );
				continue;
			}

			$this->output( "$filename OK\n" );
		}

		if ( $this->errs > 0 ) {
			$this->fatalError( 'Failed.' );
		}
	}
}

// @codeCoverageIgnoreStart
$maintClass = JSParseHelper::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
