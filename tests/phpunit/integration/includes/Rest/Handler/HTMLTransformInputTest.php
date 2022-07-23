<?php

namespace MediaWiki\Tests\Rest\Handler;

use Exception;
use MediaWiki\Rest\Handler\HTMLTransformInput;
use MediaWikiIntegrationTestCase;
use Wikimedia\Parsoid\Core\ClientError;
use Wikimedia\Parsoid\Utils\DOMUtils;

/**
 * @covers \MediaWiki\Rest\Handler\HTMLTransformInput
 */
class HTMLTransformInputTest extends MediaWikiIntegrationTestCase {

	private const ORIG_BODY = '<body>Original HTML</body>';
	private const ORIG_HTML = '<html>' . self::ORIG_BODY . '</html>';
	private const ORIG_DATA_MW = [ 'ids' => [ 'mwAQ' => [] ] ];
	private const ORIG_DATA_PARSOID = [ 'ids' => [ 'mwAQ' => [ 'pi' => [ [ [ 'k' => '1' ] ] ] ] ] ];
	private const MODIFIED_DATA_MW = [ 'ids' => [ 'mwAQ' => [
		'parts' => [ [
			'template' => [
				'target' => [ 'wt' => '1x', 'href' => './Template:1x' ],
				'params' => [ '1' => [ 'wt' => 'hi' ] ],
				'i' => 0
			]
		] ]
	] ] ];

	private function getOriginalData() {
		$profileVersion = '2.4.0';
		$htmlProfileUri = 'https://www.mediawiki.org/wiki/Specs/HTML/' . $profileVersion;
		$dataParsoidProfileUri = 'https://www.mediawiki.org/wiki/Specs/data-parsoid/' . $profileVersion;
		$dataMwProfileUri = 'https://www.mediawiki.org/wiki/Specs/data-mw/' . $profileVersion;

		$htmlContentType = "text/html;profile=\"$htmlProfileUri\"";
		$dataParsoidContentType = "application/json;profile=\"$dataParsoidProfileUri\"";
		$dataMwContentType = "application/json;profile=\"$dataMwProfileUri\"";

		return [
			'revid' => 1,
			'html' => [
				'body' => self::ORIG_HTML,
				'headers' => [
					'content-type' => $htmlContentType,
				],
			],
			'data-mw' => [
				'body' => self::ORIG_DATA_MW,
				'headers' => [
					'content-type' => $dataMwContentType,
				],
			],
			'data-parsoid' => [
				'body' => self::ORIG_DATA_PARSOID,
				'headers' => [
					'content-type' => $dataParsoidContentType,
				],
			],
		];
	}

	private function newHTMLTransformInput() {
		$doc = DOMUtils::parseHTML( self::ORIG_HTML, true );
		$input = new HTMLTransformInput( $doc );
		$opts = [
			'from' => 'pagebundle',
			'contentmodel' => 'wikitext',
			'data-mw' => [
				'body' => self::MODIFIED_DATA_MW,
				'headers' => [
					'content-type' => 'application/json; charset=utf-8',
				],
			],
			'original' => $this->getOriginalData()
		];

		// Set some options to assert on $input.
		$input->setOptions( $opts );
		$input->setInputFormat( 'pagebundle' );
		$input->setModifiedDataMW( [ self::MODIFIED_DATA_MW ] );
		$input->setOriginalRevisionId( $opts['original']['revid'] );
		$input->setOriginalData( $opts['original'] );

		return $input;
	}

	public function provideBadOriginalData() {
		$base = $this->getOriginalData();

		$orig = $base;
		unset( $orig['html']['headers']['content-type'] );
		yield 'no html content type' => [
			$orig,
			new ClientError( 'Content-type of original html is missing.' )
		];

		$orig = $base;
		$orig['html']['headers']['content-type'] = 'xyz';
		yield 'bad html content type' => [
			$orig,
			new ClientError( 'Content-type of original html is missing.' )
		];

		$orig = $base;
		unset( $orig['data-parsoid']['body']['ids'] );
		yield 'missing ids key in data-parsoid' => [
			$orig,
			new ClientError( 'Invalid data-parsoid was provided.' )
		];
	}

	/**
	 * @dataProvider provideBadOriginalData
	 *
	 * @param array $original
	 * @param Exception $exception
	 *
	 * @return void
	 * @throws ClientError
	 */
	public function testBadOriginalData( array $original, Exception $exception ) {
		$doc = DOMUtils::parseHTML( self::ORIG_HTML, true );
		$input = new HTMLTransformInput( $doc );

		$this->expectException( get_class( $exception ) );
		$this->expectExceptionMessage( $exception->getMessage() );
		$input->setOriginalData( $original );
		$input->getOriginalSchemaVersion();
		$input->getOriginalPageBundle();
	}

	public function testHasOriginalHtml() {
		$ctx = $this->newHTMLTransformInput();
		$this->assertTrue( $ctx->hasOriginalHtml() );
	}

	public function testGetOriginalSchemaVersion() {
		$ctx = $this->newHTMLTransformInput();
		$this->assertSame( '2.4.0', $ctx->getOriginalSchemaVersion() );
	}

	public function testInputIsPageBundle() {
		$ctx = $this->newHTMLTransformInput();
		$this->assertTrue( $ctx->inputIsPageBundle() );
	}

	public function testGetOriginalPageBundle() {
		$ctx = $this->newHTMLTransformInput();
		$this->assertSame(
			self::ORIG_HTML,
			$ctx->getOriginalPageBundle()->html
		);
	}

	public function testGetOriginalHtml() {
		$ctx = $this->newHTMLTransformInput();
		$this->assertSame(
			self::ORIG_HTML,
			$ctx->getOriginalHtml()
		);
	}

	public function testGetModifiedPageBundle() {
		$ctx = $this->newHTMLTransformInput();
		$this->assertSame(
			[ self::MODIFIED_DATA_MW ],
			$ctx->getModifiedPageBundle( '1.1' )->mw
		);
	}

	public function testHasModifiedDataMw() {
		$ctx = $this->newHTMLTransformInput();
		$this->assertTrue( $ctx->hasModifiedDataMw() );
	}

	public function testOldId() {
		$ctx = $this->newHTMLTransformInput();
		$this->assertSame( 1, $ctx->getOriginalRevisionId() );
	}

	public function testGetContentModel() {
		$ctx = $this->newHTMLTransformInput();
		$this->assertSame( 'wikitext', $ctx->getContentModel() );
	}

	public function testGetEnvOpts() {
		$ctx = $this->newHTMLTransformInput();
		$this->assertSame( 'byte', $ctx->getEnvironmentOffsetType() );
	}
}
