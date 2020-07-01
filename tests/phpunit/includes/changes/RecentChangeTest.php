<?php

use PHPUnit\Framework\MockObject\MockObject;
use Wikimedia\ScopedCallback;

/**
 * @group Database
 */
class RecentChangeTest extends MediaWikiIntegrationTestCase {
	protected $title;
	protected $target;
	protected $user;
	protected $user_comment;
	protected $context;

	protected function setUp() : void {
		parent::setUp();

		$this->title = Title::newFromText( 'SomeTitle' );
		$this->target = Title::newFromText( 'TestTarget' );
		$this->user = User::newFromName( 'UserName' );

		$this->user_comment = '<User comment about action>';
		$this->context = RequestContext::newExtraneousContext( $this->title );
	}

	/**
	 * @covers RecentChange::newFromRow
	 * @covers RecentChange::loadFromRow
	 */
	public function testNewFromRow() {
		$user = $this->getTestUser()->getUser();
		$actorId = $user->getActorId();

		$row = (object)[
			'rc_foo' => 'AAA',
			'rc_timestamp' => '20150921134808',
			'rc_deleted' => 'bar',
			'rc_comment_text' => 'comment',
			'rc_comment_data' => null,
			'rc_user' => $user->getId(),
		];

		$rc = RecentChange::newFromRow( $row );

		$expected = [
			'rc_foo' => 'AAA',
			'rc_timestamp' => '20150921134808',
			'rc_deleted' => 'bar',
			'rc_comment' => 'comment',
			'rc_comment_text' => 'comment',
			'rc_comment_data' => null,
			'rc_user' => $user->getId(),
			'rc_user_text' => $user->getName(),
			'rc_actor' => $actorId,
		];
		$this->assertEquals( $expected, $rc->getAttributes() );

		$row = (object)[
			'rc_foo' => 'AAA',
			'rc_timestamp' => '20150921134808',
			'rc_deleted' => 'bar',
			'rc_comment' => 'comment',
			'rc_user' => $user->getId(),
		];

		Wikimedia\suppressWarnings();
		$rc = RecentChange::newFromRow( $row );
		Wikimedia\restoreWarnings();

		$expected = [
			'rc_foo' => 'AAA',
			'rc_timestamp' => '20150921134808',
			'rc_deleted' => 'bar',
			'rc_comment' => 'comment',
			'rc_comment_text' => 'comment',
			'rc_comment_data' => null,
			'rc_user' => $user->getId(),
			'rc_user_text' => $user->getName(),
			'rc_actor' => $actorId,
		];
		$this->assertEquals( $expected, $rc->getAttributes() );
	}

	/**
	 * @covers RecentChange::parseParams
	 */
	public function testParseParams() {
		$params = [
			'root' => [
				'A' => 1,
				'B' => 'two'
			]
		];

		$this->assertParseParams(
			$params,
			'a:1:{s:4:"root";a:2:{s:1:"A";i:1;s:1:"B";s:3:"two";}}'
		);

		$this->assertParseParams(
			null,
			null
		);

		$this->assertParseParams(
			null,
			serialize( false )
		);

		$this->assertParseParams(
			null,
			'not-an-array'
		);
	}

	/**
	 * @param array $expectedParseParams
	 * @param string|null $rawRcParams
	 */
	protected function assertParseParams( $expectedParseParams, $rawRcParams ) {
		$rc = new RecentChange;
		$rc->setAttribs( [ 'rc_params' => $rawRcParams ] );

		$actualParseParams = $rc->parseParams();

		$this->assertEquals( $expectedParseParams, $actualParseParams );
	}

	/**
	 * @return array
	 */
	public function provideIsInRCLifespan() {
		return [
			[ 6000, -3000, 0, true ],
			[ 3000, -6000, 0, false ],
			[ 6000, -3000, 6000, true ],
			[ 3000, -6000, 6000, true ],
		];
	}

	/**
	 * @covers RecentChange::isInRCLifespan
	 * @dataProvider provideIsInRCLifespan
	 */
	public function testIsInRCLifespan( $maxAge, $offset, $tolerance, $expected ) {
		$this->setMwGlobals( 'wgRCMaxAge', $maxAge );
		// Calculate this here instead of the data provider because the provider
		// is expanded early on and the full test suite may take longer than 100 minutes
		// when coverage is enabled.
		$timestamp = time() + $offset;
		$this->assertEquals( $expected, RecentChange::isInRCLifespan( $timestamp, $tolerance ) );
	}

	public function provideRCTypes() {
		return [
			[ RC_EDIT, 'edit' ],
			[ RC_NEW, 'new' ],
			[ RC_LOG, 'log' ],
			[ RC_EXTERNAL, 'external' ],
			[ RC_CATEGORIZE, 'categorize' ],
		];
	}

	/**
	 * @dataProvider provideRCTypes
	 * @covers RecentChange::parseFromRCType
	 */
	public function testParseFromRCType( $rcType, $type ) {
		$this->assertEquals( $type, RecentChange::parseFromRCType( $rcType ) );
	}

	/**
	 * @dataProvider provideRCTypes
	 * @covers RecentChange::parseToRCType
	 */
	public function testParseToRCType( $rcType, $type ) {
		$this->assertEquals( $rcType, RecentChange::parseToRCType( $type ) );
	}

	/**
	 * @return MockObject|PageProps
	 */
	private function getMockPageProps() {
		return $this->getMockBuilder( PageProps::class )
			->disableOriginalConstructor()
			->getMock();
	}

	public function provideCategoryContent() {
		return [
			[ true ],
			[ false ],
		];
	}

	/**
	 * @dataProvider provideCategoryContent
	 * @covers RecentChange::newForCategorization
	 */
	public function testHiddenCategoryChange( $isHidden ) {
		$categoryTitle = Title::newFromText( 'CategoryPage', NS_CATEGORY );

		$pageProps = $this->getMockPageProps();
		$pageProps->expects( $this->once() )
			->method( 'getProperties' )
			->with( $categoryTitle, 'hiddencat' )
			->will( $this->returnValue( $isHidden ? [ $categoryTitle->getArticleID() => '' ] : [] ) );

		$scopedOverride = PageProps::overrideInstance( $pageProps );

		$rc = RecentChange::newForCategorization(
			'0',
			$categoryTitle,
			$this->user,
			$this->user_comment,
			$this->title,
			$categoryTitle->getLatestRevID(),
			$categoryTitle->getLatestRevID(),
			'0',
			false
		);

		$this->assertEquals( $isHidden, $rc->getParam( 'hidden-cat' ) );

		ScopedCallback::consume( $scopedOverride );
	}
}
