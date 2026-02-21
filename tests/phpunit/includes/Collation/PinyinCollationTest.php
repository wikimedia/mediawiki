<?php

/**
 * @covers \MediaWiki\Collation\PinyinCollation
 */
class PinyinCollationTest extends MediaWikiLangTestCase {

	/**
	 * @dataProvider providePinyinCollationOrders
	 */
	public function testPinyinCollationOrders( string $collation, string $before, string $after ) {
		$col = $this->getServiceContainer()->getCollationFactory()
			->makeCollation( $collation );

		$this->assertLessThan(
			$col->getSortKey( $after ),
			$col->getSortKey( $before ),
			"'{$before}' should be sorted before '{$after}' under $collation collation"
		);
	}

	public static function providePinyinCollationOrders() {
		$defaultOrder = [
			'', ' ', '*',
			'阿', 'A', '八', 'B', '嚓', 'C', '搭', 'D', '蛾', 'E', '发', 'F', '噶', 'G',
			'哈', 'H', 'I', '击', 'J', '喀', 'K', '垃', 'L', '妈', 'M', '拿', 'N',
			'哦', 'O', '啪', 'P', '期', 'Q', '然', 'R', '撒', 'S', '塌', 'T',
			'U', 'V', '挖', 'W', '昔', 'X', '压', 'Y', '匝', 'Z',
			'あ', 'ア',
		];

		for ( $i = 0; $i < count( $defaultOrder ) - 1; $i++ ) {
			yield [ 'pinyin-zh', $defaultOrder[$i], $defaultOrder[$i + 1] ];
		}

		$reordered = [
			'', ' ', '*',
			'A', '阿', 'B', '八', 'C', '嚓', 'D', '搭', 'E', '蛾', 'F', '发', 'G', '噶',
			'H', '哈', 'I', 'J', '击', 'K', '喀', 'L', '垃', 'M', '妈', 'N', '拿',
			'O', '哦', 'P', '啪', 'Q', '期', 'R', '然', 'S', '撒', 'T', '塌',
			'U', 'V', 'W', '挖', 'X', '昔', 'Y', '压', 'Z', '匝',
			'あ', 'ア',
		];

		for ( $i = 0; $i < count( $reordered ) - 1; $i++ ) {
			yield [ 'pinyin-zh@colReorder=latn-hani', $reordered[$i], $reordered[$i + 1] ];
			yield [ 'pinyin-zh-u-kr-latn-hani', $reordered[$i], $reordered[$i + 1] ];
		}
	}
}
