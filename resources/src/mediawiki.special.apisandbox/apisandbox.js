/* eslint-disable no-restricted-properties */
( function () {
	'use strict';
	var ApiSandbox, Util, WidgetMethods, Validators,
		$content, panel, booklet, oldhash, windowManager,
		formatDropdown,
		api = new mw.Api(),
		bookletPages = [],
		availableFormats = {},
		resultPage = null,
		suppressErrors = true,
		updatingBooklet = false,
		pages = {},
		moduleInfoCache = {},
		baseRequestParams;

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
		this.$cover = config.$cover ||
			$( '<div>' ).addClass( 'mw-apisandbox-optionalWidget-cover' );
		this.checkbox = new OO.ui.CheckboxInputWidget( config.checkbox )
			.on( 'change', this.onCheckboxChange, [], this );

		OptionalWidget[ 'super' ].call( this, config );

		// Forward most methods for convenience
		for ( k in this.widget ) {
			if ( $.isFunction( this.widget[ k ] ) && !this[ k ] ) {
				this[ k ] = this.widget[ k ].bind( this.widget );
			}
		}

		widget.connect( this, {
			change: [ this.emit, 'change' ]
		} );

		this.$cover.on( 'click', this.onOverlayClick.bind( this ) );

		this.$element
			.addClass( 'mw-apisandbox-optionalWidget' )
			.append(
				this.$cover,
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
		this.$cover.toggle( this.isDisabled() );
		this.emit( 'change' );
		return this;
	};

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
				return this.getValidity().then( function () {
					return $.Deferred().resolve( true ).promise();
				}, function () {
					return $.Deferred().resolve( false ).promise();
				} ).done( function ( ok ) {
					ok = ok || suppressErrors;
					that.setIcon( ok ? null : 'alert' );
					that.setIconTitle( ok ? '' : mw.message( 'apisandbox-alert-field' ).plain() );
				} );
			}
		},

		dateTimeInputWidget: {
			getValidity: function () {
				if ( !Util.apiBool( this.paramInfo.required ) || this.getApiValue() !== '' ) {
					return $.Deferred().resolve().promise();
				} else {
					return $.Deferred().reject().promise();
				}
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
				var item = this.getMenu().findSelectedItem();
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

		tagWidget: {
			parseApiValue: function ( v ) {
				if ( v === undefined || v === '' || v === '\x1f' ) {
					return [];
				} else {
					v = String( v );
					if ( v[ 0 ] !== '\x1f' ) {
						return v.split( '|' );
					} else {
						return v.substr( 1 ).split( '\x1f' );
					}
				}
			},
			getApiValueForTemplates: function () {
				return this.isDisabled() ? this.parseApiValue( this.paramInfo[ 'default' ] ) : this.getValue();
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
					v = this.paramInfo[ 'default' ];
				}
				this.setValue( this.parseApiValue( v ) );
			},
			apiCheckValid: function () {
				var ok = true,
					pi = this.paramInfo;

				if ( !suppressErrors ) {
					ok = this.getApiValue() !== undefined && !(
						pi.allspecifier !== undefined &&
						this.getValue().length > 1 &&
						this.getValue().indexOf( pi.allspecifier ) !== -1
					);
				}

				this.setIcon( ok ? null : 'alert' );
				this.setIconTitle( ok ? '' : mw.message( 'apisandbox-alert-field' ).plain() );
				return $.Deferred().resolve( ok ).promise();
			},
			createTagItemWidget: function ( data, label ) {
				var item = OO.ui.TagMultiselectWidget.prototype.createTagItemWidget.call( this, data, label );
				if ( this.paramInfo.deprecatedvalues &&
					this.paramInfo.deprecatedvalues.indexOf( data ) >= 0
				) {
					item.$element.addClass( 'apihelp-deprecated-value' );
				}
				return item;
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
				return v === undefined || v === '' ? [] : String( v ).split( '|' ).map( function ( v ) {
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
			var apiPromise,
				deferred = $.Deferred();

			if ( Object.prototype.hasOwnProperty.call( moduleInfoCache, module ) ) {
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
					if ( Object.prototype.hasOwnProperty.call( pages, subpages[ i ].key ) ) {
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
			var widget, innerWidget, finalWidget, items, $content, func,
				multiModeButton = null,
				multiModeInput = null,
				multiModeAllowed = false;

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
					}
					if ( !Util.apiBool( pi.multi ) ) {
						widget.paramInfo = pi;
						$.extend( widget, WidgetMethods.textInputWidget );
						widget.setValidation( Validators.generic );
					}
					if ( pi.tokentype ) {
						widget.paramInfo = pi;
						$.extend( widget, WidgetMethods.textInputWidget );
						$.extend( widget, WidgetMethods.tokenWidget );
					}
					break;

				case 'text':
					widget = new OO.ui.MultilineTextInputWidget( {
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
					multiModeAllowed = true;
					multiModeInput = widget;
					break;

				case 'integer':
					widget = new OO.ui.NumberInputWidget( {
						required: Util.apiBool( pi.required ),
						isInteger: true
					} );
					widget.setIcon = widget.input.setIcon.bind( widget.input );
					widget.setIconTitle = widget.input.setIconTitle.bind( widget.input );
					widget.getValidity = widget.input.getValidity.bind( widget.input );
					widget.paramInfo = pi;
					$.extend( widget, WidgetMethods.textInputWidget );
					if ( Util.apiBool( pi.enforcerange ) ) {
						widget.setRange( pi.min || -Infinity, pi.max || Infinity );
					}
					multiModeAllowed = true;
					multiModeInput = widget;
					break;

				case 'limit':
					widget = new OO.ui.TextInputWidget( {
						required: Util.apiBool( pi.required )
					} );
					widget.setValidation( function ( value ) {
						var n, pi = this.paramInfo;

						if ( value === 'max' ) {
							return true;
						} else {
							n = +value;
							return !isNaN( n ) && isFinite( n ) &&
								Math.floor( n ) === n &&
								n >= pi.min && n <= pi.apiSandboxMax;
						}
					} );
					pi.min = pi.min || 0;
					pi.apiSandboxMax = mw.config.get( 'apihighlimits' ) ? pi.highmax : pi.max;
					widget.paramInfo = pi;
					$.extend( widget, WidgetMethods.textInputWidget );
					multiModeAllowed = true;
					multiModeInput = widget;
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
					multiModeAllowed = true;
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
						if ( pi.allspecifier !== undefined ) {
							items.unshift( new OO.ui.MenuOptionWidget( {
								data: pi.allspecifier,
								label: mw.message( 'apisandbox-multivalue-all-namespaces', pi.allspecifier ).text()
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

				default:
					if ( !Array.isArray( pi.type ) ) {
						throw new Error( 'Unknown parameter type ' + pi.type );
					}

					items = pi.type.map( function ( v ) {
						var config = {
							data: String( v ),
							label: String( v ),
							classes: []
						};
						if ( pi.deprecatedvalues && pi.deprecatedvalues.indexOf( v ) >= 0 ) {
							config.classes.push( 'apihelp-deprecated-value' );
						}
						return new OO.ui.MenuOptionWidget( config );
					} );
					if ( Util.apiBool( pi.multi ) ) {
						if ( pi.allspecifier !== undefined ) {
							items.unshift( new OO.ui.MenuOptionWidget( {
								data: pi.allspecifier,
								label: mw.message( 'apisandbox-multivalue-all-values', pi.allspecifier ).text()
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
									'apihelp-deprecated-value',
									pi.deprecatedvalues.indexOf( item.data ) >= 0
								);
							}, [], widget );
						}
					}

					break;
			}

			if ( Util.apiBool( pi.multi ) && multiModeAllowed ) {
				innerWidget = widget;

				multiModeButton = new OO.ui.ButtonWidget( {
					label: mw.message( 'apisandbox-add-multi' ).text()
				} );
				$content = innerWidget.$element.add( multiModeButton.$element );

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

				func = function () {
					if ( !innerWidget.isDisabled() ) {
						innerWidget.apiCheckValid().done( function ( ok ) {
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
				if ( widget.getApiValueForTemplates ) {
					finalWidget.getApiValueForTemplates = widget.getApiValueForTemplates.bind( widget );
				}
				finalWidget.setDisabled( true );
			}

			widget.setApiValue( pi[ 'default' ] );

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
		 * @return {OO.ui.MenuOptionWidget[]} Each item's data should be an OO.ui.FieldLayout
		 */
		formatRequest: function ( displayParams, rawParams ) {
			var jsonInput,
				items = [
					new OO.ui.MenuOptionWidget( {
						label: Util.parseMsg( 'apisandbox-request-format-url-label' ),
						data: new OO.ui.FieldLayout(
							new OO.ui.TextInputWidget( {
								readOnly: true,
								value: mw.util.wikiScript( 'api' ) + '?' + $.param( displayParams )
							} ), {
								label: Util.parseMsg( 'apisandbox-request-url-label' )
							}
						)
					} ),
					new OO.ui.MenuOptionWidget( {
						label: Util.parseMsg( 'apisandbox-request-format-json-label' ),
						data: new OO.ui.FieldLayout(
							jsonInput = new OO.ui.MultilineTextInputWidget( {
								classes: [ 'mw-apisandbox-textInputCode' ],
								readOnly: true,
								autosize: true,
								maxRows: 6,
								value: JSON.stringify( displayParams, null, '\t' )
							} ), {
								label: Util.parseMsg( 'apisandbox-request-json-label' )
							}
						).on( 'toggle', function ( visible ) {
							if ( visible ) {
								// Call updatePosition instead of adjustSize
								// because the latter has weird caching
								// behavior and the former bypasses it.
								jsonInput.updatePosition();
							}
						} )
					} )
				];

			mw.hook( 'apisandbox.formatRequest' ).fire( items, displayParams, rawParams );

			return items;
		},

		/**
		 * Event handler for when formatDropdown's selection changes
		 */
		onFormatDropdownChange: function () {
			var i,
				menu = formatDropdown.getMenu(),
				items = menu.getItems(),
				selectedField = menu.findSelectedItem() ? menu.findSelectedItem().getData() : null;

			for ( i = 0; i < items.length; i++ ) {
				items[ i ].getData().toggle( items[ i ].getData() === selectedField );
			}
		}
	};

	/**
	* Interface to ApiSandbox UI
	*
	* @class mw.special.ApiSandbox
	*/
	ApiSandbox = {
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

			$toolbar = $( '<div>' )
				.addClass( 'mw-apisandbox-toolbar' )
				.append(
					new OO.ui.ButtonWidget( {
						label: mw.message( 'apisandbox-submit' ).text(),
						flags: [ 'primary', 'progressive' ]
					} ).on( 'click', ApiSandbox.sendRequest ).$element,
					new OO.ui.ButtonWidget( {
						label: mw.message( 'apisandbox-reset' ).text(),
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
				expanded: false,
				framed: true
			} );

			pages.main = new ApiSandbox.PageLayout( { key: 'main', path: 'main' } );

			// Parse the current hash string
			if ( !ApiSandbox.loadFromHash() ) {
				ApiSandbox.updateUI();
			}

			$( window ).on( 'hashchange', ApiSandbox.loadFromHash );

			$content
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
							if ( !Object.prototype.hasOwnProperty.call( pages, subpages[ j ].key ) ) {
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
		 *
		 * @param {Object} [params] Use this set of params instead of those in the form fields.
		 *   The form fields will be updated to match.
		 */
		sendRequest: function ( params ) {
			var page, subpages, i, query, $result, $focus,
				progress, $progressText, progressLoading,
				deferreds = [],
				paramsAreForced = !!params,
				displayParams = {},
				tokenWidgets = [],
				checkPages = [ pages.main ];

			// Blur any focused widget before submit, because
			// OO.ui.ButtonWidget doesn't take focus itself (T128054)
			$focus = $( '#mw-apisandbox-ui' ).find( document.activeElement );
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
				page = checkPages.shift();
				if ( page.tokenWidget ) {
					tokenWidgets.push( page.tokenWidget );
				}
				deferreds = deferreds.concat( page.apiCheckValid() );
				page.getQueryParams( params, displayParams );
				subpages = page.getSubpages();
				for ( i = 0; i < subpages.length; i++ ) {
					if ( Object.prototype.hasOwnProperty.call( pages, subpages[ i ].key ) ) {
						checkPages.push( pages[ subpages[ i ].key ] );
					}
				}
			}

			if ( !paramsAreForced ) {
				// forced params means we are continuing a query; the base query should be preserved
				baseRequestParams = $.extend( {}, params );
			}

			$.when.apply( $, deferreds ).done( function () {
				var formatItems, menu, selectedLabel, deferred, actions, errorCount;

				// Count how many times `value` occurs in `array`.
				function countValues( value, array ) {
					var count, i;
					count = 0;
					for ( i = 0; i < array.length; i++ ) {
						if ( array[ i ] === value ) {
							count++;
						}
					}
					return count;
				}

				errorCount = countValues( false, arguments );
				if ( errorCount > 0 ) {
					actions = [
						{
							action: 'accept',
							label: OO.ui.msg( 'ooui-dialog-process-dismiss' ),
							flags: 'primary'
						}
					];
					if ( tokenWidgets.length ) {
						// Check all token widgets' validity separately
						deferred = $.when.apply( $, tokenWidgets.map( function ( w ) {
							return w.apiCheckValid();
						} ) );

						deferred.done( function () {
							// If only the tokens are invalid, offer to fix them
							var tokenErrorCount = countValues( false, arguments );
							if ( tokenErrorCount === errorCount ) {
								delete actions[ 0 ].flags;
								actions.push( {
									action: 'fix',
									label: mw.message( 'apisandbox-results-fixtoken' ).text(),
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

				query = $.param( displayParams );

				formatItems = Util.formatRequest( displayParams, params );

				// Force a 'fm' format with wrappedhtml=1, if available
				if ( params.format !== undefined ) {
					if ( Object.prototype.hasOwnProperty.call( availableFormats, params.format + 'fm' ) ) {
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

				resultPage = page = new OO.ui.PageLayout( '|results|', { expanded: false } );
				page.setupOutlineItem = function () {
					this.outlineItem.setLabel( mw.message( 'apisandbox-results' ).text() );
				};

				if ( !formatDropdown ) {
					formatDropdown = new OO.ui.DropdownWidget( {
						menu: { items: [] },
						$overlay: true
					} );
					formatDropdown.getMenu().on( 'select', Util.onFormatDropdownChange );
				}

				menu = formatDropdown.getMenu();
				selectedLabel = menu.findSelectedItem() ? menu.findSelectedItem().getLabel() : '';
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
						} ),
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
					.catch( function ( code, data, result, jqXHR ) {
						var deferred = $.Deferred();

						if ( code !== 'http' ) {
							// Not really an error, work around mw.Api thinking it is.
							deferred.resolve( result, jqXHR );
						} else {
							// Just forward it.
							deferred.reject.apply( deferred, arguments );
						}
						return deferred.promise();
					} )
					.then( function ( data, jqXHR ) {
						var m, loadTime, button, clear,
							ct = jqXHR.getResponseHeader( 'Content-Type' ),
							loginSuppressed = jqXHR.getResponseHeader( 'MediaWiki-Login-Suppressed' ) || 'false';

						$result.empty();
						if ( loginSuppressed !== 'false' ) {
							$( '<div>' )
								.addClass( 'warning' )
								.append( Util.parseMsg( 'apisandbox-results-login-suppressed' ) )
								.appendTo( $result );
						}
						if ( /^text\/mediawiki-api-prettyprint-wrapped(?:;|$)/.test( ct ) ) {
							data = JSON.parse( data );
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
						if ( paramsAreForced || data[ 'continue' ] ) {
							$result.append(
								$( '<div>' ).append(
									new OO.ui.ButtonWidget( {
										label: mw.message( 'apisandbox-continue' ).text()
									} ).on( 'click', function () {
										ApiSandbox.sendRequest( $.extend( {}, baseRequestParams, data[ 'continue' ] ) );
									} ).setDisabled( !data[ 'continue' ] ).$element,
									( clear = new OO.ui.ButtonWidget( {
										label: mw.message( 'apisandbox-continue-clear' ).text()
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
					}, function ( code, data ) {
						var details = 'HTTP error: ' + data.exception;
						$result.empty()
							.append(
								new OO.ui.LabelWidget( {
									label: mw.message( 'apisandbox-results-error', details ).text(),
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
					tokenWait[ k ] = page.tokenWidget.fetchToken();
					tokenWait[ k ]
						.done( success.bind( page.tokenWidget, k ) )
						.fail( failure.bind( page.tokenWidget, k ) );
				}

				subpages = page.getSubpages();
				for ( i = 0; i < subpages.length; i++ ) {
					if ( Object.prototype.hasOwnProperty.call( pages, subpages[ i ].key ) ) {
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
					if ( Object.prototype.hasOwnProperty.call( pages, subpages[ i ].key ) ) {
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

	function widgetLabelOnClick() {
		var f = this.getField();
		if ( $.isFunction( f.setDisabled ) ) {
			f.setDisabled( false );
		}
		if ( $.isFunction( f.focus ) ) {
			f.focus();
		}
	}

	/**
	 * Create a widget and the FieldLayouts it needs
	 * @private
	 * @param {Object} ppi API paraminfo data for the parameter
	 * @param {string} name API parameter name
	 * @return {Object}
	 * @return {OO.ui.Widget} return.widget
	 * @return {OO.ui.FieldLayout} return.widgetField
	 * @return {OO.ui.FieldLayout} return.helpField
	 */
	ApiSandbox.PageLayout.prototype.makeWidgetFieldLayouts = function ( ppi, name ) {
		var j, l, widget, descriptionContainer, tmp, flag, count, button, widgetField, helpField, layoutConfig;

		widget = Util.createWidgetForParameter( ppi );
		if ( ppi.tokentype ) {
			this.tokenWidget = widget;
		}
		if ( this.paramInfo.templatedparameters.length ) {
			widget.on( 'change', this.updateTemplatedParameters, [ null ], this );
		}

		descriptionContainer = $( '<div>' );

		tmp = Util.parseHTML( ppi.description );
		tmp.filter( 'dl' ).makeCollapsible( {
			collapsed: true
		} ).children( '.mw-collapsible-toggle' ).each( function () {
			var $this = $( this );
			$this.parent().prev( 'p' ).append( $this );
		} );
		descriptionContainer.append( $( '<div>' ).addClass( 'description' ).append( tmp ) );

		if ( ppi.info && ppi.info.length ) {
			for ( j = 0; j < ppi.info.length; j++ ) {
				descriptionContainer.append( $( '<div>' )
					.addClass( 'info' )
					.append( Util.parseHTML( ppi.info[ j ] ) )
				);
			}
		}
		flag = true;
		count = Infinity;
		switch ( ppi.type ) {
			case 'namespace':
				flag = false;
				count = mw.config.get( 'wgFormattedNamespaces' ).length;
				break;

			case 'limit':
				if ( ppi.highmax !== undefined ) {
					descriptionContainer.append( $( '<div>' )
						.addClass( 'info' )
						.append(
							Util.parseMsg(
								'api-help-param-limit2', ppi.max, ppi.highmax
							),
							' ',
							Util.parseMsg( 'apisandbox-param-limit' )
						)
					);
				} else {
					descriptionContainer.append( $( '<div>' )
						.addClass( 'info' )
						.append(
							Util.parseMsg( 'api-help-param-limit', ppi.max ),
							' ',
							Util.parseMsg( 'apisandbox-param-limit' )
						)
					);
				}
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
					descriptionContainer.append( $( '<div>' )
						.addClass( 'info' )
						.append( Util.parseMsg(
							'api-help-param-integer-' + tmp,
							Util.apiBool( ppi.multi ) ? 2 : 1,
							ppi.min, ppi.max
						) )
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
					widget instanceof OptionalWidget &&
					widget.widget instanceof OO.ui.TagMultiselectWidget
				)
			) {
				tmp.push( mw.message( 'api-help-param-multi-separate' ).parse() );
			}
			if ( count > ppi.lowlimit ) {
				tmp.push(
					mw.message( 'api-help-param-multi-max', ppi.lowlimit, ppi.highlimit ).parse()
				);
			}
			if ( tmp.length ) {
				descriptionContainer.append( $( '<div>' )
					.addClass( 'info' )
					.append( Util.parseHTML( tmp.join( ' ' ) ) )
				);
			}
		}
		if ( 'maxbytes' in ppi ) {
			descriptionContainer.append( $( '<div>' )
				.addClass( 'info' )
				.append( Util.parseMsg( 'api-help-param-maxbytes', ppi.maxbytes ) )
			);
		}
		if ( 'maxchars' in ppi ) {
			descriptionContainer.append( $( '<div>' )
				.addClass( 'info' )
				.append( Util.parseMsg( 'api-help-param-maxchars', ppi.maxchars ) )
			);
		}
		if ( ppi.usedTemplateVars && ppi.usedTemplateVars.length ) {
			tmp = $();
			for ( j = 0, l = ppi.usedTemplateVars.length; j < l; j++ ) {
				tmp = tmp.add( $( '<var>' ).text( ppi.usedTemplateVars[ j ] ) );
				if ( j === l - 2 ) {
					tmp = tmp.add( mw.message( 'and' ).parseDom() );
					tmp = tmp.add( mw.message( 'word-separator' ).parseDom() );
				} else if ( j !== l - 1 ) {
					tmp = tmp.add( mw.message( 'comma-separator' ).parseDom() );
				}
			}
			descriptionContainer.append( $( '<div>' )
				.addClass( 'info' )
				.append( Util.parseMsg(
					'apisandbox-templated-parameter-reason',
					ppi.usedTemplateVars.length,
					tmp
				) )
			);
		}

		helpField = new OO.ui.FieldLayout(
			new OO.ui.Widget( {
				$content: '\xa0',
				classes: [ 'mw-apisandbox-spacer' ]
			} ), {
				align: 'inline',
				classes: [ 'mw-apisandbox-help-field' ],
				label: descriptionContainer
			}
		);

		layoutConfig = {
			align: 'left',
			classes: [ 'mw-apisandbox-widget-field' ],
			label: name
		};

		if ( ppi.tokentype ) {
			button = new OO.ui.ButtonWidget( {
				label: mw.message( 'apisandbox-fetch-token' ).text()
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
		widgetField.onFieldDisable = $.noop;

		widgetField.apiParamIndex = ppi.index;

		return {
			widget: widget,
			widgetField: widgetField,
			helpField: helpField
		};
	};

	/**
	 * Update templated parameters in the page
	 * @private
	 * @param {Object} [params] Query parameters for initializing the widgets
	 */
	ApiSandbox.PageLayout.prototype.updateTemplatedParameters = function ( params ) {
		var p, toProcess, doProcess, tmp, toRemove,
			that = this,
			pi = this.paramInfo,
			prefix = that.prefix + pi.prefix;

		if ( !pi || !pi.templatedparameters.length ) {
			return;
		}

		if ( !$.isPlainObject( params ) ) {
			params = null;
		}

		toRemove = {};
		// eslint-disable-next-line no-restricted-properties
		$.each( this.templatedItemsCache, function ( k, el ) {
			if ( el.widget.isElementAttached() ) {
				toRemove[ k ] = el;
			}
		} );

		// This bit duplicates the PHP logic in ApiBase::extractRequestParams().
		// If you update this, see if that needs updating too.
		toProcess = pi.templatedparameters.map( function ( p ) {
			return {
				name: prefix + p.name,
				info: p,
				vars: $.extend( {}, p.templatevars ),
				usedVars: []
			};
		} );
		doProcess = function ( placeholder, target ) {
			var values, container, index, usedVars, done;

			target = prefix + target;

			if ( !that.widgets[ target ] ) {
				// The target wasn't processed yet, try the next one.
				// If all hit this case, the parameter has no expansions.
				return true;
			}

			if ( !that.widgets[ target ].getApiValueForTemplates ) {
				// Not a multi-valued widget, so it can't have expansions.
				return false;
			}

			values = that.widgets[ target ].getApiValueForTemplates();
			if ( !Array.isArray( values ) || !values.length ) {
				// The target was processed but has no (valid) values.
				// That means it has no expansions.
				return false;
			}

			// Expand this target in the name and all other targets,
			// then requeue if there are more targets left or create the widget
			// and add it to the form if all are done.
			delete p.vars[ placeholder ];
			usedVars = p.usedVars.concat( [ target ] );
			placeholder = '{' + placeholder + '}';
			done = $.isEmptyObject( p.vars );
			if ( done ) {
				container = Util.apiBool( p.info.deprecated ) ? that.deprecatedItemsFieldset : that.itemsFieldset;
				index = container.getItems().findIndex( function ( el ) {
					return el.apiParamIndex !== undefined && el.apiParamIndex > p.info.index;
				} );
				if ( index < 0 ) {
					index = undefined;
				}
			}
			values.forEach( function ( value ) {
				var name, newVars;

				if ( !/^[^{}]*$/.exec( value ) ) {
					// Skip values that make invalid parameter names
					return;
				}

				name = p.name.replace( placeholder, value );
				if ( done ) {
					if ( that.templatedItemsCache[ name ] ) {
						tmp = that.templatedItemsCache[ name ];
					} else {
						tmp = that.makeWidgetFieldLayouts(
							$.extend( {}, p.info, { usedTemplateVars: usedVars } ), name
						);
						that.templatedItemsCache[ name ] = tmp;
					}
					delete toRemove[ name ];
					if ( !tmp.widget.isElementAttached() ) {
						that.widgets[ name ] = tmp.widget;
						container.addItems( [ tmp.widgetField, tmp.helpField ], index );
						if ( index !== undefined ) {
							index += 2;
						}
					}
					if ( params ) {
						tmp.widget.setApiValue( Object.prototype.hasOwnProperty.call( params, name ) ? params[ name ] : undefined );
					}
				} else {
					newVars = {};
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
			$.each( p.vars, doProcess );
		}

		toRemove = $.map( toRemove, function ( el, name ) {
			delete that.widgets[ name ];
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

				widget = Util.createWidgetForParameter( {
					name: name,
					type: 'string',
					'default': ''
				}, {
					nooptional: true
				} );
				button = new OO.ui.ButtonWidget( {
					icon: 'trash',
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
				var prefix, i, j, tmp,
					items = [],
					deprecatedItems = [],
					buttons = [],
					filterFmModules = function ( v ) {
						return v.substr( -2 ) !== 'fm' ||
							!Object.prototype.hasOwnProperty.call( availableFormats, v.substr( 0, v.length - 2 ) );
					};

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
							pi.parameters[ i ].type = tmp.filter( filterFmModules );
							pi.parameters[ i ][ 'default' ] = 'json';
							pi.parameters[ i ].required = true;
						}
					}
				}

				// Hide the 'wrappedhtml' parameter on format modules
				if ( pi.group === 'format' ) {
					pi.parameters = pi.parameters.filter( function ( p ) {
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
						$overlay: true,
						label: mw.message( 'apisandbox-helpurls' ).text(),
						icon: 'help',
						popup: {
							width: 'auto',
							padded: true,
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
						label: mw.message( 'apisandbox-examples' ).text(),
						icon: 'code',
						popup: {
							width: 'auto',
							padded: true,
							$content: $( '<ul>' ).append( pi.examples.map( function ( example ) {
								var a = $( '<a>' )
									.attr( 'href', '#' + example.query )
									.html( example.description );
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
						tmp = that.makeWidgetFieldLayouts( pi.parameters[ i ], prefix + pi.parameters[ i ].name );
						that.widgets[ prefix + pi.parameters[ i ].name ] = tmp.widget;
						if ( Util.apiBool( pi.parameters[ i ].deprecated ) ) {
							deprecatedItems.push( tmp.widgetField, tmp.helpField );
						} else {
							items.push( tmp.widgetField, tmp.helpField );
						}
					}
				}

				if ( !pi.parameters.length && !Util.apiBool( pi.dynamicparameters ) ) {
					items.push( new OO.ui.FieldLayout(
						new OO.ui.Widget( {} ).toggle( false ), {
							align: 'top',
							label: Util.parseMsg( 'apisandbox-no-parameters' )
						}
					) );
				}

				that.$element.empty();

				that.itemsFieldset = new OO.ui.FieldsetLayout( {
					label: that.displayText
				} );
				that.itemsFieldset.addItems( items );
				that.itemsFieldset.$element.appendTo( that.$element );

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
								flags: 'progressive'
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

				that.deprecatedItemsFieldset = new OO.ui.FieldsetLayout().addItems( deprecatedItems ).toggle( false );
				tmp = $( '<fieldset>' )
					.toggle( !that.deprecatedItemsFieldset.isEmpty() )
					.append(
						$( '<legend>' ).append(
							new OO.ui.ToggleButtonWidget( {
								label: mw.message( 'apisandbox-deprecated-parameters' ).text()
							} ).on( 'change', that.deprecatedItemsFieldset.toggle, [], that.deprecatedItemsFieldset ).$element
						),
						that.deprecatedItemsFieldset.$element
					)
					.appendTo( that.$element );
				that.deprecatedItemsFieldset.on( 'add', function () {
					this.toggle( !that.deprecatedItemsFieldset.isEmpty() );
				}, [], tmp );
				that.deprecatedItemsFieldset.on( 'remove', function () {
					this.toggle( !that.deprecatedItemsFieldset.isEmpty() );
				}, [], tmp );

				// Load stored params, if any, then update the booklet if we
				// have subpages (or else just update our valid-indicator).
				tmp = that.loadFromQueryParams;
				that.loadFromQueryParams = null;
				if ( $.isPlainObject( tmp ) ) {
					that.loadQueryParams( tmp );
				} else {
					that.updateTemplatedParameters();
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
	 * @return {jQuery.Promise[]} One promise for each widget, resolved with `false` if invalid
	 */
	ApiSandbox.PageLayout.prototype.apiCheckValid = function () {
		var promises, that = this;

		if ( this.paramInfo === null ) {
			return [];
		} else {
			promises = $.map( this.widgets, function ( widget ) {
				return widget.apiCheckValid();
			} );
			$.when.apply( $, promises ).then( function () {
				that.apiIsValid = Array.prototype.indexOf.call( arguments, false ) === -1;
				if ( that.getOutlineItem() ) {
					that.getOutlineItem().setIcon( that.apiIsValid || suppressErrors ? null : 'alert' );
					that.getOutlineItem().setIconTitle(
						that.apiIsValid || suppressErrors ? '' : mw.message( 'apisandbox-alert-page' ).plain()
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

	$( ApiSandbox.init );

	module.exports = ApiSandbox;

}() );
