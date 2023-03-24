<?php

use MediaWiki\Feed\FeedUtils;

/**
 * @covers \MediaWiki\Feed\FeedUtils
 */
class FeedUtilsTest extends MediaWikiUnitTestCase {

	/**
	 * @dataProvider provideElements
	 */
	public function testApplyDiffStyle( $html, $expected ) {
		$this->assertSame( $expected, FeedUtils::applyDiffStyle( $html ) );
	}

	public static function provideElements() {
		return [
			[
				'<td class="diff">Test</td>',
				'<td style="background-color: #fff; color: #202122;">Test</td>'
			],
			[
				'<td colspan="2" class="diff-otitle">← Previous revision</td>',
				'<td colspan="2" style="background-color: #fff; color: #202122; text-align: center;">' .
					'← Previous revision</td>'
			],
			[
				'<td colspan="2" class="diff-ntitle">Test</td>',
				'<td colspan="2" style="background-color: #fff; color: #202122; text-align: center;">' .
					'Test</td>'
			],
			[
				'<td class="diff-addedline">Test</td>',
				'<td style="color: #202122; font-size: 88%; border-style: solid; ' .
					'border-width: 1px 1px 1px 4px; border-radius: 0.33em; border-color: #a3d3ff; ' .
					'vertical-align: top; white-space: pre-wrap;">Test</td>'
			],
			// An extra class before
			[
				'<td class="extraclass diff-deletedline">Test</td>',
				'<td style="color: #202122; font-size: 88%; border-style: solid; ' .
					'border-width: 1px 1px 1px 4px; border-radius: 0.33em; border-color: #ffe49c; ' .
					'vertical-align: top; white-space: pre-wrap;">Test</td>'
			],
			// Multiple classes
			[
				'<td class="diffchange diff-context extra">Test</td>',
				'<td style="background-color: #f8f9fa; color: #202122; font-size: 88%; '
				. 'border-style: solid; border-width: 1px 1px 1px 4px; border-radius: 0.33em; '
				. 'border-color: #eaecf0; vertical-align: top; white-space: pre-wrap;">Test</td>'
			],
			// An extra class after
			[
				'<td class="diffchange diffchange-inline">Test</td>',
				'<td style="font-weight: bold; text-decoration: none;">Test</td>'
			],
			[
				'<td class="not-a-diff">Test</td>',
				'<td class="not-a-diff">Test</td>',
			],
			[
				'<td class="reallynodiff">Test</td>',
				'<td class="reallynodiff">Test</td>',
			],
			[
				'<td class="unrelated" id="diff">Test</td>',
				'<td class="unrelated" id="diff">Test</td>',
			],
			[
				'<td class="unrelated reallynodiff">diff <span class="x">x</span></td>',
				'<td class="unrelated reallynodiff">diff <span class="x">x</span></td>',
			],
			[
				'<<class="diff">>',
				'<<class="diff">>',
			],
			[
				'<b>class="a" < class="diff"</b>',
				'<b>class="a" < class="diff"</b>',
			],
			// Multiple lines together
			[
				'<td colspan="2" class="diff-ntitle"><span>Title</span><td class="diff-addedline">' .
					'Test</td></td><td class="extraclass diff-deletedline">Test</td>< class="diff"></>' .
					'class="diff"',
				'<td colspan="2" style="background-color: #fff; color: #202122; text-align: center;">' .
					'<span>Title</span><td style="color: #202122; font-size: 88%; border-style: solid; ' .
					'border-width: 1px 1px 1px 4px; border-radius: 0.33em; border-color: #a3d3ff; ' .
					'vertical-align: top; white-space: pre-wrap;">Test</td></td><td ' .
					'style="color: #202122; font-size: 88%; border-style: solid; ' .
					'border-width: 1px 1px 1px 4px; border-radius: 0.33em; border-color: #ffe49c; ' .
					'vertical-align: top; white-space: pre-wrap;">Test</td>< class="diff"></>class="diff"'
			]
		];
	}

}
