<?php

namespace MediaWiki\User\TempUser;

use Wikimedia\ArrayUtils\ArrayUtils;

/**
 * Since "base" is an overused term in class names and mostly means something
 * else, we will call the base of a numeric representation a radix.
 *
 * This class converts integer serial numbers to strings using an arbitrary
 * base between 2 and 36. It can skip certain IDs deemed to be "bad", e.g.
 * because they spell offensive words.
 *
 * @since 1.39
 */
class FilteredRadixSerialMapping implements SerialMapping {
	/** @var int */
	private $radix;

	/** @var int[] */
	private $badIndexes;

	/** @var bool */
	private $uppercase;

	/**
	 * @param array $config See MainConfigSchema::AutoCreateTempUser
	 */
	public function __construct( $config ) {
		$this->radix = $config['radix'] ?? 10;
		$this->badIndexes = $config['badIndexes'] ?? [];
		$this->uppercase = $config['uppercase'] ?? false;
	}

	public function getSerialIdForIndex( int $index ): string {
		$index = $this->adjustID( $index );
		return \Wikimedia\base_convert( (string)$index, 10, $this->radix, 1, !$this->uppercase );
	}

	/**
	 * Add the number of "bad" IDs less than or equal to the given ID to the
	 * given ID, thus mapping the set of all integers to the "good" set.
	 *
	 * @param int $id
	 * @return int
	 */
	private function adjustID( int $id ): int {
		$pos = ArrayUtils::findLowerBound(
			function ( $i ) {
				return $this->badIndexes[$i];
			},
			count( $this->badIndexes ),
			static function ( $a, $b ) {
				return $a <=> $b;
			},
			$id
		);
		return $pos === false ? $id : $id + $pos + 1;
	}
}
