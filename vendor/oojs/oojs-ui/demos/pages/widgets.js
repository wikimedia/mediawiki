OO.ui.Demo.static.pages.widgets = function ( demo ) {
	var styles, states, buttonStyleShowcaseWidget, fieldsets,
		capsuleWithPopup, capsulePopupWidget,
		$demo = demo.$element;

	/**
	 * Draggable group widget containing drag/drop items
	 * @param {Object} [config] Configuration options
	 */
	function DragDropGroupWidget( config ) {
		// Configuration initialization
		config = config || {};

		// Parent constructor
		DragDropGroupWidget.parent.call( this, config );

		// Mixin constructors
		OO.ui.mixin.DraggableGroupElement.call( this, $.extend( {}, config, { $group: this.$element } ) );

		// Respond to reorder event
		this.connect( this, { reorder: 'onReorder' } );
	}
	/* Setup */
	OO.inheritClass( DragDropGroupWidget, OO.ui.Widget );
	OO.mixinClass( DragDropGroupWidget, OO.ui.mixin.DraggableGroupElement );

	/**
	 * Respond to order event
	 * @param {OO.ui.mixin.DraggableElement} item Reordered item
	 * @param {number} newIndex New index
	 */
	DragDropGroupWidget.prototype.onReorder = function ( item, newIndex ) {
		this.addItems( [ item ], newIndex );
	};

	/**
	 * Drag/drop items
	 * @param {Object} [config] Configuration options
	 */
	function DragDropItemWidget( config ) {
		// Configuration initialization
		config = config || {};

		// Parent constructor
		DragDropItemWidget.parent.call( this, config );

		// Mixin constructors
		OO.ui.mixin.DraggableElement.call( this, config );
	}
	/* Setup */
	OO.inheritClass( DragDropItemWidget, OO.ui.OptionWidget );
	OO.mixinClass( DragDropItemWidget, OO.ui.mixin.DraggableElement );

	/**
	 * Demo for LookupElement.
	 * @extends OO.ui.TextInputWidget
	 * @mixins OO.ui.mixin.LookupElement
	 */
	function NumberLookupTextInputWidget() {
		// Parent constructor
		OO.ui.TextInputWidget.call( this, { validate: 'integer' } );
		// Mixin constructors
		OO.ui.mixin.LookupElement.call( this );
	}
	OO.inheritClass( NumberLookupTextInputWidget, OO.ui.TextInputWidget );
	OO.mixinClass( NumberLookupTextInputWidget, OO.ui.mixin.LookupElement );

	/**
	 * @inheritdoc
	 */
	NumberLookupTextInputWidget.prototype.getLookupRequest = function () {
		var
			value = this.getValue(),
			deferred = $.Deferred(),
			delay = 500 + Math.floor( Math.random() * 500 );

		this.isValid().done( function ( valid ) {
			if ( valid ) {
				// Resolve with results after a faked delay
				setTimeout( function () {
					deferred.resolve( [ value * 1, value * 2, value * 3, value * 4, value * 5 ] );
				}, delay );
			} else {
				// No results when the input contains invalid content
				deferred.resolve( [] );
			}
		} );

		return deferred.promise( { abort: function () {} } );
	};

	/**
	 * @inheritdoc
	 */
	NumberLookupTextInputWidget.prototype.getLookupCacheDataFromResponse = function ( response ) {
		return response || [];
	};

	/**
	 * @inheritdoc
	 */
	NumberLookupTextInputWidget.prototype.getLookupMenuOptionsFromData = function ( data ) {
		var
			items = [],
			i, number;
		for ( i = 0; i < data.length; i++ ) {
			number = String( data[ i ] );
			items.push( new OO.ui.MenuOptionWidget( {
				data: number,
				label: number
			} ) );
		}

		return items;
	};

	function UnsupportedSelectFileWidget() {
		// Parent constructor
		UnsupportedSelectFileWidget.parent.apply( this, arguments );
	}
	OO.inheritClass( UnsupportedSelectFileWidget, OO.ui.SelectFileWidget );
	UnsupportedSelectFileWidget.static.isSupported = function () {
		return false;
	};

	capsulePopupWidget = new OO.ui.NumberInputWidget( {
		isInteger: true
	} );
	capsulePopupWidget.connect( capsulePopupWidget, {
		enter: function () {
			if ( !isNaN( this.getNumericValue() ) ) {
				capsuleWithPopup.addItemsFromData( [ this.getNumericValue() ] );
				this.setValue( '' );
			}
			return false;
		}
	} );
	capsulePopupWidget.$element.css( 'vertical-align', 'middle' );
	capsuleWithPopup = new OO.ui.CapsuleMultiSelectWidget( {
		allowArbitrary: true,
		popup: { $content: capsulePopupWidget.$element }
	} );

	styles = [
		{},
		{
			flags: [ 'progressive' ]
		},
		{
			flags: [ 'constructive' ]
		},
		{
			flags: [ 'destructive' ]
		},
		{
			flags: [ 'primary', 'progressive' ]
		},
		{
			flags: [ 'primary', 'constructive' ]
		},
		{
			flags: [ 'primary', 'destructive' ]
		}
	];
	states = [
		{
			label: 'Button'
		},
		{
			label: 'Button',
			icon: 'tag'
		},
		{
			label: 'Button',
			icon: 'tag',
			indicator: 'down'
		},
		{
			icon: 'tag',
			title: 'Title text'
		},
		{
			indicator: 'down'
		},
		{
			icon: 'tag',
			indicator: 'down'
		},
		{
			label: 'Button',
			disabled: true
		},
		{
			icon: 'tag',
			title: 'Title text',
			disabled: true
		},
		{
			indicator: 'down',
			disabled: true
		}
	];
	buttonStyleShowcaseWidget = new OO.ui.Widget();
	$.each( styles, function ( i, style ) {
		$.each( states, function ( j, state ) {
			buttonStyleShowcaseWidget.$element.append(
				new OO.ui.ButtonWidget( $.extend( {}, style, state ) ).$element
			);
		} );
		buttonStyleShowcaseWidget.$element.append( $( '<br>' ) );
	} );

	fieldsets = [
		new OO.ui.FieldsetLayout( {
			label: 'Simple buttons',
			items: [
				new OO.ui.FieldLayout(
					new OO.ui.ButtonWidget( { label: 'Normal' } ),
					{
						label: 'ButtonWidget (normal)\u200E',
						align: 'top'
					}
				),
				new OO.ui.FieldLayout(
					new OO.ui.ButtonWidget( {
						label: 'Progressive',
						flags: [ 'progressive' ]
					} ),
					{
						label: 'ButtonWidget (progressive)\u200E',
						align: 'top'
					}
				),
				new OO.ui.FieldLayout(
					new OO.ui.ButtonWidget( {
						label: 'Constructive',
						flags: [ 'constructive' ]
					} ),
					{
						label: 'ButtonWidget (constructive)\u200E',
						align: 'top'
					}
				),
				new OO.ui.FieldLayout(
					new OO.ui.ButtonWidget( {
						label: 'Destructive',
						flags: [ 'destructive' ]
					} ),
					{
						label: 'ButtonWidget (destructive)\u200E',
						align: 'top'
					}
				),
				new OO.ui.FieldLayout(
					new OO.ui.ButtonWidget( {
						label: 'Primary progressive',
						flags: [ 'primary', 'progressive' ]
					} ),
					{
						label: 'ButtonWidget (primary, progressive)\u200E',
						align: 'top'
					}
				),
				new OO.ui.FieldLayout(
					new OO.ui.ButtonWidget( {
						label: 'Primary constructive',
						flags: [ 'primary', 'constructive' ]
					} ),
					{
						label: 'ButtonWidget (primary, constructive)\u200E',
						align: 'top'
					}
				),
				new OO.ui.FieldLayout(
					new OO.ui.ButtonWidget( {
						label: 'Primary destructive',
						flags: [ 'primary', 'destructive' ]
					} ),
					{
						label: 'ButtonWidget (primary, destructive)\u200E',
						align: 'top'
					}
				),
				new OO.ui.FieldLayout(
					new OO.ui.ButtonWidget( {
						label: 'Disabled',
						disabled: true
					} ),
					{
						label: 'ButtonWidget (disabled)\u200E',
						align: 'top'
					}
				),
				new OO.ui.FieldLayout(
					new OO.ui.ButtonWidget( {
						label: 'Constructive',
						flags: [ 'constructive' ],
						disabled: true
					} ),
					{
						label: 'ButtonWidget (constructive, disabled)\u200E',
						align: 'top'
					}
				),
				new OO.ui.FieldLayout(
					new OO.ui.ButtonWidget( {
						label: 'Constructive',
						icon: 'tag',
						flags: [ 'constructive' ],
						disabled: true
					} ),
					{
						label: 'ButtonWidget (constructive, icon, disabled)\u200E',
						align: 'top'
					}
				),
				new OO.ui.FieldLayout(
					new OO.ui.ButtonWidget( {
						label: 'Icon',
						icon: 'tag'
					} ),
					{
						label: 'ButtonWidget (icon)\u200E',
						align: 'top'
					}
				),
				new OO.ui.FieldLayout(
					new OO.ui.ButtonWidget( {
						label: 'Icon',
						icon: 'tag',
						flags: [ 'progressive' ]
					} ),
					{
						label: 'ButtonWidget (icon, progressive)\u200E',
						align: 'top'
					}
				),
				new OO.ui.FieldLayout(
					new OO.ui.ButtonWidget( {
						label: 'Indicator',
						indicator: 'down'
					} ),
					{
						label: 'ButtonWidget (indicator)\u200E',
						align: 'top'
					}
				),
				new OO.ui.FieldLayout(
					new OO.ui.ButtonWidget( {
						label: 'Indicator',
						indicator: 'down',
						flags: [ 'constructive' ]
					} ),
					{
						label: 'ButtonWidget (indicator, constructive)\u200E',
						align: 'top'
					}
				),
				new OO.ui.FieldLayout(
					new OO.ui.ButtonWidget( {
						framed: false,
						icon: 'help',
						title: 'Icon only'
					} ),
					{
						label: 'ButtonWidget (icon only)\u200E',
						align: 'top'
					}
				),
				new OO.ui.FieldLayout(
					new OO.ui.ButtonWidget( {
						framed: false,
						indicator: 'alert',
						title: 'Indicator only'
					} ),
					{
						label: 'ButtonWidget (indicator only)\u200E',
						align: 'top'
					}
				),
				new OO.ui.FieldLayout(
					new OO.ui.ButtonWidget( {
						icon: 'help',
						title: 'Icon only, framed'
					} ),
					{
						label: 'ButtonWidget (icon only, framed)\u200E',
						align: 'top'
					}
				),
				new OO.ui.FieldLayout(
					new OO.ui.ButtonWidget( {
						indicator: 'alert',
						title: 'Indicator only, framed'
					} ),
					{
						label: 'ButtonWidget (indicator only, framed)\u200E',
						align: 'top'
					}
				),
				new OO.ui.FieldLayout(
					new OO.ui.ButtonWidget( {
						framed: false,
						icon: 'tag',
						label: 'Labeled'
					} ),
					{
						label: 'ButtonWidget (frameless)\u200E',
						align: 'top'
					}
				),
				new OO.ui.FieldLayout(
					new OO.ui.ButtonWidget( {
						framed: false,
						flags: [ 'progressive' ],
						icon: 'check',
						label: 'Progressive'
					} ),
					{
						label: 'ButtonWidget (frameless, progressive)\u200E',
						align: 'top'
					}
				),
				new OO.ui.FieldLayout(
					new OO.ui.ButtonWidget( {
						framed: false,
						flags: [ 'warning' ],
						icon: 'alert',
						label: 'Warning'
					} ),
					{
						label: 'ButtonWidget (frameless, warning)\u200E',
						align: 'top'
					}
				),
				new OO.ui.FieldLayout(
					new OO.ui.ButtonWidget( {
						framed: false,
						flags: [ 'destructive' ],
						icon: 'remove',
						label: 'Destructive'
					} ),
					{
						label: 'ButtonWidget (frameless, destructive)\u200E',
						align: 'top'
					}
				),
				new OO.ui.FieldLayout(
					new OO.ui.ButtonWidget( {
						framed: false,
						flags: [ 'constructive' ],
						icon: 'add',
						label: 'Constructive'
					} ),
					{
						label: 'ButtonWidget (frameless, constructive)\u200E',
						align: 'top'
					}
				),
				new OO.ui.FieldLayout(
					new OO.ui.ButtonWidget( {
						framed: false,
						icon: 'tag',
						label: 'Disabled',
						disabled: true
					} ),
					{
						label: 'ButtonWidget (frameless, disabled)\u200E',
						align: 'top'
					}
				),
				new OO.ui.FieldLayout(
					new OO.ui.ButtonWidget( {
						framed: false,
						flags: [ 'constructive' ],
						icon: 'tag',
						label: 'Constructive',
						disabled: true
					} ),
					{
						label: 'ButtonWidget (frameless, constructive, disabled)\u200E',
						align: 'top'
					}
				),
				new OO.ui.FieldLayout(
					new OO.ui.ButtonWidget( {
						framed: false,
						icon: 'tag',
						indicator: 'down',
						label: 'Labeled'
					} ),
					{
						label: 'ButtonWidget (frameless, indicator)\u200E',
						align: 'top'
					}
				),
				new OO.ui.FieldLayout(
					new OO.ui.ButtonWidget( {
						label: 'AccessKeyed',
						accessKey: 'k'
					} ),
					{
						label: 'ButtonWidget (with accesskey k)\u200E',
						align: 'top'
					}
				)
			]
		} ),
		new OO.ui.FieldsetLayout( {
			label: 'Button sets',
			items: [
				new OO.ui.FieldLayout(
					new OO.ui.ButtonGroupWidget( {
						items: [
							new OO.ui.ButtonWidget( {
								icon: 'tag',
								label: 'One'
							} ),
							new OO.ui.ButtonWidget( {
								label: 'Two'
							} ),
							new OO.ui.ButtonWidget( {
								indicator: 'required',
								label: 'Three'
							} )
						]
					} ),
					{
						label: 'ButtonGroupWidget',
						align: 'top'
					}
				),
				new OO.ui.FieldLayout(
					new OO.ui.ButtonSelectWidget( {
						items: [
							new OO.ui.ButtonOptionWidget( {
								data: 'b',
								icon: 'tag',
								label: 'One'
							} ),
							new OO.ui.ButtonOptionWidget( {
								data: 'c',
								label: 'Two'
							} ),
							new OO.ui.ButtonOptionWidget( {
								data: 'd',
								indicator: 'required',
								label: 'Three'
							} )
						]
					} ),
					{
						label: 'ButtonSelectWidget',
						align: 'top'
					}
				),
				new OO.ui.FieldLayout(
					new OO.ui.ButtonSelectWidget( {
						disabled: true,
						items: [
							new OO.ui.ButtonOptionWidget( {
								data: 'b',
								icon: 'tag',
								label: 'One'
							} ),
							new OO.ui.ButtonOptionWidget( {
								data: 'c',
								label: 'Two'
							} ),
							new OO.ui.ButtonOptionWidget( {
								data: 'd',
								indicator: 'required',
								label: 'Three'
							} )
						]
					} ),
					{
						label: 'ButtonSelectWidget (disabled)\u200E',
						align: 'top'
					}
				),
				new OO.ui.FieldLayout(
					new OO.ui.ButtonSelectWidget( {
						items: [
							new OO.ui.ButtonOptionWidget( {
								data: 'b',
								icon: 'tag',
								label: 'One',
								disabled: true
							} ),
							new OO.ui.ButtonOptionWidget( {
								data: 'c',
								label: 'Two'
							} ),
							new OO.ui.ButtonOptionWidget( {
								data: 'd',
								indicator: 'required',
								label: 'Three'
							} )
						]
					} ),
					{
						label: 'ButtonSelectWidget (disabled items)\u200E',
						align: 'top'
					}
				)
			]
		} ),
		new OO.ui.FieldsetLayout( {
			label: 'Button style showcase',
			items: [
				new OO.ui.FieldLayout(
					buttonStyleShowcaseWidget,
					{
						align: 'top'
					}
				)
			]
		} ),
		new OO.ui.FieldsetLayout( {
			label: 'Form widgets',
			items: [
				new OO.ui.FieldLayout(
					new OO.ui.CheckboxInputWidget( {
						selected: true
					} ),
					{
						align: 'inline',
						label: 'CheckboxInputWidget'
					}
				),
				new OO.ui.FieldLayout(
					new OO.ui.CheckboxInputWidget( {
						selected: true,
						disabled: true
					} ),
					{
						align: 'inline',
						label: 'CheckboxInputWidget (disabled)\u200E'
					}
				),
				new OO.ui.FieldLayout(
					new OO.ui.RadioInputWidget( {
						name: 'oojs-ui-radio-demo'
					} ),
					{
						align: 'inline',
						label: 'Connected RadioInputWidget #1'
					}
				),
				new OO.ui.FieldLayout(
					new OO.ui.RadioInputWidget( {
						name: 'oojs-ui-radio-demo',
						selected: true
					} ),
					{
						align: 'inline',
						label: 'Connected RadioInputWidget #2'
					}
				),
				new OO.ui.FieldLayout(
					new OO.ui.RadioInputWidget( {
						selected: true,
						disabled: true
					} ),
					{
						align: 'inline',
						label: 'RadioInputWidget (disabled)\u200E'
					}
				),
				new OO.ui.FieldLayout(
					new OO.ui.RadioSelectWidget( {
						items: [
							new OO.ui.RadioOptionWidget( {
								data: 'cat',
								label: 'Cat'
							} ),
							new OO.ui.RadioOptionWidget( {
								data: 'dog',
								label: 'Dog'
							} ),
							new OO.ui.RadioOptionWidget( {
								data: 'goldfish',
								label: 'Goldfish',
								disabled: true
							} )
						]
					} ),
					{
						align: 'top',
						label: 'RadioSelectWidget'
					}
				),
				new OO.ui.FieldLayout(
					new OO.ui.RadioSelectInputWidget( {
						value: 'dog',
						options: [
							{
								data: 'cat',
								label: 'Cat'
							},
							{
								data: 'dog',
								label: 'Dog'
							},
							{
								data: 'goldfish',
								label: 'Goldfish'
							}
						]
					} ),
					{
						align: 'top',
						label: 'RadioSelectInputWidget'
					}
				),
				new OO.ui.FieldLayout(
					new OO.ui.NumberInputWidget(),
					{
						label: 'NumberInputWidget',
						align: 'top'
					}
				),
				new OO.ui.FieldLayout(
					new OO.ui.NumberInputWidget( { min: 1, max: 5, isInteger: true } ),
					{
						label: 'NumberInputWidget (1–5, ints only)',
						align: 'top'
					}
				),
				new OO.ui.FieldLayout(
					new OO.ui.NumberInputWidget( { min: 0, max: 1, step: 0.1, pageStep: 0.25 } ),
					{
						label: 'NumberInputWidget (0–1, step by .1, page by .25)',
						align: 'top'
					}
				),
				new OO.ui.FieldLayout(
					new OO.ui.ToggleSwitchWidget(),
					{
						label: 'ToggleSwitchWidget',
						align: 'top'
					}
				),
				new OO.ui.FieldLayout(
					new OO.ui.ToggleSwitchWidget( { disabled: true } ),
					{
						label: 'ToggleSwitchWidget (disabled)\u200E',
						align: 'top'
					}
				),
				new OO.ui.FieldLayout(
					new OO.ui.ToggleSwitchWidget( { disabled: true, value: true } ),
					{
						label: 'ToggleSwitchWidget (disabled, checked)\u200E',
						align: 'top'
					}
				),
				new OO.ui.FieldLayout(
					new OO.ui.ToggleButtonWidget( { label: 'Toggle' } ),
					{
						label: 'ToggleButtonWidget',
						align: 'top'
					}
				),
				new OO.ui.FieldLayout(
					new OO.ui.ToggleButtonWidget( { label: 'Toggle', value: true } ),
					{
						label: 'ToggleButtonWidget (initially active)\u200E',
						align: 'top'
					}
				),
				new OO.ui.FieldLayout(
					new OO.ui.ToggleButtonWidget( { icon: 'next' } ),
					{
						label: 'ToggleButtonWidget (icon only)\u200E',
						align: 'top'
					}
				),
				new OO.ui.FieldLayout(
					new OO.ui.TextInputWidget( { value: 'Text input' } ),
					{
						label: 'TextInputWidget\u200E',
						align: 'top'
					}
				),
				new OO.ui.FieldLayout(
					new OO.ui.TextInputWidget( { icon: 'search' } ),
					{
						label: 'TextInputWidget (icon)\u200E',
						align: 'top'
					}
				),
				new OO.ui.FieldLayout(
					new OO.ui.TextInputWidget( {
						required: true,
						validate: 'non-empty'
					} ),
					{
						label: 'TextInputWidget (required)\u200E',
						align: 'top'
					}
				),
				new OO.ui.FieldLayout(
					new OO.ui.TextInputWidget( {
						validate: function ( value ) {
							return value.length % 2 === 0;
						}
					} ),
					{
						label: 'TextInputWidget (only allows even number of characters)\u200E',
						align: 'top'
					}
				),
				new OO.ui.FieldLayout(
					new OO.ui.TextInputWidget( { placeholder: 'Placeholder' } ),
					{
						label: 'TextInputWidget (placeholder)\u200E',
						align: 'top'
					}
				),
				new OO.ui.FieldLayout(
					new OO.ui.TextInputWidget( { type: 'search' } ),
					{
						label: 'TextInputWidget (type=search)\u200E',
						align: 'top'
					}
				),
				new OO.ui.FieldLayout(
					new OO.ui.TextInputWidget( {
						value: 'Readonly',
						readOnly: true
					} ),
					{
						label: 'TextInputWidget (readonly)\u200E',
						align: 'top'
					}
				),
				new OO.ui.FieldLayout(
					new OO.ui.TextInputWidget( {
						multiline: true,
						value: 'Multiline\nMultiline'
					} ),
					{
						label: 'TextInputWidget (multiline)\u200E',
						align: 'top'
					}
				),
				new OO.ui.FieldLayout(
					new OO.ui.TextInputWidget( {
						multiline: true,
						rows: 15,
						value: 'Multiline\nMultiline'
					} ),
					{
						label: 'TextInputWidget (multiline, rows=15)\u200E',
						align: 'top'
					}
				),
				new OO.ui.FieldLayout(
					new OO.ui.TextInputWidget( {
						multiline: true,
						autosize: true,
						value: 'Autosize\nAutosize\nAutosize\nAutosize'
					} ),
					{
						label: 'TextInputWidget (autosize)\u200E',
						align: 'top'
					}
				),
				new OO.ui.FieldLayout(
					new OO.ui.TextInputWidget( {
						multiline: true,
						rows: 10,
						autosize: true,
						value: 'Autosize\nAutosize\nAutosize\nAutosize'
					} ),
					{
						label: 'TextInputWidget (autosize, rows=10)\u200E',
						align: 'top'
					}
				),
				new OO.ui.FieldLayout(
					new OO.ui.TextInputWidget( {
						icon: 'tag',
						indicator: 'alert',
						value: 'Text input with label',
						label: 'Inline label'
					} ),
					{
						label: 'TextInputWidget (label)\u200E',
						align: 'top'
					}
				),
				new OO.ui.FieldLayout(
					new OO.ui.TextInputWidget( {
						value: 'Disabled',
						icon: 'tag',
						indicator: 'required',
						label: 'Inline label',
						disabled: true
					} ),
					{
						label: 'TextInputWidget (icon, indicator, label, disabled)\u200E',
						align: 'top'
					}
				),
				new OO.ui.FieldLayout(
					new OO.ui.TextInputWidget( {
						value: 'Title attribute',
						title: 'Title attribute with more information about me.'
					} ),
					{
						label: 'TextInputWidget (with title)\u200E',
						align: 'top'
					}
				),
				new OO.ui.FieldLayout(
					new OO.ui.TextInputWidget( {
						value: 'Accesskey A',
						accessKey: 'a'
					} ),
					{
						label: 'TextInputWidget (with Accesskey)\u200E',
						align: 'top'
					}
				),
				new OO.ui.FieldLayout(
					new OO.ui.SelectFileWidget( {} ),
					{
						label: 'SelectFileWidget\u200E',
						align: 'top'
					}
				),
				new OO.ui.FieldLayout(
					new OO.ui.SelectFileWidget( { accept: [ 'image/png', 'image/jpeg' ] } ),
					{
						label: 'SelectFileWidget (accept PNG and JPEG)\u200E',
						align: 'top'
					}
				),
				new OO.ui.FieldLayout(
					new OO.ui.SelectFileWidget( {
						icon: 'tag',
						indicator: 'required'
					} ),
					{
						label: 'SelectFileWidget (icon, indicator)\u200E',
						align: 'top'
					}
				),
				new OO.ui.FieldLayout(
					new OO.ui.SelectFileWidget( {
						icon: 'tag',
						indicator: 'required',
						disabled: true
					} ),
					{
						label: 'SelectFileWidget (disabled)\u200E',
						align: 'top'
					}
				),
				new OO.ui.FieldLayout(
					new UnsupportedSelectFileWidget(),
					{
						label: 'SelectFileWidget (no browser support)\u200E',
						align: 'top'
					}
				),
				new OO.ui.FieldLayout(
					new OO.ui.SelectFileWidget( { showDropTarget: true } ),
					{
						label: 'SelectFileWidget (with drop target)\u200E',
						align: 'top'
					}
				),
				new OO.ui.FieldLayout(
					new OO.ui.SelectFileWidget( {
						showDropTarget: true,
						disabled: true
					} ),
					{
						label: 'SelectFileWidget (with drop target, disabled)\u200E',
						align: 'top'
					}
				),
				new OO.ui.FieldLayout(
					new UnsupportedSelectFileWidget( {
						showDropTarget: true
					} ),
					{
						label: 'SelectFileWidget (with drop target, no browser support)\u200E',
						align: 'top'
					}
				),
				new OO.ui.FieldLayout(
					new OO.ui.DropdownWidget( {
						label: 'Select one',
						menu: {
							items: [
								new OO.ui.MenuOptionWidget( {
									data: 'a',
									label: 'First'
								} ),
								new OO.ui.MenuOptionWidget( {
									data: 'b',
									label: 'Second',
									indicator: 'required'
								} ),
								new OO.ui.MenuOptionWidget( {
									data: 'c',
									label: 'Third'
								} ),
								new OO.ui.MenuOptionWidget( {
									data: 'c',
									label: 'The fourth option has a long label'
								} ),
								new OO.ui.MenuOptionWidget( {
									data: 'd',
									label: 'Fifth'
								} )
							]
						}
					} ),
					{
						label: 'DropdownWidget',
						align: 'top'
					}
				),
				new OO.ui.FieldLayout(
					new OO.ui.DropdownWidget( {
						label: 'Select one',
						icon: 'tag',
						menu: {
							items: [
								new OO.ui.MenuOptionWidget( {
									data: 'a',
									label: 'First'
								} ),
								new OO.ui.MenuOptionWidget( {
									data: 'b',
									label: 'Disabled second option',
									indicator: 'required',
									disabled: true
								} ),
								new OO.ui.MenuOptionWidget( {
									data: 'c',
									label: 'Third'
								} ),
								new OO.ui.MenuOptionWidget( {
									data: 'd',
									label: 'Disabled fourth option with long label',
									disabled: true
								} ),
								new OO.ui.MenuOptionWidget( {
									data: 'c',
									label: 'Third'
								} )
							]
						}
					} ),
					{
						label: 'DropdownWidget (disabled options)\u200E',
						align: 'top'
					}
				),
				new OO.ui.FieldLayout(
					new OO.ui.DropdownWidget( {
						label: 'Select one',
						disabled: true,
						menu: {
							items: [
								new OO.ui.MenuOptionWidget( {
									data: 'a',
									label: 'First'
								} ),
								new OO.ui.MenuOptionWidget( {
									data: 'b',
									label: 'Second'
								} ),
								new OO.ui.MenuOptionWidget( {
									data: 'c',
									label: 'Third'
								} ),
								new OO.ui.MenuOptionWidget( {
									data: 'd',
									label: 'Fourth'
								} )
							]
						}
					} ),
					{
						label: 'DropdownWidget (disabled)\u200E',
						align: 'top'
					}
				),
				new OO.ui.FieldLayout(
					new OO.ui.DropdownInputWidget( {
						options: [
							{
								data: 'a',
								label: 'First'
							},
							{
								data: 'b',
								label: 'Second'
							},
							{
								data: 'c',
								label: 'Third'
							}
						],
						value: 'b',
						title: 'Select an item'
					} ),
					{
						label: 'DropdownInputWidget',
						align: 'top'
					}
				),
				new OO.ui.FieldLayout(
					new OO.ui.ComboBoxWidget( {
						menu: {
							items: [
								new OO.ui.MenuOptionWidget( { data: 'asd', label: 'Label for asd' } ),
								new OO.ui.MenuOptionWidget( { data: 'fgh', label: 'Label for fgh' } ),
								new OO.ui.MenuOptionWidget( { data: 'jkl', label: 'Label for jkl' } ),
								new OO.ui.MenuOptionWidget( { data: 'zxc', label: 'Label for zxc' } ),
								new OO.ui.MenuOptionWidget( { data: 'vbn', label: 'Label for vbn' } )
							]
						}
					} ),
					{
						label: 'ComboBoxWidget',
						align: 'top'
					}
				),
				new OO.ui.FieldLayout(
					new OO.ui.ComboBoxWidget( {
						disabled: true,
						menu: {
							items: [
								new OO.ui.MenuOptionWidget( { data: 'asd', label: 'Label for asd' } ),
								new OO.ui.MenuOptionWidget( { data: 'fgh', label: 'Label for fgh' } ),
								new OO.ui.MenuOptionWidget( { data: 'jkl', label: 'Label for jkl' } ),
								new OO.ui.MenuOptionWidget( { data: 'zxc', label: 'Label for zxc' } ),
								new OO.ui.MenuOptionWidget( { data: 'vbn', label: 'Label for vbn' } )
							]
						}
					} ),
					{
						label: 'ComboBoxWidget (disabled)\u200E',
						align: 'top'
					}
				),
				new OO.ui.FieldLayout(
					new OO.ui.ComboBoxWidget(),
					{
						label: 'ComboBoxWidget (empty)\u200E',
						align: 'top'
					}
				),
				new OO.ui.FieldLayout(
					new OO.ui.CapsuleMultiSelectWidget( {
						menu: {
							items: [
								new OO.ui.MenuOptionWidget( { data: 'abc', label: 'Label for abc' } ),
								new OO.ui.MenuOptionWidget( { data: 'asd', label: 'Label for asd' } ),
								new OO.ui.MenuOptionWidget( { data: 'jkl', label: 'Label for jkl' } ),
								new OO.ui.MenuOptionWidget( { data: 'jjj', label: 'Label for jjj' } ),
								new OO.ui.MenuOptionWidget( { data: 'zxc', label: 'Label for zxc' } ),
								new OO.ui.MenuOptionWidget( { data: 'vbn', label: 'Label for vbn' } )
							]
						}
					} ),
					{
						label: 'CapsuleMultiSelectWidget',
						align: 'top'
					}
				),
				new OO.ui.FieldLayout(
					new OO.ui.CapsuleMultiSelectWidget( {
						allowArbitrary: true,
						icon: 'tag',
						indicator: 'required',
						menu: {
							items: [
								new OO.ui.MenuOptionWidget( { data: 'abc', label: 'Label for abc' } ),
								new OO.ui.MenuOptionWidget( { data: 'asd', label: 'Label for asd' } ),
								new OO.ui.MenuOptionWidget( { data: 'jkl', label: 'Label for jkl' } ),
								new OO.ui.MenuOptionWidget( { data: 'jjj', label: 'Label for jjj' } ),
								new OO.ui.MenuOptionWidget( { data: 'zxc', label: 'Label for zxc' } ),
								new OO.ui.MenuOptionWidget( { data: 'vbn', label: 'Label for vbn' } )
							]
						}
					} ),
					{
						label: 'CapsuleMultiSelectWidget (icon, indicator, arbitrary values allowed)',
						align: 'top'
					}
				),
				new OO.ui.FieldLayout(
					new OO.ui.CapsuleMultiSelectWidget( {
						disabled: true,
						icon: 'tag',
						indicator: 'required',
						values: [ 'jkl', 'zxc' ],
						menu: {
							items: [
								new OO.ui.MenuOptionWidget( { data: 'abc', label: 'Label for abc' } ),
								new OO.ui.MenuOptionWidget( { data: 'asd', label: 'Label for asd' } ),
								new OO.ui.MenuOptionWidget( { data: 'jkl', label: 'Label for jkl' } ),
								new OO.ui.MenuOptionWidget( { data: 'jjj', label: 'Label for jjj' } ),
								new OO.ui.MenuOptionWidget( { data: 'zxc', label: 'Label for zxc' } ),
								new OO.ui.MenuOptionWidget( { data: 'vbn', label: 'Label for vbn' } )
							]
						}
					} ),
					{
						label: 'CapsuleMultiSelectWidget (disabled)\u200E',
						align: 'top'
					}
				),
				new OO.ui.FieldLayout(
					new OO.ui.CapsuleMultiSelectWidget( {
						menu: {
							items: [
								new OO.ui.MenuOptionWidget( { data: 'abc', label: 'Label for abc' } ),
								new OO.ui.MenuOptionWidget( { data: 'asd', label: 'Label for asd' } ),
								new OO.ui.MenuOptionWidget( { data: 'jkl', label: 'Label for jkl' } )
							]
						}
					} ).addItemsFromData( [ 'abc', 'asd' ] ),
					{
						label: 'CapsuleMultiSelectWidget (initially selected)\u200E',
						align: 'top'
					}
				),
				new OO.ui.FieldLayout(
					capsuleWithPopup,
					{
						label: 'CapsuleMultiSelectWidget with NumberInputWidget popup\u200E',
						align: 'top'
					}
				),
				new OO.ui.FieldLayout(
					new OO.ui.ButtonInputWidget( {
						label: 'Submit the form',
						type: 'submit'
					} ),
					{
						align: 'top',
						label: 'ButtonInputWidget'
					}
				),
				new OO.ui.FieldLayout(
					new OO.ui.ButtonInputWidget( {
						label: 'Submit the form',
						type: 'submit',
						useInputTag: true
					} ),
					{
						align: 'top',
						label: 'ButtonInputWidget (using <input/>)\u200E'
					}
				),
				new OO.ui.FieldLayout(
					new OO.ui.ButtonInputWidget( {
						framed: false,
						label: 'Submit the form',
						type: 'submit'
					} ),
					{
						align: 'top',
						label: 'ButtonInputWidget (frameless)\u200E'
					}
				),
				new OO.ui.FieldLayout(
					new OO.ui.ButtonInputWidget( {
						framed: false,
						label: 'Submit the form',
						type: 'submit',
						useInputTag: true
					} ),
					{
						align: 'top',
						label: 'ButtonInputWidget (frameless, using <input/>)\u200E'
					}
				)
			]
		} ),
		new OO.ui.FieldsetLayout( {
			label: 'HorizontalLayout',
			items: [
				new OO.ui.FieldLayout(
					new OO.ui.Widget( {
						content: [ new OO.ui.HorizontalLayout( {
							items: [
								new OO.ui.ButtonWidget( { label: 'Button' } ),
								new OO.ui.ButtonGroupWidget( { items: [
									new OO.ui.ToggleButtonWidget( { label: 'A' } ),
									new OO.ui.ToggleButtonWidget( { label: 'B' } )
								] } ),
								new OO.ui.ButtonInputWidget( { label: 'ButtonInput' } ),
								new OO.ui.TextInputWidget( { value: 'TextInput' } ),
								new OO.ui.DropdownInputWidget( { options: [
									{
										label: 'DropdownInput',
										data: null
									}
								] } ),
								new OO.ui.CheckboxInputWidget( { selected: true } ),
								new OO.ui.RadioInputWidget( { selected: true } ),
								new OO.ui.LabelWidget( { label: 'Label' } )
							]
						} ) ]
					} ),
					{
						label: 'Multiple widgets shown as a single line, ' +
							'as used in compact forms or in parts of a bigger widget.',
						align: 'top'
					}
				)
			]
		} ),
		new OO.ui.FieldsetLayout( {
			label: 'Draggable',
			items: [
				new OO.ui.FieldLayout(
					new DragDropGroupWidget( {
						orientation: 'horizontal',
						items: [
							new DragDropItemWidget( {
								data: 'item1',
								label: 'Item 1'
							} ),
							new DragDropItemWidget( {
								data: 'item2',
								label: 'Item 2'
							} ),
							new DragDropItemWidget( {
								data: 'item3',
								label: 'Item 3'
							} ),
							new DragDropItemWidget( {
								data: 'item4',
								label: 'Item 4'
							} )
						]
					} ),
					{
						label: 'DraggableGroupWidget (horizontal)\u200E',
						align: 'top'
					}
				),
				new OO.ui.FieldLayout(
					new DragDropGroupWidget( {
						items: [
							new DragDropItemWidget( {
								data: 'item1',
								label: 'Item 1'
							} ),
							new DragDropItemWidget( {
								data: 'item2',
								label: 'Item 2'
							} ),
							new DragDropItemWidget( {
								data: 'item3',
								label: 'Item 3'
							} ),
							new DragDropItemWidget( {
								data: 'item4',
								label: 'Item 4'
							} )
						]
					} ),
					{
						label: 'DraggableGroupWidget (vertical)\u200E',
						align: 'top'
					}
				)
			]
		} ),
		new OO.ui.FieldsetLayout( {
			label: 'Other widgets',
			items: [
				new OO.ui.FieldLayout(
					new OO.ui.IconWidget( {
						icon: 'picture',
						title: 'Picture icon'
					} ),
					{
						label: 'IconWidget (normal)\u200E',
						align: 'top'
					}
				),
				new OO.ui.FieldLayout(
					new OO.ui.IconWidget( {
						icon: 'remove',
						flags: 'destructive',
						title: 'Remove icon'
					} ),
					{
						label: 'IconWidget (flagged)\u200E',
						align: 'top'
					}
				),
				new OO.ui.FieldLayout(
					new OO.ui.IconWidget( {
						icon: 'picture',
						title: 'Picture icon',
						disabled: true
					} ),
					{
						label: 'IconWidget (disabled)\u200E',
						align: 'top'
					}
				),
				new OO.ui.FieldLayout(
					new OO.ui.IndicatorWidget( {
						indicator: 'required',
						title: 'Required indicator'
					} ),
					{
						label: 'IndicatorWidget (normal)\u200E',
						align: 'top'
					}
				),
				new OO.ui.FieldLayout(
					new OO.ui.IndicatorWidget( {
						indicator: 'required',
						title: 'Required indicator',
						disabled: true
					} ),
					{
						label: 'IndicatorWidget (disabled)\u200E',
						align: 'top'
					}
				),
				new OO.ui.FieldLayout(
					new OO.ui.LabelWidget( {
						label: 'Label'
					} ),
					{
						label: 'LabelWidget (normal)\u200E',
						align: 'top'
					}
				),
				new OO.ui.FieldLayout(
					new OO.ui.LabelWidget( {
						label: 'Label',
						disabled: true
					} ),
					{
						label: 'LabelWidget (disabled)\u200E',
						align: 'top'
					}
				),
				new OO.ui.FieldLayout(
					new OO.ui.LabelWidget( {
						label: new OO.ui.HtmlSnippet( '<b>Fancy</b> <i>text</i> <u>formatting</u>!' )
					} ),
					{
						label: 'LabelWidget (with html)\u200E',
						align: 'top'
					}
				),
				new OO.ui.FieldLayout(
					new OO.ui.PopupButtonWidget( {
						icon: 'info',
						framed: false,
						popup: {
							head: true,
							label: 'More information',
							$content: $( '<p>Extra information here.</p>' ),
							padded: true,
							align: 'force-left'
						}
					} ),
					{
						label: 'PopupButtonWidget (frameless, with popup head, align: force-left)\u200E',
						align: 'top'
					}
				),
				new OO.ui.FieldLayout(
					new OO.ui.PopupButtonWidget( {
						icon: 'info',
						framed: false,
						popup: {
							head: true,
							label: 'More information',
							$content: $( '<p>Extra information here.</p>' ),
							padded: true,
							align: 'force-right'
						}
					} ),
					{
						label: 'PopupButtonWidget (frameless, with popup head align: force-right)\u200E',
						align: 'top'
					}
				),
				new OO.ui.FieldLayout(
					new OO.ui.PopupButtonWidget( {
						icon: 'info',
						framed: false,
						popup: {
							head: true,
							label: 'More information',
							$content: $( '<p>Extra information here.</p>' ),
							padded: true,
							align: 'backwards'
						}
					} ),
					{
						label: 'PopupButtonWidget (frameless, with popup head align: backwards)\u200E',
						align: 'top'
					}
				),
				new OO.ui.FieldLayout(
					new OO.ui.PopupButtonWidget( {
						icon: 'info',
						framed: false,
						popup: {
							head: true,
							label: 'More information',
							$content: $( '<p>Extra information here.</p>' ),
							padded: true,
							align: 'forwards'
						}
					} ),
					{
						label: 'PopupButtonWidget (frameless, with popup head align: forwards)\u200E',
						align: 'top'
					}
				),
				new OO.ui.FieldLayout(
					new OO.ui.PopupButtonWidget( {
						icon: 'info',
						framed: false,
						popup: {
							head: true,
							label: 'More information',
							$content: $( '<p>Extra information here.</p><ul><li>Item one</li><li>Item two</li><li>Item three</li><li>Item four</li></ul><p>Even more information here whihc might well be clipped off the visible area.</p>' ),
							$footer: $( '<p>And maybe a footer whilst we\'re act it?</p>' ),
							padded: true,
							align: 'forwards'
						}
					} ),
					{
						label: 'PopupButtonWidget (frameless, with popup head and footer, align: forwards)\u200E',
						align: 'top'
					}
				),
				new OO.ui.FieldLayout(
					new OO.ui.PopupButtonWidget( {
						icon: 'menu',
						label: 'Options',
						popup: {
							$content: $( '<p>Additional options here.</p>' ),
							padded: true,
							align: 'left'
						}
					} ),
					{
						label: 'PopupButtonWidget (framed, no popup head)\u200E',
						align: 'top'
					}
				),
				new OO.ui.FieldLayout(
					new NumberLookupTextInputWidget(),
					{
						label: 'LookupElement (try inputting an integer)\u200E',
						align: 'top'
					}
				),
				new OO.ui.FieldLayout(
					new OO.ui.ProgressBarWidget( {
						progress: 33
					} ),
					{
						label: 'Progress bar',
						align: 'top'
					}
				),
				new OO.ui.FieldLayout(
					new OO.ui.ProgressBarWidget( {
						progress: false
					} ),
					{
						label: 'Progress bar (indeterminate)\u200E',
						align: 'top'
					}
				)
			]
		} ),
		new OO.ui.FieldsetLayout( {
			label: 'Field layouts',
			help: 'I am an additional, helpful information. Lorem ipsum dolor sit amet, cibo pri ' +
				'in, duo ex inimicus perpetua complectitur, mel periculis similique at.\u200E',
			items: [
				new OO.ui.FieldLayout(
					new OO.ui.ButtonWidget( {
						label: 'Button'
					} ),
					{
						label: 'FieldLayout with help',
						help: 'I am an additional, helpful information. Lorem ipsum dolor sit amet, cibo pri ' +
							'in, duo ex inimicus perpetua complectitur, mel periculis similique at.\u200E',
						align: 'top'
					}
				),
				new OO.ui.FieldLayout(
					new OO.ui.ButtonWidget( {
						label: 'Button'
					} ),
					{
						label: 'FieldLayout with HTML help',
						help: new OO.ui.HtmlSnippet( '<b>Bold text</b> is helpful!' ),
						align: 'top'
					}
				),
				new OO.ui.FieldLayout(
					new OO.ui.ButtonWidget( {
						label: 'Button'
					} ),
					{
						label: 'FieldLayout with title',
						title: 'Field title text',
						align: 'top'
					}
				),
				new OO.ui.ActionFieldLayout(
					new OO.ui.TextInputWidget(),
					new OO.ui.ButtonWidget( {
						label: 'Button'
					} ),
					{
						label: 'ActionFieldLayout aligned left',
						align: 'left'
					}
				),
				new OO.ui.ActionFieldLayout(
					new OO.ui.TextInputWidget(),
					new OO.ui.ButtonWidget( {
						label: 'Button'
					} ),
					{
						label: 'ActionFieldLayout aligned inline',
						align: 'inline'
					}
				),
				new OO.ui.ActionFieldLayout(
					new OO.ui.TextInputWidget(),
					new OO.ui.ButtonWidget( {
						label: 'Button'
					} ),
					{
						label: 'ActionFieldLayout aligned right',
						align: 'right'
					}
				),
				new OO.ui.ActionFieldLayout(
					new OO.ui.TextInputWidget(),
					new OO.ui.ButtonWidget( {
						label: 'Button'
					} ),
					{
						label: 'ActionFieldLayout aligned top',
						align: 'top'
					}
				),
				new OO.ui.ActionFieldLayout(
					new OO.ui.TextInputWidget(),
					new OO.ui.ButtonWidget( {
						label: 'Button'
					} ),
					{
						label: 'ActionFieldLayout aligned top with help',
						help: 'I am an additional, helpful information. Lorem ipsum dolor sit amet, cibo pri ' +
							'in, duo ex inimicus perpetua complectitur, mel periculis similique at.\u200E',
						align: 'top'
					}
				),
				new OO.ui.ActionFieldLayout(
					new OO.ui.TextInputWidget(),
					new OO.ui.ButtonWidget( {
						label: 'Button'
					} ),
					{
						label: $( '<i>' ).text( 'ActionFieldLayout aligned top with rich text label' ),
						align: 'top'
					}
				),
				new OO.ui.FieldLayout(
					new OO.ui.TextInputWidget( {
						value: ''
					} ),
					{
						label: 'FieldLayout with notice',
						notices: [ 'Please input a number.' ],
						align: 'top'
					}
				),
				new OO.ui.FieldLayout(
					new OO.ui.TextInputWidget( {
						value: 'Foo'
					} ),
					{
						label: 'FieldLayout with error message',
						errors: [ 'The value must be a number.' ],
						align: 'top'
					}
				),
				new OO.ui.FieldLayout(
					new OO.ui.TextInputWidget( {
						value: 'Foo'
					} ),
					{
						label: 'FieldLayout with notice and error message',
						notices: [ 'Please input a number.' ],
						errors: [ 'The value must be a number.' ],
						align: 'top'
					}
				)
			]
		} ),
		new OO.ui.FormLayout( {
			method: 'GET',
			action: 'widgets.php',
			items: [
				new OO.ui.FieldsetLayout( {
					label: 'Form layout',
					items: [
						new OO.ui.FieldLayout(
							new OO.ui.TextInputWidget( {
								name: 'username'
							} ),
							{
								label: 'User name',
								align: 'top'
							}
						),
						new OO.ui.FieldLayout(
							new OO.ui.TextInputWidget( {
								name: 'password',
								type: 'password'
							} ),
							{
								label: 'Password',
								align: 'top'
							}
						),
						new OO.ui.FieldLayout(
							new OO.ui.CheckboxInputWidget( {
								name: 'rememberme',
								selected: true
							} ),
							{
								label: 'Remember me',
								align: 'inline'
							}
						),
						new OO.ui.FieldLayout(
							new OO.ui.ButtonInputWidget( {
								name: 'login',
								label: 'Log in',
								type: 'submit',
								flags: [ 'primary', 'progressive' ],
								icon: 'check'
							} ),
							{
								label: null,
								align: 'top'
							}
						)
					]
				} )
			]
		} )
	];

	$.each( fieldsets, function ( i, fieldsetLayout ) {
		$.each( fieldsetLayout.getItems(), function ( j, fieldLayout ) {
			fieldLayout.$element.append(
				demo.buildConsole( fieldLayout, 'layout', 'widget' )
			);
		} );
	} );

	$demo.append(
		new OO.ui.PanelLayout( {
			expanded: false,
			framed: true
		} ).$element
			.addClass( 'oo-ui-demo-container' )
			.append(
				$( fieldsets.map( function ( fieldset ) { return fieldset.$element[ 0 ]; } ) )
			)
	);
};

OO.ui.Demo.static.defaultPage = 'widgets';
