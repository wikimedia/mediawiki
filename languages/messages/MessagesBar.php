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
 * @author Metalhead64
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
'tog-showtoolbar'            => 'Bearbatn-Werkzeugleiste oozoang',
'tog-editondblclick'         => 'Seitn mid am Doppiklick beåaweitn (JavaScript wiad braucht)',
'tog-editsection'            => 'Links zum Beåaweitn vu de anzlnen Åbschnitte ãnzoang',
'tog-editwidth'              => "Eingåbeföid erweitan, damid's in gãnzn Büidschiam ausfüid",
'tog-watchcreations'         => 'Vu mia söiwa nei eigstöide Seitn automatisch beobåchtn',
'tog-watchdefault'           => 'Vu mia söiwa beåabeitete und vu mia nei eigstöide Seitn automatisch beobåchtn',
'tog-watchmoves'             => 'Vu mia söiwa vaschomne Seitn automatisch beobåchtn',
'tog-watchdeletion'          => 'Vu mia söiwa glöschte Seitn automatisch beobåchtn',
'tog-minordefault'           => 'De eigenen Ändarungen standardmäßig åis geringfügig markian',
'tog-previewonfirst'         => "Beim erstn Beåawatn imma d'Voaschau ãnzoang",
'tog-nocache'                => 'Seitncache deaktivian',
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

'underline-always' => 'imma',
'underline-never'  => 'nia',

# Dates
'sunday'      => 'Sunntåg',
'monday'      => 'Montåg',
'tuesday'     => 'Ertig',
'wednesday'   => 'Mittwoch',
'thursday'    => 'Pfinstig',
'friday'      => 'Freitåg',
'saturday'    => 'Sãmståg',
'sun'         => 'Su',
'mon'         => 'Mo',
'tue'         => 'Er',
'wed'         => 'Mi',
'thu'         => 'Pf',
'fri'         => 'Fr',
'sat'         => 'Sa',
'january'     => 'Jänna',
'february'    => 'Februa',
'march'       => 'Mäaz',
'april'       => 'Aprüi',
'may_long'    => 'Mai',
'june'        => 'Juni',
'july'        => 'Juli',
'august'      => 'August',
'september'   => 'Septemba',
'october'     => 'Oktowa',
'november'    => 'Novemba',
'december'    => 'Dezemba',
'january-gen' => 'Jänner',
'jan'         => 'Jän.',
'feb'         => 'Feb.',
'mar'         => 'Mär.',
'apr'         => 'Apr.',
'may'         => 'Mai',
'jun'         => 'Jun.',
'jul'         => 'Jul.',
'aug'         => 'Aug.',
'sep'         => 'Sep.',
'oct'         => 'Okt.',
'nov'         => 'Nov.',
'dec'         => 'Dez.',

# Categories related messages
'pagecategories'                 => '{{PLURAL:$1|Kategorie|Kategorien}}',
'category_header'                => 'Seitn in da Kategorie „$1“',
'subcategories'                  => 'Untakategorien',
'category-media-header'          => 'Medien in da Kategorie „$1“',
'category-empty'                 => "''De Kategorie enthåit momentan kane Seitn und kane Medien ned.''",
'hidden-categories'              => '{{PLURAL:$1|Vasteckte Kategorie|Vasteckte Kategorien}}',
'hidden-category-category'       => 'Vasteckte Kategorie', # Name of the category where hidden categories will be listed
'category-subcat-count'          => "{{PLURAL:$2|De Kategorie enthåit netta de foignde Untakategorie:|{{PLURAL:$1|D'foignde Untakategorie is ane vu insgsamt $2 Untakategorien in dea Kategorie:|Vu insgsamt $2 Untakategorien in dea Kategorie wean $1 ãzoagt:}}}}",
'category-subcat-count-limited'  => 'In de Kategorie {{PLURAL:$1|is de foignde Untakategorie|san de foigndn Untakategorien}} eisoatiad:',
'category-article-count-limited' => 'De {{PLURAL:$1|foignde Seitn is|foigndn $1 Seitn san}} in dea Kategorie enthåitn:',
'category-file-count-limited'    => "{{PLURAL:$1|D'foignde Datei is|De foigndn $1 Datein san}} in de Kategorie eisoatiad:",
'listingcontinuesabbrev'         => '(Foatsetzung)',

'mainpagetext'      => "<big>'''MediaWiki is eafoigreich installiad woan.'''</big>",
'mainpagedocfooter' => 'A Huif zua Benutzung und Konfiguration vo da Wiki-Software findst im [http://meta.wikimedia.org/wiki/Help:Contents Benutzerhandbuch].
* [https://lists.wikimedia.org/mailman/listinfo/mediawiki-announce Mailingliste neuer MediaWiki-Versionen]',

'about'          => 'Üba',
'article'        => 'Artikl',
'newwindow'      => '(wiad in am neichn Fensta aufgmåcht)',
'cancel'         => 'Åbbrecha',
'qbmyoptions'    => 'Meine Seitn',
'qbspecialpages' => 'Spezialseitn',
'moredotdotdot'  => 'Mehra …',
'mypage'         => 'Eigne Seitn',
'mytalk'         => 'Eigne Diskussion',
'anontalk'       => 'Diskussionsseitn vo dera IP',
'and'            => '&#32;und',

'errorpagetitle'    => 'Fehla',
'returnto'          => 'Zruck zur Seitn $1.',
'tagline'           => 'Aus {{SITENAME}}',
'help'              => 'Fragen?',
'search'            => 'Suach',
'searchbutton'      => 'Suach',
'searcharticle'     => 'Artikl',
'history'           => 'Versionen',
'updatedmarker'     => '(gändat)',
'printableversion'  => 'Version zum Ausdruckn',
'permalink'         => 'Bschtändige URL',
'print'             => 'Druckn',
'edit'              => 'werkln',
'create'            => 'Erstöin',
'editthispage'      => 'Seitn beårbeitn',
'create-this-page'  => 'Seitn eastöin',
'delete'            => 'löschn',
'deletethispage'    => 'De Seitn löschn',
'undelete_short'    => '{{PLURAL:$1|1 Version|$1 Versionen}} wiedaheastöin',
'protect'           => 'Schützn',
'protect_change'    => 'ändan',
'protectthispage'   => 'Seitn schützn',
'unprotect'         => 'freigem',
'unprotectthispage' => 'Schutz aufhem',
'newpage'           => 'Neiche Seitn',
'talkpagelinktext'  => 'bschprecha',
'articlepage'       => 'Artikl',
'talk'              => 'bschprecha',
'toolbox'           => 'Weakzeigkistn',
'userpage'          => 'Benutzerseitn',
'mediawikipage'     => 'Inhaltsseitn ozoang',
'categorypage'      => 'Kategorieseitn ozoang',
'otherlanguages'    => 'Åndane Språchn',
'redirectedfrom'    => '(vu $1 weida gschickt)',
'lastmodifiedat'    => 'De Seitn is zletzt am $1 um $2 gändert worn.', # $1 date, $2 time
'jumpto'            => 'Wechseln zua:',
'jumptosearch'      => 'Suach',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'Üba {{SITENAME}}',
'aboutpage'            => 'Project:Üba_{{SITENAME}}',
'disclaimers'          => 'Impressum',
'disclaimerpage'       => 'Project:Impressum',
'edithelp'             => 'Bearbeitungshuifn',
'edithelppage'         => 'Help:Bearbeitungshuifn',
'mainpage'             => 'Hauptsaitn',
'mainpage-description' => 'Hauptsaitn',
'privacy'              => 'Datnschutz',

'badaccess' => 'Koane ausreichenden Rechte',

'ok'                      => 'haut hi',
'retrievedfrom'           => 'Vu „$1“',
'youhavenewmessages'      => 'Sie ham $1 ($2).',
'newmessageslink'         => 'neie Nachrichten',
'newmessagesdifflink'     => 'neie Nachrichtn',
'youhavenewmessagesmulti' => 'Sie ham neie Nachrichten: $1',
'editsection'             => 'werkln',
'editold'                 => 'werkln',
'viewsourcelink'          => 'in Quöitext ãschau',
'editsectionhint'         => 'Åbschnitt beåaweitn: $1',
'toc'                     => 'Inhoitsvazeichnis',
'showtoc'                 => 'Ozoang',
'thisisdeleted'           => '$1 ooschaun oda wieda herstelln?',
'viewdeleted'             => '$1 oozoang?',
'red-link-title'          => "$1 (de Seitn gibt's ned)",

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-user'     => 'Benutzerseitn',
'nstab-special'  => 'Spezialseitn',
'nstab-project'  => 'Projektseitn',
'nstab-image'    => 'Datei',
'nstab-template' => 'Voalåg',
'nstab-category' => 'Kategorie',

# Main script and global functions
'nosuchspecialpage' => "De Spezialseitn gibt's ned",

# General errors
'error'                => 'Fehla',
'databaseerror'        => 'Fehla in da Datenbank',
'noconnect'            => 'Konn koa Verbindung zur Datenbank auf $1 herstelln',
'missingarticle-rev'   => '(Veasionsnumma: $1)',
'missingarticle-diff'  => '(Untaschiad zwischn Veasionen: $1, $2)',
'internalerror'        => 'Inteana Fehla',
'internalerror_info'   => 'Inteana Fehla: $1',
'filecopyerror'        => 'De Datei „$1“ håd ned nåch „$2“ kopiad wean kina.',
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
'viewsource'           => 'Quöitext ãnschau',
'viewsourcefor'        => 'fia $1',
'actionthrottled'      => 'Akzionszåih limitiad',
'actionthrottledtext'  => "De Akzion kãu innahåib vu am kuazn Zeitåbstãnd netta begrenzt oft ausgfüahd wean. De Grenzn håst gråd erreicht. Bitte probia's in a poa Minutn nu amåi.",
'protectedpagetext'    => "De Seitn is fia s'Beåaweitn gspead.",
'viewsourcetext'       => 'Sie kinnan aba an Quelltext vo dera Seitn ooschaun und kopiern:',
'editinginterface'     => "'''Obacht:''' De Seitn enthoit von da MediaWiki-Software benutzten Text. Änderungen wirken si auf de Benutzeroberfläche aus.",
'titleprotected'       => "A Seitn mit dem Nama kann net ogelegt wern. De Sperre is durch [[User:$1|$1]] mit da Begründung ''„$2“'' eigericht worn.",

# Login and logout pages
'logouttext'                 => 'Sie san iatzat abgmeldt.
Sie kinnan {{SITENAME}} iatzat anonym weitanutzn, oda si unta am selben oda am andern Benutzernamen wieder omeldn.',
'yourname'                   => 'Benutzernam:',
'yourpasswordagain'          => 'Passwort no amoi',
'yourdomainname'             => 'Eanane Domain:',
'login'                      => 'Oomeidn',
'userlogin'                  => 'Oomeidn',
'logout'                     => 'Obmeidn',
'userlogout'                 => 'Obmeidn',
'notloggedin'                => 'Net ogmeldt',
'nologin'                    => 'Du hast koa Benutzakonto? $1.',
'createaccount'              => 'Benutzerkonto oleng',
'gotaccount'                 => 'Du hast hast scho a Benutzerkonto? $1.',
'gotaccountlink'             => 'Omeidn',
'userexists'                 => "Der Benutzernam is schon vergem. Bittschee nehman S' an andern.",
'username'                   => 'Benutzernam:',
'yourrealname'               => 'Echter Nam:',
'yourlanguage'               => 'Sprache vo da Benutzeroberfläche:',
'loginerror'                 => 'Fehla bei da Oomeidung',
'nocookieslogin'             => "{{SITENAME}} nimmt Cookies zum Eiloggen vo de Benutzer her. Sie ham Cookies deaktiviert, bittschee aktiviern Sie de und versuchan's nomoi.",
'loginsuccesstitle'          => 'Omeidung is erfolgreich gwen',
'loginsuccess'               => 'Sie san  iatzat ois „$1“ bei {{SITENAME}} oogmeidt.',
'wrongpassword'              => "Des Passwort is falsch (oda fehlt). Bitte probier's no amoi.",
'wrongpasswordempty'         => 'Des eigemne Passwort is laar gwen. Bitte no amoi probiern.',
'mailmypassword'             => 'Neis Passwort zuasendn',
'passwordremindertitle'      => 'Neis Passwort fia a {{SITENAME}}-Benutzerkonto',
'acct_creation_throttle_hit' => 'Du hosst scho $1 {{PLURAL:$1|Benutzakonto|Benutzakonten}} und konnst iatzat koane mehr oleng.',
'accountcreated'             => 'Benutzerkonto is erstellt worn',
'accountcreatedtext'         => "'s Benutzerkonto $1 is eigricht worn.",

# Password reset dialog
'oldpassword' => 'Oids Passwort:',
'newpassword' => 'Neis Passwort:',
'retypenew'   => 'Neis Passwort (no amoi):',

# Edit page toolbar
'italic_sample' => 'Kuasiva Text',
'italic_tip'    => 'Kuasiva Text',
'nowiki_tip'    => 'Unfoamatiada Text',
'image_tip'     => 'Dateilink',
'sig_tip'       => 'Dei Signatur mit Zeitstempe',
'hr_tip'        => 'Wåågrechte Linie (spåasãm vawendn)',

# Edit pages
'summary'                  => 'Zsammafassung',
'subject'                  => 'Betreff:',
'minoredit'                => 'Nur Kloanigkeitn san verändert worn',
'watchthis'                => 'De Seitn beobachtn',
'savearticle'              => 'Seitn speichern',
'preview'                  => 'Voaschau',
'showpreview'              => 'Vorschau zoang',
'showdiff'                 => 'Ändarungen zoang',
'anoneditwarning'          => "Du beåaweitst de Seitn, ohne dass'd ãgmöidt bist. Wãnn'st iatst speichast, dãun wiad dei aktuelle IP-Adressn in da Veasionsgschicht aufzeichnt und kãu damid unwidaruflich '''öffntlich''' eigseng wean.",
'missingsummary'           => "'''Hinweis:''' Sie ham koa Zsammafassung oogem. Wenn S' wieda auf „Speichern“ klicken, werd Eana Änderung ohne Zsammafassung übanumma.",
'missingcommenttext'       => "Bitte gebn S' a Zsammafassung ei.",
'subject-preview'          => 'Vorschau vom Betreff',
'blockedtitle'             => 'Benutzer is gesperrt',
'whitelistedittitle'       => 'Zum Bearbatn miaßn Sie si oomeidn',
'whitelistedittext'        => 'Sie miaßn si $1, um Seiten bearbatn zum kinna.',
'confirmedittitle'         => 'Zum Bearbatn is a E-Mail-Bestätigung erforderlich.',
'loginreqtitle'            => 'Es braucht a Oomeidung',
'loginreqlink'             => 'oomeidn',
'loginreqpagetext'         => 'Sie miaßn si $1, um Seitn lesen zum kinna.',
'accmailtitle'             => 'Passwort is vaschickt worn',
'accmailtext'              => 'Des Passwort fia „$1“ is an $2 gschickt worn.',
'newarticle'               => '(Nei)',
'newarticletext'           => 'Da an Text vo da neien Seitn eintragn. Bitte nur in ganze Sätze schreim und koane urheberrechtsgeschützten Texte vo andere kopiern.',
'anontalkpagetext'         => "---- ''De Seitn werd dazu hergnumma, am net ogmeldten Benutzer Nachrichtn zum hinterlassen.
Wennst mit de Kommentare auf dera Seitn nix ofanga kåst, is vermutlich da friarare Inhaber vo dera IP-Adressn gmoant und du kånstas ignoriern.
Wennst a anonymer Benutzer bist und dengst, daß irrelevante Kommentare an di grichtet worn san, [[Special:UserLogin|meld di bitte o]], um zukünftig Verwirrung zum vermeiden. ''",
'noarticletext'            => 'De Seitn enthåit zua Zeid kan Text ned.
Du kãnnst in Titl vu dea Seitn auf de ãndan Seitn [[Special:Search/{{PAGENAME}}|suacha]],
<span class="plainlinks"> in de dazuaghearadn [{{fullurl:Special:Log|page={{urlencode:{{FULLPAGENAME}}}}}} Logbiache suachn] oda de Seitn [{{fullurl:{{FULLPAGENAME}}|action=edit}} beåabeitn]</span>.',
'updated'                  => '(Gändat)',
'note'                     => "'''Hinweis:'''",
'previewnote'              => "'''Des is netta a Voaschau, d'Seitn is nu ned gspeichat woan!'''",
'previewconflict'          => "De Vorschau gibt an Inhalt vom obern Textfeld wieda; so werd de Seite ausschaun, wenn S' iatzat speichern.",
'session_fail_preview'     => '<strong>Dei Bearbeitung is net gspeichert worn, wei deine Sitzungsdaten valorn ganga san.
Bitte versuachs no amoi, indem du unta da foigendn Textvorschau nochmois auf „Seitn speichern“ klickst.
Sollt des Problem bestehn bleim, meld di ab und danach wieda oo.</strong>',
'editing'                  => 'Bearbatn vo $1',
'editconflict'             => 'Konflikt beim Bearbatn: $1',
'explainconflict'          => "Jemand anders hat de Seitn gändert, nachdem du oogfanga hast sie zum bearbatn.
Des obere Textfeld enthoit den aktuellen Stand.
Des untere Textfeld enthoit deine Änderungen.
Bitte füg deine Änderungen in des obere Textfeld ei.
'''Nur''' da Inhalt vom obern Textfeld werd gspeichert, wenn du auf „Seitn speichern“ klickst!",
'yourtext'                 => 'Eana Text',
'editingold'               => "<strong>ACHTUNG: Sie arbatn an a oidn Version vo dera Seit.
Wenn S' speichern, wern alle neiern Versionen übaschriem.</strong>",
'longpagewarning'          => "<strong>WARNUNG: De Seitn is $1 kB groaß; net jeda Browser konn Seitn bearbatn, di größer als 32 kB san.
Überlegen S' bitte, ob a Aufteilung vo da Seitn in kloanere Abschnitte möglich is.</strong>",
'semiprotectedpagewarning' => "'''Hoibsperrung:''' De Seitn is so gsperrt worn, daß nur registrierte Benutzer de ändern kinnan.",
'titleprotectedwarning'    => "'''ACHTUNG: Die Seitenerstellung wurde gesperrt. Nur bestimmte Benutzergruppen können die Seite erstellen.'''",
'templatesused'            => 'De foigendn Vorlagn wern von dera Seitn vawendt:',
'templatesusedpreview'     => 'De foigendn Vorlagn wern von dera Seitnvorschau vawendt:',
'templatesusedsection'     => 'De foigendn Vorlagn wern von dem Abschnitt vawendt:',
'template-protected'       => '(schreibgschützt)',
'nocreatetitle'            => 'De Erstellung vo neie Seitn is eingeschränkt.',
'recreate-deleted-warn'    => "'''Obacht: Du ladst aa Datei hoach, de scho friara glöscht worn is.'''
Bittschee prüf gnau, ob as erneite Hoachladn de Richtlinien entspricht.
Zu deina Information folgt des Lösch-Logbuach mit da Begründung fia de vorherige Löschung:",

# Account creation failure
'cantcreateaccounttitle' => 'Benutzerkonto konn net erstellt wern.',

# History pages
'viewpagelogs'    => 'Logbiacha fia de Seitn oozoang',
'currentrev-asof' => 'Aktuelle Veasion vum $1',
'histlegend'      => 'Zum Ozoagn vo Änderungen einfach de zwoa Versionen auswähln und auf de Schaltfläche „{{int:compareselectedversions}}“ klicken.<br />
* (Aktuell) = Untaschied zur aktuellen Version, (Vorherige) = Untaschied zur vorherigen Version
* Uhrzeit/Datum = Version zu dera Zeit, Benutzername/IP-Adresse vom Bearbeiter, K = Kloane Änderung',
'deletedrev'      => '[glöscht]',
'histlast'        => 'Neiste',
'historyempty'    => '(laa)',

# Revision feed
'history-feed-title' => 'Versionshistorie',
'history-feed-empty' => "Die angeforderte Seitn gibt's net.
Vielleicht is sie gelöscht oda verschom worn.
[[Special:Search|Durchsuachan]] S' {{SITENAME}} für passende neie Seitn.",

# Revision deletion
'rev-deleted-text-permission' => '<div class="mw-warning plainlinks"> De Version is glöscht worn und is nimma öffentlich zum einseng.
Nähere Angaben zum Löschvorgang sowia a Begründung findn si im [{{fullurl:Special:Log/delete|page={{PAGENAMEE}}}} Lösch-Logbuch].</div>',
'rev-deleted-text-view'       => '<div class="mw-warning plainlinks">De Version is gelöscht worn und is nimma öffentlich einsehbar.
Als Administrator kennan Sie weiterhin einseng.
Nähere Angaben zum Löschvorgang sowia a Begründung finden si im [{{fullurl:Special:Log/delete|page={{FULLPAGENAMEE}}}} Lösch-Logbuach].</div>',
'rev-deleted-no-diff'         => '<div class="mw-warning plainlinks">Du kannst diesen Unterschied nicht betrachten, da eine der Versionen aus den öffentlichen Archiven entfernt wurde.
Details stehen im [{{fullurl:Special:Log/delete|page={{FULLPAGENAMEE}}}} Lösch-Logbuch].</div>',
'rev-delundel'                => 'zoang/vastecka',
'revdelete-nooldid-title'     => 'Koa Version ogem',
'revdelete-text'              => "'''Der Inhalt oder andere Bestandteile gelöschter Versionen sind nicht mehr öffentlich einsehbar, erscheinen jedoch weiterhin als Einträge in der Versionsgeschichte.'''
{{SITENAME}}-Administratoren können den entfernten Inhalt oder andere entfernte Bestandteile weiterhin einsehen und wiederherstellen, es sei denn, es wurde festgelegt, dass die Zugangsbeschränkungen auch für Administratoren gelten.",

# Merge log
'revertmerge'      => 'Vareinigung zrucknehma',
'mergelogpagetext' => "Des is s'Logbuach vu de vareinigtn Versionsgschichtn.",

# Diffs
'history-title'           => 'Versionsgschicht vu „$1“',
'difference'              => '(Untaschied zwischn Versionen)',
'compareselectedversions' => 'Gwählte Versionen vergleicha',
'editundo'                => 'rückgängig',

# Search results
'searchresults'            => 'Suachergebnisse',
'searchresults-title'      => 'Eagebnisse vu da Suach nåch „$1“',
'searchresulttext'         => "Fia weidare Infos üwa's Suacha schau auf'd [[{{MediaWiki:Helppage}}|Hüifeseitn]].",
'searchsubtitle'           => 'Dei Suachãnfråg: „[[:$1|$1]]“ ([[Special:Prefixindex/$1|ålle Seitn, de mid „$1“ ãnfãngan]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|ålle Seitn, de wås nåch „$1“ valinkn]])',
'searchsubtitleinvalid'    => 'Dei Suachãnfråg: „$1“.',
'noexactmatch'             => "'''Es gibt ka Seitn mi'm Titl „$1“.'''
Wãnn'st di mid dem Thema auskennst, kãnnst [[:$1|de Seitn söiwa schreim]].",
'prevn'                    => "d'voahearing $1",
'nextn'                    => 'de nextn $1',
'viewprevnext'             => 'Zoag ($1) ($2) ($3)',
'search-result-size'       => '$1 ({{PLURAL:$2|1 Woat|$2 Wöata}})',
'search-suggest'           => 'Hädst „$1“ gmoant?',
'search-interwiki-caption' => 'Schwestaprojekte',
'search-interwiki-default' => '$1 Eagebnisse:',
'showingresultstotal'      => "Es {{PLURAL:$4|foigt s'Suacheagebnis '''$1''' vu '''$3:'''|foing de Suacheagebnisse '''$1–$2''' vu '''$3:'''}}",
'powersearch'              => 'Suach',
'powersearch-redir'        => 'Weidaleitungen ãnzoang',
'powersearch-field'        => 'Suach nåch:',

# Preferences page
'mypreferences'         => 'Eistellunga',
'changepassword'        => 'Passwort ändan',
'math_unknown_function' => 'Unbekannte Funktion',
'textboxsize'           => 'Bearbatn',
'searchresultshead'     => 'Suachn',
'guesstimezone'         => 'Vom Browser übanehma',
'allowemail'            => 'E-Mail-Empfang vo andere Benutzer möglich macha.',

# User rights
'userrights-groupsmember' => 'Mitglied vo:',
'userrights-no-interwiki' => 'Du hast koa Berechtigung, Benutzerrechte in anderne Wikis zum ändern.',

# Rights
'right-block' => 'Benutzer sperrn (Schreibrecht)',

# Associated actions - in the sentence "You do not have permission to X"
'action-autopatrol' => 'eigne Arbat ois kontrolliert markiern',

# Recent changes
'recentchanges'        => 'Letzte Ändarungen',
'recentchanges-legend' => 'Ãnzeigeopzionen',
'rclistfrom'           => 'Netta Änderungen seid $1 ãzoang.',
'rcshowhidemine'       => 'Eigne Beiträge $1',
'rclinks'              => 'De letztn $1 Ändarungen vu de letztn $2 Tåg ãnzoang<br />$3',
'diff'                 => 'Untaschied',
'hist'                 => 'Veasionen',
'hide'                 => 'ausblendn',
'show'                 => 'eiblendn',
'minoreditletter'      => 'K',
'newpageletter'        => 'Nei',
'boteditletter'        => 'B',

# Recent changes linked
'recentchangeslinked' => 'Valinkts prüfn',

# Upload
'upload'            => 'Aufelådn',
'reupload'          => 'Abbrecha',
'uploadnologin'     => 'Net ogmeidt',
'uploadnologintext' => "Sie miassn [[Special:UserLogin|ogmeidt sei]], wenn S' Dateien hoachladn wolln.",
'uploadlog'         => 'Datei-Logbuach',
'uploadlogpage'     => 'Datei-Logbuach',
'uploadlogpagetext' => 'Des is des Logbuach vo de hoachgladna Dateien, schaug aa unta [[Special:NewFiles|neie Dateien]].',
'uploadedfiles'     => 'Hoachgladene Dateien',
'badfilename'       => "Da Dateinam is in „$1“ g'ändat won.",
'large-file'        => 'De Dateigreß sollat nach Möglichkeit $1 net überschreitn. De Datei is $2 groaß.',
'emptyfile'         => "De hochgladene Datei is laar. Da Grund konn a Tippfehler im Dateinam sei. Bitte kontrollieren'S, ob Sie de Datei wirklich hochladn woin.",
'successfulupload'  => 'Erfolgreich hoachgladn',
'uploadwarning'     => 'Obacht',
'uploaddisabled'    => "'tschuldigung, as Hochladn is deaktiviert.",
'uploadscripted'    => 'De Datei enthalt HTML- oda Scriptcode, der irrtümlichaweis von am Webbrowser ausgführt wern kinnat.',
'watchthisupload'   => 'De Seitn beobachtn',
'filewasdeleted'    => 'A Datei mit dem Nama is scho oamoi hochgladn worn und zwischenzeitlich wieda glöscht worn. Bitte schaug erscht den Eintrag im $1 oo, bevor du de Datei wirklich speicherst.',
'upload-wasdeleted' => "'''Obacht: Du ladst aa Datei hoach, de scho friara glöscht worn is.'''
Bittschee prüf gnau, ob as erneite Hoachladn de Richtlinien entspricht.
Zu deina Information folgt des Lösch-Logbuach mit da Begründung fia de vorherige Löschung:",

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6' => 'URL is net erreichbar',

'license-nopreview'  => '(es gibt koa Vorschau)',
'upload_source_file' => ' (a Datei auf deim Computa)',

# File description page
'filehist'                  => 'Dateiveasionen',
'filehist-current'          => 'aktuell',
'filehist-datetime'         => 'Version vum',
'filehist-thumb'            => 'Voaschaubüidl',
'filehist-thumbtext'        => "Vorschaubüidl fia'd Veasion vum $1",
'filehist-user'             => 'Benutza',
'filehist-dimensions'       => 'Måße',
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
'sharedupload'              => 'De Datei stãmmt aus $1 und deaf bei ãndare Projekte vawendt wean.', # $1 is the repo name, $2 is shareduploadwiki(-desc)
'shareduploadwiki-desc'     => 'Es folgt der Inhalt der $1 aus dem gemeinsam benutzten Repositorium.',
'noimage'                   => "A Datei mit dem Nam existiert net, Sie kinnan s' abe $1.",
'uploadnewversion-linktext' => 'A neie Version vo dera Datei hoachladn',

# File reversion
'filerevert-defaultcomment' => 'zruckgsetzt auf de Version vom $1, $2',
'filerevert-submit'         => 'Zrucksetzen',

# File deletion
'filedelete-legend' => 'Lösch de Datei',
'filedelete-intro'  => "Du löschst de Datei '''„[[Media:$1|$1]]“'''.",

# MIME search
'mimesearch-summary' => 'Auf dieser Spezialseite können die Dateien nach dem MIME-Typ gefiltert werden. Die Eingabe muss immer den Medien- und Subtyp beinhalten: <tt>image/jpeg</tt> (siehe Bildbeschreibungsseite).',
'download'           => 'Runterladn',

# Unused templates
'unusedtemplates' => 'Net benutzte Vorlagen',

# Random page
'randompage' => 'Zuafalls-Artikl',

# Statistics
'statistics-mostpopular' => 'Am meistn bsuachte Seitn',

'disambiguationspage'  => 'Template:Begriffsklärung',
'disambiguations-text' => 'De folgenden Seitn valinkn auf a Seitn zur Begriffsklärung.
Sie solltn stattdessn auf de eigentlich gemoante Seitn valinkn.<br />A Seitn werd ois Begriffsklärungsseitn behandelt, wenn [[MediaWiki:Disambiguationspage]] auf sie valinkt.<br />
Links aus Namensräume wern da net aufglistet.',

'withoutinterwiki-submit' => 'Zoag',

# Miscellaneous special pages
'nmembers'               => '{{PLURAL:$1|1 Eitråg|$1 Eiträge}}',
'uncategorizedtemplates' => 'Net kategorisierte Vorlagen',
'longpages'              => 'Lange Seitn',
'newpages'               => 'Neie Seitn',
'ancientpages'           => 'Scho länger nimma bearbate Artikel',
'move'                   => 'vaschiam',
'notargettitle'          => 'Koa Seitn ogem',

# Book sources
'booksources-go' => 'Suach',

# Special:Log
'log'           => 'Logbiacha',
'all-logs-page' => 'Alle Logbiacha',
'alllogstext'   => 'Des is de kombinierte Anzeige vo alle in {{SITENAME}} gführten Logbiacha. Die Ausgabe ko durch de Auswahl vom Logbuchtyp, vom Benutzer oder vom Seitntitel eigschränkt wern.',
'logempty'      => 'Koane passenden Einträg.',

# Special:AllPages
'allpages'          => 'Alle Seitn',
'allpagesfrom'      => 'Seitn zoang ab:',
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
'linksearch-ok' => 'Suacha',

# Special:ListUsers
'listusers-submit'   => 'Zoag',
'listusers-noresult' => 'Koane Benutzer gfunden.',

# E-mail user
'mailnologin'   => 'Sie san net oogmeidt.',
'emailuser'     => 'E-Mail an den Benutza',
'noemailtitle'  => 'Koa E-Mail-Adress',
'emailfrom'     => 'Vo',
'emailsend'     => 'Sendn',
'emailccme'     => 'Schick a Kopie vo da E-Mail an mi selba',
'emailsenttext' => 'Eana E-Mail is verschickt wrn.',

# Watchlist
'watchlist'         => 'Beobachtungslistn',
'mywatchlist'       => 'Beobachtungslistn',
'watchlistanontext' => 'Sie miaßn si $1, um Eanane Beobachtungslistn zum seng oda Einträge auf ihr zum bearbatn.',
'watchnologin'      => 'Sie san net ogmeidt',
'addedwatch'        => 'Zua Beobachtungslistn dazuado',
'addedwatchtext'    => 'De Seitn „$1“ is zua deina [[Special:Watchlist|Beobachtungslistn]] dazuado worn.
Änderunga an dera Seitn und vo da Diskussionsseitn wern da glistet und
in da Übasicht vo de [[Special:RecentChanges|letztn Änderungen]] in Fettschrift ozoagt. 
Wennst de Seitn wieder vo deina Beobachtungslistn wegdoa mechtn, klickst auf da jeweiligen Seitn auf „nimma beobachten“.',
'watch'             => 'Beobåchtn',
'watchthispage'     => 'Seitn beobachtn',
'unwatch'           => 'nimma beobachten',
'unwatchthispage'   => 'Nimma beobåchtn',
'wlheader-enotif'   => '* Da E-Mail-Benachrichtigungsdienst is aktiviert.',
'watchlistcontains' => 'Dei Beobachtungslistn enthoit $1 {{PLURAL:$1|Seite|Seitn}}.',
'wlshowlast'        => 'Zoag de Änderunga vo de letzten $1 Stunden, $2 Tag oda $3 (in de letzten 30 Tag).',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'Beobåchtn …',
'unwatching' => 'Net beobachtn …',

'enotif_reset'       => 'Alle Seiten als besuacht markiern',
'enotif_newpagetext' => 'Des is a neie Seitn.',
'changed'            => "g'ändat",
'enotif_lastvisited' => 'Alle Änderungen auf oan Blick: $1',
'enotif_body'        => 'Liaba/e $WATCHINGUSERNAME,
de {{SITENAME}} Seitn "$PAGETITLE" is vo $PAGEEDITOR am $PAGEEDITDATE $CHANGEDORCREATED worn.
Zusammenfassung vom Bearbeiter: $PAGESUMMARY $PAGEMINOREDIT
Es wern solang koae weitern Benachrichtigungsmails gsendt, bis Sie de Seitn wieder besuacht ham. Auf Eanana Beobachtungsseitn kinnans S\' alle Benachrichtigungsmarker zsamm zrucksetzen.
             Eana {{SITENAME}} Benachrichtigungssystem
-- 
Um die Einstellungen Ihrer Beobachtungslistn anzupassen bsuachans bitte: {{fullurl:Special:Watchlist/edit}}',

# Delete
'deletepage'        => 'Seitn löschen',
'excontent'         => "Oida Inhalt: '$1'",
'exblank'           => 'Seitn is laar gwen',
'historywarning'    => 'OBACHT: Die Seitn, de Sie löschen wolln, håt a Versionsgeschichte:',
'confirmdeletetext' => 'Sie san dabei, a Seitn oda a Datei und alle zughörigen ältern Versionen
zum löschen. Bitte bestätigen Sie da dazu, dass Sie de Konsequenzen verstengan
und dass Sie in Übaeinstimmung mit de [[{{MediaWiki:Policy-url}}|Richtlinien]] handeln.',
'deletedtext'       => '„$1“ is glöscht worn. Im $2 findn Sie a Listn vo de letzten Löschungen.',
'deletedarticle'    => 'håd „[[$1]]“ glöscht',
'dellogpage'        => 'Lösch-Logbuach',
'deletionlog'       => 'Lösch-Logbuach',
'reverted'          => 'Auf a oide Version zruckgesetzt',
'deletecomment'     => 'Grund vo dera Löschung',

# Rollback
'rollbacklink' => 'Zrucksetzen',

# Protect
'protectlogpage'              => 'Seitenschutz-Logbuach',
'prot_1movedto2'              => 'håt [[$1]] nåch [[$2]] verschom',
'protect-level-autoconfirmed' => 'Sperrung fia net registrierte Benutzer',

# Restrictions (nouns)
'restriction-move' => 'verschiam',

# Undelete
'undelete'               => 'Glöschte Seitn wiedaherstelln',
'undeletehistorynoadmin' => 'De Seitn is glöscht worn. Da Grund fia de Löschung is in da Zsammafassung oogem,
genau wia Details zum letztn Benutza der de Seitn vor da Löschung bearbat håt.
Da aktuelle Text vo da glöschtn Seitn is nur fia Administratoren zum seng.',
'undeletebtn'            => 'Wiedaherstelln',
'undeletelink'           => 'wiederherstellen',
'undeletereset'          => 'Abbrecha',
'undeletedarticle'       => 'håt „[[$1]]“ wieda hergstellt',
'undeletedfiles'         => '$1 {{plural:$1|Datei|Dateien}} san wieda hergstellt worn',
'undelete-search-box'    => 'Suach nach glöschte Seitn',
'undelete-search-submit' => 'Suach',

# Namespace form on various pages
'namespace'      => 'Nãmensraum:',
'invert'         => 'Auswåih umdrahn',
'blanknamespace' => '(Seitn)',

# Contributions
'contributions' => 'Benutzerbeiträg',
'mycontris'     => 'Eigene Beiträg',
'contribsub2'   => 'Fia $1 ($2)',

'sp-contributions-newbies-sub' => 'Fia Neiling',
'sp-contributions-search'      => 'Suach nach Benutzerbeiträge',

# What links here
'whatlinkshere' => 'Links auf de Seitn',
'isredirect'    => 'Weiterleitungsseitn',

# Block/unblock
'blockip'            => 'IP-Adresse/Benutzer sperrn',
'blockip-legend'     => 'IP-Adresse/Benutzer sperrn',
'badipaddress'       => 'De IP-Adress håt a falsch Format.',
'blockipsuccesssub'  => 'De Sperre war erfoigreich',
'ipb-unblock-addr'   => '„$1“ freigem',
'ipb-unblock'        => 'IP-Adresse/Benutzer freigem',
'unblockip'          => 'IP-Adresse freigem',
'ipusubmit'          => 'Freigem',
'unblocked'          => '[[User:$1|$1]] is freigem worn',
'createaccountblock' => 'Erstellung vo Benutzakonten gsperrt',
'blocklink'          => 'sperrn',
'unblocklink'        => 'freigem',
'contribslink'       => 'Beiträge',
'autoblocker'        => "Automatische Sperre, weil s' a gmeinsame IP-Adressn mit „$1“ hernehma. Grund: „$2“.",

# Developer tools
'unlockdb'            => 'Datenbank freigem',
'unlockconfirm'       => 'Ja, i mecht de Datenbank freigem.',
'unlockbtn'           => 'Datenbank freigem',
'locknoconfirm'       => 'Sie ham des Bestätigungsfeld net markiert.',
'lockfilenotwritable' => "Die Datenbank-Sperrdatei ist net beschreibbar. Zum Sperrn oda Freigem vo da Datenbank muaß de für'n Webserver beschreibbar sei.",
'databasenotlocked'   => 'De Datenbank is net gsperrt.',

# Move page
'move-page-legend'       => 'Seitn vaschiam',
'move-watch'             => 'De Seitn beobachten',
'movepagebtn'            => 'Seitn vaschiam',
'articleexists'          => 'Unter dem Nam existiert bereits a Seitn.
Bitte nehmans an andern Nam.',
'1movedto2'              => 'håt [[$1]] nåch [[$2]] verschom',
'1movedto2_redir'        => 'håt [[$1]] nåch [[$2]] verschom und dabei a Weiterleitung überschriem',
'revertmove'             => 'zruck vaschiam',
'delete_and_move'        => 'Löschn und vaschiam',
'delete_and_move_reason' => 'glöscht, um Plåtz fia Vaschiam zum macha',
'selfmove'               => 'Ursprungs- und Zielname sand gleich; a Seitn kann net auf sich selber verschom wern.',

# Namespace 8 related
'allmessagesname'           => 'Nam',
'allmessagescurrent'        => 'Aktuella Text',
'allmessagestext'           => 'Des is a Listn vo de MediaWiki-Systemtexte.
Please visit [http://www.mediawiki.org/wiki/Localisation MediaWiki Localisation] and [http://translatewiki.net translatewiki.net] if you wish to contribute to the generic MediaWiki localisation.',
'allmessagesnotsupportedDB' => "'''Special:Allmessages''' is im Moment net möglich, wei de Datenbank offline is.",
'allmessagesmodified'       => 'Nur geänderte zoagn',

# Special:Import
'importnotext' => 'Laar oder koa Text',

# Import log
'importlogpage' => 'Import-Logbuach',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'Dei Benutzaseitn',
'tooltip-pt-preferences'          => 'Eigene Eistellunga',
'tooltip-pt-mycontris'            => 'Liste vo eigene Beiträg',
'tooltip-pt-logout'               => 'Obmeidn',
'tooltip-ca-addsection'           => 'An Kommentar zua dera Diskussion dazuagem.',
'tooltip-ca-viewsource'           => 'De Seitn is gschützt. An Quelltext kann ma oschaun.',
'tooltip-ca-history'              => 'Friarane Versionen vo dera Seitn',
'tooltip-ca-protect'              => 'De Seitn schützen',
'tooltip-ca-delete'               => 'De Seitn löschen',
'tooltip-ca-move'                 => 'De Seitn vaschiam',
'tooltip-ca-watch'                => 'De Seitn zua persönlichen Beobachtungslistn dazua doa',
'tooltip-ca-unwatch'              => 'De Seitn von da persönlichen Beobachtungslistn entferna',
'tooltip-search'                  => '{{SITENAME}} durchsuacha',
'tooltip-p-logo'                  => 'Hauptseitn',
'tooltip-n-mainpage'              => "d'Hauptseitn ãnzoang",
'tooltip-n-currentevents'         => 'Hintagrundinfoamazionen üwa aktuelle Ereignisse',
'tooltip-n-randompage'            => 'Zufällige Seitn',
'tooltip-t-contributions'         => "d'Listn vu de Beiträg vu dem Benutza ãschau",
'tooltip-t-upload'                => 'Datein aufelådn',
'tooltip-ca-nstab-main'           => 'Seitninhalt ozoagn',
'tooltip-ca-nstab-user'           => 'Benutzaseitn ãzoang',
'tooltip-ca-nstab-help'           => 'Huifseitn oozoang',
'tooltip-minoredit'               => 'De Änderung åis a klaane markian.',
'tooltip-save'                    => 'Änderunga speichan',
'tooltip-preview'                 => "a Voaschau vu de Ändarungen ãn dea Seitn. Bittschee voa'm Speichan benutzn!
Vorschau der Änderungen an dieser Seite. Bitte vor dem Speichern benutzen!",
'tooltip-diff'                    => "d'Ändarungen an dem Text in ana Taböin ãzoang",
'tooltip-compareselectedversions' => 'Unterschiede zwischn zwoa ausgewählte Versiona vo dera  Seitn vergleicha.',
'tooltip-watch'                   => 'De Seitn da persönlichn Beobachtungslistn dazua doa.',
'tooltip-recreate'                => 'Seitn nei erstelln, obwoi sie glöscht worn is.',

# Attribution
'lastmodifiedatby' => 'De Seitn is zletzt am $1 um $2 vo $3 gändert worn.', # $1 date, $2 time, $3 user
'othercontribs'    => 'Basiert auf da Arbat vo $1',
'creditspage'      => 'Seitninformationa',

# Patrolling
'markedaspatrollederrortext' => 'Sie miaßn a Seitnänderung auswähln.',

# Image deletion
'deletedrevision'    => 'Oide Version $1 glöscht.',
'filedelete-missing' => 'De Datei „$1“ ko net glöscht wern, weils es net gibt.',

# Browsing diffs
'previousdiff' => '← Zum vorigen Versionsunterschied',

# Media information
'file-nohires'         => '<small>Es gibt ka hechane Auflösung.</small>',
'show-big-image'       => 'Version in hechana Auflösung',
'show-big-image-thumb' => '<small>Greßn vu da Voaãnsicht: $1 × $2 Pixl</small>',

# Special:NewFiles
'newimages'         => 'Neie Dateien',
'newimages-summary' => 'De Spezialseitn zoagt de zletzt hochgeladena Buidl und Dateien o.',
'noimages'          => 'Koane Datein gfunden.',
'ilsubmit'          => 'Suach',

# Metadata
'metadata' => 'Metadatn',

# EXIF tags
'exif-gpsspeed' => 'Geschwindigkeit vom GPS-Empfänger',

'exif-componentsconfiguration-0' => "Gibt's net",

# 'all' in various places, this might be different for inflected languages
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

# Special:Version
'version-hook-subscribedby' => 'Aufruf vo',

# Special:SpecialPages
'specialpages' => 'Spezial-Seitn',

);
