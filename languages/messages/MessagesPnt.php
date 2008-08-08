<?php
/** Pontic (Ποντιακά)
 *
 * @ingroup Language
 * @file
 *
 * @author Sinopeus
 * @author Consta
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

'about'          => 'Περί',
'newwindow'      => "(ανοίγ' σ' άλλον παραθύρ)",
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

'errorpagetitle'    => 'Λάθος',
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
'permalink'         => 'Σκιρόν σύνδεσμος',
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
'redirectedfrom'    => '(Έρτεν ασό $1)',
'redirectpagesub'   => 'Σελίδαν διπλού σύνδεσμονος',
'protectedpage'     => 'Ασπαλιζμένον σελίδαν',
'jumpto'            => 'Δέβα σο:',
'jumptonavigation'  => 'Πορπάτεμαν',
'jumptosearch'      => 'Αράεμαν',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'Περί {{SITENAME}}',
'aboutpage'            => 'Project:Σχετικά',
'bugreports'           => 'Αναφοράντας λαθίων',
'bugreportspage'       => 'Project:Γραφέστεν',
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
'privacypage'          => 'Project:Πολιτική ιδιωτικού απορρήτου',

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
'nstab-mediawiki' => 'Μένεμαν',
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
'badtitle'          => 'Άχρηστον τίτλος',
'badtitletext'      => "Το ψαλαφεμένον ο τίτλος τη σελίδας εν άκυρον, εύκαιρον για λανθασμένον διαγλωσσικόν σύνδεσμον.
Εν δυνατόν και να έχ' έναν για πολλά γράμματα που 'κ ίνεται να κουλανεύκουνταν απές σε τίτλον.",
'viewsource'        => 'Τέρεν κωδικόν',
'viewsourcefor'     => 'για $1',
'protectedpagetext' => "Ατή η σελίδαν εν ασπαλιγμένη και 'κ αλλάζεται.",
'viewsourcetext'    => "Μπορείτε να τερείτε και να αντιγράφετε το κείμενον τ' ατεινές τη σελίδας:",

# Login and logout pages
'yourname'                => 'Όνεμα χρήστε:',
'yourpassword'            => 'Σημάδι:',
'yourpasswordagain'       => "Ξαν' γράψτεν το σημάδι:",
'remembermypassword'      => "Αποθήκεμαν τη σημαδίμ σ' αβούτον τον υπολογιστήν",
'login'                   => 'Εμπάτε',
'nav-login-createaccount' => 'Εμπάτε / Ποίστεν λογαρίαν',
'loginprompt'             => "Πρέπ' ν' άφτετε τα cookies για εμπείτε σο {{SITENAME}}.",
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
'nouserspecified'         => "Πρέπ' να ψιλίζετε όνομαν.",
'wrongpassword'           => "Το σημάδι 'κ εν σωστόν.
Ποίστεν άλλο προσπάθειαν.",
'wrongpasswordempty'      => 'Το σημάδι έτον εύκαιρον.
Ποίστεν άλλο προσπάθειαν.',
'passwordtooshort'        => "Το σημάδι εν πολλά μικρόν.
Πρέπ' να εχ' {{PLURAL:$1|1 γράμμαν|$1 γράμματα}} κιαν. Το σημάδινεσουν πρέπ' να εν αλλέτερον ασόν όνομαν τη χρήστε.",
'mailmypassword'          => 'Αποστολή κωδικού',
'passwordremindertitle'   => 'Καινούρεον σημάδιν για {{SITENAME}}',
'passwordremindertext'    => 'Κάποιος (ίσως εσείς, ασήν διεύθυνσην IP $1)
εποίκεν ψαλαφίον να στείλκουμες έναν νέον σημάδιν για τον ιστοτόπον {{SITENAME}} ($4).
Το σημάδιν για τον χρήστεν "$2" ατώρα εν "$3".
Ατώρα εμπάτε σην σελίδαν και ποίστεν το σημάδινεσουν διαφορετικόν.

Εάν το ψαλαφίον εποίκενατο άλλος για έχετε σο νούνεσουν αξάν το παλαιόν το σημάδινεσουν και το καινούρεον ξάι \'κ χρειάσκεται, επορείτε ν\' ανασπάλλετε το καινούρεον το σημάδιν με τ\' αβούτο το μένεμαν εντάμαν και να μεταχειρίσκεστε το παλαιόν το σημάδιν άμον ντ\' εφτάγατε ους οσήμερον.',
'noemail'                 => '\'Κ εδώθεν e-mail address τη χρήστε "$1".',
'passwordsent'            => 'Έναν καινούρεον σημάδιν επήγεν σο e-mail τη "$1".
Άμον ντο παίρετ\' ατό, εμπάτε ξαν.',
'eauthentsent'            => "Έναν μένεμαν confirmation e-mail επήγεν σην διεύθυνσην ντ' εδώκατε.
Πριχού να πηγαίνει άλλον μένεμαν σ' αβούτεν τη λογαρίαν, θα φτάτεν ατά ντο γραφ' σο μένεμαν απές. Αέτς πα θα δεκνίζετε το e-mail ατό εν το τεσέτερον.",
'accountcreated'          => 'Έντον η λογαρίαν',
'createaccount-title'     => 'Δημιουργίαν λογαρίας για {{SITENAME}}',
'loginlanguagelabel'      => 'Γλώσσαν: $1',

# Edit page toolbar
'bold_sample'     => 'Χοντρόν κείμενον',
'bold_tip'        => 'Χοντρόν κείμενον',
'italic_sample'   => 'Ψιλόν κείμενον',
'italic_tip'      => 'Ψιλόν κείμενον',
'link_sample'     => 'Τίτλος σύνδεσμονος',
'link_tip'        => 'Εσωτερικόν σύνδεσμον',
'extlink_sample'  => 'http://www.example.com τίτλος σύνδεσμονος',
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
'hr_tip'          => "Οριζόντιον γραμμή (μη θέκ'ς ατέν πολλά)",

# Edit pages
'summary'                => 'Σύνοψη',
'subject'                => 'Θέμα/επικεφαλίδα',
'minoredit'              => 'Μικρόν αλλαγήν',
'watchthis'              => 'Ωρίαγμαν τη σελίδας',
'savearticle'            => 'Αποθήκεμαν σελίδας',
'preview'                => 'Πρώτον τέρεμα',
'showpreview'            => 'Πρώτον τέρεμαν',
'showdiff'               => 'Αλλαγάς',
'anoneditwarning'        => "'''Ωρίασων:''' 'Κ εποίκες τ' εσέβεμαν.
Τ' IP ις θα γράφκεται και θα ελέπν' ατό σ' ιστορικόν τη σελίδας.",
'summary-preview'        => 'Πρώτον τέρεμαν τη σύνοψης',
'blockedtext'            => "<big>'''Τ' όνομαν ή το IP εσούν εκλείστεν.'''</big>

Τ' ασπάλιγμαν εποίκενατο ο χρήστες $1.
Έδωκεν την αιτίαν ''$2''.

* Ασπάλιγμαν αχπάσκεται: $8
* Ασπάλιγμαν τελείται: $6
* Θα κλείσκεται ο χρήστες: $7

Για το ασπάλιγμαν εμπορείτε να συντισένετε με τον $1 ή με τ' αλλτς τοι [[{{MediaWiki:Grouppage-sysop}}|διαχειριστάδες]].
Για να γράφετε ελεκτρονικόν μένεμαν ('e-mail this user') βαλέστεν το τεσέτερον το σωστόν το e-mail address σα [[Special:Preferences|προτιμήσαι τη λογαρίας εσούν]]. Επεκεί 'κ θα είστουν ασπαλιγμένος για γράψιμον τη μενεματί.
Τ' ατοριζνόν το IP εσούν εν $3, και το ID τη ασπαλιγματί εν #$5.
Ποδεδίζουμε σας να γράφτε τα και τα δυο σο μένεμαν εσούν.",
'autoblockedtext'        => "Το IP εσούν εκλείστεν αυτόματα επειδή μεταχειρίσκουτονατο άλλος χρήστες ντ' έτον ασπάλιγμένος ασόν χρήστεν $1.
Έδωκεν την αιτίαν:

:''$2''

* Ασπάλιγμαν αχπάσκεται: $8
* Ασπάλιγμαν τελείται: $6

Για το ασπάλιγμαν επορείτε να συντισένετε με το χρήστεν $1 ή με τ' αλλτς τ' [[{{MediaWiki:Grouppage-sysop}}|administrators]]. Για να γράφετε ελεκτρονικόν μένεμαν ('e-mail this user') βαλέστεν το τεσέτερον το σωστόν το e-mail address σα [[Special:Preferences|προτιμήσαι τη λογαρίας εσούν]]. Εάν 'κ εν ασπαλιγμένον η χρήσηνατ, επορείτε να γράφετε μένεμαν. 

Το ID τη ασπαλιγματίνεσουν εν $5.
Ποδεδίζουμε σας να γράφτατο σο μένεμαν εσούν.",
'blockednoreason'        => "'Κ εγράφτεν αιτίαν",
'newarticle'             => '(Νέον)',
'newarticletext'         => "Έρθατεν ασ' έναν σύνδεσμον σ' έναν εύκαιρον σελίδαν. 
Για να εφτάτε τη σελίδαν, αρχινέστε γράψιμον σο χουτίν αφκά (δεαβάστεν τη [[{{MediaWiki:Helppage}}|σελίδαν βοήθειας]] και μαθέστεν κιάλλα).
Εάν 'κ θέλετε ν' εφτάτε αβούτεν τη σελίδαν, πατήστε το κουμπίν το λεει '''οπίς''' και δεβάτεν οπίς απ' όθεν έρθατεν.",
'noarticletext'          => "(Αβούτεν η σελίδαν 'κ εχ' κείμενον απές ακόμαν.)",
'previewnote'            => "<strong>Ατό πα πρώτον τέρεμαν εν και μόνον.
Τ' αλλαγάς 'κ εκρατέθαν!</strong>",
'editing'                => 'Αλλαγήν $1',
'editingsection'         => 'Αλλαγήν $1 (τμήμα)',
'editingold'             => "<strong>ΩΡΙΑ: Εφτάτε αλλαγάς σε παλαιόν έκδοσην τη σελίδας. 
Εάν θα κρατείτε ατά, ούλ' τ' επεκεί αλλαγάς θα χάνταν.</strong>",
'copyrightwarning'       => "Ούλαι τα γραψίματα ασο {{SITENAME}} θα μεταχειρίσκουνταν άμον ντο λεει το $2 (τερέστεν και $1).
Εάν 'κ θέλετε ατό να ίνεται, να μην εφτάτε το αποθήκεμαν.<br />
Καμμίαν κι ανασπάλλετε: Αδακά 'κ εν ο τόπον για να θέκουμε γράψιμον ντ' έγραψαν αλλ. Βαλέστε άρθρα όνταν κατέχετε τα δικαιώματα πνευματί μαναχόν. 
<strong>ΚΑΜΜΙΑΝ 'Κ ΘΕΚΕΤΕ ΓΡΑΨΙΜΟΝ ΑΔΑΚΑ ΟΝΤΕΣ 'Κ ΕΧΕΤΕ ΤΑ ΔΙΚΑΙΩΜΑΤΑ ΠΝΕΥΜΑΤΙ!</strong>",
'longpagewarning'        => "<strong>ΩΡΙΑ: Αβούτεν η σελίδαν έχ' μέγεθος $1kb. Μερικά browser 'κ επορούν ν' επεξεργάσκουνταν σελίδας ντ' έχνε 32kb κιαν. Επορείτε να λύετε το πρόβλημαν αν εφτάτεν ατέναν μικρά κομμάται.</strong>",
'templatesused'          => "Πρότυπα το μεταχειρίσκουνταν σ' αβούτεν την σελίδαν:",
'templatesusedpreview'   => "Πρότυπα σ' αβούτον το πρώτον τέρεμαν:",
'template-protected'     => '(ασπαλιγμένον)',
'template-semiprotected' => '(ημψά-ασπαλιγμένον)',
'nocreatetext'           => "Σο {{SITENAME}} περιορίσκουτον το ποίσεμα σελιδίων.
'Πορείτε να κλώσκεστε οπίς και ν' αλλάζετε έναν παλαιόν σελίδαν ή να [[Special:UserLogin|εμπάτε ή να εφτάτε λογαρίαν]].",
'recreate-deleted-warn'  => "'''Ωρία: Εφτάτε αξάν μίαν σελίδαν ντο νεβζινέθεν οψεκές.'''

Ίσως εν καλλίον να μην εφτάτε τη σελίδαν.
Τερέστεν για βοήθειαν και σ' αρχείον την αιτίαν για το σβήσιμον:",

# History pages
'viewpagelogs'        => "Τέρεν πρωτόκολλα γι' αβούτεν τη σελίδαν",
'currentrev'          => 'Ατωριζνόν μορφήν',
'revisionasof'        => 'Μορφήν τη $1',
'revision-info'       => 'Έκδοση σα $1 ασόν/ασήν $2',
'previousrevision'    => '←Παλαιόν μορφήν',
'nextrevision'        => 'Κι άλλο καινούρεον μορφήν→',
'currentrevisionlink' => 'Ατωριζνόν μορφήν',
'cur'                 => 'ατωριζνόν',
'next'                => 'επόμενον',
'last'                => 'υστερνόν',
'page_first'          => 'πρώτον',
'page_last'           => 'υστερνόν',
'histlegend'          => 'Σύγκριμα διαφορίων: βαλέστεν τα μορφάς το θέλετε και τερέστεν τα διαφοράσατουν. Για να τερείτε τα διαφοράς, ποίστεν έναν κλικ σο πεδίον το λεει "Γαρσουλαεύτε...". <br />
Πληροφορία: (ατωριζνόν) = διαφοράς με τ\' ατωριζνόν τη μορφήν,
(υστερνόν) = διαφοράς με τ\' υστερνόν τη μορφήν, μ = μικρά διαφοράς.',
'histfirst'           => "Ασ' όλεα παλαιόν",
'histlast'            => "Ασ' όλεα καινούρ'",
'historyempty'        => '(εύκαιρον)',

# Revision feed
'history-feed-item-nocomment' => '$1 σο $2', # user at time

# Diffs
'history-title'           => 'Ιστορικόν εκδοσίων για τη σελίδαν "$1"',
'difference'              => '(Διαφορά μεταξύ τη μορφίων)',
'lineno'                  => 'Γραμμή $1:',
'compareselectedversions' => 'Γαρσουλαεύτε...',
'editundo'                => 'αναίρεση',
'diff-multi'              => "({{PLURAL:$1|Μίαν αλλαγήν|$1 αλλαγάς}} 'κ δεκνίζκουνταν.)",

# Search results
'noexactmatch'          => "'''Η Βικιπαίδειαν 'κ εχ' σελίδαν με τ' όνεμαν \"\$1\".'''
Εμπορείτε να [[:\$1|εφτάτε ατέναν]].",
'prevn'                 => '$1 προηγουμένων',
'nextn'                 => '$1 επομένων',
'viewprevnext'          => 'Τέρεν ($1) ($2) ($3)',
'search-suggest'        => 'Γιαμ αραεύετε: $1',
'search-interwiki-more' => '(πλέα)',
'searchall'             => 'ούλαι',
'powersearch'           => 'Αναλυτικόν αράεμαν',

# Preferences page
'preferences'   => 'Προτιμήσαι',
'mypreferences' => "Τ' εμά τα προτιμήσαι",
'skin-preview'  => 'Πρώτον τέρεμα',
'retypenew'     => 'Γράψον ξαν το νέον σημάδιν:',

'grouppage-sysop' => '{{ns:project}}:Διαχειριστάδες',

# User rights log
'rightslog' => 'Αρχείον δικαιωματίων',

# Recent changes
'nchanges'                       => '$1 {{PLURAL:$1|αλλαγή|αλλαγάς}}',
'recentchanges'                  => 'Υστερνά αλλαγάς',
'recentchanges-feed-description' => "Τ' ασ' όλεα καινούρεα αλλαγάς τη wiki ωρία σ' αβούτεν την περίληψην.",
'rcnote'                         => "Αφκά {{PLURAL:$1|έχ' '''1''' αλλαγήν|έχ' τα '''$1''' τελευταία αλλαγάς}} τη {{PLURAL:$2|τελευταίας ημέρας|τελευταίων '''$2''' ημερίων}}, σα $5, $4.",
'rcnotefrom'                     => "Αφκά καικά ευρίουνταν τ' αλλαγάς ασό <b>$2</b> (εμφάνιση <b>$1</b> αλλαγίων max).",
'rclistfrom'                     => "Καινούρεα αλλαγάς ντ' εσκαλών'νε ασά $1",
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

# Recent changes linked
'recentchangeslinked'          => 'Σχετικά αλλαγάς',
'recentchangeslinked-title'    => 'Αλλαγάς τη "$1"',
'recentchangeslinked-noresult' => "Σ' αβούτα τα σελίδας 'κ εγένταν αλλάγματα.",
'recentchangeslinked-summary'  => "Αβούτος εν κατάλογον με τ' υστερνά τ' αλλαγάς σελιδίων με σύνδεσμον ασ' έναν συγκεκριμένον σελίδαν (για σε σελίδας συγκεκριμένου κατηγορίας).
Τα σελίδας σον [[Special:Watchlist|κατάλογον ωριαγματί]] είν' '''σκηρά'''.",

# Upload
'upload'        => 'Φόρτωμα αρχείου',
'uploadbtn'     => 'Φόρτωμα αρχείου',
'uploadlogpage' => 'Αρχείον ανεβασματίων',
'uploadedimage' => 'Εγέντον το φόρτωμαν τη "[[$1]]"',

# Special:ImageList
'imagelist' => 'Λίσταν εικονίων',

# Image description page
'filehist'                  => 'Ιστορικό αρχείου',
'filehist-help'             => "Εφτάτε κλικ σ' έναν ημερομηνίαν/ώραν απάν αέτς για να τερείτε πως έτον τ' αρχείον σ' εκείνεν την ώραν.",
'filehist-current'          => 'υστερινά',
'filehist-datetime'         => 'Ώραν/Ημερομ.',
'filehist-user'             => 'Χρήστες',
'filehist-dimensions'       => 'Διαστάσεις',
'filehist-filesize'         => 'Μέγεθος',
'filehist-comment'          => 'Σχόλιον',
'imagelinks'                => 'Σύνδεσμοι',
'linkstoimage'              => "Ατά τα {{PLURAL:$1|σελίδαν δεκνίζ'|$1 σελίδας δεκνίζ'νε}} σην εικόναν:",
'nolinkstoimage'            => "'Κ εχ σελίδας ντο δεκνίζνε σ' αβούτεν εικόναν.",
'sharedupload'              => "Αβούτον τ' αρχείον εφορτώθεν για κοινόν κουλάνεμαν κι εν δυνατόν να χρησιμοπισκάται και σ' άλλα έργα.",
'noimage'                   => "Αρχείον με αείκον όνεμαν 'κ έχ', άμα επορείς να $1.",
'noimage-linktext'          => "σκώσ' ατό",
'uploadnewversion-linktext' => "Σκώσ' καινούραιον έκδοσην τ' αρχείου",

# MIME search
'mimesearch' => 'Αράεμαν MIME',

# List redirects
'listredirects' => 'Κατάλογον με διπλά συνδέσμ',

# Unused templates
'unusedtemplates' => "Πρότυπα ντο 'κ μεταχειρίσκουνταν",

# Random page
'randompage' => 'Τυχαίον σελίδα',

# Random redirect
'randomredirect' => 'Τυχαία διπλά συνδέσμ',

# Statistics
'statistics' => 'Στατιστικήν',

'disambiguations' => 'Σελίδας εξηγησίων',

'doubleredirects' => 'Περισσά διπλά συνδέσμ',

'brokenredirects' => 'Τσακωμένα διπλά συνδέσμ',

'withoutinterwiki' => "Σελίδας ντο κ' έχνε συνδέσμ",

'fewestrevisions' => "Σελίδας με τ' ασόλων λιγότερα αλλαγάς.",

# Miscellaneous special pages
'nbytes'                  => '$1 {{PLURAL:$1|byte|bytes}}',
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
'mostlinked'              => "Σελίδας με τ' ασόλων πλέα σελίδας ντο δεκνίζν' εκαικά.",
'mostlinkedcategories'    => "Κατηγορίας με τ' ασόλων πλέα σελίδας ντο δεκνίζν' εκαικά.",
'mostlinkedtemplates'     => "Πρότυπα με τ' ασόλων πλέα σελίδας ντο δεκνίζν' εκαικά",
'mostcategories'          => "Σελίδας με τ' ασ' όλτς πολλά κατηγορίας",
'mostimages'              => "Αρχεία με τ' ασόλων πλέα σελίδας ντο δεκνίζν' εκαικά",
'mostrevisions'           => "Σελίδας με τ' ασόλων πλέα αλλαγάς",
'prefixindex'             => 'Κατάλογος κατά πρόθεμαν',
'shortpages'              => 'Μικρά σελίδας',
'longpages'               => 'Τρανά σελίδας',
'deadendpages'            => 'Αδιέξοδα σελίδας',
'protectedpages'          => 'Ασπαλιγμένα σελίδας',
'listusers'               => 'Κατάλογον χρήστιων',
'newpages'                => 'Καινούρεα σελίδας',
'ancientpages'            => 'Ασ’ όλιον παλαιά σελίδας',
'move'                    => 'Ετεροχλάεμαν',
'movethispage'            => "Άλλαξον τ' όνεμα τη σελίδας",

# Book sources
'booksources'               => 'Βιβλιογραφικά πηγάς',
'booksources-search-legend' => 'Αράεμαν τη βιβλίων',
'booksources-go'            => 'Δέβα',

# Special:Log
'specialloguserlabel'  => 'Χρήστες:',
'speciallogtitlelabel' => 'Τίτλος:',
'log'                  => 'Αρχεία',
'all-logs-page'        => "Όλεα τ' αρχεία",

# Special:AllPages
'allpages'       => 'Όλεα τα σελίδας',
'alphaindexline' => '$1 ους $2',
'nextpage'       => 'Επόμενον σελίδα ($1)',
'prevpage'       => 'Προηγούμενον σελίδα ($1)',
'allpagesfrom'   => "Τέρεμαν σελιδίων ντ' εσκαλών'νε ασό:",
'allarticles'    => 'Όλεα τα σελίδας',
'allpagessubmit' => 'Δέβα',
'allpagesprefix' => 'Τέρεμαν σελιδίων με πρόθεμαν:',

# Special:Categories
'categories'         => 'Κατηγορίας',
'categoriespagetext' => 'Τα κατηγορίας αφκά καικά έχνε σελίδας και μέσα.',

# E-mail user
'emailuser' => 'Στείλον μένεμαν σον χρήστεν ατόν.',

# Watchlist
'watchlist'            => "Σελίδας ντ' ωριάζω",
'mywatchlist'          => "Σελίδας ντ' ωριάζω",
'watchlistfor'         => "(για '''$1''')",
'addedwatch'           => 'Εθέκεν σην λίσταν ωριαγματί',
'addedwatchtext'       => "Η σελίδαν \"[[:\$1]]\" επήγεν σον [[Special:Watchlist|κατάλογον οριαγματί]] εσούν.
Μελλούμενα αλλαγάς τ' ατεινές τη σελίδας θα γράφκουνταικεκά, και η σελίδαν θ' ευρίεται με γράμματα '''χοντρά''' σ' [[Special:RecentChanges|υστερνά τ' αλλαγάς]] για να τερείτετα καλίον.",
'removedwatch'         => 'Αση λίσταν επάρθεν',
'removedwatchtext'     => 'Η σελίδαν "[[:$1]]" νεβζινέθεν ασ\' [[Special:Watchlist|τ\'εσόν τον κατάλογον]].',
'watch'                => 'Ωρίαγμαν',
'watchthispage'        => 'Ωρίαν τη σελίδαν',
'unwatch'              => "Τέλεμαν τ' ωριαγματί",
'watchlist-details'    => '{{PLURAL:$1|$1 σελίδα|$1 σελίδας}} ωριάσκουνταν, θέγα τα σελίδας καλάτσεματι.',
'wlshowlast'           => "Φανέρωμαν τ' υστερναίων $1 ωρίων $2 ημερίων $3",
'watchlist-hide-bots'  => "Κρύψον τ' αλλαγάς τη bots",
'watchlist-hide-own'   => "Κρύψον τ' αλλαγάς 'ιμ",
'watchlist-hide-minor' => 'Κρύψον τα μικρά αλλαγάς',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'Ωριάζω...',
'unwatching' => "'κ ωριάζω...",

# Delete/protect/revert
'deletepage'                  => 'Σβήσον τη σελίδαν',
'historywarning'              => "Ωρία: Η σελίδαν που θα σβήετε έχ' ιστορικόν:",
'confirmdeletetext'           => "Είστουν σουμά σο σβήσεμαν είνος σελίδας και ούλ' τ' ιστορίασατς εντάμαν.
Παρακαλούμε σας να δίτε το τελικόν τη βεβαίωσην το θέλετε να εφτάτε το σβήσεμαν, τ' εγροικάτε τα συνέπειας τ' ατεινές τη πράξης και τ' εφτάτ' ατεν με βάσην [[{{MediaWiki:Policy-url}}|τη πολιτικήν]].",
'actioncomplete'              => 'Η ενέργειαν ετελέθεν',
'deletedtext'                 => 'Το "<nowiki>$1</nowiki>" εσβήγανατο.
Τερέστεν το $2 και δεαβάστεν για τα υστερνά τα σβησίματα.',
'deletedarticle'              => 'νεβζινέθεν η "[[$1]]"',
'dellogpage'                  => "Κατάλογος με τ' ατά ντ' εσβήγαν",
'deletecomment'               => 'Λόγον για το σβήσιμο:',
'deleteotherreason'           => 'Άλλον/αλλομίαν λόγον:',
'deletereasonotherlist'       => 'Άλλον λόγον',
'rollbacklink'                => 'φέρον ξαν σην υστερναίαν',
'protectlogpage'              => 'Αρχείον ασπαλιγματίων',
'protectcomment'              => 'Σχόλιον:',
'protectexpiry'               => 'Τελείται:',
'protect_expiry_invalid'      => "Ο χρόνος τελεματί 'κ εν σωστόν.",
'protect_expiry_old'          => 'Ο χρόνος τελεματί πέρνιξον.',
'protect-unchain'             => 'Άνοιξον τα δικαιώματα ετεροχλάεματι',
'protect-text'                => "Αδά επορείτε να τερείτε και ν' αλλάζετε τ' επίπεδον τη προστασίας για τη σελίδαν <strong><nowiki>$1</nowiki></strong>.",
'protect-locked-access'       => "Η λογαρίανεσουν 'κ έχ' το δικαίωμαν να αλλάζ' τ' ασπάλιγμαν τη σελίδας.
Αδά έχ' τ' ατωριζνά τα νομς για τη σελίδαν <strong>$1</strong>:",
'protect-default'             => '(προεπιλεγμένον)',
'protect-fallback'            => 'Ψαλαφίον δικαιωματίων "$1"',
'protect-level-autoconfirmed' => 'Ασπάλιγμαν χρηστίων θίχως λογαρίαν',
'protect-level-sysop'         => 'Διαχειριστάδες μαναχόν',
'protect-summary-cascade'     => 'διαδοχικόν',
'protect-expiring'            => 'λήγει στις $1 (UTC)',
'protect-cascade'             => "Ασπάλιγμαν σελιδίων ντ' είν απές σ' αβούτεν σελίδαν (διαδοχικόν προστασίαν)",
'protect-cantedit'            => "'Κι έχετε δικαίωμαν ν' αλλάζετε τ' επίπεδον ασπάλιγματι τ' ατεινές σελίδας.",
'restriction-type'            => 'Δικαίωμαν:',
'restriction-level'           => 'Επίπεδον περιορισμού:',

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
'sp-contributions-blocklog'    => 'Αρχείον ασπαλιγματίων',

# What links here
'whatlinkshere'       => "Ντο δεκνίζ' αδακές",
'whatlinkshere-title' => 'Σελίδας ντο συνδέουν ση σελίδαν $1',
'whatlinkshere-page'  => 'Σελίδαν:',
'linkshere'           => "Αβούτα τα σελίδας δεκνίζνε σο '''[[:$1]]''':",
'nolinkshere'         => "'Κ ευρέθεν σελίδα το δεκνίζ' ση σελίδαν '''[[:$1]]'''.",
'isredirect'          => 'σελίδαν διπλού σύνδεσμονος',
'istemplate'          => 'ενσωμάτωση',
'whatlinkshere-prev'  => '{{PLURAL:$1|προτεσνή|προτεσνά $1}}',
'whatlinkshere-next'  => '{{PLURAL:$1|υστερνή|υστερναία $1}}',
'whatlinkshere-links' => '← σύνδεσμοι',

# Block/unblock
'blockip'       => 'Ασπάλιγμαν τη χρήστε',
'ipboptions'    => '2 ώρας:2 hours,1 ημέρα:1 day,3 ημέρας:3 days,1 εβδομάδα:1 week,2 εβδομάδας:2 weeks,1 μήνα:1 month,3 μήνας:3 months,6 μήνας:6 months,1 χρόνο:1 year,αόριστα:infinite', # display1:time1,display2:time2,...
'ipblocklist'   => "Λίσταν με τ' ασπαλιγμένα τα IP και τα λογαρίας",
'blocklink'     => 'Ασπάλιγμαν',
'unblocklink'   => 'άνοιγμαν ασπαλιγματί',
'contribslink'  => "Δουλείαν ατ'",
'blocklogpage'  => 'Αρχείον ασπαλιγματίων',
'blocklogentry' => 'εσπάλισεν [[$1]] για $2 $3',

# Move page
'movepagetext'            => "Εάν εφτάτε το ψαλαφίον αφκά θα δίτε άλλον όνομαν σ' έναν σελίδαν και θα παίρτεν τ' ιστορικόνατς εκαικά. Το παλαιόν η σελίδαν θα μεταβάλκεται σε σύνδεσμον σην καινούραιαν.

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
'movepage-moved'          => '<big>\'\'\'"$1" επήγεν σο "$2"\'\'\'</big>', # The two titles are passed in plain text as $3 and $4 to allow additional goodies in the message.
'articleexists'           => 'Σελίδαν με αήκον όνεμαν υπάρχει.
Βαλέστεν άλλο όνεμαν.',
'cantmove-titleprotected' => "'Κ επορείτε ν' εφτάτε σελίδαν με τ' αβούτον τ' όνεμαν επειδή εσπάλισανατο.",
'talkexists'              => "'''Η σελίδαν ετερχλαεύτεν, άμα η σελίδαν καλατσεματί επέμνεν επειδή σο καινούρεον τίτλον έχ' άλλον.
Ποίστεν τα ένα.'''",
'movedto'                 => 'ετεροχλαεύτεν σο',
'movetalk'                => 'Ετεροχλάεμαν τη σελίδας καλατσεματί',
'1movedto2'               => '[[:$1]] ετερχλαεύτεν σο [[:$2]]',
'1movedto2_redir'         => '[[:$1]] ετερχλαεύτεν σο [[:$2]] σε σύνδεσμον απάν',
'movelogpage'             => 'Αρχείον ετεροχλαεματί',
'movereason'              => 'Λόγον:',
'revertmove'              => 'επαναφορά',

# Export
'export'            => 'Εξαγωγήν σελίδιων',
'export-addcattext' => 'Βαλέστεν σελίδας ασήν κατηγορίαν:',
'export-addcat'     => 'Βαλέστεν',
'export-download'   => 'Αποθήκεμαν άμον αρχείον',

# Namespace 8 related
'allmessages'     => 'Μενέματα συστηματί',
'allmessagesname' => 'Όνεμαν',

# Thumbnails
'thumbnail-more'  => 'Ποίσον κι άλλο τρανόν',
'thumbnail_error' => 'Έντον λάθος ση δημιουργίαν τη μικρογραφίας: $1',

# Special:Import
'import'                  => 'Εισαγωγήν σελιδίων',
'import-interwiki-submit' => 'Εισαγωγήν',
'importstart'             => 'Εισαγωγή σελιδίων...',

# Import log
'importlogpage' => 'Αρχείον εισαγωγίων',

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
'tooltip-n-currentevents'         => "Εύρον άλλα πληροφορίας για τ' ατά ντ' ίντανε οψεκές.",
'tooltip-n-recentchanges'         => "Κατάλογος με τ' υστερνά αλλαγάς σο wiki.",
'tooltip-n-randompage'            => 'Κατά τύχην εύρον σελίδαν και δείξον ατέν',
'tooltip-n-help'                  => "Αδά θα ευρίετε τα απαντήσεις ντ' αραεύετε.",
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
'previousdiff' => '← Προτεσνόν διαφορά',
'nextdiff'     => 'Άλλον διαφορά →',

# Media information
'file-info-size'       => '($1 × $2 εικονοστοιχεία, μέγεθος αρχείου: $3, MIME τύπος: $4)',
'file-nohires'         => "<small>'Κ εχ κι άλλο ψηλόν ανάλυσην.</small>",
'svg-long-desc'        => "(Αρχείον SVG, κατ' όνομα $1 × $2 εικονοστοιχεία, μέγεθος αρχεί: $3)",
'show-big-image'       => 'Τζιπ τρανόν ανάλυση',
'show-big-image-thumb' => "<small>Μέγεθος τη πρώτ' τερεματί: $1 × $2 εικονοστοιχεία</small>",

# Special:NewImages
'newimages' => 'Τερέστεν τα καινούρεα φωτογραφίας',
'ilsubmit'  => 'Αράεμαν',
'bydate'    => 'ημερομηνίας',

# Bad image list
'bad_image_list' => "Η σύνταξην εν αέτς:

Τα αντικείμενα τη λίστας (τα γραμμάς ντ' αχπάσκουνταν με *) και μόνον τερούμε. Ο πρώτον ο σύνδεσμος σε μιαν γραμμήν πρέπ' να δεκνίζ' σε κακόν αρχείον.
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
* focallength", # Do not translate list items

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

# External editor support
'edit-externally'      => "Αλλαγήν τ' αρχείου με προγράμματα ασα εξ μερέα",
'edit-externally-help' => 'Τερέστεν τα [http://www.mediawiki.org/wiki/Manual:External_editors setup instructions] και θα ευρίετε κι άλλα γνώσιας.',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'ούλαι',
'imagelistall'     => 'ούλαι',
'watchlistall2'    => 'ούλαι',
'namespacesall'    => 'ούλαι',
'monthsall'        => 'ούλαι',

# Multipage image navigation
'imgmultigo' => 'Δέβα!',

# Table pager
'table_pager_limit_submit' => 'Δέβα',

# Watchlist editing tools
'watchlisttools-view' => 'Τερέστεν σοβαρά αλλαγάς',
'watchlisttools-edit' => 'Τέρεν κι άλλαξον κατάλογον ωρίαγματι',
'watchlisttools-raw'  => 'Επεξεργαστείτε την πρωτογενή τη λίσταν ωριαγματί',

# Special:Version
'version' => 'Έκδοση', # Not used as normal message but as header for the special page itself

# Special:SpecialPages
'specialpages' => 'Ειδικά σελίδας',

);
