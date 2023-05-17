( function () {
	$( function () {
		function ProcessDialog( config ) {
			ProcessDialog.super.call( this, config );
		}

		OO.inheritClass( ProcessDialog, OO.ui.ProcessDialog );

		ProcessDialog.static.name = 'managewiki-review';

		ProcessDialog.static.title = mw.msg( 'managewiki-review-title' );

		ProcessDialog.static.actions = [ {
			icon: 'close',
			flags: [ 'safe', 'close' ]
		} ];

		ProcessDialog.prototype.initialize = function () {
			ProcessDialog.super.prototype.initialize.apply( this, arguments );

			this.content = new OO.ui.PanelLayout( {
				padded: true,
				expanded: false
			} );

			var dialog = this;
			$( '#managewiki-review' ).on( 'click', function () {
				dialog.content.$element.html( '' );
				$( '#managewiki-form :input[name]:not( #managewiki-submit-reason :input[name] )' ).each( function () {
					if ( this.type === 'checkbox' && this.defaultChecked !== undefined && this.defaultChecked !== this.checked ) {
						dialog.content.$element.append( '<li><b>' + this.name.replace( 'wp', '' ).replace( /-namespace|-namespacetalk|ext-|set-/, '' ).replace( '[]', '[' + this.value + ']' ) + ' (' + $( $( this ).parents( 'fieldset' ).contents()[ 0 ] ).text() + ')</b> was <i>' + ( this.checked === true ? 'enabled' : 'disabled' ) + '</i></li>' );
					} else if ( this.defaultValue !== undefined && this.defaultValue !== this.value ) {
						dialog.content.$element.append( '<li><b>' + this.name.replace( 'wp', '' ).replace( /-namespace|-namespacetalk|ext-|set-/, '' ) + ' (' + $( $( this ).parents( 'fieldset' ).contents()[ 0 ] ).text() + ')</b> was changed from <i>' + ( this.defaultValue ? this.defaultValue : '&lt;none&gt;' ) + '</i> to <i>' + ( this.value ? this.value : '&lt;none&gt;' ) + '</i></li>' );
					}
				} );

				if ( !dialog.content.$element.html() ) {
					/* eslint-disable-next-line no-jquery/no-parse-html-literal */
					dialog.content.$element.append( '<i>No changes made.</i>' );
				}

				dialog.$body.append( dialog.content.$element );
			} );
		};

		ProcessDialog.prototype.getActionProcess = function ( action ) {
			var dialog = this;
			if ( action ) {
				return new OO.ui.Process( function () {
					dialog.close( {
						action: action
					} );
				} );
			}

			return ProcessDialog.super.prototype.getActionProcess.call( this, action );
		};

		ProcessDialog.prototype.getBodyHeight = function () {
			return this.content.$element.outerHeight( true );
		};

		var windowManager = new OO.ui.WindowManager();
		$( document.body ).append( windowManager.$element );

		var processDialog = new ProcessDialog( {
			size: 'large'
		} );

		windowManager.addWindows( [ processDialog ] );

		$( '#managewiki-review' ).on( 'click', function () {
			windowManager.openWindow( processDialog );
		} );
	} );
}() );
