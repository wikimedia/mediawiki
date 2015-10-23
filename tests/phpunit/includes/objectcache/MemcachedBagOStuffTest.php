<?php
/**
 * @group BagOStuff
 */
class MemcachedBagOStuffTest extends MediaWikiTestCase {
	/** @var MemcachedBagOStuff */
	private $cache;

	protected function setUp() {
		parent::setUp();
		$this->cache = new MemcachedBagOStuff( array( 'keyspace' => 'test' ) );
	}

	/**
	 * @covers MemcachedBagOStuff::makeKeyInternal
	 */
	public function testKeyNormalization() {
		$this->assertEquals(
			$this->cache->makeKey( 'vanilla' ),
			'test:vanilla'
		);

		$this->assertEquals(
			$this->cache->makeKey( 'punctuation_marks_are_ok', '!@$^&*()' ),
			'test:punctuation_marks_are_ok:!@$^&*()'
		);

		$this->assertEquals(
			$this->cache->makeKey( 'percent_is_escaped', '!@$%^&*()' ),
			'test:percent_is_escaped:!@$%25^&*()'
		);

		$this->assertEquals(
			$this->cache->makeKey( 'but spaces', 'hashes#', "and\nnewlines", 'are_not' ),
			'test:but_spaces:hashes%23:and%0Anewlines:are_not'
		);

		$this->assertEquals(
			$this->cache->makeKey( 'this', 'key', 'contains', '𝕞𝕦𝕝𝕥𝕚𝕓𝕪𝕥𝕖', 'characters' ),
			'test:this:key:contains:%F0%9D%95%9E%F0%9D%95%A6%F0%9D%95%9D%F0%9D%95%A5%F0%9' .
				'D%95%9A%F0%9D%95%93%F0%9D%95%AA%F0%9D%95%A5%F0%9D%95%96:characters'
		);

		$this->assertEquals(
			$this->cache->makeKey( 'this', 'key', 'contains', '𝕥𝕠𝕠 𝕞𝕒𝕟𝕪 𝕞𝕦𝕝𝕥𝕚𝕓𝕪𝕥𝕖 𝕔𝕙𝕒𝕣𝕒𝕔𝕥𝕖𝕣𝕤' ),
			'test:this:key:contains:#c118f92685a635cb843039de50014c9c'
		);

		$this->assertEquals(
			$this->cache->makeKey( '𝕖𝕧𝕖𝕟', '𝕚𝕗', '𝕨𝕖', '𝕄𝔻𝟝', '𝕖𝕒𝕔𝕙',
				'𝕒𝕣𝕘𝕦𝕞𝕖𝕟𝕥', '𝕥𝕙𝕚𝕤', '𝕜𝕖𝕪', '𝕨𝕠𝕦𝕝𝕕', '𝕤𝕥𝕚𝕝𝕝', '𝕓𝕖', '𝕥𝕠𝕠', '𝕝𝕠𝕟𝕘' ),
			'test:##5820ad1d105aa4dc698585c39df73e19'
		);

		$this->assertEquals(
			$this->cache->makeKey( '##5820ad1d105aa4dc698585c39df73e19' ),
			'test:%23%235820ad1d105aa4dc698585c39df73e19'
		);
	}
}
