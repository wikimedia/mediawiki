<?php

class CommentStoreFactoryTest extends MediaWikiTestCase {

	public function testNewForKey() {
		$factory = new CommentStoreFactory( Language::factory( 'en' ), MIGRATION_NEW );
		$store = $factory->newForKey( 'brocolkey' );
		$fields = $store->getFields();
		$this->assertSame(
			[ 'brocolkey_id' => 'brocolkey_id' ],
			$fields
		);
	}

}
