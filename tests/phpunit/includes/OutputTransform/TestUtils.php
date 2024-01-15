<?php

namespace MediaWiki\Tests\OutputTransform;

use MediaWiki\Parser\ParserOutput;
use Wikimedia\Parsoid\Core\SectionMetadata;
use Wikimedia\Parsoid\Core\TOCData;

/**
 * Consts and utils used for OutputTransform tests
 */
class TestUtils {
	public const TEST_DOC = <<<EOF
<p>Test document.
</p>
<meta property="mw:PageProp/toc" />
<h2 data-mw-anchor="Section_1">Section 1<mw:editsection page="Test Page" section="1">Section 1</mw:editsection></h2>
<p>One
</p>
<h2 data-mw-anchor="Section_2">Section 2<mw:editsection page="Test Page" section="2">Section 2</mw:editsection></h2>
<p>Two
</p>
<h3 data-mw-anchor="Section_2.1">Section 2.1</h3>
<p>Two point one
</p>
<h2 data-mw-anchor="Section_3">Section 3<mw:editsection page="Test Page" section="4">Section 3</mw:editsection></h2>
<p>Three
</p>
EOF;
	public const TEST_DOC_WITH_LINKS = <<<EOF
<p>Test document.
</p>
<meta property="mw:PageProp/toc" />
<h2><span class="mw-headline" id="Section_1">Section 1</span><span class="mw-editsection"><span class="mw-editsection-bracket">[</span><a href="/w/index.php?title=Test_Page&amp;action=edit&amp;section=1" title="Edit section: Section 1">edit</a><span class="mw-editsection-bracket">]</span></span></h2>
<p>One
</p>
<h2><span class="mw-headline" id="Section_2">Section 2</span><span class="mw-editsection"><span class="mw-editsection-bracket">[</span><a href="/w/index.php?title=Test_Page&amp;action=edit&amp;section=2" title="Edit section: Section 2">edit</a><span class="mw-editsection-bracket">]</span></span></h2>
<p>Two
</p>
<h3><span class="mw-headline" id="Section_2.1">Section 2.1</span></h3>
<p>Two point one
</p>
<h2><span class="mw-headline" id="Section_3">Section 3</span><span class="mw-editsection"><span class="mw-editsection-bracket">[</span><a href="/w/index.php?title=Test_Page&amp;action=edit&amp;section=4" title="Edit section: Section 3">edit</a><span class="mw-editsection-bracket">]</span></span></h2>
<p>Three
</p>
EOF;
	public const TEST_DOC_WITHOUT_LINKS = <<<EOF
<p>Test document.
</p>
<meta property="mw:PageProp/toc" />
<h2><span class="mw-headline" id="Section_1">Section 1</span></h2>
<p>One
</p>
<h2><span class="mw-headline" id="Section_2">Section 2</span></h2>
<p>Two
</p>
<h3><span class="mw-headline" id="Section_2.1">Section 2.1</span></h3>
<p>Two point one
</p>
<h2><span class="mw-headline" id="Section_3">Section 3</span></h2>
<p>Three
</p>
EOF;

	// In this test, the `>` is not escaped in the 'data-mw-anchor' attribute and tag content, which
	// is allowed in HTML, but it's not the serialization used by the Parser. Extensions that modify
	// the HTML, e.g. DiscussionTools, can cause this to appear.
	public const TEST_DOC_ANGLE_BRACKETS = <<<HTML
<h2 data-mw-anchor=">">><mw:editsection page="Test Page" section="1">></mw:editsection></h2>
HTML;
	public const TEST_DOC_ANGLE_BRACKETS_WITH_LINKS = <<<HTML
<h2><span class="mw-headline" id="&gt;">></span><span class="mw-editsection"><span class="mw-editsection-bracket">[</span><a href="/w/index.php?title=Test_Page&amp;action=edit&amp;section=1" title="Edit section: &gt;">edit</a><span class="mw-editsection-bracket">]</span></span></h2>
HTML;
	public const TEST_DOC_ANGLE_BRACKETS_WITHOUT_LINKS = <<<HTML
<h2><span class="mw-headline" id="&gt;">></span></h2>
HTML;

	public const TEST_TO_DEDUP = <<<EOF
<p>This is a test document.</p>
<style data-mw-deduplicate="duplicate1">.Duplicate1 {}</style>
<style data-mw-deduplicate="duplicate1">.Duplicate1 {}</style>
<style data-mw-deduplicate="duplicate2">.Duplicate2 {}</style>
<style data-mw-deduplicate="duplicate1">.Duplicate1 {}</style>
<style data-mw-deduplicate="duplicate2">.Duplicate2 {}</style>
<style data-mw-not-deduplicate="duplicate1">.Duplicate1 {}</style>
<style data-mw-deduplicate="duplicate1">.Same-attribute-different-content {}</style>
<style data-mw-deduplicate="duplicate3">.Duplicate1 {}</style>
<style>.Duplicate1 {}</style>
EOF;

	public static function initSections( ParserOutput $po ): void {
		$po->setTOCData( new TOCData( SectionMetadata::fromLegacy( [
			'index' => "1",
			'level' => 1,
			'toclevel' => 1,
			'number' => "1",
			'line' => "Section 1",
			'anchor' => "Section_1"
		] ), SectionMetadata::fromLegacy( [
			'index' => "2",
			'level' => 1,
			'toclevel' => 1,
			'number' => "2",
			'line' => "Section 2",
			'anchor' => "Section_2"
		] ), SectionMetadata::fromLegacy( [
			'index' => "3",
			'level' => 2,
			'toclevel' => 2,
			'number' => "2.1",
			'line' => "Section 2.1",
			'anchor' => "Section_2.1"
		] ), SectionMetadata::fromLegacy( [
			'index' => "4",
			'level' => 1,
			'toclevel' => 1,
			'number' => "3",
			'line' => "Section 3",
			'anchor' => "Section_3"
		] ), ) );
	}
}
