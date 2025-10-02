<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

declare( strict_types=1 );

namespace Wikimedia\Stats\Emitters;

use InvalidArgumentException;
use UDPTransport;
use Wikimedia\Stats\Formatters\FormatterInterface;
use Wikimedia\Stats\Metrics\NullMetric;
use Wikimedia\Stats\StatsCache;
use Wikimedia\Stats\StatsUtils;

/**
 * Metrics UDP Emitter Implementation
 *
 * Leverages UDPTransport to emit wire-formatted metrics.
 *
 * @author Cole White
 * @since 1.41
 */
class UDPEmitter implements EmitterInterface {

	private string $prefix;
	private StatsCache $cache;
	private FormatterInterface $formatter;
	private ?UDPTransport $transport;
	private int $payloadSize;

	public function __construct( string $prefix, StatsCache $cache, FormatterInterface $formatter, ?string $target ) {
		$this->prefix = $this->normalizePrefix( $prefix );
		$this->cache = $cache;
		$this->formatter = $formatter;
		$this->transport = $target ? UDPTransport::newFromString( $target ) : null;
		$this->payloadSize = UDPTransport::MAX_PAYLOAD_SIZE;
	}

	/**
	 * Sets payload size for batching.
	 *
	 * @param int $payloadSize
	 * @return UDPEmitter
	 */
	public function withPayloadSize( int $payloadSize ): UDPEmitter {
		$this->payloadSize = $payloadSize;
		return $this;
	}

	/**
	 * Overrides the transport.
	 *
	 * @param UDPTransport $transport
	 * @return UDPEmitter
	 */
	public function withTransport( UDPTransport $transport ): UDPEmitter {
		$this->transport = $transport;
		return $this;
	}

	private function normalizePrefix( string $prefix ): string {
		if ( $prefix === '' ) {
			throw new InvalidArgumentException( 'UDPEmitter: Prefix cannot be empty.' );
		}
		return StatsUtils::normalizeString( $prefix );
	}

	/**
	 * Renders metrics and samples through the formatter and returns a string[] of wire-formatted metric samples.
	 */
	private function render(): array {
		$output = [];
		foreach ( $this->cache->getAllMetrics() as $metric ) {
			// Skip NullMetric instances.
			if ( get_class( $metric ) === NullMetric::class ) {
				continue;
			}
			foreach ( $this->formatter->getFormattedSamples( $this->prefix, $metric ) as $formatted ) {
				$output[] = $formatted;
			}
		}
		return $output;
	}

	/**
	 * Batch the array of samples into payload of payloadSize and
	 * emit them via the configured transport.
	 *
	 * @param array $samples
	 * @param int $payloadSize
	 * @return void
	 */
	private function batch( array $samples, int $payloadSize ): void {
		$payload = '';
		foreach ( $samples as $sample ) {
			if ( strlen( $payload ) + strlen( $sample ) + 1 > $payloadSize ) {
				// Send this payload and make a new one
				$this->transport->emit( $payload );
				$payload = '';
			}
			$payload .= $sample . "\n";
		}
		// Send what is left in the payload
		if ( strlen( $payload ) > 0 ) {
			$this->transport->emit( $payload );
		}
	}

	/**
	 * @inheritDoc
	 */
	public function send(): void {
		$this->batch( $this->render(), $this->payloadSize );
	}
}
