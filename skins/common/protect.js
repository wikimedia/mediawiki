( function ( mw, $ ) {

var ProtectionForm = window.ProtectionForm = {
	existingMatch: false,

	/**
	 * Set up the protection chaining interface (i.e. "unlock move permissions" checkbox)
	 * on the protection form
	 *
	 * @param opts Object : parameters with members:
	 *     tableId              Identifier of the table containing UI bits
	 *     labelText            Text to use for the checkbox label
	 *     numTypes             The number of protection types
	 *     existingMatch        True if all the existing expiry times match
	 */
	init: function ( opts ) {
		var box, boxbody, row, cell, check, label;

		if ( !( document.createTextNode && document.getElementById && document.getElementsByTagName ) ) {
			return false;
		}

		box = document.getElementById( opts.tableId );
		if ( !box ) {
			return false;
		}

		boxbody = box.getElementsByTagName( 'tbody' )[0];
		row = document.createElement( 'tr' );
		boxbody.insertBefore( row, boxbody.firstChild.nextSibling );

		this.existingMatch = opts.existingMatch;

		cell = document.createElement( 'td' );
		row.appendChild( cell );
		// If there is only one protection type, there is nothing to chain
		if ( opts.numTypes > 1 ) {
			check = document.createElement( 'input' );
			check.id = 'mwProtectUnchained';
			check.type = 'checkbox';
			$( check ).click( function () {
				ProtectionForm.onChainClick();
			} );

			label = document.createElement( 'label' );
			label.htmlFor = 'mwProtectUnchained';
			label.appendChild( document.createTextNode( opts.labelText ) );

			cell.appendChild( check );
			cell.appendChild( document.createTextNode( ' ' ) );
			cell.appendChild( label );

			check.checked = !this.areAllTypesMatching();
			this.enableUnchainedInputs( check.checked );
		}

		$( '#mwProtect-reason' ).byteLimit( 180 );

		this.updateCascadeCheckbox();

		return true;
	},

	/**
	 * Sets the disabled attribute on the cascade checkbox depending on the current selected levels
	 */
	updateCascadeCheckbox: function () {
		var i, lists, items, selected;

		// For non-existent titles, there is no cascade option
		if ( !document.getElementById( 'mwProtect-cascade' ) ) {
			return;
		}
		lists = this.getLevelSelectors();
		for ( i = 0; i < lists.length; i++ ) {
			if ( lists[i].selectedIndex > -1 ) {
				items = lists[i].getElementsByTagName( 'option' );
				selected = items[ lists[i].selectedIndex ].value;
				if ( !this.isCascadeableLevel( selected ) ) {
					document.getElementById( 'mwProtect-cascade' ).checked = false;
					document.getElementById( 'mwProtect-cascade' ).disabled = true;
					return;
				}
			}
		}
		document.getElementById( 'mwProtect-cascade' ).disabled = false;
	},

	/**
	 * Checks if a cerain protection level is cascadeable.
	 * @param level {String}
	 * @return {Boolean}
	 */
	isCascadeableLevel: function (  level ) {
		var cascadeLevels, len, i;

		cascadeLevels = mw.config.get( 'wgCascadeableLevels' );
		// cascadeLevels isn't defined on all pages
		if ( cascadeLevels ) {
			for ( i = 0, len = cascadeLevels.length; i < len; i += 1 ) {
				if ( cascadeLevels[i] === level ) {
					return true;
				}
			}
		}
		return false;
	},

	/**
	 * When protection levels are locked together, update the rest
	 * when one action's level changes
	 *
	 * @param source Element Level selector that changed
	 */
	updateLevels: function ( source ) {
		if ( !this.isUnchained() ) {
			this.setAllSelectors( source.selectedIndex );
		}
		this.updateCascadeCheckbox();
	},

	/**
	 * When protection levels are locked together, update the
	 * expiries when one changes
	 *
	 * @param source Element expiry input that changed
	 */

	updateExpiry: function ( source ) {
		var expiry, listId, list;

		if ( !this.isUnchained() ) {
			expiry = source.value;
			this.forEachExpiryInput( function ( element ) {
				element.value = expiry;
			} );
		}
		listId = source.id.replace( /^mwProtect-(\w+)-expires$/, 'mwProtectExpirySelection-$1' );
		list = document.getElementById( listId );
		if ( list && list.value !== 'othertime' ) {
			if ( this.isUnchained() ) {
				list.value = 'othertime';
			} else {
				this.forEachExpirySelector( function ( element ) {
					element.value = 'othertime';
				} );
			}
		}
	},

	/**
	 * When protection levels are locked together, update the
	 * expiry lists when one changes and clear the custom inputs
	 *
	 * @param source Element expiry selector that changed
	 */
	updateExpiryList: function ( source ) {
		var expiry;
		if ( !this.isUnchained() ) {
			expiry = source.value;
			this.forEachExpirySelector( function ( element ) {
				element.value = expiry;
			} );
			this.forEachExpiryInput( function ( element ) {
				element.value = '';
			} );
		}
	},

	/**
	 * Update chain status and enable/disable various bits of the UI
	 * when the user changes the "unlock move permissions" checkbox
	 */
	onChainClick: function () {
		if ( this.isUnchained() ) {
			this.enableUnchainedInputs( true );
		} else {
			this.setAllSelectors( this.getMaxLevel() );
			this.enableUnchainedInputs( false );
		}
		this.updateCascadeCheckbox();
	},

	/**
	 * Returns true if the named attribute in all objects in the given array are matching
	 */
	matchAttribute: function ( objects, attrName ) {
		var i, element, value;

		// Check levels
		value = null;
		for ( i = 0; i < objects.length; i++ ) {
			element = objects[i];
			if ( value === null ) {
				value = element[attrName];
			} else {
				if ( value !== element[attrName] ) {
					return false;
				}
			}
		}
		return true;
	},

	/**
	 * Are all actions protected at the same level, with the same expiry time?
	 *
	 * @return boolean
	 */
	areAllTypesMatching: function () {
		return this.existingMatch
			&& this.matchAttribute( this.getLevelSelectors(), 'selectedIndex' )
			&& this.matchAttribute( this.getExpirySelectors(), 'selectedIndex' )
			&& this.matchAttribute( this.getExpiryInputs(), 'value' );
	},

	/**
	 * Is protection chaining off?
	 *
	 * @return bool
	 */
	isUnchained: function () {
		var element = document.getElementById( 'mwProtectUnchained' );
		return element
			? element.checked
			: true; // No control, so we need to let the user set both levels
	},

	/**
	 * Find the highest protection level in any selector
	 */
	getMaxLevel: function () {
		var maxIndex = -1;
		this.forEachLevelSelector( function ( element ) {
			if ( element.selectedIndex > maxIndex ) {
				maxIndex = element.selectedIndex;
			}
		} );
		return maxIndex;
	},

	/**
	 * Protect all actions at the specified level
	 *
	 * @param index int Protection level
	 */
	setAllSelectors: function ( index ) {
		this.forEachLevelSelector( function ( element ) {
			if ( element.selectedIndex !== index ) {
				element.selectedIndex = index;
			}
		} );
	},

	/**
	 * Apply a callback to each protection selector
	 *
	 * @param func callable Callback function
	 */
	forEachLevelSelector: function ( func ) {
		var i, selectors;

		selectors = this.getLevelSelectors();
		for ( i = 0; i < selectors.length; i++ ) {
			func( selectors[i] );
		}
	},

	/**
	 * Get a list of all protection selectors on the page
	 *
	 * @return Array
	 */
	getLevelSelectors: function () {
		var i, ours, all, element;

		all = document.getElementsByTagName( 'select' );
		ours = [];
		for ( i = 0; i < all.length; i++ ) {
			element = all[i];
			if ( element.id.match( /^mwProtect-level-/ ) ) {
				ours[ours.length] = element;
			}
		}
		return ours;
	},

	/**
	 * Apply a callback to each expiry input
	 *
	 * @param func callable Callback function
	 */
	forEachExpiryInput: function ( func ) {
		var i, inputs;

		inputs = this.getExpiryInputs();
		for ( i = 0; i < inputs.length; i++ ) {
			func( inputs[i] );
		}
	},

	/**
	 * Get a list of all expiry inputs on the page
	 *
	 * @return Array
	 */
	getExpiryInputs: function () {
		var i, all, element, ours;

		all = document.getElementsByTagName( 'input' );
		ours = [];
		for ( i = 0; i < all.length; i++ ) {
			element = all[i];
			if ( element.name.match( /^mwProtect-expiry-/ ) ) {
				ours[ours.length] = element;
			}
		}
		return ours;
	},

	/**
	 * Apply a callback to each expiry selector list
	 * @param func callable Callback function
	 */
	forEachExpirySelector: function ( func ) {
		var i, inputs;

		inputs = this.getExpirySelectors();
		for ( i = 0; i < inputs.length; i++ ) {
			func( inputs[i] );
		}
	},

	/**
	 * Get a list of all expiry selector lists on the page
	 *
	 * @return Array
	 */
	getExpirySelectors: function () {
		var i, all, ours, element;

		all = document.getElementsByTagName( 'select' );
		ours = [];
		for ( i = 0; i < all.length; i++ ) {
			element = all[i];
			if ( element.id.match( /^mwProtectExpirySelection-/ ) ) {
				ours[ours.length] = element;
			}
		}
		return ours;
	},

	/**
	 * Enable/disable protection selectors and expiry inputs
	 *
	 * @param val boolean Enable?
	 */
	enableUnchainedInputs: function ( val ) {
		var first = true;

		this.forEachLevelSelector( function ( element ) {
			if ( first ) {
				first = false;
			} else {
				element.disabled = !val;
			}
		} );
		first = true;
		this.forEachExpiryInput( function ( element ) {
			if ( first ) {
				first = false;
			} else {
				element.disabled = !val;
			}
		} );
		first = true;
		this.forEachExpirySelector( function ( element ) {
			if ( first ) {
				first = false;
			} else {
				element.disabled = !val;
			}
		} );
	}
};

}( mediaWiki, jQuery ) );
