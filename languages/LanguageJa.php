<?php
global $IP;
include_once( "LanguageUtf8.php" );

# The names of the namespaces can be set here, but the numbers
# are magical, so don't change or move them!  The Namespace class
# encapsulates some of the magic-ness.
# 名前空間の名前はここで設定できますが、番号は特別なので、
# 変更したり移動したりしないでね! 名前空間クラスは特殊性を幾らか
# 隠匿します。
#
/* private */ $wgNamespaceNamesJa = array(
	-2	=> "Media",
	-1	=> "特別" /* "Special" */, 
	0	=> "",
	1	=> "ノート" /* "Talk" */,
	2	=> "利用者" /* "User" */,
	3	=> "利用者‐会話" /* "User_talk" */,
	4	=> $wgMetaNamespace /* "Wikipedia" */,
	5	=> $wgMetaNamespace."‐ノート" /* "Wikipedia_talk" */,
	6	=> "画像" /* "Image" */,
	7	=> "画像‐ノート" /* "Image_talk" */,
	8	=> "MediaWiki",
	9	=> "MediaWikiノート",
);

/* private */ $wgQuickbarSettingsJa = array(
	"None", "Fixed left", "Fixed right", "Floating left"
);

/* private */ $wgSkinNamesJa = array(
	"標準", "ノスタルジア", "ケルンブルー"
);

/* private */ $wgUserTogglesJa = array(
	"hover"		=> "Show hoverbox over wiki links",
	"underline" => "Underline links",
	"highlightbroken" => "Highlight links to empty topics",
	"justify"	=> "Justify paragraphs",
	"hideminor" => "Hide minor edits in recent changes",
	"numberheadings" => "Auto-number headings",
	"showtoolbar" => "Show edit toolbar",
	"rememberpassword" => "Remember password across sessions",
	"editwidth" => "Edit box has full width",
	"editondblclick" => "Edit pages on double click (JavaScript)",
	"watchdefault" => "Watch new and modified articles",
	"minordefault" => "Mark all edits minor by default"
);

/* private */ $wgWeekdayNamesJa = array(
	"日曜日", "月曜日", "火曜日", "水曜日", "木曜日",
	"金曜日", "土曜日"
);

/* private */ $wgMonthNamesJa = array( # ???
	"一月", "二月", "三月", "四月", "五月", "六月",
	"七月", "八月", "九月", "十月", "十一月",
	"十二月"
);

/* private */ $wgMonthAbbreviationsJa = array(
	"1月", "2月", "3月", "4月", "5月", "6月",
	"7月", "8月", "9月", "10月", "11月", "12月"
);

# All special pages have to be listed here: a description of ""
# will make them not show up on the "Special Pages" page, which
# is the right thing for some of them (such as the "targeted" ones).
# 全ての特殊頁はここに列挙しないといけません。
# "" (空文字列) という説明にすると「特殊頁」頁に掲載しません。
# これは幾つか (「targeted」とか) に対しては適当なことです。

 
/* private */ $wgValidSpecialPagesJa = array(
	"Userlogin"		=> "",
	"Userlogout"	=> "",
	"Preferences"	=> "ユーザー設定を保存",
	"Watchlist"		=> "ウォッチリスト",
	"Recentchanges" => "最近更新したページ",
	"Upload"		=> "画像をアップロードする",
	"Imagelist"		=> "画像リスト",
	"Listusers"		=> "登録済みユーザー",
	"Statistics"	=> "サイトの統計",
	"Randompage"	=> "ランダム記事",

	"Lonelypages"	=> "孤立したページ",
	"Unusedimages"	=> "孤立した画像",
	"Popularpages"	=> "人気の記事",
	"Wantedpages"	=> "執筆が待望されている記事",
	"Shortpages"	=> "短い記事",
	"Longpages"		=> "長い記事",
	"Newpages"		=> "新しく登場した記事",
	"Ancientpages"	=> "Oldest articles",
	"Allpages"		=> "タイトル別全ページ",

	"Ipblocklist"	=> "ブロックされたIPアドレス",
	"Maintenance" => "管理ページ",
	"Specialpages"  => "",
	"Contributions" => "",
	"Emailuser"		=> "",
	"Whatlinkshere" => "",
	"Recentchangeslinked" => "",
	"Movepage"		=> "",
	"Booksources"	=> "外部の参考文献",
	"Export"	=> "XML export",
	"Version"	=> "Version",
);

/* private */ $wgSysopSpecialPagesJa = array(
	"Makesysop" => "Turn a user into a sysop",
	"Blockip"		=> "IPアドレスをブロック",
	"Asksql"		=> "データベースに問い合わせ",
	"Undelete"		=> "消去されたページを閲覧し、復帰させる"
);

/* private */ $wgDeveloperSpecialPagesJa = array(
	"Lockdb"		=> "データベースを読み出し専用にする",
	"Unlockdb"		=> "データベースを書き込み可能にする",
);

/* private */ $wgAllMessagesJa = array(

# Bits of text used by many pages:
#
"mainpage"		=> "メインページ",
"about"			=> "About",
"aboutwikipedia" => "Wikipediaについて",
"aboutpage"		=> "{$wgMetaNamespace}:About",
"help"			=> "ヘルプ",
"helppage"		=> "{$wgMetaNamespace}:Help",
"wikititlesuffix" => "Wikipedia",
"bugreports"	=> "バグの報告",
"bugreportspage" => "{$wgMetaNamespace}:バグの報告",
"faq"			=> "FAQ",
"faqpage"		=> "{$wgMetaNamespace}:FAQ",
"edithelp"		=> "ヘルプを編集",
"edithelppage"	=> "{$wgMetaNamespace}:編集の仕方",
"cancel"		=> "中止",
"qbfind"		=> "検索",
"qbbrowse"		=> "閲覧",
"qbedit"		=> "編集",
"qbpageoptions" => "ページ・オプション",
"qbpageinfo"	=> "ページ情報",
"qbmyoptions"	=> "オプション",
"mypage"		=> "マイ・ページ",
"mytalk"		=> "マイ・トーク",
"currentevents" => "最近の出来事",
"errorpagetitle" => "エラー",
"returnto"		=> "$1 に戻る。",
"fromwikipedia"	=> "出典: フリー百科事典『ウィキペディア（Wikipedia）』",
"whatlinkshere"	=> "ここにリンクしているページ",
"help"			=> "ヘルプ",
"search"		=> "検索",
"history"		=> "履歴",
"printableversion" => "印刷用バージョン",
"editthispage"	=> "このページを編集",
"deletethispage" => "このページを削除",
"protectthispage" => "このページを保護",
"unprotectthispage" => "ページ保護解除",
"talkpage"		=> "この記事のノート",
"subjectpage"	=> "サブジェクト・ページ",
"otherlanguages" => "他の言語",
"redirectedfrom" => "($1 から転送)",
"lastmodified"	=> "最終更新 $1。",
"viewcount"		=> "このページは $1 回アクセスされました。",
"printsubtitle" => "(From http://www.wikipedia.org)",
"protectedpage" => "保護されたページ",
"administrators" => "{$wgMetaNamespace}:Administrators",
"sysoptitle"	=> "シスオペによるアクセスが必要",
"sysoptext"		=> "あなたの要求した処理は \"sysop\" のみが実行できます。
 $1を参照してください。",
"developertitle" => "開発者によるアクセスが必要",
"developertext"	=> "あなたの要求した処理を実行できるのは、 \"developer\" のみです。 $1を参照してください。",
"nbytes"		=> "$1 バイト",
"go"			=> "行く",
"ok"			=> "OK",
"sitetitle"		=> "Wikipedia",
"sitesubtitle"	=> "フリー百科事典",
"retrievedfrom" => "Retrieved from \"$1\"",

# Main script and global functions
#
"nosuchaction"	=> "そのような動作はありません",
"nosuchactiontext" => "URI で指定された動作は Wikipedia で認識できません。",
"nosuchspecialpage" => "そのような特別ページはありません。",
"nospecialpagetext" => "その特別ページの要求は Wikipedia には理解できません。",

# General errors
#
"error"			=> "エラー",
"databaseerror" => "データベース・エラー",
"dberrortext"	=> "データベース検索の文法エラー。
検索問合わせが間違っているか($5 を参照),
あるいはソフトウェアのバグかもしれません。
最後に実行を試みた問い合わせ: 
<blockquote><tt>$1</tt></blockquote>
from within function \"<tt>$2</tt>\".
MySQL returned error \"<tt>$3: $4</tt>\".",
"noconnect"		=> "$1 のデータベースに接続できません。",
"nodb"			=> "$1 のデータベースを選択できません。",
"readonly"		=> "データベースはロックされています",
"enterlockreason" => "ロックする理由を入力して下さい。ロックが解除されるのがいつになるかの見積もりについても述べて下さい。",
"readonlytext"	=> "ウィキペディア・データベースは現在、新しい記事の追加や修正を受け付けない「ロック」状態になっています。これはおそらくは定期的なメンテナンスのためで、メンテナンス終了後は正常な状態に復帰します。
データベースをロックした管理者は次のような説明をしています：
<p>$1
<p>The Wikipedia database is currently locked to new
entries and other modifications, probably for routine database maintenance,
after which it will be back to normal.
The administrator who locked it offered this explanation:
<p>$1",
"missingarticle" => "データベースは、\"$1\"という題のページの、存在するはずの文章を見つけることができませんでした。
<p>これはデータベースのエラーではなく、ソフトウェアのバグだろうと思われます。
<p>URI と共に管理者に報告して下さるようにお願いします。
<p>The database did not find the text of a page
that it should have found, named \"$1\".
<p>This is not a database error, but likely a bug in the software.
<p>Please report this to an administrator, making note of the URI.",
"internalerror" => "内部処理エラー Internal error",
"filecopyerror" => "ファイルを\"$1\"から\"$2\"へ複製できませんでした。Could not copy file \"$1\" to \"$2\".",
"filerenameerror" => "ファイル名を\"$1\"から\"$2\"へ変更できませんでした。Could not rename file \"$1\" to \"$2\".",
"filedeleteerror" => "ファイル\"$1\"を削除できませんでした。Could not delete file \"$1\".",
"filenotfound"	=> "ファイルを\"$1\"は見つかりませんでした。Could not find file \"$1\".",
"unexpected"	=> "エラー：\"$1\" と \"$2\" が同じです。Unexpected value: \"$1\"=\"$2\".",
"formerror"		=> "エラー: フォームを送信できませんでした。 Error: could not submit form",	
"badarticleerror" => "この動作はこのページではとることができません。 This action cannot be performed on this page.",
"cannotdelete"	=> "指定されたページ、または画像を削除できませんでした。 Could not delete the page or image specified.",

# Login and logout pages
#
"logouttitle"	=> "ユーザー ログアウト",
"logouttext"	=> "
ログアウトしました。
ウィキペディアを匿名で使い続うことができます。
あるいはログインして元の、あるいは別のユーザーとして使うこともできます。
<P>You are now logged out.
You can continue to use Wikipedia anonymously, or you can log in
again as the same or as a different user.\n",

"welcomecreation" => "<h2>$1 さん、ようこそ!</h2><p>あなたのアカウントができました。
お好みに合わせてユーザーオプションを変更することをお忘れなく。",

"loginpagetitle" => "ユーザー・ログイン",
"yourname"		=> "あなたのユーザー名",
"yourpassword"	=> "あなたのパスワード",
"yourpasswordagain" => "パスワード再入力",
"newusersonly"	=> " (新規ユーザのみ)",
"remembermypassword" => "セッションをまたがってパスワードを保持する。",
"loginproblem"	=> "<b>ログインでエラーが発生しました。</b><br>再度実行してください。",
"alreadyloggedin" => "<font color=red><b>ユーザ $1 は、すでにログイン済みです。</b></font><br>\n",

"login"			=> "ログイン",
"userlogin"		=> "ログイン",
"logout"		=> "ログアウト",
"userlogout"	=> "ログアウト",
"createaccount"	=> "新規アカウント作成",
"badretype"		=> "両方のパスワードが一致しません。",
"userexists"	=> "そのユーザー名はすでに使われています。ほかの名前をお選びください。",
"youremail"		=> "電子メール",
"yournick"		=> "ニックネーム (署名用)",
"emailforlost"	=> "パスワードを忘れたときには、あたらしいパスワードを電子メールで受け取ることが出来ます。",
"loginerror"	=> "ログイン・エラー",
"noname"		=> "ユーザ名を正しく指定していません。",
"loginsuccesstitle" => "ログイン成功",
"loginsuccess"	=> "あなたは現在 Wikipedia に \"$1\" としてログインしています。",
"nosuchuser"	=> " \"$1\" というユーザーは見当たりません。
綴りが正しいか再度確認するか、下記のフォームを使ってアカウントを作成してください。",
"wrongpassword"	=> "パスワードが間違っています。再度入力してください。",
"mailmypassword" => "新しいパスワードを、メールで送る",
"passwordremindertitle" => "Password reminder from Wikipedia （ウィキペディアからのパスワードのお知らせ）",
"passwordremindertext" => "どなたか ($1 のIPアドレスの使用者)が、Wikipediaのログイン・パスワードの再発行を依頼しました。
ユーザ \"$2\" のパスワードを、 \"$3\" に変更しました。
ログイン後、別のパスワードに変更しましょう。",
"noemail"		=> "ユーザ \"$1\" のメール・アドレスは登録されていません。",
"passwordsent"	=> "あたらしいパスワードは \"$1\" さんの登録済みメール・アドレスにお送りしました。メールを受け取ったら、再度ログインしてください。",

# Edit pages
#
"summary"		=> "要約",
"minoredit"		=> "これは細部の修正です。",
"savearticle"	=> "ページを保存",
"preview"		=> "プレビュー",
"showpreview"	=> "プレビューを実行",
"blockedtitle"	=> "ユーザはブロックされています。",
"blockedtext"	=> "あなたのユーザ名またはIPアドレスは $1 によってブロックされています。
その理由は次の通りです。:<br>$2<p>詳細は管理者にお問い合わせください。",
"newarticle"	=> "(新規)",
"newarticletext" => "新しい記事を書き込んでください。",
"noarticletext" => "(このページには現在記事がありません。)",
"updated"		=> "(更新)",
"note"			=> "<strong>注釈:</strong> ",
"previewnote"	=> "これはプレビューです。まだ保存されていません!",
"previewconflict" => "このプレビューは、上の文章編集エリアの文章を保存した場合に
どう見えるようになるかを示すものです。
<p>" /* "This preview reflects the text in the upper 
text editing area as it will appear if you choose to save." */,
"editing"		=> "Editing $1",
"editconflict"	=> "編集競合: $1",
"explainconflict" => "あなたがこのページを編集し始めてから誰か他の人が
このページを変更してしまいました。
上の文章エリアは現在の最新の状態を反映しています。
あなたの加える変更の内容は下の文章エリアに示されています。
変更内容を、上の文章エリアの内容に組み込んで下さい。
<b>上の文章エリアの内容だけ</b>が、\"Save page\"をクリックした時に
保存されることになります。\n<p>"
/* Someone else has changed this page since you
started editing it.
The upper text area contains the page text as it currently exists.
Your changes are shown in the lower text area.
You will have to merge your changes into the existing text.
<b>Only</b> the text in the upper text area will be saved when you
press \"Save page\".\n<p>" */,
"yourtext"		=> "あなたの文章",
"storedversion" => "保存された版",
"editingold"	=> "<strong>警告: あなたはこのページの古い版を
編集しています。もしもこの文章を保存すると、この版以降に追加された
全ての変更が無効になってしまいます。</strong>",
/* <p><strong>WARNING: You are editing an out-of-date
revision of this page.
If you save it, any changes made since this revision will be lost.</strong>\n" */
"yourdiff"		=> "あなたの更新内容",
"copyrightwarning" => "Wikipediaに投稿された文書は、すべて GNU Free Documentation License によって発行されたものとみなされますので、留意してください。
<p>(詳細は $1 を参照, また、参考までに非公式日本語訳は &lt;http://www.opensource.jp/fdl/fdl.ja.html&gt;　を参照)。
<p>あなたの文章が他人によって自由に編集、配布されることを望まない場合は、投稿を控えて下さい。
<p>また、あなたの投稿する文章はあなた自身によって書かれたものであるか、パブリック・ドメインかそれに類する自由なリソースからの複製であることを約束して下さい。
<strong>著作権のある作品を許諾なしに投稿してはいけません!</strong>",


# History pages
#
"revhistory"	=> "改訂履歴",
"nohistory"		=> "このページには改訂履歴がありません。  There is no edit history for this page.",
"revnotfound"	=> "要求された版が見つかりません Revision not found",
"revnotfoundtext" => "要求されたこのページの旧版は見つかりませんでした。
URLをもう一度確認して、このページにアクセスしてみて下さい。

The old revision of the page you asked for could not be found.
Please check the URL you used to access this page.\n",
"loadhist"		=> "改訂履歴の読み込み中",
"currentrev"	=> "最新版",
"revisionasof"	=> "$1の版",
"cur"			=> "最新版",
"next"			=> "次の版",
"last"			=> "前の版",
"orig"			=> "最古版",
"histlegend"	=> "凡例: (最新版) = 最新版との比較,
(前の版) = 直前の版との比較, M = 細部の修正",

# Diffs
#
"difference"	=> "(版間での差分)" /* "(Difference between revisions)" */,
"loadingrev"	=> "差分をとるために古い版を読み込んでいます" /*"loading revision for diff" */,
"lineno"		=> "$1 行" /* "Line $1:" */,
"editcurrent"	=> "この頁の最新版を編集" /* "Edit the current version of this page" */,

# 検索結果（Search results）
#
"searchresults" => "検索結果" /* "Search results" */,
"searchhelppage" => "{$wgMetaNamespace}:Searching",
"searchingwikipedia" => "Wikipedia を検索中" /* "Searching Wikipedia" */,
"searchresulttext" => "Wikipedia の検索についての詳しい情報は、 $1 をご覧下さい。" /* "For more information about searching Wikipedia, see $1." */ ,
"searchquery"	=> "問い合わせ \"$1\" について、" /* "For query \"$1\"" */,
"badquery"		=> "おかしな形式の検索問い合わせ" /* "Badly formed search query" */,
"badquerytext"	=> "問い合わせを処理できませんでした。
これはおそらく、3文字未満の語を検索しようとしたためですが、これにはまだ対応していません。
例えば「魚 and and 大きさ」のように、表現を誤記しているのかもしれません。"  /* "We could not process your query.
This is probably because you have attempted to search for a
word fewer than three letters long, which is not yet supported.
It could also be that you have mistyped the expression, for
example \"fish and and scales\".
Please try another query." */,
"matchtotals"	=> "問い合わせ「$1」は $2 の記事の題及び $3 の記事の本文と一致しました。" /* "The query \"$1\" matched $2 article titles
and the text of $3 articles." */,
"titlematches"	=> "記事の題と一致" /* "Article title matches" */,
"notitlematches" => "記事の題とは一致しませんでした" /* "No article title matches" */,
"textmatches"	=> "記事本文と一致" /* "Article text matches" */,
"notextmatches"	=> /* "No article text matches" */ "記事本文とは一致しませんでした",
"prevn"			=> "前 $1" /* "previous $1" */,
"nextn"			=> "次 $1" /* "next $1" */,
"viewprevnext"	=> "($1) ($2) ($3) を見る" /* "View ($1) ($2) ($3)." */,
"showingresults" => "$2 からの $1 個の結果を次に示します" /* "Showing below <b>$1</b> results starting with #<b>$2</b>." */,
"nonefound"		=> "<strong>Note</strong>: 検索がうまくいかないのは、「ある」や「から」のような一般的な語で索引付けされていないとか、複数の検索語を指定している (全ての検索語を含む頁だけが結果に示されます。) とかのためかもしれません。" /* "<strong>Note</strong>: unsuccessful searches are
often caused by searching for common words like \"have\" and \"from\",
which are not indexed, or by specifying more than one search term (only pages
containing all of the search terms will appear in the result)." */,

# Preferences page ユーザーオプション設定頁
#
"preferences"	=> "オプション" /* "Preferences" */, 
"prefsnologin" => "ログインしていません" /* "Not logged in" */,
"prefsnologintext"	=>  "ユーザーオプションを変更するためには、
<a href=\"" .
  wfLocalUrl( "特別:Userlogin" ) . "\">ログイン</a>している必要があります。"
/* "You must be <a href=\"" .
  wfLocalUrl( "Special:Userlogin" ) . "\">logged in</a>
to set user preferences." */,
"prefsreset"	=> "ユーザー設定は初期化されました。" /* "Preferences have been reset from storage." */,
"qbsettings"	=> "クイックバー設定" /* "Quickbar settings" */, 
"changepassword" => "パスワード変更" /* "Change password" */,
"skin"			=> "外装" /* "Skin" */,
"saveprefs"		=> "設定の保存" /* "Save preferences" */,
"resetprefs"	=> "設定の初期化" /* "Reset preferences" */,
"oldpassword"	=> "古いパスワード" /* "Old password" */,
"newpassword"	=> "新しいパスワード" /* "New password" */,
"retypenew"		=> "新しいパスワードを再入力して下さい" /* "Retype new password" */,
"textboxsize"	=> "テキストボックスの大きさ" /* "Textbox dimensions" */,
"rows"			=> "縦" /* "Rows" */,
"columns"		=> "横" /* "Columns" */,
"searchresultshead" => "検索結果の表示" /* "Search result settings" */,
"resultsperpage" => "1ページあたりの表示件数" /* "Hits to show per page" */,
"contextlines"	=> "1件あたりの行数" /* "Lines to show per hit" */,
"contextchars"	=> "1行あたりの文字数" /* "Characters of context per line" */,
"recentchangescount" => "最近更新されたページの表示件数" /* "Number of titles in recent changes" */,
"savedprefs"	=> "ユーザー設定を保存しました" /* "Your preferences have been saved." */,
"timezonetext"  => "UTCとあなたの地域の標準時間との差を入力して下さい" /* "Enter number of hours your local time differs
from server time (UTC)." */,
"localtime"	=> "あなたの地域の標準時間" /* "Local time" */,
"timezoneoffset" =>"差" /* "Offset" */,
"emailflag"		=> "他のユーザーからのメール送付を差し止める" /* "Disable e-mail from other users" */,

# 最近更新したページ（Recent changes）
#
"recentchanges" => "最近更新したページ",
"recentchangestext" => "最近付け加えられた変更はこのページで確認することができます。
[[{$wgMetaNamespace}:新規参加者の方、ようこそ]]！
以下のページも参照して下さい:
[[{$wgMetaNamespace}:ウィキペディア　よくある質問集]],
[[{$wgMetaNamespace}:ウィキペディアの基本方針とガイドライン]]
（特に[[{$wgMetaNamespace}:記事名のつけ方]],
[[{$wgMetaNamespace}:中立的な観点]]）,
[[{$wgMetaNamespace}:ウィキペディアで起こしがちな間違い]].

ウィキペディアが成功するためには、あなたの投稿する内容が他人の著作権などによって束縛されていないことがとても重要です。[[{$wgMetaNamespace}:著作権]]
法的責任問題は、プロジェクトに致命傷を与えることもある問題です。他人の著作物などを流用することは絶対に避けてください。また次のページも参照して下さい。[http://meta.wikipedia.org/wiki/Special:Recentchanges recent meta discussion]"

/* Track the most recent changes to Wikipedia on this page.
[[{$wgMetaNamespace}:Welcome,_newcomers|Welcome, newcomers]]!
Please have a look at these pages: [[{$wgMetaNamespace}:FAQ|Wikipedia FAQ]],
[[{$wgMetaNamespace}:Policies and guidelines|Wikipedia policy]]
(especially [[{$wgMetaNamespace}:Naming conventions|naming conventions]],
[[{$wgMetaNamespace}:Neutral point of view|neutral point of view]]),
and [[{$wgMetaNamespace}:Most common Wikipedia faux pas|most common Wikipedia faux pas]].

If you want to see Wikipedia succeed, it's very important that you don't add
material restricted by others' [[{$wgMetaNamespace}:Copyrights|copyrights]].
The legal liability could really hurt the project, so please don't do it.
See also the [http://meta.wikipedia.org/wiki/Special:Recentchanges recent meta discussion]. */,
"rcloaderr"		=> "最近の更新情報をダウンロード中" /* "Loading recent changes" */,
"rcnote"		=> "以下は最近<strong>$2</strong>日間の<strong>$1</strong>件の更新です。" /* "Below are the last <strong>$1</strong> changes in last <strong>$2</strong> days." */,
# "rclinks"		=> "最近$2時間/$3日間の$1件分を表示する" /* "Show last $1 changes in last $2 hours / last $3 days" */,
"rclinks"		=> "最近$2日間の$1件分を表示する" /* "Show last $1 changes in last $2 days." */,
"rchide"		=> "in $4 form; $1 minor edits; $2 secondary namespaces; $3 multiple edits.",
"diff"			=> "差分" /* "diff" */,
"hist"			=> "履歴" /* "hist" */,
"hide"			=> "省略" /* "hide" */,
"show"			=> "表示" /* "show" */,
"tableform"		=> "表" /* "table" */,
"listform"		=> "リスト" /* "list" */,
"nchanges"		=> "$1件の変更" /* "$1 changes" */,

# Upload
#
"upload"		=> "アップロード Upload",
"uploadbtn"		=> "ファイルをアップロードする Upload file",
"uploadlink"	=> "イメージのアップロード Upload images",
"reupload"		=> "再アップロード Re-upload",
"reuploaddesc"	=> "アップロードのフォームへ戻る Return to the upload form.",
"uploadnologin" => "ログインしていません、 Not logged in",
"uploadnologintext"	=> "ユーザーオプションを変更するためには、
<a href=\"" .
  wfLocalUrl( "特別:Userlogin" ) . "\">ログイン</a>している必要があります。

You must be <a href=\"" .
  wfLocalUrl( "Special:Userlogin" ) . "\">logged in</a>
to upload files.",
"uploadfile"	=> "ファイルのアップロード  Upload file",
"uploaderror"	=> "アップロード エラー  Upload error",
"uploadtext"	=> "<strong>ご注意！</strong> 
ここにファイルをアップロードする前に、ウィキペディアの<a href=\"" .
wfLocalUrlE( "{$wgMetaNamespace}:画像利用の方針" ) . "\">画像利用の方針</a>を
よく読んで、方針に反することのないようにして下さい。.
<p>
これまでにアップロードされたイメージの一覧や検索には、
<a href=\"" . wfLocalUrlE( "特別:Imagelist" ) .
"\">画像リスト</a>が便利です。
アップロードと削除の記録は<a href=\"" .
wfLocalUrlE( "{$wgMetaNamespace}:Upload_log" ) . "\">にあります。</a>.
<p>記事に必要な画像を新しくアップロードする場合には、以下のフォームを利用して下さい。

ほとんどのブラウザーでは、\"Browse\"というボタンが表示されます。そのボタンを押すと、
あなたの使用しているコンピューター（のオペレーティング・システム）でファイルを開く
際のの標準的な手続きが始まります。ファイルを選択して、Browseというボタンの横にある
空欄にファイル名が入力された状態にして下さい。
また、あなたがそのファイルをアップロードすることが著作権を侵害に該当しないことを
あなたが表明する必要があります。そのために、チェック欄にチェックを入れて下さい。
ファイルをアップロードするボタンを押すことで、アップロード手続きは完了します。
もしもあなたのインターネット接続が低速のものであれば、アップロードには多少時間が
かかります。

望ましいフォーマットは、写真などのイメージの場合はJPEG、手書きのものやアイコン
などはPNG、サウンドにはOGGです。

混乱を避けるために説明的な名前をつけて下さい。

画像を記事に組み入れるためには、次のようなフォーマットでリンクを張ります。
<b>[[画像:file.jpg]]</b>  <b>[[画像:file.png|説明文]]</b>
また、サウンドには <b>[[media:file.ogg]]</b> を用います。
<p>
ウィキペディアの他のページと同じく、あなたがアップロードしたファイルも、より
よい百科事典作成のために他のユーザーによって編集、削除されることがあります。
また、アップロード機能を乱用した利用者は、アップロード機能の使用を禁じされることも
ありますのでご承知下さい。",

/* <strong>STOP!</strong> Before you upload here,
make sure to read and follow Wikipedia's <a href=\"" .
wfLocalUrlE( "{$wgMetaNamespace}:Image_use_policy" ) . "\">image use policy</a>.
<p>To view or search previously uploaded images,
go to the <a href=\"" . wfLocalUrlE( "Special:Imagelist" ) .
"\">list of uploaded images</a>.
Uploads and deletions are logged on the <a href=\"" .
wfLocalUrlE( "{$wgMetaNamespace}:Upload_log" ) . "\">upload log</a>.
<p>Use the form below to upload new image files for use in
illustrating your articles.
On most browsers, you will see a \"Browse...\" button, which will
bring up your operating system's standard file open dialog.
Choosing a file will fill the name of that file into the text
field next to the button.
You must also check the box affirming that you are not
violating any copyrights by uploading the file.
Press the \"Upload\" button to finish the upload.
This may take some time if you have a slow internet connection.
<p>The preferred formats are JPEG for photographic images, PNG

for drawings and other iconic images, and OGG for sounds.
Please name your files descriptively to avoid confusion.
To include the image in an article, use a link in the form
<b>[[image:file.jpg]]</b> or <b>[[image:file.png|alt text]]</b>
or <b>[[media:file.ogg]]</b> for sounds.
<p>Please note that as with Wikipedia pages, others may edit or
delete your uploads if they think it serves the encyclopedia, and
you may be blocked from uploading if you abuse the system." */
"uploadlog"		=> "upload log",
"uploadlogpage" => "Upload_log",
"uploadlogpagetext" => "以下は最近のファイルのアップロードのログです。
記録は全てサーバーの時間であるUTCに基づくものです。
<ul>
</ul>
"
/* Below is a list of the most recent file uploads.
All times shown are server time (UTC). */,

"filename"		=> "ファイル名",
"filedesc"		=> "ファイルの概要",
"affirmation"	=> "このファイルの著作権者は$1のライセンスに基づく
使用を許可したことをここに表明します。

I affirm that the copyright holder of this file
agrees to license it under the terms of the $1.",
"copyrightpage" => "{$wgMetaNamespace}:Copyrights",

"copyrightpagename" => "ウィキペディアの著作権",
"uploadedfiles"	=> "アップロードされたファイル",
"noaffirmation" => "あなたのアップロードが著作権の侵害にあたらない旨を
表明して下さい。"
/* "You must affirm that your upload does not violate
any copyrights." */,
"ignorewarning"	=> "警告を無視し、保存してしまう" /* "Ignore warning and save file anyway." */,
"minlength"		=> "ファイル名は3文字以上である必要があります。" /* "Image names must be at least three letters." */,
"badfilename"	=> "ファイル名は\"$1\"へ変更されました。" /* "Image name has been changed to \"$1\"." */,
"badfiletype"	=> "\".$1\" は推奨されているファイルフォーマットではありません。" /* 
"\".$1\" is not a recommended image file format." */,
"largefile"		=> "ファイルサイズは100キロバイト以下に抑えることが推奨されています。" /* It is recommended that images not exceed 100k in size." */,
"successfulupload" => "アップロード成功" /* "Successful upload" */,
"fileuploaded"	=> "ファイル\"$1\は無事にアップロードされました。
以下のリンク($2)をクリックし、ファイルについての情報－出典、製作者や時期、
その他知っている情報を書き込んで下さい。

" /* " "File \"$1\" uploaded successfully. 
Please follow this link: ($2) to the description page and fill
in information about the file, such as where it came from, when it was
created and by whom, and anything else you may know about it." */,

"uploadwarning" => "アップロード 警告" /* "Upload warning" */,
"savefile"		=> "ファイルを保存" /* "Save file" */,
"uploadedimage" => "\"$1\"をアップロードしました。" /* "uploaded \"$1\"" */,

# Image list
#
"imagelist"		=> "画像リスト",
"imagelisttext"	        => "$1枚の画像を$2に表示しています",
"getimagelist"	        => "画像リストを取得",
"ilshowmatch"           => "マッチする名前の画像を全て表示",
"ilsubmit"		=> "検索",
"showlast"		=> "$2に$1枚の画像を表示",
"all"			=> "全て",
"byname"		=> "名前順",
"bydate"		=> "日付順",
"bysize"		=> "サイズ順",
"imgdelete"		=> "削除",
"imgdesc"		=> "詳細",
"imglegend"		=> "凡例: (詳細)=画像の詳細を表示/編集",
"imghistory"	        => "画像の履歴",
"revertimg"		=> "差戻",
"deleteimg"		=> "削除",
"imghistlegend"         => "凡例: (最新)=最新版の画像, (削除)=この版の画像を削除, (差戻)=この版の画像に差し戻す<br><b>アップロードされた画像を見るには日付をクリックします。</b>",
"imagelinks"	        => "リンク",
"linkstoimage"	        => "この画像にリンクしているページの一覧:",
"nolinkstoimage"        => "この画像にリンクしているページはありません。",

# Statistics
#
"statistics"	        => "アクセス統計",
"sitestats"		=> "サイト全体の統計",
"userstats"		=> "ユーザー登録統計",
"sitestatstext"         => "<p>データベース内には <b>$1</b> ページのデータがあります。この数字には「会話ページ」や「Wikipedia関連のページ」、「書きかけのページ」、「リダイレクト」など、記事とはみなせないページが含まれています。これらを除いた、記事とみなされるページ数は約 <b>$2</b> ページになります。</p><p>ページの総閲覧回数は <b>$3</b> 回です。また、ソフトウェアの更新(2002/06/20)以来、<b>$4</b> 回の編集が行われました。平均すると、１ページあたり <b>$5</b> 回の編集が行われ、１編集あたり <b>$6</b> 回閲覧されています。</p>",
"userstatstext"         => "登録済みの利用者は <b>$1</b> 人で、内 <b>$2</b> 人が管理者権限を持っています。($3を参照)",

# Miscellaneous special pages
#
"orphans"		=> "孤立しているページ",
"lonelypages"	        => "孤立しているページ",
"unusedimages"	        => "使われていない画像",
"popularpages"	        => "人気のページ",
"nviews"		=> "$1 回表示",
"wantedpages"	        => "投稿が望まれているページ",
"nlinks"		=> "$1 個のリンク",
"allpages"		=> "全ページ",
"randompage"	        => "おまかせ表示",
"shortpages"	        => "短いページ",
"longpages"		=> "長いページ",
"listusers"		=> "登録ユーザー一覧",
"specialpages"	        => "特別ページ",
"spheading"		=> "特別ページ",
"sysopspheading"        => "シスオペ用特別ページ (pages for sysop)",
"developerspheading"    => "開発者用特別ページ (pages for developper)",
"protectpage"	        => "Protect page",
"recentchangeslinked"   => "リンクを見張る",
"rclsub"		=> "(to pages linked from \"$1\")",
"debug"			=> "デバッグ (debug)",
"newpages"		=> "新しいページ",
"movethispage"	        => "このページを移動する",
"unusedimagestext" => "<p>ご注意:他言語版のウィキペディアも含め、他のウェブサイトがURLを直接用いて画像にリンクしている場合もあります。以下の画像一覧には、そのような形で利用されている画像が含まれている可能性があります。",
"booksources"	=> "文献資料",
"booksourcetext" => "以下のリストは、新本、古本などを販売している外部サイトへのリンクです。
あなたがお探しの本について、更に詳しい情報が提供されている場合もあります｡
ウィキペディアはこれらの業務とは提携関係は持っていません。また、このリストはリストされたサイトへのウィキペディアの支持を表すものでもありません。",

# Email this user
#
"mailnologin"	=> "送信先のアドレスがありません。" /* No send address"*/,
"mailnologintext" => "ログインしていません。メールを送信するためには、
あなたの電子メールアドレスを<a href=\"" .
  wfLocalUrl( "Special:ユーザーオプション" ) . "\">ユーザーオプション</a>
で指定し、
<a href=\"" .
  wfLocalUrl( "特別:Userlogin" ) . "\">ログイン</a>している必要があります。"
/* You must be <a href=\"" .
  wfLocalUrl( "Special:Userlogin" ) . "\">logged in</a>
and have a valid e-mail address in your <a href=\"" .
  wfLocalUrl( "Special:Preferences" ) . "\">preferences</a>
to send e-mail to other users." */,
"emailuser"		=> "このユーザーにメールを送る" /* "E-mail this user" */,
"emailpage"		=> "メール送信ページ" /* "E-mail user" */,
"emailpagetext"	=> "もしメールを送る先のユーザーが、有効なメールアドレスを
ユーザーオプションに登録してあれば、下のフォームを通じてメールを送ることができます。
あなたが登録したご自分のメールアドレスはFrom:の欄に自動的に組み込まれ、受け取った相手が
返事を出せるようになっています。"

/* "If this user has entered a valid e-mail address in
is user preferences, the form below will send a single message.
The e-mail address you entered in your user preferences will appear
as the \"From\" address of the mail, so the recipient will be able
to reply."*/,
"noemailtitle"	=> "送り先のメールアドレスがありません。" /* "No e-mail address" */,
"noemailtext"	=> "このユーザーは有効なメールアドレスを登録していないか、メールを受け取りたくないというオプションを選択しています。"
 /* "This user has not specified a valid e-mail address,
or has chosen not to receive e-mail from other users." */,
"emailfrom"		=> "あなたのアドレス" /* "From" */,
"emailto"		=> "あて先" /* "To" */,
"emailsubject"	=> "題名" /* "Subject" */,
"emailmessage"	=> "本文" /* "Message" */,
"emailsend"		=> "メール送信" /* "Send" */,
"emailsent"		=> "メールを送りました" /* "E-mail sent" */,
"emailsenttext" => "メールは無事送信されました。" /* "Your e-mail message has been sent." */,

# Watchlist ウォッチリスト
#
"watchlist"		=> "ウォッチリスト",
"watchlistsub"	=> "(ユーザー名 \"$1\")" /* (for user \"$1\") */,
"nowatchlist"	=> "あなたのウォッチリストは空です。" /* "You have no items on your watchlist." */,
"watchnologin"	=> "ログインしていません" /* "Not logged in" */,
"watchnologintext"	=> "ウォッチリストを変更するためには、
<a href=\"" .
  wfLocalUrl( "特別:Userlogin" ) . "\">ログイン</a>している必要があります。"
/* "You must be <a href=\"" .
  wfLocalUrl( "Special:Userlogin" ) . "\">logged in</a>
to modify your watchlist." */,
"addedwatch"	=> "ウォッチリストに加えました" /* "Added to watchlist" */,
"addedwatchtext" => "ページ\"$1\" をあなたの
<a href=\"" .
  wfLocalUrl( "特別:Watchlist" ) . "\">ウォッチリスト</a>
に追加しました。
このページと、付属のノートのページに変更があった際にはそれをウォッチリストで
知ることができます。また、
<a href=\"" . wfLocalUrl( "特別:Recentchanges" ) . "\">最近更新したページ</a> では
ウォッチリストに含まれているページは<b>ボールド体</b>で表示され、見つけやすく
なります。</p>

<p>もしもウォッチリストから特定のページを削除したい場合には、サイドバーにある
\"ウォッチリストから削除\" のリンクをクリックして下さい。",

/* The page \"$1\" has been added to your <a href=\"" .
  wfLocalUrl( "Special:Watchlist" ) . "\">watchlist</a>.
Future changes to this page and its associated Talk page will be listed there,
and the page will appear <b>bolded</b> in the <a href=\"" .
  wfLocalUrl( "Special:Recentchanges" ) . "\">list of recent changes</a> to
make it easier to pick out.</p> 

<p>If you want to remove the page from your watchlist later, click \"Stop watching\" in the sidebar." */

"removedwatch"	=> "ウォッチリストから削除しました" /* "Removed from watchlist" */,
"removedwatchtext" => "ページ\"$1\はウォッチリストから削除されました。" /* "The page \"$1\" has been removed from your watchlist." */,
"watchthispage"	=> "ウォッチリストに追加" /* "Watch this page" */,
"unwatchthispage" => "ウォッチリストから削除" /* "Stop watching" */,
"notanarticle"	=> "これは記事ではありません。" /* "Not an article" */,

# Delete/protect/revert  （ここは管理者用の部分なので当面英文を残しておきます。）
#
"deletepage"	=> "Delete page (ページ削除)",
"confirm"		=> "Confirm (確認)",
"confirmdelete" => "Confirm delete (削除確認)",
"deletesub"		=> "(Deleting \"$1\") (サブページ\"$1\"を削除)",
"confirmdeletetext" => "指定されたページまたはイメージは、その更新履歴と共に
データベースから永久に削除されようとしています。
あなたが削除を望んでおり、それがもたらす帰結を理解しており、かつあなたの
しようとしていることが[[{$wgMetaNamespace}:Policy|ウィキペディアの基本方針]]に即したものであることを確認して下さい。

You are about to permanently delete a page
or image along with all of its history from the database.
Please confirm that you intend to do this, that you understand the
consequences, and that you are doing this in accordance with
[[{$wgMetaNamespace}:Policy]].",
"confirmcheck"	=> "はい。上記の通りです。　Yes, I really want to delete this.",
"actioncomplete" => "削除を完了しました。　Action complete",
"deletedtext"	=> "\"$1\" は削除されました。　\"$1\" has been deleted.
最近の削除に関しては$2 を参照して下さい。
See $2 for a record of recent deletions.",
"deletedarticle" => "\"$1\"　を削除しました",
"dellogpage"	=> "削除記録　Deletion_log",
"dellogpagetext" => "以下に示すのは最近の削除記録です。時間はサーバーの時間（UTC）によって記録されています。

Below is a list of the most recent deletions.
All times shown are server time (UTC).
<ul>
</ul>
",
"deletionlog"	=> "削除記録　deletion log",
"reverted"		=> "以前のバージョンへの差し戻し。　Reverted to earlier revision",
"deletecomment"	=> "削除の理由　Reason for deletion",
"imagereverted" => "以前のバージョンへの差し戻しに成功しました。　Revert to earlier version was successful.",

# Contributions ユーザーの投稿記録
#
"contributions"	=> "ユーザーの投稿記録",
"contribsub"	=> "ユーザー名：$1",
"nocontribs"	=> "ユーザーの投稿記録は見つかりませんでした。",
"ucnote"		=> "以下に示すのが過去<b>$2</b>日間における、最大<b>$1</b>件の投稿・編集です。" /*Below are this user's last <b>$1</b> changes in the last <b>$2</b> days."*/,
"uclinks"		=> "$1 件の投稿・編集を見る。; $2日間分の投稿・編集を見る。

View the last $1 changes; view the last $2 days.",

# What links here このページにリンクしている他のページ
#
"whatlinkshere"	=> "ここにリンクしている他のページ" /* "what links here" */,
"notargettitle" => "対象となるページが存在しません" /* "No target" */,
"notargettext"	=> "対象となるページ又はユーザーが指定されていません" /* "You have not specified a target page or user
to perform this function on." */,
"linklistsub"	=> "リンクのリスト" /* "(List of links)" */,
"linkshere"		=> "以下のページが指定されたページにリンクしています。" /* "The following pages link to here:" */,
"nolinkshere"	=> "指定されたページにリンクしているページはありません。" /* "No pages link to here." */,
"isredirect"	=> "リダイレクトページ" /* "redirect page" */,

# Block/unblock IP （この部分は管理者用なので当面英文を残しておきます。）
#
"blockip"		=> "Block IP address",
"blockiptext"	=> "Use the form below to block write access
from a specific IP address.
This should be done only only to prevent valndalism, and in
accordance with [[{$wgMetaNamespace}:Policy|Wikipedia policy]].
Fill in a specific reason below (for example, citing particular
pages that were vandalized).",
"ipaddress"		=> "IP Address",
"ipbreason"		=> "Reason",
"ipbsubmit"		=> "Block this address",
"badipaddress"	=> "The IP address is badly formed.",
"noblockreason" => "You must supply a reason for the block.",
"blockipsuccesssub" => "Block succeeded",
"blockipsuccesstext" => "The IP address \"$1\" has been blocked.
<br>See [[Special:Ipblocklist|IP block list]] to review blocks.",
"unblockip"		=> "Unblock IP address",
"unblockiptext"	=> "Use the form below to restore write access
to a previously blocked IP address.",
"ipusubmit"		=> "Unblock this address",
"ipusuccess"	=> "IP address \"$1\" unblocked",
"ipblocklist"	=> "List of blocked IP addresses",
"blocklistline"	=> "$1, $2 blocked $3",
"blocklink"		=> "block",
"unblocklink"	=> "unblock",
"contribslink"	=> "contribs",

# Developer tools  （この部分は管理者用なので当面英文を残しておきます。）
#
"lockdb"		=> "Lock database",
"unlockdb"		=> "Unlock database",
"lockdbtext"	=> "Locking the database will suspend the ability of all
users to edit pages, change their preferences, edit their watchlists, and
other things requiring changes in the database.
Please confirm that this is what you intend to do, and that you will
unlock the database when your maintenance is done.",
"unlockdbtext"	=> "Unlocking the database will restore the ability of all
users to edit pages, change their preferences, edit their watchlists, and
other things requiring changes in the database.
Please confirm that this is what you intend to do.",
"lockconfirm"	=> "Yes, I really want to lock the database.",
"unlockconfirm"	=> "Yes, I really want to unlock the database.",
"lockbtn"		=> "Lock database",
"unlockbtn"		=> "Unlock database",
"locknoconfirm" => "You did not check the confirmation box.",
"lockdbsuccesssub" => "Database lock succeeded",
"unlockdbsuccesssub" => "Database lock removed",
"lockdbsuccesstext" => "The Wikipedia database has been locked.
<br>Remember to remove the lock after your maintenance is complete.",
"unlockdbsuccesstext" => "The Wikipedia database has been unlocked.",

# SQL query     （この部分は管理者用なので当面英文を残しておきます。）
#
"asksql"		=> "SQL query",
"asksqltext"	=> "Use the form below to make a direct query of the
Wikipedia database.
Use single quotes ('like this') to delimit string literals.
This can often add considerable load to the server, so please use
this function sparingly.",
"sqlquery"		=> "Enter query",
"querybtn"		=> "Submit query",
"selectonly"	=> "Queries other than \"SELECT\" are restricted to
Wikipedia developers.",
"querysuccessful" => "Query successful",

# Move page ページの移動
#
"movepage"		=> "ページの移動",
"movepagetext"	=> "以下のフォームを利用して、ページ名を変更し、
そのページに付随する履歴の情報を変更先のページへ移動することができます。
変更されるページは、変更後は変更先へのリダイレクトページになります。
更新前のページへと張られたリンクは変更されません。また、ページに付随する
ノートのページも移動されません。
<b>注意！</b>
これは人気のあるページにとって抜本的で予想外の変更になるかも知れません。
ページの移動に伴う諸帰結をよく理解してから移動に踏み切るようにして下さい。"

/* "Using the form below will rename a page, moving all
of its history to the new name.
The old title will become a redirect page to the new title.
Links to the old page title will not be changed, and the talk
page, if any, will not be moved.
<b>WARNING!</b>
This can be a drastic and unexpected change for a popular page;
please be sure you understand the consequences of this before
proceeding." */,
"movearticle"	=> "ページの移動",
"movenologin"	=> "ログインしていません",
"movenologintext" => "この機能を利用するためには、ユーザー登録をして、
<a href=\"" .
  wfLocalUrl( "特別:Userlogin" ) . "\">ログイン</a>している必要が
あります。",
"newtitle"		=> "新しいページへ" /* "To new title" */,
"movepagebtn"	=> "ページを移動" /* "Move page" */,
"pagemovedsub"	=> "無事移動しました。" /* "Move succeeded" */,
"pagemovedtext" => "ページ\"[[$1]]\" は \"[[$2]]\" に移動しました。" /* "Page \"[[$1]]\" moved to \"[[$2]]\"." */,
"articleexists" => "指定された移動先には既にページが存在するか、名前が不適切です。" /* "A page of that name already exists, or the
name you have chosen is not valid.
Please choose another name." */,
"movedto"		=> "移動先:" /* "moved to" */,
"movetalk"		=> "付随するノートのページが存在する場合にはそれも同時に移動させる" /* "Move \"talk\" page as well, if applicable." */,
"talkpagemoved" => "付随のノートのページも移動しました。" /* "The corresponding talk page was also moved." */,
"talkpagenotmoved" => "付随のノートのページは<b>移動されませんでした。</b>" /* "The corresponding talk page was <strong>not</strong> moved." */,

);

class LanguageJa extends LanguageUtf8 {

	function getNamespaces() {
		global $wgNamespaceNamesJa;
		return $wgNamespaceNamesJa;
	}

	function getNsText( $index ) {
		global $wgNamespaceNamesJa;
		return $wgNamespaceNamesJa[$index];
	}

	function getNsIndex( $text ) {
		global $wgNamespaceNamesJa;

		foreach ( $wgNamespaceNamesJa as $i => $n ) {
			if ( 0 == strcasecmp( $n, $text ) ) { return $i; }
		}
		return false;
	}

	function getQuickbarSettings() {
		global $wgQuickbarSettingsJa;
		return $wgQuickbarSettingsJa;
	}

	function getSkinNames() {
		global $wgSkinNamesJa;
		return $wgSkinNamesJa;
	}


	function getUserToggles() {
		global $wgUserTogglesJa;
		return $wgUserTogglesJa;
	}

	function getMonthName( $key )
	{
		global $wgMonthNamesJa;
		return $wgMonthNamesJa[$key-1];
	}

	function getMonthAbbreviation( $key )
	{
		global $wgMonthAbbreviationsJa;
		return $wgMonthAbbreviationsJa[$key-1];
	}

	function getWeekdayName( $key )
	{
		global $wgWeekdayNamesJa;
		return $wgWeekdayNamesJa[$key-1];
	}

	# Inherit default userAdjust()
	 
	function date( $ts, $adj = false )
	{
		if ( $adj ) { $ts = $this->userAdjust( $ts ); }

		$d = substr( $ts, 0, 4 ) . "年" .
		  $this->getMonthAbbreviation( substr( $ts, 4, 2 ) ) .
		  (0 + substr( $ts, 6, 2 )) . "日";
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
		return $this->time( $ts, $adj ) . " " . $this->date( $ts, $adj );
	}

	# Inherit default rfc1123()

	function getValidSpecialPages()
	{
		global $wgValidSpecialPagesJa;
		return $wgValidSpecialPagesJa;
	}

	function getSysopSpecialPages()
	{
		global $wgSysopSpecialPagesJa;
		return $wgSysopSpecialPagesJa;
	}

	function getDeveloperSpecialPages()
	{
		global $wgDeveloperSpecialPagesJa;
		return $wgDeveloperSpecialPagesJa;
	}

	function getMessage( $key )
	{
		global $wgAllMessagesJa;
        if(array_key_exists($key, $wgAllMessagesJa))
			return $wgAllMessagesJa[$key];
		else
			return Language::getMessage($key);
	}

	function stripForSearch( $string ) {
		# MySQL fulltext index doesn't grok utf-8, so we
		# need to fold cases and convert to hex
		global $wikiLowerChars;
		$s = $string;

		# Strip known punctuation ?
		#$s = preg_replace( '/\xe3\x80[\x80-\xbf]/', '', $s ); # U3000-303f

		# Space strings of like hiragana/katakana/kanji
		$hiragana = '(?:\xe3(?:\x81[\x80-\xbf]|\x82[\x80-\x9f]))'; # U3040-309f
		$katakana = '(?:\xe3(?:\x82[\xa0-\xbf]|\x83[\x80-\xbf]))'; # U30a0-30ff
		$kanji = '(?:\xe3[\x88-\xbf][\x80-\xbf]'
			. '|[\xe4-\xe8][\x80-\xbf]{2}'
			. '|\xe9[\x80-\xa5][\x80-\xbf]'
			. '|\xe9\xa6[\x80-\x99])';
			# U3200-9999 = \xe3\x88\x80-\xe9\xa6\x99
		$s = preg_replace( "/({$hiragana}+|{$katakana}+|{$kanji}+)/", ' $1 ', $s );

		# Double-width roman characters: ff00-ff5f ~= 0020-007f
		$s = preg_replace( '/\xef\xbc([\x80-\xbf])/e', 'chr((ord("$1") & 0x3f) + 0x20)', $s );
		$s = preg_replace( '/\xef\xbd([\x80-\x99])/e', 'chr((ord("$1") & 0x3f) + 0x60)', $s );

		return trim( preg_replace(
		  "/([\\xc0-\\xff][\\x80-\\xbf]*)/e",
		  "'U8' . bin2hex( strtr( \"\$1\", \$wikiLowerChars ) )",
		  $s ) );
		return $s;
	}

	# Italic is not appropriate for Japanese script
	# Unfortunately most browsers do not recognise this, and render <em> as italic
	function emphasize( $text )
	{
		return $text;
	}
}

?>
