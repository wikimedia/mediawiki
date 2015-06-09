<?php
class LCStoreStaticArray implements LCStore {
	private $currentLang = null;
	private $data = array();
	private $fname = null;
	private $dir;


	public function __construct( $conf ) {
		global $wgCacheDirectory;
		$this->dir = $wgCacheDirectory;
	}

	public function startWrite( $code ) {
		$this->currentLang = $code;
		$this->fname = $this->dir . '/' . $code . '.l10n.php';
		$this->data[$code] = array();
		if ( file_exists( $this->fname ) ) {
			$this->data[$code] = require $this->fname;
		}
	}

	public function set( $key, $value ) {
		$this->data[$this->currentLang][$key] = self::encode( $value );
	}

	private static function encode( $value ) {
		if ( is_scalar( $value ) || $value === null ) {
			// [V]alue
			return array( 'v', $value );
		}
		if ( is_object( $value ) ) {
			// [S]erialized
			return array( 's', serialize( $value ) );
		}
		if ( is_array( $value ) ) {
			// [A]rray
			return array( 'a', array_map(
				function( $v ) {
					return LCStoreStaticArray::encode($v);
				}, $value )
			);
		}

		die("Can't encode '".var_export($value, true)."'");
	}

	/**
	 * @param array $encoded
	 * @return array|mixed
	 */
	private static function decode( array $encoded ) {
		$type = $encoded[0];
		$data = $encoded[1];

		if ( $type === 'v' ) {
			return $data;
		}
		if ( $type === 's' ) {
			return unserialize( $data );
		}
		if ( $type === 'a' ) {
			return array_map(
				function( $v ) {
					return LCStoreStaticArray::decode($v);
				},
				$data
			);
		}

		throw new RuntimeException( 'Unable to decode:' . var_export( $encoded, true ) );
	}

	public function finishWrite() {
		file_put_contents(
			$this->fname,
			'<?php return ' . var_export( $this->data[$this->currentLang], true ) . ';'
		);
		$this->currentLang = null;
		$this->fname = null;
	}

	public function get( $code, $key ) {
		if ( !array_key_exists( $code, $this->data ) ) {
			$fname = $this->dir . '/' . $code . '.l10n.php';
			if ( !file_exists( $fname ) ) {
				return null;
			}
			$this->data[$code] = require $fname;
		}
		$data = $this->data[$code];
		if ( array_key_exists( $key, $data ) ) {
			return self::decode( $data[$key] );
		}
		return null;
	}
}
