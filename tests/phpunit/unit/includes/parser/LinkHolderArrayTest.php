<?php

declare( strict_types = 1 );

use MediaWiki\Linker\LinkRenderer;

/**
 * @covers LinkHolderArray
 */
class LinkHolderArrayTest extends MediaWikiUnitTestCase {

	/**
	 * @covers LinkHolderArray::merge
	 */
	public function testMerge() {
		$link1 = new LinkHolderArray(
			$this->createMock( Parser::class ),
			$this->createMock( ILanguageConverter::class ),
			$this->createHookContainer()
		);
		$link2 = new LinkHolderArray(
			$this->createMock( Parser::class ),
			$this->createMock( ILanguageConverter::class ),
			$this->createHookContainer()
		);

		$link1->internals = [
			'dummy' => [
				'dummy' => 'dummy entries 1',
				'dummy entries 1' => 'dummy entries 1',
			],
			'dummy entries 1' => [
				'dummy' => 'dummy entries 1',
				'dummy entries 1' => 'dummy entries 1',
			],
		];
		$link2->internals = [
			'dummy' => [
				'dummy' => 'dummy entries 2',
				'dummy entries 2' => 'dummy entries 2',
			],
			'dummy entries 2' => [
				'dummy' => 'dummy entries 2',
				'dummy entries 2' => 'dummy entries 2',
			],
		];
		$link1->interwikis = [
			'dummy' => [
				'dummy' => 'dummy interwikis 1',
				'dummy interwikis 1' => 'dummy interwikis 1',
			],
			'dummy interwikis 1' => [
				'dummy' => 'dummy interwikis 1',
				'dummy entries 1' => 'dummy interwikis 1',
			],
		];
		$link2->interwikis = [
			'dummy' => [
				'dummy' => 'dummy interwikis 2',
				'dummy interwikis 2' => 'dummy interwikis 2',
			],
			'dummy interwikis 2' => [
				'dummy' => 'dummy interwikis 2',
				'dummy interwikis 2' => 'dummy interwikis 2',
			],
		];

		$link1->size = 123;
		$link2->size = 321;

		$link1->merge( $link2 );

		$this->assertArrayEquals(
			[
				'dummy' => [
					'dummy' => 'dummy entries 1',
					'dummy entries 1' => 'dummy entries 1',
					'dummy entries 2' => 'dummy entries 2',
				],
				'dummy entries 1' => [
					'dummy' => 'dummy entries 1',
					'dummy entries 1' => 'dummy entries 1',
				],
				'dummy entries 2' => [
					'dummy' => 'dummy entries 2',
					'dummy entries 2' => 'dummy entries 2',
				],
			],
			$link1->internals
		);

		$this->assertArrayEquals(
			[
				'dummy' => [
					'dummy' => 'dummy entries 2',
					'dummy entries 2' => 'dummy entries 2',
				],
				'dummy entries 2' => [
					'dummy' => 'dummy entries 2',
					'dummy entries 2' => 'dummy entries 2',
				],
			],
			$link2->internals
		);

		$this->assertArrayEquals(
			[
				'dummy' => [
					'dummy' => 'dummy interwikis 1',
					'dummy interwikis 1' => 'dummy interwikis 1',
				],
				'dummy interwikis 1' => [
					'dummy' => 'dummy interwikis 1',
					'dummy entries 1' => 'dummy interwikis 1',
				],
				'dummy interwikis 2' => [
					'dummy' => 'dummy interwikis 2',
					'dummy interwikis 2' => 'dummy interwikis 2',
				],
			],
			$link1->interwikis
		);
		$this->assertArrayEquals(
			[
				'dummy' => [
					'dummy' => 'dummy interwikis 2',
					'dummy interwikis 2' => 'dummy interwikis 2',
				],
				'dummy interwikis 2' => [
					'dummy' => 'dummy interwikis 2',
					'dummy interwikis 2' => 'dummy interwikis 2',
				],
			],
			$link2->interwikis
		);
		$this->assertSame( 127, $link1->size );
		$this->assertSame( 321, $link2->size );
	}

	/**
	 * @covers LinkHolderArray::clear
	 */
	public function testClear() {
		$linkHolderArray = new LinkHolderArray(
			$this->createMock( Parser::class ),
			$this->createMock( ILanguageConverter::class ),
			$this->createHookContainer()
		);
		$linkHolderArray->internals = [ 'dummy data' ];
		$linkHolderArray->interwikis = [ 'dummy data' ];
		$linkHolderArray->size = -123;
		$linkHolderArray->clear();

		$this->assertSame( [], $linkHolderArray->internals );
		$this->assertSame( [], $linkHolderArray->interwikis );
		$this->assertSame( 0, $linkHolderArray->size );
	}

	/**
	 * @dataProvider provideReplaceText
	 * @covers LinkHolderArray::replaceText
	 *
	 * @param string $input
	 * @param string $expected
	 */
	public function testReplaceText(
		string $input,
		string $expected
	) {
		$linkHolderArray = new LinkHolderArray(
			$this->createMock( Parser::class ),
			$this->createMock( ILanguageConverter::class ),
			$this->createHookContainer()
		);

		$this->assertSame(
			$input,
			$linkHolderArray->replaceText( $input )
		);
		$linkHolderArray->internals = [
			'a' => [ 'b:c' => [ 'text' => 'dummy 1' ] ],
			'z' => [ 'x:c' => [ 'text' => 'dummy 2' ] ],
		];
		$linkHolderArray->interwikis = [
			'a:b:c' => [ 'text' => 'dummy 3' ],
			'z:x:c' => [ 'text' => 'dummy 4' ],
		];

		$this->assertSame(
			$expected,
			$linkHolderArray->replaceText( $input )
		);
	}

	public function provideReplaceText() {
		yield [
			'<!--LINK\'" q:w:e--> <!-- <!-- <!--IWLINK\'" q:w:e-->',
			'<!--LINK\'" q:w:e--> <!-- <!-- <!--IWLINK\'" q:w:e-->',
		];
		yield [
			'<!--<!--<!--LINK\'" a:b:c-->-->-->',
			'<!--<!--dummy 1-->-->',
		];
		yield [
			'<!--LINK\'" q:w:e--><!--LINK\'" a:b:c-->  <!--LINK\'" z:x:c-->',
			'<!--LINK\'" q:w:e-->dummy 1  dummy 2',
		];
		yield [
			'<!--IWLINK\'" q:w:e--><!--IWLINK\'" a:b:c-->  <!--IWLINK\'" z:x:c-->',
			'<!--IWLINK\'" q:w:e-->dummy 3  dummy 4',
		];
		yield [
			'<!--IWLINK\'" q:w:e-->  <!--LINK\'" a:b:c--><!--IWLINK\'" z:x:c-->',
			'<!--IWLINK\'" q:w:e-->  dummy 1dummy 4',
		];
		yield [
			'<!--LINK\'" a:b:c--><!--LINK\'" a:b:c--><!--LINK\'" a:b:c-->',
			'dummy 1dummy 1dummy 1',
		];
		yield [
			'<!--IWLINK\'" z:x:c--><!--IWLINK\'" z:x:c--><!--IWLINK\'" z:x:c-->',
			'dummy 4dummy 4dummy 4',
		];
	}

	/**
	 * @dataProvider provideReplace_external
	 * @covers LinkHolderArray::replace
	 * @covers LinkHolderArray::replaceInterwiki
	 *
	 * @param string $text
	 * @param string $extended
	 */
	public function testReplace_external(
		string $text,
		string $extended
	) {
		$link = new LinkHolderArray(
			$this->createMock( Parser::class ),
			$this->createMock( ILanguageConverter::class ),
			$this->createHookContainer()
		);
		$title = $this->createMock( Title::class );
		$title->method( 'isExternal' )->willReturn( true );

		$link->interwikis = [
			'key' => [
				'title' => $title,
				'text' => 'text',
			],
		];
		$parser = $this->createMock( Parser::class );
		$link->parent = $parser;

		$parserOutput = $this->createMock( ParserOutput::class );
		$parser->method( 'getOutput' )->willReturn( $parserOutput );

		$linkRenderer = $this->createMock( LinkRenderer::class );
		$parser->method( 'getLinkRenderer' )->willReturn( $linkRenderer );

		$linkRenderer->method( 'makeLink' )->willReturn( 'new text' );
		$link->replace( $text );
		$this->assertSame( $extended, $text );
	}

	public function provideReplace_external() {
		yield [
			'dummy text',
			'dummy text',
		];
		yield [
			'<!--IWLINK\'" key-->',
			'new text',
		];
		yield [
			'text1<!--IWLINK\'" key--><!--IWLINK\'" key-->  text2',
			'text1new textnew text  text2',
		];
	}
}
