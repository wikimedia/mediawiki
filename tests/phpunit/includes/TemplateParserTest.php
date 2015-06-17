<?php

/**
 * @group Templates
 */
class TemplateParserTest extends MediaWikiTestCase {

	protected $templateDir;

	protected function setUp() {
		parent::setUp();

		$this->setMwGlobals( array(
			'wgSecretKey' => 'foo',
			'wgMemc' => new EmptyBagOStuff(),
		) );

		$this->templateDir = dirname( __DIR__ ) . '/data/templates/';
	}

	/**
	 * @dataProvider provideProcessTemplate
	 * @covers TemplateParser::processTemplate
	 * @covers TemplateParser::getTemplate
	 * @covers TemplateParser::getTemplateFilename
	 */
	public function testProcessTemplate( $name, $args, $result, $exception = false ) {
		if ( $exception ) {
			$this->setExpectedException( $exception );
		}
		$tp = new TemplateParser( $this->templateDir, true );
		$this->assertEquals( $result, $tp->processTemplate( $name, $args ) );
	}

	public static function provideProcessTemplate() {
		return array(array(
				'foobar_block_conditional',
				array (
					'langdir' => 'ltr',
					'articleCountMsg' => '8 pages',
					'privacyMsg' => 'Public',
					'collectionUrl' => '/wiki/Special:Gather/id/1/Responsive_design_collection',
					'hasImage' => true,
					'image' => '<div
					class=\'list-thumb

						list-thumb-x\'
	style=\'background-image: url("http://localhost:8080/images/0/02/Fucka_2015-02-16_11-53.jpg")\'></div>
',
					'title' => 'Responsive design collection',
					'owner' => array (
						'link' => '/wiki/Special:Gather/by/User',
						'class' => 'mw-ui-icon mw-ui-icon-before mw-ui-icon-profile ',
						'label' => 'User',
					),
				),
				'<div class=\'collection-card \'>
	<a href=\'/wiki/Special:Gather/id/1/Responsive_design_collection\' class=\'collection-card-image\'>
		<div
					class=\'list-thumb

						list-thumb-x\'
	style=\'background-image: url("http://localhost:8080/images/0/02/Fucka_2015-02-16_11-53.jpg")\'></div>

	</a>
	<div class=\'collection-card-overlay\' dir=\'ltr\'>
		<div class=\'collection-card-title\'>
			<a href=\'/wiki/Special:Gather/id/1/Responsive_design_collection\'>Responsive design collection</a>
		</div>
		
			<a
				class="mw-ui-icon mw-ui-icon-before mw-ui-icon-profile  collection-owner"
				href="/wiki/Special:Gather/by/User">User</a>
		<span>•</span>
		
		
		<span class=\'collection-card-article-count\'>8 pages</span>
	</div>
</div>
',
			),
		);

		return array(
			array(
				'foobar',
				array(),
				"hello world!\n"
			),
			array(
				'foobar_args',
				array(
					'planet' => 'world',
				),
				"hello world!\n",
			),
			array(
				'foobar_block_conditional',
				array (
					'langdir' => 'ltr',
					'articleCountMsg' => '8 pages',
					'privacyMsg' => 'Public',
					'collectionUrl' => '/wiki/Special:Gather/id/1/Responsive_design_collection',
					'hasImage' => true,
					'image' => '<div
					class=\'list-thumb

						list-thumb-x\'
	style=\'background-image: url("http://localhost:8080/images/0/02/Fucka_2015-02-16_11-53.jpg")\'></div>
',
					'title' => 'Responsive design collection',
					'owner' => array (
						'link' => '/wiki/Special:Gather/by/User',
						'class' => 'mw-ui-icon mw-ui-icon-before mw-ui-icon-profile ',
						'label' => 'User',
					),
				),
				'<div class=\'collection-card \'>
	<a href=\'/wiki/Special:Gather/id/1/Responsive_design_collection\' class=\'collection-card-image\'>
		<div
  class=\'list-thumb
        
        list-thumb-x\'
  style=\'background-image: url("http://localhost:8080/images/0/02/Fucka_2015-02-16_11-53.jpg")\'></div>

	</a>
	<div class=\'collection-card-overlay\' dir=\'ltr\'>
		<div class=\'collection-card-title\'>
			<a href=\'/wiki/Special:Gather/id/1/Responsive_design_collection\'>Responsive design collection</a>
		</div>
		list-thumb-y
		
		<span class=\'collection-card-article-count\'>8 pages</span>
	</div>
</div>',
			),
			array(
				'../foobar',
				array(),
				false,
				'UnexpectedValueException'
			),
			array(
				'nonexistenttemplate',
				array(),
				false,
				'RuntimeException',
			)
		);
	}
}
