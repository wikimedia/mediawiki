<?php
/** Polish (Polski)
 *
 * @package MediaWiki
 * @subpackage Language
 */

$namespaceNames = array(
	NS_MEDIA            => 'Media',
	NS_SPECIAL          => 'Specjalna',
	NS_MAIN             => '',
	NS_TALK             => 'Dyskusja',
	NS_USER             => 'Użytkownik',
	NS_USER_TALK        => 'Dyskusja_użytkownika',
	# NS_PROJECT set by $wgMetaNamespace
	NS_PROJECT_TALK     => 'Dyskusja_$1',
	NS_IMAGE            => 'Grafika',
	NS_IMAGE_TALK       => 'Dyskusja_grafiki',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'Dyskusja_MediaWiki',
	NS_TEMPLATE         => 'Szablon',
	NS_TEMPLATE_TALK    => 'Dyskusja_szablonu',
	NS_HELP             => 'Pomoc',
	NS_HELP_TALK        => 'Dyskusja_pomocy',
	NS_CATEGORY         => 'Kategoria',
	NS_CATEGORY_TALK    => 'Dyskusja_kategorii'
);

$quickbarSettings = array(
	'Brak', 'Stały, z lewej', 'Stały, z prawej', 'Unoszący się, z lewej'
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


$messages = array(

# User preference toggles
'tog-underline' => 'Podkreślenie linków:',
'tog-highlightbroken' => '<a href="" class="new">Podświetl</a> linki pustych stron (alternatywa: znak zapytania<a href="" class="internal">?</a>).',
'tog-justify' => 'Wyrównuj tekst w akapitach do obu stron',
'tog-hideminor' => 'Ukryj drobne zmiany w "Ostatnich zmianach"',
'tog-extendwatchlist' => 'Rozszerzona lista obserwowanych',
'tog-usenewrc' => 'Rozszerzenie ostatnich zmian (JavaScript)',
'tog-numberheadings' => 'Automatyczna numeracja nagłówków',
'tog-showtoolbar' => 'Pokaż pasek narzędzi (JavaScript)',
'tog-editondblclick' => 'Podwójne kliknięcie rozpoczyna edycję (JavaScript)',
'tog-editsection' => 'Możliwość edycji poszczególnych sekcji strony',
'tog-editsectiononrightclick' => 'Kliknięcie prawym klawiszem na tytule sekcji<br />rozpoczyna jej edycję (JavaScript)',
'tog-showtoc' => 'Pokaż spis treści (na stronach zawierających więcej niż 3 nagłówki)',
'tog-rememberpassword' => 'Pamiętaj hasło między sesjami',
'tog-editwidth' => 'Obszar edycji o pełnej szerokości',
'tog-watchcreations' => 'Dodaj tworzone przeze mnie strony do obserwowanych',
'tog-watchdefault' => 'Obserwuj strony, które będę edytować',
'tog-minordefault' => 'Wszystkie zmiany zaznaczaj domyślnie jako drobne',
'tog-previewontop' => 'Pokazuj podgląd przed obszarem edycji',
'tog-previewonfirst' => 'Pokaż podgląd strony podczas pierwszej edycji',
'tog-nocache' => 'Wyłącz pamięć podręczną',
'tog-enotifwatchlistpages' => 'Wyślij e-mail kiedy obserwowana przeze mnie strona ulegnie zmianie',
'tog-enotifusertalkpages' => 'Wyślij e-mail kiedy moja strona dyskusji ulegnie zmianie',
'tog-enotifminoredits' => 'Wyślij e-mail także w przypadku drobnych zmian na stronach',
'tog-enotifrevealaddr' => 'Ujawnij mój adres e-mail w zawiadomieniach',
'tog-shownumberswatching' => 'Pokaż liczbę obserwujących użytkowników',
'tog-fancysig' => 'Podpis bez automatycznego linku',
'tog-externaleditor' => 'Domyślnie używaj zewnętrznego edytora',
'tog-externaldiff' => 'Domyślnie używaj zewnętrznego programu pokazującego zmiany',
'tog-showjumplinks' => 'Włącz odnośniki "skocz do"',
'tog-uselivepreview' => 'Używaj dynamicznego podglądu (JavaScript) (eksperymentalna)',
'tog-autopatrol' => 'Zaznacz moje edycje jako patrolowane',
'tog-forceeditsummary' => 'Informuj o niewypełnieniu pola opisu zmian',
'tog-watchlisthideown' => 'Ukryj moje edycje w obserwowanych',
'tog-watchlisthidebots' => 'Ukryj edycje botów w obserwowanych',

'underline-always' => 'Zawsze',
'underline-never' => 'Nigdy',
'underline-default' => 'Ustawienia przeglądarki',

'skinpreview' => '(podgląd)',

# dates
'sunday' => 'niedziela',
'monday' => 'poniedziałek',
'tuesday' => 'wtorek',
'wednesday' => 'środa',
'thursday' => 'czwartek',
'friday' => 'piątek',
'saturday' => 'sobota',
'sun' => 'Nie',
'mon' => 'Pon',
'tue' => 'Wto',
'wed' => 'Śro',
'thu' => 'Czw',
'fri' => 'Pią',
'sat' => 'Sob',
'january' => 'styczeń',
'february' => 'luty',
'march' => 'marzec',
'april' => 'kwiecień',
'may_long' => 'maj',
'june' => 'czerwiec',
'july' => 'lipiec',
'august' => 'sierpień',
'september' => 'wrzesień',
'october' => 'październik',
'november' => 'listopad',
'december' => 'grudzień',
'january-gen' => 'stycznia',
'february-gen' => 'lutego',
'march-gen' => 'marca',
'april-gen' => 'kwietnia',
'may-gen' => 'maja',
'june-gen' => 'czerwca',
'july-gen' => 'lipca',
'august-gen' => 'sierpnia',
'september-gen' => 'września',
'october-gen' => 'października',
'november-gen' => 'listopada',
'december-gen' => 'grudnia',
'jan' => 'sty',
'feb' => 'lut',
'mar' => 'mar',
'apr' => 'kwi',
'may' => 'maj',
'jun' => 'cze',
'jul' => 'lip',
'aug' => 'sie',
'sep' => 'wrz',
'oct' => 'paź',
'nov' => 'lis',
'dec' => 'gru',

# Bits of text used by many pages:
#
'categories' => 'Kategorie',
'pagecategories' => '{{PLURAL:$1|Kategoria|Kategorie}}',
'category_header' => 'Artykuły w kategorii "$1"',
'subcategories' => 'Podkategorie',

'mainpage' => 'Strona główna',
'mainpagetext' => "<big>'''Instalacja oprogramowania powiodła się.'''</big>",
'mainpagedocfooter' => 'Zobacz [http://meta.wikimedia.org/wiki/Help:Contents przewodnik użytkownika] w celu uzyskania informacji o działaniu oprogramowania wiki.

== Na początek ==

* [http://www.mediawiki.org/wiki/Help:Configuration_settings Lista ustawień konfiguracyjnych]
* [http://www.mediawiki.org/wiki/Help:FAQ MediaWiki FAQ]
* [http://mail.wikimedia.org/mailman/listinfo/mediawiki-announce Ogłoszenia o wydaniach MediaWiki]',

'portal' => 'Portal użytkowników',
'portal-url' => 'Project:Portal użytkowników',
'about' => 'O serwisie',
'aboutsite' => 'O serwisie {{SITENAME}}',
'aboutpage' => 'Project:O serwisie',
'article' => 'Artykuł',
'help' => 'Pomoc',
'helppage' => 'Project:Pomoc',
'bugreports' => 'Raport o błędach',
'bugreportspage' => 'Project:Błędy',
'sitesupport' => 'Dary pieniężne',
'sitesupport-url' => 'Project:Dary pieniężne',
'faq' => 'FAQ',
'edithelp' => 'Pomoc w edycji',
'newwindow' => '(otwiera się w nowym oknie)',
'edithelppage' => 'Project:Jak_edytować_stronę',
'cancel' => 'Anuluj',
'qbfind' => 'Znajdź',
'qbbrowse' => 'Przeglądanie',
'qbedit' => 'Edycja',
'qbpageoptions' => 'Opcje strony',
'qbpageinfo' => 'O stronie',
'qbmyoptions' => 'Moje opcje',
'qbspecialpages' => 'Strony specjalne',
'moredotdotdot' => 'Więcej...',
'mypage' => 'Moja strona',
'mytalk' => 'Moja dyskusja',
'anontalk' => 'Dyskusja tego IP',
'navigation' => 'Nawigacja',

# Metadata in edit box
'metadata_help' => 'Metadane (zobacz [[{{ns:project}}:Metadane]]):',

'currentevents' => 'Bieżące wydarzenia',
'currentevents-url' => 'Bieżące wydarzenia',

'disclaimers' => 'Informacje Prawne',
'privacy' => 'Zasady ochrony prywatności',
'privacypage' => '{{ns:Project}}:Zasady ochrony prywatności',
'errorpagetitle' => 'Błąd',
'returnto' => 'Wróć do strony $1.',
'help' => 'Pomoc',
'search' => 'Szukaj',
'searchbutton' => 'Szukaj',
'go' => 'Przejdź',
'searcharticle' => 'Przejdź',
'history' => 'Historia strony',
'history_short' => 'Historia',
'info_short' => 'Informacja',
'printableversion' => 'Wersja do druku',
'permalink' => 'Bezpośredni link',
'print' => 'Drukuj',
'edit' => 'Edytuj',
'editthispage' => 'Edytuj tę stronę',
'delete' => 'Usuń',
'deletethispage' => 'Usuń tę stronę',
'undelete_short' => 'Odtwórz {{PLURAL:$1|jedną wersję|$1 wersji}}',
'protect' => 'Zabezpiecz',
'protectthispage' => 'Zabezpiecz tę stronę',
'unprotect' => 'Odbezpiecz',
'unprotectthispage' => 'Odbezpiecz tę stronę',
'newpage' => 'Nowa strona',
'talkpage' => 'Dyskusja',
'specialpage' => 'strona specjalna',
'personaltools' => 'Osobiste',
'postcomment' => 'Skomentuj',
'articlepage' => 'Strona artykułu',
'talk' => 'dyskusja',
'views' => 'widok',
'toolbox' => 'Narzędzia',
'userpage' => 'Strona użytkownika',
'projectpage' => 'Strona projektu',
'imagepage' => 'Strona grafiki',
'mediawikipage' => 'Strona komunikatu',
'templatepage' => 'Strona szablonu',
'viewhelppage' => 'Strona pomocy',
'categorypage' => 'Strona kategorii',
'viewtalkpage' => 'Strona dyskusji',
'otherlanguages' => 'W innych językach',
'redirectedfrom' => '(Przekierowano z $1)',
'autoredircomment' => 'Przekierowanie do [[$1]]',
'redirectpagesub' => 'Strona przekierowująca',
'lastmodifiedat' => 'Tę stronę ostatnio zmodyfikowano $2, $1.',
'viewcount' => 'Tę stronę obejrzano {{plural:$1|jeden raz|$1 razy}}.',
'copyright' => 'Tekst udostępniany na licencji $1.',
'protectedpage' => 'Strona zabezpieczona',
'jumpto' => 'Skocz do:',
'jumptonavigation' => 'nawigacji',
'jumptosearch' => 'wyszukiwania',

'badaccess' => 'Nieprawidłowe uprawnienia',
'badaccess-group0' => 'Nie masz uprawnień wymaganych do wykonania tej operacji.',
'badaccess-group1' => 'Wykonywanie tej operacji zostało ograniczone do użytkowników w grupie $1.',
'badaccess-group2' => 'Wykonywanie tej operacji zostało ograniczone do użytkowników w jednej z grup $1.',
'badaccess-groups' => 'Wykonywanie tej operacji zostało ograniczone do użytkowników w jednej z grup $1.',

'versionrequired' => 'Wymagana MediaWiki w wersji $1',
'versionrequiredtext' => 'Wymagana jest MediaWiki w wersji $1 aby skorzystać z tej strony . Zobacz [[Special:Version]]',

'retrievedfrom' => 'Źródło: "$1"',
'youhavenewmessages' => 'Masz $1 ($2).',
'newmessageslink' => 'nowe wiadomości',
'newmessagesdifflink' => 'różnica z poprzednią wersją',
'editsection' => 'edytuj',
'editold' => 'edytuj',
'editsectionhint' => 'Edytuj sekcję: $1',
'toc' => 'Spis treści',
'showtoc' => 'pokaż',
'hidetoc' => 'ukryj',
'thisisdeleted' => 'Pokaż/odtwórz $1',
'viewdeleted' => 'Zobacz $1',
'restorelink' => '{{PLURAL:$1|jedną skasowaną wersję|skasowane wersje (w sumie $1)}}',
'feedlinks' => 'Kanały:',
'feed-invalid' => 'Niewłaściwy typ kanału informacyjnego.',

# Short words for each namespace, by default used in the 'article' tab in monobook
'nstab-main' => 'Artykuł',
'nstab-user' => 'Strona użytkownika',
'nstab-media' => 'Media',
'nstab-special' => 'Strona specjalna',
'nstab-project' => 'Strona projektu',
'nstab-image' => 'Plik',
'nstab-mediawiki' => 'Komunikat',
'nstab-template' => 'Szablon',
'nstab-help' => 'Strona pomocy',
'nstab-category' => 'Kategoria',

# Main script and global functions
#
'nosuchaction' => 'Nie ma takiej operacji',
'nosuchactiontext' => 'Oprogramowanie nie rozpoznaje
operacji takiej jak podana w URL',
'nosuchspecialpage' => 'Nie ma takiej strony specjalnej',
'nospecialpagetext' => 'Oprogramowanie nie rozpoznaje takiej specjalnej strony. Listę stron specjalnych znajdziesz na [[{{ns:Special}}:Specialpages]]',

# General errors
#
'error' => 'Błąd',
'databaseerror' => 'Błąd bazy danych',
'dberrortext' => 'Wystąpił błąd składni w zapytaniu do bazy danych.
Ostatnie, nieudane zapytanie to:
<blockquote><tt>$1</tt></blockquote>
wysłane przez funkcję "<tt>$2</tt>".
MySQL zgłosił błąd "<tt>$3: $4</tt>".',
'dberrortextcl' => 'Wystąpił błąd składni w zapytaniu do bazy danych.
Ostatnie, nieudane zapytanie to:
"$1"
wywołane zostało przez funkcję "$2".
MySQL zgłosił błąd "$3: $4"',
'noconnect' => 'Przepraszamy! {{SITENAME}} ma chwilowo problemy techniczne. Nie można połączyć się z serwerem bazy danych.<br />$1',
'nodb' => 'Nie można odnaleźć bazy danych $1',
'cachederror' => 'Poniższy tekst strony jest kopią znajdującą się w pamięci podręcznej i może być już nieaktualny.',
'laggedslavemode' => 'Uwaga: Ta strona może nie zawierać najnowszych aktualizacji.',
'readonly' => 'Baza danych jest zablokowana',
'enterlockreason' => 'Podaj powód zablokowania bazy oraz szacunkowy czas jej odblokowania',
'readonlytext' => 'Baza danych jest w tej chwili zablokowana
- nie można wprowadzać nowych artykułów ani modyfikować istniejących. Powodem
są prawdopodobnie czynności administracyjne. Po ich zakończeniu przywrócona
zostanie pełna funkcjonalność bazy.
Administrator, który zablokował bazę, podał następujące wyjaśnienie:<br /> $1',
'missingarticle' => 'Oprogramowanie nie odnalazło tekstu strony, która powinna się znajdować w bazie, tzn. strony "$1".

Zazwyczaj zdarza się to, gdy wybrane zostanie łącze do skasowanej strony, np. w starszej wersji innej ze stron.

Inne okoliczności świadczyłyby o tym, że w oprogramowaniu jest błąd. W takim przypadku zgłoś, proszę, ten fakt
administratorowi podając także powyższy adres.',
'readonly_lag' => 'Baza danych została automatycznie zablokowana na czas potrzebny na synchronizację zmian między serwerem głównym i serwerami pośredniczącymi.',
'internalerror' => 'Błąd wewnętrzny',
'filecopyerror' => 'Nie można skopiować pliku "$1" do "$2".',
'filerenameerror' => 'Nie można zmienić nazwy pliku "$1" na "$2".',
'filedeleteerror' => 'Nie można skasować pliku "$1".',
'filenotfound' => 'Nie można znaleźć pliku "$1".',
'unexpected' => 'Niespodziewana wartość: "$1"="$2".',
'formerror' => 'Błąd: nie można wysłać formularza',
'badarticleerror' => 'Dla tej strony ta operacja nie może być wykonana.',
'cannotdelete' => 'Nie można skasować podanej strony lub obrazka.',
'badtitle' => 'Niepoprawny tytuł',
'badtitletext' => 'Podano niepoprawny tytuł strony. Prawdopodobnie zawiera znaki, których użycie jest zabronione lub jest pusty.',
'perfdisabled' => 'Przepraszamy! By odciążyć serwer w godzinach szczytu czasowo zablokowaliśmy wykonanie tej czynności.',
'perfcached' => 'Poniższe dane są kopią z pamięci podręcznej i mogą nie być do końca aktualne.',
'perfcachedts' => 'Poniższe dane są kopią z pamięci podręcznej i zostały uaktualnione $1.',
'wrong_wfQuery_params' => 'Nieprawidłowe parametry przekazane do wfQuery()<br />
Funkcja: $1<br />
Zapytanie: $2',
'viewsource' => 'Tekst źródłowy',
'viewsourcefor' => 'dla $1',
'protectedtext' => 'Wyłączono możliwość edycji tej strony. Istnieje kilka powodów
dla których jest to robione - zobacz [[{{ns:Project}}:Strona_zabezpieczona]].

Tekst źródłowy strony można w dalszym ciągu podejrzeć i skopiować.',
'protectedinterface' => 'Ta strona dostarcza tekst interfejsu do oprogramowania i została zablokowana możliwość jej edycji.',
'editinginterface' => "'''Ostrzeżenie:''' Edytujesz stronę, która jest użyta w celu dostarczenia tekstu interfejsu do oprogramowania. Zmiany na tej stronie zmienią wygląd interfejsu użytkownika dla innych użytkowników.",
'sqlhidden' => '(ukryto zapytanie SQL)',

# Login and logout pages
#
'logouttitle' => 'Wylogowanie użytkownika',
'logouttext' => '<strong>Wylogowano Cię</strong>.<br />Możesz kontynuować pracę jako niezarejestrowany użytkownik albo zalogować się ponownie jako ten sam lub inny użytkownik.',

'welcomecreation' => '== Witaj, $1! ==

Właśnie utworzyliśmy dla Ciebie konto. Nie zapomnij dostosować [[{{ns:Special}}:Preferences|preferencji]].',

'loginpagetitle' => 'Logowanie',
'yourname' => 'Login',
'yourpassword' => 'Hasło',
'yourpasswordagain' => 'Powtórz hasło',
'remembermypassword' => 'Zapamiętaj moje hasło na tym komputerze',
'yourdomainname' => 'Twoja domena',
'externaldberror' => 'Wystąpił błąd zewnętrznej bazy autentyfikacyjnej lub nie posiadasz uprawnień koniecznych do aktualizacji zewnętrznego konta.',
'loginproblem' => '<b>Wystąpił problem przy próbie zalogowania się.</b><br />Spróbuj ponownie!',
'alreadyloggedin' => '<strong>$1, jesteś już zalogowany!</strong><br />',

'login' => 'Zaloguj mnie',
'loginprompt' => 'Musisz mieć włączone cookies by móc się zalogować.',
'userlogin' => 'Logowanie / rejestracja',
'logout' => 'Wyloguj mnie',
'userlogout' => 'Wylogowanie',
'notloggedin' => 'Brak logowania',
'nologin' => 'Nie masz konta? $1.',
'nologinlink' => 'Zarejestruj się',
'createaccount' => 'Załóż nowe konto',
'gotaccount' => 'Masz już konto? $1.',
'gotaccountlink' => 'Zaloguj się',
'createaccountmail' => 'przez e-mail',
'badretype' => 'Wprowadzone hasła różnią się między sobą.',
'userexists' => 'Wybrana przez Ciebie nazwa użytkownika jest już zajęta. Wybierz, proszę, inną.',
'youremail' => 'Twój E-mail *',
'username' => 'Nazwa użytkownika:',
'uid' => 'ID użytkownika:',
'yourrealname' => 'Imię i nazwisko *',
'yourlanguage' => 'Język interfejsu',
'yourvariant' => 'Wariant',
'yournick' => 'Twój podpis',
'badsig' => 'Błędny podpis, sprawdź tagi HTML.',
'prefs-help-email-enotif' => 'Ten adres jest także używany do wysyłania powiadomień, jeśli włączysz tę opcję.',
'prefs-help-realname' => '* Imię i nazwisko (opcjonalnie): jeśli zdecydujesz się je podać, zostaną użyte, aby zapewnić Twojej pracy atrybucję.',
'loginerror' => 'Błąd logowania',
'prefs-help-email' => '* E-mail (opcjonalnie): Podanie e-maila pozwala innym skontaktować się z tobą za pośrednictwem twojej strony użytkownika
lub twojej strony dyskusji bez potrzeby ujawniania twoich danych identyfikacyjnych.',
'nocookiesnew' => 'Konto użytkownika zostało utworzone, ale nie jesteś zalogowany. {{SITENAME}} używa ciasteczek do logowania. Masz wyłączone ciasteczka. Żeby się zalogować odblokuj ciasteczka i podaj nazwę i hasło swojego konta.',
'nocookieslogin' => '{{SITENAME}} używa ciasteczek żeby zalogować użytkownika. Masz zablokowaną obsługę ciasteczek. Spróbuj ponownie po ich odblokowaniu.',
'noname' => 'To nie jest poprawna nazwa użytkownika.',
'loginsuccesstitle' => 'Udane logowanie',
'loginsuccess' => 'Zalogowano Cię do serwisu {{SITENAME}} jako "$1".',
'nosuchuser' => 'Nie ma użytkownika nazywającego się "$1". Sprawdź pisownię lub użyj poniższego formularza by utworzyć nowe konto.',
'nosuchusershort' => 'Nie ma użytkownika nazywającego się "$1".',
'nouserspecified' => 'Musisz podać nazwę użytkownika.',
'wrongpassword' => 'Podane przez Ciebie hasło jest nieprawidłowe. Spróbuj jeszcze raz.',
'wrongpasswordempty' => 'Wprowadzone hasło jest puste. Spróbuj ponownie.',
'mailmypassword' => 'Wyślij mi nowe hasło',
'passwordremindertitle' => 'Przypomnienie hasła w serwisie {{SITENAME}}',
'passwordremindertext' => 'Ktoś (prawdopodobnie Ty, spod adresu $1)
poprosił od nas o wysłanie nowego hasła dostępu do serwisu
{{SITENAME}} ($4).
Aktualne hasło dla użytkownika "$2" to "$3".
Najlepiej będzie jak zalogujesz się teraz i od razu zmienisz hasło.',
'noemail' => 'W bazie nie ma adresu e-mailowego dla użytkownika "$1".',
'passwordsent' => 'Nowe hasło zostało wysłane na adres e-mailowy użytkownika "$1". Po otrzymaniu go zaloguj się ponownie.',
'eauthentsent' => 'Potwierdzenie zostało wysłane na adres e-mail.
Nim jakiekolwiek wiadomości zostaną wysłane na ten adres, należy wypełnić zawarte w nim instrukcje, by potwierdzić Twoją własność e-maila.',
'mailerror' => 'Przy wysyłaniu e-maila nastąpił błąd: $1',
'acct_creation_throttle_hit' => 'Przykro nam, założyłeś/aś już $1 kont(a). Nie możesz założyć kolejnego.',
'emailauthenticated' => 'Twój adres email został uwierzytelniony $1.',
'emailnotauthenticated' => 'Twój adres e-mail nie jest potwierdzony. Poniższe funkcje poczty nie będą działały.',
'noemailprefs' => 'Musisz podać adres e-mail, aby te funkcje działały.',
'emailconfirmlink' => 'Potwierdź swój adres e-mail',
'invalidemailaddress' => 'E-mail nie zostanie zaakceptowany: jego format nie spełnia formalnych wymagań. Proszę wpisać poprawny adres email lub wyczyścić pole.',
'accountcreated' => 'Utworzono konto',
'accountcreatedtext' => 'Konto dla $1 zostało utworzone.',

# Edit page toolbar
'bold_sample' => 'Tekst wytłuszczony',
'bold_tip' => 'Tekst wytłuszczony',
'italic_sample' => 'Tekst pochylony',
'italic_tip' => 'Tekst pochylony',
'link_sample' => 'Tytuł linku',
'link_tip' => 'Link wewnętrzny',
'extlink_sample' => 'http://www.przyklad.pl tytuł strony',
'extlink_tip' => 'Link zewnętrzny (pamiętaj o prefiksie http:// )',
'headline_sample' => 'Tekst nagłówka',
'headline_tip' => 'Nagłówek 2. poziomu',
'math_sample' => 'W tym miejscu wprowadź wzór',
'math_tip' => 'Wzór matematyczny (LaTeX)',
'nowiki_sample' => 'Wstaw tu tekst niesformatowany',
'nowiki_tip' => 'Zignoruj formatowanie wiki',
'image_sample' => 'Przyklad.jpg',
'image_tip' => 'Obrazek osadzony',
'media_sample' => 'Przyklad.ogg',
'media_tip' => 'Link do pliku',
'sig_tip' => 'Twój podpis wraz z datą i czasem',
'hr_tip' => 'Pozioma linia (używaj oszczędnie)',

# Edit pages
#
'summary' => 'Opis zmian',
'subject' => 'Temat/nagłówek',
'minoredit' => 'To jest drobna zmiana',
'watchthis' => 'Obserwuj tę stronę',
'savearticle' => 'Zapisz',
'preview' => 'Podgląd',
'showpreview' => 'Podgląd',
'showlivepreview' => 'Dynamiczny podgląd',
'showdiff' => 'Podgląd zmian',
'anoneditwarning' => 'Nie jesteś zalogowany. Twój adres IP będzie zapisany w historii edycji strony.',
'missingsummary' => "'''Przypomnienie:''' Nie wprowadziłeś opisu zmian. Jeżeli nie chcesz go wprowadzać naciśnij przycisk \"Zapisz\" jeszcze raz.",
'missingcommenttext' => 'Wprowadź komentarz poniżej.',
'blockedtitle' => 'Użytkownik jest zablokowany',
'blockedtext' => "'''Twoje konto lub adres IP zostały zablokowane.'''

Blokada została nałożona przez $1. Podany powód to: ''$2''.

W celu wyjaśnienia sprawy zablokowania możesz się skontaktować z $1 lub innym [[{{ns:Project}}:Administratorzy|administratorem]].

Twój adres IP to $3.",
'whitelistedittitle' => 'Przed edycją musisz się zalogować',
'whitelistedittext' => 'Musisz $1 żeby móc edytować artykuły.',
'whitelistreadtitle' => 'Przed przeczytaniem musisz się zalogować',
'whitelistreadtext' => 'Musisz się [[{{ns:Special}}:Userlogin|zalogować]] żeby czytać strony.',
'whitelistacctitle' => 'Nie jesteś dopuszczony do utworzenia konta',
'whitelistacctext' => 'Aby móc zakładać konta na tej Wiki musisz [[{{ns:Special}}:Userlogin|zalogować się]] i mieć przyznane specjalne prawa.',
'confirmedittitle' => 'Wymagane potwierdzenie e-maila by móc edytować',
'confirmedittext' => 'Musisz podać i potwierdzić swój e-mail by móc edytować. Możesz to zrobić w [[{{ns:Special}}:Preferences|swoich ustawieniach]].',
'loginreqtitle' => 'Musisz się zalogować',
'loginreqlink' => 'zaloguj się',
'loginreqpagetext' => 'Musisz $1 żeby móc przeglądać inne strony.',
'accmailtitle' => 'Hasło wysłane.',
'accmailtext' => 'Hasło dla użytkownika "$1" zostało wysłane pod adres $2.',
'newarticle' => '(Nowy)',
'newarticletext' => "Nie ma jeszcze artykułu o tym tytule. W poniższym polu można wpisać pierwszy jego fragment. Jeśli nie to było Twoim zamiarem, wciśnij po prostu ''Wstecz''.",
'anontalkpagetext' => "---- ''To jest strona dyskusyjna dla użytkowników anonimowych - takich, którzy nie mają jeszcze swojego konta lub nie chcą go w tej chwili używać. By ich identyfikować używamy numerów IP. Jeśli jesteś anonimowym użytkownikiem i wydaje Ci się, że zamieszczone tu komentarze nie są skierowane do Ciebie, [[{{ns:Special}}:Userlogin|utwórz proszę konto albo zaloguj się]] - dzięki temu unikniesz w przyszłości podobnych nieporozumień.''",
'noarticletext' => 'Nie ma jeszcze artykułu o tym tytule. Możesz [{{fullurl:{{NAMESPACE}}:{{PAGENAME}}|action=edit}} utworzyć artykuł {{FULLPAGENAME}}] lub [[{{ns:Special}}:Search/{{FULLPAGENAME}}|poszukać {{FULLPAGENAME}} w innych artykułach]].',
'clearyourcache' => "'''Uwaga:''' po zapisaniu zmian musisz zaktualizować pamięć podręczną (cache) przeglądarki: '''Mozilla / Firefox:''' kliknij ''Reload'' (lub ''Ctrl-R''), '''IE / Opera:''' ''Ctrl-F5'', '''Safari:''' ''Cmd-R'', '''Konqueror''' ''Ctrl-R''.",
'usercssjsyoucanpreview' => '<strong>Wskazówka:</strong> Użyj przycisku "Podgląd", aby przetestować Twój nowy arkusz stylów CSS lub kod JavaScript przed jego zapisaniem.',
'usercsspreview' => "'''Pamiętaj, że to na razie tylko podgląd Twojego arkusza stylów - nic jeszcze nie zostało zapisane!'''",
'userjspreview' => "'''Pamiętaj, że to na razie tylko podgląd Twojego JavaScriptu - nic jeszcze nie zostało zapisane!'''",
'userinvalidcssjstitle' => "'''Uwaga:''' Nie ma skórki o nazwie \"$1\". Pamiętaj, że strony użytkownika zawierające CSS i JavaScript powinny zaczynać się małą literą, np. User:Foo/monobook.css.",
'updated' => '(Zmodyfikowano)',
'note' => '<strong>Uwaga:</strong>',
'previewnote' => '<strong>To jest tylko podgląd - artykuł nie został jeszcze zapisany!</strong>',
'session_fail_preview' => '<strong>Przepraszamy! Serwer nie może przetworzyć tej edycji z powodu utraty danych sesji. Spróbuj jeszcze raz. Jeśli to nie pomoże - wyloguj się i zaloguj ponownie.</strong>',
'previewconflict' => 'Wersja podglądana odnosi się do tekstu z górnego pola edycji. Tak będzie wyglądać strona jeśli zdecydujesz się ją zapisać.',
'importing' => 'Importowanie $1',
'editing' => 'Edytujesz "$1"',
'editinguser' => 'Edytujesz "$1"',
'editingsection' => 'Edytujesz "$1" (fragment)',
'editingcomment' => 'Edytujesz "$1" (komentarz)',
'editconflict' => 'Konflikt edycji: $1',
'explainconflict' => 'Ktoś zdążył wprowadzić swoją wersję artykułu w trakcie Twojej edycji.
Górne pole edycji zawiera tekst strony aktualnie zapisany w bazie danych.
Twoje zmiany znajdują się w dolnym polu edycji.
By wprowadzić swoje zmiany musisz zmodyfikować tekst z górnego pola.
<b>Tylko</b> tekst z górnego pola będzie zapisany w bazie gdy wciśniesz "Zapisz".<br />',
'yourtext' => 'Twój tekst',
'storedversion' => 'Zapisana wersja',
'nonunicodebrowser' => "<strong>Uwaga! Twoja przeglądarka nie umie poprawnie rozpoznawać kodowania UTF-8 (Unicode). Z tego powodu wszystkie znaki, których Twoja przeglądarka nie jest w stanie rozpoznać, zostały zastąpione ich kodami heksadecymalnymi.</strong>",
'editingold' => "<strong>Ostrzeżenie: Edytujesz inną niż bieżąca wersję tej strony. Jeśli zapiszesz ją wszystkie późniejsze zmiany zostaną skasowane.</strong>",
'yourdiff' => 'Różnice',
'copyrightwarning' => "Proszę pamiętać o tym, że wszelki wkład do serwisu {{SITENAME}} jest udostępniany na zasadach $2 (szczegóły w $1). Jeśli nie chcesz, żeby Twój tekst był dowolnie zmieniany przez każdego i rozpowszechniany bez ograniczeń, nie umieszczaj go tutaj.<br />
Niniejszym jednocześnie oświadczasz, że ten tekst jest Twoim dziełem lub pochodzi z materiałów dostępnych na zasadach ''public domain'' albo kompatybilnych.
<br /><strong>PROSZĘ NIE UŻYWAĆ BEZ POZWOLENIA MATERIAŁÓW OBJĘTYCH PRAWEM AUTORSKIM!</strong>",
'longpagewarning' => '<strong>Uwaga: Ta strona ma $1 kilobajt-y/-ów; w przypadku niektórych przeglądarek mogą wystąpić problemy w edycji stron mających więcej niż 32 kilobajty. Jeśli to możliwe, spróbuj podzielić tekst na mniejsze części.</strong>',
'longpageerror' => '<strong>Błąd: Przesłany przez Ciebie tekst ma $1 kilobajtów. Maksymalna długość tekstu nie może przekraczać $2 kilobajtów. Twój tekst nie zostanie zapisany.</strong>',
'readonlywarning' => '<strong>Uwaga: Baza danych została chwilowo zablokowana do celów administracyjnych. Nie można więc na razie zapisać nowej wersji artykułu. Proponujemy przenieść jej tekst do prywatnego pliku (wytnij/wklej) i zachować na później.</strong>',
'protectedpagewarning' => '<strong>Uwaga: Modyfikacja tej strony została zablokowana. Mogą ją edytować jedynie użytkownicy z prawami administracyjnymi. Upewnij się, że postępujesz zgodnie z [[{{ns:Project}}:Blokowanie_stron|zasadami dotyczącymi zablokowanych stron]].</strong>',
'semiprotectedpagewarning' => '<strong>Uwaga:</strong> Tę stronę mogą edytować tylko zarejestrowani użytkownicy.',
'templatesused' => 'Szablony użyte na tej stronie:',
'edittools' => '<!-- Znajdujący się tutaj tekst zostanie pokazany pod polem edycji i formularzem przesyłania plików. -->',
'nocreatetitle' => 'Ograniczono tworzenie stron',
'nocreatetext' => 'Ograniczono możliwość tworzenia nowych stron. Możesz edytować istniejące strony lub [[{{ns:Special}}:Userlogin|zalogować się albo utworzyć nowe konto]].',
'cantcreateaccounttitle' => 'Nie można utworzyć konta',
'cantcreateaccounttext' => 'Możliwość utworzenia konta z tego adresu IP (<b>$1</b>) została zablokowana. Stało się to prawdopodobnie wskutek ciągłych aktów wandalizmu z Twojej szkoły/uczelni lub wandalizmów innych użytkowników Twojego providera internetowego.',

# History pages
#
'revhistory' => 'Historia modyfikacji',
'viewpagelogs' => 'Zobacz rejestry operacji dla tej strony',
'nohistory' => 'Ta strona nie ma swojej historii edycji.',
'revnotfound' => 'Wersja nie została odnaleziona',
'revnotfoundtext' => 'Starsza wersja strony nie może zostać odnaleziona. Sprawdź, proszę, URL użyty przez Ciebie by uzyskać dostęp do tej strony.',
'loadhist' => 'Pobieranie historii tej strony',
'currentrev' => 'Aktualna wersja',
'revisionasof' => 'Wersja z dnia $1',
'revision-info' => 'Wersja z dnia $1; $2',
'previousrevision' => '← Poprzednia wersja',
'nextrevision' => 'Następna wersja →',
'currentrevisionlink' => 'Aktualna wersja',
'cur' => 'bież',
'next' => 'następna',
'last' => 'poprz',
'orig' => 'oryginał',
'histlegend' => 'Legenda: (bież) - różnice z wersją bieżącą, (poprz) - różnice z wersją poprzedzającą, d - drobne zmiany',
'deletedrev' => '[usunięto]',
'histfirst' => 'od początku',
'histlast' => 'od końca',
'rev-deleted-comment' => '(komentarz usunięty)',
'rev-deleted-user' => '(użytkownik usunięty)',
'rev-delundel' => 'pokaż/ukryj',

'history-feed-title' => 'Historia wersji',
'history-feed-description' => 'Historia wersji tej strony wiki',
'history-feed-item-nocomment' => '$1 o $2',
'history-feed-empty' => 'Wybrana strona nie istnieje. Mogła ona zostać usunięta lub przeniesiona pod inną nazwę. Możesz także [[{{ns:special}}:Search|poszukać]] tej strony.',

# Revision deletion
#
'revisiondelete' => 'Skasuj/przywróć wersje',
'revdelete-selected' => 'Wybrano wersje strony [[:$1]]:',

# Diffs
#
'difference' => '(Różnice między wersjami)',
'loadingrev' => 'pobieranie wersji w celu porównania',
'lineno' => "Linia $1:",
'editcurrent' => 'Edytuj bieżącą wersję tej strony',
'selectnewerversionfordiff' => 'Wybierz nowszą wersję do porównania',
'selectolderversionfordiff' => 'Wybierz starszą wersję do porównania',
'compareselectedversions' => 'porównaj wybrane wersje',

# Search results
#
'searchresults' => 'Wyniki wyszukiwania',
'searchresulttext' => 'Aby dowiedzieć się więcej o przeszukiwaniu serwisu {{SITENAME}}, zobacz stronę [[{{ns:Project}}:Przeszukiwanie|Przeszukiwanie]].',
'searchsubtitle' => 'Dla zapytania "[[:$1]]"',
'searchsubtitleinvalid' => 'Dla zapytania "$1"',
'badquery' => 'Źle sformułowane zapytanie',
'badquerytext' => 'Nie można zrealizować Twojego zapytania. Prawdopodobna przyczyna to obecność słowa krótszego niż trzyliterowe. Spróbuj, proszę, innego zapytania.',
'matchtotals' => 'Zapytanie "$1", liczba znalezionych tytułów: $2,
liczba znalezionych artykułów: $3.',
'noexactmatch' => 'Nie ma stron zatytułowanych "$1". Możesz [[:$1|utworzyć tę stronę]] lub spróbować pełnego przeszukiwania.',
'titlematches' => 'Znaleziono w tytułach:',
'notitlematches' => 'Nie znaleziono w tytułach',
'textmatches' => 'Znaleziono na stronach:',
'notextmatches' => 'Nie znaleziono w tekście stron',
'prevn' => 'poprzednie $1',
'nextn' => 'następne $1',
'viewprevnext' => 'Zobacz ($1) ($2) ($3).',
'showingresults' => 'Oto lista <b>$1</b> pozycji, poczynając od numeru <b>$2</b>.',
'showingresultsnum' => 'Oto lista <b>$3</b> pozycji, poczynając od numeru <b>$2</b>.',
'nonefound' => "'''Uwaga''': brak rezultatów wyszukiwania spowodowany jest bardzo często szukaniem najpopularniejszych słów, takich jak \"jest\" czy \"nie\", które nie są indeksowane, albo z powodu podania w zapytaniu więcej niż jednego słowa (na liście odnalezionych stron znajdą się tylko te, które zawierają wszystkie podane słowa).",
'powersearch' => 'Szukaj',
'powersearchtext' => "Szukaj w przestrzeniach nazw:<br />$1<br />$2 Pokaż przekierowania<br />Szukany tekst $3 $9",
'searchdisabled' => 'Wyszukiwanie w serwisie {{SITENAME}} zostało wyłączone. W międzyczasie możesz skorzystać z wyszukiwania Google.',
'blanknamespace' => '(główna)',

# Preferences page
#
'preferences' => 'Preferencje',
'mypreferences' => 'Moje preferencje',
'prefsnologin' => 'Nie jesteś zalogowany',
'prefsnologintext' => 'Musisz się [[{{ns:Special}}:Userlogin|zalogować]] przed zmianą swoich preferencji.',
'prefsreset' => 'Preferencje domyślne zostały odtworzone.',
'qbsettings' => 'Pasek szybkiego dostępu',
'changepassword' => 'Zmiana hasła',
'skin' => 'Skórka',
'math' => 'Wzory matematyczne',
'dateformat' => 'Format daty',
'datedefault' => 'Domyślny',
'datetime' => 'Data i czas',
'math_failure' => 'Parser nie mógł rozpoznać',
'math_unknown_error' => 'nieznany błąd',
'math_unknown_function' => 'nieznana funkcja',
'math_lexing_error' => 'błąd leksera',
'math_syntax_error' => 'błąd składni',
'math_image_error' => 'konwersja do formatu PNG niepowiodła się ; check for correct installation of latex, dvips, gs, and convert',
'math_bad_tmpdir' => 'Nie można utworzyć lub zapisywać w tymczasowym katalogu dla wzorów matematycznych',
'math_bad_output' => 'Nie można utworzyć lub zapisywać w wyjściowym katalogu dla wzorów matematycznych',
'prefs-personal' => 'Dane użytkownika',
'prefs-rc' => 'Ostatnie zmiany',
'prefs-watchlist' => 'Obserwowane',
'prefs-watchlist-days' => 'Liczba dni ukazywania się pozycji na liście:',
'prefs-watchlist-edits' => 'Liczba edycji pokazywanych w rozszerzonej liście obserwowanych:',
'prefs-misc' => 'Różne',
'saveprefs' => 'Zapisz preferencje',
'resetprefs' => 'Preferencje domyślne',
'oldpassword' => 'Stare hasło',
'newpassword' => 'Nowe hasło',
'retypenew' => 'Powtórz nowe hasło',
'textboxsize' => 'Edytowanie',
'rows' => 'Wiersze:',
'columns' => 'Kolumny:',
'searchresultshead' => 'Wyszukiwarka',
'resultsperpage' => 'Liczba wyników na stronie',
'contextlines' => 'Pierwsze wiersze artykułu',
'contextchars' => 'Litery kontekstu w linijce',
'stubthreshold' => 'Maksymalny rozmiar artykułu prowizorycznego:',
'recentchangescount' => 'Liczba pozycji na liście ostatnich zmian:',
'savedprefs' => 'Twoje preferencje zostały zapisane.',
'timezonelegend' => 'Strefa czasowa',
'timezonetext' => 'Podaj liczbę godzin różnicy między Twoim czasem, a czasem uniwersalnym (UTC). Np. dla Polski jest to liczba "2" (czas letni) lub "1" (czas zimowy).',
'localtime' => 'Twój czas:',
'timezoneoffset' => 'Różnica ¹',
'servertime' => 'Aktualny czas serwera',
'guesstimezone' => 'Pobierz z przeglądarki',
'allowemail' => 'Inni użytkownicy mogą przesyłać mi e-maile',
'defaultns' => 'Przeszukuj następujące przestrzenie nazw domyślnie:',
'default' => 'domyślnie',
'files' => 'Pliki',

# User rights
'userrights-lookup-user' => 'Zarządzaj grupami użytkownika',
'userrights-user-editname' => 'Wprowadź nazwę użytkownika:',
'editusergroup' => 'Edytuj grupy użytkownika',

'userrights-editusergroup' => 'Edytuj grupy użytkownika',
'saveusergroups' => 'Zapisz',
'userrights-groupsmember' => 'Członek grup:',
'userrights-groupsavailable' => 'Dostępne grupy:',
'userrights-groupshelp' => 'Zaznacz grupy do których użytkownik ma zostać dodany lub z których ma zostać usunięty. Niezaznaczone grupy nie zostaną zmienione. Możesz odznaczyć grupę za pomocą CTRL + lewy przycisk myszy.',

# Groups
'group' => 'Grupa:',
'group-bot' => 'Boty',
'group-sysop' => 'Administratorzy',
'group-bureaucrat' => 'Biurokraci',
'group-all' => '(wszyscy)',

'group-bot-member' => 'Bot',
'group-sysop-member' => 'Administrator',
'group-bureaucrat-member' => 'Biurokrata',

'grouppage-bot' => '{{ns:Project}}:Boty',
'grouppage-sysop' => '{{ns:Project}}:Administratorzy',
'grouppage-bureaucrat' => '{{ns:Project}}:Biurokraci',

# Recent changes
#
'changes' => 'zmiany',
'recentchanges' => 'Ostatnie zmiany',
'recentchangestext' => 'Ta strona przedstawia historię ostatnich zmian w serwisie.',
'rcnote' => 'To ostatnie <strong>$1</strong> zmian dokonanych w ciągu ostatnich <strong>$2</strong> dni, poczynając od $3.',
'rcnotefrom' => 'Poniżej pokazano zmiany dokonane po <b>$2</b> (nie więcej niż <b>$1</b> pozycji).',
'rclistfrom' => 'Pokaż zmiany od $1',
'rcshowhideminor' => '$1 drobne zmiany',
'rcshowhidebots' => '$1 boty',
'rcshowhideliu' => '$1 zalogowanych',
'rcshowhideanons' => '$1 anonimowych',
'rcshowhidepatr' => '$1 patrolowane',
'rcshowhidemine' => '$1 moje edycje',
'rclinks' => 'Wyświetl ostatnie $1 zmian w ciągu ostatnich $2 dni.<br />$3',
'diff' => 'różn',
'hist' => 'hist',
'hide' => 'ukryj',
'show' => 'pokaż',
'minoreditletter' => 'd',
'newpageletter' => 'N',
'sectionlink' => '→',
'rc_categories' => 'Ogranicz do kategorii (oddzielaj za pomocą "|")',
'rc_categories_any' => 'Wszystkie',

# Upload
#
'upload' => 'Prześlij plik',
'uploadbtn' => 'Prześlij plik',
'reupload' => 'Prześlij ponownie',
'reuploaddesc' => 'Wróć do formularza wysyłki.',
'uploadnologin' => 'Brak logowania',
'uploadnologintext' => 'Musisz się [[{{ns:Special}}:Userlogin|zalogować]] przed przesłaniem pików.',
'upload_directory_read_only' => 'Serwer nie może zapisywać do katalogu ($1) przeznaczonego na przesyłane pliki.',
'uploaderror' => 'Błąd przesyłki',
'uploadtext' => 'Użyj poniższego formularza do przesłania plików. Jeśli chcesz przejrzeć lub przeszukać dotychczas przesłane pliki, przejdź do [[{{ns:Special}}:Imagelist|listy dołączonych plików]]. Wszystkie przesyłki są odnotowane w [[{{ns:Special}}:Log/upload|rejestrze przesyłanych plików]].',
'uploadlog' => 'Wykaz przesyłek',
'uploadlogpage' => 'Dołączone',
'uploadlogpagetext' => 'Oto lista ostatnio przesłanych plików.',
'filename' => 'Plik',
'filedesc' => 'Opis',
'fileuploadsummary' => 'Opis:',
'filestatus' => 'Status prawny',
'filesource' => 'Kod źródłowy',
'copyrightpage' => "{{ns:Project}}:Prawa_autorskie",
'copyrightpagename' => "prawami autorskimi serwisu {{SITENAME}}",
'uploadedfiles' => 'Przesłane pliki',
'ignorewarning' => 'Zignoruj ostrzeżenia i wymuś zapisanie pliku.',
'ignorewarnings' => 'Ignoruj ostrzeżenia',
'minlength' => 'Nazwa obrazku musi mieć co najmniej trzy litery.',
'illegalfilename' => 'Nazwa pliku ("$1") zawiera znaki niedozwolone w tytułach stron. Proszę zmienić nazwę pliku i przesłać go ponownie.',
'badfilename' => 'Nazwę obrazku zmieniona na "$1".',
'badfiletype' => '".$1" nie jest zalecanym formatem pliku.',
'largefile' => 'Zalecane jest aby rozmiar pliku z obrazkiem nie był większy niż $1 bajtów. Ten plik ma rozmiar $2 bajtów.',
'largefileserver' => 'Plik jest większy niż maksymalny dozwolony rozmiar.',
'emptyfile' => 'Plik, który przesłałeś wydaje się być pusty. Może być to spowodowane literówką w nazwie pliku. Sprawdź, czy nazwa jest prawidłowa.',
'fileexists' => 'Plik o takiej nazwie już istnieje! Załadowanie nowej grafiki nieodwacalnie usunie już istniejącą ($1)! Upewnij się, że wiesz, co robisz.',
'fileexists-forbidden' => 'Plik o tej nazwie już istnieje! Wróć i załaduj ten plik pod inną nazwą. [[Image:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => 'Plik o tej nazwie już istnieje! Wróć i załaduj ten plik pod inną nazwą. [[Image:$1|thumb|center|$1]]',
'successfulupload' => 'Przesłanie pliku powiodło się',
'fileuploaded' => 'Plik "$1" został pomyślnie przesłany. Przejdź, proszę, do strony opisu pliku ($2) i podaj dotyczące go informacje takie jak: pochodzenie pliku, kiedy i przez kogo został utworzony i cokolwiek co wiesz o pliku, a wydaje Ci się ważne.',
'uploadwarning' => 'Ostrzeżenie o przesyłce',
'savefile' => 'Zapisz plik',
'uploadedimage' => 'przesłano "[[$1]]"',
'uploaddisabled' => 'Przesyłanie plików wyłączone',
'uploaddisabledtext' => 'Możliwość przesyłania plików została wyłączona.',
'uploadscripted' => 'Ten plik zawiera kod HTML lub skrypt który może zostać błędnie zinterpretowany przez przeglądarkę internetową.',
'uploadcorrupt' => 'Ten plik jest uszkodzony lub ma nieprawidłowe rozszerzenie. Proszę sprawdzić plik i załadować poprawną wersję.',
'uploadvirus' => 'W tym pliku jest wirus! Szczegóły: $1',
'sourcefilename' => 'Nazwa oryginalna',
'destfilename' => 'Nazwa docelowa',
'watchthisupload' => 'Obserwuj tę stronę',
'filewasdeleted' => 'Plik o tej nazwie istniał, ale został skasowany. Zanim załadujesz go ponownie, sprawdź $1.',

'license' => 'Licencja',
'nolicense' => 'Nie wybrano (wpisz ręcznie!)',
'upload_source_url' => ' (poprawny, publicznie dostępny URL)',
'upload_source_file' => ' (plik na twoim komputerze)',

# Image list
#
'imagelist' => 'Lista plików',
'imagelisttext' => "To jest lista '''$1''' plików posortowanych $2.",
'imagelistforuser' => 'Lista grafik załadowanych przez $1.',
'getimagelist' => 'pobieranie listy plików',
'ilsubmit' => 'Szukaj',
'showlast' => 'Pokaż ostatnie $1 plików posortowanych $2.',
'byname' => 'według nazwy',
'bydate' => 'według daty',
'bysize' => 'według rozmiaru',
'imgdelete' => 'usuń',
'imgdesc' => 'opis',
'imgfile' => 'plik',
'imglegend' => 'Legenda: (opis) - pokaż/edytuj opis pliku.',
'imghistory' => 'Historia pliku',
'revertimg' => 'przywróć',
'deleteimg' => 'usuń',
'deleteimgcompletely' => 'Usuń wszystkie wersje tego pliku',
'imghistlegend' => 'Legend: (bież) - to jest bieżący plik, (usuń) - usuń starszą wersję, (przywróć) - przywróc starszą wersję.<br /><i>Kliknij na datę aby zobaczyć przesłany plik</i>.',
'imagelinks' => 'Odnośniki do pliku',
'linkstoimage' => 'Oto strony odwołujące się do tego pliku:',
'nolinkstoimage' => 'Żadna strona nie odwołuje się do tego pliku.',
'sharedupload' => 'Plik [[Commons:Image:{{PAGENAME}}|{{PAGENAME}}]] umieszczony jest we wspólnym repozytorium i może być używany w innych projektach.',
'shareduploadwiki' => 'Zobacz $1 aby dowiedzieć się więcej.',
'shareduploadwiki-linktext' => 'stronę opisu grafiki',
'noimage' => 'Nie istnieje plik o tej nazwie. Możesz go $1.',
'noimage-linktext' => 'przesłać',
'uploadnewversion-linktext' => 'Załaduj nowszą wersję tego pliku',
'imagelist_date' => 'Data',
'imagelist_name' => 'Nazwa',
'imagelist_user' => 'Użytkownik',
'imagelist_size' => 'Rozmiar (bajty)',
'imagelist_description' => 'Opis',
'imagelist_search_for' => 'Szukaj grafiki o nazwie:',

# Mime search
#
'mimesearch' => 'Wyszukiwanie MIME',
'mimetype' => 'Typ MIME:',
'download' => 'pobierz',

# Unwatchedpages
#
'unwatchedpages' => 'Nieobserwowane strony',

# List redirects
'listredirects' => 'Lista przekierowań',

# Unused templates
'unusedtemplates' => 'Nieużywane szablony',
'unusedtemplatestext' => 'Poniżej znajduje się lista szablonów nieużywanych na innych stronach.',
'unusedtemplateswlh' => 'linkujące',

# Random redirect
'randomredirect' => 'Losowe przekierowanie',

# Statistics
#
'statistics' => 'Statystyka',
'sitestats' => 'Statystyka artykułów',
'userstats' => 'Statystyka użytkowników',
'sitestatstext' => "W bazie danych jest w sumie '''$1''' stron.

Ta liczba uwzględnia strony dyskusji, strony na temat serwisu {{SITENAME}}, strony prowizorycznych (\"stub\"), strony przekierowujące oraz inne, które trudno uznać za artykuły. Wyłączając powyższe, jest prawdopodobnie '''$2''' stron, które można uznać za artykuły.

Ilość przesłanych plików: '''$8'''.

Użytkownicy od startu serwisu wykonali '''$4''' edycji, średnio '''$5''' edycji na stronę. W sumie było '''$3''' odwiedzin, średnio '''$6''' odwiedzin na edycję.

Długość [http://meta.wikimedia.org/wiki/Help:Job_queue kolejki zadań] wynosi '''$7'''.",
'userstatstext' => "Jest '''$1''' zarejestrowanych użytkowników. Spośród nich '''$2''' (czyli '''$4%''') ma status $5.",
'statistics-mostpopular' => 'Najczęściej odwiedzane strony',

'disambiguations' => 'Strony ujednoznaczniające',
'disambiguationspage' => '{{ns:Template}}:disambig',
'disambiguationstext' => 'Poniższe artykuły odwołują się do <i>stron ujednoznaczniających</i>, a powinny odwoływać się bezpośrednio do hasła związanego z treścią artykułu.<br />Strona uznawana jest za ujednoznaczniającą jeśli odwołuje się do niej $1.<br />Linki z innych przestrzeni nazw <i>nie</i> zostały tu uwzględnione.',

'doubleredirects' => 'Podwójne przekierowania',
'doubleredirectstext' => 'Na tej liście mogą znajdować się przekierowania pozorne. Oznacza to, że poniżej pierwszej linii artykułu, zawierającej "#REDIRECT ...", może znajdować się dodatkowy tekst.<br />Każdy wiersz listy zawiera odwołania do pierwszego i drugiego przekierowania oraz pierwszą linię tekstu drugiego przekierowania. Umożliwia to w większości przypadków odnalezienie właściwego artykułu, do którego powinno się przekierowywać.',

'brokenredirects' => 'Zerwane przekierowania',
'brokenredirectstext' => 'Poniższe przekierowania wskazują na nieistniejące artykuły.',


# Miscellaneous special pages
#
'nbytes' => '$1 {{PLURAL:$1|bajt|bajtów}}',
'ncategories' => '$1 {{PLURAL:$1|kategoria|kategorii}}',
'nlinks' => '$1 {{PLURAL:$1|link|linków}}',
'nmembers' => '$1 {{PLURAL:$1|element|elementów}}',
'nrevisions' => '$1 {{PLURAL:$1|wersja|wersji}}',
'nviews' => 'odwiedzono $1 {{PLURAL:$1|raz|razy}}',

'lonelypages' => 'Porzucone strony',
'uncategorizedpages' => 'Nieskategoryzowane strony',
'uncategorizedcategories' => 'Nieskategoryzowane kategorie',
'uncategorizedimages' => 'Nieskategoryzowane grafiki',
'unusedcategories' => 'Nieużywane kategorie',
'unusedimages' => 'Nie używane pliki',
'popularpages' => 'Najpopularniejsze strony',
'wantedcategories' => 'Potrzebne kategorie',
'wantedpages' => 'Najpotrzebniejsze strony',
'mostlinked' => 'Najczęściej linkowane',
'mostlinkedcategories' => 'Kategorie o największej liczbie artykułów',
'mostcategories' => 'Artykuły z największą liczbą kategorii',
'mostimages' => 'Najczęściej linkowane pliki',
'mostrevisions' => 'Najczęściej edytowane artykuły',
'allpages' => 'Wszystkie strony',
'prefixindex' => 'Wszystkie strony według prefiksu',
'randompage' => 'Losuj stronę',
'shortpages' => 'Najkrótsze strony',
'longpages' => 'Najdłuższe strony',
'deadendpages' => 'Strony bez linków',
'deadendpagestext' => 'Poniższe strony nie posiadają odnośników do innych stron znajdujących się w tej wiki.',
'listusers' => 'Lista użytkowników',
'specialpages' => 'Strony specjalne',
'spheading' => 'Strony specjalne dla wszystkich użytkowników',
'restrictedpheading' => 'Strony specjalne z ograniczonym dostępem',
'recentchangeslinked' => 'Zmiany w dolinkowanych',
'rclsub' => '(dla stron dolinkowanych do "$1")',
'newpages' => 'Nowe strony',
'newpages-username' => 'Nazwa użytkownika:',
'ancientpages' => 'Najstarsze strony',
'intl' => 'Linki interwiki',
'move' => 'Przenieś',
'movethispage' => 'Przenieś tę stronę',
'unusedimagestext' => 'Pamiętaj, proszę, że inne witryny, np. projekty Wikimedia w innych językach, mogą odwoływać się do tych plików używając bezpośrednio URL. Dlatego też niektóre z plików mogą się znajdować na tej liście mimo, że żadna strona nie odwołuje się do nich.',
'unusedcategoriestext' => 'Poniższe kategorie istnieją, choć nie korzysta z nich żaden artykuł ani kategoria.',

'booksources' => 'Książki',
'categoriespagetext' => 'Poniższe kategorie istnieją na wiki.',
'userrights' => 'Zarządzanie prawami użytkowników',
'groups' => 'Grupy użytkowników',

'booksourcetext' => 'Oto lista linków do innych witryn, które pośredniczą w sprzedaży nowych i używanych książek i mogą podać informacje o książkach, których szukasz. {{SITENAME}} nie jest stowarzyszona z żadnym ze sprzedawców, a ta lista nie powinna być interpretowana jako świadectwo udziału w zyskach.',
'alphaindexline' => "$1 --> $2",
'version' => 'Wersja oprogramowania',
'log' => 'Rejestry operacji',
'alllogstext' => 'Połączone rejestry przesłanych plików, skasowanych stron, zabezpieczania, blokowania i nadawania uprawnień. Możesz zawęzić wynik przez wybranie typu rejestru, nazwy użytkownika albo nazwy interesującej Cię strony.',
'logempty' => 'Brak pozycji w rejestrze.',


# Special:Allpages
'nextpage' => 'Następna strona ($1)',
'allpagesfrom' => 'Strony zaczynające się na:',
'allarticles' => 'Wszystkie artykuły',
'allinnamespace' => 'Wszystkie strony (w przestrzeni $1)',
'allnotinnamespace' => 'Wszystkie strony (oprócz przestrzeni nazw $1)',
'allpagesprev' => 'Poprzednia',
'allpagesnext' => 'Następna',
'allpagessubmit' => 'Pokaż',
'allpagesprefix' => 'Pokaż zaczynające się od:',
'allpagesbadtitle' => 'Podana nazwa jest nieprawidłowa, zawiera prefiks międzyprojektowy lub międzyjęzykowy. Może ona także zawierać w sobie jeden lub więcej znaków których użycie w nazwach jest niedozwolone.',

# Special:Listusers
'listusersfrom' => 'Wyświetl użytkowników zaczynając od:',

# E this user
#
'mailnologin' => 'Brak adresu',
'mailnologintext' => 'Musisz się [[{{ns:Special}}:Userlogin|zalogować]] i mieć wpisany aktualny adres e-mailowy w swoich [[{{ns:Special}}:Preferences|preferencjach]], aby móc wysłać e-mail do innych użytkowników.',
'emailuser' => 'Wyślij e-mail do tego użytkownika',
'emailpage' => 'Wyślij e-mail do użytkownika',
'emailpagetext' => 'Jeśli ten użytkownik wpisał poprawny adres e-mailowy w swoich preferencjach, to poniższy formularz umożliwi Ci wysłanie jednej wiadomości. Adres e-mailowy, który został przez Ciebie wprowadzony w Twoich preferencjach pojawi się w polu "Od", dzięki temu odbiorca będzie mógł Ci odpowiedzieć.',
'usermailererror' => 'Obiekt Mail zwrócił błąd:',
'defemailsubject' => '{{SITENAME}} e-mail',
'noemailtitle' => 'Brak adresu e-mailowego',
'noemailtext' => 'Ten użytkownik nie podał poprawnego adresu e-mailowego, albo zadecydował, że nie chce otrzymywać e-maili od innych użytkowników.',
'emailfrom' => 'Od',
'emailto' => 'Do',
'emailsubject' => 'Temat',
'emailmessage' => 'Wiadomość',
'emailsend' => 'Wyślij',
'emailsent' => 'Wiadomość została wysłana',
'emailsenttext' => 'Twoja wiadomość została wysłana.',

# Watchlist
#
'watchlist' => 'Obserwowane',
'watchlistfor' => "(dla użytkownika '''$1''')",
'nowatchlist' => 'Nie ma żadnych pozycji na liście obserwowanych przez Ciebie stron.',
'watchlistanontext' => '$1 aby obejrzeć lub edytować elementy listy obserwowanych.',
'watchlistcount' => "'''Masz $1 {{PLURAL:$1|$1 pozycję|$1 pozycji}} na liście obserwowanych stron, włączając strony dyskusji.'''",
'clearwatchlist' => 'Wyczyść listę obserwowanych',
'watchlistcleartext' => 'Czy jesteś pewien, że chcesz je usunąć?',
'watchlistclearbutton' => 'Wyczyść obserwowane',
'watchlistcleardone' => 'Twoja lista stron obserwowanych została wyczyszczona. Liczba usuniętych obiektów: $1.',
'watchnologin' => 'Brak logowania',
'watchnologintext' => 'Musisz się [[{{ns:Special}}:Userlogin|zalogować]] przed modyfikacją listy obserwowanych artykułów.',
'addedwatch' => 'Dodana do listy obserwowanych',
'addedwatchtext' => "Strona \"[[:$1|$1]]\" została dodana do Twojej [[{{ns:Special}}:Watchlist|listy obserwowanych]]. Na tej liście znajdzie się rejestr przyszłych zmian tej strony i związanej z nią strony dyskusji, a nazwa samej strony zostanie '''wytłuszczona''' na [[{{ns:Special}}:Recentchanges|liście ostatnich zmian]], aby łatwiej było Ci sam fakt zmiany zauważyć.

Jeśli chcesz usunąć stronę ze swojej listy obserwowanych, kliknij na \"Przestań obserwować\".",
'removedwatch' => 'Usunięto z listy obserwowanych',
'removedwatchtext' => 'Strona "[[:$1]]" została usunięta z Twojej listy obserwowanych.',
'watch' => 'Obserwuj',
'watchthispage' => 'Obserwuj tę stronę',
'unwatch' => 'Przestań obserwować',
'unwatchthispage' => 'Przestań obserwować',
'notanarticle' => 'To nie artykuł',
'watchnochange' => 'Żadna z obserwowanych stron nie była edytowana w podanym okresie.',
'watchdetails' => '* Liczba obserwowanych przez Ciebie stron: $1, nie licząc stron dyskusji.
* [[{{ns:Special}}:Watchlist/edit|Pokaż i edytuj pełną listę]]
* [[{{ns:Special}}:Watchlist/clear|Wyczyść listę obserwowanych]]',
'wlheader-enotif' => '* Wysyłanie powiadomień na adres e-mail jest włączone.',
'wlheader-showupdated' => "* Strony które zostały zmienione od twojej ostatniej wizyty na nich zostały '''pogrubione'''",
'watchmethod-recent'=> 'poszukiwanie ostatnich zmian wśród obserwowanych stron',
'watchmethod-list' => 'poszukiwanie obserwowanych stron wśród ostatnich zmian',
'removechecked' => 'Usuń zaznaczone pozycje z listy obserwowanych',
'watchlistcontains' => 'Lista obserwowanych przez Ciebie stron zawiera $1 pozycji.',
'watcheditlist' => "Oto alfabetyczna lista obserwowanych stron. Zaznacz, które z nich chcesz usunąć z listy i kliknij przycisk ''Usuń zaznaczone pozycje z listy obserwowanych'' znajdujący się na dole strony.",
'removingchecked' => 'Usuwamy zaznaczone pozycje z listy obserwowanych...',
'couldntremove' => 'Nie można było usunąć pozycji "$1"...',
'iteminvalidname' => 'Problem z pozycją "$1", niepoprawna nazwa...',
'wlnote' => 'Poniżej pokazano ostatnie $1 zmian dokonanych w ciągu ostatnich <b>$2</b> godzin.',
'wlshowlast' => 'Pokaż ostatnie $1 godzin $2 dni ($3)',
'wlsaved' => 'To jest ostatnia zapisana kopia Twojej listy obserwowanych.',
'wlhideshowown' => '$1 moje edycje',
'wlhideshowbots' => '$1 edycje botów',
'wldone' => 'Wykonano.',

'enotif_mailer' => 'Powiadomienie z serwisu {{SITENAME}}',
'enotif_reset' => 'Zaznacz wszystkie strony jako odwiedzone',
'enotif_newpagetext'=> 'To jest nowa strona.',
'changed' => 'zmieniona',
'created' => 'utworzona',
'enotif_subject' => 'Strona $PAGETITLE w serwisie {{SITENAME}} została $CHANGEDORCREATED przez użytkownika $PAGEEDITOR',
'enotif_lastvisited' => 'Zobacz $1 w celu obejrzenia wszystkich zmian od twojej ostatniej wizyty.',
'enotif_body' => 'Drogi $WATCHINGUSERNAME,

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
{{fullurl:{{ns:help}}:Contents}}',

# Delete/protect/revert
#
'deletepage' => 'Usuń stronę',
'confirm' => 'Potwierdź',
'excontent' => 'Zawartość strony "$1"',
'excontentauthor' => 'treść: "$1" (jedyny autor: [[{{ns:Special}}:Contributions/$2|$2]])',
'exbeforeblank' => 'Poprzednia zawartość pustej strony "$1"',
'exblank' => 'Strona była pusta',
'confirmdelete' => 'Potwierdź usunięcie',
'deletesub' => '(Usuwanie "$1")',
'historywarning' => 'Uwaga! Strona, którą chcesz skasować ma starsze wersje:',
'confirmdeletetext' => 'Zamierzasz trwale usunąć stronę lub plik z bazy danych razem z dotyczącą ich historią. Potwierdź, proszę, swoje zamiary, tzn., że rozumiesz konsekwencje, i że robisz to w zgodzie z [[{{ns:Project}}:Zasady i wskazówki|zasadami]].',
'actioncomplete' => 'Operacja wykonana',
'deletedtext' => 'Usunięto "$1". Rejestr ostatnio dokonanych kasowań możesz obejrzeć tutaj: $2.',
'deletedarticle' => 'usunięto "[[$1]]"',
'dellogpage' => 'Usunięte',
'dellogpagetext' => 'To jest lista ostatnio wykonanych kasowań.',
'deletionlog' => 'rejestr usunięć',
'reverted' => 'Przywrócono starszą wersję',
'deletecomment' => 'Powód usunięcia',
'imagereverted' => 'Przywrócenie wcześniejszej wersji powiodło się.',
'rollback' => 'Cofnij edycję',
'rollback_short' => 'Cofnij',
'rollbacklink' => 'cofnij',
'rollbackfailed' => 'Nie udało się cofnąć zmiany',
'cantrollback' => 'Nie można cofnąć edycji; jest tylko jedna wersja tej strony.',
'alreadyrolled' => 'Nie można cofnąć ostatniej zmiany strony [[:$1|$1]], której autorem jest [[{{ns:user}}:$2|$2]] ([[{{ns:user_talk}}:$2|dyskusja]]). Ktoś inny zdążył już to zrobić lub wprowadził własne poprawki do treści strony. Autorem ostatniej zmiany jest teraz [[{{ns:user}}:$3|$3]] ([[{{ns:user_talk}}:$3|dyskusja]]).',
# only shown if there is an edit comment
'editcomment' => 'Opisano ją następująco: "<i>$1</i>".',
'revertpage' => 'Wycofano edycję użytkownika [[{{ns:Special}}:Contributions/$2|$2]] ([[{{ns:user_talk}}:$2|dyskusja]]). Autor przywróconej wersji to [[{{ns:user}}:$1|$1]].',
'sessionfailure' => 'Błąd weryfikacji sesji. Twoje polecenie zostało anulowane, aby uniknąć przechwycenia sesji.

Naciśnij "wstecz", przeładuj stronę, po czym ponownie wydaj polecenie.',
'protectlogpage' => 'Zabezpieczone',
'protectlogtext' => 'Poniżej znajduje się lista blokad założonych i zdjętych z pojedynczych stron. By dowiedzieć się czegoś więcej o blokowaniu stron, przeczytaj [[{{ns:Project}}:Zabezpieczanie stron]].',
'protectedarticle' => 'zabezpieczono "[[$1]]"',
'unprotectedarticle' => 'odbezpieczono "[[$1]]"',
'protectsub' => '(Zabezpieczanie "$1")',
'confirmprotecttext' => 'Czy na pewno chcesz zabezpieczyć tę stronę?',
'confirmprotect' => 'potwierdź zabezpieczenie',
'protectmoveonly' => 'Zabezpiecz tylko przed przenoszeniem',
'protectcomment' => 'Powód zabezpieczenia',
'unprotectsub' => '(Odbezpieczanie "$1")',
'confirmunprotecttext' => 'Czy na pewno chcesz odbezpieczyć tę stronę?',
'confirmunprotect' => 'potwierdź odbezpieczenie',
'unprotectcomment' => 'Powód odbezpieczenia',
'protect-unchain' => 'Odblokowanie możliwości przenoszenia strony',
'protect-text' => 'Możesz tu zobaczyć i zmienić poziom zabezpieczenia strony <strong>$1</strong>. Upewnij się, że przestrzegasz [[{{ns:Project}}:Blokowanie stron|zasad zabezpieczania stron]].',
'protect-viewtext' => 'Nie masz uprawnień do zmiany poziomu zabezpieczenia strony. Obecne ustawienia dla strony <strong>$1</strong> to:',
'protect-default' => '(wszyscy)',
'protect-level-autoconfirmed' => 'tylko zarejestrowani',
'protect-level-sysop' => 'tylko administratorzy',

# restrictions (nouns)
'restriction-edit' => 'Edytuj',
'restriction-move' => 'Przenieś',

# Undelete
'undelete' => 'Odtwórz skasowaną stronę',
'undeletepage' => 'Odtwarzanie skasowanych stron',
'viewdeletedpage' => 'Zobacz skasowane wersje',
'undeletepagetext' => 'Poniższe strony zostały skasowane, ale ich kopia wciąż znajduje się w archiwum. Archiwum co jakiś czas także jest kasowane.',
'undeleteextrahelp' => "Aby odtworzyć całą stronę, pozostaw wszystkie pola niezaznaczone i kliknij '''Odtwórz'''. Aby wybrać częściowe odtworzenie należy zaznaczyć odpowiednie pole. Naciśnięcie '''Wyczyść''' wyczyści wszystkie pola, łącznie z opisem komentarza.",
'undeletearticle' => 'Odtwórz skasowaną stronę',
'undeleterevisions' => 'Liczba zarchiwizowanych wersji: $1',
'undeletehistory' => 'Odtworzenie strony spowoduje przywrócenie także jej wszystkich poprzednich wersji. Jeśli od czasu skasowania ktoś utworzył nową stronę o tej nazwie, odtwarzane wersje znajdą się w jej historii, a obecna wersja pozostanie bez zmian.',
'undeletehistorynoadmin' => 'Ten artykuł został skasowany. Przyczyna usunięcia podana jest w podsumowaniu poniżej, razem z danymi użytkownika, który edytował artykuł przed skasowaniem. Sama treść usuniętych wersji jest dostępna jedynie dla administratorów.',
'undeleterevision' => 'Skasowano wersję z $1',
'undeletebtn' => 'Odtwórz',
'undeletereset' => 'Wyczyść',
'undeletecomment' => 'Powód odtworzenia:',
'undeletedarticle' => 'odtworzono "$1"',
'undeletedrevisions' => 'Liczba odtworzonych wersji: $1',
'undeletedrevisions-files' => "Odtworzono $1 wersji i $2 plik(i)",
'undeletedfiles' => "Odtworzono $1 plik(i)",
'cannotundelete' => 'Odtworzenie nie powiodło się. Ktoś inny mógł odtworzyć stronę pierwszy.',
'undeletedpage' => '<big>Odtworzono stronę $1.</big>

Zobacz [[{{ns:Special}}:Log/delete]], jeśli chcesz przejrzeć rejestr ostatnio skasowanych i odtworzonych stron.',

# Namespace form on various pages
'namespace' => 'Przestrzeń nazw:',
'invert' => 'Odwróć wybór',

# Contributions
#
'contributions' => 'Wkład użytkownika',
'mycontris' => 'Moje edycje',
'contribsub' => 'Dla użytkownika $1',
'nocontribs' => 'Brak zmian odpowiadających tym kryteriom.',
'ucnote' => 'Oto lista ostatnich <b>$1</b> zmian dokonanych przez użytkownika w ciągu ostatnich <b>$2</b> dni.',
'uclinks' => 'Zobacz ostatnie $1 zmian; zobacz ostatnie $2 dni.',
'uctop' => ' (jako ostatnia)' ,
'newbies' => 'początkujący',

'sp-newimages-showfrom' => 'Pokaż nowe grafiki od $1',

'sp-contributions-newest' => 'Najnowsze',
'sp-contributions-oldest' => 'Najstarsze',
'sp-contributions-newer' => 'nowsze $1',
'sp-contributions-older' => 'starsze $1',
'sp-contributions-newbies-sub' => 'Dla nowych użytkowników',

# What links here
#
'whatlinkshere' => 'Linkujące',
'notargettitle' => 'Wskazywana strona nie istnieje',
'notargettext' => 'Nie podano strony albo użytkownika, dla których ta operacja ma być wykonana.',
'linklistsub' => '(Lista linków)',
'linkshere' => "Następujące strony odwołują się do '''[[:$1]]''':",
'nolinkshere' => "Żadna strona nie odwołuje się do '''[[:$1]]'''.",
'isredirect' => 'strona przekierowująca',
'istemplate' => 'dołączony szablon',

# Block/unblock IP
#
'blockip' => 'Zablokuj użytkownika',
'blockiptext' => 'Użyj poniższego formularza aby zablokować prawo zapisu spod określonego adresu IP. Powinno się to robić jedynie by zapobiec wandalizmowi, a zarazem w zgodzie z [[{{ns:Project}}:Zasady i wskazówki|zasadami]]. Podaj powód (np. umieszczając nazwy stron, na których dopuszczono się wandalizmu).',
'ipaddress' => 'Adres IP',
'ipadressorusername' => 'Adres IP lub nazwa użytkownika',
'ipbexpiry' => 'Czas blokady',
'ipbreason' => 'Powód',
'ipbanononly' => 'Zablokuj tylko anonimowych użytkowników',
'ipbcreateaccount' => 'Zapobiegnij utworzeniu konta',
'ipbsubmit' => 'Zablokuj użytkownika',
'ipbother' => 'Inny czas',
'ipboptions' => '2 godziny:2 hours,1 dzień:1 day,3 dni:3 days,1 tydzień:1 week,2 tygodnie:2 weeks,1 miesiąc:1 month,3 miesiące:3 months,6 miesięcy:6 months,1 rok:1 year,nieskończony:infinite',
'ipbotheroption' => 'inny',
'badipaddress' => 'Adres IP jest źle utworzony',
'blockipsuccesssub' => 'Zablokowanie powiodło się',
'blockipsuccesstext' => 'Użytkownik [[{{ns:Special}}:Contributions/$1|$1]] został zablokowany. <br />Przejdź do [[{{ns:Special}}:Ipblocklist|listy zablokowanych adresów IP]] by przejrzeć blokady.',
'unblockip' => 'Odblokuj użytkownika',
'unblockiptext' => 'Użyj poniższego formularza by przywrócić prawa zapisu dla poprzednio zablokowanego użytkownika lub adresu IP.',
'ipusubmit' => 'Odblokuj użytkownika',
'unblocked' => '[[{{ns:User}}:$1|$1]] został odblokowany.',
'ipblocklist' => 'Lista zablokowanych użytkowników i adresów IP',
'blocklistline' => '$1, $2 zablokował $3 ($4)',
'infiniteblock' => 'na zawsze',
'expiringblock' => 'wygasa $1',
'anononlyblock' => 'tylko anonimowi',
'createaccountblock' => 'Zablokowano możliwość utworzenia konta',
'ipblocklistempty' => 'Lista zablokowanych użytkowników i adresów IP jest pusta',
'blocklink' => 'zablokuj',
'unblocklink' => 'odblokuj',
'contribslink' => 'wkład',
'autoblocker' => 'Zablokowano Cię automatycznie ponieważ używasz tego samego adresu IP co użytkownik "[[{{ns:user}}:$1|$1]]". Powód: "\'\'\'$2\'\'\'".',
'blocklogpage' => 'Zablokowani',
'blocklogentry' => 'zablokowano "[[$1]]", czas blokady: $2',
'blocklogtext' => 'Poniżej znajduje się lista blokad założonych i zdjętych z poszczególnych adresów IP. Na liście nie znajdą się adresy IP, które zablokowano w sposób automatyczny. By przejrzeć listę obecnie aktywnych blokad, przejdź na stronę [[{{ns:Special}}:Ipblocklist|Zablokowane adresy IP]].',
'unblocklogentry' => 'odblokowano "$1"',
'range_block_disabled' => 'Możliwość blokowania zakresu numerów IP została wyłączona.',
'ipb_expiry_invalid' => 'Błędny czas wygaśnięcia.',
'ipb_already_blocked' => '"$1" jest już zablokowany.',
'ip_range_invalid' => 'Niewłaściwy zakres adresów IP.',
'ipb_cant_unblock' => 'Błąd: Blokada o ID $1 nie została znaleziona. Mogła ona zostać odblokowana wcześniej.',
'proxyblockreason' => 'Twój adres IP został zablokowany - jest to otwarte proxy. Sprawę należy rozwiązać u dostawcy Internetu.',
'proxyblocksuccess' => 'Wykonane.',
'sorbsreason' => 'Twój adres IP znajduje się na liście serwerów open proxy w [http://www.sorbs.net SORBS] DNSBL.',
'sorbs_create_account_reason' => 'Twój adres IP znajduje się na liście serwerów open proxy w [http://www.sorbs.net SORBS] DNSBL. Nie możesz utworzyć konta.',

# Developer tools
#
'lockdb' => 'Zablokuj bazę danych',
'unlockdb' => 'Odblokuj bazę danych',
'lockdbtext' => 'Zablokowanie bazy danych uniemożliwi wszystkim użytkownikom edycję stron, zmianę preferencji, edycję list obserwowanych artykułów oraz inne czynności wymagające dostępu do bazy danych. Potwierdź, proszę, że to jest zgodne z Twoimi zamiarami, i że odblokujesz bazę danych, gdy tylko zakończysz zadania administracyjne.',
'unlockdbtext' => 'Odblokowanie bazy danych umożliwi wszystkim użytkownikom edycję stron, zmianę preferencji, edycję list obserwowanych artykułów oraz inne czynności związane ze zmianami w bazie danych. Potwierdź, proszę, że to jest zgodne z Twoimi zamiarami.',
'lockconfirm' => 'Tak, naprawdę chcę zablokować bazę danych.',
'unlockconfirm' => 'Tak, naprawdę chcę odblokować bazę danych.',
'lockbtn' => 'Zablokuj bazę danych',
'unlockbtn' => 'Odblokuj bazę danych',
'locknoconfirm' => 'Nie zaznaczyłeś pola potwierdzenia.',
'lockdbsuccesssub' => 'Baza danych została pomyślnie zablokowana',
'unlockdbsuccesssub' => 'Blokada bazy danych usunięta',
'lockdbsuccesstext' => 'Baza danych została zablokowana.<br />Pamiętaj by [[{{ns:Special}}:Unlockdb|usunąć blokadę]] po zakończeniu działań administracyjnych.',
'unlockdbsuccesstext' => 'Baza danych została odblokowana.',
'databasenotlocked' => 'Baza danych nie jest zablokowana.',

# Make sysop
'makesysoptitle' => 'Nadaj użytkownikowi uprawnienia administratora',
'makesysoptext' => 'Ten formularz jest wykorzystywany przez użytkowników o statusie biurokraty do przyznawania innym użytkownikom praw administratora. Aby to uczynić, wpisz nazwę użytkownika i kliknij na przycisk.',
'makesysopname' => 'Nazwa użytkownika:',
'makesysopsubmit' => 'Przyznaj temu użytkownikowi uprawnienia administratora',
'makesysopok' => '<b>Użytkownik "$1" otrzymał uprawnienia administratora</b>',
'makesysopfail' => '<b>Użytkownik "$1" nie otrzymał uprawnienień administratora. (Czy wprowadziłeś poprawną nazwę użytkownika?)</b>',
'setbureaucratflag' => 'Ustaw status biurokraty',
'rightslog' => 'Uprawnienia',
'rightslogtext' => 'Rejestr zmian uprawnień użytkowników.',
'rightslogentry' => 'zmienił uprawnienia użytkownika $1 z "$2" na "$3"',
'rights' => 'Uprawnienia:',
'set_user_rights' => 'Zmień uprawnienia użytkownika',
'user_rights_set' => '<b>Uprawnienia użytkownika "$1" zostały zmienione</b>',
'set_rights_fail' => '<b>Uprawnienia użytkownika "$1" nie zostały zmienione. (Czy wprowadziłeś poprawną nazwę użytkownika?)</b>',
'makesysop' => 'Przyznaj użytkownikowi uprawnienia administratora',
'already_sysop' => 'Ten użytkownik jest już administratorem',
'already_bureaucrat' => 'Ten użytkownik jest już biurokratą',
'rightsnone' => '(podstawowe)',

# Move page
#
'movepage' => 'Przenieś stronę',
'movepagetext' => "Za pomocą poniższego formularza zmienisz nazwę strony, przenosząc jednocześnie jej historię.
Pod starym tytułem zostanie umieszczona strona przekierowująca. Linki do starego tytułu pozostaną niezmienione.
Upewnij się, że uwzględniasz podwójne lub zerwane przekierowania. Odpowiadasz za to, żeby linki odnosiły się do właściwych artykułów!

Strona '''nie''' będzie przeniesiona jeśli:
*jest pusta i nigdy nie była edytowana
*jest stroną przekierowującą
*strona o nowej nazwie już istnieje

'''UWAGA!'''
Może to być drastyczna lub nieprzewidywalna zmiana w przypadku popularnych stron; upewnij się co do konsekwencji tej operacji zanim się na nią zdecydujesz.",
'movepagetalktext' => "Odpowiednia strona dyskusji, jeśli istnieje, będzie przeniesiona automatycznie, pod warunkiem, że:
*nie przenosisz strony do innej przestrzeni nazw
*nie istnieje strona dyskusji o nowej nazwie
W takich przypadkach tekst dyskusji trzeba przenieść, i ewentualnie połączyć z istniejącym, ręcznie. Możesz też zrezygnować z przeniesienia dyskusji (poniższy ''checkbox'').",
'movearticle' => 'Przenieś stronę',
'movenologin' => 'Brak logowania',
'movenologintext' => 'Musisz być zarejestrowanym i [[{{ns:Special}}:Userlogin|zalogowanym]] użytkownikiem aby móc przenieść stronę.',
'newtitle' => 'Nowy tytuł',
'movepagebtn' => 'Przenieś stronę',
'pagemovedsub' => 'Przeniesienie powiodło się',
'pagemovedtext' => 'Strona "[[:$1|$1]]" została przeniesiona do "[[:$2|$2]]".',
'articleexists' => 'Strona o podanej nazwie już istnieje albo wybrana przez Ciebie nazwa nie jest poprawna. Wybierz, proszę, nową nazwę.',
'talkexists' => 'Strona artykułu została przeniesiona, natomiast strona dyskusji nie - strona dyskusji o nowym tytule już istnieje. Połącz, proszę, teksty obu dyskusji ręcznie.',
'movedto' => 'przeniesiono do',
'movetalk' => 'Przenieś także stronę dyskusji, jeśli to możliwe.',
'talkpagemoved' => 'Odpowiednia strona dyskusji także została przeniesiona.',
'talkpagenotmoved' => 'Odpowiednia strona dyskusji <strong>nie</strong> została przeniesiona.',
'1movedto2' => '[[$1]] przeniesiono do [[$2]]',
'1movedto2_redir' => '[[$1]] przeniesiono do [[$2]] nad przekierowaniem',
'movelogpage' => 'Przeniesione',
'movelogpagetext' => 'Oto lista stron, które ostatnio zostały przeniesione.',
'movereason' => 'Powód',
'revertmove' => 'cofnij',
'delete_and_move' => 'Usuń i przenieś',
'delete_and_move_text' => '== Wymagane usunięcie ==

Artykuł docelowy "[[:$1|$1]]" już istnieje. Czy chcesz go usunąć, by zrobić miejsce dla przenoszonego artykułu?',
'delete_and_move_confirm' => 'Tak, usuń stronę',
'delete_and_move_reason' => 'Usunięto by zrobić miejsce dla przenoszonego artykułu',
'selfmove' => 'Nazwy stron źródłowej i docelowej są takie same. Strony nie można przenieść na nią samą!',
'immobile_namespace' => 'Docelowy tytuł jest specjalnego typu. Nie można przenieść do tej przestrzeni nazw.',

# Export

'export' => 'Eksport stron',
'exporttext' => 'Możesz wyeksportować tekst i historię edycji danej strony lub zestawu stron w postaci XML. Taki zrzut można potem (jak import już będzie działać) zaimportować do innej wiki działającej na oprogramowaniu MediaWiki, obrabiać lub po prostu trzymać dla zabawy.

Żeby uzyskać kilka stron wpisz ich nazwy jedna pod drugą.

Można również użyć łącza, np. [[{{ns:Special}}:Export/{{Mediawiki:mainpage}}]] dla strony {{Mediawiki:mainpage}}.',
'exportcuronly' => 'Tylko bieżąca wersja, bez historii',
'exportnohistory' => "----
'''Uwaga:''' możliwość eksportowania pełnej historii stron została wyłączona.",
'export-submit' => 'Eksportuj',

# Namespace 8 related

'allmessages' => 'Komunikaty systemowe',
'allmessagesname' => 'Nazwa',
'allmessagesdefault' => 'Tekst domyślny',
'allmessagescurrent' => 'Tekst obecny',
'allmessagestext' => 'Oto lista wszystkich komunikatów dostępnych w przestrzeni nazw MediaWiki:',
'allmessagesnotsupportedUI' => 'Wybrany przez Ciebie język interfejsu <b>$1</b> nie jest wspierany przez stronę Special:Allmessages.',
'allmessagesnotsupportedDB' => 'Strona \'\'\'Special:Allmessages\'\'\' nie może być użyta, ponieważ \'\'\'$wgUseDatabaseMessages\'\'\' jest wyłączone.',
'allmessagesfilter' => 'Filtr nazw komunikatów:',
'allmessagesmodified' => 'Pokaż tylko zmodyfikowane',

# Thumbnails

'thumbnail-more' => 'Powiększ',
'missingimage' => '<b>Brak obrazka</b><br /><i>$1</i>',
'filemissing' => 'Brak pliku',
'thumbnail_error' => 'Błąd przy generowaniu miniatury: $1',

# Special:Import
'import' => 'Importuj strony',
'importinterwiki' => 'Import transwiki',
'import-interwiki-text' => 'Wybierz wiki i nazwę strony do importowania. Daty oraz nazwy autorów zostaną zachowane. Wszystkie operacje importu transwiki są odnotowywane w [[{{ns:Special}}:Log/import|rejestrze importu]].',
'import-interwiki-history' => 'Kopiuj całą historię edycji tej strony',
'import-interwiki-submit' => 'Importuj',
'import-interwiki-namespace' => 'Przenieś strony do przestrzeni nazw:',
'importtext' => 'Używając narzędzia Special:Export wyeksportuj plik ze źródłowej wiki, zapisz go na swoim dysku, a następnie prześlij go tutaj.',
'importstart' => 'Trwa importowanie stron...',
'import-revision-count' => '$1 {{PLURAL:$1|wersja|wersji}}',
'importnopages' => 'Brak stron do importu.',
'importfailed' => 'Import nie powiódł się: $1',
'importunknownsource' => 'Nieznany format importu źródłowego',
'importcantopen' => 'Nie można otworzyć importowanego pliku',
'importbadinterwiki' => 'Błędny link interwiki',
'importnotext' => 'Brak tekstu lub zawartości',
'importsuccess' => 'Import zakończony powodzeniem!',
'importhistoryconflict' => 'Wystąpił konflikt wersji (ta strona mogła zostać importowana już wcześniej)',
'importnosources' => 'Możliwość bezpośredniego importu historii została wyłączona: nie zdefiniowano źródła.',
'importnofile' => 'Importowany plik nie został załadowany.',
'importuploaderror' => 'Przesłanie pliku nie powiodło się. Możliwe, że plik jest większy od dozwolonego limitu.',

# import log
'importlogpage' => 'Rejestr importu',
'importlogpagetext' => 'Rejestr przeprowadzonych importów stron z innych serwisów wiki.',


# tooltip help for some actions, most are in Monobook.js
'tooltip-search' => 'Przeszukaj serwis {{SITENAME}}',
'tooltip-minoredit' => 'Oznacz zmiany jako drobne',
'tooltip-save' => 'Zapisz zmiany',
'tooltip-preview' => 'Obejrzyj efekt swojej edycji przed zapisaniem zmian!',
'tooltip-diff' => 'Pokaż zmiany dokonane w tekście.',
'tooltip-compareselectedversions' => 'Zobacz różnice między dwoma wybranymi wersjami strony.',
'tooltip-watch' => 'Dodaj tę stronę do listy obserwowanych',

# stylesheets
'common.css' => '/* Kod CSS umieszczony tutaj zostanie zastosowany we wszystkich skórkach */',
'monobook.css' => '/* Kod CSS umieszczony tutaj wpłynie na wygląd skórki Monobook */',

'notacceptable' => 'Serwer wiki nie jest w stanie dostarczyć danych, które Twoja przeglądarka będzie w stanie odczytać.',

# Attribution

'anonymous' => 'Anonimowy użytkownicy serwisu {{SITENAME}}',
'siteuser' => 'Użytkownik serwisu {{SITENAME}} - $1',
'lastmodifiedatby' => 'Ostatnia edycja tej strony: $2, $1 (autor zmian: $3)',
'and' => 'oraz',
'othercontribs' => 'Inni autorzy: $1.',
'others' => 'inni',
'creditspage' => 'Autorzy',
'nocredits' => 'Nie ma informacji o autorach tej strony.',

# Spam protection

'spamprotectiontitle' => 'Filtr antyspamowy',
'spamprotectiontext' => 'Strona, którą chciałeś zapisać została zablokowana przez filtr antyspamowy. Najprawdopodobniej zostało to spowodowane przez link do zewnętrznej strony internetowej.',
'spamprotectionmatch' => 'Tekst, który uruchomił nasz filtr antyspamowy to: $1',
'subcategorycount' => '{{PLURAL:$1|Jest jedna podkategoria|Liczba podkategorii: $1}}',
'categoryarticlecount' => '{{PLURAL:$1|Jest jeden artykuł w tej kategorii|Liczba artykułów w tej kategorii: $1}}',
'listingcontinuesabbrev' => " c.d.",
'spam_reverting' => 'Przywracanie ostatniej wersji nie zawierającej odnośników do $1',
'spam_blanking' => 'Wszystkie wersje zawierały odnośniki do $1; czyszczenie strony',

# Info page
'infosubtitle' => 'Informacja o stronie',
'numedits' => 'Liczba edycji (artykuł): $1',
'numtalkedits' => 'Liczba edycji (strona dyskusji): $1',
'numwatchers' => 'Liczba obserwujących: $1',
'numauthors' => 'Liczba autorów (artykuł): $1',
'numtalkauthors' => 'Liczba autorów (strona dyskusji): $1',

# Math options
'mw_math_png' => 'Zawsze jako PNG',
'mw_math_simple' => 'HTML dla prostych, dla reszty PNG',
'mw_math_html' => 'Spróbuj HTML; jeśli zawiedzie, to PNG',
'mw_math_source' => 'Pozostaw w TeXu (dla przeglądarek tekstowych)',
'mw_math_modern' => 'HTML, dla nowszych przeglądarek',
'mw_math_mathml' => 'MathML (eksperymentalne)',

# Patrolling
'markaspatrolleddiff' => 'oznacz edycję jako "sprawdzoną"',
'markaspatrolledtext' => 'Oznacz ten artykuł jako "sprawdzony"',
'markedaspatrolled' => 'Sprawdzone',
'markedaspatrolledtext' => 'Ta wersja została oznaczona jako "sprawdzona".',
'rcpatroldisabled' => 'Wyłączono patrolowanie w "Ostatnich zmianach"',
'rcpatroldisabledtext' => 'Patrolowanie w "Ostatnich zmianach" jest obecnie wyłączone',
'markedaspatrollederror' => 'Nie można oznaczyć jako "sprawdzone"',
'markedaspatrollederrortext' => 'Musisz wybrać wersję żeby oznaczyć ją jako "sprawdzoną".',

# Monobook.js: tooltips and access keys for monobook
'monobook.js' => '/* Deprecated; use Common.js */',

'accesskey-userpage' => '.',
'tooltip-userpage' => 'Moja osobista strona',
'accesskey-anonuserpage' => '.',
'tooltip-anonuserpage' => 'Strona użytkownika numeru IP spod którego edytujesz',
'accesskey-mytalk' => 'n',
'tooltip-mytalk' => 'Moja strona dyskusji',
'accesskey-anontalk' => 'n',
'tooltip-anontalk' => 'Dyskusja o edycjach z tego numeru IP',
'accesskey-preferences' => '',
'tooltip-preferences' => 'Moje preferencje',
'accesskey-watchlist' => 'l',
'tooltip-watchlist' => 'Lista stron obserwowanych',
'accesskey-mycontris' => 'y',
'tooltip-mycontris' => 'Lista moich edycji',
'accesskey-login' => 'o',
'tooltip-login' => 'Zachęcamy do zalogowania się, choć nie jest to obowiązkowe.',
'accesskey-anonlogin' => 'o',
'tooltip-anonlogin' => 'Zachęcamy do zalogowania się, choć nie jest to obowiązkowe',
'accesskey-logout' => '',
'tooltip-logout' => 'Wylogowanie',
'accesskey-talk' => 't',
'tooltip-talk' => 'Dyskusja o zawartości tej strony.',
'accesskey-edit' => 'e',
'tooltip-edit' => 'Możesz edytować tę stronę. Przed zapisaniem zmian użyj przycisku podgląd.',
'accesskey-addsection' => '+',
'tooltip-addsection' => 'Dodaj swój komentarz do dyskusji',
'accesskey-viewsource' => 'e',
'tooltip-viewsource' => 'Ta strona jest zabezpieczona. Możesz zobaczyć tekst źródłowy.',
'accesskey-history' => 'h',
'tooltip-history' => 'Starsze wersje tej strony.',
'accesskey-protect' => '=',
'tooltip-protect' => 'Zabezpiecz tę stronę.',
'accesskey-delete' => 'd',
'tooltip-delete' => 'Usuń tę stronę',
'accesskey-undelete' => 'd',
'tooltip-undelete' => 'Przywróć wersję tej strony sprzed usunięcia',
'accesskey-move' => 'm',
'tooltip-move' => 'Przenieś tę stronę.',
'accesskey-nomove' => '',
'tooltip-nomove' => 'Nie masz wystarczających uprawnień do przeniesienia tej strony',
'accesskey-watch' => 'w',
'tooltip-watch' => 'Dodaj tę stronę do listy obserwowanych',
'accesskey-unwatch' => 'w',
'tooltip-unwatch' => 'Usuń tę stronę z listy obserwowanych',
'accesskey-search' => 'f',
'tooltip-search' => 'Szukaj w wiki',
'accesskey-logo' => '',
'tooltip-logo' => 'Strona główna',
'accesskey-mainpage' => 'z',
'tooltip-mainpage' => 'Zobacz stronę główną',
'accesskey-portal' => '',
'tooltip-portal' => 'O projekcie, co możesz zrobić, gdzie możesz znaleźć informacje',
'accesskey-currentevents' => '',
'tooltip-currentevents' => 'Informacje o aktualnych wydarzeniach',
'accesskey-recentchanges' => 'r',
'tooltip-recentchanges' => 'Lista ostatnich zmian w artykułach',
'accesskey-randompage' => 'x',
'tooltip-randompage' => 'Pokaż losowo wybraną stronę',
'accesskey-help' => '',
'tooltip-help' => 'Zapoznaj się z obsługą wiki',
'accesskey-sitesupport' => '',
'tooltip-sitesupport' => 'Wesprzyj nas',
'accesskey-whatlinkshere' => 'j',
'tooltip-whatlinkshere' => 'Pokaż listę stron linkujących do tego artykułu',
'accesskey-recentchangeslinked' => 'k',
'tooltip-recentchangeslinked' => 'Ostatnie zmiany w stronach linkujących do tej strony',
'accesskey-feed-rss' => '',
'tooltip-feed-rss' => 'Kanał RSS dla tej strony',
'accesskey-feed-atom' => '',
'tooltip-feed-atom' => 'Kanał Atom dla tej strony',
'accesskey-contributions' => '',
'tooltip-contributions' => 'Pokaż listę edycji tego użytkownika',
'accesskey-emailuser' => '',
'tooltip-emailuser' => 'Wyślij e-mail do tego użytkownika',
'accesskey-upload' => 'u',
'tooltip-upload' => 'Wyślij plik na serwer',
'accesskey-specialpages' => 'q',
'tooltip-specialpages' => 'Lista wszystkich specjalnych stron',
'accesskey-main' => 'c',
'tooltip-main' => 'Zobacz stronę artykułu',
'accesskey-user' => 'c',
'tooltip-user' => 'Zobacz stronę osobistą użytkownika',
'accesskey-media' => 'c',
'tooltip-media' => 'Pokaż stronę pliku',
'accesskey-special' => '',
'tooltip-special' => 'To jest specjalna strona. Nie możesz jej edytować.',
'accesskey-project' => 'a',
'tooltip-project' => 'Zobacz stronę projektu',
'accesskey-image' => 'c',
'tooltip-image' => 'Zobacz stronę grafiki',
'accesskey-mediawiki' => 'c',
'tooltip-mediawiki' => 'Zobacz komunikat systemowy',
'accesskey-template' => 'c',
'tooltip-template' => 'Zobacz szablon',
'accesskey-help' => 'c',
'tooltip-help' => 'Zobacz stronę pomocy',
'accesskey-category' => 'c',
'tooltip-category' => 'Zobacz stronę kategorii',

# image deletion
'deletedrevision' => 'Skasowano poprzednie wersje $1.',

# browsing diffs
'previousdiff' => '← Poprzednia edycja',
'nextdiff' => 'Następna edycja →',

'imagemaxsize' => 'Na stronach opisu pokaż grafiki przeskalowane do rozdzielczości:',
'thumbsize' => 'Rozmiar miniaturki:',
'showbigimage' => 'Pobierz grafikę w wyższej rozdzielczości ($1x$2, $3 KB)',

'newimages' => 'Najnowsze grafiki',
'showhidebots' => '($1 boty)',
'noimages' => 'Nic.',

# labels for User: and Title: on Special:Log pages
'specialloguserlabel' => 'Użytkownik:',
'speciallogtitlelabel' => 'Tytuł:',

'passwordtooshort' => 'Twoje hasło jest za krótkie. Musi mieć co najmniej $1 znaków.',

# Media Warning
'mediawarning' => "'''Uwaga:''' Ten plik może zawierać złośliwy kod, otwierając go możesz zarazić swój system.<hr />",

'fileinfo' => '$1KB, typ MIME: <code>$2</code>',

# Metadata
'metadata' => 'Metadane',
'metadata-help' => 'Niniejszy plik zawiera dodatkowe informacje, prawdopodobnie dodane przez aparat cyfrowy lub skaner. Jeśli plik był modyfikowany, dane mogą być częściowo błędne.',
'metadata-expand' => 'Pokaż szczegóły',
'metadata-collapse' => 'Ukryj szczegóły',

# Exif tags
'exif-imagewidth' => 'Szerokość',
'exif-imagelength' => 'Wysokość',
'exif-compression' => 'Schemat kompresji',
'exif-orientation' => 'Orientacja',
'exif-planarconfiguration' => 'Rozkład danych',
'exif-xresolution' => 'rozdzielczosć w poziomie',
'exif-yresolution' => 'rozdzielczość w pionie',
'exif-resolutionunit' => 'Jednostki rozdzielczośći X i Y',
'exif-jpeginterchangeformatlength' => 'Wielkość pliku JPEG',
'exif-whitepoint' => 'Punkty bieli',
'exif-ycbcrcoefficients' => 'Współczynniki macierzy transformacji przestrzeni barw',
'exif-datetime' => 'Data i czas zmiany pliku',
'exif-imagedescription' => 'Tytuł',
'exif-make' => 'Producent aparatu',
'exif-model' => 'Model aparatu',
'exif-software' => 'Oprogramowanie',
'exif-artist' => 'Autor',
'exif-copyright' => 'Właściciel praw autorskich',
'exif-exifversion' => 'Wersja EXIF',
'exif-flashpixversion' => 'Obsługiwana wersja Flashpix',
'exif-colorspace' => 'Przestrzeń kolorów',
'exif-componentsconfiguration' => 'Znaczenie elementów',
'exif-compressedbitsperpixel' => 'Tryb kompresji grafiki',
'exif-makernote' => 'Informacje producenta',
'exif-usercomment' => 'Komentarz',
'exif-relatedsoundfile' => 'Zawiera plik audio',
'exif-datetimeoriginal' => 'Data i czas utworzenia',
'exif-datetimedigitized' => 'Data i czas utworzenia kopii cyfrowej',
'exif-exposuretime' => 'Czas ekspozycji',
'exif-exposuretime-format' => '$1 s. ($2)',
'exif-fnumber' => 'Wartość przesłony',
'exif-exposureprogram' => 'Program ekspozycji',
'exif-oecf' => 'Optyczno-elektroniczna zamiana wektora',
'exif-shutterspeedvalue' => 'Czas naświetlania',
'exif-aperturevalue' => 'Wartość przesłony',
'exif-brightnessvalue' => 'Jasność',
'exif-exposurebiasvalue' => 'Nastawienie ekspozycji',
'exif-maxaperturevalue' => 'Maksymalna wartość przysłony',
'exif-subjectdistance' => 'Odległość od obiektu',
'exif-meteringmode' => 'Tryb pomiaru',
'exif-lightsource' => 'Źródło światła',
'exif-flash' => 'Lampa błyskowa',
'exif-focallength' => 'Długość ogniskowej soczewki',
'exif-subjectarea' => 'Otoczenie obiektu',
'exif-flashenergy' => 'Moc lampy błyskowej',
'exif-focalplanexresolution' => 'Rozdzielczość w poziomie płaszczyzny odwzorowania obiektywu',
'exif-focalplaneyresolution' => 'Rozdzielczość w pionie płaszczyzny odwzorowania obiektywu',
'exif-focalplaneresolutionunit' => 'Jednostka rozdzielczości płaszczyzny odwzorowania obiektywu',
'exif-subjectlocation' => 'Umiejscowienie obiektu',
'exif-exposureindex' => 'Tabela ekspozycji',
'exif-sensingmethod' => 'Metoda pomiaru',
'exif-filesource' => 'Plik źródłowy',
'exif-scenetype' => 'Typ sceny',
'exif-cfapattern' => 'Wzór CFA',
'exif-customrendered' => 'Rodzaj obróbki',
'exif-exposuremode' => 'Ekspozycja',
'exif-whitebalance' => 'Balans bieli',
'exif-digitalzoomratio' => 'Przybliżenie cyfrowe',
'exif-focallengthin35mmfilm' => 'Długość ogniskowej na filmie 35 mm',
'exif-scenecapturetype' => 'Rodzaj sceny',
'exif-gaincontrol' => 'Kontrola sceny',
'exif-contrast' => 'Kontrast',
'exif-saturation' => 'Nasycenie barw',
'exif-sharpness' => 'Ostrość',
'exif-devicesettingdescription' => 'Opis ustawień urządzenia',
'exif-subjectdistancerange' => 'Zakres odległości od obiektu',
'exif-imageuniqueid' => 'ID grafiki',
'exif-gpsversionid' => 'Wersja GPS',
'exif-gpslatituderef' => 'Północna lub południowa szerokość geograficzna',
'exif-gpslatitude' => 'Szerokość geograficzna',
'exif-gpslongituderef' => 'Wschodnia lub zachodnia długość geograficzna',
'exif-gpslongitude' => 'Długość geograficzna',
'exif-gpsaltituderef' => 'Wielkość odwoławcza',
'exif-gpsaltitude' => 'Wysokość',
'exif-gpstimestamp' => 'Czas GPS (zegar atomowy)',
'exif-gpssatellites' => 'Satelity użyte do pomiaru',
'exif-gpsstatus' => 'Otrzymany status',
'exif-gpsmeasuremode' => 'Tryb pomiaru',
'exif-gpsdop' => 'Precyzja pomiaru',
'exif-gpsspeedref' => 'Jednostka prędkości',
'exif-gpsspeed' => 'Prędkość odbiornika GPS',
'exif-gpstrackref' => 'Poprawka pomiędzy kierunkiem i celem',
'exif-gpstrack' => 'Kierunek ruchu',
'exif-gpsdestlatitude' => 'Szerokość geograficzna celu',
'exif-gpsdestlongitude' => 'Długość geograficzna celu',
'exif-gpsdestdistance' => 'Odległość od celu',
'exif-gpsprocessingmethod' => 'Nazwa metody GPS',
'exif-gpsareainformation' => 'Nazwa przestrzeni GPS',
'exif-gpsdatestamp' => 'Data GPS',

# Exif attributes

'exif-compression-1' => 'Nieskompresowany',

'exif-orientation-1' => 'Normalna',
'exif-orientation-2' => 'Odwrócona w poziomie',
'exif-orientation-3' => 'Obrócona o 180°',
'exif-orientation-4' => 'Odwrócona w pionie',
'exif-orientation-5' => 'Obrót o 90° przeciwnie do wskazówek zegara i odwrócenie w pionie',
'exif-orientation-6' => 'Obrót o 90° zgodnie ze wskazówkami zegara',
'exif-orientation-7' => 'Obrót o 90° zgodnie do wskazówek zegara i odwrót w pionie',
'exif-orientation-8' => 'Obrót o 90° przeciwnie do wskazówek zegara',

'exif-planarconfiguration-1' => 'format masywny',
'exif-planarconfiguration-2' => 'format powierzchniowy',

'exif-componentsconfiguration-0' => 'nie istnieje',

'exif-exposureprogram-0' => 'Nie zdefiniowany',
'exif-exposureprogram-1' => 'Manualny',
'exif-exposureprogram-2' => 'Normalny',
'exif-exposureprogram-3' => 'Preselekcja przesłony',
'exif-exposureprogram-4' => 'Preselekcja migawki',
'exif-exposureprogram-5' => 'Kreatywny (duża głębia ostrości)',
'exif-exposureprogram-6' => 'Aktywny (duża szybkość migawki)',
'exif-exposureprogram-7' => 'Tryb portretowy (dla zdjęć z bliska, z rozmazanym tłem)',
'exif-exposureprogram-8' => 'Tryb krajobrazowy (dla dalekich zdjęć z ostrym tłem)',

'exif-subjectdistance-value' => '$1 metrów',

'exif-meteringmode-0' => 'Nieznany',
'exif-meteringmode-1' => 'Średni',
'exif-meteringmode-2' => 'Średnia ważona',
'exif-meteringmode-3' => 'Punktowy',
'exif-meteringmode-4' => 'Wielopunktowy',
'exif-meteringmode-5' => 'Próbkowy',
'exif-meteringmode-6' => 'Częściowy',
'exif-meteringmode-255' => 'Inny',

'exif-lightsource-0' => 'Nieznane',
'exif-lightsource-1' => 'Światło dzienne',
'exif-lightsource-2' => 'Światło jarzeniowe',
'exif-lightsource-3' => 'Światło sztuczne (żarowe)',
'exif-lightsource-4' => 'Lampa błyskowa',
'exif-lightsource-9' => 'Dobra pogoda',
'exif-lightsource-10' => 'Zachmurzona pogoda',
'exif-lightsource-11' => 'Cienie',
'exif-lightsource-12' => 'Jarzeniowe światło dnia (D 5700 – 7100K)',
'exif-lightsource-13' => 'Jarzeniowe białe (N 4600 – 5400K)',
'exif-lightsource-14' => 'Jarzeniowe miękkie (W 3900 – 4500K)',
'exif-lightsource-15' => 'Jarzeniowe białe (WW 3200 – 3700K)',
'exif-lightsource-17' => 'Światło standardowe A',
'exif-lightsource-18' => 'Światło standardowe B',
'exif-lightsource-19' => 'Światło standardowe C',
'exif-lightsource-24' => 'Żarowe studyjne',
'exif-lightsource-255' => 'Inne źródło światła',

'exif-focalplaneresolutionunit-2' => 'cale',

'exif-sensingmethod-1' => 'Niezdefiniowana',
'exif-sensingmethod-7' => 'Trilinearna',

'exif-scenetype-1' => 'Obiekt fotografowany bezpośrednio',

'exif-customrendered-0' => 'Tryb normalny',
'exif-customrendered-1' => 'Tryb zmienny',

'exif-exposuremode-0' => 'Automatyczna',
'exif-exposuremode-1' => 'Manualna',
'exif-exposuremode-2' => 'Wieloprzysłonowa',

'exif-whitebalance-0' => 'Automatyczny balans bieli',
'exif-whitebalance-1' => 'Ręczny balans bieli',

'exif-scenecapturetype-0' => 'Standardowy',
'exif-scenecapturetype-1' => 'Krajobraz',
'exif-scenecapturetype-2' => 'Portret',
'exif-scenecapturetype-3' => 'Scena nocna',

'exif-gaincontrol-0' => 'Brak',
'exif-gaincontrol-1' => 'Niskie wzmocnienie',
'exif-gaincontrol-2' => 'Wysokie wzmocnienie',
'exif-gaincontrol-3' => 'Niskie osłabienie',
'exif-gaincontrol-4' => 'Wysokie osłabienie',

'exif-contrast-0' => 'Normalny',
'exif-contrast-1' => 'Miękki',
'exif-contrast-2' => 'Twardy',

'exif-saturation-0' => 'Normalne',
'exif-saturation-1' => 'Niskie',
'exif-saturation-2' => 'Wysokie',

'exif-sharpness-0' => 'Normalna',
'exif-sharpness-1' => 'Miękka',
'exif-sharpness-2' => 'Twarda',

'exif-subjectdistancerange-0' => 'Nieznana',
'exif-subjectdistancerange-1' => 'Makro',
'exif-subjectdistancerange-2' => 'Widok z bliska',
'exif-subjectdistancerange-3' => 'Widok z daleka',

// Pseudotags used for GPSLatitudeRef and GPSDestLatitudeRef
'exif-gpslatitude-n' => 'Szerokość geograficzna północna',
'exif-gpslatitude-s' => 'Szerokość geograficzna południowa',

// Pseudotags used for GPSLongitudeRef and GPSDestLongitudeRef
'exif-gpslongitude-e' => 'Długość geograficzna wschodnia',
'exif-gpslongitude-w' => 'Długość geograficzna zachodnia',

'exif-gpsstatus-a' => 'Pomiar w trakcie',
'exif-gpsstatus-v' => 'Pomiar interoperacyjny',

'exif-gpsmeasuremode-2' => '2-wymiarowy',
'exif-gpsmeasuremode-3' => '3-wymiarowy',

// Pseudotags used for GPSSpeedRef and GPSDestDistanceRef
'exif-gpsspeed-k' => 'Kilometry na godzinę',
'exif-gpsspeed-m' => 'Mile na godzinę',
'exif-gpsspeed-n' => 'Węzły',

// Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
'exif-gpsdirection-t' => 'Prawdziwy kierunek',
'exif-gpsdirection-m' => 'Kierunek magnetyczny',

# external editor support
'edit-externally' => 'Edytuj ten plik używając zewnętrznej aplikacji',
'edit-externally-help' => 'Zobacz więcej informacji o używaniu [http://meta.wikimedia.org/wiki/Help:External_editors zewnętrznych edytorów].',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'wszystkie',
'imagelistall' => 'wszystkich',
'watchlistall1' => 'wszystkie',
'watchlistall2' => 'wszystkie',
'namespacesall' => 'wszystkie',

# E-mail address confirmation
'confirmemail' => 'Potwierdź adres e-mail',
'confirmemail_noemail' => 'Nie podałeś prawidłowego adresu e-mail w [[Special:Preferences|preferencjach]].',
'confirmemail_text' => 'Wymagane jest potwierdzenie adresu e-mail przed użyciem funkcji pocztowych. Wciśnij przycisk poniżej aby wysłać na swój adres list uwierzytelniający. W liście znajdziesz link zawierający kod: załaduj go do przeglądarki aby potwierdzić swój adres.',
'confirmemail_send' => 'Wyślij kod potwierdzenia',
'confirmemail_sent' => 'E-mail uwierzytelniający został wysłany.',
'confirmemail_sendfailed' => 'Nie udało się wsłać maila potwierdzającego. Proszę sprawdzić adres.

Program zwrócił komunikat: $1',
'confirmemail_invalid' => 'Błędny kod potwierdzenia. Kod może być przedawniony.',
'confirmemail_needlogin' => 'Musisz $1 aby potwierdzić adres email.',
'confirmemail_success' => 'Adres e-mail został zatwierdzony. Możesz się zalogować i cieszyć możliwościami wiki.',
'confirmemail_loggedin' => 'Twój adres email został potwierdzony.',
'confirmemail_error' => 'Pojawiły się błędy przy zapisywaniu potwierdzenia.',

'confirmemail_subject' => '{{SITENAME}} - potwierdzenie adresu e-mail',
'confirmemail_body' => 'Ktoś łącząc się z komputera o adresie IP $1 zarejestrował w serwisie
{{SITENAME}} konto "$2" podając niniejszy adres email.

Aby potwierdzić, że to Ty zarejestrowałeś/aś to konto oraz aby włączyć 
wszystkie funkcje wymagające działającego adresu email, otwórz w swojej
przeglądarce ten link:

$3

Jeśli to NIE TY zarejestrowałeś/aś to konto, NIE KLIKAJ W POWYŻSZY LINK.
Kod zawarty w linku straci ważność $4.',

# Inputbox extension, may be useful in other contexts as well
'tryexact' => 'Użyj dokładnego wyrażenia',
'searchfulltext' => 'Szukaj w całych tekstach',
'createarticle' => 'Utwórz artykuł',

# Scary transclusion
'scarytranscludefailed' => '[Nie powiodło się pobranie szablonu dla $1]',
'scarytranscludetoolong' => '[URL za długi]',

# Trackbacks
'trackbackbox' => '<div id="mw_trackbacks">
Sygnały Trackback dla tego artykułu:<br />
$1
</div>',
'trackbackremove' => ' ([$1 Usuń])',
'trackbackdeleteok' => 'Trackback został usunięty.',

# delete conflict

'deletedwhileediting' => 'Uwaga: Ta strona została usunięta po tym, jak rozpoczęłeś jej edycję!',
'confirmrecreate' => "Użytkownik [[{{ns:user}}:$1|$1]] ([[{{ns:user_talk}}:$1|dyskusja]]) usunął tę stronę po tym jak rozpocząłeś jego edycję, podając jako powód usunięcia:
: '''$2'''
Potwierdź chęć odtworzenia tej strony.",
'recreate' => 'Odtwórz',
'tooltip-recreate' => 'Odtworzono stronę pomimo jej wcześniejszego usunięcia.',

# HTML dump
'redirectingto' => 'Przechodzenie do [[:$1|$1]]...',

# action=purge
'confirm_purge' => 'Wyczyścić bufor dla tej strony?

$1',
'confirm_purge_button' => 'Wyczyść',

'youhavenewmessagesmulti' => 'Masz nowe wiadomości: $1',
'searchcontaining' => "Szukaj artykułów zawierających ''$1''.",
'searchnamed' => "Szukaj artykułów nazywających się ''$1''.",
'articletitles' => "Artykuły zaczynające się od ''$1''.",
'hideresults' => 'Ukryj wyniki',

# DISPLAYTITLE
'displaytitle' => '(Link do tej strony to [[:$1|$1]])',

'loginlanguagelabel' => 'Język: $1',

# Multipage image navigation
'imgmultipageprev' => '&larr; poprzednia strona',
'imgmultipagenext' => 'następna strona &rarr;',
'imgmultigo' => 'Przejdź',
'imgmultigotopre' => 'Przejdź na stronę',

# Table pager
'ascending_abbrev' => 'rosn.',
'descending_abbrev' => 'mal.',
'table_pager_next' => 'Następna strona',
'table_pager_prev' => 'Poprzednia strona',
'table_pager_first' => 'Pierwsza strona',
'table_pager_last' => 'Ostatnia strona',
'table_pager_limit' => 'Pokaż $1 obiektów na stronę',
'table_pager_limit_submit' => 'Pokaż',
'table_pager_empty' => 'Brak wyników',

);

?>
