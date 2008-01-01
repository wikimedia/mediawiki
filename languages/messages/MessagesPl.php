<?php
/** Polish (Polski)
 *
 * @addtogroup Language
 *
 * @author Derbeth
 * @author Wpedzich
 * @author Stv
 * @author Siebrand
 * @author לערי ריינהארט
 * @author Nike
 * @author Sp5uhe
 */

$namespaceNames = array(
	NS_MEDIA		=> 'Media',
	NS_SPECIAL		=> 'Specjalna',
	NS_MAIN			=> '',
	NS_TALK			=> 'Dyskusja',
	NS_USER			=> 'Użytkownik',
	NS_USER_TALK		=> 'Dyskusja_użytkownika',
	# NS_PROJECT set by $wgMetaNamespace
	NS_PROJECT_TALK		=> 'Dyskusja_$1',
	NS_IMAGE		=> 'Grafika',
	NS_IMAGE_TALK		=> 'Dyskusja_grafiki',
	NS_MEDIAWIKI		=> 'MediaWiki',
	NS_MEDIAWIKI_TALK	=> 'Dyskusja_MediaWiki',
	NS_TEMPLATE		=> 'Szablon',
	NS_TEMPLATE_TALK	=> 'Dyskusja_szablonu',
	NS_HELP			=> 'Pomoc',
	NS_HELP_TALK		=> 'Dyskusja_pomocy',
	NS_CATEGORY		=> 'Kategoria',
	NS_CATEGORY_TALK	=> 'Dyskusja_kategorii'
);

$skinNames = array(
	'standard'	=> 'Standardowa',
);

$dateFormats = array(
	'mdy time' => 'H:i',
	'mdy date' => 'M j, Y',
	'mdy both' => 'H:i, M j, Y',

	'dmy time' => 'H:i',
	'dmy date' => 'j M Y',
	'dmy both' => 'H:i, j M Y',

	'ymd time' => 'H:i',
	'ymd date' => 'Y M j',
	'ymd both' => 'H:i, Y M j',
);

$fallback8bitEncoding = 'iso-8859-2';
$separatorTransformTable = array(
	',' => "\xc2\xa0", // @bug 2749
	'.' => ','
);
$linkTrail = '/^([a-zęóąśłżźćńĘÓĄŚŁŻŹĆŃ]+)(.*)$/sDu';

$specialPageAliases = array(
	'DoubleRedirects'		=> array( 'Podwójne_przekierowania' ),
	'BrokenRedirects'		=> array( 'Zerwane_przekierowania' ),
	'Disambiguations'		=> array( 'Ujednoznacznienia' ),
	'Userlogin'			=> array( 'Zaloguj' ),
	'Userlogout'			=> array( 'Wyloguj' ),
	'Preferences'			=> array( 'Preferencje' ),
	'Watchlist'			=> array( 'Obserwowane' ),
	'Recentchanges'			=> array( 'Ostatnie_zmiany', 'OZ' ),
	'Upload'			=> array( 'Prześlij' ),
	'Imagelist'			=> array( 'Pliki' ),
	'Newimages'			=> array( 'Nowe_pliki' ),
	'Listusers'			=> array( 'Użytkownicy' ),
	'Statistics'			=> array( 'Statystyka', 'Statystyki' ),
	'Randompage'			=> array( 'Losowa_strona', 'Losowa' ),
	'Lonelypages'			=> array( 'Porzucone_strony' ),
	'Uncategorizedpages'		=> array( 'Nieskategoryzowane_strony' ),
	'Uncategorizedcategories'	=> array( 'Nieskategoryzowane_kategorie' ),
	'Uncategorizedimages'		=> array( 'Nieskategoryzowane_pliki' ),
	'Unusedcategories'		=> array( 'Nieużywane_kategorie' ),
	'Unusedimages'			=> array( 'Nieużywane_pliki' ),
	'Wantedpages'			=> array( 'Potrzebne_strony' ),
	'Wantedcategories'		=> array( 'Potrzebne_kategorie' ),
	'Mostlinked'			=> array( 'Najczęściej_linkowane' ),
	'Mostlinkedcategories'		=> array( 'Najczęściej_linkowane_kategorie' ),
	'Mostcategories'		=> array( 'Najwięcej_kategorii' ),
	'Mostimages'			=> array( 'Najczęściej_linkowane_pliki' ),
	'Mostrevisions'			=> array( 'Najwięcej_edycji', 'Najczęściej_edytowane' ),
	'Fewestrevisions'		=> array( 'Najmniej_edycji' ),
	'Shortpages'			=> array( 'Najkrótsze_strony' ),
	'Longpages'			=> array( 'Najdłuższe_strony' ),
	'Newpages'			=> array( 'Nowe_strony' ),
	'Ancientpages'			=> array( 'Stare_strony' ),
	'Deadendpages'			=> array( 'Bez_linków' ),
	'Protectedpages'		=> array( 'Zabezpieczone_strony' ),
	'Allpages'			=> array( 'Wszystkie_strony' ),
	'Prefixindex'			=> array( 'Strony_według_prefiksu' ) ,
	'Ipblocklist'			=> array( 'Zablokowani' ),
	'Specialpages'			=> array( 'Strony_specjalne' ),
	'Contributions'			=> array( 'Wkład' ),
	'Emailuser'			=> array( 'E-Mail' ),
	'Whatlinkshere'			=> array( 'Linkujące' ),
	'Recentchangeslinked'		=> array( 'Zmiany_w_linkujących' ),
	'Movepage'			=> array( 'Przenieś' ),
	'Booksources'			=> array( 'Książki' ),
	'Categories'			=> array( 'Kategorie' ),
	'Export'			=> array( 'Eksport' ),
	'Version'			=> array( 'Wersja' ),
	'Allmessages'			=> array( 'Wszystkie_komunikaty' ),
	'Log'				=> array( 'Rejestr', 'Logi' ),
	'Blockip'			=> array( 'Blokuj' ),
	'Undelete'			=> array( 'Odtwórz' ),
	'Import'			=> array( 'Import' ),
	'Lockdb'			=> array( 'Zablokuj_bazę' ),
	'Unlockdb'			=> array( 'Odblokuj_bazę' ),
	'Userrights'			=> array( 'Uprawnienia', 'Prawa_użytkowników' ),
	'MIMEsearch'			=> array( 'Wyszukiwanie_MIME' ),
	'Unwatchedpages'		=> array( 'Nieobserwowane_strony' ),
	'Listredirects'			=> array( 'Przekierowania' ),
	'Revisiondelete'		=> array( 'Usuń_wersję' ),
	'Unusedtemplates'		=> array( 'Nieużywane_szablony' ),
	'Randomredirect'		=> array( 'Losowe_przekierowanie' ),
	'Mypage'			=> array( 'Moja_strona' ),
	'Mytalk'			=> array( 'Moja_dyskusja' ),
	'Mycontributions'		=> array( 'Mój_wkład' ),
	'Listadmins'			=> array( 'Administratorzy' ),
	'Search'			=> array( 'Szukaj' ),
	'Resetpass'			=> array( 'Resetuj_hasło' ),
	'Withoutinterwiki'		=> array( 'Strony_bez_interwiki' ),
);

$messages = array(
# User preference toggles
'tog-underline'               => 'Podkreślenie linków:',
'tog-highlightbroken'         => '<a href="" class="new">Podświetl</a> linki pustych stron (alternatywa: znak zapytania<a href="" class="internal">?</a>).',
'tog-justify'                 => 'Wyrównuj tekst w akapitach do obu stron',
'tog-hideminor'               => 'Ukryj drobne zmiany w "Ostatnich zmianach"',
'tog-extendwatchlist'         => 'Rozszerzona lista obserwowanych',
'tog-usenewrc'                => 'Rozszerzenie ostatnich zmian (JavaScript)',
'tog-numberheadings'          => 'Automatyczna numeracja nagłówków',
'tog-showtoolbar'             => 'Pokaż pasek narzędzi (JavaScript)',
'tog-editondblclick'          => 'Podwójne kliknięcie rozpoczyna edycję (JavaScript)',
'tog-editsection'             => 'Możliwość edycji poszczególnych sekcji strony',
'tog-editsectiononrightclick' => 'Kliknięcie prawym klawiszem myszy na tytule sekcji<br />rozpoczyna jej edycję (JavaScript)',
'tog-showtoc'                 => 'Pokaż spis treści (na stronach zawierających więcej niż 3 nagłówki)',
'tog-rememberpassword'        => 'Pamiętaj hasło między sesjami',
'tog-editwidth'               => 'Obszar edycji o pełnej szerokości',
'tog-watchcreations'          => 'Dodaj tworzone przeze mnie strony do obserwowanych',
'tog-watchdefault'            => 'Obserwuj strony, które będę edytować',
'tog-watchmoves'              => 'Obserwuj strony, które będę przenosić',
'tog-watchdeletion'           => 'Dodaj strony, które usuwam, do listy obserwowanych',
'tog-minordefault'            => 'Wszystkie zmiany zaznaczaj domyślnie jako drobne',
'tog-previewontop'            => 'Pokazuj podgląd przed obszarem edycji',
'tog-previewonfirst'          => 'Pokaż podgląd strony podczas pierwszej edycji',
'tog-nocache'                 => 'Wyłącz pamięć podręczną',
'tog-enotifwatchlistpages'    => 'Wyślij e-mail kiedy obserwowana przeze mnie strona ulegnie zmianie',
'tog-enotifusertalkpages'     => 'Wyślij e-mail kiedy moja strona dyskusji ulegnie zmianie',
'tog-enotifminoredits'        => 'Wyślij e-mail także w przypadku drobnych zmian na stronach',
'tog-enotifrevealaddr'        => 'Nie ukrywaj mojego adresu e-mail w powiadomieniach',
'tog-shownumberswatching'     => 'Pokaż liczbę obserwujących użytkowników',
'tog-fancysig'                => 'Podpis bez automatycznego linku',
'tog-externaleditor'          => 'Domyślnie używaj zewnętrznego edytora',
'tog-externaldiff'            => 'Domyślnie używaj zewnętrznego programu pokazującego zmiany',
'tog-showjumplinks'           => 'Włącz odnośniki "skocz do"',
'tog-uselivepreview'          => 'Używaj dynamicznego podglądu (JavaScript) (eksperymentalny)',
'tog-forceeditsummary'        => 'Informuj o niewypełnieniu opisu zmian',
'tog-watchlisthideown'        => 'Ukryj moje edycje w obserwowanych',
'tog-watchlisthidebots'       => 'Ukryj edycje botów w obserwowanych',
'tog-watchlisthideminor'      => 'Ukryj drobne zmiany w obserwowanych',
'tog-ccmeonemails'            => 'Przesyłaj mi kopie wiadomości wysłanych do innych użytkowników',
'tog-diffonly'                => 'Nie pokazuj treści stron pod porównaniami zmian',

'underline-always'  => 'Zawsze',
'underline-never'   => 'Nigdy',
'underline-default' => 'Według ustawień przeglądarki',

'skinpreview' => '(podgląd)',

# Dates
'sunday'        => 'niedziela',
'monday'        => 'poniedziałek',
'tuesday'       => 'wtorek',
'wednesday'     => 'środa',
'thursday'      => 'czwartek',
'friday'        => 'piątek',
'saturday'      => 'sobota',
'sun'           => 'Nie',
'mon'           => 'Pon',
'tue'           => 'Wto',
'wed'           => 'Śro',
'thu'           => 'Czw',
'fri'           => 'Pią',
'sat'           => 'Sob',
'january'       => 'styczeń',
'february'      => 'luty',
'march'         => 'marzec',
'april'         => 'kwiecień',
'may_long'      => 'maj',
'june'          => 'czerwiec',
'july'          => 'lipiec',
'august'        => 'sierpień',
'september'     => 'wrzesień',
'october'       => 'październik',
'november'      => 'listopad',
'december'      => 'grudzień',
'january-gen'   => 'stycznia',
'february-gen'  => 'lutego',
'march-gen'     => 'marca',
'april-gen'     => 'kwietnia',
'may-gen'       => 'maja',
'june-gen'      => 'czerwca',
'july-gen'      => 'lipca',
'august-gen'    => 'sierpnia',
'september-gen' => 'września',
'october-gen'   => 'października',
'november-gen'  => 'listopada',
'december-gen'  => 'grudnia',
'jan'           => 'sty',
'feb'           => 'lut',
'mar'           => 'mar',
'apr'           => 'kwi',
'may'           => 'maj',
'jun'           => 'cze',
'jul'           => 'lip',
'aug'           => 'sie',
'sep'           => 'wrz',
'oct'           => 'paź',
'nov'           => 'lis',
'dec'           => 'gru',

# Bits of text used by many pages
'categories'            => 'Kategorie',
'pagecategories'        => '{{PLURAL:$1|Kategoria|Kategorie}}',
'category_header'       => 'Artykuły w kategorii "$1"',
'subcategories'         => 'Podkategorie',
'category-media-header' => 'Pliki w kategorii "$1"',
'category-empty'        => "''W tej kategorii nie ma obecnie artykułów ani plików.''",

'mainpagetext'      => "<big>'''Instalacja MediaWiki powiodła się.'''</big>",
'mainpagedocfooter' => 'Zobacz [http://meta.wikimedia.org/wiki/Help:Contents przewodnik użytkownika] w celu uzyskania informacji o działaniu oprogramowania wiki.

== Na początek ==
* [http://www.mediawiki.org/wiki/Manual:Configuration_settings Lista ustawień konfiguracyjnych]
* [http://www.mediawiki.org/wiki/Manual:FAQ MediaWiki FAQ]
* [http://lists.wikimedia.org/mailman/listinfo/mediawiki-announce Komunikaty o nowych wersjach MediaWiki]',

'about'          => 'O serwisie',
'article'        => 'Artykuł',
'newwindow'      => '(otwiera się w nowym oknie)',
'cancel'         => 'Anuluj',
'qbfind'         => 'Znajdź',
'qbbrowse'       => 'Przeglądanie',
'qbedit'         => 'Edycja',
'qbpageoptions'  => 'Ta strona',
'qbpageinfo'     => 'Kontekst',
'qbmyoptions'    => 'Moje strony',
'qbspecialpages' => 'Strony specjalne',
'moredotdotdot'  => 'Więcej...',
'mypage'         => 'Moja strona',
'mytalk'         => 'Moja dyskusja',
'anontalk'       => 'Dyskusja tego IP',
'navigation'     => 'Nawigacja',

# Metadata in edit box
'metadata_help' => 'Metadane:',

'errorpagetitle'    => 'Błąd',
'returnto'          => 'Wróć do strony $1.',
'tagline'           => '{{SITENAME}}',
'help'              => 'Pomoc',
'search'            => 'Szukaj',
'searchbutton'      => 'Szukaj',
'go'                => 'Przejdź',
'searcharticle'     => 'Przejdź',
'history'           => 'Historia strony',
'history_short'     => 'Historia',
'updatedmarker'     => 'zmienione od ostatniej wizyty',
'info_short'        => 'Informacja',
'printableversion'  => 'Wersja do druku',
'permalink'         => 'Bezpośredni link',
'print'             => 'Drukuj',
'edit'              => 'edytuj',
'editthispage'      => 'Edytuj tę stronę',
'delete'            => 'Usuń',
'deletethispage'    => 'Usuń tę stronę',
'undelete_short'    => 'Odtwórz {{PLURAL:$1|jedną wersję|$1 wersji}}',
'protect'           => 'Zabezpiecz',
'protect_change'    => 'zmień',
'protectthispage'   => 'Zabezpiecz tę stronę',
'unprotect'         => 'Odbezpiecz',
'unprotectthispage' => 'Odbezpiecz tę stronę',
'newpage'           => 'Nowa strona',
'talkpage'          => 'Dyskusja',
'talkpagelinktext'  => 'Dyskusja',
'specialpage'       => 'Strona specjalna',
'personaltools'     => 'Osobiste',
'postcomment'       => 'Skomentuj',
'articlepage'       => 'Strona artykułu',
'talk'              => 'Dyskusja',
'views'             => 'Widok',
'toolbox'           => 'Narzędzia',
'userpage'          => 'Strona użytkownika',
'projectpage'       => 'Strona projektu',
'imagepage'         => 'Strona grafiki',
'mediawikipage'     => 'Strona komunikatu',
'templatepage'      => 'Strona szablonu',
'viewhelppage'      => 'Strona pomocy',
'categorypage'      => 'Strona kategorii',
'viewtalkpage'      => 'Strona dyskusji',
'otherlanguages'    => 'W innych językach',
'redirectedfrom'    => '(Przekierowano z $1)',
'redirectpagesub'   => 'Strona przekierowująca',
'lastmodifiedat'    => 'Tę stronę ostatnio zmodyfikowano $2, $1.', # $1 date, $2 time
'viewcount'         => 'Tę stronę obejrzano {{plural:$1|jeden raz|$1 razy}}.',
'protectedpage'     => 'Strona zabezpieczona',
'jumpto'            => 'Skocz do:',
'jumptonavigation'  => 'nawigacji',
'jumptosearch'      => 'wyszukiwanie',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'         => 'O serwisie {{SITENAME}}',
'aboutpage'         => 'Project:O serwisie',
'bugreports'        => 'Raport o błędach',
'bugreportspage'    => 'Project:Błędy',
'copyright'         => 'Tekst udostępniany na licencji $1.',
'copyrightpagename' => 'prawami autorskimi serwisu {{SITENAME}}',
'copyrightpage'     => '{{ns:project}}:Prawa_autorskie',
'currentevents'     => 'Bieżące wydarzenia',
'currentevents-url' => 'Project:Bieżące wydarzenia',
'disclaimers'       => 'Informacje prawne',
'disclaimerpage'    => 'Project:Informacje_prawne',
'edithelp'          => 'Pomoc w edycji',
'edithelppage'      => 'Help:Jak edytować stronę',
'faq'               => 'FAQ',
'faqpage'           => 'Project:FAQ',
'helppage'          => 'Help:Pomoc',
'mainpage'          => 'Strona główna',
'policy-url'        => 'Project:Zasady',
'portal'            => 'Portal użytkowników',
'portal-url'        => 'Project:Portal użytkowników',
'privacy'           => 'Zasady ochrony prywatności',
'privacypage'       => 'Project:Zasady ochrony prywatności',
'sitesupport'       => 'Dary pieniężne',
'sitesupport-url'   => 'Project:Dary pieniężne',

'badaccess'        => 'Nieprawidłowe uprawnienia',
'badaccess-group0' => 'Nie masz uprawnień wymaganych do wykonania tej operacji.',
'badaccess-group1' => 'Wykonywanie tej operacji zostało ograniczone do użytkowników w grupie $1.',
'badaccess-group2' => 'Wykonywanie tej operacji zostało ograniczone do użytkowników w jednej z grup $1.',
'badaccess-groups' => 'Wykonywanie tej operacji zostało ograniczone do użytkowników w jednej z grup $1.',

'versionrequired'     => 'Wymagana MediaWiki w wersji $1',
'versionrequiredtext' => 'Wymagane jest MediaWiki w wersji $1 aby skorzystać z tej strony. Zobacz [[Special:Version]]',

'ok'                      => 'OK',
'retrievedfrom'           => 'Źródło: "$1"',
'youhavenewmessages'      => 'Masz $1 ($2).',
'newmessageslink'         => 'nowe wiadomości',
'newmessagesdifflink'     => 'różnica z poprzednią wersją',
'youhavenewmessagesmulti' => 'Masz nowe wiadomości: $1',
'editsection'             => 'edytuj',
'editold'                 => 'edytuj',
'editsectionhint'         => 'Edytuj sekcję: $1',
'toc'                     => 'Spis treści',
'showtoc'                 => 'pokaż',
'hidetoc'                 => 'ukryj',
'thisisdeleted'           => 'Pokaż/odtwórz $1',
'viewdeleted'             => 'Zobacz $1',
'restorelink'             => '{{PLURAL:$1|jedną skasowaną wersję|skasowane wersje (w sumie $1)}}',
'feedlinks'               => 'Kanały:',
'feed-invalid'            => 'Niewłaściwy typ kanału informacyjnego.',
'site-rss-feed'           => 'Kanał RSS $1',
'site-atom-feed'          => 'Kanał Atom $1',
'page-rss-feed'           => 'Kanał RSS "$1"',
'page-atom-feed'          => 'Kanał Atom "$1"',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Artykuł',
'nstab-user'      => 'Strona użytkownika',
'nstab-media'     => 'Media',
'nstab-special'   => 'Strona specjalna',
'nstab-project'   => 'Strona projektu',
'nstab-image'     => 'Plik',
'nstab-mediawiki' => 'Komunikat',
'nstab-template'  => 'Szablon',
'nstab-help'      => 'Strona pomocy',
'nstab-category'  => 'Kategoria',

# Main script and global functions
'nosuchaction'      => 'Nie ma takiej operacji',
'nosuchactiontext'  => 'Oprogramowanie nie rozpoznaje operacji takiej jak podana w URL',
'nosuchspecialpage' => 'Nie ma takiej strony specjalnej',
'nospecialpagetext' => 'Oprogramowanie nie rozpoznaje takiej specjalnej strony. Listę stron specjalnych znajdziesz na [[{{ns:special}}:Specialpages]]',

# General errors
'error'                => 'Błąd',
'databaseerror'        => 'Błąd bazy danych',
'dberrortext'          => 'Wystąpił błąd składni w zapytaniu do bazy danych.
Ostatnie, nieudane zapytanie to:
<blockquote><tt>$1</tt></blockquote>
wysłane przez funkcję "<tt>$2</tt>".
MySQL zgłosił błąd "<tt>$3: $4</tt>".',
'dberrortextcl'        => 'Wystąpił błąd składni w zapytaniu do bazy danych.
Ostatnie, nieudane zapytanie to:
"$1"
wywołane zostało przez funkcję "$2".
MySQL zgłosił błąd "$3: $4"',
'noconnect'            => 'Przepraszamy! {{SITENAME}} ma chwilowo problemy techniczne. Nie można połączyć się z serwerem bazy danych.<br />$1',
'nodb'                 => 'Nie można odnaleźć bazy danych $1',
'cachederror'          => 'Poniższy tekst strony jest kopią znajdującą się w pamięci podręcznej i może być już nieaktualny.',
'laggedslavemode'      => 'Uwaga: Ta strona może nie zawierać najnowszych aktualizacji.',
'readonly'             => 'Baza danych jest zablokowana',
'enterlockreason'      => 'Podaj powód zablokowania bazy oraz szacunkowy czas jej odblokowania',
'readonlytext'         => 'Baza danych jest w tej chwili zablokowana
- nie można wprowadzać nowych artykułów ani modyfikować istniejących. Powodem
są prawdopodobnie czynności administracyjne. Po ich zakończeniu przywrócona
zostanie pełna funkcjonalność bazy.
Administrator, który zablokował bazę, podał następujące wyjaśnienie:<br /> $1',
'missingarticle'       => 'Oprogramowanie nie odnalazło tekstu strony, która powinna się znajdować w bazie, tzn. strony "$1".

Zazwyczaj zdarza się to, gdy wybrane zostanie łącze do skasowanej strony, np. w starszej wersji innej ze stron.

Inne okoliczności świadczyłyby o tym, że w oprogramowaniu jest błąd. W takim przypadku zgłoś, proszę, ten fakt
administratorowi podając także powyższy adres.',
'readonly_lag'         => 'Baza danych została automatycznie zablokowana na czas potrzebny na synchronizację zmian między serwerem głównym i serwerami pośredniczącymi.',
'internalerror'        => 'Błąd wewnętrzny',
'internalerror_info'   => 'Błąd wewnętrzny: $1',
'filecopyerror'        => 'Nie można skopiować pliku "$1" do "$2".',
'filerenameerror'      => 'Nie można zmienić nazwy pliku "$1" na "$2".',
'filedeleteerror'      => 'Nie można skasować pliku "$1".',
'directorycreateerror' => 'Nie udało się utworzyć katalogu "$1".',
'filenotfound'         => 'Nie można znaleźć pliku "$1".',
'fileexistserror'      => 'Nie udało się zapisać do pliku "$1": plik istnieje',
'unexpected'           => 'Niespodziewana wartość: "$1"="$2".',
'formerror'            => 'Błąd: nie można wysłać formularza',
'badarticleerror'      => 'Dla tej strony ta operacja nie może być wykonana.',
'cannotdelete'         => 'Nie można skasować podanej strony lub grafiki.',
'badtitle'             => 'Niepoprawny tytuł',
'badtitletext'         => 'Podano niepoprawny tytuł strony. Prawdopodobnie zawiera znaki, których użycie jest zabronione lub jest pusty.',
'perfdisabled'         => 'Przepraszamy! By odciążyć serwer w godzinach szczytu czasowo zablokowaliśmy wykonanie tej czynności.',
'perfcached'           => 'Poniższe dane są kopią z pamięci podręcznej i mogą nie być do końca aktualne.',
'perfcachedts'         => 'Poniższe dane są kopią z pamięci podręcznej i zostały uaktualnione $1.',
'querypage-no-updates' => 'Uaktualnienia dla tej strony są obecnie wyłączone. Znajdujące się tutaj dane nie zostaną odświeżone.',
'wrong_wfQuery_params' => 'Nieprawidłowe parametry przekazane do wfQuery()<br />
Funkcja: $1<br />
Zapytanie: $2',
'viewsource'           => 'Tekst źródłowy',
'viewsourcefor'        => 'dla $1',
'actionthrottled'      => 'Akcja wstrzymana',
'protectedpagetext'    => 'Wyłączono możliwość edycji tej strony.',
'viewsourcetext'       => 'Tekst źródłowy strony można w dalszym ciągu podejrzeć i skopiować.',
'protectedinterface'   => 'Ta strona zawiera tekst interfejsu oprogramowania, dlatego możliwość jej edycji została zablokowana.',
'editinginterface'     => "'''Ostrzeżenie:''' Edytujesz stronę, która zawiera tekst interfejsu oprogramowania. Zmiany na tej stronie zmienią wygląd interfejsu dla innych użytkowników.",
'sqlhidden'            => '(ukryto zapytanie SQL)',
'cascadeprotected'     => 'Ta strona została zabezpieczona przed edycją, ponieważ jest ona zawarta na {{PLURAL:$1|następującej stronie, która została zabezpieczona|następujących stronach, które zostały zabezpieczone}} z włączoną opcją dziedziczenia:
$2',
'namespaceprotected'   => "Brak uprawnień do edytowania stron w przestrzeni nazw '''$1'''.",
'customcssjsprotected' => 'Nie masz uprawnień do dokonywania edycji na tej stronie, gdyż zawiera ona ustawienia osobiste innego użytkownika.',
'ns-specialprotected'  => 'Nie można edytować stron w przestrzeni nazw {{ns:special}}.',
'titleprotected'       => 'Utworzenie strony o tej nazwie zostało zablokowane przez [[User:$1|$1]], ponieważ <i>$2</i>.',

# Login and logout pages
'logouttitle'                => 'Wylogowanie użytkownika',
'logouttext'                 => '<strong>Wylogowano Cię</strong>.<br />Możesz kontynuować pracę jako niezarejestrowany użytkownik albo zalogować się ponownie jako ten sam lub inny użytkownik.',
'welcomecreation'            => '== Witaj, $1! ==

Właśnie utworzyliśmy dla Ciebie konto. Nie zapomnij dostosować [[{{ns:special}}:Preferences|preferencji]].',
'loginpagetitle'             => 'Logowanie',
'yourname'                   => 'Login',
'yourpassword'               => 'Hasło',
'yourpasswordagain'          => 'Powtórz hasło',
'remembermypassword'         => 'Zapamiętaj moje hasło na tym komputerze',
'yourdomainname'             => 'Twoja domena',
'externaldberror'            => 'Wystąpił błąd zewnętrznej bazy autentyfikacyjnej lub nie posiadasz uprawnień koniecznych do aktualizacji zewnętrznego konta.',
'loginproblem'               => '<b>Wystąpił problem przy próbie zalogowania się.</b><br />Spróbuj ponownie!',
'login'                      => 'Zaloguj mnie',
'loginprompt'                => 'Musisz mieć włączone cookies by móc się zalogować.',
'userlogin'                  => 'Logowanie / rejestracja',
'logout'                     => 'Wyloguj mnie',
'userlogout'                 => 'Wylogowanie',
'notloggedin'                => 'Brak logowania',
'nologin'                    => 'Nie masz konta? $1.',
'nologinlink'                => 'Zarejestruj się',
'createaccount'              => 'Załóż nowe konto',
'gotaccount'                 => 'Masz już konto? $1.',
'gotaccountlink'             => 'Zaloguj się',
'createaccountmail'          => 'przez e-mail',
'badretype'                  => 'Wprowadzone hasła różnią się między sobą.',
'userexists'                 => 'Wybrana przez Ciebie nazwa użytkownika jest już zajęta. Wybierz, proszę, inną.',
'youremail'                  => 'Twój E-mail *',
'username'                   => 'Nazwa użytkownika:',
'uid'                        => 'ID użytkownika:',
'yourrealname'               => 'Imię i nazwisko *',
'yourlanguage'               => 'Język interfejsu',
'yourvariant'                => 'Wariant',
'yournick'                   => 'Twój podpis',
'badsig'                     => 'Błędny podpis, sprawdź tagi HTML.',
'badsiglength'               => 'Nazwa użytkownika jest zbyt długa. Maksymalna jej długość to $1 znaków.',
'email'                      => 'E-mail',
'prefs-help-realname'        => '* Imię i nazwisko (opcjonalnie): jeśli zdecydujesz się je podać, zostaną użyte, aby zapewnić Twojej pracy atrybucję.',
'loginerror'                 => 'Błąd logowania',
'prefs-help-email'           => '* E-mail (opcjonalnie): Podanie e-maila pozwala innym skontaktować się z tobą za pośrednictwem twojej strony użytkownika
lub twojej strony dyskusji bez potrzeby ujawniania twoich danych identyfikacyjnych.',
'prefs-help-email-required'  => 'Wymagany jest adres e-mail.',
'nocookiesnew'               => 'Konto użytkownika zostało utworzone, ale nie jesteś zalogowany. {{SITENAME}} używa ciasteczek do logowania. Masz wyłączone ciasteczka. Żeby się zalogować odblokuj ciasteczka i podaj nazwę i hasło swojego konta.',
'nocookieslogin'             => '{{SITENAME}} używa ciasteczek żeby zalogować użytkownika. Masz zablokowaną obsługę ciasteczek. Spróbuj ponownie po ich odblokowaniu.',
'noname'                     => 'To nie jest poprawna nazwa użytkownika.',
'loginsuccesstitle'          => 'Udane logowanie',
'loginsuccess'               => 'Zalogowano Cię do serwisu {{SITENAME}} jako "$1".',
'nosuchuser'                 => 'Nie ma użytkownika nazywającego się "$1". Sprawdź pisownię lub użyj poniższego formularza by utworzyć nowe konto.',
'nosuchusershort'            => 'Nie ma użytkownika nazywającego się "$1".',
'nouserspecified'            => 'Musisz podać nazwę użytkownika.',
'wrongpassword'              => 'Podane przez Ciebie hasło jest nieprawidłowe. Spróbuj jeszcze raz.',
'wrongpasswordempty'         => 'Wprowadzone hasło jest puste. Spróbuj ponownie.',
'passwordtooshort'           => 'Twoje hasło jest błędne lub za krótkie. Musi mieć co najmniej $1 znaków i być inne niż Twoja nazwa użytkownika.',
'mailmypassword'             => 'Wyślij mi nowe hasło',
'passwordremindertitle'      => 'Przypomnienie hasła w serwisie {{SITENAME}}',
'passwordremindertext'       => 'Ktoś (prawdopodobnie Ty, spod adresu $1)
poprosił od nas o wysłanie nowego hasła dostępu do serwisu
{{SITENAME}} ($4).
Aktualne hasło dla użytkownika "$2" to "$3".
Najlepiej będzie jak zalogujesz się teraz i od razu zmienisz hasło.',
'noemail'                    => 'W bazie nie ma adresu e-mailowego dla użytkownika "$1".',
'passwordsent'               => 'Nowe hasło zostało wysłane na adres e-mailowy użytkownika "$1". Po otrzymaniu go zaloguj się ponownie.',
'blocked-mailpassword'       => 'Twój adres IP został zablokowany i nie możesz używać funkcji odzyskiwania hasła z powodu możliwości jej nadużywania.',
'eauthentsent'               => 'Potwierdzenie zostało wysłane na adres e-mail.
Nim jakiekolwiek wiadomości zostaną wysłane na ten adres, należy wypełnić zawarte w nim instrukcje, by potwierdzić Twoją własność e-maila.',
'throttled-mailpassword'     => 'Przypomnienie hasła zostało już wysłane w ciągu ostatnich $1 godzin.
W celu powstrzymania nadużyć możliwość wysyłania przypomnień została ograniczona do jednego na $1 godziny.',
'mailerror'                  => 'Przy wysyłaniu e-maila nastąpił błąd: $1',
'acct_creation_throttle_hit' => 'Przykro nam, założyłeś(-aś) już $1 kont(a). Nie możesz założyć kolejnego.',
'emailauthenticated'         => 'Twój adres email został uwierzytelniony $1.',
'emailnotauthenticated'      => 'Twój adres e-mail nie jest potwierdzony. Poniższe funkcje poczty nie będą działały.',
'noemailprefs'               => 'Musisz podać adres e-mail, aby te funkcje działały.',
'emailconfirmlink'           => 'Potwierdź swój adres e-mail',
'invalidemailaddress'        => 'E-mail nie zostanie zaakceptowany: jego format nie spełnia formalnych wymagań. Proszę wpisać poprawny adres email lub wyczyścić pole.',
'accountcreated'             => 'Utworzono konto',
'accountcreatedtext'         => 'Konto dla $1 zostało utworzone.',
'createaccount-title'        => 'Stworzenie konta dla {{SITENAME}}',
'createaccount-text'         => 'Ktoś ($1) utworzył konto dla $2 na {{SITENAME}}
($4). Obecne hasło "$2" to "$3". Powinieneś się teraz zalogować i je zmienić.

Możesz zignorować tą wiadomość, jeśli konto zostało stworzone przez pomyłkę.',
'loginlanguagelabel'         => 'Język: $1',

# Password reset dialog
'resetpass'               => 'Resetuj hasło',
'resetpass_announce'      => 'Zalogowałeś się z tymczasowym kodem otrzymanym przez e-mail. Aby zakończyć proces logowania musisz ustawić nowe hasło:',
'resetpass_text'          => '<!-- Dodaj tekst -->',
'resetpass_header'        => 'Resetuj hasło',
'resetpass_submit'        => 'Ustaw hasło i zaloguj',
'resetpass_success'       => 'Twoje hasło zostało pomyślnie zmienione! Trwa logowanie...',
'resetpass_bad_temporary' => 'Nieprawidłowe hasło tymczasowe. Być może zakończyłeś już proces zmiany hasła lub poprosiłeś o nowe hasło tymczasowe.',
'resetpass_forbidden'     => 'Hasła nie mogą być zmienione na tej wiki.',
'resetpass_missing'       => 'Brak danych formularza.',

# Edit page toolbar
'bold_sample'     => 'Tekst wytłuszczony',
'bold_tip'        => 'Tekst wytłuszczony',
'italic_sample'   => 'Tekst pochylony',
'italic_tip'      => 'Tekst pochylony',
'link_sample'     => 'Tytuł linku',
'link_tip'        => 'Link wewnętrzny',
'extlink_sample'  => 'http://www.przyklad.pl tytuł strony',
'extlink_tip'     => 'Link zewnętrzny (pamiętaj o prefiksie http:// )',
'headline_sample' => 'Tekst nagłówka',
'headline_tip'    => 'Nagłówek 2. poziomu',
'math_sample'     => 'W tym miejscu wprowadź wzór',
'math_tip'        => 'Wzór matematyczny (LaTeX)',
'nowiki_sample'   => 'Wstaw tu tekst niesformatowany',
'nowiki_tip'      => 'Zignoruj formatowanie wiki',
'image_sample'    => 'Przyklad.jpg',
'image_tip'       => 'Grafika osadzona',
'media_sample'    => 'Przyklad.ogg',
'media_tip'       => 'Link do pliku',
'sig_tip'         => 'Twój podpis wraz z datą i czasem',
'hr_tip'          => 'Pozioma linia (używaj oszczędnie)',

# Edit pages
'summary'                   => 'Opis zmian',
'subject'                   => 'Temat/nagłówek',
'minoredit'                 => 'To jest drobna zmiana',
'watchthis'                 => 'Obserwuj tę stronę',
'savearticle'               => 'Zapisz',
'preview'                   => 'Podgląd',
'showpreview'               => 'Pokaż podgląd',
'showlivepreview'           => 'Dynamiczny podgląd',
'showdiff'                  => 'Pokaż zmiany',
'anoneditwarning'           => 'Nie jesteś zalogowany(-a). Twój adres IP będzie zapisany w historii edycji strony.',
'missingsummary'            => "'''Przypomnienie:''' Nie wprowadziłeś opisu zmian. Jeżeli nie chcesz go wprowadzać naciśnij przycisk Zapisz jeszcze raz.",
'missingcommenttext'        => 'Wprowadź komentarz poniżej.',
'missingcommentheader'      => "'''Uwaga:''' Nie wprowadziłeś(-aś) tematu/nagłówka dla tego komentarza. Jeżeli jeszcze raz wciśniesz \"Zapisz\", twój komentarz zostanie zapisany bez nagłówka.",
'summary-preview'           => 'Podgląd opisu',
'subject-preview'           => 'Podgląd tematu/nagłówka',
'blockedtitle'              => 'Użytkownik jest zablokowany',
'blockedtext'               => '<big>\'\'\'Twoje konto lub adres IP zostały zablokowane.\'\'\'</big>

Blokada została nałożona przez $1. Podany powód to: \'\'$2\'\'.

* Początek blokady: $8
* Wygaśnięcie blokady: $6
* Cel blokady: $7

W celu wyjaśnienia sprawy zablokowania możesz się skontaktować z $1 lub innym [[{{MediaWiki:Grouppage-sysop}}|administratorem]].
Nie możesz użyć funkcji "Wyślij e-mail do tego użytkownika" jeśli nie masz podanego poprawnego adresu e-mail w swoich [[Special:Preferences|preferencjach]] lub jeśli taka możliwość została ci zablokowana.
Twój obecny adres IP to $3 a numer identyfikacyjny blokady to #$5. Prosimy o podanie jednego lub obu tych numerów przy wyjaśnianiu tej blokady.',
'autoblockedtext'           => 'Twój adres IP został zablokowany automatycznie, gdyż należy do użytkownika zablokowanego przez $1.
Przyczyna blokady:

:\'\'$2\'\'

* Blokada została nałożona $8
* blokada wygasa $6

Możesz skontaktować się z użytkownikiem $1 lub jednym z pozostałych
[[{{MediaWiki:Grouppage-sysop}}|administratorów]] i zapytać o przyczynę blokady.

Nie możesz korzystać z opcji "Wyślij e-mail do tego użytkownika", chyba że podałeś własny adres e-mail w [[Special:Preferences|preferencjach]] i nie została Ci odebrana możliwość wysyłania wiadomości.

Identyfikator Twojej blokady to $5. Należy go dołączyć do wysyłanych zapytań.',
'blockednoreason'           => 'nie podano powodu',
'blockedoriginalsource'     => "Źródło '''$1''' zostało pokazane poniżej:",
'blockededitsource'         => "Tekst '''Twoich edycji''' na '''$1''' został pokazany poniżej:",
'whitelistedittitle'        => 'Przed edycją musisz się zalogować',
'whitelistedittext'         => 'Musisz $1 żeby móc edytować artykuły.',
'whitelistreadtitle'        => 'Przed przeczytaniem musisz się zalogować',
'whitelistreadtext'         => 'Musisz się [[{{ns:Special}}:Userlogin|zalogować]], żeby czytać strony.',
'whitelistacctitle'         => 'Nie wolno ci zakładać konta',
'whitelistacctext'          => 'Aby móc zakładać konta na tej Wiki musisz [[{{ns:special}}:Userlogin|zalogować się]] i mieć przyznane specjalne prawa.',
'confirmedittitle'          => 'Wymagane potwierdzenie e-maila by móc edytować',
'confirmedittext'           => 'Musisz podać i potwierdzić swój e-mail by móc edytować. Możesz to zrobić w [[{{ns:special}}:Preferences|swoich ustawieniach]].',
'nosuchsectiontitle'        => 'Nieistniejąca sekcja',
'nosuchsectiontext'         => 'Próbowałeś edytować sekcję która nie istnieje. Skoro nie ma sekcji $1, nie ma też gdzie zapisać twojej edycji.',
'loginreqtitle'             => 'Musisz się zalogować',
'loginreqlink'              => 'zaloguj się',
'loginreqpagetext'          => 'Musisz $1 żeby móc przeglądać inne strony.',
'accmailtitle'              => 'Hasło wysłane.',
'accmailtext'               => 'Hasło dla użytkownika "$1" zostało wysłane pod adres $2.',
'newarticle'                => '(Nowy)',
'newarticletext'            => "Nie ma jeszcze artykułu o tym tytule. W poniższym polu można wpisać pierwszy jego fragment. Jeśli nie to było Twoim zamiarem, wciśnij po prostu ''Wstecz''.",
'anontalkpagetext'          => "---- ''To jest strona dyskusyjna dla użytkowników anonimowych - takich, którzy nie mają jeszcze swojego konta lub nie chcą go w tej chwili używać. By ich identyfikować używamy numerów IP. Jeśli jesteś anonimowym użytkownikiem i wydaje Ci się, że zamieszczone tu komentarze nie są skierowane do Ciebie, [[{{ns:special}}:Userlogin|utwórz proszę konto albo zaloguj się]] - dzięki temu unikniesz w przyszłości podobnych nieporozumień.''",
'noarticletext'             => 'Nie ma jeszcze artykułu o tym tytule. Możesz [{{fullurl:{{NAMESPACE}}:{{PAGENAME}}|action=edit}} utworzyć artykuł {{FULLPAGENAME}}] lub [[{{ns:special}}:Search/{{FULLPAGENAME}}|poszukać {{FULLPAGENAME}} w innych artykułach]].',
'userpage-userdoesnotexist' => 'Użytkownik "$1" nie jest zarejestrowany. Upewnij się czy na pewno zamierzałeś stworzyć/zmodyfikować właśnie tę stronę.',
'clearyourcache'            => "'''Uwaga:''' By zobaczyć zmiany po zapisaniu nowych ustawień poleć przeglądarce zignorować zawartość pamięci podręcznej (cache). '''Mozilla / Firefox:''' przytrzymaj ''Shift'' klikając na ''Odśwież'' lub wciśnij ''Ctrl-Shift-R'') (''Cmd-Shift-R'' na Macintoshu), '''IE :''' przytrzymaj ''Ctrl'' klikając na ''Odśwież'' lub wciśnij ''Ctrl-F5''; '''Konqueror:''': po prostu kliknij przycisk ''Odśwież'' lub wciśnij ''F5''; użytkownicy '''Opery''' mogą być zmuszeni do kompletnego wyczyszczenia ich pamięci podręcznej w menu ''Narzędzia→Preferencje''.",
'usercssjsyoucanpreview'    => '<strong>Wskazówka:</strong> Użyj przycisku "Podgląd", aby przetestować Twój nowy arkusz stylów CSS lub kod JavaScript przed jego zapisaniem.',
'usercsspreview'            => "'''Pamiętaj, że to na razie tylko podgląd Twojego arkusza stylów - nic jeszcze nie zostało zapisane!'''",
'userjspreview'             => "'''Pamiętaj, że to na razie tylko podgląd Twojego JavaScriptu - nic jeszcze nie zostało zapisane!'''",
'userinvalidcssjstitle'     => "'''Uwaga:''' Nie ma skórki o nazwie \"\$1\". Pamiętaj, że strony użytkownika zawierające CSS i JavaScript powinny zaczynać się małą literą, np. {{ns:user}}:Foo/monobook.css.",
'updated'                   => '(Zmodyfikowano)',
'note'                      => '<strong>Uwaga:</strong>',
'previewnote'               => '<strong>To jest tylko podgląd - artykuł nie został jeszcze zapisany!</strong>',
'previewconflict'           => 'Wersja podglądana odnosi się do tekstu z górnego pola edycji. Tak będzie wyglądać strona jeśli zdecydujesz się ją zapisać.',
'session_fail_preview'      => '<strong>Przepraszamy! Serwer nie może przetworzyć tej edycji z powodu utraty danych sesji. Spróbuj jeszcze raz. Jeśli to nie pomoże - wyloguj się i zaloguj ponownie.</strong>',
'session_fail_preview_html' => "<strong>Przepraszamy! Serwer nie może przetworzyć tej edycji z powodu utraty danych sesji.</strong>

''Ponieważ na tej wiki włączona została opcja \"raw HTML\", podgląd został ukryty w celu zabezpieczenia przed atakami JavaScript.''

<strong>Jeśli jest to prawidłowa próba dokonania edycji, spróbuj jeszcze raz. Jeśli to nie pomoże - wyloguj się i zaloguj ponownie.</strong>",
'token_suffix_mismatch'     => '<strong>Twoja edycja została odrzucona, ponieważ twój klient pomieszał znaki interpunkcyjne w żetonie edycyjnym. Twoja edycja została odrzucona by zapobiec zniszczeniu tekstu strony. Takie problemy zdarzają się w wypadku korzystania z wadliwych anonimowych sieciowych usług proxy.</strong>',
'editing'                   => 'Edytujesz "$1"',
'editinguser'               => 'Edytujesz "$1"',
'editingsection'            => 'Edytujesz "$1" (fragment)',
'editingcomment'            => 'Edytujesz "$1" (komentarz)',
'editconflict'              => 'Konflikt edycji: $1',
'explainconflict'           => 'Ktoś zdążył wprowadzić swoją wersję artykułu w trakcie Twojej edycji.
Górne pole edycji zawiera tekst strony aktualnie zapisany w bazie danych.
Twoje zmiany znajdują się w dolnym polu edycji.
By wprowadzić swoje zmiany musisz zmodyfikować tekst z górnego pola.
<b>Tylko</b> tekst z górnego pola będzie zapisany w bazie gdy wciśniesz "Zapisz".<br />',
'yourtext'                  => 'Twój tekst',
'storedversion'             => 'Zapisana wersja',
'nonunicodebrowser'         => '<strong>Uwaga! Twoja przeglądarka nie umie poprawnie rozpoznawać kodowania UTF-8 (Unicode). Z tego powodu wszystkie znaki, których Twoja przeglądarka nie jest w stanie rozpoznać, zostały zastąpione ich kodami heksadecymalnymi.</strong>',
'editingold'                => '<strong>Ostrzeżenie: Edytujesz inną niż bieżąca wersję tej strony. Jeśli zapiszesz ją, wszystkie późniejsze zmiany zostaną skasowane.</strong>',
'yourdiff'                  => 'Różnice',
'copyrightwarning'          => "Proszę pamiętać o tym, że wszelki wkład do serwisu {{SITENAME}} jest udostępniany na zasadach $2 (szczegóły w $1). Jeśli nie chcesz, żeby Twój tekst był dowolnie zmieniany przez każdego i rozpowszechniany bez ograniczeń, nie umieszczaj go tutaj.<br />
Niniejszym jednocześnie oświadczasz, że ten tekst jest Twoim dziełem lub pochodzi z materiałów dostępnych na zasadach ''public domain'' albo kompatybilnych.
<strong>PROSZĘ NIE UŻYWAĆ BEZ POZWOLENIA MATERIAŁÓW OBJĘTYCH PRAWEM AUTORSKIM!</strong>",
'copyrightwarning2'         => "Proszę pamiętać o tym, że wszelki wkład do serwisu {{SITENAME}} może być edytowany, zmieniany lub usunięty przez innych użytkowników. Jeśli nie chcesz, żeby Twój tekst był dowolnie zmieniany przez każdego i rozpowszechniany bez ograniczeń, nie umieszczaj go tutaj.<br />
Niniejszym jednocześnie oświadczasz, że ten tekst jest Twoim dziełem lub pochodzi z materiałów dostępnych na zasadach ''public domain'' albo kompatybilnych (zobacz także $1).
<strong>PROSZĘ NIE UŻYWAĆ BEZ POZWOLENIA MATERIAŁÓW OBJĘTYCH PRAWEM AUTORSKIM!</strong>",
'longpagewarning'           => '<strong>Uwaga: Ta strona ma $1 kilobajt-y/-ów; w przypadku niektórych przeglądarek mogą wystąpić problemy w edycji stron mających więcej niż 32 kilobajty. Jeśli to możliwe, spróbuj podzielić tekst na mniejsze części.</strong>',
'longpageerror'             => '<strong>Błąd: Przesłany przez Ciebie tekst ma $1 kilobajtów. Maksymalna długość tekstu nie może przekraczać $2 kilobajtów. Twój tekst nie zostanie zapisany.</strong>',
'readonlywarning'           => '<strong>Uwaga: Baza danych została chwilowo zablokowana do celów administracyjnych. Nie można więc na razie zapisać nowej wersji artykułu. Proponujemy przenieść jej tekst do prywatnego pliku (wytnij/wklej) i zachować na później.</strong>',
'protectedpagewarning'      => '<strong>Uwaga: Modyfikacja tej strony została zablokowana. Mogą ją edytować jedynie użytkownicy z prawami administratora.</strong>',
'semiprotectedpagewarning'  => "'''Uwaga:''' Ta strona została zabezpieczona i tylko zarejestrowani użytkownicy mogą ją edytować.",
'cascadeprotectedwarning'   => "'''Uwaga:''' Ta strona została zabezpieczona i tylko użytkownicy z uprawnieniami administratora mogą ją edytować. Strona ta jest zawarta na {{PLURAL:$1|wymienionej stronie, która została zabezpieczona|następujących stronach, które zostały zabezpieczone}} z włączoną opcją dziedziczenia:",
'templatesused'             => 'Szablony użyte na tej stronie:',
'templatesusedpreview'      => 'Szablony użyte w tym podglądzie:',
'templatesusedsection'      => 'Szablony użyte w tej sekcji:',
'template-protected'        => '(zabezpieczony)',
'template-semiprotected'    => '(częściowo zabezpieczony)',
'edittools'                 => '<!-- Znajdujący się tutaj tekst zostanie pokazany pod polem edycji i formularzem przesyłania plików. -->',
'nocreatetitle'             => 'Ograniczono tworzenie stron',
'nocreatetext'              => 'Ograniczono możliwość tworzenia nowych stron. Możesz edytować istniejące strony lub [[{{ns:special}}:Userlogin|zalogować się albo utworzyć nowe konto]].',
'nocreate-loggedin'         => 'Nie masz uprawnień do tworzenia nowych stron na {{SITENAME}}.',
'permissionserrors'         => 'Błędy uprawnień',
'permissionserrorstext'     => 'Nie masz uprawnień do tego działania z {{PLURAL:$1|następującej przyczyny|następujących przyczyn}}:',
'recreate-deleted-warn'     => "'''Uwaga: Próbujesz odtworzyć uprzednio skasowaną stronę.'''

Należy zastanowić się, czy ponowne edytowanie tej strony jest uzasadnione.
Dla wygody użytkowników, poniżej przedstawiony jest rejestr usunięć niniejszej strony:",

# "Undo" feature
'undo-success' => 'Edycja została wycofana. Proszę porównać ukazane poniżej różnice między wersjami w celu ich zweryfikowania poprawności, a następnie zapisać zmiany w celu zakończenia operacji.',
'undo-failure' => 'Edycja nie została wycofana z powodu konfliktu z wersjami pośrednimi.',
'undo-summary' => 'Wycofanie wersji $1 utworzonej przez [[Special:Contributions/$2]] ([[User talk:$2]])',

# Account creation failure
'cantcreateaccounttitle' => 'Nie można utworzyć konta',
'cantcreateaccount-text' => "Tworzenie konta z tego adresu IP (<b>$1</b>) zostało zablokowane przez użytkownika [[User:$3|$3]].

Podany przez $3 powód to ''$2''",

# History pages
'viewpagelogs'        => 'Zobacz rejestry operacji dla tej strony',
'nohistory'           => 'Ta strona nie ma swojej historii edycji.',
'revnotfound'         => 'Wersja nie została odnaleziona',
'revnotfoundtext'     => 'Starsza wersja strony nie może zostać odnaleziona. Sprawdź, proszę, URL użyty przez Ciebie by uzyskać dostęp do tej strony.',
'loadhist'            => 'Pobieranie historii tej strony',
'currentrev'          => 'Aktualna wersja',
'revisionasof'        => 'Wersja z dnia $1',
'revision-info'       => 'Wersja z dnia $1; $2',
'previousrevision'    => '← Poprzednia wersja',
'nextrevision'        => 'Następna wersja →',
'currentrevisionlink' => 'Aktualna wersja',
'cur'                 => 'bież',
'next'                => 'następna',
'last'                => 'poprz',
'orig'                => 'oryginał',
'page_first'          => 'początek',
'page_last'           => 'koniec',
'histlegend'          => 'Legenda: (bież) - różnice z wersją bieżącą, (poprz) - różnice z wersją poprzedzającą, d - drobne zmiany',
'deletedrev'          => '[usunięto]',
'histfirst'           => 'od początku',
'histlast'            => 'od końca',
'historysize'         => '({{PLURAL:$1|1 bajt|$1 bajtów}})',
'historyempty'        => '(pusta)',

# Revision feed
'history-feed-title'          => 'Historia wersji',
'history-feed-description'    => 'Historia wersji tej strony wiki',
'history-feed-item-nocomment' => '$1 o $2', # user at time
'history-feed-empty'          => 'Wybrana strona nie istnieje. Mogła ona zostać usunięta lub przeniesiona pod inną nazwę. Możesz także [[{{ns:special}}:Search|poszukać]] tej strony.',

# Revision deletion
'rev-deleted-comment'         => '(komentarz usunięty)',
'rev-deleted-user'            => '(użytkownik usunięty)',
'rev-deleted-event'           => '(wpis usunięty)',
'rev-deleted-text-permission' => '<div class="mw-warning plainlinks">
Wersja tej strony została usunięta i nie jest dostępna publicznie.
Szczegóły mogą znajdować się w [{{fullurl:Special:Log/delete|page={{PAGENAMEE}}}} rejestrze usunięć].
</div>',
'rev-deleted-text-view'       => '<div class="mw-warning plainlinks">
Wersja tej strony została usunięta i nie jest dostępna publicznie.
Jako administrator tego serwisu możesz ją obejrzeć.
Szczegóły mogą znajdować się w [{{fullurl:Special:Log/delete|page={{PAGENAMEE}}}} rejestrze usunięć].
</div>',
'rev-delundel'                => 'pokaż/ukryj',
'revisiondelete'              => 'Skasuj/przywróć wersje',
'revdelete-nooldid-title'     => 'Nie wybrano wersji',
'revdelete-nooldid-text'      => 'Nie wybrano wersji na których ma zostać wykonana ta operacja.',
'revdelete-selected'          => '{{PLURAL:$2|Wybrana wersja|Wybrane wersje}} strony [[:$1]]:',
'logdelete-selected'          => "{{PLURAL:$2|Wybrane zdarzenie z rejestru|Wybrane zdarzenia z rejestru}} dla '''$1:'''",
'revdelete-text'              => 'Usunięte wersje będą nadal widoczne w historii strony ale ich treść nie będzie publicznie dostępna.

Inni administratorzy tej wiki nadal mają dostęp do ukrytych wersji i mogą je odtworzyć poprzez ten sam interfejs, chyba że operator serwisu nałożył dodatkowe ograniczenia.',
'revdelete-legend'            => 'Ustaw ograniczenia dla wersji:',
'revdelete-hide-text'         => 'Ukryj tekst wersji',
'revdelete-hide-name'         => 'Ukryj akcję i cel',
'revdelete-hide-comment'      => 'Ukryj komentarz edycji',
'revdelete-hide-user'         => 'Ukryj nazwę użytkownika/adres IP',
'revdelete-hide-restricted'   => 'Zaakceptuj te ograniczenia Apply these restrictions to sysops as well as others',
'revdelete-suppress'          => 'Ukryj informacje przed sysopami tak samo jak przed innymi',
'revdelete-hide-image'        => 'Ukryj zawartość pliku',
'revdelete-unsuppress'        => 'Usuń ograniczenia dla odtwarzanej historii zmian',
'revdelete-log'               => 'Komentarz:',
'revdelete-submit'            => 'Zaakceptuj dla wybranych wersji',
'revdelete-logentry'          => 'zmieniono widoczność wersji w [[$1]]',
'logdelete-logentry'          => 'zmieniono widoczność zdarzeń dla [[$1]]',
'revdelete-logaction'         => '$1 {{PLURAL:$1|wersję|wersje}} ustawiono w tryb $2',
'logdelete-logaction'         => '$1 {{PLURAL:$1|zdarzenie|zdarzenia}} dla [[$3]] przestawiono w tryb $2',
'revdelete-success'           => 'Pomyślnie zmieniono widoczność wersji.',
'logdelete-success'           => 'Pomyślnie zmieniono widoczność zdarzeń.',

# Oversight log
'oversightlog'    => 'Log Oversight',
'overlogpagetext' => 'Poniżej znajduje się lista najnowszych usunięć i blokad dotyczących zawartości ukrytej przed sysopami. Wejdź na stronę [[Special:Ipblocklist|IP block list]], by zobaczyć listę aktywnych banów i blokad.',

# History merging
'mergehistory'                     => 'Złącz historię zmian stron',
'mergehistory-header'              => "Ta strona pozwala na scalenie historii zmian jednej strony z inną nowszą stroną.
Upewnij się, że zmiany będą zapewniać ciągłość historyczną edycji strony.

'''Na końcu musi pozostać bieżąca wersja strony źródłowej.'''",
'mergehistory-box'                 => 'Połącz historię zmian dwóch stron:',
'mergehistory-from'                => 'Strona źródłowa:',
'mergehistory-into'                => 'Strona docelowa:',
'mergehistory-list'                => 'Historia zmian możliwa do połączenia',
'mergehistory-go'                  => 'Pokaż możliwe do połączenia edycje',
'mergehistory-submit'              => 'Połącz historię zmian',
'mergehistory-empty'               => 'Brak historii zmian do złączenia',
'mergehistory-success'             => '$3 zmian [[:$1]] z powodzeniem zostało złączonych z [[:$2]].',
'mergehistory-no-source'           => 'Strona źródłowa $1 nie istnieje.',
'mergehistory-no-destination'      => 'Strona docelowa $1 nie istnieje.',
'mergehistory-invalid-source'      => 'Strona źródłowa musi mieć poprawną nazwę.',
'mergehistory-invalid-destination' => 'Strona docelowa musi mieć prawidłową nazwę.',

# Merge log
'mergelog'           => 'Połącz log',
'pagemerge-logentry' => 'Połączono [[$1]] z [[$2]] (historia zmian aż do $3)',
'revertmerge'        => 'Rozdziel',

# Diffs
'history-title'           => 'Historia edycji "$1"',
'difference'              => '(Różnice między wersjami)',
'lineno'                  => 'Linia $1:',
'compareselectedversions' => 'porównaj wybrane wersje',
'editundo'                => 'anuluj zmianę',
'diff-multi'              => '(Nie pokazano {{plural:$1|jednej wersji pośredniej|$1 wersji pośrednich}}.)',

# Search results
'searchresults'         => 'Wyniki wyszukiwania',
'searchresulttext'      => 'Aby dowiedzieć się więcej o przeszukiwaniu serwisu {{SITENAME}}, zobacz [[{{MediaWiki:Helppage}}|{{int:help}}]].',
'searchsubtitle'        => 'Wyniki dla zapytania "[[:$1]]"',
'searchsubtitleinvalid' => 'Dla zapytania "$1"',
'noexactmatch'          => 'Nie ma stron zatytułowanych "$1". Możesz [[:$1|utworzyć tę stronę]] lub spróbować pełnego przeszukiwania.',
'noexactmatch-nocreate' => "'''Brak strony \"\$1\".'''",
'titlematches'          => 'Znaleziono w tytułach:',
'notitlematches'        => 'Nie znaleziono w tytułach',
'textmatches'           => 'Znaleziono na stronach:',
'notextmatches'         => 'Nie znaleziono w tekście stron',
'prevn'                 => 'poprzednie $1',
'nextn'                 => 'następne $1',
'viewprevnext'          => 'Zobacz ($1) ($2) ($3).',
'showingresults'        => "Oto lista składająca się z {{PLURAL:$1|'''1''' wyniku|'''$1''' wyników}}, poczynając od numeru '''$2'''.",
'showingresultsnum'     => "Oto lista składająca się z {{PLURAL:$3|'''1''' wyniku|'''$3''' wyników}}, poczynając od numeru '''$2'''.",
'nonefound'             => "'''Uwaga''': brak rezultatów wyszukiwania spowodowany jest bardzo często szukaniem najpopularniejszych słów, takich jak \"jest\" czy \"nie\", które nie są indeksowane, albo z powodu podania w zapytaniu więcej niż jednego słowa (na liście odnalezionych stron znajdą się tylko te, które zawierają wszystkie podane słowa).",
'powersearch'           => 'Szukaj',
'powersearchtext'       => 'Szukaj w przestrzeniach nazw:<br />$1<br />$2 Pokaż przekierowania<br />Szukany tekst $3 $9',
'searchdisabled'        => 'Wyszukiwanie w serwisie {{SITENAME}} zostało wyłączone. W międzyczasie możesz skorzystać z wyszukiwania Google.',

# Preferences page
'preferences'              => 'Preferencje',
'mypreferences'            => 'Moje preferencje',
'prefs-edits'              => 'Liczba edycji:',
'prefsnologin'             => 'Nie jesteś zalogowany',
'prefsnologintext'         => 'Musisz się [[{{ns:special}}:Userlogin|zalogować]] przed zmianą swoich preferencji.',
'prefsreset'               => 'Preferencje domyślne zostały odtworzone.',
'qbsettings'               => 'Pasek szybkiego dostępu',
'qbsettings-none'          => 'Brak',
'qbsettings-fixedleft'     => 'Stały, z lewej',
'qbsettings-fixedright'    => 'Stały, z prawej',
'qbsettings-floatingleft'  => 'Unoszący się, z lewej',
'qbsettings-floatingright' => 'Unoszący się, z prawej',
'changepassword'           => 'Zmiana hasła',
'skin'                     => 'Skórka',
'math'                     => 'Wzory',
'dateformat'               => 'Format daty',
'datedefault'              => 'Domyślny',
'datetime'                 => 'Data i czas',
'math_failure'             => 'Parser nie mógł rozpoznać',
'math_unknown_error'       => 'nieznany błąd',
'math_unknown_function'    => 'nieznana funkcja',
'math_lexing_error'        => 'błąd leksera',
'math_syntax_error'        => 'błąd składni',
'math_image_error'         => 'konwersja do formatu PNG niepowiodła się ; sprawdź, czy poprawnie zainstalowane są latex, dvips, gs i convert.',
'math_bad_tmpdir'          => 'Nie można utworzyć lub zapisywać w tymczasowym katalogu dla wzorów matematycznych',
'math_bad_output'          => 'Nie można utworzyć lub zapisywać w wyjściowym katalogu dla wzorów matematycznych',
'math_notexvc'             => 'Brak texvc; zapoznaj się z math/README w celu konfiguracji.',
'prefs-personal'           => 'Dane użytkownika',
'prefs-rc'                 => 'Ostatnie zmiany',
'prefs-watchlist'          => 'Obserwowane',
'prefs-watchlist-days'     => 'Liczba dni ukazywania się pozycji na liście:',
'prefs-watchlist-edits'    => 'Liczba edycji pokazywanych w rozszerzonej liście obserwowanych:',
'prefs-misc'               => 'Różne',
'saveprefs'                => 'Zapisz',
'resetprefs'               => 'Preferencje domyślne',
'oldpassword'              => 'Stare hasło',
'newpassword'              => 'Nowe hasło',
'retypenew'                => 'Powtórz nowe hasło',
'textboxsize'              => 'Edytowanie',
'rows'                     => 'Wiersze:',
'columns'                  => 'Kolumny:',
'searchresultshead'        => 'Wyszukiwanie',
'resultsperpage'           => 'Liczba wyników na stronie',
'contextlines'             => 'Pierwsze wiersze artykułu',
'contextchars'             => 'Litery kontekstu w linijce',
'stub-threshold'           => 'Maksymalny rozmiar artykułu oznaczanego jako <a href="#" class="stub">stub (zalążek)</a>',
'recentchangesdays'        => 'Liczba dni do pokazania w ostatnich zmianach:',
'recentchangescount'       => 'Liczba pozycji na liście ostatnich zmian:',
'savedprefs'               => 'Twoje preferencje zostały zapisane.',
'timezonelegend'           => 'Strefa czasowa',
'timezonetext'             => 'Podaj liczbę godzin różnicy między Twoim czasem, a czasem uniwersalnym (UTC).',
'localtime'                => 'Twój czas:',
'timezoneoffset'           => 'Różnica ¹',
'servertime'               => 'Aktualny czas serwera',
'guesstimezone'            => 'Pobierz z przeglądarki',
'allowemail'               => 'Inni użytkownicy mogą przesyłać mi e-maile',
'defaultns'                => 'Przeszukuj następujące przestrzenie nazw domyślnie:',
'default'                  => 'domyślnie',
'files'                    => 'Pliki',

# User rights
'userrights-lookup-user'      => 'Zarządzaj grupami użytkownika',
'userrights-user-editname'    => 'Wprowadź nazwę użytkownika:',
'editusergroup'               => 'Edytuj grupy użytkownika',
'userrights-editusergroup'    => 'Edytuj grupy użytkownika',
'saveusergroups'              => 'Zapisz',
'userrights-groupsmember'     => 'Należy do grupy:',
'userrights-groupsremovable'  => 'Usuwalny z grup:',
'userrights-groupsavailable'  => 'Dostępne grupy:',
'userrights-groupshelp'       => 'Zaznacz grupy do których użytkownik ma zostać dodany lub z których ma zostać usunięty. Niezaznaczone grupy nie zostaną zmienione. Możesz odznaczyć grupę za pomocą CTRL + lewy przycisk myszy.',
'userrights-reason'           => 'Powód zmiany:',
'userrights-available-none'   => 'Nie możesz zmieniać przynależności do grup.',
'userrights-available-add'    => 'Możesz dodać użytkowników do grupy $1.',
'userrights-available-remove' => 'Możesz usunąć użytkowników z grupy $1.',
'userrights-no-interwiki'     => 'Nie masz dostępu do edycji uprawnień.',
'userrights-nodatabase'       => 'Baza danych $1 nie istnieje lub nie jest lokalna.',
'userrights-nologin'          => 'Musisz [[Special:Userlogin|zalogować się]] na konto administratora, by nadawać uprawnienia użytkownikom.',
'userrights-notallowed'       => 'Nie masz dostępu do nadawania uprawnień użytkownikom.',

# Groups
'group'               => 'Grupa:',
'group-autoconfirmed' => 'Automatycznie zatwierdzeni użytkownicy',
'group-bot'           => 'Boty',
'group-sysop'         => 'Administratorzy',
'group-bureaucrat'    => 'Biurokraci',
'group-all'           => '(wszyscy)',

'group-autoconfirmed-member' => 'Automatycznie zatwierdzony użytkownik',
'group-bot-member'           => 'Bot',
'group-sysop-member'         => 'Administrator',
'group-bureaucrat-member'    => 'Biurokrata',

'grouppage-autoconfirmed' => '{{ns:project}}:Automatycznie zatwierdzeni użytkownicy',
'grouppage-bot'           => '{{ns:project}}:Boty',
'grouppage-sysop'         => '{{ns:project}}:Administratorzy',
'grouppage-bureaucrat'    => '{{ns:project}}:Biurokraci',

# User rights log
'rightslog'      => 'Uprawnienia',
'rightslogtext'  => 'Rejestr zmian uprawnień użytkowników.',
'rightslogentry' => 'zmienił(-a) uprawnienia użytkownika $1 z "$2" na "$3"',
'rightsnone'     => '(podstawowe)',

# Recent changes
'nchanges'                          => '$1 {{PLURAL:$1|zmiana|zmiany}}',
'recentchanges'                     => 'Ostatnie zmiany',
'recentchangestext'                 => 'Ta strona przedstawia historię ostatnich zmian w tej wiki.',
'recentchanges-feed-description'    => 'Obserwuj najświeższe zmiany w tej wiki.',
'rcnote'                            => "Poniżej znajduje się {{PLURAL:$1|ostatnia zmiana dokonana|ostatnie '''$1''' zmian dokonanych}} w ciągu {{PLURAL:$2|ostatniego dnia|ostatnich '''$2''' dni}}, poczynając od $3.",
'rcnotefrom'                        => 'Poniżej pokazano zmiany dokonane po <b>$2</b> (nie więcej niż <b>$1</b> pozycji).',
'rclistfrom'                        => 'Pokaż zmiany od $1',
'rcshowhideminor'                   => '$1 drobne zmiany',
'rcshowhidebots'                    => '$1 boty',
'rcshowhideliu'                     => '$1 zalogowanych',
'rcshowhideanons'                   => '$1 anonimowych',
'rcshowhidepatr'                    => '$1 patrolowane',
'rcshowhidemine'                    => '$1 moje edycje',
'rclinks'                           => 'Wyświetl ostatnie $1 zmian w ciągu ostatnich $2 dni.<br />$3',
'diff'                              => 'różn',
'hist'                              => 'hist',
'hide'                              => 'ukryj',
'show'                              => 'pokaż',
'minoreditletter'                   => 'd',
'newpageletter'                     => 'N',
'boteditletter'                     => 'b',
'number_of_watching_users_pageview' => '[$1 {{PLURAL:$1|obserwujący użytkownik|obserwujących użytkowników}}/s]',
'rc_categories'                     => 'Ogranicz do kategorii (oddzielaj za pomocą "|")',
'rc_categories_any'                 => 'Wszystkie',
'newsectionsummary'                 => '/* $1 */ nowa sekcja',

# Recent changes linked
'recentchangeslinked'          => 'Zmiany w dolinkowanych',
'recentchangeslinked-title'    => 'Zmiany w stronach linkowanych z $1',
'recentchangeslinked-noresult' => 'Nie było żadnych zmian na dolinkowanych stronych w wybranym okresie.',
'recentchangeslinked-summary'  => "Ta strona specjalna zawiera listę ostatnich zmian dokonanych na stronach dolinkowanych. Strony znajdujące się na Twojej liście obserwowanych zostały '''pogrubione'''.",

# Upload
'upload'                      => 'Prześlij plik',
'uploadbtn'                   => 'Prześlij plik',
'reupload'                    => 'Prześlij ponownie',
'reuploaddesc'                => 'Wróć do formularza wysyłki.',
'uploadnologin'               => 'Brak logowania',
'uploadnologintext'           => 'Musisz się [[{{ns:special}}:Userlogin|zalogować]] przed przesłaniem pików.',
'upload_directory_read_only'  => 'Serwer nie może zapisywać do katalogu ($1) przeznaczonego na przesyłane pliki.',
'uploaderror'                 => 'Błąd przesyłki',
'uploadtext'                  => 'Użyj poniższego formularza do przesłania plików. Jeśli chcesz przejrzeć lub przeszukać dotychczas przesłane pliki, przejdź do [[{{ns:special}}:Imagelist|listy dołączonych plików]]. Wszystkie przesyłki są odnotowane w [[{{ns:special}}:Log/upload|rejestrze przesyłanych plików]].',
'upload-permitted'            => 'Dopuszczalne formaty plików: $1.',
'upload-preferred'            => 'Zalecane formaty plików: $1.',
'upload-prohibited'           => 'Zabronione formaty plików: $1.',
'uploadlog'                   => 'Wykaz przesyłek',
'uploadlogpage'               => 'Przesłane',
'uploadlogpagetext'           => 'Oto lista ostatnio przesłanych plików.',
'filename'                    => 'Plik',
'filedesc'                    => 'Opis',
'fileuploadsummary'           => 'Opis:',
'filestatus'                  => 'Status prawny',
'filesource'                  => 'Kod źródłowy',
'uploadedfiles'               => 'Przesłane pliki',
'ignorewarning'               => 'Zignoruj ostrzeżenia i wymuś zapisanie pliku.',
'ignorewarnings'              => 'Ignoruj ostrzeżenia',
'minlength1'                  => 'Nazwa pliku musi składać się z co najmniej jednej litery.',
'illegalfilename'             => 'Nazwa pliku ("$1") zawiera znaki niedozwolone w tytułach stron. Proszę zmienić nazwę pliku i przesłać go ponownie.',
'badfilename'                 => 'Nazwa pliku została zmieniona na "$1".',
'filetype-badmime'            => 'Przesyłanie plików z typem MIME "$1" jest niedozwolone.',
'filetype-unwanted-type'      => "'''\".\$1\"''' nie jest zalecanym typem pliku. Pożądane są pliki w formatach \$2.",
'filetype-banned-type'        => "'''\".\$1\"''' nie jest niedozwolonym typem pliku. Dostępne są pliki w formatach \$2.",
'filetype-missing'            => 'Plik nie ma rozszerzenia (np. ".jpg").',
'large-file'                  => 'Zalecane jest aby rozmiar pliku nie był większy niż $1 bajtów. Ten plik ma rozmiar $2 bajtów.',
'largefileserver'             => 'Plik jest większy niż maksymalny dozwolony rozmiar.',
'emptyfile'                   => 'Przesłany plik wydaje się być pusty. Może być to spowodowane literówką w nazwie pliku. Sprawdź, czy nazwa jest prawidłowa.',
'fileexists'                  => 'Plik o takiej nazwie już istnieje! Załadowanie nowej grafiki nieodwacalnie usunie już istniejącą ($1)! Upewnij się, że wiesz, co robisz.',
'fileexists-extension'        => 'Plik o podobnej nazwie już istnieje:<br />
Nazwa przesyłanego pliku: <strong><tt>$1</tt></strong><br />
Nazwa istniejącego pliku: <strong><tt>$2</tt></strong><br />
Proszę wybrać inną nazwę.',
'fileexists-thumb'            => "<center>'''Istniejąca grafika'''</center>",
'fileexists-thumbnail-yes'    => 'Plik wydaje się być pomniejszoną grafiką <i>(miniaturką)</i>. Zobacz plik <strong><tt>$1</tt></strong>.<br />
Jeśli plik jest tą samą grafiką co ta w oryginalnym rozmiarze, to nie musisz przesyłać dodatkowej miniaturki.',
'file-thumbnail-no'           => 'Nazwa pliku zaczyna się od <strong><tt>$1</tt></strong>. Wydaje się, że jest to pomniejszona grafika <i>(miniaturka)</i>.
Jeśli posiadasz tę grafikę w pełnym rozmiarze - prześlij ją, inaczej będziesz musiał zmienić nazwę przesyłanego obecnie pliku.',
'fileexists-forbidden'        => 'Plik o tej nazwie już istnieje! Wróć i załaduj ten plik pod inną nazwą. [[Image:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => 'Plik o tej nazwie już istnieje! Wróć i załaduj ten plik pod inną nazwą. [[Image:$1|thumb|center|$1]]',
'successfulupload'            => 'Przesłanie pliku powiodło się',
'uploadwarning'               => 'Ostrzeżenie o przesyłce',
'savefile'                    => 'Zapisz plik',
'uploadedimage'               => 'przesłano "[[$1]]"',
'overwroteimage'              => 'przesłano nową wersję "[[$1]]"',
'uploaddisabled'              => 'Przesyłanie plików wyłączone',
'uploaddisabledtext'          => 'Możliwość przesyłania plików została wyłączona.',
'uploadscripted'              => 'Ten plik zawiera kod HTML lub skrypt który może zostać błędnie zinterpretowany przez przeglądarkę internetową.',
'uploadcorrupt'               => 'Ten plik jest uszkodzony lub ma nieprawidłowe rozszerzenie. Proszę sprawdzić plik i załadować poprawną wersję.',
'uploadvirus'                 => 'W tym pliku jest wirus! Szczegóły: $1',
'sourcefilename'              => 'Nazwa oryginalna',
'destfilename'                => 'Nazwa docelowa',
'watchthisupload'             => 'Obserwuj tę stronę',
'filewasdeleted'              => 'Plik o tej nazwie istniał, ale został skasowany. Zanim załadujesz go ponownie, sprawdź $1.',
'upload-wasdeleted'           => "'''Ostrzeżenie: Ładujesz plik, który był poprzednio usunięty.'''

Zastanów się, czy powinno się ładować ten plik.
Rejestr usunięć tego pliku jest dla wygody podany poniżej:",
'filename-bad-prefix'         => 'Nazwa pliku, który ładujesz, zaczyna się od <strong>"$1"</strong> &ndash; jest to nazwa zazwyczaj przypisywana automatycznie przez cyfrowe aparaty fotograficzne, która nie daje żadnych informacji o zawartości pliku. Prosimy o wybranie bardziej zrozumiałej nazwy pliku.',

'upload-proto-error'      => 'Nieprawidłowy protokół',
'upload-proto-error-text' => 'Zdalne przesyłanie plików wymaga podania adresu URL zaczynającego się na <code>http://</code> lub <code>ftp://</code>.',
'upload-file-error'       => 'Błąd wewnętrzny',
'upload-file-error-text'  => 'Wystąpił błąd wewnętrzny podczas próby utworzenia tymczasowego pliku na serwerze. Skontaktuj się z administratorem systemu.',
'upload-misc-error'       => 'Nieznany błąd przesyłania',
'upload-misc-error-text'  => 'Wystąpił nieznany błąd podczas przesyłania. Proszę, sprawdź czy podany URl jest poprawny i dostępny, a następnie spróbuj ponownie. Jeśli problem będzie się powtarzał skontaktuj się z administratorem systemu.',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6'       => 'URL jest nieosiągalny',
'upload-curl-error6-text'  => 'Podany URL jest nieosiągalny. Proszę, dokładnie sprawdź czy podany URL jest prawidłowy i czy dana strona działa.',
'upload-curl-error28'      => 'Upłynął limit czasu odpowiedzi',
'upload-curl-error28-text' => 'Strona odpowiada zbyt wolno. Proszę, sprawdź czy strona działa, odczekaj kilka minut i spróbuj ponownie. Możesz także spróbować w czasie mniejszego obciążenia strony.',

'license'            => 'Licencja',
'nolicense'          => 'Nie wybrano (wpisz ręcznie!)',
'license-nopreview'  => '(Podgląd niedostępny)',
'upload_source_url'  => ' (poprawny, publicznie dostępny URL)',
'upload_source_file' => ' (plik na twoim komputerze)',

# Image list
'imagelist'                 => 'Lista plików',
'imagelisttext'             => "Na poniższej liście znajduje się '''$1''' {{plural:$1|plik posortowany|plików posortowanych}} $2.",
'getimagelist'              => 'pobieranie listy plików',
'ilsubmit'                  => 'Szukaj',
'showlast'                  => 'Pokaż ostatnie $1 plików posortowanych $2.',
'byname'                    => 'według nazwy',
'bydate'                    => 'według daty',
'bysize'                    => 'według rozmiaru',
'imgdelete'                 => 'usuń',
'imgdesc'                   => 'opis',
'imgfile'                   => 'plik',
'filehist'                  => 'Historia pliku',
'filehist-help'             => 'Kliknij na datę/czas, aby zobaczyć, jak plik wyglądał w tym czasie.',
'filehist-deleteall'        => 'usuń wszystkie',
'filehist-deleteone'        => 'usuń tę wersję',
'filehist-revert'           => 'cofnij',
'filehist-current'          => 'aktualny',
'filehist-datetime'         => 'Data/czas',
'filehist-user'             => 'Użytkownik',
'filehist-dimensions'       => 'Wymiary',
'filehist-filesize'         => 'Rozmiar pliku',
'filehist-comment'          => 'Komentarz',
'imagelinks'                => 'Odnośniki do pliku',
'linkstoimage'              => 'Następujące strony odwołują się do tego pliku:',
'nolinkstoimage'            => 'Żadna strona nie odwołuje się do tego pliku.',
'sharedupload'              => 'Ten plik znajduje się na wspólnym serwerze plików i może być używany na innych projektach.',
'shareduploadwiki'          => 'Zobacz $1 aby dowiedzieć się więcej.',
'shareduploadwiki-linktext' => 'stronę opisu grafiki',
'noimage'                   => 'Nie istnieje plik o tej nazwie. Możesz go $1.',
'noimage-linktext'          => 'przesłać',
'uploadnewversion-linktext' => 'Załaduj nowszą wersję tego pliku',
'imagelist_date'            => 'Data',
'imagelist_name'            => 'Nazwa',
'imagelist_user'            => 'Użytkownik',
'imagelist_size'            => 'Rozmiar (bajty)',
'imagelist_description'     => 'Opis',
'imagelist_search_for'      => 'Szukaj grafiki o nazwie:',

# File reversion
'filerevert'                => 'Przywracanie $1',
'filerevert-legend'         => 'Przywracanie poprzedniej wersji pliku',
'filerevert-intro'          => '<span class="plainlinks">Zamierzasz przywrócić \'\'\'[[Media:$1|$1]]\'\'\' do wersji z [$4 $3, $2].</span>',
'filerevert-comment'        => 'Komentarz:',
'filerevert-defaultcomment' => 'Przywrócono wersję z $2, $1',
'filerevert-submit'         => 'Przywróć',
'filerevert-success'        => '<span class="plainlinks">Plik \'\'\'[[Media:$1|$1]]\'\'\' został cofnięty do [wersji $4 z $3, $2].</span>',
'filerevert-badversion'     => 'Nie ma poprzedniej lokalnej wersji tego pliku z podaną datą.',

# File deletion
'filedelete'             => 'Usunięcie $1',
'filedelete-legend'      => 'Skasuj plik',
'filedelete-intro'       => "Usuwasz '''[[Media:$1|$1]]'''.",
'filedelete-intro-old'   => '<span class="plainlinks">Usuwasz wersję pliku \'\'\'[[Media:$1|$1]]\'\'\' z datą [$4 $3, $2].</span>',
'filedelete-comment'     => 'Komentarz:',
'filedelete-submit'      => 'Skasuj',
'filedelete-success'     => "Skasowano plik '''$1'''.",
'filedelete-success-old' => '<span class="plainlinks">Skasowano plik \'\'\'[[Media:$1|$1]]\'\'\' w wersji z $3, $2.</span>',
'filedelete-nofile'      => "Plik '''$1''' nie istnieje w tym serwisie.",
'filedelete-nofile-old'  => "Nie ma zarchiwizowanje wersji '''$1''' o podanych atrybutach.",
'filedelete-iscurrent'   => 'Próbujesz skasować najnowszą wersję tego pliku. Musisz najpierw przywrócić starszą wersję.',

# MIME search
'mimesearch'         => 'Wyszukiwanie MIME',
'mimesearch-summary' => 'Ta strona umożliwia wyszukiwanie plików ze względu na ich typ MIME. Użycie: typtreści/podtyp, np. <tt>image/jpeg</tt>.',
'mimetype'           => 'Typ MIME:',
'download'           => 'pobierz',

# Unwatched pages
'unwatchedpages'         => 'Nieobserwowane strony',
'unwatchedpages-summary' => 'Poniżej znajduje się lista stron nieobserwowanych przez żadnego użytkownika.',

# List redirects
'listredirects'         => 'Lista przekierowań',
'listredirects-summary' => 'Poniżej znajduje się lista przekierowań.',

# Unused templates
'unusedtemplates'     => 'Nieużywane szablony',
'unusedtemplatestext' => 'Poniżej znajduje się lista szablonów nieużywanych na innych stronach.',
'unusedtemplateswlh'  => 'linkujące',

# Random page
'randompage'         => 'Losuj stronę',
'randompage-nopages' => 'Nie ma żadnych stron w tej przestrzeni nazw.',

# Random redirect
'randomredirect'         => 'Losowe przekierowanie',
'randomredirect-nopages' => 'Nie ma przekierowań w tej przestrzeni nazw.',

# Statistics
'statistics'             => 'Statystyka',
'sitestats'              => 'Statystyka artykułów',
'userstats'              => 'Statystyka użytkowników',
'sitestatstext'          => "W bazie danych jest w sumie '''\$1''' {{PLURAL:\$1|strona|stron}}.

Ta liczba uwzględnia strony dyskusji, strony na temat serwisu {{SITENAME}}, strony prowizoryczne (\"stuby\"), strony przekierowujące, oraz inne, które trudno uznać za artykuły. Wyłączając powyższe, jest prawdopodobnie '''\$2''' {{PLURAL:\$2|strona, którą można uznać za artykuł|stron, które można uznać za artykuły}}.

Przesłano \$8 {{PLURAL:\$8|plik|plików}}.

Od startu serwisu {{SITENAME}} {{PLURAL:\$3|była '''1''' odwiedzina strony|było '''\$3''' odwiedzin stron}} i wykonano '''\$4''' {{PLURAL:\$4|edycję|edycji}}. Daje to średnio '''\$5''' edycji na stronę i '''\$6''' odwiedzin na edycję.

Długość [http://meta.wikimedia.org/wiki/Help:Job_queue kolejki zadań] to '''\$7'''.",
'userstatstext'          => "Jest {{PLURAL:$1|'''1''' zarejestrowany użytkownik|'''$1''' zarejestrowanych użytkowników}}. {{PLURAL:$1|Użytkownik ten|Spośród nich '''$2''' (czyli '''$4%''')}} ma status $5.",
'statistics-mostpopular' => 'Najczęściej odwiedzane strony',

'disambiguations'         => 'Strony ujednoznaczniające',
'disambiguations-summary' => 'Poniżej znajduje się lista stron ujednoznaczniających.',
'disambiguationspage'     => '{{ns:template}}:disambig',
'disambiguations-text'    => "Poniższe artykuły odwołują się do '''stron ujednoznaczniających''', a powinny odwoływać się bezpośrednio do hasła związanego z treścią artykułu.<br />Strona uznawana jest za ujednoznaczniającą jeśli zawiera ona szablon określony w [[MediaWiki:disambiguationspage]].",

'doubleredirects'     => 'Podwójne przekierowania',
'doubleredirectstext' => 'Na tej liście mogą znajdować się przekierowania pozorne. Oznacza to, że poniżej pierwszej linii artykułu, zawierającej "#REDIRECT ...", może znajdować się dodatkowy tekst.<br />Każdy wiersz listy zawiera odwołania do pierwszego i drugiego przekierowania oraz pierwszą linię tekstu drugiego przekierowania. Umożliwia to w większości przypadków odnalezienie właściwego artykułu, do którego powinno się przekierowywać.',

'brokenredirects'        => 'Zerwane przekierowania',
'brokenredirectstext'    => 'Poniższe przekierowania wskazują na nieistniejące artykuły.',
'brokenredirects-edit'   => '(edytuj)',
'brokenredirects-delete' => '(usuń)',

'withoutinterwiki'        => 'Strony bez odnośników językowych',
'withoutinterwiki-header' => 'Poniższe strony nie odwołują się do innych wersji językowych.',

'fewestrevisions' => 'Strony z najmniejszą ilością wersji',

# Miscellaneous special pages
'nbytes'                          => '$1 {{PLURAL:$1|bajt|bajtów}}',
'ncategories'                     => '$1 {{PLURAL:$1|kategoria|kategorii}}',
'nlinks'                          => '$1 {{PLURAL:$1|link|linków}}',
'nmembers'                        => '$1 {{PLURAL:$1|element|elementów}}',
'nrevisions'                      => '$1 {{PLURAL:$1|wersja|wersji}}',
'nviews'                          => 'odwiedzono $1 {{PLURAL:$1|raz|razy}}',
'specialpage-empty'               => 'Ta strona jest pusta.',
'lonelypages'                     => 'Porzucone strony',
'lonelypagestext'                 => 'Do poniższych stron nie odwołuje się żadna inna strona na {{SITENAME}}.',
'uncategorizedpages'              => 'Nieskategoryzowane strony',
'uncategorizedpages-summary'      => 'Poniżej znajduje się lista stron nienależących do żadnej kategorii.',
'uncategorizedcategories'         => 'Nieskategoryzowane kategorie',
'uncategorizedcategories-summary' => 'Poniżej znajduje się lista kategorii nienależących do żadnej kategorii.',
'uncategorizedimages'             => 'Nieskategoryzowane pliki',
'uncategorizedimages-summary'     => 'Poniżej znajduje się lista plików nienależących do żadnej kategorii.',
'uncategorizedtemplates'          => 'Nieskategoryzowane szablony',
'unusedcategories'                => 'Nieużywane kategorie',
'unusedimages'                    => 'Nieużywane pliki',
'popularpages'                    => 'Najpopularniejsze strony',
'wantedcategories'                => 'Potrzebne kategorie',
'wantedpages'                     => 'Najpotrzebniejsze strony',
'mostlinked'                      => 'Najczęściej linkowane',
'mostlinkedcategories'            => 'Kategorie o największej liczbie artykułów',
'mostlinkedtemplates'             => 'Najczęściej linkowane szablony',
'mostcategories'                  => 'Artykuły z największą liczbą kategorii',
'mostcategories-summary'          => 'Poniżej znajduje się lista stron zawierających największą liczbę kategorii.',
'mostimages'                      => 'Najczęściej linkowane pliki',
'mostrevisions'                   => 'Najczęściej edytowane artykuły',
'mostrevisions-summary'           => 'Poniżej znajduje się lista najczęściej edytowanych stron.',
'allpages'                        => 'Wszystkie strony',
'prefixindex'                     => 'Wszystkie strony według prefiksu',
'shortpages'                      => 'Najkrótsze strony',
'shortpages-summary'              => 'Poniżej znajduje się lista najkrótszych stron.',
'longpages'                       => 'Najdłuższe strony',
'longpages-summary'               => 'Poniżej znajduje się lista najdłuższych stron.',
'deadendpages'                    => 'Strony bez linków',
'deadendpagestext'                => 'Poniższe strony nie posiadają odnośników do innych stron znajdujących się w tej wiki.',
'protectedpages'                  => 'Strony zabezpieczone',
'protectedpagestext'              => 'Poniższe strony zostały zabezpieczone przed przenoszeniem lub edytowaniem.',
'protectedpagesempty'             => 'Żadna strona nie jest obecnie zablokowana z podanymi parametrami.',
'protectedtitles'                 => 'Zablokowane nazwy artykułów',
'protectedtitlestext'             => 'Utworzenie artykułów o następujących nazwach jest zablokowane',
'protectedtitlesempty'            => 'Dla tych ustawień utworzenie artykułu o dowolnej nazwie nie jest zablokowane',
'listusers'                       => 'Lista użytkowników',
'listusers-summary'               => 'Poniżej znajduje się lista wszystkich użytkowników zarejestrowanych w tej wiki.',
'specialpages'                    => 'Strony specjalne',
'spheading'                       => 'Strony specjalne dla wszystkich użytkowników',
'restrictedpheading'              => 'Strony specjalne z ograniczonym dostępem',
'newpages'                        => 'Nowe strony',
'newpages-username'               => 'Nazwa użytkownika:',
'ancientpages'                    => 'Najstarsze strony',
'intl'                            => 'Linki interwiki',
'move'                            => 'Przenieś',
'movethispage'                    => 'Przenieś tę stronę',
'unusedimagestext'                => 'Pamiętaj, proszę, że inne witryny, np. projekty Wikimedia w innych językach, mogą odwoływać się do tych plików używając bezpośrednio URL. Dlatego też niektóre z plików mogą się znajdować na tej liście mimo, że żadna strona nie odwołuje się do nich.',
'unusedcategoriestext'            => 'Poniższe kategorie istnieją, choć nie korzysta z nich żaden artykuł ani kategoria.',
'notargettitle'                   => 'Wskazywana strona nie istnieje',
'notargettext'                    => 'Nie podano strony albo użytkownika, dla których ta operacja ma być wykonana.',
'pager-newer-n'                   => '{{PLURAL:$1|nowsza 1|nowsza $1}}',
'pager-older-n'                   => '{{PLURAL:$1|starsza 1|starsza $1}}',

# Book sources
'booksources'               => 'Książki',
'booksources-search-legend' => 'Szukaj źródeł książek',
'booksources-go'            => 'Pokaż',
'booksources-text'          => 'Poniżej znajduje się lista odnośników do innych stron, które pośredniczą w sprzedaży nowych i używanych książek, a także mogą posiadać dalsze informacje na temat poszukiwanej przez ciebie książki.',

'categoriespagetext' => 'Poniższe kategorie istnieją na wiki.',
'data'               => 'Dane',
'userrights'         => 'Zarządzanie prawami użytkowników',
'groups'             => 'Grupy użytkowników',
'alphaindexline'     => 'od $1 do $2',
'version'            => 'Wersja oprogramowania',

# Special:Log
'specialloguserlabel'  => 'Użytkownik:',
'speciallogtitlelabel' => 'Tytuł:',
'log'                  => 'Rejestry operacji',
'all-logs-page'        => 'Wszystkie rejestry',
'log-search-legend'    => 'Szukaj w rejestrze',
'log-search-submit'    => 'Szukaj',
'alllogstext'          => 'Połączone rejestry przesłanych plików, skasowanych stron, zabezpieczania, blokowania i nadawania uprawnień. Możesz zawęzić wynik przez wybranie typu rejestru, nazwy użytkownika albo nazwy interesującej Cię strony.',
'logempty'             => 'Brak pozycji w rejestrze.',
'log-title-wildcard'   => 'Szukaj tytułów zaczynających się od',

# Special:Allpages
'nextpage'          => 'Następna strona ($1)',
'prevpage'          => 'Poprzednia strona ($1)',
'allpagesfrom'      => 'Strony zaczynające się na:',
'allarticles'       => 'Wszystkie artykuły',
'allinnamespace'    => 'Wszystkie strony (w przestrzeni $1)',
'allnotinnamespace' => 'Wszystkie strony (oprócz przestrzeni nazw $1)',
'allpagesprev'      => 'Poprzednia',
'allpagesnext'      => 'Następna',
'allpagessubmit'    => 'Pokaż',
'allpagesprefix'    => 'Pokaż zaczynające się od:',
'allpagesbadtitle'  => 'Podana nazwa jest nieprawidłowa, zawiera prefiks międzyprojektowy lub międzyjęzykowy. Może ona także zawierać w sobie jeden lub więcej znaków których użycie w nazwach jest niedozwolone.',
'allpages-bad-ns'   => 'W serwisie {{SITENAME}} nie istnieje przestrzeń nazw "$1".',

# Special:Listusers
'listusersfrom'      => 'Wyświetl użytkowników zaczynając od:',
'listusers-submit'   => 'Pokaż',
'listusers-noresult' => 'Nie znaleziono użytkownika.',

# E-mail user
'mailnologin'     => 'Brak adresu',
'mailnologintext' => 'Musisz się [[{{ns:special}}:Userlogin|zalogować]] i mieć wpisany aktualny adres e-mailowy w swoich [[{{ns:special}}:Preferences|preferencjach]], aby móc wysłać e-mail do innych użytkowników.',
'emailuser'       => 'Wyślij e-mail do tego użytkownika',
'emailpage'       => 'Wyślij e-mail do użytkownika',
'emailpagetext'   => 'Jeśli ten użytkownik wpisał poprawny adres e-mailowy w swoich preferencjach, to poniższy formularz umożliwi Ci wysłanie jednej wiadomości. Adres e-mailowy, który został przez Ciebie wprowadzony w Twoich preferencjach pojawi się w polu "Od", dzięki temu odbiorca będzie mógł Ci odpowiedzieć.',
'usermailererror' => 'Obiekt Mail zwrócił błąd:',
'defemailsubject' => 'e-mail na {{SITENAME}}',
'noemailtitle'    => 'Brak adresu e-mailowego',
'noemailtext'     => 'Ten użytkownik nie podał poprawnego adresu e-mailowego, albo zadecydował, że nie chce otrzymywać e-maili od innych użytkowników.',
'emailfrom'       => 'Od',
'emailto'         => 'Do',
'emailsubject'    => 'Temat',
'emailmessage'    => 'Wiadomość',
'emailsend'       => 'Wyślij',
'emailccme'       => 'Wyślij mi kopię mojej wiadomości.',
'emailccsubject'  => 'Kopia twojej wiadomości do $1: $2',
'emailsent'       => 'Wiadomość została wysłana',
'emailsenttext'   => 'Twoja wiadomość została wysłana.',

# Watchlist
'watchlist'            => 'Obserwowane',
'mywatchlist'          => 'Obserwowane',
'watchlistfor'         => "(dla użytkownika '''$1''')",
'nowatchlist'          => 'Nie ma żadnych pozycji na liście obserwowanych przez Ciebie stron.',
'watchlistanontext'    => '$1 aby obejrzeć lub edytować elementy listy obserwowanych.',
'watchnologin'         => 'Brak logowania',
'watchnologintext'     => 'Musisz się [[{{ns:special}}:Userlogin|zalogować]] przed modyfikacją listy obserwowanych artykułów.',
'addedwatch'           => 'Dodana do listy obserwowanych',
'addedwatchtext'       => 'Strona "[[:$1|$1]]" została dodana do Twojej [[{{ns:special}}:Watchlist|listy obserwowanych]]. Na tej liście znajdzie się rejestr przyszłych zmian tej strony i związanej z nią strony dyskusji, a nazwa samej strony zostanie \'\'\'wytłuszczona\'\'\' na [[{{ns:special}}:Recentchanges|liście ostatnich zmian]], aby łatwiej było Ci sam fakt zmiany zauważyć.

Jeśli chcesz usunąć stronę ze swojej listy obserwowanych, kliknij na "Przestań obserwować".',
'removedwatch'         => 'Usunięto z listy obserwowanych',
'removedwatchtext'     => 'Strona "[[:$1]]" została usunięta z Twojej listy obserwowanych.',
'watch'                => 'Obserwuj',
'watchthispage'        => 'Obserwuj tę stronę',
'unwatch'              => 'Nie obserwuj',
'unwatchthispage'      => 'Przestań obserwować',
'notanarticle'         => 'To nie artykuł',
'watchnochange'        => 'Żadna z obserwowanych stron nie była edytowana w podanym okresie.',
'watchlist-details'    => 'Liczba obserwowanych przez Ciebie stron: $1, nie licząc stron dyskusji.',
'wlheader-enotif'      => '* Wysyłanie powiadomień na adres e-mail jest włączone.',
'wlheader-showupdated' => "* Strony które zostały zmienione od twojej ostatniej wizyty na nich zostały '''pogrubione'''",
'watchmethod-recent'   => 'poszukiwanie ostatnich zmian wśród obserwowanych stron',
'watchmethod-list'     => 'poszukiwanie obserwowanych stron wśród ostatnich zmian',
'watchlistcontains'    => 'Lista obserwowanych przez Ciebie stron zawiera {{PLURAL:$1|jedną pozycję|$1 pozycji}}.',
'iteminvalidname'      => 'Problem z pozycją "$1", niepoprawna nazwa...',
'wlnote'               => "Poniżej pokazano {{PLURAL:$1|ostatnią zmianę dokonaną|ostatnie '''$1''' zmian dokonanych}} w ciągu {{PLURAL:$2|ostatniej godziny|ostatnich '''$2''' godzin}}.",
'wlshowlast'           => 'Pokaż ostatnie $1 godzin $2 dni ($3)',
'watchlist-show-bots'  => 'pokaż edycje botów',
'watchlist-hide-bots'  => 'ukryj edycje botów',
'watchlist-show-own'   => 'pokaż moje edycje',
'watchlist-hide-own'   => 'ukryj moje edycje',
'watchlist-show-minor' => 'pokaż drobne zmiany',
'watchlist-hide-minor' => 'ukryj drobne zmiany',

# Displayed when you click the "watch" button and it's in the process of watching
'watching'   => 'Obserwuję...',
'unwatching' => 'Przestaję obserwować...',

'enotif_mailer'                => 'Powiadomienie z serwisu {{SITENAME}}',
'enotif_reset'                 => 'Zaznacz wszystkie strony jako odwiedzone',
'enotif_newpagetext'           => 'To jest nowa strona.',
'enotif_impersonal_salutation' => 'użytkownik na {{SITENAME}}',
'changed'                      => 'zmieniono',
'created'                      => 'utworzono',
'enotif_subject'               => 'Strona $PAGETITLE w serwisie {{SITENAME}} została $CHANGEDORCREATED przez użytkownika $PAGEEDITOR',
'enotif_lastvisited'           => 'Zobacz $1 w celu obejrzenia wszystkich zmian od twojej ostatniej wizyty.',
'enotif_lastdiff'              => 'Zobacz $1 w celu obejrzenia tej zmiany.',
'enotif_anon_editor'           => 'użytkownik anonimowy $1',
'enotif_body'                  => 'Drogi $WATCHINGUSERNAME,

strona $PAGETITLE w serwisie {{SITENAME}} została $CHANGEDORCREATED o $PAGEEDITDATE przez użytkownika $PAGEEDITOR, zobacz $PAGETITLE_URL w celu obejrzenia aktualnej wersji.

$NEWPAGE

Opis zmiany: $PAGESUMMARY $PAGEMINOREDIT

Skontaktuj się z autorem:
mail: $PAGEEDITOR_EMAIL
wiki: $PAGEEDITOR_WIKI

W przypadku kolejnych zmian nowe powiadomienia nie zostaną wysłane dopóki nie odwiedzisz tej strony. Możesz także zresetować flagi powiadomień dla wszystkich obserwowanych przez ciebie stron.

	Wiadomość systemu powiadomień serwisu {{SITENAME}}

--
W celu zmiany ustawień swojej listy obserwowanych odwiedź
{{fullurl:{{ns:special}}:Watchlist/edit}}

Pomoc:
{{fullurl:{{MediaWiki:Helppage}}}}',

# Delete/protect/revert
'deletepage'                  => 'Usuń stronę',
'confirm'                     => 'Potwierdź',
'excontent'                   => 'Zawartość strony "$1"',
'excontentauthor'             => 'treść: "$1" (jedyny autor: [[{{ns:special}}:Contributions/$2|$2]])',
'exbeforeblank'               => 'Poprzednia zawartość pustej strony "$1"',
'exblank'                     => 'Strona była pusta',
'confirmdelete'               => 'Potwierdź usunięcie',
'deletesub'                   => '(Usuwanie "$1")',
'historywarning'              => 'Uwaga! Strona, którą chcesz skasować ma starsze wersje:',
'confirmdeletetext'           => 'Zamierzasz trwale usunąć stronę lub plik z bazy danych razem z dotyczącą ich historią. Potwierdź, proszę, swoje zamiary, tzn., że rozumiesz konsekwencje, i że robisz to w zgodzie z [[{{MediaWiki:Policy-url}}|zasadami]].',
'actioncomplete'              => 'Operacja wykonana',
'deletedtext'                 => 'Usunięto "$1". Rejestr ostatnio dokonanych kasowań możesz obejrzeć tutaj: $2.',
'deletedarticle'              => 'usunięto "[[$1]]"',
'dellogpage'                  => 'Usunięte',
'dellogpagetext'              => 'To jest lista ostatnio wykonanych kasowań.',
'deletionlog'                 => 'rejestr usunięć',
'reverted'                    => 'Przywrócono poprzednią wersję',
'deletecomment'               => 'Powód usunięcia',
'deleteotherreason'           => 'Inna/dodatkowa przyczyna:',
'deletereasonotherlist'       => 'Inna przyczyna',
'deletereason-dropdown'       => '* Najczęstsze przyczyny usunięcia
** Prośba autora
** Naruszenie praw autorskich
** Wandalizm',
'rollback'                    => 'Cofnij edycję',
'rollback_short'              => 'Cofnij',
'rollbacklink'                => 'cofnij',
'rollbackfailed'              => 'Nie udało się cofnąć zmiany',
'cantrollback'                => 'Nie można cofnąć edycji; jest tylko jedna wersja tej strony.',
'alreadyrolled'               => 'Nie można cofnąć ostatniej zmiany strony [[:$1|$1]], której autorem jest [[{{ns:user}}:$2|$2]] ([[{{ns:user_talk}}:$2|Dyskusja]]). Ktoś inny zdążył już to zrobić lub wprowadził własne poprawki do treści strony. Autorem ostatniej zmiany jest teraz [[{{ns:user}}:$3|$3]] ([[{{ns:user_talk}}:$3|Dyskusja]]).',
'editcomment'                 => 'Opisano ją następująco: "<i>$1</i>".', # only shown if there is an edit comment
'revertpage'                  => 'Wycofano edycję użytkownika [[{{ns:special}}:Contributions/$2|$2]] ([[User_talk:$2|dyskusja]]). Autor przywróconej wersji to [[User:$1|$1]].',
'rollback-success'            => 'Wycofano edycje użytkownika $1; powrócono do ostatniej wersji autorstwa $2.',
'sessionfailure'              => 'Błąd weryfikacji sesji. Twoje polecenie zostało anulowane, aby uniknąć przechwycenia sesji.

Naciśnij "wstecz", przeładuj stronę, po czym ponownie wydaj polecenie.',
'protectlogpage'              => 'Zabezpieczone',
'protectlogtext'              => 'Poniżej znajduje się lista blokad założonych i zdjętych z pojedynczych stron. Aby przejrzeć listę obecnie działających zabezpieczeń, przejdź na stronę wykazu [[Special:Protectedpages|zabezpieczonych stron]].',
'protectedarticle'            => 'zabezpieczono "[[$1]]"',
'modifiedarticleprotection'   => 'zmieniono poziom zabezpieczenia dla hasła "[[$1]]"',
'unprotectedarticle'          => 'odbezpieczono "[[$1]]"',
'protectsub'                  => '(Zabezpieczanie "$1")',
'confirmprotect'              => 'Potwierdź zabezpieczenie',
'protectcomment'              => 'Powód zabezpieczenia',
'protectexpiry'               => 'upływa za',
'protect_expiry_invalid'      => 'Podany czas automatycznego odblokowania jest nieprawidłowy.',
'protect_expiry_old'          => 'Podany czas automatycznego odblokowania znajduje się w przeszłości.',
'unprotectsub'                => '(Odbezpieczanie "$1")',
'protect-unchain'             => 'Odblokowanie możliwości przenoszenia strony',
'protect-text'                => 'Możesz tu zobaczyć i zmienić poziom zabezpieczenia strony <strong>$1</strong>.',
'protect-locked-blocked'      => 'Nie możesz zmienić poziomów zabezpieczenia będąc zablokowanym. Obecne ustawienia dla strony <strong>$1</strong> to:',
'protect-locked-dblock'       => 'Nie można zmienić poziomu zabezpieczenia z powodu działającej blokady bazy danych. Obecne ustawienia dla strony <strong>$1</strong> to:',
'protect-locked-access'       => 'Nie masz uprawnień do zmiany poziomu zabezpieczenia strony. Obecne ustawienia dla strony <strong>$1</strong> to:',
'protect-cascadeon'           => 'Ta strona jest obecnie zabezpieczona przed edycją, ponieważ jest ona zawarta na {{PLURAL:$1|następującej stronie, która została zabezpieczona|następujących stronach, które zostały}} zabezpieczone z włączoną opcją dziedziczenia. Możesz zmienić poziom zabezpieczenia strony, ale nie wpłynie to na dziedziczenie zabezpiecznia.',
'protect-default'             => '(wszyscy)',
'protect-fallback'            => 'Wymaga uprawnień "$1"',
'protect-level-autoconfirmed' => 'tylko zarejestrowani',
'protect-level-sysop'         => 'tylko administratorzy',
'protect-summary-cascade'     => 'dziedziczenie',
'protect-expiring'            => 'wygasa $1 (UTC)',
'protect-cascade'             => 'Dziedziczenie zabezpieczenia - zabezpiecz wszystkie strony zawarte na tej stronie.',
'protect-cantedit'            => 'Nie możesz zmienić poziomu zabezpieczenia tej strony, ponieważ nie masz uprawnień do jej edycji.',
'restriction-type'            => 'Ograniczenia',
'restriction-level'           => 'Poziom',
'minimum-size'                => 'Minimalny rozmiar',
'maximum-size'                => 'Maksymalny rozmiar',
'pagesize'                    => '(bajtów)',

# Restrictions (nouns)
'restriction-edit'   => 'Edycja',
'restriction-move'   => 'Przeniesienie',
'restriction-create' => 'Stwórz',

# Restriction levels
'restriction-level-sysop'         => 'pełne zabezpieczenie',
'restriction-level-autoconfirmed' => 'częściowe zabezpieczenie',
'restriction-level-all'           => 'dowolny poziom',

# Undelete
'undelete'                     => 'Odtwórz skasowaną stronę',
'undeletepage'                 => 'Odtwarzanie skasowanych stron',
'viewdeletedpage'              => 'Zobacz skasowane wersje',
'undeletepagetext'             => 'Poniższe strony zostały skasowane, ale ich kopia wciąż znajduje się w archiwum. Archiwum co jakiś czas także jest kasowane.',
'undeleteextrahelp'            => "Aby odtworzyć całą stronę, pozostaw wszystkie pola niezaznaczone i kliknij '''Odtwórz'''. Aby wybrać częściowe odtworzenie należy zaznaczyć odpowiednie pole. Naciśnięcie '''Wyczyść''' wyczyści wszystkie pola, łącznie z opisem komentarza.",
'undeleterevisions'            => '{{PLURAL:$1|Jedna zarchiwizowana wersja|Liczba zarchiwizowanych wersji: $1}}',
'undeletehistory'              => 'Odtworzenie strony spowoduje przywrócenie także jej wszystkich poprzednich wersji. Jeśli od czasu skasowania ktoś utworzył nową stronę o tej nazwie, odtwarzane wersje znajdą się w jej historii, a obecna wersja pozostanie bez zmian.',
'undeleterevdel'               => 'Odtworzenie strony nie zostanie przeprowadzone w wypadku, gdyby miało skutkować częściowym usunięciem aktualnej wersji. W takiej sytuacji należy odznaczyć lub przywrócić widoczność najnowszym skasowanym wersjom. Nie zostaną pokazane wersje plików, do oglądania których nie masz uprawnień.',
'undeletehistorynoadmin'       => 'Ten artykuł został skasowany. Przyczyna usunięcia podana jest w podsumowaniu poniżej, razem z danymi użytkownika, który edytował artykuł przed skasowaniem. Sama treść usuniętych wersji jest dostępna jedynie dla administratorów.',
'undelete-revision'            => 'Skasowano wersję $1 z $2 autorstwa $3:',
'undeleterevision-missing'     => 'Nieprawidłowa lub brakująca wersja. Możesz mieć zły link lub wersja mogła zostać odtworzona lub usunięta z archiwum.',
'undelete-nodiff'              => 'Nie znaleziono poprzednich wersji.',
'undeletebtn'                  => 'Odtwórz',
'undeletereset'                => 'Wyczyść',
'undeletecomment'              => 'Powód odtworzenia:',
'undeletedarticle'             => 'odtworzono "$1"',
'undeletedrevisions'           => 'Liczba odtworzonych wersji: $1',
'undeletedrevisions-files'     => 'Odtworzono $1 {{PLURAL:$1|wersję|wersji}} i $2 {{PLURAL:$2|plik|plików}}',
'undeletedfiles'               => 'Odtworzono $1 {{PLURAL:$1|plik|pliki}}',
'cannotundelete'               => 'Odtworzenie nie powiodło się. Ktoś inny mógł odtworzyć stronę pierwszy.',
'undeletedpage'                => '<big>Odtworzono stronę $1.</big>

Zobacz [[{{ns:special}}:Log/delete]], jeśli chcesz przejrzeć rejestr ostatnio skasowanych i odtworzonych stron.',
'undelete-header'              => 'Zobacz [[Special:Log/delete|rejestr usunięć]] aby sprawdzić ostatnio skasowane strony.',
'undelete-search-box'          => 'Szukaj usuniętych stron',
'undelete-search-prefix'       => 'Strony zaczynające się od:',
'undelete-search-submit'       => 'Szukaj',
'undelete-no-results'          => 'Nie znaleziono wskazanych stron w archiwum usuniętych.',
'undelete-filename-mismatch'   => 'Nie można odtworzyć wersji pliku z datą $1: niezgodność nazwy pliku',
'undelete-bad-store-key'       => 'Nie można odtworzyć wersji pliku z datą $1: przed usunięciem brakowało pliku.',
'undelete-cleanup-error'       => 'Błąd przy odtwarzaniu nieużywanego archiwum pliku "$1".',
'undelete-missing-filearchive' => 'Nie udało się odtworzyć archiwum pliku o ID $1, ponieważ nie jest w bazie danych. Być może plik został już odtworzony.',
'undelete-error-short'         => 'Błąd przy odtwarzaniu pliku: $1',
'undelete-error-long'          => 'Napotkano błędy przy odtwarzaniu pliku:

$1',

# Namespace form on various pages
'namespace'      => 'Przestrzeń nazw:',
'invert'         => 'Odwróć wybór',
'blanknamespace' => '(główna)',

# Contributions
'contributions' => 'Wkład użytkownika',
'mycontris'     => 'Moje edycje',
'contribsub2'   => 'Dla użytkownika $1 ($2)',
'nocontribs'    => 'Brak zmian odpowiadających tym kryteriom.',
'ucnote'        => 'Oto lista ostatnich <b>$1</b> zmian dokonanych przez użytkownika w ciągu ostatnich <b>$2</b> dni.',
'uclinks'       => 'Zobacz ostatnie $1 zmian; zobacz ostatnie $2 dni.',
'uctop'         => ' (jako ostatnia)',
'month'         => 'Od miesiąca (i wcześniejsze):',
'year'          => 'Od roku (i wcześniejsze):',

'sp-contributions-newbies'     => 'Pokaż wkład nowych użytkowników',
'sp-contributions-newbies-sub' => 'Dla nowych użytkowników',
'sp-contributions-blocklog'    => 'blokady',
'sp-contributions-search'      => 'Szukaj wkładu',
'sp-contributions-username'    => 'Adres IP lub nazwa użytkownika:',
'sp-contributions-submit'      => 'Szukaj',

'sp-newimages-showfrom' => 'Pokaż nowe grafiki od $1',

# What links here
'whatlinkshere'       => 'Linkujące',
'whatlinkshere-title' => 'Strony linkujące do $1',
'whatlinkshere-page'  => 'Strona:',
'linklistsub'         => '(Lista linków)',
'linkshere'           => "Następujące strony odwołują się do '''[[:$1]]''':",
'nolinkshere'         => "Żadna strona nie odwołuje się do '''[[:$1]]'''.",
'nolinkshere-ns'      => "Żadna strona nie odwołuje się do '''[[:$1]]''' w wybranej przestrzeni nazw.",
'isredirect'          => 'strona przekierowująca',
'istemplate'          => 'dołączony szablon',
'whatlinkshere-prev'  => '{{PLURAL:$1|poprzednie|poprzednie $1}}',
'whatlinkshere-next'  => '{{PLURAL:$1|następne|następne $1}}',
'whatlinkshere-links' => '← linkujące',

# Block/unblock
'blockip'                     => 'Zablokuj użytkownika',
'blockiptext'                 => 'Użyj poniższego formularza, aby zablokować prawo zapisu spod określonego adresu IP. Powinno się to robić jedynie po to, by zapobiec wandalizmowi, a zarazem w zgodzie z [[{{MediaWiki:Policy-url}}|zasadami]]. Podaj powód (np. umieszczając nazwy stron, na których dopuszczono się wandalizmu).',
'ipaddress'                   => 'Adres IP',
'ipadressorusername'          => 'Adres IP lub nazwa użytkownika',
'ipbexpiry'                   => 'Czas blokady',
'ipbreason'                   => 'Powód',
'ipbreasonotherlist'          => 'Inny powód',
'ipbreason-dropdown'          => '*Najczęstsze powody blokad
** Ataki na innych użytkowników
** Naruszenie praw autorskich
** Niedozwolona nazwa użytkownika
** Open proxy/Tor
** Spamowanie
** Usuwanie treści stron
** Wprowadzanie fałszywych informacji
** Wulgaryzmy
** Wypisywanie bzdur na stronach',
'ipbanononly'                 => 'Zablokuj tylko anonimowych użytkowników',
'ipbcreateaccount'            => 'Zapobiegnij utworzeniu konta',
'ipbemailban'                 => 'Zablokuj możliwość wysyłania e-maili',
'ipbenableautoblock'          => 'Automatycznie blokuj adresy IP, spod których łączył się ten użytkownik.',
'ipbsubmit'                   => 'Zablokuj użytkownika',
'ipbother'                    => 'Inny czas',
'ipboptions'                  => '2 godziny:2 hours,1 dzień:1 day,3 dni:3 days,1 tydzień:1 week,2 tygodnie:2 weeks,1 miesiąc:1 month,3 miesiące:3 months,6 miesięcy:6 months,1 rok:1 year,nieskończony:infinite', # display1:time1,display2:time2,...
'ipbotheroption'              => 'inny',
'ipbotherreason'              => 'Inne uzasadnienie',
'ipbhidename'                 => 'Ukryj nazwę użytkownika/adres IP w rejestrze blokad, na liście bieżących blokad i liście użytkowników',
'badipaddress'                => 'Adres IP jest źle utworzony',
'blockipsuccesssub'           => 'Zablokowanie powiodło się',
'blockipsuccesstext'          => 'Użytkownik [[{{ns:special}}:Contributions/$1|$1]] został zablokowany. <br />Przejdź do [[{{ns:special}}:Ipblocklist|listy zablokowanych adresów IP]] by przejrzeć blokady.',
'ipb-edit-dropdown'           => 'Edytuj przyczynę blokady',
'ipb-unblock-addr'            => 'Odblokuj $1',
'ipb-unblock'                 => 'Odblokuj użytkownika lub adres IP',
'ipb-blocklist-addr'          => 'Zobacz istniejące blokady $1',
'ipb-blocklist'               => 'Zobacz istniejące blokady',
'unblockip'                   => 'Odblokuj użytkownika',
'unblockiptext'               => 'Użyj poniższego formularza by przywrócić prawa zapisu dla poprzednio zablokowanego użytkownika lub adresu IP.',
'ipusubmit'                   => 'Odblokuj użytkownika',
'unblocked'                   => '[[User:$1|$1]] został odblokowany.',
'unblocked-id'                => 'Blokada $1 została zdjęta',
'ipblocklist'                 => 'Lista zablokowanych użytkowników i adresów IP',
'ipblocklist-legend'          => 'Znajdź zablokowanego użytkownika',
'ipblocklist-username'        => 'Nazwa użytkownika lub adres IP:',
'ipblocklist-submit'          => 'Szukaj',
'blocklistline'               => '$1, $2 blokuje $3 ($4)',
'infiniteblock'               => 'na zawsze',
'expiringblock'               => 'wygasa $1',
'anononlyblock'               => 'tylko anonimowi',
'noautoblockblock'            => 'autoblok wyłączony',
'createaccountblock'          => 'blokada tworzenia kont',
'emailblock'                  => 'zablokowany e-mail',
'ipblocklist-empty'           => 'Lista blokad jest pusta.',
'ipblocklist-no-results'      => 'Podany adres IP lub użytkownik nie jest zablokowany.',
'blocklink'                   => 'zablokuj',
'unblocklink'                 => 'odblokuj',
'contribslink'                => 'wkład',
'autoblocker'                 => 'Zablokowano Cię automatycznie, ponieważ używasz tego samego adresu IP, co użytkownik "[[{{ns:user}}:$1|$1]]". Powód blokady założonej na konto $1: "$2"',
'blocklogpage'                => 'Zablokowani',
'blocklogentry'               => 'zablokowano "[[$1]]", czas blokady: $2 $3',
'blocklogtext'                => 'Poniżej znajduje się lista blokad założonych i zdjętych z poszczególnych adresów IP. Na liście nie znajdą się adresy IP, które zablokowano w sposób automatyczny. By przejrzeć listę obecnie aktywnych blokad, przejdź na stronę [[Special:Ipblocklist|zablokowanych adresów i użytkowników]].',
'unblocklogentry'             => 'odblokowano "$1"',
'block-log-flags-anononly'    => 'tylko anonimowi',
'block-log-flags-nocreate'    => 'blokada tworzenia konta',
'block-log-flags-noautoblock' => 'autoblok wyłączony',
'block-log-flags-noemail'     => 'e-mail zablokowany',
'range_block_disabled'        => 'Możliwość blokowania zakresu numerów IP została wyłączona.',
'ipb_expiry_invalid'          => 'Błędny czas blokady.',
'ipb_already_blocked'         => '"$1" jest już zablokowany.',
'ipb_cant_unblock'            => 'Błąd: Blokada o ID $1 nie została znaleziona. Mogła ona zostać odblokowana wcześniej.',
'ipb_blocked_as_range'        => 'Błąd: Adres IP $1 nie został zablokowany bezpośrednio i nie może zostać odblokowany. Należy on do zablokowanego zakresu adresów $2. Odblokować można tylko cały zakres.',
'ip_range_invalid'            => 'Niewłaściwy zakres adresów IP.',
'blockme'                     => 'Zablokuj mnie',
'proxyblocker'                => 'Blokowanie proxy',
'proxyblocker-disabled'       => 'Ta funkcja jest wyłączona.',
'proxyblockreason'            => 'Twój adres IP został zablokowany - jest to otwarte proxy. Sprawę należy rozwiązać u dostawcy Internetu.',
'proxyblocksuccess'           => 'Wykonane.',
'sorbsreason'                 => 'Twój adres IP znajduje się na liście serwerów open proxy w DNSBL.',
'sorbs_create_account_reason' => 'Twój adres IP znajduje się na liście serwerów open proxy w DNSBL. Nie możesz utworzyć konta.',

# Developer tools
'lockdb'              => 'Zablokuj bazę danych',
'unlockdb'            => 'Odblokuj bazę danych',
'lockdbtext'          => 'Zablokowanie bazy danych uniemożliwi wszystkim użytkownikom edycję stron, zmianę preferencji, edycję list obserwowanych artykułów oraz inne czynności wymagające dostępu do bazy danych. Potwierdź, proszę, że to jest zgodne z Twoimi zamiarami, i że odblokujesz bazę danych, gdy tylko zakończysz zadania administracyjne.',
'unlockdbtext'        => 'Odblokowanie bazy danych umożliwi wszystkim użytkownikom edycję stron, zmianę preferencji, edycję list obserwowanych artykułów oraz inne czynności związane ze zmianami w bazie danych. Potwierdź, proszę, że to jest zgodne z Twoimi zamiarami.',
'lockconfirm'         => 'Tak, naprawdę chcę zablokować bazę danych.',
'unlockconfirm'       => 'Tak, naprawdę chcę odblokować bazę danych.',
'lockbtn'             => 'Zablokuj bazę danych',
'unlockbtn'           => 'Odblokuj bazę danych',
'locknoconfirm'       => 'Nie zaznaczyłeś pola potwierdzenia.',
'lockdbsuccesssub'    => 'Baza danych została pomyślnie zablokowana',
'unlockdbsuccesssub'  => 'Blokada bazy danych usunięta',
'lockdbsuccesstext'   => 'Baza danych została zablokowana.<br />Pamiętaj by [[{{ns:special}}:Unlockdb|usunąć blokadę]] po zakończeniu działań administracyjnych.',
'unlockdbsuccesstext' => 'Baza danych została odblokowana.',
'lockfilenotwritable' => 'Nie można zapisać pliku blokady bazy danych. Aby móc blokować i odblokowywać bazę danych, plik musi mieć właściwe prawa dostępu.',
'databasenotlocked'   => 'Baza danych nie jest zablokowana.',

# Move page
'movepage'                => 'Przeniesienie strony',
'movepagetext'            => "Za pomocą poniższego formularza zmienisz nazwę strony, przenosząc jednocześnie jej historię.
Pod starym tytułem zostanie umieszczona strona przekierowująca. Linki do starego tytułu pozostaną niezmienione.
Upewnij się, że uwzględniasz podwójne lub zerwane przekierowania. Odpowiadasz za to, żeby linki odnosiły się do właściwych artykułów!

Strona '''nie''' będzie przeniesiona jeśli:
*jest pusta i nigdy nie była edytowana
*jest stroną przekierowującą
*strona o nowej nazwie już istnieje

'''UWAGA!'''
Może to być drastyczna lub nieprzewidywalna zmiana w przypadku popularnych stron; upewnij się co do konsekwencji tej operacji zanim się na nią zdecydujesz.",
'movepagetalktext'        => "Odpowiednia strona dyskusji, jeśli istnieje, będzie przeniesiona automatycznie, pod warunkiem, że:
*nie przenosisz strony do innej przestrzeni nazw
*nie istnieje strona dyskusji o nowej nazwie
W takich przypadkach tekst dyskusji trzeba przenieść, i ewentualnie połączyć z istniejącym, ręcznie. Możesz też zrezygnować z przeniesienia dyskusji (poniższy ''checkbox'').",
'movearticle'             => 'Przeniesienie strony',
'movenologin'             => 'Brak logowania',
'movenologintext'         => 'Musisz być zarejestrowanym i [[{{ns:special}}:Userlogin|zalogowanym]] użytkownikiem aby móc przenieść stronę.',
'movenotallowed'          => 'Nie masz uprawnień do przenoszenia stron na {{SITENAME}}.',
'newtitle'                => 'Nowy tytuł',
'move-watch'              => 'Obserwuj tę stronę',
'movepagebtn'             => 'Przenieś stronę',
'pagemovedsub'            => 'Przeniesienie powiodło się',
'movepage-moved'          => '<big>\'\'\'Strona "$1" została przeniesiona do "$2".\'\'\'</big>', # The two titles are passed in plain text as $3 and $4 to allow additional goodies in the message.
'articleexists'           => 'Strona o podanej nazwie już istnieje albo wybrana przez Ciebie nazwa nie jest poprawna. Wybierz, proszę, nową nazwę.',
'cantmove-titleprotected' => 'Nie możesz przenieść strony, ponieważ nowa nazwa strony jest niedozwolona z powodu zabezpieczenia przed utworzeniem',
'talkexists'              => 'Strona artykułu została przeniesiona, natomiast strona dyskusji nie - strona dyskusji o nowym tytule już istnieje. Połącz, proszę, teksty obu dyskusji ręcznie.',
'movedto'                 => 'przeniesiono do',
'movetalk'                => 'Przenieś także stronę dyskusji, jeśli to możliwe.',
'talkpagemoved'           => 'Odpowiednia strona dyskusji także została przeniesiona.',
'talkpagenotmoved'        => 'Odpowiednia strona dyskusji <strong>nie</strong> została przeniesiona.',
'1movedto2'               => '[[$1]] przeniesiono do [[$2]]',
'1movedto2_redir'         => '[[$1]] przeniesiono do [[$2]] nad przekierowaniem',
'movelogpage'             => 'Przeniesione',
'movelogpagetext'         => 'Oto lista stron, które ostatnio zostały przeniesione.',
'movereason'              => 'Powód',
'revertmove'              => 'cofnij',
'delete_and_move'         => 'Usuń i przenieś',
'delete_and_move_text'    => '== Wymagane usunięcie ==

Artykuł docelowy "[[:$1|$1]]" już istnieje. Czy chcesz go usunąć, by zrobić miejsce dla przenoszonego artykułu?',
'delete_and_move_confirm' => 'Tak, usuń stronę',
'delete_and_move_reason'  => 'Usunięto by zrobić miejsce dla przenoszonego artykułu',
'selfmove'                => 'Nazwy stron źródłowej i docelowej są takie same. Strony nie można przenieść na nią samą!',
'immobile_namespace'      => 'Docelowy tytuł jest specjalnego typu. Nie można przenieść do tej przestrzeni nazw.',

# Export
'export'            => 'Eksport stron',
'exporttext'        => 'Możesz wyeksportować tekst i historię edycji danej strony lub zestawu stron w postaci XML. Taki zrzut można potem (jak import już będzie działać) zaimportować do innej wiki działającej na oprogramowaniu MediaWiki, obrabiać lub po prostu trzymać dla zabawy.

Żeby uzyskać kilka stron wpisz ich nazwy jedna pod drugą.

Można również użyć łącza, np. [[{{ns:special}}:Export/{{Mediawiki:mainpage}}]] dla strony {{Mediawiki:mainpage}}.',
'exportcuronly'     => 'Tylko bieżąca wersja, bez historii',
'exportnohistory'   => "----
'''Uwaga:''' możliwość eksportowania pełnej historii stron została wyłączona.",
'export-submit'     => 'Eksportuj',
'export-addcattext' => 'Dodaj strony z kategorii:',
'export-addcat'     => 'Dodaj',
'export-download'   => 'Oferuj do zapisania jako plik',

# Namespace 8 related
'allmessages'               => 'Komunikaty',
'allmessagesname'           => 'Nazwa',
'allmessagesdefault'        => 'Tekst domyślny',
'allmessagescurrent'        => 'Tekst obecny',
'allmessagestext'           => 'Oto lista wszystkich komunikatów dostępnych w przestrzeni nazw MediaWiki:',
'allmessagesnotsupportedDB' => "Strona '''{{ns:special}}:Allmessages''' nie może być użyta, ponieważ '''\$wgUseDatabaseMessages''' jest wyłączone.",
'allmessagesfilter'         => 'Filtr nazw komunikatów:',
'allmessagesmodified'       => 'Pokaż tylko zmodyfikowane',

# Thumbnails
'thumbnail-more'           => 'Powiększ',
'missingimage'             => '<b>Brak grafiki</b><br /><i>$1</i>',
'filemissing'              => 'Brak pliku',
'thumbnail_error'          => 'Błąd przy generowaniu miniatury: $1',
'djvu_page_error'          => 'Strona DjVu poza zakresem',
'djvu_no_xml'              => 'Nie można pobrać XML-u dla pliku DjVu',
'thumbnail_invalid_params' => 'Nieprawidłowe parametry miniatury',
'thumbnail_dest_directory' => 'Nie można utworzyć katalogu docelowego',

# Special:Import
'import'                     => 'Importuj strony',
'importinterwiki'            => 'Import transwiki',
'import-interwiki-text'      => 'Wybierz wiki i nazwę strony do importowania. Daty oraz nazwy autorów zostaną zachowane. Wszystkie operacje importu transwiki są odnotowywane w [[{{ns:special}}:Log/import|rejestrze importu]].',
'import-interwiki-history'   => 'Kopiuj całą historię edycji tej strony',
'import-interwiki-submit'    => 'Importuj',
'import-interwiki-namespace' => 'Przenieś strony do przestrzeni nazw:',
'importtext'                 => 'Używając narzędzia Special:Export wyeksportuj plik ze źródłowej wiki, zapisz go na swoim dysku, a następnie prześlij go tutaj.',
'importstart'                => 'Trwa importowanie stron...',
'import-revision-count'      => '$1 {{PLURAL:$1|wersja|wersji}}',
'importnopages'              => 'Brak stron do importu.',
'importfailed'               => 'Import nie powiódł się: $1',
'importunknownsource'        => 'Nieznany format importu źródłowego',
'importcantopen'             => 'Nie można otworzyć importowanego pliku',
'importbadinterwiki'         => 'Błędny link interwiki',
'importnotext'               => 'Brak tekstu lub zawartości',
'importsuccess'              => 'Import zakończony powodzeniem!',
'importhistoryconflict'      => 'Wystąpił konflikt wersji (ta strona mogła zostać importowana już wcześniej)',
'importnosources'            => 'Możliwość bezpośredniego importu historii została wyłączona: nie zdefiniowano źródła.',
'importnofile'               => 'Importowany plik nie został załadowany.',
'importuploaderror'          => 'Przesłanie pliku nie powiodło się. Możliwe, że plik jest większy od dozwolonego limitu.',

# Import log
'importlogpage'                    => 'Rejestr importu',
'importlogpagetext'                => 'Rejestr przeprowadzonych importów stron z innych serwisów wiki.',
'import-logentry-upload'           => 'zaimportowano [[$1]] przez przesłanie pliku',
'import-logentry-upload-detail'    => '$1 wersji',
'import-logentry-interwiki'        => 'zaimportowano $1 przez transwiki',
'import-logentry-interwiki-detail' => '$1 wersji z $2',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'Moja osobista strona',
'tooltip-pt-anonuserpage'         => 'Strona użytkownika dla numeru IP spod którego edytujesz',
'tooltip-pt-mytalk'               => 'Moja strona dyskusji',
'tooltip-pt-anontalk'             => 'Dyskusja użytkownika dla numeru IP spod którego edytujesz',
'tooltip-pt-preferences'          => 'Moje preferencje',
'tooltip-pt-watchlist'            => 'Lista stron przez Ciebie obserwowanych',
'tooltip-pt-mycontris'            => 'Lista moich edycji',
'tooltip-pt-login'                => 'Zachęcamy do zalogowania się, choć nie jest to obowiązkowe.',
'tooltip-pt-anonlogin'            => 'Zachęcamy do zalogowania się, choć nie jest to obowiązkowe',
'tooltip-pt-logout'               => 'Wyloguj się z wiki',
'tooltip-ca-talk'                 => 'Dyskusja o zawartości tej strony.',
'tooltip-ca-edit'                 => 'Możesz edytować tę stronę. Przed zapisaniem zmian użyj przycisku podgląd.',
'tooltip-ca-addsection'           => 'Dodaj swój komentarz do dyskusji',
'tooltip-ca-viewsource'           => 'Ta strona jest zabezpieczona. Możesz zobaczyć tekst źródłowy.',
'tooltip-ca-history'              => 'Starsze wersje tej strony.',
'tooltip-ca-protect'              => 'Zabezpiecz tę stronę.',
'tooltip-ca-delete'               => 'Usuń tę stronę',
'tooltip-ca-undelete'             => 'Przywróć wersję tej strony sprzed usunięcia',
'tooltip-ca-move'                 => 'Przenieś tę stronę.',
'tooltip-ca-watch'                => 'Dodaj tę stronę do listy obserwowanych',
'tooltip-ca-unwatch'              => 'Usuń tę stronę z listy obserwowanych',
'tooltip-search'                  => 'Przeszukaj serwis {{SITENAME}}',
'tooltip-search-go'               => 'Przejście do strony o dokładnie takiej nazwie (o ile istnieje)',
'tooltip-search-fulltext'         => 'Szukanie wprowadzonego tekstu',
'tooltip-p-logo'                  => 'Strona główna',
'tooltip-n-mainpage'              => 'Zobacz stronę główną',
'tooltip-n-portal'                => 'O projekcie, co możesz zrobić, gdzie możesz znaleźć informacje',
'tooltip-n-currentevents'         => 'Informacje o aktualnych wydarzeniach',
'tooltip-n-recentchanges'         => 'Lista ostatnich zmian na wiki',
'tooltip-n-randompage'            => 'Pokaż losowo wybraną stronę',
'tooltip-n-help'                  => 'Zapoznaj się z obsługą wiki',
'tooltip-n-sitesupport'           => 'Wesprzyj nas',
'tooltip-t-whatlinkshere'         => 'Pokaż listę stron linkujących do tego artykułu',
'tooltip-t-recentchangeslinked'   => 'Ostatnie zmiany w stronach linkujących do tej strony',
'tooltip-feed-rss'                => 'Kanał RSS dla tej strony',
'tooltip-feed-atom'               => 'Kanał Atom dla tej strony',
'tooltip-t-contributions'         => 'Pokaż listę edycji tego użytkownika',
'tooltip-t-emailuser'             => 'Wyślij e-mail do tego użytkownika',
'tooltip-t-upload'                => 'Wyślij plik na serwer',
'tooltip-t-specialpages'          => 'Lista wszystkich specjalnych stron',
'tooltip-t-print'                 => 'Wersja do wydruku',
'tooltip-t-permalink'             => 'Stały link do tej wersji strony',
'tooltip-ca-nstab-main'           => 'Zobacz stronę artykułu',
'tooltip-ca-nstab-user'           => 'Zobacz stronę osobistą użytkownika',
'tooltip-ca-nstab-media'          => 'Pokaż stronę pliku',
'tooltip-ca-nstab-special'        => 'To jest strona specjalna. Nie możesz jej edytować.',
'tooltip-ca-nstab-project'        => 'Zobacz stronę projektu',
'tooltip-ca-nstab-image'          => 'Zobacz stronę grafiki',
'tooltip-ca-nstab-mediawiki'      => 'Zobacz komunikat systemowy',
'tooltip-ca-nstab-template'       => 'Zobacz szablon',
'tooltip-ca-nstab-help'           => 'Zobacz stronę pomocy',
'tooltip-ca-nstab-category'       => 'Zobacz stronę kategorii',
'tooltip-minoredit'               => 'Oznacz zmianę jako drobną',
'tooltip-save'                    => 'Zapisz zmiany',
'tooltip-preview'                 => 'Obejrzyj efekt swojej edycji przed zapisaniem zmian!',
'tooltip-diff'                    => 'Pokaż zmiany dokonane w tekście.',
'tooltip-compareselectedversions' => 'Zobacz różnice między dwoma wybranymi wersjami strony.',
'tooltip-watch'                   => 'Dodaj tę stronę do listy obserwowanych',
'tooltip-recreate'                => 'Odtworzono stronę pomimo jej wcześniejszego usunięcia.',
'tooltip-upload'                  => 'Rozpoczęcie ładowania',

# Stylesheets
'common.css'   => '/* Umieszczony tutaj kod CSS zostanie zastosowany we wszystkich skórkach */',
'monobook.css' => '/* Umieszczony tutaj kod CSS wpłynie na wygląd skórki Monobook */',

# Scripts
'common.js'   => '/* Umieszczony tutaj kod JavaScript zostanie załadowany przez każdego użytkownika, podczas każdego ładowania strony. */',
'monobook.js' => '/* Zobacz [[MediaWiki:common.js]] */',

# Metadata
'nodublincore'      => 'Metadane zgodne z Dublin Core RDF zostały wyłączone dla tego serwera.',
'nocreativecommons' => 'Metadane zgodne z Creative Commons RDF zostały wyłączone dla tego serwera.',
'notacceptable'     => 'Serwer wiki nie jest w stanie dostarczyć danych, które Twoja przeglądarka będzie w stanie odczytać.',

# Attribution
'anonymous'        => 'Anonimowy użytkownicy serwisu {{SITENAME}}',
'siteuser'         => 'Użytkownik serwisu {{SITENAME}} - $1',
'lastmodifiedatby' => 'Ostatnia edycja tej strony: $2, $1 (autor zmian: $3)', # $1 date, $2 time, $3 user
'and'              => 'oraz',
'othercontribs'    => 'Inni autorzy: $1.',
'others'           => 'inni',
'siteusers'        => 'Użytkownicy serwisu {{SITENAME}} - $1',
'creditspage'      => 'Autorzy',
'nocredits'        => 'Nie ma informacji o autorach tej strony.',

# Spam protection
'spamprotectiontitle'    => 'Filtr antyspamowy',
'spamprotectiontext'     => 'Strona, którą chciałeś(-aś) zapisać, została zablokowana przez filtr antyspamowy. Najprawdopodobniej zostało to spowodowane przez link do zewnętrznej strony internetowej.',
'spamprotectionmatch'    => 'Tekst, który uruchomił nasz filtr antyspamowy to: $1',
'subcategorycount'       => '{{PLURAL:$1|Jest jedna podkategoria|Liczba podkategorii: $1}}',
'categoryarticlecount'   => '{{PLURAL:$1|Jest jeden artykuł w tej kategorii|Liczba artykułów w tej kategorii: $1}}',
'category-media-count'   => '{{PLURAL:$1|Jest jeden plik w tej kategorii|Liczba plików w tej kategorii: $1}}',
'listingcontinuesabbrev' => 'c.d.',
'spambot_username'       => 'MediaWiki czyszczenie spamu',
'spam_reverting'         => 'Przywracanie ostatniej wersji nie zawierającej odnośników do $1',
'spam_blanking'          => 'Wszystkie wersje zawierały odnośniki do $1; czyszczenie strony',

# Info page
'infosubtitle'   => 'Informacja o stronie',
'numedits'       => 'Liczba edycji (artykuł): $1',
'numtalkedits'   => 'Liczba edycji (strona dyskusji): $1',
'numwatchers'    => 'Liczba obserwujących: $1',
'numauthors'     => 'Liczba autorów (artykuł): $1',
'numtalkauthors' => 'Liczba autorów (strona dyskusji): $1',

# Math options
'mw_math_png'    => 'Zawsze jako PNG',
'mw_math_simple' => 'HTML dla prostych, dla reszty PNG',
'mw_math_html'   => 'Spróbuj HTML; jeśli zawiedzie, to PNG',
'mw_math_source' => 'Pozostaw w TeXu (dla przeglądarek tekstowych)',
'mw_math_modern' => 'HTML, dla nowszych przeglądarek',
'mw_math_mathml' => 'MathML (eksperymentalne)',

# Patrolling
'markaspatrolleddiff'                 => 'Oznacz jako sprawdzone',
'markaspatrolledtext'                 => 'Oznacz ten artykuł jako sprawdzony',
'markedaspatrolled'                   => 'Oznaczono jako sprawdzone',
'markedaspatrolledtext'               => 'Ta wersja została oznaczona jako sprawdzona.',
'rcpatroldisabled'                    => 'Wyłączono patrolowanie na ostatnich zmianach',
'rcpatroldisabledtext'                => 'Patrolowanie ostatnich zmian jest obecnie wyłączone',
'markedaspatrollederror'              => 'Nie można oznaczyć jako sprawdzone',
'markedaspatrollederrortext'          => 'Musisz wybrać wersję żeby oznaczyć ją jako sprawdzoną.',
'markedaspatrollederror-noautopatrol' => 'Nie masz uprawnień wymaganych do oznaczania swoich edycji jako sprawdzone.',

# Patrol log
'patrol-log-page' => 'Patrolowane',
'patrol-log-line' => 'oznacza wersję $1 hasła $2 jako sprawdzoną $3',
'patrol-log-auto' => '(automatycznie)',

# Image deletion
'deletedrevision'                 => 'Skasowano poprzednie wersje $1',
'filedeleteerror-short'           => 'Błąd przy usuwaniu pliku: $1',
'filedeleteerror-long'            => 'Wystąpiły błędy przy usuwaniu pliku:

$1',
'filedelete-missing'              => 'Pliku "$1" nie można skasować, ponieważ nie istnieje.',
'filedelete-old-unregistered'     => 'Żądanej wersji pliku "$1" nie ma w bazie danych.',
'filedelete-current-unregistered' => 'Pliku "$1" nie ma w bazie danych.',
'filedelete-archive-read-only'    => 'Serwer nie może pisać do katalogu archiwum "$1".',

# Browsing diffs
'previousdiff' => '← Poprzednia edycja',
'nextdiff'     => 'Następna edycja →',

# Media information
'mediawarning'         => "'''Uwaga:''' Ten plik może zawierać złośliwy kod, otwierając go możesz zarazić swój system.<hr />",
'imagemaxsize'         => 'Na stronach opisu pokaż grafiki przeskalowane do rozdzielczości:',
'thumbsize'            => 'Rozmiar miniaturki:',
'widthheightpage'      => '$1×$2, $3 stron',
'file-info'            => '(rozmiar pliku: $1, typ MIME: $2)',
'file-info-size'       => '($1 × $2 pikseli, rozmiar pliku: $3, typ MIME: $4)',
'file-nohires'         => '<small>Grafika w wyższej rozdzielczości jest niedostępna.</small>',
'svg-long-desc'        => '(Plik SVG, nominalnie $1 × $2 pikseli, rozmiar pliku: $3)',
'show-big-image'       => 'Oryginalna rozdzielczość',
'show-big-image-thumb' => '<small>Rozmiar podglądu: $1 × $2 pikseli</small>',

# Special:Newimages
'newimages'    => 'Najnowsze grafiki',
'showhidebots' => '($1 boty)',
'noimages'     => 'Nic.',

# Bad image list
'bad_image_list' => 'Format jest następujący:

Jedynie elementy listy (linijki zaczynające się od znaku *) są brane pod uwagę. Pierwszy link w linii musi być linkiem do złej grafiki. Następne linki w linii są traktowane jako wyjątki, tzn. strony, gdzie grafika może być wstawiona.',

# Metadata
'metadata'          => 'Metadane',
'metadata-help'     => 'Niniejszy plik zawiera dodatkowe informacje, prawdopodobnie dodane przez aparat cyfrowy lub skaner. Jeśli plik był modyfikowany, dane mogą być częściowo błędne.',
'metadata-expand'   => 'Pokaż szczegóły',
'metadata-collapse' => 'Ukryj szczegóły',
'metadata-fields'   => 'Wymienione poniżej pola EXIF zostaną wymienione na stronie grafiki. Pozostałe pola zostaną domyślnie ukryte.
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* focallength', # Do not translate list items

# EXIF tags
'exif-imagewidth'                  => 'Szerokość',
'exif-imagelength'                 => 'Wysokość',
'exif-bitspersample'               => 'Bitów na próbkę',
'exif-compression'                 => 'Metoda kompresji',
'exif-photometricinterpretation'   => 'Interpretacja fotometryczna',
'exif-orientation'                 => 'Orientacja obrazu',
'exif-samplesperpixel'             => 'Próbek na piksel',
'exif-planarconfiguration'         => 'Rozkład danych',
'exif-ycbcrsubsampling'            => 'Podpróbkowanie Y do C',
'exif-ycbcrpositioning'            => 'Rozmieszczenie Y do C',
'exif-xresolution'                 => 'rozdzielczosć w poziomie',
'exif-yresolution'                 => 'rozdzielczość w pionie',
'exif-resolutionunit'              => 'Jednostka rozdzielczości',
'exif-stripoffsets'                => 'Przesunięcie pasów obrazu',
'exif-rowsperstrip'                => 'Liczba wierszy na pas obrazu',
'exif-stripbytecounts'             => 'Liczba bajtów na pas obrazu',
'exif-jpeginterchangeformat'       => 'Położenie pierwszego bajtu miniaturki obrazu',
'exif-jpeginterchangeformatlength' => 'Ilość bajtów miniaturki JPEG',
'exif-transferfunction'            => 'Funkcja przejścia',
'exif-whitepoint'                  => 'Punkt bieli',
'exif-primarychromaticities'       => 'Kolory trzech barw głównych',
'exif-ycbcrcoefficients'           => 'Macierz współczynników transformacji barw z RGB na YCbCr',
'exif-referenceblackwhite'         => 'Wartość punktu odniesienia czerni i bieli',
'exif-datetime'                    => 'Data i czas modyfikacji pliku',
'exif-imagedescription'            => 'Tytuł/opis obrazu',
'exif-make'                        => 'Producent aparatu',
'exif-model'                       => 'Model aparatu',
'exif-software'                    => 'Oprogramowanie',
'exif-artist'                      => 'Autor',
'exif-copyright'                   => 'Właściciel praw autorskich',
'exif-exifversion'                 => 'Wersja standardu Exif',
'exif-flashpixversion'             => 'Obsługiwana wersja Flashpix',
'exif-colorspace'                  => 'Przestrzeń kolorów',
'exif-componentsconfiguration'     => 'Znaczenie składowych',
'exif-compressedbitsperpixel'      => 'Skompresowanych bitów na piksel',
'exif-pixelydimension'             => 'Prawidłowa szerokość obrazu',
'exif-pixelxdimension'             => 'Prawidłowa wysokość obrazu',
'exif-makernote'                   => 'Informacje producenta aparatu',
'exif-usercomment'                 => 'Komentarz użytkownika',
'exif-relatedsoundfile'            => 'Powiązany plik audio',
'exif-datetimeoriginal'            => 'Data i czas utworzenia oryginału',
'exif-datetimedigitized'           => 'Data i czas zeskanowania',
'exif-subsectime'                  => 'Data i czas modyfikacji pliku - ułamki sekund',
'exif-subsectimeoriginal'          => 'Data i czas utworzenia oryginału - ułamki sekund',
'exif-subsectimedigitized'         => 'Data i czas zeskanowania - ułamki sekund',
'exif-exposuretime'                => 'Czas ekspozycji',
'exif-exposuretime-format'         => '$1 s ($2)',
'exif-fnumber'                     => 'Wartość przysłony',
'exif-exposureprogram'             => 'Program ekspozycji',
'exif-spectralsensitivity'         => 'Czułość widmowa',
'exif-isospeedratings'             => 'Szybkość aparatu zgodnie z ISO12232',
'exif-oecf'                        => 'Funkcja konwersji obrazu na dane zgodnie z ISO14524',
'exif-shutterspeedvalue'           => 'Szybkość migawki',
'exif-aperturevalue'               => 'Przysłona obiektywu',
'exif-brightnessvalue'             => 'Jasność',
'exif-exposurebiasvalue'           => 'Odchylenie ekspozycji',
'exif-maxaperturevalue'            => 'Maksymalna wartość przysłony',
'exif-subjectdistance'             => 'Odległość od obiektu',
'exif-meteringmode'                => 'Tryb pomiaru',
'exif-lightsource'                 => 'Rodzaj źródła światła',
'exif-flash'                       => 'Lampa błyskowa',
'exif-focallength'                 => 'Długość ogniskowej obiektywu',
'exif-subjectarea'                 => 'Położenie i obszar głównego motywu obrazu',
'exif-flashenergy'                 => 'Energia lampy błyskowej',
'exif-spatialfrequencyresponse'    => 'Odpowiedź częstotliwości przestrzennej zgodnie z ISO12233',
'exif-focalplanexresolution'       => 'Rozdzielczość w poziomie płaszczyzny odwzorowania obiektywu',
'exif-focalplaneyresolution'       => 'Rozdzielczość w pionie płaszczyzny odwzorowania obiektywu',
'exif-focalplaneresolutionunit'    => 'Jednostka rozdzielczości płaszczyzny odwzorowania obiektywu',
'exif-subjectlocation'             => 'Położenie głównego motywu obrazu',
'exif-exposureindex'               => 'Indeks ekspozycji',
'exif-sensingmethod'               => 'Metoda pomiaru (rodzaj przetwornika)',
'exif-filesource'                  => 'Typ źródła pliku',
'exif-scenetype'                   => 'Rodzaj sceny',
'exif-cfapattern'                  => 'Wzór CFA',
'exif-customrendered'              => 'Wstępnie przetworzony (poddany obróbce)',
'exif-exposuremode'                => 'Tryb ekspozycji',
'exif-whitebalance'                => 'Balans bieli',
'exif-digitalzoomratio'            => 'Współczynnik powiększenia cyfrowego',
'exif-focallengthin35mmfilm'       => 'Długość ogniskowej, odpowiednik dla filmu 35mm',
'exif-scenecapturetype'            => 'Rodzaj uchwycenia sceny',
'exif-gaincontrol'                 => 'Wzmocnienie jasności obrazu',
'exif-contrast'                    => 'Kontrast obrazu',
'exif-saturation'                  => 'Nasycenie kolorów obrazu',
'exif-sharpness'                   => 'Ostrość obrazu',
'exif-devicesettingdescription'    => 'Opis ustawień urządzenia',
'exif-subjectdistancerange'        => 'Odległość od obiektu',
'exif-imageuniqueid'               => 'Unikalny identyfikator obrazu',
'exif-gpsversionid'                => 'Wersja formatu danych GPS',
'exif-gpslatituderef'              => 'Szerokość geograficzna (północ/południe)',
'exif-gpslatitude'                 => 'Szerokość geograficzna',
'exif-gpslongituderef'             => 'Długość geograficzna (wschód/zachód)',
'exif-gpslongitude'                => 'Długość geograficzna',
'exif-gpsaltituderef'              => 'Wysokość nad poziomem morza (odniesienie)',
'exif-gpsaltitude'                 => 'Wysokość nad poziomem morza',
'exif-gpstimestamp'                => 'Czas GPS (zegar atomowy)',
'exif-gpssatellites'               => 'Satelity użyte do pomiaru',
'exif-gpsstatus'                   => 'Otrzymany status',
'exif-gpsmeasuremode'              => 'Tryb pomiaru',
'exif-gpsdop'                      => 'Precyzja pomiaru',
'exif-gpsspeedref'                 => 'Jednostka prędkości',
'exif-gpsspeed'                    => 'Prędkość pozioma',
'exif-gpstrackref'                 => 'Poprawka pomiędzy kierunkiem i celem',
'exif-gpstrack'                    => 'Kierunek ruchu',
'exif-gpsimgdirectionref'          => 'Poprawka dla kierunku zdjęcia',
'exif-gpsimgdirection'             => 'Kierunek zdjęcia',
'exif-gpsmapdatum'                 => 'Model pomiaru geodezyjnego',
'exif-gpsdestlatituderef'          => 'Północna lub południowa szerokość geograficzna celu',
'exif-gpsdestlatitude'             => 'Szerokość geograficzna celu',
'exif-gpsdestlongituderef'         => 'Wschodnia lub zachodnia długość geograficzna celu',
'exif-gpsdestlongitude'            => 'Długość geograficzna celu',
'exif-gpsdestbearingref'           => 'Znacznik namiaru na cel (kierunku)',
'exif-gpsdestbearing'              => 'Namiar na cel (kierunek)',
'exif-gpsdestdistanceref'          => 'Znacznik odległości do celu',
'exif-gpsdestdistance'             => 'Odległość od celu',
'exif-gpsprocessingmethod'         => 'Nazwa metody GPS',
'exif-gpsareainformation'          => 'Nazwa przestrzeni GPS',
'exif-gpsdatestamp'                => 'Data GPS',
'exif-gpsdifferential'             => 'Korekcja różnicy GPS',

# EXIF attributes
'exif-compression-1' => 'nieskompresowany',

'exif-unknowndate' => 'nieznana data',

'exif-orientation-1' => 'normalna', # 0th row: top; 0th column: left
'exif-orientation-2' => 'odbicie lustrzane w poziomie', # 0th row: top; 0th column: right
'exif-orientation-3' => 'obraz obrócony o 180°', # 0th row: bottom; 0th column: right
'exif-orientation-4' => 'odbicie lustrzane w pionie', # 0th row: bottom; 0th column: left
'exif-orientation-5' => 'obraz obrócony o 90° przeciwnie do ruchu wskazówek zegara i odbicie lustrzane w pionie', # 0th row: left; 0th column: top
'exif-orientation-6' => 'obraz obrócony o 90° zgodnie z ruchem wskazówek zegara', # 0th row: right; 0th column: top
'exif-orientation-7' => 'Obrót o 90° zgodnie zgodnie ze wskazówkami zegara i odwrót w pionie', # 0th row: right; 0th column: bottom
'exif-orientation-8' => 'Obrót o 90° przeciwnie do wskazówek zegara', # 0th row: left; 0th column: bottom

'exif-planarconfiguration-1' => 'format masywny',
'exif-planarconfiguration-2' => 'format powierzchniowy',

'exif-componentsconfiguration-0' => 'nie istnieje',

'exif-exposureprogram-0' => 'niezdefiniowany',
'exif-exposureprogram-1' => 'ręczny',
'exif-exposureprogram-2' => 'standardowy',
'exif-exposureprogram-3' => 'preselekcja przysłony',
'exif-exposureprogram-4' => 'preselekcja migawki',
'exif-exposureprogram-5' => 'kreatywny (duża głębia ostrości)',
'exif-exposureprogram-6' => 'aktywny (duża szybkość migawki)',
'exif-exposureprogram-7' => 'tryb portretowy (dla zdjęć z bliska, z nieostrym tłem)',
'exif-exposureprogram-8' => 'tryb krajobrazowy (dla zdjęć wykonywanych z dużej odległości z ostrością ustawioną na tło)',

'exif-subjectdistance-value' => '$1 metrów',

'exif-meteringmode-0'   => 'nieokreślony',
'exif-meteringmode-1'   => 'średnia',
'exif-meteringmode-2'   => 'średnia ważona',
'exif-meteringmode-3'   => 'punktowy',
'exif-meteringmode-4'   => 'wielopunktowy',
'exif-meteringmode-5'   => 'próbkowanie',
'exif-meteringmode-6'   => 'częściowy',
'exif-meteringmode-255' => 'inny',

'exif-lightsource-0'   => 'nieznany',
'exif-lightsource-1'   => 'dzienne',
'exif-lightsource-2'   => 'jarzeniowe',
'exif-lightsource-3'   => 'sztuczne (żarowe)',
'exif-lightsource-4'   => 'lampa błyskowa (flesz)',
'exif-lightsource-9'   => 'dzienne (dobra pogoda)',
'exif-lightsource-10'  => 'dzienne (pochmurno)',
'exif-lightsource-11'  => 'cień',
'exif-lightsource-12'  => 'jarzeniowe dzienne (temperatura barwowa 5700 – 7100K)',
'exif-lightsource-13'  => 'jarzeniowe ciepłe (temperatura barwowa 4600 – 5400K)',
'exif-lightsource-14'  => 'jarzeniowe zimne (temperatura barwowa 3900 – 4500K)',
'exif-lightsource-15'  => 'jarzeniowe białe (temperatura barwowa 3200 – 3700K)',
'exif-lightsource-17'  => 'standardowe A',
'exif-lightsource-18'  => 'standardowe B',
'exif-lightsource-19'  => 'standardowe C',
'exif-lightsource-24'  => 'żarowe studyjne ISO',
'exif-lightsource-255' => 'Inne źródło światła',

'exif-focalplaneresolutionunit-2' => 'cale',

'exif-sensingmethod-1' => 'niezdefiniowana',
'exif-sensingmethod-2' => 'jednoukładowy przetwornik obrazu kolorowego',
'exif-sensingmethod-3' => 'dwuukładowy przetwornik obrazu kolorowego',
'exif-sensingmethod-4' => 'trójukładowy przetwornik obrazu kolorowego',
'exif-sensingmethod-5' => 'przetwornik obrazu z sekwencyjnym przetwarzaniem kolorów',
'exif-sensingmethod-7' => 'trójliniowy przetwornik obrazu',
'exif-sensingmethod-8' => 'liniowy przetwornik obrazu z sekwencyjnym przetwarzaniem kolorów',

'exif-scenetype-1' => 'obiekt fotografowany bezpośrednio',

'exif-customrendered-0' => 'nie',
'exif-customrendered-1' => 'tak',

'exif-exposuremode-0' => 'automatyczne ustalenie parametrów naświetlania',
'exif-exposuremode-1' => 'ręczne ustalenie parametrów naświetlania',
'exif-exposuremode-2' => 'wielokrotna ze zmianą parametrów naświetlania',

'exif-whitebalance-0' => 'automatyczny',
'exif-whitebalance-1' => 'ręczny',

'exif-scenecapturetype-0' => 'standardowy',
'exif-scenecapturetype-1' => 'krajobraz',
'exif-scenecapturetype-2' => 'portret',
'exif-scenecapturetype-3' => 'scena nocna',

'exif-gaincontrol-0' => 'brak',
'exif-gaincontrol-1' => 'niskie wzmocnienie',
'exif-gaincontrol-2' => 'wysokie wzmocnienie',
'exif-gaincontrol-3' => 'niskie osłabienie',
'exif-gaincontrol-4' => 'wysokie osłabienie',

'exif-contrast-0' => 'normalny',
'exif-contrast-1' => 'niski',
'exif-contrast-2' => 'wysoki',

'exif-saturation-0' => 'normalne',
'exif-saturation-1' => 'niskie',
'exif-saturation-2' => 'wysokie',

'exif-sharpness-0' => 'normalna',
'exif-sharpness-1' => 'niska',
'exif-sharpness-2' => 'wysoka',

'exif-subjectdistancerange-0' => 'nieznana',
'exif-subjectdistancerange-1' => 'makro',
'exif-subjectdistancerange-2' => 'widok z bliska',
'exif-subjectdistancerange-3' => 'widok z daleka',

# Pseudotags used for GPSLatitudeRef and GPSDestLatitudeRef
'exif-gpslatitude-n' => 'północna',
'exif-gpslatitude-s' => 'południowa',

# Pseudotags used for GPSLongitudeRef and GPSDestLongitudeRef
'exif-gpslongitude-e' => 'wschodnia',
'exif-gpslongitude-w' => 'zachodnia',

'exif-gpsstatus-a' => 'pomiar w trakcie',
'exif-gpsstatus-v' => 'wyniki pomiaru dostępne na bieżąco',

'exif-gpsmeasuremode-2' => 'dwuwymiarowy',
'exif-gpsmeasuremode-3' => 'trójwymiarowy',

# Pseudotags used for GPSSpeedRef and GPSDestDistanceRef
'exif-gpsspeed-k' => 'kilometrów na godzinę',
'exif-gpsspeed-m' => 'mil na godzinę',
'exif-gpsspeed-n' => 'węzłów',

# Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
'exif-gpsdirection-t' => 'kierunek geograficzny',
'exif-gpsdirection-m' => 'kierunek magnetyczny',

# External editor support
'edit-externally'      => 'Edytuj ten plik używając zewnętrznej aplikacji',
'edit-externally-help' => 'Zobacz więcej informacji o używaniu [http://meta.wikimedia.org/wiki/Help:External_editors zewnętrznych edytorów].',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'wszystkie',
'imagelistall'     => 'wszystkich',
'watchlistall2'    => 'wszystkie',
'namespacesall'    => 'wszystkie',
'monthsall'        => 'wszystkie',

# E-mail address confirmation
'confirmemail'            => 'Potwierdź adres e-mail',
'confirmemail_noemail'    => 'Nie podałeś prawidłowego adresu e-mail w [[Special:Preferences|preferencjach]].',
'confirmemail_text'       => '{{SITENAME}} wymaga potwierdzenia adresu e-mail przed użyciem funkcji korzystających z poczty. Wciśnij przycisk poniżej aby wysłać na swój adres list z linkiem do strony WWW. Następnie otwórz ten link w przeglądarce, czym potwierdzisz wiarygodność adresu e-mail.',
'confirmemail_pending'    => '<div class="error">Kod potwierdzenia został wysłany do Ciebie. Jeśli zarejestrowałeś się niedawno, poczekaj kilka minut na dostarczenie wiadomości przed kolejną prośbą o wysłanie kodu.</div>',
'confirmemail_send'       => 'Wyślij kod uwierzytelniający',
'confirmemail_sent'       => 'E-mail uwierzytelniający został wysłany.',
'confirmemail_oncreate'   => 'Kod potwierdzenia został wysłany na Twój adres E-mail. Kod ten nie jest wymagany do zalogowania się, jednak będziesz musiał go podać przed włączeniem niektórych opcji e-mail na wiki.',
'confirmemail_sendfailed' => 'Nie udało się wysłać e-maila potwierdzającego. Proszę sprawdzić czy w adresie nie ma literówki.

Program zwrócił komunikat: $1',
'confirmemail_invalid'    => 'Błędny kod potwierdzenia. Kod może być przedawniony.',
'confirmemail_needlogin'  => 'Musisz $1 aby potwierdzić adres email.',
'confirmemail_success'    => 'Adres e-mail został potwierdzony. Możesz się zalogować i korzystać z szerszego wachlarza funkcjonalności wiki.',
'confirmemail_loggedin'   => 'Twój adres email został zweryfikowany.',
'confirmemail_error'      => 'Pojawiły się błędy przy zapisywaniu potwierdzenia.',
'confirmemail_subject'    => '{{SITENAME}} - potwierdzenie adresu e-mail',
'confirmemail_body'       => 'Ktoś łącząc się z komputera o adresie IP $1 zarejestrował w serwisie
{{SITENAME}} konto "$2" podając niniejszy adres e-mail.

Aby potwierdzić, że to Ty zarejestrowałeś/aś to konto oraz aby włączyć 
wszystkie funkcje korzystające z poczty elektronicznej, otwórz w swojej
przeglądarce ten link:

$3

Jeśli to NIE TY zarejestrowałeś/aś to konto, NIE KLIKAJ W POWYŻSZY LINK.
Kod zawarty w linku straci ważność $4.',

# Scary transclusion
'scarytranscludedisabled' => '[Dołączanie przez interwiki jest wyłączone]',
'scarytranscludefailed'   => '[Nie powiodło się pobranie szablonu dla $1]',
'scarytranscludetoolong'  => '[URL za długi]',

# Trackbacks
'trackbackbox'      => '<div id="mw_trackbacks">
Komunikaty Trackback dla tego artykułu:<br />
$1
</div>',
'trackbackremove'   => ' ([$1 Usuń])',
'trackbacklink'     => 'TrackBack',
'trackbackdeleteok' => 'TrackBack został usunięty.',

# Delete conflict
'deletedwhileediting' => 'Uwaga: Ta strona została usunięta po tym, jak rozpocząłeś jej edycję!',
'confirmrecreate'     => "Użytkownik [[{{ns:user}}:$1|$1]] ([[{{ns:user_talk}}:$1|Dyskusja]]) usunął ten artykuł po tym jak rozpocząłeś jego edycję, podając jako powód usunięcia:
: ''$2'' 
Potwierdź chęć odtworzenia tego artykułu.",
'recreate'            => 'Odtwórz',

# HTML dump
'redirectingto' => 'Przekierowanie do [[:$1|$1]]...',

# action=purge
'confirm_purge'        => 'Wyczyścić bufor dla tej strony?

$1',
'confirm_purge_button' => 'Wyczyść',

# AJAX search
'searchcontaining' => "Szukaj artykułów zawierających ''$1''.",
'searchnamed'      => "Szukaj artykułów nazywających się ''$1''.",
'articletitles'    => "Artykuły zaczynające się od ''$1''.",
'hideresults'      => 'Ukryj wyniki',
'useajaxsearch'    => 'Użyj wyszukiwania AJAX',

# Multipage image navigation
'imgmultipageprev'   => '← poprzednia strona',
'imgmultipagenext'   => 'następna strona →',
'imgmultigo'         => 'Przejdź',
'imgmultigotopre'    => 'Przejdź na stronę',
'imgmultiparseerror' => 'Plik obrazu wydaje się być uszkodzony lub nieprawidłowy i {{SITENAME}} nie możne odzyskać listy stron.',

# Table pager
'ascending_abbrev'         => 'rosn.',
'descending_abbrev'        => 'mal.',
'table_pager_next'         => 'Następna strona',
'table_pager_prev'         => 'Poprzednia strona',
'table_pager_first'        => 'Pierwsza strona',
'table_pager_last'         => 'Ostatnia strona',
'table_pager_limit'        => 'Pokaż po $1 pozycji na stronie',
'table_pager_limit_submit' => 'Pokaż',
'table_pager_empty'        => 'Brak wyników',

# Auto-summaries
'autosumm-blank'   => 'Usunięcie całej zawartości strony',
'autosumm-replace' => "Zastąpienie treści strony tekstem: '$1'",
'autoredircomment' => 'Przekierowanie do [[$1]]',
'autosumm-new'     => 'Nowa strona: $1',

# Live preview
'livepreview-loading' => 'Trwa ładowanie…',
'livepreview-ready'   => 'Trwa ładowanie… Gotowe!',
'livepreview-failed'  => 'Podgląd na żywo nie zadziałał! Spróbuj podglądu standardowego.',
'livepreview-error'   => 'Nieudane połączenie: $1 "$2" Spróbuj podglądu standardowego.',

# Friendlier slave lag warnings
'lag-warn-normal' => 'Na tej liście zmiany nowsze niż $1 sekund mogą nie być pokazane.',
'lag-warn-high'   => 'Z powodu dużego obciążenia serwerów bazy danych, na tej liście zmiany nowsze niż $1 sekund mogą nie być pokazane.',

# Watchlist editor
'watchlistedit-numitems'       => 'Twoja lista obserwowanych zawiera {{PLURAL:$1|1 tytuł|$1 tytułów}}, nieuwzględniając strony dyskusji.',
'watchlistedit-noitems'        => 'Twoja lista obserwowanych nie zawiera żadnych tytułów.',
'watchlistedit-normal-title'   => 'Edytuj listę obserwowanych stron',
'watchlistedit-normal-legend'  => 'Usuń tytuły z listy obserwowanych',
'watchlistedit-normal-explain' => 'Obserwowane przez Ciebie strony zostały wymienione poniżej. Aby usunąć obserwowaną stronę z listy zaznacz znajdujące się obok niej pole i naciśnij "Usuń zaznaczone pozycje". Możesz także skorzystać z [[Special:Watchlist/raw|edytora surowej listy obserwowanych]] lub [[Special:Watchlist/clear|wyczyścić listę]].',
'watchlistedit-normal-submit'  => 'Usuń tytuły',
'watchlistedit-normal-done'    => '{{PLURAL:$1|1 tytuł został|$1 tytułów zostało}} usuniętych z twojej listy obserwowanych:',
'watchlistedit-raw-title'      => 'Edycja surowej listy obserwowanych',
'watchlistedit-raw-legend'     => 'Edycja surowej listy obserwowanych',
'watchlistedit-raw-explain'    => 'Tytuły na twojej liście obserwowanych są pokazane poniżej i mogą być edytowane przez
Titles on your watchlist are shown below, and can be edited by
	dodawanie i usuwanie z listy; jeden tytuł na linię. Kiedy skończysz, kliknij "Uaktualnij listę obserwowanych".
	Możesz też [[Special:Watchlist/edit|użyć standardowego edytora]].',
'watchlistedit-raw-titles'     => 'Tytuły:',
'watchlistedit-raw-submit'     => 'Uaktualnij listę obserwowanych',
'watchlistedit-raw-done'       => 'Lista obserwowanych stron została uaktualniona.',
'watchlistedit-raw-added'      => '{{PLURAL:$1|1 tytuł został|$1 tytułów zostało}} dodanych:',
'watchlistedit-raw-removed'    => '{{PLURAL:$1|1 tytuł został|$1 tytułów zostało}} usuniętych:',

# Watchlist editing tools
'watchlisttools-view' => 'Pokaż ważniejsze zmiany',
'watchlisttools-edit' => 'Pokaż i edytuj listę',
'watchlisttools-raw'  => 'Edytuj surową listę',

);
