( function( mw, $, undefined ) {

	/**
	 * Thingy for collecting user feedback on a wiki page
	 * @param {mw.Api}  api properly configured to talk to this wiki
	 * @param {mw.Title} the title of the page where you collect feedback
	 * @param {id} a string identifying this feedback form to separate it from others on the same page
	 */
	mw.Feedback = function( api, feedbackTitle ) {
		var _this = this;
		this.api = api;
		this.feedbackTitle = feedbackTitle;
		this.setup();
	};

	mw.Feedback.prototype = {
		setup: function() {
			var _this = this;

			// Set up buttons for dialog box. We have to do it the hard way since the json keys are localized
			_this.buttons = {};
			_this.buttons[ gM( 'mwe-upwiz-feedback-cancel' ) ] = function() { _this.cancel(); };
			_this.buttons[ gM( 'mwe-upwiz-feedback-submit' ) ] = function() { _this.submit(); };
				
			var $feedbackPageLink = $j( '<a></a>' ).attr( { 'href': _this.feedbackTitle.getUrl(), 'target': '_blank' } );
			this.$dialog = 
				$( '<div style="position:relative;"></div>' ).append( 
					$( '<div class="mwe-upwiz-feedback-mode mwe-upwiz-feedback-form"></div>' ).append( 
						$( '<div style="margin-top:0.4em;"></div>' ).append( 
							$( '<small></small>' ).msg( 'mwe-upwiz-feedback-note', 
										    _this.feedbackTitle.getNameText(), 
										    $feedbackPageLink ) 
						),
						$( '<div style="margin-top:1em;"></div>' ).append( 
							gM( 'mwe-upwiz-feedback-subject' ), 
							$( '<br/>' ), 
							$( '<input type="text" class="mwe-upwiz-feedback-subject" name="subject" maxlength="60" style="width:99%;"/>' ) 
						),
						$( '<div style="margin-top:0.4em;"></div>' ).append( 
							gM( 'mwe-upwiz-feedback-message' ), 
							$( '<br/>' ), 
							$( '<textarea name="message" class="mwe-upwiz-feedback-message" style="width:99%;" rows="5" cols="60"></textarea>' ) 
						)
					),
					$( '<div class="mwe-upwiz-feedback-mode mwe-upwiz-feedback-submitting" style="text-align:center;margin:3em 0;"></div>' ).append( 
						gM( 'mwe-upwiz-feedback-adding' ), 
						$( '<br/>' ), 
						$( '<img src="http://upload.wikimedia.org/wikipedia/commons/4/42/Loading.gif" />' ) 
					),
					$( '<div class="mwe-upwiz-feedback-mode mwe-upwiz-feedback-error" style="position:relative;"></div>' ).append( 
						$( '<div class="mwe-upwiz-feedback-error-msg style="color:#990000;margin-top:0.4em;"></div>' )

					)
				).dialog({
					width: 500,
					autoOpen: false,
					title: gM( 'mwe-upwiz-feedback-title' ),
					modal: true,
					buttons: _this.buttons
				}); 

			this.subjectInput = this.$dialog.find( 'input.mwe-upwiz-feedback-subject' ).get(0);
			this.messageInput = this.$dialog.find( 'textarea.mwe-upwiz-feedback-message' ).get(0);
			this.displayForm();		
		},

		display: function( s ) {
			this.$dialog.dialog( { buttons:{} } ); // hide the buttons
			this.$dialog.find( '.mwe-upwiz-feedback-mode' ).hide(); // hide everything
			this.$dialog.find( '.mwe-upwiz-feedback-' + s ).show(); // show the desired div 
		},

		displaySubmitting: function() {	
			this.display( 'submitting' );
		},

		displayForm: function( contents ) {
			this.subjectInput.value = (contents && contents.subject) ? contents.subject : '';
			this.messageInput.value = (contents && contents.message) ? contents.message : '';
						
			this.display( 'form' );	
			this.$dialog.dialog( { buttons: this.buttons } ); // put the buttons back
		},

		displayError: function( message ) {
			this.display( 'error' );
			this.$dialog.find( '.mwe-upwiz-feedback-error-msg' ).msg( message ); 
		},

		cancel: function() { 
			this.$dialog.dialog( 'close' );
		},

		submit: function() {
			var _this = this;

			// get the values to submit
			var subject = this.subjectInput.value;

			var message = "<small>User agent: " + navigator.userAgent + "</small>\n\n"
				 + this.messageInput.value;
			if ( message.indexOf( '~~~' ) == -1 ) {
				message += " ~~~~";
			}

			this.displaySubmitting();

			var ok = function( result ) {
				if ( result.edit !== undefined ) {
					if ( result.edit.result === 'Success' ) {
						_this.$dialog.dialog( 'close' ); // edit complete, close dialog box
					} else {
						_this.displayError( 'mwe-upwiz-feedback-error1' ); // unknown API result
					}
				} else {
					displayError( 'mwe-upwiz-feedback-error2' ); // edit failed
				}
			};

			var err = function( code, info ) {
				displayError( 'mwe-upwiz-feedback-error3' ); // ajax request failed
			};
		
			this.api.newSection( this.feedbackTitle, subject, message, ok, err );

		}, // close submit button function


		launch: function( contents ) {
			this.displayForm( contents );
			this.$dialog.dialog( 'open' );
			this.subjectInput.focus();
		}	

	};


} )( window.mediaWiki, jQuery );
