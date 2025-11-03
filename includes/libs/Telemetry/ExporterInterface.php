<?php
namespace Wikimedia\Telemetry;

/**
 * Base interface for OTEL trace data exporters.
 * @since 1.43
 * @internal
 */
interface ExporterInterface {

	/**
	 * Export all trace data.
	 * @param TracerState $tracerState
	 * @return void
	 */
	public function export( TracerState $tracerState ): void;
}
