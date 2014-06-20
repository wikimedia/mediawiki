( function ( mw, $ ) {

var ProtectionForm = window.ProtectionForm = {
	existingMatch: false,

	/**
	 * Set up the protection chaining interface (i.e. "unlock move permissions" checkbox)
	 * on the protection form
	 * Called from html form
	 *
	 * @param opts Object : parameters with members:
	 *     tableId              Identifier of the table containing UI bits
	 *     labelText            Text to use for the checkbox label
	 *     numTypes             The number of protection types
	 *     existingMatch        True if all the existing expiry times match
	 */
	init: function ( opts ) {
		var $box, $boxbody, $row, $cell, $check, $label;

		$box = $( document.getElementById( opts.tableId ) );
		if ( !$box.length ) {
			return false;
		}

		$boxbody = $box.find( 'tbody:first' );
		$row = $( '<tr>' );
		$row.insertAfter( $boxbody.find( 'tr:first' ) );

		this.existingMatch = opts.existingMatch;

		$cell = $( '<td>' );
		$row.append( $cell );
		// If there is only one protection type, there is nothing to chain
		if ( opts.numTypes > 1 ) {
			$check = $( '<input>' );
			$check.attr( {
				'id': 'mwProtectUnchained',
				'type': 'checkbox'
			} );
			$check.click( function () {
				ProtectionForm.onChainClick();
			} );

			$label = $( '<label>' );
			$label.attr( 'htmlFor', 'mwProtectUnchained' );
			$label.text( opts.labelText );

			$cell.append( $check );
			$cell.append( ' ' );
			$cell.append( $label );

			$check.prop( 'checked', !this.areAllTypesMatching() );
			this.enableUnchainedInputs( $check.prop( 'checked' ) );
		}

		$( '#mwProtect-reason' ).byteLimit( 180 );

		this.updateCascadeCheckbox();

		return true;
	},

	/**
	 * Sets the disabled attribute on the cascade checkbox depending on the current selected levels
	 */
	updateCascadeCheckbox: function () {
		var selected,
			$mwProtectCascade,
			form = this,
			found = false;

		// For non-existent titles, there is no cascade option
		$mwProtectCascade = $( '#mwProtect-cascade' );
		if ( !$mwProtectCascade.length ) {
			return;
		}

		this.getLevelSelectors().each( function ( index, element ) {
			selected = $( element ).val();
			if ( !form.isCascadeableLevel( selected ) ) {
				$mwProtectCascade.prop( {
					'checked': false,
					'disabled': true
				} );
				found = true;
				return false;
			}
		} );

		if ( !found ) {
			$mwProtectCascade.prop( 'disabled', false );
		}
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
	 * Called on onchange from html form
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
	 * Called from onchange or onkeyup on the html form
	 *
	 * @param source Element expiry input that changed
	 */
	updateExpiry: function ( source ) {
		var expiry, listId, list;

		if ( !this.isUnchained() ) {
			expiry = source.value;
			this.getExpiryInputs().each( function ( index, element ) {
				element.value = expiry;
			} );
		}
		listId = source.id.replace( /^mwProtect-(\w+)-expires$/, 'mwProtectExpirySelection-$1' );
		list = document.getElementById( listId );
		if ( list && list.value !== 'othertime' ) {
			if ( this.isUnchained() ) {
				list.value = 'othertime';
			} else {
				this.getExpirySelectors().each( function ( index, element ) {
					element.value = 'othertime';
				} );
			}
		}
	},

	/**
	 * When protection levels are locked together, update the
	 * expiry lists when one changes and clear the custom inputs
	 * Called from onchange on the html form
	 *
	 * @param source Element expiry selector that changed
	 */
	updateExpiryList: function ( source ) {
		var expiry;
		if ( !this.isUnchained() ) {
			expiry = source.value;
			this.getExpirySelectors().each( function ( index, element ) {
				element.value = expiry;
			} );
			this.getExpiryInputs().each( function ( index, element ) {
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
		var $element = $( '#mwProtectUnchained' );
		return $element.length
			? $element.prop( 'checked' )
			: true; // No control, so we need to let the user set both levels
	},

	/**
	 * Find the highest protection level in any selector
	 */
	getMaxLevel: function () {
		var maxIndex = -1;
		this.getLevelSelectors().each( function ( index, element ) {
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
		this.getLevelSelectors().prop( 'selectedIndex', index );
	},

	/**
	 * Get a list of all protection selectors on the page
	 *
	 * @return jQuery
	 */
	getLevelSelectors: function () {
		return $( 'select' ).filter( function ( index, element ) {
			return element.id.match( /^mwProtect-level-/ );
		} );
	},

	/**
	 * Get a list of all expiry inputs on the page
	 *
	 * @return jQuery
	 */
	getExpiryInputs: function () {
		return $( 'input' ).filter( function ( index, element ) {
			return element.name.match( /^mwProtect-expiry-/ );
		} );
	},

	/**
	 * Get a list of all expiry selector lists on the page
	 *
	 * @return jQuery
	 */
	getExpirySelectors: function () {
		return $( 'select' ).filter( function ( index, element ) {
			return element.id.match( /^mwProtectExpirySelection-/ );
		} );
	},

	/**
	 * Enable/disable protection selectors and expiry inputs
	 * Also called on onsubmit from the html form
	 *
	 * @param val boolean Enable?
	 */
	enableUnchainedInputs: function ( val ) {
		this.getLevelSelectors().each( function ( index, element ) {
			if ( index !== 0 ) {
				element.disabled = !val;
			}
		} );
		this.getExpiryInputs().each( function ( index, element ) {
			if ( index !== 0 ) {
				element.disabled = !val;
			}
		} );
		this.getExpirySelectors().each( function ( index, element ) {
			if ( index !== 0 ) {
				element.disabled = !val;
			}
		} );
	}
};

}( mediaWiki, jQuery ) );
