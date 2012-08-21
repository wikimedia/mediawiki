/*
 * Apex-specific scripts
 */
( function ( $, mw ) {

mw.ApexSkin = function () {
	// Properties
	this.$navLeft = $( 'body.ltr .apex-nav-primary, body.rtl .apex-nav-secondary' );
	this.$navRight = $( 'body.ltr .apex-nav-secondary, body.rtl .apex-nav-primary' );
	this.$views = $( '#p-views' );
	this.$viewsList = this.$views.find( 'ul' );
	this.$actions = $( '#p-cactions' );
	this.$actionsList = this.$actions.find( 'ul' );
	this.minimumNavDistance = 32;
	this.stash = [];

	// Events
	$( window ).on( 'resize', $.proxy( this.onResize, this ) );
};

mw.ApexSkin.prototype.getNavDistance = function () {
	return this.$navRight.offset().left - ( this.$navLeft.offset().left + this.$navLeft.width() );
};

mw.ApexSkin.prototype.onResize = function () {
	var i, stashable, $item, $anchor, width,
		$actionsList = this.$actionsList,
		stash = this.stash,
		gap = this.getNavDistance(),
		buffer = this.minimumNavDistance;
	if ( gap < buffer ) {
		// Collect a list of all overlapping items
		stashable = this.$viewsList.find( '.apex-nav-stashable' ).get();
		for ( i = stashable.length - 1; i >= 0; i-- ) {
			$item = $( stashable[i] );
			$anchor = $item.next();
			width = $item.outerWidth();
			stash.push( { '$item': $item, '$anchor': $anchor, 'width': width } );
			$item.addClass( 'apex-nav-stashed' );
			$actionsList.prepend( $item );
			gap += width;
			if ( gap >= buffer ) {
				break;
			}
		}
	} else if ( stash.length ) {
		// Collect a list of all overlapping items
		for ( i = stash.length - 1; i >= 0; i-- ) {
			$item = stash[i].$item;
			$anchor = stash[i].$anchor;
			width = stash[i].width;
			if ( gap - width > buffer ) {
				if ( $anchor.length ) {
					$anchor.before( $item );
				} else {
					this.$viewsList.append( $item );
				}
				$item.removeClass( 'apex-nav-stashed' );
				gap -= width;
			} else {
				break;
			}
		}
	}
};

$( function () {
	mw.skin = new mw.ApexSkin();
} );

} ( jQuery, mediaWiki ) );