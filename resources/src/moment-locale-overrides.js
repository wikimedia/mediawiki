// Use DMY date format for Moment.js, in accordance with MediaWiki's date formatting routines.
// This affects English only (and languages without localisations, that fall back to English).
// http://momentjs.com/docs/#/customization/long-date-formats/
/*global moment, mw */
moment.locale( 'en', {
	longDateFormat: {
		// Unchanged, but have to be repeated here:
		LT: 'h:mm A',
		LTS: 'h:mm:ss A',
		// Customized:
		L: 'DD/MM/YYYY',
		LL: 'D MMMM YYYY',
		LLL: 'D MMMM YYYY LT',
		LLLL: 'dddd, D MMMM YYYY LT'
	}
} );

// HACK: Overwrite moment's i18n with MediaWiki's for the current language so that
// wgTranslateNumerals is respected.
moment.locale( moment.locale(), {
	preparse: function ( s ) {
		var i,
			table = mw.language.getDigitTransformTable();
		if ( mw.config.get( 'wgTranslateNumerals' ) ) {
			for ( i = 0; i < 10; i++ ) {
				if ( table[ i ] !== undefined ) {
					s = s.replace( new RegExp( mw.RegExp.escape( table[ i ] ), 'g' ), i );
				}
			}
		}
		// HACK: momentjs replaces commas in some languages, which is the only other use of preparse
		// aside from digit transformation. We can only override preparse, not extend it, so we
		// have to replicate the comma replacement functionality here.
		if ( [ 'ar', 'ar-sa', 'fa' ].indexOf( mw.config.get( 'wgUserLanguage' ) ) !== -1 ) {
			s = s.replace( /،/g, ',' );
		}
		return s;
	},
	postformat: function ( s ) {
		var i,
			table = mw.language.getDigitTransformTable();
		if ( mw.config.get( 'wgTranslateNumerals' ) ) {
			for ( i = 0; i < 10; i++ ) {
				if ( table[ i ] !== undefined ) {
					s = s.replace( new RegExp( mw.RegExp.escape( i ), 'g' ), table[ i ] );
				}
			}
		}
		// HACK: momentjs replaces commas in some languages, which is the only other use of postformat
		// aside from digit transformation. We can only override postformat, not extend it, so we
		// have to replicate the comma replacement functionality here.
		if ( [ 'ar', 'ar-sa', 'fa' ].indexOf( mw.config.get( 'wgUserLanguage' ) ) !== -1 ) {
			s = s.replace( /,/g, '،' );
		}
		return s;
	}
} );
