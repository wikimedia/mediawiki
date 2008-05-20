<?php
/** Pontic (Ποντιακά)
 *
 * @ingroup Language
 * @file
 *
 * @author Consta
 * @author Sinopeus
 * @author Siebrand
 */

$namespaceNames = array(
	NS_MEDIA          => 'Μέσον',
	NS_SPECIAL        => 'Ειδικόν',
	NS_TALK           => 'Καλάτσεμαν',
	NS_USER           => 'Χρήστες',
	NS_USER_TALK      => 'Καλάτσεμαν_χρήστε',
	# NS_PROJECT set by $wgMetaNamespace
	NS_PROJECT_TALK   => '$1_καλάτσεμαν',
	NS_IMAGE          => 'Εικόναν',
	NS_IMAGE_TALK     => 'Καλάτσεμαν_εικόνας',
	NS_MEDIAWIKI      => 'MediaWiki',
	NS_MEDIAWIKI_TALK => 'MediaWiki_talk',
	NS_TEMPLATE       => 'Πρότυπον',
	NS_TEMPLATE_TALK  => 'Καλάτσεμαν_πρότυπι',
	NS_HELP           => 'Βοήθειαν',
	NS_HELP_TALK      => 'Καλάτσεμαν_βοήθειας',
	NS_CATEGORY       => 'Κατηγορίαν',
	NS_CATEGORY_TALK  => 'Καλάτσεμαν_κατηγορίας',
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
'tog-showhiddencats' => 'Δείξον κρυμμένα κατηγορίας',

'underline-always' => 'Πάντα',
'underline-never'  => 'Καμίαν',

'skinpreview' => '(Πρώτον τέρεμα)',

# Dates
'sunday'        => 'Κερεκήν',
'monday'        => 'Δευτέραν',
'tuesday'       => 'Τριτ',
'wednesday'     => 'Τετάρτ',
'thursday'      => 'Πεφτ',
'friday'        => 'Παρασκευήν',
'saturday'      => 'Σάββαν',
'sun'           => 'Κυρ',
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
'august'        => 'Aύγουστον',
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
'august-gen'    => 'Αύγουστονος',
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
'aug'           => 'Αύγ',
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
'hidden-category-category'       => 'Κρυμμέν κατηγορίας', # Name of the category where hidden categories will be listed
'category-subcat-count-limited'  => "Η κατηγορίαν ατή έχ' αφκά καικά {{PLURAL:$1|την υποκατηγορίαν|$1 τα υποκατηγορίας}}.",
'category-article-count'         => "{{PLURAL:$2|Αβούτη κατηγορίαν έχ' την αφκά καικά σελίδαν μαναχόν.| Αφκά καικά {{PLURAL:$1|η σελίδαν εν|$1 τα σελίδας είναι}} σην κατηγορίαν ατέν, απές σο σύνολον τη $2.}}",
'category-article-count-limited' => 'Αφκά καικά {{PLURAL:$1|η σελίδαν εν|$1 τα σελίδας είναι}} σην κατηγορίαν ατέν.',
'category-file-count'            => "{{PLURAL:$2|Αβούτη κατηγορίαν έχ' το αφκά καικά αρχείον μαναχόν.| Αφκά καικά {{PLURAL:$1|το αρχείον εν|$1 τα αρχεία είναι}} σην κατηγορίαν ατέν, απές σο σύνολον τη $2.}}",
'category-file-count-limited'    => "{{PLURAL:$1|Τ' αρχείον|$1 Τ' αρχεία}} αφκά καικά είν' σην κατηγορίαν.",
'listingcontinuesabbrev'         => 'συνεχίζεται...',

'cancel'         => 'Χάτεμαν',
'qbfind'         => 'Εύρον',
'qbedit'         => 'Άλλαξον',
'qbpageoptions'  => 'Ατή η σελίδαν',
'qbmyoptions'    => "Τ' εμά τα σελίδας",
'qbspecialpages' => 'Ειδικά σελίδας',
'moredotdotdot'  => 'Πλέα...',
'mypage'         => "Τ' εμόν η σελίδαν",
'mytalk'         => "Τ' εμόν το καλάτσεμαν",
'anontalk'       => "Καλάτσεμα για τ'ατό το IP",
'and'            => 'και',

# Metadata in edit box
'metadata_help' => 'Μεταδογμένα:',

'returnto'          => 'Επιστροφήν σο $1.',
'tagline'           => 'Ασό {{SITENAME}}',
'help'              => 'Βοήθειαν',
'search'            => 'αράεμαν',
'searchbutton'      => 'Εύρον',
'go'                => 'Δέβα',
'searcharticle'     => 'Δέβα',
'history'           => 'Ιστορίαν τη σελίδας',
'history_short'     => 'Ιστορίαν',
'printableversion'  => 'Μορφή εκτύπωσης',
'print'             => 'Τύπωμαν',
'edit'              => 'Άλλαξον',
'create'            => 'Ποίσον',
'editthispage'      => 'Άλλαξον τη σελίδαν ατέν',
'create-this-page'  => 'Ποίσον τη σελίδαν',
'delete'            => 'Σβήσον',
'deletethispage'    => 'Σβήσεμαν τη σελίδας',
'protect'           => 'Ασπάλιγμαν',
'protect_change'    => "Άλλαγμα τ' ασπάλιγματη",
'protectthispage'   => 'Ασπάλιγμα ατουνού τη σελίδας',
'unprotect'         => 'Άνοιγμαν',
'unprotectthispage' => 'Άνοιγμα ατουνού τη σελίδας',
'newpage'           => 'Νέον σελίδαν',
'talkpage'          => 'Καλάτσεμαν για τη σελίδαν ατέν',
'talkpagelinktext'  => 'Καλάτσεμαν',
'specialpage'       => 'Ειδικόν σελίδαν',
'personaltools'     => 'Προσωπικά εργαλεία',
'postcomment'       => 'Ποίσον σχόλιον',
'talk'              => 'Καλάτσεμαν',
'views'             => 'Τερέματα',
'toolbox'           => 'Εργαλεία',
'userpage'          => 'Τέρεν σελίδαν χρήστε',
'imagepage'         => 'Τέρεν σελίδαν δογμενίων',
'mediawikipage'     => 'Τέρεν σελίδαν μενεματίων',
'templatepage'      => 'Τέρεν σελίδαν προτυπίων',
'viewhelppage'      => 'Τέρεν σελίδαν βοήθειας',
'viewtalkpage'      => 'Τέρεν καλάτσεμα',
'otherlanguages'    => "Σ' άλλα γλώσσας",
'protectedpage'     => 'Ασπαλιζμένον σελίδαν',
'jumpto'            => 'Δέβα σο:',
'jumptonavigation'  => 'Πορπάτεμαν',
'jumptosearch'      => 'Αράεμαν',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutpage'            => 'Project:Σχετικά',
'copyrightpage'        => '{{ns:project}}:Δικαιώματα Πνευματή',
'currentevents'        => 'Ατωριζνά γεγονότα',
'currentevents-url'    => 'Project:Ατωριζνά γεγονότα',
'disclaimers'          => 'Ιμπρέσουμ',
'disclaimerpage'       => 'Project:Ιμπρέσουμ',
'edithelp'             => "Βοήθειαν για τ' αλλαγμαν",
'edithelppage'         => 'Help:Άλλαγμαν',
'helppage'             => 'Help:Περιεχόμενα',
'mainpage'             => 'Αρχικόν σελίδα',
'mainpage-description' => 'Αρχικόν σελίδα',
'portal'               => 'Πύλην τη κοινότητας',
'portal-url'           => 'Project:Πύλη κοινότητας',
'privacy'              => 'Ωρίαγμαν δογμενίων',
'sitesupport'          => 'Δωρεάς',

'retrievedfrom'           => 'Ασο "$1"',
'youhavenewmessages'      => 'Έχετε $1 ($2).',
'newmessageslink'         => 'καινούρεα μενέματα',
'newmessagesdifflink'     => 'υστερνόν αλλαγήν',
'youhavenewmessagesmulti' => 'Έχετε καινούρεα μενέματα σο $1',
'editsection'             => 'άλλαξον',
'editold'                 => 'άλλαξον',
'editsectionhint'         => "Άλλαξον κομμάτ': $1",
'toc'                     => 'Περιεχόμενα',
'showtoc'                 => 'δείξον',
'hidetoc'                 => 'κρύψον',
'feedlinks'               => 'Ροή δογμενίων:',
'site-rss-feed'           => '$1 RSS Συνδρομή',
'site-atom-feed'          => '$1 Atom Συνδρομή',
'page-rss-feed'           => '"$1" RSS Συνδρομή',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Σελίδαν',
'nstab-user'      => 'Σελίδα χρήστε',
'nstab-special'   => 'Ειδικόν',
'nstab-project'   => 'Σχετικά με',
'nstab-image'     => 'Εικόναν',
'nstab-mediawiki' => 'Μένεμα',
'nstab-template'  => 'Πρότυπον',
'nstab-help'      => 'Σελίδα βοήθειας',
'nstab-category'  => 'Κατηγορίαν',

# Main script and global functions
'nosuchaction'      => "Αΐκον ενέργειαν 'κ εχ'.",
'nosuchspecialpage' => "Αΐκον ειδικόν σελίδαν 'κ εχ'.",

# General errors
'laggedslavemode'   => "Ωρία: Η σελίδαν ίσως ξάι 'κ εχ' τα υστερνά τα αλλαγάς.",
'readonly'          => 'Βάση δογμενίων εν ασπαλιζμένον',
'enterlockreason'   => "Βαλέστεν λόγον για τ' ασπάλιγμαν και ους πότε θα εν ασπαλιγμένον",
'badarticleerror'   => "Ατή η ενέργειαν 'κ επορεί να ίνεται σ'ατήν τη σελίδαν.",
'viewsource'        => 'Τέρεν κωδικόν',
'viewsourcefor'     => 'για $1',
'protectedpagetext' => "Ατή η σελίδαν εν ασπαλιγμένη και 'κ αλλάζεται.",
'viewsourcetext'    => "Μπορείτε να τερείτε και να αντιγράφετε το κείμενον τ' ατεινές τη σελίδας:",

# Login and logout pages
'yourname'                => 'Όνεμα χρήστε:',
'yourpassword'            => 'Σημάδι:',
'yourpasswordagain'       => "Ξαν' γράψτεν το σημάδι:",
'login'                   => 'Εμπάτε',
'nav-login-createaccount' => 'Εμπάτε / Ποίστεν λογαρίαν',
'loginprompt'             => "Πρέπ' να έχετε ενεργοποιήσει τα cookies για εμπείτε σο {{SITENAME}}.",
'userlogin'               => 'Εμπάτε / Ποίστεν λογαρίαν',
'logout'                  => 'οξουκά',
'userlogout'              => 'Οξουκά',
'notloggedin'             => 'Ευρίσκεζνε οξουκά ασή Βικιπαίδειαν',
'nologin'                 => "Λογαρίαν 'κ έχετε; $1.",
'nologinlink'             => 'Ποίστεν λογαρίαν',
'createaccount'           => 'Ποίσον λογαρίαν',
'gotaccount'              => 'Λογαρίαν έχετε; $1.',
'gotaccountlink'          => 'Εμπάτε',
'badretype'               => "Τα σημάδε ντ' εγράψετεν 'κ ταιριάζνε.",
'username'                => 'Όνεμα χρήστε:',
'yourrealname'            => 'Πραματικόν όνεμαν:',
'yourlanguage'            => "Τ' εσόν η γώσσαν:",
'prefs-help-realname'     => "'Κ επρέπ' να βάλετεν τεσέτερον πραματικόν τ' όνεμαν.
Άμα αν βάλετεν ατό, αμπορεί πα ν' αναγνωρίζκεται τεσέτερον η δουλείαν.",
'loginerror'              => 'Σφάλμα εγγραφής',
'loginsuccesstitle'       => "Έντον τ' εσέβεμαν",
'loginsuccess'            => "'''Εσήβετεν σο {{SITENAME}} ους \"\$1\".'''",
'nosuchuser'              => 'Αδά \'κ εχ\' χρήστεν με τ\' όνομα "$1".
Το γράψιμον ωρία ή ποίσον καινούρεον λογαρίαν.',
'nosuchusershort'         => 'Αδά \'κ εχ\' χρήστεν με τ\' όνομα "<nowiki>$1</nowiki>".
Το γράψιμονις ωρία.',
'mailmypassword'          => 'Αποστολή κωδικού',
'accountcreated'          => 'Έντον η λογαρίαν',
'createaccount-title'     => 'Δημιουργίαν λογαρίας για {{SITENAME}}',
'loginlanguagelabel'      => 'Γλώσσαν: $1',

# Edit page toolbar
'link_sample'     => 'Τίτλος σύνδεσμονος',
'link_tip'        => 'Εσωτερικόν σύνδεσμον',
'extlink_sample'  => 'http://www.paradeigma.com τίτλος σύνδεσμονος',
'extlink_tip'     => 'Εξωτερικόν σύνδεσμος (να μην ανασπάλλετε το πρόθεμαν http:// )',
'headline_sample' => 'Κείμενον τίτλονος',
'headline_tip'    => 'Δεύτερον τίτλος (επίπεδον 2)',
'math_sample'     => 'Αδά εισάγετε την φόρμουλαν',
'math_tip'        => 'Μαθεματικόν φόρμουλα (LaTeX)',
'nowiki_sample'   => 'Αδακά πα να εισάγετε το μη μορφοποιημένον κείμενον.',
'nowiki_tip'      => "Ξάι 'κ να τερείται η μορφοποίηση Wiki.",
'image_tip'       => 'Ενσωματωμένον εικόνα',
'media_tip'       => 'Σύνδεσμος αρχείατι πολυμεσίων',
'sig_tip'         => 'Η υπογραφήν εσούν με ώραν κι ημερομηνίαν',

# Edit pages
'summary'                => 'Σύνοψη',
'subject'                => 'Θέμα/επικεφαλίδα',
'minoredit'              => 'Μικρόν αλλαγήν',
'watchthis'              => 'Ωρίαγμαν τη σελίδας',
'savearticle'            => 'Αποθήκεμαν σελίδας',
'preview'                => 'Πρώτον τέρεμα',
'showpreview'            => 'Πρώτον τέρεμαν',
'showdiff'               => 'Αλλαγάς',
'newarticle'             => '(Νέον)',
'previewnote'            => "<strong>Ατό πα πρώτον τέρεμαν εν και μόνον.
Τ' αλλαγάς 'κ εκρατέθαν!</strong>",
'editing'                => 'Αλλαγήν $1',
'editingsection'         => 'Αλλαγήν $1 (τμήμα)',
'editingold'             => "<strong>ΩΡΙΑ: Εφτάτε αλλαγάς σε παλαιόν έκδοσην τη σελίδας. 
Εάν θα κρατείτε ατά, ούλ' τ' επεκεί αλλαγάς θα χάνταν.</strong>",
'templatesused'          => "Πρότυπα το μεταχειρίσκουνταν σ' αβούτεν την σελίδαν:",
'template-protected'     => '(ασπαλιγμένον)',
'template-semiprotected' => '(ημψά-ασπαλιγμένον)',

# History pages
'viewpagelogs'        => "Τέρεν πρωτόκολλα γι' αβούτεν τη σελίδαν",
'currentrev'          => 'Ατωριζνόν μορφήν',
'revisionasof'        => 'Μορφήν τη $1',
'revision-info'       => 'Έκδοση σα $1 ασόν/ασήν $2',
'previousrevision'    => '←Παλαιόν μορφήν',
'nextrevision'        => 'Κι άλλο καινούρεον έκδοση→',
'currentrevisionlink' => 'Ατωριζνόν έκδοση',
'cur'                 => 'τρέχουσα',
'last'                => 'τελευταία',
'page_first'          => 'πρώτη',
'page_last'           => 'τελευταία',
'histfirst'           => "Ασ' όλεα παλαιόν",
'histlast'            => "Ασ' όλεα καινούρ'",

# Revision feed
'history-feed-item-nocomment' => '$1 σο $2', # user at time

# Diffs
'history-title'           => 'Ιστορικόν εκδοσίων για τη σελίδαν "$1"',
'lineno'                  => 'Γραμμή $1:',
'compareselectedversions' => 'Γαρσουλαεύτε τα εκδώσεις',
'editundo'                => 'αναίρεση',

# Search results
'noexactmatch' => "'''Η Βικιπαίδειαν 'κ εχ' σελίδαν με τ' όνεμαν \"\$1\".'''
Εμπορείτε να [[:\$1|εφτάτε ατέν]].",
'prevn'        => '$1 προηγουμένων',
'nextn'        => '$1 επομένων',
'viewprevnext' => 'Τέρεν ($1) ($2) ($3)',
'powersearch'  => 'Αναλυτικόν αράεμαν',

# Preferences page
'preferences'   => 'Προτιμήσεις',
'mypreferences' => "Τ' εμά τα προτιμήσεις",
'retypenew'     => 'Γράψον ξαν το νέον σημάδιν:',

# Recent changes
'recentchanges'                  => 'Υστερνά αλλαγάς',
'recentchanges-feed-description' => "Τ' ασ' όλεα καινούρεα αλλαγάς τη wiki ωρία σ' αβούτεν την περίληψην.",
'rcshowhideminor'                => '$1 τα μικρά αλλαγάς',
'rcshowhideliu'                  => '$1 χρήστες με λογαρίαν',
'rcshowhideanons'                => '$1 αναγνώριμοι χρήστες',
'rcshowhidemine'                 => "$1 τ' επεξεργασίων ιμ",
'rclinks'                        => "Δείξον τα $1 υστερνά τ' αλλαγάς α σα $2 υστερνά τα ημέρας<br />$3",
'diff'                           => 'διαφορά',
'hist'                           => 'ιστ.',
'hide'                           => 'Κρύψον',
'show'                           => 'Δείξον',
'minoreditletter'                => 'μ',
'newpageletter'                  => 'Ν',
'boteditletter'                  => 'b',

# Recent changes linked
'recentchangeslinked-title' => 'Αλλαγάς τη "$1"',

# Upload
'upload'        => 'Φόρτωμα αρχείου',
'uploadbtn'     => 'Φόρτωμα αρχείου',
'uploadedimage' => 'Εγέντον το φόρτωμαν τη "[[$1]]"',

# Image description page
'filehist'            => 'Ιστορικό αρχείου',
'filehist-current'    => 'υστερινά',
'filehist-datetime'   => 'Ώραν/Ημερομ.',
'filehist-user'       => 'Χρήστες',
'filehist-dimensions' => 'Διαστάσεις',
'filehist-filesize'   => 'Μέγεθος',
'filehist-comment'    => 'Σχόλιον',
'imagelinks'          => 'Σύνδεσμοι',
'linkstoimage'        => "Ατά τα σελίδας δεκνίζ'νε σην εικόναν:",
'nolinkstoimage'      => "'Κ εχ σελίδας ντο δεκνίζνε σ' αβούτεν εικόναν.",

# MIME search
'mimesearch' => 'Αράεμαν MIME',

# Unused templates
'unusedtemplates' => "Πρότυπα ντο 'κ μεταχειρίσκουνταν",

# Random page
'randompage' => 'Τυχαίον σελίδα',

# Statistics
'statistics' => 'Στατιστικήν',

'withoutinterwiki' => "Σελίδας ντο κ' έχνε συνδέσμ",

# Miscellaneous special pages
'nlinks'                  => '$1 {{PLURAL:$1|σύνδεσμος|συνδέσμ}}',
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
'mostcategories'          => "Σελίδας με τ' ασ' όλτς πολλά κατηγορίας",
'prefixindex'             => 'Κατάλογος κατά πρόθεμαν',
'shortpages'              => 'Μικρά σελίδας',
'longpages'               => 'Τρανά σελίδας',
'deadendpages'            => 'Αδιέξοδα σελίδας',
'protectedpages'          => 'Ασπαλιγμένα σελίδας',
'listusers'               => 'Κατάλογον χρήστιων',
'specialpages'            => 'Ειδικά σελίδας',
'newpages'                => 'Καινούρεα σελίδας',
'ancientpages'            => 'Ασ’ όλιον παλαιά σελίδας',
'move'                    => 'Ετεροχλάεμαν',
'movethispage'            => "Άλλαξον τ' όνεμα τη σελίδας",

# Book sources
'booksources-search-legend' => 'Αράεμαν τη βιβλίων',
'booksources-go'            => 'Δέβα',

# Special:Log
'specialloguserlabel'  => 'Χρήστες:',
'speciallogtitlelabel' => 'Τίτλος:',

# Special:Allpages
'allpages'       => 'Όλεα τα σελίδας',
'alphaindexline' => '$1 ους $2',
'nextpage'       => 'Επόμενον σελίδα ($1)',
'prevpage'       => 'Προηγούμενον σελίδα ($1)',
'allarticles'    => 'Όλεα τα σελίδας',
'allpagessubmit' => 'Δέβα',

# Special:Categories
'categories'         => 'Κατηγορίας',
'categoriespagetext' => 'Τα κατηγορίας αφκά καικά έχνε σελίδας και μέσα.',

# E-mail user
'emailuser' => 'Στείλον μένεμαν σον χρήστεν ατόν.',

# Watchlist
'watchlist'            => "Σελίδας ντ' ωριάζω",
'mywatchlist'          => "Σελίδας ντ' ωριάζω",
'watchlistfor'         => "(για '''$1''')",
'removedwatch'         => 'Αση λίσταν επάρθεν',
'removedwatchtext'     => 'Η σελίδαν "[[:$1]]" νεβζινέθεν ασ\' τ\'εσόν τον κατάλογον.',
'watch'                => 'Ωρίαγμαν',
'watchthispage'        => 'Ωρίαν τη σελίδαν',
'unwatch'              => 'Τέλος τη ωρίαγματη',
'watchlist-details'    => '{{PLURAL:$1|$1 σελίδα|$1 σελίδας}} ωριάσκουνταν, θέγα τα σελίδας καλάτσεματι.',
'wlshowlast'           => "Φανέρωμαν τ' υστερναίων $1 ωρίων $2 ημερίων $3",
'watchlist-hide-bots'  => "Κρύψον τ' αλλαγάς τη bots",
'watchlist-hide-own'   => "Κρύψον τ' αλλαγάς 'ιμ",
'watchlist-hide-minor' => 'Κρύψον τα μικρά αλλαγάς',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'Ωριάζω...',
'unwatching' => "'κ ωριάζω...",

# Delete/protect/revert
'deletepage'              => 'Σβήσον τη σελίδαν',
'actioncomplete'          => 'Η ενέργειαν ετελέθεν',
'deletedarticle'          => 'νεβζινέθεν η "[[$1]]"',
'dellogpage'              => "Κατάλογος με τ' ατά το νεβζίναν",
'deletecomment'           => 'Λόγον για το σβήσιμο:',
'deleteotherreason'       => 'Άλλον/αλλομίαν λόγον:',
'deletereasonotherlist'   => 'Άλλον λόγον',
'rollbacklink'            => 'φέρον ξαν σην υστερναίαν',
'protectcomment'          => 'Σχόλιον:',
'protect-unchain'         => 'Άνοιξον τα δικαιώματα ετεροχλάεματι',
'protect-default'         => '(προεπιλεγμένον)',
'protect-summary-cascade' => 'διαδοχικόν',
'protect-expiring'        => 'λήγει στις $1 (UTC)',
'protect-cascade'         => "Ασπάλιγμαν σελιδίων ντ' είν απές σ' αβούτεν σελίδαν (διαδοχικόν προστασίαν)",
'protect-cantedit'        => "'Κι έχετε δικαίωμαν ν' αλλάζετε τ' επίπεδον ασπάλιγματι τ' ατεινές σελίδας.",
'restriction-type'        => 'Δικαίωμαν:',
'restriction-level'       => 'Επίπεδον περιορισμού:',

# Undelete
'undeletebtn' => 'Ποίσον ξαν',

# Namespace form on various pages
'namespace'      => 'Περιοχήν:',
'invert'         => "Αντιστροφή τ' επιλογής",
'blanknamespace' => '(Αρχικόν περιοχή)',

# Contributions
'contributions' => "Δουλείας ντ' εποίκε ο χρήστες",
'mycontris'     => "Δουλείας ντ' εποίκα",
'contribsub2'   => 'Για τον/την $1 ($2)',
'uctop'         => '(υστερνά)',
'month'         => 'Ασόν μήναν (και πριχού):',
'year'          => 'Ασή χρονίαν (και πριχού):',

'sp-contributions-newbies-sub' => 'Για τα καινούρεα τοι λογαρίας',

# What links here
'whatlinkshere'       => "Ντο δεκνίζ' αδακές",
'whatlinkshere-title' => 'Σελίδας ντο συνδέουν σο $1',
'whatlinkshere-page'  => 'Σελίδαν:',
'linklistsub'         => "(Κατάλογον με τοι συνδέσμ')",
'linkshere'           => "Αβούτα τα σελίδας δεκνίζνε σο '''[[:$1]]''':",
'nolinkshere'         => "'Κ ευρέθεν σελίδα το δεκνίζ' ση σελίδαν '''[[:$1]]'''.",
'istemplate'          => 'ενσωμάτωση',
'whatlinkshere-prev'  => '{{PLURAL:$1|προτεσνή|προτεσνά $1}}',
'whatlinkshere-next'  => '{{PLURAL:$1|υστερνή|υστερναία $1}}',
'whatlinkshere-links' => '← σύνδεσμοι',

# Block/unblock
'contribslink' => "Δουλείαν ατ'",

# Move page
'movearticle'      => 'Ετεροχλάεμαν σελίδας:',
'newtitle'         => 'Νέον τίτλον:',
'move-watch'       => 'Αβούτη τη σελίδαν ωρία',
'movepagebtn'      => 'Ποίσον ετεροχλάεμαν τη σελίδαν',
'pagemovedsub'     => 'Ετερχλαεύτεν',
'movedto'          => 'ετεροχλαεύτεν σο',
'movetalk'         => 'Ετεροχλάεμαν τη σελίδας καλάτσεματι',
'talkpagemoved'    => 'Ετερχλαεύτεν και η σελίδαν καλάτσεματι.',
'talkpagenotmoved' => "Η σελίδαν καλάτσεματιν ατ' <strong>'κ</strong> ετερχλαεύτεν.",
'1movedto2'        => '[[$1]] ετερχλαεύτεν σο [[$2]]',
'movereason'       => 'Λόγον:',
'revertmove'       => 'επαναφορά',

# Export
'export' => 'Εξαγωγήν σελίδιων',

# Namespace 8 related
'allmessages' => 'Μενέματα σύστηματη',

# Thumbnails
'thumbnail-more'  => 'Ποίσον κι άλλο τρανόν',
'thumbnail_error' => 'Έντον λάθος ση δημιουργίαν τη μικρογραφίας: $1',

# Tooltip help for the actions
'tooltip-pt-userpage'             => "Τ' εμόν η σελίδαν",
'tooltip-pt-mytalk'               => "Σελίδαν με τ' εμά τα καλατσέματα",
'tooltip-pt-preferences'          => "Τ' εμά τα προτιμήσεις",
'tooltip-pt-watchlist'            => "Λίστα με τα σελίδας ντ' ωριάζω",
'tooltip-pt-mycontris'            => "Λίστα με τα δουλείας ντ' εποίκα",
'tooltip-pt-login'                => "Μπορείτε νε εφτάτε λογαρίαν άμα 'κ πρεπ'.",
'tooltip-pt-logout'               => 'Απιδεβένετεν τη Βικιπαίδειαν',
'tooltip-ca-talk'                 => "Γονούσεμαν γι' αβούτον τ' άρθρον",
'tooltip-ca-edit'                 => "Άλλαγμαν τη σελίδας. Άμαν τερέστεν τ' αλλαγάς πριν θα κρατείτε ατά.",
'tooltip-ca-addsection'           => "Βαλέστε σχόλιον σ' αβούτο το γουνούσεμα.",
'tooltip-ca-viewsource'           => "Ατό η σελίδαν εν ασπαλιγμένον. Άμαν μπορείτε να τερείτε το κείμενον ατ'ς.",
'tooltip-ca-history'              => 'Παλαιά εκδώσεις τη σελίδας.',
'tooltip-ca-protect'              => 'Ασπάλιγμα τη σελίδας',
'tooltip-ca-delete'               => 'Σβήσεμαν τη σελίδας',
'tooltip-ca-move'                 => "Κότζεμαν τη σελίδας ας έναν τίτλον σ' άλλον.",
'tooltip-ca-watch'                => 'Ωρίαγμαν τη σελίδας',
'tooltip-ca-unwatch'              => 'Έπαρ αβούτεν τη σελίδαν αση λίσταν ωρίαγματι.',
'tooltip-search'                  => 'Εύρον σο {{SITENAME}}',
'tooltip-n-mainpage'              => 'Τερέστεν το αρχικόν τη σελίδαν',
'tooltip-n-portal'                => 'Σχετικά με το Wiκi - πώς μπορείτε να εφτάτε γιαρτήμ, πού θα ευρίετε πράγματα',
'tooltip-n-recentchanges'         => "Κατάλογος με τ' υστερνά αλλαγάς σο wiki.",
'tooltip-n-randompage'            => 'Κατά τύχην εύρον σελίδαν και δείξον ατέν',
'tooltip-n-help'                  => "Αδά θα ευρίετε τα απαντήσεις ντ' αραεύετε.",
'tooltip-n-sitesupport'           => 'Βοηθέστεν το έργον.',
'tooltip-t-whatlinkshere'         => "Ούλ' τ' άρθρα ντο δεκνίζνε σο παρόν το άρθρον",
'tooltip-t-contributions'         => 'Τερέστεν τη λίσταν με τα συνεισφοράντας τη χρήστε',
'tooltip-t-emailuser'             => "E-mail σ' αβούτον χρήστεν",
'tooltip-t-upload'                => 'Φόρτωμα αρχείων',
'tooltip-t-specialpages'          => 'Κατάλογον με τα ειδικά σελίδας',
'tooltip-ca-nstab-user'           => 'Τέρεν τη σελίδαν τη χρήστε',
'tooltip-ca-nstab-project'        => 'Τέρεν σελίδαν σύστηματι',
'tooltip-ca-nstab-image'          => 'Τερέστεν την εικόναν',
'tooltip-ca-nstab-mediawiki'      => 'Τέρεμαν τη μενεματίων τη σύστηματι',
'tooltip-ca-nstab-template'       => 'Τερέστεν τα πρότυπα',
'tooltip-ca-nstab-help'           => 'Τερέστεν τη σελίδα βοήθειας',
'tooltip-ca-nstab-category'       => 'Τέρεν σελίδαν κατηγορίας',
'tooltip-minoredit'               => 'Όντες εφτάτε μικρόν αλλαγήν',
'tooltip-save'                    => "Κρα τ' αλλαγάς",
'tooltip-preview'                 => "Τέρεν τ' αλλαγάς πριχού να κρατείς τη σελίδαν!",
'tooltip-diff'                    => "Δείξον τ' αλλαγάς ντ' εποίκες σο κείμενον.",
'tooltip-compareselectedversions' => "Τερέστε τα διαφοράς τ' εκδωσίων τη σελίδας",
'tooltip-watch'                   => "Βαλέστεν αβούτεν τη σελίδαν σην τ' εσέτερον λίσταν ωριάγματι",

# Browsing diffs
'nextdiff' => "Αϊτέστε σ' υστερναίον διαφορά →",

# Media information
'file-nohires'   => "<small>'Κ εχ κι άλλο ψηλόν ανάλυσην.</small>",
'show-big-image' => 'Τζιπ ψηλόν ανάλυση',

# Special:Newimages
'ilsubmit' => 'Αράεμαν',
'bydate'   => 'ημερομηνίας',

# Metadata
'metadata'          => 'Μεταδογμένα',
'metadata-expand'   => 'Δείξον τα λεπτομέρειας',
'metadata-collapse' => 'Κρύψον τα λεπτομέρειας',

# EXIF tags
'exif-imagewidth'                  => 'Πλάτος',
'exif-imagelength'                 => 'Ύψηλος',
'exif-bitspersample'               => 'Bits ανά στοιχείο',
'exif-compression'                 => 'Σχήμα συμπίεσης',
'exif-photometricinterpretation'   => 'Σύνθεση τη pixel',
'exif-orientation'                 => 'Προσανατολισμός',
'exif-samplesperpixel'             => 'Αριθμός στοιχείων',
'exif-ycbcrsubsampling'            => 'Αναλογικόν δείγμαν σε φωτεινότητα και χρώμαν',
'exif-ycbcrpositioning'            => 'Ρύθμιση φωτεινότητας και χρώματι',
'exif-xresolution'                 => 'Οριζόντιον ανάλυση',
'exif-yresolution'                 => 'Κατακόρυφον ανάλυση',
'exif-resolutionunit'              => 'Μονάδα μέτρησης ανάλυσης X και Y',
'exif-stripoffsets'                => 'Τοποθέτηση δεδομενίων εικόνας',
'exif-stripbytecounts'             => 'Bytes ανά συμπιεσμένον λωρίδα',
'exif-jpeginterchangeformat'       => 'Μετάθεση σε JPEG SOI',
'exif-jpeginterchangeformatlength' => 'Bytes δεδομενίων JPEG',
'exif-transferfunction'            => 'Λειτουργία μεταφοράς',
'exif-whitepoint'                  => "Χρωματικόν προσδιορισμός τ' άσπρου",
'exif-primarychromaticities'       => 'Πρωτεύοντες χρωματισμοί',

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => 'ούλαι',
'namespacesall' => 'όλεα',
'monthsall'     => 'ούλαι',

# Multipage image navigation
'imgmultigo' => 'Δέβα!',

# Table pager
'table_pager_limit_submit' => 'Δέβα',

# Watchlist editing tools
'watchlisttools-edit' => 'Τέρεν κι άλλαξον κατάλογον ωρίαγματι',

# Special:Version
'version' => 'Έκδοση', # Not used as normal message but as header for the special page itself

);
