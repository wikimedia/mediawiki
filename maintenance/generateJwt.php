<?php
/**
 * Check that database usernames are actually valid.
 *
 * @license GPL-2.0-or-later
 * @file
 * @ingroup Maintenance
 */

use MediaWiki\Maintenance\Maintenance;
use MediaWiki\Utils\MWTimestamp;

// @codeCoverageIgnoreStart
require_once __DIR__ . '/Maintenance.php';
// @codeCoverageIgnoreEnd

/**
 * Maintenance script to generate a JWT token
 *
 *
 * @ingroup Maintenance
 */
class GenerateJwt extends Maintenance {

	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Generate a JWT token from a JSON file or a JSON string' );
		$this->addOption( 'file', 'A filename with claims stored as JSON', false, true );
		$this->addOption( 'json', 'A json string with claims', false, true );
		$this->addOption( 'include-default-claims', 'Inject default claims: iss, iat, jti and sxp', false );
		$this->addOption( 'validate', 'Validate if JWT has all required fields (iss, sub)', false );
		$this->addOption( 'verbose', 'Be verbose and output the claims array', false );
	}

	/**
	 * Retrieve default claims to inject into the JWT token
	 * @return array
	 * @throws \Random\RandomException
	 */
	protected function getDefaultClaims() {
		return [
			'iss' => $this->getServiceContainer()->getUrlUtils()->getCanonicalServer(),
			'iat' => MWTimestamp::time(),
			'jti' => base64_encode( random_bytes( 16 ) ),
			'sxp' => MWTimestamp::time() + 3600,
		];
	}

	/**
	 * Validate if JWT has all required fields
	 * @param array $claims
	 * @return void
	 * @throws \MediaWiki\Maintenance\MaintenanceFatalError
	 */
	private function validateClaims( array $claims ) {
		foreach ( [ 'iss', 'sub' ] as $requiredClaim ) {
			if ( !array_key_exists( $requiredClaim, $claims ) ) {
				$this->fatalError( 'Missing required claim: ' . $requiredClaim );
			}
		}
	}

	/**
	 * Read claims from the input file or JSON string
	 * @return array
	 * @throws \MediaWiki\Maintenance\MaintenanceFatalError
	 */
	private function readClaimsFromInput(): array {
		$file = $this->getOption( 'file' );
		$json = $this->getOption( 'json' );
		if ( $file === null && $json === null ) {
			$this->fatalError( 'Either --file or --json must be specified' );
		}
		if ( $file ) {
			if ( !file_exists( $file ) ) {
				$this->fatalError( 'File does not exist: ' . $file );
			}
			$this->output( 'Reading claims from file: ' . $file . PHP_EOL );
			$content = file_get_contents( $file );
		} else {
			$content = $json;
		}
		if ( strlen( $content ) == 0 ) {
			$this->fatalError( 'Empty content, cannot decode' );
		}
		$claims = json_decode( $content, true );
		$lastError = json_last_error();
		if ( $lastError !== JSON_ERROR_NONE ) {
			$this->fatalError( 'Invalid JSON: ' . json_last_error_msg() );
		}
		if ( !is_array( $claims ) ) {
			$this->fatalError( 'Decoded claims structure is not an array' );
		}
		return $claims;
	}

	public function execute() {
		$jwtCodec = $this->getServiceContainer()->getJwtCodec();
		if ( !$jwtCodec->isEnabled() ) {
			$this->fatalError( 'JWT is not enabled on this wiki. Please setup JwtPublicKey and JwtPrivateKey' );
		}

		$claims = $this->readClaimsFromInput();

		if ( $this->hasOption( 'include-default-claims' ) ) {
			$claims = $this->getDefaultClaims() + $claims;
		}

		if ( $this->getOption( 'verbose' ) ) {
			$this->output( 'Decoded Claims: ' . PHP_EOL );
			$this->output( json_encode( $claims, JSON_PRETTY_PRINT ) . PHP_EOL );
		}

		if ( $this->getOption( 'validate' ) ) {
			$this->validateClaims( $claims );
		}

		$token = $jwtCodec->create( $claims );
		$this->output( $token . PHP_EOL );
	}
}

// @codeCoverageIgnoreStart
$maintClass = GenerateJWT::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
