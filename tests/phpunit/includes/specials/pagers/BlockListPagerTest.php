<?php

use MediaWiki\Block\BlockActionInfo;
use MediaWiki\Block\BlockRestrictionStore;
use MediaWiki\Block\BlockUtils;
use MediaWiki\Block\DatabaseBlock;
use MediaWiki\Block\Restriction\NamespaceRestriction;
use MediaWiki\Block\Restriction\PageRestriction;
use MediaWiki\Cache\LinkBatchFactory;
use MediaWiki\CommentFormatter\RowCommentFormatter;
use MediaWiki\Linker\LinkRenderer;
use MediaWiki\MainConfigNames;
use MediaWiki\SpecialPage\SpecialPageFactory;
use Wikimedia\Rdbms\FakeResultWrapper;
use Wikimedia\Rdbms\ILoadBalancer;
use Wikimedia\TestingAccessWrapper;

/**
 * @group Database
 * @coversDefaultClass BlockListPager
 */
class BlockListPagerTest extends MediaWikiIntegrationTestCase {

	/** @var BlockActionInfo */
	private $blockActionInfo;

	/** @var BlockRestrictionStore */
	private $blockRestrictionStore;

	/** @var BlockUtils */
	private $blockUtils;

	/** @var CommentStore */
	private $commentStore;

	/** @var LinkRenderer */
	private $linkRenderer;

	/** @var LinkBatchFactory */
	private $linkBatchFactory;

	/** @var ILoadBalancer */
	private $loadBalancer;

	/** @var RowCommentFormatter */
	private $rowCommentFormatter;

	/** @var SpecialPageFactory */
	private $specialPageFactory;

	protected function setUp(): void {
		parent::setUp();

		$services = $this->getServiceContainer();
		$this->blockActionInfo = $services->getBlockActionInfo();
		$this->blockRestrictionStore = $services->getBlockRestrictionStore();
		$this->blockUtils = $services->getBlockUtils();
		$this->commentStore = $services->getCommentStore();
		$this->linkBatchFactory = $services->getLinkBatchFactory();
		$this->linkRenderer = $services->getLinkRenderer();
		$this->loadBalancer = $services->getDBLoadBalancer();
		$this->rowCommentFormatter = $services->getRowCommentFormatter();
		$this->specialPageFactory = $services->getSpecialPageFactory();
	}

	private function getBlockListPager() {
		return new BlockListPager(
			RequestContext::getMain(),
			$this->blockActionInfo,
			$this->blockRestrictionStore,
			$this->blockUtils,
			$this->commentStore,
			$this->linkBatchFactory,
			$this->linkRenderer,
			$this->loadBalancer,
			$this->rowCommentFormatter,
			$this->specialPageFactory,
			[]
		);
	}

	/**
	 * @covers ::formatValue
	 * @dataProvider formatValueEmptyProvider
	 * @dataProvider formatValueDefaultProvider
	 */
	public function testFormatValue( $name, $expected = null, $row = null ) {
		// Set the time to now so it does not get off during the test.
		MWTimestamp::setFakeTime( MWTimestamp::time() );

		$value = $name === 'ipb_timestamp' ? MWTimestamp::time() : '';
		$expected = $expected ?? MWTimestamp::getInstance()->format( 'H:i, j F Y' );

		$row = $row ?: (object)[];
		$pager = $this->getBlockListPager();
		$wrappedPager = TestingAccessWrapper::newFromObject( $pager );
		$wrappedPager->mCurrentRow = $row;

		$formatted = $pager->formatValue( $name, $value );
		$this->assertStringMatchesFormat( $expected, $formatted );
	}

	/**
	 * Test empty values.
	 */
	public function formatValueEmptyProvider() {
		return [
			[
				'test',
				'Unable to format test',
			],
			[
				'ipb_timestamp',
			],
			[
				'ipb_expiry',
				'infinite<br />0 minutes left',
			],
		];
	}

	/**
	 * Test the default row values.
	 */
	public function formatValueDefaultProvider() {
		$row = (object)[
			'ipb_user' => 0,
			'ipb_address' => '127.0.0.1',
			'ipb_by_text' => 'Admin',
			'ipb_create_account' => 1,
			'ipb_auto' => 0,
			'ipb_anon_only' => 0,
			'ipb_create_account' => 1,
			'ipb_enable_autoblock' => 1,
			'ipb_deleted' => 0,
			'ipb_block_email' => 0,
			'ipb_allow_usertalk' => 0,
			'ipb_sitewide' => 1,
		];

		return [
			[
				'test',
				'Unable to format test',
				$row,
			],
			[
				'ipb_timestamp',
				null,
				$row,
			],
			[
				'ipb_expiry',
				'infinite<br />0 minutes left',
				$row,
			],
			[
				'ipb_by',
				'<a %s><bdi>Admin</bdi></a>%s',
				$row,
			],
			[
				'ipb_params',
				'<ul><li>editing (sitewide)</li>' .
					'<li>account creation disabled</li><li>cannot edit own talk page</li></ul>',
				$row,
			]
		];
	}

	/**
	 * @covers ::formatValue
	 * @covers ::getRestrictionListHTML
	 */
	public function testFormatValueRestrictions() {
		$this->overrideConfigValues( [
			MainConfigNames::ArticlePath => '/wiki/$1',
			MainConfigNames::Script => '/w/index.php',
		] );

		$pager = $this->getBlockListPager();

		$row = (object)[
			'ipb_id' => 0,
			'ipb_user' => 0,
			'ipb_anon_only' => 0,
			'ipb_enable_autoblock' => 0,
			'ipb_create_account' => 0,
			'ipb_block_email' => 0,
			'ipb_allow_usertalk' => 1,
			'ipb_sitewide' => 0,
		];
		$wrappedPager = TestingAccessWrapper::newFromObject( $pager );
		$wrappedPager->mCurrentRow = $row;

		$pageName = 'Victor Frankenstein';
		$page = $this->insertPage( $pageName );
		$title = $page['title'];
		$pageId = $page['id'];

		$restrictions = [
			( new PageRestriction( 0, $pageId ) )->setTitle( $title ),
			new NamespaceRestriction( 0, NS_MAIN ),
			// Deleted page.
			new PageRestriction( 0, 999999 ),
		];

		$wrappedPager = TestingAccessWrapper::newFromObject( $pager );
		$wrappedPager->restrictions = $restrictions;

		$formatted = $pager->formatValue( 'ipb_params', '' );
		$this->assertEquals( '<ul><li>'
			// FIXME: Expectation value should not be dynamic
			// and must not depend on a localisation message.
			// TODO: Mock the message or consider using qqx.
			. wfMessage( 'blocklist-editing' )->text()
			. '<ul><li>'
			. wfMessage( 'blocklist-editing-page' )->text()
			. '<ul><li>'
			. '<a href="/wiki/Victor_Frankenstein" title="'
			. $pageName
			. '">'
			. $pageName
			. '</a></li></ul></li><li>'
			. wfMessage( 'blocklist-editing-ns' )->text()
			. '<ul><li>'
			. '<a href="/w/index.php?title=Special:AllPages&amp;namespace=0" title="'
			. 'Special:AllPages'
			. '">'
			. wfMessage( 'blanknamespace' )->text()
			. '</a></li></ul></li></ul></li></ul>',
			$formatted
		);
	}

	/**
	 * @covers ::preprocessResults
	 */
	public function testPreprocessResults() {
		$this->tablesUsed[] = 'ipblocks';
		$this->tablesUsed[] = 'ipblocks_restrictions';
		$this->tablesUsed[] = 'comment';
		$this->tablesUsed[] = 'page';
		$this->tablesUsed[] = 'user';

		// Test the Link Cache.
		$linkCache = $this->getServiceContainer()->getLinkCache();
		$wrappedlinkCache = TestingAccessWrapper::newFromObject( $linkCache );
		$admin = $this->getTestSysop()->getUser();

		$links = [
			'User:127.0.0.1',
			'User_talk:127.0.0.1',
			$admin->getUserPage()->getPrefixedDBkey(),
			$admin->getTalkPage()->getPrefixedDBkey(),
			'Comment_link'
		];

		foreach ( $links as $link ) {
			$this->assertNull( $wrappedlinkCache->badLinks->get( $link ) );
		}

		$row = (object)[
			'ipb_address' => '127.0.0.1',
			'ipb_by' => $admin->getId(),
			'ipb_by_text' => $admin->getName(),
			'ipb_sitewide' => 1,
			'ipb_timestamp' => $this->db->timestamp( wfTimestamp( TS_MW ) ),
			'ipb_reason_text' => '[[Comment link]]',
			'ipb_reason_data' => null,
		];
		$pager = $this->getBlockListPager();
		$pager->preprocessResults( new FakeResultWrapper( [ $row ] ) );

		foreach ( $links as $link ) {
			$this->assertSame( 1, $wrappedlinkCache->badLinks->get( $link ), "Bad link [[$link]]" );
		}

		// Test sitewide blocks.
		$row = (object)[
			'ipb_address' => '127.0.0.1',
			'ipb_by' => $admin->getId(),
			'ipb_by_text' => $admin->getName(),
			'ipb_sitewide' => 1,
			'ipb_reason_text' => '',
			'ipb_reason_data' => null,
		];
		$pager = $this->getBlockListPager();
		$pager->preprocessResults( new FakeResultWrapper( [ $row ] ) );

		$this->assertObjectNotHasAttribute( 'ipb_restrictions', $row );

		$pageName = 'Victor Frankenstein';
		$page = $this->getExistingTestPage( 'Victor Frankenstein' );
		$title = $page->getTitle();

		$target = '127.0.0.1';

		// Test partial blocks.
		$block = new DatabaseBlock( [
			'address' => $target,
			'by' => $this->getTestSysop()->getUser(),
			'reason' => 'Parce que',
			'expiry' => $this->db->getInfinity(),
			'sitewide' => false,
		] );
		$block->setRestrictions( [
			new PageRestriction( 0, $page->getId() ),
		] );
		$blockStore = $this->getServiceContainer()->getDatabaseBlockStore();
		$blockStore->insertBlock( $block );

		$result = $this->db->newSelectQueryBuilder()
			->queryInfo( DatabaseBlock::getQueryInfo() )
			->where( [ 'ipb_id' => $block->getId() ] )
			->caller( __METHOD__ )
			->fetchResultSet();

		$pager = $this->getBlockListPager();
		$pager->preprocessResults( $result );

		$wrappedPager = TestingAccessWrapper::newFromObject( $pager );

		$restrictions = $wrappedPager->restrictions;
		$this->assertIsArray( $restrictions );

		$restriction = $restrictions[0];
		$this->assertEquals( $page->getId(), $restriction->getValue() );
		$this->assertEquals( $page->getId(), $restriction->getTitle()->getArticleID() );
		$this->assertEquals( $title->getDBkey(), $restriction->getTitle()->getDBkey() );
		$this->assertEquals( $title->getNamespace(), $restriction->getTitle()->getNamespace() );
	}
}
