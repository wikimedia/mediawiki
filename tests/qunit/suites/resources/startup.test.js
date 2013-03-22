/*global isCompatible: true */
( function ( $ ) {
	var browsers =
			[
				[ true,  'Mozilla/5.0 (Mobile; rv:14.0) Gecko/14.0 Firefox/14.0' ],
				[ true,  'Mozilla/5.0 (BB10; Touch) AppleWebKit/537.3+ (KHTML, like Gecko) Version/10.0.9.386 Mobile Safari/537.3+' ],
				[ false, 'Mozilla/4.0 (compatible; MSIE 4.01; Windows CE; Sprint;PPC-i830; PPC; 240x320)' ],
				[ true,  'Mozilla/5.0 (compatible; MSIE 10.0; Windows Phone 8.0; Trident/6.0; ARM; Touch; IEMobile/10.0; <Manufacturer>; <Device> [;<Operator>])' ],
				[ true,  'Mozilla/5.0 (Linux; U; Android 2.1; en-us; Nexus One Build/ERD62) AppleWebKit/530.17 (KHTML, like Gecko) Version/4.0 Mobile Safari/530.17' ],
				[ true,  'Mozilla/5.0 (ipod: U;CPU iPhone OS 2_2 like Mac OS X: es_es) AppleWebKit/525.18.1 (KHTML, like Gecko) Version/3.0 Mobile/3B48b Safari/419.3' ],
				[ true,  'Mozilla/5.0 (iPhone; U; CPU like Mac OS X; en) AppleWebKit/420.1 (KHTML, like Gecko) Version/3.0 Mobile/3B48b Safari/419.3' ],
				[ false, 'Mozilla/5.0 (SymbianOS/9.1; U; [en]; SymbianOS/91 Series60/3.0) AppleWebKit/413 (KHTML, like Gecko) Safari/413' ],
				[ false, 'Mozilla/5.0 (webOS/1.0; U; en-US) AppleWebKit/525.27.1 (KHTML, like Gecko) Version/1.0 Safari/525.27.1 Pre/1.0' ],
				[ true,  'Opera/9.00 (Nintendo Wii; U; ; 1309-9; en)' ],
				[ false, 'Opera/9.50 (J2ME/MIDP; Opera Mini/4.0.10031/298; U; en)' ],
				[ true,  'Opera/9.51 Beta (Microsoft Windows; PPC; Opera Mobi/1718; U; en)' ],
				[ false, 'Mozilla/4.0 (compatible; Linux 2.6.10) NetFront/3.3 Kindle/1.0 (screen 600x800)' ],
				[ false, 'Mozilla/4.0 (compatible; Linux 2.6.22) NetFront/3.4 Kindle/2.0 (screen 824x1200; rotate)' ],
				[ false, 'Mozilla/4.08 (Windows; Mobile Content Viewer/1.0) NetFront/3.2' ],
				[ false, 'SonyEricssonK608i/R2L/SN356841000828910 Browser/SEMC-Browser/4.2 Profile/MIDP-2.0 Configuration/CLDC-1.1' ],
				[ false, 'NokiaN73-2/3.0-630.0.2 Series60/3.0 Profile/MIDP-2.0 Configuration/CLDC-1.1' ],
				[ false, 'Mozilla/4.0 (PSP (PlayStation Portable); 2.00)' ],
				[ false, 'Mozilla/5.0 (PLAYSTATION 3; 1.00)' ],
				[ false, 'BlackBerry9300/5.0.0.716 Profile/MIDP-2.1 Configuration/CLDC-1.1 VendorID/133' ],
				[ false, 'BlackBerry7250/4.0.0 Profile/MIDP-2.0 Configuration/CLDC-1.1' ],
				[ false, 'I\'m an unknown mobile browser' ],

				// Desktop
				[ true,  'Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.8.0.7) Gecko/20060928 (Debian|Debian-1.8.0.7-1) Epiphany/2.14' ],
				[ false, 'Mozilla/1.22 (compatible; MSIE 2.0; Windows 3.1)' ],
				[ false, 'Mozilla/4.0 (compatible; MSIE 4.01; Windows NT)' ],
				[ false, 'Mozilla/4.0 (compatible; MSIE 5.0; Windows NT 5.2; .NET CLR 1.1.4322)' ],
				[ true,  'Mozilla/5.0 (Windows; U; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 2.0.50727)' ],
				[ true,  'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.0)' ],
				[ true,  'Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; Trident/5.0)' ],
				[ true,  'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_6_7; en-US) AppleWebKit/534.16 (KHTML, like Gecko) Chrome/10.0.648.205 Safari/534.16' ],
				[ true,  'Mozilla/5.0 (X11; U; Linux i686 (x86_64); en-US; rv:1.8.1.6) Gecko/20070817 IceWeasel/2.0.0.6-g2' ],
				[ true,  'Mozilla/5.0 (X11; U; Linux i686 (x86_64); en-US; rv:1.8.1.11) Gecko/20071203 IceCat/2.0.0.11-g1' ],
				[ true,  'Mozilla/5.0 (compatible; Konqueror/4.3; Linux) KHTML/4.3.5 (like Gecko)' ],
				[ true,  'Mozilla/5.0 (X11; Ubuntu; Linux i686; rv:16.0) Gecko/20120815 Firefox/16.0' ],
				[ true,  'Opera/9.80 (Windows NT 6.1; U; ru) Presto/2.8.131 Version/11.10' ],
				[ true,  'Mozilla/5.0 (Macintosh; I; Intel Mac OS X 10_6_7; ru-ru) AppleWebKit/534.31+ (KHTML, like Gecko) Version/5.0.5 Safari/533.21.1' ],
				[ true,  'I\'m an unknown desktop browser' ],

				// While not true JS-aware browsers, text browsers and crawlers are something that needs no special treatment
				[ true,  'Links (2.2; GNU/kFreeBSD 6.3-1-486 i686; 80x25)' ], // Jidanni made me put this here
				[ true,  'Lynx/2.8.6rel.4 libwww-FM/2.14 SSL-MM/1.4.1 OpenSSL/0.9.8g' ],
				[ true,  'w3m/0.5.1' ],
				[ true,  'Googlebot/2.1 (+http://www.google.com/bot.html)' ],
				[ true,  'Mozilla/5.0 (compatible; googlebot/2.1; +http://www.google.com/bot.html)' ],
				[ true,  'Wget/1.9' ],
				[ true,  'Mozilla/5.0 (compatible; YandexBot/3.0)' ]
			];

	QUnit.module( 'startup', QUnit.newMwEnvironment() );

	QUnit.test( 'isCompatible', browsers.length, function ( assert ) {
		$.each( browsers, function( index, device ) {
				assert.equal( isCompatible( device[1] ), device[0],
					'"' + device[1] + '" ' + ( device[0] ? 'supports' : 'does not support' ) + ' jQuery'
				);
			}
		);
	} );
}( jQuery ) );

