<?php
/**
 * Null Metric Implementation
 *
 * When a request from cache yields a type other than what was requested,
 * an instance of this class should be passed to the caller to provide
 * an interface that suppresses method calls against it.
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
 * @since 1.38
 */

declare( strict_types=1 );

namespace Wikimedia\Metrics;

class NullMetric {

	/**
	 * Silently suppress all undefined method calls.
	 *
	 * @param $method_name string
	 * @param $args array
	 * @return null
	 */
	public function __call( string $method_name, array $args ) {
		return null;
	}

}
