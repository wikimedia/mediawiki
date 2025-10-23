<?php
/**
 * Maintenance script to read a JWT token and output claims
 *
 * @license GPL-2.0-or-later
 * @file
 * @ingroup Maintenance
 */

use Lcobucci\JWT\Encoding\JoseEncoder;
use Lcobucci\JWT\Token\Parser;
use Lcobucci\JWT\Token\Plain;
use MediaWiki\Maintenance\Maintenance;

// @codeCoverageIgnoreStart
require_once __DIR__ . '/Maintenance.php';
// @codeCoverageIgnoreEnd

/**
 * Maintenance script to read a JWT token and output claims
 *
 *
 * @ingroup Maintenance
 */
class ExtractClaimsFromJwt extends Maintenance {

	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Read the JWT token and output the claims' );
		$this->addArg( 'jwt', 'A JWT Token string', false );
		$this->addOption( 'validate', 'Validate if JWT has all required fields (iss, sub)' );
	}

	/**
	 * Read claims from the input file or JSON string
	 * @return string
	 * @throws \MediaWiki\Maintenance\MaintenanceFatalError
	 */
	private function readJWTFromInput(): string {
		$content = $this->getArg( 'jwt' );
		if ( $content === null ) {
			$content = $this->getStdin( Maintenance::STDIN_ALL );
		}
		if ( $content === '' || $content === false ) {
			$this->fatalError( 'Empty JWT token. Pass it as an argument or as stdin' );
		}
		return $content;
	}

	public function execute() {
		$jwtCodec = $this->getServiceContainer()->getJwtCodec();
		if ( !$jwtCodec->isEnabled() ) {
			$this->fatalError( 'JWT is not enabled on this wiki. Please setup JwtPublicKey and JwtPrivateKey' );
		}

		$token = trim( $this->readJWTFromInput() );

		if ( $this->hasOption( 'validate' ) ) {
			try {
				$data = $jwtCodec->parse( $token );
			} catch ( Exception $e ) {
				$this->fatalError( 'Error while parsing JWT: ' . $e->getMessage() );
			}
			$this->output( json_encode( $data, JSON_PRETTY_PRINT ) . PHP_EOL );
		} else {
			$parser = new Parser( new JoseEncoder() );
			try {
				$plain = $parser->parse( $token );
			} catch ( Exception $e ) {
				$this->fatalError( 'Error while parsing JWT: ' . $e->getMessage() );
			}
			/** @var Plain $plain */'@phan-var Plain $plain';
			$this->output( json_encode( $plain->claims()->all(), JSON_PRETTY_PRINT ) . PHP_EOL );
		}
	}
}

// @codeCoverageIgnoreStart
$maintClass = ExtractClaimsFromJwt::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
