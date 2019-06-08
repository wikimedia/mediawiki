( function () {
	/**
	 * Top section (between page title and filters) on Special:Recentchanges
	 *
	 * @class mw.rcfilters.ui.RcTopSectionWidget
	 * @extends OO.ui.Widget
	 *
	 * @constructor
	 * @param {mw.rcfilters.ui.SavedLinksListWidget} savedLinksListWidget
	 * @param {jQuery} $topLinks Content of the community-defined links
	 * @param {Object} [config] Configuration object
	 */
	var RcTopSectionWidget = function MwRcfiltersUiRcTopSectionWidget(
		savedLinksListWidget, $topLinks, config
	) {
		var toplinksTitle,
			topLinksCookieName = 'rcfilters-toplinks-collapsed-state',
			topLinksCookie = mw.cookie.get( topLinksCookieName ),
			topLinksCookieValue = topLinksCookie || 'collapsed',
			widget = this;

		config = config || {};

		// Parent
		RcTopSectionWidget.parent.call( this, config );

		this.$topLinks = $topLinks;

		toplinksTitle = new OO.ui.ButtonWidget( {
			framed: false,
			indicator: topLinksCookieValue === 'collapsed' ? 'down' : 'up',
			flags: [ 'progressive' ],
			label: $( '<span>' ).append( mw.message( 'rcfilters-other-review-tools' ).parse() ).contents()
		} );

		this.$topLinks
			.makeCollapsible( {
				collapsed: topLinksCookieValue === 'collapsed',
				$customTogglers: toplinksTitle.$element
			} )
			.on( 'beforeExpand.mw-collapsible', function () {
				mw.cookie.set( topLinksCookieName, 'expanded' );
				toplinksTitle.setIndicator( 'up' );
				widget.switchTopLinks( 'expanded' );
			} )
			.on( 'beforeCollapse.mw-collapsible', function () {
				mw.cookie.set( topLinksCookieName, 'collapsed' );
				toplinksTitle.setIndicator( 'down' );
				widget.switchTopLinks( 'collapsed' );
			} );

		this.$topLinks.find( '.mw-recentchanges-toplinks-title' )
			.replaceWith( toplinksTitle.$element.removeAttr( 'tabIndex' ) );

		// Create two positions for the toplinks to toggle between
		// in the table (first cell) or up above it
		this.$top = $( '<div>' )
			.addClass( 'mw-rcfilters-ui-rcTopSectionWidget-topLinks-top' );
		this.$tableTopLinks = $( '<div>' )
			.addClass( 'mw-rcfilters-ui-cell' )
			.addClass( 'mw-rcfilters-ui-rcTopSectionWidget-topLinks-table' );

		// Initialize
		this.$element
			.addClass( 'mw-rcfilters-ui-rcTopSectionWidget' )
			.append(
				$( '<div>' )
					.addClass( 'mw-rcfilters-ui-table' )
					.append(
						$( '<div>' )
							.addClass( 'mw-rcfilters-ui-row' )
							.append(
								this.$tableTopLinks,
								$( '<div>' )
									.addClass( 'mw-rcfilters-ui-table-placeholder' )
									.addClass( 'mw-rcfilters-ui-cell' ),
								!mw.user.isAnon() ?
									$( '<div>' )
										.addClass( 'mw-rcfilters-ui-cell' )
										.addClass( 'mw-rcfilters-ui-rcTopSectionWidget-savedLinks' )
										.append( savedLinksListWidget.$element ) :
									null
							)
					)
			);

		// Hack: For jumpiness reasons, this should be a sibling of -head
		$( '.rcfilters-head' ).before( this.$top );

		// Initialize top links position
		widget.switchTopLinks( topLinksCookieValue );
	};

	/* Initialization */

	OO.inheritClass( RcTopSectionWidget, OO.ui.Widget );

	/**
	 * Switch the top links widget from inside the table (when collapsed)
	 * to the 'top' (when open)
	 *
	 * @param {string} [state] The state of the top links widget: 'expanded' or 'collapsed'
	 */
	RcTopSectionWidget.prototype.switchTopLinks = function ( state ) {
		state = state || 'expanded';

		if ( state === 'expanded' ) {
			this.$top.append( this.$topLinks );
		} else {
			this.$tableTopLinks.append( this.$topLinks );
		}
		this.$topLinks.toggleClass( 'mw-recentchanges-toplinks-collapsed', state === 'collapsed' );
	};

	module.exports = RcTopSectionWidget;
}() );
