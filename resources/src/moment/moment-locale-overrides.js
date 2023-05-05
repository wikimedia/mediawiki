/* global moment */

( function () {
	// Define a locale if the current language doesn't have one, so that we can apply overrides
	// without affecting the defaults for English (T313188)
	if ( moment.locale() === 'en' && mw.config.get( 'wgUserLanguage' ) !== 'en' ) {
		moment.defineLocale( mw.config.get( 'wgUserLanguage' ), {
			parentLocale: 'en'
		} );
	}

	// HACK: Overwrite moment's i18n with MediaWiki's for the current language so that
	// wgTranslateNumerals is respected.
	moment.updateLocale( moment.locale(), {
		preparse: function ( s ) {
			var table = mw.language.getDigitTransformTable();
			if ( mw.config.get( 'wgTranslateNumerals' ) ) {
				for ( var i = 0; i < 10; i++ ) {
					if ( table[ i ] !== undefined ) {
						// eslint-disable-next-line security/detect-non-literal-regexp
						s = s.replace( new RegExp( mw.util.escapeRegExp( table[ i ] ), 'g' ), i );
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
			var table = mw.language.getDigitTransformTable();
			if ( mw.config.get( 'wgTranslateNumerals' ) ) {
				for ( var i = 0; i < 10; i++ ) {
					if ( table[ i ] !== undefined ) {
						// eslint-disable-next-line security/detect-non-literal-regexp
						s = s.replace( new RegExp( i, 'g' ), table[ i ] );
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
}() );
