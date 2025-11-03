<?php
namespace Wikimedia\Telemetry;

use Wikimedia\Assert\Assert;

/**
 * @since 1.43
 * @internal
 */
class Tracer implements TracerInterface {
	/**
	 * The length of a trace ID in bytes, as specified by the OTEL specification.
	 */
	private const TRACE_ID_BYTES_LENGTH = 16;

	/**
	 * The length of a span ID in bytes, as specified by the OTEL specification.
	 */
	private const SPAN_ID_BYTES_LENGTH = 8;

	private Clock $clock;
	private SamplerInterface $sampler;
	private ExporterInterface $exporter;
	private ContextPropagatorInterface $contextPropagator;
	private TracerState $tracerState;

	/**
	 * Whether tracing has been explicitly ended by calling shutdown() on this instance.
	 * @var bool
	 */
	private bool $wasShutdown = false;

	public function __construct(
		Clock $clock,
		SamplerInterface $sampler,
		ExporterInterface $exporter,
		TracerState $tracerState,
		ContextPropagatorInterface $contextPropagator
	) {
		$this->clock = $clock;
		$this->sampler = $sampler;
		$this->exporter = $exporter;
		$this->tracerState = $tracerState;
		$this->contextPropagator = $contextPropagator;
	}

	/** @inheritDoc */
	public function createSpan( string $spanName ): SpanInterface {
		$activeSpanContext = $this->tracerState->getActiveSpanContext();

		// Gracefully handle attempts to instrument code after shutdown() was called.
		if ( !$this->wasShutdown ) {
			Assert::precondition(
				$activeSpanContext !== null,
				'Attempted to create a span with the currently active span as the implicit parent, ' .
				'but no span was active. Use createRootSpan() to create a span with no parent (i.e. a root span).'
			);
		}

		return $this->newSpan( $spanName, $activeSpanContext );
	}

	/** @inheritDoc */
	public function createRootSpan( string $spanName ): SpanInterface {
		return $this->newSpan( $spanName, null );
	}

	/** @inheritDoc */
	public function createSpanWithParent( string $spanName, SpanContext $parentSpanContext ): SpanInterface {
		return $this->newSpan( $spanName, $parentSpanContext );
	}

	/** @inheritDoc */
	public function createRootSpanFromCarrier( string $spanName, array $carrier ): SpanInterface {
		$spanContext = $this->contextPropagator->extract( $carrier );
		return $this->newSpan( $spanName, $spanContext );
	}

	/** @inheritDoc */
	public function getRequestHeaders(): array {
		$activeSpanContext = $this->tracerState->getActiveSpanContext();
		return $this->contextPropagator->inject( $activeSpanContext, [] );
	}

	private function newSpan( string $spanName, ?SpanContext $parentSpanContext ): SpanInterface {
		$traceId = $parentSpanContext !== null ?
			$parentSpanContext->getTraceId() : $this->generateId( self::TRACE_ID_BYTES_LENGTH );
		$spanId = $this->generateId( self::SPAN_ID_BYTES_LENGTH );
		$sampled = $this->sampler->shouldSample( $parentSpanContext );

		$spanContext = new SpanContext(
			$traceId,
			$spanId,
			$parentSpanContext !== null ? $parentSpanContext->getSpanId() : null,
			$spanName,
			$sampled
		);

		if ( $this->wasShutdown || !$sampled ) {
			return new NoopSpan( $this->tracerState, $spanContext );
		}

		return new Span(
			$this->clock,
			$this->tracerState,
			$spanContext
		);
	}

	/** @inheritDoc */
	public function shutdown(): void {
		$this->wasShutdown = true;
		$this->exporter->export( $this->tracerState );
	}

	/**
	 * Generate a valid hexadecimal string for use as a trace or span ID, with the given length in bytes.
	 *
	 * @param int $bytesLength The byte length of the ID
	 * @return string The ID as a hexadecimal string
	 */
	private function generateId( int $bytesLength ): string {
		return bin2hex( random_bytes( $bytesLength ) );
	}
}
