/*global OO */
( function ( $, mw, OO ) {
	'use strict';
	var ApiSandbox, Util, WidgetMethods, Validators,
		$content, panel, booklet, oldhash, windowManager, fullscreenButton,
		api = new mw.Api(),
		bookletPages = [],
		availableFormats = {},
		resultPage = null,
		suppressErrors = true,
		updatingBooklet = false,
		pages = {},
		moduleInfoCache = {};

	WidgetMethods = {
		textInputWidget: {
			getApiValue: function () {
				return this.getValue();
			},
			setApiValue: function ( v ) {
				if ( v === undefined ) {
					v = this.paramInfo[ 'default' ];
				}
				this.setValue( v );
			},
			apiCheckValid: function () {
				var that = this;
				return this.isValid().done( function ( ok ) {
					ok = ok || suppressErrors;
					that.setIcon( ok ? null : 'alert' );
					that.setIconTitle( ok ? '' : mw.message( 'apisandbox-alert-field' ).plain() );
				} );
			}
		},

		dateTimeInputWidget: {
			isValid: function () {
				var ok = !Util.apiBool( this.paramInfo.required ) || this.getApiValue() !== '';
				return $.Deferred().resolve( ok ).promise();
			}
		},

		tokenWidget: {
			alertTokenError: function ( code, error ) {
				windowManager.openWindow( 'errorAlert', {
					title: mw.message(
						'apisandbox-results-fixtoken-fail', this.paramInfo.tokentype
					).parse(),
					message: error,
					actions: [
						{
							action: 'accept',
							label: OO.ui.msg( 'ooui-dialog-process-dismiss' ),
							flags: 'primary'
						}
					]
				} );
			},
			fetchToken: function () {
				this.pushPending();
				return api.getToken( this.paramInfo.tokentype )
					.done( this.setApiValue.bind( this ) )
					.fail( this.alertTokenError.bind( this ) )
					.always( this.popPending.bind( this ) );
			},
			setApiValue: function ( v ) {
				WidgetMethods.textInputWidget.setApiValue.call( this, v );
				if ( v === '123ABC' ) {
					this.fetchToken();
				}
			}
		},

		passwordWidget: {
			getApiValueForDisplay: function () {
				return '';
			}
		},

		toggleSwitchWidget: {
			getApiValue: function () {
				return this.getValue() ? 1 : undefined;
			},
			setApiValue: function ( v ) {
				this.setValue( Util.apiBool( v ) );
			},
			apiCheckValid: function () {
				return $.Deferred().resolve( true ).promise();
			}
		},

		dropdownWidget: {
			getApiValue: function () {
				var item = this.getMenu().getSelectedItem();
				return item === null ? undefined : item.getData();
			},
			setApiValue: function ( v ) {
				var menu = this.getMenu();

				if ( v === undefined ) {
					v = this.paramInfo[ 'default' ];
				}
				if ( v === undefined ) {
					menu.selectItem();
				} else {
					menu.selectItemByData( String( v ) );
				}
			},
			apiCheckValid: function () {
				var ok = this.getApiValue() !== undefined || suppressErrors;
				this.setIcon( ok ? null : 'alert' );
				this.setIconTitle( ok ? '' : mw.message( 'apisandbox-alert-field' ).plain() );
				return $.Deferred().resolve( ok ).promise();
			}
		},

		capsuleWidget: {
			getApiValue: function () {
				return this.getItemsData().join( '|' );
			},
			setApiValue: function ( v ) {
				this.setItemsFromData( v === undefined || v === '' ? [] : String( v ).split( '|' ) );
			},
			apiCheckValid: function () {
				var ok = this.getApiValue() !== undefined || suppressErrors;
				this.setIcon( ok ? null : 'alert' );
				this.setIconTitle( ok ? '' : mw.message( 'apisandbox-alert-field' ).plain() );
				return $.Deferred().resolve( ok ).promise();
			}
		},

		optionalWidget: {
			getApiValue: function () {
				return this.isDisabled() ? undefined : this.widget.getApiValue();
			},
			setApiValue: function ( v ) {
				this.setDisabled( v === undefined );
				this.widget.setApiValue( v );
			},
			apiCheckValid: function () {
				if ( this.isDisabled() ) {
					return $.Deferred().resolve( true ).promise();
				} else {
					return this.widget.apiCheckValid();
				}
			}
		},

		submoduleWidget: {
			single: function () {
				var v = this.isDisabled() ? this.paramInfo[ 'default' ] : this.getApiValue();
				return v === undefined ? [] : [ { value: v, path: this.paramInfo.submodules[ v ] } ];
			},
			multi: function () {
				var map = this.paramInfo.submodules,
					v = this.isDisabled() ? this.paramInfo[ 'default' ] : this.getApiValue();
				return v === undefined || v === '' ? [] : $.map( String( v ).split( '|' ), function ( v ) {
					return { value: v, path: map[ v ] };
				} );
			}
		},

		uploadWidget: {
			getApiValueForDisplay: function () {
				return '...';
			},
			getApiValue: function () {
				return this.getValue();
			},
			setApiValue: function () {
				// Can't, sorry.
			},
			apiCheckValid: function () {
				var ok = this.getValue() !== null || suppressErrors;
				this.setIcon( ok ? null : 'alert' );
				this.setIconTitle( ok ? '' : mw.message( 'apisandbox-alert-field' ).plain() );
				return $.Deferred().resolve( ok ).promise();
			}
		}
	};

	Validators = {
		generic: function () {
			return !Util.apiBool( this.paramInfo.required ) || this.getApiValue() !== '';
		}
	};

	/**
	 * @class mw.special.ApiSandbox.Utils
	 * @private
	 */
	Util = {
		/**
		 * Fetch API module info
		 *
		 * @param {string} module Module to fetch data for
		 * @return {jQuery.Promise}
		 */
		fetchModuleInfo: function ( module ) {
			var apiPromise,
				deferred = $.Deferred();

			if ( moduleInfoCache.hasOwnProperty( module ) ) {
				return deferred
					.resolve( moduleInfoCache[ module ] )
					.promise( { abort: function () {} } );
			} else {
				apiPromise = api.post( {
					action: 'paraminfo',
					modules: module,
					helpformat: 'html',
					uselang: mw.config.get( 'wgUserLanguage' )
				} ).done( function ( data ) {
					var info;

					if ( data.warnings && data.warnings.paraminfo ) {
						deferred.reject( '???', data.warnings.paraminfo[ '*' ] );
						return;
					}

					info = data.paraminfo.modules;
					if ( !info || info.length !== 1 || info[ 0 ].path !== module ) {
						deferred.reject( '???', 'No module data returned' );
						return;
					}

					moduleInfoCache[ module ] = info[ 0 ];
					deferred.resolve( info[ 0 ] );
				} ).fail( function ( code, details ) {
					if ( code === 'http' ) {
						details = 'HTTP error: ' + details.exception;
					} else if ( details.error ) {
						details = details.error.info;
					}
					deferred.reject( code, details );
				} );
				return deferred
					.promise( { abort: apiPromise.abort } );
			}
		},

		/**
		 * Mark all currently-in-use tokens as bad
		 */
		markTokensBad: function () {
			var page, subpages, i,
				checkPages = [ pages.main ];

			while ( checkPages.length ) {
				page = checkPages.shift();

				if ( page.tokenWidget ) {
					api.badToken( page.tokenWidget.paramInfo.tokentype );
				}

				subpages = page.getSubpages();
				for ( i = 0; i < subpages.length; i++ ) {
					if ( pages.hasOwnProperty( subpages[ i ].key ) ) {
						checkPages.push( pages[ subpages[ i ].key ] );
					}
				}
			}
		},

		/**
		 * Test an API boolean
		 *
		 * @param {Mixed} value
		 * @return {boolean}
		 */
		apiBool: function ( value ) {
			return value !== undefined && value !== false;
		},

		/**
		 * Create a widget for a parameter.
		 *
		 * @param {Object} pi Parameter info from API
		 * @param {Object} opts Additional options
		 * @return {OO.ui.Widget}
		 */
		createWidgetForParameter: function ( pi, opts ) {
			var widget, innerWidget, finalWidget, items, $button, $content, func,
				multiMode = 'none';

			opts = opts || {};

			switch ( pi.type ) {
				case 'boolean':
					widget = new OO.ui.ToggleSwitchWidget();
					widget.paramInfo = pi;
					$.extend( widget, WidgetMethods.toggleSwitchWidget );
					pi.required = true; // Avoid wrapping in the non-required widget
					break;

				case 'string':
				case 'user':
					if ( pi.tokentype ) {
						widget = new TextInputWithIndicatorWidget( {
							input: {
								indicator: 'previous',
								indicatorTitle: mw.message( 'apisandbox-fetch-token' ).text(),
								required: Util.apiBool( pi.required )
							}
						} );
					} else if ( Util.apiBool( pi.multi ) ) {
						widget = new OO.ui.CapsuleMultiSelectWidget( {
							allowArbitrary: true
						} );
						widget.paramInfo = pi;
						$.extend( widget, WidgetMethods.capsuleWidget );
					} else {
						widget = new OO.ui.TextInputWidget( {
							required: Util.apiBool( pi.required )
						} );
					}
					if ( !Util.apiBool( pi.multi ) ) {
						widget.paramInfo = pi;
						$.extend( widget, WidgetMethods.textInputWidget );
						widget.setValidation( Validators.generic );
					}
					if ( pi.tokentype ) {
						$.extend( widget, WidgetMethods.tokenWidget );
						widget.input.paramInfo = pi;
						$.extend( widget.input, WidgetMethods.textInputWidget );
						$.extend( widget.input, WidgetMethods.tokenWidget );
						widget.on( 'indicator', widget.fetchToken, [], widget );
					}
					break;

				case 'text':
					widget = new OO.ui.TextInputWidget( {
						multiline: true,
						required: Util.apiBool( pi.required )
					} );
					widget.paramInfo = pi;
					$.extend( widget, WidgetMethods.textInputWidget );
					widget.setValidation( Validators.generic );
					break;

				case 'password':
					widget = new OO.ui.TextInputWidget( {
						type: 'password',
						required: Util.apiBool( pi.required )
					} );
					widget.paramInfo = pi;
					$.extend( widget, WidgetMethods.textInputWidget );
					$.extend( widget, WidgetMethods.passwordWidget );
					widget.setValidation( Validators.generic );
					multiMode = 'enter';
					break;

				case 'integer':
					widget = new OO.ui.NumberInputWidget( {
						required: Util.apiBool( pi.required ),
						isInteger: true
					} );
					widget.setIcon = widget.input.setIcon.bind( widget.input );
					widget.setIconTitle = widget.input.setIconTitle.bind( widget.input );
					widget.isValid = widget.input.isValid.bind( widget.input );
					widget.paramInfo = pi;
					$.extend( widget, WidgetMethods.textInputWidget );
					if ( Util.apiBool( pi.enforcerange ) ) {
						widget.setRange( pi.min || -Infinity, pi.max || Infinity );
					}
					multiMode = 'enter';
					break;

				case 'limit':
					widget = new OO.ui.NumberInputWidget( {
						required: Util.apiBool( pi.required ),
						isInteger: true
					} );
					widget.setIcon = widget.input.setIcon.bind( widget.input );
					widget.setIconTitle = widget.input.setIconTitle.bind( widget.input );
					widget.isValid = widget.input.isValid.bind( widget.input );
					widget.input.setValidation( function ( value ) {
						return value === 'max' || widget.validateNumber( value );
					} );
					widget.paramInfo = pi;
					$.extend( widget, WidgetMethods.textInputWidget );
					widget.setRange( pi.min || 0, mw.config.get( 'apihighlimits' ) ? pi.highmax : pi.max );
					multiMode = 'enter';
					break;

				case 'timestamp':
					widget = new mw.widgets.datetime.DateTimeInputWidget( {
						formatter: {
							format: '${year|0}-${month|0}-${day|0}T${hour|0}:${minute|0}:${second|0}${zone|short}'
						},
						required: Util.apiBool( pi.required ),
						clearable: false
					} );
					widget.paramInfo = pi;
					$.extend( widget, WidgetMethods.textInputWidget );
					$.extend( widget, WidgetMethods.dateTimeInputWidget );
					multiMode = 'indicator';
					break;

				case 'upload':
					widget = new OO.ui.SelectFileWidget();
					widget.paramInfo = pi;
					$.extend( widget, WidgetMethods.uploadWidget );
					break;

				case 'namespace':
					items = $.map( mw.config.get( 'wgFormattedNamespaces' ), function ( name, ns ) {
						if ( ns === '0' ) {
							name = mw.message( 'blanknamespace' ).text();
						}
						return new OO.ui.MenuOptionWidget( { data: ns, label: name } );
					} ).sort( function ( a, b ) {
						return a.data - b.data;
					} );
					if ( Util.apiBool( pi.multi ) ) {
						widget = new OO.ui.CapsuleMultiSelectWidget( {
							menu: { items: items }
						} );
						widget.paramInfo = pi;
						$.extend( widget, WidgetMethods.capsuleWidget );
					} else {
						widget = new OO.ui.DropdownWidget( {
							menu: { items: items }
						} );
						widget.paramInfo = pi;
						$.extend( widget, WidgetMethods.dropdownWidget );
					}
					break;

				default:
					if ( !$.isArray( pi.type ) ) {
						throw new Error( 'Unknown parameter type ' + pi.type );
					}

					items = $.map( pi.type, function ( v ) {
						return new OO.ui.MenuOptionWidget( { data: String( v ), label: String( v ) } );
					} );
					if ( Util.apiBool( pi.multi ) ) {
						widget = new OO.ui.CapsuleMultiSelectWidget( {
							menu: { items: items }
						} );
						widget.paramInfo = pi;
						$.extend( widget, WidgetMethods.capsuleWidget );
						if ( Util.apiBool( pi.submodules ) ) {
							widget.getSubmodules = WidgetMethods.submoduleWidget.multi;
							widget.on( 'change', ApiSandbox.updateUI );
						}
					} else {
						widget = new OO.ui.DropdownWidget( {
							menu: { items: items }
						} );
						widget.paramInfo = pi;
						$.extend( widget, WidgetMethods.dropdownWidget );
						if ( Util.apiBool( pi.submodules ) ) {
							widget.getSubmodules = WidgetMethods.submoduleWidget.single;
							widget.getMenu().on( 'choose', ApiSandbox.updateUI );
						}
					}

					break;
			}

			if ( Util.apiBool( pi.multi ) && multiMode !== 'none' ) {
				innerWidget = widget;
				switch ( multiMode ) {
					case 'enter':
						$content = innerWidget.$element;
						break;

					case 'indicator':
						$button = innerWidget.$indicator;
						$button.css( 'cursor', 'pointer' );
						$button.attr( 'tabindex', 0 );
						$button.parent().append( $button );
						innerWidget.setIndicator( 'next' );
						$content = innerWidget.$element;
						break;

					default:
						throw new Error( 'Unknown multiMode "' + multiMode + '"' );
				}

				widget = new OO.ui.CapsuleMultiSelectWidget( {
					allowArbitrary: true,
					popup: {
						classes: [ 'mw-apisandbox-popup' ],
						$content: $content
					}
				} );
				widget.paramInfo = pi;
				$.extend( widget, WidgetMethods.capsuleWidget );

				func = function () {
					if ( !innerWidget.isDisabled() ) {
						innerWidget.apiCheckValid().done( function ( ok ) {
							if ( ok ) {
								widget.addItemsFromData( [ innerWidget.getApiValue() ] );
								innerWidget.setApiValue( undefined );
							}
						} );
						return false;
					}
				};
				switch ( multiMode ) {
					case 'enter':
						innerWidget.connect( null, { enter: func } );
						break;

					case 'indicator':
						$button.on( {
							click: func,
							keypress: function ( e ) {
								if ( e.which === OO.ui.Keys.SPACE || e.which === OO.ui.Keys.ENTER ) {
									func();
								}
							}
						} );
						break;
				}
			}

			if ( Util.apiBool( pi.required ) || opts.nooptional ) {
				finalWidget = widget;
			} else {
				finalWidget = new OptionalWidget( widget );
				finalWidget.paramInfo = pi;
				$.extend( finalWidget, WidgetMethods.optionalWidget );
				if ( widget.getSubmodules ) {
					finalWidget.getSubmodules = widget.getSubmodules.bind( widget );
					finalWidget.on( 'disable', function () { setTimeout( ApiSandbox.updateUI ); } );
				}
				finalWidget.setDisabled( true );
			}

			widget.setApiValue( pi[ 'default' ] );

			return finalWidget;
		},

		/**
		 * Parse an HTML string, adding target="_blank" to any links
		 *
		 * @param {string} html HTML to parse
		 * @return {jQuery}
		 */
		parseHTML: function ( html ) {
			var $ret = $( $.parseHTML( html ) );
			$ret.filter( 'a' ).add( $ret.find( 'a' ) )
				.filter( '[href]:not([target])' )
				.attr( 'target', '_blank' );
			return $ret;
		}
	};

	/**
	* Interface to ApiSandbox UI
	*
	* @class mw.special.ApiSandbox
	*/
	mw.special.ApiSandbox = ApiSandbox = {
		/**
		 * Initialize the UI
		 *
		 * Automatically called on $.ready()
		 */
		init: function () {
			var $toolbar;

			$content = $( '#mw-apisandbox' );

			windowManager = new OO.ui.WindowManager();
			$( 'body' ).append( windowManager.$element );
			windowManager.addWindows( {
				errorAlert: new OO.ui.MessageDialog()
			} );

			fullscreenButton = new OO.ui.ButtonWidget( {
				label: mw.message( 'apisandbox-fullscreen' ).text(),
				title: mw.message( 'apisandbox-fullscreen-tooltip' ).text()
			} ).on( 'click', ApiSandbox.toggleFullscreen );

			$toolbar = $( '<div>' )
				.addClass( 'mw-apisandbox-toolbar' )
				.append(
					fullscreenButton.$element,
					new OO.ui.ButtonWidget( {
						label: mw.message( 'apisandbox-submit' ).text(),
						flags: [ 'primary', 'constructive' ]
					} ).on( 'click', ApiSandbox.sendRequest ).$element,
					new OO.ui.ButtonWidget( {
						label: mw.message( 'apisandbox-reset' ).text(),
						flags: 'destructive'
					} ).on( 'click', ApiSandbox.resetUI ).$element
				);

			booklet = new OO.ui.BookletLayout( {
				outlined: true,
				autoFocus: false
			} );

			panel = new OO.ui.PanelLayout( {
				classes: [ 'mw-apisandbox-container' ],
				content: [ booklet ],
				expanded: false,
				framed: true
			} );

			pages.main = new ApiSandbox.PageLayout( { key: 'main', path: 'main' } );

			// Parse the current hash string
			if ( !ApiSandbox.loadFromHash() ) {
				ApiSandbox.updateUI();
			}

			// If the hashchange event exists, use it. Otherwise, fake it.
			// And, of course, IE has to be dumb.
			if ( 'onhashchange' in window &&
				( document.documentMode === undefined || document.documentMode >= 8 )
			) {
				$( window ).on( 'hashchange', ApiSandbox.loadFromHash );
			} else {
				setInterval( function () {
					if ( oldhash !== location.hash ) {
						ApiSandbox.loadFromHash();
					}
				}, 1000 );
			}

			$content
				.empty()
				.append( $( '<p>' ).append( mw.message( 'apisandbox-intro' ).parse() ) )
				.append(
					$( '<div>', { id: 'mw-apisandbox-ui' } )
						.append( $toolbar )
						.append( panel.$element )
				);

			$( window ).on( 'resize', ApiSandbox.resizePanel );

			ApiSandbox.resizePanel();
		},

		/**
		 * Toggle "fullscreen" mode
		 */
		toggleFullscreen: function () {
			var $body = $( document.body );

			$body.toggleClass( 'mw-apisandbox-fullscreen' );
			if ( $body.hasClass( 'mw-apisandbox-fullscreen' ) ) {
				fullscreenButton.setLabel( mw.message( 'apisandbox-unfullscreen' ).text() );
				fullscreenButton.setTitle( mw.message( 'apisandbox-unfullscreen-tooltip' ).text() );
				$body.append( $( '#mw-apisandbox-ui' ) );
			} else {
				fullscreenButton.setLabel( mw.message( 'apisandbox-fullscreen' ).text() );
				fullscreenButton.setTitle( mw.message( 'apisandbox-fullscreen-tooltip' ).text() );
				$content.append( $( '#mw-apisandbox-ui' ) );
			}
			ApiSandbox.resizePanel();
		},

		/**
		 * Set the height of the panel based on the current viewport.
		 */
		resizePanel: function () {
			var height = $( window ).height(),
				contentTop = $content.offset().top;

			if ( $( document.body ).hasClass( 'mw-apisandbox-fullscreen' ) ) {
				height -= panel.$element.offset().top - $( '#mw-apisandbox-ui' ).offset().top;
				panel.$element.height( height - 1 );
			} else {
				// Subtract the height of the intro text
				height -= panel.$element.offset().top - contentTop;

				panel.$element.height( height - 10 );
				$( window ).scrollTop( contentTop - 5 );
			}
		},

		/**
		 * Update the current query when the page hash changes
		 */
		loadFromHash: function () {
			var params, m, re,
				hash = location.hash;

			if ( oldhash === hash ) {
				return false;
			}
			oldhash = hash;
			if ( hash === '' ) {
				return false;
			}

			// I'm surprised this doesn't seem to exist in jQuery or mw.util.
			params = {};
			hash = hash.replace( /\+/g, '%20' );
			re = /([^&=#]+)=?([^&#]*)/g;
			while ( ( m = re.exec( hash ) ) ) {
				params[ decodeURIComponent( m[ 1 ] ) ] = decodeURIComponent( m[ 2 ] );
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
			var i, page, subpages, j, removePages,
				addPages = [];

			if ( !$.isPlainObject( params ) ) {
				params = undefined;
			}

			if ( updatingBooklet ) {
				return;
			}
			updatingBooklet = true;
			try {
				if ( params !== undefined ) {
					pages.main.loadQueryParams( params );
				}
				addPages.push( pages.main );
				if ( resultPage !== null ) {
					addPages.push( resultPage );
				}
				pages.main.apiCheckValid();

				i = 0;
				while ( addPages.length ) {
					page = addPages.shift();
					if ( bookletPages[ i ] !== page ) {
						for ( j = i; j < bookletPages.length; j++ ) {
							if ( bookletPages[ j ].getName() === page.getName() ) {
								bookletPages.splice( j, 1 );
							}
						}
						bookletPages.splice( i, 0, page );
						booklet.addPages( [ page ], i );
					}
					i++;

					if ( page.getSubpages ) {
						subpages = page.getSubpages();
						for ( j = 0; j < subpages.length; j++ ) {
							if ( !pages.hasOwnProperty( subpages[ j ].key ) ) {
								subpages[ j ].indentLevel = page.indentLevel + 1;
								pages[ subpages[ j ].key ] = new ApiSandbox.PageLayout( subpages[ j ] );
							}
							if ( params !== undefined ) {
								pages[ subpages[ j ].key ].loadQueryParams( params );
							}
							addPages.splice( j, 0, pages[ subpages[ j ].key ] );
							pages[ subpages[ j ].key ].apiCheckValid();
						}
					}
				}

				if ( bookletPages.length > i ) {
					removePages = bookletPages.splice( i, bookletPages.length - i );
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
			suppressErrors = true;
			pages = {
				main: new ApiSandbox.PageLayout( { key: 'main', path: 'main' } )
			};
			resultPage = null;
			ApiSandbox.updateUI();
		},

		/**
		 * Submit button handler
		 */
		sendRequest: function () {
			var page, subpages, i, query, $result, $focus,
				progress, $progressText, progressLoading,
				deferreds = [],
				params = {},
				displayParams = {},
				checkPages = [ pages.main ];

			// Blur any focused widget before submit, because
			// OO.ui.ButtonWidget doesn't take focus itself (T128054)
			$focus = $( '#mw-apisandbox-ui' ).find( document.activeElement );
			if ( $focus.length ) {
				$focus[ 0 ].blur();
			}

			suppressErrors = false;

			while ( checkPages.length ) {
				page = checkPages.shift();
				deferreds.push( page.apiCheckValid() );
				page.getQueryParams( params, displayParams );
				subpages = page.getSubpages();
				for ( i = 0; i < subpages.length; i++ ) {
					if ( pages.hasOwnProperty( subpages[ i ].key ) ) {
						checkPages.push( pages[ subpages[ i ].key ] );
					}
				}
			}

			$.when.apply( $, deferreds ).done( function () {
				if ( $.inArray( false, arguments ) !== -1 ) {
					windowManager.openWindow( 'errorAlert', {
						title: mw.message( 'apisandbox-submit-invalid-fields-title' ).parse(),
						message: mw.message( 'apisandbox-submit-invalid-fields-message' ).parse(),
						actions: [
							{
								action: 'accept',
								label: OO.ui.msg( 'ooui-dialog-process-dismiss' ),
								flags: 'primary'
							}
						]
					} );
					return;
				}

				query = $.param( displayParams );

				// Force a 'fm' format with wrappedhtml=1, if available
				if ( params.format !== undefined ) {
					if ( availableFormats.hasOwnProperty( params.format + 'fm' ) ) {
						params.format = params.format + 'fm';
					}
					if ( params.format.substr( -2 ) === 'fm' ) {
						params.wrappedhtml = 1;
					}
				}

				progressLoading = false;
				$progressText = $( '<span>' ).text( mw.message( 'apisandbox-sending-request' ).text() );
				progress = new OO.ui.ProgressBarWidget( {
					progress: false,
					$content: $progressText
				} );

				$result = $( '<div>' )
					.append( progress.$element );

				resultPage = page = new OO.ui.PageLayout( '|results|' );
				page.setupOutlineItem = function () {
					this.outlineItem.setLabel( mw.message( 'apisandbox-results' ).text() );
				};
				page.$element.empty()
					.append(
						new OO.ui.FieldLayout(
							new OO.ui.TextInputWidget( {
								readOnly: true,
								value: mw.util.wikiScript( 'api' ) + '?' + query
							} ), {
								label: mw.message( 'apisandbox-request-url-label' ).parse()
							}
						).$element,
						$result
					);
				ApiSandbox.updateUI();
				booklet.setPage( '|results|' );

				location.href = oldhash = '#' + query;

				api.post( params, {
					contentType: 'multipart/form-data',
					dataType: 'text',
					xhr: function () {
						var xhr = new window.XMLHttpRequest();
						xhr.upload.addEventListener( 'progress', function ( e ) {
							if ( !progressLoading ) {
								if ( e.lengthComputable ) {
									progress.setProgress( e.loaded * 100 / e.total );
								} else {
									progress.setProgress( false );
								}
							}
						} );
						xhr.addEventListener( 'progress', function ( e ) {
							if ( !progressLoading ) {
								progressLoading = true;
								$progressText.text( mw.message( 'apisandbox-loading-results' ).text() );
							}
							if ( e.lengthComputable ) {
								progress.setProgress( e.loaded * 100 / e.total );
							} else {
								progress.setProgress( false );
							}
						} );
						return xhr;
					}
				} )
					.then( null, function ( code, data, result, jqXHR ) {
						if ( code !== 'http' ) {
							// Not really an error, work around mw.Api thinking it is.
							return $.Deferred()
								.resolve( result, jqXHR )
								.promise();
						}
						return this;
					} )
					.fail( function ( code, data ) {
						var details = 'HTTP error: ' + data.exception;
						$result.empty()
							.append(
								new OO.ui.LabelWidget( {
									label: mw.message( 'apisandbox-results-error', details ).text(),
									classes: [ 'error' ]
								} ).$element
							);
					} )
					.done( function ( data, jqXHR ) {
						var m, loadTime, button,
							ct = jqXHR.getResponseHeader( 'Content-Type' );

						$result.empty();
						if ( /^text\/mediawiki-api-prettyprint-wrapped(?:;|$)/.test( ct ) ) {
							data = $.parseJSON( data );
							if ( data.modules.length ) {
								mw.loader.load( data.modules );
							}
							$result.append( Util.parseHTML( data.html ) );
							loadTime = data.time;
						} else if ( ( m = data.match( /<pre[ >][\s\S]*<\/pre>/ ) ) ) {
							$result.append( Util.parseHTML( m[ 0 ] ) );
							if ( ( m = data.match( /"wgBackendResponseTime":\s*(\d+)/ ) ) ) {
								loadTime = parseInt( m[ 1 ], 10 );
							}
						} else {
							$( '<pre>' )
								.addClass( 'api-pretty-content' )
								.text( data )
								.appendTo( $result );
						}
						if ( typeof loadTime === 'number' ) {
							$result.append(
								$( '<div>' ).append(
									new OO.ui.LabelWidget( {
										label: mw.message( 'apisandbox-request-time', loadTime ).text()
									} ).$element
								)
							);
						}

						if ( jqXHR.getResponseHeader( 'MediaWiki-API-Error' ) === 'badtoken' ) {
							// Flush all saved tokens in case one of them is the bad one.
							Util.markTokensBad();
							button = new OO.ui.ButtonWidget( {
								label: mw.message( 'apisandbox-results-fixtoken' ).text()
							} );
							button.on( 'click', ApiSandbox.fixTokenAndResend )
								.on( 'click', button.setDisabled, [ true ], button )
								.$element.appendTo( $result );
						}
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
			var page, subpages, i, k,
				ok = true,
				tokenWait = { dummy: true },
				checkPages = [ pages.main ],
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
				page = checkPages.shift();

				if ( page.tokenWidget ) {
					k = page.apiModule + page.tokenWidget.paramInfo.name;
					tokenWait[ k ] = page.tokenWidget.fetchToken()
						.done( success.bind( page.tokenWidget, k ) )
						.fail( failure.bind( page.tokenWidget, k ) );
				}

				subpages = page.getSubpages();
				for ( i = 0; i < subpages.length; i++ ) {
					if ( pages.hasOwnProperty( subpages[ i ].key ) ) {
						checkPages.push( pages[ subpages[ i ].key ] );
					}
				}
			}

			success( 'dummy', '' );
		},

		/**
		 * Reset validity indicators for all widgets
		 */
		updateValidityIndicators: function () {
			var page, subpages, i,
				checkPages = [ pages.main ];

			while ( checkPages.length ) {
				page = checkPages.shift();
				page.apiCheckValid();
				subpages = page.getSubpages();
				for ( i = 0; i < subpages.length; i++ ) {
					if ( pages.hasOwnProperty( subpages[ i ].key ) ) {
						checkPages.push( pages[ subpages[ i ].key ] );
					}
				}
			}
		}
	};

	/**
	 * PageLayout for API modules
	 *
	 * @class
	 * @private
	 * @extends OO.ui.PageLayout
	 * @constructor
	 * @param {Object} [config] Configuration options
	 */
	ApiSandbox.PageLayout = function ( config ) {
		config = $.extend( { prefix: '' }, config );
		this.displayText = config.key;
		this.apiModule = config.path;
		this.prefix = config.prefix;
		this.paramInfo = null;
		this.apiIsValid = true;
		this.loadFromQueryParams = null;
		this.widgets = {};
		this.tokenWidget = null;
		this.indentLevel = config.indentLevel ? config.indentLevel : 0;
		ApiSandbox.PageLayout[ 'super' ].call( this, config.key, config );
		this.loadParamInfo();
	};
	OO.inheritClass( ApiSandbox.PageLayout, OO.ui.PageLayout );
	ApiSandbox.PageLayout.prototype.setupOutlineItem = function () {
		this.outlineItem.setLevel( this.indentLevel );
		this.outlineItem.setLabel( this.displayText );
		this.outlineItem.setIcon( this.apiIsValid || suppressErrors ? null : 'alert' );
		this.outlineItem.setIconTitle(
			this.apiIsValid || suppressErrors ? '' : mw.message( 'apisandbox-alert-page' ).plain()
		);
	};

	/**
	 * Fetch module information for this page's module, then create UI
	 */
	ApiSandbox.PageLayout.prototype.loadParamInfo = function () {
		var dynamicFieldset, dynamicParamNameWidget,
			that = this,
			removeDynamicParamWidget = function ( name, layout ) {
				dynamicFieldset.removeItems( [ layout ] );
				delete that.widgets[ name ];
			},
			addDynamicParamWidget = function () {
				var name, layout, widget, button;

				// Check name is filled in
				name = dynamicParamNameWidget.getValue().trim();
				if ( name === '' ) {
					dynamicParamNameWidget.focus();
					return;
				}

				if ( that.widgets[ name ] !== undefined ) {
					windowManager.openWindow( 'errorAlert', {
						title: mw.message(
							'apisandbox-dynamic-error-exists', name
						).parse(),
						actions: [
							{
								action: 'accept',
								label: OO.ui.msg( 'ooui-dialog-process-dismiss' ),
								flags: 'primary'
							}
						]
					} );
					return;
				}

				widget = Util.createWidgetForParameter( {
					name: name,
					type: 'string',
					'default': ''
				}, {
					nooptional: true
				} );
				button = new OO.ui.ButtonWidget( {
					icon: 'remove',
					flags: 'destructive'
				} );
				layout = new OO.ui.ActionFieldLayout(
					widget,
					button,
					{
						label: name,
						align: 'left'
					}
				);
				button.on( 'click', removeDynamicParamWidget, [ name, layout ] );
				that.widgets[ name ] = widget;
				dynamicFieldset.addItems( [ layout ], dynamicFieldset.getItems().length - 1 );
				widget.focus();

				dynamicParamNameWidget.setValue( '' );
			};

		this.$element.empty()
			.append( new OO.ui.ProgressBarWidget( {
				progress: false,
				text: mw.message( 'apisandbox-loading', this.displayText ).text()
			} ).$element );

		Util.fetchModuleInfo( this.apiModule )
			.done( function ( pi ) {
				var prefix, i, j, dl, widget, $widgetLabel, widgetField, helpField, tmp, flag, count,
					items = [],
					deprecatedItems = [],
					buttons = [],
					filterFmModules = function ( v ) {
						return v.substr( -2 ) !== 'fm' ||
							!availableFormats.hasOwnProperty( v.substr( 0, v.length - 2 ) );
					},
					widgetLabelOnClick = function () {
						var f = this.getField();
						if ( $.isFunction( f.setDisabled ) ) {
							f.setDisabled( false );
						}
						if ( $.isFunction( f.focus ) ) {
							f.focus();
						}
					},
					doNothing = function () {};

				// This is something of a hack. We always want the 'format' and
				// 'action' parameters from the main module to be specified,
				// and for 'format' we also want to simplify the dropdown since
				// we always send the 'fm' variant.
				if ( that.apiModule === 'main' ) {
					for ( i = 0; i < pi.parameters.length; i++ ) {
						if ( pi.parameters[ i ].name === 'action' ) {
							pi.parameters[ i ].required = true;
							delete pi.parameters[ i ][ 'default' ];
						}
						if ( pi.parameters[ i ].name === 'format' ) {
							tmp = pi.parameters[ i ].type;
							for ( j = 0; j < tmp.length; j++ ) {
								availableFormats[ tmp[ j ] ] = true;
							}
							pi.parameters[ i ].type = $.grep( tmp, filterFmModules );
							pi.parameters[ i ][ 'default' ] = 'json';
							pi.parameters[ i ].required = true;
						}
					}
				}

				// Hide the 'wrappedhtml' parameter on format modules
				if ( pi.group === 'format' ) {
					pi.parameters = $.grep( pi.parameters, function ( p ) {
						return p.name !== 'wrappedhtml';
					} );
				}

				that.paramInfo = pi;

				items.push( new OO.ui.FieldLayout(
					new OO.ui.Widget( {} ).toggle( false ), {
						align: 'top',
						label: Util.parseHTML( pi.description )
					}
				) );

				if ( pi.helpurls.length ) {
					buttons.push( new OO.ui.PopupButtonWidget( {
						label: mw.message( 'apisandbox-helpurls' ).text(),
						icon: 'help',
						popup: {
							$content: $( '<ul>' ).append( $.map( pi.helpurls, function ( link ) {
								return $( '<li>' ).append( $( '<a>', {
									href: link,
									target: '_blank',
									text: link
								} ) );
							} ) )
						}
					} ) );
				}

				if ( pi.examples.length ) {
					buttons.push( new OO.ui.PopupButtonWidget( {
						label: mw.message( 'apisandbox-examples' ).text(),
						icon: 'code',
						popup: {
							$content: $( '<ul>' ).append( $.map( pi.examples, function ( example ) {
								var a = $( '<a>', {
									href: '#' + example.query,
									html: example.description
								} );
								a.find( 'a' ).contents().unwrap(); // Can't nest links
								return $( '<li>' ).append( a );
							} ) )
						}
					} ) );
				}

				if ( buttons.length ) {
					items.push( new OO.ui.FieldLayout(
						new OO.ui.ButtonGroupWidget( {
							items: buttons
						} ), { align: 'top' }
					) );
				}

				if ( pi.parameters.length ) {
					prefix = that.prefix + pi.prefix;
					for ( i = 0; i < pi.parameters.length; i++ ) {
						widget = Util.createWidgetForParameter( pi.parameters[ i ] );
						that.widgets[ prefix + pi.parameters[ i ].name ] = widget;
						if ( pi.parameters[ i ].tokentype ) {
							that.tokenWidget = widget;
						}

						dl = $( '<dl>' );
						dl.append( $( '<dd>', {
							addClass: 'description',
							append: Util.parseHTML( pi.parameters[ i ].description )
						} ) );
						if ( pi.parameters[ i ].info && pi.parameters[ i ].info.length ) {
							for ( j = 0; j < pi.parameters[ i ].info.length; j++ ) {
								dl.append( $( '<dd>', {
									addClass: 'info',
									append: Util.parseHTML( pi.parameters[ i ].info[ j ] )
								} ) );
							}
						}
						flag = true;
						count = 1e100;
						switch ( pi.parameters[ i ].type ) {
							case 'namespace':
								flag = false;
								count = mw.config.get( 'wgFormattedNamespaces' ).length;
								break;

							case 'limit':
								if ( pi.parameters[ i ].highmax !== undefined ) {
									dl.append( $( '<dd>', {
										addClass: 'info',
										append: Util.parseHTML( mw.message(
											'api-help-param-limit2', pi.parameters[ i ].max, pi.parameters[ i ].highmax
										).parse() )
									} ) );
								} else {
									dl.append( $( '<dd>', {
										addClass: 'info',
										append: Util.parseHTML( mw.message(
											'api-help-param-limit', pi.parameters[ i ].max
										).parse() )
									} ) );
								}
								break;

							case 'integer':
								tmp = '';
								if ( pi.parameters[ i ].min !== undefined ) {
									tmp += 'min';
								}
								if ( pi.parameters[ i ].max !== undefined ) {
									tmp += 'max';
								}
								if ( tmp !== '' ) {
									dl.append( $( '<dd>', {
										addClass: 'info',
										append: Util.parseHTML( mw.message(
											'api-help-param-integer-' + tmp,
											Util.apiBool( pi.parameters[ i ].multi ) ? 2 : 1,
											pi.parameters[ i ].min, pi.parameters[ i ].max
										).parse() )
									} ) );
								}
								break;

							default:
								if ( $.isArray( pi.parameters[ i ].type ) ) {
									flag = false;
									count = pi.parameters[ i ].type.length;
								}
								break;
						}
						if ( Util.apiBool( pi.parameters[ i ].multi ) ) {
							tmp = [];
							if ( flag && !( widget instanceof OO.ui.CapsuleMultiSelectWidget ) &&
								!(
									widget instanceof OptionalWidget &&
									widget.widget instanceof OO.ui.CapsuleMultiSelectWidget
								)
							) {
								tmp.push( mw.message( 'api-help-param-multi-separate' ).parse() );
							}
							if ( count > pi.parameters[ i ].lowlimit ) {
								tmp.push(
									mw.message( 'api-help-param-multi-max',
										pi.parameters[ i ].lowlimit, pi.parameters[ i ].highlimit
									).parse()
								);
							}
							if ( tmp.length ) {
								dl.append( $( '<dd>', {
									addClass: 'info',
									append: Util.parseHTML( tmp.join( ' ' ) )
								} ) );
							}
						}
						helpField = new OO.ui.FieldLayout(
							new OO.ui.Widget( {
								$content: '\xa0',
								classes: [ 'mw-apisandbox-spacer' ]
							} ), {
								align: 'inline',
								classes: [ 'mw-apisandbox-help-field' ],
								label: dl
							}
						);

						$widgetLabel = $( '<span>' );
						widgetField = new OO.ui.FieldLayout(
							widget,
							{
								align: 'left',
								classes: [ 'mw-apisandbox-widget-field' ],
								label: prefix + pi.parameters[ i ].name,
								$label: $widgetLabel
							}
						);

						// FieldLayout only does click for InputElement
						// widgets. So supply our own click handler.
						$widgetLabel.on( 'click', widgetLabelOnClick.bind( widgetField ) );

						// Don't grey out the label when the field is disabled,
						// it makes it too hard to read and our "disabled"
						// isn't really disabled.
						widgetField.onFieldDisable = doNothing;

						if ( Util.apiBool( pi.parameters[ i ].deprecated ) ) {
							deprecatedItems.push( widgetField, helpField );
						} else {
							items.push( widgetField, helpField );
						}
					}
				}

				if ( !pi.parameters.length && !Util.apiBool( pi.dynamicparameters ) ) {
					items.push( new OO.ui.FieldLayout(
						new OO.ui.Widget( {} ).toggle( false ), {
							align: 'top',
							label: Util.parseHTML( mw.message( 'apisandbox-no-parameters' ).parse() )
						}
					) );
				}

				that.$element.empty();

				new OO.ui.FieldsetLayout( {
					label: that.displayText
				} ).addItems( items )
					.$element.appendTo( that.$element );

				if ( Util.apiBool( pi.dynamicparameters ) ) {
					dynamicFieldset = new OO.ui.FieldsetLayout();
					dynamicParamNameWidget = new OO.ui.TextInputWidget( {
						placeholder: mw.message( 'apisandbox-dynamic-parameters-add-placeholder' ).text()
					} ).on( 'enter', addDynamicParamWidget );
					dynamicFieldset.addItems( [
						new OO.ui.FieldLayout(
							new OO.ui.Widget( {} ).toggle( false ), {
								align: 'top',
								label: Util.parseHTML( pi.dynamicparameters )
							}
						),
						new OO.ui.ActionFieldLayout(
							dynamicParamNameWidget,
							new OO.ui.ButtonWidget( {
								icon: 'add',
								flags: 'constructive'
							} ).on( 'click', addDynamicParamWidget ),
							{
								label: mw.message( 'apisandbox-dynamic-parameters-add-label' ).text(),
								align: 'left'
							}
						)
					] );
					$( '<fieldset>' )
						.append(
							$( '<legend>' ).text( mw.message( 'apisandbox-dynamic-parameters' ).text() ),
							dynamicFieldset.$element
						)
						.appendTo( that.$element );
				}

				if ( deprecatedItems.length ) {
					tmp = new OO.ui.FieldsetLayout().addItems( deprecatedItems ).toggle( false );
					$( '<fieldset>' )
						.append(
							$( '<legend>' ).append(
								new OO.ui.ToggleButtonWidget( {
									label: mw.message( 'apisandbox-deprecated-parameters' ).text()
								} ).on( 'change', tmp.toggle, [], tmp ).$element
							),
							tmp.$element
						)
						.appendTo( that.$element );
				}

				// Load stored params, if any, then update the booklet if we
				// have subpages (or else just update our valid-indicator).
				tmp = that.loadFromQueryParams;
				that.loadFromQueryParams = null;
				if ( $.isPlainObject( tmp ) ) {
					that.loadQueryParams( tmp );
				}
				if ( that.getSubpages().length > 0 ) {
					ApiSandbox.updateUI( tmp );
				} else {
					that.apiCheckValid();
				}
			} ).fail( function ( code, detail ) {
				that.$element.empty()
					.append(
						new OO.ui.LabelWidget( {
							label: mw.message( 'apisandbox-load-error', that.apiModule, detail ).text(),
							classes: [ 'error' ]
						} ).$element,
						new OO.ui.ButtonWidget( {
							label: mw.message( 'apisandbox-retry' ).text()
						} ).on( 'click', that.loadParamInfo, [], that ).$element
					);
			} );
	};

	/**
	 * Check that all widgets on the page are in a valid state.
	 *
	 * @return {boolean}
	 */
	ApiSandbox.PageLayout.prototype.apiCheckValid = function () {
		var that = this;

		if ( this.paramInfo === null ) {
			return $.Deferred().resolve( false ).promise();
		} else {
			return $.when.apply( $, $.map( this.widgets, function ( widget ) {
				return widget.apiCheckValid();
			} ) ).then( function () {
				that.apiIsValid = $.inArray( false, arguments ) === -1;
				if ( that.getOutlineItem() ) {
					that.getOutlineItem().setIcon( that.apiIsValid || suppressErrors ? null : 'alert' );
					that.getOutlineItem().setIconTitle(
						that.apiIsValid || suppressErrors ? '' : mw.message( 'apisandbox-alert-page' ).plain()
					);
				}
				return $.Deferred().resolve( that.apiIsValid ).promise();
			} );
		}
	};

	/**
	 * Load form fields from query parameters
	 *
	 * @param {Object} params
	 */
	ApiSandbox.PageLayout.prototype.loadQueryParams = function ( params ) {
		if ( this.paramInfo === null ) {
			this.loadFromQueryParams = params;
		} else {
			$.each( this.widgets, function ( name, widget ) {
				var v = params.hasOwnProperty( name ) ? params[ name ] : undefined;
				widget.setApiValue( v );
			} );
		}
	};

	/**
	 * Load query params from form fields
	 *
	 * @param {Object} params Write query parameters into this object
	 * @param {Object} displayParams Write query parameters for display into this object
	 */
	ApiSandbox.PageLayout.prototype.getQueryParams = function ( params, displayParams ) {
		$.each( this.widgets, function ( name, widget ) {
			var value = widget.getApiValue();
			if ( value !== undefined ) {
				params[ name ] = value;
				if ( $.isFunction( widget.getApiValueForDisplay ) ) {
					value = widget.getApiValueForDisplay();
				}
				displayParams[ name ] = value;
			}
		} );
	};

	/**
	 * Fetch a list of subpage names loaded by this page
	 *
	 * @return {Array}
	 */
	ApiSandbox.PageLayout.prototype.getSubpages = function () {
		var ret = [];
		$.each( this.widgets, function ( name, widget ) {
			var submodules, i;
			if ( $.isFunction( widget.getSubmodules ) ) {
				submodules = widget.getSubmodules();
				for ( i = 0; i < submodules.length; i++ ) {
					ret.push( {
						key: name + '=' + submodules[ i ].value,
						path: submodules[ i ].path,
						prefix: widget.paramInfo.submoduleparamprefix || ''
					} );
				}
			}
		} );
		return ret;
	};

	/**
	 * A text input with a clickable indicator
	 *
	 * @class
	 * @private
	 * @constructor
	 * @param {Object} [config] Configuration options
	 */
	function TextInputWithIndicatorWidget( config ) {
		var k;

		config = config || {};
		TextInputWithIndicatorWidget[ 'super' ].call( this, config );

		this.$indicator = $( '<span>' ).addClass( 'mw-apisandbox-clickable-indicator' );
		OO.ui.mixin.TabIndexedElement.call(
			this, $.extend( {}, config, { $tabIndexed: this.$indicator } )
		);

		this.input = new OO.ui.TextInputWidget( $.extend( {
			$indicator: this.$indicator,
			disabled: this.isDisabled()
		}, config.input ) );

		// Forward most methods for convenience
		for ( k in this.input ) {
			if ( $.isFunction( this.input[ k ] ) && !this[ k ] ) {
				this[ k ] = this.input[ k ].bind( this.input );
			}
		}

		this.$indicator.on( {
			click: this.onIndicatorClick.bind( this ),
			keypress: this.onIndicatorKeyPress.bind( this )
		} );

		this.$element.append( this.input.$element );
	}
	OO.inheritClass( TextInputWithIndicatorWidget, OO.ui.Widget );
	OO.mixinClass( TextInputWithIndicatorWidget, OO.ui.mixin.TabIndexedElement );
	TextInputWithIndicatorWidget.prototype.onIndicatorClick = function ( e ) {
		if ( !this.isDisabled() && e.which === 1 ) {
			this.emit( 'indicator' );
		}
		return false;
	};
	TextInputWithIndicatorWidget.prototype.onIndicatorKeyPress = function ( e ) {
		if ( !this.isDisabled() && ( e.which === OO.ui.Keys.SPACE || e.which === OO.ui.Keys.ENTER ) ) {
			this.emit( 'indicator' );
			return false;
		}
	};
	TextInputWithIndicatorWidget.prototype.setDisabled = function ( disabled ) {
		TextInputWithIndicatorWidget[ 'super' ].prototype.setDisabled.call( this, disabled );
		if ( this.input ) {
			this.input.setDisabled( this.isDisabled() );
		}
		return this;
	};

	/**
	 * A wrapper for a widget that provides an enable/disable button
	 *
	 * @class
	 * @private
	 * @constructor
	 * @param {OO.ui.Widget} widget
	 * @param {Object} [config] Configuration options
	 */
	function OptionalWidget( widget, config ) {
		var k;

		config = config || {};

		this.widget = widget;
		this.$overlay = config.$overlay ||
			$( '<div>' ).addClass( 'mw-apisandbox-optionalWidget-overlay' );
		this.checkbox = new OO.ui.CheckboxInputWidget( config.checkbox )
			.on( 'change', this.onCheckboxChange, [], this );

		OptionalWidget[ 'super' ].call( this, config );

		// Forward most methods for convenience
		for ( k in this.widget ) {
			if ( $.isFunction( this.widget[ k ] ) && !this[ k ] ) {
				this[ k ] = this.widget[ k ].bind( this.widget );
			}
		}

		this.$overlay.on( 'click', this.onOverlayClick.bind( this ) );

		this.$element
			.addClass( 'mw-apisandbox-optionalWidget' )
			.append(
				this.$overlay,
				$( '<div>' ).addClass( 'mw-apisandbox-optionalWidget-fields' ).append(
					$( '<div>' ).addClass( 'mw-apisandbox-optionalWidget-widget' ).append(
						widget.$element
					),
					$( '<div>' ).addClass( 'mw-apisandbox-optionalWidget-checkbox' ).append(
						this.checkbox.$element
					)
				)
			);

		this.setDisabled( widget.isDisabled() );
	}
	OO.inheritClass( OptionalWidget, OO.ui.Widget );
	OptionalWidget.prototype.onCheckboxChange = function ( checked ) {
		this.setDisabled( !checked );
	};
	OptionalWidget.prototype.onOverlayClick = function () {
		this.setDisabled( false );
		if ( $.isFunction( this.widget.focus ) ) {
			this.widget.focus();
		}
	};
	OptionalWidget.prototype.setDisabled = function ( disabled ) {
		OptionalWidget[ 'super' ].prototype.setDisabled.call( this, disabled );
		this.widget.setDisabled( this.isDisabled() );
		this.checkbox.setSelected( !this.isDisabled() );
		this.$overlay.toggle( this.isDisabled() );
		return this;
	};

	$( ApiSandbox.init );

}( jQuery, mediaWiki, OO ) );
