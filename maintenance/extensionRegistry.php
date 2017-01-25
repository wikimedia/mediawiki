<?php
/**
 * Interact with the ExtensionRegistry.
 *
 * Copyright © 2017 Antoine "hashar" Musso <hashar@free.fr>
 * Copyright © 2017 Wikimedia Foundation Inc.
 * https://www.mediawiki.org/
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

require_once __DIR__ . '/Maintenance.php';

/**
 * Maintenance script to interact with the ExtensionRegistry.
 *
 * @ingroup Maintenance
 * @since 1.28
 */
class ExtensionRegistryCLI extends Maintenance {

	/**
	 * Contains everything that has been loaded.
	 *
	 * Hash of name of the thing => credits information
	 *
	 * Only initialized on execution of the script.
	 * @var array
	 */
	protected $registered = null;

	protected static $outFormats = [
		'human',
		'json',
		'php',
	];
	protected static $validCommands = [
		'dump',
	];

	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Interact with MediaWiki ExtensionRegistry' );
		$this->addArg( 'command', "One of:\n" . self::commandsHelp(),  true );
		$this->addOption( 'format', join( ', ', self::$outFormats ), false, true );
	}

	public function getFormat() {
		return strtolower( $this->getOption( 'format', 'human' ) );
	}

	protected function validateParamsAndArgs() {
		$error = false;

		if ( ! in_array( $this->getFormat(), self::$outFormats ) ) {
			$this->error( 'Invalid value for --format' );
			$error = true;
		}

		if ( ! $this->validateCommand( $this->getArg( 0 ) ) ) {
			$this->error( 'Invalid command' );
			$error = true;
		}

		parent::validateParamsAndArgs();
		if ( $error ) {
			$this->maybeHelp( true );
		}
	}

	protected function validateCommand( $command ) {
		$command = strtolower( $command );
		return in_array( $command, self::$validCommands );
	}

	protected static function commandsHelp() {
		return '* ' . join( "\n* ", self::$validCommands ) .  "\n";
	}

	public function execute() {
		$this->registry = ExtensionRegistry::getInstance()->getAllThings();
		$command = strtolower( $this->getArg( 0 ) );
		call_user_func( [ $this, 'do' . ucfirst( $command ) ] );
	}

	function doDump() {
		$this->output(
			$this->format(
				$this->registry,
				$this->getFormat()
			)
		);
	}

	function format( $array, $format ) {
		switch ( $format ) {
			case 'human':
				return FormatJson::encode( $array, true, FormatJson::ALL_OK );
				break;
			case 'json':
				return FormatJson::encode( $array );
				break;
			case 'php':
				return var_export( $array, true );
			default:
				throw new MWException(
					"Dump given an invalid format: $format" );

		}
	}

}

$maintClass = 'ExtensionRegistryCLI';
require_once RUN_MAINTENANCE_IF_MAIN;

