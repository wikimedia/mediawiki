/**
 * mediawiki.Feedback
 *
 * @author Ryan Kaldari, 2010
 * @author Neil Kandalgaonkar, 2010-11
 * @since 1.19
 *
 * This is a way of getting simple feedback from users. It's useful
 * for testing new features -- users can give you feedback without
 * the difficulty of opening a whole new talk page. For this reason,
 * it also tends to collect a wider range of both positive and negative
 * comments. However you do need to tend to the feedback page. It will
 * get long relatively quickly, and you often get multiple messages
 * reporting the same issue.
 *
 * It takes the form of thing on your page which, when clicked, opens a small
 * dialog box. Submitting that dialog box appends its contents to a
 * wiki page that you specify, as a new section.
 *
 * Not compatible with LiquidThreads.
 *
 * Minimal example in how to use it:
 *
 *    var feedback = new mw.Feedback();
 *    $( '#myButton' ).click( function() { feedback.launch(); } );
 *
 * You can also launch the feedback form with a prefilled subject and body.
 * See the docs for the launch() method.
 */
( function( mw, $, undefined ) {
	/**
	 * Thingy for collecting user feedback on a wiki page
	 * @param {Array} options -- optional, all properties optional.
	 * 		api: {mw.Api} if omitted, will just create a standard API
	 * 		title: {mw.Title} the title of the page where you collect feedback. Defaults to "Feedback".
	 * 		dialogTitleMessageKey: {String} message key for the title of the dialog box
	 * 		bugsLink: {mw.Uri|String} url where bugs can be posted
	 * 		bugsListLink: {mw.Uri|String} url where bugs can be listed
	 */
	mw.Feedback = function( options ) {
		if ( options === undefined ) {
			options = {};
		}

		if ( options.api === undefined ) {
			options.api = new mw.Api();
		}

		if ( options.title === undefined ) {
			options.title = new mw.Title( 'Feedback' );
		}

		if ( options.dialogTitleMessageKey === undefined ) {
			options.dialogTitleMessageKey = 'feedback-submit';
		}

		if ( options.bugsLink === undefined ) {
			options.bugsLink = '//bugzilla.wikimedia.org/enter_bug.cgi';
		}

		if ( options.bugsListLink === undefined ) {
			options.bugsListLink = '//bugzilla.wikimedia.org/query.cgi';
		}

		$.extend( this, options );
		this.setup();
	};

	mw.Feedback.prototype = {
		setup: function() {
			var _this = this;

			var $feedbackPageLink = $( '<a></a>' )
				.attr( { 'href': _this.title.getUrl(), 'target': '_blank' } )
				.css( { 'white-space': 'nowrap' } );

			var $bugNoteLink = $( '<a></a>' ).attr( { 'href': '#' } ).click( function() { _this.displayBugs(); } );

			var $bugsListLink = $( '<a></a>' ).attr( { 'href': _this.bugsListLink, 'target': '_blank' } );

			this.$dialog =
				$( '<div style="position:relative;"></div>' ).append(
					$( '<div class="feedback-mode feedback-form"></div>' ).append(
						$( '<small></small>' ).append(
							$( '<p></p>' ).msg(
								'feedback-bugornote',
								$bugNoteLink,
								_this.title.getNameText(),
								$feedbackPageLink.clone()
							)
						),
						$( '<div style="margin-top:1em;"></div>' ).append(
							mw.msg( 'feedback-subject' ),
							$( '<br/>' ),
							$( '<input type="text" class="feedback-subject" name="subject" maxlength="60" style="width:99%;"/>' )
						),
						$( '<div style="margin-top:0.4em;"></div>' ).append(
							mw.msg( 'feedback-message' ),
							$( '<br/>' ),
							$( '<textarea name="message" class="feedback-message" style="width:99%;" rows="5" cols="60"></textarea>' )
						)
					),
					$( '<div class="feedback-mode feedback-bugs"></div>' ).append(
						$( '<p>' ).msg( 'feedback-bugcheck', $bugsListLink )
					),
					$( '<div class="feedback-mode feedback-submitting" style="text-align:center;margin:3em 0;"></div>' ).append(
						mw.msg( 'feedback-adding' ),
						$( '<br/>' ),
						$( '<span class="feedback-spinner"></span>' )
					),
					$( '<div class="feedback-mode feedback-thanks" style="text-align:center;margin:1em"></div>' ).msg(
						'feedback-thanks', _this.title.getNameText(), $feedbackPageLink.clone()
					),
					$( '<div class="feedback-mode feedback-error" style="position:relative;"></div>' ).append(
						$( '<div class="feedback-error-msg style="color:#990000;margin-top:0.4em;"></div>' )
					)
				);

				// undo some damage from dialog css
				this.$dialog.find( 'a' ).css( { 'color': '#0645ad' } );

				this.$dialog.dialog({
					width: 500,
					autoOpen: false,
					title: mw.msg( this.dialogTitleMessageKey ),
					modal: true,
					buttons: _this.buttons
				});

			this.subjectInput = this.$dialog.find( 'input.feedback-subject' ).get(0);
			this.messageInput = this.$dialog.find( 'textarea.feedback-message' ).get(0);

		},

		display: function( s ) {
			this.$dialog.dialog( { buttons:{} } ); // hide the buttons
			this.$dialog.find( '.feedback-mode' ).hide(); // hide everything
			this.$dialog.find( '.feedback-' + s ).show(); // show the desired div
		},

		displaySubmitting: function() {
			this.display( 'submitting' );
		},

		displayBugs: function() {
			var _this = this;
			this.display( 'bugs' );
			var bugsButtons = {};
			bugsButtons[ mw.msg( 'feedback-bugnew' ) ] = function() { window.open( _this.bugsLink, '_blank' ); };
			bugsButtons[ mw.msg( 'feedback-cancel' ) ] = function() { _this.cancel(); };
			this.$dialog.dialog( { buttons: bugsButtons } );
		},

		displayThanks: function() {
			var _this = this;
			this.display( 'thanks' );
			var closeButton = {};
			closeButton[ mw.msg( 'feedback-close' ) ] = function() { _this.$dialog.dialog( 'close' ); };
			this.$dialog.dialog( { buttons: closeButton } );
		},

		/**
		 * Display the feedback form
		 * @param {Object} optional prefilled contents for the feedback form. Object with properties:
		 *						subject: {String}
		 *						message: {String}
		 */
		displayForm: function( contents ) {
			var _this = this;
			this.subjectInput.value = (contents && contents.subject) ? contents.subject : '';
			this.messageInput.value = (contents && contents.message) ? contents.message : '';

			this.display( 'form' );

			// Set up buttons for dialog box. We have to do it the hard way since the json keys are localized
			var formButtons = {};
			formButtons[ mw.msg( 'feedback-submit' ) ] = function() { _this.submit(); };
			formButtons[ mw.msg( 'feedback-cancel' ) ] = function() { _this.cancel(); };
			this.$dialog.dialog( { buttons: formButtons } ); // put the buttons back
		},

		displayError: function( message ) {
			var _this = this;
			this.display( 'error' );
			this.$dialog.find( '.feedback-error-msg' ).msg( message );
			var closeButton = {};
			closeButton[ mw.msg( 'feedback-close' ) ] = function() { _this.$dialog.dialog( 'close' ); };
			this.$dialog.dialog( { buttons: closeButton } );
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
						_this.displayThanks();
					} else {
						_this.displayError( 'feedback-error1' ); // unknown API result
					}
				} else {
					_this.displayError( 'feedback-error2' ); // edit failed
				}
			};

			var err = function( code, info ) {
				_this.displayError( 'feedback-error3' ); // ajax request failed
			};

			this.api.newSection( this.title, subject, message, ok, err );
		}, // close submit button function

		/**
		 * Modify the display form, and then open it, focusing interface on the subject.
		 * @param {Object} optional prefilled contents for the feedback form. Object with properties:
		 *						subject: {String}
		 *						message: {String}
		 */
		launch: function( contents ) {
			this.displayForm( contents );
			this.$dialog.dialog( 'open' );
			this.subjectInput.focus();
		}

	};

} )( window.mediaWiki, jQuery );
