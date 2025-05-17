<?php

/**
 * @covers \HistoryBlobUtils
 */
class HistoryBlobUtilsTest extends MediaWikiUnitTestCase {
	public static function provideUnserialize() {
		return [
			'old production HBS' => [
				'O:15:"historyblobstub":2:{s:6:"mOldId";s:7:"5817087";s:5:"mHash";s:32:"d41d8cd98f00b204e9800998ecf8427e";}',
				HistoryBlobStub::class
			],
			'new HBS' => [
				'O:15:"HistoryBlobStub":3:{s:9:"\000*\000mOldId";s:1:"1";s:8:"\000*\000mHash";s:32:"69f6b94cbc2a253d1ca928853bafebfb";s:7:"\000*\000mRef";s:1:"5";}',
				HistoryBlobStub::class
			],
			'old production HBCS' => [
				'O:18:"historyblobcurstub":1:{s:6:"mCurId";i:8;}',
				HistoryBlobCurStub::class,
			],
			'old production CGZ' => [
				'O:27:"concatenatedgziphistoryblob":4:{s:8:"mVersion";i:0;s:11:"mCompressed";b:1;s:6:"mItems";s:114:"M\3141\016\2030\f\000\300\257T\351\ab\'N"\363\201>\240\033bp\354 \245\035\220\240\033\352\337\021L\354\247\023F\3367\016\310\016$\242\2304\210\350\3154y/9\345\242\000\220\233\247\352\206\215\221\330=\327f}m\372\343q|uk\337\376x/\237e\232NpF>j\3049E\v\244T\305\022\005P\250y\256%K\251zEp\217n\215\033\376\a";s:12:"mDefaultHash";s:32:"1a42adae1420ddc600a7678c1117e05b";}',
				ConcatenatedGzipHistoryBlob::class
			],
			'production DHB (PHP 5)' => [
				'O:15:"DiffHistoryBlob":1:{s:11:"mCompressed";s:415:"\245RM\213\023A\020M\306\333\\\\\325{3\b^V\262I\360\203\n\203\304/\024\027\227hnC\b\025\247&\333\320\323=\366\307\262a\022\360Wx\364\254\177C\217\373\217<Y=\031\024\331\243\r\3254\257_\277\256zU\b\023h\035<\204\254\224U\345\262\031\302\024Z\t\2473\a\323\351\004\262A\277n=\035\016\006m\273<\177>\232\ao,\351\303!M\363\\\\,D\236\247P\024\257I\325\301\213E@\345\245\336\222]\255"\374R)\262;\206\211\364\021y\217\273\332\3502B\2320\034\301\027\250%)\306\244\216\264\a\274\322\033\277\025\305[\364\2645V\022,I*%\235\247}O\030-\370\035SH\303\031\343\302T\002\203\2770\326\211\315Nh\254\tD\2440\303u\f\024%\tr\037\255\214\357\235h\214\025\332\324\033\373\207XY`q\n,\321\240\025\306\226\226\004\252\346\0027?\277{\371)POl<\3145]\231\277\272\030\376\021=Jf3\tc6v\374\370\021d\277\314\235\353\350\351p\261^\277;\347J\327\353\224K\376\0206|\336/\245\'\225\027E\024$\026\264\242/s\265\332\277\321%]\3457n\242C]CBr\227\033\266\344\030\316\377[29\260\316\017\216\230\374\204\223\177\302C\361\352\313\375\366\033\347\236\304\311\270\315\221\214x\373\034\017\327\274=\213_\337\303\n\316vN\\\\\242\026\246w\361\322\250-i\307\335\300\272s$IY\345k\247~\340\201\203\254\306&\233u\363xz2>\0313\374\033";}',
				DiffHistoryBlob::class
			],
			'disallowed class' => [
				'O:8:"stdClass":0:{}',
				null
			],
		];
	}

	/**
	 * @dataProvider provideUnserialize
	 */
	public function testUnserialize( $input, $expectedClass ) {
		$obj = HistoryBlobUtils::unserialize( stripcslashes( $input ) );
		if ( $expectedClass === null ) {
			$this->assertNull( $obj );
		} else {
			$this->assertInstanceOf( $expectedClass, $obj );
		}
	}

	public function testUnserializeBadEmbedded() {
		// Creation of dynamic property is deprecated - T352679
		self::markTestSkippedIfPhp( '>=', '8.2' );

		$obj = HistoryBlobUtils::unserialize( "O:15:\"HistoryBlobStub\":4:{s:9:\"\0*\0mOldId\";N;s:8:\"\0*\0mHash\";s:0:\"\";s:7:\"\0*\0mRef\";N;s:3:\"bad\";O:8:\"stdClass\":0:{}}" );
		$this->assertInstanceOf( '__PHP_Incomplete_Class', $obj->bad );
	}
}
