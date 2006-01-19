<?php
/** Kurdish language file ( كوردي )
  *
  * @package MediaWiki
  * @subpackage Language
  */

require_once( "LanguageUtf8.php" );

if ( $wgMetaNamespace == "Wikipedia" ) {
	$wgMetaNamespace = "Wîkîpediya";
}

/* private */ $wgNamespaceNamesKu = array(
	NS_MEDIA            => 'Medya',
	NS_SPECIAL          => 'Taybet',
	NS_MAIN             => '',
	NS_TALK             => 'Nîqaş',
	NS_USER             => 'Bikarhêner',
	NS_USER_TALK        => 'Bikarhêner_nîqaş',
	NS_PROJECT          => $wgMetaNamespace,
	NS_PROJECT_TALK     => $wgMetaNamespace . '_nîqaş',
	NS_IMAGE            => 'Wêne',
	NS_IMAGE_TALK       => 'Wêne_nîqaş',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'MediaWiki_nîqaş',
	NS_TEMPLATE         => 'Şablon',
	NS_TEMPLATE_TALK    => 'Şablon_nîqaş',
	NS_HELP             => 'Alîkarî',
	NS_HELP_TALK        => 'Alîkarî_nîqaş',
	NS_CATEGORY         => 'Kategorî',
	NS_CATEGORY_TALK    => 'Kategorî_nîqaş'
) + $wgNamespaceNamesEn;

/* private */ $wgAllMessagesKy = array (

# copied from wikipedia

'1movedto2' => '$1 çû cihê $2',
'1movedto2_redir' => '$1 çû cihê $2 ser redirect',
'about' => 'Der barê',
'aboutpage' => 'Wîkîpediya:Der barê',
'aboutsite' => 'Der barê {{SITENAME}}',
#'accesskey-compareselectedversions' => 'v',
#'accesskey-diff' => 'd',
#'accesskey-minoredit' => 'i',
#'accesskey-preview' => 'p',
#'accesskey-save' => 's',
#'accesskey-search' => 'f',
'accmailtext' => 'Şîfreya \'$1\' hat şandin ji $2 re.',
'accmailtitle' => 'Şîfre hat şandin.',
'acct_creation_throttle_hit' => 'Biborîne te hesab $1 vekirine. Tu êdî nikare hesabine din vekî.',
'actioncomplete' => 'Çalakî temam',
'addedwatch' => 'Hat îlawekirinî listeya şopandinê',
/* 'addedwatchtext' => 'The page "$1" has been added to your [[Special:Watchlist|watchlist]].
Future changes to this page and its associated Talk page will be listed there,
and the page will appear \'\'\'bolded\'\'\' in the [[Special:Recentchanges|list of recent changes]] to
make it easier to pick out.

<p>If you want to remove the page from your watchlist later, click "Stop watching" in the sidebar.', */
'addgroup' => 'Komê tevlî bike',
#'addgrouplogentry' => 'Added group $2',
#'addsection' => '+',
'administrators' => 'Wîkîpediya:Koordînasyon',
'affirmation' => 'Ez diyar dikim ku xwediya/ê mafên nivîsariya vê dosyayê destûra xwe ji bo weşandinê di bin şertên lîsansê GFDL de daye. Binihêre: $1.',
'allarticles' => 'Hemû gotar',
#'allinnamespace' => 'All pages ($1 namespace)',
/* 'alllogstext' => 'Combined display of upload, deletion, protection, blocking, and sysop logs.
You can narrow down the view by selecting a log type, the user name, or the affected page.', */
'allmessages' => 'Hemû mesajên sîstemê',
#'allmessagescurrent' => 'Current text',
#'allmessagesdefault' => 'Default text',
#'allmessagesname' => 'Name',
#'allmessagesnotsupportedDB' => 'Special:AllMessages not supported because wgUseDatabaseMessages is off.',
#'allmessagesnotsupportedUI' => 'Your current interface language <b>$1</b> is not supported by Special:AllMessages at this site. ',
'allmessagestext' => 'Ev lîsteya hemû mesajên di namespace a MediaWiki: de ye.',
#'allnonarticles' => 'All non-articles',
#'allnotinnamespace' => 'All pages (not in $1 namespace)',
'allpages' => 'Hemû rûpel',
#'allpagesfrom' => 'Display pages starting at:',
'allpagesnext' => 'Pêş',
'allpagesprev' => 'Paş',
'allpagessubmit' => 'Biçe',
'alphaindexline' => '$1 heta $2',
#'already_bureaucrat' => 'This user is already a bureaucrat',
#'already_steward' => 'This user is already a steward',
#'already_sysop' => 'This user is already an administrator',
'alreadyloggedin' => '<strong>Bikarhêner $1, tu jixwe têketî!</strong><br />',
/* 'alreadyrolled' => 'Cannot rollback last edit of [[$1]]
by [[User:$2|$2]] ([[User talk:$2|Talk]]); someone else has edited or rolled back the page already.

Last edit was by [[User:$3|$3]] ([[User talk:$3|Talk]]). ', */
'ancientpages' => 'Gotarên kevintirîn',
'and' => 'û',
'anontalk' => 'Nîqaş ji bo vê IPê',
'anontalkpagetext' => '----
\'\'Ev rûpela nîqaşê ye ji bo bikarhênerên nediyarkirî ku hîn hesabekî xwe çênekirine an jî bikarnaînin. Ji ber vê yekê divê em wan bi [[IP address|navnîşana IP]] ya hejmarî nîşan bikin. Navnîşaneke IP dikare ji aliyê gelek kesan ve were bikaranîn. Heger tu bikarhênerekî nediyarkirî bî û bawerdikî ku nirxandinên bê peywend di der barê te de hatine kirin ji kerema xwe re [[Special:Userlogin|hesabekî xwe veke an jî têkeve Ensîklopediya Azad]] da ku tu xwe ji tevlîheviyên bi bikarhênerên din re biparêzî.\'\'',
'anonymous' => 'Bikarhênera/ê nediyarkirî ya/yê wîkîpediyayê',
'apr' => 'avr',
'april' => 'avrêl',
'article' => 'Gotar',
'articleexists' => 'Rûpela bi vî navî heye, an navê ku te hilbijart derbas nabe. Navekî din hilbijêre.',
'articlepage' => 'Li naveroka rûpelê binêre',
'aug' => 'teb',
'august' => 'tebax',
#'autoblocker' => 'Autoblocked because you share an IP address with "$1". Reason "$2".',
#'badaccess' => 'Permission error',
/* 'badaccesstext' => 'The action you have requested is limited
to users with the "$2" permission assigned.
See $1.', */
'badarticleerror' => 'Ev çalakî di vê rûpelê de nabe.',
'badfilename' => 'Navê wêneyê hat guherandin û bû "$1".',
'badfiletype' => 'Formata ".$1" naye tawsiye kirin. (Ji bo wêne .png û .jpg tên tawsiye kirin.)',
'badipaddress' => 'Bikarhêner bi vî navî tune',
#'badquery' => 'Badly formed search query',
/* 'badquerytext' => 'We could not process your query.
This is probably because you have attempted to search for a
word fewer than three letters long, which is not yet supported.
It could also be that you have mistyped the expression, for
example "fish and and scales".
Please try another query.', */
'badretype' => 'Herdu şîfreyên ku te nivîsîn hevûdin nagirin.',
'badtitle' => 'Sernivîsa nebaş',
/* 'badtitletext' => 'The requested page title was invalid, empty, or
an incorrectly linked inter-language or inter-wiki title.', */
#'blanknamespace' => '(Main)',
'blockedtext' => 'Navê bikarhêner an jî navnîşana IP ya te ji aliyê $1 hat bloke kirin. Sedema vê ev  e:<br />\'\'$2\'\'<p>Tu dikarî bi $1 an yek ji [{{ns:project}}:Koordînator|koordînatorên din]] re ser vê blokê nîqaş bikî.

Têbînî: Tu nikarî fonksiyona "Ji vê bikarhêner re E-mail bişîne" bi kar bîne eger te navnîşana email a xwe di "[[Special:Preferences|Tercîhên min]]" de nenivîsand.

Navnîşana te ya IP $3 ye. Ji kerema xwe eger pirsên te hebe vê navnîşanê bibêje.

==Note to AOL users==
Due to continuing acts of vandalism by one particular AOL user, Wikipedia often blocks AOL proxies. Unfortunately, a single proxy server may be used by a large number of AOL users, and hence innocent AOL users are often inadvertently blocked. We apologise for any inconvenience caused.

If this happens to you, please email an administrator, using an AOL email address. Be sure to include the IP address given above.',
'blockedtitle' => 'Bikarhêner hat bloke kirin',
'blockip' => 'Bikarhêner asteng bike',
'blockipsuccesssub' => 'Blok serkeftî',
'blockipsuccesstext' => '"$1" hat asteng kirin.
<br />Bibîne [[Special:Ipblocklist|Lîsteya IP\'yan hatî asteng kirin]] ji bo lîsteya blokan.',
/* 'blockiptext' => 'Use the form below to block write access
from a specific IP address or username.
This should be done only only to prevent vandalism, and in
accordance with [[Project:Policy|policy]].
Fill in a specific reason below (for example, citing particular
pages that were vandalized).', */
'blocklink' => 'asteng bike',
'blocklistline' => '$1, $2 $3 bloke kir ($4)',
'blocklogentry' => '"$1" bloke kir',
#'blocklogpage' => 'Block_log',
/* 'blocklogtext' => 'This is a log of user blocking and unblocking actions. Automatically
blocked IP addresses are not listed. See the [[Special:Ipblocklist|IP block list]] for
the list of currently operational bans and blocks.', */
'bold_sample' => 'Nivîsa qalind',
'bold_tip' => 'Nivîsa qalind',
'booksources' => 'Çavkaniyên pirtûkan',
/* 'booksourcetext' => 'Below is a list of links to other sites that
sell new and used books, and may also have further information
about books you are looking for.
{{SITENAME}} is not affiliated with any of these businesses, and
this list should not be construed as an endorsement.', */
'brokenredirects' => 'Ragihandinên jê bûye',
#'brokenredirectstext' => 'The following redirects link to a non-existing pages.',
'bugreports' => 'Raporên çewtiyan',
#'bugreportspage' => 'Project:Bug_reports',
#'bureaucratlog' => 'Bureaucrat_log',
#'bureaucratlogentry' => 'Changed group membership for $1 from $2 to $3',
'bydate' => 'li gor dîrokê',
'byname' => 'li gor navê',
'bysize' => 'li gor mezinayiyê',
#'cachederror' => 'The following is a cached copy of the requested page, and may not be up to date.',
'cancel' => 'Betal',
#'cannotdelete' => 'Could not delete the page or file specified. (It may have already been deleted by someone else.)',
#'cantrollback' => 'Cannot revert edit; last contributor is only author of this page.',
'categories' => 'Kategoriyên rûpelan',
'categoriespagetext' => 'Di vê wîkiyê de ev kategorî hene:',
'category' => 'kategorî',
'category_header' => 'Gotarên di kategoriya "$1" de',
'categoryarticlecount' => 'Di vê kategoriyê de $1 gotar hene.',
'categoryarticlecount1' => 'Di vê kategoriyê de $1 gotar heye.',
#'changed' => 'changed',
#'changegrouplogentry' => 'Changed group $2',
'changepassword' => 'Şîfre biguherîne',
'changes' => 'guherandin',
#'clearyourcache' => "'''Note:''' After saving, you may have to bypass your browser's cache to see the changes. '''Mozilla / Firefox / Safari:''' hold down ''Shift'' while clicking ''Reload'', or press ''Ctrl-Shift-R'' (''Cmd-Shift-R'' on Apple Mac); '''IE:''' hold ''Ctrl'' while clicking ''Refresh'', or press ''Ctrl-F5''; '''Konqueror:''': simply click the ''Reload'' button, or press ''F5''; '''Opera''' users may need to completely clear their cache in ''Tools&rarr;Preferences''.",
'columns' => 'stûn',
'compareselectedversions' => 'Guhertoyan bide ber hev',
'confirm' => 'Teyît bike',
'confirmdelete' => 'Teyîda jêbirinê',
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
'contribslink' => 'tevkarî',
'contribsub' => 'Ji bo $1',
'contributions' => 'Tevkariyên vê bikarhêner',
'copyright' => 'Naverok bin $1 (Lîsansa Belgekirina Azada GNU) tê weşandin.',
'copyrightpage' => 'Wîkîpediya:Mafên nivîsanê',
'copyrightpagename' => 'Mafên nivîsanê',
'copyrightwarning' => '<div class="plainlinks" style="margin-top:1px;border-width:1px;border-style:solid;border-color:#aaaaaa;padding:2px;">
{|border=0|
|
[[metawikipedia:Help:Special_characters|Nîşanên taybet]]:
<charinsert>Ç ç </charinsert> ·
<charinsert>Ê ê </charinsert> ·
<charinsert>Î î </charinsert> ·
<charinsert>Ş ş </charinsert> ·
<charinsert>Û û </charinsert> ·
<charinsert>&ndash; &mdash; </charinsert> ·
<charinsert>[+] [[+]] {{+}} </charinsert> ·
<charinsert>~ | °</charinsert> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
|<div class="plainlinks" dir="rtl" align="right">
<charinsert>ئا  </charinsert> ·
<charinsert>ڵ </charinsert> ·
<charinsert>ڤ </charinsert> ·
<charinsert>ۆ </charinsert> ·
<charinsert>ێ </charinsert> ·
<charinsert>ڕ </charinsert> ·
<charinsert>ئه </charinsert> ·
<charinsert>ه </charinsert>

</div>
|}
</div>
<b>BERHEMÊN MAFÊN WAN PARASTÎ (©) BÊ DESTÛR NEWEŞÎNE!</b>
<div>Dîqat bike: Hemû tevkariyên Wîkîpediya di bin [[Lîsansa Belgekirina azada GNU]] de tên belav kirin.
<div>Eger tu nexwazî ku nivîsên te bê dilrehmî bên guherandin û li gora keyfa herkesî bên belavkirin, li vir neweşîne.</div>
<div>Tu soz didî ku te ev bi xwe nivîsand an jî ji çavkaniyekê azad an geliyane \'\'(public domain)\'\' girt.',
/* 'copyrightwarning2' => 'Please note that all contributions to {{SITENAME}}
may be edited, altered, or removed by other contributors.
If you don\'t want your writing to be edited mercilessly, then don\'t submit it here.<br />
You are also promising us that you wrote this yourself, or copied it from a
public domain or similar free resource (see $1 for details).
<strong>DO NOT SUBMIT COPYRIGHTED WORK WITHOUT PERMISSION!</strong>', */
#'couldntremove' => 'Couldn\'t remove item \'$1\'...',
'createaccount' => 'Hesabê nû çêke',
'createaccountmail' => 'bi e-name',
#'createarticle' => 'Create article',
#'created' => 'created',
#'creditspage' => 'Page credits',
'cur' => 'ferq',
'currentevents' => 'Bûyerên rojane',
'currentevents-url' => 'Bûyerên rojane',
'currentrev' => 'Revîzyona niha',
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
'deadendpages' => 'Rûpelên bê dergeh',
#'debug' => 'Debug',
'dec' => 'ber',
'december' => 'Berfanbar',
#'default' => 'default',
#'defaultns' => 'Search in these namespaces by default:',
'defemailsubject' => 'Wîkîpediya e-name',
'delete' => 'Jê bibe',
#'delete_and_move' => 'Delete and move',
#'delete_and_move_reason' => 'Deleted to make way for move',
/* 'delete_and_move_text' => '==Deletion required==

The destination article "[[$1]]" already exists. Do you want to delete it to make way for the move?', */
'deletecomment' => 'Sedema jêbirinê',
'deletedarticle' => '"$1" hat jêbirin',
#'deletedrev' => '[deleted]',
#'deletedrevision' => 'Deleted old revision $1.',
'deletedtext' => '"$1" hat jêbirin. Ji bo qeyda rûpelên ku di dema nêzîk de hatin jêbirin binêre $2.',
'deleteimg' => 'jêbibe',
#'deleteimgcompletely' => 'Delete all revisions',
'deletepage' => 'Rûpelê jê bibe',
#'deletesub' => '(Deleting "$1")',
'deletethispage' => 'Vê rûpelê jê bibe',
#'deletionlog' => 'deletion log',
#'dellogpage' => 'Deletion_log',
#'dellogpagetext' => 'Below is a list of the most recent deletions.',
#'destfilename' => 'Destination filename',
/* 'developertext' => 'The action you have requested can only be
performed by users with "developer" status.
See $1.', */
#'developertitle' => 'Developer access required',
'diff' => 'cudahî',
'difference' => '(Ferq nav revîzyonan)',
'disambiguations' => 'Rûpelên cudakirinê',
#'disambiguationspage' => 'Project:Links_to_disambiguating_pages',
#'disambiguationstext' => 'The following pages link to a <i>disambiguation page</i>. They should link to the appropriate topic instead.<br />A page is treated as disambiguation if it is linked from $1.<br />Links from other namespaces are <i>not</i> listed here.',
#'disclaimerpage' => 'Project:General_disclaimer',
'disclaimers' => 'Ferexetname',
#'doubleredirects' => 'Double Redirects',
#'doubleredirectstext' => 'Each row contains links to the first and second redirect, as well as the first line of the second redirect text, usually giving the "real" target page, which the first redirect should point to.',
/* 'eauthentsent' => 'A confirmation email has been sent to the nominated email address.
Before any other mail is sent to the account, you will have to follow the instructions in the email,
to confirm that the account is actually yours.', */
'edit' => 'Biguherîne',
#'edit-externally' => 'Edit this file using an external application',
#'edit-externally-help' => 'See the [http://meta.wikimedia.org/wiki/Help:External_editors setup instructions] for more information.',
#'editcomment' => 'The edit comment was: "<i>$1</i>".',
'editconflict' => 'Têkçûna guherandinan: $1',
#'editcurrent' => 'Edit the current version of this page',
#'editgroup' => 'Edit Group',
'edithelp' => 'Alîkarî ji bo guherandin',
'edithelppage' => 'Wîkîpediya:Rûpeleke_çawa_biguherînim',
'editing' => 'Biguherîne: "$1"',
'editingcomment' => '$1 (şîrove) tê guherandin.',
'editingold' => '<strong>HÎŞYAR: Tu ser revîsyoneke kevn a vê rûpelê dixebitî.
Eger tu qeyd bikî, hemû guhertinên ji vê revîzyonê piştre winda dibin.
</strong>',
'editingsection' => 'Tê guherandin: $1 (beş)',
'editsection' => 'biguherîne',
'editthispage' => 'Vê rûpelê biguherîne',
#'editusergroup' => 'Edit User Groups',
#'email' => 'Email',
#'emailauthenticated' => 'Your email address was authenticated on $1.',
#'emailconfirmlink' => 'Confirm your e-mail address',
#'emailflag' => 'Disable e-mail from other users',
'emailforlost' => '* Nivîsandina navnîşana te \'\'ne mecbûrî\'\' ye. Lê eger tu navnîşana xwe binîvîsî, mirov dikare e-mailekê ji te re bişîne bêyî ku navnîşana te zanibe. Her wiha, eger tu şîfreya xwe ji bîr bikî, Wîkîpediya dikare şîfreya te bişîne ji vê navnîşana te re.',
'emailfrom' => 'Ji',
'emailmessage' => 'Name',
/* 'emailnotauthenticated' => 'Your email address is <strong>not yet authenticated</strong>. No email
will be sent for any of the following features.', */
'emailpage' => 'E-name bikarhêner',
/* 'emailpagetext' => 'If this user has entered a valid e-mail address in
his or her user preferences, the form below will send a single message.
The e-mail address you entered in your user preferences will appear
as the "From" address of the mail, so the recipient will be able
to reply.', */
'emailsend' => 'Bişîne',
#'emailsent' => 'E-mail sent',
'emailsenttext' => 'E-nameya te hat şandin.',
'emailsubject' => 'Mijar',
#'emailto' => 'To',
'emailuser' => 'Ji vê/î bikarhênerê/î re e-name bişîne',
#'emptyfile' => 'The file you uploaded seems to be empty. This might be due to a typo in the file name. Please check whether you really want to upload this file.',
/* 'enterlockreason' => 'Enter a reason for the lock, including an estimate
of when the lock will be released', */
'error' => 'Çewtî (Error)',
'errorpagetitle' => 'Çewtî (Error)',
#'exbeforeblank' => 'content before blanking was: \'$1\'',
'exblank' => 'rûpel vala bû',
'excontent' => 'Naveroka berê:',
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
#'exif-orientation-6' => 'Roatated 90° CW',
#'exif-orientation-7' => 'Roateted 90° CW and flipped vertically',
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
#'exif-resolutionunit-2' => 'inches',
#'exif-resolutionunit-3' => 'centimetres',
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
#'exif-xresolution' => 'Image resolution in width direction',
#'exif-ycbcrcoefficients' => 'Color space transformation matrix coefficients',
#'exif-ycbcrpositioning' => 'Y and C positioning',
#'exif-ycbcrsubsampling' => 'Subsampling ratio of Y to C',
#'exif-yresolution' => 'Image resolution in height direction',
'explainconflict' => 'Ji dema te dest bi guherandinê kir heta niha kesekê/î din ev rûpel guherand.

Jor guhartoya heyî tê dîtîn. Guherandinên te jêr tên nîşan dan. Divê tû wan bikî yek. Heke niha tomar bikî, <b>bi tene</b> nivîsara qutiya jor wê bê tomarkirin. <p>',
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
'extlink_sample' => 'http://www.minak.com navê lînkê',
'extlink_tip' => 'Lînka derve (http:// di destpêkê de ji bîr neke)',
'faq' => 'Pirs û Bersîv (FAQ)',
'faqpage' => 'Wîkîpediya:Pirs û Bersîv',
'feb' => 'reş',
'february' => 'reşemî',
#'feedlinks' => 'Feed:',
#'filecopyerror' => 'Could not copy file "$1" to "$2".',
#'filedeleteerror' => 'Could not delete file "$1".',
'filedesc' => 'Kurte',
#'fileexists' => 'A file with this name exists already, please check $1 if you are not sure if you want to change it.',
#'fileinfo' => '$1KB, MIME type: <code>$2</code>',
#'filemissing' => 'File missing',
'filename' => 'Navê dosyayê',
'filenotfound' => 'Dosya bi navê "$1" nehat dîtin.',
#'filerenameerror' => 'Could not rename file "$1" to "$2".',
#'files' => 'Files',
'filesource' => 'Çavkanî',
#'filestatus' => 'Copyright status',
'fileuploaded' => 'Barkirina dosyaya bi navê $1 serkeftî.
Ji kerema xwe, biçe: $2 û agahî li der barê dosyayê binivîse (ji ku derê hat girtin, kîngê hat çêkirin, kê çêkir û hwd.)

Heke ev dosya wêneyek be, bi vî rengî bi kar bîne:
<br />
<tt><nowiki>[[Wêne:$1|thumb|Binnivîs]]</nowiki></tt>',
#'formerror' => 'Error: could not submit form',
'friday' => 'În',
#'geo' => 'GEO coordinates',
#'getimagelist' => 'fetching file list',
'go' => 'Gotar',
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
'groups-editgroup-name' => 'Group name:',
/* 'groups-editgroup-preamble' => 'If the name or description starts with a colon, the
remainder will be treated as a message name, and hence the text will be localised
using the MediaWiki namespace', */
#'groups-existing' => 'Existing groups',
'groups-group-edit' => 'Existing groups:',
#'groups-lookup-group' => 'Manage group rights',
#'groups-noname' => 'Please specify a valid group name',
#'groups-tableheader' => 'ID || Name || Description || Rights',
#'guesstimezone' => 'Fill in from browser',
'headline_sample' => 'Nivîsara sernameyê',
'headline_tip' => 'Sername asta 2',
'help' => 'Alîkarî',
'helppage' => 'Wîkîpediya:Alîkarî',
'hide' => 'veşêre',
'hidetoc' => 'veşêre',
'hist' => 'dîrok',
#'histfirst' => 'Earliest',
#'histlast' => 'Latest',
'histlegend' => 'Legend: (ferq) = cudayî nav vê û versiyon a niha,
(berê) = cudayî nav vê û yê berê vê, B = guhêrka biçûk',
'history' => 'Dîroka rûpelê',
#'history_copyright' => '-',
'history_short' => 'Dîrok',
#'historywarning' => 'Warning: The page you are about to delete has a history: ',
'hr_tip' => 'Rastexêza berwarî (kêm bi kar bîne)',
'ignorewarning' => 'Hişyarê qebûl neke û dosyayê qeyd bike.',
#'illegalfilename' => 'The filename "$1" contains characters that are not allowed in page titles. Please rename the file and try uploading it again.',
'ilsubmit' => 'Lêbigere',
'image_sample' => 'Mînak.jpg',
'image_tip' => 'Wêne li hundirê gotarê',
'imagelinks' => 'Lînkên wêneyê',
'imagelist' => 'Listeya wêneyan',
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
'imghistory' => 'Dîroka wêneyê',
#'imglegend' => 'Legend: (desc) = show/edit image description.',
#'immobile_namespace' => 'Destination title is of a special type; cannot move pages into that namespace.',
#'import' => 'Import pages',
#'importfailed' => 'Import failed: $1',
#'importhistoryconflict' => 'Conflicting history revision exists (may have imported this page before)',
#'importinterwiki' => 'Transwiki import',
#'importnosources' => 'No transwiki import sources have been defined and direct history uploads are disabled.',
'importnotext' => 'Vala an nivîs tune',
#'importsuccess' => 'Import succeeded!',
#'importtext' => 'Please export the file from the source wiki using the Special:Export utility, save it to your disk and upload it here.',
'info_short' => 'Agahî',
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
'ipboptions' => '2 hours,1 day,3 days,1 week,2 weeks,1 month,3 months,6 months,1 year,infinite',
#'ipbother' => 'Other time',
#'ipbotheroption' => 'other',
'ipbreason' => 'Sedem',
'ipbsubmit' => 'Vê bikarhêner bloke bike',
#'ipusubmit' => 'Unblock this address',
#'ipusuccess' => '"[[$1]]" unblocked',
#'isbn' => 'ISBN',
'isredirect' => 'rûpela ragihandinê',
'italic_sample' => 'Nivîsa xwar (îtalîk)',
'italic_tip' => 'Nivîsa xwar (îtalîk)',
#'iteminvalidname' => 'Problem with item \'$1\', invalid name...',
'jan' => 'rêb',
'january' => 'Rêbendan',
'jul' => 'tîr',
'july' => 'Tîrmeh',
'jun' => 'pşr',
'june' => 'pûşper',
#'laggedslavemode' => 'Warning: Page may not contain recent updates.',
#'largefile' => 'It is recommended that images not exceed $1 bytes in size, this file is $2 bytes',
'last' => 'berê',
'lastmodified' => 'Ev rûpel carî dawî di $1 de hat guherandin.',
#'lastmodifiedby' => 'This page was last modified $1 by $2.',
'lineno' => 'Rêza $1:',
'link_sample' => 'Navê lînkê',
'link_tip' => 'Lînka hundir',
'linklistsub' => '(Listeya lînkan)',
'linkshere' => 'Di van rûpelên de lînkek ji vê re heye:',
'linkstoimage' => 'Di van rûpelên de lînkek ji vê wêneyê re heye:',
#'linktrail' => '/^([a-z]+)(.*)$/sD',
'listform' => 'lîste',
'listingcontinuesabbrev' => ' dewam',
'listusers' => 'Lîsteya bikarhêneran',
#'loadhist' => 'Loading page history',
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
'login' => 'Têkeve (login)',
#'loginend' => '&nbsp;',
'loginerror' => 'Çewtî (Login error)',
'loginpagetitle' => 'Qeyda bikarhêner (User login)',
'loginproblem' => '<b>Di qeyda te (login) de pirsgirêkek derket.</b><br />Careke din biceribîne!',
'loginprompt' => '<b>Eger tu xwe nû qeyd bikî, nav û şîfreya xwe hilbijêre.</b> Ji bo xwe qeyd kirinê di Wîkîpediya de divê ku \'\'cookies\'\' gengaz be.',
'loginreqtitle' => 'Têketin pêwîst e',
'loginsuccess' => 'Tu niha di Wîkîpediya de qeydkirî yî wek "$1".',
'loginsuccesstitle' => 'Têketin serkeftî!',
'logout' => 'Derkeve (log out)',
'logouttext' => 'Tu niha derketî (logged out).
Tu dikarî wîkîpediyayê niha weke bikarhênerekî nediyarkirî bikarbînî, yan jî tu dikarî dîsa bi vî navê xwe yan navekî din wek bikarhêner têkevî wikipêdiyayê. Bila di bîra te de be ku gengaz e hin rûpel mîna ku tu hîn bi navê xwe qeyd kiriyî werin nîşandan, heta ku tu nîşanên çavlêgerandina (browser) xwe jênebî.',
'logouttitle' => 'Derketina bikarhêner',
'lonelypages' => 'Rûpelên sêwî',
'longpages' => 'Rûpelên dirêj',
'longpagewarning' => 'HIŞYAR: Drêjahiya vê rûpelê $1 kB (kilobayt) e, ev pir e. Dibe ku çend \'\'browser\'\'
baş nikarin rûpelên ku ji 32 kB drêjtir in biguherînin. Eger tu vê rûpelê beş beş bikî gelo ne çêtir e?',
#'mailerror' => 'Error sending mail: $1',
'mailmypassword' => 'Şîfreyeke nû bi e-mail ji min re bişîne',
'mailnologin' => 'Navnîşan neşîne',
/* 'mailnologintext' => 'You must be [[Special:Userlogin|logged in]]
and have a valid e-mail address in your [[Special:Preferences|preferences]]
to send e-mail to other users.', */
'mainpage' => 'Serûpel',
/* 'mainpagedocfooter' => 'Please see [http://meta.wikipedia.org/wiki/MediaWiki_i18n documentation on customizing the interface]
and the [http://meta.wikipedia.org/wiki/MediaWiki_User%27s_Guide User\'s Guide] for usage and configuration help.', */
#'mainpagetext' => 'Wiki software successfully installed.',
'maintenance' => 'Rûpela tamîratê (Maintenance)',
#'maintenancebacklink' => 'Back to Maintenance Page',
#'maintnancepagetext' => 'This page includes several handy tools for everyday maintenance. Some of these functions tend to stress the database, so please do not hit reload after every item you fixed ;-)',
#'makesysop' => 'Make a user into a sysop',
#'makesysopfail' => '<b>User "$1" could not be made into a sysop. (Did you enter the name correctly?)</b>',
'makesysopname' => 'Navê bikarhêner:',
#'makesysopok' => '<b>User "$1" is now a sysop</b>',
#'makesysopsubmit' => 'Make this user into a sysop',
/* 'makesysoptext' => 'This form is used by bureaucrats to turn ordinary users into administrators.
Type the name of the user in the box and press the button to make the user an administrator', */
#'makesysoptitle' => 'Make a user into a sysop',
'mar' => 'adr',
'march' => 'adar',
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
'may' => 'gul',
'may_long' => 'gulan',
#'media_sample' => 'Example.ogg',
#'media_tip' => 'Media file link',
/* 'mediawarning' => '\'\'\'Warning\'\'\': This file may contain malicious code, by executing it your system may be compromised.
<hr>', */
#'metadata' => 'Metadata',
#'metadata_page' => 'Wikipedia:Metadata',
#'minlength' => 'Image names must be at least three letters.',
'minoredit' => 'Ev guheraniyekê biçûk e',
'minoreditletter' => 'B',
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
'monday' => 'duşem',
'moredotdotdot' => 'Zêde...',
'move' => 'Nav biguherîne',
'movearticle' => 'Rûpelê bigerîne',
'movedto' => 'bû',
#'movelogpage' => 'Move log',
#'movelogpagetext' => 'Below is a list of page moved.',
'movenologin' => 'Xwe qeyd nekir',
/* 'movenologintext' => 'You must be a registered user and [[Special:Userlogin|logged in]]
to move a page.', */
'movepage' => 'Vê rûpelê bigerîne',
'movepagebtn' => 'Vê rûpelê bigerîne',
'movepagetalktext' => 'Rûpela axaftinê (talk) giredayî ji vê rûpelê re wê bê gerandin jî.
\'\'\'Îstisna:\'\'\'
*You are moving the page across namespaces,
*A non-empty talk page already exists under the new name, or
*You uncheck the box below.

In those cases, you will have to move or merge the page manually if desired.',
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
'movetalk' => 'Rûpela "talk" (axaftin) jî bigerîne, eger gengaz be.',
'movethispage' => 'Vê rûpelê bigerîne',
#'mw_math_html' => 'HTML if possible or else PNG',
#'mw_math_mathml' => 'MathML if possible (experimental)',
#'mw_math_modern' => 'Recommended for modern browsers',
#'mw_math_png' => 'Always render PNG',
#'mw_math_simple' => 'HTML if very simple or else PNG',
#'mw_math_source' => 'Leave it as TeX (for text browsers)',
'mycontris' => 'Tevkariyên min',
'mypage' => 'Rûpela min',
'mytalk' => 'Rûpela nîqaşa min',
#'namespace' => 'Namespace:',
'navigation' => 'Navîgasyon',
'nbytes' => '$1 bayt',
'nchanges' => '$1 guherandin',
'newarticle' => '(Nû)',
'newarticletext' => '<div style="font-size:small;color:#003333;border-width:1px;border-style:solid;border-color:#aaaaaa;padding:3px">
Ev rûpel hîn tune. Eger tu bixwazî vê rûpelê çêkî, dest bi nivîsandinê bike û piştre qeyd bike. \'\'\'Wêrek be\'\'\', biceribîne!<br />
Ji bo alîkarî binêre: [[Wîkîpediya:Alîkarî|Alîkarî]].<br />
Eger tu bi şaştî hatî, bizivire rûpela berê.
</div>',
'newbies' => 'ecemî',
'newimages' => 'Pêşangeha wêneyên nû',
'newmessages' => '$1 ji bo te heye.',
'newmessageslink' => 'Nameyên nû',
'newpage' => 'Rûpela nû',
'newpageletter' => 'Nû',
'newpages' => 'Rûpelên nû',
'newpassword' => 'Şîfreya nû',
#'newtitle' => 'To new title',
'newusersonly' => '(ji bo bikarhênerên nû)',
#'newwindow' => '(opens in new window)',
'next' => 'pêş',
#'nextdiff' => 'Next diff →',
'nextn' => '$1 pêş',
'nextpage' => 'Rûpela pêşî ($1)',
#'nextrevision' => 'Newer revision→',
'nlinks' => '$1 lînk',
'noaffirmation' => 'Pêwîst e tu teyît bikî ku barkirin mafên nivîsanê îhlal neke.',
'noarticletext' => '<div style="border: 1px solid #ccc; padding: 7px; background-color: #fff; color: #000">\'\'\'Di Wîkîpediyayê de rûpeleke bi vî navî hîn tune.\'\'\'
* \'\'\'[{{SERVER}}{{localurl:{{NAMESPACE}}:{{PAGENAME}}|action=edit}} Dest bi nivîsandina gotara "{{PAGENAME}}" bike]\'\'\'
* \'\'\'Heke te niha vê rûpelê çekiribe û niha xuya neke, vê demê dereng tê. Bisekine û rûpelê taze bike.\'\'\'
* [[{{ns:special}}:Search/{{PAGENAME}}|Di nav gotarên din de li "{{PAGENAME}}" bigere]]
* [http://ku.wiktionary.org/wiki/{{NAMESPACE}}:{{PAGENAME}} Di \'\'\'Wîkîferheng, ferhenga azad\'\'\' de li rûpela "{{PAGENAME}}" binihêre]
</div>',
'noconnect' => 'Bibexşîne! Çend pirsgrêkên teknîkî heye, girêdan ji pêşkêşvanê (suxrekirê, server) re niha ne gengaz e.',
#'nocontribs' => 'No changes were found matching these criteria.',
'nocookieslogin' => 'Ji bo qeydkirina bikarhêneran Wîkîpediya "cookies" bi kar tîne. Te fonksiyona "cookies" girt. Ji kerema xwe "cookies" gengaz bike û careke din biceribîne.',
#'nocookiesnew' => 'The user account was created, but you are not logged in. {{SITENAME}} uses cookies to log in users. You have cookies disabled. Please enable them, then log in with your new username and password.',
#'nocreativecommons' => 'Creative Commons RDF metadata disabled for this server.',
#'nocredits' => 'There is no credits info available for this page.',
#'nodb' => 'Could not select database $1',
#'nodublincore' => 'Dublin Core RDF metadata disabled for this server.',
'noemail' => 'Navnîşana bikarhênerê/î "$1" nehat tomar kirine.',
/* 'noemailprefs' => '<strong>No email address has been specified</strong>, the following
features will not work.', */
/* 'noemailtext' => 'This user has not specified a valid e-mail address,
or has chosen not to receive e-mail from other users.', */
'noemailtitle' => 'Navnîşana e-name tune',
'nogomatch' => 'Rûpeleke wek vî navî tune.

Tu dixwazî <b><a href="$1" class="new">vê gotarê binivîsî</a></b> ?',
#'nohistory' => 'There is no edit history for this page.',
#'noimages' => 'Nothing to see.',
'nolinkshere' => 'Ji hîç rûpel ji vê re lînk tune.',
'nolinkstoimage' => 'Rûpeleke ku ji vê wêneyê re lînk dike tune.',
'noname' => 'Navê ku te nivîsand derbas nabe.',
/* 'nonefound' => '\'\'\'Note\'\'\': unsuccessful searches are
often caused by searching for common words like "have" and "from",
which are not indexed, or by specifying more than one search term (only pages
containing all of the search terms will appear in the result).', */
#'nonunicodebrowser' => '<strong>WARNING: Your browser is not unicode compliant, please change it before editing an article.</strong>',
#'nospecialpagetext' => 'You have requested an invalid special page, a list of valid special pages may be found at [[{{ns:special}}:Specialpages]].',
'nosuchaction' => 'Çalakiyek bi vê rengê tune',
/* 'nosuchactiontext' => 'The action specified by the URL is not
recognized by the wiki', */
'nosuchspecialpage' => 'Rûpeleke taybet bi vê rengê tune',
'nosuchuser' => 'Bikarhênera/ê bi navê "$1" tune. Navê rast binivîse an bi vê formê <b>hesabeke nû çêke</b>. (Ji bo hevalên nû "Têkeve" çênabe!)',
#'nosuchusershort' => 'There is no user by the name "$1". Check your spelling.',
#'notacceptable' => 'The wiki server can\'t provide data in a format your client can read.',
'notanarticle' => 'Ne gotar e',
/* 'notargettext' => 'You have not specified a target page or user
to perform this function on.', */
'notargettitle' => 'Hedef tune',
'note' => '<strong>Not:</strong>',
'notextmatches' => 'Di nivîsarê de nehat dîtin.',
'notitlematches' => 'Di nav sernivîsan de nehat dîtin.',
'notloggedin' => 'Xwe qeyd nekir (not logged in)',
'nov' => 'ser',
'november' => 'sermawez',
#'nowatchlist' => 'You have no items on your watchlist.',
'nowiki_sample' => 'Nivîs ku nebe formatkirin',
#'nowiki_tip' => 'Ignore wiki formatting',
'nstab-category' => 'Kategorî',
'nstab-help' => 'Alîkarî',
'nstab-image' => 'Wêne',
'nstab-main' => 'Gotar',
'nstab-media' => 'Medya',
'nstab-mediawiki' => 'Mesaj',
'nstab-special' => 'Taybet',
'nstab-template' => 'Şablon',
'nstab-user' => 'Bikarhêner',
'nstab-wp' => 'Der barê',
#'numauthors' => 'Number of distinct authors (article): $1',
#'number_of_watching_users_RCview' => '[$1]',
#'number_of_watching_users_pageview' => '[$1 watching user/s]',
#'numedits' => 'Number of edits (article): $1',
#'numtalkauthors' => 'Number of distinct authors (discussion page): $1',
#'numtalkedits' => 'Number of edits (discussion page): $1',
#'numwatchers' => 'Number of watchers: $1',
#'nviews' => '$1 views',
'oct' => 'kew',
'october' => 'kewçêr',
'ok' => 'Temam',
'oldpassword' => 'Şîfreya kevn',
'orig' => 'orîj',
'orphans' => 'Rûpelên sêwî',
#'othercontribs' => 'Based on work by $1.',
'otherlanguages' => 'Zimanên din',
#'others' => 'others',
'pagemovedsub' => 'Gerandin serkeftî',
'pagemovedtext' => 'Rûpela "[[$1]]" çû cihê "[[$2]]".',
#'pagetitle' => '$1 - {{SITENAME}}',
/* 'passwordremindertext' => 'Someone (probably you, from IP address $1)
requested that we send you a new {{SITENAME}} login password.
The password for user "$2" is now "$3".
You should log in and change your password now.', */
#'passwordremindertitle' => 'Password reminder from {{SITENAME}}',
'passwordsent' => 'Ji navnîşana e-mail ku ji bo "$1" hat tomarkirin şîfreyekê nû hat şandin. Vê bistîne û dîsa têkeve.',
#'passwordtooshort' => 'Your password is too short. It must have at least $1 characters.',
#'perfcached' => 'The following data is cached and may not be completely up to date:',
/* 'perfdisabled' => 'Sorry! This feature has been temporarily disabled
because it slows the database down to the point that no one can use
the wiki.', */
#'perfdisabledsub' => 'Here\'s a saved copy from $1:',
#'personaltools' => 'Personal tools',
'popularpages' => 'Rûpelên populer',
'portal' => 'Portala komê',
'portal-url' => 'Wîkîpediya:Portala komê',
'postcomment' => 'Şîroveyekê bişîne',
#'poweredby' => '{{SITENAME}} is powered by [http://www.mediawiki.org/ MediaWiki], an open source wiki engine.',
'powersearch' => 'Lêbigere',
'powersearchtext' => 'Lêgerîn di nav cihên navan de:<br />
$1<br />
$2 Ragihandinan nîşan bide &nbsp; Lêbigere: $3 $9',
'preferences' => 'Tercîhên min',
#'prefs-help-email' => '² Email (optional): Enables others to contact you through your user or user_talk page without the need of revealing your identity.',
#'prefs-help-email-enotif' => 'This address is also used to send you email notifications if you enabled the options.',
#'prefs-help-realname' => '¹ Real name (optional): if you choose to provide it this will be used for giving you attribution for your work.',
'prefs-misc' => 'Eyaren cuda',
'prefs-personal' => 'Agahiyên bikarhênerê/î',
#'prefs-rc' => 'Recent changes & stubs',
'prefslogintext' => 'Te xwe wek "$1" qeyd kir.
Numareya ID ya te ya întern $2 ye.

Binêre [[Wîkîpediya:Alîkariya tercîhan]] ji bo alîkarî ser tercîhan.',
'prefsnologin' => 'Xwe qeyd nekir',
/* 'prefsnologintext' => 'You must be [[Special:Userlogin|logged in]]
to set user preferences.', */
#'prefsreset' => 'Preferences have been reset from storage.',
'preview' => 'Pêşdîtin',
/* 'previewconflict' => 'This preview reflects the text in the upper
text editing area as it will appear if you choose to save.', */
'previewnote' => 'Ji bîr neke ku ev bi tenê çavdêriyek e, ev rûpel hîn nehat qeyd kirin!',
#'previousdiff' => '← Previous diff',
#'previousrevision' => '←Older revision',
'prevn' => '$1 paş',
#'print' => 'Print',
'printableversion' => 'Versiyon ji bo çapkirinê',
'printsubtitle' => '(Ji http://ku.wikipedia.org)',
'protect' => 'Biparêze',
'protectcomment' => 'Sedema parastinê',
'protectedarticle' => 'parastî [[$1]]',
'protectedpage' => 'Rûpela parastî',
'protectedpagewarning' => 'ŞIYARÎ:  Ev rûpel hat qefl kirin. Bi tenê bikarhênerên ku xwediyên mafan "sysop" ne dikarin vê rûpelê biguherînin. <br />
Be sure you are following the
<a href=\'/wiki/Wîkîpediya:Protected_page_guidelines\'>protected page
guidelines</a>.',
/* 'protectedtext' => 'This page has been locked to prevent editing; there are
a number of reasons why this may be so, please see
[[Project:Protected page]].

You can view and copy the source of this page:', */
#'protectlogpage' => 'Protection_log',
/* 'protectlogtext' => 'Below is a list of page locks/unlocks.
See [[Project:Protected page]] for more information.', */
#'protectmoveonly' => 'Protect from moves only',
'protectpage' => 'Rûpelê biparêze',
#'protectsub' => '(Protecting "$1")',
'protectthispage' => 'Vê rûpelê biparêze',
#'proxyblocker' => 'Proxy blocker',
#'proxyblockreason' => 'Your IP address has been blocked because it is an open proxy. Please contact your Internet service provider or tech support and inform them of this serious security problem.',
'proxyblocksuccess' => 'Çêbû.',
#'pubmedurl' => 'http://www.ncbi.nlm.nih.gov/entrez/query.fcgi?cmd=Retrieve&db=pubmed&dopt=Abstract&list_uids=$1',
'qbbrowse' => 'Bigere',
'qbedit' => 'Biguherîne',
'qbfind' => 'Bibîne',
'qbmyoptions' => 'Rûpelên min',
#'qbpageinfo' => 'Context',
'qbpageoptions' => 'Ev rûpel',
#'qbsettings' => 'Quickbar',
'qbspecialpages' => 'Rûpelên taybet',
'randompage' => 'Rûpela tesadufî',
#'randompage-url' => 'Special:Random',
#'range_block_disabled' => 'The sysop ability to create range blocks is disabled.',
#'rchide' => 'in $4 form; $1 minor edits; $2 secondary namespaces; $3 multiple edits.',
'rclinks' => '$1 guherandinên di $2 rojên dawî de nîşan bide<br />$3',
'rclistfrom' => 'an jî guherandinên ji $1 şûnda nîşan bide.',
#'rcliu' => '; $1 edits from logged in users',
'rcloaderr' => 'Guherandinên dawî tên bar kirin',
#'rclsub' => '(to pages linked from "$1")',
'rcnote' => 'Jêr <strong>$1</strong> guherandinên dawî di <strong>$2</strong> rojên dawî de tên nîşan dan.',
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
'recentchanges' => 'Guherandinên dawî',
#'recentchanges-url' => 'Special:Recentchanges',
#'recentchangesall' => 'all',
#'recentchangescount' => 'Titles in recent changes',
'recentchangeslinked' => 'Guherandinên peywend',
'recentchangestext' => '<div class="plainlinks" id="recentchangestable">
{| width="100%" cellpadding="3"
|----- valign="top"
|
{| width="100%" style="border: 3px solid #dfdfdf;"
|-
| align="right" | \'\'\'Alîkarî:\'\'\'&nbsp;
| align="left" | [[Wîkîpediya:Alîkarî|Alîkarî]]
|-----
| align="right" | \'\'\'Wêne & Gotar:\'\'\'&nbsp;
| align="left"  | [[Special:Newimages|Wêneyên nû]] | [[Special:Newpages|Gotarên nû]] |  [[Special:Lonelypages|sêwî]] | [[Special:Wantedpages|pêwîst]] | [[Wîkîpediya:Gotar ku ji bo hemû Wîkîpediyayan pêwîst in|1000 gotar]] | [[Special:Uncategorizedpages|bê kategorî]]
|-----
| align="right" valign="top" | \'\'\'Xwişkên Wîkîpediyayê:\'\'\'&nbsp;
| align="left" | [http://commons.wikimedia.org/wiki/Special:Recentchanges Commons] | [http://ku.wiktionary.org/wiki/Special:Recentchanges Wîkîferheng] | [http://wikisource.org/wiki/Special:Recentchanges Wîkîçavkanî] | [http://ku.wikiquote.org/wiki/Special:Recentchanges Wikigotin] | [http://ku.wikibooks.org/wiki/Special:Recentchanges Wîkîpirtûk] | [http://meta.wikimedia.org/wiki/Special:Recentchanges Meta-Wiki] <small>[http://ku.wikipedia.org/w/wiki.phtml?title=MediaWiki:Recentchangestext&amp;action=edit vê biguherîne]</small>
|}
|}
[[af:Recent Changes]]
[[als:Special:Recentchanges]]
[[an:Special:Recentchanges]]
[[ar:Special:Recentchanges]]
[[ast:Special:Recentchanges]]
[[bg:Специални:Recentchanges]]
[[bs:Special:Recentchanges]]
[[ca:RecentChanges]]
[[cs:Special:Recentchanges]]
[[cy:Special:Recentchanges]]
[[da:Speciel:Recentchanges]]
[[de:Spezial:Recentchanges]]
[[el:Special:Recentchanges]]
[[en:Special:Recentchanges]]
[[eo:Lastaj_Sxangxoj]]
[[es:Cambios Recientes]]
[[et:Recent Changes]]
[[eu:Special:Recentchanges]]
[[fa:%D9%88%DB%8C%DA%98%D9%87:Recentchanges]]
[[fi:Toiminnot:Recentchanges]]
[[fr:RecentChanges]]
[[fy:Wiki:Recentchanges]]
[[ga:Speisialta:Recentchanges]]
[[gl:Special:Recentchanges]]
[[he:מיוחד:Recentchanges]]
[[hi:%E0%A4%B5%E0%A4%BF%E0%A4%B6%E0%A5%87%E0%A4%B7:Recentchanges]]
[[hr:Special:Recentchanges]]
[[hu:Speciális:Recentchanges]]
[[ia:Special:Recentchanges]]
[[id:Recent_Changes]]
[[is:Special:Recentchanges]]
[[it:Speciale:Recentchanges]]
[[ja:特別:Recentchanges]]
[[ka:Special:Recentchanges]]
[[ko:특수기능:Recentchanges]]
[[la:Recent_Changes]]
[[lb:Special:Recentchanges]]
[[mh:Special:Recentchanges]]
[[ms:Special:Recentchanges]]
[[na:Special:Recentchanges]]
[[nds:Special:Recentchanges]]
[[nl:Speciaal:Recentchanges]]
[[no:Spesial:Recentchanges]]
[[oc:Especial:Recentchanges]]
[[pl:Specjalna:Recentchanges]]
[[pt:Especial:Recentchanges]]
[[rm:Special:Recentchanges]]
[[ro:Special:Recentchanges]]
[[ru:Special:Recentchanges]]
[[sa:Special:Recentchanges]]
[[simple:Special:Recentchanges]]
[[sk:Špeciálne:Recentchanges]]
[[sl:Posebno:Recentchanges]]
[[sm:Special:Recentchanges]]
[[sq:Special:Recentchanges]]
[[sr:Посебно:Recentchanges]]
[[sv:NyligenSkrivnaSidor]]
[[tg:Special:Recentchanges]]
[[to:Special:Recentchanges]]
[[tr:Special:Recentchanges]]
[[tt:Special:Recentchanges]]
[[uk:Special:Recentchanges]]
[[vi:Special:Recentchanges]]
[[yi:Special:Recentchanges]]
[[zh:Special:Recentchanges]]',
'redirectedfrom' => '(Hat ragihandin ji $1)',
'remembermypassword' => 'Şifreya min di her rûniştdemê de bîne bîra xwe.',
#'removechecked' => 'Remove checked items from watchlist',
#'removedwatch' => 'Removed from watchlist',
#'removedwatchtext' => 'The page "$1" has been removed from your watchlist.',
#'removingchecked' => 'Removing requested items from watchlist...',
#'renamegrouplogentry' => 'Renamed group $2 to $3',
#'resetprefs' => 'Reset',
#'restorelink' => '$1 deleted edits',
#'resultsperpage' => 'Hits per page',
'retrievedfrom' => 'Ji "$1" hatiye standin.',
'returnto' => 'Bizivire $1.',
'retypenew' => 'Şîfreya nû careke din binîvîse',
#'reupload' => 'Re-upload',
#'reuploaddesc' => 'Return to the upload form.',
#'reverted' => 'Reverted to earlier revision',
#'revertimg' => 'rev',
#'revertmove' => 'revert',
'revertpage' => 'Guherandina $2 hat betal kirin, vegerand guhartoya dawî ya $1',
'revhistory' => 'Dîroka revîzyonan',
'revisionasof' => 'Revîzyon a $1',
#'revisionasofwithlink' => 'Revision as of $1; $2<br />$3 | $4',
'revnotfound' => 'Revîzyon nehat dîtin',
/* 'revnotfoundtext' => 'The old revision of the page you asked for could not be found.
Please check the URL you used to access this page.
', */
#'rfcurl' => 'http://www.faqs.org/rfcs/rfc$1.html',
#'rights' => 'Rights:',
#'rightslogtext' => 'This is a log of changes to user rights.',
#'rollback' => 'Roll back edits',
'rollback_short' => 'Bizivirîne paş',
#'rollbackfailed' => 'Rollback failed',
'rollbacklink' => 'bizivirîne paş',
'rows' => 'Rêz',
'saturday' => 'şemî',
'savearticle' => 'Rûpelê tomar bike',
'savedprefs' => 'Tercîhên te qeyd kirî ne.',
'savefile' => 'Dosyayê tomar bike',
#'savegroup' => 'Save Group',
'saveprefs' => 'Tercîhan qeyd bike',
#'saveusergroups' => 'Save User Groups',
#'scarytranscludedisabled' => '[Interwiki transcluding is disabled]',
'scarytranscludefailed' => '[Template fetch failed; sorry]',
#'scarytranscludetoolong' => '[URL is too long; sorry]',
'search' => 'Lêbigere',
'searchdisabled' => '<p>Tu dikarî li Wîkîpediya bi Google an Yahoo! bigere. Têbînî: Dibe ku encamen lêgerîne ne yên herî nû ne.
</p>',
#'searchfulltext' => 'Search full text',
'searchquery' => 'Ji bo query "$1"',
'searchresults' => 'Encamên lêgerînê',
'searchresultshead' => 'Eyarên encamên lêgerinê',
'searchresulttext' => 'Ji bo zêdetir agahî der barê lêgerînê di Wîkîpediyayê de, binêre $1.',
#'sectionlink' => '→',
#'selectnewerversionfordiff' => 'Select a newer version for comparison',
#'selectolderversionfordiff' => 'Select an older version for comparison',
#'selflinks' => 'Pages with Self Links',
#'selflinkstext' => 'The following pages contain a link to themselves, which they should not.',
#'selfmove' => 'Source and destination titles are the same; can\'t move a page over itself.',
'sep' => 'rez',
'september' => 'rezber',
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
'shortpages' => 'Rûpelên kurt',
'show' => 'nîşan bide',
'showbigimage' => 'Versyona mezin bibîne an daxe ($1x$2, $3 KB).',
#'showdiff' => 'Show changes',
'showhideminor' => 'an guherandinên biçûk $1 | bot $2 | bikarhênerên têketî $3 | guherandinên patrolkirî $4',
'showingresults' => '<b>$1</b> encam, bi #<b>$2</b> dest pê dike.',
'showingresultsnum' => '<b>$3</b> encam, bi #<b>$2</b> dest pê dike.',
'showlast' => '$1 wêneyên dawî bi rêz kirî $2 nîşan bide.',
'showpreview' => 'Pêşdîtin',
'showtoc' => 'nîşan bide',
'sig_tip' => 'Îmze û demxeya wext ya te',
'sitestats' => 'Statîstîkên sîteyê',
'sitestatstext' => 'Di \'\'database\'\' de <b>$1</b> rûpel hene. Tê de rûpelên nîqaşê, rûpelên der barê Wîkîpediyayê, rûpelên pir kurt (stub), rûpelên ragihandinê (redirect) û rûpelên din ku qey ne gotar in hene.

Derve wan, \'\'\'$2\'\'\' rûpel hene ku qey \'\'\'gotarên rewa\'\'\' ne.

Ji 28\'ê çileya 2004 heta roja îro <b>$4</b> carî rûpel hatin guherandin.

----

Fonksiyonên ku hîn rast naxebitin:
*<b>$3</b> total page views and
*<b>$5</b> average edits per page
*<b>$6</b> views per edit.',
'sitesubtitle' => 'Ensîklopediya azad',
'sitesupport' => 'Ji bo Weqfa Wikimedia Beş',
#'sitesupport-url' => 'Project:Site support',
'sitetitle' => 'Wîkîpediya Kurdî',
'siteuser' => 'Bikarhênera/ê $1 a/ê Wîkîpediyayê',
'siteusers' => 'Bikarhênerên $1 yên Wîkîpediyayê',
'skin' => 'Çerm',
#'sorbs' => 'SORBS DNSBL',
#'sorbsreason' => 'Your IP address is listed as an open proxy in the [http://www.sorbs.net SORBS] DNSBL.',
#'sourcefilename' => 'Source filename',
#'spamprotectionmatch' => 'The following text is what triggered our spam filter: $1',
#'spamprotectiontext' => 'The page you wanted to save was blocked by the spam filter. This is probably caused by a link to an external site.',
#'spamprotectiontitle' => 'Spam protection filter',
#'speciallogtitlelabel' => 'Title: ',
#'specialloguserlabel' => 'User: ',
'specialpage' => 'Rûpela taybet',
'specialpages' => 'Rûpelên taybet',
'spheading' => 'Rûpelên taybet ji bo hemû bikarhêneran',
#'sqlhidden' => '(SQL query hidden)',
'statistics' => 'Statîstîk',
'storedversion' => 'Versiyona qeydkirî',
#'stubthreshold' => 'Threshold for stub display',
'subcategories' => 'Binkategorî',
'subcategorycount' => 'Di vê kategoriyê de $1 binkategorî hene.',
'subcategorycount1' => 'Di vê kategoriyê de $1 binkategorî heye.',
'subject' => 'Mijar/sernivîs',
#'subjectpage' => 'View subject',
'successfulupload' => 'Barkirin serkeftî',
'summary' => 'Kurte û çavkanî',
'sunday' => 'yekşem',
'sysoptext' => 'Çalakiya ku te xwest bi tenê bikarhêneran bi mafên "sysop" dikarin çêkin.
Binêre $1.',
#'sysoptitle' => 'Sysop access required',
'tableform' => 'tablo',
'tagline' => 'Ji {{SITENAME}}',
'talk' => 'Nîqaş',
/* 'talkexists' => '\'\'\'The page itself was moved successfully, but the
talk page could not be moved because one already exists at the new
title. Please merge them manually.\'\'\'', */
'talkpage' => 'Vê rûpelê nîqas bike',
#'talkpagemoved' => 'The corresponding talk page was also moved.',
#'talkpagenotmoved' => 'The corresponding talk page was <strong>not</strong> moved.',
#'talkpagetext' => '<!-- MediaWiki:talkpagetext -->',
#'templatesused' => 'Templates used on this page:',
#'textboxsize' => 'Editing',
'textmatches' => 'Dîtinên di nivîsara rûpelan de',
#'thisisdeleted' => 'View or restore $1?',
'thumbnail-more' => 'Mezin bike',
'thumbsize' => 'Thumbnail size :',
'thursday' => 'Pêncşem',
#'timezonelegend' => 'Time zone',
#'timezoneoffset' => 'Offset¹',
#'timezonetext' => 'The number of hours your local time differs from server time (UTC).',
'titlematches' => 'Dîtinên di sernivîsên gotaran de',
'toc' => 'Tabloya Naverokê',
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
#'tog-showtoc' => 'Show table of contents<br />(for pages with more than 3 headings)',
#'tog-showtoolbar' => 'Show edit toolbar (JavaScript)',
'tog-underline' => 'Underline links',
#'tog-usenewrc' => 'Enhanced recent changes (JavaScript)',
#'tog-watchdefault' => 'Add pages you edit to your watchlist',
'toolbox' => 'Qutiya amûran',
'tooltip-compareselectedversions' => 'Cudatiyên guhartoyên hilbijartî yên vê rûpelê bibîne. [alt-v]',
#'tooltip-diff' => 'Show which changes you made to the text. [alt-d]',
#'tooltip-minoredit' => 'Mark this as a minor edit [alt-i]',
#'tooltip-preview' => 'Preview your changes, please use this before saving! [alt-p]',
#'tooltip-save' => 'Save your changes [alt-s]',
#'tooltip-search' => 'Search this wiki [alt-f]',
#'tooltip-watch' => 'Add this page to your watchlist [alt-w]',
#'tryexact' => 'Try exact match',
'tuesday' => 'Sêşem',
'uclinks' => '$1 guherandinên dawî; $2 rojên dawî',
#'ucnote' => 'Below are this user\'s last <b>$1</b> changes in the last <b>$2</b> days.',
'uctop' => ' (ser)',
#'unblockip' => 'Unblock user',
/* 'unblockiptext' => 'Use the form below to restore write access
to a previously blocked IP address or username.', */
'unblocklink' => 'betala astengê',
'unblocklogentry' => 'astenga "$1" hat betal kirin',
'uncategorizedcategories' => 'Kategoriyên bê kategorî',
'uncategorizedpages' => 'Rûpelên bê kategorî',
#'undelete' => 'Restore deleted page',
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
#'unlockbtn' => 'Unlock database',
#'unlockconfirm' => 'Yes, I really want to unlock the database.',
#'unlockdb' => 'Unlock database',
#'unlockdbsuccesssub' => 'Database lock removed',
#'unlockdbsuccesstext' => 'The database has been unlocked.',
/* 'unlockdbtext' => 'Unlocking the database will restore the ability of all
users to edit pages, change their preferences, edit their watchlists, and
other things requiring changes in the database.
Please confirm that this is what you intend to do.', */
'unprotect' => 'Parastinê rake',
'unprotectcomment' => 'Sedem ji bo rakirina parastinê',
#'unprotectedarticle' => 'unprotected "[[$1]]"',
#'unprotectsub' => '(Unprotecting "$1")',
'unprotectthispage' => 'Parastina vê rûpelê rake',
'unusedimages' => 'Wêneyên ku nayên bi kar anîn',
/* 'unusedimagestext' => '<p>Please note that other web sites may link to an image with
a direct URL, and so may still be listed here despite being
in active use.</p>', */
'unwatch' => 'Êdî neşopîne',
#'unwatchthispage' => 'Stop watching',
'updated' => '(Hat taze kirin)',
'upload' => 'Wêneyê barbike',
#'upload_directory_read_only' => 'The upload directory ($1) is not writable by the webserver.',
'uploadbtn' => 'Dosyayê barbike',
#'uploadcorrupt' => 'The file is corrupt or has an incorrect extension. Please check the file and upload again.',
#'uploaddisabled' => 'Sorry, uploading is disabled.',
'uploadedfiles' => 'Dosyayên bar kirî',
'uploadedimage' => '"$1" barkirî',
#'uploaderror' => 'Upload error',
'uploadlink' => 'Wêneyê barbike',
#'uploadlog' => 'upload log',
#'uploadlogpage' => 'Upload_log',
#'uploadlogpagetext' => 'Below is a list of the most recent file uploads.',
#'uploadnewversion' => '[$1 Upload a new version of this file]',
'uploadnologin' => 'Xwe qeyd nekir',
'uploadnologintext' => 'Ji bo barkirina wêneyan divê ku tu <a href="{{localurl:Special:Userlogin}}">têkeve</a>.',
#'uploadscripted' => 'This file contains HTML or script code that my be erroneously be interpreted by a web browser.',
'uploadtext' => '\'\'\'STOP!\'\'\' Before you upload here, make sure to read and follow the [[Project:Image use policy|image use policy]]. To view or search previously uploaded images, go to the [[Special:Imagelist|list of uploaded images]]. Uploads and deletions are logged on the [[Project:Upload log|upload log]]. Use the form below to upload new image files for use in illustrating your pages. On most browsers, you will see a "Browse..." button, which will bring up your operating system\'s standard file open dialog. Choosing a file will fill the name of that file into the text field next to the button. You must also check the box affirming that you are not violating any copyrights by uploading the file. Press the "Upload" button to finish the upload. This may take some time if you have a slow internet connection. The preferred formats are JPEG for photographic images, PNG for drawings and other iconic images, and OGG for sounds. Please name your files descriptively to avoid confusion. To include the image in a page, use a link in the form \'\'\'<nowiki>[[{{ns:6}}:file.jpg]]</nowiki>\'\'\' or \'\'\'<nowiki>[[{{ns:6}}:file.png|alt text]]</nowiki>\'\'\' or \'\'\'<nowiki>[[{{ns:-2}}:file.ogg]]</nowiki>\'\'\' for sounds. Please note that as with wiki pages, others may edit or delete your uploads if they think it serves the project, and you may be blocked from uploading if you abuse the system.',
#'uploadvirus' => 'The file contains a virus! Details: $1',
'uploadwarning' => 'Hişyara barkirinê',
/* 'usenewcategorypage' => '1

Set first character to "0" to disable the new category page layout.', */
#'user_rights_set' => '<b>User rights for "$1" updated</b>',
#'usercssjsyoucanpreview' => '<strong>Tip:</strong> Use the \'Show preview\' button to test your new CSS/JS before saving.',
#'usercsspreview' => '\'\'\'Remember that you are only previewing your user CSS, it has not yet been saved!\'\'\'',
#'userexists' => 'The user name you entered is already in use. Please choose a different name.',
#'userjspreview' => '\'\'\'Remember that you are only testing/previewing your user JavaScript, it has not yet been saved!\'\'\'',
'userlogin' => 'Têkeve an hesabeke nû çêke',
'userlogout' => 'Derkeve',
#'usermailererror' => 'Mail object returned error: ',
'userpage' => 'Rûpelê vê/vî bikarhênerê/î temaşe bike',
#'userrights' => 'User rights management',
#'userrights-editusergroup' => 'Edit user groups',
#'userrights-groupsavailable' => 'Available groups:',
/* 'userrights-groupshelp' => 'Select groups you want the user to be removed from or added to.
Unselected groups will not be changed. You can deselect a group with CTRL + Left Click', */
#'userrights-groupsmember' => 'Member of:',
#'userrights-logcomment' => 'Changed group membership from $1 to $2',
#'userrights-lookup-user' => 'Manage user groups',
'userrights-user-editname' => 'Enter a username:',
'userstats' => 'Statistîkên bikarhêneran',
'userstatstext' => '<b>$1</b> bikarhênerên qeydkirî hene. Ji wan <b>$2</b> administrator/koordînator in. (Binêre $3).
<br /><br />
Ji bo statîstîkên din ser serûpelê biçe: <b>Statîstîk</b>',
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
#'val_rev_stats_link' => 'See the validation statistics for "$1" <a href="$2">here</a>',
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
'version' => 'Guherto',
#'versionrequired' => 'Version $1 of MediaWiki required',
#'versionrequiredtext' => 'Version $1 of MediaWiki is required to use this page. See [[Special:Version]]',
'viewcount' => 'Ev rûpel $1 car hat xwestin.',
'viewprevnext' => '($1) ($2) ($3).',
#'views' => 'Views',
'viewsource' => 'Çavkanî',
'viewtalkpage' => 'Nîqaşê temaşe bike',
'wantedpages' => 'Rûpelên ku tên xwestin',
'watch' => 'Bişopîne',
'watchdetails' => '* $1 pages watched not counting talk pages
* [[Special:Watchlist/edit|Show and edit complete watchlist]]',
/* 'watcheditlist' => 'Here\'s an alphabetical list of your
watched content pages. Check the boxes of pages you want to remove from your watchlist and click the \'remove checked\' button
at the bottom of the screen (deleting a content page also deletes the accompanying talk page and vice versa).', */
'watchlist' => 'Lîsteya min ya şopandinê',
#'watchlistall1' => 'all',
#'watchlistall2' => 'all',
'watchlistcontains' => 'Di lîsteya şopandina te de $1 rûpel hene.',
'watchlistsub' => '(ji bo bikarhêner "$1")',
#'watchmethod-list' => 'checking watched pages for recent edits',
#'watchmethod-recent' => 'checking recent edits for watched pages',
#'watchnochange' => 'None of your watched items were edited in the time period displayed.',
'watchnologin' => 'Xwe qeyd nekir',
/* 'watchnologintext' => 'You must be [[Special:Userlogin|logged in]]
to modify your watchlist.', */
'watchthis' => 'Vê gotarê bişopîne',
'watchthispage' => 'Vê rûpelê bişopîne',
'wednesday' => 'Çarşem',
'welcomecreation' => '<h2>Bi xêr hatî, $1!</h2><p>Hesaba te hat afirandin. Tu dikarî niha tercîhên xwe eyar bikî.',
'whatlinkshere' => 'Lînkên ji vê rûpelê re',
#'whitelistacctext' => 'To be allowed to create accounts in this Wiki you have to [[Special:Userlogin|log]] in and have the appropriate permissions.',
#'whitelistacctitle' => 'You are not allowed to create an account',
#'whitelistedittext' => 'You have to [[Special:Userlogin|login]] to edit pages.',
#'whitelistedittitle' => 'Login required to edit',
#'whitelistreadtext' => 'You have to [[Special:Userlogin|login]] to read pages.',
#'whitelistreadtitle' => 'Login required to read',
'wikipediapage' => 'Rûpela meta temaşe bike',
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
'wrongpassword' => 'Şifreya ku te nivîsand şaş e. Ji kerema xwe careke din biceribîne.',
'yourdiff' => 'Cudahî',
#'yourdomainname' => 'Your domain',
'youremail' => 'E-maila te*',
#'yourlanguage' => 'Language',
'yourname' => 'Navê te wek bikarhêner (user name)',
'yournick' => 'Leqeba te (ji bo îmza)',
'yourpassword' => 'Şîfreya te (password)',
'yourpasswordagain' => 'Şîfreya xwe careke din binîvîse',
'yourrealname' => 'Navê te yê rastî*',
'yourtext' => 'Nivîsara te',
#'yourvariant' => 'Variant',
);


class LanguageKu extends LanguageUtf8 {
	function getNamespaces() {
		global $wgNamespaceNamesKu;
		return $wgNamespaceNamesKu;
	}

	function getMessage( $key ) {
		global $wgAllMessagesKu, $wgAllMessagesEn;
		if( isset( $wgAllMessagesKu[$key] ) ) {
			return $wgAllMessagesKu[$key];
		} else {
			return parent::getMessage( $key );
		}
	}
}

?>
