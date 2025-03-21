/**
 * Utility library for managing components using the [CSS checkbox hack]{@link https://css-tricks.com/the-checkbox-hack/}.
 * To access call ```require('mediawiki.page.ready').checkboxHack```.
 *
 * The checkbox hack works without JavaScript for graphical user-interface users, but relies on
 * enhancements to work well for screen reader users. This module provides required a11y
 * interactivity for updating the `aria-expanded` accessibility state, and optional enhancements
 * for avoiding the distracting focus ring when using a pointing device, and target dismissal on
 * focus loss or external click.
 *
 * The checkbox hack is a prevalent pattern in MediaWiki similar to disclosure widgets[0]. Although
 * dated and out-of-fashion, it's surprisingly flexible allowing for both `details` / `summary`-like
 * patterns, menu components, and more complex structures (to be used sparingly) where the toggle
 * button and target are in different parts of the Document without an enclosing element, so long as
 * they can be described as a sibling to the input. It's complicated and frequent enough to warrant
 * single implementation.
 *
 * In time, proper disclosure widgets should replace checkbox hacks. However, the second pattern has
 * no equivalent so the checkbox hack may have a continued use case for some time to come.
 *
 * When the abstraction is leaky, the underlying implementation is simpler than anything built to
 * hide it. Attempts to abstract the functionality for the second pattern failed so all related code
 * celebrates the implementation as directly as possible.
 *
 * All the code assumes that when the input is checked, the target is in an expanded state.
 *
 * Consider the disclosure widget pattern first mentioned:
 *
 * ```html
 * <details>                                              <!-- Container -->
 *     <summary>Click to expand navigation menu</summary> <!-- Button -->
 *     <ul>                                               <!-- Target -->
 *         <li>Main page</li>
 *         <li>Random article</li>
 *         <li>Donate to Wikipedia</li>
 *     </ul>
 * </details>
 * ```
 *
 * Which is represented verbosely by a checkbox hack as such:
 *
 * ```html
 * <div>                                                 <!-- Container -->
 *     <input                                            <!-- Visually hidden checkbox -->
 *         type="checkbox"
 *         id="sidebar-checkbox"
 *         class="mw-checkbox-hack-checkbox"
 *         {{#visible}}checked{{/visible}}
 *         role="button"
 *         aria-labelledby="sidebar-button"
 *         aria-expanded="true||false"
 *         aria-haspopup="true">                         <!-- Optional attribute -->
 *     <label                                            <!-- Button -->
 *         id="sidebar-button"
 *         class="mw-checkbox-hack-button"
 *         for="sidebar-checkbox"
 *         aria-hidden="true">
 *         Click to expand navigation menu
 *     </label>
 *     <ul id="sidebar" class="mw-checkbox-hack-target"> <!-- Target -->
 *         <li>Main page</li>
 *         <li>Random article</li>
 *         <li>Donate to Wikipedia</li>
 *     </ul>
 * </div>
 * ```
 *
 * Where the checkbox is the input, the label is the button, and the target is the unordered list.
 * `aria-haspopup` is an optional attribute that can be applied when dealing with popup elements (i.e. menus).
 *
 * Note that while the label acts as a button for visual users (i.e. it's usually styled as a button and is clicked),
 * the checkbox is what's actually interacted with for keyboard and screenreader users. Many of the HTML attributes
 * and JS enhancements serve to give the checkbox the behavior and semantics of a button.
 * For this reason any hover/focus/active state styles for the button should be applied based on the checkbox state
 * (i.e. https://github.com/wikimedia/mediawiki/blob/master/resources/src/mediawiki.ui.button/button.less#L90)
 *
 * Consider the disparate pattern:
 *
 * ```html
 * <!-- ... -->
 * <!-- The only requirement is that the button and target can be described as a sibling to the
 *      checkbox. -->
 * <input
 *     type="checkbox"
 *     id="sidebar-checkbox"
 *     class="mw-checkbox-hack-checkbox"
 *     {{#visible}}checked{{/visible}}
 *     role="button"
 *     aria-labelledby="sidebar-button"
 *     aria-expanded="true||false"
 *     aria-haspopup="true">
 * <!-- ... -->
 * <label
 *     id="sidebar-button"
 *     class="mw-checkbox-hack-button"
 *     for="sidebar-checkbox"
 *     aria-hidden="true">
 *     Toggle navigation menu
 * </label>
 * <!-- ... -->
 * <ul id="sidebar" class="mw-checkbox-hack-target">
 *     <li>Main page</li>
 *     <li>Random article</li>
 *     <li>Donate to Wikipedia</li>
 * </ul>
 * <!-- ... -->
 * ```
 *
 * Which is the same as the disclosure widget but without the enclosing container and the input only
 * needs to be a preceding sibling of the button and target. It's possible to bend the checkbox hack
 * further to allow the button and target to be at an arbitrary depth so long as a parent can be
 * described as a succeeding sibling of the input, but this requires a mixin implementation that
 * duplicates the rules for each relation selector.
 *
 * Exposed APIs should be considered stable.
 *
 * Accompanying checkbox hack styles are tracked in T252774.
 *
 * [0]: https://developer.mozilla.org/docs/Web/HTML/Element/details
 *
 * @namespace CheckboxHack
 * @memberof module:mediawiki.page.ready
 */
/**
 * Revise the button's `aria-expanded` state to match the checked state.
 *
 * @memberof module:mediawiki.page.ready.CheckboxHack
 * @param {HTMLInputElement} checkbox
 * @param {HTMLElement} button
 */
function updateAriaExpanded( checkbox, button ) {
	if ( button ) {
		mw.log.warn( '[1.38] The button parameter in updateAriaExpanded is deprecated, aria-expanded will be applied to the checkbox going forward. View the updated checkbox hack documentation for more details.' );
		button.setAttribute( 'aria-expanded', checkbox.checked.toString() );
		return;
	}

	checkbox.setAttribute( 'aria-expanded', checkbox.checked.toString() );
}

/**
 * Set the checked state and fire the 'input' event.
 * Programmatic changes to checkbox.checked do not trigger an input or change event.
 * The input event in turn will call updateAriaExpanded().
 *
 * setCheckedState() is called when a user event on some element other than the checkbox
 * should result in changing the checkbox state.
 *
 * Per https://html.spec.whatwg.org/multipage/indices.html#event-input
 * Input event is fired at controls when the user changes the value.
 * Per https://html.spec.whatwg.org/multipage/input.html#checkbox-state-(type=checkbox):event-input
 * Fire an event named input at the element with the bubbles attribute initialized to true.
 *
 * https://html.spec.whatwg.org/multipage/indices.html#event-change
 * For completeness the 'change' event should be fired too,
 * however we make no use of the 'change' event,
 * nor expect it to be used, thus firing it
 * would be unnecessary load.
 *
 * @param {HTMLInputElement} checkbox
 * @param {boolean} checked
 * @ignore
 */
function setCheckedState( checkbox, checked ) {
	checkbox.checked = checked;

	const e = new Event( 'input', { bubbles: true, composed: true } );
	checkbox.dispatchEvent( e );
}

/**
 * Returns true if the Event's target is an inclusive descendant of any the checkbox hack's
 * constituents (checkbox, button, or target), and false otherwise.
 *
 * @param {HTMLInputElement} checkbox
 * @param {HTMLElement} button
 * @param {Node} target
 * @param {Event} event
 * @return {boolean}
 * @ignore
 */
function containsEventTarget( checkbox, button, target, event ) {
	return event.target instanceof Node && (
		checkbox.contains( event.target ) ||
		button.contains( event.target ) ||
		target.contains( event.target )
	);
}

/**
 * Dismiss the target when event is outside the checkbox, button, and target.
 * In simple terms this closes the target (menu, typically) when clicking somewhere else.
 *
 * @param {HTMLInputElement} checkbox
 * @param {HTMLElement} button
 * @param {Node} target
 * @param {Event} event
 * @ignore
 */
function dismissIfExternalEventTarget( checkbox, button, target, event ) {
	if ( checkbox.checked && !containsEventTarget( checkbox, button, target, event ) ) {
		setCheckedState( checkbox, false );
	}
}

/**
 * Update the `aria-expanded` attribute based on checkbox state (target visibility) changes.
 *
 * @memberof module:mediawiki.page.ready.CheckboxHack
 * @param {HTMLInputElement} checkbox
 * @param {HTMLElement} button
 * @return {function(): void} Cleanup function that removes the added event listeners.
 */
function bindUpdateAriaExpandedOnInput( checkbox, button ) {
	if ( button ) {
		mw.log.warn( '[1.38] The button parameter in bindUpdateAriaExpandedOnInput is deprecated, aria-expanded will be applied to the checkbox going forward. View the updated checkbox hack documentation for more details.' );
	}

	const listener = updateAriaExpanded.bind( undefined, checkbox, button );
	// Whenever the checkbox state changes, update the `aria-expanded` state.
	checkbox.addEventListener( 'input', listener );

	return function () {
		checkbox.removeEventListener( 'input', listener );
	};
}

/**
 * Manually change the checkbox state to avoid a focus change when using a pointing device.
 *
 * @memberof module:mediawiki.page.ready.CheckboxHack
 * @param {HTMLInputElement} checkbox
 * @param {HTMLElement} button
 * @return {function(): void} Cleanup function that removes the added event listeners.
 */
function bindToggleOnClick( checkbox, button ) {
	function listener( event ) {
		// Do not allow the browser to handle the checkbox. Instead, manually toggle it which does
		// not alter focus.
		event.preventDefault();
		setCheckedState( checkbox, !checkbox.checked );
	}
	button.addEventListener( 'click', listener, true );

	return function () {
		button.removeEventListener( 'click', listener, true );
	};
}

/**
 * Manually change the checkbox state when the button is focused and SPACE is pressed.
 *
 * @deprecated Use `bindToggleOnEnter` instead.
 * @memberof module:mediawiki.page.ready.CheckboxHack
 * @param {HTMLInputElement} checkbox
 * @param {HTMLElement} button
 * @return {function(): void} Cleanup function that removes the added event listeners.
 */
function bindToggleOnSpaceEnter( checkbox, button ) {
	mw.log.warn( '[1.38] bindToggleOnSpaceEnter is deprecated. Use `bindToggleOnEnter` instead.' );

	function isEnterOrSpace( /** @type {KeyboardEvent} */ event ) {
		return event.key === ' ' || event.key === 'Enter';
	}

	function onKeydown( /** @type {KeyboardEvent} */ event ) {
		// Only handle SPACE and ENTER.
		if ( !isEnterOrSpace( event ) ) {
			return;
		}
		// Prevent the browser from scrolling when pressing space. The browser will
		// try to do this unless the "button" element is a button or a checkbox.
		// Depending on the actual "button" element, this also possibly prevents a
		// native click event from being triggered so we programatically trigger a
		// click event in the keyup handler.
		event.preventDefault();
	}

	function onKeyup( /** @type {KeyboardEvent} */ event ) {
		// Only handle SPACE and ENTER.
		if ( !isEnterOrSpace( event ) ) {
			return;
		}

		// A native button element triggers a click event when the space or enter
		// keys are pressed. Since the passed in "button" may or may not be a
		// button, programmatically trigger a click event to make it act like a
		// button.
		button.click();
	}

	button.addEventListener( 'keydown', onKeydown );
	button.addEventListener( 'keyup', onKeyup );

	return function () {
		button.removeEventListener( 'keydown', onKeydown );
		button.removeEventListener( 'keyup', onKeyup );
	};
}

/**
 * Manually change the checkbox state when the button is focused and Enter is pressed.
 *
 * @memberof module:mediawiki.page.ready.CheckboxHack
 * @param {HTMLInputElement} checkbox
 * @return {function(): void} Cleanup function that removes the added event listeners.
 */
function bindToggleOnEnter( checkbox ) {
	function onKeyup( /** @type {KeyboardEvent} */ event ) {
		// Only handle ENTER.
		if ( event.key !== 'Enter' ) {
			return;
		}

		setCheckedState( checkbox, !checkbox.checked );
	}

	checkbox.addEventListener( 'keyup', onKeyup );

	return function () {
		checkbox.removeEventListener( 'keyup', onKeyup );
	};
}

/**
 * Dismiss the target when clicking elsewhere and update the `aria-expanded` attribute based on
 * checkbox state (target visibility).
 *
 * @memberof module:mediawiki.page.ready.CheckboxHack
 * @param {window} window
 * @param {HTMLInputElement} checkbox
 * @param {HTMLElement} button
 * @param {Node} target
 * @return {function(): void} Cleanup function that removes the added event listeners.
 */
function bindDismissOnClickOutside( window, checkbox, button, target ) {
	const listener = dismissIfExternalEventTarget.bind( undefined, checkbox, button, target );
	window.addEventListener( 'click', listener, true );

	return function () {
		window.removeEventListener( 'click', listener, true );
	};
}

/**
 * Dismiss the target when focusing elsewhere and update the `aria-expanded` attribute based on
 * checkbox state (target visibility).
 *
 * @param {window} window
 * @param {HTMLInputElement} checkbox
 * @param {HTMLElement} button
 * @param {Node} target
 * @return {function(): void} Cleanup function that removes the added event listeners.
 * @memberof module:mediawiki.page.ready.CheckboxHack
 */
function bindDismissOnFocusLoss( window, checkbox, button, target ) {
	// If focus is given to any element outside the target, dismiss the target. Setting a focusout
	// listener on the target would be preferable, but this interferes with the click listener.
	const listener = dismissIfExternalEventTarget.bind( undefined, checkbox, button, target );
	window.addEventListener( 'focusin', listener, true );

	return function () {
		window.removeEventListener( 'focusin', listener, true );
	};
}

/**
 * Dismiss the target when clicking on a link to prevent the target from being open
 * when navigating to a new page.
 *
 * @param {HTMLInputElement} checkbox
 * @param {Node} target
 * @return {function(): void} Cleanup function that removes the added event listeners.
 * @memberof module:mediawiki.page.ready.CheckboxHack
 */
function bindDismissOnClickLink( checkbox, target ) {
	function dismissIfClickLinkEvent( event ) {
		// Handle clicks to links and link children elements
		if (
			// check that the element wasn't removed from the DOM.
			event.target && event.target.parentNode &&
			( event.target.nodeName === 'A' || event.target.parentNode.nodeName === 'A' )
		) {
			setCheckedState( checkbox, false );
		}
	}
	target.addEventListener( 'click', dismissIfClickLinkEvent );

	return function () {
		target.removeEventListener( 'click', dismissIfClickLinkEvent );
	};
}

/**
 * Dismiss the target when clicking or focusing elsewhere and update the `aria-expanded` attribute
 * based on checkbox state (target visibility) changes made by **the user.** When tapping the button
 * itself, clear the focus outline.
 *
 * This function calls the other bind* functions and is the only expected interaction for most use
 * cases. It's constituents are provided distinctly for the other use cases.
 *
 * @memberof module:mediawiki.page.ready.CheckboxHack
 * @param {window} window
 * @param {HTMLInputElement} checkbox The underlying hidden checkbox that controls target
 *   visibility.
 * @param {HTMLElement} button The visible label icon associated with the checkbox. This button
 *   toggles the state of the underlying checkbox.
 * @param {Node} target The Node to toggle visibility of based on checkbox state.
 * @return {function(): void} Cleanup function that removes the added event listeners.
 */
function bind( window, checkbox, button, target ) {
	const cleanups = [
		bindUpdateAriaExpandedOnInput( checkbox ),
		bindToggleOnClick( checkbox, button ),
		bindToggleOnEnter( checkbox ),
		bindDismissOnClickOutside( window, checkbox, button, target ),
		bindDismissOnFocusLoss( window, checkbox, button, target ),
		bindDismissOnClickLink( checkbox, target )
	];

	return function () {
		cleanups.forEach( ( cleanup ) => {
			cleanup();
		} );
	};
}

module.exports = {
	updateAriaExpanded,
	bindUpdateAriaExpandedOnInput,
	bindToggleOnClick,
	bindToggleOnSpaceEnter,
	bindToggleOnEnter,
	bindDismissOnClickOutside,
	bindDismissOnFocusLoss,
	bindDismissOnClickLink,
	bind
};
