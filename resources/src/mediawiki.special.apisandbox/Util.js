let Util = null;

const api = new mw.Api(),
	moduleInfoCache = {},
	ApiSandbox = require( './ApiSandbox.js' ),
	OptionalParamWidget = require( './OptionalParamWidget.js' ),
	BooleanToggleSwitchParamWidget = require( './BooleanToggleSwitchParamWidget.js' ),
	DateTimeParamWidget = require( './DateTimeParamWidget.js' ),
	LimitParamWidget = require( './LimitParamWidget.js' ),
	PasswordParamWidget = require( './PasswordParamWidget.js' ),
	UploadSelectFileParamWidget = require( './UploadSelectFileParamWidget.js' );

const WidgetMethods = {
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
			return this.getValidity().then(
				() => $.Deferred().resolve( true ).promise(),
				() => $.Deferred().resolve( false ).promise()
			).done( ( ok ) => {
				ok = ok || shouldSuppressErrors;
				this.setIcon( ok ? null : 'alert' );
				this.setTitle( ok ? '' : mw.message( 'apisandbox-alert-field' ).plain() );
			} );
		}
	},

	tokenWidget: {
		alertTokenError: function ( code, error ) {
			ApiSandbox.windowManager.openWindow( 'errorAlert', {
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
			const selected = this.getMenu().findFirstSelectedItem();
			return selected ? selected.getData() : undefined;
		},
		setApiValue: function ( v ) {
			if ( v === undefined ) {
				v = this.paramInfo.default;
			}
			const menu = this.getMenu();
			if ( v === undefined ) {
				menu.selectItem();
			} else {
				menu.selectItemByData( String( v ) );
			}
		},
		apiCheckValid: function ( shouldSuppressErrors ) {
			const ok = this.getApiValue() !== undefined || shouldSuppressErrors;
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
			const items = this.getValue();
			if ( !items.join( '' ).includes( '|' ) ) {
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
			let ok = true;
			if ( !shouldSuppressErrors ) {
				const pi = this.paramInfo;
				ok = this.getApiValue() !== undefined && !(
					pi.allspecifier !== undefined &&
					this.getValue().length > 1 &&
					this.getValue().includes( pi.allspecifier )
				);
			}

			this.setIcon( ok ? null : 'alert' );
			this.setTitle( ok ? '' : mw.message( 'apisandbox-alert-field' ).plain() );
			return $.Deferred().resolve( ok ).promise();
		},
		createTagItemWidget: function ( data, label ) {
			const item = OO.ui.TagMultiselectWidget.prototype.createTagItemWidget.call( this, data, label );
			if ( this.paramInfo.deprecatedvalues &&
				this.paramInfo.deprecatedvalues.includes( data )
			) {
				item.$element.addClass( 'mw-apisandbox-deprecated-value' );
			}
			if ( this.paramInfo.internalvalues &&
				this.paramInfo.internalvalues.includes( data )
			) {
				item.$element.addClass( 'mw-apisandbox-internal-value' );
			}
			return item;
		}
	},

	submoduleWidget: {
		single: function () {
			const v = this.isDisabled() ? this.paramInfo.default : this.getApiValue();
			return v === undefined ? [] : [ { value: v, path: this.paramInfo.submodules[ v ] } ];
		},
		multi: function () {
			const map = this.paramInfo.submodules,
				v = this.isDisabled() ? this.paramInfo.default : this.getApiValue();
			return v === undefined || v === '' ?
				[] :
				String( v ).split( '|' ).map( ( val ) => ( { value: val, path: map[ val ] } ) );
		}
	}
};

const Validators = {
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
		const deferred = $.Deferred();

		if ( Object.prototype.hasOwnProperty.call( moduleInfoCache, module ) ) {
			return deferred
				.resolve( moduleInfoCache[ module ] )
				.promise( { abort: () => {} } );
		} else {
			const apiPromise = api.post( {
				action: 'paraminfo',
				modules: module,
				helpformat: 'html',
				uselang: mw.config.get( 'wgUserLanguage' )
			} ).done( ( data ) => {
				if ( data.warnings && data.warnings.paraminfo ) {
					deferred.reject( '???', data.warnings.paraminfo[ '*' ] );
					return;
				}

				const info = data.paraminfo.modules;
				if ( !info || info.length !== 1 || info[ 0 ].path !== module ) {
					deferred.reject( '???', 'No module data returned' );
					return;
				}

				moduleInfoCache[ module ] = info[ 0 ];
				deferred.resolve( info[ 0 ] );
			} ).fail( ( code, details ) => {
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
		const checkPages = [ ApiSandbox.pages.main ];

		while ( checkPages.length ) {
			const page = checkPages.shift();

			if ( page.tokenWidget ) {
				api.badToken( page.tokenWidget.paramInfo.tokentype );
			}

			const subpages = page.getSubpages();
			subpages.forEach( ( subpage ) => {
				if ( Object.prototype.hasOwnProperty.call( ApiSandbox.pages, subpage.key ) ) {
					checkPages.push( ApiSandbox.pages[ subpage.key ] );
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
		let multiModeButton = null,
			multiModeInput = null,
			multiModeAllowed = false;

		opts = opts || {};

		let widget, items;
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
					Object.assign( widget, WidgetMethods.textInputWidget );
					widget.setValidation( Validators.generic );
					Object.assign( widget, WidgetMethods.tokenWidget );
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
					Object.assign( widget, WidgetMethods.tagWidget );
				} else {
					widget = new OO.ui.TextInputWidget( {
						required: Util.apiBool( pi.required )
					} );
					widget.paramInfo = pi;
					Object.assign( widget, WidgetMethods.textInputWidget );
					widget.setValidation( Validators.generic );
				}
				break;

			case 'raw':
			case 'text':
				widget = new OO.ui.MultilineTextInputWidget( {
					required: Util.apiBool( pi.required )
				} );
				widget.paramInfo = pi;
				Object.assign( widget, WidgetMethods.textInputWidget );
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
				Object.assign( widget, WidgetMethods.textInputWidget );
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
				items = $.map( mw.config.get( 'wgFormattedNamespaces' ), ( name, ns ) => {
					if ( ns === '0' ) {
						name = mw.msg( 'blanknamespace' );
					}
					return new OO.ui.MenuOptionWidget( { data: ns, label: name } );
				} ).sort( ( a, b ) => a.data - b.data );
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
					Object.assign( widget, WidgetMethods.tagWidget );
				} else {
					widget = new OO.ui.DropdownWidget( {
						menu: { items: items },
						$overlay: true
					} );
					widget.paramInfo = pi;
					Object.assign( widget, WidgetMethods.dropdownWidget );
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
					Object.assign( widget, WidgetMethods.tagWidget );
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
					Object.assign( widget, WidgetMethods.textInputWidget );
				}
				break;

			default:
				if ( !Array.isArray( pi.type ) ) {
					throw new Error( 'Unknown parameter type ' + pi.type );
				}

				items = pi.type.map( ( v ) => {
					const optionWidget = new OO.ui.MenuOptionWidget( {
						data: String( v ),
						label: String( v )
					} );
					if ( pi.deprecatedvalues && pi.deprecatedvalues.includes( v ) ) {
						optionWidget.$element.addClass( 'mw-apisandbox-deprecated-value' );
						optionWidget.$label.before(
							$( '<span>' ).addClass( 'mw-apisandbox-flag' ).text( mw.msg( 'api-help-param-deprecated-label' ) )
						);
					}
					if ( pi.internalvalues && pi.internalvalues.includes( v ) ) {
						optionWidget.$element.addClass( 'mw-apisandbox-internal-value' );
						optionWidget.$label.before(
							$( '<span>' ).addClass( 'mw-apisandbox-flag' ).text( mw.msg( 'api-help-param-internal-label' ) )
						);
					}
					return optionWidget;
				} ).sort( ( a, b ) => a.label < b.label ? -1 : ( a.label > b.label ? 1 : 0 ) );
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
					Object.assign( widget, WidgetMethods.tagWidget );
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
					Object.assign( widget, WidgetMethods.dropdownWidget );
					if ( Util.apiBool( pi.submodules ) ) {
						widget.getSubmodules = WidgetMethods.submoduleWidget.single;
						widget.getMenu().on( 'select', ApiSandbox.updateUI );
					}
					if ( pi.deprecatedvalues ) {
						widget.getMenu().on( 'select', ( item ) => {
							widget.$element.toggleClass(
								'mw-apisandbox-deprecated-value',
								pi.deprecatedvalues.includes( item.data )
							);
						} );
					}
					if ( pi.internalvalues ) {
						widget.getMenu().on( 'select', ( item ) => {
							widget.$element.toggleClass(
								'mw-apisandbox-internal-value',
								pi.internalvalues.includes( item.data )
							);
						} );
					}
				}

				break;
		}

		if ( Util.apiBool( pi.multi ) && multiModeAllowed ) {
			const innerWidget = widget;

			multiModeButton = new OO.ui.ButtonWidget( {
				label: mw.msg( 'apisandbox-add-multi' )
			} );
			const $content = innerWidget.$element.add( multiModeButton.$element );

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
			Object.assign( widget, WidgetMethods.tagWidget );

			const func = () => {
				if ( !innerWidget.isDisabled() ) {
					innerWidget.apiCheckValid( ApiSandbox.suppressErrors ).done( ( ok ) => {
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

		let finalWidget;
		if ( Util.apiBool( pi.required ) || opts.nooptional ) {
			finalWidget = widget;
		} else {
			finalWidget = new OptionalParamWidget( widget );
			finalWidget.paramInfo = pi;
			if ( widget.getSubmodules ) {
				finalWidget.getSubmodules = widget.getSubmodules.bind( widget );
				finalWidget.on( 'disable', () => {
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
		const $ret = $( $.parseHTML( html ) );
		return Util.fixupHTML( $ret );
	},

	/**
	 * Parse an i18n message and call Util.fixupHTML()
	 *
	 * @param {string} key Key of message to get
	 * @param {...Mixed} parameters Values for $N replacements
	 * @return {jQuery}
	 */
	parseMsg: function ( key, ...parameters ) {
		// eslint-disable-next-line mediawiki/msg-doc
		const $ret = mw.message( key, ...parameters ).parseDom();
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
		let jsonLayout, phpLayout;
		const apiUrl = new URL( mw.util.wikiScript( 'api' ), location.origin ).toString();
		const items = [
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
				} ).on( 'toggle', ( visible ) => {
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
						Object.keys( displayParams ).map(
							// displayParams is a dictionary of strings or numbers
							( param ) => '\t' +
								JSON.stringify( param ) +
								' => ' +
								JSON.stringify( displayParams[ param ] ).replace( /\$/g, '\\$' )
						).join( ',\n' ) +
						'\n]',
					multiline: true,
					textInput: {
						classes: [ 'mw-apisandbox-textInputCode' ],
						autosize: true,
						maxRows: 6
					}
				} ).on( 'toggle', ( visible ) => {
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
		const menu = ApiSandbox.formatDropdown.getMenu(),
			selected = menu.findFirstSelectedItem(),
			selectedField = selected ? selected.getData() : null;

		menu.getItems().forEach( ( item ) => {
			item.getData().toggle( item.getData() === selectedField );
		} );
	}
};

module.exports = Util;
