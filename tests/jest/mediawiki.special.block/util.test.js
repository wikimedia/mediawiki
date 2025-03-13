const util = require( '../../../resources/src/mediawiki.special.block/util.js' );

describe( 'util', () => {
	const santizeRangeTestCases = [
		{
			title: 'IPv4 range',
			input: '35.56.31.252/16',
			isIPv4Address: true,
			expected: '35.56.0.0/16'
		}, {
			title: 'IPv4 range 2',
			input: '135.16.21.252/24',
			isIPv4Address: true,
			expected: '135.16.21.0/24'
		}, {
			title: 'IPv4 large range',
			input: '135.16.21.252/1',
			isIPv4Address: true,
			expected: '128.0.0.0/1'
		}, {
			title: 'IPv4 intermediate range',
			input: '135.16.255.255/23',
			isIPv4Address: true,
			expected: '135.16.254.0/23'
		}, {
			title: 'IPv4 silly range',
			input: '5.36.71.252/32',
			isIPv4Address: true,
			expected: '5.36.71.252/32'
		}, {
			title: 'IPv4 non-range',
			input: '5.36.71.252',
			isIPv4Address: true,
			expected: '5.36.71.252'
		}, {
			title: 'IPv6 range',
			input: '0:1:2:3:4:c5:f6:7/96',
			sanitizeIP: '0:1:2:3:4:C5:F6:7/96',
			isIPv6Address: true,
			expected: '0:1:2:3:4:C5:0:0/96'
		}, {
			title: 'IPv6 range 2',
			input: '0:1:2:3:4:5:6:7/120',
			isIPv6Address: true,
			expected: '0:1:2:3:4:5:6:0/120'
		}, {
			title: 'IPv6 large range',
			input: 'ffff::/3',
			sanitizeIP: 'FFFF::/3',
			isIPv6Address: true,
			expected: 'E000:0:0:0:0:0:0:0/3'
		}, {
			title: 'IPv6 intermediate range',
			input: '0:1:2:3:4:5:6:7/95',
			isIPv6Address: true,
			expected: '0:1:2:3:4:4:0:0/95'
		}, {
			title: 'IPv6 silly range',
			input: '0:e1:2:3:4:5:e6:7/128',
			sanitizeIP: '0:E1:2:3:4:5:E6:7',
			isIPv6Address: true,
			expected: '0:E1:2:3:4:5:E6:7/128'
		}, {
			title: 'IPv6 non range',
			input: '0:c1:A2:3:4:5:c6:7',
			sanitizeIP: '0:C1:A2:3:4:5:C6:7',
			isIPv6Address: true,
			expected: '0:C1:A2:3:4:5:C6:7'
		}
	];
	it.each( santizeRangeTestCases )( 'sanitizeRange ($title)',
		( { input, expected, sanitizeIP, isIPv4Address, isIPv6Address } ) => {
			mw.util.sanitizeIP = jest.fn().mockReturnValue( sanitizeIP || input );
			mw.util.isIPv4Address = jest.fn().mockReturnValue( !!isIPv4Address );
			mw.util.isIPv6Address = jest.fn().mockReturnValue( !!isIPv6Address );
			expect( util.sanitizeRange( input ) ).toBe( expected );
		}
	);

	const formatTimestampCases = [
		{
			title: 'infinity',
			input: 'infinity',
			expected: 'infiniteblock'
		},
		{
			title: 'finite',
			input: '2029-09-20T14:31:51Z',
			expected: '2029-09-20T14:31:51.000Z'
		}
	];

	it.each( formatTimestampCases )( 'formatTimestamp', ( { input, expected } ) => {
		mw.util.isInfinity = jest.fn().mockReturnValue( input === 'infinity' );
		expect( util.formatTimestamp( input ) ).toBe( expected );
	} );
} );
