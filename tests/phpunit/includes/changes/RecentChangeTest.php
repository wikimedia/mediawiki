<?php
use Wikimedia\ScopedCallback;

/**
 * @group Database
 */
class RecentChangeTest extends MediaWikiTestCase {
	protected $title;
	protected $target;
	protected $user;
	protected $user_comment;
	protected $context;

	public function setUp() {
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
		$row = new stdClass();
		$row->rc_foo = 'AAA';
		$row->rc_timestamp = '20150921134808';
		$row->rc_deleted = 'bar';

		$rc = RecentChange::newFromRow( $row );

		$expected = [
			'rc_foo' => 'AAA',
			'rc_timestamp' => '20150921134808',
			'rc_deleted' => 'bar',
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
	 * 50 mins and 100 mins are used here as the tests never take that long!
	 * @return array
	 */
	public function provideIsInRCLifespan() {
		return [
			[ 6000, time() - 3000, 0, true ],
			[ 3000, time() - 6000, 0, false ],
			[ 6000, time() - 3000, 6000, true ],
			[ 3000, time() - 6000, 6000, true ],
		];
	}

	/**
	 * @covers RecentChange::isInRCLifespan
	 * @dataProvider provideIsInRCLifespan
	 */
	public function testIsInRCLifespan( $maxAge, $timestamp, $tolerance, $expected ) {
		$this->setMwGlobals( 'wgRCMaxAge', $maxAge );
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
	 * @return PHPUnit_Framework_MockObject_MockObject|PageProps
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
