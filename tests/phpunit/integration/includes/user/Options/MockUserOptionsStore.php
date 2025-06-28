<?php

namespace MediaWiki\Tests\User\Options;

use MediaWiki\User\Options\UserOptionsStore;
use MediaWiki\User\UserIdentity;

class MockUserOptionsStore implements UserOptionsStore {
	/** @var array */
	public $data = [];

	public function __construct( array $initialData = [] ) {
		$this->data = $initialData;
	}

	public function fetch( UserIdentity $user, int $recency ) {
		return $this->data[$user->getName()] ?? [];
	}

	public function fetchBatchForUserNames( array $keys, array $userNames ) {
		$result = [];
		foreach ( $keys as $key ) {
			foreach ( $userNames as $name ) {
				if ( isset( $this->data[$name][$key] ) ) {
					$result[$key][$name] = $this->data[$name][$key];
				}
			}
		}
		return $result;
	}

	public function store( UserIdentity $user, array $updates ) {
		$userName = $user->getName();
		if ( !isset( $this->data[$userName] ) ) {
			$this->data[$userName] = [];
		}
		$userData =& $this->data[$userName];
		$changed = false;
		foreach ( $updates as $key => $value ) {
			if ( $value === null ) {
				$changed = $changed || isset( $userData[$key] );
				unset( $userData[$key] );
			} else {
				$changed = !isset( $userData[$key] ) || $userData[$key] !== $value;
				$userData[$key] = $value;
			}
		}
		return $changed;
	}

	/**
	 * @return array
	 */
	public function getData() {
		return $this->data;
	}
}
