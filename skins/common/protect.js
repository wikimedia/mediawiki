
window.ProtectionForm = {
	'existingMatch': false,

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
	'init': function( opts ) {
		if( !( document.createTextNode && document.getElementById && document.getElementsByTagName ) )
			return false;

		var box = document.getElementById( opts.tableId );
		if( !box )
			return false;
		
		var boxbody = box.getElementsByTagName('tbody')[0]
		var row = document.createElement( 'tr' );
		boxbody.insertBefore( row, boxbody.firstChild.nextSibling );

		this.existingMatch = opts.existingMatch;

		var cell = document.createElement( 'td' );
		row.appendChild( cell );
		// If there is only one protection type, there is nothing to chain
		if( opts.numTypes > 1 ) {
			var check = document.createElement( 'input' );
			check.id = 'mwProtectUnchained';
			check.type = 'checkbox';
			cell.appendChild( check );
			addClickHandler( check, function() { ProtectionForm.onChainClick(); } );

			cell.appendChild( document.createTextNode( ' ' ) );
			var label = document.createElement( 'label' );
			label.htmlFor = 'mwProtectUnchained';
			label.appendChild( document.createTextNode( opts.labelText ) );
			cell.appendChild( label );

			check.checked = !this.areAllTypesMatching();
			this.enableUnchainedInputs( check.checked );
		}

		this.updateCascadeCheckbox();

		return true;
	},

	/**
	 * Sets the disabled attribute on the cascade checkbox depending on the current selected levels
	 */
	'updateCascadeCheckbox': function() {
		// For non-existent titles, there is no cascade option
		if( !document.getElementById( 'mwProtect-cascade' ) ) {
			return;
		}
		var lists = this.getLevelSelectors();
		for( var i = 0; i < lists.length; i++ ) {
			if( lists[i].selectedIndex > -1 ) {
				var items = lists[i].getElementsByTagName( 'option' );
				var selected = items[ lists[i].selectedIndex ].value;
				if( !this.isCascadeableLevel(selected) ) {
					document.getElementById( 'mwProtect-cascade' ).checked = false;
					document.getElementById( 'mwProtect-cascade' ).disabled = true;
					return;
				}
			}
		}
		document.getElementById( 'mwProtect-cascade' ).disabled = false;
	},

	/**
	 * Is this protection level cascadeable?
	 * @param level String
	 *
	 * @return boolean
	 *
	 */
	'isCascadeableLevel': function( level ) {
		for (var k = 0; k < wgCascadeableLevels.length; k++) {
			if ( wgCascadeableLevels[k] == level ) {
				return true;
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
	'updateLevels': function(source) {
		if( !this.isUnchained() )
			this.setAllSelectors( source.selectedIndex );
		this.updateCascadeCheckbox();
	},

	/**
	 * When protection levels are locked together, update the
	 * expiries when one changes
	 *
	 * @param source Element expiry input that changed
	 */

	'updateExpiry': function(source) {
		if( !this.isUnchained() ) {
			var expiry = source.value;
			this.forEachExpiryInput(function(element) {
				element.value = expiry;
			});
		}
		var listId = source.id.replace( /^mwProtect-(\w+)-expires$/, 'mwProtectExpirySelection-$1' );
		var list = document.getElementById( listId );
		if (list && list.value != 'othertime' ) {
			if ( this.isUnchained() ) {
				list.value = 'othertime';
			} else {
				this.forEachExpirySelector(function(element) {
					element.value = 'othertime';
				});
			}
		}
	},

	/**
	 * When protection levels are locked together, update the
	 * expiry lists when one changes and clear the custom inputs
	 *
	 * @param source Element expiry selector that changed
	 */
	'updateExpiryList': function(source) {
		if( !this.isUnchained() ) {
			var expiry = source.value;
			this.forEachExpirySelector(function(element) {
				element.value = expiry;
			});
			this.forEachExpiryInput(function(element) {
				element.value = '';
			});
		}
	},

	/**
	 * Update chain status and enable/disable various bits of the UI
	 * when the user changes the "unlock move permissions" checkbox
	 */
	'onChainClick': function() {
		if( this.isUnchained() ) {
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
	'matchAttribute' : function( objects, attrName ) {
		var value = null;

		// Check levels
		for ( var i = 0; i < objects.length; i++ ) {
			var element = objects[i];
			if ( value == null ) {
				value = element[attrName];
			} else {
				if ( value != element[attrName] ) {
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
	'areAllTypesMatching': function() {
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
	'isUnchained': function() {
		var element = document.getElementById( 'mwProtectUnchained' );
		return element
			? element.checked
			: true; // No control, so we need to let the user set both levels
	},

	/**
	 * Find the highest protection level in any selector
	 */
	'getMaxLevel': function() {
		var maxIndex = -1;
		this.forEachLevelSelector(function(element) {
			if (element.selectedIndex > maxIndex) {
				maxIndex = element.selectedIndex;
			}
		});
		return maxIndex;
	},

	/**
	 * Protect all actions at the specified level
	 *
	 * @param index int Protection level
	 */
	'setAllSelectors': function(index) {
		this.forEachLevelSelector(function(element) {
			if (element.selectedIndex != index) {
				element.selectedIndex = index;
			}
		});
	},

	/**
	 * Apply a callback to each protection selector
	 *
	 * @param func callable Callback function
	 */
	'forEachLevelSelector': function(func) {
		var selectors = this.getLevelSelectors();
		for (var i = 0; i < selectors.length; i++) {
			func(selectors[i]);
		}
	},

	/**
	 * Get a list of all protection selectors on the page
	 *
	 * @return Array
	 */
	'getLevelSelectors': function() {
		var all = document.getElementsByTagName("select");
		var ours = new Array();
		for (var i = 0; i < all.length; i++) {
			var element = all[i];
			if (element.id.match(/^mwProtect-level-/)) {
				ours[ours.length] = element;
			}
		}
		return ours;
	},

	/**
	 * Apply a callback to each expiry input
	 *
	 * @param callable func Callback function
	 */
	'forEachExpiryInput': function(func) {
		var inputs = this.getExpiryInputs();
		for (var i = 0; i < inputs.length; i++) {
			func(inputs[i]);
		}
	},

	/**
	 * Get a list of all expiry inputs on the page
	 *
	 * @return Array
	 */
	'getExpiryInputs': function() {
		var all = document.getElementsByTagName("input");
		var ours = new Array();
		for (var i = 0; i < all.length; i++) {
			var element = all[i];
			if (element.name.match(/^mwProtect-expiry-/)) {
				ours[ours.length] = element;
			}
		}
		return ours;
	},

	/**
	 * Apply a callback to each expiry selector list
	 * @param callable func Callback function
	 */
	'forEachExpirySelector': function(func) {
		var inputs = this.getExpirySelectors();
		for (var i = 0; i < inputs.length; i++) {
			func(inputs[i]);
		}
	},

	/**
	 * Get a list of all expiry selector lists on the page
	 *
	 * @return Array
	 */
	'getExpirySelectors': function() {
		var all = document.getElementsByTagName("select");
		var ours = new Array();
		for (var i = 0; i < all.length; i++) {
			var element = all[i];
			if (element.id.match(/^mwProtectExpirySelection-/)) {
				ours[ours.length] = element;
			}
		}
		return ours;
	},

	/**
	 * Enable/disable protection selectors and expiry inputs
	 *
	 * @param boolean val Enable?
	 */
	'enableUnchainedInputs': function(val) {
		var first = true;
		this.forEachLevelSelector(function(element) {
			if (first) {
				first = false;
			} else {
				element.disabled = !val;
			}
		});
		first = true;
		this.forEachExpiryInput(function(element) {
			if (first) {
				first = false;
			} else {
				element.disabled = !val;
			}
		});
		first = true;
		this.forEachExpirySelector(function(element) {
			if (first) {
				first = false;
			} else {
				element.disabled = !val;
			}
		});
	}
}
