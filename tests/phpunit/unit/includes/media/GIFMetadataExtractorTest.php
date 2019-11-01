<?php

/**
 * @group Media
 */
class GIFMetadataExtractorTest extends \MediaWikiUnitTestCase {

	protected function setUp() : void {
		parent::setUp();

		$this->mediaPath = __DIR__ . '/../../../data/media/';
	}

	/**
	 * Put in a file, and see if the metadata coming out is as expected.
	 * @param string $filename
	 * @param array $expected The extracted metadata.
	 * @dataProvider provideGetMetadata
	 * @covers GIFMetadataExtractor::getMetadata
	 */
	public function testGetMetadata( $filename, $expected ) {
		$actual = GIFMetadataExtractor::getMetadata( $this->mediaPath . $filename );
		$this->assertEquals( $expected, $actual );
	}

	public static function provideGetMetadata() {
		$xmpNugget = <<<EOF
<?xpacket begin='﻿' id='W5M0MpCehiHzreSzNTczkc9d'?>
<x:xmpmeta xmlns:x='adobe:ns:meta/' x:xmptk='Image::ExifTool 7.30'>
<rdf:RDF xmlns:rdf='http://www.w3.org/1999/02/22-rdf-syntax-ns#'>

 <rdf:Description rdf:about=''
  xmlns:Iptc4xmpCore='http://iptc.org/std/Iptc4xmpCore/1.0/xmlns/'>
  <Iptc4xmpCore:Location>The interwebs</Iptc4xmpCore:Location>
 </rdf:Description>

 <rdf:Description rdf:about=''
  xmlns:tiff='http://ns.adobe.com/tiff/1.0/'>
  <tiff:Artist>Bawolff</tiff:Artist>
  <tiff:ImageDescription>
   <rdf:Alt>
    <rdf:li xml:lang='x-default'>A file to test GIF</rdf:li>
   </rdf:Alt>
  </tiff:ImageDescription>
 </rdf:Description>
</rdf:RDF>
</x:xmpmeta>
                                                                                                    
                                                                                                    
                                                                                                    
                                                                                                    
                                                                                                    
                                                                                                    
                                                                                                    
                                                                                                    
                                                                                                    
                                                                                                    
                                                                                                    
                                                                                                    
                                                                                                    
                                                                                                    
                                                                                                    
                                                                                                    
                                                                                                    
                                                                                                    
                                                                                                    
                                                                                                    
                                                                                                    
                                                                                                    
                                                                                                    
                                                                                                    
<?xpacket end='w'?>
EOF;
		$xmpNugget = str_replace( "\r", '', $xmpNugget ); // Windows compat

		return [
			[
				'nonanimated.gif',
				[
					'comment' => [ 'GIF test file ⁕ Created with GIMP' ],
					'duration' => 0.1,
					'frameCount' => 1,
					'looped' => false,
					'xmp' => '',
				]
			],
			[
				'animated.gif',
				[
					'comment' => [ 'GIF test file . Created with GIMP' ],
					'duration' => 2.4,
					'frameCount' => 4,
					'looped' => true,
					'xmp' => '',
				]
			],

			[
				'animated-xmp.gif',
				[
					'xmp' => $xmpNugget,
					'duration' => 2.4,
					'frameCount' => 4,
					'looped' => true,
					'comment' => [ 'GIƒ·test·file' ],
				]
			],
		];
	}
}
