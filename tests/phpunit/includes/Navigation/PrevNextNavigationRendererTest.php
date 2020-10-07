<?php

use MediaWiki\Navigation\PrevNextNavigationRenderer;
use Wikimedia\TestingAccessWrapper;

/**
 * @covers \MediaWiki\Navigation\PrevNextNavigationRenderer
 */
class PrevNextNavigationRendererTest extends MediaWikiIntegrationTestCase {

	public function provideBuildPrevNextNavigation() {
		yield [ 0, 20, false, false ];
		yield [ 17, 20, false, false ];
		yield [ 0, 17, false, false ];
		yield [ 0, 20, true, 'Foo' ];
		yield [ 17, 20, true, 'Föö_Bär' ];
	}

	/**
	 * @dataProvider provideBuildPrevNextNavigation
	 */
	public function testBuildPrevNextNavigation( $offset, $limit, $atEnd, $subPage ) {
		$this->setUserLang( 'qqx' ); // disable i18n

		$prevNext = new PrevNextNavigationRenderer( RequestContext::getMain() );
		$prevNext = TestingAccessWrapper::newFromObject( $prevNext );

		$html = $prevNext->buildPrevNextNavigation(
			SpecialPage::getTitleFor( 'Watchlist', $subPage ),
			$offset,
			$limit,
			[ 'x' => 25 ],
			$atEnd
		);

		$this->assertStringStartsWith( '(viewprevnext:', $html );

		preg_match_all( '!<a.*?</a>!', $html, $m, PREG_PATTERN_ORDER );
		$links = $m[0];

		foreach ( $links as $a ) {
			if ( $subPage ) {
				$this->assertStringContainsString( 'Special:Watchlist/' . wfUrlencode( $subPage ), $a );
			} else {
				$this->assertStringContainsString( 'Special:Watchlist', $a );
				$this->assertStringNotContainsString( 'Special:Watchlist/', $a );
			}
			$this->assertStringContainsString( 'x=25', $a );
		}

		$i = 0;

		if ( $offset > 0 ) {
			$this->assertStringContainsString(
				'limit=' . $limit . '&amp;offset=' . max( 0, $offset - $limit ) . '&amp;',
				$links[ $i ]
			);
			$this->assertStringContainsString( 'title="(prevn-title: ' . $limit . ')"', $links[$i] );
			$this->assertStringContainsString( 'class="mw-prevlink"', $links[$i] );
			$this->assertStringContainsString( '>(prevn: ' . $limit . ')<', $links[$i] );
			$i += 1;
		}

		if ( !$atEnd ) {
			$this->assertStringContainsString(
				'limit=' . $limit . '&amp;offset=' . ( $offset + $limit ) . '&amp;',
				$links[ $i ]
			);
			$this->assertStringContainsString( 'title="(nextn-title: ' . $limit . ')"', $links[$i] );
			$this->assertStringContainsString( 'class="mw-nextlink"', $links[$i] );
			$this->assertStringContainsString( '>(nextn: ' . $limit . ')<', $links[$i] );
			$i += 1;
		}

		$this->assertCount( 5 + $i, $links );

		$this->assertStringContainsString( 'limit=20&amp;offset=' . $offset, $links[$i] );
		$this->assertStringContainsString( 'title="(shown-title: 20)"', $links[$i] );
		$this->assertStringContainsString( 'class="mw-numlink"', $links[$i] );
		$this->assertStringContainsString( '>20<', $links[$i] );
		$i += 4;

		$this->assertStringContainsString( 'limit=500&amp;offset=' . $offset, $links[$i] );
		$this->assertStringContainsString( 'title="(shown-title: 500)"', $links[$i] );
		$this->assertStringContainsString( 'class="mw-numlink"', $links[$i] );
		$this->assertStringContainsString( '>500<', $links[$i] );
	}
}
