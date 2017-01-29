/*!
 * JavaScript for Special:RecentChanges
 */
( function ( mw, $ ) {

	mw.rcfilters.ui.RoanFilterCapsuleMultiselectWidget = function ( controller, config ) {
		// Parent constructor
		mw.rcfilters.ui.RoanFilterCapsuleMultiselectWidget.parent.call( this, config );

		this.controller = controller;
		this.model = controller.getModel();

		// CapsuleMultiselectWidget unhelpfully creates a raw <input> rather than a TextInputWidget,
		// and also attaches tab indexing and event handlers to this.$input
		// HACK: create a TextInputWidget that hijacks this.$input
		this.filterInput = new OO.ui.TextInputWidget( {
			$input: this.$input,
			icon: 'search',
			placeholder: mw.msg( 'rcfilters-search-placeholder' ),
			classes: [ 'mw-rcfilters-ui-filterCapsuleMultiselectWidget-input' ]
		} );
		
		this.resetButton = new OO.ui.ButtonWidget( {
			icon: 'trash',
			framed: false,
			title: mw.msg( 'rcfilters-clear-all-filters' ),
			classes: [ 'mw-rcfilters-ui-filterCapsuleMultiselectWidget-resetButton' ]
		} );

		this.emptyFilterMessage = new OO.ui.LabelWidget( {
			label: mw.msg( 'rcfilters-empty-filter' ),
			classes: [ 'mw-rcfilters-ui-filterCapsuleMultiselectWidget-emptyFilters' ]
		} );

		this.resetButton.connect( this, { click: 'onResetButtonClick' } );
		this.model.connect( this, { itemUpdate: 'onModelItemUpdate' } );
		
		this.$content.prepend(
			$( '<div>' )
				.addClass( 'mw-rcfilters-ui-filterCapsuleMultiselectWidget-content-title' )
				.text( mw.msg( 'rcfilters-activefilters' ) )
		);
		this.$content.append( this.emptyFilterMessage.$element );
		this.$handle.append(
			// The content and button should appear side by side regardless of how
			// wide the button is; the button also changes its width depending
			// on language and its state, so the safest way to present both side
			// by side is with a table layout
			$( '<div>' )
				.addClass( 'mw-rcfilters-ui-filterCapsuleMultiselectWidget-table' )
				.append(
					$( '<div>' )
						.addClass( 'mw-rcfilters-ui-filterCapsuleMultiselectWidget-row' )
						.append(
							$( '<div>' )
								.addClass( 'mw-rcfilters-ui-filterCapsuleMultiselectWidget-content' )
								.addClass( 'mw-rcfilters-ui-filterCapsuleMultiselectWidget-cell' )
								.append( this.$content ),
							$( '<div>' )
								.addClass( 'mw-rcfilters-ui-filterCapsuleMultiselectWidget-cell' )
								.append( this.resetButton.$element )
						)
				)
		);
		this.$handle.after( this.filterInput.$element );
		this.$element.addClass( 'mw-rcfilters-ui-filterCapsuleMultiselectWidget' );

		this.getMenu().addItems( this.buildMenuItems() );
		this.addItemsFromData(
			this.model.getItems()
				.filter( function ( filterItem ) { return filterItem.isSelected(); } )
				.map( function ( filterItem ) { return filterItem.getName(); } )
		);
		this.reevaluateResetRestoreState();

	};

	OO.inheritClass( mw.rcfilters.ui.RoanFilterCapsuleMultiselectWidget, OO.ui.CapsuleMultiselectWidget );

	/**
	 * @private
	 */
	mw.rcfilters.ui.RoanFilterCapsuleMultiselectWidget.prototype.buildMenuItems = function () {
		// TODO inline in the constructor?
		var group,
			menuItems = [],
			groups = this.model.getFilterGroups(),
			controller = this.controller;
		
		for ( group in groups ) {
			menuItems.push( new mw.rcfilters.ui.FilterGroupMenuSectionOptionWidget( this.model, group ) );
			menuItems.push.apply( menuItems, groups[ group ].filters.map( function ( filterItem ) {
				return new mw.rcfilters.ui.FilterItemMenuOptionWidget( controller, filterItem );
			} ) );
		}
		return menuItems;
	};

	mw.rcfilters.ui.RoanFilterCapsuleMultiselectWidget.prototype.createItemWidget = function ( data, label ) {
		var filterItem = this.model.getItemByName( data );
		if ( !filterItem ) {
			return null;
		}
		return new mw.rcfilters.ui.RoanFilterCapsuleItemWidget( filterItem );
	};

	mw.rcfilters.ui.RoanFilterCapsuleMultiselectWidget.prototype.onModelItemUpdate = function ( filterItem ) {
		var groupWidget;
		if ( filterItem.isSelected() ) {
			this.addItemsFromData( [ filterItem.getName() ] );
		} else {
			this.removeItemsFromData ( [ filterItem.getName() ] );
		}

		groupWidget = this.getMenu().getItemFromData( 'filtergroup-' + filterItem.getGroup() );
		if ( groupWidget ) {
			groupWidget.reevaluateActiveState();
		}

		this.reevaluateResetRestoreState();
	};

	mw.rcfilters.ui.RoanFilterCapsuleMultiselectWidget.prototype.onResetButtonClick =
		mw.rcfilters.ui.FilterCapsuleMultiselectWidget.prototype.onResetButtonClick;
	
	mw.rcfilters.ui.RoanFilterCapsuleMultiselectWidget.prototype.reevaluateResetRestoreState =
		mw.rcfilters.ui.FilterCapsuleMultiselectWidget.prototype.reevaluateResetRestoreState;

	mw.rcfilters.ui.RoanFilterCapsuleMultiselectWidget.prototype.onKeyDown = function () {};
	mw.rcfilters.ui.RoanFilterCapsuleMultiselectWidget.prototype.updateInputSize = function () {};
	mw.rcfilters.ui.RoanFilterCapsuleMultiselectWidget.prototype.onMenuChoose = function () {};
	
	mw.rcfilters.ui.RoanFilterCapsuleMultiselectWidget.prototype.removeItems = function ( items ) {
		var i, filterItem;
		
		// Parent method
		mw.rcfilters.ui.RoanFilterCapsuleMultiselectWidget.parent.prototype.removeItems.call( this, items );

		for ( i = 0; i < items.length; i++ ) {
			items[ i ].getModel().toggleSelected( false );
		}
	};

	

	mw.rcfilters.ui.RoanFilterCapsuleItemWidget = function ( filterItem, config ) {
		this.model = filterItem;
		
		// Parent constructor
		mw.rcfilters.ui.RoanFilterCapsuleItemWidget.parent.call( this, $.extend( {
			data: this.model.getName(),
			label: this.model.getLabel()
		}, config ) );

		this.model.connect( this, { update: 'reevaluateActiveState' } );
		this.reevaluateActiveState();
	};

	OO.inheritClass( mw.rcfilters.ui.RoanFilterCapsuleItemWidget, OO.ui.CapsuleItemWidget );

	mw.rcfilters.ui.RoanFilterCapsuleItemWidget.prototype.getModel = function () {
		return this.model;
	};
	
	mw.rcfilters.ui.RoanFilterCapsuleItemWidget.prototype.reevaluateActiveState = function () {
		this.$element.toggleClass(
			'mw-rcfilters-ui-filterCapsuleMultiselectWidget-item-inactive',
			!this.model.isActive()
		);
	};
	
	mw.rcfilters.ui.FilterGroupMenuSectionOptionWidget = function ( model, groupName, config ) {
		var groupData = model.getFilterGroup( groupName );
		this.model = model;
		this.groupName = groupName;
		
		// Parent constructor
		mw.rcfilters.ui.FilterGroupMenuSectionOptionWidget.parent.call( this, $.extend( {
			label: groupData && groupData.title || this.groupName,
			data: 'filtergroup-' + this.groupName
		}, config ) );

		this.$element.addClass( 'mw-rcfilters-ui-filterGroupMenuSectionOptionWidget' );
		
		this.reevaluateActiveState();
	};

	OO.inheritClass( mw.rcfilters.ui.FilterGroupMenuSectionOptionWidget, OO.ui.MenuSectionOptionWidget );

	mw.rcfilters.ui.FilterGroupMenuSectionOptionWidget.prototype.reevaluateActiveState = function () {
		this.$element.toggleClass(
			'mw-rcfilters-ui-filterGroupMenuSectionOptionWidget-active',
			this.model.isFilterGroupActive( this.groupName )
		);
	};

	mw.rcfilters.ui.FilterItemMenuOptionWidget = function ( controller, filterItem, config ) {
		var layout,
			$label = $( '<div>' )
				.addClass( 'mw-rcfilters-ui-filterItemWidget-label' );
		this.controller = controller;
		this.model = filterItem;

		// Parent constructor
		mw.rcfilters.ui.FilterItemMenuOptionWidget.parent.call( this, $.extend( {
			data: this.model.getName(),
			label: this.model.getLabel()
		}, config ) );
		

		this.checkboxWidget = new OO.ui.CheckboxInputWidget( {
			value: this.model.getName(),
			selected: this.model.isSelected()
		} );

		// Wrap this.$label and optionally add a description below it
		$label.append(
			this.$label
				.addClass( 'mw-rcfilters-ui-filterItemWidget-label-title' )
		);
		if ( this.model.getDescription() ) {
			$label.append(
				$( '<div>' )
					.addClass( 'mw-rcfilters-ui-filterItemWidget-label-desc' )
					.text( this.model.getDescription() )
			);
		}

		layout = new OO.ui.FieldLayout( this.checkboxWidget, {
			label: $label,
			align: 'inline'
		} );

		// Event
		this.checkboxWidget.connect( this, { change: 'onCheckboxChange' } );
		this.model.connect( this, { update: 'onModelUpdate' } );

		this.$element
			.addClass( 'mw-rcfilters-ui-filterItemWidget' )
			.append(
				layout.$element
			);
		
		// HACK: Intercept mousedown/mouseup and stop them from propagating up to
		// the SelectWidget
		// TODO: Instead of this we could also embrace 'choose', since that will happen
		// for keyboard interaction anyway. This would be simple to do with an event handler,
		// the only problem is that MenuSelectWidget wants to hide itself on choose
		this.$element.on( 'mousedown mouseup', function ( e ) {
			e.stopPropagation();
		} );
	};

	OO.inheritClass( mw.rcfilters.ui.FilterItemMenuOptionWidget, OO.ui.MenuOptionWidget );

	//mw.rcfilters.ui.FilterItemMenuOptionWidget.static.selectable = false;

	mw.rcfilters.ui.FilterItemMenuOptionWidget.prototype.onCheckboxChange =
		mw.rcfilters.ui.FilterItemWidget.prototype.onCheckboxChange;
	
	mw.rcfilters.ui.FilterItemMenuOptionWidget.prototype.onModelUpdate =
		mw.rcfilters.ui.FilterItemWidget.prototype.onModelUpdate;

	mw.rcfilters.ui.FilterItemMenuOptionWidget.prototype.getName =
		mw.rcfilters.ui.FilterItemWidget.prototype.getName;
	

	/**
	 * @class mw.rcfilters
	 * @singleton
	 */
	var rcfilters = {
		/** */
		init: function () {
			var model = new mw.rcfilters.dm.FiltersViewModel(),
				controller = new mw.rcfilters.Controller( model ),
				widget = new mw.rcfilters.ui.FilterWrapperWidget( controller, model ),
				widget2;

			model.initializeFilters( {
				registration: {
					title: mw.msg( 'rcfilters-filtergroup-registration' ),
					type: 'send_unselected_if_any',
					filters: [
						{
							name: 'hideliu',
							label: mw.msg( 'rcfilters-filter-registered-label' ),
							description: mw.msg( 'rcfilters-filter-registered-description' )
						},
						{
							name: 'hideanon',
							label: mw.msg( 'rcfilters-filter-unregistered-label' ),
							description: mw.msg( 'rcfilters-filter-unregistered-description' )
						}
					]
				},
				userExpLevel: {
					title: mw.msg( 'rcfilters-filtergroup-userExpLevel' ),
					// Type 'string_options' means that the group is evaluated by
					// string values separated by comma; for example, param=opt1,opt2
					// If all options are selected they are replaced by the term "all".
					// The filters are the values for the parameter defined by the group.
					// ** In this case, the parameter name is the group name. **
					type: 'string_options',
					separator: ',',
					filters: [
						{
							name: 'newcomer',
							label: mw.msg( 'rcfilters-filter-userExpLevel-newcomer-label' ),
							description: mw.msg( 'rcfilters-filter-userExpLevel-newcomer-description' )
						},
						{
							name: 'learner',
							label: mw.msg( 'rcfilters-filter-userExpLevel-learner-label' ),
							description: mw.msg( 'rcfilters-filter-userExpLevel-learner-description' )
						},
						{
							name: 'experienced',
							label: mw.msg( 'rcfilters-filter-userExpLevel-experienced-label' ),
							description: mw.msg( 'rcfilters-filter-userExpLevel-experienced-description' )
						}
					]
				},
				authorship: {
					title: mw.msg( 'rcfilters-filtergroup-authorship' ),
					// Type 'send_unselected_if_any' means that the controller will go over
					// all unselected filters in the group and use their parameters
					// as truthy in the query string.
					// This is to handle the "negative" filters. We are showing users
					// a positive message ("Show xxx") but the filters themselves are
					// based on "hide YYY". The purpose of this is to correctly map
					// the functionality to the UI, whether we are dealing with 2
					// parameters in the group or more.
					type: 'send_unselected_if_any',
					filters: [
						{
							name: 'hidemyself',
							label: mw.msg( 'rcfilters-filter-editsbyself-label' ),
							description: mw.msg( 'rcfilters-filter-editsbyself-description' )
						},
						{
							name: 'hidebyothers',
							label: mw.msg( 'rcfilters-filter-editsbyother-label' ),
							description: mw.msg( 'rcfilters-filter-editsbyother-description' )
						}
					]
				},
				automated: {
					title: mw.msg( 'rcfilters-filtergroup-automated' ),
					type: 'send_unselected_if_any',
					filters: [
						{
							name: 'hidebots',
							label: mw.msg( 'rcfilters-filter-bots-label' ),
							description: mw.msg( 'rcfilters-filter-bots-description' ),
							'default': true
						},
						{
							name: 'hidehumans',
							label: mw.msg( 'rcfilters-filter-humans-label' ),
							description: mw.msg( 'rcfilters-filter-humans-description' ),
							'default': false
						}
					]
				},
				significance: {
					title: mw.msg( 'rcfilters-filtergroup-significance' ),
					type: 'send_unselected_if_any',
					filters: [
						{
							name: 'hideminor',
							label: mw.msg( 'rcfilters-filter-minor-label' ),
							description: mw.msg( 'rcfilters-filter-minor-description' )
						},
						{
							name: 'hidemajor',
							label: mw.msg( 'rcfilters-filter-major-label' ),
							description: mw.msg( 'rcfilters-filter-major-description' )
						}
					]
				},
				changetype: {
					title: mw.msg( 'rcfilters-filtergroup-changetype' ),
					type: 'send_unselected_if_any',
					filters: [
						{
							name: 'hidepageedits',
							label: mw.msg( 'rcfilters-filter-pageedits-label' ),
							description: mw.msg( 'rcfilters-filter-pageedits-description' ),
							'default': false
						},
						{
							name: 'hidenewpages',
							label: mw.msg( 'rcfilters-filter-newpages-label' ),
							description: mw.msg( 'rcfilters-filter-newpages-description' ),
							'default': false
						},
						{
							name: 'hidecategorization',
							label: mw.msg( 'rcfilters-filter-categorization-label' ),
							description: mw.msg( 'rcfilters-filter-categorization-description' ),
							'default': true
						},
						{
							name: 'hidelog',
							label: mw.msg( 'rcfilters-filter-logactions-label' ),
							description: mw.msg( 'rcfilters-filter-logactions-description' ),
							'default': false
						}
					]
				}
			} );

			$( '.rcoptions' ).before( widget.$element );
			widget2 = new mw.rcfilters.ui.RoanFilterCapsuleMultiselectWidget( controller, model );
			widget.$element.after( widget2.$element );

			// Initialize values
			controller.initialize();

			$( '.rcoptions form' ).submit( function () {
				var $form = $( this );

				// Get current filter values
				$.each( model.getParametersFromFilters(), function ( paramName, paramValue ) {
					var $existingInput = $form.find( 'input[name=' + paramName + ']' );
					// Check if the hidden input already exists
					// This happens if the parameter was already given
					// on load
					if ( $existingInput.length ) {
						// Update the value
						$existingInput.val( paramValue );
					} else {
						// Append hidden fields with filter values
						$form.append(
							$( '<input>' )
								.attr( 'type', 'hidden' )
								.attr( 'name', paramName )
								.val( paramValue )
						);
					}
				} );

				// Continue the submission process
				return true;
			} );
		}
	};

	$( rcfilters.init );

	module.exports = rcfilters;

}( mediaWiki, jQuery ) );
