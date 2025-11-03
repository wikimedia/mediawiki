<?php
namespace Wikimedia\Telemetry;

use JsonSerializable;

/**
 * Data transfer object holding data associated with a given span.
 *
 * @since 1.43
 */
class SpanContext implements JsonSerializable {

	/**
	 * The ID of the trace this span is part of, as a hexadecimal string.
	 * @var string
	 */
	private string $traceId;

	/**
	 * The ID of this span, as a hexadecimal string.
	 * @var string
	 */
	private string $spanId;

	/**
	 * The ID of this span, as a hexadecimal string, or `null` if this is a root span.
	 * @var string|null
	 */
	private ?string $parentSpanId;

	/**
	 * A concise description of the work represented by this span.
	 * @see TracerInterface::createSpan()
	 * @var string
	 */
	private string $name;

	/**
	 * Whether the active sampler decided to sample and record this span.
	 * @var bool
	 */
	private bool $sampled;

	/**
	 * Key-value metadata associated with this span.
	 * @see Span::setAttributes()
	 * @var array
	 */
	private array $attributes = [];

	/**
	 * Describes the relationship of this span to other spans within the same trace.
	 * @see Span::setSpanKind()
	 * @var int
	 */
	private int $spanKind = SpanInterface::SPAN_KIND_INTERNAL;

	/**
	 * The success or failure of this span.
	 * @see Span::setSpanStatus()
	 * @var int
	 */
	private int $spanStatus = SpanInterface::SPAN_STATUS_UNSET;

	/**
	 * UNIX epoch timestamp in nanoseconds at which this span was started,
	 * or `null` if this span was not started yet.
	 * @var int|null
	 */
	private ?int $startEpochNanos = null;

	/**
	 * UNIX epoch timestamp in nanoseconds at which this span was ended,
	 * or `null` if this span was not ended yet.
	 * @var int|null
	 */
	private ?int $endEpochNanos = null;

	public function __construct(
		string $traceId,
		string $spanId,
		?string $parentSpanId,
		string $name,
		bool $sampled
	) {
		$this->traceId = $traceId;
		$this->spanId = $spanId;
		$this->parentSpanId = $parentSpanId;
		$this->name = $name;
		$this->sampled = $sampled;
	}

	public function setEndEpochNanos( int $endEpochNanos ): void {
		$this->endEpochNanos = $endEpochNanos;
	}

	public function setStartEpochNanos( int $startEpochNanos ): void {
		$this->startEpochNanos = $startEpochNanos;
	}

	public function setSpanKind( int $spanKind ): void {
		$this->spanKind = $spanKind;
	}

	public function setSpanStatus( int $status ): void {
		$this->spanStatus = $status;
	}

	public function setAttributes( array $attributes ): void {
		$this->attributes = array_merge( $this->attributes, $attributes );
	}

	public function isSampled(): bool {
		return $this->sampled;
	}

	public function getSpanId(): string {
		return $this->spanId;
	}

	public function getTraceId(): string {
		return $this->traceId;
	}

	public function getParentSpanId(): ?string {
		return $this->parentSpanId;
	}

	public function wasStarted(): bool {
		return $this->startEpochNanos !== null;
	}

	public function wasEnded(): bool {
		return $this->endEpochNanos !== null;
	}

	public function jsonSerialize(): array {
		$json = [
			'traceId' => $this->traceId,
			'parentSpanId' => $this->parentSpanId,
			'spanId' => $this->spanId,
			'name' => $this->name,
			'startTimeUnixNano' => $this->startEpochNanos,
			'endTimeUnixNano' => $this->endEpochNanos,
			'kind' => $this->spanKind
		];

		if ( $this->spanStatus !== SpanInterface::SPAN_STATUS_UNSET ) {
			$json['status'] = [ 'code' => $this->spanStatus ];
		}

		if ( $this->attributes ) {
			$json['attributes'] = OtlpSerializer::serializeKeyValuePairs( $this->attributes );
		}

		return $json;
	}

	/**
	 * Check whether the given SpanContext belongs to the same span.
	 *
	 * @param SpanContext|null $other
	 * @return bool
	 */
	public function equals( ?SpanContext $other ): bool {
		if ( $other === null ) {
			return false;
		}

		return $other->spanId === $this->spanId;
	}
}
