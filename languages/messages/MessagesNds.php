<?php

/** Low Saxon (Plattdüütsch)
 *
 * @package MediaWiki
 * @subpackage Language
 */
$quickbarSettings = array(
	'Keen', 'Links, fast', 'Rechts, fast', 'Links, sweven'
);

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
	'smarty'        => 'Paddington',
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
# Schalter för de Brukers
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
'tog-showtoc'                 => 'Wiesen vun\'n Inholtsverteken bi Sieten mit mehr as dree Överschriften',
'tog-rememberpassword'        => 'Duersam Inloggen',
'tog-editwidth'               => 'Text-Ingaavfeld mit vulle Breed',
'tog-watchdefault'            => 'Op niege un ännerte Sieten oppassen',
'tog-minordefault'            => 'Alle Ännern as lütt markeern',
'tog-previewontop'            => 'Vörschau vör dat Editeerfinster wiesen',
'tog-previewonfirst'          => 'Vörschau bi\'n eersten Ännern wiesen',
'tog-nocache'                 => 'Sietencache deaktiveern',
# Dates
'sunday'    => 'Sünndag',
'monday'    => 'Maandag',
'tuesday'   => 'Dingsdag',
'wednesday' => 'Merrweek',
'thursday'  => 'Dunnersdag',
'friday'    => 'Freedag',
'saturday'  => 'Sünnavend',
'january'   => 'Januar',
'february'  => 'Februar',
'march'     => 'März',
'april'     => 'April',
'may_long'  => 'Mai',
'june'      => 'Juni',
'july'      => 'Juli',
'august'    => 'August',
'september' => 'September',
'october'   => 'Oktober',
'november'  => 'November',
'december'  => 'Dezember',
'jan'       => 'Jan',
'feb'       => 'Feb',
'mar'       => 'Mär',
'apr'       => 'Apr',
'may'       => 'Mai',
'jun'       => 'Jun',
'jul'       => 'Jul',
'aug'       => 'Aug',
'sep'       => 'Sep',
'oct'       => 'Okt',
'nov'       => 'Nov',
'dec'       => 'Dez',


# Textdelen, de vun vele Sieten bruukt warrn:
#
'categories'            => 'Sietenkategorien',
'pagecategories'        => 'Sietenkategorien',
'category_header'       => 'Sieten in de Kategorie $1',
'subcategories'         => 'Ünnerkategorien',
'mainpage'                      => 'Hööftsiet',
'mainpagetext'          => 'De Wiki-Software is mit Spood installeert worrn.',
'mainpagedocfooter'     => 'Kiek de [http://meta.wikimedia.org/wiki/MediaWiki_i18n Dokumentatschoon för dat Anpassen vun de Brukerböversiet]
un dat [http://meta.wikimedia.org/wiki/MediaWiki_User%27s_Guide Brukerhandbook] för Hülp to de Bruuk un Konfiguratschoon.',
'portal'                        => '{{SITENAME}}-Portal',
'portal-url'                      => '{{ns:4}}:Portal',
'about'                               => 'Över',
'aboutsite'             => 'Över {{SITENAME}}',
'aboutpage'                     => '{{ns:4}}:Över_{{SITENAME}}',
'article'               => 'Artikel',
'help'                          => 'Hülp',
'helppage'                      => '{{ns:4}}:Hülp',
'bugreports'               => 'Kontakt',
'bugreportspage'        => '{{ns:4}}:Kontakt',
'sitesupport'           => 'Gaven',
'faq'                                   => 'Faken stellte Fragen',
'faqpage'                       => '{{ns:project}}:Faken stellte Fragen',
'newwindow'                     => '(apent sik in en nieg Finster)',
'edithelp'                          => 'Bearbeidenshülp',
'edithelppage'           => '{{ns:project}}:Editeerhülp',
'cancel'                              => 'Afbreken',
'qbfind'                              => 'Finnen',
'qbbrowse'                          => 'Blädern',
'qbedit'                        => 'Ännern',
'qbpageoptions'         => 'Sietenoptschonen',
'qbpageinfo'               => 'Sietendaten',
'qbmyoptions'           => 'Instellen',
'qbspecialpages'               => 'Spezialsieten',
'moredotdotdot'         => 'Mehr...',
'mypage'                              => 'Mien Siet',
'mytalk'                              => 'Mien Diskuschoon',
'anontalk'                          => 'Diskuschoonssiet vun disse IP',
'navigation'            => 'Navigatschoon',
'currentevents'         => 'Aktuell Schehn',
'currentevents-url'     => '{{ns:4}}:Aktuell Schehn',
'disclaimers'           => 'Lizenzbestimmen',
'disclaimerpage'                      => '{{ns:4}}:Lizenzbestimmen',
'errorpagetitle'        => 'Fehler',
'returnto'                      => 'Trüch to $1.',
'whatlinkshere'         => 'Wat wiest hierher',
'help'                          => 'Hülp',
'search'                        => 'Söök',
'searchbutton'                  => 'Söök',
'history'                            => 'Historie',
'history_short'         => 'Historie',
'info_short'               => 'Informatschoon',
'printableversion'      => 'Druckversion',
'editthispage'          => 'Siet bearbeiden',
'delete'                => 'wegsmieten',
'deletethispage'        => 'Disse Siet wegsmieten',
'undelete_short'        => 'Weerholen',
'protect'               => 'Schulen',
'protectthispage'       => 'Siet schulen',
'unprotect'             => 'Freegeven',
'unprotectthispage'     => 'Schuul opheben',
'newpage'               => 'Niege Siet',
'talkpage'                          => 'Diskuschoon',
'specialpage'           => 'Spezialsiet',
'personaltools'         => 'Persönliche Warktüüch',
'postcomment'           => 'Kommentar hentofögen',
'articlepage'             => 'Artikel',
'toolbox'               => 'Warktüüch',
'projectpage'         => 'Meta-Text',
'userpage'              => 'Brukersiet',
'imagepage'             => 'Bildsiet',
'viewtalkpage'          => 'Diskuschoon',
'otherlanguages'        => 'Annere Spraken',
'redirectedfrom'        => '(Wiederleiden vun $1)',
'lastmodifiedat'           => 'Disse Siet is toletzt üm $2, $1 ännert worrn.',
'viewcount'                        => 'Disse Siet is $1 Maal opropen worrn.',
'copyright'             => 'De Inholt is verfögbor ünner de $1.',
'protectedpage'         => 'Schulte Sieten',
'nbytes'                              => '$1 Bytes',
'go'                                     => 'Los',
'searcharticle'                                     => 'Los',
'ok'                                    => 'OK',
'retrievedfrom'         => 'Vun „$1“',
'newmessageslink'       => 'niege Norichten',
'editsection'           => 'bearbeiden',
'editold'           => 'bearbeiden',
'toc'                   => 'Inholtsverteken',
'showtoc'               => 'wiesen',
'hidetoc'               => 'Nich wiesen',
'thisisdeleted'         => 'Ankieken oder weerholen vun $1?',
'restorelink'           => '$1 löscht Bearbeidensvörgäng',
'feedlinks'             => 'Feed:',

# Kortwöör för elkeen Namespace, ünner annern vun MonoBook bruukt
'nstab-main'            => 'Artikel',
'nstab-user'            => 'Brukersiet',
'nstab-media'           => 'Media',
'nstab-special'         => 'Spezial',
'nstab-project'              => 'Över',
'nstab-image'           => 'Bild',
'nstab-mediawiki'       => 'Noricht',
'nstab-template'        => 'Vörlaag',
'nstab-help'            => 'Hülp',
'nstab-category'        => 'Kategorie',

# Editeer-Warktüüchleist
'bold_sample'           => 'Fetten Text',
'bold_tip'              => 'Fetten Text',
'italic_sample'         => 'Kursiven Text',
'italic_tip'            => 'Kursiven Text',
'link_sample'           => 'Link-Text',
'link_tip'              => 'Internen Link',
'extlink_sample'        => 'http://www.bispeel.com Link-Text',
'extlink_tip'           => 'Externen Link (http:// is wichtig)',
'headline_sample'       => 'Evene 2 Överschrift',
'headline_tip'          => 'Evene 2 Överschrift',
'math_sample'           => 'Formel hier infögen',
'math_tip'              => 'Mathematsche Formel (LaTeX)',
'nowiki_sample'         => 'Unformateerten Text hier infögen',
'nowiki_tip'            => 'Unformateerten Text',
'image_sample'          => 'Bispeel.jpg',
'image_tip'             => 'Bild-Verwies',
'media_sample'          => 'Bispeel.mp3',
'media_tip'             => 'Mediendatei-Verwies',
'sig_tip'               => 'Dien Signatur mit Tiedstempel',
'hr_tip'                => 'Waagrechte Lien (sporsam bruken)',

# Hööft-Script un globale Funktschonen
#
'nosuchaction'           => 'Disse Aktschoon gifft dat nich',
'nosuchactiontext'      => 'Disse Aktschoon warrt vun de MediaWiki-Software nich ünnerstütt',
'nosuchspecialpage'     => 'Disse Spezialsiet gifft dat nich',
'nospecialpagetext'     => 'Disse Spezialsiet warrt vun de MediaWiki-Software nich ünnerstütt',

# Generelle Fehlers
#
'error'                 => 'Fehler',
'databaseerror'         => 'Fehler in de Datenbank',
'dberrortext'             => 'Dor weer en Syntaxfehler in de Datenbankaffraag.
De letzte Datenbankaffraag weer:

<blockquote><tt>$1</tt></blockquote>

ut de Funktschoon <tt>$2</tt>.
MySQL mell den Fehler <tt>$3: $4</tt>.',
'dberrortextcl'         => 'Dor weer en Syntaxfehler in de Datenbankaffraag.
De letzte Datenbankaffraag weer: $1 ut de Funktschoon <tt>$2</tt>.
MySQL mell den Fehler: <tt>$3: $4</tt>.',
'noconnect'                           => 'De Software kunn keen Verbinnen to de Datenbank op $1 opnehmen',
'nodb'                                    => 'De Software kunn de Datenbank $1 nich utwählen',
'cachederror'           => 'Disse Siet is en Kopie ut\'n Cache un is mööglicherwies nich aktuell.',
'readonly'              => 'Datenbank is sparrt',
'enterlockreason'       => 'Giff den Grund an, worüm de Datenbank sparrt warrn schall un taxeer, wo lang de Sparr duert',
'readonlytext'              => 'De {{SITENAME}}-Datenbank is för enige Tied sparrt, to\'n Bispeel wegen Pleegarbeiden. Versöök dat later noch eenmal.',
'missingarticle'        => 'De Text för de Siet \'$1\' kunn nich in de Datenbank funnen warrn. Dat is wohrschienlich en Fehler in de Software. Bitte mell dat an enen Administrater un giff ok den Sietennaam an.',
'internalerror'         => 'Internen Fehler',
'filecopyerror'         => 'De Software kunn Datei \'$1\' nich no \'$2\' kopeern.',
'filerenameerror'       => 'De Software kunn Datei \'$1\' nich no \'$2\' ümnömen.',
'filedeleteerror'       => 'De Software kunn Datei \'$1\' nich löschen.',
'filenotfound'              => 'De Software kunn Datei \'$1\' nich finnen.',
'unexpected'            => 'Unvermodten Weert: \'$1\'=\'$2\'.',
'formerror'                           => 'Fehler: De Software kunn dat Formular nich verarbeiden',
'badarticleerror'       => 'Disse Aktschoon kann op disse Siet nich anwennt warrn.',
'cannotdelete'          => 'De Software kunn de spezifizeerte Siet nich löschen. (Mööglicherwies is de al vun en annern löscht worrn.)',
'badtitle'              => 'Ungülligen Titel',
'badtitletext'             => 'De Titel vun de födderte Siet weer ungüllig, leddig, oder en ungülligen Spraaklink vun en annern Wiki.',
'perfdisabled'          => 'Disse Funktschoon is wegen Överlast vun de Servers för enige Tied deaktiveert. Versöök dat doch twüschen 02:00 un 14:00 UTC noch eenmal<br />(Aktuelle Servertied: '.date('H:i:s').' UTC).',
'perfdisabledsub'       => 'Hier is en spiekerte Kopie vun $1:',
'perfcached'            => 'Disse Daten kamen ut den Cache un sünd mööglicherwies nich aktuell:',
'wrong_wfQuery_params'  => 'Falschen Parameter för wfQuery()<br />
Funktschoon: $1<br />
Query: $2',
'viewsource'            => 'Borntext ankieken',
'protectedtext'         => 'Disse Siet is för dat Bearbeiden sparrt. Dorför kann dat verschedene Grünn geven; kiek [[{{ns:4}}:Schulte Sieten]].

Du kannst den Borntext vun disse Siet ankieken un kopeern:',


# Login- un Logoutsieten
#
'logouttitle'             => 'Bruker-Afmellen',
'logouttext'            => 'Du büst nu afmellt. Du kannst {{SITENAME}} nu anonym wiederbruken oder di ünner en annern Brukernaam weer anmellen.',

'welcomecreation'       => '<h2>Willkomen, $1!</h2><p>Dien Brukerkonto is nu inricht.
Vergeet nich, dien [[Special:Preferences|Instellen]] antopassen.',

'loginpagetitle'        => 'Bruker-Anmellen',
'yourname'                      => 'Dien Brukernaam',
'yourpassword'           => 'Dien Password',
'yourpasswordagain'     => 'Password nochmal ingeven',
'remembermypassword'    => 'Duersam inloggen',
'loginproblem'           => '<b>Dor weer en Problem mit dien Anmellen.</b><br />Versöök dat noch eenmal!',
'alreadyloggedin'       => '<strong>Bruker $1, du büst al anmellt!</strong><br />',

'login'                         => 'Anmellen',
'loginprompt'           => 'Üm sik bi {{SITENAME}} antomellen, musst du Cookies aktiveert hebben.',
'userlogin'                     => 'Anmellen',
'logout'                              => 'Afmellen',
'userlogout'               => 'Afmellen',
'notloggedin'           => 'Nich anmellt',
'createaccount'         => 'Nieg Brukerkonto anleggen',
'createaccountmail'     => 'över E-Mail',
'badretype'                        => 'De beiden Passwöör stimmt nich övereen.',
'userexists'               => 'Dissen Brukernaam is al vergeven. Bitte wähl en annern.',
'youremail'                     => 'Dien E-Mail (kene Plicht) *',
'yournick'                          => 'Dien Ökelnaam (för dat Ünnerschrieven)',
'yourrealname'                  => 'Dien echten Naam (kene Plicht)',
'yourlanguage'           => 'Snittstellenspraak',
'yourvariant'           => 'Dien Spraak',
// FIXME: following should be split to 'prefs-help-realname' & 'prefs-help-email'
#'prefs-help-userdata'   => '* <strong>E-Mail</strong> (kene Plicht): Wenn du en E-Mailadress angiffst, könen annere di E-Mails sennen,
#ahn dat diene Adress no buten künnig warrt. Wenn du dien ol Password vergeten hest,
#kannst du ok blots denn en nieg Passwort kriegen, wenn du en E-Mailadress angeven hest.',
'loginerror'               => 'Fehler bi dat Anmellen',
'noname'                              => 'Du muttst en Brukernaam angeven.',
'loginsuccesstitle'     => 'Anmellen hett Spood',
'loginsuccess'           => 'Du büst nu as „$1“ bi {{SITENAME}} anmellt.',
'nosuchuser'               => 'De Brukernaam „$1“ existeert nich.
Prööv de Schrievwies oder mell di as niegen Bruker an.',
'nosuchusershort'             => 'De Brukernaam „$1“ existeert nich. Prööv de Schrievwies.',
'wrongpassword'         => 'Dat Password is falsch. Bitte versöök dat nochmal.',
'mailmypassword'        => 'En nieg Password sennen',
'passwordremindertitle' => '{{SITENAME}} Password',
'passwordremindertext'  => 'Een (IP-Adress $1) hett üm en nieg Password för dat Anmellen bi {{SITENAME}} beed.
Dat Password för Bruker „$2“ is nu „$3“. Bitte mell di nu an un änner dien Password.',
'noemail'                       => 'Bruker „$1“ hett kene E-Mail-Adress angeven.',
'passwordsent'           => 'En nieg Password is an de E-Mail-Adress vun Bruker „$1“ send worrn. Mell di an, wenn du dat Password kriegt hest.',
'mailerror'             => 'Fehler bi dat Sennen vun de E-Mail: $1',
'acct_creation_throttle_hit' => 'Du hest al $1 Brukerkontos anleggt. Du kannst nich noch mehr anleggen.',


# Sieten ännern
#
'summary'                      => 'Tosamenfaten',
'subject'                => 'Bedrap',
'minoredit'                  => 'Blots lütte Ännern.',
'watchthis'              => 'Op disse Siet oppassen',
'savearticle'                  => 'Siet spiekern',
'preview'                       => 'Vörschau',
'showpreview'               => 'Vörschau wiesen',
'blockedtitle'            => 'Bruker is blockt',
'blockedtext'                  => 'Dien Brukernaam oder dien IP-Adress is vun $1 blockt worrn.
As Grund is angeven:<br />$2<p>Wenn du över den Block spreken willst, kontakteer den Administrater.',
'whitelistedittitle'     => 'üm de Siet to Bearbeiden is dat neudig anmellt to ween',
'whitelistedittext'      => 'Du muttst di [[Special:Userlogin|hier anmellen]] üm Sieten bearbeiden to könen.',
'whitelistreadtitle'     => 'üm to Lesen is dat neudig anmellt to ween',
'whitelistreadtext'      => 'Du muttst di [[Special:Userlogin|hier anmellen]] üm Sieten lesen to könen.',
'whitelistacctitle'      => 'Du hest nich de Rechten en Konto antoleggen',
'whitelistacctext'       => 'Üm in dissen Wiki Kontos anleggen to könen muttst du di [[Special:Userlogin|hier anmellen]] un de neudigen Rechten hebben.',
'loginreqtitle'          => 'Anmellen neudig',
'loginreqlink'           => 'anmellen',
'loginreqpagetext'       => 'Du muttst di $1, üm annere Sieten ankieken to könen.',
'accmailtitle'           => 'Passwort is send worrn.',
'accmailtext'            => 'Dat Passwort vun $1 is an $2 send worrn.',
'newarticle'                    => '(Nieg)',
'newarticletext'         => 'Hier den Text vun de niegen Siet indregen. Jümmer in ganze Sätz schrieven un kene Texten vun annern, de enen Oorheverrecht ünnerliggt, hierher kopeern.',
'anontalkpagetext'       => "---- ''Dit is de Diskuschoonssiet vun en nich anmellt Bruker. Wi mööt hier de numerische [[IP-Adress]]
verwennen, üm den Bruker to identifizeern. So en Adress kann vun verscheden Brukern bruukt warrn. Wenn du en anonymen Bruker büst un meenst,
dat disse Kommentaren nich an di richt sünd, denn [[Special:Userlogin|mell di doch an]], dormit dat Problem nich mehr besteiht.''",
'noarticletext'          => '(Disse Siet hett in\'n Momang kenen Text)',
'usercsspreview'         => "'''Denk doran, dat du blots en Vörschau vun dien CSS ankiekst, dat is noch nich spiekert!'''",
'userjspreview'          => "'''Denk doran, dat du blots en Vörschau vun dien JS ankiekst, dat is noch nich spiekert!'''",
'clearyourcache'         => "'''Denk doran:''' No den Spiekern muttst du dien Browser noch seggen, de niege Version to laden: '''Mozilla/Firefox:''' ''Strg-Shift-R'', '''IE:''' ''Strg-F5'', '''Safari:''' ''Cmd-Shift-R'', '''Konqueror:''' ''F5''.",
'usercssjsyoucanpreview' => '<strong>Tipp:</strong> Bruuk den Vörschau-Knoop, üm dien nieg CSS/JS vör dat Spiekern to testen.',
'updated'                       => '(Ännert)',
'note'                          => '<strong>Henwies:</strong>',
'previewnote'                   => 'Dit is blots en Vörschau, de Siet is noch nich spiekert!',
'previewconflict'        => 'Disse Vörschau wiest den Inholt vun dat Textfeld baven; so warrt de Siet utseihn, wenn du nu spiekerst.',
'editing'                       => 'Ännern vun $1',
'editinguser'                       => 'Ännern vun $1',
'editingsection'                => 'Ännern vun $1 (Afsatz)',
'editingcomment'                => 'Ännern vun $1 (Kommentar)',
'editconflict'            => 'Konflikt bi dat Bearbeiden: $1',
'explainconflict'        => 'En anner Bruker hett disse Siet ännert, no de Tied dat du anfungen hest, de Siet to bearbeiden.
Dat Textfeld baven wiest de aktuelle Siet.
Dat Textfeld nerrn wiest diene Ännern.
Föög diene Ännern in dat Textfeld baven in.

<b>Blots</b> de Text in dat Textfeld baven warrt spiekert, wenn du op Spiekern klickst!<br />',
'yourtext'                      => 'Dien Text',
'storedversion'          => 'Spiekerte Version',
'nonunicodebrowser'      => '<strong>Wohrscho: Dien Browser ünnerstütt keen Unicode, wähl en annern Browser, wenn du en Siet ännern wullst.</strong>',
'editingold'                    => '<strong>Wohrscho: Du bearbeidst en ole Version vun disse Siet.
Wenn du spiekerst, warrn alle niegeren Versionen överschrieven.</strong>',
'yourdiff'                      => 'Ünnerscheed',
/*'copyrightwarning'       => "<b><big>Kopeer kene Websieten</big>, de nich dien egen sünd un bruuk <big>kene Warken, de enen Oorheverrecht ünnerliggt,</big> ahn Verlööv vun de Copyright-Inhebbers!</b>
<p>Du giffst hiermit dien Tosaag, dat du dien Text <strong>sülvst verfaat</strong> hest, dat de Text Gemeengood
(<strong>„Public Domain“</strong>) is, oder dat de <strong>Copyright-Inhebber</strong> sien <strong>Tostimmen</strong> geven hett.
Wenn dissen Text al an annere Steed apentlich maakt is, schriev dat ok op de Diskuschoonssiet, sünst kann dat passeern, dat en annern dat weer löscht,
vun wegen dat he denkt, dat weer en Brook vun dat Oorheverrecht.

<p><i>Denk doran, dat alle {{SITENAME}}-Bidreeg automatsch ünner de „GNU Fre'e Dokumentatschoonslizenz“ steiht.
Wenn du nich wullst, dat dien Arbeid hier vun annern ännert un verbreed warrt, denn klick nich op Spiekern.</i></p>",*/
'longpagewarning'        => '<strong>Wohrscho: Disse Siet is $1 KB groot; en poor Browser köönt Probleme hebben, Sieten to bearbeiden, de grötter as 32 KB sünd.
Bedenk of disse Siet vilicht in lüttere Afsnitten opdeelt warrn kann.</strong>',
'readonlywarning'        => '<strong>Wohrscho: De Datenbank is wiel dat Ännern vun de
Siet för Pleegarbeiden sparrt worrn, so dat du de Siet en Stoot nich
spiekern kannst. Seker di den Text un versöök later weer de Ännern to spiekern.</strong>',
'protectedpagewarning'   => '<strong>Wohrscho: Disse Siet is sparrt worrn, so dat blots
Bruker mit Sysop-Rechten doran arbeiden könnt. Kiek ok bi de [[Project:Schulte Sieten|Regeln för schulte Sieten]].</strong>',
'copyrightwarning2'      => 'Dien Text, de du op {{SITENAME}} stellen wullst, könnt vun elkeen ännert oder wegmaakt warrn.
Wenn du dat nich wullst, dröffst du dien Text hier nich apentlich maken.<br />

Du bestätigst ok, dat du den Text sülvst schreven hest oder ut en „Public Domain“-Born oder en annere fre\'e Born kopeert hest (Kiek ok $1 för Details).
<strong>Kopeer kene Warken, de enen Oorheverrecht ünnerliggt, ahn Verlööv vun de Copyright-Inhebbers!</strong>',

# Sietenhistorie
#
'revhistory'            => 'Fröhere Versionen',
'nohistory'                     => 'Dor sünd kene fröheren Versionen vun disse Siet.',
'revnotfound'            => 'Kene fröheren Versionen funnen',
'revnotfoundtext'      => 'De Version vun disse Siet, no de du söökst, kunn nich funnen warrn. Prööv de URL vun disse Siet.',
'loadhist'                      => 'Lade List mit freuhere Versionen',
'currentrev'              => 'Aktuelle Version',
'revisionasof'          => 'Version vun\'n $1',
'nextrevision'          => '←Nächstjüngere Version',
'previousrevision'      => 'Nächstöllere Version→',
'cur'                           => 'Aktuell',
'next'                          => 'Tokamen',
'last'                          => 'Letzte',
'orig'                                  => 'Original',
'histlegend'              => 'Ünnerscheed-Utwahl: De Boxen vun de wünschten
Versionen markeern un \'Enter\' drücken oder den Knoop nerrn klicken/alt-v.<br />
Legende:
(Aktuell) = Ünnerscheed to de aktuelle Version,
(Letzte) = Ünnerscheed to de vörige Version,
L = Lütte Ännern',

# Ünnerscheed
#
'difference'                   => '(Ünnerscheed twüschen Versionen)',
'loadingrev'                    => 'laad Versionen üm Ünnerscheden to wiesen',
'lineno'                                => 'Lien $1:',
'editcurrent'                 => 'De aktuelle Version vun disse Siet bearbeiden',
'selectnewerversionfordiff' => 'En niegere Version för en Vergliek utwählen',
'selectolderversionfordiff' => 'En öllere Version för en Vergliek utwählen',
'compareselectedversions'   => 'Wählte Versionen verglieken',

# Söök
#
'searchresults'     => 'Söökresultaten',
'searchresulttext'  => 'För mehr Informatschonen över {{SITENAME}}, kiek [[{{ns:4}}:Söök|{{SITENAME}} dörsöken]].',
'searchsubtitle'         => 'För de Söökanfraag „[[:$1]]“',
'searchsubtitleinvalid'         => 'För de Söökanfraag „$1“',
'badquery'                      => 'Falsche Söökanfraag',
'badquerytext'       => 'De Söökanfraag kunn nich verarbeid warrn.
Sachts hest du versöökt, en Word to söken, dat kötter as twee Bookstaven is.
Dit funktschoneert in\'n Momang noch nich.
Mööglicherwies hest du ok de Anfraag falsch formuleert, to\'n Bispeel \'Lohn un un Stüern\'. Versöök en anners formuleerte Anfraag.',
'matchtotals'         => 'De Anfraag „$1“ stimmt mit $2 Sietenöverschriften un den Text vun $3 Sieten överein.',
'noexactmatch'         => 'Dor existeert kene Siet mit dissen Naam. Versöök de Vulltextsöök oder legg de Siet [[:$1|nieg]] an.',
'titlematches'       => 'Övereenstimmen mit Överschriften',
'notitlematches'    => 'Kene Övereenstimmen',
'textmatches'           => 'Övereenstimmen mit Texten',
'notextmatches'     => 'Kene Övereenstimmen',
'prevn'                         => 'vörige $1',
'nextn'                           => 'tokamen $1',
'viewprevnext'       => 'Wies ($1) ($2) ($3).',
'showingresults'    => 'Hier sünd <b>$1</b> Resultaten, anfungen mit #<b>$2</b>.',
'showingresultsnum' => 'Hier sünd <b>$3</b> Resultaten, anfungen mit #<b>$2</b>.',
'nonefound'                    => '<strong>Henwies</strong>:
Söökanfragen ahn Spood hebbt faken de Oorsaak, dat no kotte oder gemeene Wöör söökt warrt, de nich indizeert sünd.',
'powersearch'       => 'Söök',
'powersearchtext'   => '
Söök in Naamrüüm:<br />


$1<br />
$2 Wies ok Wiederleiden   Söök no $3 $9',
'searchdisabled'    => '<p>De Vulltextsöök is wegen Överlast en Stoot deaktiveert. In disse Tied kannst du disse Google-Söök verwennen,
de aver nich jümmer den aktuellsten Stand weerspegelt.<p>',
'blanknamespace'     => '(Hööft-)',

# Instellen
#
'preferences'          => 'Instellen',
'prefsnologin'       => 'Nich anmellt',
'prefsnologintext'   => 'Du muttst [[Special:Userlogin|anmellt]] ween, üm dien Instellen to ännern.',
'prefsreset'            => 'Instellen sünd op Standard trüchsett.',
'qbsettings'         => 'Sietenliest',
'changepassword'     => 'Password ännern',
'skin'                          => 'Utsehn vun de Steed',
'math'                          => 'TeX',
'dateformat'         => 'Datumsformat',
'math_failure'          => 'Parser-Fehler',
'math_unknown_error'    => 'Unbekannten Fehler',
'math_unknown_function' => 'Unbekannte Funktschoon',
'math_lexing_error'      => '\'Lexing\'-Fehler',
'math_syntax_error'      => 'Syntaxfehler',
'saveprefs'                     => 'Instellen spiekern',
'resetprefs'            => 'Instellen trüchsetten',
'oldpassword'           => 'Ool Password',
'newpassword'          => 'Nieg Password',
'retypenew'                     => 'Nieg Password (nochmal)',
'textboxsize'          => 'Textfeld-Grött',
'rows'                              => 'Regen',
'columns'                       => 'Spalten',
'searchresultshead'  => 'Söökresultaten',
'resultsperpage'     => 'Treffer pro Siet',
'contextlines'          => 'Lienen pro Treffer',
'contextchars'          => 'Teken pro Lien',
'stubthreshold'      => 'Kotte Sieten markeeren bet',
'recentchangescount' => 'Antall „Letzte Ännern“',
'savedprefs'            => 'Dien Instellen sünd spiekert.',
'timezonelegend'     => 'Tiedrebeet',
'timezonetext'          => 'Giff de Antall vun de Stünnen an, de twüschen dien Tiedrebeet un UTC liggen.',
'localtime'             => 'Oortstied',
'timezoneoffset'     => 'Ünnerscheed',
'servertime'         => 'Aktuelle Tied op den Server',
'guesstimezone'      => 'Ut den Browser övernehmen',
'defaultns'                     => 'In disse Naamrüüm schall standardmatig söökt warrn:',

# letzte Ännern
#
'changes'            => 'Ännern',
'recentchanges'      => 'Letzte Ännern',
'recentchangestext'  => '
Disse Siet warrt wiel dat Laden automatsch aktualiseert. Wiest warrn Sieten, de toletzt bearbeid worrn sünd, dorto de Tied un de Naam vun de Autor.',
'rcnote'                        => 'Hier sünd de letzten <b>$1</b> Ännern vun de letzten <b>$2</b> Daag. (<b>N</b> - Niege Sieten; <b>L</b> - Lütte Ännern)',
'rcnotefrom'            => 'Dit sünd de Ännern siet <b>$2</b> (bet to <b>$1</b> wiest).',
'rclistfrom'            => 'Wies niege Ännern siet $1',
'rclinks'                         => 'Wies de letzten $1 Ännern; wies de letzten $2 Daag.',
'diff'                          => 'Ünnerscheed',
'hist'                              => 'Versionen',
'hide'                          => 'Nich wiesen',
'show'                          => 'Wiesen',
'minoreditletter'    => 'L',
'newpageletter'      => 'N',


# Upload
#
'upload'                        => 'Hoochladen',
'uploadbtn'                    => 'Datei hoochladen',
'reupload'                      => 'Nieg hoochladen',
'reuploaddesc'          => 'Trüch to de Hoochladen-Siet.',
'uploadnologin'     => 'Nich anmellt',
'uploadnologintext'     => 'Du muttst [[Spezial:Userlogin|anmellt ween]] üm Datein hoochladen to könen.',
'uploaderror'         => 'Fehler bi dat Hoochladen',
'uploadtext'           => "
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
'''[[Bild:datei.jpg]]''' oder
'''[[Bild:datei.jpg|Beschrieven]]'''.

Denk doran, dat, lieks as bi de annern Sieten, annere Bruker dien Datein löschen oder ännern könen.',
'uploadlog'                => 'Datei-Logbook',
'uploadlogpage'     => 'Datei-Logbook',
'uploadlogpagetext' => 'Hier is de List vun de letzten hoochladenen Datein.
Alle Tieden sünd UTC.

<ul>

</ul>",
'filename'                      => 'Dateinaam',
'filedesc'                      => 'Beschrieven',
'filestatus'        => 'Copyright-Status',
'filesource'        => 'Born',
'copyrightpage'     => '{{ns:4}}:Copyright',
'copyrightpagename' => '{{SITENAME}} Copyright',
'uploadedfiles'         => 'Hoochladene Datein',
'minlength'             => 'Bilddatein möten tominnst dree Bookstaven hebben.',
'badfilename'           => 'De Bildnaam is in „$1“ ännert worrn.',
'badfiletype'           => '„.$1“ is keen anratenswert Dateiformat.',
'largefile'             => 'Kene Biller över 100 KByte hoochladen.',
'emptyfile'                    => 'De hoochladene Datei is leddig. De Grund kann en Tippfehler in de Dateinaam ween. Kontrolleer, of du de Datei redig hoochladen wullst.',
'fileexists'            => 'En Datei mit dissen Naam existeert al, prööv $1, wenn du di nich seker büst of du dat ännern wullst.',
'successfulupload'  => 'Datei hoochladen hett Spood',
'fileuploaded'       => 'Dat Hoochladen vun de Datei „$1“ hett Spood.
Disse ($2) Link föhrt to de Bildsiet. Dor kann indregen warrn, woneem dat Bild kummt, welkeen dat wann mookt hett un wenn neudig, welkeen Copyright-Status dat Bild hett.',
'uploadwarning'     => 'Wohrscho',
'savefile'                      => 'Datei spiekern',
'uploadedimage'     => '„$1“ hoochladen',
'uploadcorrupt'     => 'De Datei is korrupt oder hett en falsch Ennen. Datei pröven un nieg hoochladen.',
'filemissing'           => 'Datei fehlt',

# Billerlist
#
'imagelist'               => 'Billerlist',
'imagelisttext'       => 'Hier is en List vun $1 Biller, sorteert $2.',
'getimagelist'          => 'Billerlist laden',
'ilsubmit'                        => 'Söök',
'showlast'                      => 'Wies de letzten $1 Biller, sorteert $2.',
'byname'                        => 'no Naam',
'bydate'                        => 'no Datum',
'bysize'                        => 'no Grött',
'imgdelete'                    => 'Löschen',
'imgdesc'                         => 'Beschrieven',
'imglegend'                     => 'Legende: (Beschrieven) = Wies/Änner Bildbeschrieven.',
'imghistory'          => 'Bild-Versionen',
'revertimg'                     => 'trüchsetten',
'deleteimg'                    => 'Löschen',
'deleteimgcompletely'   => 'Löschen',
'imghistlegend'       => 'Legende: (cur) = Dit is dat aktuelle Bild, (Löschen) = lösch
disse ole Version, (Trüchsetten) = bruuk weer disse ole Version.',
'imagelinks'            => 'Bildverwiesen',
'linkstoimage'          => 'Disse Sieten bruukt dat Bild:',
'nolinkstoimage'      => 'Kene Siet bruukt dat Bild.',
'sharedupload'        => 'Disse Datei is en Datei, de mööglicherwies ok vun annere Wikis bruukt warrt.',


# Statistik
#
'statistics'            => 'Statistik',
'sitestats'                     => 'Sietenstatistik',
'userstats'                     => 'Brukerstatistik',
'sitestatstext'       => 'Dat gifft allens tosamen <b>$1</b> Sieten in de Datenbank.
Dat slött Diskuschoonsieten, Sieten över {{SITENAME}}, extrem kotte Artikels, Wiederleiden un annere Sieten in, de nich as Artikel gelten köönt.
Disse utnommen, gifft dat <b>$2</b> Sieten, de as Artikel gelten könen.<p>


De Lüüd hebbt <b>$3</b>× Sieten oprufen, un <b>$4</b>× Sieten ännert.
Dorut ergeven sik <b>$5</b> Ännern pro Siet, un <b>$6</b> Ankieken pro Ännern.',
'userstatstext'       => 'Dat gifft <b>$1</b> registreert Bruker.
Dorvun hebbt <b>$2</b> Administrater-Rechten (kiek $3).',

# Maintenance Page
#
'disambiguations'               => 'Begreepklorensieten',
'disambiguationspage'        => '{{ns:4}}:Begreepkloren',
'disambiguationstext'           => 'Disse Sieten wiest no en <i>Begreepklorensiet</i>. Se schallen staats dat no de Siet wiesen, de egentlich meent is.<br />En Siet warrt as Begreepklorensiet ansehn, wenn $1 op se verwiest.<br />Verwiesen ut Naamrüüm sünd hier <i>nich</i> oplist.',
'doubleredirects'                  => 'Dubbelte Wiederleiden',
'doubleredirectstext'        => '<b>Wohrscho:</b> Disse List kann „falsche Positive“ bargen.
Dat passeert denn, wenn en Wiederleiden blangen de Wiederleiden-Verwies noch mehr Text mit annere Verwiesen hett.
De schallen denn löscht warrn. Elk Reeg wiest de eerste un tweete Wiederleiden un de eerste Reeg Text ut de Siet,
to den vun den tweeten Wiederleiden wiest warrt, un to den de eerste Wiederleiden mehrst wiesen schall.',
'brokenredirects'            => 'Kaputte Wiederleiden',
'brokenredirectstext'          => 'Disse Wiederleiden wiesen to en Siet, de nich existeert',


# Verscheden Spezialsieten
#
'lonelypages'                   => 'Weetsieten',
'uncategorizedpages'        => 'Unkategoriseerte Sieten',
'uncategorizedcategories'       => 'Unkategoriseerte Kategorien',
'unusedimages'                  => 'Weetbiller',
'popularpages'                  => 'Faken opropene Sieten',
'nviews'                            => '$1 Affragen',
'wantedpages'                   => 'Wünschte Sieten',
'nlinks'                                => '$1 Verwies',
'allpages'                            => 'Alle Sieten',
'randompage'                    => 'Tofällige Siet',
'shortpages'                 => 'Kotte Sieten',
'longpages'                     => 'Lange Sieten',
'listusers'                          => 'Brukerlist',
'specialpages'                  => 'Spezialsieten',
'spheading'                             => 'Spezialsieten för alle Bruker',
'recentchangeslinked'     => 'Verlinkte Sieten',
'rclsub'                                => '(op Artikel vun „$1“)',
'newpages'                              => 'Niege Sieten',
'ancientpages'            => 'Öllste Sieten',
'move'                    => 'Schuven',
'movethispage'                  => 'Siet schuven',
'unusedimagestext'        => 'Denk doran, dat annere Wikis mööglicherwies en poor vun disse Biller bruken.',
'booksources'               => 'Bookhannel',
'categoriespagetext'      => 'Disse Kategorien existeern in dissen Wiki',
'data'                     => 'Daten',
'booksourcetext'          => 'Dit is en List mit Links to Internetsieten, de niege un bruukte Böker verkööpt.
Dor kann dat ok mehr Informatschonen över de Böker geven, de di interesseert.
{{SITENAME}} is mit kenen vun disse Höker warflich verbunnen.',
'alphaindexline'          => '$1 bet $2',
'log'                           => 'Logböker',
'alllogstext'               => 'Kombineerte Ansicht vun Hoochlaad-, Lösch-, Schuul-, Block- un Sysop-Logböker.
Du kannst de List kötter maken, wenn du den Logtyp, den Brukernaam oder de de Siet angiffst.',


# E-Mail an'n Bruker
#
'mailnologin'             => 'Du büst nich anmellt.',
'mailnologintext'       => 'Du muttst [[Spezial:Userlogin|anmellt ween]] un en güllige E-Mail-Adress hebben, dormit du en annern Bruker en E-Mail sennen kannst.',
'emailuser'                     => 'E-Mail an dissen Bruker',
'emailpage'                     => 'E-Mail an Bruker',
'emailpagetext'         => 'Wenn disse Bruker en güllige E-Mail-Adress angeven hett, kannst du em mit den nerrn stahn Formular en E-Mail sennen. As Afsenner warrt de E-Mail-Adress ut dien Instellen indregen, dormit de Bruker di antern kann.',
'usermailererror'       => 'Dat Mail-Objekt hett en Fehler trüchgeven:',
'defemailsubject'       => '{{SITENAME}} E-Mail',
'noemailtitle'          => 'Kene E-Mail-Adress',
'noemailtext'             => 'Disse Bruker hett kene güllige E-Mail-Adress angeven, oder will kene E-Mail vun annere Bruker sennt kriegen.',
'emailfrom'                 => 'Vun',
'emailto'                       => 'An',
'emailsubject'          => 'Bedrap',
'emailmessage'                 => 'Noricht',
'emailsend'                     => 'Sennen',
'emailsent'                        => 'E-Mail afsennt',
'emailsenttext'         => 'Dien E-Mail is afsennt worrn.',

# Special:Allpages
'nextpage'          => 'tokamen Siet ($1)',
'allarticles'       => 'Alle Artikels',
'allpagesprev'      => 'vörig',
'allpagesnext'      => 'tokamen',
'allinnamespace' => 'Alle Sieten ($1 Naamruum)',
'allpagessubmit'    => 'Los',

# Oppasslist
#
'watchlist'                     => 'Oppasslist',
'nowatchlist'           => 'Du hest kene Indreeg op dien Oppasslist.',
'watchnologin'            => 'Du büst nich anmellt',
'watchnologintext'      => 'Du muttst [[Spezial:Userlogin|anmellt]] ween, wenn du dien Oppasslist ännern willst.',
'addedwatch'            => 'To de Oppasslist hentofögt',
'addedwatchtext'        => 'De Siet „$1“ is to dien <a href=\'{{localurle:Spezial:Watchlist}}\'>Oppasslist</a> hentofögt worrn.
Ännern, de in Tokumst an disse Siet un an de toheurige Diskuschoonssiet mookt warrn, sünd dorop list un de Siet is op de
<a href=\'{{localurle:Spezial:Recentchanges}}\'>List vun de letzten Ännern</a> fett markeert. Wenn du de Siet nich mehr op dien Oppasslist
hebben wullst, klick op „Nich mehr oppassen“ in de Linklist.',
'removedwatch'           => 'De Siet is nich mehr op de Oppasslist',
'removedwatchtext'      => 'De Siet „$1“ is nich mehr op de Oppasslist.',
'watchthispage'         => 'Op Siet oppassen',
'unwatchthispage'       => 'Nich mehr oppassen',
'notanarticle'           => 'Keen Artikel',
'watchnochange'         => 'Kene Siet, op de du oppasst, is in den wiesten Tiedruum bearbeid worrn.',
'watchdetails'          => '($1 Sieten sünd op de Oppasslist (ahn Diskuschoonssieten);
$2 Sieten werrn in de instellte Tied bearbeid;
$3... [$4 komplette List wiesen un bearbeiden].)',
'watchmethod-recent'    => 'letzte Ännern no Oppasslist pröven',
'watchmethod-list'      => 'Oppasslist no letzte Ännern pröven',
'removechecked'         => 'Markeerte Indreeg löschen',
'watchlistcontains'     => 'Dien Oppasslist bargt $1 Sieten.',
'watcheditlist'         => 'Hier is ene alphabetsche List vun de Sieten op de du oppasst. Markeer de Sieten, de vun de Oppasslist löscht warrn schallt un klick den \'markeerte Indreeg löschen\'-Knoop.',
'removingchecked'       => 'Indreeg warrt vun de Oppasslist löscht...',
'couldntremove'         => 'De Indrag \'$1\' kann nich löscht warrn...',
'iteminvalidname'       => 'Problem mit den Indrag \'$1\', ungülligen Naam...',
'wlnote'                => 'Nerrn steiht de letzten Ännern vun de letzten <b>$2</b> Stünnen.',
'wlshowlast'            => 'Wies de letzten $1 Stünnen $2 Daag $3',
'wlsaved'                               => 'Dit is en spiekerte Version vun dien Oppasslist.',

# löschen/schulen/trüchsetten
#
'deletepage'               => 'Siet löschen',
'confirm'                            => 'Bestätigen',
'excontent'             => "Olen Inholt: '$1'",
'exbeforeblank'         => "Inholt vör dat Leddigmaken vun de Siet: '$1'",
'exblank'               => 'Siet weer leddig',
'confirmdelete'         => 'Löschen bestätigen',
'deletesub'                     => '(Lösche „$1“)',
'historywarning'        => 'Wohrscho: De Siet, de du versöökst to löschen, hett en Versionshistorie:',
'confirmdeletetext'     => 'Du büst dorbi, en Siet oder en Bild un alle ölleren Versionen duersam ut de Datenbank to löschen.
Segg to, dat du över de Folgen Bescheed weetst un dat du in Övereenstimmen mit uns [[{{ns:4}}:Leidlienen|Leidlienen]] hannelst.',
'actioncomplete'        => 'Aktschoon beennt',
'deletedtext'           => '„$1“ is löscht.
In $2 kannst du en List vun de letzten Löschen finnen.',
'deletedarticle'        => '„$1“ löscht',
'dellogpage'            => 'Lösch-Logbook',
'dellogpagetext'        => 'Hier is en List vun de letzten Löschen (UTC).

<ul>

</ul>',
'deletionlog'             => 'Lösch-Logbook',
'reverted'                          => 'Op en ole Version trüchsett',
'deletecomment'         => 'Grund vun de Löschen',
'imagereverted'         => 'Op en ole Version trüchsett.',
'rollback'              => 'Trüchnahm vun de Ännern',
'rollback_short'        => 'Trüchnehmen',
'rollbacklink'          => 'Trüchnehmen',
'rollbackfailed'        => 'Trüchnahm hett kenen Spood',
'cantrollback'          => 'De Ännern kann nich trüchnahmen warrn; de letzte Autor is de eenzige.',
'alreadyrolled'         => 'Dat Trüchnehmen vun de Ännern an de Siet [[:$1]] vun [[User:$2|$2]]
([[User_talk:$2|Diskuschoonssiet]]) is nich mööglich, vun wegen dat dor en annere Ännern oder Trüchnahm ween is.

De letzte Ännern is vun [[User:$3|$3]]
([[User talk:$3|Diskuschoon]])',
#   blots wiesen wenn dor en Ännerkommentar is
'editcomment'           => 'De Ännerkommentar weer: <i>$1</i>.',
'revertpage'            => 'Weerholt to de letzte Ännern vun $1',

# Weerholen
'undelete'              => 'Löschte Siet weerholen',
'undeletepage'          => 'Löschte Sieten weerholen',
'undeletepagetext'      => 'Disse Sieten sünd löscht worrn, aver jümmer noch
spiekert un könnt weerholt warrn.',
'undeletearticle'       => 'Löschte Siet weerholen',
'undeleterevisions'     => '$1 Versionen archiveert',
'undeletehistory'       => 'Wenn du disse Siet weerholst, warrt ok alle olen Versionen weerholt. Wenn siet dat Löschen en nieg Siet mit lieken
Naam schreven worrn is, warrt de weerholten Versionen as ole Versionen vun disse Siet wiest.',
'undeleterevision'      => 'Löschte Version vun de $1',
'undeletebtn'           => 'Weerholen!',
'undeletedarticle'      => '„$1“ weerholt',

# Bidreeg
#
'contributions'         => 'Brukerbidreeg',
'mycontris'             => 'Mien Bidreeg',
'contribsub'               => 'För $1',
'nocontribs'               => 'Kene Ännern för disse Kriterien funnen.',
'ucnote'                       => 'Dit sünd de letzten <b>$1</b> Bidreeg vun de Bruker in de letzten <b>$2</b> Doog.',
'uclinks'                          => 'Wies de letzten $1 Bidreeg; wies de letzten $2 Daag.',
'uctop'                         => ' (aktuell)',
'newbies'               => 'Niegling',

# Wat wiest hier hen
#
'whatlinkshere'         => 'Wat wiest hierher',
'notargettitle'         => 'Kene Siet angeven',
'notargettext'           => 'Du hest nich angeven, op welke Siet du disse Funktschoon anwennen willst.',
'linklistsub'             => '(List vun de Verwiesen)',
'linkshere'                     => 'Disse Sieten wiesen hierher:',
'nolinkshere'             => 'Kene Siet wiest hierher.',
'isredirect'               => 'Wiederleiden',

# Blocken/nich mehr blocken vun IPs
#
'blockip'                            => 'IP-Adress blocken',
'blockiptext'           => 'Bruuk dat Formular, üm en IP-Adress to blocken.
Dit schall blots maakt warrn, üm Vandalismus to vermasseln, aver jümmer in Övereenstimmen mit uns [[{{ns:4}}:Leidlienen|Leidlienen]].
Ok den Grund för dat Blocken indregen.',
'ipaddress'                     => 'IP-Adress',
'ipbreason'                     => 'Grund',
'ipbsubmit'                        => 'Adress blocken',
'badipaddress'          => 'De IP-Adress hett en falsch Format.',
'blockipsuccesssub'     => 'Blocken hett Spood',
'blockipsuccesstext'    => 'De IP-Adress „$1“ is nu blockt.

<br />Op de [[Special:Ipblocklist|IP-Blocklist]] is en List vun alle Blocks to finnen.',
'unblockip'                     => 'IP-Adress freegeven',
'unblockiptext'              => 'Bruuk dat Formular, üm en blockte IP-Adress freetogeven.',
'ipusubmit'                     => 'Disse Adress freegeven',
'ipblocklist'           => 'List vun blockte IP-Adressen',
'blocklistline'              => '$1, $2 hett $3 blockt ($4)',
'blocklink'                     => 'blocken',
'unblocklink'             => 'freegeven',
'contribslink'           => 'Bidreeg',
'autoblocker'           => 'Automatisch Block, vun wegen dat du en IP-Adress bruukst mit „$1“. Grund: „$2“.',

# Entwickler-Warktüüch
#
'lockdb'                        => 'Datenbank sparren',
'unlockdb'                          => 'Datenbank freegeven',
'lockdbtext'               => 'Mit de Sparr vun de Datenbank warrt alle Ännern an de Brukerinstellen, Oppasslisten, Sieten un so wieder verhinnert.
Schall de Datenbank redig sparrt warrn?',
'unlockdbtext'          => 'Dat Beennen vun de Datenbank-Sparr maakt alle Ännern weer mööglich.
Schall de Datenbank-Sparr redig beennt warrn?',
'lockconfirm'           => 'Ja, ik will de Datenbank sparren.',
'unlockconfirm'         => 'Ja, ik will de Datenbank freegeven.',
'lockbtn'                            => 'Datenbank sparren',
'unlockbtn'                        => 'Datenbank freegeven',
'locknoconfirm'         => 'Du hest dat Bestätigungsfeld nich markeert.',
'lockdbsuccesssub'      => 'Datenbanksparr hett Spood',
'unlockdbsuccesssub'    => 'Datenbankfreegaav hett Spood',
'lockdbsuccesstext'     => 'De {{SITENAME}}-Datenbank is sparrt.
<br />Du muttst de Datenbank weer freegeven, wenn de Pleegarbeiden beennt sünd.',
'unlockdbsuccesstext'   => 'De {{SITENAME}}-Datenbank is weer freegeven.',

# Siet schuven
#
'movepage'                           => 'Siet schuven',
'movepagetext'              => 'Mit dissen Formular kannst du en Siet ümnömen, tosamen mit allen Versionen. De ole Titel warrt to den niegen wiederleid. Verwies op den olen Titel warrn nich ännert un de Diskuschoonssiet warrt ok nich mitschuven.',
'movepagetalktext'      => "De tohören Diskuschoonssiet warrt, wenn een dor is, mitschuuvt, '''mit disse Utnahmen:''
* Du schuuvst de Siet in en annern Naamruum oder
* dat existeert al en Diskuschoonssiet mit dissen Naam, oder
* du wählst de nerrn stahn Optschoon af

In disse Fäll muttst du de Siet, wenn du dat wullst, vun Hand schuven.",
'movearticle'           => 'Siet schuven',
'movenologin'           => 'Du büst nich anmellt',
'movenologintext'       => 'Du muttst en registreert Bruker un
[[Special:Userlogin|anmellt]] ween,
üm en Siet to schuven.',
'newtitle'                     => 'To niegen Titel',
'movepagebtn'             => 'Siet schuven',
'pagemovedsub'              => 'Schuven hett Spood',
'pagemovedtext'         => 'Siet „[[$1]]“ no „[[$2]]“ schuuvt.',
'articleexists'         => 'Ünner dissen Naam existeert al en Siet.
Bitte wähl en annern Naam.',
'talkexists'            => 'Dat Schuven vun de Siet sülvst hett Spood, aver dat Schuven vun de
Diskuschoonssiet nich, vun wegen dat dor al en Siet mit dissen Titel existeert. De Inholt muss vun Hand anpasst warrn.',
'movedto'                       => 'schuven no',
'1movedto2_redir'       => '$1 schuven no $2 över Wiederleiden',
'movetalk'                   => 'De Diskuschoonssiet ok schuven, wenn mööglich.',
'talkpagemoved'         => 'De Diskuschoonssiet is ok schuven worrn.',
'talkpagenotmoved'      => 'De Diskuschoonssiet is <strong>nich</strong> schuven worrn.',

'export'                => 'Sieten exporteern',
'exporttext'            => 'Du kannst de Text un de Bearbeidenshistorie vun een oder mehr Sieten no XML exporteern. Dat Resultat kann in en annern Wiki mit Mediawiki-Software inspeelt warrn, bearbeid oder archiveert warrn.',
'exportcuronly'         => 'Blots de aktuelle Version vun de Siet exporteern',
'missingimage'          => '<b>Bild fehlt</b><br /><i>$1</i>',

#Tooltips:
'tooltip-watch'         => 'Op disse Siet oppassen.',
'tooltip-search'        => 'Söken',
'tooltip-minoredit'     => 'Disse Ännern as lütt markeern.',
'tooltip-save'          => 'Ännern spiekern',
'tooltip-preview'       => 'Vörschau vun de Ännern an disse Siet. Bruuk dat vör dat Spiekern.',
'tooltip-compareselectedversions' => 'Ünnerscheed twüschen twee utwählte Versionen vun disse Siet verglieken.',

#Tastatur-Shortcuts
'accesskey-search'                  => 'f',
'accesskey-minoredit'               => 'i',
'accesskey-save'                    => 's',
'accesskey-preview'                 => 'p',
'accesskey-compareselectedversions' => 'v',

'makesysoptitle'        => 'Maak en Bruker to en Administrater',
'makesysoptext'         => 'Disse Mask warrt vun Bürokraten bruukt, üm normale Bruker to Administratern to maken.',
'makesysopname'         => 'Naam vun de Bruker:',
'makesysopsubmit'       => 'Maak dissen Bruker to en Administrater',
'makesysopok'           => '<b>Bruker „$1“ is nu en Administrater.</b>',
'makesysopfail'         => '<b>Bruker „$1“ kunn nich to en Administrater maakt warrn. (Is de Naam richtig schreven?)</b>',
'makesysop'             => 'Maak en Bruker to en Administrater',
'setbureaucratflag'     => 'Bürokraten-Flagg setten',
'rights'                        => 'Rechten:',
'set_user_rights'             => 'Brukerrechten setten',
'user_rights_set'       => '<b>Brukerrechten för „$1“ aktualiseert</b>',
'set_rights_fail'             => '<b>Brukerrechten för „$1“ kunnen nich sett warrn. (Is de Naam richtig schreven?)</b>',
'1movedto2'                        => '$1 is no $2 schuven worrn',
'allmessages'                    => 'Alle MediaWiki-Norichten',
'allmessagestext'             => 'Dit is en List vun alle mööglichen Norichten in den MediaWiki-Naamruum.',
'thumbnail-more'               => 'vergröttern',
'and'                                   => 'un',
'uploaddisabled'               => 'Dat Hoochladen is deaktiveert.',
'deadendpages'                  => 'Sackstraatsieten',
'intl'                          => 'Interwiki-Links',
'version'                            => 'Version',
'protectlogpage'               => 'Sietenschuul-Logbook',
'protectlogtext'        => 'Dit is en List vun de blockten Sieten. Kiek [[{{ns:4}}:Schulte Sieten]] för mehr Informatschonen.',
'protectedarticle'      => 'Siet $1 schuult',
'unprotectedarticle'    => 'Siet $1 freegeven',
'protectsub'            =>'(Sparren vun „$1“)',
'confirmprotecttext'    => 'Schall disse Siet redig schuult warrn?',
'ipbexpiry'                        => 'Aflöptied',
'blocklogpage'                  => 'Brukerblock-Logbook',
'blocklogentry'         => 'block [[User:$1]] - ([[Special:Contributions/$1|Bidreeg]]) för en Tiedruum vun: $2',
'blocklogtext'                  => 'Dit is en Logbook över Blocks un Freegaven vun Brukern. Automatisch blockte IP-Adressen sünd nich opföhrt.
Kiek [[Special:Ipblocklist|IP-Blocklist]] för en List vun den blockten Brukern.',
'unblocklogentry'             => 'Block vun [[User:$1]] ophoven',
'range_block_disabled'   => 'De Mööglichkeit, ganze Adressrüüm to sparren, is nich aktiveert.',
'ipb_expiry_invalid'       => 'De angeven Aflöptied is ungüllig.',
'ip_range_invalid'           => 'Ungüllig IP-Addressrebeet.',
'confirmprotect'        => 'Sparr bestätigen',
'protectcomment'        => 'Grund för de Sparr',
'unprotectsub'                  => '(Beennen vun de Sparr vun „$1“)',
'confirmunprotecttext'  => 'Schall de Sparr vun disse Siet redig beennt warrn?',
'confirmunprotect'           => 'De Sparr beennen',
'unprotectcomment'           => 'Grund för dat Beennen vun de Sparr',
'proxyblocker'          => 'Proxyblocker',
'proxyblockreason'      => 'Dien IP-Adress is blockt, vun wegen dat se en apenen Proxy is.
Kontakteer dien Provider oder diene Systemtechnik un informeer se över dat möögliche Sekerheitsproblem.',
'proxyblocksuccess'     => 'Fardig.',
'math_image_error'           => 'dat Konverteern no PNG hett kenen Spood.',
'math_bad_tmpdir'       => 'Kann dat Temporärverteken för mathematsche Formeln nich anleggen oder beschrieven.',
'math_bad_output'             => 'Kann dat Teelverteken för mathematsche Formeln nich anleggen oder beschrieven.',
'math_notexvc'                  => 'Dat texvc-Programm kann nich funnen warrn. Kiek ok math/README.',
'prefs-personal'        => 'Brukerdaten',
'prefs-rc'              => 'Letzte Ännern un Wiesen vun kotte Sieten',
'prefs-misc'            => 'Verscheden Instellen',
'import'                => 'Sieten importeern',
'importtext'            => 'Exporteer de Siet vun dat Utgangswiki mit Special:Export un laad de Datei denn över disse Siet weer hooch.',
'importfailed'          => 'Import hett kenen Spood: $1',
'importnotext'          => 'Leddig oder keen Text',
'importsuccess'                => 'Import hett Spood!',
'importhistoryconflict' => 'Dor sünd al öllere Versionen, de mit dissen kollideert. (Mööglicherwies is de Siet al vörher importeert worrn)',
'isbn'                                  => 'ISBN',
'siteuser'              => '{{SITENAME}}-Bruker $1',
'siteusers'             => '{{SITENAME}}-Bruker $1',
'watch'                 => 'Oppassen',
'unwatch'               => 'nich mehr oppassen',
'edit'                  => 'ännern',
'talk'                  => 'Diskuschoon',
'nocookiesnew'          => 'De Brukertogang is anleggt, aver du büst nich inloggt. {{SITENAME}} bruukt för disse Funktschoon Cookies, aktiveer de Cookies un logg di denn mit dien nieg Brukernaam un den Password in.',
'nocookieslogin'        => '{{SITENAME}} bruukt Cookies för dat Inloggen vun de Bruker. Du hest Cookies deaktiveert, aktiveer de Cookies un versöök dat noch eenmal.',
'subcategorycount'      => 'Disse Kategorie hett $1 Ünnerkategorien.',
'categoryarticlecount'  => 'To disse Kategorie höört $1 Sieten.',

# Math
'mw_math_png'           => 'Jümmer as PNG dorstellen',
'mw_math_simple'        => 'Eenfach TeX as HTML dorstellen, sünst PNG',
'mw_math_html'          => 'Wenn mööglich as HTML dorstellen, sünst PNG',
'mw_math_source'        =>'As TeX laten (för Textbrowser)',
'mw_math_modern'        => 'Anratenswert för moderne Browser',
'mw_math_mathml'        => 'MathML (experimentell)',

# Infosiet
'infosubtitle'          => 'Informatschonen för de Siet',
'numedits'              => 'Antall vun Ännern (Siet): $1',
'numtalkedits'          => 'Antall vun Ännern (Diskuschoonssiet): $1',
'numwatchers'           => 'Antall vun Oppassers: $1',
'numauthors'            => 'Antall vun verschedene Autoren (Siet): $1',
'numtalkauthors'        => 'Antall vun verschedene Autoren (Diskuschoonssiet): $1',

# Tooltip help for some actions, most are in Monobook.js
'tooltip-search'        => 'In dissen Wiki söken',
'tooltip-minoredit'     => 'Dit as en lütt Ännern markeern',
'tooltip-save'          => 'Dien Ännern spiekern',
'tooltip-preview'       => 'Vörschau för dien Ännern, bruuk dat vör dat Spiekern.',
'tooltip-compareselectedversions' => 'De Ünnerscheed twüschen de twee wählten Versionen vun disse Siet ankieken.',

# Stilvörlagen

'monobook.css'          => '/* disse Datei editeern üm den Monobook-Skin för de ganze Siet antopassen */',
#'monobook.js' => '/* Deprecated; use [[MediaWiki:common.js]] */',

# Monobook.js: tooltips and access keys for monobook
'monobook.js' => '/* Deprecated; use [[MediaWiki:common.js]] */',

'accesskey-pt-userpage' => '.',
'tooltip-pt-userpage' => 'Mien Brukersiet',
'accesskey-pt-anonuserpage' => '.',
'tooltip-pt-anonuserpage' => 'De Brukersiet för de IP-Adress ünner de du schriffst',
'accesskey-pt-mytalk' => 'n',
'tooltip-pt-mytalk' => 'Mien Diskuschoonssiet',
'accesskey-pt-anontalk' => 'n',
'tooltip-pt-anontalk' => 'Diskuschoon över Ännern vun disse IP-Adress',
'accesskey-pt-preferences' => '',
'tooltip-pt-preferences' => 'Mien Instellen',
'accesskey-pt-watchlist' => 'l',
'tooltip-pt-watchlist' => 'Mien Oppasslist',
'accesskey-pt-mycontris' => 'y',
'tooltip-pt-mycontris' => 'List vun mien Bidreeg',
'accesskey-pt-login' => 'o',
'tooltip-pt-login' => 'Du kannst di geern anmellen, dat is aver nich neudig, üm Sieten to bearbeiden.',
'accesskey-pt-anonlogin' => 'o',
'tooltip-pt-anonlogin' => 'Du kannst di geern anmellen, dat is aver nich neudig, üm Sieten to bearbeiden.',
'accesskey-pt-logout' => '',
'tooltip-pt-logout' => 'Afmellen',
'accesskey-ca-talk' => 't',
'tooltip-ca-talk' => 'Diskuschoon över disse Siet',
'accesskey-ca-edit' => 'e',
'tooltip-ca-edit' => 'Du kannst disse Siet ännern. Bruuk dat vör dat Spiekern.',
'accesskey-ca-addsection' => '+',
'tooltip-ca-addsection' => 'En Kommentar to disse Diskuschoonssiet hentofögen.',
'accesskey-ca-viewsource' => 'e',
'tooltip-ca-viewsource' => 'Disse Siet is schuult. Du kannst den Borntext ankieken.',
'accesskey-ca-history' => 'h',
'tooltip-ca-history' => 'Historie vun disse Siet.',
'accesskey-ca-protect' => '=',
'tooltip-ca-protect' => 'Disse Siet schulen',
'accesskey-ca-delete' => 'd',
'tooltip-ca-delete' => 'Disse Siet löschen',
'accesskey-ca-undelete' => 'd',
'tooltip-ca-undelete' => 'Weerholen vun de Siet, so as se vör dat löschen ween is',
'accesskey-ca-move' => 'm',
'tooltip-ca-move' => 'Disse Siet schuven',
'accesskey-ca-watch' => 'w',
'tooltip-ca-watch' => 'Disse Siet to de Oppasslist hentofögen',
'accesskey-ca-unwatch' => 'w',
'tooltip-ca-unwatch' => 'Disse Siet vun de Oppasslist löschen',
'accesskey-search' => 'f',
'tooltip-search' => 'In dissen Wiki söken',
'accesskey-p-logo' => '',
'tooltip-p-logo' => 'Hööftsiet',
'accesskey-n-mainpage' => 'z',
'tooltip-n-mainpage' => 'Besöök de Hööftsiet',
'accesskey-n-portal' => '',
'tooltip-n-portal' => 'över dat Projekt, wat du doon kannst, woans du de Saken finnen kannst',
'accesskey-n-currentevents' => '',
'tooltip-n-currentevents' => 'Achtergrünn to aktuellen Schehn finnen',
'accesskey-n-recentchanges' => 'r',
'tooltip-n-recentchanges' => 'List vun de letzten Ännern in dissen Wiki.',
'accesskey-n-randompage' => 'x',
'tooltip-n-randompage' => 'Tofällige Siet',
'accesskey-n-help' => '',
'tooltip-n-help' => 'Hier kriegst du Hülp.',
'accesskey-n-sitesupport' => '',
'tooltip-n-sitesupport' => 'Gaven',
'accesskey-t-whatlinkshere' => 'j',
'tooltip-t-whatlinkshere' => 'Wat wiest hierher',
'accesskey-t-recentchangeslinked' => 'k',
'tooltip-t-recentchangeslinked' => 'Verlinkte Sieten',
'accesskey-feed-rss' => '',
'tooltip-feed-rss' => 'RSS-Feed för disse Siet',
'accesskey-feed-atom' => '',
'tooltip-feed-atom' => 'Atom-Feed för disse Siet',
'accesskey-t-contributions' => '',
'tooltip-t-contributions' => 'List vun de Bidreeg vun dissen Bruker',
'accesskey-t-emailuser' => '',
'tooltip-t-emailuser' => 'En E-Mail an dissen Bruker sennen',
'accesskey-t-upload' => 'u',
'tooltip-t-upload' => 'Biller oder Mediendatein hoochladen',
'accesskey-t-specialpages' => 'q',
'tooltip-t-specialpages' => 'List vun alle Spezialsieten',
'accesskey-ca-nstab-main' => 'c',
'tooltip-ca-nstab-main' => 'Siet ankieken',
'accesskey-ca-nstab-user' => 'c',
'tooltip-ca-nstab-user' => 'Brukersiet ankieken',
'accesskey-ca-nstab-media' => 'c',
'tooltip-ca-nstab-media' => 'Mediensiet ankieken',
'accesskey-ca-nstab-special' => '',
'tooltip-ca-nstab-special' => 'Dit is en Spezialsiet, du kannst disse Siet nich ännern.',
'accesskey-ca-nstab-project' => 'a',
'tooltip-ca-nstab-project' => 'Portalsiet ankieken',
'accesskey-ca-nstab-image' => 'c',
'tooltip-ca-nstab-image' => 'Bildsiet ankieken',
'accesskey-ca-nstab-mediawiki' => 'c',
'tooltip-ca-nstab-mediawiki' => 'Systemnorichten ankieken',
'accesskey-ca-nstab-template' => 'c',
'tooltip-ca-nstab-template' => 'Vörlaag ankieken',
'accesskey-ca-nstab-help' => 'c',
'tooltip-ca-nstab-help' => 'Hülpsiet ankieken',
'accesskey-ca-nstab-category' => 'c',
'tooltip-ca-nstab-category' => 'Kategoriesiet ankieken',

# Billerlöschen
'deletedrevision' => 'Löschte ole Version $1.',

# Ünnerscheed ankieken
'previousdiff'                          => '← Geih to den vörigen Ünnerscheed',
'nextdiff'                              => 'Geih to den tokamen Ünnerscheed →',

'imagemaxsize'                          => 'Biller op de Bildbeschrievensiet begrenzen op:',
'showbigimage'                          => 'Version mit högere Oplösen dolladen ($1x$2, $3 KB)',

'newimages'                             => 'Galeree vun niege Biller',


# Schalttafel

'editusergroup'                         => 'Brukergruppen bearbeiden',


# Brukergruppen bearbeiden
'saveusergroups'                        => 'Brukergruppen spiekern',

# Metadata
'nodublincore'                          => 'Dublin-Core-RDF-Metadaten sünd för dissen Server nich aktiveert.',
'nocreativecommons'                     => 'Creative-Commons-RDF-Metadaten sünd för dissen Server nich aktiveert.',
'notacceptable'                         => 'Dat Wiki-Server kann kene Daten in enen Format levern, dat dien Klient lesen kann.',

# Attributschoon

'anonymous'                             => 'Anonyme Bruker vun {{SITENAME}}',
'siteuser'                              => '{{SITENAME}}-Bruker $1',
'lastmodifiedatby'                        => 'Disse Siet weer dat letzte Maal $2, $1 vun $3 ännert.',
'othercontribs'                         => 'Grünnt op Arbeid vun $1.',
'others'                                => 'annere',
'siteusers'                             => '{{SITENAME}}-Bruker $1',
'creditspage'                           => 'Sieten-Autoren',
'nocredits'                             => 'Dor is keen Autorenlist för disse Siet verfögbor.',

# Spamschild

'spamprotectiontitle'                   => 'Spamschild',
'spamprotectiontext'                    => 'De Siet, de du spiekern wullst, weer vun de Spamschild blockt. Dat kann vun en Link to en externe Siet kamen.',
'spamprotectionmatch'                   => 'Dit Text hett den Spamschild utlöst: $1',
'listingcontinuesabbrev'                => ' wieder',

# Patrolleern
'markaspatrolleddiff'       => 'As patrolleert markeern',
'markaspatrolledtext'       => 'Disse Siet as patrolleert markeern',
'markedaspatrolled'         => 'As patrolleert markeert',
'markedaspatrolledtext'     => 'Disse Revision is as patrolleert markeert.',
'rcpatroldisabled'          => 'Letzte-Ännern-Patrol nich aktiveert',
'rcpatroldisabledtext'      => 'De Letzte-Ännern-Patrol-Funktschoon is in\'n Momang nich aktiveert.',

# Naamruum 8

'allmessages'                   => 'Alle Systemnorichten',
'allmessagestext'                 => 'Dit is en List vun alle Systemnorichten, de in de MediaWiki:-Naamruum verfögbor sünd.',
'allmessagesnotsupportedUI' => 'Dien aktuelle Snittstellenspraak <b>$1</b> warrt vun Special:AllMessages op disse Steed nich ünnerstütt.',
'allmessagesnotsupportedDB' => 'Special:AllMessages is nich ünnerstütt, vun wegen dat wgUseDatabaseMessages utstellt is.',

);


?>
