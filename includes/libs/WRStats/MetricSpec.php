<?php

namespace Wikimedia\WRStats;

/**
 * Class representation of normalized metric specifications
 *
 * @internal
 */
class MetricSpec {
	/** The default (value axis) resolution */
	public const DEFAULT_RESOLUTION = 1;

	/** @var string */
	public $type;
	/** @var float|int */
	public $resolution;
	/** @var SequenceSpec[] Sequences in ascending order of expiry */
	public $sequences;

	/**
	 * @param array $spec
	 */
	public function __construct( array $spec ) {
		$this->type = $spec['type'] ?? 'counter';
		$this->resolution = $spec['resolution'] ?? self::DEFAULT_RESOLUTION;
		foreach ( [ 'timeStep', 'expiry' ] as $var ) {
			if ( isset( $spec[$var] ) ) {
				throw new WRStatsError( __METHOD__ .
					": $var must be specified in the sequences array" );
			}
		}
		$seqArrays = $spec['sequences'] ?? [];
		if ( !count( $seqArrays ) ) {
			$seqArrays = [ [] ];
		}
		$sequences = [];
		foreach ( $seqArrays as $i => $seqArray ) {
			if ( !is_array( $seqArray ) ) {
				throw new WRStatsError( __METHOD__ .
					': sequences is supposed to be an array of arrays' );
			}
			$seqSpec = new SequenceSpec( $seqArray );
			while ( isset( $sequences[$seqSpec->name] ) ) {
				$seqSpec->name .= "s$i";
			}
			$sequences[$seqSpec->name] = $seqSpec;
		}
		uasort( $sequences, static function ( SequenceSpec $a, SequenceSpec $b ) {
			return $a->hardExpiry <=> $b->hardExpiry;
		} );
		$this->sequences = $sequences;
	}
}
