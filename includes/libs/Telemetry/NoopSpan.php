<?php
namespace Wikimedia\Telemetry;

/**
 * An unsampled span that does nothing and persists no data.
 *
 * @since 1.43
 * @internal
 */
class NoopSpan implements SpanInterface {
	private SpanContext $context;
	private TracerState $tracerState;

	public function __construct(
		TracerState $tracerState,
		SpanContext $context
	) {
		$this->tracerState = $tracerState;
		$this->context = $context;
	}

	/** @inheritDoc */
	public function getContext(): SpanContext {
		return $this->context;
	}

	/** @inheritDoc */
	public function setAttributes( array $attributes ): SpanInterface {
		return $this;
	}

	/** @inheritDoc */
	public function setSpanKind( int $spanKind ): SpanInterface {
		return $this;
	}

	/** @inheritDoc */
	public function setSpanStatus( int $status ): SpanInterface {
		return $this;
	}

	/** @inheritDoc */
	public function start( ?int $epochNanos = null ): SpanInterface {
		return $this;
	}

	/** @inheritDoc */
	public function end( ?int $epochNanos = null ): void {
		// no-op
	}

	/** @inheritDoc */
	public function activate(): SpanInterface {
		$this->tracerState->activateSpan( $this->context );
		return $this;
	}

	/** @inheritDoc */
	public function deactivate(): SpanInterface {
		$this->tracerState->deactivateSpan( $this->context );
		return $this;
	}
}
