/*!
 * JavaScript for the mediawiki.action.protect module
 */
( function () {
	var cascadeableLevels = require( './config.json' ).CascadingRestrictionLevels,
		cascadeCheckbox = $( '#mwProtect-cascade' ).length ?
			OO.ui.CheckboxInputWidget.static.infuse( $( '#mwProtect-cascade' ) ) :
			false,
		mwProtectUnchained = new OO.ui.CheckboxInputWidget( {
			selected: false,
			id: 'mwProtectUnchained'
		} ),
		levelSelectors = $( '[id ^= mwProtect-level-]' ).map( function () {
			return OO.ui.infuse( this );
		} ).toArray(),
		expiryInputs = $( '[id ^= mwProtect-][id $= -expires]' ).map( function () {
			return OO.ui.infuse( this );
		} ).toArray(),
		expirySelectors = $( '[id ^= mwProtectExpirySelection-]' ).map( function () {
			return OO.ui.infuse( this );
		} ).toArray(),
		chainedInputs = [ levelSelectors, expirySelectors, expiryInputs ];

	/**
	 * Enable/disable protection selectors and expiry inputs
	 *
	 * @ignore
	 * @param {boolean} enable
	 */
	function toggleUnchainedInputs( enable ) {
		chainedInputs.forEach( function ( widgets ) {
			widgets.slice( 1 ).forEach( function ( widget ) {
				widget.setDisabled( !enable );
			} );
		} );
	}

	/**
	 * Are all actions protected at the same level, with the same expiry time?
	 *
	 * @ignore
	 * @return {boolean}
	 */
	function areAllTypesMatching() {
		return chainedInputs.every( function ( widgets ) {
			return widgets.every( function ( widget ) {
				return widget.getValue() === widgets[ 0 ].getValue();
			} );
		} );
	}

	/**
	 * Is protection chaining off?
	 *
	 * @ignore
	 * @return {boolean}
	 */
	function isUnchained() {
		return mwProtectUnchained.isElementAttached() ? mwProtectUnchained.isSelected() : true;
	}

	/**
	 * Find the highest protection level in any selector
	 *
	 * @ignore
	 * @return {number}
	 */
	function getMaxLevel() {
		return Math.max.apply( Math, levelSelectors.map( function ( widget ) {
			return widget.dropdownWidget.getMenu().getItems().map( function ( item ) {
				return item.selected;
			} ).indexOf( true );
		} ) );
	}

	/**
	 * Set all protection level selectors to the same protection level
	 *
	 * @ignore
	 * @param {number|string} val Protection level index or value
	 */
	function setAllSelectors( val ) {
		levelSelectors.forEach( function ( widget ) {
			if ( typeof val === 'number' ) {
				widget.setValue( widget.dropdownWidget.getMenu().getItems()[ val ].getData() );
			} else {
				widget.setValue( val );
			}
		} );
	}

	/**
	 * Set the value of all widgets to the value of the first widget
	 *
	 * @ignore
	 * @param {OO.ui.Widget[]} widgets Array of widgets
	 */
	function setAllToFirst( widgets ) {
		widgets.slice( 1 ).forEach( function ( widget ) {
			widget.setValue( widgets[ 0 ].getValue() );
		} );
	}

	/**
	 * Enables or disables the cascade checkbox depending on the current selected levels
	 * Disables expiry inputs when there is not protection
	 *
	 * @ignore
	 * @return {boolean}
	 */
	function updateCascadeAndExpire() {
		levelSelectors.forEach( function ( val, index ) {
			var disable = !val.getValue() || index && !isUnchained();
			expirySelectors[ index ].setDisabled( disable );
			expiryInputs[ index ].setDisabled( disable );
		} );
		if ( cascadeCheckbox ) {
			levelSelectors.some( function ( widget ) {
				if ( cascadeableLevels.indexOf( widget.getValue() ) === -1 ) {
					cascadeCheckbox.setSelected( false ).setDisabled( true );
					return true;
				}
				cascadeCheckbox.setDisabled( false );
				return false;
			} );
		}
	}

	// Enable on inputs on submit
	$( '#mw-Protect-Form' ).on( 'submit', function () {
		chainedInputs.forEach( function ( widgets ) {
			widgets.forEach( function ( widget ) {
				widget.setDisabled( false );
			} );
		} );
	} );

	// Change value of chained selectors and expiry inputs
	expirySelectors.forEach( function ( widget ) {
		widget.on( 'change', function ( val ) {
			if ( isUnchained() ) {
				if ( val !== 'othertime' ) {
					expiryInputs[ expirySelectors.indexOf( widget ) ].setValue( '' );
				}
			} else {
				setAllToFirst( expirySelectors );
				if ( val !== 'othertime' ) {
					expiryInputs[ 0 ].setValue( '' );
					setAllToFirst( expiryInputs );
				}
			}
		} );
	} );

	// Change value of chained inputs and expiry selectors
	expiryInputs.forEach( function ( widget ) {
		widget.on( 'change', function ( val ) {
			if ( isUnchained() ) {
				if ( val ) {
					expirySelectors[ expiryInputs.indexOf( widget ) ].setValue( 'othertime' );
				}
			} else {
				setAllToFirst( expiryInputs );
				if ( val ) {
					expirySelectors[ 0 ].setValue( 'othertime' );
					setAllToFirst( expirySelectors );
				}
			}
		} );
	} );

	// Change value of chained level selectors and update cascade checkbox
	levelSelectors.forEach( function ( widget ) {
		widget.on( 'change', function ( val ) {
			if ( !isUnchained() ) {
				setAllSelectors( val );
			}
			updateCascadeAndExpire();
		} );
	} );

	// If there is only one protection type, there is nothing to chain
	if ( $( '.oo-ui-panelLayout-framed .oo-ui-panelLayout-framed' ).length > 1 ) {
		mwProtectUnchained.on( 'change', function () {
			toggleUnchainedInputs( isUnchained() );
			if ( !isUnchained() ) {
				setAllSelectors( getMaxLevel() );
				setAllToFirst( expirySelectors );
				setAllToFirst( expiryInputs );
			}
			updateCascadeAndExpire();
		} ).setSelected( !areAllTypesMatching() );
		$( '.oo-ui-panelLayout-framed .oo-ui-panelLayout-framed' ).first().after(
			( new OO.ui.FieldLayout( mwProtectUnchained, {
				label: mw.msg( 'protect-unchain-permissions' ),
				align: 'inline'
			} ) ).$element
		);
		toggleUnchainedInputs( !areAllTypesMatching() );
	}

	// Arbitrary 75 to leave some space for the autogenerated null edit's summary
	$( '#mwProtect-reason input' ).codePointLimit( mw.config.get( 'wgCommentCodePointLimit' ) - 75 );

	updateCascadeAndExpire();
}() );
