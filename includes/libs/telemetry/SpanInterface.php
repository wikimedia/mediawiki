<?php
namespace Wikimedia\Telemetry;

/**
 * Represents an OpenTelemetry span, i.e. a single operation within a trace.
 *
 * @since 1.43
 * @see https://opentelemetry.io/docs/specs/otel/trace/api/#span
 */
interface SpanInterface {
	/**
	 * Default value. Indicates that the span represents an internal operation within an application,
	 * as opposed to an operations with remote parents or children.
	 */
	public const SPAN_KIND_INTERNAL = 1;

	/**
	 * Indicates that the span covers server-side handling of a synchronous RPC or other remote request.
	 */
	public const SPAN_KIND_SERVER = 2;

	/**
	 * Indicates that the span describes a request to some remote service.
	 */
	public const SPAN_KIND_CLIENT = 3;

	/**
	 * Indicates that the span describes the initiators of an asynchronous request.
	 */
	public const SPAN_KIND_PRODUCER = 4;

	/**
	 * Indicates that the span describes a child of an asynchronous
	 * {@link SpanInterface::SPAN_KIND_PRODUCER} request.
	 */
	public const SPAN_KIND_CONSUMER = 5;

	/**
	 * Get the context holding data for this span.
	 * @return SpanContext
	 */
	public function getContext(): SpanContext;

	/**
	 * Set attributes (arbitrary metadata) for this span.
	 *
	 * When deciding on the set of attributes to register as well as their naming, consider following
	 * <a href="https://opentelemetry.io/docs/specs/semconv/general/trace/">Semantic Conventions</a> where
	 * applicable.
	 *
	 * @param array $attributes key-value mapping of attribute names to values
	 * @return SpanInterface fluent interface
	 */
	public function setAttributes( array $attributes ): SpanInterface;

	/**
	 * Set the kind of this span, which describes how it relates to its parent and children
	 * within the overarching trace.
	 *
	 * @param int $spanKind One of the SpanInterface::SPAN_KIND_** constants
	 * @see https://opentelemetry.io/docs/specs/otel/trace/api/#spankind
	 * @return SpanInterface fluent interface
	 */
	public function setSpanKind( int $spanKind ): SpanInterface;

	/**
	 * Start this span, optionally specifying an override for its start time.
	 * @param int|null $epochNanos The start time to use, or `null` to use the current time.
	 * @return SpanInterface
	 */
	public function start( ?int $epochNanos = null ): SpanInterface;

	/**
	 * End this span, optionally specifying an override for its end time.
	 * @param int|null $epochNanos The end time to use, or `null` to use the current time.
	 * @return void
	 */
	public function end( ?int $epochNanos = null ): void;

	/**
	 * Make this span the active span.
	 *
	 * This will cause any spans started without specifying an explicit parent to automatically
	 * become children of this span as long as it remains active.
	 *
	 * @return void
	 */
	public function activate(): void;

	/**
	 * Deactivate this span.
	 * @return void
	 */
	public function deactivate(): void;
}
