<?php
/** Low Saxon (Plattdüütsch)
 *
 * @addtogroup Language
 */
$magicWords = array(
	#   ID                                 CASE  SYNONYMS
	'redirect'               => array( 0, '#redirect',                   '#wiederleiden'          ),
	'notoc'                  => array( 0, '__NOTOC__',                   '__KEENINHOLTVERTEKEN__' ),
	'forcetoc'               => array( 0, '__FORCETOC__',                '__WIESINHOLTVERTEKEN__' ),
	'toc'                    => array( 0, '__TOC__',                     '__INHOLTVERTEKEN__'     ),
	'noeditsection'          => array( 0, '__NOEDITSECTION__',           '__KEENÄNNERNLINK__'     ),
	'start'                  => array( 0, '__START__'                                             ),
	'currentmonth'           => array( 1, 'CURRENTMONTH',                'AKTMAAND'               ),
	'currentmonthname'       => array( 1, 'CURRENTMONTHNAME',            'AKTMAANDNAAM'           ),
	'currentday'             => array( 1, 'CURRENTDAY',                  'AKTDAG'                 ),
	'currentdayname'         => array( 1, 'CURRENTDAYNAME',              'AKTDAGNAAM'             ),
	'currentyear'            => array( 1, 'CURRENTYEAR',                 'AKTJOHR'                ),
	'currenttime'            => array( 1, 'CURRENTTIME',                 'AKTTIED'                ),
	'numberofarticles'       => array( 1, 'NUMBEROFARTICLES',            'ARTIKELTALL'            ),
	'currentmonthnamegen'    => array( 1, 'CURRENTMONTHNAMEGEN',         'AKTMAANDNAAMGEN'        ),
	'pagename'               => array( 1, 'PAGENAME',                    'SIETNAAM'               ),
	'pagenamee'              => array( 1, 'PAGENAMEE',                   'SIETNAAME'              ),
	'namespace'              => array( 1, 'NAMESPACE',                   'NAAMRUUM'               ),
	'subst'                  => array( 0, 'SUBST:'                                                ),
	'msgnw'                  => array( 0, 'MSGNW:'                                                ),
	'img_thumbnail'          => array( 1, 'thumbnail', 'thumb',          'duum'                   ),
	'img_right'              => array( 1, 'right',                       'rechts'                 ),
	'img_left'               => array( 1, 'left',                        'links'                  ),
	'img_none'               => array( 1, 'none',                        'keen'                   ),
	'img_width'              => array( 1, '$1px',                        '$1px'                   ),
	'img_center'             => array( 1, 'center', 'centre',            'merrn'                  ),
	'img_framed'             => array( 1, 'framed', 'enframed', 'frame', 'rahmt'                  ),
	'int'                    => array( 0, 'INT:'                                                  ),
	'sitename'               => array( 1, 'SITENAME',                    'STEEDNAAM'              ),
	'ns'                     => array( 0, 'NS:',                         'NR:'                    ),
	'localurl'               => array( 0, 'LOCALURL:',                   'STEEDURL:'              ),
	'localurle'              => array( 0, 'LOCALURLE:',                  'STEEDURLE:'             ),
	'server'                 => array( 0, 'SERVER',                      'SERVER'                 ),
	'grammar'                => array( 0, 'GRAMMAR:',                    'GRAMMATIK:'             )
);

$skinNames = array(
	'standard'      => 'Klassik',
	'nostalgia'     => 'Nostalgie',
	'cologneblue'   => 'Kölsch Blau',
	'chick'         => 'Küken'
);


$bookstoreList = array(
	'Verteken vun leverbore Böker'  => 'http://www.buchhandel.de/sixcms/list.php?page=buchhandel_profisuche_frameset&suchfeld=isbn&suchwert=$1=0&y=0',
	'abebooks.de'                   => 'http://www.abebooks.de/servlet/BookSearchPL?ph=2&isbn=$1',
	'Amazon.de'                     => 'http://www.amazon.de/exec/obidos/ISBN=$1',
	'Lehmanns Fachbuchhandlung'     => 'http://www.lob.de/cgi-bin/work/suche?flag=new&stich1=$1',
);

$namespaceNames = array(
	NS_MEDIA            => 'Media',
	NS_SPECIAL          => 'Spezial',
	NS_MAIN             => '',
	NS_TALK             => 'Diskuschoon',
	NS_USER             => 'Bruker',
	NS_USER_TALK        => 'Bruker_Diskuschoon',
	# NS_PROJECT set by $wgMetaNamespace
	NS_PROJECT_TALK     => '$1_Diskuschoon',
	NS_IMAGE            => 'Bild',
	NS_IMAGE_TALK       => 'Bild_Diskuschoon',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'MediaWiki_Diskuschoon',
	NS_TEMPLATE         => 'Vörlaag',
	NS_TEMPLATE_TALK    => 'Vörlaag_Diskuschoon',
	NS_HELP             => 'Hülp',
	NS_HELP_TALK        => 'Hülp_Diskuschoon',
	NS_CATEGORY         => 'Kategorie',
	NS_CATEGORY_TALK    => 'Kategorie_Diskuschoon'
);
$linkTrail = '/^([äöüßa-z]+)(.*)$/sDu';
$separatorTransformTable = array(',' => '.', '.' => ',' );

$dateFormats = array(
	'mdy time' => 'H:i',
	'mdy date' => 'M j., Y',
	'mdy both' => 'H:i, M j., Y',

	'dmy time' => 'H:i',
	'dmy date' => 'j. M Y',
	'dmy both' => 'H:i, j. M Y',

	'ymd time' => 'H:i',
	'ymd date' => 'Y M j.',
	'ymd both' => 'H:i, Y M j.',
);

$messages = array(
# User preference toggles
'tog-underline'               => 'Verwies ünnerstrieken',
'tog-highlightbroken'         => 'Verwies op leddige Sieten hervörheven',
'tog-justify'                 => 'Text as Blocksatz',
'tog-hideminor'               => 'Kene lütten Ännern in letzte Ännern wiesen',
'tog-usenewrc'                => 'Erwiederte letzte Ännern (nich för alle Browser bruukbor)',
'tog-numberheadings'          => 'Överschrieven automatsch nummereern',
'tog-showtoolbar'             => 'Editeer-Warktüüchlist wiesen',
'tog-editondblclick'          => 'Sieten mit Dubbelklick bearbeiden (JavaScript)',
'tog-editsection'             => 'Links för dat Bearbeiden vun en Afsatz wiesen',
'tog-editsectiononrightclick' => 'En Afsatz mit en Rechtsklick bearbeiden (Javascript)',
'tog-showtoc'                 => "Wiesen vun'n Inholtsverteken bi Sieten mit mehr as dree Överschriften",
'tog-rememberpassword'        => 'Duersam Inloggen',
'tog-editwidth'               => 'Text-Ingaavfeld mit vulle Breed',
'tog-watchcreations'          => 'Nee schrevene Sieden op miene Oppasslist setten',
'tog-watchdefault'            => 'Op ne’e un ännerte Sieden oppassen',
'tog-watchmoves'              => 'Sieden, de ik schuuv, to de Oppasslist todoon',
'tog-watchdeletion'           => 'Sieden, de ik wegsmiet, to de Oppasslist todoon',
'tog-minordefault'            => 'Alle Ännern as lütt markeern',
'tog-previewontop'            => 'Vörschau vör dat Editeerfinster wiesen',
'tog-previewonfirst'          => "Vörschau bi'n eersten Ännern wiesen",
'tog-nocache'                 => 'Sietencache deaktiveern',
'tog-enotifwatchlistpages'    => 'Schriev mi en Nettbreef, wenn ene Siet, op de ik oppass, ännert warrt',
'tog-enotifusertalkpages'     => 'Schriev mi en Nettbreef, wenn ik ne’e Narichten heff',
'tog-enotifminoredits'        => 'Schriev mi en Nettbreef, ok wenn dat blots en lütte Ännern weer',
'tog-enotifrevealaddr'        => 'Miene Nettbreefadress in Bestätigungsnettbreven wiesen',
'tog-shownumberswatching'     => 'Wies de Tall vun Brukers, de op disse Siet oppasst',
'tog-fancysig'                => 'eenfache Signatur (ahn Lenk)',
'tog-externaldiff'            => 'Extern Warktüüch to’n Wiesen vun Ünnerscheden as Standard bruken',
'tog-uselivepreview'          => 'Live-Vörschau bruken (JavaScript) (Experimental)',
'tog-forceeditsummary'        => 'Segg mi bescheid, wenn ik keen Tosamenfaten geven heff, wat ik allens ännert heff',
'tog-watchlisthideown'        => 'Ännern vun mi sülvs op de Oppasslist nich wiesen',
'tog-watchlisthidebots'       => 'Ännern vun Bots op de Oppasslist nich wiesen',
'tog-nolangconversion'        => 'Variantenkonverschoon utschalten',
'tog-ccmeonemails'            => 'vun Nettbreven, de ik wegschick, an mi sülvst Kopien schicken',

'underline-always'  => 'Jümmer',
'underline-never'   => 'Nienich',
'underline-default' => 'so as in’n Nettkieker instellt',

'skinpreview' => '(Vörschau)',

# Dates
'sunday'        => 'Sünndag',
'monday'        => 'Maandag',
'tuesday'       => 'Dingsdag',
'wednesday'     => 'Merrweek',
'thursday'      => 'Dunnersdag',
'friday'        => 'Freedag',
'saturday'      => 'Sünnavend',
'sun'           => 'Sü',
'mon'           => 'Ma',
'tue'           => 'Di',
'wed'           => 'Mi',
'thu'           => 'Du',
'fri'           => 'Fr',
'sat'           => 'Sa',
'january'       => 'Januar',
'february'      => 'Februar',
'march'         => 'März',
'april'         => 'April',
'may_long'      => 'Mai',
'june'          => 'Juni',
'july'          => 'Juli',
'august'        => 'August',
'september'     => 'September',
'october'       => 'Oktober',
'november'      => 'November',
'december'      => 'Dezember',
'january-gen'   => 'Januar',
'february-gen'  => 'Februar',
'march-gen'     => 'März',
'april-gen'     => 'April',
'may-gen'       => 'Mai',
'june-gen'      => 'Juni',
'july-gen'      => 'Juli',
'august-gen'    => 'August',
'september-gen' => 'September',
'october-gen'   => 'Oktober',
'november-gen'  => 'November',
'december-gen'  => 'Dezember',
'jan'           => 'Jan',
'feb'           => 'Feb',
'mar'           => 'Mär',
'apr'           => 'Apr',
'may'           => 'Mai',
'jun'           => 'Jun',
'jul'           => 'Jul',
'aug'           => 'Aug',
'sep'           => 'Sep',
'oct'           => 'Okt',
'nov'           => 'Nov',
'dec'           => 'Dez',

# Bits of text used by many pages
'categories'            => '{{PLURAL:$1|Kategorie|Kategorien}}',
'pagecategories'        => '{{PLURAL:$1|Kategorie|Kategorien}}',
'category_header'       => 'Sieden in de Kategorie „$1“',
'subcategories'         => 'Ünnerkategorien',
'category-media-header' => 'Mediendatein in de Kategorie „$1“',

'mainpagetext'      => 'De Wiki-Software is mit Spood installeert worrn.',
'mainpagedocfooter' => 'Kiek de [http://meta.wikimedia.org/wiki/MediaWiki_i18n Dokumentatschoon för dat Anpassen vun de Brukerböversiet]
un dat [http://meta.wikimedia.org/wiki/MediaWiki_User%27s_Guide Brukerhandbook] för Hülp to de Bruuk un Konfiguratschoon.',

'about'          => 'Över',
'article'        => 'Artikel',
'newwindow'      => '(apent sik in en nieg Finster)',
'cancel'         => 'Afbreken',
'qbfind'         => 'Finnen',
'qbbrowse'       => 'Blädern',
'qbedit'         => 'Ännern',
'qbpageoptions'  => 'Sietenoptschonen',
'qbpageinfo'     => 'Sietendaten',
'qbmyoptions'    => 'Instellen',
'qbspecialpages' => 'Spezialsieten',
'moredotdotdot'  => 'Mehr...',
'mypage'         => 'Mien Siet',
'mytalk'         => 'Mien Diskuschoon',
'anontalk'       => 'Diskuschoonssiet vun disse IP',
'navigation'     => 'Navigatschoon',

'errorpagetitle'    => 'Fehler',
'returnto'          => 'Trüch to $1.',
'tagline'           => 'Vun {{SITENAME}}',
'help'              => 'Hülp',
'search'            => 'Söken',
'searchbutton'      => 'Söken',
'go'                => 'Gah',
'searcharticle'     => 'Los',
'history'           => 'Historie',
'history_short'     => 'Historie',
'updatedmarker'     => 'bearbeidt, in de Tiet sietdem ik toletzt dor weer',
'info_short'        => 'Informatschoon',
'printableversion'  => 'Druckversion',
'permalink'         => 'Duurlenk',
'print'             => 'Drucken',
'edit'              => 'Ännern',
'editthispage'      => 'Siet bearbeiden',
'delete'            => 'Wegsmieten',
'deletethispage'    => 'Disse Siet wegsmieten',
'undelete_short'    => '{{PLURAL:$1|ene Version|$1 Versionen}} wedderhalen',
'protect'           => 'Schulen',
'protectthispage'   => 'Siet schulen',
'unprotect'         => 'Freegeven',
'unprotectthispage' => 'Schuul opheben',
'newpage'           => 'Niege Siet',
'talkpage'          => 'Diskuschoon',
'specialpage'       => 'Spezialsiet',
'personaltools'     => 'Persönliche Warktüüch',
'postcomment'       => 'Kommentar hentofögen',
'articlepage'       => 'Artikel',
'talk'              => 'Diskuschoon',
'toolbox'           => 'Warktüüch',
'userpage'          => 'Brukersiet',
'projectpage'       => 'Meta-Text',
'imagepage'         => 'Bildsiet',
'mediawikipage'     => 'Systemnaricht ankieken',
'templatepage'      => 'Vörlaag ankieken',
'viewhelppage'      => 'Helpsiet ankieken',
'categorypage'      => 'Kategorie ankieken',
'viewtalkpage'      => 'Diskuschoon',
'otherlanguages'    => 'Annere Spraken',
'redirectedfrom'    => '(Wiederleiden vun $1)',
'redirectpagesub'   => 'Redirectsiet',
'lastmodifiedat'    => 'Disse Siet is toletzt üm $2, $1 ännert worrn.', # $1 date, $2 time
'viewcount'         => 'Disse Siet is $1 Maal opropen worrn.',
'protectedpage'     => 'Schulte Sieten',
'jumpto'            => 'Wesseln na:',
'jumptonavigation'  => 'Navigatschoon',
'jumptosearch'      => 'Söök',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'         => 'Över {{SITENAME}}',
'aboutpage'         => '{{ns:project}}:Över_{{SITENAME}}',
'bugreports'        => 'Kontakt',
'bugreportspage'    => '{{ns:project}}:Kontakt',
'copyright'         => 'Inholt is verfögbor ünner de $1.',
'copyrightpagename' => '{{SITENAME}} Copyright',
'copyrightpage'     => '{{ns:project}}:Lizenz',
'currentevents'     => 'Aktuell Schehn',
'currentevents-url' => '{{ns:project}}:Aktuell Schehn',
'disclaimers'       => 'Lizenzbestimmen',
'disclaimerpage'    => '{{ns:project}}:Lizenzbestimmen',
'edithelp'          => 'Bearbeidenshülp',
'edithelppage'      => '{{ns:project}}:Editeerhülp',
'faq'               => 'Faken stellte Fragen',
'faqpage'           => '{{ns:project}}:Faken stellte Fragen',
'helppage'          => '{{ns:help}}:Hülp',
'mainpage'          => 'Hööftsiet',
'portal'            => '{{SITENAME}}-Portal',
'portal-url'        => '{{ns:project}}:{{SITENAME}}-Portal',
'sitesupport'       => 'Spennen',

'badaccess' => 'Fehler bi de Rechten',

'versionrequired' => 'Version $1 vun MediaWiki nödig',

'ok'                  => 'OK',
'pagetitle'           => '$1 - {{SITENAME}}',
'retrievedfrom'       => 'Vun „$1“',
'youhavenewmessages'  => 'Du hest $1 ($2).',
'newmessageslink'     => 'Ne’e Narichten',
'newmessagesdifflink' => 'Ünnerscheed to vörher',
'editsection'         => 'bearbeiden',
'editold'             => 'bearbeiden',
'editsectionhint'     => 'Ännere Afsnitt: $1',
'toc'                 => 'Inholtsverteken',
'showtoc'             => 'wiesen',
'hidetoc'             => 'Nich wiesen',
'thisisdeleted'       => 'Ankieken oder weerholen vun $1?',
'viewdeleted'         => '$1 ankieken?',
'restorelink'         => '$1 löscht Bearbeidensvörgäng',

# Short words for each namespace, by default used in the 'article' tab in monobook
'nstab-main'      => 'Artikel',
'nstab-user'      => 'Siet vun den Bruker',
'nstab-media'     => 'Media',
'nstab-special'   => 'Spezial',
'nstab-project'   => 'Över',
'nstab-image'     => 'Bild',
'nstab-mediawiki' => 'Naricht',
'nstab-template'  => 'Vörlaag',
'nstab-help'      => 'Hülp',
'nstab-category'  => 'Kategorie',

# Main script and global functions
'nosuchaction'      => 'Disse Aktschoon gifft dat nich',
'nosuchactiontext'  => 'Disse Aktschoon warrt vun de MediaWiki-Software nich ünnerstütt',
'nosuchspecialpage' => 'Disse Spezialsiet gifft dat nich',
'nospecialpagetext' => 'Disse Spezialsiet warrt vun de MediaWiki-Software nich ünnerstütt',

# General errors
'error'                => 'Fehler',
'databaseerror'        => 'Fehler in de Datenbank',
'dberrortext'          => 'Dor weer en Syntaxfehler in de Datenbankaffraag.
De letzte Datenbankaffraag weer:

<blockquote><tt>$1</tt></blockquote>

ut de Funktschoon <tt>$2</tt>.
MySQL mell den Fehler <tt>$3: $4</tt>.',
'dberrortextcl'        => 'Dor weer en Syntaxfehler in de Datenbankaffraag.
De letzte Datenbankaffraag weer: $1 ut de Funktschoon <tt>$2</tt>.
MySQL mell den Fehler: <tt>$3: $4</tt>.',
'noconnect'            => 'De Software kunn keen Verbinnen to de Datenbank op $1 opnehmen',
'nodb'                 => 'De Software kunn de Datenbank $1 nich utwählen',
'cachederror'          => "Disse Siet is en Kopie ut'n Cache un is mööglicherwies nich aktuell.",
'laggedslavemode'      => 'Wohrschau: Disse Siet is villicht nich mehr op den ne’esten Stand.',
'readonly'             => 'Datenbank is sparrt',
'enterlockreason'      => 'Giff den Grund an, worüm de Datenbank sparrt warrn schall un taxeer, wo lang de Sparr duert',
'readonlytext'         => 'De Datenbank vun {{SITENAME}} is opstunns sparrt. Versöök dat later noch eenmal, duert meist nich lang, denn geiht dat wedder.

As Grund för de Sparr is angeven: $1',
'missingarticle'       => "De Text för de Siet '$1' kunn nich in de Datenbank funnen warrn. Dat is wohrschienlich en Fehler in de Software. Bitte mell dat an enen Administrater un giff ok den Sietennaam an.",
'internalerror'        => 'Internen Fehler',
'filecopyerror'        => "De Software kunn Datei '$1' nich no '$2' kopeern.",
'filerenameerror'      => "De Software kunn Datei '$1' nich no '$2' ümnömen.",
'filedeleteerror'      => "De Software kunn Datei '$1' nich löschen.",
'filenotfound'         => "De Software kunn Datei '$1' nich finnen.",
'unexpected'           => "Unvermodten Weert: '$1'='$2'.",
'formerror'            => 'Fehler: De Software kunn dat Formular nich verarbeiden',
'badarticleerror'      => 'Disse Aktschoon kann op disse Siet nich anwennt warrn.',
'cannotdelete'         => 'De Software kunn de spezifizeerte Siet nich löschen. (Mööglicherwies is de al vun en annern löscht worrn.)',
'badtitle'             => 'Ungülligen Titel',
'badtitletext'         => 'De Titel vun de födderte Siet weer ungüllig, leddig, oder en ungülligen Spraaklink vun en annern Wiki.',
'perfdisabled'         => 'Disse Funktschoon is wegen Överlast vun de Servers för enige Tied deaktiveert. Versöök dat doch twüschen 02:00 un 14:00 UTC noch eenmal<br />(Aktuelle Servertied: 21:06:12 UTC).',
'perfcached'           => 'Disse Daten kamen ut den Cache un sünd mööglicherwies nich aktuell:',
'wrong_wfQuery_params' => 'Falschen Parameter för wfQuery()<br />
Funktschoon: $1<br />
Query: $2',
'viewsource'           => 'Dokmentborn ankieken',
'viewsourcefor'        => 'för $1',
'protectedinterface'   => 'Op disse Siet staht Narichtentexte för dat System un de Siet is dorüm sparrt.',
'sqlhidden'            => '(SQL-Affraag versteken)',

# Login and logout pages
'logouttitle'                => 'Bruker-Afmellen',
'logouttext'                 => 'Du büst nu afmellt. Du kannst {{SITENAME}} nu anonym wiederbruken oder di ünner en annern Brukernaam wedder anmellen.',
'welcomecreation'            => '<h2>Willkomen, $1!</h2><p>Dien Brukerkonto is nu inricht.
Vergeet nich, dien [[Special:Preferences|Instellen]] antopassen.',
'loginpagetitle'             => 'Bruker-Anmellen',
'yourname'                   => 'Dien Brukernaam',
'yourpassword'               => 'Dien Passwoort',
'yourpasswordagain'          => 'Password nochmal ingeven',
'remembermypassword'         => 'Duersam inloggen',
'loginproblem'               => '<b>Dor weer en Problem mit dien Anmellen.</b><br />Versöök dat noch eenmal!',
'alreadyloggedin'            => '<strong>Bruker $1, du büst al anmellt!</strong><br />',
'login'                      => 'Anmellen',
'loginprompt'                => 'Üm di bi {{SITENAME}} antomellen, musst du Cookies anstellt hebben.',
'userlogin'                  => 'Nee Konto anleggen oder anmellen',
'logout'                     => 'Afmellen',
'userlogout'                 => 'Afmellen',
'notloggedin'                => 'Nich anmellt',
'nologin'                    => 'Wenn du noch keen Brukerkonto hest, denn kannst di anmellen: $1.',
'nologinlink'                => 'Brukerkonto inrichten',
'createaccount'              => 'Nieg Brukerkonto anleggen',
'gotaccount'                 => 'Hebbt Se al en Konto? $1.',
'createaccountmail'          => 'över E-Mail',
'badretype'                  => 'De beiden Passwöör stimmt nich övereen.',
'userexists'                 => 'Dissen Brukernaam is al vergeven. Bitte wähl en annern.',
'youremail'                  => 'Dien E-Mail (kene Plicht) *',
'username'                   => 'Brukernaam:',
'uid'                        => 'Bruker-ID:',
'yourrealname'               => 'Dien echten Naam (kene Plicht)',
'yourlanguage'               => 'Snittstellenspraak',
'yourvariant'                => 'Dien Spraak',
'yournick'                   => 'Dien Ökelnaam (för dat Ünnerschrieven)',
'badsig'                     => 'De Signatur is nich korrekt, kiek nochmal na de HTML-Tags.',
'email'                      => 'Nettbreef',
'loginerror'                 => 'Fehler bi dat Anmellen',
'nocookiesnew'               => 'De Brukertogang is anleggt, aver du büst nich inloggt. {{SITENAME}} bruukt för disse Funktschoon Cookies, aktiveer de Cookies un logg di denn mit dien nieg Brukernaam un den Password in.',
'nocookieslogin'             => '{{SITENAME}} bruukt Cookies för dat Inloggen vun de Bruker. Du hest Cookies deaktiveert, aktiveer de Cookies un versöök dat noch eenmal.',
'noname'                     => 'Du muttst en Brukernaam angeven.',
'loginsuccesstitle'          => 'Anmellen hett Spood',
'loginsuccess'               => 'Du büst nu as „$1“ bi {{SITENAME}} anmellt.',
'nosuchuser'                 => 'De Brukernaam „$1“ existeert nich.
Prööv de Schrievwies oder mell di as niegen Bruker an.',
'nosuchusershort'            => 'De Brukernaam „$1“ existeert nich. Prööv de Schrievwies.',
'nouserspecified'            => 'Du musst en Brukernaam angeven',
'wrongpassword'              => 'Dat Passwoort, wat du ingeven hest, is verkehrt. Kannst dat aver noch wedder versöken.',
'wrongpasswordempty'         => 'Dat ingevene Passwoort is leddig, versöök dat noch wedder.',
'mailmypassword'             => 'En nieg Password sennen',
'passwordremindertitle'      => '{{SITENAME}} Password',
'passwordremindertext'       => 'Een (IP-Adress $1) hett för en nee Passwoort to’n Anmellen bi {{SITENAME}} beden ($4).
Dat Passwoort för Bruker „$2“ is nu „$3“. Bitte mell di nu an un änner dien Passwoort.

Wenn du nich sülvst för en nee Passwoort beden hest, denn bruukst di wegen disse Naricht nich to kümmern un kannst dien oolt Passwoort wiederbruken.',
'noemail'                    => 'Bruker „$1“ hett kene E-Mail-Adress angeven.',
'passwordsent'               => 'En nieg Password is an de E-Mail-Adress vun Bruker „$1“ send worrn. Mell di an, wenn du dat Password kriegt hest.',
'mailerror'                  => 'Fehler bi dat Sennen vun de E-Mail: $1',
'acct_creation_throttle_hit' => 'Du hest al $1 Brukerkontos anleggt. Du kannst nich noch mehr anleggen.',
'emailconfirmlink'           => 'Nettbreef-Adress bestätigen',
'accountcreated'             => 'Brukerkonto inricht',

# Password reset dialog
'resetpass_header' => 'Passwoort trüchsetten',

# Edit page toolbar
'bold_sample'     => 'Fetten Text',
'bold_tip'        => 'Fetten Text',
'italic_sample'   => 'Kursiven Text',
'italic_tip'      => 'Kursiven Text',
'link_sample'     => 'Link-Text',
'link_tip'        => 'Internen Link',
'extlink_sample'  => 'http://www.bispeel.com Link-Text',
'extlink_tip'     => 'Externen Link (http:// is wichtig)',
'headline_sample' => 'Evene 2 Överschrift',
'headline_tip'    => 'Evene 2 Överschrift',
'math_sample'     => 'Formel hier infögen',
'math_tip'        => 'Mathematsche Formel (LaTeX)',
'nowiki_sample'   => 'Unformateerten Text hier infögen',
'nowiki_tip'      => 'Unformateerten Text',
'image_sample'    => 'Bispeel.jpg',
'image_tip'       => 'Bild-Verwies',
'media_sample'    => 'Bispeel.mp3',
'media_tip'       => 'Mediendatei-Verwies',
'sig_tip'         => 'Diene Signatur mit Tietstempel',
'hr_tip'          => 'Waagrechte Lien (sporsam bruken)',

# Edit pages
'summary'                => 'Tosamenfaten',
'subject'                => 'Bedrap',
'minoredit'              => 'Blots lütte Ännern.',
'watchthis'              => 'Op disse Siet oppassen',
'savearticle'            => 'Siet spiekern',
'preview'                => 'Vörschau',
'showpreview'            => 'Vörschau wiesen',
'showlivepreview'        => 'Live-Vörschau',
'showdiff'               => 'Ännern wiesen',
'blockedtitle'           => 'Bruker is blockt',
'blockedtext'            => 'Dien Brukernaam oder dien IP-Adress is vun $1 blockt worrn.
As Grund is angeven:

:$2

De Duer steiht in’t [[Special:Log/block|Logbook]]. Wenn du glöövst, dat Sparren weer unrecht, denn mell di bi een vun de [[{{MediaWiki:grouppage-sysop}}|Administraters]]. Geev bi Fragen jümmer ok dien IP-Adress ($3) oder de ID vun dien Block (#$5) mit an.',
'whitelistedittitle'     => 'üm de Siet to Bearbeiden is dat neudig anmellt to ween',
'whitelistedittext'      => 'Du musst di $1, dat du Sieden ännern kannst.',
'whitelistreadtitle'     => 'üm to Lesen is dat neudig anmellt to ween',
'whitelistreadtext'      => 'Du musst di [[{{ns:special}}:Userlogin|hier anmellen]], dat du Sieden lesen kannst.',
'whitelistacctitle'      => 'Du hest nich de Rechten en Konto antoleggen',
'whitelistacctext'       => 'Üm in dissen Wiki Kontos anleggen to könen muttst du di [[Special:Userlogin|hier anmellen]] un de neudigen Rechten hebben.',
'loginreqtitle'          => 'Anmellen nödig',
'loginreqlink'           => 'anmellen',
'loginreqpagetext'       => 'Du muttst di $1, üm annere Sieten ankieken to könen.',
'accmailtitle'           => 'Passwort is send worrn.',
'accmailtext'            => 'Dat Passwort vun $1 is an $2 send worrn.',
'newarticle'             => '(Nieg)',
'newarticletext'         => 'Hier den Text vun de niegen Siet indregen. Jümmer in ganze Sätz schrieven un kene Texten vun annern, de enen Oorheverrecht ünnerliggt, hierher kopeern.',
'anontalkpagetext'       => "---- ''Dit is de Diskuschoonssiet vun en nich anmellt Bruker. Wi mööt hier de numerische [[IP-Adress]]
verwennen, üm den Bruker to identifizeern. So en Adress kann vun verscheden Brukern bruukt warrn. Wenn du en anonymen Bruker büst un meenst,
dat disse Kommentaren nich an di richt sünd, denn [[Special:Userlogin|mell di doch an]], dormit dat Problem nich mehr besteiht.''",
'noarticletext'          => 'Dor is keen Text op disse Siet. [[{{ns:special}}:Search/{{PAGENAME}}|Na dissen Utdruck in annere Sieden söken]].',
'clearyourcache'         => "'''Denk doran:''' No den Spiekern muttst du dien Browser noch seggen, de niege Version to laden: '''Mozilla/Firefox:''' ''Strg-Shift-R'', '''IE:''' ''Strg-F5'', '''Safari:''' ''Cmd-Shift-R'', '''Konqueror:''' ''F5''.",
'usercssjsyoucanpreview' => '<strong>Tipp:</strong> Bruuk den Vörschau-Knoop, üm dien nieg CSS/JS vör dat Spiekern to testen.',
'usercsspreview'         => "'''Denk doran, dat du blots en Vörschau vun dien CSS ankiekst, dat is noch nich spiekert!'''",
'userjspreview'          => "'''Denk doran, dat du blots en Vörschau vun dien JS ankiekst, dat is noch nich spiekert!'''",
'updated'                => '(Ännert)',
'note'                   => '<strong>Wohrschau:</strong>',
'previewnote'            => 'Dit is blots en Vörschau, de Siet is noch nich spiekert!',
'previewconflict'        => 'Disse Vörschau wiest den Inholt vun dat Textfeld baven; so warrt de Siet utseihn, wenn du nu spiekerst.',
'editing'                => 'Ännern vun $1',
'editinguser'            => 'Ännern vun $1',
'editingsection'         => 'Ännern vun $1 (Afsatz)',
'editingcomment'         => 'Ännern vun $1 (Kommentar)',
'editconflict'           => 'Konflikt bi dat Bearbeiden: $1',
'explainconflict'        => 'En anner Bruker hett disse Siet ännert, no de Tied dat du anfungen hest, de Siet to bearbeiden.
Dat Textfeld baven wiest de aktuelle Siet.
Dat Textfeld nerrn wiest diene Ännern.
Föög diene Ännern in dat Textfeld baven in.

<b>Blots</b> de Text in dat Textfeld baven warrt spiekert, wenn du op Spiekern klickst!<br />',
'yourtext'               => 'Dien Text',
'storedversion'          => 'Spiekerte Version',
'nonunicodebrowser'      => '<strong>Wohrscho: Dien Browser ünnerstütt keen Unicode, wähl en annern Browser, wenn du en Siet ännern wullst.</strong>',
'editingold'             => '<strong>Wohrscho: Du bearbeidst en ole Version vun disse Siet.
Wenn du spiekerst, warrn alle niegeren Versionen överschrieven.</strong>',
'yourdiff'               => 'Ünnerscheed',
'copyrightwarning2'      => "Dien Text, de du op {{SITENAME}} stellen wullst, könnt vun elkeen ännert oder wegmaakt warrn.
Wenn du dat nich wullst, dröffst du dien Text hier nich apentlich maken.<br />

Du bestätigst ok, dat du den Text sülvst schreven hest oder ut en „Public Domain“-Born oder en annere fre'e Born kopeert hest (Kiek ok $1 för Details).
<strong>Kopeer kene Warken, de enen Oorheverrecht ünnerliggt, ahn Verlööv vun de Copyright-Inhebbers!</strong>",
'longpagewarning'        => '<strong>Wohrscho: Disse Siet is $1 KB groot; en poor Browser köönt Probleme hebben, Sieten to bearbeiden, de grötter as 32 KB sünd.
Bedenk of disse Siet vilicht in lüttere Afsnitten opdeelt warrn kann.</strong>',
'readonlywarning'        => '<strong>Wohrscho: De Datenbank is wiel dat Ännern vun de
Siet för Pleegarbeiden sparrt worrn, so dat du de Siet en Stoot nich
spiekern kannst. Seker di den Text un versöök later weer de Ännern to spiekern.</strong>',
'protectedpagewarning'   => '<strong>Wohrscho: Disse Siet is sparrt worrn, so dat blots
Bruker mit Sysop-Rechten doran arbeiden könnt.</strong>',
'template-protected'     => '(schuult)',
'template-semiprotected' => '(half-schuult)',

# Account creation failure
'cantcreateaccounttitle' => 'Brukerkonto kann nich anleggt warrn',

# History pages
'revhistory'       => 'Fröhere Versionen',
'viewpagelogs'     => 'Logbook för disse Siet',
'nohistory'        => 'Dor sünd kene fröheren Versionen vun disse Siet.',
'revnotfound'      => 'Kene fröheren Versionen funnen',
'revnotfoundtext'  => 'De Version vun disse Siet, no de du söökst, kunn nich funnen warrn. Prööv de URL vun disse Siet.',
'loadhist'         => 'Lade List mit freuhere Versionen',
'currentrev'       => 'Aktuelle Version',
'revisionasof'     => "Version vun'n $1",
'revision-info'    => '<div id="viewingold-warning" style="background: #ffbdbd; border: 1px solid #BB7979; font-weight: bold; padding: .5em 1em;">
Dit is en ole Version vun disse Siet, so as <span id="mw-revision-name">$2</span> de <span id="mw-revision-date">$1</span> ännert hett. De Version kann temlich stark vun de <a href="{{FULLURL:{{FULLPAGENAME}}}}" title="{{FULLPAGENAME}}">aktuelle Version</a> afwieken.
</div>',
'previousrevision' => 'Nächstöllere Version→',
'nextrevision'     => '←Nächstjüngere Version',
'cur'              => 'Aktuell',
'next'             => 'Tokamen',
'last'             => 'Letzte',
'orig'             => 'Original',
'histlegend'       => "Ünnerscheed-Utwahl: De Boxen vun de wünschten
Versionen markeern un 'Enter' drücken oder den Knoop nerrn klicken/alt-v.<br />
Legende:
(Aktuell) = Ünnerscheed to de aktuelle Version,
(Letzte) = Ünnerscheed to de vörige Version,
L = Lütte Ännern",

# Diffs
'difference'                => '(Ünnerscheed twischen de Versionen)',
'loadingrev'                => 'laad Versionen üm Ünnerscheden to wiesen',
'lineno'                    => 'Reeg $1:',
'editcurrent'               => 'De aktuelle Version vun disse Siet bearbeiden',
'selectnewerversionfordiff' => 'En niegere Version för en Vergliek utwählen',
'selectolderversionfordiff' => 'En öllere Version för en Vergliek utwählen',
'compareselectedversions'   => 'Wählte Versionen verglieken',
'editundo'                  => 'rutnehmen',

# Search results
'searchresults'         => 'Söökresultaten',
'searchresulttext'      => 'För mehr Informatschonen över {{SITENAME}}, kiek [[{{MediaWiki:helppage}}|{{SITENAME}} dörsöken]].',
'searchsubtitle'        => 'För de Söökanfraag „[[:$1]]“',
'searchsubtitleinvalid' => 'För de Söökanfraag „$1“',
'badquery'              => 'Falsche Söökanfraag',
'badquerytext'          => "De Söökanfraag kunn nich verarbeid warrn.
Sachts hest du versöökt, en Word to söken, dat kötter as twee Bookstaven is.
Dit funktschoneert in'n Momang noch nich.
Mööglicherwies hest du ok de Anfraag falsch formuleert, to'n Bispeel 'Lohn un un Stüern'. Versöök en anners formuleerte Anfraag.",
'matchtotals'           => 'De Anfraag „$1“ stimmt mit $2 Sietenöverschriften un den Text vun $3 Sieten överein.',
'noexactmatch'          => 'Dor existeert kene Siet mit dissen Naam. Versöök de Vulltextsöök oder legg de Siet [[:$1|nieg]] an.',
'titlematches'          => 'Övereenstimmen mit Överschriften',
'notitlematches'        => 'Kene Övereenstimmen',
'textmatches'           => 'Övereenstimmen mit Texten',
'notextmatches'         => 'Kene Övereenstimmen',
'prevn'                 => 'vörige $1',
'nextn'                 => 'tokamen $1',
'viewprevnext'          => 'Wies ($1) ($2) ($3).',
'showingresults'        => 'Hier sünd <b>$1</b> Resultaten, anfungen mit #<b>$2</b>.',
'showingresultsnum'     => 'Hier sünd <b>$3</b> Resultaten, anfungen mit #<b>$2</b>.',
'nonefound'             => '<strong>Henwies</strong>:
Söökanfragen ahn Spood hebbt faken de Oorsaak, dat no kotte oder gemeene Wöör söökt warrt, de nich indizeert sünd.',
'powersearch'           => 'Söken',
'powersearchtext'       => '
Söök in Naamrüüm:<br />


$1<br />
$2 Wies ok Wiederleiden   Söök no $3 $9',
'searchdisabled'        => '<p>De Vulltextsöök is wegen Överlast en Stoot deaktiveert. In disse Tied kannst du disse Google-Söök verwennen,
de aver nich jümmer den aktuellsten Stand weerspegelt.<p>',
'blanknamespace'        => '(Hööft-)',

# Preferences page
'preferences'              => 'Instellen',
'prefsnologin'             => 'Nich anmellt',
'prefsnologintext'         => 'Du muttst [[Special:Userlogin|anmellt]] ween, üm dien Instellen to ännern.',
'prefsreset'               => 'Instellen sünd op Standard trüchsett.',
'qbsettings'               => 'Sietenliest',
'qbsettings-none'          => 'Keen',
'qbsettings-fixedleft'     => 'Links, fast',
'qbsettings-fixedright'    => 'Rechts, fast',
'qbsettings-floatingleft'  => 'Links, sweven',
'qbsettings-floatingright' => 'Rechts, sweven',
'changepassword'           => 'Passwoort ännern',
'skin'                     => 'Utsehn vun de Steed',
'math'                     => 'TeX',
'dateformat'               => 'Datumsformat',
'datetime'                 => 'Datum un Tiet',
'math_failure'             => 'Parser-Fehler',
'math_unknown_error'       => 'Unbekannten Fehler',
'math_unknown_function'    => 'Unbekannte Funktschoon',
'math_lexing_error'        => "'Lexing'-Fehler",
'math_syntax_error'        => 'Syntaxfehler',
'math_image_error'         => 'dat Konverteern no PNG hett kenen Spood.',
'math_bad_tmpdir'          => 'Kann dat Temporärverteken för mathematsche Formeln nich anleggen oder beschrieven.',
'math_bad_output'          => 'Kann dat Teelverteken för mathematsche Formeln nich anleggen oder beschrieven.',
'math_notexvc'             => 'Dat texvc-Programm kann nich funnen warrn. Kiek ok math/README.',
'prefs-personal'           => 'Brukerdaten',
'prefs-rc'                 => 'Letzte Ännern un Wiesen vun kotte Sieten',
'prefs-watchlist'          => 'Oppasslist',
'prefs-misc'               => 'Verscheden Instellen',
'saveprefs'                => 'Instellen spiekern',
'resetprefs'               => 'Instellen trüchsetten',
'oldpassword'              => 'Oolt Passwoort:',
'newpassword'              => 'Nee Passwoort',
'retypenew'                => 'Nieg Password (nochmal)',
'textboxsize'              => 'Textfeld-Grött',
'rows'                     => 'Regen',
'columns'                  => 'Spalten',
'searchresultshead'        => 'Söökresultaten',
'resultsperpage'           => 'Treffer pro Siet',
'contextlines'             => 'Lienen pro Treffer',
'contextchars'             => 'Teken pro Lien',
'recentchangescount'       => 'Antall „Letzte Ännern“',
'savedprefs'               => 'Dien Instellen sünd spiekert.',
'timezonelegend'           => 'Tietrebeet',
'timezonetext'             => 'Giff de Antall vun de Stünnen an, de twüschen dien Tiedrebeet un UTC liggen.',
'localtime'                => 'Oortstied',
'timezoneoffset'           => 'Ünnerscheed',
'servertime'               => 'Aktuelle Tied op den Server',
'guesstimezone'            => 'Ut den Browser övernehmen',
'allowemail'               => 'Nettbreven vun annere Brukers annehmen',
'defaultns'                => 'In disse Naamrüüm schall standardmatig söökt warrn:',
'files'                    => 'Datein',

# User rights
'editusergroup'            => 'Brukergruppen bearbeiden',
'userrights-editusergroup' => 'Brukergruppen ännern',
'saveusergroups'           => 'Brukergruppen spiekern',

# Groups
'group'            => 'Grupp:',
'group-bot'        => 'Bots',
'group-bureaucrat' => 'Bürokraten',

'group-bot-member'        => 'Bot',
'group-bureaucrat-member' => 'Bürokraat',

'grouppage-bot' => '{{ns:project}}:Bots',

# User rights log
'rightslog'  => 'Brukerrechten-Logbook',
'rightsnone' => '(kene)',

# Recent changes
'nchanges'          => '$1 {{PLURAL:$1|Ännern|Ännern}}',
'recentchanges'     => 'Niegest Ännern',
'recentchangestext' => '
Disse Siet warrt wiel dat Laden automatsch aktualiseert. Wiest warrn Sieten, de toletzt bearbeid worrn sünd, dorto de Tied un de Naam vun de Autor.',
'rcnote'            => "Hier sünd de letzten '''$1''' Ännern vun de letzten {{PLURAL:$2|Dag|'''$2''' Daag}} (Stand $3). ('''N''' - Ne’e Sieden; '''L''' - Lütte Ännern)",
'rcnotefrom'        => 'Dit sünd de Ännern siet <b>$2</b> (bet to <b>$1</b> wiest).',
'rclistfrom'        => 'Wies niege Ännern siet $1',
'rcshowhidebots'    => '$1 Bots',
'rcshowhideliu'     => '$1 inloggte Brukers',
'rcshowhideanons'   => '$1 anonyme Brukers',
'rcshowhidepatr'    => '$1 nakekene Ännern',
'rcshowhidemine'    => '$1 miene Ännern',
'rclinks'           => "Wies de letzten '''$1''' Ännern vun de letzten '''$2''' Daag. ('''N''' - Ne’e Sieden; '''L''' - Lütte Ännern)<br />$3",
'diff'              => 'Ünnerscheed',
'hist'              => 'Versionen',
'hide'              => 'Nich wiesen',
'show'              => 'Wiesen',
'minoreditletter'   => 'L',
'newpageletter'     => 'N',
'boteditletter'     => 'b',

# Recent changes linked
'recentchangeslinked' => 'Ännern an lenkte Sieden',

# Upload
'upload'             => 'Hoochladen',
'uploadbtn'          => 'Datei hoochladen',
'reupload'           => 'Nieg hoochladen',
'reuploaddesc'       => 'Trüch to de Hoochladen-Siet.',
'uploadnologin'      => 'Nich anmellt',
'uploadnologintext'  => 'Du muttst [[Spezial:Userlogin|anmellt ween]] üm Datein hoochladen to könen.',
'uploaderror'        => 'Fehler bi dat Hoochladen',
'uploadtext'         => "
Üm hoochladene Biller to söken un antokieken,
geih to de [[Special:Imagelist|List vun hoochladene Biller]].

Bruuk dat Formular, üm niege Biller hoochtoladen un disse in Sieten to bruken.
In de mehrsten Browser warrt en „Durchsuchen“-Feld wiest, dat en Standard-Dateidialog apent.
Wähl de Datei ut, de du hoochladen wullst. De Dateinaam warrt denn in dat Textfeld wiest.
Bestätig dann den Copyright-Henwies.
Toletzt muttst du den „Hoochladen“-Knopp klicken.
Dat kann en Stoot duern, sünnerlich bi en langsamen Internet-Verbinnen.

För Fotos is dat JPEG-Format, för Grafiken un Symbolen dat PNG-Format best.
Üm en Bild in en Siet to bruken, schriev an Stell vun dat Bild
'''[[Image:datei.jpg]]''' oder
'''[[Image:datei.jpg|Beschrieven]]'''.

Denk doran, dat, lieks as bi de annern Sieten, annere Bruker dien Datein löschen oder ännern könen.',
'uploadlog'                => 'Datei-Logbook',
'uploadlogpage'     => 'Datei-Logbook',
'uploadlogpagetext' => 'Hier is de List vun de letzten hoochladenen Datein.
Alle Tieden sünd UTC.",
'uploadlog'          => 'Hoochlade-Logbook',
'uploadlogpage'      => 'Hoochlade-Logbook',
'uploadlogpagetext'  => 'Ünnen steiht de List vun de ne’esten hoochladenen Datein.',
'filename'           => 'Dateinaam',
'filedesc'           => 'Beschrieven',
'filestatus'         => 'Copyright-Status',
'filesource'         => 'Born',
'uploadedfiles'      => 'Hoochladene Datein',
'minlength'          => 'Bilddatein möten tominnst dree Bookstaven hebben.',
'badfilename'        => 'De Bildnaam is na „$1“ ännert worrn.',
'emptyfile'          => 'De hoochladene Datei is leddig. De Grund kann en Tippfehler in de Dateinaam ween. Kontrolleer, of du de Datei redig hoochladen wullst.',
'fileexists'         => 'En Datei mit dissen Naam existeert al, prööv $1, wenn du di nich seker büst of du dat ännern wullst.',
'successfulupload'   => 'Datei hoochladen hett Spood',
'fileuploaded'       => 'Dat Hoochladen vun de Datei „$1“ hett Spood.
Disse ($2) Link föhrt to de Bildsiet. Dor kann indregen warrn, woneem dat Bild kummt, welkeen dat wann mookt hett un wenn neudig, welkeen Copyright-Status dat Bild hett.',
'uploadwarning'      => 'Wohrscho',
'savefile'           => 'Datei spiekern',
'uploadedimage'      => '„$1“ hoochladen',
'uploaddisabled'     => 'Dat Hoochladen is deaktiveert.',
'uploaddisabledtext' => 'Op dit Wiki is dat Hoochladen vun Datein utschalt.',
'uploadcorrupt'      => 'De Datei is korrupt oder hett en falsch Ennen. Datei pröven un nieg hoochladen.',
'uploadvirus'        => 'In de Datei stickt en Virus! Mehr: $1',
'sourcefilename'     => 'Dateinaam op dien Reekner',
'destfilename'       => 'Dateinaam, so as dat hier spiekert warrn schall',
'watchthisupload'    => 'Op disse Siet oppassen',

'upload-proto-error' => 'Verkehrt Protokoll',
'upload-file-error'  => 'Internen Fehler',
'upload-misc-error'  => 'Unbekannt Fehler bi dat Hoochladen',

'nolicense' => 'nix utwählt',

# Image list
'imagelist'           => 'Billerlist',
'imagelisttext'       => 'Hier is en List vun $1 Biller, sorteert $2.',
'getimagelist'        => 'Billerlist laden',
'ilsubmit'            => 'Söök',
'showlast'            => 'Wies de letzten $1 Biller, sorteert $2.',
'byname'              => 'na Naam',
'bydate'              => 'na Datum',
'bysize'              => 'na Grött',
'imgdelete'           => 'Löschen',
'imgdesc'             => 'Beschrieven',
'imgfile'             => 'Datei',
'imglegend'           => 'Legende: (Beschrieven) = Wies/Änner Bildbeschrieven.',
'imghistory'          => 'Bild-Versionen',
'revertimg'           => 'trüchsetten',
'deleteimg'           => 'Löschen',
'deleteimgcompletely' => 'Löschen',
'imghistlegend'       => 'Legende: (cur) = Dit is dat aktuelle Bild, (Löschen) = lösch
disse ole Version, (Trüchsetten) = bruuk weer disse ole Version.',
'imagelinks'          => 'Bildverwiesen',
'linkstoimage'        => 'Disse Sieden bruukt dit Bild:',
'nolinkstoimage'      => 'Kene Siet bruukt dat Bild.',
'sharedupload'        => 'Disse Datei is en Datei, de mööglicherwies ok vun annere Wikis bruukt warrt.',
'noimage-linktext'    => 'Hoochladen',
'imagelist_date'      => 'Datum',
'imagelist_name'      => 'Naam',
'imagelist_user'      => 'Bruker',
'imagelist_size'      => 'Grött (Bytes)',

# MIME search
'mimetype' => 'MIME-Typ:',

# Unwatched pages
'unwatchedpages' => 'Sieden, de op kene Oppasslist staht',

# Unused templates
'unusedtemplates' => 'nich bruukte Vörlagen',

# Statistics
'statistics'    => 'Statistik',
'sitestats'     => 'Sietenstatistik',
'userstats'     => 'Brukerstatistik',
'sitestatstext' => "Dat gifft allens tosamen {{PLURAL:$1|ene Siet|'''$1''' Sieden}} in de Datenbank.
Dat slött Diskuschoonsieden, Sieden över {{SITENAME}}, bannig korte Sieden, Wiederleiden un annere Sieden in, de nich as richtige Sieden gellen köönt.
Disse utnahmen, gifft dat {{PLURAL:$2|ene Siet, de as Artikel gellen kann|'''$2''' Sieden, de as Artikels gellen köönt}}.

'''$8''' hoochladene {{PLURAL:$8|Datei|Datein}} gifft dat.

De Lüüd hebbt {{PLURAL:$3|ene Siet|'''$3'''× Sieden}} opropen, un {{PLURAL:$4|ene Siet ännert|'''$4'''× Sieden ännert}}.
Dat heet, jede Siet is '''$5''' Maal ännert un '''$6''' maal ankeken worrn.

De List, mit de Opgaven, de de Software noch maken mutt, hett {{PLURAL:$7|een Indrag|'''$7''' Indrääg}}.",
'userstatstext' => "Dat gifft {{PLURAL:$1|'''een''' anmellt Bruker|'''$1''' anmellt Brukers}}.
Dorvun {{PLURAL:$2|hett '''een'''|hebbt '''$2'''}} {{PLURAL:$1||($4 %)}} $5-Rechten (kiek $3).",

'disambiguations'     => 'Mehrdüdige Begrepen',
'disambiguationspage' => "Op disse Siet schöölt all Vörlagen un Redirects na sone Vörlagen indragen warrn, de bi Begrepen staht, de mehrdüdig sünd un den Leser op de richtige Siet wiederwiest. Op Sieden mit disse Vörlagen schall keen Lenk wiesen. Lenken, de dor doch op wiest, warrt denn op de Siet [[Special:Disambiguations]] optellt.

<small>Vun disse Siet warrt blots de Lenken na den Vörlagen-Naamruum utleest ('''<nowiki>[[</nowiki>{{ns:10}}<nowiki>:...]]</nowiki>''') un allens annere kann geern ännert warrn.</small>

* [[{{ns:10}}:mehrdüdig Begreep]]",

'doubleredirects'     => 'Dubbelte Wiederleiden',
'doubleredirectstext' => '<b>Wohrscho:</b> Disse List kann „falsche Positive“ bargen.
Dat passeert denn, wenn en Wiederleiden blangen de Wiederleiden-Verwies noch mehr Text mit annere Verwiesen hett.
De schallen denn löscht warrn. Elk Reeg wiest de eerste un tweete Wiederleiden un de eerste Reeg Text ut de Siet,
to den vun den tweeten Wiederleiden wiest warrt, un to den de eerste Wiederleiden mehrst wiesen schall.',

'brokenredirects'     => 'Kaputte Wiederleiden',
'brokenredirectstext' => 'Disse Wiederleiden wiesen to en Siet, de nich existeert',

# Miscellaneous special pages
'nbytes'                  => '$1 Bytes',
'ncategories'             => '$1 {{PLURAL:$1|Kategorie|Kategorien}}',
'nlinks'                  => '$1 Verwies',
'nviews'                  => '$1 Affragen',
'specialpage-empty'       => 'Disse Siet is leddig.',
'lonelypages'             => 'Weetsieden',
'uncategorizedpages'      => 'Sieden ahn Kategorie',
'uncategorizedcategories' => 'Kategorien ahn Kategorie',
'uncategorizedimages'     => 'Unkategoriseerte Biller',
'unusedcategories'        => 'Kategorien ahn insorteerte Artikels oder Ünnerkategorien',
'unusedimages'            => 'Weetbiller',
'popularpages'            => 'Faken opropene Sieten',
'wantedcategories'        => 'Kategorien, de veel bruukt warrt, aver noch keen Text hebbt (nich anleggt sünd)',
'wantedpages'             => 'Wünschte Sieten',
'mostlinked'              => 'Sieden, op de vele Lenken wiest',
'mostcategories'          => 'Artikels mit vele Kategorien',
'mostimages'              => 'Biller, de veel bruukt warrt',
'allpages'                => 'Alle Sieden',
'randompage'              => 'Tofällige Siet',
'shortpages'              => 'Korte Sieden',
'longpages'               => 'Lange Sieden',
'deadendpages'            => 'Sackstraatsieten',
'protectedpages'          => 'Schuulte Sieden',
'protectedpagestext'      => 'Disse Sieden sünd vör dat Schuven oder Ännern schuult',
'protectedpagesempty'     => 'No pages are currently protected',
'listusers'               => 'Brukerlist',
'specialpages'            => 'Sünnerliche Sieden',
'spheading'               => 'Spezialsieten för alle Bruker',
'rclsub'                  => '(op Artikel vun „$1“)',
'newpages'                => 'Ne’e Sieden',
'newpages-username'       => 'Brukernaam:',
'ancientpages'            => 'Öllste Sieden',
'intl'                    => 'Interwiki-Links',
'move'                    => 'Schuven',
'movethispage'            => 'Siet schuven',
'unusedimagestext'        => 'Denk doran, dat annere Wikis mööglicherwies en poor vun disse Biller bruken.',

# Book sources
'booksources'      => 'Bookhannel',

'categoriespagetext' => 'In dit Wiki gifft dat disse Kategorien:',
'data'               => 'Daten',
'groups'             => 'Brukergruppen',
'alphaindexline'     => '$1 bet $2',

# Special:Log
'specialloguserlabel'  => 'Bruker:',
'speciallogtitlelabel' => 'Titel:',
'log'                  => 'Logböker',
'alllogstext'          => 'Kombineerte Ansicht vun all Logböker bi {{SITENAME}}.
Du kannst de List körter maken, wenn du den Logbook-Typ, den Brukernaam oder de Siet angiffst.',

# Special:Allpages
'nextpage'       => 'tokamen Siet ($1)',
'allpagesfrom'   => 'Sieden wiesen, de mit disse Bookstaven anfangt:',
'allarticles'    => 'Alle Artikels',
'allinnamespace' => 'Alle Sieten ($1 Naamruum)',
'allpagesprev'   => 'vörig',
'allpagesnext'   => 'tokamen',
'allpagessubmit' => 'Los',

# Special:Listusers
'listusersfrom' => 'Wies de Brukers, de anfangt mit:',

# E-mail user
'mailnologin'     => 'Du büst nich anmellt.',
'mailnologintext' => 'Du muttst [[Spezial:Userlogin|anmellt ween]] un en güllige E-Mail-Adress hebben, dormit du en annern Bruker en E-Mail sennen kannst.',
'emailuser'       => 'E-Mail an dissen Bruker',
'emailpage'       => 'E-Mail an Bruker',
'emailpagetext'   => 'Wenn disse Bruker en güllige E-Mail-Adress angeven hett, kannst du em mit den nerrn stahn Formular en E-Mail sennen. As Afsenner warrt de E-Mail-Adress ut dien Instellen indregen, dormit de Bruker di antern kann.',
'usermailererror' => 'Dat Mail-Objekt hett en Fehler trüchgeven:',
'defemailsubject' => '{{SITENAME}} E-Mail',
'noemailtitle'    => 'Kene E-Mail-Adress',
'noemailtext'     => 'Disse Bruker hett kene güllige E-Mail-Adress angeven, oder will kene E-Mail vun annere Bruker sennt kriegen.',
'emailfrom'       => 'Vun',
'emailto'         => 'An',
'emailsubject'    => 'Bedrap',
'emailmessage'    => 'Noricht',
'emailsend'       => 'Sennen',
'emailccsubject'  => 'Kopie vun diene Naricht an $1: $2',
'emailsent'       => 'E-Mail afsennt',
'emailsenttext'   => 'Dien E-Mail is afsennt worrn.',

# Watchlist
'watchlist'            => 'Mien Oppasslist',
'mywatchlist'            => 'Mien Oppasslist',
'nowatchlist'          => 'Du hest kene Indreeg op dien Oppasslist.',
'clearwatchlist'       => 'Oppasslist lerrig maken',
'watchlistcleartext'   => 'Würklich rutnehmen?',
'watchlistclearbutton' => 'Oppasslist leddig maken',
'watchlistcleardone'   => 'Diene Oppasslist is nu leddig maakt. {{PLURAL:$1|Ene Siet|$1 Sieden}} rutnahmen.',
'watchnologin'         => 'Du büst nich anmellt',
'watchnologintext'     => 'Du muttst [[Spezial:Userlogin|anmellt]] ween, wenn du dien Oppasslist ännern willst.',
'addedwatch'           => 'To de Oppasslist toföögt',
'addedwatchtext'       => 'De Siet „$1“ is to diene [[Special:Watchlist|Oppasslist]] tofögt worrn.
Ännern, de in Tokumst an disse Siet un an de tohörige Diskuschoonssiet maakt warrt, sünd dor op list un de Siet is op de [[Special:Recentchanges|List vun de letzten Ännern]] fett markt. Wenn du de Siet nich mehr op diene Oppasslist hebben willst, klick op „Nich mehr oppassen“ in de Linklist.',
'removedwatch'         => 'De Siet is nich mehr op de Oppasslist',
'removedwatchtext'     => 'De Siet „$1“ is nich mehr op de Oppasslist.',
'watch'                => 'Oppassen',
'watchthispage'        => 'Op disse Siet oppassen',
'unwatch'              => 'nich mehr oppassen',
'unwatchthispage'      => 'Nich mehr oppassen',
'notanarticle'         => 'Keen Artikel',
'watchnochange'        => 'Kene Siet, op de du oppasst, is in den wiesten Tiedruum bearbeid worrn.',
'watchdetails'         => '($1 Sieten sünd op de Oppasslist (ahn Diskuschoonssieten);
$2 Sieten werrn in de instellte Tied bearbeid;
$3... [$4 komplette List wiesen un bearbeiden].)',
'watchmethod-recent'   => 'letzte Ännern no Oppasslist pröven',
'watchmethod-list'     => 'Oppasslist no letzte Ännern pröven',
'removechecked'        => 'Markeerte Indreeg löschen',
'watchlistcontains'    => 'Dien Oppasslist bargt $1 Sieten.',
'watcheditlist'        => "Hier is ene alphabetsche List vun de Sieten op de du oppasst. Markeer de Sieten, de vun de Oppasslist löscht warrn schallt un klick den 'markeerte Indreeg löschen'-Knoop.",
'removingchecked'      => 'Indreeg warrt vun de Oppasslist löscht...',
'couldntremove'        => "De Indrag '$1' kann nich löscht warrn...",
'iteminvalidname'      => "Problem mit den Indrag '$1', ungülligen Naam...",
'wlnote'               => "Ünnen staht de letzten Ännern vun de {{PLURAL:$2|letzte Stünn|letzten '''$2''' Stünnen}}.",
'wlshowlast'           => 'Wies de letzten $1 Stünnen $2 Daag $3',
'wlsaved'              => 'Dit is en spiekerte Version vun dien Oppasslist.',
'wldone'               => 'Trech.',

'enotif_newpagetext' => 'Dit is en ne’e Siet.',

# Delete/protect/revert
'deletepage'           => 'Siet löschen',
'confirm'              => 'Bestätigen',
'excontent'            => 'Olen Inholt: ‚$1‘',
'exbeforeblank'        => 'Inholt vör dat Leddigmaken vun de Siet: ‚$1‘',
'exblank'              => 'Siet weer leddig',
'confirmdelete'        => 'Löschen bestätigen',
'deletesub'            => '(Lösche „$1“)',
'historywarning'       => 'Wohrscho: De Siet, de du versöökst to löschen, hett en Versionshistorie:',
'confirmdeletetext'    => 'Du büst dorbi, en Siet oder en Bild un alle ölleren Versionen duersam ut de Datenbank to löschen.
Segg to, dat du över de Folgen Bescheed weetst un dat du in Övereenstimmen mit uns [[{{MediaWiki:policy-url}}|Leidlienen]] hannelst.',
'actioncomplete'       => 'Aktschoon beennt',
'deletedtext'          => '„$1“ is löscht.
In $2 kannst du en List vun de letzten Löschen finnen.',
'deletedarticle'       => '„$1“ löscht',
'dellogpage'           => 'Lösch-Logbook',
'dellogpagetext'       => 'Hier is en List vun de letzten Löschen (UTC).',
'deletionlog'          => 'Lösch-Logbook',
'reverted'             => 'Op en ole Version trüchsett',
'deletecomment'        => 'Grund för dat Löschen',
'imagereverted'        => 'Op en ole Version trüchsett.',
'rollback'             => 'Trüchnahm vun de Ännern',
'rollback_short'       => 'Trüchnehmen',
'rollbacklink'         => 'Trüchnehmen',
'rollbackfailed'       => 'Trüchnahm hett kenen Spood',
'cantrollback'         => 'De Ännern kann nich trüchnahmen warrn; de letzte Autor is de eenzige.',
'alreadyrolled'        => 'Dat Trüchnehmen vun de Ännern an de Siet [[:$1]] vun [[User:$2|$2]]
([[User_talk:$2|Diskuschoonssiet]]) is nich mööglich, vun wegen dat dor en annere Ännern oder Trüchnahm ween is.

De letzte Ännern is vun [[User:$3|$3]]
([[User talk:$3|Diskuschoon]])',
'editcomment'          => 'De Ännerkommentar weer: <i>$1</i>.', # only shown if there is an edit comment
'revertpage'           => 'Ännern vun [[Special:Contributions/$2|$2]] rut un de Version vun [[{{ns:user}}:$1]] wedderhaalt',
'protectlogpage'       => 'Sietenschuul-Logbook',
'protectlogtext'       => 'Dit is en List vun de blockten Sieten. Kiek [[Special:Protectedpages|Schulte Sieten]] för mehr Informatschonen.',
'protectedarticle'     => 'Siet $1 schuult',
'unprotectedarticle'   => 'Siet $1 freegeven',
'protectsub'           => '(Sparren vun „$1“)',
'confirmprotect'       => 'Sparr bestätigen',
'protectcomment'       => 'Grund för de Sparr',
'unprotectsub'         => '(Beennen vun de Sparr vun „$1“)',

# Undelete
'undelete'          => 'Löschte Siet weerholen',
'undeletepage'      => 'Löschte Sieten weerholen',
'undeletepagetext'  => 'Disse Sieten sünd löscht worrn, aver jümmer noch
spiekert un könnt weerholt warrn.',
'undeleterevisions' => '$1 Versionen archiveert',
'undeletehistory'   => 'Wenn du disse Siet weerholst, warrt ok alle olen Versionen weerholt. Wenn siet dat Löschen en nieg Siet mit lieken
Naam schreven worrn is, warrt de weerholten Versionen as ole Versionen vun disse Siet wiest.',
'undeletebtn'       => 'Weerholen!',
'undeletedarticle'  => '„$1“ weerholt',

# Namespace form on various pages
'namespace' => 'Naamruum:',

# Contributions
'contributions' => 'Bidrääg vun den Bruker',
'mycontris'     => 'Mien Arbeid',
'contribsub2'    => 'För $1 ($2)',
'nocontribs'    => 'Kene Ännern för disse Kriterien funnen.',
'ucnote'        => 'Dit sünd de letzten <b>$1</b> Bidreeg vun de Bruker in de letzten <b>$2</b> Doog.',
'uclinks'       => 'Wies de letzten $1 Bidreeg; wies de letzten $2 Daag.',
'uctop'         => ' (aktuell)',

'sp-contributions-blocklog' => 'Sparr-Logbook',

# What links here
'whatlinkshere' => 'Wat wiest na disse Siet hen',
'notargettitle' => 'Kene Siet angeven',
'notargettext'  => 'Du hest nich angeven, op welke Siet du disse Funktschoon anwennen willst.',
'linklistsub'   => '(List vun de Verwiesen)',
'linkshere'     => 'Disse Sieden wiest hierher:',
'nolinkshere'   => 'Kene Siet wiest hierher.',
'isredirect'    => 'Wiederleiden',

# Block/unblock
'blockip'                  => 'IP-Adress blocken',
'blockiptext'              => 'Bruuk dat Formular, üm en IP-Adress to blocken.
Dit schall blots maakt warrn, üm Vandalismus to vermasseln, aver jümmer in Övereenstimmen mit uns [[{{MediaWiki:policy-url}}|Leidlienen]].
Ok den Grund för dat Blocken indregen.',
'ipaddress'                => 'IP-Adress',
'ipbexpiry'                => 'Aflöptied',
'ipbreason'                => 'Grund',
'ipbsubmit'                => 'Adress blocken',
'badipaddress'             => 'De IP-Adress hett en falsch Format.',
'blockipsuccesssub'        => 'Blocken hett Spood',
'blockipsuccesstext'       => 'De IP-Adress „$1“ is nu blockt.

<br />Op de [[Special:Ipblocklist|IP-Blocklist]] is en List vun alle Blocks to finnen.',
'unblockip'                => 'IP-Adress freegeven',
'unblockiptext'            => 'Bruuk dat Formular, üm en blockte IP-Adress freetogeven.',
'ipusubmit'                => 'Disse Adress freegeven',
'ipblocklist'              => 'List vun blockte IP-Adressen',
'blocklistline'            => '$1, $2 hett $3 blockt ($4)',
'blocklink'                => 'blocken',
'unblocklink'              => 'freegeven',
'contribslink'             => 'Bidrääg',
'autoblocker'              => 'Automatisch Block, vun wegen dat du en IP-Adress bruukst mit „$1“. Grund: „$2“.',
'blocklogpage'             => 'Brukerblock-Logbook',
'blocklogentry'            => 'block [[User:$1]] - ([[Special:Contributions/$1|Bidreeg]]) för en Tiedruum vun: $2',
'blocklogtext'             => 'Dit is en Logbook över Blocks un Freegaven vun Brukern. Automatisch blockte IP-Adressen sünd nich opföhrt.
Kiek [[Special:Ipblocklist|IP-Blocklist]] för en List vun den blockten Brukern.',
'unblocklogentry'          => 'Block vun [[User:$1]] ophoven',
'block-log-flags-anononly' => 'blots anonyme Brukers',
'range_block_disabled'     => 'De Mööglichkeit, ganze Adressrüüm to sparren, is nich aktiveert.',
'ipb_expiry_invalid'       => 'De angeven Aflöptied is ungüllig.',
'ipb_already_blocked'      => '„$1“ is al blockt',
'ip_range_invalid'         => 'Ungüllig IP-Addressrebeet.',
'proxyblocker'             => 'Proxyblocker',
'proxyblockreason'         => 'Dien IP-Adress is blockt, vun wegen dat se en apenen Proxy is.
Kontakteer dien Provider oder diene Systemtechnik un informeer se över dat möögliche Sekerheitsproblem.',
'proxyblocksuccess'        => 'Fardig.',

# Developer tools
'lockdb'              => 'Datenbank sparren',
'unlockdb'            => 'Datenbank freegeven',
'lockdbtext'          => 'Mit de Sparr vun de Datenbank warrt alle Ännern an de Brukerinstellen, Oppasslisten, Sieten un so wieder verhinnert.
Schall de Datenbank redig sparrt warrn?',
'unlockdbtext'        => 'Dat Beennen vun de Datenbank-Sparr maakt alle Ännern weer mööglich.
Schall de Datenbank-Sparr redig beennt warrn?',
'lockconfirm'         => 'Ja, ik will de Datenbank sparren.',
'unlockconfirm'       => 'Ja, ik will de Datenbank freegeven.',
'lockbtn'             => 'Datenbank sparren',
'unlockbtn'           => 'Datenbank freegeven',
'locknoconfirm'       => 'Du hest dat Bestätigungsfeld nich markeert.',
'lockdbsuccesssub'    => 'Datenbanksparr hett Spood',
'unlockdbsuccesssub'  => 'Datenbankfreegaav hett Spood',
'lockdbsuccesstext'   => 'De {{SITENAME}}-Datenbank is sparrt.
<br />Du muttst de Datenbank weer freegeven, wenn de Pleegarbeiden beennt sünd.',
'unlockdbsuccesstext' => 'De {{SITENAME}}-Datenbank is weer freegeven.',

# Move page
'movepage'                => 'Siet schuven',
'movepagetext'            => 'Mit dissen Formular kannst du en Siet ümnömen, tosamen mit allen Versionen. De ole Titel warrt to den niegen wiederleid. Verwies op den olen Titel warrn nich ännert un de Diskuschoonssiet warrt ok nich mitschuven.',
'movepagetalktext'        => "De tohören Diskuschoonssiet warrt, wenn een dor is, mitschuuvt, '''mit disse Utnahmen:''
* Du schuuvst de Siet in en annern Naamruum oder
* dat existeert al en Diskuschoonssiet mit dissen Naam, oder
* du wählst de nerrn stahn Optschoon af

In disse Fäll muttst du de Siet, wenn du dat wullst, vun Hand schuven.",
'movearticle'             => 'Siet schuven',
'movenologin'             => 'Du büst nich anmellt',
'movenologintext'         => 'Du muttst en registreert Bruker un
[[Special:Userlogin|anmellt]] ween,
üm en Siet to schuven.',
'newtitle'                => 'To niegen Titel',
'movepagebtn'             => 'Siet schuven',
'pagemovedsub'            => 'Schuven hett Spood',
'pagemovedtext'           => 'Siet „[[$1]]“ no „[[$2]]“ schuuvt.',
'articleexists'           => 'Ünner dissen Naam existeert al en Siet.
Bitte wähl en annern Naam.',
'talkexists'              => 'Dat Schuven vun de Siet sülvst hett Spood, aver dat Schuven vun de
Diskuschoonssiet nich, vun wegen dat dor al en Siet mit dissen Titel existeert. De Inholt muss vun Hand anpasst warrn.',
'movedto'                 => 'schaven na',
'movetalk'                => 'De Diskuschoonssiet ok schuven, wenn mööglich.',
'talkpagemoved'           => 'De Diskuschoonssiet is ok schuven worrn.',
'talkpagenotmoved'        => 'De Diskuschoonssiet is <strong>nich</strong> schuven worrn.',
'1movedto2'               => '[[$1]] is nu na [[$2]] verschaven.',
'1movedto2_redir'         => '[[$1]] is nu na [[$2]] verschaven un hett den olen Redirect överschreven.',
'movelogpage'             => 'Schuuv-Logbook',
'movereason'              => 'Grund',
'delete_and_move_confirm' => 'Jo, de Siet wegsmieten',

# Export
'export'        => 'Sieden exporteren',
'exporttext'    => 'Du kannst de Text un de Bearbeidenshistorie vun een oder mehr Sieten no XML exporteern. Dat Resultat kann in en annern Wiki mit Mediawiki-Software inspeelt warrn, bearbeid oder archiveert warrn.',
'exportcuronly' => 'Blots de aktuelle Version vun de Siet exporteern',

# Namespace 8 related
'allmessages'               => 'Alle Systemnarichten',
'allmessagesname'           => 'Naam',
'allmessagesdefault'        => 'Standardtext',
'allmessagescurrent'        => 'Text nu',
'allmessagestext'           => 'Dit is de List vun all de Systemnarichten, de dat in den Mediawiki-Naamruum gifft.',
'allmessagesnotsupportedUI' => 'Dien aktuelle Snittstellenspraak <b>$1</b> warrt vun special:Allmessages op disse Steed nich ünnerstütt.',
'allmessagesnotsupportedDB' => 'special:Allmessages is nich ünnerstütt, vun wegen dat wgUseDatabaseMessages utstellt is.',

# Thumbnails
'thumbnail-more'  => 'grötter maken',
'missingimage'    => '<b>Bild fehlt</b><br /><i>$1</i>',
'filemissing'     => 'Datei fehlt',
'thumbnail_error' => 'Fehler bi dat Maken vun’t Duumnagel-Bild: $1',

# Special:Import
'import'                => 'Import vun Sieden',
'importtext'            => 'Exporteer de Siet vun dat Utgangswiki mit Special:Export un laad de Datei denn över disse Siet weer hooch.',
'importstart'           => 'Sieden warrt rinhaalt...',
'importnopages'         => 'Gifft kene Sieden to’n Rinhalen.',
'importfailed'          => 'Import hett kenen Spood: $1',
'importcantopen'        => 'Kunn de Import-Datei nich apen maken',
'importnotext'          => 'Leddig oder keen Text',
'importsuccess'         => 'Import hett Spood!',
'importhistoryconflict' => 'Dor sünd al öllere Versionen, de mit dissen kollideert. (Mööglicherwies is de Siet al vörher importeert worrn)',
'importnofile'          => 'Kene Import-Datei hoochladen.',
'importuploaderror'     => 'Hoochladen vun de Import-Datei wull nich klappen; kann angahn de Datei is grötter as de maximale Dateigrött för’t Hoochladen.',

# Import log
'importlogpage' => 'Import-Logbook',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'Mien Brukersiet',
'tooltip-pt-anonuserpage'         => 'De Brukersiet för de IP-Adress ünner de du schriffst',
'tooltip-pt-mytalk'               => 'Mien Diskuschoonssiet',
'tooltip-pt-anontalk'             => 'Diskuschoon över Ännern vun disse IP-Adress',
'tooltip-pt-preferences'          => 'Mien Instellen',
'tooltip-pt-watchlist'            => 'Mien Oppasslist',
'tooltip-pt-mycontris'            => 'List vun mien Bidreeg',
'tooltip-pt-login'                => 'Du kannst di geern anmellen, dat is aver nich neudig, üm Sieten to bearbeiden.',
'tooltip-pt-anonlogin'            => 'Du kannst di geern anmellen, dat is aver nich neudig, üm Sieten to bearbeiden.',
'tooltip-pt-logout'               => 'Afmellen',
'tooltip-ca-talk'                 => 'Diskuschoon över disse Siet',
'tooltip-ca-edit'                 => 'Du kannst disse Siet ännern. Bruuk dat vör dat Spiekern.',
'tooltip-ca-addsection'           => 'En Kommentar to disse Diskuschoonssiet hentofögen.',
'tooltip-ca-viewsource'           => 'Disse Siet is schuult. Du kannst den Borntext ankieken.',
'tooltip-ca-history'              => 'Historie vun disse Siet.',
'tooltip-ca-protect'              => 'Disse Siet schulen',
'tooltip-ca-delete'               => 'Disse Siet löschen',
'tooltip-ca-undelete'             => 'Weerholen vun de Siet, so as se vör dat löschen ween is',
'tooltip-ca-move'                 => 'Disse Siet schuven',
'tooltip-ca-watch'                => 'Disse Siet to de Oppasslist hentofögen',
'tooltip-ca-unwatch'              => 'Disse Siet vun de Oppasslist löschen',
'tooltip-search'                  => 'Söken in dit Wiki',
'tooltip-p-logo'                  => 'Hööftsiet',
'tooltip-n-mainpage'              => 'Besöök de Hööftsiet',
'tooltip-n-portal'                => 'över dat Projekt, wat du doon kannst, woans du de Saken finnen kannst',
'tooltip-n-currentevents'         => 'Achtergrünn to aktuellen Schehn finnen',
'tooltip-n-recentchanges'         => 'List vun de letzten Ännern in dissen Wiki.',
'tooltip-n-randompage'            => 'Tofällige Siet',
'tooltip-n-help'                  => 'Hier kriegst du Hülp.',
'tooltip-n-sitesupport'           => 'Gaven',
'tooltip-t-whatlinkshere'         => 'Wat wiest hierher',
'tooltip-t-recentchangeslinked'   => 'Verlinkte Sieten',
'tooltip-feed-rss'                => 'RSS-Feed för disse Siet',
'tooltip-feed-atom'               => 'Atom-Feed för disse Siet',
'tooltip-t-contributions'         => 'List vun de Bidreeg vun dissen Bruker',
'tooltip-t-emailuser'             => 'En E-Mail an dissen Bruker sennen',
'tooltip-t-upload'                => 'Biller oder Mediendatein hoochladen',
'tooltip-t-specialpages'          => 'List vun alle Spezialsieten',
'tooltip-ca-nstab-main'           => 'Siet ankieken',
'tooltip-ca-nstab-user'           => 'Brukersiet ankieken',
'tooltip-ca-nstab-media'          => 'Mediensiet ankieken',
'tooltip-ca-nstab-special'        => 'Dit is en Spezialsiet, du kannst disse Siet nich ännern.',
'tooltip-ca-nstab-project'        => 'Portalsiet ankieken',
'tooltip-ca-nstab-image'          => 'Bildsiet ankieken',
'tooltip-ca-nstab-mediawiki'      => 'Systemnorichten ankieken',
'tooltip-ca-nstab-template'       => 'Vörlaag ankieken',
'tooltip-ca-nstab-help'           => 'Hülpsiet ankieken',
'tooltip-ca-nstab-category'       => 'Kategoriesiet ankieken',
'tooltip-minoredit'               => 'Dit as en lütt Ännern markeern',
'tooltip-save'                    => 'Sekern, wat du ännert hest',
'tooltip-preview'                 => 'Vörschau för dien Ännern, bruuk dat vör dat Spiekern.',
'tooltip-diff'                    => 'Den Ünnerscheed to vörher ankieken.',
'tooltip-compareselectedversions' => 'De Ünnerscheed twüschen de twee wählten Versionen vun disse Siet ankieken.',
'tooltip-watch'                   => 'Op disse Siet oppassen.',
'tooltip-recreate'                => 'Siet wedder nee anleggen, ok wenn se wegsmeten worrn is',

# Stylesheets
'monobook.css' => '/* disse Datei editeern üm den Monobook-Skin för de ganze Siet antopassen */',

# Metadata
'nodublincore'      => 'Dublin-Core-RDF-Metadaten sünd för dissen Server nich aktiveert.',
'nocreativecommons' => 'Creative-Commons-RDF-Metadaten sünd för dissen Server nich aktiveert.',
'notacceptable'     => 'Dat Wiki-Server kann kene Daten in enen Format levern, dat dien Klient lesen kann.',

# Attribution
'anonymous'        => 'Anonyme Bruker vun {{SITENAME}}',
'siteuser'         => '{{SITENAME}}-Bruker $1',
'lastmodifiedatby' => 'Disse Siet weer dat letzte Maal $2, $1 vun $3 ännert.', # $1 date, $2 time, $3 user
'and'              => 'un',
'othercontribs'    => 'Grünnt op Arbeid vun $1.',
'others'           => 'annere',
'siteusers'        => '{{SITENAME}}-Bruker $1',
'creditspage'      => 'Sieten-Autoren',
'nocredits'        => 'Dor is keen Autorenlist för disse Siet verfögbor.',

# Spam protection
'spamprotectiontitle'    => 'Spamschild',
'spamprotectiontext'     => 'De Siet, de du spiekern wullst, weer vun de Spamschild blockt. Dat kann vun en Link to en externe Siet kamen.',
'spamprotectionmatch'    => 'Dit Text hett den Spamschild utlöst: $1',
'subcategorycount'       => 'Disse Kategorie hett $1 Ünnerkategorien.',
'categoryarticlecount'   => 'To disse Kategorie höört {{PLURAL:$1|ene Siet|$1 Sieden}} to.',
'listingcontinuesabbrev' => ' wieder',

# Info page
'infosubtitle'   => 'Informatschonen för de Siet',
'numedits'       => 'Antall vun Ännern (Siet): $1',
'numtalkedits'   => 'Antall vun Ännern (Diskuschoonssiet): $1',
'numwatchers'    => 'Antall vun Oppassers: $1',
'numauthors'     => 'Antall vun verschedene Autoren (Siet): $1',
'numtalkauthors' => 'Antall vun verschedene Autoren (Diskuschoonssiet): $1',

# Math options
'mw_math_png'    => 'Jümmer as PNG dorstellen',
'mw_math_simple' => 'Eenfach TeX as HTML dorstellen, sünst PNG',
'mw_math_html'   => 'Wenn mööglich as HTML dorstellen, sünst PNG',
'mw_math_source' => 'As TeX laten (för Textbrowser)',
'mw_math_modern' => 'Anratenswert för moderne Browser',
'mw_math_mathml' => 'MathML (experimentell)',

# Patrolling
'markaspatrolleddiff'                 => 'As nakeken marken',
'markaspatrolledtext'                 => 'Disse Siet as nakeken marken',
'markedaspatrolled'                   => 'As nakeken marken',
'markedaspatrolledtext'               => 'Disse Version is as nakeken markt.',
'rcpatroldisabled'                    => 'Nakieken vun Letzte Ännern nich anstellt',
'rcpatroldisabledtext'                => 'Dat Nakieken vun de Letzten Ännern is in’n Momang nich anstellt.',
'markedaspatrollederror'              => 'As nakeken marken klappt nich',
'markedaspatrollederrortext'          => 'Du musst ene Version angeven, dat du de as nakeken marken kannst.',
'markedaspatrollederror-noautopatrol' => 'Du kannst de Saken, de du sülvst ännert hest, nich as nakeken marken.',

# Patrol log
'patrol-log-page' => 'Nakiek-Logbook',
'patrol-log-line' => '$1 vun $2 as nakeken markt $3',
'patrol-log-auto' => '(automaatsch)',
'patrol-log-diff' => 'r$1',

# Image deletion
'deletedrevision' => 'Löschte ole Version $1.',

# Browsing diffs
'previousdiff' => '← Gah to den vörigen Ünnerscheed',
'nextdiff'     => 'Gah to den tokamen Ünnerscheed →',

# Media information
'imagemaxsize'         => 'Biller op de Bildbeschrievensiet begrenzen op:',
'thumbsize'            => 'Grött vun dat Duumnagel-Bild:',
'file-info'            => '(Grött: $1, MIME-Typ: $2)',
'file-info-size'       => '($1 × $2 Pixel, Grött: $3, MIME-Typ: $4)',
'file-nohires'         => '<small>Gifft dat Bild nich grötter.</small>',
'file-svg'             => '<small>Dit is en gröttenännerbor Vektorbild (SVG). De vörinstellte Grött is: $1 × $2 Pixels.</small>',
'show-big-image'       => 'Dat Bild wat grötter',
'show-big-image-thumb' => '<small>Grött vun disse Vörschau: $1 × $2 Pixels</small>',

'newimages'    => 'Ne’e Biller',
'showhidebots' => '($1 Bots)',
'noimages'     => 'Kene Biller.',

'passwordtooshort' => 'Dat Passwoort is to kort. Dat schall woll beter $1 Teken lang oder noch länger wesen.',

# EXIF tags
'exif-imagewidth'          => 'Breed',
'exif-imagelength'         => 'Hööchd',
'exif-orientation'         => 'Utrichtung',
'exif-model'               => 'Kameramodell',
'exif-software'            => 'bruukte Software',
'exif-artist'              => 'Autor',
'exif-exifversion'         => 'Exif-Version',
'exif-colorspace'          => 'Farvruum',
'exif-exposuretime-format' => '$1 Sek. ($2)',
'exif-fnumber'             => 'F-Nummer',
'exif-flash'               => 'Blitz',
'exif-whitebalance'        => 'Wittutgliek',
'exif-contrast'            => 'Kontrast',
'exif-gpslatitude'         => 'Breed',
'exif-gpslongitude'        => 'Läng',
'exif-gpsaltitude'         => 'Hööch',
'exif-gpsspeedref'         => 'Tempo-Eenheit',

# EXIF attributes
'exif-orientation-1' => 'Normal', # 0th row: top; 0th column: left
'exif-orientation-2' => 'waagrecht kippt', # 0th row: top; 0th column: right
'exif-orientation-3' => '180° dreiht', # 0th row: bottom; 0th column: right
'exif-orientation-4' => 'Vertikal kippt', # 0th row: bottom; 0th column: left
'exif-orientation-5' => '90° gegen de Klock dreiht un vertikal kippt', # 0th row: left; 0th column: top
'exif-orientation-6' => '90° mit de Klock dreiht', # 0th row: right; 0th column: top
'exif-orientation-7' => '90° mit de Klock dreiht un vertikal kippt', # 0th row: right; 0th column: bottom
'exif-orientation-8' => '90° gegen de Klock dreiht', # 0th row: left; 0th column: bottom

'exif-componentsconfiguration-0' => 'gifft dat nich',

'exif-subjectdistance-value' => '$1 Meter',

'exif-lightsource-0'  => 'unbekannt',
'exif-lightsource-1'  => 'Daglicht',
'exif-lightsource-4'  => 'Blitz',
'exif-lightsource-9'  => 'Good Weder',
'exif-lightsource-11' => 'Schatten',

'exif-whitebalance-0' => 'Automaatsch Wittutgliek',
'exif-whitebalance-1' => 'Wittutgliek vun Hand',

'exif-scenecapturetype-0' => 'Standard',
'exif-scenecapturetype-1' => 'Landschop',
'exif-scenecapturetype-2' => 'Porträt',
'exif-scenecapturetype-3' => 'Nacht',

'exif-contrast-0' => 'Normal',
'exif-contrast-1' => 'Wiek',
'exif-contrast-2' => 'Hart',

'exif-sharpness-0' => 'Normal',
'exif-sharpness-1' => 'Wiek',
'exif-sharpness-2' => 'Hart',

'exif-subjectdistancerange-0' => 'unbekannt',
'exif-subjectdistancerange-1' => 'Makro',
'exif-subjectdistancerange-2' => 'Nahopnahm',
'exif-subjectdistancerange-3' => 'Feernopnahm',

# Pseudotags used for GPSLatitudeRef and GPSDestLatitudeRef
'exif-gpslatitude-n' => 'Breed Noord',
'exif-gpslatitude-s' => 'Breed Süüd',

# Pseudotags used for GPSLongitudeRef and GPSDestLongitudeRef
'exif-gpslongitude-e' => 'Läng Oost',
'exif-gpslongitude-w' => 'Läng West',

# Pseudotags used for GPSSpeedRef and GPSDestDistanceRef
'exif-gpsspeed-k' => 'Kilometers in’e Stünn',
'exif-gpsspeed-m' => 'Mielen in’e Stünn',
'exif-gpsspeed-n' => 'Knoten',

# E-mail address confirmation
'confirmemail'            => 'Nettbreefadress bestätigen',
'confirmemail_noemail'    => 'Du hest kene bestätigte Nettbreefadress in diene [[Special:Preferences|Instellen]] angeven.',
'confirmemail_text'       => 'Du musst diene Nettbreefadress bestätigen, ehrder du de Nettbreeffunkschonen bruken kannst. Klick op den Knopp wieder ünnen, dat die en Bestätigungskood schickt warrt.',
'confirmemail_send'       => 'Bestätigungskood schicken.',
'confirmemail_sent'       => 'Bestätigungsnettbreef afschickt.',
'confirmemail_sendfailed' => 'Bestätigungsnettbreef kunn nich sennt warrn. Schasst man nakieken, wat de Adress ok nich verkehrt schreven is.

Fehler bi’t Versennen: $1',
'confirmemail_invalid'    => 'Bestätigungskood weer nich korrekt. De Kood is villicht to oolt.',
'confirmemail_needlogin'  => 'Du musst $1, dat diene Nettbreefadress bestätigt warrt.',
'confirmemail_success'    => 'Diene Nettbreefadress is nu bestätigt.',
'confirmemail_loggedin'   => 'Diene Nettbreefadress is nu bestätigt.',
'confirmemail_error'      => 'Dat Spiekern vun diene Bestätigung hett nich klappt.',
'confirmemail_subject'    => '{{SITENAME}} Nettbreefadress-Bestätigung',
'confirmemail_body'       => 'Een, villicht du vun de IP-Adress $1 ut, hett dat Brukerkonto „$2“ mit disse Nettbreefadress op {{SITENAME}} anmellt.

Dat wi weet, dat dit Brukerkonto würklich di tohöört un dat wi de Nettbreeffunkschonen freeschalten köönt, roop dissen Lenk op:

$3

Wenn du dat nich sülvst wesen büst, denn folg den Lenk nich. De Bestätigungskood warrt $4 ungüllig.',

# Inputbox extension, may be useful in other contexts as well
'tryexact'       => 'exakte Söök versöken',
'searchfulltext' => 'in’n Vulltext söken',
'createarticle'  => 'Artikel anleggen',

# Scary transclusion
'scarytranscludefailed'  => '[Vörlaag halen för $1 hett nich klappt]',
'scarytranscludetoolong' => '[URL is to lang]',

# Trackbacks
'trackbackbox'      => '<div id="mw_trackbacks">
Trackbacks för dissen Artikel:<br />
$1
</div>',
'trackbackdeleteok' => 'Trackback mit Spood wegsmeten.',

# Delete conflict
'deletedwhileediting' => 'Wohrschau: Disse Siet is wegsmeten worrn, as du se graad ännert hest!',
'confirmrecreate'     => "De Bruker [[{{NS:2}}:$1|$1]] ([[{{NS:3}}:$1|talk]]) hett disse Siet wegsmeten, nadem du dat Ännern anfungen hest. He hett as Grund schreven:
: ''$2''
Wist du de Siet würklich nee anleggen?",
'recreate'            => 'wedder nee anleggen',

# HTML dump
'redirectingto' => 'Redirect sett na [[$1]]...',

# action=purge
'confirm_purge'        => 'Den Cache vun disse Siet leddig maken?

$1',
'confirm_purge_button' => 'Jo',

'youhavenewmessagesmulti' => 'Du hest ne’e Narichten op $1',

'searchcontaining' => "Na Artikels söken, in de ''$1'' binnen is.",
'searchnamed'      => "Na Artikels söken, de ''$1'' heten doot.",
'articletitles'    => 'Artikels, de mit „$1“ anfangt',
'hideresults'      => 'Resultaten verstecken',

'loginlanguagelabel' => 'Spraak: $1',

# Multipage image navigation
'imgmultipageprev' => '← vörige Siet',
'imgmultipagenext' => 'nächste Siet →',
'imgmultigo'       => 'Los!',
'imgmultigotopre'  => 'Gah na de Siet',

# Table pager
'table_pager_next'         => 'Nächste Siet',
'table_pager_prev'         => 'Vörige Siet',
'table_pager_first'        => 'Eerste Siet',
'table_pager_last'         => 'Letzte Siet',
'table_pager_limit'        => 'Wies $1 Indrääg je Siet',
'table_pager_limit_submit' => 'Los',
'table_pager_empty'        => 'Kene Resultaten',

# Auto-summaries
'autosumm-blank'   => '[[{{ns:12}}:Autokommentar|AK]]: Siet leddig maakt',
'autosumm-replace' => '[[{{ns:12}}:Autokommentar|AK]]: Siet leddig maakt un ‚$1‘ rinschreven',
'autoredircomment' => '[[{{ns:12}}:Autokommentar|AK]]: Redirect sett na [[$1]]', # This should be changed to the new naming convention, but existed beforehand
'autosumm-new'     => '[[{{ns:12}}:Autokommentar|AK]]: Ne’e Siet: ‚$1‘',

# Size units
'size-bytes'     => '$1 B',
'size-kilobytes' => '$1 KB',
'size-megabytes' => '$1 MB',
'size-gigabytes' => '$1 GB',

# Live preview
'livepreview-loading' => 'Läädt…',
'livepreview-ready'   => 'Läädt… Trech!',
'livepreview-failed'  => 'Live-Vörschau klapp nich!
Versöök de normale Vörschau.',
'livepreview-error'   => 'Verbinnen klapp nich: $1 „$2“
Versöök de normale Vörschau.',

);


