<?php
/**
 * Print serialized output of MediaWiki config vars.
 *
 * @license GPL-2.0-or-later
 * @file
 * @ingroup Maintenance
 * @author Tim Starling
 * @author Antoine Musso <hashar@free.fr>
 */

use MediaWiki\Json\FormatJson;
use MediaWiki\Maintenance\Maintenance;

// @codeCoverageIgnoreStart
require_once __DIR__ . '/Maintenance.php';
// @codeCoverageIgnoreEnd

/**
 * Print serialized output of MediaWiki config vars
 *
 * @ingroup Maintenance
 */
class GetConfiguration extends Maintenance {

	/** @var string|null */
	protected $regex = null;

	/** @var string|null */
	protected $format = null;

	/** @var array */
	protected $settings_list = [];

	/**
	 * List of format output internally supported.
	 * Each item MUST be lower case.
	 */
	private const OUT_FORMATS = [
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
		$this->addOption( 'format', implode( ', ', self::OUT_FORMATS ), false, true );
		$this->addOption(
			'json-partial-output-on-error',
			'Use JSON_PARTIAL_OUTPUT_ON_ERROR flag with json_encode(). This allows for partial response to ' .
			'be output in case of an exception while serializing to JSON. If an error occurs, ' .
			'the wgGetConfigurationJsonErrorOccurred field is set in the output.'
		);
	}

	public function validateParamsAndArgs() {
		$error_out = false;

		# Get the format and make sure it is set to a valid default value
		$this->format = strtolower( $this->getOption( 'format', 'PHP' ) );

		$validFormat = in_array( $this->format, self::OUT_FORMATS );
		if ( !$validFormat ) {
			$this->error( "--format set to an unrecognized format" );
			$error_out = true;
		}

		if ( $this->getOption( 'regex' ) && $this->getOption( 'iregex' ) ) {
			$this->error( "Can only use either --regex or --iregex" );
			$error_out = true;
		}
		$this->regex = $this->getOption( 'regex' ) ?: $this->getOption( 'iregex' );
		if ( $this->regex ) {
			$this->regex = '/' . $this->regex . '/';
			if ( $this->hasOption( 'iregex' ) ) {
				# case insensitive regex
				$this->regex .= 'i';
			}
		}

		if ( $this->hasOption( 'settings' ) ) {
			$this->settings_list = explode( ' ', $this->getOption( 'settings' ) );
			# Values validation
			foreach ( $this->settings_list as $name ) {
				if ( !preg_match( '/^wg[A-Z]/', $name ) ) {
					$this->error( "Variable '$name' does start with 'wg'." );
					$error_out = true;
				} elseif ( !array_key_exists( $name, $GLOBALS ) ) {
					$this->error( "Variable '$name' is not set." );
					$error_out = true;
				} elseif ( !$this->isAllowedVariable( $GLOBALS[$name] ) ) {
					$this->error( "Variable '$name' includes non-array, non-scalar, items." );
					$error_out = true;
				}
			}
		}

		parent::validateParamsAndArgs();

		if ( $error_out ) {
			# Force help and quit
			$this->maybeHelp( true );
		}
	}

	public function execute() {
		// Settings we will display
		$res = [];

		# Default: dump any wg / wmg variable
		if ( !$this->regex && !$this->getOption( 'settings' ) ) {
			// Avoid fatal "Exception: Serialization of Closure is not allowed"
			//
			// * Exclude legacy singletons that are not configuration but
			//   non-serializable objects, such as $wgUser.
			// * Exclude config arrays such as wgHooks which may contain closures
			//   via LocalSettings.php.
			$this->regex = '/^wm?g(?!User|Out|Request|Hooks).*$/';
		}

		# Filter out globals based on the regex
		if ( $this->regex ) {
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

		switch ( $this->format ) {
			case 'serialize':
			case 'php':
				$out = serialize( $res );
				break;
			case 'vardump':
				$out = $this->formatVarDump( $res );
				break;
			case 'json':
				$out = FormatJson::encode( $res );
				if ( !$out && $this->getOption( 'json-partial-output-on-error' ) ) {
					$res['wgGetConfigurationJsonErrorOccurred'] = true;
					$out = json_encode( $res, JSON_PARTIAL_OUTPUT_ON_ERROR );
				}
				break;
			default:
				$this->fatalError( "Invalid serialization format given." );
		}
		if ( !is_string( $out ) ) {
			$this->fatalError( "Failed to serialize the requested settings." );
		}

		if ( $out ) {
			$this->output( $out . "\n" );
		}
	}

	protected function formatVarDump( array $res ): string {
		$ret = '';
		foreach ( $res as $key => $value ) {
			# intercept var_dump() output
			ob_start();
			print "\${$key} = ";
			var_dump( $value );
			# grab var_dump() output and discard it from the output buffer
			$ret .= trim( ob_get_clean() ) . ";\n";
		}

		return trim( $ret, "\n" );
	}

	/**
	 * @param mixed $value
	 */
	private function isAllowedVariable( $value ): bool {
		if ( is_array( $value ) ) {
			foreach ( $value as $v ) {
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

// @codeCoverageIgnoreStart
$maintClass = GetConfiguration::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
