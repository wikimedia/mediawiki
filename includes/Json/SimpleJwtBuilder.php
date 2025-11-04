<?php

namespace MediaWiki\Json;

use Lcobucci\JWT\ClaimsFormatter;
use Lcobucci\JWT\Encoder;
use Lcobucci\JWT\Signer;
use Lcobucci\JWT\Signer\Key;
use Lcobucci\JWT\Token\DataSet;
use Lcobucci\JWT\Token\Plain;
use Lcobucci\JWT\Token\Signature;

/**
 * Duplicates the logic from lcobucci/jwt's Builder class, without the annoying syntax that makes
 * it really hard to decouple the MediaWiki callers from the library.
 * @since 1.45
 * @see https://github.com/lcobucci/jwt/blob/4.1.5/src/Token/Builder.php
 */
class SimpleJwtBuilder {

	private array $headers = [ 'typ' => 'JWT', 'alg' => null ];

	/** @var array<string, mixed> */
	private array $claims = [];

	public function __construct(
		private Encoder $encoder,
		private ClaimsFormatter $claimFormatter
	) {
	}

	public function setClaims( array $claims ): void {
		$this->claims = $claims;
	}

	public function getToken( Signer $signer, Key $key ): Plain {
		$headers = $this->headers;
		$headers['alg'] = $signer->algorithmId();

		$encodedHeaders = $this->encode( $headers );
		$encodedClaims = $this->encode( $this->claimFormatter->formatClaims( $this->claims ) );

		$signature = $signer->sign( $encodedHeaders . '.' . $encodedClaims, $key );
		$encodedSignature = $this->encoder->base64UrlEncode( $signature );

		return new Plain(
			new DataSet( $headers, $encodedHeaders ),
			new DataSet( $this->claims, $encodedClaims ),
			new Signature( $signature, $encodedSignature ),
		);
	}

	private function encode( array $items ): string {
		return $this->encoder->base64UrlEncode(
			$this->encoder->jsonEncode( $items )
		);
	}

}
