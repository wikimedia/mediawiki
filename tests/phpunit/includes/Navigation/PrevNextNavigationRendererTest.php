<?php

use Wikimedia\TestingAccessWrapper;
use MediaWiki\Navigation\PrevNextNavigationRenderer;

/**
 * @covers \MediaWiki\Navigation\PrevNextNavigationRenderer
 */
class PrevNextNavigationRendererTest extends MediaWikiTestCase {

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
		$this->setUserLang( Language::factory( 'qqx' ) ); // disable i18n

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
				$this->assertContains( 'Special:Watchlist/' . wfUrlencode( $subPage ), $a );
			} else {
				$this->assertContains( 'Special:Watchlist', $a );
				$this->assertNotContains( 'Special:Watchlist/', $a );
			}
			$this->assertContains( 'x=25', $a );
		}

		$i = 0;

		if ( $offset > 0 ) {
			$this->assertContains(
				'limit=' . $limit . '&amp;offset=' . max( 0, $offset - $limit ) . '&amp;',
				$links[ $i ]
			);
			$this->assertContains( 'title="(prevn-title: ' . $limit . ')"', $links[$i] );
			$this->assertContains( 'class="mw-prevlink"', $links[$i] );
			$this->assertContains( '>(prevn: ' . $limit . ')<', $links[$i] );
			$i += 1;
		}

		if ( !$atEnd ) {
			$this->assertContains(
				'limit=' . $limit . '&amp;offset=' . ( $offset + $limit ) . '&amp;',
				$links[ $i ]
			);
			$this->assertContains( 'title="(nextn-title: ' . $limit . ')"', $links[$i] );
			$this->assertContains( 'class="mw-nextlink"', $links[$i] );
			$this->assertContains( '>(nextn: ' . $limit . ')<', $links[$i] );
			$i += 1;
		}

		$this->assertCount( 5 + $i, $links );

		$this->assertContains( 'limit=20&amp;offset=' . $offset, $links[$i] );
		$this->assertContains( 'title="(shown-title: 20)"', $links[$i] );
		$this->assertContains( 'class="mw-numlink"', $links[$i] );
		$this->assertContains( '>20<', $links[$i] );
		$i += 4;

		$this->assertContains( 'limit=500&amp;offset=' . $offset, $links[$i] );
		$this->assertContains( 'title="(shown-title: 500)"', $links[$i] );
		$this->assertContains( 'class="mw-numlink"', $links[$i] );
		$this->assertContains( '>500<', $links[$i] );
	}
}
