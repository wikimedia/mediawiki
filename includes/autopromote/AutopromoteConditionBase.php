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
 * @since 1.28
 */

/**
 * Extends the IAutopromoteCondition with some basic functionality
 */
abstract class AutopromoteConditionBase implements IAutopromoteCondition {
	protected $parameter;

	protected function __construct( $parameter ) {
		$this->parameter = $parameter;
	}

	public function getParameter() {
		return $this->parameter;
	}

	/**
	 * Constructs a new criteria
	 * @param array $cond
	 * @return IAutopromoteCondition
	 */
	public static function newFromArray( $cond ) {
		global $wgAutopromoteConditionHandlers;

		$handler = 'AutopromoteLegacyHookCondition';

		if ( !is_array( $cond ) ) {
			$cond = [ $cond ];
		}
		$operator = $cond[0];
		if ( isset( $wgAutopromoteConditionHandlers[$operator] ) ) {
			$handler = $wgAutopromoteConditionHandlers[$operator];
			array_shift( $cond );
		}

		if ( $handler === '' || !is_string( $handler ) || !class_exists( $handler ) ) {
			throw new MWException( $handler . ' unknown' );
		}

		return new $handler( $cond );
	}
}
