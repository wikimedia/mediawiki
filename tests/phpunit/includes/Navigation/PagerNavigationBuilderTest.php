<?php

use MediaWiki\Navigation\PagerNavigationBuilder;
use MediaWiki\Page\PageReferenceValue;

/**
 * @covers \MediaWiki\Navigation\PagerNavigationBuilder
 */
class PagerNavigationBuilderTest extends MediaWikiIntegrationTestCase {

	protected function setUp(): void {
		parent::setUp();

		$this->setMwGlobals( [
			'wgArticlePath' => '/wiki/$1',
			'wgScriptPath' => '/w',
			'wgScript' => '/w/index.php',
		] );
	}

	private const EXPECTED_BASIC = '<div class="mw-pager-navigation-bar">(viewprevnext: <span class="mw-prevlink">(prevn: 50)</span>, <span class="mw-nextlink">(nextn: 50)</span>, <a href="/w/index.php?title=A&amp;limit=20" class="mw-numlink">20</a>(pipe-separator)<span class="mw-numlink">50</span>(pipe-separator)<a href="/w/index.php?title=A&amp;limit=100" class="mw-numlink">100</a>(pipe-separator)<a href="/w/index.php?title=A&amp;limit=250" class="mw-numlink">250</a>(pipe-separator)<a href="/w/index.php?title=A&amp;limit=500" class="mw-numlink">500</a>)</div>';
	private const EXPECTED_OVERRIDES = '<div class="mw-pager-navigation-bar">(parentheses: <a href="/w/index.php?title=A&amp;a=a&amp;d=d" title="(m)" class="mw-firstlink">(i)</a>(pipe-separator)<a href="/w/index.php?title=A&amp;a=a&amp;e=e" title="(n)" class="mw-lastlink">(j)</a>) (viewprevnext: <a href="/w/index.php?title=A&amp;a=a&amp;b=b" rel="prev" title="(k: 1)" class="mw-prevlink">(g: 1)</a>, <a href="/w/index.php?title=A&amp;a=a&amp;c=c" rel="next" title="(l: 1)" class="mw-nextlink">(h: 1)</a>, <span class="mw-numlink">1</span>(pipe-separator)<a href="/w/index.php?title=A&amp;a=a&amp;f=2" title="(o: 2)" class="mw-numlink">2</a>)</div>';
	private const EXPECTED_NULL_LINK_QUERY = '<div class="mw-pager-navigation-bar">(parentheses: <a href="/wiki/A" class="mw-firstlink">(i)</a>(pipe-separator)<a href="/w/index.php?title=A&amp;dir=prev" class="mw-lastlink">(j)</a>) (viewprevnext: <a href="/w/index.php?title=A&amp;dir=prev&amp;offset=1" rel="prev" class="mw-prevlink">(prevn: 1)</a>, <a href="/w/index.php?title=A&amp;dir=next&amp;offset=2" rel="next" class="mw-nextlink">(nextn: 1)</a>, <span class="mw-numlink">1</span>)</div>';

	public function testBasic() {
		$navBuilder = new PagerNavigationBuilder( new MockMessageLocalizer() );
		$navBuilder->setPage( PageReferenceValue::localReference( NS_MAIN, 'A' ) );
		$this->assertEquals( self::EXPECTED_BASIC, $navBuilder->getHtml() );
	}

	public function testOverrides() {
		$navBuilder = new PagerNavigationBuilder( new MockMessageLocalizer() );
		$navBuilder
			->setPage( PageReferenceValue::localReference( NS_MAIN, 'A' ) )
			->setLinkQuery( [ 'a' => 'a' ] )
			->setPrevLinkQuery( [ 'b' => 'b' ] )
			->setNextLinkQuery( [ 'c' => 'c' ] )
			->setFirstLinkQuery( [ 'd' => 'd' ] )
			->setLastLinkQuery( [ 'e' => 'e' ] )
			->setLimitLinkQueryParam( 'f' )
			->setPrevMsg( 'g' )
			->setNextMsg( 'h' )
			->setFirstMsg( 'i' )
			->setLastMsg( 'j' )
			->setPrevTooltipMsg( 'k' )
			->setNextTooltipMsg( 'l' )
			->setFirstTooltipMsg( 'm' )
			->setLastTooltipMsg( 'n' )
			->setLimitTooltipMsg( 'o' )
			->setCurrentLimit( 1 )
			->setLimits( [ 1, 2 ] );
		$this->assertEquals( self::EXPECTED_OVERRIDES, $navBuilder->getHtml() );
	}

	public function testNullLinkQuery() {
		$navBuilder = new PagerNavigationBuilder( new MockMessageLocalizer() );
		$navBuilder
			->setPage( PageReferenceValue::localReference( NS_MAIN, 'A' ) )
			->setFirstMsg( 'i' )
			->setLastMsg( 'j' )
			->setCurrentLimit( 1 )
			->setLimits( [ 1 ] )
			// null is allowed here, and it establishes the order of parameters for the output
			->setLinkQuery( [ 'dir' => null, 'offset' => '1' ] )
			->setPrevLinkQuery( [ 'dir' => 'prev' ] )
			->setNextLinkQuery( [ 'offset' => '2', 'dir' => 'next' ] )
			// null is allowed here, and it overrides defaults from setLinkQuery()
			->setFirstLinkQuery( [ 'offset' => null ] )
			->setLastLinkQuery( [ 'offset' => null, 'dir' => 'prev' ] );
		$this->assertEquals( self::EXPECTED_NULL_LINK_QUERY, $navBuilder->getHtml() );
	}

}
