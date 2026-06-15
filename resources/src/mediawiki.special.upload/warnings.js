/**
 * Helpers for rendering structured warnings from action=upload on Special:Upload.
 *
 * @private
 * @module warnings
 */

const NS_FILE = mw.config.get( 'wgNamespaceIds' ).file;

/**
 * Build a namespaced file title.
 *
 * @private
 * @param {string} name
 * @return {string}
 */
function fileTitle( name ) {
	return mw.Title.makeTitle( NS_FILE, name ).getPrefixedText();
}

/**
 * Map known warning keys to their equivalent messages.
 *
 * The 'filetype-unwanted-type' and 'large-file' warnings are intentionally not
 * handled here as the form disallows submitting files that would trigger
 * these warnings.
 *
 * @private
 * @param {string} key Warning key emitted by action=upload
 * @param {*} value Additional data received alongside the warning
 * @param {string} destFile Unprefixed destination filename
 * @return {jQuery|undefined} Message object
 */
function warningMessage( key, value, destFile ) {
	const destTitle = fileTitle( destFile );

	switch ( key ) {
		case 'exists':
			return mw.message( 'uploadwarning-exists', fileTitle( value ) ).parseDom();
		case 'page-exists':
			return mw.message( 'uploadwarning-page-exists', fileTitle( value ) ).parseDom();
		case 'exists-normalized':
			return mw.message( 'uploadwarning-exists-normalized', destTitle, fileTitle( value ) ).parseDom();
		case 'thumb':
			return mw.message( 'uploadwarning-thumbnail-yes', fileTitle( value ) ).parseDom();
		case 'thumb-name':
			return mw.message( 'file-thumbnail-no', value ).parseDom();
		case 'bad-prefix':
			return mw.message( 'filename-bad-prefix', value ).parseDom();
		case 'badfilename':
			return mw.message( 'badfilename', value ).parseDom();
		case 'empty-file':
			return mw.message( 'empty-file' ).parseDom();
		case 'duplicateversions':
			return mw.message( 'fileexists-duplicate-version', destTitle, value.length ).parseDom();
		case 'duplicate-archive':
			return value === '' || value === true ?
				mw.message( 'file-deleted-duplicate-notitle' ).parseDom() :
				mw.message( 'file-deleted-duplicate', fileTitle( value ) ).parseDom();
		case 'nochange':
			return mw.message( 'fileexists-no-change', destTitle ).parseDom();
		case 'was-deleted': {
			const url = mw.util.getUrl( 'Special:Log', {
				type: 'delete',
				page: fileTitle( value )
			} );
			const $link = $( '<a>' ).attr( 'href', url ).text( mw.msg( 'deletionlog' ) );
			return mw.message( 'filewasdeleted', $link ).parseDom();
		}
	}
}

/**
 * Render a single warning list item.
 *
 * @private
 * @param {HTMLLIElement} li List item to append to
 * @param {string} key Warning key emitted by action=upload
 * @param {*} value Additional data received alongside the warning
 * @param {string} destFile Unprefixed destination filename
 */
function renderWarning( li, key, value, destFile ) {
	const $li = $( li );

	// The 'duplicate' message is a bit different than the others as it
	// renders an extra <ul> of duplicate file titles after the
	// message, so it's handled here instead of in warningMessage.
	if ( key === 'duplicate' ) {
		$li.append( mw.message( 'file-exists-duplicate', value.length ).parseDom() );

		const $sublist = $( '<ul>' );
		for ( const name of value ) {
			const title = mw.Title.makeTitle( NS_FILE, name );
			const $link = $( '<a>' ).attr( 'href', title.getUrl() ).text( title.getPrefixedText() );
			$sublist.append( $( '<li>' ).append( $link ) );
		}
		$li.append( $sublist );

		return;
	}

	const $message = warningMessage( key, value, destFile );
	if ( $message ) {
		$li.append( $message );
	} else {
		$li.append( mw.message( 'api-error-unknown-warning', JSON.stringify( { [ key ]: value } ) ).parseDom() );
	}
}

/**
 * Render warning messages in an unordered list.
 *
 * @memberof module:warnings
 * @param {Object} warnings Structured warnings from action=upload
 * @param {string} destFile Unprefixed destination filename
 * @return {HTMLUListElement}
 */
function renderWarnings( warnings, destFile ) {
	const list = document.createElement( 'ul' );
	for ( const key in warnings ) {
		const li = document.createElement( 'li' );
		renderWarning( li, key, warnings[ key ], destFile );
		list.appendChild( li );
	}
	return list;
}

module.exports = { renderWarnings };
