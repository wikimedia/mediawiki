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
'tog-usenewrc'                => "Daweiterde Dorstöung voh d' létzden Änderrungen (JavaScript werd braucht)",
'tog-numberheadings'          => 'Ywerschriften autómaatisch nummerrirn',
'tog-showtoolbar'             => 'Beorweiten-Werkzeigleisten åzoang (JavaScript werd braucht)',
'tog-editondblclick'          => 'Seiten mid am Dóppedrucker beorweiden (JavaScript werd braucht)',
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
'tog-enotifwatchlistpages'    => 'Bei Änderrungen voh beówochterde Seiten a E-Mail schicken',
'tog-enotifusertalkpages'     => 'Bei Änderrungen voh meiner Benutzerseiten a E-Mail schicken',
'tog-enotifminoredits'        => 'Aa ba kloane Änderrungen voh beówochterde Seiten a E-Mail schicker',
'tog-enotifrevealaddr'        => 'Deih E-Mail-Adress in Benoochrichtigungs-E-Mails åzoang',
'tog-shownumberswatching'     => "D' Åzoi voh dé beówochterden Benutzer åzoang",
'tog-oldsig'                  => 'Existente Unterschrift',
'tog-fancysig'                => 'Unterschrift ois Wikitext bhåndln (óne autómaatische Valinkung)',
'tog-externaleditor'          => "An externen Editor ois Standard bnutzen (netter fyr Experten, braucht spezielle Eihstellungen auf'm eigernen Computer)",
'tog-externaldiff'            => "A externs Programm fyr Versionsunterschiad ois Standard bnutzen (netter fyr Experten, dafordert spezielle Eihstellungen auf'm eiganen Computer)",
'tog-showjumplinks'           => '„Wexeln zu“-Links aktivirn',
'tog-uselivepreview'          => 'Live-Vurschau nutzen (dodafyr braucht ma JavaScript) (experimentoy)',
'tog-forceeditsummary'        => 'Warnen, wånn ban Speichern dé Zåmmerfossung fööd',
'tog-watchlisthideown'        => 'Eigerne Beorweitungen in da Beówochtungslisten ausblenden',
'tog-watchlisthidebots'       => 'Beorweitungen durch Bots in da Bówochtungslisten ausblenden',
'tog-watchlisthideminor'      => 'Kloane Beorweitunger in da Bówochtungslisten ausblenden',
'tog-watchlisthideliu'        => 'Beorweitunger vo ågmödte Bnutzer in da Beówochtungslisten ausblenden',
'tog-watchlisthideanons'      => 'Ned ogmoidte Nutzer in da Beówochtungslisten ausblenden',
'tog-watchlisthidepatrolled'  => 'Kontroilirde Änderrungen in da Beowochtungslisten ausblenden',
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
'october'       => 'Óktówer',
'november'      => 'Nóvember',
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
'oct'           => 'Ókt.',
'nov'           => 'Nóv.',
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
'category-article-count'         => '{{PLURAL:$2|De Kategorie enthoit foigernde Seiten:|{{PLURAL:$1|Foigernde Seiten is aane voh insgsåmt $2 Seiten in derer Kategorie:|Es wern $1 voh insgsåmt $2 Seiten in derer Kategorie åzaagt:}}}}',
'category-article-count-limited' => 'De {{PLURAL:$1|foigande Seiten is|foiganden $1 Seiten san}} in derer Kategorie enthoiden:',
'category-file-count'            => '{{PLURAL:$2|Dé Kategorie enthoit fóigernde Daatei:|{{PLURAL:$1|Fóigernde Daatei is oane voh insgsåmmt $2 Daatein in derer Kategorie:|Es wern $1 voh insgsåmt $2 Daatein in derer Kategorie åzoagt:}}}}',
'category-file-count-limited'    => "{{PLURAL:$1|D' foingde Datei is|De foingden $1 Datein san}} in de Kategorie eisortird:",
'listingcontinuesabbrev'         => '(Furtsétzung)',
'index-category'                 => 'Indizirde Seiten',
'noindex-category'               => 'Néd-indizirde Seiten',
'broken-file-category'           => 'Seiten mid kaputte Daateilinks',

'about'         => 'Ywer',
'article'       => 'Artike',
'newwindow'     => '(werd in am neichen Fenster aufgmocht)',
'cancel'        => 'Obbrecher',
'moredotdotdot' => 'Merer',
'mypage'        => 'Eigerne Seiten',
'mytalk'        => 'Eigerne Diskussión',
'anontalk'      => 'Dischkrirseiten voh derer IP-Adress',
'navigation'    => 'Navigazión',
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
'vector-view-history'            => 'Versiónsgschicht',
'vector-view-view'               => 'Leesen',
'vector-view-viewsource'         => 'Quötext åzong',
'actions'                        => 'Akziónen',
'namespaces'                     => 'Nåmensraim',
'variants'                       => 'Varianten',

'errorpagetitle'    => 'Feeler',
'returnto'          => 'Zruck zua da Seiten $1.',
'tagline'           => 'Aus {{SITENAME}}',
'help'              => 'Hüf und Frong?',
'search'            => 'Suach',
'searchbutton'      => 'Suachen',
'go'                => 'Ausfyrn',
'searcharticle'     => 'Artiké',
'history'           => 'Versiónen',
'history_short'     => 'Versionen/Autorn',
'updatedmarker'     => '(gänderd)',
'printableversion'  => 'Versión zum Ausdrucken',
'permalink'         => 'Permanenter Link',
'print'             => 'Drucken',
'view'              => 'Leesen',
'edit'              => 'werkeln',
'create'            => 'Erstön',
'editthispage'      => 'Seiten beorweiten',
'create-this-page'  => 'Seiten erstön',
'delete'            => 'léschen',
'deletethispage'    => 'De Seiten leschen',
'undelete_short'    => '{{PLURAL:$1|1 Version|$1 Versionen}} wiederherstön',
'viewdeleted_short' => '{{PLURAL:$1|Oah geléschde Versión|$1 geléschde Versiónen}} åschauh',
'protect'           => 'Schytzen',
'protect_change'    => 'ändern',
'protectthispage'   => 'Seiten schytzen',
'unprotect'         => 'freigeem',
'unprotectthispage' => 'Seitenschutz ändern',
'newpage'           => 'Neiche Seiten',
'talkpage'          => 'De Seiten bsprecher',
'talkpagelinktext'  => 'Diskussión',
'specialpage'       => 'Speziaalseiten',
'personaltools'     => 'Persénlichs Werkzeig',
'postcomment'       => 'Neicher Obschnit',
'articlepage'       => 'Seiteninhoid åzoang',
'talk'              => 'Diskussión',
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
'redirectedfrom'    => '(voh $1 weider gschickd)',
'redirectpagesub'   => 'Weiderloatung',
'lastmodifiedat'    => 'Dé Seiten is zlétzd am $1 um $2 gänderd worn.',
'viewcount'         => 'Dé Seiten do is bis iatz {{PLURAL:$1|oahmoi|$1-moi}} obgruaffm worn.',
'protectedpage'     => 'Gschytzde Seiten',
'jumpto'            => 'Wexeln zua:',
'jumptonavigation'  => 'Navigazión',
'jumptosearch'      => 'Suach',
'view-pool-error'   => "Tschuidige, dé Server san im Moment ywerlostt.
Zvü Leid vasuachen, dé Seiten do z' bsuachen.
Bittscheh wort a por Minuten, bevur du 's nohamoi vasuachst.

$1",
'pool-timeout'      => "Zeidoblaaff beim Worten auf d' Sperrung",
'pool-queuefull'    => 'Poolworteschlång is vói',
'pool-errorunknown' => 'Unbekånnter Feeler',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'Ywer {{SITENAME}}',
'aboutpage'            => 'Project:Ywer',
'copyright'            => 'Da Inhoid is unter da $1 vafiagbor.',
'copyrightpage'        => '{{ns:project}}:Urheewerrechte',
'currentevents'        => 'Aktuelle Ereigniss',
'currentevents-url'    => 'Project:Aktuelle Ereigniss',
'disclaimers'          => 'Impressum',
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

'badaccess'        => 'Koane ausreichenden Rechtt',
'badaccess-group0' => "Du host néd d' daforderliche Berechtigung fyr dé Akzión do.",
'badaccess-groups' => 'Dé Akzión is bschränkt auf Benutzer, dé {{PLURAL:$2|da Gruppm|oahne voh dé Gruppm}} „$1“ åghern.',

'versionrequired'     => 'Versión $1 voh MediaWiki werd braucht.',
'versionrequiredtext' => "Versión $1 voh MediaWiki werd braucht, um dé Seiten nützen z' kenner.
Schaug auf [[Special:Version|Versiónsseiten]]",

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
'editsectionhint'         => 'Obschnit beorweiden: $1',
'toc'                     => 'Inhoidsvazeichnis',
'showtoc'                 => 'Åzoang',
'hidetoc'                 => 'vastecken',
'collapsible-collapse'    => 'Eihkloppm',
'collapsible-expand'      => 'Auskloppm',
'thisisdeleted'           => '$1 åschauh óder wiederherstön?',
'viewdeleted'             => '$1 åzoang?',
'restorelink'             => '$1 gléschde {{PLURAL:$1|Versión|Versiónen}}',
'feedlinks'               => 'Feed:',
'feed-invalid'            => 'Néd gütiger Feed-Abonnement-Typ.',
'feed-unavailable'        => 'Es steengern koane Feeds zur Vafiagung.',
'site-rss-feed'           => 'RSS-Feed fyr $1',
'site-atom-feed'          => 'Atom-Feed fyr $1',
'page-rss-feed'           => 'RSS-Feed fyr „$1“',
'page-atom-feed'          => 'Atom-Feed fyr „$1“',
'red-link-title'          => '$1 (dé Seiten gibts néd)',
'sort-descending'         => 'Obsteigend sortiern',
'sort-ascending'          => 'Aufsteigend sortiern',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Seiten',
'nstab-user'      => 'Benutzerseiten',
'nstab-media'     => 'Meedienseiten',
'nstab-special'   => 'Speziaalseiten',
'nstab-project'   => 'Projektseiten',
'nstab-image'     => 'Daatei',
'nstab-mediawiki' => 'Systémnoochricht',
'nstab-template'  => 'Vurlog',
'nstab-help'      => 'Hüfeseiten',
'nstab-category'  => 'Kategorie',

# Main script and global functions
'nosuchaction'      => 'De Akzion gibts ned',
'nosuchactiontext'  => 'Dé in da URL ågeewerne Akzión werd voh MediaWiki néd unterstytzd.
Es kå a Schreibfeeler in da URL vurlieng óder es is a feelerhofter Link åklickt worn.
Es kå sé aa um an Prógrammierfeeler in da Software, dé auf {{SITENAME}} bnutzd werd, håndeln.',
'nosuchspecialpage' => 'De Speziaalsaiten gibts ned',
'nospecialpagetext' => "<strong>Dé aufgruafferne Speziaalseiten is néd vurhånden.</strong>

Olle vafiagborn Speziaalseiten san in da [[Special:SpecialPages|Listen voh dé Speziaalseiten]] z' finden.",

# General errors
'error'                => 'Feeler',
'databaseerror'        => 'Feeler in da Daatenbånk',
'dberrortext'          => "Es is a Daatenbånkfeeler auftreeden!
Da Grund kå a Prógrammierfeeler seih.
D' létzde Daatenbånkobfrog wor:
<blockquote><tt>$1</tt></blockquote>
aus da Funkzión „<tt>$2</tt>“.
Dé Daatenbånk hod an Feeler „<tt>$3: $4</tt>“ gmödt.",
'dberrortextcl'        => "Tschuidigung! Es hod an Syntaxfeeler in da Daatenbånkobfrog geem.
D' letzte Daatenbånkobfrog hod  „$1“ aus da Funkzion „<tt>$2</tt>“ glautt.
De Daatenbånk möidt 'n Feeler: „<tt>$3: $4</tt>“.",
'laggedslavemode'      => "'''Ochtung:''' De åzoagte Seiten kunnterd unter Umständ ned d' letzden Beorweitungen enthoiden.",
'readonly'             => 'Daatenbånk gsperrd',
'enterlockreason'      => 'Bittscheh gib an Grund å, warum de Daatenbånk gsperrd wern soi und a Obschätzung ywer de Dauer voh da Sperrung',
'readonlytext'         => 'De Daatenbånk is vurywergeehend fyr Neieihtreeg und Änderrungen gsperrd. Bittschee vasuchs spaader nuamoi.

Grund voh da Sperrung: $1',
'missing-article'      => 'Der Text voh „$1“ $2 is néd in da Daatenbånk gfunden worn.

Dé Seiten is méglicherweis gléschd óder vaschóm worn.

Fois dés néd zuadrifft, host eventuö an Feeler in da Software gfunden. Bittscheh möd dés am [[Special:ListUsers/sysop|Administraator]] unter da Nennung voh da URL.',
'missingarticle-rev'   => '(Versiónsnummer: $1)',
'missingarticle-diff'  => '(Unterschiad zwischen Versionen: $1, $2)',
'readonly_lag'         => "De Daatenbånk is automaatisch fyr Schraibzuagriff gsperrt, damid se d' vatailten Daatenbånkserver (slaves) mim Hauptdaatenbånkserver (master) obglaichen kennan.",
'internalerror'        => 'Interner Feeler',
'internalerror_info'   => 'Interner Feeler: $1',
'fileappenderrorread'  => '"$1" hod wärend \'m dazuafyng ned gleesen wern kenna.',
'fileappenderror'      => 'Dé Daatei „$1“ hod néd noch „$2“ kopird wern kenner.',
'filecopyerror'        => 'De Datai „$1“ hod ned noch „$2“ kopird wern kenna.',
'filerenameerror'      => 'Dé Daatei „$1“ hod néd noch „$2“ umbenånnt wern kenner.',
'filedeleteerror'      => 'De Daatei „$1“ hod néd gléschd wern kenner.',
'directorycreateerror' => 'As Vazeichnis „$1“ hod néd åglégt wern kenner.',
'filenotfound'         => 'Dé Daatei „$1“ is néd gfunden worn.',
'fileexistserror'      => "In d' Daatei „$1“ hod néd gschriem wern kenner, weils dé Daatei nämlé schå gibt.",
'unexpected'           => 'Unerworteder Wert: „$1“=„$2“.',
'formerror'            => 'Feeler: Dé Eihgom håm néd vaorweitt wern kenner.',
'badarticleerror'      => 'Dé Akzión kå néd auf dé Seiten ågwendt wern.',
'cannotdelete'         => 'Dé Seiten óder Daatei "$1" kå néd gléschd wern.
Méglicherweis iss schoh vohram åndern gléschd worn.',
'badtitle'             => 'néd gütiger Titel',
'badtitletext'         => 'Da Titel voh da ågforderden Seiten is néd gütig, laar óder a ungütiger Sproochlink vohram åndern Wiki.',
'perfcached'           => "Dé fóigernden Daaten ståmmern aus 'm Cache und san méglicherweis nimmer aktuö:",
'perfcachedts'         => "Dé Daaten ståmmern aus 'm Cache, létzde Aktualisiarung: $2, $3 Uar",
'querypage-no-updates' => "'''Dé Aktualisiarungsfunkzión voh derer Seiten is derzeid deaktivird. Dé Daaten wern bis auf Weiders néd daneiert.'''",
'wrong_wfQuery_params' => 'Foische Parameeter fyr wfQuery()<br />
Funkzión: $1<br />
Obfrog: $2',
'viewsource'           => 'an Quötext åschauh',
'viewsourcefor'        => 'fyr $1',
'actionthrottled'      => 'Akziónszoi limitird',
'actionthrottledtext'  => 'Im Råmen voh ner Anti-Spam-Moossnåm kå dé Akzión do in am kurzen Zeidobstånd netter begrenzd ausgfyrd wern. Dé Grenzen host ywerschritten.
Bittscheh vasuachs in a por Minunten nuamoi.',
'protectedpagetext'    => "Dé Seiten is gschytzd worn, um Beorweitungen z' vahindern.",
'viewsourcetext'       => "Du kåst ower 'n Quötext åschaung und kópirn:",
'protectedinterface'   => "Dé Seiten do enthoit Text fyr d' Benutzerówerflächen voh da Software und is gschytzd, um an Missbrauch vurzbeing.",
'editinginterface'     => "'''Ówocht:''' Dé Seiten do enthoit voh da MediaWiki-Software gnutzden Text. 
Änderrungen auf derer Seiten wirken sé auf d' Benutzerówerflächen aus.
Ziag bittscheh im Foi voh Ywersétzungen in Betrocht, dé bei [http://translatewiki.net/wiki/Main_Page?setlang=de translatewiki.net], da Lókaalisiarungsblottform fyr MediaWiki, durchzfyrn.",
'sqlhidden'            => '(SQL-Obfrog vastéckt)',
'cascadeprotected'     => "Dé Seiten is zua da Beorweitung gsperrd worn. Sie is in d' {{PLURAL:$1|fóigande Seiten|fóiganden Seiten}} eihbunden, dé mid da Kaskaadensperrópzión gschytzd {{PLURAL:$1|is|san}}:
$2",
'namespaceprotected'   => "Du host néd d' daforderliche Berechtigung, Seiten im Náumensraum '''$1''' b'orweiden z' kenner.",
'customcssprotected'   => "Du host néd d' Berechtigung dé CSS enthoitende Seiten z' b'orweiden, weis d' persénlichen Eihstöungen vohram aundern Benutzer enthoitt.",
'customjsprotected'    => "Du host néd d' Berechtigung dé JavaScript enthoitende Seiten z' b'orweiden, weis d' persénlichen Eihstöungen vohram aundern Benutzer enthoitt.",
'ns-specialprotected'  => "Speziaalseiten kennern néd b'orweidt wern.",
'titleprotected'       => "A Seiten mid dém Nåm kå néd åglégd wern. Dé Sperrn is durch [[User:$1|$1]] mid da Begryndung ''„$2“'' eihgerichtt worn.",

# Virus scanner
'virus-badscanner'     => "Feelerhofte Kónfigurazión: unbekaunnter Virnscanner: ''$1''",
'virus-scanfailed'     => 'Scan is föögschlong (code $1)',
'virus-unknownscanner' => 'Néd bekaunnter Virnscanner:',

# Login and logout pages
'logouttext'                 => "'''Iatzerd bist obgmödt.'''

Du kåst {{SITENAME}} iatzerd anónym weiderdoah, óder di danaid unterm söwing óder am åndern Benutzernåm [[Special:UserLogin|åmöden]].
Beochtt ower, daas oanige Seiten noh åzoang kennern, daas du ågmödt bist, sólång du néd deih Browsercache glaard host.",
'welcomecreation'            => '== Servas, $1! ==

Deih Benutzerkontó is grood eihgrichtt worn.
Vagiss bittscheh néd, deine [[Special:Preferences|{{SITENAME}}-Eishtellungen]] åzpassen',
'yourname'                   => 'Benutzernåm:',
'yourpassword'               => 'Posswort:',
'yourpasswordagain'          => 'Posswort nóamoi',
'remembermypassword'         => 'Mid dém Browser dauerhoft ågmödt bleim (maximoi $1 {{PLURAL:$1|Dog|Dog}})',
'securelogin-stick-https'    => "Noch'm Auhmöden mid HTTPS vabunden bleim",
'yourdomainname'             => 'Eanerne Domain:',
'externaldberror'            => 'Entweder es ligt a Feeler bai da externen Authentifiziarung vur oder du derfst dai externs Benytzerkonto ned aktualisirn.',
'login'                      => 'Åmöden',
'nav-login-createaccount'    => 'Åmöden / Kóntó erstön',
'loginprompt'                => 'Zur Åmödung miassen Cookies aktivird seih.',
'userlogin'                  => 'Åmöden / Kontó erstön',
'userloginnocreate'          => 'Åmöden',
'logout'                     => 'Obmöden',
'userlogout'                 => 'Obmöden',
'notloggedin'                => 'Ned ågmödt',
'nologin'                    => "Du host koah Benutzerkóntó? '''$1'''.",
'nologinlink'                => 'A neichs Benutzerkontó erstön',
'createaccount'              => 'Benutzerkóntó åléng',
'gotaccount'                 => "Du host schoh a Benutzerkonto? '''$1'''.",
'gotaccountlink'             => 'Åmöden',
'userlogin-resetlink'        => "Host d' Åmödedaaten vagessen?",
'createaccountmail'          => 'per E-Mail',
'createaccountreason'        => 'Grund',
'badretype'                  => 'De zwoa Posswerter stimmer ned ywereih.',
'userexists'                 => 'Der Benutzernaum do is schoh vageem. Bittscheh nimm an aundern her.',
'loginerror'                 => 'Feeler bei da Åmödung',
'createaccounterror'         => 'Des Benutzerkonto hod ned erstöd wern kenner: $1',
'nocookiesnew'               => "Da Benytzerzuagång is erstöid worn, du bist ower ned ågmöidt. {{SITENAME}} benedigt fyr de Funkzion Cookies, bittschee aktiviar de und möidt de danoch mid daim naichn Benytzernåm und 'm dazuaghering Posswort å.",
'nocookieslogin'             => '{{SITENAME}} nimmt Cookies zum Ailoggen vo de Benytzer her. Du host Cookies deaktivird, bittschee aktivir de und vasuchs nuamoi.',
'nocookiesfornew'            => "Dés Benutzerkóntó is néd erstöd worn, wei d' Daatenherkumft néd damittelt wern hod kenner.
Es muass sichergstöd seih, daas Cookies aktivierd san. Danoch bittscheh d' Seiten daneit loon und wieder vasuacher.",
'loginsuccesstitle'          => "D' Åmöidung is erfoigraich gween",
'loginsuccess'               => 'Du bist jetzad ois „$1“ bai {{SITENAME}} ågmöidt.',
'wrongpassword'              => 'Des Posswort is foisch! Bitschee prowirs nuamoi.',
'wrongpasswordempty'         => 'Es is koa Posswort ned aigeem worn. Bittschee prowirs nuamoi.',
'mailmypassword'             => 'Neichs Posswort zuaschicken',
'passwordremindertitle'      => 'Naichs Posswort fyra {{SITENAME}}-Benytzerkonto',
'acct_creation_throttle_hit' => 'Du host scho $1 {{PLURAL:$1|Benytzerkonto|Benytzerkonten}} und kååst jetzad koane mer åleeng.',
'emailconfirmlink'           => 'E-Póst-Adressen bstäting (Authentifiziarung)',
'accountcreated'             => 'Benytzerkonto is erstöid worn',
'accountcreatedtext'         => "'s Benytzerkonto $1 is aigrichtt worn.",
'loginlanguagelabel'         => 'Sprooch: $1',

# Change password dialog
'resetpass'                 => 'Posswort ändern',
'oldpassword'               => 'Oids Posswort:',
'newpassword'               => 'Naichs Posswort:',
'retypenew'                 => 'Naichs Posswort (nuamoi):',
'resetpass-submit-loggedin' => 'Posswort ändern',
'resetpass-submit-cancel'   => 'Obbrechen',

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
'headline_sample' => 'Ywerschrift auf da zwoaten Ewene',
'headline_tip'    => 'Ewene-2-Ywerschrift',
'nowiki_sample'   => 'Dén néd-formatirden Text dodan eihfiang',
'nowiki_tip'      => 'Ned-formatirder Text',
'image_tip'       => 'Daateilink',
'media_tip'       => 'Meediendaatei-Link',
'sig_tip'         => 'Deih Unterschrift mid Zeidstempe',
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
'anoneditwarning'                  => "Du beorweitsd dé Seiten ois néd-ågmöidt. Wånn du dé speichertsd, werd deih aktuelle IP-Adress in da Versiónsgschichd aufzeichnet und is dodamid unwiaderruafflich '''éffmtléch''' zum åschauh.",
'missingsummary'                   => "'''Hiwais:''' du host koa Zåmmfossung ågeem. Wånn du ernait auf „{{int:savearticle}}“ druckst, werd dai Enderung one a Zåmmfossung ywernumma.",
'missingcommenttext'               => 'Bittschee gib a Zåmmfossung ai.',
'summary-preview'                  => 'Vurschau vo da Zåmmfossung:',
'subject-preview'                  => 'Vurschau vom Bedreff:',
'blockedtitle'                     => 'Da Benytzer is gsperrt',
'whitelistedittext'                => "Du muasst de $1, um Saiten beorwaiten z' kenna.",
'loginreqtitle'                    => "'s braucht a Åmöidung",
'loginreqlink'                     => 'åmöiden',
'loginreqpagetext'                 => "Du muasst de $1, dass d' Saiten leesen kååst.",
'accmailtitle'                     => 'Passwort is vaschickt worn',
'accmailtext'                      => 'E zuafällig genariards Posswort fyr [[User talk:$1|$1]] is an $2 gschickt worn.

Des Posswort fyr des naiche Benutzerkonto kå auf da Speziaalseiten  „[[Special:ChangePassword|Posswort ändern]]“ gändert wern.',
'newarticle'                       => '(Neich)',
'newarticletext'                   => "Du bist am Link zua ner Seiten gfóigt, dé néd vurhånden is.
Daas d' dé Seiten åléng kåst, trog dein Text in dé untensteeherde Boxen eih (schaug unter da [[{{MediaWiki:Helppage}}|Hüfeseiten]] fyr merer Informaziónen).
Bist du föschlicherweis dodan, dånn druck dé '''Zruck'''-Schoitflächen voh deim Browser.",
'anontalkpagetext'                 => "---- ''De Seiten werd dodazua hergnumma, am ned-ågmöiderten Benutzer Nochrichten z' hinterlossen.
Wånnst mid de Kommentare auf derer Seiten nix åfanga kåst, is vamuatlich da friarerne Inhower vo derer IP-Adress gmoat und du kåstas ignorirn.
Wånnst a anonymer Benutzer bist und denkst, das irrelevante Kommentare ån di grichtt worn san, [[Special:UserLogin|möid de bittschee å]], um zuakynfteg Vawirrung z' vamein.''",
'noarticletext'                    => 'De Saiten enthoit zua Zaid koan Text ned.
Du kååst an Titl vo derer Saiten auf de åndern Saiten [[Special:Search/{{PAGENAME}}|suacha]],
<span class="plainlinks"> in de dazuagheraden [{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} Logbiache suacha] oder de Saiten [{{fullurl:{{FULLPAGENAME}}|action=edit}} beorwaiten]</span>.',
'noarticletext-nopermission'       => 'Dé Seiten enthoit im Moment nó koan Text néd.
Du derfst an Titel auf åndre Seiten [[Special:Search/{{PAGENAME}}|suachen]]
óder dé zuaghering <span class="plainlinks">[{{fullurl:{{#special:Log}}|page={{FULLPAGENAMEE}}}} Logbiachén åschaung].</span>',
'userpage-userdoesnotexist'        => 'Des Benutzerkonto „<nowiki>$1</nowiki>“ is ned vurhånden. Bittschee priaf, ob du de Seiten wirkle erstöin/beorweiten wüist.',
'userpage-userdoesnotexist-view'   => 'Benutzerkonto „$1“ existiard ned.',
'blocked-notice-logextract'        => "{{GENDER:$1|Der Benutzer|De Benutzarin|Der Benutzer do}} is zurzeid gesperrd.
Zua da Informazion foigt a aktueller Auszug aus 'm Benutzersperr-Logbiache:",
'updated'                          => '(Gendert)',
'previewnote'                      => "'''Dés is netter a Vurschau, d' Seiten is nuh néd gspeicherd worn!'''",
'previewconflict'                  => "Dé Vurschau gibt 'n Inhoid vom ówern Textföd wieder. Só werd d' Seiten ausschaung, wånnst iatz speichern duast.",
'session_fail_preview'             => "'''Daine Beorwaitungen håm ned gspaichert wern kenna, wail Sitzungsdaaten valurn gånga san.'''
Bittschee vasuachs nuamoi, indem du unter da foiganden Textvurschau noamoi auf „Saiten spaichern“ druckst.
Soidad des Probleem bestee blaim, [[Special:UserLogout|möid de ob]] und danoch wider å.",
'editing'                          => 'Beorwaiten vo $1',
'editingsection'                   => 'Werkeln bei $1 (Obschnit)',
'editingcomment'                   => 'Werkeln voh $1 (Neicher Obschnit)',
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
'copyrightwarning2'                => "Bittscheh beocht, daas olle Beiträg zua {{SITENAME}} voh ånderne Midwirkende beorweitt, gänderd óder gléscht wern kennern.
Reich do koane Textt eih, fois du néd wüst, daas dé óne Eihschränkung gänderd wern kennern.

Du bstätigst dodamid aa, daas du dé Textt söwer gschriem host óder dé voh ner gmoahfrein Quön kópird host
(schaug $1 fyr weiderne Details). '''YWERTROG ÓNE A DALAABNIS KOANE URHÉWERRECHTLICH GSCHYTZDE INHOITE!'''",
'semiprotectedpagewarning'         => "'''Hoibsperrung:''' De Saiten is aso gsperrt worn, das netter registriarde Benytzer de endern kenna.",
'titleprotectedwarning'            => "'''Ochtung: De Saitenerstöiung is aso gschytzt worn, das netter Benytzer mid [[Special:ListGroupRights|speziöie Rechte]] de Saiten erstöin kennan.'''
Zur Informazion foigt da aktuöie Logbuachaitrog:",
'templatesused'                    => "{{PLURAL:$1|Dé fóigernde Vurlog|D' fóigernden Vurlong wern}} auf derer Seiten vawendt:",
'templatesusedpreview'             => "{{PLURAL:$1|De foigande Vurlog werd|D' foiganden Vurlong wern}} in derer Saiten-Vurschau vawendt:",
'templatesusedsection'             => '{{PLURAL:$1|Dé fóigende Vurlog werd|Fóigende Vurlong wern}} voh dém Obschnit vawendt:',
'template-protected'               => '(schreibgschytzd)',
'template-semiprotected'           => '(schreibgschytzd fyr néd-ågmödte Benützer)',
'hiddencategories'                 => 'Dé Seiten is in {{PLURAL:$1|a vasteckde Kategorie|$1 vasteckde Kategorien}} eihsortird:',
'nocreatetitle'                    => 'De Erstöiung vo naiche Saiten is aigschränkt.',
'nocreate-loggedin'                => "Du host koah Berechtigung, neiche Seiten z' erstön.",
'permissionserrors'                => 'Berechtigungsfeeler',
'permissionserrorstext'            => 'Du bist néd berechtigt, dé Akzión auszfyrn.  {{PLURAL:$1|Grund|Grynd}}:',
'permissionserrorstext-withaction' => "Du host de Berechtigung ned, dass d' $2.
{{PLURAL:$1|Grund|Grynd}}:",
'recreate-moveddeleted-warn'       => "'''Ówocht: Du dastöst a Seiten dé schoh friarer gléschd worn is.'''

Bittscheh priaff genau, ób dé erneite Seitendastöung dé Richtlinien entsprichd.
Zua deiner Informazión fóigts Lésch- und Vaschiawungs-Logbiaché mid da Begryndung fyr d' vurhergeherde Léschung:",
'moveddeleted-notice'              => "Dé Seiten do is gschléschd worn. Es fóigt a Auszug aus'm Lésch- und Vaschiawungs-Logbiaché voh derer Seiten.",
'edit-conflict'                    => 'Konflikt beim Beorweiden.',

# Parser/template warnings
'post-expand-template-inclusion-warning'  => "Owocht: D' Gréss vo eihbundne Vurlong is z' gróss, étlé Vurlong kennern néd eihbunden wern.",
'post-expand-template-inclusion-category' => "Seiten, in dé d' maximoie Gréss eihbundner Vurlong ywerschritten is",
'post-expand-template-argument-warning'   => "'''Ówocht:''' Dé Seiten enthoit minderstens oah Argument in ner Vurlog, dés expandird z' gróss is. Dé Argumentt wern ignorird.",
'post-expand-template-argument-category'  => 'Seiten, dé ignorirde Vurlongargumentt enthoiden',

# Account creation failure
'cantcreateaccounttitle' => 'Benutzerkonto kå ned erstöd wern',

# History pages
'viewpagelogs'           => 'Logbiacher fyr dé Seiten åzoang',
'currentrev'             => 'Aktuelle Versión',
'currentrev-asof'        => 'Aktuelle Versión vom $2, $3 Uar.',
'revisionasof'           => 'Versión vom $2, $3 Uar.',
'revision-info'          => 'Version vom $2 um $5 Uar am $4.',
'previousrevision'       => '← Nextöderne Versión',
'nextrevision'           => 'Nextjyngerne Version →',
'currentrevisionlink'    => 'Aktuelle Versión',
'cur'                    => 'Aktuö',
'next'                   => 'Naxte',
'last'                   => 'Vurherige',
'histlegend'             => 'Zum Ozoagn vo Änderungen einfach de zwoa Versionen auswähln und auf de Schaltfläche „{{int:compareselectedversions}}“ klicken.<br />
* (Aktuell) = Untaschied zur aktuellen Version, (Vorherige) = Untaschied zur vorherigen Version
* Uhrzeit/Datum = Version zu dera Zeit, Benutzername/IP-Adresse vom Bearbeiter, K = Kloane Änderung',
'history-fieldset-title' => 'Suach in da Versiónsgschicht',
'history-show-deleted'   => 'netter gléschde Versiónen',
'histfirst'              => 'Öderste',
'histlast'               => 'Neicherste',
'historyempty'           => '(laar)',

# Revision feed
'history-feed-title'          => 'Versiónsgschicht',
'history-feed-item-nocomment' => '$1 am $3 um $4 Uar',
'history-feed-empty'          => "Die angeforderte Seitn gibt's net.
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
'rev-delundel'                => 'zoang / vastecken',
'revdelete-nooldid-title'     => 'Koa Version ogem',
'revdelete-text'              => "'''Der Inhalt oder andere Bestandteile gelöschter Versionen sind nicht mehr öffentlich einsehbar, erscheinen jedoch weiterhin als Einträge in der Versionsgeschichte.'''
{{SITENAME}}-Administratoren können den entfernten Inhalt oder andere entfernte Bestandteile weiterhin einsehen und wiederherstellen, es sei denn, es wurde festgelegt, dass die Zugangsbeschränkungen auch für Administratoren gelten.",
'revdel-restore'              => 'Siagborkeid ändern',
'revdel-restore-deleted'      => 'gschléschde Versión',
'revdel-restore-visible'      => 'siagbore Versión',
'pagehist'                    => 'Versiónsgschicht',
'deletedhist'                 => 'Gléschde Versiónen',

# Merge log
'revertmerge'      => 'Vaoanigung zruckénemmer',
'mergelogpagetext' => "Des is s'Logbuach vu de vareinigtn Versionsgschichtn.",

# Diffs
'history-title'           => 'Versiónsgschicht voh „$1“',
'difference'              => '(Unterschiad zwischen dé Versiónen)',
'lineno'                  => 'Zeiln $1:',
'compareselectedversions' => 'Gwöde Versionen vagleichen',
'editundo'                => 'ryckgängig',
'diff-multi'              => '({{PLURAL:$1|A dazwischenliegerte Versión|$1 dazwischenliegende Versiónen}} vohram {{PLURAL:$2|Benutzer|$2 Benutzern}} {{PLURAL:$1|werd|wern}} néd åzoagt)',

# Search results
'searchresults'                    => 'Suachergebniss',
'searchresults-title'              => 'Ergebniss voh da Suach noch „$1“',
'searchresulttext'                 => "Fia weidare Infos üwa's Suacha schau auf'd [[{{MediaWiki:Helppage}}|Hüifeseitn]].",
'searchsubtitle'                   => 'Dei Suachãnfråg: „[[:$1|$1]]“ ([[Special:Prefixindex/$1|ålle Seitn, de mid „$1“ ãnfãngan]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|ålle Seitn, de wås nåch „$1“ valinkn]])',
'searchsubtitleinvalid'            => 'Dei Suachãnfråg: „$1“.',
'notitlematches'                   => 'Koane Üwareinstimmungen mid de Seitntitl',
'notextmatches'                    => 'Ka Üwareinstimmung mid dem Inhåit gfundn',
'prevn'                            => '{{PLURAL:$1|vurheriger|vurherige $1}}',
'nextn'                            => '{{PLURAL:$1|naxter|naxte $1}}',
'prevn-title'                      => '{{PLURAL:$1|Vurherigs Ergebnis|Vurherige $1 Ergebniss}}',
'nextn-title'                      => '{{PLURAL:$1|Foilgends Ergebnis|Foigende $1 Ergebniss}}',
'shown-title'                      => 'Zoag $1 {{PLURAL:$1|Ergebnis|Ergebniss}} pró Seiten',
'viewprevnext'                     => 'Zoag ($1 {{int:pipe-separator}} $2) ($3)',
'searchmenu-exists'                => "'''Es gibt a Seiten, dé'n Nåmen „[[:$1]]“ hod.'''",
'searchmenu-new'                   => "'''Erstö d' Seiten „[[:$1]]“ in dém Wiki.'''",
'searchprofile-articles'           => 'Inhoidsseiten',
'searchprofile-project'            => 'Hüfe und Prójektseiten',
'searchprofile-images'             => 'Muitimeedia',
'searchprofile-everything'         => 'Oiss',
'searchprofile-advanced'           => 'Daweiterd',
'searchprofile-articles-tooltip'   => 'Suachen auf $1',
'searchprofile-project-tooltip'    => 'Suachen in $1',
'searchprofile-images-tooltip'     => 'Noch Büder suachen',
'searchprofile-everything-tooltip' => 'Gsåmmten Inhoid durchsuachen (inkl. Dischkrirseiten)',
'searchprofile-advanced-tooltip'   => 'Suach in weiderne Nåmensraim',
'search-result-size'               => '$1 ({{PLURAL:$2|1 Wort|$2 Werter}})',
'search-result-category-size'      => '{{PLURAL:$1|1 Seiten|$1 Seiten}} ({{PLURAL:$2|1 Unterkategorie|$2 Unterkategorien}}, {{PLURAL:$3|1 Daatei|$3 Daatein}})',
'search-redirect'                  => '(Weiderloattung voh „$1“)',
'search-section'                   => '(Åbschnitt $1)',
'search-suggest'                   => 'Häderst „$1“ gmoahd?',
'search-interwiki-caption'         => 'Schwesterprojekte',
'search-interwiki-default'         => '$1 Eagebnisse:',
'search-interwiki-more'            => '(mea)',
'search-mwsuggest-enabled'         => 'mid Vurschleg',
'search-mwsuggest-disabled'        => 'koane Vurschleg',
'searchrelated'                    => 'vawåndt',
'searchall'                        => 'olle',
'showingresultsheader'             => "{{PLURAL:$5|Ergebnis '''$1''' voh '''$3'''|Ergebniss '''$1–$2''' voh '''$3'''}} fyr '''$4'''",
'nonefound'                        => "'''Hiwais:''' Es wern standardmässig nur oanige Nåmensraim durchsuacht. Setz ''all:'' vur dain Suachbegrif, um olle Saiten (inkl. Dischkrirsaiten, Vurlong usw.) z' durchsuacha oder züid 'n Nåmen vom z' durchsuachanden Nåmensraum.",
'search-nonefound'                 => 'Fyr deih Suachåfrog san koane Ergebniss gfunden worn',
'powersearch'                      => 'Suach',
'powersearch-legend'               => 'Eaweitate Suach',
'powersearch-ns'                   => 'Suach in Nãmensräume:',
'powersearch-redir'                => 'Weiderloattung åzoang',
'powersearch-field'                => 'Suach noch:',
'search-external'                  => 'Externe Suach',

# Preferences page
'preferences'               => 'Eihstellungen',
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
'timezoneuseserverdefault'  => 'Standardzeid vom Wiki nutzen ($1)',
'guesstimezone'             => 'Vom Browser übanehma',
'allowemail'                => 'E-Mail-Empfång voh anderne Benutzer méglé mochen.',
'prefs-searchoptions'       => 'Suachopziónen',
'prefs-namespaces'          => 'Nåmensreim',
'youremail'                 => 'E-Mail-Adress:',
'username'                  => 'Benutzernåm:',
'yourrealname'              => 'Da echte Nåm:',
'yourlanguage'              => 'Sprooch vo da Benutzerowerflächen',
'prefs-help-realname'       => 'Opzionoi. Dodamid kå dai byrgerlicher Nåm daine Baiträg zuagordnet wern.',
'prefs-help-email'          => "Dé Ågob voh ner E-Mail-Adressen is ópziónoi, daméglicht ower d' Zuasendung vohram Ersotzposswort, sófern du deih Posswort vagessen host.",
'prefs-help-email-others'   => "Mid åndre Benutzer kåst aa ywer d' Benutzerdischkrirseiten an Kontakt aufnemmer, one daas du deih Identität óffmléng muasst.",
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
'action-createpage' => "Seiten z' dastön",
'action-autopatrol' => 'eigerne Beorweitungen ois kontroilird markirn',

# Recent changes
'nchanges'                          => '$1 {{PLURAL:$1|Änderrung|Änderrungen}}',
'recentchanges'                     => 'Létzde Änderrungen',
'recentchanges-legend'              => 'Åzoagopziónen',
'recentchangestext'                 => "Auf derer Seiten kåst d' létzden Änderrungen auf '''{{SITENAME}}''' nochévavóing.",
'recentchanges-feed-description'    => 'Vafóig mid dém Feed dé létzden Änderrungen in {{SITENAME}}.',
'recentchanges-label-newpage'       => 'Neiche Seiten',
'recentchanges-label-minor'         => 'Kloane Änderrungen',
'recentchanges-label-bot'           => 'Änderrung durch an Bot',
'recentchanges-label-unpatrolled'   => 'Néd-kontróilirde Änderrung',
'rcnote'                            => "Untn {{PLURAL:$1|is de letzte Ändarung|san de letztn '''$1''' Ändarungen}} {{PLURAL:$2|vum letztn|vu de letztn '''$2'''}} Tåg aufglist. Stãnd vum $4 um $5.",
'rcnotefrom'                        => "Åzoagt wern d' Änderrungen seid  '''$2''' (max. '''$1''' Eihtrég).",
'rclistfrom'                        => 'Netter Änderrungen seid $1 åzoang.',
'rcshowhideminor'                   => 'kloane Ändarungen $1',
'rcshowhidebots'                    => 'Bots $1',
'rcshowhideliu'                     => 'Ãgmödte Benutzer $1',
'rcshowhideanons'                   => 'Anónyme Benutzer $1',
'rcshowhidepatr'                    => 'Kontróilirde Änderrungen $1',
'rcshowhidemine'                    => 'Eigerne Beiträg $1',
'rclinks'                           => "D' létzden $1 Änderrungen voh dé létzden $2 Dog åzoang<br />$3",
'diff'                              => 'Unterschiad',
'hist'                              => 'Versiónen',
'hide'                              => 'ausblenden',
'show'                              => 'eihblenden',
'minoreditletter'                   => 'K',
'newpageletter'                     => 'Neich',
'boteditletter'                     => 'B',
'number_of_watching_users_pageview' => '[$1 {{PLURAL:$1|beówochtender|beówochtende}} Benutzer]',
'rc_categories'                     => 'Netter Seiten aus d\' Kategorien (trennd mid "l"):',
'rc_categories_any'                 => 'Olle',
'newsectionsummary'                 => 'Neicher Obschnit /* $1 */',
'rc-enhanced-expand'                => 'Deteus åzoang (geet netter mid JavaScript)',
'rc-enhanced-hide'                  => 'Deteils vastecker',

# Recent changes linked
'recentchangeslinked'          => 'Änderrungen ån valinkte Seiten',
'recentchangeslinked-feed'     => 'Valinkts priafm',
'recentchangeslinked-toolbox'  => 'Valinkts priaffm',
'recentchangeslinked-title'    => 'Änderrungen ån Seiten, dé voh „$1“ valinkt san',
'recentchangeslinked-noresult' => 'Im ausgwöden Zeidraum san an dé valinkden Seiten koane Änderrungen vurgnummer worn.',
'recentchangeslinked-summary'  => "Dé Speziaalseiten zoagd d' létzden Änderrungen bei dé Seiten, zua dé voh ner gwissen Seiten valinkd werd (bzw. dé wos in ner gwissen Kategorie eihsortird san). Seiten voh deiner [[Special:Watchlist|Beówochtungslisten]] wern '''fett''' åzoagd.",
'recentchangeslinked-page'     => 'Seiten:',
'recentchangeslinked-to'       => 'Zoagt Änderrungen auf Seiten, dé do her valinken',

# Upload
'upload'              => 'Aufféloon',
'uploadbtn'           => 'Daatei aufféloon',
'uploadnologin'       => 'Ned ågmöidt',
'uploadnologintext'   => 'Du muasst [[Special:UserLogin|ågmödt]] seih, wånn Du Daatein auffeloon wüst.',
'uploadlog'           => 'Daatei-Logbiache',
'uploadlogpage'       => 'Daatei-Logbiache',
'uploadlogpagetext'   => 'Des is des Logbuach voh de auffegloodanen Daatein, schaug aa unter [[Special:NewFiles|neiche Daatein]].',
'filename'            => 'Daateinåm',
'filedesc'            => 'Bschreiwung',
'filereuploadsummary' => 'Daateiänderrungen:',
'filestatus'          => 'Copyright-Staatus:',
'filesource'          => 'Quön:',
'uploadedfiles'       => 'Aufféglooderne Daatein',
'badfilename'         => 'Da Daateinåm is auf „$1“ gänderd worn.',
'large-file'          => 'De Daateigreess soidad noch Meeglichkeid $1 ned ywerschreiten. De Daatei is $2 grooss.',
'emptyfile'           => "De hochgladene Datei is laar. Da Grund konn a Tippfehler im Dateinam sei. Bitte kontrollieren'S, ob Sie de Datei wirklich hochladn woin.",
'uploadwarning'       => 'Obacht',
'uploadedimage'       => 'hod „[[$1]]“ auffégloon',
'uploaddisabled'      => "'tschuldigung, as Hochladn is deaktiviert.",
'uploadscripted'      => 'De Datei enthalt HTML- oda Scriptcode, der irrtümlichaweis von am Webbrowser ausgführt wern kinnat.',
'watchthisupload'     => 'De Saiten beowochten',
'filewasdeleted'      => 'A Datei mit dem Nama is scho oamoi hochgladn worn und zwischenzeitlich wieda glöscht worn. Bitte schaug erscht den Eintrag im $1 oo, bevor du de Datei wirklich speicherst.',
'upload-success-subj' => 'Erfolgreich hoachgladn',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6' => 'URL is net erreichbar',

'license'            => 'Lizenz:',
'license-header'     => 'Lizenz',
'license-nopreview'  => '(es gibt koa Vorschau)',
'upload_source_file' => ' (a Datei auf deim Computa)',

# Special:ListFiles
'listfiles_search_for' => 'Suach noch da Datai:',

# File description page
'file-anchor-link'          => 'Daatei',
'filehist'                  => 'Daateiversiónen',
'filehist-help'             => "Klick auf an Zeidbunkt, um dé Versión z' loon.",
'filehist-revert'           => 'zrucksétzen',
'filehist-current'          => 'aktuö',
'filehist-datetime'         => 'Versión vom',
'filehist-thumb'            => 'Vurschaubüdel',
'filehist-thumbtext'        => "Vurschaubüdel fyr d' Versión vom $1, $3 Uar",
'filehist-user'             => 'Benutzer',
'filehist-dimensions'       => 'Moosse',
'filehist-filesize'         => 'Dataigress',
'filehist-comment'          => 'Kommentar',
'imagelinks'                => 'Daateivawendung',
'linkstoimage'              => "{{PLURAL:$1|D'foignde Seitn vawendt|De foigndn $1 Seitn vawendn}} de Datei:",
'linkstoimage-more'         => "Es {{PLURAL:$1|valinkt|valinkn}} mea wia {{PLURAL:$1|oa Seitn |$1 Seitn}} auf de Datei.
De foignde Listn zaagt netta {{PLURAL:$1|in easten Link|de easten $1 Links}} auf de Datei.
A [[Special:WhatLinksHere/$2|voiständige Listn]] gibt's aa.",
'nolinkstoimage'            => 'Koah Seiten bnutzd dé Daatei.',
'morelinkstoimage'          => '[[Special:WhatLinksHere/$1|Weidare Links]] fia de Datei.',
'duplicatesoffile'          => "{{PLURAL:$1|D'foignde Datei is a Duplikat|De foigndn $1 Datein han Duplikate}} vu dea Datei ([[Special:FileDuplicateSearch/$2|weidare Deteus]]):",
'sharedupload'              => 'De Datei stãmmt aus $1 und deaf bei ãndare Projekte vawendt wean.',
'sharedupload-desc-there'   => "De Datei stãmmt aus $1 und deaf bei ãndera Projekte vawendt wean. Schau auf'd [$2 Dateibeschreibungsseitn] fia weidare Infoamazionen.",
'sharedupload-desc-here'    => "Dé Daatei ståmmt aus $1 und derf voh åndre Prójektt vawendt wern. D' Bschreiwung voh da [$2 Daateibschreiwungsseiten] werd unten åzoagt.",
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
'randompage' => 'Zuafoisartiké',

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

'disambiguationspage'  => 'Template:Begriffsklärung',
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
'nbytes'                  => '$1 {{PLURAL:$1|Byte|Bytes}}',
'ncategories'             => '$1 {{PLURAL:$1|Kategorie|Kategorien}}',
'nlinks'                  => '{{PLURAL:$1|a Link|$1 Links}}',
'nmembers'                => '{{PLURAL:$1|1 Eithråg|$1 Eihtreeg}}',
'nrevisions'              => '{{PLURAL:$1|oah Beorwatung|$1 Beorwatungen}}',
'nviews'                  => '{{PLURAL:$1|1 Åbfråg|$1 Åbfrång}}',
'uncategorizedpages'      => 'Néd kategorisirde Seiten',
'uncategorizedcategories' => 'Néd kategorisirde Kategorien',
'uncategorizedimages'     => 'Néd kategorisirde Daatein',
'uncategorizedtemplates'  => 'Néd kategorisirde Vurlong',
'unusedcategories'        => 'Néd bnutzde Kategorien',
'unusedimages'            => 'Néd bnutzde Daatein',
'popularpages'            => 'Dé bliabersten Seiten',
'wantedcategories'        => 'Bnutzde, ower néd åglégte Kategorien',
'wantedpages'             => 'Gwynschde Seiten',
'wantedpages-badtitle'    => 'Ungütiger Titel im Ergeewnis: $1',
'wantedfiles'             => 'Fööhernde Daatein',
'wantedtemplates'         => 'Fööhernde Vurlong',
'mostlinked'              => 'Haiffig valinkte Seiten',
'mostlinkedcategories'    => 'Haiffig brauchde Kategorien',
'mostlinkedtemplates'     => 'Haiffig brauchde Vurlong',
'mostcategories'          => 'Seiten mid dé haiffigsten Kategorien',
'mostimages'              => 'Haiffig brauchde Daatein',
'mostrevisions'           => "Seiten mid d' haiffigsten Versiónen",
'prefixindex'             => 'Olle Seiten (mid Präfix)',
'shortpages'              => 'Kurze Seiten',
'longpages'               => 'Långe Seiten',
'deadendpages'            => 'Néd valinkende Seiten',
'usercreated'             => 'Erstöd am $1 um $2 Ur',
'newpages'                => 'Neiche Seiten',
'newpages-username'       => 'Benutzernåm:',
'ancientpages'            => 'Schoh länger nimmer beorweitade Seiten',
'move'                    => 'vaschiam',
'movethispage'            => 'de Seiten vaschiam',
'notargettitle'           => 'Koa Seiten ågeem',
'pager-newer-n'           => '{{PLURAL:$1|nexder|nexde $1}}',
'pager-older-n'           => '{{PLURAL:$1|vurheriger|vurhering $1}}',

# Book sources
'booksources'               => 'ISBN-Suach',
'booksources-search-legend' => 'Suach noch Benutzerquön fyr Biacher',
'booksources-go'            => 'Suach',

# Special:Log
'log'           => 'Logbiacher',
'all-logs-page' => 'Olle effmtlichen Logbiacher',
'alllogstext'   => 'Des is de kombinierte Anzeige vo alle in {{SITENAME}} gführten Logbiacha. Die Ausgabe ko durch de Auswahl vom Logbuchtyp, vom Benutzer oder vom Seitntitel eigschränkt wern.',
'logempty'      => 'Koane passenden Einträg.',

# Special:AllPages
'allpages'          => 'Olle Seiten',
'alphaindexline'    => '$1 bis $2',
'prevpage'          => 'Voaherige Seitn ($1)',
'allpagesfrom'      => 'Seitn zoang ab:',
'allpagesto'        => 'Seitn ãnzoang bis:',
'allarticles'       => 'Olle Seiten',
'allinnamespace'    => 'Alle Seitn (Namensraum: $1)',
'allnotinnamespace' => 'Alle Seitn (net im $1 Namensraum)',
'allpagesprev'      => 'Vorige',
'allpagesnext'      => 'Naxde',
'allpagessubmit'    => 'Åwenden',
'allpagesprefix'    => 'Seitn zoagn mit Präfix:',
'allpagesbadtitle'  => 'Da eigemne Seitennam is net gültig: Er håt entweda an vorogestellts Språch-, a Interwiki-Kürzel oda oa oda mehrere Zeichn, de im Seitnnam net verwendt wern derfan.',
'allpages-bad-ns'   => "Den Namensraum „$1“ gibt's in {{SITENAME}} net.",

# Special:Categories
'categories' => 'Kategorien',

# Special:DeletedContributions
'deletedcontributions' => 'Gléschde Beitrég',

# Special:LinkSearch
'linksearch'      => 'Weblinks suachen',
'linksearch-ok'   => 'Suacha',
'linksearch-line' => '$1 is valinkt voh $2',

# Special:ListUsers
'listusers-submit'   => 'Zoag',
'listusers-noresult' => 'Koane Benutzer gfunden.',

# Special:Log/newusers
'newuserlogpage' => 'Neiåmödungs-Logbiaché',

# Special:ListGroupRights
'listgrouprights-members' => '(Mitgliaderlisten)',

# E-mail user
'mailnologin'   => 'Sie san net oogmeidt.',
'emailuser'     => 'E-Póst an dén Benutzer',
'noemailtitle'  => 'Koa E-Mail-Adress',
'emailfrom'     => 'Vo',
'emailsend'     => 'Senden',
'emailccme'     => 'Schick a Kopii vo da E-Mail an mi söiwer',
'emailsenttext' => 'Dai E-Mäil is vaschickt worn.',

# Watchlist
'watchlist'         => 'Beówochtungslisten',
'mywatchlist'       => 'Beówochtungslisten',
'watchlistfor2'     => 'Voh $1 $2',
'watchlistanontext' => 'Sie miaßn si $1, um Eanane Beobachtungslistn zum seng oda Einträge auf ihr zum bearbatn.',
'watchnologin'      => 'Sie san net ogmeidt',
'addedwatchtext'    => 'De Seitn „[[:$1]]“ is zua deina [[Special:Watchlist|Beobachtungslistn]] dazuado worn.
Änderunga an dera Seitn und vo da Diskussionsseitn wern da glistet und
in da Übasicht vo de [[Special:RecentChanges|letztn Änderungen]] in Fettschrift ozoagt.
Wennst de Seitn wieder vo deina Beobachtungslistn wegdoa mechtn, klickst auf da jeweiligen Seitn auf „nimma beobachten“.',
'removedwatchtext'  => "D'Seitn „[[:$1]]“ is vu deina [[Special:Watchlist|Beobåchtungslistn]] weg gnumma woan.",
'watch'             => 'Beówochten',
'watchthispage'     => 'Seitn beobachtn',
'unwatch'           => 'nimmer beówochten',
'unwatchthispage'   => 'Nimmer beówochten',
'notanarticle'      => 'Koah Seiten',
'watchlist-details' => 'Du bówochst {{PLURAL:$1|$1 Seiten}}, Diskussiónsseiten néd midzöd',
'wlheader-enotif'   => '* Da E-Mail-Benachrichtigungsdienst is aktiviert.',
'watchlistcontains' => 'Dei Beobachtungslistn enthoit $1 {{PLURAL:$1|Seite|Seitn}}.',
'wlshowlast'        => 'Zoag de Änderunga vo de letzten $1 Stunden, $2 Tag oda $3 (in de letzten 30 Tag).',
'watchlist-options' => 'Åzoagópziónen',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'Beowochten ...',
'unwatching' => 'Néd Beówochten',

'enotif_reset'                 => 'Olle Seiten ois bsuacht markirn',
'enotif_newpagetext'           => 'Dés is a neiche Seiten.',
'enotif_impersonal_salutation' => '{{SITENAME}}-Benutzer',
'changed'                      => 'gänderd',
'created'                      => 'erstöd',
'enotif_lastvisited'           => 'Olle Änderrungen auf oahn Blick: $1',
'enotif_lastdiff'              => 'Schaug auf $1 noch derer Änderrung.',
'enotif_anon_editor'           => 'Anonymer Benutzer $1',
'enotif_body'                  => 'Servas $WATCHINGUSERNAME,

dé {{SITENAME}}-Seiten „$PAGETITLE“ is voh $PAGEEDITOR am $PAGEEDITDATE um $PAGEEDITTIME Uar $CHANGEDORCREATED.

Aktuöe Versión: $PAGETITLE_URL

$NEWPAGE

Zåmmfossung vom Beorweiter: $PAGESUMMARY $PAGEMINOREDIT

Kontakt zum Beorweiter:
E-Mail: $PAGEEDITOR_EMAIL
Wiki: $PAGEEDITOR_WIKI

Es wern da sólång koane weidern Benoochrichtigungsmails gsendt, bis du dé Seiten wieder bsuachst. Auf deiner Beówochtungslisten kåst olle Benoochrichtigunsmarkiarungen zåmm zrucksétzen.
             Deih freindlichs {{SITENAME}}-Benoochrichtigungssystém

--
Um d\' Eihstöungen voh da E-Mail-Benoochrichtigung åzpassen, bsuachst {{canonicalurl:{{#special:Preferences}}}}

Um d\' Eihstöungen voh deine Beówochtungslisten åzpassen, bsuachst {{canonicalurl:{{#special:EditWatchlist}}}}

Um d\' Seiten voh deiner Beówochtungslisten ower z\' doah, bsuachst $UNWATCHURL

Ryckmödungen und a weidre Hüf: {{canonicalurl:{{MediaWiki:Helppage}}}}',

# Delete
'deletepage'            => 'Seiten leschen',
'confirm'               => 'Bstäting',
'excontent'             => "Oider Inhoid: '$1'",
'exblank'               => "D' Seiten is laar gween",
'historywarning'        => "'''Ochtung:''' De Saiten, de du leschen mechst, hod a Versionsgschicht mid epper $1 {{PLURAL:$1|Version|Versionen}}:",
'confirmdeletetext'     => 'Sie san dabei, a Seitn oda a Datei und alle zughörigen ältern Versionen
zum löschen. Bitte bestätigen Sie da dazu, dass Sie des wirklich tuan wolln, dass Sie de Konsequenzen verstengan
und dass Sie in Übaeinstimmung mit de [[{{MediaWiki:Policy-url}}|Richtlinien]] handeln.',
'actioncomplete'        => 'Akzión beéndt',
'actionfailed'          => 'Akzión föögschlong',
'deletedtext'           => '„$1“ is glöscht worn. Im $2 findn Sie a Listn vo de letzten Löschungen.',
'dellogpage'            => 'Lésch-Logbiache',
'deletionlog'           => 'Lösch-Logbuach',
'reverted'              => 'Auf a oide Version zruckgesetzt',
'deletecomment'         => 'Grund:',
'deleteotherreason'     => 'Ånderner/ergänzender Grund:',
'deletereasonotherlist' => 'Åndrer Grund:',

# Rollback
'rollbacklink' => 'Zrucksétzen',

# Protect
'protectlogpage'              => 'Seitenschutz-Logbuach',
'protectedarticle'            => 'hod „[[$1]]“ gschytzd',
'modifiedarticleprotection'   => 'hod an Schutz voh „[[$1]]“ gänderd',
'unprotectedarticle'          => 'Schutz voh „[[$1]]“ aufghóm',
'prot_1movedto2'              => 'hod [[$1]] noch [[$2]] vaschóm',
'protect-legend'              => 'Seitenschutzstaatus ändern',
'protectcomment'              => 'Grund:',
'protectexpiry'               => 'Sperrdauer:',
'protect_expiry_invalid'      => "D' eihgéwerne Dauer is ungütig",
'protect_expiry_old'          => "D' Sperrzeid liegt in da Vagångenheit.",
'protect-text'                => "Då kãnnst nåchschau und ändan, wia d'Seitn „$1“ gschützt is.",
'protect-locked-access'       => "Dai Benytzerkonto vafiagt ned ywer de notwending Rechtt zur Endarung vom Saitenschutz. Do san de aktuöin Saitenschutzaistöiungen vo da Saiten '''„$1“:'''",
'protect-cascadeon'           => 'De Saiten is geengwärtig a Tail vo ner Kaskadenperrn. Sii is in de {{PLURAL:$1|folgende Seite|foiganden Saiten}} aibunden, de durch de Kaskadensperropzion geschytzt {{PLURAL:$1|ist|san}}. Der Saitenschutzstaatus vo derer Saiten kå gändert wern, des hod ower koan Aifluss auf de Kaskadensperrn:',
'protect-default'             => 'Olle Benutzer',
'protect-fallback'            => "D'„$1“-Berechtigung is notwendig.",
'protect-level-autoconfirmed' => 'Sperrung fyr néd registrirde Benutzer',
'protect-level-sysop'         => 'Netter Administraatorn',
'protect-summary-cascade'     => 'kaskadiarnd',
'protect-expiring'            => 'bis zum $2 um $3 Uhr (UTC)',
'protect-cascade'             => 'Kaskadiarade Sperr – ålle Voalång, de in dea Seitn eibundn han, wean emfåis gspead.',
'protect-cantedit'            => "Du kãnnst de Spea vu dea Seitn ned ändan, weu'st dafia ned de passnde Berechtigung håst.",
'restriction-type'            => 'Schutzstaatus:',
'restriction-level'           => 'Schutzheechen:',
'minimum-size'                => 'Mindestgress',

# Restrictions (nouns)
'restriction-move' => 'vaschiam',

# Undelete
'undelete'               => 'Gléschde Seiten weiderherstön',
'undeletehistorynoadmin' => 'De Seitn is glöscht worn. Da Grund fia de Löschung is in da Zsammafassung oogem,
genau wia Details zum letztn Benutza der de Seitn vor da Löschung bearbat håt.
Da aktuelle Text vo da glöschtn Seitn is nur fia Administratoren zum seng.',
'undeletebtn'            => 'Wiederherstön',
'undeletelink'           => 'åschauh / wiaderherstön',
'undeleteviewlink'       => 'åschaung',
'undeletereset'          => 'Zrucksétzen',
'undeletedfiles'         => '$1 {{plural:$1|Datei|Dateien}} san wieda hergstellt worn',
'undelete-search-box'    => 'Suach noch gléschde Seiten',
'undelete-search-submit' => 'Suach',

# Namespace form on various pages
'namespace'      => 'Nåmensraum:',
'invert'         => 'Auswoi umdraan',
'blanknamespace' => '(Seiten)',

# Contributions
'contributions'       => 'Benutzerbeiträg',
'contributions-title' => 'Benutzerbeiträg voh „$1“',
'mycontris'           => 'Eigerne Beitrég',
'contribsub2'         => 'Fyr $1 ($2)',
'uctop'               => '(aktuö)',
'month'               => 'und Monad',
'year'                => 'bis zum Jor:',

'sp-contributions-newbies'     => "Netter dé Beitrég voh d' neichen Benutzer åzoang",
'sp-contributions-newbies-sub' => 'Fyr Neiling',
'sp-contributions-blocklog'    => 'Sperrlogbiaché',
'sp-contributions-deleted'     => 'Gléschde Beitrég',
'sp-contributions-uploads'     => 'Aufféglooderne Daatein',
'sp-contributions-logs'        => 'Logbiacher',
'sp-contributions-talk'        => 'Diskussión',
'sp-contributions-search'      => 'Suach noch Benutzerbeitrég',
'sp-contributions-username'    => 'IP-Adress óder Benutzernåm:',
'sp-contributions-toponly'     => 'Netter aktuelle Versiónen zoang',
'sp-contributions-submit'      => 'Suachen',

# What links here
'whatlinkshere'            => 'Links auf dé Seiten',
'whatlinkshere-title'      => 'Seiten, dé noch „$1“ valinken',
'whatlinkshere-page'       => 'Seiten:',
'linkshere'                => "D' vóigernden Seiten valinken noch '''„[[:$1]]“''':",
'nolinkshere'              => "Koane Seiten valinkt zua '''„[[:$1]]“'''.",
'isredirect'               => 'Weiderloatungsseiten',
'istemplate'               => 'Vurlongeihbindung',
'isimage'                  => 'Daateilink',
'whatlinkshere-prev'       => "{{PLURAL:$1|vorige|d'voring $1}}",
'whatlinkshere-next'       => "{{PLURAL:$1|nexde|d'nexdn $1}}",
'whatlinkshere-links'      => '← Vaweise',
'whatlinkshere-hideredirs' => 'Weidaleitungen $1',
'whatlinkshere-hidetrans'  => 'Vurlongeihbindung $1',
'whatlinkshere-hidelinks'  => 'Links $1',
'whatlinkshere-hideimages' => 'Daateilinks $1',
'whatlinkshere-filters'    => 'Füter',

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
'ipbreason-dropdown'       => '* Oigmoahne Sperrgrynd
** Eihfyng voh voische Informaziónen
** Laarn voh Seiten
** Massenweiss Eihfyng voh externe Links
** Eihstön voh unsinnige Inhoite auf Seiten
** néd åbrochts Vahoiden
** Missbrauch mid mererne Benutzerkontós
** néd geigneter Benutzernåm',
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
'unblocklink'              => 'freigeem',
'change-blocklink'         => 'Sperr ändern',
'contribslink'             => 'Beitrég',
'autoblocker'              => "Automatische Sperre, weil s' a gmeinsame IP-Adressn mit „$1“ hernehma. Grund: „$2“.",
'blocklogpage'             => 'Benutzersperrlogbiaché',
'blocklogentry'            => 'hod „[[$1]]“ fyrn fóigenden Zeidraum gsperrd: $2; $3',
'unblocklogentry'          => "håd d'Spear vu „$1“ aufghom",
'block-log-flags-anononly' => 'netta Anonyme',
'block-log-flags-nocreate' => "'s erstön voh Benutzerkontós is gsperrd",
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
'movelogpage'            => 'Vaschiawungs-Logbiaché',
'movereason'             => 'Grund:',
'revertmove'             => 'zruck vaschiam',
'delete_and_move'        => 'Löschn und vaschiam',
'delete_and_move_reason' => 'glöscht, um Plåtz fia Vaschiam zum macha',
'selfmove'               => 'Ursprungs- und Zielname sand gleich; a Seitn kann net auf sich selber verschom wern.',

# Export
'export' => 'Seiten exportirn',

# Namespace 8 related
'allmessagesname'           => 'Nåm:',
'allmessagesdefault'        => 'Standardtext',
'allmessagescurrent'        => 'Aktuella Text',
'allmessagestext'           => 'Des is a Listen vo de MediaWiki-Systemtextt.
Bsuach bittschee de Saiten [http://www.mediawiki.org/wiki/Localisation MediaWiki-Lokalisiarung] und [http://translatewiki.net translatewiki.net], wånn du de ån da Lokalisiarung vo MediaWiki betailing mechadst.',
'allmessagesnotsupportedDB' => "'''Special:Allmessages''' is im Moment net möglich, wei de Datenbank offline is.",

# Thumbnails
'thumbnail-more'  => 'vagreessern',
'thumbnail_error' => 'Feeler beim Erstön vom Vurschaubüd: $1',

# Special:Import
'importnotext' => 'Laar oder koa Text',

# Import log
'importlogpage' => 'Import-Logbuach',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'Deih Benutzerseiten',
'tooltip-pt-mytalk'               => 'Deih Diskussiónsseiten',
'tooltip-pt-preferences'          => 'Eigerne Eihstellungen',
'tooltip-pt-watchlist'            => "Listen voh d' Seiten, dést beówochst",
'tooltip-pt-mycontris'            => 'Listen voh dé eigernen Beiträg',
'tooltip-pt-login'                => 'Das ma sé ånmödt, werd zwor gern gseeng, is ower koah Pflicht néd.',
'tooltip-pt-logout'               => 'Obmöden',
'tooltip-ca-talk'                 => 'Diskussión zum Seiteninhoid',
'tooltip-ca-edit'                 => "Seiten beorweiden. Bittscheh vurm Speichern d' Vurschaufunkzión brauchen",
'tooltip-ca-addsection'           => 'Neichen Obschnit åfånger',
'tooltip-ca-viewsource'           => 'Dé Seiten is gschytzd. Da Quötext kå ower ågschaud wern.',
'tooltip-ca-history'              => 'Friarerne Versiónen voh derer Seiten',
'tooltip-ca-protect'              => 'Seiten schytzen',
'tooltip-ca-delete'               => 'De Seitn löschen',
'tooltip-ca-move'                 => 'Dé Seiten vaschiam',
'tooltip-ca-watch'                => 'Dé Seiten zua persénlichen Beówochtungslisten dazua doah',
'tooltip-ca-unwatch'              => 'Dé Seiten voh da persénlichen Beówochtungslisten entferner',
'tooltip-search'                  => '{{SITENAME}} durchsuachen',
'tooltip-search-go'               => 'Gee direkt zua derer Seiten, dé exakd am eihgeewernen Nåm entspricht.',
'tooltip-search-fulltext'         => 'Suach noch Seiten, dé dén Text enthoiden',
'tooltip-p-logo'                  => 'Hauptseiten',
'tooltip-n-mainpage'              => 'Hauptseiten åzoang',
'tooltip-n-mainpage-description'  => 'Hauptseiten bsuachen',
'tooltip-n-portal'                => "Ywers Portoi, wos d' mochen kåst, wó eppers z' finden is",
'tooltip-n-currentevents'         => 'Hintergrundinformaziónen ywer akutelle Ereigniss',
'tooltip-n-recentchanges'         => 'Listen voh dé létzden Änderrungen auf {{SITENAME}}',
'tooltip-n-randompage'            => 'Zuaföige Seiten',
'tooltip-n-help'                  => 'Hüfeseiten åzoang',
'tooltip-t-whatlinkshere'         => 'Listen voh olle Seiten, dé do her zoang',
'tooltip-t-recentchangeslinked'   => "D' létzden Änderrungen auf dé Seiten, dé voh do valinkt san",
'tooltip-feed-rss'                => 'RSS-Feed vo derer Saiten',
'tooltip-feed-atom'               => 'Atom-Feed vo derer Saiten',
'tooltip-t-contributions'         => "D' Listen voh d' Beiträg voh dém Benutzer åschauh",
'tooltip-t-emailuser'             => 'Dém Benutzer a E-Post schicken',
'tooltip-t-upload'                => 'Daatein aufféloon',
'tooltip-t-specialpages'          => 'Listen voh olle Speziaalseiten',
'tooltip-t-print'                 => 'Druckåsicht voh derer Seiten',
'tooltip-t-permalink'             => 'Dauerhofter Link zua derer Seitenversión',
'tooltip-ca-nstab-main'           => 'Seiteninhoid åzoang',
'tooltip-ca-nstab-user'           => 'Benutzerseiten åzoang',
'tooltip-ca-nstab-media'          => 'Meediendaateiseiten åzoang',
'tooltip-ca-nstab-special'        => 'Dés is a Speziaalseiten dést néd beorweiden kåst.',
'tooltip-ca-nstab-project'        => 'Portoiseiten åzoang',
'tooltip-ca-nstab-image'          => 'Daateiseiten åzoang',
'tooltip-ca-nstab-mediawiki'      => 'MediaWiki-Systémtext åzoang',
'tooltip-ca-nstab-template'       => 'Vurlog åzoang',
'tooltip-ca-nstab-help'           => 'Huifseitn oozoang',
'tooltip-ca-nstab-category'       => 'Kategorieseiten åzoang',
'tooltip-minoredit'               => 'Dé Änderrung ois a kloane markirn.',
'tooltip-save'                    => 'Änderrungen speichern',
'tooltip-preview'                 => 'A Vurschau voh dé Änderrungen an derer Seiten. Bittscheh vurm Speichern bnutzen!',
'tooltip-diff'                    => 'Änderrungen am Text zoang',
'tooltip-compareselectedversions' => 'Unterschiade zwischen zwoa ausgwöde Versiónen voh derer  Seiten vagleichen.',
'tooltip-watch'                   => 'Dé Seiten zua persénlichen Beówochtungslisten dazua doah',
'tooltip-recreate'                => 'Seitn nei erstelln, obwoi sie glöscht worn is.',
'tooltip-rollback'                => 'Sétzd olle Beiträg, dé vom gleichen Benutzer gmocht worn san, mid am oanzing Klick auf dé Versión zruck, dé aktuö gwén is, bevur der oane zum werkeln ågfånger hod.',
'tooltip-undo'                    => 'Mocht netter dé oane Änderrung ryckgängég und zoagts Resuitot in da Vurschau å, damid in da Zåmmfossungszeiln a Begryndung ågeem wern kå.',
'tooltip-summary'                 => 'Gib a kurze Zåmmfossung eih',

# Attribution
'lastmodifiedatby' => 'Dé Seiten is zletzt am $1 um $2 voh $3 gänderd worn.',
'othercontribs'    => 'Basiard auf da Orweid voh $1',
'creditspage'      => 'Seiteninformaziónen',

# Patrolling
'markedaspatrollederrortext' => 'Du muasst a Seitenänderrung auswön',

# Image deletion
'deletedrevision'    => 'Oide Version $1 glöscht.',
'filedelete-missing' => 'De Datei „$1“ ko net glöscht wern, weils es net gibt.',

# Browsing diffs
'previousdiff' => '← Zum vorigen Versionsunterschied',
'nextdiff'     => 'zum nextn Untaschied in de Veasionen →',

# Media information
'file-info-size' => '$1 × $2 Pixel, Daateigreess: $3, MIME-Typ: $4',
'file-nohires'   => 'Es gibt koah heecherne Auflésung.',
'svg-long-desc'  => 'SVG-Datei, Basisgreß: $1 × $2 Pixl, Dateigreß: $3',
'show-big-image' => 'Versión in heecherner Auflésung',

# Special:NewFiles
'newimages'         => 'Neie Dateien',
'newimages-summary' => 'De Spezialseitn zoagt de zletzt hochgeladena Buidl und Dateien o.',
'noimages'          => 'Koane Datein gfunden.',
'ilsubmit'          => 'Suach',

# Bad image list
'bad_image_list' => "Formaat:

Netter Zeun, dé mid am * åfångern, wern ausgwertt. Ois ersters noch 'm * muass a Link auf a unerwynschde Daatei steh.
Dodrauf fóigende Links auf Seiten in da söm Zeun definirn Ausnåmen, in dénen eanern Zåmmenhång dé Daatei trótzdém vawendt wern derf.",

# Metadata
'metadata'          => 'Metadaaten',
'metadata-help'     => 'Dé Daatei enthoit weiderne Informaziónen, dé in da Reegel voh da Digitoikammera óder am vawenderden Scanner ståmmern. Durch a noochträgliche Beorweidung voh da Originoidaatei kennern oanige Deteils vaänderd worn seih.',
'metadata-expand'   => 'Erweitate Deteus eiblendn',
'metadata-collapse' => "D' erweiterden Details eihblenden",
'metadata-fields'   => "D' fóigernden Föder voh dé EXIF-Metadaaten, dé in dém MediaWiki-Systémtext ågeem san, wern auf Büdelbschreiwungsseiten mid eihkloppter Metadaatentabön åzoagt.
Weiderne wern standardmässig néd åzoagt.
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
* gpsaltitude",

# EXIF tags
'exif-gpsspeed' => 'Gschwindigkeid vom GPS-Empfänger',

'exif-componentsconfiguration-0' => 'Gibts néd',

# External editor support
'edit-externally'      => 'Dé Daatei mid am externen Prógramm beorweiden',
'edit-externally-help' => '(Schaug unter [http://www.mediawiki.org/wiki/Manual:External_editors Installaziónsåweisungen] fyr weiderne Informaziónen)',

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => 'olle',
'namespacesall' => 'olle',
'monthsall'     => 'olle',

# E-mail address confirmation
'confirmemail'           => 'E-Post-Adressn bestäting (Authentifiziarung)',
'confirmemail_noemail'   => 'Du håst ka güitige E-Post-Adressn in deine [[Special:Preferences|persönlichn Eistellungen]] eitrång.',
'confirmemail_send'      => 'Bestätigungscode zuaschicka',
'confirmemail_needlogin' => 'Sie miassn si $1 um Eana E-Mail-Adress zum bestätigen.',

# Multipage image navigation
'imgmultipageprev' => '← vorige Seitn',
'imgmultipagenext' => 'nächste Seitn →',

# Table pager
'ascending_abbrev'         => 'auf',
'descending_abbrev'        => 'ob',
'table_pager_next'         => 'Naxde Seiten',
'table_pager_prev'         => 'Vurherige Seiten',
'table_pager_first'        => 'Erste Seiten',
'table_pager_last'         => 'Letzde Seiten',
'table_pager_limit'        => 'Zoag $1 Eihträg pró Seiten',
'table_pager_limit_label'  => 'Pósiziónen pró Seiten:',
'table_pager_limit_submit' => 'Lós',
'table_pager_empty'        => 'Koane Ergeebniss',

# Auto-summaries
'autosumm-blank'   => 'Dé Seiten is glaard worn.',
'autosumm-replace' => "Da Seiteninhoid is durch an åndern Text ersétzt worn: '$1'",
'autoredircomment' => 'Weiderloatung noch [[$1]] is erstöd worn',
'autosumm-new'     => 'Dé Seiten is neich åglégt worn: $1',

# Size units
'size-bytes' => '$1 Bytes',

# Live preview
'livepreview-loading' => 'Loon ...',
'livepreview-ready'   => 'Loon … Ferdig!',
'livepreview-failed'  => "Dé sófurtige Vurschau is néd méglich!
Bittscheh d' noraale Vurschau bnutzen.",

# Watchlist editor
'watchlistedit-noitems'        => 'Dei Beobachtungslistn is laar.',
'watchlistedit-normal-title'   => 'Beobachtungslistn bearbatn',
'watchlistedit-normal-legend'  => 'Eiträge vo da Beobachtungslistn wegnehma',
'watchlistedit-normal-explain' => "Des san de Eiträg vo deiner Beowochtungslisten. Um de Eiträg z' entferna, markir de Kastln neem de Eiträg und druck am End vo da Seiten auf „{{int:Watchlistedit-normal-submit}}“. Du kåst dei Beowochtungslisten aa im  [[Special:EditWatchlist/raw|Listenformat beorweiten]].",
'watchlistedit-normal-submit'  => 'Eiträge wegnehma',
'watchlistedit-raw-title'      => 'Beówochtungslisten im Listenformaat beorweiten',
'watchlistedit-raw-legend'     => 'Beówochtungslisten im Listenformaat beorweiten',
'watchlistedit-raw-titles'     => 'Eihträg:',
'watchlistedit-raw-submit'     => 'Beówochtungslisten aktualisirn',

# Watchlist editing tools
'watchlisttools-view' => 'Ändarungen vafoing',
'watchlisttools-edit' => 'normal beorwaten',
'watchlisttools-raw'  => 'Im Listenformat beorweiden',

# Core parser functions
'duplicate-defaultsort' => 'Ówocht: Da Sortiarungsschlyssel "$2" ywerschreibt dén vurher vawendten Schlyssel "$1".',

# Special:Version
'version'                   => 'Versión',
'version-extensions'        => 'Installirde Daweiterrungen',
'version-hook-subscribedby' => 'Aufruf vo',
'version-license'           => 'Lizenz',
'version-poweredby-others'  => 'åndre',

# Special:FileDuplicateSearch
'fileduplicatesearch'          => 'Dóppéde Daatein suachen',
'fileduplicatesearch-summary'  => 'Suach noch dóppéde Daatein auf da Baasis vo eanerm Hash-Wert.',
'fileduplicatesearch-legend'   => 'Suach noch Duplikaate',
'fileduplicatesearch-filename' => 'Daateinåm:',
'fileduplicatesearch-submit'   => 'Suachen',
'fileduplicatesearch-info'     => '$1 × $2 Pixel<br />Daateigress: $3<br />MIME-Typ: $4',
'fileduplicatesearch-result-1' => 'Dé Daatei „$1“ hod koane identischen Duplikaate.',

# Special:SpecialPages
'specialpages'                   => 'Speziaalseiten',
'specialpages-note'              => '----
* Reguläre Speziaalseiten
* <span class="mw-specialpagerestricted">Zuagrifsbschränkde Speziaalseiten</span>
* <span class="mw-specialpagecached">Cachegenerrirde Speziaalseiten</span>',
'specialpages-group-maintenance' => 'Wartungslisten',
'specialpages-group-other'       => 'Åndre Speziaalseiten',
'specialpages-group-login'       => 'Åmöden',
'specialpages-group-changes'     => 'Létzde Änderrungen und Logbiacher',
'specialpages-group-media'       => 'Meedien',
'specialpages-group-users'       => 'Benutzer und Rechtt',
'specialpages-group-highuse'     => 'Haiffig brauchde Seiten',
'specialpages-group-pages'       => 'Listen voh Seiten',
'specialpages-group-pagetools'   => 'Seitenwerkzeig',
'specialpages-group-wiki'        => 'Systémdaaten und Werzeig',
'specialpages-group-redirects'   => 'Weiderloattende Speziaalseiten',
'specialpages-group-spam'        => 'Spam-Werkzeig',

# Special:BlankPage
'blankpage'              => 'Laare Seiten',
'intentionallyblankpage' => 'Dé Seiten is obsichtlich óne an Inhoid. Sie werd fyr Benchmarks vawendt',

# External image whitelist
'external_image_whitelist' => "#Dé Zeiln néd vaändern<pre>
#Unterhoib kennern Fragmentt voh reeguläre Ausdryck (da Teil zwischen de //) eihgeem wern.
#Dé wern mid d' URL voh Büder aus externe Quön vaglichen
#A pósitiaver Vagleich fyrd zur da Åzoag vom Büd, sunst werds Büdel netter ois Link åzoagt
#Zeiln, dé mid am # åfångern, wern ois Kommentar bhåndelt
#Es werd néd zwischen da Gróss- und Kloahschreiwung unterschian

#Fragmentt vo reeguläre Ausdryck noch derer Zeiln eihtrong. Dé Zeiln néd vaändern</pre>",

# Special:Tags
'tags'                    => 'Gütige Änderrungsmarkiarunen',
'tag-filter'              => '[[Special:Tags|Markiarungs]]-Füter:',
'tag-filter-submit'       => 'Füter',
'tags-title'              => 'Markiarungen',
'tags-intro'              => 'Dé Seiten zoagt olle Markiarungen, dé fyr Beorweidungen vawendt wern, sówia dé Bedeitung voh dé.',
'tags-tag'                => 'Markiarungsnåm',
'tags-display-header'     => "Bnénnung auf d' Änderrungslisten",
'tags-description-header' => 'Vóiständige Bschreiwung',
'tags-hitcount-header'    => 'Markirde Änderrungen',
'tags-edit'               => 'werkeln',
'tags-hitcount'           => '$1 {{PLURAL:$1|Änderrung|Änderrungen}}',

# Special:ComparePages
'comparepages'                => 'Seiten vagleichen',
'compare-selector'            => 'Seitenversiónen vagleichen',
'compare-page1'               => 'Seiten 1',
'compare-page2'               => 'Seiten 2',
'compare-rev1'                => 'Versión 1',
'compare-rev2'                => 'Versión 2',
'compare-submit'              => 'Vagleichen',
'compare-invalid-title'       => 'Da ågeewerne Seitennåm is néd gütig',
'compare-title-not-exists'    => 'Da ågeewerne Seitennåm is néd vurhånden',
'compare-revision-not-exists' => 'Dé ågeewerne Versión is néd vurhånden',

# Database error messages
'dberr-header'    => 'Dés Wiki hod a Próblém',
'dberr-problems'  => 'Tschuidigung. Dé Seiten hod im Moment technische Próbléme.',
'dberr-again'     => "Wort a por Minuten und vasuachs dånn neich z' loon.",
'dberr-info'      => '(Kå koah Vabindung zum Daatenbånkserver herstön: $1)',
'dberr-usegoogle' => 'Du kunntersd dawei mid Google suachen.',
'dberr-outofdate' => 'Beochtt, daas da Suachindex voh inserne Inhoitt bei Google vaoiterd seih kunnt.',

# HTML forms
'htmlform-submit'              => 'Speichern',
'htmlform-reset'               => 'Änderrungen ryckgängég mochen',
'htmlform-selectorother-other' => 'Åndre',

);
