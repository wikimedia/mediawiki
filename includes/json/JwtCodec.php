<?php

namespace MediaWiki\Json;

/**
 * A helper to read and write JWTs without having to depend on a specific library,
 * and using central configuration for the encryption details.
 *
 * @since 1.45
 * @stable to implement
 */
interface JwtCodec {

	/**
	 * Whether the codec can be used (disabled means it hasn't been properly configured).
	 * All other methods will throw on a disabled JwtCodec.
	 */
	public function isEnabled(): bool;

	/**
	 * Creates a JWT string with the given claims.
	 */
	public function create( array $claims ): string;

	/**
	 * Parses and partially validates a JWT string, and returns the set of claim inside it.
	 * The validation covers the signature and time fields; other checks are left to the caller.
	 * @throws JwtException If the JWT is invalid (expired, has invalid signature or can't
	 *   even be parsed).
	 */
	public function parse( string $jwt ): array;

}
