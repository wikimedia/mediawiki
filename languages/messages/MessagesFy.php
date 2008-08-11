<?php
/** Western Frisian (Frysk)
 *
 * @ingroup Language
 * @file
 *
 * @author Maartenvdbent
 * @author Pyt
 * @author Snakesteuben
 * @author לערי ריינהארט
 */

$skinNames = array(
	'standard' => 'Standert',
	'nostalgia' => 'Nostalgy',
);

$datePreferences = array(
	'default',
	'fy normal',
	'ISO 8601',
);

$defaultDateFormat = 'fy normal';

$dateFormats = array(
	'fy normal time' => 'H.i',
	'fy normal date' => 'j M Y',
	'fy normal both' => 'j M Y, H.i',
);

$datePreferenceMigrationMap = array(
	'default',
	'fy normal',
	'fy normal',
	'fy normal',
);

$namespaceNames = array(
	NS_MEDIA          => 'Media',
	NS_SPECIAL        => 'Wiki',
	NS_MAIN           => '',
	NS_TALK           => 'Oerlis',
	NS_USER           => 'Meidogger',
	NS_USER_TALK      => 'Meidogger_oerlis',
	# NS_PROJECT set by $wgMetaNamespace
	NS_PROJECT_TALK   => '$1_oerlis',
	NS_IMAGE          => 'Ofbyld',
	NS_IMAGE_TALK     => 'Ofbyld_oerlis',
	NS_MEDIAWIKI      => 'MediaWiki',
	NS_MEDIAWIKI_TALK => 'MediaWiki_oerlis',
	NS_TEMPLATE       => 'Berjocht',
	NS_TEMPLATE_TALK  => 'Berjocht_oerlis',
	NS_HELP           => 'Hulp',
	NS_HELP_TALK      => 'Hulp_oerlis',
	NS_CATEGORY       => 'Kategory',
	NS_CATEGORY_TALK  => 'Kategory_oerlis'
);

$namespaceAliases = array(
	'Brûker' => NS_USER,
	'Brûker_oerlis' => NS_USER_TALK,
);

$separatorTransformTable = array(',' => '.', '.' => ',' );
$linkTrail = '/^([a-zàáèéìíòóùúâêîôûäëïöü]+)(.*)$/sDu';

$messages = array(
# User preference toggles
'tog-underline'               => 'Keppelings ûnderstreekje:',
'tog-highlightbroken'         => 'Keppelings nei lege siden ta <a href="" class="new">read</a> (oars mei in fraachteken<a href="" class="internal">?</a>).',
'tog-justify'                 => 'Paragrafen útfolje',
'tog-hideminor'               => "Tekstwizigings wei litte út 'Koarts feroare'",
'tog-extendwatchlist'         => 'Utwreide folchlist',
'tog-usenewrc'                => "Utwreide ferzje fan 'Koarts feroare' brûke (net mei alle blêders mooglik)",
'tog-numberheadings'          => 'Koppen fansels nûmerje',
'tog-showtoolbar'             => 'Show edit toolbar',
'tog-editondblclick'          => 'Dûbelklik jout bewurkingsside (freget JavaScript)',
'tog-editsection'             => 'Jou [bewurk]-keppelings foar seksjebewurking',
'tog-editsectiononrightclick' => 'Rjochtsklik op sekjsetitels jout seksjebewurking (freget JavaScript)',
'tog-showtoc'                 => 'Ynhâldsopjefte, foar siden mei mear as twa koppen',
'tog-rememberpassword'        => 'Oare kear fansels oanmelde',
'tog-editwidth'               => 'Bewurkingsfjild sa breed as de side',
'tog-watchcreations'          => "Sides dy't jo makke hawwe folgje",
'tog-watchdefault'            => "Sides dy't jo feroare hawwe folgje",
'tog-watchmoves'              => "Siden dy't ik fersko automatysk folgje",
'tog-watchdeletion'           => "Siden dy't ik fuorthelje automatysk folgje",
'tog-minordefault'            => 'Feroarings yn it earst oanjaan as tekstwizigings.',
'tog-previewontop'            => 'By it neisjen, bewurkingsfjild ûnderoan sette',
'tog-previewonfirst'          => 'Proefbyld by earste bewurking sjen litte',
'tog-nocache'                 => 'Gjin oerslag brûke',
'tog-enotifwatchlistpages'    => 'E-mail my as in side op myn folchlist feroare is.',
'tog-enotifusertalkpages'     => 'E-mail my as myn oerlisside feroare wurdt',
'tog-enotifminoredits'        => 'E-mail my ek by lytse feroarings fan siden op myn folchlist',
'tog-enotifrevealaddr'        => 'Myn e-mailadres sjen litte yn e-mailberjochten',
'tog-shownumberswatching'     => 'It tal brûkers sjen litte dat dizze side folget',
'tog-fancysig'                => 'Undertekenje sûnder link nei brûkersside',
'tog-externaleditor'          => 'Standert in eksterne tekstbewurker brûke (allinne foar experts - foar dizze funksje binne spesjale ynstellings nedich)',
'tog-externaldiff'            => 'Standert in ekstern ferlikingsprogramma brûke (allinne foar experts - foar dizze funksje binne spesjale ynstellings nedich)',
'tog-showjumplinks'           => '"gean nei"-tapaslikens-links ynskeakelje',
'tog-uselivepreview'          => '"live proefbyld" brûke (JavaScript nedich - eksperimenteel)',
'tog-forceeditsummary'        => 'Warskôgje my by in lege gearfetting',
'tog-watchlisthideown'        => 'Eigen bewurkings op myn folchlist ferbergje',
'tog-watchlisthidebots'       => 'Bot-bewurkings op myn folchlist ferbergje',
'tog-watchlisthideminor'      => 'Lytse bewurkings op myn folchlist ferbergje',
'tog-ccmeonemails'            => "Stjoer my in kopy fan e-mails dy't ik nei oare brûkers stjoer",
'tog-diffonly'                => "Side-ynhâld dy't feroare wurdt net sjen litte",
'tog-showhiddencats'          => 'Ferburgen kategoryen werjaan',

'underline-always'  => 'Altyd',
'underline-never'   => 'Nea',
'underline-default' => 'Webblêder-standert',

'skinpreview' => '(Proefbyld)',

# Dates
'sunday'        => 'snein',
'monday'        => 'moandei',
'tuesday'       => 'tiisdei',
'wednesday'     => 'woansdei',
'thursday'      => 'tongersdei',
'friday'        => 'freed',
'saturday'      => 'sneon',
'sun'           => 'si',
'mon'           => 'mo',
'tue'           => 'ti',
'wed'           => 'wo',
'thu'           => 'to',
'fri'           => 'fr',
'sat'           => 'so',
'january'       => 'jannewaris',
'february'      => 'febrewaris',
'march'         => 'maart',
'april'         => 'april',
'may_long'      => 'maaie',
'june'          => 'juny',
'july'          => 'july',
'august'        => 'augustus',
'september'     => 'septimber',
'october'       => 'oktober',
'november'      => 'novimber',
'december'      => 'desimber',
'january-gen'   => 'jannewaris',
'february-gen'  => 'febrewaris',
'march-gen'     => 'maart',
'april-gen'     => 'april',
'may-gen'       => 'maaie',
'june-gen'      => 'juny',
'july-gen'      => 'july',
'august-gen'    => 'augustus',
'september-gen' => 'septimber',
'october-gen'   => 'oktober',
'november-gen'  => 'novimber',
'december-gen'  => 'desimber',
'jan'           => 'jan',
'feb'           => 'feb',
'mar'           => 'mrt',
'apr'           => 'apr',
'may'           => 'mai',
'jun'           => 'jun',
'jul'           => 'jul',
'aug'           => 'aug',
'sep'           => 'sep',
'oct'           => 'okt',
'nov'           => 'nov',
'dec'           => 'des',

# Categories related messages
'pagecategories'                 => '{{PLURAL:$1|Kategory|Kategoryen}}',
'category_header'                => 'Siden yn kategory "$1"',
'subcategories'                  => 'Underkategoryen',
'category-media-header'          => 'Media yn kategory "$1"',
'category-empty'                 => "''Dizze kategory befettet gjin siden of media.''",
'hidden-categories'              => 'Ferburgen {{PLURAL:$1|kategory|kategoryen}}',
'hidden-category-category'       => 'Ferburgen kategoryen', # Name of the category where hidden categories will be listed
'category-subcat-count'          => '{{PLURAL:$2|Dizze kategory hat allinne de folgjende ûnderkategory.|Dizze kategory hat de folgjende {{PLURAL:$1|ûnderkategory|$1 ûnderkategoryen}}, fan in totaal fan $2.}}',
'category-subcat-count-limited'  => 'Dizze kategory hat de folgjende {{PLURAL:$1|ûnderkategory|$1 ûnderkategoryen}}.',
'category-article-count'         => '{{PLURAL:$2|Dizze kategory befettet allinne de folgjende side.|De folgjende {{PLURAL:$1|side is|$1 siden binne}} yn dizze kategory, fan yn totaal $2.}}',
'category-article-count-limited' => 'De folgjende {{PLURAL:$1|side is|$1 siden binne}} yn dizze kategory.',
'category-file-count'            => '{{PLURAL:$2|Dizze kategory befettet de folgjende triem.|Dizze kategory befettet {{PLURAL:$1|de folgjende triem|$1 de folgjende triemmen}}, fan yn totaal $2.}}',
'category-file-count-limited'    => 'Dizze kategory befettet {{PLURAL:$1|de folgjende triem|de folgjende $1 triemmen}}.',
'listingcontinuesabbrev'         => 'mear',

'mainpagetext'      => 'Wiki-programma goed installearre.',
'mainpagedocfooter' => "Rieplachtsje de [http://meta.wikimedia.org/wiki/Help:Ynhâldsopjefte hantlieding] foar ynformaasje oer it gebrûk fan 'e wikisoftware.

== Mear help oer Mediawiki ==

* [http://www.mediawiki.org/wiki/Manual:Configuration_settings List mei ynstellings]
* [http://www.mediawiki.org/wiki/Manual:FAQ Faak stelde fragen (FAQ)]
* [http://lists.wikimedia.org/mailman/listinfo/mediawiki-announce Mailinglist foar oankundigings fan nije ferzjes]",

'about'          => 'Ynfo',
'article'        => 'Side',
'newwindow'      => '(iepent yn in nij finster)',
'cancel'         => 'Ferlitte',
'qbfind'         => 'Sykje',
'qbbrowse'       => 'Blêdzje',
'qbedit'         => 'Bewurkje',
'qbpageoptions'  => 'Side-opsjes',
'qbpageinfo'     => 'Side-ynfo',
'qbmyoptions'    => 'Myn Opsjes',
'qbspecialpages' => 'Spesjale siden',
'moredotdotdot'  => 'Mear...',
'mypage'         => 'Myn side',
'mytalk'         => 'Myn oerlis',
'anontalk'       => 'Oerlisside foar dit IP-adres',
'navigation'     => 'navigaasje',
'and'            => 'en',

# Metadata in edit box
'metadata_help' => 'Metadata:',

'errorpagetitle'    => 'Fout',
'returnto'          => 'Werom nei "$1".',
'tagline'           => 'Ut {{SITENAME}}',
'help'              => 'Help',
'search'            => 'Sykje',
'searchbutton'      => 'Sykje',
'go'                => 'Side',
'searcharticle'     => 'Side',
'history'           => 'Sideskiednis',
'history_short'     => 'Skiednis',
'updatedmarker'     => 'bewurke sûnt myn lêste besite',
'info_short'        => 'Ynformaasje',
'printableversion'  => 'Ofdruk-ferzje',
'permalink'         => 'Permaninte link',
'print'             => 'Ofdrukke',
'edit'              => 'Wizigje',
'create'            => 'Oanmeitsje',
'editthispage'      => 'Side bewurkje',
'create-this-page'  => 'Dizze side oanmeitsje',
'delete'            => 'Derút helje',
'deletethispage'    => 'Side wiskje',
'undelete_short'    => '$1 {{PLURAL:$1|bewurking|bewurkings}} weromsette',
'protect'           => 'Befeiligje',
'protect_change'    => 'befeiligingsstatus feroarje',
'protectthispage'   => 'Side beskermje',
'unprotect'         => 'Befeiliging opheffe',
'unprotectthispage' => 'Befeiliging fan dizze side opheffe',
'newpage'           => 'Nije side',
'talkpage'          => 'Sideoerlis',
'talkpagelinktext'  => 'Oerlis',
'specialpage'       => 'Spesjale side',
'personaltools'     => 'Persoanlike ynstellings',
'postcomment'       => 'Skriuw in opmerking',
'articlepage'       => 'Side lêze',
'talk'              => 'Oerlis',
'views'             => 'Aspekten/aksjes',
'toolbox'           => 'Arkkiste',
'userpage'          => 'Brûkerside',
'projectpage'       => 'Metaside',
'imagepage'         => 'Ofbyldside',
'mediawikipage'     => 'Berjochtside sjen litte',
'templatepage'      => 'Sjabloanside sjen litte',
'viewhelppage'      => 'Helpside sjen litte',
'categorypage'      => 'Kategoryside sjen litte',
'viewtalkpage'      => 'Oerlisside',
'otherlanguages'    => 'Oare talen',
'redirectedfrom'    => '(Trochwiisd fan "$1")',
'redirectpagesub'   => 'Trochferwiis-side',
'lastmodifiedat'    => 'Lêste kear bewurke op $2, $1.', # $1 date, $2 time
'viewcount'         => 'Disse side is {{PLURAL:$1|ienris|$1 kear}} iepenslein.',
'protectedpage'     => 'Beskerme side',
'jumpto'            => 'Gean nei:',
'jumptonavigation'  => 'navigaasje',
'jumptosearch'      => 'sykje',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'Oer de {{SITENAME}}',
'aboutpage'            => 'Project:Ynfo',
'bugreports'           => 'Brekmelding',
'bugreportspage'       => 'Project:Brekmelding',
'copyright'            => 'De ynhâld is beskikber ûnder de $1.',
'copyrightpagename'    => '{{SITENAME}} auteursrjocht',
'copyrightpage'        => '{{ns:project}}:Auteursrjocht',
'currentevents'        => 'Hjoeddeis',
'currentevents-url'    => 'Project:Rinnende saken',
'disclaimers'          => 'Foarbehâld',
'disclaimerpage'       => 'Project:Algemien foarbehâld',
'edithelp'             => 'Siden bewurkje',
'edithelppage'         => 'Help:Bewurk-rie',
'faq'                  => 'FAQ (faak stelde fragen)',
'faqpage'              => 'Project:Faak stelde fragen',
'helppage'             => 'Help:Help',
'mainpage'             => 'Haadside',
'mainpage-description' => 'Haadside',
'policy-url'           => 'Project:Belied',
'portal'               => 'Brûkersportaal',
'portal-url'           => 'Project:Brûkersportaal',
'privacy'              => 'Privacybelied',
'privacypage'          => 'Project:Privacybelied',

'badaccess'        => 'Gjin tastimming',
'badaccess-group0' => 'Jo hawwe gjin rjochten om de frege hanneling út te fieren.',
'badaccess-group1' => "De frege hanneling is foarbehâlden oan brûkers yn'e groep $1.",
'badaccess-group2' => "De frege hanneling is foarbehâlden oan brûkers yn ien fan'e groepen $1.",
'badaccess-groups' => "De frege hanneling is foarbehâlden oan brûkers yn ien fan 'e groepen $1.",

'versionrequired'     => 'Ferzje $1 fan MediaWiki is eask',
'versionrequiredtext' => "Ferzje $1 fan MediaWiki is eask om dizze side te brûken. Mear ynfo is beskikber op 'e side [[Special:Version|softwareferzje]].",

'ok'                      => 'Goed',
'retrievedfrom'           => 'Untfongen fan "$1"',
'youhavenewmessages'      => 'Jo hawwe $1 ($2).',
'newmessageslink'         => 'nije berjochten',
'newmessagesdifflink'     => 'de bewurking sjen litte',
'youhavenewmessagesmulti' => 'Jo hawwe nije berjochten op $1',
'editsection'             => 'bewurkje',
'editold'                 => 'bewurkje',
'viewsourceold'           => 'boarnetekst besjen',
'editsectionhint'         => 'Dielside bewurkje: $1',
'toc'                     => 'Ynhâld',
'showtoc'                 => 'sjen litte',
'hidetoc'                 => 'net sjen litte',
'thisisdeleted'           => '"$1" lêze of werombringje?',
'viewdeleted'             => '$1 sjen litte?',
'restorelink'             => '$1 wiske {{PLURAL:$1|ferzje|ferzjes}}',
'feedlinks'               => 'Feed:',
'feed-invalid'            => 'Feedtype wurdt net stipe.',
'feed-unavailable'        => 'Syndikaasjefeeds binne net beskikber op {{SITENAME}}',
'site-rss-feed'           => '$1 RSS Feed',
'site-atom-feed'          => '$1 Atom-Feed',
'page-rss-feed'           => '"$1" RSS Feed',
'page-atom-feed'          => '"$1" Atom Feed',
'red-link-title'          => '$1 (bestiet noch net)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Side',
'nstab-user'      => 'Brûkersside',
'nstab-media'     => 'Mediaside',
'nstab-special'   => 'Spesjaal',
'nstab-project'   => 'Projektside',
'nstab-image'     => 'Triem',
'nstab-mediawiki' => 'Berjocht',
'nstab-template'  => 'Sjabloan',
'nstab-help'      => 'Helpside',
'nstab-category'  => 'Kategory',

# Main script and global functions
'nosuchaction'      => 'Unbekende aksje.',
'nosuchactiontext'  => "De aksje dy't jo oanjoegen fia de URL is net bekind by it Wiki-programma",
'nosuchspecialpage' => 'Unbekende side',
'nospecialpagetext' => "Jo hawwe in Wiki-side opfrege dy't net bekind is by it Wiki-programma.",

# General errors
'error'                => 'Fout',
'databaseerror'        => 'Databankfout',
'dberrortext'          => 'Sinboufout in databankfraach.
De lêst besochte databankfraach wie:
<blockquote><tt>$1</tt></blockquote>
fan funksje "<tt>$2</tt>" út.
MySQL joech fout "<tt>$3: $4</tt>" werom.',
'dberrortextcl'        => 'Sinboufout in databankfraach.
De lêst besochte databankfraach wie:
"$1"
fan funksje "$2" út.
MySQL joech fout "<tt>$3: $4</tt>" werom.',
'noconnect'            => 'Sorry! Troch in fout yn de technyk, kin de Wiki gjin ferbining meitsje mei de databanktsjinner. <br />
$1',
'nodb'                 => 'Kin databank "$1" net berikke.',
'cachederror'          => 'Dit is in ferzje út de oerslag, mar it kin wêze dat dy ferâldere is.',
'laggedslavemode'      => 'Warskôging: de side kin ferâldere wêze.',
'readonly'             => 'Databank is Net-skriuwe',
'enterlockreason'      => "Skriuw wêrom de databank net-skriuwe makke is,
en sawat hoenear't de men wêr skriuwe kin",
'readonlytext'         => 'De {{SITENAME}} databank is ôfsletten foar nije siden en oare wizigings,
nei alle gedachten is it foar ûnderhâld, en kinne jo der letter gewoan wer brûk fan meitsje.
De behearder hat dizze útlis joen:
<p>$1</p>',
'missing-article'      => 'Yn de database is gjin ynhâld oantroffen foar de side "$1" dy\'t der wol wêze moatte soe ($2). 

Dat kin foarkomme as Jo in ferâldere ferwizing nei it ferskil tusken twa ferzjes fan in side folgje of in ferzje opfreegje dy\'t wiske is.

As dat net sa is, hawwe Jo faaks in fout yn \'e software fûn.
Meitsje dêr melding fan by in [[Special:ListUsers/sysop|systeembehearder]] fan {{SITENAME}} en neam dêrby de URL fan dizze side.',
'missingarticle-rev'   => '(ferzjenûmer: $1)',
'missingarticle-diff'  => '(Feroaring: $1, $2)',
'readonly_lag'         => 'De database is automatysk beskoattele wylst de ûndergeskikte databaseservers syngronisearje mei de haadserver.',
'internalerror'        => 'Ynwindige fout',
'internalerror_info'   => 'Ynterne fout: $1',
'filecopyerror'        => 'Koe bestân "$1" net kopiearje as "$2".',
'filerenameerror'      => 'Koe bestân "$1" net werneame as "$2".',
'filedeleteerror'      => 'Koe bestân "$1" net wiskje.',
'directorycreateerror' => 'Map "$1" koe net oanmakke wurde.',
'filenotfound'         => 'Koe bestân "$1" net fine.',
'fileexistserror'      => 'Skriuwen nei triem "$1" ûnmûglik: de triem bestiet al',
'unexpected'           => 'Hommelse wearde: "$1"="$2".',
'formerror'            => 'Fout: koe formulier net oerlizze',
'badarticleerror'      => 'Dat kin op dizze side net dien wurden.',
'cannotdelete'         => 'Koe de oantsjutte side of ôfbyld net wiskje. (Faaks hat in oar dat al dien.)',
'badtitle'             => 'Misse titel',
'badtitletext'         => 'De opfrege sidetitel wie ûnjildich, leech, of in miskeppele yntertaal of ynterwiki titel.',
'perfdisabled'         => "Sorry! Dit ûnderdiel is tydlik út set om't it de databank sa starich makket
dat gjinien de wiki brûke kin.",
'perfcached'           => 'De sjen littene gegevens komme út in cache en binne mûglik net by de tiid.',
'perfcachedts'         => 'De folgjende gegevens komme út in cache en binne foar it lêst bywurke op $1.',
'querypage-no-updates' => 'Dizze side kin net bywurke wurde. Dizze gegevens wurde net ferfarske.',
'wrong_wfQuery_params' => 'Ferkearde parameters foar wfQuery()<br />
Funksje: $1<br />
Query: $2',
'viewsource'           => 'Boarnetekst sjen litte',
'viewsourcefor'        => 'fan $1',
'actionthrottled'      => 'Hanneling opkeard',
'actionthrottledtext'  => 'As maatregel tsjin spam is it tal kearen per tiidsienheid beheind dat jo dizze hanneling ferrjochtsje kinne. Jo binne oer de limyt. Besykje it in tal minuten letter wer.',
'protectedpagetext'    => 'Dizze side is befeilige. Bewurkjen is net mûglik.',
'viewsourcetext'       => 'Jo kinne de boarnetekst fan dizze side besjen en kopiearje:',
'protectedinterface'   => "Dizze side befettet tekst foar berjochten fan 'e software en is befeilige om misbrûk tefoaren te kommen.",
'editinginterface'     => "'''Warskôging;''' Jo bewurkje in side dy't brûkt wurdt troch software. Bewurkings op dizze side beynfloedzje de gebrûksynterface fan elkenien. Oerweagje foar oersettings [http://translatewiki.net/wiki/Main_Page?setlang=fy Betawiki] te brûken, it oersetprojekt foar MediaWiki.",
'sqlhidden'            => '(SQL query ferburgen)',
'cascadeprotected'     => "Dizze side kin net bewurke wurde, om't er opnommen is yn 'e folgjende {{PLURAL:$1|side|siden}}, dy 't befeilige {{PLURAL:$1|is|binne}} mei de kaskade-opsje: $2",
'namespaceprotected'   => "Jo hawwe gjin rjochten om siden yn'e nammerûmte '''$1''' te bewurkjen.",
'customcssjsprotected' => "Jo kinne dizze side net bewurkje, om't er persoanlike ynstellings fan in oare brûker befettet.",
'ns-specialprotected'  => "Siden yn'e nammerûmte {{ns:special}} kinne net bewurke wurde.",
'titleprotected'       => "It oanmeitsjen fan dizze side is befeilige troch [[User:$1|$1]].
De oanfierde reden is ''$2''.",

# Virus scanner
'virus-badscanner'     => 'Minne konfiguraasje: ûnbekende virusscanner: <i>$1</i>',
'virus-scanfailed'     => 'scannen is mislearre (koade $1)',
'virus-unknownscanner' => 'ûnbekend antivirus:',

# Login and logout pages
'logouttitle'                => 'Ofmelde',
'logouttext'                 => "<strong>Jo binne no ôfmeld.</strong>

Jo kinne de {{SITENAME}} fierders anonym brûke,
of jo op 'e [[Special:UserLogin|nij oanmelde]] ûnder deselde of in oare namme.
Mûglik wurdt noch in tal siden werjûn as wiene Jo oanmeld, oant Jo de cache fan Jo browser leegje.",
'welcomecreation'            => '<h2>Wolkom, $1!</h2><p>Jo ynstellings bin oanmakke.
Ferjit net se oan jo foarkar oan te passen.',
'loginpagetitle'             => 'Oanmelde',
'yourname'                   => 'Jo brûkersnamme:',
'yourpassword'               => 'Jo wachtwurd',
'yourpasswordagain'          => 'Jo wachtwurd (nochris)',
'remembermypassword'         => 'Oare kear fansels oanmelde.',
'yourdomainname'             => 'Jo domein:',
'externaldberror'            => 'Der is in fout by it oanmelden by de database of jo hawwe gjin tastimming om jo ekstern account by te wurkjen.',
'loginproblem'               => '<b>Der wie wat mis mei jo oanmelden.</b><br />Besykje it nochris, a.j.w.',
'login'                      => 'Oanmelde',
'nav-login-createaccount'    => 'Oanmelde',
'loginprompt'                => 'Jo moatte cookies ynskeakele hawwe om jo oanmelde te kinnen by {{SITENAME}}.',
'userlogin'                  => 'Oanmelde',
'logout'                     => 'Ofmelde',
'userlogout'                 => 'Ofmelde',
'notloggedin'                => 'Net oanmelde',
'nologin'                    => 'Hawwe jo noch gjin brûkersnamme? $1.',
'nologinlink'                => 'Meitsje in brûker oan',
'createaccount'              => 'Nije ynstellings oanmeitsje',
'gotaccount'                 => 'Hawwe jo al in brûkersnamme? $1.',
'gotaccountlink'             => 'Oanmelde',
'createaccountmail'          => 'troch e-mail',
'badretype'                  => 'De ynfierde wachtwurden binne net lyk.',
'userexists'                 => 'Dy brûkersname wurdt al brûkt. Besykje in oarenien.',
'youremail'                  => 'Jo e-postadres (*).',
'username'                   => 'Brûkersnamme:',
'uid'                        => 'Brûkersnamme:',
'prefs-memberingroups'       => 'Lid fan {{PLURAL:$1|groep|groepen}}:',
'yourrealname'               => 'Jo echte namme:',
'yourlanguage'               => 'Taal:',
'yournick'                   => 'Jo alias (foar sinjaturen)',
'badsig'                     => 'Unjildige ûndertekening; kontrolearje de HTML-tags.',
'badsiglength'               => 'Bynamme is te lang; dy moat koarter as $1 {{PLURAL:$1|teken|tekens}} wêze.',
'email'                      => 'E-mail',
'prefs-help-realname'        => 'Echte namme is opsjoneel; as jo dy opjouwe kin dy namme brûkt wurde om jo erkenning te jaan foar jo wurk.',
'loginerror'                 => 'Oanmeldflater',
'prefs-help-email'           => 'E-mailadres is opsjoneel, mar stelt oaren ynsteat kontakt mei jo op te nimmen troch jo brûkers- of oerlisside sûnder dat jo idintiteit bekind wurdt.',
'prefs-help-email-required'  => 'Hjir is in e-mailadres foar nedich.',
'nocookiesnew'               => 'De brûker is oanmakke mar net oanmeld. {{SITENAME}} brûkt cookies foar it oanmelden fan brûkers. Skeakelje dy yn en meld jo dan oan mei jo nije brûkersnamme en wachtwurd.',
'nocookieslogin'             => '{{SITENAME}} brûkt cookies foar it oanmelden fan brûkers. Jo hawwe cookies útskeakele. Skeakelje dy opsje oan en besykje it nochris.',
'noname'                     => 'Jo moatte in brûkersnamme opjaan.',
'loginsuccesstitle'          => 'Oanmelden slagge.',
'loginsuccess'               => 'Jo binne no oanmelde op de {{SITENAME}} as: $1.',
'nosuchuser'                 => 'Der is gjin meidogger "$1".
Kontrolear de stavering, of meitsje in nije meidogger oan.',
'nosuchusershort'            => 'De brûker "<nowiki>$1</nowiki>" bestiet net. Kontrolearje de skriuwwize.',
'nouserspecified'            => 'Jo moatte in brûkersnamme opjaan.',
'wrongpassword'              => "Brûkersnamme en wachtwurd hearre net by elkoar.
Besykje op 'e nij, of fier it wachtwurd twa kear yn en meitsje neie brûkersynstellings.",
'wrongpasswordempty'         => 'It opjûne wachtwurd wie leech. Besykje it nochris.',
'passwordtooshort'           => 'Jo wachtwurd is ûnjildich of te koart. 
It moat minstens út {{PLURAL:$1|1 teken|$1 tekens}} bestean.',
'mailmypassword'             => 'Stjoer my in nij wachtwurd.',
'passwordremindertitle'      => 'Nij wachtwurd foar de {{SITENAME}}',
'passwordremindertext'       => 'Immen (nei alle gedachten jo, fan Ynternet-adres $1)
hat frege en stjoer jo in nij {{SITENAME}} wachtwurd ($4).
I wachtwurd foar brûker "$2" is no "$3".
Meld jo no oan, en feroarje jo wachtwurd.

As immen oars as Jo dit fersyk dien hat of as it wachtwurd Jo yntuskentiid wer yn\'t sin kommen is en Jo it net langer feroarje wolle, lûk Jo dan neat fan dit berjocht oan en gean troch mei it brûken fan Jo besteand wachtwurd.',
'noemail'                    => 'Der is gjin e-postadres foar brûker "$1".',
'passwordsent'               => 'In nij wachtwurd is tastjoert oan it e-postadres foar "$1".
Please log in again after you receive it.',
'blocked-mailpassword'       => 'Jo IP-adres is blokkearre foar it meitsjen fan feroarings. Om misbrûk tefoaren te kommen is it net mûglik in oar wachtwurd oan te freegjen.',
'eauthentsent'               => "In befêstigingsmail is nei it opjûne e-mailadres ferstjoerd. Folgje de ynstruksjes yn'e e-mail om oan te jaan dat it jo e-mailadres is. Oant dy tiid wurdt der gjin e-mail oan it adres stjoerd.",
'throttled-mailpassword'     => "Yn {{PLURAL:$1|de lêste oere|de lêste $1 oeren}} is der al in wachtwurdwink ferstjoerd.
Om misbrûk tefoaren te kommen wurdt der mar ien wachtwurdwink yn 'e {{PLURAL:$1|oere|$1 oeren}} ferstjoerd.",
'mailerror'                  => 'Fout by it ferstjoeren fan e-mail: $1',
'acct_creation_throttle_hit' => 'Sorry, jo hawwe al $1 meidochnammen oanmakke; jo kinne net mear oanmeitsje.',
'emailauthenticated'         => 'Jo e-mailadres is befêstige op $1.',
'emailnotauthenticated'      => 'Jo e-mailadres is <strong>net befêstige</strong>. Jo ûntfange gjin e-mail foar de ûndersteande funksjes.',
'noemailprefs'               => 'Jou in e-mailadres op om dizze funksjes te brûken.',
'emailconfirmlink'           => 'Befêstigje jo e-mailaddres',
'invalidemailaddress'        => "It e-mailadres is net akseptearre om't it in ûnjildige opmaak hat.
Jou beleaven in jildich e-mailadres op of lit it fjild leech.",
'accountcreated'             => 'Brûker oanmakke',
'accountcreatedtext'         => 'De brûker $1 is oanmakke.',
'createaccount-title'        => 'Brûkers registrearje foar {{SITENAME}}',
'createaccount-text'         => 'Immen hat in brûker op {{SITENAME}} ($4) oanmakke mei de namme "$2" en jo e-mailadres. It wachtwurd foar "$2" is "$3". Meld jo oan en feroarje jo wachtwurd.

Negearje it berjocht as dizze brûker sûnder jo meiwitten oanmakke is.',
'loginlanguagelabel'         => 'Taal: $1',

# Password reset dialog
'resetpass'               => 'Wachtwurd opnij ynstelle',
'resetpass_announce'      => "Jo binne oanmeld mei in tydlike koade dy't jo per e-mail tastjoerd is. Fier in nij wachtwurd yn om it oanmelden ôf te meitsjen.",
'resetpass_header'        => "Wachtwurd op 'e nij ynstelle",
'resetpass_submit'        => 'Wachtwurd ynstelle en oanmelde',
'resetpass_success'       => 'Jo wachtwurd is feroare. Dwaande mei oanmelden ...',
'resetpass_bad_temporary' => 'Unjildich tydlik wachtwurd. Jo hawwe jo wachtwurd al feroare of in nij tydlik wachtwurd oanfrege.',
'resetpass_forbidden'     => 'Wachtwurden kinne op {{SITENAME}} net feroare wurde',
'resetpass_missing'       => 'Jo hawwe gjin wachtwurd ynjûn.',

# Edit page toolbar
'bold_sample'     => 'Tsjûkprinte tekst',
'bold_tip'        => 'Tsjûkprinte',
'italic_sample'   => 'Skeanprinte tekst',
'italic_tip'      => 'skeanprinte',
'link_sample'     => 'Underwerp',
'link_tip'        => 'Ynterne link',
'extlink_sample'  => 'http://www.example.com linktekst',
'extlink_tip'     => 'Eksterne link (ferjit http:// net)',
'headline_sample' => 'Dielûnderwerp',
'headline_tip'    => 'Tuskenkop (heechste plan)',
'math_sample'     => 'Formule hjir ynfiere',
'math_tip'        => 'Wiskundige formule (LaTeX)',
'nowiki_sample'   => 'Fier hjir de net op te meitsjen tekst yn',
'nowiki_tip'      => 'Wiki-opmaak net oernimme',
'image_tip'       => 'Mediatriem',
'media_tip'       => 'Link nei triem',
'sig_tip'         => 'Jo hanteken mei datum en tiid',
'hr_tip'          => 'Horizontale streek (net tefolle brûke)',

# Edit pages
'summary'                          => 'Gearfetting',
'subject'                          => 'Mêd',
'minoredit'                        => 'Dit is in tekstwiziging',
'watchthis'                        => 'Folgje dizze side',
'savearticle'                      => 'Fêstlizze',
'preview'                          => 'Oerlêze',
'showpreview'                      => "Oerlêze foar't de side fêstlein is",
'showlivepreview'                  => 'Bewurking foar kontrôle besjen',
'showdiff'                         => 'Feroarings sjen litte',
'anoneditwarning'                  => "'''Warskôging:''' Jo binne net oanmeld. Jo IP-adres wurdt opslein as jo feroarings op dizze side meitsje.",
'missingsummary'                   => "'''Wink:''' jo hawwe gjin gearfetting jûn foar jo bewurking. As jo nochris op ''Side opslaan'' klikke wurdt de bewurking sûnder gearfetting opslein.",
'missingcommenttext'               => 'Set jo opmerking beleaven hjir ûnder.',
'missingcommentheader'             => "'''Tink derom:''' Jo hawwe gjin ûnderwerp/kop foar dizze opmerking opjûn. As jo op 'e nij op \"opslaan\" klikke, wurdt jo feroaring sûnder in ûnderwerp/kop opslein.",
'summary-preview'                  => 'Gearfetting neisjen',
'subject-preview'                  => 'Neisjen ûnderwerp/kop',
'blockedtitle'                     => 'Brûker is útsletten troch',
'blockedtext'                      => "<big>'''Jo brûkersname of Ynternet-adres is útsletten.'''</big>

De útsluting is útfierd troch $1.
De opjûne reden is ''$2''.

* Begjin útsluting : $8
* Ein útsluting : $6
* Bedoeld út te sluten: $7

Jo kinne kontakt opnimme mei $1 of in oare [[{{MediaWiki:Grouppage-sysop}}|behearder]] om de útsluting te besprekken.
Jo kinne gjin gebrûk meitsje fan 'e funksje 'e-mail dizze brûker', of jo moatte in jildich e-mailadres opjûn hawwe yn jo [[Special:Preferences|foarkarren]] en it gebrûk fan dy funksje moat net útsletten wêze.
Jo tsjintwurdich e-mailadres is $3 en it útslútnûmer is #$5. Neam beide gegevens as jo earne op dizze útsluting reagearje.",
'autoblockedtext'                  => "Jo IP-adres is automatysk blokkearre om't brûkt is troch in oare brûker, dy't blokkearre is troch $1.
De opjûne reden is:

:''$2''

* Begjin blokkade: $8
* Ein blokkade: $6

Jo kinne dizze blokkade besprekke mei $1 of in oare [[{{MediaWiki:Grouppage-sysop}}|behearder]].

U kinne gjin gebrûk meitsje fan 'e funksje 'e-mail dizze brûker', of jo moatte in jildich e-mailadres opjûn hawwe yn jo [[Special:Preferences|foarkarren]] en it gebrûk fan dy funksje moat net blokkearre wêze.

Jo blokkadenûmer is $5.
Jou beide gegevens op as jo earne oer dizze blokkade reagearje.",
'blockednoreason'                  => 'gjin reden opjûn',
'blockedoriginalsource'            => "Hjir ûnder stiet de boarnetekst fan '''$1''':",
'blockededitsource'                => "Hjir ûnder stiet de tekst fan '''jo bewurkings''' oan '''$1''':",
'whitelistedittitle'               => 'Foar bewurkjen is oanmelden ferplichte',
'whitelistedittext'                => 'Jo moatte $1 om siden te bewurkjen.',
'confirmedittitle'                 => 'Netpostbefêstiging frege foar bewurkjen',
'confirmedittext'                  => "Jo moatte jo e-mailadres befêstichje foar't jo siden feroarje kinne. Fier in e-mailedres yn by jo [[Special:Preferences|ynstellings]] en befêstichje it.",
'nosuchsectiontitle'               => 'Dizze subkop bestiet net',
'nosuchsectiontext'                => "Jo besochten in subkop te bewurkjen dy't net bestiet. Om't subkop $1 net bestiet, kin jo bewurking ek net opslein wurde.",
'loginreqtitle'                    => 'Oanmelden ferplichte',
'loginreqlink'                     => 'Oanmelde',
'loginreqpagetext'                 => '$1 is ferplichte om oare siden sjen te kinnen.',
'accmailtitle'                     => 'Wachtwurd ferstjoerd.',
'accmailtext'                      => 'It wachtwurd foar "$1" is ferstjoerd nei $2.',
'newarticle'                       => '(Nij)',
'newarticletext'                   => "Jo hawwe in keppeling folge nei in side dêr't noch gjin tekst op stiet.
Om sels tekst te meistjsen kinne jo dy gewoan yntype in dit bewurkingsfjild
([[{{MediaWiki:Helppage}}|Mear ynformaasje oer bewurkjen]].)
Oars kinne jo tebek mei de tebek-knop fan jo blêder.",
'anontalkpagetext'                 => "----''Dit is de oerlisside fan in ûnbekende meidogger; in meidogger dy't him/har net oanmeld hat. Om't der gjin namme bekend is, wurdt it Ynternet-adres brûkt om oan te jaan wa. Mar faak is it sa dat sa'n adres net altyd troch deselde persoan brûkt wurdt. As jo it idee hawwe dat jo as ûnbekende meidogger opmerkings foar in oar krije, dan kinne jo [[Special:UserLogin/signup|in meidogger namme oanmeitsje]], of jo [[Special:UserLogin|oanmelde]], sadat jo allinnich opmerkings foar josels krije.''",
'noarticletext'                    => 'Der stjit noch gjin tekst op dizze side. Jo kinne
[[Special:Search/{{PAGENAME}}|hjirboppe nei dy tekst sykje]], of [{{fullurl:{{FULLPAGENAME}}|action=edit}} de side skriuwe].',
'userpage-userdoesnotexist'        => 'Jo bewurkje in brûkersside fan in brûker dy\'t net bestiet (brûker "$1").
Kontrolearje oft jo dizze side wol oanmeitsje/bewurkje wolle.',
'clearyourcache'                   => "'''Opmerking:''' Nei it fêstlizzen kin it nedich wêze de oerslach fan dyn blêder te leegjen foardat de wizigings te sjen binne.

'''Mozilla / Firefox / Safari:''' hâld ''Shift'' yntreaun wylst jo op ''Dizze side fernije'' klikke, of typ ''Ctrl-F5'' of ''Ctrl-R'' (''Command-R'' op in Macintosh); '''Konqueror: '''klik ''Reload'' of typ ''F5;'' '''Opera:''' leegje jo cache yn ''Extra → Voorkeuren;'' '''Internet Explorer:''' hâld ''Ctrl'' yntreaun wylst jo ''Vernieuwen'' klikke of typ ''Ctrl-F5.''",
'usercssjsyoucanpreview'           => "<strong>Tip:</strong> Brûk de knop 'Earst oerlêze' om jo nije CSS/JS te testen foar it fêstlizzen.",
'usercsspreview'                   => "'''Dit is allinne mar it oerlêzen fan jo peroanlike CSS, hy is noch net fêstlein!'''",
'userjspreview'                    => "'''Tink derom: jo besjogge no jo persoanlike JavaScript. De side is net fêstlein!'''",
'userinvalidcssjstitle'            => "'''Warskôging:''' der is gjin skin \"\$1\". Tink derom: jo eigen .css- en .js-siden begjinne mei in lytse letter, bygelyks {{ns:user}}:Namme/monobook.css ynsté fan {{ns:user}}:Namme/Monobook.css.",
'updated'                          => '(Bewurke)',
'note'                             => '<strong>Opmerking:</strong>',
'previewnote'                      => '<strong>Tink der om dat dizze side noch net fêstlein is!</strong>',
'previewconflict'                  => 'Dizze side belanget allinich it earste bewurkingsfjild oan.',
'session_fail_preview'             => "<strong>Spitich! Jo bewurking is net ferwurke, om't de sessygegevens ferlern gien binne.
Besykje it nochris. As it dan noch net slagget, [[Special:UserLogout|meld jo dan ôf]] en wer oan.</strong>",
'token_suffix_mismatch'            => "<strong>Jo bewurking is wegere om't jo blêder de lêstekens yn it bewurkingstoken ûnkrekt behannele hat.
De bewurking is wegere om skeinen fan 'e sidetekst tefoaren te kommen.
Dat bart soms as der in webbasearre proxytsjinst brûkt wurdt dy't flaters befettet.</strong>",
'editing'                          => 'Bewurkje "$1"',
'editingsection'                   => 'Dwaande mei bewurkjen fan $1 (dielside)',
'editingcomment'                   => 'Dwaande mei bewurkjen fan $1 (opmerking)',
'editconflict'                     => 'Tagelyk bewurke: "$1"',
'explainconflict'                  => "In oar hat de side feroare sûnt jo begûn binne mei it bewurkjen.
It earste bewurkingsfjild is hoe't de tekst wilens wurde is.
Jo feroarings stean yn it twadde fjild.
Dy wurde allinnich tapasse safier as jo se yn it earste fjild ynpasse.
<b>Allinnich</b> de tekst út it earste fjild kin fêstlein wurde.<br />",
'yourtext'                         => 'Jo tekst',
'storedversion'                    => 'Fêstleine ferzje',
'editingold'                       => '<strong>Waarskôging: Jo binne dwaande mei in âldere ferzje fan dizze side.
Soenen jo dizze fêstlizze, dan is al wat sûnt dy tiid feroare is kwyt.</strong>',
'yourdiff'                         => 'Feroarings',
'copyrightwarning'                 => "Tink derom dat alle bydragen oan {{SITENAME}} beskôge wurde frijjûn te wêzen ûnder de $2 (sjoch $1 foar bysûnderheden). As jo net wolle dat jo tekst troch oaren neffens eigen goedfinen bewurke en ferspraat wurde kin, kies dan net foar 'Side Bewarje'.</br>
Hjirby sizze jo tagelyk ta, dat jo dizze tekst sels skreaun hawwe, of oernommen hawwe út in frije, iepenbiere boarne.</br/>
<strong>BRûK GJIN MATERIAAL DAT BESKERME WURDT TROCH AUTERURSRJOCHT, OF JO MOATTE DêR TASTIMMING TA HAWWE!</STRONG>",
'longpagewarning'                  => "<strong>Warskôging: Dizze side is $1 kilobyte lang;
der binne blêders dy't problemen hawwe mei siden fan tsjin de 32kb. of langer.
Besykje de side yn lytsere stikken te brekken.</strong>",
'readonlywarning'                  => '<strong>Waarskôging: De databank is ôfsletten foar
ûnderhâld, dus jo kinne jo bewurkings no net fêstlizze.
It wie baas en nim de tekst foar letter oer yn in tekstbestân.</strong>',
'protectedpagewarning'             => '<strong>Waarskôging: Dizze side is beskerme, dat gewoane brûkers dy net bewurkje kinne.</strong>',
'semiprotectedpagewarning'         => "'''Tink derom:''' dizze side is befeilige en kin allinne troch registrearre brûkers bewurke wurde.",
'cascadeprotectedwarning'          => "'''Warskôging:''' Dizze side is skoattele sadat allinnich behearders de side wizigje kinne, om't der in ûnderdiel útmakket fan de neikommende {{PLURAL:\$1|side|siden}}, dy't skoattele binne mei de \"ûnderlizzende siden\" opsje ynskeakele:",
'titleprotectedwarning'            => '<strong>WARSKÔGING: Dizze side is befeilige, dat allinne inkelde brûkers kinne him oanmeitsje.</strong>',
'templatesused'                    => 'Op dizze side brûkte sjabloanen:',
'templatesusedpreview'             => 'Yn dit proefbyld sjabloanen:',
'templatesusedsection'             => "Sjabloanen dy't brûkt wurde yn dizze subkop:",
'template-protected'               => '(befeilige)',
'template-semiprotected'           => '(semi-befeilige)',
'hiddencategories'                 => 'Dizze side falt yn de folgjende ferburgen
{{PLURAL:$1|kategory|kategoryen}}:',
'nocreatetitle'                    => 'It oanmeitsjen fan siden is beheind',
'nocreatetext'                     => '{{SITENAME}} hat de mûglikheid beheind om nije siden te meitsjen.
Jo kinne al besteande siden feroarje of jo kinne [[Special:UserLogin|jo oanmelde of in brûker oanmeitsje]].',
'nocreate-loggedin'                => 'Jo kinne gjin nije siden meitsje op {{SITENAME}}.',
'permissionserrors'                => 'Flaters yn rjochten',
'permissionserrorstext'            => 'Jo hawwe gjin rjochtem dit te dwaan om de folgjende {{PLURAL:$1|reden|redenen}}:',
'permissionserrorstext-withaction' => 'Jo hawwe gjin rjocht ta $2 om de folgjende {{PLURAL:$1|reden|redenen}}:',
'recreate-deleted-warn'            => "'''Warskôging: Jo binne dwaande in side oan te meitsjen dy't earder weidien is.'''

Betink oft it gaadlik is dat jo dizze side fierder bewurkje. Foar jo geriif stiet hjirûnder it lochboek oer it weidwaan fan dizze side:",

# Parser/template warnings
'expensive-parserfunction-warning'        => 'Warskôging: Dizze side brûkt tefolle kostbere parserfunksjes.

No binne it $1, wylst it minder as $2 wêze moatte.',
'expensive-parserfunction-category'       => "Siden dy't tefolle kostbere parserfuksjes brûke",
'post-expand-template-inclusion-category' => "Side wêrfoar't de maksimale trânsklúzjegrutte teboppe gien is",
'post-expand-template-argument-category'  => "Siden dy't missende sjabloaneleminten befetsje",

# "Undo" feature
'undo-success' => 'De feroaring kin werom set wurde. Kontrolearje de ferliking hjirûnder om wis te wêzen dat jo dit feroarje wolle en druk dan op fêstlizze om it werom setten troch te fieren.',
'undo-failure' => 'De feroaring kin net ûngedien makke wurde fanwege oare stridige bewurkings.',
'undo-norev'   => 'De feroaring kin werom set wurde, omdat it net bestiet of is wiske.',
'undo-summary' => 'Werom sette fan ferzje $1 fan [[Special:Contributions/$2|$2]] ([[User talk:$2|Oerlis]])',

# Account creation failure
'cantcreateaccounttitle' => 'Registrearjen is mislearre.',

# History pages
'viewpagelogs'        => 'Lochboek foar dizze side sjen litte',
'nohistory'           => 'Dit is de earste ferzje fan de side.',
'revnotfound'         => 'Ferzje net fûn',
'revnotfoundtext'     => "De âlde ferzje fan dizze side dêr't jo om frege hawwe, is der net.
Gean nei of de keppeling dy jo brûkt hawwe wol goed is.",
'currentrev'          => 'Dizze ferzje',
'revisionasof'        => 'Ferzje op $1',
'revision-info'       => 'Ferzje op $1 fan $2',
'previousrevision'    => '←Aldere ferzje',
'nextrevision'        => 'Nijere ferzje→',
'currentrevisionlink' => 'Rinnende ferzje',
'cur'                 => 'no',
'next'                => 'dan',
'last'                => 'doe',
'page_first'          => 'earste',
'page_last'           => 'lêste',
'histlegend'          => "Utlis: (no) = ferskil mei de side sa't dy no is,
(doe) = ferskill mei de side sa't er doe wie, foar de feroaring, T = Tekstwiziging",
'deletedrev'          => '[fuorthelle]',
'histfirst'           => 'Aldste',
'histlast'            => 'Nijste',
'historysize'         => '({{PLURAL:$1|1 byte|$1 bytes}})',
'historyempty'        => '(leech)',

# Revision feed
'history-feed-title'          => 'Sideskiednis',
'history-feed-description'    => 'Sideskiednis foar dizze side op de wiki',
'history-feed-item-nocomment' => '$1 op $2', # user at time

# Revision deletion
'rev-deleted-comment'     => '(opmerking wiske)',
'rev-deleted-user'        => '(meidoggernamme wiske)',
'rev-delundel'            => 'sjen litte/ferbergje',
'revisiondelete'          => 'Wiskje/weromsette ferzjes',
'revdelete-nooldid-title' => 'Gjin doelferzje',
'revdelete-selected'      => 'Spesifisearre {{PLURAL:$2|ferzje|ferzjes}} fan [[:$1]]:',
'logdelete-selected'      => '{{PLURAL:$1|keazen lochboekregel|keazen lochboekregels}}:',
'revdelete-hide-user'     => 'Meidoggernamme/IP fan de meidogger ferskûlje',
'revdelete-hide-image'    => 'Triem ynhâld ferskûlje',
'pagehist'                => 'Sideskiednis',
'deletedhist'             => 'Wiske skiednis',
'revdelete-content'       => 'ynhâld',
'revdelete-uname'         => 'meidoggernamme',
'revdelete-unhid'         => '$1 net mear ferburgen',
'revdelete-log-message'   => '$1 foar $2 {{PLURAL:$2|ferzje|ferzjes}}',
'logdelete-log-message'   => '$1 foar $2 {{PLURAL:$2|lochboekregel|lochboekregels}}',

# History merging
'mergehistory'                     => 'Skiednis fan kombinearjen',
'mergehistory-box'                 => 'Ferzjes fan twa siden kombinearje:',
'mergehistory-from'                => 'Triemnamme boarne:',
'mergehistory-into'                => 'Bestimmingside:',
'mergehistory-go'                  => "Besjen bewurkings dy't kombinearre wurde kinne",
'mergehistory-submit'              => 'Kombinearje ferzjes',
'mergehistory-empty'               => 'Gjin ferzjes kinne kombinearren wurde.',
'mergehistory-success'             => 'Kombinearjen slagge fan $3 {{PLURAL:$3|ferzje|ferzjes}} fan [[:$1]] no [[:$2]].',
'mergehistory-fail'                => 'It is net mooglik de skiednis te kombinearje; kontrolearje nochris de side en tiidparameters.',
'mergehistory-no-source'           => 'Boarneside $1 bestiet net.',
'mergehistory-no-destination'      => 'Doelside $1 bestiet net.',
'mergehistory-invalid-source'      => 'De titel fan de boarneside moet jildich wêze.',
'mergehistory-invalid-destination' => 'De titel fan de doelside moet jildich wêze.',
'mergehistory-autocomment'         => '[[:$1]] kombinearre mei [[:$2]]',
'mergehistory-comment'             => '[[:$1]] kombinearre mei [[:$2]]: $3',

# Merge log
'pagemerge-logentry' => '[[$1]] kombinearre mei [[$2]] (maksimaal $3 ferzjes)',

# Diffs
'history-title'           => 'Skiednis fan "$1"',
'difference'              => '(Ferskil tusken ferzjes)',
'lineno'                  => 'Rigel $1:',
'compareselectedversions' => 'Ferlykje keazen ferzjes',
'editundo'                => 'oergean litte',
'diff-multi'              => '({{PLURAL:$1|Ien tuskenlizzende ferzje wurdt|$1 tuskenlizzende ferzjes wurde}} net sjen litten.)',

# Search results
'searchresults'             => 'Sykresultaat',
'searchresulttext'          => 'Lês foar mear ynformaasje oer it sykjen yn de {{SITENAME}} de [[{{MediaWiki:Helppage}}|{{int:help}}]].',
'searchsubtitle'            => 'Foar fraach "[[:$1]]"',
'searchsubtitleinvalid'     => 'Foar fraach "$1"',
'noexactmatch'              => "'''Der is gjin side mei krekt de titel \"\$1\".'''
Jo kinne de [[:\$1|side oanmeitsje]].",
'noexactmatch-nocreate'     => "'''Der is gjin side mei krekt de titel \"\$1\".'''",
'titlematches'              => 'Titels',
'notitlematches'            => 'Gjin titels',
'textmatches'               => 'Siden',
'notextmatches'             => 'Gjin siden',
'prevn'                     => 'foarige $1',
'nextn'                     => 'folgende $1',
'viewprevnext'              => '($1) ($2) ($3) besjen.',
'search-result-size'        => '$1 ({{PLURAL:$2|1 wurd|$2 wurden}})',
'search-result-score'       => 'Relevante: $1%',
'search-redirect'           => '(trochferwizing $1)',
'search-section'            => '(seksje $1)',
'search-suggest'            => 'Bedoele jo: $1',
'search-interwiki-caption'  => 'Susterprojekten',
'search-interwiki-default'  => '$1 resultaten:',
'search-interwiki-more'     => '(mear)',
'search-mwsuggest-enabled'  => 'mei suggestjes',
'search-mwsuggest-disabled' => 'gjin suggestjes',
'search-relatedarticle'     => 'Besibbe',
'searchrelated'             => 'besibbe',
'searchall'                 => 'alle',
'showingresults'            => "{{PLURAL:$1|'''1''' resultaat|'''$1''' resultaten}} fan #'''$2''' ôf.",
'showingresultsnum'         => "{{PLURAL:$3|'''1''' resultaat|'''$3''' resultaten}} fan #'''$2''' ôf.",
'showingresultstotal'       => "It binne hjirûnder  {{PLURAL:$3|resultaat '''$1''' fan '''$3'''|resultaten '''$1 - $2''' fan '''$3'''}}",
'nonefound'                 => 'As der gjin resultaten binne, tink der dan om dat der <b>net</b> socht
wurde kin om wurden as "it" en "in", om\'t dy net byhâlden wurde, en dat as der mear
wurden syke wurde, allinnich siden fûn wurde wêr\'t <b>alle</b> worden op fûn wurde.',
'powersearch'               => 'Sykje',
'powersearch-legend'        => 'Sykje',
'powersearch-ns'            => 'Sykje op nammeromten:',
'powersearch-field'         => 'Sykje op',
'search-external'           => 'Ekstern sykjen',
'searchdisabled'            => "<p>Op it stuit stjit it trochsykjen fan tekst net oan, om't de
tsjinner it net oankin. Mei't we nije apparatuer krije wurdt it nei alle gedanken wer
mooglik. Foar now kinne jo sykje fia Google:</p>",

# Preferences page
'preferences'              => 'Ynstellings',
'mypreferences'            => 'Myn foarkarynstellings',
'prefs-edits'              => 'Tal bewurkings:',
'prefsnologin'             => 'Net oanmeld',
'prefsnologintext'         => 'Jo moatte [[Special:UserLogin|oanmeld]] wêze om jo ynstellings te feroarjen.',
'prefsreset'               => "De ynstellings binne tebek set sa't se fêstlein wienen.",
'qbsettings'               => 'Menu',
'qbsettings-none'          => 'Ut',
'qbsettings-fixedleft'     => 'Lofts fêst',
'qbsettings-fixedright'    => 'Rjochts fêst',
'qbsettings-floatingleft'  => 'Lofts sweevjend',
'qbsettings-floatingright' => 'Rjochts sweevjend',
'changepassword'           => 'Wachtwurd feroarje',
'skin'                     => 'Side-oansjen',
'math'                     => 'Formules',
'dateformat'               => 'Datum',
'datedefault'              => 'Gjin foarkar',
'datetime'                 => 'Datum en tiid',
'math_failure'             => 'Untsjutbere formule',
'math_unknown_error'       => 'Unbekinde fout',
'math_unknown_function'    => 'Unbekinde funksje',
'math_lexing_error'        => 'Unbekind wurd',
'math_syntax_error'        => 'Sinboufout',
'math_bad_tmpdir'          => 'De tydlike formulepad kin net skreaun of makke wêze.',
'math_bad_output'          => 'De formulepad kin net skreaun of makke wêze.',
'math_notexvc'             => 'It programma texvc net fûn; sjoch math/README te ynstallearjen.',
'prefs-personal'           => 'Persoanlike gegevens',
'prefs-rc'                 => 'Koartlyn feroare',
'prefs-watchlist'          => 'Folchlist',
'prefs-watchlist-days'     => 'Oantal dagen yn folchlist sjen litte:',
'prefs-watchlist-edits'    => 'Tal wizigings om sjen te litten yn de útwreide folchlist:',
'prefs-misc'               => 'Ferskaat',
'saveprefs'                => 'Ynstellings fêstlizze',
'resetprefs'               => 'Ynstellings tebek sette',
'oldpassword'              => 'Ald wachtwurd',
'newpassword'              => 'Nij wachtwurd',
'retypenew'                => 'Nij wachtwurd (nochris)',
'textboxsize'              => 'Tekstfjid-omjittings',
'rows'                     => 'Rigen',
'columns'                  => 'Kolommen',
'searchresultshead'        => 'Sykje',
'resultsperpage'           => 'Treffers de side',
'contextlines'             => 'Rigels ynhâld de treffer:',
'contextchars'             => 'Tekens fan de ynhâld de rigel:',
'recentchangesdays'        => 'Dagen om sjen te litten yn Koartlyn feroare:',
'recentchangescount'       => "Nûmer of titels op 'Koarts feroare'",
'savedprefs'               => 'Jo ynstellings binne fêstlein.',
'timezonelegend'           => 'Tiidsône',
'timezonetext'             => 'Jou it tal fan oeren dat jo tiidsône ferskilt fan UTC (Greenwich).',
'localtime'                => 'Jo tiidsône',
'timezoneoffset'           => 'Ferskil',
'servertime'               => 'UTC',
'guesstimezone'            => 'Freegje de blêder',
'allowemail'               => 'Netpost fan oare meidoggers tastean',
'prefs-searchoptions'      => 'Sykje-ynstellings',
'prefs-namespaces'         => 'Nammeromten',
'defaultns'                => "Nammeromten dy't normaal trochsocht wurde:",
'default'                  => 'standert',
'files'                    => 'Triemen',

# User rights
'userrights'                  => 'Behear fan meidoggerrjochten', # Not used as normal message but as header for the special page itself
'userrights-lookup-user'      => 'Behear fan meidoggerrjochten',
'userrights-user-editname'    => 'Meidoggernamme:',
'editusergroup'               => 'Wizigje meidoggerrjochten',
'editinguser'                 => "Bewurkje meidoggerrjochten fan '''[[User:$1|$1]]''' ([[User talk:$1|{{int:talkpagelinktext}}]] | [[Special:Contributions/$1|{{int:contribslink}}]])",
'userrights-editusergroup'    => 'Wizigje meidoggerrjochten',
'saveusergroups'              => 'Meidoggerrjochten fêstlizze',
'userrights-groupsmember'     => 'Sit yn group:',
'userrights-reason'           => 'Utlis foar wizigjen:',
'userrights-no-interwiki'     => "Jo hawwe gjin rjochten om meidoggerrjochten op oare wiki's te wizigjen.",
'userrights-nologin'          => 'Jo moatte jo [[Special:UserLogin|oanmelde]] as rjochtenútfurder om rjochten fan meidoggers wizigje te kinnen.',
'userrights-notallowed'       => 'Jo hawwe gjin rjochten om rjochten fan meidoggers te wizigjen.',
'userrights-changeable-col'   => "Groepen dy't jo beheare kinne",
'userrights-unchangeable-col' => "Groepen dy't jo net beheare kinne",

# Groups
'group'            => 'Groep:',
'group-user'       => 'Meidoggers',
'group-bot'        => 'Bots',
'group-sysop'      => 'Behearders',
'group-bureaucrat' => 'Rjochtenútfurders',
'group-all'        => '(alle)',

'group-user-member'       => 'Meidogger',
'group-bot-member'        => 'Bot',
'group-sysop-member'      => 'Behearder',
'group-bureaucrat-member' => 'Rjochtenútfurder',

'grouppage-user'       => '{{ns:project}}:Meidoggers',
'grouppage-bot'        => '{{ns:project}}:Bots',
'grouppage-sysop'      => '{{ns:project}}:Behearders',
'grouppage-bureaucrat' => '{{ns:project}}:Rjochtenútfurders',

# Rights
'right-read'                 => 'Siden sjen',
'right-edit'                 => 'Siden bewurkjen',
'right-createpage'           => 'Siden oanmeitsjen (net oerlissiden)',
'right-createtalk'           => 'Oerlissiden oanmeitsjen',
'right-minoredit'            => 'Bydragen markearje as tekstwiziging',
'right-move'                 => 'Siden werneamen',
'right-upload'               => 'Triemmen oanbieden',
'right-reupload'             => 'In besteande triem oerskriuwen',
'right-upload_by_url'        => 'Triemen oanbieden fia in URL',
'right-delete'               => 'Siden wiskjen',
'right-bigdelete'            => 'Wiskjen siden mei grutte skiednis',
'right-deleterevision'       => 'Spesifisearre ferzjes fan siden wiskjen',
'right-importupload'         => 'Ymportearjen siden fan in triemoanbied',
'right-userrights'           => 'Alle meidoggerrjochten bywurkje',
'right-userrights-interwiki' => "Wizigje meidoggerrjochten fan meidoggers yn oare wiki's",

# User rights log
'rightslog'      => 'Brûkersrjochte-lochboek',
'rightslogtext'  => 'Hjirûnder binne de wizigjen fan meidoggerrjochten.',
'rightslogentry' => 'groep is feroare foar meidogger $1 fan $2 no $3',
'rightsnone'     => '(gjin)',

# Recent changes
'nchanges'                          => '$1 {{PLURAL:$1|bewurking|bewurkings}}',
'recentchanges'                     => 'Koarts feroare',
'recentchangestext'                 => 'De lêste feroarings fan de {{SITENAME}}.',
'recentchanges-feed-description'    => 'Mei dizze feed kinne jo de nijste feroarings yn dizze wiki besjen.',
'rcnote'                            => "Dit {{PLURAL:$1|is de lêste feroaring|binne de lêste '''$1''' feroarings}} yn de lêste {{PLURAL:$2|dei|'''$2''' dagen}}, fan $4 $5.",
'rcnotefrom'                        => 'Dit binne de feroarings sûnt <b>$2</b> (maksimaal <b>$1</b>).',
'rclistfrom'                        => 'Jou nije feroarings, begjinnende mei $1',
'rcshowhideminor'                   => 'lytse feroarings $1',
'rcshowhidebots'                    => 'bots $1',
'rcshowhideliu'                     => 'oanmelde brûkers $1',
'rcshowhideanons'                   => 'anonime brûkers $1',
'rcshowhidepatr'                    => 'kontrolearre bewurkings $1',
'rcshowhidemine'                    => 'myn bewurkings $1',
'rclinks'                           => 'Jou $1 nije feroarings yn de lêste $2 dagen; $3 tekstwiziging',
'diff'                              => 'ferskil',
'hist'                              => 'skiednis',
'hide'                              => 'gjin',
'show'                              => 'al',
'minoreditletter'                   => 'T',
'newpageletter'                     => 'N',
'boteditletter'                     => 'b',
'number_of_watching_users_pageview' => '[$1 folgjende {{PLURAL:$1|meidogger|meidoggers}}]',
'rc_categories'                     => 'Alline kategoryen (skiede mei in "|")',
'newsectionsummary'                 => '/* $1 */ nije seksje',

# Recent changes linked
'recentchangeslinked'          => 'Folgje keppelings',
'recentchangeslinked-title'    => 'Feroarings yn ferbân mei "$1"',
'recentchangeslinked-noresult' => "Der hawwe gjin bewurkings yn 'e bedoelde perioade west op'e siden dy't hjirwei linke wurde.",
'recentchangeslinked-summary'  => "Dizze spesjale side lit de lêste bewurkings sjen op siden dy't keppele wurde fan in spesifisearre side ôf (of fan in spesifisearre Kategory ôf). Siden dy't op [[Special:Watchlist|jo folchlist]] steane, wurde '''tsjûk''' werjûn.",
'recentchangeslinked-page'     => 'Sidenamme:',

# Upload
'upload'                     => 'Bied bestân oan',
'uploadbtn'                  => 'Bied bestân oan',
'reupload'                   => "Op 'e nij oanbiede",
'reuploaddesc'               => 'Werom nei oanbied-side.',
'uploadnologin'              => 'Net oanmelde',
'uploadnologintext'          => 'Jo moatte [[Special:UserLogin|oanmeld]] wêze om in bestân oanbieden te kinnen.',
'upload_directory_read_only' => 'De webserver kin net skriuwe yn de oanbiedpad ($1).',
'uploaderror'                => 'Oanbied-fout',
'uploadtext'                 => "Om't nije triemmen oan te bieden, brûke jo de ûndersteande fekjes. Earder oanbeane triemmen, kinne jo fine op de [[Special:ImageList|list of oanbeane ôfbylden]].
Wat oanbean en wat wiske wurdt, wurdt delskreaun yn it [[Special:Log/upload|lochboek]].

Om de triem yn in side op te nimmen, meitsje jo dêr sa'n keppeling:
*'''<nowiki>[[</nowiki>{{ns:image}}<nowiki>:jo_foto.jpg]]</nowiki>''',
*'''<nowiki>[[</nowiki>{{ns:image}}<nowiki>:jo_logo.png|omskriuwing]]</nowiki>''', of
*'''<nowiki>[[</nowiki>{{ns:media}}<nowiki>:jo_lûd.ogg]]</nowiki>''', foar direkt keppeling fan mediatriemmen dy't gjin ôfbylden binne.",
'upload-prohibited'          => 'Ferbouden triemtypes: $1.',
'uploadlog'                  => 'oanbied log',
'uploadlogpage'              => 'Oanbied_log',
'uploadlogpagetext'          => 'Liste fan de lêst oanbeane bestannen.
(Tiid oanjûn as UTC).',
'filename'                   => 'Bestânsnamme',
'filedesc'                   => 'Omskriuwing',
'fileuploadsummary'          => 'Gearfetting:',
'filestatus'                 => 'Auteursrjochtensituaasje:',
'filesource'                 => 'Boarne:',
'uploadedfiles'              => 'Oanbeane bestannen',
'ignorewarning'              => 'Negearje de warskôging en lis triem dochs fêst.',
'ignorewarnings'             => 'Negearje warskôgings',
'minlength1'                 => 'Triemnammen moatte minstens út ien teken bestean.',
'badfilename'                => 'De ôfbyldnamme is feroare nei "$1".',
'successfulupload'           => 'Oanbieden slagge.',
'uploadwarning'              => 'Oanbied waarskôging',
'savefile'                   => 'Lis bestân fêst',
'uploadedimage'              => ' "[[$1]]" oanbean',
'uploaddisabled'             => 'Sorry, op dizze tsjinner kin net oanbean wurde.',
'sourcefilename'             => 'Triemnamme boarne:',
'destfilename'               => 'Triemnamme om op te slaan:',
'watchthisupload'            => 'Folgje dizze side',
'filename-bad-prefix'        => 'De namme fan de triem dy\'t jo oanbied begjint mei <strong>"$1"</strong>, dit wiist op in namme dy\'t automatysk troch in digitale kamera oanmakke wurdt. Feroarje de namme as jo wolle yn ien dy\'t in omskriuwing jout fan de triem.',

'license' => 'Lisinsje:',

# Special:ImageList
'imgfile'        => 'triem',
'imagelist'      => 'Ofbyld list',
'imagelist_name' => 'Namme',

# Image description page
'filehist'                  => 'Triemskiednis',
'filehist-help'             => "Klik op in datum/tiid om de triem te sjen sa't er doedestiids wie.",
'filehist-deleteall'        => 'wiskje alles',
'filehist-deleteone'        => 'wiskje dizze',
'filehist-revert'           => 'werom sette',
'filehist-current'          => 'rinnend',
'filehist-datetime'         => 'Datum/tiid',
'filehist-user'             => 'Brûker',
'filehist-dimensions'       => 'Ofmjittings',
'filehist-filesize'         => 'Triemgrutte',
'filehist-comment'          => 'Oanmerking',
'imagelinks'                => 'Ofbyldkeppelings',
'linkstoimage'              => 'Dizze {{PLURAL:$1|side is|$1 siden binne}} keppele oan it ôfbyld:',
'nolinkstoimage'            => 'Der binne gjin siden oan dit ôfbyld keppelje.',
'sharedupload'              => 'Dizze triem is in dielde oplading en kin ek troch oare projektren brûkt wurde.',
'noimage'                   => 'Der bestiet gjin triem mei dizze namme. Jo kinne it $1.',
'noimage-linktext'          => 'oplade',
'uploadnewversion-linktext' => 'In nije ferzje fan dizze triem oplade',

# File reversion
'filerevert'         => '$1 weromsette',
'filerevert-legend'  => 'Triem weromsette',
'filerevert-comment' => 'Oanmerking:',
'filerevert-submit'  => 'werom sette',

# File deletion
'filedelete'                  => 'Wiskje $1',
'filedelete-legend'           => 'Wiskje triem',
'filedelete-intro-old'        => "Jo wiskje de ferzje fan '''[[Media:$1|$1]]''' fan [$4 $3, $2].",
'filedelete-comment'          => 'Reden foar it wiskjen:',
'filedelete-submit'           => 'Wiskje',
'filedelete-success'          => "'''$1''' is wiske.",
'filedelete-otherreason'      => 'Oare/eventuele reden:',
'filedelete-reason-otherlist' => 'Oare reden',

# MIME search
'mimesearch' => 'Sykje op MIME-type',

# Unwatched pages
'unwatchedpages' => "Siden dy't net op in folchlist steane",

# List redirects
'listredirects' => 'List fan trochferwizings',

# Unused templates
'unusedtemplates'    => 'Net brûkte sjabloanen',
'unusedtemplateswlh' => 'oare keppelings',

# Random page
'randompage' => 'Samar in side',

# Random redirect
'randomredirect' => 'Samar in trochferwizing',

# Statistics
'statistics'    => 'Statistyk',
'sitestats'     => 'Side statistyk',
'userstats'     => 'Brûker statistyk',
'sitestatstext' => "It {{PLURAL:$1|is '''1''' side|binne '''$1''' siden}} yn de databank.
Oerlissiden, siden oer de {{SITENAME}}, stobben, trochferwizings, en oare bysûndere siden, binne dêrby meiteld. Sûnder dizze siden, it {{PLURAL:$2|is '''1''' side|binne '''$2''' siden}} mei materiaal en ynhâld. 

'''$8''' {{PLURAL:$8|triem wurdt|triemmen wurde}} al oanbied.

Der {{PLURAL:$3|is '''1''' sidelêzing|binne '''$3''' sidelêzings}}, en '''$4''' {{PLURAL:$4|bewurking|bewurkings}} sûnt {{SITENAME}} begûnen. Dat komt yn trochslach del op '''$5''' bewurkings per side, en '''$6''' lêzings per bewurking.  

De lingte fan de [http://www.mediawiki.org/wiki/Manual:Job_queue job queue] is '''$7'''.",
'userstatstext' => "It tal fan registrearre meidoggers is '''{{PLURAL:$1|1|$1}}'''.
It tal fan meidoggers dêrfan mei $5rjochten is '''{{PLURAL:$2|1|$2}}''' (of '''{{PLURAL:$4|1|$4}}''').",

'disambiguations'      => 'Trochverwizings',
'disambiguationspage'  => 'Project:trochferwizing',
'disambiguations-text' => "De ûndersteande siden keppelje mei in '''Betsjuttingssiden'''.
Se soenen mei de side sels keppele wurde moatte.<br /> In side wurdt sjoen as betsjuttingssiden, as de side ien berjocht fan [[MediaWiki:Disambiguationspage]] brûkt.",

'doubleredirects'     => 'Dûbele trochferwizings',
'doubleredirectstext' => '<b>Let op!</b> Der kinne missen yn dizze list stean!
Dat komt dan ornaris troch oare keppelings ûnder de "#REDIRECT".<br />
Eltse rigel jout keppelings nei de earste en twadde trochverwizing, en dan de earste regel fan
de twadde trochferwizing, wat it "echte" doel wêze moat.',

'brokenredirects'        => 'Misse trochferwizings',
'brokenredirectstext'    => "Dizze trochferwizings ferwize nei siden dy't der net binne.",
'brokenredirects-edit'   => '(bewurkje)',
'brokenredirects-delete' => '(wiskje)',

'withoutinterwiki'        => 'Siden sûnder links nei oare talen',
'withoutinterwiki-submit' => 'Sjen litte',

'fewestrevisions' => 'Siden mei de minste bewurkings',

# Miscellaneous special pages
'nbytes'                  => '$1 {{PLURAL:$1|byte|bytes}}',
'ncategories'             => '$1 {{PLURAL:$1|kategory|kategoryen}}',
'nlinks'                  => '$1 {{PLURAL:$1|keppeling|keppelings}}',
'nmembers'                => '$1 {{PLURAL:$1|ynskriuwing|ynskriuwings}}',
'nrevisions'              => '$1 {{PLURAL:$1|ferzje|ferzjes}}',
'nviews'                  => '{{PLURAL:$1|1 kear|$1 kear}} sjoen',
'specialpage-empty'       => 'Gjin resultaten foar dit rapport.',
'lonelypages'             => 'Lossteande siden',
'uncategorizedpages'      => 'Net-kategorisearre siden',
'uncategorizedcategories' => 'Net-kategorisearre kategoryen',
'uncategorizedimages'     => 'Net-kategorisearre triemen',
'uncategorizedtemplates'  => 'Net-kategorisearre sjabloanen',
'unusedcategories'        => 'Net-brûkte kategoryen',
'unusedimages'            => 'Lossteande ôfbylden',
'popularpages'            => 'Grage siden',
'wantedcategories'        => "Net-besteande kategoryen dêr't it meast nei ferwiisd wurdt",
'wantedpages'             => 'Nedige siden',
'mostlinked'              => "Siden dêr't it meast nei ferwiisd wurdt",
'mostlinkedcategories'    => "Kategoryen dêr't it meast nei ferwiisd wurdt",
'mostlinkedtemplates'     => 'Meast brûkte sjabloanen',
'mostcategories'          => 'Siden mei de measte kategoryen',
'mostimages'              => 'Meast brûkte triemmen',
'mostrevisions'           => 'Siden mei de measte bewurkings',
'prefixindex'             => 'Alle siden neffens foarheaksel',
'shortpages'              => 'Koarte siden',
'longpages'               => 'Lange siden',
'deadendpages'            => 'Siden sûnder links',
'protectedpages'          => 'Befeilige siden',
'protectedpagestext'      => 'De neikommende siden binne skoattele foar werneamen of wizigjen',
'listusers'               => 'Brûkerlist',
'newpages'                => 'Nije siden',
'newpages-username'       => 'Meidoggernamme:',
'ancientpages'            => 'Alde siden',
'move'                    => 'Ferskowe nei oare namme',
'movethispage'            => 'Werneam dizze side',
'unusedimagestext'        => '<p>Tink derom dat ore web sides lykas fan de oare
parten fan it meartaliche projekt mei in keppeling nei in direkte URL nei
an ôfbyld makke hawwe kinne. Dan wurde se noch brûke, mar stean al in dizze list.',
'notargettitle'           => 'Gjin side',
'notargettext'            => 'Jo hawwe net sein oer hokfoar side jo dit witte wolle.',
'pager-older-n'           => '{{PLURAL:$1|1 âlder|$1 âlder}}',

# Book sources
'booksources' => 'Boekynformaasje',

# Special:Log
'specialloguserlabel'  => 'Brûker:',
'speciallogtitlelabel' => 'Sidenamme:',
'log'                  => 'Logboeken',
'all-logs-page'        => 'Alle lochboeken',
'alllogstext'          => 'Kombinearre loch de {{SITENAME}}.
Jo kinne it oersjoch beheine troch in loch, in meidoggernamme of in side oan te jaan.',
'logempty'             => 'Gjin treffers yn it loch.',

# Special:AllPages
'allpages'          => 'Alle siden',
'alphaindexline'    => "$1 oan't $2",
'nextpage'          => 'Folgjende side ($1)',
'prevpage'          => 'Foargeande side ($1)',
'allpagesfrom'      => 'Siden sjen litte, te begjinnen mei:',
'allarticles'       => 'Alle siden',
'allinnamespace'    => 'Alle siden, yn de ($1-nammeromte)',
'allnotinnamespace' => 'Alle siden (útsein de $1-nammeromte)',
'allpagesprev'      => 'Eardere',
'allpagesnext'      => 'Fierder',
'allpagessubmit'    => 'Ynoarder',
'allpagesprefix'    => "Siden sjen litte dy't begjinne mei:",

# Special:Categories
'categories'         => 'Kategoryen',
'categoriespagetext' => 'Dizze wiki hat de neikommende kategoryen:',

# Special:ListUsers
'listusersfrom'    => 'Lit meidoggers sjen fanôf:',
'listusers-submit' => 'Sjen litte',

# Special:ListGroupRights
'listgrouprights-group'  => 'Groep',
'listgrouprights-rights' => 'Rjochten',

# E-mail user
'mailnologin'     => 'Gjin adres beskikber',
'mailnologintext' => 'Jo moatte [[Special:UserLogin|oanmeld]] wêze, en in jildich e-postadres [[Special:Preferences|ynsteld]] hawwe, om oan oare brûkers e-post stjoere te kinnen.',
'emailuser'       => 'Skriuw dizze brûker',
'emailpage'       => 'E-post nei meidogger',
'emailpagetext'   => "As dizze brûker in jildich e-postadres in ynsteld hat,
dan kinne jo ien berjocht ferstjoere.
It e-postadres dat jo ynsteld hawwe wurdt brûkt as de ôfstjoerder, sa't de ûntfanger
antwurdzje kin.",
'defemailsubject' => 'E-post fan {{SITENAME}}',
'noemailtitle'    => 'Gjin e-postadres',
'noemailtext'     => 'Dizze brûker had gjin jildich e-postadres ynsteld,
of hat oanjaan gjin post fan oare brûkers krije te wollen.',
'emailfrom'       => 'Fan',
'emailto'         => 'Oan',
'emailsubject'    => 'Oer',
'emailmessage'    => 'Tekst',
'emailsend'       => 'Stjoer',
'emailsent'       => 'Berjocht stjoerd',
'emailsenttext'   => 'Jo berjocht is stjoerd.',

# Watchlist
'watchlist'            => 'Folchlist',
'mywatchlist'          => 'Folchlist',
'watchlistfor'         => "(foar '''$1''')",
'nowatchlist'          => 'Jo hawwe gjin siden op jo folchlist.',
'watchnologin'         => 'Not oanmeld in',
'watchnologintext'     => 'Jo moatte [[Special:UserLogin|oanmeld]] wêze om jo folchlist te feroarjen.',
'addedwatch'           => 'Oan folchlist tafoege',
'addedwatchtext'       => "De side \"'''[[:\$1]]'''\" is tafoege oan jo [[Special:Watchlist|folchlist]]. Bewurkings fan dizze side en oerlisside wurde yn de takomst op jo folchlist oanjûn. Hja wurde foar jo ek '''fet''' printe op [[Special:RecentChanges|Koartlyn feroare]].

At jo letter in side net mear folgje wolle, dan brûke jo op dy side de keppeling \"Ferjit dizze side.\" Jo [[Special:Watchlist|folchlist]] hat ek in keppeling \"Jo folchlist bewurkje,\" foar at jo mear as ien side \"ferjitte\" wolle.",
'removedwatch'         => 'Net mear folgje',
'removedwatchtext'     => 'De side "<nowiki>$1</nowiki>" stiet net mear op jo folchlist.',
'watch'                => 'Folgje',
'watchthispage'        => 'Folgje dizze side',
'unwatch'              => 'Net folgje',
'unwatchthispage'      => 'Ferjit dizze side',
'notanarticle'         => 'Dit kin net folge wurde.',
'watchnochange'        => "Fan de siden dy't jo folgje is der yn dizze perioade net ien feroare.",
'watchlist-details'    => 'Jo folchlist hat {{PLURAL:$1|$1 side|$1 siden}}, oerlissiden net meiteld.',
'watchmethod-recent'   => 'Koarts feroare ...',
'watchmethod-list'     => 'Folge ...',
'watchlistcontains'    => 'Jo folgje op it stuit $1 {{PLURAL:$1|side|siden}}.',
'iteminvalidname'      => 'Misse namme: "$1" ...',
'wlnote'               => "Dit {{PLURAL:$1|is de lêste feroaring|binne de lêste '''$1''' feroarings}} yn de lêste {{PLURAL:$2|oer|'''$2''' oeren}}.",
'wlshowlast'           => 'Lêste $1 oeren, $2 dagen sjen litte ($3)',
'watchlist-hide-bots'  => 'Botbewurkings ferskûlje',
'watchlist-hide-own'   => 'Myn bewurkings ferskûlje',
'watchlist-hide-minor' => 'Lytse bewurkings ferskûlje',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => "Dwaande mei op'e folchlist te setten ...",
'unwatching' => "Dwaande mei fan'e folchlist ôf te heljen ...",

'enotif_newpagetext' => 'Dit is in nije side.',
'changed'            => 'feroare',
'created'            => 'oanmakke',
'enotif_body'        => 'Bêste $WATCHINGUSERNAME,

De {{SITENAME}}side \'$PAGETITLE\' is op $PAGEEDITDATE $CHANGEDORCREATED troch meidogger $PAGEEDITOR; 
sjoch $PAGETITLE_URL foar de aktuele ferzje.

$NEWPAGE

Gearfetting: $PAGESUMMARY $PAGEMINOREDIT

Foar oerlis mei meidogger $PAGEEDITOR:
- netpost: $PAGEEDITOR_EMAIL
- wiki: $PAGEEDITOR_WIKI

Fierdere meldings wurde jo net tastjoerd, oant jo de side lêzen hawwe. Op jo folchlist kinne jo op \'e nij meldings foar al jo folge siden freegje.

                 Jo freonlike {{SITENAME}}-meldingssysteem.

-- 
Gean nei {{fullurl:{{ns:special}}:Watchlist/edit}}
om jo folchlistynstellings te feroarjen.

Reaksjes en fierdere help:
{{fullurl:{{MediaWiki:Helppage}}}}',

# Delete/protect/revert
'deletepage'                  => 'Wisk side',
'confirm'                     => 'Befêstigje',
'excontent'                   => "ynhâld wie: '$1'",
'excontentauthor'             => "ynhâld wie: '$1' (en de ienige bewurker wie: '[[Special:Contributions/$2|$2]]')",
'exbeforeblank'               => "foar de tekst wiske wie, wie dat: '$1'",
'exblank'                     => 'side wie leech',
'delete-confirm'              => '"$1" wiskje',
'delete-legend'               => 'Wiskje',
'historywarning'              => "Waarskôging: De side dy't jo wiskje wolle hat skiednis:",
'confirmdeletetext'           => 'Jo binne dwaande mei it foar altyd wiskjen fan in side
of ôfbyld, tegearre mei alle skiednis, út de databank.
Befêstigje dat jo dat wier dwaan wolle. Befêstigje dat dat is wat jo witte wat it gefolch
is en dat jo dit dogge neffens de [[{{MediaWiki:Policy-url}}]].',
'actioncomplete'              => 'Dien',
'deletedtext'                 => '"<nowiki>$1</nowiki>" is wiske.
Sjoch "$2" foar in list fan wat resint wiske is.',
'deletedarticle'              => '"[[$1]]" is wiske',
'dellogpage'                  => 'Wisk_loch',
'dellogpagetext'              => 'Dit is wat der resint wiske is.
(Tiden oanjûn as UTC).',
'deletionlog'                 => 'wisk loch',
'reverted'                    => 'Tebekset nei eardere ferzje',
'deletecomment'               => 'Reden foar it wiskjen',
'deleteotherreason'           => 'Oare/eventuele reden:',
'deletereasonotherlist'       => 'Oare reden',
'deletereason-dropdown'       => '*Faak-brûkte redenen
** Frege troch de skriuwer
** Skeining fan auteursrjocht
** Fandalisme',
'rollback'                    => 'Feroarings tebeksette',
'rollback_short'              => 'Werom sette',
'rollbacklink'                => 'feroaring tebeksette',
'rollbackfailed'              => 'Feroaring tebeksette net slagge',
'cantrollback'                => "Disse feroaringt kin net tebek set, om't der mar ien skriuwer is.",
'alreadyrolled'               => 'Kin de feroaring fan [[:$1]]
troch [[User:$2|$2]] ([[User talk:$2|Oerlis]]) net tebeksette;
inoar hat de feroaring tebekset, of oars wat oan de side feroare.

De lêste feroaring wie fan [[User:$3|$3]] ([[User talk:$3|Oerlis]]).',
'editcomment'                 => 'De gearfetting wie: "<i>$1</i>".', # only shown if there is an edit comment
'revertpage'                  => 'Feroarings werom set fan [[Special:Contributions/$2|$2]] ([[User talk:$2|Oerlis]]) nei de lêste ferzje fan [[User:$1|$1]]', # Additional available: $3: revid of the revision reverted to, $4: timestamp of the revision reverted to, $5: revid of the revision reverted from, $6: timestamp of the revision reverted from
'rollback-success'            => 'Feroarings werom set fan $1; werom set nei de lêste ferzje fan $2.',
'protectlogpage'              => 'Befeiligingslochboek',
'protectlogtext'              => 'Hjirûnder wurdt it skoattele en frijjaan fan siden oanjûn. 
Sjoch [[Special:ProtectedPages|Skoattele side]] foar mear ynformaasje.',
'protectedarticle'            => '"[[$1]]" skoattele',
'unprotectedarticle'          => 'joech "[[$1]]" frij',
'protect-legend'              => 'Befeiliging befêstigje',
'protectcomment'              => 'Oanmerkings:',
'protectexpiry'               => 'Doer:',
'protect_expiry_invalid'      => 'De oanjûne doer is ûnjildich.',
'protect_expiry_old'          => 'Ferrindatum is yn it ferline',
'protect-unchain'             => 'Ferskowen mûglik meitsje',
'protect-text'                => 'Hjir kinne jo it befeiligingsnivo foar de side <strong><nowiki>$1</nowiki></strong> besjen en feroarje.',
'protect-locked-access'       => "'''Jo brûker hat gjin rjochten om it befeiligingsnivo te feroarjen.'''
Dit binne de rinnende ynstellings foar de side <strong>$1</strong>:",
'protect-cascadeon'           => "Dizze side is op 't stuit befeilige, om't er yn 'e folgjende {{PLURAL:$1|side|siden}} opnommen is, dy't befeilige {{PLURAL:$1|is|binne}} mei de kaskade-opsje. It befeiligingsnivo feroarje hat alhiel gjin effekt.",
'protect-default'             => '(standert)',
'protect-fallback'            => 'Hjir is it rjocht "$1" foar nedich',
'protect-level-autoconfirmed' => 'Allinne registrearre brûkers',
'protect-level-sysop'         => 'Allinne behearders',
'protect-summary-cascade'     => 'kaskade',
'protect-expiring'            => 'ferrint op $1',
'protect-cascade'             => "Kaskadebefeiliging - befeiligje alle siden en sjabloanen dy't yn dizze side opnommen binne (tink derom; dat kin grutte gefolgen hawwe).",
'protect-cantedit'            => "Jo kinne it befeiligingsnivo fan dizze side net feroarje, om't jo gjin rjochten hawwe om it te bewurkjen.",
'restriction-type'            => 'Rjochten:',
'restriction-level'           => 'Beheiningsnivo:',
'minimum-size'                => 'Min. grutte',
'maximum-size'                => 'Max. grutte:',
'pagesize'                    => '(bytes)',

# Restrictions (nouns)
'restriction-edit'   => 'Wizigje',
'restriction-move'   => 'Werneam',
'restriction-create' => 'Oanmeitsje',

# Restriction levels
'restriction-level-sysop'         => 'folslein skoattele',
'restriction-level-autoconfirmed' => 'skoattele foar anonymen',
'restriction-level-all'           => "alle nivo's",

# Undelete
'undelete'                => 'Side werom set',
'undeletepage'            => 'Side besjen en werom sette',
'viewdeletedpage'         => 'Wiske siden besjen',
'undeletepagetext'        => 'Dizze siden binne wiske, mar sitte noch yn it argyf en kinne weromset wurde.
(It argyf kin út en troch leechmeitsje wurde.)',
'undelete-fieldset-title' => 'Ferzjes werom sette',
'undeleteextrahelp'       => "Om in side hielendal werom te setten, lit alle seleksjefakjes iepen en klik '''''Weromsette'''''. Om in bepaalde ferzje werom te setten, klik de fakjes dy't mei de ferzjes oerienkomme, en klik '''''Weromsette'''''. Klik '''''Leechmeitsje''''' om it kommentaar fjild ensafuorthinne leech te meitsjen.",
'undeleterevisions'       => '$1 {{PLURAL:$1|ferzje|ferzjes}} in it argyf',
'undeletehistory'         => 'Soenen jo dizze side weromsette, dan wurde alle ferzjes weromset as part
fan de skiednis. As der in nije side is mei dizze namme, dan wurd de hjoeddeise ferzje <b>net</b>
troch de lêste ferzje út dy weromsette skiednis ferfangen.',
'undelete-revision'       => 'Wiske ferzje fan $1 (op $2) fan $3:',
'undelete-nodiff'         => 'Gjin eardere ferzje fûn.',
'undeletebtn'             => 'Weromset!',
'undeletelink'            => 'werom sette',
'undeletereset'           => 'Leechmeitsje',
'undeletecomment'         => 'Utlis foar weromsetten:',
'undeletedarticle'        => '"$1" weromset',
'undelete-header'         => 'Sjoch [[Special:Log/delete|de wiskloch]] foar resint wiske siden.',
'undelete-search-box'     => 'Sykje wiske siden',
'undelete-search-prefix'  => "Lit siden sjen dy't begjinne mei:",
'undelete-search-submit'  => 'Sykje',
'undelete-no-results'     => 'Gjin oerienkommende siden fûn yn it wisk argyf.',

# Namespace form on various pages
'namespace'      => 'Nammerûmte:',
'invert'         => 'Omkearde seleksje',
'blanknamespace' => '(Haadnammerûmte)',

# Contributions
'contributions' => 'Brûker bydragen',
'mycontris'     => 'Myn bydragen',
'contribsub2'   => 'Foar "$1 ($2)"',
'nocontribs'    => "Der binne gjin feroarings fûn dyt't hjirmei oerienkomme.",
'uctop'         => ' (boppen)',
'month'         => 'Fan moanne (en earder):',
'year'          => 'Fan jier (en earder):',

'sp-contributions-newbies-sub' => 'Foar nijlingen',
'sp-contributions-blocklog'    => 'Blokkearlochboek',
'sp-contributions-search'      => 'Sykje nei bydragen',
'sp-contributions-username'    => 'IP Adres of meidoggernamme:',
'sp-contributions-submit'      => 'Sykje',

# What links here
'whatlinkshere'            => 'Wat is hjirmei keppele',
'whatlinkshere-title'      => 'Siden dy\'t ferwize nei "$1"',
'whatlinkshere-page'       => 'Side:',
'linklistsub'              => '(List fan keppelings)',
'linkshere'                => "Dizze siden binne keppele oan '''[[:$1]]''':",
'nolinkshere'              => "Der binne gjin siden oan '''[[:$1]]''' keppele.",
'nolinkshere-ns'           => "Gjin siden yn de keazen nammeromte keppelje nei '''[[:$1]]'''.",
'isredirect'               => 'trochferwizing',
'istemplate'               => 'ynfoege as sjabloan',
'isimage'                  => 'byld keppeling',
'whatlinkshere-prev'       => '{{PLURAL:$1|foargeande|foargeande $1}}',
'whatlinkshere-next'       => '{{PLURAL:$1|folgjende|folgjende $1}}',
'whatlinkshere-links'      => '← links dêrnei ta',
'whatlinkshere-hideredirs' => '$1 trochferwizings',
'whatlinkshere-hidetrans'  => '$1 trânsklúzjes',
'whatlinkshere-hidelinks'  => '$1 keppelings',

# Block/unblock
'blockip'             => 'Slút brûker út',
'blockip-legend'      => 'Slút brûker út',
'blockiptext'         => "Brûk dizze fjilden om in brûker fan skriuwtagong út te sluten.
Dit soe allinnich omwillens fan fandalisme dwaan wurde moatte, sa't de
[[{{MediaWiki:Policy-url}}|útslut-rie]] it oanjout.
Meld de krekte reden! Begelyk, neam de siden dy't oantaaste waarden.",
'ipaddress'           => 'Brûkernamme of Ynternet-adres',
'ipadressorusername'  => 'IP Adres of meidoggernamme:',
'ipbexpiry'           => 'Ferrint nei:',
'ipbreason'           => 'Reden',
'ipbreasonotherlist'  => 'Oare reden',
'ipbanononly'         => 'Slút allinich anonyme meidoggers út',
'ipbcreateaccount'    => 'Blokkearje it oanmeitsjen fan in nij profyl',
'ipbenableautoblock'  => "Automatysk de lêste IP adressen útslute dy't troch dizze meidogger brûkt binne.",
'ipbsubmit'           => 'Slut dizze brûker út',
'ipbother'            => 'In oare tiid:',
'ipboptions'          => '15 minuten:15 min,1 oere:1 hour,2 oeren:2 hours,6 oeren:6 hours,12 oeren:12 hours,1 dei:1 day,3 dagen:3 days,1 wike:1 week,2 wiken:2 weeks,1 moanne:1 month,3 moanne:3 months,6 moanne:6 months,1 jier:1 year,ûnbeheind:infinite', # display1:time1,display2:time2,...
'ipbotheroption'      => 'oare tiid',
'ipbotherreason'      => 'Oare/eventuele reden:',
'badipaddress'        => 'Dy brûker bestiet net',
'blockipsuccesssub'   => 'Utsluting slagge',
'blockipsuccesstext'  => 'Brûker [[Special:Contributions/$1|$1]] is útsletten.<br />
(List fan [[Special:IPBlockList|útslette brûkers]].)',
'ipb-unblock-addr'    => 'Lit $1 yn',
'ipb-unblock'         => 'Lit in meidogger of IP-adres yn',
'ipb-blocklist-addr'  => 'Besteande útsluting foar $1 besjen',
'ipb-blocklist'       => 'Besteande útslutings besjen',
'unblockip'           => 'Lit brûker der wer yn',
'unblockiptext'       => 'Brûk dizze fjilden om in brûker wer skriuwtagong te jaan.',
'ipusubmit'           => 'Lit dizze brûker der wer yn',
'ipblocklist'         => 'List fan útsletten Ynternet-adressen en brûkersnammen',
'ipblocklist-submit'  => 'Sykje',
'blocklistline'       => '"$3", troch "$2" op $1 ($4)',
'infiniteblock'       => 'trochgeand',
'blocklink'           => 'slut út',
'unblocklink'         => 'lit yn',
'contribslink'        => 'bydragen',
'autoblocker'         => 'Jo wiene útsletten om\'t jo ynternet-adres oerienkomt mei dat fan "[[User:$1|$1]]". Foar it útsluten fan dy meidogger waard dizze reden jûn: "$2".',
'blocklogpage'        => 'Blokkearlochboek',
'blocklogentry'       => 'blokkearre "[[$1]]" foar de doer fan $2 $3',
'blocklogtext'        => 'Dit is in loch fan it útsluten en talitten fan meidoggers. Fansels útsletten net-adressen binne net opnaam. Sjoch de [[Special:IPBlockList|útsletlist]] foar de no jildende utslettings.',
'ipb_expiry_invalid'  => 'Tiid fan ferrinnen is net goed.',
'ipb_already_blocked' => '"$1" is al útsluten',
'ipb_cant_unblock'    => 'Flater: It útsluten fan ID $1 kin net fûn wurde. It is miskien al net mear útsluten.',
'proxyblocksuccess'   => 'Dien.',

# Developer tools
'lockdb'              => "Meitsje de database 'Net-skriuwe'",
'unlockdb'            => 'Meitsje de databank skriuwber',
'lockdbtext'          => "Salang as de databank 'Net-skriuwe' is,
is foar brûkers it feroarjen fan siden, ynstellings, folchlisten, ensfh. net mooglik.
Befêstigje dat dit is wat jo wolle, en dat jo de databank wer skriuwber meitsje as
jo ûnderhâld ree is.",
'unlockdbtext'        => 'As de databank skriuwber makke wurdt,
is foar brûkers it feroarjen fan siden, ynstelingen, folchlisten, ensfh, wer mooglik.
Befêstigje dat dit is wat jo wolle.',
'lockconfirm'         => "Ja, ik wol wier de databank 'Net--skriuwe' meitsje.",
'unlockconfirm'       => 'Ja, ik wol wier de databank skriuwber meitsje.',
'lockbtn'             => "Meitsje de database 'Net-skriuwe'",
'unlockbtn'           => 'Meitsje de databank skriuwber',
'locknoconfirm'       => 'Jo hawwe jo hanneling net befêstige.',
'lockdbsuccesssub'    => "Databank is 'Net-skriuwe'",
'unlockdbsuccesssub'  => 'Database is skriuwber',
'lockdbsuccesstext'   => "De {{SITENAME}} databank is 'Net-skriuwe' makke.
<br />Tink derom en meitsje de databank skriuwber as jo ûnderhâld ree is.",
'unlockdbsuccesstext' => 'De {{SITENAME}} databank is skriuwber makke.',

# Move page
'move-page'               => 'Werneam  $1',
'move-page-legend'        => 'Werneam side',
'movepagetext'            => "Dit werneamt in side, mei alle sideskiednis.
De âlde titel wurdt in trochferwizing nei de nije.
Keppelings mei de âlde side wurde net feroare;
gean sels nei of't der dûbele of misse ferwizings binne.
It hinget fan jo ôf of't de siden noch keppelen binne sa't it mient wie.

De side wurdt '''net''' werneamt as der al in side mei dy namme is, útsein as it in side
sûnder skiednis is en de side leech is of in trochferwizing is. Sa kinne jo in side
daalks weromneame as jo in flater meitsje, mar jo kinne in oare side net oerskriuwe.",
'movepagetalktext'        => "As der in oerlisside by heart, dan bliuwt dy oan de side keppele, '''útsein''':
*De nije sidenamme yn in oare nammeromte is,
*Der keppele oan de nije namme al in net-lege oerlisside is, of
*Jo dêr net foar kieze.

In dizze gefallen is it oan jo hoe't jo de oerlisside werneame of ynfoegje wolle.",
'movearticle'             => 'Werneam side',
'newtitle'                => 'As nij titel',
'move-watch'              => 'Dizze side folgje',
'movepagebtn'             => 'Werneam side',
'pagemovedsub'            => 'Werneamen slagge',
'movepage-moved'          => '<big>\'\'\'"$1" hjit no "$2"\'\'\'</big>', # The two titles are passed in plain text as $3 and $4 to allow additional goodies in the message.
'articleexists'           => 'In side mei dy namme bestiet al of de sidenamme is ûnjildich.
Kies in  oare sidenamme a.j.w.',
'talkexists'              => "It werneamen op sich is slagge, mar de eardere oerlisside is
net mear keppele om't der foar de nije namme el al in oerlisside wie.
Gearfoegje de oerlissiden hânmjittig.",
'movedto'                 => 'werenamd as',
'movetalk'                => 'Derby hearrende oerlisside ferskowe',
'movepage-page-moved'     => 'De side $1 is werneamd nei $2.',
'1movedto2'               => '[[$1]] ferskood nei [[$2]]',
'1movedto2_redir'         => '[[$1]] ferskood nei [[$2]], wat in synonym wie',
'movelogpage'             => 'Lochboek fan ferskode siden',
'movelogpagetext'         => 'Dit is in list fan feroare titels.',
'movereason'              => 'Reden:',
'revertmove'              => 'werom sette',
'delete_and_move'         => 'Wiskje en werneam',
'delete_and_move_text'    => '== Wiskjen nedich ==
De doelside "[[:$1]]" is der al. Moat dy wiske wurde om plak te meitsjen foar it werneamen?',
'delete_and_move_confirm' => 'Ja, wiskje de side',
'delete_and_move_reason'  => 'Wiske om plak te meitsjen foar in werneamde side',
'immobile_namespace'      => "De nije titel is yn in nammeromte dêr't gjin siden oan tafoege wurde kinne.",

# Export
'export'           => 'Eksportearje',
'export-submit'    => 'Eksportearje',
'export-addcat'    => 'Tafoegje',
'export-download'  => 'Fêstlizze as triem',
'export-templates' => 'Tafoegje berjochten',

# Namespace 8 related
'allmessages'         => 'Alle wikiberjochten',
'allmessagesname'     => 'Namme',
'allmessagesdefault'  => 'Standerttekst',
'allmessagescurrent'  => 'Tekst op it stuit',
'allmessagestext'     => 'Dit is in list fan alle systeemberjochten beskikber yn de MediaWiki-nammeromte.
Sjoch: [http://www.mediawiki.org/wiki/Localisation MediaWiki Localisation], [http://translatewiki.net Betawiki].',
'allmessagesfilter'   => 'Berjocht namme filter:',
'allmessagesmodified' => 'Allinne wizige berjochten',

# Thumbnails
'thumbnail-more'  => 'Grutter',
'filemissing'     => 'Triem net fûn',
'thumbnail_error' => 'Flater by it oanmeitsjen fan thumbnail: $1',

# Special:Import
'import'                  => 'Importearje siden',
'import-interwiki-submit' => 'Ymportearje',
'importstart'             => 'Siden oan it ymportearjen ...',
'import-revision-count'   => '$1 {{PLURAL:$1|ferzje|ferzjes}}',
'importnopages'           => 'Gjin siden te ymportearjen.',
'importfailed'            => 'Ymport fout: <nowiki>$1</nowiki>',
'importcantopen'          => 'De ymporttriem koe net iepenen wurde.',
'importbadinterwiki'      => 'Ferkearde ynterwikiferwizing',
'importnotext'            => 'Leech of gjin tekst',
'importsuccess'           => 'Ymport slagge!',
'importnofile'            => 'Gjin ymporttriem is oanbeane.',
'import-noarticle'        => 'Gjin side te ymportearjen!',
'import-nonewrevisions'   => 'Alle ferzjes wurde al ymportearre.',
'xml-error-string'        => '$1 op regel $2, kolom $3 (byte $4): $5',

# Import log
'importlogpage'                    => 'Ymportlochboek',
'import-logentry-upload-detail'    => '$1 {{PLURAL:$1|ferzje|ferzjes}}',
'import-logentry-interwiki-detail' => '$1 {{PLURAL:$1|ferzje|ferzjes}} fan $2',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'Myn brûkersside',
'tooltip-pt-mytalk'               => 'Myn oerlisside',
'tooltip-pt-preferences'          => 'Myn foarkarynstellings',
'tooltip-pt-watchlist'            => "List fan siden dy'sto besjochst op feroarings",
'tooltip-pt-mycontris'            => 'Myn bydragen',
'tooltip-pt-login'                => 'Jo wurde fan herten útnoege jo oan te melden, mar it hoecht net.',
'tooltip-pt-logout'               => 'Ofmelde',
'tooltip-ca-talk'                 => 'Oerlis oer dizze side',
'tooltip-ca-edit'                 => "Jo kinne dizze side bewurkje. Brûk a.j.w. de foarbyldwerjefteknop foar't Jo de boel bewarje.",
'tooltip-ca-addsection'           => 'In opmerking tafoegje oan de oerlis-side.',
'tooltip-ca-viewsource'           => 'Dizze side is befeilige, mar jo kinne de boarne wol besjen.',
'tooltip-ca-protect'              => 'Dizze side befeiligje',
'tooltip-ca-delete'               => 'Dizze side weidwaan',
'tooltip-ca-move'                 => 'Dizze side ferskowe',
'tooltip-ca-watch'                => 'Dizze side oan myn folchside tafoegje',
'tooltip-ca-unwatch'              => 'Dizze side fan myn folchlist ôfhelje',
'tooltip-search'                  => '{{SITENAME}} trochsykje',
'tooltip-p-logo'                  => 'Haadside',
'tooltip-n-mainpage'              => 'Gean nei de haadside',
'tooltip-n-portal'                => "Oer it projekt: wat'st dwaan kinst, wêr'st dingen fine kinst.",
'tooltip-n-currentevents'         => 'Eftergrûnynformaasje oer rinnende saken.',
'tooltip-n-recentchanges'         => 'De list fan koartlyn oanbrochte feroarings yn dizze wiki.',
'tooltip-n-randompage'            => 'Samar in side sjen litte.',
'tooltip-n-help'                  => 'Helpynformaasje oer dizze wiki.',
'tooltip-t-whatlinkshere'         => "List fan alle siden dy't nei dizze side ferwize",
'tooltip-t-contributions'         => 'Bydragen fan dizze brûker',
'tooltip-t-emailuser'             => 'Stjoer in e-mail nei dizze brûker',
'tooltip-t-upload'                => 'Triemmen oplade',
'tooltip-t-specialpages'          => 'List fan alle spesjale siden',
'tooltip-ca-nstab-user'           => 'Brûkersside sjen litte',
'tooltip-ca-nstab-project'        => 'Projektside sjen litte',
'tooltip-ca-nstab-image'          => 'De triemside sjen litte',
'tooltip-ca-nstab-template'       => 'Sjabloan sjen litte',
'tooltip-ca-nstab-help'           => 'Helpside sjen litte',
'tooltip-ca-nstab-category'       => 'Kategory-side sjen litte',
'tooltip-minoredit'               => 'Markearje dit as in lytse feroaring',
'tooltip-save'                    => 'Jo feroarings bewarje',
'tooltip-preview'                 => "Oerlêze foar't de side fêstlein is!",
'tooltip-diff'                    => "Sjen litte hokker feroarings jo yn'e tekst makke hawwe.",
'tooltip-compareselectedversions' => 'Sjoch de ferskillen tusken de twa keazen ferzjes fan dizze side.',
'tooltip-watch'                   => 'Foegje dizze side ta oan jo folchlist',

# Attribution
'anonymous' => 'Anonime meidogger(s) fan {{SITENAME}}',
'siteuser'  => '{{SITENAME}} meidogger $1',
'siteusers' => '{{SITENAME}} meidogger(s) $1',

# Spam protection
'spamprotectiontext' => "De side dy't jo fêstlizze woene is blokkearre troch in spam filter. Dit wurdt wierskynlik feroarsake troch in ferwizing nei in ekstern webstee.",

# Info page
'infosubtitle' => 'Ynformaasje foar side',

# Math options
'mw_math_png'    => 'Altiten as PNG ôfbyldzje',
'mw_math_simple' => 'HTML foar ienfâldiche formules, oars PNG',
'mw_math_html'   => 'HTML as mooglik, oars PNG',
'mw_math_source' => 'Lit de TeX ferzje stean (foar tekstblêders)',
'mw_math_modern' => 'Oanbefelle foar resinte blêders',
'mw_math_mathml' => 'MathML',

# Patrolling
'markaspatrolleddiff'                 => 'Markearje as kontroleare',
'markaspatrolledtext'                 => 'Markearje dizze side as kontrolearre',
'markedaspatrolled'                   => 'Markearre as kontrolearre',
'markedaspatrolledtext'               => 'De selektearre ferzje is markearre as kontrolearre.',
'markedaspatrollederror'              => 'Kin net as kontrolearre markearre wurde',
'markedaspatrollederrortext'          => "Jo moatte in ferzje oanjaan dy't jo as kontrolearre markearje.",
'markedaspatrollederror-noautopatrol' => 'Jo meie jo eigen bewurkings net sels markearre.',

# Browsing diffs
'previousdiff' => '← Foargeande feroaring',
'nextdiff'     => 'Folgjende feroaring →',

# Media information
'imagemaxsize'         => 'Behein ôfmjittings fan ôfbyld op beskriuwingsside ta:',
'thumbsize'            => 'Mjitte fan miniatueren:',
'file-info'            => '(triemgrutte: $1, MIME-type: $2)',
'file-info-size'       => '($1 × $2 pixel, triemgrutte: $3, MIME type: $4)',
'file-nohires'         => '<small>Gjin hegere resolúsje beskikber.</small>',
'svg-long-desc'        => '(SVG-triem, nominaal $1 × $2 pixels, triemgrutte: $3)',
'show-big-image'       => 'Hegere resolúsje',
'show-big-image-thumb' => '<small>Grutte fan dizze ôfbylding: $1 × $2 pixels</small>',

# Special:NewImages
'newimages'     => 'Nije ôfbyldings',
'imagelisttext' => "Dit is in list fan '''$1''' {{PLURAL:$1|triem|triemen}}, op $2.",
'showhidebots'  => '(Bots $1)',
'noimages'      => 'Neat te sjen.',
'ilsubmit'      => 'Sykje',
'bydate'        => 'datum',

# Bad image list
'bad_image_list' => "De opmaak is as folget:

Allinne rigels fan in list (rigels dy't begjinne mei *) wurde ferwurke. De earste link op in rigel moat in link wêze nei in net winske ôfbylding.
Alle folgjende links dy't op deselde rigel steane, wurde behannele as útsûndering, lykas bygelyks siden dêr't de ôfbylding yn'e tekst opnommen is.",

# Metadata
'metadata'          => 'Metadata',
'metadata-help'     => "Dizze triem befettet oanfoljende ynformaasje, dy't troch in fotokamera, scanner of fotobewurkingsprogramma tafoege wêze kin. As de triem oanpast is, komme de details mûglik net folslein oerien mei de feroare ôfbylding.",
'metadata-expand'   => 'Utwreide details sjen litte',
'metadata-collapse' => 'Ferskûlje útwreide details',
'metadata-fields'   => 'De EXIF-metadatafjilden yn dit berjocht steane op in ôfbyldingsside as de metadatatabel ynklapt is. Oare fjilden wurde ferburgen.
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* focallength', # Do not translate list items

# EXIF tags
'exif-imagedescription'    => 'Ofbylding titel',
'exif-make'                => 'Kamera makker',
'exif-artist'              => 'Auteur',
'exif-makernote'           => 'Opmerkings fan makker',
'exif-usercomment'         => 'Opmerkings',
'exif-relatedsoundfile'    => 'Besibbe audiotriem',
'exif-exposuretime-format' => '$1 sek ($2)',
'exif-gpsdatestamp'        => 'GPS-datum',

'exif-unknowndate' => 'Datum ûnbekend',

'exif-orientation-1' => 'Normaal', # 0th row: top; 0th column: left

'exif-exposureprogram-0' => 'Net bepaald',
'exif-exposureprogram-2' => 'Normaal programma',

'exif-subjectdistance-value' => '$1 meter',

'exif-meteringmode-0' => 'Unbekend',

'exif-lightsource-0' => 'Unbekend',

'exif-customrendered-0' => 'Normale ferwurking',

'exif-scenecapturetype-0' => 'Standert',

'exif-gaincontrol-0' => 'Gjin',

'exif-contrast-0' => 'Normaal',

'exif-saturation-0' => 'Normaal',

'exif-sharpness-0' => 'Normaal',

'exif-subjectdistancerange-0' => 'Unbekend',

# External editor support
'edit-externally'      => 'Dizze triem bewurkje mei in ekstern programma',
'edit-externally-help' => 'Sjoch de [http://www.mediawiki.org/wiki/Manual:External_editors ynstel-hantlieding] foar mear ynformaasje.',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'alle',
'imagelistall'     => 'alle',
'watchlistall2'    => 'alles',
'namespacesall'    => 'alle',
'monthsall'        => 'alle',

# E-mail address confirmation
'confirmemail'            => 'Befêstigjen netpostadres',
'confirmemail_text'       => '{{SITENAME}} freget dat jo jo netpostadres befêstigje eart jo hjir netpost brûke. Brûk de knop hjirûnder om josels in befêstigingskoade ta te stjoeren op it adres dat jo opjûn hawwe. Iepenje de koade dan yn jo blêder om te befêstigjen dat jo netpostadres jildich is.',
'confirmemail_send'       => 'Stjoer in befêstigingskoade',
'confirmemail_sent'       => 'Befêstiginskoade tastjoerd.',
'confirmemail_sendfailed' => 'De befêstiginskoade koe net stjoerd wurde. Faaks stean der ferkearde tekens yn it netpostadres.

Berjocht: $1',
'confirmemail_invalid'    => 'Dizze befêstiginskoade jildt net (mear). 
Faaks is de koade ferrûn.',
'confirmemail_success'    => 'Jo netpostadres is befêstige. Jo kinne jo no oanmelde en de wiki brûke.',
'confirmemail_loggedin'   => 'Jo netpostadres is no befêstige.',
'confirmemail_error'      => 'Der is wat skeefgongen by it fêstlizzen fan jo befêstiging.',
'confirmemail_subject'    => '{{SITENAME}} netpostbefêstiging',
'confirmemail_body'       => 'Immen, nei gedachten jo, hat dit netpostadres ($1) brûkt by de ynskriuwing as meidogger "$2" oan {{SITENAME}}.

Jo wurdt frege de neikommende keppeling oer te nimmen yn jo blêder, ta it befêstigjen dat jo dy meidogger binne. Hjirtroch komme ek de netpostfunksjes fan {{SITENAME}} foar jo beskikber. At jo dit *net* west hawwe, brûk de keppeling dan net.

$3

annulearjen: 

$5

Dit berjocht jildt oant $4.',

# Delete conflict
'confirmrecreate' => "Sûnt jo begûn binne dizze side te bewurkjen, hat meidogger [[User:$1|$1]] ([[User talk:$1|oerlis]]) de side wiske. De reden dy't derfoar jûn waard wie:
: ''$2''
Wolle jo de side wier op 'e nij skriuwe?",

# HTML dump
'redirectingto' => 'Synonym foar [[:$1]]...',

# Auto-summaries
'autosumm-blank'   => 'Alle ynhâld fan de side weismiten',
'autosumm-replace' => "Side ferfong mei '$1'",
'autoredircomment' => 'Ferwiist troch nei [[$1]]',
'autosumm-new'     => 'Nije Side: $1',

# Live preview
'livepreview-loading' => 'Ynlade...',

# Watchlist editor
'watchlistedit-raw-titles' => 'Siden:',
'watchlistedit-raw-added'  => '{{PLURAL:$1|1 side is|$1 siden binne}} tafoege:',

# Watchlist editing tools
'watchlisttools-view' => 'Folchlist besjen',
'watchlisttools-edit' => 'Folchlist besjen en bewurkje',
'watchlisttools-raw'  => 'Rûge folchlist bewurkje',

# Special:Version
'version'                  => 'Programmatuerferzje', # Not used as normal message but as header for the special page itself
'version-version'          => 'Ferzje',
'version-license'          => 'Lisinsje',
'version-software-product' => 'Produkt',
'version-software-version' => 'Ferzje',

# Special:FilePath
'filepath'        => 'Triempad',
'filepath-page'   => 'Triem:',
'filepath-submit' => 'Pad',

# Special:FileDuplicateSearch
'fileduplicatesearch'          => 'Sykje op duplikaten',
'fileduplicatesearch-legend'   => 'Sykje op duplikaten',
'fileduplicatesearch-filename' => 'Triemnamme:',
'fileduplicatesearch-submit'   => 'Sykje',
'fileduplicatesearch-info'     => '$1 × $2 pixel<br />Triemgrutte: $3<br />MIME-type: $4',
'fileduplicatesearch-result-1' => 'De triem "$1" hat gjin duplikaten.',
'fileduplicatesearch-result-n' => 'De triem "$1" hat {{PLURAL:$2|1 duplikaat|$2 duplikaten}}.',

# Special:SpecialPages
'specialpages'                   => 'Bysûndere siden',
'specialpages-note'              => '----
* Normale bysûndere siden.
* <span class="mw-specialpagerestricted">Beheinde bysûndere siden.</span>',
'specialpages-group-maintenance' => 'Underhâld siden',
'specialpages-group-other'       => 'Oare bysûndere siden',
'specialpages-group-login'       => 'Oanmelde',
'specialpages-group-changes'     => 'Koartlyn feroare en lochs',
'specialpages-group-media'       => 'Oanbieden en oare triemsiden',
'specialpages-group-users'       => 'Meidoggers en rjochten',
'specialpages-group-highuse'     => "Siden dy't in protte brûkt wurde",
'specialpages-group-pages'       => 'List fan siden',
'specialpages-group-pagetools'   => 'Sidehelpmiddels',
'specialpages-group-wiki'        => 'Wikigegevens en -helpmiddels',
'specialpages-group-redirects'   => 'Trochferwizende bysûndere siden',
'specialpages-group-spam'        => 'Spamhelpmiddels',

# Special:BlankPage
'blankpage'              => 'Side is leech',
'intentionallyblankpage' => 'Dizze side is bewust leech lizzen en wurdt brûkt foar benchmarks, ensfh.',

);
