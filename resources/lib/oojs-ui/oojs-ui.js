/*!
 * OOjs UI v0.1.0-pre (eca1fc20e7)
 * https://www.mediawiki.org/wiki/OOjs_UI
 *
 * Copyright 2011–2014 OOjs Team and other contributors.
 * Released under the MIT license
 * http://oojs.mit-license.org
 *
 * Date: Fri Apr 11 2014 16:47:56 GMT-0700 (PDT)
 */
( function ( OO ) {

'use strict';
/**
 * Namespace for all classes, static methods and static properties.
 *
 * @class
 * @singleton
 */
OO.ui = {};

OO.ui.bind = $.proxy;

/**
 * @property {Object}
 */
OO.ui.Keys = {
	'UNDEFINED': 0,
	'BACKSPACE': 8,
	'DELETE': 46,
	'LEFT': 37,
	'RIGHT': 39,
	'UP': 38,
	'DOWN': 40,
	'ENTER': 13,
	'END': 35,
	'HOME': 36,
	'TAB': 9,
	'PAGEUP': 33,
	'PAGEDOWN': 34,
	'ESCAPE': 27,
	'SHIFT': 16,
	'SPACE': 32
};

/**
 * Get the user's language and any fallback languages.
 *
 * These language codes are used to localize user interface elements in the user's language.
 *
 * In environments that provide a localization system, this function should be overridden to
 * return the user's language(s). The default implementation returns English (en) only.
 *
 * @return {string[]} Language codes, in descending order of priority
 */
OO.ui.getUserLanguages = function () {
	return [ 'en' ];
};

/**
 * Get a value in an object keyed by language code.
 *
 * @param {Object.<string,Mixed>} obj Object keyed by language code
 * @param {string|null} [lang] Language code, if omitted or null defaults to any user language
 * @param {string} [fallback] Fallback code, used if no matching language can be found
 * @return {Mixed} Local value
 */
OO.ui.getLocalValue = function ( obj, lang, fallback ) {
	var i, len, langs;

	// Requested language
	if ( obj[lang] ) {
		return obj[lang];
	}
	// Known user language
	langs = OO.ui.getUserLanguages();
	for ( i = 0, len = langs.length; i < len; i++ ) {
		lang = langs[i];
		if ( obj[lang] ) {
			return obj[lang];
		}
	}
	// Fallback language
	if ( obj[fallback] ) {
		return obj[fallback];
	}
	// First existing language
	for ( lang in obj ) {
		return obj[lang];
	}

	return undefined;
};

( function () {

/**
 * Message store for the default implementation of OO.ui.msg
 *
 * Environments that provide a localization system should not use this, but should override
 * OO.ui.msg altogether.
 *
 * @private
 */
var messages = {
	// Label text for button to exit from dialog
	'ooui-dialog-action-close': 'Close',
	// Tool tip for a button that moves items in a list down one place
	'ooui-outline-control-move-down': 'Move item down',
	// Tool tip for a button that moves items in a list up one place
	'ooui-outline-control-move-up': 'Move item up',
	// Tool tip for a button that removes items from a list
	'ooui-outline-control-remove': 'Remove item',
	// Label for the toolbar group that contains a list of all other available tools
	'ooui-toolbar-more': 'More'
};

/**
 * Get a localized message.
 *
 * In environments that provide a localization system, this function should be overridden to
 * return the message translated in the user's language. The default implementation always returns
 * English messages.
 *
 * After the message key, message parameters may optionally be passed. In the default implementation,
 * any occurrences of $1 are replaced with the first parameter, $2 with the second parameter, etc.
 * Alternative implementations of OO.ui.msg may use any substitution system they like, as long as
 * they support unnamed, ordered message parameters.
 *
 * @abstract
 * @param {string} key Message key
 * @param {Mixed...} [params] Message parameters
 * @return {string} Translated message with parameters substituted
 */
OO.ui.msg = function ( key ) {
	var message = messages[key], params = Array.prototype.slice.call( arguments, 1 );
	if ( typeof message === 'string' ) {
		// Perform $1 substitution
		message = message.replace( /\$(\d+)/g, function ( unused, n ) {
			var i = parseInt( n, 10 );
			return params[i - 1] !== undefined ? params[i - 1] : '$' + n;
		} );
	} else {
		// Return placeholder if message not found
		message = '[' + key + ']';
	}
	return message;
};

/** */
OO.ui.deferMsg = function ( key ) {
	return function () {
		return OO.ui.msg( key );
	};
};

/** */
OO.ui.resolveMsg = function ( msg ) {
	if ( $.isFunction( msg ) ) {
		return msg();
	}
	return msg;
};

} )();
/**
 * DOM element abstraction.
 *
 * @abstract
 * @class
 *
 * @constructor
 * @param {Object} [config] Configuration options
 * @cfg {Function} [$] jQuery for the frame the widget is in
 * @cfg {string[]} [classes] CSS class names
 * @cfg {jQuery} [$content] Content elements to append
 */
OO.ui.Element = function OoUiElement( config ) {
	// Configuration initialization
	config = config || {};

	// Properties
	this.$ = config.$ || OO.ui.Element.getJQuery( document );
	this.$element = this.$( this.$.context.createElement( this.getTagName() ) );
	this.elementGroup = null;

	// Initialization
	if ( $.isArray( config.classes ) ) {
		this.$element.addClass( config.classes.join( ' ' ) );
	}
	if ( config.$content ) {
		this.$element.append( config.$content );
	}
};

/* Setup */

OO.initClass( OO.ui.Element );

/* Static Properties */

/**
 * HTML tag name.
 *
 * This may be ignored if getTagName is overridden.
 *
 * @static
 * @inheritable
 * @property {string}
 */
OO.ui.Element.static.tagName = 'div';

/* Static Methods */

/**
 * Get a jQuery function within a specific document.
 *
 * @static
 * @param {jQuery|HTMLElement|HTMLDocument|Window} context Context to bind the function to
 * @param {OO.ui.Frame} [frame] Frame of the document context
 * @return {Function} Bound jQuery function
 */
OO.ui.Element.getJQuery = function ( context, frame ) {
	function wrapper( selector ) {
		return $( selector, wrapper.context );
	}

	wrapper.context = this.getDocument( context );

	if ( frame ) {
		wrapper.frame = frame;
	}

	return wrapper;
};

/**
 * Get the document of an element.
 *
 * @static
 * @param {jQuery|HTMLElement|HTMLDocument|Window} obj Object to get the document for
 * @return {HTMLDocument|null} Document object
 */
OO.ui.Element.getDocument = function ( obj ) {
	// jQuery - selections created "offscreen" won't have a context, so .context isn't reliable
	return ( obj[0] && obj[0].ownerDocument ) ||
		// Empty jQuery selections might have a context
		obj.context ||
		// HTMLElement
		obj.ownerDocument ||
		// Window
		obj.document ||
		// HTMLDocument
		( obj.nodeType === 9 && obj ) ||
		null;
};

/**
 * Get the window of an element or document.
 *
 * @static
 * @param {jQuery|HTMLElement|HTMLDocument|Window} obj Context to get the window for
 * @return {Window} Window object
 */
OO.ui.Element.getWindow = function ( obj ) {
	var doc = this.getDocument( obj );
	return doc.parentWindow || doc.defaultView;
};

/**
 * Get the direction of an element or document.
 *
 * @static
 * @param {jQuery|HTMLElement|HTMLDocument|Window} obj Context to get the direction for
 * @return {string} Text direction, either `ltr` or `rtl`
 */
OO.ui.Element.getDir = function ( obj ) {
	var isDoc, isWin;

	if ( obj instanceof jQuery ) {
		obj = obj[0];
	}
	isDoc = obj.nodeType === 9;
	isWin = obj.document !== undefined;
	if ( isDoc || isWin ) {
		if ( isWin ) {
			obj = obj.document;
		}
		obj = obj.body;
	}
	return $( obj ).css( 'direction' );
};

/**
 * Get the offset between two frames.
 *
 * TODO: Make this function not use recursion.
 *
 * @static
 * @param {Window} from Window of the child frame
 * @param {Window} [to=window] Window of the parent frame
 * @param {Object} [offset] Offset to start with, used internally
 * @return {Object} Offset object, containing left and top properties
 */
OO.ui.Element.getFrameOffset = function ( from, to, offset ) {
	var i, len, frames, frame, rect;

	if ( !to ) {
		to = window;
	}
	if ( !offset ) {
		offset = { 'top': 0, 'left': 0 };
	}
	if ( from.parent === from ) {
		return offset;
	}

	// Get iframe element
	frames = from.parent.document.getElementsByTagName( 'iframe' );
	for ( i = 0, len = frames.length; i < len; i++ ) {
		if ( frames[i].contentWindow === from ) {
			frame = frames[i];
			break;
		}
	}

	// Recursively accumulate offset values
	if ( frame ) {
		rect = frame.getBoundingClientRect();
		offset.left += rect.left;
		offset.top += rect.top;
		if ( from !== to ) {
			this.getFrameOffset( from.parent, offset );
		}
	}
	return offset;
};

/**
 * Get the offset between two elements.
 *
 * @static
 * @param {jQuery} $from
 * @param {jQuery} $to
 * @return {Object} Translated position coordinates, containing top and left properties
 */
OO.ui.Element.getRelativePosition = function ( $from, $to ) {
	var from = $from.offset(),
		to = $to.offset();
	return { 'top': Math.round( from.top - to.top ), 'left': Math.round( from.left - to.left ) };
};

/**
 * Get element border sizes.
 *
 * @static
 * @param {HTMLElement} el Element to measure
 * @return {Object} Dimensions object with `top`, `left`, `bottom` and `right` properties
 */
OO.ui.Element.getBorders = function ( el ) {
	var doc = el.ownerDocument,
		win = doc.parentWindow || doc.defaultView,
		style = win && win.getComputedStyle ?
			win.getComputedStyle( el, null ) :
			el.currentStyle,
		$el = $( el ),
		top = parseFloat( style ? style.borderTopWidth : $el.css( 'borderTopWidth' ) ) || 0,
		left = parseFloat( style ? style.borderLeftWidth : $el.css( 'borderLeftWidth' ) ) || 0,
		bottom = parseFloat( style ? style.borderBottomWidth : $el.css( 'borderBottomWidth' ) ) || 0,
		right = parseFloat( style ? style.borderRightWidth : $el.css( 'borderRightWidth' ) ) || 0;

	return {
		'top': Math.round( top ),
		'left': Math.round( left ),
		'bottom': Math.round( bottom ),
		'right': Math.round( right )
	};
};

/**
 * Get dimensions of an element or window.
 *
 * @static
 * @param {HTMLElement|Window} el Element to measure
 * @return {Object} Dimensions object with `borders`, `scroll`, `scrollbar` and `rect` properties
 */
OO.ui.Element.getDimensions = function ( el ) {
	var $el, $win,
		doc = el.ownerDocument || el.document,
		win = doc.parentWindow || doc.defaultView;

	if ( win === el || el === doc.documentElement ) {
		$win = $( win );
		return {
			'borders': { 'top': 0, 'left': 0, 'bottom': 0, 'right': 0 },
			'scroll': {
				'top': $win.scrollTop(),
				'left': $win.scrollLeft()
			},
			'scrollbar': { 'right': 0, 'bottom': 0 },
			'rect': {
				'top': 0,
				'left': 0,
				'bottom': $win.innerHeight(),
				'right': $win.innerWidth()
			}
		};
	} else {
		$el = $( el );
		return {
			'borders': this.getBorders( el ),
			'scroll': {
				'top': $el.scrollTop(),
				'left': $el.scrollLeft()
			},
			'scrollbar': {
				'right': $el.innerWidth() - el.clientWidth,
				'bottom': $el.innerHeight() - el.clientHeight
			},
			'rect': el.getBoundingClientRect()
		};
	}
};

/**
 * Get closest scrollable container.
 *
 * Traverses up until either a scrollable element or the root is reached, in which case the window
 * will be returned.
 *
 * @static
 * @param {HTMLElement} el Element to find scrollable container for
 * @param {string} [dimension] Dimension of scrolling to look for; `x`, `y` or omit for either
 * @return {HTMLElement|Window} Closest scrollable container
 */
OO.ui.Element.getClosestScrollableContainer = function ( el, dimension ) {
	var i, val,
		props = [ 'overflow' ],
		$parent = $( el ).parent();

	if ( dimension === 'x' || dimension === 'y' ) {
		props.push( 'overflow-' + dimension );
	}

	while ( $parent.length ) {
		if ( $parent[0] === el.ownerDocument.body ) {
			return $parent[0];
		}
		i = props.length;
		while ( i-- ) {
			val = $parent.css( props[i] );
			if ( val === 'auto' || val === 'scroll' ) {
				return $parent[0];
			}
		}
		$parent = $parent.parent();
	}
	return this.getDocument( el ).body;
};

/**
 * Scroll element into view.
 *
 * @static
 * @param {HTMLElement} el Element to scroll into view
 * @param {Object} [config={}] Configuration config
 * @param {string} [config.duration] jQuery animation duration value
 * @param {string} [config.direction] Scroll in only one direction, e.g. 'x' or 'y', omit
 *  to scroll in both directions
 * @param {Function} [config.complete] Function to call when scrolling completes
 */
OO.ui.Element.scrollIntoView = function ( el, config ) {
	// Configuration initialization
	config = config || {};

	var anim = {},
		callback = typeof config.complete === 'function' && config.complete,
		sc = this.getClosestScrollableContainer( el, config.direction ),
		$sc = $( sc ),
		eld = this.getDimensions( el ),
		scd = this.getDimensions( sc ),
		rel = {
			'top': eld.rect.top - ( scd.rect.top + scd.borders.top ),
			'bottom': scd.rect.bottom - scd.borders.bottom - scd.scrollbar.bottom - eld.rect.bottom,
			'left': eld.rect.left - ( scd.rect.left + scd.borders.left ),
			'right': scd.rect.right - scd.borders.right - scd.scrollbar.right - eld.rect.right
		};

	if ( !config.direction || config.direction === 'y' ) {
		if ( rel.top < 0 ) {
			anim.scrollTop = scd.scroll.top + rel.top;
		} else if ( rel.top > 0 && rel.bottom < 0 ) {
			anim.scrollTop = scd.scroll.top + Math.min( rel.top, -rel.bottom );
		}
	}
	if ( !config.direction || config.direction === 'x' ) {
		if ( rel.left < 0 ) {
			anim.scrollLeft = scd.scroll.left + rel.left;
		} else if ( rel.left > 0 && rel.right < 0 ) {
			anim.scrollLeft = scd.scroll.left + Math.min( rel.left, -rel.right );
		}
	}
	if ( !$.isEmptyObject( anim ) ) {
		$sc.stop( true ).animate( anim, config.duration || 'fast' );
		if ( callback ) {
			$sc.queue( function ( next ) {
				callback();
				next();
			} );
		}
	} else {
		if ( callback ) {
			callback();
		}
	}
};

/* Methods */

/**
 * Get the HTML tag name.
 *
 * Override this method to base the result on instance information.
 *
 * @return {string} HTML tag name
 */
OO.ui.Element.prototype.getTagName = function () {
	return this.constructor.static.tagName;
};

/**
 * Check if the element is attached to the DOM
 * @return {boolean} The element is attached to the DOM
 */
OO.ui.Element.prototype.isElementAttached = function () {
	return $.contains( this.getElementDocument(), this.$element[0] );
};

/**
 * Get the DOM document.
 *
 * @return {HTMLDocument} Document object
 */
OO.ui.Element.prototype.getElementDocument = function () {
	return OO.ui.Element.getDocument( this.$element );
};

/**
 * Get the DOM window.
 *
 * @return {Window} Window object
 */
OO.ui.Element.prototype.getElementWindow = function () {
	return OO.ui.Element.getWindow( this.$element );
};

/**
 * Get closest scrollable container.
 *
 * @see #static-method-getClosestScrollableContainer
 */
OO.ui.Element.prototype.getClosestScrollableElementContainer = function () {
	return OO.ui.Element.getClosestScrollableContainer( this.$element[0] );
};

/**
 * Get group element is in.
 *
 * @return {OO.ui.GroupElement|null} Group element, null if none
 */
OO.ui.Element.prototype.getElementGroup = function () {
	return this.elementGroup;
};

/**
 * Set group element is in.
 *
 * @param {OO.ui.GroupElement|null} group Group element, null if none
 * @chainable
 */
OO.ui.Element.prototype.setElementGroup = function ( group ) {
	this.elementGroup = group;
	return this;
};

/**
 * Scroll element into view.
 *
 * @see #static-method-scrollIntoView
 * @param {Object} [config={}]
 */
OO.ui.Element.prototype.scrollElementIntoView = function ( config ) {
	return OO.ui.Element.scrollIntoView( this.$element[0], config );
};

/**
 * Bind a handler for an event on this.$element
 * @see #static-method-onDOMEvent
 * @param {string} event
 * @param {Function} callback
 */
OO.ui.Element.prototype.onDOMEvent = function ( event, callback ) {
	OO.ui.Element.onDOMEvent( this.$element, event, callback );
};

/**
 * Unbind a handler bound with #offDOMEvent
 * @see #static-method-offDOMEvent
 * @param {string} event
 * @param {Function} callback
 */
OO.ui.Element.prototype.offDOMEvent = function ( event, callback ) {
	OO.ui.Element.offDOMEvent( this.$element, event, callback );
};

( function () {
	// Static
	var specialFocusin;

	function handler( e ) {
		jQuery.event.simulate( 'focusin', e.target, jQuery.event.fix( e ), /* bubble = */ true );
	}

	specialFocusin = {
		setup: function () {
			var doc = this.ownerDocument || this,
				attaches = $.data( doc, 'ooui-focusin-attaches' );
			if ( !attaches ) {
				doc.addEventListener( 'focus', handler, true );
			}
			$.data( doc, 'ooui-focusin-attaches', ( attaches || 0 ) + 1 );
		},
		teardown: function () {
			var doc = this.ownerDocument || this,
				attaches = $.data( doc, 'ooui-focusin-attaches' ) - 1;
			if ( !attaches ) {
				doc.removeEventListener( 'focus', handler, true );
				$.removeData( doc, 'ooui-focusin-attaches' );
			} else {
				$.data( doc, 'ooui-focusin-attaches', attaches );
			}
		}
	};

	/**
	 * Bind a handler for an event on a DOM element.
	 *
	 * Uses jQuery internally for everything except for events which are
	 * known to have issues in the browser or in jQuery. This method
	 * should become obsolete eventually.
	 *
	 * @static
	 * @param {HTMLElement|jQuery} el DOM element
	 * @param {string} event Event to bind
	 * @param {Function} callback Callback to call when the event fires
	 */
	OO.ui.Element.onDOMEvent = function ( el, event, callback ) {
		var orig;

		if ( event === 'focusin' ) {
			// jQuery 1.8.3 has a bug with handling focusin events inside iframes.
			// Firefox doesn't support focusin at all, so we listen for 'focus' on the
			// document, and simulate a 'focusin' event on the target element and make
			// it bubble from there.
			//
			// - http://jsfiddle.net/sw3hr/
			// - http://bugs.jquery.com/ticket/14180
			// - https://github.com/jquery/jquery/commit/1cecf64e5aa4153

			// Replace jQuery's override with our own
			orig = $.event.special.focusin;
			$.event.special.focusin = specialFocusin;

			$( el ).on( event, callback );

			// Restore
			$.event.special.focusin = orig;

		} else {
			$( el ).on( event, callback );
		}
	};

	/**
	 * Unbind a handler bound with #static-method-onDOMEvent.
	 *
	 * @static
	 * @param {HTMLElement|jQuery} el DOM element
	 * @param {string} event Event to unbind
	 * @param {Function} [callback] Callback to unbind
	 */
	OO.ui.Element.offDOMEvent = function ( el, event, callback ) {
		var orig;
		if ( event === 'focusin' ) {
			orig = $.event.special.focusin;
			$.event.special.focusin = specialFocusin;
			$( el ).off( event, callback );
			$.event.special.focusin = orig;
		} else {
			$( el ).off( event, callback );
		}
	};
}() );
/**
 * Embedded iframe with the same styles as its parent.
 *
 * @class
 * @extends OO.ui.Element
 * @mixins OO.EventEmitter
 *
 * @constructor
 * @param {Object} [config] Configuration options
 */
OO.ui.Frame = function OoUiFrame( config ) {
	// Parent constructor
	OO.ui.Frame.super.call( this, config );

	// Mixin constructors
	OO.EventEmitter.call( this );

	// Properties
	this.loading = false;
	this.loaded = false;
	this.config = config;

	// Initialize
	this.$element
		.addClass( 'oo-ui-frame' )
		.attr( { 'frameborder': 0, 'scrolling': 'no' } );

};

/* Setup */

OO.inheritClass( OO.ui.Frame, OO.ui.Element );
OO.mixinClass( OO.ui.Frame, OO.EventEmitter );

/* Static Properties */

/**
 * @static
 * @inheritdoc
 */
OO.ui.Frame.static.tagName = 'iframe';

/* Events */

/**
 * @event load
 */

/* Static Methods */

/**
 * Transplant the CSS styles from as parent document to a frame's document.
 *
 * This loops over the style sheets in the parent document, and copies their nodes to the
 * frame's document. It then polls the document to see when all styles have loaded, and once they
 * have, invokes the callback.
 *
 * If the styles still haven't loaded after a long time (5 seconds by default), we give up waiting
 * and invoke the callback anyway. This protects against cases like a display: none; iframe in
 * Firefox, where the styles won't load until the iframe becomes visible.
 *
 * For details of how we arrived at the strategy used in this function, see #load.
 *
 * @static
 * @inheritable
 * @param {HTMLDocument} parentDoc Document to transplant styles from
 * @param {HTMLDocument} frameDoc Document to transplant styles to
 * @param {Function} [callback] Callback to execute once styles have loaded
 * @param {number} [timeout=5000] How long to wait before giving up (in ms). If 0, never give up.
 */
OO.ui.Frame.static.transplantStyles = function ( parentDoc, frameDoc, callback, timeout ) {
	var i, numSheets, styleNode, newNode, timeoutID, pollNodeId, $pendingPollNodes,
		$pollNodes = $( [] ),
		// Fake font-family value
		fontFamily = 'oo-ui-frame-transplantStyles-loaded';

	for ( i = 0, numSheets = parentDoc.styleSheets.length; i < numSheets; i++ ) {
		styleNode = parentDoc.styleSheets[i].ownerNode;
		if ( callback && styleNode.nodeName.toLowerCase() === 'link' ) {
			// External stylesheet
			// Create a node with a unique ID that we're going to monitor to see when the CSS
			// has loaded
			pollNodeId = 'oo-ui-frame-transplantStyles-loaded-' + i;
			$pollNodes = $pollNodes.add( $( '<div>', frameDoc )
				.attr( 'id', pollNodeId )
				.appendTo( frameDoc.body )
			);

			// Add <style>@import url(...); #pollNodeId { font-family: ... }</style>
			// The font-family rule will only take effect once the @import finishes
			newNode = frameDoc.createElement( 'style' );
			newNode.textContent = '@import url(' + styleNode.href + ');\n' +
				'#' + pollNodeId + ' { font-family: ' + fontFamily + '; }';
		} else {
			// Not an external stylesheet, or no polling required; just copy the node over
			newNode = frameDoc.importNode( styleNode, true );
		}
		frameDoc.head.appendChild( newNode );
	}

	if ( callback ) {
		// Poll every 100ms until all external stylesheets have loaded
		$pendingPollNodes = $pollNodes;
		timeoutID = setTimeout( function pollExternalStylesheets() {
			while (
				$pendingPollNodes.length > 0 &&
				$pendingPollNodes.eq( 0 ).css( 'font-family' ) === fontFamily
			) {
				$pendingPollNodes = $pendingPollNodes.slice( 1 );
			}

			if ( $pendingPollNodes.length === 0 ) {
				// We're done!
				if ( timeoutID !== null ) {
					timeoutID = null;
					$pollNodes.remove();
					callback();
				}
			} else {
				timeoutID = setTimeout( pollExternalStylesheets, 100 );
			}
		}, 100 );
		// ...but give up after a while
		if ( timeout !== 0 ) {
			setTimeout( function () {
				if ( timeoutID ) {
					clearTimeout( timeoutID );
					timeoutID = null;
					$pollNodes.remove();
					callback();
				}
			}, timeout || 5000 );
		}
	}
};

/* Methods */

/**
 * Load the frame contents.
 *
 * Once the iframe's stylesheets are loaded, the `initialize` event will be emitted.
 *
 * Sounds simple right? Read on...
 *
 * When you create a dynamic iframe using open/write/close, the window.load event for the
 * iframe is triggered when you call close, and there's no further load event to indicate that
 * everything is actually loaded.
 *
 * In Chrome, stylesheets don't show up in document.styleSheets until they have loaded, so we could
 * just poll that array and wait for it to have the right length. However, in Firefox, stylesheets
 * are added to document.styleSheets immediately, and the only way you can determine whether they've
 * loaded is to attempt to access .cssRules and wait for that to stop throwing an exception. But
 * cross-domain stylesheets never allow .cssRules to be accessed even after they have loaded.
 *
 * The workaround is to change all `<link href="...">` tags to `<style>@import url(...)</style>` tags.
 * Because `@import` is blocking, Chrome won't add the stylesheet to document.styleSheets until
 * the `@import` has finished, and Firefox won't allow .cssRules to be accessed until the `@import`
 * has finished. And because the contents of the `<style>` tag are from the same origin, accessing
 * .cssRules is allowed.
 *
 * However, now that we control the styles we're injecting, we might as well do away with
 * browser-specific polling hacks like document.styleSheets and .cssRules, and instead inject
 * `<style>@import url(...); #foo { font-family: someValue; }</style>`, then create `<div id="foo">`
 * and wait for its font-family to change to someValue. Because `@import` is blocking, the font-family
 * rule is not applied until after the `@import` finishes.
 *
 * All this stylesheet injection and polling magic is in #transplantStyles.
 *
 * @private
 * @fires load
 */
OO.ui.Frame.prototype.load = function () {
	var win = this.$element.prop( 'contentWindow' ),
		doc = win.document,
		frame = this;

	this.loading = true;

	// Figure out directionality:
	this.dir = this.$element.closest( '[dir]' ).prop( 'dir' ) || 'ltr';

	// Initialize contents
	doc.open();
	doc.write(
		'<!doctype html>' +
		'<html>' +
			'<body class="oo-ui-frame-body oo-ui-' + this.dir + '" style="direction:' + this.dir + ';" dir="' + this.dir + '">' +
				'<div class="oo-ui-frame-content"></div>' +
			'</body>' +
		'</html>'
	);
	doc.close();

	// Properties
	this.$ = OO.ui.Element.getJQuery( doc, this );
	this.$content = this.$( '.oo-ui-frame-content' ).attr( 'tabIndex', 0 );
	this.$document = this.$( doc );

	this.constructor.static.transplantStyles(
		this.getElementDocument(),
		this.$document[0],
		function () {
			frame.loading = false;
			frame.loaded = true;
			frame.emit( 'load' );
		}
	);
};

/**
 * Run a callback as soon as the frame has been loaded.
 *
 *
 * This will start loading if it hasn't already, and runs
 * immediately if the frame is already loaded.
 *
 * Don't call this until the element is attached.
 *
 * @param {Function} callback
 */
OO.ui.Frame.prototype.run = function ( callback ) {
	if ( this.loaded ) {
		callback();
	} else {
		if ( !this.loading ) {
			this.load();
		}
		this.once( 'load', callback );
	}
};

/**
 * Set the size of the frame.
 *
 * @param {number} width Frame width in pixels
 * @param {number} height Frame height in pixels
 * @chainable
 */
OO.ui.Frame.prototype.setSize = function ( width, height ) {
	this.$element.css( { 'width': width, 'height': height } );
	return this;
};
/**
 * Container for elements in a child frame.
 *
 * There are two ways to specify a title: set the static `title` property or provide a `title`
 * property in the configuration options. The latter will override the former.
 *
 * @abstract
 * @class
 * @extends OO.ui.Element
 * @mixins OO.EventEmitter
 *
 * @constructor
 * @param {Object} [config] Configuration options
 * @cfg {string|Function} [title] Title string or function that returns a string
 * @cfg {string} [icon] Symbolic name of icon
 * @fires initialize
 */
OO.ui.Window = function OoUiWindow( config ) {
	// Parent constructor
	OO.ui.Window.super.call( this, config );

	// Mixin constructors
	OO.EventEmitter.call( this );

	// Properties
	this.visible = false;
	this.opening = false;
	this.closing = false;
	this.title = OO.ui.resolveMsg( config.title || this.constructor.static.title );
	this.icon = config.icon || this.constructor.static.icon;
	this.frame = new OO.ui.Frame( { '$': this.$ } );
	this.$frame = this.$( '<div>' );
	this.$ = function () {
		throw new Error( 'this.$() cannot be used until the frame has been initialized.' );
	};

	// Initialization
	this.$element
		.addClass( 'oo-ui-window' )
		// Hide the window using visibility: hidden; while the iframe is still loading
		// Can't use display: none; because that prevents the iframe from loading in Firefox
		.css( 'visibility', 'hidden' )
		.append( this.$frame );
	this.$frame
		.addClass( 'oo-ui-window-frame' )
		.append( this.frame.$element );

	// Events
	this.frame.connect( this, { 'load': 'initialize' } );
};

/* Setup */

OO.inheritClass( OO.ui.Window, OO.ui.Element );
OO.mixinClass( OO.ui.Window, OO.EventEmitter );

/* Events */

/**
 * Initialize contents.
 *
 * Fired asynchronously after construction when iframe is ready.
 *
 * @event initialize
 */

/**
 * Open window.
 *
 * Fired after window has been opened.
 *
 * @event open
 * @param {Object} data Window opening data
 */

/**
 * Close window.
 *
 * Fired after window has been closed.
 *
 * @event close
 * @param {Object} data Window closing data
 */

/* Static Properties */

/**
 * Symbolic name of icon.
 *
 * @static
 * @inheritable
 * @property {string}
 */
OO.ui.Window.static.icon = 'window';

/**
 * Window title.
 *
 * Subclasses must implement this property before instantiating the window.
 * Alternatively, override #getTitle with an alternative implementation.
 *
 * @static
 * @abstract
 * @inheritable
 * @property {string|Function} Title string or function that returns a string
 */
OO.ui.Window.static.title = null;

/* Methods */

/**
 * Check if window is visible.
 *
 * @return {boolean} Window is visible
 */
OO.ui.Window.prototype.isVisible = function () {
	return this.visible;
};

/**
 * Check if window is opening.
 *
 * @return {boolean} Window is opening
 */
OO.ui.Window.prototype.isOpening = function () {
	return this.opening;
};

/**
 * Check if window is closing.
 *
 * @return {boolean} Window is closing
 */
OO.ui.Window.prototype.isClosing = function () {
	return this.closing;
};

/**
 * Get the window frame.
 *
 * @return {OO.ui.Frame} Frame of window
 */
OO.ui.Window.prototype.getFrame = function () {
	return this.frame;
};

/**
 * Get the title of the window.
 *
 * @return {string} Title text
 */
OO.ui.Window.prototype.getTitle = function () {
	return this.title;
};

/**
 * Get the window icon.
 *
 * @return {string} Symbolic name of icon
 */
OO.ui.Window.prototype.getIcon = function () {
	return this.icon;
};

/**
 * Set the size of window frame.
 *
 * @param {number} [width=auto] Custom width
 * @param {number} [height=auto] Custom height
 * @chainable
 */
OO.ui.Window.prototype.setSize = function ( width, height ) {
	if ( !this.frame.$content ) {
		return;
	}

	this.frame.$element.css( {
		'width': width === undefined ? 'auto' : width,
		'height': height === undefined ? 'auto' : height
	} );

	return this;
};

/**
 * Set the title of the window.
 *
 * @param {string|Function} title Title text or a function that returns text
 * @chainable
 */
OO.ui.Window.prototype.setTitle = function ( title ) {
	this.title = OO.ui.resolveMsg( title );
	if ( this.$title ) {
		this.$title.text( title );
	}
	return this;
};

/**
 * Set the icon of the window.
 *
 * @param {string} icon Symbolic name of icon
 * @chainable
 */
OO.ui.Window.prototype.setIcon = function ( icon ) {
	if ( this.$icon ) {
		this.$icon.removeClass( 'oo-ui-icon-' + this.icon );
	}
	this.icon = icon;
	if ( this.$icon ) {
		this.$icon.addClass( 'oo-ui-icon-' + this.icon );
	}

	return this;
};

/**
 * Set the position of window to fit with contents.
 *
 * @param {string} left Left offset
 * @param {string} top Top offset
 * @chainable
 */
OO.ui.Window.prototype.setPosition = function ( left, top ) {
	this.$element.css( { 'left': left, 'top': top } );
	return this;
};

/**
 * Set the height of window to fit with contents.
 *
 * @param {number} [min=0] Min height
 * @param {number} [max] Max height (defaults to content's outer height)
 * @chainable
 */
OO.ui.Window.prototype.fitHeightToContents = function ( min, max ) {
	var height = this.frame.$content.outerHeight();

	this.frame.$element.css(
		'height', Math.max( min || 0, max === undefined ? height : Math.min( max, height ) )
	);

	return this;
};

/**
 * Set the width of window to fit with contents.
 *
 * @param {number} [min=0] Min height
 * @param {number} [max] Max height (defaults to content's outer width)
 * @chainable
 */
OO.ui.Window.prototype.fitWidthToContents = function ( min, max ) {
	var width = this.frame.$content.outerWidth();

	this.frame.$element.css(
		'width', Math.max( min || 0, max === undefined ? width : Math.min( max, width ) )
	);

	return this;
};

/**
 * Initialize window contents.
 *
 * The first time the window is opened, #initialize is called when it's safe to begin populating
 * its contents. See #setup for a way to make changes each time the window opens.
 *
 * Once this method is called, this.$$ can be used to create elements within the frame.
 *
 * @fires initialize
 * @chainable
 */
OO.ui.Window.prototype.initialize = function () {
	// Properties
	this.$ = this.frame.$;
	this.$title = this.$( '<div class="oo-ui-window-title"></div>' )
		.text( this.title );
	this.$icon = this.$( '<div class="oo-ui-window-icon"></div>' )
		.addClass( 'oo-ui-icon-' + this.icon );
	this.$head = this.$( '<div class="oo-ui-window-head"></div>' );
	this.$body = this.$( '<div class="oo-ui-window-body"></div>' );
	this.$foot = this.$( '<div class="oo-ui-window-foot"></div>' );
	this.$overlay = this.$( '<div class="oo-ui-window-overlay"></div>' );

	// Initialization
	this.frame.$content.append(
		this.$head.append( this.$icon, this.$title ),
		this.$body,
		this.$foot,
		this.$overlay
	);

	// Undo the visibility: hidden; hack from the constructor and apply display: none;
	// We can do this safely now that the iframe has initialized
	this.$element.hide().css( 'visibility', '' );

	this.emit( 'initialize' );

	return this;
};

/**
 * Setup window for use.
 *
 * Each time the window is opened, once it's ready to be interacted with, this will set it up for
 * use in a particular context, based on the `data` argument.
 *
 * When you override this method, you must call the parent method at the very beginning.
 *
 * @abstract
 * @param {Object} [data] Window opening data
 */
OO.ui.Window.prototype.setup = function () {
	// Override to do something
};

/**
 * Tear down window after use.
 *
 * Each time the window is closed, and it's done being interacted with, this will tear it down and
 * do something with the user's interactions within the window, based on the `data` argument.
 *
 * When you override this method, you must call the parent method at the very end.
 *
 * @abstract
 * @param {Object} [data] Window closing data
 */
OO.ui.Window.prototype.teardown = function () {
	// Override to do something
};

/**
 * Open window.
 *
 * Do not override this method. See #setup for a way to make changes each time the window opens.
 *
 * @param {Object} [data] Window opening data
 * @fires open
 * @chainable
 */
OO.ui.Window.prototype.open = function ( data ) {
	if ( !this.opening && !this.closing && !this.visible ) {
		this.opening = true;
		this.frame.run( OO.ui.bind( function () {
			this.$element.show();
			this.visible = true;
			this.emit( 'opening', data );
			this.setup( data );
			this.emit( 'open', data );
			this.opening = false;
			// Focus the content div (which has a tabIndex) to inactivate
			// (but not clear) selections in the parent frame.
			// Must happen after the window has opened.
			this.frame.$content.focus();
		}, this ) );
	}

	return this;
};

/**
 * Close window.
 *
 * See #teardown for a way to do something each time the window closes.
 *
 * @param {Object} [data] Window closing data
 * @fires close
 * @chainable
 */
OO.ui.Window.prototype.close = function ( data ) {
	if ( !this.opening && !this.closing && this.visible ) {
		this.frame.$content.find( ':focus' ).blur();
		this.closing = true;
		this.$element.hide();
		this.visible = false;
		this.emit( 'closing', data );
		this.teardown( data );
		this.emit( 'close', data );
		this.closing = false;
	}

	return this;
};
/**
 * Set of mutually exclusive windows.
 *
 * @class
 * @extends OO.ui.Element
 * @mixins OO.EventEmitter
 *
 * @constructor
 * @param {OO.Factory} factory Window factory
 * @param {Object} [config] Configuration options
 */
OO.ui.WindowSet = function OoUiWindowSet( factory, config ) {
	// Parent constructor
	OO.ui.WindowSet.super.call( this, config );

	// Mixin constructors
	OO.EventEmitter.call( this );

	// Properties
	this.factory = factory;

	/**
	 * List of all windows associated with this window set.
	 *
	 * @property {OO.ui.Window[]}
	 */
	this.windowList = [];

	/**
	 * Mapping of OO.ui.Window objects created by name from the #factory.
	 *
	 * @property {Object}
	 */
	this.windows = {};
	this.currentWindow = null;

	// Initialization
	this.$element.addClass( 'oo-ui-windowSet' );
};

/* Setup */

OO.inheritClass( OO.ui.WindowSet, OO.ui.Element );
OO.mixinClass( OO.ui.WindowSet, OO.EventEmitter );

/* Events */

/**
 * @event opening
 * @param {OO.ui.Window} win Window that's being opened
 * @param {Object} config Window opening information
 */

/**
 * @event open
 * @param {OO.ui.Window} win Window that's been opened
 * @param {Object} config Window opening information
 */

/**
 * @event closing
 * @param {OO.ui.Window} win Window that's being closed
 * @param {Object} config Window closing information
 */

/**
 * @event close
 * @param {OO.ui.Window} win Window that's been closed
 * @param {Object} config Window closing information
 */

/* Methods */

/**
 * Handle a window that's being opened.
 *
 * @param {OO.ui.Window} win Window that's being opened
 * @param {Object} [config] Window opening information
 * @fires opening
 */
OO.ui.WindowSet.prototype.onWindowOpening = function ( win, config ) {
	if ( this.currentWindow && this.currentWindow !== win ) {
		this.currentWindow.close();
	}
	this.currentWindow = win;
	this.emit( 'opening', win, config );
};

/**
 * Handle a window that's been opened.
 *
 * @param {OO.ui.Window} win Window that's been opened
 * @param {Object} [config] Window opening information
 * @fires open
 */
OO.ui.WindowSet.prototype.onWindowOpen = function ( win, config ) {
	this.emit( 'open', win, config );
};

/**
 * Handle a window that's being closed.
 *
 * @param {OO.ui.Window} win Window that's being closed
 * @param {Object} [config] Window closing information
 * @fires closing
 */
OO.ui.WindowSet.prototype.onWindowClosing = function ( win, config ) {
	this.currentWindow = null;
	this.emit( 'closing', win, config );
};

/**
 * Handle a window that's been closed.
 *
 * @param {OO.ui.Window} win Window that's been closed
 * @param {Object} [config] Window closing information
 * @fires close
 */
OO.ui.WindowSet.prototype.onWindowClose = function ( win, config ) {
	this.emit( 'close', win, config );
};

/**
 * Get the current window.
 *
 * @return {OO.ui.Window} Current window
 */
OO.ui.WindowSet.prototype.getCurrentWindow = function () {
	return this.currentWindow;
};

/**
 * Return a given window.
 *
 * @param {string} name Symbolic name of window
 * @return {OO.ui.Window} Window with specified name
 */
OO.ui.WindowSet.prototype.getWindow = function ( name ) {
	var win;

	if ( !this.factory.lookup( name ) ) {
		throw new Error( 'Unknown window: ' + name );
	}
	if ( !( name in this.windows ) ) {
		win = this.windows[name] = this.createWindow( name );
		this.addWindow( win );
	}
	return this.windows[name];
};

/**
 * Create a window for use in this window set.
 *
 * @param {string} name Symbolic name of window
 * @return {OO.ui.Window} Window with specified name
 */
OO.ui.WindowSet.prototype.createWindow = function ( name ) {
	return this.factory.create( name, { '$': this.$ } );
};

/**
 * Add a given window to this window set.
 *
 * Connects event handlers and attaches it to the DOM. Calling
 * OO.ui.Window#open will not work until the window is added to the set.
 *
 * @param {OO.ui.Window} win
 */
OO.ui.WindowSet.prototype.addWindow = function ( win ) {
	if ( this.windowList.indexOf( win ) !== -1 ) {
		// Already set up
		return;
	}
	this.windowList.push( win );

	win.connect( this, {
		'opening': [ 'onWindowOpening', win ],
		'open': [ 'onWindowOpen', win ],
		'closing': [ 'onWindowClosing', win ],
		'close': [ 'onWindowClose', win ]
	} );
	this.$element.append( win.$element );
};
/**
 * Modal dialog window.
 *
 * @abstract
 * @class
 * @extends OO.ui.Window
 *
 * @constructor
 * @param {Object} [config] Configuration options
 * @cfg {boolean} [footless] Hide foot
 * @cfg {string} [size='large'] Symbolic name of dialog size, `small`, `medium` or `large`
 */
OO.ui.Dialog = function OoUiDialog( config ) {
	// Configuration initialization
	config = $.extend( { 'size': 'large' }, config );

	// Parent constructor
	OO.ui.Dialog.super.call( this, config );

	// Properties
	this.visible = false;
	this.footless = !!config.footless;
	this.size = null;
	this.onWindowMouseWheelHandler = OO.ui.bind( this.onWindowMouseWheel, this );
	this.onDocumentKeyDownHandler = OO.ui.bind( this.onDocumentKeyDown, this );

	// Events
	this.$element.on( 'mousedown', false );
	this.connect( this, { 'opening': 'onOpening' } );

	// Initialization
	this.$element.addClass( 'oo-ui-dialog' );
	this.setSize( config.size );
};

/* Setup */

OO.inheritClass( OO.ui.Dialog, OO.ui.Window );

/* Static Properties */

/**
 * Symbolic name of dialog.
 *
 * @abstract
 * @static
 * @inheritable
 * @property {string}
 */
OO.ui.Dialog.static.name = '';

/**
 * Map of symbolic size names and CSS classes.
 *
 * @static
 * @inheritable
 * @property {Object}
 */
OO.ui.Dialog.static.sizeCssClasses = {
	'small': 'oo-ui-dialog-small',
	'medium': 'oo-ui-dialog-medium',
	'large': 'oo-ui-dialog-large'
};

/* Methods */

/**
 * Handle close button click events.
 */
OO.ui.Dialog.prototype.onCloseButtonClick = function () {
	this.close( { 'action': 'cancel' } );
};

/**
 * Handle window mouse wheel events.
 *
 * @param {jQuery.Event} e Mouse wheel event
 */
OO.ui.Dialog.prototype.onWindowMouseWheel = function () {
	return false;
};

/**
 * Handle document key down events.
 *
 * @param {jQuery.Event} e Key down event
 */
OO.ui.Dialog.prototype.onDocumentKeyDown = function ( e ) {
	switch ( e.which ) {
		case OO.ui.Keys.PAGEUP:
		case OO.ui.Keys.PAGEDOWN:
		case OO.ui.Keys.END:
		case OO.ui.Keys.HOME:
		case OO.ui.Keys.LEFT:
		case OO.ui.Keys.UP:
		case OO.ui.Keys.RIGHT:
		case OO.ui.Keys.DOWN:
			// Prevent any key events that might cause scrolling
			return false;
	}
};

/**
 * Handle frame document key down events.
 *
 * @param {jQuery.Event} e Key down event
 */
OO.ui.Dialog.prototype.onFrameDocumentKeyDown = function ( e ) {
	if ( e.which === OO.ui.Keys.ESCAPE ) {
		this.close( { 'action': 'cancel' } );
		return false;
	}
};

/** */
OO.ui.Dialog.prototype.onOpening = function () {
	this.$element.addClass( 'oo-ui-dialog-open' );
};

/**
 * Set dialog size.
 *
 * @param {string} [size='large'] Symbolic name of dialog size, `small`, `medium` or `large`
 */
OO.ui.Dialog.prototype.setSize = function ( size ) {
	var name, state, cssClass,
		sizeCssClasses = OO.ui.Dialog.static.sizeCssClasses;

	if ( !sizeCssClasses[size] ) {
		size = 'large';
	}
	this.size = size;
	for ( name in sizeCssClasses ) {
		state = name === size;
		cssClass = sizeCssClasses[name];
		this.$element.toggleClass( cssClass, state );
		if ( this.frame.$content ) {
			this.frame.$content.toggleClass( cssClass, state );
		}
	}
};

/**
 * @inheritdoc
 */
OO.ui.Dialog.prototype.initialize = function () {
	// Parent method
	OO.ui.Window.prototype.initialize.call( this );

	// Properties
	this.closeButton = new OO.ui.ButtonWidget( {
		'$': this.$,
		'frameless': true,
		'icon': 'close',
		'title': OO.ui.msg( 'ooui-dialog-action-close' )
	} );

	// Events
	this.closeButton.connect( this, { 'click': 'onCloseButtonClick' } );
	this.frame.$document.on( 'keydown', OO.ui.bind( this.onFrameDocumentKeyDown, this ) );

	// Initialization
	this.frame.$content.addClass( 'oo-ui-dialog-content' );
	if ( this.footless ) {
		this.frame.$content.addClass( 'oo-ui-dialog-content-footless' );
	}
	this.closeButton.$element.addClass( 'oo-ui-window-closeButton' );
	this.$head.append( this.closeButton.$element );
};

/**
 * @inheritdoc
 */
OO.ui.Dialog.prototype.setup = function ( data ) {
	// Parent method
	OO.ui.Window.prototype.setup.call( this, data );

	// Prevent scrolling in top-level window
	this.$( window ).on( 'mousewheel', this.onWindowMouseWheelHandler );
	this.$( document ).on( 'keydown', this.onDocumentKeyDownHandler );
};

/**
 * @inheritdoc
 */
OO.ui.Dialog.prototype.teardown = function ( data ) {
	// Parent method
	OO.ui.Window.prototype.teardown.call( this, data );

	// Allow scrolling in top-level window
	this.$( window ).off( 'mousewheel', this.onWindowMouseWheelHandler );
	this.$( document ).off( 'keydown', this.onDocumentKeyDownHandler );
};

/**
 * @inheritdoc
 */
OO.ui.Dialog.prototype.close = function ( data ) {
	var dialog = this;
	if ( !dialog.opening && !dialog.closing && dialog.visible ) {
		// Trigger transition
		dialog.$element.removeClass( 'oo-ui-dialog-open' );
		// Allow transition to complete before actually closing
		setTimeout( function () {
			// Parent method
			OO.ui.Window.prototype.close.call( dialog, data );
		}, 250 );
	}
};
/**
 * Container for elements.
 *
 * @abstract
 * @class
 * @extends OO.ui.Element
 * @mixins OO.EventEmitter
 *
 * @constructor
 * @param {Object} [config] Configuration options
 */
OO.ui.Layout = function OoUiLayout( config ) {
	// Initialize config
	config = config || {};

	// Parent constructor
	OO.ui.Layout.super.call( this, config );

	// Mixin constructors
	OO.EventEmitter.call( this );

	// Initialization
	this.$element.addClass( 'oo-ui-layout' );
};

/* Setup */

OO.inheritClass( OO.ui.Layout, OO.ui.Element );
OO.mixinClass( OO.ui.Layout, OO.EventEmitter );
/**
 * User interface control.
 *
 * @abstract
 * @class
 * @extends OO.ui.Element
 * @mixins OO.EventEmitter
 *
 * @constructor
 * @param {Object} [config] Configuration options
 * @cfg {boolean} [disabled=false] Disable
 */
OO.ui.Widget = function OoUiWidget( config ) {
	// Initialize config
	config = $.extend( { 'disabled': false }, config );

	// Parent constructor
	OO.ui.Widget.super.call( this, config );

	// Mixin constructors
	OO.EventEmitter.call( this );

	// Properties
	this.disabled = null;
	this.wasDisabled = null;

	// Initialization
	this.$element.addClass( 'oo-ui-widget' );
	this.setDisabled( !!config.disabled );
};

/* Setup */

OO.inheritClass( OO.ui.Widget, OO.ui.Element );
OO.mixinClass( OO.ui.Widget, OO.EventEmitter );

/* Events */

/**
 * @event disable
 * @param {boolean} disabled Widget is disabled
 */

/* Methods */

/**
 * Check if the widget is disabled.
 *
 * @param {boolean} Button is disabled
 */
OO.ui.Widget.prototype.isDisabled = function () {
	return this.disabled;
};

/**
 * Update the disabled state, in case of changes in parent widget.
 *
 * @chainable
 */
OO.ui.Widget.prototype.updateDisabled = function () {
	this.setDisabled( this.disabled );
	return this;
};

/**
 * Set the disabled state of the widget.
 *
 * This should probably change the widgets' appearance and prevent it from being used.
 *
 * @param {boolean} disabled Disable widget
 * @chainable
 */
OO.ui.Widget.prototype.setDisabled = function ( disabled ) {
	var isDisabled;

	this.disabled = !!disabled;
	isDisabled = this.isDisabled();
	if ( isDisabled !== this.wasDisabled ) {
		this.$element.toggleClass( 'oo-ui-widget-disabled', isDisabled );
		this.$element.toggleClass( 'oo-ui-widget-enabled', !isDisabled );
		this.emit( 'disable', isDisabled );
	}
	this.wasDisabled = isDisabled;
	return this;
};
/**
 * Element with a button.
 *
 * @abstract
 * @class
 *
 * @constructor
 * @param {jQuery} $button Button node, assigned to #$button
 * @param {Object} [config] Configuration options
 * @cfg {boolean} [frameless] Render button without a frame
 * @cfg {number} [tabIndex=0] Button's tab index, use -1 to prevent tab focusing
 */
OO.ui.ButtonedElement = function OoUiButtonedElement( $button, config ) {
	// Configuration initialization
	config = config || {};

	// Properties
	this.$button = $button;
	this.tabIndex = null;
	this.active = false;
	this.onMouseUpHandler = OO.ui.bind( this.onMouseUp, this );

	// Events
	this.$button.on( 'mousedown', OO.ui.bind( this.onMouseDown, this ) );

	// Initialization
	this.$element.addClass( 'oo-ui-buttonedElement' );
	this.$button
		.addClass( 'oo-ui-buttonedElement-button' )
		.attr( 'role', 'button' )
		.prop( 'tabIndex', config.tabIndex || 0 );
	if ( config.frameless ) {
		this.$element.addClass( 'oo-ui-buttonedElement-frameless' );
	} else {
		this.$element.addClass( 'oo-ui-buttonedElement-framed' );
	}
};

/* Methods */

/**
 * Handles mouse down events.
 *
 * @param {jQuery.Event} e Mouse down event
 */
OO.ui.ButtonedElement.prototype.onMouseDown = function () {
	// tabIndex should generally be interacted with via the property,
	// but it's not possible to reliably unset a tabIndex via a property
	// so we use the (lowercase) "tabindex" attribute instead.
	this.tabIndex = this.$button.attr( 'tabindex' );
	// Remove the tab-index while the button is down to prevent the button from stealing focus
	this.$button
		.removeAttr( 'tabindex' )
		.addClass( 'oo-ui-buttonedElement-pressed' );
	this.getElementDocument().addEventListener( 'mouseup', this.onMouseUpHandler, true );
};

/**
 * Handles mouse up events.
 *
 * @param {jQuery.Event} e Mouse up event
 */
OO.ui.ButtonedElement.prototype.onMouseUp = function () {
	// Restore the tab-index after the button is up to restore the button's accesssibility
	this.$button
		.attr( 'tabindex', this.tabIndex )
		.removeClass( 'oo-ui-buttonedElement-pressed' );
	this.getElementDocument().removeEventListener( 'mouseup', this.onMouseUpHandler, true );
};

/**
 * Set active state.
 *
 * @param {boolean} [value] Make button active
 * @chainable
 */
OO.ui.ButtonedElement.prototype.setActive = function ( value ) {
	this.$button.toggleClass( 'oo-ui-buttonedElement-active', !!value );
	return this;
};
/**
 * Element that can be automatically clipped to visible boundaies.
 *
 * @abstract
 * @class
 *
 * @constructor
 * @param {jQuery} $clippable Nodes to clip, assigned to #$clippable
 * @param {Object} [config] Configuration options
 */
OO.ui.ClippableElement = function OoUiClippableElement( $clippable, config ) {
	// Configuration initialization
	config = config || {};

	// Properties
	this.$clippable = $clippable;
	this.clipping = false;
	this.clipped = false;
	this.$clippableContainer = null;
	this.$clippableScroller = null;
	this.$clippableWindow = null;
	this.idealWidth = null;
	this.idealHeight = null;
	this.onClippableContainerScrollHandler = OO.ui.bind( this.clip, this );
	this.onClippableWindowResizeHandler = OO.ui.bind( this.clip, this );

	// Initialization
	this.$clippable.addClass( 'oo-ui-clippableElement-clippable' );
};

/* Methods */

/**
 * Set clipping.
 *
 * @param {boolean} value Enable clipping
 * @chainable
 */
OO.ui.ClippableElement.prototype.setClipping = function ( value ) {
	value = !!value;

	if ( this.clipping !== value ) {
		this.clipping = value;
		if ( this.clipping ) {
			this.$clippableContainer = this.$( this.getClosestScrollableElementContainer() );
			// If the clippable container is the body, we have to listen to scroll events and check
			// jQuery.scrollTop on the window because of browser inconsistencies
			this.$clippableScroller = this.$clippableContainer.is( 'body' ) ?
				this.$( OO.ui.Element.getWindow( this.$clippableContainer ) ) :
				this.$clippableContainer;
			this.$clippableScroller.on( 'scroll', this.onClippableContainerScrollHandler );
			this.$clippableWindow = this.$( this.getElementWindow() )
				.on( 'resize', this.onClippableWindowResizeHandler );
			// Initial clip after visible
			setTimeout( OO.ui.bind( this.clip, this ) );
		} else {
			this.$clippableContainer = null;
			this.$clippableScroller.off( 'scroll', this.onClippableContainerScrollHandler );
			this.$clippableScroller = null;
			this.$clippableWindow.off( 'resize', this.onClippableWindowResizeHandler );
			this.$clippableWindow = null;
		}
	}

	return this;
};

/**
 * Check if the element will be clipped to fit the visible area of the nearest scrollable container.
 *
 * @return {boolean} Element will be clipped to the visible area
 */
OO.ui.ClippableElement.prototype.isClipping = function () {
	return this.clipping;
};

/**
 * Check if the bottom or right of the element is being clipped by the nearest scrollable container.
 *
 * @return {boolean} Part of the element is being clipped
 */
OO.ui.ClippableElement.prototype.isClipped = function () {
	return this.clipped;
};

/**
 * Set the ideal size.
 *
 * @param {number|string} [width] Width as a number of pixels or CSS string with unit suffix
 * @param {number|string} [height] Height as a number of pixels or CSS string with unit suffix
 */
OO.ui.ClippableElement.prototype.setIdealSize = function ( width, height ) {
	this.idealWidth = width;
	this.idealHeight = height;
};

/**
 * Clip element to visible boundaries and allow scrolling when needed.
 *
 * Element will be clipped the bottom or right of the element is within 10px of the edge of, or
 * overlapped by, the visible area of the nearest scrollable container.
 *
 * @chainable
 */
OO.ui.ClippableElement.prototype.clip = function () {
	if ( !this.clipping ) {
		// this.$clippableContainer and this.$clippableWindow are null, so the below will fail
		return this;
	}

	var buffer = 10,
		cOffset = this.$clippable.offset(),
		ccOffset = this.$clippableContainer.offset() || { 'top': 0, 'left': 0 },
		ccHeight = this.$clippableContainer.innerHeight() - buffer,
		ccWidth = this.$clippableContainer.innerWidth() - buffer,
		scrollTop = this.$clippableScroller.scrollTop(),
		scrollLeft = this.$clippableScroller.scrollLeft(),
		desiredWidth = ( ccOffset.left + scrollLeft + ccWidth ) - cOffset.left,
		desiredHeight = ( ccOffset.top + scrollTop + ccHeight ) - cOffset.top,
		naturalWidth = this.$clippable.prop( 'scrollWidth' ),
		naturalHeight = this.$clippable.prop( 'scrollHeight' ),
		clipWidth = desiredWidth < naturalWidth,
		clipHeight = desiredHeight < naturalHeight;

	if ( clipWidth ) {
		this.$clippable.css( { 'overflow-x': 'auto', 'width': desiredWidth } );
	} else {
		this.$clippable.css( { 'overflow-x': '', 'width': this.idealWidth || '' } );
	}
	if ( clipHeight ) {
		this.$clippable.css( { 'overflow-y': 'auto', 'height': desiredHeight } );
	} else {
		this.$clippable.css( { 'overflow-y': '', 'height': this.idealHeight || '' } );
	}

	this.clipped = clipWidth || clipHeight;

	return this;
};
/**
 * Element with named flags that can be added, removed, listed and checked.
 *
 * A flag, when set, adds a CSS class on the `$element` by combing `oo-ui-flaggableElement-` with
 * the flag name. Flags are primarily useful for styling.
 *
 * @abstract
 * @class
 *
 * @constructor
 * @param {Object} [config] Configuration options
 * @cfg {string[]} [flags=[]] Styling flags, e.g. 'primary', 'destructive' or 'constructive'
 */
OO.ui.FlaggableElement = function OoUiFlaggableElement( config ) {
	// Config initialization
	config = config || {};

	// Properties
	this.flags = {};

	// Initialization
	this.setFlags( config.flags );
};

/* Methods */

/**
 * Check if a flag is set.
 *
 * @param {string} flag Name of flag
 * @return {boolean} Has flag
 */
OO.ui.FlaggableElement.prototype.hasFlag = function ( flag ) {
	return flag in this.flags;
};

/**
 * Get the names of all flags set.
 *
 * @return {string[]} flags Flag names
 */
OO.ui.FlaggableElement.prototype.getFlags = function () {
	return Object.keys( this.flags );
};

/**
 * Add one or more flags.
 *
 * @param {string[]|Object.<string, boolean>} flags List of flags to add, or list of set/remove
 *  values, keyed by flag name
 * @chainable
 */
OO.ui.FlaggableElement.prototype.setFlags = function ( flags ) {
	var i, len, flag,
		classPrefix = 'oo-ui-flaggableElement-';

	if ( $.isArray( flags ) ) {
		for ( i = 0, len = flags.length; i < len; i++ ) {
			flag = flags[i];
			// Set
			this.flags[flag] = true;
			this.$element.addClass( classPrefix + flag );
		}
	} else if ( OO.isPlainObject( flags ) ) {
		for ( flag in flags ) {
			if ( flags[flag] ) {
				// Set
				this.flags[flag] = true;
				this.$element.addClass( classPrefix + flag );
			} else {
				// Remove
				delete this.flags[flag];
				this.$element.removeClass( classPrefix + flag );
			}
		}
	}
	return this;
};
/**
 * Element containing a sequence of child elements.
 *
 * @abstract
 * @class
 *
 * @constructor
 * @param {jQuery} $group Container node, assigned to #$group
 * @param {Object} [config] Configuration options
 * @cfg {Object.<string,string>} [aggregations] Events to aggregate, keyed by item event name
 */
OO.ui.GroupElement = function OoUiGroupElement( $group, config ) {
	// Configuration
	config = config || {};

	// Properties
	this.$group = $group;
	this.items = [];
	this.$items = this.$( [] );
	this.aggregate = !$.isEmptyObject( config.aggregations );
	this.aggregations = config.aggregations || {};
};

/* Methods */

/**
 * Get items.
 *
 * @return {OO.ui.Element[]} Items
 */
OO.ui.GroupElement.prototype.getItems = function () {
	return this.items.slice( 0 );
};

/**
 * Add items.
 *
 * @param {OO.ui.Element[]} items Item
 * @param {number} [index] Index to insert items at
 * @chainable
 */
OO.ui.GroupElement.prototype.addItems = function ( items, index ) {
	var i, len, item, event, events, currentIndex,
		$items = this.$( [] );

	for ( i = 0, len = items.length; i < len; i++ ) {
		item = items[i];

		// Check if item exists then remove it first, effectively "moving" it
		currentIndex = $.inArray( item, this.items );
		if ( currentIndex >= 0 ) {
			this.removeItems( [ item ] );
			// Adjust index to compensate for removal
			if ( currentIndex < index ) {
				index--;
			}
		}
		// Add the item
		if ( this.aggregate ) {
			events = {};
			for ( event in this.aggregations ) {
				events[event] = [ 'emit', this.aggregations[event], item ];
			}
			item.connect( this, events );
		}
		item.setElementGroup( this );
		$items = $items.add( item.$element );
	}

	if ( index === undefined || index < 0 || index >= this.items.length ) {
		this.$group.append( $items );
		this.items.push.apply( this.items, items );
	} else if ( index === 0 ) {
		this.$group.prepend( $items );
		this.items.unshift.apply( this.items, items );
	} else {
		this.$items.eq( index ).before( $items );
		this.items.splice.apply( this.items, [ index, 0 ].concat( items ) );
	}

	this.$items = this.$items.add( $items );

	return this;
};

/**
 * Remove items.
 *
 * Items will be detached, not removed, so they can be used later.
 *
 * @param {OO.ui.Element[]} items Items to remove
 * @chainable
 */
OO.ui.GroupElement.prototype.removeItems = function ( items ) {
	var i, len, item, index;

	// Remove specific items
	for ( i = 0, len = items.length; i < len; i++ ) {
		item = items[i];
		index = $.inArray( item, this.items );
		if ( index !== -1 ) {
			if ( this.aggregate ) {
				item.disconnect( this );
			}
			item.setElementGroup( null );
			this.items.splice( index, 1 );
			item.$element.detach();
			this.$items = this.$items.not( item.$element );
		}
	}

	return this;
};

/**
 * Clear all items.
 *
 * Items will be detached, not removed, so they can be used later.
 *
 * @chainable
 */
OO.ui.GroupElement.prototype.clearItems = function () {
	var i, len, item;

	// Remove all items
	for ( i = 0, len = this.items.length; i < len; i++ ) {
		item = this.items[i];
		if ( this.aggregate ) {
			item.disconnect( this );
		}
		item.setElementGroup( null );
	}
	this.items = [];
	this.$items.detach();
	this.$items = this.$( [] );

	return this;
};
/**
 * Element containing an icon.
 *
 * @abstract
 * @class
 *
 * @constructor
 * @param {jQuery} $icon Icon node, assigned to #$icon
 * @param {Object} [config] Configuration options
 * @cfg {Object|string} [icon=''] Symbolic icon name, or map of icon names keyed by language ID;
 *  use the 'default' key to specify the icon to be used when there is no icon in the user's
 *  language
 */
OO.ui.IconedElement = function OoUiIconedElement( $icon, config ) {
	// Config intialization
	config = config || {};

	// Properties
	this.$icon = $icon;
	this.icon = null;

	// Initialization
	this.$icon.addClass( 'oo-ui-iconedElement-icon' );
	this.setIcon( config.icon || this.constructor.static.icon );
};

/* Setup */

OO.initClass( OO.ui.IconedElement );

/* Static Properties */

/**
 * Icon.
 *
 * Value should be the unique portion of an icon CSS class name, such as 'up' for 'oo-ui-icon-up'.
 *
 * For i18n purposes, this property can be an object containing a `default` icon name property and
 * additional icon names keyed by language code.
 *
 * Example of i18n icon definition:
 *     { 'default': 'bold-a', 'en': 'bold-b', 'de': 'bold-f' }
 *
 * @static
 * @inheritable
 * @property {Object|string} Symbolic icon name, or map of icon names keyed by language ID;
 *  use the 'default' key to specify the icon to be used when there is no icon in the user's
 *  language
 */
OO.ui.IconedElement.static.icon = null;

/* Methods */

/**
 * Set icon.
 *
 * @param {Object|string} icon Symbolic icon name, or map of icon names keyed by language ID;
 *  use the 'default' key to specify the icon to be used when there is no icon in the user's
 *  language
 * @chainable
 */
OO.ui.IconedElement.prototype.setIcon = function ( icon ) {
	icon = OO.isPlainObject( icon ) ? OO.ui.getLocalValue( icon, null, 'default' ) : icon;

	if ( this.icon ) {
		this.$icon.removeClass( 'oo-ui-icon-' + this.icon );
	}
	if ( typeof icon === 'string' ) {
		icon = icon.trim();
		if ( icon.length ) {
			this.$icon.addClass( 'oo-ui-icon-' + icon );
			this.icon = icon;
		}
	}
	this.$element.toggleClass( 'oo-ui-iconedElement', !!this.icon );

	return this;
};

/**
 * Get icon.
 *
 * @return {string} Icon
 */
OO.ui.IconedElement.prototype.getIcon = function () {
	return this.icon;
};
/**
 * Element containing an indicator.
 *
 * @abstract
 * @class
 *
 * @constructor
 * @param {jQuery} $indicator Indicator node, assigned to #$indicator
 * @param {Object} [config] Configuration options
 * @cfg {string} [indicator] Symbolic indicator name
 * @cfg {string} [indicatorTitle] Indicator title text or a function that return text
 */
OO.ui.IndicatedElement = function OoUiIndicatedElement( $indicator, config ) {
	// Config intialization
	config = config || {};

	// Properties
	this.$indicator = $indicator;
	this.indicator = null;
	this.indicatorLabel = null;

	// Initialization
	this.$indicator.addClass( 'oo-ui-indicatedElement-indicator' );
	this.setIndicator( config.indicator || this.constructor.static.indicator );
	this.setIndicatorTitle( config.indicatorTitle  || this.constructor.static.indicatorTitle );
};

/* Setup */

OO.initClass( OO.ui.IndicatedElement );

/* Static Properties */

/**
 * indicator.
 *
 * @static
 * @inheritable
 * @property {string|null} Symbolic indicator name or null for no indicator
 */
OO.ui.IndicatedElement.static.indicator = null;

/**
 * Indicator title.
 *
 * @static
 * @inheritable
 * @property {string|Function|null} Indicator title text, a function that return text or null for no
 *  indicator title
 */
OO.ui.IndicatedElement.static.indicatorTitle = null;

/* Methods */

/**
 * Set indicator.
 *
 * @param {string|null} indicator Symbolic name of indicator to use or null for no indicator
 * @chainable
 */
OO.ui.IndicatedElement.prototype.setIndicator = function ( indicator ) {
	if ( this.indicator ) {
		this.$indicator.removeClass( 'oo-ui-indicator-' + this.indicator );
		this.indicator = null;
	}
	if ( typeof indicator === 'string' ) {
		indicator = indicator.trim();
		if ( indicator.length ) {
			this.$indicator.addClass( 'oo-ui-indicator-' + indicator );
			this.indicator = indicator;
		}
	}
	this.$element.toggleClass( 'oo-ui-indicatedElement', !!this.indicator );

	return this;
};

/**
 * Set indicator label.
 *
 * @param {string|Function|null} indicator Indicator title text, a function that return text or null
 *  for no indicator title
 * @chainable
 */
OO.ui.IndicatedElement.prototype.setIndicatorTitle = function ( indicatorTitle ) {
	this.indicatorTitle = indicatorTitle = OO.ui.resolveMsg( indicatorTitle );

	if ( typeof indicatorTitle === 'string' && indicatorTitle.length ) {
		this.$indicator.attr( 'title', indicatorTitle );
	} else {
		this.$indicator.removeAttr( 'title' );
	}

	return this;
};

/**
 * Get indicator.
 *
 * @return {string} title Symbolic name of indicator
 */
OO.ui.IndicatedElement.prototype.getIndicator = function () {
	return this.indicator;
};

/**
 * Get indicator title.
 *
 * @return {string} Indicator title text
 */
OO.ui.IndicatedElement.prototype.getIndicatorTitle = function () {
	return this.indicatorTitle;
};
/**
 * Element containing a label.
 *
 * @abstract
 * @class
 *
 * @constructor
 * @param {jQuery} $label Label node, assigned to #$label
 * @param {Object} [config] Configuration options
 * @cfg {jQuery|string|Function} [label] Label nodes, text or a function that returns nodes or text
 * @cfg {boolean} [autoFitLabel=true] Whether to fit the label or not.
 */
OO.ui.LabeledElement = function OoUiLabeledElement( $label, config ) {
	// Config intialization
	config = config || {};

	// Properties
	this.$label = $label;
	this.label = null;

	// Initialization
	this.$label.addClass( 'oo-ui-labeledElement-label' );
	this.setLabel( config.label || this.constructor.static.label );
	this.autoFitLabel = config.autoFitLabel === undefined || !!config.autoFitLabel;
};

/* Setup */

OO.initClass( OO.ui.LabeledElement );

/* Static Properties */

/**
 * Label.
 *
 * @static
 * @inheritable
 * @property {string|Function|null} Label text; a function that returns a nodes or text; or null for
 *  no label
 */
OO.ui.LabeledElement.static.label = null;

/* Methods */

/**
 * Set the label.
 *
 * An empty string will result in the label being hidden. A string containing only whitespace will
 * be converted to a single &nbsp;
 *
 * @param {jQuery|string|Function|null} label Label nodes; text; a function that retuns nodes or
 *  text; or null for no label
 * @chainable
 */
OO.ui.LabeledElement.prototype.setLabel = function ( label ) {
	var empty = false;

	this.label = label = OO.ui.resolveMsg( label ) || null;
	if ( typeof label === 'string' && label.length ) {
		if ( label.match( /^\s*$/ ) ) {
			// Convert whitespace only string to a single non-breaking space
			this.$label.html( '&nbsp;' );
		} else {
			this.$label.text( label );
		}
	} else if ( label instanceof jQuery ) {
		this.$label.empty().append( label );
	} else {
		this.$label.empty();
		empty = true;
	}
	this.$element.toggleClass( 'oo-ui-labeledElement', !empty );
	this.$label.css( 'display', empty ? 'none' : '' );

	return this;
};

/**
 * Get the label.
 *
 * @return {jQuery|string|Function|null} label Label nodes; text; a function that returns nodes or
 *  text; or null for no label
 */
OO.ui.LabeledElement.prototype.getLabel = function () {
	return this.label;
};

/**
 * Fit the label.
 *
 * @chainable
 */
OO.ui.LabeledElement.prototype.fitLabel = function () {
	if ( this.$label.autoEllipsis && this.autoFitLabel ) {
		this.$label.autoEllipsis( { 'hasSpan': false, 'tooltip': true } );
	}
	return this;
};
/**
 * Popuppable element.
 *
 * @abstract
 * @class
 *
 * @constructor
 * @param {Object} [config] Configuration options
 * @cfg {number} [popupWidth=320] Width of popup
 * @cfg {number} [popupHeight] Height of popup
 * @cfg {Object} [popup] Configuration to pass to popup
 */
OO.ui.PopuppableElement = function OoUiPopuppableElement( config ) {
	// Configuration initialization
	config = $.extend( { 'popupWidth': 320 }, config );

	// Properties
	this.popup = new OO.ui.PopupWidget( $.extend(
		{ 'align': 'center', 'autoClose': true },
		config.popup,
		{ '$': this.$, '$autoCloseIgnore': this.$element }
	) );
	this.popupWidth = config.popupWidth;
	this.popupHeight = config.popupHeight;
};

/* Methods */

/**
 * Get popup.
 *
 * @return {OO.ui.PopupWidget} Popup widget
 */
OO.ui.PopuppableElement.prototype.getPopup = function () {
	return this.popup;
};

/**
 * Show popup.
 */
OO.ui.PopuppableElement.prototype.showPopup = function () {
	this.popup.show().display( this.popupWidth, this.popupHeight );
};

/**
 * Hide popup.
 */
OO.ui.PopuppableElement.prototype.hidePopup = function () {
	this.popup.hide();
};
/**
 * Element with a title.
 *
 * @abstract
 * @class
 *
 * @constructor
 * @param {jQuery} $label Titled node, assigned to #$titled
 * @param {Object} [config] Configuration options
 * @cfg {string|Function} [title] Title text or a function that returns text
 */
OO.ui.TitledElement = function OoUiTitledElement( $titled, config ) {
	// Config intialization
	config = config || {};

	// Properties
	this.$titled = $titled;
	this.title = null;

	// Initialization
	this.setTitle( config.title || this.constructor.static.title );
};

/* Setup */

OO.initClass( OO.ui.TitledElement );

/* Static Properties */

/**
 * Title.
 *
 * @static
 * @inheritable
 * @property {string|Function} Title text or a function that returns text
 */
OO.ui.TitledElement.static.title = null;

/* Methods */

/**
 * Set title.
 *
 * @param {string|Function|null} title Title text, a function that returns text or null for no title
 * @chainable
 */
OO.ui.TitledElement.prototype.setTitle = function ( title ) {
	this.title = title = OO.ui.resolveMsg( title ) || null;

	if ( typeof title === 'string' && title.length ) {
		this.$titled.attr( 'title', title );
	} else {
		this.$titled.removeAttr( 'title' );
	}

	return this;
};

/**
 * Get title.
 *
 * @return {string} Title string
 */
OO.ui.TitledElement.prototype.getTitle = function () {
	return this.title;
};
/**
 * Generic toolbar tool.
 *
 * @abstract
 * @class
 * @extends OO.ui.Widget
 * @mixins OO.ui.IconedElement
 *
 * @constructor
 * @param {OO.ui.ToolGroup} toolGroup
 * @param {Object} [config] Configuration options
 * @cfg {string|Function} [title] Title text or a function that returns text
 */
OO.ui.Tool = function OoUiTool( toolGroup, config ) {
	// Config intialization
	config = config || {};

	// Parent constructor
	OO.ui.Tool.super.call( this, config );

	// Mixin constructors
	OO.ui.IconedElement.call( this, this.$( '<span>' ), config );

	// Properties
	this.toolGroup = toolGroup;
	this.toolbar = this.toolGroup.getToolbar();
	this.active = false;
	this.$title = this.$( '<span>' );
	this.$link = this.$( '<a>' );
	this.title = null;

	// Events
	this.toolbar.connect( this, { 'updateState': 'onUpdateState' } );

	// Initialization
	this.$title.addClass( 'oo-ui-tool-title' );
	this.$link
		.addClass( 'oo-ui-tool-link' )
		.append( this.$icon, this.$title );
	this.$element
		.data( 'oo-ui-tool', this )
		.addClass(
			'oo-ui-tool ' + 'oo-ui-tool-name-' +
			this.constructor.static.name.replace( /^([^\/]+)\/([^\/]+).*$/, '$1-$2' )
		)
		.append( this.$link );
	this.setTitle( config.title || this.constructor.static.title );
};

/* Setup */

OO.inheritClass( OO.ui.Tool, OO.ui.Widget );
OO.mixinClass( OO.ui.Tool, OO.ui.IconedElement );

/* Events */

/**
 * @event select
 */

/* Static Properties */

/**
 * @static
 * @inheritdoc
 */
OO.ui.Tool.static.tagName = 'span';

/**
 * Symbolic name of tool.
 *
 * @abstract
 * @static
 * @inheritable
 * @property {string}
 */
OO.ui.Tool.static.name = '';

/**
 * Tool group.
 *
 * @abstract
 * @static
 * @inheritable
 * @property {string}
 */
OO.ui.Tool.static.group = '';

/**
 * Tool title.
 *
 * Title is used as a tooltip when the tool is part of a bar tool group, or a label when the tool
 * is part of a list or menu tool group. If a trigger is associated with an action by the same name
 * as the tool, a description of its keyboard shortcut for the appropriate platform will be
 * appended to the title if the tool is part of a bar tool group.
 *
 * @abstract
 * @static
 * @inheritable
 * @property {string|Function} Title text or a function that returns text
 */
OO.ui.Tool.static.title = '';

/**
 * Tool can be automatically added to catch-all groups.
 *
 * @static
 * @inheritable
 * @property {boolean}
 */
OO.ui.Tool.static.autoAddToCatchall = true;

/**
 * Tool can be automatically added to named groups.
 *
 * @static
 * @property {boolean}
 * @inheritable
 */
OO.ui.Tool.static.autoAddToGroup = true;

/**
 * Check if this tool is compatible with given data.
 *
 * @static
 * @inheritable
 * @param {Mixed} data Data to check
 * @return {boolean} Tool can be used with data
 */
OO.ui.Tool.static.isCompatibleWith = function () {
	return false;
};

/* Methods */

/**
 * Handle the toolbar state being updated.
 *
 * This is an abstract method that must be overridden in a concrete subclass.
 *
 * @abstract
 */
OO.ui.Tool.prototype.onUpdateState = function () {
	throw new Error(
		'OO.ui.Tool.onUpdateState not implemented in this subclass:' + this.constructor
	);
};

/**
 * Handle the tool being selected.
 *
 * This is an abstract method that must be overridden in a concrete subclass.
 *
 * @abstract
 */
OO.ui.Tool.prototype.onSelect = function () {
	throw new Error(
		'OO.ui.Tool.onSelect not implemented in this subclass:' + this.constructor
	);
};

/**
 * Check if the button is active.
 *
 * @param {boolean} Button is active
 */
OO.ui.Tool.prototype.isActive = function () {
	return this.active;
};

/**
 * Make the button appear active or inactive.
 *
 * @param {boolean} state Make button appear active
 */
OO.ui.Tool.prototype.setActive = function ( state ) {
	this.active = !!state;
	if ( this.active ) {
		this.$element.addClass( 'oo-ui-tool-active' );
	} else {
		this.$element.removeClass( 'oo-ui-tool-active' );
	}
};

/**
 * Get the tool title.
 *
 * @param {string|Function} title Title text or a function that returns text
 * @chainable
 */
OO.ui.Tool.prototype.setTitle = function ( title ) {
	this.title = OO.ui.resolveMsg( title );
	this.updateTitle();
	return this;
};

/**
 * Get the tool title.
 *
 * @return {string} Title text
 */
OO.ui.Tool.prototype.getTitle = function () {
	return this.title;
};

/**
 * Get the tool's symbolic name.
 *
 * @return {string} Symbolic name of tool
 */
OO.ui.Tool.prototype.getName = function () {
	return this.constructor.static.name;
};

/**
 * Update the title.
 */
OO.ui.Tool.prototype.updateTitle = function () {
	var titleTooltips = this.toolGroup.constructor.static.titleTooltips,
		accelTooltips = this.toolGroup.constructor.static.accelTooltips,
		accel = this.toolbar.getToolAccelerator( this.constructor.static.name ),
		tooltipParts = [];

	this.$title.empty()
		.text( this.title )
		.append(
			this.$( '<span>' )
				.addClass( 'oo-ui-tool-accel' )
				.text( accel )
		);

	if ( titleTooltips && typeof this.title === 'string' && this.title.length ) {
		tooltipParts.push( this.title );
	}
	if ( accelTooltips && typeof accel === 'string' && accel.length ) {
		tooltipParts.push( accel );
	}
	if ( tooltipParts.length ) {
		this.$link.attr( 'title', tooltipParts.join( ' ' ) );
	} else {
		this.$link.removeAttr( 'title' );
	}
};

/**
 * Destroy tool.
 */
OO.ui.Tool.prototype.destroy = function () {
	this.toolbar.disconnect( this );
	this.$element.remove();
};
/**
 * Collection of tool groups.
 *
 * @class
 * @extends OO.ui.Element
 * @mixins OO.EventEmitter
 * @mixins OO.ui.GroupElement
 *
 * @constructor
 * @param {OO.ui.ToolFactory} toolFactory Factory for creating tools
 * @param {OO.ui.ToolGroupFactory} toolGroupFactory Factory for creating tool groups
 * @param {Object} [config] Configuration options
 * @cfg {boolean} [actions] Add an actions section opposite to the tools
 * @cfg {boolean} [shadow] Add a shadow below the toolbar
 */
OO.ui.Toolbar = function OoUiToolbar( toolFactory, toolGroupFactory, config ) {
	// Configuration initialization
	config = config || {};

	// Parent constructor
	OO.ui.Toolbar.super.call( this, config );

	// Mixin constructors
	OO.EventEmitter.call( this );
	OO.ui.GroupElement.call( this, this.$( '<div>' ), config );

	// Properties
	this.toolFactory = toolFactory;
	this.toolGroupFactory = toolGroupFactory;
	this.groups = [];
	this.tools = {};
	this.$bar = this.$( '<div>' );
	this.$actions = this.$( '<div>' );
	this.initialized = false;

	// Events
	this.$element
		.add( this.$bar ).add( this.$group ).add( this.$actions )
		.on( 'mousedown', OO.ui.bind( this.onMouseDown, this ) );

	// Initialization
	this.$group.addClass( 'oo-ui-toolbar-tools' );
	this.$bar.addClass( 'oo-ui-toolbar-bar' ).append( this.$group );
	if ( config.actions ) {
		this.$actions.addClass( 'oo-ui-toolbar-actions' );
		this.$bar.append( this.$actions );
	}
	this.$bar.append( '<div style="clear:both"></div>' );
	if ( config.shadow ) {
		this.$bar.append( '<div class="oo-ui-toolbar-shadow"></div>' );
	}
	this.$element.addClass( 'oo-ui-toolbar' ).append( this.$bar );
};

/* Setup */

OO.inheritClass( OO.ui.Toolbar, OO.ui.Element );
OO.mixinClass( OO.ui.Toolbar, OO.EventEmitter );
OO.mixinClass( OO.ui.Toolbar, OO.ui.GroupElement );

/* Methods */

/**
 * Get the tool factory.
 *
 * @return {OO.ui.ToolFactory} Tool factory
 */
OO.ui.Toolbar.prototype.getToolFactory = function () {
	return this.toolFactory;
};

/**
 * Get the tool group factory.
 *
 * @return {OO.Factory} Tool group factory
 */
OO.ui.Toolbar.prototype.getToolGroupFactory = function () {
	return this.toolGroupFactory;
};

/**
 * Handles mouse down events.
 *
 * @param {jQuery.Event} e Mouse down event
 */
OO.ui.Toolbar.prototype.onMouseDown = function ( e ) {
	var $closestWidgetToEvent = this.$( e.target ).closest( '.oo-ui-widget' ),
		$closestWidgetToToolbar = this.$element.closest( '.oo-ui-widget' );
	if ( !$closestWidgetToEvent.length || $closestWidgetToEvent[0] === $closestWidgetToToolbar[0] ) {
		return false;
	}
};

/**
 * Sets up handles and preloads required information for the toolbar to work.
 * This must be called immediately after it is attached to a visible document.
 */
OO.ui.Toolbar.prototype.initialize = function () {
	this.initialized = true;
};

/**
 * Setup toolbar.
 *
 * Tools can be specified in the following ways:
 *
 * - A specific tool: `{ 'name': 'tool-name' }` or `'tool-name'`
 * - All tools in a group: `{ 'group': 'group-name' }`
 * - All tools: `'*'` - Using this will make the group a list with a "More" label by default
 *
 * @param {Object.<string,Array>} groups List of tool group configurations
 * @param {Array|string} [groups.include] Tools to include
 * @param {Array|string} [groups.exclude] Tools to exclude
 * @param {Array|string} [groups.promote] Tools to promote to the beginning
 * @param {Array|string} [groups.demote] Tools to demote to the end
 */
OO.ui.Toolbar.prototype.setup = function ( groups ) {
	var i, len, type, group,
		items = [],
		defaultType = 'bar';

	// Cleanup previous groups
	this.reset();

	// Build out new groups
	for ( i = 0, len = groups.length; i < len; i++ ) {
		group = groups[i];
		if ( group.include === '*' ) {
			// Apply defaults to catch-all groups
			if ( group.type === undefined ) {
				group.type = 'list';
			}
			if ( group.label === undefined ) {
				group.label = 'ooui-toolbar-more';
			}
		}
		// Check type has been registered
		type = this.getToolGroupFactory().lookup( group.type ) ? group.type : defaultType;
		items.push(
			this.getToolGroupFactory().create( type, this, $.extend( { '$': this.$ }, group ) )
		);
	}
	this.addItems( items );
};

/**
 * Remove all tools and groups from the toolbar.
 */
OO.ui.Toolbar.prototype.reset = function () {
	var i, len;

	this.groups = [];
	this.tools = {};
	for ( i = 0, len = this.items.length; i < len; i++ ) {
		this.items[i].destroy();
	}
	this.clearItems();
};

/**
 * Destroys toolbar, removing event handlers and DOM elements.
 *
 * Call this whenever you are done using a toolbar.
 */
OO.ui.Toolbar.prototype.destroy = function () {
	this.reset();
	this.$element.remove();
};

/**
 * Check if tool has not been used yet.
 *
 * @param {string} name Symbolic name of tool
 * @return {boolean} Tool is available
 */
OO.ui.Toolbar.prototype.isToolAvailable = function ( name ) {
	return !this.tools[name];
};

/**
 * Prevent tool from being used again.
 *
 * @param {OO.ui.Tool} tool Tool to reserve
 */
OO.ui.Toolbar.prototype.reserveTool = function ( tool ) {
	this.tools[tool.getName()] = tool;
};

/**
 * Allow tool to be used again.
 *
 * @param {OO.ui.Tool} tool Tool to release
 */
OO.ui.Toolbar.prototype.releaseTool = function ( tool ) {
	delete this.tools[tool.getName()];
};

/**
 * Get accelerator label for tool.
 *
 * This is a stub that should be overridden to provide access to accelerator information.
 *
 * @param {string} name Symbolic name of tool
 * @return {string|undefined} Tool accelerator label if available
 */
OO.ui.Toolbar.prototype.getToolAccelerator = function () {
	return undefined;
};
/**
 * Factory for tools.
 *
 * @class
 * @extends OO.Factory
 * @constructor
 */
OO.ui.ToolFactory = function OoUiToolFactory() {
	// Parent constructor
	OO.ui.ToolFactory.super.call( this );
};

/* Setup */

OO.inheritClass( OO.ui.ToolFactory, OO.Factory );

/* Methods */

/** */
OO.ui.ToolFactory.prototype.getTools = function ( include, exclude, promote, demote ) {
	var i, len, included, promoted, demoted,
		auto = [],
		used = {};

	// Collect included and not excluded tools
	included = OO.simpleArrayDifference( this.extract( include ), this.extract( exclude ) );

	// Promotion
	promoted = this.extract( promote, used );
	demoted = this.extract( demote, used );

	// Auto
	for ( i = 0, len = included.length; i < len; i++ ) {
		if ( !used[included[i]] ) {
			auto.push( included[i] );
		}
	}

	return promoted.concat( auto ).concat( demoted );
};

/**
 * Get a flat list of names from a list of names or groups.
 *
 * Tools can be specified in the following ways:
 *
 * - A specific tool: `{ 'name': 'tool-name' }` or `'tool-name'`
 * - All tools in a group: `{ 'group': 'group-name' }`
 * - All tools: `'*'`
 *
 * @private
 * @param {Array|string} collection List of tools
 * @param {Object} [used] Object with names that should be skipped as properties; extracted
 *  names will be added as properties
 * @return {string[]} List of extracted names
 */
OO.ui.ToolFactory.prototype.extract = function ( collection, used ) {
	var i, len, item, name, tool,
		names = [];

	if ( collection === '*' ) {
		for ( name in this.registry ) {
			tool = this.registry[name];
			if (
				// Only add tools by group name when auto-add is enabled
				tool.static.autoAddToCatchall &&
				// Exclude already used tools
				( !used || !used[name] )
			) {
				names.push( name );
				if ( used ) {
					used[name] = true;
				}
			}
		}
	} else if ( $.isArray( collection ) ) {
		for ( i = 0, len = collection.length; i < len; i++ ) {
			item = collection[i];
			// Allow plain strings as shorthand for named tools
			if ( typeof item === 'string' ) {
				item = { 'name': item };
			}
			if ( OO.isPlainObject( item ) ) {
				if ( item.group ) {
					for ( name in this.registry ) {
						tool = this.registry[name];
						if (
							// Include tools with matching group
							tool.static.group === item.group &&
							// Only add tools by group name when auto-add is enabled
							tool.static.autoAddToGroup &&
							// Exclude already used tools
							( !used || !used[name] )
						) {
							names.push( name );
							if ( used ) {
								used[name] = true;
							}
						}
					}
				// Include tools with matching name and exclude already used tools
				} else if ( item.name && ( !used || !used[item.name] ) ) {
					names.push( item.name );
					if ( used ) {
						used[item.name] = true;
					}
				}
			}
		}
	}
	return names;
};
/**
 * Collection of tools.
 *
 * Tools can be specified in the following ways:
 *
 * - A specific tool: `{ 'name': 'tool-name' }` or `'tool-name'`
 * - All tools in a group: `{ 'group': 'group-name' }`
 * - All tools: `'*'`
 *
 * @abstract
 * @class
 * @extends OO.ui.Widget
 * @mixins OO.ui.GroupElement
 *
 * @constructor
 * @param {OO.ui.Toolbar} toolbar
 * @param {Object} [config] Configuration options
 * @cfg {Array|string} [include=[]] List of tools to include
 * @cfg {Array|string} [exclude=[]] List of tools to exclude
 * @cfg {Array|string} [promote=[]] List of tools to promote to the beginning
 * @cfg {Array|string} [demote=[]] List of tools to demote to the end
 */
OO.ui.ToolGroup = function OoUiToolGroup( toolbar, config ) {
	// Configuration initialization
	config = $.extend( true, {
		'aggregations': { 'disable': 'itemDisable' }
	}, config );

	// Parent constructor
	OO.ui.ToolGroup.super.call( this, config );

	// Mixin constructors
	OO.ui.GroupElement.call( this, this.$( '<div>' ), config );

	// Properties
	this.toolbar = toolbar;
	this.tools = {};
	this.pressed = null;
	this.autoDisabled = false;
	this.include = config.include || [];
	this.exclude = config.exclude || [];
	this.promote = config.promote || [];
	this.demote = config.demote || [];
	this.onCapturedMouseUpHandler = OO.ui.bind( this.onCapturedMouseUp, this );

	// Events
	this.$element.on( {
		'mousedown': OO.ui.bind( this.onMouseDown, this ),
		'mouseup': OO.ui.bind( this.onMouseUp, this ),
		'mouseover': OO.ui.bind( this.onMouseOver, this ),
		'mouseout': OO.ui.bind( this.onMouseOut, this )
	} );
	this.toolbar.getToolFactory().connect( this, { 'register': 'onToolFactoryRegister' } );
	this.connect( this, { 'itemDisable': 'updateDisabled' } );

	// Initialization
	this.$group.addClass( 'oo-ui-toolGroup-tools' );
	this.$element
		.addClass( 'oo-ui-toolGroup' )
		.append( this.$group );
	this.populate();
};

/* Setup */

OO.inheritClass( OO.ui.ToolGroup, OO.ui.Widget );
OO.mixinClass( OO.ui.ToolGroup, OO.ui.GroupElement );

/* Events */

/**
 * @event update
 */

/* Static Properties */

/**
 * Show labels in tooltips.
 *
 * @static
 * @inheritable
 * @property {boolean}
 */
OO.ui.ToolGroup.static.titleTooltips = false;

/**
 * Show acceleration labels in tooltips.
 *
 * @static
 * @inheritable
 * @property {boolean}
 */
OO.ui.ToolGroup.static.accelTooltips = false;

/**
 * Automatically disable the toolgroup when all tools are disabled
 *
 * @static
 * @inheritable
 * @property {boolean}
 */
OO.ui.ToolGroup.static.autoDisable = true;

/* Methods */

/**
 * @inheritdoc
 */
OO.ui.ToolGroup.prototype.isDisabled = function () {
	return this.autoDisabled || OO.ui.ToolGroup.super.prototype.isDisabled.apply( this, arguments );
};

/**
 * @inheritdoc
 */
OO.ui.ToolGroup.prototype.updateDisabled = function () {
	var i, item, allDisabled = true;

	if ( this.constructor.static.autoDisable ) {
		for ( i = this.items.length - 1; i >= 0; i-- ) {
			item = this.items[i];
			if ( !item.isDisabled() ) {
				allDisabled = false;
				break;
			}
		}
		this.autoDisabled = allDisabled;
	}
	OO.ui.ToolGroup.super.prototype.updateDisabled.apply( this, arguments );
};

/**
 * Handle mouse down events.
 *
 * @param {jQuery.Event} e Mouse down event
 */
OO.ui.ToolGroup.prototype.onMouseDown = function ( e ) {
	if ( !this.disabled && e.which === 1 ) {
		this.pressed = this.getTargetTool( e );
		if ( this.pressed ) {
			this.pressed.setActive( true );
			this.getElementDocument().addEventListener(
				'mouseup', this.onCapturedMouseUpHandler, true
			);
			return false;
		}
	}
};

/**
 * Handle captured mouse up events.
 *
 * @param {Event} e Mouse up event
 */
OO.ui.ToolGroup.prototype.onCapturedMouseUp = function ( e ) {
	this.getElementDocument().removeEventListener( 'mouseup', this.onCapturedMouseUpHandler, true );
	// onMouseUp may be called a second time, depending on where the mouse is when the button is
	// released, but since `this.pressed` will no longer be true, the second call will be ignored.
	this.onMouseUp( e );
};

/**
 * Handle mouse up events.
 *
 * @param {jQuery.Event} e Mouse up event
 */
OO.ui.ToolGroup.prototype.onMouseUp = function ( e ) {
	var tool = this.getTargetTool( e );

	if ( !this.disabled && e.which === 1 && this.pressed && this.pressed === tool ) {
		this.pressed.onSelect();
	}

	this.pressed = null;
	return false;
};

/**
 * Handle mouse over events.
 *
 * @param {jQuery.Event} e Mouse over event
 */
OO.ui.ToolGroup.prototype.onMouseOver = function ( e ) {
	var tool = this.getTargetTool( e );

	if ( this.pressed && this.pressed === tool ) {
		this.pressed.setActive( true );
	}
};

/**
 * Handle mouse out events.
 *
 * @param {jQuery.Event} e Mouse out event
 */
OO.ui.ToolGroup.prototype.onMouseOut = function ( e ) {
	var tool = this.getTargetTool( e );

	if ( this.pressed && this.pressed === tool ) {
		this.pressed.setActive( false );
	}
};

/**
 * Get the closest tool to a jQuery.Event.
 *
 * Only tool links are considered, which prevents other elements in the tool such as popups from
 * triggering tool group interactions.
 *
 * @private
 * @param {jQuery.Event} e
 * @return {OO.ui.Tool|null} Tool, `null` if none was found
 */
OO.ui.ToolGroup.prototype.getTargetTool = function ( e ) {
	var tool,
		$item = this.$( e.target ).closest( '.oo-ui-tool-link' );

	if ( $item.length ) {
		tool = $item.parent().data( 'oo-ui-tool' );
	}

	return tool && !tool.isDisabled() ? tool : null;
};

/**
 * Handle tool registry register events.
 *
 * If a tool is registered after the group is created, we must repopulate the list to account for:
 *
 * - a tool being added that may be included
 * - a tool already included being overridden
 *
 * @param {string} name Symbolic name of tool
 */
OO.ui.ToolGroup.prototype.onToolFactoryRegister = function () {
	this.populate();
};

/**
 * Get the toolbar this group is in.
 *
 * @return {OO.ui.Toolbar} Toolbar of group
 */
OO.ui.ToolGroup.prototype.getToolbar = function () {
	return this.toolbar;
};

/**
 * Add and remove tools based on configuration.
 */
OO.ui.ToolGroup.prototype.populate = function () {
	var i, len, name, tool,
		toolFactory = this.toolbar.getToolFactory(),
		names = {},
		add = [],
		remove = [],
		list = this.toolbar.getToolFactory().getTools(
			this.include, this.exclude, this.promote, this.demote
		);

	// Build a list of needed tools
	for ( i = 0, len = list.length; i < len; i++ ) {
		name = list[i];
		if (
			// Tool exists
			toolFactory.lookup( name ) &&
			// Tool is available or is already in this group
			( this.toolbar.isToolAvailable( name ) || this.tools[name] )
		) {
			tool = this.tools[name];
			if ( !tool ) {
				// Auto-initialize tools on first use
				this.tools[name] = tool = toolFactory.create( name, this );
				tool.updateTitle();
			}
			this.toolbar.reserveTool( tool );
			add.push( tool );
			names[name] = true;
		}
	}
	// Remove tools that are no longer needed
	for ( name in this.tools ) {
		if ( !names[name] ) {
			this.tools[name].destroy();
			this.toolbar.releaseTool( this.tools[name] );
			remove.push( this.tools[name] );
			delete this.tools[name];
		}
	}
	if ( remove.length ) {
		this.removeItems( remove );
	}
	// Update emptiness state
	if ( add.length ) {
		this.$element.removeClass( 'oo-ui-toolGroup-empty' );
	} else {
		this.$element.addClass( 'oo-ui-toolGroup-empty' );
	}
	// Re-add tools (moving existing ones to new locations)
	this.addItems( add );
	// Disabled state may depend on items
	this.updateDisabled();
};

/**
 * Destroy tool group.
 */
OO.ui.ToolGroup.prototype.destroy = function () {
	var name;

	this.clearItems();
	this.toolbar.getToolFactory().disconnect( this );
	for ( name in this.tools ) {
		this.toolbar.releaseTool( this.tools[name] );
		this.tools[name].disconnect( this ).destroy();
		delete this.tools[name];
	}
	this.$element.remove();
};
/**
 * Factory for tool groups.
 *
 * @class
 * @extends OO.Factory
 * @constructor
 */
OO.ui.ToolGroupFactory = function OoUiToolGroupFactory() {
	// Parent constructor
	OO.Factory.call( this );

	var i, l,
		defaultClasses = this.constructor.static.getDefaultClasses();

	// Register default toolgroups
	for ( i = 0, l = defaultClasses.length; i < l; i++ ) {
		this.register( defaultClasses[i] );
	}
};

/* Setup */

OO.inheritClass( OO.ui.ToolGroupFactory, OO.Factory );

/* Static Methods */

/**
 * Get a default set of classes to be registered on construction
 *
 * @return {Function[]} Default classes
 */
OO.ui.ToolGroupFactory.static.getDefaultClasses = function () {
	return [
		OO.ui.BarToolGroup,
		OO.ui.ListToolGroup,
		OO.ui.MenuToolGroup
	];
};
/**
 * Layout made of a fieldset and optional legend.
 *
 * Just add OO.ui.FieldLayout items.
 *
 * @class
 * @extends OO.ui.Layout
 * @mixins OO.ui.LabeledElement
 * @mixins OO.ui.IconedElement
 * @mixins OO.ui.GroupElement
 *
 * @constructor
 * @param {Object} [config] Configuration options
 * @cfg {string} [icon] Symbolic icon name
 * @cfg {OO.ui.FieldLayout[]} [items] Items to add
 */
OO.ui.FieldsetLayout = function OoUiFieldsetLayout( config ) {
	// Config initialization
	config = config || {};

	// Parent constructor
	OO.ui.FieldsetLayout.super.call( this, config );

	// Mixin constructors
	OO.ui.IconedElement.call( this, this.$( '<div>' ), config );
	OO.ui.LabeledElement.call( this, this.$( '<legend>' ), config );
	OO.ui.GroupElement.call( this, this.$( '<div>' ), config );

	// Initialization
	this.$element
		.addClass( 'oo-ui-fieldsetLayout' )
		.append( this.$icon, this.$label, this.$group );
	if ( $.isArray( config.items ) ) {
		this.addItems( config.items );
	}
};

/* Setup */

OO.inheritClass( OO.ui.FieldsetLayout, OO.ui.Layout );
OO.mixinClass( OO.ui.FieldsetLayout, OO.ui.IconedElement );
OO.mixinClass( OO.ui.FieldsetLayout, OO.ui.LabeledElement );
OO.mixinClass( OO.ui.FieldsetLayout, OO.ui.GroupElement );

/* Static Properties */

OO.ui.FieldsetLayout.static.tagName = 'fieldset';
/**
 * Layout made of a field and optional label.
 *
 * @class
 * @extends OO.ui.Layout
 * @mixins OO.ui.LabeledElement
 *
 * Available label alignment modes include:
 *  - 'left': Label is before the field and aligned away from it, best for when the user will be
 *    scanning for a specific label in a form with many fields
 *  - 'right': Label is before the field and aligned toward it, best for forms the user is very
 *    familiar with and will tab through field checking quickly to verify which field they are in
 *  - 'top': Label is before the field and above it, best for when the use will need to fill out all
 *    fields from top to bottom in a form with few fields
 *  - 'inline': Label is after the field and aligned toward it, best for small boolean fields like
 *    checkboxes or radio buttons
 *
 * @constructor
 * @param {OO.ui.Widget} field Field widget
 * @param {Object} [config] Configuration options
 * @cfg {string} [align='left'] Alignment mode, either 'left', 'right', 'top' or 'inline'
 */
OO.ui.FieldLayout = function OoUiFieldLayout( field, config ) {
	// Config initialization
	config = $.extend( { 'align': 'left' }, config );

	// Parent constructor
	OO.ui.FieldLayout.super.call( this, config );

	// Mixin constructors
	OO.ui.LabeledElement.call( this, this.$( '<label>' ), config );

	// Properties
	this.$field = this.$( '<div>' );
	this.field = field;
	this.align = null;

	// Events
	if ( this.field instanceof OO.ui.InputWidget ) {
		this.$label.on( 'click', OO.ui.bind( this.onLabelClick, this ) );
	}
	this.field.connect( this, { 'disable': 'onFieldDisable' } );

	// Initialization
	this.$element.addClass( 'oo-ui-fieldLayout' );
	this.$field
		.addClass( 'oo-ui-fieldLayout-field' )
		.toggleClass( 'oo-ui-fieldLayout-disable', this.field.isDisabled() )
		.append( this.field.$element );
	this.setAlignment( config.align );
};

/* Setup */

OO.inheritClass( OO.ui.FieldLayout, OO.ui.Layout );
OO.mixinClass( OO.ui.FieldLayout, OO.ui.LabeledElement );

/* Methods */

/**
 * Handle field disable events.
 *
 * @param {boolean} value Field is disabled
 */
OO.ui.FieldLayout.prototype.onFieldDisable = function ( value ) {
	this.$element.toggleClass( 'oo-ui-fieldLayout-disabled', value );
};

/**
 * Handle label mouse click events.
 *
 * @param {jQuery.Event} e Mouse click event
 */
OO.ui.FieldLayout.prototype.onLabelClick = function () {
	this.field.simulateLabelClick();
	return false;
};

/**
 * Get the field.
 *
 * @return {OO.ui.Widget} Field widget
 */
OO.ui.FieldLayout.prototype.getField = function () {
	return this.field;
};

/**
 * Set the field alignment mode.
 *
 * @param {string} value Alignment mode, either 'left', 'right', 'top' or 'inline'
 * @chainable
 */
OO.ui.FieldLayout.prototype.setAlignment = function ( value ) {
	if ( value !== this.align ) {
		// Default to 'left'
		if ( [ 'left', 'right', 'top', 'inline' ].indexOf( value ) === -1 ) {
			value = 'left';
		}
		// Reorder elements
		if ( value === 'inline' ) {
			this.$element.append( this.$field, this.$label );
		} else {
			this.$element.append( this.$label, this.$field );
		}
		// Set classes
		if ( this.align ) {
			this.$element.removeClass( 'oo-ui-fieldLayout-align-' + this.align );
		}
		this.align = value;
		this.$element.addClass( 'oo-ui-fieldLayout-align-' + this.align );
	}

	return this;
};
/**
 * Layout made of proportionally sized columns and rows.
 *
 * @class
 * @extends OO.ui.Layout
 *
 * @constructor
 * @param {OO.ui.PanelLayout[]} panels Panels in the grid
 * @param {Object} [config] Configuration options
 * @cfg {number[]} [widths] Widths of columns as ratios
 * @cfg {number[]} [heights] Heights of columns as ratios
 */
OO.ui.GridLayout = function OoUiGridLayout( panels, config ) {
	var i, len, widths;

	// Config initialization
	config = config || {};

	// Parent constructor
	OO.ui.GridLayout.super.call( this, config );

	// Properties
	this.panels = [];
	this.widths = [];
	this.heights = [];

	// Initialization
	this.$element.addClass( 'oo-ui-gridLayout' );
	for ( i = 0, len = panels.length; i < len; i++ ) {
		this.panels.push( panels[i] );
		this.$element.append( panels[i].$element );
	}
	if ( config.widths || config.heights ) {
		this.layout( config.widths || [1], config.heights || [1] );
	} else {
		// Arrange in columns by default
		widths = [];
		for ( i = 0, len = this.panels.length; i < len; i++ ) {
			widths[i] = 1;
		}
		this.layout( widths, [1] );
	}
};

/* Setup */

OO.inheritClass( OO.ui.GridLayout, OO.ui.Layout );

/* Events */

/**
 * @event layout
 */

/**
 * @event update
 */

/* Static Properties */

OO.ui.GridLayout.static.tagName = 'div';

/* Methods */

/**
 * Set grid dimensions.
 *
 * @param {number[]} widths Widths of columns as ratios
 * @param {number[]} heights Heights of rows as ratios
 * @fires layout
 * @throws {Error} If grid is not large enough to fit all panels
 */
OO.ui.GridLayout.prototype.layout = function ( widths, heights ) {
	var x, y,
		xd = 0,
		yd = 0,
		cols = widths.length,
		rows = heights.length;

	// Verify grid is big enough to fit panels
	if ( cols * rows < this.panels.length ) {
		throw new Error( 'Grid is not large enough to fit ' + this.panels.length + 'panels' );
	}

	// Sum up denominators
	for ( x = 0; x < cols; x++ ) {
		xd += widths[x];
	}
	for ( y = 0; y < rows; y++ ) {
		yd += heights[y];
	}
	// Store factors
	this.widths = [];
	this.heights = [];
	for ( x = 0; x < cols; x++ ) {
		this.widths[x] = widths[x] / xd;
	}
	for ( y = 0; y < rows; y++ ) {
		this.heights[y] = heights[y] / yd;
	}
	// Synchronize view
	this.update();
	this.emit( 'layout' );
};

/**
 * Update panel positions and sizes.
 *
 * @fires update
 */
OO.ui.GridLayout.prototype.update = function () {
	var x, y, panel,
		i = 0,
		left = 0,
		top = 0,
		dimensions,
		width = 0,
		height = 0,
		cols = this.widths.length,
		rows = this.heights.length;

	for ( y = 0; y < rows; y++ ) {
		for ( x = 0; x < cols; x++ ) {
			panel = this.panels[i];
			width = this.widths[x];
			height = this.heights[y];
			dimensions = {
				'width': Math.round( width * 100 ) + '%',
				'height': Math.round( height * 100 ) + '%',
				'top': Math.round( top * 100 ) + '%'
			};
			// If RTL, reverse:
			if ( OO.ui.Element.getDir( this.$.context ) === 'rtl' ) {
				dimensions.right = Math.round( left * 100 ) + '%';
			} else {
				dimensions.left = Math.round( left * 100 ) + '%';
			}
			panel.$element.css( dimensions );
			i++;
			left += width;
		}
		top += height;
		left = 0;
	}

	this.emit( 'update' );
};

/**
 * Get a panel at a given position.
 *
 * The x and y position is affected by the current grid layout.
 *
 * @param {number} x Horizontal position
 * @param {number} y Vertical position
 * @return {OO.ui.PanelLayout} The panel at the given postion
 */
OO.ui.GridLayout.prototype.getPanel = function ( x, y ) {
	return this.panels[( x * this.widths.length ) + y];
};
/**
 * Layout containing a series of pages.
 *
 * @class
 * @extends OO.ui.Layout
 *
 * @constructor
 * @param {Object} [config] Configuration options
 * @cfg {boolean} [continuous=false] Show all pages, one after another
 * @cfg {boolean} [autoFocus=false] Focus on the first focusable element when changing to a page
 * @cfg {boolean} [outlined=false] Show an outline
 * @cfg {boolean} [editable=false] Show controls for adding, removing and reordering pages
 * @cfg {Object[]} [adders] List of adders for controls, each with name, icon and title properties
 */
OO.ui.BookletLayout = function OoUiBookletLayout( config ) {
	// Initialize configuration
	config = config || {};

	// Parent constructor
	OO.ui.BookletLayout.super.call( this, config );

	// Properties
	this.currentPageName = null;
	this.pages = {};
	this.ignoreFocus = false;
	this.stackLayout = new OO.ui.StackLayout( { '$': this.$, 'continuous': !!config.continuous } );
	this.autoFocus = !!config.autoFocus;
	this.outlineVisible = false;
	this.outlined = !!config.outlined;
	if ( this.outlined ) {
		this.editable = !!config.editable;
		this.adders = config.adders || null;
		this.outlineControlsWidget = null;
		this.outlineWidget = new OO.ui.OutlineWidget( { '$': this.$ } );
		this.outlinePanel = new OO.ui.PanelLayout( { '$': this.$, 'scrollable': true } );
		this.gridLayout = new OO.ui.GridLayout(
			[this.outlinePanel, this.stackLayout], { '$': this.$, 'widths': [1, 2] }
		);
		this.outlineVisible = true;
		if ( this.editable ) {
			this.outlineControlsWidget = new OO.ui.OutlineControlsWidget(
				this.outlineWidget,
				{ '$': this.$, 'adders': this.adders }
			);
		}
	}

	// Events
	this.stackLayout.connect( this, { 'set': 'onStackLayoutSet' } );
	if ( this.outlined ) {
		this.outlineWidget.connect( this, { 'select': 'onOutlineWidgetSelect' } );
	}
	if ( this.autoFocus ) {
		// Event 'focus' does not bubble, but 'focusin' does
		this.stackLayout.onDOMEvent( 'focusin', OO.ui.bind( this.onStackLayoutFocus, this ) );
	}

	// Initialization
	this.$element.addClass( 'oo-ui-bookletLayout' );
	this.stackLayout.$element.addClass( 'oo-ui-bookletLayout-stackLayout' );
	if ( this.outlined ) {
		this.outlinePanel.$element
			.addClass( 'oo-ui-bookletLayout-outlinePanel' )
			.append( this.outlineWidget.$element );
		if ( this.editable ) {
			this.outlinePanel.$element
				.addClass( 'oo-ui-bookletLayout-outlinePanel-editable' )
				.append( this.outlineControlsWidget.$element );
		}
		this.$element.append( this.gridLayout.$element );
	} else {
		this.$element.append( this.stackLayout.$element );
	}
};

/* Setup */

OO.inheritClass( OO.ui.BookletLayout, OO.ui.Layout );

/* Events */

/**
 * @event set
 * @param {OO.ui.PageLayout} page Current page
 */

/**
 * @event add
 * @param {OO.ui.PageLayout[]} page Added pages
 * @param {number} index Index pages were added at
 */

/**
 * @event remove
 * @param {OO.ui.PageLayout[]} pages Removed pages
 */

/* Methods */

/**
 * Handle stack layout focus.
 *
 * @param {jQuery.Event} e Focusin event
 */
OO.ui.BookletLayout.prototype.onStackLayoutFocus = function ( e ) {
	var name, $target;

	if ( this.ignoreFocus ) {
		// Avoid recursion from programmatic focus trigger in #onStackLayoutSet
		return;
	}

	$target = $( e.target ).closest( '.oo-ui-pageLayout' );
	for ( name in this.pages ) {
		if ( this.pages[ name ].$element[0] === $target[0] ) {
			this.setPage( name );
			break;
		}
	}
};

/**
 * Handle stack layout set events.
 *
 * @param {OO.ui.PanelLayout|null} page The page panel that is now the current panel
 */
OO.ui.BookletLayout.prototype.onStackLayoutSet = function ( page ) {
	if ( page ) {
		this.stackLayout.$element.find( ':focus' ).blur();
		page.scrollElementIntoView( { 'complete': OO.ui.bind( function () {
			this.ignoreFocus = true;
			if ( this.autoFocus ) {
				page.$element.find( ':input:first' ).focus();
			}
			this.ignoreFocus = false;
		}, this ) } );
	}
};

/**
 * Handle outline widget select events.
 *
 * @param {OO.ui.OptionWidget|null} item Selected item
 */
OO.ui.BookletLayout.prototype.onOutlineWidgetSelect = function ( item ) {
	if ( item ) {
		this.setPage( item.getData() );
	}
};

/**
 * Check if booklet has an outline.
 *
 * @return {boolean}
 */
OO.ui.BookletLayout.prototype.isOutlined = function () {
	return this.outlined;
};

/**
 * Check if booklet has editing controls.
 *
 * @return {boolean}
 */
OO.ui.BookletLayout.prototype.isEditable = function () {
	return this.editable;
};

/**
 * Check if booklet has a visible outline.
 *
 * @return {boolean}
 */
OO.ui.BookletLayout.prototype.isOutlineVisible = function () {
	return this.outlined && this.outlineVisible;
};

/**
 * Hide or show the outline.
 *
 * @param {boolean} [show] Show outline, omit to invert current state
 * @chainable
 */
OO.ui.BookletLayout.prototype.toggleOutline = function ( show ) {
	if ( this.outlined ) {
		show = show === undefined ? !this.outlineVisible : !!show;
		this.outlineVisible = show;
		this.gridLayout.layout( show ? [ 1, 2 ] : [ 0, 1 ], [ 1 ] );
	}

	return this;
};

/**
 * Get the outline widget.
 *
 * @param {OO.ui.PageLayout} page Page to be selected
 * @return {OO.ui.PageLayout|null} Closest page to another
 */
OO.ui.BookletLayout.prototype.getClosestPage = function ( page ) {
	var next, prev, level,
		pages = this.stackLayout.getItems(),
		index = $.inArray( page, pages );

	if ( index !== -1 ) {
		next = pages[index + 1];
		prev = pages[index - 1];
		// Prefer adjacent pages at the same level
		if ( this.outlined ) {
			level = this.outlineWidget.getItemFromData( page.getName() ).getLevel();
			if (
				prev &&
				level === this.outlineWidget.getItemFromData( prev.getName() ).getLevel()
			) {
				return prev;
			}
			if (
				next &&
				level === this.outlineWidget.getItemFromData( next.getName() ).getLevel()
			) {
				return next;
			}
		}
	}
	return prev || next || null;
};

/**
 * Get the outline widget.
 *
 * @return {OO.ui.OutlineWidget|null} Outline widget, or null if boolet has no outline
 */
OO.ui.BookletLayout.prototype.getOutline = function () {
	return this.outlineWidget;
};

/**
 * Get the outline controls widget. If the outline is not editable, null is returned.
 *
 * @return {OO.ui.OutlineControlsWidget|null} The outline controls widget.
 */
OO.ui.BookletLayout.prototype.getOutlineControls = function () {
	return this.outlineControlsWidget;
};

/**
 * Get a page by name.
 *
 * @param {string} name Symbolic name of page
 * @return {OO.ui.PageLayout|undefined} Page, if found
 */
OO.ui.BookletLayout.prototype.getPage = function ( name ) {
	return this.pages[name];
};

/**
 * Get the current page name.
 *
 * @return {string|null} Current page name
 */
OO.ui.BookletLayout.prototype.getPageName = function () {
	return this.currentPageName;
};

/**
 * Add a page to the layout.
 *
 * When pages are added with the same names as existing pages, the existing pages will be
 * automatically removed before the new pages are added.
 *
 * @param {OO.ui.PageLayout[]} pages Pages to add
 * @param {number} index Index to insert pages after
 * @fires add
 * @chainable
 */
OO.ui.BookletLayout.prototype.addPages = function ( pages, index ) {
	var i, len, name, page, item, currentIndex,
		stackLayoutPages = this.stackLayout.getItems(),
		remove = [],
		items = [];

	// Remove pages with same names
	for ( i = 0, len = pages.length; i < len; i++ ) {
		page = pages[i];
		name = page.getName();

		if ( Object.prototype.hasOwnProperty.call( this.pages, name ) ) {
			// Correct the insertion index
			currentIndex = $.inArray( this.pages[name], stackLayoutPages );
			if ( currentIndex !== -1 && currentIndex + 1 < index ) {
				index--;
			}
			remove.push( this.pages[name] );
		}
	}
	if ( remove.length ) {
		this.removePages( remove );
	}

	// Add new pages
	for ( i = 0, len = pages.length; i < len; i++ ) {
		page = pages[i];
		name = page.getName();
		this.pages[page.getName()] = page;
		if ( this.outlined ) {
			item = new OO.ui.OutlineItemWidget( name, page, { '$': this.$ } );
			page.setOutlineItem( item );
			items.push( item );
		}
	}

	if ( this.outlined && items.length ) {
		this.outlineWidget.addItems( items, index );
		this.updateOutlineWidget();
	}
	this.stackLayout.addItems( pages, index );
	this.emit( 'add', pages, index );

	return this;
};

/**
 * Remove a page from the layout.
 *
 * @fires remove
 * @chainable
 */
OO.ui.BookletLayout.prototype.removePages = function ( pages ) {
	var i, len, name, page,
		items = [];

	for ( i = 0, len = pages.length; i < len; i++ ) {
		page = pages[i];
		name = page.getName();
		delete this.pages[name];
		if ( this.outlined ) {
			items.push( this.outlineWidget.getItemFromData( name ) );
			page.setOutlineItem( null );
		}
	}
	if ( this.outlined && items.length ) {
		this.outlineWidget.removeItems( items );
		this.updateOutlineWidget();
	}
	this.stackLayout.removeItems( pages );
	this.emit( 'remove', pages );

	return this;
};

/**
 * Clear all pages from the layout.
 *
 * @fires remove
 * @chainable
 */
OO.ui.BookletLayout.prototype.clearPages = function () {
	var i, len,
		pages = this.stackLayout.getItems();

	this.pages = {};
	this.currentPageName = null;
	if ( this.outlined ) {
		this.outlineWidget.clearItems();
		for ( i = 0, len = pages.length; i < len; i++ ) {
			pages[i].setOutlineItem( null );
		}
	}
	this.stackLayout.clearItems();

	this.emit( 'remove', pages );

	return this;
};

/**
 * Set the current page by name.
 *
 * @fires set
 * @param {string} name Symbolic name of page
 */
OO.ui.BookletLayout.prototype.setPage = function ( name ) {
	var selectedItem,
		page = this.pages[name];

	if ( name !== this.currentPageName ) {
		if ( this.outlined ) {
			selectedItem = this.outlineWidget.getSelectedItem();
			if ( selectedItem && selectedItem.getData() !== name ) {
				this.outlineWidget.selectItem( this.outlineWidget.getItemFromData( name ) );
			}
		}
		if ( page ) {
			if ( this.currentPageName && this.pages[this.currentPageName] ) {
				this.pages[this.currentPageName].setActive( false );
			}
			this.currentPageName = name;
			this.stackLayout.setItem( page );
			page.setActive( true );
			this.emit( 'set', page );
		}
	}
};

/**
 * Call this after adding or removing items from the OutlineWidget.
 *
 * @chainable
 */
OO.ui.BookletLayout.prototype.updateOutlineWidget = function () {
	// Auto-select first item when nothing is selected anymore
	if ( !this.outlineWidget.getSelectedItem() ) {
		this.outlineWidget.selectItem( this.outlineWidget.getFirstSelectableItem() );
	}

	return this;
};
/**
 * Layout that expands to cover the entire area of its parent, with optional scrolling and padding.
 *
 * @class
 * @extends OO.ui.Layout
 *
 * @constructor
 * @param {Object} [config] Configuration options
 * @cfg {boolean} [scrollable] Allow vertical scrolling
 * @cfg {boolean} [padded] Pad the content from the edges
 */
OO.ui.PanelLayout = function OoUiPanelLayout( config ) {
	// Config initialization
	config = config || {};

	// Parent constructor
	OO.ui.PanelLayout.super.call( this, config );

	// Initialization
	this.$element.addClass( 'oo-ui-panelLayout' );
	if ( config.scrollable ) {
		this.$element.addClass( 'oo-ui-panelLayout-scrollable' );
	}

	if ( config.padded ) {
		this.$element.addClass( 'oo-ui-panelLayout-padded' );
	}

	// Add directionality class:
	this.$element.addClass( 'oo-ui-' + OO.ui.Element.getDir( this.$.context ) );
};

/* Setup */

OO.inheritClass( OO.ui.PanelLayout, OO.ui.Layout );
/**
 * Page within an booklet layout.
 *
 * @class
 * @extends OO.ui.PanelLayout
 *
 * @constructor
 * @param {string} name Unique symbolic name of page
 * @param {Object} [config] Configuration options
 * @param {string} [outlineItem] Outline item widget
 */
OO.ui.PageLayout = function OoUiPageLayout( name, config ) {
	// Configuration initialization
	config = $.extend( { 'scrollable': true }, config );

	// Parent constructor
	OO.ui.PageLayout.super.call( this, config );

	// Properties
	this.name = name;
	this.outlineItem = config.outlineItem || null;
	this.active = false;

	// Initialization
	this.$element.addClass( 'oo-ui-pageLayout' );
};

/* Setup */

OO.inheritClass( OO.ui.PageLayout, OO.ui.PanelLayout );

/* Events */

/**
 * @event active
 * @param {boolean} active Page is active
 */

/* Methods */

/**
 * Get page name.
 *
 * @return {string} Symbolic name of page
 */
OO.ui.PageLayout.prototype.getName = function () {
	return this.name;
};

/**
 * Check if page is active.
 *
 * @return {boolean} Page is active
 */
OO.ui.PageLayout.prototype.isActive = function () {
	return this.active;
};

/**
 * Get outline item.
 *
 * @return {OO.ui.OutlineItemWidget|null} Outline item widget
 */
OO.ui.PageLayout.prototype.getOutlineItem = function () {
	return this.outlineItem;
};

/**
 * Get outline item.
 *
 * @param {OO.ui.OutlineItemWidget|null} outlineItem Outline item widget, null to clear
 * @chainable
 */
OO.ui.PageLayout.prototype.setOutlineItem = function ( outlineItem ) {
	this.outlineItem = outlineItem;
	return this;
};

/**
 * Set page active state.
 *
 * @param {boolean} Page is active
 * @fires active
 */
OO.ui.PageLayout.prototype.setActive = function ( active ) {
	active = !!active;

	if ( active !== this.active ) {
		this.active = active;
		this.$element.toggleClass( 'oo-ui-pageLayout-active', active );
		this.emit( 'active', this.active );
	}
};
/**
 * Layout containing a series of mutually exclusive pages.
 *
 * @class
 * @extends OO.ui.PanelLayout
 * @mixins OO.ui.GroupElement
 *
 * @constructor
 * @param {Object} [config] Configuration options
 * @cfg {boolean} [continuous=false] Show all pages, one after another
 * @cfg {string} [icon=''] Symbolic icon name
 * @cfg {OO.ui.Layout[]} [items] Layouts to add
 */
OO.ui.StackLayout = function OoUiStackLayout( config ) {
	// Config initialization
	config = $.extend( { 'scrollable': true }, config );

	// Parent constructor
	OO.ui.StackLayout.super.call( this, config );

	// Mixin constructors
	OO.ui.GroupElement.call( this, this.$element, config );

	// Properties
	this.currentItem = null;
	this.continuous = !!config.continuous;

	// Initialization
	this.$element.addClass( 'oo-ui-stackLayout' );
	if ( this.continuous ) {
		this.$element.addClass( 'oo-ui-stackLayout-continuous' );
	}
	if ( $.isArray( config.items ) ) {
		this.addItems( config.items );
	}
};

/* Setup */

OO.inheritClass( OO.ui.StackLayout, OO.ui.PanelLayout );
OO.mixinClass( OO.ui.StackLayout, OO.ui.GroupElement );

/* Events */

/**
 * @event set
 * @param {OO.ui.PanelLayout|null} [item] Current item
 */

/* Methods */

/**
 * Add items.
 *
 * Adding an existing item (by value) will move it.
 *
 * @param {OO.ui.PanelLayout[]} items Items to add
 * @param {number} [index] Index to insert items after
 * @chainable
 */
OO.ui.StackLayout.prototype.addItems = function ( items, index ) {
	OO.ui.GroupElement.prototype.addItems.call( this, items, index );

	if ( !this.currentItem && items.length ) {
		this.setItem( items[0] );
	}

	return this;
};

/**
 * Remove items.
 *
 * Items will be detached, not removed, so they can be used later.
 *
 * @param {OO.ui.PanelLayout[]} items Items to remove
 * @chainable
 */
OO.ui.StackLayout.prototype.removeItems = function ( items ) {
	OO.ui.GroupElement.prototype.removeItems.call( this, items );
	if ( $.inArray( this.currentItem, items  ) !== -1 ) {
		this.currentItem = null;
		if ( !this.currentItem && this.items.length ) {
			this.setItem( this.items[0] );
		}
	}

	return this;
};

/**
 * Clear all items.
 *
 * Items will be detached, not removed, so they can be used later.
 *
 * @chainable
 */
OO.ui.StackLayout.prototype.clearItems = function () {
	this.currentItem = null;
	OO.ui.GroupElement.prototype.clearItems.call( this );

	return this;
};

/**
 * Show item.
 *
 * Any currently shown item will be hidden.
 *
 * @param {OO.ui.PanelLayout} item Item to show
 * @chainable
 */
OO.ui.StackLayout.prototype.setItem = function ( item ) {
	if ( item !== this.currentItem ) {
		if ( !this.continuous ) {
			this.$items.css( 'display', '' );
		}
		if ( $.inArray( item, this.items ) !== -1 ) {
			if ( !this.continuous ) {
				item.$element.css( 'display', 'block' );
			}
		} else {
			item = null;
		}
		this.currentItem = item;
		this.emit( 'set', item );
	}

	return this;
};
/**
 * Horizontal bar layout of tools as icon buttons.
 *
 * @abstract
 * @class
 * @extends OO.ui.ToolGroup
 *
 * @constructor
 * @param {OO.ui.Toolbar} toolbar
 * @param {Object} [config] Configuration options
 */
OO.ui.BarToolGroup = function OoUiBarToolGroup( toolbar, config ) {
	// Parent constructor
	OO.ui.BarToolGroup.super.call( this, toolbar, config );

	// Initialization
	this.$element.addClass( 'oo-ui-barToolGroup' );
};

/* Setup */

OO.inheritClass( OO.ui.BarToolGroup, OO.ui.ToolGroup );

/* Static Properties */

OO.ui.BarToolGroup.static.titleTooltips = true;

OO.ui.BarToolGroup.static.accelTooltips = true;

OO.ui.BarToolGroup.static.name = 'bar';
/**
 * Popup list of tools with an icon and optional label.
 *
 * @abstract
 * @class
 * @extends OO.ui.ToolGroup
 * @mixins OO.ui.IconedElement
 * @mixins OO.ui.IndicatedElement
 * @mixins OO.ui.LabeledElement
 * @mixins OO.ui.TitledElement
 * @mixins OO.ui.ClippableElement
 *
 * @constructor
 * @param {OO.ui.Toolbar} toolbar
 * @param {Object} [config] Configuration options
 */
OO.ui.PopupToolGroup = function OoUiPopupToolGroup( toolbar, config ) {
	// Configuration initialization
	config = config || {};

	// Parent constructor
	OO.ui.PopupToolGroup.super.call( this, toolbar, config );

	// Mixin constructors
	OO.ui.IconedElement.call( this, this.$( '<span>' ), config );
	OO.ui.IndicatedElement.call( this, this.$( '<span>' ), config );
	OO.ui.LabeledElement.call( this, this.$( '<span>' ), config );
	OO.ui.TitledElement.call( this, this.$element, config );
	OO.ui.ClippableElement.call( this, this.$group, config );

	// Properties
	this.active = false;
	this.dragging = false;
	this.onBlurHandler = OO.ui.bind( this.onBlur, this );
	this.$handle = this.$( '<span>' );

	// Events
	this.$handle.on( {
		'mousedown': OO.ui.bind( this.onHandleMouseDown, this ),
		'mouseup': OO.ui.bind( this.onHandleMouseUp, this )
	} );

	// Initialization
	this.$handle
		.addClass( 'oo-ui-popupToolGroup-handle' )
		.append( this.$icon, this.$label, this.$indicator );
	this.$element
		.addClass( 'oo-ui-popupToolGroup' )
		.prepend( this.$handle );
};

/* Setup */

OO.inheritClass( OO.ui.PopupToolGroup, OO.ui.ToolGroup );
OO.mixinClass( OO.ui.PopupToolGroup, OO.ui.IconedElement );
OO.mixinClass( OO.ui.PopupToolGroup, OO.ui.IndicatedElement );
OO.mixinClass( OO.ui.PopupToolGroup, OO.ui.LabeledElement );
OO.mixinClass( OO.ui.PopupToolGroup, OO.ui.TitledElement );
OO.mixinClass( OO.ui.PopupToolGroup, OO.ui.ClippableElement );

/* Static Properties */

/* Methods */

/**
 * @inheritdoc
 */
OO.ui.PopupToolGroup.prototype.setDisabled = function () {
	// Parent method
	OO.ui.PopupToolGroup.super.prototype.setDisabled.apply( this, arguments );

	if ( this.isDisabled() && this.isElementAttached() ) {
		this.setActive( false );
	}
};

/**
 * Handle focus being lost.
 *
 * The event is actually generated from a mouseup, so it is not a normal blur event object.
 *
 * @param {jQuery.Event} e Mouse up event
 */
OO.ui.PopupToolGroup.prototype.onBlur = function ( e ) {
	// Only deactivate when clicking outside the dropdown element
	if ( this.$( e.target ).closest( '.oo-ui-popupToolGroup' )[0] !== this.$element[0] ) {
		this.setActive( false );
	}
};

/**
 * @inheritdoc
 */
OO.ui.PopupToolGroup.prototype.onMouseUp = function ( e ) {
	if ( !this.disabled && e.which === 1 ) {
		this.setActive( false );
	}
	return OO.ui.ToolGroup.prototype.onMouseUp.call( this, e );
};

/**
 * Handle mouse up events.
 *
 * @param {jQuery.Event} e Mouse up event
 */
OO.ui.PopupToolGroup.prototype.onHandleMouseUp = function () {
	return false;
};

/**
 * Handle mouse down events.
 *
 * @param {jQuery.Event} e Mouse down event
 */
OO.ui.PopupToolGroup.prototype.onHandleMouseDown = function ( e ) {
	if ( !this.disabled && e.which === 1 ) {
		this.setActive( !this.active );
	}
	return false;
};

/**
 * Switch into active mode.
 *
 * When active, mouseup events anywhere in the document will trigger deactivation.
 */
OO.ui.PopupToolGroup.prototype.setActive = function ( value ) {
	value = !!value;
	if ( this.active !== value ) {
		this.active = value;
		if ( value ) {
			this.setClipping( true );
			this.$element.addClass( 'oo-ui-popupToolGroup-active' );
			this.getElementDocument().addEventListener( 'mouseup', this.onBlurHandler, true );
		} else {
			this.setClipping( false );
			this.$element.removeClass( 'oo-ui-popupToolGroup-active' );
			this.getElementDocument().removeEventListener( 'mouseup', this.onBlurHandler, true );
		}
	}
};
/**
 * Drop down list layout of tools as labeled icon buttons.
 *
 * @abstract
 * @class
 * @extends OO.ui.PopupToolGroup
 *
 * @constructor
 * @param {OO.ui.Toolbar} toolbar
 * @param {Object} [config] Configuration options
 */
OO.ui.ListToolGroup = function OoUiListToolGroup( toolbar, config ) {
	// Parent constructor
	OO.ui.ListToolGroup.super.call( this, toolbar, config );

	// Initialization
	this.$element.addClass( 'oo-ui-listToolGroup' );
};

/* Setup */

OO.inheritClass( OO.ui.ListToolGroup, OO.ui.PopupToolGroup );

/* Static Properties */

OO.ui.ListToolGroup.static.accelTooltips = true;

OO.ui.ListToolGroup.static.name = 'list';
/**
 * Drop down menu layout of tools as selectable menu items.
 *
 * @abstract
 * @class
 * @extends OO.ui.PopupToolGroup
 *
 * @constructor
 * @param {OO.ui.Toolbar} toolbar
 * @param {Object} [config] Configuration options
 */
OO.ui.MenuToolGroup = function OoUiMenuToolGroup( toolbar, config ) {
	// Configuration initialization
	config = config || {};

	// Parent constructor
	OO.ui.MenuToolGroup.super.call( this, toolbar, config );

	// Events
	this.toolbar.connect( this, { 'updateState': 'onUpdateState' } );

	// Initialization
	this.$element.addClass( 'oo-ui-menuToolGroup' );
};

/* Setup */

OO.inheritClass( OO.ui.MenuToolGroup, OO.ui.PopupToolGroup );

/* Static Properties */

OO.ui.MenuToolGroup.static.accelTooltips = true;

OO.ui.MenuToolGroup.static.name = 'menu';

/* Methods */

/**
 * Handle the toolbar state being updated.
 *
 * When the state changes, the title of each active item in the menu will be joined together and
 * used as a label for the group. The label will be empty if none of the items are active.
 */
OO.ui.MenuToolGroup.prototype.onUpdateState = function () {
	var name,
		labelTexts = [];

	for ( name in this.tools ) {
		if ( this.tools[name].isActive() ) {
			labelTexts.push( this.tools[name].getTitle() );
		}
	}

	this.setLabel( labelTexts.join( ', ' ) || ' ' );
};
/**
 * Tool that shows a popup when selected.
 *
 * @abstract
 * @class
 * @extends OO.ui.Tool
 * @mixins OO.ui.PopuppableElement
 *
 * @constructor
 * @param {OO.ui.Toolbar} toolbar
 * @param {Object} [config] Configuration options
 */
OO.ui.PopupTool = function OoUiPopupTool( toolbar, config ) {
	// Parent constructor
	OO.ui.PopupTool.super.call( this, toolbar, config );

	// Mixin constructors
	OO.ui.PopuppableElement.call( this, config );

	// Initialization
	this.$element
		.addClass( 'oo-ui-popupTool' )
		.append( this.popup.$element );
};

/* Setup */

OO.inheritClass( OO.ui.PopupTool, OO.ui.Tool );
OO.mixinClass( OO.ui.PopupTool, OO.ui.PopuppableElement );

/* Methods */

/**
 * Handle the tool being selected.
 *
 * @inheritdoc
 */
OO.ui.PopupTool.prototype.onSelect = function () {
	if ( !this.disabled ) {
		if ( this.popup.isVisible() ) {
			this.hidePopup();
		} else {
			this.showPopup();
		}
	}
	this.setActive( false );
	return false;
};

/**
 * Handle the toolbar state being updated.
 *
 * @inheritdoc
 */
OO.ui.PopupTool.prototype.onUpdateState = function () {
	this.setActive( false );
};
/**
 * Group widget.
 *
 * Mixin for OO.ui.Widget subclasses.
 *
 * Use together with OO.ui.ItemWidget to make disabled state inheritable.
 *
 * @abstract
 * @class
 * @extends OO.ui.GroupElement
 *
 * @constructor
 * @param {jQuery} $group Container node, assigned to #$group
 * @param {Object} [config] Configuration options
 */
OO.ui.GroupWidget = function OoUiGroupWidget( $element, config ) {
	// Parent constructor
	OO.ui.GroupWidget.super.call( this, $element, config );
};

/* Setup */

OO.inheritClass( OO.ui.GroupWidget, OO.ui.GroupElement );

/* Methods */

/**
 * Set the disabled state of the widget.
 *
 * This will also update the disabled state of child widgets.
 *
 * @param {boolean} disabled Disable widget
 * @chainable
 */
OO.ui.GroupWidget.prototype.setDisabled = function ( disabled ) {
	var i, len;

	// Parent method
	// Note this is calling OO.ui.Widget; we're assuming the class this is mixed into
	// is a subclass of OO.ui.Widget.
	OO.ui.Widget.prototype.setDisabled.call( this, disabled );

	// During construction, #setDisabled is called before the OO.ui.GroupElement constructor
	if ( this.items ) {
		for ( i = 0, len = this.items.length; i < len; i++ ) {
			this.items[i].updateDisabled();
		}
	}

	return this;
};
/**
 * Item widget.
 *
 * Use together with OO.ui.GroupWidget to make disabled state inheritable.
 *
 * @abstract
 * @class
 *
 * @constructor
 */
OO.ui.ItemWidget = function OoUiItemWidget() {
	//
};

/* Methods */

/**
 * Check if widget is disabled.
 *
 * Checks parent if present, making disabled state inheritable.
 *
 * @return {boolean} Widget is disabled
 */
OO.ui.ItemWidget.prototype.isDisabled = function () {
	return this.disabled ||
		( this.elementGroup instanceof OO.ui.Widget && this.elementGroup.isDisabled() );
};

/**
 * Set group element is in.
 *
 * @param {OO.ui.GroupElement|null} group Group element, null if none
 * @chainable
 */
OO.ui.ItemWidget.prototype.setElementGroup = function ( group ) {
	// Parent method
	OO.ui.Element.prototype.setElementGroup.call( this, group );

	// Initialize item disabled states
	this.updateDisabled();

	return this;
};
/**
 * Icon widget.
 *
 * @class
 * @extends OO.ui.Widget
 * @mixins OO.ui.IconedElement
 * @mixins OO.ui.TitledElement
 *
 * @constructor
 * @param {Object} [config] Configuration options
 */
OO.ui.IconWidget = function OoUiIconWidget( config ) {
	// Config intialization
	config = config || {};

	// Parent constructor
	OO.ui.IconWidget.super.call( this, config );

	// Mixin constructors
	OO.ui.IconedElement.call( this, this.$element, config );
	OO.ui.TitledElement.call( this, this.$element, config );

	// Initialization
	this.$element.addClass( 'oo-ui-iconWidget' );
};

/* Setup */

OO.inheritClass( OO.ui.IconWidget, OO.ui.Widget );
OO.mixinClass( OO.ui.IconWidget, OO.ui.IconedElement );
OO.mixinClass( OO.ui.IconWidget, OO.ui.TitledElement );

/* Static Properties */

OO.ui.IconWidget.static.tagName = 'span';
/**
 * Indicator widget.
 *
 * @class
 * @extends OO.ui.Widget
 * @mixins OO.ui.IndicatedElement
 * @mixins OO.ui.TitledElement
 *
 * @constructor
 * @param {Object} [config] Configuration options
 */
OO.ui.IndicatorWidget = function OoUiIndicatorWidget( config ) {
	// Config intialization
	config = config || {};

	// Parent constructor
	OO.ui.IndicatorWidget.super.call( this, config );

	// Mixin constructors
	OO.ui.IndicatedElement.call( this, this.$element, config );
	OO.ui.TitledElement.call( this, this.$element, config );

	// Initialization
	this.$element.addClass( 'oo-ui-indicatorWidget' );
};

/* Setup */

OO.inheritClass( OO.ui.IndicatorWidget, OO.ui.Widget );
OO.mixinClass( OO.ui.IndicatorWidget, OO.ui.IndicatedElement );
OO.mixinClass( OO.ui.IndicatorWidget, OO.ui.TitledElement );

/* Static Properties */

OO.ui.IndicatorWidget.static.tagName = 'span';
/**
 * Container for multiple related buttons.
 *
 * Use together with OO.ui.ButtonWidget.
 *
 * @class
 * @extends OO.ui.Widget
 * @mixins OO.ui.GroupElement
 *
 * @constructor
 * @param {Object} [config] Configuration options
 * @cfg {OO.ui.ButtonWidget} [items] Buttons to add
 */
OO.ui.ButtonGroupWidget = function OoUiButtonGroupWidget( config ) {
	// Parent constructor
	OO.ui.ButtonGroupWidget.super.call( this, config );

	// Mixin constructors
	OO.ui.GroupElement.call( this, this.$element, config );

	// Initialization
	this.$element.addClass( 'oo-ui-buttonGroupWidget' );
	if ( $.isArray( config.items ) ) {
		this.addItems( config.items );
	}
};

/* Setup */

OO.inheritClass( OO.ui.ButtonGroupWidget, OO.ui.Widget );
OO.mixinClass( OO.ui.ButtonGroupWidget, OO.ui.GroupElement );
/**
 * Button widget.
 *
 * @abstract
 * @class
 * @extends OO.ui.Widget
 * @mixins OO.ui.ButtonedElement
 * @mixins OO.ui.IconedElement
 * @mixins OO.ui.IndicatedElement
 * @mixins OO.ui.LabeledElement
 * @mixins OO.ui.TitledElement
 * @mixins OO.ui.FlaggableElement
 *
 * @constructor
 * @param {Object} [config] Configuration options
 * @cfg {string} [title=''] Title text
 * @cfg {string} [href] Hyperlink to visit when clicked
 * @cfg {string} [target] Target to open hyperlink in
 */
OO.ui.ButtonWidget = function OoUiButtonWidget( config ) {
	// Configuration initialization
	config = $.extend( { 'target': '_blank' }, config );

	// Parent constructor
	OO.ui.ButtonWidget.super.call( this, config );

	// Mixin constructors
	OO.ui.ButtonedElement.call( this, this.$( '<a>' ), config );
	OO.ui.IconedElement.call( this, this.$( '<span>' ), config );
	OO.ui.IndicatedElement.call( this, this.$( '<span>' ), config );
	OO.ui.LabeledElement.call( this, this.$( '<span>' ), config );
	OO.ui.TitledElement.call( this, this.$button, config );
	OO.ui.FlaggableElement.call( this, config );

	// Properties
	this.isHyperlink = typeof config.href === 'string';

	// Events
	this.$button.on( {
		'click': OO.ui.bind( this.onClick, this ),
		'keypress': OO.ui.bind( this.onKeyPress, this )
	} );

	// Initialization
	this.$button
		.append( this.$icon, this.$label, this.$indicator )
		.attr( { 'href': config.href, 'target': config.target } );
	this.$element
		.addClass( 'oo-ui-buttonWidget' )
		.append( this.$button );
};

/* Setup */

OO.inheritClass( OO.ui.ButtonWidget, OO.ui.Widget );
OO.mixinClass( OO.ui.ButtonWidget, OO.ui.ButtonedElement );
OO.mixinClass( OO.ui.ButtonWidget, OO.ui.IconedElement );
OO.mixinClass( OO.ui.ButtonWidget, OO.ui.IndicatedElement );
OO.mixinClass( OO.ui.ButtonWidget, OO.ui.LabeledElement );
OO.mixinClass( OO.ui.ButtonWidget, OO.ui.TitledElement );
OO.mixinClass( OO.ui.ButtonWidget, OO.ui.FlaggableElement );

/* Events */

/**
 * @event click
 */

/* Methods */

/**
 * Handles mouse click events.
 *
 * @param {jQuery.Event} e Mouse click event
 * @fires click
 */
OO.ui.ButtonWidget.prototype.onClick = function () {
	if ( !this.disabled ) {
		this.emit( 'click' );
		if ( this.isHyperlink ) {
			return true;
		}
	}
	return false;
};

/**
 * Handles keypress events.
 *
 * @param {jQuery.Event} e Keypress event
 * @fires click
 */
OO.ui.ButtonWidget.prototype.onKeyPress = function ( e ) {
	if ( !this.disabled && e.which === OO.ui.Keys.SPACE ) {
		if ( this.isHyperlink ) {
			this.onClick();
			return true;
		}
	}
	return false;
};
/**
 * Input widget.
 *
 * @abstract
 * @class
 * @extends OO.ui.Widget
 *
 * @constructor
 * @param {Object} [config] Configuration options
 * @cfg {string} [name=''] HTML input name
 * @cfg {string} [value=''] Input value
 * @cfg {boolean} [readOnly=false] Prevent changes
 * @cfg {Function} [inputFilter] Filter function to apply to the input. Takes a string argument and returns a string.
 */
OO.ui.InputWidget = function OoUiInputWidget( config ) {
	// Config intialization
	config = $.extend( { 'readOnly': false }, config );

	// Parent constructor
	OO.ui.InputWidget.super.call( this, config );

	// Properties
	this.$input = this.getInputElement( config );
	this.value = '';
	this.readOnly = false;
	this.inputFilter = config.inputFilter;

	// Events
	this.$input.on( 'keydown mouseup cut paste change input select', OO.ui.bind( this.onEdit, this ) );

	// Initialization
	this.$input
		.attr( 'name', config.name )
		.prop( 'disabled', this.disabled );
	this.setReadOnly( config.readOnly );
	this.$element.addClass( 'oo-ui-inputWidget' ).append( this.$input );
	this.setValue( config.value );
};

/* Setup */

OO.inheritClass( OO.ui.InputWidget, OO.ui.Widget );

/* Events */

/**
 * @event change
 * @param value
 */

/* Methods */

/**
 * Get input element.
 *
 * @param {Object} [config] Configuration options
 * @return {jQuery} Input element
 */
OO.ui.InputWidget.prototype.getInputElement = function () {
	return this.$( '<input>' );
};

/**
 * Handle potentially value-changing events.
 *
 * @param {jQuery.Event} e Key down, mouse up, cut, paste, change, input, or select event
 */
OO.ui.InputWidget.prototype.onEdit = function () {
	if ( !this.disabled ) {
		// Allow the stack to clear so the value will be updated
		setTimeout( OO.ui.bind( function () {
			this.setValue( this.$input.val() );
		}, this ) );
	}
};

/**
 * Get the value of the input.
 *
 * @return {string} Input value
 */
OO.ui.InputWidget.prototype.getValue = function () {
	return this.value;
};

/**
 * Sets the direction of the current input, either RTL or LTR
 *
 * @param {boolean} isRTL
 */
OO.ui.InputWidget.prototype.setRTL = function ( isRTL ) {
	if ( isRTL ) {
		this.$input.removeClass( 'oo-ui-ltr' );
		this.$input.addClass( 'oo-ui-rtl' );
	} else {
		this.$input.removeClass( 'oo-ui-rtl' );
		this.$input.addClass( 'oo-ui-ltr' );
	}
};

/**
 * Set the value of the input.
 *
 * @param {string} value New value
 * @fires change
 * @chainable
 */
OO.ui.InputWidget.prototype.setValue = function ( value ) {
	value = this.sanitizeValue( value );
	if ( this.value !== value ) {
		this.value = value;
		this.emit( 'change', this.value );
	}
	// Update the DOM if it has changed. Note that with sanitizeValue, it
	// is possible for the DOM value to change without this.value changing.
	if ( this.$input.val() !== this.value ) {
		this.$input.val( this.value );
	}
	return this;
};

/**
 * Sanitize incoming value.
 *
 * Ensures value is a string, and converts undefined and null to empty strings.
 *
 * @param {string} value Original value
 * @return {string} Sanitized value
 */
OO.ui.InputWidget.prototype.sanitizeValue = function ( value ) {
	if ( value === undefined || value === null ) {
		return '';
	} else if ( this.inputFilter ) {
		return this.inputFilter( String( value ) );
	} else {
		return String( value );
	}
};

/**
 * Simulate the behavior of clicking on a label bound to this input.
 */
OO.ui.InputWidget.prototype.simulateLabelClick = function () {
	if ( !this.isDisabled() ) {
		if ( this.$input.is( ':checkbox,:radio' ) ) {
			this.$input.click();
		} else if ( this.$input.is( ':input' ) ) {
			this.$input.focus();
		}
	}
};

/**
 * Check if the widget is read-only.
 *
 * @return {boolean}
 */
OO.ui.InputWidget.prototype.isReadOnly = function () {
	return this.readOnly;
};

/**
 * Set the read-only state of the widget.
 *
 * This should probably change the widgets's appearance and prevent it from being used.
 *
 * @param {boolean} state Make input read-only
 * @chainable
 */
OO.ui.InputWidget.prototype.setReadOnly = function ( state ) {
	this.readOnly = !!state;
	this.$input.prop( 'readonly', this.readOnly );
	return this;
};

/**
 * @inheritdoc
 */
OO.ui.InputWidget.prototype.setDisabled = function ( state ) {
	OO.ui.Widget.prototype.setDisabled.call( this, state );
	if ( this.$input ) {
		this.$input.prop( 'disabled', this.disabled );
	}
	return this;
};
/**
 * Checkbox widget.
 *
 * @class
 * @extends OO.ui.InputWidget
 *
 * @constructor
 * @param {Object} [config] Configuration options
 */
OO.ui.CheckboxInputWidget = function OoUiCheckboxInputWidget( config ) {
	// Parent constructor
	OO.ui.CheckboxInputWidget.super.call( this, config );

	// Initialization
	this.$element.addClass( 'oo-ui-checkboxInputWidget' );
};

/* Setup */

OO.inheritClass( OO.ui.CheckboxInputWidget, OO.ui.InputWidget );

/* Events */

/* Methods */

/**
 * Get input element.
 *
 * @return {jQuery} Input element
 */
OO.ui.CheckboxInputWidget.prototype.getInputElement = function () {
	return this.$( '<input type="checkbox" />' );
};

/**
 * Get checked state of the checkbox
 *
 * @return {boolean} If the checkbox is checked
 */
OO.ui.CheckboxInputWidget.prototype.getValue = function () {
	return this.value;
};

/**
 * Set value
 */
OO.ui.CheckboxInputWidget.prototype.setValue = function ( value ) {
	value = !!value;
	if ( this.value !== value ) {
		this.value = value;
		this.$input.prop( 'checked', this.value );
		this.emit( 'change', this.value );
	}
};

/**
 * @inheritdoc
 */
OO.ui.CheckboxInputWidget.prototype.onEdit = function () {
	if ( !this.disabled ) {
		// Allow the stack to clear so the value will be updated
		setTimeout( OO.ui.bind( function () {
			this.setValue( this.$input.prop( 'checked' ) );
		}, this ) );
	}
};
/**
 * Label widget.
 *
 * @class
 * @extends OO.ui.Widget
 * @mixins OO.ui.LabeledElement
 *
 * @constructor
 * @param {Object} [config] Configuration options
 */
OO.ui.LabelWidget = function OoUiLabelWidget( config ) {
	// Config intialization
	config = config || {};

	// Parent constructor
	OO.ui.LabelWidget.super.call( this, config );

	// Mixin constructors
	OO.ui.LabeledElement.call( this, this.$element, config );

	// Properties
	this.input = config.input;

	// Events
	if ( this.input instanceof OO.ui.InputWidget ) {
		this.$element.on( 'click', OO.ui.bind( this.onClick, this ) );
	}

	// Initialization
	this.$element.addClass( 'oo-ui-labelWidget' );
};

/* Setup */

OO.inheritClass( OO.ui.LabelWidget, OO.ui.Widget );
OO.mixinClass( OO.ui.LabelWidget, OO.ui.LabeledElement );

/* Static Properties */

OO.ui.LabelWidget.static.tagName = 'label';

/* Methods */

/**
 * Handles label mouse click events.
 *
 * @param {jQuery.Event} e Mouse click event
 */
OO.ui.LabelWidget.prototype.onClick = function () {
	this.input.simulateLabelClick();
	return false;
};
/**
 * Lookup input widget.
 *
 * Mixin that adds a menu showing suggested values to a text input. Subclasses must handle `select`
 * and `choose` events on #lookupMenu to make use of selections.
 *
 * @class
 * @abstract
 *
 * @constructor
 * @param {OO.ui.TextInputWidget} input Input widget
 * @param {Object} [config] Configuration options
 * @cfg {jQuery} [$overlay=this.$( 'body' )] Overlay layer
 */
OO.ui.LookupInputWidget = function OoUiLookupInputWidget( input, config ) {
	// Config intialization
	config = config || {};

	// Properties
	this.lookupInput = input;
	this.$overlay = config.$overlay || this.$( 'body,.oo-ui-window-overlay' ).last();
	this.lookupMenu = new OO.ui.TextInputMenuWidget( this, {
		'$': OO.ui.Element.getJQuery( this.$overlay ),
		'input': this.lookupInput,
		'$container': config.$container
	} );
	this.lookupCache = {};
	this.lookupQuery = null;
	this.lookupRequest = null;
	this.populating = false;

	// Events
	this.$overlay.append( this.lookupMenu.$element );

	this.lookupInput.$input.on( {
		'focus': OO.ui.bind( this.onLookupInputFocus, this ),
		'blur': OO.ui.bind( this.onLookupInputBlur, this ),
		'mousedown': OO.ui.bind( this.onLookupInputMouseDown, this )
	} );
	this.lookupInput.connect( this, { 'change': 'onLookupInputChange' } );

	// Initialization
	this.$element.addClass( 'oo-ui-lookupWidget' );
	this.lookupMenu.$element.addClass( 'oo-ui-lookupWidget-menu' );
};

/* Methods */

/**
 * Handle input focus event.
 *
 * @param {jQuery.Event} e Input focus event
 */
OO.ui.LookupInputWidget.prototype.onLookupInputFocus = function () {
	this.openLookupMenu();
};

/**
 * Handle input blur event.
 *
 * @param {jQuery.Event} e Input blur event
 */
OO.ui.LookupInputWidget.prototype.onLookupInputBlur = function () {
	this.lookupMenu.hide();
};

/**
 * Handle input mouse down event.
 *
 * @param {jQuery.Event} e Input mouse down event
 */
OO.ui.LookupInputWidget.prototype.onLookupInputMouseDown = function () {
	this.openLookupMenu();
};

/**
 * Handle input change event.
 *
 * @param {string} value New input value
 */
OO.ui.LookupInputWidget.prototype.onLookupInputChange = function () {
	this.openLookupMenu();
};

/**
 * Open the menu.
 *
 * @chainable
 */
OO.ui.LookupInputWidget.prototype.openLookupMenu = function () {
	var value = this.lookupInput.getValue();

	if ( this.lookupMenu.$input.is( ':focus' ) && $.trim( value ) !== '' ) {
		this.populateLookupMenu();
		if ( !this.lookupMenu.isVisible() ) {
			this.lookupMenu.show();
		}
	} else {
		this.lookupMenu.clearItems();
		this.lookupMenu.hide();
	}

	return this;
};

/**
 * Populate lookup menu with current information.
 *
 * @chainable
 */
OO.ui.LookupInputWidget.prototype.populateLookupMenu = function () {
	if ( !this.populating ) {
		this.populating = true;
		this.getLookupMenuItems()
			.done( OO.ui.bind( function ( items ) {
				this.lookupMenu.clearItems();
				if ( items.length ) {
					this.lookupMenu.show();
					this.lookupMenu.addItems( items );
					this.initializeLookupMenuSelection();
					this.openLookupMenu();
				} else {
					this.lookupMenu.hide();
				}
				this.populating = false;
			}, this ) )
			.fail( OO.ui.bind( function () {
				this.lookupMenu.clearItems();
				this.populating = false;
			}, this ) );
	}

	return this;
};

/**
 * Set selection in the lookup menu with current information.
 *
 * @chainable
 */
OO.ui.LookupInputWidget.prototype.initializeLookupMenuSelection = function () {
	if ( !this.lookupMenu.getSelectedItem() ) {
		this.lookupMenu.selectItem( this.lookupMenu.getFirstSelectableItem() );
	}
	this.lookupMenu.highlightItem( this.lookupMenu.getSelectedItem() );
};

/**
 * Get lookup menu items for the current query.
 *
 * @return {jQuery.Promise} Promise object which will be passed menu items as the first argument
 * of the done event
 */
OO.ui.LookupInputWidget.prototype.getLookupMenuItems = function () {
	var value = this.lookupInput.getValue(),
		deferred = $.Deferred();

	if ( value && value !== this.lookupQuery ) {
		// Abort current request if query has changed
		if ( this.lookupRequest ) {
			this.lookupRequest.abort();
			this.lookupQuery = null;
			this.lookupRequest = null;
		}
		if ( value in this.lookupCache ) {
			deferred.resolve( this.getLookupMenuItemsFromData( this.lookupCache[value] ) );
		} else {
			this.lookupQuery = value;
			this.lookupRequest = this.getLookupRequest()
				.always( OO.ui.bind( function () {
					this.lookupQuery = null;
					this.lookupRequest = null;
				}, this ) )
				.done( OO.ui.bind( function ( data ) {
					this.lookupCache[value] = this.getLookupCacheItemFromData( data );
					deferred.resolve( this.getLookupMenuItemsFromData( this.lookupCache[value] ) );
				}, this ) )
				.fail( function () {
					deferred.reject();
				} );
			this.pushPending();
			this.lookupRequest.always( OO.ui.bind( function () {
				this.popPending();
			}, this ) );
		}
	}
	return deferred.promise();
};

/**
 * Get a new request object of the current lookup query value.
 *
 * @abstract
 * @return {jqXHR} jQuery AJAX object, or promise object with an .abort() method
 */
OO.ui.LookupInputWidget.prototype.getLookupRequest = function () {
	// Stub, implemented in subclass
	return null;
};

/**
 * Handle successful lookup request.
 *
 * Overriding methods should call #populateLookupMenu when results are available and cache results
 * for future lookups in #lookupCache as an array of #OO.ui.MenuItemWidget objects.
 *
 * @abstract
 * @param {Mixed} data Response from server
 */
OO.ui.LookupInputWidget.prototype.onLookupRequestDone = function () {
	// Stub, implemented in subclass
};

/**
 * Get a list of menu item widgets from the data stored by the lookup request's done handler.
 *
 * @abstract
 * @param {Mixed} data Cached result data, usually an array
 * @return {OO.ui.MenuItemWidget[]} Menu items
 */
OO.ui.LookupInputWidget.prototype.getLookupMenuItemsFromData = function () {
	// Stub, implemented in subclass
	return [];
};
/**
 * Option widget.
 *
 * Use with OO.ui.SelectWidget.
 *
 * @abstract
 * @class
 * @extends OO.ui.Widget
 * @mixins OO.ui.IconedElement
 * @mixins OO.ui.LabeledElement
 * @mixins OO.ui.IndicatedElement
 * @mixins OO.ui.FlaggableElement
 *
 * @constructor
 * @param {Mixed} data Option data
 * @param {Object} [config] Configuration options
 * @cfg {string} [rel] Value for `rel` attribute in DOM, allowing per-option styling
 */
OO.ui.OptionWidget = function OoUiOptionWidget( data, config ) {
	// Config intialization
	config = config || {};

	// Parent constructor
	OO.ui.OptionWidget.super.call( this, config );

	// Mixin constructors
	OO.ui.ItemWidget.call( this );
	OO.ui.IconedElement.call( this, this.$( '<span>' ), config );
	OO.ui.LabeledElement.call( this, this.$( '<span>' ), config );
	OO.ui.IndicatedElement.call( this, this.$( '<span>' ), config );
	OO.ui.FlaggableElement.call( this, config );

	// Properties
	this.data = data;
	this.selected = false;
	this.highlighted = false;
	this.pressed = false;

	// Initialization
	this.$element
		.data( 'oo-ui-optionWidget', this )
		.attr( 'rel', config.rel )
		.addClass( 'oo-ui-optionWidget' )
		.append( this.$label );
	this.$element
		.prepend( this.$icon )
		.append( this.$indicator );
};

/* Setup */

OO.inheritClass( OO.ui.OptionWidget, OO.ui.Widget );
OO.mixinClass( OO.ui.OptionWidget, OO.ui.ItemWidget );
OO.mixinClass( OO.ui.OptionWidget, OO.ui.IconedElement );
OO.mixinClass( OO.ui.OptionWidget, OO.ui.LabeledElement );
OO.mixinClass( OO.ui.OptionWidget, OO.ui.IndicatedElement );
OO.mixinClass( OO.ui.OptionWidget, OO.ui.FlaggableElement );

/* Static Properties */

OO.ui.OptionWidget.static.tagName = 'li';

OO.ui.OptionWidget.static.selectable = true;

OO.ui.OptionWidget.static.highlightable = true;

OO.ui.OptionWidget.static.pressable = true;

OO.ui.OptionWidget.static.scrollIntoViewOnSelect = false;

/* Methods */

/**
 * Check if option can be selected.
 *
 * @return {boolean} Item is selectable
 */
OO.ui.OptionWidget.prototype.isSelectable = function () {
	return this.constructor.static.selectable && !this.disabled;
};

/**
 * Check if option can be highlighted.
 *
 * @return {boolean} Item is highlightable
 */
OO.ui.OptionWidget.prototype.isHighlightable = function () {
	return this.constructor.static.highlightable && !this.disabled;
};

/**
 * Check if option can be pressed.
 *
 * @return {boolean} Item is pressable
 */
OO.ui.OptionWidget.prototype.isPressable = function () {
	return this.constructor.static.pressable && !this.disabled;
};

/**
 * Check if option is selected.
 *
 * @return {boolean} Item is selected
 */
OO.ui.OptionWidget.prototype.isSelected = function () {
	return this.selected;
};

/**
 * Check if option is highlighted.
 *
 * @return {boolean} Item is highlighted
 */
OO.ui.OptionWidget.prototype.isHighlighted = function () {
	return this.highlighted;
};

/**
 * Check if option is pressed.
 *
 * @return {boolean} Item is pressed
 */
OO.ui.OptionWidget.prototype.isPressed = function () {
	return this.pressed;
};

/**
 * Set selected state.
 *
 * @param {boolean} [state=false] Select option
 * @chainable
 */
OO.ui.OptionWidget.prototype.setSelected = function ( state ) {
	if ( !this.disabled && this.constructor.static.selectable ) {
		this.selected = !!state;
		if ( this.selected ) {
			this.$element.addClass( 'oo-ui-optionWidget-selected' );
			if ( this.constructor.static.scrollIntoViewOnSelect ) {
				this.scrollElementIntoView();
			}
		} else {
			this.$element.removeClass( 'oo-ui-optionWidget-selected' );
		}
	}
	return this;
};

/**
 * Set highlighted state.
 *
 * @param {boolean} [state=false] Highlight option
 * @chainable
 */
OO.ui.OptionWidget.prototype.setHighlighted = function ( state ) {
	if ( !this.disabled && this.constructor.static.highlightable ) {
		this.highlighted = !!state;
		if ( this.highlighted ) {
			this.$element.addClass( 'oo-ui-optionWidget-highlighted' );
		} else {
			this.$element.removeClass( 'oo-ui-optionWidget-highlighted' );
		}
	}
	return this;
};

/**
 * Set pressed state.
 *
 * @param {boolean} [state=false] Press option
 * @chainable
 */
OO.ui.OptionWidget.prototype.setPressed = function ( state ) {
	if ( !this.disabled && this.constructor.static.pressable ) {
		this.pressed = !!state;
		if ( this.pressed ) {
			this.$element.addClass( 'oo-ui-optionWidget-pressed' );
		} else {
			this.$element.removeClass( 'oo-ui-optionWidget-pressed' );
		}
	}
	return this;
};

/**
 * Make the option's highlight flash.
 *
 * While flashing, the visual style of the pressed state is removed if present.
 *
 * @param {Function} [done] Callback to execute when flash effect is complete.
 */
OO.ui.OptionWidget.prototype.flash = function ( done ) {
	var $this = this.$element;

	if ( !this.disabled && this.constructor.static.pressable ) {
		$this.removeClass( 'oo-ui-optionWidget-highlighted oo-ui-optionWidget-pressed' );
		setTimeout( OO.ui.bind( function () {
			$this.addClass( 'oo-ui-optionWidget-highlighted' );
			if ( done ) {
				// Restore original classes
				$this
					.toggleClass( 'oo-ui-optionWidget-highlighted', this.highlighted )
					.toggleClass( 'oo-ui-optionWidget-pressed', this.pressed );
				setTimeout( done, 100 );
			}
		}, this ), 100 );
	}
};

/**
 * Get option data.
 *
 * @return {Mixed} Option data
 */
OO.ui.OptionWidget.prototype.getData = function () {
	return this.data;
};
/**
 * Selection of options.
 *
 * Use together with OO.ui.OptionWidget.
 *
 * @abstract
 * @class
 * @extends OO.ui.Widget
 * @mixins OO.ui.GroupElement
 *
 * @constructor
 * @param {Object} [config] Configuration options
 * @cfg {OO.ui.OptionWidget[]} [items] Options to add
 */
OO.ui.SelectWidget = function OoUiSelectWidget( config ) {
	// Config intialization
	config = config || {};

	// Parent constructor
	OO.ui.SelectWidget.super.call( this, config );

	// Mixin constructors
	OO.ui.GroupWidget.call( this, this.$element, config );

	// Properties
	this.pressed = false;
	this.selecting = null;
	this.hashes = {};

	// Events
	this.$element.on( {
		'mousedown': OO.ui.bind( this.onMouseDown, this ),
		'mouseup': OO.ui.bind( this.onMouseUp, this ),
		'mousemove': OO.ui.bind( this.onMouseMove, this ),
		'mouseover': OO.ui.bind( this.onMouseOver, this ),
		'mouseleave': OO.ui.bind( this.onMouseLeave, this )
	} );

	// Initialization
	this.$element.addClass( 'oo-ui-selectWidget oo-ui-selectWidget-depressed' );
	if ( $.isArray( config.items ) ) {
		this.addItems( config.items );
	}
};

/* Setup */

OO.inheritClass( OO.ui.SelectWidget, OO.ui.Widget );

// Need to mixin base class as well
OO.mixinClass( OO.ui.SelectWidget, OO.ui.GroupElement );
OO.mixinClass( OO.ui.SelectWidget, OO.ui.GroupWidget );

/* Events */

/**
 * @event highlight
 * @param {OO.ui.OptionWidget|null} item Highlighted item
 */

/**
 * @event press
 * @param {OO.ui.OptionWidget|null} item Pressed item
 */

/**
 * @event select
 * @param {OO.ui.OptionWidget|null} item Selected item
 */

/**
 * @event choose
 * @param {OO.ui.OptionWidget|null} item Chosen item
 */

/**
 * @event add
 * @param {OO.ui.OptionWidget[]} items Added items
 * @param {number} index Index items were added at
 */

/**
 * @event remove
 * @param {OO.ui.OptionWidget[]} items Removed items
 */

/* Static Properties */

OO.ui.SelectWidget.static.tagName = 'ul';

/* Methods */

/**
 * Handle mouse down events.
 *
 * @private
 * @param {jQuery.Event} e Mouse down event
 */
OO.ui.SelectWidget.prototype.onMouseDown = function ( e ) {
	var item;

	if ( !this.disabled && e.which === 1 ) {
		this.togglePressed( true );
		item = this.getTargetItem( e );
		if ( item && item.isSelectable() ) {
			this.pressItem( item );
			this.selecting = item;
			this.$( this.$.context ).one( 'mouseup', OO.ui.bind( this.onMouseUp, this ) );
		}
	}
	return false;
};

/**
 * Handle mouse up events.
 *
 * @private
 * @param {jQuery.Event} e Mouse up event
 */
OO.ui.SelectWidget.prototype.onMouseUp = function ( e ) {
	var item;

	this.togglePressed( false );
	if ( !this.selecting ) {
		item = this.getTargetItem( e );
		if ( item && item.isSelectable() ) {
			this.selecting = item;
		}
	}
	if ( !this.disabled && e.which === 1 && this.selecting ) {
		this.pressItem( null );
		this.chooseItem( this.selecting );
		this.selecting = null;
	}

	return false;
};

/**
 * Handle mouse move events.
 *
 * @private
 * @param {jQuery.Event} e Mouse move event
 */
OO.ui.SelectWidget.prototype.onMouseMove = function ( e ) {
	var item;

	if ( !this.disabled && this.pressed ) {
		item = this.getTargetItem( e );
		if ( item && item !== this.selecting && item.isSelectable() ) {
			this.pressItem( item );
			this.selecting = item;
		}
	}
	return false;
};

/**
 * Handle mouse over events.
 *
 * @private
 * @param {jQuery.Event} e Mouse over event
 */
OO.ui.SelectWidget.prototype.onMouseOver = function ( e ) {
	var item;

	if ( !this.disabled ) {
		item = this.getTargetItem( e );
		this.highlightItem( item && item.isHighlightable() ? item : null );
	}
	return false;
};

/**
 * Handle mouse leave events.
 *
 * @private
 * @param {jQuery.Event} e Mouse over event
 */
OO.ui.SelectWidget.prototype.onMouseLeave = function () {
	if ( !this.disabled ) {
		this.highlightItem( null );
	}
	return false;
};

/**
 * Get the closest item to a jQuery.Event.
 *
 * @private
 * @param {jQuery.Event} e
 * @return {OO.ui.OptionWidget|null} Outline item widget, `null` if none was found
 */
OO.ui.SelectWidget.prototype.getTargetItem = function ( e ) {
	var $item = this.$( e.target ).closest( '.oo-ui-optionWidget' );
	if ( $item.length ) {
		return $item.data( 'oo-ui-optionWidget' );
	}
	return null;
};

/**
 * Get selected item.
 *
 * @return {OO.ui.OptionWidget|null} Selected item, `null` if no item is selected
 */
OO.ui.SelectWidget.prototype.getSelectedItem = function () {
	var i, len;

	for ( i = 0, len = this.items.length; i < len; i++ ) {
		if ( this.items[i].isSelected() ) {
			return this.items[i];
		}
	}
	return null;
};

/**
 * Get highlighted item.
 *
 * @return {OO.ui.OptionWidget|null} Highlighted item, `null` if no item is highlighted
 */
OO.ui.SelectWidget.prototype.getHighlightedItem = function () {
	var i, len;

	for ( i = 0, len = this.items.length; i < len; i++ ) {
		if ( this.items[i].isHighlighted() ) {
			return this.items[i];
		}
	}
	return null;
};

/**
 * Get an existing item with equivilant data.
 *
 * @param {Object} data Item data to search for
 * @return {OO.ui.OptionWidget|null} Item with equivilent value, `null` if none exists
 */
OO.ui.SelectWidget.prototype.getItemFromData = function ( data ) {
	var hash = OO.getHash( data );

	if ( hash in this.hashes ) {
		return this.hashes[hash];
	}

	return null;
};

/**
 * Toggle pressed state.
 *
 * @param {boolean} pressed An option is being pressed
 */
OO.ui.SelectWidget.prototype.togglePressed = function ( pressed ) {
	if ( pressed === undefined ) {
		pressed = !this.pressed;
	}
	if ( pressed !== this.pressed ) {
		this.$element.toggleClass( 'oo-ui-selectWidget-pressed', pressed );
		this.$element.toggleClass( 'oo-ui-selectWidget-depressed', !pressed );
		this.pressed = pressed;
	}
};

/**
 * Highlight an item.
 *
 * Highlighting is mutually exclusive.
 *
 * @param {OO.ui.OptionWidget} [item] Item to highlight, omit to deselect all
 * @fires highlight
 * @chainable
 */
OO.ui.SelectWidget.prototype.highlightItem = function ( item ) {
	var i, len, highlighted,
		changed = false;

	for ( i = 0, len = this.items.length; i < len; i++ ) {
		highlighted = this.items[i] === item;
		if ( this.items[i].isHighlighted() !== highlighted ) {
			this.items[i].setHighlighted( highlighted );
			changed = true;
		}
	}
	if ( changed ) {
		this.emit( 'highlight', item );
	}

	return this;
};

/**
 * Select an item.
 *
 * @param {OO.ui.OptionWidget} [item] Item to select, omit to deselect all
 * @fires select
 * @chainable
 */
OO.ui.SelectWidget.prototype.selectItem = function ( item ) {
	var i, len, selected,
		changed = false;

	for ( i = 0, len = this.items.length; i < len; i++ ) {
		selected = this.items[i] === item;
		if ( this.items[i].isSelected() !== selected ) {
			this.items[i].setSelected( selected );
			changed = true;
		}
	}
	if ( changed ) {
		this.emit( 'select', item );
	}

	return this;
};

/**
 * Press an item.
 *
 * @param {OO.ui.OptionWidget} [item] Item to press, omit to depress all
 * @fires press
 * @chainable
 */
OO.ui.SelectWidget.prototype.pressItem = function ( item ) {
	var i, len, pressed,
		changed = false;

	for ( i = 0, len = this.items.length; i < len; i++ ) {
		pressed = this.items[i] === item;
		if ( this.items[i].isPressed() !== pressed ) {
			this.items[i].setPressed( pressed );
			changed = true;
		}
	}
	if ( changed ) {
		this.emit( 'press', item );
	}

	return this;
};

/**
 * Choose an item.
 *
 * Identical to #selectItem, but may vary in subclasses that want to take additional action when
 * an item is selected using the keyboard or mouse.
 *
 * @param {OO.ui.OptionWidget} item Item to choose
 * @fires choose
 * @chainable
 */
OO.ui.SelectWidget.prototype.chooseItem = function ( item ) {
	this.selectItem( item );
	this.emit( 'choose', item );

	return this;
};

/**
 * Get an item relative to another one.
 *
 * @param {OO.ui.OptionWidget} item Item to start at
 * @param {number} direction Direction to move in
 * @return {OO.ui.OptionWidget|null} Item at position, `null` if there are no items in the menu
 */
OO.ui.SelectWidget.prototype.getRelativeSelectableItem = function ( item, direction ) {
	var inc = direction > 0 ? 1 : -1,
		len = this.items.length,
		index = item instanceof OO.ui.OptionWidget ?
			$.inArray( item, this.items ) : ( inc > 0 ? -1 : 0 ),
		stopAt = Math.max( Math.min( index, len - 1 ), 0 ),
		i = inc > 0 ?
			// Default to 0 instead of -1, if nothing is selected let's start at the beginning
			Math.max( index, -1 ) :
			// Default to n-1 instead of -1, if nothing is selected let's start at the end
			Math.min( index, len );

	while ( true ) {
		i = ( i + inc + len ) % len;
		item = this.items[i];
		if ( item instanceof OO.ui.OptionWidget && item.isSelectable() ) {
			return item;
		}
		// Stop iterating when we've looped all the way around
		if ( i === stopAt ) {
			break;
		}
	}
	return null;
};

/**
 * Get the next selectable item.
 *
 * @return {OO.ui.OptionWidget|null} Item, `null` if ther aren't any selectable items
 */
OO.ui.SelectWidget.prototype.getFirstSelectableItem = function () {
	var i, len, item;

	for ( i = 0, len = this.items.length; i < len; i++ ) {
		item = this.items[i];
		if ( item instanceof OO.ui.OptionWidget && item.isSelectable() ) {
			return item;
		}
	}

	return null;
};

/**
 * Add items.
 *
 * When items are added with the same values as existing items, the existing items will be
 * automatically removed before the new items are added.
 *
 * @param {OO.ui.OptionWidget[]} items Items to add
 * @param {number} [index] Index to insert items after
 * @fires add
 * @chainable
 */
OO.ui.SelectWidget.prototype.addItems = function ( items, index ) {
	var i, len, item, hash,
		remove = [];

	for ( i = 0, len = items.length; i < len; i++ ) {
		item = items[i];
		hash = OO.getHash( item.getData() );
		if ( hash in this.hashes ) {
			// Remove item with same value
			remove.push( this.hashes[hash] );
		}
		this.hashes[hash] = item;
	}
	if ( remove.length ) {
		this.removeItems( remove );
	}

	OO.ui.GroupElement.prototype.addItems.call( this, items, index );

	// Always provide an index, even if it was omitted
	this.emit( 'add', items, index === undefined ? this.items.length - items.length - 1 : index );

	return this;
};

/**
 * Remove items.
 *
 * Items will be detached, not removed, so they can be used later.
 *
 * @param {OO.ui.OptionWidget[]} items Items to remove
 * @fires remove
 * @chainable
 */
OO.ui.SelectWidget.prototype.removeItems = function ( items ) {
	var i, len, item, hash;

	for ( i = 0, len = items.length; i < len; i++ ) {
		item = items[i];
		hash = OO.getHash( item.getData() );
		if ( hash in this.hashes ) {
			// Remove existing item
			delete this.hashes[hash];
		}
		if ( item.isSelected() ) {
			this.selectItem( null );
		}
	}
	OO.ui.GroupElement.prototype.removeItems.call( this, items );

	this.emit( 'remove', items );

	return this;
};

/**
 * Clear all items.
 *
 * Items will be detached, not removed, so they can be used later.
 *
 * @fires remove
 * @chainable
 */
OO.ui.SelectWidget.prototype.clearItems = function () {
	var items = this.items.slice();

	// Clear all items
	this.hashes = {};
	OO.ui.GroupElement.prototype.clearItems.call( this );
	this.selectItem( null );

	this.emit( 'remove', items );

	return this;
};
/**
 * Menu item widget.
 *
 * Use with OO.ui.MenuWidget.
 *
 * @class
 * @extends OO.ui.OptionWidget
 *
 * @constructor
 * @param {Mixed} data Item data
 * @param {Object} [config] Configuration options
 */
OO.ui.MenuItemWidget = function OoUiMenuItemWidget( data, config ) {
	// Configuration initialization
	config = $.extend( { 'icon': 'check' }, config );

	// Parent constructor
	OO.ui.MenuItemWidget.super.call( this, data, config );

	// Initialization
	this.$element.addClass( 'oo-ui-menuItemWidget' );
};

/* Setup */

OO.inheritClass( OO.ui.MenuItemWidget, OO.ui.OptionWidget );
/**
 * Menu widget.
 *
 * Use together with OO.ui.MenuItemWidget.
 *
 * @class
 * @extends OO.ui.SelectWidget
 * @mixins OO.ui.ClippableElement
 *
 * @constructor
 * @param {Object} [config] Configuration options
 * @cfg {OO.ui.InputWidget} [input] Input to bind keyboard handlers to
 */
OO.ui.MenuWidget = function OoUiMenuWidget( config ) {
	// Config intialization
	config = config || {};

	// Parent constructor
	OO.ui.MenuWidget.super.call( this, config );

	// Mixin constructors
	OO.ui.ClippableElement.call( this, this.$group, config );

	// Properties
	this.newItems = null;
	this.$input = config.input ? config.input.$input : null;
	this.$previousFocus = null;
	this.isolated = !config.input;
	this.visible = false;
	this.flashing = false;
	this.onKeyDownHandler = OO.ui.bind( this.onKeyDown, this );

	// Initialization
	this.$element.hide().addClass( 'oo-ui-menuWidget' );
};

/* Setup */

OO.inheritClass( OO.ui.MenuWidget, OO.ui.SelectWidget );
OO.mixinClass( OO.ui.MenuWidget, OO.ui.ClippableElement );

/* Methods */

/**
 * Handles key down events.
 *
 * @param {jQuery.Event} e Key down event
 */
OO.ui.MenuWidget.prototype.onKeyDown = function ( e ) {
	var nextItem,
		handled = false,
		highlightItem = this.getHighlightedItem();

	if ( !this.disabled && this.visible ) {
		if ( !highlightItem ) {
			highlightItem = this.getSelectedItem();
		}
		switch ( e.keyCode ) {
			case OO.ui.Keys.ENTER:
				this.chooseItem( highlightItem );
				handled = true;
				break;
			case OO.ui.Keys.UP:
				nextItem = this.getRelativeSelectableItem( highlightItem, -1 );
				handled = true;
				break;
			case OO.ui.Keys.DOWN:
				nextItem = this.getRelativeSelectableItem( highlightItem, 1 );
				handled = true;
				break;
			case OO.ui.Keys.ESCAPE:
				if ( highlightItem ) {
					highlightItem.setHighlighted( false );
				}
				this.hide();
				handled = true;
				break;
		}

		if ( nextItem ) {
			this.highlightItem( nextItem );
			nextItem.scrollElementIntoView();
		}

		if ( handled ) {
			e.preventDefault();
			e.stopPropagation();
			return false;
		}
	}
};

/**
 * Check if the menu is visible.
 *
 * @return {boolean} Menu is visible
 */
OO.ui.MenuWidget.prototype.isVisible = function () {
	return this.visible;
};

/**
 * Bind key down listener.
 */
OO.ui.MenuWidget.prototype.bindKeyDownListener = function () {
	if ( this.$input ) {
		this.$input.on( 'keydown', this.onKeyDownHandler );
	} else {
		// Capture menu navigation keys
		this.getElementWindow().addEventListener( 'keydown', this.onKeyDownHandler, true );
	}
};

/**
 * Unbind key down listener.
 */
OO.ui.MenuWidget.prototype.unbindKeyDownListener = function () {
	if ( this.$input ) {
		this.$input.off( 'keydown' );
	} else {
		this.getElementWindow().removeEventListener( 'keydown', this.onKeyDownHandler, true );
	}
};

/**
 * Choose an item.
 *
 * This will close the menu when done, unlike selectItem which only changes selection.
 *
 * @param {OO.ui.OptionWidget} item Item to choose
 * @chainable
 */
OO.ui.MenuWidget.prototype.chooseItem = function ( item ) {
	// Parent method
	OO.ui.MenuWidget.super.prototype.chooseItem.call( this, item );

	if ( item && !this.flashing ) {
		this.flashing = true;
		item.flash( OO.ui.bind( function () {
			this.hide();
			this.flashing = false;
		}, this ) );
	} else {
		this.hide();
	}

	return this;
};

/**
 * Add items.
 *
 * Adding an existing item (by value) will move it.
 *
 * @param {OO.ui.MenuItemWidget[]} items Items to add
 * @param {number} [index] Index to insert items after
 * @chainable
 */
OO.ui.MenuWidget.prototype.addItems = function ( items, index ) {
	var i, len, item;

	// Parent method
	OO.ui.SelectWidget.prototype.addItems.call( this, items, index );

	// Auto-initialize
	if ( !this.newItems ) {
		this.newItems = [];
	}

	for ( i = 0, len = items.length; i < len; i++ ) {
		item = items[i];
		if ( this.visible ) {
			// Defer fitting label until
			item.fitLabel();
		} else {
			this.newItems.push( item );
		}
	}

	return this;
};

/**
 * Show the menu.
 *
 * @chainable
 */
OO.ui.MenuWidget.prototype.show = function () {
	var i, len;

	if ( this.items.length ) {
		this.$element.show();
		this.visible = true;
		this.bindKeyDownListener();

		// Change focus to enable keyboard navigation
		if ( this.isolated && this.$input && !this.$input.is( ':focus' ) ) {
			this.$previousFocus = this.$( ':focus' );
			this.$input.focus();
		}
		if ( this.newItems && this.newItems.length ) {
			for ( i = 0, len = this.newItems.length; i < len; i++ ) {
				this.newItems[i].fitLabel();
			}
			this.newItems = null;
		}

		this.setClipping( true );
	}

	return this;
};

/**
 * Hide the menu.
 *
 * @chainable
 */
OO.ui.MenuWidget.prototype.hide = function () {
	this.$element.hide();
	this.visible = false;
	this.unbindKeyDownListener();

	if ( this.isolated && this.$previousFocus ) {
		this.$previousFocus.focus();
		this.$previousFocus = null;
	}

	this.setClipping( false );

	return this;
};
/**
 * Inline menu of options.
 *
 * Use with OO.ui.MenuOptionWidget.
 *
 * @class
 * @extends OO.ui.Widget
 * @mixins OO.ui.IconedElement
 * @mixins OO.ui.IndicatedElement
 * @mixins OO.ui.LabeledElement
 * @mixins OO.ui.TitledElement
 *
 * @constructor
 * @param {Object} [config] Configuration options
 * @cfg {Object} [menu] Configuration options to pass to menu widget
 */
OO.ui.InlineMenuWidget = function OoUiInlineMenuWidget( config ) {
	// Configuration initialization
	config = $.extend( { 'indicator': 'down' }, config );

	// Parent constructor
	OO.ui.InlineMenuWidget.super.call( this, config );

	// Mixin constructors
	OO.ui.IconedElement.call( this, this.$( '<span>' ), config );
	OO.ui.IndicatedElement.call( this, this.$( '<span>' ), config );
	OO.ui.LabeledElement.call( this, this.$( '<span>' ), config );
	OO.ui.TitledElement.call( this, this.$label, config );

	// Properties
	this.menu = new OO.ui.MenuWidget( $.extend( { '$': this.$ }, config.menu ) );
	this.$handle = this.$( '<span>' );

	// Events
	this.$element.on( { 'click': OO.ui.bind( this.onClick, this ) } );
	this.menu.connect( this, { 'select': 'onMenuSelect' } );

	// Initialization
	this.$handle
		.addClass( 'oo-ui-inlineMenuWidget-handle' )
		.append( this.$icon, this.$label, this.$indicator );
	this.$element
		.addClass( 'oo-ui-inlineMenuWidget' )
		.append( this.$handle, this.menu.$element );
};

/* Setup */

OO.inheritClass( OO.ui.InlineMenuWidget, OO.ui.Widget );
OO.mixinClass( OO.ui.InlineMenuWidget, OO.ui.IconedElement );
OO.mixinClass( OO.ui.InlineMenuWidget, OO.ui.IndicatedElement );
OO.mixinClass( OO.ui.InlineMenuWidget, OO.ui.LabeledElement );
OO.mixinClass( OO.ui.InlineMenuWidget, OO.ui.TitledElement );

/* Methods */

/**
 * Get the menu.
 *
 * @return {OO.ui.MenuWidget} Menu of widget
 */
OO.ui.InlineMenuWidget.prototype.getMenu = function () {
	return this.menu;
};

/**
 * Handles menu select events.
 *
 * @param {OO.ui.MenuItemWidget} item Selected menu item
 */
OO.ui.InlineMenuWidget.prototype.onMenuSelect = function ( item ) {
	var selectedLabel;

	if ( !item ) {
		return;
	}

	selectedLabel = item.getLabel();

	// If the label is a DOM element, clone it, because setLabel will append() it
	if ( selectedLabel instanceof jQuery ) {
		selectedLabel = selectedLabel.clone();
	}

	this.setLabel( selectedLabel );
};

/**
 * Handles mouse click events.
 *
 * @param {jQuery.Event} e Mouse click event
 */
OO.ui.InlineMenuWidget.prototype.onClick = function ( e ) {
	// Skip clicks within the menu
	if ( $.contains( this.menu.$element[0], e.target ) ) {
		return;
	}

	if ( !this.disabled ) {
		if ( this.menu.isVisible() ) {
			this.menu.hide();
		} else {
			this.menu.show();
		}
	}
	return false;
};
/**
 * Menu section item widget.
 *
 * Use with OO.ui.MenuWidget.
 *
 * @class
 * @extends OO.ui.OptionWidget
 *
 * @constructor
 * @param {Mixed} data Item data
 * @param {Object} [config] Configuration options
 */
OO.ui.MenuSectionItemWidget = function OoUiMenuSectionItemWidget( data, config ) {
	// Parent constructor
	OO.ui.MenuSectionItemWidget.super.call( this, data, config );

	// Initialization
	this.$element.addClass( 'oo-ui-menuSectionItemWidget' );
};

/* Setup */

OO.inheritClass( OO.ui.MenuSectionItemWidget, OO.ui.OptionWidget );

/* Static Properties */

OO.ui.MenuSectionItemWidget.static.selectable = false;

OO.ui.MenuSectionItemWidget.static.highlightable = false;
/**
 * Create an OO.ui.OutlineWidget object.
 *
 * Use with OO.ui.OutlineItemWidget.
 *
 * @class
 * @extends OO.ui.SelectWidget
 *
 * @constructor
 * @param {Object} [config] Configuration options
 */
OO.ui.OutlineWidget = function OoUiOutlineWidget( config ) {
	// Config intialization
	config = config || {};

	// Parent constructor
	OO.ui.OutlineWidget.super.call( this, config );

	// Initialization
	this.$element.addClass( 'oo-ui-outlineWidget' );
};

/* Setup */

OO.inheritClass( OO.ui.OutlineWidget, OO.ui.SelectWidget );
/**
 * Creates an OO.ui.OutlineControlsWidget object.
 *
 * Use together with OO.ui.OutlineWidget.js
 *
 * @class
 *
 * @constructor
 * @param {OO.ui.OutlineWidget} outline Outline to control
 * @param {Object} [config] Configuration options
 */
OO.ui.OutlineControlsWidget = function OoUiOutlineControlsWidget( outline, config ) {
	// Configuration initialization
	config = $.extend( { 'icon': 'add-item' }, config );

	// Parent constructor
	OO.ui.OutlineControlsWidget.super.call( this, config );

	// Mixin constructors
	OO.ui.GroupElement.call( this, this.$( '<div>' ), config );
	OO.ui.IconedElement.call( this, this.$( '<div>' ), config );

	// Properties
	this.outline = outline;
	this.$movers = this.$( '<div>' );
	this.upButton = new OO.ui.ButtonWidget( {
		'$': this.$,
		'frameless': true,
		'icon': 'collapse',
		'title': OO.ui.msg( 'ooui-outline-control-move-up' )
	} );
	this.downButton = new OO.ui.ButtonWidget( {
		'$': this.$,
		'frameless': true,
		'icon': 'expand',
		'title': OO.ui.msg( 'ooui-outline-control-move-down' )
	} );
	this.removeButton = new OO.ui.ButtonWidget( {
		'$': this.$,
		'frameless': true,
		'icon': 'remove',
		'title': OO.ui.msg( 'ooui-outline-control-remove' )
	} );

	// Events
	outline.connect( this, {
		'select': 'onOutlineChange',
		'add': 'onOutlineChange',
		'remove': 'onOutlineChange'
	} );
	this.upButton.connect( this, { 'click': ['emit', 'move', -1] } );
	this.downButton.connect( this, { 'click': ['emit', 'move', 1] } );
	this.removeButton.connect( this, { 'click': ['emit', 'remove'] } );

	// Initialization
	this.$element.addClass( 'oo-ui-outlineControlsWidget' );
	this.$group.addClass( 'oo-ui-outlineControlsWidget-adders' );
	this.$movers
		.addClass( 'oo-ui-outlineControlsWidget-movers' )
		.append( this.removeButton.$element, this.upButton.$element, this.downButton.$element );
	this.$element.append( this.$icon, this.$group, this.$movers );
};

/* Setup */

OO.inheritClass( OO.ui.OutlineControlsWidget, OO.ui.Widget );
OO.mixinClass( OO.ui.OutlineControlsWidget, OO.ui.GroupElement );
OO.mixinClass( OO.ui.OutlineControlsWidget, OO.ui.IconedElement );

/* Events */

/**
 * @event move
 * @param {number} places Number of places to move
 */

/**
 * @event remove
 */

/* Methods */

/**
 * Handle outline change events.
 */
OO.ui.OutlineControlsWidget.prototype.onOutlineChange = function () {
	var i, len, firstMovable, lastMovable,
		items = this.outline.getItems(),
		selectedItem = this.outline.getSelectedItem(),
		movable = selectedItem && selectedItem.isMovable(),
		removable = selectedItem && selectedItem.isRemovable();

	if ( movable ) {
		i = -1;
		len = items.length;
		while ( ++i < len ) {
			if ( items[i].isMovable() ) {
				firstMovable = items[i];
				break;
			}
		}
		i = len;
		while ( i-- ) {
			if ( items[i].isMovable() ) {
				lastMovable = items[i];
				break;
			}
		}
	}
	this.upButton.setDisabled( !movable || selectedItem === firstMovable );
	this.downButton.setDisabled( !movable || selectedItem === lastMovable );
	this.removeButton.setDisabled( !removable );
};
/**
 * Creates an OO.ui.OutlineItemWidget object.
 *
 * Use with OO.ui.OutlineWidget.
 *
 * @class
 * @extends OO.ui.OptionWidget
 *
 * @constructor
 * @param {Mixed} data Item data
 * @param {Object} [config] Configuration options
 * @cfg {number} [level] Indentation level
 * @cfg {boolean} [movable] Allow modification from outline controls
 */
OO.ui.OutlineItemWidget = function OoUiOutlineItemWidget( data, config ) {
	// Config intialization
	config = config || {};

	// Parent constructor
	OO.ui.OutlineItemWidget.super.call( this, data, config );

	// Properties
	this.level = 0;
	this.movable = !!config.movable;
	this.removable = !!config.removable;

	// Initialization
	this.$element.addClass( 'oo-ui-outlineItemWidget' );
	this.setLevel( config.level );
};

/* Setup */

OO.inheritClass( OO.ui.OutlineItemWidget, OO.ui.OptionWidget );

/* Static Properties */

OO.ui.OutlineItemWidget.static.highlightable = false;

OO.ui.OutlineItemWidget.static.scrollIntoViewOnSelect = true;

OO.ui.OutlineItemWidget.static.levelClass = 'oo-ui-outlineItemWidget-level-';

OO.ui.OutlineItemWidget.static.levels = 3;

/* Methods */

/**
 * Check if item is movable.
 *
 * Movablilty is used by outline controls.
 *
 * @return {boolean} Item is movable
 */
OO.ui.OutlineItemWidget.prototype.isMovable = function () {
	return this.movable;
};

/**
 * Check if item is removable.
 *
 * Removablilty is used by outline controls.
 *
 * @return {boolean} Item is removable
 */
OO.ui.OutlineItemWidget.prototype.isRemovable = function () {
	return this.removable;
};

/**
 * Get indentation level.
 *
 * @return {number} Indentation level
 */
OO.ui.OutlineItemWidget.prototype.getLevel = function () {
	return this.level;
};

/**
 * Set movability.
 *
 * Movablilty is used by outline controls.
 *
 * @param {boolean} movable Item is movable
 * @chainable
 */
OO.ui.OutlineItemWidget.prototype.setMovable = function ( movable ) {
	this.movable = !!movable;
	return this;
};

/**
 * Set removability.
 *
 * Removablilty is used by outline controls.
 *
 * @param {boolean} movable Item is removable
 * @chainable
 */
OO.ui.OutlineItemWidget.prototype.setRemovable = function ( removable ) {
	this.removable = !!removable;
	return this;
};

/**
 * Set indentation level.
 *
 * @param {number} [level=0] Indentation level, in the range of [0,#maxLevel]
 * @chainable
 */
OO.ui.OutlineItemWidget.prototype.setLevel = function ( level ) {
	var levels = this.constructor.static.levels,
		levelClass = this.constructor.static.levelClass,
		i = levels;

	this.level = level ? Math.max( 0, Math.min( levels - 1, level ) ) : 0;
	while ( i-- ) {
		if ( this.level === i ) {
			this.$element.addClass( levelClass + i );
		} else {
			this.$element.removeClass( levelClass + i );
		}
	}

	return this;
};
/**
 * Option widget that looks like a button.
 *
 * Use together with OO.ui.ButtonSelectWidget.
 *
 * @class
 * @extends OO.ui.OptionWidget
 * @mixins OO.ui.ButtonedElement
 * @mixins OO.ui.FlaggableElement
 *
 * @constructor
 * @param {Mixed} data Option data
 * @param {Object} [config] Configuration options
 */
OO.ui.ButtonOptionWidget = function OoUiButtonOptionWidget( data, config ) {
	// Parent constructor
	OO.ui.ButtonOptionWidget.super.call( this, data, config );

	// Mixin constructors
	OO.ui.ButtonedElement.call( this, this.$( '<a>' ), config );
	OO.ui.FlaggableElement.call( this, config );

	// Initialization
	this.$element.addClass( 'oo-ui-buttonOptionWidget' );
	this.$button.append( this.$element.contents() );
	this.$element.append( this.$button );
};

/* Setup */

OO.inheritClass( OO.ui.ButtonOptionWidget, OO.ui.OptionWidget );
OO.mixinClass( OO.ui.ButtonOptionWidget, OO.ui.ButtonedElement );
OO.mixinClass( OO.ui.ButtonOptionWidget, OO.ui.FlaggableElement );

/* Methods */

/**
 * @inheritdoc
 */
OO.ui.ButtonOptionWidget.prototype.setSelected = function ( state ) {
	OO.ui.OptionWidget.prototype.setSelected.call( this, state );

	this.setActive( state );

	return this;
};
/**
 * Select widget containing button options.
 *
 * Use together with OO.ui.ButtonOptionWidget.
 *
 * @class
 * @extends OO.ui.SelectWidget
 *
 * @constructor
 * @param {Object} [config] Configuration options
 */
OO.ui.ButtonSelectWidget = function OoUiButtonSelectWidget( config ) {
	// Parent constructor
	OO.ui.ButtonSelectWidget.super.call( this, config );

	// Initialization
	this.$element.addClass( 'oo-ui-buttonSelectWidget' );
};

/* Setup */

OO.inheritClass( OO.ui.ButtonSelectWidget, OO.ui.SelectWidget );
/**
 * Container for content that is overlaid and positioned absolutely.
 *
 * @class
 * @extends OO.ui.Widget
 * @mixins OO.ui.LabeledElement
 *
 * @constructor
 * @param {Object} [config] Configuration options
 * @cfg {boolean} [tail=true] Show tail pointing to origin of popup
 * @cfg {string} [align='center'] Alignment of popup to origin
 * @cfg {jQuery} [$container] Container to prevent popup from rendering outside of
 * @cfg {boolean} [autoClose=false] Popup auto-closes when it loses focus
 * @cfg {jQuery} [$autoCloseIgnore] Elements to not auto close when clicked
 * @cfg {boolean} [head] Show label and close button at the top
 */
OO.ui.PopupWidget = function OoUiPopupWidget( config ) {
	// Config intialization
	config = config || {};

	// Parent constructor
	OO.ui.PopupWidget.super.call( this, config );

	// Mixin constructors
	OO.ui.LabeledElement.call( this, this.$( '<div>' ), config );
	OO.ui.ClippableElement.call( this, this.$( '<div>' ), config );

	// Properties
	this.visible = false;
	this.$popup = this.$( '<div>' );
	this.$head = this.$( '<div>' );
	this.$body = this.$clippable;
	this.$tail = this.$( '<div>' );
	this.$container = config.$container || this.$( 'body' );
	this.autoClose = !!config.autoClose;
	this.$autoCloseIgnore = config.$autoCloseIgnore;
	this.transitionTimeout = null;
	this.tail = false;
	this.align = config.align || 'center';
	this.closeButton = new OO.ui.ButtonWidget( { '$': this.$, 'frameless': true, 'icon': 'close' } );
	this.onMouseDownHandler = OO.ui.bind( this.onMouseDown, this );

	// Events
	this.closeButton.connect( this, { 'click': 'onCloseButtonClick' } );

	// Initialization
	this.useTail( config.tail !== undefined ? !!config.tail : true );
	this.$body.addClass( 'oo-ui-popupWidget-body' );
	this.$tail.addClass( 'oo-ui-popupWidget-tail' );
	this.$head
		.addClass( 'oo-ui-popupWidget-head' )
		.append( this.$label, this.closeButton.$element );
	if ( !config.head ) {
		this.$head.hide();
	}
	this.$popup
		.addClass( 'oo-ui-popupWidget-popup' )
		.append( this.$head, this.$body );
	this.$element.hide()
		.addClass( 'oo-ui-popupWidget' )
		.append( this.$popup, this.$tail );
};

/* Setup */

OO.inheritClass( OO.ui.PopupWidget, OO.ui.Widget );
OO.mixinClass( OO.ui.PopupWidget, OO.ui.LabeledElement );
OO.mixinClass( OO.ui.PopupWidget, OO.ui.ClippableElement );

/* Events */

/**
 * @event hide
 */

/**
 * @event show
 */

/* Methods */

/**
 * Handles mouse down events.
 *
 * @param {jQuery.Event} e Mouse down event
 */
OO.ui.PopupWidget.prototype.onMouseDown = function ( e ) {
	if (
		this.visible &&
		!$.contains( this.$element[0], e.target ) &&
		( !this.$autoCloseIgnore || !this.$autoCloseIgnore.has( e.target ).length )
	) {
		this.hide();
	}
};

/**
 * Bind mouse down listener.
 */
OO.ui.PopupWidget.prototype.bindMouseDownListener = function () {
	// Capture clicks outside popup
	this.getElementWindow().addEventListener( 'mousedown', this.onMouseDownHandler, true );
};

/**
 * Handles close button click events.
 */
OO.ui.PopupWidget.prototype.onCloseButtonClick = function () {
	if ( this.visible ) {
		this.hide();
	}
};

/**
 * Unbind mouse down listener.
 */
OO.ui.PopupWidget.prototype.unbindMouseDownListener = function () {
	this.getElementWindow().removeEventListener( 'mousedown', this.onMouseDownHandler, true );
};

/**
 * Check if the popup is visible.
 *
 * @return {boolean} Popup is visible
 */
OO.ui.PopupWidget.prototype.isVisible = function () {
	return this.visible;
};

/**
 * Set whether to show a tail.
 *
 * @return {boolean} Make tail visible
 */
OO.ui.PopupWidget.prototype.useTail = function ( value ) {
	value = !!value;
	if ( this.tail !== value ) {
		this.tail = value;
		if ( value ) {
			this.$element.addClass( 'oo-ui-popupWidget-tailed' );
		} else {
			this.$element.removeClass( 'oo-ui-popupWidget-tailed' );
		}
	}
};

/**
 * Check if showing a tail.
 *
 * @return {boolean} tail is visible
 */
OO.ui.PopupWidget.prototype.hasTail = function () {
	return this.tail;
};

/**
 * Show the context.
 *
 * @fires show
 * @chainable
 */
OO.ui.PopupWidget.prototype.show = function () {
	if ( !this.visible ) {
		this.setClipping( true );
		this.$element.show();
		this.visible = true;
		this.emit( 'show' );
		if ( this.autoClose ) {
			this.bindMouseDownListener();
		}
	}
	return this;
};

/**
 * Hide the context.
 *
 * @fires hide
 * @chainable
 */
OO.ui.PopupWidget.prototype.hide = function () {
	if ( this.visible ) {
		this.setClipping( false );
		this.$element.hide();
		this.visible = false;
		this.emit( 'hide' );
		if ( this.autoClose ) {
			this.unbindMouseDownListener();
		}
	}
	return this;
};

/**
 * Updates the position and size.
 *
 * @param {number} width Width
 * @param {number} height Height
 * @param {boolean} [transition=false] Use a smooth transition
 * @chainable
 */
OO.ui.PopupWidget.prototype.display = function ( width, height, transition ) {
	var padding = 10,
		originOffset = Math.round( this.$element.offset().left ),
		containerLeft = Math.round( this.$container.offset().left ),
		containerWidth = this.$container.innerWidth(),
		containerRight = containerLeft + containerWidth,
		popupOffset = width * ( { 'left': 0, 'center': -0.5, 'right': -1 } )[this.align],
		popupLeft = popupOffset - padding,
		popupRight = popupOffset + padding + width + padding,
		overlapLeft = ( originOffset + popupLeft ) - containerLeft,
		overlapRight = containerRight - ( originOffset + popupRight );

	// Prevent transition from being interrupted
	clearTimeout( this.transitionTimeout );
	if ( transition ) {
		// Enable transition
		this.$element.addClass( 'oo-ui-popupWidget-transitioning' );
	}

	if ( overlapRight < 0 ) {
		popupOffset += overlapRight;
	} else if ( overlapLeft < 0 ) {
		popupOffset -= overlapLeft;
	}

	// Position body relative to anchor and resize
	this.$popup.css( {
		'left': popupOffset,
		'width': width,
		'height': height === undefined ? 'auto' : height
	} );

	if ( transition ) {
		// Prevent transitioning after transition is complete
		this.transitionTimeout = setTimeout( OO.ui.bind( function () {
			this.$element.removeClass( 'oo-ui-popupWidget-transitioning' );
		}, this ), 200 );
	} else {
		// Prevent transitioning immediately
		this.$element.removeClass( 'oo-ui-popupWidget-transitioning' );
	}

	return this;
};
/**
 * Button that shows and hides a popup.
 *
 * @class
 * @extends OO.ui.ButtonWidget
 * @mixins OO.ui.PopuppableElement
 *
 * @constructor
 * @param {Object} [config] Configuration options
 */
OO.ui.PopupButtonWidget = function OoUiPopupButtonWidget( config ) {
	// Parent constructor
	OO.ui.PopupButtonWidget.super.call( this, config );

	// Mixin constructors
	OO.ui.PopuppableElement.call( this, config );

	// Initialization
	this.$element
		.addClass( 'oo-ui-popupButtonWidget' )
		.append( this.popup.$element );
};

/* Setup */

OO.inheritClass( OO.ui.PopupButtonWidget, OO.ui.ButtonWidget );
OO.mixinClass( OO.ui.PopupButtonWidget, OO.ui.PopuppableElement );

/* Methods */

/**
 * Handles mouse click events.
 *
 * @param {jQuery.Event} e Mouse click event
 */
OO.ui.PopupButtonWidget.prototype.onClick = function ( e ) {
	// Skip clicks within the popup
	if ( $.contains( this.popup.$element[0], e.target ) ) {
		return;
	}

	if ( !this.disabled ) {
		if ( this.popup.isVisible() ) {
			this.hidePopup();
		} else {
			this.showPopup();
		}
		OO.ui.ButtonWidget.prototype.onClick.call( this );
	}
	return false;
};
/**
 * Search widget.
 *
 * Combines query and results selection widgets.
 *
 * @class
 * @extends OO.ui.Widget
 *
 * @constructor
 * @param {Object} [config] Configuration options
 * @cfg {string|jQuery} [placeholder] Placeholder text for query input
 * @cfg {string} [value] Initial query value
 */
OO.ui.SearchWidget = function OoUiSearchWidget( config ) {
	// Configuration intialization
	config = config || {};

	// Parent constructor
	OO.ui.SearchWidget.super.call( this, config );

	// Properties
	this.query = new OO.ui.TextInputWidget( {
		'$': this.$,
		'icon': 'search',
		'placeholder': config.placeholder,
		'value': config.value
	} );
	this.results = new OO.ui.SelectWidget( { '$': this.$ } );
	this.$query = this.$( '<div>' );
	this.$results = this.$( '<div>' );

	// Events
	this.query.connect( this, {
		'change': 'onQueryChange',
		'enter': 'onQueryEnter'
	} );
	this.results.connect( this, {
		'highlight': 'onResultsHighlight',
		'select': 'onResultsSelect'
	} );
	this.query.$input.on( 'keydown', OO.ui.bind( this.onQueryKeydown, this ) );

	// Initialization
	this.$query
		.addClass( 'oo-ui-searchWidget-query' )
		.append( this.query.$element );
	this.$results
		.addClass( 'oo-ui-searchWidget-results' )
		.append( this.results.$element );
	this.$element
		.addClass( 'oo-ui-searchWidget' )
		.append( this.$results, this.$query );
};

/* Setup */

OO.inheritClass( OO.ui.SearchWidget, OO.ui.Widget );

/* Events */

/**
 * @event highlight
 * @param {Object|null} item Item data or null if no item is highlighted
 */

/**
 * @event select
 * @param {Object|null} item Item data or null if no item is selected
 */

/* Methods */

/**
 * Handle query key down events.
 *
 * @param {jQuery.Event} e Key down event
 */
OO.ui.SearchWidget.prototype.onQueryKeydown = function ( e ) {
	var highlightedItem, nextItem,
		dir = e.which === OO.ui.Keys.DOWN ? 1 : ( e.which === OO.ui.Keys.UP ? -1 : 0 );

	if ( dir ) {
		highlightedItem = this.results.getHighlightedItem();
		if ( !highlightedItem ) {
			highlightedItem = this.results.getSelectedItem();
		}
		nextItem = this.results.getRelativeSelectableItem( highlightedItem, dir );
		this.results.highlightItem( nextItem );
		nextItem.scrollElementIntoView();
	}
};

/**
 * Handle select widget select events.
 *
 * Clears existing results. Subclasses should repopulate items according to new query.
 *
 * @param {string} value New value
 */
OO.ui.SearchWidget.prototype.onQueryChange = function () {
	// Reset
	this.results.clearItems();
};

/**
 * Handle select widget enter key events.
 *
 * Selects highlighted item.
 *
 * @param {string} value New value
 */
OO.ui.SearchWidget.prototype.onQueryEnter = function () {
	// Reset
	this.results.selectItem( this.results.getHighlightedItem() );
};

/**
 * Handle select widget highlight events.
 *
 * @param {OO.ui.OptionWidget} item Highlighted item
 * @fires highlight
 */
OO.ui.SearchWidget.prototype.onResultsHighlight = function ( item ) {
	this.emit( 'highlight', item ? item.getData() : null );
};

/**
 * Handle select widget select events.
 *
 * @param {OO.ui.OptionWidget} item Selected item
 * @fires select
 */
OO.ui.SearchWidget.prototype.onResultsSelect = function ( item ) {
	this.emit( 'select', item ? item.getData() : null );
};

/**
 * Get the query input.
 *
 * @return {OO.ui.TextInputWidget} Query input
 */
OO.ui.SearchWidget.prototype.getQuery = function () {
	return this.query;
};

/**
 * Reset the widget to initial value.
 */
OO.ui.SearchWidget.prototype.clear = function () {
	this.query.setValue( '' );
};

/**
 * Get the results list.
 *
 * @return {OO.ui.SelectWidget} Select list
 */
OO.ui.SearchWidget.prototype.getResults = function () {
	return this.results;
};
/**
 * Text input widget.
 *
 * @class
 * @extends OO.ui.InputWidget
 *
 * @constructor
 * @param {Object} [config] Configuration options
 * @cfg {string} [placeholder] Placeholder text
 * @cfg {string} [icon] Symbolic name of icon
 * @cfg {boolean} [multiline=false] Allow multiple lines of text
 * @cfg {boolean} [autosize=false] Automatically resize to fit content
 * @cfg {boolean} [maxRows=10] Maximum number of rows to make visible when autosizing
 */
OO.ui.TextInputWidget = function OoUiTextInputWidget( config ) {
	config = $.extend( { 'maxRows': 10 }, config );

	// Parent constructor
	OO.ui.TextInputWidget.super.call( this, config );

	// Properties
	this.pending = 0;
	this.multiline = !!config.multiline;
	this.autosize = !!config.autosize;
	this.maxRows = config.maxRows;

	// Events
	this.$input.on( 'keypress', OO.ui.bind( this.onKeyPress, this ) );
	this.$element.on( 'DOMNodeInsertedIntoDocument', OO.ui.bind( this.onElementAttach, this ) );

	// Initialization
	this.$element.addClass( 'oo-ui-textInputWidget' );
	if ( config.icon ) {
		this.$element.addClass( 'oo-ui-textInputWidget-decorated' );
		this.$element.append(
			this.$( '<span>' )
				.addClass( 'oo-ui-textInputWidget-icon oo-ui-icon-' + config.icon )
				.mousedown( OO.ui.bind( function () {
					this.$input.focus();
					return false;
				}, this ) )
		);
	}
	if ( config.placeholder ) {
		this.$input.attr( 'placeholder', config.placeholder );
	}
};

/* Setup */

OO.inheritClass( OO.ui.TextInputWidget, OO.ui.InputWidget );

/* Events */

/**
 * User presses enter inside the text box.
 *
 * Not called if input is multiline.
 *
 * @event enter
 */

/* Methods */

/**
 * Handle key press events.
 *
 * @param {jQuery.Event} e Key press event
 * @fires enter If enter key is pressed and input is not multiline
 */
OO.ui.TextInputWidget.prototype.onKeyPress = function ( e ) {
	if ( e.which === OO.ui.Keys.ENTER && !this.multiline ) {
		this.emit( 'enter' );
	}
};

/**
 * Handle element attach events.
 *
 * @param {jQuery.Event} e Element attach event
 */
OO.ui.TextInputWidget.prototype.onElementAttach = function () {
	this.adjustSize();
};

/**
 * @inheritdoc
 */
OO.ui.TextInputWidget.prototype.onEdit = function () {
	this.adjustSize();

	// Parent method
	return OO.ui.InputWidget.prototype.onEdit.call( this );
};

/**
 * Automatically adjust the size of the text input.
 *
 * This only affects multi-line inputs that are auto-sized.
 *
 * @chainable
 */
OO.ui.TextInputWidget.prototype.adjustSize = function () {
	var $clone, scrollHeight, innerHeight, outerHeight, maxInnerHeight, idealHeight;

	if ( this.multiline && this.autosize ) {
		$clone = this.$input.clone()
			.val( this.$input.val() )
			.css( { 'height': 0 } )
			.insertAfter( this.$input );
		// Set inline height property to 0 to measure scroll height
		scrollHeight = $clone[0].scrollHeight;
		// Remove inline height property to measure natural heights
		$clone.css( 'height', '' );
		innerHeight = $clone.innerHeight();
		outerHeight = $clone.outerHeight();
		// Measure max rows height
		$clone.attr( 'rows', this.maxRows ).css( 'height', 'auto' );
		maxInnerHeight = $clone.innerHeight();
		$clone.removeAttr( 'rows' ).css( 'height', '' );
		$clone.remove();
		idealHeight = Math.min( maxInnerHeight, scrollHeight );
		// Only apply inline height when expansion beyond natural height is needed
		this.$input.css(
			'height',
			// Use the difference between the inner and outer height as a buffer
			idealHeight > outerHeight ? idealHeight + ( outerHeight - innerHeight ) : ''
		);
	}
	return this;
};

/**
 * Get input element.
 *
 * @param {Object} [config] Configuration options
 * @return {jQuery} Input element
 */
OO.ui.TextInputWidget.prototype.getInputElement = function ( config ) {
	return config.multiline ? this.$( '<textarea>' ) : this.$( '<input type="text" />' );
};

/* Methods */

/**
 * Check if input supports multiple lines.
 *
 * @return {boolean}
 */
OO.ui.TextInputWidget.prototype.isMultiline = function () {
	return !!this.multiline;
};

/**
 * Check if input automatically adjusts its size.
 *
 * @return {boolean}
 */
OO.ui.TextInputWidget.prototype.isAutosizing = function () {
	return !!this.autosize;
};

/**
 * Check if input is pending.
 *
 * @return {boolean}
 */
OO.ui.TextInputWidget.prototype.isPending = function () {
	return !!this.pending;
};

/**
 * Increase the pending stack.
 *
 * @chainable
 */
OO.ui.TextInputWidget.prototype.pushPending = function () {
	this.pending++;
	this.$element.addClass( 'oo-ui-textInputWidget-pending' );
	this.$input.addClass( 'oo-ui-texture-pending' );
	return this;
};

/**
 * Reduce the pending stack.
 *
 * Clamped at zero.
 *
 * @chainable
 */
OO.ui.TextInputWidget.prototype.popPending = function () {
	this.pending = Math.max( 0, this.pending - 1 );
	if ( !this.pending ) {
		this.$element.removeClass( 'oo-ui-textInputWidget-pending' );
		this.$input.removeClass( 'oo-ui-texture-pending' );
	}
	return this;
};
/**
 * Menu for a text input widget.
 *
 * @class
 * @extends OO.ui.MenuWidget
 *
 * @constructor
 * @param {OO.ui.TextInputWidget} input Text input widget to provide menu for
 * @param {Object} [config] Configuration options
 * @cfg {jQuery} [$container=input.$element] Element to render menu under
 */
OO.ui.TextInputMenuWidget = function OoUiTextInputMenuWidget( input, config ) {
	// Parent constructor
	OO.ui.TextInputMenuWidget.super.call( this, config );

	// Properties
	this.input = input;
	this.$container = config.$container || this.input.$element;
	this.onWindowResizeHandler = OO.ui.bind( this.onWindowResize, this );

	// Initialization
	this.$element.addClass( 'oo-ui-textInputMenuWidget' );
};

/* Setup */

OO.inheritClass( OO.ui.TextInputMenuWidget, OO.ui.MenuWidget );

/* Methods */

/**
 * Handle window resize event.
 *
 * @param {jQuery.Event} e Window resize event
 */
OO.ui.TextInputMenuWidget.prototype.onWindowResize = function () {
	this.position();
};

/**
 * Show the menu.
 *
 * @chainable
 */
OO.ui.TextInputMenuWidget.prototype.show = function () {
	// Parent method
	OO.ui.MenuWidget.prototype.show.call( this );

	this.position();
	this.$( this.getElementWindow() ).on( 'resize', this.onWindowResizeHandler );
	return this;
};

/**
 * Hide the menu.
 *
 * @chainable
 */
OO.ui.TextInputMenuWidget.prototype.hide = function () {
	// Parent method
	OO.ui.MenuWidget.prototype.hide.call( this );

	this.$( this.getElementWindow() ).off( 'resize', this.onWindowResizeHandler );
	return this;
};

/**
 * Position the menu.
 *
 * @chainable
 */
OO.ui.TextInputMenuWidget.prototype.position = function () {
	var frameOffset,
		$container = this.$container,
		dimensions = $container.offset();

	// Position under input
	dimensions.top += $container.height();

	// Compensate for frame position if in a differnt frame
	if ( this.input.$.frame && this.input.$.context !== this.$element[0].ownerDocument ) {
		frameOffset = OO.ui.Element.getRelativePosition(
			this.input.$.frame.$element, this.$element.offsetParent()
		);
		dimensions.left += frameOffset.left;
		dimensions.top += frameOffset.top;
	} else {
		// Fix for RTL (for some reason, no need to fix if the frameoffset is set)
		if ( this.$element.css( 'direction' ) === 'rtl' ) {
			dimensions.right = this.$element.parent().position().left -
				dimensions.width - dimensions.left;
			// Erase the value for 'left':
			delete dimensions.left;
		}
	}

	this.$element.css( dimensions );
	this.setIdealSize( $container.width() );
	return this;
};
/**
 * Width with on and off states.
 *
 * Mixin for widgets with a boolean state.
 *
 * @abstract
 * @class
 *
 * @constructor
 * @param {Object} [config] Configuration options
 * @cfg {boolean} [value=false] Initial value
 */
OO.ui.ToggleWidget = function OoUiToggleWidget( config ) {
	// Configuration initialization
	config = config || {};

	// Properties
	this.value = null;

	// Initialization
	this.$element.addClass( 'oo-ui-toggleWidget' );
	this.setValue( !!config.value );
};

/* Events */

/**
 * @event change
 * @param {boolean} value Changed value
 */

/* Methods */

/**
 * Get the value of the toggle.
 *
 * @return {boolean}
 */
OO.ui.ToggleWidget.prototype.getValue = function () {
	return this.value;
};

/**
 * Set the value of the toggle.
 *
 * @param {boolean} value New value
 * @fires change
 * @chainable
 */
OO.ui.ToggleWidget.prototype.setValue = function ( value ) {
	value = !!value;
	if ( this.value !== value ) {
		this.value = value;
		this.emit( 'change', value );
		this.$element.toggleClass( 'oo-ui-toggleWidget-on', value );
		this.$element.toggleClass( 'oo-ui-toggleWidget-off', !value );
	}
	return this;
};
/**
 * Button that toggles on and off.
 *
 * @class
 * @extends OO.ui.ButtonWidget
 * @mixins OO.ui.ToggleWidget
 *
 * @constructor
 * @param {Object} [config] Configuration options
 * @cfg {boolean} [value=false] Initial value
 */
OO.ui.ToggleButtonWidget = function OoUiToggleButtonWidget( config ) {
	// Configuration initialization
	config = config || {};

	// Parent constructor
	OO.ui.ToggleButtonWidget.super.call( this, config );

	// Mixin constructors
	OO.ui.ToggleWidget.call( this, config );

	// Initialization
	this.$element.addClass( 'oo-ui-toggleButtonWidget' );
};

/* Setup */

OO.inheritClass( OO.ui.ToggleButtonWidget, OO.ui.ButtonWidget );
OO.mixinClass( OO.ui.ToggleButtonWidget, OO.ui.ToggleWidget );

/* Methods */

/**
 * @inheritdoc
 */
OO.ui.ToggleButtonWidget.prototype.onClick = function () {
	if ( !this.disabled ) {
		this.setValue( !this.value );
	}

	// Parent method
	return OO.ui.ButtonWidget.prototype.onClick.call( this );
};

/**
 * @inheritdoc
 */
OO.ui.ToggleButtonWidget.prototype.setValue = function ( value ) {
	value = !!value;
	if ( value !== this.value ) {
		this.setActive( value );
	}

	// Parent method
	OO.ui.ToggleWidget.prototype.setValue.call( this, value );

	return this;
};
/**
 * Switch that slides on and off.
 *
 * @abstract
 * @class
 * @extends OO.ui.Widget
 * @mixins OO.ui.ToggleWidget
 *
 * @constructor
 * @param {Object} [config] Configuration options
 * @cfg {boolean} [value=false] Initial value
 */
OO.ui.ToggleSwitchWidget = function OoUiToggleSwitchWidget( config ) {
	// Parent constructor
	OO.ui.ToggleSwitchWidget.super.call( this, config );

	// Mixin constructors
	OO.ui.ToggleWidget.call( this, config );

	// Properties
	this.dragging = false;
	this.dragStart = null;
	this.sliding = false;
	this.$glow = this.$( '<span>' );
	this.$grip = this.$( '<span>' );

	// Events
	this.$element.on( 'click', OO.ui.bind( this.onClick, this ) );

	// Initialization
	this.$glow.addClass( 'oo-ui-toggleSwitchWidget-glow' );
	this.$grip.addClass( 'oo-ui-toggleSwitchWidget-grip' );
	this.$element
		.addClass( 'oo-ui-toggleSwitchWidget' )
		.append( this.$glow, this.$grip );
};

/* Setup */

OO.inheritClass( OO.ui.ToggleSwitchWidget, OO.ui.Widget );
OO.mixinClass( OO.ui.ToggleSwitchWidget, OO.ui.ToggleWidget );

/* Methods */

/**
 * Handle mouse down events.
 *
 * @param {jQuery.Event} e Mouse down event
 */
OO.ui.ToggleSwitchWidget.prototype.onClick = function ( e ) {
	if ( !this.disabled && e.which === 1 ) {
		this.setValue( !this.value );
	}
};
}( OO ) );
