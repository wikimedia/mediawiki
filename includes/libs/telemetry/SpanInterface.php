<?php
namespace Wikimedia\Telemetry;

/**
 * Represents an OpenTelemetry span, i.e. a single operation within a trace.
 *
 * A Span is literally a span of time, with an operation name, and an optional
 * set of key/value attributes. It may be attached to a parent span, or it may
 * be the root of a trace.
 *
 * @since 1.43
 * @see https://opentelemetry.io/docs/specs/otel/trace/api/#span
 */
interface SpanInterface {
	/**
	 * Default value. Indicates that the span represents an internal operation within an application,
	 * as opposed to an operations with remote parents or children. For example: wikitext parsing.
	 */
	public const SPAN_KIND_INTERNAL = 1;

	/**
	 * Indicates that the span covers server-side handling of a synchronous RPC or other
	 * incoming request from a remote client.
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
	 * Set the kind of this span, which describes how it relates to its parent and children
	 * within the overarching trace.
	 *
	 * @param int $spanKind One of the SpanInterface::SPAN_KIND_** constants
	 * @see https://opentelemetry.io/docs/specs/otel/trace/api/#spankind
	 * @return SpanInterface fluent interface
	 */
	public function setSpanKind( int $spanKind ): SpanInterface;

	/**
	 * Default value. Indicates that the span status is not set.
	 * This is to ensure that successes are recorded explicitly.
	 */
	public const SPAN_STATUS_UNSET = 0;

	/**
	 * Indicates that the operation represented by this span was successful.
	 */
	public const SPAN_STATUS_OK = 1;

	/**
	 * Indicates that the operation represented by this span failed.
	 * Will be searchable in the trace viewer, for example with the `error=true` tag in Jaeger.
	 */
	public const SPAN_STATUS_ERROR = 2;

	/**
	 * Set the status of this span. By default this is SPAN_STATUS_UNSET.
	 *
	 * @param int $spanStatus One of the SpanInterface::SPAN_STATUS_** constants
	 * @return SpanInterface fluent interface
	 */
	public function setSpanStatus( int $spanStatus ): SpanInterface;

	/**
	 * Set attributes (arbitrary metadata) for this span.
	 * Any existing attributes with the same keys will be overwritten.
	 * Attributes with a `null` value will be ignored during export.
	 *
	 * These attributes will be attached to the span and will be searchable in the trace viewer.
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
	 * Start this span, optionally specifying an override for its start time.
	 *
	 * @param int|null $epochNanos The start time to use, or `null` to use the current time.
	 * @return SpanInterface
	 */
	public function start( ?int $epochNanos = null ): SpanInterface;

	/**
	 * End this span, optionally specifying an override for its end time.
	 *
	 * Spans will also automatically be ended shortly after they go out of scope, but calling
	 * end() yourself is a good way to ensure the end timestamp is accurate.
	 *
	 * @param int|null $epochNanos The end time to use, or `null` to use the current time.
	 * @return void
	 */
	public function end( ?int $epochNanos = null ): void;

	/**
	 * Make this span the active span.
	 *
	 * This will cause any spans started without specifying an explicit parent to automatically
	 * become children of this span, for as long as it remains active.
	 *
	 * Activated spans form a stack; the most recently activated span is the active span.
	 *
	 * Long-running synchronous operations are good candidates for activation, especially if they
	 * will have other children spans created by code that is not explicitly aware of the parent span.
	 * For example, a MediaWiki EntryPoint might activate a span at the start of the request.
	 * Or a large unit of work (like wikitext parsing) might activate their own span, so that all
	 * database queries and other operations that occur during that work are automatically made descendants.
	 *
	 * @return SpanInterface fluent interface
	 */
	public function activate(): SpanInterface;

	/**
	 * Deactivate this span.
	 *
	 * Spans will also automatically be deactivated when they go out of scope.
	 *
	 * @return SpanInterface fluent interface
	 */
	public function deactivate(): SpanInterface;

	/**
	 * Get the context holding data for this span.
	 * @return SpanContext
	 */
	public function getContext(): SpanContext;
}
