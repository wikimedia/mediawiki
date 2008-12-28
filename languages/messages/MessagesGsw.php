<?php
/** Swiss German (Alemannisch)
 *
 * @ingroup Language
 * @file
 *
 * @author Als-Holder
 * @author Hendergassler
 * @author J. 'mach' wust
 * @author Melancholie
 * @author MichaelFrey
 * @author Spacebirdy
 * @author Strommops
 * @author Urhixidur
 * @author לערי ריינהארט
 * @author 80686
 */

$fallback = 'de';

$specialPageAliases = array(
	'DoubleRedirects'           => array( 'Doppleti Wyterlaitige' ),
	'BrokenRedirects'           => array( 'Kaputti Wyterlaitige' ),
	'Disambiguations'           => array( 'Begriffschlärigsverwyys' ),
	'Userlogin'                 => array( 'Amälde' ),
	'Userlogout'                => array( 'Abmälde' ),
	'CreateAccount'             => array( 'Benutzerchonto aaleege' ),
	'Preferences'               => array( 'Ystellige' ),
	'Watchlist'                 => array( 'Beobachtigslischte' ),
	'Recentchanges'             => array( 'Letschti Änderige' ),
	'Upload'                    => array( 'Uffelade' ),
	'Imagelist'                 => array( 'Dateie' ),
	'Newimages'                 => array( 'Neji Dateie' ),
	'Listusers'                 => array( 'Benutzerlischte' ),
	'Listgrouprights'           => array( 'Grupperächt' ),
	'Statistics'                => array( 'Statischtik' ),
	'Randompage'                => array( 'Zuefelligi Syte' ),
	'Lonelypages'               => array( 'Verwaisti Syte' ),
	'Uncategorizedpages'        => array( 'Syte', 'wo nit kategorisiert sin' ),
	'Uncategorizedcategories'   => array( 'Kategorie', 'wo nit kategorisiert sin' ),
	'Uncategorizedimages'       => array( 'Dateie', 'wo nit kategorisiert sin' ),
	'Uncategorizedtemplates'    => array( 'Vorlage', 'wo nit kategorisiert sin' ),
	'Unusedcategories'          => array( 'Kategorie', 'wo nit brucht wäre' ),
	'Unusedimages'              => array( 'Dateie', 'wo nit brucht wäre' ),
	'Wantedpages'               => array( 'Syte', 'wo gwinscht sin' ),
	'Wantedcategories'          => array( 'Kategorie', 'wo gwinscht sin' ),
	'Wantedfiles'               => array( 'Dateie', 'wo fähle' ),
	'Wantedtemplates'           => array( 'Vorlage', 'wo fähle' ),
	'Mostlinked'                => array( 'Syte', 'wo am meischte vergleicht sin' ),
	'Mostlinkedcategories'      => array( 'Kategorie', 'wo am meischte brucht wäre' ),
	'Mostlinkedtemplates'       => array( 'Vorlage', 'wo am meischte brucht wäre' ),
	'Mostimages'                => array( 'Dateie', 'wo am meischte brucht wäre' ),
	'Mostcategories'            => array( 'Syte', 'wo am meischte kategorisiert sin' ),
	'Mostrevisions'             => array( 'Syte', 'wo am meischte bearbeitet sin' ),
	'Fewestrevisions'           => array( 'Syte', 'wo am wenigschte bearbeitet sin' ),
	'Shortpages'                => array( 'Churzi Syte' ),
	'Longpages'                 => array( 'Langi Syte' ),
	'Newpages'                  => array( 'Neji Syte' ),
	'Ancientpages'              => array( 'Veralteti Syte' ),
	'Deadendpages'              => array( 'Sackgassesyte' ),
	'Protectedpages'            => array( 'Gschitzti Syte' ),
	'Protectedtitles'           => array( 'Gsperrti Titel' ),
	'Allpages'                  => array( 'Alli Syte' ),
	'Prefixindex'               => array( 'Vorsilbeverzeichnis' ),
	'Ipblocklist'               => array( 'Gsperrti IP' ),
	'Specialpages'              => array( 'Spezialsyte' ),
	'Contributions'             => array( 'Byytreeg' ),
	'Emailuser'                 => array( 'E-Mail' ),
	'Confirmemail'              => array( 'E-Mail bstetige' ),
	'Whatlinkshere'             => array( 'Was gleicht do ane?' ),
	'Recentchangeslinked'       => array( 'Änderige an vergleichte Syte' ),
	'Movepage'                  => array( 'Verschiebe' ),
	'Blockme'                   => array( 'Proxy-Sperre' ),
	'Booksources'               => array( 'ISBN-Suech' ),
	'Categories'                => array( 'Kategorie' ),
	'Export'                    => array( 'Exportiere' ),
	'Allmessages'               => array( 'Alli Nochrichte' ),
	'Log'                       => array( 'Logbuech' ),
	'Blockip'                   => array( 'Sperre' ),
	'Undelete'                  => array( 'Widerhärstelle' ),
	'Import'                    => array( 'Importiere' ),
	'Lockdb'                    => array( 'Datebank sperre' ),
	'Unlockdb'                  => array( 'Sperrig vu dr Datebank ufhebe' ),
	'Userrights'                => array( 'Benutzerrächt' ),
	'MIMEsearch'                => array( 'MIME-Suech' ),
	'FileDuplicateSearch'       => array( 'Datei-Duplikat-Suech' ),
	'Unwatchedpages'            => array( 'Syte', 'wu nit beobachtet wäre' ),
	'Listredirects'             => array( 'Wyterleitige' ),
	'Revisiondelete'            => array( 'Versionsleschig' ),
	'Unusedtemplates'           => array( 'Vorlage', 'wo nit brucht wäre' ),
	'Randomredirect'            => array( 'Zuefelligi Wyterleitig' ),
	'Mypage'                    => array( 'Myyni Benutzersyte' ),
	'Mytalk'                    => array( 'Myyni Diskussionssyte' ),
	'Mycontributions'           => array( 'Myyni Byytreeg' ),
	'Listadmins'                => array( 'Ammanne' ),
	'Listbots'                  => array( 'Bötli' ),
	'Popularpages'              => array( 'Beliebteschti Syte' ),
	'Search'                    => array( 'Suech' ),
	'Resetpass'                 => array( 'Passwort ändre' ),
	'Withoutinterwiki'          => array( 'Ohni Interwiki' ),
	'MergeHistory'              => array( 'Versionsgschichte zämefiere' ),
	'Filepath'                  => array( 'Dateipfad' ),
	'Invalidateemail'           => array( 'E-Mail nit bstetige' ),
	'Blankpage'                 => array( 'Läärsyte' ),
	'LinkSearch'                => array( 'Suech no Gleicher' ),
	'DeletedContributions'      => array( 'Gleschti Byytreeg' ),
);

$linkTrail = '/^([äöüßa-z]+)(.*)$/sDu';

$messages = array(
# User preference toggles
'tog-underline'               => 'Links unterstryche',
'tog-highlightbroken'         => 'Links uf lääri Themene durestryche',
'tog-justify'                 => 'Tekscht als Blocksatz',
'tog-hideminor'               => 'Keini «chlyni Änderige» aazeige',
'tog-extendwatchlist'         => 'Erwiterti Beobachtungslischte',
'tog-usenewrc'                => 'Erwytereti «letschti Änderige» (geit nid uf allne Browser)',
'tog-numberheadings'          => 'Überschrifte outomatisch numeriere',
'tog-showtoolbar'             => 'Editier-Wärchzüüg aazeige',
'tog-editondblclick'          => 'Syte ändere mit Doppelklick i d Syte (JavaScript)',
'tog-editsection'             => 'Gleicher aazeige für ds Bearbeite vo einzelnen Absätz',
'tog-editsectiononrightclick' => 'Einzelni Absätz ändere mit Rächtsclick (Javascript)',
'tog-showtoc'                 => 'Inhaltsverzeichnis aazeige bi Artikle mit meh als drei Überschrifte',
'tog-rememberpassword'        => 'Passwort spychere (Cookie)',
'tog-editwidth'               => 'Fäld zum Täkscht yygee het di voll Breiti',
'tog-watchcreations'          => 'Sälber gmachti Sytene beobachte',
'tog-watchdefault'            => 'Vo dir nöi gmachti oder verändereti Syte beobachte',
'tog-watchmoves'              => 'Sälber verschobeni Sytene beobachte',
'tog-watchdeletion'           => 'Sälber glöschti Sytene beobachte',
'tog-minordefault'            => 'Alli dyni Änderigen als «chlyni Änderige» markiere',
'tog-previewontop'            => 'Vorschou vor em Editierfänschter aazeige',
'tog-previewonfirst'          => 'Vorschou aazeige bim erschten Editiere',
'tog-nocache'                 => 'Syte-Cache deaktiviere',
'tog-enotifwatchlistpages'    => 'Benachrichtigungsmails by Änderigen a Wiki-Syte',
'tog-enotifusertalkpages'     => 'Benachrichtigungsmails bi Änderigen a dyne Benutzersyte',
'tog-enotifminoredits'        => 'Benachrichtigungsmail ou bi chlyne Sytenänderige',
'tog-enotifrevealaddr'        => 'Dyni E-Mail-Adrässe wird i Benachrichtigungsmails zeigt',
'tog-shownumberswatching'     => 'Aazahl Benutzer aazeige, wo ne Syten am Aaluege sy (i den Artikelsyte, i de «letschten Änderigen» und i der Beobachtigslischte)',
'tog-fancysig'                => 'Kei outomatischi Verlinkig vor Signatur uf d Benutzersyte',
'tog-externaleditor'          => 'Externe Editor als Standard bruche (nume fir Experte, doderzue brucht s speziälli Yystellige uf em Computer)',
'tog-externaldiff'            => 'Externi diff als default',
'tog-showjumplinks'           => '«Wächsle-zu»-Links ermügleche',
'tog-uselivepreview'          => 'Live-Vorschau bruche (JavaScript) (experimentell)',
'tog-forceeditsummary'        => 'Sag mer s, wänn i s Zämmefassigsfeld läär loss',
'tog-watchlisthideown'        => 'Eigeni Änderige uf d Beobachtigslischt usblände',
'tog-watchlisthidebots'       => 'Bot-Änderige in d Beobachtigslischt usblende',
'tog-watchlisthideminor'      => 'Chlyni Änderige nit in de Beobachtigslischte aazeige',
'tog-watchlisthideliu'        => 'Bearbeitige vu aagmäldete Benutzer usblände',
'tog-watchlisthideanons'      => 'Bearbeitige vu anonyme Benutzer (IP-Adresse) usblände',
'tog-nolangconversion'        => 'Konvertierig vu Sprachvariante abschalte',
'tog-ccmeonemails'            => 'Schick mr Kopie vo de E-Mails, won i andere schick.',
'tog-diffonly'                => "Numme Versionsunterschied aazeige, ohni d'Syte",
'tog-showhiddencats'          => 'Zeig di versteckte Kategorie',
'tog-norollbackdiff'          => 'Unterschid noch em Zrucksetze unterdrucke',

'underline-always'  => 'immer',
'underline-never'   => 'nie',
'underline-default' => 'Browser-Vorystellig',

# Dates
'sunday'        => 'Sunntig',
'monday'        => 'Mäntig',
'tuesday'       => 'Zischtig',
'wednesday'     => 'Mittwuche',
'thursday'      => 'Dunschtig',
'friday'        => 'Fritig',
'saturday'      => 'Samschtig',
'sun'           => 'Sun',
'mon'           => 'Män',
'tue'           => 'Zys',
'wed'           => 'Mit',
'thu'           => 'Dun',
'fri'           => 'Fri',
'sat'           => 'Sam',
'january'       => 'Jänner',
'february'      => 'Februar',
'march'         => 'März',
'april'         => 'April',
'may_long'      => 'Mai',
'june'          => 'Juni',
'july'          => 'Juli',
'august'        => 'Ougschte',
'september'     => 'Septämber',
'october'       => 'Oktober',
'november'      => 'Novämber',
'december'      => 'Dezämber',
'january-gen'   => 'Jänner',
'february-gen'  => 'Februar',
'march-gen'     => 'März',
'april-gen'     => 'April',
'may-gen'       => 'Mai',
'june-gen'      => 'Juni',
'july-gen'      => 'Juli',
'august-gen'    => 'Augschte',
'september-gen' => 'Septämber',
'october-gen'   => 'Oktober',
'november-gen'  => 'Novämber',
'december-gen'  => 'Dezämber',
'jan'           => 'Jan.',
'feb'           => 'Feb.',
'mar'           => 'Mär.',
'apr'           => 'Apr.',
'may'           => 'Mei',
'jun'           => 'Jun.',
'jul'           => 'Jul.',
'aug'           => 'Aug.',
'sep'           => 'Sep.',
'oct'           => 'Okt.',
'nov'           => 'Nov.',
'dec'           => 'Dez.',

# Categories related messages
'pagecategories'                 => '{{PLURAL:$1|Kategori|Kategorie}}',
'category_header'                => 'Artikel in de Kategori "$1"',
'subcategories'                  => 'Unterkategorie',
'category-media-header'          => 'Medie in dr Kategori „$1“',
'category-empty'                 => "''Die Kategori het im Momänt kaini Syte oder Medie.''",
'hidden-categories'              => '{{PLURAL:$1|Versteckti Kategori|Versteckti Kategorie}}',
'hidden-category-category'       => 'Versteckti Kategorie', # Name of the category where hidden categories will be listed
'category-subcat-count'          => '{{PLURAL:$2|Die Kategori het die Unterkategorie:|{{PLURAL:$1|Die Unterkategori isch eini vu insgsamt $2 Unterkategorie in däre Kategori:|S wäre $1 vu insgsamt $2 Unterkategorie in däre Kategori aazeigt:}}}}',
'category-subcat-count-limited'  => 'Die Kategorie het die {{PLURAL:$1|Unterkategori|$1 Unterkategorie}}:',
'category-article-count'         => '{{PLURAL:$2|In däre Kategorie het s die Syte:|{{PLURAL:$1|Die Syte isch eini vu insgsamt $2 Syte in däre Kategori:|S wäre $1 vu insgsamt $2 Syte in däre Kategori aazeigt:}}}}',
'category-article-count-limited' => 'In däre Kategori het s die {{PLURAL:$1|Syte|$1 Syte}}:',
'category-file-count'            => '{{PLURAL:$2|In däre Kategori het s die Datei:|{{PLURAL:$1|Die Datei isch eini vu insgsamt $2 Dateie in däre Kategori:|S wäre $1 vu insgsamt $2 Dateie in däre Kategori aazeigt:}}}}',
'category-file-count-limited'    => 'In däre Kategori het s die {{PLURAL:$1|Datei|$1 Dateie}}:',
'listingcontinuesabbrev'         => '(Forts.)',

'mainpagetext'      => 'MediaWiki isch erfolgrich inschtalliert worre.',
'mainpagedocfooter' => 'Lueg uf d [http://meta.wikimedia.org/wiki/MediaWiki_localisation Dokumentation fir d Aapassig vu dr Benutzeroberflächi] un s [http://meta.wikimedia.org/wiki/Help:Contents Benutzerhandbuech] fir d Hilf iber d Benutzig un s Yystelle.',

'about'          => 'Über',
'article'        => 'Inhaltssyte',
'newwindow'      => '(imene nöie Fänschter)',
'cancel'         => 'Abbräche',
'qbfind'         => 'Finde',
'qbbrowse'       => 'Blättre',
'qbedit'         => 'Ändere',
'qbpageoptions'  => 'Sytenoptione',
'qbpageinfo'     => 'Sytedate',
'qbmyoptions'    => 'Ystellige',
'qbspecialpages' => 'Spezialsytene',
'moredotdotdot'  => 'Meh …',
'mypage'         => 'Myyni Syte',
'mytalk'         => 'Myyni Diskussionsyte',
'anontalk'       => 'Diskussionssyste vo sellere IP',
'navigation'     => 'Navigation',
'and'            => '&#32;un',

# Metadata in edit box
'metadata_help' => 'Metadate:',

'errorpagetitle'    => 'Fähler',
'returnto'          => 'Zruck zur Syte $1.',
'tagline'           => 'Us {{SITENAME}}',
'help'              => 'Hilf',
'search'            => 'Suech',
'searchbutton'      => 'Suech',
'go'                => 'Sueche',
'searcharticle'     => 'Sueche',
'history'           => 'Versione',
'history_short'     => 'Versione/Autore',
'updatedmarker'     => '(gändret syt mym letschte Bsuech)',
'info_short'        => 'Information',
'printableversion'  => 'Druck-Aasicht',
'permalink'         => 'Bschtändigi URL',
'print'             => 'Drucke',
'edit'              => 'ändere',
'create'            => 'Erstelle',
'editthispage'      => 'Syte bearbeite',
'create-this-page'  => 'Die Syte afange',
'delete'            => 'lösche',
'deletethispage'    => 'Syte lösche',
'undelete_short'    => '{{PLURAL:$1|1 Version|$1 Versione}} widerherstelle',
'protect'           => 'schütze',
'protect_change'    => 'ändere',
'protectthispage'   => 'Artikel schütze',
'unprotect'         => 'nümm schütze',
'unprotectthispage' => 'Schutz ufhebe',
'newpage'           => 'Nöji Syte',
'talkpage'          => 'Iber die Syte dischputiere',
'talkpagelinktext'  => 'Diskussion',
'specialpage'       => 'Spezialsyte',
'personaltools'     => 'Persönlichi Wärkzüg',
'postcomment'       => 'Kommentar abgeh',
'articlepage'       => 'Syte',
'talk'              => 'Diskussion',
'views'             => 'Wievylmol agluegt',
'toolbox'           => 'Wärkzügkäschtli',
'userpage'          => 'Benutzersyte',
'projectpage'       => 'Projektsyte azeige',
'imagepage'         => 'Bildsyte',
'mediawikipage'     => 'Inhaltssyte aazeige',
'templatepage'      => 'Vorlagesyte aazeige',
'viewhelppage'      => 'D Hilf aazeige',
'categorypage'      => 'Kategoriesyte aazeige',
'viewtalkpage'      => 'Diskussion',
'otherlanguages'    => 'Anderi Sproche',
'redirectedfrom'    => '(Witergleitet vun $1)',
'redirectpagesub'   => 'Umgleiteti Syte',
'lastmodifiedat'    => 'Letschti Änderig vo dere Syte: $2, $1<br />', # $1 date, $2 time
'viewcount'         => 'Selli Syte isch {{PLURAL:$1|eimol|$1 Mol}} bsuecht worde.',
'protectedpage'     => 'Gschützti Syte',
'jumpto'            => 'Gump zue:',
'jumptonavigation'  => 'Navigation',
'jumptosearch'      => 'Suech',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'Über {{GRAMMAR:akkusativ|{{SITENAME}}}}',
'aboutpage'            => 'Project:Über {{UCFIRST:{{GRAMMAR:akkusativ|{{SITENAME}}}}}}',
'copyright'            => 'Der Inhalt vo dere Syte stoht unter der $1.',
'copyrightpagename'    => '{{SITENAME}} Urheberrächt',
'copyrightpage'        => '{{ns:project}}:Copyright',
'currentevents'        => 'Aktuelli Mäldige',
'currentevents-url'    => 'Project:Aktuelli Termin',
'disclaimers'          => 'Impressum',
'disclaimerpage'       => 'Project:Impressum',
'edithelp'             => 'Rotschläg fir s Bearbeite',
'edithelppage'         => 'Help:Ändere',
'faq'                  => 'Froge, wo vilmol gstellt wäre',
'faqpage'              => 'Project:FAQ',
'helppage'             => 'Help:Hilf',
'mainpage'             => 'Houptsyte',
'mainpage-description' => 'Houptsyte',
'policy-url'           => 'Project:Leitlinie',
'portal'               => 'Gmeinschaftsportal',
'portal-url'           => 'Project:Gmeinschafts-Portal',
'privacy'              => 'Dateschutz',
'privacypage'          => 'Project:Dateschutz',

'badaccess'        => 'Dyyni Rächt länge nid.',
'badaccess-group0' => 'Du hesch d Berächtigung nid, wo s brucht fir die Aktion.',
'badaccess-groups' => 'Die Aktion isch bschränkt uf Benutzer, wu {{PLURAL:$2|zue dr Gruppe|zue einer vu dr Gruppe}} „$1“ ghere.',

'versionrequired'     => 'Version $1 vun MediaWiki wird brucht',
'versionrequiredtext' => 'Version $1 vu MediaWiki wird brucht zum die Syte nutze. Lueg [[Special:Version]]',

'ok'                      => 'OK',
'retrievedfrom'           => 'Vun "$1"',
'youhavenewmessages'      => 'Du hesch $1 ($2).',
'newmessageslink'         => 'nöji Nachrichte',
'newmessagesdifflink'     => 'Unterschid',
'youhavenewmessagesmulti' => 'Si hen neui Nochrichte: $1',
'editsection'             => 'ändere',
'editold'                 => 'Ändre',
'viewsourceold'           => 'Quelltext azeige',
'editlink'                => 'bearbeite',
'viewsourcelink'          => 'Quälltäxt aaluege',
'editsectionhint'         => 'Abschnitt ändere: $1',
'toc'                     => 'Inhaltsverzeichnis',
'showtoc'                 => 'ufklappe',
'hidetoc'                 => 'zueklappe',
'thisisdeleted'           => 'Aaluege oder widerherstelle vu $1?',
'viewdeleted'             => '$1 aaluege?',
'restorelink'             => '{{PLURAL:$1|glöschti Änderig|$1 glöschti Ändrige}}',
'feedlinks'               => 'Feed:',
'feed-invalid'            => 'Nid giltige Abonnement-Typ.',
'feed-unavailable'        => 'S stehn keini Feeds z Verfiegig.',
'site-rss-feed'           => 'RSS-Feed fir $1',
'site-atom-feed'          => 'Atom-Feed für $1',
'page-rss-feed'           => 'RSS-Feed für „$1“',
'page-atom-feed'          => 'Atom-Feed fir „$1“',
'red-link-title'          => '$1 (Syte isch nid vorhande)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Syte',
'nstab-user'      => 'Benutzersyte',
'nstab-media'     => 'Mediesyte',
'nstab-special'   => 'Spezialsyte',
'nstab-project'   => 'Projektsyte',
'nstab-image'     => 'Bildli',
'nstab-mediawiki' => 'Nochricht',
'nstab-template'  => 'Vorlag',
'nstab-help'      => 'Hilf',
'nstab-category'  => 'Kategorie',

# Main script and global functions
'nosuchaction'      => 'Die Aktion git s nit',
'nosuchactiontext'  => 'Die Aktion wird vun de MediaWiki-Software nit unterstitzt',
'nosuchspecialpage' => 'Die Spezialsyte git s nit',
'nospecialpagetext' => "<big>'''Die Spezialsyte git s nid.'''</big>

Alli verfiegbare Spezialsyte sin in dr [[Special:SpecialPages|Lisch vu Spezialsyte]] z finde.",

# General errors
'error'                => 'Fähler',
'databaseerror'        => 'Fähler in dr Datebank',
'dberrortext'          => 'S het e Syntaxfähler in dr Datebankabfreg gee.

D letscht Datebankabfrog het gheisse: "$1" us dr Funktion "<tt>$2</tt>".

MySQL het dr Fähler "<tt>$3: $4</tt>" gmeldet.',
'dberrortextcl'        => 'S het e Syntaxfähler gee in dr Abfrog vu dr Datebank.
Di letscht Datebankabfrog isch gsi: „$1“ us dr Funktion „<tt>$2</tt>“.
MySQL het dr Fähler „<tt>$3: $4</tt>“ gmäldet.',
'noconnect'            => 'Äxkisi! Im Wiki git s tächnischi Schwirigkeite, s git kei Verbindig zue dr Datebank.<br />
$1',
'nodb'                 => 'Ha d Datebank $1 nit chenne uswähle',
'cachederror'          => 'Des isch e Kopie us em Cache un megligerwys nit aktuäll.',
'laggedslavemode'      => 'Warnig: di letschte Änderige wäre u. U. nonig aazeigt!',
'readonly'             => 'Datebank isch gsperrt',
'enterlockreason'      => 'Bitte gib e Grund y, worum d Datebank soll gsperrt wäre un e Yschätzig wie lang si soll gsperrt blybe',
'readonlytext'         => 'D {{SITENAME}}-Datebank isch e Zyd lang gsperrt fir Nejytreg un Änderige. Bitte versuech s speter no mol.

Grund vu dr Sperrig: $1',
'missing-article'      => 'Där Täxt fir „$1“ $2 isch nid in dr Datebank gfunde wore.

Die Syte isch villicht glescht oder verschobe wore.

Wänn s des nid isch, hesch villicht e Fähler in dr Software gfunde. Bitte mäld des eme  [[Special:ListUsers/sysop|Ammann]] un gib d URL derzue aa.',
'missingarticle-rev'   => '(Versionsnummere: $1)',
'missingarticle-diff'  => '(Unterschid zwische Versione: $1, $2)',
'readonly_lag'         => 'D Datebank isch automatisch gperrt wore, wil di verteilte Datebankserver (Sklave) mien mit em Hauptdatebankserver (Meischter) abgliche wäre.',
'internalerror'        => 'Interner Fähler',
'internalerror_info'   => 'Interne Fähler: $1',
'filecopyerror'        => 'Datei "$1" het nit noch "$2" kopiert werre kinne.',
'filerenameerror'      => 'D Datei "$1" het nit in "$2" umgnennt werre kinne.',
'filedeleteerror'      => 'Datei "$1" het nit glöscht werre kinne.',
'directorycreateerror' => 'S Verzeichnis „$1“ het nid chenne aaglait wäre.',
'filenotfound'         => 'Datei "$1" isch nit gfunde worre.',
'fileexistserror'      => 'In d Datei „$1“ het nit chenne gschribe wäre, wel s die Datei scho git.',
'unexpected'           => 'Wärt, wu nit erwartet woren isch: „$1“=„$2“.',
'formerror'            => 'Fähler: Ds Formular het nid chönne verarbeitet wärde',
'badarticleerror'      => 'D Aktion konn uf denne Artikel nit ongwendet werre.',
'cannotdelete'         => 'Konn d Syte oder dr Artikel nit lesche. (Isch meglicherwis schu vun eber ondrem glescht worre.)',
'badtitle'             => 'Ugültiger Titel',
'badtitletext'         => 'Dr Titel vu dr agforderte Syte isch nit giltig gsi, leer, oder e nit giltig Sprochgleich vun eme andre Wiki.',
'perfcached'           => 'Die Informatione chömme us em Zwüschespeicher un sin derwiil villicht nid aktuell.
----',
'perfcachedts'         => 'Die Date stamme us em Cache un sin am $1 s letscht Mol aktualisiert wore.',
'querypage-no-updates' => "'''D Aktualisierigsfunktion fir die Syte isch im Momänt deaktiviert. D Date wäre vorerscht nid ernejert.'''",
'wrong_wfQuery_params' => 'Falschi Parameter fir wfQuery()<br />
Funktion: $1<br />
Abfrog: $2',
'viewsource'           => 'Quelltext aaluege',
'viewsourcefor'        => 'fir $1',
'actionthrottled'      => 'Aktionsaazahl limitiert',
'actionthrottledtext'  => 'As Schutz vor Spam cha die Aktion im e churze Zytabstand nume begränzt durgfiert wäre. Du bisch ebe an die Gränz cho. Bitte versuech s in e paar Minute non emol.',
'protectedpagetext'    => 'Die Syte isch fir s Bearbeite gsperrt.',
'viewsourcetext'       => 'Quelltext vo dere Syte:',
'protectedinterface'   => 'In däre Syte het s Text fir s Sproch-Interface vu dr Software un isch gsperrt, zum Missbruch z verhindre.',
'editinginterface'     => "'''Obacht:''' Du bisch e Syten am Verändere, wo zum user interface ghört. We du die Syte veränderisch, de änderet sech ds user interface o für di andere Benutzer.",
'sqlhidden'            => '(SQL-Abfrog verschteckt)',
'cascadeprotected'     => 'Die Syte isch fir s Bearbeite gsperrt. Si isch yybunde in {{PLURAL:$1|die Syte, wu do chunnt|die Syte, wu do chemme}} , wu mit ere Kaskadesperroption gschitzt {{PLURAL:$1|isch|sin}}:
$2',
'namespaceprotected'   => "Du hesch kei Berächtigung, die Syte im '''$1'''-Namensruum z bearbeite.",
'customcssjsprotected' => 'Du bisch nid berächtigt, die Syte  bearbeite, wel si zue dr persenlige Yystellige vun eme andere Benutzer ghert.',
'ns-specialprotected'  => 'Spezialsyte chenne nid bearbeitet wäre.',
'titleprotected'       => "E Syte mit däm Name cha nid aaglait wäre. 
Die Sperri isch dur [[User:$1|$1]] yygrichtet wore mit dr Begrindig ''„$2“''.",

# Virus scanner
'virus-badscanner'     => 'Fählerhafti Konfiguration: Virescanner, wu nid bekannt isch: <i>$1</i>',
'virus-scanfailed'     => 'Scan het nid funktioniert (code $1)',
'virus-unknownscanner' => 'Virescanner, wu nid bekannt isch:',

# Login and logout pages
'logouttitle'                => 'Benutzer-Abmäldig',
'logouttext'                 => '<div align="center" style="background-color:white;">
<b>Du bisch jitz abgmäldet!</b>
</div><br />
We du jitz öppis uf der {{SITENAME}} änderisch, de wird dyni IP-Adrässen als Urhäber regischtriert u nid dy Benutzername. Du chasch di mit em glychen oder emnen andere Benutzername nöi aamälde.',
'welcomecreation'            => '==Willcho, $1!==
Dyy Benutzerchonto isch aaglait wore.
Vergiss nid, dyni Yystellige aazpasse.',
'loginpagetitle'             => 'Benutzer-Aamelde',
'yourname'                   => 'Dyy Benutzername',
'yourpassword'               => 'Passwort',
'yourpasswordagain'          => 'Passwort no mol yygee',
'remembermypassword'         => 'Passwort spychere',
'yourdomainname'             => 'Dyyni Domäne',
'externaldberror'            => 'Entwäder s lit e Fähler bi dr externe Authentifizierung vor, oder Du derfsch Dyy extern Benutzerchonto nid aktualisiere.',
'login'                      => 'Aamälde',
'nav-login-createaccount'    => 'Aamälde / Chonto aaleege',
'loginprompt'                => '<small>Für di bir {{SITENAME}} aazmälde, muesch Cookies erloube!</small>',
'userlogin'                  => 'Aamälde',
'logout'                     => 'Abmälde',
'userlogout'                 => 'Abmälde',
'notloggedin'                => 'Nit aagmäldet',
'nologin'                    => 'No kei Benutzerchonto? $1.',
'nologinlink'                => '»Chonto aaleege«',
'createaccount'              => 'Nöis Benutzerkonto aalege',
'gotaccount'                 => 'Du häsch scho a Chonto? $1',
'gotaccountlink'             => '»Login fir Benutzer, wu scho aagmäldet sin«',
'createaccountmail'          => 'iber E-Mail',
'badretype'                  => 'Di beidi Passwörter stimme nid zämme.',
'userexists'                 => 'Dä Benutzername git s scho.
Bitte nimm e andere.',
'youremail'                  => 'E-Mail-Adräss:',
'username'                   => 'Benutzername:',
'uid'                        => 'Benutzer-ID:',
'prefs-memberingroups'       => 'Mitglid vu dr {{PLURAL:$1|Benutzergruppe|Benutzergruppe}}:',
'yourrealname'               => 'Ihre Name*',
'yourlanguage'               => 'Sproch:',
'yourvariant'                => 'Variante:',
'yournick'                   => 'Unterschrift:',
'badsig'                     => 'Dr Syntax vu dr Signatur isch nid giltig; bitte d HTML iberpriefe.',
'badsiglength'               => 'D Unterschrift derf hegschtens $1 {{PLURAL:$1|Zeiche|Zeiche}} lang syy.',
'email'                      => 'E-Mail',
'prefs-help-realname'        => '* <strong>Dyy ächte Name</strong> (optional): Wänn du wetsch, ass Dyyni Änderige uf Dii chenne zruckgfierd wäre.',
'loginerror'                 => 'Fähler bir Aamäldig',
'prefs-help-email'           => '* <strong>E-Mail-Adrässe</strong> (optional): We du en E-Mail-Adrässen aagisch, überchömen anderi Benutzer d Müglechkeit, di über dyni Benutzer- oder Benutzer_Diskussionsyte z kontaktiere. Im Fall das du mal ds Passwort sötsch vergässe ha, cha dir es nöis Zuefalls-Passwort gmailet wärde.<br />
** <strong>Signatur</strong> (optional): D Signatur wird ygsetzt, we du e Diskussionsbytrag mit «<nowiki>~~~~</nowiki>» unterschrybsch; we du ke spezielli Signatur aagisch, de wird eifach di Benutzername mit emne Link uf dyni Benutzersyten ygfüegt.',
'prefs-help-email-required'  => 'S brucht e giltigi E-Mail-Adräss.',
'nocookiesnew'               => 'Dr Benutzerzuegang isch aaglait wore, aber Du bisch nid yygloggt. {{SITENAME}} brucht fir die Funktion Cookies, bitte tue die aktiviere un logg Di derno mit Dyynem neje Benutzername un em Passwort, wu drzue ghert, yy.',
'nocookieslogin'             => '{{SITENAME}} brucht Cookies fir e Aamäldig. Du hesch d Cookies deaktiviert. Aktivier si bitte un versuech s no mol.',
'noname'                     => 'Du muesch e Benutzername aagee.',
'loginsuccesstitle'          => 'Aamäldig erfolgrych',
'loginsuccess'               => "'''Du bisch jetz als \"\$1\" bi {{SITENAME}} aagmäldet.'''",
'nosuchuser'                 => 'Dr Benutzername "$1" git s nit.

Iberprief d Schrybwys, oder mäld Di as [[Special:UserLogin/signup|neje Benutzer aa]].',
'nosuchusershort'            => 'S git kei Benutzername „<nowiki>$1</nowiki>“. Bitte iberprief d Schrybwys.',
'nouserspecified'            => 'Bitte gib e Benutzername yy.',
'wrongpassword'              => 'Des Passwort isch falsch (oder fählt). Bitte versuech s nomol.',
'wrongpasswordempty'         => 'Du hesch vergässe dyy Passwort yyzgee. Bitte versuech s nomol.',
'passwordtooshort'           => 'Dys Passwort isch ungültig oder z churz.
Es mues mindischtens {{PLURAL:$1|1 Zeiche|$1 Zeiche}} ha u sech vom Benutzernamen underscheide.',
'mailmypassword'             => 'Es nöis Passwort schicke',
'passwordremindertitle'      => 'Nei Passwort fir {{SITENAME}}',
'passwordremindertext'       => 'Ebber mit dr IP-Adress $1 het e nej Passwort fir d Aamäldig bi {{SITENAME}} ($4) aagfordert, wahrschyyns Du sälber.

S automatisch generiert Passwort fir dr Benutzer $2 heisst jetz: $3

Du sottsch dich jetzt aamälde un s Passwort ändere: {{fullurl:Special:UserLogin}}

Bitte ignorier die E-Mail, wänn Du s nid sälber aagforderet hesch. S alt Passwort blybt wyter giltig.',
'noemail'                    => 'Dr Benutzer "$1" het kei E-Mail-Adräss aagee.',
'passwordsent'               => 'E temporär Passwort isch an d E-Mail-Adräss vum Benutzer "$1" gschickt wore.
Bitte mäld Di dodemit aa, wänn s iberchu hesch.',
'blocked-mailpassword'       => 'Die IP-Adräss, wu vu Dir verwändet wird, isch fir s Ändre vu Syte gsperrt
Zum Missbruuch z verhindere, isch au d Megligkeit gsperrt wore, e nej Passwort aazfordere.',
'eauthentsent'               => 'E Bstätigungs-Mail isch an die Adräss gschickt wore, wu Du aagee hesch. 

Voreb ass no mee Mails iber d {{SITENAME}}-Mailfunktion an die Adräss gschickt wäre, muesch d Inschtruktione in däm Mail befolge, zum bstätige, ass es wirkli Dyys isch.',
'throttled-mailpassword'     => 'In dr letschte {{PLURAL:$1|Stund|$1 Stunde}} isch scho ne nej Passwort aagforderet wore. Zum Missbruch vu däre Funktion z verhindere, cha nume {{PLURAL:$1|eimol in dr Stund|alli $1 Stunde}} e nej Passwort aageforderet wäre.',
'mailerror'                  => 'Fähler bim Sende vun de Mail: $1',
'acct_creation_throttle_hit' => 'Du hesch scho {{PLURAL:$1|1 Benutzerchonto|$1 Benutzerchonte}} aagleit.
Du chasch keini meh aalege.',
'emailauthenticated'         => 'Di E-Mail-Adräss isch am $2 um $3 Uhr bschtätigt worde.',
'emailnotauthenticated'      => 'Dyni E-Mail-Adräss isch nonig bstätigt. Wäg däm gehn di erwyterete E-Mail-Funktione nonig.
Fir d Bstätigung muesch em Gleich nogoh, wu Dir gschickt woren isch. Du chasch au e neie sonig Gleich aafordere:',
'noemailprefs'               => '<strong>Du hesch kei E-Mail-Adrässen aaggä</strong>, drum sy di folgende Funktione nid müglech.',
'emailconfirmlink'           => 'E-Poscht-Adräss bstätige',
'invalidemailaddress'        => 'Diä E-Mail-Adress isch nit akzeptiert worre, wil s ä ugültigs Format ghet het.
Bitte gib ä neiji Adress in nem gültige Format ii, odr tue s Feld leere.',
'accountcreated'             => 'De Benutzer isch agleit worre.',
'accountcreatedtext'         => 'De Benutzer $1 isch aagleit worre.',
'createaccount-title'        => 'Aalege vum e Benutzerchonto fir {{SITENAME}}',
'createaccount-text'         => 'Fir Dii isch e Benutzerchonto "$2" uf {{SITENAME}} ($4) aaglait wore. S Passwort fir "$2" , wu automatisch generiert woren isch, isch "$3". Du sottsch Di jetz aamälde un s Passwort ändere.

Wänn s Benutzerchonto us Versäh aaglait woren isch, chasch die Nochricht ignoriere.',
'login-throttled'            => 'Du hesch z vilmol vergebli versuecht, Di unter däm Benutzername aazmälde. Bitte wart, voreb Du s non emol versuechsch.',
'loginlanguagelabel'         => 'Sproch: $1',

# Password reset dialog
'resetpass'                 => 'Passwort fir s Benutzerchonto ändere oder zrucksetze',
'resetpass_announce'        => 'Aamäldig mit em Code, wu per Mail zuegschickt woren isch. Zum d Aamäldig abzschliesse, muesch jetz e nej Passwort wehle.',
'resetpass_header'          => 'Passwort zrucksetze',
'oldpassword'               => 'Alts Passwort',
'newpassword'               => 'Nöis Passwort',
'retypenew'                 => 'Nöis Passwort (es zwöits Mal)',
'resetpass_submit'          => 'Passwort ibermittle un aamälde',
'resetpass_success'         => 'Dyy Passwort isch erfolgryych gänderet wore. Jetz chunnt d Aamäldig …',
'resetpass_bad_temporary'   => 'Vorlaifig Passwort, wu nimi giltig isch. Du hesch Dyy Passwort scho gänderet oder e nej vorlaifig Passwort aagforderet.',
'resetpass_forbidden'       => 'S Passwort cha nid gänderet wäre.',
'resetpass-no-info'         => 'Du muesch Di aamälde zum uf die Syte diräkt zuegryfe z chenne.',
'resetpass-submit-loggedin' => 'Passwort ändere',
'resetpass-wrong-oldpass'   => 'S temporär oder aktuäll Passwort isch nimi giltig.
Villicht hesch Dyy Passwort scho gänderet oder e nej temporär Passwort aagforderet.',
'resetpass-temp-password'   => 'Temporär Passwort:',

# Edit page toolbar
'bold_sample'     => 'fetti Schrift',
'bold_tip'        => 'Fetti Schrift',
'italic_sample'   => 'kursiv gschribe',
'italic_tip'      => 'Kursiv gschribe',
'link_sample'     => 'Stichwort',
'link_tip'        => 'Interne Link',
'extlink_sample'  => 'http://www.example.com Linktekscht',
'extlink_tip'     => 'Externer Link (http:// beachte)',
'headline_sample' => 'Abschnitts-Überschrift',
'headline_tip'    => 'Überschrift Äbeni 2',
'math_sample'     => 'Formel do yfüge',
'math_tip'        => 'Mathematisch Formel (LaTeX)',
'nowiki_sample'   => 'Was da inne staht wird nid formatiert',
'nowiki_tip'      => 'Wiki-Formatierige ignoriere',
'image_sample'    => 'Byschpil.jpg',
'image_tip'       => 'Bildverwys',
'media_sample'    => 'Byschpil.mp3',
'media_tip'       => 'Dateie-Link',
'sig_tip'         => 'Dyni Signatur mit Zytagab',
'hr_tip'          => 'Horizontali Linie (sparsam verwende)',

# Edit pages
'summary'                          => 'Zämefassig:',
'subject'                          => 'Beträff:',
'minoredit'                        => 'Numen es birebitzeli gänderet',
'watchthis'                        => 'Dä Artikel beobachte',
'savearticle'                      => 'Syte spychere',
'preview'                          => 'Vorschou',
'showpreview'                      => 'Vorschau aaluege',
'showlivepreview'                  => 'Live-Vorschau',
'showdiff'                         => 'Zeig Änderige',
'anoneditwarning'                  => "'''Warnig:''' Si sin nit aagmäldet. Ihri IP-Adrässe wird in de Gschicht vo däm Artikel gspeicheret.",
'missingsummary'                   => "'''Obacht:''' Du hesch kei Zämefassig aagee. Wenn du nomol uf Spychere drucksch, wird d Änderung ohni gspychert.",
'missingcommenttext'               => 'Bitte gib Dyy Kommentar unte yy.',
'missingcommentheader'             => "'''ACHTIG:''' Du hesch kei Iberschrift im Fäld „Betreff:“ yygee. Wänn nomol uf „Syte spichere“ drucksch, wird Dyyni Bearbeitig ohni Iberschrift gspicheret.",
'summary-preview'                  => 'Vorschou vor Zämefassig:',
'subject-preview'                  => 'Vorschau vum Betreff:',
'blockedtitle'                     => 'Benutzer isch gsperrt.',
'blockedtext'                      => "<big>'''Dy Benutzernamen oder dyni IP-Adrässen isch gsperrt worde.'''</big>

Du chasch $1 oder en anderen [[{{MediaWiki:Grouppage-sysop}}|Administrator]] kontaktiere, für die Sperrig z diskutiere. Vergis i däm Fall bitte keni vo de folgenden Agabe:

*Administrator, wo het gsperrt: $1
*Grund für d Sperrig: $2
*Afang vor Sperrig: $8
*Ändi vor Sperrig: $6
*IP-Adrässe: $3
*Sperrig betrifft: $7
*ID vor Sperrig: #$5",
'autoblockedtext'                  => 'Dyyni IP-Adräss isch automatisch gsperrt wore, wel si vu me andere Benutzer brucht woren isch, wu dur $1 gsperrt woren isch.
As Grund isch aagee wore:

:\'\'$2\'\' (<span class="plainlinks">[{{fullurl:Special:IPBlockList|&action=search&limit=&ip=%23}}$5 Logbucheintrag]</span>)

<p style="border-style: solid; border-color: red; border-width: 1px; padding:5px;"><b>E Zuegriff zum Läse isch alno megli,</b>
nume d Bearbeitig un s Aalege vu Syte in {{SITENAME}} isch gsperrt wore.
Wänn die Nochricht aazeigt wird, au wänn Du nume zum Läse zuegriffe hesch, bisch eme (rote) Gleich uf e Syte noogange, wu s nonig git.</p>

Du chasch $1 oder ein vu dr andre [[{{MediaWiki:Grouppage-sysop}}|Ammanne]] kontaktiere, zum iber die Sperri z diskutiere.

<div style="border-style: solid; border-color: red; border-width: 1px; padding:5px;">
\'\'\'Bitte gib in jedere Aafrog die Date aa:\'\'\'
*Ammann, wu gsperrt het: $1
*Sperrgrund: $2
*Aafang vu dr Sperri: $8
*Sperr-Änd: $6
*IP-Adräss: $3
*Sperri betrifft: $7
*Sperr-ID: #$5
</div>',
'blockednoreason'                  => 'kei Begrindig aagee',
'blockedoriginalsource'            => "Dr Quälltäxt vu '''$1''' wird do aazeigt:",
'blockededitsource'                => "Dr Quälltäxt vu '''Dyyne Änderige''' an '''$1''':",
'whitelistedittitle'               => 'Zum Bearbeite muess mer aagmäldet syy.',
'whitelistedittext'                => 'Du muesch Di $1 zum Artikel bearbeite.',
'confirmedittitle'                 => 'Zum Ändere isch e bstätigti E-Mail-Adräss notwändig.',
'confirmedittext'                  => 'Si mien Ihri E-Mail-Adräss zerscht bstätige, voreb Si Syte chenne ändere. Bitte setze Si in [[Special:Preferences|Ihre Iistellige]] Ihri E-Mail-Adräss yy un len Si si priefe.',
'nosuchsectiontitle'               => 'Abschnitt git s nid.',
'nosuchsectiontext'                => 'Du versuechsch dr Abschnitt $1, wu s nid git, z bearbeite. Mer cha aber nume Abschnitt bearbeiter, wu s scho git.',
'loginreqtitle'                    => 'S brucht d Aamäldig.',
'loginreqlink'                     => 'aamälde',
'loginreqpagetext'                 => 'Du muesch Di $1, zum Syte chenne läse.',
'accmailtitle'                     => 'S Passwort isch verschickt worre.',
'accmailtext'                      => 'S Passwort für "$1" isch uf $2 gschickt worde.',
'newarticle'                       => '(Nej)',
'newarticletext'                   => "Du bisch eme Gleich nogange zuen ere Syte, wu s nid git. 
Zum die Syte aalege, chasch do in däm Chaschte unte aafange schrybe (lueg [[{{MediaWiki:Helppage}}|Hilfe]] fir meh Informationeo).
Wänn do nid hesch welle aane goh, no druck in Dyynem Browser uf '''Zruck'''.",
'anontalkpagetext'                 => "''Des isch e Diskussionssyte vo me anonyme Benutzer, wo kei Zuegang aaglait het oder wo ne nit bruucht. Sälleweg muen mir di numerischi IP-Adräss bruuche zum ihn oder si z identifiziere. Sone IP-Adräss cha au vo mehrere Benutzer deilt werde. Wenn Si en anonyme Benutzer sin un s Gfiehl hen, dass do irrelevanti Kommentar an Si grichtet wärde, derno [[Special:UserLogin|lege Si sich bitte en Zuegang aa odr mälde sich aa]] zum in Zuekunft Verwirrige mit andere anonyme Benutzer z vermide.''",
'noarticletext'                    => "Uf dere Syte het's no kei Tekscht. Du chasch uf anderne Syte [[Special:Search/{{PAGENAME}}|dä Ytrag sueche]] oder [{{fullurl:{{FULLPAGENAME}}|action=edit}} die Syte bearbeite].",
'userpage-userdoesnotexist'        => 'S Benutzerchonto „$1“ git s nid. Bitte prief, eb Du die Syte wirkli wit aalege/bearbeite.',
'clearyourcache'                   => "'''Hywys:''' Noch dynere Änderig muess no der Browser-Cache gläärt wäre!<br />'''Mozilla/Safari/Konqueror:''' ''Strg-Umschalttaschte-R'' (oder ''Umschalttasche'' druckt halte und uf s ''Nei-Lade''-Symbol klicke), '''IE:''' ''Strg-F5'', '''Opera/Firefox:''' ''F5''",
'usercssjsyoucanpreview'           => '<strong>Tipp:</strong> Nimm dr Vorschau-Chnopf, zum Dyy nej CSS/JS vor em Spichere z teschte.',
'usercsspreview'                   => "== Vorschau vu Dyynem Benutzer-CSS. ==
'''Wichtig:''' Noch em Spichere muesch Dyynem Browser sage, ass er die nej Version ladet:

'''Mozilla:''' ''Strg-Shift-R'', '''IE:''' ''Strg-F5'', '''Safari:''' ''Cmd-Shift-R'', '''Konqueror:''' ''F5''.",
'userjspreview'                    => "== Vorschau vu Dyynem Benutzer-Javascript. ==
'''Gib acht:''' Noch em Spychere muesch Dyy Browser aawyse di nej Version z lade: '''Mozilla:''' ''Strg-Shift-R'', '''IE:''' ''Strg-F5'', '''Safari:''' ''Cmd-Shift-R'', '''Konqueror:''' ''F5''.",
'userinvalidcssjstitle'            => "'''Achtig:''' D Skin „$1“ git s nid. Dänk dra, ass benutzerspezifischi .css- und .js-Syte mit eme Chleibuechstabe mien aafange, also z B. ''{{ns:user}}:Mustermann/monobook.css'' statt ''{{ns:user}}:Mustermann/Monobook.css''.",
'updated'                          => '(Gänderet)',
'note'                             => '<strong>Obacht: </strong>',
'previewnote'                      => '<strong>Das isch numen e Vorschau und nonig gspycheret!</strong>',
'previewconflict'                  => 'Die Vorschau zeigt dr Inhalt vum obere Täxtfäld. Eso siht dr Artikel us, wän Du jetz uf Spychere drucksch.',
'session_fail_preview'             => '<strong>Dyyni Bearbeitig het nid chenne gspycheret wäre, wel Sitzigsdate verlore gange sin.
Bitte versuech s nomol. Derzue drucksch unter däre Täxtvorschau nomol uf „Syte spychere“.
Wänn s Problem blybt, [[Special:UserLogout|mäld Di ab]] un derno wider aa.</strong>',
'session_fail_preview_html'        => "<strong>Dyyni Bearbeitig het nid chenne gspycheret wäre, wel Sitzigsdate verlore gange sin.</strong>

''Wel in {{SITENAME}} s Spychere vun ere reine HTML aktiviert isch, isch d Vorschau usbländet wore, zum JavaScript-Attacke z verhindere.''

<strong>
Bitte versuech s nomol. Derzue drucksch unter däre Täxtvorschau nomol uf „Syte spicherne“.
Wänn s Problem blybt, [[Special:UserLogout|mäld Di ab]] un derno wider aa.</strong>",
'token_suffix_mismatch'            => '<strong>Dyyni Bearbeitig isch zruckgwise wore, wel Dyy Browser Zeiche im Bearbeite-Token verstimmlet het.
S Spichere cha dr Inhalt vu dr Syte hii mache. Des git s e mänkmol, wänn eber e anonyme Proxy-Dienscht brucht, wu Fähler macht.</strong>',
'editing'                          => 'Bearbeite vo «$1»',
'editingsection'                   => 'Bearbeite vo «$1» (Absatz)',
'editingcomment'                   => 'Bearbeite vu $1 (Kommentar)',
'editconflict'                     => 'Bearbeitigs-Konflikt: «$1»',
'explainconflict'                  => "Öpper anders het dä Artikel gänderet, wo du ne sälber am Ändere bisch gsy.
Im obere Tekschtfäld steit der jitzig Artikel.
Im untere Tekschtfält stöh dyni Änderige.
Bitte überträg dyni Änderigen i ds obere Tekschtfäld.
We du «Syte spychere» drücksch, de wird '''nume''' der Inhalt vom obere Tekschtfäld gspycheret.",
'yourtext'                         => 'Ihre Tekscht',
'storedversion'                    => 'Gspychereti Version',
'nonunicodebrowser'                => '<strong>Obacht:</strong> Dyy Browser cha Unicode-Zeiche nid richtig verschaffe. Bitte verwänd e andere Browser zum Syte bearbeite.',
'editingold'                       => '<strong>Obacht: Du bisch en alti Version vo däm Artikel am Bearbeite.
Alli nöiere Versione wärden überschribe, we du uf «Syte spychere» drücksch.</strong>',
'yourdiff'                         => 'Unterschid',
'copyrightwarning'                 => "<strong>Bitte <big>kopier kener Internetsyte</big>, wo nid dyner eigete sy, bruuch <big>kener urhäberrächtlech gschützte Wärch</big> ohni Erloubnis vor Copyright-Inhaberschaft!</strong><br />
Hiemit gisch du zue, das du dä Tekscht <strong>sälber gschribe</strong> hesch, das der Tekscht Allgmeinguet (<strong>public domain</strong>) isch, oder das der <strong>Copyright-Inhaberschaft</strong> iri <strong>Zuestimmig</strong> het 'gä. Falls dä Tekscht scho nöumen anders isch veröffentlecht worde, de schryb das bitte uf d Diskussionssyte.
<i>Bis dir bewusst, dass alli {{SITENAME}}-Byträg outomatisch under der „$2“ stöh (für Details vgl. $1). We du nid wosch, das anderi dy Bytrag chöu veränderen u wyterverbreite, de drück nid uf „Syte spychere“.</i>",
'copyrightwarning2'                => 'Dängge Si dra, dass alli Änderige {{GRAMMAR:dativ {{SITENAME}}}} vo andere Benutzer wider gänderet oder glöscht chönne wärde. Wenn Si nit wänn, dass ander Lüt an Ihrem Tekscht ummedoktere denn schicke Si ihn jetz nit ab.<br />
Si verspräche uns usserdäm, dass Si des alles selber gschribe oder vo nere Quälle kopiert hen, wo Public Domain odr sunscht frei isch (lueg $1 für Details).
<strong>SETZE SI DO OHNI ERLAUBNIS KEINI URHEBERRÄCHTLICH GSCHÜTZTI WÄRK INE!</strong>',
'longpagewarning'                  => '<span style="color:#ff0000">WARNIG:</span> Die Syten isch $1 kB gross; elteri Browser chönnte Problem ha, Sytene z bearbeite wo grösser sy als 32 kB. Überleg bitte, öb du Abschnitt vo dere Syte zu eigete Sytene chönntsch usboue.',
'longpageerror'                    => '<strong>FÄHLER: Dä Täxt, wu Du spichere wit, isch $1 KB gross. Des isch gresser wie s erlaubt Maximum vu $2 KB – s Spichere isch nid megli.</strong>',
'readonlywarning'                  => '<strong>ACHTUNG: Die Datebank isch fir Wartigsarbete gesperrt. Wäge däm chenne Dyyni Änderige im Momänt nid gspicheret wäre.
Sichere de Täxt bitte lokal uf Dyynem Computer un versuech speter nomol, d Änderige z ibertrage.</strong>

Grund fir d Sperri: $1',
'protectedpagewarning'             => '<strong>WARNIG: Die Syten isch gsperrt worde, so das se nume Benutzer mit Sysop-Rechten chöi verändere.</strong>',
'semiprotectedpagewarning'         => "'''''Halbsperrig''': Die Syte cha vu aagmäldete Benutzern bearbeitet wäre. Fir Benutzer, wu nid oder grad erscht aagmäldet sin, isch dr Schrybzuegang gsperrt.''",
'cascadeprotectedwarning'          => "'''ACHTIG: Die Syte isch gsperrt. Wäg däm cha si nume vu Benutzer mit Ammannerächt bearbeitet wäre. Si isch in die {{PLURAL:$1|Syte|Syte}} yybunde, wu mit ere Kaskadesperroption gschitzt {{PLURAL:$1|isch|sin}}:'''",
'titleprotectedwarning'            => '<strong>ACHTIG: S Aalege vu däre Syte isch gsperrt. Wäg däm cha si nume vu bstimmte Benutzer aaglait wäre.</strong>',
'templatesused'                    => 'Selli Vorlage wärde in sellem Artikel bruucht:',
'templatesusedpreview'             => 'Vorlage wo i dere Vorschou vorchöme:',
'templatesusedsection'             => 'Vorlage, wu in däm Abschnitt brucht wäre:',
'template-protected'               => '(schrybgschützt)',
'template-semiprotected'           => '(schrybgschitzt fir Benutzer, wo nit aagmäldet oder nei sin)',
'hiddencategories'                 => 'Die Syte ghert zue {{PLURAL:$1|einere versteckte Kategori|$1 versteckte Kategorie}}:',
'edittools'                        => '<!-- Selle Text wird untr em "ändere"-Formular un bim "Uffelade"-Formular aagzeigt. -->',
'nocreatetitle'                    => 'S Aalege vu neje Syte isch yygschränkt.',
'nocreatetext'                     => "Uf {{SITENAME}} isch d Erstellig vo nöue Syten ygschränkt.
Du chasch nur Syten ändere, wo's scho git, oder muesch di [[Special:UserLogin|amälde]].",
'nocreate-loggedin'                => 'Du bisch nid berächtigt, neji Syte aazlege.',
'permissionserrors'                => 'Berächtigungsfähler',
'permissionserrorstext'            => 'Du bisch nid berächtigt, die Aktion uszfiere. {{PLURAL:$1|Grund|Grind}}:',
'permissionserrorstext-withaction' => 'Du bisch nid berächtigt, $2.
{{PLURAL:$1|Grund|Grind}}:',
'recreate-deleted-warn'            => "'''Obacht: Du bisch e Syten am kreiere, wo scho einisch isch glösche worde.'''

Bitte überprüeff, öb s sinnvoll isch, mit em Bearbeite wyter z mache.
Hie gsehsch ds Lösch-Logbuech vo dere Syte:",
'deleted-notice'                   => 'Die Syte isch glescht wore. Do chunnt e Uuszuug us em Lesch-Logbuech fir die Syte.',
'deletelog-fulllog'                => 'Vollständigs Lesch-Logbuech',
'edit-hook-aborted'                => 'D Bearbeitig isch ohni Erchlärung dur e Schnittstell abbroche wore.',
'edit-gone-missing'                => 'D Syte het nid chenne aktalisiert wäre.
Si isch schyns glescht wore.',
'edit-conflict'                    => 'Bearbeitigskonflikt.',
'edit-no-change'                   => 'Dyyni Bearbeitig isch ignoriert wore, wel kei Änderig am Täxt gmacht woren isch.',
'edit-already-exists'              => 'Di nej Syte het nid chenne aaglait wäre, wel s si scho git.',

# Parser/template warnings
'expensive-parserfunction-warning'        => 'Achtig: In däre Syte het s z vyyl Ufruef vu ufwändige Parserfunktione.

S {{PLURAL:$2|derf nid meh wie ein Ufruef|derfe nid meh wie $1 Ufruef}} gee.',
'expensive-parserfunction-category'       => 'Syte, wu ufwändigi Parserfunktione z vyylmol ufruefe',
'post-expand-template-inclusion-warning'  => 'Warnig: D Gressi vu yybundene Vorlage isch z gross, e Teil Vorlage chenne nid yybunde wäre.',
'post-expand-template-inclusion-category' => 'Syte, wu d maximal Gressi vu dr yybundene Vorlage iberschritte isch',
'post-expand-template-argument-warning'   => 'Warnig: In däre Syte het s zmindescht ei Argumänt in ere Vorlag, wu z gross isch, wänn s expandiert isch. Die Argumänt wäre ignoriert.',
'post-expand-template-argument-category'  => 'Syte, wu s ignorierti Vorlageargumänt din het',
'parser-template-loop-warning'            => 'Vorlagelätsch entdeckt: [[$1]]',
'parser-template-recursion-depth-warning' => 'Vorlagerekursionstiefegränz iberschritte ($1)',

# "Undo" feature
'undo-success' => 'Zum die Änderig ruckgängig z mache, kontrollier bitte d Bearbeitig in dr Verglichsaasicht un druck derno uf „Syte spichere“.',
'undo-failure' => '<span class="error">D Änderig het nid chenne ruckgängig gmacht wäre, wel dää Abschnitt mittlerwyli gänderet woren isch.</span>',
'undo-norev'   => 'D Bearbeitig het nid chenne ruckgängig gmacht wäre, wel si nid vorhande oder glescht isch.',
'undo-summary' => 'D Änderig $1 vu [[Special:Contributions/$2|$2]] ([[User talk:$2|Diskussion]]) isch ruckgängig gmacht wore.',

# Account creation failure
'cantcreateaccounttitle' => 'Benutzerchonto cha nid aaglait wäre.',
'cantcreateaccount-text' => "S Aalege vu me Benutzerchonto vu dr IP-Adräss '''($1)''' isch dur [[User:$3|$3]] gsperrt wore.

Grund vu dr Sperri: ''$2''",

# History pages
'viewpagelogs'           => 'Logbüecher für die Syten azeige',
'nohistory'              => 'S git kei Versionsgschicht fir die Syte.',
'currentrev'             => 'Itzigi Version',
'currentrev-asof'        => 'Aktuälli Version vu $1',
'revisionasof'           => 'Version vo $1',
'revision-info'          => 'Alti Bearbeitig vom $1 dür $2', # Additionally available: $3: revision id
'previousrevision'       => '← Vorderi Version',
'nextrevision'           => 'Nächschti Version →',
'currentrevisionlink'    => 'Itzigi Version',
'cur'                    => 'Jetz',
'next'                   => 'Nächschti',
'last'                   => 'vorane',
'page_first'             => 'Afang',
'page_last'              => 'Ändi',
'histlegend'             => 'Du chasch zwei Versionen uswähle und verglyche.<br />
Erklärig: (aktuell) = Underschid zu jetz,
(vorane) = Underschid zur alte Version, <strong>K</strong> = chlyni Änderig',
'history-fieldset-title' => 'Suech in dr Versionsgschicht',
'deletedrev'             => '[glescht]',
'histfirst'              => 'Eltischti',
'histlast'               => 'Nöischti',
'historysize'            => '({{PLURAL:$1|1 Byte|$1 Bytes}})',
'historyempty'           => '(läär)',

# Revision feed
'history-feed-title'          => 'Versionsgschicht',
'history-feed-description'    => 'Versionsgschicht fir die Syte in {{SITENAME}}',
'history-feed-item-nocomment' => '$1 um $2', # user at time
'history-feed-empty'          => 'Di aagforderet Syte git s nid. Villicht isch si glescht oder verschobe wore. [[Special:Search|Suech]] {{SITENAME}} fir neji Syte, wu passe.',

# Revision deletion
'rev-deleted-comment'         => '(Bearbeitigskommentar uusegnuh)',
'rev-deleted-user'            => '(Benutzername uusegnuh)',
'rev-deleted-event'           => '(Logbuechaktion uusegnuh)',
'rev-deleted-text-permission' => '<div class="mw-warning plainlinks"> Die Version isch glescht wore un chaa nimi aagluegt wäre.
Information zue dr Leschig un e Begrindig het s im [{{fullurl:Special:Log/delete|page={{FULLPAGENAMEE}}}} Lesch-Logbuech].</div>',
'rev-deleted-text-view'       => '<div class="mw-warning plainlinks">Die Version isch glescht wore un cha nimi aagluegt wäre. As Amman chasch si aber alno aaluege uf {{SITENAME}}.
Informatione zue dr Leschig un e Begrindig het s im [{{fullurl:Special:Log/delete|page={{FULLPAGENAMEE}}}} Lesch-Logbuech].</div>',
'rev-delundel'                => 'zeig/versteck',
'revisiondelete'              => 'Versione lesche/widerherstelle',
'revdelete-nooldid-title'     => 'Kei Version aagee',
'revdelete-nooldid-text'      => 'Du hesch entwäder kei Version aagee, wu die Aktion soll usgfiert wäre, die usgwehlt Version git s nid oder Du versuechsch di aktuäll Version z verstecke.',
'revdelete-selected'          => "'''{{PLURAL:$2|Usgwehlti Version|Usgwehlti Versione}} vu [[:$1]]:'''",
'logdelete-selected'          => "'''{{PLURAL:$1|Usgwehlte Logbuechyytrag|Usgwehlti Logbuechyytreg}}:'''",
'revdelete-text'              => "'''Dr Inhalt oder anderi Bstandteil vu gleschte Versione chenne nimi aagluegt wäre, si erschyyne aber alno as Yytreg in dr Versiongschicht.'''

{{SITENAME}}-Ammanne chenne dr Inhalt, wu glescht isch, oder di andre gleschte Bstandteil alno aaluege un au widerherstelle, user s isch feschtgleit, ass d Zuegangsbschränkige au fir Ammanne gälte.",
'revdelete-legend'            => 'Setze vu dr Sichtbarkeits-Yyschränkige',
'revdelete-hide-text'         => 'Täxt vu dr Version versteckle',
'revdelete-hide-name'         => 'Logbuech-Aktion versteckle',
'revdelete-hide-comment'      => 'Bearbeitigskommentar versteckle',
'revdelete-hide-user'         => 'Benutzername/d IP vum Bearbeiter versteckle',
'revdelete-hide-restricted'   => 'Die Yyschränkige gälte au fir Ammanne un des Formular wird gsperrt',
'revdelete-suppress'          => 'Grund vu dr Leschig au vor dr Ammanne versteckle',
'revdelete-hide-image'        => 'Bildinhalt versteckle',
'revdelete-unsuppress'        => 'Yyhscränkige fir di widerhergstellte Versione ufhebe',
'revdelete-log'               => 'Kommentar/Begrindig (erschyynt im Logbuech):',
'revdelete-submit'            => 'Uf usgwehlti Version aawände',
'revdelete-logentry'          => 'het d Versionsaasicht fir „[[$1]]“ gänderet',
'logdelete-logentry'          => 'het d Sichtbarkeit fir „[[$1]]“ gänderet',
'revdelete-success'           => "'''Versionsaasicht erfolgryych gänderet.'''",
'logdelete-success'           => "'''Logbuechaasicht erfolgryych gänderet.'''",
'revdel-restore'              => 'Sichtbarkeit ändere',
'pagehist'                    => 'Versionsgeschicht',
'deletedhist'                 => 'Gleschti Versione',
'revdelete-content'           => 'Syteinhalt',
'revdelete-summary'           => 'Zämmefassig',
'revdelete-uname'             => 'Benutzername',
'revdelete-restricted'        => 'Yyschränkige gälte au fir Ammanne',
'revdelete-unrestricted'      => 'Yyschränkige fir Ammanne ufghobe',
'revdelete-hid'               => 'versteckleti $1',
'revdelete-unhid'             => 'macht $1 wider effetlig',
'revdelete-log-message'       => '$1 fir $2 {{PLURAL:$2|Version|Versione}}',
'logdelete-log-message'       => '$1 fir $2 {{PLURAL:$2|Logbuechyytrag|Logbuechyytreg}}',

# Suppression log
'suppressionlog'     => 'Oversight-Logbuech',
'suppressionlogtext' => 'Des isch s Logbuech vu dr Oversight-Aktione (Änderige vu dr Sichtbarkeit vu Versione, Bearbeitigskommentar, Benutzernäme un Benutzersperrine).',

# History merging
'mergehistory'                     => 'Versionsgschichte zämmefiere',
'mergehistory-header'              => 'Mit däre Spezialsyte chasch d Versionsgschicht vun ere Ursprungssyte mit dr Versionsgchicht vun ee Ziilsyte zämefiere.
Stell sicher, ass d Versionsgschicht vun eme Artikel historisch korrekt isch.',
'mergehistory-box'                 => 'Versionsgschichte vu zwoo Syte zämefiere',
'mergehistory-from'                => 'Ursprungssyte:',
'mergehistory-into'                => 'Ziilsyte:',
'mergehistory-list'                => 'Versione, wu zämegfierd chenne wäre',
'mergehistory-merge'               => 'Die Versione vu „[[:$1]]“ chenne no „[[:$2]]“ ibertrait wäre. Markier d Version, wu d Versione bis zuen ere solle yyschliessli ibertrage wäre. Bitte gib Acht, ass d Nutzig vu dr Navigationsgleicher d Uuswahl zrucksetzt.',
'mergehistory-go'                  => 'Zeig d Versione, wu zämegfierd chenne wäre',
'mergehistory-submit'              => 'Fier Versione zäme',
'mergehistory-empty'               => 'S chenne kei Versione zämegfierd wäre.',
'mergehistory-success'             => '{{PLURAL:$3|1 Version|$3 Versione}} vu „[[:$1]]“ isch no „[[:$2]]“ zämegfierd.',
'mergehistory-fail'                => 'Zämefierig vu dr Versione nid megli, bitte prief d Syte un d Zytaagobe.',
'mergehistory-no-source'           => 'Ursprungssyte „$1“ isch nit vorhande.',
'mergehistory-no-destination'      => 'Ziilsyte „$1“ isch nit vorhande.',
'mergehistory-invalid-source'      => 'Ursprungssyte muess e giltige Sytename syy.',
'mergehistory-invalid-destination' => 'Ziilsyte muess e giltige Sytename syy.',
'mergehistory-autocomment'         => '„[[:$1]]“ zämegfierd no „[[:$2]]“',
'mergehistory-comment'             => '„[[:$1]]“ zämegfierd no „[[:$2]]“: $3',
'mergehistory-same-destination'    => 'Uusgangs- un Ziilsyte derfe nit di nämlige syy',

# Merge log
'mergelog'           => 'Zämefierigs-Logbuech',
'pagemerge-logentry' => 'het [[$1]] in [[$2]] zämegfierd (Versione bis $3)',
'revertmerge'        => 'Zämefierig ruckgängig mache',
'mergelogpagetext'   => 'Des isch e Lischt vu dr letschte Zämefierige vu Versionsgschichte.',

# Diffs
'history-title'           => 'Versionsgschicht vo „$1“',
'difference'              => '(Unterschide zwüsche Versione)',
'lineno'                  => 'Zyle $1:',
'compareselectedversions' => 'Usgwählti Versione verglyche',
'visualcomparison'        => 'Visuelle Verglych',
'wikicodecomparison'      => 'Wikitext-Verglych',
'editundo'                => 'rückgängig',
'diff-multi'              => '(Der Versioneverglych zeigt ou d Änderige vo {{PLURAL:$1|1 Version|$1 Versione}} derzwüsche.)',
'diff-movedto'            => 'verschobe no $1',
'diff-styleadded'         => '$1-Style zuegfiegt',
'diff-added'              => '$1 zuegfügt',
'diff-changedto'          => 'gänderet no $1',
'diff-movedoutof'         => 'verschobe us $1',
'diff-styleremoved'       => '$1-Style ewäg gmacht',
'diff-removed'            => '$1 ewäg gmacht',
'diff-changedfrom'        => 'veränderet vu $1',
'diff-src'                => 'Quälle',
'diff-withdestination'    => 'mit Ziil $1',
'diff-with'               => '&#32;mit $1 $2',
'diff-with-final'         => '&#32;un $1 $2',
'diff-width'              => 'Breiti',
'diff-height'             => 'Hechi',
'diff-p'                  => "e '''Absatz'''",
'diff-blockquote'         => "e '''Block'''",
'diff-h1'                 => "e '''Iberschrift (1. Ornig)'''",
'diff-h2'                 => "e '''Iberschrift (2. Ornig)'''",
'diff-h3'                 => "e '''Iberschrift (3. Ornig)'''",
'diff-h4'                 => "e '''Iberschrift (4. Ornig)'''",
'diff-h5'                 => "e '''Iberschrift (5. Ornig)'''",
'diff-pre'                => "e '''formatierte Block'''",
'diff-div'                => "e '''Blockelement'''",
'diff-ul'                 => "e '''Lischt'''",
'diff-ol'                 => "e '''numerierti Lischt'''",
'diff-li'                 => "e '''Lischteyytrag'''",
'diff-table'              => "e '''Tabälle'''",
'diff-tbody'              => "e '''Tabälleinhalt'''",
'diff-tr'                 => "e '''Zylete'''",
'diff-td'                 => "e '''Zälle'''",
'diff-th'                 => "e '''Spalteiberschrift'''",
'diff-br'                 => "e '''Zyleteumbruch'''",
'diff-hr'                 => "e '''horizontali Linie'''",
'diff-code'               => "e '''Beryych Computercode'''",
'diff-dl'                 => "e '''Definitionslischt'''",
'diff-dt'                 => "e '''Definitionsterm'''",
'diff-dd'                 => "e '''Definition'''",
'diff-input'              => "e '''Yygab'''",
'diff-form'               => "e '''Formular'''",
'diff-img'                => "e '''Bild'''",
'diff-span'               => "e '''Span'''",
'diff-a'                  => "e '''Gleich'''",
'diff-i'                  => "'''kursiv'''",
'diff-b'                  => "'''feist'''",
'diff-strong'             => "'''firighobe'''",
'diff-em'                 => "'''betont'''",
'diff-font'               => "'''Schriftart'''",
'diff-big'                => "'''gross'''",
'diff-del'                => "'''glescht'''",
'diff-tt'                 => "'''feschti Wyti'''",
'diff-sub'                => "'''tiefgstellt'''",
'diff-sup'                => "'''hochgstellt'''",
'diff-strike'             => "'''durgstriche'''",

# Search results
'searchresults'                    => 'Suech-Ergäbnis',
'searchresults-title'              => 'Suechergebniss fir „$1“',
'searchresulttext'                 => 'Für wiiteri Informatione zuem Sueche uff {{SITENAME}} chönne Si mol uff [[{{MediaWiki:Helppage}}|{{int:help}}]] luege.',
'searchsubtitle'                   => 'Für d Suechaafrag «[[:$1]]»',
'searchsubtitleinvalid'            => 'Für d Suechaafrag «$1»',
'noexactmatch'                     => "'''Es git kei Syte mit em Tiel „$1“.'''
Du chasch die [[:$1|Syte nöu schrybe]].",
'noexactmatch-nocreate'            => "'''S git kei Syte mit em Titel „$1“.'''",
'toomanymatches'                   => 'D Aazahl vu dr Suechergebniss isch z gross, bitte versuech e anderi Abfrog.',
'titlematches'                     => 'Iberyystimmige mit Sytentitel',
'notitlematches'                   => 'Kei Iberyystimmige mit Sytetitel',
'textmatches'                      => 'Iberyystimmige mit Inhalte',
'notextmatches'                    => 'Kei Iberyystimmige mit Inhalte',
'prevn'                            => 'vorderi $1',
'nextn'                            => 'nächschti $1',
'viewprevnext'                     => '($1) ($2) aazeige; ($3) uf ds Mal',
'searchmenu-legend'                => 'Suechoptione',
'searchmenu-exists'                => "* Syte '''[[$1]]'''",
'searchmenu-new'                   => "'''[[:$1|Leg]] d Syte ''$1'' in dem Wiki aa!'''",
'searchhelp-url'                   => 'Help:Hilf',
'searchmenu-prefix'                => '[[Special:PrefixIndex/$1|Zeige alli Syte, wu mit dem Suechbegriff aafange]]',
'searchprofile-articles'           => 'Inhaltssyte',
'searchprofile-articles-and-proj'  => 'Inhaltssyte & Projäkt',
'searchprofile-project'            => 'Projäkt',
'searchprofile-images'             => 'Dateie',
'searchprofile-everything'         => 'Alles',
'searchprofile-advanced'           => 'Erwyteret',
'searchprofile-articles-tooltip'   => 'Sueche in $1',
'searchprofile-project-tooltip'    => 'Sueche in $1',
'searchprofile-images-tooltip'     => 'Noch Bilder sueche',
'searchprofile-everything-tooltip' => 'Gsamte Inhalt dursueche (au d Diskussionssyte)',
'searchprofile-advanced-tooltip'   => 'Suech in wytere Namensryym',
'prefs-search-nsdefault'           => 'Standard-Namensryym:',
'prefs-search-nscustom'            => 'Suech in wytere Namensryym:',
'search-result-size'               => '$1 ({{PLURAL:$2|1 Wort|$2 Werter}})',
'search-result-score'              => 'Relevanz: $1 %',
'search-redirect'                  => '(Wyterleitig $1)',
'search-section'                   => '(Abschnitt $1)',
'search-suggest'                   => 'Hesch „$1“ gmeint?',
'search-interwiki-caption'         => 'Schweschterprojäkt',
'search-interwiki-default'         => '$1 Ergebniss:',
'search-interwiki-more'            => '(meh)',
'search-mwsuggest-enabled'         => 'mit Vorschleg',
'search-mwsuggest-disabled'        => 'kei Vorschleg',
'search-relatedarticle'            => 'Verwandti',
'mwsuggest-disable'                => 'Vorschleg per Ajax deaktiviere',
'searchrelated'                    => 'verwandt',
'searchall'                        => 'alli',
'showingresults'                   => "Do {{PLURAL:$1|isch '''1''' Ergebnis|sin '''$1''' Ergebniss}}, s fangt aa mit dr Nummerer '''$2.'''",
'showingresultsnum'                => "Do {{PLURAL:$3|isch '''1''' Ergebnis|sin '''$3''' Ergebniss}}, s fangt aa mit dr Nummere '''$2.'''",
'showingresultstotal'              => "S {{PLURAL:$4|folgt s Suechergebnis '''$1''' vu '''$3:'''|folge d Suechergebniss '''$1–$2''' vu '''$3:'''}}",
'nonefound'                        => "'''Hiiwyys:''' S wäre standardmässig nume e Teil Namensryym dursuecht. Setz ''all:'' vor Dyy Suechbegriff go alli Syte (mit Diskussionssyte, Vorlage usw.) dursueche oder diräkt dr Name vum Namensruum, wu sett dursuecht wäre.",
'search-nonefound'                 => 'Fir Dyyni Suechaafrog sin keini Ergebniss gfunde wore.',
'powersearch'                      => 'Erwytereti Suechi',
'powersearch-legend'               => 'Erwytereti Suech',
'powersearch-ns'                   => 'Suech in Namensryym:',
'powersearch-redir'                => 'Wyterleitige aazeige',
'powersearch-field'                => 'Suech no:',
'search-external'                  => 'Externi Suech',
'searchdisabled'                   => 'D {{SITENAME}}-Suech isch deaktiviert. Du chasch mit Google sueche, s cha aber syy ass dr Suechindex vu Google fir {{SITENAME}} veraltet isch.',

# Preferences page
'preferences'               => 'Iistellige',
'mypreferences'             => 'Ystellige',
'prefs-edits'               => 'Aazahl vu dr Bearbeitige:',
'prefsnologin'              => 'Nid aagmäldet',
'prefsnologintext'          => 'Du muesch <span class="plainlinks">[{{fullurl:Special:UserLogin|returnto=$1}} aagmäldet]</span> sy, für Benutzerystellige chönne z ändere',
'prefsreset'                => 'Du hesch itz wider Standardystellige',
'qbsettings'                => 'Syteleischte',
'qbsettings-none'           => 'Keini',
'qbsettings-fixedleft'      => 'Links, fescht',
'qbsettings-fixedright'     => 'Rächts, fescht',
'qbsettings-floatingleft'   => 'Links, in dr Schwebi',
'qbsettings-floatingright'  => 'Rächts, in dr Schwebi',
'changepassword'            => 'Passwort ändere',
'skin'                      => 'Skin',
'skin-preview'              => 'Vorschou',
'math'                      => 'TeX',
'dateformat'                => 'Datumsformat',
'datedefault'               => 'kei Aagab',
'datetime'                  => 'Datum un Zit',
'math_failure'              => 'Parser-Fähler',
'math_unknown_error'        => 'Nit bekannte Fähler',
'math_unknown_function'     => 'Nit bekannti Funktion',
'math_lexing_error'         => "'Lexing'-Fähler",
'math_syntax_error'         => 'Syntaxfähler',
'math_image_error'          => 'd PNG-Konvertierig het nit funktioniert;
prief di korrekt Installation vu latex, dvips, gs un convert',
'math_bad_tmpdir'           => 'S temporär Verzeichnis fir mathematischi Formle cha nit aagleit oder bschribe wäre.',
'math_bad_output'           => 'S Ziilverzeichnis fir mathematischi Formle cha nit aagleit oder bschribe wäre.',
'math_notexvc'              => 'S texvc-Programm isch nit gfunde wore. Bitte acht gee uf math/README.',
'prefs-personal'            => 'Benutzerdate',
'prefs-rc'                  => 'Letschti Änderige',
'prefs-watchlist'           => 'Beobachtigslischte',
'prefs-watchlist-days'      => 'Aazahl vu dr Täg, wu d Beobchtigslischt standardmässig soll umfasse:',
'prefs-watchlist-days-max'  => '(Maximal 7 Täg)',
'prefs-watchlist-edits'     => 'Maximali Zahl vu dr Yyträg:',
'prefs-watchlist-edits-max' => '(Maximali Aazahl: 1000)',
'prefs-misc'                => 'Verschidnigs',
'prefs-resetpass'           => 'Passwort ändere',
'saveprefs'                 => 'Änderige spychere',
'resetprefs'                => 'Änderige doch nid spychere',
'textboxsize'               => 'Tekscht-Ygab',
'prefs-edit-boxsize'        => 'Gressi vum Bearbeitigsfänschter.',
'rows'                      => 'Zylene',
'columns'                   => 'Spaltene',
'searchresultshead'         => 'Suech-Ergäbnis',
'resultsperpage'            => 'Träffer pro Syte',
'contextlines'              => 'Zyle pro Träffer',
'contextchars'              => 'Zeiche pro Zyle',
'stub-threshold'            => 'Gleichformatierig <a href="#" class="stub">vu chleine Syte</a> (in Byte):',
'recentchangesdays'         => 'Aazahl vu dr Täg, wu d Lischt vu dr  „Letschte Änderige“ standardmässig soll umfasse:',
'recentchangesdays-max'     => '(Maximal $1 {{PLURAL:$1|Tag|Täg}})',
'recentchangescount'        => 'Aazahl «letschti Änderige»',
'savedprefs'                => 'Dyni Ystellige sy gspycheret worde.',
'timezonelegend'            => 'Zytzone',
'timezonetext'              => 'Zytdifferänz i Stunden aagä zwüsche der Serverzyt u dyre Lokalzyt',
'localtime'                 => 'Ortszyt',
'timezoneoffset'            => 'Unterschid¹',
'servertime'                => 'Aktuelli Serverzyt',
'guesstimezone'             => 'Vom Browser la ysetze',
'allowemail'                => 'andere Benutzer erlaube, dass si Ihne E-Mails chenne schicke',
'prefs-searchoptions'       => 'Suechoptione',
'prefs-namespaces'          => 'Namensryym',
'defaultns'                 => 'Namensrüüm wo standardmäässig söll gsuecht wärde:',
'default'                   => 'Voryystellig',
'files'                     => 'Bilder',

# User rights
'userrights'               => 'Benutzerrächtsverwaltig', # Not used as normal message but as header for the special page itself
'userrights-lookup-user'   => 'Verwalt d Gruppezuegherigkeit',
'userrights-user-editname' => 'Benutzername:',
'editusergroup'            => 'Ändere vo Benutzerrächt',
'editinguser'              => "Benutzerrächt ändere vu '''[[User:$1|$1]]''' ([[User talk:$1|{{int:talkpagelinktext}}]] | [[Special:Contributions/$1|{{int:contribslink}}]])",
'userrights-editusergroup' => 'Bearbeit d Gruppezuegherigkeit vum Benutzers',
'saveusergroups'           => 'Spychere d Gruppezuegherigkeit',
'userrights-groupsmember'  => 'Mitgliid vu:',
'userrights-groups-help'   => 'Du chasch d Gruppezuegherigkeit fir dää Benutzer ändere:
* E markiert Chäschtli bedytet, ass dr Benutzer Mitgliid vu däre Gruppe isch
* E * bedytet, ass Du s Benutzerrächt nit wider chasch zruckneh, wänn s erteilt isch (oder umgchehrt).',

# Groups
'group-bot'        => 'Bötli',
'group-sysop'      => 'Ammanne',
'group-bureaucrat' => 'Bürokrate',
'group-all'        => '(alli)',

'group-user-member'          => 'Benutzer',
'group-autoconfirmed-member' => 'Bstätigte Benutzer',
'group-bot-member'           => 'Bötli',
'group-sysop-member'         => 'Ammann',
'group-bureaucrat-member'    => 'Bürokrat',

'grouppage-sysop' => '{{ns:project}}:Ammanne',

# User rights log
'rightslog'     => 'Benutzerrächt-Logbuech',
'rightslogtext' => 'Des ischs Logbuech vun de Änderunge on Bnutzerrechte.',

# Recent changes
'nchanges'                       => '$1 {{PLURAL:$1|Änderig|Änderige}}',
'recentchanges'                  => 'Letschti Änderige',
'recentchangestext'              => 'Uff däre Syte chönne Si die letschte Änderige in däm Wiki aaluege.',
'recentchanges-feed-description' => 'Di letschten Änderige vo {{SITENAME}} i däm Feed abonniere.',
'rcnote'                         => "Azeigt {{PLURAL:$1|wird '''1''' Änderig|wärde di letschte '''$1''' Änderige}} {{PLURAL:$2|vom letschte Tag|i de letschte '''$2''' Täg}} (Stand: $4, $5)",
'rcnotefrom'                     => 'Des sin d Ändrige syter <b>$2</b> (bis zem <b>$1</b> zeigt).',
'rclistfrom'                     => '<small>Nöji Änderige ab $1 aazeige (UTC)</small>',
'rcshowhideminor'                => 'Chlynigkeite $1',
'rcshowhidebots'                 => 'Bots $1',
'rcshowhideliu'                  => 'Aagmoldene Benützer $1',
'rcshowhideanons'                => 'Nid aagmäldete Benutzer $1',
'rcshowhidepatr'                 => 'Vum Fäldhieter aagluegti Änderige $1',
'rcshowhidemine'                 => 'Eigeni Änderige $1',
'rclinks'                        => 'Zeig di letschte $1 Änderige vo de vergangene $2 Täg.<br />$3',
'diff'                           => 'Unterschid',
'hist'                           => 'Versione',
'hide'                           => 'usblände',
'show'                           => 'yblände',
'minoreditletter'                => 'C',
'newpageletter'                  => 'N',
'boteditletter'                  => 'B',

# Recent changes linked
'recentchangeslinked'          => 'Verlinktes prüefe',
'recentchangeslinked-title'    => 'Änderigen a Sytene, wo „$1“ druf verlinkt',
'recentchangeslinked-noresult' => 'Kener Änderigen a verlinkte Sytenen im usgwählte Zytruum.',
'recentchangeslinked-summary'  => "Die Spezialsyte zeigt d Änderige vo allne Syte, wo ei vo dir bestimmti Syte druf verlinkt, bzw. vo allne Syte, wo zu eire vo dir bestimmte Kategorie ghöre.
Sytene, wo zu dyre [[Special:Watchlist|Beobachtigslischte]] ghöre, erschyne '''fett'''.",

# Upload
'upload'            => 'Datei uffelade',
'uploadbtn'         => 'Bild lokal ufelade',
'uploadnologintext' => 'Si mien [[Special:UserLogin|aagmäldet syy]], zum Dateie uffelade z chenne.',
'uploadtext'        => "Verwänd des Formular unte zum Dateie uffelade.
Zum friejer uffegladeni Dateie aazluege oder z sueche lueg uf dr [[Special:FileList|Lischt vu uffegladene Dateie]], 
Weli Dateie uffeglade sin, sihsch im [[Special:Log/upload|Logbuech vu dr uffegladene Dateie]], weli glescht sin im [[Special:Log/delete|Lesch-Logbuech]]

Zum e Datei oder e Bild in ere Syte yyzböue, schryybsch eifach:
* '''<nowiki>[[</nowiki>{{ns:file}}<nowiki>:file.jpg]]</nowiki>''' fir di voll Version vu dr Datei
* '''<nowiki>[[</nowiki>{{ns:file}}<nowiki>:file.png|al text]]</nowiki>''' fir e 200 Pixel grossi Version im e Chaschte mit 'alt text' as Bschrybig
* '''<tt><nowiki>[[</nowiki>{{ns:media}}<nowiki>:File.ogg]]</nowiki></tt>''' fir e diräkt Gleich zuer dr Datei ohni ass si aazeigt wird",
'uploadlogpage'     => 'Dateie-Logbuech',
'uploadedimage'     => 'het „[[$1]]“ ufeglade',

# Special:FileList
'imagelist' => 'Lischte vo Bilder',

# File description page
'filehist'                  => 'Dateiversione',
'filehist-help'             => 'Klick uf e Zytpunkt zu aazeige, wie s dert usgsäh het.',
'filehist-current'          => 'aktuell',
'filehist-datetime'         => 'Version vom',
'filehist-user'             => 'Benutzer',
'filehist-dimensions'       => 'Mäß',
'filehist-filesize'         => 'Dateigrößi',
'filehist-comment'          => 'Kommentar',
'imagelinks'                => 'Bildverwyys',
'linkstoimage'              => 'Di {{PLURAL:$1|Syte|$1 Sytene}} händ en Link zu dem Bild:',
'nolinkstoimage'            => 'Kei Artikel verwändet des Bild.',
'sharedupload'              => 'Die Datei wird vu verschidene Projekt brucht.',
'noimage'                   => 'Es git kei Datei mit däm Name, aber du chasch se $1.',
'noimage-linktext'          => 'ufelade',
'uploadnewversion-linktext' => 'E nöui Version vo dere Datei ufelade',

# MIME search
'mimesearch' => 'MIME-Suechi',

# Unwatched pages
'unwatchedpages' => 'Unbeobachteti Sytene',

# List redirects
'listredirects' => 'Lischte vo Wyterleitige (Redirects)',

# Unused templates
'unusedtemplates' => 'Nid bruuchti Vorlage',

# Random page
'randompage' => 'Zuefalls-Artikel',

# Random redirect
'randomredirect' => 'Zuefälligi Wyterleitig',

# Statistics
'statistics'              => 'Statistik',
'statistics-header-users' => 'Benutzer-Statischtik',

'disambiguations'     => 'Begriffsklärigssytene',
'disambiguationspage' => 'Template:Begriffsklärig',

'doubleredirects'       => 'Doppleti Wyterleitige (Redirects)',
'double-redirect-fixer' => 'DoubleRedirectBot',

'brokenredirects'     => 'Kaputti Wyterleitige',
'brokenredirectstext' => 'Die Wyterleitige fiere zue Artikel, wu s gar nid git.',

'withoutinterwiki' => 'Sytenen ohni Links zu andere Sprache',

'fewestrevisions' => 'Syte mit de wenigschte Bearbeitige',

# Miscellaneous special pages
'nbytes'                  => '$1 {{PLURAL:$1|Byte|Bytes}}',
'ncategories'             => '$1 {{PLURAL:$1|Kategori|Kategorie}}',
'nlinks'                  => '$1 {{PLURAL:$1|Gleich|Gleicher}}',
'nmembers'                => '$1 {{PLURAL:$1|Syte|Sytene}}',
'nrevisions'              => '$1 {{PLURAL:$1|Revision|Revisione}}',
'nviews'                  => '$1 {{PLURAL:$1|Betrachtig|Betrachtige}}',
'lonelypages'             => 'Verwaisti Sytene',
'uncategorizedpages'      => 'Nit kategorisierte Sytene',
'uncategorizedcategories' => 'Nit kategorisierte Kategorie',
'uncategorizedimages'     => 'Nid kategorisierti Dateie',
'uncategorizedtemplates'  => 'Nid kategorisierti Vorlage',
'unusedcategories'        => 'Nid ’bruuchti Kategorië',
'unusedimages'            => 'Verwaiste Bilder',
'popularpages'            => 'Beliebti Artikel',
'wantedcategories'        => 'Bruuchti Kategorie, wo s no nid git',
'wantedpages'             => 'Artikel, wo fähle',
'mostlinked'              => 'Syte, wo am meischte vergleicht sin',
'mostlinkedcategories'    => 'Am meischte verlinkti Kategorië',
'mostlinkedtemplates'     => 'Am meischten yybouti Vorlage',
'mostcategories'          => 'Sytene mit de meischte Kategorië',
'mostimages'              => 'Am meischte verlinkti Dateie',
'mostrevisions'           => 'Syte mit de meischte Bearbeitige',
'prefixindex'             => 'Alli Artikle (mit Präfix)',
'shortpages'              => 'Churzi Artikel',
'longpages'               => 'Langi Artikel',
'deadendpages'            => 'Artikel ohni Links («Sackgasse»)',
'protectedpages'          => 'Gschützti Sytene',
'listusers'               => 'Lischte vo Benutzer',
'newpages'                => 'Nöji Artikel',
'ancientpages'            => 'alti Sytene',
'move'                    => 'verschiebe',
'movethispage'            => 'Artikel verschiebe',
'pager-newer-n'           => '{{PLURAL:$1|nächschte|nächschte $1}}',
'pager-older-n'           => '{{PLURAL:$1|vorige|vorige $1}}',

# Book sources
'booksources' => 'ISBN-Suech',

# Special:Log
'specialloguserlabel'  => 'Benutzer:',
'speciallogtitlelabel' => 'Titel:',
'log'                  => 'Logbüecher',
'all-logs-page'        => 'Alli Logbüecher',
'alllogstext'          => 'Kombinierti Aasicht vu alle in {{SITENAME}} gfierte Protokoll.
D Aazeig cha dur d Uuswahl vun eme Protokoll, eme Benutzername oder eme Sytename yygschränkt wäre (Acht gee uf d Gross- un Chleischrybig).',
'logempty'             => 'Kei Yyträg gfunde, wu passe.',

# Special:AllPages
'allpages'          => 'alli Sytene',
'alphaindexline'    => 'vo $1 bis $2',
'nextpage'          => 'Nächscht Syte ($1)',
'prevpage'          => 'Vorderi Syte ($1)',
'allpagesfrom'      => 'Syte aazeige vo:',
'allarticles'       => 'alli Artikel',
'allinnamespace'    => 'alli Sytene im Namensruum $1',
'allnotinnamespace' => 'alli Sytene, wo nit im $1 Namensruum sin',
'allpagesprev'      => 'Füehrigs',
'allpagesnext'      => 'nächschts',
'allpagessubmit'    => 'gang',
'allpagesprefix'    => 'Alli Sytene mit em Präfix:',

# Special:Categories
'categories'         => 'Kategorie',
'categoriespagetext' => 'Selli Kategorie gits in dem Wiki:',

# Special:LinkSearch
'linksearch'       => 'Suech Netzgleicher',
'linksearch-pat'   => 'Suechmuschter:',
'linksearch-ns'    => 'Namensruum:',
'linksearch-ok'    => 'Sueche',
'linksearch-text'  => 'S chönne Platzhalter wie "*.wikipedia.org" benutzt werre.<br />Unterschtützti Protokoll: <tt>$1</tt>',
'linksearch-line'  => '$1 isch vo $2 verknüpft',
'linksearch-error' => 'Platzhalter chönne numme am Aafang verwändet werre.',

# E-mail user
'mailnologin'     => 'Du bisch nid aagmäldet oder hesch keis Mail aaggä',
'mailnologintext' => 'Du muesch [[Special:UserLogin|aagmäldet syy]] un e bstätigti E-Mail-Adräss in Dyyne [[Special:Preferences|Yystellige]] aagee ha, fir dass epper anderem es E-Mail chasch schicke.',
'emailuser'       => 'Es Mail schrybe',
'emailpage'       => 'E-Mail an Benutzer',
'emailpagetext'   => 'Öpperem, wo sälber e bestätigeti e-Mail-Adrässe het aaggä, chasch du mit däm Formular es Mail schicke.
Im Absänder steit dyni eigeti e-Mail-Adrässe us dine [[Special:Preferences|Istellige]], so das me dir cha antworte.',
'usermailererror' => 'S Mail-Objekt het e Fähler zruckgee:',
'noemailtitle'    => 'Kei e-Mail-Adrässe',
'noemailtext'     => 'Dä Benutzer het kei bstätigti E-Mail-Adräss aagee oder wet kei E-Mails vo andere Benutzer.',
'emailfrom'       => 'Vo',
'emailto'         => 'Empfänger',
'emailsubject'    => 'Titel',
'emailmessage'    => 'E-Bost',
'emailsend'       => 'Abschicke',
'emailsent'       => 'E-Mail furtgschickt',
'emailsenttext'   => 'Dys E-Mail isch verschickt worde.',

# Watchlist
'watchlist'         => 'Beobachtigslischte',
'mywatchlist'       => 'Beobachtigslischte',
'watchlistfor'      => "(für '''$1''')",
'nowatchlist'       => 'Du hesch ke Yträg uf dyre Beobachtigslischte.',
'watchnologintext'  => 'Du muesch [[Special:UserLogin|aagmäldet]] syy, zum Dyyni Beobachtigssyte z bearbeite.',
'addedwatch'        => 'zue de Beobachtigslischte drzue do',
'addedwatchtext'    => 'D Syte "[[:$1]]" stoht jetz uf Ihre [[Special:Watchlist|Beobachtigslischte]].
Neui Änderige an de Syte odr de Diskussionssyte drvo chasch jetz dört seh. Usserdem sin selli Änderige uf de [[Special:RecentChanges|letschte Änderige]] fett gschriibe, dass Si s schneller finde.

Wenn Si d Syte spöter wiedr vo de Lischte striiche wenn, denn drucke Si eifach uf "nümm beobachte".',
'removedwatch'      => 'Us der Beobachtigsliste glösche',
'removedwatchtext'  => 'D Syte «[[:$1]]» isch us dyre [[Special:Watchlist|Beobachtigsliste]] glösche worde.',
'watch'             => 'beobachte',
'watchthispage'     => 'Die Syte beobachte',
'unwatch'           => 'nümm beobachte',
'watchnochange'     => 'Vo den Artikle, wo du beobachtisch, isch im aazeigte Zytruum kene veränderet worde.',
'watchlist-details' => '{{PLURAL:$1|1 Syte wird|$1 Sytene wärde}} beobachtet (Diskussionssyte nid zelt, aber ou beobachtet).',
'wlshowlast'        => 'Zeig di letschte $1 Stunde $2 Tage $3',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'Am beobachte …',
'unwatching' => 'Nümm am beobachten …',

'enotif_subject'     => 'D {{SITENAME}} Syte $PAGETITLE isch vum $PAGEEDITOR $CHANGEDORCREATED wore.',
'enotif_lastvisited' => '$1 zeigt alli Änderige uf s Mol.',
'enotif_body'        => 'Liebe/r $WATCHINGUSERNAME,

d {{SITENAME}} Syte $PAGETITLE isch vom $PAGEEDITOR am $PAGEEDITDATE $CHANGEDORCREATED,
di aktuelli Version isch: $PAGETITLE_URL

$NEWPAGE

Zämmenfassig vom Autor: $PAGESUMMARY $PAGEMINOREDIT
Kontakt zuem Autor:
Mail $PAGEEDITOR_EMAIL
Wiki $PAGEEDITOR_WIKI

Es wird kei wiiteri Benochrichtigungsposcht gschickt bis Si selli Syte wider bsueche. Uf de Beobachtigssyte chönne Si d Beobachtigsmarker zrucksetze.

             Ihr fründlichs {{SITENAME}} Benochrichtigssyschtem

---
Ihri Beobachtigslischte {{fullurl:Special:Watchlist/edit}}
Hilf zue de Benutzig gits uff {{fullurl:{{MediaWiki:Helppage}}}}',

# Delete
'deletepage'            => 'Syte lösche',
'confirm'               => 'Bstätige',
'excontentauthor'       => "einzige Inhalt: '$1' (bearbeitet worde nume dür '$2')",
'historywarning'        => '<span style="color:#ff0000">OBACHT:</span> Die Syte, wu Du wit lesche, het e Versionsgschicht:',
'confirmdeletetext'     => 'Du bisch dra, e Artikel oder e Bild mitsamt dr Versionsgschicht fir immer us der Datebank z lesche.
Bitte bi Dir iber d Konsequänze bewusst, un bi sicher, dass Du Di an unsri [[{{MediaWiki:Policy-url}}|Leitlinie]] haltsch.',
'actioncomplete'        => 'Uftrag usgfiert.',
'deletedtext'           => '«<nowiki>$1</nowiki>» isch glescht wore.
Im $2 het s e Lischt vu dr letschte Leschige.',
'deletedarticle'        => '„[[$1]]“ glescht',
'dellogpage'            => 'Lösch-Logbuech',
'deletionlog'           => 'Lösch-Logbuech',
'deletecomment'         => 'Löschigsgrund',
'deleteotherreason'     => 'Andere/zuesätzleche Grund:',
'deletereasonotherlist' => 'Andere Grund',

# Rollback
'rollback_short' => 'Zrucksetze',
'rollbacklink'   => 'Zrüggsetze',
'alreadyrolled'  => 'Cha d Änderig uf [[:$1]] wo [[User:$2|$2]] ([[User talk:$2|Talk]]) gmacht het nit zruckneh will des öbber anderscht scho gmacht het.

Di letschti Änderig het [[User:$3|$3]] ([[User talk:$3|Talk]]) gmacht.',
'revertpage'     => 'Ruckgängig gmacht zue dr letschte Änderig vo [[Special:Contributions/$2|$2]] ([[User talk:$2|Diskussion]]) mit de letzte Version vo [[User:$1|$1]] widerhergstellt', # Additionally available: $3: revid of the revision reverted to, $4: timestamp of the revision reverted to, $5: revid of the revision reverted from, $6: timestamp of the revision reverted from

# Protect
'protectlogpage'              => 'Syteschutz-Logbuech',
'protectlogtext'              => 'Des isch e Lischt vu dr blockierte Syte. Lueg [[Special:ProtectedPages|Gschitzti Syte]] fir meh Informatione.',
'prot_1movedto2'              => '[[$1]] isch uf [[$2]] verschobe worde.',
'protectcomment'              => 'Grund vu dr Sperrig',
'protectexpiry'               => 'Gsperrt bis:',
'protect_expiry_invalid'      => 'Di gwählti Duur isch nid gültig.',
'protect_expiry_old'          => 'Di gwählti Duur isch scho vergange.',
'protect-unchain'             => 'Verschiebschutz ändere',
'protect-text'                => 'Hie chasch der Schutzstatus vor Syte <strong><nowiki>$1</nowiki></strong> azeigen und ändere.',
'protect-locked-access'       => 'Dys Konto het nid di nötige Rächt, für der Schutzstatus z ändere.
Hie sy di aktuelle Schutzystellige vor Syte <strong>$1</strong>:',
'protect-cascadeon'           => 'Die Syten isch gschützt, wil si {{PLURAL:$1|zur folgende Syte|zu de folgende Syte}} ghört, wo derfür e Kaskadesperrig gilt.
Der Schutzstatus vo dere Syte lat sech la ändere, aber das het kei Yfluss uf d Kaskadesperrig.',
'protect-default'             => 'Alli (Standard)',
'protect-fallback'            => '«$1»-Berächtigung nötig',
'protect-level-autoconfirmed' => 'Nid regischtrierti Benutzer sperre',
'protect-level-sysop'         => 'Nur Adminischtratore',
'protect-summary-cascade'     => 'Kaskade',
'protect-expiring'            => 'bis $1 (UTC)',
'protect-cascade'             => 'Kaskadesperrig – alli yybundnige Vorlage sy mitgsperrt.',
'protect-cantedit'            => 'Du chasch der Schutzstatus vo dere Syte nid ändere, wil du kener Berächtigunge hesch, für se z bearbeite.',
'protect-expiry-options'      => '1 Stund:1 hour,2 Stunde:2 hours,6 Stunde:6 hours,1 Tag:1 day,3 Täg:3 days,1 Wuche:1 week,2 Wuche:2 weeks,1 Monet:1 month,3 Monet:3 months,1 Johr:1 year,Fir immer:infinite', # display1:time1,display2:time2,...
'restriction-type'            => 'Schutzstatus',
'restriction-level'           => 'Schutzhöchi:',

# Undelete
'undeletehistorynoadmin' => 'Dä Artikel isch glescht wore. Dr Grund fir d Leschig isch in dr Zämmefassig aagee, derzue au Aagaabe zum letschte Benutzer, wu dä Artikel bearbeitet het vor dr Leschig. Dr aktuäll Täxt vum gleschte Artikel isch nume zuegängli fir Ammanne.',
'undeletebtn'            => 'Widerhärstelle',
'undeletedarticle'       => 'hät d Site „[[$1]]“ widderhergstellt',
'undeletedrevisions'     => '{{PLURAL:$1|ei Revision|$1 Revisione}} wider zruckgholt.',

# Namespace form on various pages
'namespace'      => 'Namensruum:',
'invert'         => 'Uswahl umkehre',
'blanknamespace' => '(Haupt-)',

# Contributions
'contributions' => 'Benutzer-Byträg',
'mycontris'     => 'myyni Byyträg',
'contribsub2'   => 'Für $1 ($2)',
'uctop'         => '(aktuell)',
'month'         => 'u Monet:',
'year'          => 'bis Jahr:',

'sp-contributions-newbies'     => 'Zeig nume Biträg vo neie Benutzer',
'sp-contributions-newbies-sub' => 'vo nöji Benützer',
'sp-contributions-blocklog'    => 'Sperrlogbuech',
'sp-contributions-search'      => 'Suech no Benutzerbiträg',
'sp-contributions-username'    => 'IP-Adress oder Benutzername:',

# What links here
'whatlinkshere'       => 'Was linkt da ane?',
'whatlinkshere-title' => 'Sytene, wo uf „$1“ verlinke',
'linkshere'           => "Die Sytene hän e Gleich, wu zu '''„[[:$1]]“''' fiere:",
'nolinkshere'         => "Kei Artikel vergleicht uf '''„[[:$1]]“'''.",
'isredirect'          => 'Wyterleitigssyte',
'istemplate'          => 'Vorlageybindig',
'whatlinkshere-prev'  => '{{PLURAL:$1|vorder|vorderi $1}}',
'whatlinkshere-next'  => '{{PLURAL:$1|nächscht|nächschti $1}}',
'whatlinkshere-links' => '← Links',

# Block/unblock
'blockip'         => 'Benutzer bzw. IP blockyre',
'blockiptext'     => 'Bnutz des Formular, zum e Bnutzer oder e IP-Adress z\'blockiere.<sup class="plainlinks">[http://jodies.de/ipcalc?host=&mask1=&mask2= <span title="IP-Berych ermittle; bloss kurz sperre!">(B)</span>]</sup>

Des söt nummer erfolge um [[Wikipedia:Vandalismus|Vandalismus]] z\'verhindre in i Übereinstimmig mit üsre [[Wikipedia:Leitlinien|Leitlinie]] gschehe.
Bitte gib au de Grund für d\'Blockad aa.',
'ipbsubmit'       => 'Adräss blockiere',
'ipboptions'      => '1 Stund:1 hour,2 Stunde:2 hours,6 Stunde:6 hours,1 Tag:1 day,3 Täg:3 days,1 Wuche:1 week,2 Wuche:2 weeks,1 Monet:1 month,3 Monet:3 months,1 Johr:1 year,Fir immer:infinite', # display1:time1,display2:time2,...
'ipblocklist'     => 'Liste vo blockierten IP-Adrässen u Benutzernäme',
'blocklistline'   => '$1, $2 het $3 ($4) gsperrt',
'blocklink'       => 'sperre',
'unblocklink'     => 'freigä',
'contribslink'    => 'Byträg',
'blocklogpage'    => 'Sperrigs-Protokoll',
'blocklogentry'   => 'sperrt [[$1]] für d Ziit vo: $2 $3',
'blocklogtext'    => 'Des isch s Logbuech iber Sperrige un Entsperrige vu Benutzer. Automatisch blockierti IP-Adrässe wäre nit erfasst. Lueg au [[Special:IPBlockList|IP-Block Lischt]] fir e Lischt vu gsperrte Benutzer.',
'unblocklogentry' => 'Blockad vu $1 ufghobe',

# Move page
'move-page-legend' => 'Artikel verschiebe',
'movepagetext'     => 'Mit däm Formular chasch du en Artikel verschiebe, u zwar mit syre komplette Versionsgschicht. Der alt Titel leitet zum nöie wyter, aber Links ufen alt Titel blyben unveränderet.',
'movepagetalktext' => "D Diskussionssyte wird mitverschobe, '''ussert:'''
*Du verschiebsch d Syten i nen andere Namensruum, oder
*es git scho ne Diskussionssyte mit däm Namen oder
*du wählsch unte d Option, se nid z verschiebe.

I söttigne Fäll müessti d Diskussionssyten allefalls vo Hand kopiert wärde.",
'movearticle'      => 'Artikel verschiebe',
'movenologin'      => 'Du bisch nid aagmäldet',
'movenologintext'  => 'Du muesch dich zersch [[Special:UserLogin|aamälde]] damit du die Syte chasch verschiebe.',
'newtitle'         => 'Zum nöie Titel',
'move-watch'       => 'Die Syte beobachte',
'movepagebtn'      => 'Artikel verschiebe',
'pagemovedsub'     => 'Verschiebig erfolgrych',
'movepage-moved'   => "<big>'''«$1» isch verschobe worde nach «$2»'''</big>", # The two titles are passed in plain text as $3 and $4 to allow additional goodies in the message.
'articleexists'    => 'E Syte mit däm Name git s scho oder de Name isch nid giltig. Bitte nimm en andere.',
'talkexists'       => 'D Syte sälber isch erfolgrych verschobe worde, nid aber d Diskussionssyte, wil s under em nöue Titel scho eini het gä. Bitte setz se vo Hand zäme.',
'movedto'          => 'verschoben uf',
'movetalk'         => 'Diskussionssyte nach Müglechkeit mitverschiebe',
'1movedto2'        => '[[$1]] isch uf [[$2]] verschobe worde.',
'1movedto2_redir'  => '[[$1]] isch uf [[$2]] verschobe worre un het drbii e Wiiterleitig übrschriebe.',
'movelogpage'      => 'Verschiebigs-Logbuech',
'movereason'       => 'Grund',
'revertmove'       => 'Zrugg verschiebe',
'selfmove'         => 'Der nöi Artikelname mues en andere sy als der alt!',

# Export
'export'     => 'Sytenen exportiere',
'exporttext' => 'Du chasch dr Text un d Versionsgschicht vu einzelne Syte in ere XML-Datei exportiere. Die Datei cha derno in e ander MediaWiki-Wiki importiert wäre iber [[Special:Import|Importiere]].
Zum Exportiere trag dr Sytetitel in dr Täxtchaschte unter yy, ei Titel pro Zyyle, un wehl us, eb Du di aktuäll Version mitsamt dr eltere Versione (mit dr Versionsgschicht-Zyyle) oder nume di aktuäll Version mit dr Information iber di letscht Bearbeitig. In däm Fall chasch au e Gleich fir dr Export verwände, z. B. [[{{ns:special}}:Export/{{MediaWiki:Mainpage}}]] fir d Syte "[[{{MediaWiki:Mainpage}}]]".',

# Namespace 8 related
'allmessages'               => 'Systemnochrichte',
'allmessagesname'           => 'Name',
'allmessagesdefault'        => 'Standard-Tekscht',
'allmessagescurrent'        => 'jetzige Tekscht',
'allmessagestext'           => 'Sell isch e Lischte vo alle mögliche Systemnochrichte ussem MediaWiki Namensruum.
Please visit [http://www.mediawiki.org/wiki/Localisation MediaWiki Localisation] and [http://translatewiki.net Betawiki] if you wish to contribute to the generic MediaWiki localisation.',
'allmessagesnotsupportedDB' => "'''{{ns:special}}:Allmessages''' cha nit bruucht wärde will '''\$wgUseDatabaseMessages''' abgschalte isch.",
'allmessagesfilter'         => 'Nochrichte nochem Name filtere:',
'allmessagesmodified'       => 'numme gänderti aazeige',

# Thumbnails
'thumbnail-more'  => 'vergrösere',
'thumbnail_error' => 'Fähler bir Härstellig vo re Vorschou: $1',

# Special:Import
'importtext'            => 'Bitte speicher d’Syte vum Quellwiki met em Spezial:Export-Wärkzüg ab, un lad denn di XML-Datei do uffe. („Bild lokal“ sot im Folgénde eigentle „XML-Datei“ hoiße ;-)',
'import-revision-count' => '– {{PLURAL:$1|1 Vérsion|$1 Vérsiona}}',

# Import log
'importlogpage'                 => 'Import-Logbuech',
'import-logentry-upload-detail' => '$1 {{PLURAL:$1|Vérsion|Vérsiona}} [[Spezial:Importieren|importiert]]',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'Myni Benutzersyte',
'tooltip-pt-mytalk'               => 'Myni Diskussionssyte',
'tooltip-pt-preferences'          => 'Myni Ystellige',
'tooltip-pt-watchlist'            => 'Lischte vo de beobachtete Syte.',
'tooltip-pt-mycontris'            => 'Lischte vo myne Byträg',
'tooltip-pt-login'                => 'Aamälde',
'tooltip-pt-logout'               => 'Abmälde',
'tooltip-ca-talk'                 => 'Diskussion zum Artikelinhalt',
'tooltip-ca-edit'                 => 'Syte bearbeite. Bitte vor em Spychere d Vorschou aaluege.',
'tooltip-ca-addsection'           => 'E Kommentar zu dere Syte derzuetue.',
'tooltip-ca-viewsource'           => 'Die Syte isch geschützt. Du chasch der Quelltext aaluege.',
'tooltip-ca-history'              => 'Früecheri Versione vo dere Syte.',
'tooltip-ca-protect'              => 'Seite beschütze',
'tooltip-ca-delete'               => 'Syten entsorge',
'tooltip-ca-undelete'             => 'Sodeli, da isch es wider.',
'tooltip-ca-move'                 => 'Dür ds Verschiebe gits e nöie Name.',
'tooltip-ca-watch'                => 'Tue die Syten uf dyni Beobachtigslischte.',
'tooltip-ca-unwatch'              => 'Nim die Syte us dyre Beobachtungslischte furt.',
'tooltip-search'                  => 'Dürchsuech {{SITENAME}}',
'tooltip-p-logo'                  => 'Houptsyte',
'tooltip-n-mainpage'              => 'Gang uf d Houptsyte',
'tooltip-n-portal'                => 'Über ds Projekt, was du chasch mache, wo du was findsch',
'tooltip-n-currentevents'         => 'Hindergrundinformatione zu aktuellen Ereignis finde',
'tooltip-n-recentchanges'         => 'Lischte vo de letschten Änderige i däm Wiki.',
'tooltip-n-randompage'            => 'E zuefälligi Syte',
'tooltip-n-help'                  => 'Ds Ort zum Usefinde.',
'tooltip-t-whatlinkshere'         => 'Lischte vo allne Sytene, wo do ane linke',
'tooltip-t-recentchangeslinked'   => 'Letschti Änderige vo de Syte, wo vo do verlinkt sin',
'tooltip-feed-rss'                => 'RSS-Feed für selli Syte',
'tooltip-feed-atom'               => 'Atom-Feed für selli Syte',
'tooltip-t-contributions'         => 'Lischte vo de Byträg vo däm Benutzer',
'tooltip-t-emailuser'             => 'Schick däm Benutzer e E-Bost',
'tooltip-t-upload'                => 'Dateien ufelade',
'tooltip-t-specialpages'          => 'Lischte vo allne Spezialsyte',
'tooltip-ca-nstab-main'           => 'Artikelinhalt aaluege',
'tooltip-ca-nstab-user'           => 'Benutzersyte aaluege',
'tooltip-ca-nstab-media'          => 'Mediasyte aaluege',
'tooltip-ca-nstab-special'        => 'Sell isch e Spezialsyte, du chasch se nid bearbeite.',
'tooltip-ca-nstab-project'        => 'D Projektsyte aaluege',
'tooltip-ca-nstab-image'          => 'Die Bildsyten aaluege',
'tooltip-ca-nstab-mediawiki'      => 'D Systemmäldige aaluege',
'tooltip-ca-nstab-template'       => 'D Vorlag aaluege',
'tooltip-ca-nstab-help'           => 'D Hilfssyten aaluege',
'tooltip-ca-nstab-category'       => 'D Kategoryesyten aaluege',
'tooltip-minoredit'               => 'Die Änderig als chly markiere.',
'tooltip-save'                    => 'Änderige spychere',
'tooltip-preview'                 => 'Vorschou vo dynen Änderige. Bitte vor em Spycheren aluege!',
'tooltip-diff'                    => 'Zeigt a, was du am Tekscht hesch veränderet.',
'tooltip-compareselectedversions' => 'Underschide zwüsche zwo usgwählte Versione vo dere Syten azeige.',
'tooltip-watch'                   => 'Tue die Syten uf dyni Beobachtigslischte.',

# Attribution
'anonymous'        => '{{PLURAL:$1|Anonyme Benutzer|Anonymi Benutzer}} uff {{SITENAME}}',
'lastmodifiedatby' => 'Diese Seite wurde zuletzt geändert um $2, $1 von $3.', # $1 date, $2 time, $3 user
'othercontribs'    => 'Basiert auf der Arbeit von $1.',

# Spam protection
'spamprotectiontitle' => 'Spamschutz-Filter',

# Math options
'mw_math_png'    => 'Immer als PNG aazeige',
'mw_math_simple' => 'Eifachs TeX als HTML aazeige, süsch als PNG',
'mw_math_html'   => 'Falls müglech als HTML aazeige, süsch als PNG',
'mw_math_source' => 'Als TeX la sy (für Tekschtbrowser)',
'mw_math_modern' => 'Empfolnigi Ystellig für modärni Browser',

# Patrolling
'markaspatrolleddiff'   => 'Als patrulyrt markyre',
'markaspatrolledtext'   => 'Erschtversion patrulyre',
'markedaspatrolledtext' => 'D’Änderig isch als patrulyrt markyrt.',

# Patrol log
'patrol-log-line' => 'het d’$1 vo $2 als patrulyrt markyrt $3',

# Browsing diffs
'previousdiff' => '← Vorderi Änderig',
'nextdiff'     => 'Nächschti Änderig →',

# Media information
'mediawarning'         => "'''Obacht:''' In däre Art Datei chend s e beswillige Programmcode din ha. Wänn du die Datei abeladsch oder effnesch, cha dr Computer bschädigt wäre.<hr />",
'imagemaxsize'         => 'Maximali Gröössi vo de Bilder uf de Bildbeschrybigs-Sytene:',
'thumbsize'            => 'Bildvorschou-Gröössi:',
'file-info-size'       => '($1 × $2 Pixel, Dateigrößi: $3, MIME-Typ: $4)',
'file-nohires'         => '<small>Kei höcheri Uflösig verfüegbar.</small>',
'svg-long-desc'        => '(SVG-Datei, Basisgrößi: $1 × $2 Pixel, Dateigrößi: $3)',
'show-big-image'       => 'Originalgrößi',
'show-big-image-thumb' => '<small>Größi vo dere Vorschou: $1 × $2 Pixel</small>',

# Special:NewFiles
'newimages'     => 'Gallery vo noie Bilder',
'imagelisttext' => "Hie isch e Lischte vo '''$1''' {{PLURAL:$1|Datei|Dateie}}, sortiert $2.",
'ilsubmit'      => 'Suech',

# Bad image list
'bad_image_list' => 'Format:

Nume Zylene, wo mit emne * afö, wärde berücksichtigt.
Nach em * mues zersch e Link zuren Unerwünschte Datei cho.
Wyteri Links uf der glyche Zyle wärden als Usnahme behandlet, wo die Datei trotzdäm darff vorcho.',

# Metadata
'metadata'          => 'Metadate',
'metadata-help'     => "Die Datei het wyteri Informatione, allwäg vor Digitalkamera oder vom Scanner wo se het gschaffe.
We die Datei isch veränderet worde, de cha's sy, das die zuesätzlechi Information für di verändereti Datei nümm richtig zuetrifft.",
'metadata-expand'   => 'Erwytereti Details azeige',
'metadata-collapse' => 'Erwytereti Details verstecke',
'metadata-fields'   => 'Die EXIF-Metadate wärden ir Bildbeschrybig ou denn azeigt, we d Metadate-Tabälle versteckt isch.
Anderi Metadate sy standardmäßig versteckt.
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* focallength', # Do not translate list items

# EXIF tags
'exif-orientation'     => 'Orientierung',
'exif-pixelxdimension' => 'Valind image height',
'exif-fnumber'         => 'F-Wert',
'exif-isospeedratings' => 'Filmempfindlichkeit (ISO)',

# External editor support
'edit-externally'      => 'Die Datei mit emnen externe Programm bearbeite',
'edit-externally-help' => '(Lueg d [http://www.mediawiki.org/wiki/Manual:External_editors Installationsaawisige] fir witeri Informatione)',

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => 'alli',
'namespacesall' => 'alli',
'monthsall'     => 'alli',

# E-mail address confirmation
'confirmemail'          => 'Bstätigung vo Ihre E-Poscht-Adräss',
'confirmemail_text'     => 'Dermit du di erwyterete Mailfunktione chasch bruuche, muesch du die e-Mail-Adrässe, wo du hesch aaggä, la bestätige. Klick ufe Chnopf unte; das schickt dir es Mail. I däm Mail isch e Link; we du däm Link folgsch, de tuesch dadermit bestätige, das die e-Mail-Adrässe dyni isch.',
'confirmemail_send'     => 'Bestätigungs-Mail verschicke',
'confirmemail_sent'     => 'Es isch dir es Mail zur Adrässbestätigung gschickt worde.',
'confirmemail_success'  => 'Dyni E-Mail-Adräss isch bstätiget worde. Du chasch di jitz aamälde.',
'confirmemail_loggedin' => 'Dyni E-Mail-Adräss isch jitz bstätigt.',
'confirmemail_subject'  => '{{SITENAME}} E-Mail-Adrässbstätigung',
'confirmemail_body'     => "Hallo

{{SITENAME}}-BenutzerIn «$2» — das bisch allwäg du — het sech vor IP-Adrässen $1 uus mit deren e-Mail-Adrässe bi {{SITENAME}} aagmäldet.

Für z bestätige, das die Adrässe würklech dir isch, u für dyni erwytereten e-Mail-Funktionen uf {{SITENAME}} yzschalte, tue bitte der folgend Link i dym Browser uuf:

$3

Falls du *nid* $2 sötsch sy, de tue bitte de  Link unte dra uf um d'e-Mail-Bestätigung abzbreche:

$5

De Bestätigung Code isch gültug bis $4.

Fründtlechi Grüess",

# action=purge
'confirm-purge-top' => 'D Zwischespycherig vu dr Syte lesche?',

# Multipage image navigation
'imgmultipageprev' => '← vorderi Syte',

# Table pager
'table_pager_prev' => 'Vorderi Syte',

# Watchlist editing tools
'watchlisttools-view' => 'Beobachtigsliste: Änderige',
'watchlisttools-edit' => 'normal bearbeite',
'watchlisttools-raw'  => 'imene große Textfäld bearbeite',

# Special:Version
'version' => 'Version', # Not used as normal message but as header for the special page itself

# Special:SpecialPages
'specialpages'             => 'Spezialsytene',
'specialpages-group-login' => 'Aamälde',

);
