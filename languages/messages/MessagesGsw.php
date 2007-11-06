<?php
/** Swiss German (Alemannisch)
 *
 * @addtogroup Language
 *
 * @author 80686
 * @author G - ג
 * @author Helix84
 */

$fallback = 'de';
$linkTrail = '/^([äöüßa-z]+)(.*)$/sDu';

$messages = array(
# User preference toggles
'tog-underline'               => 'Links unterstryche',
'tog-highlightbroken'         => 'Links uf lääri Themene durestryche',
'tog-justify'                 => 'Tekscht als Blocksatz',
'tog-hideminor'               => 'Cheini «chlyni Änderige» aazeige',
'tog-extendwatchlist'         => 'Erwiterti Beobachtungslischte',
'tog-usenewrc'                => 'Erwytereti «letschti Änderige» (geit nid uf allne Browser)',
'tog-numberheadings'          => 'Überschrifte outomatisch numeriere',
'tog-showtoolbar'             => 'Editier-Wärchzüüg aazeige',
'tog-editondblclick'          => 'Syte ändere mit Doppelklick i d Syte (JavaScript)',
'tog-editsection'             => 'Gleicher aazeige für ds Bearbeite vo einzelnen Absätz',
'tog-editsectiononrightclick' => 'Einzelni Absätz ändere mit Rächtsclick (Javascript)',
'tog-showtoc'                 => 'Inhaltsverzeichnis aazeige bi Artikle mit meh als drei Überschrifte',
'tog-rememberpassword'        => 'Passwort spychere (Cookie)',
'tog-editwidth'               => 'Tekschtygabfäld mit voller Breiti',
'tog-watchcreations'          => 'Sälbr gmachti Sytene beobachte',
'tog-watchdefault'            => 'Vo dir nöi gmachti oder verändereti Syte beobachte',
'tog-watchmoves'              => 'Sälbr vrschobeni Sytene beobachte',
'tog-watchdeletion'           => 'Sälbr glöschti Sytene beobachte',
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
'tog-externaleditor'          => 'Externen Editor als default',
'tog-externaldiff'            => 'Externi diff als default',
'tog-showjumplinks'           => '«Wächsle-zu»-Links ermügleche',
'tog-uselivepreview'          => 'Live preview benütze (JavaScript) (experimentell)',
'tog-forceeditsummary'        => 'Sei miers, wänn I s Zommefassungsfeld leer los',
'tog-watchlisthideown'        => 'Eigeni Änderige uf d Beobachtungslischt usblende',
'tog-watchlisthidebots'       => 'Bot-Änderige in d Beobachtungslischt usblende',
'tog-ccmeonemails'            => "Schick mr Kopie vo de Boscht wo n'ich andere schicke due.",

'underline-always'  => 'immer',
'underline-never'   => 'nie',
'underline-default' => 'Browser-Vorystellig',

'skinpreview' => '(Vorschou)',

# Dates
'sunday'    => 'Sundi',
'monday'    => 'Mändi',
'tuesday'   => 'Zischdi',
'wednesday' => 'Mittwuch',
'thursday'  => 'Durschdi',
'friday'    => 'Fridi',
'saturday'  => 'Somschdi',
'january'   => 'Jänner',
'august'    => 'Ougschte',
'september' => 'Septämber',
'november'  => 'Novämber',
'december'  => 'Dezämber',
'may'       => 'Mei',

# Bits of text used by many pages
'categories'      => 'Kategorie',
'pagecategories'  => '{{PLURAL:$1|Kategori|Kategorie}}',
'category_header' => 'Artikel in de Kategori "$1"',
'subcategories'   => 'Unterkategorie',

'mainpagetext'      => 'MediaWiki isch erfolgrich inschtalliert worre.',
'mainpagedocfooter' => 'Luege uf d [http://meta.wikimedia.org/wiki/MediaWiki_localisation Dokumentation fier d Onpassung vun de Bnutzeroberflächi] un s [http://meta.wikimedia.org/wiki/Help:Contents Bnutzerhondbuech] fier d Hilf yber d Bnutzung un s Ystelle.',

'about'          => 'Übr',
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
'mypage'         => 'Minni Syte',
'mytalk'         => 'mini Diskussionsyte',
'anontalk'       => 'Diskussionssyste vo sellere IP',

'errorpagetitle'    => 'Fähler',
'returnto'          => 'Zrügg zur Syte $1.',
'tagline'           => 'Us {{SITENAME}}',
'help'              => 'Hilf',
'search'            => 'Suech',
'searchbutton'      => 'Suech',
'history'           => 'Versione',
'history_short'     => 'Versione/Autore',
'printableversion'  => 'Druck-Aasicht',
'permalink'         => 'Bschtändigi URL',
'print'             => 'Drucke',
'edit'              => 'ändere',
'editthispage'      => 'Syte bearbeite',
'delete'            => 'lösche',
'deletethispage'    => 'Syte lösche',
'undelete_short'    => '$1 widerherstelle',
'protect'           => 'schütze',
'protectthispage'   => 'Artikel schütze',
'unprotect'         => 'nümm schütze',
'unprotectthispage' => 'Schutz ufhebe',
'newpage'           => 'Nöji Syte',
'specialpage'       => 'Spezialsyte',
'personaltools'     => 'Persönlichi Wärkzüg',
'postcomment'       => 'Kommentar abgeh',
'articlepage'       => 'Syte',
'toolbox'           => 'Wärkzügkäschtli',
'userpage'          => 'Benutzersyte',
'imagepage'         => 'Bildsyte',
'otherlanguages'    => 'Andere Schprôche',
'redirectedfrom'    => '(Witergleitet vun $1)',
'redirectpagesub'   => 'Umgleiteti Syte',
'lastmodifiedat'    => 'Letschti Änderig vo dere Syte: $2, $1<br />', # $1 date, $2 time
'viewcount'         => 'Selli Syte isch {{PLURAL:$1|eimol|$1 Mol}} bsuecht worde.',
'protectedpage'     => 'Gschützt Syte',
'jumpto'            => 'Hops zue:',
'jumptosearch'      => 'Suech',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'         => 'Übr {{GRAMMAR:akkusativ|{{SITENAME}}}}',
'aboutpage'         => 'Project:Übr {{UCFIRST:{{GRAMMAR:akkusativ|{{SITENAME}}}}}}',
'bugreportspage'    => 'Project:Kontakt',
'copyright'         => 'Der Inhalt vo dere Syte steht unter der $1.',
'copyrightpage'     => 'Project:Copyright',
'currentevents'     => 'Aktuelli Mäldige',
'currentevents-url' => 'Aktuelli Termin',
'disclaimers'       => 'Impressum',
'disclaimerpage'    => '{{ns:project}}:Impressum',
'edithelp'          => 'Ratschläg fiers Bearbeite',
'edithelppage'      => 'Project:Ändere',
'faqpage'           => 'Project:FAQ',
'helppage'          => 'Help:Hilf',
'mainpage'          => 'Houptsyte',
'policy-url'        => 'Project:Leitlinien',
'portal'            => 'Gmeinschaftsportal',
'portal-url'        => 'Project:Gemeinschafts-Portal',
'privacy'           => 'Daateschutz',
'privacypage'       => 'Project:Daateschutz',
'sitesupport'       => 'Finanzielli Hilf',
'sitesupport-url'   => 'Project:Spenden',

'badaccess' => 'Kei usreichendi Rechte.',

'versionrequired'     => 'Version $1 vun MediaWiki wird bnötigt',
'versionrequiredtext' => 'Version $1 vun MediaWiki wird bnötigt um diä Syte zue nutze. Luege [[Special:Version]]',

'retrievedfrom'           => 'Vun "$1"',
'youhavenewmessages'      => 'Du hesch $1 ($2).',
'newmessageslink'         => 'nöji Nachrichte',
'newmessagesdifflink'     => 'Unterschid',
'youhavenewmessagesmulti' => 'Si hen neui Nochrichte: $1',
'editsection'             => 'ändere',
'editsectionhint'         => 'Abschnitt ändere: $1',
'showtoc'                 => 'ufklappe',
'hidetoc'                 => 'zueklappe',
'thisisdeleted'           => 'Onluege oder widrherstelle vun $1?',
'viewdeleted'             => '$1 onluege?',
'restorelink'             => '{{PLURAL:$1|glöschti Änderig|$1 glöschti Ändrige}}',

# Short words for each namespace, by default used in the 'article' tab in monobook
'nstab-user'      => 'Benutzersyte',
'nstab-project'   => 'Projektsyte',
'nstab-image'     => 'Bildli',
'nstab-mediawiki' => 'Nochricht',
'nstab-template'  => 'Vorlag',
'nstab-help'      => 'Hilf',

# Main script and global functions
'nosuchaction'      => 'Di Aktion gibts nit',
'nosuchactiontext'  => 'Di Aktion wird vun de MediaWiki-Software nit unterschtützt',
'nosuchspecialpage' => 'Di Spezialsyte gibts nit',
'nospecialpagetext' => 'Diese Spezialseite wird von der MediaWiki-Software nicht unterstützt',

# General errors
'error'                => 'Fähler',
'databaseerror'        => 'Fähler in dr Datebonk',
'dberrortext'          => 'S het ä Syntaxfähler in dr Datenbonkabfrôg gebä.

D letzscht Datebonkabfrôg het ghiesse: "$1" us de Funktion "<tt>$2</tt>".

MySQL het den Fähler gmeldet: "<tt>$3: $4</tt>".',
'noconnect'            => 'Hab kei Vobindung zuer Datebonk uf $1 herschtelle kinne',
'nodb'                 => 'Hab d Datebonk $1 nit uswähle kinne',
'cachederror'          => 'D folgende isch ä Kopie usm Cache un möglicherwis nit aktuell.',
'laggedslavemode'      => 'Obacht: Kürzlich vorgnommene Änderunge wärdet u.U. no nit aazaigt!',
'readonly'             => 'Datebonk isch gsperrt',
'enterlockreason'      => 'Bitte gib ä Grund i, worum Datebonk gsperrt werre soll un ä Yschätzung yber d Dur vum Sperre',
'readonlytext'         => 'Diä {{SITENAME}}-Datebonk isch vorybergehend fier Neijyträg un Änderige gsperrt. Bitte vosuechs s später no mol.

Grund vun de Sperrung: $1',
'missingarticle'       => 'De Tekscht vum Artikel "$1" isch in de Datebonk nit gfunde. Des isch wahrschinlich ä Fähler in de Software. Sin so guet, un melde des m Adminischtrator, un gib de Artikelnome on.',
'readonly_lag'         => 'Datebonk isch automatisch gschperrt worre, wil d Sklavedatebonkserver ihr Meischter yhole miesse',
'internalerror'        => 'Interner Fähler',
'filecopyerror'        => 'Datei "$1" het nit noch "$2" kopiert werre kinne.',
'filerenameerror'      => 'Datei "$1" het nit noch "$2" umbnennt werre kinne.',
'filedeleteerror'      => 'Datei "$1" het nit glöscht werre kinne.',
'filenotfound'         => 'Datei "$1" isch nit gfunde worre.',
'formerror'            => 'Fähler: Ds Formular het nid chönne verarbeitet wärde',
'badarticleerror'      => 'D Aktion konn uf denne Artikel nit ongwendet werre.',
'cannotdelete'         => 'Konn d spezifiziert Syte odr Artikel nit lösche. (Isch möglicherwis schu vun ebr ondrem glöscht worre.)',
'badtitle'             => 'Ugültiger Titel',
'badtitletext'         => 'Dr Titel vun de ongfordert Syte isch ugültig gsi, leer, odr ä ugültiger Sprochlink vun nm ondre Wiki.',
'perfdisabled'         => "Leider isch die Funktion momentan usgschalte, wil's d Datebank eso starch würd belaschte, dass mer s Wiki nümm chönnti benütze.",
'perfcached'           => 'Selli Informatione chömme usem Zwüschespeicher un sin derwiil viilliecht nid aktuell.
----',
'perfcachedts'         => 'D folgendi Date stomme usm Cache un sin om $1 s letzscht mol aktualisiert worre.',
'wrong_wfQuery_params' => 'Falschi Parameter fier wfQuery()<br />
Funktion: $1<br />
Abfrog: $2',
'viewsource'           => 'Quelltext aaluege',
'viewsourcefor'        => 'fier $1',
'protectedinterface'   => 'Die Syte enthält Text fiers Sproch-Interface vun de Software un isch gsperrt, um Missbrouch zue vohindre.',
'editinginterface'     => "'''Obacht:''' Du bisch e Syten am Verändere wo zum user interface ghört. We du die Syte veränderisch, de änderet sech ds user interface o für di andere Benutzer.",
'sqlhidden'            => '(SQL-Abfrog voschteckt)',

# Login and logout pages
'logouttitle'                => 'Benutzer-Abmäldig',
'logouttext'                 => '<div align="center" style="background-color:white;">
<b>Du bisch jitz abgmäldet!</b>
</div><br />
We du jitz öppis uf der {{SITENAME}} änderisch, de wird dyni IP-Adrässen als Urhäber regischtriert u nid dy Benutzername. Du chasch di mit em glychen oder emnen andere Benutzername nöi aamälde.',
'welcomecreation'            => '<h2>Willkomme, $1!</h2>

Dys Benutzerkonto isch aagleit worde

Vergis nid, dyni [[Special:Preferences|Ystelligen]] aazpasse.',
'loginpagetitle'             => 'Benutzer-Aamelde',
'yourname'                   => 'Dii Benutzername',
'yourpassword'               => 'Basswort',
'yourpasswordagain'          => 'Basswort nommol iitipe',
'remembermypassword'         => 'Passwort spychere',
'yourdomainname'             => 'Diini Domäne',
'externaldberror'            => 'Entwedr s ligt ä Fähler bi dr extern Authentifizierung vor, odr du derfsch din externs Benutzerkonto nit aktualisiere.',
'loginproblem'               => "'''S het ä Problem mit dinre Onmeldung gäbe.'''<br />Bitte vosuechs grad nomal!",
'login'                      => 'Aamälde',
'loginprompt'                => '<small>Für di bir {{SITENAME}} aazmälde, muesch Cookies erloube!</small>',
'userlogin'                  => 'Aamälde',
'logout'                     => 'Abmälde',
'userlogout'                 => 'Abmälde',
'notloggedin'                => 'Nit aagmäldet',
'nologin'                    => 'No chei Benutzerchonto? $1',
'nologinlink'                => '»Chonto aaleege«',
'createaccount'              => 'Nöis Benutzerkonto aalege',
'gotaccount'                 => 'Du häsch scho a Chonto? $1',
'gotaccountlink'             => '»Login für beryts aagmeldete Benutzer«',
'createaccountmail'          => 'yber eMail',
'badretype'                  => 'Di beidi Passwörter stimme nit yberi.',
'userexists'                 => 'Dä Benutzername git’s scho. Bitte lis en anderen uus.',
'youremail'                  => 'Ihri E-Bost-Adräss**',
'username'                   => 'Benutzernome:',
'yourrealname'               => 'Ihre Name*',
'yourlanguage'               => 'Sproch:',
'yourvariant'                => 'Variante',
'yournick'                   => 'Spitzname (zuem Untrschriibe):',
'badsig'                     => 'Dr Syntax vun de Signatur isch ungültig; luege uffs HTML.',
'email'                      => 'E-Bost',
'prefs-help-realname'        => '* <strong>Dy ächt Name</strong> (optional): We du wosch, das dyni Änderigen uf di chöi zrüggfüert wärde.',
'loginerror'                 => 'Fähler bir Aamäldig',
'prefs-help-email'           => "* <strong>E-Bost-Adräss</strong> (optional): Dodemit chönne anderi Lüt übr Ihri Benutzersyte mitene Kontakt uffneh, ohni dass Si muen Ihri E-Bost-Adräss z'veröffentliche.
Im Fall dass Si mol Ihr Basswort vergässe hen cha Ihne au e ziitwiiligs Eimol-Basswort gschickt wärde.",
'nocookieslogin'             => '{{SITENAME}} bruucht Cookies für nen Aamäldig. Du hesch Cookies deaktiviert. Aktivier se bitte u versuech’s nomal.',
'noname'                     => 'Du muesch ä Benutzername aagebe.',
'loginsuccesstitle'          => 'Aamäldig erfolgrych',
'loginsuccess'               => "'''Du bisch jetz als \"\$1\" bi {{SITENAME}} aagmäldet.'''",
'nosuchuser'                 => 'Dr Benutzername "$1" exischtiert nit.

Yberprüf d Schribwis, odr meld dich als neijer Benutzer ô.',
'nosuchusershort'            => 'S gibt kei Benutzername „$1“. Bitte yberprüf mol d Schribwis.',
'nouserspecified'            => 'Bitte gib ä Benutzername ii.',
'wrongpassword'              => "Sell Basswort isch falsch (odr fählt). Bitte versuech's nomol.",
'wrongpasswordempty'         => 'Du hesch vagässe diin Basswort iizgeh. Bitte probiers nomol.',
'mailmypassword'             => 'Es nöis Passwort schicke',
'passwordremindertitle'      => 'Neijs Password fier {{SITENAME}}',
'passwordremindertext'       => 'Ebber mit dr IP-Adress $1 het ä neijs Passwort fier d Anmeldung bi {{SITENAME}} ongfordert.

S automatisch generiert Passwort fier de Benutzer $2 lutet jetzert: $3

Du sottsch dich jetzt onmelde un s Passwort ändere: {{fullurl:Special:Userlogin}}

Bitte ignorier diä E-Mail, wenn du s nit selber ongfordert hesch. S alt Passwort blibt witerhin gültig.',
'noemail'                    => 'Dr Benutzer "$1" het kei E-Mail-Adress ongebe.',
'passwordsent'               => 'Ä zytwilligs Passwort isch on d E-Mail-Adress vum Benutzer "$1" gschickt worre.
Bitte meld dich domit ô, wenns bekumme hesch.',
'eauthentsent'               => 'Es Bestätigungs-Mail isch a die Adrässe gschickt worde, wo du hesch aaggä. 

Bevor das wyteri Mails yber d {{SITENAME}}-Mailfunktion a die Adrässe gschickt wärde, muesch du d Instruktionen i däm Mail befolge, für z bestätige, das es würklech dys isch.',
'mailerror'                  => 'Fähler bim Sende vun de Mail: $1',
'acct_creation_throttle_hit' => 'Duet mr leid, so hän scho $1 Benutzer. Si chönne cheini meh aalege.',
'emailauthenticated'         => 'Di E-Bost-Adräss isch am $1 bschtätigt worde.',
'emailnotauthenticated'      => 'Dyni e-Mail-Adrässen isch no nid bestätiget. Drum göh di erwytereten e-Mail-Funktione no nid.
Für d Bestätigung muesch du em Link folge, wo dir isch gmailet worde. Du chasch ou e nöie söttige Link aafordere:',
'noemailprefs'               => '<strong>Du hesch kei E-Mail-Adrässen aaggä</strong>, drum sy di folgende Funktione nid müglech.',
'emailconfirmlink'           => 'E-Bost-Adräss bschtätige',
'invalidemailaddress'        => 'Diä E-Mail-Adress isch nit akzeptiert worre, wil s ä ugültigs Format ghet het. Bitte gib ä neiji Adress in nem gültige Format ii, odr tue s Feld leere.',
'accountcreated'             => 'De Benutzer isch agleit worre.',
'accountcreatedtext'         => 'De Benutzer $1 isch aagleit worre.',

# Edit page toolbar
'bold_sample'     => 'fetti Schrift',
'bold_tip'        => 'Fetti Schrift',
'italic_sample'   => 'kursiv gschribe',
'italic_tip'      => 'Kursiv gschribe',
'link_sample'     => 'Stichwort',
'extlink_sample'  => 'http://www.zumbyschpil.ch Linktekscht',
'extlink_tip'     => 'Externer Link (http:// beachte)',
'headline_sample' => 'Abschnitts-Überschrift',
'math_sample'     => 'Formel do yfüge',
'math_tip'        => 'Mathematisch Formel (LaTeX)',
'nowiki_sample'   => 'Was da inne staht wird nid formatiert',
'image_sample'    => 'Byschpil.jpg',
'image_tip'       => 'Bildvoweis',
'media_sample'    => 'Byschpil.mp3',
'media_tip'       => 'Mediedateivoweis',
'hr_tip'          => 'Horizontal Linie (sparsom vowende)',

# Edit pages
'summary'                  => 'Zämefassig',
'minoredit'                => 'Numen es birebitzeli gänderet',
'watchthis'                => 'Dä Artikel beobachte',
'savearticle'              => 'Syte spychere',
'showpreview'              => 'Vorschau aaluege',
'showdiff'                 => 'Zeig Änderige',
'anoneditwarning'          => "'''Warnig:''' Si sin nit agmolde. Ihri IP-Adrässe wird in de Gschicht vo sellem Artikel gspeicheret.",
'missingsummary'           => "'''Obacht:''' Du hesch kei Zämefassig ongebe. Wenn du erneijt uf Spacher durcksch, wird d Änderung ohni gspychert.",
'missingcommenttext'       => 'Bitte gib dinr Kommentar unte ii.',
'whitelistedittext'        => 'Sie müssen sich $1, um Artikel bearbeiten zu können.',
'whitelistreadtext'        => 'Sie müssen sich [[Special:Userlogin|hier anmelden]], um Artikel lesen zu können.',
'whitelistacctext'         => 'Um in diesem Wiki Accounts anlegen zu dürfen, müssen Sie sich [[Special:Userlogin|hier anmelden]] und die nötigen Berechtigungen haben.',
'confirmedittitle'         => 'Zuem Ändere isch e bschtätigti E-Bost-Adräss nötig.',
'confirmedittext'          => 'Si muen Ihri E-Bost-Adräss erscht bstätige bevor Si Syte go ändere chönne. Bitte setze Si in [[Special:Preferences|Ihre Iistellige]] Ihri E-Bost Adräss ii un löhn Si si pruefe.',
'accmailtitle'             => 'S Bassword isch verschickt worre.',
'accmailtext'              => 'S Basswort für "$1" isch uf $2 gschickt worde.',
'newarticletext'           => '<div id="newarticletext">
{{MediaWiki:Newarticletext/{{NAMESPACE}}}}
</div>',
'anontalkpagetext'         => "----''Sell isch e Diskussionssyte vome anonyme Benutzer wo chei Zuegang aaglegt het odr wo ihn nit bruucht. Sälleweg muen mir di numerischi IP-Adräss bruuche um ihn odr si z'identifiziere. Sone IP-Adräss cha au vo mehrere Benutzer deilt werde. Wenn Si en anonyme Benutzer sin un 's Gfuehl hen, dass do irrelevanti Kommentar an Si grichtet wärde, denn [[Special:Userlogin|lege Si sich bitte en Zuegang aa odr mälde sich aa]] go in Zuekunft Verwirrige mit andere anonyme Benutzer z'vermeide.''",
'noarticletext'            => '<div id="noarticletext">
{{MediaWiki:Noarticletext/{{NAMESPACE}}}}
</div>',
'clearyourcache'           => "'''Hywys:''' Nôch dyner Änderig muess no der Browser-Cache gleert wärde!<br />'''Mozilla/Safari/Konqueror:''' ''Strg-Umschalttaste-R'' (oder ''Umschalttaste'' drückt halte und uf’s ''Neu-Laden''-Symbol klicke), '''IE:''' ''Strg-F5'', '''Opera/Firefox:''' ''F5''",
'usercsspreview'           => "== Vorschau ihres Benutzer-CSS. ==
'''Beachten Sie:''' Nach dem Speichern müssen Sie ihrem Browser sagen, die neue Version zu laden: '''Mozilla:''' ''Strg-Shift-R'', '''IE:''' ''Strg-F5'', '''Safari:''' ''Cmd-Shift-R'', '''Konqueror:''' ''F5''.",
'userjspreview'            => "== Vorschau Ihres Benutzer-Javascript. ==
'''Beachten Sie:''' Nach dem Speichern müssen Sie ihrem Browser sagen, die neue Version zu laden: '''Mozilla:''' ''Strg-Shift-R'', '''IE:''' ''Strg-F5'', '''Safari:''' ''Cmd-Shift-R'', '''Konqueror:''' ''F5''.",
'note'                     => '<strong>Achtung: </strong>',
'previewnote'              => 'Das isch numen e Vorschau und nonig gspycheret!',
'editing'                  => 'Bearbeite vo «$1»',
'editinguser'              => 'Bearbeite vo «$1»',
'editingsection'           => 'Bearbeite vo «$1» (Absatz)',
'editconflict'             => 'Bearbeitigs-Konflikt: «$1»',
'explainconflict'          => "Öpper anders het dä Artikel gänderet, wo du ne sälber am Ändere bisch gsy.
Im obere Tekschtfäld steit der jitzig Artikel.
Im untere Tekschtfält stöh dyni Änderige.
Bitte überträg dyni Änderigen i ds obere Tekschtfäld.
We du «Syte spychere» drücksch, de wird '''nume''' der Inhalt vom obere Tekschtfäld gspycheret.",
'yourtext'                 => 'Ihre Tekscht',
'storedversion'            => 'Gspychereti Version',
'editingold'               => '<strong>Obacht: Du bisch en alti Version vo däm Artikel am Bearbeite.
Alli nöiere Versione wärden überschribe, we du uf «Syte spychere» drücksch.</strong>',
'yourdiff'                 => 'Untrschied',
'copyrightwarning2'        => 'Dängge Si dra, dass alli Änderige {{GRAMMAR:dativ {{SITENAME}}}} vo andere Benutzer wiedr gänderet odr glöscht wärde chönne. Wenn Si nit wänn, dass ander Lüt an Ihrem tekscht ummedoktere denn schicke Si ihn jetz nit ab.<br />
Si verspräche uns usserdäm, dass Si des alles selber gschriebe oder vo nere Quälle kopiert hen, wo Public Domain odr sunscht frei isch (lueg $1 für Details).
<strong>SETZE SI DO OHNI ERLAUBNIS CHEINI URHEBERRÄCHTLICH GSCHÜTZTI WÄRK INE!</strong>',
'longpagewarning'          => '<span style="color:#ff0000">WARNIG:</span> Die Syten isch $1KB groß; elteri Browser chönnte Problem ha, Sytene z bearbeite wo gröser sy als 32KB. Überleg bitte, öb du Abschnitte vo dere Syte zu eigete Sytene chönntsch usboue.',
'protectedpagewarning'     => '<span style="color:#ff0000">WARNIG:</span> Die Syten isch gsperrt worde, so das se nume Benutzer mit Sysop-Rechten chöi verändere. Bitte häb di a d [[Project:Geschützte Seiten|Regle für gschützti Syte]].',
'semiprotectedpagewarning' => "'''''Halbsperrung''': Diese Seite kann von angemeldeten Benutzern bearbeitet werden. Für nicht angemeldete oder gerade eben erst angemeldete Benutzer ist der Schreibzugang gesperrt.''",
'templatesused'            => 'Selli Vorlage wärde in sellem Artikel bruucht:',
'edittools'                => '<!-- Selle Text wird untr em "ändere"-Formular un bim "Uffelade"-Formular aagzeigt. -->',

# History pages
'revhistory'          => 'Früecheri Versione',
'currentrev'          => 'Itzigi Version',
'revisionasof'        => 'Version vo $1',
'previousrevision'    => '← Vorderi Version',
'nextrevision'        => 'Nächschti Version →',
'currentrevisionlink' => 'Itzigi Version',
'cur'                 => 'Jetz',
'next'                => 'Nächschti',
'last'                => 'vorane',
'histlegend'          => 'Du chasch zwei Versionen uswähle und verglyche.<br />
Erklärig: (aktuell) = Underschid zu jetz,
(vorane) = Underschid zur alte Version, <strong>K</strong> = chlyni Änderig',
'histfirst'           => 'Eltischti',
'histlast'            => 'Nöischti',

# Diffs
'difference'              => '(Unterschide zwüsche Versione)',
'compareselectedversions' => 'Usgwählti Versione verglyche',

# Search results
'searchresults'         => 'Suech-Ergäbnis',
'searchresulttext'      => 'Für wiiteri Informatione zuem Sueche uff {{SITENAME}} chönne Si mol uff [[{{MediaWiki:helppage}}|{{int:help}}]] luege.',
'searchsubtitle'        => 'Für d Suechaafrag «[[:$1]]»',
'searchsubtitleinvalid' => 'Für d Suechaafrag «$1»',
'prevn'                 => 'vorderi $1',
'nextn'                 => 'nächschti $1',
'viewprevnext'          => '($1) ($2) aazeige; ($3) uf ds Mal',
'powersearch'           => 'Suechi',
'powersearchtext'       => '
Suche in Namensräumen :<br />
$1<br />
$2 Zeige auch REDIRECTs   Suche nach $3 $9',
'searchdisabled'        => '<p>Die Volltextsuche wurde wegen Überlastung temporär deaktiviert. Derweil können Sie entweder folgende Google- oder Yahoo-Suche verwenden, die allerdings nicht den aktuellen Stand widerspiegeln.</p>',

# Preferences page
'preferences'        => 'Iistellige',
'prefsnologin'       => 'Nid aagmäldet',
'prefsnologintext'   => 'Du muesch [[Special:Userlogin|aagmäldet]] sy, für Benutzerystellige chönne z ändere',
'prefsreset'         => 'Du hesch itz wider Standardystellige',
'changepassword'     => 'Passwort ändere',
'datedefault'        => 'kei Aagab',
'datetime'           => 'Datum un Zit',
'prefs-personal'     => 'Benutzerdate',
'prefs-rc'           => 'Letschti Änderige',
'prefs-watchlist'    => 'Beobachtigslischte',
'prefs-misc'         => 'Verschidnigs',
'saveprefs'          => 'Änderige spychere',
'resetprefs'         => 'Änderige doch nid spychere',
'oldpassword'        => 'Alts Passwort',
'newpassword'        => 'Nöis Passwort',
'retypenew'          => 'Nöis Passwort (es zwöits Mal)',
'textboxsize'        => 'Tekscht-Ygab',
'rows'               => 'Zylene',
'columns'            => 'Spaltene',
'searchresultshead'  => 'Suech-Ergäbnis',
'resultsperpage'     => 'Träffer pro Syte',
'contextlines'       => 'Zyle pro Träffer',
'contextchars'       => 'Zeiche pro Zyle',
'recentchangescount' => 'Aazahl «letschti Änderige»',
'savedprefs'         => 'Dyni Ystellige sy gspycheret worde.',
'timezonelegend'     => 'Zytzone',
'timezonetext'       => 'Zytdifferänz i Stunden aagä zwüsche der Serverzyt u dyre Lokalzyt',
'localtime'          => 'Ortszyt',
'timezoneoffset'     => 'Unterschid¹',
'servertime'         => 'Aktuelli Serverzyt',
'guesstimezone'      => 'Vom Browser la ysetze',
'allowemail'         => 'andere Benutzer erlaube, dass si Ihne E-Bost schicke chönne',
'defaultns'          => 'Namensrüüm wo standardmäässig söll gsuecht wärde:',
'files'              => 'Bilder',

# User rights
'userrights-lookup-user'   => 'Verwalte Gruppenzugehörigkeit',
'editusergroup'            => 'Ändere vo Benutzerrächt',
'userrights-editusergroup' => 'Bearbeite Gruppenzugehörigkeit des Benutzers',
'saveusergroups'           => 'Speichere Gruppenzugehörigkeit',
'userrights-groupshelp'    => 'Wähle die Gruppen, aus denen der Benutzer entfernt oder zu denen er hinzugefügt werden soll.
Nicht selektierte Gruppen werden nicht geändert. Eine Selektion kann mit Strg + Linksklick (bzw. Ctrl + Linksklick) entfernt werden.',

# User rights log
'rightslogtext' => 'Des ischs Logbuech vun de Änderunge on Bnutzerrechte.',

# Recent changes
'recentchanges'     => 'Letschti Änderige',
'recentchangestext' => 'Uff sellere Syte chönne Si die letschte Änderige in sellem Wiki aaluege.',
'rcnote'            => 'Anzeig: <b>$1</b> Änderige; <b>$2</b> Täg   (<b>N</b> = nöji Artikel; <b>K</b> = chlyni Änderig; <b><span style="color:#ff0000">!</span></b> = unprüeft)',
'rcnotefrom'        => 'Dies sind die Änderungen seit <b>$2</b> (bis zu <b>$1</b> gezeigt).',
'rclistfrom'        => '<small>Nöji Änderige ab $1 aazeige (UTC)</small>',
'rcshowhideminor'   => 'Chlynigkeite $1',
'rcshowhideliu'     => 'Aagmoldene Benützer $1',
'rcshowhideanons'   => 'Uuaagmoldene Benützer $1',
'rcshowhidepatr'    => 'Patrulyrtes $1',
'rcshowhidemine'    => 'Eigeni Änderige $1',
'rclinks'           => 'Zeig di letschte $1 Änderige vo de vergangene $2 Täg.<br />$3',
'diff'              => 'Unterschid',
'hist'              => 'Versione',
'hide'              => 'usblände',
'show'              => 'yblände',

# Recent changes linked
'recentchangeslinked' => 'Verlinktes prüefe',

# Upload
'upload'            => 'Datei uffelade',
'uploadbtn'         => 'Bild lokal ufelade',
'uploadnologintext' => 'Sie müssen [[Special:Userlogin|angemeldet sein]], um Dateien hochladen zu können.',
'uploadtext'        => "Bruuche Si sell Formular unte go Dateie uffelade. Zuem aaluege odr fruener uffegladeni Bilder go sueche lueg uff de [[Special:Imagelist|Lischte vo uffegladene Dateie]], Uffeladige un Löschige sin au protokolliert uff [[Special:Log/upload|Uffeladige Protokoll]].

Go e Datei odr en Bild innere Syte iizbaue schriibe Si eifach ane:
* '''<nowiki>[[</nowiki>{{ns:image}}<nowiki>:file.jpg]]</nowiki>'''
* '''<nowiki>[[</nowiki>{{ns:image}}<nowiki>:file.png|alt text]]</nowiki>'''
or
* '''<nowiki>[[</nowiki>{{ns:media}}<nowiki>:file.ogg]]</nowiki>'''
go direkt e Gleich uff d Datei z'mache.",

# Image list
'imagelist'        => 'Lischte vo Bilder',
'imagelisttext'    => 'Hier ist eine Liste von $1 Bildern, sortiert $2.',
'getimagelist'     => 'Lade Bilderliste',
'ilsubmit'         => 'Suech',
'showlast'         => 'Zeige die letzten $1 Bilder, sortiert nach $2.',
'imagelinks'       => 'Bildverweise',
'linkstoimage'     => 'Di folgende Sytene händ en Link zu dem Bildli:',
'nolinkstoimage'   => 'Kein Artikel benutzt dieses Bild.',
'sharedupload'     => 'Selli Datei wird vo verschiedene Projekt bruucht.',
'noimage-linktext' => 'Lads uffe!',

# Unwatched pages
'unwatchedpages' => 'Unbeobachteti Sytene',

# List redirects
'listredirects' => 'Lischte vo Wyterleitige (Redirects)',

# Statistics
'sitestats'     => 'Statistik',
'userstats'     => 'Benützer-Statistik',
'sitestatstext' => "Zuer Ziit git's '''$2''' [[Special:Allpages|Artikel]] in {{SITENAME}}.

Insgsamt sin '''$1''' Syte in de Datebank. Selli sin au alli Sytene wo usserhalb vom Hauptnamensruum exischtiere (z.B. Diskussionssyte) odr wo cheini interne Gleicher hen odr wo au numme [[Special:Listredirects|Weiterleitige]] sin.

Insgesamt wurden '''$8''' Dateien hochgeladen.

Es isch insgsamt '''$4''' mol öbbis gänderet worde un drmit jedi Syte im Durchschnitt '''$5''' mol und '''$6''' Seitenabrufe pro Bearbeitung.

Es het '''$8''' uffegladeni Dateie.

Zuer Ziit stöhn '''$7''' Arbete zuem mache aa.",
'userstatstext' => "S git '''$1''' regischtriirte Benutzer. Dodrvo sin '''$2''' (also '''$4 %''') Administratore (lueg au uff $3).",

'disambiguationspage' => 'Template:Begriffsklärig',

'doubleredirects' => 'Doppelte Redirects',

'brokenredirects'     => 'Kaputti Wyterleitige',
'brokenredirectstext' => "Di folgende Wyterleitige füered zu Artikel wo's gar nid git.",

# Miscellaneous special pages
'nbytes'                  => '$1 {{PLURAL:$1|byte|bytes}}',
'ncategories'             => '$1 {{PLURAL:$1|Kategori|Kategorie}}',
'nlinks'                  => '$1 {{PLURAL:$1|Gleich|Gleicher}}',
'nmembers'                => '$1 {{PLURAL:$1|Syte|Sytene}}',
'nrevisions'              => '$1 {{PLURAL:$1|Revision|Revisione}}',
'nviews'                  => '$1 {{PLURAL:$1|Betrachtig|Betrachtige}}',
'lonelypages'             => 'Verwaisti Sytene',
'uncategorizedpages'      => 'Nit kategorisierte Sytene',
'uncategorizedcategories' => 'Nit kategorisierte Kategorie',
'unusedimages'            => 'Verwaiste Bilder',
'popularpages'            => 'Beliebti Artikel',
'wantedpages'             => 'Artikel wo fähle',
'mostlinked'              => 'Meistverlinke Seiten',
'allpages'                => 'alli Sytene',
'prefixindex'             => 'Alli Artikle (mit Präfix)',
'randompage'              => 'Zuefalls-Artikel',
'shortpages'              => 'Churzi Artikel',
'longpages'               => 'Langi Artikel',
'deadendpages'            => 'Artikel ohni Links («Sackgasse»)',
'listusers'               => 'Lischte vo Benutzer',
'specialpages'            => 'Spezialsytene',
'spheading'               => 'Spezial-Sytene für alli Benützer',
'newpages'                => 'Nöji Artikel',
'ancientpages'            => 'alti Sytene',
'move'                    => 'verschiebe',
'movethispage'            => 'Artikel verschiebe',

# Book sources
'booksources' => 'ISBN-Suech',

'categoriespagetext' => 'Selli Kategorie gits in dem Wiki:',
'userrights'         => 'Benutzerrechtsverwaltung',
'alphaindexline'     => 'vo $1 bis $2',

# Special:Log
'alllogstext' => 'Kombinierti Aasicht vo de Protokoll vom Ufelade, Lösche, Schütze, Spärre un de Adminischtratore.
Si chönnet d Aazeig iischränke wenn Si e Protokoll, e Benutzername odr e Sytename iigän.',
'logempty'    => 'Kei passendi Yträg gfunde.',

# Special:Allpages
'nextpage'          => 'Nächscht Syte ($1)',
'allpagesfrom'      => 'Syte aazeige vo:',
'allarticles'       => 'alli Artikel',
'allinnamespace'    => 'alli Sytene im Namensruum $1',
'allnotinnamespace' => 'alli Sytene wo nit im $1 Namensruum sin',
'allpagesprev'      => 'Füehrigs',
'allpagesnext'      => 'nächschts',
'allpagessubmit'    => 'gang',
'allpagesprefix'    => 'Alli Sytene mit em Präfix:',

# E-mail user
'mailnologin'     => 'Du bisch nid aagmäldet oder hesch keis Mail aaggä',
'mailnologintext' => 'Du muesch [[Special:Userlogin|aagmäldet sy]] und e bestätigeti e-Mail-Adrässen i dynen [[Special:Preferences|Ystelligen]] aaggä ha, für das du öpper anderem es e-Mail chasch schicke.',
'emailuser'       => 'Es Mail schrybe',
'emailpage'       => 'e-Mail ane BenutzerIn',
'emailpagetext'   => 'Öpperem, wo sälber e bestätigeti e-Mail-Adrässe het aaggä, chasch du mit däm Formular es Mail schicke. Im Absänder steit dyni eigeti e-Mail-Adrässe, so das me dir cha antworte.',
'usermailererror' => 'Das Mail-Objekt gab einen Fehler zurück:',
'noemailtitle'    => 'Kei e-Mail-Adrässe',
'noemailtext'     => 'Dä Benutzer het kei bestätigeti e-Mail-Adrässen aaggä oder wot kei e-Mails vo anderne Benutzer empfa.',
'emailfrom'       => 'Vo',
'emailto'         => 'Empfänger',
'emailsubject'    => 'Titel',
'emailmessage'    => 'E-Bost',
'emailsend'       => 'Abschicke',
'emailsent'       => 'E-Bost furtgschickt',
'emailsenttext'   => 'Dys e-Mail isch verschickt worde.',

# Watchlist
'watchlist'         => 'Beobachtigslischte',
'mywatchlist'       => 'Beobachtigslischte',
'nowatchlist'       => 'Du hesch ke Yträg uf dyre Beobachtigslischte.',
'watchnologintext'  => 'Du musst [[Special:Userlogin|angemeldet]] sein, um deine Beobachtungsliste zu bearbeiten.',
'addedwatch'        => 'zue de Beobachtigslischte drzue do',
'addedwatchtext'    => 'D Syte "[[:$1]]" stoht jetz uf Ihre [[Special:Watchlist|Beobachtigslischte]].
Neui Änderige an de Syte odr de Diskussionssyte drvo chasch jetz dört seh. Usserdem sin selli Änderige uf de [[Special:Recentchanges|letschte Änderige]] fett gschriibe, dass Si s schneller finde.

Wenn Si d Syte spöter wiedr vo de Lischte striiche wenn, denn drucke Si eifach uf "nümm beobachte".',
'watch'             => 'beobachte',
'watchthispage'     => 'Die Syte beobachte',
'unwatch'           => 'nümm beobachte',
'watchnochange'     => 'Vo den Artikle, wo du beobachtisch, isch im aazeigte Zytruum kene veränderet worde.',
'watchlist-details' => '$1 Artikel wärde beobachtet (Diskussionssyte nid zelt, aber ou beobachtet).',
'wlshowlast'        => 'Zeig di letschte $1 Stunde $2 Tage $3',

'enotif_subject'     => 'Die {{SITENAME}} Seite $PAGETITLE wurde von $PAGEEDITOR $CHANGEDORCREATED',
'enotif_lastvisited' => '$1 zeigt alle Änderungen auf einen Blick.',
'enotif_body'        => 'Liebe/r $WATCHINGUSERNAME,

d {{SITENAME}} Syte $PAGETITLE isch vom $PAGEEDITOR am $PAGEEDITDATE $CHANGEDORCREATED,
di aktuelli Version isch: $PAGETITLE_URL

$NEWPAGE

Zämmenfassig vom Autor: $PAGESUMMARY $PAGEMINOREDIT
Kontakt zuem Autor:
Mail $PAGEEDITOR_EMAIL
Wiki $PAGEEDITOR_WIKI

Es wird chei wiiteri Benochrichtigungsbost gschickt bis Si selli Syte wiedr bsueche. Uf de Beobachtigssyte chönne Si d Beobachtigsmarker zrucksetze.

             Ihr fründlichs {{SITENAME}} Benochrichtigssyschtem

---
Ihri Beobachtigslischte {{fullurl:Special:Watchlist/edit}}
Hilf zue de Benutzig gits uff {{fullurl:{{MediaWiki:helppage}}}}',

# Delete/protect/revert
'deletepage'        => 'Syte lösche',
'confirm'           => 'Bestätige',
'excontentauthor'   => "einzigen Inhalt: '$1' (bearbeitet worde nume dür '$2')",
'confirmdelete'     => 'Löschig bestätige',
'deletesub'         => '(«$1» lösche)',
'historywarning'    => '<span style="color:#ff0000">WARNUNG:</span> Die Seite die Sie zu löschen gedenken hat eine Versionsgeschichte:',
'confirmdeletetext' => 'Du bisch drann, en Artikel oder es Bild mitsamt Versionsgschicht permanänt us der Datebank z lösche.
Bitte bis dir über d Konsequänze bewusst, u bis sicher, das du di a üsi [[{{MediaWiki:policy-url}}|Leitlinien]] haltisch.',
'actioncomplete'    => 'Uftrag usgfuehrt.',
'deletedtext'       => '«$1» isch glösche worde.
Im $2 het’s e Lischte vo de letschte Löschige.',
'deletionlog'       => 'Lösch-Logbuech',
'deletecomment'     => 'Löschigsgrund',
'alreadyrolled'     => 'Cha d Änderig uf [[:$1]] wo [[User:$2|$2]] ([[User talk:$2|Talk]]) gmacht het nit zruckneh will des öbber anderscht scho gmacht het.

Di letschti Änderig het [[User:$3|$3]] ([[User talk:$3|Talk]]) gmacht.',
'revertpage'        => 'Rückgängig gmacht zuer letschte Änderig vo $1',
'protectlogtext'    => 'Dies ist eine Liste der blockierten Seiten. Siehe [[Project:Geschützte Seiten]] für mehr Informationen.',
'protectcomment'    => 'Grund der Sperrung',

# Undelete
'undeletehistorynoadmin' => 'Dieser Artikel wurde gelöscht. Der Grund für die Löschung ist in der Zusammenfassung angegeben,
genauso wie Details zum letzten Benutzer der diesen Artikel vor der Löschung bearbeitet hat.
Der aktuelle Text des gelöschten Artikels ist nur Administratoren zugänglich.',
'undeletebtn'            => 'Wiederherstellen!',
'undeletedrevisions'     => '{{PLURAL:$1|ei Revision|$1 Revisione}} wiedr zruckgholt.',

# Namespace form on various pages
'namespace'      => 'Namensruum:',
'invert'         => 'Uswahl umkehre',
'blanknamespace' => '(Haupt-)',

# Contributions
'contributions' => 'Benutzer-Byträg',
'mycontris'     => 'mini Biiträg',

# What links here
'whatlinkshere' => 'Was linkt da ane?',
'linkshere'     => 'Di folgende Sytene händ en Link wo da ane führt:',
'nolinkshere'   => 'Kein Artikel verweist hierhin.',
'istemplate'    => 'Vorlageybindig',

# Block/unblock
'blockip'         => 'Benutzer bzw. IP blockyre',
'ipbsubmit'       => 'Adresse blockieren',
'ipboptions'      => '1 Stunde:1 hour,2 Stunden:2 hours,6 Stunden:6 hours,1 Tag:1 day,3 Tage:3 days,1 Woche:1 week,2 Wochen:2 weeks,1 Monat:1 month,3 Monate:3 months,1 Jahr:1 year,Für immer:infinite',
'ipblocklist'     => 'Lischte vo blockierte IP-Adresse',
'blocklistline'   => '$1, $2 het $3 ($4) gschperrt',
'blocklink'       => 'spärre',
'contribslink'    => 'Byträg',
'blocklogpage'    => 'Sperrigs-Protokoll',
'blocklogentry'   => 'sperrt [[User:$1]] - ([[Special:Contributions/$1|Biiträg]]) für d Ziit vo: $2',
'blocklogtext'    => 'Des ischs Logbuech yber Sperrunge un Entsperrunge vun Bnutzer. Automatisch blockti IP-Adresse werre nit erfasst. Lueg au [[Special:Ipblocklist|IP-Block Lischt]] fyr ä Lischt vun gsperrti Bnutzer.',
'unblocklogentry' => 'Blockade von [[User:$1]] aufgehoben',

# Move page
'movepage'         => 'Artikel verschiebe',
'movepagetext'     => 'Mit däm Forumlar chasch du en Artikel verschiebe, u zwar mit syre komplette Versionsgschicht. Der alt Titel leitet zum nöie wyter, aber Links ufen alt Titel blyben unveränderet.',
'movepagetalktext' => "D Diskussionssyte wird mitverschobe, '''ussert:'''
*Du verschiebsch d Syten i nen andere Namensruum, oder
*es git scho ne Diskussionssyte mit däm Namen oder
*du wählsch unte d Option, se nid z verschiebe.

I söttigne Fäll müessti d Diskussionssyten allefalls vo Hand kopiert wärde.",
'movearticle'      => 'Artikel verschiebe',
'movenologin'      => 'Du bisch nid aagmäldet',
'movenologintext'  => 'Du muesch dich z’ersch [[Special:Userlogin|aamälde]] damit du die Syte chasch zügle.',
'newtitle'         => 'Zum nöie Titel',
'movepagebtn'      => 'Artikel verschiebe',
'pagemovedsub'     => 'Verschiebig erfolgrych',
'articleexists'    => 'A Syte mit sellem Name gits scho odr de Name isch ungültigt. Bitte nimm en andere.',
'movedto'          => 'verschoben uf',
'movetalk'         => 'Diskussionssyte nach Müglechkeit mitverschiebe',
'talkpagemoved'    => 'D Diskussionssyten isch mitverschobe worde.',
'1movedto2'        => '[[$1]] isch uf [[$2]] verschobe worde.',
'1movedto2_redir'  => '[[$1]] isch uf [[$2]] verschobe worre un het drbii e Wiiterleitig übrschriebe.',
'movereason'       => 'Grund',
'selfmove'         => 'Der nöi Artikelname mues en andere sy als der alt!',

# Export
'export'     => 'Sytenen exportiere',
'exporttext' => 'Sie können den Text und die Bearbeitungshistorie einer bestimmten oder einer Auswahl von Seiten nach XML exportieren. Das Ergebnis kann in ein anderes Wiki mit Mediawiki Software eingespielt werden, bearbeitet oder archiviert werden.',

# Namespace 8 related
'allmessages'               => 'Systemnochrichte',
'allmessagesname'           => 'Name',
'allmessagesdefault'        => 'Standard-Tekscht',
'allmessagescurrent'        => 'jetzige Tekscht',
'allmessagestext'           => 'Sell isch e Lischte vo alle mögliche Systemnochrichte ussem MediaWiki Namensruum.',
'allmessagesnotsupportedDB' => "'''Special:Allmessages''' cha nit bruucht wärde will '''\$wgUseDatabaseMessages''' abgschalte isch.",
'allmessagesfilter'         => 'Nochrichte nochem Name filtere:',
'allmessagesmodified'       => 'numme gänderti aazeige',

# Special:Import
'importtext' => 'Bitte speichere Si selli Syte vom Quellwiki met em Special:Export Wärkzüg ab un lade Si denn di Datei denn do uffe.',

# Tooltip help for the actions
'tooltip-pt-userpage'           => 'Myni Benutzersyte',
'tooltip-pt-mytalk'             => 'Myni Diskussionssyte',
'tooltip-pt-preferences'        => 'Myni Ystellige',
'tooltip-pt-watchlist'          => 'Lischte vo de beobachtete Syte.',
'tooltip-pt-mycontris'          => 'Lischte vo myne Byträg',
'tooltip-pt-login'              => 'Ylogge',
'tooltip-pt-logout'             => 'Uslogge',
'tooltip-ca-talk'               => 'Diskussion zum Artikelinhalt',
'tooltip-ca-edit'               => 'Syte bearbeite. Bitte vor em Spychere d Vorschou aaluege.',
'tooltip-ca-addsection'         => 'E Kommentar zu dere Syte derzuetue.',
'tooltip-ca-viewsource'         => 'Die Syte isch geschützt. Du chasch der Quelltext aaluege.',
'tooltip-ca-history'            => 'Früecheri Versione vo dere Syte.',
'tooltip-ca-protect'            => 'Seite beschütze',
'tooltip-ca-delete'             => 'Syten entsorge',
'tooltip-ca-undelete'           => 'Sodeli, da isch es wider.',
'tooltip-ca-move'               => 'Dür ds Verschiebe gits e nöie Name.',
'tooltip-ca-watch'              => 'Tue die Syten uf dyni Beobachtigslischte.',
'tooltip-ca-unwatch'            => 'Nim die Syte us dyre Beobachtungslischte furt.',
'tooltip-search'                => 'Dürchsuech das Wiki',
'tooltip-p-logo'                => 'Houptsyte',
'tooltip-n-mainpage'            => 'Gang uf d Houptsyte',
'tooltip-n-portal'              => 'Über ds Projekt, was du chasch mache, wo du was findsch',
'tooltip-n-recentchanges'       => 'Lischte vo de letschten Änderige i däm Wiki.',
'tooltip-n-randompage'          => 'E zuefälligi Syte',
'tooltip-n-help'                => 'Ds Ort zum Usefinde.',
'tooltip-n-sitesupport'         => 'Unterstütz üs',
'tooltip-t-whatlinkshere'       => 'Lischte vo allne Sytene, wo do ane linke',
'tooltip-t-recentchangeslinked' => 'Letschti Änderige vo de Syte, wo vo do verlinkt sin',
'tooltip-feed-rss'              => 'RSS-Feed für selli Syte',
'tooltip-feed-atom'             => 'Atom-Feed für selli Syte',
'tooltip-t-contributions'       => 'Lischte vo de Byträg vo däm Benutzer',
'tooltip-t-emailuser'           => 'Schick däm Benutzer e E-Bost',
'tooltip-t-specialpages'        => 'Lischte vo allne Spezialsyte',
'tooltip-ca-nstab-main'         => 'Artikelinhalt aaluege',
'tooltip-ca-nstab-user'         => 'Benutzersyte aaluege',
'tooltip-ca-nstab-media'        => 'Mediasyte aaluege',
'tooltip-ca-nstab-special'      => 'Sell isch e Spezialsyte, du chasch se nid bearbeite.',
'tooltip-ca-nstab-project'      => 'D Projektsyte aaluege',
'tooltip-ca-nstab-image'        => 'Die Bildsyten aaluege',
'tooltip-ca-nstab-mediawiki'    => 'D Systemmäldige aaluege',
'tooltip-ca-nstab-template'     => 'D Vorlag aaluege',
'tooltip-ca-nstab-help'         => 'D Hilfssyten aaluege',
'tooltip-ca-nstab-category'     => 'D Kategoryesyten aaluege',

# Attribution
'anonymous'        => 'Anonyme Benutzer uff {{SITENAME}}',
'lastmodifiedatby' => 'Diese Seite wurde zuletzt geändert um $2, $1 von $3.', # $1 date, $2 time, $3 user
'and'              => 'un',
'othercontribs'    => 'Basiert auf der Arbeit von $1.',

# Spam protection
'spamprotectiontitle'    => 'Spamschutz-Filter',
'subcategorycount'       => 'In sellere Kategori {{PLURAL:$1|isch no ei Unterkategori|sin no $1 Unterkategorie}}.',
'categoryarticlecount'   => 'In sellere Kategorie {{PLURAL:$1|isch ei Artikel|sin $1 Artikel}}.',
'listingcontinuesabbrev' => '(Forts.)',

# Math options
'mw_math_png'    => 'Immer als PNG aazeige',
'mw_math_simple' => 'Eifachs TeX als HTML aazeige, süsch als PNG',
'mw_math_html'   => 'Falls müglech als HTML aazeige, süsch als PNG',
'mw_math_source' => 'Als TeX la sy (für Tekschtbrowser)',
'mw_math_modern' => 'Empfolnigi Ystellig für modärni Browser',

# Patrolling
'markaspatrolleddiff'   => 'Als geprüft markiere',
'markaspatrolledtext'   => 'Den Artikel als geprüft markiere',
'markedaspatrolledtext' => 'Die usgwählte Artikeländerung isch als geprüft markiert worre.',

# Browsing diffs
'previousdiff' => '← Vorderi Änderig',
'nextdiff'     => 'Nächschti Änderig →',

# Media information
'mediawarning' => '
===Warnung!===
Diese Art von Datei kann böswilligen Programmcode enthalten.
Durch das Herunterladen oder Öffnen der Datei kann der Computer beschädigt werden.
Bereits das Anklicken des Links kann dazu führen dass der Browser die Datei öffnet
und unbekannter Programmcode zur Ausführung kommt.

Die Betreiber dieses Wikis können keine Verantwortung für den Inhalte
dieser Datei übernehmen. Sollte diese Datei tatsächlich böswilligen Programmcode enthalten,
sollte umgehend ein Administrator informiert werden!',
'imagemaxsize' => 'Maximali Gröössi vo de Bilder uf de Bildbeschrybigs-Sytene:',
'thumbsize'    => 'Bildvorschou-Gröössi:',

# Special:Newimages
'newimages' => 'Gallery vo noie Bilder',

# EXIF tags
'exif-orientation'       => 'Orientierung',
'exif-copyright'         => 'Copyright',
'exif-pixelxdimension'   => 'Valind image height',
'exif-fnumber'           => 'F-Wert',
'exif-isospeedratings'   => 'Filmempfindlichkeit (ISO)',
'exif-shutterspeedvalue' => 'Shutter Speed Value',
'exif-brightnessvalue'   => 'Brightness Value',

# External editor support
'edit-externally-help' => 'Siehe [http://meta.wikimedia.org/wiki/Hilfe:Externe_Editoren Installations-Anweisungen] für weitere Informationen',

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => 'alli',

# E-mail address confirmation
'confirmemail'            => 'Bschtätigung vo Ihre E-Bost-Adräss',
'confirmemail_text'       => 'Dermit du di erwyterete Mailfunktione chasch bruuche, muesch du die e-Mail-Adrässe, wo du hesch aaggä, la bestätige. Klick ufe Chnopf unte; das schickt dir es Mail. I däm Mail isch e Link; we du däm Link folgsch, de tuesch dadermit bestätige, das die e-Mail-Adrässe dyni isch.',
'confirmemail_send'       => 'Bestätigungs-Mail verschicke',
'confirmemail_sent'       => 'Es isch dir es Mail zur Adrässbestätigung gschickt worde.',
'confirmemail_sendfailed' => 'Could not send confirmation mail due to misconfigured server or invalid characters in e-mail address.',
'confirmemail_success'    => 'Dyni e-Mail-Adrässen isch bestätiget worde. Du chasch di jitz ylogge.',
'confirmemail_loggedin'   => 'Dyni e-Mail-Adrässen isch jitz bestätiget.',
'confirmemail_subject'    => '{{SITENAME}} e-Mail-Adrässbestätigung',
'confirmemail_body'       => 'Hallo

{{SITENAME}}-BenutzerIn «$2» — das bisch allwäg du — het sech vor IP-Adrässen $1 uus mit deren e-Mail-Adrässe bi {{SITENAME}} aagmäldet.

Für z bestätige, das die Adrässe würklech dir isch, u für dyni erwytereten e-Mail-Funktionen uf {{SITENAME}} yzschalte, tue bitte der folgend Link i dym Browser uuf:

$3

Falls du *nid* $2 sötsch sy, de tue dä Link bitte nid uuf.

Die Bestätigung isch nume müglech bis $4.

Fründtlechi Grüess',

# action=purge
'confirm_purge' => "Die Zwischeschpoicherung vo der Syte „{{FULLPAGENAME}}“ lösche?

\$1

<div style=\"font-size: 95%; margin-top: 2em;\">
'''''Erklärig:'''''

''Zwüschespycherige (Cache) sy temporäri Kopye vore Websyten uf dym Computer. We ne Syte us em Zwüschespycher abgrüefft wird, de bruucht das weniger Rächeleischtig füre {{SITENAME}}-Server als en Abrueff vor Originalsyte.''

''Falls du e Syte scho nes Wyli am Aaluege bisch, de het dy Computer sone Zwüschespycherig gmacht. Derby chönnt die Syten unter Umständ scho i dere Zyt liecht veraltere.''

''Ds Lösche vor Zwüschespycherig zwingt der Server, dir di aktuellschti Version vor Syte z gä!''
</div>",

);
