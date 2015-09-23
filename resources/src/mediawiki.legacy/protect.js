( function ( mw, $ ) {

var ProtectionForm = window.ProtectionForm = {
	/**
	 * Set up the protection chaining interface (i.e. "unlock move permissions" checkbox)
	 * on the protection form
	 */
	init: function () {
		var $cell = $( '<td>' ),
			$row = $( '<tr>' ).append( $cell );

		if ( !$( '#mwProtectSet' ).length ) {
			return false;
		}

		if ( mw.config.get( 'wgCascadeableLevels' ) !== undefined ) {
			$( 'form#mw-Protect-Form' ).submit( this.toggleUnchainedInputs.bind( ProtectionForm, true ) );
		}
		this.getExpirySelectors().each( function () {
			$( this ).change( ProtectionForm.updateExpiryList.bind( ProtectionForm, this ) );
		} );
		this.getExpiryInputs().each( function () {
			$( this ).on( 'keyup change', ProtectionForm.updateExpiry.bind( ProtectionForm, this ) );
		} );
		this.getLevelSelectors().each( function () {
			$( this ).change( ProtectionForm.updateLevels.bind( ProtectionForm, this ) );
		} );

		$( '#mwProtectSet > tbody > tr:first' ).after( $row );

		// If there is only one protection type, there is nothing to chain
		if ( $( '[id ^= mw-protect-table-]' ).length > 1 ) {
			$cell.append(
				$( '<input>' )
					.attr( { id: 'mwProtectUnchained', type: 'checkbox' } )
					.click( this.onChainClick.bind( this ) )
					.prop( 'checked', !this.areAllTypesMatching() ),
				document.createTextNode( ' ' ),
				$( '<label>' )
					.attr( 'for', 'mwProtectUnchained' )
					.text( mw.msg( 'protect-unchain-permissions' ) )
			);

			this.toggleUnchainedInputs( !this.areAllTypesMatching() );
		}

		$( '#mwProtect-reason' ).byteLimit( 180 );

		this.updateCascadeCheckbox();
	},

	/**
	 * Sets the disabled attribute on the cascade checkbox depending on the current selected levels
	 */
	updateCascadeCheckbox: function () {
		this.getLevelSelectors().each( function () {
			if ( !ProtectionForm.isCascadeableLevel( $( this ).val() ) ) {
				$( '#mwProtect-cascade' ).prop( { checked: false, disabled: true } );
				return false;
			} else {
				$( '#mwProtect-cascade' ).prop( 'disabled', false );
			}
		} );
	},

	/**
	 * Checks if a certain protection level is cascadeable.
	 *
	 * @param {string} level
	 * @return {boolean}
	 */
	isCascadeableLevel: function ( level ) {
		return $.inArray( level, mw.config.get( 'wgCascadeableLevels' ) ) !== -1;
	},

	/**
	 * When protection levels are locked together, update the rest
	 * when one action's level changes
	 *
	 * @param {Element} source Level selector that changed
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
	 * @param {Element} source expiry input that changed
	 */

	updateExpiry: function ( source ) {
		if ( !this.isUnchained() ) {
			this.getExpiryInputs().each( function () {
				this.value = source.value;
			} );
		}
		if ( this.isUnchained() ) {
			$( '#' + source.id.replace( /^mwProtect-(\w+)-expires$/, 'mwProtectExpirySelection-$1' ) ).val( 'othertime' );
		} else {
			this.getExpirySelectors().each( function () {
				this.value = 'othertime';
			} );
		}
	},

	/**
	 * When protection levels are locked together, update the
	 * expiry lists when one changes and clear the custom inputs
	 *
	 * @param {Element} source Expiry selector that changed
	 */
	updateExpiryList: function ( source ) {
		if ( !this.isUnchained() ) {
			this.getExpirySelectors().each( function () {
				this.value = source.value;
			} );
			this.getExpiryInputs().each( function () {
				this.value = '';
			} );
		}
	},

	/**
	 * Update chain status and enable/disable various bits of the UI
	 * when the user changes the "unlock move permissions" checkbox
	 */
	onChainClick: function () {
		this.toggleUnchainedInputs( this.isUnchained() );
		if ( !this.isUnchained() ) {
			this.setAllSelectors( this.getMaxLevel() );
		}
		this.updateCascadeCheckbox();
	},

	/**
	 * Returns true if the named attribute in all objects in the given array are matching
	 *
	 * @param {Object[]} objects
	 * @param {string} attrName
	 * @return {boolean}
	 */
	matchAttribute: function ( objects, attrName ) {
		return $.map( objects, function ( object ) {
			return object[ attrName ];
		} ).filter( function ( item, index, a ) {
			return index === a.indexOf( item );
		} ).length === 1;
	},

	/**
	 * Are all actions protected at the same level, with the same expiry time?
	 *
	 * @return {boolean}
	 */
	areAllTypesMatching: function () {
		return this.matchAttribute( this.getLevelSelectors(), 'selectedIndex' )
			&& this.matchAttribute( this.getExpirySelectors(), 'selectedIndex' )
			&& this.matchAttribute( this.getExpiryInputs(), 'value' );
	},

	/**
	 * Is protection chaining off?
	 *
	 * @return {boolean}
	 */
	isUnchained: function () {
		var element = document.getElementById( 'mwProtectUnchained' );
		return element
			? element.checked
			: true; // No control, so we need to let the user set both levels
	},

	/**
	 * Find the highest protection level in any selector
	 *
	 * @return {number}
	 */
	getMaxLevel: function () {
		return Math.max.apply( Math, this.getLevelSelectors().map( function () {
			return this.selectedIndex;
		} ) );
	},

	/**
	 * Protect all actions at the specified level
	 *
	 * @param {number} index Protection level
	 */
	setAllSelectors: function ( index ) {
		this.getLevelSelectors().each( function () {
			this.selectedIndex = index;
		} );
	},

	/**
	 * Get a list of all protection selectors on the page
	 *
	 * @return {jQuery}
	 */
	getLevelSelectors: function () {
		return $( 'select[id ^= mwProtect-level-]' );
	},

	/**
	 * Get a list of all expiry inputs on the page
	 *
	 * @return {jQuery}
	 */
	getExpiryInputs: function () {
		return $( 'input[id ^= mwProtect-][id $= -expires]' );
	},

	/**
	 * Get a list of all expiry selector lists on the page
	 *
	 * @return {jQuery}
	 */
	getExpirySelectors: function () {
		return $( 'select[id ^= mwProtectExpirySelection-]' );
	},

	/**
	 * Enable/disable protection selectors and expiry inputs
	 *
	 * @param {boolean} val Enable?
	 */
	toggleUnchainedInputs: function ( val ) {
		var setDisabled = function () { this.disabled = !val; };
		this.getLevelSelectors().slice( 1 ).each( setDisabled );
		this.getExpiryInputs().slice( 1 ).each( setDisabled );
		this.getExpirySelectors().slice( 1 ).each( setDisabled );
	}
};

$( ProtectionForm.init.bind( ProtectionForm ) );

}( mediaWiki, jQuery ) );
