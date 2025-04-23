<?php
/**
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
 * @file
 */

declare( strict_types=1 );

namespace Wikimedia\Stats\Metrics;

use InvalidArgumentException;
use Psr\Log\LoggerInterface;
use Wikimedia\Stats\Exceptions\IllegalOperationException;

/**
 * Implementation code common to all metric types.
 *
 * @internal
 * @since 1.43
 */
trait MetricTrait {

	private BaseMetricInterface $baseMetric;
	private LoggerInterface $logger;
	private ?string $bucket = null;

	/** @inheritDoc */
	public function __construct( $baseMetric, $logger ) {
		$this->baseMetric = $baseMetric;
		$this->logger = $logger;
	}

	/** @inheritDoc */
	public function getName(): string {
		return $this->baseMetric->getName();
	}

	/** @inheritDoc */
	public function getComponent(): string {
		return $this->baseMetric->getComponent();
	}

	/** @inheritDoc */
	public function getSamples(): array {
		return $this->baseMetric->getSamples();
	}

	/** @inheritDoc */
	public function getSampleCount(): int {
		return $this->baseMetric->getSampleCount();
	}

	/** @inheritDoc */
	public function getSampleRate(): float {
		return $this->baseMetric->getSampleRate();
	}

	/** @inheritDoc */
	public function setSampleRate( float $sampleRate ) {
		try {
			$this->baseMetric->setSampleRate( $sampleRate );
		} catch ( IllegalOperationException | InvalidArgumentException $ex ) {
			// Log the condition and give the caller something that will absorb calls.
			trigger_error( "Stats: ({$this->getName()}) {$ex->getMessage()}", E_USER_WARNING );
			return new NullMetric;
		}
		return $this;
	}

	/** @inheritDoc */
	public function getLabelKeys(): array {
		$labelKeys = $this->baseMetric->getLabelKeys();
		if ( $this->bucket ) {
			$labelKeys[] = 'le';
		}
		return $labelKeys;
	}

	/** @inheritDoc */
	public function setLabel( string $key, string $value ) {
		if ( strcasecmp( $key, 'le' ) === 0 ) {
			trigger_error( "Stats: ({$this->getName()}) 'le' cannot be used as a label key", E_USER_WARNING );
			return new NullMetric();
		}
		try {
			$this->baseMetric->addLabel( $key, $value );
		} catch ( IllegalOperationException | InvalidArgumentException $ex ) {
			// Log the condition and give the caller something that will absorb calls.
			trigger_error( "Stats: ({$this->getName()}) {$ex->getMessage()}", E_USER_WARNING );
			return new NullMetric;
		}
		return $this;
	}

	/** @inheritDoc */
	public function setLabels( array $labels ) {
		foreach ( $labels as $key => $value ) {
			$metric = $this->setLabel( $key, $value );
			if ( $metric instanceof NullMetric ) {
				return $metric;
			}
		}
		return $this;
	}

	/** @inheritDoc */
	public function copyToStatsdAt( $statsdNamespaces ) {
		try {
			$this->baseMetric->setStatsdNamespaces( $statsdNamespaces );
		} catch ( InvalidArgumentException $ex ) {
			// Log the condition and give the caller something that will absorb calls.
			trigger_error( "Stats: ({$this->getName()}) {$ex->getMessage()}", E_USER_WARNING );
			return new NullMetric;
		}
		return $this;
	}

	/** @inheritDoc */
	public function fresh(): self {
		$this->baseMetric->clearLabels();
		return $this;
	}

	/** @inheritDoc */
	public function isHistogram(): bool {
		return ( $this instanceof CounterMetric && $this->bucket !== null );
	}
}
