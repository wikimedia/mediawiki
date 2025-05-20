const ParamLabelWidget = require( './ParamLabelWidget.js' ),
	OptionalParamWidget = require( './OptionalParamWidget.js' ),
	ApiSandbox = require( './ApiSandbox.js' ),
	Util = require( './Util.js' );

/**
 * PageLayout for API modules
 *
 * @class
 * @private
 * @extends OO.ui.PageLayout
 * @constructor
 * @param {Object} [config] Configuration options
 */
function ApiSandboxLayout( config ) {
	config = Object.assign( { prefix: '', expanded: false }, config );
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
	ApiSandboxLayout.super.call( this, config.key, config );
	this.loadParamInfo();
}

OO.inheritClass( ApiSandboxLayout, OO.ui.PageLayout );

ApiSandboxLayout.prototype.setupOutlineItem = function () {
	this.outlineItem.setLevel( this.indentLevel );
	this.outlineItem.setLabel( this.displayText );
	this.outlineItem.setIcon( this.apiIsValid || ApiSandbox.suppressErrors ? null : 'alert' );
	this.outlineItem.setTitle(
		this.apiIsValid || ApiSandbox.suppressErrors ? '' : mw.message( 'apisandbox-alert-page' ).plain()
	);
};

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
ApiSandboxLayout.prototype.makeWidgetFieldLayouts = function ( ppi, name ) {
	const widget = Util.createWidgetForParameter( ppi );
	if ( ppi.tokentype ) {
		this.tokenWidget = widget;
	}
	if ( this.paramInfo.templatedparameters.length ) {
		widget.on( 'change', () => {
			this.updateTemplatedParameters( null );
		} );
	}

	const helpLabel = new ParamLabelWidget();

	let $tmp = Util.parseHTML( ppi.description );
	$tmp.filter( 'dl' ).makeCollapsible( {
		collapsed: true
	} ).children( '.mw-collapsible-toggle' ).each( ( i, el ) => {
		const $el = $( el );
		$el.parent().prev( 'p' ).append( $el );
	} );
	helpLabel.addDescription( $tmp );

	if ( ppi.info && ppi.info.length ) {
		for ( let i = 0; i < ppi.info.length; i++ ) {
			helpLabel.addInfo( Util.parseHTML( ppi.info[ i ].text ) );
		}
	}
	let flag = true;
	let count = Infinity;
	let tmp;
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
		for ( let j = 0, l = ppi.usedTemplateVars.length; j < l; j++ ) {
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
	const helpField = new OO.ui.FieldLayout(
		helpLabel,
		{
			align: 'top',
			classes: [ 'mw-apisandbox-help-field' ]
		}
	);

	const layoutConfig = {
		align: 'left',
		classes: [ 'mw-apisandbox-widget-field' ],
		label: name
	};

	let widgetField;
	if ( ppi.tokentype ) {
		const button = new OO.ui.ButtonWidget( {
			label: mw.msg( 'apisandbox-fetch-token' )
		} );
		button.on( 'click', () => {
			widget.fetchToken();
		} );

		widgetField = new OO.ui.ActionFieldLayout( widget, button, layoutConfig );
	} else {
		widgetField = new OO.ui.FieldLayout( widget, layoutConfig );
	}

	// We need our own click handler on the widget label to
	// turn off the disablement.
	widgetField.$label.on( 'click', () => {
		if ( typeof widget.setDisabled === 'function' ) {
			widget.setDisabled( false );
		}
		if ( typeof widget.focus === 'function' ) {
			widget.focus();
		}
	} );

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
ApiSandboxLayout.prototype.updateTemplatedParameters = function ( params ) {
	const pi = this.paramInfo,
		prefix = this.prefix + pi.prefix;

	if ( !pi || !pi.templatedparameters.length ) {
		return;
	}

	if ( !$.isPlainObject( params ) ) {
		params = null;
	}

	let toRemove = {};
	// eslint-disable-next-line no-jquery/no-each-util
	$.each( this.templatedItemsCache, ( k, el ) => {
		if ( el.widget.isElementAttached() ) {
			toRemove[ k ] = el;
		}
	} );

	// This bit duplicates the PHP logic in ApiBase::extractRequestParams().
	// If you update this, see if that needs updating too.
	const toProcess = pi.templatedparameters.map( ( info ) => ( {
		name: prefix + info.name,
		info: info,
		vars: Object.assign( {}, info.templatevars ),
		usedVars: []
	} ) );
	let p;
	const doProcess = ( placeholder, target ) => {
		target = prefix + target;

		if ( !this.widgets[ target ] ) {
			// The target wasn't processed yet, try the next one.
			// If all hit this case, the parameter has no expansions.
			return true;
		}

		if ( !this.widgets[ target ].getApiValueForTemplates ) {
			// Not a multi-valued widget, so it can't have expansions.
			return false;
		}

		const values = this.widgets[ target ].getApiValueForTemplates();
		if ( !Array.isArray( values ) || !values.length ) {
			// The target was processed but has no (valid) values.
			// That means it has no expansions.
			return false;
		}

		// Expand this target in the name and all other targets,
		// then requeue if there are more targets left or create the widget
		// and add it to the form if all are done.
		delete p.vars[ placeholder ];
		const usedVars = p.usedVars.concat( [ target ] );
		placeholder = '{' + placeholder + '}';
		const done = $.isEmptyObject( p.vars );
		let index, container;
		if ( done ) {
			container = Util.apiBool( p.info.deprecated ) ? this.deprecatedItemsFieldset : this.itemsFieldset;
			const items = container.getItems();
			for ( let i = 0; i < items.length; i++ ) {
				if ( items[ i ].apiParamIndex !== undefined && items[ i ].apiParamIndex > p.info.index ) {
					index = i;
					break;
				}
			}
		}
		values.forEach( ( value ) => {
			if ( !/^[^{}]*$/.exec( value ) ) {
				// Skip values that make invalid parameter names
				return;
			}

			const name = p.name.replace( placeholder, value );
			if ( done ) {
				let tmp;
				if ( this.templatedItemsCache[ name ] ) {
					tmp = this.templatedItemsCache[ name ];
				} else {
					tmp = this.makeWidgetFieldLayouts(
						Object.assign( {}, p.info, { usedTemplateVars: usedVars } ), name
					);
					this.templatedItemsCache[ name ] = tmp;
				}
				delete toRemove[ name ];
				if ( !tmp.widget.isElementAttached() ) {
					this.widgets[ name ] = tmp.widget;
					container.addItems( [ tmp.widgetField, tmp.helpField ], index );
					if ( index !== undefined ) {
						index += 2;
					}
				}
				if ( params ) {
					tmp.widget.setApiValue( Object.prototype.hasOwnProperty.call( params, name ) ? params[ name ] : undefined );
				}
			} else {
				const newVars = {};
				// eslint-disable-next-line no-jquery/no-each-util
				$.each( p.vars, ( k, v ) => {
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
	toRemove = $.map( toRemove, ( el, name ) => {
		delete this.widgets[ name ];
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
ApiSandboxLayout.prototype.loadParamInfo = function () {
	let dynamicFieldset, dynamicParamNameWidget;
	const removeDynamicParamWidget = ( name, item ) => {
			dynamicFieldset.removeItems( [ item ] );
			delete this.widgets[ name ];
		},
		addDynamicParamWidget = () => {
			// Check name is filled in
			const name = dynamicParamNameWidget.getValue().trim();
			if ( name === '' ) {
				dynamicParamNameWidget.focus();
				return;
			}

			if ( this.widgets[ name ] !== undefined ) {
				ApiSandbox.windowManager.openWindow( 'errorAlert', {
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

			const widget = Util.createWidgetForParameter( {
				name: name,
				type: 'string',
				default: ''
			}, {
				nooptional: true
			} );
			const button = new OO.ui.ButtonWidget( {
				icon: 'trash',
				flags: 'destructive'
			} );
			const actionFieldLayout = new OO.ui.ActionFieldLayout(
				widget,
				button,
				{
					label: name,
					align: 'left'
				}
			);
			button.on( 'click', () => {
				removeDynamicParamWidget( name, actionFieldLayout );
			} );
			this.widgets[ name ] = widget;
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
		.done( ( pi ) => {
			const items = [],
				deprecatedItems = [],
				buttons = [],
				filterFmModules = ( v ) => !v.endsWith( 'fm' ) ||
					!Object.prototype.hasOwnProperty.call( ApiSandbox.availableFormats, v.slice( 0, -2 ) );

			// This is something of a hack. We always want the 'format' and
			// 'action' parameters from the main module to be specified,
			// and for 'format' we also want to simplify the dropdown since
			// we always send the 'fm' variant.
			if ( this.apiModule === 'main' ) {
				pi.parameters.forEach( ( parameter ) => {
					if ( parameter.name === 'action' ) {
						parameter.required = true;
						delete parameter.default;
					}
					if ( parameter.name === 'format' ) {
						const types = parameter.type;
						types.forEach( ( type ) => {
							ApiSandbox.availableFormats[ type ] = true;
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
				pi.parameters = pi.parameters.filter( ( p ) => p.name !== 'wrappedhtml' ).map( ( p ) => {
					if ( p.name === 'formatversion' ) {
						// Use the highest numeric value
						p.default = p.type.reduce( ( prev, current ) => !isNaN( current ) ? Math.max( prev, current ) : prev );
						p.required = true;
					}
					return p;
				} );
			}

			this.paramInfo = pi;

			let $desc = Util.parseHTML( pi.description );
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
						$content: $( '<ul>' ).append( pi.helpurls.map( ( link ) => $( '<li>' ).append( $( '<a>' )
							.attr( { href: link, target: '_blank' } )
							.text( link )
						) ) )
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
						$content: $( '<ul>' ).append( pi.examples.map( ( example ) => {
							const $a = $( '<a>' )
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
				const prefix = this.prefix + pi.prefix;
				pi.parameters.forEach( ( parameter ) => {
					const tmpLayout = this.makeWidgetFieldLayouts( parameter, prefix + parameter.name );
					this.widgets[ prefix + parameter.name ] = tmpLayout.widget;
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

			this.$element.empty();

			this.itemsFieldset = new OO.ui.FieldsetLayout( {
				label: this.displayText
			} );
			this.itemsFieldset.addItems( items );
			this.itemsFieldset.$element.appendTo( this.$element );

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
					.appendTo( this.$element );
			}

			this.deprecatedItemsFieldset = new OO.ui.FieldsetLayout().addItems( deprecatedItems ).toggle( false );
			const $tmp = $( '<fieldset>' )
				.toggle( !this.deprecatedItemsFieldset.isEmpty() )
				.append(
					$( '<legend>' ).append(
						new OO.ui.ToggleButtonWidget( {
							label: mw.msg( 'apisandbox-deprecated-parameters' )
						} ).on( 'change', () => {
							this.deprecatedItemsFieldset.toggle();
						} ).$element
					),
					this.deprecatedItemsFieldset.$element
				)
				.appendTo( this.$element );
			this.deprecatedItemsFieldset.on( 'add', () => {
				$tmp.toggle( !this.deprecatedItemsFieldset.isEmpty() );
			} );
			this.deprecatedItemsFieldset.on( 'remove', () => {
				$tmp.toggle( !this.deprecatedItemsFieldset.isEmpty() );
			} );
			// Load stored params, if any, then update the booklet if we
			// have subpages (or else just update our valid-indicator).
			const tmp = this.loadFromQueryParams;
			this.loadFromQueryParams = null;
			if ( $.isPlainObject( tmp ) ) {
				this.loadQueryParams( tmp );
			} else {
				this.updateTemplatedParameters();
			}
			if ( this.getSubpages().length > 0 ) {
				ApiSandbox.updateUI( tmp );
			} else {
				this.apiCheckValid();
			}
		} ).fail( ( code, detail ) => {
			this.$element.empty()
				.append(
					new OO.ui.LabelWidget( {
						label: mw.msg( 'apisandbox-load-error', this.apiModule, detail ),
						classes: [ 'error' ]
					} ).$element,
					new OO.ui.ButtonWidget( {
						label: mw.msg( 'apisandbox-retry' )
					} ).on( 'click', () => {
						this.loadParamInfo();
					} ).$element
				);
		} );
};

/**
 * Check that all widgets on the page are in a valid state.
 *
 * @return {jQuery.Promise[]} One promise for each widget, resolved with `false` if invalid
 */
ApiSandboxLayout.prototype.apiCheckValid = function () {
	if ( this.paramInfo === null ) {
		return [];
	} else {
		// eslint-disable-next-line no-jquery/no-map-util
		const promises = $.map( this.widgets, ( widget ) => widget.apiCheckValid( ApiSandbox.suppressErrors ) );
		$.when( ...promises ).then( ( ...results ) => {
			this.apiIsValid = !results.includes( false );
			if ( this.getOutlineItem() ) {
				this.getOutlineItem().setIcon( this.apiIsValid || ApiSandbox.suppressErrors ? null : 'alert' );
				this.getOutlineItem().setTitle(
					this.apiIsValid || ApiSandbox.suppressErrors ? '' : mw.message( 'apisandbox-alert-page' ).plain()
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
ApiSandboxLayout.prototype.loadQueryParams = function ( params ) {
	if ( this.paramInfo === null ) {
		this.loadFromQueryParams = params;
	} else {
		// eslint-disable-next-line no-jquery/no-each-util
		$.each( this.widgets, ( name, widget ) => {
			const v = Object.prototype.hasOwnProperty.call( params, name ) ? params[ name ] : undefined;
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
ApiSandboxLayout.prototype.getQueryParams = function ( params, displayParams, ajaxOptions ) {
	// eslint-disable-next-line no-jquery/no-each-util
	$.each( this.widgets, ( name, widget ) => {
		let value = widget.getApiValue();
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
ApiSandboxLayout.prototype.getSubpages = function () {
	const ret = [];
	// eslint-disable-next-line no-jquery/no-each-util
	$.each( this.widgets, ( name, widget ) => {
		if ( typeof widget.getSubmodules === 'function' ) {
			widget.getSubmodules().forEach( ( submodule ) => {
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

module.exports = ApiSandboxLayout;
