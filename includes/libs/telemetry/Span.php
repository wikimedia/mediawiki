<?php
namespace Wikimedia\Telemetry;

use Wikimedia\Assert\Assert;

/**
 * Represents an OpenTelemetry span, i.e. a single operation within a trace.
 *
 * @since 1.43
 * @see https://opentelemetry.io/docs/specs/otel/trace/api/#span
 */
class Span implements SpanInterface {

	private Clock $clock;

	private TracerState $tracerState;

	private SpanContext $context;

	public function __construct(
		Clock $clock,
		TracerState $tracerState,
		SpanContext $context
	) {
		$this->clock = $clock;
		$this->tracerState = $tracerState;
		$this->context = $context;
	}

	public function __destruct() {
		$this->end();
		$activeSpanContext = $this->tracerState->getActiveSpanContext();
		if ( $this->context->equals( $activeSpanContext ) ) {
			$this->deactivate();
		}
	}

	/** @inheritDoc */
	public function getContext(): SpanContext {
		return $this->context;
	}

	/** @inheritDoc */
	public function setAttributes( array $attributes ): SpanInterface {
		$this->context->setAttributes( $attributes );
		return $this;
	}

	/** @inheritDoc */
	public function setSpanKind( int $spanKind ): SpanInterface {
		$this->context->setSpanKind( $spanKind );
		return $this;
	}

	/** @inheritDoc */
	public function setSpanStatus( int $status ): SpanInterface {
		$this->context->setSpanStatus( $status );
		return $this;
	}

	/** @inheritDoc */
	public function start( ?int $epochNanos = null ): SpanInterface {
		Assert::precondition(
			!$this->context->wasStarted(),
			'Cannot start a span more than once'
		);

		$this->context->setStartEpochNanos( $epochNanos ?? $this->clock->getCurrentNanoTime() );

		return $this;
	}

	/** @inheritDoc */
	public function end( ?int $epochNanos = null ): void {
		Assert::precondition(
			$this->context->wasStarted(),
			'Cannot end a span that has not been started'
		);

		// Make duplicate end() calls a no-op, since it may occur legitimately,
		// e.g. when a span wrapped in an RAII ScopedSpan wrapper is ended explicitly.
		if ( !$this->context->wasEnded() ) {
			$this->context->setEndEpochNanos( $epochNanos ?? $this->clock->getCurrentNanoTime() );
			$this->tracerState->addSpanContext( $this->context );
		}
	}

	/** @inheritDoc */
	public function activate(): SpanInterface {
		$this->tracerState->activateSpan( $this->getContext() );
		return $this;
	}

	/** @inheritDoc */
	public function deactivate(): SpanInterface {
		$this->tracerState->deactivateSpan( $this->getContext() );
		return $this;
	}

}
