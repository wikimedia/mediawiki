<?php
namespace Wikimedia\Telemetry;

/**
 * A no-op tracer that creates no-op spans and persists no data.
 * Useful for scenarios where tracing is disabled.
 * Can still propagate some (not-Span-specific) context, like X-Request-Id.
 *
 * @since 1.43
 * @internal
 */
class NoopTracer implements TracerInterface {

	private SpanContext $noopSpanContext;
	private TracerState $tracerState;
	private ?ContextPropagatorInterface $contextPropagator;

	public function __construct( ?ContextPropagatorInterface $contextPropagator = null ) {
		$this->noopSpanContext = new SpanContext( '', '', null, '', false );
		$this->tracerState = new TracerState();
		$this->contextPropagator = $contextPropagator;
	}

	/** @inheritDoc */
	public function createSpan( string $spanName, $parentSpan = null ): SpanInterface {
		return new NoopSpan( $this->tracerState, $this->noopSpanContext );
	}

	/** @inheritDoc */
	public function createRootSpan( string $spanName ): SpanInterface {
		return new NoopSpan( $this->tracerState, $this->noopSpanContext );
	}

	/** @inheritDoc */
	public function createSpanWithParent( string $spanName, SpanContext $parentSpanContext ): SpanInterface {
		return new NoopSpan( $this->tracerState, $this->noopSpanContext );
	}

	/** @inheritDoc */
	public function shutdown(): void {
		// no-op
	}

	/** @inheritDoc */
	public function createRootSpanFromCarrier( string $spanName, array $carrier ): SpanInterface {
		return new NoopSpan( $this->tracerState, $this->noopSpanContext );
	}

	/** @inheritDoc */
	public function getRequestHeaders(): array {
		$rv = [];
		if ( $this->contextPropagator ) {
			$rv = $this->contextPropagator->inject( $this->noopSpanContext, $rv );
		}
		return $rv;
	}
}
