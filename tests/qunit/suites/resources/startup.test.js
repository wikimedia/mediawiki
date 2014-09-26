/*global isCompatible: true */
( function ( $ ) {
	var testcases = {
		gradeA: [
			// Chrome
			'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_6_7; en-US) AppleWebKit/534.16 (KHTML, like Gecko) Chrome/10.0.648.205 Safari/534.16',
			// Firefox 4+
			'Mozilla/5.0 (Windows NT 6.1.1; rv:5.0) Gecko/20100101 Firefox/5.0',
			'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.6; rv:9.0) Gecko/20100101 Firefox/9.0',
			'Mozilla/5.0 (Macintosh; I; Intel Mac OS X 11_7_9; de-LI; rv:1.9b4) Gecko/2012010317 Firefox/10.0a4',
			'Mozilla/5.0 (Windows NT 6.1; rv:12.0) Gecko/20120403211507 Firefox/12.0',
			'Mozilla/5.0 (Windows NT 6.2; Win64; x64; rv:16.0.1) Gecko/20121011 Firefox/16.0.1',
			// Kindle Fire
			'Mozilla/5.0 (Linux; U; Android 2.3.4; en-us; Kindle Fire Build/GINGERBREAD) AppleWebKit/533.1 (KHTML, like Gecko) Version/4.0 Safari/533.1',
			// Safari 5.0+
			'Mozilla/5.0 (Macintosh; I; Intel Mac OS X 10_6_7; ru-ru) AppleWebKit/534.31+ (KHTML, like Gecko) Version/5.0.5 Safari/533.21.1',
			// Opera 12+ (Presto-based)
			'Opera/9.80 (Windows NT 6.1; U; es-ES) Presto/2.9.181 Version/12.00',
			'Opera/9.80 (Windows NT 5.1) Presto/2.12.388 Version/12.17',
			// Opera 15+ (Chromium-based)
			'Mozilla/5.0 (Windows NT 6.0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/28.0.1500.95 Safari/537.36 OPR/15.0.1147.153',
			'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/29.0.1547.57 Safari/537.36 OPR/16.0.1196.62',
			'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/36.0.1985.125 Safari/537.36 OPR/23.0.1522.75',
			// Internet Explorer 8+
			'Mozilla/5.0 (compatible; MSIE 8.0; Windows NT 5.2; Trident/4.0; Media Center PC 4.0; SLCC1; .NET CLR 3.0.04320)',
			'Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 7.1; Trident/5.0)',
			'Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.1; WOW64; Trident/6.0)',
			// IE Mobile
			'Mozilla/5.0 (compatible; MSIE 9.0; Windows Phone OS 7.5; Trident/5.0; IEMobile/9.0; NOKIA; Lumia 800)',
			// BlackBerry 6+
			'Mozilla/5.0 (BlackBerry; U; BlackBerry 9300; en) AppleWebKit/534.8+ (KHTML, like Gecko) Version/6.0.0.570 Mobile Safari/534.8+',
			'Mozilla/5.0 (BlackBerry; U; BlackBerry 9900; en) AppleWebKit/534.11+ (KHTML, like Gecko) Version/7.1.0.346 Mobile Safari/534.11+',
			'Mozilla/5.0 (BB10; Touch) AppleWebKit/537.3+ (KHTML, like Gecko) Version/10.0.9.386 Mobile Safari/537.3+',
			// Open WebOS 1.4+ (HP Veer 4G)
			'Mozilla/5.0 (webOS/2.1.2; U; en-US) AppleWebKit/532.2 (KHTML, like Gecko) Version/1.0 Safari/532.2 P160UNA/1.0',
			// Firefox Mobile
			'Mozilla/5.0 (Mobile; rv:14.0) Gecko/14.0 Firefox/14.0',
			// iOS
			'Mozilla/5.0 (ipod: U;CPU iPhone OS 2_2 like Mac OS X: es_es) AppleWebKit/525.18.1 (KHTML, like Gecko) Version/3.0 Mobile/3B48b Safari/419.3',
			'Mozilla/5.0 (iPhone; U; CPU like Mac OS X; en) AppleWebKit/420.1 (KHTML, like Gecko) Version/3.0 Mobile/3B48b Safari/419.3',
			// Android
			'Mozilla/5.0 (Linux; U; Android 2.1; en-us; Nexus One Build/ERD62) AppleWebKit/530.17 (KHTML, like Gecko) Version/4.0 Mobile Safari/530.17'
		],
		gradeC: [
			// Internet Explorer < 8
			'Mozilla/2.0 (compatible; MSIE 3.03; Windows 3.1)',
			'Mozilla/4.0 (compatible; MSIE 4.01; Windows 95)',
			'Mozilla/4.0 (compatible; MSIE 5.0; Windows 98;)',
			'Mozilla/4.0 (compatible; MSIE 5.5; Windows NT)',
			'Mozilla/5.0 (compatible; MSIE 6.0; Windows NT 5.1)',
			'Mozilla/5.0 (compatible; MSIE 7.0; Windows NT 6.0; en-US)',
			// Firefox < 3
			'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.0.2) Gecko/20060308 Firefox/1.5.0.2',
			'Mozilla/5.0 (X11; U; Linux i686; nl; rv:1.8.1.1) Gecko/20070311 Firefox/2.0.0.1',
			// Opera < 12
			'Mozilla/5.0 (Windows NT 5.0; U) Opera 7.54 [en]',
			'Opera/7.54 (Windows NT 5.0; U) [en]',
			'Mozilla/5.0 (Windows NT 5.1; U; en) Opera 8.0',
			'Opera/8.0 (X11; Linux i686; U; cs)',
			'Opera/9.00 (X11; Linux i686; U; de)',
			'Opera/9.62 (X11; Linux i686; U; en) Presto/2.1.1',
			'Opera/9.80 (Windows NT 6.1; U; en) Presto/2.2.15 Version/10.00',
			'Opera/9.80 (Windows NT 6.1; U; ru) Presto/2.8.131 Version/11.10',
			'Opera/9.80 (Windows NT 6.1; WOW64; U; pt) Presto/2.10.229 Version/11.62',
			// BlackBerry < 6
			'BlackBerry9300/5.0.0.716 Profile/MIDP-2.1 Configuration/CLDC-1.1 VendorID/133',
			'BlackBerry7250/4.0.0 Profile/MIDP-2.0 Configuration/CLDC-1.1',
			// Open WebOS < 1.5 (Palm Pre, Palm Pixi)
			'Mozilla/5.0 (webOS/1.0; U; en-US) AppleWebKit/525.27.1 (KHTML, like Gecko) Version/1.0 Safari/525.27.1 Pre/1.0',
			'Mozilla/5.0 (webOS/1.4.0; U; en-US) AppleWebKit/532.2 (KHTML, like Gecko) Version/1.0 Safari/532.2 Pixi/1.1 ',
			// SymbianOS
			'NokiaN95_8GB-3;Mozilla/5.0 SymbianOS/9.2;U;Series60/3.1 NokiaN95_8GB-3/11.2.011 Profile/MIDP-2.0 Configuration/CLDC-1.1 AppleWebKit/413 (KHTML, like Gecko)',
			'Nokia7610/2.0 (5.0509.0) SymbianOS/7.0s Series60/2.1 Profile/MIDP-2.0 Configuration/CLDC-1.0 ',
			'Mozilla/5.0 (SymbianOS/9.1; U; [en]; SymbianOS/91 Series60/3.0) AppleWebKit/413 (KHTML, like Gecko) Safari/413',
			'Mozilla/5.0 (SymbianOS/9.3; Series60/3.2 NokiaE52-2/091.003; Profile/MIDP-2.1 Configuration/CLDC-1.1 ) AppleWebKit/533.4 (KHTML, like Gecko) NokiaBrowser/7.3.1.34 Mobile Safari/533.4',
			// NetFront
			'Mozilla/4.0 (compatible; Linux 2.6.10) NetFront/3.3 Kindle/1.0 (screen 600x800)',
			'Mozilla/4.0 (compatible; Linux 2.6.22) NetFront/3.4 Kindle/2.0 (screen 824x1200; rotate)',
			'Mozilla/4.08 (Windows; Mobile Content Viewer/1.0) NetFront/3.2',
			// Opera Mini
			'Opera/9.80 (J2ME/MIDP; Opera Mini/3.1.10423/22.387; U; en) Presto/2.5.25 Version/10.54',
			'Opera/9.50 (J2ME/MIDP; Opera Mini/4.0.10031/298; U; en)',
			'Opera/9.80 (J2ME/MIDP; Opera Mini/6.24093/26.1305; U; en) Presto/2.8.119 Version/10.54',
			'Opera/9.80 (Android; Opera Mini/7.29530/27.1407; U; en) Presto/2.8.119 Version/11.10',
			// Ovi Browser
			'Mozilla/5.0 (Series40; NokiaX3-02/05.60; Profile/MIDP-2.1 Configuration/CLDC-1.1) Gecko/20100401 S40OviBrowser/3.2.0.0.6',
			'Mozilla/5.0 (Series40; Nokia305/05.92; Profile/MIDP-2.1 Configuration/CLDC-1.1) Gecko/20100401 S40OviBrowser/3.7.0.0.11',
			// Google Glass
			'Mozilla/5.0 (Linux; U; Android 4.0.4; en-us; Glass 1 Build/IMM76L; XE11) AppleWebKit/534.30 (KHTML, like Gecko) Version/4.0 Mobile Safari/534.30'
		],
		// No explicit support for or against these browsers, they're given a shot at Grade A.
		gradeX: [
			// Firefox 3.6
			'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3',
			// Gecko
			'Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.8.0.7) Gecko/20060928 (Debian|Debian-1.8.0.7-1) Epiphany/2.14',
			'Mozilla/5.0 (X11; U; Linux i686 (x86_64); en-US; rv:1.8.1.6) Gecko/20070817 IceWeasel/2.0.0.6-g2',
			// KHTML
			'Mozilla/5.0 (compatible; Konqueror/3.5; Linux) KHTML/3.5.4 (like Gecko)',
			'Mozilla/5.0 (compatible; Konqueror/4.3; Linux) KHTML/4.3.5 (like Gecko)',
			// Text browsers
			'Links (2.1pre33; Darwin 8.11.0 Power Macintosh; x)',
			'Links (6.9; Unix 6.9-astral sparc; 80x25)',
			'Lynx/2.8.6rel.4 libwww-FM/2.14 SSL-MM/1.4.1 OpenSSL/0.9.8g',
			'w3m/0.5.1',
			// Bots
			'Googlebot/2.1 (+http://www.google.com/bot.html)',
			'Mozilla/5.0 (compatible; googlebot/2.1; +http://www.google.com/bot.html)',
			'Mozilla/5.0 (compatible; YandexBot/3.0)',
			// Scripts
			'curl/7.21.4 (universal-apple-darwin11.0) libcurl/7.21.4 OpenSSL/0.9.8r zlib/1.2.5',
			'Wget/1.9',
			'Wget/1.10.1 (Red Hat modified)',
			// Unknown
			'I\'m an unknown browser',
			// Empty
			''
		]
	};

	QUnit.module( 'startup', QUnit.newMwEnvironment() );

	QUnit.test( 'isCompatible( Grade A )', testcases.gradeA.length, function ( assert ) {
		$.each( testcases.gradeA, function ( i, ua ) {
				assert.strictEqual( isCompatible( ua ), true, ua );
			}
		);
	} );

	QUnit.test( 'isCompatible( Grade C )', testcases.gradeC.length, function ( assert ) {
		$.each( testcases.gradeC, function ( i, ua ) {
				assert.strictEqual( isCompatible( ua ), false, ua );
			}
		);
	} );

	QUnit.test( 'isCompatible( Grade X )', testcases.gradeX.length, function ( assert ) {
		$.each( testcases.gradeX, function ( i, ua ) {
				assert.strictEqual( isCompatible( ua ), true, ua );
			}
		);
	} );

}( jQuery ) );
