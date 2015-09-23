OO.ui.Demo.static.pages.toolbars = function ( demo ) {
	var i, toolGroups, saveButton, deleteButton, actionButton, actionButtonDisabled, PopupTool, ToolGroupTool,
		setDisabled = function () { this.setDisabled( true ); },
		$demo = demo.$element,
		$containers = $(),
		toolFactories = [],
		toolGroupFactories = [],
		toolbars = [];

	// Show some random accelerator keys that don't actually work
	function getToolAccelerator( name ) {
		return {
			listTool1: 'Ctrl+Shift+1',
			listTool2: 'Ctrl+Alt+2',
			listTool3: 'Cmd+Enter',
			listTool5: 'Shift+Down',
			menuTool: 'Ctrl+M'
		}[ name ];
	}

	for ( i = 0; i < 4; i++ ) {
		toolFactories.push( new OO.ui.ToolFactory() );
		toolGroupFactories.push( new OO.ui.ToolGroupFactory() );
		toolbars.push( new OO.ui.Toolbar( toolFactories[ i ], toolGroupFactories[ i ], { actions: true } ) );
		toolbars[ i ].getToolAccelerator = getToolAccelerator;
	}

	function createTool( toolbar, group, name, icon, title, init, onSelect, displayBothIconAndLabel ) {
		var Tool = function () {
			Tool.parent.apply( this, arguments );
			this.toggled = false;
			if ( init ) {
				init.call( this );
			}
		};

		OO.inheritClass( Tool, OO.ui.Tool );

		Tool.prototype.onSelect = function () {
			if ( onSelect ) {
				onSelect.call( this );
			} else {
				this.toggled = !this.toggled;
				this.setActive( this.toggled );
			}
			toolbars[ toolbar ].emit( 'updateState' );
		};
		Tool.prototype.onUpdateState = function () {};

		Tool.static.name = name;
		Tool.static.group = group;
		Tool.static.icon = icon;
		Tool.static.title = title;
		Tool.static.displayBothIconAndLabel = !!displayBothIconAndLabel;
		return Tool;
	}

	function createToolGroup( toolbar, group ) {
		$.each( toolGroups[ group ], function ( i, tool ) {
			var args = tool.slice();
			args.splice( 0, 0, toolbar, group );
			toolFactories[ toolbar ].register( createTool.apply( null, args ) );
		} );
	}

	function createDisabledToolGroup( parent, name ) {
		var DisabledToolGroup = function () {
			DisabledToolGroup.parent.apply( this, arguments );
			this.setDisabled( true );
		};

		OO.inheritClass( DisabledToolGroup, parent );

		DisabledToolGroup.static.name = name;

		DisabledToolGroup.prototype.onUpdateState = function () {
			this.setLabel( 'Disabled' );
		};

		return DisabledToolGroup;
	}

	toolGroupFactories[ 0 ].register( createDisabledToolGroup( OO.ui.BarToolGroup, 'disabledBar' ) );
	toolGroupFactories[ 0 ].register( createDisabledToolGroup( OO.ui.ListToolGroup, 'disabledList' ) );
	toolGroupFactories[ 1 ].register( createDisabledToolGroup( OO.ui.MenuToolGroup, 'disabledMenu' ) );

	PopupTool = function ( toolGroup, config ) {
		// Parent constructor
		OO.ui.PopupTool.call( this, toolGroup, $.extend( { popup: {
			padded: true,
			label: 'Popup head',
			head: true
		} }, config ) );

		this.popup.$body.append( '<p>Popup contents</p>' );
	};

	OO.inheritClass( PopupTool, OO.ui.PopupTool );

	PopupTool.static.name = 'popupTool';
	PopupTool.static.group = 'popupTools';
	PopupTool.static.icon = 'help';

	toolFactories[ 2 ].register( PopupTool );

	ToolGroupTool = function ( toolGroup, config ) {
		// Parent constructor
		OO.ui.ToolGroupTool.call( this, toolGroup, config );
	};

	OO.inheritClass( ToolGroupTool, OO.ui.ToolGroupTool );

	ToolGroupTool.static.name = 'toolGroupTool';
	ToolGroupTool.static.group = 'barTools';
	ToolGroupTool.static.groupConfig = {
		indicator: 'down',
		include: [ { group: 'moreListTools' } ]
	};

	toolFactories[ 0 ].register( ToolGroupTool );
	toolFactories[ 3 ].register( ToolGroupTool );

	// Toolbar
	toolbars[ 0 ].setup( [
		{
			type: 'bar',
			include: [ { group: 'barTools' } ],
			demote: [ 'toolGroupTool' ]
		},
		{
			type: 'disabledBar',
			include: [ { group: 'disabledBarTools' } ]
		},
		{
			type: 'list',
			indicator: 'down',
			label: 'List',
			icon: 'picture',
			include: [ { group: 'listTools' } ],
			allowCollapse: [ 'listTool1', 'listTool6' ]
		},
		{
			type: 'disabledList',
			indicator: 'down',
			label: 'List',
			icon: 'picture',
			include: [ { group: 'disabledListTools' } ]
		},
		{
			type: 'list',
			indicator: 'down',
			label: 'Auto-disabling list',
			icon: 'picture',
			include: [ { group: 'autoDisableListTools' } ]
		},
		{
			label: 'Catch-all',
			include: '*'
		}
	] );
	// Toolbar with action buttons
	toolbars[ 1 ].setup( [
		{
			type: 'menu',
			indicator: 'down',
			icon: 'picture',
			include: [ { group: 'menuTools' } ]
		},
		{
			type: 'disabledMenu',
			indicator: 'down',
			icon: 'picture',
			include: [ { group: 'disabledMenuTools' } ]
		}
	] );
	// Fake toolbar to be injected into the first toolbar
	// demonstrating right-aligned menus
	toolbars[ 2 ].setup( [
		{
			include: [ { group: 'popupTools' } ]
		},
		{
			type: 'list',
			icon: 'menu',
			include: [ { group: 'listTools' } ]
		}
	] );
	toolbars[ 3 ].setup( [
		{
			type: 'bar',
			include: [ { group: 'history' } ]
		},
		{
			type: 'menu',
			indicator: 'down',
			include: [ { group: 'menuTools' } ]
		},
		{
			type: 'list',
			indicator: 'down',
			icon: 'comment',
			include: [ { group: 'listTools' } ],
			allowCollapse: [ 'listTool1', 'listTool6' ]
		},
		{
			type: 'bar',
			include: [ { group: 'link' } ]
		},
		{
			type: 'bar',
			include: [ { group: 'cite' } ]
		},
		{
			type: 'bar',
			include: [ { group: 'citeDisabled' } ]
		},
		{
			type: 'list',
			indicator: 'down',
			label: 'Insert',
			include: [ { group: 'autoDisableListTools' }, { group: 'unusedStuff' } ]
		}
	] );

	saveButton = new OO.ui.ButtonWidget( { label: 'Save', flags: [ 'progressive', 'primary' ] } );
	deleteButton = new OO.ui.ButtonWidget( { label: 'Delete', flags: [ 'destructive' ] } );
	actionButton = new OO.ui.ButtonWidget( { label: 'Action' } );
	actionButtonDisabled = new OO.ui.ButtonWidget( { label: 'Disabled', disabled: true } );
	toolbars[ 1 ].$actions
		.append( actionButton.$element, actionButtonDisabled.$element );

	toolbars[ 3 ].$actions
		.append( toolbars[ 2 ].$element, deleteButton.$element, saveButton.$element );

	for ( i = 0; i < toolbars.length; i++ ) {
		toolbars[ i ].emit( 'updateState' );
	}

	toolGroups = {
		barTools: [
			[ 'barTool', 'picture', 'Basic tool in bar' ],
			[ 'disabledBarTool', 'picture', 'Basic tool in bar disabled', setDisabled ]
		],

		disabledBarTools: [
			[ 'barToolInDisabled', 'picture', 'Basic tool in disabled bar' ]
		],

		listTools: [
			[ 'listTool', 'picture', 'First basic tool in list' ],
			[ 'listTool1', 'picture', 'Basic tool in list' ],
			[ 'listTool3', 'picture', 'Basic disabled tool in list', setDisabled ],
			[ 'listTool6', 'picture', 'A final tool' ]
		],

		moreListTools: [
			[ 'listTool2', 'code', 'Another basic tool' ],
			[ 'listTool4', 'picture', 'More basic tools' ],
			[ 'listTool5', 'ellipsis', 'And even more' ]
		],

		popupTools: [
			[ 'popupTool' ]
		],

		disabledListTools: [
			[ 'listToolInDisabled', 'picture', 'Basic tool in disabled list' ]
		],

		autoDisableListTools: [
			[ 'autoDisableListTool', 'picture', 'Click to disable this tool', null, setDisabled ]
		],

		menuTools: [
			[ 'menuTool', 'picture', 'Basic tool' ],
			[ 'disabledMenuTool', 'picture', 'Basic tool disabled', setDisabled ]
		],

		disabledMenuTools: [
			[ 'menuToolInDisabled', 'picture', 'Basic tool' ]
		],

		unusedStuff: [
			[ 'unusedTool', 'help', 'This tool is not explicitly used anywhere' ],
			[ 'unusedTool1', 'help', 'And neither is this one' ]
		],

		history: [
			[ 'undoTool', 'undo', 'Undo' ],
			[ 'redoTool', 'redo', 'Redo' ]
		],

		link: [
			[ 'linkTool', 'link', 'Link' ]
		],

		cite: [
			[ 'citeTool', 'citeArticle', 'Cite', null, null, true ]
		],

		citeDisabled: [
			[ 'citeToolDisabled', 'citeArticle', 'Cite', setDisabled, null, true ]
		]
	};

	createToolGroup( 0, 'unusedStuff' );
	createToolGroup( 0, 'barTools' );
	createToolGroup( 0, 'disabledBarTools' );
	createToolGroup( 0, 'listTools' );
	createToolGroup( 0, 'moreListTools' );
	createToolGroup( 0, 'disabledListTools' );
	createToolGroup( 0, 'autoDisableListTools' );
	createToolGroup( 1, 'menuTools' );
	createToolGroup( 1, 'disabledMenuTools' );
	createToolGroup( 2, 'listTools' );
	createToolGroup( 3, 'history' );
	createToolGroup( 3, 'link' );
	createToolGroup( 3, 'cite' );
	createToolGroup( 3, 'citeDisabled' );
	createToolGroup( 3, 'menuTools' );
	createToolGroup( 3, 'listTools' );
	createToolGroup( 3, 'moreListTools' );
	createToolGroup( 3, 'autoDisableListTools' );
	createToolGroup( 3, 'unusedStuff' );

	for ( i = 0; i < toolbars.length; i++ ) {
		$containers = $containers.add(
			new OO.ui.PanelLayout( {
				expanded: false,
				framed: true
			} ).$element
				.addClass( 'oo-ui-demo-container oo-ui-demo-toolbars' )
		);

		if ( i === 2 ) {
			continue;
		}
		$containers.eq( i ).append( toolbars[ i ].$element );
	}
	$containers.append( '' );
	$demo.append(
		$containers.eq( 0 ).append( '<div class="oo-ui-demo-toolbars-contents">Toolbar</div>' ),
		$containers.eq( 1 ).append( '<div class="oo-ui-demo-toolbars-contents">Toolbar with action buttons</div>' ),
		$containers.eq( 3 ).append( '<div class="oo-ui-demo-toolbars-contents">Word processor toolbar</div>' )
	);
	for ( i = 0; i < toolbars.length; i++ ) {
		toolbars[ i ].initialize();
	}
};
