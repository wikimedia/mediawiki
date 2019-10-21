<?php

namespace MediaWiki\Config;

use Config;
use InvalidArgumentException;
use Wikimedia\Assert\Assert;

/**
 * A class for passing options to services. It can be constructed from a Config, and in practice
 * most options will be taken from site configuration, but they don't have to be. The options passed
 * are copied and will not reflect subsequent updates to site configuration (assuming they're not
 * objects).
 *
 * Services that take this type as a parameter to their constructor should specify a list of the
 * keys they expect to receive in an array. The convention is to make it a public const called
 * CONSTRUCTOR_OPTIONS. In the constructor, they should call assertRequiredOptions() to make sure
 * that they weren't passed too few or too many options. This way it's clear what each class
 * depends on, and that it's getting passed the correct set of options. (This means there are no
 * optional options. This makes sense for services, since they shouldn't be constructed by
 * outside code.)
 *
 * @since 1.34
 */
class ServiceOptions {
	private $keys = [];
	private $options = [];

	/**
	 * @param string[] $keys Which keys to extract from $sources
	 * @param Config|array ...$sources Each source is either a Config object or an array. If the
	 *  same key is present in two sources, the first one takes precedence. Keys that are not in
	 *  $keys are ignored.
	 * @throws InvalidArgumentException if one of $keys is not found in any of $sources
	 */
	public function __construct( array $keys, ...$sources ) {
		$this->keys = $keys;
		foreach ( $keys as $key ) {
			foreach ( $sources as $source ) {
				if ( $source instanceof Config ) {
					if ( $source->has( $key ) ) {
						$this->options[$key] = $source->get( $key );
						continue 2;
					}
				} else {
					if ( array_key_exists( $key, $source ) ) {
						$this->options[$key] = $source[$key];
						continue 2;
					}
				}
			}
			throw new InvalidArgumentException( "Key \"$key\" not found in input sources" );
		}
	}

	/**
	 * Assert that the list of options provided in this instance exactly match $expectedKeys,
	 * without regard for order.
	 *
	 * @param string[] $expectedKeys
	 */
	public function assertRequiredOptions( array $expectedKeys ) {
		if ( $this->keys !== $expectedKeys ) {
			$extraKeys = array_diff( $this->keys, $expectedKeys );
			$missingKeys = array_diff( $expectedKeys, $this->keys );
			Assert::precondition( !$extraKeys && !$missingKeys,
				(
				$extraKeys
					? 'Unsupported options passed: ' . implode( ', ', $extraKeys ) . '!'
					: ''
				) . ( $extraKeys && $missingKeys ? ' ' : '' ) . (
				$missingKeys
					? 'Required options missing: ' . implode( ', ', $missingKeys ) . '!'
					: ''
				)
			);
		}
	}

	/**
	 * @param string $key
	 * @return mixed
	 */
	public function get( $key ) {
		if ( !array_key_exists( $key, $this->options ) ) {
			throw new InvalidArgumentException( "Unrecognized option \"$key\"" );
		}
		return $this->options[$key];
	}
}
