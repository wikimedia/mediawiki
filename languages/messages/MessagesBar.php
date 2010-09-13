<?php
/** Bavarian (Boarisch)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Malafaya
 * @author Man77
 * @author Merlissimo
 * @author Metalhead64
 * @author Mucalexx
 * @author The Evil IP address
 * @author Wikifan
 * @author bar.wikipedia.org administrators
 */

$fallback = 'de';

$messages = array(
# User preference toggles
'tog-underline'              => 'Links untastreicha:',
'tog-highlightbroken'        => 'Links auf leere Seiten hervorhem',
'tog-justify'                => 'Text ois Blocksatz',
'tog-hideminor'              => 'Kloane Änderunga ausblendn',
'tog-hidepatrolled'          => 'Kontrolliade Ändarungen in de „Letztn Ändarungen“ ausblendn',
'tog-newpageshidepatrolled'  => 'Kontrolliade Seitn auf da Listn „Neie Seitn“ vabeang',
'tog-extendwatchlist'        => 'Erweiterte Beobachtungslistn',
'tog-usenewrc'               => 'Eaweitade Dåastellung vu de letztn Ändarungen (JavaScript wiad braucht)',
'tog-numberheadings'         => 'Üwaschriftn automatisch nummarian',
'tog-showtoolbar'            => 'Bearbatn-Werkzeugleiste oozoang (JavaScript wiad braucht)',
'tog-editondblclick'         => 'Seitn mid am Doppiklick beåaweitn (JavaScript wiad braucht)',
'tog-editsection'            => 'Links zum Beåaweitn vu de anzlnen Åbschnitte ãnzoang',
'tog-watchcreations'         => 'Vu mia söiwa nei eigstöide Seitn automatisch beobåchtn',
'tog-watchdefault'           => 'Vu mia söiwa beåabeitete und vu mia nei eigstöide Seitn automatisch beobåchtn',
'tog-watchmoves'             => 'Vu mia söiwa vaschomne Seitn automatisch beobåchtn',
'tog-watchdeletion'          => 'Vu mia söiwa glöschte Seitn automatisch beobåchtn',
'tog-previewonfirst'         => "Beim erstn Beåawatn imma d'Voaschau ãnzoang",
'tog-nocache'                => 'Saitencache vom Browser deaktiviarn',
'tog-enotifwatchlistpages'   => 'Bei Ändarungen vu beobåchtete Seitn E-Post schicka',
'tog-enotifusertalkpages'    => 'Bei Ändarungen vu meina Benutza-Diskussionsseitn E-Post schicka',
'tog-enotifminoredits'       => 'Aa bei kloane Änderungen vu beobåchtete Seitn E-Post schicka',
'tog-enotifrevealaddr'       => 'Dei E-Post-Adressn wiad in da Benåchrichtigungs-E-Post ãnzoagt',
'tog-shownumberswatching'    => 'Ãnzåih vu de beobåchtndn Benutza ãnzoang',
'tog-fancysig'               => 'Signatua åis Wikitext behãndln (ohne automatische Valinkung)',
'tog-externaleditor'         => "An exteanen Editor åiss Standard benutzn (netta fia Expertn, es miaßn spezielle Einstellungen auf'm PC eigricht wean)",
'tog-showjumplinks'          => '„Wexln zu“-Links aktivian',
'tog-uselivepreview'         => 'Live-Voaschau nutzn (dafia braucht ma JavaScript) (experimentell)',
'tog-forceeditsummary'       => 'Warnen, wenn beim Speichern de Zusammenfassung feit',
'tog-watchlisthideown'       => 'Eigne Bearbatunga in dar Beobachtungslistn ausblendn',
'tog-watchlisthidebots'      => 'Bearbatunga durch Bots in da Beobachtungslistn ausblendn',
'tog-watchlisthideminor'     => 'Kloane Bearbatunga in da Beobachtungslistn ausblendn',
'tog-watchlisthidepatrolled' => 'Kontrolliade Ändarungen in da Beobåchtungslistn ausblendn',
'tog-ccmeonemails'           => 'Schick ma Kopien vu da E-Post, de i ãndare Benutza schick',
'tog-showhiddencats'         => 'Vasteckte Kategorien ãnzoang',

'underline-always'  => 'imma',
'underline-never'   => 'nia',
'underline-default' => 'åbhängig vu da Browser-Eistellung',

# Dates
'sunday'        => 'Sunntåg',
'monday'        => 'Montåg',
'tuesday'       => 'Ertig',
'wednesday'     => 'Mittwoch',
'thursday'      => 'Pfinstig',
'friday'        => 'Freitåg',
'saturday'      => 'Sãmståg',
'sun'           => 'Su',
'mon'           => 'Mo',
'tue'           => 'Er',
'wed'           => 'Mi',
'thu'           => 'Pf',
'fri'           => 'Fr',
'sat'           => 'Sa',
'january'       => 'Jenner',
'february'      => 'Feewer',
'march'         => 'Merz',
'april'         => 'Aprüi',
'may_long'      => 'Mai',
'june'          => 'Juni',
'july'          => 'Juli',
'august'        => 'August',
'september'     => 'September',
'october'       => 'Oktower',
'november'      => 'November',
'december'      => 'Dezember',
'january-gen'   => 'Jänner',
'february-gen'  => 'Februa',
'march-gen'     => 'Mäaz',
'april-gen'     => 'Aprüi',
'may-gen'       => 'Mai',
'june-gen'      => 'vom Juni',
'july-gen'      => 'vom Juli',
'august-gen'    => 'vom August',
'september-gen' => 'vom September',
'october-gen'   => 'vom Oktower',
'november-gen'  => 'vom November',
'december-gen'  => 'vom Dezember',
'jan'           => 'Jen.',
'feb'           => 'Few.',
'mar'           => 'Mer.',
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
'pagecategories'                 => '{{PLURAL:$1|Kategorie|Kategoriin}}',
'category_header'                => 'Seitn in da Kategorie „$1“',
'subcategories'                  => 'Untakategorien',
'category-media-header'          => 'Medien in da Kategorie „$1“',
'category-empty'                 => "''De Kategorie enthåit momentan kane Seitn und kane Medien ned.''",
'hidden-categories'              => '{{PLURAL:$1|Vasteckte Kategorie|Vasteckte Kategorien}}',
'hidden-category-category'       => 'Vasteckte Kategorie',
'category-subcat-count'          => "{{PLURAL:$2|De Kategorie enthåit netta de foignde Untakategorie:|{{PLURAL:$1|D'foignde Untakategorie is ane vu insgsamt $2 Untakategorien in dea Kategorie:|Vu insgsamt $2 Untakategorien in dea Kategorie wean $1 ãzoagt:}}}}",
'category-subcat-count-limited'  => 'In de Kategorie {{PLURAL:$1|is de foignde Untakategorie|san de foigndn Untakategorien}} eisoatiad:',
'category-article-count'         => '{{PLURAL:$2|De Kategorii enthoit foigande Saitn:|{{PLURAL:$1|Foigande Saitn is aane vo insgsaumt $2 Saitn in derer Kategorii:|Es wern $1 vo insgsaumt $2 Saitn in derer Kategorii augzaagt:}}}}',
'category-article-count-limited' => 'De {{PLURAL:$1|foignde Seitn is|foigndn $1 Seitn san}} in dea Kategorie enthåitn:',
'category-file-count-limited'    => "{{PLURAL:$1|D'foignde Datei is|De foigndn $1 Datein san}} in de Kategorie eisoatiad:",
'listingcontinuesabbrev'         => '(Foatsetzung)',
'index-category'                 => 'Indiziade Seitn',
'noindex-category'               => 'Ned indiziade Seitn',

'mainpagetext'      => "'''MediaWiki is eafoigreich installiad woan.'''",
'mainpagedocfooter' => 'A Huif zua Benutzung und Konfiguration vo da Wiki-Software findst im [http://meta.wikimedia.org/wiki/Help:Contents Benutzerhandbuch].
* [https://lists.wikimedia.org/mailman/listinfo/mediawiki-announce Mailingliste neuer MediaWiki-Versionen]',

'about'         => 'Üba',
'article'       => 'Artikl',
'newwindow'     => '(wiad in am neichn Fensta aufgmåcht)',
'cancel'        => 'Obbrecha',
'moredotdotdot' => 'Mehra …',
'mypage'        => 'Eigne Seitn',
'mytalk'        => 'Aigane Diskussion',
'anontalk'      => 'Diskussionsseitn vo dera IP',
'navigation'    => 'Navigazion',

# Cologne Blue skin
'qbfind'         => 'Findn',
'qbedit'         => 'werkln',
'qbmyoptions'    => 'Meine Seitn',
'qbspecialpages' => 'Spezialseitn',

# Vector skin
'vector-action-delete'    => 'Leschen',
'vector-action-move'      => 'Vaschiam',
'vector-action-protect'   => 'Schytzen',
'vector-action-undelete'  => 'Wiedaheastöin',
'vector-action-unprotect' => 'Freigem',
'namespaces'              => 'Nãmensräim',

'errorpagetitle'    => 'Fehla',
'returnto'          => 'Zruck zur Seitn $1.',
'tagline'           => 'Aus {{SITENAME}}',
'help'              => 'Fragen?',
'search'            => 'Suach',
'searchbutton'      => 'Suach',
'searcharticle'     => 'Artike',
'history'           => 'Versionen',
'history_short'     => 'Versionen/Autorn',
'updatedmarker'     => '(gändat)',
'info_short'        => 'Informazion',
'printableversion'  => 'Version zum Ausdrucka',
'permalink'         => 'Permanenter Link',
'print'             => 'Druckn',
'edit'              => 'werkln',
'create'            => 'Erstöin',
'editthispage'      => 'Seitn beårbeitn',
'create-this-page'  => 'Seitn eastöin',
'delete'            => 'löschn',
'deletethispage'    => 'De Seitn löschn',
'undelete_short'    => '{{PLURAL:$1|1 Version|$1 Versionen}} wiedaheastöin',
'protect'           => 'Schützn',
'protect_change'    => 'endern',
'protectthispage'   => 'Seitn schützn',
'unprotect'         => 'freigem',
'unprotectthispage' => 'Schutz aufhem',
'newpage'           => 'Neiche Seitn',
'talkpage'          => 'De Saiten bsprecha',
'talkpagelinktext'  => 'bsprecha',
'specialpage'       => 'Speziaalsaiten',
'personaltools'     => 'Persenliche Werkzaige',
'postcomment'       => 'Naicher Obschnit',
'articlepage'       => 'Artike',
'talk'              => 'bschprecha',
'views'             => 'Åsichten',
'toolbox'           => 'Werkzaigkisten',
'userpage'          => 'Benytzersaiten',
'projectpage'       => 'Projektsaiten åzoang',
'imagepage'         => 'Dataisaiten åzoang',
'mediawikipage'     => 'Inhoitssaiten åzoang',
'templatepage'      => 'Vurlongsaiten åzoang',
'viewhelppage'      => 'Hüifsaiten åzoang',
'categorypage'      => 'Kategoriisaiten åzoang',
'viewtalkpage'      => 'Diskussion',
'otherlanguages'    => 'Ånderne Sproochen',
'redirectedfrom'    => '(vo $1 waider gschickt)',
'redirectpagesub'   => 'Waiderloatung',
'lastmodifiedat'    => 'De Saiten is zletzt am $1 um $2 gändert worn.',
'jumpto'            => 'Wexln zua:',
'jumptonavigation'  => 'Navigazion',
'jumptosearch'      => 'Suach',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'Ywer {{SITENAME}}',
'aboutpage'            => 'Project:Ywer_{{SITENAME}}',
'copyright'            => 'Da Inhoit is unter da $1 vafiagbor.',
'copyrightpage'        => '{{ns:project}}:Uahebarechte',
'disclaimers'          => 'Herkumftsågob/Impressum',
'disclaimerpage'       => 'Project:Herkumftsågob/Impressum',
'edithelp'             => 'Beorwaitungshüifm',
'edithelppage'         => 'Help:Beorwaitungshüifm',
'helppage'             => 'Help:Inhoitsvazaichnis',
'mainpage'             => 'Hauptsaiten',
'mainpage-description' => 'Hauptsaiten',
'policy-url'           => 'Project:Richtlinien',
'privacy'              => 'Datnschutz',
'privacypage'          => 'Project:Daatenschutz',

'badaccess' => 'Koane ausreichenden Rechte',

'ok'                      => 'haut hi',
'retrievedfrom'           => 'Vu „$1“',
'youhavenewmessages'      => 'Du host $1 ($2).',
'newmessageslink'         => 'naiche Nochrichten',
'newmessagesdifflink'     => 'naiche Nochrichten',
'youhavenewmessagesmulti' => 'Du host naiche Nochrichten: $1',
'editsection'             => 'werkln',
'editold'                 => 'werkln',
'viewsourceold'           => 'Quöitext åzoang',
'editlink'                => 'werkln',
'viewsourcelink'          => 'an Quöitext åschaun',
'editsectionhint'         => 'Obschnit beorwaiten: $1',
'toc'                     => 'Inhoitsvazaichnis',
'showtoc'                 => 'Åzoang',
'hidetoc'                 => 'vastecka',
'thisisdeleted'           => '$1 åschaun oder wiiderherstöin?',
'viewdeleted'             => '$1 åzoang?',
'restorelink'             => '$1 gleschte {{PLURAL:$1|Version|Versionen}}',
'feedlinks'               => 'Feed:',
'feed-invalid'            => 'Ungüitiger Feed-Abonnement-Typ.',
'site-rss-feed'           => 'RSS-Feed fyr $1',
'site-atom-feed'          => 'Atom-Feed fia $1',
'page-rss-feed'           => 'RSS-Feed fyr „$1“',
'page-atom-feed'          => 'Atom-Feed fyr „$1“',
'red-link-title'          => '$1 (de Saiten gibts ned)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Saiten',
'nstab-user'      => 'Benytzersaiten',
'nstab-special'   => 'Speziaalsaiten',
'nstab-project'   => 'Projektsaiten',
'nstab-image'     => 'Datai',
'nstab-mediawiki' => 'MediaWiki-Systemnochricht',
'nstab-template'  => 'Vurlog',
'nstab-help'      => 'Hüifesaiten',
'nstab-category'  => 'Kategorii',

# Main script and global functions
'nosuchaction'      => 'De Akzion gibts ned',
'nosuchspecialpage' => 'De Speziaalsaiten gibts ned',

# General errors
'error'                => 'Feeler',
'databaseerror'        => 'Feeler in da Daatenbånk',
'dberrortextcl'        => 'Tschüdigung, do is a Föhla in da Datnbaunk.
Die lezde Aunfrog wor:
"$1"
vo da Funktsion "$2".
Fölanumma: $3 ($4)',
'laggedslavemode'      => "'''Ochtung:''' De åzoagte Saiten kunntad unter Umständ ned d' letzten Beorwaitungen enthoiden.",
'readonly'             => 'Daatenbånk gsperrt',
'enterlockreason'      => 'Bitte gib an Grund au, warum d Datnbaunk gschpert wern soi und wi laungs dauan wiad',
'readonlytext'         => 'De Datenbånk is vorywergeehend fyr Naiaiträg und Endarungen gsperrt. Bittschee vasuchs spaader no amoi.

Grund vo da Sperrung: $1',
'missing-article'      => 'Der Text vo „$1“ $2 is ned in da Daatenbank gfunden worn.

De Saiten is meglicherwais glescht oder vaschoom worn.

Fois des ned zuatrifft, host eventuöi an Feeler in da Software gfunden. Bittschee möid des am [[Special:ListUsers/sysop|Åmtmåå (Administrator)]] unter da Nennung vo da URL.',
'missingarticle-rev'   => '(Veasionsnumma: $1)',
'missingarticle-diff'  => '(Untaschiad zwischn Veasionen: $1, $2)',
'readonly_lag'         => 'Di Datnbaunk is momentan automatisch gschpert, damit si die Seava syncronisian kinan.',
'internalerror'        => 'Inteana Fehla',
'internalerror_info'   => 'Inteana Fehla: $1',
'fileappenderrorread'  => '"$1" kau werendn hinzufügn ned glesn wearn.',
'fileappenderror'      => 'De Datei „$1“ håd ned nåch „$2“ kopiad wean kina.',
'filecopyerror'        => 'De Datei „$1“ håd ned nåch „$2“ kopiad wean kina.',
'filerenameerror'      => 'De Datei „$1“ håd ned nåch „$2“ umbenaunt wean kina.',
'filedeleteerror'      => 'De Datei „$1“ håt net glöscht wern kinna.',
'directorycreateerror' => "S'Vazeichnis „$1“ håd ned ãnglegt wean kina.",
'filenotfound'         => 'De Datei „$1“ is net gfundn worn.',
'fileexistserror'      => "In'd Datei „$1“ håd ned gschrim wean kina, weu's de Datei nämli schãu gibt.",
'unexpected'           => 'Uneawåateta Weat: „$1“=„$2“.',
'formerror'            => 'Fehla: De Eingåm håm ned vaåawat wean kina.',
'badarticleerror'      => 'De Aktion kann net auf de Seitn ogwendt wern.',
'badtitle'             => 'Ungüitiga Titl',
'badtitletext'         => 'Da Titl vo dera oogfordertn Seitn is net gültig, laar oda a ungültigar Sprachlink vo an andern Wiki.',
'wrong_wfQuery_params' => 'Fåische Parameta fia wfQuery()<br />
Funkzion: $1<br />
Åbfråg: $2',
'viewsource'           => 'Quöitext åschaun',
'viewsourcefor'        => 'fia $1',
'actionthrottled'      => 'Akzionszåih limitiad',
'actionthrottledtext'  => "De Akzion kãu innahåib vu am kuazn Zeitåbstãnd netta begrenzt oft ausgfüahd wean. De Grenzn håst gråd erreicht. Bitte probia's in a poa Minutn nu amåi.",
'protectedpagetext'    => "De Seitn is fia s'Beåaweitn gspead.",
'viewsourcetext'       => 'Sie kinnan aba an Quelltext vo dera Seitn ooschaun und kopiern:',
'editinginterface'     => "'''Obacht:''' De Seitn enthoit von da MediaWiki-Software benutzten Text. Änderungen wirken si auf de Benutzeroberfläche aus.",
'titleprotected'       => "A Seitn mit dem Nama kann net ogelegt wern. De Sperre is durch [[User:$1|$1]] mit da Begründung ''„$2“'' eigericht worn.",

# Login and logout pages
'logouttext'                 => "'''Du bist jetzad abgmöidt.'''

Du kååst {{SITENAME}} jetzad anonym waidernytzn, oder di ernait unterm söim oder am åndern Benytzernåm [[Special:UserLogin|åmöidn]].
Beochtt ower, dass oanige Saitn no åzoang kennan, dass du ågmöidt bist, solång du ned dai Browsercache glaart host.",
'welcomecreation'            => '== Seavas, $1! ==

Dei Benutzakonto is gråd eigricht woan.
Vagiss bittschee ned, deine [[Special:Preferences|{{SITENAME}}-Eistellungen]] ãnzpassn',
'yourname'                   => 'Benytzernåm:',
'yourpassword'               => 'Posswort:',
'yourpasswordagain'          => 'Posswort no amoi',
'remembermypassword'         => 'Mid dém Browser dauerhoft ågmöidt blaim (maximaal $1 {{PLURAL:$1|Dog|Dog}})',
'yourdomainname'             => 'Eanerne Domain:',
'externaldberror'            => 'Entweder es ligt a Feeler bai da externen Authentifiziarung vur oder du derfst dai externs Benytzerkonto ned aktualisirn.',
'login'                      => 'Åmöiden',
'nav-login-createaccount'    => 'Åmöiden / Konto erstöin',
'loginprompt'                => 'Zur Åmöidung miassn Cookies aktiviard sai.',
'userlogin'                  => 'Åmöiden / Konto erstöin',
'userloginnocreate'          => 'Åmöiden',
'logout'                     => 'Obmöiden',
'userlogout'                 => 'Obmöiden',
'notloggedin'                => 'Ned ågmöidt',
'nologin'                    => "Du host koa Benytzerkonto? '''$1'''.",
'nologinlink'                => 'A naichs Benytzerkonto erstöin',
'createaccount'              => 'Benytzerkonto åleeng',
'gotaccount'                 => "Du hast hast scho a Benutzerkonto? '''$1'''.",
'gotaccountlink'             => 'Åmöiden',
'createaccountmail'          => 'per E-Mäil',
'createaccountreason'        => 'Grund',
'badretype'                  => 'De zwoa Posswerter stimman ned ywerai.',
'userexists'                 => 'Da Benytzernåm is scho vageem. Bittschee nimm an åndern her.',
'loginerror'                 => 'Feeler bai da Åmöidung',
'createaccounterror'         => 'Des Benytzerkonto hod ned erstöid wern kenna: $1',
'nocookiesnew'               => "Da Benytzerzuagång is erstöid worn, du bist ower ned ågmöidt. {{SITENAME}} benedigt fyr de Funkzion Cookies, bittschee aktiviar de und möidt de danoch mid daim naichn Benytzernåm und 'm dazuaghering Posswort å.",
'nocookieslogin'             => "{{SITENAME}} nimmt Cookies zum Eiloggen vo de Benutzer her. Sie ham Cookies deaktiviert, bittschee aktiviern Sie de und versuchan's nomoi.",
'loginsuccesstitle'          => "D' Åmöidung is erfoigraich gween",
'loginsuccess'               => 'Sie san  iatzat ois „$1“ bei {{SITENAME}} oogmeidt.',
'wrongpassword'              => "Des Passwort is falsch. Bitte probier's no amoi.",
'wrongpasswordempty'         => 'Des eigemne Passwort is laar gwen. Bitte no amoi probiern.',
'mailmypassword'             => 'Neis Passwort zuasendn',
'passwordremindertitle'      => 'Neis Passwort fia a {{SITENAME}}-Benutzerkonto',
'acct_creation_throttle_hit' => 'Du hosst scho $1 {{PLURAL:$1|Benutzakonto|Benutzakonten}} und konnst iatzat koane mehr oleng.',
'accountcreated'             => 'Benytzerkonto is erstöid worn',
'accountcreatedtext'         => "'s Benutzerkonto $1 is eigricht worn.",

# Password reset dialog
'oldpassword' => 'Oids Passwort:',
'newpassword' => 'Neis Passwort:',
'retypenew'   => 'Neis Passwort (no amoi):',

# Edit page toolbar
'bold_sample'     => 'Fetter Text',
'bold_tip'        => 'Fetter Text',
'italic_sample'   => 'Kuasiva Text',
'italic_tip'      => 'Kuasiva Text',
'link_sample'     => 'Link-Text',
'link_tip'        => 'Inteana Link',
'extlink_sample'  => 'http://www.example.com Link-Text',
'extlink_tip'     => "Exteana Link (pass auf's http:// auf)",
'headline_sample' => 'Ywerschrift auf da zwoaten Ewane',
'headline_tip'    => 'Üwaschrift auf da 2. Emn',
'math_sample'     => 'Foaml då eifüng',
'math_tip'        => 'Mathematische Foaml (LaTeX)',
'nowiki_sample'   => 'Ned foamatiadn Text då eifüng',
'nowiki_tip'      => 'Unfoamatiada Text',
'image_tip'       => 'Datailink',
'media_tip'       => 'Datei-Link',
'sig_tip'         => 'Dei Signatur mit Zeitstempe',
'hr_tip'          => 'Wåågrechte Linie (spåasãm vawendn)',

# Edit pages
'summary'                          => 'Zsammafassung',
'subject'                          => 'Bedreff',
'minoredit'                        => 'Netter Kloanigkaiten san vaendert worn',
'watchthis'                        => 'De Saiten beowochten',
'savearticle'                      => 'Saiten spaichern',
'preview'                          => 'Voaschau',
'showpreview'                      => 'Vorschau zoang',
'showdiff'                         => 'Ändarungen zoang',
'anoneditwarning'                  => "Du beåaweitst de Seitn, ohne dass'd ãgmöidt bist. Wãnn'st iatst speichast, dãun wiad dei aktuelle IP-Adressn in da Veasionsgschicht aufzeichnt und kãu damid unwidaruflich '''öffntlich''' eigseng wean.",
'missingsummary'                   => "'''Hinweis:''' Sie ham koa Zsammafassung oogem. Wenn S' wieda auf „Speichern“ klicken, werd Eana Änderung ohne Zsammafassung übanumma.",
'missingcommenttext'               => "Bitte gebn S' a Zsammafassung ei.",
'summary-preview'                  => 'Voaschau vu da Zsãmmafåssung:',
'subject-preview'                  => 'Vorschau vom Betreff',
'blockedtitle'                     => 'Benutzer is gesperrt',
'whitelistedittitle'               => 'Zum Beorwaiten muasst de åmöiden',
'whitelistedittext'                => 'Sie miaßn si $1, um Seiten bearbatn zum kinna.',
'loginreqtitle'                    => 'Es braucht a Oomeidung',
'loginreqlink'                     => 'oomeidn',
'loginreqpagetext'                 => 'Sie miaßn si $1, um Seitn lesen zum kinna.',
'accmailtitle'                     => 'Passwort is vaschickt worn',
'accmailtext'                      => 'Des Passwort fia „$1“ is an $2 gschickt worn.',
'newarticle'                       => '(Nei)',
'newarticletext'                   => "↓ Du bist am Link zua ner Saiten gfóigt, dé néd vurhånden is.
Das d' dé Saiten åléng kååst, trog dain Text a dé untensteehate Boxen ai (schau unter da [[{{MediaWiki:Helppage}}|Hüifssaiten]] fyr merer Informaziónen).
Bist du föischlicherwais dodan, dånn druck dé '''Zruck'''-Schoitflächen vo daim Browser.",
'anontalkpagetext'                 => "---- ''De Seitn werd dazu hergnumma, am net ogmeldten Benutzer Nachrichtn zum hinterlassen.
Wennst mit de Kommentare auf dera Seitn nix ofanga kåst, is vermutlich da friarare Inhaber vo dera IP-Adressn gmoant und du kånstas ignoriern.
Wennst a anonymer Benutzer bist und dengst, daß irrelevante Kommentare an di grichtet worn san, [[Special:UserLogin|meld di bitte o]], um zukünftig Verwirrung zum vermeiden. ''",
'noarticletext'                    => 'De Seitn enthåit zua Zeid kan Text ned.
Du kãnnst in Titl vu dea Seitn auf de ãndan Seitn [[Special:Search/{{PAGENAME}}|suacha]],
<span class="plainlinks"> in de dazuaghearadn [{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} Logbiache suachn] oda de Seitn [{{fullurl:{{FULLPAGENAME}}|action=edit}} beåabeitn]</span>.',
'updated'                          => '(Gändat)',
'previewnote'                      => "'''Des is netter a Vurschau, d' Saiten is nu ned gspaichert worn!'''",
'previewconflict'                  => "De Vorschau gibt an Inhalt vom obern Textfeld wieda; so werd de Seite ausschaun, wenn S' iatzat speichern.",
'session_fail_preview'             => 'Dei Bearbeitung is net gspeichert worn, wei deine Sitzungsdaten valorn ganga san.
Bitte versuachs no amoi, indem du unta da foigendn Textvorschau nochmois auf „Seitn speichern“ klickst.
Sollt des Problem bestehn bleim, [[Special:UserLogout|meld di ab]] und danach wieda oo.',
'editing'                          => 'Bearbatn vo $1',
'editingsection'                   => 'Werkln bei $1 (Åbschnitt)',
'editconflict'                     => 'Konflikt baim Beorwaiten: $1',
'explainconflict'                  => "Jemand anders hat de Seitn gändert, nachdem du oogfanga hast sie zum bearbatn.
Des obere Textfeld enthoit den aktuellen Stand.
Des untere Textfeld enthoit deine Änderungen.
Bitte füg deine Änderungen in des obere Textfeld ei.
'''Nur''' da Inhalt vom obern Textfeld werd gspeichert, wenn du auf „Seitn speichern“ klickst!",
'yourtext'                         => 'Eana Text',
'editingold'                       => "'''Achtung: Sie arbatn an a oidn Version vo dera Seit.'''
Wenn S' speichern, wern alle neiern Versionen übaschriem.",
'copyrightwarning'                 => "'''Bittschee kopiar koane Websaiten, dé néd daine oaganen san, benytz koane urhéwerrechtléch gschytzten Werke óne aner Dalaabnis vom Urhéwer!'''<br />

Du gibst uns dodamid dai Zuasog, dass du dén Text '''söiwer vafosst''' host, das da Text a Oigmoaguat '''(public domain)''' is, óder das da '''Urhéwer''' sai '''Zuastimmung''' geem hod. Fois der Text beraits irngdwó ånderst vaéffmtlécht worn is, moch bittschee an Hiwais in da Diskussiónssaitn.
<i>Bittschee beochtt, das olle {{SITENAME}}-Baitrég autómaatésch unter da „$2“ steengan (schau unter $1 fyr Details). Fois du néd mechst, das dai Orwait do vo ånderne vaéndert und vabroatt werd, dånn druck néd auf „Saiten spaichern“.</i>",
'longpagewarning'                  => "'''Warnung:''' De Seitn is $1 kB groaß; net jeda Browser konn Seitn bearbatn, di größer als 32 kB san.
Überlegen S' bitte, ob a Aufteilung vo da Seitn in kloanere Abschnitte möglich is.",
'semiprotectedpagewarning'         => "'''Hoibsperrung:''' De Seitn is so gsperrt worn, daß nur registrierte Benutzer de ändern kinnan.",
'titleprotectedwarning'            => "'''Ochtung: De Saitenerstöiung is aso gschytzt worn, das netter Benytzer mid [[Special:ListGroupRights|speziöie Rechte]] de Saiten erstöin kennan.'''
Zur Informazion foigt da aktuöie Logbuachaitrog:",
'templatesused'                    => "{{PLURAL:$1|D'foignde Voalåg wiad|D'foigndn Voalång wean}} auf dea Seitn vawendt:",
'templatesusedpreview'             => "{{PLURAL:$1|D'foignde Voalåg wiad|De foigndn Voalång wean}} in dea Seitn-Voaschau vawendt:",
'templatesusedsection'             => 'De foigendn Vorlagn wern von dem Abschnitt vawendt:',
'template-protected'               => '(schreibgschützt)',
'template-semiprotected'           => '(schreibgschützt fia ned ãngmöidte und neiche Benutza)',
'hiddencategories'                 => 'De Seitn is in {{PLURAL:$1|a vasteckte Kategorie|$1 vasteckte Kategorien}} eisoatiad:',
'nocreatetitle'                    => 'De Erstellung vo neie Seitn is eingeschränkt.',
'permissionserrorstext-withaction' => "Du håst de Berechtigung ned, dass'd $2.
{{PLURAL:$1|Grund|Gründ}}:",
'recreate-moveddeleted-warn'       => "'''Obacht: Du ladst aa Datei hoach, de scho friara glöscht worn is.'''
Bittschee prüf gnau, ob as erneite Hoachladn de Richtlinien entspricht.
Zu deina Information folgt des Lösch-Logbuach mit da Begründung fia de vorherige Löschung:",

# Account creation failure
'cantcreateaccounttitle' => 'Benutzerkonto konn net erstellt wern.',

# History pages
'viewpagelogs'           => 'Logbiacha fia de Seitn oozoang',
'currentrev-asof'        => 'Aktuelle Veasion vum $2, $3.',
'revisionasof'           => 'Veasion vum $2, $3.',
'revision-info'          => 'Veasion vum $2 um $5 am $4.',
'previousrevision'       => '← Nextöidare Version',
'nextrevision'           => 'Nextjingare Veasion →',
'currentrevisionlink'    => 'Aktuelle Veasion',
'cur'                    => 'Aktuöi',
'last'                   => 'Voaherige',
'histlegend'             => 'Zum Ozoagn vo Änderungen einfach de zwoa Versionen auswähln und auf de Schaltfläche „{{int:compareselectedversions}}“ klicken.<br />
* (Aktuell) = Untaschied zur aktuellen Version, (Vorherige) = Untaschied zur vorherigen Version
* Uhrzeit/Datum = Version zu dera Zeit, Benutzername/IP-Adresse vom Bearbeiter, K = Kloane Änderung',
'history-fieldset-title' => 'Suach in da Versiónsgschicht',
'histfirst'              => 'öidaste',
'histlast'               => 'Neiste',
'historyempty'           => '(laa)',

# Revision feed
'history-feed-title' => 'Versionshistorie',
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
'revdel-restore'              => 'Sichtborkait ändern',

# Merge log
'revertmerge'      => 'Vaoanigung zruckenemma',
'mergelogpagetext' => "Des is s'Logbuach vu de vareinigtn Versionsgschichtn.",

# Diffs
'history-title'           => 'Versionsgschicht vu „$1“',
'difference'              => '(Untaschied zwischn Versionen)',
'lineno'                  => 'Zailn $1:',
'compareselectedversions' => 'Gwählte Versionen vergleicha',
'editundo'                => 'ryckgängig',

# Search results
'searchresults'             => 'Suachergebniss',
'searchresults-title'       => 'Eagebnisse vu da Suach nåch „$1“',
'searchresulttext'          => "Fia weidare Infos üwa's Suacha schau auf'd [[{{MediaWiki:Helppage}}|Hüifeseitn]].",
'searchsubtitle'            => 'Dei Suachãnfråg: „[[:$1|$1]]“ ([[Special:Prefixindex/$1|ålle Seitn, de mid „$1“ ãnfãngan]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|ålle Seitn, de wås nåch „$1“ valinkn]])',
'searchsubtitleinvalid'     => 'Dei Suachãnfråg: „$1“.',
'notitlematches'            => 'Koane Üwareinstimmungen mid de Seitntitl',
'notextmatches'             => 'Ka Üwareinstimmung mid dem Inhåit gfundn',
'prevn'                     => "d'voahearing {{PLURAL:$1|$1}}",
'nextn'                     => 'de nextn {{PLURAL:$1|$1}}',
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

# Preferences page
'preferences'       => 'Eistellungen',
'mypreferences'     => 'Eistellunga',
'changepassword'    => 'Passwort ändan',
'prefs-editing'     => 'Beorwaiten',
'searchresultshead' => 'Suachen',
'resultsperpage'    => 'Treffer pro Saiten:',
'guesstimezone'     => 'Vom Browser übanehma',
'allowemail'        => 'E-Mail-Empfang vo andere Benutzer möglich macha.',
'username'          => 'Benutzernam:',
'yourrealname'      => 'Echter Nam:',
'yourlanguage'      => 'Sprooch vo da Benytzerowerflächen',

# User rights
'userrights-groupsmember' => 'Midgliad vo:',
'userrights-no-interwiki' => 'Du hast koa Berechtigung, Benutzerrechte in anderne Wikis zum ändern.',

# Groups
'group-sysop'      => 'Administratorn',
'group-bureaucrat' => 'Bürokratn',
'group-suppress'   => 'Oversighter',
'group-all'        => '(olle)',

'group-user-member'          => 'Benutza',
'group-autoconfirmed-member' => 'Bestätigta Benutza',

'grouppage-user'          => '{{ns:project}}:Benutza',
'grouppage-autoconfirmed' => '{{ns:project}}:Bestätigte Benutza',
'grouppage-sysop'         => '{{ns:project}}:Administratoan',
'grouppage-bureaucrat'    => '{{ns:project}}:Bürokratn',

# Rights
'right-read'  => 'Seitn lesn',
'right-edit'  => 'Saiten beorwaiten',
'right-block' => 'Benutzer sperrn (Schreibrecht)',

# User rights log
'rightslog' => 'Rechte-Logbiachl',

# Associated actions - in the sentence "You do not have permission to X"
'action-edit'       => 'an dea Seitn werklst',
'action-autopatrol' => 'eigne Arbat ois kontrolliert markiern',

# Recent changes
'nchanges'                       => '$1 {{PLURAL:$1|Ändarung|Ändarungen}}',
'recentchanges'                  => 'Letzte Ändarungen',
'recentchanges-legend'           => 'Ãnzeigeopzionen',
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
'show'                           => 'eiblendn',
'minoreditletter'                => 'K',
'newpageletter'                  => 'Nei',
'boteditletter'                  => 'B',
'rc-enhanced-expand'             => 'Deteus ãnzoang (gehd netta mid JavaScript)',
'rc-enhanced-hide'               => 'Details vastecka',

# Recent changes linked
'recentchangeslinked'         => 'Valinkts priafm',
'recentchangeslinked-feed'    => 'Valinkts prüfn',
'recentchangeslinked-toolbox' => 'Valinkts prüfn',
'recentchangeslinked-title'   => 'Ändarungen auf Seitn, zu de vu da Seitn „$1“ valinkt is',
'recentchangeslinked-summary' => "De Spezialseitn zagt de letztn Änderungen bei de Seitn, zu de vu ana gwissn Seitn valinkt wiad (bzw. de wås in ana gwissn Kategorie eisoatiad han). Seitn vu deina [[Special:Watchlist|Beobåchtungslistn]] wean '''fett''' ãnzoagt.",
'recentchangeslinked-page'    => 'Saiten:',
'recentchangeslinked-to'      => 'Zoagt Éndarungen auf Saiten, dé doher valinken',

# Upload
'upload'              => 'Auffeloon',
'uploadnologin'       => 'Ned ågmöidt',
'uploadnologintext'   => "Sie miassn [[Special:UserLogin|ogmeidt sei]], wenn S' Dateien hoachladn wolln.",
'uploadlog'           => 'Datei-Logbuach',
'uploadlogpage'       => 'Datei-Logbuach',
'uploadlogpagetext'   => 'Des is des Logbuach vo de hoachgladna Dateien, schaug aa unta [[Special:NewFiles|neie Dateien]].',
'uploadedfiles'       => 'Hoachgladene Dateien',
'badfilename'         => "Da Dateinam is in „$1“ g'ändat won.",
'large-file'          => 'De Dateigreß sollat nach Möglichkeit $1 net überschreitn. De Datei is $2 groaß.',
'emptyfile'           => "De hochgladene Datei is laar. Da Grund konn a Tippfehler im Dateinam sei. Bitte kontrollieren'S, ob Sie de Datei wirklich hochladn woin.",
'uploadwarning'       => 'Obacht',
'uploadedimage'       => 'håd „[[$1]]“ aufeglådn',
'uploaddisabled'      => "'tschuldigung, as Hochladn is deaktiviert.",
'uploadscripted'      => 'De Datei enthalt HTML- oda Scriptcode, der irrtümlichaweis von am Webbrowser ausgführt wern kinnat.',
'watchthisupload'     => 'De Saiten beowochten',
'filewasdeleted'      => 'A Datei mit dem Nama is scho oamoi hochgladn worn und zwischenzeitlich wieda glöscht worn. Bitte schaug erscht den Eintrag im $1 oo, bevor du de Datei wirklich speicherst.',
'upload-wasdeleted'   => "'''Obacht: Du ladst aa Datei hoach, de scho friara glöscht worn is.'''
Bittschee prüf gnau, ob as erneite Hoachladn de Richtlinien entspricht.
Zu deina Information folgt des Lösch-Logbuach mit da Begründung fia de vorherige Löschung:",
'upload-success-subj' => 'Erfolgreich hoachgladn',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6' => 'URL is net erreichbar',

'license-nopreview'  => '(es gibt koa Vorschau)',
'upload_source_file' => ' (a Datei auf deim Computa)',

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
'redirectstofile'           => "{{PLURAL:$1|D'foignde Datei leitet|De foigndn $1 Datein leitn}} auf de Datei weida:",
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
'statistics'             => 'Statistik',
'statistics-mostpopular' => 'Am meistn bsuachte Seitn',

'disambiguations-text' => 'De folgenden Seitn valinkn auf a Seitn zur Begriffsklärung.
Sie solltn stattdessn auf de eigentlich gemoante Seitn valinkn.<br />A Seitn werd ois Begriffsklärungsseitn behandelt, wenn [[MediaWiki:Disambiguationspage]] auf sie valinkt.<br />
Links aus Namensräume wern da net aufglistet.',

'withoutinterwiki-submit' => 'Zoag',

# Miscellaneous special pages
'nbytes'                 => '$1 {{PLURAL:$1|Byte|Bytes}}',
'ncategories'            => '$1 {{PLURAL:$1|Kategorie|Kategorien}}',
'nlinks'                 => '{{PLURAL:$1|a Link|$1 Links}}',
'nmembers'               => '{{PLURAL:$1|1 Eitråg|$1 Eiträge}}',
'nrevisions'             => '{{PLURAL:$1|oa Beåawatung|$1 Beåawatungen}}',
'nviews'                 => '{{PLURAL:$1|1 Åbfråg|$1 Åbfrång}}',
'uncategorizedtemplates' => 'Net kategorisierte Vorlagen',
'prefixindex'            => 'olle Saiten mid Präfix',
'shortpages'             => 'Kurze Saiten',
'longpages'              => 'Långe Saiten',
'newpages'               => 'Neie Seitn',
'ancientpages'           => 'Scho länger nimma bearbate Artikel',
'move'                   => 'vaschiam',
'movethispage'           => 'de Seitn vaschiam',
'notargettitle'          => 'Koa Seitn ogem',
'pager-newer-n'          => '{{PLURAL:$1|nexta|nexde $1}}',
'pager-older-n'          => '{{PLURAL:$1|voaheriga|voahering $1}}',

# Book sources
'booksources'               => 'ISBN-Suach',
'booksources-search-legend' => 'Suach nåch Bezugsquöin fia Biacha',
'booksources-go'            => 'Suach',

# Special:Log
'log'           => 'Logbiacha',
'all-logs-page' => 'Olle Logbiacher',
'alllogstext'   => 'Des is de kombinierte Anzeige vo alle in {{SITENAME}} gführten Logbiacha. Die Ausgabe ko durch de Auswahl vom Logbuchtyp, vom Benutzer oder vom Seitntitel eigschränkt wern.',
'logempty'      => 'Koane passenden Einträg.',

# Special:AllPages
'allpages'          => 'Alle Seitn',
'alphaindexline'    => '$1 bis $2',
'prevpage'          => 'Voaherige Seitn ($1)',
'allpagesfrom'      => 'Seitn zoang ab:',
'allpagesto'        => 'Seitn ãnzoang bis:',
'allarticles'       => 'Alle Seitn',
'allinnamespace'    => 'Alle Seitn (Namensraum: $1)',
'allnotinnamespace' => 'Alle Seitn (net im $1 Namensraum)',
'allpagesprev'      => 'Vorige',
'allpagesnext'      => 'Nachste',
'allpagessubmit'    => 'Owendn',
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
'mywatchlist'       => 'Beobachtungslistn',
'watchlistanontext' => 'Sie miaßn si $1, um Eanane Beobachtungslistn zum seng oda Einträge auf ihr zum bearbatn.',
'watchnologin'      => 'Sie san net ogmeidt',
'addedwatch'        => 'Zua Beobachtungslistn dazuado',
'addedwatchtext'    => 'De Seitn „[[:$1]]“ is zua deina [[Special:Watchlist|Beobachtungslistn]] dazuado worn.
Änderunga an dera Seitn und vo da Diskussionsseitn wern da glistet und
in da Übasicht vo de [[Special:RecentChanges|letztn Änderungen]] in Fettschrift ozoagt.
Wennst de Seitn wieder vo deina Beobachtungslistn wegdoa mechtn, klickst auf da jeweiligen Seitn auf „nimma beobachten“.',
'removedwatch'      => 'vu da Beobåchtungslistn weg nemma',
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
'watching'   => 'Beobåchtn …',
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
'deletepage'            => 'Seitn löschen',
'excontent'             => "Oida Inhalt: '$1'",
'exblank'               => 'Seitn is laar gwen',
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
'blanknamespace' => '(Saiten)',

# Contributions
'contributions'       => 'Benutzerbeiträg',
'contributions-title' => 'Benutzabeiträg vu „$1“',
'mycontris'           => 'Eigene Beiträg',
'contribsub2'         => 'Fia $1 ($2)',
'uctop'               => '(aktuöi)',
'month'               => 'und Monat',
'year'                => 'bis zum Joa:',

'sp-contributions-newbies'     => 'Netta de Beiträg vu de neichn Benutza ãnzoang',
'sp-contributions-newbies-sub' => 'Fia Neiling',
'sp-contributions-blocklog'    => 'Sperrlogbiache',
'sp-contributions-talk'        => 'bschprecha',
'sp-contributions-search'      => 'Suach nach Benutzerbeiträge',
'sp-contributions-username'    => 'IP-Adress oda Benutzanãm:',
'sp-contributions-submit'      => 'Suacha',

# What links here
'whatlinkshere'            => 'Links auf de Seitn',
'whatlinkshere-title'      => 'Seitn, de nåch „$1“ valinkn',
'whatlinkshere-page'       => 'Seitn:',
'linkshere'                => "↓ Dé foiganden Saiten valinken auf '''„[[:$1]]“''':",
'isredirect'               => 'Weiterleitungsseitn',
'istemplate'               => 'Voalãngeibindung',
'isimage'                  => 'Datailink',
'whatlinkshere-prev'       => "{{PLURAL:$1|vorige|d'voring $1}}",
'whatlinkshere-next'       => "{{PLURAL:$1|nexde|d'nexdn $1}}",
'whatlinkshere-links'      => '← Vaweise',
'whatlinkshere-hideredirs' => 'Weidaleitungen $1',
'whatlinkshere-hidetrans'  => 'Voalãngeibindungen $1',
'whatlinkshere-hidelinks'  => '↓ Links $1',
'whatlinkshere-hideimages' => '↓ Datailinks $1',
'whatlinkshere-filters'    => 'Füita',

# Block/unblock
'blockip'                      => 'IP-Adress/Benytzer sperrn',
'blockip-title'                => '↓ Benytzer sperrn',
'blockip-legend'               => 'IP-Adresse/Benutzer sperrn',
'blockiptext'                  => "Mid dem Formular sperrst a IP-Adress oder an Benytzernåmen, das vo durten aus koane Endarungen mer vurgnumma wern kennan.
Des soid nur dafoing, um an Vandalismus z' vahindern und in Yweraistimmung mid d' [[{{MediaWiki:Policy-url}}|Richtlinien]].
Gib bittschee an Grund fyr d' Sperrn å.",
'ipaddress'                    => 'IP-Adressen:',
'ipadressorusername'           => 'IP-Adress oder Benytzernåm:',
'ipbexpiry'                    => 'Sperrdauer:',
'ipbreason'                    => 'Grund:',
'ipbreasonotherlist'           => 'Åndarer Grund:',
'ipbreason-dropdown'           => "*Oigmoane Sperrgrynd:
**Aifyng vo foische Informazionen
**'s Laarn vo Saiten
**a mossenwaiss Aifyng vo externe Links
**Aistöin vo unsinnige Inhoite in Saiten
**Ned åbrochts Vahoiten
**Missbrauch mid mererne Benytzerkontos
**Ned åbrochter Benyternåm",
'ipbanononly'                  => 'Nur ned-ågmöidate Benytzer sperrn',
'ipbcreateaccount'             => "D' Erstöiung vo Benytzerkontos vahindern",
'ipbemailban'                  => 'E-Mäil-Vasånd sperrn',
'ipbenableautoblock'           => "Sperr de aktuöi vo dem Benytzer gnytzte IP-Adress sowia automaatisch olle foiganden, vo denen aus er Beorwaitungen oder 's Åleeng vo naiche Benytzerkontos vasuacht",
'ipbsubmit'                    => 'IP-Adress/Benytzer sperrn',
'ipbother'                     => 'Åndare Dauer (auf englisch):',
'ipboptions'                   => '2 Stund:2 hours,1 Dog:1 day,3 Dog:3 days,1 Woch:1 week,2 Wochen:2 weeks,1 Monad:1 month,3 Monad:3 months,6 Monad:6 months,1 Jor:1 year,Leemslång:infinite',
'ipbotheroption'               => 'Åndre Dauer:',
'ipbotherreason'               => 'Ånderner/ergenznder Grund:',
'ipbhidename'                  => 'An Benytzernåmen in Beorwaitungen und Linsten vastecken',
'ipbwatchuser'                 => 'De Benytzer(diskussions)saiten beowochten',
'ipballowusertalk'             => "Da Benytzer derf d' oagane Dischkrirsaiten wärnd sainer Sperrn beorwaiten",
'ipb-change-block'             => "D' Sperrn mid de Sperrparameter danaiern",
'badipaddress'                 => 'De IP-Adress hod a foischs Format.',
'blockipsuccesssub'            => 'De Sperrn is erfoigraich gween',
'blockipsuccesstext'           => 'Da Benytzer/de IP-Adress [[Special:Contributions/$1|$1]] is gsperrt worn.<br />
Zur da Aufheewung vo da Sperrn schau unter da [[Special:IPBlockList|Listen vo olle aktivm Sperrn]].',
'ipb-edit-dropdown'            => 'Sperrgrynd beorwaiten',
'ipb-unblock-addr'             => '„$1“ fraigeem',
'ipb-unblock'                  => 'IP-Adress/Benytzer fraigeem',
'ipb-blocklist-addr'           => 'Aktuelle Sperrn fyr „$1“ åzoang',
'ipb-blocklist'                => 'Olle aktuöin Sperrn åzaang',
'ipb-blocklist-contribs'       => 'Benytzerbaiträg vo „$1“',
'unblockip'                    => 'IP-Adress fraigeem',
'unblockiptext'                => 'Mid dem Formular do kååst du a IP-Adress oder an Benytzer fraigeem.',
'ipusubmit'                    => 'Freigem',
'unblocked'                    => '[[User:$1|$1]] is freigem worn',
'unblocked-id'                 => 'Sperr-ID $1 is fraigeem worn',
'ipblocklist'                  => 'Gspeade IP-Adressn und Benutzanãmen',
'ipblocklist-legend'           => 'Suach noch am gsperrden Benytzer',
'ipblocklist-username'         => 'Benytzernåm oder IP-Adress:',
'ipblocklist-sh-userblocks'    => 'Benytzersperrn $1',
'ipblocklist-sh-tempblocks'    => 'Befristate Sperrn $1',
'ipblocklist-sh-addressblocks' => 'IP-Sperrn $1',
'createaccountblock'           => 'Erstellung vo Benutzakonten gsperrt',
'emailblock'                   => 'E-Post vaschicka gspead',
'blocklink'                    => 'sperrn',
'unblocklink'                  => 'freigem',
'change-blocklink'             => 'Sperr ändern',
'contribslink'                 => 'Baiträg',
'autoblocker'                  => "Automatische Sperre, weil s' a gmeinsame IP-Adressn mit „$1“ hernehma. Grund: „$2“.",
'blocklogpage'                 => 'Benutzasperrlogbiache',
'blocklogentry'                => "håd „[[$1]]“ fia'n foigndn Zeidraum gspead: $2; $3",
'unblocklogentry'              => "håd d'Spear vu „$1“ aufghom",
'block-log-flags-anononly'     => 'netta Anonyme',
'block-log-flags-nocreate'     => "S'Eastöin vu Benutzakontn is gspead",
'block-log-flags-noemail'      => 'E-Post vaschicka gspead',

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
'allmessagestext'           => 'Des is a Listn vo de MediaWiki-Systemtexte.
Please visit [http://www.mediawiki.org/wiki/Localisation MediaWiki Localisation] and [http://translatewiki.net translatewiki.net] if you wish to contribute to the generic MediaWiki localisation.',
'allmessagesnotsupportedDB' => "'''Special:Allmessages''' is im Moment net möglich, wei de Datenbank offline is.",

# Thumbnails
'thumbnail-more' => 'vagreßan',

# Special:Import
'importnotext' => 'Laar oder koa Text',

# Import log
'importlogpage' => 'Import-Logbuach',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'Dei Benutzaseitn',
'tooltip-pt-mytalk'               => 'Dei Dischgria-Seitn',
'tooltip-pt-preferences'          => 'Eigene Eistellunga',
'tooltip-pt-watchlist'            => "Listn vu de Seitn, de'st beowåchst",
'tooltip-pt-mycontris'            => 'Liste vo eigene Beiträg',
'tooltip-pt-login'                => 'Das ma se ånmöidt, werd zwor gern gseeng, is ower koa Pflicht ned.',
'tooltip-pt-logout'               => 'Obmeidn',
'tooltip-ca-talk'                 => 'Diskussion zum Seitninhåit',
'tooltip-ca-edit'                 => "Seitn beåawatn. Bitte voa'm Speichan d'Voaschaufunkzion benutzn.",
'tooltip-ca-addsection'           => 'An Kommentar zua dera Diskussion dazuagem.',
'tooltip-ca-viewsource'           => 'De Seitn is gschützt. An Quelltext kann ma oschaun.',
'tooltip-ca-history'              => 'Friarane Versionen vo dera Seitn',
'tooltip-ca-protect'              => 'De Seitn schützen',
'tooltip-ca-delete'               => 'De Seitn löschen',
'tooltip-ca-move'                 => 'De Seitn vaschiam',
'tooltip-ca-watch'                => 'De Seitn zua persönlichen Beobachtungslistn dazua doa',
'tooltip-ca-unwatch'              => 'De Seitn von da persönlichen Beobachtungslistn entferna',
'tooltip-search'                  => '{{SITENAME}} durchsuacha',
'tooltip-search-go'               => 'Gee direkt zua derer Saiten, de exakt am aigeewanen Nåm entspricht.',
'tooltip-search-fulltext'         => 'Suach noch Saiten, de den Text enthoiden',
'tooltip-p-logo'                  => 'Hauptseitn',
'tooltip-n-mainpage'              => 'de Hauptsaiten åzoang',
'tooltip-n-mainpage-description'  => 'Zua da Hauptsaiten gee',
'tooltip-n-portal'                => "Ywers Portaal, wos d' mocha kååst, wo epps zum finden is",
'tooltip-n-currentevents'         => 'Hintergrundinformazionen ywer akutöie Eraignisse',
'tooltip-n-recentchanges'         => 'Listen vo de letzten Endarungen auf {{SITENAME}}',
'tooltip-n-randompage'            => 'Zuafellige Saiten',
'tooltip-n-help'                  => 'Hüifesaiten åzoang',
'tooltip-t-whatlinkshere'         => 'Listn vu ålle Seitn, de då hea zoang',
'tooltip-t-recentchangeslinked'   => "D'letztn Ändarungen auf de Seitn, de vu då valinkt san",
'tooltip-feed-rss'                => 'RSS-Feed vo derer Saiten',
'tooltip-feed-atom'               => 'Atom-Feed vo derer Saiten',
'tooltip-t-contributions'         => "d'Listn vu de Beiträg vu dem Benutza ãschau",
'tooltip-t-emailuser'             => 'Dem Benutza E-Post schicka',
'tooltip-t-upload'                => 'Datain auffeloon',
'tooltip-t-specialpages'          => 'Listen vo olle Speziaalsaiten',
'tooltip-t-print'                 => 'Druckãnsicht vu dea Seitn',
'tooltip-t-permalink'             => 'Dauerhofter Link zua der Saitenversion',
'tooltip-ca-nstab-main'           => 'Saiteninhoit åzoang',
'tooltip-ca-nstab-user'           => 'Benutzaseitn ãzoang',
'tooltip-ca-nstab-special'        => 'Des is a Speziaalsaiten, dest ned beorwaiten kååst.',
'tooltip-ca-nstab-project'        => 'Portalseitn ãnzoang',
'tooltip-ca-nstab-image'          => "D'Dateiseitn ãnzoang",
'tooltip-ca-nstab-template'       => "d'Vorlåg ãnzoang",
'tooltip-ca-nstab-help'           => 'Huifseitn oozoang',
'tooltip-ca-nstab-category'       => 'Kategorieseitn ãnzoang',
'tooltip-minoredit'               => 'De Änderung åis a klaane markian.',
'tooltip-save'                    => 'Enderungen spaichern',
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

# Math errors
'math_unknown_function' => 'Unbekannte Funktion',

# Patrolling
'markedaspatrollederrortext' => 'Sie miaßn a Seitnänderung auswähln.',

# Image deletion
'deletedrevision'    => 'Oide Version $1 glöscht.',
'filedelete-missing' => 'De Datei „$1“ ko net glöscht wern, weils es net gibt.',

# Browsing diffs
'previousdiff' => '← Zum vorigen Versionsunterschied',
'nextdiff'     => 'zum nextn Untaschied in de Veasionen →',

# Media information
'file-info-size'       => '($1 × $2 Pixl, Dateigreßn: $3, MIME-Typ: $4)',
'file-nohires'         => '<small>Es gibt ka hechane Auflösung.</small>',
'svg-long-desc'        => '(SVG-Datei, Basisgreß: $1 × $2 Pixl, Dateigreß: $3)',
'show-big-image'       => 'Version in hechana Auflösung',
'show-big-image-thumb' => '<small>Greßn vu da Voaãnsicht: $1 × $2 Pixl</small>',

# Special:NewFiles
'newimages'         => 'Neie Dateien',
'newimages-summary' => 'De Spezialseitn zoagt de zletzt hochgeladena Buidl und Dateien o.',
'noimages'          => 'Koane Datein gfunden.',
'ilsubmit'          => 'Suach',

# Bad image list
'bad_image_list' => "Format:

Netta Zeun, de mid am * ãnfãngan, wean ausgweat. Åis eastas nåch'm * muaß a Link auf a uneawünschte Datei steh.
Darauf foignde Links auf Seitn in da söiwn Zeun definian Ausnãhmen, in denen eanan Zusãmmenhãng de Datei trotzdem vawendt wean deaf.",

# Metadata
'metadata'          => 'Metadatn',
'metadata-help'     => 'Dé Datai enthoit waiderne Informaziónen, dé in da Reegl vo da Digitoikamera óder am vawendaten Scanner ståmman. Durch a nochträgliche Beorwaitung vo da Originoidatai kennan oanige Details vaéndert worn sai.',
'metadata-expand'   => 'Erweitate Deteus eiblendn',
'metadata-collapse' => "D'eaweidatn Deteus ausblendn",
'metadata-fields'   => 'Dé foiganden Föider vo de ESIF-Metadaaten in dém MediaWiki-Systémtext wern auf de Büidlbeschraiwungssaiten åzoagt; waiderne standardmässég "aikloppte" Details kennan åzoagt wern.
*make
*model
*dateimeoriginal
*exposuretime
*fnumber
*isospeedratings
*focallength',

# EXIF tags
'exif-gpsspeed' => 'Geschwindigkeit vom GPS-Empfänger',

'exif-componentsconfiguration-0' => "Gibt's net",

# External editor support
'edit-externally'      => 'De Datei mid am exteanen Programm beåawatn',
'edit-externally-help' => '↓ (Schau unter [http://www.mediawiki.org/wiki/Manual:External_editors Installaziónsåwaisungen] fyr waiderne Informaziónen)',

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
'watchlistedit-normal-explain' => 'Des sand de Eiträge vo deiner Beobachtungslistn. Um Eiträge zum entferna, markier de Kastl nem de Eiträg und klick auf „Eiträg entferna“. Du kannst dei Beobachtungsliste aa im [[Special:Watchlist/raw|Listenformat bearbatn]].',
'watchlistedit-normal-submit'  => 'Eiträge wegnehma',
'watchlistedit-raw-titles'     => 'Eiträg:',

# Watchlist editing tools
'watchlisttools-view' => 'Ändarungen vafoing',
'watchlisttools-edit' => 'noamal beåawatn',
'watchlisttools-raw'  => 'im Listnfoamat beåawatn',

# Special:Version
'version-hook-subscribedby' => 'Aufruf vo',

# Special:SpecialPages
'specialpages' => 'Speziaalsaiten',

);
