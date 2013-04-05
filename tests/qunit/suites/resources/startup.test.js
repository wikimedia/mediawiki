/*global isCompatible: true */
( function ( $ ) {
	var testcases = {
		// Supported: Compatible
		gradeA: [
			// Chrome
			'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_6_7; en-US) AppleWebKit/534.16 (KHTML, like Gecko) Chrome/10.0.648.205 Safari/534.16',
			// Firefox 10+
			'Mozilla/5.0 (Macintosh; I; Intel Mac OS X 11_7_9; de-LI; rv:1.9b4) Gecko/2012010317 Firefox/10.0a4',
			'Mozilla/5.0 (Windows NT 6.1; rv:12.0) Gecko/20120403211507 Firefox/12.0',
			'Mozilla/5.0 (Windows NT 6.2; Win64; x64; rv:16.0.1) Gecko/20121011 Firefox/16.0.1',
			// Safari 5.0+
			'Mozilla/5.0 (Macintosh; I; Intel Mac OS X 10_6_7; ru-ru) AppleWebKit/534.31+ (KHTML, like Gecko) Version/5.0.5 Safari/533.21.1',
			// Opera 11+
			'Opera/9.80 (Windows NT 6.1; U; ru) Presto/2.8.131 Version/11.10',
			// Internet Explorer 6+
			'Mozilla/5.0 (compatible; MSIE 6.0; Windows NT 5.1)',
			'Mozilla/5.0 (compatible; MSIE 7.0; Windows NT 6.0; en-US)',
			'Mozilla/5.0 (compatible; MSIE 8.0; Windows NT 5.2; Trident/4.0; Media Center PC 4.0; SLCC1; .NET CLR 3.0.04320)',
			'Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 7.1; Trident/5.0)',
			'Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.1; WOW64; Trident/6.0)'
		],
		// Supported: Uncompatible, serve basic content
		gradeB: [
			// Internet Explorer < 6
			'Mozilla/2.0 (compatible; MSIE 3.03; Windows 3.1)',
			'Mozilla/4.0 (compatible; MSIE 4.01; Windows 95)',
			'Mozilla/4.0 (compatible; MSIE 5.0; Windows 98;)',
			'Mozilla/4.0 (compatible; MSIE 5.5; Windows NT)',
			// Firefox < 10
			'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.0.2) Gecko/20060308 Firefox/1.5.0.2',
			'Mozilla/5.0 (X11; U; Linux i686; nl; rv:1.8.1.1) Gecko/20070311 Firefox/2.0.0.1',
			'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3',
			'Mozilla/5.0 (Windows NT 6.1.1; rv:5.0) Gecko/20100101 Firefox/5.0',
			'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.6; rv:9.0) Gecko/20100101 Firefox/9.0'

		],
		// No special treatment blacklisting, benefit of doubt at own risk
		gradeX: [
			// Gecko
			'Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.8.0.7) Gecko/20060928 (Debian|Debian-1.8.0.7-1) Epiphany/2.14',
			'Mozilla/5.0 (X11; U; Linux i686 (x86_64); en-US; rv:1.8.1.6) Gecko/20070817 IceWeasel/2.0.0.6-g2',
			// KHTML
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
	QUnit.test( 'isCompatible( Grade B )', testcases.gradeB.length, function ( assert ) {
		$.each( testcases.gradeB, function ( i, ua ) {
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

