const { SUCCESS_PAGE_MESSAGE } = require( './constants.js' );
const AuthMessageDialog = require( './AuthMessageDialog.js' );
const AuthPopupError = require( './AuthPopupError.js' );

/**
 * Open a browser window with the same position and dimensions on the user's screen as the given DOM
 * element.
 *
 * @private
 * @param {string} url
 * @param {HTMLElement} el
 * @param {Event} mouseEvent
 * @return {Window|null}
 */
function openBrowserWindowCoveringElement( url, el, mouseEvent ) {
	// Tested on:
	// * Windows 10 22H2, Firefox and Edge, 100% and 200% scale screens, -/=/+ zoom
	//   All good.
	// * Windows 10 22H2, Firefox and Edge, 150% scale screen, -/=/+ zoom (another device, tablet)
	//   Okay, except:
	//   - On Edge, when using the touch screen, we don't get a mouse event, so the popup is off.
	// * Ubuntu 22.04, Firefox and Chromium, 100% scale screen, -/=/+ zoom
	//   Okay, except:
	//   - On Firefox, when zoomed in, popup window size is slightly off.
	// * (I couldn't get OS scaling to work on Ubuntu, it bricked my VM when enabled.)

	function getWindowDimensions( conversionRatio ) {
		// Find the position of the viewport (not just the browser window) on the screen, accounting for
		// browser toolbars and sidebars.
		// Workaround for a spec deficiency: https://github.com/w3c/csswg-drafts/issues/809
		let innerScreenX;
		let innerScreenY;
		if ( window.mozInnerScreenX !== undefined && window.mozInnerScreenY !== undefined ) {
			// Use Firefox's non-standard property designed for this use case.
			innerScreenX = window.mozInnerScreenX;
			innerScreenY = window.mozInnerScreenY;
		} else if ( mouseEvent && mouseEvent.clientX && mouseEvent.screenX && mouseEvent.clientY && mouseEvent.screenY ) {
			// Obtain the difference from a mouse event, if we got one (and it isn't a simulated event).
			// This is seemingly the only thing in all of web APIs that relates the two positions.
			// https://github.com/w3c/csswg-drafts/issues/809#issuecomment-2134169650
			innerScreenX = mouseEvent.screenX / conversionRatio - mouseEvent.clientX;
			innerScreenY = mouseEvent.screenY / conversionRatio - mouseEvent.clientY;
		} else {
			// Fall back to the position of the browser window.
			// It will be off by an unpredictable amount, depending on browser toolbars and sidebars
			// (e.g. if you have dev tools open and pinned on the left, it will be way off).
			innerScreenX = window.screenX;
			innerScreenY = window.screenY;
		}

		return {
			width: el.offsetWidth * conversionRatio,
			height: el.offsetHeight * conversionRatio,
			left: ( innerScreenX + el.offsetLeft ) * conversionRatio,
			top: ( innerScreenY + el.offsetTop ) * conversionRatio
		};
	}

	// Calculate the dimensions of the window assuming that all the APIs measure things in CSS pixels,
	// as they should per the draft CSSOM View spec: https://drafts.csswg.org/cssom-view/
	// If the assumption is right, we can avoid moving/resizing the window later, which looks ugly.
	const cssPixelsRect = getWindowDimensions( 1.0 );

	// Add a bit of padding to ensure the popup window covers the backdrop dialog,
	// even if the OS chrome has rounded corners or includes semi-transparent shadows.
	const padding = 10;

	// window.open() sometimes "adjusts" the given dimensions far more than it's reasonable.
	// We will re-apply them later using window.resizeTo()/moveTo(), which respect them a bit more.
	const w = window.open( 'about:blank', '_blank', [
		'popup',
		'width=' + ( cssPixelsRect.width + 2 * padding ),
		'height=' + ( cssPixelsRect.height + 2 * padding ),
		'left=' + ( cssPixelsRect.left - padding ),
		'top=' + ( cssPixelsRect.top - padding )
	].join( ',' ) );
	if ( !w ) {
		return null;
	}

	function applyWindowDimensions( rect ) {
		w.resizeTo( rect.width + 2 * padding, rect.height + 2 * padding );
		w.moveTo( rect.left - padding, rect.top - padding );
	}

	// Support: Chrome
	// Once we have the window open, we can try to handle browsers that don't implement the spec yet,
	// and measure things in device pixels. For example, Chrome: https://crbug.com/343009010
	//
	// Support: Firefox
	// On Firefox window.open() *really* doesn't respect the given dimensions, so recalculate
	// them using this method even though they're ostensibly correct.
	//
	// Key assumption here is that the new about:blank window usually doesn't have any zoom applied.
	// Therefore:
	// * Outside the popup window, we can use its devicePixelRatio to calculate the browser zoom
	//   ratio, allowing us to convert CSS pixels to device pixels. We couldn't just use
	//   window.devicePixelRatio, because it combines OS scaling ratio and browser zoom ratio.
	// * Inside the popup window, CSS pixels and device pixels are equivalent, so the result is
	//   correct regardless of whether the browser follows the new spec or the legacy behavior.

	// Read devicePixelRatio from the popup window to get just the OS scaling ratio. Then cancel it
	// out from the main window's devicePixelRatio, leaving just the browser zoom ratio.
	const browserZoomRatio = window.devicePixelRatio / w.devicePixelRatio;

	// Recalculate the dimensions of the window, converting the result to device pixels.
	const devicePixelsRect = getWindowDimensions( browserZoomRatio );

	// Support: Firefox
	// On Firefox, window.moveTo()/resizeTo() are async (https://bugzilla.mozilla.org/1899178).
	// Because of that, sometimes an attempt to move and resize at the same time will result in
	// incorrect position or size, because when it attempts to fit the window to screen dimensions,
	// and does so using outdated values. Try to move/resize again after the first resize happens.
	// However, don't do it after the new page has loaded, because it will set wrong dimensions if
	// browser zoom is active.
	const retryApplyWindowDimensions = () => {
		try {
			if ( w.location.href === 'about:blank' ) {
				applyWindowDimensions( devicePixelsRect );
			} else {
				w.removeEventListener( 'resize', retryApplyWindowDimensions );
			}
		} catch ( err ) {
			w.removeEventListener( 'resize', retryApplyWindowDimensions );
		}
	};
	w.addEventListener( 'resize', retryApplyWindowDimensions );

	// Apply the size again, using the new dimensions.
	applyWindowDimensions( devicePixelsRect );

	// Actually navigate the window away from about:blank once we're done calculating its position.
	w.location = url;

	return w;
}

/**
 * Check if we're probably running on iOS, which has unusual restrictions on popup windows.
 *
 * @private
 * @return {boolean}
 */
function isIos() {
	return /ipad|iphone|ipod/i.test( navigator.userAgent );
}

/**
 * @classdesc
 * Allows opening the login form without leaving the page.
 *
 * The page opened in the popup should communicate success using the authSuccess.js script. If it
 * doesn't, we also check for a login success when the user interacts with the parent window.
 *
 * The constructor is not publicly accessible in MediaWiki. Use the instance exposed by the
 * {@link module:mediawiki.authenticationPopup mediawiki.authenticationPopup} module.
 *
 * **This library is not stable yet (as of May 2024). We're still testing which of the
 * methods work from the technical side, and which methods are understandable for users.
 * Some methods or the whole library may be removed in the future.**
 *
 * Unstable.
 *
 * @internal
 * @class
 */
class AuthPopup {
	/**
	 * Async function to check for a login success.
	 *
	 * @callback AuthPopup~CheckLoggedIn
	 * @return {Promise<any>} A promise resolved with a truthy value if the user is
	 *  logged in and resolved with a falsy value if the user isn’t logged in.
	 */

	/**
	 * @param {Object} config
	 * @param {string} config.loginPopupUrl URL of the login form to be opened as a popup
	 * @param {string} [config.loginFallbackUrl] URL of a fallback login form to link to if the popup
	 *     can't be opened. Defaults to `loginPopupUrl` if not provided.
	 * @param {AuthPopup~CheckLoggedIn} config.checkLoggedIn Async function to check for a login success.
	 * @param {jQuery|string|Function|null} [config.message] Custom message to replace the contents of
	 *     the backdrop message dialog, passed to {@link OO.ui.MessageDialog}
	 */
	constructor( config ) {
		this.loginPopupUrl = config.loginPopupUrl;
		this.loginFallbackUrl = config.loginFallbackUrl || config.loginPopupUrl;
		this.checkLoggedIn = config.checkLoggedIn;
		this.message = config.message || ( () => {
			const message = document.createElement( 'div' );

			const intro = document.createElement( 'p' );
			intro.innerText = OO.ui.msg( 'userlogin-authpopup-loggingin-body' );
			message.appendChild( intro );

			const fallbackLink = document.createElement( 'a' );
			fallbackLink.setAttribute( 'target', '_blank' );
			fallbackLink.setAttribute( 'href', this.loginFallbackUrl );
			fallbackLink.innerText = OO.ui.msg( 'userlogin-authpopup-loggingin-body-link' );
			const fallback = document.createElement( 'p' );
			fallback.appendChild( fallbackLink );
			message.appendChild( fallback );

			return $( message );
		} );
	}

	/**
	 * Open the login form in a small browser popup window.
	 *
	 * In the parent window, display a backdrop message dialog with the same dimensions,
	 * to provide an alternative method to log in if the browser refuses to open the window,
	 * and to allow the user to restart the process if they lose track of the popup window.
	 *
	 * This should only be called in response to a user-initiated event like 'click',
	 * otherwise the user's browser will always refuse to open the window.
	 *
	 * @return {Promise<any>} Resolved when the login succeeds with the value returned by the
	 *     `checkLoggedIn` callback. Resolved with a falsy value if the user cancels the process.
	 *     Rejected when an unexpected error stops the login process.
	 */
	startPopupWindow() {
		// Obtain a mouse event, which we need to calculate where the current browser window appears
		// on the user's screen. (No joke.) 'mouseenter' event should be fired when the dialog opens.
		let mouseEvent;

		return this.showDialog( {
			initOpenWindow: ( m ) => {
				m.$element.one( 'mouseenter', ( e ) => {
					mouseEvent = e;
				} );
				m.$element.on( 'mousemove', ( e ) => {
					mouseEvent = e;
				} );

				if ( isIos() ) {
					// iOS Safari only allows window.open() when it occurs immediately in response to a
					// user-initiated event like 'click', not async, not respecting the HTML5 user activation
					// rules. Therefore we must open the window right here, and we can't wait for the message to
					// be displayed by the code below. On the other hand, the opened window will always be
					// fullscreen anyway even if we were to ask for a popup, so it's not a big deal.
					return window.open( this.loginPopupUrl, '_blank' );
				}
				return null;
			},

			openWindow: ( m ) => {
				const frame = m.$frame[ 0 ];
				return openBrowserWindowCoveringElement( this.loginPopupUrl, frame, mouseEvent );
			},

			data: {
				title: OO.ui.deferMsg( 'userlogin-authpopup-loggingin-title' ),
				message: this.message
			}
		} );
	}

	/**
	 * Open the login form in a new browser tab or window.
	 *
	 * In the parent window, display a backdrop message dialog,
	 * to provide an alternative method to log in if the browser refuses to open the window,
	 * and to allow the user to restart the process if they lose track of the new tab or window.
	 *
	 * This should only be called in response to a user-initiated event like 'click',
	 * otherwise the user's browser will always refuse to open the window.
	 *
	 * @return {Promise<any>} Resolved when the login succeeds with the value returned by the
	 *     `checkLoggedIn` callback. Resolved with a falsy value if the user cancels the process.
	 *     Rejected when an unexpected error stops the login process.
	 */
	startNewTabOrWindow() {
		const openWindow = () => window.open( this.loginPopupUrl, '_blank' );

		return this.showDialog( {
			initOpenWindow: openWindow,

			openWindow: openWindow,

			data: {
				title: OO.ui.deferMsg( 'userlogin-authpopup-loggingin-title' ),
				message: this.message
			}
		} );
	}

	/**
	 * Open the login form in an iframe in a modal message dialog.
	 *
	 * In order for this to work, the wiki must be configured to allow the login page to be framed
	 * ($wgEditPageFrameOptions), which has security implications.
	 *
	 * Add a button to provide an alternative method to log in, just in case.
	 *
	 * @return {Promise<any>} Resolved when the login succeeds with the value returned by the
	 *     `checkLoggedIn` callback. Resolved with a falsy value if the user cancels the process.
	 *     Rejected when an unexpected error stops the login process.
	 */
	startIframe() {
		const $iframe = $( '<iframe>' )
			.attr( 'src', this.loginPopupUrl )
			.css( {
				border: '0',
				display: 'block',
				width: '100%',
				height: '100%'
			} );

		return this.showDialog( {
			initOpenWindow: () => {},

			openWindow: ( m ) => {
				// We can't pass it as .data.message, because that has wrappers that mess up the styles
				m.$body.empty().append( $iframe );
				// Allow default click handling on the fallback link-action (eww)
				m.actions.get( { actions: 'fallback' } )[ 0 ].off( 'click' );
			},

			data: {
				title: '',
				message: '',
				actions: [ {
					action: 'fallback',
					href: this.loginFallbackUrl,
					target: '_blank',
					label: OO.ui.deferMsg( 'userlogin-authpopup-loggingin-body-link' ),
					flags: 'safe'
				} ].concat(
					AuthMessageDialog.static.actions.filter( ( a ) => a.action === 'cancel' )
				)
			}
		} );
	}

	/**
	 * Open the backdrop dialog for a customizable popup window.
	 *
	 * Caller must provide callback functions that open their popup window, and/or provide the dialog
	 * opening data to display something in the dialog.
	 *
	 * @private
	 * @param {Object} config
	 * @param {Function} config.initOpenWindow Called before opening the dialog
	 * @param {Function} config.openWindow Called after opening the dialog and upon user retry
	 * @param {Object} config.data Opening data for the MessageDialog
	 * @return {Promise<any>} Resolved when the login succeeds with the value returned by the
	 *     `checkLoggedIn` callback. Resolved with a falsy value if the user cancels the process.
	 *     Rejected when an unexpected error stops the login process.
	 */
	showDialog( config ) {
		const { initOpenWindow, openWindow, data } = config;

		// Display a message in the current browser window, so that if the popup window doesn't open,
		// or if the user loses it on their desktop somehow, they can still see what was supposed to happen,
		// and have a way to retry or cancel it. This message stays open throughout the process.
		const windowManager = new OO.ui.WindowManager();
		$( OO.ui.getTeleportTarget() ).append( windowManager.$element );
		const m = new AuthMessageDialog();
		windowManager.addWindows( { authMessageDialog: m } );

		let w = initOpenWindow( m );

		return new Promise( ( resolve, reject ) => {
			const instance = windowManager.openWindow( 'authMessageDialog', data );

			instance.opened.then( () => {
				// Open a browser window covering the message we displayed.
				if ( !w ) {
					w = openWindow( m );
				}

				// When the fallback link is clicked, opening the login form in a fullscreen window,
				// close the popup window.
				m.$body.find( 'a' ).on( 'click', () => {
					if ( w ) {
						w.close();
					}
				} );

				m.on( 'retry', () => {
					if ( w ) {
						w.close();
					}
					w = openWindow( m );
				} );
				m.on( 'cancel', () => {
					if ( w ) {
						w.close();
					}
					m.close();
					resolve( null );
				} );

				// Close orphaned browser windows on the user's desktop if they leave/close the page.
				const onBeforeUnload = () => {
					if ( w ) {
						w.close();
					}
				};
				window.addEventListener( 'beforeunload', onBeforeUnload );
				instance.closed.then( () => window.removeEventListener( 'beforeunload', onBeforeUnload ) );

				// If the user leaves this window and then comes back, check if they have logged in
				// the old-fashioned way in the meantime.
				const onFocus = () => {
					this.checkLoggedIn().then( ( loggedIn ) => {
						if ( loggedIn ) {
							if ( w ) {
								w.close();
							}
							m.close();
							resolve( loggedIn );
						}
					} ).catch( reject );
				};
				window.addEventListener( 'focus', onFocus );
				instance.closed.then( () => window.removeEventListener( 'focus', onFocus ) );

				// Wait for a message from authSuccess.js.
				// Beware that it may never come if the initial popup was blocked,
				// in which case we rely on checking in the 'focus' event.
				const onMessage = ( event ) => {
					if ( event.origin !== window.origin ) {
						return;
					}
					if ( event.data !== SUCCESS_PAGE_MESSAGE ) {
						return;
					}

					if ( w ) {
						w.close();
					}

					// Okay, they went through the workflow. Confirm that they're logged in from our perspective,
					// because browsers are weird about cookies and they're also weird about popups.
					this.checkLoggedIn().then( ( loggedIn ) => {
						m.close();
						if ( loggedIn ) {
							// Yes!
							resolve( loggedIn );
						} else {
							// If they're not logged in, despite (presumably) providing correct credentials
							// and reaching the success page, something is pretty wrong. It could be a
							// server-side problem, or maybe the user's browser must be doing something funky.
							// It's definitely unexpected and should be logged as an error.
							reject( new AuthPopupError( 'Expected a successful login at this point' ) );
						}
					} ).catch( reject );
				};
				window.addEventListener( 'message', onMessage );
				instance.closed.then( () => window.removeEventListener( 'message', onMessage ) );
			} );
		} );
	}

}

module.exports = AuthPopup;
