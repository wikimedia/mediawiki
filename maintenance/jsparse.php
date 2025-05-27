<?php
/**
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
