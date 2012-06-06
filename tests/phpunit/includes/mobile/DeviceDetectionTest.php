<?php

/**
 * @group Mobile
 */
 class DeviceDetectionTest extends MediaWikiTestCase {

	/**
	 * @dataProvider provideTestFormatName
	 */
	public function testFormatName( $format, $userAgent ) {
		$detector = new DeviceDetection();
		$this->assertEquals( $format, $detector->detectFormatName( $userAgent ) );
	}

	public function provideTestFormatName() {
		return array(
			array( 'android',   'Mozilla/5.0 (Linux; U; Android 2.1; en-us; Nexus One Build/ERD62) AppleWebKit/530.17 (KHTML, like Gecko) Version/4.0 Mobile Safari/530.17' ),
			array( 'iphone2',   'Mozilla/5.0 (ipod: U;CPU iPhone OS 2_2 like Mac OS X: es_es) AppleWebKit/525.18.1 (KHTML, like Gecko) Version/3.0 Mobile/3B48b Safari/419.3' ),
			array( 'iphone',    'Mozilla/5.0 (iPhone; U; CPU like Mac OS X; en) AppleWebKit/420.1 (KHTML, like Gecko) Version/3.0 Mobile/3B48b Safari/419.3' ),
			array( 'nokia',     'Mozilla/5.0 (SymbianOS/9.1; U; [en]; SymbianOS/91 Series60/3.0) AppleWebKit/413 (KHTML, like Gecko) Safari/413' ),
			array( 'palm_pre',  'Mozilla/5.0 (webOS/1.0; U; en-US) AppleWebKit/525.27.1 (KHTML, like Gecko) Version/1.0 Safari/525.27.1 Pre/1.0' ),
			array( 'wii',       'Opera/9.00 (Nintendo Wii; U; ; 1309-9; en)' ),
			array( 'operamini', 'Opera/9.50 (J2ME/MIDP; Opera Mini/4.0.10031/298; U; en)' ),
			array( 'operamobile',    'Opera/9.51 Beta (Microsoft Windows; PPC; Opera Mobi/1718; U; en)' ),
			array( 'kindle',    'Mozilla/4.0 (compatible; Linux 2.6.10) NetFront/3.3 Kindle/1.0 (screen 600x800)' ),
			array( 'kindle2',   'Mozilla/4.0 (compatible; Linux 2.6.22) NetFront/3.4 Kindle/2.0 (screen 824x1200; rotate)' ),
			array( 'capable',   'Mozilla/5.0 (X11; Linux i686; rv:2.0.1) Gecko/20100101 Firefox/4.0.1' ),
			array( 'netfront',  'Mozilla/4.08 (Windows; Mobile Content Viewer/1.0) NetFront/3.2' ),
			array( 'wap2',      'SonyEricssonK608i/R2L/SN356841000828910 Browser/SEMC-Browser/4.2 Profile/MIDP-2.0 Configuration/CLDC-1.1' ),
			array( 'wap2',      'NokiaN73-2/3.0-630.0.2 Series60/3.0 Profile/MIDP-2.0 Configuration/CLDC-1.1' ),
			array( 'psp',       'Mozilla/4.0 (PSP (PlayStation Portable); 2.00)' ),
			array( 'ps3',       'Mozilla/5.0 (PLAYSTATION 3; 1.00)' ),
			array( 'ie', 'Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; Trident/5.0)' ),
			array( 'ie', 'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.0)' ),
			array( 'blackberry', 'BlackBerry9300/5.0.0.716 Profile/MIDP-2.1 Configuration/CLDC-1.1 VendorID/133' ),
			array( 'blackberry-lt5', 'BlackBerry7250/4.0.0 Profile/MIDP-2.0 Configuration/CLDC-1.1' ),
		);
	}
}
