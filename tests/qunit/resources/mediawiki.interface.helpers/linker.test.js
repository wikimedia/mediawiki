( function () {
	QUnit.module( 'mediawiki.interface.helpers.linker', {
		beforeEach() {
			this.$expiredTempUserLink = $( '<a class="mw-tempuserlink-expired">' );
			this.$tooltip = $( '<div id="test-tooltip-0" class="mw-tempuserlink-expired--tooltip" style="display: none;">' );
			this.$expiredTempUserLink.attr( 'aria-describedby', this.$tooltip.attr( 'id' ) );

			const $fixture = $( '#qunit-fixture' );

			$fixture.append( this.$expiredTempUserLink )
				.append( this.$tooltip );
		}
	} );

	QUnit.test( 'keeps tooltips initially hidden', function ( assert ) {
		mw.hook( 'wikipage.content' ).fire( $( '#qunit-fixture' ) );

		assert.strictEqual( this.$tooltip.css( 'display' ), 'none' );
	} );

	const showOnEvents = [ 'mouseover', 'focus' ];

	showOnEvents.forEach( ( eventName ) => {
		QUnit.test( `shows tooltip on ${ eventName }`, function ( assert ) {
			mw.hook( 'wikipage.content' ).fire( $( '#qunit-fixture' ) );

			this.$expiredTempUserLink.trigger( eventName );

			const { display, visibility } = this.$tooltip.css( [
				'display',
				'visibility'
			] );

			assert.strictEqual( display, 'block' );
			assert.strictEqual( visibility, 'visible' );
		} );
	} );

	const hideOnEvents = [ 'mouseout', 'blur' ];

	hideOnEvents.forEach( ( eventName ) => {
		QUnit.test( `hides tooltip on ${ eventName }`, function ( assert ) {
			mw.hook( 'wikipage.content' ).fire( $( '#qunit-fixture' ) );

			this.$expiredTempUserLink.trigger( 'mouseover' );
			this.$expiredTempUserLink.trigger( eventName );

			assert.strictEqual( this.$tooltip.css( 'visibility' ), 'hidden' );
		} );
	} );

	QUnit.test( 'hides tooltip when Esc is pressed', function ( assert ) {
		mw.hook( 'wikipage.content' ).fire( $( '#qunit-fixture' ) );

		this.$expiredTempUserLink.trigger( 'mouseover' );

		$( document ).trigger( $.Event( 'keydown', { key: 'Escape' } ) );

		assert.strictEqual( this.$tooltip.css( 'visibility' ), 'hidden' );
	} );
}() );
