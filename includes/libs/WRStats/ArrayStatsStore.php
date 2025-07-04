<?php

namespace Wikimedia\WRStats;

/**
 * In-memory stats store.
 */
class ArrayStatsStore implements StatsStore {
	/**
	 * @var array[] Associative array mapping data keys to arrays where the
	 *   first entry is the value and the second is the TTL.
	 */
	private $data = [];

	/** @inheritDoc */
	public function makeKey( $prefix, $internals, $entity ) {
		$globality = $entity->isGlobal() ? 'global' : 'local';
		return implode( ':',
			array_merge( [ $globality ], $prefix, $internals, $entity->getComponents() )
		);
	}

	/** @inheritDoc */
	public function incr( array $values, $ttl ) {
		foreach ( $values as $key => $value ) {
			if ( !isset( $this->data[$key] ) ) {
				$this->data[$key] = [ 0, $ttl ];
			}
			$this->data[$key][0] += $value;
		}
	}

	public function delete( array $keys ) {
		foreach ( $keys as $key ) {
			unset( $this->data[$key] );
		}
	}

	/** @inheritDoc */
	public function query( array $keys ) {
		$values = [];
		foreach ( $keys as $key ) {
			if ( isset( $this->data[$key] ) ) {
				$values[$key] = $this->data[$key][0];
			}
		}
		return $values;
	}

	/**
	 * @return array
	 */
	public function getData() {
		return $this->data;
	}
}
