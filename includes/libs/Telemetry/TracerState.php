<?php
namespace Wikimedia\Telemetry;

use Wikimedia\Assert\Assert;
use Wikimedia\Assert\PreconditionException;

/**
 * Holds shared telemetry state, such as finished span data buffered for export.
 *
 * Since this data is tied to the lifetime of a given web request or process, this class is a singleton to
 * avoid discarding data in the case of MediaWiki service container resets.
 *
 * @since 1.43
 * @internal
 */
class TracerState {
	/**
	 * Shared tracer state for the current process or web request, `null` if uninitialized.
	 * @var TracerState|null
	 */
	private static ?TracerState $instance = null;

	/**
	 * List of already finished spans to be exported.
	 * @var SpanContext[]
	 */
	private array $finishedSpanContexts = [];

	/**
	 * Stack holding contexts for activated spans.
	 * @var SpanContext[]
	 */
	private array $activeSpanContextStack = [];

	/**
	 * The root span of this process, or `null` if not initialized yet.
	 * @var SpanInterface|null
	 */
	private ?SpanInterface $rootSpan = null;

	/**
	 * Get or initialize the shared tracer state for the current process or web request.
	 */
	public static function getInstance(): TracerState {
		self::$instance ??= new self();
		return self::$instance;
	}

	/**
	 * Reset shared tracer state. Useful for testing.
	 */
	public static function destroyInstance(): void {
		Assert::precondition(
			defined( 'MW_PHPUNIT_TEST' ),
			'This function can only be called in tests'
		);

		self::$instance = null;
	}

	/**
	 * Add the given span to the list of finished spans.
	 * @param SpanContext $context
	 * @return void
	 */
	public function addSpanContext( SpanContext $context ): void {
		$this->finishedSpanContexts[] = $context;
	}

	/**
	 * Get the list of finished spans.
	 * @return SpanContext[]
	 */
	public function getSpanContexts(): array {
		return $this->finishedSpanContexts;
	}

	/**
	 * Clear the list of finished spans.
	 */
	public function clearSpanContexts(): void {
		$this->finishedSpanContexts = [];
	}

	/**
	 * Make the given span the active span.
	 * @param SpanContext $spanContext Context of the span to activate
	 * @return void
	 */
	public function activateSpan( SpanContext $spanContext ): void {
		$this->activeSpanContextStack[] = $spanContext;
	}

	/**
	 * Deactivate the given span, if it was the active span.
	 * @param SpanContext $spanContext Context of the span to deactivate
	 * @return void
	 */
	public function deactivateSpan( SpanContext $spanContext ): void {
		$activeSpanContext = $this->getActiveSpanContext();

		Assert::invariant(
			$activeSpanContext !== null && $activeSpanContext->getSpanId() === $spanContext->getSpanId(),
			'Attempted to deactivate a span which is not the active span.'
		);

		array_pop( $this->activeSpanContextStack );
	}

	/**
	 * Get the context of the currently active span, or `null` if no span is active.
	 * @return SpanContext|null
	 */
	public function getActiveSpanContext(): ?SpanContext {
		return $this->activeSpanContextStack[count( $this->activeSpanContextStack ) - 1] ?? null;
	}

	/**
	 * Set the root span associated with the current request or process.
	 *
	 * @since 1.44
	 * @param SpanInterface $rootSpan The root span to set.
	 * @throws PreconditionException If a root span was already initialized for this request or process.
	 */
	public function setRootSpan( SpanInterface $rootSpan ): void {
		Assert::precondition(
			$this->rootSpan === null,
			'Attempted to set a new root span while one was already initialized.'
		);
		$this->rootSpan = $rootSpan;
	}

	/**
	 * End the root span associated with the current request or process.
	 *
	 * @since 1.44
	 * @param int $spanStatus The status of the root span. One of the SpanInterface::SPAN_STATUS_** constants.
	 */
	public function endRootSpan( int $spanStatus = SpanInterface::SPAN_STATUS_UNSET ): void {
		if ( $this->rootSpan !== null ) {
			if ( $spanStatus !== SpanInterface::SPAN_STATUS_UNSET ) {
				$this->rootSpan->setSpanStatus( $spanStatus );
			}
			$this->rootSpan->end();
		}
	}
}
