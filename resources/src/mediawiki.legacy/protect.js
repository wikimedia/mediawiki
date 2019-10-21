( function () {
	var config = require( './config.json' ),
		reasonCodePointLimit = mw.config.get( 'wgCommentCodePointLimit' ),
		reasonByteLimit = mw.config.get( 'wgCommentByteLimit' );

	/**
	 * Get a list of all protection selectors on the page
	 *
	 * @return {jQuery}
	 */
	function getLevelSelectors() {
		return $( 'select[id ^= mwProtect-level-]' );
	}

	/**
	 * Get a list of all expiry inputs on the page
	 *
	 * @return {jQuery}
	 */
	function getExpiryInputs() {
		return $( 'input[id ^= mwProtect-][id $= -expires]' );
	}

	/**
	 * Get a list of all expiry selector lists on the page
	 *
	 * @return {jQuery}
	 */
	function getExpirySelectors() {
		return $( 'select[id ^= mwProtectExpirySelection-]' );
	}

	/**
	 * Enable/disable protection selectors and expiry inputs
	 *
	 * @param {boolean} val Enable?
	 */
	function toggleUnchainedInputs( val ) {
		var setDisabled = function () {
			this.disabled = !val;
		};
		getLevelSelectors().slice( 1 ).each( setDisabled );
		getExpiryInputs().slice( 1 ).each( setDisabled );
		getExpirySelectors().slice( 1 ).each( setDisabled );
	}

	/**
	 * Checks if a certain protection level is cascadeable.
	 *
	 * @param {string} level
	 * @return {boolean}
	 */
	function isCascadeableLevel( level ) {
		return config.CascadingRestrictionLevels.indexOf( level ) !== -1;
	}

	/**
	 * Sets the disabled attribute on the cascade checkbox depending on the current selected levels
	 */
	function updateCascadeCheckbox() {
		getLevelSelectors().each( function () {
			if ( !isCascadeableLevel( $( this ).val() ) ) {
				$( '#mwProtect-cascade' ).prop( { checked: false, disabled: true } );
				return false;
			} else {
				$( '#mwProtect-cascade' ).prop( 'disabled', false );
			}
		} );
	}

	/**
	 * Returns true if the named attribute in all objects in the given array are matching
	 *
	 * @param {Object[]} objects
	 * @param {string} attrName
	 * @return {boolean}
	 */
	function matchAttribute( objects, attrName ) {
		// eslint-disable-next-line no-jquery/no-map-util
		return $.map( objects, function ( object ) {
			return object[ attrName ];
		} ).filter( function ( item, index, a ) {
			return index === a.indexOf( item );
		} ).length === 1;
	}

	/**
	 * Are all actions protected at the same level, with the same expiry time?
	 *
	 * @return {boolean}
	 */
	function areAllTypesMatching() {
		return matchAttribute( getLevelSelectors(), 'selectedIndex' ) &&
			matchAttribute( getExpirySelectors(), 'selectedIndex' ) &&
			matchAttribute( getExpiryInputs(), 'value' );
	}

	/**
	 * Is protection chaining off?
	 *
	 * @return {boolean}
	 */
	function isUnchained() {
		var element = document.getElementById( 'mwProtectUnchained' );
		return element ?
			element.checked :
			true; // No control, so we need to let the user set both levels
	}

	/**
	 * Find the highest protection level in any selector
	 *
	 * @return {number}
	 */
	function getMaxLevel() {
		return Math.max.apply( Math, getLevelSelectors().map( function () {
			return this.selectedIndex;
		} ) );
	}

	/**
	 * Protect all actions at the specified level
	 *
	 * @param {number} index Protection level
	 */
	function setAllSelectors( index ) {
		getLevelSelectors().prop( 'selectedIndex', index );
	}

	/**
	 * When protection levels are locked together, update the rest
	 * when one action's level changes
	 *
	 * @param {Event} event Level selector that changed
	 */
	function updateLevels( event ) {
		if ( !isUnchained() ) {
			setAllSelectors( event.target.selectedIndex );
		}
		updateCascadeCheckbox();
	}

	/**
	 * When protection levels are locked together, update the
	 * expiries when one changes
	 *
	 * @param {Event} event Expiry input that changed
	 */
	function updateExpiry( event ) {
		if ( !isUnchained() ) {
			getExpiryInputs().val( event.target.value );
		}
		if ( isUnchained() ) {
			$( '#' + event.target.id.replace( /^mwProtect-(\w+)-expires$/, 'mwProtectExpirySelection-$1' ) ).val( 'othertime' );
		} else {
			getExpirySelectors().val( 'othertime' );
		}
	}

	/**
	 * When protection levels are locked together, update the
	 * expiry lists when one changes and clear the custom inputs
	 *
	 * @param {Event} event Expiry selector that changed
	 */
	function updateExpiryList( event ) {
		if ( !isUnchained() ) {
			getExpirySelectors().val( event.target.value );
			getExpiryInputs().val( '' );
		}
	}

	/**
	 * Update chain status and enable/disable various bits of the UI
	 * when the user changes the "unlock move permissions" checkbox
	 */
	function onChainClick() {
		toggleUnchainedInputs( isUnchained() );
		if ( !isUnchained() ) {
			setAllSelectors( getMaxLevel() );
		}
		updateCascadeCheckbox();
	}

	/**
	 * Set up the protection chaining interface (i.e. "unlock move permissions" checkbox)
	 * on the protection form
	 */
	function init() {
		var $cell = $( '<td>' ),
			$row = $( '<tr>' ).append( $cell );

		if ( !$( '#mwProtectSet' ).length ) {
			return;
		}

		$( 'form#mw-Protect-Form' ).on( 'submit', toggleUnchainedInputs.bind( this, true ) );
		getExpirySelectors().on( 'change', updateExpiryList );
		getExpiryInputs().on( 'input change', updateExpiry );
		getLevelSelectors().on( 'change', updateLevels );

		$( '#mwProtectSet > tbody > tr' ).first().after( $row );

		// If there is only one protection type, there is nothing to chain
		if ( $( '[id ^= mw-protect-table-]' ).length > 1 ) {
			$cell.append(
				$( '<input>' )
					.attr( { id: 'mwProtectUnchained', type: 'checkbox' } )
					.on( 'click', onChainClick )
					.prop( 'checked', !areAllTypesMatching() ),
				document.createTextNode( ' ' ),
				$( '<label>' )
					.attr( 'for', 'mwProtectUnchained' )
					.text( mw.msg( 'protect-unchain-permissions' ) )
			);

			toggleUnchainedInputs( !areAllTypesMatching() );
		}

		// Arbitrary 75 to leave some space for the autogenerated null edit's summary
		if ( reasonCodePointLimit ) {
			$( '#mwProtect-reason' ).codePointLimit( reasonCodePointLimit - 75 );
		} else if ( reasonByteLimit ) {
			$( '#mwProtect-reason' ).byteLimit( reasonByteLimit - 75 );
		}

		updateCascadeCheckbox();
	}

	$( init );

}() );
