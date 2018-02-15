<?php
/**
 * @defgroup ExternalStorage ExternalStorage
 */

/**
 * @ingroup ExternalStorage
 */
class ExternalStoreFactory {

	/**
	 * @var array
	 */
	private $externalStores;

	/**
	 * @param array $externalStores See $wgExternalStores
	 */
	public function __construct( array $externalStores ) {
		$this->externalStores = array_map( 'strtolower', $externalStores );
	}

	/**
	 * Get an external store object of the given type, with the given parameters
	 *
	 * @param string $proto Type of external storage, should be a value in $wgExternalStores
	 * @param array $params Associative array of ExternalStoreMedium parameters
	 * @return ExternalStoreMedium|bool The store class or false on error
	 */
	public function getStoreObject( $proto, array $params = [] ) {
		if ( !$this->externalStores || !in_array( strtolower( $proto ), $this->externalStores ) ) {
			// Protocol not enabled
			return false;
		}

		$class = 'ExternalStore' . ucfirst( $proto );

		// Any custom modules should be added to $wgAutoLoadClasses for on-demand loading
		return class_exists( $class ) ? new $class( $params ) : false;
	}

}
