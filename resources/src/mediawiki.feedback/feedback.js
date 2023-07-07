/*!
 * mediawiki.feedback
 *
 * @author Ryan Kaldari, 2010
 * @author Neil Kandalgaonkar, 2010-11
 * @author Moriel Schottlender, 2015
 * @since 1.19
 */
( function () {

	var FeedbackDialog = require( './FeedbackDialog.js' );

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
	 * This feature works with any content model that defines a
	 * `mw.messagePoster.MessagePoster`.
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
	 * @cfg {mw.Title} [title="Feedback"] The title of the page where you collect
	 *  feedback.
	 * @cfg {string} [apiUrl] api.php URL if the feedback page is on another wiki
	 * @cfg {string} [dialogTitleMessageKey="feedback-dialog-title"] Message key for the
	 *  title of the dialog box
	 * @cfg {mw.Uri|string} [bugsLink="//phabricator.wikimedia.org/maniphest/task/edit/form/1/"] URL where
	 *  bugs can be posted
	 * @cfg {boolean} [showUseragentCheckbox=false] Show a Useragent agreement checkbox as part of the form.
	 * @cfg {boolean} [useragentCheckboxMandatory=false] Make the Useragent checkbox mandatory.
	 * @cfg {string|jQuery} [useragentCheckboxMessage] Supply a custom message for the useragent checkbox.
	 *  defaults to the message 'feedback-terms'.
	 */
	mw.Feedback = function MwFeedback( config ) {
		config = config || {};

		this.dialogTitleMessageKey = config.dialogTitleMessageKey || 'feedback-dialog-title';

		// Feedback page title
		this.feedbackPageTitle = config.title || new mw.Title( 'Feedback' );

		this.messagePosterPromise = mw.messagePoster.factory.create( this.feedbackPageTitle, config.apiUrl );
		this.foreignApi = config.apiUrl ? new mw.ForeignApi( config.apiUrl ) : null;

		// Links
		this.bugsTaskSubmissionLink = config.bugsLink || '//phabricator.wikimedia.org/maniphest/task/edit/form/1/';

		// Terms of use
		this.useragentCheckboxShow = !!config.showUseragentCheckbox;
		this.useragentCheckboxMandatory = !!config.useragentCheckboxMandatory;
		this.useragentCheckboxMessage = config.useragentCheckboxMessage ||
			$( '<p>' ).append( mw.message( 'feedback-terms' ).parseDom() );

		// Message dialog
		this.thankYouDialog = new OO.ui.MessageDialog();
	};

	/* Initialize */
	OO.initClass( mw.Feedback );

	/**
	 * mw.Feedback Dialog
	 *
	 * @class
	 */
	mw.Feedback.Dialog = FeedbackDialog;

	/* Static Properties */
	mw.Feedback.static.windowManager = null;
	mw.Feedback.static.dialog = null;

	/* Methods */

	/**
	 * Respond to dialog submit event. If the information was
	 * submitted successfully, open a MessageDialog to thank the user.
	 *
	 * @param {string} status A status of the end of operation
	 *  of the main feedback dialog. Empty if the dialog was
	 *  dismissed with no action or the user followed the button
	 *  to the external task reporting site.
	 * @param {string} feedbackPageName
	 * @param {string} feedbackPageUrl
	 */
	mw.Feedback.prototype.onDialogSubmit = function ( status, feedbackPageName, feedbackPageUrl ) {
		var dialogConfig;

		if ( status !== 'submitted' ) {
			return;
		}

		dialogConfig = {
			title: mw.msg( 'feedback-thanks-title' ),
			message: $( '<span>' ).msg(
				'feedback-thanks',
				feedbackPageName,
				$( '<a>' ).attr( {
					target: '_blank',
					href: feedbackPageUrl
				} )
			),
			actions: [
				{
					action: 'accept',
					label: mw.msg( 'feedback-close' ),
					flags: 'primary'
				}
			]
		};

		// Show the message dialog
		this.constructor.static.windowManager.openWindow(
			this.thankYouDialog,
			dialogConfig
		);
	};

	/**
	 * Modify the display form, and then open it, focusing interface on the subject.
	 *
	 * @param {Object} [contents] Prefilled contents for the feedback form.
	 * @param {string} [contents.subject] The subject of the feedback, as plaintext
	 * @param {string} [contents.message] The content of the feedback, as wikitext
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
			$( document.body )
				.append( this.constructor.static.windowManager.$element );
		}
		// Open the dialog
		this.constructor.static.windowManager.openWindow(
			this.constructor.static.dialog,
			{
				// The following messages are used here
				// * feedback-dialog-title
				// * config.dialogTitleMessageKey ...
				title: mw.msg( this.dialogTitleMessageKey ),
				foreignApi: this.foreignApi,
				settings: {
					messagePosterPromise: this.messagePosterPromise,
					title: this.feedbackPageTitle,
					dialogTitleMessageKey: this.dialogTitleMessageKey,
					bugsTaskSubmissionLink: this.bugsTaskSubmissionLink,
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

}() );
