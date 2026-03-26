/**
 * A/B test gate for the email confirmation banner.
 *
 * PHP renders one hidden container per arm. This module reveals the container
 * matching the user's Test Kitchen assignment and removes the rest, so
 * WikimediaEvents instrumentation only fires for users who see a banner.
 */
( function () {
	const containers = document.querySelectorAll( '.mw-emailconfirmbanner-container' );
	if ( !containers.length ) {
		return;
	}

	function removeAll() {
		containers.forEach( ( el ) => el.remove() );
	}

	mw.loader.using( 'ext.testKitchen' ).then( () => {
		const exp = mw.testKitchen.getExperiment( 'email_confirmation_banner_ab_test' );
		if ( !exp || !exp.getAssignedGroup ) {
			removeAll();
			return;
		}
		const assignedGroup = exp.getAssignedGroup();
		containers.forEach( ( el ) => {
			if ( el.dataset.arm === assignedGroup ) {
				el.style.removeProperty( 'display' );
				mw.hook( 'mediawiki.emailConfirmationBanner.shown' ).fire( el );
			} else {
				el.remove();
			}
		} );
	} ).catch( removeAll );
}() );
