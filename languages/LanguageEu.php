<?php
/**
  * Language file for Basque (Euskara)
  * Inherit from english
  * @package MediaWiki
  * @subpackage Language
  */

require_once( 'LanguageUtf8.php' );

/* private */ $wgNamespaceNamesEu = array(
	NS_MEDIA			=> 'Media',
	NS_SPECIAL			=> 'Aparteko',
	NS_MAIN				=> '',
	NS_TALK				=> 'Eztabaida',
	NS_USER				=> 'Lankide',
	NS_USER_TALK		=> 'Lankide_eztabaida',
	NS_PROJECT			=> $wgMetaNamespace,
	NS_PROJECT_TALK		=> $wgMetaNamespace.'_eztabaida',
	NS_IMAGE			=> 'Irudi',
	NS_IMAGE_TALK		=> 'Irudi_eztabaida',
	NS_MEDIAWIKI		=> 'MediaWiki',
	NS_MEDIAWIKI_TALK	=> 'MediaWiki_eztabaida',
) + $wgNamespaceNamesEn;

/* private */ $wgQuickbarSettingsEu = array(
	'Ezein ere', 'Eskuinean', 'Ezkerrean', 'Ezkerrean mugikor'
);

/* private */ $wgSkinNamesEu = array(
	'standard'		=> 'Lehenetsia',
	'nostalgia'		=> 'Nostalgia',
	'cologneblue'	=> 'Cologne Blue',
	'smarty'		=> 'Paddington',
	'montparnasse'	=> 'Montparnasse'
);

/* private */ $wgAllMessagesBr = array(
'1movedto2' => '$1 izenburua $2-en truke aldatu da.',
#'1movedto2_redir' => '$1 moved to $2 over redirect',
'about' => 'buruz',
'aboutpage' => 'Wikipedia:Wikipediari_buruz',
#'aboutsite' => 'About {{SITENAME}}',
#'accesskey-compareselectedversions' => 'v',
'accesskey-diff' => 'd',
#'accesskey-minoredit' => 'i',
#'accesskey-preview' => 'p',
#'accesskey-save' => 's',
#'accesskey-search' => 'f',
#'accmailtext' => 'The password for \'$1\' has been sent to $2.',
#'accmailtitle' => 'Password sent.',
#'acct_creation_throttle_hit' => 'Sorry, you have already created $1 accounts. You can\'t make any more.',
#'actioncomplete' => 'Action complete',
#'addedwatch' => 'Added to watchlist',
/* 'addedwatchtext' => 'The page "$1" has been added to your [[Special:Watchlist|watchlist]].
Future changes to this page and its associated Talk page will be listed there,
and the page will appear \'\'\'bolded\'\'\' in the [[Special:Recentchanges|list of recent changes]] to
make it easier to pick out.

<p>If you want to remove the page from your watchlist later, click "Stop watching" in the sidebar.', */
#'addgroup' => 'Add Group',
#'addgrouplogentry' => 'Added group $2',
#'addsection' => '+',
'administrators' => 'Wikipedia:Administratzaileak',
#'allarticles' => 'All articles',
#'allinnamespace' => 'All pages ($1 namespace)',
/* 'alllogstext' => 'Combined display of upload, deletion, protection, blocking, and sysop logs.
You can narrow down the view by selecting a log type, the user name, or the affected page.', */
'allmessages' => 'Mezu_guztiak',
#'allmessagescurrent' => 'Current text',
#'allmessagesdefault' => 'Default text',
#'allmessagesname' => 'Name',
#'allmessagesnotsupportedDB' => 'Special:AllMessages not supported because wgUseDatabaseMessages is off.',
#'allmessagesnotsupportedUI' => 'Your current interface language <b>$1</b> is not supported by Special:AllMessages at this site. ',
#'allmessagestext' => 'This is a list of system messages available in the MediaWiki: namespace.',
#'allnonarticles' => 'All non-articles',
#'allnotinnamespace' => 'All pages (not in $1 namespace)',
'allpages' => 'Orri guztiak',
#'allpagesfrom' => 'Display pages starting at:',
#'allpagesnext' => 'Next',
#'allpagesprev' => 'Previous',
#'allpagessubmit' => 'Go',
'alphaindexline' => '$1 -tik $2 -raino',
#'already_bureaucrat' => 'This user is already a bureaucrat',
#'already_steward' => 'This user is already a steward',
#'already_sysop' => 'This user is already an administrator',
'alreadyloggedin' => '<strong>Lankide $1, barruan zaude!</strong><br />',
/* 'alreadyrolled' => 'Cannot rollback last edit of [[$1]]
by [[User:$2|$2]] ([[User talk:$2|Talk]]); someone else has edited or rolled back the page already.

Last edit was by [[User:$3|$3]] ([[User talk:$3|Talk]]). ', */
'ancientpages' => 'Orri zaharrak',
#'and' => 'and',
#'anontalk' => 'Talk for this IP',
#'anontalkpagetext' => '----\'\'This is the discussion page for an anonymous user who has not created an account yet or who does not use it. We therefore have to use the numerical [[IP address]] to identify him/her. Such an IP address can be shared by several users. If you are an anonymous user and feel that irrelevant comments have been directed at you, please [[Special:Userlogin|create an account or log in]] to avoid future confusion with other anonymous users.\'\' ',
#'anonymous' => 'Anonymous user(s) of {{SITENAME}}',
#'apr' => 'Apr',
#'april' => 'April',
#'article' => 'Content page',
/* 'articleexists' => 'A page of that name already exists, or the
name you have chosen is not valid.
Please choose another name.', */
#'articlepage' => 'View content page',
#'aug' => 'Aug',
#'august' => 'August',
#'autoblocker' => 'Autoblocked because your IP address has been recently used by "[[User:$1|$1]]". The reason given for $1\'s block is: "\'\'\'$2\'\'\'"',
#'badaccess' => 'Permission error',
/* 'badaccesstext' => 'The action you have requested is limited
to users with the "$2" permission assigned.
See $1.', */
#'badarticleerror' => 'This action cannot be performed on this page.',
#'badfilename' => 'File name has been changed to "$1".',
#'badfiletype' => '".$1" is not a recommended image file format.',
#'badipaddress' => 'Invalid IP address',
#'badquery' => 'Badly formed search query',
/* 'badquerytext' => 'We could not process your query.
This is probably because you have attempted to search for a
word fewer than three letters long, which is not yet supported.
It could also be that you have mistyped the expression, for
example "fish and and scales".
Please try another query.', */
#'badretype' => 'The passwords you entered do not match.',
#'badtitle' => 'Bad title',
/* 'badtitletext' => 'The requested page title was invalid, empty, or
an incorrectly linked inter-language or inter-wiki title.', */
#'blanknamespace' => '(Main)',
'blockedtext' => 'Your user name or IP address has been blocked by $1.
The reason given is this:<br />\'\'$2\'\'<p>You may contact $1 or one of the other
[[Wikipedia:Administratzaileak|administrators]] to discuss the block.

Note that you may not use the "email this user" feature unless you have a valid email address registered in your [[Special:Preferences|user preferences]].

Your IP address is $3. Please include this address in any queries you make.

==Note to AOL users==
Due to continuing acts of vandalism by one particular AOL user, Wikipedia often blocks AOL proxies. Unfortunately, a single proxy server may be used by a large number of AOL users, and hence innocent AOL users are often inadvertently blocked. We apologise for any inconvenience caused.

If this happens to you, please email an administrator, using an AOL email address. Be sure to include the IP address given above.',
#'blockedtitle' => 'User is blocked',
#'blockip' => 'Block user',
#'blockipsuccesssub' => 'Block succeeded',
/* 'blockipsuccesstext' => '[[{{ns:Special}}:Contributions/$1|$1]] has been blocked.
<br />See[[{{ns:Special}}:Ipblocklist|IP block list]] to review blocks.', */
/* 'blockiptext' => 'Use the form below to block write access
from a specific IP address or username.
This should be done only only to prevent vandalism, and in
accordance with [[Project:Policy|policy]].
Fill in a specific reason below (for example, citing particular
pages that were vandalized).', */
#'blocklink' => 'block',
'blocklistline' => '$1, $2 blocked $3 (expires $4)',
#'blocklogentry' => 'blocked "[[$1]]" with an expiry time of $2',
#'blocklogpage' => 'Block_log',
/* 'blocklogtext' => 'This is a log of user blocking and unblocking actions. Automatically
blocked IP addresses are not listed. See the [[Special:Ipblocklist|IP block list]] for
the list of currently operational bans and blocks.', */
#'boardvote' => 'Wikimedia Board of Trustees election',
#'boardvote_days' => 'Days',
#'boardvote_dumplink' => 'Click here',
#'boardvote_edits' => 'Edits',
/* 'boardvote_entered' => 'Thank you, your vote has been recorded.

If you wish, you may record the following details. Your voting record is:

<pre>$1</pre>

It has been encrypted with the public key of the Election Administrators:

<pre>$2</pre>

The resulting encrypted version follows. It will be displayed publicly on [[Special:Boardvote/dump]]. 

<pre>$3</pre>

[[Special:Boardvote/entry|Back]]', */
/* 'boardvote_entry' => '<!--* [[Special:Boardvote/vote|Vote]]-->
* [[Special:Boardvote/list|List votes to date]]
* [[Special:Boardvote/dump|Dump encrypted election record]]', */
#'boardvote_footer' => '&nbsp;',
/* 'boardvote_intro' => '
<p>Welcome to the second elections for the Wikimedia Board of Trustees. We are
voting for two people to represent the community of users on the various
Wikimedia projects. They will help to determine the future direction
that the Wikimedia projects will take, individually and as a group, and
represent <em>your</em> interests and concerns to the Board of Trustees. They will
decide on ways to generate income and the allocation of moneys raised.</p>

<p>Please read the candidates\' statements and responses to queries carefully
before voting. Each of the candidates is a respected user, who has contributed
considerable time and effort to making these projects a welcoming environment
committed to the pursuit and free distribution of human knowledge.</p>

<p>You may vote for as many candidates as you want. The
candidate with the most votes in each position will be declared the winner of that
position. In the event of a tie, a run-off election will be held.</p>

<p>For more information, see:</p>
<ul><li><a href="http://meta.wikipedia.org/wiki/Election_FAQ_2005" class="external">Election FAQ</a></li>
<li><a href="http://meta.wikipedia.org/wiki/Election_Candidates_2005" class="external">Candidates</a></li></ul>
', */
/* 'boardvote_intro_change' => '<p>You have voted before. However you may change 
your vote using the form below. Please check the boxes next to each candidate whom 
you approve of.</p>', */
#'boardvote_ip' => 'IP',
/* 'boardvote_listintro' => '<p>This is a list of all votes which have been recorded 
to date. $1 for the encrypted data.</p>', */
#'boardvote_needadmin' => 'Only election administrators can perform this operation.',
/* 'boardvote_notloggedin' => 'You are not logged in. To vote, you must use an account
with at least $1 contributions before $2.', */
/* 'boardvote_notqualified' => 'Sorry, you made only $1 edits before $2. You 
need at least $3 to be able to vote.', */
#'boardvote_novotes' => 'Nobody has voted yet.',
#'boardvote_sitenotice' => '<a href="{{localurle:Special:Boardvote/vote}}">Wikimedia Board Elections</a>:  Vote open until July 12',
#'boardvote_strike' => 'Strike',
#'boardvote_time' => 'Time',
#'boardvote_ua' => 'User agent',
#'boardvote_unstrike' => 'Unstrike',
#'boardvote_user' => 'User',
'bold_sample' => 'Lodia',
'bold_tip' => 'Lodia',
#'booksources' => 'Book sources',
/* 'booksourcetext' => 'Below is a list of links to other sites that
sell new and used books, and may also have further information
about books you are looking for.', */
#'brokenredirects' => 'Broken Redirects',
#'brokenredirectstext' => 'The following redirects link to a non-existing pages.',
#'bugreports' => 'Bug reports',
#'bugreportspage' => 'Project:Bug_reports',
#'bureaucratlog' => 'Bureaucrat_log',
#'bureaucratlogentry' => 'Changed group membership for $1 from $2 to $3',
#'bydate' => 'by date',
#'byname' => 'by name',
#'bysize' => 'by size',
#'cachederror' => 'The following is a cached copy of the requested page, and may not be up to date.',
'cancel' => 'Bertan behera utzi',
#'cannotdelete' => 'Could not delete the page or file specified. (It may have already been deleted by someone else.)',
#'cantrollback' => 'Cannot revert edit; last contributor is only author of this page.',
'categories' => 'Kategoriak',
#'categoriespagetext' => 'The following categories exist in the wiki.',
'category' => 'kategoria',
'category_header' => '"$1" kategoriako artikuluak',
'categoryarticlecount' => 'Kategoria honetan $1 artikulu daude.',
#'categoryarticlecount1' => 'There is $1 article in this category.',
#'changed' => 'changed',
#'changegrouplogentry' => 'Changed group $2',
#'changepassword' => 'Change password',
#'changes' => 'changes',
#'checkuser' => 'Check user',
#'clearyourcache' => '\'\'\'Note:\'\'\' After saving, you have to clear your browser cache to see the changes: \'\'\'Mozilla:\'\'\' click \'\'Reload\'\' (or \'\'Ctrl-R\'\'), \'\'\'IE / Opera:\'\'\' \'\'Ctrl-F5\'\', \'\'\'Safari:\'\'\' \'\'Cmd-R\'\', \'\'\'Konqueror\'\'\' \'\'Ctrl-R\'\'.',
#'columns' => 'Columns',
#'compareselectedversions' => 'Compare selected versions',
#'confirm' => 'Confirm',
#'confirmdelete' => 'Confirm delete',
/* 'confirmdeletetext' => 'You are about to permanently delete a page
or image along with all of its history from the database.
Please confirm that you intend to do this, that you understand the
consequences, and that you are doing this in accordance with
[[Project:Policy]].', */
#'confirmemail' => 'Confirm E-mail address',
'confirmemail_body' => 'Someone, probably you from IP address $1, has registered an
account "$2" with this e-mail address on {{SITENAME}}.

To confirm that this account really does belong to you and activate
e-mail features on {{SITENAME}}, open this link in your browser:

$3

If this is *not* you, don\'t follow the link. This confirmation code
will expire at $4.',
#'confirmemail_error' => 'Something went wrong saving your confirmation.',
#'confirmemail_invalid' => 'Invalid confirmation code. The code may have expired.',
#'confirmemail_loggedin' => 'Your e-mail address has now been confirmed.',
#'confirmemail_send' => 'Mail a confirmation code',
#'confirmemail_sendfailed' => 'Could not send confirmation mail. Check address for invalid characters.',
#'confirmemail_sent' => 'Confirmation e-mail sent.',
#'confirmemail_subject' => '{{SITENAME}} e-mail address confirmation',
#'confirmemail_success' => 'Your e-mail address has been confirmed. You may now log in and enjoy the wiki.',
/* 'confirmemail_text' => 'This wiki requires that you validate your e-mail address
before using e-mail features. Activate the button below to send a confirmation
mail to your address. The mail will include a link containing a code; load the
link in your browser to confirm that your e-mail address is valid.', */
#'confirmprotect' => 'Confirm protection',
#'confirmprotecttext' => 'Do you really want to protect this page?',
#'confirmunprotect' => 'Confirm unprotection',
#'confirmunprotecttext' => 'Do you really want to unprotect this page?',
#'contextchars' => 'Context per line',
#'contextlines' => 'Lines per hit',
#'contribs-showhideminor' => '$1 minor edits',
#'contribslink' => 'contribs',
#'contribsub' => 'For $1',
'contributions' => 'Lankidearen ekarpenak',
#'copyright' => 'Content is available under $1.',
#'copyrightpage' => 'Project:Copyrights',
#'copyrightpagename' => '{{SITENAME}} copyright',
'copyrightwarning' => 'Mesedez, egin kontu Wikipedia-ri egindako ekarpen guztiak GNU Dokumentazio aske Lizentziaren barnean egin dela suposatzen dela (begira $1). Ez ezazu sakatu bidaltzeko botoia zure idatzia, baimenik gabe eta zure nahiaren aurka hedatzen ikustea nahi ez baduzu. Zu ere, idatzia zure kabuz idatzi duzula, edo publikora zabaldutako leku batetik ateratzen ari zarela agintzen ari zara. <strong>EZ EZAZU COPYRIGHT BATEN MENPEAN DAGOEN LANA BAIMENIK GABE ERABILI!</strong>',
/* 'copyrightwarning2' => 'Please note that all contributions to {{SITENAME}}
may be edited, altered, or removed by other contributors.
If you don\'t want your writing to be edited mercilessly, then don\'t submit it here.<br />
You are also promising us that you wrote this yourself, or copied it from a
public domain or similar free resource (see $1 for details).
<strong>DO NOT SUBMIT COPYRIGHTED WORK WITHOUT PERMISSION!</strong>', */
#'couldntremove' => 'Couldn\'t remove item \'$1\'...',
#'createaccount' => 'Create new account',
#'createaccountmail' => 'by email',
#'createarticle' => 'Create article',
#'created' => 'created',
#'creditspage' => 'Page credits',
'cur' => 'azk',
'currentevents' => 'Gaurkotasun',
#'currentevents-url' => 'Current events',
'currentrev' => 'Azken eguneratzea',
#'currentrevisionlink' => 'view current revision',
#'data' => 'Data',
#'databaseerror' => 'Database error',
#'dateformat' => 'Date format',
/* 'dberrortext' => 'A database query syntax error has occurred.
This may indicate a bug in the software.
The last attempted database query was:
<blockquote><tt>$1</tt></blockquote>
from within function "<tt>$2</tt>".
MySQL returned error "<tt>$3: $4</tt>".', */
/* 'dberrortextcl' => 'A database query syntax error has occurred.
The last attempted database query was:
"$1"
from within function "$2".
MySQL returned error "$3: $4".
', */
'deadendpages' => 'Artikulu itsuak',
#'debug' => 'Debug',
#'dec' => 'Dec',
#'december' => 'December',
#'default' => 'default',
#'defaultns' => 'Search in these namespaces by default:',
#'defemailsubject' => '{{SITENAME}} e-mail',
#'delete' => 'Delete',
#'delete_and_move' => 'Delete and move',
#'delete_and_move_reason' => 'Deleted to make way for move',
/* 'delete_and_move_text' => '==Deletion required==

The destination article "[[$1]]" already exists. Do you want to delete it to make way for the move?', */
#'deletecomment' => 'Reason for deletion',
#'deletedarticle' => 'deleted "[[$1]]"',
#'deletedrev' => '[deleted]',
#'deletedrevision' => 'Deleted old revision $1.',
/* 'deletedtext' => '"$1" has been deleted.
See $2 for a record of recent deletions.', */
#'deleteimg' => 'del',
#'deleteimgcompletely' => 'Delete all revisions of this file',
#'deletepage' => 'Delete page',
#'deletesub' => '(Deleting "$1")',
'deletethispage' => 'Orria ezabatu',
#'deletionlog' => 'deletion log',
#'dellogpage' => 'Deletion_log',
#'dellogpagetext' => 'Below is a list of the most recent deletions.',
#'destfilename' => 'Destination filename',
/* 'developertext' => 'The action you have requested can only be
performed by users with "developer" capability.
See $1.', */
#'developertitle' => 'Developer access required',
#'diff' => 'diff',
#'difference' => '(Difference between revisions)',
#'disambiguations' => 'Disambiguation pages',
#'disambiguationspage' => 'Template:disambig',
#'disambiguationstext' => 'The following pages link to a <i>disambiguation page</i>. They should link to the appropriate topic instead.<br />A page is treated as disambiguation if it is linked from $1.<br />Links from other namespaces are <i>not</i> listed here.',
#'disclaimerpage' => 'Project:General_disclaimer',
#'disclaimers' => 'Disclaimers',
#'doubleredirects' => 'Double redirects',
#'doubleredirectstext' => 'Each row contains links to the first and second redirect, as well as the first line of the second redirect text, usually giving the "real" target page, which the first redirect should point to.',
/* 'eauthentsent' => 'A confirmation email has been sent to the nominated email address.
Before any other mail is sent to the account, you will have to follow the instructions in the email,
to confirm that the account is actually yours.', */
#'edit' => 'Edit this page',
#'edit-externally' => 'Edit this file using an external application',
#'edit-externally-help' => 'See the [http://meta.wikimedia.org/wiki/Help:External_editors setup instructions] for more information.',
#'editcomment' => 'The edit comment was: "<i>$1</i>".',
#'editconflict' => 'Edit conflict: $1',
#'editcurrent' => 'Edit the current version of this page',
#'editgroup' => 'Edit Group',
'edithelp' => 'Editatzeko laguntza',
'edithelppage' => 'Wikipedia:Editatzeko laguntza',
'editing' => '"$1" editatzen',
#'editingcomment' => 'Editing $1 (comment)',
/* 'editingold' => '<strong>WARNING: You are editing an out-of-date
revision of this page.
If you save it, any changes made since this revision will be lost.</strong>', */
#'editingsection' => 'Editing $1 (section)',
'editsection' => 'editatu',
'editthispage' => 'Orria editatu',
#'editusergroup' => 'Edit User Groups',
#'email' => 'Email',
#'emailauthenticated' => 'Your email address was authenticated on $1.',
#'emailconfirmlink' => 'Confirm your e-mail address',
#'emailflag' => 'Disable e-mail from other users',
/* 'emailforlost' => 'Fields marked with superscripts are optional.  Storing an email address enables people to contact you through the website without you having to reveal your
email address to them, and it can be used to send you a new password if you forget it.<br /><br />Your real name, if you choose to provide it, will be used for giving you attribution for your work.', */
#'emailfrom' => 'From',
#'emailmessage' => 'Message',
/* 'emailnotauthenticated' => 'Your email address is <strong>not yet authenticated</strong>. No email
will be sent for any of the following features.', */
#'emailpage' => 'E-mail user',
/* 'emailpagetext' => 'If this user has entered a valid e-mail address in
his or her user preferences, the form below will send a single message.
The e-mail address you entered in your user preferences will appear
as the "From" address of the mail, so the recipient will be able
to reply.', */
#'emailsend' => 'Send',
#'emailsent' => 'E-mail sent',
#'emailsenttext' => 'Your e-mail message has been sent.',
#'emailsubject' => 'Subject',
#'emailto' => 'To',
#'emailuser' => 'E-mail this user',
#'emptyfile' => 'The file you uploaded seems to be empty. This might be due to a typo in the file name. Please check whether you really want to upload this file.',
/* 'enotif_body' => 'Dear $WATCHINGUSERNAME,

the {{SITENAME}} page $PAGETITLE has been $CHANGEDORCREATED on $PAGEEDITDATE by $PAGEEDITOR, see $PAGETITLE_URL for the current version.

$NEWPAGE

Editor\'s summary: $PAGESUMMARY $PAGEMINOREDIT

Contact the editor:
mail: $PAGEEDITOR_EMAIL
wiki: $PAGEEDITOR_WIKI

There will be no other notifications in case of further changes unless you visit this page. You could also reset the notification flags for all your watched pages on your watchlist.

             Your friendly {{SITENAME}} notification system

--
To change your watchlist settings, visit
{{SERVER}}{{localurl:Special:Watchlist/edit}}

Feedback and further assistance:
{{SERVER}}{{localurl:Help:Contents}}', */
#'enotif_lastvisited' => 'See $1 for all changes since your last visit.',
#'enotif_mailer' => '{{SITENAME}} Notification Mailer',
#'enotif_newpagetext' => 'This is a new page.',
#'enotif_reset' => 'Mark all pages visited',
#'enotif_subject' => '{{SITENAME}} page $PAGETITLE has been $CHANGEDORCREATED by $PAGEEDITOR',
/* 'enterlockreason' => 'Enter a reason for the lock, including an estimate
of when the lock will be released', */
#'error' => 'Error',
#'errorpagetitle' => 'Error',
#'exbeforeblank' => 'content before blanking was: \'$1\'',
#'exblank' => 'page was empty',
#'excontent' => 'content was: \'$1\'',
#'excontentauthor' => 'content was: \'$1\' (and the only contributor was \'$2\')',
#'exif-aperturevalue' => 'Aperture',
#'exif-artist' => 'Author',
#'exif-bitspersample' => 'Bits per component',
#'exif-brightnessvalue' => 'Brightness',
#'exif-cfapattern' => 'CFA pattern',
#'exif-colorspace' => 'Color space',
#'exif-colorspace-1' => 'sRGB',
#'exif-colorspace-ffff.h' => 'FFFF.H',
#'exif-componentsconfiguration' => 'Meaning of each component',
#'exif-componentsconfiguration-0' => 'does not exist',
#'exif-componentsconfiguration-1' => 'Y',
#'exif-componentsconfiguration-2' => 'Cb',
#'exif-componentsconfiguration-3' => 'Cr',
#'exif-componentsconfiguration-4' => 'R',
#'exif-componentsconfiguration-5' => 'G',
#'exif-componentsconfiguration-6' => 'B',
#'exif-compressedbitsperpixel' => 'Image compression mode',
#'exif-compression' => 'Compression scheme',
#'exif-compression-1' => 'Uncompressed',
#'exif-compression-6' => 'JPEG',
#'exif-contrast' => 'Contrast',
#'exif-contrast-0' => 'Normal',
#'exif-contrast-1' => 'Soft',
#'exif-contrast-2' => 'Hard',
#'exif-copyright' => 'Copyright holder',
#'exif-customrendered' => 'Custom image processing',
#'exif-customrendered-0' => 'Normal process',
#'exif-customrendered-1' => 'Custom process',
#'exif-datetime' => 'File change date and time',
#'exif-datetimedigitized' => 'Date and time of digitizing',
#'exif-datetimeoriginal' => 'Date and time of data generation',
#'exif-devicesettingdescription' => 'Device settings description',
#'exif-digitalzoomratio' => 'Digital zoom ratio',
#'exif-exifversion' => 'Exif version',
#'exif-exposurebiasvalue' => 'Exposure bias',
#'exif-exposureindex' => 'Exposure index',
#'exif-exposuremode' => 'Exposure mode',
#'exif-exposuremode-0' => 'Auto exposure',
#'exif-exposuremode-1' => 'Manual exposure',
#'exif-exposuremode-2' => 'Auto bracket',
#'exif-exposureprogram' => 'Exposure Program',
#'exif-exposureprogram-0' => 'Not defined',
#'exif-exposureprogram-1' => 'Manual',
#'exif-exposureprogram-2' => 'Normal program',
#'exif-exposureprogram-3' => 'Aperture priority',
#'exif-exposureprogram-4' => 'Shutter priority',
#'exif-exposureprogram-5' => 'Creative program (biased toward depth of field)',
#'exif-exposureprogram-6' => 'Action program (biased toward fast shutter speed)',
#'exif-exposureprogram-7' => 'Portrait mode (for closeup photos with the background out of focus)',
#'exif-exposureprogram-8' => 'Landscape mode (for landscape photos with the background in focus)',
#'exif-exposuretime' => 'Exposure time',
#'exif-filesource' => 'File source',
#'exif-filesource-3' => 'DSC',
#'exif-flash' => 'Flash',
#'exif-flashenergy' => 'Flash energy',
#'exif-flashpixversion' => 'Supported Flashpix version',
#'exif-fnumber' => 'F Number',
#'exif-focallength' => 'Lens focal length',
#'exif-focallengthin35mmfilm' => 'Focal length in 35 mm film',
#'exif-focalplaneresolutionunit' => 'Focal plane resolution unit',
#'exif-focalplaneresolutionunit-2' => 'inches',
#'exif-focalplanexresolution' => 'Focal plane X resolution',
#'exif-focalplaneyresolution' => 'Focal plane Y resolution',
#'exif-gaincontrol' => 'Scene control',
#'exif-gaincontrol-0' => 'None',
#'exif-gaincontrol-1' => 'Low gain up',
#'exif-gaincontrol-2' => 'High gain up',
#'exif-gaincontrol-3' => 'Low gain down',
#'exif-gaincontrol-4' => 'High gain down',
#'exif-gpsaltitude' => 'Altitude',
#'exif-gpsaltituderef' => 'Altitude reference',
#'exif-gpsareainformation' => 'Name of GPS area',
#'exif-gpsdatestamp' => 'GPS date',
#'exif-gpsdestbearing' => 'Bearing of destination',
#'exif-gpsdestbearingref' => 'Reference for bearing of destination',
#'exif-gpsdestdistance' => 'Distance to destination',
#'exif-gpsdestdistanceref' => 'Reference for distance to destination',
#'exif-gpsdestlatitude' => 'Latitude destination',
#'exif-gpsdestlatituderef' => 'Reference for latitude of destination',
#'exif-gpsdestlongitude' => 'Longitude of destination',
#'exif-gpsdestlongituderef' => 'Reference for longitude of destination',
#'exif-gpsdifferential' => 'GPS differential correction',
#'exif-gpsdirection-m' => 'Magnetic direction',
#'exif-gpsdirection-t' => 'True direction',
#'exif-gpsdop' => 'Measurement precision',
#'exif-gpsimgdirection' => 'Direction of image',
#'exif-gpsimgdirectionref' => 'Reference for direction of image',
#'exif-gpslatitude' => 'Latitude',
#'exif-gpslatitude-n' => 'North latitude',
#'exif-gpslatitude-s' => 'South latitude',
#'exif-gpslatituderef' => 'North or South Latitude',
#'exif-gpslongitude' => 'Longitude',
#'exif-gpslongitude-e' => 'East longitude',
#'exif-gpslongitude-w' => 'West longitude',
#'exif-gpslongituderef' => 'East or West Longitude',
#'exif-gpsmapdatum' => 'Geodetic survey data used',
#'exif-gpsmeasuremode' => 'Measurement mode',
#'exif-gpsmeasuremode-2' => '2-dimensional measurement',
#'exif-gpsmeasuremode-3' => '3-dimensional measurement',
#'exif-gpsprocessingmethod' => 'Name of GPS processing method',
#'exif-gpssatellites' => 'Satellites used for measurement',
#'exif-gpsspeed' => 'Speed of GPS receiver',
#'exif-gpsspeed-k' => 'Kilometres per hour',
#'exif-gpsspeed-m' => 'Miles per hour',
#'exif-gpsspeed-n' => 'Knots',
#'exif-gpsspeedref' => 'Speed unit',
#'exif-gpsstatus' => 'Receiver status',
#'exif-gpsstatus-a' => 'Measurement in progress',
#'exif-gpsstatus-v' => 'Measurement interoperability',
#'exif-gpstimestamp' => 'GPS time (atomic clock)',
#'exif-gpstrack' => 'Direction of movement',
#'exif-gpstrackref' => 'Reference for direction of movement',
#'exif-gpsversionid' => 'GPS tag version',
#'exif-imagedescription' => 'Image title',
#'exif-imagelength' => 'Height',
#'exif-imageuniqueid' => 'Unique image ID',
#'exif-imagewidth' => 'Width',
#'exif-isospeedratings' => 'ISO speed rating',
#'exif-jpeginterchangeformat' => 'Offset to JPEG SOI',
#'exif-jpeginterchangeformatlength' => 'Bytes of JPEG data',
#'exif-lightsource' => 'Light source',
#'exif-lightsource-0' => 'Unknown',
#'exif-lightsource-1' => 'Daylight',
#'exif-lightsource-10' => 'Clody weather',
#'exif-lightsource-11' => 'Shade',
#'exif-lightsource-12' => 'Daylight fluorescent (D 5700 – 7100K)',
#'exif-lightsource-13' => 'Day white fluorescent (N 4600 – 5400K)',
#'exif-lightsource-14' => 'Cool white fluorescent (W 3900 – 4500K)',
#'exif-lightsource-15' => 'White fluorescent (WW 3200 – 3700K)',
#'exif-lightsource-17' => 'Standard light A',
#'exif-lightsource-18' => 'Standard light B',
#'exif-lightsource-19' => 'Standard light C',
#'exif-lightsource-2' => 'Fluorescent',
#'exif-lightsource-20' => 'D55',
#'exif-lightsource-21' => 'D65',
#'exif-lightsource-22' => 'D75',
#'exif-lightsource-23' => 'D50',
#'exif-lightsource-24' => 'ISO studio tungsten',
#'exif-lightsource-255' => 'Other light source',
#'exif-lightsource-3' => 'Tungsten (incandescent light)',
#'exif-lightsource-4' => 'Flash',
#'exif-lightsource-9' => 'Fine weather',
#'exif-make' => 'Camera manufacturer',
#'exif-make-value' => '$1',
#'exif-makernote' => 'Manufacturer notes',
#'exif-maxaperturevalue' => 'Maximum land aperture',
#'exif-meteringmode' => 'Metering mode',
#'exif-meteringmode-0' => 'Unknown',
#'exif-meteringmode-1' => 'Average',
#'exif-meteringmode-2' => 'CenterWeightedAverage',
#'exif-meteringmode-255' => 'Other',
#'exif-meteringmode-3' => 'Spot',
#'exif-meteringmode-4' => 'MultiSpot',
#'exif-meteringmode-5' => 'Pattern',
#'exif-meteringmode-6' => 'Partial',
#'exif-model' => 'Camera model',
#'exif-model-value' => '$1',
#'exif-oecf' => 'Optoelectronic conversion factor',
#'exif-orientation' => 'Orientation',
#'exif-orientation-1' => 'Normal',
#'exif-orientation-2' => 'Flipped horizontally',
#'exif-orientation-3' => 'Rotated 180°',
#'exif-orientation-4' => 'Flipped vertically',
#'exif-orientation-5' => 'Rotated 90° CCW and flipped vertically',
#'exif-orientation-6' => 'Rotated 90° CW',
#'exif-orientation-7' => 'Rotated 90° CW and flipped vertically',
#'exif-orientation-8' => 'Rotated 90° CCW',
#'exif-photometricinterpretation' => 'Pixel composition',
#'exif-photometricinterpretation-1' => 'RGB',
#'exif-photometricinterpretation-6' => 'YCbCr',
#'exif-pixelxdimension' => 'Valind image height',
#'exif-pixelydimension' => 'Valid image width',
#'exif-planarconfiguration' => 'Data arrangement',
#'exif-planarconfiguration-1' => 'chunky format',
#'exif-planarconfiguration-2' => 'planar format',
#'exif-primarychromaticities' => 'Chromaticities of primarities',
#'exif-referenceblackwhite' => 'Pair of black and white reference values',
#'exif-relatedsoundfile' => 'Related audio file',
#'exif-resolutionunit' => 'Unit of X and Y resolution',
#'exif-rowsperstrip' => 'Number of rows per strip',
#'exif-samplesperpixel' => 'Number of components',
#'exif-saturation' => 'Saturation',
#'exif-saturation-0' => 'Normal',
#'exif-saturation-1' => 'Low saturation',
#'exif-saturation-2' => 'High saturation',
#'exif-scenecapturetype' => 'Scene capture type',
#'exif-scenecapturetype-0' => 'Standard',
#'exif-scenecapturetype-1' => 'Landscape',
#'exif-scenecapturetype-2' => 'Portrait',
#'exif-scenecapturetype-3' => 'Night scene',
#'exif-scenetype' => 'Scene type',
#'exif-scenetype-1' => 'A directly photographed image',
#'exif-sensingmethod' => 'Sensing method',
#'exif-sensingmethod-1' => 'Undefined',
#'exif-sensingmethod-2' => 'One-chip color area sensor',
#'exif-sensingmethod-3' => 'Two-chip color area sensor',
#'exif-sensingmethod-4' => 'Three-chip color area sensor',
#'exif-sensingmethod-5' => 'Color sequential area sensor',
#'exif-sensingmethod-7' => 'Trilinear sensor',
#'exif-sensingmethod-8' => 'Color sequential linear sensor',
#'exif-sharpness' => 'Sharpness',
#'exif-sharpness-0' => 'Normal',
#'exif-sharpness-1' => 'Soft',
#'exif-sharpness-2' => 'Hard',
#'exif-shutterspeedvalue' => 'Shutter speed',
#'exif-software' => 'Software used',
#'exif-software-value' => '$1',
#'exif-spatialfrequencyresponse' => 'Spatial frequency response',
#'exif-spectralsensitivity' => 'Spectral sensitivity',
#'exif-stripbytecounts' => 'Bytes per compressed strip',
#'exif-stripoffsets' => 'Image data location',
#'exif-subjectarea' => 'Subject area',
#'exif-subjectdistance' => 'Subject distance',
#'exif-subjectdistance-value' => '$1 metres',
#'exif-subjectdistancerange' => 'Subject distance range',
#'exif-subjectdistancerange-0' => 'Unknown',
#'exif-subjectdistancerange-1' => 'Macro',
#'exif-subjectdistancerange-2' => 'Close view',
#'exif-subjectdistancerange-3' => 'Distant view',
#'exif-subjectlocation' => 'Subject location',
#'exif-subsectime' => 'DateTime subseconds',
#'exif-subsectimedigitized' => 'DateTimeDigitized subseconds',
#'exif-subsectimeoriginal' => 'DateTimeOriginal subseconds',
#'exif-transferfunction' => 'Transfer function',
#'exif-usercomment' => 'User comments',
#'exif-whitebalance' => 'White Balance',
#'exif-whitebalance-0' => 'Auto white balance',
#'exif-whitebalance-1' => 'Manual white balance',
#'exif-whitepoint' => 'White point chromaticity',
#'exif-xresolution' => 'Horizontal resolution',
#'exif-xyresolution-c' => '$1 dpc',
#'exif-xyresolution-i' => '$1 dpi',
#'exif-ycbcrcoefficients' => 'Color space transformation matrix coefficients',
#'exif-ycbcrpositioning' => 'Y and C positioning',
#'exif-ycbcrsubsampling' => 'Subsampling ratio of Y to C',
#'exif-yresolution' => 'Vertical resolution',
#'expiringblock' => 'expires $1',
/* 'explainconflict' => 'Someone else has changed this page since you
started editing it.
The upper text area contains the page text as it currently exists.
Your changes are shown in the lower text area.
You will have to merge your changes into the existing text.
<b>Only</b> the text in the upper text area will be saved when you
press "Save page".<br />', */
#'export' => 'Export pages',
#'exportcuronly' => 'Include only the current revision, not the full history',
/* 'exporttext' => 'You can export the text and editing history of a particular page or
set of pages wrapped in some XML. In the future, this may then be imported into another
wiki running MediaWiki software, although there is no support for this feature in the
current version.

To export article pages, enter the titles in the text box below, one title per line, and
select whether you want the current version as well as all old versions, with the page
history lines, or just the current version with the info about the last edit.

In the latter case you can also use a link, e.g. [[{{ns:Special}}:Export/Train]] for the
article [[Train]].
', */
#'externaldberror' => 'There was either an external authentication database error or you are not allowed to update your external account.',
#'extlink_sample' => 'http://www.example.com link title',
#'extlink_tip' => 'External link (remember http:// prefix)',
#'faq' => 'FAQ',
#'faqpage' => 'Project:FAQ',
#'feb' => 'Feb',
#'february' => 'February',
#'feedlinks' => 'Feed:',
#'filecopyerror' => 'Could not copy file "$1" to "$2".',
#'filedeleteerror' => 'Could not delete file "$1".',
#'filedesc' => 'Summary',
#'fileexists' => 'A file with this name exists already, please check $1 if you are not sure if you want to change it.',
#'fileinfo' => '$1KB, MIME type: <code>$2</code>',
#'filemissing' => 'File missing',
#'filename' => 'Filename',
#'filenotfound' => 'Could not find file "$1".',
#'filerenameerror' => 'Could not rename file "$1" to "$2".',
#'files' => 'Files',
#'filesource' => 'Source',
#'filestatus' => 'Copyright status',
/* 'fileuploaded' => 'File $1 uploaded successfully.
Please follow this link: $2 to the description page and fill
in information about the file, such as where it came from, when it was
created and by whom, and anything else you may know about it. If this is an image, you can insert it like this: <tt><nowiki>[[Image:$1|thumb|Description]]</nowiki></tt>', */
#'formerror' => 'Error: could not submit form',
#'friday' => 'Friday',
#'geo' => 'GEO coordinates',
#'getimagelist' => 'fetching file list',
'go' => 'Joan',
/* 'googlesearch' => '
<form method="get" action="http://www.google.com/search" id="googlesearch">
    <input type="hidden" name="domains" value="{{SERVER}}" />
    <input type="hidden" name="num" value="50" />
    <input type="hidden" name="ie" value="$2" />
    <input type="hidden" name="oe" value="$2" />

    <input type="text" name="q" size="31" maxlength="255" value="$1" />
    <input type="submit" name="btnG" value="$3" />
  <div>
    <input type="radio" name="sitesearch" id="gwiki" value="{{SERVER}}" checked="checked" /><label for="gwiki">{{SITENAME}}</label>
    <input type="radio" name="sitesearch" id="gWWW" value="" /><label for="gWWW">WWW</label>
  </div>
</form>', */
#'group-admin-desc' => 'Trusted users able to block users and delete articles',
#'group-admin-name' => 'Administrator',
#'group-anon-desc' => 'Anonymous users',
#'group-anon-name' => 'Anonymous',
#'group-bureaucrat-desc' => 'The bureaucrat group is able to make sysops',
#'group-bureaucrat-name' => 'Bureaucrat',
#'group-loggedin-desc' => 'General logged in users',
#'group-loggedin-name' => 'User',
#'group-steward-desc' => 'Full access',
#'group-steward-name' => 'Steward',
#'groups' => 'User groups',
#'groups-addgroup' => 'Add group',
#'groups-already-exists' => 'A group of that name already exists',
#'groups-editgroup' => 'Edit group',
#'groups-editgroup-description' => 'Group description (max 255 characters):<br />',
#'groups-editgroup-name' => 'Group name:',
/* 'groups-editgroup-preamble' => 'If the name or description starts with a colon, the
remainder will be treated as a message name, and hence the text will be localised
using the MediaWiki namespace', */
#'groups-existing' => 'Existing groups',
#'groups-group-edit' => 'Existing groups:',
#'groups-lookup-group' => 'Manage group rights',
#'groups-noname' => 'Please specify a valid group name',
#'groups-tableheader' => 'ID || Name || Description || Rights',
#'guesstimezone' => 'Fill in from browser',
'headline_sample' => 'Goiburuko',
'headline_tip' => '2. mailako goiburukoa',
'help' => 'Laguntza',
'helppage' => 'Wikipedia:Laguntza',
'hide' => 'ezkutatu',
'hidetoc' => 'ezkutatu',
#'hist' => 'hist',
#'histfirst' => 'Earliest',
#'histlast' => 'Latest',
'histlegend' => 'Legenda: betsionen artean desberdintasunak: (azk) = azkena, (aur) = aurrekoa; 
t = edikateka txikiak',
'history' => 'Orriaren historia',
#'history_copyright' => '-',
'history_short' => 'Historia',
#'historywarning' => 'Warning: The page you are about to delete has a history: ',
'hr_tip' => 'Lerro horizontal (neurritasunaz)',
#'ignorewarning' => 'Ignore warning and save file anyway.',
#'illegalfilename' => 'The filename "$1" contains characters that are not allowed in page titles. Please rename the file and try uploading it again.',
'ilsubmit' => 'Bilatu',
'image_sample' => 'Adibide.png',
#'image_tip' => 'Embedded image',
#'imagelinks' => 'Links',
'imagelist' => 'Irudien zerrenda',
#'imagelistall' => 'all',
#'imagelisttext' => 'Below is a list of $1 files sorted $2.',
#'imagemaxsize' => 'Limit images on image description pages to: ',
#'imagepage' => 'View image page',
#'imagereverted' => 'Revert to earlier version was successful.',
#'imgdelete' => 'del',
#'imgdesc' => 'desc',
/* 'imghistlegend' => 'Legend: (cur) = this is the current file, (del) = delete
this old version, (rev) = revert to this old version.
<br /><i>Click on date to see the file uploaded on that date</i>.', */
#'imghistory' => 'File history',
#'imglegend' => 'Legend: (desc) = show/edit file description.',
#'immobile_namespace' => 'Destination title is of a special type; cannot move pages into that namespace.',
#'import' => 'Import pages',
#'importfailed' => 'Import failed: $1',
#'importhistoryconflict' => 'Conflicting history revision exists (may have imported this page before)',
#'importinterwiki' => 'Transwiki import',
#'importnosources' => 'No transwiki import sources have been defined and direct history uploads are disabled.',
#'importnotext' => 'Empty or no text',
#'importsuccess' => 'Import succeeded!',
#'importtext' => 'Please export the file from the source wiki using the Special:Export utility, save it to your disk and upload it here.',
#'infiniteblock' => 'infinite',
#'info_short' => 'Information',
#'infobox' => 'Click a button to get an example text',
#'infobox_alert' => 'Please enter the text you want to be formatted.\n It will be shown in the infobox for copy and pasting.\nExample:\n$1\nwill become:\n$2',
#'infosubtitle' => 'Information for page',
#'internalerror' => 'Internal error',
#'intl' => 'Interlanguage links',
/* 'invalidemailaddress' => 'The email address cannot be accepted as it appears to have an invalid
format. Please enter a well-formatted address or empty that field.', */
#'invert' => 'Invert selection',
/* 'ip_range_invalid' => 'Invalid IP range.
', */
#'ipaddress' => 'IP Address',
#'ipadressorusername' => 'IP Address or username',
#'ipb_expiry_invalid' => 'Expiry time invalid.',
#'ipbexpiry' => 'Expiry',
#'ipblocklist' => 'List of blocked IP addresses and usernames',
#'ipblocklistempty' => 'The blocklist is empty.',
#'ipboptions' => '2 hours:2 hours,1 day:1 day,3 days:3 days,1 week:1 week,2 weeks:2 weeks,1 month:1 month,3 months:3 months,6 months:6 months,1 year:1 year,infinite:infinite',
#'ipbother' => 'Other time',
#'ipbotheroption' => 'other',
#'ipbreason' => 'Reason',
#'ipbsubmit' => 'Block this user',
#'ipusubmit' => 'Unblock this address',
#'ipusuccess' => '"[[$1]]" unblocked',
#'isbn' => 'ISBN',
#'isredirect' => 'redirect page',
'italic_sample' => 'Etzana',
'italic_tip' => 'Etzana',
#'iteminvalidname' => 'Problem with item \'$1\', invalid name...',
#'jan' => 'Jan',
#'january' => 'January',
#'jul' => 'Jul',
#'july' => 'July',
#'jun' => 'Jun',
#'june' => 'June',
#'laggedslavemode' => 'Warning: Page may not contain recent updates.',
#'largefile' => 'It is recommended that images not exceed $1 bytes in size, this file is $2 bytes',
'last' => 'aur',
'lastmodified' => 'Orriaren azken eguneratzea: $1.',
#'lastmodifiedby' => 'This page was last modified $1 by $2.',
#'lineno' => 'Line $1:',
#'link_sample' => 'Link title',
#'link_tip' => 'Internal link',
#'linklistsub' => '(List of links)',
#'linkprefix' => '/^(.*?)([a-zA-Z\x80-\xff]+)$/sD',
#'linkshere' => 'The following pages link to here:',
#'linkstoimage' => 'The following pages link to this file:',
#'linktrail' => '/^([a-z]+)(.*)$/sD',
#'listform' => 'list',
#'listingcontinuesabbrev' => ' cont.',
#'listusers' => 'User list',
'loadhist' => 'Orriaren historia kargatzen',
#'loadingrev' => 'loading revision for diff',
#'localtime' => 'Local time',
#'lockbtn' => 'Lock database',
#'lockconfirm' => 'Yes, I really want to lock the database.',
#'lockdb' => 'Lock database',
#'lockdbsuccesssub' => 'Database lock succeeded',
/* 'lockdbsuccesstext' => 'The database has been locked.
<br />Remember to remove the lock after your maintenance is complete.', */
/* 'lockdbtext' => 'Locking the database will suspend the ability of all
users to edit pages, change their preferences, edit their watchlists, and
other things requiring changes in the database.
Please confirm that this is what you intend to do, and that you will
unlock the database when your maintenance is done.', */
#'locknoconfirm' => 'You did not check the confirmation box.',
#'log' => 'Logs',
'login' => 'Izena eman/Saio-hasiera',
#'loginend' => '&nbsp;',
#'loginerror' => 'Login error',
'loginpagetitle' => 'Saio hasiera',
'loginproblem' => '<b>Arazoren bat egon da zure saio-hasieran.</b><br />¡Saiatu berriro!',
#'loginprompt' => 'You must have cookies enabled to log in to {{SITENAME}}.',
#'loginreqtext' => 'You must [[special:Userlogin|login]] to view other pages.',
#'loginreqtitle' => 'Login Required',
#'loginsuccess' => 'You are now logged in to {{SITENAME}} as "$1".',
#'loginsuccesstitle' => 'Login successful',
'logout' => 'Saio-bukaera',
'logouttext' => 'Zure saioa amaitu duzu. 
Izena eman gabe wikipedia erabiltzen jarraitu ahal duzu, edo izen berdin edo bestearekin beste saioa hasi ahal duzu.<br />Orri batzuk saioa mantentzen duzuela adierazi dezakete arakatzailearen katxea garbitu arte.',
'logouttitle' => 'Saio amaiera',
'lonelypages' => 'Orri umezurtzak',
'longpages' => 'Orri luzeak',
/* 'longpagewarning' => '<strong>WARNING: This page is $1 kilobytes long; some
browsers may have problems editing pages approaching or longer than 32kb.
Please consider breaking the page into smaller sections.</strong>', */
/* 'lucenepowersearchtext' => '
Search in namespaces:

$1

Search for $3 $9', */
#'mailerror' => 'Error sending mail: $1',
#'mailmypassword' => 'Mail me a new password',
#'mailnologin' => 'No send address',
/* 'mailnologintext' => 'You must be [[Special:Userlogin|logged in]]
and have a valid e-mail address in your [[Special:Preferences|preferences]]
to send e-mail to other users.', */
'mainpage' => 'Azala',
/* 'mainpagedocfooter' => 'Please see [http://meta.wikipedia.org/wiki/MediaWiki_i18n documentation on customizing the interface]
and the [http://meta.wikipedia.org/wiki/MediaWiki_User%27s_Guide User\'s Guide] for usage and configuration help.', */
#'mainpagetext' => 'Wiki software successfully installed.',
#'maintenance' => 'Maintenance page',
#'maintenancebacklink' => 'Back to Maintenance Page',
#'maintnancepagetext' => 'This page includes several handy tools for everyday maintenance. Some of these functions tend to stress the database, so please do not hit reload after every item you fixed ;-)',
#'makesysop' => 'Make a user into a sysop',
#'makesysop-nodatabase' => 'Bad interwiki username: $1',
#'makesysopfail' => '<b>User "$1" could not be made into a sysop. (Did you enter the name correctly?)</b>',
#'makesysopname' => 'Name of the user:',
#'makesysopok' => '<b>User "$1" is now a sysop</b>',
#'makesysopsubmit' => 'Make this user into a sysop',
/* 'makesysoptext' => 'This form is used by bureaucrats to turn ordinary users into administrators.
Type the name of the user in the box and press the button to make the user an administrator', */
#'makesysoptitle' => 'Make a user into a sysop',
#'mar' => 'Mar',
#'march' => 'March',
#'markaspatrolleddiff' => 'Mark as patrolled',
#'markaspatrolledlink' => '[$1]',
#'markaspatrolledtext' => 'Mark this article as patrolled',
#'markedaspatrolled' => 'Marked as patrolled',
#'markedaspatrolledtext' => 'The selected revision has been marked as patrolled.',
/* 'matchtotals' => 'The query "$1" matched $2 page titles
and the text of $3 pages.', */
#'math' => 'Math',
#'math_bad_output' => 'Can\'t write to or create math output directory',
#'math_bad_tmpdir' => 'Can\'t write to or create math temp directory',
#'math_failure' => 'Failed to parse',
#'math_image_error' => 'PNG conversion failed; check for correct installation of latex, dvips, gs, and convert',
#'math_lexing_error' => 'lexing error',
#'math_notexvc' => 'Missing texvc executable; please see math/README to configure.',
#'math_sample' => 'Insert formula here',
#'math_syntax_error' => 'syntax error',
#'math_tip' => 'Mathematical formula (LaTeX)',
#'math_unknown_error' => 'unknown error',
#'math_unknown_function' => 'unknown function ',
#'may' => 'May',
#'may_long' => 'May',
#'media_sample' => 'Example.ogg',
#'media_tip' => 'Media file link',
/* 'mediawarning' => '\'\'\'Warning\'\'\': This file may contain malicious code, by executing it your system may be compromised.
<hr>', */
#'metadata' => 'Metadata',
#'metadata_page' => 'Wikipedia:Metadata',
#'minlength' => 'File names must be at least three letters.',
'minoredit' => 'Edizio txikia',
'minoreditletter' => 't',
#'mispeelings' => 'Pages with misspellings',
#'mispeelingspage' => 'List of common misspellings',
#'mispeelingstext' => 'The following pages contain a common misspelling, which are listed on $1. The correct spelling might be given (like this).',
/* 'missingarticle' => 'The database did not find the text of a page
that it should have found, named "$1".

This is usually caused by following an outdated diff or history link to a
page that has been deleted.

If this is not the case, you may have found a bug in the software.
Please report this to an administrator, making note of the URL.', */
/* 'missingimage' => '<b>Missing image</b><br /><i>$1</i>
', */
#'missinglanguagelinks' => 'Missing Language Links',
#'missinglanguagelinksbutton' => 'Find missing language links for',
#'missinglanguagelinkstext' => 'These pages do <i>not</i> link to their counterpart in $1. Redirects and subpages are <i>not</i> shown.',
#'monday' => 'Monday',
'moredotdotdot' => 'Gehiago...',
#'mostlinked' => 'Most linked to pages',
#'move' => 'Move',
'movearticle' => 'Oraingo izenburua',
#'movedto' => 'moved to',
#'movelogpage' => 'Move log',
#'movelogpagetext' => 'Below is a list of page moved.',
#'movenologin' => 'Not logged in',
/* 'movenologintext' => 'You must be a registered user and [[Special:Userlogin|logged in]]
to move a page.', */
'movepage' => 'Orriaren izenburua aldatu',
'movepagebtn' => 'Orriaren izenburua aldatu',
/* 'movepagetalktext' => 'The associated talk page, if any, will be automatically moved along with it \'\'\'unless:\'\'\'
*You are moving the page across namespaces,
*A non-empty talk page already exists under the new name, or
*You uncheck the box below.

In those cases, you will have to move or merge the page manually if desired.', */
/* 'movepagetext' => 'Using the form below will rename a page, moving all
of its history to the new name.
The old title will become a redirect page to the new title.
Links to the old page title will not be changed; be sure to
check for double or broken redirects.
You are responsible for making sure that links continue to
point where they are supposed to go.

Note that the page will \'\'\'not\'\'\' be moved if there is already
a page at the new title, unless it is empty or a redirect and has no
past edit history. This means that you can rename a page back to where
it was just renamed from if you make a mistake, and you cannot overwrite
an existing page.

<b>WARNING!</b>
This can be a drastic and unexpected change for a popular page;
please be sure you understand the consequences of this before
proceeding.', */
#'movereason' => 'Reason',
#'movetalk' => 'Move "talk" page as well, if applicable.',
'movethispage' => 'Izenburua aldatu',
#'mw_math_html' => 'HTML if possible or else PNG',
#'mw_math_mathml' => 'MathML if possible (experimental)',
#'mw_math_modern' => 'Recommended for modern browsers',
#'mw_math_png' => 'Always render PNG',
#'mw_math_simple' => 'HTML if very simple or else PNG',
#'mw_math_source' => 'Leave it as TeX (for text browsers)',
'mycontris' => 'Nire ekarpenak',
#'mypage' => 'My page',
#'mytalk' => 'My talk',
#'namespace' => 'Namespace:',
#'namespacesall' => 'all',
#'navigation' => 'Navigation',
#'nbytes' => '$1 bytes',
#'nchanges' => '$1 changes',
#'newarticle' => '(New)',
'newarticletext' => 'Orri hau ez dago datu-basean; artikulua hastea nahi baduzu, testu lehioan idatzi dezakezu (Mesedez, zure lehen bisita bada, irakurri lehen [[Wikipedia:Laguntza|Laguntza orria]]).
Honaino nahigabe helduz gero, zure arakatzaileko \'\'\'atzera\'\'\' botoia sakatu.',
#'newbies' => 'newbies',
#'newimages' => 'Gallery of new files',
#'newmessages' => 'You have $1.',
#'newmessageslink' => 'new messages',
'newpage' => 'Orri berria',
'newpageletter' => 'B',
'newpages' => 'Orri berriak',
#'newpassword' => 'New password',
'newtitle' => 'Izenburu berria',
'newusersonly' => ' (lankide berriak bakarrik)',
#'newwindow' => '(opens in new window)',
#'next' => 'next',
#'nextdiff' => 'Next diff &rarr;',
'nextn' => 'hurrengo $1ak',
#'nextpage' => 'Next page ($1)',
#'nextrevision' => 'Newer revision&rarr;',
'nlinks' => '$1 esteka',
#'noarticletext' => '(There is currently no text in this page)',
/* 'noconnect' => 'Sorry! The wiki is experiencing some technical difficulties, and cannot contact the database server. <br />
$1', */
#'nocontribs' => 'No changes were found matching these criteria.',
#'nocookieslogin' => '{{SITENAME}} uses cookies to log in users. You have cookies disabled. Please enable them and try again.',
#'nocookiesnew' => 'The user account was created, but you are not logged in. {{SITENAME}} uses cookies to log in users. You have cookies disabled. Please enable them, then log in with your new username and password.',
#'nocreativecommons' => 'Creative Commons RDF metadata disabled for this server.',
#'nocredits' => 'There is no credits info available for this page.',
#'nodb' => 'Could not select database $1',
#'nodublincore' => 'Dublin Core RDF metadata disabled for this server.',
#'noemail' => 'There is no e-mail address recorded for user "$1".',
/* 'noemailprefs' => '<strong>No email address has been specified</strong>, the following
features will not work.', */
/* 'noemailtext' => 'This user has not specified a valid e-mail address,
or has chosen not to receive e-mail from other users.', */
#'noemailtitle' => 'No e-mail address',
#'nogomatch' => 'No page with [[$1|this exact title]] exists, trying full text search.',
#'nohistory' => 'There is no edit history for this page.',
#'noimage' => 'No file by this name exists, you can [$1 upload it]',
#'noimages' => 'Nothing to see.',
#'nolinkshere' => 'No pages link to here.',
#'nolinkstoimage' => 'There are no pages that link to this file.',
'noname' => 'Lankide izena ez duzu eman.',
/* 'nonefound' => '\'\'\'Note\'\'\': unsuccessful searches are
often caused by searching for common words like "have" and "from",
which are not indexed, or by specifying more than one search term (only pages
containing all of the search terms will appear in the result).', */
#'nonunicodebrowser' => '<strong>WARNING: Your browser is not unicode compliant. A workaround is in place to allow you to safely edit articles: non-ASCII characters will appear in the edit box as hexadecimal codes.</strong>',
#'nospecialpagetext' => 'You have requested an invalid special page, a list of valid special pages may be found at [[{{ns:special}}:Specialpages]].',
#'nosuchaction' => 'No such action',
/* 'nosuchactiontext' => 'The action specified by the URL is not
recognized by the wiki', */
#'nosuchspecialpage' => 'No such special page',
/* 'nosuchuser' => 'There is no user by the name "$1".
Check your spelling, or use the form below to create a new user account.', */
#'nosuchusershort' => 'There is no user by the name "$1". Check your spelling.',
#'notacceptable' => 'The wiki server can\'t provide data in a format your client can read.',
#'notanarticle' => 'Not a content page',
/* 'notargettext' => 'You have not specified a target page or user
to perform this function on.', */
#'notargettitle' => 'No target',
#'note' => '<strong>Note:</strong> ',
#'notextmatches' => 'No page text matches',
#'notitlematches' => 'No page title matches',
#'notloggedin' => 'Not logged in',
#'nov' => 'Nov',
#'november' => 'November',
'nowatchlist' => 'Zure segimendu zerrenda hutsik dago.',
#'nowiki_sample' => 'Insert non-formatted text here',
#'nowiki_tip' => 'Ignore wiki formatting',
'nstab-category' => 'Kategoria',
'nstab-help' => 'Laguntza',
'nstab-image' => 'Irudia',
'nstab-main' => 'Artikulua',
#'nstab-media' => 'Media page',
'nstab-mediawiki' => 'Oharra',
'nstab-special' => 'Berezia',
'nstab-template' => 'Txantiloia',
#'nstab-user' => 'User page',
#'nstab-wp' => 'Project page',
#'numauthors' => 'Number of distinct authors (article): $1',
#'number_of_watching_users_RCview' => '[$1]',
#'number_of_watching_users_pageview' => '[$1 watching user/s]',
#'numedits' => 'Number of edits (article): $1',
#'numtalkauthors' => 'Number of distinct authors (discussion page): $1',
#'numtalkedits' => 'Number of edits (discussion page): $1',
#'numwatchers' => 'Number of watchers: $1',
#'nviews' => '$1 views',
#'oairepository' => 'OAI Repository',
#'oct' => 'Oct',
#'october' => 'October',
#'ok' => 'OK',
#'oldpassword' => 'Old password',
#'orig' => 'orig',
'orphans' => 'Orri umezurtzak',
#'othercontribs' => 'Based on work by $1.',
'otherlanguages' => 'Beste hizkuntzak',
#'others' => 'others',
#'pagemovedsub' => 'Move succeeded',
'pagemovedtext' => '"$1"-ren izenburua "$2"-en truke aldatu da.',
#'pagetitle' => '$1 - {{SITENAME}}',
'passwordremindertext' => 'Norbaitek (zu seguruenik, IP $1 helbidetik) Wikipedian saio berria hasteko pasahitza bidaltzea eskatu du.
\"$2\" lankidearen pasahitza orain \"$3\" da.
Mesedez, hasi saioa eta pasahitz hau berri baten truke aldatu.',
'passwordremindertitle' => 'Wikipediaren pasahitz oroigarria',
'passwordsent' => 'Pasahitz oroigarria \"$1\"-ren helbide elektronikora bidali dugu.
Mesedez hasi saioa pasahitza hartu bezain laster.',
#'passwordtooshort' => 'Your password is too short. It must have at least $1 characters.',
#'perfcached' => 'The following data is cached and may not be completely up to date:',
/* 'perfdisabled' => 'Sorry! This feature has been temporarily disabled
because it slows the database down to the point that no one can use
the wiki.', */
#'perfdisabledsub' => 'Here\'s a saved copy from $1:',
#'personaltools' => 'Personal tools',
'popularpages' => 'Orri bisitatuenak',
#'portal' => 'Community portal',
#'portal-url' => 'Project:Community Portal',
#'postcomment' => 'Post a comment',
#'poweredby' => '{{SITENAME}} is powered by [http://www.mediawiki.org/ MediaWiki], an open source wiki engine.',
#'powersearch' => 'Search',
/* 'powersearchtext' => '
Search in namespaces :<br />
$1<br />
$2 List redirects &nbsp; Search for $3 $9', */
'preferences' => 'Hobespenak',
#'prefs-help-email' => '* Email (optional): Enables others to contact you through your user or user_talk page without the need of revealing your identity.',
#'prefs-help-email-enotif' => 'This address is also used to send you email notifications if you enabled the options.',
#'prefs-help-realname' => '* Real name (optional): if you choose to provide it this will be used for giving you attribution for your work.',
'prefs-misc' => 'Nahaztea',
#'prefs-personal' => 'User data',
#'prefs-rc' => 'Recent changes & stubs',
/* 'prefslogintext' => 'You are logged in as "$1".
Your internal ID number is $2.

See [[Project:User preferences help]] for help deciphering the options.', */
#'prefsnologin' => 'Not logged in',
#'prefsnologintext' => 'You must be [[Special:Userlogin|logged in]] to set user preferences.',
#'prefsreset' => 'Preferences have been reset from storage.',
'preview' => 'Aurrebista',
/* 'previewconflict' => 'This preview reflects the text in the upper
text editing area as it will appear if you choose to save.', */
#'previewnote' => 'Remember that this is only a preview, and has not yet been saved!',
#'previousdiff' => '&larr; Previous diff',
#'previousrevision' => '&larr;Older revision',
'prevn' => 'aurreko $1ak',
#'print' => 'Print',
'printableversion' => 'Inprimatzeko bertsio',
#'printsubtitle' => '(From {{SERVER}})',
#'protect' => 'Protect',
#'protectcomment' => 'Reason for protecting',
#'protectedarticle' => 'protected "[[$1]]"',
#'protectedpage' => 'Protected page',
#'protectedpagewarning' => '<strong>WARNING:  This page has been locked so that only users with sysop privileges can edit it. Be sure you are following the [[Project:Protected_page_guidelines|protected page guidelines]].</strong>',
/* 'protectedtext' => 'This page has been locked to prevent editing; there are
a number of reasons why this may be so, please see
[[Project:Protected page]].

You can view and copy the source of this page:', */
#'protectlogpage' => 'Protection_log',
/* 'protectlogtext' => 'Below is a list of page locks/unlocks.
See [[Project:Protected page]] for more information.', */
#'protectmoveonly' => 'Protect from moves only',
#'protectpage' => 'Protect page',
#'protectsub' => '(Protecting "$1")',
#'protectthispage' => 'Protect this page',
#'proxyblocker' => 'Proxy blocker',
#'proxyblockreason' => 'Your IP address has been blocked because it is an open proxy. Please contact your Internet service provider or tech support and inform them of this serious security problem.',
/* 'proxyblocksuccess' => 'Done.
', */
#'pubmedurl' => 'http://www.ncbi.nlm.nih.gov/entrez/query.fcgi?cmd=Retrieve&db=pubmed&dopt=Abstract&list_uids=$1',
#'qbbrowse' => 'Browse',
#'qbedit' => 'Edit',
#'qbfind' => 'Find',
#'qbmyoptions' => 'My pages',
#'qbpageinfo' => 'Context',
#'qbpageoptions' => 'This page',
#'qbsettings' => 'Quickbar',
#'qbspecialpages' => 'Special pages',
'randompage' => 'Ausazko orria',
#'randompage-url' => 'Special:Random',
#'range_block_disabled' => 'The sysop ability to create range blocks is disabled.',
#'rchide' => 'in $4 form; $1 minor edits; $2 secondary namespaces; $3 multiple edits.',
'rclinks' => 'Erakutsi azken $1 aldaketak $2 egunetan.<br />$3',
'rclistfrom' => 'Erakutsi $1tik aldaketa berriak',
'rcliu' => '; $1 erregistratu diren lankideen editaketak',
'rcloaderr' => 'Aldaketa berriak kargatzen',
#'rclsub' => '(to pages linked from "$1")',
'rcnote' => 'Azken <strong>$1</strong> aldaketak <strong>$2</strong> egunetan erakusten.',
#'rcnotefrom' => 'Below are the changes since <b>$2</b> (up to <b>$1</b> shown).',
#'rcpatroldisabled' => 'Recent Changes Patrol disabled',
#'rcpatroldisabledtext' => 'The Recent Changes Patrol feature is currently disabled.',
#'readonly' => 'Database locked',
#'readonly_lag' => 'The database has been automatically locked while the slave database servers catch up to the master',
/* 'readonlytext' => 'The database is currently locked to new
entries and other modifications, probably for routine database maintenance,
after which it will be back to normal.
The administrator who locked it offered this explanation:
$1', */
/* 'readonlywarning' => '<strong>WARNING: The database has been locked for maintenance,
so you will not be able to save your edits right now. You may wish to cut-n-paste
the text into a text file and save it for later.</strong>', */
'recentchanges' => 'Aldaketa berriak',
#'recentchanges-url' => 'Special:Recentchanges',
#'recentchangesall' => 'all',
#'recentchangescount' => 'Titles in recent changes',
'recentchangeslinked' => 'Lotutako orrien aldaketak',
#'recentchangestext' => 'Track the most recent changes to the wiki on this page.',
'redirectedfrom' => 'Wikipediatik, entziklopedia askea.<br />
(Redirected from $1)',
'remembermypassword' => 'Gogoratu pasahitza saio tartean (cookie gorde).',
#'removechecked' => 'Remove checked items from watchlist',
#'removedwatch' => 'Removed from watchlist',
#'removedwatchtext' => 'The page "$1" has been removed from your watchlist.',
#'removingchecked' => 'Removing requested items from watchlist...',
#'renamegrouplogentry' => 'Renamed group $2 to $3',
#'renameuser' => 'Rename user',
#'renameusererrordoesnotexist' => 'The user "<nowiki>$1</nowiki>" does not exist',
#'renameusererrorexists' => 'The user "<nowiki>$1</nowiki>" already exits',
#'renameusererrorinvalid' => 'The username "<nowiki>$1</nowiki>" is invalid',
#'renameusererrortoomany' => 'The user "<nowiki>$1</nowiki>" has $2 contributions, renaming a user with more than $3 contributions could adversely affect site performance',
#'renameuserlog' => 'Renamed the user "[[User:$1|$1]]" (which had $3 edits) to "[[User:$2|$2]]"',
#'renameuserlogpage' => 'User rename log',
#'renameuserlogpagetext' => 'This is a log of changes to user names',
#'renameusernew' => 'New username: ',
#'renameuserold' => 'Current username: ',
#'renameusersubmit' => 'Submit',
#'renameusersuccess' => 'The user "<nowiki>$1</nowiki>" has been renamed to "<nowiki>$2</nowiki>"',
#'resetprefs' => 'Reset',
#'restorelink' => '$1 deleted edits',
#'restorelink1' => 'one deleted edit',
#'restrictedpheading' => 'Restricted special pages',
#'resultsperpage' => 'Hits per page',
#'retrievedfrom' => 'Retrieved from "$1"',
#'returnto' => 'Return to $1.',
#'retypenew' => 'Retype new password',
#'reupload' => 'Re-upload',
#'reuploaddesc' => 'Return to the upload form.',
#'reverted' => 'Reverted to earlier revision',
#'revertimg' => 'rev',
#'revertmove' => 'revert',
#'revertpage' => 'Reverted edit of $2, changed back to last version by $1',
#'revhistory' => 'Revision history',
#'revisionasof' => 'Revision as of $1',
#'revisionasofwithlink' => 'Revision as of $1; $2<br />$3 | $4',
#'revnotfound' => 'Revision not found',
/* 'revnotfoundtext' => 'The old revision of the page you asked for could not be found.
Please check the URL you used to access this page.
', */
#'rfcurl' => 'http://www.ietf.org/rfc/rfc$1.txt',
#'rights' => 'Rights:',
#'rightslogtext' => 'This is a log of changes to user rights.',
#'rollback' => 'Roll back edits',
#'rollback_short' => 'Rollback',
#'rollbackfailed' => 'Rollback failed',
#'rollbacklink' => 'rollback',
#'rows' => 'Rows',
#'saturday' => 'Saturday',
'savearticle' => 'Orria gorde',
#'savedprefs' => 'Your preferences have been saved.',
#'savefile' => 'Save file',
#'savegroup' => 'Save Group',
#'saveprefs' => 'Save',
#'saveusergroups' => 'Save User Groups',
#'scarytranscludedisabled' => '[Interwiki transcluding is disabled]',
#'scarytranscludefailed' => '[Template fetch failed for $1; sorry]',
#'scarytranscludetoolong' => '[URL is too long; sorry]',
'search' => 'Bilatu',
#'searchdidyoumean' => 'Did you mean: "<a href="$1">$2</a>"?',
#'searchdisabled' => '{{SITENAME}} search is disabled. You can search via Google in the meantime. Note that their indexes of {{SITENAME}} content may be out of date.',
#'searchfulltext' => 'Search full text',
/* 'searchnearmatch' => '<li>$1</li>
', */
/* 'searchnearmatches' => '<b>These pages have similar titles to your query:</b>
', */
#'searchnext' => '<span style=\'font-size: small\'>Next</span> &#x00BB;',
#'searchnoresults' => 'Sorry, there were no exact matches to your query.',
#'searchnumber' => '<strong>Results $1-$2 of $3</strong>',
#'searchprev' => '&#x00AB; <span style=\'font-size: small\'>Prev</span>',
#'searchquery' => 'For query "$1"',
'searchresults' => 'Bilaketaren emaitza',
#'searchresultshead' => 'Search',
#'searchresulttext' => 'For more information about searching {{SITENAME}}, see [[Project:Searching|Searching {{SITENAME}}]].',
#'searchscore' => 'Relevancy: $1',
#'searchsize' => '$1KB ($2 words)',
#'sectionlink' => '&rarr;',
#'selectnewerversionfordiff' => 'Select a newer version for comparison',
#'selectolderversionfordiff' => 'Select an older version for comparison',
#'selflinks' => 'Pages with Self Links',
#'selflinkstext' => 'The following pages contain a link to themselves, which they should not.',
#'selfmove' => 'Source and destination titles are the same; can\'t move a page over itself.',
#'sep' => 'Sep',
#'september' => 'September',
#'servertime' => 'Server time',
/* 'sessionfailure' => 'There seems to be a problem with your login session;
this action has been canceled as a precaution against session hijacking.
Please hit "back" and reload the page you came from, then try again.', */
#'set_rights_fail' => '<b>User rights for "$1" could not be set. (Did you enter the name correctly?)</b>',
#'set_user_rights' => 'Set user rights',
#'setbureaucratflag' => 'Set bureaucrat flag',
#'setstewardflag' => 'Set steward flag',
#'shareddescriptionfollows' => '-',
#'sharedupload' => 'This file is a shared upload and may be used by other projects.',
#'shareduploadwiki' => 'Please see the [$1 file description page] for further information.',
'shortpages' => 'Artikulu laburrak',
'show' => 'erakutsi',
#'showbigimage' => 'Download high resolution version ($1x$2, $3 KB)',
#'showdiff' => 'Show changes',
'showhideminor' => '$1 editaketa txikiak',
#'showingresults' => 'Showing below up to <b>$1</b> results starting with #<b>$2</b>.',
#'showingresultsnum' => 'Showing below <b>$3</b> results starting with #<b>$2</b>.',
#'showlast' => 'Show last $1 files sorted $2.',
'showpreview' => 'Aurrebista erakutsi',
'showtoc' => 'erakutsi',
'sidebar' => '
* navigation
** mainpage|mainpage
** portal-url|portal
** currentevents-url|currentevents
** recentchanges-url|recentchanges
** randompage-url|randompage
** helppage|help
** sitesupport-url|sitesupport',
#'sig_tip' => 'Your signature with timestamp',
#'sitematrix' => 'List of Wikimedia wikis',
#'sitenotice' => '-',
'sitestats' => 'Gunearen estatistikak',
'sitestatstext' => 'Datu-basean guztira <b>$1</b> orri daude; eztabaidatzeko, wikipedari buruzko orriak, \'\'redirect\'\'-k eta artikulu laburrak barne hartzen. 
Horiek baztertzen, <b>$2</b> artikulu dira datu-basean.<p>
There have been a total of <b>$3</b> page views, and <b>$4</b> page edits
since the software was upgraded (July 20, 2002).
That comes to <b>$5</b> average edits per page, and <b>$6</b> views per edit.',
#'sitesubtitle' => 'The Free Encyclopedia',
'sitesupport' => 'Emariak',
#'sitesupport-url' => 'Project:Site support',
#'sitetitle' => '{{SITENAME}}',
#'siteuser' => '{{SITENAME}} user $1',
#'siteusers' => '{{SITENAME}} user(s) $1',
#'skin' => 'Skin',
#'skinpreview' => '(Preview)',
#'sorbs' => 'SORBS DNSBL',
#'sorbs_create_account_reason' => 'Your IP address is listed as an open proxy in the [http://www.sorbs.net SORBS] DNSBL. You cannot create an account',
#'sorbsreason' => 'Your IP address is listed as an open proxy in the [http://www.sorbs.net SORBS] DNSBL.',
#'sourcefilename' => 'Source filename',
#'spamprotectionmatch' => 'The following text is what triggered our spam filter: $1',
#'spamprotectiontext' => 'The page you wanted to save was blocked by the spam filter. This is probably caused by a link to an external site.',
#'spamprotectiontitle' => 'Spam protection filter',
#'speciallogtitlelabel' => 'Title: ',
#'specialloguserlabel' => 'User: ',
#'specialpage' => 'Special Page',
'specialpages' => 'Orri bereziak',
#'spheading' => 'Special pages for all users',
#'sqlhidden' => '(SQL query hidden)',
'statistics' => 'Estatistikak',
#'storedversion' => 'Stored version',
#'stubthreshold' => 'Threshold for stub display',
#'subcategories' => 'Subcategories',
#'subcategorycount' => 'There are $1 subcategories to this category.',
#'subcategorycount1' => 'There is $1 subcategory to this category.',
#'subject' => 'Subject/headline',
#'subjectpage' => 'View subject',
#'successfulupload' => 'Successful upload',
'summary' => 'Laburpen',
#'sunday' => 'Sunday',
/* 'sysoptext' => 'The action you have requested can only be
performed by users with "sysop" capability.
See $1.', */
'sysoptitle' => '<i>Sysop</i> izatea behar da',
'tableform' => 'taula',
#'tagline' => 'From {{SITENAME}}',
'talk' => 'Eztabaida',
/* 'talkexists' => '\'\'\'The page itself was moved successfully, but the
talk page could not be moved because one already exists at the new
title. Please merge them manually.\'\'\'', */
'talkpage' => 'Eztabaida orri honen gainean',
#'talkpagemoved' => 'The corresponding talk page was also moved.',
#'talkpagenotmoved' => 'The corresponding talk page was <strong>not</strong> moved.',
#'talkpagetext' => '<!-- MediaWiki:talkpagetext -->',
#'templatesused' => 'Templates used on this page:',
#'textboxsize' => 'Editing',
#'textmatches' => 'Page text matches',
#'thisisdeleted' => 'View or restore $1?',
#'thumbnail-more' => 'Enlarge',
'thumbsize' => 'Thumbnail size :',
#'thursday' => 'Thursday',
#'timezonelegend' => 'Time zone',
#'timezoneoffset' => 'Offset¹',
#'timezonetext' => 'The number of hours your local time differs from server time (UTC).',
#'titlematches' => 'Article title matches',
'toc' => 'Aurkibidea',
#'tog-editondblclick' => 'Edit pages on double click (JavaScript)',
#'tog-editsection' => 'Enable section editing via [edit] links',
#'tog-editsectiononrightclick' => 'Enable section editing by right clicking<br /> on section titles (JavaScript)',
#'tog-editwidth' => 'Edit box has full width',
#'tog-enotifminoredits' => 'Send me an email also for minor edits of pages',
#'tog-enotifrevealaddr' => 'Reveal my email address in notification mails',
#'tog-enotifusertalkpages' => 'Send me an email when my user talk page is changed',
#'tog-enotifwatchlistpages' => 'Send me an email on page changes',
#'tog-externaldiff' => 'Use external diff by default',
#'tog-externaleditor' => 'Use external editor by default',
#'tog-fancysig' => 'Raw signatures (without automatic link)',
#'tog-hideminor' => 'Hide minor edits in recent changes',
#'tog-highlightbroken' => 'Format broken links <a href="" class="new">like this</a> (alternative: like this<a href="" class="internal">?</a>).',
#'tog-justify' => 'Justify paragraphs',
#'tog-minordefault' => 'Mark all edits minor by default',
#'tog-nocache' => 'Disable page caching',
#'tog-numberheadings' => 'Auto-number headings',
#'tog-previewonfirst' => 'Show preview on first edit',
#'tog-previewontop' => 'Show preview before edit box',
#'tog-rememberpassword' => 'Remember across sessions',
#'tog-shownumberswatching' => 'Show the number of watching users',
#'tog-showtoc' => 'Show table of contents (for pages with more than 3 headings)',
#'tog-showtoolbar' => 'Show edit toolbar (JavaScript)',
#'tog-underline' => 'Underline links',
#'tog-usenewrc' => 'Enhanced recent changes (JavaScript)',
#'tog-watchdefault' => 'Add pages you edit to your watchlist',
'toolbox' => 'Lanabesak',
#'tooltip-compareselectedversions' => 'See the differences between the two selected versions of this page. [alt-v]',
#'tooltip-diff' => 'Show which changes you made to the text. [alt-d]',
#'tooltip-minoredit' => 'Mark this as a minor edit [alt-i]',
#'tooltip-preview' => 'Preview your changes, please use this before saving! [alt-p]',
#'tooltip-save' => 'Save your changes [alt-s]',
#'tooltip-search' => 'Search {{SITENAME}} [alt-f]',
#'tooltip-watch' => 'Add this page to your watchlist [alt-w]',
'trackback' => '; $4$5 : [$2 $1]',
'trackbackbox' => '<div id=\'mw_trackbacks\'>
Trackbacks for this article:<br />
$1
</div>',
#'trackbackdeleteok' => 'The trackback was successfully deleted.',
'trackbackexcerpt' => '; $4$5 : [$2 $1]: <nowiki>$3</nowiki>',
#'trackbacklink' => 'Trackback',
#'trackbackremove' => ' ([$1 Delete])',
#'tryexact' => 'Try exact match',
#'tuesday' => 'Tuesday',
#'uclinks' => 'View the last $1 changes; view the last $2 days.',
#'ucnote' => 'Below are this user\'s last <b>$1</b> changes in the last <b>$2</b> days.',
#'uctop' => ' (top)',
#'unblockip' => 'Unblock user',
/* 'unblockiptext' => 'Use the form below to restore write access
to a previously blocked IP address or username.', */
#'unblocklink' => 'unblock',
#'unblocklogentry' => 'unblocked $1',
#'uncategorizedcategories' => 'Uncategorized categories',
#'uncategorizedpages' => 'Uncategorized pages',
'undelete' => 'Orria ezabatuta berreskuratu',
#'undelete_short' => 'Undelete $1 edits',
#'undelete_short1' => 'Undelete one edit',
#'undeletearticle' => 'Restore deleted page',
#'undeletebtn' => 'Restore!',
#'undeletedarticle' => 'restored "[[$1]]"',
#'undeletedrevisions' => '$1 revisions restored',
/* 'undeletedtext' => '[[$1]] has been successfully restored.
See [[Special:Log/delete]] for a record of recent deletions and restorations.', */
/* 'undeletehistory' => 'If you restore the page, all revisions will be restored to the history.
If a new page with the same name has been created since the deletion, the restored
revisions will appear in the prior history, and the current revision of the live page
will not be automatically replaced.', */
#'undeletepage' => 'View and restore deleted pages',
/* 'undeletepagetext' => 'The following pages have been deleted but are still in the archive and
can be restored. The archive may be periodically cleaned out.', */
#'undeleterevision' => 'Deleted revision as of $1',
#'undeleterevisions' => '$1 revisions archived',
#'underline-always' => 'Always',
#'underline-default' => 'Browser default',
#'underline-never' => 'Never',
#'unexpected' => 'Unexpected value: "$1"="$2".',
#'unit-pixel' => 'px',
#'unlockbtn' => 'Unlock database',
#'unlockconfirm' => 'Yes, I really want to unlock the database.',
#'unlockdb' => 'Unlock database',
#'unlockdbsuccesssub' => 'Database lock removed',
#'unlockdbsuccesstext' => 'The database has been unlocked.',
/* 'unlockdbtext' => 'Unlocking the database will restore the ability of all
users to edit pages, change their preferences, edit their watchlists, and
other things requiring changes in the database.
Please confirm that this is what you intend to do.', */
#'unprotect' => 'unprotect',
#'unprotectcomment' => 'Reason for unprotecting',
#'unprotectedarticle' => 'unprotected "[[$1]]"',
#'unprotectsub' => '(Unprotecting "$1")',
#'unprotectthispage' => 'Unprotect this page',
#'unusedcategories' => 'Unused categories',
#'unusedcategoriestext' => 'The following category pages exist although no other article or category make use of them.',
'unusedimages' => 'Irudi umezurtzak',
/* 'unusedimagestext' => '<p>Please note that other web sites may link to an image with
a direct URL, and so may still be listed here despite being
in active use.</p>', */
#'unwatch' => 'Unwatch',
#'unwatchthispage' => 'Stop watching',
#'updated' => '(Updated)',
#'upload' => 'Upload file',
#'upload_directory_read_only' => 'The upload directory ($1) is not writable by the webserver.',
#'uploadbtn' => 'Upload file',
#'uploadcorrupt' => 'The file is corrupt or has an incorrect extension. Please check the file and upload again.',
#'uploaddisabled' => 'Sorry, uploading is disabled.',
#'uploadedfiles' => 'Uploaded files',
#'uploadedimage' => 'uploaded "[[$1]]"',
#'uploaderror' => 'Upload error',
#'uploadlink' => 'Upload images',
#'uploadlog' => 'upload log',
#'uploadlogpage' => 'Upload_log',
#'uploadlogpagetext' => 'Below is a list of the most recent file uploads.',
#'uploadnewversion' => '[$1 Upload a new version of this file]',
#'uploadnologin' => 'Not logged in',
/* 'uploadnologintext' => 'You must be [[Special:Userlogin|logged in]]
to upload files.', */
#'uploadscripted' => 'This file contains HTML or script code that my be erroneously be interpreted by a web browser.',
'uploadtext' => 'Begira lehen ea bidali nahi duzun irudia <strong><a href="http://commons.wikimedia.org/wiki/Main_Page">Commons-ean</a></strong> den.
<p><strong>STOP!</strong> Before you upload here,
make sure to read and follow the <a href="/wiki/Special:Image_use_policy">image use policy</a>.
<p>If a file with the name you are specifying already
exists on the wiki, it\'ll be replaced without warning.
So unless you mean to update a file, it\'s a good idea
to first check if such a file exists.
<p>To view or search previously uploaded images,
go to the <a href="/wiki/Special:Imagelist">list of uploaded images</a>.
Uploads and deletions are logged on the <a href="/wiki/Wikipedia:Upload_log">upload log</a>.
</p><p>Use the form below to upload new image files for use in
illustrating your pages.
On most browsers, you will see a "Browse..." button, which will
bring up your operating system\'s standard file open dialog.
Choosing a file will fill the name of that file into the text
field next to the button.
You must also check the box affirming that you are not
violating any copyrights by uploading the file.
Press the "Upload" button to finish the upload.
This may take some time if you have a slow internet connection.
<p>The preferred formats are JPEG for photographic images, PNG
for drawings and other iconic images, and OGG for sounds.
Please name your files descriptively to avoid confusion.
To include the image in a page, use a link in the form
<b>[[Image:file.jpg]]</b> or <b>[[Image:file.png|alt text]]</b>
or <b>[[Media:file.ogg]]</b> for sounds.
<p>Please note that as with wiki pages, others may edit or
delete your uploads if they think it serves the project, and
you may be blocked from uploading if you abuse the system.',
#'uploadvirus' => 'The file contains a virus! Details: $1',
#'uploadwarning' => 'Upload warning',
/* 'usenewcategorypage' => '1

Set first character to "0" to disable the new category page layout.', */
#'user_rights_set' => '<b>User rights for "$1" updated</b>',
#'usercssjsyoucanpreview' => '<strong>Tip:</strong> Use the \'Show preview\' button to test your new CSS/JS before saving.',
#'usercsspreview' => '\'\'\'Remember that you are only previewing your user CSS, it has not yet been saved!\'\'\'',
'userexists' => 'Beste lankide erabiltzen ari den izena eman duzu. Mesedez, beste izen aukeratu.',
#'userjspreview' => '\'\'\'Remember that you are only testing/previewing your user JavaScript, it has not yet been saved!\'\'\'',
'userlogin' => 'Izena eman edo saio berria hasi',
#'userlogout' => 'Log out',
#'usermailererror' => 'Mail object returned error: ',
#'userpage' => 'View user page',
#'userrights' => 'User rights management',
#'userrights-editusergroup' => 'Edit user groups',
#'userrights-groupsavailable' => 'Available groups:',
/* 'userrights-groupshelp' => 'Select groups you want the user to be removed from or added to.
Unselected groups will not be changed. You can deselect a group with CTRL + Left Click', */
#'userrights-groupsmember' => 'Member of:',
#'userrights-logcomment' => 'Changed group membership from $1 to $2',
#'userrights-lookup-user' => 'Manage user groups',
'userrights-user-editname' => 'Enter a username:',
'userstats' => 'Lankideen estatistikak',
'userstatstext' => '<b>$1</b> lankideek izena eman dute.
<b>$2</b> administratzaileak dira (ikusi $3).',
#'val_add' => 'Add',
#'val_article_lists' => 'List of validated articles',
#'val_clear_old' => 'Clear my older validation data',
#'val_del' => 'Delete',
#'val_details_th' => '<sub>User</sub> \ <sup>Topic</sup>',
#'val_details_th_user' => 'User $1',
#'val_form_note' => '\'\'\'Hint:\'\'\' Merging your data means that for the article revision you select, all options where you have specified \'\'no opinion\'\' will be set to the value and comment of the most recent revision for which you have expressed an opinion. For example, if you want to change a single option for a newer revision, but also keep your other settings for this article in this revision, just select which option you intend to \'\'change\'\', and merging will fill in the other options with your previous settings.',
#'val_iamsure' => 'Check this box if you really mean it!',
#'val_list_header' => '<th>#</th><th>Topic</th><th>Range</th><th>Action</th>',
#'val_merge_old' => 'Use my previous assessment where selected \'No opinion\'',
#'val_my_stats_title' => 'My validation overview',
#'val_no' => 'No',
#'val_no_anon_validation' => 'You have to be logged in to validate an article.',
#'val_noop' => 'No opinion',
#'val_of' => '$1 of $2',
#'val_page_validation_statistics' => 'Page validation statistics for $1',
#'val_percent' => '<b>$1%</b><br />($2 of $3 points<br />by $4 users)',
#'val_percent_single' => '<b>$1%</b><br />($2 of $3 points<br />by one user)',
#'val_rev_for' => 'Revisions for $1',
#'val_rev_stats' => 'See the validation statistics for "$1" <a href="$2">here</a>',
#'val_revision' => 'Revision',
#'val_revision_changes_ok' => 'Your ratings have been stored!',
#'val_revision_number' => 'Revision #$1',
#'val_revision_of' => 'Revision of $1',
#'val_revision_stats_link' => 'details',
#'val_show_my_ratings' => 'Show my validations',
#'val_stat_link_text' => 'Validation statistics for this article',
#'val_tab' => 'Validate',
/* 'val_table_header' => '<tr><th>Class</th>$1<th colspan=4>Opinion</th>$1<th>Comment</th></tr>
', */
#'val_this_is_current_version' => 'this is the latest version',
#'val_time' => 'Time',
#'val_topic_desc_page' => 'Project:Validation topics',
#'val_total' => 'Total',
#'val_user_stats_title' => 'Validation overview of user $1',
#'val_user_validations' => 'This user has validated $1 pages.',
#'val_validate_article_namespace_only' => 'Only articles can be validated. This page is <i>not</i> in the article namespace.',
#'val_validate_version' => 'Validate this version',
#'val_validated' => 'Validation done.',
#'val_validation_of' => 'Validation of "$1"',
#'val_version' => 'Version',
#'val_version_of' => 'Version of $1',
#'val_view_version' => 'View this revision',
#'val_votepage_intro' => 'Change this text <a href="{{SERVER}}{{localurl:MediaWiki:Val_votepage_intro}}">here</a>!',
#'val_warning' => '<b>Never, <i>ever</i>, change something here without <i>explicit</i> community consensus!</b>',
#'val_yes' => 'Yes',
#'validate' => 'Validate page',
#'variantname-zh' => 'zh',
#'variantname-zh-cn' => 'cn',
#'variantname-zh-hk' => 'hk',
#'variantname-zh-sg' => 'sg',
#'variantname-zh-tw' => 'tw',
#'version' => 'Version',
#'versionrequired' => 'Version $1 of MediaWiki required',
#'versionrequiredtext' => 'Version $1 of MediaWiki is required to use this page. See [[Special:Version]]',
#'viewcount' => 'This page has been accessed $1 times.',
'viewprevnext' => 'Erakutsi ($1) ($2) ($3).',
#'views' => 'Views',
#'viewsource' => 'View source',
'viewtalkpage' => 'Eztabaida erakutsi',
'wantedpages' => 'Orri eskatutakoenak',
#'watch' => 'Watch',
'watchdetails' => '* $1 pages watched not counting talk pages
* [[Special:Watchlist/edit|Show and edit complete watchlist]]',
/* 'watcheditlist' => 'Here\'s an alphabetical list of your
watched content pages. Check the boxes of pages you want to remove from your watchlist and click the \'remove checked\' button
at the bottom of the screen (deleting a content page also deletes the accompanying talk page and vice versa).', */
'watchlist' => 'Segimendu zerrenda',
#'watchlistall1' => 'all',
#'watchlistall2' => 'all',
'watchlistcontains' => 'Zure segimendu zerrenda $1 orri ditu.',
'watchlistsub' => '("$1" lankidearena)',
#'watchmethod-list' => 'checking watched pages for recent edits',
#'watchmethod-recent' => 'checking recent edits for watched pages',
#'watchnochange' => 'None of your watched items was edited in the time period displayed.',
#'watchnologin' => 'Not logged in',
#'watchnologintext' => 'You must be [[Special:Userlogin|logged in]] to modify your watchlist.',
'watchthis' => 'Artikulua zelatatu',
'watchthispage' => 'Orria zelatatu',
#'wednesday' => 'Wednesday',
'welcomecreation' => '<h2>Ongi etorri, $1!</h2><p>Zure kontua sotu duzu.
Ez ahaztu zure hobespenak pertsonalizatu.',
'whatlinkshere' => 'Honekin lotzen diren orriak',
#'whitelistacctext' => 'To be allowed to create accounts in this Wiki you have to [[Special:Userlogin|log]] in and have the appropriate permissions.',
#'whitelistacctitle' => 'You are not allowed to create an account',
#'whitelistedittext' => 'You have to [[Special:Userlogin|login]] to edit pages.',
#'whitelistedittitle' => 'Login required to edit',
#'whitelistreadtext' => 'You have to [[Special:Userlogin|login]] to read pages.',
#'whitelistreadtitle' => 'Login required to read',
'wikipediapage' => 'Erakutsi Meta-orria',
#'wikititlesuffix' => '{{SITENAME}}',
#'wlheader-enotif' => '* Email notification is enabled.',
#'wlheader-showupdated' => '* Pages which have been changed since you last visited them are shown in \'\'\'bold\'\'\'',
#'wlhide' => 'Hide',
#'wlhideshowown' => '$1 my edits.',
#'wlnote' => 'Below are the last $1 changes in the last <b>$2</b> hours.',
#'wlsaved' => 'This is a saved version of your watchlist.',
#'wlshow' => 'Show',
#'wlshowlast' => 'Show last $1 hours $2 days $3',
/* 'wrong_wfQuery_params' => 'Incorrect parameters to wfQuery()<br />
Function: $1<br />
Query: $2
', */
'wrongpassword' => 'Pasahitza ez da zuzena. Saiatu berriro.',
'yourdiff' => 'Desberdintasunak',
#'yourdomainname' => 'Your domain',
'youremail' => 'Zure helbide elektronikoa (e-mail)*',
#'yourlanguage' => 'Language',
'yourname' => 'Zure erabiltzaile-izena',
'yournick' => 'Zure gaitzizena (sinatzeko)',
'yourpassword' => 'Zure pasahitza',
'yourpasswordagain' => 'Idatzi berriro pasahitza',
'yourrealname' => 'Zure benetako izena*',
'yourtext' => 'Zure testua',
#'yourvariant' => 'Variant',

);

class LanguageEu extends LanguageUtf8 {

	function getNamespaces() {
		global $wgNamespaceNamesEu;
		return $wgNamespaceNamesEu;
	}


	function getNsIndex( $text ) {
		global $wgNamespaceNamesEu, $wgSitename;

		foreach ( $wgNamespaceNamesEu as $i => $n ) {
			if ( 0 == strcasecmp( $n, $text ) ) { return $i; }
		}
		if( $wgSitename == 'Wikipédia' ) {
			if( 0 == strcasecmp( 'Wikipedia', $text ) ) return 4;
			if( 0 == strcasecmp( 'Discussion_Wikipedia', $text ) ) return 5;
		}
		return false;
	}

	function getQuickbarSettings() {
		global $wgQuickbarSettingsEu;
		return $wgQuickbarSettingsEu;
	}

	function getSkinNames() {
		global $wgSkinNamesEu;
		return $wgSkinNamesEu;
	}

	function getMessage( $key ) {
		global $wgAllMessagesEu;
		if( isset( $wgAllMessagesEu[$key] ) ) {
			return $wgAllMessagesEu[$key];
		} else {
			return parent::getMessage( $key );
		}
	}

}

?>
