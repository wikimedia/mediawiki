/*!
 * mediawiki.feedback
 *
 * @author Ryan Kaldari, 2010
 * @author Neil Kandalgaonkar, 2010-11
 * @since 1.19
 */
( function ( mw, $ ) {
	/**
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
	 *     var feedback = new mw.Feedback();
	 *     $( '#myButton' ).click( function () { feedback.launch(); } );
	 *
	 * You can also launch the feedback form with a prefilled subject and body.
	 * See the docs for the #launch() method.
	 *
	 * @class
	 * @constructor
	 * @param {Object} [options]
	 * @param {mw.Api} [options.api] if omitted, will just create a standard API
	 * @param {mw.Title} [options.title="Feedback"] The title of the page where you collect
	 * feedback.
	 * @param {string} [options.dialogTitleMessageKey="feedback-submit"] Message key for the
	 * title of the dialog box
	 * @param {string} [options.bugsLink="//bugzilla.wikimedia.org/enter_bug.cgi"] URL where
	 * bugs can be posted
	 * @param {mw.Uri|string} [options.bugsListLink="//bugzilla.wikimedia.org/query.cgi"]
	 * URL where bugs can be listed
	 */
	mw.Feedback = function ( options ) {
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
		/**
		 * Sets up interface
		 */
		setup: function () {
			var $feedbackPageLink,
				$bugNoteLink,
				$bugsListLink,
				fb = this;

			$feedbackPageLink = $( '<a>' )
				.attr( {
					href: fb.title.getUrl(),
					target: '_blank'
				} )
				.css( {
					whiteSpace: 'nowrap'
				} );

			$bugNoteLink = $( '<a>' ).attr( { href: '#' } ).click( function () {
				fb.displayBugs();
			} );

			$bugsListLink = $( '<a>' ).attr( {
				href: fb.bugsListLink,
				target: '_blank'
			} );

			// TODO: Use a stylesheet instead of these inline styles
			this.$dialog =
				$( '<div style="position: relative;"></div>' ).append(
					$( '<div class="feedback-mode feedback-form"></div>' ).append(
						$( '<small>' ).append(
							$( '<p>' ).msg(
								'feedback-bugornote',
								$bugNoteLink,
								fb.title.getNameText(),
								$feedbackPageLink.clone()
							)
						),
						$( '<div style="margin-top: 1em;"></div>' ).append(
							mw.msg( 'feedback-subject' ),
							$( '<br>' ),
							$( '<input type="text" class="feedback-subject" name="subject" maxlength="60" style="width: 100%; -moz-box-sizing: border-box; -webkit-box-sizing: border-box; box-sizing: border-box;"/>' )
						),
						$( '<div style="margin-top: 0.4em;"></div>' ).append(
							mw.msg( 'feedback-message' ),
							$( '<br>' ),
							$( '<textarea name="message" class="feedback-message" rows="5" cols="60"></textarea>' )
						)
					),
					$( '<div class="feedback-mode feedback-bugs"></div>' ).append(
						$( '<p>' ).msg( 'feedback-bugcheck', $bugsListLink )
					),
					$( '<div class="feedback-mode feedback-submitting" style="text-align: center; margin: 3em 0;"></div>' ).append(
						mw.msg( 'feedback-adding' ),
						$( '<br>' ),
						$( '<span class="feedback-spinner"></span>' )
					),
					$( '<div class="feedback-mode feedback-thanks" style="text-align: center; margin:1em"></div>' ).msg(
						'feedback-thanks', fb.title.getNameText(), $feedbackPageLink.clone()
					),
					$( '<div class="feedback-mode feedback-error" style="position: relative;"></div>' ).append(
						$( '<div class="feedback-error-msg style="color: #990000; margin-top: 0.4em;"></div>' )
					)
				);

				// undo some damage from dialog css
				this.$dialog.find( 'a' ).css( {
					color: '#0645ad'
				} );

				this.$dialog.dialog({
					width: 500,
					autoOpen: false,
					title: mw.msg( this.dialogTitleMessageKey ),
					modal: true,
					buttons: fb.buttons
				});

			this.subjectInput = this.$dialog.find( 'input.feedback-subject' ).get(0);
			this.messageInput = this.$dialog.find( 'textarea.feedback-message' ).get(0);

		},

		/**
		 * Displays a section of the dialog.
		 *
		 * @param {"form"|"bugs"|"submitting"|"thanks"|"error"} s
		 * The section of the dialog to show.
		 */
		display: function ( s ) {
			// Hide the buttons
			this.$dialog.dialog( { buttons: {} } );
			// Hide everything
			this.$dialog.find( '.feedback-mode' ).hide();
			// Show the desired div
			this.$dialog.find( '.feedback-' + s ).show();
		},

		/**
		 * Display the submitting section.
		 */
		displaySubmitting: function () {
			this.display( 'submitting' );
		},

		/**
		 * Display the bugs section.
		 */
		displayBugs: function () {
			var fb = this,
				bugsButtons = {};
			this.display( 'bugs' );
			bugsButtons[ mw.msg( 'feedback-bugnew' ) ] = function () {
				window.open( fb.bugsLink, '_blank' );
			};
			bugsButtons[ mw.msg( 'feedback-cancel' ) ] = function () {
				fb.cancel();
			};
			this.$dialog.dialog( {
				buttons: bugsButtons
			} );
		},

		/**
		 * Display the thanks section.
		 */
		displayThanks: function () {
			var fb = this,
				closeButton = {};
			this.display( 'thanks' );
			closeButton[ mw.msg( 'feedback-close' ) ] = function () {
				fb.$dialog.dialog( 'close' );
			};
			this.$dialog.dialog( {
				buttons: closeButton
			} );
		},

		/**
		 * Display the feedback form
		 * @param {Object} [contents] Prefilled contents for the feedback form.
		 * @param {string} [contents.subject] The subject of the feedback
		 * @param {string} [contents.message] The content of the feedback
		 */
		displayForm: function ( contents ) {
			var fb = this,
				formButtons = {};
			this.subjectInput.value = ( contents && contents.subject ) ? contents.subject : '';
			this.messageInput.value = ( contents && contents.message ) ? contents.message : '';

			this.display( 'form' );

			// Set up buttons for dialog box. We have to do it the hard way since the json keys are localized
			formButtons[ mw.msg( 'feedback-submit' ) ] = function () {
				fb.submit();
			};
			formButtons[ mw.msg( 'feedback-cancel' ) ] = function () {
				fb.cancel();
			};
			this.$dialog.dialog( { buttons: formButtons } ); // put the buttons back
		},

		/**
		 * Display an error on the form.
		 *
		 * @param {string} message Should be a valid message key.
		 */
		displayError: function ( message ) {
			var fb = this,
				closeButton = {};
			this.display( 'error' );
			this.$dialog.find( '.feedback-error-msg' ).msg( message );
			closeButton[ mw.msg( 'feedback-close' ) ] = function () {
				fb.$dialog.dialog( 'close' );
			};
			this.$dialog.dialog( { buttons: closeButton } );
		},

		/**
		 * Close the feedback form.
		 */
		cancel: function () {
			this.$dialog.dialog( 'close' );
		},

		/**
		 * Submit the feedback form.
		 */
		submit: function () {
			var subject, message,
				fb = this;

			function ok( result ) {
				if ( result.edit !== undefined ) {
					if ( result.edit.result === 'Success' ) {
						fb.displayThanks();
					} else {
						// unknown API result
						fb.displayError( 'feedback-error1' );
					}
				} else {
					// edit failed
					fb.displayError( 'feedback-error2' );
				}
			}

			function err() {
				// ajax request failed
				fb.displayError( 'feedback-error3' );
			}

			// Get the values to submit.
			subject = this.subjectInput.value;

			// We used to include "mw.html.escape( navigator.userAgent )" but there are legal issues
			// with posting this without their explicit consent
			message = this.messageInput.value;
			if ( message.indexOf( '~~~' ) === -1 ) {
				message += ' ~~~~';
			}

			this.displaySubmitting();

			this.api.newSection( this.title, subject, message, ok, err );
		},

		/**
		 * Modify the display form, and then open it, focusing interface on the subject.
		 * @param {Object} [contents] Prefilled contents for the feedback form.
		 * @param {string} [contents.subject] The subject of the feedback
		 * @param {string} [contents.message] The content of the feedback
		 */
		launch: function ( contents ) {
			this.displayForm( contents );
			this.$dialog.dialog( 'open' );
			this.subjectInput.focus();
		}

	};

}( mediaWiki, jQuery ) );
