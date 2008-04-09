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
 * @author Masti
 * @author Matma Rex
 * @author Szczepan1990
 * @author Leinad
 * @author Herr Kriss
 * @author Lajsikonik
 * @author Equadus
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
	'Imagelist'                 => array( 'Pliki' ),
	'Newimages'                 => array( 'Nowe_pliki' ),
	'Listusers'                 => array( 'Użytkownicy' ),
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
	'Mostlinked'                => array( 'Najczęściej_linkowane' ),
	'Mostlinkedcategories'      => array( 'Najczęściej_linkowane_kategorie' ),
	'Mostlinkedtemplates'       => array( 'Najczęściej_linkowane_szablony' ),
	'Mostcategories'            => array( 'Najwięcej_kategorii' ),
	'Mostimages'                => array( 'Najczęściej_linkowane_pliki' ),
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
	'Import'                    => array( 'Import' ),
	'Lockdb'                    => array( 'Zablokuj_bazę' ),
	'Unlockdb'                  => array( 'Odblokuj_bazę' ),
	'Userrights'                => array( 'Uprawnienia', 'Prawa_użytkowników' ),
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
	'Popularpages'              => array( 'Pupularne_strony' ),
	'Search'                    => array( 'Szukaj' ),
	'Resetpass'                 => array( 'Resetuj_hasło' ),
	'Withoutinterwiki'          => array( 'Strony_bez_interwiki' ),
	'MergeHistory'              => array( 'Połącz_historię' ),
	'Filepath'                  => array( 'Ścieżka_do_pliku' ),
);

$messages = array(
# User preference toggles
'tog-underline'               => 'Podkreślenie linków',
'tog-highlightbroken'         => 'Oznacz <a href="" class="new">tak</a> linki do brakujących stron (alternatywa: dołączany znak zapytania<a href="" class="internal">?</a>).',
'tog-justify'                 => 'Wyrównuj tekst w akapitach do obu stron',
'tog-hideminor'               => 'Ukryj drobne poprawki w „Ostatnich zmianach”',
'tog-extendwatchlist'         => 'Rozszerzona lista obserwowanych',
'tog-usenewrc'                => 'Rozszerzenie ostatnich zmian (JavaScript)',
'tog-numberheadings'          => 'Automatyczna numeracja nagłówków',
'tog-showtoolbar'             => 'Pokaż pasek narzędzi (JavaScript)',
'tog-editondblclick'          => 'Podwójne kliknięcie rozpoczyna edycję (JavaScript)',
'tog-editsection'             => 'Możliwość edycji poszczególnych sekcji strony',
'tog-editsectiononrightclick' => 'Kliknięcie prawym klawiszem myszy na tytule sekcji rozpoczyna jej edycję (JavaScript)',
'tog-showtoc'                 => 'Pokaż spis treści (na stronach o więcej niż 3 nagłówkach)',
'tog-rememberpassword'        => 'Pamiętaj hasło między sesjami na tym komputerze',
'tog-editwidth'               => 'Obszar edycji o pełnej szerokości',
'tog-watchcreations'          => 'Dodaj do obserwowanych strony tworzone przeze mnie',
'tog-watchdefault'            => 'Dodaj do obserwowanych strony, które edytuję',
'tog-watchmoves'              => 'Dodaj do obserwowanych strony, które przenoszę',
'tog-watchdeletion'           => 'Dodaj do obserwowanych strony, które usuwam',
'tog-minordefault'            => 'Wszystkie zmiany oznaczaj domyślnie jako drobne',
'tog-previewontop'            => 'Pokazuj podgląd powyżej obszaru edycji',
'tog-previewonfirst'          => 'Pokaż podgląd strony podczas pierwszej edycji',
'tog-nocache'                 => 'Wyłącz pamięć podręczną',
'tog-enotifwatchlistpages'    => 'Wyślij do mnie e-mail jeśli strona z listy moich obserwowanych zostanie zmodyfikowana',
'tog-enotifusertalkpages'     => 'Wyślij e-mail jeśli moja strona dyskusji zostanie zmodyfikowana',
'tog-enotifminoredits'        => 'Wyślij e-mail także w przypadku drobnych zmian na stronach',
'tog-enotifrevealaddr'        => 'Nie ukrywaj mojego adresu e-mail w powiadomieniach',
'tog-shownumberswatching'     => 'Pokaż liczbę obserwujących użytkowników',
'tog-fancysig'                => 'Podpis bez automatycznego linku',
'tog-externaleditor'          => 'Domyślnie używaj zewnętrznego edytora',
'tog-externaldiff'            => 'Domyślnie używaj zewnętrznego programu pokazującego zmiany',
'tog-showjumplinks'           => 'Włącz odnośniki „skocz do”',
'tog-uselivepreview'          => 'Używaj dynamicznego podglądu (JavaScript) (eksperymentalny)',
'tog-forceeditsummary'        => 'Informuj o niewypełnieniu opisu zmian',
'tog-watchlisthideown'        => 'Ukryj moje edycje w obserwowanych',
'tog-watchlisthidebots'       => 'Ukryj edycje botów w obserwowanych',
'tog-watchlisthideminor'      => 'Ukryj drobne zmiany w obserwowanych',
'tog-nolangconversion'        => 'Wyłącz odmianę',
'tog-ccmeonemails'            => 'Przesyłaj mi kopie wiadomości wysłanych przez mnie do innych użytkowników',
'tog-diffonly'                => 'Nie pokazuj treści stron pod porównaniami zmian',
'tog-showhiddencats'          => 'Pokaż ukryte kategorie',

'underline-always'  => 'zawsze',
'underline-never'   => 'nigdy',
'underline-default' => 'według ustawień przeglądarki',

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

# Categories related messages
'categories'                     => 'Kategorie',
'categoriespagetext'             => 'Poniższe kategorie zawierają strony lub pliki.',
'special-categories-sort-count'  => 'sortowanie według liczby',
'special-categories-sort-abc'    => 'sortowanie alfabetyczne',
'pagecategories'                 => '{{PLURAL:$1|Kategoria|Kategorie}}',
'category_header'                => 'Strony w kategorii „$1”',
'subcategories'                  => 'Podkategorie',
'category-media-header'          => 'Pliki w kategorii „$1”',
'category-empty'                 => "''W tej kategorii nie ma obecnie ani stron, ani plików.''",
'hidden-categories'              => '{{PLURAL:$1|Ukryta kategoria|Ukryte kategorie}}',
'hidden-category-category'       => 'Ukryte kategorie', # Name of the category where hidden categories will be listed
'category-subcat-count'          => '{{PLURAL:$2|Ta kategoria ma tylko jedną podkategorię.|Ta kategoria ma {{PLURAL:$1|tylko jedną podkategorię|$1 podkategorie|$1 podkategorii}} spośród ogólnej liczby $2.}}',
'category-subcat-count-limited'  => 'Ta kategoria ma {{PLURAL:$1|1 podkategorię|$1 podkategorie|$1 podkategorii}}.',
'category-article-count'         => '{{PLURAL:$2|W tej kategorii jest tylko jedna strona.|W tej kategorii {{PLURAL:$1|jest 1 strona|są $1 strony|jest $1 stron}} z ogólnej liczby $2 stron.}}',
'category-article-count-limited' => 'W tej kategorii {{PLURAL:$1|jest 1 strona|są $1 strony|jest $1 stron}}.',
'category-file-count'            => '{{PLURAL:$2|W tej kategorii znajduje się tylko jeden plik.|W tej kategorii {{PLURAL:$1|jest 1 plik|są $1 pliki|jest $1 plików}} z ogólnej liczby $2 plików.}}',
'category-file-count-limited'    => 'W tej kategorii {{PLURAL:$1|jest 1 plik|są $1 pliki|jest $1 plików}}.',
'listingcontinuesabbrev'         => 'cd.',

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
'and'            => 'oraz',

# Metadata in edit box
'metadata_help' => 'Metadane:',

'errorpagetitle'    => 'Błąd',
'returnto'          => 'Wróć do strony $1.',
'tagline'           => 'Z {{GRAMMAR:D.lp|{{SITENAME}}}}',
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
'permalink'         => 'Link bezpośredni',
'print'             => 'Drukuj',
'edit'              => 'edytuj',
'create'            => 'Utwórz',
'editthispage'      => 'Edytuj tę stronę',
'create-this-page'  => 'Utwórz tę stronę',
'delete'            => 'Usuń',
'deletethispage'    => 'Usuń tę stronę',
'undelete_short'    => 'Odtwórz {{PLURAL:$1|jedną wersję|$1 wersje|$1 wersji}}',
'protect'           => 'Zabezpiecz',
'protect_change'    => 'zmień zabezpieczenie',
'protectthispage'   => 'Zabezpiecz tę stronę',
'unprotect'         => 'Odbezpiecz',
'unprotectthispage' => 'Odbezpiecz tę stronę',
'newpage'           => 'Nowa strona',
'talkpage'          => 'Dyskusja',
'talkpagelinktext'  => 'Dyskusja',
'specialpage'       => 'Strona specjalna',
'personaltools'     => 'Osobiste narzędzia',
'postcomment'       => 'Skomentuj',
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
'lastmodifiedat'    => 'Tę stronę ostatnio zmodyfikowano $2, $1.', # $1 date, $2 time
'viewcount'         => 'Tę stronę obejrzano {{PLURAL:$1|tylko raz|$1 razy}}.',
'protectedpage'     => 'Strona zabezpieczona',
'jumpto'            => 'Skocz do:',
'jumptonavigation'  => 'nawigacji',
'jumptosearch'      => 'wyszukiwania',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'         => 'O {{GRAMMAR:MS.lp|{{SITENAME}}}}',
'aboutpage'         => 'Project:O serwisie',
'bugreports'        => 'Raport o błędach',
'bugreportspage'    => 'Project:Błędy',
'copyright'         => 'Tekst jest udostępniany na licencji $1.',
'copyrightpagename' => 'prawami autorskimi {{GRAMMAR:D.lp|{{SITENAME}}}}',
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

'badaccess'        => 'Niewłaściwe uprawnienia',
'badaccess-group0' => 'Nie masz uprawnień wymaganych do wykonania tej operacji.',
'badaccess-group1' => 'Wykonywanie tej operacji zostało ograniczone do użytkowników w grupie $1.',
'badaccess-group2' => 'Wykonywanie tej operacji zostało ograniczone do użytkowników w jednej z grup $1.',
'badaccess-groups' => 'Wykonywanie tej operacji zostało ograniczone do użytkowników w jednej z grup $1.',

'versionrequired'     => 'Wymagana MediaWiki w wersji $1',
'versionrequiredtext' => 'Użycie tej strony wymaga oprogramowania MediaWiki w wersji $1. Zobacz stronę [[Special:Version|wersja oprogramowania]].',

'ok'                      => 'OK',
'retrievedfrom'           => 'Źródło: „$1”',
'youhavenewmessages'      => 'Masz $1 ($2).',
'newmessageslink'         => 'nowe wiadomości',
'newmessagesdifflink'     => 'różnica z poprzednią wersją',
'youhavenewmessagesmulti' => 'Masz nowe wiadomości na $1',
'editsection'             => 'edytuj',
'editold'                 => 'edytuj',
'editsectionhint'         => 'Edytuj sekcję: $1',
'toc'                     => 'Spis treści',
'showtoc'                 => 'pokaż',
'hidetoc'                 => 'ukryj',
'thisisdeleted'           => 'Pokaż/odtwórz $1',
'viewdeleted'             => 'Zobacz $1',
'restorelink'             => '{{PLURAL:$1|jedną usuniętą wersję|$1 usunięte wersje|$1 usuniętych wersji}}',
'feedlinks'               => 'Kanały:',
'feed-invalid'            => 'Niewłaściwy typ kanału informacyjnego.',
'feed-unavailable'        => 'Kanały informacyjne {{GRAMMAR:D.lp|{{SITENAME}}}} nie są dostępne',
'site-rss-feed'           => 'Kanał RSS {{GRAMMAR:D.lp|$1}}',
'site-atom-feed'          => 'Kanał Atom {{GRAMMAR:D.lp|$1}}',
'page-rss-feed'           => 'Kanał RSS „$1”',
'page-atom-feed'          => 'Kanał Atom „$1”',
'red-link-title'          => '$1 (jeszcze nie utworzona)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Strona',
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
'nospecialpagetext' => "<big>'''Brak żądanej strony specjalnej.'''</big>

Listę dostępnych stron specjalnych znajdziesz [[Special:Specialpages|tutaj]].",

# General errors
'error'                => 'Błąd',
'databaseerror'        => 'Błąd bazy danych',
'dberrortext'          => 'Wystąpił błąd składni w zapytaniu do bazy danych.
Może to oznaczać błąd w oprogramowaniu.
Ostatnie, nieudane zapytanie to:
<blockquote><tt>$1</tt></blockquote>
wysłane przez funkcję „<tt>$2</tt>”.
MySQL zgłosił błąd „<tt>$3: $4</tt>”.',
'dberrortextcl'        => 'Wystąpił błąd składni w zapytaniu do bazy danych.
Ostatnie, nieudane zapytanie to:
„$1”
wywołane zostało przez funkcję „$2”.
MySQL zgłosił błąd „$3: $4”',
'noconnect'            => 'Przepraszamy! {{SITENAME}} ma chwilowo problemy techniczne. Nie można połączyć się z serwerem bazy danych.<br />$1',
'nodb'                 => 'Nie można odnaleźć bazy danych $1',
'cachederror'          => 'Poniższy tekst strony jest kopią znajdującą się w pamięci podręcznej i może być już nieaktualny.',
'laggedslavemode'      => 'Uwaga! Ta strona może nie zawierać najnowszych aktualizacji.',
'readonly'             => 'Baza danych jest zablokowana',
'enterlockreason'      => 'Podaj powód zablokowania bazy oraz szacunkowy czas jej odblokowania',
'readonlytext'         => 'Baza danych jest obecnie zablokowana - nie można wprowadzać nowych informacji ani modyfikować istniejących. Powodem są prawdopodobnie czynności administracyjne. Po ich zakończeniu przywrócona zostanie pełna funkcjonalność bazy.

Administrator, który zablokował bazę, podał następujące wyjaśnienie: $1',
'missingarticle'       => 'Oprogramowanie nie odnalazło tekstu strony „$1”, która powinna się znajdować w bazie.

Zazwyczaj zdarza się to, gdy zostanie wybrane łącze do usuniętej strony, np. w starszej wersji innej ze stron.

Inne okoliczności świadczyłyby o tym, że w oprogramowaniu jest błąd.
W takim przypadku zgłoś, proszę, ten fakt administratorowi, koniecznie podając adres URL tej strony.',
'readonly_lag'         => 'Baza danych została automatycznie zablokowana na czas potrzebny do wykonania synchronizacji zmian między serwerem głównym i serwerami pośredniczącymi.',
'internalerror'        => 'Błąd wewnętrzny',
'internalerror_info'   => 'Błąd wewnętrzny: $1',
'filecopyerror'        => 'Nie można skopiować pliku „$1” do „$2”.',
'filerenameerror'      => 'Nie można zmienić nazwy pliku „$1” na „$2”.',
'filedeleteerror'      => 'Nie można usunąć pliku „$1”.',
'directorycreateerror' => 'Nie udało się utworzyć katalogu „$1”.',
'filenotfound'         => 'Nie można znaleźć pliku „$1”.',
'fileexistserror'      => 'Nie udało się zapisać do pliku „$1”: plik istnieje',
'unexpected'           => 'Niespodziewana wartość: „$1”=„$2”.',
'formerror'            => 'Błąd: nie można wysłać formularza',
'badarticleerror'      => 'Dla tej strony ta operacja nie może być wykonana.',
'cannotdelete'         => 'Nie można usunąć podanej strony lub grafiki.
Możliwe, że zostały już usunięte przez kogoś innego.',
'badtitle'             => 'Niepoprawny tytuł',
'badtitletext'         => 'Podano niepoprawny tytuł strony. Prawdopodobnie jest pusty lub zawiera znaki, których użycie jest zabronione.',
'perfdisabled'         => 'Przepraszamy! By odciążyć serwer w godzinach szczytu czasowo zablokowaliśmy wykonanie tej czynności.',
'perfcached'           => 'Poniższe dane są kopią z pamięci podręcznej i mogą być nieaktualne.',
'perfcachedts'         => 'Poniższe dane są kopią z pamięci podręcznej, zostały ostatnio uaktualnione $1.',
'querypage-no-updates' => 'Uaktualnienia dla tej strony są obecnie wyłączone. Znajdujące się tutaj dane nie zostaną odświeżone.',
'wrong_wfQuery_params' => 'Nieprawidłowe parametry przekazane do wfQuery()<br />
Funkcja: $1<br />
Zapytanie: $2',
'viewsource'           => 'Tekst źródłowy',
'viewsourcefor'        => 'dla $1',
'actionthrottled'      => 'Akcja wstrzymana',
'actionthrottledtext'  => 'Mechanizm obrony przed spamem ogranicza liczbę wykonań tej czynności w jednostce czasu. Usiłowałeś przekroczyć to ograniczenie. Proszę spróbuj jeszcze raz za kilka minut.',
'protectedpagetext'    => 'Wyłączono możliwość edycji tej strony.',
'viewsourcetext'       => 'Tekst źródłowy strony można podejrzeć i skopiować.',
'protectedinterface'   => 'Ta strona zawiera tekst interfejsu oprogramowania, dlatego możliwość jej edycji została zablokowana.',
'editinginterface'     => "'''Ostrzeżenie:''' Edytujesz stronę, która zawiera tekst interfejsu oprogramowania.
Zmiany na tej stronie zmienią wygląd interfejsu dla innych użytkowników.
Rozważ wykonanie tłumaczenia na [http://translatewiki.net/wiki/Main_Page?setlang=pl Betawiki], specjalizowanym projekcie lokalizacji oprogramowania MediaWiki.",
'sqlhidden'            => '(ukryto zapytanie SQL)',
'cascadeprotected'     => 'Ta strona została zabezpieczona przed edycją, ponieważ jest ona zawarta na {{PLURAL:$1|następującej stronie, która została zabezpieczona|następujących stronach, które zostały zabezpieczone}} z włączoną opcją dziedziczenia:
$2',
'namespaceprotected'   => "Nie masz uprawnień do edytowania stron w przestrzeni nazw '''$1'''.",
'customcssjsprotected' => 'Nie możesz edytować tej strony, ponieważ zawiera ona ustawienia osobiste innego użytkownika.',
'ns-specialprotected'  => 'Stron specjalnych nie można edytować.',
'titleprotected'       => "Utworzenie strony o tej nazwie zostało zablokowane przez [[User:$1|$1]].
Powód blokady: ''$2''.",

# Login and logout pages
'logouttitle'                => 'Wylogowanie użytkownika',
'logouttext'                 => '<strong>Zostałeś wylogowany.</strong>

Możesz kontynuować pracę w {{GRAMMAR:MS.lp|{{SITENAME}}}} jako niezarejestrowany użytkownik albo zalogować się ponownie jako ten sam lub inny użytkownik.
Zauważ, że do momentu wyczyszczenia pamięci podręcznej przeglądarki niektóre strony oglądane przez Ciebie wcześniej, gdy byłeś zalogowany, mogą być nadal przeglądane.',
'welcomecreation'            => '== Witaj, $1! ==
Konto zostało utworzone.
Nie zapomnij dostosować [[Special:Preferences|preferencji]].',
'loginpagetitle'             => 'Logowanie',
'yourname'                   => 'Nazwa użytkownika:',
'yourpassword'               => 'Hasło:',
'yourpasswordagain'          => 'Powtórz hasło:',
'remembermypassword'         => 'Zapamiętaj moje hasło na tym komputerze',
'yourdomainname'             => 'Twoja domena:',
'externaldberror'            => 'Wystąpił błąd zewnętrznej bazy autentyfikacyjnej lub nie posiadasz uprawnień koniecznych do aktualizacji zewnętrznego konta.',
'loginproblem'               => '<b>Wystąpił problem przy próbie zalogowania.</b><br />Spróbuj ponownie!',
'login'                      => 'Zaloguj',
'loginprompt'                => 'Musisz mieć włączoną w przeglądarce obsługę ciasteczek by móc się zalogować do {{GRAMMAR:D.lp|{{SITENAME}}}}.',
'userlogin'                  => 'Logowanie / rejestracja',
'logout'                     => 'Wyloguj mnie',
'userlogout'                 => 'Wylogowanie',
'notloggedin'                => 'Niezalogowany',
'nologin'                    => 'Nie masz konta? $1.',
'nologinlink'                => 'Zarejestruj się',
'createaccount'              => 'Załóż nowe konto',
'gotaccount'                 => 'Masz już konto? $1.',
'gotaccountlink'             => 'Zaloguj się',
'createaccountmail'          => 'przez e-mail',
'badretype'                  => 'Wprowadzone hasła różnią się między sobą.',
'userexists'                 => 'Wybrana przez Ciebie nazwa użytkownika jest już zajęta.
Wybierz inną nazwę użytkownika.',
'youremail'                  => 'Twój adres e-mail',
'username'                   => 'Nazwa użytkownika',
'uid'                        => 'ID użytkownika',
'yourrealname'               => 'Imię i nazwisko',
'yourlanguage'               => 'Język interfejsu',
'yourvariant'                => 'Wariant:',
'yournick'                   => 'Twój podpis',
'badsig'                     => 'Błędny podpis, sprawdź znaczniki HTML.',
'badsiglength'               => 'Nazwa użytkownika jest zbyt długa. Maksymalna jej długość to $1 znaków.',
'email'                      => 'E-mail',
'prefs-help-realname'        => '* Imię i nazwisko (opcjonalne) - jeśli zdecydujesz się je podać, zostaną użyte, aby zapewnić Twojej pracy atrybucję.',
'loginerror'                 => 'Błąd zalogowania',
'prefs-help-email'           => '* E-mail (opcjonalne) - Pozwala innym użytkownikom skontaktować się z Tobą poprzez odpowiedni formularz, bez ujawniania Twojego adresu e-mail.',
'prefs-help-email-required'  => 'Wymagany jest adres e-mail.',
'nocookiesnew'               => 'Konto użytkownika zostało utworzone, ale nie jesteś zalogowany.
{{SITENAME}} używa ciasteczek do zalogowania. 
Masz obecnie w przeglądarce wyłączoną obsługę ciasteczek. 
Żeby się zalogować włącz obsługę ciasteczek, następnie podaj nazwę użytkownika i hasło dostępu do swojego konta.',
'nocookieslogin'             => '{{SITENAME}} wykorzystuje mechanizm ciasteczek do zalogowania użytkownika.
Masz obecnie w przeglądarce wyłączoną obsługę ciasteczek. 
Spróbuj ponownie po ich odblokowaniu.',
'noname'                     => 'To nie jest poprawna nazwa użytkownika.',
'loginsuccesstitle'          => 'Zalogowano pomyślnie',
'loginsuccess'               => "'''Zalogowałeś się do {{GRAMMAR:D.lp|{{SITENAME}}}} jako „$1”.'''",
'nosuchuser'                 => 'Nie ma użytkownika o nazwie „$1”.
Sprawdź pisownię lub użyj poniższego formularza by utworzyć nowe konto.',
'nosuchusershort'            => 'Nie ma użytkownika o nazwie „<nowiki>$1</nowiki>”.
Sprawdź poprawność pisowni.',
'nouserspecified'            => 'Musisz podać nazwę użytkownika.',
'wrongpassword'              => 'Podane hasło jest nieprawidłowe. Spróbuj jeszcze raz.',
'wrongpasswordempty'         => 'Wprowadzone hasło jest puste. Spróbuj ponownie.',
'passwordtooshort'           => 'Twoje hasło jest błędne lub za krótkie.
Musi mieć co najmniej $1 znaków i być inne niż Twoja nazwa użytkownika.',
'mailmypassword'             => 'Wyślij mi nowe hasło poprzez e-mail',
'passwordremindertitle'      => 'Nowe tymczasowe hasło do {{GRAMMAR:D.lp|{{SITENAME}}}}',
'passwordremindertext'       => 'Ktoś (prawdopodobnie Ty, spod adresu IP $1)
poprosił o przesłanie nowego hasła do {{GRAMMAR:D.lp|{{SITENAME}}}} ($4).
Nowe hasło użytkownika "$2" to "$3".
Najlepiej będzie, gdy zalogujesz się teraz i od razu zmienisz hasło.

Jeśli to nie Ty prosiłeś o przesłanie hasła i nie chcesz zmieniać poprzedniego hasła wystarczy, że zignorujesz tą wiadomość i dalej będziesz się posługiwał swoim dotychczasowym hasłem.',
'noemail'                    => 'Nie ma zdefiniowanego adresu e-mail dla użytkownika „$1”.',
'passwordsent'               => 'Nowe hasło zostało wysłane na adres e-mail użytkownika „$1”.
Po otrzymaniu go zaloguj się ponownie.',
'blocked-mailpassword'       => 'Twój adres IP został zablokowany i nie możesz używać funkcji odzyskiwania hasła z powodu możliwości jej nadużywania.',
'eauthentsent'               => 'Potwierdzenie zostało wysłane na adres e-mail.
Zanim jakiekolwiek inne wiadomości zostaną wysłane na ten adres, należy wykonać zawarte w mailu instrukcje. Potwierdzisz w ten sposób, że ten adres e-mail należy do Ciebie.',
'throttled-mailpassword'     => 'Przypomnienie hasła zostało już wysłane w ciągu {{PLURAL:$1|ostatniej godziny|ostatnich $1 godzin}}.
W celu powstrzymania nadużyć możliwość wysyłania przypomnień została ograniczona do jednego na {{PLURAL:$1|godzinę|godziny|godzin}}.',
'mailerror'                  => 'Przy wysyłaniu e-maila wystąpił błąd: $1',
'acct_creation_throttle_hit' => 'Założyłeś już {{PLURAL:$1|konto|$1 konta|$1 kont}}.
Nie możesz założyć kolejnego.',
'emailauthenticated'         => 'Twój adres e-mail został uwierzytelniony $1.',
'emailnotauthenticated'      => 'Twój adres e-mail nie został potwierdzony.
Poniższe funkcje poczty są nieaktywne.',
'noemailprefs'               => 'Musisz podać adres e-mail, by skorzystać z tych funkcji.',
'emailconfirmlink'           => 'Potwierdź swój adres e-mail',
'invalidemailaddress'        => 'Adres e-mail jest niepoprawny i nie może być zaakceptowany.
Proszę wpisać poprawny adres e-mail lub wyczyścić pole.',
'accountcreated'             => 'Konto zostało utworzone',
'accountcreatedtext'         => 'Konto dla $1 zostało utworzone.',
'createaccount-title'        => 'Utworzenie konta w {{GRAMMAR:MS.lp|{{SITENAME}}}}',
'createaccount-text'         => 'Ktoś utworzył w {{GRAMMAR:MS.lp|{{SITENAME}}}} ($4) dla Twojego adresu e-mail konto "$2". Aktualne hasło to "$3". Powinieneś się teraz zalogować i je zmienić.

Możesz zignorować tą wiadomość, jeśli konto zostało stworzone przez pomyłkę.',
'loginlanguagelabel'         => 'Język: $1',

# Password reset dialog
'resetpass'               => 'Resetuj hasło',
'resetpass_announce'      => 'Zalogowałeś się wykorzystując tymczasowe hasło otrzymane przez e-mail.
Aby zakończyć proces logowania musisz ustawić nowe hasło:',
'resetpass_text'          => '<!-- Dodaj tekst -->',
'resetpass_header'        => 'Resetuj hasło',
'resetpass_submit'        => 'Ustaw hasło i zaloguj się',
'resetpass_success'       => 'Twoje hasło zostało pomyślnie zmienione! Trwa logowanie...',
'resetpass_bad_temporary' => 'Nieprawidłowe hasło tymczasowe.
Być może zakończyłeś już proces zmiany hasła lub poprosiłeś o nowe hasło tymczasowe.',
'resetpass_forbidden'     => 'Haseł użytkowników w {{GRAMMAR:MS.lp|{{SITENAME}}}} nie można zmieniać.',
'resetpass_missing'       => 'Brak danych formularza.',

# Edit page toolbar
'bold_sample'     => 'Tekst wytłuszczony',
'bold_tip'        => 'Tekst wytłuszczony',
'italic_sample'   => 'Tekst pochylony',
'italic_tip'      => 'Tekst pochylony',
'link_sample'     => 'Tytuł linku',
'link_tip'        => 'Link wewnętrzny',
'extlink_sample'  => 'http://www.przyklad.pl nazwa linku',
'extlink_tip'     => 'Link zewnętrzny (pamiętaj o prefiksie http:// )',
'headline_sample' => 'Tekst nagłówka',
'headline_tip'    => 'Nagłówek 2. poziomu',
'math_sample'     => 'W tym miejscu wprowadź wzór',
'math_tip'        => 'Wzór matematyczny (LaTeX)',
'nowiki_sample'   => 'Wstaw tu tekst niesformatowany',
'nowiki_tip'      => 'Zignoruj formatowanie wiki',
'image_sample'    => 'Przyklad.jpg',
'image_tip'       => 'Plik osadzony',
'media_sample'    => 'Przyklad.ogg',
'media_tip'       => 'Link do pliku',
'sig_tip'         => 'Twój podpis wraz z datą i czasem',
'hr_tip'          => 'Pozioma linia (używaj oszczędnie)',

# Edit pages
'summary'                           => 'Opis zmian',
'subject'                           => 'Temat/nagłówek',
'minoredit'                         => 'To jest drobna zmiana',
'watchthis'                         => 'Obserwuj tę stronę',
'savearticle'                       => 'Zapisz',
'preview'                           => 'Podgląd',
'showpreview'                       => 'Pokaż podgląd',
'showlivepreview'                   => 'Dynamiczny podgląd',
'showdiff'                          => 'Pokaż zmiany',
'anoneditwarning'                   => "'''Uwaga:''' Nie jesteś zalogowany.
Twój adres IP będzie zapisany w historii edycji strony.",
'missingsummary'                    => "'''Uwaga:''' Nie wprowadziłeś opisu zmian.
Jeżeli nie chcesz go wprowadzać naciśnij przycisk Zapisz jeszcze raz.",
'missingcommenttext'                => 'Wprowadź komentarz poniżej.',
'missingcommentheader'              => "'''Uwaga:''' Treść nagłówka jest pusta - uzupełnij go!
Jeśli tego nie zrobisz, Twój komentarz zostanie zapisany bez nagłówka.",
'summary-preview'                   => 'Podgląd opisu',
'subject-preview'                   => 'Podgląd nagłówka',
'blockedtitle'                      => 'Użytkownik jest zablokowany',
'blockedtext'                       => "<big>'''Twoje konto lub adres IP zostały zablokowane.'''</big>

Blokada została nałożona przez $1. Podany powód to: ''$2''.

* Początek blokady: $8
* Wygaśnięcie blokady: $6
* Cel blokady: $7

W celu wyjaśnienia przyczyn zablokowania możesz się skontaktować z $1 lub innym [[{{MediaWiki:Grouppage-sysop}}|administratorem]].
Nie możesz użyć funkcji „Wyślij e-mail do tego użytkownika” jeśli nie podałeś poprawnego adresu e-mail w swoich [[Special:Preferences|preferencjach]] lub jeśli taka możliwość została Ci zablokowana.
Twój obecny adres IP to $3, a numer identyfikacyjny blokady to $5. Prosimy o podanie jednego lub obu tych numerów przy wyjaśnianiu tej blokady.",
'autoblockedtext'                   => "Ten adres IP został zablokowany automatycznie, gdyż korzysta z niego inny użytkownik, zablokowany przez administratora $1.
Przyczyna blokady:

:''$2''

* Początek blokady: $8
* Wygaśnięcie blokady: $6

Możesz skontaktować się z $1 lub jednym z pozostałych [[{{MediaWiki:Grouppage-sysop}}|administratorów]] w celu uzyskania informacji o blokadzie.

Jeśli w [[Special:Preferences|preferencjach]] nie ustawiłeś prawidłowego adresu e-mail lub zablokowano Ci tą funkcjonalność, nie możesz skorzystać z opcji „Wyślij e-mail do tego użytkownika”.

Identyfikator blokady to $5.
Zanotuj go i podaj administratorowi.",
'blockednoreason'                   => 'nie podano przyczyny',
'blockedoriginalsource'             => "Źródło '''$1''' zostało pokazane poniżej:",
'blockededitsource'                 => "Tekst '''Twoich edycji''' na '''$1''' został pokazany poniżej:",
'whitelistedittitle'                => 'Przed edycją musisz się zalogować',
'whitelistedittext'                 => 'Musisz $1 by edytować strony.',
'whitelistreadtitle'                => 'Czytanie możliwe jest dopiero po zalogowaniu się',
'whitelistreadtext'                 => 'Musisz się [[Special:Userlogin|zalogować]], żeby czytać strony.',
'whitelistacctitle'                 => 'Nie masz uprawnień do założenia konta',
'whitelistacctext'                  => 'Zakładanie kont w {{GRAMMAR:MS.lp|{{SITENAME}}}} wymaga [[Special:Userlogin|zalogowania]] oraz posiadania odpowiednich uprawnień.',
'confirmedittitle'                  => 'Edytowanie wymaga potwierdzenia adresu e-mail',
'confirmedittext'                   => 'Edytowanie wymaga potwierdzenia adresu e-mail.
Podaj adres e-mail i potwierdź go w swoich [[Special:Preferences|ustawieniach użytkownika]].',
'nosuchsectiontitle'                => 'Sekcja nie istnieje',
'nosuchsectiontext'                 => 'Próbowałeś edytować sekcję, która nie istnieje.
Ponieważ nie ma sekcji $1, nie ma też gdzie zapisać Twojej edycji.',
'loginreqtitle'                     => 'Musisz się zalogować',
'loginreqlink'                      => 'zaloguj się',
'loginreqpagetext'                  => 'Musisz $1 żeby móc przeglądać inne strony.',
'accmailtitle'                      => 'Hasło zostało wysłane.',
'accmailtext'                       => 'Hasło użytkownika „$1” zostało wysłane na adres $2.',
'newarticle'                        => '(Nowy)',
'newarticletext'                    => "Nie ma jeszcze strony o tym tytule.
Jeśli chcesz ją utworzyć wpisz treść strony w poniższym polu (więcej informacji odnajdziesz [[{{MediaWiki:Helppage}}|na stronie pomocy]]). 
Jeśli utworzenie nowej strony nie było Twoim zamiarem, wciśnij ''Wstecz'' w swojej przeglądarce.",
'anontalkpagetext'                  => "---- ''To jest strona dyskusji anonimowego użytkownika - takiego, który nie ma jeszcze swojego konta lub nie chce go w tej chwili używać. By go identyfikować używamy adresów IP.
Jednak adres IP może być współdzielony przez wielu użytkowników.
Jeśli jesteś anonimowym użytkownikiem i uważasz, że zamieszczone tu komentarze nie są skierowane do Ciebie, [[Special:Userlogin|utwórz konto lub zaloguj się]] - dzięki temu unikniesz w przyszłości podobnych nieporozumień.''",
'noarticletext'                     => 'Nie ma jeszcze strony o tym tytule. Możesz [[Special:Search/{{PAGENAME}}|poszukać {{PAGENAME}} na innych stronach]] lub [{{fullurl:{{FULLPAGENAME}}|action=edit}} utworzyć stronę {{FULLPAGENAME}}].',
'userpage-userdoesnotexist'         => 'Użytkownik „$1” nie jest zarejestrowany. Upewnij się czy na pewno zamierzałeś utworzyć/zmodyfikować właśnie tę stronę.',
'clearyourcache'                    => "'''Uwaga:''' Zmiany po zapisaniu nowych ustawień mogą nie być widoczne. Należy wyczyścić zawartość pamięci podręcznej przeglądarki internetowej. '''Mozilla / Firefox / Safari:''' przytrzymaj wciśnięty ''Shift'' i kliknij na ''Odśwież'' lub wciśnij ''Ctrl-Shift-R'' (''Cmd-Shift-R'' na Macintoshu), '''IE:''' przytrzymaj ''Ctrl'' i kliknij na ''Odśwież'' lub wciśnij ''Ctrl-F5''; '''Konqueror:''': po prostu kliknij przycisk ''Odśwież'' lub wciśnij ''F5''; '''Opera''' może wymagać wyczyszczenia pamięci podręcznej w menu ''Narzędzia→Preferencje''.",
'usercssjsyoucanpreview'            => '<strong>Wskazówka:</strong> Użyj przycisku „Podgląd”, aby przetestować nowy arkusz stylów CSS lub kod JavaScript przed jego zapisaniem.',
'usercsspreview'                    => "'''Pamiętaj, że to tylko podgląd arkusza stylów CSS - nic jeszcze nie zostało zapisane!'''",
'userjspreview'                     => "'''Pamiętaj, że to tylko podgląd kodu JavaScriptu - nic jeszcze nie zostało zapisane!'''",
'userinvalidcssjstitle'             => "'''Uwaga:''' Nie ma skórki o nazwie „$1”.
Strony użytkownika zawierające CSS i JavaScript powinny zaczynać się małą literą, np. {{ns:user}}:Foo/monobook.css, w przeciwieństwie do nieprawidłowego {{ns:user}}:Foo/Monobook.css.",
'updated'                           => '(Zmodyfikowano)',
'note'                              => '<strong>Uwaga:</strong>',
'previewnote'                       => '<strong>To jest tylko podgląd - zmiany nie zostały jeszcze zapisane!</strong>',
'previewconflict'                   => 'Wersja podglądana odnosi się do tekstu z górnego pola edycji. Tak będzie wyglądać strona jeśli zdecydujesz się ją zapisać.',
'session_fail_preview'              => '<strong>Uwaga! Serwer nie może przetworzyć tej edycji z powodu utraty danych sesji. Spróbuj jeszcze raz. Jeśli to nie pomoże - wyloguj się i zaloguj ponownie.</strong>',
'session_fail_preview_html'         => "<strong>Uwaga! Serwer nie może przetworzyć tej edycji z powodu utraty danych sesji.</strong>

''Ponieważ w {{GRAMMAR:MS.lp|{{SITENAME}}}} włączona została opcja „surowy HTML”, podgląd został ukryty w celu zabezpieczenia przed atakami JavaScript.''

<strong>Jeśli jest to uprawniona próba dokonania edycji, spróbuj jeszcze raz. Jeśli to nie pomoże - wyloguj się i zaloguj ponownie.</strong>",
'token_suffix_mismatch'             => '<strong>Twoja edycja została odrzucona, ponieważ twój klient pomieszał znaki interpunkcyjne w żetonie edycyjnym.
Twoja edycja została odrzucona by zapobiec zniszczeniu tekstu strony.
Takie problemy zdarzają się w wypadku korzystania z wadliwych anonimowych sieciowych usług proxy.</strong>',
'editing'                           => 'Edytujesz „$1”',
'editingsection'                    => 'Edytujesz „$1” (fragment)',
'editingcomment'                    => 'Edytujesz „$1” (komentarz)',
'editconflict'                      => 'Konflikt edycji: $1',
'explainconflict'                   => "Ktoś zmienił treść strony w trakcie Twojej edycji.
Górne pole zawiera tekst strony aktualnie zapisany w bazie danych.
Twoje zmiany znajdują się w dolnym polu.
By wprowadzić swoje zmiany musisz zmodyfikować tekst z górnego pola.
'''Tylko''' tekst z górnego pola zostanie zapisany w bazie gdy wciśniesz „Zapisz”.",
'yourtext'                          => 'Twój tekst',
'storedversion'                     => 'Zapisana wersja',
'nonunicodebrowser'                 => '<strong>Uwaga! Twoja przeglądarka nie rozpoznaje poprawnie kodowania UTF-8 (Unicode).
Z tego powodu wszystkie znaki, których przeglądarka nie rozpoznaje, zostały zastąpione ich kodami szesnastkowymi.</strong>',
'editingold'                        => '<strong>Uwaga! Edytujesz inną niż bieżąca wersję tej strony.
Jeśli zapiszesz ją, wszystkie zmiany wykonane w międzyczasie zostaną wycofane.</strong>',
'yourdiff'                          => 'Różnice',
'copyrightwarning'                  => "Wszelki wkład w {{GRAMMAR:B.lp|{{SITENAME}}}} jest udostępniany na zasadach $2 (szczegóły w $1). Jeśli nie chcesz, żeby Twój tekst był dowolnie zmieniany przez każdego i rozpowszechniany bez ograniczeń, nie umieszczaj go tutaj.<br />
Zapisując swoją edycję oświadczasz, że ten tekst jest Twoim dziełem lub pochodzi z materiałów dostępnych na zasadach ''public domain'' albo kompatybilnych.
<strong>PROSZĘ NIE UŻYWAĆ MATERIAŁÓW CHRONIONYCH PRAWEM AUTORSKIM BEZ POZWOLENIA WŁAŚCICIELA!</strong>",
'copyrightwarning2'                 => "Wszelki wkład w {{GRAMMAR:B.lp|{{SITENAME}}}} może być edytowany, zmieniany lub usunięty przez innych użytkowników.
Jeśli nie chcesz, żeby Twój tekst był dowolnie zmieniany przez każdego i rozpowszechniany bez ograniczeń, nie umieszczaj go tutaj.<br />
Zapisując swoją edycję oświadczasz, że ten tekst jest Twoim dziełem lub pochodzi z materiałów dostępnych na zasadach ''public domain'' albo kompatybilnych (zobacz także $1).
<strong>PROSZĘ NIE UŻYWAĆ MATERIAŁÓW CHRONIONYCH PRAWEM AUTORSKIM BEZ POZWOLENIA WŁAŚCICIELA!</strong>",
'longpagewarning'                   => '<strong>Uwaga! Ta strona ma {{PLURAL:$1|1 kilobajt|$1 kilobajty|$1 kilobajtów}}.
W niektórych przeglądarkach mogą wystąpić problemy przy edycji stron mających więcej niż 32 kilobajty. 
Jeśli to możliwe, spróbuj podzielić tekst na mniejsze części.</strong>',
'longpageerror'                     => '<strong>Błąd! Wprowadzony przez Ciebie tekst ma {{PLURAL:$1|1 kilobajt|$1 kilobajty|$1 kilobajtów}}. Długość tekstu nie może przekraczać {{PLURAL:$2|1 kilobajt|$2 kilobajty|$2 kilobajtów}}. Tekst nie może być zapisany.</strong>',
'readonlywarning'                   => '<strong>Uwaga! Baza danych została zablokowana do celów administracyjnych. W tej chwili nie można zapisać nowej wersji strony. Zapisz jej treść do pliku używając wytnij/wklej i zachowaj na później.</strong>',
'protectedpagewarning'              => '<strong>Uwaga! Modyfikacja tej strony została zablokowana. Mogą ją edytować jedynie użytkownicy z uprawnieniami administratora.</strong>',
'semiprotectedpagewarning'          => "'''Uwaga!''' Ta strona została zabezpieczona i tylko zarejestrowani użytkownicy mogą ją edytować.",
'cascadeprotectedwarning'           => "'''Uwaga!''' Ta strona została zabezpieczona i tylko użytkownicy z uprawnieniami administratora mogą ją edytować. Strona ta jest zawarta na {{PLURAL:$1|następującej stronie, która została zabezpieczona|następujących stronach, które zostały zabezpieczone}} z włączoną opcją dziedziczenia:",
'titleprotectedwarning'             => '<strong>Uwaga! Utworzenie strony o tej nazwie zostało zablokowane. Tylko niektórzy użytkownicy mogą ją utworzyć.</strong>',
'templatesused'                     => 'Szablony użyte na tej stronie:',
'templatesusedpreview'              => 'Szablony użyte w tym podglądzie:',
'templatesusedsection'              => 'Szablony użyte w tej sekcji:',
'template-protected'                => '(zabezpieczony)',
'template-semiprotected'            => '(częściowo zabezpieczony)',
'hiddencategories'                  => 'Ta strona jest w {{PLURAL:$1|jednej ukrytej kategorii|$1 ukrytych kategoriach}}:',
'edittools'                         => '<!-- Znajdujący się tutaj tekst zostanie pokazany pod polem edycji i formularzem przesyłania plików. -->',
'nocreatetitle'                     => 'Ograniczono tworzenie stron',
'nocreatetext'                      => 'W {{GRAMMAR:MS.lp|{{SITENAME}}}} ograniczono możliwość tworzenia nowych stron.
Możesz edytować istniejące strony, bądź też [[Special:Userlogin|zalogować się lub utworzyć konto]].',
'nocreate-loggedin'                 => 'Nie masz uprawnień do tworzenia stron w {{GRAMMAR:MS.lp|{{SITENAME}}}}.',
'permissionserrors'                 => 'Błędy uprawnień',
'permissionserrorstext'             => 'Nie masz uprawnień do tego działania z {{PLURAL:$1|następującej przyczyny|następujących przyczyn}}:',
'recreate-deleted-warn'             => "'''Uwaga! Próbujesz odtworzyć uprzednio usuniętą stronę.'''

Upewnij się, czy ponowne edytowanie tej strony jest uzasadnione.
Poniżej, dla wygody przedstawiony jest rejestr usunięć niniejszej strony:",
'expensive-parserfunction-warning'  => 'Uwaga! Ta strona zawiera zbyt wiele wywołań złożonych obliczeniowo funkcji parsera.

Powinno ich być mniej niż $2, a jest obecnie $1.',
'expensive-parserfunction-category' => 'Strony ze zbyt dużą liczbą wywołań złożonych obliczeniowo funkcji parsera',

# "Undo" feature
'undo-success' => 'Edycja może zostać wycofana. Proszę porównać ukazane poniżej różnice między wersjami w celu ich zweryfikowania poprawności, a następnie zapisać zmiany w celu zakończenia operacji.',
'undo-failure' => 'Edycja nie może zostać wycofana z powodu konfliktu z wersjami pośrednimi.',
'undo-summary' => 'Wycofanie wersji $1 utworzonej przez [[Special:Contributions/$2|$2]] ([[User talk:$2|dyskusja]])',

# Account creation failure
'cantcreateaccounttitle' => 'Nie można utworzyć konta',
'cantcreateaccount-text' => "Tworzenie konta z tego adresu IP ('''$1''') zostało zablokowane przez [[User:$3|$3]].

Podany przez $3 powód to ''$2''",

# History pages
'viewpagelogs'        => 'Zobacz rejestry operacji dla tej strony',
'nohistory'           => 'Ta strona nie ma swojej historii edycji.',
'revnotfound'         => 'Wersja nie została odnaleziona',
'revnotfoundtext'     => 'Starsza wersja strony nie może zostać odnaleziona. Sprawdź adres URL, którego użyłeś by uzyskać dostęp do tej strony.',
'currentrev'          => 'Aktualna wersja',
'revisionasof'        => 'Wersja z dnia $1',
'revision-info'       => 'Wersja $2 z dnia $1',
'previousrevision'    => '← Poprzednia wersja',
'nextrevision'        => 'Następna wersja →',
'currentrevisionlink' => 'Aktualna wersja',
'cur'                 => 'bież',
'next'                => 'następna',
'last'                => 'poprz',
'page_first'          => 'początek',
'page_last'           => 'koniec',
'histlegend'          => "Wybór porównania: zaznacz kropeczkami dwie wersje do porównania i wciśnij enter lub przycisk ''Porównaj wybrane wersje''.<br />
Legenda: (bież) - pokaż zmiany od tej wersji do bieżącej,
(poprz) - pokaż zmiany od wersji poprzedzającej, d - drobne zmiany",
'deletedrev'          => '[usunięto]',
'histfirst'           => 'od początku',
'histlast'            => 'od końca',
'historysize'         => '({{PLURAL:$1|1 bajt|$1 bajty|$1 bajtów}})',
'historyempty'        => '(pusta)',

# Revision feed
'history-feed-title'          => 'Historia wersji',
'history-feed-description'    => 'Historia wersji tej strony wiki',
'history-feed-item-nocomment' => '$1 o $2', # user at time
'history-feed-empty'          => 'Wybrana strona nie istnieje.
Mogła zostać usunięta lub jej nazwa została zmieniona.
Spróbuj [[Special:Search|poszukać]] tej strony.',

# Revision deletion
'rev-deleted-comment'         => '(komentarz usunięty)',
'rev-deleted-user'            => '(użytkownik usunięty)',
'rev-deleted-event'           => '(wpis usunięty)',
'rev-deleted-text-permission' => '<div class="mw-warning plainlinks">
Wersja tej strony została usunięta i nie jest dostępna publicznie.
Szczegóły mogą znajdować się w [{{fullurl:Special:Log/delete|page={{FULLPAGENAMEE}}}} rejestrze usunięć].</div>',
'rev-deleted-text-view'       => '<div class="mw-warning plainlinks">
Ta wersja strony została usunięta i nie jest dostępna publicznie.
Jednak jako administrator {{GRAMMAR:D.lp|{{SITENAME}}}} możesz ją obejrzeć.
Powody usunięcia mogą znajdować się w [{{fullurl:Special:Log/delete|page={{FULLPAGENAMEE}}}} rejestrze usunięć].</div>',
'rev-delundel'                => 'pokaż/ukryj',
'revisiondelete'              => 'Usuń/przywróć wersje',
'revdelete-nooldid-title'     => 'Nie wybrano wersji',
'revdelete-nooldid-text'      => 'Nie wybrano wersji na których ma zostać wykonana ta operacja,
wybrana wersja nie istnieje lub próbowano ukryć wersję bieżącą.',
'revdelete-selected'          => '{{PLURAL:$2|Zaznaczona wersja|Zaznaczone wersje}} strony [[:$1]]:',
'logdelete-selected'          => 'Zaznaczone {{PLURAL:$1|zdarzenie|zdarzenia}} z rejestru:',
'revdelete-text'              => 'Usunięte wersje będą nadal widoczne w historii strony ale ich treść nie będzie publicznie dostępna.

Inni administratorzy {{GRAMMAR:D.lp|{{SITENAME}}}} nadal będą mieć dostęp do ukrytych wersji i będą mogli je odtworzyć, chyba że operator serwisu nałożył dodatkowe ograniczenia.',
'revdelete-legend'            => 'Ustaw ograniczenia widoczności dla wersji',
'revdelete-hide-text'         => 'Ukryj tekst wersji',
'revdelete-hide-name'         => 'Ukryj akcję i cel',
'revdelete-hide-comment'      => 'Ukryj komentarz edycji',
'revdelete-hide-user'         => 'Ukryj nazwę użytkownika/adres IP',
'revdelete-hide-restricted'   => 'Wprowadź te ograniczenia dla administratorów i zablokuj ten interfejs',
'revdelete-suppress'          => 'Ukryj informacje zarówno przed administratorami jak i przed innymi',
'revdelete-hide-image'        => 'Ukryj zawartość pliku',
'revdelete-unsuppress'        => 'Usuń ukrywanie dla odtwarzanej historii zmian',
'revdelete-log'               => 'Komentarz:',
'revdelete-submit'            => 'Zaakceptuj dla wybranych wersji',
'revdelete-logentry'          => 'zmienił widoczność wersji w [[$1]]',
'logdelete-logentry'          => 'zmienił widoczność zdarzenia dla [[$1]]',
'revdelete-success'           => "'''Pomyślnie zmieniono widoczność wersji.'''",
'logdelete-success'           => "'''Pomyślnie zmieniono widoczność zdarzeń.'''",
'revdel-restore'              => 'Zmień widoczność',
'pagehist'                    => 'Historia edycji strony',
'deletedhist'                 => 'Usunięta historia edycji',
'revdelete-content'           => 'zawartość',
'revdelete-summary'           => 'opis zmian',
'revdelete-uname'             => 'nazwa użytkownika',
'revdelete-restricted'        => 'ustaw ograniczenia dla administratorów',
'revdelete-unrestricted'      => 'usuń ograniczenia dla administratorów',
'revdelete-hid'               => 'ukryj $1',
'revdelete-unhid'             => 'nie ukrywaj $1',
'revdelete-log-message'       => '$1 - $2 {{PLURAL:$2|wersja|wersje|wersji}}',
'logdelete-log-message'       => '$1 - $2 {{PLURAL:$2|zdarzenie|zdarzenia|zdarzeń}}',

# Suppression log
'suppressionlog'     => 'Dziennik utajniania',
'suppressionlogtext' => 'Poniżej znajduje się lista ostatnich usunięć i blokad utajniona przed administratorami. Zobacz [[Special:Ipblocklist|rejestr blokowania adresów IP]] jeśli chcesz sprawdzić aktualne zakazy i blokady.',

# History merging
'mergehistory'                     => 'Scal historię zmian stron',
'mergehistory-header'              => 'Ta strona pozwala na scalenie historii zmian jednej strony z historią innej, nowszej strony.
Upewnij się, że zmiany będą zapewniać ciągłość historyczną edycji strony.',
'mergehistory-box'                 => 'Scal historię zmian dwóch stron:',
'mergehistory-from'                => 'Strona źródłowa:',
'mergehistory-into'                => 'Strona docelowa:',
'mergehistory-list'                => 'Historia zmian możliwa do scalenia',
'mergehistory-merge'               => 'Następujące zmiany strony [[:$1]] mogą zostać scalone z [[:$2]].
Oznacz w kolumnie kropeczką która zmiana, łącznie z wcześniejszymi, ma zostać scalona. 
Użycie linków nawigacyjnych kasuje wybór w kolumnie.',
'mergehistory-go'                  => 'Pokaż możliwe do scalenia zmiany',
'mergehistory-submit'              => 'Scal historię zmian',
'mergehistory-empty'               => 'Brak historii zmian do scalenia.',
'mergehistory-success'             => '$3 {{PLURAL:$3|zmiana|zmiany|zmian}} w [[:$1]] z powodzeniem zostało scalonych z [[:$2]].',
'mergehistory-fail'                => 'Scalenie historii zmian jest niewykonalne. Proszę zmienić ustawienia parametrów.',
'mergehistory-no-source'           => 'Strona źródłowa $1 nie istnieje.',
'mergehistory-no-destination'      => 'Strona docelowa $1 nie istnieje.',
'mergehistory-invalid-source'      => 'Strona źródłowa musi mieć poprawną nazwę.',
'mergehistory-invalid-destination' => 'Strona docelowa musi mieć poprawną nazwę.',
'mergehistory-autocomment'         => 'Historia [[:$1]] scalona z [[:$2]]',
'mergehistory-comment'             => 'Historia [[:$1]] scalona z [[:$2]]: $3',

# Merge log
'mergelog'           => 'Scalone',
'pagemerge-logentry' => 'scalił [[$1]] z [[$2]] (historia zmian aż do $3)',
'revertmerge'        => 'Rozdziel',
'mergelogpagetext'   => 'Poniżej znajduje się lista ostatnich scaleń historii zmian stron.',

# Diffs
'history-title'           => 'Historia edycji „$1”',
'difference'              => '(Różnice między wersjami)',
'lineno'                  => 'Linia $1:',
'compareselectedversions' => 'porównaj wybrane wersje',
'editundo'                => 'anuluj zmianę',
'diff-multi'              => '(Nie pokazano {{PLURAL:$1|jednej wersji pośredniej|$1 wersji pośrednich}}.)',

# Search results
'searchresults'         => 'Wyniki wyszukiwania',
'searchresulttext'      => 'Więcej informacji o przeszukiwaniu {{GRAMMAR:D.lp|{{SITENAME}}}} znajdziesz na stronie [[{{MediaWiki:Helppage}}|{{int:help}}]].',
'searchsubtitle'        => "Wyniki dla zapytania '''[[:$1]]'''",
'searchsubtitleinvalid' => "Dla zapytania '''$1'''",
'noexactmatch'          => "'''Nie ma strony zatytułowanej „$1”.'''
Możesz [[:$1|utworzyć tę stronę]].",
'noexactmatch-nocreate' => "'''Brak strony „$1”.'''",
'toomanymatches'        => 'Zbyt wiele elementów pasujących do wzorca, spróbuj innego zapytania',
'titlematches'          => 'Znaleziono w tytułach',
'notitlematches'        => 'Nie znaleziono w tytułach',
'textmatches'           => 'Znaleziono w treści stron',
'notextmatches'         => 'Nie znaleziono w treści stron',
'prevn'                 => '{{PLURAL:$1|poprzedni|poprzednie $1}}',
'nextn'                 => '{{PLURAL:$1|następny|następne $1}}',
'viewprevnext'          => 'Zobacz ($1) ($2) ($3).',
'search-result-size'    => '$1 ({{PLURAL:$2|1 słowo|$2 słowa|$2 słów}})',
'search-result-score'   => 'Trafność: $1%',
'search-redirect'       => '(przekierowanie $1)',
'search-section'        => '(sekcja $1)',
'search-suggest'        => 'Czy chodziło Ci o: $1',
'searchall'             => 'wszystkie',
'showingresults'        => "Oto lista z {{PLURAL:$1|'''1''' wynikiem|'''$1''' wynikami}}, rozpoczynając od wyniku numer '''$2'''.",
'showingresultsnum'     => "Oto lista z {{PLURAL:$3|'''1''' wynikiem|'''$3''' wynikami}}, rozpoczynając od wyniku numer '''$2'''.",
'showingresultstotal'   => "Poniżej znajdują się wyniki wyszukiwania '''$1 - $2''' z '''$3'''",
'nonefound'             => "'''Uwaga!''' Brak rezultatów wyszukiwania spowodowany jest bardzo często szukaniem najpopularniejszych słów, takich jak „jest” czy „nie”, które nie są indeksowane, albo z powodu podania w zapytaniu więcej niż jednego słowa (na liście odnalezionych stron znajdą się tylko te, które zawierają wszystkie podane słowa).",
'powersearch'           => 'Szukaj',
'powersearch-legend'    => 'Wyszukiwanie zaawansowane',
'powersearchtext'       => 'Szukaj w przestrzeniach nazw:<br />$1<br />$2 Pokaż przekierowania<br />Szukany tekst $3 $9',
'search-external'       => 'Wyszukiwanie zewnętrzne',
'searchdisabled'        => 'Wyszukiwanie w {{GRAMMAR:MS.lp|{{SITENAME}}}} zostało wyłączone.
W międzyczasie możesz skorzystać z wyszukiwania Google.
Jednak informacje o treści {{GRAMMAR:D.lp|{{SITENAME}}}} mogą być w Google nieaktualne.',

# Preferences page
'preferences'              => 'Preferencje',
'mypreferences'            => 'Moje preferencje',
'prefs-edits'              => 'Liczba edycji',
'prefsnologin'             => 'Nie jesteś zalogowany',
'prefsnologintext'         => 'Musisz się [[Special:Userlogin|zalogować]] przed zmianą swoich preferencji.',
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
'math_lexing_error'        => 'błędna nazwa',
'math_syntax_error'        => 'błąd składni',
'math_image_error'         => 'konwersja do formatu PNG nie powiodła się.
Sprawdź, czy poprawnie zainstalowane są latex, dvips, gs i convert',
'math_bad_tmpdir'          => 'Nie można utworzyć lub zapisywać w tymczasowym katalogu dla wzorów matematycznych',
'math_bad_output'          => 'Nie można utworzyć lub zapisywać w wyjściowym katalogu dla wzorów matematycznych',
'math_notexvc'             => 'Brak programu texvc.
Zapoznaj się z math/README w celu konfiguracji.',
'prefs-personal'           => 'Dane użytkownika',
'prefs-rc'                 => 'Ostatnie zmiany',
'prefs-watchlist'          => 'Obserwowane',
'prefs-watchlist-days'     => 'Liczba dni widocznych na liście obserwowanych',
'prefs-watchlist-edits'    => 'Liczba edycji pokazywanych w rozszerzonej liście obserwowanych',
'prefs-misc'               => 'Różne',
'saveprefs'                => 'Zapisz',
'resetprefs'               => 'Cofnij niezapisane zmiany',
'oldpassword'              => 'Stare hasło',
'newpassword'              => 'Nowe hasło',
'retypenew'                => 'Powtórz nowe hasło',
'textboxsize'              => 'Edytowanie',
'rows'                     => 'Wiersze',
'columns'                  => 'Kolumny',
'searchresultshead'        => 'Wyszukiwanie',
'resultsperpage'           => 'Liczba wyników na stronie',
'contextlines'             => 'Pierwsze wiersze stron',
'contextchars'             => 'Litery kontekstu w linijce',
'stub-threshold'           => 'Maksymalny (w bajtach) rozmiar strony oznaczanej jako <a href="#" class="stub">zalążek (stub)</a>',
'recentchangesdays'        => 'Liczba dni do pokazania w ostatnich zmianach',
'recentchangescount'       => 'Liczba pozycji na liście ostatnich zmian',
'savedprefs'               => 'Twoje preferencje zostały zapisane.',
'timezonelegend'           => 'Strefa czasowa',
'timezonetext'             => 'Liczba godzin różnicy między Twoim czasem lokalnym, a czasem uniwersalnym (UTC).',
'localtime'                => 'Twój czas lokalny',
'timezoneoffset'           => 'Różnica¹',
'servertime'               => 'Aktualny czas serwera',
'guesstimezone'            => 'Pobierz z przeglądarki',
'allowemail'               => 'Zgadzam się, by inni użytkownicy mogli przesyłać mi e-maile',
'defaultns'                => 'Przeszukuj następujące przestrzenie nazw domyślnie:',
'default'                  => 'domyślnie',
'files'                    => 'Pliki',

# User rights
'userrights'                       => 'Zarządzaj prawami użytkowników', # Not used as normal message but as header for the special page itself
'userrights-lookup-user'           => 'Zarządzaj grupami użytkownika',
'userrights-user-editname'         => 'Wprowadź nazwę użytkownika:',
'editusergroup'                    => 'Edytuj grupy użytkownika',
'editinguser'                      => "Zmiana uprawnień użytkownika '''[[User:$1|$1]]''' ([[User talk:$1|{{int:talkpagelinktext}}]] | [[Special:Contributions/$1|{{int:contribslink}}]])",
'userrights-editusergroup'         => 'Edytuj grupy użytkownika',
'saveusergroups'                   => 'Zapisz',
'userrights-groupsmember'          => 'Należy do:',
'userrights-groupsremovable'       => 'Usuwalny z grup:',
'userrights-groupsavailable'       => 'Dostępne grupy:',
'userrights-groups-help'           => 'Możesz modyfikować przynależność tego użytkownika do podanych grup.
Zaznaczone pole oznacza przynależność użytkownika do danej grupy.
Nie zaznaczone pole oznacza, że użytkownik nie należy do danej grupy.',
'userrights-reason'                => 'Powód zmiany:',
'userrights-available-none'        => 'Nie możesz zmieniać przynależności do grup.',
'userrights-available-add'         => 'Możesz dodać dowolnego użytkownika do {{PLURAL:$2|grupy|grup}}: $1.',
'userrights-available-remove'      => 'Możesz usunąć dowolnego użytkownika z {{PLURAL:$2|grupy|grup}}: $1.',
'userrights-available-add-self'    => 'Nie możesz dodać siebie do {{PLURAL:$2|grupy|grup}}: $1.',
'userrights-available-remove-self' => 'Nie możesz usunąć siebie z {{PLURAL:$2|grupy|grup}}: $1.',
'userrights-no-interwiki'          => 'Nie masz dostępu do edycji uprawnień na innych wiki.',
'userrights-nodatabase'            => 'Baza danych $1 nie istnieje lub nie jest lokalna.',
'userrights-nologin'               => 'Musisz [[Special:Userlogin|zalogować się]] na konto administratora, by nadawać uprawnienia użytkownikom.',
'userrights-notallowed'            => 'Nie masz dostępu do nadawania uprawnień użytkownikom.',
'userrights-changeable-col'        => 'Grupy, które możesz wybrać',
'userrights-unchangeable-col'      => 'Grupy, których nie możesz wybrać',

# Groups
'group'               => 'Grupa:',
'group-autoconfirmed' => 'Automatycznie zatwierdzeni użytkownicy',
'group-bot'           => 'Boty',
'group-sysop'         => 'Administratorzy',
'group-bureaucrat'    => 'Biurokraci',
'group-suppress'      => 'Rewizorzy',
'group-all'           => '(wszyscy)',

'group-autoconfirmed-member' => 'Automatycznie zatwierdzony użytkownik',
'group-bot-member'           => 'Bot',
'group-sysop-member'         => 'Administrator',
'group-bureaucrat-member'    => 'Biurokrata',
'group-suppress-member'      => 'Rewizor',

'grouppage-autoconfirmed' => '{{ns:project}}:Automatycznie zatwierdzeni użytkownicy',
'grouppage-bot'           => '{{ns:project}}:Boty',
'grouppage-sysop'         => '{{ns:project}}:Administratorzy',
'grouppage-bureaucrat'    => '{{ns:project}}:Biurokraci',
'grouppage-suppress'      => '{{ns:project}}:Rewizorzy',

# User rights log
'rightslog'      => 'Uprawnienia',
'rightslogtext'  => 'Rejestr zmian uprawnień użytkowników.',
'rightslogentry' => 'zmienił przynależności użytkownika $1 do grup ($2 → $3)',
'rightsnone'     => 'brak',

# Recent changes
'nchanges'                          => '$1 {{PLURAL:$1|zmiana|zmiany|zmian}}',
'recentchanges'                     => 'Ostatnie zmiany',
'recentchangestext'                 => 'Ta strona przedstawia historię ostatnich zmian w tej wiki.',
'recentchanges-feed-description'    => 'Obserwuj najświeższe zmiany w tej wiki.',
'rcnote'                            => "Poniżej {{PLURAL:$1|znajduje się ostatnia zmiana dokonana|znajdują się ostatnie '''$1''' zmiany dokonane|znajduje się ostatnich '''$1''' zmian dokonanych}} w ciągu {{PLURAL:$2|ostatniego dnia|ostatnich '''$2''' dni}}, poczynając od $3.",
'rcnotefrom'                        => "Poniżej pokazano zmiany dokonane po '''$2''' (nie więcej niż '''$1''' pozycji).",
'rclistfrom'                        => 'Pokaż zmiany od $1',
'rcshowhideminor'                   => '$1 drobne zmiany',
'rcshowhidebots'                    => '$1 boty',
'rcshowhideliu'                     => '$1 zalogowanych',
'rcshowhideanons'                   => '$1 anonimowych',
'rcshowhidepatr'                    => '$1 sprawdzone',
'rcshowhidemine'                    => '$1 moje edycje',
'rclinks'                           => 'Wyświetl ostatnie $1 zmian w ciągu ostatnich $2 dni.<br />$3',
'diff'                              => 'różn',
'hist'                              => 'hist',
'hide'                              => 'ukryj',
'show'                              => 'pokaż',
'minoreditletter'                   => 'd',
'newpageletter'                     => 'N',
'boteditletter'                     => 'b',
'number_of_watching_users_pageview' => '[$1 {{PLURAL:$1|obserwujący użytkownik|obserwujących użytkowników}}]',
'rc_categories'                     => 'Ogranicz do kategorii (oddzielaj za pomocą „|”)',
'rc_categories_any'                 => 'Wszystkie',
'newsectionsummary'                 => '/* $1 */ nowa sekcja',

# Recent changes linked
'recentchangeslinked'          => 'Zmiany w dolinkowanych',
'recentchangeslinked-title'    => 'Zmiany w stronach linkowanych z $1',
'recentchangeslinked-noresult' => 'Nie było żadnych zmian na dolinkowanych stronych w wybranym okresie.',
'recentchangeslinked-summary'  => "Ta strona specjalna zawiera listę ostatnich zmian dokonanych na stronach dolinkowanych.
Tytuły stron znajdujących się na Twojej liście obserwowanych zostały '''wytłuszczone'''.",

# Upload
'upload'                      => 'Prześlij plik',
'uploadbtn'                   => 'Prześlij plik',
'reupload'                    => 'Prześlij ponownie',
'reuploaddesc'                => 'Przerwij wysyłanie i wróć do formularza wysyłki',
'uploadnologin'               => 'Nie jesteś zalogowany',
'uploadnologintext'           => 'Musisz się [[Special:Userlogin|zalogować]] przed przesłaniem plików.',
'upload_directory_read_only'  => 'Serwer nie może zapisywać do katalogu ($1) przeznaczonego na przesyłane pliki.',
'uploaderror'                 => 'Błąd wysyłania',
'uploadtext'                  => "Użyj poniższego formularza do przesłania plików.
Jeśli chcesz przejrzeć lub przeszukać dotychczas przesłane pliki, przejdź do [[Special:Imagelist|listy plików]]. Wszystkie przesyłki są odnotowane w [[Special:Log/upload|rejestrze przesyłanych plików]].

Plik pojawi się na stronie, jeśli użyjesz linku według jednego z następujących wzorów:
'''<nowiki>[[</nowiki>{{ns:image}}<nowiki>:Plik.jpg]]</nowiki>''',
'''<nowiki>[[</nowiki>{{ns:image}}<nowiki>:Plik.png|tekst opisu]]</nowiki>''' lub
'''<nowiki>[[</nowiki>{{ns:media}}<nowiki>:Plik.ogg]]</nowiki>''' aby uzyskać bezpośredni link do pliku.",
'upload-permitted'            => 'Dopuszczalne formaty plików: $1.',
'upload-preferred'            => 'Zalecane formaty plików: $1.',
'upload-prohibited'           => 'Zabronione formaty plików: $1.',
'uploadlog'                   => 'rejestr przesyłania plików',
'uploadlogpage'               => 'Przesłane',
'uploadlogpagetext'           => 'Lista ostatnio przesłanych plików.',
'filename'                    => 'Nazwa pliku',
'filedesc'                    => 'Opis',
'fileuploadsummary'           => 'Opis:',
'filestatus'                  => 'Status prawny:',
'filesource'                  => 'Źródło:',
'uploadedfiles'               => 'Przesłane pliki',
'ignorewarning'               => 'Zignoruj ostrzeżenia i wymuś zapisanie pliku.',
'ignorewarnings'              => 'Ignoruj wszystkie ostrzeżenia',
'minlength1'                  => 'Nazwa pliku musi składać się co najmniej z jednej litery.',
'illegalfilename'             => 'Nazwa pliku „$1” zawiera znaki niedozwolone w tytułach stron.
Proszę zmienić nazwę pliku i przesłać go ponownie.',
'badfilename'                 => 'Nazwa pliku została zmieniona na „$1”.',
'filetype-badmime'            => 'Przesyłanie plików z typem MIME „$1” jest niedozwolone.',
'filetype-unwanted-type'      => "'''„.$1”''' nie jest zalecanym typem pliku. Pożądane są pliki w formatach $2.",
'filetype-banned-type'        => "'''„.$1”''' jest niedozwolonym typem pliku. Dopuszczalne są pliki w formatach $2.",
'filetype-missing'            => 'Plik nie ma rozszerzenia (np. „.jpg”).',
'large-file'                  => 'Zalecane jest aby rozmiar pliku nie był większy niż {{PLURAL:$1|1 bajt|$1 bajty|$1 bajtów}}.
Ten plik ma rozmiar {{PLURAL:$2|1 bajt|$2 bajty|$2 bajtów}}.',
'largefileserver'             => 'Plik jest większy niż maksymalny dozwolony rozmiar.',
'emptyfile'                   => 'Przesłany plik wydaje się być pusty. Może być to spowodowane literówką w nazwie pliku.
Sprawdź, czy nazwa jest prawidłowa.',
'fileexists'                  => 'Plik o takiej nazwie już istnieje. Sprawdź <strong><tt>$1</tt></strong>, jeśli nie jesteś pewien czy chcesz go wymienić.',
'filepageexists'              => 'Istnieje już strona opisu tego pliku utworzona <strong><tt>$1</tt></strong>, ale nie ma obecnie pliku o tej nazwie.
Informacje o pliku, które wprowadziłeś nie pojawią się na stronie opisu.
Jeśli chcesz by informacje te zostały wyświetlone musisz je ręcznie przeredagować',
'fileexists-extension'        => 'Plik o podobnej nazwie już istnieje:<br />
Nazwa przesyłanego pliku: <strong><tt>$1</tt></strong><br />
Nazwa istniejącego pliku: <strong><tt>$2</tt></strong><br />
Proszę wybrać inną nazwę.',
'fileexists-thumb'            => "<center>'''Istniejący plik'''</center>",
'fileexists-thumbnail-yes'    => 'Plik wydaje się być pomniejszoną grafiką <i>(miniaturką)</i>.
Sprawdź plik <strong><tt>$1</tt></strong>.<br />
Jeśli wybrany plik jest tą samą grafiką co ta w oryginalnym rozmiarze, nie musisz przesyłać dodatkowej miniaturki.',
'file-thumbnail-no'           => 'Nazwa pliku zaczyna się od <strong><tt>$1</tt></strong>.
Wydaje się, że jest to pomniejszona grafika <i>(miniaturka)</i>.
Jeśli posiadasz tę grafikę w pełnym rozmiarze - prześlij ją. Jeśli chcesz wysłać tą - zmień nazwę przesyłanego obecnie pliku.',
'fileexists-forbidden'        => 'Plik o tej nazwie już istnieje.
Wróć i załaduj ten plik pod inną nazwą. [[Image:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => 'Plik o tej nazwie już istnieje we współdzielonym repozytorium plików.
Wróć i załaduj ten plik pod inną nazwą. [[Image:$1|thumb|center|$1]]',
'successfulupload'            => 'Przesłanie pliku powiodło się',
'uploadwarning'               => 'Ostrzeżenie o przesyłce',
'savefile'                    => 'Zapisz plik',
'uploadedimage'               => 'przesłał [[$1]]',
'overwroteimage'              => 'przesłał nową wersję [[$1]]',
'uploaddisabled'              => 'Przesyłanie plików wyłączone',
'uploaddisabledtext'          => 'Funkcjonalność przesyłania plików została wyłączona w {{GRAMMAR:MS.lp|{{SITENAME}}}}.',
'uploadscripted'              => 'Ten plik zawiera kod HTML lub skrypt, który może zostać błędnie zinterpretowany przez przeglądarkę internetową.',
'uploadcorrupt'               => 'Ten plik jest uszkodzony lub ma nieprawidłowe rozszerzenie.
Proszę sprawdzić plik i załadować poprawną wersję.',
'uploadvirus'                 => 'W tym pliku jest wirus! Szczegóły: $1',
'sourcefilename'              => 'Nazwa oryginalna:',
'destfilename'                => 'Nazwa docelowa:',
'upload-maxfilesize'          => 'Maksymalna wielkość pliku: $1',
'watchthisupload'             => 'Obserwuj tę stronę',
'filewasdeleted'              => 'Plik o tej nazwie istniał, ale został usunięty.
Zanim załadujesz go ponownie, sprawdź $1.',
'upload-wasdeleted'           => "'''Uwaga! Ładujesz plik, który był poprzednio usunięty.'''

Zastanów się, czy powinno się ładować ten plik.
Rejestr usunięć tego pliku jest dla wygody podany poniżej:",
'filename-bad-prefix'         => 'Nazwa pliku, który przesyłasz, zaczyna się od <strong>„$1”</strong>. Jest to nazwa zazwyczaj przypisywana automatycznie przez cyfrowe aparaty fotograficzne, która nie informuje o zawartości pliku.
Zmień nazwę pliku na bardziej opisową.',
'filename-prefix-blacklist'   => '  #<!-- nie modyfikuj tej linii --> <pre>
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

'upload-proto-error'      => 'Nieprawidłowy protokół',
'upload-proto-error-text' => 'Zdalne przesyłanie plików wymaga podania adresu URL zaczynającego się od <code>http://</code> lub <code>ftp://</code>.',
'upload-file-error'       => 'Błąd wewnętrzny',
'upload-file-error-text'  => 'Wystąpił błąd wewnętrzny podczas próby utworzenia tymczasowego pliku na serwerze.
Skontaktuj się z administratorem systemu.',
'upload-misc-error'       => 'Nieznany błąd przesyłania',
'upload-misc-error-text'  => 'Wystąpił nieznany błąd podczas przesyłania.
Sprawdź czy podany adres URL jest poprawny i dostępny, a następnie spróbuj ponownie.
Jeśli problem będzie się powtarzał skontaktuj się z administratorem systemu.',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6'       => 'Adres URL jest nieosiągalny',
'upload-curl-error6-text'  => 'Podany adres URL jest nieosiągalny. Upewnij się czy podany adres URL jest prawidłowy i czy dana strona jest dostępna.',
'upload-curl-error28'      => 'Upłynął limit czasu odpowiedzi',
'upload-curl-error28-text' => 'Zbyt długi czas odpowiedzi serwera.
Sprawdź czy strona działa, odczekaj kilka minut i spróbuj ponownie.
Możesz także spróbować w czasie mniejszego obciążenia serwera.',

'license'            => 'Licencja:',
'nolicense'          => 'Nie wybrano',
'license-nopreview'  => '(Podgląd niedostępny)',
'upload_source_url'  => ' (poprawny, publicznie dostępny adres URL)',
'upload_source_file' => ' (plik na twoim komputerze)',

# Special:Imagelist
'imagelist-summary'     => 'To jest strona specjalna prezentująca wszystkie pliki przesłane na serwer.
Domyślnie na górze listy wyświetlane są ostatnio przesłane pliki.
Kliknięcie w nagłówek kolumny zmienia sposób sortowania.',
'imagelist_search_for'  => 'Szukaj pliku o nazwie:',
'imgdesc'               => 'opis',
'imgfile'               => 'plik',
'imagelist'             => 'Lista plików',
'imagelist_date'        => 'Data',
'imagelist_name'        => 'Nazwa',
'imagelist_user'        => 'Użytkownik',
'imagelist_size'        => 'Wielkość',
'imagelist_description' => 'Opis',

# Image description page
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
'shareduploadwiki-desc'     => 'Opis znajdujący się na $1 możesz zobaczyć poniżej.',
'shareduploadwiki-linktext' => 'stronę opisu pliku',
'noimage'                   => 'Nie istnieje plik o tej nazwie. Możesz go $1.',
'noimage-linktext'          => 'przesłać',
'uploadnewversion-linktext' => 'Załaduj nowszą wersję tego pliku',
'imagepage-searchdupe'      => 'Wyszukiwanie powtarzających się plików',

# File reversion
'filerevert'                => 'Przywracanie $1',
'filerevert-legend'         => 'Przywracanie poprzedniej wersji pliku',
'filerevert-intro'          => '<span class="plainlinks">Zamierzasz przywrócić \'\'\'[[Media:$1|$1]]\'\'\' do [wersji $4 z $3, $2].</span>',
'filerevert-comment'        => 'Komentarz:',
'filerevert-defaultcomment' => 'Przywrócono wersję z $2, $1',
'filerevert-submit'         => 'Przywróć',
'filerevert-success'        => '<span class="plainlinks">Plik \'\'\'[[Media:$1|$1]]\'\'\' został cofnięty do [wersji $4 z $3, $2].</span>',
'filerevert-badversion'     => 'Nie ma poprzedniej lokalnej wersji tego pliku z podaną datą.',

# File deletion
'filedelete'                  => 'Usunięcie $1',
'filedelete-legend'           => 'Usuń plik',
'filedelete-intro'            => "Usuwasz '''[[Media:$1|$1]]'''.",
'filedelete-intro-old'        => '<span class="plainlinks">Usuwasz wersję pliku \'\'\'[[Media:$1|$1]]\'\'\' z datą [$4 $3, $2].</span>',
'filedelete-comment'          => 'Komentarz:',
'filedelete-submit'           => 'Usuń',
'filedelete-success'          => "Usunięto plik '''$1'''.",
'filedelete-success-old'      => '<span class="plainlinks">Usunięto plik \'\'\'[[Media:$1|$1]]\'\'\' w wersji z $3, $2.</span>',
'filedelete-nofile'           => "Plik '''$1''' nie istnieje w {{GRAMMAR:MS.pl|{{SITENAME}}}}.",
'filedelete-nofile-old'       => "Nie ma zarchiwizowanej wersji '''$1''' o podanych atrybutach.",
'filedelete-iscurrent'        => 'Próbujesz usunąć najnowszą wersję tego pliku.
Musisz najpierw przywrócić starszą wersję.',
'filedelete-otherreason'      => 'Inna/dodatkowa przyczyna:',
'filedelete-reason-otherlist' => 'Inna przyczyna',
'filedelete-reason-dropdown'  => '* Najczęstsze przyczyny usunięcia
** Naruszenie praw autorskich
** Kopia istniejącego już pliku',
'filedelete-edit-reasonlist'  => 'Edycja listy powodów usunięcia pliku',

# MIME search
'mimesearch'         => 'Wyszukiwanie MIME',
'mimesearch-summary' => 'Ta strona umożliwia wyszukiwanie plików ze względu na ich typ MIME.
Użycie: typ_treści/podtyp, np. <tt>image/jpeg</tt>.',
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
'unusedtemplatestext' => 'Poniżej znajduje się lista wszystkich stron znajdujących się w przestrzeni nazw przeznaczonej dla szablonów, które nie są używane przez inne strony.
Sprawdź inne linki do szablonów zanim usuniesz tę stronę.',
'unusedtemplateswlh'  => 'inne linkujące',

# Random page
'randompage'         => 'Losuj stronę',
'randompage-nopages' => 'Nie ma żadnych stron w tej przestrzeni nazw.',

# Random redirect
'randomredirect'         => 'Losowe przekierowanie',
'randomredirect-nopages' => 'Nie ma przekierowań w tej przestrzeni nazw.',

# Statistics
'statistics'             => 'Statystyki',
'sitestats'              => 'Statystyka {{GRAMMAR:D.lp|{{SITENAME}}}}',
'userstats'              => 'Statystyka użytkowników',
'sitestatstext'          => "W bazie danych {{PLURAL:$1|jest '''1''' strona|są '''$1''' strony|jest '''$1''' stron}}.

Ta liczba uwzględnia strony dyskusji, strony na temat {{GRAMMAR:D.lp|{{SITENAME}}}}, zalążki (stuby), strony przekierowujące, oraz inne, które trudno uznać za artykuły.
Wyłączając powyższe, {{PLURAL:$2|jest|są|jest}} prawdopodobnie '''$2''' {{PLURAL:$2|strona, którą można uznać za artykuł|strony, które można uznać za artykuły|stron, które można uznać za artykuły}}.

Przesłano $8 {{PLURAL:$8|plik|pliki|plików}}.

Od uruchomienia {{GRAMMAR:D.lp|{{SITENAME}}}} {{PLURAL:$3|'''1''' raz odwiedzono strony|'''$3''' razy odwiedzono strony|było '''$3''' odwiedzin stron}} i wykonano '''$4''' {{PLURAL:$4|edycję|edycje|edycji}}.
Daje to średnio '''$5''' {{PLURAL:$5|edycję|edycje|edycji}} na stronę i '''$6''' {{PLURAL:$6|odwiedzinę|odwiedziny|odwiedzin}} na edycję.

Długość [http://meta.wikimedia.org/wiki/Help:Job_queue kolejki zadań] wynosi '''$7'''.",
'userstatstext'          => "Jest {{PLURAL:$1|'''1''' zarejestrowany użytkownik|'''$1''' zarejestrowanych użytkowników}}. {{PLURAL:$1|Użytkownik ten|Spośród nich '''$2''' (czyli '''$4%''')}} ma status $5.",
'statistics-mostpopular' => 'Najczęściej odwiedzane strony',

'disambiguations'         => 'Strony ujednoznaczniające',
'disambiguations-summary' => 'Poniżej znajduje się lista stron ujednoznaczniających.',
'disambiguationspage'     => '{{ns:template}}:disambig',
'disambiguations-text'    => "Poniższe strony odwołują się do '''stron ujednoznaczniających''',
a powinny odwoływać się bezpośrednio do stron treści.<br />
Strona uznawana jest za ujednoznaczniającą jeśli zawiera ona szablon linkowany przez stronę [[MediaWiki:Disambiguationspage]]",

'doubleredirects'     => 'Podwójne przekierowania',
'doubleredirectstext' => 'Lista zawiera strony z przekierowaniami do stron, które przekierowują do innej strony. Każdy wiersz zawiera linki do pierwszego i drugiego przekierowania oraz link do którego prowadzi drugie przekierowanie. Ostatni link prowadzi zazwyczaj do strony do której powinna w rzeczywistości przekierowywać pierwsza strona.',

'brokenredirects'        => 'Zerwane przekierowania',
'brokenredirectstext'    => 'Poniższe przekierowania wskazują na nieistniejące strony:',
'brokenredirects-edit'   => '(edytuj)',
'brokenredirects-delete' => '(usuń)',

'withoutinterwiki'        => 'Strony bez odnośników językowych',
'withoutinterwiki-header' => 'Poniższe strony nie odwołują się do innych wersji językowych:',
'withoutinterwiki-submit' => 'Pokaż',

'fewestrevisions' => 'Strony z najmniejszą ilością wersji',

# Miscellaneous special pages
'nbytes'                          => '$1 {{PLURAL:$1|bajt|bajty|bajtów}}',
'ncategories'                     => '$1 {{PLURAL:$1|kategoria|kategorie|kategorii}}',
'nlinks'                          => '$1 {{PLURAL:$1|link|linki|linków}}',
'nmembers'                        => '$1 {{PLURAL:$1|element|elementy|elementów}}',
'nrevisions'                      => '$1 {{PLURAL:$1|wersja|wersje|wersji}}',
'nviews'                          => 'odwiedzono $1 {{PLURAL:$1|raz|razy}}',
'specialpage-empty'               => 'Ta strona raportu jest pusta.',
'lonelypages'                     => 'Porzucone strony',
'lonelypagestext'                 => 'Do poniższych stron nie odwołuje się żadna inna strona w {{GRAMMAR:MS.lp|{{SITENAME}}}}.',
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
'mostlinked'                      => 'Najczęściej linkowane strony',
'mostlinkedcategories'            => 'Kategorie o największej liczbie artykułów',
'mostlinkedtemplates'             => 'Najczęściej linkowane szablony',
'mostcategories'                  => 'Artykuły z największą liczbą kategorii',
'mostcategories-summary'          => 'Poniżej znajduje się lista stron zawierających największą liczbę kategorii.',
'mostimages'                      => 'Najczęściej linkowane pliki',
'mostrevisions'                   => 'Strony o największej liczbie wersji',
'mostrevisions-summary'           => 'Poniżej znajduje się lista najczęściej edytowanych stron.',
'prefixindex'                     => 'Wszystkie strony według prefiksu',
'shortpages'                      => 'Najkrótsze strony',
'shortpages-summary'              => 'Poniżej znajduje się lista najkrótszych stron.',
'longpages'                       => 'Najdłuższe strony',
'longpages-summary'               => 'Poniżej znajduje się lista najdłuższych stron.',
'deadendpages'                    => 'Strony bez linków',
'deadendpagestext'                => 'Poniższe strony nie posiadają odnośników do innych stron znajdujących się w {{GRAMMAR:MS.pl|{{SITENAME}}}}.',
'protectedpages'                  => 'Strony zabezpieczone',
'protectedpagestext'              => 'Poniższe strony zostały zabezpieczone przed przenoszeniem lub edytowaniem.',
'protectedpagesempty'             => 'Żadna strona nie jest obecnie zablokowana z podanymi parametrami.',
'protectedtitles'                 => 'Zablokowane nazwy artykułów',
'protectedtitlestext'             => 'Utworzenie artykułów o następujących nazwach jest zablokowane',
'protectedtitlesempty'            => 'Dla tych ustawień dopuszczalne jest utworzenie artykułu o dowolnej nazwie',
'listusers'                       => 'Lista użytkowników',
'listusers-summary'               => 'Poniżej znajduje się lista wszystkich użytkowników zarejestrowanych w tej wiki.',
'specialpages'                    => 'Strony specjalne',
'spheading'                       => 'Strony specjalne dla wszystkich użytkowników',
'restrictedpheading'              => 'Strony specjalne z ograniczonym dostępem',
'newpages'                        => 'Nowe strony',
'newpages-username'               => 'Nazwa użytkownika:',
'ancientpages'                    => 'Najstarsze strony',
'move'                            => 'Przenieś',
'movethispage'                    => 'Przenieś tę stronę',
'unusedimagestext'                => 'Inne witryny mogą odwoływać się do tych plików używając bezpośrednich adresów URL. Oznacza to, że niektóre z plików mogą się znajdować na tej liście, pomimo tego, że są wykorzystywane.',
'unusedcategoriestext'            => 'Poniższe kategorie istnieją, choć nie korzysta z nich żaden artykuł ani kategoria.',
'notargettitle'                   => 'Wskazywana strona nie istnieje',
'notargettext'                    => 'Nie podano strony albo użytkownika, dla których ta operacja ma być wykonana.',
'pager-newer-n'                   => '{{PLURAL:$1|1 nowszy|$1 nowsze|$1 nowszych}}',
'pager-older-n'                   => '{{PLURAL:$1|1 starszy|$1 starsze|$1 starszych}}',
'suppress'                        => 'Rewizor',

# Book sources
'booksources'               => 'Książki',
'booksources-search-legend' => 'Szukaj informacji o książkach',
'booksources-go'            => 'Pokaż',
'booksources-text'          => 'Poniżej znajduje się lista odnośników do innych witryn, które pośredniczą w sprzedaży nowych i używanych książek, a także mogą posiadać dalsze informacje na temat poszukiwanej przez ciebie książki.',

# Special:Log
'specialloguserlabel'  => 'Użytkownik:',
'speciallogtitlelabel' => 'Tytuł:',
'log'                  => 'Rejestry operacji',
'all-logs-page'        => 'Wszystkie operacje',
'log-search-legend'    => 'Szukaj w rejestrze',
'log-search-submit'    => 'Szukaj',
'alllogstext'          => 'Wspólny rejestr wszystkich typów operacji dla {{GRAMMAR:D.lp|{{SITENAME}}}}.
Możesz zawęzić liczbę wyników przez wybranie typu rejestru, nazwy użytkownika albo tytułu strony.',
'logempty'             => 'Brak wpisów w rejestrze.',
'log-title-wildcard'   => 'Szukaj tytułów zaczynających się od tego tekstu',

# Special:Allpages
'allpages'          => 'Wszystkie strony',
'alphaindexline'    => 'od $1 do $2',
'nextpage'          => 'Następna strona ($1)',
'prevpage'          => 'Poprzednia strona ($1)',
'allpagesfrom'      => 'Strony o tytułach zaczynających się od:',
'allarticles'       => 'Wszystkie artykuły',
'allinnamespace'    => 'Wszystkie strony (w przestrzeni nazw $1)',
'allnotinnamespace' => 'Wszystkie strony (oprócz przestrzeni nazw $1)',
'allpagesprev'      => 'Poprzednia',
'allpagesnext'      => 'Następna',
'allpagessubmit'    => 'Pokaż',
'allpagesprefix'    => 'Pokaż strony o tytułach zaczynających się od:',
'allpagesbadtitle'  => 'Podana nazwa jest nieprawidłowa, zawiera prefiks międzyprojektowy lub międzyjęzykowy. Może ona także zawierać w sobie jeden lub więcej znaków, których użycie w nazwach jest niedozwolone.',
'allpages-bad-ns'   => 'W {{GRAMMAR:MS.lp|{{SITENAME}}}} nie istnieje przestrzeń nazw „$1”.',

# Special:Listusers
'listusersfrom'      => 'Wyświetl użytkowników zaczynając od:',
'listusers-submit'   => 'Pokaż',
'listusers-noresult' => 'Nie znaleziono żadnego użytkownika.',

# E-mail user
'mailnologin'     => 'Brak adresu',
'mailnologintext' => 'Musisz się [[Special:Userlogin|zalogować]] i mieć wpisany aktualny adres e-mailowy w swoich [[Special:Preferences|preferencjach]], aby móc wysłać e-mail do innego użytkownika.',
'emailuser'       => 'Wyślij e-mail do tego użytkownika',
'emailpage'       => 'Wyślij e-mail do użytkownika',
'emailpagetext'   => 'Poniższy formularz pozwala na wysłanie jednej wiadomości do użytkownika pod warunkiem, że wpisał on poprawny adres e-mail w swoich preferencjach. Adres e-mailowy, który został przez Ciebie wprowadzony w Twoich preferencjach pojawi się w polu „Od”, dzięki czemu odbiorca będzie mógł Ci odpowiedzieć.',
'usermailererror' => 'Moduł obsługi poczty zwrócił błąd:',
'defemailsubject' => 'Wiadomość od {{GRAMMAR:D.pl|{{SITENAME}}}}',
'noemailtitle'    => 'Brak adresu e-mail',
'noemailtext'     => 'Ten użytkownik nie podał poprawnego adresu e-mail, albo zadecydował, że nie chce otrzymywać wiadomości e-mail od innych użytkowników.',
'emailfrom'       => 'Od',
'emailto'         => 'Do',
'emailsubject'    => 'Temat',
'emailmessage'    => 'Wiadomość',
'emailsend'       => 'Wyślij',
'emailccme'       => 'Wyślij mi kopię mojej wiadomości.',
'emailccsubject'  => 'Kopia Twojej wiadomości do $1: $2',
'emailsent'       => 'Wiadomość została wysłana',
'emailsenttext'   => 'Twoja wiadomość została wysłana.',

# Watchlist
'watchlist'            => 'Obserwowane',
'mywatchlist'          => 'Obserwowane',
'watchlistfor'         => "(dla użytkownika '''$1''')",
'nowatchlist'          => 'Nie ma żadnych pozycji na liście obserwowanych przez Ciebie stron.',
'watchlistanontext'    => '$1 aby obejrzeć lub edytować elementy listy obserwowanych.',
'watchnologin'         => 'Brak logowania',
'watchnologintext'     => 'Musisz się [[Special:Userlogin|zalogować]] przed modyfikacją listy obserwowanych artykułów.',
'addedwatch'           => 'Dodana do listy obserwowanych',
'addedwatchtext'       => "Strona „[[:$1|$1]]” została dodana do Twojej [[Special:Watchlist|listy obserwowanych]].
Każda zmiana treści tej strony lub związanej z nią strony dyskusji zostanie odnotowana na poniższej liście. Dodatkowo nazwa strony zostanie '''wytłuszczona''' na [[Special:Recentchanges|liście ostatnich zmian]], aby ułatwić Ci zauważenie faktu zmiany.",
'removedwatch'         => 'Usunięto z listy obserwowanych',
'removedwatchtext'     => 'Strona „[[:$1]]” została usunięta z Twojej listy obserwowanych.',
'watch'                => 'Obserwuj',
'watchthispage'        => 'Obserwuj tę stronę',
'unwatch'              => 'Nie obserwuj',
'unwatchthispage'      => 'Przestań obserwować',
'notanarticle'         => 'To nie jest artykuł',
'notvisiblerev'        => 'Wersja została usunięta',
'watchnochange'        => 'Żadna z obserwowanych stron nie była edytowana w podanym okresie.',
'watchlist-details'    => '$1 {{PLURAL:$1|strona obserwowana|strony obserwowane|stron obserwowanych}}, nie licząc stron dyskusji.',
'wlheader-enotif'      => '* Wysyłanie powiadomień na adres e-mail jest włączone.',
'wlheader-showupdated' => "* Strony, które zostały zmodyfikowane od Twojej ostatniej wizyty na nich zostały '''wytłuszczone'''",
'watchmethod-recent'   => 'poszukiwanie ostatnich zmian wśród obserwowanych stron',
'watchmethod-list'     => 'poszukiwanie obserwowanych stron wśród ostatnich zmian',
'watchlistcontains'    => 'Lista obserwowanych przez Ciebie stron zawiera {{PLURAL:$1|jedną pozycję|$1 pozycje|$1 pozycji}}.',
'iteminvalidname'      => 'Problem z pozycją „$1”, niepoprawna nazwa...',
'wlnote'               => "Poniżej pokazano {{PLURAL:$1|ostatnią zmianę dokonaną|ostatnie '''$1''' zmiany dokonane|ostatnie '''$1''' zmian dokonanych}} w ciągu {{PLURAL:$2|ostatniej godziny|ostatnich '''$2''' godzin}}.",
'wlshowlast'           => 'Pokaż ostatnie $1 godzin $2 dni ($3)',
'watchlist-show-bots'  => 'pokaż edycje botów',
'watchlist-hide-bots'  => 'ukryj edycje botów',
'watchlist-show-own'   => 'pokaż moje edycje',
'watchlist-hide-own'   => 'ukryj moje edycje',
'watchlist-show-minor' => 'pokaż drobne zmiany',
'watchlist-hide-minor' => 'ukryj drobne zmiany',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'Obserwuję...',
'unwatching' => 'Przestaję obserwować...',

'enotif_mailer'                => 'Powiadomienie z {{GRAMMAR:D.lp|{{SITENAME}}}}',
'enotif_reset'                 => 'Zaznacz wszystkie strony jako odwiedzone',
'enotif_newpagetext'           => 'To jest nowa strona.',
'enotif_impersonal_salutation' => 'użytkownik {{GRAMMAR:D.lp|{{SITENAME}}}}',
'changed'                      => 'zmieniona',
'created'                      => 'utworzona',
'enotif_subject'               => 'Strona $PAGETITLE w {{GRAMMAR:MS.lp|{{SITENAME}}}} została $CHANGEDORCREATED przez użytkownika $PAGEEDITOR',
'enotif_lastvisited'           => 'Zobacz na stronie $1 wszystkie zmiany od Twojej ostatniej wizyty.',
'enotif_lastdiff'              => 'Zobacz na stronie $1 tą zmianę.',
'enotif_anon_editor'           => 'użytkownik anonimowy $1',
'enotif_body'                  => 'Drogi (droga) $WATCHINGUSERNAME,

strona $PAGETITLE w {{GRAMMAR:MS.lp|{{SITENAME}}}} została $CHANGEDORCREATED $PAGEEDITDATE przez użytkownika $PAGEEDITOR. Zobacz na stronie $PAGETITLE_URL aktualną wersję.

$NEWPAGE

Opis zmiany: $PAGESUMMARY $PAGEMINOREDIT

Skontaktuj się z autorem:
mail: $PAGEEDITOR_EMAIL
wiki: $PAGEEDITOR_WIKI

W przypadku kolejnych zmian nowe powiadomienia nie zostaną wysłane, dopóki nie odwiedzisz tej strony.
Możesz także zresetować wszystkie flagi powiadomień na swojej liście stron obserwowanych.

	Wiadomość systemu powiadomień {{GRAMMAR:D.lp|{{SITENAME}}}}

--
W celu zmiany ustawień swojej listy obserwowanych odwiedź
{{fullurl:{{ns:special}}:Watchlist/edit}}

Pomoc:
{{fullurl:{{MediaWiki:Helppage}}}}',

# Delete/protect/revert
'deletepage'                  => 'Usuń stronę',
'confirm'                     => 'Potwierdź',
'excontent'                   => 'zawartość strony „$1”',
'excontentauthor'             => 'treść: „$1” (jedyny autor: [[Special:Contributions/$2|$2]])',
'exbeforeblank'               => 'poprzednia zawartość, obecnie pustej strony: „$1”',
'exblank'                     => 'Strona była pusta',
'delete-confirm'              => 'Usuń „$1”',
'delete-legend'               => 'Usuń',
'historywarning'              => 'Uwaga! Strona, którą chcesz usunąć ma starsze wersje:',
'confirmdeletetext'           => 'Zamierzasz usunąć stronę razem z całą dotyczącą jej historią.
Upewnij się czy na pewno chcesz to zrobić, że rozumiesz konsekwencje i że robisz to w zgodzie z [[{{MediaWiki:Policy-url}}|zasadami]].',
'actioncomplete'              => 'Operacja wykonana',
'deletedtext'                 => 'Usunięto „<nowiki>$1</nowiki>”.
Zobacz na stronie $2 rejestr ostatnio wykonanych usunięć.',
'deletedarticle'              => 'usunął [[$1]]',
'suppressedarticle'           => 'utajnił [[$1]]',
'dellogpage'                  => 'Usunięte',
'dellogpagetext'              => 'To jest lista ostatnio wykonanych usunięć.',
'deletionlog'                 => 'rejestr usunięć',
'reverted'                    => 'Przywrócono poprzednią wersję',
'deletecomment'               => 'Powód usunięcia',
'deleteotherreason'           => 'Inna/dodatkowa przyczyna:',
'deletereasonotherlist'       => 'Inna przyczyna',
'deletereason-dropdown'       => '* Najczęstsze przyczyny usunięcia
** Prośba autora
** Naruszenie praw autorskich
** Wandalizm',
'delete-edit-reasonlist'      => 'Edycja listy powodów usunięcia strony',
'delete-toobig'               => 'Ta strona ma bardzo długą historię edycji, ponad $1 {{PLURAL:$1|zmianę|zmiany|zmian}}.
Usunięcie jej mogłoby spowodować zakłócenia w pracy {{GRAMMAR:D.lp|{{SITENAME}}}} i dlatego zostało ograniczone.',
'delete-warning-toobig'       => 'Ta strona ma bardzo długą historię edycji, ponad $1 {{PLURAL:$1|zmianę|zmiany|zmian}}.
Bądź ostrożny, ponieważ usunięcie jej może spowodować zakłócenia w pracy {{GRAMMAR:D.lp|{{SITENAME}}}}.',
'rollback'                    => 'Cofnij edycję',
'rollback_short'              => 'Cofnij',
'rollbacklink'                => 'cofnij',
'rollbackfailed'              => 'Nie udało się cofnąć zmiany',
'cantrollback'                => 'Nie można cofnąć edycji, ponieważ jest tylko jedna wersja tej strony.',
'alreadyrolled'               => 'Nie można dla strony [[:$1|$1]] cofnąć ostatniej zmiany, którą wykonał [[User:$2|$2]] ([[User talk:$2|dyskusja]]).
Ktoś inny zdążył już to zrobić lub wprowadził własne poprawki do treści strony.

Autorem ostatniej zmiany jest teraz [[User:$3|$3]] ([[User talk:$3|dyskusja]]).',
'editcomment'                 => 'Edycję opisano: „<i>$1</i>”.', # only shown if there is an edit comment
'revertpage'                  => 'Wycofano edycję użytkownika [[Special:Contributions/$2|$2]] ([[User talk:$2|dyskusja]]).
Autor przywróconej wersji to [[User:$1|$1]].', # Additional available: $3: revid of the revision reverted to, $4: timestamp of the revision reverted to, $5: revid of the revision reverted from, $6: timestamp of the revision reverted from
'rollback-success'            => 'Wycofano edycje użytkownika $1.
Przywrócono ostatnią wersję autorstwa $2.',
'sessionfailure'              => 'Błąd weryfikacji zalogowania.
Polecenie zostało anulowane, aby uniknąć przechwycenia sesji.

Naciśnij „wstecz”, przeładuj stronę, po czym ponownie wydaj polecenie.',
'protectlogpage'              => 'Zabezpieczone',
'protectlogtext'              => 'Poniżej znajduje się lista blokad założonych i zdjętych z pojedynczych stron.
Aby przejrzeć listę obecnie działających zabezpieczeń, przejdź na stronę wykazu [[Special:Protectedpages|zabezpieczonych stron]].',
'protectedarticle'            => 'zabezpieczył [[$1]]',
'modifiedarticleprotection'   => 'zmienił poziom zabezpieczenia [[$1]]',
'unprotectedarticle'          => 'odbezpieczył [[$1]]',
'protect-title'               => 'Zmiana poziomu zabezpieczenia „$1”',
'protect-legend'              => 'Potwierdź zabezpieczenie',
'protectcomment'              => 'Powód zabezpieczenia',
'protectexpiry'               => 'Upływa za',
'protect_expiry_invalid'      => 'Podany czas automatycznego odblokowania jest nieprawidłowy.',
'protect_expiry_old'          => 'Podany czas automatycznego odblokowania znajduje się w przeszłości.',
'protect-unchain'             => 'Odblokowanie możliwości przenoszenia strony',
'protect-text'                => 'Możesz tu sprawdzić i zmienić poziom zabezpieczenia strony <strong><nowiki>$1</nowiki></strong>.',
'protect-locked-blocked'      => 'Nie możesz zmienić poziomów zabezpieczenia będąc zablokowanym. Obecne ustawienia dla strony <strong>$1</strong> to:',
'protect-locked-dblock'       => 'Nie można zmienić poziomu zabezpieczenia z powodu działającej blokady bazy danych. Obecne ustawienia dla strony <strong>$1</strong> to:',
'protect-locked-access'       => 'Nie masz uprawnień do zmiany poziomu zabezpieczenia strony. Obecne ustawienia dla strony <strong>$1</strong> to:',
'protect-cascadeon'           => 'Ta strona jest zabezpieczona przed edycją, ponieważ jest używana przez {{PLURAL:$1|następującą stronę, która została zabezpieczona|następujące strony, które zostały zabezpieczone}} z włączoną opcją dziedziczenia. Możesz zmienić poziom zabezpieczenia strony, ale nie wpłynie to na dziedziczenie zabezpieczenia.',
'protect-default'             => '(wszyscy)',
'protect-fallback'            => 'Wymaga uprawnień „$1”',
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
'undelete'                     => 'Odtwórz usuniętą stronę',
'undeletepage'                 => 'Odtwarzanie usuniętych stron',
'undeletepagetitle'            => "'''Poniżej znajdują się usunięte wersje strony [[:$1]]'''.",
'viewdeletedpage'              => 'Zobacz usunięte wersje',
'undeletepagetext'             => 'Poniższe strony zostały usunięte, ale ich kopia wciąż znajduje się w archiwum.
Archiwum co jakiś czas może być oczyszczane.',
'undeleteextrahelp'            => "Aby odtworzyć całą stronę, pozostaw wszystkie pola niezaznaczone i kliknij '''Odtwórz'''. Aby wybrać częściowe odtworzenie należy zaznaczyć odpowiednie pole. Naciśnięcie '''Wyczyść''' wyczyści wszystkie pola, łącznie z opisem komentarza.",
'undeleterevisions'            => '$1 {{PLURAL:$1|zarchiwizowana wersja|zarchiwizowane wersje|zarchiwizowanych wersji}}',
'undeletehistory'              => 'Odtworzenie strony spowoduje przywrócenie także jej wszystkich poprzednich wersji. Jeśli od czasu usunięcia ktoś utworzył nową stronę o tej nazwie, odtwarzane wersje znajdą się w jej historii, a obecna wersja pozostanie bez zmian.',
'undeleterevdel'               => 'Odtworzenie strony nie zostanie przeprowadzone w wypadku, gdyby miało skutkować częściowym usunięciem aktualnej wersji. W takiej sytuacji należy odznaczyć lub przywrócić widoczność najnowszym usuniętym wersjom.',
'undeletehistorynoadmin'       => 'Ten artykuł został usunięty. Przyczyna usunięcia podana jest w podsumowaniu poniżej, razem z danymi użytkownika, który edytował artykuł przed usunięciem. Sama treść usuniętych wersji jest dostępna jedynie dla administratorów.',
'undelete-revision'            => 'Usunięto wersję $1 z $2 autorstwa $3:',
'undeleterevision-missing'     => 'Nieprawidłowa lub brakująca wersja. Możesz mieć zły link lub wersja mogła zostać odtworzona lub usunięta z archiwum.',
'undelete-nodiff'              => 'Nie znaleziono poprzednich wersji.',
'undeletebtn'                  => 'Odtwórz',
'undeletelink'                 => 'odtwórz',
'undeletereset'                => 'Wyczyść',
'undeletecomment'              => 'Powód odtworzenia:',
'undeletedarticle'             => 'odtworzył [[$1]]',
'undeletedrevisions'           => 'Odtworzono {{PLURAL:$1|1 wersję|$1 wersje|$1 wersji}}',
'undeletedrevisions-files'     => 'Odtworzono $1 {{PLURAL:$1|wersję|wersje|wersji}} i $2 {{PLURAL:$2|plik|pliki|plików}}',
'undeletedfiles'               => 'odtworzył $1 {{PLURAL:$1|plik|pliki|plików}}',
'cannotundelete'               => 'Odtworzenie nie powiodło się. Ktoś inny mógł odtworzyć stronę pierwszy.',
'undeletedpage'                => "<big>'''Odtworzono stronę $1.'''</big>

Zobacz [[Special:Log/delete|rejestr usunięć]], jeśli chcesz przejrzeć ostatnie operacje usuwania i odtwarzania stron.",
'undelete-header'              => 'Zobacz [[Special:Log/delete|rejestr usunięć]] aby sprawdzić ostatnio usunięte strony.',
'undelete-search-box'          => 'Szukaj usuniętych stron',
'undelete-search-prefix'       => 'Strony zaczynające się od:',
'undelete-search-submit'       => 'Szukaj',
'undelete-no-results'          => 'Nie znaleziono wskazanych stron w archiwum usuniętych.',
'undelete-filename-mismatch'   => 'Nie można odtworzyć wersji pliku z datą $1: niezgodność nazwy pliku',
'undelete-bad-store-key'       => 'Nie można odtworzyć wersji pliku z datą $1: przed usunięciem brakowało pliku.',
'undelete-cleanup-error'       => 'Wystąpił błąd przy usuwaniu nieużywanego archiwalnego pliku „$1”.',
'undelete-missing-filearchive' => 'Nie udało się odtworzyć z archiwum pliku o ID $1, ponieważ nie ma go w bazie danych. Być może plik został już odtworzony.',
'undelete-error-short'         => 'Wystąpił błąd przy odtwarzaniu pliku: $1',
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
'uctop'         => ' (jako ostatnia)',
'month'         => 'Od miesiąca (i wcześniejsze):',
'year'          => 'Od roku (i wcześniejsze):',

'sp-contributions-newbies'     => 'Pokaż wkład nowych użytkowników',
'sp-contributions-newbies-sub' => 'Dla nowych użytkowników',
'sp-contributions-blocklog'    => 'blokady',
'sp-contributions-search'      => 'Szukaj wkładu',
'sp-contributions-username'    => 'Adres IP lub nazwa użytkownika:',
'sp-contributions-submit'      => 'Szukaj',

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
'blockip-legend'              => 'Zablokuj użytkownika',
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
'blockipsuccesstext'          => 'Użytkownik [[Special:Contributions/$1|$1]] został zablokowany.<br />
Przejdź do [[Special:Ipblocklist|listy zablokowanych adresów IP]] by przejrzeć blokady.',
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
'blocklistline'               => '$1, $2 zablokował $3 ($4)',
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
'autoblocker'                 => 'Zablokowano Cię automatycznie, ponieważ używasz tego samego adresu IP, co użytkownik „[[User:$1|$1]]”.
Powód blokady $1 to: „$2”',
'blocklogpage'                => 'Historia blokad',
'blocklogentry'               => 'zablokował [[$1]], czas blokady: $2 $3',
'blocklogtext'                => 'Poniżej znajduje się lista blokad założonych i zdjętych z poszczególnych adresów IP. Na liście nie znajdą się adresy IP, które zablokowano w sposób automatyczny. By przejrzeć listę obecnie aktywnych blokad, przejdź na stronę [[Special:Ipblocklist|zablokowanych adresów i użytkowników]].',
'unblocklogentry'             => 'odblokował $1',
'block-log-flags-anononly'    => 'tylko anonimowi',
'block-log-flags-nocreate'    => 'blokada tworzenia konta',
'block-log-flags-noautoblock' => 'autoblok wyłączony',
'block-log-flags-noemail'     => 'e-mail zablokowany',
'range_block_disabled'        => 'Możliwość blokowania zakresu numerów IP została wyłączona.',
'ipb_expiry_invalid'          => 'Błędny czas blokady.',
'ipb_already_blocked'         => '„$1” jest już zablokowany',
'ipb_cant_unblock'            => 'Błąd: Blokada o ID $1 nie została znaleziona. Mogła ona zostać odblokowana wcześniej.',
'ipb_blocked_as_range'        => 'Błąd: Adres IP $1 nie został zablokowany bezpośrednio i nie może zostać odblokowany. Należy on do zablokowanego zakresu adresów $2. Odblokować można tylko cały zakres.',
'ip_range_invalid'            => 'Niewłaściwy zakres adresów IP.',
'blockme'                     => 'Zablokuj mnie',
'proxyblocker'                => 'Blokowanie proxy',
'proxyblocker-disabled'       => 'Ta funkcja jest wyłączona.',
'proxyblockreason'            => 'Twój adres IP został zablokowany - jest to otwarte proxy. Sprawę należy rozwiązać u dostawcy Internetu.',
'proxyblocksuccess'           => 'Wykonane.',
'sorbsreason'                 => 'Twój adres IP znajduje się na liście serwerów open proxy w DNSBL, używanej przez {{GRAMMAR:B.lp|{{SITENAME}}}}.',
'sorbs_create_account_reason' => 'Twój adres IP znajduje się na liście serwerów open proxy w DNSBL, używanej przez {{GRAMMAR:B.lp|{{SITENAME}}}}. Nie możesz utworzyć konta',

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
'lockdbsuccesstext'   => 'Baza danych została zablokowana.<br />
Pamiętaj by [[Special:Unlockdb|usunąć blokadę]] po zakończeniu działań administracyjnych.',
'unlockdbsuccesstext' => 'Baza danych została odblokowana.',
'lockfilenotwritable' => 'Nie można zapisać pliku blokady bazy danych. Aby móc blokować i odblokowywać bazę danych, plik musi mieć właściwe prawa dostępu.',
'databasenotlocked'   => 'Baza danych nie jest zablokowana.',

# Move page
'move-page'               => 'Przenieś $1',
'move-page-legend'        => 'Przeniesienie strony',
'movepagetext'            => "Za pomocą poniższego formularza zmienisz nazwę strony, przenosząc jednocześnie jej historię.
Pod starym tytułem zostanie umieszczona strona przekierowująca. Linki do starego tytułu pozostaną niezmienione.
Upewnij się, że uwzględniasz podwójne lub zerwane przekierowania. Odpowiadasz za to, żeby linki odnosiły się do właściwych artykułów!

Strona '''nie''' zostanie przeniesiona jeśli:
*jest pusta i nigdy nie była edytowana
*jest stroną przekierowującą
*strona o nowej nazwie już istnieje

'''UWAGA!'''
Może to być drastyczna lub nieprzewidywalna zmiana w przypadku popularnych stron.
Upewnij się co do konsekwencji tej operacji zanim się na nią zdecydujesz.",
'movepagetalktext'        => "Odpowiednia strona dyskusji, jeśli istnieje, będzie przeniesiona automatycznie, pod warunkiem, że:
*nie przenosisz strony do innej przestrzeni nazw
*nie istnieje strona dyskusji o nowej nazwie
W takich przypadkach tekst dyskusji trzeba przenieść, i ewentualnie połączyć z istniejącym, ręcznie. Możesz też zrezygnować z przeniesienia dyskusji (poniższy ''checkbox'').",
'movearticle'             => 'Przeniesienie strony',
'movenologin'             => 'Brak logowania',
'movenologintext'         => 'Musisz być zarejestrowanym i [[Special:Userlogin|zalogowanym]] użytkownikiem aby móc przenieść stronę.',
'movenotallowed'          => 'Nie masz uprawnień do przenoszenia stron w {{GRAMMAR:MS.lp|{{SITENAME}}}}.',
'newtitle'                => 'Nowy tytuł',
'move-watch'              => 'Obserwuj tę stronę',
'movepagebtn'             => 'Przenieś stronę',
'pagemovedsub'            => 'Przeniesienie powiodło się',
'movepage-moved'          => "<big>'''Strona „$1” została przeniesiona do „$2”.'''</big>", # The two titles are passed in plain text as $3 and $4 to allow additional goodies in the message.
'articleexists'           => 'Strona o podanej nazwie już istnieje albo wybrana przez Ciebie nazwa nie jest poprawna. Wybierz, proszę, nową nazwę.',
'cantmove-titleprotected' => 'Nie możesz przenieść strony, ponieważ nowa nazwa strony jest niedozwolona z powodu zabezpieczenia przed utworzeniem',
'talkexists'              => 'Strona artykułu została przeniesiona, natomiast strona dyskusji nie - strona dyskusji o nowym tytule już istnieje. Połącz, proszę, teksty obu dyskusji ręcznie.',
'movedto'                 => 'przeniesiono do',
'movetalk'                => 'Przenieś także stronę dyskusji, jeśli to możliwe.',
'talkpagemoved'           => 'Odpowiednia strona dyskusji także została przeniesiona.',
'talkpagenotmoved'        => 'Odpowiednia strona dyskusji <strong>nie</strong> została przeniesiona.',
'1movedto2'               => 'stronę [[$1]] przeniósł do [[$2]]',
'1movedto2_redir'         => 'stronę [[$1]] przeniósł do [[$2]] nad przekierowaniem',
'movelogpage'             => 'Przeniesione',
'movelogpagetext'         => 'Oto lista stron, które ostatnio zostały przeniesione.',
'movereason'              => 'Powód',
'revertmove'              => 'cofnij',
'delete_and_move'         => 'Usuń i przenieś',
'delete_and_move_text'    => '== Wymagane usunięcie ==

Artykuł docelowy „[[:$1|$1]]” już istnieje. Czy chcesz go usunąć, by zrobić miejsce dla przenoszonego artykułu?',
'delete_and_move_confirm' => 'Tak, usuń stronę docelową',
'delete_and_move_reason'  => 'Usunięto by zrobić miejsce dla przenoszonego artykułu',
'selfmove'                => 'Nazwy stron źródłowej i docelowej są takie same. Strony nie można przenieść na nią samą!',
'immobile_namespace'      => 'Docelowy tytuł jest specjalnego typu. Nie można przenieść do tej przestrzeni nazw.',

# Export
'export'            => 'Eksport stron',
'exporttext'        => 'Możesz wyeksportować treść i historię edycji jednej strony lub zestawu stron w postaci XML.
Taki zrzut można później zaimportować do innej wiki działającej na oprogramowaniu MediaWiki korzystając ze [[Special:Import|strony importu]].

Wyeksportowanie wielu stron wymaga wpisania poniżej tytułów stron, po jednym tytule w wierszu oraz określenia czy ma zostać wyeksportowana bieżąca czy wszystkie wersje strony z opisami edycji lub też tylko bieżąca wersja z opisem ostatniej edycji.

Możesz również użyć łącza, np. [[{{ns:special}}:Export/{{MediaWiki:Mainpage}}]] dla strony „[[{{MediaWiki:Mainpage}}]]”.',
'exportcuronly'     => 'Tylko bieżąca wersja, bez historii',
'exportnohistory'   => "----
'''Uwaga:''' możliwość eksportowania pełnej historii stron została wyłączona.",
'export-submit'     => 'Eksportuj',
'export-addcattext' => 'Dodaj strony z kategorii:',
'export-addcat'     => 'Dodaj',
'export-download'   => 'Zapisz do pliku',
'export-templates'  => 'Dołącz szablony',

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
'filemissing'              => 'Brak pliku',
'thumbnail_error'          => 'Błąd przy generowaniu miniatury: $1',
'djvu_page_error'          => 'Strona DjVu poza zakresem',
'djvu_no_xml'              => 'Nie można pobrać XML-u dla pliku DjVu',
'thumbnail_invalid_params' => 'Nieprawidłowe parametry miniatury',
'thumbnail_dest_directory' => 'Nie można utworzyć katalogu docelowego',

# Special:Import
'import'                     => 'Importuj strony',
'importinterwiki'            => 'Import transwiki',
'import-interwiki-text'      => 'Wybierz wiki i nazwę strony do importowania.
Daty oraz nazwy autorów zostaną zachowane.
Wszystkie operacje importu transwiki są odnotowywane w [[Special:Log/import|rejestrze importu]].',
'import-interwiki-history'   => 'Kopiuj całą historię edycji tej strony',
'import-interwiki-submit'    => 'Importuj',
'import-interwiki-namespace' => 'Przenieś strony do przestrzeni nazw:',
'importtext'                 => 'Używając narzędzia Special:Export wyeksportuj plik ze źródłowej wiki, zapisz go na swoim dysku, a następnie prześlij go tutaj.',
'importstart'                => 'Trwa importowanie stron...',
'import-revision-count'      => '$1 {{PLURAL:$1|wersja|wersje|wersji}}',
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
'importuploaderrorsize'      => 'Przesyłanie pliku importowanego zawiodło. Jest większy niż dopuszczalny rozmiar dla przesyłanego pliku.',
'importuploaderrorpartial'   => 'Przesyłanie pliku importowanego zawiodło. Został przesłany tylko częściowo.',
'importuploaderrortemp'      => 'Przesyłanie pliku importowanego zawiodło. Brak katalogu na dla plików tymczasowych.',
'import-parse-failure'       => 'nieudana analiza składni importowanego XML',
'import-noarticle'           => 'Brak stron do importu!',
'import-nonewrevisions'      => 'Wszystkie wersje wcześniej zaimportowane.',
'xml-error-string'           => '$1 linia $2, kolumna $3 (bajt $4): $5',

# Import log
'importlogpage'                    => 'Rejestr importu',
'importlogpagetext'                => 'Rejestr przeprowadzonych importów stron z innych serwisów wiki.',
'import-logentry-upload'           => 'zaimportował [[$1]] przez przesłanie pliku',
'import-logentry-upload-detail'    => '$1 {{PLURAL:$1|wersja|wersje|wersji}}',
'import-logentry-interwiki'        => 'zaimportował $1 przez transwiki',
'import-logentry-interwiki-detail' => '$1 {{PLURAL:$1|wersja|wersje|wersji}} z $2',

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
'anonymous'        => 'Anonimowi użytkownicy {{GRAMMAR:D.lp|{{SITENAME}}}}',
'siteuser'         => 'Użytkownik {{GRAMMAR:D.lp|{{SITENAME}}}} - $1',
'lastmodifiedatby' => 'Ostatnia edycja tej strony: $2, $1 (autor zmian: $3)', # $1 date, $2 time, $3 user
'othercontribs'    => 'Inni autorzy: $1.',
'others'           => 'inni',
'siteusers'        => 'Użytkownicy {{GRAMMAR:D.lp|{{SITENAME}}}} - $1',
'creditspage'      => 'Autorzy',
'nocredits'        => 'Nie ma informacji o autorach tej strony.',

# Spam protection
'spamprotectiontitle' => 'Filtr antyspamowy',
'spamprotectiontext'  => 'Strona, którą chciałeś/aś zapisać, została zablokowana przez filtr antyspamowy. Najprawdopodobniej zostało to spowodowane przez link do zewnętrznej strony internetowej.',
'spamprotectionmatch' => 'Tekst, który uruchomił nasz filtr antyspamowy to: $1',
'spambot_username'    => 'MediaWiki czyszczenie spamu',
'spam_reverting'      => 'Przywracanie ostatniej wersji nie zawierającej odnośników do $1',
'spam_blanking'       => 'Wszystkie wersje zawierały odnośniki do $1; czyszczenie strony',

# Info page
'infosubtitle'   => 'Informacja o stronie',
'numedits'       => 'Liczba edycji (artykuł): $1',
'numtalkedits'   => 'Liczba edycji (strona dyskusji): $1',
'numwatchers'    => 'Liczba obserwujących: $1',
'numauthors'     => 'Liczba autorów (artykuł): $1',
'numtalkauthors' => 'Liczba autorów (strona dyskusji): $1',

# Math options
'mw_math_png'    => 'Zawsze generuj grafikę PNG',
'mw_math_simple' => 'HTML dla prostych, dla pozostałych grafika PNG',
'mw_math_html'   => 'Spróbuj HTML, a jeśli zawiedzie użyj grafiki PNG',
'mw_math_source' => 'Pozostaw w TeXu (dla przeglądarek tekstowych)',
'mw_math_modern' => 'HTML - zalecane dla nowych przeglądarek',
'mw_math_mathml' => 'MathML jeśli dostępny (eksperymentalne)',

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
'patrol-log-page' => 'Rejestr patrolowania',
'patrol-log-line' => 'oznaczył wersję $1 hasła $2 jako sprawdzoną $3',
'patrol-log-auto' => '(automatycznie)',

# Image deletion
'deletedrevision'                 => 'Usunięto poprzednie wersje $1',
'filedeleteerror-short'           => 'Błąd przy usuwaniu pliku: $1',
'filedeleteerror-long'            => 'Wystąpiły błędy przy usuwaniu pliku:

$1',
'filedelete-missing'              => 'Pliku „$1” nie można usunąć, ponieważ nie istnieje.',
'filedelete-old-unregistered'     => 'Żądanej wersji pliku „$1” nie ma w bazie danych.',
'filedelete-current-unregistered' => 'Pliku „$1” nie ma w bazie danych.',
'filedelete-archive-read-only'    => 'Serwer nie może pisać do katalogu archiwum „$1”.',

# Browsing diffs
'previousdiff' => '← Poprzednia edycja',
'nextdiff'     => 'Następna edycja →',

# Media information
'mediawarning'         => "'''Uwaga!''' Ten plik może zawierać złośliwy kod, otwierając go możesz zarazić swój system.<hr />",
'imagemaxsize'         => 'Na stronach opisu pokaż grafiki przeskalowane do rozdzielczości',
'thumbsize'            => 'Rozmiar miniaturki',
'widthheightpage'      => '$1×$2, $3 stron',
'file-info'            => '(rozmiar pliku: $1, typ MIME: $2)',
'file-info-size'       => '($1 × $2 pikseli, rozmiar pliku: $3, typ MIME: $4)',
'file-nohires'         => '<small>Grafika w wyższej rozdzielczości jest niedostępna.</small>',
'svg-long-desc'        => '(Plik SVG, nominalnie $1 × $2 pikseli, rozmiar pliku: $3)',
'show-big-image'       => 'Oryginalna rozdzielczość',
'show-big-image-thumb' => '<small>Rozmiar podglądu: $1 × $2 pikseli</small>',

# Special:Newimages
'newimages'             => 'Najnowsze grafiki',
'imagelisttext'         => "Poniżej na {{PLURAL:$1||posortowanej $2}} liście {{PLURAL:$1|znajduje|znajdują|znajduje}} się '''$1''' {{PLURAL:$1|plik|pliki|plików}}.",
'newimages-summary'     => 'Na tej stronie specjalnej prezentowane są ostatnio wgrane pliki.',
'showhidebots'          => '($1 boty)',
'noimages'              => 'Brak plików do wyświetlenia.',
'ilsubmit'              => 'Szukaj',
'bydate'                => 'według daty',
'sp-newimages-showfrom' => 'Pokaż nowe grafiki od $1',

# Bad image list
'bad_image_list' => 'Dane należy prowadzić w formacie:

Jedynie elementy listy (linie zaczynające się od znaku *) brane są pod uwagę. Pierwszy link w linii musi być linkiem do zabronionego pliku. Następne linki w linii są traktowane jako wyjątki, są to nazwy stron, gdzie plik o zabronionej nazwie może być wstawiony.',

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
'confirmemail'             => 'Potwierdź adres e-mail',
'confirmemail_noemail'     => 'Nie podałeś prawidłowego adresu e-mail w [[Special:Preferences|preferencjach]].',
'confirmemail_text'        => '{{SITENAME}} wymaga potwierdzenia adresu e-mail przed użyciem funkcji korzystających z poczty. Wciśnij przycisk poniżej aby wysłać na swój adres list z linkiem do strony WWW. Następnie otwórz ten link w przeglądarce, czym potwierdzisz wiarygodność adresu e-mail.',
'confirmemail_pending'     => '<div class="error">Kod potwierdzenia został właśnie do Ciebie wysłany. Jeśli zarejestrowałeś się niedawno, poczekaj kilka minut na dostarczenie wiadomości przed kolejną prośbą o wysłanie kodu.</div>',
'confirmemail_send'        => 'Wyślij kod uwierzytelniający',
'confirmemail_sent'        => 'E-mail uwierzytelniający został wysłany.',
'confirmemail_oncreate'    => 'Kod potwierdzenia został wysłany na Twój adres E-mail. Kod ten nie jest wymagany do zalogowania się, jednak będziesz musiał go podać przed włączeniem niektórych opcji e-mail na wiki.',
'confirmemail_sendfailed'  => 'Nie udało się wysłać e-maila potwierdzającego. Proszę sprawdzić czy w adresie nie ma literówki.

Program zwrócił komunikat: $1',
'confirmemail_invalid'     => 'Błędny kod potwierdzenia. Kod może być przedawniony.',
'confirmemail_needlogin'   => 'Musisz $1 aby potwierdzić adres email.',
'confirmemail_success'     => 'Adres e-mail został potwierdzony. Możesz się zalogować i korzystać z szerszego wachlarza funkcjonalności wiki.',
'confirmemail_loggedin'    => 'Twój adres email został zweryfikowany.',
'confirmemail_error'       => 'Pojawiły się błędy przy zapisywaniu potwierdzenia.',
'confirmemail_subject'     => '{{SITENAME}} - potwierdzenie adresu e-mail',
'confirmemail_body'        => 'Ktoś łącząc się z komputera o adresie IP $1
zarejestrował w {{GRAMMAR:MS.lp|{{SITENAME}}}} konto "$2" podając niniejszy adres e-mail.

Aby potwierdzić, że to Ty zarejestrowałeś/aś to konto oraz, aby włączyć
wszystkie funkcje korzystające z poczty elektronicznej, otwórz w swojej
przeglądarce ten link:

$3

Jeśli to *nie* Ty zarejestrowałeś/aś konto, otwórz w swojej przeglądarce
poniższy link, aby anulować potwierdzenie adresu e-mail:

$5

Kod zawarty w linku straci ważność $4.',
'confirmemail_invalidated' => 'Potwierdzenie adresu e-mail zostało anulowane',
'invalidateemail'          => 'Anulowanie potwierdzenia adresu e-mail',

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
'deletedwhileediting' => 'Uwaga! Ta strona została usunięta po tym, jak rozpocząłeś jej edycję!',
'confirmrecreate'     => "Użytkownik [[User:$1|$1]] ([[User talk:$1|dyskusja]]) usunął ten artykuł po tym jak rozpocząłeś jego edycję, podając jako powód usunięcia:
: ''$2''
Potwierdź chęć ponownego utworzenia tego artykułu.",
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
'articletitles'    => "Artykuły zaczynające się od ''$1''",
'hideresults'      => 'Ukryj wyniki',
'useajaxsearch'    => 'Użyj wyszukiwania AJAX',

# Multipage image navigation
'imgmultipageprev' => '← poprzednia strona',
'imgmultipagenext' => 'następna strona →',
'imgmultigo'       => 'Przejdź',
'imgmultigotopre'  => 'Przejdź na stronę',

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
'autosumm-blank'   => 'Uwaga! Usunięcie treści (strona pozostała pusta)!',
'autosumm-replace' => 'Uwaga! Zastąpienie treści hasła bardzo krótkim tekstem: „$1”',
'autoredircomment' => 'Przekierowanie do [[$1]]',
'autosumm-new'     => 'Nowa strona: $1',

# Size units
'size-kilobytes' => '$1 kB',

# Live preview
'livepreview-loading' => 'Trwa ładowanie…',
'livepreview-ready'   => 'Trwa ładowanie… Gotowe!',
'livepreview-failed'  => 'Podgląd na żywo nie zadziałał! Spróbuj podglądu standardowego.',
'livepreview-error'   => 'Nieudane połączenie: $1 „$2” Spróbuj podglądu standardowego.',

# Friendlier slave lag warnings
'lag-warn-normal' => 'Na tej liście zmiany nowsze niż $1 sekund mogą nie być pokazane.',
'lag-warn-high'   => 'Z powodu dużego obciążenia serwerów bazy danych, na tej liście zmiany nowsze niż $1 sekund mogą nie być pokazane.',

# Watchlist editor
'watchlistedit-numitems'       => 'Twoja lista obserwowanych zawiera {{PLURAL:$1|1 tytuł|$1 tytuły|$1 tytułów}}, nieuwzględniając strony dyskusji.',
'watchlistedit-noitems'        => 'Twoja lista obserwowanych nie zawiera żadnych tytułów.',
'watchlistedit-normal-title'   => 'Edytuj listę obserwowanych stron',
'watchlistedit-normal-legend'  => 'Usuń tytuły z listy obserwowanych',
'watchlistedit-normal-explain' => 'Poniżej znajduje się lista obserwowanych przez Ciebie stron. Aby usunąć obserwowaną stronę z listy zaznacz znajdujące się obok niej pole i naciśnij „Usuń zaznaczone pozycje”. Możesz także skorzystać z [[Special:Watchlist/raw|edytora surowej listy obserwowanych]].',
'watchlistedit-normal-submit'  => 'Usuń tytuły',
'watchlistedit-normal-done'    => '{{PLURAL:$1|1 tytuł został usunięty|$1 tytuły zostały usunięte|$1 tytułów zostało usuniętych}} z twojej listy obserwowanych:',
'watchlistedit-raw-title'      => 'Edycja surowej listy obserwowanych',
'watchlistedit-raw-legend'     => 'Edycja surowej listy obserwowanych',
'watchlistedit-raw-explain'    => 'Poniżej znajduje się lista obserwowanych artykułów. W każdej linii znajduje się tytuł jednego artykułu. Listę możesz modyfikować poprzez dodawania nowych i usuwanie obecnych. Gdy zakończysz, kliknij „Uaktualnij listę obserwowanych”.
Możesz również [[Special:Watchlist/edit|użyć standardowego edytora]].',
'watchlistedit-raw-titles'     => 'Tytuły:',
'watchlistedit-raw-submit'     => 'Uaktualnij listę obserwowanych',
'watchlistedit-raw-done'       => 'Lista obserwowanych stron została uaktualniona.',
'watchlistedit-raw-added'      => '{{PLURAL:$1|1 tytuł został dodany|$1 tytuły zostały dodane|$1 tytułów zostało dodanych}}:',
'watchlistedit-raw-removed'    => '{{PLURAL:$1|1 tytuł został usunięty|$1 tytuły zostały usunięte|$1 tytułów zostało usuniętych}}:',

# Watchlist editing tools
'watchlisttools-view' => 'Pokaż ważniejsze zmiany',
'watchlisttools-edit' => 'Pokaż i edytuj listę',
'watchlisttools-raw'  => 'Edytuj surową listę',

# Core parser functions
'unknown_extension_tag' => 'Nieznany znacznik rozszerzenia „$1”',

# Special:Version
'version'                          => 'Wersja oprogramowania', # Not used as normal message but as header for the special page itself
'version-extensions'               => 'Zainstalowane rozszerzenia',
'version-specialpages'             => 'Strony specjalne',
'version-parserhooks'              => 'Haki analizatora składni (Parser hooks)',
'version-variables'                => 'Zmienne',
'version-other'                    => 'Pozostałe',
'version-mediahandlers'            => 'Wtyczki obsługi mediów',
'version-hooks'                    => 'Haki (Hooks)',
'version-extension-functions'      => 'Funkcje rozszerzeń',
'version-parser-extensiontags'     => 'Znaczniki rozszerzeń dla analizatora składni',
'version-parser-function-hooks'    => 'Funkcje haków analizatora składni (Parser function hooks)',
'version-skin-extension-functions' => 'Funkcje rozszerzeń skórek',
'version-hook-name'                => 'Nazwa haka (Hook name)',
'version-hook-subscribedby'        => 'Zapotrzebowany przez',
'version-version'                  => 'Wersja',
'version-license'                  => 'Licencja',
'version-software'                 => 'Zainstalowane oprogramowanie',
'version-software-product'         => 'Nazwa',
'version-software-version'         => 'Wersja',

# Special:Filepath
'filepath'         => 'Ścieżka do pliku',
'filepath-page'    => 'Plik:',
'filepath-submit'  => 'Ścieżka',
'filepath-summary' => 'Ta strona specjalna zwraca pełną ścieżkę do pliku. Grafiki są pokazywane w pełnej rozdzielczości, inne typy plików są otwierane w skojarzonym z nimi programie. Wpisz nazwę pliku bez prefiksu „{{ns:image}}:”.',

# Special:FileDuplicateSearch
'fileduplicatesearch'          => 'Szukaj duplikatów pliku',
'fileduplicatesearch-summary'  => 'Szukaj duplikatów pliku na podstawie wartości funkcji skrótu.

Wpisz nazwę pliku z pominięciem prefiksu „{{ns:image}}:”.',
'fileduplicatesearch-legend'   => 'Szukaj duplikatów pliku',
'fileduplicatesearch-filename' => 'Nazwa pliku:',
'fileduplicatesearch-submit'   => 'Szukaj',
'fileduplicatesearch-info'     => '$1 × $2 pikseli<br />Wielkość pliku: $3<br />Typ MIME: $4',
'fileduplicatesearch-result-1' => 'Nie ma duplikatu pliku „$1”.',
'fileduplicatesearch-result-n' => 'W {{GRAMMAR:MS.lp|{{SITENAME}}}} {{PLURAL:$2|jest dodatkowa kopia|są $2 dodatkowe kopie|jest $2 dodatkowych kopii}} pliku „$1”.',

);
