<?php
/**
 * Update the CREDITS list by merging in the list of git commit authors.
 *
 * The contents of the existing contributors list will be preserved. If a name
 * needs to be removed for some reason that must be done manually before or
 * after running this script.
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
 */

namespace MediaWiki\Maintenance;

use Collator;

// @codeCoverageIgnoreStart
require_once __DIR__ . '/Maintenance.php';
// @codeCoverageIgnoreEnd

/**
 * @ingroup Maintenance
 */
class UpdateCredits extends Maintenance {

	private const CREDITS = MW_INSTALL_PATH . '/CREDITS';
	private const START_CONTRIBUTORS = '<!-- BEGIN CONTRIBUTOR LIST -->';
	private const END_CONTRIBUTORS = '<!-- END CONTRIBUTOR LIST -->';

	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Update the CREDITS list by merging in the list of git commit authors' );
	}

	public function execute() {
		$inHeader = true;
		$inFooter = false;
		$header = [];
		$contributors = [];
		$footer = [];

		if ( !file_exists( self::CREDITS ) ) {
			$this->fatalError( 'No CREDITS file found.' );
		}

		$lines = explode( "\n", file_get_contents( self::CREDITS ) );
		foreach ( $lines as $line ) {
			if ( $inHeader ) {
				$header[] = $line;
				$inHeader = $line !== self::START_CONTRIBUTORS;
			} elseif ( $inFooter ) {
				$footer[] = $line;
			} elseif ( $line === self::END_CONTRIBUTORS ) {
				$inFooter = true;
				$footer[] = $line;
			} else {
				$name = substr( $line, 2 );
				$contributors[$name] = true;
			}
		}
		unset( $lines );

		$lines = explode( "\n", (string)shell_exec( 'git log --format="%aN"' ) );
		foreach ( $lines as $line ) {
			if ( !$line ) {
				continue;
			}
			if ( str_starts_with( $line, '[BOT]' ) ) {
				continue;
			}
			$contributors[$line] = true;
		}

		$contributors = array_keys( $contributors );
		$collator = Collator::create( 'root' );
		$collator->setAttribute( Collator::NUMERIC_COLLATION, Collator::ON );
		$collator->sort( $contributors );
		array_walk( $contributors, static function ( &$v, $k ) {
			$v = "* {$v}";
		} );

		file_put_contents(
			self::CREDITS,
			implode( "\n", array_merge( $header, $contributors, $footer ) )
		);

		$this->output( "Done! CREDITS file updated\n" );
	}
}

// @codeCoverageIgnoreStart
$maintClass = UpdateCredits::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
