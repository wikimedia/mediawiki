<?php

class MediaTransformOutputTest extends MediaWikiTestCase {

	/**
	 * TODO: should probably moved to parserTests.txt
	 *
	 * @covers ThumbnailImage::toHtml
	 * @dataProvider provideOptionForThumbnailImageHtml
	 */
	function testThumbnailImageHtml( $expectedHTML, $toHtmlOptions, $message = '' ) {

		$thm = new ThumbnailImage(
			TempFSFile::factory( 'unittest_' . __CLASS__ , 'txt' ),
			'url://invalid',
			800,
			600
		);
		$this->assertEquals( $expectedHTML,
			$thm->toHtml( $toHtmlOptions ),
			$message
		);
	}

	function provideOptionForThumbnailImageHtml() {

		/** expected HTML, toHtml option array, optional message */
		$cases = array();

		$cases[] = array(
			'<img alt="" src="url://invalid" width="800" height="600" />', array(),
			'Thumbnail html without any options'
		);
		$cases[] = array(
			'<img alt="Some text for alt" src="url://invalid" width="800" height="600" />',
			array( 'alt' => 'Some text for alt' )
		);
		$cases[] = array(
			'<img alt="0" src="url://invalid" width="800" height="600" />',
			array( 'alt' => 0 ),
			'Number zero can be used as alt text'
		);
		$cases[] = array(
			'<img alt="1" src="url://invalid" width="800" height="600" />',
			array( 'alt' => 1 ),
			'Number one can be used as alt text'
		);
		$cases[] = array(
			'<img alt="0" src="url://invalid" width="800" height="600" />',
			array( 'alt' => '0' ),
			'String "0" can be used as alt text'
		);

		$cases[] = array(
			'<img alt="&lt;script&gt;alert(&quot;harmful&quot;);&lt;/script&gt;" src="url://invalid" width="800" height="600" />',
			array( 'alt' => '<script>alert("harmful");</script>' ),
			'Alt text must be escaped'
		);

		$cases[] = array(
			'<a href="http://example.org/"><img alt="" src="url://invalid" width="800" height="600" /></a>',
			array( 'custom-url-link' => 'http://example.org/' ),
			'"custom-url-link" creates an anchor to the given URL'
		);
		$cases[] = array(
			'<a href="http://example.org/" title="Some overriden title"><img alt="" src="url://invalid" width="800" height="600" /></a>',
			array(
				'custom-url-link' => 'http://example.org/',
				'title' => 'Some overriden title',
			),
			'"custom-url-link" with "title" creates an anchor with a title attribute'
		);

		$cases[] = array(
			'<a href="http://example.org/" target="_blank"><img alt="" src="url://invalid" width="800" height="600" /></a>',
			array(
				'custom-url-link' => 'http://example.org/',
				'custom-target-link' => '_blank',
			),
			'"custom-url-link" with "custom-target-link" let you override an anchor target'
		);

		$cases[] = array(
			'<a href="http://example.org/" target="_blank"><img alt="" src="url://invalid" width="800" height="600" /></a>',
			array(
				'custom-url-link' => 'http://example.org/',
				'parser-extlink-target' => '_blank',
			),
			'"custom-url-link" with "parser-extlink-rel" let you set a rel attribute such as "nofollow"'
		);

		$cases[] = array(
			'<a href="http://example.org/" target="_custom_target"><img alt="" src="url://invalid" width="800" height="600" /></a>',
			array(
				'custom-url-link' => 'http://example.org/',
				'custom-target-link' => '_custom_target',
				'parser-extlink-target' => '_parser_target',
			),
			'"custom-target-link" overrides "parser-extlink-target"'
		);

		$cases[] = array(
			'<a href="http://example.org/" rel="nofollow"><img alt="" src="url://invalid" width="800" height="600" /></a>',
			array(
				'custom-url-link' => 'http://example.org/',
				'parser-extlink-rel' => 'nofollow',
			),
			'"custom-url-link" with "parser-extlink-rel" let you set a rel attribute such as "nofollow"'
		);

		$cases[] = array(
			'<a href="/index.php/Some_Page" title="Some Page"><img alt="" src="url://invalid" width="800" height="600" /></a>',
			array( 'custom-title-link' => Title::newFromText( 'Some_Page' ) ),
			'"custom-title-link" creates an anchor to the given title'
		);

		$cases[] = array(
			'<a href="/index.php/Some_Page" title="Overriden Title"><img alt="" src="url://invalid" width="800" height="600" /></a>',
			array(
				'custom-title-link' => Title::newFromText( 'Some_Page' ),
				'title' => 'Overriden Title',
			),
			'"title" overrides the title passed with "custom-title-link"'
		);

		// "valign"
		$cases[] = array(
			'<img alt="" src="url://invalid" width="800" height="600" style="vertical-align: middle" />',
			array( 'valign' => 'middle' ),
			'"valign" let us apply a vertical-align style on image"'
		);
		$cases[] = array(
			'<img alt="" src="url://invalid" width="800" height="600" />',
			array( 'valign' => false ),
			'"valign" set to false does not apply any specific style"',
		);

		// "img-class"
		$cases[] = array(
			'<img alt="" src="url://invalid" width="800" height="600" class="" />',
			array( 'img-class' => '' ),
		);
		$cases[] = array(
			'<img alt="" src="url://invalid" width="800" height="600" class="border blue" />',
			array( 'img-class' => 'border blue' ),
		);
		$cases[] = array(
			'<img alt="" src="url://invalid" width="800" height="600" />',
			array( 'img-class' => false ),
		);

		# TODO : test 'desc-link' and 'file-link'

		# TODO: add various options checks such as 'custom-url-link', 'valign',
		# 'img-class'...
		return $cases;
	}

}
