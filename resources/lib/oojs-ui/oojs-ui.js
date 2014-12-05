/*!
 * OOjs UI v0.3.0
 * https://www.mediawiki.org/wiki/OOjs_UI
 *
 * Copyright 2011–2014 OOjs Team and other contributors.
 * Released under the MIT license
 * http://oojs.mit-license.org
 *
 * Date: 2014-12-05T00:54:04Z
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
	UNDEFINED: 0,
	BACKSPACE: 8,
	DELETE: 46,
	LEFT: 37,
	RIGHT: 39,
	UP: 38,
	DOWN: 40,
	ENTER: 13,
	END: 35,
	HOME: 36,
	TAB: 9,
	PAGEUP: 33,
	PAGEDOWN: 34,
	ESCAPE: 27,
	SHIFT: 16,
	SPACE: 32
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

/**
 * Check if a node is contained within another node
 *
 * Similar to jQuery#contains except a list of containers can be supplied
 * and a boolean argument allows you to include the container in the match list
 *
 * @param {HTMLElement|HTMLElement[]} containers Container node(s) to search in
 * @param {HTMLElement} contained Node to find
 * @param {boolean} [matchContainers] Include the container(s) in the list of nodes to match, otherwise only match descendants
 * @return {boolean} The node is in the list of target nodes
 */
OO.ui.contains = function ( containers, contained, matchContainers ) {
	var i;
	if ( !Array.isArray( containers ) ) {
		containers = [ containers ];
	}
	for ( i = containers.length - 1; i >= 0; i-- ) {
		if ( ( matchContainers && contained === containers[i] ) || $.contains( containers[i], contained ) ) {
			return true;
		}
	}
	return false;
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
		// Tool tip for a button that moves items in a list down one place
		'ooui-outline-control-move-down': 'Move item down',
		// Tool tip for a button that moves items in a list up one place
		'ooui-outline-control-move-up': 'Move item up',
		// Tool tip for a button that removes items from a list
		'ooui-outline-control-remove': 'Remove item',
		// Label for the toolbar group that contains a list of all other available tools
		'ooui-toolbar-more': 'More',
		// Label for the fake tool that expands the full list of tools in a toolbar group
		'ooui-toolgroup-expand': 'More',
		// Label for the fake tool that collapses the full list of tools in a toolbar group
		'ooui-toolgroup-collapse': 'Fewer',
		// Default label for the accept button of a confirmation dialog
		'ooui-dialog-message-accept': 'OK',
		// Default label for the reject button of a confirmation dialog
		'ooui-dialog-message-reject': 'Cancel',
		// Title for process dialog error description
		'ooui-dialog-process-error': 'Something went wrong',
		// Label for process dialog dismiss error button, visible when describing errors
		'ooui-dialog-process-dismiss': 'Dismiss',
		// Label for process dialog retry action button, visible when describing only recoverable errors
		'ooui-dialog-process-retry': 'Try again',
		// Label for process dialog retry action button, visible when describing only warnings
		'ooui-dialog-process-continue': 'Continue'
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

	/**
	 * Package a message and arguments for deferred resolution.
	 *
	 * Use this when you are statically specifying a message and the message may not yet be present.
	 *
	 * @param {string} key Message key
	 * @param {Mixed...} [params] Message parameters
	 * @return {Function} Function that returns the resolved message when executed
	 */
	OO.ui.deferMsg = function () {
		var args = arguments;
		return function () {
			return OO.ui.msg.apply( OO.ui, args );
		};
	};

	/**
	 * Resolve a message.
	 *
	 * If the message is a function it will be executed, otherwise it will pass through directly.
	 *
	 * @param {Function|string} msg Deferred message, or message text
	 * @return {string} Resolved message
	 */
	OO.ui.resolveMsg = function ( msg ) {
		if ( $.isFunction( msg ) ) {
			return msg();
		}
		return msg;
	};

} )();

/**
 * Element that can be marked as pending.
 *
 * @abstract
 * @class
 *
 * @constructor
 * @param {Object} [config] Configuration options
 * @cfg {jQuery} [$pending] Element to mark as pending, defaults to this.$element
 */
OO.ui.PendingElement = function OoUiPendingElement( config ) {
	// Configuration initialization
	config = config || {};

	// Properties
	this.pending = 0;
	this.$pending = null;

	// Initialisation
	this.setPendingElement( config.$pending || this.$element );
};

/* Setup */

OO.initClass( OO.ui.PendingElement );

/* Methods */

/**
 * Set the pending element (and clean up any existing one).
 *
 * @param {jQuery} $pending The element to set to pending.
 */
OO.ui.PendingElement.prototype.setPendingElement = function ( $pending ) {
	if ( this.$pending ) {
		this.$pending.removeClass( 'oo-ui-pendingElement-pending' );
	}

	this.$pending = $pending;
	if ( this.pending > 0 ) {
		this.$pending.addClass( 'oo-ui-pendingElement-pending' );
	}
};

/**
 * Check if input is pending.
 *
 * @return {boolean}
 */
OO.ui.PendingElement.prototype.isPending = function () {
	return !!this.pending;
};

/**
 * Increase the pending stack.
 *
 * @chainable
 */
OO.ui.PendingElement.prototype.pushPending = function () {
	if ( this.pending === 0 ) {
		this.$pending.addClass( 'oo-ui-pendingElement-pending' );
		this.updateThemeClasses();
	}
	this.pending++;

	return this;
};

/**
 * Reduce the pending stack.
 *
 * Clamped at zero.
 *
 * @chainable
 */
OO.ui.PendingElement.prototype.popPending = function () {
	if ( this.pending === 1 ) {
		this.$pending.removeClass( 'oo-ui-pendingElement-pending' );
		this.updateThemeClasses();
	}
	this.pending = Math.max( 0, this.pending - 1 );

	return this;
};

/**
 * List of actions.
 *
 * @abstract
 * @class
 * @mixins OO.EventEmitter
 *
 * @constructor
 * @param {Object} [config] Configuration options
 */
OO.ui.ActionSet = function OoUiActionSet( config ) {
	// Configuration initialization
	config = config || {};

	// Mixin constructors
	OO.EventEmitter.call( this );

	// Properties
	this.list = [];
	this.categories = {
		actions: 'getAction',
		flags: 'getFlags',
		modes: 'getModes'
	};
	this.categorized = {};
	this.special = {};
	this.others = [];
	this.organized = false;
	this.changing = false;
	this.changed = false;
};

/* Setup */

OO.mixinClass( OO.ui.ActionSet, OO.EventEmitter );

/* Static Properties */

/**
 * Symbolic name of dialog.
 *
 * @abstract
 * @static
 * @inheritable
 * @property {string}
 */
OO.ui.ActionSet.static.specialFlags = [ 'safe', 'primary' ];

/* Events */

/**
 * @event click
 * @param {OO.ui.ActionWidget} action Action that was clicked
 */

/**
 * @event resize
 * @param {OO.ui.ActionWidget} action Action that was resized
 */

/**
 * @event add
 * @param {OO.ui.ActionWidget[]} added Actions added
 */

/**
 * @event remove
 * @param {OO.ui.ActionWidget[]} added Actions removed
 */

/**
 * @event change
 */

/* Methods */

/**
 * Handle action change events.
 *
 * @fires change
 */
OO.ui.ActionSet.prototype.onActionChange = function () {
	this.organized = false;
	if ( this.changing ) {
		this.changed = true;
	} else {
		this.emit( 'change' );
	}
};

/**
 * Check if a action is one of the special actions.
 *
 * @param {OO.ui.ActionWidget} action Action to check
 * @return {boolean} Action is special
 */
OO.ui.ActionSet.prototype.isSpecial = function ( action ) {
	var flag;

	for ( flag in this.special ) {
		if ( action === this.special[flag] ) {
			return true;
		}
	}

	return false;
};

/**
 * Get actions.
 *
 * @param {Object} [filters] Filters to use, omit to get all actions
 * @param {string|string[]} [filters.actions] Actions that actions must have
 * @param {string|string[]} [filters.flags] Flags that actions must have
 * @param {string|string[]} [filters.modes] Modes that actions must have
 * @param {boolean} [filters.visible] Actions must be visible
 * @param {boolean} [filters.disabled] Actions must be disabled
 * @return {OO.ui.ActionWidget[]} Actions matching all criteria
 */
OO.ui.ActionSet.prototype.get = function ( filters ) {
	var i, len, list, category, actions, index, match, matches;

	if ( filters ) {
		this.organize();

		// Collect category candidates
		matches = [];
		for ( category in this.categorized ) {
			list = filters[category];
			if ( list ) {
				if ( !Array.isArray( list ) ) {
					list = [ list ];
				}
				for ( i = 0, len = list.length; i < len; i++ ) {
					actions = this.categorized[category][list[i]];
					if ( Array.isArray( actions ) ) {
						matches.push.apply( matches, actions );
					}
				}
			}
		}
		// Remove by boolean filters
		for ( i = 0, len = matches.length; i < len; i++ ) {
			match = matches[i];
			if (
				( filters.visible !== undefined && match.isVisible() !== filters.visible ) ||
				( filters.disabled !== undefined && match.isDisabled() !== filters.disabled )
			) {
				matches.splice( i, 1 );
				len--;
				i--;
			}
		}
		// Remove duplicates
		for ( i = 0, len = matches.length; i < len; i++ ) {
			match = matches[i];
			index = matches.lastIndexOf( match );
			while ( index !== i ) {
				matches.splice( index, 1 );
				len--;
				index = matches.lastIndexOf( match );
			}
		}
		return matches;
	}
	return this.list.slice();
};

/**
 * Get special actions.
 *
 * Special actions are the first visible actions with special flags, such as 'safe' and 'primary'.
 * Special flags can be configured by changing #static-specialFlags in a subclass.
 *
 * @return {OO.ui.ActionWidget|null} Safe action
 */
OO.ui.ActionSet.prototype.getSpecial = function () {
	this.organize();
	return $.extend( {}, this.special );
};

/**
 * Get other actions.
 *
 * Other actions include all non-special visible actions.
 *
 * @return {OO.ui.ActionWidget[]} Other actions
 */
OO.ui.ActionSet.prototype.getOthers = function () {
	this.organize();
	return this.others.slice();
};

/**
 * Toggle actions based on their modes.
 *
 * Unlike calling toggle on actions with matching flags, this will enforce mutually exclusive
 * visibility; matching actions will be shown, non-matching actions will be hidden.
 *
 * @param {string} mode Mode actions must have
 * @chainable
 * @fires toggle
 * @fires change
 */
OO.ui.ActionSet.prototype.setMode = function ( mode ) {
	var i, len, action;

	this.changing = true;
	for ( i = 0, len = this.list.length; i < len; i++ ) {
		action = this.list[i];
		action.toggle( action.hasMode( mode ) );
	}

	this.organized = false;
	this.changing = false;
	this.emit( 'change' );

	return this;
};

/**
 * Change which actions are able to be performed.
 *
 * Actions with matching actions will be disabled/enabled. Other actions will not be changed.
 *
 * @param {Object.<string,boolean>} actions List of abilities, keyed by action name, values
 *   indicate actions are able to be performed
 * @chainable
 */
OO.ui.ActionSet.prototype.setAbilities = function ( actions ) {
	var i, len, action, item;

	for ( i = 0, len = this.list.length; i < len; i++ ) {
		item = this.list[i];
		action = item.getAction();
		if ( actions[action] !== undefined ) {
			item.setDisabled( !actions[action] );
		}
	}

	return this;
};

/**
 * Executes a function once per action.
 *
 * When making changes to multiple actions, use this method instead of iterating over the actions
 * manually to defer emitting a change event until after all actions have been changed.
 *
 * @param {Object|null} actions Filters to use for which actions to iterate over; see #get
 * @param {Function} callback Callback to run for each action; callback is invoked with three
 *   arguments: the action, the action's index, the list of actions being iterated over
 * @chainable
 */
OO.ui.ActionSet.prototype.forEach = function ( filter, callback ) {
	this.changed = false;
	this.changing = true;
	this.get( filter ).forEach( callback );
	this.changing = false;
	if ( this.changed ) {
		this.emit( 'change' );
	}

	return this;
};

/**
 * Add actions.
 *
 * @param {OO.ui.ActionWidget[]} actions Actions to add
 * @chainable
 * @fires add
 * @fires change
 */
OO.ui.ActionSet.prototype.add = function ( actions ) {
	var i, len, action;

	this.changing = true;
	for ( i = 0, len = actions.length; i < len; i++ ) {
		action = actions[i];
		action.connect( this, {
			click: [ 'emit', 'click', action ],
			resize: [ 'emit', 'resize', action ],
			toggle: [ 'onActionChange' ]
		} );
		this.list.push( action );
	}
	this.organized = false;
	this.emit( 'add', actions );
	this.changing = false;
	this.emit( 'change' );

	return this;
};

/**
 * Remove actions.
 *
 * @param {OO.ui.ActionWidget[]} actions Actions to remove
 * @chainable
 * @fires remove
 * @fires change
 */
OO.ui.ActionSet.prototype.remove = function ( actions ) {
	var i, len, index, action;

	this.changing = true;
	for ( i = 0, len = actions.length; i < len; i++ ) {
		action = actions[i];
		index = this.list.indexOf( action );
		if ( index !== -1 ) {
			action.disconnect( this );
			this.list.splice( index, 1 );
		}
	}
	this.organized = false;
	this.emit( 'remove', actions );
	this.changing = false;
	this.emit( 'change' );

	return this;
};

/**
 * Remove all actions.
 *
 * @chainable
 * @fires remove
 * @fires change
 */
OO.ui.ActionSet.prototype.clear = function () {
	var i, len, action,
		removed = this.list.slice();

	this.changing = true;
	for ( i = 0, len = this.list.length; i < len; i++ ) {
		action = this.list[i];
		action.disconnect( this );
	}

	this.list = [];

	this.organized = false;
	this.emit( 'remove', removed );
	this.changing = false;
	this.emit( 'change' );

	return this;
};

/**
 * Organize actions.
 *
 * This is called whenever organized information is requested. It will only reorganize the actions
 * if something has changed since the last time it ran.
 *
 * @private
 * @chainable
 */
OO.ui.ActionSet.prototype.organize = function () {
	var i, iLen, j, jLen, flag, action, category, list, item, special,
		specialFlags = this.constructor.static.specialFlags;

	if ( !this.organized ) {
		this.categorized = {};
		this.special = {};
		this.others = [];
		for ( i = 0, iLen = this.list.length; i < iLen; i++ ) {
			action = this.list[i];
			if ( action.isVisible() ) {
				// Populate categories
				for ( category in this.categories ) {
					if ( !this.categorized[category] ) {
						this.categorized[category] = {};
					}
					list = action[this.categories[category]]();
					if ( !Array.isArray( list ) ) {
						list = [ list ];
					}
					for ( j = 0, jLen = list.length; j < jLen; j++ ) {
						item = list[j];
						if ( !this.categorized[category][item] ) {
							this.categorized[category][item] = [];
						}
						this.categorized[category][item].push( action );
					}
				}
				// Populate special/others
				special = false;
				for ( j = 0, jLen = specialFlags.length; j < jLen; j++ ) {
					flag = specialFlags[j];
					if ( !this.special[flag] && action.hasFlag( flag ) ) {
						this.special[flag] = action;
						special = true;
						break;
					}
				}
				if ( !special ) {
					this.others.push( action );
				}
			}
		}
		this.organized = true;
	}

	return this;
};

/**
 * DOM element abstraction.
 *
 * @abstract
 * @class
 *
 * @constructor
 * @param {Object} [config] Configuration options
 * @cfg {Function} [$] jQuery for the frame the widget is in
 * @cfg {string[]} [classes] CSS class names to add
 * @cfg {string} [text] Text to insert
 * @cfg {jQuery} [$content] Content elements to append (after text)
 * @cfg {Mixed} [data] Element data
 */
OO.ui.Element = function OoUiElement( config ) {
	// Configuration initialization
	config = config || {};

	// Properties
	this.$ = config.$ || OO.ui.Element.getJQuery( document );
	this.data = config.data;
	this.$element = this.$( this.$.context.createElement( this.getTagName() ) );
	this.elementGroup = null;
	this.debouncedUpdateThemeClassesHandler = this.debouncedUpdateThemeClasses.bind( this );
	this.updateThemeClassesPending = false;

	// Initialization
	if ( $.isArray( config.classes ) ) {
		this.$element.addClass( config.classes.join( ' ' ) );
	}
	if ( config.text ) {
		this.$element.text( config.text );
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
 * This may be ignored if #getTagName is overridden.
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
 * @param {jQuery} [$iframe] HTML iframe element that contains the document, omit if document is
 *   not in an iframe
 * @return {Function} Bound jQuery function
 */
OO.ui.Element.getJQuery = function ( context, $iframe ) {
	function wrapper( selector ) {
		return $( selector, wrapper.context );
	}

	wrapper.context = this.getDocument( context );

	if ( $iframe ) {
		wrapper.$iframe = $iframe;
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
 * @return {string} Text direction, either 'ltr' or 'rtl'
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
		offset = { top: 0, left: 0 };
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
 * The two elements may be in a different frame, but in that case the frame $element is in must
 * be contained in the frame $anchor is in.
 *
 * @static
 * @param {jQuery} $element Element whose position to get
 * @param {jQuery} $anchor Element to get $element's position relative to
 * @return {Object} Translated position coordinates, containing top and left properties
 */
OO.ui.Element.getRelativePosition = function ( $element, $anchor ) {
	var iframe, iframePos,
		pos = $element.offset(),
		anchorPos = $anchor.offset(),
		elementDocument = this.getDocument( $element ),
		anchorDocument = this.getDocument( $anchor );

	// If $element isn't in the same document as $anchor, traverse up
	while ( elementDocument !== anchorDocument ) {
		iframe = elementDocument.defaultView.frameElement;
		if ( !iframe ) {
			throw new Error( '$element frame is not contained in $anchor frame' );
		}
		iframePos = $( iframe ).offset();
		pos.left += iframePos.left;
		pos.top += iframePos.top;
		elementDocument = iframe.ownerDocument;
	}
	pos.left -= anchorPos.left;
	pos.top -= anchorPos.top;
	return pos;
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
		top: Math.round( top ),
		left: Math.round( left ),
		bottom: Math.round( bottom ),
		right: Math.round( right )
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
			borders: { top: 0, left: 0, bottom: 0, right: 0 },
			scroll: {
				top: $win.scrollTop(),
				left: $win.scrollLeft()
			},
			scrollbar: { right: 0, bottom: 0 },
			rect: {
				top: 0,
				left: 0,
				bottom: $win.innerHeight(),
				right: $win.innerWidth()
			}
		};
	} else {
		$el = $( el );
		return {
			borders: this.getBorders( el ),
			scroll: {
				top: $el.scrollTop(),
				left: $el.scrollLeft()
			},
			scrollbar: {
				right: $el.innerWidth() - el.clientWidth,
				bottom: $el.innerHeight() - el.clientHeight
			},
			rect: el.getBoundingClientRect()
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
 * @return {HTMLElement} Closest scrollable container
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
 * @param {Object} [config] Configuration options
 * @param {string} [config.duration] jQuery animation duration value
 * @param {string} [config.direction] Scroll in only one direction, e.g. 'x' or 'y', omit
 *  to scroll in both directions
 * @param {Function} [config.complete] Function to call when scrolling completes
 */
OO.ui.Element.scrollIntoView = function ( el, config ) {
	// Configuration initialization
	config = config || {};

	var rel, anim = {},
		callback = typeof config.complete === 'function' && config.complete,
		sc = this.getClosestScrollableContainer( el, config.direction ),
		$sc = $( sc ),
		eld = this.getDimensions( el ),
		scd = this.getDimensions( sc ),
		$win = $( this.getWindow( el ) );

	// Compute the distances between the edges of el and the edges of the scroll viewport
	if ( $sc.is( 'body' ) ) {
		// If the scrollable container is the <body> this is easy
		rel = {
			top: eld.rect.top,
			bottom: $win.innerHeight() - eld.rect.bottom,
			left: eld.rect.left,
			right: $win.innerWidth() - eld.rect.right
		};
	} else {
		// Otherwise, we have to subtract el's coordinates from sc's coordinates
		rel = {
			top: eld.rect.top - ( scd.rect.top + scd.borders.top ),
			bottom: scd.rect.bottom - scd.borders.bottom - scd.scrollbar.bottom - eld.rect.bottom,
			left: eld.rect.left - ( scd.rect.left + scd.borders.left ),
			right: scd.rect.right - scd.borders.right - scd.scrollbar.right - eld.rect.right
		};
	}

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

/**
 * Bind a handler for an event on a DOM element.
 *
 * Used to be for working around a jQuery bug (jqbug.com/14180),
 * but obsolete as of jQuery 1.11.0.
 *
 * @static
 * @deprecated Use jQuery#on instead.
 * @param {HTMLElement|jQuery} el DOM element
 * @param {string} event Event to bind
 * @param {Function} callback Callback to call when the event fires
 */
OO.ui.Element.onDOMEvent = function ( el, event, callback ) {
	$( el ).on( event, callback );
};

/**
 * Unbind a handler bound with #static-method-onDOMEvent.
 *
 * @deprecated Use jQuery#off instead.
 * @static
 * @param {HTMLElement|jQuery} el DOM element
 * @param {string} event Event to unbind
 * @param {Function} [callback] Callback to unbind
 */
OO.ui.Element.offDOMEvent = function ( el, event, callback ) {
	$( el ).off( event, callback );
};

/* Methods */

/**
 * Get element data.
 *
 * @return {Mixed} Element data
 */
OO.ui.Element.prototype.getData = function () {
	return this.data;
};

/**
 * Set element data.
 *
 * @param {Mixed} Element data
 * @chainable
 */
OO.ui.Element.prototype.setData = function ( data ) {
	this.data = data;
	return this;
};

/**
 * Check if element supports one or more methods.
 *
 * @param {string|string[]} methods Method or list of methods to check
 * @return {boolean} All methods are supported
 */
OO.ui.Element.prototype.supports = function ( methods ) {
	var i, len,
		support = 0;

	methods = $.isArray( methods ) ? methods : [ methods ];
	for ( i = 0, len = methods.length; i < len; i++ ) {
		if ( $.isFunction( this[methods[i]] ) ) {
			support++;
		}
	}

	return methods.length === support;
};

/**
 * Update the theme-provided classes.
 *
 * @localdoc This is called in element mixins and widget classes any time state changes.
 *   Updating is debounced, minimizing overhead of changing multiple attributes and
 *   guaranteeing that theme updates do not occur within an element's constructor
 */
OO.ui.Element.prototype.updateThemeClasses = function () {
	if ( !this.updateThemeClassesPending ) {
		this.updateThemeClassesPending = true;
		setTimeout( this.debouncedUpdateThemeClassesHandler );
	}
};

/**
 * @private
 */
OO.ui.Element.prototype.debouncedUpdateThemeClasses = function () {
	OO.ui.theme.updateElementClasses( this );
	this.updateThemeClassesPending = false;
};

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
 * @param {Object} [config] Configuration options
 */
OO.ui.Element.prototype.scrollElementIntoView = function ( config ) {
	return OO.ui.Element.scrollIntoView( this.$element[0], config );
};

/**
 * Bind a handler for an event on this.$element
 *
 * @deprecated Use jQuery#on instead.
 * @param {string} event
 * @param {Function} callback
 */
OO.ui.Element.prototype.onDOMEvent = function ( event, callback ) {
	OO.ui.Element.onDOMEvent( this.$element, event, callback );
};

/**
 * Unbind a handler bound with #offDOMEvent
 *
 * @deprecated Use jQuery#off instead.
 * @param {string} event
 * @param {Function} callback
 */
OO.ui.Element.prototype.offDOMEvent = function ( event, callback ) {
	OO.ui.Element.offDOMEvent( this.$element, event, callback );
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
	// Configuration initialization
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
	config = $.extend( { disabled: false }, config );

	// Parent constructor
	OO.ui.Widget.super.call( this, config );

	// Mixin constructors
	OO.EventEmitter.call( this );

	// Properties
	this.visible = true;
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

/**
 * @event toggle
 * @param {boolean} visible Widget is visible
 */

/* Methods */

/**
 * Check if the widget is disabled.
 *
 * @return {boolean} Button is disabled
 */
OO.ui.Widget.prototype.isDisabled = function () {
	return this.disabled;
};

/**
 * Check if widget is visible.
 *
 * @return {boolean} Widget is visible
 */
OO.ui.Widget.prototype.isVisible = function () {
	return this.visible;
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
		this.updateThemeClasses();
	}
	this.wasDisabled = isDisabled;

	return this;
};

/**
 * Toggle visibility of widget.
 *
 * @param {boolean} [show] Make widget visible, omit to toggle visibility
 * @fires visible
 * @chainable
 */
OO.ui.Widget.prototype.toggle = function ( show ) {
	show = show === undefined ? !this.visible : !!show;

	if ( show !== this.isVisible() ) {
		this.visible = show;
		this.$element.toggle( show );
		this.emit( 'toggle', show );
	}

	return this;
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
 * Container for elements in a child frame.
 *
 * Use together with OO.ui.WindowManager.
 *
 * @abstract
 * @class
 * @extends OO.ui.Element
 * @mixins OO.EventEmitter
 *
 * When a window is opened, the setup and ready processes are executed. Similarly, the hold and
 * teardown processes are executed when the window is closed.
 *
 * - {@link OO.ui.WindowManager#openWindow} or {@link #open} methods are used to start opening
 * - Window manager begins opening window
 * - {@link #getSetupProcess} method is called and its result executed
 * - {@link #getReadyProcess} method is called and its result executed
 * - Window is now open
 *
 * - {@link OO.ui.WindowManager#closeWindow} or {@link #close} methods are used to start closing
 * - Window manager begins closing window
 * - {@link #getHoldProcess} method is called and its result executed
 * - {@link #getTeardownProcess} method is called and its result executed
 * - Window is now closed
 *
 * Each process (setup, ready, hold and teardown) can be extended in subclasses by overriding
 * {@link #getSetupProcess}, {@link #getReadyProcess}, {@link #getHoldProcess} and
 * {@link #getTeardownProcess} respectively. Each process is executed in series, so asynchronous
 * processing can complete. Always assume window processes are executed asynchronously. See
 * OO.ui.Process for more details about how to work with processes. Some events, as well as the
 * #open and #close methods, provide promises which are resolved when the window enters a new state.
 *
 * Sizing of windows is specified using symbolic names which are interpreted by the window manager.
 * If the requested size is not recognized, the window manager will choose a sensible fallback.
 *
 * @constructor
 * @param {Object} [config] Configuration options
 * @cfg {string} [size] Symbolic name of dialog size, `small`, `medium`, `large` or `full`; omit to
 *   use #static-size
 */
OO.ui.Window = function OoUiWindow( config ) {
	// Configuration initialization
	config = config || {};

	// Parent constructor
	OO.ui.Window.super.call( this, config );

	// Mixin constructors
	OO.EventEmitter.call( this );

	// Properties
	this.manager = null;
	this.initialized = false;
	this.visible = false;
	this.opening = null;
	this.closing = null;
	this.opened = null;
	this.timing = null;
	this.loading = null;
	this.size = config.size || this.constructor.static.size;
	this.$frame = this.$( '<div>' );
	this.$overlay = this.$( '<div>' );

	// Initialization
	this.$element
		.addClass( 'oo-ui-window' )
		.append( this.$frame, this.$overlay );
	this.$frame.addClass( 'oo-ui-window-frame' );
	this.$overlay.addClass( 'oo-ui-window-overlay' );

	// NOTE: Additional initialization will occur when #setManager is called
};

/* Setup */

OO.inheritClass( OO.ui.Window, OO.ui.Element );
OO.mixinClass( OO.ui.Window, OO.EventEmitter );

/* Static Properties */

/**
 * Symbolic name of size.
 *
 * Size is used if no size is configured during construction.
 *
 * @static
 * @inheritable
 * @property {string}
 */
OO.ui.Window.static.size = 'medium';

/* Static Methods */

/**
 * Transplant the CSS styles from as parent document to a frame's document.
 *
 * This loops over the style sheets in the parent document, and copies their nodes to the
 * frame's document. It then polls the document to see when all styles have loaded, and once they
 * have, resolves the promise.
 *
 * If the styles still haven't loaded after a long time (5 seconds by default), we give up waiting
 * and resolve the promise anyway. This protects against cases like a display: none; iframe in
 * Firefox, where the styles won't load until the iframe becomes visible.
 *
 * For details of how we arrived at the strategy used in this function, see #load.
 *
 * @static
 * @inheritable
 * @param {HTMLDocument} parentDoc Document to transplant styles from
 * @param {HTMLDocument} frameDoc Document to transplant styles to
 * @param {number} [timeout=5000] How long to wait before giving up (in ms). If 0, never give up.
 * @return {jQuery.Promise} Promise resolved when styles have loaded
 */
OO.ui.Window.static.transplantStyles = function ( parentDoc, frameDoc, timeout ) {
	var i, numSheets, styleNode, styleText, newNode, timeoutID, pollNodeId, $pendingPollNodes,
		$pollNodes = $( [] ),
		// Fake font-family value
		fontFamily = 'oo-ui-frame-transplantStyles-loaded',
		nextIndex = parentDoc.oouiFrameTransplantStylesNextIndex || 0,
		deferred = $.Deferred();

	for ( i = 0, numSheets = parentDoc.styleSheets.length; i < numSheets; i++ ) {
		styleNode = parentDoc.styleSheets[i].ownerNode;
		if ( styleNode.disabled ) {
			continue;
		}

		if ( styleNode.nodeName.toLowerCase() === 'link' ) {
			// External stylesheet; use @import
			styleText = '@import url(' + styleNode.href + ');';
		} else {
			// Internal stylesheet; just copy the text
			// For IE10 we need to fall back to .cssText, BUT that's undefined in
			// other browsers, so fall back to '' rather than 'undefined'
			styleText = styleNode.textContent || parentDoc.styleSheets[i].cssText || '';
		}

		// Create a node with a unique ID that we're going to monitor to see when the CSS
		// has loaded
		if ( styleNode.oouiFrameTransplantStylesId ) {
			// If we're nesting transplantStyles operations and this node already has
			// a CSS rule to wait for loading, reuse it
			pollNodeId = styleNode.oouiFrameTransplantStylesId;
		} else {
			// Otherwise, create a new ID
			pollNodeId = 'oo-ui-frame-transplantStyles-loaded-' + nextIndex;
			nextIndex++;

			// Add #pollNodeId { font-family: ... } to the end of the stylesheet / after the @import
			// The font-family rule will only take effect once the @import finishes
			styleText += '\n' + '#' + pollNodeId + ' { font-family: ' + fontFamily + '; }';
		}

		// Create a node with id=pollNodeId
		$pollNodes = $pollNodes.add( $( '<div>', frameDoc )
			.attr( 'id', pollNodeId )
			.appendTo( frameDoc.body )
		);

		// Add our modified CSS as a <style> tag
		newNode = frameDoc.createElement( 'style' );
		newNode.textContent = styleText;
		newNode.oouiFrameTransplantStylesId = pollNodeId;
		frameDoc.head.appendChild( newNode );
	}
	frameDoc.oouiFrameTransplantStylesNextIndex = nextIndex;

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
				deferred.resolve();
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
				deferred.reject();
			}
		}, timeout || 5000 );
	}

	return deferred.promise();
};

/* Methods */

/**
 * Handle mouse down events.
 *
 * @param {jQuery.Event} e Mouse down event
 */
OO.ui.Window.prototype.onMouseDown = function ( e ) {
	// Prevent clicking on the click-block from stealing focus
	if ( e.target === this.$element[0] ) {
		return false;
	}
};

/**
 * Check if window has been initialized.
 *
 * @return {boolean} Window has been initialized
 */
OO.ui.Window.prototype.isInitialized = function () {
	return this.initialized;
};

/**
 * Check if window is visible.
 *
 * @return {boolean} Window is visible
 */
OO.ui.Window.prototype.isVisible = function () {
	return this.visible;
};

/**
 * Check if window is loading.
 *
 * @return {boolean} Window is loading
 */
OO.ui.Window.prototype.isLoading = function () {
	return this.loading && this.loading.state() === 'pending';
};

/**
 * Check if window is loaded.
 *
 * @return {boolean} Window is loaded
 */
OO.ui.Window.prototype.isLoaded = function () {
	return this.loading && this.loading.state() === 'resolved';
};

/**
 * Check if window is opening.
 *
 * This is a wrapper around OO.ui.WindowManager#isOpening.
 *
 * @return {boolean} Window is opening
 */
OO.ui.Window.prototype.isOpening = function () {
	return this.manager.isOpening( this );
};

/**
 * Check if window is closing.
 *
 * This is a wrapper around OO.ui.WindowManager#isClosing.
 *
 * @return {boolean} Window is closing
 */
OO.ui.Window.prototype.isClosing = function () {
	return this.manager.isClosing( this );
};

/**
 * Check if window is opened.
 *
 * This is a wrapper around OO.ui.WindowManager#isOpened.
 *
 * @return {boolean} Window is opened
 */
OO.ui.Window.prototype.isOpened = function () {
	return this.manager.isOpened( this );
};

/**
 * Get the window manager.
 *
 * @return {OO.ui.WindowManager} Manager of window
 */
OO.ui.Window.prototype.getManager = function () {
	return this.manager;
};

/**
 * Get the window size.
 *
 * @return {string} Symbolic size name, e.g. 'small', 'medium', 'large', 'full'
 */
OO.ui.Window.prototype.getSize = function () {
	return this.size;
};

/**
 * Disable transitions on window's frame for the duration of the callback function, then enable them
 * back.
 *
 * @private
 * @param {Function} callback Function to call while transitions are disabled
 */
OO.ui.Window.prototype.withoutSizeTransitions = function ( callback ) {
	// Temporarily resize the frame so getBodyHeight() can use scrollHeight measurements.
	// Disable transitions first, otherwise we'll get values from when the window was animating.
	var oldTransition,
		styleObj = this.$frame[0].style;
	oldTransition = styleObj.transition || styleObj.OTransition || styleObj.MsTransition ||
		styleObj.MozTransition || styleObj.WebkitTransition;
	styleObj.transition = styleObj.OTransition = styleObj.MsTransition =
		styleObj.MozTransition = styleObj.WebkitTransition = 'none';
	callback();
	// Force reflow to make sure the style changes done inside callback really are not transitioned
	this.$frame.height();
	styleObj.transition = styleObj.OTransition = styleObj.MsTransition =
		styleObj.MozTransition = styleObj.WebkitTransition = oldTransition;
};

/**
 * Get the height of the dialog contents.
 *
 * @return {number} Content height
 */
OO.ui.Window.prototype.getContentHeight = function () {
	var bodyHeight,
		win = this,
		styleObj = this.$frame[0].style;

	// Temporarily resize the frame so getBodyHeight() can use scrollHeight measurements.
	// Disable transitions first, otherwise we'll get values from when the window was animating.
	this.withoutSizeTransitions( function () {
		var oldHeight = styleObj.height;
		styleObj.height = '1px';
		bodyHeight = win.getBodyHeight();
		styleObj.height = oldHeight;
	} );

	return Math.round(
		// Add buffer for border
		( this.$frame.outerHeight() - this.$frame.innerHeight() ) +
		// Use combined heights of children
		( this.$head.outerHeight( true ) + bodyHeight + this.$foot.outerHeight( true ) )
	);
};

/**
 * Get the height of the dialog contents.
 *
 * When this function is called, the dialog will temporarily have been resized
 * to height=1px, so .scrollHeight measurements can be taken accurately.
 *
 * @return {number} Height of content
 */
OO.ui.Window.prototype.getBodyHeight = function () {
	return this.$body[0].scrollHeight;
};

/**
 * Get the directionality of the frame
 *
 * @return {string} Directionality, 'ltr' or 'rtl'
 */
OO.ui.Window.prototype.getDir = function () {
	return this.dir;
};

/**
 * Get a process for setting up a window for use.
 *
 * Each time the window is opened this process will set it up for use in a particular context, based
 * on the `data` argument.
 *
 * When you override this method, you can add additional setup steps to the process the parent
 * method provides using the 'first' and 'next' methods.
 *
 * @abstract
 * @param {Object} [data] Window opening data
 * @return {OO.ui.Process} Setup process
 */
OO.ui.Window.prototype.getSetupProcess = function () {
	return new OO.ui.Process();
};

/**
 * Get a process for readying a window for use.
 *
 * Each time the window is open and setup, this process will ready it up for use in a particular
 * context, based on the `data` argument.
 *
 * When you override this method, you can add additional setup steps to the process the parent
 * method provides using the 'first' and 'next' methods.
 *
 * @abstract
 * @param {Object} [data] Window opening data
 * @return {OO.ui.Process} Setup process
 */
OO.ui.Window.prototype.getReadyProcess = function () {
	return new OO.ui.Process();
};

/**
 * Get a process for holding a window from use.
 *
 * Each time the window is closed, this process will hold it from use in a particular context, based
 * on the `data` argument.
 *
 * When you override this method, you can add additional setup steps to the process the parent
 * method provides using the 'first' and 'next' methods.
 *
 * @abstract
 * @param {Object} [data] Window closing data
 * @return {OO.ui.Process} Hold process
 */
OO.ui.Window.prototype.getHoldProcess = function () {
	return new OO.ui.Process();
};

/**
 * Get a process for tearing down a window after use.
 *
 * Each time the window is closed this process will tear it down and do something with the user's
 * interactions within the window, based on the `data` argument.
 *
 * When you override this method, you can add additional teardown steps to the process the parent
 * method provides using the 'first' and 'next' methods.
 *
 * @abstract
 * @param {Object} [data] Window closing data
 * @return {OO.ui.Process} Teardown process
 */
OO.ui.Window.prototype.getTeardownProcess = function () {
	return new OO.ui.Process();
};

/**
 * Toggle visibility of window.
 *
 * If the window is isolated and hasn't fully loaded yet, the visibility property will be used
 * instead of display.
 *
 * @param {boolean} [show] Make window visible, omit to toggle visibility
 * @fires toggle
 * @chainable
 */
OO.ui.Window.prototype.toggle = function ( show ) {
	show = show === undefined ? !this.visible : !!show;

	if ( show !== this.isVisible() ) {
		this.visible = show;

		if ( this.isolated && !this.isLoaded() ) {
			// Hide the window using visibility instead of display until loading is complete
			// Can't use display: none; because that prevents the iframe from loading in Firefox
			this.$element.css( 'visibility', show ? 'visible' : 'hidden' );
		} else {
			this.$element.toggle( show ).css( 'visibility', '' );
		}
		this.emit( 'toggle', show );
	}

	return this;
};

/**
 * Set the window manager.
 *
 * This must be called before initialize. Calling it more than once will cause an error.
 *
 * @param {OO.ui.WindowManager} manager Manager for this window
 * @throws {Error} If called more than once
 * @chainable
 */
OO.ui.Window.prototype.setManager = function ( manager ) {
	if ( this.manager ) {
		throw new Error( 'Cannot set window manager, window already has a manager' );
	}

	// Properties
	this.manager = manager;
	this.isolated = manager.shouldIsolate();

	// Initialization
	if ( this.isolated ) {
		this.$iframe = this.$( '<iframe>' );
		this.$iframe.attr( { frameborder: 0, scrolling: 'no' } );
		this.$frame.append( this.$iframe );
		this.$ = function () {
			throw new Error( 'this.$() cannot be used until the frame has been initialized.' );
		};
		// WARNING: Do not use this.$ again until #initialize is called
	} else {
		this.$content = this.$( '<div>' );
		this.$document = $( this.getElementDocument() );
		this.$content.addClass( 'oo-ui-window-content' );
		this.$frame.append( this.$content );
	}
	this.toggle( false );

	// Figure out directionality:
	this.dir = OO.ui.Element.getDir( this.$iframe || this.$content ) || 'ltr';

	return this;
};

/**
 * Set the window size.
 *
 * @param {string} size Symbolic size name, e.g. 'small', 'medium', 'large', 'full'
 * @chainable
 */
OO.ui.Window.prototype.setSize = function ( size ) {
	this.size = size;
	this.manager.updateWindowSize( this );
	return this;
};

/**
 * Set window dimensions.
 *
 * Properties are applied to the frame container.
 *
 * @param {Object} dim CSS dimension properties
 * @param {string|number} [dim.width] Width
 * @param {string|number} [dim.minWidth] Minimum width
 * @param {string|number} [dim.maxWidth] Maximum width
 * @param {string|number} [dim.width] Height, omit to set based on height of contents
 * @param {string|number} [dim.minWidth] Minimum height
 * @param {string|number} [dim.maxWidth] Maximum height
 * @chainable
 */
OO.ui.Window.prototype.setDimensions = function ( dim ) {
	var height,
		win = this,
		styleObj = this.$frame[0].style;

	// Calculate the height we need to set using the correct width
	if ( dim.height === undefined ) {
		this.withoutSizeTransitions( function () {
			var oldWidth = styleObj.width;
			win.$frame.css( 'width', dim.width || '' );
			height = win.getContentHeight();
			styleObj.width = oldWidth;
		} );
	} else {
		height = dim.height;
	}

	this.$frame.css( {
		width: dim.width || '',
		minWidth: dim.minWidth || '',
		maxWidth: dim.maxWidth || '',
		height: height || '',
		minHeight: dim.minHeight || '',
		maxHeight: dim.maxHeight || ''
	} );

	return this;
};

/**
 * Initialize window contents.
 *
 * The first time the window is opened, #initialize is called when it's safe to begin populating
 * its contents. See #getSetupProcess for a way to make changes each time the window opens.
 *
 * Once this method is called, this.$ can be used to create elements within the frame.
 *
 * @throws {Error} If not attached to a manager
 * @chainable
 */
OO.ui.Window.prototype.initialize = function () {
	if ( !this.manager ) {
		throw new Error( 'Cannot initialize window, must be attached to a manager' );
	}

	// Properties
	this.$head = this.$( '<div>' );
	this.$body = this.$( '<div>' );
	this.$foot = this.$( '<div>' );
	this.$innerOverlay = this.$( '<div>' );

	// Events
	this.$element.on( 'mousedown', this.onMouseDown.bind( this ) );

	// Initialization
	this.$head.addClass( 'oo-ui-window-head' );
	this.$body.addClass( 'oo-ui-window-body' );
	this.$foot.addClass( 'oo-ui-window-foot' );
	this.$innerOverlay.addClass( 'oo-ui-window-inner-overlay' );
	this.$content.append( this.$head, this.$body, this.$foot, this.$innerOverlay );

	return this;
};

/**
 * Open window.
 *
 * This is a wrapper around calling {@link OO.ui.WindowManager#openWindow} on the window manager.
 * To do something each time the window opens, use #getSetupProcess or #getReadyProcess.
 *
 * @param {Object} [data] Window opening data
 * @return {jQuery.Promise} Promise resolved when window is opened; when the promise is resolved the
 *   first argument will be a promise which will be resolved when the window begins closing
 */
OO.ui.Window.prototype.open = function ( data ) {
	return this.manager.openWindow( this, data );
};

/**
 * Close window.
 *
 * This is a wrapper around calling OO.ui.WindowManager#closeWindow on the window manager.
 * To do something each time the window closes, use #getHoldProcess or #getTeardownProcess.
 *
 * @param {Object} [data] Window closing data
 * @return {jQuery.Promise} Promise resolved when window is closed
 */
OO.ui.Window.prototype.close = function ( data ) {
	return this.manager.closeWindow( this, data );
};

/**
 * Setup window.
 *
 * This is called by OO.ui.WindowManager during window opening, and should not be called directly
 * by other systems.
 *
 * @param {Object} [data] Window opening data
 * @return {jQuery.Promise} Promise resolved when window is setup
 */
OO.ui.Window.prototype.setup = function ( data ) {
	var win = this,
		deferred = $.Deferred();

	this.$element.show();
	this.visible = true;
	this.getSetupProcess( data ).execute().done( function () {
		// Force redraw by asking the browser to measure the elements' widths
		win.$element.addClass( 'oo-ui-window-setup' ).width();
		win.$content.addClass( 'oo-ui-window-content-setup' ).width();
		deferred.resolve();
	} );

	return deferred.promise();
};

/**
 * Ready window.
 *
 * This is called by OO.ui.WindowManager during window opening, and should not be called directly
 * by other systems.
 *
 * @param {Object} [data] Window opening data
 * @return {jQuery.Promise} Promise resolved when window is ready
 */
OO.ui.Window.prototype.ready = function ( data ) {
	var win = this,
		deferred = $.Deferred();

	this.$content.focus();
	this.getReadyProcess( data ).execute().done( function () {
		// Force redraw by asking the browser to measure the elements' widths
		win.$element.addClass( 'oo-ui-window-ready' ).width();
		win.$content.addClass( 'oo-ui-window-content-ready' ).width();
		deferred.resolve();
	} );

	return deferred.promise();
};

/**
 * Hold window.
 *
 * This is called by OO.ui.WindowManager during window closing, and should not be called directly
 * by other systems.
 *
 * @param {Object} [data] Window closing data
 * @return {jQuery.Promise} Promise resolved when window is held
 */
OO.ui.Window.prototype.hold = function ( data ) {
	var win = this,
		deferred = $.Deferred();

	this.getHoldProcess( data ).execute().done( function () {
		// Get the focused element within the window's content
		var $focus = win.$content.find( OO.ui.Element.getDocument( win.$content ).activeElement );

		// Blur the focused element
		if ( $focus.length ) {
			$focus[0].blur();
		}

		// Force redraw by asking the browser to measure the elements' widths
		win.$element.removeClass( 'oo-ui-window-ready' ).width();
		win.$content.removeClass( 'oo-ui-window-content-ready' ).width();
		deferred.resolve();
	} );

	return deferred.promise();
};

/**
 * Teardown window.
 *
 * This is called by OO.ui.WindowManager during window closing, and should not be called directly
 * by other systems.
 *
 * @param {Object} [data] Window closing data
 * @return {jQuery.Promise} Promise resolved when window is torn down
 */
OO.ui.Window.prototype.teardown = function ( data ) {
	var win = this,
		deferred = $.Deferred();

	this.getTeardownProcess( data ).execute().done( function () {
		// Force redraw by asking the browser to measure the elements' widths
		win.$element.removeClass( 'oo-ui-window-load oo-ui-window-setup' ).width();
		win.$content.removeClass( 'oo-ui-window-content-setup' ).width();
		win.$element.hide();
		win.visible = false;
		deferred.resolve();
	} );

	return deferred.promise();
};

/**
 * Load the frame contents.
 *
 * Once the iframe's stylesheets are loaded the returned promise will be resolved. Calling while
 * loading will return a promise but not trigger a new loading cycle. Calling after loading is
 * complete will return a promise that's already been resolved.
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
 * The workaround is to change all `<link href="...">` tags to `<style>@import url(...)</style>`
 * tags. Because `@import` is blocking, Chrome won't add the stylesheet to document.styleSheets
 * until the `@import` has finished, and Firefox won't allow .cssRules to be accessed until the
 * `@import` has finished. And because the contents of the `<style>` tag are from the same origin,
 * accessing .cssRules is allowed.
 *
 * However, now that we control the styles we're injecting, we might as well do away with
 * browser-specific polling hacks like document.styleSheets and .cssRules, and instead inject
 * `<style>@import url(...); #foo { font-family: someValue; }</style>`, then create `<div id="foo">`
 * and wait for its font-family to change to someValue. Because `@import` is blocking, the
 * font-family rule is not applied until after the `@import` finishes.
 *
 * All this stylesheet injection and polling magic is in #transplantStyles.
 *
 * @return {jQuery.Promise} Promise resolved when loading is complete
 */
OO.ui.Window.prototype.load = function () {
	var sub, doc, loading,
		win = this;

	this.$element.addClass( 'oo-ui-window-load' );

	// Non-isolated windows are already "loaded"
	if ( !this.loading && !this.isolated ) {
		this.loading = $.Deferred().resolve();
		this.initialize();
		// Set initialized state after so sub-classes aren't confused by it being set by calling
		// their parent initialize method
		this.initialized = true;
	}

	// Return existing promise if already loading or loaded
	if ( this.loading ) {
		return this.loading.promise();
	}

	// Load the frame
	loading = this.loading = $.Deferred();
	sub = this.$iframe.prop( 'contentWindow' );
	doc = sub.document;

	// Initialize contents
	doc.open();
	doc.write(
		'<!doctype html>' +
		'<html>' +
			'<body class="oo-ui-window-isolated oo-ui-' + this.dir + '"' +
				' style="direction:' + this.dir + ';" dir="' + this.dir + '">' +
				'<div class="oo-ui-window-content"></div>' +
			'</body>' +
		'</html>'
	);
	doc.close();

	// Properties
	this.$ = OO.ui.Element.getJQuery( doc, this.$iframe );
	this.$content = this.$( '.oo-ui-window-content' ).attr( 'tabIndex', 0 );
	this.$document = this.$( doc );

	// Initialization
	this.constructor.static.transplantStyles( this.getElementDocument(), this.$document[0] )
		.always( function () {
			// Initialize isolated windows
			win.initialize();
			// Set initialized state after so sub-classes aren't confused by it being set by calling
			// their parent initialize method
			win.initialized = true;
			// Undo the visibility: hidden; hack and apply display: none;
			// We can do this safely now that the iframe has initialized
			// (don't do this from within #initialize because it has to happen
			// after the all subclasses have been handled as well).
			win.toggle( win.isVisible() );

			loading.resolve();
		} );

	return loading.promise();
};

/**
 * Base class for all dialogs.
 *
 * Logic:
 * - Manage the window (open and close, etc.).
 * - Store the internal name and display title.
 * - A stack to track one or more pending actions.
 * - Manage a set of actions that can be performed.
 * - Configure and create action widgets.
 *
 * User interface:
 * - Close the dialog with Escape key.
 * - Visually lock the dialog while an action is in
 *   progress (aka "pending").
 *
 * Subclass responsibilities:
 * - Display the title somewhere.
 * - Add content to the dialog.
 * - Provide a UI to close the dialog.
 * - Display the action widgets somewhere.
 *
 * @abstract
 * @class
 * @extends OO.ui.Window
 * @mixins OO.ui.PendingElement
 *
 * @constructor
 * @param {Object} [config] Configuration options
 */
OO.ui.Dialog = function OoUiDialog( config ) {
	// Parent constructor
	OO.ui.Dialog.super.call( this, config );

	// Mixin constructors
	OO.ui.PendingElement.call( this );

	// Properties
	this.actions = new OO.ui.ActionSet();
	this.attachedActions = [];
	this.currentAction = null;
	this.onDocumentKeyDownHandler = this.onDocumentKeyDown.bind( this );

	// Events
	this.actions.connect( this, {
		click: 'onActionClick',
		resize: 'onActionResize',
		change: 'onActionsChange'
	} );

	// Initialization
	this.$element
		.addClass( 'oo-ui-dialog' )
		.attr( 'role', 'dialog' );
};

/* Setup */

OO.inheritClass( OO.ui.Dialog, OO.ui.Window );
OO.mixinClass( OO.ui.Dialog, OO.ui.PendingElement );

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
 * Dialog title.
 *
 * @abstract
 * @static
 * @inheritable
 * @property {jQuery|string|Function} Label nodes, text or a function that returns nodes or text
 */
OO.ui.Dialog.static.title = '';

/**
 * List of OO.ui.ActionWidget configuration options.
 *
 * @static
 * inheritable
 * @property {Object[]}
 */
OO.ui.Dialog.static.actions = [];

/**
 * Close dialog when the escape key is pressed.
 *
 * @static
 * @abstract
 * @inheritable
 * @property {boolean}
 */
OO.ui.Dialog.static.escapable = true;

/* Methods */

/**
 * Handle frame document key down events.
 *
 * @param {jQuery.Event} e Key down event
 */
OO.ui.Dialog.prototype.onDocumentKeyDown = function ( e ) {
	if ( e.which === OO.ui.Keys.ESCAPE ) {
		this.close();
		return false;
	}
};

/**
 * Handle action resized events.
 *
 * @param {OO.ui.ActionWidget} action Action that was resized
 */
OO.ui.Dialog.prototype.onActionResize = function () {
	// Override in subclass
};

/**
 * Handle action click events.
 *
 * @param {OO.ui.ActionWidget} action Action that was clicked
 */
OO.ui.Dialog.prototype.onActionClick = function ( action ) {
	if ( !this.isPending() ) {
		this.currentAction = action;
		this.executeAction( action.getAction() );
	}
};

/**
 * Handle actions change event.
 */
OO.ui.Dialog.prototype.onActionsChange = function () {
	this.detachActions();
	if ( !this.isClosing() ) {
		this.attachActions();
	}
};

/**
 * Get set of actions.
 *
 * @return {OO.ui.ActionSet}
 */
OO.ui.Dialog.prototype.getActions = function () {
	return this.actions;
};

/**
 * Get a process for taking action.
 *
 * When you override this method, you can add additional accept steps to the process the parent
 * method provides using the 'first' and 'next' methods.
 *
 * @abstract
 * @param {string} [action] Symbolic name of action
 * @return {OO.ui.Process} Action process
 */
OO.ui.Dialog.prototype.getActionProcess = function ( action ) {
	return new OO.ui.Process()
		.next( function () {
			if ( !action ) {
				// An empty action always closes the dialog without data, which should always be
				// safe and make no changes
				this.close();
			}
		}, this );
};

/**
 * @inheritdoc
 *
 * @param {Object} [data] Dialog opening data
 * @param {jQuery|string|Function|null} [data.title] Dialog title, omit to use #static-title
 * @param {Object[]} [data.actions] List of OO.ui.ActionWidget configuration options for each
 *   action item, omit to use #static-actions
 */
OO.ui.Dialog.prototype.getSetupProcess = function ( data ) {
	data = data || {};

	// Parent method
	return OO.ui.Dialog.super.prototype.getSetupProcess.call( this, data )
		.next( function () {
			var i, len,
				items = [],
				config = this.constructor.static,
				actions = data.actions !== undefined ? data.actions : config.actions;

			this.title.setLabel(
				data.title !== undefined ? data.title : this.constructor.static.title
			);
			for ( i = 0, len = actions.length; i < len; i++ ) {
				items.push(
					new OO.ui.ActionWidget( $.extend( { $: this.$ }, actions[i] ) )
				);
			}
			this.actions.add( items );

			if ( this.constructor.static.escapable ) {
				this.$document.on( 'keydown', this.onDocumentKeyDownHandler );
			}
		}, this );
};

/**
 * @inheritdoc
 */
OO.ui.Dialog.prototype.getTeardownProcess = function ( data ) {
	// Parent method
	return OO.ui.Dialog.super.prototype.getTeardownProcess.call( this, data )
		.first( function () {
			if ( this.constructor.static.escapable ) {
				this.$document.off( 'keydown', this.onDocumentKeyDownHandler );
			}

			this.actions.clear();
			this.currentAction = null;
		}, this );
};

/**
 * @inheritdoc
 */
OO.ui.Dialog.prototype.initialize = function () {
	// Parent method
	OO.ui.Dialog.super.prototype.initialize.call( this );

	// Properties
	this.title = new OO.ui.LabelWidget( { $: this.$ } );

	// Initialization
	this.$content.addClass( 'oo-ui-dialog-content' );
	this.setPendingElement( this.$head );
};

/**
 * Attach action actions.
 */
OO.ui.Dialog.prototype.attachActions = function () {
	// Remember the list of potentially attached actions
	this.attachedActions = this.actions.get();
};

/**
 * Detach action actions.
 *
 * @chainable
 */
OO.ui.Dialog.prototype.detachActions = function () {
	var i, len;

	// Detach all actions that may have been previously attached
	for ( i = 0, len = this.attachedActions.length; i < len; i++ ) {
		this.attachedActions[i].$element.detach();
	}
	this.attachedActions = [];
};

/**
 * Execute an action.
 *
 * @param {string} action Symbolic name of action to execute
 * @return {jQuery.Promise} Promise resolved when action completes, rejected if it fails
 */
OO.ui.Dialog.prototype.executeAction = function ( action ) {
	this.pushPending();
	return this.getActionProcess( action ).execute()
		.always( this.popPending.bind( this ) );
};

/**
 * Collection of windows.
 *
 * @class
 * @extends OO.ui.Element
 * @mixins OO.EventEmitter
 *
 * Managed windows are mutually exclusive. If a window is opened while there is a current window
 * already opening or opened, the current window will be closed without data. Empty closing data
 * should always result in the window being closed without causing constructive or destructive
 * action.
 *
 * As a window is opened and closed, it passes through several stages and the manager emits several
 * corresponding events.
 *
 * - {@link #openWindow} or {@link OO.ui.Window#open} methods are used to start opening
 * - {@link #event-opening} is emitted with `opening` promise
 * - {@link #getSetupDelay} is called the returned value is used to time a pause in execution
 * - {@link OO.ui.Window#getSetupProcess} method is called on the window and its result executed
 * - `setup` progress notification is emitted from opening promise
 * - {@link #getReadyDelay} is called the returned value is used to time a pause in execution
 * - {@link OO.ui.Window#getReadyProcess} method is called on the window and its result executed
 * - `ready` progress notification is emitted from opening promise
 * - `opening` promise is resolved with `opened` promise
 * - Window is now open
 *
 * - {@link #closeWindow} or {@link OO.ui.Window#close} methods are used to start closing
 * - `opened` promise is resolved with `closing` promise
 * - {@link #event-closing} is emitted with `closing` promise
 * - {@link #getHoldDelay} is called the returned value is used to time a pause in execution
 * - {@link OO.ui.Window#getHoldProcess} method is called on the window and its result executed
 * - `hold` progress notification is emitted from opening promise
 * - {@link #getTeardownDelay} is called the returned value is used to time a pause in execution
 * - {@link OO.ui.Window#getTeardownProcess} method is called on the window and its result executed
 * - `teardown` progress notification is emitted from opening promise
 * - Closing promise is resolved
 * - Window is now closed
 *
 * @constructor
 * @param {Object} [config] Configuration options
 * @cfg {boolean} [isolate] Configure managed windows to isolate their content using inline frames
 * @cfg {OO.Factory} [factory] Window factory to use for automatic instantiation
 * @cfg {boolean} [modal=true] Prevent interaction outside the dialog
 */
OO.ui.WindowManager = function OoUiWindowManager( config ) {
	// Configuration initialization
	config = config || {};

	// Parent constructor
	OO.ui.WindowManager.super.call( this, config );

	// Mixin constructors
	OO.EventEmitter.call( this );

	// Properties
	this.factory = config.factory;
	this.modal = config.modal === undefined || !!config.modal;
	this.isolate = !!config.isolate;
	this.windows = {};
	this.opening = null;
	this.opened = null;
	this.closing = null;
	this.preparingToOpen = null;
	this.preparingToClose = null;
	this.size = null;
	this.currentWindow = null;
	this.$ariaHidden = null;
	this.requestedSize = null;
	this.onWindowResizeTimeout = null;
	this.onWindowResizeHandler = this.onWindowResize.bind( this );
	this.afterWindowResizeHandler = this.afterWindowResize.bind( this );
	this.onWindowMouseWheelHandler = this.onWindowMouseWheel.bind( this );
	this.onDocumentKeyDownHandler = this.onDocumentKeyDown.bind( this );

	// Initialization
	this.$element
		.addClass( 'oo-ui-windowManager' )
		.toggleClass( 'oo-ui-windowManager-modal', this.modal );
};

/* Setup */

OO.inheritClass( OO.ui.WindowManager, OO.ui.Element );
OO.mixinClass( OO.ui.WindowManager, OO.EventEmitter );

/* Events */

/**
 * Window is opening.
 *
 * Fired when the window begins to be opened.
 *
 * @event opening
 * @param {OO.ui.Window} win Window that's being opened
 * @param {jQuery.Promise} opening Promise resolved when window is opened; when the promise is
 *   resolved the first argument will be a promise which will be resolved when the window begins
 *   closing, the second argument will be the opening data; progress notifications will be fired on
 *   the promise for `setup` and `ready` when those processes are completed respectively.
 * @param {Object} data Window opening data
 */

/**
 * Window is closing.
 *
 * Fired when the window begins to be closed.
 *
 * @event closing
 * @param {OO.ui.Window} win Window that's being closed
 * @param {jQuery.Promise} opening Promise resolved when window is closed; when the promise
 *   is resolved the first argument will be a the closing data; progress notifications will be fired
 *   on the promise for `hold` and `teardown` when those processes are completed respectively.
 * @param {Object} data Window closing data
 */

/**
 * Window was resized.
 *
 * @event resize
 * @param {OO.ui.Window} win Window that was resized
 */

/* Static Properties */

/**
 * Map of symbolic size names and CSS properties.
 *
 * @static
 * @inheritable
 * @property {Object}
 */
OO.ui.WindowManager.static.sizes = {
	small: {
		width: 300
	},
	medium: {
		width: 500
	},
	large: {
		width: 700
	},
	full: {
		// These can be non-numeric because they are never used in calculations
		width: '100%',
		height: '100%'
	}
};

/**
 * Symbolic name of default size.
 *
 * Default size is used if the window's requested size is not recognized.
 *
 * @static
 * @inheritable
 * @property {string}
 */
OO.ui.WindowManager.static.defaultSize = 'medium';

/* Methods */

/**
 * Handle window resize events.
 *
 * @param {jQuery.Event} e Window resize event
 */
OO.ui.WindowManager.prototype.onWindowResize = function () {
	clearTimeout( this.onWindowResizeTimeout );
	this.onWindowResizeTimeout = setTimeout( this.afterWindowResizeHandler, 200 );
};

/**
 * Handle window resize events.
 *
 * @param {jQuery.Event} e Window resize event
 */
OO.ui.WindowManager.prototype.afterWindowResize = function () {
	if ( this.currentWindow ) {
		this.updateWindowSize( this.currentWindow );
	}
};

/**
 * Handle window mouse wheel events.
 *
 * @param {jQuery.Event} e Mouse wheel event
 */
OO.ui.WindowManager.prototype.onWindowMouseWheel = function ( e ) {
	// Kill all events in the parent window if the child window is isolated,
	// or if the event didn't come from the child window
	return !( this.shouldIsolate() || !$.contains( this.getCurrentWindow().$frame[0], e.target ) );
};

/**
 * Handle document key down events.
 *
 * @param {jQuery.Event} e Key down event
 */
OO.ui.WindowManager.prototype.onDocumentKeyDown = function ( e ) {
	switch ( e.which ) {
		case OO.ui.Keys.PAGEUP:
		case OO.ui.Keys.PAGEDOWN:
		case OO.ui.Keys.END:
		case OO.ui.Keys.HOME:
		case OO.ui.Keys.LEFT:
		case OO.ui.Keys.UP:
		case OO.ui.Keys.RIGHT:
		case OO.ui.Keys.DOWN:
			// Kill all events in the parent window if the child window is isolated,
			// or if the event didn't come from the child window
			return !( this.shouldIsolate() || !$.contains( this.getCurrentWindow().$frame[0], e.target ) );
	}
};

/**
 * Check if window is opening.
 *
 * @return {boolean} Window is opening
 */
OO.ui.WindowManager.prototype.isOpening = function ( win ) {
	return win === this.currentWindow && !!this.opening && this.opening.state() === 'pending';
};

/**
 * Check if window is closing.
 *
 * @return {boolean} Window is closing
 */
OO.ui.WindowManager.prototype.isClosing = function ( win ) {
	return win === this.currentWindow && !!this.closing && this.closing.state() === 'pending';
};

/**
 * Check if window is opened.
 *
 * @return {boolean} Window is opened
 */
OO.ui.WindowManager.prototype.isOpened = function ( win ) {
	return win === this.currentWindow && !!this.opened && this.opened.state() === 'pending';
};

/**
 * Check if window contents should be isolated.
 *
 * Window content isolation is done using inline frames.
 *
 * @return {boolean} Window contents should be isolated
 */
OO.ui.WindowManager.prototype.shouldIsolate = function () {
	return this.isolate;
};

/**
 * Check if a window is being managed.
 *
 * @param {OO.ui.Window} win Window to check
 * @return {boolean} Window is being managed
 */
OO.ui.WindowManager.prototype.hasWindow = function ( win ) {
	var name;

	for ( name in this.windows ) {
		if ( this.windows[name] === win ) {
			return true;
		}
	}

	return false;
};

/**
 * Get the number of milliseconds to wait between beginning opening and executing setup process.
 *
 * @param {OO.ui.Window} win Window being opened
 * @param {Object} [data] Window opening data
 * @return {number} Milliseconds to wait
 */
OO.ui.WindowManager.prototype.getSetupDelay = function () {
	return 0;
};

/**
 * Get the number of milliseconds to wait between finishing setup and executing ready process.
 *
 * @param {OO.ui.Window} win Window being opened
 * @param {Object} [data] Window opening data
 * @return {number} Milliseconds to wait
 */
OO.ui.WindowManager.prototype.getReadyDelay = function () {
	return 0;
};

/**
 * Get the number of milliseconds to wait between beginning closing and executing hold process.
 *
 * @param {OO.ui.Window} win Window being closed
 * @param {Object} [data] Window closing data
 * @return {number} Milliseconds to wait
 */
OO.ui.WindowManager.prototype.getHoldDelay = function () {
	return 0;
};

/**
 * Get the number of milliseconds to wait between finishing hold and executing teardown process.
 *
 * @param {OO.ui.Window} win Window being closed
 * @param {Object} [data] Window closing data
 * @return {number} Milliseconds to wait
 */
OO.ui.WindowManager.prototype.getTeardownDelay = function () {
	return this.modal ? 250 : 0;
};

/**
 * Get managed window by symbolic name.
 *
 * If window is not yet instantiated, it will be instantiated and added automatically.
 *
 * @param {string} name Symbolic window name
 * @return {jQuery.Promise} Promise resolved with matching window, or rejected with an OO.ui.Error
 * @throws {Error} If the symbolic name is unrecognized by the factory
 * @throws {Error} If the symbolic name unrecognized as a managed window
 */
OO.ui.WindowManager.prototype.getWindow = function ( name ) {
	var deferred = $.Deferred(),
		win = this.windows[name];

	if ( !( win instanceof OO.ui.Window ) ) {
		if ( this.factory ) {
			if ( !this.factory.lookup( name ) ) {
				deferred.reject( new OO.ui.Error(
					'Cannot auto-instantiate window: symbolic name is unrecognized by the factory'
				) );
			} else {
				win = this.factory.create( name, this, { $: this.$ } );
				this.addWindows( [ win ] );
				deferred.resolve( win );
			}
		} else {
			deferred.reject( new OO.ui.Error(
				'Cannot get unmanaged window: symbolic name unrecognized as a managed window'
			) );
		}
	} else {
		deferred.resolve( win );
	}

	return deferred.promise();
};

/**
 * Get current window.
 *
 * @return {OO.ui.Window|null} Currently opening/opened/closing window
 */
OO.ui.WindowManager.prototype.getCurrentWindow = function () {
	return this.currentWindow;
};

/**
 * Open a window.
 *
 * @param {OO.ui.Window|string} win Window object or symbolic name of window to open
 * @param {Object} [data] Window opening data
 * @return {jQuery.Promise} Promise resolved when window is done opening; see {@link #event-opening}
 *   for more details about the `opening` promise
 * @fires opening
 */
OO.ui.WindowManager.prototype.openWindow = function ( win, data ) {
	var manager = this,
		preparing = [],
		opening = $.Deferred();

	// Argument handling
	if ( typeof win === 'string' ) {
		return this.getWindow( win ).then( function ( win ) {
			return manager.openWindow( win, data );
		} );
	}

	// Error handling
	if ( !this.hasWindow( win ) ) {
		opening.reject( new OO.ui.Error(
			'Cannot open window: window is not attached to manager'
		) );
	} else if ( this.preparingToOpen || this.opening || this.opened ) {
		opening.reject( new OO.ui.Error(
			'Cannot open window: another window is opening or open'
		) );
	}

	// Window opening
	if ( opening.state() !== 'rejected' ) {
		if ( !win.getManager() ) {
			win.setManager( this );
		}
		preparing.push( win.load() );

		if ( this.closing ) {
			// If a window is currently closing, wait for it to complete
			preparing.push( this.closing );
		}

		this.preparingToOpen = $.when.apply( $, preparing );
		// Ensure handlers get called after preparingToOpen is set
		this.preparingToOpen.done( function () {
			if ( manager.modal ) {
				manager.toggleGlobalEvents( true );
				manager.toggleAriaIsolation( true );
			}
			manager.currentWindow = win;
			manager.opening = opening;
			manager.preparingToOpen = null;
			manager.emit( 'opening', win, opening, data );
			setTimeout( function () {
				win.setup( data ).then( function () {
					manager.updateWindowSize( win );
					manager.opening.notify( { state: 'setup' } );
					setTimeout( function () {
						win.ready( data ).then( function () {
							manager.opening.notify( { state: 'ready' } );
							manager.opening = null;
							manager.opened = $.Deferred();
							opening.resolve( manager.opened.promise(), data );
						} );
					}, manager.getReadyDelay() );
				} );
			}, manager.getSetupDelay() );
		} );
	}

	return opening.promise();
};

/**
 * Close a window.
 *
 * @param {OO.ui.Window|string} win Window object or symbolic name of window to close
 * @param {Object} [data] Window closing data
 * @return {jQuery.Promise} Promise resolved when window is done closing; see {@link #event-closing}
 *   for more details about the `closing` promise
 * @throws {Error} If no window by that name is being managed
 * @fires closing
 */
OO.ui.WindowManager.prototype.closeWindow = function ( win, data ) {
	var manager = this,
		preparing = [],
		closing = $.Deferred(),
		opened;

	// Argument handling
	if ( typeof win === 'string' ) {
		win = this.windows[win];
	} else if ( !this.hasWindow( win ) ) {
		win = null;
	}

	// Error handling
	if ( !win ) {
		closing.reject( new OO.ui.Error(
			'Cannot close window: window is not attached to manager'
		) );
	} else if ( win !== this.currentWindow ) {
		closing.reject( new OO.ui.Error(
			'Cannot close window: window already closed with different data'
		) );
	} else if ( this.preparingToClose || this.closing ) {
		closing.reject( new OO.ui.Error(
			'Cannot close window: window already closing with different data'
		) );
	}

	// Window closing
	if ( closing.state() !== 'rejected' ) {
		if ( this.opening ) {
			// If the window is currently opening, close it when it's done
			preparing.push( this.opening );
		}

		this.preparingToClose = $.when.apply( $, preparing );
		// Ensure handlers get called after preparingToClose is set
		this.preparingToClose.done( function () {
			manager.closing = closing;
			manager.preparingToClose = null;
			manager.emit( 'closing', win, closing, data );
			opened = manager.opened;
			manager.opened = null;
			opened.resolve( closing.promise(), data );
			setTimeout( function () {
				win.hold( data ).then( function () {
					closing.notify( { state: 'hold' } );
					setTimeout( function () {
						win.teardown( data ).then( function () {
							closing.notify( { state: 'teardown' } );
							if ( manager.modal ) {
								manager.toggleGlobalEvents( false );
								manager.toggleAriaIsolation( false );
							}
							manager.closing = null;
							manager.currentWindow = null;
							closing.resolve( data );
						} );
					}, manager.getTeardownDelay() );
				} );
			}, manager.getHoldDelay() );
		} );
	}

	return closing.promise();
};

/**
 * Add windows.
 *
 * @param {Object.<string,OO.ui.Window>|OO.ui.Window[]} windows Windows to add
 * @throws {Error} If one of the windows being added without an explicit symbolic name does not have
 *   a statically configured symbolic name
 */
OO.ui.WindowManager.prototype.addWindows = function ( windows ) {
	var i, len, win, name, list;

	if ( $.isArray( windows ) ) {
		// Convert to map of windows by looking up symbolic names from static configuration
		list = {};
		for ( i = 0, len = windows.length; i < len; i++ ) {
			name = windows[i].constructor.static.name;
			if ( typeof name !== 'string' ) {
				throw new Error( 'Cannot add window' );
			}
			list[name] = windows[i];
		}
	} else if ( $.isPlainObject( windows ) ) {
		list = windows;
	}

	// Add windows
	for ( name in list ) {
		win = list[name];
		this.windows[name] = win;
		this.$element.append( win.$element );
	}
};

/**
 * Remove windows.
 *
 * Windows will be closed before they are removed.
 *
 * @param {string} name Symbolic name of window to remove
 * @return {jQuery.Promise} Promise resolved when window is closed and removed
 * @throws {Error} If windows being removed are not being managed
 */
OO.ui.WindowManager.prototype.removeWindows = function ( names ) {
	var i, len, win, name, cleanupWindow,
		manager = this,
		promises = [],
		cleanup = function ( name, win ) {
			delete manager.windows[name];
			win.$element.detach();
		};

	for ( i = 0, len = names.length; i < len; i++ ) {
		name = names[i];
		win = this.windows[name];
		if ( !win ) {
			throw new Error( 'Cannot remove window' );
		}
		cleanupWindow = cleanup.bind( null, name, win );
		promises.push( this.closeWindow( name ).then( cleanupWindow, cleanupWindow ) );
	}

	return $.when.apply( $, promises );
};

/**
 * Remove all windows.
 *
 * Windows will be closed before they are removed.
 *
 * @return {jQuery.Promise} Promise resolved when all windows are closed and removed
 */
OO.ui.WindowManager.prototype.clearWindows = function () {
	return this.removeWindows( Object.keys( this.windows ) );
};

/**
 * Set dialog size.
 *
 * Fullscreen mode will be used if the dialog is too wide to fit in the screen.
 *
 * @chainable
 */
OO.ui.WindowManager.prototype.updateWindowSize = function ( win ) {
	// Bypass for non-current, and thus invisible, windows
	if ( win !== this.currentWindow ) {
		return;
	}

	var viewport = OO.ui.Element.getDimensions( win.getElementWindow() ),
		sizes = this.constructor.static.sizes,
		size = win.getSize();

	if ( !sizes[size] ) {
		size = this.constructor.static.defaultSize;
	}
	if ( size !== 'full' && viewport.rect.right - viewport.rect.left < sizes[size].width ) {
		size = 'full';
	}

	this.$element.toggleClass( 'oo-ui-windowManager-fullscreen', size === 'full' );
	this.$element.toggleClass( 'oo-ui-windowManager-floating', size !== 'full' );
	win.setDimensions( sizes[size] );

	this.emit( 'resize', win );

	return this;
};

/**
 * Bind or unbind global events for scrolling.
 *
 * @param {boolean} [on] Bind global events
 * @chainable
 */
OO.ui.WindowManager.prototype.toggleGlobalEvents = function ( on ) {
	on = on === undefined ? !!this.globalEvents : !!on;

	if ( on ) {
		if ( !this.globalEvents ) {
			this.$( this.getElementDocument() ).on( {
				// Prevent scrolling by keys in top-level window
				keydown: this.onDocumentKeyDownHandler
			} );
			this.$( this.getElementWindow() ).on( {
				// Prevent scrolling by wheel in top-level window
				mousewheel: this.onWindowMouseWheelHandler,
				// Start listening for top-level window dimension changes
				'orientationchange resize': this.onWindowResizeHandler
			} );
			this.globalEvents = true;
		}
	} else if ( this.globalEvents ) {
		// Unbind global events
		this.$( this.getElementDocument() ).off( {
			// Allow scrolling by keys in top-level window
			keydown: this.onDocumentKeyDownHandler
		} );
		this.$( this.getElementWindow() ).off( {
			// Allow scrolling by wheel in top-level window
			mousewheel: this.onWindowMouseWheelHandler,
			// Stop listening for top-level window dimension changes
			'orientationchange resize': this.onWindowResizeHandler
		} );
		this.globalEvents = false;
	}

	return this;
};

/**
 * Toggle screen reader visibility of content other than the window manager.
 *
 * @param {boolean} [isolate] Make only the window manager visible to screen readers
 * @chainable
 */
OO.ui.WindowManager.prototype.toggleAriaIsolation = function ( isolate ) {
	isolate = isolate === undefined ? !this.$ariaHidden : !!isolate;

	if ( isolate ) {
		if ( !this.$ariaHidden ) {
			// Hide everything other than the window manager from screen readers
			this.$ariaHidden = $( 'body' )
				.children()
				.not( this.$element.parentsUntil( 'body' ).last() )
				.attr( 'aria-hidden', '' );
		}
	} else if ( this.$ariaHidden ) {
		// Restore screen reader visibility
		this.$ariaHidden.removeAttr( 'aria-hidden' );
		this.$ariaHidden = null;
	}

	return this;
};

/**
 * Destroy window manager.
 *
 * Windows will not be closed, only removed from the DOM.
 */
OO.ui.WindowManager.prototype.destroy = function () {
	this.toggleGlobalEvents( false );
	this.toggleAriaIsolation( false );
	this.$element.remove();
};

/**
 * @abstract
 * @class
 *
 * @constructor
 * @param {string|jQuery} message Description of error
 * @param {Object} [config] Configuration options
 * @cfg {boolean} [recoverable=true] Error is recoverable
 * @cfg {boolean} [warning=false] Whether this error is a warning or not.
 */
OO.ui.Error = function OoUiElement( message, config ) {
	// Configuration initialization
	config = config || {};

	// Properties
	this.message = message instanceof jQuery ? message : String( message );
	this.recoverable = config.recoverable === undefined || !!config.recoverable;
	this.warning = !!config.warning;
};

/* Setup */

OO.initClass( OO.ui.Error );

/* Methods */

/**
 * Check if error can be recovered from.
 *
 * @return {boolean} Error is recoverable
 */
OO.ui.Error.prototype.isRecoverable = function () {
	return this.recoverable;
};

/**
 * Check if the error is a warning
 *
 * @return {boolean} Error is warning
 */
OO.ui.Error.prototype.isWarning = function () {
	return this.warning;
};

/**
 * Get error message as DOM nodes.
 *
 * @return {jQuery} Error message in DOM nodes
 */
OO.ui.Error.prototype.getMessage = function () {
	return this.message instanceof jQuery ?
		this.message.clone() :
		$( '<div>' ).text( this.message ).contents();
};

/**
 * Get error message as text.
 *
 * @return {string} Error message
 */
OO.ui.Error.prototype.getMessageText = function () {
	return this.message instanceof jQuery ? this.message.text() : this.message;
};

/**
 * A list of functions, called in sequence.
 *
 * If a function added to a process returns boolean false the process will stop; if it returns an
 * object with a `promise` method the process will use the promise to either continue to the next
 * step when the promise is resolved or stop when the promise is rejected.
 *
 * @class
 *
 * @constructor
 * @param {number|jQuery.Promise|Function} step Time to wait, promise to wait for or function to
 *   call, see #createStep for more information
 * @param {Object} [context=null] Context to call the step function in, ignored if step is a number
 *   or a promise
 * @return {Object} Step object, with `callback` and `context` properties
 */
OO.ui.Process = function ( step, context ) {
	// Properties
	this.steps = [];

	// Initialization
	if ( step !== undefined ) {
		this.next( step, context );
	}
};

/* Setup */

OO.initClass( OO.ui.Process );

/* Methods */

/**
 * Start the process.
 *
 * @return {jQuery.Promise} Promise that is resolved when all steps have completed or rejected when
 *   any of the steps return boolean false or a promise which gets rejected; upon stopping the
 *   process, the remaining steps will not be taken
 */
OO.ui.Process.prototype.execute = function () {
	var i, len, promise;

	/**
	 * Continue execution.
	 *
	 * @ignore
	 * @param {Array} step A function and the context it should be called in
	 * @return {Function} Function that continues the process
	 */
	function proceed( step ) {
		return function () {
			// Execute step in the correct context
			var deferred,
				result = step.callback.call( step.context );

			if ( result === false ) {
				// Use rejected promise for boolean false results
				return $.Deferred().reject( [] ).promise();
			}
			if ( typeof result === 'number' ) {
				if ( result < 0 ) {
					throw new Error( 'Cannot go back in time: flux capacitor is out of service' );
				}
				// Use a delayed promise for numbers, expecting them to be in milliseconds
				deferred = $.Deferred();
				setTimeout( deferred.resolve, result );
				return deferred.promise();
			}
			if ( result instanceof OO.ui.Error ) {
				// Use rejected promise for error
				return $.Deferred().reject( [ result ] ).promise();
			}
			if ( $.isArray( result ) && result.length && result[0] instanceof OO.ui.Error ) {
				// Use rejected promise for list of errors
				return $.Deferred().reject( result ).promise();
			}
			// Duck-type the object to see if it can produce a promise
			if ( result && $.isFunction( result.promise ) ) {
				// Use a promise generated from the result
				return result.promise();
			}
			// Use resolved promise for other results
			return $.Deferred().resolve().promise();
		};
	}

	if ( this.steps.length ) {
		// Generate a chain reaction of promises
		promise = proceed( this.steps[0] )();
		for ( i = 1, len = this.steps.length; i < len; i++ ) {
			promise = promise.then( proceed( this.steps[i] ) );
		}
	} else {
		promise = $.Deferred().resolve().promise();
	}

	return promise;
};

/**
 * Create a process step.
 *
 * @private
 * @param {number|jQuery.Promise|Function} step
 *
 * - Number of milliseconds to wait; or
 * - Promise to wait to be resolved; or
 * - Function to execute
 *   - If it returns boolean false the process will stop
 *   - If it returns an object with a `promise` method the process will use the promise to either
 *     continue to the next step when the promise is resolved or stop when the promise is rejected
 *   - If it returns a number, the process will wait for that number of milliseconds before
 *     proceeding
 * @param {Object} [context=null] Context to call the step function in, ignored if step is a number
 *   or a promise
 * @return {Object} Step object, with `callback` and `context` properties
 */
OO.ui.Process.prototype.createStep = function ( step, context ) {
	if ( typeof step === 'number' || $.isFunction( step.promise ) ) {
		return {
			callback: function () {
				return step;
			},
			context: null
		};
	}
	if ( $.isFunction( step ) ) {
		return {
			callback: step,
			context: context
		};
	}
	throw new Error( 'Cannot create process step: number, promise or function expected' );
};

/**
 * Add step to the beginning of the process.
 *
 * @inheritdoc #createStep
 * @return {OO.ui.Process} this
 * @chainable
 */
OO.ui.Process.prototype.first = function ( step, context ) {
	this.steps.unshift( this.createStep( step, context ) );
	return this;
};

/**
 * Add step to the end of the process.
 *
 * @inheritdoc #createStep
 * @return {OO.ui.Process} this
 * @chainable
 */
OO.ui.Process.prototype.next = function ( step, context ) {
	this.steps.push( this.createStep( step, context ) );
	return this;
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

/**
 * Get tools from the factory
 *
 * @param {Array} include Included tools
 * @param {Array} exclude Excluded tools
 * @param {Array} promote Promoted tools
 * @param {Array} demote Demoted tools
 * @return {string[]} List of tools
 */
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
 * - A specific tool: `{ name: 'tool-name' }` or `'tool-name'`
 * - All tools in a group: `{ group: 'group-name' }`
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
				item = { name: item };
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
 * Theme logic.
 *
 * @abstract
 * @class
 *
 * @constructor
 * @param {Object} [config] Configuration options
 */
OO.ui.Theme = function OoUiTheme( config ) {
	// Configuration initialization
	config = config || {};
};

/* Setup */

OO.initClass( OO.ui.Theme );

/* Methods */

/**
 * Get a list of classes to be applied to a widget.
 *
 * The 'on' and 'off' lists combined MUST contain keys for all classes the theme adds or removes,
 * otherwise state transitions will not work properly.
 *
 * @param {OO.ui.Element} element Element for which to get classes
 * @return {Object.<string,string[]>} Categorized class names with `on` and `off` lists
 */
OO.ui.Theme.prototype.getElementClasses = function ( /* element */ ) {
	return { on: [], off: [] };
};

/**
 * Update CSS classes provided by the theme.
 *
 * For elements with theme logic hooks, this should be called any time there's a state change.
 *
 * @param {OO.ui.Element} element Element for which to update classes
 * @return {Object.<string,string[]>} Categorized class names with `on` and `off` lists
 */
OO.ui.Theme.prototype.updateElementClasses = function ( element ) {
	var classes = this.getElementClasses( element );

	element.$element
		.removeClass( classes.off.join( ' ' ) )
		.addClass( classes.on.join( ' ' ) );
};

/**
 * Element with a button.
 *
 * Buttons are used for controls which can be clicked. They can be configured to use tab indexing
 * and access keys for accessibility purposes.
 *
 * @abstract
 * @class
 *
 * @constructor
 * @param {Object} [config] Configuration options
 * @cfg {jQuery} [$button] Button node, assigned to #$button, omit to use a generated `<a>`
 * @cfg {boolean} [framed=true] Render button with a frame
 * @cfg {number} [tabIndex=0] Button's tab index. Use 0 to use default ordering, use -1 to prevent
 *   tab focusing.
 * @cfg {string} [accessKey] Button's access key
 */
OO.ui.ButtonElement = function OoUiButtonElement( config ) {
	// Configuration initialization
	config = config || {};

	// Properties
	this.$button = null;
	this.framed = null;
	this.tabIndex = null;
	this.accessKey = null;
	this.active = false;
	this.onMouseUpHandler = this.onMouseUp.bind( this );
	this.onMouseDownHandler = this.onMouseDown.bind( this );

	// Initialization
	this.$element.addClass( 'oo-ui-buttonElement' );
	this.toggleFramed( config.framed === undefined || config.framed );
	this.setTabIndex( config.tabIndex || 0 );
	this.setAccessKey( config.accessKey );
	this.setButtonElement( config.$button || this.$( '<a>' ) );
};

/* Setup */

OO.initClass( OO.ui.ButtonElement );

/* Static Properties */

/**
 * Cancel mouse down events.
 *
 * @static
 * @inheritable
 * @property {boolean}
 */
OO.ui.ButtonElement.static.cancelButtonMouseDownEvents = true;

/* Methods */

/**
 * Set the button element.
 *
 * If an element is already set, it will be cleaned up before setting up the new element.
 *
 * @param {jQuery} $button Element to use as button
 */
OO.ui.ButtonElement.prototype.setButtonElement = function ( $button ) {
	if ( this.$button ) {
		this.$button
			.removeClass( 'oo-ui-buttonElement-button' )
			.removeAttr( 'role accesskey tabindex' )
			.off( this.onMouseDownHandler );
	}

	this.$button = $button
		.addClass( 'oo-ui-buttonElement-button' )
		.attr( { role: 'button', accesskey: this.accessKey, tabindex: this.tabIndex } )
		.on( 'mousedown', this.onMouseDownHandler );
};

/**
 * Handles mouse down events.
 *
 * @param {jQuery.Event} e Mouse down event
 */
OO.ui.ButtonElement.prototype.onMouseDown = function ( e ) {
	if ( this.isDisabled() || e.which !== 1 ) {
		return false;
	}
	// Remove the tab-index while the button is down to prevent the button from stealing focus
	this.$button.removeAttr( 'tabindex' );
	this.$element.addClass( 'oo-ui-buttonElement-pressed' );
	// Run the mouseup handler no matter where the mouse is when the button is let go, so we can
	// reliably reapply the tabindex and remove the pressed class
	this.getElementDocument().addEventListener( 'mouseup', this.onMouseUpHandler, true );
	// Prevent change of focus unless specifically configured otherwise
	if ( this.constructor.static.cancelButtonMouseDownEvents ) {
		return false;
	}
};

/**
 * Handles mouse up events.
 *
 * @param {jQuery.Event} e Mouse up event
 */
OO.ui.ButtonElement.prototype.onMouseUp = function ( e ) {
	if ( this.isDisabled() || e.which !== 1 ) {
		return false;
	}
	// Restore the tab-index after the button is up to restore the button's accessibility
	this.$button.attr( 'tabindex', this.tabIndex );
	this.$element.removeClass( 'oo-ui-buttonElement-pressed' );
	// Stop listening for mouseup, since we only needed this once
	this.getElementDocument().removeEventListener( 'mouseup', this.onMouseUpHandler, true );
};

/**
 * Check if button has a frame.
 *
 * @return {boolean} Button is framed
 */
OO.ui.ButtonElement.prototype.isFramed = function () {
	return this.framed;
};

/**
 * Toggle frame.
 *
 * @param {boolean} [framed] Make button framed, omit to toggle
 * @chainable
 */
OO.ui.ButtonElement.prototype.toggleFramed = function ( framed ) {
	framed = framed === undefined ? !this.framed : !!framed;
	if ( framed !== this.framed ) {
		this.framed = framed;
		this.$element
			.toggleClass( 'oo-ui-buttonElement-frameless', !framed )
			.toggleClass( 'oo-ui-buttonElement-framed', framed );
		this.updateThemeClasses();
	}

	return this;
};

/**
 * Set tab index.
 *
 * @param {number|null} tabIndex Button's tab index, use null to remove
 * @chainable
 */
OO.ui.ButtonElement.prototype.setTabIndex = function ( tabIndex ) {
	tabIndex = typeof tabIndex === 'number' && tabIndex >= 0 ? tabIndex : null;

	if ( this.tabIndex !== tabIndex ) {
		if ( this.$button ) {
			if ( tabIndex !== null ) {
				this.$button.attr( 'tabindex', tabIndex );
			} else {
				this.$button.removeAttr( 'tabindex' );
			}
		}
		this.tabIndex = tabIndex;
	}

	return this;
};

/**
 * Set access key.
 *
 * @param {string} accessKey Button's access key, use empty string to remove
 * @chainable
 */
OO.ui.ButtonElement.prototype.setAccessKey = function ( accessKey ) {
	accessKey = typeof accessKey === 'string' && accessKey.length ? accessKey : null;

	if ( this.accessKey !== accessKey ) {
		if ( this.$button ) {
			if ( accessKey !== null ) {
				this.$button.attr( 'accesskey', accessKey );
			} else {
				this.$button.removeAttr( 'accesskey' );
			}
		}
		this.accessKey = accessKey;
	}

	return this;
};

/**
 * Set active state.
 *
 * @param {boolean} [value] Make button active
 * @chainable
 */
OO.ui.ButtonElement.prototype.setActive = function ( value ) {
	this.$element.toggleClass( 'oo-ui-buttonElement-active', !!value );
	return this;
};

/**
 * Element containing a sequence of child elements.
 *
 * @abstract
 * @class
 *
 * @constructor
 * @param {Object} [config] Configuration options
 * @cfg {jQuery} [$group] Container node, assigned to #$group, omit to use a generated `<div>`
 */
OO.ui.GroupElement = function OoUiGroupElement( config ) {
	// Configuration initialization
	config = config || {};

	// Properties
	this.$group = null;
	this.items = [];
	this.aggregateItemEvents = {};

	// Initialization
	this.setGroupElement( config.$group || this.$( '<div>' ) );
};

/* Methods */

/**
 * Set the group element.
 *
 * If an element is already set, items will be moved to the new element.
 *
 * @param {jQuery} $group Element to use as group
 */
OO.ui.GroupElement.prototype.setGroupElement = function ( $group ) {
	var i, len;

	this.$group = $group;
	for ( i = 0, len = this.items.length; i < len; i++ ) {
		this.$group.append( this.items[i].$element );
	}
};

/**
 * Check if there are no items.
 *
 * @return {boolean} Group is empty
 */
OO.ui.GroupElement.prototype.isEmpty = function () {
	return !this.items.length;
};

/**
 * Get items.
 *
 * @return {OO.ui.Element[]} Items
 */
OO.ui.GroupElement.prototype.getItems = function () {
	return this.items.slice( 0 );
};

/**
 * Get an item by its data.
 *
 * Data is compared by a hash of its value. Only the first item with matching data will be returned.
 *
 * @param {Object} data Item data to search for
 * @return {OO.ui.Element|null} Item with equivalent data, `null` if none exists
 */
OO.ui.GroupElement.prototype.getItemFromData = function ( data ) {
	var i, len, item,
		hash = OO.getHash( data );

	for ( i = 0, len = this.items.length; i < len; i++ ) {
		item = this.items[i];
		if ( hash === OO.getHash( item.getData() ) ) {
			return item;
		}
	}

	return null;
};

/**
 * Get items by their data.
 *
 * Data is compared by a hash of its value. All items with matching data will be returned.
 *
 * @param {Object} data Item data to search for
 * @return {OO.ui.Element[]} Items with equivalent data
 */
OO.ui.GroupElement.prototype.getItemsFromData = function ( data ) {
	var i, len, item,
		hash = OO.getHash( data ),
		items = [];

	for ( i = 0, len = this.items.length; i < len; i++ ) {
		item = this.items[i];
		if ( hash === OO.getHash( item.getData() ) ) {
			items.push( item );
		}
	}

	return items;
};

/**
 * Add an aggregate item event.
 *
 * Aggregated events are listened to on each item and then emitted by the group under a new name,
 * and with an additional leading parameter containing the item that emitted the original event.
 * Other arguments that were emitted from the original event are passed through.
 *
 * @param {Object.<string,string|null>} events Aggregate events emitted by group, keyed by item
 *   event, use null value to remove aggregation
 * @throws {Error} If aggregation already exists
 */
OO.ui.GroupElement.prototype.aggregate = function ( events ) {
	var i, len, item, add, remove, itemEvent, groupEvent;

	for ( itemEvent in events ) {
		groupEvent = events[itemEvent];

		// Remove existing aggregated event
		if ( Object.prototype.hasOwnProperty.call( this.aggregateItemEvents, itemEvent ) ) {
			// Don't allow duplicate aggregations
			if ( groupEvent ) {
				throw new Error( 'Duplicate item event aggregation for ' + itemEvent );
			}
			// Remove event aggregation from existing items
			for ( i = 0, len = this.items.length; i < len; i++ ) {
				item = this.items[i];
				if ( item.connect && item.disconnect ) {
					remove = {};
					remove[itemEvent] = [ 'emit', groupEvent, item ];
					item.disconnect( this, remove );
				}
			}
			// Prevent future items from aggregating event
			delete this.aggregateItemEvents[itemEvent];
		}

		// Add new aggregate event
		if ( groupEvent ) {
			// Make future items aggregate event
			this.aggregateItemEvents[itemEvent] = groupEvent;
			// Add event aggregation to existing items
			for ( i = 0, len = this.items.length; i < len; i++ ) {
				item = this.items[i];
				if ( item.connect && item.disconnect ) {
					add = {};
					add[itemEvent] = [ 'emit', groupEvent, item ];
					item.connect( this, add );
				}
			}
		}
	}
};

/**
 * Add items.
 *
 * Adding an existing item will move it.
 *
 * @param {OO.ui.Element[]} items Items
 * @param {number} [index] Index to insert items at
 * @chainable
 */
OO.ui.GroupElement.prototype.addItems = function ( items, index ) {
	var i, len, item, event, events, currentIndex,
		itemElements = [];

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
		if ( item.connect && item.disconnect && !$.isEmptyObject( this.aggregateItemEvents ) ) {
			events = {};
			for ( event in this.aggregateItemEvents ) {
				events[event] = [ 'emit', this.aggregateItemEvents[event], item ];
			}
			item.connect( this, events );
		}
		item.setElementGroup( this );
		itemElements.push( item.$element.get( 0 ) );
	}

	if ( index === undefined || index < 0 || index >= this.items.length ) {
		this.$group.append( itemElements );
		this.items.push.apply( this.items, items );
	} else if ( index === 0 ) {
		this.$group.prepend( itemElements );
		this.items.unshift.apply( this.items, items );
	} else {
		this.items[index].$element.before( itemElements );
		this.items.splice.apply( this.items, [ index, 0 ].concat( items ) );
	}

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
	var i, len, item, index, remove, itemEvent;

	// Remove specific items
	for ( i = 0, len = items.length; i < len; i++ ) {
		item = items[i];
		index = $.inArray( item, this.items );
		if ( index !== -1 ) {
			if (
				item.connect && item.disconnect &&
				!$.isEmptyObject( this.aggregateItemEvents )
			) {
				remove = {};
				if ( Object.prototype.hasOwnProperty.call( this.aggregateItemEvents, itemEvent ) ) {
					remove[itemEvent] = [ 'emit', this.aggregateItemEvents[itemEvent], item ];
				}
				item.disconnect( this, remove );
			}
			item.setElementGroup( null );
			this.items.splice( index, 1 );
			item.$element.detach();
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
	var i, len, item, remove, itemEvent;

	// Remove all items
	for ( i = 0, len = this.items.length; i < len; i++ ) {
		item = this.items[i];
		if (
			item.connect && item.disconnect &&
			!$.isEmptyObject( this.aggregateItemEvents )
		) {
			remove = {};
			if ( Object.prototype.hasOwnProperty.call( this.aggregateItemEvents, itemEvent ) ) {
				remove[itemEvent] = [ 'emit', this.aggregateItemEvents[itemEvent], item ];
			}
			item.disconnect( this, remove );
		}
		item.setElementGroup( null );
		item.$element.detach();
	}

	this.items = [];
	return this;
};

/**
 * A mixin for an element that can be dragged and dropped.
 * Use in conjunction with DragGroupWidget
 *
 * @abstract
 * @class
 *
 * @constructor
 */
OO.ui.DraggableElement = function OoUiDraggableElement() {
	// Properties
	this.index = null;

	// Initialize and events
	this.$element
		.attr( 'draggable', true )
		.addClass( 'oo-ui-draggableElement' )
		.on( {
			dragstart: this.onDragStart.bind( this ),
			dragover: this.onDragOver.bind( this ),
			dragend: this.onDragEnd.bind( this ),
			drop: this.onDrop.bind( this )
		} );
};

/* Events */

/**
 * @event dragstart
 * @param {OO.ui.DraggableElement} item Dragging item
 */

/**
 * @event dragend
 */

/**
 * @event drop
 */

/* Methods */

/**
 * Respond to dragstart event.
 * @param {jQuery.Event} event jQuery event
 * @fires dragstart
 */
OO.ui.DraggableElement.prototype.onDragStart = function ( e ) {
	var dataTransfer = e.originalEvent.dataTransfer;
	// Define drop effect
	dataTransfer.dropEffect = 'none';
	dataTransfer.effectAllowed = 'move';
	// We must set up a dataTransfer data property or Firefox seems to
	// ignore the fact the element is draggable.
	try {
		dataTransfer.setData( 'application-x/OOjs-UI-draggable', this.getIndex() );
	} catch ( err ) {
		// The above is only for firefox. No need to set a catch clause
		// if it fails, move on.
	}
	// Add dragging class
	this.$element.addClass( 'oo-ui-draggableElement-dragging' );
	// Emit event
	this.emit( 'dragstart', this );
	return true;
};

/**
 * Respond to dragend event.
 * @fires dragend
 */
OO.ui.DraggableElement.prototype.onDragEnd = function () {
	this.$element.removeClass( 'oo-ui-draggableElement-dragging' );
	this.emit( 'dragend' );
};

/**
 * Handle drop event.
 * @param {jQuery.Event} event jQuery event
 * @fires drop
 */
OO.ui.DraggableElement.prototype.onDrop = function ( e ) {
	e.preventDefault();
	this.emit( 'drop', e );
};

/**
 * In order for drag/drop to work, the dragover event must
 * return false and stop propogation.
 */
OO.ui.DraggableElement.prototype.onDragOver = function ( e ) {
	e.preventDefault();
};

/**
 * Set item index.
 * Store it in the DOM so we can access from the widget drag event
 * @param {number} Item index
 */
OO.ui.DraggableElement.prototype.setIndex = function ( index ) {
	if ( this.index !== index ) {
		this.index = index;
		this.$element.data( 'index', index );
	}
};

/**
 * Get item index
 * @return {number} Item index
 */
OO.ui.DraggableElement.prototype.getIndex = function () {
	return this.index;
};

/**
 * Element containing a sequence of child elements that can be dragged
 * and dropped.
 *
 * @abstract
 * @class
 *
 * @constructor
 * @param {Object} [config] Configuration options
 * @cfg {jQuery} [$group] Container node, assigned to #$group, omit to use a generated `<div>`
 * @cfg {string} [orientation] Item orientation, 'horizontal' or 'vertical'. Defaults to 'vertical'
 */
OO.ui.DraggableGroupElement = function OoUiDraggableGroupElement( config ) {
	// Configuration initialization
	config = config || {};

	// Parent constructor
	OO.ui.GroupElement.call( this, config );

	// Properties
	this.orientation = config.orientation || 'vertical';
	this.dragItem = null;
	this.itemDragOver = null;
	this.itemKeys = {};
	this.sideInsertion = '';

	// Events
	this.aggregate( {
		dragstart: 'itemDragStart',
		dragend: 'itemDragEnd',
		drop: 'itemDrop'
	} );
	this.connect( this, {
		itemDragStart: 'onItemDragStart',
		itemDrop: 'onItemDrop',
		itemDragEnd: 'onItemDragEnd'
	} );
	this.$element.on( {
		dragover: $.proxy( this.onDragOver, this ),
		dragleave: $.proxy( this.onDragLeave, this )
	} );

	// Initialize
	if ( $.isArray( config.items ) ) {
		this.addItems( config.items );
	}
	this.$placeholder = $( '<div>' )
		.addClass( 'oo-ui-draggableGroupElement-placeholder' );
	this.$element
		.addClass( 'oo-ui-draggableGroupElement' )
		.append( this.$status )
		.toggleClass( 'oo-ui-draggableGroupElement-horizontal', this.orientation === 'horizontal' )
		.prepend( this.$placeholder );
};

/* Setup */
OO.mixinClass( OO.ui.DraggableGroupElement, OO.ui.GroupElement );

/* Events */

/**
 * @event reorder
 * @param {OO.ui.DraggableElement} item Reordered item
 * @param {number} [newIndex] New index for the item
 */

/* Methods */

/**
 * Respond to item drag start event
 * @param {OO.ui.DraggableElement} item Dragged item
 */
OO.ui.DraggableGroupElement.prototype.onItemDragStart = function ( item ) {
	var i, len;

	// Map the index of each object
	for ( i = 0, len = this.items.length; i < len; i++ ) {
		this.items[i].setIndex( i );
	}

	if ( this.orientation === 'horizontal' ) {
		// Set the height of the indicator
		this.$placeholder.css( {
			height: item.$element.outerHeight(),
			width: 2
		} );
	} else {
		// Set the width of the indicator
		this.$placeholder.css( {
			height: 2,
			width: item.$element.outerWidth()
		} );
	}
	this.setDragItem( item );
};

/**
 * Respond to item drag end event
 */
OO.ui.DraggableGroupElement.prototype.onItemDragEnd = function () {
	this.unsetDragItem();
	return false;
};

/**
 * Handle drop event and switch the order of the items accordingly
 * @param {OO.ui.DraggableElement} item Dropped item
 * @fires reorder
 */
OO.ui.DraggableGroupElement.prototype.onItemDrop = function ( item ) {
	var toIndex = item.getIndex();
	// Check if the dropped item is from the current group
	// TODO: Figure out a way to configure a list of legally droppable
	// elements even if they are not yet in the list
	if ( this.getDragItem() ) {
		// If the insertion point is 'after', the insertion index
		// is shifted to the right (or to the left in RTL, hence 'after')
		if ( this.sideInsertion === 'after' ) {
			toIndex++;
		}
		// Emit change event
		this.emit( 'reorder', this.getDragItem(), toIndex );
	}
	// Return false to prevent propogation
	return false;
};

/**
 * Handle dragleave event.
 */
OO.ui.DraggableGroupElement.prototype.onDragLeave = function () {
	// This means the item was dragged outside the widget
	this.$placeholder
		.css( 'left', 0 )
		.hide();
};

/**
 * Respond to dragover event
 * @param {jQuery.Event} event Event details
 */
OO.ui.DraggableGroupElement.prototype.onDragOver = function ( e ) {
	var dragOverObj, $optionWidget, itemOffset, itemMidpoint, itemBoundingRect,
		itemSize, cssOutput, dragPosition, itemIndex, itemPosition,
		clientX = e.originalEvent.clientX,
		clientY = e.originalEvent.clientY;

	// Get the OptionWidget item we are dragging over
	dragOverObj = this.getElementDocument().elementFromPoint( clientX, clientY );
	$optionWidget = $( dragOverObj ).closest( '.oo-ui-draggableElement' );
	if ( $optionWidget[0] ) {
		itemOffset = $optionWidget.offset();
		itemBoundingRect = $optionWidget[0].getBoundingClientRect();
		itemPosition = $optionWidget.position();
		itemIndex = $optionWidget.data( 'index' );
	}

	if (
		itemOffset &&
		this.isDragging() &&
		itemIndex !== this.getDragItem().getIndex()
	) {
		if ( this.orientation === 'horizontal' ) {
			// Calculate where the mouse is relative to the item width
			itemSize = itemBoundingRect.width;
			itemMidpoint = itemBoundingRect.left + itemSize / 2;
			dragPosition = clientX;
			// Which side of the item we hover over will dictate
			// where the placeholder will appear, on the left or
			// on the right
			cssOutput = {
				left: dragPosition < itemMidpoint ? itemPosition.left : itemPosition.left + itemSize,
				top: itemPosition.top
			};
		} else {
			// Calculate where the mouse is relative to the item height
			itemSize = itemBoundingRect.height;
			itemMidpoint = itemBoundingRect.top + itemSize / 2;
			dragPosition = clientY;
			// Which side of the item we hover over will dictate
			// where the placeholder will appear, on the top or
			// on the bottom
			cssOutput = {
				top: dragPosition < itemMidpoint ? itemPosition.top : itemPosition.top + itemSize,
				left: itemPosition.left
			};
		}
		// Store whether we are before or after an item to rearrange
		// For horizontal layout, we need to account for RTL, as this is flipped
		if (  this.orientation === 'horizontal' && this.$element.css( 'direction' ) === 'rtl' ) {
			this.sideInsertion = dragPosition < itemMidpoint ? 'after' : 'before';
		} else {
			this.sideInsertion = dragPosition < itemMidpoint ? 'before' : 'after';
		}
		// Add drop indicator between objects
		if ( this.sideInsertion ) {
			this.$placeholder
				.css( cssOutput )
				.show();
		} else {
			this.$placeholder
				.css( {
					left: 0,
					top: 0
				} )
				.hide();
		}
	} else {
		// This means the item was dragged outside the widget
		this.$placeholder
			.css( 'left', 0 )
			.hide();
	}
	// Prevent default
	e.preventDefault();
};

/**
 * Set a dragged item
 * @param {OO.ui.DraggableElement} item Dragged item
 */
OO.ui.DraggableGroupElement.prototype.setDragItem = function ( item ) {
	this.dragItem = item;
};

/**
 * Unset the current dragged item
 */
OO.ui.DraggableGroupElement.prototype.unsetDragItem = function () {
	this.dragItem = null;
	this.itemDragOver = null;
	this.$placeholder.hide();
	this.sideInsertion = '';
};

/**
 * Get the current dragged item
 * @return {OO.ui.DraggableElement|null} item Dragged item or null if no item is dragged
 */
OO.ui.DraggableGroupElement.prototype.getDragItem = function () {
	return this.dragItem;
};

/**
 * Check if there's an item being dragged.
 * @return {Boolean} Item is being dragged
 */
OO.ui.DraggableGroupElement.prototype.isDragging = function () {
	return this.getDragItem() !== null;
};

/**
 * Element containing an icon.
 *
 * Icons are graphics, about the size of normal text. They can be used to aid the user in locating
 * a control or convey information in a more space efficient way. Icons should rarely be used
 * without labels; such as in a toolbar where space is at a premium or within a context where the
 * meaning is very clear to the user.
 *
 * @abstract
 * @class
 *
 * @constructor
 * @param {Object} [config] Configuration options
 * @cfg {jQuery} [$icon] Icon node, assigned to #$icon, omit to use a generated `<span>`
 * @cfg {Object|string} [icon=''] Symbolic icon name, or map of icon names keyed by language ID;
 *  use the 'default' key to specify the icon to be used when there is no icon in the user's
 *  language
 * @cfg {string} [iconTitle] Icon title text or a function that returns text
 */
OO.ui.IconElement = function OoUiIconElement( config ) {
	// Configuration initialization
	config = config || {};

	// Properties
	this.$icon = null;
	this.icon = null;
	this.iconTitle = null;

	// Initialization
	this.setIcon( config.icon || this.constructor.static.icon );
	this.setIconTitle( config.iconTitle || this.constructor.static.iconTitle );
	this.setIconElement( config.$icon || this.$( '<span>' ) );
};

/* Setup */

OO.initClass( OO.ui.IconElement );

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
 *     { default: 'bold-a', en: 'bold-b', de: 'bold-f' }
 *
 * @static
 * @inheritable
 * @property {Object|string} Symbolic icon name, or map of icon names keyed by language ID;
 *  use the 'default' key to specify the icon to be used when there is no icon in the user's
 *  language
 */
OO.ui.IconElement.static.icon = null;

/**
 * Icon title.
 *
 * @static
 * @inheritable
 * @property {string|Function|null} Icon title text, a function that returns text or null for no
 *  icon title
 */
OO.ui.IconElement.static.iconTitle = null;

/* Methods */

/**
 * Set the icon element.
 *
 * If an element is already set, it will be cleaned up before setting up the new element.
 *
 * @param {jQuery} $icon Element to use as icon
 */
OO.ui.IconElement.prototype.setIconElement = function ( $icon ) {
	if ( this.$icon ) {
		this.$icon
			.removeClass( 'oo-ui-iconElement-icon oo-ui-icon-' + this.icon )
			.removeAttr( 'title' );
	}

	this.$icon = $icon
		.addClass( 'oo-ui-iconElement-icon' )
		.toggleClass( 'oo-ui-icon-' + this.icon, !!this.icon );
	if ( this.iconTitle !== null ) {
		this.$icon.attr( 'title', this.iconTitle );
	}
};

/**
 * Set icon name.
 *
 * @param {Object|string|null} icon Symbolic icon name, or map of icon names keyed by language ID;
 *  use the 'default' key to specify the icon to be used when there is no icon in the user's
 *  language, use null to remove icon
 * @chainable
 */
OO.ui.IconElement.prototype.setIcon = function ( icon ) {
	icon = OO.isPlainObject( icon ) ? OO.ui.getLocalValue( icon, null, 'default' ) : icon;
	icon = typeof icon === 'string' && icon.trim().length ? icon.trim() : null;

	if ( this.icon !== icon ) {
		if ( this.$icon ) {
			if ( this.icon !== null ) {
				this.$icon.removeClass( 'oo-ui-icon-' + this.icon );
			}
			if ( icon !== null ) {
				this.$icon.addClass( 'oo-ui-icon-' + icon );
			}
		}
		this.icon = icon;
	}

	this.$element.toggleClass( 'oo-ui-iconElement', !!this.icon );
	this.updateThemeClasses();

	return this;
};

/**
 * Set icon title.
 *
 * @param {string|Function|null} icon Icon title text, a function that returns text or null
 *  for no icon title
 * @chainable
 */
OO.ui.IconElement.prototype.setIconTitle = function ( iconTitle ) {
	iconTitle = typeof iconTitle === 'function' ||
		( typeof iconTitle === 'string' && iconTitle.length ) ?
			OO.ui.resolveMsg( iconTitle ) : null;

	if ( this.iconTitle !== iconTitle ) {
		this.iconTitle = iconTitle;
		if ( this.$icon ) {
			if ( this.iconTitle !== null ) {
				this.$icon.attr( 'title', iconTitle );
			} else {
				this.$icon.removeAttr( 'title' );
			}
		}
	}

	return this;
};

/**
 * Get icon name.
 *
 * @return {string} Icon name
 */
OO.ui.IconElement.prototype.getIcon = function () {
	return this.icon;
};

/**
 * Element containing an indicator.
 *
 * Indicators are graphics, smaller than normal text. They can be used to describe unique status or
 * behavior. Indicators should only be used in exceptional cases; such as a button that opens a menu
 * instead of performing an action directly, or an item in a list which has errors that need to be
 * resolved.
 *
 * @abstract
 * @class
 *
 * @constructor
 * @param {Object} [config] Configuration options
 * @cfg {jQuery} [$indicator] Indicator node, assigned to #$indicator, omit to use a generated
 *   `<span>`
 * @cfg {string} [indicator] Symbolic indicator name
 * @cfg {string} [indicatorTitle] Indicator title text or a function that returns text
 */
OO.ui.IndicatorElement = function OoUiIndicatorElement( config ) {
	// Configuration initialization
	config = config || {};

	// Properties
	this.$indicator = null;
	this.indicator = null;
	this.indicatorTitle = null;

	// Initialization
	this.setIndicator( config.indicator || this.constructor.static.indicator );
	this.setIndicatorTitle( config.indicatorTitle || this.constructor.static.indicatorTitle );
	this.setIndicatorElement( config.$indicator || this.$( '<span>' ) );
};

/* Setup */

OO.initClass( OO.ui.IndicatorElement );

/* Static Properties */

/**
 * indicator.
 *
 * @static
 * @inheritable
 * @property {string|null} Symbolic indicator name
 */
OO.ui.IndicatorElement.static.indicator = null;

/**
 * Indicator title.
 *
 * @static
 * @inheritable
 * @property {string|Function|null} Indicator title text, a function that returns text or null for no
 *  indicator title
 */
OO.ui.IndicatorElement.static.indicatorTitle = null;

/* Methods */

/**
 * Set the indicator element.
 *
 * If an element is already set, it will be cleaned up before setting up the new element.
 *
 * @param {jQuery} $indicator Element to use as indicator
 */
OO.ui.IndicatorElement.prototype.setIndicatorElement = function ( $indicator ) {
	if ( this.$indicator ) {
		this.$indicator
			.removeClass( 'oo-ui-indicatorElement-indicator oo-ui-indicator-' + this.indicator )
			.removeAttr( 'title' );
	}

	this.$indicator = $indicator
		.addClass( 'oo-ui-indicatorElement-indicator' )
		.toggleClass( 'oo-ui-indicator-' + this.indicator, !!this.indicator );
	if ( this.indicatorTitle !== null ) {
		this.$indicatorTitle.attr( 'title', this.indicatorTitle );
	}
};

/**
 * Set indicator name.
 *
 * @param {string|null} indicator Symbolic name of indicator to use or null for no indicator
 * @chainable
 */
OO.ui.IndicatorElement.prototype.setIndicator = function ( indicator ) {
	indicator = typeof indicator === 'string' && indicator.length ? indicator.trim() : null;

	if ( this.indicator !== indicator ) {
		if ( this.$indicator ) {
			if ( this.indicator !== null ) {
				this.$indicator.removeClass( 'oo-ui-indicator-' + this.indicator );
			}
			if ( indicator !== null ) {
				this.$indicator.addClass( 'oo-ui-indicator-' + indicator );
			}
		}
		this.indicator = indicator;
	}

	this.$element.toggleClass( 'oo-ui-indicatorElement', !!this.indicator );
	this.updateThemeClasses();

	return this;
};

/**
 * Set indicator title.
 *
 * @param {string|Function|null} indicator Indicator title text, a function that returns text or
 *   null for no indicator title
 * @chainable
 */
OO.ui.IndicatorElement.prototype.setIndicatorTitle = function ( indicatorTitle ) {
	indicatorTitle = typeof indicatorTitle === 'function' ||
		( typeof indicatorTitle === 'string' && indicatorTitle.length ) ?
			OO.ui.resolveMsg( indicatorTitle ) : null;

	if ( this.indicatorTitle !== indicatorTitle ) {
		this.indicatorTitle = indicatorTitle;
		if ( this.$indicator ) {
			if ( this.indicatorTitle !== null ) {
				this.$indicator.attr( 'title', indicatorTitle );
			} else {
				this.$indicator.removeAttr( 'title' );
			}
		}
	}

	return this;
};

/**
 * Get indicator name.
 *
 * @return {string} Symbolic name of indicator
 */
OO.ui.IndicatorElement.prototype.getIndicator = function () {
	return this.indicator;
};

/**
 * Get indicator title.
 *
 * @return {string} Indicator title text
 */
OO.ui.IndicatorElement.prototype.getIndicatorTitle = function () {
	return this.indicatorTitle;
};

/**
 * Element containing a label.
 *
 * @abstract
 * @class
 *
 * @constructor
 * @param {Object} [config] Configuration options
 * @cfg {jQuery} [$label] Label node, assigned to #$label, omit to use a generated `<span>`
 * @cfg {jQuery|string|Function} [label] Label nodes, text or a function that returns nodes or text
 * @cfg {boolean} [autoFitLabel=true] Whether to fit the label or not.
 */
OO.ui.LabelElement = function OoUiLabelElement( config ) {
	// Configuration initialization
	config = config || {};

	// Properties
	this.$label = null;
	this.label = null;
	this.autoFitLabel = config.autoFitLabel === undefined || !!config.autoFitLabel;

	// Initialization
	this.setLabel( config.label || this.constructor.static.label );
	this.setLabelElement( config.$label || this.$( '<span>' ) );
};

/* Setup */

OO.initClass( OO.ui.LabelElement );

/* Static Properties */

/**
 * Label.
 *
 * @static
 * @inheritable
 * @property {string|Function|null} Label text; a function that returns nodes or text; or null for
 *  no label
 */
OO.ui.LabelElement.static.label = null;

/* Methods */

/**
 * Set the label element.
 *
 * If an element is already set, it will be cleaned up before setting up the new element.
 *
 * @param {jQuery} $label Element to use as label
 */
OO.ui.LabelElement.prototype.setLabelElement = function ( $label ) {
	if ( this.$label ) {
		this.$label.removeClass( 'oo-ui-labelElement-label' ).empty();
	}

	this.$label = $label.addClass( 'oo-ui-labelElement-label' );
	this.setLabelContent( this.label );
};

/**
 * Set the label.
 *
 * An empty string will result in the label being hidden. A string containing only whitespace will
 * be converted to a single `&nbsp;`.
 *
 * @param {jQuery|string|Function|null} label Label nodes; text; a function that returns nodes or
 *  text; or null for no label
 * @chainable
 */
OO.ui.LabelElement.prototype.setLabel = function ( label ) {
	label = typeof label === 'function' ? OO.ui.resolveMsg( label ) : label;
	label = ( typeof label === 'string' && label.length ) || label instanceof jQuery ? label : null;

	if ( this.label !== label ) {
		if ( this.$label ) {
			this.setLabelContent( label );
		}
		this.label = label;
	}

	this.$element.toggleClass( 'oo-ui-labelElement', !!this.label );

	return this;
};

/**
 * Get the label.
 *
 * @return {jQuery|string|Function|null} Label nodes; text; a function that returns nodes or
 *  text; or null for no label
 */
OO.ui.LabelElement.prototype.getLabel = function () {
	return this.label;
};

/**
 * Fit the label.
 *
 * @chainable
 */
OO.ui.LabelElement.prototype.fitLabel = function () {
	if ( this.$label && this.$label.autoEllipsis && this.autoFitLabel ) {
		this.$label.autoEllipsis( { hasSpan: false, tooltip: true } );
	}

	return this;
};

/**
 * Set the content of the label.
 *
 * Do not call this method until after the label element has been set by #setLabelElement.
 *
 * @private
 * @param {jQuery|string|Function|null} label Label nodes; text; a function that returns nodes or
 *  text; or null for no label
 */
OO.ui.LabelElement.prototype.setLabelContent = function ( label ) {
	if ( typeof label === 'string' ) {
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
	}
};

/**
 * Element containing an OO.ui.PopupWidget object.
 *
 * @abstract
 * @class
 *
 * @constructor
 * @param {Object} [config] Configuration options
 * @cfg {Object} [popup] Configuration to pass to popup
 * @cfg {boolean} [autoClose=true] Popup auto-closes when it loses focus
 */
OO.ui.PopupElement = function OoUiPopupElement( config ) {
	// Configuration initialization
	config = config || {};

	// Properties
	this.popup = new OO.ui.PopupWidget( $.extend(
		{ autoClose: true },
		config.popup,
		{ $: this.$, $autoCloseIgnore: this.$element }
	) );
};

/* Methods */

/**
 * Get popup.
 *
 * @return {OO.ui.PopupWidget} Popup widget
 */
OO.ui.PopupElement.prototype.getPopup = function () {
	return this.popup;
};

/**
 * Element with named flags that can be added, removed, listed and checked.
 *
 * A flag, when set, adds a CSS class on the `$element` by combining `oo-ui-flaggedElement-` with
 * the flag name. Flags are primarily useful for styling.
 *
 * @abstract
 * @class
 *
 * @constructor
 * @param {Object} [config] Configuration options
 * @cfg {string|string[]} [flags] Styling flags, e.g. 'primary', 'destructive' or 'constructive'
 * @cfg {jQuery} [$flagged] Flagged node, assigned to #$flagged, omit to use #$element
 */
OO.ui.FlaggedElement = function OoUiFlaggedElement( config ) {
	// Configuration initialization
	config = config || {};

	// Properties
	this.flags = {};
	this.$flagged = null;

	// Initialization
	this.setFlags( config.flags );
	this.setFlaggedElement( config.$flagged || this.$element );
};

/* Events */

/**
 * @event flag
 * @param {Object.<string,boolean>} changes Object keyed by flag name containing boolean
 *   added/removed properties
 */

/* Methods */

/**
 * Set the flagged element.
 *
 * If an element is already set, it will be cleaned up before setting up the new element.
 *
 * @param {jQuery} $flagged Element to add flags to
 */
OO.ui.FlaggedElement.prototype.setFlaggedElement = function ( $flagged ) {
	var classNames = Object.keys( this.flags ).map( function ( flag ) {
		return 'oo-ui-flaggedElement-' + flag;
	} ).join( ' ' );

	if ( this.$flagged ) {
		this.$flagged.removeClass( classNames );
	}

	this.$flagged = $flagged.addClass( classNames );
};

/**
 * Check if a flag is set.
 *
 * @param {string} flag Name of flag
 * @return {boolean} Has flag
 */
OO.ui.FlaggedElement.prototype.hasFlag = function ( flag ) {
	return flag in this.flags;
};

/**
 * Get the names of all flags set.
 *
 * @return {string[]} Flag names
 */
OO.ui.FlaggedElement.prototype.getFlags = function () {
	return Object.keys( this.flags );
};

/**
 * Clear all flags.
 *
 * @chainable
 * @fires flag
 */
OO.ui.FlaggedElement.prototype.clearFlags = function () {
	var flag, className,
		changes = {},
		remove = [],
		classPrefix = 'oo-ui-flaggedElement-';

	for ( flag in this.flags ) {
		className = classPrefix + flag;
		changes[flag] = false;
		delete this.flags[flag];
		remove.push( className );
	}

	if ( this.$flagged ) {
		this.$flagged.removeClass( remove.join( ' ' ) );
	}

	this.updateThemeClasses();
	this.emit( 'flag', changes );

	return this;
};

/**
 * Add one or more flags.
 *
 * @param {string|string[]|Object.<string, boolean>} flags One or more flags to add, or an object
 *  keyed by flag name containing boolean set/remove instructions.
 * @chainable
 * @fires flag
 */
OO.ui.FlaggedElement.prototype.setFlags = function ( flags ) {
	var i, len, flag, className,
		changes = {},
		add = [],
		remove = [],
		classPrefix = 'oo-ui-flaggedElement-';

	if ( typeof flags === 'string' ) {
		className = classPrefix + flags;
		// Set
		if ( !this.flags[flags] ) {
			this.flags[flags] = true;
			add.push( className );
		}
	} else if ( $.isArray( flags ) ) {
		for ( i = 0, len = flags.length; i < len; i++ ) {
			flag = flags[i];
			className = classPrefix + flag;
			// Set
			if ( !this.flags[flag] ) {
				changes[flag] = true;
				this.flags[flag] = true;
				add.push( className );
			}
		}
	} else if ( OO.isPlainObject( flags ) ) {
		for ( flag in flags ) {
			className = classPrefix + flag;
			if ( flags[flag] ) {
				// Set
				if ( !this.flags[flag] ) {
					changes[flag] = true;
					this.flags[flag] = true;
					add.push( className );
				}
			} else {
				// Remove
				if ( this.flags[flag] ) {
					changes[flag] = false;
					delete this.flags[flag];
					remove.push( className );
				}
			}
		}
	}

	if ( this.$flagged ) {
		this.$flagged
			.addClass( add.join( ' ' ) )
			.removeClass( remove.join( ' ' ) );
	}

	this.updateThemeClasses();
	this.emit( 'flag', changes );

	return this;
};

/**
 * Element with a title.
 *
 * Titles are rendered by the browser and are made visible when hovering the element. Titles are
 * not visible on touch devices.
 *
 * @abstract
 * @class
 *
 * @constructor
 * @param {Object} [config] Configuration options
 * @cfg {jQuery} [$titled] Titled node, assigned to #$titled, omit to use #$element
 * @cfg {string|Function} [title] Title text or a function that returns text. If not provided, the
 *    static property 'title' is used.
 */
OO.ui.TitledElement = function OoUiTitledElement( config ) {
	// Configuration initialization
	config = config || {};

	// Properties
	this.$titled = null;
	this.title = null;

	// Initialization
	this.setTitle( config.title || this.constructor.static.title );
	this.setTitledElement( config.$titled || this.$element );
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
 * Set the titled element.
 *
 * If an element is already set, it will be cleaned up before setting up the new element.
 *
 * @param {jQuery} $titled Element to set title on
 */
OO.ui.TitledElement.prototype.setTitledElement = function ( $titled ) {
	if ( this.$titled ) {
		this.$titled.removeAttr( 'title' );
	}

	this.$titled = $titled;
	if ( this.title ) {
		this.$titled.attr( 'title', this.title );
	}
};

/**
 * Set title.
 *
 * @param {string|Function|null} title Title text, a function that returns text or null for no title
 * @chainable
 */
OO.ui.TitledElement.prototype.setTitle = function ( title ) {
	title = typeof title === 'string' ? OO.ui.resolveMsg( title ) : null;

	if ( this.title !== title ) {
		if ( this.$titled ) {
			if ( title !== null ) {
				this.$titled.attr( 'title', title );
			} else {
				this.$titled.removeAttr( 'title' );
			}
		}
		this.title = title;
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
 * Element that can be automatically clipped to visible boundaries.
 *
 * Whenever the element's natural height changes, you have to call
 * #clip to make sure it's still clipping correctly.
 *
 * @abstract
 * @class
 *
 * @constructor
 * @param {Object} [config] Configuration options
 * @cfg {jQuery} [$clippable] Nodes to clip, assigned to #$clippable, omit to use #$element
 */
OO.ui.ClippableElement = function OoUiClippableElement( config ) {
	// Configuration initialization
	config = config || {};

	// Properties
	this.$clippable = null;
	this.clipping = false;
	this.clippedHorizontally = false;
	this.clippedVertically = false;
	this.$clippableContainer = null;
	this.$clippableScroller = null;
	this.$clippableWindow = null;
	this.idealWidth = null;
	this.idealHeight = null;
	this.onClippableContainerScrollHandler = this.clip.bind( this );
	this.onClippableWindowResizeHandler = this.clip.bind( this );

	// Initialization
	this.setClippableElement( config.$clippable || this.$element );
};

/* Methods */

/**
 * Set clippable element.
 *
 * If an element is already set, it will be cleaned up before setting up the new element.
 *
 * @param {jQuery} $clippable Element to make clippable
 */
OO.ui.ClippableElement.prototype.setClippableElement = function ( $clippable ) {
	if ( this.$clippable ) {
		this.$clippable.removeClass( 'oo-ui-clippableElement-clippable' );
		this.$clippable.css( { width: '', height: '' } );
		this.$clippable.width(); // Force reflow for https://code.google.com/p/chromium/issues/detail?id=387290
		this.$clippable.css( { overflowX: '', overflowY: '' } );
	}

	this.$clippable = $clippable.addClass( 'oo-ui-clippableElement-clippable' );
	this.clip();
};

/**
 * Toggle clipping.
 *
 * Do not turn clipping on until after the element is attached to the DOM and visible.
 *
 * @param {boolean} [clipping] Enable clipping, omit to toggle
 * @chainable
 */
OO.ui.ClippableElement.prototype.toggleClipping = function ( clipping ) {
	clipping = clipping === undefined ? !this.clipping : !!clipping;

	if ( this.clipping !== clipping ) {
		this.clipping = clipping;
		if ( clipping ) {
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
			this.clip();
		} else {
			this.$clippable.css( { width: '', height: '' } );
			this.$clippable.width(); // Force reflow for https://code.google.com/p/chromium/issues/detail?id=387290
			this.$clippable.css( { overflowX: '', overflowY: '' } );

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
	return this.clippedHorizontally || this.clippedVertically;
};

/**
 * Check if the right of the element is being clipped by the nearest scrollable container.
 *
 * @return {boolean} Part of the element is being clipped
 */
OO.ui.ClippableElement.prototype.isClippedHorizontally = function () {
	return this.clippedHorizontally;
};

/**
 * Check if the bottom of the element is being clipped by the nearest scrollable container.
 *
 * @return {boolean} Part of the element is being clipped
 */
OO.ui.ClippableElement.prototype.isClippedVertically = function () {
	return this.clippedVertically;
};

/**
 * Set the ideal size. These are the dimensions the element will have when it's not being clipped.
 *
 * @param {number|string} [width] Width as a number of pixels or CSS string with unit suffix
 * @param {number|string} [height] Height as a number of pixels or CSS string with unit suffix
 */
OO.ui.ClippableElement.prototype.setIdealSize = function ( width, height ) {
	this.idealWidth = width;
	this.idealHeight = height;

	if ( !this.clipping ) {
		// Update dimensions
		this.$clippable.css( { width: width, height: height } );
	}
	// While clipping, idealWidth and idealHeight are not considered
};

/**
 * Clip element to visible boundaries and allow scrolling when needed. Call this method when
 * the element's natural height changes.
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
		$container = this.$clippableContainer.is( 'body' ) ?
			this.$clippableWindow : this.$clippableContainer,
		ccOffset = $container.offset() || { top: 0, left: 0 },
		ccHeight = $container.innerHeight() - buffer,
		ccWidth = $container.innerWidth() - buffer,
		scrollTop = this.$clippableScroller.scrollTop(),
		scrollLeft = this.$clippableScroller.scrollLeft(),
		desiredWidth = ( ccOffset.left + scrollLeft + ccWidth ) - cOffset.left,
		desiredHeight = ( ccOffset.top + scrollTop + ccHeight ) - cOffset.top,
		naturalWidth = this.$clippable.prop( 'scrollWidth' ),
		naturalHeight = this.$clippable.prop( 'scrollHeight' ),
		clipWidth = desiredWidth < naturalWidth,
		clipHeight = desiredHeight < naturalHeight;

	if ( clipWidth ) {
		this.$clippable.css( { overflowX: 'scroll', width: desiredWidth } );
	} else {
		this.$clippable.css( 'width', this.idealWidth || '' );
		this.$clippable.width(); // Force reflow for https://code.google.com/p/chromium/issues/detail?id=387290
		this.$clippable.css( 'overflowX', '' );
	}
	if ( clipHeight ) {
		this.$clippable.css( { overflowY: 'scroll', height: desiredHeight } );
	} else {
		this.$clippable.css( 'height', this.idealHeight || '' );
		this.$clippable.height(); // Force reflow for https://code.google.com/p/chromium/issues/detail?id=387290
		this.$clippable.css( 'overflowY', '' );
	}

	this.clippedHorizontally = clipWidth;
	this.clippedVertically = clipHeight;

	return this;
};

/**
 * Generic toolbar tool.
 *
 * @abstract
 * @class
 * @extends OO.ui.Widget
 * @mixins OO.ui.IconElement
 * @mixins OO.ui.FlaggedElement
 *
 * @constructor
 * @param {OO.ui.ToolGroup} toolGroup
 * @param {Object} [config] Configuration options
 * @cfg {string|Function} [title] Title text or a function that returns text
 */
OO.ui.Tool = function OoUiTool( toolGroup, config ) {
	// Configuration initialization
	config = config || {};

	// Parent constructor
	OO.ui.Tool.super.call( this, config );

	// Mixin constructors
	OO.ui.IconElement.call( this, config );
	OO.ui.FlaggedElement.call( this, config );

	// Properties
	this.toolGroup = toolGroup;
	this.toolbar = this.toolGroup.getToolbar();
	this.active = false;
	this.$title = this.$( '<span>' );
	this.$titleText = this.$( '<span>' );
	this.$accel = this.$( '<span>' );
	this.$link = this.$( '<a>' );
	this.title = null;

	// Events
	this.toolbar.connect( this, { updateState: 'onUpdateState' } );

	// Initialization
	this.$titleText.addClass( 'oo-ui-tool-title-text' );
	this.$accel
		.addClass( 'oo-ui-tool-accel' )
		.prop( {
			// This may need to be changed if the key names are ever localized,
			// but for now they are essentially written in English
			dir: 'ltr',
			lang: 'en'
		} );
	this.$title
		.addClass( 'oo-ui-tool-title' )
		.append( this.$titleText, this.$accel );
	this.$link
		.addClass( 'oo-ui-tool-link' )
		.append( this.$icon, this.$title )
		.prop( 'tabIndex', 0 )
		.attr( 'role', 'button' );
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
OO.mixinClass( OO.ui.Tool, OO.ui.IconElement );
OO.mixinClass( OO.ui.Tool, OO.ui.FlaggedElement );

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
 * @return {boolean} Button is active
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

	this.$titleText.text( this.title );
	this.$accel.text( accel );

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
	OO.ui.GroupElement.call( this, config );

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
		.on( 'mousedown touchstart', this.onPointerDown.bind( this ) );

	// Initialization
	this.$group.addClass( 'oo-ui-toolbar-tools' );
	if ( config.actions ) {
		this.$bar.append( this.$actions.addClass( 'oo-ui-toolbar-actions' ) );
	}
	this.$bar
		.addClass( 'oo-ui-toolbar-bar' )
		.append( this.$group, '<div style="clear:both"></div>' );
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
OO.ui.Toolbar.prototype.onPointerDown = function ( e ) {
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
 * - A specific tool: `{ name: 'tool-name' }` or `'tool-name'`
 * - All tools in a group: `{ group: 'group-name' }`
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
				group.label = OO.ui.msg( 'ooui-toolbar-more' );
			}
		}
		// Check type has been registered
		type = this.getToolGroupFactory().lookup( group.type ) ? group.type : defaultType;
		items.push(
			this.getToolGroupFactory().create( type, this, $.extend( { $: this.$ }, group ) )
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
 * Collection of tools.
 *
 * Tools can be specified in the following ways:
 *
 * - A specific tool: `{ name: 'tool-name' }` or `'tool-name'`
 * - All tools in a group: `{ group: 'group-name' }`
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
	config = config || {};

	// Parent constructor
	OO.ui.ToolGroup.super.call( this, config );

	// Mixin constructors
	OO.ui.GroupElement.call( this, config );

	// Properties
	this.toolbar = toolbar;
	this.tools = {};
	this.pressed = null;
	this.autoDisabled = false;
	this.include = config.include || [];
	this.exclude = config.exclude || [];
	this.promote = config.promote || [];
	this.demote = config.demote || [];
	this.onCapturedMouseUpHandler = this.onCapturedMouseUp.bind( this );

	// Events
	this.$element.on( {
		'mousedown touchstart': this.onPointerDown.bind( this ),
		'mouseup touchend': this.onPointerUp.bind( this ),
		mouseover: this.onMouseOver.bind( this ),
		mouseout: this.onMouseOut.bind( this )
	} );
	this.toolbar.getToolFactory().connect( this, { register: 'onToolFactoryRegister' } );
	this.aggregate( { disable: 'itemDisable' } );
	this.connect( this, { itemDisable: 'updateDisabled' } );

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
OO.ui.ToolGroup.prototype.onPointerDown = function ( e ) {
	// e.which is 0 for touch events, 1 for left mouse button
	if ( !this.isDisabled() && e.which <= 1 ) {
		this.pressed = this.getTargetTool( e );
		if ( this.pressed ) {
			this.pressed.setActive( true );
			this.getElementDocument().addEventListener(
				'mouseup', this.onCapturedMouseUpHandler, true
			);
		}
	}
	return false;
};

/**
 * Handle captured mouse up events.
 *
 * @param {Event} e Mouse up event
 */
OO.ui.ToolGroup.prototype.onCapturedMouseUp = function ( e ) {
	this.getElementDocument().removeEventListener( 'mouseup', this.onCapturedMouseUpHandler, true );
	// onPointerUp may be called a second time, depending on where the mouse is when the button is
	// released, but since `this.pressed` will no longer be true, the second call will be ignored.
	this.onPointerUp( e );
};

/**
 * Handle mouse up events.
 *
 * @param {jQuery.Event} e Mouse up event
 */
OO.ui.ToolGroup.prototype.onPointerUp = function ( e ) {
	var tool = this.getTargetTool( e );

	// e.which is 0 for touch events, 1 for left mouse button
	if ( !this.isDisabled() && e.which <= 1 && this.pressed && this.pressed === tool ) {
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
 * Dialog for showing a message.
 *
 * User interface:
 * - Registers two actions by default (safe and primary).
 * - Renders action widgets in the footer.
 *
 * @class
 * @extends OO.ui.Dialog
 *
 * @constructor
 * @param {Object} [config] Configuration options
 */
OO.ui.MessageDialog = function OoUiMessageDialog( config ) {
	// Parent constructor
	OO.ui.MessageDialog.super.call( this, config );

	// Properties
	this.verticalActionLayout = null;

	// Initialization
	this.$element.addClass( 'oo-ui-messageDialog' );
};

/* Inheritance */

OO.inheritClass( OO.ui.MessageDialog, OO.ui.Dialog );

/* Static Properties */

OO.ui.MessageDialog.static.name = 'message';

OO.ui.MessageDialog.static.size = 'small';

OO.ui.MessageDialog.static.verbose = false;

/**
 * Dialog title.
 *
 * A confirmation dialog's title should describe what the progressive action will do. An alert
 * dialog's title should describe what event occurred.
 *
 * @static
 * inheritable
 * @property {jQuery|string|Function|null}
 */
OO.ui.MessageDialog.static.title = null;

/**
 * A confirmation dialog's message should describe the consequences of the progressive action. An
 * alert dialog's message should describe why the event occurred.
 *
 * @static
 * inheritable
 * @property {jQuery|string|Function|null}
 */
OO.ui.MessageDialog.static.message = null;

OO.ui.MessageDialog.static.actions = [
	{ action: 'accept', label: OO.ui.deferMsg( 'ooui-dialog-message-accept' ), flags: 'primary' },
	{ action: 'reject', label: OO.ui.deferMsg( 'ooui-dialog-message-reject' ), flags: 'safe' }
];

/* Methods */

/**
 * @inheritdoc
 */
OO.ui.MessageDialog.prototype.setManager = function ( manager ) {
	OO.ui.MessageDialog.super.prototype.setManager.call( this, manager );

	// Events
	this.manager.connect( this, {
		resize: 'onResize'
	} );

	return this;
};

/**
 * @inheritdoc
 */
OO.ui.MessageDialog.prototype.onActionResize = function ( action ) {
	this.fitActions();
	return OO.ui.MessageDialog.super.prototype.onActionResize.call( this, action );
};

/**
 * Handle window resized events.
 */
OO.ui.MessageDialog.prototype.onResize = function () {
	var dialog = this;
	dialog.fitActions();
	// Wait for CSS transition to finish and do it again :(
	setTimeout( function () {
		dialog.fitActions();
	}, 300 );
};

/**
 * Toggle action layout between vertical and horizontal.
 *
 * @param {boolean} [value] Layout actions vertically, omit to toggle
 * @chainable
 */
OO.ui.MessageDialog.prototype.toggleVerticalActionLayout = function ( value ) {
	value = value === undefined ? !this.verticalActionLayout : !!value;

	if ( value !== this.verticalActionLayout ) {
		this.verticalActionLayout = value;
		this.$actions
			.toggleClass( 'oo-ui-messageDialog-actions-vertical', value )
			.toggleClass( 'oo-ui-messageDialog-actions-horizontal', !value );
	}

	return this;
};

/**
 * @inheritdoc
 */
OO.ui.MessageDialog.prototype.getActionProcess = function ( action ) {
	if ( action ) {
		return new OO.ui.Process( function () {
			this.close( { action: action } );
		}, this );
	}
	return OO.ui.MessageDialog.super.prototype.getActionProcess.call( this, action );
};

/**
 * @inheritdoc
 *
 * @param {Object} [data] Dialog opening data
 * @param {jQuery|string|Function|null} [data.title] Description of the action being confirmed
 * @param {jQuery|string|Function|null} [data.message] Description of the action's consequence
 * @param {boolean} [data.verbose] Message is verbose and should be styled as a long message
 * @param {Object[]} [data.actions] List of OO.ui.ActionOptionWidget configuration options for each
 *   action item
 */
OO.ui.MessageDialog.prototype.getSetupProcess = function ( data ) {
	data = data || {};

	// Parent method
	return OO.ui.MessageDialog.super.prototype.getSetupProcess.call( this, data )
		.next( function () {
			this.title.setLabel(
				data.title !== undefined ? data.title : this.constructor.static.title
			);
			this.message.setLabel(
				data.message !== undefined ? data.message : this.constructor.static.message
			);
			this.message.$element.toggleClass(
				'oo-ui-messageDialog-message-verbose',
				data.verbose !== undefined ? data.verbose : this.constructor.static.verbose
			);
		}, this );
};

/**
 * @inheritdoc
 */
OO.ui.MessageDialog.prototype.getBodyHeight = function () {
	var bodyHeight, oldOverflow,
		$scrollable = this.container.$element;

	oldOverflow = $scrollable[0].style.overflow;
	$scrollable[0].style.overflow = 'hidden';

	// Force… ugh… something to happen
	$scrollable.contents().hide();
	$scrollable.height();
	$scrollable.contents().show();

	bodyHeight = Math.round( this.text.$element.outerHeight( true ) );
	$scrollable[0].style.overflow = oldOverflow;

	return bodyHeight;
};

/**
 * @inheritdoc
 */
OO.ui.MessageDialog.prototype.setDimensions = function ( dim ) {
	var $scrollable = this.container.$element;
	OO.ui.MessageDialog.super.prototype.setDimensions.call( this, dim );

	// Twiddle the overflow property, otherwise an unnecessary scrollbar will be produced.
	// Need to do it after transition completes (250ms), add 50ms just in case.
	setTimeout( function () {
		var oldOverflow = $scrollable[0].style.overflow;
		$scrollable[0].style.overflow = 'hidden';

		// Force… ugh… something to happen
		$scrollable.contents().hide();
		$scrollable.height();
		$scrollable.contents().show();

		$scrollable[0].style.overflow = oldOverflow;
	}, 300 );

	return this;
};

/**
 * @inheritdoc
 */
OO.ui.MessageDialog.prototype.initialize = function () {
	// Parent method
	OO.ui.MessageDialog.super.prototype.initialize.call( this );

	// Properties
	this.$actions = this.$( '<div>' );
	this.container = new OO.ui.PanelLayout( {
		$: this.$, scrollable: true, classes: [ 'oo-ui-messageDialog-container' ]
	} );
	this.text = new OO.ui.PanelLayout( {
		$: this.$, padded: true, expanded: false, classes: [ 'oo-ui-messageDialog-text' ]
	} );
	this.message = new OO.ui.LabelWidget( {
		$: this.$, classes: [ 'oo-ui-messageDialog-message' ]
	} );

	// Initialization
	this.title.$element.addClass( 'oo-ui-messageDialog-title' );
	this.$content.addClass( 'oo-ui-messageDialog-content' );
	this.container.$element.append( this.text.$element );
	this.text.$element.append( this.title.$element, this.message.$element );
	this.$body.append( this.container.$element );
	this.$actions.addClass( 'oo-ui-messageDialog-actions' );
	this.$foot.append( this.$actions );
};

/**
 * @inheritdoc
 */
OO.ui.MessageDialog.prototype.attachActions = function () {
	var i, len, other, special, others;

	// Parent method
	OO.ui.MessageDialog.super.prototype.attachActions.call( this );

	special = this.actions.getSpecial();
	others = this.actions.getOthers();
	if ( special.safe ) {
		this.$actions.append( special.safe.$element );
		special.safe.toggleFramed( false );
	}
	if ( others.length ) {
		for ( i = 0, len = others.length; i < len; i++ ) {
			other = others[i];
			this.$actions.append( other.$element );
			other.toggleFramed( false );
		}
	}
	if ( special.primary ) {
		this.$actions.append( special.primary.$element );
		special.primary.toggleFramed( false );
	}

	if ( !this.isOpening() ) {
		// If the dialog is currently opening, this will be called automatically soon.
		// This also calls #fitActions.
		this.manager.updateWindowSize( this );
	}
};

/**
 * Fit action actions into columns or rows.
 *
 * Columns will be used if all labels can fit without overflow, otherwise rows will be used.
 */
OO.ui.MessageDialog.prototype.fitActions = function () {
	var i, len, action,
		previous = this.verticalActionLayout,
		actions = this.actions.get();

	// Detect clipping
	this.toggleVerticalActionLayout( false );
	for ( i = 0, len = actions.length; i < len; i++ ) {
		action = actions[i];
		if ( action.$element.innerWidth() < action.$label.outerWidth( true ) ) {
			this.toggleVerticalActionLayout( true );
			break;
		}
	}

	if ( this.verticalActionLayout !== previous ) {
		this.$body.css( 'bottom', this.$foot.outerHeight( true ) );
		// We changed the layout, window height might need to be updated.
		this.manager.updateWindowSize( this );
	}
};

/**
 * Navigation dialog window.
 *
 * Logic:
 * - Show and hide errors.
 * - Retry an action.
 *
 * User interface:
 * - Renders header with dialog title and one action widget on either side
 *   (a 'safe' button on the left, and a 'primary' button on the right, both of
 *   which close the dialog).
 * - Displays any action widgets in the footer (none by default).
 * - Ability to dismiss errors.
 *
 * Subclass responsibilities:
 * - Register a 'safe' action.
 * - Register a 'primary' action.
 * - Add content to the dialog.
 *
 * @abstract
 * @class
 * @extends OO.ui.Dialog
 *
 * @constructor
 * @param {Object} [config] Configuration options
 */
OO.ui.ProcessDialog = function OoUiProcessDialog( config ) {
	// Parent constructor
	OO.ui.ProcessDialog.super.call( this, config );

	// Initialization
	this.$element.addClass( 'oo-ui-processDialog' );
};

/* Setup */

OO.inheritClass( OO.ui.ProcessDialog, OO.ui.Dialog );

/* Methods */

/**
 * Handle dismiss button click events.
 *
 * Hides errors.
 */
OO.ui.ProcessDialog.prototype.onDismissErrorButtonClick = function () {
	this.hideErrors();
};

/**
 * Handle retry button click events.
 *
 * Hides errors and then tries again.
 */
OO.ui.ProcessDialog.prototype.onRetryButtonClick = function () {
	this.hideErrors();
	this.executeAction( this.currentAction.getAction() );
};

/**
 * @inheritdoc
 */
OO.ui.ProcessDialog.prototype.onActionResize = function ( action ) {
	if ( this.actions.isSpecial( action ) ) {
		this.fitLabel();
	}
	return OO.ui.ProcessDialog.super.prototype.onActionResize.call( this, action );
};

/**
 * @inheritdoc
 */
OO.ui.ProcessDialog.prototype.initialize = function () {
	// Parent method
	OO.ui.ProcessDialog.super.prototype.initialize.call( this );

	// Properties
	this.$navigation = this.$( '<div>' );
	this.$location = this.$( '<div>' );
	this.$safeActions = this.$( '<div>' );
	this.$primaryActions = this.$( '<div>' );
	this.$otherActions = this.$( '<div>' );
	this.dismissButton = new OO.ui.ButtonWidget( {
		$: this.$,
		label: OO.ui.msg( 'ooui-dialog-process-dismiss' )
	} );
	this.retryButton = new OO.ui.ButtonWidget( { $: this.$ } );
	this.$errors = this.$( '<div>' );
	this.$errorsTitle = this.$( '<div>' );

	// Events
	this.dismissButton.connect( this, { click: 'onDismissErrorButtonClick' } );
	this.retryButton.connect( this, { click: 'onRetryButtonClick' } );

	// Initialization
	this.title.$element.addClass( 'oo-ui-processDialog-title' );
	this.$location
		.append( this.title.$element )
		.addClass( 'oo-ui-processDialog-location' );
	this.$safeActions.addClass( 'oo-ui-processDialog-actions-safe' );
	this.$primaryActions.addClass( 'oo-ui-processDialog-actions-primary' );
	this.$otherActions.addClass( 'oo-ui-processDialog-actions-other' );
	this.$errorsTitle
		.addClass( 'oo-ui-processDialog-errors-title' )
		.text( OO.ui.msg( 'ooui-dialog-process-error' ) );
	this.$errors
		.addClass( 'oo-ui-processDialog-errors' )
		.append( this.$errorsTitle, this.dismissButton.$element, this.retryButton.$element );
	this.$content
		.addClass( 'oo-ui-processDialog-content' )
		.append( this.$errors );
	this.$navigation
		.addClass( 'oo-ui-processDialog-navigation' )
		.append( this.$safeActions, this.$location, this.$primaryActions );
	this.$head.append( this.$navigation );
	this.$foot.append( this.$otherActions );
};

/**
 * @inheritdoc
 */
OO.ui.ProcessDialog.prototype.attachActions = function () {
	var i, len, other, special, others;

	// Parent method
	OO.ui.ProcessDialog.super.prototype.attachActions.call( this );

	special = this.actions.getSpecial();
	others = this.actions.getOthers();
	if ( special.primary ) {
		this.$primaryActions.append( special.primary.$element );
		special.primary.toggleFramed( true );
	}
	if ( others.length ) {
		for ( i = 0, len = others.length; i < len; i++ ) {
			other = others[i];
			this.$otherActions.append( other.$element );
			other.toggleFramed( true );
		}
	}
	if ( special.safe ) {
		this.$safeActions.append( special.safe.$element );
		special.safe.toggleFramed( true );
	}

	this.fitLabel();
	this.$body.css( 'bottom', this.$foot.outerHeight( true ) );
};

/**
 * @inheritdoc
 */
OO.ui.ProcessDialog.prototype.executeAction = function ( action ) {
	OO.ui.ProcessDialog.super.prototype.executeAction.call( this, action )
		.fail( this.showErrors.bind( this ) );
};

/**
 * Fit label between actions.
 *
 * @chainable
 */
OO.ui.ProcessDialog.prototype.fitLabel = function () {
	var width = Math.max(
		this.$safeActions.is( ':visible' ) ? this.$safeActions.width() : 0,
		this.$primaryActions.is( ':visible' ) ? this.$primaryActions.width() : 0
	);
	this.$location.css( { paddingLeft: width, paddingRight: width } );

	return this;
};

/**
 * Handle errors that occurred during accept or reject processes.
 *
 * @param {OO.ui.Error[]} errors Errors to be handled
 */
OO.ui.ProcessDialog.prototype.showErrors = function ( errors ) {
	var i, len, $item,
		items = [],
		recoverable = true,
		warning = false;

	for ( i = 0, len = errors.length; i < len; i++ ) {
		if ( !errors[i].isRecoverable() ) {
			recoverable = false;
		}
		if ( errors[i].isWarning() ) {
			warning = true;
		}
		$item = this.$( '<div>' )
			.addClass( 'oo-ui-processDialog-error' )
			.append( errors[i].getMessage() );
		items.push( $item[0] );
	}
	this.$errorItems = this.$( items );
	if ( recoverable ) {
		this.retryButton.clearFlags().setFlags( this.currentAction.getFlags() );
	} else {
		this.currentAction.setDisabled( true );
	}
	if ( warning ) {
		this.retryButton.setLabel( OO.ui.msg( 'ooui-dialog-process-continue' ) );
	} else {
		this.retryButton.setLabel( OO.ui.msg( 'ooui-dialog-process-retry' ) );
	}
	this.retryButton.toggle( recoverable );
	this.$errorsTitle.after( this.$errorItems );
	this.$errors.show().scrollTop( 0 );
};

/**
 * Hide errors.
 */
OO.ui.ProcessDialog.prototype.hideErrors = function () {
	this.$errors.hide();
	this.$errorItems.remove();
	this.$errorItems = null;
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
 * @cfg {boolean} [autoFocus=true] Focus on the first focusable element when changing to a page
 * @cfg {boolean} [outlined=false] Show an outline
 * @cfg {boolean} [editable=false] Show controls for adding, removing and reordering pages
 */
OO.ui.BookletLayout = function OoUiBookletLayout( config ) {
	// Configuration initialization
	config = config || {};

	// Parent constructor
	OO.ui.BookletLayout.super.call( this, config );

	// Properties
	this.currentPageName = null;
	this.pages = {};
	this.ignoreFocus = false;
	this.stackLayout = new OO.ui.StackLayout( { $: this.$, continuous: !!config.continuous } );
	this.autoFocus = config.autoFocus === undefined || !!config.autoFocus;
	this.outlineVisible = false;
	this.outlined = !!config.outlined;
	if ( this.outlined ) {
		this.editable = !!config.editable;
		this.outlineControlsWidget = null;
		this.outlineSelectWidget = new OO.ui.OutlineSelectWidget( { $: this.$ } );
		this.outlinePanel = new OO.ui.PanelLayout( { $: this.$, scrollable: true } );
		this.gridLayout = new OO.ui.GridLayout(
			[ this.outlinePanel, this.stackLayout ],
			{ $: this.$, widths: [ 1, 2 ] }
		);
		this.outlineVisible = true;
		if ( this.editable ) {
			this.outlineControlsWidget = new OO.ui.OutlineControlsWidget(
				this.outlineSelectWidget, { $: this.$ }
			);
		}
	}

	// Events
	this.stackLayout.connect( this, { set: 'onStackLayoutSet' } );
	if ( this.outlined ) {
		this.outlineSelectWidget.connect( this, { select: 'onOutlineSelectWidgetSelect' } );
	}
	if ( this.autoFocus ) {
		// Event 'focus' does not bubble, but 'focusin' does
		this.stackLayout.onDOMEvent( 'focusin', this.onStackLayoutFocus.bind( this ) );
	}

	// Initialization
	this.$element.addClass( 'oo-ui-bookletLayout' );
	this.stackLayout.$element.addClass( 'oo-ui-bookletLayout-stackLayout' );
	if ( this.outlined ) {
		this.outlinePanel.$element
			.addClass( 'oo-ui-bookletLayout-outlinePanel' )
			.append( this.outlineSelectWidget.$element );
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

	// Find the page that an element was focused within
	$target = $( e.target ).closest( '.oo-ui-pageLayout' );
	for ( name in this.pages ) {
		// Check for page match, exclude current page to find only page changes
		if ( this.pages[name].$element[0] === $target[0] && name !== this.currentPageName ) {
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
	var layout = this;
	if ( page ) {
		page.scrollElementIntoView( { complete: function () {
			if ( layout.autoFocus ) {
				layout.focus();
			}
		} } );
	}
};

/**
 * Focus the first input in the current page.
 *
 * If no page is selected, the first selectable page will be selected.
 * If the focus is already in an element on the current page, nothing will happen.
 */
OO.ui.BookletLayout.prototype.focus = function () {
	var $input, page = this.stackLayout.getCurrentItem();
	if ( !page && this.outlined ) {
		this.selectFirstSelectablePage();
		page = this.stackLayout.getCurrentItem();
		if ( !page ) {
			return;
		}
	}
	// Only change the focus if is not already in the current page
	if ( !page.$element.find( ':focus' ).length ) {
		$input = page.$element.find( ':input:first' );
		if ( $input.length ) {
			$input[0].focus();
		}
	}
};

/**
 * Handle outline widget select events.
 *
 * @param {OO.ui.OptionWidget|null} item Selected item
 */
OO.ui.BookletLayout.prototype.onOutlineSelectWidgetSelect = function ( item ) {
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
			level = this.outlineSelectWidget.getItemFromData( page.getName() ).getLevel();
			if (
				prev &&
				level === this.outlineSelectWidget.getItemFromData( prev.getName() ).getLevel()
			) {
				return prev;
			}
			if (
				next &&
				level === this.outlineSelectWidget.getItemFromData( next.getName() ).getLevel()
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
 * @return {OO.ui.OutlineSelectWidget|null} Outline widget, or null if booklet has no outline
 */
OO.ui.BookletLayout.prototype.getOutline = function () {
	return this.outlineSelectWidget;
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
			item = new OO.ui.OutlineOptionWidget( { $: this.$, data: name } );
			page.setOutlineItem( item );
			items.push( item );
		}
	}

	if ( this.outlined && items.length ) {
		this.outlineSelectWidget.addItems( items, index );
		this.selectFirstSelectablePage();
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
			items.push( this.outlineSelectWidget.getItemFromData( name ) );
			page.setOutlineItem( null );
		}
	}
	if ( this.outlined && items.length ) {
		this.outlineSelectWidget.removeItems( items );
		this.selectFirstSelectablePage();
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
		this.outlineSelectWidget.clearItems();
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
		$focused,
		page = this.pages[name];

	if ( name !== this.currentPageName ) {
		if ( this.outlined ) {
			selectedItem = this.outlineSelectWidget.getSelectedItem();
			if ( selectedItem && selectedItem.getData() !== name ) {
				this.outlineSelectWidget.selectItem( this.outlineSelectWidget.getItemFromData( name ) );
			}
		}
		if ( page ) {
			if ( this.currentPageName && this.pages[this.currentPageName] ) {
				this.pages[this.currentPageName].setActive( false );
				// Blur anything focused if the next page doesn't have anything focusable - this
				// is not needed if the next page has something focusable because once it is focused
				// this blur happens automatically
				if ( this.autoFocus && !page.$element.find( ':input' ).length ) {
					$focused = this.pages[this.currentPageName].$element.find( ':focus' );
					if ( $focused.length ) {
						$focused[0].blur();
					}
				}
			}
			this.currentPageName = name;
			this.stackLayout.setItem( page );
			page.setActive( true );
			this.emit( 'set', page );
		}
	}
};

/**
 * Select the first selectable page.
 *
 * @chainable
 */
OO.ui.BookletLayout.prototype.selectFirstSelectablePage = function () {
	if ( !this.outlineSelectWidget.getSelectedItem() ) {
		this.outlineSelectWidget.selectItem( this.outlineSelectWidget.getFirstSelectableItem() );
	}

	return this;
};

/**
 * Layout made of a field and optional label.
 *
 * Available label alignment modes include:
 *  - left: Label is before the field and aligned away from it, best for when the user will be
 *    scanning for a specific label in a form with many fields
 *  - right: Label is before the field and aligned toward it, best for forms the user is very
 *    familiar with and will tab through field checking quickly to verify which field they are in
 *  - top: Label is before the field and above it, best for when the user will need to fill out all
 *    fields from top to bottom in a form with few fields
 *  - inline: Label is after the field and aligned toward it, best for small boolean fields like
 *    checkboxes or radio buttons
 *
 * @class
 * @extends OO.ui.Layout
 * @mixins OO.ui.LabelElement
 *
 * @constructor
 * @param {OO.ui.Widget} fieldWidget Field widget
 * @param {Object} [config] Configuration options
 * @cfg {string} [align='left'] Alignment mode, either 'left', 'right', 'top' or 'inline'
 * @cfg {string} [help] Explanatory text shown as a '?' icon.
 */
OO.ui.FieldLayout = function OoUiFieldLayout( fieldWidget, config ) {
	// Configuration initialization
	config = $.extend( { align: 'left' }, config );

	// Properties (must be set before parent constructor, which calls #getTagName)
	this.fieldWidget = fieldWidget;

	// Parent constructor
	OO.ui.FieldLayout.super.call( this, config );

	// Mixin constructors
	OO.ui.LabelElement.call( this, config );

	// Properties
	this.$field = this.$( '<div>' );
	this.align = null;
	if ( config.help ) {
		this.popupButtonWidget = new OO.ui.PopupButtonWidget( {
			$: this.$,
			classes: [ 'oo-ui-fieldLayout-help' ],
			framed: false,
			icon: 'info'
		} );

		this.popupButtonWidget.getPopup().$body.append(
			this.$( '<div>' )
				.text( config.help )
				.addClass( 'oo-ui-fieldLayout-help-content' )
		);
		this.$help = this.popupButtonWidget.$element;
	} else {
		this.$help = this.$( [] );
	}

	// Events
	if ( this.fieldWidget instanceof OO.ui.InputWidget ) {
		this.$label.on( 'click', this.onLabelClick.bind( this ) );
	}
	this.fieldWidget.connect( this, { disable: 'onFieldDisable' } );

	// Initialization
	this.$element.addClass( 'oo-ui-fieldLayout' );
	this.$field
		.addClass( 'oo-ui-fieldLayout-field' )
		.toggleClass( 'oo-ui-fieldLayout-disable', this.fieldWidget.isDisabled() )
		.append( this.fieldWidget.$element );
	this.setAlignment( config.align );
};

/* Setup */

OO.inheritClass( OO.ui.FieldLayout, OO.ui.Layout );
OO.mixinClass( OO.ui.FieldLayout, OO.ui.LabelElement );

/* Methods */

/**
 * @inheritdoc
 */
OO.ui.FieldLayout.prototype.getTagName = function () {
	if ( this.fieldWidget instanceof OO.ui.InputWidget ) {
		return 'label';
	} else {
		return 'div';
	}
};

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
	this.fieldWidget.simulateLabelClick();
	return false;
};

/**
 * Get the field.
 *
 * @return {OO.ui.Widget} Field widget
 */
OO.ui.FieldLayout.prototype.getField = function () {
	return this.fieldWidget;
};

/**
 * Set the field alignment mode.
 *
 * @private
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
			this.$element.append( this.$field, this.$label, this.$help );
		} else {
			this.$element.append( this.$help, this.$label, this.$field );
		}
		// Set classes. The following classes can be used here:
		// * oo-ui-fieldLayout-align-left
		// * oo-ui-fieldLayout-align-right
		// * oo-ui-fieldLayout-align-top
		// * oo-ui-fieldLayout-align-inline
		if ( this.align ) {
			this.$element.removeClass( 'oo-ui-fieldLayout-align-' + this.align );
		}
		this.$element.addClass( 'oo-ui-fieldLayout-align-' + value );
		this.align = value;
	}

	return this;
};

/**
 * Layout made of a fieldset and optional legend.
 *
 * Just add OO.ui.FieldLayout items.
 *
 * @class
 * @extends OO.ui.Layout
 * @mixins OO.ui.IconElement
 * @mixins OO.ui.LabelElement
 * @mixins OO.ui.GroupElement
 *
 * @constructor
 * @param {Object} [config] Configuration options
 * @cfg {OO.ui.FieldLayout[]} [items] Items to add
 */
OO.ui.FieldsetLayout = function OoUiFieldsetLayout( config ) {
	// Configuration initialization
	config = config || {};

	// Parent constructor
	OO.ui.FieldsetLayout.super.call( this, config );

	// Mixin constructors
	OO.ui.IconElement.call( this, config );
	OO.ui.LabelElement.call( this, config );
	OO.ui.GroupElement.call( this, config );

	// Initialization
	this.$element
		.addClass( 'oo-ui-fieldsetLayout' )
		.prepend( this.$icon, this.$label, this.$group );
	if ( $.isArray( config.items ) ) {
		this.addItems( config.items );
	}
};

/* Setup */

OO.inheritClass( OO.ui.FieldsetLayout, OO.ui.Layout );
OO.mixinClass( OO.ui.FieldsetLayout, OO.ui.IconElement );
OO.mixinClass( OO.ui.FieldsetLayout, OO.ui.LabelElement );
OO.mixinClass( OO.ui.FieldsetLayout, OO.ui.GroupElement );

/**
 * Layout with an HTML form.
 *
 * @class
 * @extends OO.ui.Layout
 *
 * @constructor
 * @param {Object} [config] Configuration options
 * @cfg {string} [method] HTML form `method` attribute
 * @cfg {string} [action] HTML form `action` attribute
 * @cfg {string} [enctype] HTML form `enctype` attribute
 */
OO.ui.FormLayout = function OoUiFormLayout( config ) {
	// Configuration initialization
	config = config || {};

	// Parent constructor
	OO.ui.FormLayout.super.call( this, config );

	// Events
	this.$element.on( 'submit', this.onFormSubmit.bind( this ) );

	// Initialization
	this.$element
		.addClass( 'oo-ui-formLayout' )
		.attr( {
			method: config.method,
			action: config.action,
			enctype: config.enctype
		} );
};

/* Setup */

OO.inheritClass( OO.ui.FormLayout, OO.ui.Layout );

/* Events */

/**
 * @event submit
 */

/* Static Properties */

OO.ui.FormLayout.static.tagName = 'form';

/* Methods */

/**
 * Handle form submit events.
 *
 * @param {jQuery.Event} e Submit event
 * @fires submit
 */
OO.ui.FormLayout.prototype.onFormSubmit = function () {
	this.emit( 'submit' );
	return false;
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
 * @cfg {number[]} [heights] Heights of rows as ratios
 */
OO.ui.GridLayout = function OoUiGridLayout( panels, config ) {
	var i, len, widths;

	// Configuration initialization
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
		this.layout( config.widths || [ 1 ], config.heights || [ 1 ] );
	} else {
		// Arrange in columns by default
		widths = this.panels.map( function () { return 1; } );
		this.layout( widths, [ 1 ] );
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
	var x, y, panel, width, height, dimensions,
		i = 0,
		top = 0,
		left = 0,
		cols = this.widths.length,
		rows = this.heights.length;

	for ( y = 0; y < rows; y++ ) {
		height = this.heights[y];
		for ( x = 0; x < cols; x++ ) {
			width = this.widths[x];
			panel = this.panels[i];
			dimensions = {
				width: Math.round( width * 100 ) + '%',
				height: Math.round( height * 100 ) + '%',
				top: Math.round( top * 100 ) + '%'
			};
			// If RTL, reverse:
			if ( OO.ui.Element.getDir( this.$.context ) === 'rtl' ) {
				dimensions.right = Math.round( left * 100 ) + '%';
			} else {
				dimensions.left = Math.round( left * 100 ) + '%';
			}
			// HACK: Work around IE bug by setting visibility: hidden; if width or height is zero
			if ( width === 0 || height === 0 ) {
				dimensions.visibility = 'hidden';
			} else {
				dimensions.visibility = '';
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
 * @return {OO.ui.PanelLayout} The panel at the given position
 */
OO.ui.GridLayout.prototype.getPanel = function ( x, y ) {
	return this.panels[ ( x * this.widths.length ) + y ];
};

/**
 * Layout that expands to cover the entire area of its parent, with optional scrolling and padding.
 *
 * @class
 * @extends OO.ui.Layout
 *
 * @constructor
 * @param {Object} [config] Configuration options
 * @cfg {boolean} [scrollable=false] Allow vertical scrolling
 * @cfg {boolean} [padded=false] Pad the content from the edges
 * @cfg {boolean} [expanded=true] Expand size to fill the entire parent element
 */
OO.ui.PanelLayout = function OoUiPanelLayout( config ) {
	// Configuration initialization
	config = $.extend( {
		scrollable: false,
		padded: false,
		expanded: true
	}, config );

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
	if ( config.expanded ) {
		this.$element.addClass( 'oo-ui-panelLayout-expanded' );
	}
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
 */
OO.ui.PageLayout = function OoUiPageLayout( name, config ) {
	// Configuration initialization
	config = $.extend( { scrollable: true }, config );

	// Parent constructor
	OO.ui.PageLayout.super.call( this, config );

	// Properties
	this.name = name;
	this.outlineItem = null;
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
 * @return {OO.ui.OutlineOptionWidget|null} Outline item widget
 */
OO.ui.PageLayout.prototype.getOutlineItem = function () {
	return this.outlineItem;
};

/**
 * Set outline item.
 *
 * @localdoc Subclasses should override #setupOutlineItem instead of this method to adjust the
 *   outline item as desired; this method is called for setting (with an object) and unsetting
 *   (with null) and overriding methods would have to check the value of `outlineItem` to avoid
 *   operating on null instead of an OO.ui.OutlineOptionWidget object.
 *
 * @param {OO.ui.OutlineOptionWidget|null} outlineItem Outline item widget, null to clear
 * @chainable
 */
OO.ui.PageLayout.prototype.setOutlineItem = function ( outlineItem ) {
	this.outlineItem = outlineItem || null;
	if ( outlineItem ) {
		this.setupOutlineItem();
	}
	return this;
};

/**
 * Setup outline item.
 *
 * @localdoc Subclasses should override this method to adjust the outline item as desired.
 *
 * @param {OO.ui.OutlineOptionWidget} outlineItem Outline item widget to setup
 * @chainable
 */
OO.ui.PageLayout.prototype.setupOutlineItem = function () {
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
 * @cfg {OO.ui.Layout[]} [items] Layouts to add
 */
OO.ui.StackLayout = function OoUiStackLayout( config ) {
	// Configuration initialization
	config = $.extend( { scrollable: true }, config );

	// Parent constructor
	OO.ui.StackLayout.super.call( this, config );

	// Mixin constructors
	OO.ui.GroupElement.call( this, $.extend( {}, config, { $group: this.$element } ) );

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
 * @param {OO.ui.Layout|null} item Current item or null if there is no longer a layout shown
 */

/* Methods */

/**
 * Get the current item.
 *
 * @return {OO.ui.Layout|null}
 */
OO.ui.StackLayout.prototype.getCurrentItem = function () {
	return this.currentItem;
};

/**
 * Unset the current item.
 *
 * @private
 * @param {OO.ui.StackLayout} layout
 * @fires set
 */
OO.ui.StackLayout.prototype.unsetCurrentItem = function () {
	var prevItem = this.currentItem;
	if ( prevItem === null ) {
		return;
	}

	this.currentItem = null;
	this.emit( 'set', null );
};

/**
 * Add items.
 *
 * Adding an existing item (by value) will move it.
 *
 * @param {OO.ui.Layout[]} items Items to add
 * @param {number} [index] Index to insert items after
 * @chainable
 */
OO.ui.StackLayout.prototype.addItems = function ( items, index ) {
	// Mixin method
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
 * @param {OO.ui.Layout[]} items Items to remove
 * @chainable
 * @fires set
 */
OO.ui.StackLayout.prototype.removeItems = function ( items ) {
	// Mixin method
	OO.ui.GroupElement.prototype.removeItems.call( this, items );

	if ( $.inArray( this.currentItem, items ) !== -1 ) {
		if ( this.items.length ) {
			this.setItem( this.items[0] );
		} else {
			this.unsetCurrentItem();
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
 * @fires set
 */
OO.ui.StackLayout.prototype.clearItems = function () {
	this.unsetCurrentItem();
	OO.ui.GroupElement.prototype.clearItems.call( this );

	return this;
};

/**
 * Show item.
 *
 * Any currently shown item will be hidden.
 *
 * FIXME: If the passed item to show has not been added in the items list, then
 * this method drops it and unsets the current item.
 *
 * @param {OO.ui.Layout} item Item to show
 * @chainable
 * @fires set
 */
OO.ui.StackLayout.prototype.setItem = function ( item ) {
	var i, len;

	if ( item !== this.currentItem ) {
		if ( !this.continuous ) {
			for ( i = 0, len = this.items.length; i < len; i++ ) {
				this.items[i].$element.css( 'display', '' );
			}
		}
		if ( $.inArray( item, this.items ) !== -1 ) {
			if ( !this.continuous ) {
				item.$element.css( 'display', 'block' );
			}
			this.currentItem = item;
			this.emit( 'set', item );
		} else {
			this.unsetCurrentItem();
		}
	}

	return this;
};

/**
 * Horizontal bar layout of tools as icon buttons.
 *
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
 * @mixins OO.ui.IconElement
 * @mixins OO.ui.IndicatorElement
 * @mixins OO.ui.LabelElement
 * @mixins OO.ui.TitledElement
 * @mixins OO.ui.ClippableElement
 *
 * @constructor
 * @param {OO.ui.Toolbar} toolbar
 * @param {Object} [config] Configuration options
 * @cfg {string} [header] Text to display at the top of the pop-up
 */
OO.ui.PopupToolGroup = function OoUiPopupToolGroup( toolbar, config ) {
	// Configuration initialization
	config = config || {};

	// Parent constructor
	OO.ui.PopupToolGroup.super.call( this, toolbar, config );

	// Mixin constructors
	OO.ui.IconElement.call( this, config );
	OO.ui.IndicatorElement.call( this, config );
	OO.ui.LabelElement.call( this, config );
	OO.ui.TitledElement.call( this, config );
	OO.ui.ClippableElement.call( this, $.extend( {}, config, { $clippable: this.$group } ) );

	// Properties
	this.active = false;
	this.dragging = false;
	this.onBlurHandler = this.onBlur.bind( this );
	this.$handle = this.$( '<span>' );

	// Events
	this.$handle.on( {
		'mousedown touchstart': this.onHandlePointerDown.bind( this ),
		'mouseup touchend': this.onHandlePointerUp.bind( this )
	} );

	// Initialization
	this.$handle
		.addClass( 'oo-ui-popupToolGroup-handle' )
		.append( this.$icon, this.$label, this.$indicator );
	// If the pop-up should have a header, add it to the top of the toolGroup.
	// Note: If this feature is useful for other widgets, we could abstract it into an
	// OO.ui.HeaderedElement mixin constructor.
	if ( config.header !== undefined ) {
		this.$group
			.prepend( this.$( '<span>' )
				.addClass( 'oo-ui-popupToolGroup-header' )
				.text( config.header )
			);
	}
	this.$element
		.addClass( 'oo-ui-popupToolGroup' )
		.prepend( this.$handle );
};

/* Setup */

OO.inheritClass( OO.ui.PopupToolGroup, OO.ui.ToolGroup );
OO.mixinClass( OO.ui.PopupToolGroup, OO.ui.IconElement );
OO.mixinClass( OO.ui.PopupToolGroup, OO.ui.IndicatorElement );
OO.mixinClass( OO.ui.PopupToolGroup, OO.ui.LabelElement );
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
OO.ui.PopupToolGroup.prototype.onPointerUp = function ( e ) {
	// e.which is 0 for touch events, 1 for left mouse button
	// Only close toolgroup when a tool was actually selected
	// FIXME: this duplicates logic from the parent class
	if ( !this.isDisabled() && e.which <= 1 && this.pressed && this.pressed === this.getTargetTool( e ) ) {
		this.setActive( false );
	}
	return OO.ui.PopupToolGroup.super.prototype.onPointerUp.call( this, e );
};

/**
 * Handle mouse up events.
 *
 * @param {jQuery.Event} e Mouse up event
 */
OO.ui.PopupToolGroup.prototype.onHandlePointerUp = function () {
	return false;
};

/**
 * Handle mouse down events.
 *
 * @param {jQuery.Event} e Mouse down event
 */
OO.ui.PopupToolGroup.prototype.onHandlePointerDown = function ( e ) {
	// e.which is 0 for touch events, 1 for left mouse button
	if ( !this.isDisabled() && e.which <= 1 ) {
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
			this.getElementDocument().addEventListener( 'mouseup', this.onBlurHandler, true );

			// Try anchoring the popup to the left first
			this.$element.addClass( 'oo-ui-popupToolGroup-active oo-ui-popupToolGroup-left' );
			this.toggleClipping( true );
			if ( this.isClippedHorizontally() ) {
				// Anchoring to the left caused the popup to clip, so anchor it to the right instead
				this.toggleClipping( false );
				this.$element
					.removeClass( 'oo-ui-popupToolGroup-left' )
					.addClass( 'oo-ui-popupToolGroup-right' );
				this.toggleClipping( true );
			}
		} else {
			this.getElementDocument().removeEventListener( 'mouseup', this.onBlurHandler, true );
			this.$element.removeClass(
				'oo-ui-popupToolGroup-active oo-ui-popupToolGroup-left  oo-ui-popupToolGroup-right'
			);
			this.toggleClipping( false );
		}
	}
};

/**
 * Drop down list layout of tools as labeled icon buttons.
 *
 * This layout allows some tools to be collapsible, controlled by a "More" / "Fewer" option at the
 * bottom of the main list. These are not automatically positioned at the bottom of the list; you
 * may want to use the 'promote' and 'demote' configuration options to achieve this.
 *
 * @class
 * @extends OO.ui.PopupToolGroup
 *
 * @constructor
 * @param {OO.ui.Toolbar} toolbar
 * @param {Object} [config] Configuration options
 * @cfg {Array} [allowCollapse] List of tools that can be collapsed. Remaining tools will be always
 *  shown.
 * @cfg {Array} [forceExpand] List of tools that *may not* be collapsed. All remaining tools will be
 *  allowed to be collapsed.
 * @cfg {boolean} [expanded=false] Whether the collapsible tools are expanded by default
 */
OO.ui.ListToolGroup = function OoUiListToolGroup( toolbar, config ) {
	// Configuration initialization
	config = config || {};

	// Properties (must be set before parent constructor, which calls #populate)
	this.allowCollapse = config.allowCollapse;
	this.forceExpand = config.forceExpand;
	this.expanded = config.expanded !== undefined ? config.expanded : false;
	this.collapsibleTools = [];

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

/* Methods */

/**
 * @inheritdoc
 */
OO.ui.ListToolGroup.prototype.populate = function () {
	var i, len, allowCollapse = [];

	OO.ui.ListToolGroup.super.prototype.populate.call( this );

	// Update the list of collapsible tools
	if ( this.allowCollapse !== undefined ) {
		allowCollapse = this.allowCollapse;
	} else if ( this.forceExpand !== undefined ) {
		allowCollapse = OO.simpleArrayDifference( Object.keys( this.tools ), this.forceExpand );
	}

	this.collapsibleTools = [];
	for ( i = 0, len = allowCollapse.length; i < len; i++ ) {
		if ( this.tools[ allowCollapse[i] ] !== undefined ) {
			this.collapsibleTools.push( this.tools[ allowCollapse[i] ] );
		}
	}

	// Keep at the end, even when tools are added
	this.$group.append( this.getExpandCollapseTool().$element );

	this.getExpandCollapseTool().toggle( this.collapsibleTools.length !== 0 );

	// Calling jQuery's .hide() and then .show() on a detached element caches the default value of its
	// 'display' attribute and restores it, and the tool uses a <span> and can be hidden and re-shown.
	// Is this a jQuery bug? http://jsfiddle.net/gtj4hu3h/
	if ( this.getExpandCollapseTool().$element.css( 'display' ) === 'inline' ) {
		this.getExpandCollapseTool().$element.css( 'display', 'inline-block' );
	}

	this.updateCollapsibleState();
};

OO.ui.ListToolGroup.prototype.getExpandCollapseTool = function () {
	if ( this.expandCollapseTool === undefined ) {
		var ExpandCollapseTool = function () {
			ExpandCollapseTool.super.apply( this, arguments );
		};

		OO.inheritClass( ExpandCollapseTool, OO.ui.Tool );

		ExpandCollapseTool.prototype.onSelect = function () {
			this.toolGroup.expanded = !this.toolGroup.expanded;
			this.toolGroup.updateCollapsibleState();
			this.setActive( false );
		};
		ExpandCollapseTool.prototype.onUpdateState = function () {
			// Do nothing. Tool interface requires an implementation of this function.
		};

		ExpandCollapseTool.static.name = 'more-fewer';

		this.expandCollapseTool = new ExpandCollapseTool( this );
	}
	return this.expandCollapseTool;
};

/**
 * @inheritdoc
 */
OO.ui.ListToolGroup.prototype.onPointerUp = function ( e ) {
	var ret = OO.ui.ListToolGroup.super.prototype.onPointerUp.call( this, e );

	// Do not close the popup when the user wants to show more/fewer tools
	if ( this.$( e.target ).closest( '.oo-ui-tool-name-more-fewer' ).length ) {
		// Prevent the popup list from being hidden
		this.setActive( true );
	}

	return ret;
};

OO.ui.ListToolGroup.prototype.updateCollapsibleState = function () {
	var i, len;

	this.getExpandCollapseTool()
		.setIcon( this.expanded ? 'collapse' : 'expand' )
		.setTitle( OO.ui.msg( this.expanded ? 'ooui-toolgroup-collapse' : 'ooui-toolgroup-expand' ) );

	for ( i = 0, len = this.collapsibleTools.length; i < len; i++ ) {
		this.collapsibleTools[i].toggle( this.expanded );
	}
};

/**
 * Drop down menu layout of tools as selectable menu items.
 *
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
	this.toolbar.connect( this, { updateState: 'onUpdateState' } );

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
 * @mixins OO.ui.PopupElement
 *
 * @constructor
 * @param {OO.ui.Toolbar} toolbar
 * @param {Object} [config] Configuration options
 */
OO.ui.PopupTool = function OoUiPopupTool( toolbar, config ) {
	// Parent constructor
	OO.ui.PopupTool.super.call( this, toolbar, config );

	// Mixin constructors
	OO.ui.PopupElement.call( this, config );

	// Initialization
	this.$element
		.addClass( 'oo-ui-popupTool' )
		.append( this.popup.$element );
};

/* Setup */

OO.inheritClass( OO.ui.PopupTool, OO.ui.Tool );
OO.mixinClass( OO.ui.PopupTool, OO.ui.PopupElement );

/* Methods */

/**
 * Handle the tool being selected.
 *
 * @inheritdoc
 */
OO.ui.PopupTool.prototype.onSelect = function () {
	if ( !this.isDisabled() ) {
		this.popup.toggle();
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
 * Mixin for OO.ui.Widget subclasses to provide OO.ui.GroupElement.
 *
 * Use together with OO.ui.ItemWidget to make disabled state inheritable.
 *
 * @abstract
 * @class
 * @extends OO.ui.GroupElement
 *
 * @constructor
 * @param {Object} [config] Configuration options
 */
OO.ui.GroupWidget = function OoUiGroupWidget( config ) {
	// Parent constructor
	OO.ui.GroupWidget.super.call( this, config );
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
	// Note: Calling #setDisabled this way assumes this is mixed into an OO.ui.Widget
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
 * Mixin for widgets used as items in widgets that inherit OO.ui.GroupWidget.
 *
 * Item widgets have a reference to a OO.ui.GroupWidget while they are attached to the group. This
 * allows bidirectional communication.
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
	// Note: Calling #setElementGroup this way assumes this is mixed into an OO.ui.Element
	OO.ui.Element.prototype.setElementGroup.call( this, group );

	// Initialize item disabled states
	this.updateDisabled();

	return this;
};

/**
 * Mixin that adds a menu showing suggested values for a text input.
 *
 * Subclasses must handle `select` and `choose` events on #lookupMenu to make use of selections.
 *
 * Subclasses that set the value of #lookupInput from their `choose` or `select` handler should
 * be aware that this will cause new suggestions to be looked up for the new value. If this is
 * not desired, disable lookups with #setLookupsDisabled, then set the value, then re-enable lookups.
 *
 * @class
 * @abstract
 *
 * @constructor
 * @param {OO.ui.TextInputWidget} input Input widget
 * @param {Object} [config] Configuration options
 * @cfg {jQuery} [$overlay] Overlay for dropdown; defaults to relative positioning
 * @cfg {jQuery} [$container=input.$element] Element to render menu under
 */
OO.ui.LookupInputWidget = function OoUiLookupInputWidget( input, config ) {
	// Configuration initialization
	config = config || {};

	// Properties
	this.lookupInput = input;
	this.$overlay = config.$overlay || this.$element;
	this.lookupMenu = new OO.ui.TextInputMenuSelectWidget( this, {
		$: OO.ui.Element.getJQuery( this.$overlay ),
		input: this.lookupInput,
		$container: config.$container
	} );
	this.lookupCache = {};
	this.lookupQuery = null;
	this.lookupRequest = null;
	this.lookupsDisabled = false;
	this.lookupInputFocused = false;

	// Events
	this.lookupInput.$input.on( {
		focus: this.onLookupInputFocus.bind( this ),
		blur: this.onLookupInputBlur.bind( this ),
		mousedown: this.onLookupInputMouseDown.bind( this )
	} );
	this.lookupInput.connect( this, { change: 'onLookupInputChange' } );
	this.lookupMenu.connect( this, { toggle: 'onLookupMenuToggle' } );

	// Initialization
	this.$element.addClass( 'oo-ui-lookupWidget' );
	this.lookupMenu.$element.addClass( 'oo-ui-lookupWidget-menu' );
	this.$overlay.append( this.lookupMenu.$element );
};

/* Methods */

/**
 * Handle input focus event.
 *
 * @param {jQuery.Event} e Input focus event
 */
OO.ui.LookupInputWidget.prototype.onLookupInputFocus = function () {
	this.lookupInputFocused = true;
	this.populateLookupMenu();
};

/**
 * Handle input blur event.
 *
 * @param {jQuery.Event} e Input blur event
 */
OO.ui.LookupInputWidget.prototype.onLookupInputBlur = function () {
	this.closeLookupMenu();
	this.lookupInputFocused = false;
};

/**
 * Handle input mouse down event.
 *
 * @param {jQuery.Event} e Input mouse down event
 */
OO.ui.LookupInputWidget.prototype.onLookupInputMouseDown = function () {
	// Only open the menu if the input was already focused.
	// This way we allow the user to open the menu again after closing it with Esc
	// by clicking in the input. Opening (and populating) the menu when initially
	// clicking into the input is handled by the focus handler.
	if ( this.lookupInputFocused && !this.lookupMenu.isVisible() ) {
		this.populateLookupMenu();
	}
};

/**
 * Handle input change event.
 *
 * @param {string} value New input value
 */
OO.ui.LookupInputWidget.prototype.onLookupInputChange = function () {
	if ( this.lookupInputFocused ) {
		this.populateLookupMenu();
	}
};

/**
 * Handle the lookup menu being shown/hidden.
 * @param {boolean} visible Whether the lookup menu is now visible.
 */
OO.ui.LookupInputWidget.prototype.onLookupMenuToggle = function ( visible ) {
	if ( !visible ) {
		// When the menu is hidden, abort any active request and clear the menu.
		// This has to be done here in addition to closeLookupMenu(), because
		// MenuSelectWidget will close itself when the user presses Esc.
		this.abortLookupRequest();
		this.lookupMenu.clearItems();
	}
};

/**
 * Get lookup menu.
 *
 * @return {OO.ui.TextInputMenuSelectWidget}
 */
OO.ui.LookupInputWidget.prototype.getLookupMenu = function () {
	return this.lookupMenu;
};

/**
 * Disable or re-enable lookups.
 *
 * When lookups are disabled, calls to #populateLookupMenu will be ignored.
 *
 * @param {boolean} disabled Disable lookups
 */
OO.ui.LookupInputWidget.prototype.setLookupsDisabled = function ( disabled ) {
	this.lookupsDisabled = !!disabled;
};

/**
 * Open the menu. If there are no entries in the menu, this does nothing.
 *
 * @chainable
 */
OO.ui.LookupInputWidget.prototype.openLookupMenu = function () {
	if ( !this.lookupMenu.isEmpty() ) {
		this.lookupMenu.toggle( true );
	}
	return this;
};

/**
 * Close the menu, empty it, and abort any pending request.
 *
 * @chainable
 */
OO.ui.LookupInputWidget.prototype.closeLookupMenu = function () {
	this.lookupMenu.toggle( false );
	this.abortLookupRequest();
	this.lookupMenu.clearItems();
	return this;
};

/**
 * Request menu items based on the input's current value, and when they arrive,
 * populate the menu with these items and show the menu.
 *
 * If lookups have been disabled with #setLookupsDisabled, this function does nothing.
 *
 * @chainable
 */
OO.ui.LookupInputWidget.prototype.populateLookupMenu = function () {
	var widget = this,
		value = this.lookupInput.getValue();

	if ( this.lookupsDisabled ) {
		return;
	}

	// If the input is empty, clear the menu
	if ( value === '' ) {
		this.closeLookupMenu();
	// Skip population if there is already a request pending for the current value
	} else if ( value !== this.lookupQuery ) {
		this.getLookupMenuItems()
			.done( function ( items ) {
				widget.lookupMenu.clearItems();
				if ( items.length ) {
					widget.lookupMenu
						.addItems( items )
						.toggle( true );
					widget.initializeLookupMenuSelection();
				} else {
					widget.lookupMenu.toggle( false );
				}
			} )
			.fail( function () {
				widget.lookupMenu.clearItems();
			} );
	}

	return this;
};

/**
 * Select and highlight the first selectable item in the menu.
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
 * of the done event. If the request was aborted to make way for a subsequent request,
 * this promise will not be rejected: it will remain pending forever.
 */
OO.ui.LookupInputWidget.prototype.getLookupMenuItems = function () {
	var widget = this,
		value = this.lookupInput.getValue(),
		deferred = $.Deferred(),
		ourRequest;

	this.abortLookupRequest();
	if ( Object.prototype.hasOwnProperty.call( this.lookupCache, value ) ) {
		deferred.resolve( this.getLookupMenuItemsFromData( this.lookupCache[value] ) );
	} else {
		this.lookupInput.pushPending();
		this.lookupQuery = value;
		ourRequest = this.lookupRequest = this.getLookupRequest();
		ourRequest
			.always( function () {
				// We need to pop pending even if this is an old request, otherwise
				// the widget will remain pending forever.
				// TODO: this assumes that an aborted request will fail or succeed soon after
				// being aborted, or at least eventually. It would be nice if we could popPending()
				// at abort time, but only if we knew that we hadn't already called popPending()
				// for that request.
				widget.lookupInput.popPending();
			} )
			.done( function ( data ) {
				// If this is an old request (and aborting it somehow caused it to still succeed),
				// ignore its success completely
				if ( ourRequest === widget.lookupRequest ) {
					widget.lookupQuery = null;
					widget.lookupRequest = null;
					widget.lookupCache[value] = widget.getLookupCacheItemFromData( data );
					deferred.resolve( widget.getLookupMenuItemsFromData( widget.lookupCache[value] ) );
				}
			} )
			.fail( function () {
				// If this is an old request (or a request failing because it's being aborted),
				// ignore its failure completely
				if ( ourRequest === widget.lookupRequest ) {
					widget.lookupQuery = null;
					widget.lookupRequest = null;
					deferred.reject();
				}
			} );
	}
	return deferred.promise();
};

/**
 * Abort the currently pending lookup request, if any.
 */
OO.ui.LookupInputWidget.prototype.abortLookupRequest = function () {
	var oldRequest = this.lookupRequest;
	if ( oldRequest ) {
		// First unset this.lookupRequest to the fail handler will notice
		// that the request is no longer current
		this.lookupRequest = null;
		this.lookupQuery = null;
		oldRequest.abort();
	}
};

/**
 * Get a new request object of the current lookup query value.
 *
 * @abstract
 * @return {jQuery.Promise} jQuery AJAX object, or promise object with an .abort() method
 */
OO.ui.LookupInputWidget.prototype.getLookupRequest = function () {
	// Stub, implemented in subclass
	return null;
};

/**
 * Get a list of menu item widgets from the data stored by the lookup request's done handler.
 *
 * @abstract
 * @param {Mixed} data Cached result data, usually an array
 * @return {OO.ui.MenuOptionWidget[]} Menu items
 */
OO.ui.LookupInputWidget.prototype.getLookupMenuItemsFromData = function () {
	// Stub, implemented in subclass
	return [];
};

/**
 * Get lookup cache item from server response data.
 *
 * @abstract
 * @param {Mixed} data Response from server
 * @return {Mixed} Cached result data
 */
OO.ui.LookupInputWidget.prototype.getLookupCacheItemFromData = function () {
	// Stub, implemented in subclass
	return [];
};

/**
 * Set of controls for an OO.ui.OutlineSelectWidget.
 *
 * Controls include moving items up and down, removing items, and adding different kinds of items.
 *
 * @class
 * @extends OO.ui.Widget
 * @mixins OO.ui.GroupElement
 * @mixins OO.ui.IconElement
 *
 * @constructor
 * @param {OO.ui.OutlineSelectWidget} outline Outline to control
 * @param {Object} [config] Configuration options
 */
OO.ui.OutlineControlsWidget = function OoUiOutlineControlsWidget( outline, config ) {
	// Configuration initialization
	config = $.extend( { icon: 'add' }, config );

	// Parent constructor
	OO.ui.OutlineControlsWidget.super.call( this, config );

	// Mixin constructors
	OO.ui.GroupElement.call( this, config );
	OO.ui.IconElement.call( this, config );

	// Properties
	this.outline = outline;
	this.$movers = this.$( '<div>' );
	this.upButton = new OO.ui.ButtonWidget( {
		$: this.$,
		framed: false,
		icon: 'collapse',
		title: OO.ui.msg( 'ooui-outline-control-move-up' )
	} );
	this.downButton = new OO.ui.ButtonWidget( {
		$: this.$,
		framed: false,
		icon: 'expand',
		title: OO.ui.msg( 'ooui-outline-control-move-down' )
	} );
	this.removeButton = new OO.ui.ButtonWidget( {
		$: this.$,
		framed: false,
		icon: 'remove',
		title: OO.ui.msg( 'ooui-outline-control-remove' )
	} );

	// Events
	outline.connect( this, {
		select: 'onOutlineChange',
		add: 'onOutlineChange',
		remove: 'onOutlineChange'
	} );
	this.upButton.connect( this, { click: [ 'emit', 'move', -1 ] } );
	this.downButton.connect( this, { click: [ 'emit', 'move', 1 ] } );
	this.removeButton.connect( this, { click: [ 'emit', 'remove' ] } );

	// Initialization
	this.$element.addClass( 'oo-ui-outlineControlsWidget' );
	this.$group.addClass( 'oo-ui-outlineControlsWidget-items' );
	this.$movers
		.addClass( 'oo-ui-outlineControlsWidget-movers' )
		.append( this.removeButton.$element, this.upButton.$element, this.downButton.$element );
	this.$element.append( this.$icon, this.$group, this.$movers );
};

/* Setup */

OO.inheritClass( OO.ui.OutlineControlsWidget, OO.ui.Widget );
OO.mixinClass( OO.ui.OutlineControlsWidget, OO.ui.GroupElement );
OO.mixinClass( OO.ui.OutlineControlsWidget, OO.ui.IconElement );

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
 * Mixin for widgets with a boolean on/off state.
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
 * Group widget for multiple related buttons.
 *
 * Use together with OO.ui.ButtonWidget.
 *
 * @class
 * @extends OO.ui.Widget
 * @mixins OO.ui.GroupElement
 *
 * @constructor
 * @param {Object} [config] Configuration options
 * @cfg {OO.ui.ButtonWidget[]} [items] Buttons to add
 */
OO.ui.ButtonGroupWidget = function OoUiButtonGroupWidget( config ) {
	// Configuration initialization
	config = config || {};

	// Parent constructor
	OO.ui.ButtonGroupWidget.super.call( this, config );

	// Mixin constructors
	OO.ui.GroupElement.call( this, $.extend( {}, config, { $group: this.$element } ) );

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
 * Generic widget for buttons.
 *
 * @class
 * @extends OO.ui.Widget
 * @mixins OO.ui.ButtonElement
 * @mixins OO.ui.IconElement
 * @mixins OO.ui.IndicatorElement
 * @mixins OO.ui.LabelElement
 * @mixins OO.ui.TitledElement
 * @mixins OO.ui.FlaggedElement
 *
 * @constructor
 * @param {Object} [config] Configuration options
 * @cfg {string} [href] Hyperlink to visit when clicked
 * @cfg {string} [target] Target to open hyperlink in
 */
OO.ui.ButtonWidget = function OoUiButtonWidget( config ) {
	// Configuration initialization
	config = config || {};

	// Parent constructor
	OO.ui.ButtonWidget.super.call( this, config );

	// Mixin constructors
	OO.ui.ButtonElement.call( this, config );
	OO.ui.IconElement.call( this, config );
	OO.ui.IndicatorElement.call( this, config );
	OO.ui.LabelElement.call( this, config );
	OO.ui.TitledElement.call( this, $.extend( {}, config, { $titled: this.$button } ) );
	OO.ui.FlaggedElement.call( this, config );

	// Properties
	this.href = null;
	this.target = null;
	this.isHyperlink = false;

	// Events
	this.$button.on( {
		click: this.onClick.bind( this ),
		keypress: this.onKeyPress.bind( this )
	} );

	// Initialization
	this.$button.append( this.$icon, this.$label, this.$indicator );
	this.$element
		.addClass( 'oo-ui-buttonWidget' )
		.append( this.$button );
	this.setHref( config.href );
	this.setTarget( config.target );
};

/* Setup */

OO.inheritClass( OO.ui.ButtonWidget, OO.ui.Widget );
OO.mixinClass( OO.ui.ButtonWidget, OO.ui.ButtonElement );
OO.mixinClass( OO.ui.ButtonWidget, OO.ui.IconElement );
OO.mixinClass( OO.ui.ButtonWidget, OO.ui.IndicatorElement );
OO.mixinClass( OO.ui.ButtonWidget, OO.ui.LabelElement );
OO.mixinClass( OO.ui.ButtonWidget, OO.ui.TitledElement );
OO.mixinClass( OO.ui.ButtonWidget, OO.ui.FlaggedElement );

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
	if ( !this.isDisabled() ) {
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
	if ( !this.isDisabled() && ( e.which === OO.ui.Keys.SPACE || e.which === OO.ui.Keys.ENTER ) ) {
		this.emit( 'click' );
		if ( this.isHyperlink ) {
			return true;
		}
	}
	return false;
};

/**
 * Get hyperlink location.
 *
 * @return {string} Hyperlink location
 */
OO.ui.ButtonWidget.prototype.getHref = function () {
	return this.href;
};

/**
 * Get hyperlink target.
 *
 * @return {string} Hyperlink target
 */
OO.ui.ButtonWidget.prototype.getTarget = function () {
	return this.target;
};

/**
 * Set hyperlink location.
 *
 * @param {string|null} href Hyperlink location, null to remove
 */
OO.ui.ButtonWidget.prototype.setHref = function ( href ) {
	href = typeof href === 'string' ? href : null;

	if ( href !== this.href ) {
		this.href = href;
		if ( href !== null ) {
			this.$button.attr( 'href', href );
			this.isHyperlink = true;
		} else {
			this.$button.removeAttr( 'href' );
			this.isHyperlink = false;
		}
	}

	return this;
};

/**
 * Set hyperlink target.
 *
 * @param {string|null} target Hyperlink target, null to remove
 */
OO.ui.ButtonWidget.prototype.setTarget = function ( target ) {
	target = typeof target === 'string' ? target : null;

	if ( target !== this.target ) {
		this.target = target;
		if ( target !== null ) {
			this.$button.attr( 'target', target );
		} else {
			this.$button.removeAttr( 'target' );
		}
	}

	return this;
};

/**
 * Button widget that executes an action and is managed by an OO.ui.ActionSet.
 *
 * @class
 * @extends OO.ui.ButtonWidget
 * @mixins OO.ui.PendingElement
 *
 * @constructor
 * @param {Object} [config] Configuration options
 * @cfg {string} [action] Symbolic action name
 * @cfg {string[]} [modes] Symbolic mode names
 * @cfg {boolean} [framed=false] Render button with a frame
 */
OO.ui.ActionWidget = function OoUiActionWidget( config ) {
	// Configuration initialization
	config = $.extend( { framed: false }, config );

	// Parent constructor
	OO.ui.ActionWidget.super.call( this, config );

	// Mixin constructors
	OO.ui.PendingElement.call( this, config );

	// Properties
	this.action = config.action || '';
	this.modes = config.modes || [];
	this.width = 0;
	this.height = 0;

	// Initialization
	this.$element.addClass( 'oo-ui-actionWidget' );
};

/* Setup */

OO.inheritClass( OO.ui.ActionWidget, OO.ui.ButtonWidget );
OO.mixinClass( OO.ui.ActionWidget, OO.ui.PendingElement );

/* Events */

/**
 * @event resize
 */

/* Methods */

/**
 * Check if action is available in a certain mode.
 *
 * @param {string} mode Name of mode
 * @return {boolean} Has mode
 */
OO.ui.ActionWidget.prototype.hasMode = function ( mode ) {
	return this.modes.indexOf( mode ) !== -1;
};

/**
 * Get symbolic action name.
 *
 * @return {string}
 */
OO.ui.ActionWidget.prototype.getAction = function () {
	return this.action;
};

/**
 * Get symbolic action name.
 *
 * @return {string}
 */
OO.ui.ActionWidget.prototype.getModes = function () {
	return this.modes.slice();
};

/**
 * Emit a resize event if the size has changed.
 *
 * @chainable
 */
OO.ui.ActionWidget.prototype.propagateResize = function () {
	var width, height;

	if ( this.isElementAttached() ) {
		width = this.$element.width();
		height = this.$element.height();

		if ( width !== this.width || height !== this.height ) {
			this.width = width;
			this.height = height;
			this.emit( 'resize' );
		}
	}

	return this;
};

/**
 * @inheritdoc
 */
OO.ui.ActionWidget.prototype.setIcon = function () {
	// Mixin method
	OO.ui.IconElement.prototype.setIcon.apply( this, arguments );
	this.propagateResize();

	return this;
};

/**
 * @inheritdoc
 */
OO.ui.ActionWidget.prototype.setLabel = function () {
	// Mixin method
	OO.ui.LabelElement.prototype.setLabel.apply( this, arguments );
	this.propagateResize();

	return this;
};

/**
 * @inheritdoc
 */
OO.ui.ActionWidget.prototype.setFlags = function () {
	// Mixin method
	OO.ui.FlaggedElement.prototype.setFlags.apply( this, arguments );
	this.propagateResize();

	return this;
};

/**
 * @inheritdoc
 */
OO.ui.ActionWidget.prototype.clearFlags = function () {
	// Mixin method
	OO.ui.FlaggedElement.prototype.clearFlags.apply( this, arguments );
	this.propagateResize();

	return this;
};

/**
 * Toggle visibility of button.
 *
 * @param {boolean} [show] Show button, omit to toggle visibility
 * @chainable
 */
OO.ui.ActionWidget.prototype.toggle = function () {
	// Parent method
	OO.ui.ActionWidget.super.prototype.toggle.apply( this, arguments );
	this.propagateResize();

	return this;
};

/**
 * Button that shows and hides a popup.
 *
 * @class
 * @extends OO.ui.ButtonWidget
 * @mixins OO.ui.PopupElement
 *
 * @constructor
 * @param {Object} [config] Configuration options
 */
OO.ui.PopupButtonWidget = function OoUiPopupButtonWidget( config ) {
	// Parent constructor
	OO.ui.PopupButtonWidget.super.call( this, config );

	// Mixin constructors
	OO.ui.PopupElement.call( this, config );

	// Initialization
	this.$element
		.addClass( 'oo-ui-popupButtonWidget' )
		.append( this.popup.$element );
};

/* Setup */

OO.inheritClass( OO.ui.PopupButtonWidget, OO.ui.ButtonWidget );
OO.mixinClass( OO.ui.PopupButtonWidget, OO.ui.PopupElement );

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

	if ( !this.isDisabled() ) {
		this.popup.toggle();
		// Parent method
		OO.ui.PopupButtonWidget.super.prototype.onClick.call( this );
	}
	return false;
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
	if ( !this.isDisabled() ) {
		this.setValue( !this.value );
	}

	// Parent method
	return OO.ui.ToggleButtonWidget.super.prototype.onClick.call( this );
};

/**
 * @inheritdoc
 */
OO.ui.ToggleButtonWidget.prototype.setValue = function ( value ) {
	value = !!value;
	if ( value !== this.value ) {
		this.setActive( value );
	}

	// Parent method (from mixin)
	OO.ui.ToggleWidget.prototype.setValue.call( this, value );

	return this;
};

/**
 * Dropdown menu of options.
 *
 * Dropdown menus provide a control for accessing a menu and compose a menu within the widget, which
 * can be accessed using the #getMenu method.
 *
 * Use with OO.ui.MenuOptionWidget.
 *
 * @class
 * @extends OO.ui.Widget
 * @mixins OO.ui.IconElement
 * @mixins OO.ui.IndicatorElement
 * @mixins OO.ui.LabelElement
 * @mixins OO.ui.TitledElement
 *
 * @constructor
 * @param {Object} [config] Configuration options
 * @cfg {Object} [menu] Configuration options to pass to menu widget
 */
OO.ui.DropdownWidget = function OoUiDropdownWidget( config ) {
	// Configuration initialization
	config = $.extend( { indicator: 'down' }, config );

	// Parent constructor
	OO.ui.DropdownWidget.super.call( this, config );

	// Mixin constructors
	OO.ui.IconElement.call( this, config );
	OO.ui.IndicatorElement.call( this, config );
	OO.ui.LabelElement.call( this, config );
	OO.ui.TitledElement.call( this, $.extend( {}, config, { $titled: this.$label } ) );

	// Properties
	this.menu = new OO.ui.MenuSelectWidget( $.extend( { $: this.$, widget: this }, config.menu ) );
	this.$handle = this.$( '<span>' );

	// Events
	this.$element.on( { click: this.onClick.bind( this ) } );
	this.menu.connect( this, { select: 'onMenuSelect' } );

	// Initialization
	this.$handle
		.addClass( 'oo-ui-dropdownWidget-handle' )
		.append( this.$icon, this.$label, this.$indicator );
	this.$element
		.addClass( 'oo-ui-dropdownWidget' )
		.append( this.$handle, this.menu.$element );
};

/* Setup */

OO.inheritClass( OO.ui.DropdownWidget, OO.ui.Widget );
OO.mixinClass( OO.ui.DropdownWidget, OO.ui.IconElement );
OO.mixinClass( OO.ui.DropdownWidget, OO.ui.IndicatorElement );
OO.mixinClass( OO.ui.DropdownWidget, OO.ui.LabelElement );
OO.mixinClass( OO.ui.DropdownWidget, OO.ui.TitledElement );

/* Methods */

/**
 * Get the menu.
 *
 * @return {OO.ui.MenuSelectWidget} Menu of widget
 */
OO.ui.DropdownWidget.prototype.getMenu = function () {
	return this.menu;
};

/**
 * Handles menu select events.
 *
 * @param {OO.ui.MenuOptionWidget} item Selected menu item
 */
OO.ui.DropdownWidget.prototype.onMenuSelect = function ( item ) {
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
OO.ui.DropdownWidget.prototype.onClick = function ( e ) {
	// Skip clicks within the menu
	if ( $.contains( this.menu.$element[0], e.target ) ) {
		return;
	}

	if ( !this.isDisabled() ) {
		if ( this.menu.isVisible() ) {
			this.menu.toggle( false );
		} else {
			this.menu.toggle( true );
		}
	}
	return false;
};

/**
 * Icon widget.
 *
 * See OO.ui.IconElement for more information.
 *
 * @class
 * @extends OO.ui.Widget
 * @mixins OO.ui.IconElement
 * @mixins OO.ui.TitledElement
 *
 * @constructor
 * @param {Object} [config] Configuration options
 */
OO.ui.IconWidget = function OoUiIconWidget( config ) {
	// Configuration initialization
	config = config || {};

	// Parent constructor
	OO.ui.IconWidget.super.call( this, config );

	// Mixin constructors
	OO.ui.IconElement.call( this, $.extend( {}, config, { $icon: this.$element } ) );
	OO.ui.TitledElement.call( this, $.extend( {}, config, { $titled: this.$element } ) );

	// Initialization
	this.$element.addClass( 'oo-ui-iconWidget' );
};

/* Setup */

OO.inheritClass( OO.ui.IconWidget, OO.ui.Widget );
OO.mixinClass( OO.ui.IconWidget, OO.ui.IconElement );
OO.mixinClass( OO.ui.IconWidget, OO.ui.TitledElement );

/* Static Properties */

OO.ui.IconWidget.static.tagName = 'span';

/**
 * Indicator widget.
 *
 * See OO.ui.IndicatorElement for more information.
 *
 * @class
 * @extends OO.ui.Widget
 * @mixins OO.ui.IndicatorElement
 * @mixins OO.ui.TitledElement
 *
 * @constructor
 * @param {Object} [config] Configuration options
 */
OO.ui.IndicatorWidget = function OoUiIndicatorWidget( config ) {
	// Configuration initialization
	config = config || {};

	// Parent constructor
	OO.ui.IndicatorWidget.super.call( this, config );

	// Mixin constructors
	OO.ui.IndicatorElement.call( this, $.extend( {}, config, { $indicator: this.$element } ) );
	OO.ui.TitledElement.call( this, $.extend( {}, config, { $titled: this.$element } ) );

	// Initialization
	this.$element.addClass( 'oo-ui-indicatorWidget' );
};

/* Setup */

OO.inheritClass( OO.ui.IndicatorWidget, OO.ui.Widget );
OO.mixinClass( OO.ui.IndicatorWidget, OO.ui.IndicatorElement );
OO.mixinClass( OO.ui.IndicatorWidget, OO.ui.TitledElement );

/* Static Properties */

OO.ui.IndicatorWidget.static.tagName = 'span';

/**
 * Base class for input widgets.
 *
 * @abstract
 * @class
 * @extends OO.ui.Widget
 * @mixins OO.ui.FlaggedElement
 *
 * @constructor
 * @param {Object} [config] Configuration options
 * @cfg {string} [name=''] HTML input name
 * @cfg {string} [value=''] Input value
 * @cfg {Function} [inputFilter] Filter function to apply to the input. Takes a string argument and returns a string.
 */
OO.ui.InputWidget = function OoUiInputWidget( config ) {
	// Configuration initialization
	config = config || {};

	// Parent constructor
	OO.ui.InputWidget.super.call( this, config );

	// Mixin constructors
	OO.ui.FlaggedElement.call( this, config );

	// Properties
	this.$input = this.getInputElement( config );
	this.value = '';
	this.inputFilter = config.inputFilter;

	// Events
	this.$input.on( 'keydown mouseup cut paste change input select', this.onEdit.bind( this ) );

	// Initialization
	this.$input
		.attr( 'name', config.name )
		.prop( 'disabled', this.isDisabled() );
	this.$element.addClass( 'oo-ui-inputWidget' ).append( this.$input, $( '<span>' ) );
	this.setValue( config.value );
};

/* Setup */

OO.inheritClass( OO.ui.InputWidget, OO.ui.Widget );
OO.mixinClass( OO.ui.InputWidget, OO.ui.FlaggedElement );

/* Events */

/**
 * @event change
 * @param {string} value
 */

/* Methods */

/**
 * Get input element.
 *
 * @private
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
	var widget = this;
	if ( !this.isDisabled() ) {
		// Allow the stack to clear so the value will be updated
		setTimeout( function () {
			widget.setValue( widget.$input.val() );
		} );
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
	value = this.cleanUpValue( value );
	// Update the DOM if it has changed. Note that with cleanUpValue, it
	// is possible for the DOM value to change without this.value changing.
	if ( this.$input.val() !== value ) {
		this.$input.val( value );
	}
	if ( this.value !== value ) {
		this.value = value;
		this.emit( 'change', this.value );
	}
	return this;
};

/**
 * Clean up incoming value.
 *
 * Ensures value is a string, and converts undefined and null to empty string.
 *
 * @private
 * @param {string} value Original value
 * @return {string} Cleaned up value
 */
OO.ui.InputWidget.prototype.cleanUpValue = function ( value ) {
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
			this.$input[0].focus();
		}
	}
};

/**
 * @inheritdoc
 */
OO.ui.InputWidget.prototype.setDisabled = function ( state ) {
	OO.ui.InputWidget.super.prototype.setDisabled.call( this, state );
	if ( this.$input ) {
		this.$input.prop( 'disabled', this.isDisabled() );
	}
	return this;
};

/**
 * Focus the input.
 *
 * @chainable
 */
OO.ui.InputWidget.prototype.focus = function () {
	this.$input[0].focus();
	return this;
};

/**
 * Blur the input.
 *
 * @chainable
 */
OO.ui.InputWidget.prototype.blur = function () {
	this.$input[0].blur();
	return this;
};

/**
 * A button that is an input widget. Intended to be used within a OO.ui.FormLayout.
 *
 * @class
 * @extends OO.ui.InputWidget
 * @mixins OO.ui.ButtonElement
 * @mixins OO.ui.IconElement
 * @mixins OO.ui.IndicatorElement
 * @mixins OO.ui.LabelElement
 * @mixins OO.ui.TitledElement
 * @mixins OO.ui.FlaggedElement
 *
 * @constructor
 * @param {Object} [config] Configuration options
 * @cfg {string} [type='button'] HTML tag `type` attribute, may be 'button', 'submit' or 'reset'
 * @cfg {boolean} [useInputTag=false] Whether to use `<input/>` rather than `<button/>`. Only useful
 *  if you need IE 6 support in a form with multiple buttons. If you use this option, icons and
 *  indicators will not be displayed, it won't be possible to have a non-plaintext label, and it
 *  won't be possible to set a value (which will internally become identical to the label).
 */
OO.ui.ButtonInputWidget = function OoUiButtonInputWidget( config ) {
	// Configuration initialization
	config = $.extend( { type: 'button', useInputTag: false }, config );

	// Properties (must be set before parent constructor, which calls #setValue)
	this.useInputTag = config.useInputTag;

	// Parent constructor
	OO.ui.ButtonInputWidget.super.call( this, config );

	// Mixin constructors
	OO.ui.ButtonElement.call( this, $.extend( {}, config, { $button: this.$input } ) );
	OO.ui.IconElement.call( this, config );
	OO.ui.IndicatorElement.call( this, config );
	OO.ui.LabelElement.call( this, config );
	OO.ui.TitledElement.call( this, $.extend( {}, config, { $titled: this.$input } ) );
	OO.ui.FlaggedElement.call( this, config );

	// Events
	this.$input.on( {
		click: this.onClick.bind( this ),
		keypress: this.onKeyPress.bind( this )
	} );

	// Initialization
	if ( !config.useInputTag ) {
		this.$input.append( this.$icon, this.$label, this.$indicator );
	}
	this.$element.addClass( 'oo-ui-buttonInputWidget' );
};

/* Setup */

OO.inheritClass( OO.ui.ButtonInputWidget, OO.ui.InputWidget );
OO.mixinClass( OO.ui.ButtonInputWidget, OO.ui.ButtonElement );
OO.mixinClass( OO.ui.ButtonInputWidget, OO.ui.IconElement );
OO.mixinClass( OO.ui.ButtonInputWidget, OO.ui.IndicatorElement );
OO.mixinClass( OO.ui.ButtonInputWidget, OO.ui.LabelElement );
OO.mixinClass( OO.ui.ButtonInputWidget, OO.ui.TitledElement );
OO.mixinClass( OO.ui.ButtonInputWidget, OO.ui.FlaggedElement );

/* Events */

/**
 * @event click
 */

/* Methods */

/**
 * Get input element.
 *
 * @private
 * @param {Object} [config] Configuration options
 * @return {jQuery} Input element
 */
OO.ui.ButtonInputWidget.prototype.getInputElement = function ( config ) {
	// Configuration initialization
	config = config || {};

	var html = '<' + ( config.useInputTag ? 'input' : 'button' ) + ' type="' + config.type + '">';

	return this.$( html );
};

/**
 * Set label value.
 *
 * Overridden to support setting the 'value' of `<input/>` elements.
 *
 * @param {jQuery|string|Function|null} label Label nodes; text; a function that returns nodes or
 *  text; or null for no label
 * @chainable
 */
OO.ui.ButtonInputWidget.prototype.setLabel = function ( label ) {
	OO.ui.LabelElement.prototype.setLabel.call( this, label );

	if ( this.useInputTag ) {
		if ( typeof label === 'function' ) {
			label = OO.ui.resolveMsg( label );
		}
		if ( label instanceof jQuery ) {
			label = label.text();
		}
		if ( !label ) {
			label = '';
		}
		this.$input.val( label );
	}

	return this;
};

/**
 * Set the value of the input.
 *
 * Overridden to disable for `<input/>` elements, which have value identical to the label.
 *
 * @param {string} value New value
 * @chainable
 */
OO.ui.ButtonInputWidget.prototype.setValue = function ( value ) {
	if ( !this.useInputTag ) {
		OO.ui.ButtonInputWidget.super.prototype.setValue.call( this, value );
	}
	return this;
};

/**
 * Handles mouse click events.
 *
 * @param {jQuery.Event} e Mouse click event
 * @fires click
 */
OO.ui.ButtonInputWidget.prototype.onClick = function () {
	if ( !this.isDisabled() ) {
		this.emit( 'click' );
	}
	return false;
};

/**
 * Handles keypress events.
 *
 * @param {jQuery.Event} e Keypress event
 * @fires click
 */
OO.ui.ButtonInputWidget.prototype.onKeyPress = function ( e ) {
	if ( !this.isDisabled() && ( e.which === OO.ui.Keys.SPACE || e.which === OO.ui.Keys.ENTER ) ) {
		this.emit( 'click' );
	}
	return false;
};

/**
 * Checkbox input widget.
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

/* Methods */

/**
 * Get input element.
 *
 * @private
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
 * Set checked state of the checkbox
 *
 * @param {boolean} value New value
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
	var widget = this;
	if ( !this.isDisabled() ) {
		// Allow the stack to clear so the value will be updated
		setTimeout( function () {
			widget.setValue( widget.$input.prop( 'checked' ) );
		} );
	}
};

/**
 * Radio input widget.
 *
 * Radio buttons only make sense as a set, and you probably want to use the OO.ui.RadioSelectWidget
 * class instead of using this class directly.
 *
 * This class doesn't make it possible to learn whether the radio button is selected ("pressed").
 *
 * @class
 * @extends OO.ui.InputWidget
 *
 * @constructor
 * @param {Object} [config] Configuration options
 * @param {boolean} [config.selected=false] Whether the radio button is initially selected
 */
OO.ui.RadioInputWidget = function OoUiRadioInputWidget( config ) {
	// Parent constructor
	OO.ui.RadioInputWidget.super.call( this, config );

	// Initialization
	this.$element.addClass( 'oo-ui-radioInputWidget' );
	this.setSelected( config.selected !== undefined ? config.selected : false );
};

/* Setup */

OO.inheritClass( OO.ui.RadioInputWidget, OO.ui.InputWidget );

/* Methods */

/**
 * Get input element.
 *
 * @private
 * @return {jQuery} Input element
 */
OO.ui.RadioInputWidget.prototype.getInputElement = function () {
	return this.$( '<input type="radio" />' );
};

/**
 * @inheritdoc
 */
OO.ui.RadioInputWidget.prototype.onEdit = function () {
	// RadioInputWidget doesn't track its state.
};

/**
 * Set selection state of this radio button.
 *
 * @param {boolean} state Whether the button is selected
 * @chainable
 */
OO.ui.RadioInputWidget.prototype.setSelected = function ( state ) {
	// RadioInputWidget doesn't track its state.
	this.$input.prop( 'checked', state );
	return this;
};

/**
 * Check if this radio button is selected.
 *
 * @return {boolean} Radio is selected
 */
OO.ui.RadioInputWidget.prototype.isSelected = function () {
	return this.$input.prop( 'checked' );
};

/**
 * Input widget with a text field.
 *
 * @class
 * @extends OO.ui.InputWidget
 * @mixins OO.ui.IconElement
 * @mixins OO.ui.IndicatorElement
 * @mixins OO.ui.PendingElement
 *
 * @constructor
 * @param {Object} [config] Configuration options
 * @cfg {string} [type='text'] HTML tag `type` attribute
 * @cfg {string} [placeholder] Placeholder text
 * @cfg {boolean} [readOnly=false] Prevent changes
 * @cfg {boolean} [multiline=false] Allow multiple lines of text
 * @cfg {boolean} [autosize=false] Automatically resize to fit content
 * @cfg {boolean} [maxRows=10] Maximum number of rows to make visible when autosizing
 * @cfg {RegExp|string} [validate] Regular expression (or symbolic name referencing
 *  one, see #static-validationPatterns)
 */
OO.ui.TextInputWidget = function OoUiTextInputWidget( config ) {
	// Configuration initialization
	config = $.extend( { readOnly: false }, config );

	// Parent constructor
	OO.ui.TextInputWidget.super.call( this, config );

	// Mixin constructors
	OO.ui.IconElement.call( this, config );
	OO.ui.IndicatorElement.call( this, config );
	OO.ui.PendingElement.call( this, config );

	// Properties
	this.readOnly = false;
	this.multiline = !!config.multiline;
	this.autosize = !!config.autosize;
	this.maxRows = config.maxRows !== undefined ? config.maxRows : 10;
	this.validate = null;

	// Clone for resizing
	if ( this.autosize ) {
		this.$clone = this.$input
			.clone()
			.insertAfter( this.$input )
			.hide();
	}

	this.setValidation( config.validate );

	// Events
	this.$input.on( {
		keypress: this.onKeyPress.bind( this ),
		blur: this.setValidityFlag.bind( this )
	} );
	this.$element.on( 'DOMNodeInsertedIntoDocument', this.onElementAttach.bind( this ) );
	this.$icon.on( 'mousedown', this.onIconMouseDown.bind( this ) );
	this.$indicator.on( 'mousedown', this.onIndicatorMouseDown.bind( this ) );

	// Initialization
	this.$element
		.addClass( 'oo-ui-textInputWidget' )
		.append( this.$icon, this.$indicator );
	this.setReadOnly( config.readOnly );
	if ( config.placeholder ) {
		this.$input.attr( 'placeholder', config.placeholder );
	}
	this.$element.attr( 'role', 'textbox' );
};

/* Setup */

OO.inheritClass( OO.ui.TextInputWidget, OO.ui.InputWidget );
OO.mixinClass( OO.ui.TextInputWidget, OO.ui.IconElement );
OO.mixinClass( OO.ui.TextInputWidget, OO.ui.IndicatorElement );
OO.mixinClass( OO.ui.TextInputWidget, OO.ui.PendingElement );

/* Static properties */

OO.ui.TextInputWidget.static.validationPatterns = {
	'non-empty': /.+/,
	integer: /^\d+$/
};

/* Events */

/**
 * User presses enter inside the text box.
 *
 * Not called if input is multiline.
 *
 * @event enter
 */

/**
 * User clicks the icon.
 *
 * @event icon
 */

/**
 * User clicks the indicator.
 *
 * @event indicator
 */

/* Methods */

/**
 * Handle icon mouse down events.
 *
 * @param {jQuery.Event} e Mouse down event
 * @fires icon
 */
OO.ui.TextInputWidget.prototype.onIconMouseDown = function ( e ) {
	if ( e.which === 1 ) {
		this.$input[0].focus();
		this.emit( 'icon' );
		return false;
	}
};

/**
 * Handle indicator mouse down events.
 *
 * @param {jQuery.Event} e Mouse down event
 * @fires indicator
 */
OO.ui.TextInputWidget.prototype.onIndicatorMouseDown = function ( e ) {
	if ( e.which === 1 ) {
		this.$input[0].focus();
		this.emit( 'indicator' );
		return false;
	}
};

/**
 * Handle key press events.
 *
 * @param {jQuery.Event} e Key press event
 * @fires enter If enter key is pressed and input is not multiline
 */
OO.ui.TextInputWidget.prototype.onKeyPress = function ( e ) {
	if ( e.which === OO.ui.Keys.ENTER && !this.multiline ) {
		this.emit( 'enter', e );
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
	return OO.ui.TextInputWidget.super.prototype.onEdit.call( this );
};

/**
 * @inheritdoc
 */
OO.ui.TextInputWidget.prototype.setValue = function ( value ) {
	// Parent method
	OO.ui.TextInputWidget.super.prototype.setValue.call( this, value );

	this.setValidityFlag();
	this.adjustSize();
	return this;
};

/**
 * Check if the widget is read-only.
 *
 * @return {boolean}
 */
OO.ui.TextInputWidget.prototype.isReadOnly = function () {
	return this.readOnly;
};

/**
 * Set the read-only state of the widget.
 *
 * This should probably change the widget's appearance and prevent it from being used.
 *
 * @param {boolean} state Make input read-only
 * @chainable
 */
OO.ui.TextInputWidget.prototype.setReadOnly = function ( state ) {
	this.readOnly = !!state;
	this.$input.prop( 'readOnly', this.readOnly );
	return this;
};

/**
 * Automatically adjust the size of the text input.
 *
 * This only affects multi-line inputs that are auto-sized.
 *
 * @chainable
 */
OO.ui.TextInputWidget.prototype.adjustSize = function () {
	var scrollHeight, innerHeight, outerHeight, maxInnerHeight, measurementError, idealHeight;

	if ( this.multiline && this.autosize && this.$input.val() !== this.valCache ) {
		this.$clone
			.val( this.$input.val() )
			.attr( 'rows', '' )
			// Set inline height property to 0 to measure scroll height
			.css( 'height', 0 );

		this.$clone[0].style.display = 'block';

		this.valCache = this.$input.val();

		scrollHeight = this.$clone[0].scrollHeight;

		// Remove inline height property to measure natural heights
		this.$clone.css( 'height', '' );
		innerHeight = this.$clone.innerHeight();
		outerHeight = this.$clone.outerHeight();

		// Measure max rows height
		this.$clone
			.attr( 'rows', this.maxRows )
			.css( 'height', 'auto' )
			.val( '' );
		maxInnerHeight = this.$clone.innerHeight();

		// Difference between reported innerHeight and scrollHeight with no scrollbars present
		// Equals 1 on Blink-based browsers and 0 everywhere else
		measurementError = maxInnerHeight - this.$clone[0].scrollHeight;
		idealHeight = Math.min( maxInnerHeight, scrollHeight + measurementError );

		this.$clone[0].style.display = 'none';

		// Only apply inline height when expansion beyond natural height is needed
		if ( idealHeight > innerHeight ) {
			// Use the difference between the inner and outer height as a buffer
			this.$input.css( 'height', idealHeight + ( outerHeight - innerHeight ) );
		} else {
			this.$input.css( 'height', '' );
		}
	}
	return this;
};

/**
 * Get input element.
 *
 * @private
 * @param {Object} [config] Configuration options
 * @return {jQuery} Input element
 */
OO.ui.TextInputWidget.prototype.getInputElement = function ( config ) {
	// Configuration initialization
	config = config || {};

	var type = config.type || 'text';

	return config.multiline ? this.$( '<textarea>' ) : this.$( '<input type="' + type + '" />' );
};

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
 * Select the contents of the input.
 *
 * @chainable
 */
OO.ui.TextInputWidget.prototype.select = function () {
	this.$input.select();
	return this;
};

/**
 * Sets the validation pattern to use.
 * @param {RegExp|string|null} validate Regular expression (or symbolic name referencing
 *  one, see #static-validationPatterns)
 */
OO.ui.TextInputWidget.prototype.setValidation = function ( validate ) {
	if ( validate instanceof RegExp ) {
		this.validate = validate;
	} else {
		this.validate = this.constructor.static.validationPatterns[validate] || /.*/;
	}
};

/**
 * Sets the 'invalid' flag appropriately.
 */
OO.ui.TextInputWidget.prototype.setValidityFlag = function () {
	var widget = this;
	this.isValid().done( function ( valid ) {
		widget.setFlags( { invalid: !valid } );
	} );
};

/**
 * Returns whether or not the current value is considered valid, according to the
 * supplied validation pattern.
 *
 * @return {jQuery.Deferred}
 */
OO.ui.TextInputWidget.prototype.isValid = function () {
	return $.Deferred().resolve( !!this.getValue().match( this.validate ) ).promise();
};

/**
 * Text input with a menu of optional values.
 *
 * @class
 * @extends OO.ui.Widget
 *
 * @constructor
 * @param {Object} [config] Configuration options
 * @cfg {Object} [menu] Configuration options to pass to menu widget
 * @cfg {Object} [input] Configuration options to pass to input widget
 * @cfg {jQuery} [$overlay] Overlay layer; defaults to relative positioning
 */
OO.ui.ComboBoxWidget = function OoUiComboBoxWidget( config ) {
	// Configuration initialization
	config = config || {};

	// Parent constructor
	OO.ui.ComboBoxWidget.super.call( this, config );

	// Properties
	this.$overlay = config.$overlay || this.$element;
	this.input = new OO.ui.TextInputWidget( $.extend(
		{ $: this.$, indicator: 'down', disabled: this.isDisabled() },
		config.input
	) );
	this.menu = new OO.ui.TextInputMenuSelectWidget( this.input, $.extend(
		{
			$: OO.ui.Element.getJQuery( this.$overlay ),
			widget: this,
			input: this.input,
			disabled: this.isDisabled()
		},
		config.menu
	) );

	// Events
	this.input.connect( this, {
		change: 'onInputChange',
		indicator: 'onInputIndicator',
		enter: 'onInputEnter'
	} );
	this.menu.connect( this, {
		choose: 'onMenuChoose',
		add: 'onMenuItemsChange',
		remove: 'onMenuItemsChange'
	} );

	// Initialization
	this.$element.addClass( 'oo-ui-comboBoxWidget' ).append( this.input.$element );
	this.$overlay.append( this.menu.$element );
	this.onMenuItemsChange();
};

/* Setup */

OO.inheritClass( OO.ui.ComboBoxWidget, OO.ui.Widget );

/* Methods */

/**
 * Get the combobox's menu.
 * @return {OO.ui.TextInputMenuSelectWidget} Menu widget
 */
OO.ui.ComboBoxWidget.prototype.getMenu = function () {
	return this.menu;
};

/**
 * Handle input change events.
 *
 * @param {string} value New value
 */
OO.ui.ComboBoxWidget.prototype.onInputChange = function ( value ) {
	var match = this.menu.getItemFromData( value );

	this.menu.selectItem( match );

	if ( !this.isDisabled() ) {
		this.menu.toggle( true );
	}
};

/**
 * Handle input indicator events.
 */
OO.ui.ComboBoxWidget.prototype.onInputIndicator = function () {
	if ( !this.isDisabled() ) {
		this.menu.toggle();
	}
};

/**
 * Handle input enter events.
 */
OO.ui.ComboBoxWidget.prototype.onInputEnter = function () {
	if ( !this.isDisabled() ) {
		this.menu.toggle( false );
	}
};

/**
 * Handle menu choose events.
 *
 * @param {OO.ui.OptionWidget} item Chosen item
 */
OO.ui.ComboBoxWidget.prototype.onMenuChoose = function ( item ) {
	if ( item ) {
		this.input.setValue( item.getData() );
	}
};

/**
 * Handle menu item change events.
 */
OO.ui.ComboBoxWidget.prototype.onMenuItemsChange = function () {
	var match = this.menu.getItemFromData( this.input.getValue() );
	this.menu.selectItem( match );
	this.$element.toggleClass( 'oo-ui-comboBoxWidget-empty', this.menu.isEmpty() );
};

/**
 * @inheritdoc
 */
OO.ui.ComboBoxWidget.prototype.setDisabled = function ( disabled ) {
	// Parent method
	OO.ui.ComboBoxWidget.super.prototype.setDisabled.call( this, disabled );

	if ( this.input ) {
		this.input.setDisabled( this.isDisabled() );
	}
	if ( this.menu ) {
		this.menu.setDisabled( this.isDisabled() );
	}

	return this;
};

/**
 * Label widget.
 *
 * @class
 * @extends OO.ui.Widget
 * @mixins OO.ui.LabelElement
 *
 * @constructor
 * @param {Object} [config] Configuration options
 * @cfg {OO.ui.InputWidget} [input] Input widget this label is for
 */
OO.ui.LabelWidget = function OoUiLabelWidget( config ) {
	// Configuration initialization
	config = config || {};

	// Parent constructor
	OO.ui.LabelWidget.super.call( this, config );

	// Mixin constructors
	OO.ui.LabelElement.call( this, $.extend( {}, config, { $label: this.$element } ) );
	OO.ui.TitledElement.call( this, config );

	// Properties
	this.input = config.input;

	// Events
	if ( this.input instanceof OO.ui.InputWidget ) {
		this.$element.on( 'click', this.onClick.bind( this ) );
	}

	// Initialization
	this.$element.addClass( 'oo-ui-labelWidget' );
};

/* Setup */

OO.inheritClass( OO.ui.LabelWidget, OO.ui.Widget );
OO.mixinClass( OO.ui.LabelWidget, OO.ui.LabelElement );
OO.mixinClass( OO.ui.LabelWidget, OO.ui.TitledElement );

/* Static Properties */

OO.ui.LabelWidget.static.tagName = 'span';

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
 * Generic option widget for use with OO.ui.SelectWidget.
 *
 * @class
 * @extends OO.ui.Widget
 * @mixins OO.ui.LabelElement
 * @mixins OO.ui.FlaggedElement
 *
 * @constructor
 * @param {Object} [config] Configuration options
 */
OO.ui.OptionWidget = function OoUiOptionWidget( config ) {
	// Configuration initialization
	config = config || {};

	// Parent constructor
	OO.ui.OptionWidget.super.call( this, config );

	// Mixin constructors
	OO.ui.ItemWidget.call( this );
	OO.ui.LabelElement.call( this, config );
	OO.ui.FlaggedElement.call( this, config );

	// Properties
	this.selected = false;
	this.highlighted = false;
	this.pressed = false;

	// Initialization
	this.$element
		.data( 'oo-ui-optionWidget', this )
		.attr( 'role', 'option' )
		.addClass( 'oo-ui-optionWidget' )
		.append( this.$label );
};

/* Setup */

OO.inheritClass( OO.ui.OptionWidget, OO.ui.Widget );
OO.mixinClass( OO.ui.OptionWidget, OO.ui.ItemWidget );
OO.mixinClass( OO.ui.OptionWidget, OO.ui.LabelElement );
OO.mixinClass( OO.ui.OptionWidget, OO.ui.FlaggedElement );

/* Static Properties */

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
	return this.constructor.static.selectable && !this.isDisabled();
};

/**
 * Check if option can be highlighted.
 *
 * @return {boolean} Item is highlightable
 */
OO.ui.OptionWidget.prototype.isHighlightable = function () {
	return this.constructor.static.highlightable && !this.isDisabled();
};

/**
 * Check if option can be pressed.
 *
 * @return {boolean} Item is pressable
 */
OO.ui.OptionWidget.prototype.isPressable = function () {
	return this.constructor.static.pressable && !this.isDisabled();
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
	if ( this.constructor.static.selectable ) {
		this.selected = !!state;
		this.$element.toggleClass( 'oo-ui-optionWidget-selected', state );
		if ( state && this.constructor.static.scrollIntoViewOnSelect ) {
			this.scrollElementIntoView();
		}
		this.updateThemeClasses();
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
	if ( this.constructor.static.highlightable ) {
		this.highlighted = !!state;
		this.$element.toggleClass( 'oo-ui-optionWidget-highlighted', state );
		this.updateThemeClasses();
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
	if ( this.constructor.static.pressable ) {
		this.pressed = !!state;
		this.$element.toggleClass( 'oo-ui-optionWidget-pressed', state );
		this.updateThemeClasses();
	}
	return this;
};

/**
 * Make the option's highlight flash.
 *
 * While flashing, the visual style of the pressed state is removed if present.
 *
 * @return {jQuery.Promise} Promise resolved when flashing is done
 */
OO.ui.OptionWidget.prototype.flash = function () {
	var widget = this,
		$element = this.$element,
		deferred = $.Deferred();

	if ( !this.isDisabled() && this.constructor.static.pressable ) {
		$element.removeClass( 'oo-ui-optionWidget-highlighted oo-ui-optionWidget-pressed' );
		setTimeout( function () {
			// Restore original classes
			$element
				.toggleClass( 'oo-ui-optionWidget-highlighted', widget.highlighted )
				.toggleClass( 'oo-ui-optionWidget-pressed', widget.pressed );

			setTimeout( function () {
				deferred.resolve();
			}, 100 );

		}, 100 );
	}

	return deferred.promise();
};

/**
 * Option widget with an option icon and indicator.
 *
 * Use together with OO.ui.SelectWidget.
 *
 * @class
 * @extends OO.ui.OptionWidget
 * @mixins OO.ui.IconElement
 * @mixins OO.ui.IndicatorElement
 *
 * @constructor
 * @param {Object} [config] Configuration options
 */
OO.ui.DecoratedOptionWidget = function OoUiDecoratedOptionWidget( config ) {
	// Parent constructor
	OO.ui.DecoratedOptionWidget.super.call( this, config );

	// Mixin constructors
	OO.ui.IconElement.call( this, config );
	OO.ui.IndicatorElement.call( this, config );

	// Initialization
	this.$element
		.addClass( 'oo-ui-decoratedOptionWidget' )
		.prepend( this.$icon )
		.append( this.$indicator );
};

/* Setup */

OO.inheritClass( OO.ui.DecoratedOptionWidget, OO.ui.OptionWidget );
OO.mixinClass( OO.ui.OptionWidget, OO.ui.IconElement );
OO.mixinClass( OO.ui.OptionWidget, OO.ui.IndicatorElement );

/**
 * Option widget that looks like a button.
 *
 * Use together with OO.ui.ButtonSelectWidget.
 *
 * @class
 * @extends OO.ui.DecoratedOptionWidget
 * @mixins OO.ui.ButtonElement
 *
 * @constructor
 * @param {Object} [config] Configuration options
 */
OO.ui.ButtonOptionWidget = function OoUiButtonOptionWidget( config ) {
	// Parent constructor
	OO.ui.ButtonOptionWidget.super.call( this, config );

	// Mixin constructors
	OO.ui.ButtonElement.call( this, config );

	// Initialization
	this.$element.addClass( 'oo-ui-buttonOptionWidget' );
	this.$button.append( this.$element.contents() );
	this.$element.append( this.$button );
};

/* Setup */

OO.inheritClass( OO.ui.ButtonOptionWidget, OO.ui.DecoratedOptionWidget );
OO.mixinClass( OO.ui.ButtonOptionWidget, OO.ui.ButtonElement );

/* Static Properties */

// Allow button mouse down events to pass through so they can be handled by the parent select widget
OO.ui.ButtonOptionWidget.static.cancelButtonMouseDownEvents = false;

/* Methods */

/**
 * @inheritdoc
 */
OO.ui.ButtonOptionWidget.prototype.setSelected = function ( state ) {
	OO.ui.ButtonOptionWidget.super.prototype.setSelected.call( this, state );

	if ( this.constructor.static.selectable ) {
		this.setActive( state );
	}

	return this;
};

/**
 * Option widget that looks like a radio button.
 *
 * Use together with OO.ui.RadioSelectWidget.
 *
 * @class
 * @extends OO.ui.OptionWidget
 *
 * @constructor
 * @param {Object} [config] Configuration options
 */
OO.ui.RadioOptionWidget = function OoUiRadioOptionWidget( config ) {
	// Parent constructor
	OO.ui.RadioOptionWidget.super.call( this, config );

	// Properties
	this.radio = new OO.ui.RadioInputWidget( { value: config.data } );

	// Initialization
	this.$element
		.addClass( 'oo-ui-radioOptionWidget' )
		.prepend( this.radio.$element );
};

/* Setup */

OO.inheritClass( OO.ui.RadioOptionWidget, OO.ui.OptionWidget );

/* Static Properties */

OO.ui.RadioOptionWidget.static.highlightable = false;

OO.ui.RadioOptionWidget.static.pressable = false;

/* Methods */

/**
 * @inheritdoc
 */
OO.ui.RadioOptionWidget.prototype.setSelected = function ( state ) {
	OO.ui.RadioOptionWidget.super.prototype.setSelected.call( this, state );

	this.radio.setSelected( state );

	return this;
};

/**
 * Item of an OO.ui.MenuSelectWidget.
 *
 * @class
 * @extends OO.ui.DecoratedOptionWidget
 *
 * @constructor
 * @param {Object} [config] Configuration options
 */
OO.ui.MenuOptionWidget = function OoUiMenuOptionWidget( config ) {
	// Configuration initialization
	config = $.extend( { icon: 'check' }, config );

	// Parent constructor
	OO.ui.MenuOptionWidget.super.call( this, config );

	// Initialization
	this.$element
		.attr( 'role', 'menuitem' )
		.addClass( 'oo-ui-menuOptionWidget' );
};

/* Setup */

OO.inheritClass( OO.ui.MenuOptionWidget, OO.ui.DecoratedOptionWidget );

/**
 * Section to group one or more items in a OO.ui.MenuSelectWidget.
 *
 * @class
 * @extends OO.ui.DecoratedOptionWidget
 *
 * @constructor
 * @param {Object} [config] Configuration options
 */
OO.ui.MenuSectionOptionWidget = function OoUiMenuSectionOptionWidget( config ) {
	// Parent constructor
	OO.ui.MenuSectionOptionWidget.super.call( this, config );

	// Initialization
	this.$element.addClass( 'oo-ui-menuSectionOptionWidget' );
};

/* Setup */

OO.inheritClass( OO.ui.MenuSectionOptionWidget, OO.ui.DecoratedOptionWidget );

/* Static Properties */

OO.ui.MenuSectionOptionWidget.static.selectable = false;

OO.ui.MenuSectionOptionWidget.static.highlightable = false;

/**
 * Items for an OO.ui.OutlineSelectWidget.
 *
 * @class
 * @extends OO.ui.DecoratedOptionWidget
 *
 * @constructor
 * @param {Object} [config] Configuration options
 * @cfg {number} [level] Indentation level
 * @cfg {boolean} [movable] Allow modification from outline controls
 */
OO.ui.OutlineOptionWidget = function OoUiOutlineOptionWidget( config ) {
	// Configuration initialization
	config = config || {};

	// Parent constructor
	OO.ui.OutlineOptionWidget.super.call( this, config );

	// Properties
	this.level = 0;
	this.movable = !!config.movable;
	this.removable = !!config.removable;

	// Initialization
	this.$element.addClass( 'oo-ui-outlineOptionWidget' );
	this.setLevel( config.level );
};

/* Setup */

OO.inheritClass( OO.ui.OutlineOptionWidget, OO.ui.DecoratedOptionWidget );

/* Static Properties */

OO.ui.OutlineOptionWidget.static.highlightable = false;

OO.ui.OutlineOptionWidget.static.scrollIntoViewOnSelect = true;

OO.ui.OutlineOptionWidget.static.levelClass = 'oo-ui-outlineOptionWidget-level-';

OO.ui.OutlineOptionWidget.static.levels = 3;

/* Methods */

/**
 * Check if item is movable.
 *
 * Movability is used by outline controls.
 *
 * @return {boolean} Item is movable
 */
OO.ui.OutlineOptionWidget.prototype.isMovable = function () {
	return this.movable;
};

/**
 * Check if item is removable.
 *
 * Removability is used by outline controls.
 *
 * @return {boolean} Item is removable
 */
OO.ui.OutlineOptionWidget.prototype.isRemovable = function () {
	return this.removable;
};

/**
 * Get indentation level.
 *
 * @return {number} Indentation level
 */
OO.ui.OutlineOptionWidget.prototype.getLevel = function () {
	return this.level;
};

/**
 * Set movability.
 *
 * Movability is used by outline controls.
 *
 * @param {boolean} movable Item is movable
 * @chainable
 */
OO.ui.OutlineOptionWidget.prototype.setMovable = function ( movable ) {
	this.movable = !!movable;
	this.updateThemeClasses();
	return this;
};

/**
 * Set removability.
 *
 * Removability is used by outline controls.
 *
 * @param {boolean} movable Item is removable
 * @chainable
 */
OO.ui.OutlineOptionWidget.prototype.setRemovable = function ( removable ) {
	this.removable = !!removable;
	this.updateThemeClasses();
	return this;
};

/**
 * Set indentation level.
 *
 * @param {number} [level=0] Indentation level, in the range of [0,#maxLevel]
 * @chainable
 */
OO.ui.OutlineOptionWidget.prototype.setLevel = function ( level ) {
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
	this.updateThemeClasses();

	return this;
};

/**
 * Container for content that is overlaid and positioned absolutely.
 *
 * @class
 * @extends OO.ui.Widget
 * @mixins OO.ui.LabelElement
 *
 * @constructor
 * @param {Object} [config] Configuration options
 * @cfg {number} [width=320] Width of popup in pixels
 * @cfg {number} [height] Height of popup, omit to use automatic height
 * @cfg {boolean} [anchor=true] Show anchor pointing to origin of popup
 * @cfg {string} [align='center'] Alignment of popup to origin
 * @cfg {jQuery} [$container] Container to prevent popup from rendering outside of
 * @cfg {number} [containerPadding=10] How much padding to keep between popup and container
 * @cfg {jQuery} [$content] Content to append to the popup's body
 * @cfg {boolean} [autoClose=false] Popup auto-closes when it loses focus
 * @cfg {jQuery} [$autoCloseIgnore] Elements to not auto close when clicked
 * @cfg {boolean} [head] Show label and close button at the top
 * @cfg {boolean} [padded] Add padding to the body
 */
OO.ui.PopupWidget = function OoUiPopupWidget( config ) {
	// Configuration initialization
	config = config || {};

	// Parent constructor
	OO.ui.PopupWidget.super.call( this, config );

	// Mixin constructors
	OO.ui.LabelElement.call( this, config );
	OO.ui.ClippableElement.call( this, config );

	// Properties
	this.visible = false;
	this.$popup = this.$( '<div>' );
	this.$head = this.$( '<div>' );
	this.$body = this.$( '<div>' );
	this.$anchor = this.$( '<div>' );
	// If undefined, will be computed lazily in updateDimensions()
	this.$container = config.$container;
	this.containerPadding = config.containerPadding !== undefined ? config.containerPadding : 10;
	this.autoClose = !!config.autoClose;
	this.$autoCloseIgnore = config.$autoCloseIgnore;
	this.transitionTimeout = null;
	this.anchor = null;
	this.width = config.width !== undefined ? config.width : 320;
	this.height = config.height !== undefined ? config.height : null;
	this.align = config.align || 'center';
	this.closeButton = new OO.ui.ButtonWidget( { $: this.$, framed: false, icon: 'close' } );
	this.onMouseDownHandler = this.onMouseDown.bind( this );

	// Events
	this.closeButton.connect( this, { click: 'onCloseButtonClick' } );

	// Initialization
	this.toggleAnchor( config.anchor === undefined || config.anchor );
	this.$body.addClass( 'oo-ui-popupWidget-body' );
	this.$anchor.addClass( 'oo-ui-popupWidget-anchor' );
	this.$head
		.addClass( 'oo-ui-popupWidget-head' )
		.append( this.$label, this.closeButton.$element );
	if ( !config.head ) {
		this.$head.hide();
	}
	this.$popup
		.addClass( 'oo-ui-popupWidget-popup' )
		.append( this.$head, this.$body );
	this.$element
		.hide()
		.addClass( 'oo-ui-popupWidget' )
		.append( this.$popup, this.$anchor );
	// Move content, which was added to #$element by OO.ui.Widget, to the body
	if ( config.$content instanceof jQuery ) {
		this.$body.append( config.$content );
	}
	if ( config.padded ) {
		this.$body.addClass( 'oo-ui-popupWidget-body-padded' );
	}
	this.setClippableElement( this.$body );
};

/* Setup */

OO.inheritClass( OO.ui.PopupWidget, OO.ui.Widget );
OO.mixinClass( OO.ui.PopupWidget, OO.ui.LabelElement );
OO.mixinClass( OO.ui.PopupWidget, OO.ui.ClippableElement );

/* Methods */

/**
 * Handles mouse down events.
 *
 * @param {jQuery.Event} e Mouse down event
 */
OO.ui.PopupWidget.prototype.onMouseDown = function ( e ) {
	if (
		this.isVisible() &&
		!$.contains( this.$element[0], e.target ) &&
		( !this.$autoCloseIgnore || !this.$autoCloseIgnore.has( e.target ).length )
	) {
		this.toggle( false );
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
	if ( this.isVisible() ) {
		this.toggle( false );
	}
};

/**
 * Unbind mouse down listener.
 */
OO.ui.PopupWidget.prototype.unbindMouseDownListener = function () {
	this.getElementWindow().removeEventListener( 'mousedown', this.onMouseDownHandler, true );
};

/**
 * Set whether to show a anchor.
 *
 * @param {boolean} [show] Show anchor, omit to toggle
 */
OO.ui.PopupWidget.prototype.toggleAnchor = function ( show ) {
	show = show === undefined ? !this.anchored : !!show;

	if ( this.anchored !== show ) {
		if ( show ) {
			this.$element.addClass( 'oo-ui-popupWidget-anchored' );
		} else {
			this.$element.removeClass( 'oo-ui-popupWidget-anchored' );
		}
		this.anchored = show;
	}
};

/**
 * Check if showing a anchor.
 *
 * @return {boolean} anchor is visible
 */
OO.ui.PopupWidget.prototype.hasAnchor = function () {
	return this.anchor;
};

/**
 * @inheritdoc
 */
OO.ui.PopupWidget.prototype.toggle = function ( show ) {
	show = show === undefined ? !this.isVisible() : !!show;

	var change = show !== this.isVisible();

	// Parent method
	OO.ui.PopupWidget.super.prototype.toggle.call( this, show );

	if ( change ) {
		if ( show ) {
			if ( this.autoClose ) {
				this.bindMouseDownListener();
			}
			this.updateDimensions();
			this.toggleClipping( true );
		} else {
			this.toggleClipping( false );
			if ( this.autoClose ) {
				this.unbindMouseDownListener();
			}
		}
	}

	return this;
};

/**
 * Set the size of the popup.
 *
 * Changing the size may also change the popup's position depending on the alignment.
 *
 * @param {number} width Width
 * @param {number} height Height
 * @param {boolean} [transition=false] Use a smooth transition
 * @chainable
 */
OO.ui.PopupWidget.prototype.setSize = function ( width, height, transition ) {
	this.width = width;
	this.height = height !== undefined ? height : null;
	if ( this.isVisible() ) {
		this.updateDimensions( transition );
	}
};

/**
 * Update the size and position.
 *
 * Only use this to keep the popup properly anchored. Use #setSize to change the size, and this will
 * be called automatically.
 *
 * @param {boolean} [transition=false] Use a smooth transition
 * @chainable
 */
OO.ui.PopupWidget.prototype.updateDimensions = function ( transition ) {
	var popupOffset, originOffset, containerLeft, containerWidth, containerRight,
		popupLeft, popupRight, overlapLeft, overlapRight, anchorWidth,
		widget = this;

	if ( !this.$container ) {
		// Lazy-initialize $container if not specified in constructor
		this.$container = this.$( this.getClosestScrollableElementContainer() );
	}

	// Set height and width before measuring things, since it might cause our measurements
	// to change (e.g. due to scrollbars appearing or disappearing)
	this.$popup.css( {
		width: this.width,
		height: this.height !== null ? this.height : 'auto'
	} );

	// Compute initial popupOffset based on alignment
	popupOffset = this.width * ( { left: 0, center: -0.5, right: -1 } )[this.align];

	// Figure out if this will cause the popup to go beyond the edge of the container
	originOffset = Math.round( this.$element.offset().left );
	containerLeft = Math.round( this.$container.offset().left );
	containerWidth = this.$container.innerWidth();
	containerRight = containerLeft + containerWidth;
	popupLeft = popupOffset - this.containerPadding;
	popupRight = popupOffset + this.containerPadding + this.width + this.containerPadding;
	overlapLeft = ( originOffset + popupLeft ) - containerLeft;
	overlapRight = containerRight - ( originOffset + popupRight );

	// Adjust offset to make the popup not go beyond the edge, if needed
	if ( overlapRight < 0 ) {
		popupOffset += overlapRight;
	} else if ( overlapLeft < 0 ) {
		popupOffset -= overlapLeft;
	}

	// Adjust offset to avoid anchor being rendered too close to the edge
	// $anchor.width() doesn't work with the pure CSS anchor (returns 0)
	// TODO: Find a measurement that works for CSS anchors and image anchors
	anchorWidth = this.$anchor[0].scrollWidth * 2;
	if ( popupOffset + this.width < anchorWidth ) {
		popupOffset = anchorWidth - this.width;
	} else if ( -popupOffset < anchorWidth ) {
		popupOffset = -anchorWidth;
	}

	// Prevent transition from being interrupted
	clearTimeout( this.transitionTimeout );
	if ( transition ) {
		// Enable transition
		this.$element.addClass( 'oo-ui-popupWidget-transitioning' );
	}

	// Position body relative to anchor
	this.$popup.css( 'margin-left', popupOffset );

	if ( transition ) {
		// Prevent transitioning after transition is complete
		this.transitionTimeout = setTimeout( function () {
			widget.$element.removeClass( 'oo-ui-popupWidget-transitioning' );
		}, 200 );
	} else {
		// Prevent transitioning immediately
		this.$element.removeClass( 'oo-ui-popupWidget-transitioning' );
	}

	// Reevaluate clipping state since we've relocated and resized the popup
	this.clip();

	return this;
};

/**
 * Progress bar widget.
 *
 * @class
 * @extends OO.ui.Widget
 *
 * @constructor
 * @param {Object} [config] Configuration options
 * @cfg {number|boolean} [progress=false] Initial progress percent or false for indeterminate
 */
OO.ui.ProgressBarWidget = function OoUiProgressBarWidget( config ) {
	// Configuration initialization
	config = config || {};

	// Parent constructor
	OO.ui.ProgressBarWidget.super.call( this, config );

	// Properties
	this.$bar = this.$( '<div>' );
	this.progress = null;

	// Initialization
	this.setProgress( config.progress !== undefined ? config.progress : false );
	this.$bar.addClass( 'oo-ui-progressBarWidget-bar');
	this.$element
		.attr( {
			role: 'progressbar',
			'aria-valuemin': 0,
			'aria-valuemax': 100
		} )
		.addClass( 'oo-ui-progressBarWidget' )
		.append( this.$bar );
};

/* Setup */

OO.inheritClass( OO.ui.ProgressBarWidget, OO.ui.Widget );

/* Static Properties */

OO.ui.ProgressBarWidget.static.tagName = 'div';

/* Methods */

/**
 * Get progress percent
 *
 * @return {number} Progress percent
 */
OO.ui.ProgressBarWidget.prototype.getProgress = function () {
	return this.progress;
};

/**
 * Set progress percent
 *
 * @param {number|boolean} progress Progress percent or false for indeterminate
 */
OO.ui.ProgressBarWidget.prototype.setProgress = function ( progress ) {
	this.progress = progress;

	if ( progress !== false ) {
		this.$bar.css( 'width', this.progress + '%' );
		this.$element.attr( 'aria-valuenow', this.progress );
	} else {
		this.$bar.css( 'width', '' );
		this.$element.removeAttr( 'aria-valuenow' );
	}
	this.$element.toggleClass( 'oo-ui-progressBarWidget-indeterminate', !progress );
};

/**
 * Search widget.
 *
 * Search widgets combine a query input, placed above, and a results selection widget, placed below.
 * Results are cleared and populated each time the query is changed.
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
	// Configuration initialization
	config = config || {};

	// Parent constructor
	OO.ui.SearchWidget.super.call( this, config );

	// Properties
	this.query = new OO.ui.TextInputWidget( {
		$: this.$,
		icon: 'search',
		placeholder: config.placeholder,
		value: config.value
	} );
	this.results = new OO.ui.SelectWidget( { $: this.$ } );
	this.$query = this.$( '<div>' );
	this.$results = this.$( '<div>' );

	// Events
	this.query.connect( this, {
		change: 'onQueryChange',
		enter: 'onQueryEnter'
	} );
	this.results.connect( this, {
		highlight: 'onResultsHighlight',
		select: 'onResultsSelect'
	} );
	this.query.$input.on( 'keydown', this.onQueryKeydown.bind( this ) );

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
 * Get the results list.
 *
 * @return {OO.ui.SelectWidget} Select list
 */
OO.ui.SearchWidget.prototype.getResults = function () {
	return this.results;
};

/**
 * Generic selection of options.
 *
 * Items can contain any rendering. Any widget that provides options, from which the user must
 * choose one, should be built on this class.
 *
 * Use together with OO.ui.OptionWidget.
 *
 * @class
 * @extends OO.ui.Widget
 * @mixins OO.ui.GroupElement
 *
 * @constructor
 * @param {Object} [config] Configuration options
 * @cfg {OO.ui.OptionWidget[]} [items] Options to add
 */
OO.ui.SelectWidget = function OoUiSelectWidget( config ) {
	// Configuration initialization
	config = config || {};

	// Parent constructor
	OO.ui.SelectWidget.super.call( this, config );

	// Mixin constructors
	OO.ui.GroupWidget.call( this, $.extend( {}, config, { $group: this.$element } ) );

	// Properties
	this.pressed = false;
	this.selecting = null;
	this.onMouseUpHandler = this.onMouseUp.bind( this );
	this.onMouseMoveHandler = this.onMouseMove.bind( this );

	// Events
	this.$element.on( {
		mousedown: this.onMouseDown.bind( this ),
		mouseover: this.onMouseOver.bind( this ),
		mouseleave: this.onMouseLeave.bind( this )
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

/* Methods */

/**
 * Handle mouse down events.
 *
 * @private
 * @param {jQuery.Event} e Mouse down event
 */
OO.ui.SelectWidget.prototype.onMouseDown = function ( e ) {
	var item;

	if ( !this.isDisabled() && e.which === 1 ) {
		this.togglePressed( true );
		item = this.getTargetItem( e );
		if ( item && item.isSelectable() ) {
			this.pressItem( item );
			this.selecting = item;
			this.getElementDocument().addEventListener(
				'mouseup',
				this.onMouseUpHandler,
				true
			);
			this.getElementDocument().addEventListener(
				'mousemove',
				this.onMouseMoveHandler,
				true
			);
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
	if ( !this.isDisabled() && e.which === 1 && this.selecting ) {
		this.pressItem( null );
		this.chooseItem( this.selecting );
		this.selecting = null;
	}

	this.getElementDocument().removeEventListener(
		'mouseup',
		this.onMouseUpHandler,
		true
	);
	this.getElementDocument().removeEventListener(
		'mousemove',
		this.onMouseMoveHandler,
		true
	);

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

	if ( !this.isDisabled() && this.pressed ) {
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

	if ( !this.isDisabled() ) {
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
	if ( !this.isDisabled() ) {
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
 * Toggle pressed state.
 *
 * @param {boolean} pressed An option is being pressed
 */
OO.ui.SelectWidget.prototype.togglePressed = function ( pressed ) {
	if ( pressed === undefined ) {
		pressed = !this.pressed;
	}
	if ( pressed !== this.pressed ) {
		this.$element
			.toggleClass( 'oo-ui-selectWidget-pressed', pressed )
			.toggleClass( 'oo-ui-selectWidget-depressed', !pressed );
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
 * @param {number} direction Direction to move in, -1 to look backward, 1 to move forward
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

	while ( len !== 0 ) {
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
 * @return {OO.ui.OptionWidget|null} Item, `null` if there aren't any selectable items
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
 * @param {OO.ui.OptionWidget[]} items Items to add
 * @param {number} [index] Index to insert items after
 * @fires add
 * @chainable
 */
OO.ui.SelectWidget.prototype.addItems = function ( items, index ) {
	// Mixin method
	OO.ui.GroupWidget.prototype.addItems.call( this, items, index );

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
	var i, len, item;

	// Deselect items being removed
	for ( i = 0, len = items.length; i < len; i++ ) {
		item = items[i];
		if ( item.isSelected() ) {
			this.selectItem( null );
		}
	}

	// Mixin method
	OO.ui.GroupWidget.prototype.removeItems.call( this, items );

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

	// Mixin method
	OO.ui.GroupWidget.prototype.clearItems.call( this );

	// Clear selection
	this.selectItem( null );

	this.emit( 'remove', items );

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
 * Select widget containing radio button options.
 *
 * Use together with OO.ui.RadioOptionWidget.
 *
 * @class
 * @extends OO.ui.SelectWidget
 *
 * @constructor
 * @param {Object} [config] Configuration options
 */
OO.ui.RadioSelectWidget = function OoUiRadioSelectWidget( config ) {
	// Parent constructor
	OO.ui.RadioSelectWidget.super.call( this, config );

	// Initialization
	this.$element.addClass( 'oo-ui-radioSelectWidget' );
};

/* Setup */

OO.inheritClass( OO.ui.RadioSelectWidget, OO.ui.SelectWidget );

/**
 * Overlaid menu of options.
 *
 * Menus are clipped to the visible viewport. They do not provide a control for opening or closing
 * the menu.
 *
 * Use together with OO.ui.MenuOptionWidget.
 *
 * @class
 * @extends OO.ui.SelectWidget
 * @mixins OO.ui.ClippableElement
 *
 * @constructor
 * @param {Object} [config] Configuration options
 * @cfg {OO.ui.InputWidget} [input] Input to bind keyboard handlers to
 * @cfg {OO.ui.Widget} [widget] Widget to bind mouse handlers to
 * @cfg {boolean} [autoHide=true] Hide the menu when the mouse is pressed outside the menu
 */
OO.ui.MenuSelectWidget = function OoUiMenuSelectWidget( config ) {
	// Configuration initialization
	config = config || {};

	// Parent constructor
	OO.ui.MenuSelectWidget.super.call( this, config );

	// Mixin constructors
	OO.ui.ClippableElement.call( this, $.extend( {}, config, { $clippable: this.$group } ) );

	// Properties
	this.flashing = false;
	this.visible = false;
	this.newItems = null;
	this.autoHide = config.autoHide === undefined || !!config.autoHide;
	this.$input = config.input ? config.input.$input : null;
	this.$widget = config.widget ? config.widget.$element : null;
	this.$previousFocus = null;
	this.isolated = !config.input;
	this.onKeyDownHandler = this.onKeyDown.bind( this );
	this.onDocumentMouseDownHandler = this.onDocumentMouseDown.bind( this );

	// Initialization
	this.$element
		.hide()
		.attr( 'role', 'menu' )
		.addClass( 'oo-ui-menuSelectWidget' );
};

/* Setup */

OO.inheritClass( OO.ui.MenuSelectWidget, OO.ui.SelectWidget );
OO.mixinClass( OO.ui.MenuSelectWidget, OO.ui.ClippableElement );

/* Methods */

/**
 * Handles document mouse down events.
 *
 * @param {jQuery.Event} e Key down event
 */
OO.ui.MenuSelectWidget.prototype.onDocumentMouseDown = function ( e ) {
	if (
		!OO.ui.contains( this.$element[0], e.target, true ) &&
		( !this.$widget || !OO.ui.contains( this.$widget[0], e.target, true ) )
	) {
		this.toggle( false );
	}
};

/**
 * Handles key down events.
 *
 * @param {jQuery.Event} e Key down event
 */
OO.ui.MenuSelectWidget.prototype.onKeyDown = function ( e ) {
	var nextItem,
		handled = false,
		highlightItem = this.getHighlightedItem();

	if ( !this.isDisabled() && this.isVisible() ) {
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
				this.toggle( false );
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
 * Bind key down listener.
 */
OO.ui.MenuSelectWidget.prototype.bindKeyDownListener = function () {
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
OO.ui.MenuSelectWidget.prototype.unbindKeyDownListener = function () {
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
OO.ui.MenuSelectWidget.prototype.chooseItem = function ( item ) {
	var widget = this;

	// Parent method
	OO.ui.MenuSelectWidget.super.prototype.chooseItem.call( this, item );

	if ( item && !this.flashing ) {
		this.flashing = true;
		item.flash().done( function () {
			widget.toggle( false );
			widget.flashing = false;
		} );
	} else {
		this.toggle( false );
	}

	return this;
};

/**
 * @inheritdoc
 */
OO.ui.MenuSelectWidget.prototype.addItems = function ( items, index ) {
	var i, len, item;

	// Parent method
	OO.ui.MenuSelectWidget.super.prototype.addItems.call( this, items, index );

	// Auto-initialize
	if ( !this.newItems ) {
		this.newItems = [];
	}

	for ( i = 0, len = items.length; i < len; i++ ) {
		item = items[i];
		if ( this.isVisible() ) {
			// Defer fitting label until item has been attached
			item.fitLabel();
		} else {
			this.newItems.push( item );
		}
	}

	// Reevaluate clipping
	this.clip();

	return this;
};

/**
 * @inheritdoc
 */
OO.ui.MenuSelectWidget.prototype.removeItems = function ( items ) {
	// Parent method
	OO.ui.MenuSelectWidget.super.prototype.removeItems.call( this, items );

	// Reevaluate clipping
	this.clip();

	return this;
};

/**
 * @inheritdoc
 */
OO.ui.MenuSelectWidget.prototype.clearItems = function () {
	// Parent method
	OO.ui.MenuSelectWidget.super.prototype.clearItems.call( this );

	// Reevaluate clipping
	this.clip();

	return this;
};

/**
 * @inheritdoc
 */
OO.ui.MenuSelectWidget.prototype.toggle = function ( visible ) {
	visible = ( visible === undefined ? !this.visible : !!visible ) && !!this.items.length;

	var i, len,
		change = visible !== this.isVisible(),
		elementDoc = this.getElementDocument(),
		widgetDoc = this.$widget ? this.$widget[0].ownerDocument : null;

	// Parent method
	OO.ui.MenuSelectWidget.super.prototype.toggle.call( this, visible );

	if ( change ) {
		if ( visible ) {
			this.bindKeyDownListener();

			// Change focus to enable keyboard navigation
			if ( this.isolated && this.$input && !this.$input.is( ':focus' ) ) {
				this.$previousFocus = this.$( ':focus' );
				this.$input[0].focus();
			}
			if ( this.newItems && this.newItems.length ) {
				for ( i = 0, len = this.newItems.length; i < len; i++ ) {
					this.newItems[i].fitLabel();
				}
				this.newItems = null;
			}
			this.toggleClipping( true );

			// Auto-hide
			if ( this.autoHide ) {
				elementDoc.addEventListener(
					'mousedown', this.onDocumentMouseDownHandler, true
				);
				// Support $widget being in a different document
				if ( widgetDoc && widgetDoc !== elementDoc ) {
					widgetDoc.addEventListener(
						'mousedown', this.onDocumentMouseDownHandler, true
					);
				}
			}
		} else {
			this.unbindKeyDownListener();
			if ( this.isolated && this.$previousFocus ) {
				this.$previousFocus[0].focus();
				this.$previousFocus = null;
			}
			elementDoc.removeEventListener(
				'mousedown', this.onDocumentMouseDownHandler, true
			);
			// Support $widget being in a different document
			if ( widgetDoc && widgetDoc !== elementDoc ) {
				widgetDoc.removeEventListener(
					'mousedown', this.onDocumentMouseDownHandler, true
				);
			}
			this.toggleClipping( false );
		}
	}

	return this;
};

/**
 * Menu for a text input widget.
 *
 * This menu is specially designed to be positioned beneath the text input widget. Even if the input
 * is in a different frame, the menu's position is automatically calculated and maintained when the
 * menu is toggled or the window is resized.
 *
 * @class
 * @extends OO.ui.MenuSelectWidget
 *
 * @constructor
 * @param {OO.ui.TextInputWidget} input Text input widget to provide menu for
 * @param {Object} [config] Configuration options
 * @cfg {jQuery} [$container=input.$element] Element to render menu under
 */
OO.ui.TextInputMenuSelectWidget = function OoUiTextInputMenuSelectWidget( input, config ) {
	// Configuration initialization
	config = config || {};

	// Parent constructor
	OO.ui.TextInputMenuSelectWidget.super.call( this, config );

	// Properties
	this.input = input;
	this.$container = config.$container || this.input.$element;
	this.onWindowResizeHandler = this.onWindowResize.bind( this );

	// Initialization
	this.$element.addClass( 'oo-ui-textInputMenuSelectWidget' );
};

/* Setup */

OO.inheritClass( OO.ui.TextInputMenuSelectWidget, OO.ui.MenuSelectWidget );

/* Methods */

/**
 * Handle window resize event.
 *
 * @param {jQuery.Event} e Window resize event
 */
OO.ui.TextInputMenuSelectWidget.prototype.onWindowResize = function () {
	this.position();
};

/**
 * @inheritdoc
 */
OO.ui.TextInputMenuSelectWidget.prototype.toggle = function ( visible ) {
	visible = visible === undefined ? !this.isVisible() : !!visible;

	var change = visible !== this.isVisible();

	if ( change && visible ) {
		// Make sure the width is set before the parent method runs.
		// After this we have to call this.position(); again to actually
		// position ourselves correctly.
		this.position();
	}

	// Parent method
	OO.ui.TextInputMenuSelectWidget.super.prototype.toggle.call( this, visible );

	if ( change ) {
		if ( this.isVisible() ) {
			this.position();
			this.$( this.getElementWindow() ).on( 'resize', this.onWindowResizeHandler );
		} else {
			this.$( this.getElementWindow() ).off( 'resize', this.onWindowResizeHandler );
		}
	}

	return this;
};

/**
 * Position the menu.
 *
 * @chainable
 */
OO.ui.TextInputMenuSelectWidget.prototype.position = function () {
	var $container = this.$container,
		pos = OO.ui.Element.getRelativePosition( $container, this.$element.offsetParent() );

	// Position under input
	pos.top += $container.height();
	this.$element.css( pos );

	// Set width
	this.setIdealSize( $container.width() );
	// We updated the position, so re-evaluate the clipping state
	this.clip();

	return this;
};

/**
 * Structured list of items.
 *
 * Use with OO.ui.OutlineOptionWidget.
 *
 * @class
 * @extends OO.ui.SelectWidget
 *
 * @constructor
 * @param {Object} [config] Configuration options
 */
OO.ui.OutlineSelectWidget = function OoUiOutlineSelectWidget( config ) {
	// Configuration initialization
	config = config || {};

	// Parent constructor
	OO.ui.OutlineSelectWidget.super.call( this, config );

	// Initialization
	this.$element.addClass( 'oo-ui-outlineSelectWidget' );
};

/* Setup */

OO.inheritClass( OO.ui.OutlineSelectWidget, OO.ui.SelectWidget );

/**
 * Switch that slides on and off.
 *
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
	this.$element.on( 'click', this.onClick.bind( this ) );

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
	if ( !this.isDisabled() && e.which === 1 ) {
		this.setValue( !this.value );
	}
};

}( OO ) );
