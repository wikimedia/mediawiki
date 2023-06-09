const popUpNotification = require( '../../resources/skins.vector.js/popupNotification.js' );

/**
 * @type string
 */
let testId;

/**
 * @type string
 */
let testMessage;

/**
 * @type string
 */
let vectorPopupClass;

/**
 * @type {Record<string,OoUiPopupWidget>}
 */
let activeNotification;

describe( 'Popup Notification', () => {
	beforeEach( () => {
		global.window.matchMedia = jest.fn( () => ( {} ) );
		document.body.style = 'direction: ltr';
		jest.spyOn( mw.loader, 'using' )
			.mockImplementation( () => Promise.resolve() );
		testId = 'test-id';
		testMessage = 'test message';
		vectorPopupClass = 'vector-popup-notification';
		activeNotification = [];
		popUpNotification.hideAll();
	} );

	afterEach( () => {
		jest.resetModules();
	} );

	// test add function
	test( 'add', async () => {
		const popupWidget = await popUpNotification.add(
			document.body,
			testMessage,
			testId,
			[],
			4000,
			() => {}
		);
		activeNotification[ testId ] = popupWidget;
		expect( activeNotification[ testId ] ).toBeDefined();
		expect( activeNotification[ testId ].$element ).toBeDefined();
		expect( activeNotification[ testId ].$element[ 0 ].textContent )
			.toContain( testMessage );
		expect( activeNotification[ testId ].$element[ 0 ].classList
			.contains( vectorPopupClass ) ).toBe( true );
	} );

	// test hide function
	test( 'hide', async () => {
		const popupWidget = await popUpNotification.add(
			document.body,
			testMessage,
			testId,
			[],
			4000,
			() => {}
		);
		activeNotification[ testId ] = popupWidget;
		expect( activeNotification[ testId ].visible ).toBe( false );
		popUpNotification.show( activeNotification[ testId ] );
		expect( activeNotification[ testId ].visible ).toBe( true );
		popUpNotification.hide( activeNotification[ testId ] );
		expect( activeNotification[ testId ].visible ).toBe( false );
	} );

	// test show function
	test( 'show', async () => {
		const popupWidget = await popUpNotification.add(
			document.body,
			testMessage,
			testId,
			[],
			4000,
			() => {}
		);
		activeNotification[ testId ] = popupWidget;
		expect( activeNotification[ testId ].visible ).toBe( false );
		popUpNotification.show( activeNotification[ testId ] );
		expect( activeNotification[ testId ].visible ).toBe( true );
	} );

	// test hideAll function
	test( 'hideAll', async () => {
		const popupWidget = await popUpNotification.add(
			document.body,
			testMessage,
			testId,
			[],
			4000,
			() => {}
		);
		activeNotification[ testId ] = popupWidget;
		expect( activeNotification[ testId ].visible ).toBe( false );
		popUpNotification.show( activeNotification[ testId ] );
		expect( activeNotification[ testId ].visible ).toBe( true );
		popUpNotification.hideAll();
		expect( activeNotification[ testId ].visible ).toBe( false );
	} );
} );
