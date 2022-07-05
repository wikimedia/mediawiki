<?php

namespace Wikimedia\WRStats;

/**
 * Base class for entity keys. An entity key is an array of storage key
 * components which can be used to distinguish stats with the same metric name.
 * The entity key object also carries a global flag which is passed through to
 * the store.
 *
 * @since 1.39
 */
abstract class EntityKey {
	/** @var array */
	private $components;

	/**
	 * @param array $components Array of elements, each convertible to string.
	 */
	public function __construct( array $components = [] ) {
		$this->components = $components;
	}

	/**
	 * @return array
	 */
	public function getComponents() {
		return $this->components;
	}

	/**
	 * @return bool
	 */
	abstract public function isGlobal();
}
