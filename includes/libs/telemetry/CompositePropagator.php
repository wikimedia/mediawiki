<?php
namespace Wikimedia\Telemetry;

use Wikimedia\Assert\Assert;
use Wikimedia\Assert\ParameterAssertionException;

/**
 * CompositePropagator accepts an array of other propagators.
 * It delegates to each of them in order when extracting or injecting.
 * When extracting, it returns the first non-null result.
 */
class CompositePropagator implements ContextPropagatorInterface {
	/** @var ContextPropagatorInterface[] */
	private $propagators;

	/**
	 * @param ContextPropagatorInterface[] $propagators
	 * @throws ParameterAssertionException if $propagators is empty
	 */
	public function __construct( array $propagators ) {
		Assert::parameter(
			count( $propagators ) > 0,
			'$propagators',
			'must not be empty'
		);
		$this->propagators = $propagators;
	}

	/**
	 * @inheritDoc
	 */
	public function extract( array $carrier ): ?SpanContext {
		foreach ( $this->propagators as $propagator ) {
			$context = $propagator->extract( $carrier );
			if ( $context !== null ) {
				return $context;
			}
		}
		return null;
	}

	/**
	 * @inheritDoc
	 */
	public function inject( ?SpanContext $context, array $carrier ): array {
		foreach ( $this->propagators as $propagator ) {
			$carrier = $propagator->inject( $context, $carrier );
		}
		return $carrier;
	}
}
