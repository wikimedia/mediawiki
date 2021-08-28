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

namespace MediaWiki;

use MediaWiki\Config\ServiceOptions;

/**
 * Helper to check if certain features should be temporarily disabled.
 *
 * @author Taavi Väänänen <hi@taavi.wtf>
 * @since 1.44
 */
class FeatureShutdown {
	/** @internal Only public for service wiring use. */
	public const CONSTRUCTOR_OPTIONS = [
		'FeatureShutdown',
	];

	/** @var array */
	private $shutdowns;

	/**
	 * @param ServiceOptions $options
	 */
	public function __construct( ServiceOptions $options ) {
		$options->assertRequiredOptions( self::CONSTRUCTOR_OPTIONS );
		$this->shutdowns = $options->get( 'FeatureShutdown' );
	}

	/**
	 * @param string $featureName
	 * @return array|null
	 */
	public function findForFeature( string $featureName ): ?array {
		if ( !isset( $this->shutdowns[$featureName] ) ) {
			return null;
		}

		$time = time();
		foreach ( $this->shutdowns[$featureName] as $shutdown ) {
			if ( strtotime( $shutdown['start'] ) > $time || strtotime( $shutdown['end'] ) < $time ) {
				continue;
			}

			if ( isset( $shutdown['percentage'] ) && rand( 0, 99 ) > $shutdown['percentage'] ) {
				continue;
			}

			return $shutdown;
		}

		return null;
	}
}
