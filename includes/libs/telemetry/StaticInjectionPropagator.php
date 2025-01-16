<?php
namespace Wikimedia\Telemetry;

/**
 * A {@link ContextPropagatorInterface} implementation that injects
 * a fixed set of headers into outgoing requests.  It does not perform extraction.
 *
 * @since 1.44
 * @internal
 */
class StaticInjectionPropagator implements ContextPropagatorInterface {
	private array $headers;

	public function __construct( array $headers ) {
		$this->headers = $headers;
	}

	/**
	 * @inheritDoc
	 */
	public function inject( ?SpanContext $spanContext, array $carrier ): array {
		foreach ( $this->headers as $key => $value ) {
			$carrier[$key] = $value;
		}
		return $carrier;
	}

	/**
	 * @inheritDoc
	 */
	public function extract( array $carrier ): ?SpanContext {
		return null;
	}
}
