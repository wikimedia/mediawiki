( function () {
	'use strict';
	var ApiSandbox, Util, WidgetMethods, Validators,
		windowManager,
		formatDropdown,
		api = new mw.Api(),
		bookletPages = [],
		availableFormats = {},
		resultPage = null,
		suppressErrors = true,
		updatingBooklet = false,
		pages = {},
		moduleInfoCache = {},
		baseRequestParams = {},
		OptionalParamWidget = require( './OptionalParamWidget.js' ),
		ParamLabelWidget = require( './ParamLabelWidget.js' ),
		BooleanToggleSwitchParamWidget = require( './BooleanToggleSwitchParamWidget.js' ),
		DateTimeParamWidget = require( './DateTimeParamWidget.js' ),
		LimitParamWidget = require( './LimitParamWidget.js' ),
		PasswordParamWidget = require( './PasswordParamWidget.js' ),
		UploadSelectFileParamWidget = require( './UploadSelectFileParamWidget.js' );

	WidgetMethods = {
		textInputWidget: {
			getApiValue: function () {
				return this.getValue();
			},
			setApiValue: function ( v ) {
				if ( v === undefined ) {
					v = this.paramInfo.default;
				}
				this.setValue( v );
			},
			apiCheckValid: function ( shouldSuppressErrors ) {
				var widget = this;
				return this.getValidity().then( function () {
					return $.Deferred().resolve( true ).promise();
				}, function () {
					return $.Deferred().resolve( false ).promise();
				} ).done( function ( ok ) {
					ok = ok || shouldSuppressErrors;
					widget.setIcon( ok ? null : 'alert' );
					widget.setTitle( ok ? '' : mw.message( 'apisandbox-alert-field' ).plain() );
				} );
			}
		},

		tokenWidget: {
			alertTokenError: function ( code, error ) {
				windowManager.openWindow( 'errorAlert', {
					title: Util.parseMsg( 'apisandbox-results-fixtoken-fail', this.paramInfo.tokentype ),
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
				if ( v === undefined ) {
					v = this.paramInfo.default;
				}
				this.setValue( v );
				if ( v === '123ABC' ) {
					this.fetchToken();
				}
			}
		},

		dropdownWidget: {
			getApiValue: function () {
				var selected = this.getMenu().findFirstSelectedItem();
				return selected ? selected.getData() : undefined;
			},
			setApiValue: function ( v ) {
				if ( v === undefined ) {
					v = this.paramInfo.default;
				}
				var menu = this.getMenu();
				if ( v === undefined ) {
					menu.selectItem();
				} else {
					menu.selectItemByData( String( v ) );
				}
			},
			apiCheckValid: function ( shouldSuppressErrors ) {
				var ok = this.getApiValue() !== undefined || shouldSuppressErrors;
				this.setIcon( ok ? null : 'alert' );
				this.setTitle( ok ? '' : mw.message( 'apisandbox-alert-field' ).plain() );
				return $.Deferred().resolve( ok ).promise();
			}
		},

		tagWidget: {
			parseApiValue: function ( v ) {
				if ( v === undefined || v === '' || v === '\x1f' ) {
					return [];
				} else {
					v = String( v );
					if ( v[ 0 ] !== '\x1f' ) {
						return v.split( '|' );
					} else {
						return v.slice( 1 ).split( '\x1f' );
					}
				}
			},
			getApiValueForTemplates: function () {
				return this.isDisabled() ? this.parseApiValue( this.paramInfo.default ) : this.getValue();
			},
			getApiValue: function () {
				var items = this.getValue();
				if ( items.join( '' ).indexOf( '|' ) === -1 ) {
					return items.join( '|' );
				} else {
					return '\x1f' + items.join( '\x1f' );
				}
			},
			setApiValue: function ( v ) {
				if ( v === undefined ) {
					v = this.paramInfo.default;
				}
				this.setValue( this.parseApiValue( v ) );
			},
			apiCheckValid: function ( shouldSuppressErrors ) {
				var ok = true;
				if ( !shouldSuppressErrors ) {
					var pi = this.paramInfo;
					ok = this.getApiValue() !== undefined && !(
						pi.allspecifier !== undefined &&
						this.getValue().length > 1 &&
						this.getValue().indexOf( pi.allspecifier ) !== -1
					);
				}

				this.setIcon( ok ? null : 'alert' );
				this.setTitle( ok ? '' : mw.message( 'apisandbox-alert-field' ).plain() );
				return $.Deferred().resolve( ok ).promise();
			},
			createTagItemWidget: function ( data, label ) {
				var item = OO.ui.TagMultiselectWidget.prototype.createTagItemWidget.call( this, data, label );
				if ( this.paramInfo.deprecatedvalues &&
					this.paramInfo.deprecatedvalues.indexOf( data ) >= 0
				) {
					item.$element.addClass( 'mw-apisandbox-deprecated-value' );
				}
				if ( this.paramInfo.internalvalues &&
					this.paramInfo.internalvalues.indexOf( data ) >= 0
				) {
					item.$element.addClass( 'mw-apisandbox-internal-value' );
				}
				return item;
			}
		},

		submoduleWidget: {
			single: function () {
				var v = this.isDisabled() ? this.paramInfo.default : this.getApiValue();
				return v === undefined ? [] : [ { value: v, path: this.paramInfo.submodules[ v ] } ];
			},
			multi: function () {
				var map = this.paramInfo.submodules,
					v = this.isDisabled() ? this.paramInfo.default : this.getApiValue();
				return v === undefined || v === '' ? [] : String( v ).split( '|' ).map( function ( val ) {
					return { value: val, path: map[ val ] };
				} );
			}
		}
	};

	Validators = {
		generic: function () {
			return !Util.apiBool( this.paramInfo.required ) || this.getApiValue() !== '';
		}
	};

	/**
	 * @class mw.special.ApiSandbox.Util
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
			var deferred = $.Deferred();

			if ( Object.prototype.hasOwnProperty.call( moduleInfoCache, module ) ) {
				return deferred
					.resolve( moduleInfoCache[ module ] )
					.promise( { abort: function () {} } );
			} else {
				var apiPromise = api.post( {
					action: 'paraminfo',
					modules: module,
					helpformat: 'html',
					uselang: mw.config.get( 'wgUserLanguage' )
				} ).done( function ( data ) {
					if ( data.warnings && data.warnings.paraminfo ) {
						deferred.reject( '???', data.warnings.paraminfo[ '*' ] );
						return;
					}

					var info = data.paraminfo.modules;
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
			var checkPages = [ pages.main ];

			while ( checkPages.length ) {
				var page = checkPages.shift();

				if ( page.tokenWidget ) {
					api.badToken( page.tokenWidget.paramInfo.tokentype );
				}

				var subpages = page.getSubpages();
				// eslint-disable-next-line no-loop-func
				subpages.forEach( function ( subpage ) {
					if ( Object.prototype.hasOwnProperty.call( pages, subpage.key ) ) {
						checkPages.push( pages[ subpage.key ] );
					}
				} );
			}
		},

		/**
		 * Test an API boolean
		 *
		 * @param {any} value
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
			var multiModeButton = null,
				multiModeInput = null,
				multiModeAllowed = false;

			opts = opts || {};

			var widget, items;
			switch ( pi.type ) {
				case 'boolean':
					widget = new BooleanToggleSwitchParamWidget();
					widget.paramInfo = pi;
					pi.required = true; // Avoid wrapping in the non-required widget
					break;

				case 'string':
					// ApiParamInfo only sets `tokentype` when the parameter
					// name is `token` AND the module ::needsToken() returns
					// a truthy value; ApiBase, when the module ::needsToken()
					// returns a truthy value, sets the `token` param to PARAM_TYPE
					// string always, so we only need to have handling for
					// token widgets for `string`. The token never accepts multiple
					// values, though that doesn't appear to be enforced anywhere...
					// and the token widget methods all assume it only a single value
					if ( pi.tokentype ) {
						// We probably don't need to check if its required,
						// it always is, but whats the harm
						widget = new OO.ui.TextInputWidget( {
							required: Util.apiBool( pi.required )
						} );
						widget.paramInfo = pi;
						$.extend( widget, WidgetMethods.textInputWidget );
						widget.setValidation( Validators.generic );
						$.extend( widget, WidgetMethods.tokenWidget );
						break;
					}
					// intentional fall through
				case 'user':
				case 'expiry':
					if ( Util.apiBool( pi.multi ) ) {
						widget = new OO.ui.TagMultiselectWidget( {
							allowArbitrary: true,
							allowDuplicates: Util.apiBool( pi.allowsduplicates ),
							$overlay: true
						} );
						widget.paramInfo = pi;
						$.extend( widget, WidgetMethods.tagWidget );
					} else {
						widget = new OO.ui.TextInputWidget( {
							required: Util.apiBool( pi.required )
						} );
						widget.paramInfo = pi;
						$.extend( widget, WidgetMethods.textInputWidget );
						widget.setValidation( Validators.generic );
					}
					break;

				case 'raw':
				case 'text':
					widget = new OO.ui.MultilineTextInputWidget( {
						required: Util.apiBool( pi.required )
					} );
					widget.paramInfo = pi;
					$.extend( widget, WidgetMethods.textInputWidget );
					widget.setValidation( Validators.generic );
					break;

				case 'password':
					widget = new PasswordParamWidget( {
						required: Util.apiBool( pi.required )
					} );
					widget.paramInfo = pi;
					widget.setValidation( Validators.generic );
					multiModeAllowed = true;
					multiModeInput = widget;
					break;

				case 'integer':
					widget = new OO.ui.NumberInputWidget( {
						step: 1,
						min: pi.min || -Infinity,
						max: pi.max || Infinity,
						required: Util.apiBool( pi.required )
					} );
					widget.paramInfo = pi;
					$.extend( widget, WidgetMethods.textInputWidget );
					multiModeAllowed = true;
					multiModeInput = widget;
					break;

				case 'limit':
					widget = new LimitParamWidget( {
						required: Util.apiBool( pi.required )
					} );
					pi.min = pi.min || 0;
					pi.apiSandboxMax = ( mw.config.get( 'apihighlimits' ) ? pi.highmax : pi.max ) || pi.max;
					widget.paramInfo = pi;
					multiModeAllowed = true;
					multiModeInput = widget;
					break;

				case 'timestamp':
					widget = new DateTimeParamWidget( {
						required: Util.apiBool( pi.required )
					} );
					widget.paramInfo = pi;
					multiModeAllowed = true;
					break;

				case 'upload':
					widget = new UploadSelectFileParamWidget();
					widget.paramInfo = pi;
					break;

				case 'namespace':
					// eslint-disable-next-line no-jquery/no-map-util
					items = $.map( mw.config.get( 'wgFormattedNamespaces' ), function ( name, ns ) {
						if ( ns === '0' ) {
							name = mw.msg( 'blanknamespace' );
						}
						return new OO.ui.MenuOptionWidget( { data: ns, label: name } );
					} ).sort( function ( a, b ) {
						return a.data - b.data;
					} );
					if ( Util.apiBool( pi.multi ) ) {
						if ( pi.allspecifier !== undefined ) {
							items.unshift( new OO.ui.MenuOptionWidget( {
								data: pi.allspecifier,
								label: mw.msg( 'apisandbox-multivalue-all-namespaces', pi.allspecifier )
							} ) );
						}

						widget = new OO.ui.MenuTagMultiselectWidget( {
							menu: { items: items },
							$overlay: true
						} );
						widget.paramInfo = pi;
						$.extend( widget, WidgetMethods.tagWidget );
					} else {
						widget = new OO.ui.DropdownWidget( {
							menu: { items: items },
							$overlay: true
						} );
						widget.paramInfo = pi;
						$.extend( widget, WidgetMethods.dropdownWidget );
					}
					break;

				case 'title':
					if ( Util.apiBool( pi.multi ) ) {
						widget = new mw.widgets.TitlesMultiselectWidget( {
							required: Util.apiBool( pi.required ),
							validateTitle: true,
							suggestions: true,
							showMissing: !Util.apiBool( pi.mustExist ),
							addQueryInput: !Util.apiBool( pi.mustExist ),
							tagLimit: pi.limit || undefined
						} );
						widget.paramInfo = pi;
						$.extend( widget, WidgetMethods.tagWidget );
					} else {
						widget = new mw.widgets.TitleInputWidget( {
							required: Util.apiBool( pi.required ),
							validateTitle: true,
							suggestions: true,
							autocomplete: true,
							showMissing: !Util.apiBool( pi.mustExist ),
							addQueryInput: !Util.apiBool( pi.mustExist )
						} );
						widget.paramInfo = pi;
						$.extend( widget, WidgetMethods.textInputWidget );
					}
					break;

				default:
					if ( !Array.isArray( pi.type ) ) {
						throw new Error( 'Unknown parameter type ' + pi.type );
					}

					items = pi.type.map( function ( v ) {
						var optionWidget = new OO.ui.MenuOptionWidget( {
							data: String( v ),
							label: String( v )
						} );
						if ( pi.deprecatedvalues && pi.deprecatedvalues.indexOf( v ) >= 0 ) {
							optionWidget.$element.addClass( 'mw-apisandbox-deprecated-value' );
							optionWidget.$label.before(
								$( '<span>' ).addClass( 'mw-apisandbox-flag' ).text( mw.msg( 'api-help-param-deprecated-label' ) )
							);
						}
						if ( pi.internalvalues && pi.internalvalues.indexOf( v ) >= 0 ) {
							optionWidget.$element.addClass( 'mw-apisandbox-internal-value' );
							optionWidget.$label.before(
								$( '<span>' ).addClass( 'mw-apisandbox-flag' ).text( mw.msg( 'api-help-param-internal-label' ) )
							);
						}
						return optionWidget;
					} ).sort( function ( a, b ) {
						return a.label < b.label ? -1 : ( a.label > b.label ? 1 : 0 );
					} );
					if ( Util.apiBool( pi.multi ) ) {
						if ( pi.allspecifier !== undefined ) {
							items.unshift( new OO.ui.MenuOptionWidget( {
								data: pi.allspecifier,
								label: mw.msg( 'apisandbox-multivalue-all-values', pi.allspecifier )
							} ) );
						}

						widget = new OO.ui.MenuTagMultiselectWidget( {
							menu: { items: items },
							$overlay: true
						} );
						widget.paramInfo = pi;
						$.extend( widget, WidgetMethods.tagWidget );
						if ( Util.apiBool( pi.submodules ) ) {
							widget.getSubmodules = WidgetMethods.submoduleWidget.multi;
							widget.on( 'change', ApiSandbox.updateUI );
						}
					} else {
						widget = new OO.ui.DropdownWidget( {
							menu: { items: items },
							$overlay: true
						} );
						widget.paramInfo = pi;
						$.extend( widget, WidgetMethods.dropdownWidget );
						if ( Util.apiBool( pi.submodules ) ) {
							widget.getSubmodules = WidgetMethods.submoduleWidget.single;
							widget.getMenu().on( 'select', ApiSandbox.updateUI );
						}
						if ( pi.deprecatedvalues ) {
							widget.getMenu().on( 'select', function ( item ) {
								this.$element.toggleClass(
									'mw-apisandbox-deprecated-value',
									pi.deprecatedvalues.indexOf( item.data ) >= 0
								);
							}, [], widget );
						}
						if ( pi.internalvalues ) {
							widget.getMenu().on( 'select', function ( item ) {
								this.$element.toggleClass(
									'mw-apisandbox-internal-value',
									pi.internalvalues.indexOf( item.data ) >= 0
								);
							}, [], widget );
						}
					}

					break;
			}

			if ( Util.apiBool( pi.multi ) && multiModeAllowed ) {
				var innerWidget = widget;

				multiModeButton = new OO.ui.ButtonWidget( {
					label: mw.msg( 'apisandbox-add-multi' )
				} );
				var $content = innerWidget.$element.add( multiModeButton.$element );

				widget = new OO.ui.PopupTagMultiselectWidget( {
					allowArbitrary: true,
					allowDuplicates: Util.apiBool( pi.allowsduplicates ),
					$overlay: true,
					popup: {
						classes: [ 'mw-apisandbox-popup' ],
						padded: true,
						$content: $content
					}
				} );
				widget.paramInfo = pi;
				$.extend( widget, WidgetMethods.tagWidget );

				var func = function () {
					if ( !innerWidget.isDisabled() ) {
						innerWidget.apiCheckValid( suppressErrors ).done( function ( ok ) {
							if ( ok ) {
								widget.addTag( innerWidget.getApiValue() );
								innerWidget.setApiValue( undefined );
							}
						} );
						return false;
					}
				};

				if ( multiModeInput ) {
					multiModeInput.on( 'enter', func );
				}
				multiModeButton.on( 'click', func );
			}

			var finalWidget;
			if ( Util.apiBool( pi.required ) || opts.nooptional ) {
				finalWidget = widget;
			} else {
				finalWidget = new OptionalParamWidget( widget );
				finalWidget.paramInfo = pi;
				if ( widget.getSubmodules ) {
					finalWidget.getSubmodules = widget.getSubmodules.bind( widget );
					finalWidget.on( 'disable', function () {
						setTimeout( ApiSandbox.updateUI );
					} );
				}
				if ( widget.getApiValueForTemplates ) {
					finalWidget.getApiValueForTemplates = widget.getApiValueForTemplates.bind( widget );
				}
				finalWidget.setDisabled( true );
			}

			widget.setApiValue( pi.default );

			return finalWidget;
		},

		/**
		 * Parse an HTML string and call Util.fixupHTML()
		 *
		 * @param {string} html HTML to parse
		 * @return {jQuery}
		 */
		parseHTML: function ( html ) {
			var $ret = $( $.parseHTML( html ) );
			return Util.fixupHTML( $ret );
		},

		/**
		 * Parse an i18n message and call Util.fixupHTML()
		 *
		 * @param {string} key Key of message to get
		 * @param {...Mixed} parameters Values for $N replacements
		 * @return {jQuery}
		 */
		parseMsg: function () {
			var $ret = mw.message.apply( mw.message, arguments ).parseDom();
			return Util.fixupHTML( $ret );
		},

		/**
		 * Fix HTML for ApiSandbox display
		 *
		 * Fixes are:
		 * - Add target="_blank" to any links
		 *
		 * @param {jQuery} $html DOM to process
		 * @return {jQuery}
		 */
		fixupHTML: function ( $html ) {
			$html.filter( 'a' ).add( $html.find( 'a' ) )
				.filter( '[href]:not([target])' )
				.attr( 'target', '_blank' );
			return $html;
		},

		/**
		 * Format a request and return a bunch of menu option widgets
		 *
		 * @param {Object} displayParams Query parameters, sanitized for display.
		 * @param {Object} rawParams Query parameters. You should probably use displayParams instead.
		 * @param {string} method HTTP method that must be used for this request: 'get' or 'post'
		 * @param {Object} ajaxOptions Extra options that must be used for this request, in the format
		 *   expected by jQuery#ajax.
		 * @return {OO.ui.MenuOptionWidget[]} Each item's data should be an OO.ui.FieldLayout
		 */
		formatRequest: function ( displayParams, rawParams, method, ajaxOptions ) {
			var jsonLayout, phpLayout,
				apiUrl = new mw.Uri( mw.util.wikiScript( 'api' ) ).toString(),
				items = [
					new OO.ui.MenuOptionWidget( {
						label: Util.parseMsg( 'apisandbox-request-format-url-label' ),
						data: new mw.widgets.CopyTextLayout( {
							label: Util.parseMsg( 'apisandbox-request-url-label' ),
							copyText: apiUrl + '?' + $.param( displayParams )
						} )
					} ),
					new OO.ui.MenuOptionWidget( {
						label: Util.parseMsg( 'apisandbox-request-format-json-label' ),
						data: jsonLayout = new mw.widgets.CopyTextLayout( {
							label: Util.parseMsg( 'apisandbox-request-json-label' ),
							copyText: JSON.stringify( displayParams, null, '\t' ),
							multiline: true,
							textInput: {
								classes: [ 'mw-apisandbox-textInputCode' ],
								autosize: true,
								maxRows: 6
							}
						} ).on( 'toggle', function ( visible ) {
							if ( visible ) {
								// Call updatePosition instead of adjustSize
								// because the latter has weird caching
								// behavior and the former bypasses it.
								jsonLayout.textInput.updatePosition();
							}
						} )
					} ),
					new OO.ui.MenuOptionWidget( {
						label: Util.parseMsg( 'apisandbox-request-format-php-label' ),
						data: phpLayout = new mw.widgets.CopyTextLayout( {
							label: Util.parseMsg( 'apisandbox-request-php-label' ),
							copyText: '[\n' +
								Object.keys( displayParams ).map( function ( param ) {
									// displayParams is a dictionary of strings or numbers
									return '\t' +
										JSON.stringify( param ) +
										' => ' +
										JSON.stringify( displayParams[ param ] ).replace( /\$/g, '\\$' );
								} ).join( ',\n' ) +
								'\n]',
							multiline: true,
							textInput: {
								classes: [ 'mw-apisandbox-textInputCode' ],
								autosize: true,
								maxRows: 6
							}
						} ).on( 'toggle', function ( visible ) {
							if ( visible ) {
								// Call updatePosition instead of adjustSize
								// because the latter has weird caching
								// behavior and the former bypasses it.
								phpLayout.textInput.updatePosition();
							}
						} )
					} )
				];

			mw.hook( 'apisandbox.formatRequest' ).fire( items, displayParams, rawParams, method, ajaxOptions );

			return items;
		},

		/**
		 * Event handler for when formatDropdown's selection changes
		 */
		onFormatDropdownChange: function () {
			var menu = formatDropdown.getMenu(),
				selected = menu.findFirstSelectedItem(),
				selectedField = selected ? selected.getData() : null;

			menu.getItems().forEach( function ( item ) {
				item.getData().toggle( item.getData() === selectedField );
			} );
		}
	};

	var booklet, panel, oldhash;
	/**
	 * Interface to ApiSandbox UI.
	 *
	 * @class mw.special.ApiSandbox
	 * @ignore
	 */
	ApiSandbox = {
		/**
		 * Initialize the UI
		 *
		 * Automatically called on $.ready()
		 */
		init: function () {
			windowManager = new OO.ui.WindowManager();
			$( OO.ui.getTeleportTarget() ).append( windowManager.$element );
			windowManager.addWindows( {
				errorAlert: new OO.ui.MessageDialog()
			} );

			var $toolbar = $( '<div>' )
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

			pages.main = new ApiSandbox.PageLayout( { key: 'main', path: 'main' } );

			// Parse the current hash string
			if ( !ApiSandbox.loadFromHash() ) {
				ApiSandbox.updateUI();
			}

			$( window ).on( 'hashchange', ApiSandbox.loadFromHash );

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
			var fragment = location.hash;

			if ( oldhash === fragment ) {
				return false;
			}
			oldhash = fragment;
			if ( fragment === '' ) {
				return false;
			}

			// I'm surprised this doesn't seem to exist in jQuery or mw.util.
			var params = {};
			fragment = fragment.replace( /\+/g, '%20' );
			var pattern = /([^&=#]+)=?([^&#]*)/g;
			var match;
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
			var addPages = [];

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

				var i = 0;
				while ( addPages.length ) {
					var page = addPages.shift();
					if ( bookletPages[ i ] !== page ) {
						for ( var j = i; j < bookletPages.length; j++ ) {
							if ( bookletPages[ j ].getName() === page.getName() ) {
								bookletPages.splice( j, 1 );
							}
						}
						bookletPages.splice( i, 0, page );
						booklet.addPages( [ page ], i );
					}
					i++;

					if ( page.getSubpages ) {
						var subpages = page.getSubpages();
						// eslint-disable-next-line no-loop-func
						subpages.forEach( function ( subpage, k ) {
							if ( !Object.prototype.hasOwnProperty.call( pages, subpage.key ) ) {
								subpage.indentLevel = page.indentLevel + 1;
								pages[ subpage.key ] = new ApiSandbox.PageLayout( subpage );
							}
							if ( params !== undefined ) {
								pages[ subpage.key ].loadQueryParams( params );
							}
							addPages.splice( k, 0, pages[ subpage.key ] );
							pages[ subpage.key ].apiCheckValid();
						} );
					}
				}

				if ( bookletPages.length > i ) {
					var removePages = bookletPages.splice( i, bookletPages.length - i );
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
		 *
		 * @param {Object} [params] Use this set of params instead of those in the form fields.
		 *   The form fields will be updated to match.
		 */
		sendRequest: function ( params ) {
			var deferreds = [],
				paramsAreForced = !!params,
				displayParams = {},
				ajaxOptions = {},
				method = 'get',
				tokenWidgets = [],
				checkPages = [ pages.main ];

			// Blur any focused widget before submit, because
			// OO.ui.ButtonWidget doesn't take focus itself (T128054)
			var $focus = $( '#mw-apisandbox-ui' ).find( document.activeElement );
			if ( $focus.length ) {
				$focus[ 0 ].blur();
			}

			suppressErrors = false;

			// save widget state in params (or load from it if we are forced)
			if ( paramsAreForced ) {
				ApiSandbox.updateUI( params );
			}
			params = {};
			while ( checkPages.length ) {
				var checkPage = checkPages.shift();
				if ( checkPage.tokenWidget ) {
					tokenWidgets.push( checkPage.tokenWidget );
				}
				deferreds = deferreds.concat( checkPage.apiCheckValid() );
				checkPage.getQueryParams( params, displayParams, ajaxOptions );
				if ( checkPage.paramInfo.mustbeposted !== undefined ) {
					method = 'post';
				}
				var subpages = checkPage.getSubpages();
				// eslint-disable-next-line no-loop-func
				subpages.forEach( function ( subpage ) {
					if ( Object.prototype.hasOwnProperty.call( pages, subpage.key ) ) {
						checkPages.push( pages[ subpage.key ] );
					}
				} );
			}

			if ( !paramsAreForced ) {
				// forced params means we are continuing a query; the base query should be preserved
				baseRequestParams = $.extend( {}, params );
			}

			$.when.apply( $, deferreds ).done( function () {
				// Count how many times `value` occurs in `array`.
				function countValues( value, array ) {
					var count = 0;
					for ( var n = 0; n < array.length; n++ ) {
						if ( array[ n ] === value ) {
							count++;
						}
					}
					return count;
				}

				var errorCount = countValues( false, arguments );
				if ( errorCount > 0 ) {
					var actions = [
						{
							action: 'accept',
							label: OO.ui.msg( 'ooui-dialog-process-dismiss' ),
							flags: 'primary'
						}
					];
					var deferred;
					if ( tokenWidgets.length ) {
						// Check all token widgets' validity separately
						deferred = $.when.apply( $, tokenWidgets.map( function ( w ) {
							return w.apiCheckValid( suppressErrors );
						} ) );

						deferred.done( function () {
							// If only the tokens are invalid, offer to fix them
							var tokenErrorCount = countValues( false, arguments );
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
					deferred.always( function () {
						windowManager.openWindow( 'errorAlert', {
							title: Util.parseMsg( 'apisandbox-submit-invalid-fields-title' ),
							message: Util.parseMsg( 'apisandbox-submit-invalid-fields-message' ),
							actions: actions
						} ).closed.then( function ( data ) {
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

				var query = $.param( displayParams );

				var formatItems = Util.formatRequest( displayParams, params, method, ajaxOptions );

				// Force a 'fm' format with wrappedhtml=1, if available
				if ( params.format !== undefined ) {
					if ( Object.prototype.hasOwnProperty.call( availableFormats, params.format + 'fm' ) ) {
						params.format = params.format + 'fm';
					}
					if ( params.format.slice( -2 ) === 'fm' ) {
						params.wrappedhtml = 1;
					}
				}

				var progressLoading = false;
				var $progressText = $( '<span>' ).text( mw.msg( 'apisandbox-sending-request' ) );
				var progress = new OO.ui.ProgressBarWidget( {
					progress: false
				} );

				var $result = $( '<div>' )
					.append( $progressText, progress.$element );

				var page = resultPage = new OO.ui.PageLayout( '|results|', { expanded: false } );
				page.setupOutlineItem = function () {
					this.outlineItem.setLabel( mw.msg( 'apisandbox-results' ) );
				};

				if ( !formatDropdown ) {
					formatDropdown = new OO.ui.DropdownWidget( {
						menu: { items: [] },
						$overlay: true
					} );
					formatDropdown.getMenu().on( 'select', Util.onFormatDropdownChange );
				}

				var menu = formatDropdown.getMenu();
				var selectedLabel = menu.findSelectedItem() ? menu.findSelectedItem().getLabel() : '';
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
							formatDropdown, {
								label: Util.parseMsg( 'apisandbox-request-selectformat-label' )
							}
						).$element,
						formatItems.map( function ( item ) {
							return item.getData().$element;
						} )
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

				location.href = oldhash = '#' + query;

				api[ method ]( params, $.extend( ajaxOptions, {
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
						var d = $.Deferred();

						if ( code !== 'http' ) {
							// Not really an error, work around mw.Api thinking it is.
							d.resolve( result, jqXHR );
						} else {
							// Just forward it.
							d.reject.apply( d, arguments );
						}
						return d.promise();
					} )
					.then( function ( data, jqXHR ) {
						var ct = jqXHR.getResponseHeader( 'Content-Type' ),
							loginSuppressed = jqXHR.getResponseHeader( 'MediaWiki-Login-Suppressed' ) || 'false';

						$result.empty();
						if ( loginSuppressed !== 'false' ) {
							$( '<div>' )
								.addClass( 'warning' )
								.append( Util.parseMsg( 'apisandbox-results-login-suppressed' ) )
								.appendTo( $result );
						}
						var loadTime;
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
							var clear;
							$result.append(
								$( '<div>' ).append(
									new OO.ui.ButtonWidget( {
										label: mw.msg( 'apisandbox-continue' )
									} ).on( 'click', function () {
										ApiSandbox.sendRequest( $.extend( {}, baseRequestParams, data.continue ) );
									} ).setDisabled( !data.continue ).$element,
									( clear = new OO.ui.ButtonWidget( {
										label: mw.msg( 'apisandbox-continue-clear' )
									} ).on( 'click', function () {
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
							var button = new OO.ui.ButtonWidget( {
								label: mw.msg( 'apisandbox-results-fixtoken' )
							} );
							button.on( 'click', ApiSandbox.fixTokenAndResend )
								.on( 'click', button.setDisabled, [ true ], button )
								.$element.appendTo( $result );
						}
					}, function ( code, data ) {
						var details = 'HTTP error: ' + data.exception;
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
			var ok = true,
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
				var page = checkPages.shift();

				if ( page.tokenWidget ) {
					var key = page.apiModule + page.tokenWidget.paramInfo.name;
					tokenWait[ key ] = page.tokenWidget.fetchToken();
					tokenWait[ key ]
						.done( success.bind( page.tokenWidget, key ) )
						.fail( failure.bind( page.tokenWidget, key ) );
				}

				var subpages = page.getSubpages();
				// eslint-disable-next-line no-loop-func
				subpages.forEach( function ( subpage ) {
					if ( Object.prototype.hasOwnProperty.call( pages, subpage.key ) ) {
						checkPages.push( pages[ subpage.key ] );
					}
				} );
			}

			success( 'dummy', '' );
		},

		/**
		 * Reset validity indicators for all widgets
		 */
		updateValidityIndicators: function () {
			var checkPages = [ pages.main ];

			while ( checkPages.length ) {
				var page = checkPages.shift();
				page.apiCheckValid();
				var subpages = page.getSubpages();
				// eslint-disable-next-line no-loop-func
				subpages.forEach( function ( subpage ) {
					if ( Object.prototype.hasOwnProperty.call( pages, subpage.key ) ) {
						checkPages.push( pages[ subpage.key ] );
					}
				} );
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
		config = $.extend( { prefix: '', expanded: false }, config );
		this.displayText = config.key;
		this.apiModule = config.path;
		this.prefix = config.prefix;
		this.paramInfo = null;
		this.apiIsValid = true;
		this.loadFromQueryParams = null;
		this.widgets = {};
		this.itemsFieldset = null;
		this.deprecatedItemsFieldset = null;
		this.templatedItemsCache = {};
		this.tokenWidget = null;
		this.indentLevel = config.indentLevel ? config.indentLevel : 0;
		ApiSandbox.PageLayout.super.call( this, config.key, config );
		this.loadParamInfo();
	};
	OO.inheritClass( ApiSandbox.PageLayout, OO.ui.PageLayout );
	ApiSandbox.PageLayout.prototype.setupOutlineItem = function () {
		this.outlineItem.setLevel( this.indentLevel );
		this.outlineItem.setLabel( this.displayText );
		this.outlineItem.setIcon( this.apiIsValid || suppressErrors ? null : 'alert' );
		this.outlineItem.setTitle(
			this.apiIsValid || suppressErrors ? '' : mw.message( 'apisandbox-alert-page' ).plain()
		);
	};

	function widgetLabelOnClick() {
		var f = this.getField();
		if ( typeof f.setDisabled === 'function' ) {
			f.setDisabled( false );
		}
		if ( typeof f.focus === 'function' ) {
			f.focus();
		}
	}

	/**
	 * Create a widget and the FieldLayouts it needs
	 *
	 * @private
	 * @param {Object} ppi API paraminfo data for the parameter
	 * @param {string} name API parameter name
	 * @return {Object}
	 * @return {OO.ui.Widget} return.widget
	 * @return {OO.ui.FieldLayout} return.widgetField
	 * @return {OO.ui.FieldLayout} return.helpField
	 */
	ApiSandbox.PageLayout.prototype.makeWidgetFieldLayouts = function ( ppi, name ) {
		var widget = Util.createWidgetForParameter( ppi );
		if ( ppi.tokentype ) {
			this.tokenWidget = widget;
		}
		if ( this.paramInfo.templatedparameters.length ) {
			widget.on( 'change', this.updateTemplatedParameters, [ null ], this );
		}

		var helpLabel = new ParamLabelWidget();

		var $tmp = Util.parseHTML( ppi.description );
		$tmp.filter( 'dl' ).makeCollapsible( {
			collapsed: true
		} ).children( '.mw-collapsible-toggle' ).each( function () {
			var $this = $( this );
			$this.parent().prev( 'p' ).append( $this );
		} );
		helpLabel.addDescription( $tmp );

		if ( ppi.info && ppi.info.length ) {
			for ( var i = 0; i < ppi.info.length; i++ ) {
				helpLabel.addInfo( Util.parseHTML( ppi.info[ i ].text ) );
			}
		}
		var flag = true;
		var count = Infinity;
		var tmp;
		switch ( ppi.type ) {
			case 'namespace':
				flag = false;
				count = mw.config.get( 'wgFormattedNamespaces' ).length;
				break;

			case 'limit':
				tmp = [
					mw.message(
						'paramvalidator-help-type-number-minmax', 1,
						widget.paramInfo.min, widget.paramInfo.apiSandboxMax
					).parse(),
					mw.message( 'apisandbox-param-limit' ).parse()
				];
				helpLabel.addInfo( Util.parseHTML( tmp.join( mw.msg( 'word-separator' ) ) ) );
				break;

			case 'integer':
				tmp = '';
				if ( ppi.min !== undefined ) {
					tmp += 'min';
				}
				if ( ppi.max !== undefined ) {
					tmp += 'max';
				}
				if ( tmp !== '' ) {
					helpLabel.addInfo(
						Util.parseMsg(
							'paramvalidator-help-type-number-' + tmp,
							Util.apiBool( ppi.multi ) ? 2 : 1,
							ppi.min, ppi.max
						)
					);
				}
				break;

			default:
				if ( Array.isArray( ppi.type ) ) {
					flag = false;
					count = ppi.type.length;
				}
				break;
		}
		if ( Util.apiBool( ppi.multi ) ) {
			tmp = [];
			if ( flag && !( widget instanceof OO.ui.TagMultiselectWidget ) &&
				!(
					widget instanceof OptionalParamWidget &&
					widget.widget instanceof OO.ui.TagMultiselectWidget
				)
			) {
				tmp.push( mw.message( 'api-help-param-multi-separate' ).parse() );
			}
			if ( count > ppi.lowlimit ) {
				tmp.push(
					mw.message( 'paramvalidator-help-multi-max', ppi.lowlimit, ppi.highlimit ).parse()
				);
			}
			if ( tmp.length ) {
				helpLabel.addInfo( Util.parseHTML( tmp.join( mw.msg( 'word-separator' ) ) ) );
			}
		}
		if ( 'maxbytes' in ppi ) {
			helpLabel.addInfo( Util.parseMsg( 'paramvalidator-help-type-string-maxbytes', ppi.maxbytes ) );
		}
		if ( 'maxchars' in ppi ) {
			helpLabel.addInfo( Util.parseMsg( 'paramvalidator-help-type-string-maxchars', ppi.maxchars ) );
		}
		if ( ppi.usedTemplateVars && ppi.usedTemplateVars.length ) {
			$tmp = $();
			for ( var j = 0, l = ppi.usedTemplateVars.length; j < l; j++ ) {
				$tmp = $tmp.add( $( '<var>' ).text( ppi.usedTemplateVars[ j ] ) );
				if ( j === l - 2 ) {
					$tmp = $tmp.add( mw.message( 'and' ).parseDom() );
					$tmp = $tmp.add( mw.message( 'word-separator' ).parseDom() );
				} else if ( j !== l - 1 ) {
					$tmp = $tmp.add( mw.message( 'comma-separator' ).parseDom() );
				}
			}
			helpLabel.addInfo(
				Util.parseMsg(
					'apisandbox-templated-parameter-reason',
					ppi.usedTemplateVars.length,
					$tmp
				)
			);
		}

		// TODO: Consder adding more options for the position of helpInline
		// so that this can become part of the widgetField, instead of
		// having to use a separate field.
		var helpField = new OO.ui.FieldLayout(
			helpLabel,
			{
				align: 'top',
				classes: [ 'mw-apisandbox-help-field' ]
			}
		);

		var layoutConfig = {
			align: 'left',
			classes: [ 'mw-apisandbox-widget-field' ],
			label: name
		};

		var widgetField;
		if ( ppi.tokentype ) {
			var button = new OO.ui.ButtonWidget( {
				label: mw.msg( 'apisandbox-fetch-token' )
			} );
			button.on( 'click', widget.fetchToken, [], widget );

			widgetField = new OO.ui.ActionFieldLayout( widget, button, layoutConfig );
		} else {
			widgetField = new OO.ui.FieldLayout( widget, layoutConfig );
		}

		// We need our own click handler on the widget label to
		// turn off the disablement.
		widgetField.$label.on( 'click', widgetLabelOnClick.bind( widgetField ) );

		// Don't grey out the label when the field is disabled,
		// it makes it too hard to read and our "disabled"
		// isn't really disabled.
		widgetField.onFieldDisable( false );
		widgetField.onFieldDisable = function () {};

		widgetField.apiParamIndex = ppi.index;

		return {
			widget: widget,
			widgetField: widgetField,
			helpField: helpField
		};
	};

	/**
	 * Update templated parameters in the page
	 *
	 * @private
	 * @param {Object} [params] Query parameters for initializing the widgets
	 */
	ApiSandbox.PageLayout.prototype.updateTemplatedParameters = function ( params ) {
		var layout = this,
			pi = this.paramInfo,
			prefix = layout.prefix + pi.prefix;

		if ( !pi || !pi.templatedparameters.length ) {
			return;
		}

		if ( !$.isPlainObject( params ) ) {
			params = null;
		}

		var toRemove = {};
		// eslint-disable-next-line no-jquery/no-each-util
		$.each( this.templatedItemsCache, function ( k, el ) {
			if ( el.widget.isElementAttached() ) {
				toRemove[ k ] = el;
			}
		} );

		// This bit duplicates the PHP logic in ApiBase::extractRequestParams().
		// If you update this, see if that needs updating too.
		var toProcess = pi.templatedparameters.map( function ( info ) {
			return {
				name: prefix + info.name,
				info: info,
				vars: $.extend( {}, info.templatevars ),
				usedVars: []
			};
		} );
		var p;
		var doProcess = function ( placeholder, target ) {
			target = prefix + target;

			if ( !layout.widgets[ target ] ) {
				// The target wasn't processed yet, try the next one.
				// If all hit this case, the parameter has no expansions.
				return true;
			}

			if ( !layout.widgets[ target ].getApiValueForTemplates ) {
				// Not a multi-valued widget, so it can't have expansions.
				return false;
			}

			var values = layout.widgets[ target ].getApiValueForTemplates();
			if ( !Array.isArray( values ) || !values.length ) {
				// The target was processed but has no (valid) values.
				// That means it has no expansions.
				return false;
			}

			// Expand this target in the name and all other targets,
			// then requeue if there are more targets left or create the widget
			// and add it to the form if all are done.
			delete p.vars[ placeholder ];
			var usedVars = p.usedVars.concat( [ target ] );
			placeholder = '{' + placeholder + '}';
			var done = $.isEmptyObject( p.vars );
			var index, container;
			if ( done ) {
				container = Util.apiBool( p.info.deprecated ) ? layout.deprecatedItemsFieldset : layout.itemsFieldset;
				var items = container.getItems();
				for ( var i = 0; i < items.length; i++ ) {
					if ( items[ i ].apiParamIndex !== undefined && items[ i ].apiParamIndex > p.info.index ) {
						index = i;
						break;
					}
				}
			}
			values.forEach( function ( value ) {
				if ( !/^[^{}]*$/.exec( value ) ) {
					// Skip values that make invalid parameter names
					return;
				}

				var name = p.name.replace( placeholder, value );
				if ( done ) {
					var tmp;
					if ( layout.templatedItemsCache[ name ] ) {
						tmp = layout.templatedItemsCache[ name ];
					} else {
						tmp = layout.makeWidgetFieldLayouts(
							$.extend( {}, p.info, { usedTemplateVars: usedVars } ), name
						);
						layout.templatedItemsCache[ name ] = tmp;
					}
					delete toRemove[ name ];
					if ( !tmp.widget.isElementAttached() ) {
						layout.widgets[ name ] = tmp.widget;
						container.addItems( [ tmp.widgetField, tmp.helpField ], index );
						if ( index !== undefined ) {
							index += 2;
						}
					}
					if ( params ) {
						tmp.widget.setApiValue( Object.prototype.hasOwnProperty.call( params, name ) ? params[ name ] : undefined );
					}
				} else {
					var newVars = {};
					// eslint-disable-next-line no-jquery/no-each-util
					$.each( p.vars, function ( k, v ) {
						newVars[ k ] = v.replace( placeholder, value );
					} );
					toProcess.push( {
						name: name,
						info: p.info,
						vars: newVars,
						usedVars: usedVars
					} );
				}
			} );
			return false;
		};
		while ( toProcess.length ) {
			p = toProcess.shift();
			// eslint-disable-next-line no-jquery/no-each-util
			$.each( p.vars, doProcess );
		}

		// eslint-disable-next-line no-jquery/no-map-util
		toRemove = $.map( toRemove, function ( el, name ) {
			delete layout.widgets[ name ];
			return [ el.widgetField, el.helpField ];
		} );
		if ( toRemove.length ) {
			this.itemsFieldset.removeItems( toRemove );
			this.deprecatedItemsFieldset.removeItems( toRemove );
		}
	};

	/**
	 * Fetch module information for this page's module, then create UI
	 */
	ApiSandbox.PageLayout.prototype.loadParamInfo = function () {
		var dynamicFieldset, dynamicParamNameWidget,
			layout = this,
			removeDynamicParamWidget = function ( name, item ) {
				dynamicFieldset.removeItems( [ item ] );
				delete layout.widgets[ name ];
			},
			addDynamicParamWidget = function () {
				// Check name is filled in
				var name = dynamicParamNameWidget.getValue().trim();
				if ( name === '' ) {
					dynamicParamNameWidget.focus();
					return;
				}

				if ( layout.widgets[ name ] !== undefined ) {
					windowManager.openWindow( 'errorAlert', {
						title: Util.parseMsg( 'apisandbox-dynamic-error-exists', name ),
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

				var widget = Util.createWidgetForParameter( {
					name: name,
					type: 'string',
					default: ''
				}, {
					nooptional: true
				} );
				var button = new OO.ui.ButtonWidget( {
					icon: 'trash',
					flags: 'destructive'
				} );
				var actionFieldLayout = new OO.ui.ActionFieldLayout(
					widget,
					button,
					{
						label: name,
						align: 'left'
					}
				);
				button.on( 'click', removeDynamicParamWidget, [ name, actionFieldLayout ] );
				layout.widgets[ name ] = widget;
				dynamicFieldset.addItems( [ actionFieldLayout ], dynamicFieldset.getItemCount() - 1 );
				widget.focus();

				dynamicParamNameWidget.setValue( '' );
			};

		this.$element.empty()
			.append(
				document.createTextNode(
					mw.msg( 'apisandbox-loading', this.displayText )
				),
				new OO.ui.ProgressBarWidget( { progress: false } ).$element
			);

		Util.fetchModuleInfo( this.apiModule )
			.done( function ( pi ) {
				var items = [],
					deprecatedItems = [],
					buttons = [],
					filterFmModules = function ( v ) {
						return v.slice( -2 ) !== 'fm' ||
							!Object.prototype.hasOwnProperty.call( availableFormats, v.slice( 0, v.length - 2 ) );
					};

				// This is something of a hack. We always want the 'format' and
				// 'action' parameters from the main module to be specified,
				// and for 'format' we also want to simplify the dropdown since
				// we always send the 'fm' variant.
				if ( layout.apiModule === 'main' ) {
					pi.parameters.forEach( function ( parameter ) {
						if ( parameter.name === 'action' ) {
							parameter.required = true;
							delete parameter.default;
						}
						if ( parameter.name === 'format' ) {
							var types = parameter.type;
							types.forEach( function ( type ) {
								availableFormats[ type ] = true;
							} );
							parameter.type = types.filter( filterFmModules );
							parameter.default = 'json';
							parameter.required = true;
						}
					} );
				}

				// Hide the 'wrappedhtml' parameter on format modules
				// and make formatversion default to the latest version for humans
				// (even though machines get a different default for b/c)
				if ( pi.group === 'format' ) {
					pi.parameters = pi.parameters.filter( function ( p ) {
						return p.name !== 'wrappedhtml';
					} ).map( function ( p ) {
						if ( p.name === 'formatversion' ) {
							// Use the highest numeric value
							p.default = p.type.reduce( function ( prev, current ) {
								return !isNaN( current ) ? Math.max( prev, current ) : prev;
							} );
							p.required = true;
						}
						return p;
					} );
				}

				layout.paramInfo = pi;

				var $desc = Util.parseHTML( pi.description );
				if ( pi.deprecated !== undefined ) {
					$desc = $( '<span>' ).addClass( 'apihelp-deprecated' ).text( mw.msg( 'api-help-param-deprecated' ) )
						.add( document.createTextNode( mw.msg( 'word-separator' ) ) ).add( $desc );
				}
				if ( pi.internal !== undefined ) {
					$desc = $( '<span>' ).addClass( 'apihelp-internal' ).text( mw.msg( 'api-help-param-internal' ) )
						.add( document.createTextNode( mw.msg( 'word-separator' ) ) ).add( $desc );
				}
				items.push( new OO.ui.FieldLayout(
					new OO.ui.Widget( {} ).toggle( false ), {
						align: 'top',
						label: $desc
					}
				) );

				if ( pi.helpurls.length ) {
					buttons.push( new OO.ui.PopupButtonWidget( {
						$overlay: true,
						label: mw.msg( 'apisandbox-helpurls' ),
						icon: 'help',
						popup: {
							width: 'auto',
							padded: true,
							classes: [ 'mw-apisandbox-popup-help' ],
							$content: $( '<ul>' ).append( pi.helpurls.map( function ( link ) {
								return $( '<li>' ).append( $( '<a>' )
									.attr( { href: link, target: '_blank' } )
									.text( link )
								);
							} ) )
						}
					} ) );
				}

				if ( pi.examples.length ) {
					buttons.push( new OO.ui.PopupButtonWidget( {
						$overlay: true,
						label: mw.msg( 'apisandbox-examples' ),
						icon: 'code',
						popup: {
							width: 'auto',
							padded: true,
							classes: [ 'mw-apisandbox-popup-help' ],
							$content: $( '<ul>' ).append( pi.examples.map( function ( example ) {
								var $a = $( '<a>' )
									.attr( 'href', '#' + example.query )
									.html( example.description );
								$a.find( 'a' ).contents().unwrap(); // Can't nest links
								return $( '<li>' ).append( $a );
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
					var prefix = layout.prefix + pi.prefix;
					pi.parameters.forEach( function ( parameter ) {
						var tmpLayout = layout.makeWidgetFieldLayouts( parameter, prefix + parameter.name );
						layout.widgets[ prefix + parameter.name ] = tmpLayout.widget;
						if ( Util.apiBool( parameter.deprecated ) ) {
							deprecatedItems.push( tmpLayout.widgetField, tmpLayout.helpField );
						} else {
							items.push( tmpLayout.widgetField, tmpLayout.helpField );
						}
					} );
				}

				if ( !pi.parameters.length && !Util.apiBool( pi.dynamicparameters ) ) {
					items.push( new OO.ui.FieldLayout(
						new OO.ui.Widget( {} ).toggle( false ), {
							align: 'top',
							label: Util.parseMsg( 'apisandbox-no-parameters' )
						}
					) );
				}

				layout.$element.empty();

				layout.itemsFieldset = new OO.ui.FieldsetLayout( {
					label: layout.displayText
				} );
				layout.itemsFieldset.addItems( items );
				layout.itemsFieldset.$element.appendTo( layout.$element );

				if ( Util.apiBool( pi.dynamicparameters ) ) {
					dynamicFieldset = new OO.ui.FieldsetLayout();
					dynamicParamNameWidget = new OO.ui.TextInputWidget( {
						placeholder: mw.msg( 'apisandbox-dynamic-parameters-add-placeholder' )
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
								flags: 'progressive'
							} ).on( 'click', addDynamicParamWidget ),
							{
								label: mw.msg( 'apisandbox-dynamic-parameters-add-label' ),
								align: 'left'
							}
						)
					] );
					$( '<fieldset>' )
						.append(
							$( '<legend>' ).text( mw.msg( 'apisandbox-dynamic-parameters' ) ),
							dynamicFieldset.$element
						)
						.appendTo( layout.$element );
				}

				layout.deprecatedItemsFieldset = new OO.ui.FieldsetLayout().addItems( deprecatedItems ).toggle( false );
				var $tmp = $( '<fieldset>' )
					.toggle( !layout.deprecatedItemsFieldset.isEmpty() )
					.append(
						$( '<legend>' ).append(
							new OO.ui.ToggleButtonWidget( {
								label: mw.msg( 'apisandbox-deprecated-parameters' )
							} ).on( 'change', layout.deprecatedItemsFieldset.toggle, [], layout.deprecatedItemsFieldset ).$element
						),
						layout.deprecatedItemsFieldset.$element
					)
					.appendTo( layout.$element );
				layout.deprecatedItemsFieldset.on( 'add', function () {
					this.toggle( !layout.deprecatedItemsFieldset.isEmpty() );
				}, [], $tmp );
				layout.deprecatedItemsFieldset.on( 'remove', function () {
					this.toggle( !layout.deprecatedItemsFieldset.isEmpty() );
				}, [], $tmp );

				// Load stored params, if any, then update the booklet if we
				// have subpages (or else just update our valid-indicator).
				var tmp = layout.loadFromQueryParams;
				layout.loadFromQueryParams = null;
				if ( $.isPlainObject( tmp ) ) {
					layout.loadQueryParams( tmp );
				} else {
					layout.updateTemplatedParameters();
				}
				if ( layout.getSubpages().length > 0 ) {
					ApiSandbox.updateUI( tmp );
				} else {
					layout.apiCheckValid();
				}
			} ).fail( function ( code, detail ) {
				layout.$element.empty()
					.append(
						new OO.ui.LabelWidget( {
							label: mw.msg( 'apisandbox-load-error', layout.apiModule, detail ),
							classes: [ 'error' ]
						} ).$element,
						new OO.ui.ButtonWidget( {
							label: mw.msg( 'apisandbox-retry' )
						} ).on( 'click', layout.loadParamInfo, [], layout ).$element
					);
			} );
	};

	/**
	 * Check that all widgets on the page are in a valid state.
	 *
	 * @return {jQuery.Promise[]} One promise for each widget, resolved with `false` if invalid
	 */
	ApiSandbox.PageLayout.prototype.apiCheckValid = function () {
		var layout = this;

		if ( this.paramInfo === null ) {
			return [];
		} else {
			// eslint-disable-next-line no-jquery/no-map-util
			var promises = $.map( this.widgets, function ( widget ) {
				return widget.apiCheckValid( suppressErrors );
			} );
			$.when.apply( $, promises ).then( function () {
				layout.apiIsValid = Array.prototype.indexOf.call( arguments, false ) === -1;
				if ( layout.getOutlineItem() ) {
					layout.getOutlineItem().setIcon( layout.apiIsValid || suppressErrors ? null : 'alert' );
					layout.getOutlineItem().setTitle(
						layout.apiIsValid || suppressErrors ? '' : mw.message( 'apisandbox-alert-page' ).plain()
					);
				}
			} );
			return promises;
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
			// eslint-disable-next-line no-jquery/no-each-util
			$.each( this.widgets, function ( name, widget ) {
				var v = Object.prototype.hasOwnProperty.call( params, name ) ? params[ name ] : undefined;
				widget.setApiValue( v );
			} );
			this.updateTemplatedParameters( params );
		}
	};

	/**
	 * Load query params from form fields
	 *
	 * @param {Object} params Write query parameters into this object
	 * @param {Object} displayParams Write query parameters for display into this object
	 * @param {Object} ajaxOptions Write options for the API request into this object, in the format
	 *   expected by jQuery#ajax.
	 */
	ApiSandbox.PageLayout.prototype.getQueryParams = function ( params, displayParams, ajaxOptions ) {
		// eslint-disable-next-line no-jquery/no-each-util
		$.each( this.widgets, function ( name, widget ) {
			var value = widget.getApiValue();
			if ( value !== undefined ) {
				params[ name ] = value;
				if ( typeof widget.getApiValueForDisplay === 'function' ) {
					value = widget.getApiValueForDisplay();
				}
				displayParams[ name ] = value;
				if ( typeof widget.requiresFormData === 'function' && widget.requiresFormData() ) {
					ajaxOptions.contentType = 'multipart/form-data';
				}
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
		// eslint-disable-next-line no-jquery/no-each-util
		$.each( this.widgets, function ( name, widget ) {
			if ( typeof widget.getSubmodules === 'function' ) {
				widget.getSubmodules().forEach( function ( submodule ) {
					ret.push( {
						key: name + '=' + submodule.value,
						path: submodule.path,
						prefix: widget.paramInfo.submoduleparamprefix || ''
					} );
				} );
			}
		} );
		return ret;
	};

	$( ApiSandbox.init );

	module.exports = ApiSandbox;

}() );
