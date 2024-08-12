<?php
namespace Wikimedia\Telemetry;

use Wikimedia\Assert\Assert;

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
	 * Get or initialize the shared tracer state for the current process or web request.
	 * @return TracerState
	 */
	public static function getInstance(): TracerState {
		self::$instance ??= new self();
		return self::$instance;
	}

	/**
	 * Reset shared tracer state. Useful for testing.
	 * @return void
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
}
