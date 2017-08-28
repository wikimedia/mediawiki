( function ( mw ) {
	/**
	 * Top section (between page title and filters) on Special:Recentchanges
	 *
	 * @extends OO.ui.Widget
	 *
	 * @constructor
	 * @param {mw.rcfilters.ui.SavedLinksListWidget} savedLinksListWidget
	 * @param {jQuery} $topLinks
	 * @param {Object} [config] Configuration object
	 */
	mw.rcfilters.ui.RcTopSectionWidget = function MwRcfiltersUiRcTopSectionWidget(
		savedLinksListWidget, $topLinks, config
	) {
		var topLinksCookieName = 'rcfilters-toplinks-collapsed-state',
			topLinksCookie = mw.cookie.get( topLinksCookieName ),
			topLinksCookieValue = topLinksCookie || 'collapsed',
			toplinksTitle;
		config = config || {};

		// Parent
		mw.rcfilters.ui.RcTopSectionWidget.parent.call( this, config );

		toplinksTitle = new OO.ui.ButtonWidget( {
			framed: false,
			indicator: topLinksCookieValue === 'collapsed' ? 'down' : 'up',
			flags: [ 'progressive' ],
			label: $( '<span>' ).append( mw.message( 'rcfilters-other-review-tools' ).parse() ).contents()
		} );

		$topLinks
			.addClass( 'mw-rcfilters-ui-ready' )
			.makeCollapsible( {
				collapsed: topLinksCookieValue === 'collapsed',
				$customTogglers: toplinksTitle.$element
			} )
			.on( 'beforeExpand.mw-collapsible', function () {
				mw.cookie.set( topLinksCookieName, 'expanded' );
				toplinksTitle.setIndicator( 'up' );
			} )
			.on( 'beforeCollapse.mw-collapsible', function () {
				mw.cookie.set( topLinksCookieName, 'collapsed' );
				toplinksTitle.setIndicator( 'down' );
			} );

		$topLinks.find( '.mw-recentchanges-toplinks-title' ).replaceWith( toplinksTitle.$element );

		this.$element
			.addClass( 'mw-rcfilters-ui-rcTopSectionWidget' )
			.addClass( 'mw-rcfilters-ui-table' )
			.append(
				$( '<div>' )
					.addClass( 'mw-rcfilters-ui-row' )
					.append(
						$( '<div>' )
							.addClass( 'mw-rcfilters-ui-cell' )
							.addClass( 'mw-rcfilters-ui-rcTopSectionWidget-topLinks' )
							.append( $topLinks )
					)
					.append(
						!mw.user.isAnon() ?
							$( '<div>' )
								.addClass( 'mw-rcfilters-ui-cell' )
								.addClass( 'mw-rcfilters-ui-rcTopSectionWidget-savedLinks' )
								.append( savedLinksListWidget.$element ) :
							null
					)
			);
	};

	/* Initialization */

	OO.inheritClass( mw.rcfilters.ui.RcTopSectionWidget, OO.ui.Widget );
}( mediaWiki ) );
