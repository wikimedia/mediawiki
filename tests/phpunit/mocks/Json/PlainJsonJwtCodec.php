<?php

namespace MediaWiki\Tests\Mocks\Json;

use MediaWiki\Json\JwtCodec;
use MediaWiki\Json\JwtException;
use Wikimedia\Timestamp\ConvertibleTimestamp;

/**
 * A JwtCodec replacement that outputs plain unencrypted JSON.
 */
class PlainJsonJwtCodec implements JwtCodec {

	/** @inheritDoc */
	public function isEnabled(): bool {
		return true;
	}

	/** @inheritDoc */
	public function create( array $claims ): string {
		$jwt = json_encode( $claims,
			JSON_UNESCAPED_SLASHES
				| JSON_UNESCAPED_UNICODE
		);
		if ( $jwt === false ) {
			throw new JwtException( json_last_error_msg() );
		}
		return $jwt;
	}

	/** @inheritDoc */
	public function parse( string $jwt ): array {
		$claims = json_decode( $jwt, true );
		if ( $claims === null ) {
			throw new JwtException( json_last_error_msg() );
		}

		// match LooseValidAt check in RsaJwtToken
		if ( ( $claims['iat'] ?? 0 ) > ConvertibleTimestamp::time() ) {
			throw new JwtException( 'The token was issued in the future' );
		} elseif ( ( $claims['nbf'] ?? 0 ) > ConvertibleTimestamp::time() ) {
			throw new JwtException( 'The token cannot be used yet' );
		} elseif ( ( $claims['exp'] ?? PHP_INT_MAX ) <= ConvertibleTimestamp::time() ) {
			throw new JwtException( 'The token is expired' );
		}

		return $claims;
	}

}
