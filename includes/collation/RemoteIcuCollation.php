<?php

use MediaWiki\Shell\ShellboxClientFactory;
use Shellbox\RPC\RpcClient;

/**
 * An ICU collation that uses a remote server to compute sort keys. This can be
 * used in conjunction with $wgTempCategoryCollations to migrate to a different
 * version of ICU.
 */
class RemoteIcuCollation extends Collation {
	private RpcClient $rpcClient;
	private string $locale;

	public function __construct( ShellboxClientFactory $shellboxClientFactory, string $locale ) {
		$this->rpcClient = $shellboxClientFactory->getRpcClient(
			[ 'service' => 'icu-collation' ] );
		$this->locale = $locale;
	}

	/** @inheritDoc */
	public function getSortKey( $string ) {
		return $this->getSortKeys( [ $string ] )[0];
	}

	/**
	 * Encode an array of binary strings as a string
	 *
	 * @param string[] $strings
	 * @return string
	 */
	private static function encode( $strings ) {
		$ret = '';
		foreach ( $strings as $s ) {
			$ret .= sprintf( "%08x", strlen( $s ) ) . $s;
		}
		return $ret;
	}

	/**
	 * Decode the value returned by encode()
	 *
	 * @param string $blob
	 * @return string[]
	 */
	private static function decode( $blob ) {
		$p = 0;
		$ret = [];
		while ( $p < strlen( $blob ) ) {
			$len = intval( substr( $blob, $p, 8 ), 16 );
			$p += 8;
			$ret[] = substr( $blob, $p, $len );
			$p += $len;
		}
		return $ret;
	}

	/** @inheritDoc */
	public function getSortKeys( $strings ) {
		if ( !count( $strings ) ) {
			return [];
		}
		$blob = $this->rpcClient->call(
			'icu-collation',
			[ self::class, 'doGetSortKeys' ],
			[
				$this->locale,
				self::encode( array_values( $strings ) )
			],
			[
				'classes' => [ parent::class, self::class ],
				'binary' => true
			]
		);
		return array_combine(
			array_keys( $strings ),
			self::decode( $blob )
		);
	}

	/** @inheritDoc */
	public function getFirstLetter( $string ) {
		// @phan-suppress-previous-line PhanPluginNeverReturnMethod
		throw new RuntimeException( __METHOD__ . ': not implemented' );
	}

	/**
	 * The remote entry point. Get sort keys for an encoded list of inputs.
	 *
	 * @param string $locale The ICU locale
	 * @param string $blob The input array encoded with encode()
	 * @return string The encoded result
	 */
	public static function doGetSortKeys( $locale, $blob ) {
		$mainCollator = Collator::create( $locale );
		if ( !$mainCollator ) {
			throw new RuntimeException( "Invalid ICU locale specified for collation: $locale" );
		}

		// If the special suffix for numeric collation is present, turn on numeric collation.
		if ( str_ends_with( $locale, '-u-kn' ) ) {
			$mainCollator->setAttribute( Collator::NUMERIC_COLLATION, Collator::ON );
		}
		$ret = [];
		foreach ( self::decode( $blob ) as $string ) {
			$ret[] = $mainCollator->getSortKey( $string );
		}
		return self::encode( $ret );
	}
}
