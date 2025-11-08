<?php

namespace MediaWiki\Tests\EditPage;

use MediaWiki\EditPage\PreloadedContentBuilder;
use MediaWiki\MainConfigNames;
use MediaWiki\Permissions\UltimateAuthority;
use MediaWiki\Title\Title;
use MediaWikiIntegrationTestCase;

/**
 * @covers \MediaWiki\EditPage\PreloadedContentBuilder
 * @group Database
 */
class PreloadedContentBuilderTest extends MediaWikiIntegrationTestCase {

	/** @var PreloadedContentBuilder */
	private $preloadedContentBuilder;

	protected function setUp(): void {
		$services = $this->getServiceContainer();

		// Needed for the 'Default preload section' test case due to use of wfMessage()
		$this->overrideConfigValue( MainConfigNames::UseDatabaseMessages, true );

		$this->preloadedContentBuilder = $services->getPreloadedContentBuilder();
	}

	public static function provideCases() {
		// title, preload, preloadParams, section, pages, expectedContent

		yield 'Non-existent page, no preload' =>
			[ 'Does-not-exist-asdfasdf', null, [], null, [],
				"" ];
		yield 'Non-existent page, preload' =>
			[ 'Does-not-exist-asdfasdf', 'Template:Preload', [], null, [ 'Template:Preload' => 'Preload' ],
				"Preload" ];
		yield 'Non-existent page, preload with parameters' =>
			[ 'Does-not-exist-asdfasdf', 'Template:Preloadparams', [ 'a', 'b' ], null, [ 'Template:Preloadparams' => 'Preload $1 $2' ],
				"Preload a b" ];

		yield 'Existing page content is ignored (it is not our responsibility)' =>
			[ 'Exists', null, [], null, [ 'Exists' => 'Hello' ],
				"" ];
		yield 'Existing page content is ignored (it is not our responsibility), preload' =>
			[ 'Exists', 'Template:Preload', [], null, [ 'Exists' => 'Hello', 'Template:Preload' => 'Preload' ],
				"Preload" ];

		yield 'Preload section' =>
			[ 'Exists', 'Template:Preload', [], 'new', [ 'Exists' => 'Hello', 'Template:Preload' => 'Preload' ],
				"Preload" ];
		yield 'Default preload section' =>
			[ 'Exists', null, [], 'new', [ 'Exists' => 'Hello', 'MediaWiki:Addsection-preload' => 'Preloadsection' ],
				"Preloadsection" ];

		yield 'Non-existent page in MediaWiki: namespace is prefilled with message' =>
			[ 'MediaWiki:View', null, [], null, [],
				"View" ];
		yield 'Non-existent page in MediaWiki: namespace for non-existent message' =>
			[ 'MediaWiki:Does-not-exist-asdfasdf', null, [], null, [],
				"" ];
		yield 'Non-existent page in MediaWiki: namespace does not support preload' =>
			[ 'MediaWiki:View', 'Template:Preload', [], null, [ 'Template:Preload' => 'Preload' ],
				"View" ];
		yield 'Non-existent message supports preload' =>
			[ 'MediaWiki:Does-not-exist-asdfasdf', 'Template:Preload', [], null, [ 'Template:Preload' => 'Preload' ],
				"Preload" ];

		yield 'JSON page in MediaWiki: namespace is prefilled with empty JSON' =>
			[ 'MediaWiki:Foo.json', null, [], null, [],
				"{}" ];

		yield 'Preload using Special:MyLanguage' =>
			[ 'Does-not-exist-asdfasdf', 'Special:MyLanguage/Template:Preload', [], null, [ 'Template:Preload' => 'Preload' ],
				"Preload" ];

		yield 'Preload using a localisation message' =>
			[ 'Does-not-exist-asdfasdf', 'MediaWiki:View', [], null, [],
				"View" ];
		yield 'Preload using a page in mediawiki namespace' =>
			[ 'Does-not-exist-asdfasdf', 'MediaWiki:For-preloading', [], null, [ 'MediaWiki:For-preloading' => '<noinclude>Noinclude</noinclude><includeonly>Includeonly</includeonly>' ],
				"Includeonly" ];

		yield 'Preload over redirect' =>
			[ 'Does-not-exist-asdfasdf', 'Template:Preload2', [], null, [ 'Template:Preload' => 'Preload', 'Template:Preload2' => '#REDIRECT[[Template:Preload]]' ],
				"Preload" ];
	}

	/**
	 * @dataProvider provideCases
	 */
	public function testGetPreloadedContent( $title, $preload, $preloadParams, $section, $pages, $expectedContent ) {
		foreach ( $pages as $page => $content ) {
			$this->editPage( $page, $content );
		}

		$content = $this->preloadedContentBuilder->getPreloadedContent(
			Title::newFromText( $title )->toPageIdentity(),
			new UltimateAuthority( $this->getTestUser()->getUser() ),
			$preload,
			$preloadParams,
			$section
		);

		$this->assertEquals( $expectedContent, $content->serialize() );
	}

}
