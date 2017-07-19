/*!
 * MediaWiki Widgets - SearchInputWidget class.
 *
 * @copyright 2011-2015 MediaWiki Widgets Team and others; see AUTHORS.txt
 * @license The MIT License (MIT); see LICENSE.txt
 */
( function ( $, mw ) {

	/**
	 * Creates a mw.widgets.SearchInputWidget object.
	 *
	 * @class
	 * @extends mw.widgets.TitleInputWidget
	 *
	 * @constructor
	 * @param {Object} [config] Configuration options
	 * @cfg {boolean} [pushPending=false] Visually mark the input field as "pending", while
	 *  requesting suggestions.
	 * @cfg {boolean} [performSearchOnClick=true] If true, the script will start a search when-
	 *  ever a user hits a suggestion. If false, the text of the suggestion is inserted into the
	 *  text field only.
	 *  @cfg {string} [dataLocation='header'] Where the search input field will be
	 *  used (header or content).
	 */
	mw.widgets.SearchInputWidget = function MwWidgetsSearchInputWidget( config ) {
		// The parent constructors will detach this from the DOM, and won't
		// be reattached until after this function is completed. As such
		// grab a handle here. If no config.$input is passed tracking of
		// form submissions won't work.
		var $form = config.$input ? config.$input.closest( 'form' ) : $();

		config = $.extend( {
			icon: 'search',
			maxLength: undefined,
			performSearchOnClick: true,
			dataLocation: 'header',
			namespace: 0
		}, config );

		// Parent constructor
		mw.widgets.SearchInputWidget.parent.call( this, config );

		// Initialization
		this.$element.addClass( 'mw-widget-searchInputWidget' );
		this.lookupMenu.$element.addClass( 'mw-widget-searchWidget-menu' );
		this.lastLookupItems = [];
		if ( !config.pushPending ) {
			this.pushPending = false;
		}
		if ( config.dataLocation ) {
			this.dataLocation = config.dataLocation;
		}
		if ( config.performSearchOnClick ) {
			this.performSearchOnClick = config.performSearchOnClick;
		}
		this.setLookupsDisabled( !this.suggestions );

		$form.on( 'submit', function () {
			mw.track( 'mw.widgets.SearchInputWidget', {
				action: 'submit-form',
				numberOfResults: this.lastLookupItems.length,
				$form: $form,
				inputLocation: this.dataLocation || 'header',
				index: this.lastLookupItems.indexOf(
					this.$input.val()
				)
			} );
		}.bind( this ) );

		this.$element.addClass( 'oo-ui-textInputWidget-type-search' );
		this.updateSearchIndicator();
		this.connect( this, {
			disable: 'onDisable'
		} );
	};

	/* Setup */

	OO.inheritClass( mw.widgets.SearchInputWidget, mw.widgets.TitleInputWidget );

	/* Methods */

	/**
	 * @inheritdoc
	 * @protected
	 */
	mw.widgets.SearchInputWidget.prototype.getInputElement = function () {
		return $( '<input>' ).attr( 'type', 'search' );
	};

	/**
	 * @inheritdoc
	 */
	mw.widgets.SearchInputWidget.prototype.onIndicatorMouseDown = function ( e ) {
		if ( e.which === OO.ui.MouseButtons.LEFT ) {
			// Clear the text field
			this.setValue( '' );
			this.$input[ 0 ].focus();
			return false;
		}
	};

	/**
	 * Update the 'clear' indicator displayed on type: 'search' text
	 * fields, hiding it when the field is already empty or when it's not
	 * editable.
	 */
	mw.widgets.SearchInputWidget.prototype.updateSearchIndicator = function () {
		if ( this.getValue() === '' || this.isDisabled() || this.isReadOnly() ) {
			this.setIndicator( null );
		} else {
			this.setIndicator( 'clear' );
		}
	};

	/**
	 * @see OO.ui.SearchInputWidget#onChange
	 */
	mw.widgets.SearchInputWidget.prototype.onChange = function () {
		mw.widgets.SearchInputWidget.parent.prototype.onChange.call( this );
		this.updateSearchIndicator();
	};

	/**
	 * Handle disable events.
	 *
	 * @param {boolean} disabled Element is disabled
	 * @private
	 */
	mw.widgets.SearchInputWidget.prototype.onDisable = function () {
		this.updateSearchIndicator();
	};

	/**
	 * @inheritdoc
	 */
	mw.widgets.SearchInputWidget.prototype.setReadOnly = function ( state ) {
		mw.widgets.SearchInputWidget.parent.prototype.setReadOnly.call( this, state );
		this.updateSearchIndicator();
		return this;
	};

	/**
	 * @inheritdoc mw.widgets.TitleWidget
	 */
	mw.widgets.SearchInputWidget.prototype.getSuggestionsPromise = function () {
		var api = this.getApi(),
			promise,
			self = this;

		// reuse the searchSuggest function from mw.searchSuggest
		promise = mw.searchSuggest.request( api, this.getQueryValue(), $.noop, this.limit, this.getNamespace() );

		// tracking purposes
		promise.done( function ( data, jqXHR ) {
			self.requestType = jqXHR.getResponseHeader( 'X-OpenSearch-Type' );
		} );

		return promise;
	};

	/**
	 * @inheritdoc mw.widgets.TitleInputWidget
	 */
	mw.widgets.SearchInputWidget.prototype.getLookupCacheDataFromResponse = function ( response ) {
		var resp;

		// mw.widgets.TitleInputWidget uses response.query, which doesn't exist for opensearch,
		// so return the whole response (titles only, and links)
		resp = {
			data: response || {},
			metadata: {
				type: this.requestType || 'unknown',
				query: this.getQueryValue()
			}
		};
		this.requestType = undefined;

		return resp;
	};

	/**
	 * @inheritdoc mw.widgets.TitleWidget
	 */
	mw.widgets.SearchInputWidget.prototype.getOptionsFromData = function ( data ) {
		var items = [],
			self = this;

		// mw.widgets.TitleWidget does a lot more work here, because the TitleOptionWidgets can
		// differ a lot, depending on the returned data from the request. With the request used here
		// we get only the search results.
		$.each( data.data[ 1 ], function ( i, result ) {
			items.push( new mw.widgets.TitleOptionWidget(
				// data[ 3 ][ i ] is the link for this result
				self.getOptionWidgetData( result, null, data.data[ 3 ][ i ] )
			) );
		} );

		mw.track( 'mw.widgets.SearchInputWidget', {
			action: 'impression-results',
			numberOfResults: items.length,
			resultSetType: data.metadata.type,
			query: data.metadata.query,
			inputLocation: this.dataLocation || 'header'
		} );

		return items;
	};

	/**
	 * @inheritdoc mw.widgets.TitleWidget
	 *
	 * @param {string} title
	 * @param {Object} data
	 * @param {string} url The Url to the result
	 */
	mw.widgets.SearchInputWidget.prototype.getOptionWidgetData = function ( title, data, url ) {
		// the values used in mw.widgets-TitleWidget doesn't exist here, that's why
		// the values are hard-coded here
		return {
			data: title,
			url: url,
			imageUrl: null,
			description: null,
			missing: false,
			redirect: false,
			disambiguation: false,
			query: this.getQueryValue()
		};
	};

	/**
	 * @inheritdoc
	 */
	mw.widgets.SearchInputWidget.prototype.onLookupMenuItemChoose = function () {
		mw.widgets.SearchInputWidget.parent.prototype.onLookupMenuItemChoose.apply( this, arguments );

		if ( this.performSearchOnClick ) {
			this.$element.closest( 'form' ).submit();
		}
	};

	/**
	 * @inheritdoc
	 */
	mw.widgets.SearchInputWidget.prototype.getLookupMenuOptionsFromData = function () {
		var items = mw.widgets.SearchInputWidget.parent.prototype.getLookupMenuOptionsFromData.apply(
			this, arguments
		);

		this.lastLookupItems = items.map( function ( item ) {
			return item.data;
		} );

		return items;
	};

}( jQuery, mediaWiki ) );
