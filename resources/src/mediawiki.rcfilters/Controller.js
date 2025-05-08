const byteLength = require( 'mediawiki.String' ).byteLength,
	UriProcessor = require( './UriProcessor.js' );

/* eslint no-underscore-dangle: "off" */
/**
 * Controller for the filters in Recent Changes.
 *
 * @class Controller
 * @memberof mw.rcfilters
 * @ignore
 * @param {mw.rcfilters.dm.FiltersViewModel} filtersModel Filters view model
 * @param {mw.rcfilters.dm.ChangesListViewModel} changesListModel Changes list view model
 * @param {mw.rcfilters.dm.SavedQueriesModel} savedQueriesModel Saved queries model
 * @param {Object} config Additional configuration
 * @param {string} config.savedQueriesPreferenceName Where to save the saved queries
 * @param {string} config.daysPreferenceName Preference name for the days filter
 * @param {string} config.limitPreferenceName Preference name for the limit filter
 * @param {string} config.collapsedPreferenceName Preference name for collapsing and showing
 *  the active filters area
 * @param {boolean} [config.normalizeTarget] Dictates whether or not to go through the
 *  title normalization to separate title subpage/parts into the target= url
 *  parameter
 */
const Controller = function MwRcfiltersController( filtersModel, changesListModel, savedQueriesModel, config ) {
	this.filtersModel = filtersModel;
	this.changesListModel = changesListModel;
	this.savedQueriesModel = savedQueriesModel;
	this.savedQueriesPreferenceName = config.savedQueriesPreferenceName;
	this.daysPreferenceName = config.daysPreferenceName;
	this.limitPreferenceName = config.limitPreferenceName;
	this.collapsedPreferenceName = config.collapsedPreferenceName;
	this.normalizeTarget = !!config.normalizeTarget;

	// TODO merge dmConfig.json and config.json virtual files, see T256836
	this.pollingRate = require( './dmConfig.json' ).StructuredChangeFiltersLiveUpdatePollingRate;

	this.requestCounter = {};
	this.uriProcessor = null;
	this.initialized = false;
	this.wereSavedQueriesSaved = false;

	this.prevLoggedItems = [];

	this.FILTER_CHANGE = 'filterChange';
	this.SHOW_NEW_CHANGES = 'showNewChanges';
	this.LIVE_UPDATE = 'liveUpdate';
};

/* Initialization */
OO.initClass( Controller );

/**
 * Initialize the filter and parameter states
 *
 * @param {Array} filterStructure Filter definition and structure for the model
 * @param {Object} namespaceStructure Namespace definition
 * @param {Object} tagList Tag definition
 * @param {Object} [conditionalViews] Conditional view definition
 */
Controller.prototype.initialize = function ( filterStructure, namespaceStructure, tagList, conditionalViews ) {
	const displayConfig = mw.config.get( 'StructuredChangeFiltersDisplayConfig' ),
		defaultSavedQueryExists = mw.config.get( 'wgStructuredChangeFiltersDefaultSavedQueryExists' ),
		views = $.extend( true, {}, conditionalViews ),
		url = new URL( location.href );

	// Prepare views
	const nsAllContents = {
		name: 'all-contents',
		label: mw.msg( 'rcfilters-allcontents-label' ),
		description: '',
		identifiers: [ 'subject' ],
		cssClass: 'mw-changeslist-ns-subject',
		subset: []
	};
	const nsAllDiscussions = {
		name: 'all-discussions',
		label: mw.msg( 'rcfilters-alldiscussions-label' ),
		description: '',
		identifiers: [ 'talk' ],
		cssClass: 'mw-changeslist-ns-talk',
		subset: []
	};
	const items = [ nsAllContents, nsAllDiscussions ];
	for ( const namespaceID in namespaceStructure ) {
		const label = namespaceStructure[ namespaceID ];
		// Build and clean up the individual namespace items definition
		const isTalk = mw.Title.isTalkNamespace( namespaceID ),
			nsFilter = {
				name: namespaceID,
				label: label || mw.msg( 'blanknamespace' ),
				description: '',
				identifiers: [
					isTalk ? 'talk' : 'subject'
				],
				cssClass: 'mw-changeslist-ns-' + namespaceID
			};
		items.push( nsFilter );
		( isTalk ? nsAllDiscussions : nsAllContents ).subset.push( { filter: namespaceID } );
	}

	views.namespaces = {
		title: mw.msg( 'namespaces' ),
		trigger: ':',
		groups: [ {
			// Group definition (single group)
			name: 'namespace', // parameter name is singular
			type: 'string_options',
			title: mw.msg( 'namespaces' ),
			labelPrefixKey: {
				default: 'rcfilters-tag-prefix-namespace',
				inverted: 'rcfilters-tag-prefix-namespace-inverted'
			},
			separator: ';',
			supportsAll: false,
			fullCoverage: true,
			filters: items
		} ]
	};
	views.invertNamespaces = {
		groups: [
			{
				// Should really be called invertNamespacesGroup; legacy name is used so that
				// saved queries don't break
				name: 'invertGroup',
				type: 'boolean',
				hidden: true,
				filters: [ {
					name: 'invert',
					default: '0'
				} ]
			} ]
	};

	views.tags = {
		title: mw.msg( 'rcfilters-view-tags' ),
		trigger: '#',
		groups: [ {
			// Group definition (single group)
			name: 'tagfilter', // Parameter name
			type: 'string_options',
			title: 'rcfilters-view-tags', // Message key
			labelPrefixKey: {
				default: 'rcfilters-tag-prefix-tags',
				inverted: 'rcfilters-tag-prefix-tags-inverted'
			},
			separator: '|',
			supportsAll: false,
			fullCoverage: false,
			filters: tagList
		} ]
	};
	views.invertTags = {
		groups: [
			{
				name: 'invertTagsGroup',
				type: 'boolean',
				hidden: true,
				filters: [ {
					name: 'inverttags',
					default: '0'
				} ]
			} ]
	};

	// Add parameter range operations
	views.range = {
		groups: [
			{
				name: 'limit',
				type: 'single_option',
				title: '', // Because it's a hidden group, this title actually appears nowhere
				hidden: true,
				allowArbitrary: true,
				// FIXME: $.isNumeric is deprecated
				validate: $.isNumeric,
				range: {
					min: 0, // The server normalizes negative numbers to 0 results
					max: 1000
				},
				sortFunc: function ( a, b ) {
					return Number( a.name ) - Number( b.name );
				},
				default: mw.user.options.get( this.limitPreferenceName, displayConfig.limitDefault ),
				sticky: true,
				filters: displayConfig.limitArray.map( ( num ) => this._createFilterDataFromNumber( num, num ) )
			},
			{
				name: 'days',
				type: 'single_option',
				title: '', // Because it's a hidden group, this title actually appears nowhere
				hidden: true,
				allowArbitrary: true,
				// FIXME: $.isNumeric is deprecated
				validate: $.isNumeric,
				range: {
					min: 0,
					max: displayConfig.maxDays
				},
				sortFunc: function ( a, b ) {
					return Number( a.name ) - Number( b.name );
				},
				numToLabelFunc: function ( i ) {
					return Number( i ) < 1 ?
						( Number( i ) * 24 ).toFixed( 2 ) :
						Number( i );
				},
				default: mw.user.options.get( this.daysPreferenceName, displayConfig.daysDefault ),
				sticky: true,
				filters: [
					// Hours (1, 2, 6, 12)
					0.04166, 0.0833, 0.25, 0.5
				// Days
				].concat( displayConfig.daysArray )
					.map( ( num ) => this._createFilterDataFromNumber(
						num,
						// Convert fractions of days to number of hours for the labels
						num < 1 ? Math.round( num * 24 ) : num
					) )
			}
		]
	};

	views.display = {
		groups: [
			{
				name: 'display',
				type: 'boolean',
				title: '', // Because it's a hidden group, this title actually appears nowhere
				hidden: true,
				sticky: true,
				filters: [
					{
						name: 'enhanced',
						default: String( mw.user.options.get( 'usenewrc', 0 ) )
					}
				]
			}
		]
	};

	// Before we do anything, we need to see if we require additional items in the
	// groups that have 'AllowArbitrary'. For the moment, those are only single_option
	// groups; if we ever expand it, this might need further generalization:
	for ( const viewName in views ) {
		const viewData = views[ viewName ];
		viewData.groups.forEach( ( groupData ) => {
			const extraValues = [];
			if ( groupData.allowArbitrary ) {
				// If the value in the URL isn't in the group, add it
				if ( url.searchParams.get( groupData.name ) !== null ) {
					extraValues.push( url.searchParams.get( groupData.name ) );
				}
				// If the default value isn't in the group, add it
				if ( groupData.default !== undefined ) {
					extraValues.push( String( groupData.default ) );
				}
				this.addNumberValuesToGroup( groupData, extraValues );
			}
		} );
	}

	// Initialize the model
	this.filtersModel.initializeFilters( filterStructure, views );

	this.uriProcessor = new UriProcessor(
		this.filtersModel,
		{ normalizeTarget: this.normalizeTarget }
	);

	let parsedSavedQueries;
	if ( !mw.user.isAnon() ) {
		try {
			parsedSavedQueries = JSON.parse( mw.user.options.get( this.savedQueriesPreferenceName ) || '{}' );
		} catch ( err ) {
			parsedSavedQueries = {};
		}

		// Initialize saved queries
		this.savedQueriesModel.initialize( parsedSavedQueries );
		if ( this.savedQueriesModel.isConverted() ) {
			// Since we know we converted, we're going to re-save
			// the queries so they are now migrated to the new format
			this._saveSavedQueries();
		}
	}

	if ( defaultSavedQueryExists ) {
		// This came from the server, meaning that we have a default
		// saved query, but the server could not load it, probably because
		// it was pre-conversion to the new format.
		// We need to load this query again
		this.applySavedQuery( this.savedQueriesModel.getDefault() );
	} else {
		// There are either recognized parameters in the URL
		// or there are none, but there is also no default
		// saved query (so defaults are from the backend)
		// We want to update the state but not fetch results
		// again
		this.updateStateFromUrl( false );

		const pieces = this._extractChangesListInfo( $( '#mw-content-text' ) );

		// Update the changes list with the existing data
		// so it gets processed
		this.changesListModel.update(
			pieces.changes,
			pieces.fieldset,
			pieces.noResultsDetails,
			true // We're using existing DOM elements
		);
	}

	this.initialized = true;
	this.switchView( 'default' );

	if ( this.pollingRate ) {
		this._scheduleLiveUpdate();
	}
};

/**
 * Check if the controller has finished initializing.
 *
 * @return {boolean} Controller is initialized
 */
Controller.prototype.isInitialized = function () {
	return this.initialized;
};

/**
 * Extracts information from the changes list DOM
 *
 * @param {jQuery} $root Root DOM to find children from
 * @param {number} [statusCode] Server response status code
 * @return {Object} Information about changes list
 * @return {Object|string} return.changes Changes list, or 'NO_RESULTS' if there are no results
 *   (either normally or as an error)
 * @return {string} [return.noResultsDetails] 'NO_RESULTS_NORMAL' for a normal 0-result set,
 *   'NO_RESULTS_TIMEOUT' for no results due to a timeout, or omitted for more than 0 results
 * @return {jQuery} return.fieldset Fieldset
 */
Controller.prototype._extractChangesListInfo = function ( $root, statusCode ) {
	const $changesListContents = $root.find( '.mw-changeslist' ).first().contents(),
		areResults = !!$changesListContents.length,
		checkForLogout = !areResults && statusCode === 200;

	// We check if user logged out on different tab/browser or the session has expired.
	// 205 status code returned from the server, which indicates that we need to reload the page
	// is not usable on WL page, because we get redirected to login page, which gives 200 OK
	// status code (if everything else goes well).
	// Bug: T177717
	if ( checkForLogout && !!$root.find( '#wpName1' ).length ) {
		location.reload( false );
		return;
	}

	const info = {
		changes: $changesListContents.length ? $changesListContents : 'NO_RESULTS',
		fieldset: $root.find( 'fieldset.cloptions' ).first()
	};

	if ( !areResults ) {
		if ( $root.find( '.mw-changeslist-timeout' ).length ) {
			info.noResultsDetails = 'NO_RESULTS_TIMEOUT';
		} else if ( $root.find( '.mw-changeslist-notargetpage' ).length ) {
			info.noResultsDetails = 'NO_RESULTS_NO_TARGET_PAGE';
		} else if ( $root.find( '.mw-changeslist-invalidtargetpage' ).length ) {
			info.noResultsDetails = 'NO_RESULTS_INVALID_TARGET_PAGE';
		} else {
			info.noResultsDetails = 'NO_RESULTS_NORMAL';
		}
	}

	return info;
};

/**
 * Create filter data from a number, for the filters that are numerical value
 *
 * @param {number} num Number
 * @param {number} numForDisplay Number for the label
 * @return {Object} Filter data
 */
Controller.prototype._createFilterDataFromNumber = function ( num, numForDisplay ) {
	return {
		name: String( num ),
		label: mw.language.convertNumber( numForDisplay )
	};
};

/**
 * Add an arbitrary values to groups that allow arbitrary values
 *
 * @param {Object} groupData Group data
 * @param {string|string[]} arbitraryValues An array of arbitrary values to add to the group
 */
Controller.prototype.addNumberValuesToGroup = function ( groupData, arbitraryValues ) {
	const normalizeWithinRange = function ( range, val ) {
		if ( val < range.min ) {
			return range.min; // Min
		} else if ( val >= range.max ) {
			return range.max; // Max
		}
		return val;
	};

	arbitraryValues = Array.isArray( arbitraryValues ) ? arbitraryValues : [ arbitraryValues ];

	// Normalize the arbitrary values and the default value for a range
	if ( groupData.range ) {
		arbitraryValues = arbitraryValues.map( ( val ) => normalizeWithinRange( groupData.range, val ) );

		// Normalize the default, since that's user defined
		if ( groupData.default !== undefined ) {
			groupData.default = String( normalizeWithinRange( groupData.range, groupData.default ) );
		}
	}

	// This is only true for single_option group
	// We assume these are the only groups that will allow for
	// arbitrary, since it doesn't make any sense for the other
	// groups.
	arbitraryValues.forEach( ( val ) => {
		if (
			// If the group allows for arbitrary data
			groupData.allowArbitrary &&
			// and it is single_option (or string_options, but we
			// don't have cases of those yet, nor do we plan to)
			groupData.type === 'single_option' &&
			// and, if there is a validate method and it passes on
			// the data
			( !groupData.validate || groupData.validate( val ) ) &&
			// but if that value isn't already in the definition
			!groupData.filters
				.map( ( filterData ) => String( filterData.name ) )
				.includes( String( val ) )
		) {
			// Add the filter information
			groupData.filters.push( this._createFilterDataFromNumber(
				val,
				groupData.numToLabelFunc ?
					groupData.numToLabelFunc( val ) :
					val
			) );

			// If there's a sort function set up, re-sort the values
			if ( groupData.sortFunc ) {
				groupData.filters.sort( groupData.sortFunc );
			}
		}
	} );
};

/**
 * Reset to default filters
 */
Controller.prototype.resetToDefaults = function () {
	const params = this._getDefaultParams();
	if ( this.applyParamChange( params ) ) {
		// Only update the changes list if there was a change to actual filters
		this.updateChangesList();
	} else {
		this.uriProcessor.updateURL( params );
	}
};

/**
 * Check whether the default values of the filters are all false.
 *
 * @return {boolean} Defaults are all false
 */
Controller.prototype.areDefaultsEmpty = function () {
	return $.isEmptyObject( this._getDefaultParams() );
};

/**
 * Empty all selected filters
 */
Controller.prototype.emptyFilters = function () {
	if ( this.applyParamChange( {} ) ) {
		// Only update the changes list if there was a change to actual filters
		this.updateChangesList();
	} else {
		this.uriProcessor.updateURL();
	}
};

/**
 * Update the selected state of a filter
 *
 * @param {string} filterName Filter name
 * @param {boolean} [isSelected] Filter selected state
 */
Controller.prototype.toggleFilterSelect = function ( filterName, isSelected ) {
	const filterItem = this.filtersModel.getItemByName( filterName );

	if ( !filterItem ) {
		// If no filter was found, break
		return;
	}

	isSelected = isSelected === undefined ? !filterItem.isSelected() : isSelected;

	if ( filterItem.isSelected() !== isSelected ) {
		this.filtersModel.toggleFilterSelected( filterName, isSelected );

		this.updateChangesList();

		// Check filter interactions
		this.filtersModel.reassessFilterInteractions( filterItem );
	}
};

/**
 * Clear both highlight and selection of a filter
 *
 * @param {string} filterName Name of the filter item
 */
Controller.prototype.clearFilter = function ( filterName ) {
	const filterItem = this.filtersModel.getItemByName( filterName ),
		isHighlighted = filterItem.isHighlighted(),
		isSelected = filterItem.isSelected();

	if ( isSelected || isHighlighted ) {
		this.filtersModel.clearHighlightColor( filterName );
		this.filtersModel.toggleFilterSelected( filterName, false );

		if ( isSelected ) {
			// Only update the changes list if the filter changed
			// its selection state. If it only changed its highlight
			// then don't reload
			this.updateChangesList();
		}

		this.filtersModel.reassessFilterInteractions( filterItem );
	}
};

/**
 * Toggle the highlight feature on and off
 */
Controller.prototype.toggleHighlight = function () {
	this.filtersModel.toggleHighlight();
	this.uriProcessor.updateURL();

	if ( this.filtersModel.isHighlightEnabled() ) {
		/**
		 * Fires when highlight feature is enabled.
		 *
		 * @event ~'RcFilters.highlight.enable'
		 * @memberof Hooks
		 */
		mw.hook( 'RcFilters.highlight.enable' ).fire();
	}
};

/**
 * Toggle the inverted tags feature on and off
 */
Controller.prototype.toggleInvertedTags = function () {
	this.filtersModel.toggleInvertedTags();

	if (
		this.filtersModel.getFiltersByView( 'tags' ).filter(
			( filterItem ) => filterItem.isSelected()
		).length
	) {
		// Only re-fetch results if there are tags items that are actually selected
		this.updateChangesList();
	} else {
		this.uriProcessor.updateURL();
	}
};

/**
 * Toggle the inverted namespaces feature on and off
 */
Controller.prototype.toggleInvertedNamespaces = function () {
	this.filtersModel.toggleInvertedNamespaces();

	if (
		this.filtersModel.getFiltersByView( 'namespaces' ).filter(
			( filterItem ) => filterItem.isSelected()
		).length
	) {
		// Only re-fetch results if there are namespace items that are actually selected
		this.updateChangesList();
	} else {
		this.uriProcessor.updateURL();
	}
};

/**
 * Set the value of the 'showlinkedto' parameter
 *
 * @param {boolean} value
 */
Controller.prototype.setShowLinkedTo = function ( value ) {
	const targetItem = this.filtersModel.getGroup( 'page' ).getItemByParamName( 'target' ),
		showLinkedToItem = this.filtersModel.getGroup( 'toOrFrom' ).getItemByParamName( 'showlinkedto' );

	this.filtersModel.toggleFilterSelected( showLinkedToItem.getName(), value );
	this.uriProcessor.updateURL();
	// reload the results only when target is set
	if ( targetItem.getValue() ) {
		this.updateChangesList();
	}
};

/**
 * Set the target page
 *
 * @param {string} page
 */
Controller.prototype.setTargetPage = function ( page ) {
	const targetItem = this.filtersModel.getGroup( 'page' ).getItemByParamName( 'target' );
	targetItem.setValue( page );
	this.uriProcessor.updateURL();
	this.updateChangesList();
};

/**
 * Set the highlight color for a filter item
 *
 * @param {string} filterName Name of the filter item
 * @param {string} color Selected color
 */
Controller.prototype.setHighlightColor = function ( filterName, color ) {
	this.filtersModel.setHighlightColor( filterName, color );
	this.uriProcessor.updateURL();
};

/**
 * Clear highlight for a filter item
 *
 * @param {string} filterName Name of the filter item
 */
Controller.prototype.clearHighlightColor = function ( filterName ) {
	this.filtersModel.clearHighlightColor( filterName );
	this.uriProcessor.updateURL();
};

/**
 * Enable or disable live updates.
 *
 * @param {boolean} enable True to enable, false to disable
 */
Controller.prototype.toggleLiveUpdate = function ( enable ) {
	this.changesListModel.toggleLiveUpdate( enable );
	if ( this.changesListModel.getLiveUpdate() && this.changesListModel.getNewChangesExist() ) {
		this.updateChangesList( null, this.LIVE_UPDATE );
	}
};

/**
 * Set a timeout for the next live update.
 *
 * @private
 */
Controller.prototype._scheduleLiveUpdate = function () {
	setTimeout( this._doLiveUpdate.bind( this ), this.pollingRate * 1000 );
};

/**
 * Perform a live update.
 *
 * @private
 */
Controller.prototype._doLiveUpdate = function () {
	if ( !this._shouldCheckForNewChanges() ) {
		// skip this turn and check back later
		this._scheduleLiveUpdate();
		return;
	}

	this._checkForNewChanges()
		.then( ( statusCode ) => {
			// no result is 204 with the 'peek' param
			// logged out is 205
			const newChanges = statusCode === 200;

			if ( !this._shouldCheckForNewChanges() ) {
				// by the time the response is received,
				// it may not be appropriate anymore
				return;
			}

			// 205 is the status code returned from server when user's logged in/out
			// status is not matching while fetching live update changes.
			// This works only on Recent Changes page. For WL, look _extractChangesListInfo.
			// Bug: T177717
			if ( statusCode === 205 ) {
				location.reload( false );
				return;
			}

			if ( newChanges ) {
				if ( this.changesListModel.getLiveUpdate() ) {
					return this.updateChangesList( null, this.LIVE_UPDATE );
				} else {
					this.changesListModel.setNewChangesExist( true );
				}
			}
		} )
		.always( this._scheduleLiveUpdate.bind( this ) );
};

/**
 * @return {boolean} It's appropriate to check for new changes now
 * @private
 */
Controller.prototype._shouldCheckForNewChanges = function () {
	return !document.hidden &&
		!this.filtersModel.hasConflict() &&
		!this.changesListModel.getNewChangesExist() &&
		!this.updatingChangesList &&
		this.changesListModel.getNextFrom();
};

/**
 * Check if new changes, newer than those currently shown, are available
 *
 * @return {jQuery.Promise} Promise object that resolves with a bool
 *   specifying if there are new changes or not
 *
 * @private
 */
Controller.prototype._checkForNewChanges = function () {
	const params = {
		limit: 1,
		peek: 1, // bypasses ChangesList specific UI
		from: this.changesListModel.getNextFrom(),
		isAnon: mw.user.isAnon()
	};
	return this._queryChangesList( 'liveUpdate', params ).then(
		( data ) => data.status
	);
};

/**
 * Show the new changes
 *
 * @return {jQuery.Promise} Promise object that resolves after
 * fetching and showing the new changes
 */
Controller.prototype.showNewChanges = function () {
	return this.updateChangesList( null, this.SHOW_NEW_CHANGES );
};

/**
 * Save the current model state as a saved query
 *
 * @param {string} [label] Label of the saved query
 * @param {boolean} [setAsDefault=false] This query should be set as the default
 */
Controller.prototype.saveCurrentQuery = function ( label, setAsDefault ) {
	// Add item
	this.savedQueriesModel.addNewQuery(
		label || mw.msg( 'rcfilters-savedqueries-defaultlabel' ),
		this.filtersModel.getCurrentParameterState( true ),
		setAsDefault
	);

	// Save item
	this._saveSavedQueries();
};

/**
 * Remove a saved query
 *
 * @param {string} queryID Query id
 */
Controller.prototype.removeSavedQuery = function ( queryID ) {
	this.savedQueriesModel.removeQuery( queryID );

	this._saveSavedQueries();
};

/**
 * Rename a saved query
 *
 * @param {string} queryID Query id
 * @param {string} newLabel New label for the query
 */
Controller.prototype.renameSavedQuery = function ( queryID, newLabel ) {
	const queryItem = this.savedQueriesModel.getItemByID( queryID );

	if ( queryItem ) {
		queryItem.updateLabel( newLabel );
	}
	this._saveSavedQueries();
};

/**
 * Set a saved query as default
 *
 * @param {string} queryID Query Id. If null is given, default
 *  query is reset.
 */
Controller.prototype.setDefaultSavedQuery = function ( queryID ) {
	this.savedQueriesModel.setDefault( queryID );
	this._saveSavedQueries();
};

/**
 * Load a saved query
 *
 * @param {string} queryID Query id
 */
Controller.prototype.applySavedQuery = function ( queryID ) {
	const params = this.savedQueriesModel.getItemParams( queryID );

	const currentMatchingQuery = this.findQueryMatchingCurrentState();

	if (
		currentMatchingQuery &&
		currentMatchingQuery.getID() === queryID
	) {
		// If the query we want to load is the one that is already
		// loaded, don't reload it
		return;
	}

	if ( this.applyParamChange( params ) ) {
		// Update changes list only if there was a difference in filter selection
		this.updateChangesList();
	} else {
		this.uriProcessor.updateURL( params );
	}
};

/**
 * Check whether the current filter and highlight state exists
 * in the saved queries model.
 *
 * @ignore
 * @return {mw.rcfilters.dm.SavedQueryItemModel} Matching item model
 */
Controller.prototype.findQueryMatchingCurrentState = function () {
	return this.savedQueriesModel.findMatchingQuery(
		this.filtersModel.getCurrentParameterState( true )
	);
};

/**
 * Save the current state of the saved queries model with all
 * query item representation in the user settings.
 */
Controller.prototype._saveSavedQueries = function () {
	const backupPrefName = this.savedQueriesPreferenceName + '-versionbackup',
		state = this.savedQueriesModel.getState();

	// Stringify state
	const stringified = JSON.stringify( state );

	if ( byteLength( stringified ) > 65535 ) {
		// Double check, since the preference can only hold that.
		return;
	}

	if ( !this.wereSavedQueriesSaved && this.savedQueriesModel.isConverted() ) {
		// The queries were converted from the previous version
		// Keep the old string in the [prefname]-versionbackup
		const oldPrefValue = mw.user.options.get( this.savedQueriesPreferenceName );

		// Save the old preference in the backup preference
		new mw.Api().saveOption( backupPrefName, oldPrefValue );
		// Update the preference for this session
		mw.user.options.set( backupPrefName, oldPrefValue );
	}

	// Save the preference
	new mw.Api().saveOption( this.savedQueriesPreferenceName, stringified );
	// Update the preference for this session
	mw.user.options.set( this.savedQueriesPreferenceName, stringified );

	// Tag as already saved so we don't do this again
	this.wereSavedQueriesSaved = true;
};

/**
 * Update sticky preferences with current model state
 */
Controller.prototype.updateStickyPreferences = function () {
	// Update default sticky values with selected, whether they came from
	// the initial defaults or from the URL value that is being normalized
	this.updateDaysDefault( this.filtersModel.getGroup( 'days' ).findSelectedItems()[ 0 ].getParamName() );
	this.updateLimitDefault( this.filtersModel.getGroup( 'limit' ).findSelectedItems()[ 0 ].getParamName() );

	// TODO: Make these automatic by having the model go over sticky
	// items and update their default values automatically
};

/**
 * Update the limit default value
 *
 * @param {number} newValue New value
 */
Controller.prototype.updateLimitDefault = function ( newValue ) {
	this.updateNumericPreference( this.limitPreferenceName, newValue );
};

/**
 * Update the days default value
 *
 * @param {number} newValue New value
 */
Controller.prototype.updateDaysDefault = function ( newValue ) {
	this.updateNumericPreference( this.daysPreferenceName, newValue );
};

/**
 * Update the group by page default value
 *
 * @param {boolean} newValue New value
 */
Controller.prototype.updateGroupByPageDefault = function ( newValue ) {
	this.updateNumericPreference( 'usenewrc', Number( newValue ) );
};

/**
 * Update the collapsed state value
 *
 * @param {boolean} isCollapsed Filter area is collapsed
 */
Controller.prototype.updateCollapsedState = function ( isCollapsed ) {
	this.updateNumericPreference( this.collapsedPreferenceName, Number( isCollapsed ) );
};

/**
 * Update a numeric preference with a new value
 *
 * @param {string} prefName Preference name
 * @param {number|string} newValue New value
 */
Controller.prototype.updateNumericPreference = function ( prefName, newValue ) {
	// FIXME: $.isNumeric is deprecated
	// eslint-disable-next-line no-jquery/no-is-numeric
	if ( !$.isNumeric( newValue ) ) {
		return;
	}

	if ( String( mw.user.options.get( prefName ) ) !== String( newValue ) ) {
		// Save the preference
		new mw.Api().saveOption( prefName, newValue );
		// Update the preference for this session
		mw.user.options.set( prefName, newValue );
	}
};

/**
 * Synchronize the URL with the current state of the filters
 * without adding a history entry.
 */
Controller.prototype.replaceUrl = function () {
	this.uriProcessor.updateURL();
};

/**
 * Update filter state (selection and highlighting) based
 * on current URL values.
 *
 * @param {boolean} [fetchChangesList=true] Fetch new results into the changes
 *  list based on the updated model.
 */
Controller.prototype.updateStateFromUrl = function ( fetchChangesList ) {
	fetchChangesList = fetchChangesList === undefined ? true : !!fetchChangesList;

	this.uriProcessor.updateModelBasedOnQuery();

	// Update the sticky preferences, in case we received a value
	// from the URL
	this.updateStickyPreferences();

	// Only update and fetch new results if it is requested
	if ( fetchChangesList ) {
		this.updateChangesList();
	}
};

/**
 * Update the list of changes and notify the model
 *
 * @param {Object} [params] Extra parameters to add to the API call
 * @param {string} [updateMode='filterChange'] One of 'filterChange', 'liveUpdate', 'showNewChanges', 'markSeen'
 * @return {jQuery.Promise} Promise that is resolved when the update is complete
 */
Controller.prototype.updateChangesList = function ( params, updateMode ) {
	updateMode = updateMode === undefined ? this.FILTER_CHANGE : updateMode;

	if ( updateMode === this.FILTER_CHANGE ) {
		this.uriProcessor.updateURL( params );
	}
	if ( updateMode === this.FILTER_CHANGE || updateMode === this.SHOW_NEW_CHANGES ) {
		this.changesListModel.invalidate();
	}
	this.changesListModel.setNewChangesExist( false );
	this.updatingChangesList = true;
	return this._fetchChangesList()
		.then(
			// Success
			( pieces ) => {
				const $changesListContent = pieces.changes,
					$fieldset = pieces.fieldset;
				this.changesListModel.update(
					$changesListContent,
					$fieldset,
					pieces.noResultsDetails,
					false,
					// separator between old and new changes
					updateMode === this.SHOW_NEW_CHANGES || updateMode === this.LIVE_UPDATE
				);
			}
			// Do nothing for failure
		)
		.always( () => {
			this.updatingChangesList = false;
		} );
};

/**
 * Get an object representing the default parameter state, whether
 * it is from the model defaults or from the saved queries.
 *
 * @return {Object} Default parameters
 */
Controller.prototype._getDefaultParams = function () {
	if ( this.savedQueriesModel.getDefault() ) {
		return this.savedQueriesModel.getDefaultParams();
	} else {
		return this.filtersModel.getDefaultParams();
	}
};

/**
 * Query the list of changes from the server for the current filters
 *
 * @param {string} counterId Id for this request. To allow concurrent requests
 *  not to invalidate each other.
 * @param {Object} [params={}] Parameters to add to the query
 *
 * @return {jQuery.Promise} Promise object resolved with { content, status }
 */
Controller.prototype._queryChangesList = function ( counterId, params ) {
	const url = this.uriProcessor.getUpdatedUri(),
		stickyParams = this.filtersModel.getStickyParamsValues();

	params = params || {};
	params.action = 'render'; // bypasses MW chrome

	Object.keys( params ).forEach( ( key ) => url.searchParams.set( key, params[ key ] ) );

	this.requestCounter[ counterId ] = this.requestCounter[ counterId ] || 0;
	const requestId = ++this.requestCounter[ counterId ];
	const latestRequest = function () {
		return requestId === this.requestCounter[ counterId ];
	}.bind( this );

	// Sticky parameters override the URL params
	// this is to make sure that whether we represent
	// the sticky params in the URL or not (they may
	// be normalized out) the sticky parameters are
	// always being sent to the server with their
	// current/default values
	Object.keys( stickyParams ).forEach( ( key ) => url.searchParams.set( key, stickyParams[ key ] ) );

	return $.ajax( url.toString() )
		.then(
			( content, message, jqXHR ) => {
				if ( !latestRequest() ) {
					return $.Deferred().reject();
				}
				return {
					content: content,
					status: jqXHR.status
				};
			},
			// RC returns 404 when there is no results
			( jqXHR ) => {
				if ( latestRequest() ) {
					return $.Deferred().resolve(
						{
							content: jqXHR.responseText,
							status: jqXHR.status
						}
					).promise();
				}
			}
		);
};

/**
 * Fetch the list of changes from the server for the current filters
 *
 * @return {jQuery.Promise} Promise object that will resolve with the changes list
 *  and the fieldset.
 */
Controller.prototype._fetchChangesList = function () {
	return this._queryChangesList( 'updateChangesList' )
		.then(
			( data ) => {
				// Status code 0 is not HTTP status code,
				// but is valid value of XMLHttpRequest status.
				// It is used for variety of network errors, for example
				// when an AJAX call was cancelled before getting the response
				if ( data && data.status === 0 ) {
					return {
						changes: 'NO_RESULTS',
						// We need empty result set, to avoid exceptions because of undefined value
						fieldset: $( [] ),
						noResultsDetails: 'NO_RESULTS_NETWORK_ERROR'
					};
				}

				const $parsed = $( '<div>' ).append( $( $.parseHTML(
					data ? data.content : ''
				) ) );

				return this._extractChangesListInfo( $parsed, data.status );
			}
		);
};

/**
 * Apply a change of parameters to the model state, and check whether
 * the new state is different than the old state.
 *
 * @param  {Object} newParamState New parameter state to apply
 * @return {boolean} New applied model state is different than the previous state
 */
Controller.prototype.applyParamChange = function ( newParamState ) {
	const before = this.filtersModel.getSelectedState();

	this.filtersModel.updateStateFromParams( newParamState );

	const after = this.filtersModel.getSelectedState();

	return !OO.compare( before, after );
};

/**
 * Mark all changes as seen on Watchlist
 */
Controller.prototype.markAllChangesAsSeen = function () {
	const api = new mw.Api();
	api.postWithToken( 'csrf', {
		formatversion: 2,
		action: 'setnotificationtimestamp',
		entirewatchlist: true
	} ).then( () => {
		this.updateChangesList( null, 'markSeen' );
	} );
};

/**
 * Set the current search for the system.
 *
 * @param {string} searchQuery Search query, including triggers
 */
Controller.prototype.setSearch = function ( searchQuery ) {
	this.filtersModel.setSearch( searchQuery );
};

/**
 * Switch the view by changing the search query trigger
 * without changing the search term
 *
 * @param  {string} view View to change to
 */
Controller.prototype.switchView = function ( view ) {
	this.setSearch(
		this.filtersModel.getViewTrigger( view ) +
		this.filtersModel.removeViewTriggers( this.filtersModel.getSearch() )
	);
};

/**
 * Reset the search for a specific view. This means we null the search query
 * and replace it with the relevant trigger for the requested view
 *
 * @param  {string} [view='default'] View to change to
 */
Controller.prototype.resetSearchForView = function ( view ) {
	view = view || 'default';

	this.setSearch(
		this.filtersModel.getViewTrigger( view )
	);
};

module.exports = Controller;
