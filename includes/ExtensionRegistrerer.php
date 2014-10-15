<?php

class ExtensionRegistrerer {

	/**
	 * @var BagOStuff
	 */
	protected $cache;
	/**
	 * @var ConfigFactory
	 */
	protected $configFactory;

	/**
	 * @param ConfigFactory $configFactory
	 */
	public function __construct( ConfigFactory $configFactory ) {
		$this->configFactory = $configFactory;
		// Setup APC caching if possible, otherwise just skip it
		try {
			$this->cache = wfGetCache( CACHE_ACCEL );
		} catch ( MWException $e ) {
			$this->cache = new EmptyBagOStuff();
		}
	}

	/**
	 * @param string $filename
	 * @param int $mtime
	 * @return array
	 */
	public function loadExtensionInfoFromFile( $filename, $mtime ) {
		$key = wfMemcKey( 'extreg', md5( $filename ) );
		$cached = $this->cache->get( $key );
		if ( isset( $cached['mtime'] ) && $cached['mtime'] === $mtime ) {
			return $cached['info'];
		}

		$contents = file_get_contents( $filename );
		$json = json_decode( $contents );
		if ( is_object( $json ) ) {
			$json = wfObjectToArray( $json );
			$this->cache->set( $key, array( 'mtime' => $mtime, 'info' => $json ) );
		} else {
			// Don't throw an error here, but don't cache it either.
			$json = array();
		}

		return $json;
	}

	/**
	 * @param string $name
	 * @param string|null $path
	 * @return array
	 */
	private function getPathAndMTime( $name, $path = null ) {
		if ( !$path ) {
			global $IP;
			$path = "$IP/extensions";
		}
		$fullpath = $path . DIRECTORY_SEPARATOR . $name . DIRECTORY_SEPARATOR . "extension.json";
		$mtime = filemtime( $fullpath );
		return array( $fullpath, $mtime );
	}

	public function loadExtension( $name, $path = null ) {
		list( $fullpath, $mtime ) = $this->getPathAndMTime( $name, $path );
		$info = $this->loadExtensionInfoFromFile( $fullpath, $mtime );
		//var_dump("Loading $name" );
		$this->processInfo( $name, $info );

	}

	protected function processInfo( $name, $info ) {
		$this->setCredits( $info );
		foreach ( $info as $key => $val ) {
			if ( $key === 'config' ) {
				$this->registerConfig( $name, $val );
			} else {
				$this->setToGlobal( $key, $val );
			}
		}
	}

	protected function setCredits( &$info ) {
		$attrs = array(
			'name',
			'type',
			'authors',
			'path',
			'version',
			'url',
			'description',
			'descriptionmsg',
			'license-name',
		);
		$credits = array();
		foreach ( $attrs as $attr ) {
			if ( isset( $info[$attr] ) ) {
				$credits[$attr] = $info[$attr];
				unset( $info[$attr] );
			}
		}

		$GLOBALS['wgExtensionCredits'][] = $credits;
	}

	protected function setToGlobal( $global, $value ) {
		$GLOBALS["wg$global"] = array_merge_recursive( $GLOBALS["wg$global"], $value );
	}

	/**
	 * @param string $name
	 * @param array $config
	 */
	protected function registerConfig( $name, $config ) {
		$this->configFactory->register( $name . '-default', function() use ( $config ) {
			return new HashConfig( $config );
		} );
		$this->configFactory->register( $name, function( $name, ConfigFactory $factory ) {
			return new MultiConfig( array(
				GlobalVarConfig::newInstance(),
				$factory->makeConfig( $name . '-default' )
			) );
		} );
	}
}
