( function ( mw ) {
	/**
	 * List displaying all filter groups
	 *
	 * @extends OO.ui.MenuTagMultiselectWidget
	 * @mixins OO.ui.mixin.PendingElement
	 *
	 * @constructor
	 * @param {mw.rcfilters.Controller} controller Controller
	 * @param {mw.rcfilters.dm.NamespaceViewModel} model View model
	 * @param {Object} config Configuration object
	 * @cfg {jQuery} [$overlay] A jQuery object serving as overlay for popups
	 */
	mw.rcfilters.ui.NamespaceTagMultiselectWidget = function MwRcfiltersUiNamespaceTagMultiselectWidget( controller, model, config ) {
		config = config || {};

		this.controller = controller;
		this.model = model;
		this.$overlay = config.$overlay || this.$element;

		// Parent (same as FilterTagMultiselectWidget, which this will live in later)
		mw.rcfilters.ui.NamespaceTagMultiselectWidget.parent.call( this, $.extend( true, {
			label: mw.msg( 'rcfilters-filterlist-title' ),
			placeholder: mw.msg( 'rcfilters-empty-filter' ),
			inputPosition: 'outline',
			allowArbitrary: false,
			allowDisplayInvalidTags: false,
			allowReordering: false,
			$overlay: this.$overlay,
			menu: {
				hideWhenOutOfView: false,
				hideOnChoose: false,
				width: 650,
				$footer: $( '<div>' )
					.append(
						new OO.ui.ButtonWidget( {
							framed: false,
							icon: 'feedback',
							flags: [ 'progressive' ],
							label: mw.msg( 'rcfilters-filterlist-feedbacklink' ),
							href: 'https://www.mediawiki.org/wiki/Help_talk:New_filters_for_edit_review'
						} ).$element
					)
			},
			input: {
				icon: 'search',
				placeholder: mw.msg( 'rcfilters-search-placeholder' )
			}
		}, config ) );

		// Events
		this.model.connect( this, {
			initialize: 'populateFromModel',
			itemUpdate: 'onModelItemUpdate'
		} );

		this.$element
			.addClass( 'mw-rcfilters-ui-namespaceTagMultiselectWidget' );
	};

	/* Initialization */

	OO.inheritClass( mw.rcfilters.ui.NamespaceTagMultiselectWidget, OO.ui.MenuTagMultiselectWidget );

	/* Methods */

	/**
	 * @inheritdoc
	 */
	mw.rcfilters.ui.NamespaceTagMultiselectWidget.prototype.createMenuWidget = function ( menuConfig ) {
		return new mw.rcfilters.ui.FloatingMenuSelectWidget(
			this.controller,
			this.model,
			$.extend( {
				filterFromInput: true
			}, menuConfig )
		);
	};

	/**
	 * @inheritdoc
	 */
	mw.rcfilters.ui.NamespaceTagMultiselectWidget.prototype.createTagItemWidget = function ( data ) {
		var namespaceItem = this.model.getItemByName( data );

		if ( namespaceItem ) {
			return new mw.rcfilters.ui.NamespaceTagItemWidget(
				this.controller,
				namespaceItem,
				{
					$overlay: this.$overlay
				}
			);
		}
	};

	/**
	 * @inheritdoc
	 */
	mw.rcfilters.ui.NamespaceTagMultiselectWidget.prototype.onMenuChoose = function ( item ) {
		mw.rcfilters.ui.NamespaceTagMultiselectWidget.parent.prototype.onMenuChoose.call( this, item );

		this.controller.toggleNamespaceSelect( item.model.getName() );

		this.focus();
	};

	/**
	 * Respond to model itemUpdate event
	 *
	 * @param {mw.rcfilters.dm.FilterItem} item Filter item model
	 */
	mw.rcfilters.ui.NamespaceTagMultiselectWidget.prototype.onModelItemUpdate = function ( item ) {
		if ( item.isSelected() ) {
			this.addTag( item.getName(), item.getLabel() );
		} else {
			this.removeTagByData( item.getName() );
		}
	};

	/**
	 * Populate the menu from the model
	 */
	mw.rcfilters.ui.NamespaceTagMultiselectWidget.prototype.populateFromModel = function () {
		var widget = this,
			items = [];

		// Reset
		this.getMenu().clearItems();

		// Add items
		this.model.getItems().forEach( function ( namespaceItem ) {
			items.push(
				new mw.rcfilters.ui.NamespaceMenuOptionWidget(
					widget.controller,
					namespaceItem,
					{
						$overlay: widget.$overlay
					}
				)
			);
		} );

		// Add all items to the menu
		this.getMenu().addItems( items );
	};

	/**
	 * @inheritdoc
	 */
	mw.rcfilters.ui.NamespaceTagMultiselectWidget.prototype.createTagItemWidget = function ( data ) {
		var item = this.model.getItemByName( data );

		if ( item ) {
			return new mw.rcfilters.ui.TagItemWidget(
				this.controller,
				item,
				{
					$overlay: this.$overlay
				}
			);
		}
	};

	/**
	 * Scroll the element to top within its container
	 *
	 * @private
	 * @param {jQuery} $element Element to position
	 * @param {number} [marginFromTop] When scrolling the entire widget to the top, leave this
	 *  much space (in pixels) above the widget.
	 */
	mw.rcfilters.ui.NamespaceTagMultiselectWidget.prototype.scrollToTop = function ( $element, marginFromTop ) {
		var container = OO.ui.Element.static.getClosestScrollableContainer( $element[ 0 ], 'y' ),
			pos = OO.ui.Element.static.getRelativePosition( $element, $( container ) ),
			containerScrollTop = $( container ).is( 'body, html' ) ? 0 : $( container ).scrollTop();

		// Scroll to item
		$( container ).animate( {
			scrollTop: containerScrollTop + pos.top - ( marginFromTop || 0 )
		} );
	};
}( mediaWiki ) );
