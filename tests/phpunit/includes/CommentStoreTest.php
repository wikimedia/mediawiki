<?php

use Wikimedia\TestingAccessWrapper;

/**
 * @group Database
 * @covers CommentStore
 */
class CommentStoreTest extends MediaWikiLangTestCase {

	protected $tablesUsed = [
		'revision',
		'revision_comment_temp',
		'ipblocks',
		'comment',
	];

	/**
	 * Create a store for a particular stage
	 * @param int $stage
	 * @return CommentStore
	 */
	protected function makeStore( $stage ) {
		$store = new CommentStore( $this->db );
		TestingAccessWrapper::newFromObject( $store )->stage = $stage;
		return $store;
	}

	/**
	 * @dataProvider provideGetFields
	 * @param int $stage
	 * @param string $key
	 * @param array $expect
	 */
	public function testGetFields( $stage, $key, $expect ) {
		$store = $this->makeStore( $stage );
		$result = $store->getFields( $key );
		$this->assertEquals( $expect, $result );
	}

	public static function provideGetFields() {
		return [
			'Simple table, old' => [
				MIGRATION_OLD, 'ipb_reason',
				[ 'ipb_reason_text' => 'ipb_reason', 'ipb_reason_data' => 'NULL' ],
			],
			'Simple table, write-both' => [
				MIGRATION_WRITE_BOTH, 'ipb_reason',
				[ 'ipb_reason_old' => 'ipb_reason', 'ipb_reason_id' => 'ipb_reason_id' ],
			],
			'Simple table, write-new' => [
				MIGRATION_WRITE_NEW, 'ipb_reason',
				[ 'ipb_reason_old' => 'ipb_reason', 'ipb_reason_id' => 'ipb_reason_id' ],
			],
			'Simple table, new' => [
				MIGRATION_NEW, 'ipb_reason',
				[ 'ipb_reason_id' => 'ipb_reason_id' ],
			],

			'Revision, old' => [
				MIGRATION_OLD, 'rev_comment',
				[ 'rev_comment_text' => 'rev_comment', 'rev_comment_data' => 'NULL' ],
			],
			'Revision, write-both' => [
				MIGRATION_WRITE_BOTH, 'rev_comment',
				[ 'rev_comment_old' => 'rev_comment', 'rev_comment_pk' => 'rev_id' ],
			],
			'Revision, write-new' => [
				MIGRATION_WRITE_NEW, 'rev_comment',
				[ 'rev_comment_old' => 'rev_comment', 'rev_comment_pk' => 'rev_id' ],
			],
			'Revision, new' => [
				MIGRATION_NEW, 'rev_comment',
				[ 'rev_comment_pk' => 'rev_id' ],
			],

			'Image, old' => [
				MIGRATION_OLD, 'img_description',
				[ 'img_description_text' => 'img_description', 'img_description_data' => 'NULL' ],
			],
			'Image, write-both' => [
				MIGRATION_WRITE_BOTH, 'img_description',
				[ 'img_description_old' => 'img_description', 'img_description_pk' => 'img_name' ],
			],
			'Image, write-new' => [
				MIGRATION_WRITE_NEW, 'img_description',
				[ 'img_description_old' => 'img_description', 'img_description_pk' => 'img_name' ],
			],
			'Image, new' => [
				MIGRATION_NEW, 'img_description',
				[ 'img_description_pk' => 'img_name' ],
			],
		];
	}

	/**
	 * @dataProvider provideGetJoin
	 * @param int $stage
	 * @param string $key
	 * @param array $expect
	 */
	public function testGetJoin( $stage, $key, $expect ) {
		$store = $this->makeStore( $stage );
		$result = $store->getJoin( $key );
		$this->assertEquals( $expect, $result );
	}

	public static function provideGetJoin() {
		return [
			'Simple table, old' => [
				MIGRATION_OLD, 'ipb_reason', [
					'tables' => [],
					'fields' => [ 'ipb_reason_text' => 'ipb_reason', 'ipb_reason_data' => 'NULL' ],
					'joins' => [],
				],
			],
			'Simple table, write-both' => [
				MIGRATION_WRITE_BOTH, 'ipb_reason', [
					'tables' => [ 'comment_ipb_reason' => 'comment' ],
					'fields' => [
						'ipb_reason_text' => 'COALESCE( comment_ipb_reason.comment_text, ipb_reason )',
						'ipb_reason_data' => 'comment_ipb_reason.comment_data',
					],
					'joins' => [
						'comment_ipb_reason' => [ 'LEFT JOIN', [ 'comment_ipb_reason.comment_id = ipb_reason_id' ] ],
					],
				],
			],
			'Simple table, write-new' => [
				MIGRATION_WRITE_NEW, 'ipb_reason', [
					'tables' => [ 'comment_ipb_reason' => 'comment' ],
					'fields' => [
						'ipb_reason_text' => 'COALESCE( comment_ipb_reason.comment_text, ipb_reason )',
						'ipb_reason_data' => 'comment_ipb_reason.comment_data',
					],
					'joins' => [
						'comment_ipb_reason' => [ 'LEFT JOIN', [ 'comment_ipb_reason.comment_id = ipb_reason_id' ] ],
					],
				],
			],
			'Simple table, new' => [
				MIGRATION_NEW, 'ipb_reason', [
					'tables' => [ 'comment_ipb_reason' => 'comment' ],
					'fields' => [
						'ipb_reason_text' => 'comment_ipb_reason.comment_text',
						'ipb_reason_data' => 'comment_ipb_reason.comment_data',
					],
					'joins' => [
						'comment_ipb_reason' => [ 'JOIN', [ 'comment_ipb_reason.comment_id = ipb_reason_id' ] ],
					],
				],
			],

			'Revision, old' => [
				MIGRATION_OLD, 'rev_comment', [
					'tables' => [],
					'fields' => [ 'rev_comment_text' => 'rev_comment', 'rev_comment_data' => 'NULL' ],
					'joins' => [],
				],
			],
			'Revision, write-both' => [
				MIGRATION_WRITE_BOTH, 'rev_comment', [
					'tables' => [
						'temp_rev_comment' => 'revision_comment_temp',
						'comment_rev_comment' => 'comment',
					],
					'fields' => [
						'rev_comment_text' => 'COALESCE( comment_rev_comment.comment_text, rev_comment )',
						'rev_comment_data' => 'comment_rev_comment.comment_data',
					],
					'joins' => [
						'temp_rev_comment' => [ 'LEFT JOIN', [ 'temp_rev_comment.revcomment_rev = rev_id' ] ],
						'comment_rev_comment' => [ 'LEFT JOIN',
							[ 'comment_rev_comment.comment_id = temp_rev_comment.revcomment_comment_id' ] ],
					],
				],
			],
			'Revision, write-new' => [
				MIGRATION_WRITE_NEW, 'rev_comment', [
					'tables' => [
						'temp_rev_comment' => 'revision_comment_temp',
						'comment_rev_comment' => 'comment',
					],
					'fields' => [
						'rev_comment_text' => 'COALESCE( comment_rev_comment.comment_text, rev_comment )',
						'rev_comment_data' => 'comment_rev_comment.comment_data',
					],
					'joins' => [
						'temp_rev_comment' => [ 'LEFT JOIN', [ 'temp_rev_comment.revcomment_rev = rev_id' ] ],
						'comment_rev_comment' => [ 'LEFT JOIN',
							[ 'comment_rev_comment.comment_id = temp_rev_comment.revcomment_comment_id' ] ],
					],
				],
			],
			'Revision, new' => [
				MIGRATION_NEW, 'rev_comment', [
					'tables' => [
						'temp_rev_comment' => 'revision_comment_temp',
						'comment_rev_comment' => 'comment',
					],
					'fields' => [
						'rev_comment_text' => 'comment_rev_comment.comment_text',
						'rev_comment_data' => 'comment_rev_comment.comment_data',
					],
					'joins' => [
						'temp_rev_comment' => [ 'JOIN', [ 'temp_rev_comment.revcomment_rev = rev_id' ] ],
						'comment_rev_comment' => [ 'JOIN',
							[ 'comment_rev_comment.comment_id = temp_rev_comment.revcomment_comment_id' ] ],
					],
				],
			],

			'Image, old' => [
				MIGRATION_OLD, 'img_description', [
					'tables' => [],
					'fields' => [ 'img_description_text' => 'img_description', 'img_description_data' => 'NULL' ],
					'joins' => [],
				],
			],
			'Image, write-both' => [
				MIGRATION_WRITE_BOTH, 'img_description', [
					'tables' => [
						'temp_img_description' => 'image_comment_temp',
						'comment_img_description' => 'comment',
					],
					'fields' => [
						'img_description_text' => 'COALESCE( comment_img_description.comment_text, img_description )',
						'img_description_data' => 'comment_img_description.comment_data',
					],
					'joins' => [
						'temp_img_description' => [ 'LEFT JOIN',
							[ 'temp_img_description.imgcomment_name = img_name' ] ],
						'comment_img_description' => [ 'LEFT JOIN',
							[ 'comment_img_description.comment_id = temp_img_description.imgcomment_description_id' ] ],
					],
				],
			],
			'Image, write-new' => [
				MIGRATION_WRITE_NEW, 'img_description', [
					'tables' => [
						'temp_img_description' => 'image_comment_temp',
						'comment_img_description' => 'comment',
					],
					'fields' => [
						'img_description_text' => 'COALESCE( comment_img_description.comment_text, img_description )',
						'img_description_data' => 'comment_img_description.comment_data',
					],
					'joins' => [
						'temp_img_description' => [ 'LEFT JOIN',
							[ 'temp_img_description.imgcomment_name = img_name' ] ],
						'comment_img_description' => [ 'LEFT JOIN',
							[ 'comment_img_description.comment_id = temp_img_description.imgcomment_description_id' ] ],
					],
				],
			],
			'Image, new' => [
				MIGRATION_NEW, 'img_description', [
					'tables' => [
						'temp_img_description' => 'image_comment_temp',
						'comment_img_description' => 'comment',
					],
					'fields' => [
						'img_description_text' => 'comment_img_description.comment_text',
						'img_description_data' => 'comment_img_description.comment_data',
					],
					'joins' => [
						'temp_img_description' => [ 'JOIN', [ 'temp_img_description.imgcomment_name = img_name' ] ],
						'comment_img_description' => [ 'JOIN',
							[ 'comment_img_description.comment_id = temp_img_description.imgcomment_description_id' ] ],
					],
				],
			],
		];
	}

	private function assertComment( $expect, $actual, $from ) {
		$this->assertSame( $expect['text'], $actual->text, "text $from" );
		$this->assertInstanceOf( get_class( $expect['message'] ), $actual->message,
			"message class $from" );
		$this->assertSame( $expect['message']->getKeysToTry(), $actual->message->getKeysToTry(),
			"message keys $from" );
		$this->assertEquals( $expect['message']->text(), $actual->message->text(),
			"message rendering $from" );
		$this->assertEquals( $expect['data'], $actual->data, "data $from" );
	}

	/**
	 * @dataProvider provideInsertRoundTrip
	 * @param string $table
	 * @param string $key
	 * @param string $pk
	 * @param string $extraFields
	 * @param string|Message $comment
	 * @param array|null $data
	 * @param array $expect
	 */
	public function testInsertRoundTrip( $table, $key, $pk, $extraFields, $comment, $data, $expect ) {
		$expectOld = [
			'text' => $expect['text'],
			'message' => new RawMessage( '$1', [ $expect['text'] ] ),
			'data' => null,
		];

		$stages = [
			MIGRATION_OLD => [ MIGRATION_OLD, MIGRATION_WRITE_NEW ],
			MIGRATION_WRITE_BOTH => [ MIGRATION_OLD, MIGRATION_NEW ],
			MIGRATION_WRITE_NEW => [ MIGRATION_WRITE_BOTH, MIGRATION_NEW ],
			MIGRATION_NEW => [ MIGRATION_WRITE_BOTH, MIGRATION_NEW ],
		];

		foreach ( $stages as $writeStage => $readRange ) {
			if ( $key === 'ipb_reason' ) {
				$extraFields['ipb_address'] = __CLASS__ . "#$writeStage";
			}

			$wstore = $this->makeStore( $writeStage );
			$usesTemp = $key === 'rev_comment';

			if ( $usesTemp ) {
				list( $fields, $callback ) = $wstore->insertWithTempTable( $key, $comment, $data );
			} else {
				$fields = $wstore->insert( $key, $comment, $data );
			}

			if ( $writeStage <= MIGRATION_WRITE_BOTH ) {
				$this->assertSame( $expect['text'], $fields[$key], "old field, stage=$writeStage" );
			} else {
				$this->assertArrayNotHasKey( $key, $fields, "old field, stage=$writeStage" );
			}
			if ( $writeStage >= MIGRATION_WRITE_BOTH && !$usesTemp ) {
				$this->assertArrayHasKey( "{$key}_id", $fields, "new field, stage=$writeStage" );
			} else {
				$this->assertArrayNotHasKey( "{$key}_id", $fields, "new field, stage=$writeStage" );
			}

			$this->db->insert( $table, $extraFields + $fields, __METHOD__ );
			$id = $this->db->insertId();
			if ( $usesTemp ) {
				$callback( $id );
			}

			for ( $readStage = $readRange[0]; $readStage <= $readRange[1]; $readStage++ ) {
				$rstore = $this->makeStore( $readStage );

				$fieldRow = $this->db->selectRow(
					$table,
					$rstore->getFields( $key ),
					[ $pk => $id ],
					__METHOD__
				);

				list( $tables, $fields, $joins ) = array_values( $rstore->getJoin( $key ) );
				$joinRow = $this->db->selectRow(
					[ $table ] + $tables,
					$fields,
					[ $pk => $id ],
					__METHOD__,
					[],
					$joins
				);

				$this->assertComment(
					$writeStage === MIGRATION_OLD || $readStage === MIGRATION_OLD ? $expectOld : $expect,
					$rstore->getComment( $key, $fieldRow ),
					"w=$writeStage, r=$readStage, from getFields()"
				);
				$this->assertComment(
					$writeStage === MIGRATION_OLD || $readStage === MIGRATION_OLD ? $expectOld : $expect,
					$rstore->getComment( $key, $joinRow ),
					"w=$writeStage, r=$readStage, from getJoin()"
				);
			}
		}
	}

	public static function provideInsertRoundTrip() {
		$msgComment = new Message( 'parentheses', [ 'message comment' ] );
		$textCommentMsg = new RawMessage( '$1', [ 'text comment' ] );
		$nestedMsgComment = new Message( [ 'parentheses', 'rawmessage' ], [ new Message( 'mainpage' ) ] );
		$ipbfields = [
			'ipb_range_start' => '',
			'ipb_range_end' => '',
		];
		$revfields = [
			'rev_page' => 42,
			'rev_text_id' => 42,
			'rev_len' => 0,
		];

		return [
			'Simple table, text comment' => [
				'ipblocks', 'ipb_reason', 'ipb_id', $ipbfields, 'text comment', null, [
					'text' => 'text comment',
					'message' => $textCommentMsg,
					'data' => null,
				]
			],
			'Simple table, text comment with data' => [
				'ipblocks', 'ipb_reason', 'ipb_id', $ipbfields, 'text comment', [ 'message' => 42 ], [
					'text' => 'text comment',
					'message' => $textCommentMsg,
					'data' => [ 'message' => 42 ],
				]
			],
			'Simple table, message comment' => [
				'ipblocks', 'ipb_reason', 'ipb_id', $ipbfields, $msgComment, null, [
					'text' => '(message comment)',
					'message' => $msgComment,
					'data' => null,
				]
			],
			'Simple table, message comment with data' => [
				'ipblocks', 'ipb_reason', 'ipb_id', $ipbfields, $msgComment, [ 'message' => 42 ], [
					'text' => '(message comment)',
					'message' => $msgComment,
					'data' => [ 'message' => 42 ],
				]
			],
			'Simple table, nested message comment' => [
				'ipblocks', 'ipb_reason', 'ipb_id', $ipbfields, $nestedMsgComment, null, [
					'text' => '(Main Page)',
					'message' => $nestedMsgComment,
					'data' => null,
				]
			],

			'Revision, text comment' => [
				'revision', 'rev_comment', 'rev_id', $revfields, 'text comment', null, [
					'text' => 'text comment',
					'message' => $textCommentMsg,
					'data' => null,
				]
			],
			'Revision, text comment with data' => [
				'revision', 'rev_comment', 'rev_id', $revfields, 'text comment', [ 'message' => 42 ], [
					'text' => 'text comment',
					'message' => $textCommentMsg,
					'data' => [ 'message' => 42 ],
				]
			],
			'Revision, message comment' => [
				'revision', 'rev_comment', 'rev_id', $revfields, $msgComment, null, [
					'text' => '(message comment)',
					'message' => $msgComment,
					'data' => null,
				]
			],
			'Revision, message comment with data' => [
				'revision', 'rev_comment', 'rev_id', $revfields, $msgComment, [ 'message' => 42 ], [
					'text' => '(message comment)',
					'message' => $msgComment,
					'data' => [ 'message' => 42 ],
				]
			],
			'Revision, nested message comment' => [
				'revision', 'rev_comment', 'rev_id', $revfields, $nestedMsgComment, null, [
					'text' => '(Main Page)',
					'message' => $nestedMsgComment,
					'data' => null,
				]
			],
		];
	}

	public static function provideStages() {
		return [
			'MIGRATION_OLD' => [ MIGRATION_OLD ],
			'MIGRATION_WRITE_BOTH' => [ MIGRATION_WRITE_BOTH ],
			'MIGRATION_WRITE_NEW' => [ MIGRATION_WRITE_NEW ],
			'MIGRATION_NEW' => [ MIGRATION_NEW ],
		];
	}

	/**
	 * @dataProvider provideStages
	 * @param int $stage
	 * @expectedException InvalidArgumentException
	 * @expectedExceptionMessage Must use insertWithTempTable() for rev_comment
	 */
	public function testInsertWrong( $stage ) {
		$store = $this->makeStore( $stage );
		$store->insert( 'rev_comment', 'foo' );
	}

	/**
	 * @dataProvider provideStages
	 * @param int $stage
	 * @expectedException InvalidArgumentException
	 * @expectedExceptionMessage Must use insert() for ipb_reason
	 */
	public function testInsertWithTempTableWrong( $stage ) {
		$store = $this->makeStore( $stage );
		$store->insertWithTempTable( 'ipb_reason', 'foo' );
	}

	/**
	 * @dataProvider provideStages
	 * @param int $stage
	 * @expectedException InvalidArgumentException
	 * @expectedExceptionMessage $data must be null or an array
	 */
	public function testInsertBadData( $stage ) {
		$store = $this->makeStore( $stage );
		$store->insert( 'ipb_reason', 'foo', 'bar' );
	}

	/**
	 * @dataProvider provideStages
	 * @param int $stage
	 */
	public function testInsertWithTempTableDeprecated( $stage ) {
		$wrap = TestingAccessWrapper::newFromClass( CommentStore::class );
		$wrap->formerTempTables += [ 'ipb_reason' => '1.30' ];

		$this->hideDeprecated( 'CommentStore::insertWithTempTable for ipb_reason' );
		$store = $this->makeStore( $stage );
		list( $fields, $callback ) = $store->insertWithTempTable( 'ipb_reason', 'foo' );
		$this->assertTrue( is_callable( $callback ) );
	}

}
