<?php
/**
 * Metrics UDP Emitter Implementation
 *
 * Leverages UDPTransport to emit wire-formatted metrics.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @license GPL-2.0-or-later
 * @author Cole White
 * @since 1.41
 */

declare( strict_types=1 );

namespace Wikimedia\Metrics;

use UDPTransport;

class MetricsUDPEmitter {

	/** @var RendererInterface */
	private RendererInterface $renderer;

	private ?UDPTransport $transport;

	/** @var int */
	private int $payloadSize;

	public function __construct( RendererInterface $renderer ) {
		$this->renderer = $renderer;
		$this->payloadSize = UDPTransport::MAX_PAYLOAD_SIZE;
	}

	/**
	 * Sets payload size for batching.
	 *
	 * @param int $payloadSize
	 * @return MetricsUDPEmitter
	 */
	public function withPayloadSize( int $payloadSize ): MetricsUDPEmitter {
		$this->payloadSize = $payloadSize;
		return $this;
	}

	/**
	 * Sets transport to UDPTransport with target URI.
	 *
	 * @param string|null $target
	 * @return MetricsUDPEmitter
	 */
	public function withTarget( ?string $target ): MetricsUDPEmitter {
		if ( $target !== null ) {
			$this->transport = UDPTransport::newFromString( $target );
		}
		return $this;
	}

	/**
	 * Sets transport.
	 *
	 * @param UDPTransport $transport
	 * @return MetricsUDPEmitter
	 */
	public function withTransport( UDPTransport $transport ): MetricsUDPEmitter {
		$this->transport = $transport;
		return $this;
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
			if ( strlen( $payload ) + strlen( $sample ) + 1 < $payloadSize ) {
				$payload .= $sample . "\n";
			} else {
				// Send this payload and make a new one
				$this->transport->emit( $payload );
				$payload = '';
			}
		}
		// Send what is left in the payload
		if ( strlen( $payload ) > 0 ) {
			$this->transport->emit( $payload );
		}
	}

	/**
	 * Calls render() on the RendererInterface instance, groups the result into payloads,
	 * then sends the payloads through the transport instance.
	 *
	 * @return void
	 */
	public function send(): void {
		if ( $this->transport ) {
			$this->batch( $this->renderer->render(), $this->payloadSize );
		}
	}
}
