<?php
require_once("LanguageUtf8.php");

# FIXME: Lots of hardcoded Wikipedia-related text needs to be cleaned up.

# NOTE: To turn off "Current Events" in the sidebar,
# set "currentevents" => "-"

# The names of the namespaces can be set here, but the numbers
# are magical, so don't change or move them!  The Namespace class
# encapsulates some of the magic-ness.
#

# Yucky hardcoding hack as polish grammar need tweaking :o)
switch( $wgMetaNamespace ) {
case 'Wikipedia':
	$wgMetaTalkNamespace = 'Dyskusja_Wikipedii'; break;
case 'Wikisłownik':
	$wgMetaTalkNamespace = 'Wikidyskusja'; break;
case 'Wikicytaty':
	$wgMetaTalkNamespace = 'Dyskusja_Wikicytatów'; break;
default:
	$wgMetaTalkNamespace = 'Dyskusja_'.$wgMetaNamespace;
}

/* private */ $wgNamespaceNamesPl = array(
    NS_MEDIA            => "Media",
    NS_SPECIAL          => "Specjalna",
    NS_MAIN             => "",
    NS_TALK             => "Dyskusja",
    NS_USER             => "Wikipedysta",
    NS_USER_TALK        => "Dyskusja_Wikipedysty",
    NS_WIKIPEDIA        => $wgMetaNamespace,
    NS_WIKIPEDIA_TALK   => $wgMetaTalkNamespace,  // see above
    NS_IMAGE            => "Grafika",
    NS_IMAGE_TALK       => "Dyskusja_grafiki",
    NS_MEDIAWIKI        => "MediaWiki",
    NS_MEDIAWIKI_TALK   => "Dyskusja_MediaWiki",
    NS_TEMPLATE         => "Szablon",
    NS_TEMPLATE_TALK    => "Dyskusja_szablonu",
    NS_HELP             => "Pomoc",
    NS_HELP_TALK        => "Dyskusja_pomocy",
    NS_CATEGORY         => "Kategoria",
    NS_CATEGORY_TALK    => "Dyskusja_kategorii"
) + $wgNamespaceNamesEn;

/* private */ $wgQuickbarSettingsPl = array(
        "Brak", "Stały, z lewej", "Stały, z prawej", "Unoszący się, z lewej"
);

/* private */ $wgSkinNamesPl = array(
	'standard' => "Standard",
	'nostalgia' => "Nostalgia",
	'cologneblue' => "Cologne Blue",
	'smarty' => "Paddington",
	'montparnasse' => "Montparnasse",
	'davinci' => "DaVinci",
	'mono' => "Mono",
	'monobook' => "MonoBook",
 "myskin" => "MySkin" 
);

/* private */ $wgMathNamesPl = array( 
    "Zawsze jako PNG",
        "HTML dla prostych, dla reszty PNG",
        "Spróbuj HTML; jeśli zawiedzie, to PNG",
        "Pozostaw w TeXu (tekst)",
        "HTML, dla nowszych przeglądarek"
); 

/* private */ $wgUserTogglesPl = array(
        "hover" => "Pokazuj okienko podpowiedzi ponad linkami",
        "underline" => "Podkreślenie linków",
        "highlightbroken" => "<a href=\"\" class=\"new\">Podświetl</a> linki pustych stron (alternatywa: znak zapytania<a href=\"\" class=\"internal\">?</a>).",
        "justify" => "Wyrównuj tekst artykułu w kolumnie",
        "hideminor" => "Ukryj drobne zmiany w \"Ostatnich zmianach\"",
        "usenewrc" => "Konsolidacja ostatnich zmian (JavaScript)",
        "numberheadings" => "Automatyczna numeracja nagłówków",
	"showtoolbar" => "Show edit toolbar",
        "editondblclick" => "Podwójne kliknięcie rozpoczyna edycję (JavaScript)",
                "editsection" => "Możliwość edycji poszczególnych sekcji strony",
                "editsectiononrightclick" => "Kliknięcie prawym klawiszem na tytule sekcji<br>rozpoczyna jej edycję (JavaScript)",
                "showtoc" =>  "Spis treści (na stronach zawierających więcej niż 3 nagłówki)",
        "rememberpassword" => "Pamiętaj hasło między sesjami",
        "editwidth" => "Obszar edycji o pełnej szerokości",
        "watchdefault" => "Obserwuj strony, które będę edytować",
        "minordefault" => "Wszystkie zmiany zaznaczaj domyślnie jako drobne",
        "previewontop" => "Pokazuj podgląd przed oknem edycji",
                "nocache" => "Wyłącz pamięć podręczną"
);

/* private */ $wgBookstoreListPl = array(
        "AddALL" => "http://www.addall.com/New/Partner.cgi?query=$1&type=ISBN",
        "PriceSCAN" => "http://www.pricescan.com/books/bookDetail.asp?isbn=$1",
        "Barnes & Noble" => "http://shop.barnesandnoble.com/bookSearch/isbnInquiry.asp?isbn=$1",
        "Amazon.com" => "http://www.amazon.com/exec/obidos/ISBN=$1"
);

/* private */ $wgWeekdayNamesPl = array(
        "niedziela", "poniedziałek", "wtorek", "środa", "czwartek",
        "piątek", "sobota"
);

/* private */ $wgMonthNamesPl = array(
        "styczeń", "luty", "marzec", "kwiecień", "maj", "czerwiec",
        "lipiec", "sierpień", "wrzesień", "październik", "listopad",
        "grudzień"
);

/* private */ $wgMonthNamesGenPl = array(
        "stycznia", "lutego", "marca", "kwietnia", "maja", "czerwca",
        "lipca", "sierpnia", "września", "października", "listopada",
        "grudnia"
);

/* private */ $wgMonthAbbreviationsPl = array(
        "sty", "lut", "mar", "kwi", "maj", "cze", "lip", "sie",
        "wrz", "paź", "lis", "gru"
);

# All special pages have to be listed here: a description of ""
# will make them not show up on the "Special Pages" page, which
# is the right thing for some of them (such as the "targeted" ones).
#
/* private */ $wgValidSpecialPagesPl = array(
        "Userlogin"     => "",
        "Userlogout"    => "",
        "Preferences"   => "Zmiana moich preferencji",
        "Watchlist"     => "Obserwowane",
        "Recentchanges" => "Ostatnio zmienione",
        "Upload"        => "Przesyłanie plików",
        "Imagelist"     => "Lista obrazków i multimediów",
        "Listusers"     => "Zarejestrowani użytkownicy",
        "Statistics"    => "Statystyka",
        "Randompage"    => "Losowa strona",

        "Lonelypages"   => "Porzucone artykuły",
        "Unusedimages"  => "Porzucone pliki",
        "Popularpages"  => "Najpopularniejsze",
        "Wantedpages"   => "Najbardziej potrzebne",
        "Shortpages"    => "Najkrótsze",
        "Longpages"     => "Najdłuższe",
        "Newpages"      => "Nowoutworzone",
	"Ancientpages" => "Najstarsze",
        "Allpages"      => "Wszystkie",

        "Ipblocklist"   => "Zablokowane adresy IP",
        "Maintenance"   => "Prosta administracja",
        "Specialpages"  => "",
        "Contributions" => "",
        "Emailuser"     => "",
        "Whatlinkshere" => "",
        "Recentchangeslinked" => "",
        "Movepage"      => "",
        "Booksources"   => "Książki",
//	"Categories"    => "Kategorie stron",
	"Export"	=> "XML export",
	"Version"	=> "Version",
);

/* private */ $wgSysopSpecialPagesPl = array(
        "Blockip"       => "Zablokuj adres IP",
        "Asksql"        => "Zapytanie SQL",
        "Undelete"      => "Odtwarzanie skasowanych stron"

);

/* private */ $wgDeveloperSpecialPagesPl = array(
        "Lockdb"        => "Zablokuj zapis do bazy danych",
        "Unlockdb"      => "Odblokuj zapis do bazy danych",
);

/* private */ $wgAllMessagesPl = array(

# Bits of text used by many pages:
#
"categories" => "Kategorie stron",
"category" => "kategoria",
"category_header" => "Artykuły w kategorii \"$1\"",
"subcategories" => "Podkategorie",
"linktrail" => "/^([a-z]+)(.*)\$/sD",
"mainpage" => "Strona główna",
"mainpagetext" => "Instalacja oprogramowania powiodła się.",
"about" => "O Wikipedii",
"aboutwikipedia" => "O Wikipedii",
"aboutpage" => "{{ns:4}}:O_Wikipedii",
"help" => "Pomoc",
"helppage" => "{{ns:4}}:Pomoc",
"wikititlesuffix" => "{{SITENAME}}",
"bugreports" => "Raport o błędach",
"bugreportspage" => "{{ns:4}}:Błędy",
"sitesupport" => "Dary pieniężne",
"sitesupportpage" => "http://wikimediafoundation.org/fundraising", # If not set, won't appear. Can be wiki page or URL
"faq" => "FAQ",
"faqpage" => "{{ns:4}}:FAQ",
"edithelp" => "Pomoc w edycji",
"edithelppage" => "{{ns:4}}:Jak_edytować_stronę",
"cancel" => "Anuluj",
"qbfind" => "Znajdź",
"qbbrowse" => "Przeglądanie",
"qbedit" => "Edycja",
"qbpageoptions" => "Opcje strony",
"qbpageinfo" => "O stronie",
"qbmyoptions" => "Moje opcje",
"mypage" => "Moja strona",
"mytalk" => "Moja dyskusja",
"currentevents" => "-",
"errorpagetitle" => "Błąd",
"returnto" => "Wróć do strony: $1.",
"fromwikipedia" => "Z Wikipedii, wolnej encyklopedii.",
"whatlinkshere" => "Strony, które odwołują się do tej",
"help" => "Pomoc",
"search" => "Szukaj",
"go" => "OK",
"history" => "Historia strony",
"printableversion" => "Wersja do druku",
"editthispage" => "Edytuj",
"deletethispage" => "Usuń",
"protectthispage" => "Zabezpiecz",
"unprotectthispage" => "Odbezpiecz",
"newpage" => "Nowa strona",
"talkpage" => "Dyskusja",
"postcomment" => "Skomentuj",
"articlepage" => "Strona artykułu", 
"subjectpage" => "Strona dyskutowana", # for compatibility
"userpage" => "Strona wikipedysty", 
"wikipediapage" => "Strona metaartykułu", 
"imagepage" =>  "Strona grafiki", 
"viewtalkpage" => "Strona dyskusji",
"otherlanguages" => "Wersja",
"redirectedfrom" => "(Przekierowano z $1)",
"lastmodified" => "Tę stronę ostatnio zmodyfikowano o $1;",
"viewcount" => "Tę stronę obejrzano $1 razy;",
"gnunote" => "udostępniana jest w oparciu o licencję <a class=internal href='/wiki/GNU_FDL'>GNU FDL</a>; możesz ją samodzielnie uzupełnić lub poprawić.",
"printsubtitle" => "(z http://pl.wikipedia.org)",
"protectedpage" => "Strona zabezpieczona",
"administrators" => "{{ns:4}}:Administratorzy",
"sysoptitle" => "Wymagane prawa dostępu administratora",
"sysoptext" => "Ta operacja może być wykonana tylko przez
użytkowania o statusie \"administrator\".
Zobacz $1.",
"developertitle" => "Wymagane prawa dostępu Programisty",
"developertext" => "Ta operacja może być wykonana tylko przez
użytkownika o prawach \"Programisty\".
Zobacz $1.",
"nbytes" => "$1 bajtów",
"go" => "OK",
"ok" => "OK",
"sitetitle" => "{{SITENAME}}",
"sitesubtitle" => "Wolna Encyklopedia",
"retrievedfrom" => "Źródło: \"$1\"",
"newmessages" => "Masz $1.",
"newmessageslink" => "Nowe wiadomości",
"editsection" => "Edytuj",
"toc" => "Spis treści",
"showtoc" => "pokaż",
"hidetoc" => "schowaj",
"thisisdeleted" => "Pokaż/odtwórz $1",
"restorelink" => "skasowane wersje (w sumie $1)",

# Main script and global functions
#
"nosuchaction" => "Nie ma takiej operacji",
"nosuchactiontext" => "Oprogramowanie Wikipedii nie rozpoznaje
operacji takiej jak podana w URL",
"nosuchspecialpage" => "Nie ma takiej strony specjalnej",
"nospecialpagetext" => "Oprogramowanie Wikipedii nie rozpoznaje takiej
specjalnej strony.",

# General errors
#
"error" => "Błąd",
"databaseerror" => "Błąd bazy danych",
"dberrortext" => "Wystąpił błąd składni w zapytaniu do bazy danych.
Mogło to być spowodowane przez złe sformułowanie zapytania (zobacz $5)
albo przez błąd w oprogramowaniu.
Ostatnie, nieudane zapytanie to:
<blockquote><tt>$1</tt></blockquote>
wysłane przez funkcję \"<tt>$2</tt>\".
MySQL zgłosił błąd \"<tt>$3: $4</tt>\".",
"dberrortextcl" => "Wystąpił błąd składni w zapytaniu do bazy danych.
Ostatnie, nieudane zapytanie to:
\"$1\"
wywołane zostało przez funkcję \"$2\".
MySQL zgłosił błąd \"$3: $4\".\n",
"noconnect" => "{{SITENAME}} ma chwilowo problemy techniczne. Nie można połączyć się z serwerem bazy danych. Przepraszamy!",
"nodb" => "Nie można odnaleźć bazy danych $1",
"cachederror" => "Poniższy tekst strony jest kopią znajdującą się w pamięci podręcznej i może być już niekatualny.",
"readonly" => "Baza danych jest zablokowana",
"enterlockreason" => "Podaj powód zablokowania bazy oraz szacunkowy czas
jej odblokowania",
"readonlytext" => "Baza danych Wikipedii jest w tej chwili zablokowana
- nie można wprowadzać nowych artykułów ani modyfikować istniejących. Powodem
są prawdopodobnie czynności administracyjne. Po ich zakończeniu przywrócona
zostanie pełna funkcjonalność bazy.
Administrator, który zablokował bazę, podał następujące wyjaśnienie:
<p>$1",
"missingarticle" => "Oprogramowanie nie odnalazło tekstu strony,
która powinna się znajdować w bazie, tzn. strony \"$1\".
<p>Zazwyczaj zdarza się to, gdy wybrane zostanie łącze
do skasowanej strony, np. w starszej wersji innej ze stron.
<p>Inne okoliczności świadczyłyby o tym, że w oprogramowaniu jest błąd.
W takim przypadku zgłoś, proszę, ten fakt administratorowi
podając także powyższy adres.",
"internalerror" => "Błąd wewnętrzny",
"filecopyerror" => "Nie można skopiować pliku \"$1\" do \"$2\".",
"filerenameerror" => "Nie można zmienić nazwy pliku \"$1\" na \"$2\".",
"filedeleteerror" => "Nie można skasować pliku \"$1\".",
"filenotfound" => "Nie można znaleźć pliku \"$1\".",
"unexpected" => "Niespodziewana wartość: \"$1\"=\"$2\".",
"formerror" => "Błąd: nie można wysłać formularza",
"badarticleerror" => "Dla tej strony ta operacja nie może być wykonana.",
"cannotdelete" => "Nie można skasować podanej strony lub obrazka.",
"badtitle" => "Niepoprawny tytuł",
"badtitletext" => "Podano niepoprawny tytuł strony. Prawdopodobnie zawiera
znaki, których użycie jest zabronione lub jest pusty.",
"perfdisabled" => "By odciążyć serwer w godzinach szczytu czasowo zablokowaliśmy
wykonanie tej czynności. Wróć proszę i spróbuj jeszcze raz między 02.00 a 14.00
czasu UTC. Przepraszamy!",
"perfdisabledstub" => "Oto ostatnia zapisana wersja strony z $1",
"viewsource" => "Tekst źródłowy",
"protectedtext" => "Wyłączono możliwość edycji tej strony; istnieje kilka powodów
dla których jest to robione - zobacz [[{{ns:4}}:Strona_zabezpieczona]]

Tekst źródłowy strony można w dalszym ciągu podejrzeć i skopiować.",

# Login and logout pages
#
"logouttitle" => "Wylogowanie użytkownika",
"logouttext" => "Wylogowano Cię.
Możesz kontynuować pracę z Wikipedią jako niezarejestrowany użytkownik
albo zalogować się ponownie jako ten sam lub inny użytkownik.\n",

"welcomecreation" => "<h2>Witaj, $1!</h2><p>Właśnie utworzyliśmy dla Ciebie konto.
Nie zapomnij dostosować <i>preferencji</i>.",

"loginpagetitle" => "User login",
"yourname" => "Twój login",
"yourpassword" => "Twoje hasło",
"yourpasswordagain" => "Powtórz hasło",
"newusersonly" => " (tylko nowi użytkownicy)",
"remembermypassword" => "Pamiętaj moje hasło między sesjami.",
"loginproblem" => "<b>Są problemy z Twoim logowaniem.</b><br>Spróbuj ponownie!",
"alreadyloggedin" => "<font color=red><b>$1, jesteś już zalogowany!</b></font><br>\n",

"login" => "Zaloguj mnie",
"userlogin" => "Logowanie",
"logout" => "Wyloguj mnie",
"userlogout" => "Wylogowanie",
"notloggedin" => "Brak logowania",
"createaccount" => "Załóż nowe konto",
"badretype" => "Wprowadzone hasła różnią się między sobą.",
"userexists" => "Wybrana przez Ciebie nazwa użytkownika jest już zajęta. Wybierz, proszę, inną.",
"youremail" => "Twój e-mail*",
"yournick" => "Twój podpis",
"emailforlost" => "* Wpisanie adresu e-mailowego nie jest obowiązkowe. Pozwala to jednak innym użytkownikom skontaktowanie się z Tobą bez jawnej znajomości Twojego adresu. Przyda się także, gdy zapomnisz hasła - możesz wtedy poprosić o przesłanie nowego na podany adres.",
"loginerror" => "Błąd logowania",
"noname" => "To nie jest poprawna nazwa użytkownika.",
"loginsuccesstitle" => "Udane logowanie",
"loginsuccess" => "Zalogowano Cię do Wikipedii jako \"$1\".",
"nosuchuser" => "Nie ma użytkowniku nazywającego się \"$1\".
Sprawdź pisownię lub użyj poniższego formularza by utworzyć nowe konto.",
"wrongpassword" => "Podane przez Ciebie hasło jest nieprawidłowe. Spróbuj jeszcze raz.",
"mailmypassword" => "Wyślij mi nowe hasło",
"passwordremindertitle" => "{{SITENAME}} przypomina o haśle",
"passwordremindertext" => "Ktoś (prawdopodobnie Ty, spod adresu $1)
poprosił od nas o wysłanie nowego hasła dostępu do Wikipedii.
Aktualne hasło dla użytkownika \"$2\" to \"$3\". 
Najlepiej będzie jak zalogujesz się teraz i od razu zmienisz hasło.",
"noemail" => "W bazie nie ma adresu e-mailowego dla użytkownika \"$1\".",
"passwordsent" => "Nowe hasło zostało wysłane na adres e-mailowy użytkownika \"$1\"
Po otrzymaniu go zaloguj się ponownie.",

# Edit pages
#
"summary" => "Opis zmian",
"subject" => "Temat/nagłówek",
"minoredit" => "To jest drobna zmiana.",
"watchthis" => "Obserwuj",
"savearticle" => "Zapisz",
"preview" => "Podgląd",
"showpreview" => "Podgląd",
"blockedtitle" => "Użytkownik jest zablokowany",
"blockedtext" => "Twoje konto lub adres IP zostały zablokowane przez $1.
Podany powód to:<br>$2.<p>W celu wyjaśnienia sprawy zablokowania możesz się skontaktować z $1 lub innym
[[{{ns:4}}:Administratorzy|administratorem]].",
"newarticle" => "(Nowy)",
"newarticletext" => "Nie ma jeszcze artykułu o tym tytule. W poniższym polu można wpisać pierwszy jego fragment. Jeśli nie to było Twoim zamiarem, wciśnij po prostu ''Wstecz''.",
"anontalkpagetext" => "---- ''To jest strona dyskusyjna dla użytkowników
anonimowych - takich, którzy nie mają jeszcze swojego konta na Wikipedii lub
nie chcą go w tej chwili używać. By ich identyfikować używamy [[IP|numerów IP]].
Jeśli jesteś anonimowym użytkownikiem i wydaje Ci się, że zamieszczone tu komentarze
nie są skierowane do Ciebie, [[Specjalna:Userlogin|utwórz proszę konto i/albo zaloguj się]]
- dzięki temu unikniesz w przyszłości podobnych nieporozumień.'' ",
"noarticletext" => "(Nie ma jeszcze artykułu o tym tytule. Wybierz ''Edytuj'' by go rozpocząć.)",
"updated" => "(Zmodyfikowano)",
"note" => "<strong>Uwaga:</strong> ",
"previewnote" => "To jest tylko podgląd - artykuł nie został jeszcze zapisany!",
"previewconflict" => "Wersja podglądana odnosi się do tekstu
z górnego pola edycji. Tak będzie wyglądać strona jeśli zdecydujesz się ją zapisać.",
"editing" => "Edytujesz \"$1\"",
"sectionedit" => " (fragment)",
"commentedit" => " (komentarz)",
"editconflict" => "Konflikt edycji: $1",
"explainconflict" => "Ktoś zdążył wprowadzić swoją wersję artykułu
w trakcie Twojej edycji.
Górne pole edycji zawiera tekst strony aktualnie zapisany w bazie danych.
Twoje zmiany znajdują się w dolnym polu edycji.
By wprowadzić swoje zmiany musisz zmodyfikować tekst z górnego pola.
<b>Tylko</b> tekst z górnego pola będzie zapisany w bazie gdy wciśniesz
\"Zapisz\".\n<p>",
"yourtext" => "Twój tekst",
"storedversion" => "Zapisana wersja",
"editingold" => "<font color=\"red\"><strong>OSTRZEŻENIE: Edytujesz inną niż bieżąca wersję tej strony.
Jeśli zapiszesz ją wszystkie późniejsze zmiany zostaną skasowane.</strong></font>\n",
"yourdiff" => "Różnice",
"copyrightwarning" => "Proszę pamiętać o tym, że przyjmuje się, iż wszelki
wkład do Wikipedii jest udostępniany na zasadach <i>GNU Free Documentation License</i>
(szczegóły w $1).  <br>Jeśli nie chcesz, żeby Twój tekst był dowolnie zmieniany przez każdego i rozpowszechniany bez ograniczeń, nie umieszczaj go w Wikipedii.  Niniejszym jednocześnie oświadczasz, że ten tekst jest Twoim dziełem lub pochodzi z materiałów dostępnych na zasadach <i>public domain</i> albo
licencji <i>GNU Free Documentation License</i> lub kompatybilnej.
<br><strong>PROSZĘ NIE UŻYWAĆ BEZ POZWOLENIA MATERIAŁÓW OBJĘTYCH PRAWEM
AUTORSKIM!</strong>",
"longpagewarning" => "UWAGA: Ta strona ma $1 kilobajt-y/-ów; w przypadku niektórych
przeglądarek mogą wystąpić problemy w edycji stron mających więcej niż 32 kilobajty.
Jeśli to możliwe, spróbuj podzielić tekst na mniejsze części.",
"readonlywarning" => "UWAGA: Baza danych została chwilowo zablokowana do celów
administracyjnych. Nie można więc na razie zapisać nowej wersji
artykułu. Proponujemy przenieść jej tekst do prywatnego pliku
(wytnij/wklej) i zachować na później.",
"protectedpagewarning" => "UWAGA: Modyfikacja tej strony została zablokowana.
Mogą ją edytować jedynie użytkownicy z prawami administracyjnymi.
Upewnij się, że postępujesz zgodnie z
<a href='/wiki/{{ns:4}}:Blokowanie_stron'>zasadami dotyczącymi
zablokowanych stron</a>.",


# History pages
#
"revhistory" => "Historia modyfikacji",
"nohistory" => "Ta strona nie ma swojej historii edycji.",
"revnotfound" => "Wersja nie została odnaleziona",
"revnotfoundtext" => "Ta (starsza) wersja strony nie może zostać odnaleziona.
Sprawdź proszę URL użyty przez Ciebie by uzyskać dostęp do tej strony.\n",
"loadhist" => "Pobieranie historii tej strony",
"currentrev" => "Aktualna wersja",

"revisionasof" => "Wersja z dnia $1",
"cur" => "bież",
"next" => "następna",
"last" => "poprz",
"orig" => "oryginał",
"histlegend" => "Legenda: (bież) = różnice z wersją bieżącą,
(poprz) = różnice z wersją poprzedzającą, M = drobne zmiany",

# Diffs
#
"difference" => "(Różnice między wersjami)",
"loadingrev" => "pobieranie wersji w celu porównania",
"lineno" => "Linia $1:",
"editcurrent" => "Edytuj bieżącą wersję tej strony",

# Search results
#
"searchresults" => "Wyniki wyszukiwania",
"searchhelppage" => "{{ns:4}}:Przeszukiwanie",
"searchingwikipedia" => "Przeszukiwanie Wikipedii",
"searchresulttext" => "Aby dowiedzieć się więcej o przeszukiwaniu Wikipedii, zobacz $1.",
"searchquery" => "Dla zapytania \"$1\"",
"badquery" => "Źle sformułowane zapytanie",
"badquerytext" => "Nie można zrealizować Twojego zapytania.
Prawdopodobna przyczyna to obecność słowa krótszego niż trzyliterowe.
Spróbuj, proszę, innego zapytania.",
"matchtotals" => "Zapytanie \"$1\", liczba znalezionych tytułów: $2,
liczba znalezionych artykułów: $3.",
"nogomatch" => "Nie istnieją strony o dokładnie takim tytule. Spróbuj pełnego przeszukiwania. ",
"titlematches" => "Znaleziono w tytułach:",
"notitlematches" => "Nie znaleziono w tytułach",
"textmatches" => "Znaleziono na stronach:",
"notextmatches" => "Nie znaleziono w tekście stron",
"prevn" => "poprzednie $1",
"nextn" => "następne $1",
"viewprevnext" => "Zobacz ($1) ($2) ($3).",
"showingresults" => "Oto lista <b>$1</b> pozycji, poczynając od numeru <b>$2</b>.",
"showingresultsnum" => "Oto lista <b>$3</b> pozycji, poczynając od numeru <b>$2</b>.",
"nonefound" => "<strong>Uwaga</strong>: brak rezultatów wyszukiwania
spowodowany jest bardzo często szukaniem najpopularniejszych słów, takich jak
\"jest\" czy \"nie\", które nie są indeksowane, albo z powodu podania w
zapytaniu więcej niż jednego słowa (na liście odnalezionych stron znajdą się
tylko te, które zawierają wszystkie podane słowa).",
"powersearch" => "Szukaj", 
"powersearchtext" => "
Szukaj w przestrzeniach nazw :<br>
$1<br>
$2 Pokaż przekierowania   Szukany tekst $3 $9",
"searchdisabled" => "<p>Ze względu na duże obciążenie serwera wyszukiwanie
w treści artykułów zostało czasowo wyłączone; mamy nadzieję, że 
po zbliżającej się modyfikacji sprzętu możliwość ta zostanie przywrócona.
W międzyczasie polecamy wyszukiwanie za pomocą Google:</p>

",
"googlesearch" => "<!-- SiteSearch Google -->
<FORM method=GET action=\"http://www.google.com/search\">
<TABLE bgcolor=\"#FFFFFF\"><tr><td>
<A HREF=\"http://www.google.com/\">
<IMG SRC=\"http://www.google.com/logos/Logo_40wht.gif\"
border=\"0\" ALT=\"Google\"></A>
</td>
<td>
<INPUT TYPE=text name=q size=31 maxlength=255 value=\"$1\">
<INPUT type=submit name=btnG VALUE=\"Google Search\">
<font size=-1>
<input type=hidden name=domains value=\"{$wgServer}\"><br><input type=radio
name=sitesearch value=\"\"> WWW <input type=radio name=sitesearch
value=\"{$wgServer}\" checked> {$wgServer} <br>
<input type='hidden' name='ie' value='$2'>
<input type='hidden' name='oe' value='$2'>
</font>
</td></tr></TABLE>
</FORM>
<!-- SiteSearch Google -->
",
"blanknamespace" => "(Główna)",


# Preferences page
#
"preferences" => "Preferencje",
"prefsnologin" => "Brak logowania",
"prefsnologintext" => "Musisz się <a href=\"" .
  wfLocalUrl( "Specjalna:Userlogin" ) . "\">zalogować</a>
przez zmianą swoich preferencji.",
"prefslogintext" => "Zalogowano Cię jako \"$1\". Twój numer identyfikacyjny to $2.

Zobacz [[{{ns:4}}:Preferencje]], aby poznać znaczenie poszczególnych opcji.",
"prefsreset" => "Preferencje domyślne zostały odtworzone.",
"qbsettings" => "Pasek szybkiego dostępu", 
"changepassword" => "Zmiana hasła",
"skin" => "Skórka",
"math" => "Prezentacja wzorów matematycznych", 
"math_failure" => "Parser nie umiał rozpoznać", 
"math_unknown_error" => "nieznany błąd", 
"math_unknown_function" => "nieznana funkcja ", 
"math_lexing_error" => "błąd leksera", 
"math_syntax_error" => "błąd składni", 
"saveprefs" => "Zapisz preferencje",
"resetprefs" => "Preferencje domyślne",
"oldpassword" => "Stare hasło",
"newpassword" => "Nowe hasło",
"retypenew" => "Powtórz nowe hasło",
"textboxsize" => "Wymiary pola edycji",
"rows" => "Wiersze",
"columns" => "Kolumny",
"searchresultshead" => "Ustawienia wyszukiwarki",
"resultsperpage" => "Liczba wyników na stronie",
"contextlines" => "Pierwsze wiersze artykułu",
"contextchars" => "Litery kontekstu w linijce",
"stubthreshold" => "Maksymalny rozmiar artykułu prowizorycznego",
"recentchangescount" => "Liczba pozycji na liście ostatnich zmian",
"savedprefs" => "Twoje preferencje zostały zapisane.",
"timezonetext" => "Podaj liczbę godzin różnicy między Twoim czasem,
a czasem uniwersalnym (UTC). Np. dla Polski jest to liczba \"2\" (czas letni)
lub \"1\" (czas zimowy).",
"localtime" => "Twój czas",
"timezoneoffset" => "Różnica",
"servertime" => "Aktualny czas serwera",
"guesstimezone" => "Pobierz z przeglądarki",
"emailflag" => "Nie chcę otrzymywać e-maili od innych użytkowników",
"defaultns" => "Przeszukuj następujące przestrzenie nazw domyślnie:",

# Recent changes
#
"changes" => "zmian-a/-y",
"recentchanges" => "Ostatnie zmiany",
"recentchangestext" => "Ta strona przedstawia historię ostatnich zmian w polskiej Wikipedii.

[[{{ns:4}}:Powitanie nowicjuszy|Witaj]]! Jeśli jesteś tu po raz pierwszy, zapoznaj się, proszę, z tymi stronami: [[{{ns:4}}:FAQ|{{SITENAME}} FAQ]], [[{{ns:4}}:Zasady i wskazówki|polityka Wikipedii]] (a zwłaszcza [[{{ns:4}}:Nazewnictwo|konwencje nazywania stron]], [[{{ns:4}}:Neutralny punkt widzenia|neutralny punkt widzenia]]) oraz [[{{ns:4}}:Najczęstsze nieporozumienia|najczęstsze nieporozumienia]].

Jeśli zależy Ci na dalszym rozwoju Wikipedii, nie dodawaj materiałów zastrzeżonych prawami autorskimi. Złamanie tej zasady mogłyby narazić projekt Wikipedii na poważne konsekwencje prawne.  Zobacz także [http://meta.wikipedia.org/wiki/Special:Recentchanges ostatnie zmiany na stronach dyskusyjnych projektu].",
"rcloaderr" => "Ładuję ostatnie zmiany",
"rcnote" => "To ostatnie <strong>$1</strong> zmian dokonanych na Wikipedii w ciągu ostatnich <strong>$2</strong> dni.",
"rcnotefrom" => "Poniżej pokazano zmiany dokonane po <b>$2</b> (nie więcej niż <b>$1</b> pozycji).",
"rclistfrom" => "Pokaż nowe zmiany począwszy od $1",
"rclinks" => "Wyświetl ostatnie $1 zmian w ciągu ostatnich $2 dni.",
"rchide" => "in $4 form; $1 drobnych zmian; $2 innych przestrzeni nazw; $3 wielokrotnych edycji.",
"diff" => "różn",
"hist" => "hist",
"hide" => "schowaj",
"show" => "pokaż",
"tableform" => "tabelka",
"listform" => "lista",
"nchanges" => "$1 zmian",
"minoreditletter" => "M",
"newpageletter" => "N",

# Upload
#
"upload" => "Prześlij plik",
"uploadbtn" => "Prześlij plik",
"uploadlink" => "Prześlij obrazki",
"reupload" => "Prześlij ponownie",
"reuploaddesc" => "Wróć do formularza wysyłki.",
"uploadnologin" => "Brak logowania",
"uploadnologintext" => "Musisz się <a href=\"" .
  wfLocalUrl( "Specjalna:Userlogin" ) . "\">zalogować</a>
przed przesłaniem pików.",
"uploadfile" => "Prześlij plik",
"uploaderror" => "Błąd przesyłki",
"uploadtext" => "<strong>STOP!</strong> Zanim prześlesz plik,
przeczytaj <a href=\"" . wfLocalUrlE( "{{ns:4}}:Zasady_dołączania_plików" ) .
"\">zasady dołączania plików</a> i upewnij się, że przesyłając pozostaniesz z
nimi w zgodzie.
<p>Jeśli chcesz przejrzeć lub przeszukać dotychczas przesłane pliki,
przejdź do <a href=\"" . wfLocalUrlE( "Specjalna:Imagelist" ) .
"\">listy dołączonych plików</a>.
Wszystkie przesyłki i skasowania są odnotowane na
specjalnych wykazach (<a href=\"" .  wfLocalUrlE( "{{ns:4}}:Dołączone" ) .
"\">dołączone</a>, <a href=\"" . wfLocalUrlE( "{{ns:4}}:Usunięte" ) .
"\">usunięte</a>).
<p>By przesłać nowy plik mający zilustrować Twój artykuł skorzystaj
z poniższego formularza.
W przypadku większości przeglądarek zobaczysz przycisk <i>Browse...</i>
albo <i>Przeglądaj...</i>, który umożliwi Ci otworzenie standardowego
okienka wyboru pliku. Wybranie pliku spowoduje umieszczenie jego nazwy
w polu tekstowym obok przycisku.
Musisz także zaznaczając odpowiednie pole, potwierdzić, że przesyłając
plik nie naruszasz niczyich praw autorskich.
Przesyłka rozpocznie się po naciśnięciu <i>Prześlij plik</i>.
Może zająć kilka chwil, zwłaszcza jeśli masz wolne połączenie z internetem.
<p>Preferowane formaty to JPEG dla zdjęć fotograficznych, PNG dla rysunków
i obrazków o charakterze ikon oraz OGG dla dźwięków.
Aby uniknąć nieporozumień nadawaj plikom nazwy związane z zawartością.
Aby umieścić obrazek w artykule, użyj linku w postaci
<b>[[grafika:obrazek.jpg]]</b> lub <b>[[grafika:obrazek.png|opcjonalny tekst]]</b>.
Dla plików dźwiękowych link będzie miał postać <b>[[media:file.ogg]]</b>.
<p>Pamiętaj, proszę, że tak jak w przypadku zwykłych stron Wikipedii,
inni użytkownicy mogą edytować lub kasować przesłane przez Ciebie pliki,
jeśli stwierdzą, że to będzie lepiej służyć całemu projektowi.
Twoje prawo do przesyłania może zostać Ci odebrane, jeśli nadużyjesz systemu.",
"uploadlog" => "Wykaz przesyłek",
"uploadlogpage" => "Dołączone",
"uploadlogpagetext" => "Oto lista ostatnio przesłanych plików.
Wszystkie czasy odnoszą się do strefy czasu uniwersalnego (UTC).
<ul>
</ul>
",
"filename" => "Plik",
"filedesc" => "Opis",
"affirmation" => "Potwierdzam, że właściciel praw autorskich do tego pliku
zgadza się udzielić licencji zgodnie z $1.",
"copyrightpage" => "{{ns:4}}:Prawa_autorskie",
"copyrightpagename" => "prawami autorskimi Wikipedii",
"uploadedfiles" => "Przesłane pliki",
"noaffirmation" => "Musisz potwierdzić, że Twoja przesyłka nie narusza żadnych
praw autorskich.",
"ignorewarning" => "Zignoruj ostrzeżenie i prześlij plik.",
"minlength" => "Nazwa obrazku musi mieć co najmniej trzy litery.",
"badfilename" => "Nazwę obrazku zmieniona na \"$1\".",
"badfiletype" => "\".$1\" nie jest zalecanym formatem pliku.",
"largefile" => "Zalecane jest aby rozmiar pliku z obrazkiem nie był większy niż 100 kilobajtów.",
"successfulupload" => "Wysyłka powiodła się",
"fileuploaded" => "Plik \"$1\" został pomyślnie przesłany.
Przejdź, proszę, do strony opisu pliku ($2) i podaj dotyczące go informacje
takie jak: pochodzenie pliku, kiedy i przez kogo został utworzony
i cokolwiek co wiesz o pliku, a wydaje Ci się ważne.",
"uploadwarning" => "Ostrzeżenie o przesyłce",
"savefile" => "Zapisz plik",
"uploadedimage" => "przesłano \"$1\"",
"uploaddisabled" => "Przepraszamy! Możliwość przesyłania plików na ten serwer została wyłączona.",

# Image list
#
"imagelist" => "Lista plików",
"imagelisttext" => "To jest lista $1 plików posortowanych $2.",
"getimagelist" => "pobieranie listy plików",
"ilshowmatch" => "Pokaż wszystkie pliki o takiej samej nazwie",
"ilsubmit" => "Szukaj",
"showlast" => "Pokaż ostatnie $1 plików posortowane $2.",
"all" => "wszystkie",
"byname" => "według nazwy",
"bydate" => "według daty",
"bysize" => "według rozmiaru",
"imgdelete" => "usuń",
"imgdesc" => "opisz",
"imglegend" => "Legenda: (opisz) = pokaż/edytuj opis pliku.",
"imghistory" => "Historia pliku",
"revertimg" => "przywróć",
"deleteimg" => "usuń",
"deleteimgcompletely" => "usuń",
"imghistlegend" => "Legenda: (bież) = to jest obecny plik, (usuń) = usuń
tę starszą wersję, (przywróć) = przywróć tę starszą wersję.
<br><i>Kliknij na datę aby zobaczyć jakie pliki przesłano tego dnia</i>.",
"imagelinks" => "Linki do pliku",
"linkstoimage" => "Oto strony odwołujące się do tego pliku:",
"nolinkstoimage" => "Żadna strona nie odwołuje się do tego pliku.",

# Statistics
#
"statistics" => "Statystyka",
"sitestats" => "Statystyka artykułów",
"userstats" => "Statystyka użytkowników",
"sitestatstext" => "W bazie danych jest w sumie <b>$1</b> stron.
Ta liczba uwzględnia strony <i>Dyskusji</i>, strony na temat samej Wikipedii,
strony typu <i>stub</i> (prowizoryczne), strony przekierowujące, oraz inne, które trudno
uznać za artykuły. Wyłączając powyższe, jest prawdopodobnie <b>$2</b> stron, które można uznać
za artykuły.<p>
Było w sumie <b>$3</b> odwiedzin oraz <b>$4</b> edycji od kiedy dokonano
upgrade'u oprogramowania (22 listopada 2002).
Daje to średnio <b>$5</b> edycji na jedną stronę i <b>$6</b> odwiedzin na jedną edycję.",
"userstatstext" => "Jest <b>$1</b> zarejestrowanych użytkowników.
Spośród nich <b>$2</b> ma status administratora (zobacz $3).",

# Maintenance Page
#
"maintenance" => "Prosta administracja",
"maintnancepagetext" => "Na tej stronie zgrupowano kilka użytecznych narzędzi
pomagających w prostej administracji. Niektóre z nich obciążają bazę danych, proszę
więc, by ich nie nadużywać.",
"maintenancebacklink" => "Powrót do strony prostej administracji",
"disambiguations" => "Strony ujednoznaczniające",
"disambiguationspage" => "{{ns:4}}:Strony_ujednoznaczniające",
"disambiguationstext" => "Poniższe artykuły odwołują się do <i>stron
ujednoznaczniających</i>, a powinny odwoływać się bezpośrednio do hasła
związanego z treścią artykułu.<br>Strona uznawana jest za ujednoznaczniającą
jeśli odwołuje się do niej $1.<br>Linki z innych przestrzeni nazw <i>nie</i>
zostały tu uwzględnione.",
"doubleredirects" => "Podwójne przekierowania",
"doubleredirectstext" => "<b>Uwaga:</b> Na tej liście mogą znajdować się
przekierowania pozorne. Oznacza to, że poniżej pierwszej linii artykułu,
zawierającej \"#REDIRECT ...\", może znajdować się dodatkowy tekst.<br>Każdy
wiersz listy zawiera odwołania do pierwszego i drugiego przekierowania oraz
pierwszą linię tekstu drugiego przekierowania. Umożliwia to w większości
przypadków odnalezienie właściwego artykułu, do którego powinno się
przekierowywać.",
"brokenredirects" => "Zerwane przekierowania",
"brokenredirectstext" => "Poniższe przekierowania wskazują na nieistniejące artykuły.",
"selflinks" => "Strony zawierające odwołania do siebie samych",
"selflinkstext" => "Poniższe strony zawierają odnośniki do samych siebie
(co nie powinno mieć miejsca).",

"mispeelings" => "Strony z błędami pisowni",
"mispeelingstext" => "Poniższe strony zawierają najczęstsze błędy
pisowni (ich listę można znaleźć w $1). Poprawna pisownia może być podana obok w
nawiasach.", 
"mispeelingspage" => "Lista najczęstszych błędów pisowni",
"missinglanguagelinks" => "Brakujące odnośniki do innych wersji językowych",
"missinglanguagelinksbutton" => "Znajdź brakujące odnośniki, wersja",
"missinglanguagelinkstext" => "Dla wielu artykułów istnieje wersja $1.
Artykuły umieszczone na poniższej liście <i>nie</i> odnoszą się do swojego
odpowiednika w tym języku. Na tej liście <i>pominięto</i> podstrony oraz przekierowania.",

# Miscellaneous special pages
#
"orphans" => "Porzucone strony",
"lonelypages" => "Porzucone strony",
"unusedimages" => "Nie używane pliki",
"popularpages" => "Najpopularniejsze strony",
"nviews" => "odwiedzono $1 razy",
"wantedpages" => "Najpotrzebniejsze strony",
"nlinks" => "$1 linków",
"allpages" => "Wszystkie strony",
"randompage" => "Losuj stronę",
"shortpages" => "Najkrótsze strony",
"longpages" => "Najdłuższe strony",
"listusers" => "Lista użytkowników",
"specialpages" => "Strony specjalne",
"spheading" => "Strony specjalne",
"sysopspheading" => "Strony specjalne tylko dla użytkowników z prawami Administratora",
"developerspheading" => "Strony specjalne tylko dla użytkowników z prawami Programisty",
"protectpage" => "Zabezpiecz stronę",
"recentchangeslinked" => "Zmiany w dolinkowanych",
"rclsub" => "(dla stron dolinkowanych do \"$1\")",
"debug" => "Odpluskwianie",
"newpages" => "Nowe strony",
"ancientpages" => "Najstarsze strony",
"movethispage" => "Przenieś",
"unusedimagestext" => "<p>Pamiętaj, proszę, że inne witryny,
np. Wikipedie w innych językach, mogą odwoływać się do tych plików
używając bezpośrednio URL. Dlatego też niektóre z plików mogą się znajdować
na tej liście mimo, że żadna strona tej Wikipedii nie odwołuje się do nich.",
"booksources" => "Książki",
"booksourcetext" => "Oto lista linków do innych witryn,
które pośredniczą w sprzedaży nowych i używanych książek i mogą podać
informacje o książkach, których szukasz.
{{SITENAME}} nie jest stowarzyszona z żadnym ze sprzedawców,

a ta lista nie powinna być interpretowana jako świadectwo udziału w zyskach.",
"alphaindexline" => "$1 --> $2",

# Email this user
#
"mailnologin" => "Brak adresu",
"mailnologintext" => "Musisz się <a href=\"" .
  wfLocalUrl( "Specjalna:Userlogin" ) . "\">zalogować</a>
i mieć wpisany aktualny adres e-mailowy w swoich <a href=\"" .
  wfLocalUrl( "Specjalna:Preferences" ) . "\">preferencjach</a>,
aby móc wysłać e-mail do innych użytkowników.",
"emailuser" => "Wyślij e-mail do tego użytkownika",
"emailpage" => "Wyślij e-mail do użytkownika",
"emailpagetext" => "Jeśli ten użytkownik wpisał poprawny adres e-mailowy
w swoich preferencjach, to poniższy formularz umożliwi Ci wysłanie jednej wiadomości.
Adres e-mailowy, który został przez Ciebie wprowadzony w Twoich preferencjach
pojawi się w polu \"Od\"; dzięki temu odbiorca będzie mógł Ci odpowiedzieć.",
"noemailtitle" => "Brak adresu e-mailowego",
"noemailtext" => "Ten użytkownik nie podał poprawnego adresu e-mailowego,
albo zadecydował, że nie chce otrzymywać e-maili od innych użytkowników.",
"emailfrom" => "Od",
"emailto" => "Do",
"emailsubject" => "Temat",
"emailmessage" => "Wiadomość",
"emailsend" => "Wyślij",
"emailsent" => "Wiadomość została wysłana",
"emailsenttext" => "Twoja wiadomość została wysłana.",

# Watchlist
#
"watchlist" => "Obserwowane",
"watchlistsub" => "(dla użytkownika \"$1\")",
"nowatchlist" => "Nie ma żadnych pozycji na liście obserwowanych przez Ciebie stron.",
"watchnologin" => "Brak logowania",
"watchnologintext" => "Musisz się <a href=\"" .
  wfLocalUrl( "Specjalna:Userlogin" ) . "\">zalogować</a>
przed modyfikacją listy obserwowanych artykułów.",
"addedwatch" => "Dodana do listy obserwowanych",
"addedwatchtext" => "Strona \"$1\" została dodana do Twojej <a href=\"" .
  wfLocalUrl( "Specjalna:Watchlist" ) . "\">listy obserwowanych</a>.
Na tej liście znajdzie się rejestr przyszłych zmian tej strony i stowarzyszonej z nią strony Dyskusji,
a nazwa samej strony zostanie <b>wytłuszczona</b> na <a href=\"" .
  wfLocalUrl( "Specjalna:Recentchanges" ) . "\">liście ostatnich zmian</a> aby
łatwiej było Ci sam fakt zmiany zauważyć.</p>

<p>Jeśli chcesz usunąć stronę ze swojej listy obserwowanych, kliknij na
\"Przestań obserwować\".",
"removedwatch" => "Usunięto z listy obserwowanych",
"removedwatchtext" => "Strona \"$1\" została usunięta z Twojej listy obserwowanych.",
"watchthispage" => "Obserwuj",
"unwatchthispage" => "Przestań obserwować",
"notanarticle" => "To nie artykuł",


"watchnochange" => "Żadna z obserwowanych stron nie była edytowana w podanym okresie.",
"watchdetails" => "(Liczba obserwowanych przez Ciebie stron: $1, nie licząc stron dyskusji;
liczba stron edytowanych od ostatniej cezury: $2;
$3...
<a href='$4'>pokaż i edytuj pełną listę</a>.)",
"watchmethod-recent" => "poszukiwanie ostatnich zmian wśród obserwowanych stron",
"watchmethod-list" => "poszukiwanie obserwowanych stron wśród ostatnich zmian",
"removechecked" => "Usuń zaznaczone pozycje z listy obserwowanych",
"watcheditlist" => "Oto alfabetyczna lista obserwowanych stron.
Zaznacz, które z nich mamy usunąć z listy i kliknij przycisk

<i>Usuń...</i> znajdujący się na dole strony.",
"removingchecked" => "Usuwamy zaznaczone pozycje z listy obserwowanych...",
"couldntremove" => "Nie można było usunąć pozycji '$1'...",
"iteminvalidname" => "Problem z pozycją '$1', niepoprawna nazwa...",
"wlnote" => "Poniżej pokazano zmiany dokonane w ciągu ostatnich <b>$2</b> godzin (maksymalna liczba pozycji: $1).",

# Delete/protect/revert
#
"deletepage" => "Usuń stronę",
"confirm" => "Potwierdź",
"excontent" => "Zawartość strony",
"exbeforeblank" => "Poprzednia zawartość pustej strony",
"exblank" => "Strona była pusta",
"confirmdelete" => "Potwierdź usunięcie",
"deletesub" => "(Usuwanie \"$1\")",
"historywarning" => "Uwaga: Strona, którą chcesz skasować ma starsze wersje; oto ",
"confirmdeletetext" => "Zamierzasz trwale usunąć stronę
lub plik z bazy danych razem z dotyczącą ich historią.
Potwierdź, proszę, swoje zamiary, tzn., że rozumiesz konsekwencje,
i że robisz to w zgodzie z
[[{{ns:4}}:Zasady i wskazówki|zasadami Wikipedii]].",
"confirmcheck" => "Tak, naprawdę chcę usunąć.",
"actioncomplete" => "Operacja wykonana",

"deletedtext" => "Usunięto \"$1\".
Rejestr ostatnio dokonanych kasowań możesz obejrzeć tutaj: $2.",
"deletedarticle" => "usunięto \"$1\"",
"dellogpage" => "Usunięte",
"dellogpagetext" => "To jest lista ostatnio wykonanych kasowań.
Podane czasy odnoszą się do strefy czasu uniwersalnego (UTC).
<ul>
</ul>
",
"deletionlog" => "rejestr usunięć",
"reverted" => "Przywrócono starszą wersję",
"deletecomment" => "Powód usunięcia",
"imagereverted" => "Przywrócenie wcześniejszej wersji powiodło się.",
"rollback" => "Cofnij edycję", 
"rollbacklink" => "cofnij",
"rollbackfailed" => "Nie udało się cofnąć zmiany",
"cantrollback" => "Nie można cofnąć edycji; jest tylko jedna wersja tej strony.",
"alreadyrolled" => "Nie można cofnąć ostatniej zmiany strony [[$1]],
której autorem jest [[Wikipedysta:$2|$2]] ([[Dyskusja_wikipedysty:$2|Dyskusja]]).
Ktoś inny zdążył już to zrobić lub wprowadził własne poprawki do treści strony.
Autorem ostatniej zmiany jest teraz [[Wikipedysta:$3|$3]] ([[Dyskusja_wikipedysty:$3|Dyskusja]]).",
# only shown if there is an edit comment
"editcomment" => "Opisano ją następująco: \"<i>$1</i>\".",
"revertpage" => "Przywrócono przedostatnią wersję, jej autor to $1", 

# Undelete
#
"undelete" => "Odtwórz skasowaną stronę",
"undeletepage" => "Odtwarzanie skasowanych stron",
"undeletepagetext" => "Poniższe strony zostały skasowane, ale ich kopia wciąż
znajduje się w archiwum.<br><b>Uwaga:</b> archiwum co jakiś czas także jest kasowane!",
"undeletearticle" => "Odtwórz skasowaną stronę",
"undeleterevisions" => "Liczba zarchiwizowanych wersji: $1",
"undeletehistory" => "Odtworzenie strony spowoduje przywrócenie także jej
wszystkich poprzednich wersji. Jeśli od czasu skasowania ktoś utworzył nową stronę
o tej nazwie, odtwarzane wersje znajdą się w jej historii, a obecna wersja
pozostanie bez zmian.",
"undeleterevision" => "Skasowano wersję z $1",
"undeletebtn" => "Odtwórz!",
"undeletedarticle" => "odtworzono \"$1\"",
"undeletedtext" => "Pomyślnie odtworzono stronę [[$1]].
Zobacz [[{{ns:4}}:Usunięte]], jeśli chcesz przejrzeć rejestr ostatnio
skasowanych i odtworzonych stron.",

# Contributions
#
"contributions" => "Wkład użytkownika",
"mycontris" => "Moje edycje",
"contribsub" => "Dla użytkownika $1",
"nocontribs" => "Brak zmian odpowiadających tym kryteriom.",

"ucnote" => "Oto lista ostatnich <b>$1</b> zmian dokonanych przez
użytkownika w ciągu ostatnich <b>$2</b> dni.",
"uclinks" => "Zobacz ostatnie $1 zmian; zobacz ostatnie $2 dni.",
"uctop" => " (jako ostatnia)",

# What links here
#
"whatlinkshere" => "Linkujące",
"notargettitle" => "Wskazywana strona nie istnieje",
"notargettext" => "Nie podano strony albo użytkownika, dla których
ta operacja ma być wykonana.",
"linklistsub" => "(Lista linków)",
"linkshere" => "Do tej strony odwołują się następujące inne strony:",
"nolinkshere" => "Do tej strony nie odwołuje się żadna inna.",
"isredirect" => "strona przekierowująca",

# Block/unblock IP
#
"blockip" => "Zablokuj adres IP",
"blockiptext" => "Użyj poniższego formularza aby zablokować prawo
zapisu spod określonego adresu IP.
Powinno się to robić jedynie by zapobiec wandalizmowi, a zarazem
w zgodzie z [[{{ns:4}}:Zasady i wskazówki|zasadami Wikipedii]].
Podaj powód (np. umieszczając nazwy stron, na których dopuszczono
się wandalizmu).",
"ipaddress" => "Adres IP",
"ipbreason" => "Powód",
"ipbsubmit" => "Zablokuj ten adres",
"badipaddress" => "Adres IP jest źle utworzony.",
"noblockreason" => "Musisz podać powód blokady.",
"blockipsuccesssub" => "Zablokowanie powiodło się",
"blockipsuccesstext" => "Adres IP \"$1\" został zablokowany.
<br>Przejdź do [[Specjalna:Ipblocklist|Listy zablokowanych adresów IP]] by przejrzeć blokady.",
"unblockip" => "Odblokuj adres IP",
"unblockiptext" => "Użyj poniższego formularza by przywrócić prawa zapisu
dla poprzednio zablokowanego adresu IP.",
"ipusubmit" => "Odblokuj ten adres",
"ipusuccess" => "Adress IP \"$1\" został odblokowany",
"ipblocklist" => "Lista zablokowanych adresów IP",
"blocklistline" => "$1, $2 zablokował $3",
"blocklink" => "zablokuj",
"unblocklink" => "odblokuj",
"contribslink" => "wkład",

# Developer tools
#
"lockdb" => "Zablokuj bazę danych",
"unlockdb" => "Odblokuj bazę danych",
"lockdbtext" => "Zablokowanie bazy danych uniemożliwi wszystkim użytkownikom
edycję stron, zmianę preferencji, edycję list obserwowanych artykułów oraz inne
czynności wymagające dostępu do bazy danych.
Potwierdź, proszę, że to jest zgodne z Twoimi zamiarami, i że odblokujesz
bazę danych, gdy tylko zakończysz zadania administracyjne.",
"unlockdbtext" => "Odblokowanie bazy danych umożliwi wszystkim użytkownikom
edycję stron, zmianę preferencji, edycję list
obserwowanych artykułów oraz inne czynności związane ze zmianami w bazie danych.
Potwierdź, proszę, że to jest zgodne z Twoimi zamiarami.",
"lockconfirm" => "Tak, naprawdę chcę zablokować bazę danych.",
"unlockconfirm" => "Tak, naprawdę chcę odblokować bazę danych.",
"lockbtn" => "Zablokuj bazę danych",
"unlockbtn" => "Odblokuj bazę danych",
"locknoconfirm" => "Nie zaznaczyłeś pola potwierdzenia.",
"lockdbsuccesssub" => "Baza danych została pomyślnie zablokowana",
"unlockdbsuccesssub" => "Blokada bazy danych usunięta",
"lockdbsuccesstext" => "Baza danych Wikipedii została zablokowana.
<br>Pamiętaj usunąć blokadę po zakończeniu spraw administracyjnych.",
"unlockdbsuccesstext" => "Baza danych Wikipedii została odblokowana.",

# SQL query
#
"asksql" => "Zapytanie SQL",
"asksqltext" => "Użyj poniższego formularza by wysłać bezpośrednie zapytanie
do bazy danych Wikipedii.
Do ograniczania literałów łańcuchowych używaj pojedynczych cudzysłowów ('tak jak tu').
Twoje zapytanie może poważnie obciążyć serwer, więc używaj tej możliwości
z rozwagą.",
"sqlislogged" => "Przypominamy, że wszystkie zapytania są logowane!",
"sqlquery" => "Podaj zapytanie",
"querybtn" => "Wyślij zapytanie",
"selectonly" => "Zapytania inne niż \"SELECT\" są zastrzeżone tylko dla
użytkowników o statusie Programisty.",
"querysuccessful" => "Zapytanie zakończone sukcesem",

# Move page
#
"movepage" => "Przenieś stronę",
"movepagetext" => "Za pomocą poniższego formularza zmienisz nazwę strony,
przenosząc jednocześnie jej historę.
Pod starym tytułem zostanie umieszczona strona przekierowująca.
Linki do starego tytułu pozostaną niezmienione.
[[Specjalna:Maintenance|Upewnij się]], że uwzględniasz podwójne
lub zerwane przekierowania. Odpowiadasz za to, żeby linki odnosiły
się do właściwych artykułów!

Strona '''nie''' będzie przeniesiona jeśli:
*jest pusta i nigdy nie była edytowana
*jest stroną przekierowującą
*strona o nowej nazwie już istnieje

<b>UWAGA!</b>
Może to być drastyczna lub nieprzewidywalna zmiana w przypadku popularnych stron;
upewnij się co do konsekwencji tej operacji zanim się na nią zdecydujesz.",
"movepagetalktext" => "Odpowiednia strona dyskusji, jeśli istnieje, będzie
przeniesiona automatycznie, pod warunkiem, że:
*nie przenosisz strony do innej przestrzeni nazw
*nie istnieje strona dyskusji o nowej nazwie
W takich przypadkach tekst dyskusji trzeba przenieść, i ewentualnie połączyć
z istniejącym, ręcznie.
Możesz też zrezygnować z przeniesienia dyskusji (poniższy <i>checkbox</i>).",
"movearticle" => "Przenieś stronę",
"movenologin" => "Brak logowania",
"movenologintext" => "Musisz być zarejestrowanym i <a href=\"" .
  wfLocalUrl( "Specjalna:Userlogin" ) . "\">zalogowanym</a>
użytkownikiem aby móc przenieść stronę.",
"newtitle" => "Nowy tytuł",
"movepagebtn" => "Przenieś stronę",
"pagemovedsub" => "Przeniesienie powiodło się",
"pagemovedtext" => "Strona \"[[$1]]\" została przeniesiona do \"[[$2]]\".",
"articleexists" => "Strona o podanej nazwie już istnieje albo
wybrana przez Ciebie nazwa nie jest poprawna.
Wybierz, proszę, nową nazwę.",
"movedto" => "przeniesiono do",
"movetalk" => "Przenieś także stronę <i>Dyskusji</i>, jeśli to możliwe.",
"talkpagemoved" => "Odpowiednia strona z <i>Dyskusją</i> także została przeniesiona.",
"talkpagenotmoved" => "Odpowiednia strona z <i>Dyskusją</i> <strong>nie</strong> została przeniesiona.",

);

class LanguagePl extends LanguageUtf8 {

        function getDefaultUserOptions () {
                $opt = Language::getDefaultUserOptions();
                return $opt;
        }
	
        function getNamespaces() {
                global $wgNamespaceNamesPl;
                return $wgNamespaceNamesPl;
        }

        function getNsText( $index ) {
                global $wgNamespaceNamesPl;
                return $wgNamespaceNamesPl[$index];
        }

        function getNsIndex( $text ) {
                global $wgNamespaceNamesPl;

                foreach ( $wgNamespaceNamesPl as $i => $n ) {
                        if ( 0 == strcasecmp( $n, $text ) ) { return $i; }
                }
                return false;
        }

        function getQuickbarSettings() {
                global $wgQuickbarSettingsPl;
                return $wgQuickbarSettingsPl;
        }

        function getSkinNames() {
                global $wgSkinNamesPl;
                return $wgSkinNamesPl;
        }

        function getMathNames() { 
                global $wgMathNamesPl; 
                return $wgMathNamesPl; 
    }

        function getUserToggles() {
                global $wgUserTogglesPl;
                return $wgUserTogglesPl;
        }

        function getMonthName( $key )
        {
                global $wgMonthNamesPl;
                return $wgMonthNamesPl[$key-1];
        }

        function getMonthNameGen( $key )
        {
                global $wgMonthNamesGenPl;
                return $wgMonthNamesGenPl[$key-1];
        }

        function getMonthAbbreviation( $key )
        {
                global $wgMonthAbbreviationsPl;
                return $wgMonthAbbreviationsPl[$key-1];
        }

        function getWeekdayName( $key )
        {
                global $wgWeekdayNamesPl;
                return $wgWeekdayNamesPl[$key-1];
        }

        function userAdjust( $ts )
        {
                global $wgUser;

                $diff = $wgUser->getOption( "timecorrection" );
                if ( ! $diff ) { $diff = 0; }
                if ( 0 == $diff ) { return $ts; }

                $t = mktime( ( (int)substr( $ts, 8, 2) ) + $diff,
                  (int)substr( $ts, 10, 2 ), (int)substr( $ts, 12, 2 ),
                  (int)substr( $ts, 4, 2 ), (int)substr( $ts, 6, 2 ),
                  (int)substr( $ts, 0, 4 ) );
                return date( "YmdHis", $t );
        }

        function date( $ts, $adj = false )
        {
                if ( $adj ) { $ts = $this->userAdjust( $ts ); }

                $d = (0 + substr( $ts, 6, 2 )) . 
                  " " . $this->getMonthAbbreviation( substr( $ts, 4, 2 ) ) .
                  " " . substr( $ts, 0, 4 );
                return $d;
        }

        function time( $ts, $adj = false )
        {
                if ( $adj ) { $ts = $this->userAdjust( $ts ); }

                $t = substr( $ts, 8, 2 ) . ":" . substr( $ts, 10, 2 );
                return $t;
        }

        function timeanddate( $ts, $adj = false )
        {
                return $this->time( $ts, $adj ) . ", " . $this->date( $ts, $adj );
        }

        function rfc1123( $ts )
        {
                return date( "D, d M Y H:i:s T", $ts );
        }

        function getValidSpecialPages()
        {
                global $wgValidSpecialPagesPl;
                return $wgValidSpecialPagesPl;
        }

        function getSysopSpecialPages()
        {
                global $wgSysopSpecialPagesPl;
                return $wgSysopSpecialPagesPl;
        }

        function getDeveloperSpecialPages()
        {
                global $wgDeveloperSpecialPagesPl;
                return $wgDeveloperSpecialPagesPl;
        }

        function getMessage( $key )
        {
                global $wgAllMessagesPl;
        if(array_key_exists($key, $wgAllMessagesPl))
                        return $wgAllMessagesPl[$key];
                else
                        return Language::getMessage($key);
        }

        # Inherit ucfirst() and stripForSearch() from LanguageUtf8

        function checkTitleEncoding( $s ) {
        # Check for Latin-2 backwards-compatibility URLs
                $ishigh = preg_match( '/[\x80-\xff]/', $s);
                $isutf = preg_match( '/^([\x00-\x7f]|[\xc0-\xdf][\x80-\xbf]|' .
                '[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xf7][\x80-\xbf]{3})+$/', $s );
        
        if($ishigh and !$isutf)
                return iconv( "ISO-8859-2", "UTF-8", $s );
        
        return $s;
        }

}

?>
