<?php
/**
 * Metrics Renderer Implementation
 *
 * Runs the contents of MetricsCache through a Formatter to produce
 * wire-formatted metrics.
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

use InvalidArgumentException;
use Wikimedia\Metrics\Exceptions\InvalidConfigurationException;
use Wikimedia\Metrics\Formatters\FormatterInterface;
use Wikimedia\Metrics\Metrics\NullMetric;

class MetricsRenderer implements RendererInterface {

	/** @var FormatterInterface|null */
	private ?FormatterInterface $formatter;

	/** @var string|null */
	private ?string $prefix;

	/** @var MetricsCache */
	private MetricsCache $cache;

	/** @inheritDoc */
	public function __construct( MetricsCache $cache ) {
		$this->cache = $cache;
	}

	/** @inheritDoc */
	public function withFormat( ?int $format ): RendererInterface {
		if ( $format !== null ) {
			$this->formatter = OutputFormats::getNewFormatter( $format );
		}
		return $this;
	}

	/** @inheritDoc */
	public function withFormatter( FormatterInterface $formatter ): RendererInterface {
		$this->formatter = $formatter;
		return $this;
	}

	/** @inheritDoc */
	public function withPrefix( string $prefix ): RendererInterface {
		if ( $prefix === '' ) {
			throw new InvalidArgumentException( 'MetricsRenderer: Prefix cannot be empty.' );
		}
		$this->prefix = MetricUtils::normalizeString( $prefix );
		return $this;
	}

	/** @inheritDoc */
	public function render(): array {
		$this->validateConfig();
		$output = [];
		// bypass the work if no formatter
		if ( $this->formatter === null ) {
			return $output;
		}
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
	 * Check for correctly configured instance.
	 *
	 * @return void
	 * @throws InvalidConfigurationException
	 */
	private function validateConfig() {
		if ( $this->prefix === null ) {
			throw new InvalidConfigurationException( 'MetricsRenderer: Prefix cannot be null.' );
		}
		if ( $this->formatter === null ) {
			throw new InvalidConfigurationException( 'MetricsRenderer: Formatter not provided.' );
		}
	}

}
