( function ( mw, $ ) {
	/**
	 * A group of filters
	 *
	 * @extends OO.ui.Widget
	 * @mixins OO.ui.mixin.GroupWidget
	 * @mixins OO.ui.mixin.LabelElement
	 *
	 * @constructor
	 * @param {mw.rcfilters.Controller} controller Controller
	 * @param {mw.rcfilters.dm.FilterGroup} model Filter group model
	 * @param {Object} config Configuration object
	 * @cfg {jQuery} [$overlay] Overlay
	 */
	mw.rcfilters.ui.FilterGroupWidget = function MwRcfiltersUiFilterGroupWidget( controller, model, config ) {
		var whatsThisMessages,
			$header = $( '<div>' )
				.addClass( 'mw-rcfilters-ui-filterGroupWidget-header' ),
			$popupContent = $( '<div>' )
				.addClass( 'mw-rcfilters-ui-filterGroupWidget-whatsThisButton-popup-content' );

		config = config || {};

		// Parent
		mw.rcfilters.ui.FilterGroupWidget.parent.call( this, config );

		this.controller = controller;
		this.model = model;
		this.filters = {};
		this.$overlay = config.$overlay || this.$element;

		// Mixin constructors
		OO.ui.mixin.GroupWidget.call( this, config );
		OO.ui.mixin.LabelElement.call( this, $.extend( {}, config, {
			label: this.model.getTitle(),
			$label: $( '<div>' )
				.addClass( 'mw-rcfilters-ui-filterGroupWidget-header-title' )
		} ) );

		$header.append( this.$label );

		if ( this.model.hasWhatsThis() ) {
			whatsThisMessages = this.model.getWhatsThis();

			// Create popup
			if ( whatsThisMessages.header ) {
				$popupContent.append(
					( new OO.ui.LabelWidget( {
						label: mw.msg( whatsThisMessages.header ),
						classes: [ 'mw-rcfilters-ui-filterGroupWidget-whatsThisButton-popup-content-header' ]
					} ) ).$element
				);
			}
			if ( whatsThisMessages.body ) {
				$popupContent.append(
					( new OO.ui.LabelWidget( {
						label: mw.msg( whatsThisMessages.body ),
						classes: [ 'mw-rcfilters-ui-filterGroupWidget-whatsThisButton-popup-content-body' ]
					} ) ).$element
				);
			}
			if ( whatsThisMessages.linkText && whatsThisMessages.url ) {
				$popupContent.append(
					( new OO.ui.ButtonWidget( {
						framed: false,
						flags: [ 'progressive' ],
						href: whatsThisMessages.url,
						label: mw.msg( whatsThisMessages.linkText ),
						classes: [ 'mw-rcfilters-ui-filterGroupWidget-whatsThisButton-popup-content-link' ]
					} ) ).$element
				);
			}

			// Add button
			this.whatsThisButton = new OO.ui.PopupButtonWidget( {
				framed: false,
				label: mw.msg( 'rcfilters-filterlist-whatsthis' ),
				$overlay: this.$overlay,
				classes: [ 'mw-rcfilters-ui-filterGroupWidget-whatsThisButton' ],
				flags: [ 'progressive' ],
				popup: {
					padded: false,
					align: 'center',
					position: 'above',
					$content: $popupContent,
					classes: [ 'mw-rcfilters-ui-filterGroupWidget-whatsThisButton-popup' ]
				}
			} );

			$header
				.append( this.whatsThisButton.$element );
		}

		// Populate
		this.populateFromModel();

		this.model.connect( this, { update: 'onModelUpdate' } );

		this.$element
			.addClass( 'mw-rcfilters-ui-filterGroupWidget' )
			.addClass( 'mw-rcfilters-ui-filterGroupWidget-name-' + this.model.getName() )
			.append(
				$header,
				this.$group
					.addClass( 'mw-rcfilters-ui-filterGroupWidget-group' )
			);
	};

	/* Initialization */

	OO.inheritClass( mw.rcfilters.ui.FilterGroupWidget, OO.ui.Widget );
	OO.mixinClass( mw.rcfilters.ui.FilterGroupWidget, OO.ui.mixin.GroupWidget );
	OO.mixinClass( mw.rcfilters.ui.FilterGroupWidget, OO.ui.mixin.LabelElement );

	/**
	 * Respond to model update event
	 */
	mw.rcfilters.ui.FilterGroupWidget.prototype.onModelUpdate = function () {
		this.$element.toggleClass(
			'mw-rcfilters-ui-filterGroupWidget-active',
			this.model.isActive()
		);
	};

	/**
	 * Get an item widget from its filter name
	 *
	 * @param {string} filterName Filter name
	 * @return {mw.rcfilters.ui.FilterItemWidget} Item widget
	 */
	mw.rcfilters.ui.FilterGroupWidget.prototype.getItemWidget = function ( filterName ) {
		return this.filters[ filterName ];
	};

	/**
	 * Populate data from the model
	 */
	mw.rcfilters.ui.FilterGroupWidget.prototype.populateFromModel = function () {
		var widget = this;

		this.clearItems();
		this.filters = {};

		this.addItems(
			this.model.getItems().map( function ( filterItem ) {
				var groupWidget = new mw.rcfilters.ui.FilterItemWidget(
					widget.controller,
					filterItem,
					{
						label: filterItem.getLabel(),
						description: filterItem.getDescription(),
						$overlay: widget.$overlay
					}
				);

				widget.filters[ filterItem.getName() ] = groupWidget;

				return groupWidget;
			} )
		);
	};

	/**
	 * Get the group name
	 *
	 * @return {string} Group name
	 */
	mw.rcfilters.ui.FilterGroupWidget.prototype.getName = function () {
		return this.model.getName();
	};
}( mediaWiki, jQuery ) );
