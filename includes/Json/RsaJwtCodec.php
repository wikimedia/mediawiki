<?php

namespace MediaWiki\Json;

use DateTimeImmutable;
use Lcobucci\JWT;
use Lcobucci\JWT\Encoding\JoseEncoder;
use Lcobucci\JWT\Encoding\UnifyAudience;
use Lcobucci\JWT\Signer\Key;
use Lcobucci\JWT\Signer\Rsa;
use Lcobucci\JWT\Token\Parser;
use Lcobucci\JWT\Token\Plain;
use Lcobucci\JWT\Token\RegisteredClaims;
use Lcobucci\JWT\Validation\Constraint\LooseValidAt;
use Lcobucci\JWT\Validation\Constraint\SignedWith;
use Lcobucci\JWT\Validation\Validator;
use LogicException;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\Logger\LoggerFactory;
use MediaWiki\MainConfigNames;

/**
 * JwtCodec using lcobucci/jwt with an RSA key ($wgJwtPublicKey / $wgJwtPrivateKey).
 *
 * @since 1.45
 */
class RsaJwtCodec implements JwtCodec {

	/** @internal */
	public const CONSTRUCTOR_OPTIONS = [
		MainConfigNames::JwtPublicKey,
		MainConfigNames::JwtPrivateKey,
	];

	public function __construct(
		private ServiceOptions $serviceOptions
	) {
		$this->serviceOptions->assertRequiredOptions( self::CONSTRUCTOR_OPTIONS );
	}

	/** @inheritDoc */
	public function isEnabled(): bool {
		return $this->serviceOptions->get( MainConfigNames::JwtPublicKey )
			&& $this->serviceOptions->get( MainConfigNames::JwtPrivateKey );
	}

	/** @inheritDoc */
	public function create( array $claims ): string {
		if ( !$this->isEnabled() ) {
			throw new LogicException( 'JWT handling is not configured' );
		}

		$tokenBuilder = new SimpleJwtBuilder( new JoseEncoder(), new UnifyAudience() );
		$signingAlgorithm = new Rsa\Sha256();
		$privateKey = Key\InMemory::plainText( $this->serviceOptions->get( MainConfigNames::JwtPrivateKey ) );

		$tokenBuilder->setClaims( $claims );
		return $tokenBuilder->getToken( $signingAlgorithm, $privateKey )->toString();
	}

	/** @inheritDoc */
	public function parse( string $jwt ): array {
		if ( !$this->isEnabled() ) {
			throw new LogicException( 'JWT handling is not configured' );
		}

		$parser = new Parser( new JoseEncoder() );
		$signingAlgorithm = new Rsa\Sha256();
		$privateKey = Key\InMemory::plainText( $this->serviceOptions->get( MainConfigNames::JwtPublicKey ) );
		$validator = new Validator();
		$validationConstraints = [
			new SignedWith( $signingAlgorithm, $privateKey ),
			new LooseValidAt( new ClockAdapter() ),
		];

		try {
			$token = $parser->parse( $jwt );
			/** @var Plain $token */'@phan-var Plain $token';
			$validator->assert( $token, ...$validationConstraints );
		} catch ( JWT\Exception $e ) {
			LoggerFactory::getInstance( 'json' )->warning( 'Invalid JWT: {error}', [
				'error' => $e->getMessage(),
				'jwtString' => $jwt,
			] );
			throw new JwtException( $e->getMessage(), [], $e->getCode(), $e );
		}

		$claims = $token->claims()->all();

		// The lcobucci parser tries to be too clever for its own good, and converts some claims to
		// PHP objects. Undo that.
		foreach ( $claims as $claimName => $claimValue ) {
			if ( in_array( $claimName, RegisteredClaims::DATE_CLAIMS ) ) {
				/** @var DateTimeImmutable $claimValue */'@phan-var DateTimeImmutable $claimValue';
				$claims[$claimName] = $claimValue->getTimestamp();
			}
		}

		return $claims;
	}

}
