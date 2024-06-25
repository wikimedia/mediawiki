<?php

use MediaWiki\CommentStore\CommentStore;
use MediaWiki\CommentStore\CommentStoreComment;
use MediaWiki\Language\RawMessage;
use MediaWiki\Message\Message;
use Wikimedia\Rdbms\IMaintainableDatabase;

/**
 * @group Database
 * @covers \MediaWiki\CommentStore\CommentStore
 * @covers \MediaWiki\CommentStore\CommentStoreComment
 */
class CommentStoreTest extends MediaWikiLangTestCase {

	protected function getSchemaOverrides( IMaintainableDatabase $db ) {
		return [
			'scripts' => [
				__DIR__ . '/CommentStoreTest.sql',
			],
			'drop' => [],
			'create' => [ 'commentstore1', 'commentstore2', 'commentstore2_temp' ],
			'alter' => [],
		];
	}

	/**
	 * Create a store for a particular stage
	 * @return CommentStore
	 */
	private function makeStore() {
		$lang = $this->createMock( Language::class );
		$lang->method( 'truncateForDatabase' )->willReturnCallback( static function ( $str, $len ) {
			return strlen( $str ) > $len ? substr( $str, 0, $len - 3 ) . '...' : $str;
		} );
		$lang->method( 'truncateForVisual' )->willReturnCallback( static function ( $str, $len ) {
			return mb_strlen( $str ) > $len ? mb_substr( $str, 0, $len - 3 ) . '...' : $str;
		} );
		return new CommentStore( $lang );
	}

	/**
	 * @dataProvider provideGetJoin
	 * @param string $key
	 * @param array $expect
	 */
	public function testGetJoin( $key, $expect ) {
		$store = $this->makeStore();
		$result = $store->getJoin( $key );
		$this->assertEquals( $expect, $result );
	}

	public static function provideGetJoin() {
		return [
			'Simple table' => [
				'ipb_reason', [
					'tables' => [ 'comment_ipb_reason' => 'comment' ],
					'fields' => [
						'ipb_reason_text' => 'comment_ipb_reason.comment_text',
						'ipb_reason_data' => 'comment_ipb_reason.comment_data',
						'ipb_reason_cid' => 'comment_ipb_reason.comment_id',
					],
					'joins' => [
						'comment_ipb_reason' => [ 'JOIN', 'comment_ipb_reason.comment_id = ipb_reason_id' ],
					],
				],
			],

			'Revision' => [
				'rev_comment', [
					'tables' => [
						'comment_rev_comment' => 'comment',
					],
					'fields' => [
						'rev_comment_text' => 'comment_rev_comment.comment_text',
						'rev_comment_data' => 'comment_rev_comment.comment_data',
						'rev_comment_cid' => 'comment_rev_comment.comment_id',
					],
					'joins' => [
						'comment_rev_comment' => [ 'JOIN', 'comment_rev_comment.comment_id = rev_comment_id' ],
					],
				],
			],

			'Image' => [
				'img_description', [
					'tables' => [
						'comment_img_description' => 'comment',
					],
					'fields' => [
						'img_description_text' => 'comment_img_description.comment_text',
						'img_description_data' => 'comment_img_description.comment_data',
						'img_description_cid' => 'comment_img_description.comment_id',
					],
					'joins' => [
						'comment_img_description' => [ 'JOIN',
							'comment_img_description.comment_id = img_description_id',
						],
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
		$this->assertEquals( $expect['text'], $actual->message->text(),
			"message rendering and text $from" );
		$this->assertEquals( $expect['data'], $actual->data, "data $from" );
	}

	/**
	 * @dataProvider provideInsertRoundTrip
	 * @param string $table
	 * @param string $key
	 * @param string $pk
	 * @param string|Message $comment
	 * @param array|null $data
	 * @param array $expect
	 */
	public function testInsertRoundTrip( $table, $key, $pk, $comment, $data, $expect ) {
		static $id = 1;

		$wstore = $this->makeStore();

		$fields = $wstore->insert( $this->getDb(), $key, $comment, $data );

		$this->assertArrayNotHasKey( $key, $fields, "old field" );
		$this->assertArrayHasKey( "{$key}_id", $fields, "new field" );

		$this->getDb()->newInsertQueryBuilder()
			->insertInto( $table )
			->row( [ $pk => ++$id ] + $fields )
			->caller( __METHOD__ )
			->execute();

		$rstore = $this->makeStore();

		$fieldRow = $this->getDb()->newSelectQueryBuilder()
			->select( [ "{$key}_id" => "{$key}_id" ] )
			->from( $table )
			->where( [ $pk => $id ] )
			->caller( __METHOD__ )->fetchRow();

		$queryInfo = $rstore->getJoin( $key );
		$joinRow = $this->getDb()->newSelectQueryBuilder()
			->queryInfo( $queryInfo )
			->from( $table )
			->where( [ $pk => $id ] )
			->caller( __METHOD__ )
			->fetchRow();

		$this->assertComment(
			$expect,
			$rstore->getCommentLegacy( $this->getDb(), $key, $fieldRow ),
			"from getFields()"
		);
		$this->assertComment(
			$expect,
			$rstore->getComment( $key, $joinRow ),
			"from getJoin()"
		);
	}

	public static function provideInsertRoundTrip() {
		$msgComment = new Message( 'parentheses', [ 'message comment' ] );
		$textCommentMsg = new RawMessage( '$1', [ Message::plaintextParam( '{{text}} comment' ) ] );
		$nestedMsgComment = new Message( [ 'parentheses', 'rawmessage' ], [ new Message( 'mainpage' ) ] );
		$comStoreComment = new CommentStoreComment(
			null, 'comment store comment', null, [ 'foo' => 'bar' ]
		);

		return [
			'Simple table, text comment' => [
				'commentstore1', 'cs1_comment', 'cs1_id', '{{text}} comment', null, [
					'text' => '{{text}} comment',
					'message' => $textCommentMsg,
					'data' => null,
				]
			],
			'Simple table, text comment with data' => [
				'commentstore1', 'cs1_comment', 'cs1_id', '{{text}} comment', [ 'message' => 42 ], [
					'text' => '{{text}} comment',
					'message' => $textCommentMsg,
					'data' => [ 'message' => 42 ],
				]
			],
			'Simple table, message comment' => [
				'commentstore1', 'cs1_comment', 'cs1_id', $msgComment, null, [
					'text' => '(message comment)',
					'message' => $msgComment,
					'data' => null,
				]
			],
			'Simple table, message comment with data' => [
				'commentstore1', 'cs1_comment', 'cs1_id', $msgComment, [ 'message' => 42 ], [
					'text' => '(message comment)',
					'message' => $msgComment,
					'data' => [ 'message' => 42 ],
				]
			],
			'Simple table, nested message comment' => [
				'commentstore1', 'cs1_comment', 'cs1_id', $nestedMsgComment, null, [
					'text' => '(Main Page)',
					'message' => $nestedMsgComment,
					'data' => null,
				]
			],
			'Simple table, CommentStoreComment' => [
				'commentstore1', 'cs1_comment', 'cs1_id', clone $comStoreComment, [ 'baz' => 'baz' ], [
					'text' => 'comment store comment',
					'message' => $comStoreComment->message,
					'data' => [ 'foo' => 'bar' ],
				]
			],
		];
	}

	public function testGetCommentErrors() {
		$store = $this->makeStore();
		try {
			$store->getComment( 'dummy', [ 'dummy' => 'comment' ] );
			$this->fail( 'Expected exception not thrown' );
		} catch ( InvalidArgumentException $ex ) {
			$this->assertSame( '$row does not contain fields needed for comment dummy', $ex->getMessage() );
		}
		// Ignore: Using deprecated fallback handling for comment dummy
		$res = @$store->getComment( 'dummy', [ 'dummy' => 'comment' ], true );
		$this->assertSame( 'comment', $res->text );
		try {
			$store->getComment( 'dummy', [ 'dummy_id' => 1 ] );
			$this->fail( 'Expected exception not thrown' );
		} catch ( InvalidArgumentException $ex ) {
			$this->assertSame(
				'$row does not contain fields needed for comment dummy and getComment(), '
				. 'but does have fields for getCommentLegacy()',
				$ex->getMessage()
			);
		}

		$store = $this->makeStore();
		try {
			$store->getComment( 'rev_comment', [ 'rev_comment' => 'comment' ] );
			$this->fail( 'Expected exception not thrown' );
		} catch ( InvalidArgumentException $ex ) {
			$this->assertSame(
				'$row does not contain fields needed for comment rev_comment', $ex->getMessage()
			);
		}
		$res = @$store->getComment( 'rev_comment', [ 'rev_comment' => 'comment' ], true );
		$this->assertSame( 'comment', $res->text );
		try {
			$store->getComment( 'rev_comment', [ 'rev_comment_id' => 1 ] );
			$this->fail( 'Expected exception not thrown' );
		} catch ( InvalidArgumentException $ex ) {
			$this->assertSame(
				'$row does not contain fields needed for comment rev_comment and getComment(), '
				. 'but does have fields for getCommentLegacy()',
				$ex->getMessage()
			);
		}
	}

	public function testInsertTruncation() {
		$comment = str_repeat( '💣', 16400 );
		$truncated = str_repeat( '💣', CommentStore::COMMENT_CHARACTER_LIMIT - 3 ) . '...';

		$store = $this->makeStore();
		$fields = $store->insert( $this->getDb(), 'ipb_reason', $comment );
		$stored = $this->getDb()->newSelectQueryBuilder()
			->select( 'comment_text' )
			->from( 'comment' )
			->where( [ 'comment_id' => $fields['ipb_reason_id'] ] )
			->caller( __METHOD__ )->fetchField();
		$this->assertSame( $truncated, $stored );
	}

	public function testInsertTooMuchData() {
		$store = $this->makeStore();
		$this->expectException( OverflowException::class );
		$this->expectExceptionMessage( "Comment data is too long (65611 bytes, maximum is 65535)" );
		$store->insert( $this->getDb(), 'ipb_reason', 'foo', [
			'long' => str_repeat( '💣', 16400 )
		] );
	}

}
