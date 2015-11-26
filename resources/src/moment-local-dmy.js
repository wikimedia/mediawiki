// Use DMY date format for Moment.js, in accordance with MediaWiki's date formatting routines.
// This affects English only (and languages without localisations, that fall back to English).
// http://momentjs.com/docs/#/customization/long-date-formats/
/*global moment */
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
