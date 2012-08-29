<?php

class MediaTransformOutputTest extends MediaWikiTestCase {

	/**
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
			'', array(),
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
			'Alt text must be  escaped'

		);

		# TODO: add various options checks such as 'custom-url-link', 'valign',
		# 'img-class'...
		return $cases;
	}

}
