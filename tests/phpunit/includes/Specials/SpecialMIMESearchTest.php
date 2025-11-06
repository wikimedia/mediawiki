<?php
namespace MediaWiki\Tests\Specials;

use MediaWiki\Context\RequestContext;
use MediaWiki\Request\FauxRequest;
use MediaWiki\Specials\SpecialMIMESearch;
use MediaWiki\Title\Title;
use MediaWikiIntegrationTestCase;

/**
 * @group Database
 * @covers \MediaWiki\Specials\SpecialMIMESearch
 */
class SpecialMIMESearchTest extends MediaWikiIntegrationTestCase {

	/** @var SpecialMIMESearch */
	private $page;

	protected function setUp(): void {
		parent::setUp();

		$services = $this->getServiceContainer();
		$this->page = new SpecialMIMESearch(
			$services->getConnectionProvider(),
			$services->getLinkBatchFactory(),
			$services->getLanguageConverterFactory()
		);
		$context = new RequestContext();
		$context->setTitle( Title::makeTitle( NS_SPECIAL, 'MIMESearch' ) );
		$context->setRequest( new FauxRequest() );
		$this->page->setContext( $context );
	}

	/**
	 * @dataProvider providerMimeFiltering
	 * @param string $par Subpage for special page
	 * @param string $major Major MIME type we expect to look for
	 * @param string $minor Minor MIME type we expect to look for
	 */
	public function testMimeFiltering( $par, $major, $minor ) {
		$this->page->run( $par );
		$qi = $this->page->getQueryInfo();
		$this->assertEquals( $qi['conds']['img_major_mime'], $major );
		if ( $minor !== null ) {
			$this->assertEquals( $qi['conds']['img_minor_mime'], $minor );
		} else {
			$this->assertArrayNotHasKey( 'img_minor_mime', $qi['conds'] );
		}
		$this->assertContains( 'image', $qi['tables'] );
	}

	public static function providerMimeFiltering() {
		return [
			[ 'image/gif', 'image', 'gif' ],
			[ 'image/png', 'image', 'png' ],
			[ 'application/pdf', 'application', 'pdf' ],
			[ 'image/*', 'image', null ],
			[ 'multipart/*', 'multipart', null ],
		];
	}
}
