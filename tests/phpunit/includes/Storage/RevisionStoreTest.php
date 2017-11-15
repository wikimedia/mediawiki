<?php

namespace MediaWiki\Tests\Storage;

use MediaWiki\Storage\RevisionStore;
use MediaWiki\Storage\SqlBlobStore;
use MediaWikiTestCase;
use WANObjectCache;
use Wikimedia\Rdbms\LoadBalancer;

class RevisionStoreTest extends MediaWikiTestCase {

	/**
	 * @param LoadBalancer $loadBalancer
	 * @param SqlBlobStore $blobStore
	 * @param WANObjectCache $WANObjectCache
	 *
	 * @return RevisionStore
	 */
	private function getRevisionStore(
		$loadBalancer = null,
		$blobStore = null,
		$WANObjectCache = null
	) {
		return new RevisionStore(
			$loadBalancer ? $loadBalancer : $this->getMockLoadBalancer(),
			$blobStore ? $blobStore : $this->getMockSqlBlobStore(),
			$WANObjectCache ? $WANObjectCache : $this->getHashWANObjectCache()
		);
	}

	/**
	 * @return \PHPUnit_Framework_MockObject_MockObject|LoadBalancer
	 */
	private function getMockLoadBalancer() {
		return $this->getMockBuilder( LoadBalancer::class )
			->disableOriginalConstructor()->getMock();
	}

	/**
	 * @return \PHPUnit_Framework_MockObject_MockObject|SqlBlobStore
	 */
	private function getMockSqlBlobStore() {
		return $this->getMockBuilder( SqlBlobStore::class )
			->disableOriginalConstructor()->getMock();
	}

	private function getHashWANObjectCache() {
		return new WANObjectCache( [ 'cache' => new \HashBagOStuff() ] );
	}

	/**
	 * @covers \MediaWiki\Storage\RevisionStore::getContentHandlerUseDB
	 * @covers \MediaWiki\Storage\RevisionStore::setContentHandlerUseDB
	 */
	public function testGetSetContentHandlerDb() {
		$store = $this->getRevisionStore();
		$this->assertTrue( $store->getContentHandlerUseDB() );
		$store->setContentHandlerUseDB( false );
		$this->assertFalse( $store->getContentHandlerUseDB() );
		$store->setContentHandlerUseDB( true );
		$this->assertTrue( $store->getContentHandlerUseDB() );
	}

	private function getDefaultQueryFields() {
		return [
			'rev_id',
			'rev_page',
			'rev_text_id',
			'rev_timestamp',
			'rev_user_text',
			'rev_user',
			'rev_minor_edit',
			'rev_deleted',
			'rev_len',
			'rev_parent_id',
			'rev_sha1',
		];
	}

	private function getCommentQueryFields() {
		return [
			'rev_comment_text' => 'rev_comment',
			'rev_comment_data' => 'NULL',
			'rev_comment_cid' => 'NULL',
		];
	}

	private function getContentHandlerQueryFields() {
		return [
			'rev_content_format',
			'rev_content_model',
		];
	}

	public function provideGetQueryInfo() {
		yield [
			true,
			[],
			[
				'tables' => [ 'revision' ],
				'fields' => array_merge(
					$this->getDefaultQueryFields(),
					$this->getCommentQueryFields(),
					$this->getContentHandlerQueryFields()
				),
				'joins' => [],
			]
		];
		yield [
			false,
			[],
			[
				'tables' => [ 'revision' ],
				'fields' => array_merge(
					$this->getDefaultQueryFields(),
					$this->getCommentQueryFields()
				),
				'joins' => [],
			]
		];
		yield [
			false,
			[ 'page' ],
			[
				'tables' => [ 'revision', 'page' ],
				'fields' => array_merge(
					$this->getDefaultQueryFields(),
					$this->getCommentQueryFields(),
					[
						'page_namespace',
						'page_title',
						'page_id',
						'page_latest',
						'page_is_redirect',
						'page_len',
					]
				),
				'joins' => [
					'page' => [ 'INNER JOIN', [ 'page_id = rev_page' ] ],
				],
			]
		];
		yield [
			false,
			[ 'user' ],
			[
				'tables' => [ 'revision', 'user' ],
				'fields' => array_merge(
					$this->getDefaultQueryFields(),
					$this->getCommentQueryFields(),
					[
						'user_name',
					]
				),
				'joins' => [
					'user' => [ 'LEFT JOIN', [ 'rev_user != 0', 'user_id = rev_user' ] ],
				],
			]
		];
		yield [
			false,
			[ 'text' ],
			[
				'tables' => [ 'revision', 'text' ],
				'fields' => array_merge(
					$this->getDefaultQueryFields(),
					$this->getCommentQueryFields(),
					[
						'old_text',
						'old_flags',
					]
				),
				'joins' => [
					'text' => [ 'INNER JOIN', [ 'rev_text_id=old_id' ] ],
				],
			]
		];
		yield [
			true,
			[ 'page', 'user', 'text' ],
			[
				'tables' => [ 'revision', 'page', 'user', 'text' ],
				'fields' => array_merge(
					$this->getDefaultQueryFields(),
					$this->getCommentQueryFields(),
					$this->getContentHandlerQueryFields(),
					[
						'page_namespace',
						'page_title',
						'page_id',
						'page_latest',
						'page_is_redirect',
						'page_len',
						'user_name',
						'old_text',
						'old_flags',
					]
				),
				'joins' => [
					'page' => [ 'INNER JOIN', [ 'page_id = rev_page' ] ],
					'user' => [ 'LEFT JOIN', [ 'rev_user != 0', 'user_id = rev_user' ] ],
					'text' => [ 'INNER JOIN', [ 'rev_text_id=old_id' ] ],
				],
			]
		];
	}

	/**
	 * @dataProvider provideGetQueryInfo
	 * @covers \MediaWiki\Storage\RevisionStore::getQueryInfo
	 */
	public function testGetQueryInfo( $contentHandlerUseDb, $options, $expected ) {
		$store = $this->getRevisionStore();
		$store->setContentHandlerUseDB( $contentHandlerUseDb );
		$this->assertEquals( $expected, $store->getQueryInfo( $options ) );
	}

	private function getDefaultArchiveFields() {
		return [
			'ar_id',
			'ar_page_id',
			'ar_namespace',
			'ar_title',
			'ar_rev_id',
			'ar_text',
			'ar_text_id',
			'ar_timestamp',
			'ar_user_text',
			'ar_user',
			'ar_minor_edit',
			'ar_deleted',
			'ar_len',
			'ar_parent_id',
			'ar_sha1',
		];
	}

	/**
	 * @covers \MediaWiki\Storage\RevisionStore::getArchiveQueryInfo
	 */
	public function testGetArchiveQueryInfo_contentHandlerDb() {
		$store = $this->getRevisionStore();
		$store->setContentHandlerUseDB( true );
		$this->assertEquals(
			[
				'tables' => [
					'archive'
				],
				'fields' => array_merge(
					$this->getDefaultArchiveFields(),
					[
						'ar_comment_text' => 'ar_comment',
						'ar_comment_data' => 'NULL',
						'ar_comment_cid' => 'NULL',
						'ar_content_format',
						'ar_content_model',
					]
				),
				'joins' => [],
			],
			$store->getArchiveQueryInfo()
		);
	}

	/**
	 * @covers \MediaWiki\Storage\RevisionStore::getArchiveQueryInfo
	 */
	public function testGetArchiveQueryInfo_noContentHandlerDb() {
		$store = $this->getRevisionStore();
		$store->setContentHandlerUseDB( false );
		$this->assertEquals(
			[
				'tables' => [
					'archive'
				],
				'fields' => array_merge(
					$this->getDefaultArchiveFields(),
					[
						'ar_comment_text' => 'ar_comment',
						'ar_comment_data' => 'NULL',
						'ar_comment_cid' => 'NULL',
					]
				),
				'joins' => [],
			],
			$store->getArchiveQueryInfo()
		);
	}

}
