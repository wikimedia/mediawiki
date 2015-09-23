OO.ui.Demo.static.pages.dialogs = function ( demo ) {
	var i, l, name, openButton, DialogClass, config,
		$demo = demo.$element,
		fieldset = new OO.ui.FieldsetLayout( { label: 'Dialogs' } ),
		windows = {},
		windowManager = new OO.ui.WindowManager();

	function SimpleDialog( config ) {
		SimpleDialog.parent.call( this, config );
	}
	OO.inheritClass( SimpleDialog, OO.ui.Dialog );
	SimpleDialog.static.title = 'Simple dialog';
	SimpleDialog.prototype.initialize = function () {
		var closeButton,
			dialog = this;

		SimpleDialog.parent.prototype.initialize.apply( this, arguments );
		this.content = new OO.ui.PanelLayout( { padded: true, expanded: false } );
		this.content.$element.append( '<p>Dialog content</p>' );

		closeButton = new OO.ui.ButtonWidget( {
			label: OO.ui.msg( 'ooui-dialog-process-dismiss' )
		} );
		closeButton.on( 'click', function () {
			dialog.close();
		} );

		this.content.$element.append( closeButton.$element );
		this.$body.append( this.content.$element );
	};
	SimpleDialog.prototype.getBodyHeight = function () {
		return this.content.$element.outerHeight( true );
	};

	function ProcessDialog( config ) {
		ProcessDialog.parent.call( this, config );
	}
	OO.inheritClass( ProcessDialog, OO.ui.ProcessDialog );
	ProcessDialog.static.title = 'Process dialog';
	ProcessDialog.static.actions = [
		{ action: 'save', label: 'Done', flags: [ 'primary', 'progressive' ] },
		{ action: 'cancel', label: 'Cancel', flags: 'safe' },
		{ action: 'other', label: 'Other', flags: 'other' }
	];
	ProcessDialog.prototype.initialize = function () {
		ProcessDialog.parent.prototype.initialize.apply( this, arguments );
		this.content = new OO.ui.PanelLayout( { padded: true, expanded: false } );
		this.content.$element.append( '<p>Dialog content</p>' );
		this.$body.append( this.content.$element );
	};
	ProcessDialog.prototype.getActionProcess = function ( action ) {
		var dialog = this;
		if ( action ) {
			return new OO.ui.Process( function () {
				dialog.close( { action: action } );
			} );
		}
		return ProcessDialog.parent.prototype.getActionProcess.call( this, action );
	};
	ProcessDialog.prototype.getBodyHeight = function () {
		return this.content.$element.outerHeight( true );
	};

	function FramelessProcessDialog( config ) {
		FramelessProcessDialog.parent.call( this, config );
	}
	OO.inheritClass( FramelessProcessDialog, ProcessDialog );
	FramelessProcessDialog.static.actions = OO.copy( FramelessProcessDialog.static.actions );
	FramelessProcessDialog.static.actions.forEach( function ( action ) {
		action.framed = false;
	} );

	function SearchWidgetDialog( config ) {
		SearchWidgetDialog.parent.call( this, config );
		this.broken = false;
	}
	OO.inheritClass( SearchWidgetDialog, OO.ui.ProcessDialog );
	SearchWidgetDialog.static.title = 'Search widget dialog';
	SearchWidgetDialog.prototype.initialize = function () {
		var i, items, searchWidget;
		SearchWidgetDialog.parent.prototype.initialize.apply( this, arguments );
		items = [];
		searchWidget = new OO.ui.SearchWidget();
		for ( i = 1; i <= 20; i++ ) {
			items.push( new OO.ui.OptionWidget( { data: i, label: 'Item ' + i } ) );
		}
		searchWidget.results.addItems( items );
		searchWidget.onQueryChange = function () {};
		this.$body.append( searchWidget.$element );
	};
	SearchWidgetDialog.prototype.getBodyHeight = function () {
		return 300;
	};
	SearchWidgetDialog.static.actions = [
		{ action: 'cancel', label: 'Cancel', flags: 'safe' }
	];
	SearchWidgetDialog.prototype.getActionProcess = function ( action ) {
		var dialog = this;
		return new OO.ui.Process( function () {
			dialog.close( { action: action } );
		} );
	};

	function BrokenDialog( config ) {
		BrokenDialog.parent.call( this, config );
		this.broken = false;
	}
	OO.inheritClass( BrokenDialog, OO.ui.ProcessDialog );
	BrokenDialog.static.title = 'Broken dialog';
	BrokenDialog.static.actions = [
		{ action: 'save', label: 'Save', flags: [ 'primary', 'constructive' ] },
		{ action: 'delete', label: 'Delete', flags: 'destructive' },
		{ action: 'cancel', label: 'Cancel', flags: 'safe' }
	];
	BrokenDialog.prototype.getBodyHeight = function () {
		return 250;
	};
	BrokenDialog.prototype.initialize = function () {
		BrokenDialog.parent.prototype.initialize.apply( this, arguments );
		this.content = new OO.ui.PanelLayout( { padded: true } );
		this.fieldset = new OO.ui.FieldsetLayout( {
			label: 'Dialog with error handling', icon: 'alert'
		} );
		this.description = new OO.ui.LabelWidget( {
			label: 'Deleting will fail and will not be recoverable. ' +
				'Saving will fail the first time, but succeed the second time.'
		} );
		this.fieldset.addItems( [ this.description ] );
		this.content.$element.append( this.fieldset.$element );
		this.$body.append( this.content.$element );
	};
	BrokenDialog.prototype.getSetupProcess = function ( data ) {
		return BrokenDialog.parent.prototype.getSetupProcess.call( this, data )
			.next( function () {
				this.broken = true;
			}, this );
	};
	BrokenDialog.prototype.getActionProcess = function ( action ) {
		return BrokenDialog.parent.prototype.getActionProcess.call( this, action )
			.next( function () {
				return 1000;
			}, this )
			.next( function () {
				var closing;

				if ( action === 'save' ) {
					if ( this.broken ) {
						this.broken = false;
						return new OO.ui.Error( 'Server did not respond' );
					}
				} else if ( action === 'delete' ) {
					return new OO.ui.Error( 'Permission denied', { recoverable: false } );
				}

				closing = this.close( { action: action } );
				if ( action === 'save' ) {
					// Return a promise to remaing pending while closing
					return closing;
				}
				return BrokenDialog.parent.prototype.getActionProcess.call( this, action );
			}, this );
	};

	function SamplePage( name, config ) {
		config = $.extend( { label: 'Sample page' }, config );
		OO.ui.PageLayout.call( this, name, config );
		this.label = config.label;
		this.icon = config.icon;
		if ( this.$element.is( ':empty' ) ) {
			this.$element.text( this.label );
		}
	}
	OO.inheritClass( SamplePage, OO.ui.PageLayout );
	SamplePage.prototype.setupOutlineItem = function ( outlineItem ) {
		SamplePage.parent.prototype.setupOutlineItem.call( this, outlineItem );
		this.outlineItem
			.setMovable( true )
			.setRemovable( true )
			.setIcon( this.icon )
			.setLabel( this.label );
	};

	function BookletDialog( config ) {
		BookletDialog.parent.call( this, config );
	}
	OO.inheritClass( BookletDialog, OO.ui.ProcessDialog );
	BookletDialog.static.title = 'Booklet dialog';
	BookletDialog.static.actions = [
		{ action: 'save', label: 'Done', flags: [ 'primary', 'progressive' ] },
		{ action: 'cancel', label: 'Cancel', flags: 'safe' }
	];
	BookletDialog.prototype.getBodyHeight = function () {
		return 250;
	};
	BookletDialog.prototype.initialize = function () {
		var dialog;
		BookletDialog.parent.prototype.initialize.apply( this, arguments );

		dialog = this;

		function changePage( direction ) {
			var pageIndex = dialog.pages.indexOf( dialog.bookletLayout.getCurrentPage() );
			pageIndex = ( dialog.pages.length + pageIndex + direction ) % dialog.pages.length;
			dialog.bookletLayout.setPage( dialog.pages[ pageIndex ].getName() );
		}

		this.navigationField = new OO.ui.FieldLayout(
			new OO.ui.ButtonGroupWidget( {
				items: [
					new OO.ui.ButtonWidget( {
						data: 'previous',
						icon: 'previous'
					} ).on( 'click', function () {
						changePage( -1 );
					} ),
					new OO.ui.ButtonWidget( {
						data: 'next',
						icon: 'next'
					} ).on( 'click', function () {
						changePage( 1 );
					} )
				]
			} ),
			{
				label: 'Change page',
				align: 'top'
			}
		);

		this.bookletLayout = new OO.ui.BookletLayout();
		this.pages = [
			new SamplePage( 'page-1', { label: 'Page 1', icon: 'window' } ),
			new SamplePage( 'page-2', { label: 'Page 2', icon: 'window' } ),
			new SamplePage( 'page-3', { label: 'Page 3', icon: 'window' } )
		];
		this.bookletLayout.addPages( this.pages );
		this.bookletLayout.connect( this, { set: 'onBookletLayoutSet' } );
		this.bookletLayout.setPage( 'page-1' );

		this.$body.append( this.bookletLayout.$element );
	};
	BookletDialog.prototype.getActionProcess = function ( action ) {
		if ( action ) {
			return new OO.ui.Process( function () {
				this.close( { action: action } );
			}, this );
		}
		return BookletDialog.parent.prototype.getActionProcess.call( this, action );
	};
	BookletDialog.prototype.onBookletLayoutSet = function ( page ) {
		page.$element.append( this.navigationField.$element );
	};

	function OutlinedBookletDialog( config ) {
		OutlinedBookletDialog.parent.call( this, config );
	}
	OO.inheritClass( OutlinedBookletDialog, OO.ui.ProcessDialog );
	OutlinedBookletDialog.static.title = 'Booklet dialog';
	OutlinedBookletDialog.static.actions = [
		{ action: 'save', label: 'Done', flags: [ 'primary', 'progressive' ] },
		{ action: 'cancel', label: 'Cancel', flags: 'safe' }
	];
	OutlinedBookletDialog.prototype.getBodyHeight = function () {
		return 250;
	};
	OutlinedBookletDialog.prototype.initialize = function () {
		OutlinedBookletDialog.parent.prototype.initialize.apply( this, arguments );
		this.bookletLayout = new OO.ui.BookletLayout( {
			outlined: true
		} );
		this.pages = [
			new SamplePage( 'small', { label: 'Small', icon: 'window' } ),
			new SamplePage( 'medium', { label: 'Medium', icon: 'window' } ),
			new SamplePage( 'large', { label: 'Large', icon: 'window' } ),
			new SamplePage( 'larger', { label: 'Larger', icon: 'window' } ),
			new SamplePage( 'full', { label: 'Full', icon: 'window' } )
		];

		this.bookletLayout.addPages( this.pages );
		this.bookletLayout.connect( this, { set: 'onBookletLayoutSet' } );
		this.$body.append( this.bookletLayout.$element );
	};
	OutlinedBookletDialog.prototype.getActionProcess = function ( action ) {
		if ( action ) {
			return new OO.ui.Process( function () {
				this.close( { action: action } );
			}, this );
		}
		return OutlinedBookletDialog.parent.prototype.getActionProcess.call( this, action );
	};
	OutlinedBookletDialog.prototype.onBookletLayoutSet = function ( page ) {
		this.setSize( page.getName() );
	};
	OutlinedBookletDialog.prototype.getSetupProcess = function ( data ) {
		return OutlinedBookletDialog.parent.prototype.getSetupProcess.call( this, data )
			.next( function () {
				this.bookletLayout.setPage( this.getSize() );
			}, this );
	};

	function SampleCard( name, config ) {
		OO.ui.CardLayout.call( this, name, config );
		this.$element.text( this.label );
	}
	OO.inheritClass( SampleCard, OO.ui.CardLayout );

	function IndexedDialog( config ) {
		IndexedDialog.parent.call( this, config );
	}
	OO.inheritClass( IndexedDialog, OO.ui.ProcessDialog );
	IndexedDialog.static.title = 'Index dialog';
	IndexedDialog.static.actions = [
		{ action: 'save', label: 'Done', flags: [ 'primary', 'progressive' ] },
		{ action: 'cancel', label: 'Cancel', flags: 'safe' }
	];
	IndexedDialog.prototype.getBodyHeight = function () {
		return 250;
	};
	IndexedDialog.prototype.initialize = function () {
		IndexedDialog.parent.prototype.initialize.apply( this, arguments );
		this.indexLayout = new OO.ui.IndexLayout();
		this.cards = [
			new SampleCard( 'first', { label: 'One' } ),
			new SampleCard( 'second', { label: 'Two' } ),
			new SampleCard( 'third', { label: 'Three' } ),
			new SampleCard( 'fourth', { label: 'Four' } )
		];

		this.indexLayout.addCards( this.cards );
		this.$body.append( this.indexLayout.$element );

		this.indexLayout.getTabs().getItemFromData( 'fourth' ).setDisabled( true );
	};
	IndexedDialog.prototype.getActionProcess = function ( action ) {
		if ( action ) {
			return new OO.ui.Process( function () {
				this.close( { action: action } );
			}, this );
		}
		return IndexedDialog.parent.prototype.getActionProcess.call( this, action );
	};

	function MenuDialog( config ) {
		MenuDialog.parent.call( this, config );
	}
	OO.inheritClass( MenuDialog, OO.ui.ProcessDialog );
	MenuDialog.static.title = 'Menu dialog';
	MenuDialog.static.actions = [
		{ action: 'save', label: 'Done', flags: [ 'primary', 'progressive' ] },
		{ action: 'cancel', label: 'Cancel', flags: 'safe' }
	];
	MenuDialog.prototype.getBodyHeight = function () {
		return 350;
	};
	MenuDialog.prototype.initialize = function () {
		var menuLayout, positionField, showField, menuPanel, contentPanel;
		MenuDialog.parent.prototype.initialize.apply( this, arguments );

		menuLayout = new OO.ui.MenuLayout();
		positionField = new OO.ui.FieldLayout(
			new OO.ui.ButtonSelectWidget( {
				items: [
					new OO.ui.ButtonOptionWidget( {
						data: 'before',
						label: 'Before'
					} ),
					new OO.ui.ButtonOptionWidget( {
						data: 'after',
						label: 'After'
					} ),
					new OO.ui.ButtonOptionWidget( {
						data: 'top',
						label: 'Top'
					} ),
					new OO.ui.ButtonOptionWidget( {
						data: 'bottom',
						label: 'Bottom'
					} )
				]
			} ).on( 'select', function ( item ) {
				menuLayout.setMenuPosition( item.getData() );
			} ),
			{
				label: 'Menu position',
				align: 'top'
			}
		);
		showField = new OO.ui.FieldLayout(
			new OO.ui.ToggleSwitchWidget( { value: true } ).on( 'change', function ( value ) {
				menuLayout.toggleMenu( value );
			} ),
			{
				label: 'Show menu',
				align: 'top'
			}
		);
		menuPanel = new OO.ui.PanelLayout( { padded: true, expanded: true, scrollable: true } );
		contentPanel = new OO.ui.PanelLayout( { padded: true, expanded: true, scrollable: true } );

		menuLayout.$menu.append(
			menuPanel.$element.append( 'Menu panel' )
		);
		menuLayout.$content.append(
			contentPanel.$element.append(
				positionField.$element,
				showField.$element
			)
		);

		this.$body.append( menuLayout.$element );
	};
	MenuDialog.prototype.getActionProcess = function ( action ) {
		if ( action ) {
			return new OO.ui.Process( function () {
				this.close( { action: action } );
			}, this );
		}
		return MenuDialog.parent.prototype.getActionProcess.call( this, action );
	};

	function ExampleLookupTextInputWidget( config ) {
		config = config || {};
		this.items = config.items || [];
		OO.ui.TextInputWidget.call( this, config );
		OO.ui.mixin.LookupElement.call( this, config );
	}
	OO.inheritClass( ExampleLookupTextInputWidget, OO.ui.TextInputWidget );
	OO.mixinClass( ExampleLookupTextInputWidget, OO.ui.mixin.LookupElement );
	ExampleLookupTextInputWidget.prototype.getLookupRequest = function () {
		return $.Deferred().resolve( [] ).promise( { abort: function () {} } );
	};
	ExampleLookupTextInputWidget.prototype.getLookupCacheDataFromResponse = function () {
		return [];
	};
	ExampleLookupTextInputWidget.prototype.getLookupMenuOptionsFromData = function () {
		return this.items;
	};

	function DialogWithDropdowns( config ) {
		DialogWithDropdowns.parent.call( this, config );
	}
	OO.inheritClass( DialogWithDropdowns, OO.ui.ProcessDialog );
	DialogWithDropdowns.static.title = 'Dialog with dropdowns ($overlay test)';
	DialogWithDropdowns.static.actions = [
		{ action: 'save', label: 'Done', flags: [ 'primary', 'progressive' ] },
		{ action: 'cancel', label: 'Cancel', flags: 'safe' }
	];
	DialogWithDropdowns.prototype.getBodyHeight = function () {
		return 250;
	};
	DialogWithDropdowns.prototype.initialize = function () {
		var $spacer = $( '<div>' ).height( 150 );
		DialogWithDropdowns.parent.prototype.initialize.apply( this, arguments );
		this.bookletLayout = new OO.ui.BookletLayout( {
			outlined: true
		} );
		this.pages = [
			new SamplePage( 'info', {
				label: 'Information',
				icon: 'info',
				content: [
					'This is a test of various kinds of dropdown menus and their $overlay config option. ',
					'Entries without any icon use a correctly set $overlay and their menus should be able to extend outside of this small dialog. ',
					'Entries with the ', new OO.ui.IconWidget( { icon: 'alert' } ), ' icon do not, and their menus should be clipped to the dialog\'s boundaries. ',
					'All dropdown menus should stick to the widget proper, even when scrolling while the menu is open.'
				]
			} ),
			new SamplePage( 'dropdown', {
				label: 'DropdownWidget',
				content: [ $spacer.clone(), new OO.ui.DropdownWidget( {
					$overlay: this.$overlay,
					menu: {
						items: this.makeItems()
					}
				} ), $spacer.clone() ]
			} ),
			new SamplePage( 'dropdown2', {
				label: 'DropdownWidget',
				icon: 'alert',
				content: [ $spacer.clone(), new OO.ui.DropdownWidget( {
					menu: {
						items: this.makeItems()
					}
				} ), $spacer.clone() ]
			} ),
			new SamplePage( 'combobox', {
				label: 'ComboBoxWidget',
				content: [ $spacer.clone(), new OO.ui.ComboBoxWidget( {
					$overlay: this.$overlay,
					menu: {
						items: this.makeItems()
					}
				} ), $spacer.clone() ]
			} ),
			new SamplePage( 'combobox2', {
				label: 'ComboBoxWidget',
				icon: 'alert',
				content: [ $spacer.clone(), new OO.ui.ComboBoxWidget( {
					menu: {
						items: this.makeItems()
					}
				} ), $spacer.clone() ]
			} ),
			new SamplePage( 'lookup', {
				label: 'LookupElement',
				content: [ $spacer.clone(), new ExampleLookupTextInputWidget( {
					$overlay: this.$overlay,
					items: this.makeItems()
				} ), $spacer.clone() ]
			} ),
			new SamplePage( 'lookup2', {
				label: 'LookupElement',
				icon: 'alert',
				content: [ $spacer.clone(), new ExampleLookupTextInputWidget( {
					items: this.makeItems()
				} ), $spacer.clone() ]
			} ),
			new SamplePage( 'capsule', {
				label: 'CapsuleMultiSelectWidget',
				content: [ $spacer.clone(), new OO.ui.CapsuleMultiSelectWidget( {
					$overlay: this.$overlay,
					menu: {
						items: this.makeItems()
					}
				} ), $spacer.clone() ]
			} ),
			new SamplePage( 'capsule2', {
				label: 'CapsuleMultiSelectWidget',
				icon: 'alert',
				content: [ $spacer.clone(), new OO.ui.CapsuleMultiSelectWidget( {
					menu: {
						items: this.makeItems()
					}
				} ), $spacer.clone() ]
			} )
		];
		this.bookletLayout.addPages( this.pages );
		this.$body.append( this.bookletLayout.$element );
	};
	DialogWithDropdowns.prototype.makeItems = function () {
		return [ 0, 1, 2, 3, 4, 5, 6, 7, 8, 9 ].map( function ( val ) {
			return new OO.ui.MenuOptionWidget( {
				data: val,
				label: String( val )
			} );
		} );
	};

	DialogWithDropdowns.prototype.getActionProcess = function ( action ) {
		if ( action ) {
			return new OO.ui.Process( function () {
				this.close( { action: action } );
			}, this );
		}
		return DialogWithDropdowns.parent.prototype.getActionProcess.call( this, action );
	};

	config = [
		{
			name: 'Simple dialog (small)',
			config: {
				size: 'small'
			},
			data: {
				title: 'Sample dialog with very long title that does not fit'
			}
		},
		{
			name: 'Simple dialog (medium)',
			config: {
				size: 'medium'
			}
		},
		{
			name: 'Simple dialog (large)',
			config: {
				size: 'large'
			}
		},
		{
			name: 'Simple dialog (larger)',
			config: {
				size: 'larger'
			}
		},
		{
			name: 'Simple dialog (full)',
			config: {
				size: 'full'
			}
		},
		{
			name: 'Process dialog (medium)',
			dialogClass: ProcessDialog,
			config: {
				size: 'medium'
			}
		},
		{
			name: 'Process dialog (frameless buttons)',
			dialogClass: FramelessProcessDialog,
			config: {
				size: 'medium'
			}
		},
		{
			name: 'Process dialog (full)',
			dialogClass: ProcessDialog,
			config: {
				size: 'full'
			}
		},
		{
			name: 'Search widget dialog (medium)',
			dialogClass: SearchWidgetDialog,
			config: {
				size: 'medium'
			}
		},
		{
			name: 'Broken dialog (error handling)',
			dialogClass: BrokenDialog,
			config: {
				size: 'medium'
			}
		},
		{
			name: 'Booklet dialog',
			dialogClass: BookletDialog,
			config: {
				size: 'medium'
			}
		},
		{
			name: 'Outlined booklet dialog',
			dialogClass: OutlinedBookletDialog,
			config: {
				size: 'medium'
			}
		},
		{
			name: 'Indexed dialog',
			dialogClass: IndexedDialog,
			config: {
				size: 'medium'
			}
		},
		{
			name: 'Menu dialog',
			dialogClass: MenuDialog,
			config: {
				size: 'medium'
			}
		},
		{
			name: 'Dialog with dropdowns ($overlay test)',
			dialogClass: DialogWithDropdowns,
			config: {
				size: 'large'
			}
		},
		{
			name: 'Message dialog (generic)',
			dialogClass: OO.ui.MessageDialog,
			data: {
				title: 'Continue?',
				message: 'It may be risky'
			}
		},
		{
			name: 'Message dialog (verbose)',
			dialogClass: OO.ui.MessageDialog,
			data: {
				title: 'Continue?',
				message: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque quis laoreet elit. Nam eu velit ullamcorper, volutpat elit sed, viverra massa. Aenean congue aliquam lorem, et laoreet risus condimentum vel. Praesent nec imperdiet mauris. Nunc eros magna, iaculis sit amet ante id, dapibus tristique lorem. Praesent in feugiat lorem, sit amet porttitor eros. Donec sapien turpis, pretium eget ligula id, scelerisque tincidunt diam. Pellentesque a venenatis tortor, at luctus nisl. Quisque vel urna a enim mattis rutrum. Morbi eget consequat nisl. Nam tristique molestie diam ac consequat. Nam varius adipiscing mattis. Praesent sodales volutpat nulla lobortis iaculis. Quisque vel odio eget diam posuere imperdiet. Fusce et iaculis odio. Donec in nibh ut dui accumsan vehicula quis et massa.',
				verbose: true
			}
		},
		{
			name: 'Message dialog (1 action)',
			dialogClass: OO.ui.MessageDialog,
			data: {
				title: 'Storage limit reached',
				message: 'You are out of disk space',
				actions: [
					{
						action: 'accept',
						label: 'Dismiss',
						flags: 'primary'
					}
				]
			}
		},
		{
			name: 'Message dialog (2 actions)',
			dialogClass: OO.ui.MessageDialog,
			data: {
				title: 'Cannot save data',
				message: 'The server is not responding',
				actions: [
					{
						action: 'reject',
						label: 'Cancel',
						flags: 'safe'
					},
					{
						action: 'repeat',
						label: 'Try again',
						flags: [ 'primary', 'constructive' ]
					}
				]
			}
		},
		{
			name: 'Message dialog (3 actions)',
			dialogClass: OO.ui.MessageDialog,
			data: {
				title: 'Delete file?',
				message: 'The file will be irreversably obliterated. Proceed with caution.',
				actions: [
					{ action: 'reject', label: 'Cancel', flags: 'safe' },
					{ action: 'reject', label: 'Move file to trash' },
					{
						action: 'accept',
						label: 'Obliterate',
						flags: [ 'primary', 'destructive' ]
					}
				]
			}
		}
	];

	function openDialog( name, data ) {
		windowManager.openWindow( name, data );
	}

	for ( i = 0, l = config.length; i < l; i++ ) {
		name = 'window_' + i;
		DialogClass = config[ i ].dialogClass || SimpleDialog;
		windows[ name ] = new DialogClass( config[ i ].config );
		openButton = new OO.ui.ButtonWidget( {
			framed: false,
			icon: 'window',
			label: $( '<span dir="ltr"></span>' ).text( config[ i ].name )
		} );
		openButton.on(
			'click', OO.ui.bind( openDialog, this, name, config[ i ].data )
		);
		fieldset.addItems( [ new OO.ui.FieldLayout( openButton, { align: 'inline' } ) ] );
	}
	windowManager.addWindows( windows );

	$demo.append(
		new OO.ui.PanelLayout( {
			expanded: false,
			framed: true
		} ).$element
			.addClass( 'oo-ui-demo-container' )
			.append( fieldset.$element ),
		windowManager.$element
	);
};
