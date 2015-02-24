/*!
 * mediawiki.feedback
 *
 * @author Ryan Kaldari, 2010
 * @author Neil Kandalgaonkar, 2010-11
 * @author Moriel Schottlender, 2015
 * @since 1.19
 */
/*jshint es3:false */
/*global OO*/
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
	 * @param {Object} [config] Configuration object
	 * @cfg {mw.Api} [api] if omitted, will just create a standard API
	 * @cfg {mw.Title} [title="Feedback"] The title of the page where you collect
	 *  feedback.
	 * @cfg {string} [dialogTitleMessageKey="feedback-dialog-title"] Message key for the
	 *  title of the dialog box
	 * @cfg {mw.Uri|string} [bugsLink="//phabricator.wikimedia.org/maniphest/task/create/"] URL where
	 *  bugs can be posted
	 * @cfg {mw.Uri|string} [bugsListLink="//phabricator.wikimedia.org/maniphest/query/advanced"] URL
	 *  where bugs can be listed
	 * @cfg {boolean} [showUseragentCheckbox=false] Show a Useragent agreement checkbox as part of the form.
	 * @cfg {boolean} [useragentCheckboxMandatory=false] Make the Useragent checkbox mandatory.
	 * @cfg {string|jQuery} [useragentCheckboxMessage] Supply a custom message for the useragent checkbox.
	 *  defaults to a combination of 'feedback-terms' and 'feedback-termsofuse' which includes a link to the
	 *  wiki's Term of Use page.
	 */
	mw.Feedback = function MwFeedback( config ) {
		config = config || {};

		this.api = config.api || new mw.Api();
		this.dialogTitleMessageKey = config.dialogTitleMessageKey || 'feedback-dialog-title';

		// Feedback page title
		this.feedbackPageTitle = config.title || new mw.Title( 'Feedback' );

		// Links
		this.bugsTaskSubmissionLink = config.bugsLink || '//phabricator.wikimedia.org/maniphest/task/create/';
		this.bugsTaskListLink = config.bugsListLink || '//phabricator.wikimedia.org/maniphest/query/advanced';

		// Terms of use
		this.useragentCheckboxShow = !!config.showUseragentCheckbox;
		this.useragentCheckboxMandatory = !!config.useragentCheckboxMandatory;
		this.useragentCheckboxMessage = config.useragentCheckboxMessage ||
			$( '<p>' )
				.append( mw.msg( 'feedback-terms' ) )
				.add( $( '<p>' ).append( mw.message( 'feedback-termsofuse' ).parse() ) );

		// Message dialog
		this.thankYouDialog = new OO.ui.MessageDialog();
	};

	/* Initialize */
	OO.initClass( mw.Feedback );

	/* Static Properties */
	mw.Feedback.static.windowManager = null;
	mw.Feedback.static.dialog = null;

	/* Methods */

	/**
	 * Respond to dialog submit event. If the information was
	 * submitted, either successfully or with an error, open
	 * a MessageDialog to thank the user.
	 * @param {string} [status] A status of the end of operation
	 *  of the main feedback dialog. Empty if the dialog was
	 *  dismissed with no action or the user followed the button
	 *  to the external task reporting site.
	 */
	mw.Feedback.prototype.onDialogSubmit = function ( status ) {
		var dialogConfig = {};
		switch ( status ) {
			case 'submitted':
				dialogConfig = {
					title: mw.msg( 'feedback-thanks-title' ),
					message: $( '<span>' ).append(
						mw.message(
							'feedback-thanks',
							this.feedbackPageTitle.getNameText(),
							$( '<a>' )
								.attr( {
									target: '_blank',
									href: this.feedbackPageTitle.getUrl()
								} )
						).parse()
					),
					actions: [
						{
							action: 'accept',
							label: mw.msg( 'feedback-close' ),
							flags: 'primary'
						}
					]
				};
				break;
			case 'error1':
			case 'error2':
			case 'error3':
				dialogConfig = {
					title: mw.msg( 'feedback-error-title' ),
					message: mw.msg( 'feedback-' + status ),
					actions: [
						{
							action: 'accept',
							label: mw.msg( 'feedback-close' ),
							flags: 'primary'
						}
					]
				};
				break;
		}

		// Show the message dialog
		if ( !$.isEmptyObject( dialogConfig ) ) {
			this.constructor.static.windowManager.openWindow(
				this.thankYouDialog,
				dialogConfig
			);
		}
	};

	/**
	 * Modify the display form, and then open it, focusing interface on the subject.
	 *
	 * @param {Object} [contents] Prefilled contents for the feedback form.
	 * @param {string} [contents.subject] The subject of the feedback
	 * @param {string} [contents.message] The content of the feedback
	 */
	mw.Feedback.prototype.launch = function ( contents ) {
		// Dialog
		if ( !this.constructor.static.dialog ) {
			this.constructor.static.dialog = new mw.Feedback.Dialog();
			this.constructor.static.dialog.connect( this, { submit: 'onDialogSubmit' } );
		}
		if ( !this.constructor.static.windowManager ) {
			this.constructor.static.windowManager = new OO.ui.WindowManager();
			this.constructor.static.windowManager.addWindows( [
				this.constructor.static.dialog,
				this.thankYouDialog
			] );
			$( 'body' )
				.append( this.constructor.static.windowManager.$element );
		}
		// Open the dialog
		this.constructor.static.windowManager.openWindow(
			this.constructor.static.dialog,
			{
				title: mw.msg( this.dialogTitleMessageKey ),
				settings: {
					api: this.api,
					title: this.feedbackPageTitle,
					dialogTitleMessageKey: this.dialogTitleMessageKey,
					bugsTaskSubmissionLink: this.bugsTaskSubmissionLink,
					bugsTaskListLink: this.bugsTaskListLink,
					useragentCheckbox: {
						show: this.useragentCheckboxShow,
						mandatory: this.useragentCheckboxMandatory,
						message: this.useragentCheckboxMessage
					}
				},
				contents: contents
			}
		);
	};

	/**
	 * mw.Feedback Dialog
	 *
	 * @class
	 * @extends OO.ui.ProcessDialog
	 *
	 * @constructor
	 * @param {Object} config Configuration object
	 */
	mw.Feedback.Dialog = function mwFeedbackDialog( config ) {
		// Parent constructor
		mw.Feedback.Dialog.super.call( this, config );

		this.status = '';
		this.feedbackPageTitle = null;
		// Initialize
		this.$element.addClass( 'mwFeedback-Dialog' );
	};

	OO.inheritClass( mw.Feedback.Dialog, OO.ui.ProcessDialog );

	/* Static properties */
	mw.Feedback.Dialog.static.name = 'mwFeedbackDialog';
	mw.Feedback.Dialog.static.title = mw.msg( 'feedback-dialog-title' );
	mw.Feedback.Dialog.static.size = 'medium';
	mw.Feedback.Dialog.static.actions = [
		{
			action: 'submit',
			label: mw.msg( 'feedback-submit' ),
			flags: [ 'primary', 'constructive' ]
		},
		{
			action: 'external',
			label: mw.msg( 'feedback-external-bug-report-button' ),
			flags: 'constructive'
		},
		{
			action: 'cancel',
			label: mw.msg( 'feedback-cancel' ),
			flags: 'safe'
		}
	];

	/**
	 * @inheritdoc
	 */
	mw.Feedback.Dialog.prototype.initialize = function () {
		var feedbackSubjectFieldLayout, feedbackMessageFieldLayout,
			feedbackFieldsetLayout;

		// Parent method
		mw.Feedback.Dialog.super.prototype.initialize.call( this );

		this.feedbackPanel = new OO.ui.PanelLayout( {
			scrollable: false,
			expanded: false,
			padded: true
		} );

		this.$spinner = $( '<div>' )
			.addClass( 'feedback-spinner' );

		// Feedback form
		this.feedbackMessageLabel = new OO.ui.LabelWidget( {
			classes: [ 'mwFeedbackDialog-welcome-message' ]
		} );
		this.feedbackSubjectInput = new OO.ui.TextInputWidget( {
			multiline: false
		} );
		this.feedbackMessageInput = new OO.ui.TextInputWidget( {
			multiline: true
		} );
		feedbackSubjectFieldLayout = new OO.ui.FieldLayout( this.feedbackSubjectInput, {
			label: mw.msg( 'feedback-subject' )
		} );
		feedbackMessageFieldLayout = new OO.ui.FieldLayout( this.feedbackMessageInput, {
			label: mw.msg( 'feedback-message' )
		} );
		feedbackFieldsetLayout = new OO.ui.FieldsetLayout( {
			items: [ feedbackSubjectFieldLayout, feedbackMessageFieldLayout	],
			classes: [ 'mwFeedbackDialog-feedback-form' ]
		} );

		// Useragent terms of use
		this.useragentCheckbox = new OO.ui.CheckboxInputWidget();
		this.useragentFieldLayout = new OO.ui.FieldLayout( this.useragentCheckbox, {
			classes: [ 'mwFeedbackDialog-feedback-terms' ],
			align: 'inline'
		} );

		this.feedbackPanel.$element.append(
			this.feedbackMessageLabel.$element,
			feedbackFieldsetLayout.$element,
			this.useragentFieldLayout.$element
		);

		// Events
		this.feedbackSubjectInput.connect( this, { change: 'validateFeedbackForm' } );
		this.feedbackMessageInput.connect( this, { change: 'validateFeedbackForm' } );
		this.useragentCheckbox.connect( this, { change: 'validateFeedbackForm' } );

		this.$body.append( this.feedbackPanel.$element );
	};

	/**
	 * Validate the feedback form
	 */
	mw.Feedback.Dialog.prototype.validateFeedbackForm = function () {
		var isValid = (
			(
				!this.useragentMandatory ||
				(
					this.useragentMandatory &&
					this.useragentCheckbox.isSelected()
				)
			) &&
				(
					!!this.feedbackMessageInput.getValue() ||
					!!this.feedbackSubjectInput.getValue()
				)
			);

		this.actions.setAbilities( { submit:  isValid } );
	};

	/**
	 * @inheritdoc
	 */
	mw.Feedback.Dialog.prototype.getBodyHeight = function () {
		return this.feedbackPanel.$element.outerHeight( true );
	};

	/**
	 * @inheritdoc
	 */
	mw.Feedback.Dialog.prototype.getSetupProcess = function ( data ) {
		return mw.Feedback.Dialog.super.prototype.getSetupProcess.call( this, data )
			.next( function () {
				var plainMsg, parsedMsg,
					settings = data.settings;
				data.contents = data.contents || {};

				// Prefill subject/message
				this.feedbackSubjectInput.setValue( data.contents.subject );
				this.feedbackMessageInput.setValue( data.contents.message );

				this.status = '';
				this.api = settings.api;
				this.setBugReportLink( settings.bugsTaskSubmissionLink );
				this.feedbackPageTitle = settings.title;
				this.feedbackPageName = settings.title.getNameText();
				this.feedbackPageUrl = settings.title.getUrl();

				// Useragent checkbox
				if ( settings.useragentCheckbox.show ) {
					this.useragentFieldLayout.setLabel( settings.useragentCheckbox.message );
				}
				this.useragentMandatory = settings.useragentCheckbox.mandatory;
				this.useragentFieldLayout.toggle( settings.useragentCheckbox.show );

				// HACK: Setting a link in the messages doesn't work. There is already a report
				// about this, and the bug report offers a somewhat hacky work around that
				// includes setting a separate message to be parsed.
				// We want to make sure the user can configure both the title of the page and
				// a separate url, so this must be allowed to parse correctly.
				// See https://phabricator.wikimedia.org/T49395#490610
				mw.messages.set( {
					'feedback-dialog-temporary-message':
						'<a href="' + this.feedbackPageUrl + '" target="_blank">' + this.feedbackPageName + '</a>'
				} );
				plainMsg = mw.message( 'feedback-dialog-temporary-message' ).plain();
				mw.messages.set( { 'feedback-dialog-temporary-message-parsed': plainMsg } );
				parsedMsg = mw.message( 'feedback-dialog-temporary-message-parsed' );
				this.feedbackMessageLabel.setLabel(
					// Double-parse
					$( '<span>' )
						.append( mw.message( 'feedback-dialog-intro', parsedMsg ).parse() )
				);

				this.validateFeedbackForm();
			}, this );
	};

	/**
	 * @inheritdoc
	 */
	mw.Feedback.Dialog.prototype.getReadyProcess = function ( data ) {
		return mw.Feedback.Dialog.super.prototype.getReadyProcess.call( this, data )
			.next( function () {
				this.feedbackSubjectInput.focus();
			}, this );
	};

	/**
	 * @inheritdoc
	 */
	mw.Feedback.Dialog.prototype.getActionProcess = function ( action ) {
		if ( action === 'cancel' ) {
			return new OO.ui.Process( function () {
				this.close( { action: action } );
			}, this );
		} else if ( action === 'external' ) {
			return new OO.ui.Process( function () {
				// Open in a new window
				window.open( this.getBugReportLink(), '_blank' );
				// Close the dialog
				this.close();
			}, this );
		} else if ( action === 'submit' ) {
			return new OO.ui.Process( function () {
				var fb = this,
					userAgentMessage = ':' +
						'<small>' +
						mw.msg( 'feedback-useragent' ) +
						' ' +
						mw.html.escape( navigator.userAgent ) +
						'</small>\n\n',
					subject = this.feedbackSubjectInput.getValue(),
					message = this.feedbackMessageInput.getValue();

				// Add user agent if checkbox is selected
				if ( this.useragentCheckbox.isSelected() ) {
					message = userAgentMessage + message;
				}

				// Add signature if needed
				if ( message.indexOf( '~~~' ) === -1 ) {
					message += '\n\n~~~~';
				}

				// Post the message, resolving redirects
				this.pushPending();
				this.api.newSection(
					this.feedbackPageTitle,
					subject,
					message,
					{ redirect: true }
				)
				.done( function ( result ) {
					if ( result.edit.result === 'Success' ) {
						fb.status = 'submitted';
					} else {
						fb.status = 'error1';
					}
					fb.popPending();
					fb.close();
				} )
				.fail( function ( code, result ) {
					if ( code === 'http' ) {
						fb.status = 'error3';
						// ajax request failed
						mw.log.warn( 'Feedback report failed with HTTP error: ' +  result.textStatus );
					} else {
						fb.status = 'error2';
						mw.log.warn( 'Feedback report failed with API error: ' +  code );
					}
					fb.popPending();
					fb.close();
				} );
			}, this );
		}
		// Fallback to parent handler
		return mw.Feedback.Dialog.super.prototype.getActionProcess.call( this, action );
	};

	/**
	 * @inheritdoc
	 */
	mw.Feedback.Dialog.prototype.getTeardownProcess = function ( data ) {
		return mw.Feedback.Dialog.super.prototype.getTeardownProcess.call( this, data )
			.first( function () {
				this.emit( 'submit', this.status, this.feedbackPageName, this.feedbackPageUrl );
				// Cleanup
				this.status = '';
				this.feedbackPageTitle = null;
				this.feedbackSubjectInput.setValue( '' );
				this.feedbackMessageInput.setValue( '' );
				this.useragentCheckbox.setSelected( false );
			}, this );
	};

	/**
	 * Set the bug report link
	 * @param {string} link Link to the external bug report form
	 */
	mw.Feedback.Dialog.prototype.setBugReportLink = function ( link ) {
		this.bugReportLink = link;
	};

	/**
	 * Get the bug report link
	 * @returns {string} Link to the external bug report form
	 */
	mw.Feedback.Dialog.prototype.getBugReportLink = function () {
		return this.bugReportLink;
	};

}( mediaWiki, jQuery ) );
