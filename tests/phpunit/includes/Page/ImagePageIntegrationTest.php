<?php

use MediaWiki\Context\RequestContext;
use MediaWiki\FileRepo\LocalRepo;
use MediaWiki\MainConfigNames;
use MediaWiki\Page\Article;
use MediaWiki\Page\ImagePage;
use MediaWiki\Permissions\Authority;
use MediaWiki\Request\FauxRequest;
use MediaWiki\Tests\Unit\HtmlAssertionHelperTrait;
use MediaWiki\Title\Title;
use MediaWiki\User\User;
use MediaWiki\WikiMap\WikiMap;
use Wikimedia\FileBackend\FSFileBackend;
use Wikimedia\Parsoid\Core\DOMCompat;
use Wikimedia\Parsoid\DOM\Document;
use Wikimedia\Parsoid\Ext\DOMUtils;

/**
 * Renders file description pages end to end, against a real local file
 * repository and real link tables.
 *
 * @covers \MediaWiki\Page\ImagePage
 * @group Database
 */
class ImagePageIntegrationTest extends MediaWikiIntegrationTestCase {
	use HtmlAssertionHelperTrait;

	private const string FILE_NAME = 'ImagePageIntegrationTest.png';

	protected function setUp(): void {
		parent::setUp();

		$this->overrideConfigValues( [
			MainConfigNames::LocalFileRepo => [
				'class' => LocalRepo::class,
				'name' => 'local',
				'backend' => new FSFileBackend( [
					'name' => 'imagepage-test-backend',
					'wikiId' => WikiMap::getCurrentWikiId(),
					'basePath' => $this->getNewTempDirectory(),
				] ),
				'url' => 'http://localhost/images',
				// Describe thumbnails without rendering them, so that no image
				// scaler is needed.
				'transformVia404' => true,
			],
			MainConfigNames::ForeignFileRepos => [],
			MainConfigNames::EnableUploads => true,
			MainConfigNames::ShowEXIF => false,
		] );
	}

	private function uploadFile( string $name, string $pageText ): void {
		$file = $this->getServiceContainer()->getRepoGroup()->getLocalRepo()
			->newFile( Title::makeTitle( NS_FILE, $name ) );
		$status = $file->upload(
			__DIR__ . '/../../data/media/greyscale-png.png',
			'Initial upload',
			$pageText,
			0,
			false,
			false,
			$this->getTestSysop()->getUser()
		);
		$this->assertStatusGood( $status );
	}

	private function viewFilePage( string $name, Authority $performer ): Document {
		$title = Title::makeTitle( NS_FILE, $name );
		$context = new RequestContext();
		$context->setRequest( new FauxRequest() );
		$context->setTitle( $title );
		$context->setAuthority( $performer );
		$context->setLanguage( 'en' );

		$article = Article::newFromTitle( $title, $context );
		$this->assertInstanceOf( ImagePage::class, $article );
		$article->view();

		return DOMUtils::parseHTML( $context->getOutput()->getHTML() );
	}

	private function assertSelectorMatchesNoElement( Document $doc, string $selector ): void {
		$this->assertNull(
			DOMCompat::querySelector( $doc, $selector ),
			"Found an element with CSS selector $selector"
		);
	}

	public function testViewExistingFile() {
		$this->uploadFile( self::FILE_NAME, 'A greyscale test image.' );

		$doc = $this->viewFilePage( self::FILE_NAME, $this->getTestSysop()->getUser() );

		// The file itself
		$this->assertSelectorMatchesOneElementInNode( $doc, '#file img' );
		// The wikitext of the description page
		$content = $this->assertSelectorMatchesOneElementInNode( $doc, '#mw-imagepage-content' );
		$this->assertStringContainsString( 'A greyscale test image.', $content->textContent );
		// The structural sections
		$this->assertSelectorMatchesOneElementInNode( $doc, 'h2#filehistory' );
		$this->assertSelectorMatchesOneElementInNode( $doc, 'h2#filelinks' );
		$history = $this->assertSelectorMatchesOneElementInNode( $doc, 'table.filehistory' );
		$this->assertStringContainsString( 'Initial upload', $history->textContent );
		// Nothing uses the file yet
		$this->assertSelectorMatchesOneElementInNode( $doc, '#mw-imagepage-nolinkstoimage' );
		$this->assertSelectorMatchesNoElement( $doc, '#mw-imagepage-nofile' );
	}

	public function testViewListsPagesUsingTheFile() {
		$this->uploadFile( self::FILE_NAME, 'Description' );
		$this->editPage( 'ImagePageIntegrationTest user', '[[File:' . self::FILE_NAME . ']]' );
		$this->editPage(
			Title::makeTitle( NS_FILE, 'ImagePageIntegrationTest redirect.png' ),
			'#REDIRECT [[File:' . self::FILE_NAME . ']]'
		);
		$this->editPage(
			'ImagePageIntegrationTest redirect user',
			'[[File:ImagePageIntegrationTest redirect.png]]'
		);
		$this->runJobs();

		$doc = $this->viewFilePage( self::FILE_NAME, $this->getTestUser()->getUser() );

		$this->assertSelectorMatchesNoElement( $doc, '#mw-imagepage-nolinkstoimage' );
		$section = $this->assertSelectorMatchesOneElementInNode( $doc, '#mw-imagepage-section-linkstoimage' );
		$this->assertSelectorMatchesOneElementInNode(
			$section, 'ul.mw-imagepage-linkstoimage > li > a[title="ImagePageIntegrationTest user"]'
		);

		// The redirect is listed with redirect=no, and the page using the
		// redirect is nested beneath it.
		$redirectLink = $this->assertSelectorMatchesOneElementInNode(
			$section, 'a[title="File:ImagePageIntegrationTest redirect.png"]'
		);
		$this->assertStringContainsString( 'redirect=no', $redirectLink->getAttribute( 'href' ) );
		$this->assertSelectorMatchesOneElementInNode(
			$section, 'ul.mw-imagepage-redirectstofile a[title="ImagePageIntegrationTest redirect user"]'
		);
	}

	public function testViewShowsDuplicates() {
		$this->uploadFile( self::FILE_NAME, 'Original' );
		$this->uploadFile( 'ImagePageIntegrationTest copy.png', 'Copy' );

		$doc = $this->viewFilePage( self::FILE_NAME, $this->getTestUser()->getUser() );

		$this->assertSelectorMatchesOneElementInNode(
			$doc, 'ul.mw-imagepage-duplicates a[title="File:ImagePageIntegrationTest copy.png"]'
		);
	}

	public function testReuploadLinkForUserWhoCanUpload() {
		$this->uploadFile( self::FILE_NAME, 'Description' );

		$doc = $this->viewFilePage( self::FILE_NAME, $this->getTestSysop()->getUser() );

		$link = $this->assertSelectorMatchesOneElementInNode( $doc, '#mw-imagepage-reupload-link a' );
		$this->assertStringContainsString( 'wpForReUpload=1', $link->getAttribute( 'href' ) );
		$this->assertSelectorMatchesNoElement( $doc, '#mw-imagepage-upload-disallowed' );
	}

	public function testReuploadDisallowedForAnonymousUser() {
		$this->uploadFile( self::FILE_NAME, 'Description' );

		$doc = $this->viewFilePage( self::FILE_NAME, new User() );

		$this->assertSelectorMatchesOneElementInNode( $doc, '#mw-imagepage-upload-disallowed' );
		$this->assertSelectorMatchesNoElement( $doc, '#mw-imagepage-reupload-link' );
	}

	public function testNoReuploadSectionWhenUploadsAreDisabled() {
		$this->uploadFile( self::FILE_NAME, 'Description' );
		$this->overrideConfigValue( MainConfigNames::EnableUploads, false );

		$doc = $this->viewFilePage( self::FILE_NAME, $this->getTestSysop()->getUser() );

		$this->assertSelectorMatchesNoElement( $doc, '.mw-imagepage-upload-links' );
	}

	public function testViewDescriptionPageWithoutFile() {
		$this->editPage(
			Title::makeTitle( NS_FILE, 'ImagePageIntegrationTest missing.png' ),
			'A description without a file.'
		);

		$doc = $this->viewFilePage(
			'ImagePageIntegrationTest missing.png',
			$this->getTestUser()->getUser()
		);

		// Since the user may upload, they are offered a link to do so
		$this->assertSelectorMatchesOneElementInNode(
			$doc, '#mw-imagepage-nofile a[href*="wpDestFile=ImagePageIntegrationTest_missing.png"]'
		);
		$content = $this->assertSelectorMatchesOneElementInNode( $doc, '#mw-imagepage-content' );
		$this->assertStringContainsString( 'A description without a file.', $content->textContent );
		$this->assertSelectorMatchesNoElement( $doc, '#file' );
		$this->assertSelectorMatchesNoElement( $doc, '#filehistory' );
	}

	public function testViewNonexistentFileAndPage() {
		$doc = $this->viewFilePage(
			'ImagePageIntegrationTest nonexistent.png',
			new User()
		);

		$this->assertSelectorMatchesOneElementInNode( $doc, '#mw-imagepage-nofile' );
		// Anonymous users may not upload, so no upload link is offered
		$this->assertSelectorMatchesNoElement( $doc, 'a[href*="wpDestFile="]' );
	}
}
