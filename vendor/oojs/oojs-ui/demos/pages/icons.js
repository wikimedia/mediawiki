OO.ui.Demo.static.pages.icons = function ( demo ) {
	var i, len, iconSet, iconsFieldset, iconButton, selector,
		icons = {
			core: [
				'add',
				'advanced',
				'alert',
				'cancel',
				'check',
				'circle',
				'close',
				'code',
				'collapse',
				'comment',
				'ellipsis',
				'expand',
				'help',
				'history',
				'info',
				'menu',
				'next',
				'notice',
				'picture',
				'previous',
				'redo',
				'remove',
				'search',
				'settings',
				'tag',
				'undo',
				'window'
			],
			movement: [
				'arrowLast',
				'arrowNext',
				'downTriangle',
				'upTriangle',
				'caretLast',
				'caretNext',
				'caretDown',
				'caretUp',
				'move'
			],
			content: [
				'article',
				'articleCheck',
				'articleSearch',
				'citeArticle',
				'book',
				'journal',
				'newspaper',
				'folderPlaceholder',
				'die',
				'download',
				'upload'
			],
			alerts: [
				'bell',
				'bellOn',
				'eye',
				'eyeClosed',
				'message',
				'signature',
				'speechBubble',
				'speechBubbleAdd',
				'speechBubbles'
			],
			interactions: [
				'beta',
				'betaLaunch',
				'bookmark',
				'browser',
				'clear',
				'clock',
				'funnel',
				'heart',
				'key',
				'keyboard',
				'logOut',
				'newWindow',
				'printer',
				'ribbonPrize',
				'sun',
				'watchlist'
			],
			moderation: [
				'block',
				'blockUndo',
				'flag',
				'flagUndo',
				'lock',
				'ongoingConversation',
				'star',
				'trash',
				'trashUndo',
				'unStar',
				'unLock'
			],
			'editing-core': [
				'edit',
				'editLock',
				'editUndo',
				'link',
				'linkExternal',
				'linkSecure'
			],
			'editing-styling': [
				'bigger',
				'smaller',
				'subscript',
				'superscript',
				'bold',
				'italic',
				'strikethrough',
				'underline',
				'textLanguage',
				'textDirLTR',
				'textDirRTL',
				'textStyle'
			],
			'editing-list': [
				'indent',
				'listBullet',
				'listNumbered',
				'outdent'
			],
			'editing-advanced': [
				'alignCentre',
				'alignLeft',
				'alignRight',
				'calendar',
				'find',
				'insert',
				'layout',
				'newline',
				'noWikiText',
				'outline',
				'puzzle',
				'quotes',
				'quotesAdd',
				'redirect',
				'searchCaseSensitive',
				'searchRegularExpression',
				'specialCharacter',
				'table',
				'tableAddColumnAfter',
				'tableAddColumnBefore',
				'tableAddRowAfter',
				'tableAddRowBefore',
				'tableCaption',
				'tableMergeCells',
				'templateAdd',
				'translation',
				'wikiText'
			],
			media: [
				'image',
				'imageAdd',
				'imageLock',
				'photoGallery',
				'play',
				'stop'
			],
			location: [
				'map',
				'mapPin',
				'mapPinAdd',
				'wikitrail'
			],
			user: [
				'userActive',
				'userAvatar',
				'userInactive',
				'userTalk'
			],
			layout: [
				'stripeFlow',
				'stripeSideMenu',
				'stripeSummary',
				'stripeToC',
				'viewCompact',
				'viewDetails'
			],
			accessibility: [
				'bright',
				'halfBright',
				'notBright',
				'moon',
				'largerText',
				'smallerText',
				'visionSimulator'
			],
			wikimedia: [
				'logoCC',
				'logoWikimediaCommons',
				'logoWikipedia'
			]
		},
		indicators = [
			'alert',
			'clear',
			'down',
			'next',
			'previous',
			'required',
			'search',
			'up'
		],
		iconsFieldsets = [],
		iconsButtons = [],
		indicatorsFieldset = new OO.ui.FieldsetLayout( { label: 'Indicators' } );

	for ( i = 0, len = indicators.length; i < len; i++ ) {
		indicatorsFieldset.addItems( [
			new OO.ui.FieldLayout(
				new OO.ui.ButtonWidget( {
					indicator: indicators[ i ],
					framed: false,
					label: indicators[ i ]
				} ),
				{ align: 'top' }
			)
		] );
	}
	for ( iconSet in icons ) {
		iconsFieldset = new OO.ui.FieldsetLayout( { label: 'Icons – ' + iconSet } );
		iconsFieldsets.push( iconsFieldset );

		for ( i = 0, len = icons[ iconSet ].length; i < len; i++ ) {
			iconButton = new OO.ui.ButtonWidget( {
				icon: icons[ iconSet ][ i ],
				framed: false,
				label: icons[ iconSet ][ i ]
			} );
			iconsButtons.push( iconButton );
			iconsFieldset.addItems( [
				new OO.ui.FieldLayout(
					iconButton,
					{ align: 'top' }
				)
			] );
		}
	}

	selector = new OO.ui.ButtonSelectWidget( {
		items: [
			new OO.ui.ButtonOptionWidget( {
				label: 'None',
				flags: [],
				data: {
					progressive: false,
					constructive: false,
					destructive: false
				}
			} ),
			new OO.ui.ButtonOptionWidget( {
				label: 'Progressive',
				flags: [ 'progressive' ],
				data: {
					progressive: true,
					constructive: false,
					destructive: false
				}
			} ),
			new OO.ui.ButtonOptionWidget( {
				label: 'Constructive',
				flags: [ 'constructive' ],
				data: {
					progressive: false,
					constructive: true,
					destructive: false
				}
			} ),
			new OO.ui.ButtonOptionWidget( {
				label: 'Destructive',
				flags: [ 'destructive' ],
				data: {
					progressive: false,
					constructive: false,
					destructive: true
				}
			} )
		]
	} )
		.on( 'select', function ( selected ) {
			iconsButtons.forEach( function ( iconButton ) {
				iconButton.setFlags( selected.getData() );
			} );
		} )
		.selectItemByData( {
			progressive: false,
			constructive: false,
			destructive: false
		} );

	demo.$element.append(
		new OO.ui.PanelLayout( {
			expanded: false,
			framed: true
		} ).$element
			.addClass( 'oo-ui-demo-container oo-ui-demo-icons' )
			.append(
				selector.$element,
				indicatorsFieldset.$element,
				iconsFieldsets.map( function ( item ) { return item.$element[ 0 ]; } )
			) );
};
