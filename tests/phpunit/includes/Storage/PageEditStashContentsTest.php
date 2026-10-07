<?php
declare( strict_types = 1 );

namespace MediaWiki\Tests\Storage;

use MediaWiki\Content\WikitextContent;
use MediaWiki\MediaWikiServices;
use MediaWiki\Parser\ParserOutput;
use MediaWiki\Storage\PageEditStashContents;
use MediaWikiIntegrationTestCase;
use Wikimedia\TestingAccessWrapper;
use Wikimedia\Tests\SerializationTestTrait;

/**
 * @covers \MediaWiki\Storage\PageEditStashContents
 */
class PageEditStashContentsTest extends MediaWikiIntegrationTestCase {
	use SerializationTestTrait;

	public static function getTestInstancesAndAssertions(): array {
		$po = new ParserOutput();
		$timestamp = '20250701173710';
		$po->setCacheTime( $timestamp );
		$po->setExtensionData( 'test1', 'test2' );
		$po->clearParseStartTime();
		$po2 = clone $po;
		$po2->setContentHolderText( 'world' );
		return [
			'basic' => [
				'instance' => new PageEditStashContents(
					pstContent: new WikitextContent( 'hello' ),
					output: $po,
					optionsHash: null,
					timestamp: $timestamp,
					edits: 42,
				),
				'assertions' => static function ( $testCase, $obj ) use ( $timestamp ) {
					$testCase->assertInstanceof( PageEditStashContents::class, $obj );
					$testCase->assertInstanceof( WikitextContent::class, $obj->pstContent );
					$testCase->assertInstanceof( ParserOutput::class, $obj->output );
					$testCase->assertSame( $timestamp, $obj->output->getCacheTime() );
					$testCase->assertSame( null, $obj->optionsHash );
					$testCase->assertSame( $timestamp, $obj->timestamp );
					$testCase->assertSame( 42, $obj->edits );
					$testCase->assertSame( 'test2', $obj->output->getExtensionData( 'test1' ) );
				},
			],
			'withHash' => [
				'instance' => new PageEditStashContents(
					pstContent: new WikitextContent( 'world' ),
					output: $po2,
					optionsHash: 'hash',
					timestamp: $timestamp,
					edits: 67,
				),
				'assertions' => static function ( $testCase, $obj ) use ( $timestamp ) {
					$testCase->assertInstanceof( PageEditStashContents::class, $obj );
					$testCase->assertInstanceof( WikitextContent::class, $obj->pstContent );
					$testCase->assertInstanceof( ParserOutput::class, $obj->output );
					$testCase->assertSame( $timestamp, $obj->output->getCacheTime() );
					$testCase->assertSame( 'hash', $obj->optionsHash );
					$testCase->assertSame( $timestamp, $obj->timestamp );
					$testCase->assertSame( 67, $obj->edits );
					$testCase->assertSame( 'test2', $obj->output->getExtensionData( 'test1' ) );
					$testCase->assertSame( 'world', $obj->output->getContentHolderText() );
				},
			],
		];
	}

	public static function getClassToTest(): string {
		return PageEditStashContents::class;
	}

	public static function getSerializedDataPath(): string {
		return __DIR__ . '/../../data/PageEditStashContents';
	}

	public static function getSupportedSerializationFormats(): array {
		// Use serialization/deserialization logic from PageEditStash
		$pageEditStash = MediaWikiServices::getInstance()->getPageEditStash();
		$wrapped = TestingAccessWrapper::newFromObject( $pageEditStash );
		return [ [
			'ext' => 'json',
			'serializer' => static function ( $obj ) use ( $wrapped ) {
				return $wrapped->serializeStashInfo( $obj );
			},
			'deserializer' => static function ( $data ) use ( $wrapped ) {
				return $wrapped->unserializeStashInfo( $data );
			},
		] ];
	}
}
