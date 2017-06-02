<?php

use Wikimedia\TestingAccessWrapper;
use Wikimedia\ScopedCallback;

class ParserOptionsTest extends MediaWikiTestCase {

	/**
	 * @dataProvider provideOptionsHash
	 * @param array $usedOptions Used options
	 * @param string $expect Expected value
	 * @param array $options Options to set
	 * @param array $globals Globals to set
	 */
	public function testOptionsHash( $usedOptions, $expect, $options, $globals = [] ) {
		global $wgHooks;

		$globals += [
			'wgRenderHashAppend' => '',
			'wgHooks' => [],
		];
		$globals['wgHooks'] += [
			'PageRenderingHash' => [],
		] + $wgHooks;
		$this->setMwGlobals( $globals );

		$popt = new ParserOptions();
		foreach ( $options as $setter => $value ) {
			$popt->$setter( $value );
		}
		$this->assertSame( $expect, $popt->optionsHash( $usedOptions ) );
	}

	public static function provideOptionsHash() {
		$used = [ 'wrapclass', 'editsection', 'printable' ];

		return [
			'Canonical options, nothing used' => [ [], '*!*!*!*!*!*', [] ],
			'Canonical options, used some options' => [ $used, '*!*!*!*!*', [] ],
			'Used some options, non-default values' => [
				$used,
				'*!*!*!*!*!printable=1!wrapclass=foobar',
				[
					'setWrapOutputClass' => 'foobar',
					'setIsPrintable' => true,
				]
			],
			'Canonical options, nothing used, but with hooks and $wgRenderHashAppend' => [
				[],
				'*!*!*!*!*!wgRenderHashAppend!*!onPageRenderingHash',
				[],
				[
					'wgRenderHashAppend' => '!wgRenderHashAppend',
					'wgHooks' => [ 'PageRenderingHash' => [ [ __CLASS__ . '::onPageRenderingHash' ] ] ],
				]
			],
		];
	}

	public static function onPageRenderingHash( &$confstr ) {
		$confstr .= '!onPageRenderingHash';
	}

	public function testMatches() {
		$popt1 = new ParserOptions();
		$popt2 = new ParserOptions();
		$this->assertTrue( $popt1->matches( $popt2 ) );

		$popt1->enableLimitReport( true );
		$popt2->enableLimitReport( false );
		$this->assertTrue( $popt1->matches( $popt2 ) );

		$popt2->setTidy( !$popt2->getTidy() );
		$this->assertFalse( $popt1->matches( $popt2 ) );
	}

}
