<?php
/** Pontic (Ποντιακά)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Consta
 * @author Crazymadlover
 * @author Omnipaedista
 * @author Sinopeus
 * @author Urhixidur
 * @author ZaDiak
 */

$namespaceNames = array(
	NS_MEDIA            => 'Μέσον',
	NS_SPECIAL          => 'Ειδικόν',
	NS_TALK             => 'Καλάτσεμαν',
	NS_USER             => 'Χρήστες',
	NS_USER_TALK        => 'Καλάτσεμαν_χρήστε',
	NS_PROJECT_TALK     => '$1_καλάτσεμαν',
	NS_FILE             => 'Αρχείον',
	NS_FILE_TALK        => 'Καλάτσεμαν_αρχείονος',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'MediaWiki_talk',
	NS_TEMPLATE         => 'Πρότυπον',
	NS_TEMPLATE_TALK    => 'Καλάτσεμαν_πρότυπι',
	NS_HELP             => 'Βοήθειαν',
	NS_HELP_TALK        => 'Καλάτσεμαν_βοήθειας',
	NS_CATEGORY         => 'Κατηγορίαν',
	NS_CATEGORY_TALK    => 'Καλάτσεμαν_κατηγορίας',
);

$namespaceAliases = array(
	'Εικόναν' => NS_FILE,
	'Καλάτσεμαν_εικόνας' => NS_FILE_TALK,
);

$datePreferences = array(
	'default',
	'pnt',
	'ISO 8601',
);

$defaultDateFormat = 'pnt';



$dateFormats = array(
	'pnt time' => 'H:i',
	'pnt date' => 'j xg Y',
	'pnt both' => 'H:i, j xg Y',
);

$messages = array(
# User preference toggles
'tog-underline'           => 'Υπογράμμιση συνδεσμίων:',
'tog-justify'             => 'Στοίχισην παραγραφίων',
'tog-editondblclick'      => 'Άλλαγμαν σελιδίων με διπλόν κλικ (JavaScript)',
'tog-previewonfirst'      => 'Δείξον πρώτον τέρεμαν σο πρώτον άλλαγμαν',
'tog-shownumberswatching' => "Δείξον τοι χρήστς π' δεαβάζνε",
'tog-showhiddencats'      => 'Δείξον κρυμμένα κατηγορίας',
'tog-norollbackdiff'      => 'Χάσον τα διαφοράς ασην αναστροφήν κιάν',

'underline-always' => 'Πάντα',
'underline-never'  => 'Καμίαν',

# Font style option in Special:Preferences
'editfont-sansserif' => 'Γραμματοσειρά σαν-σερίφ',
'editfont-serif'     => 'Γραμματοσειράν σερίφ',

# Dates
'sunday'        => 'Κερεκήν',
'monday'        => 'Δευτέραν',
'tuesday'       => 'Τριτ',
'wednesday'     => 'Τετάρτ',
'thursday'      => 'Πεφτ',
'friday'        => 'Παρέσα',
'saturday'      => 'Σάββαν',
'sun'           => 'Κερ',
'mon'           => 'Δευ',
'tue'           => 'Τρι',
'wed'           => 'Τετ',
'thu'           => 'Πεμ',
'fri'           => 'Παρ',
'sat'           => 'Σαβ',
'january'       => 'Καλαντάρτς',
'february'      => 'Κούντουρος',
'march'         => 'Μαρτς',
'april'         => 'Απρίλτς',
'may_long'      => 'Καλομηνάς',
'june'          => 'Κερασινός',
'july'          => 'Χορτοθέρτς',
'august'        => 'Aλωνάρτς',
'september'     => 'Σταυρίτες',
'october'       => 'Τρυγομηνάς',
'november'      => 'Αεργίτες',
'december'      => 'Χριστουγεννάρτς',
'january-gen'   => 'Καλανταρί',
'february-gen'  => 'Κούντουρονος',
'march-gen'     => 'Μαρτ',
'april-gen'     => 'Απρίλ',
'may-gen'       => 'Καλομηνά',
'june-gen'      => 'Κερασινού',
'july-gen'      => 'Χορτοθερί',
'august-gen'    => 'Aλωναρί',
'september-gen' => 'Σταυρί',
'october-gen'   => 'Τρυγομηνά',
'november-gen'  => 'Αεργί',
'december-gen'  => 'Χριστουγενναρί',
'jan'           => 'Καλαντ',
'feb'           => 'Κουντ',
'mar'           => 'Μάρ',
'apr'           => 'Απρ',
'may'           => 'Καλομ',
'jun'           => 'Κερ',
'jul'           => 'Χορτ',
'aug'           => 'Αλω',
'sep'           => 'Σταυ',
'oct'           => 'Τρυγ',
'nov'           => 'Αεργ',
'dec'           => 'Χριστ',

# Categories related messages
'pagecategories'                 => '{{PLURAL:$1|Κατηγορίαν|Κατηγορίας}}',
'category_header'                => 'Σελίδας τη κατηγορίας "$1"',
'subcategories'                  => 'Υποκατηγορίας',
'category-media-header'          => 'Τα μέσα σην κατηγορίαν "$1" απές',
'category-empty'                 => "''Αβούτη κατηγορίαν πα 'κ εχ' νέ σελίδας νέ μέσα.''",
'hidden-categories'              => '{{PLURAL:$1|Κρυμμένον κατηγορίαν|Κρυμμένα κατηγορίας}}',
'hidden-category-category'       => 'Κρυμμέν κατηγορίας',
'category-subcat-count'          => "{{PLURAL:$2|Αβούτη κατηγορίαν έχ' τ' αφκά την υποκατηγορίαν μαναχόν.|Αβούτη κατηγορίαν έχ' απές τ' αφκά {{PLURAL:$1|την υποκατηγορίαν|$1 τα υποκατηγορίας}}. Ούλ εντάμαν είν $2.}}",
'category-subcat-count-limited'  => "Η κατηγορίαν ατή έχ' αφκά καικά {{PLURAL:$1|την υποκατηγορίαν|$1 τα υποκατηγορίας}}.",
'category-article-count'         => "{{PLURAL:$2|Αβούτη κατηγορίαν έχ' τ' αφκά τη σελίδαν μαναχόν.|Τ' αφκά {{PLURAL:$1|η σελίδαν εν|$1 τα σελίδας είν}} απές σ' αβούτην την κατηγορίαν. Ούλ εντάμαν είν $2.}}",
'category-article-count-limited' => 'Αφκά καικά {{PLURAL:$1|η σελίδαν εν|$1 τα σελίδας είναι}} σην κατηγορίαν ατέν.',
'category-file-count'            => "{{PLURAL:$2|Αβούτη κατηγορίαν έχ' τ' αφκά τ' αρχείον μαναχόν.| Τ' αφκά {{PLURAL:$1|το αρχείον εν|$1 τα αρχεία είν}} απές σ' αβούτην την κατηγορίαν. Ούλ εντάμαν είν $2.}}",
'category-file-count-limited'    => "{{PLURAL:$1|Τ' αρχείον|$1 Τ' αρχεία}} αφκά καικά είν' σην κατηγορίαν.",
'listingcontinuesabbrev'         => 'συνεχίζεται...',
'index-category'                 => 'Συντεταγμένα σελίδας',
'noindex-category'               => 'Ασύντακτα σελίδας',

'mainpagetext' => "'''To λογισμικόν MediaWiki εθέκεν.'''",

'about'         => 'Περί',
'article'       => 'Σελίδαν',
'newwindow'     => "(ανοίγ' σ' άλλον παραθύρ)",
'cancel'        => 'Χάτεμαν',
'moredotdotdot' => 'Πλέα...',
'mypage'        => "Τ' εμόν η σελίδαν",
'mytalk'        => "Τ' εμόν το καλάτσεμαν",
'anontalk'      => "Καλάτσεμα για τ'ατό το IP",
'navigation'    => 'Πορπάτεμαν',
'and'           => '&#32;και',

# Cologne Blue skin
'qbfind'         => 'Εύρον',
'qbbrowse'       => 'Πλοήγησην',
'qbedit'         => 'Άλλαξον',
'qbpageoptions'  => 'Ατή η σελίδαν',
'qbpageinfo'     => 'Συμφραζόμενα',
'qbmyoptions'    => "Τ' εμά τα σελίδας",
'qbspecialpages' => 'Ειδικά σελίδας',
'faq'            => 'Πολλά ερωτήσεις (FAQ)',
'faqpage'        => 'Project:Πολλά ερωτήσεις (FAQ)',

# Vector skin
'vector-action-delete'       => 'Σβήσον',
'vector-action-move'         => 'Ετεροχλάεμαν',
'vector-action-protect'      => 'Ασπάλιγμαν',
'vector-action-undelete'     => 'Κλώσιμον',
'vector-action-unprotect'    => 'Άνοιγμαν',
'vector-namespace-category'  => 'Κατηγορίαν',
'vector-namespace-help'      => 'Σελίδα βοήθειας',
'vector-namespace-image'     => 'Αρχείον',
'vector-namespace-main'      => 'Σελίδαν',
'vector-namespace-media'     => 'Σελίδα μεσίων',
'vector-namespace-mediawiki' => 'Μένεμαν',
'vector-namespace-project'   => 'Σχετικά με',
'vector-namespace-special'   => 'Ειδικόν σελίδαν',
'vector-namespace-talk'      => 'Καλάτσεμαν',
'vector-namespace-template'  => 'Πρότυπον',
'vector-namespace-user'      => 'Σελίδαν χρήστε',
'vector-view-create'         => 'Ποίσον',
'vector-view-edit'           => 'Άλλαξον',
'vector-view-history'        => 'Τερέστεν ιστορίαν',
'vector-view-view'           => 'Δεάβασον',
'vector-view-viewsource'     => 'Τερέστεν κωδικόν',
'actions'                    => 'Ενέργειας',
'namespaces'                 => 'Περιοχάς',
'variants'                   => 'Παραλλαγάς',

'errorpagetitle'    => 'Λάθος',
'returnto'          => 'Επιστροφήν σο $1.',
'tagline'           => 'Ασό {{SITENAME}}',
'help'              => 'Βοήθειαν',
'search'            => 'Αράεμαν',
'searchbutton'      => 'Εύρον',
'go'                => 'Δέβα',
'searcharticle'     => 'Δέβα',
'history'           => 'Ιστορίαν τη σελίδας',
'history_short'     => 'Ιστορίαν',
'updatedmarker'     => 'αλλαγάς ασο τελευταίον το τέρεμαμ κι αδά μερέαν',
'info_short'        => 'Πληροφορίας',
'printableversion'  => 'Μορφή εκτύπωσης',
'permalink'         => 'Σκιρόν σύνδεσμος',
'print'             => 'Τύπωμαν',
'edit'              => 'Άλλαξον',
'create'            => 'Ποίσον',
'editthispage'      => 'Άλλαξον τη σελίδαν ατέν',
'create-this-page'  => 'Ποίσον τη σελίδαν',
'delete'            => 'Σβήσον',
'deletethispage'    => 'Σβήσεμαν τη σελίδας',
'undelete_short'    => 'Κλώσιμον {{PLURAL:$1|αλλαγματί|$1 αλλαγματίων}}',
'protect'           => 'Ασπάλιγμαν',
'protect_change'    => 'Άλλαγμαν',
'protectthispage'   => 'Ασπάλιγμα ατουνού τη σελίδας',
'unprotect'         => 'Άνοιγμαν',
'unprotectthispage' => 'Άνοιγμα ατουνού τη σελίδας',
'newpage'           => 'Καινούρεον σελίδα',
'talkpage'          => 'Καλάτσεμαν για τη σελίδαν ατέν',
'talkpagelinktext'  => 'Καλάτσεμαν',
'specialpage'       => 'Ειδικόν σελίδαν',
'personaltools'     => 'Προσωπικά εργαλεία',
'postcomment'       => "Νέον κομμάτ'",
'articlepage'       => 'Σελίδα',
'talk'              => 'Καλάτσεμαν',
'views'             => 'Τερέματα',
'toolbox'           => 'Εργαλεία',
'userpage'          => 'Τέρεν σελίδαν χρήστε',
'projectpage'       => 'Τέρεμαν σελίδας βοήθειας',
'imagepage'         => 'Τέρεν σελίδαν δογμενίων',
'mediawikipage'     => 'Τέρεν σελίδαν μενεματίων',
'templatepage'      => 'Τέρεν σελίδαν προτυπίων',
'viewhelppage'      => 'Τέρεν σελίδαν βοήθειας',
'categorypage'      => 'Τέρεν σελίδαν κατηγορίων',
'viewtalkpage'      => 'Τέρεν καλάτσεμαν',
'otherlanguages'    => "Σ' άλλα γλώσσας",
'redirectedfrom'    => '(Έρτεν ασό $1)',
'redirectpagesub'   => 'Σελίδαν διπλού σύνδεσμονος',
'lastmodifiedat'    => 'Αούτη σελίδα επεξεράστεν σα $1, $2.',
'protectedpage'     => 'Ασπαλιζμένον σελίδαν',
'jumpto'            => 'Δέβα σο:',
'jumptonavigation'  => 'Πορπάτεμαν',
'jumptosearch'      => 'Αράεμαν',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'Περί {{SITENAME}}',
'aboutpage'            => 'Project:Σχετικά',
'copyright'            => 'Το περιεχόμενον εν άμον ντο λεει η $1.',
'copyrightpage'        => '{{ns:project}}:Δικαιώματα πνευματί',
'currentevents'        => 'Ατωριζνά γεγονότα',
'currentevents-url'    => 'Project:Ατωριζνά γεγονότα',
'disclaimers'          => 'Ιμπρέσουμ',
'disclaimerpage'       => 'Project:Ιμπρέσουμ',
'edithelp'             => "Βοήθεια για τ' αλλαγμαν",
'edithelppage'         => 'Help:Άλλαγμαν',
'helppage'             => 'Help:Περιεχόμενα',
'mainpage'             => 'Αρχικόν σελίδα',
'mainpage-description' => 'Αρχικόν σελίδα',
'policy-url'           => 'Project:Πολιτική',
'portal'               => 'Πύλην τη κοινότητας',
'portal-url'           => 'Project:Πύλη κοινότητας',
'privacy'              => 'Ωρίαγμαν δογμενίων',
'privacypage'          => 'Project:Πολιτική ιδιωτικού απορρήτου',

'badaccess' => 'Σφάλμαν άδειας',

'versionrequiredtext' => 'Για να κουλεύετε αβούτεν τη σελίδαν χρειάσκεται η έκδοση $1 τη MediaWiki.
Τερέστεν τη [[Special:Version|version page]].',

'ok'                      => 'Εγέντον',
'retrievedfrom'           => 'Ασο "$1"',
'youhavenewmessages'      => 'Έχετε $1 ($2).',
'newmessageslink'         => 'καινούρεα μενέματα',
'newmessagesdifflink'     => 'υστερνόν αλλαγήν',
'youhavenewmessagesmulti' => 'Έχετε καινούρεα μενέματα σο $1',
'editsection'             => 'άλλαξον',
'editold'                 => 'άλλαξον',
'viewsourceold'           => 'τερέστεν κωδικόν',
'editlink'                => 'άλλαξον',
'viewsourcelink'          => 'τερέστεν κωδικόν',
'editsectionhint'         => "Άλλαξον κομμάτ': $1",
'toc'                     => 'Περιεχόμενα',
'showtoc'                 => 'δείξον',
'hidetoc'                 => 'κρύψον',
'thisisdeleted'           => 'Τέρεμαν γιά επαναφοράν $1;',
'viewdeleted'             => 'Τερέστεν το $1;',
'feedlinks'               => 'Ροή δογμενίων:',
'site-rss-feed'           => '$1 RSS Συνδρομή',
'site-atom-feed'          => '$1 Atom Συνδρομή',
'page-rss-feed'           => '"$1" RSS Συνδρομή',
'page-atom-feed'          => '"$1" Atom Συνδρομή',
'red-link-title'          => "$1 ('κ εγράφτεν ακόμαν)",

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Σελίδαν',
'nstab-user'      => 'Σελίδα χρήστε',
'nstab-media'     => 'Σελίδα μεσίων',
'nstab-special'   => 'Ειδικόν σελίδα',
'nstab-project'   => 'Σχετικά με',
'nstab-image'     => 'Εικόνα',
'nstab-mediawiki' => 'Μένεμαν',
'nstab-template'  => 'Πρότυπον',
'nstab-help'      => 'Σελίδα βοήθειας',
'nstab-category'  => 'Κατηγορία',

# Main script and global functions
'nosuchaction'      => "Αΐκον ενέργειαν 'κ εχ'.",
'nosuchspecialpage' => "Αΐκον ειδικόν σελίδαν 'κ εχ'.",

# General errors
'error'                => 'Σφάλμαν',
'databaseerror'        => 'Λάθος σην βάσην τη δογμενίων',
'laggedslavemode'      => "Ωρία: Η σελίδαν ίσως ξάι 'κ εχ' τα υστερνά τα αλλαγάς.",
'readonly'             => 'Βάση δογμενίων εν ασπαλιζμένον',
'enterlockreason'      => "Βαλέστεν λόγον για τ' ασπάλιγμαν και ους πότε θα εν ασπαλιγμένον",
'missing-article'      => 'Η βάση δογμενίων \'κ εύρεν το κείμενον σελίδας που έπρεπεν με τ\' όνεμα "$1" $2.

Αούτο έχ\' αιτίαν ανενημέρωτον σύνδεσμον "διαφ" γιά σύνδεσμον ιστορικίου που δεκνίζ\' σε σελίδαν που εβζινέθεν.
Αν \'κ έινεν αούτον, επορεί ν\' εύρετεν σφάλμαν σο software.
Άμα επορείτε, αναφέρετε ατό σε [[Special:ListUsers/sysop|γιαρίφ]] και δότε τον URL.',
'missingarticle-rev'   => '(μορφήν#: $1)',
'missingarticle-diff'  => '(Δεαφ: $1, $2)',
'internalerror'        => 'Σφάλμαν απές μερέαν',
'internalerror_info'   => 'Σφάλμαν απές μερέαν: $1',
'filecopyerror'        => 'Η αντιγραφή τ\' αρχείου ασό "$1" σο "$2" \'κ εγέντον.',
'filerenameerror'      => 'Η αλλαγή τ\' ονεματί τ\' αρχείου ασό "$1" σο "$2" \'κ εγέντον.',
'filedeleteerror'      => 'Το σβήσεμαν τ\' αρχείου "$1" \'κ εγέντον.',
'directorycreateerror' => 'Η κατηγορία "$1" \'κ εγέντον.',
'filenotfound'         => 'Τ\' αρχείον "$1" \'κ ευρέθεν.',
'fileexistserror'      => 'Τ\' αρχείον "$1" \'κ εγράφτεν: τ\' αρχείον υπάρχει',
'unexpected'           => 'Άχρηστον αξία: "$1"="$2".',
'badarticleerror'      => "Αβούτη η ενέργειαν 'κ επορεί να ίνεται σ'αβούτεν τη σελίδαν.",
'cannotdelete'         => 'Ατό ("$1") να σβύεται \'κ ίνεται.
Γιαμ ενεσβύεν ασ\'άλλτς;',
'badtitle'             => 'Άχρηστον τίτλος',
'badtitletext'         => "Το ψαλαφεμένον ο τίτλος τη σελίδας εν άκυρον, γιά εύκαιρον γιά τσακωμένον διαγλωσσικόν σύνδεσμος.
Τερέστεν αν έχ' έναν γιά πολλά γράμματα που 'κ ίνεται να κουλανεύκουνταν απές σε τίτλον.",
'viewsource'           => 'Τερέστεν κωδικόν',
'viewsourcefor'        => 'για $1',
'protectedpagetext'    => "Αβούτη σελίδαν εν ασπαλιγμένον και 'κ αλλάζ'.",
'viewsourcetext'       => "Επορείτε να τερείτε και ν' αντιγράφετε το κείμενον τ' ατεινές τη σελίδας:",
'protectedinterface'   => "Αβούτη σελίδαν έχ' απές κείμενον για το interface τη software και για τ' ατό εν ασπαλιγμένον.",
'namespaceprotected'   => "'Κ επορείτε να αλλάζετε σελίδας σην περιοχἠν ονοματίων '''$1'''.",
'customcssjsprotected' => "'Κ επορείτε να αλλάζετε αβούτο τη σελίδαν. Ατουπές, άλλος χρήστες έχ' τα προσωπικά τ'αγαπεμένα τ'.",
'ns-specialprotected'  => "Τα ειδικά τα σελίδας 'κ επορούν ν' επεξεργάσκουνταν.",

# Virus scanner
'virus-unknownscanner' => 'αναγνώριμον αντιικόν:',

# Login and logout pages
'welcomecreation'            => "== Καλώς έρθετεν, $1! ==
Η λογαρίαν εσουν εγέντον.
Τ' άλλαγμαν τη [[Special:Preferences|{{SITENAME}} προτιμησίων]] εσουν μη νεσπάλετε.",
'yourname'                   => 'Όνεμαν χρήστε:',
'yourpassword'               => 'Σημάδι:',
'yourpasswordagain'          => "Ξαν' γράψτεν το σημάδι:",
'remembermypassword'         => "Αποθήκεμαν τη σημαδίμ σ' αβούτον τον υπολογιστήν",
'yourdomainname'             => 'Το domain εσούν:',
'login'                      => 'Εμπάτε',
'nav-login-createaccount'    => 'Εμπάτεν / ποισέστεν λογαρίαν',
'loginprompt'                => "Πρέπ' ν' άφτετε τα cookies για εμπείτε σο {{SITENAME}}.",
'userlogin'                  => 'Εμπάτεν / ποισέστεν λογαρίαν',
'userloginnocreate'          => 'Εμπάτεν',
'logout'                     => 'οξουκά',
'userlogout'                 => 'Οξουκά',
'notloggedin'                => 'Ευρίσκεζνε οξουκά ασή Βικιπαίδειαν',
'nologin'                    => "Λογαρίαν 'κ έχετε; '''$1'''.",
'nologinlink'                => 'Ποισέστεν λογαρίαν',
'createaccount'              => 'Ποίσον λογαρίαν',
'gotaccount'                 => "Λογαρίαν έχετε; '''$1'''.",
'gotaccountlink'             => 'Εμπάτε',
'createaccountmail'          => 'με ελεκτρονικόν μένεμαν',
'badretype'                  => "Τα σημάδε ντ' εγράψετεν 'κ ταιριάζνε.",
'userexists'                 => "Τ' όνεμαν έχ' ατό άλλος χρήστες.
Βαλέστε άλλον όνεμαν.",
'loginerror'                 => 'Σφάλμα εγγραφής',
'noname'                     => "'Κ έβαλατε καλόν όνεμαν χρήστε.",
'loginsuccesstitle'          => "Έντον τ' εσέβεμαν",
'loginsuccess'               => "'''Εσήβετεν σο {{SITENAME}} με τ'όνεμαν \"\$1\".'''",
'nosuchuser'                 => 'Αδαπές \'κ εχ\' χρήστεν με τ\' όνομαν "$1".
Τερέστεν τα γράμματα τη ονοματί, τερέστεν τα τρανογράμματα και τα μικρογράμματα να είναι τογρία.
Τερέστεν την ορθογραφίαν ή [[Special:UserLogin/signup|ποισέστεν καινούρεον λογαρίαν]].',
'nosuchusershort'            => "Αδαπές 'κ εχ' χρήστεν με τ' όνομαν \"<nowiki>\$1</nowiki>\".
Τ'όνομαν γραφέστεν ατο τογρία.",
'nouserspecified'            => "Πρέπ' να ψιλίζετε έναν όνεμαν.",
'login-userblocked'          => "Το χρήστεν έδεξαν ατον. Να εμπαίν 'κ ίνεται.",
'wrongpassword'              => "Το σημάδιν 'κ εν σωστόν.
Ποισέστεν άλλο γράσεμαν.",
'wrongpasswordempty'         => 'Το σημάδιν έτον εύκαιρον.
Ποισέστεν άλλο γράσεμαν.',
'passwordtooshort'           => "Το σημάδινεσουν πρέπ' να εχ' {{PLURAL:$1|1 γράμμαν|$1 γράμματα}}.",
'password-name-match'        => "Τ'όνομαν και το σημάδιν 'κ ίνεται να είναι έναν. Αλλέικον πράγμαν εν το ένα ασ' άλλον.",
'mailmypassword'             => 'Στείλον καινούρεον σημάδιν',
'passwordremindertitle'      => 'Καινούρεον προσωρνόν σημάδιν για {{SITENAME}}',
'passwordremindertext'       => 'Κάποιος (Γιαμ\' εσείστουν, ασήν διεύθυνσην IP $1)
εποίκεν ψαλαφίον να στείλκουμες καινούρεον σημάδιν για τον ιστοτόπον {{SITENAME}} ($4).
Το σημάδιν για τον χρήστεν "$2" ατώρα εν "$3". Εάν εποίκατε το ψαλαφίον,
εμπάτεν ατώρα σην σελίδαν και ποισέστεν το σημάδινεσουν αλλέικον. Το καινούρεον το σημάδιν θα χάται σε {{PLURAL:$5|μιαν ημέραν|$5 ημέρας}}.

Εάν το ψαλαφίον εποίκενατο άλλος ή έχετε σο νούνεσουν αξάν το παλαιόν το σημάδινεσουν και το καινούρεον ξάι \'κ χρειάσκεται, επορείτε ν\' ανασπάλλετε το καινούρεον το σημάδιν με τ\' αβούτο το μένεμαν εντάμαν και να μεταχειρίσκεστε το παλαιόν το σημάδιν άμον ντ\' εφτάγατε ους οσήμερον.',
'noemail'                    => '\'Κ εδώθεν e-mail address τη χρήστε "$1".',
'passwordsent'               => 'Έναν καινούρεον σημάδιν επήγεν σο e-mail τη "$1".
Άμον ντο παίρετ\' ατό, εμπάτε ξαν.',
'eauthentsent'               => "Έναν μένεμαν confirmation e-mail επήγεν σην διεύθυνσην ντ' εδώκατε.
Πριχού να πηγαίνει άλλον μένεμαν σ' αβούτεν τη λογαρίαν, θα φτάτεν ατά ντο γραφ' σο μένεμαν απές. Αέτς πα θα δεκνίζετε το e-mail ατό εν το τεσέτερον.",
'mailerror'                  => 'Σφάλμαν σην αποστολήν τη μενεματί: $1',
'acct_creation_throttle_hit' => "Εποίκατε, ή ίσως αλλέτερος ασήν διεύθυνσήνεσουν IP, {{PLURAL:$1|1 λογαρίαν|$1 λογαρίας}}, το μέγιστον γι' ατώρα.
'Κ επορείτε ν' εφτάτε άλλον.",
'accountcreated'             => 'Έντον η λογαρίαν',
'accountcreatedtext'         => "Έντον η λογαρίαν τη χρήστ' $1.",
'createaccount-title'        => 'Δημιουργίαν λογαρίας για {{SITENAME}}',
'loginlanguagelabel'         => 'Γλώσσαν: $1',

# Password reset dialog
'resetpass'                 => 'Νέον σημάδιν',
'resetpass_header'          => "Άλλαξον σημάδ'",
'oldpassword'               => 'Παλαιόν σημάδιν:',
'newpassword'               => 'Καινούρεον σημάδιν:',
'retypenew'                 => 'Γράψον ξαν το νέον σημάδιν:',
'resetpass_submit'          => 'Ορίστεν το σημάδιν κι ελάτεν απές',
'resetpass_success'         => 'Το σημάδιν ελλάεν!
Ατώρα συνδέουμε σας...',
'resetpass_forbidden'       => "Τα σημάδια για να εμπάτεν 'κ επορούν ν'αλλάζνε",
'resetpass-no-info'         => "Επρέπ να ελάτεν απές για ν'ελέπετε αούτον τη σελίδαν κιάλλο τογρία.",
'resetpass-submit-loggedin' => 'Άλλαξον σημάδιν',
'resetpass-submit-cancel'   => 'Χάσονα',
'resetpass-wrong-oldpass'   => "'Κ εγράφτεν τογρία το προσωρνόν ή κανονικόν σημάδιν.
Γιαμ' εποίκατε καινούρεον σημάδιν ή εποίκατε ψαλαφίον για καινούρεον προσωρνόν σημάδιν;",
'resetpass-temp-password'   => "Προσωρινόν σημάδ':",

# Edit page toolbar
'bold_sample'     => 'Χοντρόν κείμενον',
'bold_tip'        => 'Χοντρόν κείμενον',
'italic_sample'   => 'Ψιλόν κείμενον',
'italic_tip'      => 'Ψιλόν κείμενον',
'link_sample'     => 'Τίτλον σύνδεσμονος',
'link_tip'        => 'Εσωτερικόν σύνδεσμον',
'extlink_sample'  => 'http://www.example.com τίτλον σύνδεσμονος',
'extlink_tip'     => 'Εξωτερικόν σύνδεσμον (να μην ανασπάλλετε το πρόθεμαν http:// )',
'headline_sample' => 'Κείμενον τίτλονος',
'headline_tip'    => 'Δεύτερον τίτλος (επίπεδον 2)',
'math_sample'     => 'Αδά εισάγετε την φόρμουλαν',
'math_tip'        => 'Μαθεματικόν φόρμουλα (LaTeX)',
'nowiki_sample'   => 'Αδακά πα να εισάγετε το μη μορφοποιημένον κείμενον.',
'nowiki_tip'      => "Ξάι 'κ να τερείται η μορφοποίηση Wiki.",
'image_tip'       => 'Ενσωματωμένον εικόνα',
'media_tip'       => 'Σύνδεσμον αρχείατι πολυμεσίων',
'sig_tip'         => 'Η υπογραφήν εσούν με ώραν κι ημερομηνίαν',
'hr_tip'          => "Οριζόντιον γραμμή (μη θέκ'ς ατέν πολλά)",

# Edit pages
'summary'                          => 'Σύνοψη:',
'subject'                          => 'Θέμα/επικεφαλίδα:',
'minoredit'                        => 'Μικρόν αλλαγήν',
'watchthis'                        => 'Ωρίαγμαν τη σελίδας',
'savearticle'                      => 'Τοπλάεμαν σελίδας',
'preview'                          => 'Πρώτον τέρεμαν',
'showpreview'                      => 'Πρώτον τέρεμαν',
'showdiff'                         => 'Αλλαγάς',
'anoneditwarning'                  => "'''Ωρίασων:''' 'Κ εποίκες τ' εσέβεμαν.
Τ' IP ις θα γράφκεται και θα ελέπν' ατό σ' ιστορικόν τη σελίδας.",
'summary-preview'                  => 'Πρώτον τέρεμαν τη σύνοψης:',
'blockedtitle'                     => 'Ο χρήστες εν ασπαλιζμένος',
'blockedtext'                      => "'''Τ' όνομαν ή το IP εσούν εκλείστεν.'''

Τ' ασπάλιγμαν εποίκενατο ο χρήστες $1.
Έδωκεν την αιτίαν ''$2''.

* Ασπάλιγμαν αχπάσκεται: $8
* Ασπάλιγμαν τελείται: $6
* Θα κλείσκεται ο χρήστες: $7

Για τ' ασπάλιγμαν επορείτε να συντισένετε με τον $1 ή με τ' αλλτς τοι [[{{MediaWiki:Grouppage-sysop}}|νοματέοις]].
Για να γράφετε ελεκτρονικόν μένεμαν ('e-mail this user') βαλέστεν το τεσέτερον το σωστόν το e-mail address σα [[Special:Preferences|προτιμήσαι τη λογαρίας εσούν]]. Επεκεί 'κ θα είστουν ασπαλιγμένος για γράψιμον τη μενεματί.
Τ' ατοριζνόν το IP εσούν εν $3, και το ID τ' ασπαλιγματί εν #$5.
Ποδεδίζουμε σας να γράφετε τα και τα δυο σο μένεμανεσουν απές.",
'autoblockedtext'                  => "Το IP εσούν εκλείστεν αυτόματα επειδή μεταχειρίσκουτονατο άλλος χρήστες ντ' έτον ασπάλιγμένος ασόν χρήστεν $1.
Έδωκεν την αιτίαν:

:''$2''

* Ασπάλιγμαν αχπάσκεται: $8
* Ασπάλιγμαν τελείται: $6
* Ασπαλιζὀμενον: $7

Για το ασπάλιγμαν επορείτε να συντισένετε με το χρήστεν $1 ή με τ' αλλτς τοι [[{{MediaWiki:Grouppage-sysop}}|νοματέοις]]. Για να γράφετε ελεκτρονικόν μένεμαν ('e-mail this user') βαλέστεν το τεσέτερον το σωστόν το e-mail address σα [[Special:Preferences|προτιμήσαι τη λογαρίας εσούν]]. Εάν 'κ εν ασπαλιγμένον η χρήσηνατ, επορείτε να γράφετε μένεμαν.

Το IP εσούν εν $3 και το ID τη ασπαλιγματίνεσουν εν #$5.
Ποδεδίζουμε σας να γράφτατο σο μένεμαν εσούν.",
'blockednoreason'                  => "'Κ εγράφτεν αιτίαν",
'whitelistedittitle'               => "Εμπάτε για να φτάτε τ' αλλαγάς",
'whitelistedittext'                => "Πρέπ να $1 για ν' επορείτε ν' επεξεργάσκεστε τα σελίδας.",
'nosuchsectiontitle'               => "Αΐκον κομμάτ' 'κ εχ'",
'loginreqtitle'                    => 'Επρέπ να εσέβειτε',
'loginreqlink'                     => 'εσέβεμαν',
'loginreqpagetext'                 => 'Επρέπ να $1 για να τερείτε άλλα σελίδας.',
'accmailtitle'                     => 'Το σημάδι εστάλθεν.',
'accmailtext'                      => "Το σημάδι για τον/την [[User talk:$1|$1]] εστάλθεν σο $2.

Το σημάδι για το καινούρεον την λογαρίαν επορείς να αλλάζεις ασα την σελίδαν ''[[Special:ChangePassword|άλλαξον λογαρίαν]]'' με τ' έμπαζμανεσουν.",
'newarticle'                       => '(Καινούρεον)',
'newarticletext'                   => "Έρθατεν ασ' έναν σύνδεσμον σ' έναν εύκαιρον σελίδαν.
Για να εφτάτε τη σελίδαν, αρχινέστε γράψιμον σο χουτίν αφκά (δεαβάστεν τη [[{{MediaWiki:Helppage}}|σελίδαν βοήθειας]] και μαθέστεν κιάλλα).
Εάν 'κ θέλετε ν' εφτάτε αβούτεν τη σελίδαν, πατήστε το κουμπίν το λεει '''οπίς''' και δεβάτεν οπίς απ' όθεν έρθατεν.",
'noarticletext'                    => "Αβούτεν η σελίδαν 'κ εχ' κείμενον απές ακόμαν.
[[Special:Search/{{PAGENAME}}|Εύρον αβούτον τον τίτλον]] σ' αλλέα τοι σελίδας,
<span class=\"plainlinks\">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} εύρον την σελίδαν σα σχετικά αρχεία],
ή [{{fullurl:{{FULLPAGENAME}}|action=edit}} άλλαξον αβούτεν την σελίδαν].",
'updated'                          => '(Ενήμερον)',
'note'                             => "'''Σημείωμαν:'''",
'previewnote'                      => "'''Ατό πα πρώτον τέρεμαν εν και μόνον.
Τ' αλλαγάς 'κ εκρατέθαν!'''",
'editing'                          => 'Αλλαγήν $1',
'editingsection'                   => 'Αλλαγήν $1 (τμήμα)',
'editingcomment'                   => "Άλλαγμαν $1 (καινούρεον κομμάτ')",
'yourtext'                         => 'Το γράψιμονις',
'storedversion'                    => 'Αποθηκεμένον μορφή',
'editingold'                       => "'''ΩΡΙΑ: Εφτάτε αλλαγάς σε παλαιόν έκδοσην τη σελίδας.
Εάν θα κρατείτε ατά, ούλ' τ' επεκεί αλλαγάς θα χάνταν.'''",
'yourdiff'                         => 'Διαφοράς',
'copyrightwarning'                 => "Ούλαι τα γραψίματα ασο {{SITENAME}} θα μεταχειρίσκουνταν άμον ντο λεει το $2 (τερέστεν και $1).
Εάν 'κ θέλετε ατό να ίνεται, να μην εφτάτε το αποθήκεμαν.<br />
Καμίαν κι ανασπάλλετε: Αδακά 'κ εν ο τόπον για να θέκουμε γράψιμον ντ' έγραψαν αλλ. Βαλέστε άρθρα όνταν κατέχετε τα δικαιώματα πνευματί μαναχόν.
'''ΚΑΜΙΑΝ 'Κ ΘΕΚΕΤΕ ΓΡΑΨΙΜΟΝ ΑΔΑΚΑ ΟΝΤΕΣ 'Κ ΕΧΕΤΕ ΤΑ ΔΙΚΑΙΩΜΑΤΑ ΠΝΕΥΜΑΤΙ!'''",
'longpagewarning'                  => "'''ΩΡΙΑ: Αβούτεν η σελίδαν έχ' μέγεθος $1 kb. Μερικά browser 'κ επορούν ν' επεξεργάσκουνταν σελίδας ντ' έχνε 32 kb κιαν. Επορείτε να λύετε το πρόβλημαν αν εφτάτεν ατέναν μικρά κομμάται.'''",
'templatesused'                    => "{{PLURAL:$1|Πρότυπον|Πρότυπα}} το μεταχειρίσκουνταν σ' αβούτεν την σελίδαν:",
'templatesusedpreview'             => "{{PLURAL:$1|Πρότυπον|Πρότυπα}} σ' αβούτον το πρώτον τέρεμαν:",
'template-protected'               => '(ασπαλιγμένον)',
'template-semiprotected'           => '(ημψά-ασπαλιγμένον)',
'hiddencategories'                 => "Αούτο η σελίδαν ανήκ' σα {{PLURAL:$1|1 κρυμμένον κατηγορία|$1 κρυμμένα κατηγορίας}}:",
'nocreatetext'                     => "Σο {{SITENAME}} περιορίσκουτον το ποίσεμα σελιδίων.
'Πορείτε να κλώσκεστε οπίς και ν' αλλάζετε έναν παλαιόν σελίδαν ή να [[Special:UserLogin|εμπάτε ή να εφτάτε λογαρίαν]].",
'permissionserrorstext-withaction' => "'Κ έχετε την άδειαν για $2, για {{PLURAL:$1|τ'αφκά το λόγον|τ'αφκά τοι λόγους}}:",
'recreate-moveddeleted-warn'       => "'''Ωρία: Εφτάτε αξάν μίαν σελίδαν ντ' ενεσβύεν οψεκές.'''

Ίσως εν καλλίον να μην εφτάτε τη σελίδαν.
Τερέστεν για βοήθειαν σ' αρχείον σβησεματίων και ετεροχλαεματίων για την αιτίαν για το σβήσιμον:",
'moveddeleted-notice'              => 'Αούτο η σελίδαν εβζινέθεν.
Αφκά ευρίεται έναν γράψιμον ασο αρχείον σβησεματίων και ετεροχλαεματίων τη σελίδας.',

# Account creation failure
'cantcreateaccounttitle' => "Το ποίσιμον τη λογαρίας 'κ έντον",

# History pages
'viewpagelogs'           => "Τέρεν αρχεία γι' αβούτεν τη σελίδαν",
'nohistory'              => "Αούτο η σελίδαν αλλαγάς 'κ εςς.",
'currentrev'             => 'Ατωριζνόν μορφήν',
'currentrev-asof'        => 'Ατωριζνόν μορφήν τη $1',
'revisionasof'           => 'Μορφήν τη $1',
'revision-info'          => 'Έκδοση σα $1 ασόν/ασήν $2',
'previousrevision'       => '←Παλαιόν μορφήν',
'nextrevision'           => 'Κι άλλο καινούρεον μορφήν→',
'currentrevisionlink'    => 'Ατωριζνόν μορφήν',
'cur'                    => 'ατωριζνόν',
'next'                   => 'επόμενον',
'last'                   => 'τελευταίον',
'page_first'             => 'πρώτον',
'page_last'              => 'τελευταίον',
'histlegend'             => 'Σύγκριμα διαφορίων: βαλέστεν τα μορφάς το θέλετε και τερέστεν τα διαφοράσατουν. Για να τερείτε τα διαφοράς, ποισέστεν έναν κλικ σο πεδίον το λεει "Γαρσουλαεύτε...". <br />
Πληροφορία: (ατωριζνόν) = διαφοράς με τ\' ατωριζνόν τη μορφήν,
(υστερνόν) = διαφοράς με τ\' υστερνόν τη μορφήν, μ = μικρά διαφοράς.',
'history-fieldset-title' => 'Εύρον σο ιστορικόν',
'history-show-deleted'   => "Ατά ντ'ενεσβύαν μαναχόν",
'histfirst'              => "Ασ' όλεα παλαιόν",
'histlast'               => "Ασ' όλεα καινούρ'",
'historysize'            => '({{PLURAL:$1|1 byte|$1 bytes}})',
'historyempty'           => '(εύκαιρον)',

# Revision feed
'history-feed-item-nocomment' => '$1 σο $2',

# Revision deletion
'rev-delundel'               => 'δείξον/κρύψον',
'rev-showdeleted'            => 'δείξον',
'revdelete-show-file-submit' => 'Ναι',
'revdel-restore'             => 'Ἀλλαγμαν ορατότητας',
'pagehist'                   => 'Ιστορίαν σελίδας',
'deletedhist'                => 'Σβηγμένον ιστορίαν',
'revdelete-content'          => 'περιεχόμενον',
'revdelete-summary'          => 'σύνοψην',
'revdelete-uname'            => "όνεμαν χρήστ'",
'revdelete-hid'              => 'κρυφόν $1',
'revdelete-unhid'            => 'όχι κρυφόν $1',

# History merging
'mergehistory-from' => 'Σελίδα πηγή:',
'mergehistory-into' => 'Σελίδα προορισμού:',

# Merge log
'revertmerge' => 'Χώρτσον ξαν',

# Diffs
'history-title'           => 'Ιστορικόν εκδοσίων για τη σελίδαν "$1"',
'difference'              => '(Διαφορά μεταξύ τη μορφίων)',
'lineno'                  => 'Γραμμή $1:',
'compareselectedversions' => 'Γαρσουλαεύτε...',
'editundo'                => 'αναίρεση',
'diff-multi'              => "({{PLURAL:$1|Μίαν αλλαγήν|$1 αλλαγάς}} 'κ δεκνίζκουνταν.)",

# Search results
'searchresults'             => 'Εύρον αποτελέσματα',
'searchresults-title'       => 'Εύρον αποτελέσματα για "$1"',
'searchresulttext'          => "Κι άλλο πολλά πληροφορίας για τ'αράεμαν σο {{SITENAME}} ευρίσκουνταν σο [[{{MediaWiki:Helppage}}|{{int:help}}]].",
'searchsubtitle'            => 'Αραέβετε \'\'\'[[:$1]]\'\'\' ([[Special:Prefixindex/$1|ούλα τα σελίδας ντ\'αρχίζνε με "$1"]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|ούλα τα σελίδας ντο δεκνίζνε σο "$1"]])',
'searchsubtitleinvalid'     => "Αράεψες το '''$1'''",
'notitlematches'            => "Κανέναν όνομαν σελίδας 'κ ταιριάζ",
'notextmatches'             => "Κανέναν γράψιμον 'κ ταιριάζ",
'prevn'                     => '{{PLURAL:$1|$1}} προηγουμένων',
'nextn'                     => '{{PLURAL:$1|$1}} επομένων',
'viewprevnext'              => 'Τέρεν ($1 {{int:pipe-separator}} $2) ($3)',
'searchhelp-url'            => 'Help:Περιεχόμενα',
'searchprofile-images'      => 'Πολυμέσα',
'searchprofile-everything'  => 'Όλεα',
'search-result-size'        => '$1 ({{PLURAL:$2|1 λέξη|$2 λέξεις}})',
'search-redirect'           => '(το διπλόν ο σύνδεσμον $1)',
'search-section'            => "(κομμάτ' $1)",
'search-suggest'            => 'Γιαμ αραεύετε: $1',
'search-interwiki-caption'  => 'Αδερφικά έργα',
'search-interwiki-default'  => '$1 αποτελέσματα:',
'search-interwiki-more'     => '(πλέα)',
'search-mwsuggest-enabled'  => 'με οδηγίας',
'search-mwsuggest-disabled' => 'θίχως οδηγίας',
'searchall'                 => 'ούλαι',
'nonefound'                 => "'''Σημείωση:''' Κανονικά ολίγα περιοχάς ονοματίων αραεύκουνταν μαναχόν. Βαλέστεν ''all:'' ασην λέξην εμπροστά για ίνεται το αράεμαν σ'όλεα τα σελίδας (και σελίδας καλατσεματί, πρότυπα κλπ.) ή βαλέστεν ους πρόθεμαν την περιοχήν ονοματίων π'θέλετε για να αραεύετε εκαικά.",
'powersearch'               => 'Αναλυτικόν αράεμαν',
'powersearch-legend'        => 'Αναλυτικόν αράεμαν',
'powersearch-ns'            => "Αράεμαν σα τόπε τ' ονοματίων:",
'powersearch-redir'         => 'Κατάλογον με διπλά συνδέσμ',
'powersearch-field'         => 'Αράεμαν τη',
'powersearch-toggleall'     => 'Όλια',
'powersearch-togglenone'    => 'Τιδέν',
'search-external'           => 'Εύρον σα εξ μερέαν',

# Quickbar
'qbsettings-none' => 'Τιδέν',

# Preferences page
'preferences'               => 'Αγαπεμένα',
'mypreferences'             => "Τ' εμά τ' αγαπεμένα",
'changepassword'            => 'Άλλαξον σημάδιν',
'prefs-skin'                => 'Όψην',
'skin-preview'              => 'Πρώτον τέρεμαν',
'prefs-math'                => 'Απόδοσην μαθηματικίων',
'prefs-datetime'            => 'Ημερομηνίαν και ώραν',
'prefs-rc'                  => 'Υστερνά αλλαγάς',
'prefs-watchlist'           => 'Κατάλογον ωριαγματί',
'prefs-misc'                => 'Διαφ',
'saveprefs'                 => 'Αποθήκεμαν',
'searchresultshead'         => 'Εύρον',
'timezonelegend'            => 'Χρονικόν ζώνην:',
'localtime'                 => 'Τοπικόν χρόνον:',
'timezoneoffset'            => 'Διαφοράν ωρίων¹:',
'timezoneregion-africa'     => 'Αφρικήν',
'timezoneregion-america'    => 'Αμερικήν',
'timezoneregion-antarctica' => 'Ανταρκτικήν',
'timezoneregion-arctic'     => 'Aρκτικός',
'timezoneregion-asia'       => 'Ασίαν',
'timezoneregion-atlantic'   => 'Ατλαντικόν Ωκεανός',
'timezoneregion-australia'  => 'Αυστραλίαν',
'timezoneregion-europe'     => 'Ευρώπην',
'timezoneregion-indian'     => 'Ινδικόν Ωκεανός',
'timezoneregion-pacific'    => 'Ειρηνικόν Ωκεανός',
'default'                   => 'προεπιλογήν',
'prefs-files'               => 'Αρχεία',
'youremail'                 => 'Ελεκτρονικόν μένεμαν:',
'username'                  => 'Όνεμα χρήστε:',
'uid'                       => 'ID Χρήστε:',
'yourrealname'              => 'Πραματικόν όνεμαν:',
'yourlanguage'              => "Τ' εσόν η γλώσσαν:",
'yournick'                  => 'Υπογραφή:',
'badsiglength'              => "Το σημάδινεσουν εν πολλά τρανόν.
Πρέπ' να εχ' λιγότερα ασά $1 {{PLURAL:$1|γράμμαν|γράμματα}}.",
'yourgender'                => 'Φύλον:',
'gender-unknown'            => 'Aναγνώριμον',
'gender-male'               => 'Αρσενικόν',
'gender-female'             => 'Θελκόν',
'email'                     => 'Ελεκτρονικόν μένεμαν',
'prefs-help-realname'       => "'Κ επρέπ' να βάλετεν το τεσέτερον το πραματικόν τ' όνεμαν.
Άμα αν εβάλετεν ατό, αμπορεί πα ν' αναγνωρίζκεται το τεσέτερον η δουλείαν.",
'prefs-help-email-required' => 'Χρειάσκεται το ηλεκτρονικόν η διεύθυνση.',
'prefs-signature'           => 'Υπογραφή',
'prefs-diffs'               => 'Διαφοράς',

# User rights
'userrights-groupsmember' => 'Μέλος τη:',

# Groups
'group-user'          => 'Χρήστες',
'group-autoconfirmed' => 'Αυτόματα βεβαιωμένοι χρηστ',
'group-bot'           => 'Bots',
'group-sysop'         => 'Νοματέοι',
'group-bureaucrat'    => 'Γεροντάδες',
'group-suppress'      => 'Παραβλέμματα',
'group-all'           => '(ούλαι)',

'group-user-member'          => 'Χρήστες',
'group-autoconfirmed-member' => 'Αυτόματα βεβαιωμένος χρήστες',
'group-bot-member'           => 'bot',
'group-sysop-member'         => 'Νοματέας',
'group-bureaucrat-member'    => 'Γέροντας',
'group-suppress-member'      => 'Επόπτες',

'grouppage-user'          => '{{ns:project}}:Χρηστς',
'grouppage-autoconfirmed' => '{{ns:project}}:Αυτόματα βεβαιωμένοι χρηστ',
'grouppage-sysop'         => '{{ns:project}}:Νοματέοι',
'grouppage-bureaucrat'    => '{{ns:project}}:Γεροντάδες',
'grouppage-suppress'      => '{{ns:project}}:Επόπτες',

# Rights
'right-read'          => 'Δεάβασον σελίδας',
'right-edit'          => 'Άλλαξον σελίδας',
'right-createpage'    => "Ποίσον σελίδας (ντο 'κ εν σελίδας καλατζεματί)",
'right-createtalk'    => 'Ποίσον σελίδας καλατζεματί',
'right-createaccount' => 'Ποίσον καινούρεα λογαρίας χρηστίων',
'right-move'          => 'Ετεροχλάεμαν σελιδίων',
'right-movefile'      => 'Ετεροχλάεμαν αρχείων',
'right-upload'        => 'Φόρτωσον αρχεία',
'right-upload_by_url' => "Φόρτωσον αρχείον ασ' έναν URL",
'right-delete'        => 'Σβήσον σελίδας',
'right-bigdelete'     => 'Σβήσον σελίδας με τρανά ιστορίας',
'right-browsearchive' => 'Αράεμαν σα σβημένα σελίδας',
'right-import'        => "Έμπαζμαν σελιδίων ασ' άλλα βίκι",
'right-siteadmin'     => 'Ασπάλισον κι άνοιξον τη βάση δογμενίων',

# User rights log
'rightslog'  => 'Αρχείον δικαιωματίων',
'rightsnone' => '(τιδέν)',

# Associated actions - in the sentence "You do not have permission to X"
'action-read'          => 'δεάβασον αβούτεν την σελίδαν',
'action-edit'          => 'άλλαγμαν τη σελίδας',
'action-createpage'    => 'ποίσον σελίδας',
'action-move'          => "κότζεμαν τη σελίδας σ' άλλον τίτλον",
'action-movefile'      => 'ετεροχλάεμαν αβούτου τη αρχείου',
'action-upload'        => 'φόρτωσον αβούτο το αρχείον',
'action-upload_by_url' => "φόρτωσον αβούτο το αρχείον ασ' έναν URL",

# Recent changes
'nchanges'                       => '$1 {{PLURAL:$1|αλλαγή|αλλαγάς}}',
'recentchanges'                  => 'Υστερνά αλλαγάς',
'recentchanges-legend'           => 'Επιλογάς υστερνιδίων αλλαγίων',
'recentchanges-feed-description' => "Τ' ασ' όλεα καινούρεα αλλαγάς τη wiki ωρία σ' αβούτεν την περίληψην.",
'rcnote'                         => "Αφκά {{PLURAL:$1|έχ' '''1''' αλλαγήν|έχ' τα '''$1''' τελευταία αλλαγάς}} τη {{PLURAL:$2|τελευταίας ημέρας|τελευταίων '''$2''' ημερίων}}, σα $5, $4.",
'rcnotefrom'                     => "Αφκά καικά ευρίουνταν τ' αλλαγάς ασό <b>$2</b> (εμφάνιση <b>$1</b> αλλαγίων max).",
'rclistfrom'                     => "Δείξον τ' αλλαγάς ασα $1 μαναχόν",
'rcshowhideminor'                => '$1 τα μικρά αλλαγάς',
'rcshowhidebots'                 => '$1 bots',
'rcshowhideliu'                  => '$1 χρήστες με λογαρίαν',
'rcshowhideanons'                => "$1 τ' αναγνώριμους τοι χρήστς",
'rcshowhidepatr'                 => "$1 αλλαγάς ντ' ωράουνταν",
'rcshowhidemine'                 => "$1 τ' αλλαγάς ιμ",
'rclinks'                        => "Δείξον τα $1 υστερνά τ' αλλαγάς α σα $2 υστερνά τα ημέρας<br />$3",
'diff'                           => 'διαφορά',
'hist'                           => 'ιστ.',
'hide'                           => 'Κρύψον',
'show'                           => 'Δείξον',
'minoreditletter'                => 'μ',
'newpageletter'                  => 'Ν',
'boteditletter'                  => 'b',
'rc_categories_any'              => 'Κάθαν',
'rc-enhanced-expand'             => "Δείξον λεπτομέρειας (θελ' JavaScript)",
'rc-enhanced-hide'               => 'Κρύψον λεπτομέρειας',

# Recent changes linked
'recentchangeslinked'          => 'Σχετικά αλλαγάς',
'recentchangeslinked-feed'     => 'Σχετικά αλλαγάς',
'recentchangeslinked-toolbox'  => 'Σχετικά αλλαγάς',
'recentchangeslinked-title'    => 'Αλλαγάς τη "$1"',
'recentchangeslinked-noresult' => "Σ' αβούτα τα σελίδας 'κ εγένταν αλλαγάς.",
'recentchangeslinked-summary'  => "Αβούτος εν κατάλογον με τ' υστερνά τ' αλλαγάς σελιδίων με σύνδεσμον ασ' έναν συγκεκριμένον σελίδαν (για σε σελίδας συγκεκριμένου κατηγορίας).
Τα σελίδας σον [[Special:Watchlist|κατάλογον ωριαγματί]] είν' '''σκηρά'''.",
'recentchangeslinked-page'     => 'Όνεμαν σελίδας:',
'recentchangeslinked-to'       => "Δείξον τ'αλλαγάς τη σελιδίων π'δεκνίζνε αδακές",

# Upload
'upload'            => 'Φόρτωσον αρχείον',
'uploadbtn'         => 'Φόρτωσον αρχείον',
'reuploaddesc'      => 'Στα! Μην εφτάς το φόρτεμαν! Δέβα οπίς ση σελίδαν φωρτεματί!',
'uploadnologin'     => "'Κ είστουν απές. Εμπάτε σην λογαρίανεσουν.",
'uploadnologintext' => "Πρεπ' σην σελίδαν [[Special:UserLogin|απές]] να είσνε (log in) για πορείτε να φορτώνετε αρχεία.",
'uploaderror'       => 'Έντον λάθος σο φόρτωμαν',
'uploadlog'         => 'αρχείον με τα φορτώματα',
'uploadlogpage'     => 'Αρχείον ανεβασματίων',
'filename'          => 'Όνεμα αρχείου',
'filedesc'          => 'Σύνοψη',
'fileuploadsummary' => 'Σύνοψη:',
'filesource'        => 'Πηγήν:',
'uploadedfiles'     => 'Φορτωμένα αρχεία',
'minlength1'        => "Τ' ονέματα τ' αρχείον πρέπ' να έχνε έναν γράμμαν και κιαλλαπάν.",
'badfilename'       => 'Τόνεμαν ταρχείου ελλάγεν κιεγέντον "$1".',
'filetype-missing'  => "Τ' αρχείον τιδέν 'κ έχ' κατάληξην (άμον \".jpg\").",
'savefile'          => "Αποθήκεψον τ' αρχείον",
'uploadedimage'     => 'Εγέντον το φόρτωμαν τη "[[$1]]"',
'overwroteimage'    => 'Εφορτώθεν καινούρεον μορφή τη "[[$1]]"',
'watchthisupload'   => 'Ωρίαγμαν τη αρχείου',

'upload-file-error' => 'Σφάλμαν απές μερέαν',
'upload-misc-error' => 'Αναγνώριμον λάθος φορτωματί',

# Special:ListFiles
'imgfile'               => 'αρχείον',
'listfiles'             => 'Λίσταν εικονίων',
'listfiles_date'        => 'Ημερομηνία',
'listfiles_name'        => 'Όνεμαν',
'listfiles_user'        => 'Χρήστες',
'listfiles_size'        => 'Μέγεθος',
'listfiles_description' => 'Σχόλιον',
'listfiles_count'       => 'Εκδόσεις',

# File description page
'file-anchor-link'          => 'Εικόνα',
'filehist'                  => "Ιστορικόν τ' αρχείου",
'filehist-help'             => "Εφτάτε κλικ σ' έναν ημερομηνίαν/ώραν απάν αέτς για να τερείτε πως έτον τ' αρχείον σ' εκείνεν την ώραν.",
'filehist-deleteone'        => 'σβήσεμαν',
'filehist-revert'           => 'επαναφορά',
'filehist-current'          => 'υστερινά',
'filehist-datetime'         => 'Ώραν/Ημερομ.',
'filehist-thumb'            => 'Εικονιδίον',
'filehist-thumbtext'        => 'Εικονιδίον για την έκδοσην τη $1',
'filehist-nothumb'          => "'Κ εν γραφικήν σύνοψην (thumbnail)",
'filehist-user'             => 'Χρήστες',
'filehist-dimensions'       => 'Διαστάσεις',
'filehist-filesize'         => 'Μέγεθος',
'filehist-comment'          => 'Σχόλιον',
'imagelinks'                => 'Συνδέσμ αρχείων',
'linkstoimage'              => "Ατά τα {{PLURAL:$1|σελίδαν δεκνίζ'|$1 σελίδας δεκνίζ'νε}} σην εικόναν:",
'nolinkstoimage'            => "'Κ εχ σελίδας ντο δεκνίζνε σ' αβούτεν εικόναν.",
'sharedupload'              => "Αούτον τ' αρχείον εφορτώθεν ασό $1 κι επορούν και κουλανεύν'ατο σ' άλλα έργα.",
'sharedupload-desc-there'   => "Αούτον τ' αρχείον εφορτώθεν ασό $1. Κι άλλα έργα επορούν και κουλανέυν'ατο.
Δεαβάστεν τη [$2 file description page] αέτς για να μαθάνετε πολλά για τ'ατό.",
'sharedupload-desc-here'    => "Αούτον τ' αρχείον εφορτώθεν ασό $1 κι επορούν και κουλανεύν'ατο σ' άλλα έργα.
Το γράψιμον κιαλλ'αφκά αση [$2 file description page] ατ' εν κι εξηγίζ'ατο.",
'filepage-nofile'           => "Αΐκον αρχείον αδαπές 'κ εχ.",
'filepage-nofile-link'      => "Αρχείον μ' αΐκον τ'όνεμαν 'κ έχ', άμα επορείς να [$1 σκώντ'ς ατο].",
'uploadnewversion-linktext' => "Σκώσον καινούρεον έκδοση τ'ατουνού τ' αρχείου",
'shared-repo-from'          => 'ασό $1',

# File reversion
'filerevert'         => 'Κλώσιμον $1',
'filerevert-legend'  => 'Κλώσιμον αρχείου',
'filerevert-comment' => 'Σχόλιον:',
'filerevert-submit'  => 'Επαναφορά',

# File deletion
'filedelete-comment'          => 'Αιτία:',
'filedelete-submit'           => 'Σβήσον',
'filedelete-reason-otherlist' => 'Άλλον αιτία',
'filedelete-edit-reasonlist'  => "Άλλαξον τ' αιτίας σβησεματί",

# MIME search
'mimesearch' => 'Αράεμαν MIME',
'mimetype'   => 'Τύπον MIME:',

# List redirects
'listredirects' => 'Κατάλογον με διπλά συνδέσμ',

# Unused templates
'unusedtemplates'    => "Πρότυπα ντο 'κ μεταχειρίσκουνταν",
'unusedtemplateswlh' => 'άλλα συνδέζμ',

# Random page
'randompage' => 'Τυχαίον σελίδα',

# Random redirect
'randomredirect' => 'Τυχαία διπλά συνδέσμ',

# Statistics
'statistics'       => 'Στατιστικήν',
'statistics-pages' => 'Σελίδας',

'disambiguations' => 'Σελίδας εξηγησίων',

'doubleredirects' => 'Περισσά διπλά συνδέσμ',

'brokenredirects'        => 'Τσακωμένα διπλά συνδέσμ',
'brokenredirects-edit'   => 'άλλαγμαν',
'brokenredirects-delete' => 'σβήσεμαν',

'withoutinterwiki'        => "Σελίδας ντο κ' έχνε συνδέσμ",
'withoutinterwiki-legend' => 'Προθέκεμαν',
'withoutinterwiki-submit' => 'Δείξον',

'fewestrevisions' => "Σελίδας με τ' ασόλων λιγότερα αλλαγάς.",

# Miscellaneous special pages
'nbytes'                  => '$1 {{PLURAL:$1|byte|bytes}}',
'ncategories'             => '{{PLURAL:$1|κατηγορίαν|κατηγορίας}}',
'nlinks'                  => '$1 {{PLURAL:$1|σύνδεσμον|συνδέσμ}}',
'nmembers'                => '$1 {{PLURAL:$1|μέλος|μέλη}}',
'lonelypages'             => 'Ορφανά σελίδας',
'uncategorizedpages'      => "Σελίδας ντο 'κ έχνε κατηγορίαν",
'uncategorizedcategories' => "Κατηγορίας ντο 'κ έχνε κατηγορίας",
'uncategorizedimages'     => "Εικόνας ντο κ' έχνε κατηγορίαν",
'uncategorizedtemplates'  => "Πρότυπα ντο κ' έχνε κατηγορίαν",
'unusedcategories'        => 'Εύκαιρα κατηγορίας',
'unusedimages'            => "Εικόνας ντο κ' μεταχειρίσκουνταν",
'wantedcategories'        => 'Κατηγορίας το θέλουμε',
'wantedpages'             => 'Σελίδας το θέλουμε',
'mostlinked'              => "Σελίδας με τ' ασόλων πλέα σελίδας ντο δεκνίζν' εκαικά.",
'mostlinkedcategories'    => "Κατηγορίας με τ' ασόλων πλέα σελίδας ντο δεκνίζν' εκαικά.",
'mostlinkedtemplates'     => "Πρότυπα με τ' ασόλων πλέα σελίδας ντο δεκνίζν' εκαικά",
'mostcategories'          => "Σελίδας με τ' ασ' όλτς πολλά κατηγορίας",
'mostimages'              => "Αρχεία με τ' ασόλων πλέα σελίδας ντο δεκνίζν' εκαικά",
'mostrevisions'           => "Σελίδας με τ' ασόλων πλέα αλλαγάς",
'prefixindex'             => 'Κατάλογον σελιδίων κατά πρόθεμαν',
'shortpages'              => 'Μικρά σελίδας',
'longpages'               => 'Τρανά σελίδας',
'deadendpages'            => 'Αδιέξοδα σελίδας',
'protectedpages'          => 'Ασπαλιγμένα σελίδας',
'listusers'               => 'Κατάλογον χρηστίων',
'usereditcount'           => '$1 {{PLURAL:$1|άλλαγμαν|αλλάγματα}}',
'newpages'                => 'Καινούρεα σελίδας',
'newpages-username'       => 'Όνεμα χρήστε:',
'ancientpages'            => 'Ασ’ όλιον παλαιά σελίδας',
'move'                    => 'Ετεροχλάεμαν',
'movethispage'            => "Άλλαξον τ' όνεμα τη σελίδας",
'pager-newer-n'           => '{{PLURAL:$1|κιάλλο καινούρεο 1|κιάλλο καινούρεα $1}}',
'pager-older-n'           => '{{PLURAL:$1|κιάλλο παλαιόν 1|κιάλλο παλαιά $1}}',
'suppress'                => 'Επόπτες',

# Book sources
'booksources'               => 'Βιβλιογραφικά πηγάς',
'booksources-search-legend' => 'Αράεμαν τη βιβλίων',
'booksources-go'            => 'Δέβα',

# Special:Log
'specialloguserlabel'  => 'Χρήστες:',
'speciallogtitlelabel' => 'Τίτλος:',
'log'                  => 'Αρχεία',
'all-logs-page'        => 'Όλεα τα δημόσεα αρχεία',

# Special:AllPages
'allpages'       => 'Όλεα τα σελίδας',
'alphaindexline' => '$1 ους $2',
'nextpage'       => 'Επόμενον σελίδα ($1)',
'prevpage'       => 'Προηγούμενον σελίδα ($1)',
'allpagesfrom'   => "Τέρεμαν σελιδίων ντ' εσκαλών'νε ασό:",
'allpagesto'     => "Δείξον τα σελίδας π' τελειών'νε σε:",
'allarticles'    => 'Όλεα τα σελίδας',
'allpagesprev'   => 'Προτεσνά',
'allpagesnext'   => 'Επόμενα',
'allpagessubmit' => 'Δέβα',
'allpagesprefix' => 'Τέρεμαν σελιδίων με πρόθεμαν:',

# Special:Categories
'categories'         => 'Κατηγορίας',
'categoriespagetext' => "{{PLURAL:$1|Η αφκά κατηγορίαν εχ|Τ' αφκά τα κατηγορίας έχνε}} απές σελίδας και μέσα. [[Special:UnusedCategories|Κατηγορίας που 'κ εμεταχειρίσκουνταν]] 'κ επορείτε να ελέπετε τα αδακά.
Τερέστεν και τα [[Special:WantedCategories|κατηγορίας που χρειάσκουνταν]].",

# Special:DeletedContributions
'sp-deletedcontributions-contribs' => "δουλείας ντ' εποίκε",

# Special:LinkSearch
'linksearch'    => 'Συνδέσμαι',
'linksearch-ns' => 'Περιοχή ονοματίων:',
'linksearch-ok' => 'Αράεμαν',

# Special:ListUsers
'listusers-submit' => 'Δείξον',

# Special:Log/newusers
'newuserlogpage'          => 'Αρχείον ποισιματίων λογαρίων χρήστε',
'newuserlog-create-entry' => 'Νέον χρήστες',

# Special:ListGroupRights
'listgrouprights-group'   => 'Ομάδαν',
'listgrouprights-rights'  => 'Δικαιώματα',
'listgrouprights-members' => '(κατάλογον μελών)',

# E-mail user
'emailuser'    => 'Στείλον μένεμαν σον χρήστεν ατόν',
'emailfrom'    => 'Ασά:',
'emailto'      => 'Σο:',
'emailsubject' => 'Θέμαν:',
'emailmessage' => 'Μένεμαν:',
'emailsend'    => 'Αποστολήν',

# Watchlist
'watchlist'         => "Σελίδας ντ' ωριάζω",
'mywatchlist'       => "Σελίδας ντ' ωριάζω",
'watchlistfor'      => "(για '''$1''')",
'watchnologin'      => "'Κ είστουν συνδεμένος",
'addedwatch'        => 'Εθέκεν σην λίσταν ωριαγματί',
'addedwatchtext'    => "Η σελίδαν \"[[:\$1]]\" επήγεν σον [[Special:Watchlist|κατάλογον οριαγματί]] εσούν.
Μελλούμενα αλλαγάς τ' ατεινές τη σελίδας θα γράφκουνταν καικά, και η σελίδαν θ' ευρίεται με γράμματα '''χοντρά''' σ' [[Special:RecentChanges|υστερνά τ' αλλαγάς]] για να τερείτετα καλίον.",
'removedwatch'      => 'Αση λίσταν επάρθεν',
'removedwatchtext'  => 'Η σελίδαν "[[:$1]]" ενεσβύεν ασ\' [[Special:Watchlist|τ\'εσόν τον κατάλογον]].',
'watch'             => 'Ωρίαγμαν',
'watchthispage'     => 'Ωρίαν τη σελίδαν',
'unwatch'           => "Τέλεμαν τ' ωριαγματί",
'unwatchthispage'   => 'Τέλεμαν ωριαγματί',
'watchlist-details' => '{{PLURAL:$1|$1 σελίδα|$1 σελίδας}} ωριάσκουνταν, θέγα τα σελίδας καλατσεματί.',
'wlshowlast'        => "Φανέρωμαν τ' υστερναίων $1 ωρίων $2 ημερίων $3",
'watchlist-options' => 'Επιλογάς ωριαγματί',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'Ωριάζω...',
'unwatching' => "'κ ωριάζω...",

'enotif_impersonal_salutation' => '{{SITENAME}} χρήστες',
'changed'                      => 'ελλάγεν',
'created'                      => 'έντον',
'enotif_anon_editor'           => 'ανώνυμον χρήστες $1',

# Delete
'deletepage'            => 'Σβήσον τη σελίδαν',
'exblank'               => 'σελίδα έτον εύκαιρον',
'delete-confirm'        => 'Σβήσον "$1"',
'delete-legend'         => 'Σβήσεμαν',
'historywarning'        => "Ωρία: Η σελίδαν που θα σβήετε έχ' ιστορικόν:",
'confirmdeletetext'     => "Είστουν σουμά σο σβήσεμαν είνος σελίδας και ούλ' τ' ιστορίασατς εντάμαν.
Παρακαλούμε σας να δείτε το τελικόν τη βεβαίωσην το θέλετε να εφτάτε το σβήσεμαν, τ' εγροικάτε τα συνέπειας τ' ατεινές τη πράξης και τ' εφτάτ' ατεν με βάσην [[{{MediaWiki:Policy-url}}|τη πολιτικήν]].",
'actioncomplete'        => 'Η ενέργειαν ετελέθεν',
'deletedtext'           => 'Το "<nowiki>$1</nowiki>" εσβήγανατο.
Τερέστεν το $2 και δεαβάστεν για τα υστερνά τα σβησίματα.',
'deletedarticle'        => 'ενεσβύεν η "[[$1]]"',
'dellogpage'            => "Κατάλογον με τ' ατά ντ' ενεσβύγαν",
'deletionlog'           => 'αρχείον ασπαλιγματίων',
'deletecomment'         => 'Αιτία:',
'deleteotherreason'     => 'Άλλον/αλλομίαν λόγον:',
'deletereasonotherlist' => 'Άλλον λόγον',

# Rollback
'rollback'       => 'Φέρον ξαν σην υστερναίαν',
'rollback_short' => 'Επαναφοράν',
'rollbacklink'   => 'φέρον ξαν σην υστερναίαν',

# Protect
'protectlogpage'              => 'Αρχείον ασπαλιγματίων',
'protectedarticle'            => 'ασπαλιζμένον "[[$1]]"',
'modifiedarticleprotection'   => 'έλλαξεν τ\'ωρίαγμαν για "[[$1]]"',
'prot_1movedto2'              => '[[$1]] ετερχλαεύτεν σο [[$2]]',
'protectcomment'              => 'Αιτίαν:',
'protectexpiry'               => 'Τελείται:',
'protect_expiry_invalid'      => "Ο χρόνον τελεματί 'κ εν σωστόν.",
'protect_expiry_old'          => 'Ο χρόνον τελεματί πέρνιξον.',
'protect-text'                => "Αδά επορείτε να τερείτε και ν' αλλάζετε τ' επίπεδον τη προστασίας για τη σελίδαν '''<nowiki>$1</nowiki>'''.",
'protect-locked-access'       => "Η λογαρίανεσουν 'κ έχ' το δικαίωμαν να αλλάζ' τ' ασπάλιγμαν τη σελίδας.
Αδά έχ' τ' ατωριζνά τα νομς για τη σελίδαν '''$1''':",
'protect-cascadeon'           => "Αβούτη η σελίδα ατώρα εν ασπαλιγμένον: Εν απές {{PLURAL:$1|σ' ακόλουθουν τη σελίδαν, ντο έχ'|σ' ακόλουθα τα σελίδας, τ' έχνε}} ενεργοποιημένον το διαδοχικόν τ' ασπάλιγμαν. Πορείτε ν' ελλάζετε το επίπεδον ασπαλιγματί τη σελίδας, άμα αβούτο ξάι 'κ θ' αλλάζ' το διαδοχικόν τ' ασπάλιγμαν.",
'protect-default'             => "Επιτρέπ' ολς τοι χρηστς",
'protect-fallback'            => 'Ψαλαφίον δικαιωματίων "$1"',
'protect-level-autoconfirmed' => 'Ασπάλιγμαν καινούρεων χρηστίων και θίχως λογαρίαν',
'protect-level-sysop'         => 'Νοματέοι μαναχόν',
'protect-summary-cascade'     => 'διαδοχικόν',
'protect-expiring'            => 'λήγει στις $1 (UTC)',
'protect-expiry-indefinite'   => 'αόριστον',
'protect-cascade'             => "Ασπάλιγμαν σελιδίων ντ' είν απές σ' αβούτεν σελίδαν (διαδοχικόν προστασίαν)",
'protect-cantedit'            => "'Κι έχετε δικαίωμαν ν' αλλάζετε τ' επίπεδον ασπάλιγματι τ' ατεινές σελίδας.",
'protect-expiry-options'      => '1 ώραν:1 hour,1 ημέραν:1 day,1 εβδομάδαν:1 week,2 εβδομάδας:2 weeks,1 μήναν:1 month,3 μήνας:3 months,6 μήνας:6 months,1 χρόνον:1 year,αόριστα:infinite',
'restriction-type'            => 'Δικαίωμαν:',
'restriction-level'           => 'Επίπεδον περιορισμού:',
'pagesize'                    => '(bytes)',

# Restrictions (nouns)
'restriction-edit'   => 'Ἀλλαγμαν',
'restriction-move'   => 'Ετεροχλάεμαν',
'restriction-create' => 'Ποίσον',
'restriction-upload' => "Σκώσ' ατό",

# Undelete
'undeletebtn'               => 'Ποίσον ξαν',
'undeletelink'              => 'τέρεμαν/επαναφορά',
'undeleteviewlink'          => 'τέρεμα',
'undeletecomment'           => 'Σχόλιον:',
'undeletedarticle'          => 'επαναφορά τη "[[$1]]"',
'undelete-search-box'       => "Αράεμαν σελιδίων ντ'ενεσβύαν",
'undelete-search-submit'    => 'Εύρον',
'undelete-show-file-submit' => 'Ναι',

# Namespace form on various pages
'namespace'      => 'Περιοχήν:',
'invert'         => "Αντιστροφή τ' επιλογής",
'blanknamespace' => '(Αρχικόν περιοχή)',

# Contributions
'contributions'       => "Δουλείας ντ' εποίκε ο χρήστες",
'contributions-title' => "Δουλείας ντ' εποίκε ο χρήστες $1",
'mycontris'           => "Δουλείας ντ' εποίκα",
'contribsub2'         => 'Για τον/την $1 ($2)',
'uctop'               => '(υστερνά)',
'month'               => 'Ασόν μήναν (και πριχού):',
'year'                => 'Ασή χρονίαν (και πριχού):',

'sp-contributions-newbies'     => 'Τέρεμαν γραψιματίων τη καινούρεων λογαρίων μαναχόν',
'sp-contributions-newbies-sub' => 'Για τα καινούρεα τοι λογαρίας',
'sp-contributions-blocklog'    => 'Αρχείον ασπαλιγματίων',
'sp-contributions-logs'        => 'αρχεία',
'sp-contributions-talk'        => 'καλάτσεμαν',
'sp-contributions-search'      => 'Εύρον συνεισφοράντας',
'sp-contributions-username'    => 'Διεύθυνσην IP γιά όνεμαν χρήστε:',
'sp-contributions-submit'      => 'Αράεμαν',

# What links here
'whatlinkshere'            => "Ντο δεκνίζ' αδακές",
'whatlinkshere-title'      => 'Σελίδας ντο συνδέουν ση σελίδαν $1',
'whatlinkshere-page'       => 'Σελίδαν:',
'linkshere'                => "Αβούτα τα σελίδας δεκνίζνε σο '''[[:$1]]''':",
'nolinkshere'              => "'Κ ευρέθεν σελίδα το δεκνίζ' ση σελίδαν '''[[:$1]]'''.",
'isredirect'               => 'σελίδαν διπλού σύνδεσμονος',
'istemplate'               => 'ενσωμάτωση',
'isimage'                  => 'σύνδεσμον εικόνας',
'whatlinkshere-prev'       => '{{PLURAL:$1|προτεσνή|προτεσνά $1}}',
'whatlinkshere-next'       => '{{PLURAL:$1|υστερνή|υστερναία $1}}',
'whatlinkshere-links'      => '← συνδέσμ',
'whatlinkshere-hideredirs' => '$1 τα διπλά οι συνδέσμαι',
'whatlinkshere-hidetrans'  => '$1 υπερκλεισμοί',
'whatlinkshere-hidelinks'  => '$1 συνδέσμαι',
'whatlinkshere-hideimages' => '$1 συνδέσμαι εικονίων',
'whatlinkshere-filters'    => 'Φίλτρα',

# Block/unblock
'blockip'                  => 'Ασπάλιγμαν τη χρήστε',
'blockip-legend'           => 'Ασπάλισον το χρήστ',
'ipbexpiry'                => 'Τέλεμαν:',
'ipbreason'                => 'Αιτία:',
'ipbreasonotherlist'       => 'Άλλον αιτία',
'ipbanononly'              => "Ασπάλισον τ'ανώνυμους τη χρήστες μαναχόν",
'ipbsubmit'                => 'Ασπάλισον τον χρήστεν',
'ipbother'                 => 'Άλλον ώρα:',
'ipboptions'               => '2 ώρας:2 hours,1 ημέρα:1 day,3 ημέρας:3 days,1 εβδομάδα:1 week,2 εβδομάδας:2 weeks,1 μήνα:1 month,3 μήνας:3 months,6 μήνας:6 months,1 χρόνο:1 year,αόριστα:infinite',
'ipbotheroption'           => "άλλ'",
'ipbotherreason'           => 'Άλλον/κιάλλον αιτία:',
'badipaddress'             => 'Άχρηστον IP',
'blockipsuccesssub'        => "Τ' ασπάλιγμαν εγέντον",
'ipb-edit-dropdown'        => 'Άλλαξον αιτίας ασπαλιγματί',
'ipblocklist'              => 'Ασπαλιγμένα IP και λογαρίας',
'ipblocklist-submit'       => 'Εύρον',
'infiniteblock'            => 'άπειρον',
'blocklink'                => 'Ασπάλιγμαν',
'unblocklink'              => 'άνοιγμαν ασπαλιγματί',
'change-blocklink'         => "άλλαξον τ'ασπάλιγμαν",
'contribslink'             => "Δουλείαν ατ'",
'blocklogpage'             => 'Αρχείον ασπαλιγματίων',
'blocklogentry'            => 'εσπάλισεν [[$1]] για $2 $3',
'unblocklogentry'          => 'άνοιγμαν ασπαλιγματί τη $1',
'block-log-flags-nocreate' => "ποίσιμον λογαρίας 'κ ίνεται",
'blockme'                  => 'Ασπάλισον με',
'proxyblocksuccess'        => 'Εγέντον.',

# Developer tools
'lockdb'              => 'Ασπάλιγμαν βάσης δογμενίων',
'unlockdb'            => 'Άνοιγμαν βάσης δογμενίων',
'lockconfirm'         => "Ναι, θέλω ν' ασπαλίζω τη βάση δογμενίων.",
'unlockconfirm'       => "Ναι, θέλω ν' ανοίγω τη βάση δογμενίων.",
'lockbtn'             => 'Ασπάλισον βάση δογμενίων',
'unlockbtn'           => 'Άνοιξον βάση δογμενίων',
'lockdbsuccesssub'    => "Έντον τ' ασπάλιγμαν τη βάσης δογμενίων",
'unlockdbsuccesssub'  => "Έντον τ' άνοιγμαν τη βάσης δογμενίων",
'lockdbsuccesstext'   => "Η βάση δογμενίων εν ασπαλιγμένον.<br />
Μην ανασπάλλετε [[Special:UnlockDB|ν' ανοίγετατεν]] όνταν η δουλείανεσουν εν κυρομένον.",
'unlockdbsuccesstext' => "Έντον τ' άνοιγμαν τη βάσης δογμενίων.",
'databasenotlocked'   => "Η βάση δογμενίων 'κ εν ασπαλιγμένον.",

# Move page
'move-page'               => 'Ετεροχλάεμαν $1',
'move-page-legend'        => 'Ετεροχλάεμαν σελίδας',
'movepagetext'            => "Εάν εφτάτε το ψαλαφίον αφκά θα δείτε άλλον όνομαν σ' έναν σελίδαν και θα παίρτεν τ' ιστορικόνατς εκαικά. Το παλαιόν η σελίδαν θα μεταβάλκεται σε σύνδεσμον σην καινούραιαν.

Επορείτε να μεταβάλκετε τα συνδέσμαι που δεκνίζνε σο παλαιόν τη σελίδαν αυτόματα. Εάν 'κ φτάτε αέτς,
ευρέστεν [[Special:DoubleRedirects|διπλά]] για [[Special:BrokenRedirects|τσακωμένα συνδέσμ]].
Έχετ' ευθύνην τα παλαιά τα συνδέσμαι να δεκνίζνε σο σωστόν τη σελίδαν.

Η σελίδαν ''''κ θ' αλλάζ'''' τη θέσηνατς όντες έχ' άλλον σελίδαν με το νέον τ' όνεμαν. Εξαίρεσην εν τ' εύκαιρα τα σελίδας και τα συνδέσμαι, ντο 'κ έχνε ιστορικόν.
Επορείτε δηλαδή να παίρετε τη σελίδαν σ' όνομαν ντ' είχεν προτεσνά. Άμα 'κ επορείτε με το ετεροχλάεμαν να σβήετε άλλον σελίδαν.

'''ΩΡΙΑ!'''
Αβούτεν η ενέργειαν επορεί να φέρει τρανά διαφοράς σ' έναν σελίδαν που δεβάζνε πολλοί.
Νουνίστενατο καλά πριχού να εφτάτε τ' άλλαγμαν τ' ονοματί.",
'movepagetalktext'        => "Η σελίδαν καλατσεματί αυτόματα θα πηγαίν' εντάμαν, '''εξόν:'''
*Έχ' άλλον σελίδαν καλατσεματί ντο 'κ εν εύκαιρον άμα έχ' το ίδιον τ' όνεμαν
*θα ευκαιρώνετε το χουτίν αφκά.

Εάν θέλετε να εφτάτε τα ένωμαν, να εφτάτε ατό με copy και paste.",
'movearticle'             => 'Ετεροχλάεμαν σελίδας:',
'newtitle'                => 'Νέον τίτλον:',
'move-watch'              => 'Ωρίαγμαν τη σελίδας',
'movepagebtn'             => 'Ετεροχλάεμαν σελίδας',
'pagemovedsub'            => 'Ετερχλαεύτεν',
'movepage-moved'          => '\'\'\'"$1" επήγεν σο "$2"\'\'\'',
'articleexists'           => 'Σελίδαν με αΐκον όνεμαν υπάρχει.
Βαλέστεν άλλο όνεμαν.',
'cantmove-titleprotected' => "'Κ επορείτε ν' εφτάτε σελίδαν με τ' αβούτον τ' όνεμαν επειδή εσπάλισανατο.",
'talkexists'              => "'''Η σελίδαν ετερχλαεύτεν, άμαν η σελίδαν καλατσεματί επέμνεν επειδή σο καινούρεον τίτλον έχ' άλλον σελίδα.
Ποισέστεν τα έναν.'''",
'movedto'                 => 'ετεροχλαεύτεν σο',
'movetalk'                => 'Ετεροχλάεμαν τη σελίδας καλατσεματί',
'1movedto2'               => '[[$1]] ετερχλαεύτεν σο [[$2]]',
'1movedto2_redir'         => '[[$1]] ετερχλαεύτεν σο [[$2]] σε σύνδεσμον απάν',
'movelogpage'             => 'Αρχείον ετεροχλαεματί',
'movereason'              => 'Λόγον:',
'revertmove'              => 'κλώσιμον',
'delete_and_move'         => 'Σβήσον και ετεροχλάεψον',

# Export
'export'            => 'Εξαγωγήν σελίδιων',
'export-addcattext' => 'Βαλέστεν σελίδας ασήν κατηγορίαν:',
'export-addcat'     => 'Βαλέστεν',
'export-addns'      => 'Προστήκην',
'export-download'   => 'Αποθήκεμαν άμον αρχείον',

# Namespace 8 related
'allmessages'               => 'Μενέματα συστηματί',
'allmessagesname'           => 'Όνεμαν',
'allmessages-filter-legend' => 'Φίλτρον',
'allmessages-language'      => 'Λαλίαν:',

# Thumbnails
'thumbnail-more'  => 'Ποίσον κι άλλο τρανόν',
'filemissing'     => "Λειπ' τ' αρχείον",
'thumbnail_error' => 'Έντον λάθος ση δημιουργίαν τη μικρογραφίας: $1',

# Special:Import
'import'                  => 'Έμπαζμαν σελιδίων',
'import-interwiki-submit' => 'Έμπαζμαν',
'import-comment'          => 'Σχόλιον:',
'importstart'             => 'Έμπαζμαν σελιδίων...',
'import-noarticle'        => "'Κ εχ' σελίδαν για έμπαζμαν!",

# Import log
'importlogpage'             => 'Αρχείον εμπαζματίων',
'import-logentry-interwiki' => 'εγέντον εισαγωγήν transwiki σην σελίδαν $1',

# Tooltip help for the actions
'tooltip-pt-userpage'             => "Τ' εσόν η σελίδαν",
'tooltip-pt-mytalk'               => "Τ' εσόν το καλάτσεμαν",
'tooltip-pt-preferences'          => "Τ' εμά τα προτιμήσεις",
'tooltip-pt-watchlist'            => "Λίστα με τα σελίδας ντ' ωριάζω",
'tooltip-pt-mycontris'            => "Λίστα με τα δουλείας ντ' εποίκες",
'tooltip-pt-login'                => "Μπορείτε νε εφτάτε λογαρίαν άμα 'κ πρεπ'.",
'tooltip-pt-logout'               => 'Απιδεβένετεν τη Βικιπαίδειαν',
'tooltip-ca-talk'                 => "Γονούσεμαν γι' αβούτον τ' άρθρον",
'tooltip-ca-edit'                 => "Άλλαγμαν τη σελίδας. Άμαν τερέστεν τ' αλλαγάς πριν θα κρατείτε ατά.",
'tooltip-ca-addsection'           => "Αρχίνεστε καινούρεον κομμάτ'.",
'tooltip-ca-viewsource'           => "Ατό η σελίδαν εν ασπαλιγμένον. Άμαν μπορείτε να τερείτε το κείμενον ατ'ς.",
'tooltip-ca-history'              => 'Παλαιά εκδώσεις τη σελίδας.',
'tooltip-ca-protect'              => 'Ασπάλιγμα τη σελίδας',
'tooltip-ca-delete'               => 'Σβήσεμαν τη σελίδας',
'tooltip-ca-move'                 => "Κότζεμαν τη σελίδας ας έναν τίτλον σ' άλλον.",
'tooltip-ca-watch'                => 'Ωρίαγμαν τη σελίδας',
'tooltip-ca-unwatch'              => 'Έπαρ αβούτεν τη σελίδαν αση λίσταν ωρίαγματι.',
'tooltip-search'                  => 'Εύρον σο {{SITENAME}}',
'tooltip-search-go'               => 'Δέβα σε σελίδαν με αΐκον όνεμαν αν υπάρχει',
'tooltip-search-fulltext'         => 'Εύρον αούτον το κείμενον',
'tooltip-p-logo'                  => "Δεβάτεν σ'αρχικόν τη σελίδαν",
'tooltip-n-mainpage'              => 'Τερέστεν το αρχικόν τη σελίδαν',
'tooltip-n-mainpage-description'  => 'Τερέστεν το αρχικόν τη σελίδαν',
'tooltip-n-portal'                => 'Σχετικά με το Wiκi - πώς μπορείτε να εφτάτε γιαρτήμ, πού θα ευρίετε πράγματα',
'tooltip-n-currentevents'         => "Εύρον άλλα πληροφορίας για τ' ατά ντ'εγένταν οψεκές.",
'tooltip-n-recentchanges'         => "Κατάλογον με τ' υστερνά αλλαγάς σο wiki.",
'tooltip-n-randompage'            => 'Κατά τύχην εύρον σελίδαν και δείξον ατέν',
'tooltip-n-help'                  => "Αδά θα ευρίετε τα απαντήσεις ντ' αραεύετε.",
'tooltip-t-whatlinkshere'         => "Ούλ' τ' άρθρα ντο δεκνίζνε σ'αούτο το άρθρον",
'tooltip-t-recentchangeslinked'   => "Υστερνά αλλαγάς σε σελίδας με συνδέσμ' απ'αδακά.",
'tooltip-feed-rss'                => 'RSS συνδρομή για την σελίδαν ατέν',
'tooltip-feed-atom'               => 'Atom συνδρομή για την σελίδαν ατέν',
'tooltip-t-contributions'         => 'Τερέστεν τη λίσταν με τα συνεισφοράντας τη χρήστε',
'tooltip-t-emailuser'             => "E-mail σ' αούτον το χρήστεν",
'tooltip-t-upload'                => 'Φόρτωμαν αρχείων',
'tooltip-t-specialpages'          => 'Κατάλογον με τα ειδικά σελίδας',
'tooltip-t-print'                 => 'Εκτυπώσιμον μορφή τη σελίδας',
'tooltip-t-permalink'             => "Μόνιμον σύνδεσμος σ'αούτο τη μορφήν τη σελίδας",
'tooltip-ca-nstab-main'           => 'Τέρεμαν σελίδας περιεχομενίων',
'tooltip-ca-nstab-user'           => 'Τέρεμαν τη σελίδας χρήστε',
'tooltip-ca-nstab-media'          => 'Τέρεμαν τη σελίδας μεσίων',
'tooltip-ca-nstab-special'        => "Ατό η σελίδαν εν ειδικόν. Ξάι 'κ επορείτε να αλλάζετατεν.",
'tooltip-ca-nstab-project'        => 'Τέρεμαν σελίδας συστηματί',
'tooltip-ca-nstab-image'          => 'Τέρεμαν εικόνας',
'tooltip-ca-nstab-mediawiki'      => 'Τέρεμαν μενεματίων συστηματί',
'tooltip-ca-nstab-template'       => 'Τέρεμαν προτυπίων',
'tooltip-ca-nstab-help'           => 'Τέρεμαν τη σελίδας βοήθειας',
'tooltip-ca-nstab-category'       => 'Τέρεμαν τη σελίδας κατηγορίας',
'tooltip-minoredit'               => 'Όντες εφτάτε μικρόν αλλαγήν',
'tooltip-save'                    => "Αποθήκεμαν τ' αλλαγίων",
'tooltip-preview'                 => "Τέρεν τ' αλλαγάς πριχού να κρατείς τη σελίδαν!",
'tooltip-diff'                    => "Τέρεμαν τ' αλλαγίων ντ' εποίκατε σο κείμενον.",
'tooltip-compareselectedversions' => "Τερέστε τα διαφοράς τ' εκδωσίων τη σελίδας",
'tooltip-watch'                   => 'Βαλέστεν την σελίδαν σην λίσταν ωριαγματί νεσουν',
'tooltip-rollback'                => "Μ'έναν κλικ σην \"αναστροφήν\" θα χάται τ' υστερνόν η αλλαγή σ'αούτον τη σελίδαν.",
'tooltip-undo'                    => 'Με την "Αναίρεση" χάται αούτον η αλλαγή και ανοίγ\' η φόρμα αλλαγματί άμον πρώτον τέρεμαν. Επιτρέπ\' την προσθήκην αιτίας ση περίληψην.',

# Attribution
'siteuser' => '{{SITENAME}} χρήστες $1',
'others'   => "άλλ'",

# Spam protection
'spamprotectiontitle' => 'Φίλτρον προστασίας ασό σπαμ',

# Math errors
'math_unknown_function' => 'άγνωρος συνάρτησην',
'math_lexing_error'     => 'σφάλμαν λεξικής ανάλυσης',
'math_syntax_error'     => 'σφάλμαν σύνταξης',

# Patrol log
'patrol-log-auto' => '(αυτόματον)',

# Browsing diffs
'previousdiff' => '← Προτεσνόν διαφορά',
'nextdiff'     => 'Άλλον διαφορά →',

# Media information
'widthheightpage'      => '$1×$2, $3 {{PLURAL:$3|σελίδα|σελίδας}}',
'file-info-size'       => '($1 × $2 εικονοστοιχεία, μέγεθος αρχείου: $3, MIME τύπον: $4)',
'file-nohires'         => "<small>'Κ εχ κι άλλο ψηλόν ανάλυσην.</small>",
'svg-long-desc'        => "(Αρχείον SVG, κατ' όνομα $1 × $2 εικονοστοιχεία, μέγεθος αρχεί: $3)",
'show-big-image'       => 'Τζιπ τρανόν ανάλυση',
'show-big-image-thumb' => "<small>Μέγεθος τη πρώτ' τερεματί: $1 × $2 εικονοστοιχεία</small>",

# Special:NewFiles
'newimages'        => 'Τερέστεν τα καινούρεα φωτογραφίας',
'newimages-legend' => 'Φίλτρον',
'showhidebots'     => '($1 μποτ)',
'ilsubmit'         => 'Αράεμαν',
'bydate'           => 'ημερομηνίας',

# Bad image list
'bad_image_list' => "Η σύνταξην εν αέτς:

Τα αντικείμενα τη λίστας (τα γραμμάς ντ' αχπάσκουνταν με *) και μόνον τερούμε. Ο πρώτον ο σύνδεσμον σε μιαν γραμμήν πρέπ' να δεκνίζ' σε κακόν αρχείον.
Ήντιαν συνδέσμ' ντ' έρταν ασην ίδιαν γραμμήν οπίς θεωρούματα εξαιρέσεις, δηλαδή σελίδας όπου επορούμ' να συναντούμε την εικόναν σε σύνδεσην.",

# Metadata
'metadata'          => 'Μεταδογμένα',
'metadata-help'     => "Αβούτον τ' αρχείον εχ' κιάλλα πληροφορίας, ίσως ασόν ψηφιακόν τη κάμεραν για το σαρωτήν το μεταχειρίσκουτον για να ίνεται.
Τ' αρχείον αν έλλαξεν μορφήν, τα στοιχεία ίσως κ' είν' σωστά πλέον.",
'metadata-expand'   => 'Δείξον τα λεπτομέρειας',
'metadata-collapse' => 'Κρύψον τα λεπτομέρειας',
'metadata-fields'   => "Τα πεδία μεταδογμενίων EXIF τ' έχ' σ' αβούτον το μένεμαν θ'
ευρίεται σην σελίδαν εμφάνισης εικόνας όντες ο πίνακας μεταδογμενίων
θα κρύφκεται. Τ' άλλα τα πεδία θα είναι κρυμμένα εξόν ντο κανονίσκουνταν αλλέτερα.
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* isospeedratings
* focallength",

# EXIF tags
'exif-imagewidth'                  => 'Πλάτος',
'exif-imagelength'                 => 'Ύψηλος',
'exif-bitspersample'               => 'Bits ανά στοιχείο',
'exif-compression'                 => 'Σχήμα συμπίεσης',
'exif-photometricinterpretation'   => 'Σύνθεση τη pixel',
'exif-orientation'                 => 'Προσανατολισμός',
'exif-samplesperpixel'             => 'Αριθμός στοιχείων',
'exif-ycbcrsubsampling'            => 'Αναλογικόν δείγμαν σε φωτεινότητα και χρώμαν',
'exif-ycbcrpositioning'            => 'Ρύθμιζμαν φωτεινότητας και χρωματί',
'exif-xresolution'                 => 'Οριζόντιον ανάλυση',
'exif-yresolution'                 => 'Κατακόρυφον ανάλυση',
'exif-resolutionunit'              => 'Μονάδα μέτρησης ανάλυσης X και Y',
'exif-stripoffsets'                => 'Τοποθέτηση δογμενίων εικόνας',
'exif-stripbytecounts'             => 'Bytes ανά συμπιεσμένον λωρίδα',
'exif-jpeginterchangeformat'       => 'Μετάθεση σε JPEG SOI',
'exif-jpeginterchangeformatlength' => 'Bytes δογμενίων JPEG',
'exif-transferfunction'            => 'Λειτουργία μεταφοράς',
'exif-whitepoint'                  => "Χρωματικόν προσδιορισμός τ' άσπρου",
'exif-primarychromaticities'       => 'Πρωτεύοντες χρωματισμοί',
'exif-imagedescription'            => 'Τίτλος εικόνας',
'exif-exposuretime-format'         => '$1 δευ ($2)',
'exif-fnumber'                     => 'Αριθμός F',
'exif-flash'                       => 'Φλάς',
'exif-contrast'                    => 'Αντίθεσην',
'exif-gpslatitude'                 => 'Γεωγραφικόν πλάτος',
'exif-gpslongitude'                => 'Γεωγραφικόν μήκος',
'exif-gpsaltitude'                 => 'Υψόμετρον',
'exif-gpsspeedref'                 => 'Μονάδα ταχύτητας',

'exif-orientation-1' => 'Νορμάλ',

'exif-subjectdistance-value' => '$1 μέτρα',

'exif-meteringmode-0'   => 'Άγνωστον',
'exif-meteringmode-3'   => 'Μονοσημειακόν',
'exif-meteringmode-255' => 'Άλλον',

'exif-lightsource-4' => 'Φλας',

'exif-focalplaneresolutionunit-2' => 'ίντζας',

'exif-gaincontrol-0' => 'Τιδέν',

'exif-subjectdistancerange-1' => 'Macro',

'exif-gpsstatus-v' => 'Διαλειτουργικότητα μετρησίων',

# Pseudotags used for GPSSpeedRef
'exif-gpsspeed-m' => 'Μίλιαν την ώραν',
'exif-gpsspeed-n' => 'Κορδίλαι',

# External editor support
'edit-externally'      => "Αλλαγήν τ' αρχείου με προγράμματα ασα εξ μερέα",
'edit-externally-help' => '(Τερέστεν τα [http://www.mediawiki.org/wiki/Manual:External_editors setup instructions] και θα ευρίετε κι άλλα πληροφορίας)',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'ούλαι',
'imagelistall'     => 'ούλαι',
'watchlistall2'    => 'ούλαι',
'namespacesall'    => 'ούλαι',
'monthsall'        => 'ούλαι',

# Trackbacks
'trackbackremove' => '([$1 Σβήσον])',

# Delete conflict
'recreate' => 'Ποίσον αξάν',

# action=purge
'confirm_purge_button' => 'Εγέντον',

# Multipage image navigation
'imgmultipageprev' => '← πρωτεζνόν σελίδα',
'imgmultipagenext' => 'επόμενον σελίδα →',
'imgmultigo'       => 'Δέβα!',
'imgmultigoto'     => 'Δέβα σην σελίδαν $1',

# Table pager
'ascending_abbrev'         => 'ανεβ',
'descending_abbrev'        => 'κατεβ',
'table_pager_next'         => 'Επόμενον σελίδα',
'table_pager_prev'         => 'Πρωτεζνόν σελίδα',
'table_pager_first'        => 'Πρώτον σελίδα',
'table_pager_last'         => 'Τελευταίον σελίδα',
'table_pager_limit_submit' => 'Δέβα',

# Auto-summaries
'autosumm-new' => "Καινούρεον σελίδαν με '$1'",

# Watchlist editor
'watchlistedit-raw-titles' => 'Τιτλ:',

# Watchlist editing tools
'watchlisttools-view' => 'Τερέστεν σοβαρά αλλαγάς',
'watchlisttools-edit' => 'Τέρεν κι άλλαξον κατάλογον ωρίαγματι',
'watchlisttools-raw'  => 'Επεξεργαστείτε την πρωτογενή τη λίσταν ωριαγματί',

# Special:Version
'version'                  => 'Έκδοση',
'version-extensions'       => "Επεκτάσεις ντ'εθέκαν",
'version-specialpages'     => 'Ειδικά σελίδας',
'version-variables'        => 'Μεταβλητάς',
'version-other'            => 'Αλλέτερα',
'version-hooks'            => 'Αγκιστρία',
'version-license'          => 'Ἀδεια',
'version-software'         => "Λογισμικόν ντ'εθέκεν",
'version-software-version' => 'Έκδοση',

# Special:FilePath
'filepath-page'   => 'Αρχείον:',
'filepath-submit' => 'Δέβα',

# Special:FileDuplicateSearch
'fileduplicatesearch-filename' => 'Όνεμα αρχείου:',
'fileduplicatesearch-submit'   => 'Εύρον',

# Special:SpecialPages
'specialpages'                 => 'Ειδικά σελίδας',
'specialpages-group-other'     => 'Αλλέτερα ειδικά σελίδας',
'specialpages-group-pagetools' => 'Εργαλεία σελίδας',
'specialpages-group-spam'      => 'Εργαλεία αντι-σπάμ',

# Special:BlankPage
'blankpage' => 'Κενόν σελίδα',

# Special:Tags
'tag-filter'           => 'Φίλτρον [[Special:Tags|ετικέτας]]:',
'tag-filter-submit'    => 'Φίλτρον',
'tags-title'           => 'Ετικέτας',
'tags-hitcount-header' => 'Αλλαγάς με ετικέτας',
'tags-edit'            => 'άλλαγμαν',
'tags-hitcount'        => '$1 {{PLURAL:$1|αλλαγή|αλλαγάς}}',

# HTML forms
'htmlform-submit'              => 'Στείλον',
'htmlform-reset'               => "Κλώσον τ'αλλαγάς",
'htmlform-selectorother-other' => 'Άλλον',

);
