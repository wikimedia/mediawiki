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
	 * This feature works with classic MediaWiki pages
	 * and is not compatible with LiquidThreads or Flow.
	 *
	 * Minimal usage example:
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
			options.dialogTitleMessageKey = 'feedback-publish';
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

			// TODO: Use a stylesheet instead of these inline styles in the template
			// FIXME: This is using an un-namespaced filename
			this.$dialog = mw.template.get( 'mediawiki.feedback', 'dialog.html' ).render();
			this.$dialog.find( '.feedback-mode small p' ).msg(
				'feedback-bugornote',
				$bugNoteLink,
				fb.title.getNameText(),
				$feedbackPageLink.clone()
			);
			this.$dialog.find( '.feedback-form .subject span' ).msg( 'feedback-subject' );
			this.$dialog.find( '.feedback-form .message span' ).msg( 'feedback-message' );
			this.$dialog.find( '.feedback-form .terms span' ).msg( 'feedback-terms' );
			this.$dialog.find( '.feedback-bugs p' ).msg( 'feedback-bugcheck', $bugsListLink );
			this.$dialog.find( '.feedback-notice .incomplete span' ).msg( 'feedback-incomplete' );
			this.$dialog.find( '.feedback-notice .publishing span' ).msg( 'feedback-adding' );
			this.$dialog.find( '.feedback-thanks' ).msg( 'feedback-thanks', fb.title.getNameText(),
				$feedbackPageLink.clone() );

			this.$dialog.dialog( {
				width: 500,
				autoOpen: false,
				title: mw.message( this.dialogTitleMessageKey ).escaped(),
				modal: true,
				buttons: fb.buttons
			} );

			this.subjectInput = this.$dialog.find( 'input.feedback-subject' ).get( 0 );
			this.messageInput = this.$dialog.find( 'textarea.feedback-message' ).get( 0 );
			this.termsInput = this.$dialog.find( 'input.feedback-terms' ).get(0);
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
		 * Display the publishing section.
		 */
		displayPublishing: function () {
			this.display( 'publishing' );
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

		displayFormIncomplete: function () {
			var fb = this,
				buttons = {};
			this.display( 'incomplete' );
			buttons[ mw.msg( 'feedback-back' ) ] = function () {
				fb.displayForm( {
					'subject':  $( fb.subjectInput ).val(),
					'message': $( fb.messageInput ).val()
				} );
			};
			buttons[ mw.msg( 'feedback-doanyway' )] = function () {
				fb.publish();
			};
			this.$dialog.dialog( {
				buttons: buttons
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

			this.$dialog.dialog( { buttons: formButtons } ); // put the buttons back
			this.togglePublish();
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
		areTermsChecked: function () {
			return $( '.feedback-terms' ).prop( 'checked' );
		},

		togglePublish: function () {
			$( '#mw-feedback-publish' ).button(
				this.areTermsChecked() ? 'enable' : 'disable'
			);
		},

		isFormValid: function () {
			if (
				$.trim( $( '.feedback-subject' ).val() ) !== '' &&
				$.trim( $( '.feedback-message' ).val() ) !== '' &&
				this.areTermsChecked()
			) {
				return true;
			}
			return false;
		},

		cancel: function () {
			this.$dialog.dialog( 'close' );
		},

		/**
		 * Publish the feedback form.
		 */
		publish: function () {
			var subject, message,
				fb = this;

			// Get the values to submit.
			subject = $.trim( this.subjectInput.value );

			// We used to include "mw.html.escape( navigator.userAgent )" but there are legal issues
			// with posting this without their explicit consent
			message = $.trim( this.messageInput.value );

			function err() {
				// ajax request failed
				fb.displayError( 'feedback-error3' );
			}

			// Get the values to publish.
			subject = this.subjectInput.value;

			// Get the Browser info if permissions granted and append with message.
			message = ( this.termsInput.checked ?
				'<small>User agent: ' + mw.html.escape( navigator.userAgent ) + '</small>\n\n' :
				'' ) + this.messageInput.value;

			// Add signature.
			if ( message.indexOf( '~~~' ) === -1 ) {
				message += '\n\n~~~~';
			}

			this.displayPublishing();

			// Post the message, resolving redirects
			this.api.newSection(
				this.title,
				subject,
				message,
				{ redirect: true }
			)
			.done( function ( result ) {
				if ( result.edit.result === 'Success' ) {
					fb.displayThanks();
				} else {
					// unknown API result
					fb.displayError( 'feedback-error1' );
				}
			} )
			.fail( function ( code, result ) {
				if ( code === 'http' ) {
					// ajax request failed
					fb.displayError( 'feedback-error3' );
					mw.log.warn( 'Feedback report failed with HTTP error: ' +  result.textStatus );
				} else {
					fb.displayError( 'feedback-error2' );
					mw.log.warn( 'Feedback report failed with API error: ' +  code );
				}
			} );
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
