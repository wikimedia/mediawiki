'use strict';
let resultPage = null,
	updatingBooklet = false,
	baseRequestParams = {};

const api = new mw.Api(),
	bookletPages = [];

let booklet, panel, oldhash;
/**
 * Interface to ApiSandbox UI.
 *
 * @class mw.special.ApiSandbox
 * @ignore
 */
const ApiSandbox = {
	suppressErrors: true,
	windowManager: null,
	formatDropdown: null,
	availableFormats: {},
	pages: {},

	/**
	 * Initialize the UI
	 *
	 * Automatically called on $.ready()
	 */
	init: function () {
		ApiSandbox.windowManager = new OO.ui.WindowManager();
		$( OO.ui.getTeleportTarget() ).append( ApiSandbox.windowManager.$element );
		ApiSandbox.windowManager.addWindows( {
			errorAlert: new OO.ui.MessageDialog()
		} );

		const $toolbar = $( '<div>' )
			.addClass( 'mw-apisandbox-toolbar' )
			.append(
				new OO.ui.ButtonWidget( {
					label: mw.msg( 'apisandbox-submit' ),
					flags: [ 'primary', 'progressive' ]
				} ).on( 'click', ApiSandbox.sendRequest ).$element,
				new OO.ui.ButtonWidget( {
					label: mw.msg( 'apisandbox-reset' ),
					flags: 'destructive'
				} ).on( 'click', ApiSandbox.resetUI ).$element
			);

		booklet = new OO.ui.BookletLayout( {
			expanded: false,
			outlined: true,
			autoFocus: false
		} );

		panel = new OO.ui.PanelLayout( {
			classes: [ 'mw-apisandbox-container' ],
			content: [ booklet ],
			expanded: false
		} );

		const ApiSandboxLayout = require( './ApiSandboxLayout.js' );
		ApiSandbox.pages.main = new ApiSandboxLayout( { key: 'main', path: 'main' } );

		// Parse the current hash string
		if ( !ApiSandbox.loadFromHash() ) {
			ApiSandbox.updateUI();
		}

		$( window ).on( 'hashchange', ApiSandbox.loadFromHash );

		const Util = require( './Util.js' );
		$( '#mw-apisandbox' )
			.empty()
			.append( $( '<p>' ).append( Util.parseMsg( 'apisandbox-intro' ) ) )
			.append(
				$( '<div>' ).attr( 'id', 'mw-apisandbox-ui' )
					.append( $toolbar )
					.append( panel.$element )
			);
	},

	/**
	 * Update the current query when the page hash changes
	 *
	 * @return {boolean} Successful
	 */
	loadFromHash: function () {
		let fragment = location.hash;

		if ( oldhash === fragment ) {
			return false;
		}
		oldhash = fragment;
		if ( fragment === '' ) {
			return false;
		}

		// I'm surprised this doesn't seem to exist in jQuery or mw.util.
		const params = {};
		fragment = fragment.replace( /\+/g, '%20' );
		const pattern = /([^&=#]+)=?([^&#]*)/g;
		let match;
		while ( ( match = pattern.exec( fragment ) ) ) {
			params[ decodeURIComponent( match[ 1 ] ) ] = decodeURIComponent( match[ 2 ] );
		}

		ApiSandbox.updateUI( params );
		return true;
	},

	/**
	 * Update the pages in the booklet
	 *
	 * @param {Object} [params] Optional query parameters to load
	 */
	updateUI: function ( params ) {
		const addPages = [];

		if ( !$.isPlainObject( params ) ) {
			params = undefined;
		}

		if ( updatingBooklet ) {
			return;
		}

		const ApiSandboxLayout = require( './ApiSandboxLayout.js' );
		updatingBooklet = true;
		try {
			if ( params !== undefined ) {
				ApiSandbox.pages.main.loadQueryParams( params );
			}
			addPages.push( ApiSandbox.pages.main );
			if ( resultPage !== null ) {
				addPages.push( resultPage );
			}
			ApiSandbox.pages.main.apiCheckValid();

			let i = 0;
			while ( addPages.length ) {
				const page = addPages.shift();
				if ( bookletPages[ i ] !== page ) {
					for ( let j = i; j < bookletPages.length; j++ ) {
						if ( bookletPages[ j ].getName() === page.getName() ) {
							bookletPages.splice( j, 1 );
						}
					}
					bookletPages.splice( i, 0, page );
					booklet.addPages( [ page ], i );
				}
				i++;

				if ( page.getSubpages ) {
					const subpages = page.getSubpages();
					subpages.forEach( ( subpage, k ) => {
						if ( !Object.prototype.hasOwnProperty.call( ApiSandbox.pages, subpage.key ) ) {
							subpage.indentLevel = page.indentLevel + 1;
							ApiSandbox.pages[ subpage.key ] = new ApiSandboxLayout( subpage );
						}
						if ( params !== undefined ) {
							ApiSandbox.pages[ subpage.key ].loadQueryParams( params );
						}
						addPages.splice( k, 0, ApiSandbox.pages[ subpage.key ] );
						ApiSandbox.pages[ subpage.key ].apiCheckValid();
					} );
				}
			}

			if ( bookletPages.length > i ) {
				const removePages = bookletPages.splice( i, bookletPages.length - i );
				booklet.removePages( removePages );
			}

			if ( !booklet.getCurrentPageName() ) {
				booklet.selectFirstSelectablePage();
			}
		} finally {
			updatingBooklet = false;
		}
	},

	/**
	 * Reset button handler
	 */
	resetUI: function () {
		const ApiSandboxLayout = require( './ApiSandboxLayout.js' );
		ApiSandbox.suppressErrors = true;
		ApiSandbox.pages = {
			main: new ApiSandboxLayout( { key: 'main', path: 'main' } )
		};
		resultPage = null;
		ApiSandbox.updateUI();
	},

	/**
	 * Submit button handler
	 *
	 * @param {Object} [params] Use this set of params instead of those in the form fields.
	 *   The form fields will be updated to match.
	 */
	sendRequest: function ( params ) {
		let method = 'get';
		const paramsAreForced = !!params,
			deferreds = [],
			displayParams = {},
			ajaxOptions = {},
			tokenWidgets = [],
			checkPages = [ ApiSandbox.pages.main ];

		// Blur any focused widget before submit, because
		// OO.ui.ButtonWidget doesn't take focus itself (T128054)
		const $focus = $( '#mw-apisandbox-ui' ).find( document.activeElement );
		if ( $focus.length ) {
			$focus[ 0 ].blur();
		}

		ApiSandbox.suppressErrors = false;

		// save widget state in params (or load from it if we are forced)
		if ( paramsAreForced ) {
			ApiSandbox.updateUI( params );
		}
		params = {};
		while ( checkPages.length ) {
			const checkPage = checkPages.shift();
			if ( checkPage.tokenWidget ) {
				tokenWidgets.push( checkPage.tokenWidget );
			}
			deferreds.push( ...checkPage.apiCheckValid() );
			checkPage.getQueryParams( params, displayParams, ajaxOptions );
			if ( checkPage.paramInfo.mustbeposted !== undefined ) {
				method = 'post';
			}
			const subpages = checkPage.getSubpages();
			subpages.forEach( ( subpage ) => {
				if ( Object.prototype.hasOwnProperty.call( ApiSandbox.pages, subpage.key ) ) {
					checkPages.push( ApiSandbox.pages[ subpage.key ] );
				}
			} );
		}

		if ( !paramsAreForced ) {
			// forced params means we are continuing a query; the base query should be preserved
			baseRequestParams = Object.assign( {}, params );
		}

		const Util = require( './Util.js' );

		$.when( ...deferreds ).done( ( ...args ) => {
			// Count how many times `value` occurs in `array`.
			const countValues = ( value, array ) => {
				let count = 0;
				for ( let n = 0; n < array.length; n++ ) {
					if ( array[ n ] === value ) {
						count++;
					}
				}
				return count;
			};

			const errorCount = countValues( false, args );
			if ( errorCount > 0 ) {
				const actions = [
					{
						action: 'accept',
						label: OO.ui.msg( 'ooui-dialog-process-dismiss' ),
						flags: 'primary'
					}
				];
				let deferred;
				if ( tokenWidgets.length ) {
					// Check all token widgets' validity separately
					deferred = $.when( ...tokenWidgets.map( ( w ) => w.apiCheckValid( ApiSandbox.suppressErrors ) ) );

					deferred.done( ( ...args2 ) => {
						// If only the tokens are invalid, offer to fix them
						const tokenErrorCount = countValues( false, args2 );
						if ( tokenErrorCount === errorCount ) {
							delete actions[ 0 ].flags;
							actions.push( {
								action: 'fix',
								label: mw.msg( 'apisandbox-results-fixtoken' ),
								flags: 'primary'
							} );
						}
					} );
				} else {
					deferred = $.Deferred().resolve();
				}
				deferred.always( () => {
					ApiSandbox.windowManager.openWindow( 'errorAlert', {
						title: Util.parseMsg( 'apisandbox-submit-invalid-fields-title' ),
						message: Util.parseMsg( 'apisandbox-submit-invalid-fields-message' ),
						actions: actions
					} ).closed.then( ( data ) => {
						if ( data && data.action === 'fix' ) {
							ApiSandbox.fixTokenAndResend();
						}
					} );
				} );
				return;
			}

			if ( params.format === undefined ) {
				// While not required by the API, the sandbox UI makes the 'format' parameter required.
				// If we reach this point without any value for it, that's a bug, so stop here
				// (it would result in incorrect formatting on the results panel).
				throw new Error( "'format' parameter is required" );
			}
			if ( params.action === undefined ) {
				// While not required by the API, the sandbox UI makes the 'action' parameter required.
				// If we reach this point without any value for it, that's a bug, so stop here
				// (it would result in dumping the entire HTML help output on the results panel).
				throw new Error( "'action' parameter is required" );
			}

			const query = $.param( displayParams );

			const formatItems = Util.formatRequest( displayParams, params, method, ajaxOptions );

			// Force a 'fm' format with wrappedhtml=1, if available
			if ( params.format !== undefined ) {
				if ( Object.prototype.hasOwnProperty.call( ApiSandbox.availableFormats, params.format + 'fm' ) ) {
					params.format = params.format + 'fm';
				}
				if ( params.format.slice( -2 ) === 'fm' ) {
					params.wrappedhtml = 1;
				}
			}

			let progressLoading = false;
			const $progressText = $( '<span>' ).text( mw.msg( 'apisandbox-sending-request' ) );
			const progress = new OO.ui.ProgressBarWidget( {
				progress: false
			} );

			const $result = $( '<div>' )
				.append( $progressText, progress.$element );

			const page = resultPage = new OO.ui.PageLayout( '|results|', { expanded: false } );
			page.setupOutlineItem = function () {
				this.outlineItem.setLabel( mw.msg( 'apisandbox-results' ) );
			};

			if ( !ApiSandbox.formatDropdown ) {
				ApiSandbox.formatDropdown = new OO.ui.DropdownWidget( {
					menu: { items: [] },
					$overlay: true
				} );
				ApiSandbox.formatDropdown.getMenu().on( 'select', Util.onFormatDropdownChange );
			}

			const menu = ApiSandbox.formatDropdown.getMenu();
			let selectedLabel = menu.findSelectedItem() ? menu.findSelectedItem().getLabel() : '';
			if ( typeof selectedLabel !== 'string' ) {
				selectedLabel = selectedLabel.text();
			}
			menu.clearItems().addItems( formatItems );
			menu.chooseItem( menu.getItemFromLabel( selectedLabel ) || menu.findFirstSelectableItem() );

			// Fire the event to update field visibilities
			Util.onFormatDropdownChange();

			page.$element.empty()
				.append(
					new OO.ui.FieldLayout(
						ApiSandbox.formatDropdown, {
							label: Util.parseMsg( 'apisandbox-request-selectformat-label' )
						}
					).$element,
					formatItems.map( ( item ) => item.getData().$element )
				);

			if ( method === 'post' ) {
				page.$element.append( new OO.ui.LabelWidget( {
					label: mw.message( 'apisandbox-request-post' ).parseDom(),
					classes: [ 'oo-ui-inline-help' ]
				} ).$element );
			}
			if ( ajaxOptions.contentType === 'multipart/form-data' ) {
				page.$element.append( new OO.ui.LabelWidget( {
					label: mw.message( 'apisandbox-request-formdata' ).parseDom(),
					classes: [ 'oo-ui-inline-help' ]
				} ).$element );
			}

			page.$element.append( $result );

			ApiSandbox.updateUI();
			booklet.setPage( '|results|' );

			const selected = menu.findFirstSelectedItem();
			if ( selected ) {
				const textInput = selected.getData().textInput;
				if ( textInput instanceof OO.ui.MultilineTextInputWidget ) {
					textInput.updatePosition();
				}
			}

			location.href = oldhash = '#' + query;

			api[ method ]( params, Object.assign( ajaxOptions, {
				dataType: 'text',
				xhr: function () {
					const xhr = new window.XMLHttpRequest();
					xhr.upload.addEventListener( 'progress', ( e ) => {
						if ( !progressLoading ) {
							if ( e.lengthComputable ) {
								progress.setProgress( e.loaded * 100 / e.total );
							} else {
								progress.setProgress( false );
							}
						}
					} );
					xhr.addEventListener( 'progress', ( e ) => {
						if ( !progressLoading ) {
							progressLoading = true;
							$progressText.text( mw.msg( 'apisandbox-loading-results' ) );
						}
						if ( e.lengthComputable ) {
							progress.setProgress( e.loaded * 100 / e.total );
						} else {
							progress.setProgress( false );
						}
					} );
					return xhr;
				}
			} ) )
				.catch( function ( code, data, result, jqXHR ) {
					const d = $.Deferred();

					if ( code !== 'http' ) {
						// Not really an error, work around mw.Api thinking it is.
						d.resolve( result, jqXHR );
					} else {
						// Just forward it.
						d.reject.apply( d, arguments );
					}
					return d.promise();
				} )
				.then( ( data, jqXHR ) => {
					const ct = jqXHR.getResponseHeader( 'Content-Type' ),
						loginSuppressed = jqXHR.getResponseHeader( 'MediaWiki-Login-Suppressed' ) || 'false';

					$result.empty();
					if ( loginSuppressed !== 'false' ) {
						$( '<div>' )
							.addClass( 'warning' )
							.append( Util.parseMsg( 'apisandbox-results-login-suppressed' ) )
							.appendTo( $result );
					}
					let loadTime;
					if ( /^text\/mediawiki-api-prettyprint-wrapped(?:;|$)/.test( ct ) ) {
						try {
							data = JSON.parse( data );
						} catch ( e ) {
							// API response is not JSON but e.g. an Xdebug error, show as HTML
							data = { modules: {}, html: data };
						}
						if ( data.modules.length ) {
							mw.loader.load( data.modules );
						}
						if ( data.status && data.status !== 200 ) {
							$( '<div>' )
								.addClass( 'api-pretty-header api-pretty-status' )
								.append( Util.parseMsg( 'api-format-prettyprint-status', data.status, data.statustext ) )
								.appendTo( $result );
						}
						$result.append( Util.parseHTML( data.html ) );
						loadTime = data.time;
					} else {
						$( '<pre>' )
							.addClass( 'api-pretty-content' )
							.text( data )
							.appendTo( $result );
					}
					if ( paramsAreForced || data.continue ) {
						let clear;
						$result.append(
							$( '<div>' ).append(
								new OO.ui.ButtonWidget( {
									label: mw.msg( 'apisandbox-continue' )
								} ).on( 'click', () => {
									ApiSandbox.sendRequest( Object.assign( {}, baseRequestParams, data.continue ) );
								} ).setDisabled( !data.continue ).$element,
								( clear = new OO.ui.ButtonWidget( {
									label: mw.msg( 'apisandbox-continue-clear' )
								} ).on( 'click', () => {
									ApiSandbox.updateUI( baseRequestParams );
									clear.setDisabled( true );
									booklet.setPage( '|results|' );
								} ).setDisabled( !paramsAreForced ) ).$element,
								new OO.ui.PopupButtonWidget( {
									$overlay: true,
									framed: false,
									icon: 'info',
									popup: {
										$content: $( '<div>' ).append( Util.parseMsg( 'apisandbox-continue-help' ) ),
										padded: true,
										width: 'auto'
									}
								} ).$element
							)
						);
					}
					if ( typeof loadTime === 'number' ) {
						$result.append(
							$( '<div>' ).append(
								new OO.ui.LabelWidget( {
									label: mw.msg( 'apisandbox-request-time', loadTime )
								} ).$element
							)
						);
					}

					if ( jqXHR.getResponseHeader( 'MediaWiki-API-Error' ) === 'badtoken' ) {
						// Flush all saved tokens in case one of them is the bad one.
						Util.markTokensBad();
						const button = new OO.ui.ButtonWidget( {
							label: mw.msg( 'apisandbox-results-fixtoken' )
						} );
						button.on( 'click', () => {
							ApiSandbox.fixTokenAndResend();
							button.setDisabled( true );
						} )
							.$element.appendTo( $result );
					}
				}, ( code, data ) => {
					const details = 'HTTP error: ' + data.exception;
					$result.empty()
						.append(
							new OO.ui.LabelWidget( {
								label: mw.msg( 'apisandbox-results-error', details ),
								classes: [ 'error' ]
							} ).$element
						);
				} );
		} );
	},

	/**
	 * Handler for the "Correct token and resubmit" button
	 *
	 * Used on a 'badtoken' error, it re-fetches token parameters for all
	 * pages and then re-submits the query.
	 */
	fixTokenAndResend: function () {
		let ok = true;
		const tokenWait = { dummy: true },
			checkPages = [ ApiSandbox.pages.main ],
			success = function ( k ) {
				delete tokenWait[ k ];
				if ( ok && $.isEmptyObject( tokenWait ) ) {
					ApiSandbox.sendRequest();
				}
			},
			failure = function ( k ) {
				delete tokenWait[ k ];
				ok = false;
			};

		while ( checkPages.length ) {
			const page = checkPages.shift();

			if ( page.tokenWidget ) {
				const key = page.apiModule + page.tokenWidget.paramInfo.name;
				tokenWait[ key ] = page.tokenWidget.fetchToken();
				tokenWait[ key ]
					.done( success.bind( page.tokenWidget, key ) )
					.fail( failure.bind( page.tokenWidget, key ) );
			}

			const subpages = page.getSubpages();
			subpages.forEach( ( subpage ) => {
				if ( Object.prototype.hasOwnProperty.call( ApiSandbox.pages, subpage.key ) ) {
					checkPages.push( ApiSandbox.pages[ subpage.key ] );
				}
			} );
		}

		success( 'dummy', '' );
	},

	/**
	 * Reset validity indicators for all widgets
	 */
	updateValidityIndicators: function () {
		const checkPages = [ ApiSandbox.pages.main ];

		while ( checkPages.length ) {
			const page = checkPages.shift();
			page.apiCheckValid();
			const subpages = page.getSubpages();
			subpages.forEach( ( subpage ) => {
				if ( Object.prototype.hasOwnProperty.call( ApiSandbox.pages, subpage.key ) ) {
					checkPages.push( ApiSandbox.pages[ subpage.key ] );
				}
			} );
		}
	}
};

module.exports = ApiSandbox;
