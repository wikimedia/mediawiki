<?php
/**
 * Implements the LayeredParameterizedPassword class for the MediaWiki software.
 *
 * @license GPL-2.0-or-later
 * @file
 */

declare( strict_types = 1 );

namespace MediaWiki\Password;

use InvalidArgumentException;
use UnexpectedValueException;

/**
 * This password hash type layers one or more parameterized password types
 * on top of each other.
 *
 * The underlying types must be parameterized. This wrapping type accumulates
 * all the parameters and arguments from each hash and then passes the hash of
 * the last layer as the password for the next layer.
 *
 * @since 1.24
 */
class LayeredParameterizedPassword extends ParameterizedPassword {
	protected function getDelimiter(): string {
		return '!';
	}

	protected function getDefaultParams(): array {
		$params = [];

		foreach ( $this->config['types'] as $type ) {
			$passObj = $this->factory->newFromType( $type );

			if ( !$passObj instanceof ParameterizedPassword ) {
				throw new UnexpectedValueException( 'Underlying type must be a parameterized password.' );
			} elseif ( $passObj->getDelimiter() === $this->getDelimiter() ) {
				throw new UnexpectedValueException(
					'Underlying type cannot use same delimiter as encapsulating type.'
				);
			}

			$params[] = implode( $passObj->getDelimiter(), $passObj->getDefaultParams() );
		}

		return $params;
	}

	public function crypt( string $password ): void {
		$lastHash = $password;
		foreach ( $this->config['types'] as $i => $type ) {
			// Construct pseudo-hash based on params and arguments
			/** @var ParameterizedPassword $passObj */
			$passObj = $this->factory->newFromType( $type );
			'@phan-var ParameterizedPassword $passObj';

			$params = '';
			$args = '';
			if ( $this->params[$i] !== '' ) {
				$params = $this->params[$i] . $passObj->getDelimiter();
			}
			if ( isset( $this->args[$i] ) && $this->args[$i] !== '' ) {
				$args = $this->args[$i] . $passObj->getDelimiter();
			}
			$existingHash = ":$type:" . $params . $args . $this->hash;

			// Hash the last hash with the next type in the layer
			$passObj = $this->factory->newFromCiphertext( $existingHash );
			'@phan-var ParameterizedPassword $passObj';
			$passObj->crypt( $lastHash );

			// Move over the params and args
			$this->params[$i] = implode( $passObj->getDelimiter(), $passObj->params );
			$this->args[$i] = implode( $passObj->getDelimiter(), $passObj->args );
			$lastHash = $passObj->hash;
		}

		$this->hash = $lastHash;
	}

	/**
	 * Finish the hashing of a partially hashed layered hash
	 *
	 * Given a password hash that is hashed using the first layer of this object's
	 * configuration, perform the remaining layers of password hashing in order to
	 * get an updated hash with all the layers.
	 *
	 * @param ParameterizedPassword $passObj Password hash of the first layer
	 */
	public function partialCrypt( ParameterizedPassword $passObj ) {
		$type = $passObj->config['type'];
		if ( $type !== $this->config['types'][0] ) {
			throw new InvalidArgumentException( 'Only a hash in the first layer can be finished.' );
		}

		// Gather info from the existing hash
		$this->params[0] = implode( $passObj->getDelimiter(), $passObj->params );
		$this->args[0] = implode( $passObj->getDelimiter(), $passObj->args );
		$lastHash = $passObj->hash;

		// Layer the remaining types
		foreach ( $this->config['types'] as $i => $type ) {
			if ( $i == 0 ) {
				continue;
			}

			// Construct pseudo-hash based on params and arguments
			/** @var ParameterizedPassword $passObj */
			$passObj = $this->factory->newFromType( $type );
			'@phan-var ParameterizedPassword $passObj';

			$params = '';
			$args = '';
			if ( $this->params[$i] !== '' ) {
				$params = $this->params[$i] . $passObj->getDelimiter();
			}
			if ( isset( $this->args[$i] ) && $this->args[$i] !== '' ) {
				$args = $this->args[$i] . $passObj->getDelimiter();
			}
			$existingHash = ":$type:" . $params . $args . $this->hash;

			// Hash the last hash with the next type in the layer
			$passObj = $this->factory->newFromCiphertext( $existingHash );
			'@phan-var ParameterizedPassword $passObj';
			$passObj->crypt( $lastHash );

			// Move over the params and args
			$this->params[$i] = implode( $passObj->getDelimiter(), $passObj->params );
			$this->args[$i] = implode( $passObj->getDelimiter(), $passObj->args );
			$lastHash = $passObj->hash;
		}

		$this->hash = $lastHash;
	}
}

/** @deprecated since 1.43 use MediaWiki\\Password\\LayeredParameterizedPassword */
class_alias( LayeredParameterizedPassword::class, 'LayeredParameterizedPassword' );
