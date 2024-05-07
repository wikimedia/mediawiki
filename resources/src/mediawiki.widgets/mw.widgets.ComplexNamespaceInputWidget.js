/*!
 * MediaWiki Widgets - ComplexNamespaceInputWidget class.
 *
 * @copyright 2011-2015 MediaWiki Widgets Team and others; see AUTHORS.txt
 * @license The MIT License (MIT); see LICENSE.txt
 */
( function () {

	/**
	 * @classdesc Displays a dropdown box with the choice of available namespaces,
	 * plus two checkboxes to include associated namespace or to invert selection.
	 *
	 * @class
	 * @extends OO.ui.Widget
	 *
	 * @constructor
	 * @description Create an instance of `mw.widgets.ComplexNamespaceInputWidget`.
	 * @param {Object} [config] Configuration options
	 * @param {Object} config.namespace Configuration for the NamespaceInputWidget dropdown with list
	 *     of namespaces
	 * @param {string} config.namespace.includeAllValue If specified, add a "all namespaces"
	 *     option to the dropdown, and use this as the input value for it
	 * @param {Object} config.invert Configuration for the "invert selection" CheckboxInputWidget. If
	 *     null, the checkbox will not be generated.
	 * @param {Object} config.associated Configuration for the "include associated namespace"
	 *     CheckboxInputWidget. If null, the checkbox will not be generated.
	 * @param {Object} config.invertLabel Configuration for the FieldLayout with label wrapping the
	 *     "invert selection" checkbox
	 * @param {string} config.invertLabel.label Label text for the label
	 * @param {Object} config.associatedLabel Configuration for the FieldLayout with label wrapping
	 *     the "include associated namespace" checkbox
	 * @param {string} config.associatedLabel.label Label text for the label
	 */
	mw.widgets.ComplexNamespaceInputWidget = function MwWidgetsComplexNamespaceInputWidget( config ) {
		// Configuration initialization
		config = $.extend(
			{
				// Config options for nested widgets
				namespace: {},
				invert: {},
				invertLabel: {},
				associated: {},
				associatedLabel: {}
			},
			config
		);

		// Parent constructor
		mw.widgets.ComplexNamespaceInputWidget.super.call( this, config );

		// Properties
		this.config = config;

		this.namespace = new mw.widgets.NamespaceInputWidget( config.namespace );
		if ( config.associated !== null ) {
			this.associated = new OO.ui.CheckboxInputWidget( $.extend(
				{ value: '1' },
				config.associated
			) );
			// TODO Should use a LabelWidget? But they don't work like HTML <label>s yet
			this.associatedLabel = new OO.ui.FieldLayout(
				this.associated,
				$.extend(
					{ align: 'inline' },
					config.associatedLabel
				)
			);
		}
		if ( config.invert !== null ) {
			this.invert = new OO.ui.CheckboxInputWidget( $.extend(
				{ value: '1' },
				config.invert
			) );
			// TODO Should use a LabelWidget? But they don't work like HTML <label>s yet
			this.invertLabel = new OO.ui.FieldLayout(
				this.invert,
				$.extend(
					{ align: 'inline' },
					config.invertLabel
				)
			);
		}

		// Events
		this.namespace.connect( this, { change: 'updateCheckboxesState' } );

		// Initialization
		this.$element
			.addClass( 'mw-widget-complexNamespaceInputWidget' )
			.append(
				this.namespace.$element,
				this.invert ? this.invertLabel.$element : '',
				this.associated ? this.associatedLabel.$element : ''
			);
		this.updateCheckboxesState();
	};

	/* Setup */

	OO.inheritClass( mw.widgets.ComplexNamespaceInputWidget, OO.ui.Widget );

	/* Methods */

	/**
	 * Update the disabled state of checkboxes when the value of namespace dropdown changes.
	 *
	 * @private
	 */
	mw.widgets.ComplexNamespaceInputWidget.prototype.updateCheckboxesState = function () {
		const disabled = this.namespace.getValue() === this.namespace.allValue;
		if ( this.invert ) {
			this.invert.setDisabled( disabled );
		}
		if ( this.associated ) {
			this.associated.setDisabled( disabled );
		}
	};

	/**
	 * @inheritdoc
	 */
	mw.widgets.ComplexNamespaceInputWidget.prototype.setDisabled = function ( disabled ) {
		mw.widgets.ComplexNamespaceInputWidget.super.prototype.setDisabled.call( this, disabled );
		if ( this.namespace ) {
			this.namespace.setDisabled( disabled );
		}
		if ( this.invert ) {
			this.invert.setDisabled( disabled );
		}

		if ( this.associated ) {
			this.associated.setDisabled( disabled );
		}
		return this;
	};

}() );
