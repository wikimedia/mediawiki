<?php

namespace MediaWiki\Api\TypeDef;

use ApiBase;
use ApiMain;
use ApiUsageException;
use ChangeTags;
use MediaWikiLangTestCase;
use MockApi;
use RequestContext;
use Wikimedia\TestingAccessWrapper;

/**
 * @group API
 * @covers MediaWiki\Api\TypeDef\TagsDef
 */
class TagsDefTest extends MediaWikiLangTestCase {

	protected function setUp() {
		parent::setUp();
		ChangeTags::defineTag( 'tag1' );
		ChangeTags::defineTag( 'tag2' );

		$this->tablesUsed[] = 'change_tag_def';
		$this->tablesUsed[] = 'valid_tag';

		// Since the type def shouldn't care about the specific user,
		// remove the right from relevant groups to ensure that it's not
		// checking.
		$this->setGroupPermissions( [
			'*' => [ 'applychangetags' => false ],
			'user' => [ 'applychangetags' => false ],
		] );
	}

	/** @dataProvider provideValidate */
	public function testValidate( $value, $expect, $valuesList = null ) {
		$typeDef = new TagsDef;
		$api = new MockApi;
		$w = TestingAccessWrapper::newFromObject( $api );
		$w->mMainModule = new ApiMain;
		$w->mModulePrefix = 'tt';

		$settings = [];
		if ( $valuesList !== null ) {
			$settings['values-list'] = $valuesList;
		}

		if ( $expect instanceof ApiUsageException ) {
			$this->setExpectedException( ApiUsageException::class, $expect->getMessage() );
			$typeDef->validate( 'foobar', $value, $settings, $api );
		} else {
			$this->assertSame( $expect, $typeDef->validate( 'foobar', $value, $settings, $api ) );
			$this->assertSame( [], $api->warnings );
		}
	}

	public static function provideValidate() {
		return [
			'Basic' => [ 'tag1', [ 'tag1' ] ],
			'Bad tag' => [ 'doesnotexist',
				ApiUsageException::newWithMessage( null, [ 'tags-apply-not-allowed-one', 'doesnotexist' ] )
			],
			'Multi' => [ 'tag1', 'tag1', [ 'tag1', 'tag2' ] ],
			'Multi with bad tag' => [
				'tag1',
				ApiUsageException::newWithMessage( null, [ 'tags-apply-not-allowed-one', 'doesnotexist' ] ),
				[ 'tag1', 'doesnotexist' ],
			],
		];
	}

	public function testGetEnumValues() {
		$typeDef = new TagsDef;
		$tags = ChangeTags::listExplicitlyDefinedTags();
		$this->assertSame( $tags, $typeDef->getEnumValues( 'foobar', [], new MockApi ) );
	}

	public function testGetHelpInfo() {
		$typeDef = new TagsDef;
		$tags = ChangeTags::listExplicitlyDefinedTags();

		$settings = [];
		$this->assertSame( [
			'One of the following values: ' . implode( ', ', $tags ),
		], $typeDef->getHelpInfo( RequestContext::getMain(), 'foobar', $settings, new MockApi ) );

		$settings = [
			ApiBase::PARAM_ISMULTI => true,
		];
		$this->assertSame( [
			// phpcs:disable Generic.Files.LineLength
			'Values (separate with <kbd>|</kbd> or <a href="/wiki/Special:ApiHelp/main#main.2Fdatatypes" title="Special:ApiHelp/main">alternative</a>): '
				. implode( ', ', $tags ),
			// phpcs:enable
		], $typeDef->getHelpInfo( RequestContext::getMain(), 'foobar', $settings, new MockApi ) );
	}

	public function testGetParamInfo() {
		$typeDef = new TagsDef;
		$tags = ChangeTags::listExplicitlyDefinedTags();

		$this->assertSame( [
			'type' => $tags,
		], $typeDef->getParamInfo( 'foobar', [], new MockApi ) );

		$this->assertSame( [
			'default' => '',
			'type' => $tags,
		], $typeDef->getParamInfo( 'foobar', [ ApiBase::PARAM_DFLT => '' ], new MockApi ) );
	}

	public function testNeedsHelpParamMultiSeparate() {
		$typeDef = new TagsDef;
		$this->assertFalse( $typeDef->needsHelpParamMultiSeparate() );
	}

}
