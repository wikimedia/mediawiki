<?php

use MediaWiki\Message\Message;
use MediaWiki\Skin\SkinComponentListItem;

/**
 * @covers \MediaWiki\Skin\SkinComponentListItem
 * @covers \MediaWiki\Skin\SkinComponentLink
 * @group Skin
 */
class SkinComponentListItemTest extends MediaWikiUnitTestCase {
	public function testGetTemplateData() {
		$msg = $this->createMock( Message::class );
		$msg->method( 'isDisabled' )->willReturn( false );
		$msg->method( 'text' )->willReturn( 'text' );
		$localizer = $this->createMock( MessageLocalizer::class );
		$localizer->method( 'msg' )->willReturn(
			$msg
		);
		$listItemData = [
			'id' => 'pt-watchlist',
			'class' => 'mw-watchlist',
			'icon' => 'watchlist',
		];
		$linkData = [
			'href' => '/wiki/Special:Watchlist',
			'text' => 'Link text',
			'single-id' => 'pt-watchlist-link',
		];
		$component = new SkinComponentListItem(
			'watchlist',
			$listItemData + $linkData + [
				'link-class' => 'link-watchlist',
			],
			$localizer,
			[],
			[]
		);
		$componentLinks = new SkinComponentListItem(
			'watchlist',
			$listItemData + [
				'links' => [
					$linkData + [
						'class' => 'link-watchlist',
					]
				]
			],
			$localizer,
			[],
			[]
		);
		$expectedLinkData = [
			'icon' => 'watchlist',
			'array-attributes' => [
				[
					'key' => 'href',
					'value' => '/wiki/Special:Watchlist',
				],
				[
					'key' => 'class',
					'value' => 'link-watchlist',
				],
				[
					'key' => 'title',
					'value' => 'texttexttext',
				]
				],
			'text' => 'Link text',
		];
		$this->assertEquals( $expectedLinkData, $component->getTemplateData()['array-links'][ 0 ] );
		$this->assertEquals( $expectedLinkData, $componentLinks->getTemplateData()['array-links'][ 0 ] );
	}
}
