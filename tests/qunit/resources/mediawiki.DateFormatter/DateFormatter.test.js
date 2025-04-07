const DateFormatter = require( 'mediawiki.DateFormatter' );
const midnightZulu = new Date( '2025-01-01T00:00:00Z' );
const oneZulu = new Date( '2025-01-01T01:00:00Z' );
const nextDay = new Date( '2025-01-02T00:00:00Z' );

QUnit.module( 'mediawiki.DateFormatter static functions', ( hooks ) => {
	let userOptions;

	function fakeOptionsGet( key, fallback ) {
		return key in userOptions ? userOptions[ key ] : fallback;
	}

	hooks.beforeEach( function () {
		userOptions = {
			timecorrection: 'Offset|60'
		};
		this.sandbox.stub( mw.user.options, 'get', fakeOptionsGet );
		DateFormatter.clearInstanceCache();
	} );

	QUnit.test( 'forUser', ( assert ) => {
		const { forUser } = DateFormatter;
		const instance = forUser();
		assert.true( instance instanceof DateFormatter );
		assert.strictEqual( instance.formatTime( midnightZulu ), '01:00' );
	} );

	QUnit.test( 'forUtc', ( assert ) => {
		const { forUtc } = DateFormatter;
		const instance = forUtc();
		assert.true( instance instanceof DateFormatter );
		assert.strictEqual( instance.formatTime( midnightZulu ), '00:00' );
	} );

	QUnit.test( 'forSiteZone', ( assert ) => {
		DateFormatter.config.localZone = 'Etc/GMT-4';
		DateFormatter.config.localOffset = 4 * 60;
		DateFormatter.clearInstanceCache();

		const { forSiteZone } = DateFormatter;
		const instance = forSiteZone();
		assert.true( instance instanceof DateFormatter );
		assert.strictEqual( instance.formatTime( midnightZulu ), '04:00' );
	} );

	const formatTimeAndDateCases = [
		{
			title: 'null',
			dateOption: null,
			expected: '01:00, 1 (january) 2025'
		},
		{
			title: 'mdy',
			dateOption: 'mdy',
			expected: '01:00, (january) 1, 2025'
		},
		{
			title: 'bad option',
			dateOption: 'bad',
			expected: '01:00, 1 (january) 2025'
		}
	];

	QUnit.test.each(
		'formatTimeAndDate',
		formatTimeAndDateCases,
		( assert, { dateOption, expected } ) => {
			userOptions.date = dateOption;
			const { formatTimeAndDate } = DateFormatter;
			assert.strictEqual(
				formatTimeAndDate( midnightZulu ),
				expected
			);
		}
	);

	QUnit.test( 'formatTime', ( assert ) => {
		const { formatTime } = DateFormatter;
		assert.strictEqual(
			formatTime( midnightZulu ),
			'01:00'
		);
	} );

	QUnit.test( 'formatDate', ( assert ) => {
		const { formatDate } = DateFormatter;
		assert.strictEqual(
			formatDate( midnightZulu ),
			'1 (january) 2025'
		);
	} );

	QUnit.test( 'formatPrettyDate', ( assert ) => {
		const { formatPrettyDate } = DateFormatter;
		assert.strictEqual(
			formatPrettyDate( midnightZulu ),
			'1 (january)'
		);
	} );

	QUnit.test( 'formatIso', ( assert ) => {
		const { formatIso } = DateFormatter;
		assert.strictEqual(
			formatIso( midnightZulu ),
			'2025-01-01T01:00:00+01:00'
		);
	} );

	QUnit.test( 'formatForDateTimeInput', ( assert ) => {
		const { formatForDateTimeInput } = DateFormatter;
		assert.strictEqual(
			formatForDateTimeInput( midnightZulu ),
			'2025-01-01T01:00'
		);
	} );

	QUnit.test( 'formatTimeAndDateRange', ( assert ) => {
		const { formatTimeAndDateRange } = DateFormatter;
		assert.strictEqual(
			formatTimeAndDateRange( midnightZulu, oneZulu ),
			// Just descriptive, we don't supply fixed patterns for ranges
			// Ideally it would at least use our month name "(january)"
			'January 1, 2025, 01:00 – 02:00'
		);
	} );

	QUnit.test( 'formatTimeRange', ( assert ) => {
		const { formatTimeRange } = DateFormatter;
		assert.strictEqual(
			formatTimeRange( midnightZulu, oneZulu ),
			// Just descriptive
			'01:00 – 02:00'
		);
	} );

	QUnit.test( 'formatDateRange', ( assert ) => {
		const { formatDateRange } = DateFormatter;
		assert.strictEqual(
			formatDateRange( midnightZulu, nextDay ),
			// Just descriptive
			'January 1 – 2, 2025'
		);
	} );

	const normalizeZoneCases = {
		'Known zone': {
			supportsOffset: true,
			opt: 'ZoneInfo|660|Australia/Sydney',
			zone: 'Etc/GMT',
			offset: 0,
			expected: 'Australia/Sydney'
		},
		'Supported offset': {
			supportsOffset: true,
			opt: 'Offset|60',
			zone: 'Etc/GMT',
			offset: 0,
			expected: '+01:00'
		},
		'Whole hour offset unsupported': {
			supportsOffset: false,
			opt: 'Offset|60',
			zone: 'Etc/GMT',
			offset: 0,
			expected: 'Etc/GMT-1'
		},
		'Partial hour offset unsupported': {
			supportsOffset: false,
			opt: 'Offset|61',
			zone: 'Etc/GMT',
			offset: 0,
			expected: undefined
		},
		'Null fallback': {
			supportsOffset: true,
			opt: null,
			zone: 'Australia/Sydney',
			offset: 600,
			expected: 'Australia/Sydney'
		},
		'Null fallback to offset': {
			supportsOffset: true,
			opt: null,
			zone: null,
			offset: 600,
			expected: '+10:00'
		},
		'Unknown zone fallback': {
			supportsOffset: true,
			opt: 'Atlantic/Atlantis',
			zone: 'Australia/Sydney',
			offset: 600,
			expected: 'Australia/Sydney'
		},
		'Unknown default zone': {
			supportsOffset: true,
			opt: null,
			zone: 'Atlantic/Atlantis',
			offset: -120,
			expected: '-02:00'
		}
	};

	QUnit.test.each( 'priv.normalizeZone', normalizeZoneCases,
		function ( assert, { supportsOffset, opt, zone, offset, expected } ) {
			this.sandbox.stub( DateFormatter.priv, 'supportsOffset', () => supportsOffset );
			DateFormatter.clearInstanceCache();
			const result = DateFormatter.priv.normalizeZone( opt, zone, offset );
			assert.strictEqual( result, expected );
		}
	);

} );

QUnit.module( 'mediawiki.DateFormatter instance methods', ( hooks ) => {
	let userOptions;

	function fakeOptionsGet( key, fallback ) {
		userOptions = {
			timecorrection: 'Offset|60'
		};
		return key in userOptions ? userOptions[ key ] : fallback;
	}

	hooks.beforeEach( function () {
		this.sandbox.stub( mw.user.options, 'get', fakeOptionsGet );
		DateFormatter.clearInstanceCache();
	} );

	function getInstance() {
		return DateFormatter.forUtc();
	}

	QUnit.test( 'formatTimeAndDate', ( assert ) => {
		assert.strictEqual(
			getInstance().formatTimeAndDate( midnightZulu ),
			'00:00, 1 (january) 2025'
		);
	} );

	QUnit.test( 'formatTime', ( assert ) => {
		assert.strictEqual(
			getInstance().formatTime( midnightZulu ),
			'00:00'
		);
	} );

	QUnit.test( 'formatDate', ( assert ) => {
		assert.strictEqual(
			getInstance().formatDate( midnightZulu ),
			'1 (january) 2025'
		);
	} );

	QUnit.test( 'formatPrettyDate', ( assert ) => {
		assert.strictEqual(
			getInstance().formatPrettyDate( midnightZulu ),
			'1 (january)'
		);
	} );

	QUnit.test( 'formatMw', ( assert ) => {
		assert.strictEqual(
			getInstance().formatMw( midnightZulu ),
			'20250101000000'
		);
	} );

	QUnit.test( 'formatIso', ( assert ) => {
		assert.strictEqual(
			getInstance().formatIso( midnightZulu ),
			'2025-01-01T00:00:00+00:00'
		);
	} );

	QUnit.test( 'formatForDateTimeInput', ( assert ) => {
		assert.strictEqual(
			getInstance().formatForDateTimeInput( midnightZulu ),
			'2025-01-01T00:00'
		);
	} );

	QUnit.test( 'formatTimeAndDateRange', ( assert ) => {
		assert.strictEqual(
			getInstance().formatTimeAndDateRange( midnightZulu, oneZulu ),
			// Just descriptive, we don't supply fixed patterns for ranges
			'January 1, 2025, 00:00 – 01:00'
		);
	} );

	QUnit.test( 'formatTimeRange', ( assert ) => {
		assert.strictEqual(
			getInstance().formatTimeRange( midnightZulu, oneZulu ),
			// Just descriptive
			'00:00 – 01:00'
		);
	} );

	QUnit.test( 'formatDateRange', ( assert ) => {
		assert.strictEqual(
			getInstance().formatDateRange( midnightZulu, nextDay ),
			// Just descriptive
			'January 1 – 2, 2025'
		);
	} );

} );
