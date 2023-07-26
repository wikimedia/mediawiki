/*!
 * JavaScript for diff inline toggle
 */
( function () {
	var $wikitextDiffContainer, $wikitextDiffHeader, $wikitextDiffBody,
		$wikitextDiffBodyInline, $wikitextDiffBodyTable,
		url = new URL( location.href ),
		api = new mw.Api(),
		$inlineLegendContainer = $( '.mw-diff-inline-legend' );

	var initDiffType;
	var $inlineButton = $( '#mw-diffPage-inline-button' );
	if ( $inlineButton.length ) {
		initDiffType = OO.ui.ButtonWidget.static.infuse( $inlineButton ).active ? 'inline' : 'table';
	} else {
		initDiffType = 'table';
	}

	var inlineToggleField = new mw.widgets.InlineToggleField( initDiffType ),
		inlineToggleSwitch = inlineToggleField.inlineToggleSwitch;

	$( '.mw-diffPage-inlineToggle-container' ).empty().append( inlineToggleField.$element );
	inlineToggleField.on( 'change', onDiffTypeInlineChange );
	inlineToggleField.on( 'disable', onDiffTypeInlineDisabled );

	$wikitextDiffContainer = $( 'table.diff[data-mw="interface"]' );
	$wikitextDiffHeader = $wikitextDiffContainer.find( 'tr.diff-title' );
	$wikitextDiffBody = $wikitextDiffContainer.find( 'tr' ).not( $wikitextDiffHeader );

	if ( inlineToggleSwitch.getValue() ) {
		$wikitextDiffBodyInline = $wikitextDiffBody;
		$wikitextDiffBody.first( 'tr' ).addClass( 'inline-diff-row' );
	} else {
		$wikitextDiffBodyTable = $wikitextDiffBody;
	}

	/**
	 * Toggle the DOM containers according to the format selected.
	 *
	 * @param {boolean} isInline
	 */
	function onDiffTypeInlineChange( isInline ) {
		$inlineLegendContainer.toggleClass( 'oo-ui-element-hidden', !isInline );
		if ( typeof $wikitextDiffBodyInline !== 'undefined' ) {
			$wikitextDiffBodyInline.toggleClass( 'oo-ui-element-hidden', !isInline );
		}

		if ( typeof $wikitextDiffBodyTable !== 'undefined' ) {
			$wikitextDiffBodyTable.toggleClass( 'oo-ui-element-hidden', isInline );
		}

		if ( ( isInline && typeof $wikitextDiffBodyInline === 'undefined' ) ||
			( !isInline && typeof $wikitextDiffBodyTable === 'undefined' ) ) {
			fetchDiff( isInline );
		}

		api.saveOption( 'diff-type', isInline ? 'inline' : 'table' )
			.fail( function ( error ) {
				if ( error === 'notloggedin' ) {
					// Can't save preference, so use query parameter stickiness
					switchQueryParams( isInline );
				}
			} );
	}

	/**
	 * Change the query parameters to maintain the new diff type.
	 * This is used for anonymous users.
	 *
	 * @param {boolean} isInline
	 */
	function switchQueryParams( isInline ) {
		$( '#differences-prevlink, #differences-nextlink' )
			.each( function () {
				var linkUrl;
				try {
					linkUrl = new URL( this.href );
				} catch ( e ) {
					return;
				}
				if ( isInline ) {
					linkUrl.searchParams.set( 'diff-type', 'inline' );
				} else {
					linkUrl.searchParams.delete( 'diff-type' );
				}
				this.href = linkUrl.toString();
			} );
	}

	/**
	 * Toggle the legend when the toggle switch disabled state changes.
	 *
	 * @param {boolean} disabled
	 */
	function onDiffTypeInlineDisabled( disabled ) {
		if ( disabled ) {
			$inlineLegendContainer.addClass( 'oo-ui-element-hidden' );
		} else {
			$inlineLegendContainer.toggleClass( 'oo-ui-element-hidden', !inlineToggleSwitch.getValue() );
		}
	}

	/**
	 * Fetch the diff through mw.API in the given format.
	 *
	 * @param {boolean} isInline
	 */
	function fetchDiff( isInline ) {
		var apiParams, oldPageName, newPageName,
			diffType = isInline ? 'inline' : 'table',
			oldRevId = mw.config.get( 'wgDiffOldId' ),
			newRevId = mw.config.get( 'wgDiffNewId' );

		if ( mw.config.get( 'wgCanonicalSpecialPageName' ) !== 'ComparePages' ) {
			oldPageName = newPageName = mw.config.get( 'wgRelevantPageName' );
		} else {
			oldPageName = url.searchParams.get( 'page1' );
			newPageName = url.searchParams.get( 'page2' );
		}

		apiParams = {
			action: 'compare',
			fromtitle: oldPageName,
			totitle: newPageName,
			fromrev: oldRevId,
			torev: newRevId,
			difftype: diffType
		};

		api.get( apiParams ).done( function ( diffData ) {
			if ( isInline ) {
				$wikitextDiffBodyInline = $( diffData.compare[ '*' ] );
				$wikitextDiffBodyInline.first( 'tr' ).addClass( 'inline-diff-row' );
			} else {
				$wikitextDiffBodyTable = $( diffData.compare[ '*' ] );
			}
			$wikitextDiffBody.last().after( isInline ? $wikitextDiffBodyInline : $wikitextDiffBodyTable );
			$wikitextDiffBody = $wikitextDiffContainer.find( 'tr' ).not( $wikitextDiffHeader );
			/**
			 * Fired when the wikitext DOM is updated so others can react accordingly.
			 *
			 * @event wikipage_diff_wikitextDiffBody
			 * @member mw.hook
			 * @param {jQuery} $wikitextDiffBody
			 */
			mw.hook( 'wikipage.diff.wikitextBodyUpdate' ).fire( $wikitextDiffBody );
		} );
	}

	/**
	 * Fired when the diff type switch is present so others can decide
	 * how to manipulate the DOM
	 *
	 * @event wikipage_diff_diffTypeSwitch
	 * @member mw.hook
	 * @param {OO.ui.ToggleSwitchWidget} inlineToggleSwitch
	 */
	mw.hook( 'wikipage.diff.diffTypeSwitch' ).fire( inlineToggleSwitch );
}() );
