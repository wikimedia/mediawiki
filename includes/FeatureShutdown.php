<?php
/**
 * @license GPL-2.0-or-later
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
