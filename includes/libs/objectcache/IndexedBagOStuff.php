<?php

/**
 * BagOStuff implementation that indexes what it stores based on key parts.
 * This means a list of keys for a single key part can be retrieved.
 *
 * @author Addshore
 *
 * @since 1.27
 */
class IndexedBagOStuff extends BagOStuff {

	/**
	 * @var BagOStuff
	 */
	private $bagOStuff;

	/**
	 * @var array
	 */
	private $index = [];

	/**
	 * @var array
	 */
	private $knownKeys = [];

	/**
	 * @param BagOStuff $bagOStuff
	 * @param array $params
	 */
	public function __construct(
		BagOStuff $bagOStuff,
		array $params = []
	) {
		parent::__construct( $params );
		$this->bagOStuff = $bagOStuff;
	}

	/**
	 * @param string $key
	 * @param integer $flags Bitfield of BagOStuff::READ_* constants [optional]
	 *
	 * @return mixed Returns false on failure and if the item does not exist
	 */
	protected function doGet( $key, $flags = 0 ) {
		$this->bagOStuff->doGet( $key, $flags );
	}

	public function add( $key, $value, $exptime = 0 ) {
		$this->bagOStuff->add( $key, $value, $exptime );
	}

	/**
	 * Set an item
	 *
	 * @param string $key
	 * @param mixed $value
	 * @param int $exptime Either an interval in seconds or a unix timestamp for expiry
	 * @param int $flags Bitfield of BagOStuff::WRITE_* constants
	 *
	 * @return bool Success
	 */
	public function set( $key, $value, $exptime = 0, $flags = 0 ) {
		wfDebugLog( 'addshore', __METHOD__ );
		$this->bagOStuff->set( $key, $value, $exptime, $flags );
		$this->addKeyToIndex( $key );
	}

	/**
	 * @param string $key
	 */
	private function addKeyToIndex( $key ) {
		wfDebugLog( 'addshore', __METHOD__ );
		if ( !array_key_exists( $key, $this->knownKeys ) ) {

			wfDebugLog( 'addshore', "early return" );
			return;
		}

		wfDebugLog( 'addshore', json_encode( $this->knownKeys ) );
		list( $keyspace, $args ) = $this->knownKeys[$key];

		wfDebugLog( 'addshore', json_encode( $keyspace, $args ) );
		foreach ( $args as $argNumber => $arg ) {

			wfDebugLog( 'addshore', $argNumber, $arg, $key );
			$this->index[$keyspace][$argNumber][$arg][$key] = $key;
		}
	}

	/**
	 * Delete an item
	 *
	 * @param string $key
	 *
	 * @return bool True if the item was deleted or not found, false on failure
	 */
	public function delete( $key ) {
		$this->bagOStuff->delete( $key );
		$this->removeKeyFromIndex( $key );
	}

	private function removeKeyFromIndex( $key ) {
		if ( !array_key_exists( $key, $this->knownKeys ) ) {
			return;
		}

		list( $keyspace, $args ) = $this->knownKeys[$key];
		foreach ( $args as $argNumber => $arg ) {
			unset( $this->index[$keyspace][$argNumber][$arg][$key] );
		}
	}

	/**
	 * Construct a cache key.
	 *
	 * @param string $keyspace
	 * @param array $args
	 * @return string
	 */
	public function makeKeyInternal( $keyspace, $args ) {
		$key = $this->bagOStuff->makeKeyInternal( $keyspace, $args );
		$this->knownKeys[$key] = [ $keyspace, $args ];
	}

	/**
	 * @param array $args
	 *
	 * @return array of keys that match all given args
	 */
	public function getInternalKeysForArgs( $keyspace, array $args ) {
		$keyLists = [];
		foreach ( $args as $position => $arg ) {

			wfDebugLog( 'addshore', "loop run " . $position  );
			$keysLists[$position] = $this->getInternalKeysForArg( $keyspace, $position, $arg );

			wfDebugLog( 'addshore', json_encode( $keysLists ) );
		}

		if( count( $keyLists ) > 1 ) {
			return call_user_func_array( 'array_intersect', $keyLists );
		}

		foreach ( $keyLists as $keyList ) {
			return $keyList;
		}

		return [];
	}

	/**
	 * @param string $keyspace
	 * @param int $position
	 * @param mixed $arg
	 *
	 * @return array of keys that match the given arg
	 */
	public function getInternalKeysForArg( $keyspace, $position, $arg ) {
		if ( isset( $this->index[$keyspace][$position][$arg] ) ) {
			wfDebugLog( 'addshore', ':)' );
			return $this->index[$keyspace][$position][$arg];
		}
		wfDebugLog( 'addshore', json_encode( $this->index ) );
		wfDebugLog( 'addshore', ':(' );
		return [];
	}

	public function getGlobalKeysForArgs( array $args ) {
		return $this->getInternalKeysForArgs( 'global', $args );
	}

	public function getGlobalKeyForArg( $position, $arg ) {
		return $this->getInternalKeysForArg( 'global', $position, $arg );
	}

	public function getKeysForArgs( array $args ) {
		return $this->getInternalKeysForArgs( $this->keyspace, $args );
	}

	public function getKeyForArg( $position, $arg ) {
		return $this->getInternalKeysForArg( $this->keyspace, $position, $arg );
	}

}
