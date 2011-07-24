<?php
/** Bavarian (Boarisch)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Als-Holder
 * @author Malafaya
 * @author Man77
 * @author Merlissimo
 * @author Metalhead64
 * @author Mucalexx
 * @author The Evil IP address
 * @author Wikifan
 * @author bar.wikipedia.org administrators
 * @author ✓
 */

$fallback = 'de';

$messages = array(
# User preference toggles
'tog-underline'               => 'Links unterstreichen:',
'tog-highlightbroken'         => 'Links auf néd vurhåndane Seiten hervurhém: <a href="" class="new">Beispü</a> (sunst wia der dodan: <a href="" class="internal">?</a>)',
'tog-justify'                 => 'Text ois Blócksootz',
'tog-hideminor'               => 'Kloane Änderrungen ausblenden',
'tog-hidepatrolled'           => 'Kontroilirde Änderrungen in dé „Létzten Änderrungen“ ausblenden',
'tog-newpageshidepatrolled'   => 'Kóntróilirde Seiten auf da Listen „Neiche Seiten“ vaberng',
'tog-extendwatchlist'         => 'Daweiterde Beówochtungslisten',
'tog-usenewrc'                => "Daweiterde Dorstöung vo d' létzten Änderrungen (JavaScript werd braucht)",
'tog-numberheadings'          => 'Ywerschriften autómaatisch nummerrirn',
'tog-showtoolbar'             => 'Beorweiten-Werkzeigleisten åzoang (JavaScript werd braucht)',
'tog-editondblclick'          => 'Seiten mid am Dóppedrucker beorweiten (JavaScript werd braucht)',
'tog-editsection'             => "Links zum beorweiten voh d' oahzelnen Obschnitt åzoang",
'tog-editsectiononrightclick' => 'Oahzelne Obschnitt mid am Rechtsdrucker beorweiten (JavaScript werd braucht)',
'tog-showtoc'                 => 'Åzoang vom Inhoidsvazeichnis bei Seiten mid merer ois drei Ywerschriften',
'tog-rememberpassword'        => 'Mim Browser dauerhoft ågmödt bleim (maximaal $1 {{PLURAL:$1|Toog|Toog}})',
'tog-watchcreations'          => 'Voh mir söwer eihgstöde Seiten autómaatisch beówochten',
'tog-watchdefault'            => 'Voh mir söwer gänderde Seiten autómaatisch beówochten',
'tog-watchmoves'              => 'Voh mir söwer vaschówerne Seiten autómaatisch beówochten',
'tog-watchdeletion'           => 'Voh mir söwer gléschde Seiten autómaatisch beówochten',
'tog-minordefault'            => "D' eigernen Änderrungen standardmässig gringfiagig markirn",
'tog-previewontop'            => 'Vurschau ówerhoib vom Beorweitungsfenster åzoang',
'tog-previewonfirst'          => "Ban ersten Beorweiten oiwei d' Vurschau åzoang",
'tog-nocache'                 => 'Seitencache vom Browser deaktivirn',
'tog-enotifwatchlistpages'    => 'Ba Änderrungen voh beówochterde Seiten a E-Mail schicker',
'tog-enotifusertalkpages'     => 'Ba Änderrungen voh meiner Benutzerseiten a E-Mail schicker',
'tog-enotifminoredits'        => 'Aa ba kloane Änderrungen voh beówochterde Seiten a E-Mail schicker',
'tog-enotifrevealaddr'        => 'Deih E-Mail-Adress in Benoochrichtigungs-E-Mails åzoang',
'tog-shownumberswatching'     => "D' Åzoi voh dé beówochterden Benutzer åzoang",
'tog-oldsig'                  => 'Vurschau voh da aktuön Unterschrift:',
'tog-fancysig'                => 'Unterschrift ois Wikitext bhåndln (óne autómaatische Valinkung)',
'tog-externaleditor'          => "An externen Editor ois Standard bnutzen (netter fyr Experten, braucht spezielle Eihstellungen auf'm eigernen Computer)",
'tog-externaldiff'            => "A externs Programm fyr Versionsunterschiad ois Standard bnutzen (netter fyr Experten, dafordert spezielle Eihstellungen auf'm eiganen Computer)",
'tog-showjumplinks'           => '„Wexeln zu“-Links aktivirn',
'tog-uselivepreview'          => 'Live-Vurschau nutzen (dodafyr braucht ma JavaScript) (experimentoy)',
'tog-forceeditsummary'        => "Warner, wånn ban Speichern d'Zåmmerfossung foyd",
'tog-watchlisthideown'        => 'Oagane Beorweitunger in da Bówochtungslisten ausblenden',
'tog-watchlisthidebots'       => 'Beorweitungen durch Bots in da Bówochtungslisten ausblenden',
'tog-watchlisthideminor'      => 'Kloane Beorweitunger in da Bówochtungslisten ausblenden',
'tog-watchlisthideliu'        => 'Beorweitunger vo ågmödte Bnutzer in da Beówochtungslisten ausblenden',
'tog-watchlisthideanons'      => 'Ned ogmoidte Nutzer in da Beówochtungslisten ausblenden',
'tog-watchlisthidepatrolled'  => 'Kontrollirde Änderrungen in da Beowochtungslisten ausblenden',
'tog-ccmeonemails'            => 'Schick ma Kopien voh dé E-Mails, dé i åndre Benutzer send',
'tog-diffonly'                => "Zoag beim Versiónsvagleich netter dé Unterschiad und néd d' voiständige Seiten",
'tog-showhiddencats'          => 'Vasteckte Kategorien åzoang',
'tog-norollbackdiff'          => "Unterschiad noch'm Zrucksetzen unterdrucker",

'underline-always'  => 'oiwei',
'underline-never'   => 'nia',
'underline-default' => 'obhängig voh da Browsereistöung',

# Font style option in Special:Preferences
'editfont-style'     => 'Schriftoart fyrn Text im Beorweitungsfenster',
'editfont-default'   => 'Browserstandard',
'editfont-monospace' => 'Schrift mid ner festen Zeichenbreaden',
'editfont-sansserif' => 'Serifenlose Groteskschrift',
'editfont-serif'     => 'Schrift mid Serifen',

# Dates
'sunday'        => 'Sunndog',
'monday'        => 'Mondog',
'tuesday'       => 'Deansdog',
'wednesday'     => 'Midwoch',
'thursday'      => 'Dunnersdog',
'friday'        => 'Freidog',
'saturday'      => 'Såmsdog',
'sun'           => 'Su',
'mon'           => 'Mo',
'tue'           => 'De',
'wed'           => 'Mi',
'thu'           => 'Du',
'fri'           => 'Fr',
'sat'           => 'Så',
'january'       => 'Jänner',
'february'      => 'Feewer',
'march'         => 'März',
'april'         => 'Aprü',
'may_long'      => 'Mai',
'june'          => 'Juni',
'july'          => 'Juli',
'august'        => 'August',
'september'     => 'September',
'october'       => 'Oktower',
'november'      => 'November',
'december'      => 'Dezember',
'january-gen'   => 'Jänner',
'february-gen'  => 'Feewer',
'march-gen'     => 'März',
'april-gen'     => 'Aprü',
'may-gen'       => 'Mai',
'june-gen'      => 'Juni',
'july-gen'      => 'Juli',
'august-gen'    => 'August',
'september-gen' => 'September',
'october-gen'   => 'Oktower',
'november-gen'  => 'November',
'december-gen'  => 'Dezember',
'jan'           => 'Jän.',
'feb'           => 'Few.',
'mar'           => 'Mär.',
'apr'           => 'Apr.',
'may'           => 'Mai',
'jun'           => 'Jun.',
'jul'           => 'Jul.',
'aug'           => 'Aug.',
'sep'           => 'Sep.',
'oct'           => 'Okt.',
'nov'           => 'Nov.',
'dec'           => 'Dez.',

# Categories related messages
'pagecategories'                 => '{{PLURAL:$1|Kategorie|Kategorien}}',
'category_header'                => 'Seiten in da Kategorie „$1“',
'subcategories'                  => 'Unterkategorien',
'category-media-header'          => 'Medien in da Kategorie „$1“',
'category-empty'                 => "''De Kategorie enthoit im Moment koane Seiten und koane Medien ned.''",
'hidden-categories'              => '{{PLURAL:$1|Vasteckte Kategorie|Vasteckte Kategorien}}',
'hidden-category-category'       => 'Vasteckte Kategorie',
'category-subcat-count'          => '{{PLURAL:$2|De Kategorie enthoit netter de foigande Unterkategorie:|{{PLURAL:$1|De foigande Unterkategorie is oane voh insgsåmt $2 Unterkategorien in derer Kategorie:|Voh insgsåmt $2 Unterkategorien in derer Kategorie wern $1 åzoagt:}}}}',
'category-subcat-count-limited'  => 'In de Kategorie {{PLURAL:$1|is de foigande Unterkategorie|san de foiganden Unterkategorien}} eihsortird:',
'category-article-count'         => '{{PLURAL:$2|De Kategorie enthoit foigande Seiten:|{{PLURAL:$1|Foigande Seiten is aane vo insgsåmt $2 Seiten in derer Kategorie:|Es wern $1 voh insgsåmt $2 Seiten in derer Kategorie ågzaagt:}}}}',
'category-article-count-limited' => 'De {{PLURAL:$1|foigande Seiten is|foiganden $1 Seiten san}} in derer Kategorie enthoiden:',
'category-file-count'            => '{{PLURAL:$2|De Kategorie enthoit foigande Datei:|{{PLURAL:$1|Foigande Datei is aahne voh insgsåmmt $2 Datein in derer Kategorie:|Es wern $1 voh insgsåmt $2 Datein in derer Kategorie åzaagt:}}}}',
'category-file-count-limited'    => "{{PLURAL:$1|D' foingde Datei is|De foingden $1 Datein san}} in de Kategorie eisortird:",
'listingcontinuesabbrev'         => '(Furtsétzung)',
'index-category'                 => 'Indizirde Seiten',
'noindex-category'               => 'Ned-indizirde Seiten',
'broken-file-category'           => 'Seiten mid kaputte Daateilinks',

'about'         => 'Ywer',
'article'       => 'Artike',
'newwindow'     => '(werd in am neichen Fenster aufgmocht)',
'cancel'        => 'Obbrecher',
'moredotdotdot' => 'Merer',
'mypage'        => 'Eigerne Seiten',
'mytalk'        => 'Eigerne Diskussion',
'anontalk'      => 'Dischkrirseiten voh derer IP-Adress',
'navigation'    => 'Navigazion',
'and'           => '&#32;und',

# Cologne Blue skin
'qbfind'         => 'Finden',
'qbbrowse'       => 'Blaadeln',
'qbedit'         => 'werkeln',
'qbpageoptions'  => 'Seitenopzionen',
'qbpageinfo'     => 'Seitendaaten',
'qbmyoptions'    => 'Meine Seiten',
'qbspecialpages' => 'Speziaalseiten',
'faq'            => 'Heiffige Frong',
'faqpage'        => 'Project:FAQ',

# Vector skin
'vector-action-addsection'       => 'Obschnit dazuafyng',
'vector-action-delete'           => 'Leschen',
'vector-action-move'             => 'Vaschiam',
'vector-action-protect'          => 'Schytzen',
'vector-action-undelete'         => 'Wiederherstön',
'vector-action-unprotect'        => 'freigeem',
'vector-simplesearch-preference' => 'Daweiterte Suachvurschläg aktivirn (netter Vector)',
'vector-view-create'             => 'Erstön',
'vector-view-edit'               => 'Werkeln',
'vector-view-history'            => 'Versionsgschicht',
'vector-view-view'               => 'Leesen',
'vector-view-viewsource'         => 'Quötext åzong',
'actions'                        => 'Akzionen',
'namespaces'                     => 'Nåmensreim',
'variants'                       => 'Varianten',

'errorpagetitle'    => 'Feeler',
'returnto'          => 'Zruck zur Seiten $1.',
'tagline'           => 'Aus {{SITENAME}}',
'help'              => 'Hüf & Frong?',
'search'            => 'Suach',
'searchbutton'      => 'Suachen',
'go'                => 'Ausfyrn',
'searcharticle'     => 'Artike',
'history'           => 'Versionen',
'history_short'     => 'Versionen/Autorn',
'updatedmarker'     => '(gänderd)',
'printableversion'  => 'Version zum Ausdrucker',
'permalink'         => 'Permanenter Link',
'print'             => 'Drucken',
'view'              => 'Leesen',
'edit'              => 'beorweiten',
'create'            => 'Erstön',
'editthispage'      => 'Seiten beorweiten',
'create-this-page'  => 'Seiten erstön',
'delete'            => 'leschen',
'deletethispage'    => 'De Seiten leschen',
'undelete_short'    => '{{PLURAL:$1|1 Version|$1 Versionen}} wiederherstön',
'viewdeleted_short' => '{{PLURAL:$1|Oah geléschde Versión|$1 geléschde Versiónen}} åschauh',
'protect'           => 'Schytzen',
'protect_change'    => 'endern',
'protectthispage'   => 'Seiten schytzen',
'unprotect'         => 'freigeem',
'unprotectthispage' => 'Seitenschutz ändern',
'newpage'           => 'Neiche Seiten',
'talkpage'          => 'De Seiten bsprecher',
'talkpagelinktext'  => 'Diskussion',
'specialpage'       => 'Speziaalseiten',
'personaltools'     => 'Perseenlichs Werkzeig',
'postcomment'       => 'Neicher Obschnit',
'articlepage'       => 'Seiteninhoid åzoang',
'talk'              => 'Diskussion',
'views'             => 'Åsichten',
'toolbox'           => 'Werkzeigkisten',
'userpage'          => 'Benutzerseiten',
'projectpage'       => 'Projektseiten åzoang',
'imagepage'         => 'Daateiseiten åzoang',
'mediawikipage'     => 'Inhoitsseiten åzoang',
'templatepage'      => 'Vurlongseiten åzoang',
'viewhelppage'      => 'Hüfeseiten åzoang',
'categorypage'      => 'Kategorieseiten åzoang',
'viewtalkpage'      => 'Diskussion',
'otherlanguages'    => 'Ånderne Sproochen',
'redirectedfrom'    => '(vo $1 waider gschickt)',
'redirectpagesub'   => 'Weiderloatung',
'lastmodifiedat'    => 'De Seiten is zletzt am $1 um $2 gänderd worn.',
'jumpto'            => 'Wexeln zua:',
'jumptonavigation'  => 'Navigazion',
'jumptosearch'      => 'Suach',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'Ywer {{SITENAME}}',
'aboutpage'            => 'Project:Ywer_{{SITENAME}}',
'copyright'            => 'Da Inhoidis unter da $1 vafiagbor.',
'copyrightpage'        => '{{ns:project}}:Urheewerrechte',
'currentevents'        => 'Aktuelle Ereigniss',
'currentevents-url'    => 'Project:Aktuelle Ereigniss',
'disclaimers'          => 'Herkumftsågob/Impressum',
'disclaimerpage'       => 'Project:Impressum',
'edithelp'             => 'Beorweitungshüfm',
'edithelppage'         => 'Help:Beorweitungshüfm',
'helppage'             => 'Help:Inhoidsvazeichnis',
'mainpage'             => 'Hauptseiten',
'mainpage-description' => 'Hauptseiten',
'policy-url'           => 'Project:Richtlinien',
'portal'               => 'Gmoahschoftsportoi',
'portal-url'           => 'Project:Gmoahschoftsportoi',
'privacy'              => 'Daatenschutz',
'privacypage'          => 'Project:Daatenschutz',

'badaccess' => 'Koane ausreichenden Rechtt',

'ok'                      => 'Passt',
'retrievedfrom'           => 'Voh „$1“',
'youhavenewmessages'      => 'Du host $1 ($2).',
'newmessageslink'         => 'neiche Noochrichten',
'newmessagesdifflink'     => 'neiche Noochrichten',
'youhavenewmessagesmulti' => 'Du host neiche Noochrichten: $1',
'editsection'             => 'werkeln',
'editold'                 => 'werkeln',
'viewsourceold'           => 'Quötext åzoang',
'editlink'                => 'werkeln',
'viewsourcelink'          => 'an Quötext åschauh',
'editsectionhint'         => 'Obschnit beorweiten: $1',
'toc'                     => 'Inhoidsvazeichnis',
'showtoc'                 => 'Åzoang',
'hidetoc'                 => 'vastecka',
'thisisdeleted'           => '$1 åschaun oder wiiderherstöin?',
'viewdeleted'             => '$1 åzoang?',
'restorelink'             => '$1 gleschte {{PLURAL:$1|Version|Versionen}}',
'feedlinks'               => 'Feed:',
'feed-invalid'            => 'Ungüitiger Feed-Abonnement-Typ.',
'site-rss-feed'           => 'RSS-Feed fyr $1',
'site-atom-feed'          => 'Atom-Feed fyr $1',
'page-rss-feed'           => 'RSS-Feed fyr „$1“',
'page-atom-feed'          => 'Atom-Feed fyr „$1“',
'red-link-title'          => '$1 (de Seiten gibts ned)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Seiten',
'nstab-user'      => 'Benutzerseiten',
'nstab-media'     => 'Medienseiten',
'nstab-special'   => 'Speziaalseiten',
'nstab-project'   => 'Projektseiten',
'nstab-image'     => 'Daatei',
'nstab-mediawiki' => 'MediaWiki-Systeemnoochricht',
'nstab-template'  => 'Vurlog',
'nstab-help'      => 'Hüfeseiten',
'nstab-category'  => 'Kategorie',

# Main script and global functions
'nosuchaction'      => 'De Akzion gibts ned',
'nosuchspecialpage' => 'De Speziaalsaiten gibts ned',

# General errors
'error'                => 'Feeler',
'databaseerror'        => 'Feeler in da Daatenbånk',
'dberrortextcl'        => "Tschuidigung! Es hod an Syntaxfeeler in da Daatenbånkobfrog geem.
D' letzte Daatenbånkobfrog hod  „$1“ aus da Funkzion „<tt>$2</tt>“ glautt.
De Daatenbånk möidt 'n Feeler: „<tt>$3: $4</tt>“.",
'laggedslavemode'      => "'''Ochtung:''' De åzoagte Saiten kunntad unter Umständ ned d' letzten Beorwaitungen enthoiden.",
'readonly'             => 'Daatenbånk gsperd',
'enterlockreason'      => 'Bittschee gib an Grund å, warum de Daatenbånk gsperrd wern soi und a Obschätzung ywer de Dauer vo da Sperrung',
'readonlytext'         => 'De Daatenbånk is vorywergeehend fyr Neieihtreeg und Änderrungen gsperrd. Bittschee vasuchs spaader nuamoi.

Grund voh da Sperrung: $1',
'missing-article'      => 'Der Text vo „$1“ $2 is ned in da Daatenbank gfunden worn.

De Saiten is meglicherwais glescht oder vaschoom worn.

Fois des ned zuatrifft, host eventuöi an Feeler in da Software gfunden. Bittschee möid des am [[Special:ListUsers/sysop|Åmtmåå (Administrator)]] unter da Nennung vo da URL.',
'missingarticle-rev'   => '(Versionsnummer: $1)',
'missingarticle-diff'  => '(Unterschiad zwischen Versionen: $1, $2)',
'readonly_lag'         => "De Daatenbånk is automaatisch fyr Schraibzuagriff gsperrt, damid se d' vatailten Daatenbånkserver (slaves) mim Hauptdaatenbånkserver (master) obglaichen kennan.",
'internalerror'        => 'Interner Feeler',
'internalerror_info'   => 'Interner Feeler: $1',
'fileappenderrorread'  => '"$1" hod wärend \'m dazuafyng ned gleesen wern kenna.',
'fileappenderror'      => 'De Datai „$1“ hod ned noch „$2“ kopird wern kenna.',
'filecopyerror'        => 'De Datai „$1“ hod ned noch „$2“ kopird wern kenna.',
'filerenameerror'      => 'De Datai „$1“ hod ned noch „$2“ umbenånnt wern kenna.',
'filedeleteerror'      => 'De Datai „$1“ hod ned glescht wern kenna.',
'directorycreateerror' => 'As Vazaichnis „$1“ hod ned ågleegt wern kenna.',
'filenotfound'         => 'De Datai „$1“ is ned gfunden worn.',
'fileexistserror'      => "In d' Datai „$1“ hod ned gschriim wern kenna, wails de Datai nämle schå gibt.",
'unexpected'           => 'Unerworteter Wert: „$1“=„$2“.',
'formerror'            => 'Feeler: De Aigoom håm ned vaorwaitt wern kenna.',
'badarticleerror'      => 'De Akzion kå ned auf da Saiten ågwendt wern.',
'badtitle'             => 'Ungüitiger Titl',
'badtitletext'         => 'Da Titl vo derer ågforderten Saiten is ned güitig, laar oder a ungüitiger Sproochlink voram åndern Wiki.',
'wrong_wfQuery_params' => 'Foische Parameter fyr wfQuery()<br />
Funkzion: $1<br />
Obfrog: $2',
'viewsource'           => 'Quöitext åschaun',
'viewsourcefor'        => 'fyr $1',
'actionthrottled'      => 'Akzionszoi limitird',
'actionthrottledtext'  => 'Im Råmen vo ner Anti-Spam-Moossnåm kå de Akzion do in am kurzen Zaidobstånd netter begrenzt ausgfyrd wern. De Grenzen host ywerschritten.
Bittschee vasuachs in a por Minunten nuamoi.',
'protectedpagetext'    => "De Saiten is fyr Beorwaitungen gsperrt worn, um Beorwaitungen z' vahindern.",
'viewsourcetext'       => 'Du kååst ower an Quöitext vo derer Saiten åschaun und kopirn.',
'editinginterface'     => "'''Owocht:''' De Saiten do enthoit vo da MediaWiki-Software an gnytzen Text. 
Enderungen auf derer Saiten wirken se auf de Benytzerowerflächen aus.
Ziag bittschee im Foi vo Ywersetzungen in Betrocht, de bai [http://translatewiki.net/wiki/Main_Page?setlang=de translatewiki.net], da Lokaalisiarungsblottform fyr MediaWiki, durchzfyrn.",
'titleprotected'       => "A Saiten mid dem Nåmen kå ned ågleegt wern. De Sperrn is durch [[User:$1|$1]] mid da Begryndung ''„$2“'' aigerichtt worn.",

# Login and logout pages
'logouttext'                 => "'''Du bist jetzad abgmöidt.'''

Du kååst {{SITENAME}} jetzad anonym waidernytzn, oder di ernait unterm söim oder am åndern Benytzernåm [[Special:UserLogin|åmöidn]].
Beochtt ower, dass oanige Saitn no åzoang kennan, dass du ågmöidt bist, solång du ned dai Browsercache glaart host.",
'welcomecreation'            => '== Servas, $1! ==

Dei Benutzerkonto is grood eigrichtt worn.
Vagiss bittschee ned, deine [[Special:Preferences|{{SITENAME}}-Eishtellungen]] åzpassen',
'yourname'                   => 'Benützernåm:',
'yourpassword'               => 'Posswort:',
'yourpasswordagain'          => 'Posswort no amoi',
'remembermypassword'         => 'Mid dem Browser dauerhoft ågmöidt blaim (maximaal $1 {{PLURAL:$1|Dog|Dog}})',
'yourdomainname'             => 'Eanerne Domain:',
'externaldberror'            => 'Entweder es ligt a Feeler bai da externen Authentifiziarung vur oder du derfst dai externs Benytzerkonto ned aktualisirn.',
'login'                      => 'Åmöden',
'nav-login-createaccount'    => 'Åmöden / Konto erstön',
'loginprompt'                => 'Zur Åmöidung miassen Cookies aktiviard sai.',
'userlogin'                  => 'Åmöden / Konto erstön',
'userloginnocreate'          => 'Åmöden',
'logout'                     => 'Obmöden',
'userlogout'                 => 'Obmöden',
'notloggedin'                => 'Ned ågmödt',
'nologin'                    => "Du host koa Benutzerkonto? '''$1'''.",
'nologinlink'                => 'A neichs Benutzerkonto erstön',
'createaccount'              => 'Benutzerkonto åleeng',
'gotaccount'                 => "Du host scho a Benutzerkonto? '''$1'''.",
'gotaccountlink'             => 'Åmöden',
'createaccountmail'          => 'per E-Mail',
'createaccountreason'        => 'Grund',
'badretype'                  => 'De zwoa Posswerter stimmer ned ywereih.',
'userexists'                 => 'Da Benytzernåm is scho vageem. Bittschee nimm an åndern her.',
'loginerror'                 => 'Feeler bai da Åmöidung',
'createaccounterror'         => 'Des Benytzerkonto hod ned erstöid wern kenna: $1',
'nocookiesnew'               => "Da Benytzerzuagång is erstöid worn, du bist ower ned ågmöidt. {{SITENAME}} benedigt fyr de Funkzion Cookies, bittschee aktiviar de und möidt de danoch mid daim naichn Benytzernåm und 'm dazuaghering Posswort å.",
'nocookieslogin'             => '{{SITENAME}} nimmt Cookies zum Ailoggen vo de Benytzer her. Du host Cookies deaktivird, bittschee aktivir de und vasuchs nuamoi.',
'loginsuccesstitle'          => "D' Åmöidung is erfoigraich gween",
'loginsuccess'               => 'Du bist jetzad ois „$1“ bai {{SITENAME}} ågmöidt.',
'wrongpassword'              => 'Des Posswort is foisch! Bitschee prowirs nuamoi.',
'wrongpasswordempty'         => 'Es is koa Posswort ned aigeem worn. Bittschee prowirs nuamoi.',
'mailmypassword'             => 'Naichs Posswort zuaschicka',
'passwordremindertitle'      => 'Naichs Posswort fyra {{SITENAME}}-Benytzerkonto',
'acct_creation_throttle_hit' => 'Du host scho $1 {{PLURAL:$1|Benytzerkonto|Benytzerkonten}} und kååst jetzad koane mer åleeng.',
'accountcreated'             => 'Benytzerkonto is erstöid worn',
'accountcreatedtext'         => "'s Benytzerkonto $1 is aigrichtt worn.",
'loginlanguagelabel'         => 'Sprooch: $1',

# Change password dialog
'oldpassword' => 'Oids Posswort:',
'newpassword' => 'Naichs Posswort:',
'retypenew'   => 'Naichs Posswort (nuamoi):',

# Special:PasswordReset
'passwordreset'          => 'Passwoat zrucksetzn',
'passwordreset-username' => 'Benutzernåm:',

# Edit page toolbar
'bold_sample'     => 'Fetter Text',
'bold_tip'        => 'Fetter Text',
'italic_sample'   => 'Kursiaver Text',
'italic_tip'      => 'Kursiaver Text',
'link_sample'     => 'Link-Text',
'link_tip'        => 'Interner Link',
'extlink_sample'  => 'http://www.example.com Link-Text',
'extlink_tip'     => 'Externer Link (http:// beochten)',
'headline_sample' => 'Ywerschrift auf da zwoaten Ewane',
'headline_tip'    => 'Ewane-2-Ywerschrift',
'nowiki_sample'   => 'Den ned-formatirden Text dodan aifyng',
'nowiki_tip'      => 'Ned-formatirder Text',
'image_tip'       => 'Datailink',
'media_tip'       => 'Medientatailink',
'sig_tip'         => 'Dai Unterschrift mid Zaidstempe',
'hr_tip'          => 'Woogrechte Linie (sporsåm vawenden)',

# Edit pages
'summary'                          => 'Zåmmfossung:',
'subject'                          => 'Bedreff',
'minoredit'                        => 'Netter Kloanigkeiten san vaänderd worn',
'watchthis'                        => "D' Seiten beówochten",
'savearticle'                      => 'Seiten speichern',
'preview'                          => 'Vurschau',
'showpreview'                      => 'Vurschau zoang',
'showdiff'                         => 'Änderrungen zoang',
'anoneditwarning'                  => "Du beorwaitst de Saiten ned-ågmöidt. Wånn du de spaichertst, werd dai aktuelle IP-Adress in da Versionsgschicht aufzaichnet und is dodamid unwiaderruaflich '''effmtlich''' zum åschaun.",
'missingsummary'                   => "'''Hiwais:''' du host koa Zåmmfossung ågeem. Wånn du ernait auf „{{int:savearticle}}“ druckst, werd dai Enderung one a Zåmmfossung ywernumma.",
'missingcommenttext'               => 'Bittschee gib a Zåmmfossung ai.',
'summary-preview'                  => 'Vurschau vo da Zåmmfossung:',
'subject-preview'                  => 'Vurschau vom Bedreff:',
'blockedtitle'                     => 'Da Benytzer is gsperrt',
'whitelistedittitle'               => 'Zum Beorwaiten muasst de åmöiden',
'whitelistedittext'                => "Du muasst de $1, um Saiten beorwaiten z' kenna.",
'loginreqtitle'                    => "'s braucht a Åmöidung",
'loginreqlink'                     => 'åmöiden',
'loginreqpagetext'                 => "Du muasst de $1, dass d' Saiten leesen kååst.",
'accmailtitle'                     => 'Passwort is vaschickt worn',
'accmailtext'                      => 'E zuafällig genariards Posswort fyr [[User talk:$1|$1]] is an $2 gschickt worn.

Des Posswort fyr des naiche Benutzerkonto kå auf da Speziaalseiten  „[[Special:ChangePassword|Posswort ändern]]“ gändert wern.',
'newarticle'                       => '(Naich)',
'newarticletext'                   => "Du bist am Link zua ner Saiten gfóigt, dé néd vurhånden is.
Das d' dé Saiten åléng kååst, trog dain Text a dé untensteehate Boxen ai (schau unter da [[{{MediaWiki:Helppage}}|Hüifssaiten]] fyr merer Informaziónen).
Bist du föischlicherwais dodan, dånn druck dé '''Zruck'''-Schoitflächen vo daim Browser.",
'anontalkpagetext'                 => "---- ''De Seiten werd dodazua hergnumma, am ned-ågmöiderten Benutzer Nochrichten z' hinterlossen.
Wånnst mid de Kommentare auf derer Seiten nix åfanga kåst, is vamuatlich da friarerne Inhower vo derer IP-Adress gmoat und du kåstas ignorirn.
Wånnst a anonymer Benutzer bist und denkst, das irrelevante Kommentare ån di grichtt worn san, [[Special:UserLogin|möid de bittschee å]], um zuakynfteg Vawirrung z' vamein.''",
'noarticletext'                    => 'De Saiten enthoit zua Zaid koan Text ned.
Du kååst an Titl vo derer Saiten auf de åndern Saiten [[Special:Search/{{PAGENAME}}|suacha]],
<span class="plainlinks"> in de dazuagheraden [{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} Logbiache suacha] oder de Saiten [{{fullurl:{{FULLPAGENAME}}|action=edit}} beorwaiten]</span>.',
'userpage-userdoesnotexist'        => 'Des Benutzerkonto „<nowiki>$1</nowiki>“ is ned vurhånden. Bittschee priaf, ob du de Seiten wirkle erstöin/beorweiten wüist.',
'userpage-userdoesnotexist-view'   => 'Benutzerkonto „$1“ existiard ned.',
'blocked-notice-logextract'        => "{{GENDER:$1|Der Benutzer|De Benutzarin|Der Benutzer do}} is zurzeid gesperrd.
Zua da Informazion foigt a aktueller Auszug aus 'm Benutzersperr-Logbiache:",
'updated'                          => '(Gendert)',
'previewnote'                      => "'''Des is netter a Vurschau, d' Saiten is nu ned gspaichert worn!'''",
'previewconflict'                  => "De Vurschau gibt an Inhoit vom owern Textföidl wiader. So werd d' Saiten ausschaun, wånn du jetzad spaichertst.",
'session_fail_preview'             => "'''Daine Beorwaitungen håm ned gspaichert wern kenna, wail Sitzungsdaaten valurn gånga san.'''
Bittschee vasuachs nuamoi, indem du unter da foiganden Textvurschau noamoi auf „Saiten spaichern“ druckst.
Soidad des Probleem bestee blaim, [[Special:UserLogout|möid de ob]] und danoch wider å.",
'editing'                          => 'Beorwaiten vo $1',
'editingsection'                   => 'Werkln bai $1 (Obschnit)',
'editconflict'                     => 'Konflikt baim Beorwaiten: $1',
'explainconflict'                  => "Eppern ånderer hod de Saitn gendert, nochdem du ågfånga host de zum beorwaiten.
Des owere Textföidl enthoit 'n aktuöin Stånd.
Des untare Textföidl enthoit daine Enderungen.
Bittschee fiag daine Enderungen ins owere Textföidl ai.
'''Netter''' da Inhoit vom owern Textföidl werd gspaichert, wånn du auf  „{{int:savearticle}}“ druckst!",
'yourtext'                         => 'Dai Text',
'editingold'                       => "'''Ochtung: Du beorwaitst a oide Version vo derer Saiten. Wånn du spaichertst, wern olle naichen Versionen ywerschriim!'''",
'copyrightwarning'                 => "'''Bittschee kopiar koane Websaiten, dé néd daine oaganen san, benytz koane urhéwerrechtléch gschytzten Werke óne aner Dalaabnis vom Urhéwer!'''<br />

Du gibst uns dodamid dai Zuasog, dass du dén Text '''söiwer vafosst''' host, das da Text a Oigmoaguat '''(public domain)''' is, óder das da '''Urhéwer''' sai '''Zuastimmung''' geem hod. Fois der Text beraits irngdwó ånderst vaéffmtlécht worn is, moch bittschee an Hiwais in da Diskussiónssaitn.
<i>Bittschee beochtt, das olle {{SITENAME}}-Baitrég autómaatésch unter da „$2“ steengan (schau unter $1 fyr Details). Fois du néd mechst, das dai Orwait do vo ånderne vaéndert und vabroatt werd, dånn druck néd auf „Saiten spaichern“.</i>",
'semiprotectedpagewarning'         => "'''Hoibsperrung:''' De Saiten is aso gsperrt worn, das netter registriarde Benytzer de endern kenna.",
'titleprotectedwarning'            => "'''Ochtung: De Saitenerstöiung is aso gschytzt worn, das netter Benytzer mid [[Special:ListGroupRights|speziöie Rechte]] de Saiten erstöin kennan.'''
Zur Informazion foigt da aktuöie Logbuachaitrog:",
'templatesused'                    => "{{PLURAL:$1|De foigande Vurlog|D' voiganden Vurlong wern}} auf derer Saiten vawendt:",
'templatesusedpreview'             => "{{PLURAL:$1|De foigande Vurlog werd|D' foiganden Vurlong wern}} in derer Saiten-Vurschau vawendt:",
'templatesusedsection'             => 'De foiganden Vurlong wern vo dem Obschnit vawendt:',
'template-protected'               => '(schreibgschytzd)',
'template-semiprotected'           => '(schreibgschytzd fyr néd-ågmödte Benützer)',
'hiddencategories'                 => 'De Saiten is in {{PLURAL:$1|a vasteckte Kategorii|$1 vasteckte Kategoriin}} aisortiard:',
'nocreatetitle'                    => 'De Erstöiung vo naiche Saiten is aigschränkt.',
'permissionserrorstext-withaction' => "Du host de Berechtigung ned, dass d' $2.
{{PLURAL:$1|Grund|Grynd}}:",
'recreate-moveddeleted-warn'       => "'''Owocht: Du loodst aa Datai auffe, de scho friarer glescht worn is.'''
Bittschee priaf gånz genau, obs ernaite Auffeloon de Richtlinien entspricht.
Zua dainer Informazion foigts Lesch-Logbuach mid da Begryndung fyr de vurherige Leschung:",

# Account creation failure
'cantcreateaccounttitle' => 'Benutzerkonto kå ned erstöd wern',

# History pages
'viewpagelogs'           => 'Logbiacher fyr de Saiten åzoang',
'currentrev-asof'        => 'Aktuelle Version vum $2, $3 Uar.',
'revisionasof'           => 'Version vom $2, $3 Uar.',
'revision-info'          => 'Version vom $2 um $5 Uar am $4.',
'previousrevision'       => '← Nextöidare Version',
'nextrevision'           => 'Nextjyngerne Version →',
'currentrevisionlink'    => 'Aktuelle Version',
'cur'                    => 'Aktuö',
'next'                   => 'Naxte',
'last'                   => 'Vurherige',
'histlegend'             => 'Zum Ozoagn vo Änderungen einfach de zwoa Versionen auswähln und auf de Schaltfläche „{{int:compareselectedversions}}“ klicken.<br />
* (Aktuell) = Untaschied zur aktuellen Version, (Vorherige) = Untaschied zur vorherigen Version
* Uhrzeit/Datum = Version zu dera Zeit, Benutzername/IP-Adresse vom Bearbeiter, K = Kloane Änderung',
'history-fieldset-title' => 'Suach in da Versiónsgschicht',
'histfirst'              => 'öidaste',
'histlast'               => 'Naichaste',
'historyempty'           => '(laar)',

# Revision feed
'history-feed-title' => 'Versionsgschicht',
'history-feed-empty' => "Die angeforderte Seitn gibt's net.
Vielleicht is sie gelöscht oda verschom worn.
[[Special:Search|Durchsuachan]] S' {{SITENAME}} für passende neie Seitn.",

# Revision deletion
'rev-deleted-text-permission' => '<div class="mw-warning plainlinks"> De Version is glöscht worn und is nimma öffentlich zum einseng.
Nähere Angaben zum Löschvorgang sowia a Begründung findn si im [{{fullurl:{{#Special:Log}}/suppress|page={{PAGENAMEE}}}} Lösch-Logbuch].</div>',
'rev-deleted-text-view'       => '<div class="mw-warning plainlinks">De Version is gelöscht worn und is nimma öffentlich einsehbar.
Als Administrator kennan Sie weiterhin einseng.
Nähere Angaben zum Löschvorgang sowia a Begründung finden si im [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} Lösch-Logbuach].</div>',
'rev-deleted-no-diff'         => '<div class="mw-warning plainlinks">Du kannst diesen Unterschied nicht betrachten, da eine der Versionen aus den öffentlichen Archiven entfernt wurde.
Details stehen im [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} Lösch-Logbuch].</div>',
'rev-delundel'                => 'zoang/vastecka',
'revdelete-nooldid-title'     => 'Koa Version ogem',
'revdelete-text'              => "'''Der Inhalt oder andere Bestandteile gelöschter Versionen sind nicht mehr öffentlich einsehbar, erscheinen jedoch weiterhin als Einträge in der Versionsgeschichte.'''
{{SITENAME}}-Administratoren können den entfernten Inhalt oder andere entfernte Bestandteile weiterhin einsehen und wiederherstellen, es sei denn, es wurde festgelegt, dass die Zugangsbeschränkungen auch für Administratoren gelten.",
'revdel-restore'              => 'Siagborkeid ändern',
'pagehist'                    => 'Versionsgschicht',

# Merge log
'revertmerge'      => 'Vaoanigung zruckenemma',
'mergelogpagetext' => "Des is s'Logbuach vu de vareinigtn Versionsgschichtn.",

# Diffs
'history-title'           => 'Versionsgschicht vu „$1“',
'difference'              => '(Untaschied zwischn Versionen)',
'lineno'                  => 'Zailn $1:',
'compareselectedversions' => 'Gwöde Versionen vagleichen',
'editundo'                => 'ryckgängig',

# Search results
'searchresults'             => 'Suachergebniss',
'searchresults-title'       => 'Ergebniss voh da Suach noch „$1“',
'searchresulttext'          => "Fia weidare Infos üwa's Suacha schau auf'd [[{{MediaWiki:Helppage}}|Hüifeseitn]].",
'searchsubtitle'            => 'Dei Suachãnfråg: „[[:$1|$1]]“ ([[Special:Prefixindex/$1|ålle Seitn, de mid „$1“ ãnfãngan]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|ålle Seitn, de wås nåch „$1“ valinkn]])',
'searchsubtitleinvalid'     => 'Dei Suachãnfråg: „$1“.',
'notitlematches'            => 'Koane Üwareinstimmungen mid de Seitntitl',
'notextmatches'             => 'Ka Üwareinstimmung mid dem Inhåit gfundn',
'prevn'                     => "d'voahearing {{PLURAL:$1|$1}}",
'nextn'                     => 'de nextn {{PLURAL:$1|$1}}',
'shown-title'               => 'Zoag $1 {{PLURAL:$1|Ergebnis|Ergebniss}} pro Seiten',
'viewprevnext'              => 'Zoag ($1 {{int:pipe-separator}} $2) ($3)',
'search-result-size'        => '$1 ({{PLURAL:$2|1 Wort|$2 Werter}})',
'search-redirect'           => '(Weidaleitung vu „$1“)',
'search-section'            => '(Åbschnitt $1)',
'search-suggest'            => 'Hädst „$1“ gmoant?',
'search-interwiki-caption'  => 'Schwesterprojekte',
'search-interwiki-default'  => '$1 Eagebnisse:',
'search-interwiki-more'     => '(mea)',
'search-mwsuggest-enabled'  => 'mid Vurschleg',
'search-mwsuggest-disabled' => 'koane Vurschleg',
'nonefound'                 => "'''Hiwais:''' Es wern standardmässig nur oanige Nåmensraim durchsuacht. Setz ''all:'' vur dain Suachbegrif, um olle Saiten (inkl. Dischkrirsaiten, Vurlong usw.) z' durchsuacha oder züid 'n Nåmen vom z' durchsuachanden Nåmensraum.",
'powersearch'               => 'Suach',
'powersearch-legend'        => 'Eaweitate Suach',
'powersearch-ns'            => 'Suach in Nãmensräume:',
'powersearch-redir'         => 'Weidaleitungen ãnzoang',
'powersearch-field'         => 'Suach nåch:',
'search-external'           => 'Externe Suach',

# Preferences page
'preferences'               => 'Eistellungen',
'mypreferences'             => 'Eigerne Eihstellungen',
'changepassword'            => 'Posswort ändern',
'prefs-editing'             => 'Beorweiten',
'prefs-edit-boxsize'        => 'Gress vom Beorweitungsfenster',
'rows'                      => 'Zeiln:',
'columns'                   => 'Spoiten',
'searchresultshead'         => 'Suachen',
'resultsperpage'            => 'Treffer pro Saiten:',
'savedprefs'                => 'Deine Eihstellungen san gspeicherd worn.',
'timezonelegend'            => 'Zaidzone:',
'localtime'                 => 'Ortszaid:',
'timezoneuseserverdefault'  => 'Standardzaid vom Server',
'guesstimezone'             => 'Vom Browser übanehma',
'allowemail'                => 'E-Mail-Empfång voh anderne Benutzer méglé mochen.',
'prefs-searchoptions'       => 'Suachopziónen',
'prefs-namespaces'          => 'Nåmensreim',
'username'                  => 'Benutzernåm:',
'yourrealname'              => 'Da echte Nåm:',
'yourlanguage'              => 'Sprooch vo da Benutzerowerflächen',
'prefs-help-realname'       => 'Opzionoi. Dodamid kå dai byrgerlicher Nåm daine Baiträg zuagordnet wern.',
'prefs-help-email'          => 'De Ågob vo ner E-Mäil is opzionoi, ermeglicht ower de Zuasendung voram Ersootzposswort, wånn du dai Posswort vagessen host.
Mid ånderne Benutzer kåst du aa ywer de Benutzerdischkrirsaiten an Kontakt aufnemma, one dass du dai Identität offmleeng muasst.',
'prefs-help-email-required' => 'Es werd a güitige E-Mäil-Adress braucht.',
'prefs-info'                => 'Baasisinformazionen',

# User rights
'userrights-groupsmember' => 'Midgliad vo:',
'userrights-no-interwiki' => 'Du hast koa Berechtigung, Benutzerrechte in anderne Wikis zum ändern.',

# Groups
'group-sysop'      => 'Administratorn',
'group-bureaucrat' => 'Bürokratn',
'group-suppress'   => 'Oversighter',
'group-all'        => '(olle)',

'group-user-member'          => 'Benutzer',
'group-autoconfirmed-member' => 'Autómaatisch bstätigter Benutzer',
'group-bot-member'           => 'Bot',
'group-sysop-member'         => 'Administraator',
'group-bureaucrat-member'    => 'Byrokraat',
'group-suppress-member'      => 'Oversighter',

'grouppage-user'          => '{{ns:project}}:Benutzer',
'grouppage-autoconfirmed' => '{{ns:project}}:Autómaatisch bstätigte Benutzer',
'grouppage-bot'           => '{{ns:project}}:Bots',
'grouppage-sysop'         => '{{ns:project}}:Administraatorn',
'grouppage-bureaucrat'    => '{{ns:project}}:Byrókraaten',
'grouppage-suppress'      => '{{ns:project}}:Oversighter',

# Rights
'right-read'  => 'Seiten leesen',
'right-edit'  => 'Seiten beorweiten',
'right-block' => 'Benutzer sperrn (Schreiwrecht)',

# User rights log
'rightslog' => 'Rechte-Logbiache',

# Associated actions - in the sentence "You do not have permission to X"
'action-edit'       => 'an derer Seiten duast werkeln',
'action-autopatrol' => 'eigerne Beorweitungen ois kontroilird markirn',

# Recent changes
'nchanges'                       => '$1 {{PLURAL:$1|Änderrung|Änderrungen}}',
'recentchanges'                  => 'Letzte Änderrungen',
'recentchanges-legend'           => 'Åzoagopzionen',
'recentchanges-feed-description' => 'Vafoig mid dem Feed de letztn Ändarungen in {{SITENAME}}.',
'rcnote'                         => "Untn {{PLURAL:$1|is de letzte Ändarung|san de letztn '''$1''' Ändarungen}} {{PLURAL:$2|vum letztn|vu de letztn '''$2'''}} Tåg aufglist. Stãnd vum $4 um $5.",
'rclistfrom'                     => 'Netta Änderungen seid $1 ãzoang.',
'rcshowhideminor'                => 'kloane Ändarungen $1',
'rcshowhidebots'                 => 'Bots $1',
'rcshowhideliu'                  => 'Ãngmöidte Benutza $1',
'rcshowhideanons'                => 'Anonyme Benutza $1',
'rcshowhidemine'                 => 'Eigne Beiträge $1',
'rclinks'                        => 'De letztn $1 Ändarungen vu de letztn $2 Tåg ãnzoang<br />$3',
'diff'                           => 'Untaschied',
'hist'                           => 'Versionen',
'hide'                           => 'ausblendn',
'show'                           => 'eihblenden',
'minoreditletter'                => 'K',
'newpageletter'                  => 'Neich',
'boteditletter'                  => 'B',
'rc-enhanced-expand'             => 'Deteus ãnzoang (gehd netta mid JavaScript)',
'rc-enhanced-hide'               => 'Details vastecka',

# Recent changes linked
'recentchangeslinked'         => 'Valinkts priafm',
'recentchangeslinked-feed'    => 'Valinkts priafm',
'recentchangeslinked-toolbox' => 'Valinkts priafm',
'recentchangeslinked-title'   => 'Ändarungen auf Seitn, zu de vu da Seitn „$1“ valinkt is',
'recentchangeslinked-summary' => "De Spezialseitn zagt de letztn Änderungen bei de Seitn, zu de vu ana gwissn Seitn valinkt wiad (bzw. de wås in ana gwissn Kategorie eisoatiad han). Seitn vu deina [[Special:Watchlist|Beobåchtungslistn]] wean '''fett''' ãnzoagt.",
'recentchangeslinked-page'    => 'Saiten:',
'recentchangeslinked-to'      => 'Zoagt Éndarungen auf Saiten, dé doher valinken',

# Upload
'upload'              => 'Auffeloon',
'uploadnologin'       => 'Ned ågmöidt',
'uploadnologintext'   => 'Du muasst [[Special:UserLogin|ågmödt]] seih, wånn Du Daatein auffeloon wüst.',
'uploadlog'           => 'Daatei-Logbiache',
'uploadlogpage'       => 'Daatei-Logbiache',
'uploadlogpagetext'   => 'Des is des Logbuach voh de auffegloodanen Daatein, schaug aa unter [[Special:NewFiles|neiche Daatein]].',
'uploadedfiles'       => 'Auffegloodane Daatein',
'badfilename'         => 'Da Daateinåm is auf „$1“ gänderd worn.',
'large-file'          => 'De Daateigreess soidad noch Meeglichkeid $1 ned ywerschreiten. De Daatei is $2 grooss.',
'emptyfile'           => "De hochgladene Datei is laar. Da Grund konn a Tippfehler im Dateinam sei. Bitte kontrollieren'S, ob Sie de Datei wirklich hochladn woin.",
'uploadwarning'       => 'Obacht',
'uploadedimage'       => 'håd „[[$1]]“ aufeglådn',
'uploaddisabled'      => "'tschuldigung, as Hochladn is deaktiviert.",
'uploadscripted'      => 'De Datei enthalt HTML- oda Scriptcode, der irrtümlichaweis von am Webbrowser ausgführt wern kinnat.',
'watchthisupload'     => 'De Saiten beowochten',
'filewasdeleted'      => 'A Datei mit dem Nama is scho oamoi hochgladn worn und zwischenzeitlich wieda glöscht worn. Bitte schaug erscht den Eintrag im $1 oo, bevor du de Datei wirklich speicherst.',
'upload-success-subj' => 'Erfolgreich hoachgladn',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6' => 'URL is net erreichbar',

'license-nopreview'  => '(es gibt koa Vorschau)',
'upload_source_file' => ' (a Datei auf deim Computa)',

# Special:ListFiles
'listfiles_search_for' => 'Suach noch da Datai:',

# File description page
'file-anchor-link'          => 'Datai',
'filehist'                  => 'Dateiversionen',
'filehist-help'             => 'Klick auf an Zeitpunkt, damid de Veasion glånd wiad.',
'filehist-current'          => 'aktuöi',
'filehist-datetime'         => 'Version vum',
'filehist-thumb'            => 'Voaschaubüidl',
'filehist-thumbtext'        => "Vorschaubüidl fia'd Veasion vum $1",
'filehist-user'             => 'Benytzer',
'filehist-dimensions'       => 'Moosse',
'filehist-filesize'         => 'Dataigress',
'filehist-comment'          => 'Kommentar',
'imagelinks'                => 'Dateivawendungen',
'linkstoimage'              => "{{PLURAL:$1|D'foignde Seitn vawendt|De foigndn $1 Seitn vawendn}} de Datei:",
'linkstoimage-more'         => "Es {{PLURAL:$1|valinkt|valinkn}} mea wia {{PLURAL:$1|oa Seitn |$1 Seitn}} auf de Datei.
De foignde Listn zaagt netta {{PLURAL:$1|in easten Link|de easten $1 Links}} auf de Datei.
A [[Special:WhatLinksHere/$2|voiständige Listn]] gibt's aa.",
'nolinkstoimage'            => 'Ka Seitn benutzt de Datei.',
'morelinkstoimage'          => '[[Special:WhatLinksHere/$1|Weidare Links]] fia de Datei.',
'duplicatesoffile'          => "{{PLURAL:$1|D'foignde Datei is a Duplikat|De foigndn $1 Datein han Duplikate}} vu dea Datei ([[Special:FileDuplicateSearch/$2|weidare Deteus]]):",
'sharedupload'              => 'De Datei stãmmt aus $1 und deaf bei ãndare Projekte vawendt wean.',
'sharedupload-desc-there'   => "De Datei stãmmt aus $1 und deaf bei ãndera Projekte vawendt wean. Schau auf'd [$2 Dateibeschreibungsseitn] fia weidare Infoamazionen.",
'uploadnewversion-linktext' => 'A neie Version vo dera Datei hoachladn',

# File reversion
'filerevert-defaultcomment' => 'zruckgsetzt auf de Version vom $1, $2',
'filerevert-submit'         => 'Zrucksetzen',

# File deletion
'filedelete-legend' => 'Lösch de Datei',
'filedelete-intro'  => "Du löschst de Datei '''„[[Media:$1|$1]]“'''.",

# MIME search
'mimesearch-summary' => 'Auf dieser Spezialseite können die Dateien nach dem MIME-Typ gefiltert werden. Die Eingabe muss immer den Medien- und Subtyp beinhalten: <tt>image/jpeg</tt> (siehe Bildbeschreibungsseite).',
'download'           => 'Owerlooden',

# Unused templates
'unusedtemplates' => 'Net benutzte Vorlagen',

# Random page
'randompage' => 'Zuafoisartike',

# Statistics
'statistics'               => 'Staatistik',
'statistics-articles'      => 'Inhoidsseiten',
'statistics-pages'         => 'Seiten',
'statistics-pages-desc'    => 'Olle Seiten in dém Wiki, inklusiav da Dischkrirseiten, Weiderloatungen usw.',
'statistics-files'         => 'Auffegloodane Daatein',
'statistics-edits'         => 'Seitenbeorweitungen',
'statistics-edits-average' => 'Beorweitungen pró Seiten im Durchschnit',
'statistics-views-total'   => 'Seitenaufruaff gsåmmt',
'statistics-mostpopular'   => 'Am meistn bsuachte Seitn',

'disambiguations-text' => 'De folgenden Seitn valinkn auf a Seitn zur Begriffsklärung.
Sie solltn stattdessn auf de eigentlich gemoante Seitn valinkn.<br />A Seitn werd ois Begriffsklärungsseitn behandelt, wenn [[MediaWiki:Disambiguationspage]] auf sie valinkt.<br />
Links aus Namensräume wern da net aufglistet.',

'doubleredirects' => 'Doppede Weiderloatungen',

'brokenredirects-edit'   => 'werkeln',
'brokenredirects-delete' => 'leschen',

'withoutinterwiki'         => 'Seiten óne an Link zua åndre Sproochen',
'withoutinterwiki-summary' => "D' foiganden Seiten valinken néd auf a åndre Sproochversion",
'withoutinterwiki-legend'  => 'Präfix',
'withoutinterwiki-submit'  => 'Zoag',

'fewestrevisions' => "Seiten mid d' weenigsten Versiónen",

# Miscellaneous special pages
'nbytes'                 => '$1 {{PLURAL:$1|Byte|Bytes}}',
'ncategories'            => '$1 {{PLURAL:$1|Kategorie|Kategorien}}',
'nlinks'                 => '{{PLURAL:$1|a Link|$1 Links}}',
'nmembers'               => '{{PLURAL:$1|1 Eithråg|$1 Eihtreeg}}',
'nrevisions'             => '{{PLURAL:$1|oah Beorwatung|$1 Beorwatungen}}',
'nviews'                 => '{{PLURAL:$1|1 Åbfråg|$1 Åbfrång}}',
'uncategorizedtemplates' => 'Néd kategorisirde Vurlong',
'wantedcategories'       => 'Bnutzde, ower néd åglégte Kategorien',
'prefixindex'            => 'Olle Seiten (mid Präfix)',
'shortpages'             => 'Kurze Seiten',
'longpages'              => 'Långe Seiten',
'newpages'               => 'Neiche Seiten',
'ancientpages'           => 'Schoh länger nimmer beorweitade Seiten',
'move'                   => 'vaschiam',
'movethispage'           => 'de Seiten vaschiam',
'notargettitle'          => 'Koa Seiten ågeem',
'pager-newer-n'          => '{{PLURAL:$1|nexder|nexde $1}}',
'pager-older-n'          => '{{PLURAL:$1|vurheriger|vurhering $1}}',

# Book sources
'booksources'               => 'ISBN-Suach',
'booksources-search-legend' => 'Suach nåch Bezugsquöin fia Biacha',
'booksources-go'            => 'Suach',

# Special:Log
'log'           => 'Logbiacha',
'all-logs-page' => 'Olle effmtlichen Logbiacher',
'alllogstext'   => 'Des is de kombinierte Anzeige vo alle in {{SITENAME}} gführten Logbiacha. Die Ausgabe ko durch de Auswahl vom Logbuchtyp, vom Benutzer oder vom Seitntitel eigschränkt wern.',
'logempty'      => 'Koane passenden Einträg.',

# Special:AllPages
'allpages'          => 'Olle Seiten',
'alphaindexline'    => '$1 bis $2',
'prevpage'          => 'Voaherige Seitn ($1)',
'allpagesfrom'      => 'Seitn zoang ab:',
'allpagesto'        => 'Seitn ãnzoang bis:',
'allarticles'       => 'Alle Seitn',
'allinnamespace'    => 'Alle Seitn (Namensraum: $1)',
'allnotinnamespace' => 'Alle Seitn (net im $1 Namensraum)',
'allpagesprev'      => 'Vorige',
'allpagesnext'      => 'Naxde',
'allpagessubmit'    => 'Åwenden',
'allpagesprefix'    => 'Seitn zoagn mit Präfix:',
'allpagesbadtitle'  => 'Da eigemne Seitennam is net gültig: Er håt entweda an vorogestellts Språch-, a Interwiki-Kürzel oda oa oda mehrere Zeichn, de im Seitnnam net verwendt wern derfan.',
'allpages-bad-ns'   => "Den Namensraum „$1“ gibt's in {{SITENAME}} net.",

# Special:LinkSearch
'linksearch'    => 'Weblinks suacha',
'linksearch-ok' => 'Suacha',

# Special:ListUsers
'listusers-submit'   => 'Zoag',
'listusers-noresult' => 'Koane Benutzer gfunden.',

# Special:Log/newusers
'newuserlogpage'          => 'Neiãnmöidungs-Logbiache',
'newuserlog-create-entry' => 'Benutza is nei registriad',

# Special:ListGroupRights
'listgrouprights-members' => '(Mitgliadalistn)',

# E-mail user
'mailnologin'   => 'Sie san net oogmeidt.',
'emailuser'     => 'E-Mail an den Benutza',
'noemailtitle'  => 'Koa E-Mail-Adress',
'emailfrom'     => 'Vo',
'emailsend'     => 'Senden',
'emailccme'     => 'Schick a Kopii vo da E-Mail an mi söiwer',
'emailsenttext' => 'Dai E-Mäil is vaschickt worn.',

# Watchlist
'watchlist'         => 'Beobachtungslistn',
'mywatchlist'       => 'Beowochtungslisten',
'watchlistanontext' => 'Sie miaßn si $1, um Eanane Beobachtungslistn zum seng oda Einträge auf ihr zum bearbatn.',
'watchnologin'      => 'Sie san net ogmeidt',
'addedwatchtext'    => 'De Seitn „[[:$1]]“ is zua deina [[Special:Watchlist|Beobachtungslistn]] dazuado worn.
Änderunga an dera Seitn und vo da Diskussionsseitn wern da glistet und
in da Übasicht vo de [[Special:RecentChanges|letztn Änderungen]] in Fettschrift ozoagt.
Wennst de Seitn wieder vo deina Beobachtungslistn wegdoa mechtn, klickst auf da jeweiligen Seitn auf „nimma beobachten“.',
'removedwatchtext'  => "D'Seitn „[[:$1]]“ is vu deina [[Special:Watchlist|Beobåchtungslistn]] weg gnumma woan.",
'watch'             => 'Beobåchtn',
'watchthispage'     => 'Seitn beobachtn',
'unwatch'           => 'nimma beobachten',
'unwatchthispage'   => 'Nimma beobåchtn',
'watchlist-details' => 'Du beobåchst {{PLURAL:$1|$1 Seitn}}, Diskussionsseitn ned midzöihd',
'wlheader-enotif'   => '* Da E-Mail-Benachrichtigungsdienst is aktiviert.',
'watchlistcontains' => 'Dei Beobachtungslistn enthoit $1 {{PLURAL:$1|Seite|Seitn}}.',
'wlshowlast'        => 'Zoag de Änderunga vo de letzten $1 Stunden, $2 Tag oda $3 (in de letzten 30 Tag).',
'watchlist-options' => 'Ãnzeigeopzionen',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'Beowochten ...',
'unwatching' => 'Net beobachtn …',

'enotif_reset'       => 'Alle Seiten als besuacht markiern',
'enotif_newpagetext' => 'Des is a neie Seitn.',
'changed'            => 'gändert',
'enotif_lastvisited' => 'Alle Änderungen auf oan Blick: $1',
'enotif_body'        => 'Liaba/e $WATCHINGUSERNAME,
de {{SITENAME}} Seitn "$PAGETITLE" is vo $PAGEEDITOR am $PAGEEDITDATE $CHANGEDORCREATED worn.
Zusammenfassung vom Bearbeiter: $PAGESUMMARY $PAGEMINOREDIT
Es wern solang koae weitern Benachrichtigungsmails gsendt, bis Sie de Seitn wieder besuacht ham. Auf Eanana Beobachtungsseitn kinnans S\' alle Benachrichtigungsmarker zsamm zrucksetzen.
             Eana {{SITENAME}} Benachrichtigungssystem
--
Um die Einstellungen Ihrer Beobachtungslistn anzupassen bsuachans bitte: {{fullurl:Special:Watchlist/edit}}',

# Delete
'deletepage'            => 'Seiten leschen',
'excontent'             => "Oider Inhoid: '$1'",
'exblank'               => "D' Seiten is laar gween",
'historywarning'        => "'''Ochtung:''' De Saiten, de du leschen mechst, hod a Versionsgschicht mid epper $1 {{PLURAL:$1|Version|Versionen}}:",
'confirmdeletetext'     => 'Sie san dabei, a Seitn oda a Datei und alle zughörigen ältern Versionen
zum löschen. Bitte bestätigen Sie da dazu, dass Sie des wirklich tuan wolln, dass Sie de Konsequenzen verstengan
und dass Sie in Übaeinstimmung mit de [[{{MediaWiki:Policy-url}}|Richtlinien]] handeln.',
'actioncomplete'        => 'Akzion beendet',
'deletedtext'           => '„$1“ is glöscht worn. Im $2 findn Sie a Listn vo de letzten Löschungen.',
'deletedarticle'        => 'hod „[[$1]]“ glescht',
'dellogpage'            => 'Lösch-Logbuach',
'deletionlog'           => 'Lösch-Logbuach',
'reverted'              => 'Auf a oide Version zruckgesetzt',
'deletecomment'         => 'Grund:',
'deleteotherreason'     => 'Ãndara/eagänznda Grund:',
'deletereasonotherlist' => 'Ãndara Grund',

# Rollback
'rollbacklink' => 'Zrucksetzen',

# Protect
'protectlogpage'              => 'Seitenschutz-Logbuach',
'protectedarticle'            => 'håd „[[$1]]“ gschützt',
'modifiedarticleprotection'   => 'håd in Schutz vu „[[$1]]“ gändat',
'prot_1movedto2'              => 'håt [[$1]] nåch [[$2]] verschom',
'protectcomment'              => 'Grund:',
'protectexpiry'               => 'Sperrdaua:',
'protect_expiry_invalid'      => "D'eigemne Daua is ungüitig.",
'protect_expiry_old'          => "D'Sperrzeid liegt in da Vagãngnheid",
'protect-text'                => "Då kãnnst nåchschau und ändan, wia d'Seitn „$1“ gschützt is.",
'protect-locked-access'       => "Dai Benytzerkonto vafiagt ned ywer de notwending Rechtt zur Endarung vom Saitenschutz. Do san de aktuöin Saitenschutzaistöiungen vo da Saiten '''„$1“:'''",
'protect-cascadeon'           => 'De Saiten is geengwärtig a Tail vo ner Kaskadenperrn. Sii is in de {{PLURAL:$1|folgende Seite|foiganden Saiten}} aibunden, de durch de Kaskadensperropzion geschytzt {{PLURAL:$1|ist|san}}. Der Saitenschutzstaatus vo derer Saiten kå gändert wern, des hod ower koan Aifluss auf de Kaskadensperrn:',
'protect-default'             => 'Ålle Benutza',
'protect-fallback'            => "D'„$1“-Berechtigung is notwendig.",
'protect-level-autoconfirmed' => 'Sperrung fia net registrierte Benutzer',
'protect-level-sysop'         => 'Netta Administratoan',
'protect-summary-cascade'     => 'kaskadiarnd',
'protect-expiring'            => 'bis zum $2 um $3 Uhr (UTC)',
'protect-cascade'             => 'Kaskadiarade Sperr – ålle Voalång, de in dea Seitn eibundn han, wean emfåis gspead.',
'protect-cantedit'            => "Du kãnnst de Spea vu dea Seitn ned ändan, weu'st dafia ned de passnde Berechtigung håst.",
'restriction-type'            => 'Schutzstaatus:',
'restriction-level'           => 'Schutzhechn:',

# Restrictions (nouns)
'restriction-move' => 'vaschiam',

# Undelete
'undelete'               => 'Gleschte Saiten wiiderherstöin',
'undeletehistorynoadmin' => 'De Seitn is glöscht worn. Da Grund fia de Löschung is in da Zsammafassung oogem,
genau wia Details zum letztn Benutza der de Seitn vor da Löschung bearbat håt.
Da aktuelle Text vo da glöschtn Seitn is nur fia Administratoren zum seng.',
'undeletebtn'            => 'Wiiderherstöin',
'undeletelink'           => 'åschaun/wiiderherstöin',
'undeletereset'          => 'Abbrecha',
'undeletedarticle'       => 'håt „[[$1]]“ wieda hergstellt',
'undeletedfiles'         => '$1 {{plural:$1|Datei|Dateien}} san wieda hergstellt worn',
'undelete-search-box'    => 'Suach nach glöschte Seitn',
'undelete-search-submit' => 'Suach',

# Namespace form on various pages
'namespace'      => 'Nåmensraum:',
'invert'         => 'Auswåih umdrahn',
'blanknamespace' => '(Seiten)',

# Contributions
'contributions'       => 'Benutzerbeiträg',
'contributions-title' => 'Benutzabeiträg vu „$1“',
'mycontris'           => 'Eigerne Beitreeg',
'contribsub2'         => 'Fia $1 ($2)',
'uctop'               => '(aktuöi)',
'month'               => 'und Monat',
'year'                => 'bis zum Joa:',

'sp-contributions-newbies'     => 'Netta de Beiträg vu de neichn Benutza ãnzoang',
'sp-contributions-newbies-sub' => 'Fia Neiling',
'sp-contributions-blocklog'    => 'Sperrlogbiache',
'sp-contributions-talk'        => 'Diskussion',
'sp-contributions-search'      => 'Suach nach Benutzerbeiträge',
'sp-contributions-username'    => 'IP-Adress oda Benutzanãm:',
'sp-contributions-submit'      => 'Suacha',

# What links here
'whatlinkshere'            => 'Links auf de Seiten',
'whatlinkshere-title'      => 'Seitn, de nåch „$1“ valinkn',
'whatlinkshere-page'       => 'Seitn:',
'linkshere'                => "Dé foiganden Saiten valinken auf '''„[[:$1]]“''':",
'isredirect'               => 'Weiterleitungsseitn',
'istemplate'               => 'Voalãngeibindung',
'isimage'                  => 'Dateilink',
'whatlinkshere-prev'       => "{{PLURAL:$1|vorige|d'voring $1}}",
'whatlinkshere-next'       => "{{PLURAL:$1|nexde|d'nexdn $1}}",
'whatlinkshere-links'      => '← Vaweise',
'whatlinkshere-hideredirs' => 'Weidaleitungen $1',
'whatlinkshere-hidetrans'  => 'Voalãngeibindungen $1',
'whatlinkshere-hidelinks'  => 'Links $1',
'whatlinkshere-hideimages' => 'Datailinks $1',
'whatlinkshere-filters'    => 'Füita',

# Block/unblock
'blockip'                  => 'IP-Adress/Benytzer sperrn',
'blockip-title'            => 'Benytzer sperrn',
'blockip-legend'           => 'IP-Adresse/Benutzer sperrn',
'blockiptext'              => "Mid dem Formular sperrst a IP-Adress oder an Benytzernåmen, das vo durten aus koane Endarungen mer vurgnumma wern kennan.
Des soid nur dafoing, um an Vandalismus z' vahindern und in Yweraistimmung mid d' [[{{MediaWiki:Policy-url}}|Richtlinien]].
Gib bittschee an Grund fyr d' Sperrn å.",
'ipadressorusername'       => 'IP-Adress oder Benytzernåm:',
'ipbexpiry'                => 'Sperrdauer:',
'ipbreason'                => 'Grund:',
'ipbreasonotherlist'       => 'Åndarer Grund:',
'ipbreason-dropdown'       => "*Oigmoane Sperrgrynd:
**Aifyng vo foische Informazionen
**'s Laarn vo Saiten
**a mossenwaiss Aifyng vo externe Links
**Aistöin vo unsinnige Inhoite in Saiten
**Ned åbrochts Vahoiten
**Missbrauch mid mererne Benytzerkontos
**Ned åbrochter Benyternåm",
'ipbcreateaccount'         => "D' Erstöiung vo Benytzerkontos vahindern",
'ipbemailban'              => 'E-Mäil-Vasånd sperrn',
'ipbenableautoblock'       => "Sperr de aktuöi vo dem Benytzer gnytzte IP-Adress sowia automaatisch olle foiganden, vo denen aus er Beorwaitungen oder 's Åleeng vo naiche Benytzerkontos vasuacht",
'ipbsubmit'                => 'IP-Adress/Benytzer sperrn',
'ipbother'                 => 'Åndare Dauer (auf englisch):',
'ipboptions'               => '2 Stund:2 hours,1 Dog:1 day,3 Dog:3 days,1 Woch:1 week,2 Wochen:2 weeks,1 Monad:1 month,3 Monad:3 months,6 Monad:6 months,1 Jor:1 year,Leemslång:infinite',
'ipbotheroption'           => 'Åndre Dauer:',
'ipbotherreason'           => 'Ånderner/ergenznder Grund:',
'ipbhidename'              => 'An Benytzernåmen in Beorwaitungen und Linsten vastecken',
'ipbwatchuser'             => 'De Benytzer(diskussions)saiten beowochten',
'ipb-change-block'         => "D' Sperrn mid de Sperrparameter danaiern",
'badipaddress'             => 'De IP-Adress hod a foischs Format.',
'blockipsuccesssub'        => 'De Sperrn is erfoigraich gween',
'blockipsuccesstext'       => 'Da Benytzer/de IP-Adress [[Special:Contributions/$1|$1]] is gsperrt worn.<br />
Zur da Aufheewung vo da Sperrn schau unter da [[Special:IPBlockList|Listen vo olle aktivm Sperrn]].',
'ipb-edit-dropdown'        => 'Sperrgrynd beorwaiten',
'ipb-unblock-addr'         => '„$1“ fraigeem',
'ipb-unblock'              => 'IP-Adress/Benytzer fraigeem',
'ipb-blocklist'            => 'Olle aktuöin Sperrn åzaang',
'ipb-blocklist-contribs'   => 'Benytzerbaiträg vo „$1“',
'unblockip'                => 'IP-Adress fraigeem',
'unblockiptext'            => 'Mid dem Formular do kååst du a IP-Adress oder an Benytzer fraigeem.',
'ipusubmit'                => 'Freigem',
'unblocked'                => '[[User:$1|$1]] is freigem worn',
'unblocked-id'             => 'Sperr-ID $1 is fraigeem worn',
'ipblocklist'              => 'Gsperrde Benutzer',
'ipblocklist-legend'       => 'Suach noch am gsperrden Benytzer',
'createaccountblock'       => 'Erstellung vo Benutzakonten gsperrt',
'emailblock'               => 'E-Post vaschicka gspead',
'blocklink'                => 'sperrn',
'unblocklink'              => 'freigem',
'change-blocklink'         => 'Sperr ändern',
'contribslink'             => 'Beitreeg',
'autoblocker'              => "Automatische Sperre, weil s' a gmeinsame IP-Adressn mit „$1“ hernehma. Grund: „$2“.",
'blocklogpage'             => 'Benutzasperrlogbiache',
'blocklogentry'            => "håd „[[$1]]“ fia'n foigndn Zeidraum gspead: $2; $3",
'unblocklogentry'          => "håd d'Spear vu „$1“ aufghom",
'block-log-flags-anononly' => 'netta Anonyme',
'block-log-flags-nocreate' => "S'Eastöin vu Benutzakontn is gspead",
'block-log-flags-noemail'  => 'E-Post vaschicka gspead',

# Developer tools
'unlockdb'            => 'Datenbank freigem',
'unlockconfirm'       => 'Ja, i mecht de Datenbank freigem.',
'unlockbtn'           => 'Datenbank freigem',
'locknoconfirm'       => 'Sie ham des Bestätigungsfeld net markiert.',
'lockfilenotwritable' => "Die Datenbank-Sperrdatei ist net beschreibbar. Zum Sperrn oda Freigem vo da Datenbank muaß de für'n Webserver beschreibbar sei.",
'databasenotlocked'   => 'De Datenbank is net gsperrt.',

# Move page
'move-page-legend'       => 'Seitn vaschiam',
'movepagetext'           => "Mid dem Foamular kãnnst a Seitn umbenenna (midsãmt ålle Veasionen).
Vum åidn Titl wiad ma nåchand zum neichn weidagschickd.
Du kãnnst Weidaleitungen, de auf'n Originaltitl valinkn, automatisch korrigian låssn.
Fåis'd des ned tuast, schau bitte nåch ob's [[Special:DoubleRedirects|doppide]] oda [[Special:BrokenRedirects|hiniche Weidaleitungen]] gibt.
Du bist dafia vaãntwoatlich, dass ålle Links aa nåch'm Vaschiam auf's richtige Züi zoang.

De Seitn wiad '''ned''' vaschom, wãnn's schãu a Seitn mid genau dem Nãm gibt, den'st mechst dass'd Seitn iatst kriagt, außa de Seitn is laa oda a Weidaleitung ohne Veasionsgschicht. Des haaßt, dass'd a Seitn zruckvaschiam kãnnst, wãnn'st an Fehla gmåcht håst. A Seitn üwaschreim kãnnst åwa ned.

'''Wårnung'''
D'Vaschiebung kãu weidreichade und ned eawårtete Foing fia beliebte Seitn håm.
Es warad åiso guat, wãnn'st ålle Konsequenzn vastãndn håst, bevoa'st a Seitn wiakli vaschiabst.",
'movepagetalktext'       => "De dazuaghearade Dischgria-Seitn wiad, fåis's ane gibt, midvaschom, '''außa'''
*unta'm neichn Nãm gibt's schãu an Eintråg oda
*du tuast s'Hakal bei da unting Opzion außa.

In de zwoa Fälle miaßadst, fåis des gwünscht is, de Seitn händisch vaschiam oda zsãmmfüng.

Bittschee gib außadem druntn in '''neichn''' Nãm vu da Seitn ei und schreib kuaz '''wieso'''<nowiki>'st</nowiki> de Seitn vaschiam mechst.",
'movearticle'            => 'Seitn vaschiam:',
'movenologin'            => 'Du bist ned ãngmöidt',
'movenologintext'        => 'Zum Vaschiam muaßt a registriada und [[Special:UserLogin|ãngmöideta Benutza]] sei.',
'movenotallowed'         => 'Du håst ka Berechtigung zum Vaschiam vu Seitn.',
'movenotallowedfile'     => 'Du håst ka Berechtigung zum Vaschiam vu Datein.',
'cant-move-user-page'    => 'Du håst ka Berechtigung zum Vaschiam vu Benutzahauptseitn.',
'cant-move-to-user-page' => 'Du håst ka Berechtigung zum Vaschiam vu Seitn auf a Benutzaseitn (Ausnãhmen han Benutza-Untaseitn).',
'newtitle'               => 'Züi:',
'move-watch'             => 'De Seitn beobachten',
'movepagebtn'            => 'Seitn vaschiam',
'pagemovedsub'           => "s'Vaschiam håd highaud",
'movepage-moved'         => "'''D'Seitn „$1“ is nåch „$2“ vaschom woan.'''",
'articleexists'          => 'Unter dem Nam existiert bereits a Seitn.
Bitte nehmans an andern Nam.',
'talkexists'             => "D'Seitn söiwa is eafoigreich vaschom woan, de dazuaghearade Diskussionsseitn åwa ned, weu's unta dea iahm neichn Nãm schãu a Seitn gibt. Bittschee kümmat di händisch um's Zsãmmfüahn.",
'movedto'                => 'vaschom nåch',
'movetalk'               => "Wãun's gehd de Dischgria-Seitn midvaschiam",
'1movedto2'              => 'håt [[$1]] nåch [[$2]] verschom',
'1movedto2_redir'        => 'håt [[$1]] nåch [[$2]] verschom und dabei a Weiterleitung überschriem',
'movelogpage'            => 'Vaschiabungs-Logbuach',
'movereason'             => 'Grund:',
'revertmove'             => 'zruck vaschiam',
'delete_and_move'        => 'Löschn und vaschiam',
'delete_and_move_reason' => 'glöscht, um Plåtz fia Vaschiam zum macha',
'selfmove'               => 'Ursprungs- und Zielname sand gleich; a Seitn kann net auf sich selber verschom wern.',

# Export
'export' => 'Seitn expoatian',

# Namespace 8 related
'allmessagesname'           => 'Nam',
'allmessagescurrent'        => 'Aktuella Text',
'allmessagestext'           => 'Des is a Listen vo de MediaWiki-Systemtextt.
Bsuach bittschee de Saiten [http://www.mediawiki.org/wiki/Localisation MediaWiki-Lokalisiarung] und [http://translatewiki.net translatewiki.net], wånn du de ån da Lokalisiarung vo MediaWiki betailing mechadst.',
'allmessagesnotsupportedDB' => "'''Special:Allmessages''' is im Moment net möglich, wei de Datenbank offline is.",

# Thumbnails
'thumbnail-more' => 'vagreßan',

# Special:Import
'importnotext' => 'Laar oder koa Text',

# Import log
'importlogpage' => 'Import-Logbuach',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'Deih Benutzerseiten',
'tooltip-pt-mytalk'               => 'Deih Diskussiónsseiten',
'tooltip-pt-preferences'          => 'Eigerne Eihstellungen',
'tooltip-pt-watchlist'            => "Listen voh d' Seiten, dést beówochst",
'tooltip-pt-mycontris'            => 'Liste vo eigene Beiträg',
'tooltip-pt-login'                => 'Das ma se ånmödt, werd zwor gern gseeng, is ower koa Pflicht ned.',
'tooltip-pt-logout'               => 'Obmöden',
'tooltip-ca-talk'                 => 'Diskussion zum Seiteninhoid',
'tooltip-ca-edit'                 => "Seiten beorweiten. Bittschee vurm Speichern d' Vurschaufunkzion bnytzen",
'tooltip-ca-addsection'           => 'An Kommentar zua dera Diskussion dazuagem.',
'tooltip-ca-viewsource'           => 'De Seitn is gschützt. An Quelltext kann ma oschaun.',
'tooltip-ca-history'              => 'Friarerne Versionen vo derer Seiten',
'tooltip-ca-protect'              => 'De Seitn schützen',
'tooltip-ca-delete'               => 'De Seitn löschen',
'tooltip-ca-move'                 => 'Dé Seiten vaschiam',
'tooltip-ca-watch'                => 'Dé Seiten zua persénlichen Beówochtungslisten dazua doah',
'tooltip-ca-unwatch'              => 'De Seitn von da persönlichen Beobachtungslistn entferna',
'tooltip-search'                  => '{{SITENAME}} durchsuachen',
'tooltip-search-go'               => 'Gee direkt zua derer Saiten, de exakt am aigeewanen Nåm entspricht.',
'tooltip-search-fulltext'         => 'Suach noch Seiten, de den Text enthoiden',
'tooltip-p-logo'                  => 'Hauptseiten',
'tooltip-n-mainpage'              => "d' Hauptseiten åzoang",
'tooltip-n-mainpage-description'  => 'Hauptseiten bsuachen',
'tooltip-n-portal'                => "Ywers Portoi, wos d' mochen kåst, wo eppers zum finden is",
'tooltip-n-currentevents'         => 'Hintergrundinformazionen ywer akutöe Ereigniss',
'tooltip-n-recentchanges'         => 'Listen vo de letzten Änderrungen auf {{SITENAME}}',
'tooltip-n-randompage'            => 'Zuafällige Seiten',
'tooltip-n-help'                  => 'Hüfeseiten åzoang',
'tooltip-t-whatlinkshere'         => 'Listen vo olle Seiten, de do her zoang',
'tooltip-t-recentchangeslinked'   => "D' letzten Änderrungen auf de Seiten, de voh do valinkt san",
'tooltip-feed-rss'                => 'RSS-Feed vo derer Saiten',
'tooltip-feed-atom'               => 'Atom-Feed vo derer Saiten',
'tooltip-t-contributions'         => "d'Listn vu de Beiträg vu dem Benutza ãschau",
'tooltip-t-emailuser'             => 'Dem Benutza E-Post schicka',
'tooltip-t-upload'                => 'Datein auffeloon',
'tooltip-t-specialpages'          => 'Listen voh olle Speziaalseiten',
'tooltip-t-print'                 => 'Druckåsicht vo derer Seiten',
'tooltip-t-permalink'             => 'Dauerhofter Link zua derer Seitenversion',
'tooltip-ca-nstab-main'           => 'Seiteninhoid åzoang',
'tooltip-ca-nstab-user'           => 'Benutzerseiten åzoang',
'tooltip-ca-nstab-special'        => 'Des is a Speziaalsaiten, dest ned beorwaiten kååst.',
'tooltip-ca-nstab-project'        => 'Portoiseiten åzoang',
'tooltip-ca-nstab-image'          => "D'Dateiseitn ãnzoang",
'tooltip-ca-nstab-mediawiki'      => 'MediaWiki-Systémtext åzoang',
'tooltip-ca-nstab-template'       => "d'Vorlåg ãnzoang",
'tooltip-ca-nstab-help'           => 'Huifseitn oozoang',
'tooltip-ca-nstab-category'       => 'Kategorieseitn ãnzoang',
'tooltip-minoredit'               => 'De Änderung åis a klaane markian.',
'tooltip-save'                    => 'Änderrungen speichern',
'tooltip-preview'                 => "a Voaschau vu de Ändarungen ãn dea Seitn. Bittschee voa'm Speichan benutzn!
Vorschau der Änderungen an dieser Seite. Bitte vor dem Speichern benutzen!",
'tooltip-diff'                    => "d'Ändarungen an dem Text in ana Taböin ãzoang",
'tooltip-compareselectedversions' => 'Unterschiede zwischn zwoa ausgewählte Versiona vo dera  Seitn vergleicha.',
'tooltip-watch'                   => 'De Seitn da persönlichn Beobachtungslistn dazua doa.',
'tooltip-recreate'                => 'Seitn nei erstelln, obwoi sie glöscht worn is.',
'tooltip-rollback'                => 'Setzt ålle Beiträg, de vum gleichn Benutza gmåcht woan han, mid am anzing Klick auf de Veasion zruck, de aktuöi gwen is, bevoa dea oane zum weakln ãngfãnga håd.',
'tooltip-undo'                    => 'Mocht netter dé oane Éndarung ryckgängég und zoagts Resuitot in da Vurschau å, damid in da Zåmmfossungszailn a Begryndung ågeem wern kå.',

# Attribution
'lastmodifiedatby' => 'De Seitn is zletzt am $1 um $2 vo $3 gändert worn.',
'othercontribs'    => 'Basiert auf da Arbat vo $1',
'creditspage'      => 'Seitninformationa',

# Patrolling
'markedaspatrollederrortext' => 'Sie miaßn a Seitnänderung auswähln.',

# Image deletion
'deletedrevision'    => 'Oide Version $1 glöscht.',
'filedelete-missing' => 'De Datei „$1“ ko net glöscht wern, weils es net gibt.',

# Browsing diffs
'previousdiff' => '← Zum vorigen Versionsunterschied',
'nextdiff'     => 'zum nextn Untaschied in de Veasionen →',

# Media information
'file-info-size' => '$1 × $2 Pixl, Dateigreßn: $3, MIME-Typ: $4',
'file-nohires'   => '<small>Es gibt ka hechane Auflösung.</small>',
'svg-long-desc'  => 'SVG-Datei, Basisgreß: $1 × $2 Pixl, Dateigreß: $3',
'show-big-image' => 'Version in hechana Auflösung',

# Special:NewFiles
'newimages'         => 'Neie Dateien',
'newimages-summary' => 'De Spezialseitn zoagt de zletzt hochgeladena Buidl und Dateien o.',
'noimages'          => 'Koane Datein gfunden.',
'ilsubmit'          => 'Suach',

# Bad image list
'bad_image_list' => "Formaat:

Netter Zeun, de mid am * åfångan, wern ausgwertt. Ois erstas noch 'm * muass a Link auf a unerwynschte Datei steeh.
Dodrauf foigende Links auf Seiten in da söwen Zeun definirn Ausnåmen, in denen ernern Zusåmmenhång de Datei trotzdem vawendt wern derf.",

# Metadata
'metadata'          => 'Metadatn',
'metadata-help'     => 'Dé Datai enthoit waiderne Informaziónen, dé in da Reegl vo da Digitoikamera óder am vawendaten Scanner ståmman. Durch a nochträgliche Beorwaitung vo da Originoidatai kennan oanige Details vaéndert worn sai.',
'metadata-expand'   => 'Erweitate Deteus eiblendn',
'metadata-collapse' => "D'eaweidatn Deteus ausblendn",
'metadata-fields'   => 'Dé foiganden Föider vo de ESIF-Metadaaten in dém MediaWiki-Systémtext wern auf de Büidlbeschraiwungssaiten åzoagt; waiderne standardmässég "aikloppte" Details kennan åzoagt wern.
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* isospeedratings
* focallength
* artist
* copyright
* imagedescription
* gpslatitude
* gpslongitude
* gpsaltitude',

# EXIF tags
'exif-gpsspeed' => 'Geschwindigkeit vom GPS-Empfänger',

'exif-componentsconfiguration-0' => "Gibt's net",

# External editor support
'edit-externally'      => 'De Datei mid am exteanen Programm beåawatn',
'edit-externally-help' => '(Schau unter [http://www.mediawiki.org/wiki/Manual:External_editors Installaziónsåwaisungen] fyr waiderne Informaziónen)',

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => 'ålle',
'namespacesall' => 'ålle',
'monthsall'     => 'ålle',

# E-mail address confirmation
'confirmemail'           => 'E-Post-Adressn bestäting (Authentifiziarung)',
'confirmemail_noemail'   => 'Du håst ka güitige E-Post-Adressn in deine [[Special:Preferences|persönlichn Eistellungen]] eitrång.',
'confirmemail_send'      => 'Bestätigungscode zuaschicka',
'confirmemail_needlogin' => 'Sie miassn si $1 um Eana E-Mail-Adress zum bestätigen.',

# Multipage image navigation
'imgmultipageprev' => '← vorige Seitn',
'imgmultipagenext' => 'nächste Seitn →',

# Table pager
'table_pager_next'  => 'Nächste Seitn',
'table_pager_first' => 'Erste Seitn',
'table_pager_last'  => 'Letzte Seitn',
'table_pager_empty' => 'Koane Ergebnisse',

# Auto-summaries
'autosumm-blank'   => 'De Seitn is gleert worn.',
'autosumm-replace' => "Da Seitninhalt is durch an andern Text ersetzt worn: '$1'",
'autoredircomment' => 'Weiterleitung nåch [[$1]] is erstellt worn',
'autosumm-new'     => 'De Seitn is nei oglegt worn: $1',

# Size units
'size-bytes' => '$1 Bytes',

# Watchlist editor
'watchlistedit-noitems'        => 'Dei Beobachtungslistn is laar.',
'watchlistedit-normal-title'   => 'Beobachtungslistn bearbatn',
'watchlistedit-normal-legend'  => 'Eiträge vo da Beobachtungslistn wegnehma',
'watchlistedit-normal-explain' => "Des san de Eiträg vo deiner Beowochtungslisten. Um de Eiträg z' entferna, markir de Kastln neem de Eiträg und druck am End vo da Seiten auf „{{int:Watchlistedit-normal-submit}}“. Du kåst dei Beowochtungslisten aa im  [[Special:EditWatchlist/raw|Listenformat beorweiten]].",
'watchlistedit-normal-submit'  => 'Eiträge wegnehma',
'watchlistedit-raw-titles'     => 'Eiträg:',

# Watchlist editing tools
'watchlisttools-view' => 'Ändarungen vafoing',
'watchlisttools-edit' => 'noamal beåawatn',
'watchlisttools-raw'  => 'im Listnfoamat beåawatn',

# Special:Version
'version-hook-subscribedby' => 'Aufruf vo',

# Special:SpecialPages
'specialpages' => 'Speziaalseiten',

);
