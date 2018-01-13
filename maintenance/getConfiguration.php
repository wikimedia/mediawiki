<?php
/**
 * Print serialized output of MediaWiki config vars.
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
 * @author Tim Starling
 * @author Antoine Musso <hashar@free.fr>
 */

require_once __DIR__ . '/Maintenance.php';

/**
 * Print serialized output of MediaWiki config vars
 *
 * @ingroup Maintenance
 */
class GetConfiguration extends Maintenance {

	protected $regex = null;

	protected $settings_list = [];

	/**
	 * List of format output internally supported.
	 * Each item MUST be lower case.
	 */
	protected static $outFormats = [
		'json',
		'php',
		'serialize',
		'vardump',
	];

	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Get serialized MediaWiki site configuration' );
		$this->addOption( 'regex', 'regex to filter variables with', false, true );
		$this->addOption( 'iregex', 'same as --regex but case insensitive', false, true );
		$this->addOption( 'settings', 'Space-separated list of wg* variables', false, true );
		$this->addOption( 'format', implode( ', ', self::$outFormats ), false, true );
	}

	protected function validateParamsAndArgs() {
		$error_out = false;

		# Get the format and make sure it is set to a valid default value
		$format = strtolower( $this->getOption( 'format', 'PHP' ) );

		$validFormat = in_array( $format, self::$outFormats );
		if ( !$validFormat ) {
			$this->error( "--format set to an unrecognized format" );
			$error_out = true;
		}

		if ( $this->getOption( 'regex' ) && $this->getOption( 'iregex' ) ) {
			$this->error( "Can only use either --regex or --iregex" );
			$error_out = true;
		}

		parent::validateParamsAndArgs();

		if ( $error_out ) {
			# Force help and quit
			$this->maybeHelp( true );
		}
	}

	/**
	 * finalSetup() since we need MWException
	 */
	public function finalSetup() {
		parent::finalSetup();

		$this->regex = $this->getOption( 'regex' ) ?: $this->getOption( 'iregex' );
		if ( $this->regex ) {
			$this->regex = '/' . $this->regex . '/';
			if ( $this->hasOption( 'iregex' ) ) {
				$this->regex .= 'i'; # case insensitive regex
			}
		}

		if ( $this->hasOption( 'settings' ) ) {
			$this->settings_list = explode( ' ', $this->getOption( 'settings' ) );
			# Values validation
			foreach ( $this->settings_list as $name ) {
				if ( !preg_match( '/^wg[A-Z]/', $name ) ) {
					throw new MWException( "Variable '$name' does start with 'wg'." );
				} elseif ( !array_key_exists( $name, $GLOBALS ) ) {
					throw new MWException( "Variable '$name' is not set." );
				} elseif ( !$this->isAllowedVariable( $GLOBALS[$name] ) ) {
					throw new MWException( "Variable '$name' includes non-array, non-scalar, items." );
				}
			}
		}
	}

	public function execute() {
		// Settings we will display
		$res = [];

		# Sane default: dump any wg / wmg variable
		if ( !$this->regex && !$this->getOption( 'settings' ) ) {
			$this->regex = '/^wm?g/';
		}

		# Filter out globals based on the regex
		if ( $this->regex ) {
			$res = [];
			foreach ( $GLOBALS as $name => $value ) {
				if ( preg_match( $this->regex, $name ) ) {
					$res[$name] = $value;
				}
			}
		}

		# Explicitly dumps a list of provided global names
		if ( $this->settings_list ) {
			foreach ( $this->settings_list as $name ) {
				$res[$name] = $GLOBALS[$name];
			}
		}

		ksort( $res );

		$out = null;
		switch ( strtolower( $this->getOption( 'format' ) ) ) {
			case 'serialize':
			case 'php':
				$out = serialize( $res );
				break;
			case 'vardump':
				$out = $this->formatVarDump( $res );
				break;
			case 'json':
				$out = FormatJson::encode( $res );
				break;
			default:
				throw new MWException( "Invalid serialization format given." );
		}
		if ( !is_string( $out ) ) {
			throw new MWException( "Failed to serialize the requested settings." );
		}

		if ( $out ) {
			$this->output( $out . "\n" );
		}
	}

	protected function formatVarDump( $res ) {
		$ret = '';
		foreach ( $res as $key => $value ) {
			ob_start(); # intercept var_dump() output
			print "\${$key} = ";
			var_dump( $value );
			# grab var_dump() output and discard it from the output buffer
			$ret .= trim( ob_get_clean() ) . ";\n";
		}

		return trim( $ret, "\n" );
	}

	private function isAllowedVariable( $value ) {
		if ( is_array( $value ) ) {
			foreach ( $value as $k => $v ) {
				if ( !$this->isAllowedVariable( $v ) ) {
					return false;
				}
			}

			return true;
		} elseif ( is_scalar( $value ) || $value === null ) {
			return true;
		}

		return false;
	}
}

$maintClass = GetConfiguration::class;
require_once RUN_MAINTENANCE_IF_MAIN;
