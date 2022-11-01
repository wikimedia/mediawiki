<?php
/**
 * Renderer Interface
 *
 * Renderers run the contents of MetricsCache through a Formatter to
 * produce wire-formatted metrics.
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

namespace Wikimedia\Metrics;

use Wikimedia\Metrics\Formatters\FormatterInterface;

interface RendererInterface {
	/**
	 * @param MetricsCache $cache
	 */
	public function __construct( MetricsCache $cache );

	/**
	 * Initializes formatter from provided format id.
	 *
	 * @param int|null $format
	 * @return RendererInterface
	 */
	public function withFormat( ?int $format ): RendererInterface;

	/**
	 * Sets formatter to FormatterInterface instance.
	 *
	 * @param FormatterInterface $formatter
	 * @return RendererInterface
	 */
	public function withFormatter( FormatterInterface $formatter ): RendererInterface;

	/**
	 * Sets prefix to provided string.
	 *
	 * @param string $prefix
	 * @return RendererInterface
	 */
	public function withPrefix( string $prefix ): RendererInterface;

	/**
	 * Renders metrics and samples through the formatting engines and returns
	 * a string[] of wire-formatted metric samples.
	 *
	 * @return string[]
	 */
	public function render(): array;
}
