<?php

namespace MediaWiki\Tests\Api\Format;

use MediaWiki\Api\ApiResult;

/**
 * @group API
 * @covers \MediaWiki\Api\ApiFormatNone
 */
class ApiFormatNoneTest extends ApiFormatTestBase {

	/** @inheritDoc */
	protected $printerName = 'none';

	public static function provideGeneralEncoding() {
		return [
			// Basic types
			[ [ null ], '' ],
			[ [ true ], '' ],
			[ [ false ], '' ],
			[ [ 42 ], '' ],
			[ [ 42.5 ], '' ],
			[ [ 1e42 ], '' ],
			[ [ 'foo' ], '' ],
			[ [ 'fóo' ], '' ],

			// Arrays and objects
			[ [ [] ], '' ],
			[ [ [ 1 ] ], '' ],
			[ [ [ 'x' => 1 ] ], '' ],
			[ [ [ 2 => 1 ] ], '' ],
			[ [ (object)[] ], '' ],
			[ [ [ 1, ApiResult::META_TYPE => 'assoc' ] ], '' ],
			[ [ [ 'x' => 1, ApiResult::META_TYPE => 'array' ] ], '' ],
			[ [ [ 'x' => 1, ApiResult::META_TYPE => 'kvp' ] ], '' ],
			[
				[ [
					'x' => 1,
					ApiResult::META_TYPE => 'BCkvp',
					ApiResult::META_KVP_KEY_NAME => 'key'
				] ],
				''
			],
			[ [ [ 'x' => 1, ApiResult::META_TYPE => 'BCarray' ] ], '' ],
			[ [ [ 'a', 'b', ApiResult::META_TYPE => 'BCassoc' ] ], '' ],

			// Content
			[ [ '*' => 'foo' ], '' ],

			// BC Subelements
			[ [ 'foo' => 'foo', ApiResult::META_BC_SUBELEMENTS => [ 'foo' ] ], '' ],
		];
	}

}
