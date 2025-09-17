<?php

namespace MediaWiki\Tests\Api\Format;

use MediaWiki\Api\ApiResult;

/**
 * @group API
 * @covers \MediaWiki\Api\ApiFormatPhp
 */
class ApiFormatPhpTest extends ApiFormatTestBase {

	/** @inheritDoc */
	protected $printerName = 'php';

	private static function addFormatVersion( $format, $arr ) {
		foreach ( $arr as &$p ) {
			if ( !isset( $p[2] ) ) {
				$p[2] = [ 'formatversion' => $format ];
			} else {
				$p[2]['formatversion'] = $format;
			}
		}
		return $arr;
	}

	public static function provideGeneralEncoding() {
		return array_merge(
			self::addFormatVersion( 1, [
				// Basic types
				[ [ null ], 'a:1:{i:0;N;}' ],
				[ [ true ], 'a:1:{i:0;s:0:"";}' ],
				[ [ false ], 'a:0:{}' ],
				[ [ true, ApiResult::META_BC_BOOLS => [ 0 ] ],
					'a:1:{i:0;b:1;}' ],
				[ [ false, ApiResult::META_BC_BOOLS => [ 0 ] ],
					'a:1:{i:0;b:0;}' ],
				[ [ 42 ], 'a:1:{i:0;i:42;}' ],
				[ [ 42.5 ], 'a:1:{i:0;d:42.5;}' ],
				[ [ 1e42 ], 'a:1:{i:0;d:1.0E+42;}' ],
				[ [ 'foo' ], 'a:1:{i:0;s:3:"foo";}' ],
				[ [ 'fóo' ], 'a:1:{i:0;s:4:"fóo";}' ],

				// Arrays and objects
				[ [ [] ], 'a:1:{i:0;a:0:{}}' ],
				[ [ [ 1 ] ], 'a:1:{i:0;a:1:{i:0;i:1;}}' ],
				[ [ [ 'x' => 1 ] ], 'a:1:{i:0;a:1:{s:1:"x";i:1;}}' ],
				[ [ [ 2 => 1 ] ], 'a:1:{i:0;a:1:{i:2;i:1;}}' ],
				[ [ (object)[] ], 'a:1:{i:0;a:0:{}}' ],
				[ [ [ 1, ApiResult::META_TYPE => 'assoc' ] ], 'a:1:{i:0;a:1:{i:0;i:1;}}' ],
				[ [ [ 'x' => 1, ApiResult::META_TYPE => 'array' ] ], 'a:1:{i:0;a:1:{i:0;i:1;}}' ],
				[ [ [ 'x' => 1, ApiResult::META_TYPE => 'kvp' ] ], 'a:1:{i:0;a:1:{s:1:"x";i:1;}}' ],
				[ [ [ 'x' => 1, ApiResult::META_TYPE => 'BCkvp', ApiResult::META_KVP_KEY_NAME => 'key' ] ],
					'a:1:{i:0;a:1:{i:0;a:2:{s:3:"key";s:1:"x";s:1:"*";i:1;}}}' ],
				[ [ [ 'x' => 1, ApiResult::META_TYPE => 'BCarray' ] ], 'a:1:{i:0;a:1:{s:1:"x";i:1;}}' ],
				[ [ [ 'a', 'b', ApiResult::META_TYPE => 'BCassoc' ] ], 'a:1:{i:0;a:2:{i:0;s:1:"a";i:1;s:1:"b";}}' ],

				// Content
				[ [ 'content' => 'foo', ApiResult::META_CONTENT => 'content' ],
					'a:1:{s:1:"*";s:3:"foo";}' ],

				// BC Subelements
				[ [ 'foo' => 'foo', ApiResult::META_BC_SUBELEMENTS => [ 'foo' ] ],
					'a:1:{s:3:"foo";a:1:{s:1:"*";s:3:"foo";}}' ],
			] ),
			self::addFormatVersion( 2, [
				// Basic types
				[ [ null ], 'a:1:{i:0;N;}' ],
				[ [ true ], 'a:1:{i:0;b:1;}' ],
				[ [ false ], 'a:1:{i:0;b:0;}' ],
				[ [ true, ApiResult::META_BC_BOOLS => [ 0 ] ],
					'a:1:{i:0;b:1;}' ],
				[ [ false, ApiResult::META_BC_BOOLS => [ 0 ] ],
					'a:1:{i:0;b:0;}' ],
				[ [ 42 ], 'a:1:{i:0;i:42;}' ],
				[ [ 42.5 ], 'a:1:{i:0;d:42.5;}' ],
				[ [ 1e42 ], 'a:1:{i:0;d:1.0E+42;}' ],
				[ [ 'foo' ], 'a:1:{i:0;s:3:"foo";}' ],
				[ [ 'fóo' ], 'a:1:{i:0;s:4:"fóo";}' ],

				// Arrays and objects
				[ [ [] ], 'a:1:{i:0;a:0:{}}' ],
				[ [ [ 1 ] ], 'a:1:{i:0;a:1:{i:0;i:1;}}' ],
				[ [ [ 'x' => 1 ] ], 'a:1:{i:0;a:1:{s:1:"x";i:1;}}' ],
				[ [ [ 2 => 1 ] ], 'a:1:{i:0;a:1:{i:2;i:1;}}' ],
				[ [ (object)[] ], 'a:1:{i:0;a:0:{}}' ],
				[ [ [ 1, ApiResult::META_TYPE => 'assoc' ] ], 'a:1:{i:0;a:1:{i:0;i:1;}}' ],
				[ [ [ 'x' => 1, ApiResult::META_TYPE => 'array' ] ], 'a:1:{i:0;a:1:{i:0;i:1;}}' ],
				[ [ [ 'x' => 1, ApiResult::META_TYPE => 'kvp' ] ], 'a:1:{i:0;a:1:{s:1:"x";i:1;}}' ],
				[ [ [ 'x' => 1, ApiResult::META_TYPE => 'BCkvp', ApiResult::META_KVP_KEY_NAME => 'key' ] ],
					'a:1:{i:0;a:1:{s:1:"x";i:1;}}' ],
				[ [ [ 'x' => 1, ApiResult::META_TYPE => 'BCarray' ] ], 'a:1:{i:0;a:1:{i:0;i:1;}}' ],
				[ [ [ 'a', 'b', ApiResult::META_TYPE => 'BCassoc' ] ], 'a:1:{i:0;a:2:{i:0;s:1:"a";i:1;s:1:"b";}}' ],

				// Content
				[ [ 'content' => 'foo', ApiResult::META_CONTENT => 'content' ],
					'a:1:{s:7:"content";s:3:"foo";}' ],

				// BC Subelements
				[ [ 'foo' => 'foo', ApiResult::META_BC_SUBELEMENTS => [ 'foo' ] ],
					'a:1:{s:3:"foo";s:3:"foo";}' ],
			] )
		);
	}

}
