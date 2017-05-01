<?php

/**
 * @group Upload
 */
class UploadBaseTest extends MediaWikiTestCase {

	/** @var UploadTestHandler */
	protected $upload;

	protected function setUp() {
		parent::setUp();

		$this->upload = new UploadTestHandler;

		$this->setMwGlobals( 'wgHooks', [
			'InterwikiLoadPrefix' => [
				function ( $prefix, &$data ) {
					return false;
				}
			],
		] );
	}

	/**
	 * First checks the return code
	 * of UploadBase::getTitle() and then the actual returned title
	 *
	 * @dataProvider provideTestTitleValidation
	 * @covers UploadBase::getTitle
	 */
	public function testTitleValidation( $srcFilename, $dstFilename, $code, $msg ) {
		/* Check the result code */
		$this->assertEquals( $code,
			$this->upload->testTitleValidation( $srcFilename ),
			"$msg code" );

		/* If we expect a valid title, check the title itself. */
		if ( $code == UploadBase::OK ) {
			$this->assertEquals( $dstFilename,
				$this->upload->getTitle()->getText(),
				"$msg text" );
		}
	}

	/**
	 * Test various forms of valid and invalid titles that can be supplied.
	 */
	public static function provideTestTitleValidation() {
		return [
			/* Test a valid title */
			[ 'ValidTitle.jpg', 'ValidTitle.jpg', UploadBase::OK,
				'upload valid title' ],
			/* A title with a slash */
			[ 'A/B.jpg', 'A-B.jpg', UploadBase::OK,
				'upload title with slash' ],
			/* A title with illegal char */
			[ 'A:B.jpg', 'A-B.jpg', UploadBase::OK,
				'upload title with colon' ],
			/* Stripping leading File: prefix */
			[ 'File:C.jpg', 'C.jpg', UploadBase::OK,
				'upload title with File prefix' ],
			/* Test illegal suggested title (r94601) */
			[ '%281%29.JPG', null, UploadBase::ILLEGAL_FILENAME,
				'illegal title for upload' ],
			/* A title without extension */
			[ 'A', null, UploadBase::FILETYPE_MISSING,
				'upload title without extension' ],
			/* A title with no basename */
			[ '.jpg', null, UploadBase::MIN_LENGTH_PARTNAME,
				'upload title without basename' ],
			/* A title that is longer than 255 bytes */
			[ str_repeat( 'a', 255 ) . '.jpg', null, UploadBase::FILENAME_TOO_LONG,
				'upload title longer than 255 bytes' ],
			/* A title that is longer than 240 bytes */
			[ str_repeat( 'a', 240 ) . '.jpg', null, UploadBase::FILENAME_TOO_LONG,
				'upload title longer than 240 bytes' ],
		];
	}

	/**
	 * Test the upload verification functions
	 * @covers UploadBase::verifyUpload
	 */
	public function testVerifyUpload() {
		/* Setup with zero file size */
		$this->upload->initializePathInfo( '', '', 0 );
		$result = $this->upload->verifyUpload();
		$this->assertEquals( UploadBase::EMPTY_FILE,
			$result['status'],
			'upload empty file' );
	}

	// Helper used to create an empty file of size $size.
	private function createFileOfSize( $size ) {
		$filename = $this->getNewTempFile();

		$fh = fopen( $filename, 'w' );
		ftruncate( $fh, $size );
		fclose( $fh );

		return $filename;
	}

	/**
	 * test uploading a 100 bytes file with $wgMaxUploadSize = 100
	 *
	 * This method should be abstracted so we can test different settings.
	 */
	public function testMaxUploadSize() {
		$this->setMwGlobals( [
			'wgMaxUploadSize' => 100,
			'wgFileExtensions' => [
				'txt',
			],
		] );

		$filename = $this->createFileOfSize( 100 );
		$this->upload->initializePathInfo( basename( $filename ) . '.txt', $filename, 100 );
		$result = $this->upload->verifyUpload();

		$this->assertEquals(
			[ 'status' => UploadBase::OK ],
			$result
		);
	}

	/**
	 * @dataProvider provideCheckSvgScriptCallback
	 */
	public function testCheckSvgScriptCallback( $svg, $wellFormed, $filterMatch, $message ) {
		list( $formed, $match ) = $this->upload->checkSvgString( $svg );
		$this->assertSame( $wellFormed, $formed, $message . " (well-formed)" );
		$this->assertSame( $filterMatch, $match, $message . " (filter match)" );
	}

	public static function provideCheckSvgScriptCallback() {
		// @codingStandardsIgnoreStart Generic.Files.LineLength
		return [
			// html5sec SVG vectors
			[
				'<svg xmlns="http://www.w3.org/2000/svg"><script>alert(1)</script></svg>',
				true,
				true,
				'Script tag in svg (http://html5sec.org/#47)'
			],
			[
				'<svg xmlns="http://www.w3.org/2000/svg"><g onload="javascript:alert(1)"></g></svg>',
				true,
				true,
				'SVG with onload property (http://html5sec.org/#11)'
			],
			[
				'<svg onload="javascript:alert(1)" xmlns="http://www.w3.org/2000/svg"></svg>',
				true,
				true,
				'SVG with onload property (http://html5sec.org/#65)'
			],
			[
				'<svg xmlns="http://www.w3.org/2000/svg"> <a xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="javascript:alert(1)"><rect width="1000" height="1000" fill="white"/></a> </svg>',
				true,
				true,
				'SVG with javascript xlink (http://html5sec.org/#87)'
			],
			[
				'<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><use xlink:href="data:application/xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIj4KPGRlZnM+CjxjaXJjbGUgaWQ9InRlc3QiIHI9IjUwIiBjeD0iMTAwIiBjeT0iMTAwIiBzdHlsZT0iZmlsbDogI0YwMCI+CjxzZXQgYXR0cmlidXRlTmFtZT0iZmlsbCIgYXR0cmlidXRlVHlwZT0iQ1NTIiBvbmJlZ2luPSdhbGVydChkb2N1bWVudC5jb29raWUpJwpvbmVuZD0nYWxlcnQoIm9uZW5kIiknIHRvPSIjMDBGIiBiZWdpbj0iMXMiIGR1cj0iNXMiIC8+CjwvY2lyY2xlPgo8L2RlZnM+Cjx1c2UgeGxpbms6aHJlZj0iI3Rlc3QiLz4KPC9zdmc+#test"/> </svg>',
				true,
				true,
				'SVG with Opera image xlink (http://html5sec.org/#88 - c)'
			],
			[
				'<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">  <animation xlink:href="javascript:alert(1)"/> </svg>',
				true,
				true,
				'SVG with Opera animation xlink (http://html5sec.org/#88 - a)'
			],
			[
				'<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">  <animation xlink:href="data:text/xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' onload=\'alert(1)\'%3E%3C/svg%3E"/> </svg>',
				true,
				true,
				'SVG with Opera animation xlink (http://html5sec.org/#88 - b)'
			],
			[
				'<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">  <image xlink:href="data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' onload=\'alert(1)\'%3E%3C/svg%3E"/> </svg>',
				true,
				true,
				'SVG with Opera image xlink (http://html5sec.org/#88 - c)'
			],
			[
				'<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">  <foreignObject xlink:href="javascript:alert(1)"/> </svg>',
				true,
				true,
				'SVG with Opera foreignObject xlink (http://html5sec.org/#88 - d)'
			],
			[
				'<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">  <foreignObject xlink:href="data:text/xml,%3Cscript xmlns=\'http://www.w3.org/1999/xhtml\'%3Ealert(1)%3C/script%3E"/> </svg>',
				true,
				true,
				'SVG with Opera foreignObject xlink (http://html5sec.org/#88 - e)'
			],
			[
				'<svg xmlns="http://www.w3.org/2000/svg"> <set attributeName="onmouseover" to="alert(1)"/> </svg>',
				true,
				true,
				'SVG with event handler set (http://html5sec.org/#89 - a)'
			],
			[
				'<svg xmlns="http://www.w3.org/2000/svg"> <animate attributeName="onunload" to="alert(1)"/> </svg>',
				true,
				true,
				'SVG with event handler animate (http://html5sec.org/#89 - a)'
			],
			[
				'<svg xmlns="http://www.w3.org/2000/svg"> <handler xmlns:ev="http://www.w3.org/2001/xml-events" ev:event="load">alert(1)</handler> </svg>',
				true,
				true,
				'SVG with element handler (http://html5sec.org/#94)'
			],
			[
				'<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"> <feImage> <set attributeName="xlink:href" to="data:image/svg+xml;charset=utf-8;base64, PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciPjxzY3JpcHQ%2BYWxlcnQoMSk8L3NjcmlwdD48L3N2Zz4NCg%3D%3D"/> </feImage> </svg>',
				true,
				true,
				'SVG with href to data: url (http://html5sec.org/#95)'
			],
			[
				'<svg xmlns="http://www.w3.org/2000/svg" id="foo"> <x xmlns="http://www.w3.org/2001/xml-events" event="load" observer="foo" handler="data:image/svg+xml,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%3E%0A%3Chandler%20xml%3Aid%3D%22bar%22%20type%3D%22application%2Fecmascript%22%3E alert(1) %3C%2Fhandler%3E%0A%3C%2Fsvg%3E%0A#bar"/> </svg>',
				true,
				true,
				'SVG with Tiny handler (http://html5sec.org/#104)'
			],
			[
				'<svg xmlns="http://www.w3.org/2000/svg"> <a id="x"><rect fill="white" width="1000" height="1000"/></a> <rect fill="white" style="clip-path:url(test3.svg#a);fill:url(#b);filter:url(#c);marker:url(#d);mask:url(#e);stroke:url(#f);"/> </svg>',
				true,
				true,
				'SVG with new CSS styles properties (http://html5sec.org/#109)'
			],
			[
				'<svg xmlns="http://www.w3.org/2000/svg"> <a id="x"><rect fill="white" width="1000" height="1000"/></a> <rect clip-path="url(test3.svg#a)" /> </svg>',
				true,
				true,
				'SVG with new CSS styles properties as attributes'
			],
			[
				'<svg xmlns="http://www.w3.org/2000/svg"> <a id="x"> <rect fill="white" width="1000" height="1000"/> </a> <rect fill="url(http://html5sec.org/test3.svg#a)" /> </svg>',
				true,
				true,
				'SVG with new CSS styles properties as attributes (2)'
			],
			[
				'<svg xmlns="http://www.w3.org/2000/svg"> <path d="M0,0" style="marker-start:url(test4.svg#a)"/> </svg>',
				true,
				true,
				'SVG with path marker-start (http://html5sec.org/#110)'
			],
			[
				'<?xml version="1.0"?> <?xml-stylesheet type="text/xml" href="#stylesheet"?> <!DOCTYPE doc [ <!ATTLIST xsl:stylesheet id ID #REQUIRED>]> <svg xmlns="http://www.w3.org/2000/svg"> <xsl:stylesheet id="stylesheet" version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform"> <xsl:template match="/"> <iframe xmlns="http://www.w3.org/1999/xhtml" src="javascript:alert(1)"></iframe> </xsl:template> </xsl:stylesheet> <circle fill="red" r="40"></circle> </svg>',
				false,
				true,
				'SVG with embedded stylesheet (http://html5sec.org/#125)'
			],
			[
				'<?xml version="1.0"?> <?xml-stylesheet type="text/xml" href="#stylesheet"?> <svg xmlns="http://www.w3.org/2000/svg"> <xsl:stylesheet id="stylesheet" version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform"> <xsl:template match="/"> <iframe xmlns="http://www.w3.org/1999/xhtml" src="javascript:alert(1)"></iframe> </xsl:template> </xsl:stylesheet> <circle fill="red" r="40"></circle> </svg>',
				true,
				true,
				'SVG with embedded stylesheet no doctype'
			],
			[
				'<svg xmlns="http://www.w3.org/2000/svg" id="x"> <listener event="load" handler="#y" xmlns="http://www.w3.org/2001/xml-events" observer="x"/> <handler id="y">alert(1)</handler> </svg>',
				true,
				true,
				'SVG with handler attribute (http://html5sec.org/#127)'
			],
			[
				// Haven't found a browser that accepts this particular example, but we
				// don't want to allow embeded svgs, ever
				'<svg> <image style=\'filter:url("data:image/svg+xml;charset=utf-8;base64, PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciPjxzY3JpcHQ/YWxlcnQoMSk8L3NjcmlwdD48L3N2Zz4NCg==")\' /> </svg>',
				true,
				true,
				'SVG with image filter via style (http://html5sec.org/#129)'
			],
			[
				// This doesn't seem possible without embedding the svg, but just in case
				'<svg> <a xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="?"> <circle r="400"></circle> <animate attributeName="xlink:href" begin="0" from="javascript:alert(1)" to="" /> </a></svg>',
				true,
				true,
				'SVG with animate from (http://html5sec.org/#137)'
			],
			[
				'<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"> <a><text y="1em">Click me</text> <animate attributeName="xlink:href" values="javascript:alert(\'Bang!\')" begin="0s" dur="0.1s" fill="freeze" /> </a></svg>',
				true,
				true,
				'SVG with animate xlink:href (http://html5sec.org/#137)'
			],
			[
				'<svg xmlns="http://www.w3.org/2000/svg" xmlns:y="http://www.w3.org/1999/xlink"> <a y:href="#"> <text y="1em">Click me</text> <animate attributeName="y:href" values="javascript:alert(\'Bang!\')" begin="0s" dur="0.1s" fill="freeze" /> </a> </svg>',
				true,
				true,
				'SVG with animate y:href (http://html5sec.org/#137)'
			],

			// Other hostile SVG's
			[
				'<?xml version="1.0" encoding="UTF-8" standalone="no"?> <svg xmlns:xlink="http://www.w3.org/1999/xlink"> <image xlink:href="https://upload.wikimedia.org/wikipedia/commons/3/34/Bahnstrecke_Zeitz-Camburg_1930.png" /> </svg>',
				true,
				true,
				'SVG with non-local image href (T67839)'
			],
			[
				'<?xml version="1.0" ?> <?xml-stylesheet type="text/xsl" href="/w/index.php?title=User:Jeeves/test.xsl&amp;action=raw&amp;format=xml" ?> <svg> <height>50</height> <width>100</width> </svg>',
				true,
				true,
				'SVG with remote stylesheet (T59550)'
			],
			[
				'<svg xmlns="http://www.w3.org/2000/svg" viewbox="-1 -1 15 15"> <rect y="0" height="13" width="12" stroke="#179" rx="1" fill="#2ac"/> <text x="1.5" y="11" font-family="courier" stroke="white" font-size="16"><![CDATA[B]]></text> <iframe xmlns="http://www.w3.org/1999/xhtml" srcdoc="&#x3C;&#x73;&#x63;&#x72;&#x69;&#x70;&#x74;&#x3E;&#x61;&#x6C;&#x65;&#x72;&#x74;&#x28;&#x27;&#x58;&#x53;&#x53;&#x45;&#x44;&#x20;&#x3D;&#x3E;&#x20;&#x44;&#x6F;&#x6D;&#x61;&#x69;&#x6E;&#x28;&#x27;&#x2B;&#x74;&#x6F;&#x70;&#x2E;&#x64;&#x6F;&#x63;&#x75;&#x6D;&#x65;&#x6E;&#x74;&#x2E;&#x64;&#x6F;&#x6D;&#x61;&#x69;&#x6E;&#x2B;&#x27;&#x29;&#x27;&#x29;&#x3B;&#x3C;&#x2F;&#x73;&#x63;&#x72;&#x69;&#x70;&#x74;&#x3E;"></iframe> </svg>',
				true,
				true,
				'SVG with rembeded iframe (T62771)'
			],
			[
				'<svg xmlns="http://www.w3.org/2000/svg" viewBox="6 3 177 153" xmlns:xlink="http://www.w3.org/1999/xlink"> <style>@import url("https://fonts.googleapis.com/css?family=Bitter:700&amp;text=WebPlatform.org");</style> <g transform="translate(-.5,-.5)"> <text fill="#474747" x="95" y="150" text-anchor="middle" font-family="Bitter" font-size="20" font-weight="bold">WebPlatform.org</text> </g> </svg>',
				true,
				true,
				'SVG with @import in style element (T71008)'
			],
			[
				'<svg xmlns="http://www.w3.org/2000/svg" viewBox="6 3 177 153" xmlns:xlink="http://www.w3.org/1999/xlink"> <style>@import url("https://fonts.googleapis.com/css?family=Bitter:700&amp;text=WebPlatform.org");<foo/></style> <g transform="translate(-.5,-.5)"> <text fill="#474747" x="95" y="150" text-anchor="middle" font-family="Bitter" font-size="20" font-weight="bold">WebPlatform.org</text> </g> </svg>',
				true,
				true,
				'SVG with @import in style element and child element (T71008#c11)'
			],
			[
				'<svg xmlns="http://www.w3.org/2000/svg" viewBox="6 3 177 153" xmlns:xlink="http://www.w3.org/1999/xlink"> <style>@imporT "https://fonts.googleapis.com/css?family=Bitter:700&amp;text=WebPlatform.org";</style> <g transform="translate(-.5,-.5)"> <text fill="#474747" x="95" y="150" text-anchor="middle" font-family="Bitter" font-size="20" font-weight="bold">WebPlatform.org</text> </g> </svg>',
				true,
				true,
				'SVG with case-insensitive @import in style element (bug T85349)'
			],
			[
				'<svg xmlns="http://www.w3.org/2000/svg"> <rect width="100" height="100" style="background-image:url(https://www.google.com/images/srpr/logo11w.png)"/> </svg>',
				true,
				true,
				'SVG with remote background image (T71008)'
			],
			[
				'<svg xmlns="http://www.w3.org/2000/svg"> <rect width="100" height="100" style="background-image:\55rl(https://www.google.com/images/srpr/logo11w.png)"/> </svg>',
				true,
				true,
				'SVG with remote background image, encoded (T71008)'
			],
			[
				'<svg xmlns="http://www.w3.org/2000/svg"> <style> #a { background-image:\55rl(\'https://www.google.com/images/srpr/logo11w.png\'); } </style> <rect width="100" height="100" id="a"/> </svg>',
				true,
				true,
				'SVG with remote background image, in style element (T71008)'
			],
			[
				// This currently doesn't seem to work in any browsers, but in case
				// https://www.w3.org/TR/css3-images/ is implemented for SVG files
				'<svg xmlns="http://www.w3.org/2000/svg"> <rect width="100" height="100" style="background-image:image(\'sprites.svg#xywh=40,0,20,20\')"/> </svg>',
				true,
				true,
				'SVG with remote background image using image() (T71008)'
			],
			[
				// As reported by Cure53
				'<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"> <a xlink:href="data:text/html;charset=utf-8;base64, PHNjcmlwdD5hbGVydChkb2N1bWVudC5kb21haW4pPC9zY3JpcHQ%2BDQo%3D"> <circle r="400" fill="red"></circle> </a> </svg>',
				true,
				true,
				'SVG with data:text/html link target (firefox only)'
			],
			[
				'<?xml version="1.0" encoding="UTF-8" standalone="no"?> <!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN" "http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd" [ <!ENTITY lol "lol"> <!ENTITY lol2 "&#x3C;&#x73;&#x63;&#x72;&#x69;&#x70;&#x74;&#x3E;&#x61;&#x6C;&#x65;&#x72;&#x74;&#x28;&#x27;&#x58;&#x53;&#x53;&#x45;&#x44;&#x20;&#x3D;&#x3E;&#x20;&#x27;&#x2B;&#x64;&#x6F;&#x63;&#x75;&#x6D;&#x65;&#x6E;&#x74;&#x2E;&#x64;&#x6F;&#x6D;&#x61;&#x69;&#x6E;&#x29;&#x3B;&#x3C;&#x2F;&#x73;&#x63;&#x72;&#x69;&#x70;&#x74;&#x3E;"> ]> <svg xmlns="http://www.w3.org/2000/svg" width="68" height="68" viewBox="-34 -34 68 68" version="1.1"> <circle cx="0" cy="0" r="24" fill="#c8c8c8"/> <text x="0" y="0" fill="black">&lol2;</text> </svg>',
				false,
				true,
				'SVG with encoded script tag in internal entity (reported by Beyond Security)'
			],
			[
				'<?xml version="1.0"?> <!DOCTYPE svg [ <!ENTITY foo SYSTEM "file:///etc/passwd"> ]> <svg xmlns="http://www.w3.org/2000/svg" version="1.1"> <desc>&foo;</desc> <rect width="300" height="100" style="fill:rgb(0,0,255);stroke-width:1;stroke:rgb(0,0,2)" /> </svg>',
				false,
				false,
				'SVG with external entity'
			],
			[
				// The base64 = <script>alert(1)</script>. If for some reason
				// entities actually do get loaded, this should trigger
				// filterMatch to be true. So this test verifies that we
				// are not loading external entities.
				'<?xml version="1.0"?> <!DOCTYPE svg [ <!ENTITY foo SYSTEM "data:text/plain;base64,PHNjcmlwdD5hbGVydCgxKTwvc2NyaXB0Pgo="> ]> <svg xmlns="http://www.w3.org/2000/svg" version="1.1"> <desc>&foo;</desc> <rect width="300" height="100" style="fill:rgb(0,0,255);stroke-width:1;stroke:rgb(0,0,2)" /> </svg>',
				false,
				false, /* False verifies entities aren't getting loaded */
				'SVG with data: uri external entity'
			],
			[
				"<svg xmlns=\"http://www.w3.org/2000/svg\" xmlns:xlink=\"http://www.w3.org/1999/xlink\"> <g> <a xlink:href=\"javascript:alert('1&#10;https://google.com')\"> <rect width=\"300\" height=\"100\" style=\"fill:rgb(0,0,255);stroke-width:1;stroke:rgb(0,0,2)\" /> </a> </g> </svg>",
				true,
				true,
				'SVG with javascript <a> link with newline (T122653)'
			],
			// Test good, but strange files that we want to allow
			[
				'<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"> <g> <a xlink:href="http://en.wikipedia.org/wiki/Main_Page"> <path transform="translate(0,496)" id="path6706" d="m 112.09375,107.6875 -5.0625,3.625 -4.3125,5.03125 -0.46875,0.5 -4.09375,3.34375 -9.125,5.28125 -8.625,-3.375 z" style="fill:#cccccc;fill-opacity:1;stroke:#6e6e6e;stroke-width:0.69999999;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:4;stroke-dasharray:none;display:inline" /> </a> </g> </svg>',
				true,
				false,
				'SVG with <a> link to a remote site'
			],
			[
				'<svg> <defs> <filter id="filter6226" x="-0.93243687" width="2.8648737" y="-0.24250539" height="1.4850108"> <feGaussianBlur stdDeviation="3.2344681" id="feGaussianBlur6228" /> </filter> <clipPath id="clipPath2436"> <path d="M 0,0 L 0,0 L 0,0 L 0,0 z" id="path2438" /> </clipPath> </defs> <g clip-path="url(#clipPath2436)" id="g2460"> <text id="text2466"> <tspan>12345</tspan> </text> </g> <path style="fill:#346733;fill-rule:evenodd;stroke:#000000;stroke-width:1;stroke-linecap:round;stroke-linejoin:bevel;stroke-opacity:1;stroke-miterlimit:4;stroke-dasharray:1, 1;stroke-dashoffset:0;filter:url(\'#filter6226\');fill-opacity:1;opacity:0.79807692" d="M 236.82371,332.63732 C 236.92217,332.63732 z" id="path5618" /> </svg>',
				true,
				false,
				'SVG with local urls, including filter: in style'
			],
			[
				'<?xml version="1.0" encoding="UTF-8" standalone="no"?><!DOCTYPE x [<!ATTLIST image x:href CDATA "data:image/png,foo" onerror CDATA "alert(\'XSSED = \'+document.domain)" onload CDATA "alert(\'XSSED = \'+document.domain)"> ]> <svg xmlns:h="http://www.w3.org/1999/xhtml" xmlns:x="http://www.w3.org/1999/xlink" xmlns="http://www.w3.org/2000/svg"> <image /> </svg>',
				false,
				false,
				'SVG with evil default attribute values'
			],
			[
				'<?xml version="1.0" encoding="UTF-8" standalone="no"?><!DOCTYPE svg SYSTEM "data:application/xml-dtd;base64,PCFET0NUWVBFIHN2ZyBbPCFBVFRMSVNUIGltYWdlIHg6aHJlZiBDREFUQSAiZGF0YTppbWFnZS9wbmcsZm9vIiBvbmVycm9yIENEQVRBICJhbGVydCgnWFNTRUQgPSAnK2RvY3VtZW50LmRvbWFpbikiIG9ubG9hZCBDREFUQSAiYWxlcnQoJ1hTU0VEID0gJytkb2N1bWVudC5kb21haW4pIj4gXT4K"><svg xmlns:x="http://www.w3.org/1999/xlink" xmlns="http://www.w3.org/2000/svg"> <image /> </svg>',
				true,
				true,
				'SVG with an evil external dtd'
			],
			[
				'<?xml version="1.0" encoding="UTF-8"?><!DOCTYPE svg PUBLIC "-//FOO/bar" "http://example.com"><svg></svg>',
				true,
				true,
				'SVG with random public doctype'
			],
			[
				'<?xml version="1.0" encoding="UTF-8"?><!DOCTYPE svg SYSTEM \'http://example.com/evil.dtd\' ><svg></svg>',
				true,
				true,
				'SVG with random SYSTEM doctype'
			],
			[
				'<?xml version="1.0" encoding="UTF-8"?><!DOCTYPE svg [<!ENTITY % foo "bar" >] ><svg></svg>',
				false,
				false,
				'SVG with parameter entity'
			],
			[
				'<?xml version="1.0" encoding="UTF-8"?><!DOCTYPE svg [<!ENTITY foo "bar%a;" ] ><svg></svg>',
				false,
				false,
				'SVG with entity referencing parameter entity'
			],
			[
				'<?xml version="1.0" encoding="UTF-8"?><!DOCTYPE svg [<!ENTITY foo "bar0000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000"> ] ><svg></svg>',
				false,
				false,
				'SVG with long entity'
			],
			[
				'<?xml version="1.0" encoding="UTF-8"?><!DOCTYPE svg [<!ENTITY  foo \'"Hi", said bob\'> ] ><svg><g>&foo;</g></svg>',
				true,
				false,
				'SVG with apostrophe quote entity'
			],
			[
				'<?xml version="1.0" encoding="UTF-8"?><!DOCTYPE svg [<!ENTITY name "Bob"><!ENTITY  foo \'"Hi", said &name;.\'> ] ><svg><g>&foo;</g></svg>',
				false,
				false,
				'SVG with recursive entity',
			],
			[
				'<?xml version="1.0" encoding="UTF-8" standalone="no"?><!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.0//EN" "http://www.w3.org/TR/2001/REC-SVG-20010904/DTD/svg10.dtd" [ <!ATTLIST svg xmlns:xlink CDATA #FIXED "http://www.w3.org/1999/xlink"> ]> <svg width="417pt" height="366pt"
 viewBox="0.00 0.00 417.00 366.00" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"></svg>',
				true, /* well-formed */
				false, /* filter-hit */
				'GraphViz-esque svg with #FIXED xlink ns (Should be allowed)'
			],
			[
				'<?xml version="1.0" encoding="UTF-8" standalone="no"?><!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.0//EN" "http://www.w3.org/TR/2001/REC-SVG-20010904/DTD/svg10.dtd" [ <!ATTLIST svg xmlns:xlink CDATA #FIXED "http://www.w3.org/1999/xlink2"> ]> <svg width="417pt" height="366pt"
 viewBox="0.00 0.00 417.00 366.00" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"></svg>',
				false,
				false,
				'GraphViz ATLIST exception should match exactly'
			],
			[
				'<?xml version="1.0" encoding="UTF-8"?><!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN" "http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd" [ <!-- Comment-here --> <!ENTITY foo "#ff6666">]><svg xmlns="http://www.w3.org/2000/svg"></svg>',
				true,
				false,
				'DTD with comments (Should be allowed)'
			],
			[
				'<?xml version="1.0" encoding="UTF-8"?><!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN" "http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd" [ <!-- invalid--comment  --> <!ENTITY foo "#ff6666">]><svg xmlns="http://www.w3.org/2000/svg"></svg>',
				false,
				false,
				'DTD with invalid comment'
			],
			[
				'<?xml version="1.0" encoding="UTF-8"?><!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN" "http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd" [ <!-- invalid ---> <!ENTITY foo "#ff6666">]><svg xmlns="http://www.w3.org/2000/svg"></svg>',
				false,
				false,
				'DTD with invalid comment 2'
			],
			[
				'<?xml version="1.0" encoding="UTF-8"?><!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN" "http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd" [ <!ENTITY bar "&foo;"> <!ENTITY foo "#ff6666">]><svg xmlns="http://www.w3.org/2000/svg"></svg>',
				true,
				false,
				'DTD with aliased entities (Should be allowed)'
			],
			[
				'<?xml version="1.0" encoding="UTF-8"?><!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN" "http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd" [ <!ENTITY bar \'&foo;\'> <!ENTITY foo \'#ff6666\'>]><svg xmlns="http://www.w3.org/2000/svg"></svg>',
				true,
				false,
				'DTD with aliased entities apos (Should be allowed)'
			]
		];
		// @codingStandardsIgnoreEnd
	}

	/**
	 * @dataProvider provideDetectScriptInSvg
	 */
	public function testDetectScriptInSvg( $svg, $expected, $message ) {
		// This only checks some weird cases, most tests are in testCheckSvgScriptCallback() above
		$result = $this->upload->detectScriptInSvg( $svg, false );
		$this->assertSame( $expected, $result, $message );
	}

	public static function provideDetectScriptInSvg() {
		global $IP;
		return [
			[
				"$IP/tests/phpunit/data/upload/buggynamespace-original.svg",
				false,
				'SVG with a weird but valid namespace definition created by Adobe Illustrator'
			],
			[
				"$IP/tests/phpunit/data/upload/buggynamespace-okay.svg",
				false,
				'SVG with a namespace definition created by Adobe Illustrator and mangled by Inkscape'
			],
			[
				"$IP/tests/phpunit/data/upload/buggynamespace-okay2.svg",
				false,
				'SVG with a namespace definition created by Adobe Illustrator and mangled by Inkscape (twice)'
			],
			[
				"$IP/tests/phpunit/data/upload/buggynamespace-bad.svg",
				[ 'uploadscriptednamespace', 'i' ],
				'SVG with a namespace definition using an undefined entity'
			],
			[
				"$IP/tests/phpunit/data/upload/buggynamespace-evilhtml.svg",
				[ 'uploadscriptednamespace', 'http://www.w3.org/1999/xhtml' ],
				'SVG with an html namespace encoded as an entity'
			],
		];
	}

	/**
	 * @dataProvider provideCheckXMLEncodingMissmatch
	 */
	public function testCheckXMLEncodingMissmatch( $fileContents, $evil ) {
		$filename = $this->getNewTempFile();
		file_put_contents( $filename, $fileContents );
		$this->assertSame( UploadBase::checkXMLEncodingMissmatch( $filename ), $evil );
	}

	public function provideCheckXMLEncodingMissmatch() {
		return [
			[ '<?xml version="1.0" encoding="utf-7"?><svg></svg>', true ],
			[ '<?xml version="1.0" encoding="utf-8"?><svg></svg>', false ],
			[ '<?xml version="1.0" encoding="WINDOWS-1252"?><svg></svg>', false ],
		];
	}
}

class UploadTestHandler extends UploadBase {
	public function initializeFromRequest( &$request ) {
	}

	public function testTitleValidation( $name ) {
		$this->mTitle = false;
		$this->mDesiredDestName = $name;
		$this->mTitleError = UploadBase::OK;
		$this->getTitle();

		return $this->mTitleError;
	}

	/**
	 * Almost the same as UploadBase::detectScriptInSvg, except it's
	 * public, works on an xml string instead of filename, and returns
	 * the result instead of interpreting them.
	 */
	public function checkSvgString( $svg ) {
		$check = new XmlTypeCheck(
			$svg,
			[ $this, 'checkSvgScriptCallback' ],
			false,
			[
				'processing_instruction_handler' => 'UploadBase::checkSvgPICallback',
				'external_dtd_handler' => 'UploadBase::checkSvgExternalDTD'
			]
		);
		return [ $check->wellFormed, $check->filterMatch ];
	}

	/**
	 * Same as parent function, but override visibility to 'public'.
	 */
	public function detectScriptInSvg( $filename, $partial ) {
		return parent::detectScriptInSvg( $filename, $partial );
	}
}
