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
 *
 * @file
 */
namespace Wikimedia\ObjectCache;

/**
 * No-op implementation that stores nothing.
 *
 * Used as placeholder or fallback when disabling caching.
 *
 * This can be used in configuration via the CACHE_NONE constant.
 *
 * @ingroup Cache
 */
class EmptyBagOStuff extends MediumSpecificBagOStuff {
	public function __construct( array $params = [] ) {
		parent::__construct( $params );

		$this->attrMap[self::ATTR_DURABILITY] = self::QOS_DURABILITY_NONE;
	}

	/** @inheritDoc */
	protected function doGet( $key, $flags = 0, &$casToken = null ) {
		$casToken = null;

		return false;
	}

	/** @inheritDoc */
	protected function doSet( $key, $value, $exptime = 0, $flags = 0 ) {
		return true;
	}

	/** @inheritDoc */
	protected function doDelete( $key, $flags = 0 ) {
		return true;
	}

	/** @inheritDoc */
	protected function doAdd( $key, $value, $exptime = 0, $flags = 0 ) {
		return true;
	}

	/** @inheritDoc */
	protected function doIncrWithInit( $key, $exptime, $step, $init, $flags ) {
		// faster
		return $init;
	}

	/** @inheritDoc */
	public function merge( $key, callable $callback, $exptime = 0, $attempts = 10, $flags = 0 ) {
		// faster
		return true;
	}
}

/** @deprecated class alias since 1.43 */
class_alias( EmptyBagOStuff::class, 'EmptyBagOStuff' );
