<?php

namespace MediaWiki\Tests\Revision;

use MediaWiki\MediaWikiServices;
use MediaWiki\Revision\SlotRecord;
use MediaWikiTestCase;
use Revision;

/**
 * Tests RevisionStore against the post-migration MCR DB schema.
 *
 * @group RevisionStore
 * @group Storage
 * @group Database
 */
class RevisionQueryInfoTest extends MediaWikiTestCase {

	protected function getRevisionQueryFields( $returnTextIdField = true ) {
		$fields = [
			'rev_id',
			'rev_page',
			'rev_timestamp',
			'rev_minor_edit',
			'rev_deleted',
			'rev_len',
			'rev_parent_id',
			'rev_sha1',
		];
		if ( $returnTextIdField ) {
			$fields[] = 'rev_text_id';
		}
		return $fields;
	}

	protected function getArchiveQueryFields( $returnTextFields = true ) {
		$fields = [
			'ar_id',
			'ar_page_id',
			'ar_namespace',
			'ar_title',
			'ar_rev_id',
			'ar_timestamp',
			'ar_minor_edit',
			'ar_deleted',
			'ar_len',
			'ar_parent_id',
			'ar_sha1',
		];
		if ( $returnTextFields ) {
			$fields[] = 'ar_text_id';
		}
		return $fields;
	}

	protected function getNewCommentQueryFields( $prefix ) {
		return [
			"{$prefix}_comment_text" => "comment_{$prefix}_comment.comment_text",
			"{$prefix}_comment_data" => "comment_{$prefix}_comment.comment_data",
			"{$prefix}_comment_cid" => "comment_{$prefix}_comment.comment_id",
		];
	}

	protected function getNewActorQueryFields( $prefix, $tmp = false ) {
		return [
			"{$prefix}_user" => "actor_{$prefix}_user.actor_user",
			"{$prefix}_user_text" => "actor_{$prefix}_user.actor_name",
			"{$prefix}_actor" => $tmp ? "temp_{$prefix}_user.{$prefix}actor_actor" : "{$prefix}_actor",
		];
	}

	protected function getTextQueryFields() {
		return [
			'old_text',
			'old_flags',
		];
	}

	protected function getPageQueryFields() {
		return [
			'page_namespace',
			'page_title',
			'page_id',
			'page_latest',
			'page_is_redirect',
			'page_len',
		];
	}

	protected function getUserQueryFields() {
		return [
			'user_name',
		];
	}

	protected function getContentHandlerQueryFields( $prefix ) {
		return [
			"{$prefix}_content_format",
			"{$prefix}_content_model",
		];
	}

	public function provideArchiveQueryInfo() {
		yield 'MCR, comment, actor' => [
			[
				'wgMultiContentRevisionSchemaMigrationStage' => SCHEMA_COMPAT_NEW,
			],
			[
				'tables' => [
					'archive',
					'actor_ar_user' => 'actor',
					'comment_ar_comment' => 'comment',
				],
				'fields' => array_merge(
					$this->getArchiveQueryFields( false ),
					$this->getNewActorQueryFields( 'ar' ),
					$this->getNewCommentQueryFields( 'ar' )
				),
				'joins' => [
					'comment_ar_comment'
						=> [ 'JOIN', 'comment_ar_comment.comment_id = ar_comment_id' ],
					'actor_ar_user' => [ 'JOIN', 'actor_ar_user.actor_id = ar_actor' ],
				],
			]
		];
		yield 'read-new MCR, comment, actor' => [
			[
				'wgContentHandlerUseDB' => true,
				'wgMultiContentRevisionSchemaMigrationStage'
					=> SCHEMA_COMPAT_WRITE_BOTH | SCHEMA_COMPAT_READ_NEW,
			],
			[
				'tables' => [
					'archive',
					'actor_ar_user' => 'actor',
					'comment_ar_comment' => 'comment',
				],
				'fields' => array_merge(
					$this->getArchiveQueryFields( false ),
					$this->getNewActorQueryFields( 'ar' ),
					$this->getNewCommentQueryFields( 'ar' )
				),
				'joins' => [
					'comment_ar_comment'
						=> [ 'JOIN', 'comment_ar_comment.comment_id = ar_comment_id' ],
					'actor_ar_user' => [ 'JOIN', 'actor_ar_user.actor_id = ar_actor' ],
				],
			]
		];
		yield 'MCR write-both/read-old' => [
			[
				'wgContentHandlerUseDB' => true,
				'wgMultiContentRevisionSchemaMigrationStage'
					=> SCHEMA_COMPAT_WRITE_BOTH | SCHEMA_COMPAT_READ_OLD,
			],
			[
				'tables' => [
					'archive',
					'actor_ar_user' => 'actor',
					'comment_ar_comment' => 'comment',
				],
				'fields' => array_merge(
					$this->getArchiveQueryFields( true ),
					$this->getContentHandlerQueryFields( 'ar' ),
					$this->getNewActorQueryFields( 'ar' ),
					$this->getNewCommentQueryFields( 'ar' )
				),
				'joins' => [
					'comment_ar_comment'
						=> [ 'JOIN', 'comment_ar_comment.comment_id = ar_comment_id' ],
					'actor_ar_user' => [ 'JOIN', 'actor_ar_user.actor_id = ar_actor' ],
				],
			]
		];
	}

	public function provideQueryInfo() {
		// TODO: more option variations
		yield 'MCR, page, user, comment, actor' => [
			[
				'wgContentHandlerUseDB' => true,
				'wgMultiContentRevisionSchemaMigrationStage' => SCHEMA_COMPAT_NEW,
			],
			[ 'page', 'user' ],
			[
				'tables' => [
					'revision',
					'page',
					'user',
					'temp_rev_user' => 'revision_actor_temp',
					'temp_rev_comment' => 'revision_comment_temp',
					'actor_rev_user' => 'actor',
					'comment_rev_comment' => 'comment',
				],
				'fields' => array_merge(
					$this->getRevisionQueryFields( false ),
					$this->getPageQueryFields(),
					$this->getUserQueryFields(),
					$this->getNewActorQueryFields( 'rev', 'temp_rev_user.revactor_actor' ),
					$this->getNewCommentQueryFields( 'rev' )
				),
				'joins' => [
					'page' => [ 'JOIN', [ 'page_id = rev_page' ] ],
					'user' => [
						'LEFT JOIN',
						[ 'actor_rev_user.actor_user != 0', 'user_id = actor_rev_user.actor_user' ],
					],
					'comment_rev_comment' => [
						'JOIN',
						'comment_rev_comment.comment_id = temp_rev_comment.revcomment_comment_id',
					],
					'actor_rev_user' => [
						'JOIN',
						'actor_rev_user.actor_id = temp_rev_user.revactor_actor',
					],
					'temp_rev_user' => [ 'JOIN', 'temp_rev_user.revactor_rev = rev_id' ],
					'temp_rev_comment' => [ 'JOIN', 'temp_rev_comment.revcomment_rev = rev_id' ],
					'temp_rev_user' => [ 'JOIN', 'temp_rev_user.revactor_rev = rev_id' ],
					'actor_rev_user' => [ 'JOIN', 'actor_rev_user.actor_id = temp_rev_user.revactor_actor' ],
				],
			]
		];
		yield 'MCR read-new, page, user, comment, actor' => [
			[
				'wgContentHandlerUseDB' => true,
				'wgMultiContentRevisionSchemaMigrationStage'
					=> SCHEMA_COMPAT_WRITE_BOTH | SCHEMA_COMPAT_READ_NEW,
			],
			[ 'page', 'user' ],
			[
				'tables' => [
					'revision',
					'page',
					'user',
					'temp_rev_user' => 'revision_actor_temp',
					'temp_rev_comment' => 'revision_comment_temp',
					'actor_rev_user' => 'actor',
					'comment_rev_comment' => 'comment',
				],
				'fields' => array_merge(
					$this->getRevisionQueryFields( false ),
					$this->getPageQueryFields(),
					$this->getUserQueryFields(),
					$this->getNewActorQueryFields( 'rev', 'temp_rev_user.revactor_actor' ),
					$this->getNewCommentQueryFields( 'rev' )
				),
				'joins' => [
					'page' => [ 'JOIN', [ 'page_id = rev_page' ] ],
					'user' => [
						'LEFT JOIN',
						[
							'actor_rev_user.actor_user != 0',
							'user_id = actor_rev_user.actor_user',
						]
					],
					'temp_rev_comment' => [ 'JOIN', 'temp_rev_comment.revcomment_rev = rev_id' ],
					'comment_rev_comment'
						=> [ 'JOIN', 'comment_rev_comment.comment_id = temp_rev_comment.revcomment_comment_id' ],
					'temp_rev_user' => [ 'JOIN', 'temp_rev_user.revactor_rev = rev_id' ],
					'actor_rev_user' => [ 'JOIN', 'actor_rev_user.actor_id = temp_rev_user.revactor_actor' ],
				],
			]
		];
		yield 'MCR read-new' => [
			[
				'wgContentHandlerUseDB' => true,
				'wgMultiContentRevisionSchemaMigrationStage'
					=> SCHEMA_COMPAT_WRITE_BOTH | SCHEMA_COMPAT_READ_NEW,
			],
			[ 'page', 'user' ],
			[
				'tables' => [
					'revision',
					'page',
					'user',
					'temp_rev_user' => 'revision_actor_temp',
					'temp_rev_comment' => 'revision_comment_temp',
					'actor_rev_user' => 'actor',
					'comment_rev_comment' => 'comment',
				],
				'fields' => array_merge(
					$this->getRevisionQueryFields( false ),
					$this->getPageQueryFields(),
					$this->getUserQueryFields(),
					$this->getNewActorQueryFields( 'rev', 'temp_rev_user.revactor_actor' ),
					$this->getNewCommentQueryFields( 'rev' )
				),
				'joins' => [
					'page' => [ 'JOIN', [ 'page_id = rev_page' ] ],
					'user' => [
						'LEFT JOIN',
						[
							'actor_rev_user.actor_user != 0',
							'user_id = actor_rev_user.actor_user'
						]
					],
					'temp_rev_comment' => [ 'JOIN', 'temp_rev_comment.revcomment_rev = rev_id' ],
					'comment_rev_comment'
						=> [ 'JOIN', 'comment_rev_comment.comment_id = temp_rev_comment.revcomment_comment_id' ],
					'temp_rev_user' => [ 'JOIN', 'temp_rev_user.revactor_rev = rev_id' ],
					'actor_rev_user' => [ 'JOIN', 'actor_rev_user.actor_id = temp_rev_user.revactor_actor' ],
				],
			]
		];
		yield 'MCR write-both/read-old' => [
			[
				'wgContentHandlerUseDB' => true,
				'wgMultiContentRevisionSchemaMigrationStage'
					=> SCHEMA_COMPAT_WRITE_BOTH | SCHEMA_COMPAT_READ_OLD,
			],
			[],
			[
				'tables' => [
					'revision',
					'temp_rev_comment' => 'revision_comment_temp',
					'comment_rev_comment' => 'comment',
					'temp_rev_user' => 'revision_actor_temp',
					'actor_rev_user' => 'actor',
				],
				'fields' => array_merge(
					$this->getRevisionQueryFields( true ),
					$this->getContentHandlerQueryFields( 'rev' ),
					$this->getNewActorQueryFields( 'rev', 'temp_rev_user.revactor_actor' ),
					$this->getNewCommentQueryFields( 'rev' )
				),
				'joins' => [
					'temp_rev_comment' => [ 'JOIN', 'temp_rev_comment.revcomment_rev = rev_id' ],
					'comment_rev_comment'
						=> [ 'JOIN', 'comment_rev_comment.comment_id = temp_rev_comment.revcomment_comment_id' ],
					'temp_rev_user' => [ 'JOIN', 'temp_rev_user.revactor_rev = rev_id' ],
					'actor_rev_user' => [ 'JOIN', 'actor_rev_user.actor_id = temp_rev_user.revactor_actor' ],
				],
			]
		];
		yield 'MCR write-both/read-old, page, user' => [
			[
				'wgContentHandlerUseDB' => true,
				'wgMultiContentRevisionSchemaMigrationStage'
					=> SCHEMA_COMPAT_WRITE_BOTH | SCHEMA_COMPAT_READ_OLD,
			],
			[ 'page', 'user' ],
			[
				'tables' => [
					'revision',
					'page',
					'user',
					'temp_rev_comment' => 'revision_comment_temp',
					'comment_rev_comment' => 'comment',
					'temp_rev_user' => 'revision_actor_temp',
					'actor_rev_user' => 'actor',
				],
				'fields' => array_merge(
					$this->getRevisionQueryFields( true ),
					$this->getContentHandlerQueryFields( 'rev' ),
					$this->getUserQueryFields(),
					$this->getPageQueryFields(),
					$this->getNewActorQueryFields( 'rev', 'temp_rev_user.revactor_actor' ),
					$this->getNewCommentQueryFields( 'rev' )
				),
				'joins' => [
					'page' => [ 'JOIN', [ 'page_id = rev_page' ] ],
					'user' => [
						'LEFT JOIN',
						[
							'actor_rev_user.actor_user != 0',
							'user_id = actor_rev_user.actor_user',
						]
					],
					'temp_rev_comment' => [ 'JOIN', 'temp_rev_comment.revcomment_rev = rev_id' ],
					'comment_rev_comment'
						=> [ 'JOIN', 'comment_rev_comment.comment_id = temp_rev_comment.revcomment_comment_id' ],
					'temp_rev_user' => [ 'JOIN', 'temp_rev_user.revactor_rev = rev_id' ],
					'actor_rev_user' => [ 'JOIN', 'actor_rev_user.actor_id = temp_rev_user.revactor_actor' ],
				],
			]
		];
	}

	public function provideSlotsQueryInfo() {
		yield 'MCR, no options' => [
			[
				'wgMultiContentRevisionSchemaMigrationStage' => SCHEMA_COMPAT_NEW,
			],
			[],
			[
				'tables' => [
					'slots'
				],
				'fields' => [
					'slot_revision_id',
					'slot_content_id',
					'slot_origin',
					'slot_role_id',
				],
				'joins' => [],
				'keys' => [
					'rev_id' => 'slot_revision_id',
					'role_id' => 'slot_role_id'
				],
			]
		];
		yield 'MCR, role option' => [
			[
				'wgMultiContentRevisionSchemaMigrationStage' => SCHEMA_COMPAT_NEW,
			],
			[ 'role' ],
			[
				'tables' => [
					'slots',
					'slot_roles',
				],
				'fields' => [
					'slot_revision_id',
					'slot_content_id',
					'slot_origin',
					'slot_role_id',
					'role_name',
				],
				'joins' => [
					'slot_roles' => [ 'LEFT JOIN', [ 'slot_role_id = role_id' ] ],
				],
				'keys' => [
					'rev_id' => 'slot_revision_id',
					'role_id' => 'slot_role_id'
				],
			]
		];
		yield 'MCR read-new, content option' => [
			[
				'wgMultiContentRevisionSchemaMigrationStage'
					=> SCHEMA_COMPAT_WRITE_BOTH | SCHEMA_COMPAT_READ_NEW,
			],
			[ 'content' ],
			[
				'tables' => [
					'slots',
					'content',
				],
				'fields' => [
					'slot_revision_id',
					'slot_content_id',
					'slot_origin',
					'slot_role_id',
					'content_size',
					'content_sha1',
					'content_address',
					'content_model',
				],
				'joins' => [
					'content' => [ 'JOIN', [ 'slot_content_id = content_id' ] ],
				],
				'keys' => [
					'rev_id' => 'slot_revision_id',
					'role_id' => 'slot_role_id',
					'model_id' => 'content_model',
				],
			]
		];
		yield 'MCR read-new, content and model options' => [
			[
				'wgMultiContentRevisionSchemaMigrationStage'
					=> SCHEMA_COMPAT_WRITE_BOTH | SCHEMA_COMPAT_READ_NEW,
			],
			[ 'content', 'model' ],
			[
				'tables' => [
					'slots',
					'content',
					'content_models',
				],
				'fields' => [
					'slot_revision_id',
					'slot_content_id',
					'slot_origin',
					'slot_role_id',
					'content_size',
					'content_sha1',
					'content_address',
					'content_model',
					'model_name',
				],
				'joins' => [
					'content' => [ 'JOIN', [ 'slot_content_id = content_id' ] ],
					'content_models' => [ 'LEFT JOIN', [ 'content_model = model_id' ] ],
				],
				'keys' => [
					'rev_id' => 'slot_revision_id',
					'role_id' => 'slot_role_id',
					'model_id' => 'content_model',
				],
			]
		];

		$db = wfGetDB( DB_REPLICA );

		yield 'MCR write-both/read-old' => [
			[
				'wgMultiContentRevisionSchemaMigrationStage'
					=> SCHEMA_COMPAT_WRITE_BOTH | SCHEMA_COMPAT_READ_OLD,
			],
			[],
			[
				'tables' => [
					'revision',
				],
				'fields' => array_merge(
					[
						'slot_revision_id' => 'rev_id',
						'slot_content_id' => 'NULL',
						'slot_origin' => 'rev_id',
						'role_name' => $db->addQuotes( SlotRecord::MAIN ),
					]
				),
				'joins' => [],
				'keys' => [
					'rev_id' => 'rev_id'
				],
			]
		];
		yield 'MCR write-both/read-old, content' => [
			[
				'wgMultiContentRevisionSchemaMigrationStage'
					=> SCHEMA_COMPAT_WRITE_BOTH | SCHEMA_COMPAT_READ_OLD,
			],
			[ 'content' ],
			[
				'tables' => [
					'revision',
				],
				'fields' => array_merge(
					[
						'slot_revision_id' => 'rev_id',
						'slot_content_id' => 'NULL',
						'slot_origin' => 'rev_id',
						'role_name' => $db->addQuotes( SlotRecord::MAIN ),
						'content_size' => 'rev_len',
						'content_sha1' => 'rev_sha1',
						'content_address' => $db->buildConcat( [
							$db->addQuotes( 'tt:' ), 'rev_text_id' ] ),
						'rev_text_id' => 'rev_text_id',
						'model_name' => 'rev_content_model',
					]
				),
				'joins' => [],
				'keys' => [
					'rev_id' => 'rev_id'
				],
			]
		];
		yield 'MCR write-both/read-old, content, model, role' => [
			[
				'wgMultiContentRevisionSchemaMigrationStage'
					=> SCHEMA_COMPAT_WRITE_BOTH | SCHEMA_COMPAT_READ_OLD,
			],
			[ 'content', 'model', 'role' ],
			[
				'tables' => [
					'revision',
				],
				'fields' => array_merge(
					[
						'slot_revision_id' => 'rev_id',
						'slot_content_id' => 'NULL',
						'slot_origin' => 'rev_id',
						'role_name' => $db->addQuotes( SlotRecord::MAIN ),
						'content_size' => 'rev_len',
						'content_sha1' => 'rev_sha1',
						'content_address' => $db->buildConcat( [
							$db->addQuotes( 'tt:' ), 'rev_text_id' ] ),
						'rev_text_id' => 'rev_text_id',
						'model_name' => 'rev_content_model',
					]
				),
				'joins' => [],
				'keys' => [
					'rev_id' => 'rev_id'
				],
			]
		];
	}

	/**
	 * @covers Revision::getArchiveQueryInfo
	 * @dataProvider provideArchiveQueryInfo
	 */
	public function testRevisionGetArchiveQueryInfo( $migrationStageSettings, $expected ) {
		$this->setMwGlobals( $migrationStageSettings );

		$queryInfo = Revision::getArchiveQueryInfo();
		$this->assertQueryInfoEquals( $expected, $queryInfo );
	}

	/**
	 * @covers Revision::getQueryInfo
	 * @dataProvider provideQueryInfo
	 */
	public function testRevisionGetQueryInfo( $migrationStageSettings, $options, $expected ) {
		$this->setMwGlobals( $migrationStageSettings );

		$queryInfo = Revision::getQueryInfo( $options );
		$this->assertQueryInfoEquals( $expected, $queryInfo );
	}

	/**
	 * @dataProvider provideQueryInfo
	 * @covers \MediaWiki\Revision\RevisionStore::getQueryInfo
	 */
	public function testRevisionStoreGetQueryInfo( $migrationStageSettings, $options, $expected ) {
		$this->setMwGlobals( $migrationStageSettings );

		$store = MediaWikiServices::getInstance()->getRevisionStore();

		$queryInfo = $store->getQueryInfo( $options );
		$this->assertQueryInfoEquals( $expected, $queryInfo );
	}

	/**
	 * @dataProvider provideSlotsQueryInfo
	 * @covers \MediaWiki\Revision\RevisionStore::getSlotsQueryInfo
	 */
	public function testRevisionStoreGetSlotsQueryInfo(
		$migrationStageSettings,
		$options,
		$expected
	) {
		$this->setMwGlobals( $migrationStageSettings );

		$store = MediaWikiServices::getInstance()->getRevisionStore();

		$queryInfo = $store->getSlotsQueryInfo( $options );
		$this->assertQueryInfoEquals( $expected, $queryInfo );
	}

	/**
	 * @dataProvider provideArchiveQueryInfo
	 * @covers \MediaWiki\Revision\RevisionStore::getArchiveQueryInfo
	 */
	public function testRevisionStoreGetArchiveQueryInfo( $migrationStageSettings, $expected ) {
		$this->setMwGlobals( $migrationStageSettings );

		$store = MediaWikiServices::getInstance()->getRevisionStore();

		$queryInfo = $store->getArchiveQueryInfo();
		$this->assertQueryInfoEquals( $expected, $queryInfo );
	}

	private function assertQueryInfoEquals( $expected, $queryInfo ) {
		$this->assertArrayEqualsIgnoringIntKeyOrder(
			$expected['tables'],
			$queryInfo['tables'],
			'tables'
		);
		$this->assertArrayEqualsIgnoringIntKeyOrder(
			$expected['fields'],
			$queryInfo['fields'],
			'fields'
		);
		$this->assertArrayEqualsIgnoringIntKeyOrder(
			$expected['joins'],
			$queryInfo['joins'],
			'joins'
		);
		if ( isset( $expected['keys'] ) ) {
			$this->assertArrayEqualsIgnoringIntKeyOrder(
				$expected['keys'],
				$queryInfo['keys'],
				'keys'
			);
		}
	}

	/**
	 * Assert that the two arrays passed are equal, ignoring the order of the values that integer
	 * keys.
	 *
	 * Note: Failures of this assertion can be slightly confusing as the arrays are actually
	 * split into a string key array and an int key array before assertions occur.
	 *
	 * @param array $expected
	 * @param array $actual
	 */
	private function assertArrayEqualsIgnoringIntKeyOrder(
		array $expected,
		array $actual,
		$message = null
	) {
		$this->objectAssociativeSort( $expected );
		$this->objectAssociativeSort( $actual );

		// Separate the int key values from the string key values so that assertion failures are
		// easier to understand.
		$expectedIntKeyValues = [];
		$actualIntKeyValues = [];

		// Remove all int keys and re add them at the end after sorting by value
		// This will result in all int keys being in the same order with same ints at the end of
		// the array
		foreach ( $expected as $key => $value ) {
			if ( is_int( $key ) ) {
				unset( $expected[$key] );
				$expectedIntKeyValues[] = $value;
			}
		}
		foreach ( $actual as $key => $value ) {
			if ( is_int( $key ) ) {
				unset( $actual[$key] );
				$actualIntKeyValues[] = $value;
			}
		}

		$this->objectAssociativeSort( $expected );
		$this->objectAssociativeSort( $actual );

		$this->objectAssociativeSort( $expectedIntKeyValues );
		$this->objectAssociativeSort( $actualIntKeyValues );

		$this->assertEquals( $expected, $actual, $message );
		$this->assertEquals( $expectedIntKeyValues, $actualIntKeyValues, $message );
	}

}
