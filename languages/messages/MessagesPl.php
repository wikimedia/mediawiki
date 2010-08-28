<?php
/** Polish (Polski)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Beau
 * @author Derbeth
 * @author Equadus
 * @author Herr Kriss
 * @author Holek
 * @author Jwitos
 * @author Lajsikonik
 * @author Leinad
 * @author Maikking
 * @author Marcin Łukasz Kiejzik
 * @author Masti
 * @author Matma Rex
 * @author McMonster
 * @author Nux
 * @author Remember the dot
 * @author Saper
 * @author Sp5uhe
 * @author Stv
 * @author Szczepan1990
 * @author Timpul
 * @author ToSter
 * @author Wpedzich
 * @author Ymar
 * @author לערי ריינהארט
 */

$namespaceNames = array(
	NS_MEDIA            => 'Media',
	NS_SPECIAL          => 'Specjalna',
	NS_TALK             => 'Dyskusja',
	NS_USER             => 'Użytkownik',
	NS_USER_TALK        => 'Dyskusja_użytkownika',
	NS_PROJECT_TALK     => 'Dyskusja_$1',
	NS_FILE             => 'Plik',
	NS_FILE_TALK        => 'Dyskusja_pliku',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'Dyskusja_MediaWiki',
	NS_TEMPLATE         => 'Szablon',
	NS_TEMPLATE_TALK    => 'Dyskusja_szablonu',
	NS_HELP             => 'Pomoc',
	NS_HELP_TALK        => 'Dyskusja_pomocy',
	NS_CATEGORY         => 'Kategoria',
	NS_CATEGORY_TALK    => 'Dyskusja_kategorii',
);

$namespaceAliases = array(
	'Grafika' => NS_FILE,
	'Dyskusja_grafiki' => NS_FILE_TALK,
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
	'DoubleRedirects'           => array( 'Podwójne_przekierowania' ),
	'BrokenRedirects'           => array( 'Zerwane_przekierowania' ),
	'Disambiguations'           => array( 'Ujednoznacznienia' ),
	'Userlogin'                 => array( 'Zaloguj' ),
	'Userlogout'                => array( 'Wyloguj' ),
	'CreateAccount'             => array( 'Stwórz_konto' ),
	'Preferences'               => array( 'Preferencje' ),
	'Watchlist'                 => array( 'Obserwowane' ),
	'Recentchanges'             => array( 'Ostatnie_zmiany', 'OZ' ),
	'Upload'                    => array( 'Prześlij' ),
	'Listfiles'                 => array( 'Pliki' ),
	'Newimages'                 => array( 'Nowe_pliki' ),
	'Listusers'                 => array( 'Użytkownicy' ),
	'Listgrouprights'           => array( 'Grupy_użytkowników', 'Uprawnienia_grup_użytkowników' ),
	'Statistics'                => array( 'Statystyka', 'Statystyki' ),
	'Randompage'                => array( 'Losowa_strona', 'Losowa' ),
	'Lonelypages'               => array( 'Porzucone_strony' ),
	'Uncategorizedpages'        => array( 'Nieskategoryzowane_strony' ),
	'Uncategorizedcategories'   => array( 'Nieskategoryzowane_kategorie' ),
	'Uncategorizedimages'       => array( 'Nieskategoryzowane_pliki' ),
	'Uncategorizedtemplates'    => array( 'Nieskategoryzowane_szablony' ),
	'Unusedcategories'          => array( 'Nieużywane_kategorie' ),
	'Unusedimages'              => array( 'Nieużywane_pliki' ),
	'Wantedpages'               => array( 'Potrzebne_strony' ),
	'Wantedcategories'          => array( 'Potrzebne_kategorie' ),
	'Wantedfiles'               => array( 'Potrzebne_pliki' ),
	'Wantedtemplates'           => array( 'Potrzebne_szablony' ),
	'Mostlinked'                => array( 'Najczęściej_linkowane' ),
	'Mostlinkedcategories'      => array( 'Najczęściej_linkowane_kategorie' ),
	'Mostlinkedtemplates'       => array( 'Najczęściej_linkowane_szablony' ),
	'Mostimages'                => array( 'Najczęściej_linkowane_pliki' ),
	'Mostcategories'            => array( 'Najwięcej_kategorii' ),
	'Mostrevisions'             => array( 'Najwięcej_edycji', 'Najczęściej_edytowane' ),
	'Fewestrevisions'           => array( 'Najmniej_edycji' ),
	'Shortpages'                => array( 'Najkrótsze_strony' ),
	'Longpages'                 => array( 'Najdłuższe_strony' ),
	'Newpages'                  => array( 'Nowe_strony' ),
	'Ancientpages'              => array( 'Stare_strony' ),
	'Deadendpages'              => array( 'Bez_linków' ),
	'Protectedpages'            => array( 'Zabezpieczone_strony' ),
	'Protectedtitles'           => array( 'Zabezpieczone_nazwy_stron' ),
	'Allpages'                  => array( 'Wszystkie_strony' ),
	'Prefixindex'               => array( 'Strony_według_prefiksu' ),
	'Ipblocklist'               => array( 'Zablokowani' ),
	'Unblock'                   => array( 'Odblokuj' ),
	'Specialpages'              => array( 'Strony_specjalne' ),
	'Contributions'             => array( 'Wkład' ),
	'Emailuser'                 => array( 'E-mail' ),
	'Confirmemail'              => array( 'Potwierdź_e-mail' ),
	'Whatlinkshere'             => array( 'Linkujące' ),
	'Recentchangeslinked'       => array( 'Zmiany_w_linkujących' ),
	'Movepage'                  => array( 'Przenieś' ),
	'Blockme'                   => array( 'Zablokuj_mnie' ),
	'Booksources'               => array( 'Książki' ),
	'Categories'                => array( 'Kategorie' ),
	'Export'                    => array( 'Eksport' ),
	'Version'                   => array( 'Wersja' ),
	'Allmessages'               => array( 'Wszystkie_komunikaty' ),
	'Log'                       => array( 'Rejestr', 'Logi' ),
	'Blockip'                   => array( 'Blokuj' ),
	'Undelete'                  => array( 'Odtwórz' ),
	'Lockdb'                    => array( 'Zablokuj_bazę' ),
	'Unlockdb'                  => array( 'Odblokuj_bazę' ),
	'Userrights'                => array( 'Uprawnienia', 'Uprawnienia_użytkowników', 'Prawa_użytkowników' ),
	'MIMEsearch'                => array( 'Wyszukiwanie_MIME' ),
	'FileDuplicateSearch'       => array( 'Szukaj_duplikatu_pliku' ),
	'Unwatchedpages'            => array( 'Nieobserwowane_strony' ),
	'Listredirects'             => array( 'Przekierowania' ),
	'Revisiondelete'            => array( 'Usuń_wersję' ),
	'Unusedtemplates'           => array( 'Nieużywane_szablony' ),
	'Randomredirect'            => array( 'Losowe_przekierowanie' ),
	'Mypage'                    => array( 'Moja_strona' ),
	'Mytalk'                    => array( 'Moja_dyskusja' ),
	'Mycontributions'           => array( 'Mój_wkład' ),
	'Listadmins'                => array( 'Administratorzy' ),
	'Listbots'                  => array( 'Boty' ),
	'Popularpages'              => array( 'Popularne_strony' ),
	'Search'                    => array( 'Szukaj' ),
	'Resetpass'                 => array( 'Zmień_hasło', 'Resetuj_hasło' ),
	'Withoutinterwiki'          => array( 'Strony_bez_interwiki' ),
	'MergeHistory'              => array( 'Połącz_historię' ),
	'Filepath'                  => array( 'Ścieżka_do_pliku' ),
	'Invalidateemail'           => array( 'Anuluj_e-mail' ),
	'Blankpage'                 => array( 'Pusta_strona' ),
	'LinkSearch'                => array( 'Wyszukiwarka_linków' ),
	'DeletedContributions'      => array( 'Usunięty_wkład' ),
	'Tags'                      => array( 'Znaczniki' ),
	'Activeusers'               => array( 'Aktywni_użytkownicy' ),
	'RevisionMove'              => array( 'Przenoszenie_wersji' ),
	'ComparePages'              => array( 'Porównywanie_stron' ),
);

$magicWords = array(
	'redirect'              => array( '0', '#PATRZ', '#PRZEKIERUJ', '#TAM', '#REDIRECT' ),
	'notoc'                 => array( '0', '__BEZSPISU__', '__NOTOC__' ),
	'nogallery'             => array( '0', '__BEZGALERII__', '__NOGALLERY__' ),
	'forcetoc'              => array( '0', '__ZESPISEM__', '__FORCETOC__' ),
	'toc'                   => array( '0', '__SPIS__', '__TOC__' ),
	'noeditsection'         => array( '0', '__BEZEDYCJISEKCJI__', '__NOEDITSECTION__' ),
	'noheader'              => array( '0', '__BEZNAGŁÓWKA__', '__NOHEADER__' ),
	'localmonth'            => array( '1', 'MIESIĄC', 'LOCALMONTH', 'LOCALMONTH2' ),
	'localmonthname'        => array( '1', 'MIESIĄCNAZWA', 'LOCALMONTHNAME' ),
	'localmonthnamegen'     => array( '1', 'MIESIĄCNAZWAD', 'LOCALMONTHNAMEGEN' ),
	'localmonthabbrev'      => array( '1', 'MIESIĄCNAZWASKR', 'LOCALMONTHABBREV' ),
	'localday'              => array( '1', 'DZIEŃ', 'LOCALDAY' ),
	'localday2'             => array( '1', 'DZIEŃ2', 'LOCALDAY2' ),
	'localdayname'          => array( '1', 'DZIEŃTYGODNIA', 'LOCALDAYNAME' ),
	'localyear'             => array( '1', 'ROK', 'LOCALYEAR' ),
	'localtime'             => array( '1', 'CZAS', 'LOCALTIME' ),
	'localhour'             => array( '1', 'GODZINA', 'LOCALHOUR' ),
	'numberofpages'         => array( '1', 'STRON', 'NUMBEROFPAGES' ),
	'numberofarticles'      => array( '1', 'ARTYKUŁÓW', 'NUMBEROFARTICLES' ),
	'numberoffiles'         => array( '1', 'PLIKÓW', 'NUMBEROFFILES' ),
	'numberofusers'         => array( '1', 'UŻYTKOWNIKÓW', 'NUMBEROFUSERS' ),
	'numberofedits'         => array( '1', 'EDYCJI', 'NUMBEROFEDITS' ),
	'pagename'              => array( '1', 'NAZWASTRONY', 'PAGENAME' ),
	'namespace'             => array( '1', 'NAZWAPRZESTRZENI', 'NAMESPACE' ),
	'talkspace'             => array( '1', 'DYSKUSJA', 'TALKSPACE' ),
	'fullpagename'          => array( '1', 'PELNANAZWASTRONY', 'FULLPAGENAME' ),
	'img_thumbnail'         => array( '1', 'mały', 'thumbnail', 'thumb' ),
	'img_manualthumb'       => array( '1', 'mały=$1', 'thumbnail=$1', 'thumb=$1' ),
	'img_right'             => array( '1', 'prawo', 'right' ),
	'img_left'              => array( '1', 'lewo', 'left' ),
	'img_none'              => array( '1', 'brak', 'none' ),
	'img_center'            => array( '1', 'centruj', 'center', 'centre' ),
	'img_framed'            => array( '1', 'ramka', 'framed', 'enframed', 'frame' ),
	'img_frameless'         => array( '1', 'bezramki', 'bez_ramki', 'frameless' ),
	'img_page'              => array( '1', 'strona=$1', 'page=$1', 'page $1' ),
	'img_border'            => array( '1', 'tło', 'border' ),
	'img_top'               => array( '1', 'góra', 'top' ),
	'img_middle'            => array( '1', 'środek', 'middle' ),
	'img_bottom'            => array( '1', 'dół', 'bottom' ),
	'sitename'              => array( '1', 'PROJEKT', 'SITENAME' ),
	'grammar'               => array( '0', 'ODMIANA:', 'GRAMMAR:' ),
	'gender'                => array( '0', 'PŁEĆ:', 'GENDER:' ),
	'localweek'             => array( '1', 'TYDZIEŃROKU', 'LOCALWEEK' ),
	'localdow'              => array( '1', 'DZIEŃTYGODNIANR', 'LOCALDOW' ),
	'plural'                => array( '0', 'MNOGA:', 'PLURAL:' ),
	'lcfirst'               => array( '0', 'ZMAŁEJ:', 'LCFIRST:' ),
	'ucfirst'               => array( '0', 'ZWIELKIEJ:', 'ZDUŻEJ:', 'UCFIRST:' ),
	'lc'                    => array( '0', 'MAŁE:', 'LC:' ),
	'uc'                    => array( '0', 'WIELKIE:', 'DUŻE:', 'UC:' ),
	'newsectionlink'        => array( '1', '__LINKNOWEJSEKCJI__', '__NEWSECTIONLINK__' ),
	'language'              => array( '0', '#JĘZYK:', '#LANGUAGE:' ),
	'numberofadmins'        => array( '1', 'ADMINISTRATORÓW', 'NUMBEROFADMINS' ),
	'padleft'               => array( '0', 'DOLEWEJ', 'PADLEFT' ),
	'padright'              => array( '0', 'DOPRAWEJ', 'PADRIGHT' ),
	'defaultsort'           => array( '1', 'DOMYŚLNIESORTUJ', 'DEFAULTSORT:', 'DEFAULTSORTKEY:', 'DEFAULTCATEGORYSORT:' ),
	'hiddencat'             => array( '1', '__KATEGORIAUKRYTA__', '__HIDDENCAT__' ),
);

$messages = array(
# User preference toggles
'tog-underline'               => 'Podkreślenie linków',
'tog-highlightbroken'         => 'Oznacz <a href="" class="new">tak</a> linki do brakujących stron (alternatywa – dołączany znak zapytania<a href="" class="internal">?</a>).',
'tog-justify'                 => 'Wyrównuj tekst w akapitach do obu stron',
'tog-hideminor'               => 'Ukryj drobne zmiany w ostatnich zmianach',
'tog-hidepatrolled'           => 'Ukryj sprawdzone edycje w ostatnich zmianach',
'tog-newpageshidepatrolled'   => 'Ukryj sprawdzone strony na liście nowych stron',
'tog-extendwatchlist'         => 'Pokaż na liście obserwowanych wszystkie zmiany, nie tylko ostatnie',
'tog-usenewrc'                => 'Zastosuj rozbudowany widok ostatnich zmian (JavaScript)',
'tog-numberheadings'          => 'Automatyczna numeracja nagłówków',
'tog-showtoolbar'             => 'Pokaż pasek narzędzi (JavaScript)',
'tog-editondblclick'          => 'Podwójne kliknięcie rozpoczyna edycję (JavaScript)',
'tog-editsection'             => 'Możliwość edycji poszczególnych sekcji strony (link [edytuj])',
'tog-editsectiononrightclick' => 'Kliknięcie prawym klawiszem myszy na tytule sekcji rozpoczyna jej edycję (JavaScript)',
'tog-showtoc'                 => 'Pokazuj spis treści (na stronach z więcej niż 3 nagłówkami)',
'tog-rememberpassword'        => 'Zapamiętaj moje hasło na tym komputerze (maksymalnie przez $1 {{PLURAL:$1|dzień|dni}})',
'tog-watchcreations'          => 'Dodaj do obserwowanych strony tworzone przeze mnie',
'tog-watchdefault'            => 'Dodaj do obserwowanych strony, które edytuję',
'tog-watchmoves'              => 'Dodaj do obserwowanych strony, które przenoszę',
'tog-watchdeletion'           => 'Dodaj do obserwowanych strony, które usuwam',
'tog-previewontop'            => 'Pokazuj podgląd powyżej obszaru edycji',
'tog-previewonfirst'          => 'Pokazuj podgląd strony podczas pierwszej edycji',
'tog-nocache'                 => 'Wyłącz pamięć podręczną przeglądarki',
'tog-enotifwatchlistpages'    => 'Wyślij do mnie e‐mail, jeśli strona z listy moich obserwowanych zostanie zmodyfikowana',
'tog-enotifusertalkpages'     => 'Wyślij do mnie e‐mail, jeśli moja strona dyskusji zostanie zmodyfikowana',
'tog-enotifminoredits'        => 'Wyślij e‐mail także w przypadku drobnych zmian na stronach',
'tog-enotifrevealaddr'        => 'Nie ukrywaj mojego adresu e‐mail w powiadomieniach',
'tog-shownumberswatching'     => 'Pokaż liczbę użytkowników obserwujących stronę',
'tog-oldsig'                  => 'Obecny wygląd Twojego podpisu',
'tog-fancysig'                => 'Traktuj podpis jako wikikod (nie linkuj automatycznie całości)',
'tog-externaleditor'          => 'Domyślnie używaj zewnętrznego edytora (dla użytkowników zaawansowanych; wymaga specjalnych ustawień dla komputera)',
'tog-externaldiff'            => 'Domyślnie używaj zewnętrznego programu pokazującego zmiany (dla użytkowników zaawansowanych; wymaga specjalnych ustawień dla komputera)',
'tog-showjumplinks'           => 'Włącz odnośniki „skocz do”',
'tog-uselivepreview'          => 'Używaj dynamicznego podglądu (JavaScript; eksperymentalny)',
'tog-forceeditsummary'        => 'Informuj o niewypełnieniu opisu zmian',
'tog-watchlisthideown'        => 'Ukryj moje edycje na liście obserwowanych',
'tog-watchlisthidebots'       => 'Ukryj edycje botów na liście obserwowanych',
'tog-watchlisthideminor'      => 'Ukrywaj drobne zmiany na liście obserwowanych',
'tog-watchlisthideliu'        => 'Ukryj edycje zalogowanych użytkowników na liście obserwowanych',
'tog-watchlisthideanons'      => 'Ukryj edycje anonimowych użytkowników na liście obserwowanych',
'tog-watchlisthidepatrolled'  => 'Ukryj sprawdzone edycje na liście obserwowanych',
'tog-nolangconversion'        => 'Wyłącz odmianę',
'tog-ccmeonemails'            => 'Przesyłaj mi kopie wiadomości, które wysyłam do innych użytkowników',
'tog-diffonly'                => 'Nie pokazuj treści stron pod porównaniami zmian',
'tog-showhiddencats'          => 'Pokazuj ukryte kategorie',
'tog-noconvertlink'           => 'Wyłącz konwersję tytułów w linkach',
'tog-norollbackdiff'          => 'Pomiń pokazywanie zmian po użyciu funkcji „cofnij”',

'underline-always'  => 'zawsze',
'underline-never'   => 'nigdy',
'underline-default' => 'według ustawień przeglądarki',

# Font style option in Special:Preferences
'editfont-style'     => 'Styl czcionki w polu edycyjnym',
'editfont-default'   => 'domyślny przeglądarki',
'editfont-monospace' => 'czcionka o stałej szerokości',
'editfont-sansserif' => 'czcionka bezszeryfowa',
'editfont-serif'     => 'czcionka szeryfowa',

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

# Categories related messages
'pagecategories'                 => '{{PLURAL:$1|Kategoria|Kategorie}}',
'category_header'                => 'Strony w kategorii „$1”',
'subcategories'                  => 'Podkategorie',
'category-media-header'          => 'Pliki w kategorii „$1”',
'category-empty'                 => "''Obecnie w tej kategorii brak stron oraz plików.''",
'hidden-categories'              => '{{PLURAL:$1|Ukryta kategoria|Ukryte kategorie}}',
'hidden-category-category'       => 'Ukryte kategorie',
'category-subcat-count'          => '{{PLURAL:$2|Ta kategoria ma tylko jedną podkategorię.|Poniżej wyświetlono $1 spośród wszystkich $2 podkategorii tej kategorii.}}',
'category-subcat-count-limited'  => 'Ta kategoria ma {{PLURAL:$1|1 podkategorię|$1 podkategorie|$1 podkategorii}}.',
'category-article-count'         => '{{PLURAL:$2|W tej kategorii jest tylko jedna strona.|Poniżej wyświetlono $1 spośród wszystkich $2 stron tej kategorii.}}',
'category-article-count-limited' => 'W tej kategorii {{PLURAL:$1|jest 1 strona|są $1 strony|jest $1 stron}}.',
'category-file-count'            => '{{PLURAL:$2|W tej kategorii znajduje się tylko jeden plik.|W tej kategorii {{PLURAL:$1|jest 1 plik|są $1 pliki|jest $1 plików}} z ogólnej liczby $2 plików.}}',
'category-file-count-limited'    => 'W tej kategorii {{PLURAL:$1|jest 1 plik|są $1 pliki|jest $1 plików}}.',
'listingcontinuesabbrev'         => 'cd.',
'index-category'                 => 'Strony indeksowane',
'noindex-category'               => 'Strony nieindeksowane',

'mainpagetext'      => "'''Instalacja MediaWiki powiodła się.'''",
'mainpagedocfooter' => 'Zobacz [http://meta.wikimedia.org/wiki/Help:Contents przewodnik użytkownika] w celu uzyskania informacji o działaniu oprogramowania wiki.

== Na początek ==
* [http://www.mediawiki.org/wiki/Manual:Configuration_settings Lista ustawień konfiguracyjnych]
* [http://www.mediawiki.org/wiki/Manual:FAQ MediaWiki FAQ]
* [https://lists.wikimedia.org/mailman/listinfo/mediawiki-announce Komunikaty o nowych wersjach MediaWiki]',

'about'         => 'O {{GRAMMAR:MS.lp|{{SITENAME}}}}',
'article'       => 'artykuł',
'newwindow'     => '(otwiera się w nowym oknie)',
'cancel'        => 'Anuluj',
'moredotdotdot' => 'Więcej...',
'mypage'        => 'Moja strona',
'mytalk'        => 'Moja dyskusja',
'anontalk'      => 'Dyskusja tego IP',
'navigation'    => 'Nawigacja',
'and'           => '&#32;oraz',

# Cologne Blue skin
'qbfind'         => 'Znajdź',
'qbbrowse'       => 'Przeglądanie',
'qbedit'         => 'Edycja',
'qbpageoptions'  => 'Ta strona',
'qbpageinfo'     => 'Kontekst',
'qbmyoptions'    => 'Moje strony',
'qbspecialpages' => 'strony specjalne',
'faq'            => 'FAQ',
'faqpage'        => 'Project:FAQ',

# Vector skin
'vector-action-addsection'       => 'Dodaj temat',
'vector-action-delete'           => 'Usuń',
'vector-action-move'             => 'Przenieś',
'vector-action-protect'          => 'Zabezpiecz',
'vector-action-undelete'         => 'Odtwórz',
'vector-action-unprotect'        => 'Odbezpiecz',
'vector-namespace-category'      => 'Kategoria',
'vector-namespace-help'          => 'Strona pomocy',
'vector-namespace-image'         => 'Plik',
'vector-namespace-main'          => 'Strona',
'vector-namespace-media'         => 'Strona pliku',
'vector-namespace-mediawiki'     => 'Komunikat',
'vector-namespace-project'       => 'Strona projektu',
'vector-namespace-special'       => 'Strona specjalna',
'vector-namespace-talk'          => 'Dyskusja',
'vector-namespace-template'      => 'Szablon',
'vector-namespace-user'          => 'Strona użytkownika',
'vector-simplesearch-preference' => 'Włącz zaawansowane podpowiedzi wyszukiwania (tylko dla skórki Wektor)',
'vector-view-create'             => 'Utwórz',
'vector-view-edit'               => 'Edytuj',
'vector-view-history'            => 'Wyświetl historię',
'vector-view-view'               => 'Czytaj',
'vector-view-viewsource'         => 'Tekst źródłowy',
'actions'                        => 'Działania',
'namespaces'                     => 'Przestrzenie nazw',
'variants'                       => 'Warianty',

'errorpagetitle'    => 'Błąd',
'returnto'          => 'Wróć do strony $1.',
'tagline'           => 'Z {{GRAMMAR:D.lp|{{SITENAME}}}}',
'help'              => 'Pomoc',
'search'            => 'Szukaj',
'searchbutton'      => 'Szukaj',
'go'                => 'Przejdź',
'searcharticle'     => 'Przejdź',
'history'           => 'Historia strony',
'history_short'     => 'Historia i autorzy',
'updatedmarker'     => 'zmienione od ostatniej wizyty',
'info_short'        => 'Informacja',
'printableversion'  => 'Wersja do druku',
'permalink'         => 'Link do tej wersji',
'print'             => 'Drukuj',
'edit'              => 'Edytuj',
'create'            => 'Utwórz',
'editthispage'      => 'Edytuj tę stronę',
'create-this-page'  => 'Utwórz tę stronę',
'delete'            => 'Usuń',
'deletethispage'    => 'Usuń tę stronę',
'undelete_short'    => 'odtwórz {{PLURAL:$1|1 wersję|$1 wersje|$1 wersji}}',
'protect'           => 'Zabezpiecz',
'protect_change'    => 'zmień',
'protectthispage'   => 'Zabezpiecz tę stronę',
'unprotect'         => 'Odbezpiecz',
'unprotectthispage' => 'Odbezpiecz tę stronę',
'newpage'           => 'Nowa strona',
'talkpage'          => 'Dyskusja',
'talkpagelinktext'  => 'dyskusja',
'specialpage'       => 'Strona specjalna',
'personaltools'     => 'Osobiste',
'postcomment'       => 'Nowa sekcja',
'articlepage'       => 'Artykuł',
'talk'              => 'Dyskusja',
'views'             => 'Widok',
'toolbox'           => 'Narzędzia',
'userpage'          => 'Strona użytkownika',
'projectpage'       => 'Strona projektu',
'imagepage'         => 'Strona pliku',
'mediawikipage'     => 'Strona komunikatu',
'templatepage'      => 'Strona szablonu',
'viewhelppage'      => 'Strona pomocy',
'categorypage'      => 'Strona kategorii',
'viewtalkpage'      => 'Strona dyskusji',
'otherlanguages'    => 'W innych językach',
'redirectedfrom'    => '(Przekierowano z $1)',
'redirectpagesub'   => 'Strona przekierowująca',
'lastmodifiedat'    => 'Tę stronę ostatnio zmodyfikowano $2, $1.',
'viewcount'         => 'Tę stronę obejrzano {{PLURAL:$1|tylko raz|$1 razy}}.',
'protectedpage'     => 'Strona zabezpieczona',
'jumpto'            => 'Skocz do:',
'jumptonavigation'  => 'nawigacji',
'jumptosearch'      => 'wyszukiwania',
'view-pool-error'   => 'Niestety w chwili obecnej serwery są przeciążone.
Zbyt wielu użytkowników próbuje wyświetlić tę stronę.
Poczekaj chwilę przed ponowną próbą dostępu do tej strony.

$1',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'O {{GRAMMAR:MS.lp|{{SITENAME}}}}',
'aboutpage'            => 'Project:O {{GRAMMAR:MS.lp|{{SITENAME}}}}',
'copyright'            => 'Treść udostępniana na licencji $1.',
'copyrightpage'        => '{{ns:project}}:Prawa_autorskie',
'currentevents'        => 'Bieżące wydarzenia',
'currentevents-url'    => 'Project:Aktualności',
'disclaimers'          => 'Informacje prawne',
'disclaimerpage'       => 'Project:Informacje prawne',
'edithelp'             => 'Pomoc w edycji',
'edithelppage'         => 'Help:Jak edytować stronę',
'helppage'             => 'Help:Spis treści',
'mainpage'             => 'Strona główna',
'mainpage-description' => 'Strona główna',
'policy-url'           => 'Project:Zasady',
'portal'               => 'Portal społeczności',
'portal-url'           => 'Project:Portal społeczności',
'privacy'              => 'Zasady ochrony prywatności',
'privacypage'          => 'Project:Zasady ochrony prywatności',

'badaccess'        => 'Niewłaściwe uprawnienia',
'badaccess-group0' => 'Nie masz uprawnień wymaganych do wykonania tej operacji.',
'badaccess-groups' => 'Wykonywanie tej operacji zostało ograniczone do użytkowników w {{PLURAL:$2|grupie|jednej z grup:}} $1.',

'versionrequired'     => 'Wymagane MediaWiki w wersji $1',
'versionrequiredtext' => 'Użycie tej strony wymaga oprogramowania MediaWiki w wersji $1. Zobacz stronę [[Special:Version|wersja oprogramowania]].',

'ok'                      => 'OK',
'pagetitle'               => '$1 – {{SITENAME}}',
'retrievedfrom'           => 'Źródło „$1”',
'youhavenewmessages'      => 'Masz $1 ($2).',
'newmessageslink'         => 'nowe wiadomości',
'newmessagesdifflink'     => 'różnica z poprzednią wersją',
'youhavenewmessagesmulti' => 'Masz nowe wiadomości na $1',
'editsection'             => 'edytuj',
'editold'                 => 'edytuj',
'viewsourceold'           => 'pokaż źródło',
'editlink'                => 'edytuj',
'viewsourcelink'          => 'tekst źródłowy',
'editsectionhint'         => 'Edytuj sekcję: $1',
'toc'                     => 'Spis treści',
'showtoc'                 => 'pokaż',
'hidetoc'                 => 'ukryj',
'thisisdeleted'           => 'Pokazać lub odtworzyć $1?',
'viewdeleted'             => 'Zobacz $1',
'restorelink'             => '{{PLURAL:$1|jedną usuniętą wersję|$1 usunięte wersje|$1 usuniętych wersji}}',
'feedlinks'               => 'Kanały:',
'feed-invalid'            => 'Niewłaściwy typ kanału informacyjnego.',
'feed-unavailable'        => 'Kanały informacyjne nie są dostępne',
'site-rss-feed'           => 'Kanał RSS {{GRAMMAR:D.lp|$1}}',
'site-atom-feed'          => 'Kanał Atom {{GRAMMAR:D.lp|$1}}',
'page-rss-feed'           => 'Kanał RSS „$1”',
'page-atom-feed'          => 'Kanał Atom „$1”',
'red-link-title'          => '$1 (strona nie istnieje)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Strona',
'nstab-user'      => 'Strona użytkownika',
'nstab-media'     => 'Pliki',
'nstab-special'   => 'Strona specjalna',
'nstab-project'   => 'Strona projektu',
'nstab-image'     => 'Plik',
'nstab-mediawiki' => 'Komunikat',
'nstab-template'  => 'Szablon',
'nstab-help'      => 'Pomoc',
'nstab-category'  => 'Kategoria',

# Main script and global functions
'nosuchaction'      => 'Brak takiej operacji',
'nosuchactiontext'  => 'Działanie określone w adresie URL jest nieprawidłowe.
Możliwe przyczyny to literówka w adresie, nieprawidłowy link lub błąd w oprogramowaniu {{GRAMMAR:D.lp|{{SITENAME}}}}.',
'nosuchspecialpage' => 'Brak takiej strony specjalnej',
'nospecialpagetext' => '<strong>Brak żądanej strony specjalnej.</strong>

Listę dostępnych stron specjalnych znajdziesz [[Special:SpecialPages|tutaj]].',

# General errors
'error'                => 'Błąd',
'databaseerror'        => 'Błąd bazy danych',
'dberrortext'          => 'Wystąpił błąd składni w zapytaniu do bazy danych.
Może to oznaczać błąd w oprogramowaniu.
Ostatnie, nieudane zapytanie to:
<blockquote><tt>$1</tt></blockquote>
wysłane przez funkcję „<tt>$2</tt>”.
Baza danych zgłosiła błąd „<tt>$3: $4</tt>”.',
'dberrortextcl'        => 'Wystąpił błąd składni w zapytaniu do bazy danych.
Ostatnie, nieudane zapytanie to:
„$1”
wywołane zostało przez funkcję „$2”.
Baza danych zgłosiła błąd „$3: $4”',
'laggedslavemode'      => 'Uwaga! Ta strona może nie zawierać najnowszych aktualizacji.',
'readonly'             => 'Baza danych jest zablokowana',
'enterlockreason'      => 'Podaj powód zablokowania bazy oraz szacunkowy termin jej odblokowania',
'readonlytext'         => 'Baza danych jest obecnie zablokowana – nie można wprowadzać nowych informacji ani modyfikować istniejących. Powodem są prawdopodobnie czynności administracyjne. Po ich zakończeniu przywrócona zostanie pełna funkcjonalność bazy.

Administrator, który zablokował bazę, podał następujące wyjaśnienie: $1',
'missing-article'      => 'W bazie danych nie odnaleziono treści strony „$1” $2.

Zazwyczaj jest to spowodowane odwołaniem do nieaktualnego linku prowadzącego do różnicy pomiędzy dwoma wersjami strony lub do wersji z historii usuniętej strony.

Jeśli tak nie jest, możliwe, że problem został wywołany przez błąd w oprogramowaniu.
Można zgłosić ten fakt [[Special:ListUsers/sysop|administratorowi]], podając adres URL.',
'missingarticle-rev'   => '(wersja $1)',
'missingarticle-diff'  => '(różnica: $1, $2)',
'readonly_lag'         => 'Baza danych została automatycznie zablokowana na czas potrzebny do wykonania synchronizacji zmian między serwerem głównym i serwerami pośredniczącymi.',
'internalerror'        => 'Błąd wewnętrzny',
'internalerror_info'   => 'Błąd wewnętrzny – $1',
'fileappenderrorread'  => 'Błąd odczytu „$1” w trakcie dołączania.',
'fileappenderror'      => 'Nie udało się dołączyć „$1” do „$2”.',
'filecopyerror'        => 'Nie można skopiować pliku „$1” do „$2”.',
'filerenameerror'      => 'Nie można zmienić nazwy pliku „$1” na „$2”.',
'filedeleteerror'      => 'Nie można usunąć pliku „$1”.',
'directorycreateerror' => 'Nie udało się utworzyć katalogu „$1”.',
'filenotfound'         => 'Nie można znaleźć pliku „$1”.',
'fileexistserror'      => 'Nie udało się zapisać do pliku „$1” ponieważ plik istnieje',
'unexpected'           => 'Nieoczekiwana wartość „$1”=„$2”.',
'formerror'            => 'Błąd – nie można wysłać formularza',
'badarticleerror'      => 'Dla tej strony ta operacja nie może być wykonana.',
'cannotdelete'         => 'Strona lub plik „$1” nie mogą zostać usunięte.
Możliwe, że zostały już usunięte przez kogoś innego.',
'badtitle'             => 'Niepoprawny tytuł',
'badtitletext'         => 'Podano niepoprawny tytuł strony. Prawdopodobnie jest pusty lub zawiera znaki, których użycie jest zabronione.',
'perfcached'           => 'Poniższe dane są kopią z pamięci podręcznej i mogą być nieaktualne.',
'perfcachedts'         => 'Poniższe dane są kopią z pamięci podręcznej. Ostatnia aktualizacja odbyła się $1.',
'querypage-no-updates' => 'Uaktualnienia dla tej strony są obecnie wyłączone. Znajdujące się tutaj dane nie zostaną odświeżone.',
'wrong_wfQuery_params' => 'Nieprawidłowe parametry przekazane do wfQuery()<br />
Funkcja: $1<br />
Zapytanie: $2',
'viewsource'           => 'Tekst źródłowy',
'viewsourcefor'        => 'dla $1',
'actionthrottled'      => 'Akcja wstrzymana',
'actionthrottledtext'  => 'Mechanizm obrony przed spamem ogranicza liczbę wykonań tej czynności w jednostce czasu. Usiłowałeś przekroczyć to ograniczenie. Spróbuj jeszcze raz za kilka minut.',
'protectedpagetext'    => 'Wyłączono możliwość edycji tej strony.',
'viewsourcetext'       => 'Tekst źródłowy strony można podejrzeć i skopiować.',
'protectedinterface'   => 'Ta strona zawiera tekst interfejsu oprogramowania, dlatego możliwość jej edycji została zablokowana.',
'editinginterface'     => "'''Ostrzeżenie:''' Edytujesz stronę, która zawiera tekst interfejsu oprogramowania.
Zmiany na tej stronie zmienią wygląd interfejsu dla innych użytkowników.
Rozważ wykonanie tłumaczenia na [http://translatewiki.net/wiki/Main_Page?setlang=pl translatewiki.net], specjalizowanym projekcie lokalizacji oprogramowania MediaWiki.",
'sqlhidden'            => '(ukryto zapytanie SQL)',
'cascadeprotected'     => 'Ta strona została zabezpieczona przed edycją, ponieważ jest ona zawarta na {{PLURAL:$1|następującej stronie, która została zabezpieczona|następujących stronach, które zostały zabezpieczone}} z włączoną opcją dziedziczenia:
$2',
'namespaceprotected'   => "Nie masz uprawnień do edytowania stron w przestrzeni nazw '''$1'''.",
'customcssjsprotected' => 'Nie możesz edytować tej strony, ponieważ zawiera ona ustawienia osobiste innego użytkownika.',
'ns-specialprotected'  => 'Stron specjalnych nie można edytować.',
'titleprotected'       => "Utworzenie strony o tej nazwie zostało zablokowane przez [[User:$1|$1]].
Uzasadnienie blokady: ''$2''.",

# Virus scanner
'virus-badscanner'     => "Zła konfiguracja – nieznany skaner antywirusowy ''$1''",
'virus-scanfailed'     => 'skanowanie nieudane (błąd $1)',
'virus-unknownscanner' => 'nieznany program antywirusowy',

# Login and logout pages
'logouttext'                 => "'''Nie jesteś już zalogowany.'''

Możesz kontynuować pracę w {{GRAMMAR:MS.lp|{{SITENAME}}}} jako niezarejestrowany użytkownik albo [[Special:UserLogin|zalogować się ponownie]] jako ten sam lub inny użytkownik.
Zauważ, że do momentu wyczyszczenia pamięci podręcznej przeglądarki niektóre strony mogą wyglądać tak, jakbyś wciąż był zalogowany.",
'welcomecreation'            => '== Witaj, $1! ==
Twoje konto zostało utworzone.
Nie zapomnij dostosować [[Special:Preferences|preferencji dla {{GRAMMAR:D.lp|{{SITENAME}}}}]].',
'yourname'                   => 'Nazwa {{GENDER:|użytkownika|użytkowniczki}}',
'yourpassword'               => 'Hasło',
'yourpasswordagain'          => 'Powtórz hasło',
'remembermypassword'         => 'Zapamiętaj moje hasło na tym komputerze (maksymalnie przez $1 {{PLURAL:$1|dzień|dni}})',
'yourdomainname'             => 'Twoja domena',
'externaldberror'            => 'Wystąpił błąd zewnętrznej bazy autentyfikacyjnej lub nie posiadasz uprawnień koniecznych do aktualizacji zewnętrznego konta.',
'login'                      => 'Zaloguj się',
'nav-login-createaccount'    => 'Logowanie i rejestracja',
'loginprompt'                => 'Musisz mieć włączoną w przeglądarce obsługę ciasteczek, by móc się zalogować do {{GRAMMAR:D.lp|{{SITENAME}}}}.',
'userlogin'                  => 'Logowanie i rejestracja',
'userloginnocreate'          => 'Zaloguj się',
'logout'                     => 'Wyloguj',
'userlogout'                 => 'Wyloguj',
'notloggedin'                => 'Nie jesteś zalogowany',
'nologin'                    => "Nie masz konta? '''$1'''.",
'nologinlink'                => 'Zarejestruj się',
'createaccount'              => 'Załóż nowe konto',
'gotaccount'                 => "Masz już konto? '''$1'''.",
'gotaccountlink'             => 'Zaloguj się',
'createaccountmail'          => 'poprzez e‐mail',
'createaccountreason'        => 'Powód',
'badretype'                  => 'Wprowadzone hasła różnią się między sobą.',
'userexists'                 => 'Wybrana przez Ciebie nazwa użytkownika jest już zajęta.
Wybierz inną nazwę użytkownika.',
'loginerror'                 => 'Błąd zalogowania',
'createaccounterror'         => 'Nie można utworzyć konta $1',
'nocookiesnew'               => 'Konto użytkownika zostało utworzone, ale nie jesteś zalogowany.
{{SITENAME}} używa ciasteczek do przechowywania informacji o zalogowaniu się.
Masz obecnie w przeglądarce wyłączoną obsługę ciasteczek.
Żeby się zalogować, włącz obsługę ciasteczek, następnie podaj nazwę użytkownika i hasło dostępu do swojego konta.',
'nocookieslogin'             => '{{SITENAME}} wykorzystuje ciasteczka do przechowywania informacji o zalogowaniu się przez użytkownika.
Masz obecnie w przeglądarce wyłączoną obsługę ciasteczek.
Spróbuj ponownie po ich odblokowaniu.',
'noname'                     => 'To nie jest poprawna nazwa użytkownika.',
'loginsuccesstitle'          => 'Zalogowano pomyślnie',
'loginsuccess'               => "'''{{GENDER:|Zalogowałeś się|Zalogowałaś się|Zalogowano}} do {{GRAMMAR:D.lp|{{SITENAME}}}} jako „$1”.'''",
'nosuchuser'                 => 'Brak użytkownika o nazwie „$1”.
W nazwie użytkownika ma znaczenie wielkość znaków.
Sprawdź pisownię lub [[Special:UserLogin/signup|utwórz nowe konto]].',
'nosuchusershort'            => 'Brak użytkownika o nazwie „<nowiki>$1</nowiki>”.
Sprawdź poprawność pisowni.',
'nouserspecified'            => 'Musisz podać nazwę użytkownika.',
'login-userblocked'          => 'Ten użytkownik jest zablokowany. Zalogowanie się jest niemożliwe.',
'wrongpassword'              => 'Podane hasło jest nieprawidłowe. Spróbuj jeszcze raz.',
'wrongpasswordempty'         => 'Wprowadzone hasło jest puste. Spróbuj ponownie.',
'passwordtooshort'           => 'Hasło musi mieć co najmniej $1 {{PLURAL:$1|znak|znaki|znaków}}.',
'password-name-match'        => 'Hasło musi być inne niż nazwa użytkownika.',
'mailmypassword'             => 'Wyślij mi nowe hasło poprzez e‐mail',
'passwordremindertitle'      => 'Nowe tymczasowe hasło do {{GRAMMAR:D.lp|{{SITENAME}}}}',
'passwordremindertext'       => 'Ktoś (prawdopodobnie Ty, spod adresu IP $1)
poprosił o przesłanie nowego hasła do {{GRAMMAR:D.lp|{{SITENAME}}}} ($4).
Dla użytkownika „$2” zostało wygenerowane tymczasowe hasło i jest nim „$3”.
Jeśli było to zamierzone działanie, to po zalogowaniu się, musisz podać nowe hasło.
Tymczasowe hasło wygaśnie za {{PLURAL:$5|1 dzień|$5 dni}}.

Jeśli to nie Ty prosiłeś o przesłanie hasła lub przypomniałeś sobie hasło i nie chcesz go zmieniać, wystarczy, że zignorujesz tę wiadomość i dalej będziesz się posługiwać swoim dotychczasowym hasłem.',
'noemail'                    => 'Brak zdefiniowanego adresu e‐mail dla użytkownika „$1”.',
'noemailcreate'              => 'Musisz podać prawidłowy adres e‐mail',
'passwordsent'               => 'Nowe hasło zostało wysłane na adres e‐mail użytkownika „$1”.
Po otrzymaniu go zaloguj się ponownie.',
'blocked-mailpassword'       => 'Twój adres IP został zablokowany i nie możesz używać funkcji odzyskiwania hasła z powodu możliwości jej nadużywania.',
'eauthentsent'               => 'Potwierdzenie zostało wysłane na adres e‐mail.
Zanim jakiekolwiek inne wiadomości zostaną wysłane na ten adres, należy wykonać zawarte w mailu instrukcje. Potwierdzisz w ten sposób, że ten adres e‐mail należy do Ciebie.',
'throttled-mailpassword'     => 'Przypomnienie hasła zostało już wysłane w ciągu {{PLURAL:$1|ostatniej godziny|ostatnich $1 godzin}}.
W celu powstrzymania nadużyć możliwość wysyłania przypomnień została ograniczona do jednego na {{PLURAL:$1|godzinę|$1 godziny|$1 godzin}}.',
'mailerror'                  => 'W trakcie wysyłania wiadomości e‐mail wystąpił błąd: $1',
'acct_creation_throttle_hit' => 'Z adresu IP, z którego korzystasz {{PLURAL:$1|ktoś już utworzył dziś konto|utworzono dziś $1 konta|utworzono dziś $1 kont}}, co jest maksymalną dopuszczalną liczbą w tym czasie.
W związku z tym, osoby korzystające z tego adresu IP w chwili obecnej nie mogą założyć kolejnego.',
'emailauthenticated'         => 'Twój adres e‐mail został potwierdzony $2 o $3.',
'emailnotauthenticated'      => "Twój adres '''e‐mail nie został potwierdzony'''.
Poniższe funkcje poczty nie działają.",
'noemailprefs'               => 'Podaj adres e‐mail w preferencjach, by skorzystać z tych funkcji.',
'emailconfirmlink'           => 'Potwierdź swój adres e‐mail',
'invalidemailaddress'        => 'Adres e‐mail jest niepoprawny i nie może być zaakceptowany.
Wpisz poprawny adres e‐mail lub wyczyść pole.',
'accountcreated'             => 'Konto zostało utworzone',
'accountcreatedtext'         => 'Konto dla $1 zostało utworzone.',
'createaccount-title'        => 'Utworzenie konta w {{GRAMMAR:MS.lp|{{SITENAME}}}}',
'createaccount-text'         => 'Ktoś utworzył w {{GRAMMAR:MS.lp|{{SITENAME}}}} ($4), podając Twój adres e‐mail, konto „$2”. Aktualnym hasłem jest „$3”.
Zaloguj się teraz i je zmień.

Możesz zignorować tę wiadomość, jeśli konto zostało utworzone przez pomyłkę.',
'usernamehasherror'          => 'Nazwa użytkownika nie może zawierać znaków kratki „#”',
'login-throttled'            => 'Zbyt wiele razy próbowałeś zalogować się na to konto.
Odczekaj chwilę zanim ponowisz próbę.',
'loginlanguagelabel'         => 'Język: $1',
'suspicious-userlogout'      => 'Żądanie wylogowania zostało odrzucone ponieważ wygląda na to, że zostało wysłane przez uszkodzoną przeglądarkę lub buforujący serwer proxy.',

# JavaScript password checks
'password-strength'            => 'Szacowane bezpieczeństwo hasła – $1',
'password-strength-bad'        => 'ZŁE',
'password-strength-mediocre'   => 'mierne',
'password-strength-acceptable' => 'akceptowalne',
'password-strength-good'       => 'dobre',
'password-retype'              => 'Powtórz hasło',
'password-retype-mismatch'     => 'Hasła nie są identyczne',

# Password reset dialog
'resetpass'                 => 'Zmień hasło',
'resetpass_announce'        => '{{GENDER:|Zalogowałeś|Zalogowałaś}} się, wykorzystując tymczasowe hasło otrzymane poprzez e‐mail.
Aby zakończyć proces logowania, musisz ustawić nowe hasło:',
'resetpass_text'            => '<!-- Dodaj tekst -->',
'resetpass_header'          => 'Zmień hasło dla swojego konta',
'oldpassword'               => 'Stare hasło',
'newpassword'               => 'Nowe hasło',
'retypenew'                 => 'Powtórz nowe hasło',
'resetpass_submit'          => 'Ustaw hasło i zaloguj się',
'resetpass_success'         => 'Twoje hasło zostało pomyślnie zmienione! Trwa logowanie...',
'resetpass_forbidden'       => 'Hasła nie mogą zostać zmienione',
'resetpass-no-info'         => 'Musisz być zalogowany, by uzyskać bezpośredni dostęp do tej strony.',
'resetpass-submit-loggedin' => 'Zmień hasło',
'resetpass-submit-cancel'   => 'Anuluj',
'resetpass-wrong-oldpass'   => 'Nieprawidłowe tymczasowe lub aktualne hasło.
Być może właśnie zmienił{{GENDER:|eś|aś|eś(‐aś)}} swoje hasło lub poprosił{{GENDER:|eś|aś|eś(‐aś)}} o nowe tymczasowe hasło.',
'resetpass-temp-password'   => 'Tymczasowe hasło:',

# Edit page toolbar
'bold_sample'     => 'Tekst tłustą czcionką',
'bold_tip'        => 'Tekst tłustą czcionką',
'italic_sample'   => 'Tekst pochyłą czcionką',
'italic_tip'      => 'Tekst pochyłą czcionką',
'link_sample'     => 'Tytuł linku',
'link_tip'        => 'Link wewnętrzny',
'extlink_sample'  => 'http://www.example.com nazwa linku',
'extlink_tip'     => 'Link zewnętrzny (pamiętaj o przedrostku http:// )',
'headline_sample' => 'Tekst nagłówka',
'headline_tip'    => 'Nagłówek 2. poziomu',
'math_sample'     => 'Tutaj wprowadź wzór',
'math_tip'        => 'Wzór matematyczny (LaTeX)',
'nowiki_sample'   => 'Tutaj wstaw niesformatowany tekst',
'nowiki_tip'      => 'Zignoruj formatowanie wiki',
'image_sample'    => 'Przykład.jpg',
'image_tip'       => 'Grafika lub inny plik osadzony w stronie',
'media_sample'    => 'Przykład.ogg',
'media_tip'       => 'Link do pliku',
'sig_tip'         => 'Twój podpis wraz z datą i czasem',
'hr_tip'          => 'Linia pozioma (nie nadużywaj)',

# Edit pages
'summary'                          => 'Opis zmian ',
'subject'                          => 'Temat/nagłówek:',
'minoredit'                        => 'To jest drobna zmiana',
'watchthis'                        => 'Obserwuj',
'savearticle'                      => 'Zapisz',
'preview'                          => 'Podgląd',
'showpreview'                      => 'Pokaż podgląd',
'showlivepreview'                  => 'Dynamiczny podgląd',
'showdiff'                         => 'Podgląd zmian',
'anoneditwarning'                  => "'''Uwaga:''' Nie jesteś {{GENDER:|zalogowany|zalogowana}}.
Twój adres IP zostanie zapisany w historii edycji strony.",
'anonpreviewwarning'               => "''Nie jesteś zalogowany. Jeśli zapiszesz zmiany w historii edycji strony zostanie umieszczony Twój adres IP.''",
'missingsummary'                   => "'''Uwaga:''' Nie wprowadz{{GENDER:|iłeś|iłaś|ono}} opisu zmian.
Jeżeli nie chcesz go wprowadzać, naciśnij przycisk „Zapisz” jeszcze raz.",
'missingcommenttext'               => 'Wprowadź komentarz poniżej.',
'missingcommentheader'             => "'''Uwaga''' – treść tytułu lub nagłówka komentarza jest pusta.
Jeśli ponownie klikniesz „{{int:savearticle}}“, zmiany zostaną zapisane bez niego.",
'summary-preview'                  => 'Podgląd opisu:',
'subject-preview'                  => 'Podgląd nagłówka:',
'blockedtitle'                     => 'Użytkownik jest zablokowany',
'blockedtext'                      => "'''Twoje konto lub adres IP zostały zablokowane.'''

Blokada została nałożona przez $1.
Podany powód to: ''$2''.

* Początek blokady: $8
* Wygaśnięcie blokady: $6
* Zablokowany został: $7

W celu wyjaśnienia przyczyny zablokowania możesz się skontaktować z $1 lub innym [[{{MediaWiki:Grouppage-sysop}}|administratorem]].
Nie możesz użyć funkcji „Wyślij e‐mail do tego użytkownika”, jeśli brak jest poprawnego adresu e‐mail w Twoich [[Special:Preferences|preferencjach]] lub jeśli taka możliwość została Ci zablokowana.
Twój obecny adres IP to $3, a numer identyfikacyjny blokady to $5.
Prosimy o podanie obu tych informacji przy wyjaśnianiu blokady.",
'autoblockedtext'                  => "Ten adres IP został zablokowany automatycznie, gdyż korzysta z niego inny użytkownik, zablokowany przez administratora $1.
Powód blokady:

:''$2''

* Początek blokady: $8
* Wygaśnięcie blokady: $6
* Zablokowany został: $7

Możesz skontaktować się z $1 lub jednym z pozostałych [[{{MediaWiki:Grouppage-sysop}}|administratorów]] w celu uzyskania informacji o blokadzie.

Nie możesz użyć funkcji „Wyślij e‐mail do tego użytkownika”, jeśli brak jest poprawnego adresu e‐mail w Twoich [[Special:Preferences|preferencjach]] lub jeśli taka możliwość została Ci zablokowana.

Twój obecny adres IP to $3, a numer identyfikacyjny blokady to $5.
Prosimy o podanie obu tych numerów przy wyjaśnianiu blokady.",
'blockednoreason'                  => 'nie podano przyczyny',
'blockedoriginalsource'            => "Źródło '''$1''' zostało pokazane poniżej:",
'blockededitsource'                => "Tekst '''Twoich edycji''' na '''$1''' został pokazany poniżej:",
'whitelistedittitle'               => 'Przed edycją musisz się zalogować',
'whitelistedittext'                => 'Musisz $1, by edytować strony.',
'confirmedittext'                  => 'Edytowanie jest możliwe dopiero po zweryfikowaniu adresu e‐mail.
Podaj adres e‐mail i potwierdź go w swoich [[Special:Preferences|ustawieniach użytkownika]].',
'nosuchsectiontitle'               => 'Nie można znaleźć sekcji',
'nosuchsectiontext'                => '{{GENDER:|Próbowałeś|Próbowałaś}} edytować sekcję, która nie istnieje.
Mogła zostać przeniesiona lub usunięta podczas przeglądania tej strony.',
'loginreqtitle'                    => 'musisz się zalogować',
'loginreqlink'                     => 'zalogować się',
'loginreqpagetext'                 => 'Musisz $1, żeby móc przeglądać inne strony.',
'accmailtitle'                     => 'Hasło zostało wysłane.',
'accmailtext'                      => "Losowo wygenerowane hasło dla [[User talk:$1|$1]] zostało wysłane do $2.

Hasło dla tego nowego konta po zalogowaniu można zmienić na stronie ''[[Special:ChangePassword|zmiana hasła]]''.",
'newarticle'                       => '(Nowy)',
'newarticletext'                   => "Brak strony o tym tytule.
Jeśli chcesz ją utworzyć, wpisz treść strony w poniższym polu (więcej informacji odnajdziesz [[{{MediaWiki:Helppage}}|na stronie pomocy]]).
Jeśli utworzenie nowej strony nie było Twoim zamiarem, wciśnij ''Wstecz'' w swojej przeglądarce.",
'anontalkpagetext'                 => "---- ''To jest strona dyskusji anonimowego użytkownika – takiego, który nie ma jeszcze swojego konta lub nie chce go w tej chwili używać.
By go identyfikować, używamy adresów IP.
Jednak adres IP może być współdzielony przez wielu użytkowników.
Jeśli jesteś anonimowym użytkownikiem i uważasz, że zamieszczone tu komentarze nie są skierowane do Ciebie, [[Special:UserLogin/signup|utwórz konto]] lub [[Special:UserLogin|zaloguj się]] – dzięki temu unikniesz w przyszłości podobnych nieporozumień.''",
'noarticletext'                    => 'Brak strony o tym tytule.
Możesz [[Special:Search/{{PAGENAME}}|poszukać „{{PAGENAME}}” na innych stronach]],
<span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} przeszukać log] lub [{{fullurl:{{FULLPAGENAME}}|action=edit}} utworzyć tę stronę]</span>.',
'noarticletext-nopermission'       => 'Na tej stronie nie ma jeszcze artykułu.
Możesz [[Special:Search/{{PAGENAME}}|wyszukać ten tytuł]] w treści innych stron
lub <span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} przeszukać powiązane logi].</span>',
'userpage-userdoesnotexist'        => 'Użytkownik „$1” nie jest zarejestrowany.
Upewnij się, czy na pewno zamierza{{GENDER:|łeś|łaś|sz}} utworzyć lub zmodyfikować właśnie tę stronę.',
'userpage-userdoesnotexist-view'   => 'Konto użytkownika „$1” nie jest zarejestrowane.',
'blocked-notice-logextract'        => '{{GENDER:$1|Ten użytkownik|Ta użytkowniczka}} jest obecnie {{GENDER:$1|zablokowany|zablokowana}}.
Ostatni wpis rejestru blokad jest pokazany poniżej.',
'clearyourcache'                   => "'''Uwaga:''' Zmiany po zapisaniu nowych ustawień mogą nie być widoczne. Należy wyczyścić zawartość pamięci podręcznej przeglądarki internetowej.
*'''Mozilla, Firefox lub Safari –''' przytrzymaj wciśnięty ''Shift'' i kliknij na ''Odśwież'' lub wciśnij ''Ctrl‐F5'' lub ''Ctrl‐R'' (''Cmd‐Shift‐R'' na Macintoshu)
*'''Konqueror –''' kliknij przycisk ''Odśwież'' lub wciśnij ''F5''
*'''Opera –''' wyczyść pamięć podręczną w menu ''Narzędzia → Preferencje''
*'''Internet Explorer –''' przytrzymaj ''Ctrl'' i kliknij na ''Odśwież'' lub wciśnij ''Ctrl‐F5''",
'usercssyoucanpreview'             => "'''Podpowiedź:''' Użyj przycisku „Podgląd”, aby przetestować nowy arkusz stylów CSS przed jego zapisaniem.",
'userjsyoucanpreview'              => "'''Podpowiedź:''' Użyj przycisku „Podgląd”, aby przetestować nowy kod JavaScript przed jego zapisaniem.",
'usercsspreview'                   => "'''Pamiętaj, że to tylko podgląd arkusza stylów CSS – nic jeszcze nie zostało zapisane!'''",
'userjspreview'                    => "'''Pamiętaj, że to tylko podgląd Twojego kodu JavaScript – nic jeszcze nie zostało zapisane!'''",
'userinvalidcssjstitle'            => "'''Uwaga:''' Brak skórki o nazwie „$1”.
Strony użytkownika zawierające CSS i JavaScript powinny zaczynać się małą literą, np. {{ns:user}}:Foo/monobook.css, w przeciwieństwie do nieprawidłowego {{ns:user}}:Foo/Monobook.css.",
'updated'                          => '(Zmodyfikowano)',
'note'                             => "'''Uwaga:'''",
'previewnote'                      => "'''To jest tylko podgląd – zmiany nie zostały jeszcze zapisane!'''",
'previewconflict'                  => 'Podgląd odnosi się do tekstu z górnego pola edycji. Tak będzie wyglądać strona, jeśli zdecydujesz się ją zapisać.',
'session_fail_preview'             => "'''Uwaga! Serwer nie może przetworzyć tej edycji z powodu utraty danych sesji.
Spróbuj jeszcze raz.
Jeśli to nie pomoże – [[Special:UserLogout|wyloguj się]] i zaloguj ponownie.'''",
'session_fail_preview_html'        => "'''Uwaga! Serwer nie może przetworzyć tej edycji z powodu utraty danych sesji.'''

''Ponieważ w {{GRAMMAR:MS.lp|{{SITENAME}}}} włączona została opcja „surowy HTML”, podgląd został ukryty w celu zabezpieczenia przed atakami z użyciem JavaScriptu.''

'''Jeśli jest to uprawniona próba dokonania edycji, spróbuj jeszcze raz.
Jeśli to nie pomoże – [[Special:UserLogout|wyloguj się]] i zaloguj ponownie.'''",
'token_suffix_mismatch'            => "'''Twoja edycja została odrzucona, ponieważ twój klient pomieszał znaki interpunkcyjne w żetonie edycyjnym.
Twoja edycja została odrzucona by zapobiec zniszczeniu tekstu strony.
Takie problemy zdarzają się w wypadku korzystania z wadliwych anonimowych sieciowych usług proxy.'''",
'editing'                          => 'Edytujesz „$1”',
'editingsection'                   => 'Edytujesz „$1” (fragment)',
'editingcomment'                   => 'Edytujesz „$1” (nowa sekcja)',
'editconflict'                     => 'Konflikt edycji: $1',
'explainconflict'                  => "Ktoś zmienił treść strony w trakcie Twojej edycji.
Górne pole zawiera tekst strony aktualnie zapisany w bazie danych.
Twoje zmiany znajdują się w dolnym polu.
By wprowadzić swoje zmiany, musisz zmodyfikować tekst z górnego pola.
'''Tylko''' tekst z górnego pola zostanie zapisany w bazie, gdy wciśniesz „Zapisz”.",
'yourtext'                         => 'Twój tekst',
'storedversion'                    => 'Zapisana wersja',
'nonunicodebrowser'                => "'''Uwaga! Twoja przeglądarka nie rozpoznaje poprawnie kodowania UTF‐8 (Unicode).
Z tego powodu wszystkie znaki, których przeglądarka nie rozpoznaje, zostały zastąpione ich kodami szesnastkowymi.'''",
'editingold'                       => "'''Uwaga! Edytujesz starszą niż bieżąca wersję tej strony.
Jeśli ją zapiszesz, wszystkie zmiany wykonane w międzyczasie zostaną wycofane.'''",
'yourdiff'                         => 'Różnice',
'copyrightwarning'                 => "Wkład do {{GRAMMAR:D.lp|{{SITENAME}}}} jest udostępniany na licencji $2 (szczegóły w $1). Jeśli nie chcesz, żeby Twój tekst był dowolnie zmieniany przez każdego i rozpowszechniany bez ograniczeń, nie umieszczaj go tutaj.<br />
Zapisując swoją edycję, oświadczasz, że ten tekst jest Twoim dziełem lub pochodzi z materiałów dostępnych na warunkach ''domeny publicznej'' lub kompatybilnych.
'''PROSZĘ NIE WPROWADZAĆ MATERIAŁÓW CHRONIONYCH PRAWEM AUTORSKIM BEZ POZWOLENIA WŁAŚCICIELA!'''",
'copyrightwarning2'                => "Wszelki wkład w {{GRAMMAR:B.lp|{{SITENAME}}}} może być edytowany, zmieniany lub usunięty przez innych użytkowników.
Jeśli nie chcesz, żeby Twój tekst był dowolnie zmieniany przez każdego i rozpowszechniany bez ograniczeń, nie umieszczaj go tutaj.<br />
Zapisując swoją edycję, oświadczasz, że ten tekst jest Twoim dziełem lub pochodzi z materiałów dostępnych na warunkach ''domeny publicznej'' lub kompatybilnych (zobacz także $1).
'''PROSZĘ NIE WPROWADZAĆ MATERIAŁÓW CHRONIONYCH PRAWEM AUTORSKIM BEZ POZWOLENIA WŁAŚCICIELA!'''",
'longpagewarning'                  => "'''Ta strona ma {{PLURAL:$1|1 kilobajt|$1 kilobajty|$1 kilobajtów}}. Jeśli to możliwe, spróbuj podzielić tekst na mniejsze części.'''",
'longpageerror'                    => "'''Błąd! Wprowadzony przez Ciebie tekst ma {{PLURAL:$1|1 kilobajt|$1 kilobajty|$1 kilobajtów}}. Długość tekstu nie może przekraczać {{PLURAL:$2|1 kilobajt|$2 kilobajty|$2 kilobajtów}}. Tekst nie może być zapisany.'''",
'readonlywarning'                  => "'''Uwaga! Baza danych została zablokowana do celów administracyjnych. W tej chwili nie można zapisać nowej wersji strony. Zapisz jej treść do pliku, używając wytnij i wklej, aby zachować na później.'''

Administrator, który zablokował bazę, podał następujące wyjaśnienie: $1",
'protectedpagewarning'             => "'''Uwaga! Możliwość modyfikacja tej strony została zabezpieczona. Mogą ją edytować jedynie użytkownicy z uprawnieniami administratora.'''
Ostatni wpis z rejestru jest pokazany poniżej.",
'semiprotectedpagewarning'         => "'''Uwaga!''' Ta strona została zabezpieczona i tylko zarejestrowani użytkownicy mogą ją edytować.
Ostatni wpis z rejestru jest pokazany poniżej.",
'cascadeprotectedwarning'          => "'''Uwaga!''' Ta strona została zabezpieczona i tylko użytkownicy z uprawnieniami administratora mogą ją edytować. Strona ta jest zawarta na {{PLURAL:$1|następującej stronie, która została zabezpieczona|następujących stronach, które zostały zabezpieczone}} z włączoną opcją dziedziczenia:",
'titleprotectedwarning'            => "'''Uwaga! Utworzenie strony o tej nazwie zostało zabezpieczone. Do jej utworzenia wymagane są [[Special:ListGroupRights|specyficzne uprawnienia]].'''
Ostatni wpis z rejestru jest pokazany poniżej.",
'templatesused'                    => '{{PLURAL:$1|Szablon użyty|Szablony użyte}} w tym artykule:',
'templatesusedpreview'             => '{{PLURAL:$1|Szablon użyty|Szablony użyte}} w tym podglądzie:',
'templatesusedsection'             => '{{PLURAL:$1|Szablon użyty|Szablony użyte}} w tej sekcji:',
'template-protected'               => '(zabezpieczony)',
'template-semiprotected'           => '(częściowo zabezpieczony)',
'hiddencategories'                 => 'Ta strona jest w {{PLURAL:$1|jednej ukrytej kategorii|$1 ukrytych kategoriach}}:',
'edittools'                        => '<!-- Znajdujący się tutaj tekst zostanie pokazany pod polem edycji i formularzem przesyłania plików. -->',
'nocreatetitle'                    => 'Ograniczono możliwość tworzenia nowych stron',
'nocreatetext'                     => 'W {{GRAMMAR:MS.lp|{{SITENAME}}}} ograniczono możliwość tworzenia nowych stron.
Możesz edytować istniejące strony bądź też [[Special:UserLogin|zalogować się lub utworzyć konto]].',
'nocreate-loggedin'                => 'Nie masz uprawnień do tworzenia nowych stron.',
'sectioneditnotsupported-title'    => 'Edycja sekcji nie jest obsługiwana',
'sectioneditnotsupported-text'     => 'Edycja sekcji na tej stronie nie jest obsługiwana.',
'permissionserrors'                => 'Błędy uprawnień',
'permissionserrorstext'            => 'Nie masz uprawnień do tego działania z {{PLURAL:$1|następującej przyczyny|następujących przyczyn}}:',
'permissionserrorstext-withaction' => 'Nie masz uprawnień do $2, z {{PLURAL:$1|następującego powodu|następujących powodów}}:',
'recreate-moveddeleted-warn'       => "'''Uwaga! Zamierzasz utworzyć stronę, która została wcześniej usunięta.'''

Upewnij się, czy ponowne utworzenie tej strony jest uzasadnione.
Poniżej znajduje się rejestr usunięć i zmian nazwy tej strony:",
'moveddeleted-notice'              => 'Ta strona została usunięta.
Rejestr usunięć i zmian nazwy tej strony jest pokazany poniżej.',
'log-fulllog'                      => 'Pokaż cały rejestr',
'edit-hook-aborted'                => 'Edycja zatrzymana z powodu haka.
Wystąpił z nieokreślonej przyczyny.',
'edit-gone-missing'                => 'Nie udało się zaktualizować strony.
Zdaje się, że została skasowana.',
'edit-conflict'                    => 'Konflikt edycji.',
'edit-no-change'                   => 'Twoja edycja została zignorowana, ponieważ nie zmienił{{GENDER:|eś|aś|eś(‐aś)}} niczego w tekście.',
'edit-already-exists'              => 'Nie udało się stworzyć nowej strony.
Strona już istnieje.',

# Parser/template warnings
'expensive-parserfunction-warning'        => 'Uwaga! Ta strona zawiera zbyt wiele wywołań złożonych obliczeniowo funkcji parsera.

Powinno być mniej niż $2 {{PLURAL:$2|wywołanie|wywołania|wywołań}}, a obecnie {{PLURAL:$1|jest $1 wywołanie|są $1 wywołania|jest $1 wywołań}}.',
'expensive-parserfunction-category'       => 'Strony ze zbyt dużą liczbą wywołań trudnych funkcji parsera',
'post-expand-template-inclusion-warning'  => 'Uwaga – zbyt duża wielkość wykorzystanych szablonów.
Niektóre szablony nie zostaną użyte.',
'post-expand-template-inclusion-category' => 'Strony, w których przekroczone jest ograniczenie wielkości użytych szablonów',
'post-expand-template-argument-warning'   => 'Uwaga – strona zawiera co najmniej jeden argument szablonu, który po rozwinięciu jest zbyt duży.
Argument ten będzie pominięty.',
'post-expand-template-argument-category'  => 'Strony, w których użyto szablon z pominięciem argumentów',
'parser-template-loop-warning'            => 'Wykryto pętlę w szablonie [[$1]]',
'parser-template-recursion-depth-warning' => 'Przekroczno limit głębokości rekurencji szablonu ($1)',
'language-converter-depth-warning'        => 'Przekroczono ograniczenie ($1) głębokości zagnieżdżenia konwersji językowej',

# "Undo" feature
'undo-success' => 'Edycja może zostać wycofana. Porównaj ukazane poniżej różnice między wersjami, a następnie zapisz zmiany.',
'undo-failure' => 'Edycja nie może zostać wycofana z powodu konfliktu z wersjami pośrednimi.',
'undo-norev'   => 'Edycja nie może być cofnięta, ponieważ nie istnieje lub została usunięta.',
'undo-summary' => 'Anulowanie wersji nr $1 utworzonej przez [[Special:Contributions/$2|$2]] ([[User talk:$2|dyskusja]])',

# Account creation failure
'cantcreateaccounttitle' => 'Nie można utworzyć konta',
'cantcreateaccount-text' => "Tworzenie konta z tego adresu IP ('''$1''') zostało zablokowane przez [[User:$3|$3]].

Podany przez $3 powód to ''$2''",

# History pages
'viewpagelogs'           => 'Zobacz rejestry operacji dla tej strony',
'nohistory'              => 'Ta strona nie ma swojej historii edycji.',
'currentrev'             => 'Aktualna wersja',
'currentrev-asof'        => 'Aktualna wersja na dzień $1',
'revisionasof'           => 'Wersja z $1',
'revision-info'          => 'Wersja $2 z dnia $1',
'previousrevision'       => '← poprzednia wersja',
'nextrevision'           => 'następna wersja →',
'currentrevisionlink'    => 'przejdź do aktualnej wersji',
'cur'                    => 'bież.',
'next'                   => 'następna',
'last'                   => 'poprz.',
'page_first'             => 'początek',
'page_last'              => 'koniec',
'histlegend'             => "Wybór porównania – zaznacz kropeczkami dwie wersje do porównania i wciśnij enter lub przycisk ''Porównaj wybrane wersje''.<br />
Legenda: (bież.) – pokaż zmiany od tej wersji do bieżącej,
(poprz.) – pokaż zmiany od wersji poprzedzającej, m – mała (drobna) zmiana",
'history-fieldset-title' => 'Przeglądaj historię',
'history-show-deleted'   => 'Tylko usunięte',
'histfirst'              => 'od początku',
'histlast'               => 'od końca',
'historysize'            => '({{PLURAL:$1|1 bajt|$1 bajty|$1 bajtów}})',
'historyempty'           => '(pusta)',

# Revision feed
'history-feed-title'          => 'Historia wersji',
'history-feed-description'    => 'Historia wersji tej strony wiki',
'history-feed-item-nocomment' => '$1 o $2',
'history-feed-empty'          => 'Wybrana strona nie istnieje.
Mogła zostać usunięta lub jej nazwa została zmieniona.
Spróbuj [[Special:Search|poszukać]] tej strony.',

# Revision deletion
'rev-deleted-comment'         => '(komentarz usunięty)',
'rev-deleted-user'            => '(nazwa użytkownika usunięta)',
'rev-deleted-event'           => '(wpis usunięty)',
'rev-deleted-user-contribs'   => '[nazwa użytkownika lub adres IP usunięte – edycja ukryta we wkładzie]',
'rev-deleted-text-permission' => "Ta wersja strony została '''usunięta'''.
Szczegółowe informacje mogą znajdować się w [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} rejestrze usunięć].",
'rev-deleted-text-unhide'     => "Ta wersja strony została '''usunięta'''.
Szczegółowe informacje mogą znajdować się w [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} rejestrze usunięć].
Jako administrator możesz [$1 obejrzeć tę wersję].",
'rev-suppressed-text-unhide'  => "Ta wersja strony została '''utajniona'''.
Szczegółowe informacje mogą znajdować się w [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} rejestrze utajniania].
Jako administrator możesz [$1 obejrzeć tę wersję].",
'rev-deleted-text-view'       => "Ta wersja strony została '''usunięta'''.
Jako administrator możesz ją obejrzeć. Szczegółowe informacje mogą znajdować się w [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} rejestrze usunięć].",
'rev-suppressed-text-view'    => "Ta wersja strony została '''utajniona'''.
Jako administrator możesz ją obejrzeć. Szczegółowe informacje mogą znajdować się w [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} rejestrze utajniania].",
'rev-deleted-no-diff'         => "Nie możesz zobaczyć porównania wersji, ponieważ jedna z nich została '''usunięta'''.
Szczegółowe informacje mogą znajdować się w [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} rejestrze usunięć].",
'rev-suppressed-no-diff'      => "Nie można wyświetlić różnic, ponieważ jedna z wersji została '''usunięta'''.",
'rev-deleted-unhide-diff'     => "Jedna z wersji tej strony została '''usunięta'''.
Szczegółowe informacje mogą znajdować się w [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} rejestrze usunięć].
Jako administrator możesz [$1 obejrzeć porównanie wersji].",
'rev-suppressed-unhide-diff'  => "Jedna z porównywanych wersji została '''ukryta'''.
Szczegółowe informacje mogą znajdować się w [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} rejestrze ukrywania].
Ponieważ jesteś administratorem możesz [$1 obejrzeć to porównanie wersji].",
'rev-deleted-diff-view'       => "Jedna z wersji użytych w porównaniu została '''usunięta'''.
Jako administrator możesz zobaczyć porównanie. Szczegóły mogą znajdować się w [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} rejestrze usunięć].",
'rev-suppressed-diff-view'    => "Jedna z wersji użytych w porównaniu została '''ukryta'''.
Jako administrator możesz zobaczyć porównanie. Szczegóły mogą znajdować się w [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} rejestrze ukrywania].",
'rev-delundel'                => 'pokaż/ukryj',
'rev-showdeleted'             => 'pokaż',
'revisiondelete'              => 'Usuń/przywróć wersje',
'revdelete-nooldid-title'     => 'Nieprawidłowa wersja do przeprowadzenia operacji',
'revdelete-nooldid-text'      => 'Nie wybrano wersji, na których ma zostać wykonana ta operacja,
wybrana wersja nie istnieje lub próbowano ukryć wersję bieżącą.',
'revdelete-nologtype-title'   => 'Brak typu rejestru',
'revdelete-nologtype-text'    => 'Nie określ{{GENDER:|iłeś|iłaś|ono}} rodzaju rejestru do przeprowadzenia tej operacji.',
'revdelete-nologid-title'     => 'Nieprawidłowy wpis w rejestrze',
'revdelete-nologid-text'      => 'Nie określ{{GENDER:|iłeś|iłaś|ono}} wpisu w rejestrze do przeprowadzenia operacji albo wybrany wpis nie istnieje.',
'revdelete-no-file'           => 'Wybrany plik nie istnieje.',
'revdelete-show-file-confirm' => 'Czy jesteś pewien, że chcesz zobaczyć usuniętą wersję pliku „<nowiki>$1</nowiki>” z $2 o $3?',
'revdelete-show-file-submit'  => 'Tak',
'revdelete-selected'          => "'''{{PLURAL:$2|Zaznaczona wersja|Zaznaczone wersje}} strony [[:$1]]:'''",
'logdelete-selected'          => "'''Zaznaczone {{PLURAL:$1|zdarzenie|zdarzenia}} z rejestru:'''",
'revdelete-text'              => "'''Usunięte wersje i czynności będą nadal widoczne w historii strony i rejestrach, ale ich treść nie będzie publicznie dostępna.'''
Inni administratorzy {{GRAMMAR:D.lp|{{SITENAME}}}} nadal będą mieć dostęp do ukrytych treści oraz będą mogli je odtworzyć używając standardowych mechanizmów, chyba że nałożono dodatkowe ograniczenia.",
'revdelete-confirm'           => 'Potwierdzić, że chcesz to zrobić, rozumiesz konsekwencje oraz że robisz to zgodnie z [[{{MediaWiki:Policy-url}}|zasadami]].',
'revdelete-suppress-text'     => "Ukrywanie powinno być używane '''wyłącznie''' w sytuacji:
* Ujawnienie danych osobowych
*: ''adres domowy, numer telefonu, numer PESEL itp''",
'revdelete-legend'            => 'Ustaw ograniczenia widoczności',
'revdelete-hide-text'         => 'Ukryj tekst wersji',
'revdelete-hide-image'        => 'Ukryj zawartość pliku',
'revdelete-hide-name'         => 'Ukryj akcję i cel',
'revdelete-hide-comment'      => 'Ukryj komentarz edycji',
'revdelete-hide-user'         => 'Ukryj nazwę użytkownika/adres IP',
'revdelete-hide-restricted'   => 'Ukryj informacje przed administratorami tak samo jak przed innymi',
'revdelete-radio-same'        => '(bez zmian)',
'revdelete-radio-set'         => 'Tak',
'revdelete-radio-unset'       => 'Nie',
'revdelete-suppress'          => 'Utajnij informacje przed administratorami, tak samo jak przed innymi',
'revdelete-unsuppress'        => 'Wyłącz utajnianie dla odtwarzanej historii zmian',
'revdelete-log'               => 'Powód',
'revdelete-submit'            => 'Zaakceptuj dla {{PLURAL:$1|wybranej|wybranych}} wersji',
'revdelete-logentry'          => 'zmienił widoczność wersji w [[$1]]',
'logdelete-logentry'          => 'zmienił widoczność zdarzenia dla [[$1]]',
'revdelete-success'           => "'''Uaktualniono widoczność wersji.'''",
'revdelete-failure'           => "'''Widoczność wersji nie może zostać uaktualniona – '''
$1",
'logdelete-success'           => "'''Zmieniono widoczność zdarzeń.'''",
'logdelete-failure'           => "'''Widoczność rejestru nie może zostać ustawiona – '''
$1",
'revdel-restore'              => 'zmień widoczność',
'revdel-restore-deleted'      => 'usunięte wersje',
'revdel-restore-visible'      => 'widoczne wersje',
'pagehist'                    => 'Historia edycji strony',
'deletedhist'                 => 'Usunięta historia edycji',
'revdelete-content'           => 'zawartość',
'revdelete-summary'           => 'opis zmian',
'revdelete-uname'             => 'nazwę użytkownika',
'revdelete-restricted'        => 'ustaw ograniczenia dla administratorów',
'revdelete-unrestricted'      => 'usuń ograniczenia dla administratorów',
'revdelete-hid'               => 'ukryj $1',
'revdelete-unhid'             => 'nie ukrywaj $1',
'revdelete-log-message'       => '$1 – $2 {{PLURAL:$2|wersja|wersje|wersji}}',
'logdelete-log-message'       => '$1 – $2 {{PLURAL:$2|zdarzenie|zdarzenia|zdarzeń}}',
'revdelete-hide-current'      => 'Wystąpił błąd przy ukrywaniu wersji datowanej na $2, $1. To jest najnowsza wersja strony, która nie może zostać ukryta.',
'revdelete-show-no-access'    => 'Wystąpił błąd przy próbie wyświetlenia elementu datowanego na $2, $1. Widoczność tego elementu została ograniczona – nie masz prawa dostępu do niego.',
'revdelete-modify-no-access'  => 'Wystąpił błąd przy próbie modyfikacji elementu datowanego na $2, $1. Widoczność tego elementu została ograniczona – nie masz prawa dostępu do niego.',
'revdelete-modify-missing'    => 'Wystąpił błąd przy próbie modyfikacji elementu o ID $1 – brakuje go w bazie danych!',
'revdelete-no-change'         => "'''Uwaga:''' element datowany na $2, $1 posiada już wskazane ustawienia widoczności.",
'revdelete-concurrent-change' => 'Wystąpił błąd przy próbie modyfikacji elementu datowanego na $2, $1. Prawdopodobnie w międzyczasie ktoś zdążył zmienić ustawienia widoczności tego elementu.
Proszę sprawdzić rejestr operacji.',
'revdelete-only-restricted'   => 'Nie można ukryć elementu z $2, $1 przed administratorami bez określenia jednej z pozostałych opcji ukrywania.',
'revdelete-reason-dropdown'   => '* Najczęstsze powody usunięcia
** Naruszenie praw autorskich
** Informacja naruszająca prywatność',
'revdelete-otherreason'       => 'Inny lub dodatkowy powód:',
'revdelete-reasonotherlist'   => 'Inny powód',
'revdelete-edit-reasonlist'   => 'Edycja listy powodów usunięcia pliku',
'revdelete-offender'          => 'Autor wersji',

# Suppression log
'suppressionlog'     => 'Rejestr utajniania',
'suppressionlogtext' => 'Poniżej znajduje się lista usunięć i blokad utajnionych przed administratorami.
Zobacz [[Special:IPBlockList|rejestr blokowania adresów IP]], jeśli chcesz sprawdzić aktualne zakazy i blokady.',

# Revision move
'moverevlogentry'              => 'przeniósł {{PLURAL:$3|jedną wersję|$3 wersje|$3 wersji}} z $1 do $2',
'revisionmove'                 => 'Przenieś wersje z „$1“',
'revmove-explain'              => 'Następujące zmiany zostaną przeniesione z $1 do wybranej strony docelowej. Jeśli cel nie istnieje, zostanie utworzony. W przeciwnym wypadku, wersje zostaną dołączone do historii strony.',
'revmove-legend'               => 'Ustaw stronę docelową i podsumowanie',
'revmove-submit'               => 'Przenieś wersje do wybranej strony',
'revisionmoveselectedversions' => 'Przenieś wybrane wersje',
'revmove-reasonfield'          => 'Powód',
'revmove-titlefield'           => 'Strona docelowa',
'revmove-badparam-title'       => 'Złe parametry',
'revmove-badparam'             => 'Żądanie zawiera niedozwolone lub niewystarczające parametry. Naciśnij „wstecz“ i spróbuj ponownie.',
'revmove-norevisions-title'    => 'Nieprawidłowa wersja docelowa',
'revmove-norevisions'          => 'Nie określono jednej lub kilku wersji, na których ma zostać wykonana funkcja, lub wybrana wersja nie istnieje.',
'revmove-nullmove-title'       => 'Zły tytuł',
'revmove-nullmove'             => 'Strona źródłowa i docelowa są takie same. Naciśnij „wstecz“ i wpisz nazwę strony różną od „$1“.',
'revmove-success-existing'     => '{{PLURAL:$1|Jedna wersja [[$2]] została przeniesiona|$1 wersje [[$2]] zostały przeniesione|$1 wersji [[$2]] zostało przeniesionych}} do historii edycji istniejącej strony [[$3]].',
'revmove-success-created'      => '{{PLURAL:$1|Jedna wersja [[$2]] została przeniesiona|$1 wersje [[$2]] zostały przeniesione|$1 wersji [[$2]] zostało przeniesionych}} do historii edycji nowo utworzonej strony [[$3]].',

# History merging
'mergehistory'                     => 'Scal historię zmian stron',
'mergehistory-header'              => 'Ta strona pozwala na scalenie historii zmian jednej strony z historią innej, nowszej strony.
Upewnij się, że zmiany będą zapewniać ciągłość historyczną edycji strony.',
'mergehistory-box'                 => 'Scal historię zmian dwóch stron:',
'mergehistory-from'                => 'Strona źródłowa:',
'mergehistory-into'                => 'Strona docelowa:',
'mergehistory-list'                => 'Historia zmian możliwa do scalenia',
'mergehistory-merge'               => 'Następujące zmiany strony [[:$1]] mogą zostać scalone z [[:$2]].
Oznacz w kolumnie kropeczką, która zmiana, łącznie z wcześniejszymi, ma zostać scalona.
Użycie linków nawigacyjnych kasuje wybór w kolumnie.',
'mergehistory-go'                  => 'Pokaż możliwe do scalenia zmiany',
'mergehistory-submit'              => 'Scal historię zmian',
'mergehistory-empty'               => 'Brak historii zmian do scalenia.',
'mergehistory-success'             => '$3 {{PLURAL:$3|zmiana|zmiany|zmian}} w [[:$1]] zostało scalonych z [[:$2]].',
'mergehistory-fail'                => 'Scalenie historii zmian jest niewykonalne. Zmień ustawienia parametrów.',
'mergehistory-no-source'           => 'Strona źródłowa $1 nie istnieje.',
'mergehistory-no-destination'      => 'Strona docelowa $1 nie istnieje.',
'mergehistory-invalid-source'      => 'Strona źródłowa musi mieć poprawną nazwę.',
'mergehistory-invalid-destination' => 'Strona docelowa musi mieć poprawną nazwę.',
'mergehistory-autocomment'         => 'Historia [[:$1]] scalona z [[:$2]]',
'mergehistory-comment'             => 'Historia [[:$1]] scalona z [[:$2]]: $3',
'mergehistory-same-destination'    => 'Strona źródłowa i docelowa nie mogą być takie same',
'mergehistory-reason'              => 'Powód',

# Merge log
'mergelog'           => 'Scalone',
'pagemerge-logentry' => 'scalił [[$1]] z [[$2]] (historia zmian aż do $3)',
'revertmerge'        => 'Rozdziel',
'mergelogpagetext'   => 'Poniżej znajduje się lista ostatnich scaleń historii zmian stron.',

# Diffs
'history-title'            => 'Historia edycji „$1”',
'difference'               => '(Różnice między wersjami)',
'difference-multipage'     => '(Różnica między stronami)',
'lineno'                   => 'Linia $1:',
'compareselectedversions'  => 'porównaj wybrane wersje',
'showhideselectedversions' => 'Pokaż lub ukryj zaznaczone wersje',
'editundo'                 => 'anuluj edycję',
'diff-multi'               => '(Nie pokazano $1 {{PLURAL:$1|wersji|wersji}} pomiędzy niniejszymi.)',

# Search results
'searchresults'                    => 'Wyniki wyszukiwania',
'searchresults-title'              => 'Wyniki wyszukiwania dla „$1”',
'searchresulttext'                 => 'Więcej informacji o przeszukiwaniu {{GRAMMAR:D.lp|{{SITENAME}}}} odnajdziesz na [[{{MediaWiki:Helppage}}|stronach pomocy]].',
'searchsubtitle'                   => "Wyniki dla zapytania '''[[:$1]]''' ([[Special:Prefixindex/$1|strony zaczynające się od „$1”]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|strony, które linkują do „$1”]])",
'searchsubtitleinvalid'            => "Dla zapytania '''$1'''",
'toomanymatches'                   => 'Zbyt wiele elementów pasujących do wzorca, spróbuj innego zapytania',
'titlematches'                     => 'Znaleziono w tytułach',
'notitlematches'                   => 'Nie znaleziono w tytułach',
'textmatches'                      => 'Znaleziono w treści stron',
'notextmatches'                    => 'Nie znaleziono w treści stron',
'prevn'                            => '{{PLURAL:$1|poprzedni|poprzednie $1}}',
'nextn'                            => '{{PLURAL:$1|następny|następne $1}}',
'prevn-title'                      => '{{PLURAL:$1|Poprzedni|Poprzednie}} $1 {{PLURAL:$1|wynik|wyniki|wyników}}',
'nextn-title'                      => '{{PLURAL:$1|Następny|Następne}} $1 {{PLURAL:$1|wynik|wyniki|wyników}}',
'shown-title'                      => 'Pokaż po $1 {{PLURAL:$1|wyniku|wyniki|wyników}} na stronę',
'viewprevnext'                     => 'Zobacz ($1 {{int:pipe-separator}} $2) ($3)',
'searchmenu-legend'                => 'Opcje wyszukiwania',
'searchmenu-exists'                => "* Strona '''[[$1]]'''",
'searchmenu-new'                   => "'''Utwórz stronę „[[:$1|$1]]” na tej wiki.'''",
'searchhelp-url'                   => 'Help:Spis treści',
'searchmenu-prefix'                => '[[Special:PrefixIndex/$1|Przeglądaj strony zaczynające się od tego przedrostka]]',
'searchprofile-articles'           => 'Strony',
'searchprofile-project'            => 'Strony pomocy i projektu',
'searchprofile-images'             => 'Multimedia',
'searchprofile-everything'         => 'Wszystko',
'searchprofile-advanced'           => 'Zaawansowane',
'searchprofile-articles-tooltip'   => 'Szukanie w przestrzeni nazw $1',
'searchprofile-project-tooltip'    => 'Szukanie w przestrzeniach nazw $1',
'searchprofile-images-tooltip'     => 'Szukanie plików',
'searchprofile-everything-tooltip' => 'Szukanie w całej zawartości (także strony dyskusji)',
'searchprofile-advanced-tooltip'   => 'Szukanie w wybranych przestrzeniach nazw',
'search-result-size'               => '$1 ({{PLURAL:$2|1 słowo|$2 słowa|$2 słów}})',
'search-result-category-size'      => '{{PLURAL:$1|1 element|$1 elementy|$1 elementów}} ({{PLURAL:$2|1 kategoria|$2 kategorie|$2 kategorii}}, {{PLURAL:$3|1 plik|$3 pliki|$3 plików}})',
'search-result-score'              => 'Trafność: $1%',
'search-redirect'                  => '(przekierowanie $1)',
'search-section'                   => '(sekcja $1)',
'search-suggest'                   => 'Czy chodziło Ci o: $1',
'search-interwiki-caption'         => 'Projekty siostrzane',
'search-interwiki-default'         => 'Wyniki dla $1:',
'search-interwiki-more'            => '(więcej)',
'search-mwsuggest-enabled'         => 'z dynamicznymi propozycjami',
'search-mwsuggest-disabled'        => 'bez dynamicznych propozycji',
'search-relatedarticle'            => 'Pokrewne',
'mwsuggest-disable'                => 'Wyłącz dynamiczne podpowiedzi',
'searcheverything-enable'          => 'Szukaj we wszystkich przestrzeniach nazw',
'searchrelated'                    => 'pokrewne',
'searchall'                        => 'wszystkie',
'showingresults'                   => "Poniżej znajduje się lista {{PLURAL:$1|z '''1''' wynikiem|'''$1''' wyników}}, rozpoczynając od wyniku numer '''$2'''.",
'showingresultsnum'                => "Poniżej znajduje się lista {{PLURAL:$3|z '''1''' wynikiem|'''$3''' wyników}}, rozpoczynając od wyniku numer '''$2'''.",
'showingresultsheader'             => "{{PLURAL:$5|Wynik '''$1''' z '''$3'''|Wyniki '''$1 – $2''' z '''$3'''}} dla '''$4'''",
'nonefound'                        => "'''Uwaga''': Domyślnie przeszukiwane są wyłącznie niektóre przestrzenie nazw. Spróbuj poprzedzić wyszukiwaną frazę przedrostkiem ''all:'', co spowoduje przeszukanie całej zawartości {{GRAMMAR:D.lp|{{SITENAME}}}} (włącznie ze stronami dyskusji, szablonami itp) lub spróbuj użyć jako przedrostka wybranej, jednej przestrzeni nazw.",
'search-nonefound'                 => 'Brak wyników spełniających kryteria podane w zapytaniu.',
'powersearch'                      => 'Szukaj',
'powersearch-legend'               => 'Wyszukiwanie zaawansowane',
'powersearch-ns'                   => 'Przeszukaj przestrzenie nazw:',
'powersearch-redir'                => 'Pokaż przekierowania',
'powersearch-field'                => 'Szukaj',
'powersearch-togglelabel'          => 'Zaznacz',
'powersearch-toggleall'            => 'wszystko',
'powersearch-togglenone'           => 'nic',
'search-external'                  => 'Wyszukiwanie zewnętrzne',
'searchdisabled'                   => 'Wyszukiwanie w {{GRAMMAR:MS.lp|{{SITENAME}}}} zostało wyłączone.
W międzyczasie możesz skorzystać z wyszukiwania Google.
Jednak informacje o treści {{GRAMMAR:D.lp|{{SITENAME}}}} mogą być w Google nieaktualne.',

# Quickbar
'qbsettings'               => 'Pasek szybkiego dostępu',
'qbsettings-none'          => 'Brak',
'qbsettings-fixedleft'     => 'Stały, z lewej',
'qbsettings-fixedright'    => 'Stały, z prawej',
'qbsettings-floatingleft'  => 'Unoszący się, z lewej',
'qbsettings-floatingright' => 'Unoszący się, z prawej',

# Preferences page
'preferences'                   => 'Preferencje',
'mypreferences'                 => 'Preferencje',
'prefs-edits'                   => 'Liczba edycji',
'prefsnologin'                  => 'Nie jesteś zalogowany',
'prefsnologintext'              => 'Musisz się <span class="plainlinks">[{{fullurl:{{#Special:UserLogin}}|returnto=$1}} zalogować]</span> przed zmianą swoich preferencji.',
'changepassword'                => 'Zmiana hasła',
'prefs-skin'                    => 'Skórka',
'skin-preview'                  => 'podgląd',
'prefs-math'                    => 'Wzory',
'datedefault'                   => 'Domyślny',
'prefs-datetime'                => 'Data i czas',
'prefs-personal'                => 'Dane użytkownika',
'prefs-rc'                      => 'Ostatnie zmiany',
'prefs-watchlist'               => 'Obserwowane',
'prefs-watchlist-days'          => 'Liczba dni widocznych na liście obserwowanych',
'prefs-watchlist-days-max'      => '(maksimum 7 dni)',
'prefs-watchlist-edits'         => 'Liczba edycji pokazywanych w rozszerzonej liście obserwowanych',
'prefs-watchlist-edits-max'     => '(maksymalna liczba: 1000)',
'prefs-watchlist-token'         => 'Identyfikator listy obserwowanych',
'prefs-misc'                    => 'Ustawienia różne',
'prefs-resetpass'               => 'Zmień hasło',
'prefs-email'                   => 'E‐mail',
'prefs-rendering'               => 'Wygląd',
'saveprefs'                     => 'Zapisz',
'resetprefs'                    => 'Cofnij niezapisane zmiany',
'restoreprefs'                  => 'Przywróć wszystkie domyślne preferencje',
'prefs-editing'                 => 'Edycja',
'prefs-edit-boxsize'            => 'Rozmiar okna edycji.',
'rows'                          => 'Wiersze',
'columns'                       => 'Kolumny',
'searchresultshead'             => 'Wyszukiwanie',
'resultsperpage'                => 'Liczba wyników na stronie',
'contextlines'                  => 'Pierwsze wiersze stron',
'contextchars'                  => 'Litery kontekstu w linijce',
'stub-threshold'                => 'Maksymalny (w bajtach) rozmiar strony oznaczanej jako <a href="#" class="stub">zalążek (stub)</a>',
'stub-threshold-disabled'       => 'Wyłączone',
'recentchangesdays'             => 'Liczba dni prezentowanych w ostatnich zmianach',
'recentchangesdays-max'         => '(maksymalnie $1 {{PLURAL:$1|dzień|dni}})',
'recentchangescount'            => 'Domyślna liczba wyświetlanych edycji',
'prefs-help-recentchangescount' => 'Uwzględnia ostatnie zmiany, historię stron i rejestry.',
'prefs-help-watchlist-token'    => 'Wypełnianie tego pola tajnym kluczem spowoduje wygenerowanie RSS dla Twojej listy obserwowanych.
Każdy znający ten klucz będzie mógł zobaczyć listę obserwowanych, należy więc wybrać bezpieczną wartość.
Wygenerowany losowo klucz, którego możesz użyć to $1',
'savedprefs'                    => 'Twoje preferencje zostały zapisane.',
'timezonelegend'                => 'Strefa czasowa',
'localtime'                     => 'Czas lokalny',
'timezoneuseserverdefault'      => 'Użyj domyślnego czasu serwera',
'timezoneuseoffset'             => 'Inna (określ różnicę czasu)',
'timezoneoffset'                => 'Różnica¹',
'servertime'                    => 'Czas serwera',
'guesstimezone'                 => 'Pobierz z przeglądarki',
'timezoneregion-africa'         => 'Afryka',
'timezoneregion-america'        => 'Ameryka',
'timezoneregion-antarctica'     => 'Antarktyda',
'timezoneregion-arctic'         => 'Arktyka',
'timezoneregion-asia'           => 'Azja',
'timezoneregion-atlantic'       => 'Ocean Atlantycki',
'timezoneregion-australia'      => 'Australia',
'timezoneregion-europe'         => 'Europa',
'timezoneregion-indian'         => 'Ocean Indyjski',
'timezoneregion-pacific'        => 'Ocean Spokojny',
'allowemail'                    => 'Zgadzam się, by inni użytkownicy mogli przesyłać do mnie e‐maile',
'prefs-searchoptions'           => 'Opcje wyszukiwania',
'prefs-namespaces'              => 'Przestrzenie nazw',
'defaultns'                     => 'Albo przeszukuj przestrzenie nazw:',
'default'                       => 'domyślnie',
'prefs-files'                   => 'Pliki',
'prefs-custom-css'              => 'własny CSS',
'prefs-custom-js'               => 'własny JavaScript',
'prefs-common-css-js'           => 'Wspólny CSS/JS dla wszystkich skórek',
'prefs-reset-intro'             => 'Na tej stronie można przywrócić domyślne ustawienia preferencji dla tej witryny.
Tej operacji nie można później cofnąć.',
'prefs-emailconfirm-label'      => 'Potwierdzenie adresu e‐mail –',
'prefs-textboxsize'             => 'Rozmiar okna edycji',
'youremail'                     => 'Twój adres e‐mail',
'username'                      => 'Nazwa użytkownika',
'uid'                           => 'ID użytkownika',
'prefs-memberingroups'          => 'Należy do {{PLURAL:$1|grupy|grup:}}',
'prefs-registration'            => 'Moment rejestracji',
'yourrealname'                  => 'Imię i nazwisko',
'yourlanguage'                  => 'Język interfejsu',
'yourvariant'                   => 'Wariant',
'yournick'                      => 'Twój podpis',
'prefs-help-signature'          => 'Wypowiedzi na stronach dyskusji powinny być podpisywane za pomocą „<nowiki>~~~~</nowiki>”, dzięki temu automatycznie wstawiany jest Twój podpis wraz z bieżącą datą.',
'badsig'                        => 'Nieprawidłowy podpis, sprawdź znaczniki HTML.',
'badsiglength'                  => 'Twój podpis jest zbyt długi.
Dopuszczalna długość to $1 {{PLURAL:$1|znak|znaki|znaków}}.',
'yourgender'                    => 'Płeć',
'gender-unknown'                => 'nie określono',
'gender-male'                   => 'mężczyzna',
'gender-female'                 => 'kobieta',
'prefs-help-gender'             => 'Podanie płci nie jest obowiązkowe. Jeśli zdecydujesz się ją określić, oprogramowanie dostosuje do niej interfejs. Informacja o Twojej płci będzie widoczna dla wszystkich.',
'email'                         => 'E‐mail',
'prefs-help-realname'           => 'Wpisanie imienia i nazwiska nie jest obowiązkowe.
Jeśli zdecydujesz się je podać, zostaną użyte, by udokumentować Twoje autorstwo.',
'prefs-help-email'              => "Podanie adresu e‐mail nie jest obowiązkowe, lecz pozwoli innym użytkownikom skontaktować się z Tobą poprzez odpowiedni formularz (bez ujawniania Twojego adresu). Będziesz także mógł poprosić o przysłanie Ci nowego hasła. '''Twój adres nie zostanie nikomu udostępniony.'''",
'prefs-help-email-required'     => 'Wymagany jest adres e‐mail.',
'prefs-info'                    => 'Podstawowe informacje',
'prefs-i18n'                    => 'Międzynarodowość',
'prefs-signature'               => 'Podpis',
'prefs-dateformat'              => 'Format daty',
'prefs-timeoffset'              => 'Różnica czasu',
'prefs-advancedediting'         => 'Zaawansowane',
'prefs-advancedrc'              => 'Zaawansowane',
'prefs-advancedrendering'       => 'Zaawansowane',
'prefs-advancedsearchoptions'   => 'Zaawansowane',
'prefs-advancedwatchlist'       => 'Zaawansowane',
'prefs-displayrc'               => 'Opcje wyświetlania',
'prefs-displaysearchoptions'    => 'Opcje wyświetlania',
'prefs-displaywatchlist'        => 'Opcje wyświetlania',
'prefs-diffs'                   => 'Zmiany',

# User rights
'userrights'                   => 'Zarządzaj uprawnieniami użytkowników',
'userrights-lookup-user'       => 'Zarządzaj grupami użytkownika',
'userrights-user-editname'     => 'Wprowadź nazwę użytkownika',
'editusergroup'                => 'Edytuj grupy użytkownika',
'editinguser'                  => "Zmiana uprawnień użytkownika '''[[User:$1|$1]]''' ([[User talk:$1|{{int:talkpagelinktext}}]]{{int:pipe-separator}}[[Special:Contributions/$1|{{int:contribslink}}]])",
'userrights-editusergroup'     => 'Edytuj grupy użytkownika',
'saveusergroups'               => 'Zapisz',
'userrights-groupsmember'      => 'Należy do:',
'userrights-groupsmember-auto' => 'Na stałe należy do:',
'userrights-groups-help'       => 'Możesz zmienić przynależność tego użytkownika do podanych grup.
* Zaznaczone pole oznacza przynależność użytkownika do danej grupy.
* Niezaznaczone pole oznacza, że użytkownik nie należy do danej grupy.
* Gwiazdka * informuje, że nie możesz usunąć z grupy po dodaniu do niej lub dodać po usunięciu z grupy.',
'userrights-reason'            => 'Powód',
'userrights-no-interwiki'      => 'Nie masz dostępu do edycji uprawnień na innych wiki.',
'userrights-nodatabase'        => 'Baza danych $1 nie istnieje lub nie jest lokalna.',
'userrights-nologin'           => 'Musisz [[Special:UserLogin|zalogować się]] na konto administratora, by nadawać uprawnienia użytkownikom.',
'userrights-notallowed'        => 'Nie masz dostępu do nadawania uprawnień użytkownikom.',
'userrights-changeable-col'    => 'Grupy, które możesz wybrać',
'userrights-unchangeable-col'  => 'Grupy, których nie możesz wybrać',

# Groups
'group'               => 'Grupa',
'group-user'          => 'Użytkownicy',
'group-autoconfirmed' => 'Automatycznie zatwierdzeni użytkownicy',
'group-bot'           => 'Boty',
'group-sysop'         => 'Administratorzy',
'group-bureaucrat'    => 'Biurokraci',
'group-suppress'      => 'Rewizorzy',
'group-all'           => '(wszyscy)',

'group-user-member'          => 'użytkownik',
'group-autoconfirmed-member' => 'automatycznie zatwierdzony użytkownik',
'group-bot-member'           => 'bot',
'group-sysop-member'         => 'administrator',
'group-bureaucrat-member'    => 'biurokrata',
'group-suppress-member'      => 'rewizor',

'grouppage-user'          => '{{ns:project}}:Użytkownicy',
'grouppage-autoconfirmed' => '{{ns:project}}:Automatycznie zatwierdzani użytkownicy',
'grouppage-bot'           => '{{ns:project}}:Boty',
'grouppage-sysop'         => '{{ns:project}}:Administratorzy',
'grouppage-bureaucrat'    => '{{ns:project}}:Biurokraci',
'grouppage-suppress'      => '{{ns:project}}:Rewizorzy',

# Rights
'right-read'                  => 'Czytanie treści stron',
'right-edit'                  => 'Edycja stron',
'right-createpage'            => 'Tworzenie stron (nie będących stronami dyskusji)',
'right-createtalk'            => 'Tworzenie stron dyskusji',
'right-createaccount'         => 'Tworzenie kont użytkowników',
'right-minoredit'             => 'Oznaczanie edycji jako drobnych',
'right-move'                  => 'Przenoszenie stron',
'right-move-subpages'         => 'Przenoszenie stron razem z ich podstronami',
'right-move-rootuserpages'    => 'Przenoszenie stron użytkowników',
'right-movefile'              => 'Przenoszenie plików',
'right-suppressredirect'      => 'Przenoszenie stron bez tworzenia przekierowania w miejscu starej nazwy',
'right-upload'                => 'Przesyłanie plików na serwer',
'right-reupload'              => 'Nadpisywanie istniejącego pliku',
'right-reupload-own'          => 'Nadpisywanie istniejącego, wcześniej przesłanego pliku',
'right-reupload-shared'       => 'Lokalne nadpisywanie pliku istniejącego we współdzielonych zasobach',
'right-upload_by_url'         => 'Przesyłanie plików z adresu URL',
'right-purge'                 => 'Czyszczenie pamięci podręcznej stron bez pytania o potwierdzenie',
'right-autoconfirmed'         => 'Edycja stron częściowo zabezpieczonych',
'right-bot'                   => 'Oznaczanie edycji jako wykonanych automatycznie',
'right-nominornewtalk'        => 'Drobne zmiany na stronach dyskusji użytkowników nie włączają powiadomienia o nowej wiadomości',
'right-apihighlimits'         => 'Zwiększony limit w zapytaniach, wykonywanych poprzez interfejs API',
'right-writeapi'              => 'Zapis poprzez interfejs API',
'right-delete'                => 'Usuwanie stron',
'right-bigdelete'             => 'Usuwanie stron z długą historią edycji',
'right-deleterevision'        => 'Usuwanie i odtwarzanie określonej wersji strony',
'right-deletedhistory'        => 'Podgląd usuniętych wersji, bez przypisanego im tekstu',
'right-deletedtext'           => 'Podgląd usuniętego tekstu i zmian pomiędzy usuniętymi wersjami',
'right-browsearchive'         => 'Przeszukiwanie usuniętych stron',
'right-undelete'              => 'Odtwarzanie usuniętych stron',
'right-suppressrevision'      => 'Podgląd i odtwarzanie wersji ukrytych przed Administratorami',
'right-suppressionlog'        => 'Podgląd rejestru ukrywania',
'right-block'                 => 'Blokowanie użytkownikom możliwości edycji',
'right-blockemail'            => 'Blokowanie wysyłania wiadomości przez użytkownika',
'right-hideuser'              => 'Blokowanie użytkownika, niewidoczne publicznie',
'right-ipblock-exempt'        => 'Obejście blokad, automatycznych blokad i blokad zakresów, adresów IP',
'right-proxyunbannable'       => 'Obejście automatycznych blokad proxy',
'right-unblockself'           => 'Odblokowanie samego siebie',
'right-protect'               => 'Zmiana poziomu zabezpieczenia i dostęp do edycji zabezpieczonych stron',
'right-editprotected'         => 'Dostęp do edycji zabezpieczonych stron (bez zabezpieczenia dziedziczonego)',
'right-editinterface'         => 'Edycja interfejsu użytkownika',
'right-editusercssjs'         => 'Edycja plików CSS i JS innych użytkowników',
'right-editusercss'           => 'Edycja plików CSS innych użytkowników',
'right-edituserjs'            => 'Edycja plików JS innych użytkowników',
'right-rollback'              => 'Szybkie cofnięcie edycji użytkownika, który jako ostatni edytował jakąś stronę',
'right-markbotedits'          => 'Oznaczanie rewertu jako edycji bota',
'right-noratelimit'           => 'Brak ograniczeń przepustowości',
'right-import'                => 'Import stron z innych wiki',
'right-importupload'          => 'Import stron poprzez przesłanie pliku',
'right-patrol'                => 'Oznaczanie edycji jako „sprawdzonych”',
'right-autopatrol'            => 'Własne edycje automatycznie oznaczane jako „sprawdzone”',
'right-patrolmarks'           => 'Podgląd znaczników patrolowania ostatnich zmian – oznaczania jako „sprawdzone”',
'right-unwatchedpages'        => 'Podgląd listy stron nieobserwowanych',
'right-trackback'             => 'Wysyłanie trackback',
'right-mergehistory'          => 'Łączenie historii edycji stron',
'right-userrights'            => 'Edycja uprawnień wszystkich użytkowników',
'right-userrights-interwiki'  => 'Edycja uprawnień użytkowników innych witryn wiki',
'right-siteadmin'             => 'Blokowanie i odblokowywanie bazy danych',
'right-reset-passwords'       => 'Zerowanie haseł innych użytkowników',
'right-override-export-depth' => 'Eksport stron wraz z linkowanymi do głębokości 5 linków',
'right-sendemail'             => 'Wysyłanie e‐maili do innych użytkowników',
'right-revisionmove'          => 'Przenoszenie wersji',
'right-selenium'              => 'Uruchamianie testów Selenium',

# User rights log
'rightslog'      => 'Uprawnienia',
'rightslogtext'  => 'Rejestr zmian uprawnień użytkowników.',
'rightslogentry' => 'zmienił przynależność $1 do grup ($2 → $3)',
'rightsnone'     => 'brak',

# Associated actions - in the sentence "You do not have permission to X"
'action-read'                 => 'przeglądania tej strony',
'action-edit'                 => 'edytowania tej strony',
'action-createpage'           => 'tworzenia stron',
'action-createtalk'           => 'tworzenia stron dyskusji',
'action-createaccount'        => 'utworzenia tego konta użytkownika',
'action-minoredit'            => 'do oznaczenia tej edycji jako drobna zmiana',
'action-move'                 => 'przeniesienia tej strony',
'action-move-subpages'        => 'przeniesienia tej strony oraz jej podstron',
'action-move-rootuserpages'   => 'przenoszenia stron użytkowników (bez podstron)',
'action-movefile'             => 'przeniesienia tego pliku',
'action-upload'               => 'przesłania tego pliku',
'action-reupload'             => 'nadpisania tego pliku',
'action-reupload-shared'      => 'nadpisania tego pliku we wspólnym repozytorium',
'action-upload_by_url'        => 'przesłania tego pliku z adresu URL',
'action-writeapi'             => 'zapisu poprzez interfejs API',
'action-delete'               => 'usunięcia tej strony',
'action-deleterevision'       => 'usunięcia tej wersji',
'action-deletedhistory'       => 'podglądu historii usunięć tej strony',
'action-browsearchive'        => 'przeszukiwania usuniętych stron',
'action-undelete'             => 'odtworzenia tej strony',
'action-suppressrevision'     => 'podglądu i odtworzenia tej wersji ukrytej',
'action-suppressionlog'       => 'podglądu rejestru ukrywania',
'action-block'                => 'zablokowania temu użytkownikowi możliwości edycji',
'action-protect'              => 'zmiany poziomu zabezpieczenia tej strony',
'action-import'               => 'importu tej strony z innej wiki',
'action-importupload'         => 'importu tej strony poprzez przesłanie pliku',
'action-patrol'               => 'oznaczenia edycji jako „sprawdzonej”',
'action-autopatrol'           => 'oznaczenia własnej edycji jako „sprawdzonej”',
'action-unwatchedpages'       => 'podglądu listy nieobserwowanych stron',
'action-trackback'            => 'wysyłania trackbacka',
'action-mergehistory'         => 'łączenia historii edycji tej strony',
'action-userrights'           => 'edytowania uprawnień wszystkich użytkowników',
'action-userrights-interwiki' => 'edytowania uprawnień użytkowników na innych witrynach wiki',
'action-siteadmin'            => 'blokowania i odblokowywania bazy danych',
'action-revisionmove'         => 'przenieś wersje',

# Recent changes
'nchanges'                          => '$1 {{PLURAL:$1|zmiana|zmiany|zmian}}',
'recentchanges'                     => 'Ostatnie zmiany',
'recentchanges-legend'              => 'Opcje ostatnich zmian',
'recentchangestext'                 => 'Ta strona przedstawia historię ostatnich zmian w tej wiki.',
'recentchanges-feed-description'    => 'Obserwuj najświeższe zmiany w tej wiki.',
'recentchanges-label-legend'        => 'Legenda: $1.',
'recentchanges-legend-newpage'      => '$1 – nowa strona',
'recentchanges-label-newpage'       => 'W tej edycji utworzono nową stronę',
'recentchanges-legend-minor'        => '$1 – drobna zmiana',
'recentchanges-label-minor'         => 'W tej edycji dokonano drobnych zmian',
'recentchanges-legend-bot'          => '$1 – edycja bota',
'recentchanges-label-bot'           => 'Ta edycja została wykonana przez bota',
'recentchanges-legend-unpatrolled'  => '$1 – niesprawdzona edycja',
'recentchanges-label-unpatrolled'   => 'Ta edycja nie została jeszcze sprawdzona',
'rcnote'                            => "Poniżej {{PLURAL:$1|znajduje się '''1''' ostatnia zmiana wykonana|znajdują się ostatnie '''$1''' zmiany wykonane|znajduje się ostatnich '''$1''' zmian wykonanych}} w ciągu {{PLURAL:$2|ostatniego dnia|ostatnich '''$2''' dni}}, licząc od $5 dnia $4.",
'rcnotefrom'                        => "Poniżej pokazano zmiany wykonane po '''$2''' (nie więcej niż '''$1''' pozycji).",
'rclistfrom'                        => 'Pokaż nowe zmiany od $1',
'rcshowhideminor'                   => '$1 drobne zmiany',
'rcshowhidebots'                    => '$1 boty',
'rcshowhideliu'                     => '$1 zalogowanych',
'rcshowhideanons'                   => '$1 anonimowych',
'rcshowhidepatr'                    => '$1 sprawdzone',
'rcshowhidemine'                    => '$1 moje edycje',
'rclinks'                           => 'Pokaż ostatnie $1 zmian w ciągu ostatnich $2 dni.<br />$3',
'diff'                              => 'różn.',
'hist'                              => 'hist.',
'hide'                              => 'Ukryj',
'show'                              => 'Pokaż',
'minoreditletter'                   => 'm',
'newpageletter'                     => 'N',
'boteditletter'                     => 'b',
'number_of_watching_users_pageview' => '[$1 {{PLURAL:$1|obserwujący użytkownik|obserwujących użytkowników}}]',
'rc_categories'                     => 'Ogranicz do kategorii (oddzielaj za pomocą „|”)',
'rc_categories_any'                 => 'Wszystkie',
'newsectionsummary'                 => '/* $1 */ nowa sekcja',
'rc-enhanced-expand'                => 'Pokaż szczegóły (wymagana JavaScript)',
'rc-enhanced-hide'                  => 'Ukryj szczegóły',

# Recent changes linked
'recentchangeslinked'          => 'Zmiany w dolinkowanych',
'recentchangeslinked-feed'     => 'Zmiany w dolinkowanych',
'recentchangeslinked-toolbox'  => 'Zmiany w dolinkowanych',
'recentchangeslinked-title'    => 'Zmiany w linkowanych z „$1”',
'recentchangeslinked-noresult' => 'Nie było żadnych zmian na (zależnie od ustawień) linkowanych lub linkujących stronach w wybranym okresie.',
'recentchangeslinked-summary'  => "Poniżej znajduje się lista ostatnich zmian na stronach linkowanych z podanej strony (lub we wszystkich stronach należących do podanej kategorii).
Strony z [[Special:Watchlist|listy obserwowanych]] są '''wytłuszczone'''.",
'recentchangeslinked-page'     => 'Tytuł strony',
'recentchangeslinked-to'       => 'Pokaż zmiany nie na stronach linkowanych, a na stronach linkujących do podanej strony',

# Upload
'upload'                      => 'Prześlij plik',
'uploadbtn'                   => 'Prześlij plik',
'reuploaddesc'                => 'Przerwij wysyłanie i wróć do formularza wysyłki',
'upload-tryagain'             => 'Zapisz zmieniony opis pliku',
'uploadnologin'               => 'Nie jesteś zalogowany',
'uploadnologintext'           => 'Musisz się [[Special:UserLogin|zalogować]] przed przesłaniem plików.',
'upload_directory_missing'    => 'Katalog dla przesyłanych plików ($1) nie istnieje i nie może zostać utworzony przez serwer WWW.',
'upload_directory_read_only'  => 'Serwer nie może zapisywać do katalogu ($1) przeznaczonego na przesyłane pliki.',
'uploaderror'                 => 'Błąd wysyłania',
'upload-recreate-warning'     => "'''Uwaga – plik o tej nazwie został wcześniej usunięty lub przrniesiony.'''

Poniżej znajduje się rejestr usunięć i zmian nazwy tej strony:",
'uploadtext'                  => "Użyj poniższego formularza do przesłania plików.
Jeśli chcesz przejrzeć lub przeszukać dotychczas przesłane pliki, przejdź do [[Special:FileList|listy plików]]. Każde przesłanie zostaje odnotowane w [[Special:Log/upload|rejestrze przesyłanych plików]], a usunięcie w [[Special:Log/delete|rejestrze usuniętych]].

Plik pojawi się na stronie, jeśli użyjesz linku według jednego z następujących wzorów:
* '''<tt><nowiki>[[</nowiki>{{ns:file}}<nowiki>:Plik.jpg]]</nowiki></tt>''' pokaże plik w pełnej postaci
* '''<tt><nowiki>[[</nowiki>{{ns:file}}<nowiki>:Plik.png|200px|thumb|left|podpis grafiki]]</nowiki></tt>''' pokaże szeroką na 200 pikseli miniaturkę umieszczoną przy lewym marginesie, otoczoną ramką, z podpisem „podpis grafiki”
* '''<tt><nowiki>[[</nowiki>{{ns:media}}<nowiki>:Plik.ogg]]</nowiki></tt>''' utworzy bezpośredni link do pliku bez wyświetlania samego pliku",
'upload-permitted'            => 'Dopuszczalne formaty plików: $1.',
'upload-preferred'            => 'Zalecane formaty plików: $1.',
'upload-prohibited'           => 'Zabronione formaty plików: $1.',
'uploadlog'                   => 'rejestr przesyłania plików',
'uploadlogpage'               => 'Przesłane',
'uploadlogpagetext'           => 'Lista ostatnio przesłanych plików.
Przejdź na stronę [[Special:NewFiles|galerii nowych plików]], by zobaczyć pliki jako miniaturki.',
'filename'                    => 'Nazwa pliku',
'filedesc'                    => 'Opis',
'fileuploadsummary'           => 'Opis',
'filereuploadsummary'         => 'Zmiany w pliku:',
'filestatus'                  => 'Status prawny',
'filesource'                  => 'Źródło',
'uploadedfiles'               => 'Przesłane pliki',
'ignorewarning'               => 'Zignoruj ostrzeżenia i wymuś zapisanie pliku.',
'ignorewarnings'              => 'Ignoruj wszystkie ostrzeżenia',
'minlength1'                  => 'Nazwa pliku musi składać się co najmniej z jednej litery.',
'illegalfilename'             => 'Nazwa pliku „$1” zawiera znaki niedozwolone w tytułach stron.
Zmień nazwę pliku i prześlij go ponownie.',
'badfilename'                 => 'Nazwa pliku została zmieniona na „$1”.',
'filetype-mime-mismatch'      => 'Rozszerzenie pliku nie pasuje do typu MIME.',
'filetype-badmime'            => 'Przesyłanie plików z typem MIME „$1” jest niedozwolone.',
'filetype-bad-ie-mime'        => 'Nie można załadować tego pliku, ponieważ Internet Explorer wykryje go jako „$1”, a taki typ pliku jest zabronioniony jako potencjalnie niebezpieczny.',
'filetype-unwanted-type'      => "'''„.$1”''' nie jest zalecanym typem pliku. Pożądane są pliki w {{PLURAL:$3|formacie|formatach}} $2.",
'filetype-banned-type'        => "'''„.$1”''' jest niedozwolonym typem pliku. Dopuszczalne są pliki w {{PLURAL:$3|formacie|formatach}} $2.",
'filetype-missing'            => 'Plik nie ma rozszerzenia (np. „.jpg”).',
'empty-file'                  => 'Przesłany przez Ciebie plik jest pusty.',
'file-too-large'              => 'Przesłany przez Ciebie plik jest zbyt duży.',
'filename-tooshort'           => 'Nazwa pliku jest zbyt krótka.',
'filetype-banned'             => 'Zabroniony format pliku.',
'verification-error'          => 'Plik nie przeszedł pozytywnie weryfikacji.',
'hookaborted'                 => 'Zmiana, którą próbowałeś wykonać została przerwana przez hak rozszerzenia.',
'illegal-filename'            => 'Niedopuszczalna nazwa pliku.',
'overwrite'                   => 'Nadpisanie istniejącego pliku nie jest dopuszczalne.',
'unknown-error'               => 'Wystąpił nieznany błąd.',
'tmp-create-error'            => 'Błąd utworzenia pliku tymczasowego.',
'tmp-write-error'             => 'Błąd zapisu pliku tymczasowego.',
'large-file'                  => 'Zalecane jest aby rozmiar pliku nie był większy niż {{PLURAL:$1|1 bajt|$1 bajty|$1 bajtów}}.
Plik ma rozmiar {{PLURAL:$2|1 bajt|$2 bajty|$2 bajtów}}.',
'largefileserver'             => 'Plik jest większy niż maksymalny dozwolony rozmiar.',
'emptyfile'                   => 'Przesłany plik wydaje się być pusty. Może być to spowodowane literówką w nazwie pliku.
Sprawdź, czy nazwa jest prawidłowa.',
'fileexists'                  => "Plik o takiej nazwie już istnieje.
Sprawdź '''<tt>[[:$1]]</tt>''', jeśli nie jesteś pewien czy chcesz go zastąpić.
[[$1|thumb]]",
'filepageexists'              => "Istnieje już strona opisu tego pliku, została utworzona '''<tt>[[:$1]]</tt>''', ale brak jest pliku o tej nazwie.
Informacje, które wprowadzasz o przesyłanym pliku, nie pojawią się na jego stronie opisu.
Jeśli chcesz, by się tam pojawiły, musisz później, ręcznie przeredagować stronę opisu.
[[$1|thumb]]",
'fileexists-extension'        => "Plik o podobnej nazwie już istnieje: [[$2|thumb]]
* Nazwa przesyłanego pliku: '''<tt>[[:$1]]</tt>'''
* Nazwa istniejącego pliku: '''<tt>[[:$2]]</tt>'''
Wybierz inną nazwę.",
'fileexists-thumbnail-yes'    => "Plik wydaje się być pomniejszoną grafiką ''(miniaturką)''. [[$1|thumb]]
Sprawdź plik '''<tt>[[:$1]]</tt>'''.
Jeśli wybrany plik jest tą samą grafiką co ta w rozmiarze pierwotnym, nie musisz przesyłać dodatkowej miniaturki.",
'file-thumbnail-no'           => "Nazwa pliku zaczyna się od '''<tt>$1</tt>'''.
Wydaje się, że jest to pomniejszona grafika ''(miniaturka)''.
Jeśli posiadasz tę grafikę w pełnym rozmiarze – prześlij ją. Jeśli chcesz wysłać tę – zmień nazwę przesyłanego obecnie pliku.",
'fileexists-forbidden'        => 'Plik o tej nazwie już istnieje i nie może zostać nadpisany.
Jeśli chcesz przesłać plik cofnij się i prześlij go pod inną nazwą. [[File:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => 'Plik o tej nazwie już istnieje we współdzielonym repozytorium plików.
Cofnij się i załaduj plik pod inną nazwą. [[File:$1|thumb|center|$1]]',
'file-exists-duplicate'       => 'Ten plik jest kopią {{PLURAL:$1|pliku|następujących plików:}}',
'file-deleted-duplicate'      => 'Identyczny do tego plik ([[$1]]) został wcześniej usunięty. Sprawdź historię usunięć tamtego pliku zanim prześlesz go ponownie.',
'uploadwarning'               => 'Ostrzeżenie o przesyłaniu',
'uploadwarning-text'          => 'Zmień poniższy opis pliku i spróbuj ponownie.',
'savefile'                    => 'Zapisz plik',
'uploadedimage'               => 'przesłał [[$1]]',
'overwroteimage'              => 'przesłano nową wersję pliku „[[$1]]“',
'uploaddisabled'              => 'Przesyłanie plików wyłączone',
'copyuploaddisabled'          => 'Przesyłanie poprzez podanie adres URL jest wyłączone.',
'uploadfromurl-queued'        => 'Żądanie przesłania pliku zostało dołączone do kolejki.',
'uploaddisabledtext'          => 'Możliwość przesyłania plików została wyłączona.',
'php-uploaddisabledtext'      => 'Przesyłanie plików PHP zostało zablokowane. Sprawdź ustawienie „file_uploads”.',
'uploadscripted'              => 'Plik zawiera kod HTML lub skrypt, który może zostać błędnie zinterpretowany przez przeglądarkę internetową.',
'uploadvirus'                 => 'W pliku jest wirus! Szczegóły: $1',
'upload-source'               => 'Plik źródłowy',
'sourcefilename'              => 'Nazwa pierwotna',
'sourceurl'                   => 'Źródłowy adres URL',
'destfilename'                => 'Nazwa docelowa',
'upload-maxfilesize'          => 'Wielkość pliku ograniczona jest do $1',
'upload-description'          => 'Opis pliku',
'upload-options'              => 'Opcje przesyłania',
'watchthisupload'             => 'Obserwuj ten plik',
'filewasdeleted'              => 'Plik o tej nazwie istniał, ale został usunięty.
Zanim załadujesz go ponownie, sprawdź $1.',
'upload-wasdeleted'           => "'''Uwaga! Przesyłasz plik, który został usunięty.'''

Zastanów się, czy powinno się przesyłać ten plik ponownie.
Poniżej znajduje się historia usunięć tego pliku.",
'filename-bad-prefix'         => "Nazwa pliku, który przesyłasz, zaczyna się od '''„$1”'''. Jest to nazwa zazwyczaj przypisywana automatycznie przez cyfrowe aparaty fotograficzne, która nie informuje o zawartości pliku.
Zmień nazwę pliku na bardziej opisową.",
'filename-prefix-blacklist'   => ' #<!-- nie modyfikuj tej linii --> <pre>
# Składnia jest następująca:
#  * Wszystko od znaku "#" do końca linii uznawane jest za komentarz
#  * Każda niepusta linia zawiera początek nazwy pliku domyślnie wykorzystywany przez aparaty cyfrowe
CIMG # Casio
DSC_ # Nikon
DSCF # Fuji
DSCN # Nikon
DUW # niektóre telefony komórkowe
IMG # ogólny
JD # Jenoptik
MGP # Pentax
PICT # wiele różnych
  #</pre> <!-- nie modyfikuj tej linii -->',
'upload-success-subj'         => 'Przesłanie pliku powiodło się',
'upload-success-msg'          => 'Przesłano plik z [$2]. Jest dostępny tutaj – [[:{{ns:file}}:$1]]',
'upload-failure-subj'         => 'Problem z przesłaniem pliku',
'upload-failure-msg'          => 'Wystąpił problem przy przesyłaniu Twojego pliku:

$1',
'upload-warning-subj'         => 'Ostrzeżenie dotyczące przesyłania',
'upload-warning-msg'          => 'Wystąpił problem z przesyłaniem z [$2]. Możesz wrócić do [[Special:Upload/stash/$1|formularza przesłania]] w celu rozwiązania tego problemu.',

'upload-proto-error'        => 'Nieprawidłowy protokół',
'upload-proto-error-text'   => 'Zdalne przesyłanie plików wymaga podania adresu URL zaczynającego się od <code>http://</code> lub <code>ftp://</code>.',
'upload-file-error'         => 'Błąd wewnętrzny',
'upload-file-error-text'    => 'Wystąpił błąd wewnętrzny podczas próby utworzenia tymczasowego pliku na serwerze.
Skontaktuj się z [[Special:ListUsers/sysop|administratorem systemu]].',
'upload-misc-error'         => 'Nieznany błąd przesyłania',
'upload-misc-error-text'    => 'Wystąpił nieznany błąd podczas przesyłania.
Sprawdź, czy podany adres URL jest poprawny i dostępny, a następnie spróbuj ponownie.
Jeśli problem będzie się powtarzał, skontaktuj się z [[Special:ListUsers/sysop|administratorem systemu]].',
'upload-too-many-redirects' => 'URL zawiera zbyt wiele przekierowań',
'upload-unknown-size'       => 'Nieznany rozmiar',
'upload-http-error'         => 'Wystąpił błąd protokołu HTTP – $1',

# img_auth script messages
'img-auth-accessdenied' => 'Odmowa dostępu',
'img-auth-nopathinfo'   => 'Brak PATH_INFO.
Serwer nie został skonfigurowany, tak aby przekazywał tę informację.
Możliwe, że jest oparty na CGI i nie może obsługiwać img_auth.
Zobacz http://www.mediawiki.org/wiki/Manual:Image_Authorization.',
'img-auth-notindir'     => 'Żądana ścieżka nie jest w obrębie katalogu skonfigurowanego do przesyłania plików.',
'img-auth-badtitle'     => 'Nie można wygenerować prawidłowego tytuł z „$1”.',
'img-auth-nologinnWL'   => 'Nie jesteś zalogowany, a „$1” nie jest na białej liście.',
'img-auth-nofile'       => 'Brak pliku „$1”.',
'img-auth-isdir'        => 'Próbujesz uzyskać dostęp do katalogu „$1”.
Dozwolony jest wyłącznie dostęp do plików.',
'img-auth-streaming'    => 'Strumieniowanie „$1”.',
'img-auth-public'       => 'Funkcja img_auth.php służy do pobierania plików z prywatnej wiki.
Ponieważ ta wiki została skonfigurowana jako publiczna dla zapewnienia optymalnego bezpieczeństwa img_auth.php została wyłączona.',
'img-auth-noread'       => 'Użytkownik nie ma dostępu do odczytu „$1”.',

# HTTP errors
'http-invalid-url'      => 'Niepoprawny adres URL: $1',
'http-invalid-scheme'   => 'Adresy „$1“ nie są obsługiwane.',
'http-request-error'    => 'Nieudane żądanie HTTP ze względu na nieznany błąd.',
'http-read-error'       => 'Błąd odczytu HTTP.',
'http-timed-out'        => 'Przekroczony czas żądania HTTP.',
'http-curl-error'       => 'Błąd pobierania z adresu $1',
'http-host-unreachable' => 'Adres jest nieosiągalny.',
'http-bad-status'       => 'Wystąpił problem z realizacją żądania HTTP $1 $2',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6'       => 'Adres URL jest nieosiągalny',
'upload-curl-error6-text'  => 'Podany adres URL jest nieosiągalny. Upewnij się, czy podany adres URL jest prawidłowy i czy dana strona jest dostępna.',
'upload-curl-error28'      => 'Upłynął limit czasu odpowiedzi',
'upload-curl-error28-text' => 'Zbyt długi czas odpowiedzi serwera.
Sprawdź, czy strona działa, odczekaj kilka minut i spróbuj ponownie.
Możesz także spróbować w czasie mniejszego obciążenia serwera.',

'license'            => 'Licencja',
'license-header'     => 'Licencja',
'nolicense'          => 'Nie wybrano',
'license-nopreview'  => '(Podgląd niedostępny)',
'upload_source_url'  => ' (poprawny, publicznie dostępny adres URL)',
'upload_source_file' => ' (plik na twoim komputerze)',

# Special:ListFiles
'listfiles-summary'     => 'Na tej stronie specjalnej prezentowane są wszystkie pliki przesłane na serwer.
Domyślnie na górze listy umieszczane są ostatnio przesłane pliki.
Kliknięcie w nagłówek kolumny zmienia sposób sortowania.',
'listfiles_search_for'  => 'Szukaj pliku o nazwie',
'imgfile'               => 'plik',
'listfiles'             => 'Lista plików',
'listfiles_date'        => 'Data',
'listfiles_name'        => 'Nazwa',
'listfiles_user'        => 'Użytkownik',
'listfiles_size'        => 'Wielkość',
'listfiles_description' => 'Opis',
'listfiles_count'       => 'Wersje',

# File description page
'file-anchor-link'          => 'Plik',
'filehist'                  => 'Historia pliku',
'filehist-help'             => 'Kliknij na datę/czas, aby zobaczyć, jak plik wyglądał w tym czasie.',
'filehist-deleteall'        => 'usuń wszystkie',
'filehist-deleteone'        => 'usuń',
'filehist-revert'           => 'przywróć',
'filehist-current'          => 'aktualny',
'filehist-datetime'         => 'Data i czas',
'filehist-thumb'            => 'Miniatura',
'filehist-thumbtext'        => 'Miniatura wersji z $1',
'filehist-nothumb'          => 'Brak miniatury',
'filehist-user'             => 'Użytkownik',
'filehist-dimensions'       => 'Wymiary',
'filehist-filesize'         => 'Rozmiar pliku',
'filehist-comment'          => 'Opis',
'filehist-missing'          => 'Brak pliku',
'imagelinks'                => 'Odnośniki do pliku',
'linkstoimage'              => '{{PLURAL:$1|Poniższa strona odwołuje|Następujące strony odwołują}} się do tego pliku:',
'linkstoimage-more'         => 'Więcej niż $1 {{PLURAL:$1|strona linkuje|strony linkują|stron linkuje}} do tego pliku.
Poniższa lista pokazuje jedynie {{PLURAL:$1|pierwszy link|pierwsze $1 linki|pierwszych $1 linków}} do tego pliku.
Dostępna jest też [[Special:WhatLinksHere/$2|pełna lista]].',
'nolinkstoimage'            => 'Żadna strona nie odwołuje się do tego pliku.',
'morelinkstoimage'          => 'Pokaż [[Special:WhatLinksHere/$1|więcej odnośników]] do tego pliku.',
'redirectstofile'           => '{{PLURAL:$1|Następujący plik przekierowuje|Następujące pliki przekierowują}} do tego pliku:',
'duplicatesoffile'          => '{{PLURAL:$1|Następujący plik jest kopią|Następujące pliki są kopiami}} pliku ([[Special:FileDuplicateSearch/$2|więcej informacji]]):',
'sharedupload'              => 'Ten plik znajduje się na $1 i może być używany w innych projektach.',
'sharedupload-desc-there'   => 'Ten plik znajduje się na $1 i może być używany w innych projektach.
Więcej informacji odnajdziesz na [$2 stronie opisu pliku].',
'sharedupload-desc-here'    => 'Ten plik znajduje się na $1 i może być używany w innych projektach.
Poniżej znajdują się informacje ze [$2 strony opisu] tego pliku.',
'filepage-nofile'           => 'Plik tej nazwie nie istnieje.',
'filepage-nofile-link'      => 'Plik o tej nazwie nie istnieje, ale możesz go [$1 przesłać].',
'uploadnewversion-linktext' => 'Załaduj nowszą wersję tego pliku',
'shared-repo-from'          => 'z $1',
'shared-repo'               => 'współdzielone zasoby',

# File reversion
'filerevert'                => 'Przywracanie $1',
'filerevert-legend'         => 'Przywracanie poprzedniej wersji pliku',
'filerevert-intro'          => "Zamierzasz przywrócić '''[[Media:$1|$1]]''' do [wersji $4 z $3, $2].",
'filerevert-comment'        => 'Powód',
'filerevert-defaultcomment' => 'Przywrócono wersję z $2, $1',
'filerevert-submit'         => 'Przywróć',
'filerevert-success'        => "Plik '''[[Media:$1|$1]]''' został cofnięty do [wersji $4 z $3, $2].",
'filerevert-badversion'     => 'Brak poprzedniej lokalnej wersji tego pliku z podaną datą.',

# File deletion
'filedelete'                  => 'Usuwanie „$1”',
'filedelete-legend'           => 'Usuń plik',
'filedelete-intro'            => "Chcesz usunąć plik '''[[Media:$1|$1]]''' razem z całą jego historią.",
'filedelete-intro-old'        => "Usuwasz wersję pliku '''[[Media:$1|$1]]''' z datą [$4 $3, $2].",
'filedelete-comment'          => 'Powód',
'filedelete-submit'           => 'Usuń plik',
'filedelete-success'          => "Usunięto plik '''$1'''.",
'filedelete-success-old'      => "Usunięto plik '''[[Media:$1|$1]]''' w wersji z $3, $2.",
'filedelete-nofile'           => "Plik '''$1''' nie istnieje.",
'filedelete-nofile-old'       => "Brak zarchiwizowanej wersji '''$1''' o podanych atrybutach.",
'filedelete-otherreason'      => 'Inny powód',
'filedelete-reason-otherlist' => 'Inny powód',
'filedelete-reason-dropdown'  => '* Najczęstsze przyczyny usunięcia
** Naruszenie praw autorskich
** Kopia już istniejącego pliku',
'filedelete-edit-reasonlist'  => 'Edycja listy powodów usunięcia pliku',
'filedelete-maintenance'      => 'Usuwanie i odtwarzanie plików zostało tymczasowo wyłączone z powodu konserwacji.',

# MIME search
'mimesearch'         => 'Wyszukiwanie MIME',
'mimesearch-summary' => 'Ta strona umożliwia wyszukiwanie plików ze względu na ich typ MIME.
Użycie: typ_treści/podtyp, np. <tt>image/jpeg</tt>.',
'mimetype'           => 'Typ MIME',
'download'           => 'pobierz',

# Unwatched pages
'unwatchedpages' => 'Nieobserwowane strony',

# List redirects
'listredirects' => 'Lista przekierowań',

# Unused templates
'unusedtemplates'     => 'Nieużywane szablony',
'unusedtemplatestext' => 'Poniżej znajduje się lista wszystkich stron znajdujących się w przestrzeni nazw {{ns:template}}, które nie są używane przez inne strony.
Sprawdź inne linki do szablonów, zanim usuniesz tę stronę.',
'unusedtemplateswlh'  => 'linkujące',

# Random page
'randompage'         => 'Losowa strona',
'randompage-nopages' => 'Brak stron w {{PLURAL:$2|przestrzeni nazw|przestrzeniach nazw:}} $1.',

# Random redirect
'randomredirect'         => 'Losowe przekierowanie',
'randomredirect-nopages' => 'Brak jakichkolwiek przekierowań w przestrzeni nazw „$1”.',

# Statistics
'statistics'                   => 'Statystyki',
'statistics-header-pages'      => 'Statystyka stron',
'statistics-header-edits'      => 'Statystyka edycji',
'statistics-header-views'      => 'Statystyka odwiedzin',
'statistics-header-users'      => 'Statystyka użytkowników',
'statistics-header-hooks'      => 'Inne statystyki',
'statistics-articles'          => 'Strony',
'statistics-pages'             => 'Strony',
'statistics-pages-desc'        => 'Wszystkie strony na wiki, w tym strony dyskusji, przekierowania, itd.',
'statistics-files'             => 'Przesłane pliki',
'statistics-edits'             => 'Edycje wykonane od powstania {{GRAMMAR:D.lp|{{SITENAME}}}}',
'statistics-edits-average'     => 'Średnia liczba edycji na stronę',
'statistics-views-total'       => 'Całkowita liczba odwiedzin',
'statistics-views-peredit'     => 'Liczba odwiedzin na edycję',
'statistics-users'             => 'Zarejestrowanych [[Special:ListUsers|użytkowników]]',
'statistics-users-active'      => 'Aktywnych użytkowników',
'statistics-users-active-desc' => 'Użytkownicy, którzy byli aktywni w ciągu {{PLURAL:$1|ostatniego dnia|ostatnich $1 dni}}',
'statistics-mostpopular'       => 'Najczęściej odwiedzane strony',

'disambiguations'      => 'Strony ujednoznaczniające',
'disambiguationspage'  => 'Template:disambig',
'disambiguations-text' => "Poniższe strony odwołują się do '''stron ujednoznaczniających''',
a powinny odwoływać się bezpośrednio do stron treści.<br />
Strona uznawana jest za ujednoznaczniającą, jeśli zawiera szablon linkowany przez stronę [[MediaWiki:Disambiguationspage]]",

'doubleredirects'            => 'Podwójne przekierowania',
'doubleredirectstext'        => 'Lista zawiera strony z przekierowaniami do stron, które przekierowują do innej strony.
Każdy wiersz zawiera linki do pierwszego i drugiego przekierowania oraz link, do którego prowadzi drugie przekierowanie. Ostatni link prowadzi zazwyczaj do strony, do której powinna w rzeczywistości przekierowywać pierwsza strona.
<del>Skreślenie</del> oznacza naprawienie przekierowania.',
'double-redirect-fixed-move' => 'Naprawa podwójnego przekierowania [[$1]] → [[$2]]',
'double-redirect-fixer'      => 'Naprawiacz przekierowań',

'brokenredirects'        => 'Zerwane przekierowania',
'brokenredirectstext'    => 'Poniższe przekierowania wskazują na nieistniejące strony.',
'brokenredirects-edit'   => 'edytuj',
'brokenredirects-delete' => 'usuń',

'withoutinterwiki'         => 'Strony bez odnośników do projektów w innych językach',
'withoutinterwiki-summary' => 'Poniższe strony nie odwołują się do innych wersji językowych.',
'withoutinterwiki-legend'  => 'Prefiks',
'withoutinterwiki-submit'  => 'Pokaż',

'fewestrevisions' => 'Strony z najmniejszą liczbą wersji',

# Miscellaneous special pages
'nbytes'                  => '$1 {{PLURAL:$1|bajt|bajty|bajtów}}',
'ncategories'             => '$1 {{PLURAL:$1|kategoria|kategorie|kategorii}}',
'nlinks'                  => '$1 {{PLURAL:$1|link|linki|linków}}',
'nmembers'                => '$1 {{PLURAL:$1|element|elementy|elementów}}',
'nrevisions'              => '$1 {{PLURAL:$1|wersja|wersje|wersji}}',
'nviews'                  => 'odwiedzono $1 {{PLURAL:$1|raz|razy}}',
'specialpage-empty'       => 'Ta strona raportu jest pusta.',
'lonelypages'             => 'Porzucone strony',
'lonelypagestext'         => 'Do poniższych stron nie linkuje żadna inna strona lub nie są one dołączone do innych stron w {{GRAMMAR:MS.lp|{{SITENAME}}}}.',
'uncategorizedpages'      => 'Nieskategoryzowane strony',
'uncategorizedcategories' => 'Nieskategoryzowane kategorie',
'uncategorizedimages'     => 'Nieskategoryzowane pliki',
'uncategorizedtemplates'  => 'Nieskategoryzowane szablony',
'unusedcategories'        => 'Puste kategorie',
'unusedimages'            => 'Nieużywane pliki',
'popularpages'            => 'Najpopularniejsze strony',
'wantedcategories'        => 'Brakujące kategorie',
'wantedpages'             => 'Najpotrzebniejsze strony',
'wantedpages-badtitle'    => 'Nieprawidłowy tytuł wśród wyników – $1',
'wantedfiles'             => 'Potrzebne pliki',
'wantedtemplates'         => 'Potrzebne szablony',
'mostlinked'              => 'Najczęściej linkowane strony',
'mostlinkedcategories'    => 'Kategorie o największej liczbie stron',
'mostlinkedtemplates'     => 'Najczęściej linkowane szablony',
'mostcategories'          => 'Strony z największą liczbą kategorii',
'mostimages'              => 'Najczęściej linkowane pliki',
'mostrevisions'           => 'Strony o największej liczbie wersji',
'prefixindex'             => 'Wszystkie strony o prefiksie',
'shortpages'              => 'Najkrótsze strony',
'longpages'               => 'Najdłuższe strony',
'deadendpages'            => 'Strony bez linków wewnętrznych',
'deadendpagestext'        => 'Poniższe strony nie posiadają odnośników do innych stron znajdujących się w {{GRAMMAR:MS.lp|{{SITENAME}}}}.',
'protectedpages'          => 'Strony zabezpieczone',
'protectedpages-indef'    => 'Tylko strony zabezpieczone na zawsze',
'protectedpages-cascade'  => 'Tylko strony zabezpieczone rekursywnie',
'protectedpagestext'      => 'Poniższe strony zostały zabezpieczone przed przenoszeniem lub edytowaniem.',
'protectedpagesempty'     => 'Żadna strona nie jest obecnie zabezpieczona z podanymi parametrami.',
'protectedtitles'         => 'Zabezpieczone nazwy stron',
'protectedtitlestext'     => 'Utworzenie stron o następujących nazwach jest zablokowane',
'protectedtitlesempty'    => 'Dla tych ustawień dopuszczalne jest utworzenie stron o dowolnej nazwie.',
'listusers'               => 'Lista użytkowników',
'listusers-editsonly'     => 'Pokaż tylko użytkowników z edycjami',
'listusers-creationsort'  => 'Sortuj według daty utworzenia',
'usereditcount'           => '$1 {{PLURAL:$1|edycja|edycje|edycji}}',
'usercreated'             => 'Utworzono $1 o $2',
'newpages'                => 'Nowe strony',
'newpages-username'       => 'Nazwa użytkownika',
'ancientpages'            => 'Najstarsze strony',
'move'                    => 'Przenieś',
'movethispage'            => 'Przenieś tę stronę',
'unusedimagestext'        => 'W serwisie istnieją następujące pliki, lecz nie są wykorzystane na żadnej ze stron.
Inne witryny mogą odwoływać się do tych plików, używając bezpośrednich adresów URL. Oznacza to, że niektóre z plików mogą się znajdować na tej liście pomimo tego, że są wykorzystywane.',
'unusedcategoriestext'    => 'Poniższe kategorie istnieją, choć nie korzysta z nich żadna strona ani kategoria.',
'notargettitle'           => 'Wskazywana strona nie istnieje',
'notargettext'            => 'Nie podano strony albo użytkownika, dla których ta operacja ma być wykonana.',
'nopagetitle'             => 'Strona docelowa nie istnieje',
'nopagetext'              => 'Wybrana strona docelowa nie istnieje.',
'pager-newer-n'           => '{{PLURAL:$1|1 nowszy|$1 nowsze|$1 nowszych}}',
'pager-older-n'           => '{{PLURAL:$1|1 starszy|$1 starsze|$1 starszych}}',
'suppress'                => 'Rewizor',

# Book sources
'booksources'               => 'Książki',
'booksources-search-legend' => 'Szukaj informacji o książkach',
'booksources-go'            => 'Pokaż',
'booksources-text'          => 'Poniżej znajduje się lista odnośników do innych witryn, które pośredniczą w sprzedaży nowych i używanych książek, a także mogą posiadać dalsze informacje na temat poszukiwanej przez Ciebie książki.',
'booksources-invalid-isbn'  => 'Podany numer ISBN został rozpoznany jako nieprawidłowy. Sprawdź czy podany numer zgadza się z numerem zaczerpniętym ze źródła.',

# Special:Log
'specialloguserlabel'  => 'Użytkownik',
'speciallogtitlelabel' => 'Tytuł',
'log'                  => 'Rejestr operacji',
'all-logs-page'        => 'Wszystkie publiczne operacje',
'alllogstext'          => 'Wspólny rejestr wszystkich typów operacji dla {{GRAMMAR:D.lp|{{SITENAME}}}}.
Możesz zawęzić liczbę wyników poprzez wybranie typu rejestru, nazwy użytkownika albo tytułu strony.',
'logempty'             => 'W rejestrze nie znaleziono pozycji odpowiadających zapytaniu.',
'log-title-wildcard'   => 'Szukaj tytułów zaczynających się od tego tekstu',

# Special:AllPages
'allpages'          => 'Wszystkie strony',
'alphaindexline'    => 'od $1 do $2',
'nextpage'          => 'Następna strona ($1)',
'prevpage'          => 'Poprzednia strona ($1)',
'allpagesfrom'      => 'Strony o tytułach rozpoczynających się od',
'allpagesto'        => 'Strony o tytułach kończących się na',
'allarticles'       => 'Wszystkie artykuły',
'allinnamespace'    => 'Wszystkie strony (w przestrzeni nazw $1)',
'allnotinnamespace' => 'Wszystkie strony (oprócz przestrzeni nazw $1)',
'allpagesprev'      => 'Poprzednia',
'allpagesnext'      => 'Następna',
'allpagessubmit'    => 'Pokaż',
'allpagesprefix'    => 'Pokaż strony o tytułach rozpoczynających się od',
'allpagesbadtitle'  => 'Podana nazwa jest nieprawidłowa, zawiera prefiks międzyprojektowy lub międzyjęzykowy. Może ona także zawierać w sobie jeden lub więcej znaków, których użycie w nazwach jest niedozwolone.',
'allpages-bad-ns'   => 'W {{GRAMMAR:MS.lp|{{SITENAME}}}} nie istnieje przestrzeń nazw „$1”.',

# Special:Categories
'categories'                    => 'Kategorie',
'categoriespagetext'            => 'Strona przedstawia {{PLURAL:$1|kategorię zawierającą|listę kategorii zawierających}} strony lub pliki.
[[Special:UnusedCategories|Nieużywane kategorie]] zostały ukryte.
Zobacz również [[Special:WantedCategories|brakujące kategorie]].',
'categoriesfrom'                => 'Wyświetl kategorie, zaczynając od',
'special-categories-sort-count' => 'sortowanie według liczby',
'special-categories-sort-abc'   => 'sortowanie alfabetyczne',

# Special:DeletedContributions
'deletedcontributions'             => 'Usunięty wkład użytkownika',
'deletedcontributions-title'       => 'Usunięty wkład użytkownika',
'sp-deletedcontributions-contribs' => 'wkład',

# Special:LinkSearch
'linksearch'       => 'Linki zewnętrzne',
'linksearch-pat'   => 'Wzorzec wyszukiwania',
'linksearch-ns'    => 'Przestrzeń nazw',
'linksearch-ok'    => 'Szukaj',
'linksearch-text'  => 'Można użyć symbolu wieloznacznego „*”. Dla przykładu „*.wikipedia.org” spowoduje wyszukanie wszystkich linków prowadzących do domeny „wikipedia.org” i jej poddomen.<br />
Obsługiwane protokoły: <tt>$1</tt>',
'linksearch-line'  => '$1 link na stronie $2',
'linksearch-error' => 'Symbolu wieloznacznego można użyć wyłącznie na początku nazwy hosta.',

# Special:ListUsers
'listusersfrom'      => 'Pokaż użytkowników zaczynając od',
'listusers-submit'   => 'Pokaż',
'listusers-noresult' => 'Nie znaleziono żadnego użytkownika.',
'listusers-blocked'  => '({{GENDER:$1|zablokowany|zablokowana|zablokowany}})',

# Special:ActiveUsers
'activeusers'            => 'Lista aktywnych użytkowników',
'activeusers-intro'      => 'Poniżej znajduje się lista użytkowników, którzy byli aktywni w ciągu {{PLURAL:$1|ostatniego dnia|ostatnich $1 dni}}.',
'activeusers-count'      => 'w ciągu {{PLURAL:$3|ostatniego dnia|ostatnich $3 dni}} {{GENDER:$2|wykonał|wykonała|wykonał}} $1 {{PLURAL:$1|edycję|edycje|edycji}}',
'activeusers-from'       => 'Pokaż użytkowników zaczynając od',
'activeusers-hidebots'   => 'Ukryj boty',
'activeusers-hidesysops' => 'Ukryj administratorów',
'activeusers-noresult'   => 'Nie odnaleziono żadnego użytkownika.',

# Special:Log/newusers
'newuserlogpage'              => 'Nowi użytkownicy',
'newuserlogpagetext'          => 'To jest rejestr ostatnio utworzonych kont użytkowników',
'newuserlog-byemail'          => 'hasło zostało wysłane e‐mailem',
'newuserlog-create-entry'     => '– nowy użytkownik',
'newuserlog-create2-entry'    => 'utworzył konto $1',
'newuserlog-autocreate-entry' => '– konto utworzone automatycznie',

# Special:ListGroupRights
'listgrouprights'                      => 'Uprawnienia grup użytkowników',
'listgrouprights-summary'              => 'Poniżej znajduje się spis zdefiniowanych na tej wiki grup użytkowników, z wyszczególnieniem przydzielonych im uprawnień.
Sprawdź stronę z [[{{MediaWiki:Listgrouprights-helppage}}|dodatkowymi informacjami]] o uprawnieniach.',
'listgrouprights-key'                  => '* <span class="listgrouprights-granted">Przyznane uprawnienie</span>
* <span class="listgrouprights-revoked">Odebrane uprawnienie</span>',
'listgrouprights-group'                => 'Grupa',
'listgrouprights-rights'               => 'Uprawnienia',
'listgrouprights-helppage'             => 'Help:Uprawnienia grup użytkowników',
'listgrouprights-members'              => '(lista członków grupy)',
'listgrouprights-addgroup'             => 'Możliwość dodawania do {{PLURAL:$2|grupy|grup:}} $1',
'listgrouprights-removegroup'          => 'Możliwość usuwania z {{PLURAL:$2|grupy|grup:}} $1',
'listgrouprights-addgroup-all'         => 'Możliwość dodania użytkownika do każdej grupy',
'listgrouprights-removegroup-all'      => 'Możliwość usunięcia użytkownika z każdej grupy',
'listgrouprights-addgroup-self'        => 'Może dodać własne konto do {{PLURAL:$2|grupy|grup:}} $1',
'listgrouprights-removegroup-self'     => 'Możliwość usunięcia własnego konta z {{PLURAL:$2|grupy|grup:}} $1',
'listgrouprights-addgroup-self-all'    => 'Może dodać własne konto do wszystkich grup',
'listgrouprights-removegroup-self-all' => 'Może usunąć własne konto ze wszystkich grup',

# E-mail user
'mailnologin'          => 'Brak adresu',
'mailnologintext'      => 'Musisz się [[Special:UserLogin|zalogować]] i mieć wpisany aktualny adres e‐mailowy w swoich [[Special:Preferences|preferencjach]], aby móc wysłać e‐mail do innego użytkownika.',
'emailuser'            => 'Wyślij e‐mail do tego użytkownika',
'emailpage'            => 'Wyślij e‐mail do użytkownika',
'emailpagetext'        => 'Możesz użyć poniższego formularza, aby wysłać wiadomość e‐mail do tego użytkownika.
Adres e‐mailowy, który został przez Ciebie wprowadzony w [[Special:Preferences|Twoich preferencjach]], zostanie umieszczony w polu „Od”, dzięki czemu odbiorca będzie mógł Ci odpowiedzieć.',
'usermailererror'      => 'Moduł obsługi poczty zwrócił błąd:',
'defemailsubject'      => 'Wiadomość z {{GRAMMAR:D.lp|{{SITENAME}}}}',
'usermaildisabled'     => 'E‐mail użytkownika jest wyłączony',
'usermaildisabledtext' => 'Nie możesz wysyłać e‐maili do innych użytkowników tej wiki',
'noemailtitle'         => 'Brak adresu e‐mail',
'noemailtext'          => 'Ten użytkownik nie podał poprawnego adresu e‐mail.',
'nowikiemailtitle'     => 'Brak zezwolenia na otrzymywanie e‐maili',
'nowikiemailtext'      => 'Ten użytkownik nie chce otrzymywać wiadomości e‐mail od innych użytkowników.',
'email-legend'         => 'Wyślij e‐mail do innego użytkownika {{GRAMMAR:D.lp|{{SITENAME}}}}',
'emailfrom'            => 'Od:',
'emailto'              => 'Do:',
'emailsubject'         => 'Temat:',
'emailmessage'         => 'Wiadomość:',
'emailsend'            => 'Wyślij',
'emailccme'            => 'Wyślij mi kopię mojej wiadomości.',
'emailccsubject'       => 'Kopia Twojej wiadomości do $1: $2',
'emailsent'            => 'Wiadomość została wysłana',
'emailsenttext'        => 'Twoja wiadomość została wysłana.',
'emailuserfooter'      => 'Wiadomość e‐mail została wysłana z {{GRAMMAR:D.lp|{{SITENAME}}}} do $2 przez $1 z użyciem „Wyślij e‐mail do tego użytkownika”.',

# User Messenger
'usermessage-summary' => 'Pozostawianie komunikatu systemowego.',
'usermessage-editor'  => 'Nadawca komunikatów systemowych',

# Watchlist
'watchlist'            => 'Obserwowane',
'mywatchlist'          => 'Obserwowane',
'watchlistfor2'        => 'Dla $1 $2',
'nowatchlist'          => 'Lista obserwowanych przez Ciebie stron jest pusta.',
'watchlistanontext'    => '$1, aby obejrzeć lub edytować elementy listy obserwowanych.',
'watchnologin'         => 'Nie jesteś zalogowany',
'watchnologintext'     => 'Musisz się [[Special:UserLogin|zalogować]] przed modyfikacją listy obserwowanych stron.',
'addedwatch'           => 'Dodana do listy obserwowanych',
'addedwatchtext'       => "Strona „[[:$1|$1]]” została dodana do Twojej [[Special:Watchlist|listy obserwowanych]].
Każda zmiana treści tej strony lub związanej z nią strony dyskusji zostanie odnotowana na tej liście. Dodatkowo nazwa strony zostanie '''wytłuszczona''' na [[Special:RecentChanges|liście ostatnich zmian]], aby ułatwić Ci zauważenie faktu zmiany.",
'removedwatch'         => 'Usunięto z listy obserwowanych',
'removedwatchtext'     => 'Strona „[[:$1]]” została usunięta z Twojej [[Special:Watchlist|listy obserwowanych]].',
'watch'                => 'Obserwuj',
'watchthispage'        => 'Obserwuj',
'unwatch'              => 'Nie obserwuj',
'unwatchthispage'      => 'Nie obserwuj',
'notanarticle'         => 'To nie jest artykuł',
'notvisiblerev'        => 'Wersja została usunięta',
'watchnochange'        => 'Żadna z obserwowanych stron nie była edytowana w podanym okresie.',
'watchlist-details'    => 'Na liście obserwowanych {{PLURAL:$1|jest 1 strona|są $1 strony|jest $1 stron}}, nie licząc stron dyskusji.',
'wlheader-enotif'      => '* Wysyłanie powiadomień na adres e‐mail jest włączone.',
'wlheader-showupdated' => "* '''Wytłuszczone''' zostały strony, które zostały zmodyfikowane od Twojej ostatniej na nich wizyty.",
'watchmethod-recent'   => 'poszukiwanie ostatnich zmian wśród obserwowanych stron',
'watchmethod-list'     => 'poszukiwanie obserwowanych stron wśród ostatnich zmian',
'watchlistcontains'    => 'Na liście obserwowanych przez Ciebie stron {{PLURAL:$1|znajduje się 1 pozycja|znajdują się $1 pozycje|znajduje się $1 pozycji}}.',
'iteminvalidname'      => 'Problem z pozycją „$1” – niepoprawna nazwa...',
'wlnote'               => "Poniżej pokazano {{PLURAL:$1|ostatnią zmianę wykonaną|ostatnie '''$1''' zmiany wykonane|ostatnich '''$1''' zmian wykonanych}} w ciągu {{PLURAL:$2|ostatniej godziny|ostatnich '''$2''' godzin}}.",
'wlshowlast'           => 'Pokaż ostatnie $1 godzin, $2 dni ($3)',
'watchlist-options'    => 'Opcje obserwowanych',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'Dodaję do obserwowanych...',
'unwatching' => 'Przestaję obserwować...',

'enotif_mailer'                => 'Powiadomienie z {{GRAMMAR:D.lp|{{SITENAME}}}}',
'enotif_reset'                 => 'Zaznacz wszystkie strony jako odwiedzone',
'enotif_newpagetext'           => 'To jest nowa strona.',
'enotif_impersonal_salutation' => 'użytkownik {{GRAMMAR:D.lp|{{SITENAME}}}}',
'changed'                      => 'zmieniona',
'created'                      => 'utworzona',
'enotif_subject'               => 'Strona $PAGETITLE w {{GRAMMAR:MS.lp|{{SITENAME}}}} została $CHANGEDORCREATED przez użytkownika $PAGEEDITOR',
'enotif_lastvisited'           => 'Zobacz na stronie $1 wszystkie zmiany od Twojej ostatniej wizyty.',
'enotif_lastdiff'              => 'Zobacz na stronie $1 tę zmianę.',
'enotif_anon_editor'           => 'użytkownik anonimowy $1',
'enotif_body'                  => 'Szanowny $WATCHINGUSERNAME,

strona $PAGETITLE w {{GRAMMAR:MS.lp|{{SITENAME}}}} została $CHANGEDORCREATED $PAGEEDITDATE przez użytkownika $PAGEEDITOR. Zobacz na stronie $PAGETITLE_URL aktualną wersję.

$NEWPAGE

Opis zmiany: $PAGESUMMARY $PAGEMINOREDIT

Kontakt do autora:
mail – $PAGEEDITOR_EMAIL
wiki – $PAGEEDITOR_WIKI

W przypadku kolejnych zmian nowe powiadomienia nie zostaną wysłane, dopóki nie odwiedzisz tej strony.
Możesz także zresetować wszystkie flagi powiadomień na swojej liście stron obserwowanych.

$NEWPAGE

	Wiadomość z systemu powiadomień {{GRAMMAR:D.lp|{{SITENAME}}}}

--
W celu zmiany ustawień swojej listy obserwowanych odwiedź
{{fullurl:{{#special:Watchlist}}/edit}}

Usunięcie strony z listy obserwowanych możliwe jest na stronie
$UNWATCHURL

Pomoc
{{fullurl:{{MediaWiki:Helppage}}}}',

# Delete
'deletepage'             => 'Usuń stronę',
'confirm'                => 'Potwierdź',
'excontent'              => 'treść: „$1”',
'excontentauthor'        => 'treść: „$1” (jedyny autor: [[Special:Contributions/$2|$2]])',
'exbeforeblank'          => 'poprzednia zawartość, obecnie pustej strony: „$1”',
'exblank'                => 'Strona była pusta',
'delete-confirm'         => 'Usuwanie „$1”',
'delete-legend'          => 'Usuń',
'historywarning'         => "'''Uwaga!''' Strona, którą chcesz usunąć, ma w przybliżeniu {{PLURAL:$1|starszą wersję|$1 starsze wersje|$1 starszych wersji}}:",
'confirmdeletetext'      => 'Zamierzasz usunąć stronę razem z całą dotyczącą jej historią.
Upewnij się, czy na pewno chcesz to zrobić, że rozumiesz konsekwencje i że robisz to w zgodzie z [[{{MediaWiki:Policy-url}}|zasadami]].',
'actioncomplete'         => 'Operacja wykonana',
'actionfailed'           => 'Działanie nie powiodło się',
'deletedtext'            => 'Usunięto „<nowiki>$1</nowiki>”.
Zobacz na stronie $2 rejestr ostatnio wykonanych usunięć.',
'deletedarticle'         => 'usunął [[$1]]',
'suppressedarticle'      => 'utajnił [[$1]]',
'dellogpage'             => 'Usunięte',
'dellogpagetext'         => 'Poniżej znajduje się lista ostatnio wykonanych usunięć.',
'deletionlog'            => 'rejestr usunięć',
'reverted'               => 'Przywrócono poprzednią wersję',
'deletecomment'          => 'Powód',
'deleteotherreason'      => 'Inny lub dodatkowy powód:',
'deletereasonotherlist'  => 'Inny powód',
'deletereason-dropdown'  => '* Najczęstsze powody usunięcia
** Prośba autora
** Naruszenie praw autorskich
** Wandalizm',
'delete-edit-reasonlist' => 'Edytuj listę powodów',
'delete-toobig'          => 'Ta strona ma bardzo długą historię edycji, ponad $1 {{PLURAL:$1|zmianę|zmiany|zmian}}.
Usunięcie jej mogłoby spowodować zakłócenia w pracy {{GRAMMAR:D.lp|{{SITENAME}}}} i dlatego zostało ograniczone.',
'delete-warning-toobig'  => 'Ta strona ma bardzo długą historię edycji, ponad $1 {{PLURAL:$1|zmianę|zmiany|zmian}}.
Bądź ostrożny, ponieważ usunięcie jej może spowodować zakłócenia w pracy {{GRAMMAR:D.lp|{{SITENAME}}}}.',

# Rollback
'rollback'          => 'Cofnij edycję',
'rollback_short'    => 'Cofnij',
'rollbacklink'      => 'cofnij',
'rollbackfailed'    => 'Nie udało się cofnąć zmiany',
'cantrollback'      => 'Nie można cofnąć edycji, ponieważ jest tylko jedna wersja tej strony.',
'alreadyrolled'     => 'Nie można dla strony [[:$1|$1]] cofnąć ostatniej zmiany, którą wykonał [[User:$2|$2]] ([[User talk:$2|dyskusja]]{{int:pipe-separator}}[[Special:Contributions/$2|{{int:contribslink}}]]).
Ktoś inny zdążył już to zrobić lub wprowadził własne poprawki do treści strony.

Autorem ostatniej zmiany jest teraz [[User:$3|$3]] ([[User talk:$3|dyskusja]]{{int:pipe-separator}}[[Special:Contributions/$3|{{int:contribslink}}]]).',
'editcomment'       => "Edycję opisał „''$1''”.",
'revertpage'        => 'Wycofano edycje użytkownika [[Special:Contributions/$2|$2]] ([[User talk:$2|dyskusja]]). Autor przywróconej wersji to [[User:$1|$1]].',
'revertpage-nouser' => 'Wycofano edycje (nazwa użytkownika usunięta). Autor przywróconej wersji to [[User:$1|$1]].',
'rollback-success'  => 'Wycofano edycje użytkownika $1.
Przywrócono ostatnią wersję autorstwa $2.',

# Edit tokens
'sessionfailure-title' => 'Błąd sesji',
'sessionfailure'       => 'Wystąpił problem z weryfikacją zalogowania.
Polecenie zostało anulowane, aby uniknąć przechwycenia sesji.
Naciśnij „wstecz” w przeglądarce, przeładuj stronę, po czym ponownie wydaj polecenie.',

# Protect
'protectlogpage'              => 'Zabezpieczone',
'protectlogtext'              => 'Poniżej znajduje się lista blokad założonych i zdjętych z pojedynczych stron.
Aby przejrzeć listę obecnie działających zabezpieczeń, przejdź na stronę wykazu [[Special:ProtectedPages|zabezpieczonych stron]].',
'protectedarticle'            => 'zabezpieczył [[$1]]',
'modifiedarticleprotection'   => 'zmienił poziom zabezpieczenia [[$1]]',
'unprotectedarticle'          => 'odbezpieczył [[$1]]',
'movedarticleprotection'      => 'przeniósł ustawienia zabezpieczeń z [[$2]] do [[$1]]',
'protect-title'               => 'Zmiana poziomu zabezpieczenia „$1”',
'prot_1movedto2'              => 'stronę [[$1]] przeniósł do [[$2]]',
'protect-legend'              => 'Potwierdź zabezpieczenie',
'protectcomment'              => 'Powód',
'protectexpiry'               => 'Czas wygaśnięcia',
'protect_expiry_invalid'      => 'Podany czas automatycznego odbezpieczenia jest nieprawidłowy.',
'protect_expiry_old'          => 'Podany czas automatycznego odblokowania znajduje się w przeszłości.',
'protect-unchain-permissions' => 'Odblokuj dodatkowe opcje zabezpieczania',
'protect-text'                => "Możesz tu sprawdzić i zmienić poziom zabezpieczenia strony '''<nowiki>$1</nowiki>'''.",
'protect-locked-blocked'      => "Nie możesz zmienić poziomów zabezpieczenia, ponieważ jesteś zablokowany.
Obecne ustawienia dla strony '''$1''' to:",
'protect-locked-dblock'       => "Nie można zmienić poziomu zabezpieczenia z powodu działającej blokady bazy danych. Obecne ustawienia dla strony '''$1''' to:",
'protect-locked-access'       => "Nie masz uprawnień do zmiany poziomu zabezpieczenia strony. Obecne ustawienia dla strony '''$1''' to:",
'protect-cascadeon'           => 'Ta strona jest zabezpieczona przed edycją, ponieważ jest używana przez {{PLURAL:$1|następującą stronę, która została zabezpieczona|następujące strony, które zostały zabezpieczone}} z włączoną opcją dziedziczenia. Możesz zmienić poziom zabezpieczenia strony, ale nie wpłynie to na dziedziczenie zabezpieczenia.',
'protect-default'             => 'Dostęp mają wszyscy użytkownicy',
'protect-fallback'            => 'Wymaga uprawnień „$1”',
'protect-level-autoconfirmed' => 'Blokuj nowych i niezarejestrowanych użytkowników',
'protect-level-sysop'         => 'Dostęp mają tylko administratorzy',
'protect-summary-cascade'     => 'dziedziczenie',
'protect-expiring'            => 'wygasa $1 (UTC)',
'protect-expiry-indefinite'   => 'na zawsze',
'protect-cascade'             => 'Dziedziczenie zabezpieczenia – zabezpiecz wszystkie strony zawarte na tej stronie.',
'protect-cantedit'            => 'Nie możesz zmienić poziomu zabezpieczenia tej strony, ponieważ nie masz uprawnień do jej edycji.',
'protect-othertime'           => 'Inny okres',
'protect-othertime-op'        => 'inny okres',
'protect-existing-expiry'     => 'Obecny czas wygaśnięcia: $2 o $3',
'protect-otherreason'         => 'Inny lub dodatkowy powód',
'protect-otherreason-op'      => 'Inny powód',
'protect-dropdown'            => '*Najczęstsze powody zabezpieczenia
** Częste wandalizmy
** Częste spamowanie
** Wojna edycyjna
** Wygłupy',
'protect-edit-reasonlist'     => 'Edytuj listę przyczyn zabezpieczenia',
'protect-expiry-options'      => '1 godzina:1 hour,1 dzień:1 day,1 tydzień:1 week,2 tygodnie:2 weeks,1 miesiąc:1 month,3 miesiące:3 months,6 miesięcy:6 months,1 rok:1 year,na zawsze:infinite',
'restriction-type'            => 'Ograniczenia',
'restriction-level'           => 'Poziom',
'minimum-size'                => 'Minimalny rozmiar',
'maximum-size'                => 'Maksymalny rozmiar',
'pagesize'                    => '(bajtów)',

# Restrictions (nouns)
'restriction-edit'   => 'edytowanie',
'restriction-move'   => 'przenoszenie',
'restriction-create' => 'tworzenie',
'restriction-upload' => 'przesyłanie',

# Restriction levels
'restriction-level-sysop'         => 'całkowite zabezpieczenie',
'restriction-level-autoconfirmed' => 'częściowe zabezpieczenie',
'restriction-level-all'           => 'dowolny poziom',

# Undelete
'undelete'                     => 'Odtwórz usuniętą stronę',
'undeletepage'                 => 'Odtwarzanie usuniętych stron',
'undeletepagetitle'            => "'''Poniżej znajdują się usunięte wersje strony [[:$1]]'''.",
'viewdeletedpage'              => 'Zobacz usunięte wersje',
'undeletepagetext'             => '{{PLURAL:$1|Następująca strona została usunięta, ale jej|Następujące $1 strony zostały usunięte, ale ich}} kopia wciąż znajduje się w archiwum.
Archiwum co jakiś czas może być oczyszczane.',
'undelete-fieldset-title'      => 'Odtwarzanie wersji',
'undeleteextrahelp'            => "Jeśli chcesz odtworzyć całą stronę, pozostaw wszystkie pola niezaznaczone i kliknij '''''Odtwórz'''''.
Częściowe odtworzenie możesz wykonać, zaznaczając odpowiednie pola, odpowiadające wersjom, które będą odtworzone, a następnie klikając '''''Odtwórz'''''.
Naciśnięcie '''''Wyczyść''''' usunie wszystkie zaznaczenia i wyczyści pole komentarza.",
'undeleterevisions'            => '$1 {{PLURAL:$1|zarchiwizowana wersja|zarchiwizowane wersje|zarchiwizowanych wersji}}',
'undeletehistory'              => 'Odtworzenie strony spowoduje przywrócenie także jej wszystkich poprzednich wersji.
Jeśli od czasu usunięcia ktoś utworzył nową stronę o tej samej nazwie, odtwarzane wersje znajdą się w jej historii, a obecna wersja pozostanie bez zmian.',
'undeleterevdel'               => 'Odtworzenie nie zostanie przeprowadzone, jeśli mogłoby spowodować częściowe usunięcie aktualnej wersji strony lub pliku.
W takiej sytuacji należy odznaczyć lub przywrócić widoczność najnowszej usuniętej wersji.',
'undeletehistorynoadmin'       => 'Ta strona została usunięta.
Przyczyna usunięcia podana jest w podsumowaniu poniżej, razem z danymi użytkownika, który edytował stronę przed usunięciem.
Sama treść usuniętych wersji jest dostępna jedynie dla administratorów.',
'undelete-revision'            => 'Usunięto wersję $1 (z $5 $4) autorstwa $3:',
'undeleterevision-missing'     => 'Nieprawidłowa lub brakująca wersja.
Możesz mieć zły link lub wersja mogła zostać odtworzona lub usunięta z archiwum.',
'undelete-nodiff'              => 'Nie znaleziono poprzednich wersji.',
'undeletebtn'                  => 'Odtwórz',
'undeletelink'                 => 'pokaż lub odtwórz',
'undeleteviewlink'             => 'pokaż',
'undeletereset'                => 'Wyczyść',
'undeleteinvert'               => 'Odwróć zaznaczenie',
'undeletecomment'              => 'Powód',
'undeletedarticle'             => 'odtworzył [[$1]]',
'undeletedrevisions'           => 'odtworzono {{PLURAL:$1|1 wersję|$1 wersje|$1 wersji}}',
'undeletedrevisions-files'     => 'odtworzono $1 {{PLURAL:$1|wersję|wersje|wersji}} i $2 {{PLURAL:$2|plik|pliki|plików}}',
'undeletedfiles'               => 'odtworzył $1 {{PLURAL:$1|plik|pliki|plików}}',
'cannotundelete'               => 'Odtworzenie nie powiodło się.
Ktoś inny prawdopodobnie odtworzył już tę stronę.',
'undeletedpage'                => "'''Odtworzono stronę $1.'''

Zobacz [[Special:Log/delete|rejestr usunięć]], jeśli chcesz przejrzeć ostatnie operacje usuwania i odtwarzania stron.",
'undelete-header'              => 'Zobacz [[Special:Log/delete|rejestr usunięć]], aby sprawdzić ostatnio usunięte strony.',
'undelete-search-box'          => 'Szukaj usuniętych stron',
'undelete-search-prefix'       => 'Strony o tytułach rozpoczynających się od',
'undelete-search-submit'       => 'Szukaj',
'undelete-no-results'          => 'Nie znaleziono wskazanych stron w archiwum usuniętych.',
'undelete-filename-mismatch'   => 'Nie można odtworzyć wersji pliku z datą $1: niezgodność nazwy pliku',
'undelete-bad-store-key'       => 'Nie można odtworzyć wersji pliku z datą $1: przed usunięciem brakowało pliku.',
'undelete-cleanup-error'       => 'Wystąpił błąd przy usuwaniu nieużywanego archiwalnego pliku „$1”.',
'undelete-missing-filearchive' => 'Nie udało się odtworzyć z archiwum pliku o ID $1, ponieważ brak go w bazie danych.
Być może plik został już odtworzony.',
'undelete-error-short'         => 'Wystąpił błąd przy odtwarzaniu pliku: $1',
'undelete-error-long'          => 'Napotkano błędy przy odtwarzaniu pliku:

$1',
'undelete-show-file-confirm'   => 'Czy na pewno chcesz zobaczyć usuniętą wersję pliku „<nowiki>$1</nowiki>” z $2 $3?',
'undelete-show-file-submit'    => 'Tak',

# Namespace form on various pages
'namespace'      => 'Przestrzeń nazw',
'invert'         => 'odwróć wybór',
'blanknamespace' => '(główna)',

# Contributions
'contributions'       => 'Wkład użytkownika',
'contributions-title' => 'Wkład {{GENDER:$1|użytkownika|użytkowniczki}} $1',
'mycontris'           => 'Moje edycje',
'contribsub2'         => 'Dla użytkownika $1 ($2)',
'nocontribs'          => 'Brak zmian odpowiadających tym kryteriom.',
'uctop'               => ' (jako ostatnia)',
'month'               => 'Przed miesiącem (włącznie)',
'year'                => 'Przed rokiem (włącznie)',

'sp-contributions-newbies'             => 'Pokaż wyłącznie wkład nowych użytkowników',
'sp-contributions-newbies-sub'         => 'Dla nowych użytkowników',
'sp-contributions-newbies-title'       => 'Wkład nowych użytkowników',
'sp-contributions-blocklog'            => 'blokady',
'sp-contributions-deleted'             => 'usunięty wkład użytkownika',
'sp-contributions-logs'                => 'rejestry',
'sp-contributions-talk'                => 'dyskusja',
'sp-contributions-userrights'          => 'zarządzanie uprawnieniami użytkownika',
'sp-contributions-blocked-notice'      => 'To konto użytkownika jest obecnie zablokowane. Ostatni wpis rejestru blokad jest pokazany poniżej.',
'sp-contributions-blocked-notice-anon' => 'Ten adres IP jest obecnie zablokowany.
Poniżej znajduje się ostatni wpis w rejestrze blokowania.',
'sp-contributions-search'              => 'Szukaj wkładu',
'sp-contributions-username'            => 'Adres IP lub nazwa użytkownika',
'sp-contributions-toponly'             => 'Pokaż wyłącznie ostatnie wersje',
'sp-contributions-submit'              => 'Szukaj',

# What links here
'whatlinkshere'            => 'Linkujące',
'whatlinkshere-title'      => 'Strony linkujące do „$1”',
'whatlinkshere-page'       => 'Strona',
'linkshere'                => "Następujące strony odwołują się do '''[[:$1]]''':",
'nolinkshere'              => "Żadna strona nie odwołuje się do '''[[:$1]]'''.",
'nolinkshere-ns'           => "Żadna strona nie odwołuje się do '''[[:$1]]''' w wybranej przestrzeni nazw.",
'isredirect'               => 'strona przekierowująca',
'istemplate'               => 'dołączony szablon',
'isimage'                  => 'odnośnik z grafiki',
'whatlinkshere-prev'       => '{{PLURAL:$1|poprzednie|poprzednie $1}}',
'whatlinkshere-next'       => '{{PLURAL:$1|następne|następne $1}}',
'whatlinkshere-links'      => '← linkujące',
'whatlinkshere-hideredirs' => '$1 przekierowania',
'whatlinkshere-hidetrans'  => '$1 dołączenia',
'whatlinkshere-hidelinks'  => '$1 linki',
'whatlinkshere-hideimages' => '$1 linki z grafik',
'whatlinkshere-filters'    => 'Filtry',

# Block/unblock
'blockip'                         => 'Zablokuj użytkownika',
'blockip-title'                   => 'Zablokowanie użytkownika',
'blockip-legend'                  => 'Zablokuj użytkownika',
'blockiptext'                     => 'Użyj poniższego formularza do zablokowania możliwości edycji spod określonego adresu IP lub konkretnemu użytkownikowi.
Blokować należy jedynie po to, by zapobiec wandalizmom, zgodnie z [[{{MediaWiki:Policy-url}}|przyjętymi zasadami]].
Podaj powód (np. umieszczając nazwy stron, na których dopuszczono się wandalizmu).',
'ipaddress'                       => 'Adres IP',
'ipadressorusername'              => 'Adres IP lub nazwa użytkownika',
'ipbexpiry'                       => 'Upływa',
'ipbreason'                       => 'Powód',
'ipbreasonotherlist'              => 'Inny powód',
'ipbreason-dropdown'              => '*Najczęstsze przyczyny blokad
** Ataki na innych użytkowników
** Naruszenie praw autorskich
** Niedozwolona nazwa użytkownika
** Open proxy lub Tor
** Spamowanie
** Usuwanie treści stron
** Wprowadzanie fałszywych informacji
** Wulgaryzmy
** Wypisywanie bzdur na stronach',
'ipbanononly'                     => 'Zablokuj tylko anonimowych użytkowników',
'ipbcreateaccount'                => 'Zapobiegnij utworzeniu konta',
'ipbemailban'                     => 'Zablokuj możliwość wysyłania e‐mailów',
'ipbenableautoblock'              => 'Zablokuj ostatni adres IP tego użytkownika i automatycznie wszystkie kolejne, z których będzie próbował edytować',
'ipbsubmit'                       => 'Zablokuj użytkownika',
'ipbother'                        => 'Inny okres',
'ipboptions'                      => '2 godziny:2 hours,1 dzień:1 day,3 dni:3 days,1 tydzień:1 week,2 tygodnie:2 weeks,1 miesiąc:1 month,3 miesiące:3 months,6 miesięcy:6 months,1 rok:1 year,na zawsze:infinite',
'ipbotheroption'                  => 'inny okres',
'ipbotherreason'                  => 'Inne lub dodatkowy powód',
'ipbhidename'                     => 'Ukryj nazwę użytkownika w edycjach i listach',
'ipbwatchuser'                    => 'Obserwuj stronę osobistą i stronę dyskusji tego użytkownika',
'ipballowusertalk'                => 'Pozwól temu użytkownikowi edytować własną stronę dyskusji, kiedy jest zablokowany',
'ipb-change-block'                => 'Zmień ustawienia blokady',
'badipaddress'                    => 'Niepoprawny adres IP',
'blockipsuccesssub'               => 'Zablokowanie powiodło się',
'blockipsuccesstext'              => '{{GENDER:$1|Użytkownik|Użytkowniczka}} [[Special:Contributions/$1|$1]] {{GENDER:$1|został zablokowany|została zablokowana}}.<br />
Przejdź do [[Special:IPBlockList|listy zablokowanych adresów IP]], by przejrzeć blokady.',
'ipb-edit-dropdown'               => 'Edytuj przyczynę blokady',
'ipb-unblock-addr'                => 'Odblokuj $1',
'ipb-unblock'                     => 'Odblokuj użytkownika lub adres IP',
'ipb-blocklist-addr'              => 'Istniejące blokady dla $1',
'ipb-blocklist'                   => 'Zobacz istniejące blokady',
'ipb-blocklist-contribs'          => 'Wkład $1',
'unblockip'                       => 'Odblokuj użytkownika',
'unblockiptext'                   => 'Użyj poniższego formularza, by przywrócić możliwość edycji z wcześniej zablokowanego adresu IP lub użytkownikowi.',
'ipusubmit'                       => 'Odblokuj',
'unblocked'                       => '[[User:$1|$1]] {{GENDER:$1|został odblokowany|została odblokowana|został odblokowany}}.',
'unblocked-id'                    => 'Blokada $1 została zdjęta',
'ipblocklist'                     => 'Lista zablokowanych adresów IP i użytkowników',
'ipblocklist-legend'              => 'Znajdź zablokowanego użytkownika',
'ipblocklist-username'            => 'Nazwa użytkownika lub adres IP',
'ipblocklist-sh-userblocks'       => '$1 blokady kont',
'ipblocklist-sh-tempblocks'       => '$1 tymczasowe blokady',
'ipblocklist-sh-addressblocks'    => '$1 blokady pojedynczych adresów IP',
'ipblocklist-submit'              => 'Szukaj',
'ipblocklist-localblock'          => 'Lokalna blokada',
'ipblocklist-otherblocks'         => '{{PLURAL:$1|Inna blokada|Inne blokady}}',
'blocklistline'                   => '$1, $2 zablokował $3 ($4)',
'infiniteblock'                   => 'na zawsze',
'expiringblock'                   => 'wygasa $1 o $2',
'anononlyblock'                   => 'tylko niezalogowani',
'noautoblockblock'                => 'automatyczne blokowanie wyłączone',
'createaccountblock'              => 'blokada tworzenia kont',
'emailblock'                      => 'zablokowany e‐mail',
'blocklist-nousertalk'            => 'nie mogą edytować własnych stron dyskusji',
'ipblocklist-empty'               => 'Lista blokad jest pusta.',
'ipblocklist-no-results'          => 'Podany adres IP lub użytkownik nie jest zablokowany.',
'blocklink'                       => 'zablokuj',
'unblocklink'                     => 'odblokuj',
'change-blocklink'                => 'zmień blokadę',
'contribslink'                    => 'edycje',
'autoblocker'                     => 'Zablokowano Cię automatycznie, ponieważ używasz tego samego adresu IP, co użytkownik „[[User:$1|$1]]”.
Przyczyna blokady $1 to: „$2”',
'blocklogpage'                    => 'Historia blokad',
'blocklog-showlog'                => '{{GENDER:$1|Ten użytkownik był|Ta użytkowniczka była}} już wcześniej {{GENDER:$1|blokowany|blokowana}}. Poniżej znajduje się rejestr blokad:',
'blocklog-showsuppresslog'        => '{{GENDER:$1|Ten użytkownik był|Ta użytkowniczka była}} już wcześniej {{GENDER:$1|blokowany oraz ukrywany|blokowana oraz ukrywana}}. Poniżej znajduje się rejestr ukrywania:',
'blocklogentry'                   => 'zablokował [[$1]], czas blokady: $2 $3',
'reblock-logentry'                => 'zmienił ustawienia blokady dla [[$1]], czas blokady: $2 $3',
'blocklogtext'                    => 'Poniżej znajduje się lista blokad założonych i zdjętych z poszczególnych adresów IP.
Na liście nie znajdą się adresy IP, które zablokowano w sposób automatyczny.
By przejrzeć listę obecnie aktywnych blokad, przejdź na stronę [[Special:IPBlockList|zablokowanych adresów i użytkowników]].',
'unblocklogentry'                 => 'odblokował $1',
'block-log-flags-anononly'        => 'tylko anonimowi',
'block-log-flags-nocreate'        => 'blokada tworzenia konta',
'block-log-flags-noautoblock'     => 'automatyczne blokowanie wyłączone',
'block-log-flags-noemail'         => 'e‐mail zablokowany',
'block-log-flags-nousertalk'      => 'nie może edytować własnej strony dyskusji',
'block-log-flags-angry-autoblock' => 'rozszerzone automatyczne blokowanie włączone',
'block-log-flags-hiddenname'      => 'nazwa użytkownika jest ukryta',
'range_block_disabled'            => 'Możliwość blokowania zakresu adresów IP została wyłączona.',
'ipb_expiry_invalid'              => 'Błędny czas wygaśnięcia blokady.',
'ipb_expiry_temp'                 => 'Ukryte blokowanie nazwy użytkownika należy wykonać trwale.',
'ipb_hide_invalid'                => 'Ukrycie konta tego użytkownika nie jest możliwe, prawdopodobnie wykonał on zbyt wiele edycji.',
'ipb_already_blocked'             => '„$1” jest już zablokowany',
'ipb-needreblock'                 => '== Istniejąca blokada ==
$1 jest już zablokowany. Czy chcesz zmienić ustawienia blokady?',
'ipb-otherblocks-header'          => '{{PLURAL:$1|Inna blokada|Inne blokady}}',
'ipb_cant_unblock'                => 'Błąd: Blokada o ID $1 nie została znaleziona. Mogła ona zostać zdjęta wcześniej.',
'ipb_blocked_as_range'            => 'Błąd – adres IP $1 nie został zablokowany bezpośrednio i nie może zostać odblokowany.
Należy on do zablokowanego zakresu adresów $2. Odblokować można tylko cały zakres.',
'ip_range_invalid'                => 'Niepoprawny zakres adresów IP.',
'ip_range_toolarge'               => 'Zakresy IP większe niż /$1 są niedozwolone.',
'blockme'                         => 'Zablokuj mnie',
'proxyblocker'                    => 'Blokowanie proxy',
'proxyblocker-disabled'           => 'Ta funkcja jest wyłączona.',
'proxyblockreason'                => 'Twój adres IP został zablokowany, ponieważ jest to adres otwartego proxy.
O tym poważnym problemie dotyczącym bezpieczeństwa należy poinformować dostawcę Internetu lub pomoc techniczną.',
'proxyblocksuccess'               => 'Wykonano.',
'sorbsreason'                     => 'Twój adres IP znajduje się na liście serwerów open proxy w DNSBL, używanej przez {{GRAMMAR:B.lp|{{SITENAME}}}}.',
'sorbs_create_account_reason'     => 'Twój adres IP znajduje się na liście serwerów open proxy w DNSBL, używanej przez {{GRAMMAR:B.lp|{{SITENAME}}}}.
Nie możesz utworzyć konta',
'cant-block-while-blocked'        => 'Nie możesz zablokować innych użytkowników, kiedy sam jesteś zablokowany.',
'cant-see-hidden-user'            => 'Konto użytkownika, które próbujesz zablokować, zostało już zablokowane oraz ukryte. Bez uprawnienia do ukrywania kont nie możesz zobaczyć oraz modyfikować blokady tego użytkownika.',
'ipbblocked'                      => 'Nie możesz blokować i odblokowywać innych użytkowników, ponieważ sam jesteś zablokowany',
'ipbnounblockself'                => 'Nie możesz odblokować samego siebie',

# Developer tools
'lockdb'              => 'Zablokuj bazę danych',
'unlockdb'            => 'Odblokuj bazę danych',
'lockdbtext'          => 'Zablokowanie bazy danych uniemożliwi wszystkim użytkownikom edycję stron, zmianę preferencji, edycję list obserwowanych stron oraz inne czynności wymagające dostępu do bazy danych.
Potwierdź, że to jest zgodne z Twoimi zamiarami, i że odblokujesz bazę danych, gdy tylko zakończysz zadania administracyjne.',
'unlockdbtext'        => 'Odblokowanie bazy danych umożliwi wszystkim użytkownikom edycję stron, zmianę preferencji, edycję list obserwowanych stron oraz inne czynności związane ze zmianami w bazie danych. Potwierdź, że to jest zgodne z Twoimi zamiarami.',
'lockconfirm'         => 'Tak, naprawdę chcę zablokować bazę danych.',
'unlockconfirm'       => 'Tak, naprawdę chcę odblokować bazę danych.',
'lockbtn'             => 'Zablokuj bazę danych',
'unlockbtn'           => 'Odblokuj bazę danych',
'locknoconfirm'       => 'Nie zaznacz{{GENDER:|yłeś|yłaś|ono}} potwierdzenia.',
'lockdbsuccesssub'    => 'Baza danych została pomyślnie zablokowana',
'unlockdbsuccesssub'  => 'Blokada bazy danych została zdjęta',
'lockdbsuccesstext'   => 'Baza danych została zablokowana.<br />
Pamiętaj by [[Special:UnlockDB|zdjąć blokadę]] po zakończeniu działań administracyjnych.',
'unlockdbsuccesstext' => 'Baza danych została odblokowana.',
'lockfilenotwritable' => 'Nie można zapisać pliku blokady bazy danych.
Blokowanie i odblokowywanie bazy danych, wymaga by plik mógł być zapisywany przez web serwer.',
'databasenotlocked'   => 'Baza danych nie jest zablokowana.',

# Move page
'move-page'                    => 'Przenieś $1',
'move-page-legend'             => 'Przeniesienie strony',
'movepagetext'                 => "Za pomocą poniższego formularza zmienisz nazwę strony, przenosząc jednocześnie jej historię.
Pod starym tytułem zostanie umieszczona strona przekierowująca.
Możesz automatycznie zaktualizować przekierowania wskazujące na tytuł przed zmianą.
Jeśli nie wybierzesz tej opcji, upewnij się po przeniesieniu strony, czy nie powstały [[Special:DoubleRedirects|podwójne]] lub [[Special:BrokenRedirects|zerwane przekierowania]].
Jesteś odpowiedzialny za to, by linki w dalszym ciągu pokazywały tam, gdzie powinny.

Strona '''nie''' zostanie przeniesiona, jeśli strona o nowej nazwie już istnieje, chyba że jest pusta lub jest przekierowaniem i ma pustą historię edycji.
To oznacza, że błędną operację zmiany nazwy można bezpiecznie odwrócić, zmieniając nową nazwę strony na poprzednią, i że nie można nadpisać istniejącej strony.

'''UWAGA!'''
Może to być drastyczna lub nieprzewidywalna zmiana w przypadku popularnych stron.
Upewnij się co do konsekwencji tej operacji, zanim się na nią zdecydujesz.",
'movepagetalktext'             => 'Powiązana strona dyskusji, jeśli istnieje, będzie przeniesiona automatycznie, chyba że:
*niepusta strona dyskusji już jest pod nową nazwą
*usuniesz zaznaczenie z poniższego pola wyboru

W takich przypadkach treść dyskusji można przenieść tylko ręcznie.',
'movearticle'                  => 'Przeniesienie strony',
'moveuserpage-warning'         => "'''Uwaga!''' Masz zamiar przenieść stronę użytkownika. Miej na uwadze, że zostanie przeniesiona tylko strona, a '''nazwa użytkownika pozostanie niezmieniona'''.",
'movenologin'                  => 'Nie jesteś zalogowany',
'movenologintext'              => 'Przenoszenie stron jest możliwe dopiero po zarejestrowaniu się i [[Special:UserLogin|zalogowaniu]].',
'movenotallowed'               => 'Nie masz uprawnień do przenoszenia stron.',
'movenotallowedfile'           => 'Nie masz uprawnień do przenoszenia plików.',
'cant-move-user-page'          => 'Nie masz uprawnień do przenoszenia stron użytkowników (za wyjątkiem podstron).',
'cant-move-to-user-page'       => 'Nie masz uprawnień do przenoszenia strony do strony użytkownika (za wyjątkiem podstron użytkownika).',
'newtitle'                     => 'Nowy tytuł',
'move-watch'                   => 'Obserwuj',
'movepagebtn'                  => 'Przenieś stronę',
'pagemovedsub'                 => 'Przeniesienie powiodło się',
'movepage-moved'               => "'''„$1” została przeniesiona do „$2”'''",
'movepage-moved-redirect'      => 'Zostało utworzone przekierowanie.',
'movepage-moved-noredirect'    => 'Nie zostało utworzone przekierowanie.',
'articleexists'                => 'Strona o podanej nazwie już istnieje albo wybrana przez Ciebie nazwa nie jest poprawna.
Wybierz inną nazwę.',
'cantmove-titleprotected'      => 'Nie możesz przenieść strony, ponieważ nowa nazwa strony jest niedozwolona z powodu zabezpieczenia przed utworzeniem',
'talkexists'                   => "'''Strona treści została przeniesiona, natomiast strona dyskusji nie – strona dyskusji o nowym tytule już istnieje. Połącz teksty obu dyskusji ręcznie.'''",
'movedto'                      => 'przeniesiono do',
'movetalk'                     => 'Przenieś także stronę dyskusji, jeśli to możliwe.',
'move-subpages'                => 'Przenieś podstrony (nie więcej niż $1)',
'move-talk-subpages'           => 'Przenieś strony dyskusji podstron (nie więcej niż $1)',
'movepage-page-exists'         => 'Strona $1 istnieje. Automatyczne nadpisanie nie jest możliwe.',
'movepage-page-moved'          => 'Strona $1 została przeniesiona do $2.',
'movepage-page-unmoved'        => 'Nazwa strony $1 nie może zostać zmieniona na $2.',
'movepage-max-pages'           => 'Przeniesionych zostało $1 {{PLURAL:$1|strona|strony|stron}}. Większa liczba nie może być przeniesiona automatycznie.',
'1movedto2'                    => '[[$1]] przeniesiono do [[$2]]',
'1movedto2_redir'              => '[[$1]] przeniesiono do [[$2]] nad przekierowaniem',
'move-redirect-suppressed'     => 'nie utworzono przekierowania pod starym tytułem',
'movelogpage'                  => 'Przeniesione',
'movelogpagetext'              => 'Lista stron, które ostatnio zostały przeniesione.',
'movesubpage'                  => '{{PLURAL:$1|Podstrona|Podstrony}}',
'movesubpagetext'              => 'Ta strona posiada $1 {{PLURAL:$1|podstronę|podstrony|podstron}}, {{PLURAL:$1|która została pokazana|które zostały pokazane}} poniżej.',
'movenosubpage'                => 'Ta strona nie posiada podstron.',
'movereason'                   => 'Powód',
'revertmove'                   => 'cofnij',
'delete_and_move'              => 'Usuń i przenieś',
'delete_and_move_text'         => '== Przeniesienie wymaga usunięcia innej strony ==
Strona docelowa „[[:$1]]” istnieje.
Czy chcesz ją usunąć, by zrobić miejsce dla przenoszonej strony?',
'delete_and_move_confirm'      => 'Tak, usuń stronę',
'delete_and_move_reason'       => 'Usunięto, by zrobić miejsce dla przenoszonej strony',
'selfmove'                     => 'Nazwy stron źródłowej i docelowej są takie same.
Strony nie można przenieść na nią samą.',
'immobile-source-namespace'    => 'Nie można przenieść stron w przestrzeni nazw „$1”',
'immobile-target-namespace'    => 'Nie można przenieść stron do przestrzeni nazw „$1”',
'immobile-target-namespace-iw' => 'Link interwiki jest nieprawidłowym tytułem, pod który miałaby być przeniesiona strona.',
'immobile-source-page'         => 'Tej strony nie można przenieść.',
'immobile-target-page'         => 'Nie można przenieść pod wskazany tytuł.',
'imagenocrossnamespace'        => 'Nie można przenieść grafiki do przestrzeni nazw nie przeznaczonej dla grafik',
'nonfile-cannot-move-to-file'  => 'Nie można przenieść obiektu nie będącego plikiem do przestrzeni nazw „{{ns:file}}“',
'imagetypemismatch'            => 'Nowe rozszerzenie nazwy pliku jest innego typu niż zawartość',
'imageinvalidfilename'         => 'Nazwa pliku docelowego jest nieprawidłowa',
'fix-double-redirects'         => 'Zaktualizuj wszystkie przekierowania wskazujące na stary tytuł',
'move-leave-redirect'          => 'Pozostaw przekierowanie pod dotychczasowym tytułem',
'protectedpagemovewarning'     => "'''UWAGA!''' Ponieważ strona została zabezpieczona, tylko użytkownicy z uprawnieniami administratora mogą zmienić jej nazwę.
Ostatni wpis z rejestru jest pokazany poniżej.",
'semiprotectedpagemovewarning' => "'''Uwaga!''' Ponieważ strona została zabezpieczona, tylko zarejestrowani użytkownicy mogą zmienić jej nazwę.
Ostatni wpis z rejestru jest pokazany poniżej.",
'move-over-sharedrepo'         => '== Plik istnieje ==
[[:$1]] istnieje we wspólnym repozytorium. Zmiana nazwy pliku na tę spowoduje przesłonięcie współdzielonego pliku.',
'file-exists-sharedrepo'       => 'Plik o wybranej nazwie istnieje we wspólnym repozytorium.
Wybierz inną nazwę.',

# Export
'export'            => 'Eksport stron',
'exporttext'        => 'Możesz wyeksportować treść i historię edycji jednej strony lub zestawu stron w formacie XML.
Wyeksportowane informacje można później zaimportować do innej wiki, działającej na oprogramowaniu MediaWiki, korzystając ze [[Special:Import|strony importu]].

Wyeksportowanie wielu stron wymaga wpisania poniżej tytułów stron po jednym tytule w wierszu oraz określenia, czy ma zostać wyeksportowana bieżąca czy wszystkie wersje strony z opisami edycji lub też tylko bieżąca wersja z opisem ostatniej edycji.

Możesz również użyć linku, np. [[{{#Special:Export}}/{{MediaWiki:Mainpage}}]] dla strony „[[{{MediaWiki:Mainpage}}]]”.',
'exportcuronly'     => 'Tylko bieżąca wersja, bez historii',
'exportnohistory'   => "----
'''Uwaga:''' Wyłączono możliwość eksportowania pełnej historii stron z użyciem tego narzędzia z powodu kłopotów z wydajnością.",
'export-submit'     => 'Eksportuj',
'export-addcattext' => 'Dodaj strony z kategorii',
'export-addcat'     => 'Dodaj',
'export-addnstext'  => 'Dodaj strony z przestrzeni nazw',
'export-addns'      => 'Dodaj',
'export-download'   => 'Zapisz do pliku',
'export-templates'  => 'Dołącz szablony',
'export-pagelinks'  => 'Dołącz linkowane strony na głębokości:',

# Namespace 8 related
'allmessages'                   => 'Komunikaty systemowe',
'allmessagesname'               => 'Nazwa',
'allmessagesdefault'            => 'Tekst domyślny',
'allmessagescurrent'            => 'Tekst obecny',
'allmessagestext'               => 'Lista wszystkich komunikatów systemowych dostępnych w przestrzeni nazw MediaWiki.
Odwiedź [http://www.mediawiki.org/wiki/Localisation Tłumaczenie MediaWiki] oraz [http://translatewiki.net translatewiki.net], jeśli chcesz uczestniczyć w tłumaczeniu oprogramowania MediaWiki.',
'allmessagesnotsupportedDB'     => "Ta strona nie może być użyta, ponieważ zmienna '''\$wgUseDatabaseMessages''' jest wyłączona.",
'allmessages-filter-legend'     => 'Filtr',
'allmessages-filter'            => 'Filtrowanie według stanu modyfikacji:',
'allmessages-filter-unmodified' => 'Niezmodyfikowane',
'allmessages-filter-all'        => 'Wszystkie',
'allmessages-filter-modified'   => 'Zmodyfikowane',
'allmessages-prefix'            => 'Tytuły rozpoczynające się od',
'allmessages-language'          => 'Język',
'allmessages-filter-submit'     => 'Pokaż',

# Thumbnails
'thumbnail-more'           => 'Powiększ',
'filemissing'              => 'Brak pliku',
'thumbnail_error'          => 'Błąd przy generowaniu miniatury $1',
'djvu_page_error'          => 'Strona DjVu poza zakresem',
'djvu_no_xml'              => 'Nie można pobrać danych w formacie XML dla pliku DjVu',
'thumbnail_invalid_params' => 'Nieprawidłowe parametry miniatury',
'thumbnail_dest_directory' => 'Nie można utworzyć katalogu docelowego',
'thumbnail_image-type'     => 'Grafika tego typu nie jest obsługiwana',
'thumbnail_gd-library'     => 'Niekompletna konfiguracja biblioteki GD – brak funkcji $1',
'thumbnail_image-missing'  => 'Chyba brakuje pliku $1',

# Special:Import
'import'                     => 'Import stron',
'importinterwiki'            => 'Import transwiki',
'import-interwiki-text'      => 'Wybierz wiki i nazwę strony do importowania.
Daty oraz nazwy autorów zostaną zachowane.
Wszystkie operacje importu transwiki są odnotowywane w [[Special:Log/import|rejestrze importu]].',
'import-interwiki-source'    => 'Źródło wiki/strony:',
'import-interwiki-history'   => 'Kopiuj całą historię edycji tej strony',
'import-interwiki-templates' => 'Załącz wszystkie szablony',
'import-interwiki-submit'    => 'Importuj',
'import-interwiki-namespace' => 'Docelowa przestrzeń nazw',
'import-upload-filename'     => 'Nazwa pliku',
'import-comment'             => 'Komentarz:',
'importtext'                 => 'Używając narzędzia [[Special:Export|eksportu]], wyeksportuj plik ze źródłowej wiki, zapisz go na swoim dysku, a następnie prześlij go tutaj.',
'importstart'                => 'Trwa importowanie stron...',
'import-revision-count'      => '$1 {{PLURAL:$1|wersja|wersje|wersji}}',
'importnopages'              => 'Brak stron do importu.',
'imported-log-entries'       => 'Zaimportowano $1 {{PLURAL:$1|wpis|wpisy|wpisów}} rejestru.',
'importfailed'               => 'Import nie powiódł się: $1',
'importunknownsource'        => 'Nieznany format importowanych danych',
'importcantopen'             => 'Nie można otworzyć importowanego pliku',
'importbadinterwiki'         => 'Błędny link interwiki',
'importnotext'               => 'Brak tekstu lub zawartości',
'importsuccess'              => 'Import zakończony powodzeniem!',
'importhistoryconflict'      => 'Wystąpił konflikt wersji (ta strona mogła zostać zaimportowana już wcześniej)',
'importnosources'            => 'Możliwość bezpośredniego importu historii została wyłączona, ponieważ nie zdefiniowano źródła.',
'importnofile'               => 'Importowany plik nie został przesłany.',
'importuploaderrorsize'      => 'Przesyłanie pliku importowanego zawiodło. Jest większy niż dopuszczalny rozmiar dla przesyłanych plików.',
'importuploaderrorpartial'   => 'Przesyłanie pliku importowanego zawiodło. Został przesłany tylko częściowo.',
'importuploaderrortemp'      => 'Przesyłanie pliku importowanego zawiodło.
Brak katalogu dla plików tymczasowych.',
'import-parse-failure'       => 'nieudana analiza składni importowanego XML',
'import-noarticle'           => 'Brak stron do zaimportowania!',
'import-nonewrevisions'      => 'Wszystkie wersje zostały już wcześniej zaimportowane.',
'xml-error-string'           => '$1 linia $2, kolumna $3 (bajt $4): $5',
'import-upload'              => 'Prześlij dane w formacie XML',
'import-token-mismatch'      => 'Utracono dane sesji. Proszę spróbować ponownie.',
'import-invalid-interwiki'   => 'Nie można importować z podanej wiki.',

# Import log
'importlogpage'                    => 'Rejestr importu',
'importlogpagetext'                => 'Rejestr przeprowadzonych importów stron z innych serwisów wiki.',
'import-logentry-upload'           => 'zaimportował [[$1]] przez przesłanie pliku',
'import-logentry-upload-detail'    => '$1 {{PLURAL:$1|wersja|wersje|wersji}}',
'import-logentry-interwiki'        => 'zaimportował $1 używając transwiki',
'import-logentry-interwiki-detail' => '$1 {{PLURAL:$1|wersja|wersje|wersji}} z $2',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'Moja osobista strona',
'tooltip-pt-anonuserpage'         => 'Strona użytkownika dla adresu IP, spod którego edytujesz',
'tooltip-pt-mytalk'               => 'Moja strona dyskusji',
'tooltip-pt-anontalk'             => 'Dyskusja użytkownika dla adresu IP, spod którego edytujesz',
'tooltip-pt-preferences'          => 'Moje preferencje',
'tooltip-pt-watchlist'            => 'Lista stron obserwowanych przez Ciebie',
'tooltip-pt-mycontris'            => 'Lista moich edycji',
'tooltip-pt-login'                => 'Zachęcamy do zalogowania się, choć nie jest to obowiązkowe.',
'tooltip-pt-anonlogin'            => 'Zachęcamy do zalogowania się, choć nie jest to obowiązkowe',
'tooltip-pt-logout'               => 'Wyloguj',
'tooltip-ca-talk'                 => 'Dyskusja o zawartości tej strony.',
'tooltip-ca-edit'                 => 'Możesz edytować tę stronę. Przed zapisaniem zmian użyj przycisku podgląd.',
'tooltip-ca-addsection'           => 'Dodaj nowy wątek.',
'tooltip-ca-viewsource'           => 'Ta strona jest zabezpieczona. Możesz zobaczyć tekst źródłowy.',
'tooltip-ca-history'              => 'Starsze wersje tej strony.',
'tooltip-ca-protect'              => 'Zabezpiecz tę stronę.',
'tooltip-ca-unprotect'            => 'Odbezpiecz tę stronę',
'tooltip-ca-delete'               => 'Usuń tę stronę',
'tooltip-ca-undelete'             => 'Przywróć wersję tej strony sprzed usunięcia',
'tooltip-ca-move'                 => 'Przenieś tę stronę.',
'tooltip-ca-watch'                => 'Dodaj tę stronę do listy obserwowanych',
'tooltip-ca-unwatch'              => 'Usuń tę stronę z listy obserwowanych',
'tooltip-search'                  => 'Przeszukaj {{GRAMMAR:B.lp|{{SITENAME}}}}',
'tooltip-search-go'               => 'Przejdź do strony o dokładnie takim tytule, o ile istnieje',
'tooltip-search-fulltext'         => 'Szukaj wprowadzonego tekstu w treści stron',
'tooltip-p-logo'                  => 'Strona główna',
'tooltip-n-mainpage'              => 'Zobacz stronę główną',
'tooltip-n-mainpage-description'  => 'Przejdź na stronę główną',
'tooltip-n-portal'                => 'O projekcie, co możesz zrobić, gdzie możesz znaleźć informacje',
'tooltip-n-currentevents'         => 'Informacje o aktualnych wydarzeniach',
'tooltip-n-recentchanges'         => 'Lista ostatnich zmian na {{GRAMMAR:D.lp|{{SITENAME}}}}.',
'tooltip-n-randompage'            => 'Pokaż stronę wybraną losowo',
'tooltip-n-help'                  => 'Tutaj możesz się wielu rzeczy dowiedzieć.',
'tooltip-t-whatlinkshere'         => 'Pokaż listę wszystkich stron linkujących do tej strony',
'tooltip-t-recentchangeslinked'   => 'Ostatnie zmiany w stronach, do których ta strona linkuje',
'tooltip-feed-rss'                => 'Kanał RSS dla tej strony',
'tooltip-feed-atom'               => 'Kanał Atom dla tej strony',
'tooltip-t-contributions'         => 'Pokaż listę edycji tego użytkownika',
'tooltip-t-emailuser'             => 'Wyślij e‐mail do tego użytkownika',
'tooltip-t-upload'                => 'Prześlij plik',
'tooltip-t-specialpages'          => 'Lista wszystkich specjalnych stron',
'tooltip-t-print'                 => 'Wersja do wydruku',
'tooltip-t-permalink'             => 'Stały link do tej wersji strony',
'tooltip-ca-nstab-main'           => 'Zobacz stronę treści',
'tooltip-ca-nstab-user'           => 'Zobacz stronę osobistą użytkownika',
'tooltip-ca-nstab-media'          => 'Zobacz stronę pliku',
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
'tooltip-diff'                    => 'Pokaż zmiany wykonane w tekście.',
'tooltip-compareselectedversions' => 'Pokazuje różnice między dwiema wybranymi wersjami tej strony.',
'tooltip-watch'                   => 'Dodaj tę stronę do listy obserwowanych',
'tooltip-recreate'                => 'Utwórz stronę pomimo jej wcześniejszego usunięcia.',
'tooltip-upload'                  => 'Rozpoczęcie przesyłania',
'tooltip-rollback'                => '„cofnij” jednym kliknięciem wycofuje wszystkie zmiany tej strony wykonane przez ostatniego edytującego.',
'tooltip-undo'                    => '„anuluj edycję” wycofuje tę edycję i otwiera okno edycji w trybie podglądu.
Pozwala na wpisanie powodu w opisie zmian.',
'tooltip-preferences-save'        => 'Zapisz preferencje',
'tooltip-summary'                 => 'Wpisz krótki opis',

# Stylesheets
'common.css'      => '/* Umieszczony tutaj kod CSS zostanie zastosowany we wszystkich skórkach */',
'standard.css'    => '/* Umieszczony tutaj kod CSS wpłynie na wygląd skórki Standardowa */',
'nostalgia.css'   => '/* Umieszczony tutaj kod CSS wpłynie na wygląd skórki Tęsknota */',
'cologneblue.css' => '/* Umieszczony tutaj kod CSS wpłynie na wygląd skórki Błękit */',
'monobook.css'    => '/* Umieszczony tutaj kod CSS wpłynie na wygląd skórki Książka */',
'myskin.css'      => '/* Umieszczony tutaj kod CSS wpłynie na wygląd skórki Moja skórka */',
'chick.css'       => '/* Umieszczony tutaj kod CSS wpłynie na wygląd skórki Kurczaczek */',
'simple.css'      => '/* Umieszczony tutaj kod CSS wpłynie na wygląd skórki Prosta */',
'modern.css'      => '/* Umieszczony tutaj kod CSS wpłynie na wygląd skórki Nowoczesna */',
'vector.css'      => '/* Umieszczony tutaj kod CSS wpłynie na wygląd skórki Wektor */',
'print.css'       => '/* Umieszczony tutaj kod CSS wpłynie na wygląd wydruku */',
'handheld.css'    => '/* Umieszczony tutaj kod CSS wpłynie na wygląd na urządzeniach kieszonkowych skórki ustawionej w zmiennej $wgHandheldStyle */',

# Scripts
'common.js'      => '/* Umieszczony tutaj kod JavaScript zostanie załadowany przez każdego użytkownika, podczas każdego ładowania strony. */',
'standard.js'    => '/* Umieszczony tutaj kod JavaScript zostanie załadowany wyłącznie przez użytkowników korzystających ze skórki Standardowa */',
'nostalgia.js'   => '/* Umieszczony tutaj kod JavaScript zostanie załadowany wyłącznie przez użytkowników korzystających ze skórki Tęsknota */',
'cologneblue.js' => '/* Umieszczony tutaj kod JavaScript zostanie załadowany wyłącznie przez użytkowników korzystających ze skórki Błękit */',
'monobook.js'    => '/* Umieszczony tutaj kod JavaScript zostanie załadowany wyłącznie przez użytkowników korzystających ze skórki Książka */',
'myskin.js'      => '/* Umieszczony tutaj kod JavaScript zostanie załadowany wyłącznie przez użytkowników korzystających ze skórki Moja skórka */',
'chick.js'       => '/* Umieszczony tutaj kod JavaScript zostanie załadowany wyłącznie przez użytkowników korzystających ze skórki Kurczaczek */',
'simple.js'      => '/* Umieszczony tutaj kod JavaScript zostanie załadowany wyłącznie przez użytkowników korzystających ze skórki Prosta */',
'modern.js'      => '/* Umieszczony tutaj kod JavaScript zostanie załadowany wyłącznie przez użytkowników korzystających ze skórki Nowoczesna */',
'vector.js'      => '/* Umieszczony tutaj kod JavaScript zostanie załadowany wyłącznie przez użytkowników korzystających ze skórki Wektor */',

# Metadata
'nodublincore'      => 'Metadane zgodne z Dublin Core RDF zostały wyłączone dla tego serwera.',
'nocreativecommons' => 'Metadane zgodne z Creative Commons RDF zostały wyłączone dla tego serwera.',
'notacceptable'     => 'Serwer wiki nie może dostarczyć danych w formacie, którego Twoja przeglądarka oczekuje.',

# Attribution
'anonymous'        => '{{PLURAL:$1|Anonimowy użytkownik|Anonimowi użytkownicy}} {{GRAMMAR:D.lp|{{SITENAME}}}}',
'siteuser'         => 'użytkownik {{GRAMMAR:D.lp|{{SITENAME}}}} – $1',
'anonuser'         => 'niezalogowany użytkownik {{GRAMMAR:D.lp|{{SITENAME}}}} – $1',
'lastmodifiedatby' => 'Ostatnia edycja tej strony: $2, $1 (autor zmian: $3)',
'othercontribs'    => 'Inni autorzy: $1.',
'others'           => 'inni',
'siteusers'        => '{{PLURAL:$2|użytkownik|użytkownicy}} {{GRAMMAR:D.lp|{{SITENAME}}}}{{PLURAL:$2||:}} $1',
'anonusers'        => '{{PLURAL:$2|niezalogowany użytkownik|niezalogowani użytkownicy}} {{GRAMMAR:D.lp|{{SITENAME}}}}{{PLURAL:$2||:}} $1',
'creditspage'      => 'Autorzy',
'nocredits'        => 'Brak informacji o autorach tej strony.',

# Spam protection
'spamprotectiontitle' => 'Filtr antyspamowy',
'spamprotectiontext'  => 'Strona, którą próbowałeś zapisać, została zablokowana przez filtr antyspamowy.
Najprawdopodobniej zostało to spowodowane przez link do zewnętrznej strony internetowej.',
'spamprotectionmatch' => 'Filtr antyspamowy zadziałał ponieważ odnalazł tekst: $1',
'spambot_username'    => 'MediaWiki – usuwanie spamu',
'spam_reverting'      => 'Przywracanie ostatniej wersji nie zawierającej linków do $1',
'spam_blanking'       => 'Wszystkie wersje zawierały odnośniki do $1. Czyszczenie strony.',

# Info page
'infosubtitle'   => 'Informacja o stronie',
'numedits'       => 'Liczba edycji (strona zawartości): $1',
'numtalkedits'   => 'Liczba edycji (strona dyskusji): $1',
'numwatchers'    => 'Liczba obserwujących: $1',
'numauthors'     => 'Liczba autorów (strona zawartości): $1',
'numtalkauthors' => 'Liczba autorów (strona dyskusji): $1',

# Skin names
'skinname-standard'    => 'Standardowa',
'skinname-nostalgia'   => 'Tęsknota',
'skinname-cologneblue' => 'Błękit',
'skinname-monobook'    => 'Książka',
'skinname-myskin'      => 'Moja skórka',
'skinname-chick'       => 'Kurczaczek',
'skinname-simple'      => 'Prosta',
'skinname-modern'      => 'Nowoczesna',
'skinname-vector'      => 'Wektor',

# Math options
'mw_math_png'    => 'Zawsze generuj grafikę PNG',
'mw_math_simple' => 'HTML dla prostych, dla pozostałych grafika PNG',
'mw_math_html'   => 'Spróbuj HTML, a jeśli zawiedzie użyj grafiki PNG',
'mw_math_source' => 'Pozostaw w TeXu (dla przeglądarek tekstowych)',
'mw_math_modern' => 'HTML – zalecane dla nowych przeglądarek',
'mw_math_mathml' => 'MathML jeśli dostępny (eksperymentalne)',

# Math errors
'math_failure'          => 'Parser nie mógł rozpoznać',
'math_unknown_error'    => 'nieznany błąd',
'math_unknown_function' => 'nieznana funkcja',
'math_lexing_error'     => 'błędna nazwa',
'math_syntax_error'     => 'błąd składni',
'math_image_error'      => 'Konwersja do formatu PNG nie powiodła się.
Sprawdź, czy poprawnie zainstalowane są latex, dvips, gs i convert.',
'math_bad_tmpdir'       => 'Nie można utworzyć lub zapisywać w tymczasowym katalogu dla wzorów matematycznych',
'math_bad_output'       => 'Nie można utworzyć lub zapisywać w wyjściowym katalogu dla wzorów matematycznych',
'math_notexvc'          => 'Brak programu texvc.
Zapoznaj się z math/README w celu konfiguracji.',

# Patrolling
'markaspatrolleddiff'                 => 'oznacz edycję jako „sprawdzoną”',
'markaspatrolledtext'                 => 'Oznacz tę stronę jako „sprawdzoną”',
'markedaspatrolled'                   => 'Sprawdzone',
'markedaspatrolledtext'               => 'Wybrana wersja [[:$1]] została oznaczona jako „sprawdzona”.',
'rcpatroldisabled'                    => 'Wyłączono funkcję patrolowania na stronie ostatnich zmian',
'rcpatroldisabledtext'                => 'Patrolowanie ostatnich zmian jest obecnie wyłączone.',
'markedaspatrollederror'              => 'Nie można oznaczyć jako „sprawdzone”',
'markedaspatrollederrortext'          => 'Musisz wybrać wersję żeby oznaczyć ją jako „sprawdzoną”.',
'markedaspatrollederror-noautopatrol' => 'Nie masz uprawnień wymaganych do oznaczania swoich edycji jako „sprawdzone”.',

# Patrol log
'patrol-log-page'      => 'Dziennik patrolowania',
'patrol-log-header'    => 'Poniżej znajduje się dziennik patrolowania stron.',
'patrol-log-line'      => 'oznaczył $1 hasła $2 jako sprawdzoną $3',
'patrol-log-auto'      => '(automatycznie)',
'patrol-log-diff'      => 'wersja $1',
'log-show-hide-patrol' => '$1 rejestr sprawdzania',

# Image deletion
'deletedrevision'                 => 'Usunięto poprzednie wersje $1',
'filedeleteerror-short'           => 'Błąd przy usuwaniu pliku $1',
'filedeleteerror-long'            => 'Wystąpiły błędy przy usuwaniu pliku:

$1',
'filedelete-missing'              => 'Pliku „$1” nie można usunąć, ponieważ nie istnieje.',
'filedelete-old-unregistered'     => 'Brak w bazie danych żądanej wersji pliku „$1”.',
'filedelete-current-unregistered' => 'Brak w bazie danych pliku „$1”.',
'filedelete-archive-read-only'    => 'Serwer WWW nie może zapisywać w katalogu z archiwami „$1”.',

# Browsing diffs
'previousdiff' => '← poprzednia edycja',
'nextdiff'     => 'następna edycja →',

# Media information
'mediawarning'         => "'''Uwaga!''' Plik w tym formacie może zawierać złośliwy kod.
Jeśli go otworzysz, możesz zarazić swój system.",
'imagemaxsize'         => "Ograniczenie wielkości obrazków<br />''(na stronach opisu plików)''",
'thumbsize'            => 'Rozmiar miniaturki',
'widthheightpage'      => '$1×$2, $3 {{PLURAL:$3|strona|strony|stron}}',
'file-info'            => '(rozmiar pliku: $1, typ MIME: $2)',
'file-info-size'       => '($1 × $2 pikseli, rozmiar pliku: $3, typ MIME: $4)',
'file-nohires'         => '<small>Grafika w wyższej rozdzielczości jest niedostępna.</small>',
'svg-long-desc'        => '(Plik SVG, nominalnie $1 × $2 pikseli, rozmiar pliku: $3)',
'show-big-image'       => 'Pełna rozdzielczość',
'show-big-image-thumb' => '<small>Rozmiar podglądu: $1 × $2 pikseli</small>',
'file-info-gif-looped' => 'zapętlony',
'file-info-gif-frames' => '$1 {{PLURAL:$1|klatka|klatki|klatek}}',
'file-info-png-looped' => 'zapętlony',
'file-info-png-repeat' => 'powtarzany $1 {{PLURAL:$1|raz|razy}}',
'file-info-png-frames' => '$1 {{PLURAL:$1|klatka|klatki|klatek}}',

# Special:NewFiles
'newimages'             => 'Najnowsze pliki',
'imagelisttext'         => "Poniżej na {{PLURAL:$1||posortowanej $2}} liście {{PLURAL:$1|znajduje|znajdują|znajduje}} się '''$1''' {{PLURAL:$1|plik|pliki|plików}}.",
'newimages-summary'     => 'Na tej stronie specjalnej prezentowane są ostatnio przesłane pliki.',
'newimages-legend'      => 'Filtruj',
'newimages-label'       => 'Nazwa pliku (lub jej fragment)',
'showhidebots'          => '($1 boty)',
'noimages'              => 'Brak plików do pokazania.',
'ilsubmit'              => 'Szukaj',
'bydate'                => 'według daty',
'sp-newimages-showfrom' => 'pokaż nowe pliki począwszy od $2, $1',

# Video information, used by Language::formatTimePeriod() to format lengths in the above messages
'seconds-abbrev' => '&nbsp;s',
'minutes-abbrev' => '&nbsp;min',
'hours-abbrev'   => '&nbsp;h',

# Bad image list
'bad_image_list' => 'Dane należy wprowadzić w formacie:

Jedynie elementy listy (linie zaczynające się od znaku gwiazdki *) brane są pod uwagę.
Pierwszy link w linii musi być linkiem do zabronionego pliku.
Następne linki w linii są traktowane jako wyjątki – są to nazwy stron, na których plik o zabronionej nazwie może być użyty.',

# Metadata
'metadata'          => 'Metadane',
'metadata-help'     => 'Niniejszy plik zawiera dodatkowe informacje, prawdopodobnie dodane przez aparat cyfrowy lub skaner użyte do wygenerowania tego pliku.
Jeśli plik był modyfikowany, dane mogą być częściowo niezgodne z parametrami zmodyfikowanego pliku.',
'metadata-expand'   => 'Pokaż szczegóły',
'metadata-collapse' => 'Ukryj szczegóły',
'metadata-fields'   => 'Wymienione poniżej pola EXIF będą prezentowane na stronie grafiki.
Pozostałe pola zostaną domyślnie ukryte.
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* isospeedratings
* focallength',

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
'exif-ycbcrpositioning'            => 'Rozmieszczenie Y i C',
'exif-xresolution'                 => 'Rozdzielczość w poziomie',
'exif-yresolution'                 => 'Rozdzielczość w pionie',
'exif-resolutionunit'              => 'Jednostka rozdzielczości X i Y',
'exif-stripoffsets'                => 'Przesunięcie pasów obrazu',
'exif-rowsperstrip'                => 'Liczba wierszy na pas obrazu',
'exif-stripbytecounts'             => 'Liczba bajtów na pas obrazu',
'exif-jpeginterchangeformat'       => 'Położenie pierwszego bajtu miniaturki obrazu',
'exif-jpeginterchangeformatlength' => 'Liczba bajtów miniaturki JPEG',
'exif-transferfunction'            => 'Funkcja przejścia',
'exif-whitepoint'                  => 'Punkt bieli',
'exif-primarychromaticities'       => 'Kolory trzech barw głównych',
'exif-ycbcrcoefficients'           => 'Macierz współczynników transformacji barw z RGB na YCbCr',
'exif-referenceblackwhite'         => 'Wartość punktu odniesienia czerni i bieli',
'exif-datetime'                    => 'Data i czas modyfikacji pliku',
'exif-imagedescription'            => 'Tytuł lub opis obrazu',
'exif-make'                        => 'Producent aparatu',
'exif-model'                       => 'Model aparatu',
'exif-software'                    => 'Użyte oprogramowanie',
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
'exif-subsectime'                  => 'Data i czas modyfikacji pliku – ułamki sekund',
'exif-subsectimeoriginal'          => 'Data i czas utworzenia oryginału – ułamki sekund',
'exif-subsectimedigitized'         => 'Data i czas zeskanowania – ułamki sekund',
'exif-exposuretime'                => 'Czas ekspozycji',
'exif-exposuretime-format'         => '$1 s ($2)',
'exif-fnumber'                     => 'Wartość przysłony',
'exif-fnumber-format'              => 'f&nbsp;/&nbsp;$1',
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
'exif-focallength-format'          => '$1&nbsp;mm',
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

'exif-orientation-1' => 'normalna',
'exif-orientation-2' => 'odbicie lustrzane w poziomie',
'exif-orientation-3' => 'obraz obrócony o 180°',
'exif-orientation-4' => 'odbicie lustrzane w pionie',
'exif-orientation-5' => 'obraz obrócony o 90° przeciwnie do ruchu wskazówek zegara i odbicie lustrzane w pionie',
'exif-orientation-6' => 'obraz obrócony o 90° zgodnie z ruchem wskazówek zegara',
'exif-orientation-7' => 'obrót o 90° zgodnie ze wskazówkami zegara i odbicie lustrzane w pionie',
'exif-orientation-8' => 'obrót o 90° przeciwnie do wskazówek zegara',

'exif-planarconfiguration-1' => 'format masywny',
'exif-planarconfiguration-2' => 'format powierzchniowy',

'exif-xyresolution-i' => '$1&nbsp;punktów na cal',
'exif-xyresolution-c' => '$1&nbsp;punktów na centymetr',

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

# Flash modes
'exif-flash-fired-0'    => 'Bez błysku flesza',
'exif-flash-fired-1'    => 'Z błyskiem flesza',
'exif-flash-return-0'   => 'bez funkcji wykrywania światła odbitego',
'exif-flash-return-2'   => 'nie wykryto światła odbitego',
'exif-flash-return-3'   => 'wykryto światło odbite',
'exif-flash-mode-1'     => 'wymuszony błysk flesza',
'exif-flash-mode-2'     => 'wymuszony brak błysku flesza',
'exif-flash-mode-3'     => 'tryb automatyczny',
'exif-flash-function-1' => 'Brak funkcji flesza',
'exif-flash-redeye-1'   => 'tryb redukcji efektu czerwonych oczu',

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

# Pseudotags used for GPSSpeedRef
'exif-gpsspeed-k' => 'kilometrów na godzinę',
'exif-gpsspeed-m' => 'mil na godzinę',
'exif-gpsspeed-n' => 'węzłów',

# Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
'exif-gpsdirection-t' => 'kierunek geograficzny',
'exif-gpsdirection-m' => 'kierunek magnetyczny',

# External editor support
'edit-externally'      => 'Edytuj plik, używając zewnętrznej aplikacji',
'edit-externally-help' => '(Więcej informacji o używaniu [http://www.mediawiki.org/wiki/Manual:External_editors zewnętrznych edytorów]).',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'wszystkie',
'imagelistall'     => 'wszystkich',
'watchlistall2'    => 'wszystkie',
'namespacesall'    => 'wszystkie',
'monthsall'        => 'wszystkie',
'limitall'         => 'wszystkie',

# E-mail address confirmation
'confirmemail'              => 'Potwierdzanie adresu e‐mail',
'confirmemail_noemail'      => 'Nie {{GENDER:|podałeś|podałaś}} prawidłowego adresu e‐mail w [[Special:Preferences|preferencjach]].',
'confirmemail_text'         => 'Projekt {{SITENAME}} wymaga weryfikacji adresu e‐mail przed użyciem funkcji korzystających z poczty.
Wciśnij przycisk poniżej aby wysłać na swój adres list z linkiem do strony WWW.
List będzie zawierał link do strony, w którym zakodowany będzie identyfikator.
Otwórz ten link w przeglądarce, czym potwierdzisz, że jesteś użytkownikiem tego adresu e‐mail.',
'confirmemail_pending'      => 'Kod potwierdzenia został właśnie do Ciebie wysłany. Jeśli zarejestrował{{GENDER:|eś|aś|eś(‐aś)}} się niedawno, poczekaj kilka minut na dostarczenie wiadomości przed kolejną prośbą o wysłanie kodu.',
'confirmemail_send'         => 'Wyślij kod potwierdzenia',
'confirmemail_sent'         => 'Wiadomość e‐mail z kodem uwierzytelniającym została wysłana.',
'confirmemail_oncreate'     => 'Link z kodem potwierdzenia został wysłany na Twój adres e‐mail.
Kod ten nie jest wymagany do zalogowania się, jednak będziesz musiał go aktywować otwierając, otrzymany link, w przeglądarce przed włączeniem niektórych opcji e‐mail na wiki.',
'confirmemail_sendfailed'   => 'Nie udało się wysłać potwierdzającej wiadomości e‐mail.
Sprawdź poprawność adresu pod kątem literówki.

System pocztowy zwrócił komunikat: $1',
'confirmemail_invalid'      => 'Błędny kod potwierdzenia.
Kod może być przedawniony.',
'confirmemail_needlogin'    => 'Musisz $1 aby potwierdzić adres email.',
'confirmemail_success'      => 'Adres e‐mail został potwierdzony.
Możesz [[Special:UserLogin|zalogować się]] i korzystać z szerszego wachlarza funkcji wiki.',
'confirmemail_loggedin'     => 'Twój adres email został zweryfikowany.',
'confirmemail_error'        => 'Pojawiły się błędy przy zapisywaniu potwierdzenia.',
'confirmemail_subject'      => '{{SITENAME}} – weryfikacja adresu e‐mail',
'confirmemail_body'         => 'Ktoś łącząc się z komputera o adresie IP $1
zarejestrował w {{GRAMMAR:MS.lp|{{SITENAME}}}} konto „$2” podając niniejszy adres e‐mail.

Aby potwierdzić, że to Ty {{GENDER:|zarejestrowałeś|zarejestrowałaś}} to konto oraz, aby włączyć
wszystkie funkcje korzystające z poczty elektronicznej, otwórz w swojej
przeglądarce ten link:

$3

Jeśli to *nie* Ty {{GENDER:|zarejestrowałeś|zarejestrowałaś}} konto, otwórz w swojej przeglądarce
poniższy link, aby anulować potwierdzenie adresu e‐mail:

$5

Kod zawarty w linku straci ważność $4.',
'confirmemail_body_changed' => 'Ktoś łącząc się z komputera o adresie IP $1
zmienił w {{GRAMMAR:MS.lp|{{SITENAME}}}} ustawiony dla konta „$2” adres e‐mail na ten właśnie.

Aby potwierdzić, że to Ty {{GENDER:|zmieniłeś|zmieniłaś}} adres otwórz w swojej
przeglądarce ten link:

$3

Jeśli *nie* jest to Twoje konto, otwórz w swojej przeglądarce
poniższy link, aby anulować potwierdzenie adresu e‐mail:

$5

Kod zawarty w linku straci ważność $4.',
'confirmemail_invalidated'  => 'Potwierdzenie adresu e‐mail zostało anulowane',
'invalidateemail'           => 'Anulowanie potwierdzenia adresu e‐mail',

# Scary transclusion
'scarytranscludedisabled' => '[Transkluzja przez interwiki jest wyłączona]',
'scarytranscludefailed'   => '[Pobranie szablonu dla $1 nie powiodło się]',
'scarytranscludetoolong'  => '[zbyt długi adres URL]',

# Trackbacks
'trackbackbox'      => 'Komunikaty TrackBack dla tej strony:<br />
$1',
'trackbackremove'   => '([$1 Usuń])',
'trackbacklink'     => 'TrackBack',
'trackbackdeleteok' => 'TrackBack został usunięty.',

# Delete conflict
'deletedwhileediting' => "'''Uwaga!''' Ta strona została usunięta po tym, jak rozpoczął{{GENDER:|eś|aś|eś(‐aś)}} jej edycję!",
'confirmrecreate'     => "[[User:$1|$1]] ([[User talk:$1|dyskusja]]) usun{{GENDER:$1|ął|ęła|ął(‐ęła)}} tę stronę po tym, jak rozpoczął{{GENDER:|eś|aś|eś(‐aś)}} jego edycję, podając jako powód usunięcia:
: ''$2''
Czy na pewno chcesz ją ponownie utworzyć?",
'recreate'            => 'Utwórz ponownie',

# action=purge
'confirm_purge_button' => 'Wyczyść',
'confirm-purge-top'    => 'Wyczyścić pamięć podręczną dla tej strony?',
'confirm-purge-bottom' => 'Odświeżenie strony wyczyści pamięć podręczną i wymusi pokazanie jej aktualnej wersji.',

# Separators for various lists, etc.
'autocomment-prefix' => '–&#32;',
'percent'            => '$1&nbsp;%',

# Multipage image navigation
'imgmultipageprev' => '← poprzednia strona',
'imgmultipagenext' => 'następna strona →',
'imgmultigo'       => 'Przejdź',
'imgmultigoto'     => 'Idź do $1 strony',

# Table pager
'ascending_abbrev'         => 'rosn.',
'descending_abbrev'        => 'mal.',
'table_pager_next'         => 'Następna strona',
'table_pager_prev'         => 'Poprzednia strona',
'table_pager_first'        => 'Pierwsza strona',
'table_pager_last'         => 'Ostatnia strona',
'table_pager_limit'        => 'Pokaż $1 pozycji na stronie',
'table_pager_limit_label'  => 'Pozycji na stronie',
'table_pager_limit_submit' => 'Pokaż',
'table_pager_empty'        => 'Brak wyników',

# Auto-summaries
'autosumm-blank'   => 'UWAGA! Usunięcie treści (strona pozostała pusta)!',
'autosumm-replace' => 'UWAGA! Zastąpienie treści hasła bardzo krótkim tekstem: „$1”',
'autoredircomment' => 'Przekierowanie do [[$1]]',
'autosumm-new'     => 'Utworzył nową stronę „$1”',

# Size units
'size-bytes'     => '$1&nbsp;B',
'size-kilobytes' => '$1&nbsp;KB',
'size-megabytes' => '$1&nbsp;MB',
'size-gigabytes' => '$1&nbsp;GB',

# Live preview
'livepreview-loading' => 'Trwa ładowanie…',
'livepreview-ready'   => 'Trwa ładowanie… Gotowe!',
'livepreview-failed'  => 'Podgląd na żywo nie zadziałał! Spróbuj podglądu standardowego.',
'livepreview-error'   => 'Nieudane połączenie: $1 „$2” Spróbuj podglądu standardowego.',

# Friendlier slave lag warnings
'lag-warn-normal' => 'Zmiany nowsze niż $1 {{PLURAL:$1|sekunda|sekundy|sekund}} mogą nie być widoczne na tej liście.',
'lag-warn-high'   => 'Z powodu dużego obciążenia serwerów bazy danych, zmiany nowsze niż $1 {{PLURAL:$1|sekunda|sekundy|sekund}} mogą nie być widoczne na tej liście.',

# Watchlist editor
'watchlistedit-numitems'       => 'Lista obserwowanych przez Ciebie stron zawiera {{PLURAL:$1|1 stronę|$1 strony|$1 stron}}, nie licząc stron dyskusji.',
'watchlistedit-noitems'        => 'Twoja lista obserwowanych jest pusta.',
'watchlistedit-normal-title'   => 'Edytuj listę obserwowanych stron',
'watchlistedit-normal-legend'  => 'Usuń strony z listy obserwowanych',
'watchlistedit-normal-explain' => 'Poniżej znajduje się lista obserwowanych przez Ciebie stron.
Aby usunąć stronę z listy zaznacz znajdujące się obok niej pole i naciśnij „{{int:Watchlistedit-normal-submit}}”.
Możesz także skorzystać z [[Special:Watchlist/raw|tekstowego edytora listy obserwowanych]].',
'watchlistedit-normal-submit'  => 'Usuń zaznaczone z listy',
'watchlistedit-normal-done'    => 'Z Twojej listy obserwowanych {{PLURAL:$1|została usunięta 1 strona|zostały usunięte $1 strony|zostało usuniętych $1 stron}}:',
'watchlistedit-raw-title'      => 'Tekstowy edytor listy obserwowanych',
'watchlistedit-raw-legend'     => 'Tekstowy edytor listy obserwowanych',
'watchlistedit-raw-explain'    => 'Poniżej wypisane zostały tytuły stron znajdujących się na Twojej liście obserwowanych. Możesz dodać lub usunąć dowolny tytuł z tej listy – jeden wiersz to jeden tytuł.
Aby zatwierdzić zmiany kliknij „{{int:Watchlistedit-raw-submit}}”.
Możesz także użyć [[Special:Watchlist/edit|standardowego edytora obserwowanych stron]].',
'watchlistedit-raw-titles'     => 'Obserwowane strony:',
'watchlistedit-raw-submit'     => 'Uaktualnij listę',
'watchlistedit-raw-done'       => 'Lista obserwowanych stron została uaktualniona.',
'watchlistedit-raw-added'      => 'Dodano {{PLURAL:$1|1 pozycję|$1 pozycje|$1 pozycji}} do listy obserwowanych:',
'watchlistedit-raw-removed'    => 'Usunięto {{PLURAL:$1|1 pozycję|$1 pozycje|$1 pozycji}} z listy obserwowanych:',

# Watchlist editing tools
'watchlisttools-view' => 'pokaż zmiany na liście obserwowanych',
'watchlisttools-edit' => 'edycja listy',
'watchlisttools-raw'  => 'tekstowy edytor listy',

# Iranian month names
'iranian-calendar-m1'  => 'Farwardin',
'iranian-calendar-m2'  => 'Ordibeheszt',
'iranian-calendar-m3'  => 'Chordād',
'iranian-calendar-m5'  => 'Mordād',
'iranian-calendar-m6'  => 'Szahriwar',
'iranian-calendar-m8'  => 'Ābān',
'iranian-calendar-m9'  => 'Āsar',
'iranian-calendar-m10' => 'Déi',

# Hijri month names
'hijri-calendar-m3'  => 'Rabi al-awwal',
'hijri-calendar-m4'  => 'Rabi al-achira',
'hijri-calendar-m5'  => 'Dżumada al-ula',
'hijri-calendar-m6'  => 'Dżumada al-achira',
'hijri-calendar-m7'  => 'Radżab',
'hijri-calendar-m8'  => 'Szaban',
'hijri-calendar-m10' => 'Szawwal',
'hijri-calendar-m11' => 'Zu al-kada',
'hijri-calendar-m12' => 'Zu al-hidżdża',

# Hebrew month names
'hebrew-calendar-m1'      => 'Tiszri',
'hebrew-calendar-m2'      => 'Cheszwan',
'hebrew-calendar-m3'      => 'Kislew',
'hebrew-calendar-m4'      => 'Tewet',
'hebrew-calendar-m5'      => 'Szewat',
'hebrew-calendar-m8'      => 'Ijar',
'hebrew-calendar-m9'      => 'Siwan',
'hebrew-calendar-m11'     => 'Aw',
'hebrew-calendar-m1-gen'  => 'Tiszri',
'hebrew-calendar-m2-gen'  => 'Cheszwan',
'hebrew-calendar-m3-gen'  => 'Kislew',
'hebrew-calendar-m4-gen'  => 'Tewet',
'hebrew-calendar-m5-gen'  => 'Szewat',
'hebrew-calendar-m8-gen'  => 'Ijar',
'hebrew-calendar-m9-gen'  => 'Siwan',
'hebrew-calendar-m11-gen' => 'Aw',

# Core parser functions
'unknown_extension_tag' => 'Nieznany znacznik rozszerzenia „$1”',
'duplicate-defaultsort' => 'Uwaga: Domyślnym kluczem sortowania będzie „$2” i zastąpi on wcześniej wykorzystywany klucz „$1”.',

# Special:Version
'version'                          => 'Wersja oprogramowania',
'version-extensions'               => 'Zainstalowane rozszerzenia',
'version-specialpages'             => 'Strony specjalne',
'version-parserhooks'              => 'Haki analizatora składni (ang. parser hooks)',
'version-variables'                => 'Zmienne',
'version-other'                    => 'Pozostałe',
'version-mediahandlers'            => 'Wtyczki obsługi mediów',
'version-hooks'                    => 'Haki (ang. hooks)',
'version-extension-functions'      => 'Funkcje rozszerzeń',
'version-parser-extensiontags'     => 'Znaczniki rozszerzeń dla analizatora składni',
'version-parser-function-hooks'    => 'Funkcje haków analizatora składni (ang. parser function hooks)',
'version-skin-extension-functions' => 'Funkcje rozszerzeń skórek',
'version-hook-name'                => 'Nazwa haka (ang. hook name)',
'version-hook-subscribedby'        => 'Zapotrzebowany przez',
'version-version'                  => '(Wersja $1)',
'version-license'                  => 'Licencja',
'version-poweredby-credits'        => "To wiki korzysta z oprogramowania '''[http://www.mediawiki.org/ MediaWiki]''', copyright © 2001‐$1 $2.",
'version-poweredby-others'         => 'inni',
'version-license-info'             => 'MediaWiki jest wolnym oprogramowaniem – możesz je dystrybuować i modyfikować zgodnie z warunkami licencji GNU General Public License opublikowanej przez Free Software Foundation w wersji 2 tej licencji lub (jeśli wolisz) dowolnej późniejszej.

MediaWiki jest dystrybuowane w nadziei, że okaże się użyteczne ale BEZ JAKIEJKOLWIEK GWARANCJI – nawet bez domyślnej gwarancji PRZYDATNOŚCI HANDLOWEJ lub PRZYDATNOŚCI DO OKREŚLONYCH ZASTOSOWAŃ. Więcej szczegółów znajdziesz w treści licencji GNU General Public License.

Powinieneś otrzymać [{{SERVER}}{{SCRIPTPATH}}/COPYING kopię licencji GNU General Public License] wraz z niniejszym oprogramowaniem. Jeśli tak się nie stało, napisz do Free Software Foundation, Inc, 51 Franklin Street, Fifth Floor , Boston, MA 02110-1301, USA lub [http://www.gnu.org/licenses/old-licenses/gpl-2.0.html przeczytaj licencję w Internecie].',
'version-software'                 => 'Zainstalowane oprogramowanie',
'version-software-product'         => 'Nazwa',
'version-software-version'         => 'Wersja',

# Special:FilePath
'filepath'         => 'Ścieżka do pliku',
'filepath-page'    => 'Plik',
'filepath-submit'  => 'Przejdź',
'filepath-summary' => 'Ta strona specjalna zwraca pełną ścieżkę do pliku.
Grafiki są pokazywane w pełnej rozdzielczości, inne typy plików są otwierane w skojarzonym z nimi programie.

Wpisz nazwę pliku bez prefiksu „{{ns:file}}:”.',

# Special:FileDuplicateSearch
'fileduplicatesearch'          => 'Szukaj duplikatów pliku',
'fileduplicatesearch-summary'  => 'Szukaj duplikatów pliku na podstawie wartości funkcji skrótu.

Wpisz nazwę pliku z pominięciem prefiksu „{{ns:file}}:”.',
'fileduplicatesearch-legend'   => 'Szukaj duplikatów pliku',
'fileduplicatesearch-filename' => 'Nazwa pliku',
'fileduplicatesearch-submit'   => 'Szukaj',
'fileduplicatesearch-info'     => '$1 × $2 pikseli<br />Wielkość pliku: $3<br />Typ MIME: $4',
'fileduplicatesearch-result-1' => 'Brak duplikatu pliku „$1”.',
'fileduplicatesearch-result-n' => 'W {{GRAMMAR:MS.lp|{{SITENAME}}}} {{PLURAL:$2|jest dodatkowa kopia|są $2 dodatkowe kopie|jest $2 dodatkowych kopii}} pliku „$1”.',

# Special:SpecialPages
'specialpages'                   => 'Strony specjalne',
'specialpages-note'              => '----
* Strony specjalne ogólnie dostępne.
* <strong class="mw-specialpagerestricted">Strony specjalne o ograniczonym dostępie.</strong>',
'specialpages-group-maintenance' => 'Raporty konserwacyjne',
'specialpages-group-other'       => 'Inne strony specjalne',
'specialpages-group-login'       => 'Logowanie i rejestracja',
'specialpages-group-changes'     => 'Ostatnie zmiany i rejestry',
'specialpages-group-media'       => 'Pliki',
'specialpages-group-users'       => 'Użytkownicy i uprawnienia',
'specialpages-group-highuse'     => 'Strony często używane',
'specialpages-group-pages'       => 'Zestawienia stron',
'specialpages-group-pagetools'   => 'Narzędzia stron',
'specialpages-group-wiki'        => 'Informacje oraz narzędzia wiki',
'specialpages-group-redirects'   => 'Specjalne strony przekierowujące',
'specialpages-group-spam'        => 'Narzędzia do walki ze spamem',

# Special:BlankPage
'blankpage'              => 'Pusta strona',
'intentionallyblankpage' => 'Ta strona umyślnie pozostała pusta',

# External image whitelist
'external_image_whitelist' => ' #Pozostaw tę linię dokładnie tak, jak jest.<pre>
#Wstaw poniżej fragmenty wyrażeń regularnych (tylko to, co znajduje się między //).
#Wyrażenia te zostaną dopasowane do adresów URL zewnętrznych (bezpośrednio linkowanych) grafik.
#Dopasowane adresy URL zostaną wyświetlone jako grafiki, w przeciwnym wypadku będzie pokazany jedynie link do grafiki.
#Linie zaczynające się od # są traktowane jako komentarze.
#We wpisach ma znaczenie wielkość znaków.

#Wstaw wszystkie deklaracje wyrażeniami regularnymi poniżej tej linii. Pozostaw tę linię dokładnie tak, jak jest.</pre>',

# Special:Tags
'tags'                    => 'Sprawdź zmiany w oparciu o wzorce tekstu',
'tag-filter'              => 'Filtr [[Special:Tags|wzorców tekstu]]',
'tag-filter-submit'       => 'Filtr',
'tags-title'              => 'Wzorce tekstu',
'tags-intro'              => 'Na tej stronie znajduje się lista wzorców tekstu, dla których oprogramowanie może oznaczyć edycje, dodatkowo wskazując ich znaczenie.',
'tags-tag'                => 'Nazwa znacznika',
'tags-display-header'     => 'Wystąpienia na listach zmian',
'tags-description-header' => 'Pełny opis znaczenia',
'tags-hitcount-header'    => 'Oznaczone zmiany',
'tags-edit'               => 'edytuj',
'tags-hitcount'           => '$1 {{PLURAL:$1|zmiana|zmiany|zmian}}',

# Special:ComparePages
'comparepages'     => 'Porównanie stron',
'compare-selector' => 'Porównanie wersji stron',
'compare-page1'    => 'Strona 1',
'compare-page2'    => 'Strona 2',
'compare-rev1'     => 'Wersja 1',
'compare-rev2'     => 'Wersja 2',
'compare-submit'   => 'Porównaj',

# Database error messages
'dberr-header'      => 'Ta wiki nie działa poprawnie',
'dberr-problems'    => 'Przepraszamy! Witryna ma problemy techniczne.',
'dberr-again'       => 'Spróbuj przeładować stronę za kilka minut.',
'dberr-info'        => '(Brak komunikacji z serwerem bazy danych – $1)',
'dberr-usegoogle'   => 'Możesz spróbować wyszukać w międzyczasie za pomocą Google.',
'dberr-outofdate'   => 'Uwaga – indeksy zawartości serwisu mogą być nieaktualne.',
'dberr-cachederror' => 'Strona została pobrana z pamięci podręcznej i może być nieaktualna.',

# HTML forms
'htmlform-invalid-input'       => 'Wystąpił problem z wprowadzonymi danymi',
'htmlform-select-badoption'    => 'Podano nieprawidłową wartość.',
'htmlform-int-invalid'         => 'Podano wartość, która nie jest liczbą całkowitą.',
'htmlform-float-invalid'       => 'Podana wartość nie jest liczbą.',
'htmlform-int-toolow'          => 'Podana wartość jest poniżej dopuszczalnego minimum $1',
'htmlform-int-toohigh'         => 'Podana wartość jest powyżej dopuszczalnego maximum $1',
'htmlform-required'            => 'Podanie tej wartości jest wymagane',
'htmlform-submit'              => 'Zapisz',
'htmlform-reset'               => 'Cofnij zmiany',
'htmlform-selectorother-other' => 'Inne',

);
