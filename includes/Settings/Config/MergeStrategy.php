<?php

namespace MediaWiki\Settings\Config;

use MediaWiki\Settings\SettingsBuilderException;
use function array_key_exists;

class MergeStrategy {

	public const ARRAY_MERGE_RECURSIVE = 'array_merge_recursive';
	public const ARRAY_REPLACE_RECURSIVE = 'array_replace_recursive';
	public const ARRAY_PLUS_2D = 'array_plus_2d';
	public const ARRAY_PLUS = 'array_plus';
	public const ARRAY_MERGE = 'array_merge';
	public const REPLACE = 'replace';

	/** @var string */
	private $name;

	/** @var bool */
	private $reversed;

	/** @var MergeStrategy[] */
	private static $strategies = [];

	/** @var MergeStrategy[] */
	private static $reversedStrategies = [];

	/**
	 * @param string $name
	 * @return self
	 */
	public static function newFromName( string $name ): self {
		if ( !array_key_exists( $name, self::$strategies ) ) {
			self::$strategies[$name] = new MergeStrategy( $name );
		}
		return self::$strategies[$name];
	}

	/**
	 * @param string $name
	 * @param bool $reversed
	 */
	private function __construct( string $name, bool $reversed = false ) {
		$this->name = $name;
		$this->reversed = $reversed;
	}

	public function getName(): string {
		return $this->name;
	}

	/**
	 * Merge $source into $destination.
	 *
	 * @note For all merge strategies except self::ARRAY_MERGE_RECURSIVE,
	 * for the values that have the same key, the value from $source will
	 * override the value in the $destination.
	 *
	 * @param array $destination
	 * @param array $source
	 * @return array
	 */
	public function merge( array $destination, array $source ): array {
		if ( $this->reversed ) {
			[ $destination, $source ] = [ $source, $destination ];
		}

		switch ( $this->name ) {
			case self::REPLACE:
				return $source;
			case self::ARRAY_MERGE_RECURSIVE:
				return array_merge_recursive( $destination, $source );
			case self::ARRAY_REPLACE_RECURSIVE:
				return array_replace_recursive( $destination, $source );
			case self::ARRAY_PLUS_2D:
				return wfArrayPlus2d( $source, $destination );
			case self::ARRAY_PLUS:
				return $source + $destination;
			case self::ARRAY_MERGE:
				return array_merge( $destination, $source );
			default:
				throw new SettingsBuilderException(
					'Unknown merge strategy {name}',
					[ 'name' => $this->name ]
				);
		}
	}

	/**
	 * Create a reversed merge strategy, which will merge $destination into $source
	 * instead of $source into $destination.
	 *
	 * @see self::merge
	 * @return MergeStrategy
	 */
	public function reverse(): self {
		if ( !array_key_exists( $this->name, self::$reversedStrategies ) ) {
			self::$reversedStrategies[$this->name] = new self( $this->name, !$this->reversed );
		}
		return self::$reversedStrategies[$this->name];
	}
}
