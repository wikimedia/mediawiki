<?php

require 'ProxyTools.php';

class LanguageConverterTest extends PHPUnit_Framework_TestCase {
	protected $lang = null;
	protected $lc = null;

	function setUp() {
		global $wgMemc;
		$wgMemc = new FakeMemCachedClient;
		$this->lang = new LanguageTest();
		$this->lc = new TestConverter( $this->lang, 'tg',
									   array( 'tg', 'tg-latn' ) );
	}

	function tearDown() {
		global $wgMemc;
		unset($wgMemc);
		unset($this->lc);
		unset($this->lang);
	}

	function testGetPreferredVariant() {
		global $wgRequest, $wgUsePathInfo, $wgLanguageCode,
			$wgVariantArticlePath, $wgUser, $wgContLang,
			$wgDefaultLanguageVariant;

		$wgRequest = new FauxRequest(array());
		$wgUser    = new User;
		$wgContLang = Language::factory( 'tg' );

		$this->assertEquals('tg', $this->lc->getPreferredVariant(false, false));
		$this->assertEquals('tg', $this->lc->getPreferredVariant(false, true));
		$this->assertEquals('tg', $this->lc->getPreferredVariant(true, false));
		$this->assertEquals('tg', $this->lc->getPreferredVariant(true, true));

		$wgRequest->setHeader('Accept-Language', 'tg-latn');
		$this->lc = new TestConverter( $this->lang, 'tg',
									   array( 'tg', 'tg-latn' ) );
		$this->assertEquals('tg', $this->lc->getPreferredVariant(false, false));
		$this->assertEquals('tg-latn', $this->lc->getPreferredVariant(false, true));
		$this->assertEquals('tg', $this->lc->getPreferredVariant(true, false));
		$this->assertEquals('tg', $this->lc->getPreferredVariant(true, true));

		$wgRequest->setHeader('Accept-Language', 'tg;q=1');
		$this->lc = new TestConverter( $this->lang, 'tg',
									   array( 'tg', 'tg-latn' ) );
		$this->assertEquals('tg', $this->lc->getPreferredVariant(false, false));
		$this->assertEquals('tg', $this->lc->getPreferredVariant(false, true));
		$this->assertEquals('tg', $this->lc->getPreferredVariant(true, false));
		$this->assertEquals('tg', $this->lc->getPreferredVariant(true, true));

		$wgRequest->setHeader('Accept-Language', 'tg-latn;q=1');
		$this->lc = new TestConverter( $this->lang, 'tg',
									   array( 'tg', 'tg-latn' ) );
		$this->assertEquals('tg', $this->lc->getPreferredVariant(false, false));
		$this->assertEquals('tg-latn', $this->lc->getPreferredVariant(false, true));
		$this->assertEquals('tg', $this->lc->getPreferredVariant(true, false));
		$this->assertEquals('tg', $this->lc->getPreferredVariant(true, true));

		$wgRequest->setHeader('Accept-Language', 'en, tg-latn;q=1');
		$this->lc = new TestConverter( $this->lang, 'tg',
									   array( 'tg', 'tg-latn' ) );
		$this->assertEquals('tg', $this->lc->getPreferredVariant(false, false));
		$this->assertEquals('tg-latn', $this->lc->getPreferredVariant(false, true));
		$this->assertEquals('tg', $this->lc->getPreferredVariant(true, false));
		$this->assertEquals('tg', $this->lc->getPreferredVariant(true, true));
		$wgRequest->setHeader('Accept-Language', '');

		$wgUser = User::newFromId("admin");
		$wgContLang = Language::factory( 'tg-latn' );
		$wgUser->setId(1);
		$wgUser->setOption('variant', 'tg-latn');
		$this->lc = new TestConverter( $this->lang, 'tg',
									   array( 'tg', 'tg-latn' ) );

		$this->assertEquals('tg', $this->lc->getPreferredVariant(false, false));
		$this->assertEquals('tg', $this->lc->getPreferredVariant(false, true));
		$this->assertEquals('tg-latn', $this->lc->getPreferredVariant(true,  false));
		$this->assertEquals('tg-latn', $this->lc->getPreferredVariant(true,  true));

		$wgRequest->setVal('variant', 'tg');
		$this->lc = new TestConverter( $this->lang, 'tg',
									   array( 'tg', 'tg-latn' ) );
		$this->assertEquals('tg', $this->lc->getPreferredVariant(true,  false));
		$this->assertEquals('tg', $this->lc->getPreferredVariant(true,  true));

		$wgRequest->setVal('variant', null);
		$wgDefaultLanguageVariant = 'tg-latn';
		$this->lc = new TestConverter( $this->lang, 'tg',
									   array( 'tg', 'tg-latn' ) );
		$this->assertEquals('tg-latn', $this->lc->getPreferredVariant(false, false));
		$this->assertEquals('tg-latn', $this->lc->getPreferredVariant(false, true));
		$this->assertEquals('tg-latn', $this->lc->getPreferredVariant(true, false));
		$this->assertEquals('tg-latn', $this->lc->getPreferredVariant(true, true));

		$wgRequest->setVal('variant', null);
		$wgDefaultLanguageVariant = 'tg';
		$this->lc = new TestConverter( $this->lang, 'tg',
									   array( 'tg', 'tg-latn' ) );
		$this->assertEquals('tg', $this->lc->getPreferredVariant(false, false));
		$this->assertEquals('tg', $this->lc->getPreferredVariant(false, true));
		$this->assertEquals('tg-latn', $this->lc->getPreferredVariant(true, false));
		$this->assertEquals('tg-latn', $this->lc->getPreferredVariant(true, true));


	}
}

/**
 * Test converter (from Tajiki to latin orthography)
 */
class TestConverter extends LanguageConverter {
	private $table = array(
		'б' => 'b',
		'в' => 'v',
		'г' => 'g',
	);

	function loadDefaultTables() {
		$this->mTables = array(
			'tg-latn' => new ReplacementArray( $this->table ),
			'tg'      => new ReplacementArray()
		);
	}

}

class LanguageTest extends Language {
	function __construct() {
		parent::__construct();
		$variants = array( 'tg', 'tg-latn' );
		$this->mConverter = new TestConverter( $this, 'tg', $variants );
	}
}
