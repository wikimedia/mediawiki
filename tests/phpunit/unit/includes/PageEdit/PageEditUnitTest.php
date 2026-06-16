<?php

namespace MediaWiki\Test\Unit\PageEdit;

use MediaWiki\Config\ServiceOptions;
use MediaWiki\Content\IContentHandlerFactory;
use MediaWiki\Content\Transform\ContentTransformer;
use MediaWiki\Context\IContextSource;
use MediaWiki\EditPage\Constraint\EditConstraintFactory;
use MediaWiki\EditPage\PageEditingHelper;
use MediaWiki\Language\Language;
use MediaWiki\MainConfigNames;
use MediaWiki\Page\ProperPageIdentity;
use MediaWiki\Page\WikiPageFactory;
use MediaWiki\PageEdit\PageEdit;
use MediaWiki\PageEdit\PageEditInputs;
use MediaWiki\Permissions\Authority;
use MediaWiki\Permissions\RateLimiter;
use MediaWiki\Revision\RevisionStore;
use MediaWiki\ShadowPage\ShadowPageLoader;
use MediaWiki\Tests\Unit\Permissions\MockAuthorityTrait;
use MediaWiki\Title\TitleFormatter;
use MediaWiki\User\Options\UserOptionsLookup;
use MediaWiki\Watchlist\WatchedItemStoreInterface;
use MediaWiki\Watchlist\WatchlistManager;
use MediaWikiUnitTestCase;
use Psr\Log\LoggerInterface;
use Wikimedia\Rdbms\IConnectionProvider;
use Wikimedia\TestingAccessWrapper;

/**
 * @covers \MediaWiki\PageEdit\PageEdit
 * @covers \MediaWiki\PageEdit\PageEditInputs
 */
class PageEditUnitTest extends MediaWikiUnitTestCase {
	use MockAuthorityTrait;

	/**
	 * @return PageEdit
	 */
	private function newPageEdit(
		PageEditInputs $inputs,
	) {
		return TestingAccessWrapper::newFromObject( new PageEdit(
			$inputs,
			new ServiceOptions( PageEdit::CONSTRUCTOR_OPTIONS, [
				MainConfigNames::EnableWatchlistLabels => false,
				MainConfigNames::UseNPPatrol => true,
				MainConfigNames::UseRCPatrol => true,
			] ),
			$this->createMock( IContentHandlerFactory::class ),
			$this->createMock( EditConstraintFactory::class ),
			$this->createMock( IConnectionProvider::class ),
			$this->createMock( Language::class ),
			$this->createMock( ContentTransformer::class ),
			$this->createMock( LoggerInterface::class ),
			$this->createMock( PageEditingHelper::class ),
			$this->createMock( RateLimiter::class ),
			$this->createMock( RevisionStore::class ),
			$this->createMock( ShadowPageLoader::class ),
			$this->createMock( TitleFormatter::class ),
			$this->createMock( UserOptionsLookup::class ),
			$this->createMock( WatchlistManager::class ),
			$this->createMock( WatchedItemStoreInterface::class ),
			$this->createMock( WikiPageFactory::class ),
		) );
	}

	private function createInputs(
		?Authority $authority = null,
		bool $markAsBot = false,
		bool $markAsMinor = false,
		string $section = '',
	): PageEditInputs {
		$inputs = new PageEditInputs(
			authority: $authority ?? $this->mockAnonNullAuthority(),
			contentModel: CONTENT_MODEL_WIKITEXT,
			context: $this->createMock( IContextSource::class ),
			page: $this->createMock( ProperPageIdentity::class ),
			summary: 'Test edit summary',
			textbox1: 'Test edit text',
		);

		$inputs->setMarkAsBot( $markAsBot );
		$inputs->setMarkAsMinor( $markAsMinor );
		$inputs->setSection( $section );

		return $inputs;
	}

	/**
	 * @dataProvider provideTestIsMinorEdit
	 */
	public function testIsMinorEdit(
		bool $hasPermission,
		bool $markAsMinor,
		bool $isNewPage,
		string $section,
		bool $expected,
	) {
		$authority = $hasPermission
			? $this->mockRegisteredAuthorityWithPermissions( [ 'minoredit' ] )
			: $this->mockRegisteredAuthorityWithoutPermissions( [ 'minoredit' ] );
		$inputs = $this->createInputs(
			authority: $authority,
			markAsMinor: $markAsMinor,
			section: $section,
		);
		$this->assertEquals( $expected, $this->newPageEdit( $inputs )->isMinorEdit( $isNewPage ) );
	}

	public static function provideTestIsMinorEdit(): array {
		return [
			'The edit is marked as minor, and the user has the minoredit permission' => [
				'hasPermission' => true,
				'markAsMinor' => true,
				'isNewPage' => false,
				'section' => '',
				'expected' => true,
			],
			'The edit is marked as minor, but the user does not have the minoredit permission' => [
				'hasPermission' => false,
				'markAsMinor' => true,
				'isNewPage' => false,
				'section' => '',
				'expected' => false,
			],
			'The edit is marked as minor, but a new page is being created' => [
				'hasPermission' => true,
				'markAsMinor' => true,
				'isNewPage' => true,
				'section' => '',
				'expected' => false,
			],
			'The edit is marked as minor, but a new section is being created' => [
				'hasPermission' => true,
				'markAsMinor' => true,
				'isNewPage' => false,
				'section' => 'new',
				'expected' => false,
			],
			'The edit is not marked as minor' => [
				'hasPermission' => true,
				'markAsMinor' => false,
				'isNewPage' => false,
				'section' => '',
				'expected' => false,
			],
		];
	}

	/**
	 * @dataProvider provideTestIsBotEdit
	 */
	public function testIsBotEdit(
		bool $hasPermission,
		bool $markAsBot,
		bool $expected,
	) {
		$authority = $hasPermission
			? $this->mockRegisteredAuthorityWithPermissions( [ 'bot' ] )
			: $this->mockRegisteredAuthorityWithoutPermissions( [ 'bot' ] );
		$inputs = $this->createInputs(
			authority: $authority,
			markAsBot: $markAsBot,
		);
		$this->assertEquals( $expected, $this->newPageEdit( $inputs )->isBotEdit() );
	}

	public static function provideTestIsBotEdit(): array {
		return [
			'The edit is marked as a bot edit, and the user has the bot permission' => [
				'hasPermission' => true,
				'markAsBot' => true,
				'expected' => true,
			],
			'The edit is marked as a bot edit, but the user does not have the bot permission' => [
				'hasPermission' => false,
				'markAsBot' => true,
				'expected' => false,
			],
			'The edit is not marked as a bot edit' => [
				'hasPermission' => true,
				'markAsBot' => false,
				'expected' => false,
			],
		];
	}

}
