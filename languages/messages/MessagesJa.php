<?php
/** Japanese (日本語)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Akaniji
 * @author Alexsh
 * @author Aotake
 * @author Aphaia
 * @author Broad-Sky
 * @author Chinneeb
 * @author Emk
 * @author Fievarsty
 * @author Fryed-peach
 * @author Hatukanezumi
 * @author Hisagi
 * @author Hosiryuhosi
 * @author Iwai.masaharu
 * @author JtFuruhata
 * @author Kahusi
 * @author Kkkdc
 * @author Klutzy
 * @author Koba-chan
 * @author Lovekhmer
 * @author Marine-Blue
 * @author Mizusumashi
 * @author Muttley
 * @author Mzm5zbC3
 * @author Ohgi
 * @author Suisui
 * @author Vigorous action
 * @author W.CC
 * @author Web comic
 * @author Whym
 * @author לערי ריינהארט
 * @author 欅
 * @author 青子守歌
 */

$datePreferences = array(
	'default',
	'ISO 8601',
);

$defaultDateFormat = 'ja';

$dateFormats = array(
	'ja time' => 'H:i',
	'ja date' => 'Y年n月j日 (D)',
	'ja both' => 'Y年n月j日 (D) H:i',
);

$namespaceNames = array(
	NS_MEDIA            => 'メディア',
	NS_SPECIAL          => '特別',
	NS_TALK             => 'ノート',
	NS_USER             => '利用者',
	NS_USER_TALK        => '利用者‐会話',
	NS_PROJECT_TALK     => '$1‐ノート',
	NS_FILE             => 'ファイル',
	NS_FILE_TALK        => 'ファイル‐ノート',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'MediaWiki‐ノート',
	NS_TEMPLATE         => 'Template',
	NS_TEMPLATE_TALK    => 'Template‐ノート',
	NS_HELP             => 'Help',
	NS_HELP_TALK        => 'Help‐ノート',
	NS_CATEGORY         => 'Category',
	NS_CATEGORY_TALK    => 'Category‐ノート',
);

$namespaceAliases = array(
	'画像' => NS_FILE,
	'画像‐ノート' => NS_FILE_TALK,
);

$specialPageAliases = array(
	'DoubleRedirects'           => array( '二重リダイレクト' ),
	'BrokenRedirects'           => array( '迷子のリダイレクト', '壊れたリダイレクト' ),
	'Disambiguations'           => array( '曖昧さ回避のページ', '曖昧さ回避' ),
	'Userlogin'                 => array( 'ログイン' ),
	'Userlogout'                => array( 'ログアウト' ),
	'CreateAccount'             => array( 'アカウント作成' ),
	'Preferences'               => array( '個人設定', 'オプション' ),
	'Watchlist'                 => array( 'ウォッチリスト' ),
	'Recentchanges'             => array( '最近更新したページ' ),
	'Upload'                    => array( 'アップロード' ),
	'Listfiles'                 => array( 'ファイルリスト', 'ファイル一覧' ),
	'Newimages'                 => array( '新着ファイル', '新しいファイルの一覧', '新着画像展示室' ),
	'Listusers'                 => array( '登録利用者の一覧' ),
	'Listgrouprights'           => array( '利用者グループの権限一覧' ),
	'Statistics'                => array( '統計' ),
	'Randompage'                => array( 'おまかせ表示' ),
	'Lonelypages'               => array( '孤立しているページ' ),
	'Uncategorizedpages'        => array( 'カテゴリ未導入のページ' ),
	'Uncategorizedcategories'   => array( 'カテゴリ未導入のカテゴリ' ),
	'Uncategorizedimages'       => array( 'カテゴリ未導入のファイル' ),
	'Uncategorizedtemplates'    => array( 'カテゴリ未導入のテンプレート' ),
	'Unusedcategories'          => array( '使われていないカテゴリ', '未使用カテゴリ' ),
	'Unusedimages'              => array( '使われていないファイル', '未使用ファイル', '未使用画像' ),
	'Wantedpages'               => array( '存在しないページへのリンク', '赤リンク' ),
	'Wantedcategories'          => array( '存在しないカテゴリへのリンク', '赤リンクカテゴリ' ),
	'Wantedfiles'               => array( 'ファイルページが存在しないファイル', '赤リンクファイル' ),
	'Wantedtemplates'           => array( '存在しないテンプレートへのリンク', '赤リンクテンプレート' ),
	'Mostlinked'                => array( '被リンクの多いページ' ),
	'Mostlinkedcategories'      => array( '被リンクの多いカテゴリ' ),
	'Mostlinkedtemplates'       => array( '被リンクの多いテンプレート', '使用箇所の多いテンプレート' ),
	'Mostimages'                => array( '被リンクの多いファイル', '使用箇所の多いファイル' ),
	'Mostcategories'            => array( 'カテゴリの多いページ', 'カテゴリの多い項目' ),
	'Mostrevisions'             => array( '編集履歴の多いページ', '版の多い項目', '版の多いページ' ),
	'Fewestrevisions'           => array( '編集履歴の少ないページ', '版の少ない項目', '版の少ないページ' ),
	'Shortpages'                => array( '短いページ' ),
	'Longpages'                 => array( '長いページ' ),
	'Newpages'                  => array( '新しいページ', '新規項目' ),
	'Ancientpages'              => array( '更新されていないページ' ),
	'Deadendpages'              => array( '有効なページへのリンクがないページ', '行き止まりページ' ),
	'Protectedpages'            => array( '保護されているページ' ),
	'Protectedtitles'           => array( '作成保護されているページ名' ),
	'Allpages'                  => array( 'ページ一覧', '全ページ' ),
	'Prefixindex'               => array( '前方一致ページ一覧', '始点指定ページ一覧' ),
	'Ipblocklist'               => array( 'ブロック一覧', 'ブロックの一覧' ),
	'Specialpages'              => array( '特別ページ一覧' ),
	'Contributions'             => array( '投稿記録' ),
	'Emailuser'                 => array( 'ウィキメール' ),
	'Confirmemail'              => array( 'メールアドレスの確認' ),
	'Whatlinkshere'             => array( 'リンク元' ),
	'Recentchangeslinked'       => array( '関連ページの更新状況', 'リンク先の更新状況' ),
	'Movepage'                  => array( '移動' ),
	'Blockme'                   => array( '自己ブロック' ),
	'Booksources'               => array( '文献資料' ),
	'Categories'                => array( 'カテゴリ', 'カテゴリ一覧' ),
	'Export'                    => array( 'データ書き出し' ),
	'Version'                   => array( 'バージョン情報' ),
	'Allmessages'               => array( 'メッセージ一覧', 'システムメッセージの一覧', '表示メッセージの一覧' ),
	'Log'                       => array( 'ログ', '記録' ),
	'Blockip'                   => array( '投稿ブロック' ),
	'Undelete'                  => array( '復帰' ),
	'Import'                    => array( 'インポート' ),
	'Lockdb'                    => array( 'データベースロック' ),
	'Unlockdb'                  => array( 'データベースロック解除', 'データベース解除' ),
	'Userrights'                => array( '利用者権限' ),
	'MIMEsearch'                => array( 'MIME検索', 'MIMEタイプ検索' ),
	'FileDuplicateSearch'       => array( '重複ファイル検索' ),
	'Unwatchedpages'            => array( 'ウォッチされていないページ' ),
	'Listredirects'             => array( 'リダイレクトの一覧' ),
	'Revisiondelete'            => array( '特定版削除' ),
	'Unusedtemplates'           => array( '使われていないテンプレート', '未使用テンプレート' ),
	'Randomredirect'            => array( 'おまかせリダイレクト' ),
	'Mypage'                    => array( '利用者ページ', 'マイページ', 'マイ・ページ' ),
	'Mytalk'                    => array( '会話ページ', 'マイトーク', 'マイ・トーク' ),
	'Mycontributions'           => array( '自分の投稿記録' ),
	'Listadmins'                => array( '管理者一覧' ),
	'Listbots'                  => array( 'ボット一覧', 'Bot一覧' ),
	'Popularpages'              => array( '人気ページ' ),
	'Search'                    => array( '検索' ),
	'Resetpass'                 => array( 'パスワードの変更', 'パスワード変更', 'パスワード再発行', 'パスワードの再発行' ),
	'Withoutinterwiki'          => array( '言語間リンクを持たないページ', '言語間リンクのないページ' ),
	'MergeHistory'              => array( '履歴統合' ),
	'Filepath'                  => array( 'パスの取得' ),
	'Invalidateemail'           => array( 'ウィキメール無効' ),
	'Blankpage'                 => array( '白紙ページ' ),
	'LinkSearch'                => array( '外部リンク検索' ),
	'DeletedContributions'      => array( '削除された投稿記録', '削除された投稿履歴', '削除歴' ),
	'Tags'                      => array( 'タグ一覧' ),
);

$magicWords = array(
	'redirect'              => array( '0', '#転送', '#リダイレクト', '＃転送', '＃リダイレクト', '#REDIRECT' ),
	'notoc'                 => array( '0', '__目次非表示__', '＿＿目次非表示＿＿', '__NOTOC__' ),
	'nogallery'             => array( '0', '__ギャラリー非表示__', '＿＿ギャラリー非表示＿＿', '__NOGALLERY__' ),
	'forcetoc'              => array( '0', '__目次強制__', '＿＿目次強制＿＿', '__FORCETOC__' ),
	'toc'                   => array( '0', '__目次__', '＿＿目次＿＿', '__TOC__' ),
	'noeditsection'         => array( '0', '__セクション編集非表示__', '＿＿セクション編集非表示＿＿', '__NOEDITSECTION__' ),
	'noheader'              => array( '0', '__見出し非表示__', '＿＿見出し非表示＿＿', '__NOHEADER__' ),
	'currentmonth'          => array( '1', '協定月', 'CURRENTMONTH' ),
	'currentmonthname'      => array( '1', '協定月名', 'CURRENTMONTHNAME' ),
	'currentmonthabbrev'    => array( '1', '省略協定月', '協定月省略', '協定月省略形', 'CURRENTMONTHABBREV' ),
	'currentday'            => array( '1', '協定日', 'CURRENTDAY' ),
	'currentday2'           => array( '1', '協定日2', '協定日２', 'CURRENTDAY2' ),
	'currentdayname'        => array( '1', '協定曜日', 'CURRENTDAYNAME' ),
	'currentyear'           => array( '1', '協定年', 'CURRENTYEAR' ),
	'currenttime'           => array( '1', '協定時間', '協定時刻', 'CURRENTTIME' ),
	'currenthour'           => array( '1', '協定時', 'CURRENTHOUR' ),
	'localmonth'            => array( '1', '現地月', 'LOCALMONTH' ),
	'localmonthname'        => array( '1', '現地月名', 'LOCALMONTHNAME' ),
	'localmonthabbrev'      => array( '1', '省略現地月', 'LOCALMONTHABBREV' ),
	'localday'              => array( '1', '現地日', 'ローカルデイ', 'LOCALDAY' ),
	'localday2'             => array( '1', '現地日2', '現地日２', 'LOCALDAY2' ),
	'localdayname'          => array( '1', '現地曜日', 'ローカルデイネーム', 'LOCALDAYNAME' ),
	'localyear'             => array( '1', '現地年', 'ローカルイヤー', 'LOCALYEAR' ),
	'localtime'             => array( '1', '現地時間', 'ローカルタイム', 'LOCALTIME' ),
	'localhour'             => array( '1', '現地時', 'LOCALHOUR' ),
	'numberofpages'         => array( '1', 'ページ数', 'NUMBEROFPAGES' ),
	'numberofarticles'      => array( '1', '記事数', 'NUMBEROFARTICLES' ),
	'numberoffiles'         => array( '1', 'ファイル数', 'NUMBEROFFILES' ),
	'numberofusers'         => array( '1', '利用者数', 'NUMBEROFUSERS' ),
	'numberofactiveusers'   => array( '1', '有効な利用者', '有効な利用者数', '有効利用者数', 'NUMBEROFACTIVEUSERS' ),
	'numberofedits'         => array( '1', '編集回数', 'NUMBEROFEDITS' ),
	'numberofviews'         => array( '1', '閲覧回数', 'NUMBEROFVIEWS' ),
	'pagename'              => array( '1', 'ページ名', 'PAGENAME' ),
	'pagenamee'             => array( '1', 'ページ名E', 'ページ名エンコード', 'PAGENAMEE' ),
	'namespace'             => array( '1', '名前空間', 'NAMESPACE' ),
	'namespacee'            => array( '1', '名前空間E', '名前空間エンコード', 'NAMESPACEE' ),
	'talkspace'             => array( '1', '会話空間', 'トークスペース', 'TALKSPACE' ),
	'talkspacee'            => array( '1', '会話空間E', 'トークスペースE', 'TALKSPACEE' ),
	'subjectspace'          => array( '1', '標準空間', 'SUBJECTSPACE', 'ARTICLESPACE' ),
	'subjectspacee'         => array( '1', '標準空間E', 'SUBJECTSPACEE', 'ARTICLESPACEE' ),
	'fullpagename'          => array( '1', 'フルページ名', '完全なページ名', 'FULLPAGENAME' ),
	'fullpagenamee'         => array( '1', 'フルページ名E', '完全なページ名E', '完全なページ名エンコード', 'FULLPAGENAMEE' ),
	'subpagename'           => array( '1', 'サブページ名', 'SUBPAGENAME' ),
	'subpagenamee'          => array( '1', 'サブページ名E', 'サブページ名エンコード', 'SUBPAGENAMEE' ),
	'basepagename'          => array( '1', '親ページ名', 'BASEPAGENAME' ),
	'basepagenamee'         => array( '1', '親ページ名E', 'BASEPAGENAMEE' ),
	'talkpagename'          => array( '1', 'トークページ名', 'TALKPAGENAME' ),
	'talkpagenamee'         => array( '1', 'トークページ名E', 'トークページ名エンコード', 'TALKPAGENAMEE' ),
	'subjectpagename'       => array( '1', '主ページ名', '記事ページ名', 'SUBJECTPAGENAME', 'ARTICLEPAGENAME' ),
	'subjectpagenamee'      => array( '1', '主ページ名E', '記事ページ名E', 'SUBJECTPAGENAMEE', 'ARTICLEPAGENAMEE' ),
	'img_thumbnail'         => array( '1', 'サムネイル', 'thumbnail', 'thumb' ),
	'img_manualthumb'       => array( '1', 'サムネイル=$1', 'thumbnail=$1', 'thumb=$1' ),
	'img_right'             => array( '1', '右', 'right' ),
	'img_left'              => array( '1', '左', 'left' ),
	'img_none'              => array( '1', 'なし', 'none' ),
	'img_width'             => array( '1', '$1ピクセル', '$1px' ),
	'img_center'            => array( '1', '中央', 'center', 'centre' ),
	'img_framed'            => array( '1', 'フレーム', 'framed', 'enframed', 'frame' ),
	'img_frameless'         => array( '1', 'フレームなし', 'frameless' ),
	'img_page'              => array( '1', 'ページ=$1', 'ページ $1', 'page=$1', 'page $1' ),
	'img_border'            => array( '1', 'ボーダー', 'border' ),
	'img_baseline'          => array( '1', 'ベースライン', 'baseline' ),
	'img_sub'               => array( '1', '下付き', 'sub' ),
	'img_super'             => array( '1', '上付き', 'super', 'sup' ),
	'img_link'              => array( '1', 'リンク=$1', 'link=$1' ),
	'img_alt'               => array( '1', '代替文=$1', 'alt=$1' ),
	'int'                   => array( '0', 'インターフェース:', 'インタ:', 'INT:' ),
	'sitename'              => array( '1', 'サイト名', 'サイトネーム', 'SITENAME' ),
	'ns'                    => array( '0', '名前空間:', '名空:', 'NS:' ),
	'localurl'              => array( '0', 'ローカルURL:', 'LOCALURL:' ),
	'localurle'             => array( '0', 'ローカルURLE:', 'ローカルURLエンコード:', 'LOCALURLE:' ),
	'server'                => array( '0', 'サーバー', 'サーバ', 'SERVER' ),
	'servername'            => array( '0', 'サーバー名', 'サーバーネーム', 'サーバ名', 'サーバネーム', 'SERVERNAME' ),
	'scriptpath'            => array( '0', 'スクリプトパス', 'SCRIPTPATH' ),
	'gender'                => array( '0', '性別:', '性別：', 'GENDER:' ),
	'notitleconvert'        => array( '0', '__タイトルコンバート拒否__', '＿＿タイトルコンバート拒否＿＿', '__タイトル非表示__', '__NOTITLECONVERT__', '__NOTC__' ),
	'nocontentconvert'      => array( '0', '__内容変換抑制__', '＿＿内容変換抑制＿＿', '__NOCONTENTCONVERT__', '__NOCC__' ),
	'currentweek'           => array( '1', '協定週', 'CURRENTWEEK' ),
	'localweek'             => array( '1', '現地週', 'ローカルウィーク', 'LOCALWEEK' ),
	'revisionid'            => array( '1', 'リビジョンID', '差分ID', 'REVISIONID' ),
	'revisionday'           => array( '1', 'リビジョン日', '差分日', 'REVISIONDAY' ),
	'revisionday2'          => array( '1', 'リビジョン日2', '差分日2', 'REVISIONDAY2' ),
	'revisionmonth'         => array( '1', 'リビジョン月', '差分月', 'REVISIONMONTH' ),
	'revisionyear'          => array( '1', 'リビジョン年', '差分年', 'REVISIONYEAR' ),
	'revisiontimestamp'     => array( '1', 'リビジョンタイムスタンプ', 'REVISIONTIMESTAMP' ),
	'plural'                => array( '0', '複数:', 'PLURAL:' ),
	'fullurl'               => array( '0', '完全なURL:', 'フルURL:', 'FULLURL:' ),
	'fullurle'              => array( '0', '完全なURLE:', 'フルURLE:', 'FULLURLE:' ),
	'newsectionlink'        => array( '1', '__新しいセクションリンク__', '__新セクションリンク__', '__NEWSECTIONLINK__' ),
	'currentversion'        => array( '1', 'ウィキバージョン', 'MediaWikiバージョン', 'メディアウィキバージョン', 'CURRENTVERSION' ),
	'urlencode'             => array( '0', 'URLエンコード:', 'URLENCODE:' ),
	'currenttimestamp'      => array( '1', '協定タイムスタンプ', 'CURRENTTIMESTAMP' ),
	'localtimestamp'        => array( '1', '現地タイムスタンプ', 'ローカルタイムスタンプ', 'LOCALTIMESTAMP' ),
	'language'              => array( '0', '#言語:', '#LANGUAGE:' ),
	'contentlanguage'       => array( '1', '内容言語', '記事言語', 'プロジェクト言語', 'CONTENTLANGUAGE', 'CONTENTLANG' ),
	'pagesinnamespace'      => array( '1', '名前空間内ページ数', 'PAGESINNAMESPACE:', 'PAGESINNS:' ),
	'numberofadmins'        => array( '1', '管理者数', 'NUMBEROFADMINS' ),
	'special'               => array( '0', '特別', 'special' ),
	'defaultsort'           => array( '1', 'デフォルトソート:', 'デフォルトソートキー:', 'デフォルトカテゴリソート:', 'DEFAULTSORT:', 'DEFAULTSORTKEY:', 'DEFAULTCATEGORYSORT:' ),
	'filepath'              => array( '0', 'ファイルパス:', 'FILEPATH:' ),
	'tag'                   => array( '0', 'タグ', 'tag' ),
	'hiddencat'             => array( '1', '__カテゴリ非表示__', '__カテ非表示__', '__非表示カテ__', '__HIDDENCAT__' ),
	'pagesincategory'       => array( '1', 'カテゴリ内ページ数', 'PAGESINCATEGORY', 'PAGESINCAT' ),
	'pagesize'              => array( '1', 'ページサイズ', 'PAGESIZE' ),
	'index'                 => array( '1', '__インデックス__', '＿＿インデックス＿＿', '__INDEX__' ),
	'noindex'               => array( '1', '__インデックス拒否__', '＿＿インデックス拒否＿＿', '__NOINDEX__' ),
	'numberingroup'         => array( '1', 'グループ人数', 'グループ所属人数', 'NUMBERINGROUP', 'NUMINGROUP' ),
	'staticredirect'        => array( '1', '__二重転送解消無効__', '＿＿二重転送解消無効＿＿', '__二重転送修正無効__', '＿＿二重転送修正無効＿＿', '__STATICREDIRECT__' ),
	'protectionlevel'       => array( '1', '保護レベル', 'PROTECTIONLEVEL' ),
);

$messages = array(
# User preference toggles
'tog-underline'               => 'リンクの下線：',
'tog-highlightbroken'         => '存在しないページへのリンクを<a href="" class="new">リンク</a>のように表示する（チェックなしの場合：リンク<a href="" class="internal">？</a>）',
'tog-justify'                 => '段落を均等割り付けする',
'tog-hideminor'               => '最近の更新に細部の編集を表示しない',
'tog-hidepatrolled'           => '最近の更新に巡回済みの編集を表示しない',
'tog-newpageshidepatrolled'   => '新しいページの一覧に巡回済みのページを表示しない',
'tog-extendwatchlist'         => 'ウォッチリストを拡張し、最新のものだけではなくすべての変更を表示する',
'tog-usenewrc'                => '最近の更新ページを拡張する（JavaScriptが必要）',
'tog-numberheadings'          => '自動的に見出しに番号を振る',
'tog-showtoolbar'             => '編集用のツールバーを表示する（JavaScriptが必要）',
'tog-editondblclick'          => 'ダブルクリックで編集する（JavaScriptが必要）',
'tog-editsection'             => '[編集]リンクから節を編集できるようにする',
'tog-editsectiononrightclick' => '節見出しの右クリックで節編集を行えるようにする（JavaScriptが必要）',
'tog-showtoc'                 => '目次を表示する（4つ以上の見出しがあるページ）',
'tog-rememberpassword'        => 'このコンピューターにログイン情報を保存する',
'tog-editwidth'               => '編集ボックスをウィンドウの幅いっぱいに表示する',
'tog-watchcreations'          => '自分が作成したページをウォッチリストに追加する',
'tog-watchdefault'            => '自分が編集したページをウォッチリストに追加する',
'tog-watchmoves'              => '自分が移動したページをウォッチリストに追加する',
'tog-watchdeletion'           => '自分が削除したページをウォッチリストに追加する',
'tog-minordefault'            => '細部の編集を既定でチェックする',
'tog-previewontop'            => 'プレビューを編集ボックスの前に配置する',
'tog-previewonfirst'          => '編集開始時にもプレビューを表示する',
'tog-nocache'                 => 'ブラウザによるページのキャッシュを無効にする',
'tog-enotifwatchlistpages'    => 'ウォッチリストにあるページが更新されたときにメールを受け取る',
'tog-enotifusertalkpages'     => '自分のトークページが更新されたときにメールを受け取る',
'tog-enotifminoredits'        => '細部の編集でもメールを受け取る',
'tog-enotifrevealaddr'        => '通知メールで自分のメールアドレスを明示する',
'tog-shownumberswatching'     => 'ページをウォッチしている利用者数を表示する',
'tog-fancysig'                => '署名をウィキ文として扱う（自動でリンクしない）',
'tog-externaleditor'          => '既定で編集に外部アプリケーションを使う（上級者向け、コンピューターに特殊な設定が必要）',
'tog-externaldiff'            => '差分表示に外部アプリケーションを使う（上級者向け、コンピューターに特殊な設定が必要）',
'tog-showjumplinks'           => '利用しやすさ向上のための「{{int:jumpto}}」リンクを有効にする',
'tog-uselivepreview'          => 'ライブプレビューを使用する（JavaScriptが必要）（試験中の機能）',
'tog-forceeditsummary'        => '要約欄が空欄の場合に警告する',
'tog-watchlisthideown'        => 'ウォッチリストに自分の編集を表示しない',
'tog-watchlisthidebots'       => 'ウォッチリストにボットによる編集を表示しない',
'tog-watchlisthideminor'      => 'ウォッチリストに細部の編集を表示しない',
'tog-watchlisthideliu'        => 'ウォッチリストにログイン利用者の編集を表示しない',
'tog-watchlisthideanons'      => 'ウォッチリストに匿名利用者の編集を表示しない',
'tog-watchlisthidepatrolled'  => 'ウォッチリストに巡回済みの編集を表示しない',
'tog-nolangconversion'        => '変種言語の変換を無効にする',
'tog-ccmeonemails'            => '他の利用者に送信したメールの控えを自分にも送る',
'tog-diffonly'                => '差分表示の下にページの内容を表示しない',
'tog-showhiddencats'          => '隠しカテゴリを表示する',
'tog-noconvertlink'           => 'リンクタイトル変換を無効にする',
'tog-norollbackdiff'          => '巻き戻し後の差分を表示しない',

'underline-always'  => '常に付ける',
'underline-never'   => '常に付けない',
'underline-default' => 'ブラウザの設定を使用',

# Dates
'sunday'        => '日曜日',
'monday'        => '月曜日',
'tuesday'       => '火曜日',
'wednesday'     => '水曜日',
'thursday'      => '木曜日',
'friday'        => '金曜日',
'saturday'      => '土曜日',
'sun'           => '日',
'mon'           => '月',
'tue'           => '火',
'wed'           => '水',
'thu'           => '木',
'fri'           => '金',
'sat'           => '土',
'january'       => '1月',
'february'      => '2月',
'march'         => '3月',
'april'         => '4月',
'may_long'      => '5月',
'june'          => '6月',
'july'          => '7月',
'august'        => '8月',
'september'     => '9月',
'october'       => '10月',
'november'      => '11月',
'december'      => '12月',
'january-gen'   => '1月',
'february-gen'  => '2月',
'march-gen'     => '3月',
'april-gen'     => '4月',
'may-gen'       => '5月',
'june-gen'      => '6月',
'july-gen'      => '7月',
'august-gen'    => '8月',
'september-gen' => '9月',
'october-gen'   => '10月',
'november-gen'  => '11月',
'december-gen'  => '12月',
'jan'           => '1月',
'feb'           => '2月',
'mar'           => '3月',
'apr'           => '4月',
'may'           => '5月',
'jun'           => '6月',
'jul'           => '7月',
'aug'           => '8月',
'sep'           => '9月',
'oct'           => '10月',
'nov'           => '11月',
'dec'           => '12月',

# Categories related messages
'pagecategories'                 => '{{PLURAL:$1|カテゴリ}}',
'category_header'                => 'カテゴリ「$1」にあるページ',
'subcategories'                  => '下位カテゴリ',
'category-media-header'          => 'カテゴリ「$1」にあるメディア',
'category-empty'                 => "''このカテゴリには、ページまたはメディアがひとつもありません。''",
'hidden-categories'              => '{{PLURAL:$1|隠しカテゴリ}}',
'hidden-category-category'       => '隠しカテゴリ', # Name of the category where hidden categories will be listed
'category-subcat-count'          => '{{PLURAL:$2|このカテゴリには、次の下位カテゴリのみ含まれています。|このカテゴリには、次の$2下位カテゴリが含まれており、そのうち$1カテゴリが表示されています。}}',
'category-subcat-count-limited'  => 'このカテゴリには、次の{{PLURAL:$1|$1下位カテゴリ}}が含まれています。',
'category-article-count'         => '{{PLURAL:$2|このカテゴリには、次のページのみ含まれています。|以下の$2ページがこのカテゴリに含まれており、そのうち$1ページが表示されています。}}',
'category-article-count-limited' => '以下のページ{{PLURAL:$1|$1ページ}}が、現在のカテゴリに含まれています。',
'category-file-count'            => '{{PLURAL:$2|このカテゴリには、次のファイルのみが含まれています。|このカテゴリには、$2ファイルが含まれており、そのうち$1ファイルが表示されています。}}',
'category-file-count-limited'    => '以下の{{PLURAL:$1|$1ファイル}}が、現在のカテゴリに含まれています。',
'listingcontinuesabbrev'         => 'の続き',

'mainpagetext'      => "'''MediaWikiが正常にインストールされました。'''",
'mainpagedocfooter' => 'ウィキソフトウェアの使い方に関する情報は[http://meta.wikimedia.org/wiki/Help:Contents 利用者案内]を参照してください。

== はじめましょう ==
* [http://www.mediawiki.org/wiki/Manual:Configuration_settings/ja 設定の一覧]
* [http://www.mediawiki.org/wiki/Manual:FAQ/ja MediaWiki よくある質問と回答]
* [https://lists.wikimedia.org/mailman/listinfo/mediawiki-announce MediaWikiリリース情報メーリングリスト]',

'about'          => '解説',
'article'        => '本文',
'newwindow'      => '（新しいウィンドウが開きます）',
'cancel'         => '中止',
'qbfind'         => '検索',
'qbbrowse'       => '閲覧',
'qbedit'         => '編集',
'qbpageoptions'  => 'このページについて',
'qbpageinfo'     => '関連情報',
'qbmyoptions'    => '自分のページ',
'qbspecialpages' => '特別ページ',
'moredotdotdot'  => '続き・・・',
'mypage'         => '自分のページ',
'mytalk'         => '自分のトーク',
'anontalk'       => 'このIPアドレスのトーク',
'navigation'     => '案内',
'and'            => 'および',

# Metadata in edit box
'metadata_help' => 'メタデータ:',

'errorpagetitle'    => 'エラー',
'returnto'          => '$1に戻る。',
'tagline'           => '提供：{{SITENAME}}',
'help'              => 'ヘルプ',
'search'            => '検索',
'searchbutton'      => '検索',
'go'                => '表示',
'searcharticle'     => '表示',
'history'           => 'ページの履歴',
'history_short'     => '履歴',
'updatedmarker'     => '最後の訪問から更新されています',
'info_short'        => '情報',
'printableversion'  => '印刷用バージョン',
'permalink'         => 'この版への固定リンク',
'print'             => '印刷',
'edit'              => '編集',
'create'            => '作成',
'editthispage'      => 'このページを編集',
'create-this-page'  => 'このページを作成',
'delete'            => '削除',
'deletethispage'    => 'このページを削除',
'undelete_short'    => '{{PLURAL:$1|$1版}}を復帰',
'protect'           => '保護',
'protect_change'    => '設定変更',
'protectthispage'   => 'このページを保護',
'unprotect'         => '保護解除',
'unprotectthispage' => 'このページの保護を解除',
'newpage'           => '新規ページ',
'talkpage'          => 'このページについて話し合う',
'talkpagelinktext'  => 'トーク',
'specialpage'       => '特別ページ',
'personaltools'     => '個人用ツール',
'postcomment'       => '新しい節',
'articlepage'       => '本文を表示',
'talk'              => '議論',
'views'             => '表示',
'toolbox'           => 'ツールボックス',
'userpage'          => '利用者ページを表示',
'projectpage'       => 'プロジェクトのページを表示',
'imagepage'         => 'ファイルページを表示',
'mediawikipage'     => 'メッセージのページを表示',
'templatepage'      => 'テンプレートのページを表示',
'viewhelppage'      => 'ヘルプのページを表示',
'categorypage'      => 'カテゴリのページを表示',
'viewtalkpage'      => '議論を表示',
'otherlanguages'    => '他の言語',
'redirectedfrom'    => '（$1から転送）',
'redirectpagesub'   => '転送ページ',
'lastmodifiedat'    => 'このページは$1$2に最終更新されました。', # $1 date, $2 time
'viewcount'         => 'このページは{{PLURAL:$1|$1回}}アクセスされました。',
'protectedpage'     => '保護されたページ',
'jumpto'            => '移動：',
'jumptonavigation'  => '案内',
'jumptosearch'      => '検索',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => '{{SITENAME}}について',
'aboutpage'            => 'Project:{{SITENAME}}について',
'copyright'            => '内容は$1のライセンスで利用することができます。',
'copyrightpagename'    => '{{SITENAME}}の著作権',
'copyrightpage'        => '{{ns:project}}:著作権',
'currentevents'        => '最近の出来事',
'currentevents-url'    => 'Project:最近の出来事',
'disclaimers'          => '免責事項',
'disclaimerpage'       => 'Project:免責事項',
'edithelp'             => '編集の仕方',
'edithelppage'         => 'Help:編集',
'faq'                  => 'よくある質問と回答',
'faqpage'              => 'Project:よくある質問と回答',
'helppage'             => 'Help:目次',
'mainpage'             => 'メインページ',
'mainpage-description' => 'メインページ',
'policy-url'           => 'Project:方針',
'portal'               => 'コミュニティ・ポータル',
'portal-url'           => 'Project:コミュニティ・ポータル',
'privacy'              => 'プライバシー・ポリシー',
'privacypage'          => 'Project:プライバシー・ポリシー',

'badaccess'        => '権限がありません',
'badaccess-group0' => '要求した操作を行うことは許可されていません。',
'badaccess-groups' => 'この操作は、$1{{PLURAL:$2|の|のいずれかの}}グループに属する利用者のみが実行できます。',

'versionrequired'     => 'MediaWikiのバージョン$1が必要',
'versionrequiredtext' => 'このページの利用にはMediaWikiのバージョン$1が必要です。[[Special:Version|バージョン情報]]を確認してください。',

'ok'                      => 'OK',
'retrievedfrom'           => '「$1」より作成',
'youhavenewmessages'      => '$1が届いています。($2)',
'newmessageslink'         => '新しいメッセージ',
'newmessagesdifflink'     => '最終更新の差分',
'youhavenewmessagesmulti' => '$1に新しい伝言が届いています',
'editsection'             => '編集',
'editold'                 => '編集',
'viewsourceold'           => 'ソースを表示',
'editlink'                => '編集',
'viewsourcelink'          => 'ソースを表示',
'editsectionhint'         => '節を編集: $1',
'toc'                     => '目次',
'showtoc'                 => '表示',
'hidetoc'                 => '非表示',
'thisisdeleted'           => '$1を閲覧または復帰しますか？',
'viewdeleted'             => '$1を表示しますか？',
'restorelink'             => '削除された$1編集',
'feedlinks'               => 'フィード：',
'feed-invalid'            => 'フィード形式の指定が間違っています。',
'feed-unavailable'        => 'フィードの配信に対応していません。',
'site-rss-feed'           => '$1のRSSフィード',
'site-atom-feed'          => '$1のAtomフィード',
'page-rss-feed'           => '「$1」のRSSフィード',
'page-atom-feed'          => '「$1」のAtomフィード',
'red-link-title'          => '$1（存在しないページ）',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'ページ',
'nstab-user'      => '利用者ページ',
'nstab-media'     => 'メディアページ',
'nstab-special'   => '特別ページ',
'nstab-project'   => 'プロジェクトページ',
'nstab-image'     => 'ファイル',
'nstab-mediawiki' => 'メッセージ',
'nstab-template'  => 'テンプレート',
'nstab-help'      => 'ヘルプ',
'nstab-category'  => 'カテゴリ',

# Main script and global functions
'nosuchaction'      => 'そのような操作はありません',
'nosuchactiontext'  => 'このURLで指定された操作は無効です。
URLを間違って打ったか、不正なリンクを辿った可能性があります。
また、{{SITENAME}}が利用するソフトウェアのバグである可能性もあります。',
'nosuchspecialpage' => 'そのような特別ページはありません',
'nospecialpagetext' => '<strong>要求された特別ページは存在しません。</strong>

有効な特別ページの一覧は[[Special:SpecialPages|{{int:specialpages}}]]にあります。',

# General errors
'error'                => 'エラー',
'databaseerror'        => 'データベース・エラー',
'dberrortext'          => 'データベースクエリの構文エラーが発生しました。
ソフトウェアにバグがある可能性があります。
最後に実行を試みたクエリは次の通りです：
関数「<tt>$2</tt>」内
<blockquote><tt>$1</tt></blockquote>。
データベースの返したエラー「<tt>$3：$4</tt>」',
'dberrortextcl'        => 'データベースクエリの構文エラーが発生しました。
最後に実行を試みたクエリは次の通りです:
関数 "$2" 内
"$1"
データベースの返したエラー "$3: $4"',
'noconnect'            => '申し訳ありません。技術的な問題が発生しており、データベースサーバーに接続できません。<br />$1',
'nodb'                 => 'データベース $1 を選択できませんでした',
'cachederror'          => '以下は要求したページのキャッシュです。最新の更新を反映していない可能性があります。',
'laggedslavemode'      => "'''警告：'''ページに最新の編集が反映されていない可能性があります。",
'readonly'             => 'データベースがロックされています',
'enterlockreason'      => 'ロックの理由とロック解除の予定を入力してください',
'readonlytext'         => 'データベースは現在、新しいページの追加や編集を受け付けない「ロック状態」になっています。これはおそらくデータベースの定期メンテナンスのためで、メンテナンス終了後は正常な状態に復帰します。

データベースをロックした管理者による説明は以下の通りです：$1',
'missing-article'      => '「$1」$2というページの本文をデータベース上に見つけることができませんでした。

ページの削除された版への古い差分表示や固定リンクをたどった時にこのようなことになります。

それ以外の操作でこのメッセージが表示された場合、ソフトウェアのバグである可能性があります。
[[Special:ListUsers/sysop|管理者]]までそのURLを添えてお知らせください。',
'missingarticle-rev'   => '（版番号：$1）',
'missingarticle-diff'  => '（差分：$1、$2）',
'readonly_lag'         => 'データベースはスレーブのデータベースサーバーがマスターに同期するまで自動的にロックされています',
'internalerror'        => '内部エラー',
'internalerror_info'   => '内部エラー：$1',
'filecopyerror'        => 'ファイル「$1」を「$2」へ複製できませんでした。',
'filerenameerror'      => 'ファイル名を「$1」から「$2」へ変更できませんでした。',
'filedeleteerror'      => 'ファイル「$1」を削除できませんでした。',
'directorycreateerror' => 'ディレクトリー「$1」を作成できませんでした。',
'filenotfound'         => 'ファイル「$1」が見つかりませんでした。',
'fileexistserror'      => 'ファイル「$1」への書き込みができません：ファイルが存在します',
'unexpected'           => '予期しない値「$1」=「$2」です。',
'formerror'            => 'エラー：フォームを送信できませんでした',
'badarticleerror'      => 'このページでは要求された操作を行えません。',
'cannotdelete'         => '指定されたページまたはファイルを削除できませんでした。すでに他の利用者によって削除された可能性があります。',
'badtitle'             => '不正なページ名',
'badtitletext'         => '要求されたページは、空、無効、または正しくない言語間リンク・ウィキ間リンクです。
ページ名に利用できない文字が1つまたは複数含まれている可能性があります。',
'perfcached'           => '以下のデータはキャッシュであり、最新の更新を反映していない可能性があります。',
'perfcachedts'         => '以下のデータは$1に最終更新されたキャッシュです。',
'querypage-no-updates' => 'ページの更新は無効になっています。
以下のデータの更新は現在行われていません。',
'wrong_wfQuery_params' => 'wfQuery()へ誤った引数が渡されました。<br />
関数：$1<br />
クエリ：$2',
'viewsource'           => 'ソースを表示',
'viewsourcefor'        => '$1のソース',
'actionthrottled'      => '操作が速度規制されました',
'actionthrottledtext'  => '短時間にこの操作を大量に行ったため、スパム対策として設定されている制限を超えました。
少し時間をおいてからもう一度操作してください。',
'protectedpagetext'    => 'このページは編集できないように保護されています。',
'viewsourcetext'       => 'このページのソースを閲覧し、コピーすることができます：',
'protectedinterface'   => 'このページはソフトウェアのインターフェースに使用されるテキストが保存されており、いたずらなどの防止のために保護されています。',
'editinginterface'     => "'''警告：'''ソフトウェアのインターフェースに使用されているテキストを編集しています。
このページの変更はすべての利用者のユーザーインタフェースに影響します。
翻訳をする場合、MediaWikiの地域化プロジェクト[http://translatewiki.net/wiki/Main_Page?setlang=ja translatewiki.net]の利用を検討してください。",
'sqlhidden'            => '（SQLクエリ非表示）',
'cascadeprotected'     => 'このページは、「連続」選択肢が有効な状態で保護されている以下の{{PLURAL:$1|ページ}}で読み込まれているため、編集できないように保護されています：
$2',
'namespaceprotected'   => "'''$1'''名前空間にあるページを編集する権限がありません。",
'customcssjsprotected' => 'このページは他の利用者の個人設定を含んでいるため、編集する権限がありません。',
'ns-specialprotected'  => '特別ページは編集できません。',
'titleprotected'       => "[[User:$1|$1]]によりこのページ名を持つページの作成は保護されています。
理由は「''$2''」です。",

# Virus scanner
'virus-badscanner'     => "環境設定が不適合です：不明なウイルス検知ソフトウェア：''$1''",
'virus-scanfailed'     => 'スキャンに失敗しました（コード $1）',
'virus-unknownscanner' => '不明なウイルス対策：',

# Login and logout pages
'logouttitle'                => 'ログアウト',
'logouttext'                 => "'''ログアウトしました。'''

このまま匿名で{{SITENAME}}を使い続けることができます。またもう一度、同じあるいは別の利用者として[[Special:UserLogin|ログイン]]することもできます。
なお、ページによっては、ブラウザのキャッシュをクリアするまで、ログインしているかのように表示され続けることがあるので注意してください。",
'welcomecreation'            => '== ようこそ、$1さん！ ==
アカウントが作成されました。
[[Special:Preferences|{{SITENAME}}の個人設定]]の変更も忘れないようにしてください。',
'loginpagetitle'             => 'ログイン',
'yourname'                   => '利用者名：',
'yourpassword'               => 'パスワード：',
'yourpasswordagain'          => 'パスワード再入力：',
'remembermypassword'         => 'このコンピューターにログイン情報を保存する',
'yourdomainname'             => 'ドメイン：',
'externaldberror'            => '外部の認証データベースでエラーが発生したか、または外部アカウント情報の更新が許可されていません。',
'login'                      => 'ログイン',
'nav-login-createaccount'    => 'ログインまたはアカウント作成',
'loginprompt'                => '{{SITENAME}}にログインするにはクッキーを有効にする必要があります。',
'userlogin'                  => 'ログインまたはアカウント作成',
'logout'                     => 'ログアウト',
'userlogout'                 => 'ログアウト',
'notloggedin'                => 'ログインしていません',
'nologin'                    => '登録がまだの場合、$1。',
'nologinlink'                => 'アカウントを作成してください',
'createaccount'              => 'アカウント作成',
'gotaccount'                 => '既にアカウントを持っている場合、$1。',
'gotaccountlink'             => 'ログインしてください',
'createaccountmail'          => 'メールで送信',
'badretype'                  => '入力したパスワードが一致しません。',
'userexists'                 => '入力された利用者名はすでに使われています。
ほかの名前を選んでください。',
'youremail'                  => '電子メール：',
'username'                   => '利用者名：',
'uid'                        => '利用者ID：',
'prefs-memberingroups'       => '所属する{{PLURAL:$1|グループ}}：',
'yourrealname'               => '本名：',
'yourlanguage'               => '使用言語：',
'yourvariant'                => '言語変種：',
'yournick'                   => '署名:',
'badsig'                     => '署名用のソースが正しくありません。
HTMLタグを見直してください。',
'badsiglength'               => '署名が長すぎます。$1文字以下でなければなりません。',
'yourgender'                 => '性別：',
'gender-unknown'             => '未指定',
'gender-male'                => '男',
'gender-female'              => '女',
'prefs-help-gender'          => '省略可能：ソフトウェアによる文法的性の解決に使用されます。
この情報は公開されます。',
'email'                      => '電子メール',
'prefs-help-realname'        => '本名登録は省略可能です。
登録した場合、著作物の帰属表示に本名が用いられます。',
'loginerror'                 => 'ログインのエラー',
'prefs-help-email'           => '電子メールアドレスの設定は省略可能ですが、パスワードを忘れた際に新しいパスワードを電子メールで受け取る場合に必要です。
一方で、利用者ページやトークページを使っての他者との連絡は身元を明らかにする必要がありません。',
'prefs-help-email-required'  => 'メールアドレスが必要です。',
'nocookiesnew'               => '利用者アカウントが作成されましたが、ログインしていません。
{{SITENAME}}ではログインにクッキーを使用します。
クッキーが無効になっているようです。
クッキーを有効にしてから、新しい利用者名とパスワードでログインしてください。',
'nocookieslogin'             => '{{SITENAME}}ではログインにクッキーを使用します。
クッキーが無効になっているようです。
クッキーを有効にして、もう一度試してください。',
'noname'                     => '利用者名を正しく指定していません。',
'loginsuccesstitle'          => 'ログイン成功',
'loginsuccess'               => "'''{{SITENAME}}に「$1」としてログインしました。'''",
'nosuchuser'                 => '「$1」という名前の利用者は見当たりません。
利用者名では大文字と小文字を区別します。
綴りが正しいことを確認するか、[[Special:UserLogin/signup|新たにアカウントを作成してください]]。',
'nosuchusershort'            => '「<nowiki>$1</nowiki>」という利用者は見当たりません。
綴りが正しいことを再度確認してください。',
'nouserspecified'            => '利用者名を指定してください。',
'wrongpassword'              => 'パスワードが間違っています。 
もう一度やり直してください。',
'wrongpasswordempty'         => 'パスワードを空にすることはできません。
もう一度やり直してください。',
'passwordtooshort'           => 'パスワードが無効、または短すぎます。パスワードは$1文字以上の文字列でなければなりません。また利用者名と同じものは使えません。',
'mailmypassword'             => '新しいパスワードを電子メールで送る',
'passwordremindertitle'      => '{{SITENAME}}の仮パスワード通知',
'passwordremindertext'       => '誰か（おそらく自身、IPアドレス$1から）が{{SITENAME}}（$4）のログイン用パスワードの再発行を申請しました。
「$2」の仮パスワードは "$3" です。
もし自身でパスワードの発行を依頼したのであれば、ログインして別のパスワードに変更してください。
この仮パスワードは{{PLURAL:$5|$5日間}}で有効期限が切れます。

パスワード再発行の申請に覚えがない、またはログイン用パスワードを思い出して、パスワード変更の必要がないなら、このメッセージは無視して、引き続き以前のパスワードを使用し続けることができます。',
'noemail'                    => '利用者「$1」のメールアドレスは登録されていません。',
'passwordsent'               => '新しいパスワードを「$1」に登録されたメールアドレスに送信しました。
メールを受け取ったら、再度ログインしてください。',
'blocked-mailpassword'       => '使用しているIPアドレスからの編集はブロックされており、不正利用防止のため、パスワードの再発行は拒否されます。',
'eauthentsent'               => '指定されたメールアドレスにアドレス確認のためのメールを送信しました。
その他のメールがこのアカウント宛に送信される前に、メールの指示に従って、このアカウントが本当に自身のものであるか確認してください。',
'throttled-mailpassword'     => '新しいパスワードは{{PLURAL:$1|$1時間}}以内に送信済みです。
悪用防止のため、パスワードは{{PLURAL:$1|$1時間}}間隔でのみ再発行可能です。',
'mailerror'                  => 'メールの送信中にエラーが発生しました：$1',
'acct_creation_throttle_hit' => '同じIPアドレスでこのウィキへ訪れた人が、直前24時間で{{PLURAL:$1|$1個}}のアカウントを作成しており、これはこの期間中に作成が許可されている最大数です。
そのため、現在このIPアドレスの利用者はアカウントをこれ以上作成できません。',
'emailauthenticated'         => 'メールアドレスは$2$3に認証されています。',
'emailnotauthenticated'      => 'メールアドレスが認証されていません。
確認されるまで以下のいかなるメールも送られません。',
'noemailprefs'               => 'これらの機能を有効にするためには個人設定でメールアドレスを登録する必要があります。',
'emailconfirmlink'           => 'メールアドレスを確認する',
'invalidemailaddress'        => '入力されたメールアドレスが正しい形式に従っていないため、受け付けられません。
正しい形式で入力し直すか、メールアドレス欄を空にしておいてください。',
'accountcreated'             => 'アカウントを作成しました',
'accountcreatedtext'         => '利用者アカウント：$1が作成されました。',
'createaccount-title'        => '{{SITENAME}}のアカウント作成',
'createaccount-text'         => '{{SITENAME}} ($4) に「$2」という名前のアカウントが、この電子メールアドレスを連絡先として作成されました。パスワードは「$3」です。
今すぐログインし、パスワードを変更してください。

何かの手違いでアカウントが作成されたと思う場合、このメッセージは無視してください。',
'login-throttled'            => 'ログインの失敗が制限回数を超えました。
しばらく時間をおいてから再度お試しください。',
'loginlanguagelabel'         => '言語: $1',

# Password reset dialog
'resetpass'                 => 'パスワードの変更',
'resetpass_announce'        => '電子メールで送信された仮パスワードでログインしています。
ログインを完了するには、ここで新しいパスワードを設定しなおす必要があります：',
'resetpass_text'            => '<!-- ここに文を挿入 -->',
'resetpass_header'          => 'アカウントのパスワードを変更',
'oldpassword'               => '古いパスワード：',
'newpassword'               => '新しいパスワード：',
'retypenew'                 => '新しいパスワードを再入力:',
'resetpass_submit'          => '再設定してログイン',
'resetpass_success'         => 'パスワードの変更に成功しました！
ログインしています・・・',
'resetpass_bad_temporary'   => '無効な仮パスワードです。すでにパスワード変更を行っているか、新しい仮パスワードの発行を依頼していませんか。',
'resetpass_forbidden'       => 'パスワードは変更できません',
'resetpass-no-info'         => 'このページに直接アクセスするためにはログインしている必要があります。',
'resetpass-submit-loggedin' => 'パスワードを変更',
'resetpass-wrong-oldpass'   => '仮パスワードまたは現在のパスワードが無効です。
すでにパスワード変更を行っているか、新しい仮パスワードの発行を依頼している可能性があります。',
'resetpass-temp-password'   => '仮パスワード：',
'resetpass-log'             => 'パスワード再設定記録',
'resetpass-logtext'         => '以下は管理者が利用者のパスワードを再設定した記録です。',
'resetpass-logentry'        => '$1のパスワードを変更しました',
'resetpass-comment'         => 'パスワードを再設定する理由:',

# Edit page toolbar
'bold_sample'     => '太字',
'bold_tip'        => '太字',
'italic_sample'   => '斜体',
'italic_tip'      => '斜体',
'link_sample'     => 'リンクの名前',
'link_tip'        => '内部リンク',
'extlink_sample'  => 'http://www.example.com リンクの名前',
'extlink_tip'     => '外部リンク (http:// を忘れずにつけてください)',
'headline_sample' => '見出し文',
'headline_tip'    => '2段目の見出し',
'math_sample'     => 'ここに数式を挿入',
'math_tip'        => '数式 (LaTeX)',
'nowiki_sample'   => 'ここにマークアップを無効にするテキストを入力します',
'nowiki_tip'      => 'ウィキ書式を無視',
'image_tip'       => 'ファイルの埋め込み',
'media_sample'    => '例.ogg',
'media_tip'       => 'ファイルへのリンク',
'sig_tip'         => '時刻印つきの署名',
'hr_tip'          => '水平線を挿入（利用は控えめに）',

# Edit pages
'summary'                          => '編集内容の要約：',
'subject'                          => '題名・見出し：',
'minoredit'                        => 'これは細部の編集です',
'watchthis'                        => 'このページをウォッチする',
'savearticle'                      => 'ページを保存',
'preview'                          => 'プレビュー',
'showpreview'                      => 'プレビューを表示',
'showlivepreview'                  => 'ライブプレビュー',
'showdiff'                         => '差分を表示',
'anoneditwarning'                  => "'''警告：'''ログインしていません。
このまま投稿を行った場合、使用中のIPアドレスがこのページの編集履歴に記録されます。",
'missingsummary'                   => "'''注意：'''要約欄が空欄です。
「{{int:savearticle}}」をもう一度クリックすると、編集は要約なしで保存されます。",
'missingcommenttext'               => '以下にコメントを入力してください。',
'missingcommentheader'             => "'''注意:''' 題名・見出しが空欄です。投稿ボタンをもう一度押すと、要約なしのまま投稿されます。",
'summary-preview'                  => '要約のプレビュー：',
'subject-preview'                  => '題名・見出しのプレビュー：',
'blockedtitle'                     => '利用者はブロックされています',
'blockedtext'                      => "'''この利用者名またはIPアドレスはブロックされています。'''

ブロックは$1によって実施されました。
ブロックの理由は「$2」です。

* ブロック開始時期：$8
* ブロック解除予定：$6
* ブロック対象：$7

このブロックについて、$1もしくは他の[[{{MediaWiki:Grouppage-sysop}}|管理者]]に問い合わせることができます。
ただし、[[Special:Preferences|個人設定]]で有効なメールアドレスが登録されていない場合、またはメール送信機能の使用がブロックされている場合、「この利用者にメールを送信」の機能は使えません。
現在のIPアドレスは$3、このブロックIDは&#x23;$5です。
問い合わせを行う際には、上記の情報を必ず書いてください。",
'autoblockedtext'                  => "利用中のIPアドレスは、$1によって投稿をブロックされた利用者によって使用されたために自動的にブロックされています。
理由は次の通りです。

:''$2''

* ブロックの開始：$8
* ブロック解除予定：$6
* 意図されているブロック対象者：$7

$1または他の[[{{MediaWiki:Grouppage-sysop}}|管理者]]にこの件について問い合わせることができます。

ただし、[[Special:Preferences|個人設定]]に正しいメールアドレスが登録されていない場合、またはメール送信がブロックされている場合、メール送信機能が使えないことに注意してください。

現在利用中のIPアドレスは$3 、このブロックIDは&#x23;$5です。
問い合わせを行う際には、この情報を必ず書いてください。",
'blockednoreason'                  => '理由が設定されていません',
'blockedoriginalsource'            => "以下に'''$1'''のソースを示します：",
'blockededitsource'                => "'''$1'''への'''編集'''を以下に示します：",
'whitelistedittitle'               => '編集にはログインが必要',
'whitelistedittext'                => 'このページを編集するには$1する必要があります。',
'confirmedittitle'                 => '編集にはメールアドレスの確認が必要',
'confirmedittext'                  => 'ページの編集を始める前にメールアドレスの確認をする必要があります。
[[Special:Preferences|個人設定]]でメールアドレスを設定し、確認を行ってください。',
'nosuchsectiontitle'               => '節が見つかりません',
'nosuchsectiontext'                => '存在しない節を編集しようとしました。
ページを閲覧している間に移動あるいは削除された可能性があります。',
'loginreqtitle'                    => 'ログインが必要',
'loginreqlink'                     => 'ログイン',
'loginreqpagetext'                 => '他のページを閲覧するには$1する必要があります。',
'accmailtitle'                     => 'パスワードを送信しました。',
'accmailtext'                      => "[[User talk:$1|$1]]のために無作為に生成したパスワードを、$2に送信しました。

この新アカウントのパスワードは、ログインした際に''[[Special:ChangePassword|パスワード変更]]''ページで変更できます。",
'newarticle'                       => '（新）',
'newarticletext'                   => "まだ存在していないページへのリンクをたどりました。
このページを新規に作成するには、下のボックスに内容を書き込んでください（詳しくは[[{{MediaWiki:Helppage}}|ヘルプページ]]を参照してください）。
誤ってこのページにたどり着いた場合には、ブラウザの'''戻る'''ボタンを使って前のページに戻ってください。",
'anontalkpagetext'                 => "----''このページはアカウントをまだ作成していないか使用していない匿名利用者のための議論ページです。
匿名利用者を識別するために、利用者名のかわりにIPアドレスが使用されています。
IPアドレスは複数の利用者の間で共有されていることがあります。
もし、自身が匿名利用者であり、自分に関係のないコメントが寄せられている考えられる場合は、[[Special:UserLogin/signup|アカウントを作成する]]か[[Special:UserLogin|ログインして]]他の匿名利用者と間違えられないようにしてください。''",
'noarticletext'                    => '現在このページには内容がありません。
他のページに含まれる[[Special:Search/{{PAGENAME}}|このページ名を検索する]]か、
<span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} 関連記録を検索する]か、
もしくは、[{{fullurl:{{FULLPAGENAME}}|action=edit}} このページを編集]</span>することができます。',
'userpage-userdoesnotexist'        => '「$1」という名前のアカウントは登録されていません。
このページを編集することが適切かどうか確認してください。',
'clearyourcache'                   => "'''注意：'''保存した後、変更を確認するには、ブラウザのキャッシュをクリアする必要があります。'''
'''Mozilla/Firefox/Safari：'''''Shift''を押しながら''再読み込み''をクリック、または ''Ctrl-F5''か''Ctrl-R''を押してください（Macintoshでは''Command-R''）。
'''Konqueror：'''''再読み込み''をクリック、または''F5''を押してください。
'''Opera：'''''ツール→設定''からキャッシュをクリアしてください。
'''Internet Explorer：'''''Ctrl''を押しながら''更新''をクリック、またはCtrl-F5を押してください。",
'usercssjsyoucanpreview'           => "'''ヒント:''' 「{{int:showpreview}}」ボタンを使うと保存前に新しいスタイルシートやスクリプトをテストできます。",
'usercsspreview'                   => "'''利用者CSSをプレビューしています。'''
'''まだ保存されていません！'''",
'userjspreview'                    => "'''利用者JavaScriptを試験、プレビューしています。'''
'''まだ保存されていません！'''",
'userinvalidcssjstitle'            => "'''警告:''' 「$1」というスキンはありません。.css と .js ページを編集する際にはサブページ名を小文字にすることを忘れないでください。例えば {{ns:user}}:Hoge/Monobook.css ではなく {{ns:user}}:Hoge/monobook.css となります。",
'updated'                          => '（更新）',
'note'                             => "'''お知らせ：'''",
'previewnote'                      => "'''これはプレビューです。'''
変更はまだ保存されていません！",
'previewconflict'                  => 'このプレビューは、上の文章編集エリアの文章を保存した場合にどう見えるようになるかを示すものです。',
'session_fail_preview'             => "'''申し訳ありません！セッションが切断されたため編集を処理できませんでした。'''
もう一度やりなおしてください。
それでも失敗する場合、[[Special:UserLogout|ログアウト]]してからログインし直してください。",
'session_fail_preview_html'        => "'''申し訳ありません！セッションが切断されたため編集を処理することができませんでした。'''

''{{SITENAME}}では生のHTMLが有効であり、JavaScriptでの攻撃を予防するためにプレビューを表示していません。''

'''この編集が問題ないものであるならば、もう一度保存してください。'''
それでもうまくいかない際には一度[[Special:UserLogout|ログアウト]]して、ログインし直してみてください。",
'token_suffix_mismatch'            => "'''使用中のクライアントが編集トークン内の句読点を正しく処理していないため、編集を受け付けられません。'''
ページ本文の破損を防ぐため、編集は反映されません。
これは、問題のある匿名プロキシサービスを利用していると、起こることがあります。",
'editing'                          => '「$1」を編集中',
'editingsection'                   => '「$1」を編集中 (節単位)',
'editingcomment'                   => '「$1」を編集中 (新しい節)',
'editconflict'                     => '編集競合：$1',
'explainconflict'                  => "このページを編集し始めた後に、他の誰かがこのページを変更しました。
上側のテキスト領域は現在の最新の状態です。
編集していた文章は下側のテキスト領域に示されています。
編集していた文章を、上側のテキスト領域の、既存の文章に組み込んでください。
上側のテキスト領域の内容'''だけ'''が、「{{int:savearticle}}」をクリックした時に実際に保存されます。",
'yourtext'                         => '編集中の文章',
'storedversion'                    => '保存された版',
'nonunicodebrowser'                => "'''警告：使用中のブラウザがUnicodeに対応していません。'''
安全にページを編集する回避策が表示されています：編集ボックス中の非ASCII文字は16進数文字コードによって表現されます。",
'editingold'                       => "'''警告：このページの古い版を編集しています。'''
この文章を保存すると、この版以降に追加されたすべての変更が失われます。",
'yourdiff'                         => '差分',
'copyrightwarning'                 => "{{SITENAME}}への投稿は、すべて$2（詳細は$1を参照）のもとで公開されたと見なされることにご注意ください。
投稿されたものが、他人によって遠慮なく編集され、自由に配布されることを望まない場合は、ここには投稿しないでください。<br />
また、投稿されるものは、自身によって書かれたものであるか、パブリック・ドメイン、またはそれに類するフリーな資料からの複製であることを約束してください。
'''著作権保護されている作品を、許諾なしに投稿しないでください！'''",
'copyrightwarning2'                => "{{SITENAME}}への全ての投稿は、他の利用者によって編集、変更、除去される可能性があります。
自信の投稿が他人によって遠慮なく編集されることを望まない場合は、ここには投稿しないでください。<br />
また、投稿されるものは、自身によって書かれたものであるか、パブリック・ドメイン、またはそれに類するフリーな資料からの複製であることを約束してください（詳細は$1を参照）。
'''著作権保護されている作品を、許諾なしに投稿してはいけません！'''",
'longpagewarning'                  => "'''警告:''' このページのサイズは $1 キロバイトです。一部のブラウザには 32 キロバイト以上のページを編集すると問題が起きるものがあります。ページをセクションに分けることを検討してください。",
'longpageerror'                    => "'''エラー：投稿された文章はは$1キロバイトの長さがあります。これは投稿できる最大の長さである$2キロバイトを超えています。'''
この編集は保存できません。",
'readonlywarning'                  => "'''警告：データベースがメンテナンスのためにロックされているため、現在は編集を保存できません。'''
必要であれば文章をカットアンドペーストしてテキストファイルとして保存し、後ほど保存をやり直してください。

データベースをロックした管理者による説明は以下の通りです：$1",
'protectedpagewarning'             => "'''警告:このページは保護されています。{{int:group-sysop}}しか編集できません。'''",
'semiprotectedpagewarning'         => "'''お知らせ:''' このページは登録利用者のみが編集できるよう保護されています。",
'cascadeprotectedwarning'          => "'''警告：'''このページは連続保護されている以下の{{PLURAL:$1|ページ}}から読み込まれているため保護されており、そのため管理者権限を持つ利用者しか編集できません。",
'titleprotectedwarning'            => "'''警告: このページは保護されているため、作成には[[Special:ListGroupRights|一定の権限]]が必要です。'''",
'templatesused'                    => 'このページで使われているテンプレート:',
'templatesusedpreview'             => 'このプレビューで使われているテンプレート:',
'templatesusedsection'             => 'このセクションで使われているテンプレート:',
'template-protected'               => '（保護）',
'template-semiprotected'           => '（半保護）',
'hiddencategories'                 => 'このページは$1隠しカテゴリに属しています：',
'edittools'                        => '<!-- ここに書いたテキストは編集及びアップロードのフォームの下に表示されます。 -->',
'nocreatetitle'                    => 'ページの作成が制限されています',
'nocreatetext'                     => '{{SITENAME}}ではページの新規作成を制限しています。
元のページに戻って既存のページを編集するか、[[Special:UserLogin|ログインまたはアカウントの作成]]をしてください。',
'nocreate-loggedin'                => '新しいページを作成する権限がありません。',
'permissionserrors'                => '認証エラー',
'permissionserrorstext'            => 'このページの編集権限がありません。{{PLURAL:$1|理由}}は以下の通りです：',
'permissionserrorstext-withaction' => '以下に示された{{PLURAL:$1|理由}}により、$2を行うことができません：',
'recreate-deleted-warn'            => "'''警告: あなたは以前に削除されたページを再作成しようとしています。'''

このページを編集し続けることが適切であるかどうか確認してください。参考として以下にこのページの削除記録を表示しています:",
'deleted-notice'                   => 'このページは削除されています。
参考のため、このページの削除ログが以下に表示されています。',
'deletelog-fulllog'                => '完全なログを表示',
'edit-hook-aborted'                => 'フックによって編集が破棄されました。
理由は不明です。',
'edit-gone-missing'                => 'ページを更新できませんでした。
既に削除されているようです。',
'edit-conflict'                    => '編集が競合。',
'edit-no-change'                   => '文章が変更されていないため、編集は無視されました。',
'edit-already-exists'              => '新しいページを作成できませんでした。
そのページは、すでに存在しています。',

# Parser/template warnings
'expensive-parserfunction-warning'        => "'''警告：'''このページでの、高負荷な構文解析関数の呼び出し回数が多過ぎます。

呼び出しは{{PLURAL:$2|$2}}回以下である必要があります（現在は{{PLURAL:$1|$1}}回）。",
'expensive-parserfunction-category'       => '高負荷な構文解析関数の呼び出しが多過ぎるページ',
'post-expand-template-inclusion-warning'  => "'''警告：'''テンプレートの読み込みサイズが大き過ぎます。
いくつかのテンプレートは読み込まれません。",
'post-expand-template-inclusion-category' => 'テンプレート読み込みサイズが制限値を越えているページ',
'post-expand-template-argument-warning'   => "'''警告：'''このページには、展開後のサイズが大きすぎる値を渡したテンプレートが1つ以上含まれています。
これらの値は省略されました。",
'post-expand-template-argument-category'  => '省略されたテンプレート引数を含むページ',
'parser-template-loop-warning'            => 'テンプレートのループが検出されました：[[$1]]',
'parser-template-recursion-depth-warning' => 'テンプレートの再帰深さ（$1）が上限値を超えました',

# "Undo" feature
'undo-success' => '取り消しが可能です。
これが意図した操作であるか、下に表示されている差分を確認し、取り消しを確定させるために、変更を保存してください。',
'undo-failure' => '中間の版での編集と競合したため、取り消せませんでした。',
'undo-norev'   => '取り消そうとした編集は存在しないかすでに削除されたために取り消せませんでした。',
'undo-summary' => '[[Special:Contributions/$2|$2]]（[[User talk:$2|トーク]]）による第$1版を取り消し',

# Account creation failure
'cantcreateaccounttitle' => 'アカウントを作成できません',
'cantcreateaccount-text' => "このIPアドレス('''$1''')からのアカウント作成は[[User:$3|$3]]によってブロックされています。

$3による理由は以下の通りです：''$2''",

# History pages
'viewpagelogs'           => 'このページに関する記録を表示',
'nohistory'              => 'このページには編集履歴がありません。',
'currentrev'             => '最新版',
'currentrev-asof'        => '$1 時点における最新版',
'revisionasof'           => '$1時点における版',
'revision-info'          => '$1時点における $2 による版', # Additionally available: $3: revision id
'previousrevision'       => '←前の版',
'nextrevision'           => '次の版→',
'currentrevisionlink'    => '最新版',
'cur'                    => '最新',
'next'                   => '次',
'last'                   => '前',
'page_first'             => '先頭',
'page_last'              => '末尾',
'histlegend'             => "差分の選択：比較したい版のラジオボタンを選択し、エンターキーを押すか、下部のボタンを押します。<br />
凡例：'''({{int:cur}})'''＝最新版との比較、'''({{int:last}})'''＝直前の版との比較、'''{{int:minoreditletter}}'''＝細部の編集",
'history-fieldset-title' => '履歴の閲覧',
'deletedrev'             => '[削除済み]',
'histfirst'              => '最古',
'histlast'               => '最新',
'historysize'            => '$1バイト',
'historyempty'           => '（空）',

# Revision feed
'history-feed-title'          => '変更履歴',
'history-feed-description'    => 'このウィキのこのページに関する変更履歴',
'history-feed-item-nocomment' => '$2 における $1 による編集', # user at time
'history-feed-empty'          => '要求されたページは存在しません。
ウィキから既に削除されたか、名前が変更された可能性があります。
[[Special:Search|このウィキの検索]]で関連する新しいページを探してみてください。',

# Revision deletion
'rev-deleted-comment'            => '（要約は除去されています）',
'rev-deleted-user'               => '（利用者名は除去されています）',
'rev-deleted-event'              => '（記録は除去されています）',
'rev-deleted-text-permission'    => "この版は'''削除されています'''。削除の詳細は[{{fullurl:Special:Log/delete|page={{FULLPAGENAMEE}}}} 削除記録]を参照してください。",
'rev-deleted-text-view'          => "この版は'''削除されています'''。あなたは{{int:group-sysop}}であるため内容を見ることができます。削除の詳細は[{{fullurl:Special:Log/delete|page={{FULLPAGENAMEE}}}} 削除記録]を参照してください。",
'rev-deleted-no-diff'            => "表示させようとした差分のいずれかの版が'''削除されているため'''、差分表示できません。削除の詳細は[{{fullurl:Special:Log/delete|page={{FULLPAGENAMEE}}}} 削除記録]を参照してください。",
'rev-deleted-unhide-diff'        => "この差分のいずれかの版が'''削除されています'''。削除の詳細は[{{fullurl:Special:Log/delete|page={{FULLPAGENAMEE}}}} 削除記録]を参照してください。あなたは{{int:group-sysop}}であるため、このまま[$1 この差分を見る]ことができます。",
'rev-delundel'                   => '表示/非表示',
'revisiondelete'                 => '版の削除と復帰',
'revdelete-nooldid-title'        => '不正な対象版',
'revdelete-nooldid-text'         => 'この操作の対象となる版を指定していないか、指定した版が存在していないか、あるいは最新版を非表示しようとしています。',
'revdelete-nologtype-title'      => '記録の種類が指定されていません',
'revdelete-nologtype-text'       => 'この操作を実行する記録の種類を指定していません。',
'revdelete-toomanytargets-title' => '対象が多すぎます',
'revdelete-toomanytargets-text'  => 'この操作の実行に指定した対象の種類が多すぎます。',
'revdelete-nologid-title'        => '不正な記録項目',
'revdelete-nologid-text'         => 'この操作の対象となる記録の項目を指定していないか、あるいは指定した項目が存在しません。',
'revdelete-selected'             => "'''[[:$1]]の{{PLURAL:$2|選択された版}}：'''",
'logdelete-selected'             => "'''{{PLURAL:$1|選択された記録の項目}}：'''",
'revdelete-text'                 => "'''削除された版や記録はページの履歴やログに表示され続けますが、一般の利用者はその内容にアクセスできなくなります。'''

追加の制限がかけられない限り、{{SITENAME}} の他の{{int:group-sysop}}もこれと同じインターフェースを使って隠された内容にアクセスしたり、復元したりできます。あなたが何をしようとしているのか、それによってどんな影響がうまれるか、および、[[{{MediaWiki:Policy-url}}|方針]]に従っているかどうか、確認をしてください。",
'revdelete-suppress-text'        => "隠蔽は、'''以下の場合に限って'''使用すべきです：
* 名誉毀損の恐れのある記述
* 非公開個人情報
*: ''自宅の住所や電話番号、社会保障番号など''",
'revdelete-legend'               => '閲覧レベル制限を設定',
'revdelete-hide-text'            => '版の本文を隠す',
'revdelete-hide-name'            => '操作および対象を隠す',
'revdelete-hide-comment'         => '編集の要約を隠す',
'revdelete-hide-user'            => '投稿者の利用者名またはIPを隠す',
'revdelete-hide-restricted'      => '他の利用者と同様に管理者からもデータを隠蔽する',
'revdelete-suppress'             => '他の利用者と同様に管理者からもデータを隠す',
'revdelete-hide-image'           => 'ファイル内容を隠す',
'revdelete-unsuppress'           => '復元版に対する制限を除去',
'revdelete-log'                  => '理由：',
'revdelete-submit'               => '選択した版に適用',
'revdelete-logentry'             => '「[[$1]]」の版の閲覧レベルを変更しました',
'logdelete-logentry'             => '「[[$1]]」の操作の閲覧レベルを変更しました',
'revdelete-success'              => "'''版の閲覧レベルを更新しました。'''",
'logdelete-success'              => "'''記録の閲覧レベルを変更しました。'''",
'revdel-restore'                 => '閲覧レベルを変更',
'pagehist'                       => 'ページの履歴',
'deletedhist'                    => '削除された履歴',
'revdelete-content'              => '本文',
'revdelete-summary'              => '編集内容の要約',
'revdelete-uname'                => '利用者名',
'revdelete-restricted'           => '管理者に対する制限を適用しました',
'revdelete-unrestricted'         => '管理者に対する制限を除去しました',
'revdelete-hid'                  => '$1を隠しました',
'revdelete-unhid'                => '$1の版指定削除を解除しました',
'revdelete-log-message'          => '$2版に対して$1',
'logdelete-log-message'          => '$2の{{PLURAL:$2|操作}}に対して$1',

# Suppression log
'suppressionlog'     => '秘匿記録',
'suppressionlogtext' => '以下は管理者から秘匿された内容を含む削除およびブロック記録です。
現在操作できるブロックについては[[Special:IPBlockList|投稿ブロック中の利用者やIPアドレス]]を参照してください。',

# History merging
'mergehistory'                     => 'ページ履歴の統合',
'mergehistory-header'              => 'このページは、1つの元ページの履歴を新しいページに統合します。
この変更を行っても履歴ページの連続性が保たれることを確認してください。',
'mergehistory-box'                 => '2ページの過去の版を統合する：',
'mergehistory-from'                => '統合元となるページ：',
'mergehistory-into'                => '統合先のページ：',
'mergehistory-list'                => '統合できる編集履歴',
'mergehistory-merge'               => '以下の[[:$1]]の履歴が、[[:$2]]へ統合可能です。
特定の時間以前に作成された版のみを統合するには、ラジオボタンで版を選択してください。
案内リンクを使うと、選択が初期化されるので注意してください。',
'mergehistory-go'                  => '統合可能な版の表示',
'mergehistory-submit'              => '版を統合する',
'mergehistory-empty'               => '統合できる版がありません。',
'mergehistory-success'             => '[[:$1]]の$3{{PLURAL:$3|版}}を[[:$2]]へ統合しました。',
'mergehistory-fail'                => '履歴の統合を行実効できません。統合を行うページと時刻の引数を再確認してください。',
'mergehistory-no-source'           => '統合元ページ「$1」が存在しません。',
'mergehistory-no-destination'      => '統合先のページ$1が存在しません。',
'mergehistory-invalid-source'      => '統合元のページは有効な名前でなければなりません。',
'mergehistory-invalid-destination' => '統合先のページは有効な名前でなければなりません。',
'mergehistory-autocomment'         => '[[:$1]]を[[:$2]]に統合',
'mergehistory-comment'             => '[[:$1]]を[[:$2]]に統合：$3',
'mergehistory-same-destination'    => '統合元と統合先に同じページを設定することはできません。',
'mergehistory-reason'              => '理由：',

# Merge log
'mergelog'           => '統合記録',
'pagemerge-logentry' => '[[$1]]を[[$2]]へ統合（$3版まで）',
'revertmerge'        => '統合解除',
'mergelogpagetext'   => '以下は、最近の1つのページ履歴のもう1つのページへの統合一覧です。',

# Diffs
'history-title'           => '「$1」の変更履歴',
'difference'              => '（版間での差分）',
'lineno'                  => '$1行：',
'compareselectedversions' => '選択した版同士を比較',
'visualcomparison'        => '表示結果の比較',
'wikicodecomparison'      => 'ウィキテキストの比較',
'editundo'                => '取り消し',
'diff-multi'              => '({{PLURAL:$1|間の$1版}}分が非表示)',
'diff-movedto'            => '$1へ移動',
'diff-styleadded'         => '$1 のスタイル追加',
'diff-added'              => '$1の追加',
'diff-changedto'          => '$1に変更',
'diff-movedoutof'         => '$1から移動',
'diff-styleremoved'       => '$1のスタイル除去',
'diff-removed'            => '$1の除去',
'diff-changedfrom'        => '$1から変更',
'diff-src'                => '出典',
'diff-withdestination'    => '変更先に$1',
'diff-with'               => '$1 $2 に&#32;',
'diff-with-additional'    => '$1 $2',
'diff-with-final'         => '&#32; および $1 $2',
'diff-width'              => '幅',
'diff-height'             => '高さ',
'diff-p'                  => "'''パラグラフ'''",
'diff-blockquote'         => "'''引用'''",
'diff-h1'                 => "'''見出し (レベル1)'''",
'diff-h2'                 => "'''見出し (レベル2)'''",
'diff-h3'                 => "'''見出し (レベル3)'''",
'diff-h4'                 => "'''見出し (レベル4)'''",
'diff-h5'                 => "'''見出し (レベル5)'''",
'diff-pre'                => "'''未整形の文章群'''",
'diff-div'                => "'''ブロック'''",
'diff-ul'                 => "'''番号なし箇条書き'''",
'diff-ol'                 => "'''番号付き箇条書き'''",
'diff-li'                 => "'''箇条書きの項目'''",
'diff-table'              => "'''表'''",
'diff-tbody'              => "'''表の中身'''",
'diff-tr'                 => "'''列'''",
'diff-td'                 => "'''セル'''",
'diff-th'                 => "'''ヘッダ'''",
'diff-br'                 => "'''改行'''",
'diff-hr'                 => "'''水平線'''",
'diff-code'               => "'''プログラムコード'''",
'diff-dl'                 => "'''定義の箇条書き'''",
'diff-dt'                 => "'''定義語'''",
'diff-dd'                 => "'''定義'''",
'diff-input'              => "'''入力'''",
'diff-form'               => "'''フォーム'''",
'diff-img'                => "'''画像'''",
'diff-span'               => "'''スパン'''",
'diff-a'                  => "'''リンク'''",
'diff-i'                  => "'''斜体'''",
'diff-b'                  => "'''太字'''",
'diff-strong'             => "'''強い強調'''",
'diff-em'                 => "'''弱い強調'''",
'diff-font'               => "'''フォント'''",
'diff-big'                => "'''大きく'''",
'diff-del'                => "'''取り消し線'''",
'diff-tt'                 => "'''固定幅'''",
'diff-sub'                => "'''下付き'''",
'diff-sup'                => "'''上付き'''",
'diff-strike'             => "'''取り消し線'''",

# Search results
'searchresults'                    => '検索結果',
'searchresults-title'              => '「$1」の検索結果',
'searchresulttext'                 => '{{SITENAME}}の検索に関する詳しい情報は、[[{{MediaWiki:Helppage}}|{{int:help}}]]をご覧ください。',
'searchsubtitle'                   => "'''[[:$1]]'''の検索（[[Special:Prefixindex/$1|「$1」から始まるページ]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|「$1」へリンクしている全ページ]]）",
'searchsubtitleinvalid'            => "'''$1'''を検索しました",
'noexactmatch'                     => "'''「$1」という名前のページは存在しません。'''[[:$1|新規作成する]]ことができます。",
'noexactmatch-nocreate'            => "'''「$1」という名前のページは存在しません。'''",
'toomanymatches'                   => '一致したページが多すぎます。他の検索語を指定してください。',
'titlematches'                     => 'ページ名と一致',
'notitlematches'                   => 'ページ名とは一致しませんでした',
'textmatches'                      => 'ページ本文と一致',
'notextmatches'                    => 'ページ本文とは一致しませんでした',
'prevn'                            => '前の$1件',
'nextn'                            => '次の$1件',
'prevn-title'                      => '前の$1結果',
'nextn-title'                      => '次の$1結果',
'shown-title'                      => 'ページあたり$1件の結果を表示',
'viewprevnext'                     => '($1) ($2) ($3) を表示',
'searchmenu-legend'                => '検索オプション',
'searchmenu-exists'                => "'''このウィキには「[[:$1]]」という名前のページがあります'''",
'searchmenu-new'                   => "'''このウィキでページ「[[:$1|$1]]」を新規作成する'''",
'searchhelp-url'                   => 'Help:目次',
'searchmenu-prefix'                => '[[Special:PrefixIndex/$1|この文字列から始まる名前のページを見る]]',
'searchprofile-articles'           => '記事',
'searchprofile-articles-and-proj'  => '記事とプロジェクト文書',
'searchprofile-project'            => 'プロジェクトページ',
'searchprofile-images'             => 'ファイル',
'searchprofile-everything'         => 'すべて',
'searchprofile-advanced'           => '詳細',
'searchprofile-articles-tooltip'   => '$1で検索',
'searchprofile-project-tooltip'    => '$1で検索',
'searchprofile-images-tooltip'     => 'ファイルを検索',
'searchprofile-everything-tooltip' => '全ページ（トークページ含む）を検索',
'searchprofile-advanced-tooltip'   => '特定の名前空間を検索',
'prefs-search-nsdefault'           => '標準の設定で検索:',
'prefs-search-nscustom'            => '指定した名前空間を検索:',
'search-result-size'               => '$1（$2単語）',
'search-result-score'              => '関連度：$1%',
'search-redirect'                  => '（$1を転送）',
'search-section'                   => '（$1の節）',
'search-suggest'                   => 'もしかして：$1',
'search-interwiki-caption'         => '姉妹プロジェクト',
'search-interwiki-default'         => '$1の結果：',
'search-interwiki-more'            => '（続き）',
'search-mwsuggest-enabled'         => '検索候補を表示',
'search-mwsuggest-disabled'        => '検索候補を表示しない',
'search-relatedarticle'            => '関連',
'mwsuggest-disable'                => 'AJAXによる検索候補の提示を無効にする',
'searchrelated'                    => '関連',
'searchall'                        => 'すべて',
'showingresults'                   => "'''$2'''件目からの'''$1'''件を表示しています。",
'showingresultsnum'                => "'''$2'''件目からの'''$3'''件を表示しています。",
'showingresultstotal'              => "'''$3''' 件中 {{PLURAL:$4|'''$1'''|'''$1 - $2'''}}件目の検索結果を表示しています",
'nonefound'                        => "'''注意'''：既定では一部の名前空間しか検索されません。
''all:''つけて全て（トークページやテンプレートなどを含む）を対象にするか、検索したい名前空間を先頭で使用してください。",
'search-nonefound'                 => '問い合わせに合致する結果はありませんでした。',
'powersearch'                      => '高度な検索',
'powersearch-legend'               => '高度な検索',
'powersearch-ns'                   => '名前空間を指定して検索：',
'powersearch-redir'                => '転送を表示',
'powersearch-field'                => '検索対象：',
'search-external'                  => '外部検索',
'searchdisabled'                   => '{{SITENAME}}の検索機能は無効化されています。
代わりにGoogleなどの検索が利用できます。
ただし外部の検索エンジンに蓄積されている{{SITENAME}}の情報は古い場合があります。',

# Preferences page
'preferences'               => '個人設定',
'mypreferences'             => '個人設定',
'prefs-edits'               => '編集回数：',
'prefsnologin'              => 'ログインしていません',
'prefsnologintext'          => '個人設定を変更するためには<span class="plainlinks">[{{fullurl:Special:UserLogin|returnto=$1}} ログイン]</span>する必要があります。',
'prefsreset'                => '個人設定を保存されている状態に復帰しました。',
'qbsettings'                => 'クイックバー',
'qbsettings-none'           => 'なし',
'qbsettings-fixedleft'      => '左端',
'qbsettings-fixedright'     => '右端',
'qbsettings-floatingleft'   => 'ウィンドウの左上に固定',
'qbsettings-floatingright'  => 'ウィンドウの右上に固定',
'changepassword'            => 'パスワードの変更',
'skin'                      => '外装',
'skin-preview'              => 'プレビュー',
'math'                      => '数式',
'dateformat'                => '日付の書式',
'datedefault'               => '選択なし',
'datetime'                  => '日付と時刻',
'math_failure'              => '構文解析失敗',
'math_unknown_error'        => '不明なエラー',
'math_unknown_function'     => '不明な関数',
'math_lexing_error'         => '字句解析エラー',
'math_syntax_error'         => '構文エラー',
'math_image_error'          => 'PNGへの変換に失敗しました。dvipng（もしくはdvipsとgsとconvert）およびlatexが正しくインストールされているか確認してください。',
'math_bad_tmpdir'           => '数式一時ディレクトリーへの書き込みまたは作成ができません',
'math_bad_output'           => '数式一時ディレクトリーへの書き込みまたは作成ができません',
'math_notexvc'              => 'texvc実行可能プログラムが見つかりません。math/READMEを読んで設定してください。',
'prefs-personal'            => '利用者情報',
'prefs-rc'                  => '最近の更新',
'prefs-watchlist'           => 'ウォッチリスト',
'prefs-watchlist-days'      => 'ウォッチリストに表示する日数：',
'prefs-watchlist-days-max'  => '(最大7日間)',
'prefs-watchlist-edits'     => '拡張ウォッチリストに表示する件数：',
'prefs-watchlist-edits-max' => '(最大数: 1000)',
'prefs-misc'                => 'その他',
'prefs-resetpass'           => 'パスワードの変更',
'saveprefs'                 => '保存',
'resetprefs'                => '保存していない変更を破棄',
'restoreprefs'              => '初期設定に戻す',
'textboxsize'               => '編集画面',
'prefs-edit-boxsize'        => '編集ウィンドウのサイズ。',
'rows'                      => '行数：',
'columns'                   => '列数：',
'searchresultshead'         => '検索',
'resultsperpage'            => '1ページあたりの表示件数：',
'contextlines'              => '1件あたりの行数：',
'contextchars'              => '1行あたりの文字数：',
'stub-threshold'            => '<a href="#" class="stub">スタブリンク</a>として表示する閾値（バイト）：',
'recentchangesdays'         => '最近の更新に表示する日数：',
'recentchangesdays-max'     => '(最大$1 {{PLURAL:$1|日|日間}})',
'recentchangescount'        => '{{int:Recentchanges}}、ページ履歴、およびログで表示する既定の件数:',
'savedprefs'                => '個人設定を保存しました。',
'timezonelegend'            => '時間帯：',
'timezonetext'              => '¹サーバーの時刻 (UTC) とあなたの地域の標準時との時差。',
'localtime'                 => '現地時間：',
'timezoneselect'            => 'タイムゾーン:',
'timezoneuseserverdefault'  => 'サーバーの既定を使用',
'timezoneuseoffset'         => 'その他（時差を指定）',
'timezoneoffset'            => '時差¹：',
'servertime'                => 'サーバーの時間：',
'guesstimezone'             => 'ブラウザの設定から入力',
'timezoneregion-africa'     => 'アフリカ',
'timezoneregion-america'    => 'アメリカ',
'timezoneregion-antarctica' => '南極',
'timezoneregion-arctic'     => '北極',
'timezoneregion-asia'       => 'アジア',
'timezoneregion-atlantic'   => '大西洋',
'timezoneregion-australia'  => 'オーストラリア',
'timezoneregion-europe'     => 'ヨーロッパ',
'timezoneregion-indian'     => 'インド洋',
'timezoneregion-pacific'    => '太平洋',
'allowemail'                => '他の利用者からの電子メールの受信を有効化する',
'prefs-searchoptions'       => '検索設定',
'prefs-namespaces'          => '名前空間',
'defaultns'                 => '標準で検索する名前空間:',
'default'                   => '既定',
'files'                     => 'ファイル',
'prefs-custom-css'          => 'カスタムCSS',
'prefs-custom-js'           => 'カスタムJS',

# User rights
'userrights'                  => '利用者権限の管理', # Not used as normal message but as header for the special page itself
'userrights-lookup-user'      => '利用者グループを管理',
'userrights-user-editname'    => '利用者名を入力：',
'editusergroup'               => '利用者グループを編集',
'editinguser'                 => "利用者'''[[User:$1|$1]]'''（[[User talk:$1|{{int:talkpagelinktext}}]]{{int:pipe-separator}}[[Special:Contributions/$1|{{int:contribslink}}]]）の権限を変更",
'userrights-editusergroup'    => '利用者グループを編集',
'saveusergroups'              => '利用者グループを保存',
'userrights-groupsmember'     => '所属グループ：',
'userrights-groups-help'      => 'この利用者が属するグループを変更することができます。
* チェックが入っているボックスは、この利用者がそのグループに属していることを意味します。
* チェックが入っていないボックスは、この利用者がそのグループに属していないことを意味します。
* *は一旦グループへ追加した場合に除去あるいはその逆が不可能であることを示しています。',
'userrights-reason'           => '理由：',
'userrights-no-interwiki'     => '他ウィキ上における利用者権限の編集権限はありません。',
'userrights-nodatabase'       => 'データベース$1は存在しないか、ローカル上にありません。',
'userrights-nologin'          => '利用者権限が割り当てられている管理者権限アカウントで[[Special:UserLogin|ログイン]]する必要があります。',
'userrights-notallowed'       => '利用者権限を変更する権限がありません。',
'userrights-changeable-col'   => '変更可能なグループ',
'userrights-unchangeable-col' => '変更できないグループ',

# Groups
'group'               => 'グループ：',
'group-user'          => '利用者',
'group-autoconfirmed' => '自動承認された利用者',
'group-bot'           => 'ボット',
'group-sysop'         => '管理者',
'group-bureaucrat'    => 'ビューロクラット',
'group-suppress'      => '秘匿者',
'group-all'           => '（全員）',

'group-user-member'          => '利用者',
'group-autoconfirmed-member' => '自動承認された利用者',
'group-bot-member'           => 'ボット',
'group-sysop-member'         => '管理者',
'group-bureaucrat-member'    => 'ビューロクラット',
'group-suppress-member'      => '秘匿者',

'grouppage-user'          => '{{ns:project}}:利用者',
'grouppage-autoconfirmed' => '{{ns:project}}:自動承認された利用者',
'grouppage-bot'           => '{{ns:project}}:ボット',
'grouppage-sysop'         => '{{ns:project}}:管理者',
'grouppage-bureaucrat'    => '{{ns:project}}:ビューロクラット',
'grouppage-suppress'      => '{{ns:project}}:秘匿者',

# Rights
'right-read'                  => 'ページの閲覧',
'right-edit'                  => 'ページの編集',
'right-createpage'            => '（議論ページでない）ページの作成',
'right-createtalk'            => '議論ページの作成',
'right-createaccount'         => '新しい利用者アカウントの作成',
'right-minoredit'             => '細部の編集の印づけ',
'right-move'                  => 'ページの移動',
'right-move-subpages'         => '下位ページを含めたページの移動',
'right-move-rootuserpages'    => '利用者ページ本体の移動',
'right-movefile'              => 'ファイルの移動',
'right-suppressredirect'      => 'ページの移動時に元のページ名からの転送を作成しない',
'right-upload'                => 'ファイルのアップロード',
'right-reupload'              => '存在するファイルの上書き',
'right-reupload-own'          => '自らがアップロードした存在するファイルの上書き',
'right-reupload-shared'       => '共有メディアリポジトリ上のファイルのローカルでの上書き',
'right-upload_by_url'         => 'URLからのファイルのアップロード',
'right-purge'                 => '確認を省略してサイトのキャッシュを破棄',
'right-autoconfirmed'         => '半保護されたページの編集',
'right-bot'                   => '自動処理として認識',
'right-nominornewtalk'        => '議論ページへ細部の編集をしたときに、新しいメッセージのお知らせを表示しない',
'right-apihighlimits'         => 'API要求でより高い制限値の使用',
'right-writeapi'              => '書き込みAPIの使用',
'right-delete'                => 'ページの削除',
'right-bigdelete'             => '大きな履歴のあるページの削除',
'right-deleterevision'        => 'ページの特定の版の削除と復帰',
'right-deletedhistory'        => '関連する本文を除く、削除された履歴項目の閲覧',
'right-browsearchive'         => '削除されたページの検索',
'right-undelete'              => 'ページの復帰',
'right-suppressrevision'      => '管理者から隠された版の確認と復元',
'right-suppressionlog'        => '非公開記録の閲覧',
'right-block'                 => '他の利用者を編集からブロック',
'right-blockemail'            => '電子メール送信から利用者をブロック',
'right-hideuser'              => '利用者名ブロックし、公開記録から隠す',
'right-ipblock-exempt'        => 'IPブロック、自動ブロック、広域ブロックを回避',
'right-proxyunbannable'       => 'プロキシの自動ブロックを回避',
'right-protect'               => '保護レベルの変更と保護されたページの編集',
'right-editprotected'         => '保護ページの編集（連続保護を除く）',
'right-editinterface'         => 'ユーザーインターフェースの編集',
'right-editusercssjs'         => '他利用者のCSSとJavaScriptファイルの編集',
'right-rollback'              => '特定ページを最後に編集した利用者の編集の即時巻き戻し',
'right-markbotedits'          => '巻き戻しをボットの編集として扱う',
'right-noratelimit'           => '速度制限を受けない',
'right-import'                => '他のウィキからのページ取り込み',
'right-importupload'          => 'ファイルアップロードからのページの取り込み',
'right-patrol'                => '他人の編集を巡回済みにする',
'right-autopatrol'            => '自分の編集を自動的に巡回済みにする',
'right-patrolmarks'           => '最近の更新で巡回済み印の閲覧',
'right-unwatchedpages'        => 'ウォッチされていないページ一覧の閲覧',
'right-trackback'             => 'トラックバックの投稿',
'right-mergehistory'          => 'ページ履歴の統合',
'right-userrights'            => '全利用者権限の編集',
'right-userrights-interwiki'  => '他のウィキの利用者の利用者権限の編集',
'right-siteadmin'             => 'データベースのロックおよびロック解除',
'right-reset-passwords'       => '他の利用者のパスワードを再設定する',
'right-override-export-depth' => 'リンク先ページを5階層まで含めて書き出す',

# User rights log
'rightslog'      => '利用者権限変更記録',
'rightslogtext'  => '以下は利用者権限の変更記録です。',
'rightslogentry' => '$1の所属グループを$2から$3へ変更しました',
'rightsnone'     => '（なし）',

# Associated actions - in the sentence "You do not have permission to X"
'action-read'                 => 'このページの閲覧',
'action-edit'                 => 'このページの編集',
'action-createpage'           => 'ページの新規作成',
'action-createtalk'           => 'トークページの新規作成',
'action-createaccount'        => 'このアカウントの作成',
'action-minoredit'            => '細部の編集として印付け',
'action-move'                 => 'このページの移動',
'action-move-subpages'        => 'このページと下位ページの移動',
'action-move-rootuserpages'   => '利用者ページ本体の移動',
'action-movefile'             => 'このファイルの移動',
'action-upload'               => 'このファイルのアップロード',
'action-reupload'             => 'このファイルの上書き',
'action-reupload-shared'      => '共有リポジトリにあるこのファイルの上書き',
'action-upload_by_url'        => 'URLからこのファイルのアップロード',
'action-writeapi'             => '書き込みAPIを使用',
'action-delete'               => 'このページの削除',
'action-deleterevision'       => 'この版の削除',
'action-deletedhistory'       => 'このページの削除履歴の表示',
'action-browsearchive'        => '削除されたページの検索',
'action-undelete'             => 'このページの復帰',
'action-suppressrevision'     => '隠された版の確認と復元',
'action-suppressionlog'       => 'この非公開記録の表示',
'action-block'                => 'この利用者の編集をブロック',
'action-protect'              => 'このページの保護レベルの変更',
'action-import'               => '他のウィキからのこのページの取り込み',
'action-importupload'         => 'ファイルアップロードからこのページの取り込み',
'action-patrol'               => '他の利用者の編集を巡回済みにする',
'action-autopatrol'           => '自身の編集を巡回済みにする',
'action-unwatchedpages'       => 'ウォッチされていないページ一覧の表示',
'action-trackback'            => 'トラックバックの投稿',
'action-mergehistory'         => 'このページの履歴統合',
'action-userrights'           => '全利用者権限の変更',
'action-userrights-interwiki' => '他のウィキ上の利用者の利用者権限変更',
'action-siteadmin'            => 'データベースのロックもしくはロック解除',

# Recent changes
'nchanges'                          => '$1回の変更',
'recentchanges'                     => '最近の更新',
'recentchanges-legend'              => '最近の更新のオプション',
'recentchangestext'                 => 'このページでそのウィキへの最近の更新を追跡。',
'recentchanges-feed-description'    => 'このフィードでそのウィキへの最近の更新を追跡。',
'rcnote'                            => "以下は、$4$5までの{{PLURAL:$2|1日|直前'''$2'''日間}}になされた'''$1'''件の変更です。",
'rcnotefrom'                        => "以下は '''$2''' 以降になされた変更です (最大 '''$1'''件)。",
'rclistfrom'                        => '$1以降の更新を表示する',
'rcshowhideminor'                   => '細部の編集を$1',
'rcshowhidebots'                    => 'ボットの編集を$1',
'rcshowhideliu'                     => 'ログイン利用者の編集を$1',
'rcshowhideanons'                   => '匿名利用者の編集を$1',
'rcshowhidepatr'                    => '巡回された編集を$1',
'rcshowhidemine'                    => '自分の編集を$1',
'rclinks'                           => '最近$2日間の$1件分を表示する<br />$3',
'diff'                              => '差分',
'hist'                              => '履歴',
'hide'                              => '非表示',
'show'                              => '表示',
'minoreditletter'                   => '細',
'newpageletter'                     => '新',
'boteditletter'                     => 'ボ',
'number_of_watching_users_pageview' => '[$1人の利用者がウォッチしています]',
'rc_categories'                     => 'カテゴリを制限（"|"区切り）',
'rc_categories_any'                 => 'すべて',
'newsectionsummary'                 => '/* $1 */ 新しい節',
'rc-enhanced-expand'                => '詳細を表示（JavaScriptが必要）',
'rc-enhanced-hide'                  => '詳細を非表示',

# Recent changes linked
'recentchangeslinked'          => '関連ページの更新状況',
'recentchangeslinked-title'    => '「$1」と関連する変更',
'recentchangeslinked-noresult' => '指定期間中に指定ページのリンク先に変更はありませんでした。',
'recentchangeslinked-summary'  => "これは、指定したページからリンクされている（もしくは、指定したカテゴリに含まれている）ページに最近加えられた変更の一覧です。
[[Special:Watchlist|自分のウォッチリスト]]にあるページは'''太字'''で表示されています。",
'recentchangeslinked-page'     => 'ページ名：',
'recentchangeslinked-to'       => '代わりに、指定したページへのリンク元での変更を表示',

# Upload
'upload'                      => 'ファイルをアップロード',
'uploadbtn'                   => 'ファイルをアップロード',
'reupload'                    => '再アップロード',
'reuploaddesc'                => 'アップロードを中止してアップロードフォームへ戻る',
'uploadnologin'               => 'ログインしていません',
'uploadnologintext'           => 'ファイルをアップロードするには[[Special:UserLogin|ログイン]]する必要があります。',
'upload_directory_missing'    => 'アップロード先ディレクトリー（$1）が見つからず、ウェブサーバーによって作成できませんでした。',
'upload_directory_read_only'  => 'アップロード先ディレクトリー（$1）に、ウェブサーバーが書き込めません。',
'uploaderror'                 => 'アップロードのエラー',
'uploadtext'                  => "ファイルをアップロードするには、以下のフォームを利用してください。
以前にアップロードされたファイルの表示と検索には[[Special:FileList|{{int:listfiles}}]]を使用し、（再）アップロードは[[Special:Log/upload|アップロード記録]]に、削除は[[Special:Log/delete|削除記録]]にも記録されます。

ページにファイルを含めるには、以下の書式のリンクを使用してください：
* '''<tt><nowiki>[[</nowiki>{{ns:file}}:<nowiki>File.jpg]]</nowiki></tt>'''とすると、ファイルが完全なままで使用されます
* '''<tt><nowiki>[[</nowiki>{{ns:file}}:<nowiki>File.png|200px|thumb|left|代替文]]</nowiki></tt>'''とすると、200ピクセルの幅に修正された状態で、左寄せの枠内に、「代替文」が説明として使用されます。
* '''<tt><nowiki>[[</nowiki>{{ns:media}}:<nowiki>File.ogg]]</nowiki></tt>'''とするとファイルを表示せずに直接ファイルへリンクします",
'upload-permitted'            => '許可されているファイル形式：$1。',
'upload-preferred'            => '推奨されているファイル形式：$1。',
'upload-prohibited'           => '禁止されているファイル形式：$1。',
'uploadlog'                   => 'アップロード記録',
'uploadlogpage'               => 'アップロード記録',
'uploadlogpagetext'           => '以下はファイルアップロードの最近の記録です。
画像付きで見るには[[Special:NewFiles|新規ファイルの一覧]]をご覧ください。',
'filename'                    => 'ファイル名',
'filedesc'                    => '概要',
'fileuploadsummary'           => '概要：',
'filereuploadsummary'         => 'ファイルの変更：',
'filestatus'                  => '著作権情報：',
'filesource'                  => '出典：',
'uploadedfiles'               => 'アップロードされたファイル',
'ignorewarning'               => '警告を無視し、ファイルを保存してしまう',
'ignorewarnings'              => '警告を無視',
'minlength1'                  => 'ファイル名は1文字以上である必要があります。',
'illegalfilename'             => 'ファイル名「$1」にページ名として許可されていない文字が含まれています。
ファイル名を変更してからもう一度アップロードしてください。',
'badfilename'                 => 'ファイル名は「$1」へ変更されました。',
'filetype-badmime'            => 'MIMEタイプ「$1」のファイルのアップロードは許可されていません。',
'filetype-bad-ie-mime'        => 'Internet Explorerが、許可されていない潜在的危険性のあるファイル形式「$1」と認識してしまうため、このファイルをアップロードできません。',
'filetype-unwanted-type'      => "'''「.$1」'''は好ましくないファイル形式です。
推奨される{{PLURAL:$3|ファイル形式}}は$2です。",
'filetype-banned-type'        => "'''「.$1」''' は許可されていないファイル形式です。
許可されている{{PLURAL:$3|ファイル形式}}は$2です。",
'filetype-missing'            => 'ファイルに、「.jpg」のような拡張子がありません。',
'large-file'                  => 'ファイルサイズは$1バイトより大きくしないことが推奨されています。
このファイルは$2バイトです。',
'largefileserver'             => 'このファイルは、サーバー設定で許されている最大サイズより大きいです。',
'emptyfile'                   => 'アップロードしたファイルは内容が空のようです。
ファイル名の指定が間違っている可能性があります。
本当にこのファイルをアップロードしたいのか、確認してください。',
'fileexists'                  => "この名前のファイルは既に存在しています。置き換えたいか確信がもてない場合は、'''<tt>[[:$1]]</tt>'''を確認してください。
[[$1|thumb]]",
'filepageexists'              => "このファイルのための説明ページは既に'''<tt>[[:$1]]</tt>'''に作成されていますが、現在、この名前のファイルは存在していません。
入力したファイルの概要は説明ページに反映されません。
新しい概要を反映するに、説明ページを手動で編集する必要があります。
[[$1|thumb]]",
'fileexists-extension'        => "類似した名前のファイルが既に存在しています：[[$2|thumb]]
* アップロード中のファイルの名前：'''<tt>[[:$1]]</tt>'''
* 既存ファイルの名前：'''<tt>[[:$2]]</tt>'''
違う名前を選択してください。",
'fileexists-thumb'            => "<center>'''既存のファイル'''</center>",
'fileexists-thumbnail-yes'    => "このファイルは元の画像から縮小されたもの（サムネイル）のようです。
[[$1|thumb]]
ファイル'''<tt>[[:$1]]</tt>'''を確認してください。
確認したファイルが同じ画像のもとのサイズの版である場合、サムネイルを個別にアップロードする必要はありません。",
'file-thumbnail-no'           => "ファイル名が'''<tt>$1</tt>'''から始まっています。
他の画像から縮小されたもの（サムネイル）のようです。
より高精細な画像をお持ちの場合は、そちらをアップロードしてください。そうでない場合はファイル名を変更してください。",
'fileexists-forbidden'        => 'この名前のファイルは既に存在しており、上書きできません。
アップロードを継続したい場合は、前のページに戻り、別のファイル名を使用してください。
[[File:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => 'この名前のファイルは共有ファイルリポジトリに既に存在しています。
アップロードを継続したい場合は、前のページに戻り、別のファイル名を使用してください。
[[File:$1|thumb|center|$1]]',
'file-exists-duplicate'       => 'このファイルは以下の{{PLURAL:$1|ファイル}}と重複しています：',
'file-deleted-duplicate'      => 'このファイル（[[$1]]）と同一のファイルが以前に削除されています。
再度アップロードをする前に、以前削除されたファイルの削除記録を確認してください。',
'successfulupload'            => 'アップロード成功',
'uploadwarning'               => 'アップロード警告',
'savefile'                    => 'ファイルを保存',
'uploadedimage'               => '「[[$1]]」をアップロードしました。',
'overwroteimage'              => '「[[$1]]」の新しい版をアップロードしました',
'uploaddisabled'              => 'アップロード機能は無効になっています。',
'uploaddisabledtext'          => 'ファイルのアップロードは、無効になっています。',
'php-uploaddisabledtext'      => 'ファイルのアップロードがPHPで無効化されています。
file_uploadsの設定を確認してください。',
'uploadscripted'              => 'このファイルは、ウェブブラウザが誤って解釈してしまうおそれのあるHTMLまたはスクリプトコードを含んでいます。',
'uploadcorrupt'               => '指定したファイルは壊れているか拡張子が正しくありません。ファイルを確認の上再度アップロードをしてください。',
'uploadvirus'                 => 'このファイルにはウイルスが含まれています！
詳細：$1',
'sourcefilename'              => 'アップロード元のファイル名：',
'destfilename'                => 'ファイル名：',
'upload-maxfilesize'          => 'ファイルの最大サイズ：$1',
'watchthisupload'             => 'このファイルをウォッチ',
'filewasdeleted'              => 'この名前のファイルは一度アップロードされ、その後削除されています。
再度アップロードする前に$1を確認してください。',
'upload-wasdeleted'           => "'''警告：過去に削除されたファイルをアップロードしようとしています。'''

このままアップロードを行うことが適切かどうか確認してください。
参考として以下にこのファイルの削除記録を表示しています：",
'filename-bad-prefix'         => "アップロードしようとしているファイルの名前が'''「$1」'''から始まっていますが、これはデジタルカメラによって自動的に付与されるような具体性を欠いた名前です。
ファイルの内容をより具体的に説明する名前を使用してください。",
'filename-prefix-blacklist'   => ' #<!-- この行はそのままにしておいてください --> <pre>
# 構文は以下:
#   * "#" 記号から行末まではすべてがコメント
#   * 空でないすべての行はデジタルカメラによって自動的に付けられる典型的なファイル名の接頭辞
CIMG # カシオ
DSC_ # ニコン
DSCF # 富士フイルム
DSCN # ニコン
DUW # 一部の携帯電話
IMG # 一般
JD # Jenoptik
MGP # ペンタックス
PICT # その他
 #</pre> <!-- この行はそのままにしておいてください -->',

'upload-proto-error'      => '不正なプロトコル',
'upload-proto-error-text' => '外部アップロード機能では、<code>http://</code>か<code>ftp://</code>で始まっているURLが必要があります。',
'upload-file-error'       => '内部エラー',
'upload-file-error-text'  => '内部エラーのため、サーバー上の一時ファイル作成に失敗しました。
[[Special:ListUsers/sysop|管理者]]に連絡してください。',
'upload-misc-error'       => '不明なアップロードのエラー',
'upload-misc-error-text'  => 'アップロード時に不明なエラーが発生しました。
指定したURLがアクセス可能で有効なものであるかを再度確認してください。
それでもこのエラーが発生する場合は、[[Special:ListUsers/sysop|管理者]]に連絡してください。',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6'       => 'URLに到達できませんでした',
'upload-curl-error6-text'  => '指定したURLに到達できませんでした。
URLが正しいものであるか、指定したサイトが現在使用可能かを再度確認してください。',
'upload-curl-error28'      => 'アップロードのタイムアウト',
'upload-curl-error28-text' => 'サイトからの応答に時間がかかりすぎています。
指定したサイトが現在使用可能かを確認した上で、しばらく待ってもう一度お試しください。
混雑していない時間帯に実行することを推奨します。',

'license'            => 'ライセンス：',
'nolicense'          => '選択なし',
'license-nopreview'  => '（プレビューはありません）',
'upload_source_url'  => '（有効かつ一般に公開されているURL）',
'upload_source_file' => '（自身のコンピューター上のファイル）',

# Special:ListFiles
'listfiles-summary'     => 'この特別ページでは、アップロードされたすべてのファイルを表示します。
既定では一番最近にアップロードされたファイルが一覧の上部に表示されていまます。
各列のヘッダ部分をクリックすると、並び順を変更できます。',
'listfiles_search_for'  => 'メディア名で検索：',
'imgfile'               => 'ファイル',
'listfiles'             => 'ファイル一覧',
'listfiles_date'        => '日時',
'listfiles_name'        => '名前',
'listfiles_user'        => '利用者',
'listfiles_size'        => 'サイズ',
'listfiles_description' => '概要',
'listfiles_count'       => '版数',

# File description page
'filehist'                  => 'ファイルの履歴',
'filehist-help'             => '過去の版のファイルを表示するには、表示したい版の日付/時刻をクリックしてください。',
'filehist-deleteall'        => 'すべて削除',
'filehist-deleteone'        => '削除',
'filehist-revert'           => '差し戻す',
'filehist-current'          => '現在の版',
'filehist-datetime'         => '日付/時刻',
'filehist-thumb'            => 'サムネイル',
'filehist-thumbtext'        => '$1時点における版のサムネイル',
'filehist-nothumb'          => 'サムネイルなし',
'filehist-user'             => '利用者',
'filehist-dimensions'       => '解像度',
'filehist-filesize'         => 'ファイルサイズ',
'filehist-comment'          => 'コメント',
'imagelinks'                => 'ファイルリンク',
'linkstoimage'              => '以下の{{PLURAL:$1|ページ|$1ページ}}が、このファイルへリンクしています：',
'linkstoimage-more'         => '$1より多いページが、このファイルにリンクしています。
以下の一覧は、このファイルにリンクしている最初の$1ページのみを表示しています。
[[Special:WhatLinksHere/$2|完全な一覧]]も参照してください。',
'nolinkstoimage'            => 'このファイルへリンクしているページはありません。',
'morelinkstoimage'          => 'このファイルへの[[Special:WhatLinksHere/$1|リンク元を更に]]を表示する。',
'redirectstofile'           => '以下の{{PLURAL:$1|ファイル|$1ファイル}}が、このファイルへの転送になっています：',
'duplicatesoffile'          => '以下の$1ファイルが、このファイルと内容が同一です（[[Special:FileDuplicateSearch/$2|詳細]]）：',
'sharedupload'              => 'このファイルは$1のものであり、他のプロジェクトで使用されている可能性があります。', # $1 is the repo name, $2 is shareduploadwiki(-desc)
'shareduploadwiki'          => '詳しい情報は$1を参照してください。',
'shareduploadwiki-desc'     => 'この$1にある、ファイルの説明は以下の通りです。',
'shareduploadwiki-linktext' => 'ファイルの詳細ページ',
'noimage'                   => '同名のファイルは存在しません。「$1」リンクをクリックしてください。',
'noimage-linktext'          => 'このファイル名でアップロードする',
'uploadnewversion-linktext' => 'このファイルの新しい版をアップロードする',
'shared-repo-from'          => '$1より', # $1 is the repository name
'shared-repo'               => '共有リポジトリ', # used when shared-repo-NAME does not exist

# File reversion
'filerevert'                => '$1を差し戻す',
'filerevert-legend'         => 'ファイルを差し戻す',
'filerevert-intro'          => "ファイル'''[[Media:$1|$1]]'''を[$4 $2$3版]に差し戻そうとしています。",
'filerevert-comment'        => '理由：',
'filerevert-defaultcomment' => '$1$2の版へ差し戻し',
'filerevert-submit'         => '差し戻す',
'filerevert-success'        => "'''[[Media:$1|$1]]'''は[$4  $2$3の版]に差し戻されました。",
'filerevert-badversion'     => 'このファイルに指定された時刻印を持つ過去の版はありません。',

# File deletion
'filedelete'                  => '$1の削除',
'filedelete-legend'           => 'ファイルの削除',
'filedelete-intro'            => "'''[[Media:$1|$1]]'''をすべての履歴とともに削除しようとしています。",
'filedelete-intro-old'        => "'''[[Media:$1|$1]]'''の[$4 $2$3の版]を削除しようとしています。",
'filedelete-comment'          => '理由：',
'filedelete-submit'           => '削除',
'filedelete-success'          => "'''$1''' は削除されました。",
'filedelete-success-old'      => "'''[[Media:$1|$1]]'''の$2$3の版は削除されています。",
'filedelete-nofile'           => "'''$1'''は存在しません。",
'filedelete-nofile-old'       => "指定された属性を持つ'''$1'''の古い版は存在しません。",
'filedelete-otherreason'      => '他の、または追加の理由：',
'filedelete-reason-otherlist' => 'その他の理由',
'filedelete-reason-dropdown'  => '*よくある削除理由
** 著作権侵害
** 重複ファイル',
'filedelete-edit-reasonlist'  => '削除理由を編集する',

# MIME search
'mimesearch'         => 'MIMEタイプ検索',
'mimesearch-summary' => '指定したMIMEタイプに合致するファイルを検索します。
contenttype/subtypeの形式で指定してください（例：<tt>image/jpeg</tt>）。',
'mimetype'           => 'MIMEタイプ：',
'download'           => 'ダウンロード',

# Unwatched pages
'unwatchedpages' => 'ウォッチされていないページ',

# List redirects
'listredirects' => 'リダイレクトの一覧',

# Unused templates
'unusedtemplates'     => '使われていないテンプレート',
'unusedtemplatestext' => 'このページでは{{ns:template}}名前空間にあって他のページに読み込まれていないページを一覧にしています。
削除する前にリンク元で他のリンクがないか確認してください。',
'unusedtemplateswlh'  => 'リンク元',

# Random page
'randompage'         => 'おまかせ表示',
'randompage-nopages' => '「$1」名前空間には、ページがありません。',

# Random redirect
'randomredirect'         => 'おまかせリダイレクト',
'randomredirect-nopages' => '「$1」名前空間には、転送がありません。',

# Statistics
'statistics'                   => '統計',
'statistics-header-pages'      => 'ページに関する統計',
'statistics-header-edits'      => '編集に関する統計',
'statistics-header-views'      => '閲覧に関する統計',
'statistics-header-users'      => '利用者に関する統計',
'statistics-articles'          => '記事数',
'statistics-pages'             => '総ページ数',
'statistics-pages-desc'        => 'このウィキ内のすべてのページです（トークページや転送などを含む）。',
'statistics-files'             => 'アップロードされたファイル数',
'statistics-edits'             => '{{SITENAME}}が立ち上がってからの編集回数の総計',
'statistics-edits-average'     => '1ページあたりの編集回数',
'statistics-views-total'       => '総閲覧回数',
'statistics-views-peredit'     => '閲覧回数に対する編集回数の割合',
'statistics-jobqueue'          => '[http://www.mediawiki.org/wiki/Manual:Job_queue ジョブ・キュー]の長さ',
'statistics-users'             => '[[Special:ListUsers|利用者]]',
'statistics-users-active'      => '活動中の利用者',
'statistics-users-active-desc' => '過去$1{{PLURAL:$1|日間}}に何らかの操作を行った利用者',
'statistics-mostpopular'       => '最も閲覧されているページ',

'disambiguations'      => '曖昧さ回避ページ',
'disambiguationspage'  => 'Template:曖昧回避',
'disambiguations-text' => "以下のページは'''曖昧さ回避ページ'''へリンクしています。
これらのページは、より適した主題のページへリンクされるべきです。<br />
[[MediaWiki:Disambiguationspage]]からリンクされたテンプレートを使用しているページは、曖昧さ回避ページと見なされます。",

'doubleredirects'            => '二重転送',
'doubleredirectstext'        => 'これは他のリダイレクトページにリダイレクトしているページの一覧です。各行は始点のリダイレクトとそのリダイレクト先ページ、および、そのまたリダイレクトしている先のページを含んでいます。3つ目のページがたいていは「真の」リダイレクト先であり、1つ目のリダイレクトはそこを直接指すべきです。<s>打ち消し線</s>のはいった項目は既に修正されています。',
'double-redirect-fixed-move' => '[[$1]]が移動されています。
[[$2]]に転送されます。',
'double-redirect-fixer'      => '転送修正係',

'brokenredirects'        => '迷子のリダイレクト',
'brokenredirectstext'    => '以下の転送は、存在しないページにリンクしています：',
'brokenredirects-edit'   => '(編集)',
'brokenredirects-delete' => '(削除)',

'withoutinterwiki'         => '言語間リンクを持たないページ',
'withoutinterwiki-summary' => '以下のページには他の言語版へのリンクがありません。',
'withoutinterwiki-legend'  => '先頭文字列',
'withoutinterwiki-submit'  => '表示',

'fewestrevisions' => '編集履歴の少ないページ',

# Miscellaneous special pages
'nbytes'                  => '$1バイト',
'ncategories'             => '$1カテゴリ',
'nlinks'                  => '$1個のリンク',
'nmembers'                => '$1項目',
'nrevisions'              => '$1版',
'nviews'                  => '$1回の閲覧',
'specialpage-empty'       => '合致するものがありません。',
'lonelypages'             => '孤立しているページ',
'lonelypagestext'         => '以下のページは、{{SITENAME}}の他のページからリンクも参照読み込みもされていません。',
'uncategorizedpages'      => 'カテゴリ分類されていないページ',
'uncategorizedcategories' => 'カテゴリ分類されていないカテゴリ',
'uncategorizedimages'     => 'カテゴリ分類されていないファイル',
'uncategorizedtemplates'  => 'カテゴリ分類されていないテンプレート',
'unusedcategories'        => '使われていないカテゴリ',
'unusedimages'            => '使われていないファイル',
'popularpages'            => '人気のページ',
'wantedcategories'        => '望まれているカテゴリ',
'wantedpages'             => '望まれているページ',
'wantedpages-badtitle'    => '結果に不正なページ名が含まれています：$1',
'wantedfiles'             => '望まれているファイル',
'wantedtemplates'         => '望まれているテンプレート',
'mostlinked'              => '被リンク数の多いページ',
'mostlinkedcategories'    => '被リンク数の多いカテゴリ',
'mostlinkedtemplates'     => '使用箇所の多いテンプレート',
'mostcategories'          => 'カテゴリの多いページ',
'mostimages'              => '被リンク数の多いファイル',
'mostrevisions'           => '版の多いページ',
'prefixindex'             => '先頭が同じ全ページ',
'shortpages'              => '短いページ',
'longpages'               => '長いページ',
'deadendpages'            => '行き止まりページ',
'deadendpagestext'        => '以下のページは、{{SITENAME}}の他のページにリンクしていません。',
'protectedpages'          => '保護されているページ',
'protectedpages-indef'    => '無期限保護のみ',
'protectedpages-cascade'  => '連続保護のみ',
'protectedpagestext'      => '以下のページは移動や編集が禁止されています',
'protectedpagesempty'     => '指定した条件で保護中のページは現在ありません。',
'protectedtitles'         => '作成保護されているページ名',
'protectedtitlestext'     => '以下のページは新規作成が禁止されています',
'protectedtitlesempty'    => 'これらの引数で現在保護されているページはありません。',
'listusers'               => '利用者の一覧',
'listusers-editsonly'     => '投稿記録のある利用者のみを表示',
'listusers-creationsort'  => '作成日順に整列',
'usereditcount'           => '$1回の編集',
'usercreated'             => '$1$2に作成',
'newpages'                => '新しいページ',
'newpages-username'       => '利用者名：',
'ancientpages'            => '最古のページ',
'move'                    => '移動',
'movethispage'            => 'このページを移動',
'unusedimagestext'        => '以下のファイルは存在していますが、どのページにも埋め込まれていません。
ただし、他のウェブサイトが直接URLでファイルにリンクしている可能性があり、以下のファイル一覧には、そのような形で利用されているファイルが含まれているかもしれないことに注意してください。',
'unusedcategoriestext'    => '以下のカテゴリはページが存在しますが、他のどのページおよびカテゴリでも使われていません。',
'notargettitle'           => '対象が存在しません',
'notargettext'            => 'この機能の実行対象となるページまたは利用者が指定されていません。',
'nopagetitle'             => 'そのようなページはありません',
'nopagetext'              => '指定したページは存在しません。',
'pager-newer-n'           => '以後の$1件',
'pager-older-n'           => '以前の$1件',
'suppress'                => '秘匿する',

# Book sources
'booksources'               => '書籍情報源',
'booksources-search-legend' => '書籍情報源を検索',
'booksources-go'            => '検索',
'booksources-text'          => '以下は、新古本を販売している外部サイトへのリンクの一覧で、検索中の本について、更に詳しい情報が提供されているかもしれません：',
'booksources-invalid-isbn'  => '指定されたISBN番号は有効ではないようです。参照している情報源から写し間違えていませんか。',

# Special:Log
'specialloguserlabel'  => '利用者名：',
'speciallogtitlelabel' => 'ページ名：',
'log'                  => '記録',
'all-logs-page'        => 'すべての公開記録',
'alllogstext'          => '{{SITENAME}}の取得可能な記録がまとめて表示されています。
記録の種類、実行した利用者（大文字小文字は区別）、影響を受けたページ（大文字小文字は区別）による絞り込みができます。',
'logempty'             => '該当する記録がみつかりませんでした。',
'log-title-wildcard'   => 'この文字列で始まるページ名を検索する',

# Special:AllPages
'allpages'          => '全ページ',
'alphaindexline'    => '$1から$2まで',
'nextpage'          => '次のページ（$1）',
'prevpage'          => '前のページ（$1）',
'allpagesfrom'      => '最初に表示するページ：',
'allpagesto'        => '最後に表示するページ：',
'allarticles'       => '全ページ',
'allinnamespace'    => '全ページ（$1名前空間）',
'allnotinnamespace' => '全ページ（$1名前空間を除く）',
'allpagesprev'      => '前へ',
'allpagesnext'      => '次へ',
'allpagessubmit'    => '表示',
'allpagesprefix'    => '次の文字列から始まるページを表示：',
'allpagesbadtitle'  => '指定したページ名は無効か、言語間またはウィキ間接頭辞を含んでいます。
ページ名に1つ以上の使用できない文字が含まれている可能性があります。',
'allpages-bad-ns'   => '{{SITENAME}}に「$1」という名前空間はありません。',

# Special:Categories
'categories'                    => 'カテゴリ',
'categoriespagetext'            => '以下のカテゴリにはページまたはメディアが存在します。[[Special:UnusedCategories|未使用のカテゴリ]]はここには表示されていません。[[Special:WantedCategories|カテゴリページが存在しないカテゴリ]]も参照してください。',
'categoriesfrom'                => '最初に表示するカテゴリ：',
'special-categories-sort-count' => '項目数順',
'special-categories-sort-abc'   => 'アルファベット順',

# Special:DeletedContributions
'deletedcontributions'       => '利用者の削除された投稿',
'deletedcontributions-title' => '利用者の削除された投稿',

# Special:LinkSearch
'linksearch'       => '外部リンク',
'linksearch-pat'   => '検索パターン：',
'linksearch-ns'    => '名前空間：',
'linksearch-ok'    => '検索',
'linksearch-text'  => '"*.wikipedia.org" のようにワイルドカードを使うことができます。<br />
対応プロトコル：<tt>$1</tt>',
'linksearch-line'  => '$1 が $2 からリンクされています',
'linksearch-error' => 'ワイルドカードはホスト名の先頭でのみ使用できます。',

# Special:ListUsers
'listusersfrom'      => '最初に表示する利用者：',
'listusers-submit'   => '表示',
'listusers-noresult' => '利用者が見つかりませんでした。',

# Special:Log/newusers
'newuserlogpage'              => 'アカウント作成記録',
'newuserlogpagetext'          => '以下はアカウント作成の記録です。',
'newuserlog-byemail'          => 'パスワードを電子メールで送信しました',
'newuserlog-create-entry'     => '新規利用者アカウント',
'newuserlog-create2-entry'    => 'が新規にアカウント $1 を作成しました',
'newuserlog-autocreate-entry' => 'アカウントが自動的に作成されました',

# Special:ListGroupRights
'listgrouprights'                 => '利用者グループの権限',
'listgrouprights-summary'         => '以下は、このウィキに登録されている利用者グループと、それぞれに割り当てられている権限の一覧です。
個々の権限に関する更なる情報は[[{{MediaWiki:Listgrouprights-helppage}}|追加情報]]を見てください。',
'listgrouprights-group'           => 'グループ',
'listgrouprights-rights'          => '権限',
'listgrouprights-helppage'        => 'Help:グループ権限',
'listgrouprights-members'         => '（該当者一覧）',
'listgrouprights-addgroup'        => '{{PLURAL:$2|グループ}}を追加：$1',
'listgrouprights-removegroup'     => '{{PLURAL:$2|グループ}}を除去：$1',
'listgrouprights-addgroup-all'    => '全グループ追加可能',
'listgrouprights-removegroup-all' => '全グループ除去可能',

# E-mail user
'mailnologin'      => '送信アドレスがありません',
'mailnologintext'  => '他の利用者宛にメールを送信するためには、[[Special:UserLogin|ログイン]]し、[[Special:Preferences|個人設定]]で有効なメールアドレスを設定する必要があります。',
'emailuser'        => 'この利用者にメールを送信',
'emailpage'        => '利用者にメール送信',
'emailpagetext'    => '下のフォームを通じて、この利用者にメールを送ることができます。
[[Special:Preferences|利用者の個人設定]]で登録した電子メールアドレスが「差出人」アドレスとして表示され、受信者は返事を直接出せるようになっています。',
'usermailererror'  => 'メールが以下のエラーを返しました：',
'defemailsubject'  => '{{SITENAME}} 電子メール',
'noemailtitle'     => 'メールアドレスがありません',
'noemailtext'      => 'この利用者は有効なメールアドレスを登録していません。',
'nowikiemailtitle' => '電子メール不許可',
'nowikiemailtext'  => 'この利用者は他の利用者からメールを受け取らない設定にしています。',
'email-legend'     => '{{SITENAME}}の他の利用者に電子メールを送る',
'emailfrom'        => '差出人：',
'emailto'          => '宛先：',
'emailsubject'     => '件名：',
'emailmessage'     => '本文：',
'emailsend'        => '送信',
'emailccme'        => '自分宛に控えを送信する。',
'emailccsubject'   => '$1に送信したメールの控え：$2',
'emailsent'        => 'メールを送りました',
'emailsenttext'    => 'メールは無事送信されました。',
'emailuserfooter'  => 'この電子メールは$1から$2へ、{{SITENAME}}の「利用者へメールを送信」機能を使って送られました。',

# Watchlist
'watchlist'            => 'ウォッチリスト',
'mywatchlist'          => 'ウォッチリスト',
'watchlistfor'         => "(利用者: '''$1''')",
'nowatchlist'          => 'ウォッチリストに項目がありません。',
'watchlistanontext'    => 'ウォッチリストに入っている項目を表示または編集するには、$1してください。',
'watchnologin'         => 'ログインしていません',
'watchnologintext'     => 'ウォッチリストを変更するためには、[[Special:UserLogin|ログイン]]している必要があります。',
'addedwatch'           => 'ウォッチリストに追加しました',
'addedwatchtext'       => "ページ 「[[:$1]]」を[[Special:Watchlist|ウォッチリスト]]に追加しました。
このページと付属のトークページに変更があった際には、ウォッチリストに表示されます。また、ウォッチリストに登録されているページは[[Special:RecentChanges|最近の更新の一覧]]に'''太字'''で表示され、見つけやすくなります。",
'removedwatch'         => 'ウォッチリストから除去しました',
'removedwatchtext'     => 'ページ「[[:$1]]」を[[Special:Watchlist|ウォッチリスト]]から除去しました。',
'watch'                => 'ウォッチ',
'watchthispage'        => 'このページをウォッチする',
'unwatch'              => 'ウォッチしない',
'unwatchthispage'      => 'ウォッチをやめる',
'notanarticle'         => '記事ではありません',
'notvisiblerev'        => '別の利用者による最終版は削除されました',
'watchnochange'        => 'ウォッチリストに登録しているページで、指定期間内に編集されたものはありません。',
'watchlist-details'    => 'ウォッチリストには$1ページが登録されています（トークページは数えません）。',
'wlheader-enotif'      => '* メール通知が有効になっています',
'wlheader-showupdated' => "* 最後に訪問したあとに変更されたページは、'''太字'''で表示されます",
'watchmethod-recent'   => 'ウォッチしているページの最近の編集を確認中',
'watchmethod-list'     => '最近の編集内のウォッチしているページを確認中',
'watchlistcontains'    => 'ウォッチリストには、$1ページが登録されています。',
'iteminvalidname'      => '項目「$1」は問題があります、名前が不正です・・・',
'wlnote'               => "以下は最近'''$2'''時間における、最も新しい'''$1'''編集です。",
'wlshowlast'           => '次の期間で表示：$1時間、$2日間、$3',
'watchlist-options'    => 'ウォッチリストのオプション',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'ウォッチリストに追加しています・・・',
'unwatching' => 'ウォッチリストから除去しています・・・',

'enotif_mailer'                => '{{SITENAME}} 通知メール',
'enotif_reset'                 => 'すべてのページを訪問済みにする',
'enotif_newpagetext'           => 'これは新しいページです。',
'enotif_impersonal_salutation' => '{{SITENAME}} 利用者',
'changed'                      => '変更',
'created'                      => '作成',
'enotif_subject'               => '{{SITENAME}}のページ「$PAGETITLE」が$PAGEEDITORによって$CHANGEDORCREATEDされました',
'enotif_lastvisited'           => '最後に閲覧した後に行なわれた全てのの変更は、$1で見ることができます。',
'enotif_lastdiff'              => 'この変更内容を表示するには$1をご覧ください。',
'enotif_anon_editor'           => '匿名利用者：$1',
'enotif_body'                  => '$WATCHINGUSERNAMEさん、

{{SITENAME}}のページ $PAGETITLE が $PAGEEDITDATE に
$PAGEEDITOR によって$CHANGEDORCREATEDされました。
現在の版を見るには次のURLにアクセスしてください:
$PAGETITLE_URL
$NEWPAGE

編集内容の要約: $PAGESUMMARY ($PAGEMINOREDIT)

投稿者:
メール: $PAGEEDITOR_EMAIL
ウィキ: $PAGEEDITOR_WIKI

あなたがこのページを訪れない限り、これ以上の通知は送信されません。
ウォッチリストからすべての通知フラグをリセットすることもできます。

                         {{SITENAME}} 通知システム

--
ウォッチリストの設定を変更する:
{{fullurl:Special:Watchlist/edit}}

助けが必要ですか:
{{fullurl:Help:Contents}}',

# Delete
'deletepage'             => 'ページを削除',
'confirm'                => '確認',
'excontent'              => '内容：「$1」',
'excontentauthor'        => '内容：「$1」（投稿者は「[[Special:Contributions/$2|$2]]」のみ）',
'exbeforeblank'          => '白紙化前の内容：「$1」',
'exblank'                => '白紙ページ',
'delete-confirm'         => '「$1」の削除',
'delete-legend'          => '削除',
'historywarning'         => '警告: 削除しようとしているページには履歴があります:',
'confirmdeletetext'      => 'ページをすべての履歴とともに削除しようとしています。
本当にこの操作を行いたいか、操作の結果を理解しているか、およびこの操作が[[{{MediaWiki:Policy-url}}|方針]]に従っているかどうか、確認をしてください。',
'actioncomplete'         => '完了しました',
'deletedtext'            => '「<nowiki>$1</nowiki>」は削除されました。
最近の削除に関しては、$2を参照してください。',
'deletedarticle'         => '「[[$1]]」を削除しました',
'suppressedarticle'      => '「[[$1]]」を隠蔽しました',
'dellogpage'             => '削除記録',
'dellogpagetext'         => '以下は、最近の削除と復帰の一覧です。',
'deletionlog'            => '削除記録',
'reverted'               => '以前の版への差し戻し',
'deletecomment'          => '理由：',
'deleteotherreason'      => '他の、または追加の理由：',
'deletereasonotherlist'  => 'その他の理由',
'deletereason-dropdown'  => '*よくある削除理由
** 投稿者依頼
** 著作権侵害
** 荒らし',
'delete-edit-reasonlist' => '削除理由を編集する',
'delete-toobig'          => 'このページには、$1版より多い編集履歴があります。
このようなページの削除は、{{SITENAME}}の偶発的な問題を避けるため、制限されています。',
'delete-warning-toobig'  => 'このページには、 $1版より多い編集履歴があります。
削除すると、{{SITENAME}}のデータベース処理に大きな負荷がかかります。
十分に注意してください。',

# Rollback
'rollback'         => '編集を巻き戻し',
'rollback_short'   => '巻き戻し',
'rollbacklink'     => '巻き戻し',
'rollbackfailed'   => '巻き戻しに失敗しました',
'cantrollback'     => '編集を差し戻せません。
最後の投稿者が、このページの唯一の作者です。',
'alreadyrolled'    => 'ページ[[:$1]]の[[User:$2|$2]]（[[User talk:$2|トーク]]{{int:pipe-separator}}[[Special:Contributions/$2|{{int:contribslink}}]]）による編集をまきもどせません。
他の利用者が、すでに編集あるいは巻き戻しました。

このページの最後の編集は[[User:$3|$3]]（[[User talk:$3|トーク]]{{int:pipe-separator}}[[Special:Contributions/$3|{{int:contribslink}}]]）によるものです。',
'editcomment'      => "編集内容の要約：「''$1''」", # only shown if there is an edit comment
'revertpage'       => '[[Special:Contributions/$2|$2]]（[[User talk:$2|トーク]]）による編集を[[User:$1|$1]]による直前の版へ差し戻しました', # Additionally available: $3: revid of the revision reverted to, $4: timestamp of the revision reverted to, $5: revid of the revision reverted from, $6: timestamp of the revision reverted from
'rollback-success' => '$1による編集を差し戻しました。
$2による最後の版へ変更されました。',
'sessionfailure'   => 'ログインのセッションに問題が発生しました。
セッション乗っ取りを防ぐために操作は取り消されました。
前のページへ戻って再度読み込んだ後に、もう一度試してください。',

# Protect
'protectlogpage'              => '保護記録',
'protectlogtext'              => '以下はページの保護の保護解除の記録です。
現在、保護レベルを変更できるページについては[[Special:ProtectedPages|保護ページ一覧]]を参照してください。',
'protectedarticle'            => '「[[$1]]」を保護しました',
'modifiedarticleprotection'   => '「[[$1]]」の保護レベルを変更しました',
'unprotectedarticle'          => '「[[$1]]」の保護を解除しました',
'movedarticleprotection'      => '保護の設定を「[[$2]]」から「[[$1]]」へ移動しました',
'protect-title'               => '「$1」の保護レベルを変更',
'prot_1movedto2'              => '[[$1]] を [[$2]] へ移動',
'protect-legend'              => '保護の確認',
'protectcomment'              => '理由：',
'protectexpiry'               => '有効期限：',
'protect_expiry_invalid'      => '有効期間が不正です。',
'protect_expiry_old'          => '有効期限が過去の時刻です。',
'protect-unchain'             => '移動権限を操作',
'protect-text'                => "ページ「'''<nowiki>$1</nowiki>'''」に対する保護レベルの表示と操作ができます。",
'protect-locked-blocked'      => "ブロック中は、保護レベルを変更できません。
ページ'''$1'''の現在の状態は以下の通りです：",
'protect-locked-dblock'       => "使用中のでデータベースが現在ロックされているため、保護レベルを変更できません。
ページ'''$1'''の現在の状態は以下の通りです：",
'protect-locked-access'       => "アカウントに、ページの保護レベルを変更する権限がありません。
ページ'''$1'''の現在の状態は以下の通りです：",
'protect-cascadeon'           => 'このページは現在、連続保護が有効になっている以下の{{PLURAL:$1|ページ}}から読み込まれているため、保護されています。
このページの保護制限を変更することは可能ですが、連続保護には影響しません。',
'protect-default'             => 'すべての利用者を許可',
'protect-fallback'            => '「$1」権限が必要',
'protect-level-autoconfirmed' => '新規利用者と匿名利用者を禁止',
'protect-level-sysop'         => '管理者のみ',
'protect-summary-cascade'     => '連続',
'protect-expiring'            => '$1(UTC)で自動的に解除',
'protect-expiry-indefinite'   => '無期限',
'protect-cascade'             => 'このページに読み込まれているページを保護する（連続保護）',
'protect-cantedit'            => 'このページの編集権限がないため、保護レベルを変更できません。',
'protect-othertime'           => 'その他の期間：',
'protect-othertime-op'        => 'その他の期間',
'protect-existing-expiry'     => '現在の保護期限: $2 $3',
'protect-otherreason'         => '他の、または追加の理由：',
'protect-otherreason-op'      => 'その他の理由',
'protect-dropdown'            => '*よくある保護理由
** 度重なる荒らし
** 度重なるスパム投稿
** 非生産的な編集合戦
** 高負荷ページ',
'protect-edit-reasonlist'     => '保護理由を編集する',
'protect-expiry-options'      => '1時間:1 hour,1日:1 day,1週間:1 week,2週間:2 weeks,1か月:1 month,3か月:3 months,6か月:6 months,1年:1 year,無期限:infinite', # display1:time1,display2:time2,...
'restriction-type'            => '許可：',
'restriction-level'           => '制限レベル：',
'minimum-size'                => '最小サイズ',
'maximum-size'                => '最大サイズ：',
'pagesize'                    => '（バイト）',

# Restrictions (nouns)
'restriction-edit'   => '編集',
'restriction-move'   => '移動',
'restriction-create' => '作成',
'restriction-upload' => 'アップロード',

# Restriction levels
'restriction-level-sysop'         => '全保護',
'restriction-level-autoconfirmed' => '半保護',
'restriction-level-all'           => '任意のレベル',

# Undelete
'undelete'                     => '削除されたページを表示',
'undeletepage'                 => '削除されたページの表示と復元',
'undeletepagetitle'            => "'''以下は、[[:$1]]の削除された版です'''。",
'viewdeletedpage'              => '削除されたページを表示',
'undeletepagetext'             => '以下の{{PLURAL:$1|ページ}}は削除されていますが、保存版に残っているため、復元できます。
保存版は定期的に消去される可能性があります。',
'undelete-fieldset-title'      => '削除された版の復元',
'undeleteextrahelp'            => "すべての版を復元する場合は、チェックボックスをどれも選択していない状態で'''''{{int:undeletebtn}}'''''をクリックしてください。
特定の版を復帰する場合は、復帰する版のチェックボックスを選択した状態で'''''{{int:undeletebtn}}'''''をクリックしてください。
'''''{{int:undeletereset}}'''''をクリックすると、コメント欄と全てのチェックボックスが消去されます。",
'undeleterevisions'            => '$1版が保存されています',
'undeletehistory'              => 'ページの復帰を行うと、すべての特定版が履歴に復帰します。ページが削除された後に、同じ名前で新しいページが作成されていた場合、復帰した特定版は、その前の履歴として出現します。',
'undeleterevdel'               => '復帰した結果、版指定削除されているページまたはファイルの版が最新となる場合、復帰は実行されません。
このような場合、版指定削除されていない版が最新となるようにチェックするか、その版の版指定削除を解除する必要があります。',
'undeletehistorynoadmin'       => 'このページは削除されています。
以下に、削除前にこのページを編集していた利用者の詳細情報と共に、この削除の理由が示されています。
削除された各版の本文は管理者のみが使用可能です。',
'undelete-revision'            => '$3によるページ$1の$4$5の削除版：',
'undeleterevision-missing'     => '不正な、あるいは存在しない版です。
間違ったリンクを辿ったか、この版は既に復帰されたか、もしくは保存版から除去された可能性があります。',
'undelete-nodiff'              => 'これより前の版はありません。',
'undeletebtn'                  => '復元',
'undeletelink'                 => '閲覧/復元',
'undeletereset'                => 'リセット',
'undeleteinvert'               => '選択を反転',
'undeletecomment'              => '理由：',
'undeletedarticle'             => '「[[$1]]」を復元しました',
'undeletedrevisions'           => '$1版を復元しました',
'undeletedrevisions-files'     => '$1版と$2ファイルを復元しました',
'undeletedfiles'               => '$1ファイルを復帰しました',
'cannotundelete'               => '復帰に失敗しました。
誰かが、既にこのページを復帰した可能性があります。',
'undeletedpage'                => "'''$1を復元しました。'''

最近の削除と復帰の記録については[[Special:Log/delete|削除記録]]を参照してください。",
'undelete-header'              => '最近削除されたページは[[Special:Log/delete|削除記録]]で確認できます。',
'undelete-search-box'          => '削除されたページを検索',
'undelete-search-prefix'       => '表示を開始するページ名：',
'undelete-search-submit'       => '検索',
'undelete-no-results'          => '削除の保存版に、一致するページが見つかりませんでした。',
'undelete-filename-mismatch'   => '時刻印$1をもつファイルの版を復帰できません：ファイル名が一致しません',
'undelete-bad-store-key'       => '時刻印$1をもつファイルの版を復帰できません：削除前にファイルが失われています。',
'undelete-cleanup-error'       => '未使用の保存版のファイル「$1」の削除中にエラーが発生しました。',
'undelete-missing-filearchive' => 'データベースに存在しないため、ID$1を持つファイルの保存版を復元できません。
既に復帰されている可能性があります。',
'undelete-error-short'         => 'ファイルの復帰エラー：$1',
'undelete-error-long'          => 'ファイルの復帰中にエラーが発生しました：

$1',
'undelete-show-file-confirm'   => '$2$3の版からファイル「<nowiki>$1</nowiki>」の削除版を本当に表示しますか？',
'undelete-show-file-submit'    => 'はい',

# Namespace form on various pages
'namespace'      => '名前空間：',
'invert'         => '選択したものを除く',
'blanknamespace' => '（標準）',

# Contributions
'contributions'       => '利用者の投稿記録',
'contributions-title' => '$1の投稿記録',
'mycontris'           => '自分の投稿記録',
'contribsub2'         => '利用者:$1（$2）',
'nocontribs'          => 'これらの条件に一致する変更は見つかりませんでした。', # Optional parameter: $1 is the user name
'uctop'               => '（最新）',
'month'               => 'これ以前の月：',
'year'                => 'これ以前の年：',

'sp-contributions-newbies'       => '新しいアカウントの投稿のみを表示',
'sp-contributions-newbies-sub'   => '新しいアカウントのみ',
'sp-contributions-newbies-title' => '新しいアカウント利用者の投稿記録',
'sp-contributions-blocklog'      => 'ブロック記録',
'sp-contributions-logs'          => '記録',
'sp-contributions-search'        => '投稿の検索',
'sp-contributions-username'      => 'IPアドレスまたは利用者名：',
'sp-contributions-submit'        => '検索',

# What links here
'whatlinkshere'            => 'リンク元',
'whatlinkshere-title'      => '「$1」へリンクしているページ',
'whatlinkshere-page'       => 'ページ：',
'linkshere'                => "以下のページが、'''[[:$1]]'''にリンクしています：",
'nolinkshere'              => "'''[[:$1]]'''にリンクしているページはありません。",
'nolinkshere-ns'           => "選択された名前空間中で、'''[[:$1]]'''にリンクしているページはありません。",
'isredirect'               => '転送ページ',
'istemplate'               => '参照読み込み',
'isimage'                  => '画像リンク',
'whatlinkshere-prev'       => '{{PLURAL:$1|前|前の$1件}}',
'whatlinkshere-next'       => '{{PLURAL:$1|次|次の$1件}}',
'whatlinkshere-links'      => '← リンク',
'whatlinkshere-hideredirs' => '転送を$1',
'whatlinkshere-hidetrans'  => '参照読み込みを$1',
'whatlinkshere-hidelinks'  => 'リンクを$1',
'whatlinkshere-hideimages' => '画像リンクを$1',
'whatlinkshere-filters'    => '絞り込み',

# Block/unblock
'blockip'                         => '利用者をブロック',
'blockip-legend'                  => '利用者をブロック',
'blockiptext'                     => '以下のフォームを使用して、指定した利用者やIPアドレスからの書き込みアクセスブロックすることができます。
このような措置は、荒らしからの防御のためにのみ行われるべきで、また[[{{MediaWiki:Policy-url}}|方針]]に沿ったものであるべきです。
以下にブロックの理由を具体的に書いてください（例えば、荒らされたページへの言及など）。',
'ipaddress'                       => 'IPアドレス：',
'ipadressorusername'              => 'IPアドレスまたは利用者名：',
'ipbexpiry'                       => '有効期限：',
'ipbreason'                       => '理由：',
'ipbreasonotherlist'              => 'その他の理由',
'ipbreason-dropdown'              => '*よくあるブロック理由
** 虚偽情報の挿入
** ページから内容の除去
** 外部サイトへのスパムリンク追加
** ページへ無意味な/意味不明な内容の挿入
** 威圧的な態度/嫌がらせ
** 複数アカウントの不正利用
** 許可されていない利用者名',
'ipbanononly'                     => '匿名利用者のみブロック',
'ipbcreateaccount'                => 'アカウント作成を禁止する',
'ipbemailban'                     => 'メール送信を防止',
'ipbenableautoblock'              => 'この利用者が最後に使用したIPアドレスと、後に編集しようとしたIPアドレスを自動的にブロック',
'ipbsubmit'                       => 'この利用者をブロック',
'ipbother'                        => 'その他の期間：',
'ipboptions'                      => '2時間:2 hours,1日:1 day,3日:3 days,1週間:1 week,2週間:2 weeks,1か月:1 month,3か月:3 months,6か月:6 months,1年:1 year,無期限:infinite', # display1:time1,display2:time2,...
'ipbotheroption'                  => 'その他',
'ipbotherreason'                  => '他の、または追加の理由：',
'ipbhidename'                     => '利用者名を編集履歴や各種一覧から秘匿する',
'ipbwatchuser'                    => 'この利用者の利用者ページとトークページをウォッチする',
'ipballowusertalk'                => 'この利用者に対して、ブロック中の自身のトークページ編集を許可',
'ipb-change-block'                => 'これらの設定で、利用者を再びブロック',
'badipaddress'                    => '不正なIPアドレス',
'blockipsuccesssub'               => 'ブロックに成功しました',
'blockipsuccesstext'              => '[[Special:Contributions/$1|$1]]はブロックされました。<br />
ブロックを確認するには[[Special:IPBlockList|ブロック中のIPアドレスの一覧]]を参照してください。',
'ipb-edit-dropdown'               => 'ブロック理由を編集する',
'ipb-unblock-addr'                => '$1のブロックを解除',
'ipb-unblock'                     => '利用者またはIPアドレスのブロックを解除する',
'ipb-blocklist-addr'              => '$1 に対する現在有効なブロック',
'ipb-blocklist'                   => '現在有効なブロックを表示',
'ipb-blocklist-contribs'          => '$1の投稿',
'unblockip'                       => 'ブロックを解除する',
'unblockiptext'                   => '以下のフォームで利用者またはIPアドレスの投稿ブロックを解除できます。',
'ipusubmit'                       => 'この投稿ブロックを解除',
'unblocked'                       => '[[User:$1|$1]]のブロックを解除しました',
'unblocked-id'                    => 'ブロック$1は除去されました',
'ipblocklist'                     => 'ブロック中のIPアドレスや利用者',
'ipblocklist-legend'              => 'ブロック中の利用者を検索',
'ipblocklist-username'            => '利用者名またはIPアドレス：',
'ipblocklist-sh-userblocks'       => 'アカウントのブロックを$1',
'ipblocklist-sh-tempblocks'       => '一時ブロックを$1',
'ipblocklist-sh-addressblocks'    => '単一IPのブロックを$1',
'ipblocklist-submit'              => '検索',
'blocklistline'                   => '$1に$2が$3をブロック（$4）',
'infiniteblock'                   => '無期限',
'expiringblock'                   => '$1 に解除',
'anononlyblock'                   => '匿名のみ',
'noautoblockblock'                => '自動ブロック無効',
'createaccountblock'              => 'アカウント作成のブロック',
'emailblock'                      => 'メール送信のブロック',
'blocklist-nousertalk'            => '自身のトークページ編集禁止',
'ipblocklist-empty'               => 'ブロック一覧は空です。',
'ipblocklist-no-results'          => '指定されたIPアドレスまたは利用者名はブロックされていません。',
'blocklink'                       => 'ブロック',
'unblocklink'                     => 'ブロックを解除',
'change-blocklink'                => '設定を変更',
'contribslink'                    => '投稿記録',
'autoblocker'                     => '使用中のIPアドレスが「[[User:$1|$1]]」に使用されたため、自動ブロックされています。
$1のブロックの理由は「$2」です。',
'blocklogpage'                    => 'ブロック記録',
'blocklog-fulllog'                => '投稿ブロック記録すべてを見る',
'blocklogentry'                   => '[[$1]] を$2ブロックしました。ブロックの詳細$3',
'reblock-logentry'                => '[[$1]]ブロック設定を$2に変更しました。ブロックの内容は$3です',
'blocklogtext'                    => 'このページは利用者のブロックと解除の記録です。
自動的にブロックされたIPアドレスは表示されていません。
現時点で有効なブロックは[[Special:IPBlockList|ブロックの一覧]]をご覧ください。',
'unblocklogentry'                 => '$1のブロックを解除しました',
'block-log-flags-anononly'        => '匿名利用者のみ',
'block-log-flags-nocreate'        => 'アカウント作成のブロック',
'block-log-flags-noautoblock'     => '自動ブロック無効',
'block-log-flags-noemail'         => 'メール送信のブロック',
'block-log-flags-nousertalk'      => '自身のトークページの編集禁止',
'block-log-flags-angry-autoblock' => '拡張自動ブロック有効',
'block-log-flags-hiddenname'      => '利用者名を隠す',
'range_block_disabled'            => '範囲ブロックを作成する管理者機能は無効化されています。',
'ipb_expiry_invalid'              => '有効期限が不正です。',
'ipb_expiry_temp'                 => '利用者名秘匿のブロックは、無期限ブロックなります。',
'ipb_hide_invalid'                => 'このアカウントを秘匿できません。編集回数が非常に多いためだと思われます。',
'ipb_already_blocked'             => '「$1」は既にブロックされています',
'ipb-needreblock'                 => '== すでにブロックされています ==
$1は、すでにブロックされています。
設定を変更しますか？',
'ipb_cant_unblock'                => 'エラー：ブロックID$1が見つかりません。
ブロックが既に解除されている可能性があります。',
'ipb_blocked_as_range'            => 'エラー：IPアドレス$1は直接ブロックされておらず、ブロックを解除できませんでした。
ただし、$2の範囲でブロックされており、こちらの設定を変更することでブロック解除できます。',
'ip_range_invalid'                => '不正なIP範囲です。',
'blockme'                         => '自分をブロック',
'proxyblocker'                    => 'プロキシブロック係',
'proxyblocker-disabled'           => 'この機能は無効になっています。',
'proxyblockreason'                => '使用中のIPアドレスは公開プロキシであるため投稿ブロックされています。
使用中ののインターネットサービスプロバイダー、もしくは技術担当者に連絡を取り、これが深刻なセキュリティー問題であることを伝えてください。',
'proxyblocksuccess'               => '完了。',
'sorbsreason'                     => '使用中のIPアドレスが、{{SITENAME}}の使用しているDNSBLに公開プロキシとして記載されています。',
'sorbs_create_account_reason'     => '使用中のIPアドレスが、{{SITENAME}}の使用しているDNSBLに公開プロキシとして記載されています。
アカウントは作成できません',
'cant-block-while-blocked'        => 'ブロック中は、他の利用者をブロックできません。',

# Developer tools
'lockdb'              => 'データベースのロック',
'unlockdb'            => 'データベースのロック解除',
'lockdbtext'          => 'データベースをロックするとすべての利用者はページの編集や、個人設定の変更、ウォッチリストの編集、その他データベースでの変更を要求する作業ができなくなります。
本当にデータベースをロックしていいかどうか確認し、メンテナンスが終了したらロックを解除してください。',
'unlockdbtext'        => 'データベースのロックを解除すると、すべての利用者がページの編集や、個人設定の変更、ウォッチリストの編集、その他データベースでの変更を要求する作業ができるようになります。
本当にデータベースのロックを解除していいかどうか確認してください。',
'lockconfirm'         => '本当にデータベースをロックする。',
'unlockconfirm'       => '本当にデータベースのロックを解除する。',
'lockbtn'             => 'データベースをロック',
'unlockbtn'           => 'データベースのロックを解除',
'locknoconfirm'       => '確認ボックスがチェックされていません。',
'lockdbsuccesssub'    => 'データベースのロックに成功しました',
'unlockdbsuccesssub'  => 'データベースのロックを除去しました',
'lockdbsuccesstext'   => 'データベースはロックされましたら。<br />
メンテナンスが完了したら、忘れずに[[Special:UnlockDB|ロックを除去]]してください。',
'unlockdbsuccesstext' => 'データベースのロックは解除されました。',
'lockfilenotwritable' => 'データベースのロックファイルは書き込み不可です。
データベースをロックまたは解除するには、ウェブサーバーにより書き込み可能である必要があります。',
'databasenotlocked'   => 'データベースはロックされていません。',

# Move page
'move-page'                    => '「$1」の移動',
'move-page-legend'             => 'ページの移動',
'movepagetext'                 => "下のフォームを利用すると、ページ名が変更され、その履歴も変更先へ移動します。
古いページは変更先への転送ページとなります。
変更前のページへの転送は自動的に修正することができます。
自動的な修正を選択しない場合は、[[Special:DoubleRedirects|二重リダイレクト]]や[[Special:BrokenRedirects|迷子のリダイレクト]]を確認する必要があります。
リンクを正しく維持するのは移動した人の責任です。

移動先がすでに存在する場合には、そのページが空またはリダイレクトで、かつ過去の版を持たない場合を除いて移動'''できません'''。
つまり、間違えてページ名を変更した場合には元に戻せます。また移動によって既存のページを上書きしてしまうことはありません。

'''注意！'''
よく閲覧されるページや、他の多くのページからリンクされているページを移動すると予期せぬ結果が起こるかもしれません。
ページの移動に伴う影響をよく考えてから移動してください。",
'movepagetalktext'             => '関連付けられたトークページは、自動的に一緒に移動されます。ただしこれは、以下の場合を除きます。
* 空でないトークページが新しい名前で存在する場合
* 下のボックスのチェックを消した場合

これらの場合、必要に応じて、トークページを移動または統合する必要があります。',
'movearticle'                  => '移動するページ：',
'movenologin'                  => 'ログインしていません',
'movenologintext'              => 'ページを移動するためには、登録利用者でありかつ、[[Special:UserLogin|ログイン]]している必要があります。',
'movenotallowed'               => 'ページを移動する権限がありません。',
'movenotallowedfile'           => 'ファイルを移動する権限がありません。',
'cant-move-user-page'          => '利用者ページを移動させる権限がありません（下位ページ内は除く）。',
'cant-move-to-user-page'       => '利用者下位ページ以外の利用者ページに、ページを移動させる権限がありません。',
'newtitle'                     => '新しいページ名：',
'move-watch'                   => '移動元と移動先ページをウォッチ',
'movepagebtn'                  => 'ページを移動',
'pagemovedsub'                 => '移動に成功しました',
'movepage-moved'               => "'''「$1」は「$2」へ移動されました'''", # The two titles are passed in plain text as $3 and $4 to allow additional goodies in the message.
'movepage-moved-redirect'      => '転送が作成されました。',
'movepage-moved-noredirect'    => '転送の作成は抑制されました。',
'articleexists'                => '指定された移動先には既にページが存在するか、名前が不適切です。
別の名前を選択してください。',
'cantmove-titleprotected'      => '新しいページ名が作成保護されているため、この場所にページを移動できません。',
'talkexists'                   => "'''ページ自身は無事に移動されましたが、トークページは移動先のページが存在したため移動できませんでした。
手動で統合してください。'''",
'movedto'                      => '移動先：',
'movetalk'                     => '関連付けられたトークページを移動',
'move-subpages'                => '下位ページも移動する（$1ページまで）',
'move-talk-subpages'           => 'トークページの下位ページも移動する（$1個まで）',
'movepage-page-exists'         => 'ページ$1は既に存在するため、自動的に上書きされませんでした。',
'movepage-page-moved'          => 'ページ$1は$2へ移動されました。',
'movepage-page-unmoved'        => 'ページ$1は$2へ移動できませんでした。',
'movepage-max-pages'           => '自動的に移動できる{{PLURAL:$1|ページ}}は $1件までで、それ以上は移動されません。',
'1movedto2'                    => '[[$1]]を[[$2]]へ移動',
'1movedto2_redir'              => '[[$1]]を、[[$2]]へ移動し転送を上書き',
'move-redirect-suppressed'     => '転送は非作成',
'movelogpage'                  => '移動記録',
'movelogpagetext'              => '以下は全てのページの移動一覧です。',
'movesubpage'                  => '{{PLURAL:$1|下位ページ}}',
'movesubpagetext'              => 'このページには、以下に示す$1下位ページがあります。',
'movenosubpage'                => 'このページに下位ページはありません。',
'movereason'                   => '理由：',
'revertmove'                   => '差し戻し',
'delete_and_move'              => '削除して移動する',
'delete_and_move_text'         => '== 削除が必要です ==
移動先「[[:$1]]」は既に存在しています。
移動するためにこのページを削除しますか？',
'delete_and_move_confirm'      => 'ページを削除します',
'delete_and_move_reason'       => '移動のために削除',
'selfmove'                     => '移動元と移動先のページ名が同じです。
自分自身へは移動できません。',
'immobile-source-namespace'    => '$1名前空間のページを移動させることはできません。',
'immobile-target-namespace'    => '「$1」名前空間へはページを移動させることはできません。',
'immobile-target-namespace-iw' => 'ウィキ間リンクは、ページの移動では不正な対象です。',
'immobile-source-page'         => 'このページは移動できません。',
'immobile-target-page'         => '目的のページ名へは移動させることができません。',
'imagenocrossnamespace'        => 'ファイル名前空間以外に、ファイルを移動することはできません。',
'imagetypemismatch'            => '新しいファイルの拡張子がファイルのタイプと一致していません。',
'imageinvalidfilename'         => '対象ファイル名が不正です',
'fix-double-redirects'         => '元のページ名への転送を更新',
'move-leave-redirect'          => '跡地に転送を残す',

# Export
'export'            => 'ページの書き出し',
'exporttext'        => 'ここでは単独あるいは複数のページの本文と編集履歴を、XMLの形で書き出すことができます。
このXMLは、他のMediaWikiを使用しているウィキで[[Special:Import|取り込みページ]]を使って取り込めます。

ページを書き出すには、下の入力ボックスに書き出したいページの名前を一行に一つずつ記入してください。また、編集履歴とともにすべての過去版を含んで書き出すのか、最新版のみを書き出すのか選択してください。

後者の場合ではリンクの形で使うこともできます。例えば、[[{{#Special:Export}}/{{MediaWiki:Mainpage}}]]はページ「[[{{MediaWiki:Mainpage}}]]」が対象になります。',
'exportcuronly'     => 'すべての履歴はなしで、最新版のみを含める',
'exportnohistory'   => "----
'''注意：'''負荷上の理由により、このフォームによるページの完全な履歴の書き出しは無効化されています。",
'export-submit'     => '書き出し',
'export-addcattext' => 'カテゴリからページを追加：',
'export-addcat'     => '追加',
'export-addnstext'  => '名前空間からページを追加：',
'export-addns'      => '追加',
'export-download'   => 'ファイルとして保存',
'export-templates'  => 'テンプレートも含める',
'export-pagelinks'  => '以下の階層までのリンク先ページを含める：',

# Namespace 8 related
'allmessages'               => 'システムメッセージの一覧',
'allmessagesname'           => '名前',
'allmessagesdefault'        => '既定のメッセージ文',
'allmessagescurrent'        => '現在のメッセージ文',
'allmessagestext'           => 'これはMediaWiki名前空間で利用可能なシステムメッセージの一覧です。
一般的なMediaWikiの地域化に貢献したい場合は、[http://www.mediawiki.org/wiki/Localisation MediaWikiの地域化]や[http://translatewiki.net?setlang=ja translatewiki.net]を訪れてみてください。',
'allmessagesnotsupportedDB' => "'''\$wgUseDatabaseMessages'''が無効なので、このページを使うことはできません。",
'allmessagesfilter'         => 'メッセージ名で絞り込み:',
'allmessagesmodified'       => '変更されたもののみを表示',

# Thumbnails
'thumbnail-more'           => '拡大',
'filemissing'              => 'ファイルがありません',
'thumbnail_error'          => 'サムネイルの作成中にエラーが発生しました：$1',
'djvu_page_error'          => 'DjVuページが範囲外です',
'djvu_no_xml'              => 'DjVuファイルのXMLデータを取得できません',
'thumbnail_invalid_params' => 'サムネイル引数が不正です',
'thumbnail_dest_directory' => '出力ディレクトリを作成できません',

# Special:Import
'import'                     => 'ページデータの取り込み',
'importinterwiki'            => 'ウィキ間移動の取り込み',
'import-interwiki-text'      => '取り込むウィキとページ名を選択してください。
版の日付と編集者の名前は保持されます。
全てのウィキ間移動取り込みの操作は[[Special:Log/import|取り込み記録]]に記録されます。',
'import-interwiki-source'    => '取り込み元のウィキ/ページ：',
'import-interwiki-history'   => 'このページのすべての版を複製する',
'import-interwiki-templates' => 'すべてのテンプレートを含める',
'import-interwiki-submit'    => '取り込み',
'import-interwiki-namespace' => '目的の名前空間：',
'import-upload-filename'     => 'ファイルの名前：',
'import-comment'             => 'コメント：',
'importtext'                 => '書き出し元となるウィキから[[Special:Export|書き出し用機能]]を使ってファイルを書き出してください。
それをコンピューターに保存した後、ここにアップロードしてください。',
'importstart'                => 'ページを取り込んでいます・・・',
'import-revision-count'      => '$1版',
'importnopages'              => '取り込むページがありません。',
'importfailed'               => '取り込みに失敗しました：<nowiki>$1</nowiki>',
'importunknownsource'        => '取り込み元のタイプが不明です',
'importcantopen'             => '取り込みファイルが開けませんでした',
'importbadinterwiki'         => 'ウィキ間リンクが正しくありません',
'importnotext'               => '空かもしくは本文がありません',
'importsuccess'              => '取り込みが完了しました！',
'importhistoryconflict'      => '取り込み時にいくつかの版が競合しました（以前に同じページが取り込まれているかもしれません）',
'importnosources'            => 'ウィキ間移動の取り込み元が定義されていないため、履歴の直接アップロードは無効になっています。',
'importnofile'               => 'ファイルがアップロードされませんでした',
'importuploaderrorsize'      => '取り込みファイルのアップロードに失敗しました。
ファイルは、アップロード可能なサイズを超えています。',
'importuploaderrorpartial'   => '取り込みファイルのアップロードに失敗しました。
ファイルの一部のみアップロードされました。',
'importuploaderrortemp'      => '取り込みファイルのアップロードに失敗しました。
一時フォルダーがありません。',
'import-parse-failure'       => 'XMLの取り込み構文解析に失敗しました',
'import-noarticle'           => '取り込むページがありません！',
'import-nonewrevisions'      => 'すべての版は以前に取り込み済みです。',
'xml-error-string'           => '$1、$2行の$3文字目（$4バイト目）：$5',
'import-upload'              => 'XMLデータをアップロード',
'import-token-mismatch'      => 'セッションデータを損失しました。
もう一度試してください。',
'import-invalid-interwiki'   => '指定されたウィキから取り込めませんでした。',

# Import log
'importlogpage'                    => '取り込み記録',
'importlogpagetext'                => '管理された他のウィキから編集履歴を伴ったページ取り込みです。',
'import-logentry-upload'           => 'ファイルのアップロードにより[[$1]]を取り込みました',
'import-logentry-upload-detail'    => '$1版',
'import-logentry-interwiki'        => '$1をウィキ間移動しました',
'import-logentry-interwiki-detail' => '$2の$1版',

# Tooltip help for the actions
'tooltip-pt-userpage'             => '自分の利用者ページ',
'tooltip-pt-anonuserpage'         => '自分が編集しているIPアドレスの利用者ページ',
'tooltip-pt-mytalk'               => '自分のトークページ',
'tooltip-pt-anontalk'             => 'このIPアドレスからなされた編集についての議論',
'tooltip-pt-preferences'          => '個人設定',
'tooltip-pt-watchlist'            => '変更を監視しているページの一覧',
'tooltip-pt-mycontris'            => '自分の投稿一覧',
'tooltip-pt-login'                => 'ログインすることが推奨されます。ただし、必須ではありません。',
'tooltip-pt-anonlogin'            => 'ログインすることが推奨されます。ただし、必須ではありません。',
'tooltip-pt-logout'               => 'ログアウト',
'tooltip-ca-talk'                 => '記事についての議論',
'tooltip-ca-edit'                 => 'このページを編集できます。保存する前にプレビューボタンを使ってください。',
'tooltip-ca-addsection'           => '新しい節を開始する',
'tooltip-ca-viewsource'           => 'このページは保護されています。
ページのソースを閲覧できます。',
'tooltip-ca-history'              => 'このページの過去の版',
'tooltip-ca-protect'              => 'このページを保護',
'tooltip-ca-delete'               => 'このページを削除',
'tooltip-ca-undelete'             => '削除される前になされた編集を復元',
'tooltip-ca-move'                 => 'このページを移動',
'tooltip-ca-watch'                => 'このページをウォッチリストに追加',
'tooltip-ca-unwatch'              => 'このページをウォッチリストから除去',
'tooltip-search'                  => '{{SITENAME}}内を検索',
'tooltip-search-go'               => 'この正確な名前をもつページが存在すれば、そのページへ移動します',
'tooltip-search-fulltext'         => '入力された文字列が含まれるページを検索します',
'tooltip-p-logo'                  => 'メインページに移動',
'tooltip-n-mainpage'              => 'メインページに移動',
'tooltip-n-portal'                => 'このプロジェクトについて、できること、情報を入手する場所',
'tooltip-n-currentevents'         => '最近の出来事について予備知識を得る',
'tooltip-n-recentchanges'         => 'ウィキにおける最近の更新の一覧',
'tooltip-n-randompage'            => '無作為に抽出されたページの読み込み',
'tooltip-n-help'                  => '情報を得る場所',
'tooltip-t-whatlinkshere'         => 'ここにリンクしている全ウィキのページの一覧',
'tooltip-t-recentchangeslinked'   => 'ここにリンクしている全ウィキのページの最近の変更',
'tooltip-feed-rss'                => 'このページのRSSフィード',
'tooltip-feed-atom'               => 'このページのAtomフィード',
'tooltip-t-contributions'         => 'この利用者の投稿の一覧を表示',
'tooltip-t-emailuser'             => 'この利用者に電子メールを送信',
'tooltip-t-upload'                => 'ファイルをアップロード',
'tooltip-t-specialpages'          => '特別ページの一覧',
'tooltip-t-print'                 => 'このページの印刷用ページ',
'tooltip-t-permalink'             => 'ページのこの版への固定リンク',
'tooltip-ca-nstab-main'           => '本文を表示',
'tooltip-ca-nstab-user'           => '利用者ページを表示',
'tooltip-ca-nstab-media'          => 'メディアページを表示',
'tooltip-ca-nstab-special'        => 'これは特別ページです。編集することはできません。',
'tooltip-ca-nstab-project'        => 'プロジェクトページを表示',
'tooltip-ca-nstab-image'          => 'ファイルページを表示',
'tooltip-ca-nstab-mediawiki'      => 'システムメッセージを表示',
'tooltip-ca-nstab-template'       => 'テンプレートを表示',
'tooltip-ca-nstab-help'           => 'ヘルプページを表示',
'tooltip-ca-nstab-category'       => 'カテゴリページを表示',
'tooltip-minoredit'               => 'この編集を細部の変更とマーク',
'tooltip-save'                    => '変更を保存',
'tooltip-preview'                 => '変更をプレビューで確認できます。保存前に使用してください！',
'tooltip-diff'                    => '文章に加えた変更を表示します',
'tooltip-compareselectedversions' => '選択された二つのこのページの版間の差分を表示します',
'tooltip-watch'                   => 'このページをウォッチリストへ追加します',
'tooltip-recreate'                => '削除されていても、ページを再作成',
'tooltip-upload'                  => 'アップロードを開始',
'tooltip-rollback'                => '「巻き戻し」は、このページの最後の編集者によるこのページへの編集を1クリックで差し戻します',
'tooltip-undo'                    => '「取り消し」はこの編集を差し戻し、編集画面をプレビューをつけて開きます。要約欄に取り消しの理由を追加することができます。',

# Stylesheets
'common.css'      => '/* ここに書いたCSSはすべての外装に反映されます */',
'standard.css'    => '/* ここに記述したCSSはスタンダード外装の利用者に影響します */',
'nostalgia.css'   => '/* ここに記述したCSSはノスタルジア外装の利用者に影響します */',
'cologneblue.css' => '/* ここに記述したCSSはケルンブルー外装の利用者に影響します */',
'monobook.css'    => '/* ここに記述したCSSはモノブック外装の利用者に影響します */',
'myskin.css'      => '/* ここに記述したCSSはマイスキン外装の利用者に影響します */',
'chick.css'       => '/* ここに記述したCSSはチック外装の利用者に影響します */',
'simple.css'      => '/* ここに記述したCSSはシンプル外装の利用者に影響します */',
'modern.css'      => '/* ここに記述したCSSはモダン外装の利用者に影響します */',
'print.css'       => '/* ここに記述したCSSは印刷出力に影響します */',
'handheld.css'    => '/* ここに記述したCSSは$wgHandheldStyleで設定された外装に基づく携帯機器に影響します */',

# Scripts
'common.js'      => '/* ここにあるすべてのJavaScriptは、すべてのページ読み込みですべての利用者に対して読み込まれます */',
'standard.js'    => '/* ここにあるすべてのJavaScriptは、スタンダード外装を使用している利用者に対して読み込まれます */',
'nostalgia.js'   => '/* ここにあるすべてのJavaScriptは、ノスタルジア外装を使用している利用者に対して読み込まれます */',
'cologneblue.js' => '/* ここにあるすべてのJavaScriptは、ケルンブルー外装を使用している利用者に対して読み込まれます */',
'monobook.js'    => '/* ここにあるすべてのJavaScriptは、モノブック外装を使用している利用者に対して読み込まれます */',
'myskin.js'      => '/* ここにあるすべてのJavaScriptは、マイスキン外装を使用している利用者に対して読み込まれます */',
'chick.js'       => '/* ここにあるすべてのJavaScriptは、チック外装を使用している利用者に対して読み込まれます */',
'simple.js'      => '/* ここにあるすべてのJavaScriptは、シンプル外装を使用している利用者に対して読み込まれます */',
'modern.js'      => '/* ここにあるすべてのJavaScriptは、モダン外装を使用している利用者に対して読み込まれます */',

# Metadata
'nodublincore'      => 'このサーバーではDublin Core RDFメタデータが無効になっています。',
'nocreativecommons' => 'このサーバーではクリエイティブ・コモンズのRDFメタデータが無効化されています。',
'notacceptable'     => 'ウィキサーバーは、使用中のクライアントが読める形式での情報を、提供できません。',

# Attribution
'anonymous'        => '{{SITENAME}}の匿名{{PLURAL:$1|利用者}}',
'siteuser'         => '{{SITENAME}}の利用者：$1',
'lastmodifiedatby' => 'このページは$1の$2に$3によってページの最終更新されました。', # $1 date, $2 time, $3 user
'othercontribs'    => 'また、最終更新以前に $1 が編集しました。',
'others'           => 'その他',
'siteusers'        => '{{SITENAME}}の{{PLURAL:$2|利用者}}$1',
'creditspage'      => 'ページの帰属表示',
'nocredits'        => 'このページに対する帰属情報がありません。',

# Spam protection
'spamprotectiontitle' => 'スパム防御フィルター',
'spamprotectiontext'  => '保存しようとした文章はスパムフィルターによってブロックされました。
これはおそらく、ブラックリストにある外部サイトへのリンクが原因で発生します。',
'spamprotectionmatch' => '以下は、スパムフィルターが発動した文章です：$1',
'spambot_username'    => 'MediaWikiスパム除去',
'spam_reverting'      => '$1へのリンクを含まない最新の版に差し戻し',
'spam_blanking'       => 'すべての版が$1へのリンクを含んでいます。白紙化します。',

# Info page
'infosubtitle'   => 'ページ情報',
'numedits'       => '編集数（ページ）：$1',
'numtalkedits'   => '編集数（議論ページ）：$1',
'numwatchers'    => 'ウォッチしている利用者数：$1',
'numauthors'     => '個別の著者数（ページ）：$1',
'numtalkauthors' => '個別の著者数（議論ページ）：$1',

# Skin names
'skinname-standard'    => 'クラシック',
'skinname-nostalgia'   => 'ノスタルジア',
'skinname-cologneblue' => 'ケルンブルー',
'skinname-monobook'    => 'モノブック',
'skinname-myskin'      => 'マイスキン',
'skinname-chick'       => 'チック',
'skinname-simple'      => 'シンプル',
'skinname-modern'      => 'モダン',

# Math options
'mw_math_png'    => '常にPNGで描画',
'mw_math_simple' => '簡単ならHTML、それ以外はPNG',
'mw_math_html'   => '可能ならHTML、それ以外はPNG',
'mw_math_source' => 'TeXのまま（テキストブラウザー向け）',
'mw_math_modern' => '最新のブラウザーでの推奨',
'mw_math_mathml' => '可能ならMathML（試験中の機能）',

# Patrolling
'markaspatrolleddiff'                 => '巡回済みにする',
'markaspatrolledtext'                 => 'このページを巡回済みにする',
'markedaspatrolled'                   => '巡回済みにしました',
'markedaspatrolledtext'               => '選択された編集をパトロール済みにしました。',
'rcpatroldisabled'                    => '最近の更新の巡回は無効です',
'rcpatroldisabledtext'                => '最近の更新の巡回機能は現在無効になっています。',
'markedaspatrollederror'              => '巡回済みにできません。',
'markedaspatrollederrortext'          => '巡回済みにするためにはどの版かを指定する必要があります。',
'markedaspatrollederror-noautopatrol' => '自分自身による編集を巡回済みにする権限がありません。',

# Patrol log
'patrol-log-page'      => '巡回記録',
'patrol-log-header'    => '以下は巡回された版の記録です。',
'patrol-log-line'      => '$2の$1を巡回$3',
'patrol-log-auto'      => '（自動）',
'patrol-log-diff'      => '$1版',
'log-show-hide-patrol' => '巡回記録を$1',

# Image deletion
'deletedrevision'                 => '古い版$1を削除しました',
'filedeleteerror-short'           => 'ファイル削除エラー：$1',
'filedeleteerror-long'            => 'ファイルの削除中にエラーが発生しました：

$1',
'filedelete-missing'              => 'ファイル「$1」は存在しないため、削除することができません。',
'filedelete-old-unregistered'     => '指定されたファイルの版「$1」はデータベースにありません。',
'filedelete-current-unregistered' => '指定されたファイル「$1」はデータベース内にはありません。',
'filedelete-archive-read-only'    => '保存版ディレクトリ「$1」は、ウェブサーバーから書き込み不可になっています。',

# Browsing diffs
'previousdiff' => '←古い編集',
'nextdiff'     => '新しい編集→',

# Visual comparison
'visual-comparison' => '表示結果の比較',

# Media information
'mediawarning'         => "'''警告:''' このファイルは悪意のあるコードを含んでいる可能性があります。
実行するとコンピューターが危害を被る場合があります。",
'imagemaxsize'         => 'ファイルページで表示する画像の最大サイズ:',
'thumbsize'            => 'サムネイルの大きさ：',
'widthheightpage'      => '$1×$2、$3ページ',
'file-info'            => '(ファイルサイズ: $1, MIMEタイプ: $2)',
'file-info-size'       => '($1 × $2 ピクセル, ファイルサイズ: $3, MIMEタイプ: $4)',
'file-nohires'         => '<small>高解像度版はありません。</small>',
'svg-long-desc'        => '(SVGファイル, $1 × $2 ピクセル, ファイルサイズ: $3)',
'show-big-image'       => '高解像度での画像',
'show-big-image-thumb' => '<small>このプレビューのサイズ：$1×$2ピクセル</small>',

# Special:NewFiles
'newimages'             => '新しいファイルのギャラリー',
'imagelisttext'         => "以下は、$2で並び替えられた'''$1'''ファイルの一覧です。",
'newimages-summary'     => 'この特別ページでは最近、アップロードされたファイルを表示します。',
'newimages-legend'      => '絞り込み',
'newimages-label'       => 'ファイル名（もしくはその一部）：',
'showhidebots'          => '（ボットを$1）',
'noimages'              => '表示できるものがありません。',
'ilsubmit'              => '検索',
'bydate'                => '日付順',
'sp-newimages-showfrom' => '$1の$2以降の新しいファイルを表示',

# Bad image list
'bad_image_list' => '書式は以下の通りです：

箇条書き項目（*で始まる行）のみが考慮されます。
各行最初のリンクは、好ましくないファイルへのリンクとしてください。
同じ行の以降のリンクは例外とみなされ、つまりそのファイルの内部挿入が許可されます。',

/*
Short names for language variants used for language conversion links.
To disable showing a particular link, set it to 'disable', e.g.
'variantname-zh-sg' => 'disable',
Variants for Chinese language
*/
'variantname-zh-hans' => '簡体',
'variantname-zh-hant' => '繁体',
'variantname-zh-cn'   => '中国簡体',
'variantname-zh-tw'   => '台湾正体',
'variantname-zh-hk'   => '香港正体',
'variantname-zh-sg'   => 'シンガポール簡体',
'variantname-zh'      => '無変換',

# Metadata
'metadata'          => 'メタデータ',
'metadata-help'     => 'このファイルは、追加情報を含んでいます（おそらく、デジタルカメラやスキャナーが作成あるいはデジタル化し追加したもの）。
このファイルが元の状態から変更されている場合、いくつかの項目は、完全には修正されたファイルに反映していないかもしれません。',
'metadata-expand'   => '拡張項目を表示',
'metadata-collapse' => '拡張項目を非表示',
'metadata-fields'   => 'ここのメッセージにあるEXIFメタデータフィールドは、メタデータ表が折りたたまれている状態のときに画像ページに読み込まれます。
他のものは既定では非表示です。
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* isospeedratings
* focallength', # Do not translate list items

# EXIF tags
'exif-imagewidth'                  => '幅',
'exif-imagelength'                 => '高さ',
'exif-bitspersample'               => 'コンポーネントごとのビット',
'exif-compression'                 => '圧縮形式',
'exif-photometricinterpretation'   => '画素構成',
'exif-orientation'                 => '画像方向',
'exif-samplesperpixel'             => 'コンポーネント数',
'exif-planarconfiguration'         => 'データ格納形式',
'exif-ycbcrsubsampling'            => 'CへのYの副次抽出率',
'exif-ycbcrpositioning'            => 'YとCの位置',
'exif-xresolution'                 => '水平解像度',
'exif-yresolution'                 => '垂直解像度',
'exif-resolutionunit'              => 'XとY解像度の単位',
'exif-stripoffsets'                => '画像データの場所',
'exif-rowsperstrip'                => 'ストリップごとの行数',
'exif-stripbytecounts'             => '圧縮されたストリップごとのバイト数',
'exif-jpeginterchangeformat'       => 'JPEGのSOIへのオフセット',
'exif-jpeginterchangeformatlength' => 'JPEGデータのバイト数',
'exif-transferfunction'            => '再生階調カーブ特性',
'exif-whitepoint'                  => '参照白色点の色度座標値',
'exif-primarychromaticities'       => '原色の色度座標値',
'exif-ycbcrcoefficients'           => '色変換マトリックス係数',
'exif-referenceblackwhite'         => '参照黒色点値・参照白色点値',
'exif-datetime'                    => 'ファイル変更日時',
'exif-imagedescription'            => '画像の説明',
'exif-make'                        => '画像入力機器のメーカー',
'exif-model'                       => '画像入力機器の機種',
'exif-software'                    => 'ファームウェアのバージョン',
'exif-artist'                      => '作成者',
'exif-copyright'                   => '著作権者',
'exif-exifversion'                 => 'Exifバージョン',
'exif-flashpixversion'             => '対応フラッシュピックスバージョン',
'exif-colorspace'                  => '色空間',
'exif-componentsconfiguration'     => '各コンポーネントの構成',
'exif-compressedbitsperpixel'      => '画像圧縮モード',
'exif-pixelydimension'             => '実効画像幅',
'exif-pixelxdimension'             => '実効画像高さ',
'exif-makernote'                   => 'メーカーノート',
'exif-usercomment'                 => '利用者のコメント',
'exif-relatedsoundfile'            => '関連音声ファイル',
'exif-datetimeoriginal'            => '画像データ生成日時',
'exif-datetimedigitized'           => 'デジタルデータ作成日時',
'exif-subsectime'                  => 'ファイル変更日時（秒未満）',
'exif-subsectimeoriginal'          => '画像データ生成日時（秒未満）',
'exif-subsectimedigitized'         => 'デジタルデータ作成日時（秒未満）',
'exif-exposuretime'                => '露出時間',
'exif-exposuretime-format'         => '$1秒 ($2)',
'exif-fnumber'                     => 'F値',
'exif-exposureprogram'             => '露出プログラム',
'exif-spectralsensitivity'         => 'スペクトル感度',
'exif-isospeedratings'             => 'ISOスピードレート',
'exif-oecf'                        => '光電変換関数',
'exif-shutterspeedvalue'           => 'シャッタースピード',
'exif-aperturevalue'               => '絞り値',
'exif-brightnessvalue'             => '明るさ',
'exif-exposurebiasvalue'           => '露出補正値',
'exif-maxaperturevalue'            => 'レンズ最小F値',
'exif-subjectdistance'             => '被写体距離',
'exif-meteringmode'                => '測光方式',
'exif-lightsource'                 => '光源',
'exif-flash'                       => 'フラッシュ',
'exif-focallength'                 => 'レンズの焦点距離',
'exif-subjectarea'                 => '主要被写体の位置',
'exif-flashenergy'                 => 'フラッシュ強度',
'exif-spatialfrequencyresponse'    => '空間周波数応答',
'exif-focalplanexresolution'       => '水平方向の焦点面解像度',
'exif-focalplaneyresolution'       => '垂直方向の焦点面解像度',
'exif-focalplaneresolutionunit'    => '焦点面解像度の単位',
'exif-subjectlocation'             => '被写体の場所',
'exif-exposureindex'               => '露出インデックス',
'exif-sensingmethod'               => 'センサー方式',
'exif-filesource'                  => 'ファイルソース',
'exif-scenetype'                   => 'シーンタイプ',
'exif-cfapattern'                  => 'CFAパターン',
'exif-customrendered'              => '画像処理',
'exif-exposuremode'                => '露出モード',
'exif-whitebalance'                => 'ホワイトバランス',
'exif-digitalzoomratio'            => 'デジタルズーム倍率',
'exif-focallengthin35mmfilm'       => 'レンズの焦点距離（35mmフィルム換算）',
'exif-scenecapturetype'            => '被写体の種別',
'exif-gaincontrol'                 => 'ゲインコントロール',
'exif-contrast'                    => 'コントラスト',
'exif-saturation'                  => '彩度',
'exif-sharpness'                   => 'シャープネス',
'exif-devicesettingdescription'    => '機器設定',
'exif-subjectdistancerange'        => '被写体距離の範囲',
'exif-imageuniqueid'               => 'ユニーク画像ID',
'exif-gpsversionid'                => 'GPSタグのバージョン',
'exif-gpslatituderef'              => '北緯/南緯',
'exif-gpslatitude'                 => '緯度',
'exif-gpslongituderef'             => '東経/西経',
'exif-gpslongitude'                => '経度',
'exif-gpsaltituderef'              => '高度の基準',
'exif-gpsaltitude'                 => '高度',
'exif-gpstimestamp'                => 'GPS時刻（原子時計）',
'exif-gpssatellites'               => '測位に用いた衛星信号',
'exif-gpsstatus'                   => 'GPS受信機の状態',
'exif-gpsmeasuremode'              => 'GPS測位方法',
'exif-gpsdop'                      => '測位精度',
'exif-gpsspeedref'                 => '速度の単位',
'exif-gpsspeed'                    => '速度',
'exif-gpstrackref'                 => '進行方向の基準',
'exif-gpstrack'                    => '進行方向',
'exif-gpsimgdirectionref'          => '撮影方向の基準',
'exif-gpsimgdirection'             => '撮影方向',
'exif-gpsmapdatum'                 => '測地系',
'exif-gpsdestlatituderef'          => '目的地の北緯/南緯',
'exif-gpsdestlatitude'             => '目的地の緯度',
'exif-gpsdestlongituderef'         => '目的地の東経/西経',
'exif-gpsdestlongitude'            => '目的地の経度',
'exif-gpsdestbearingref'           => '目的地の方角の基準',
'exif-gpsdestbearing'              => '目的地の方角',
'exif-gpsdestdistanceref'          => '目的地までの距離の単位',
'exif-gpsdestdistance'             => '目的地までの距離',
'exif-gpsprocessingmethod'         => 'GPS処理方法',
'exif-gpsareainformation'          => 'GPSエリア名',
'exif-gpsdatestamp'                => 'GPS測位日時',
'exif-gpsdifferential'             => 'ディファレンシャル補正',

# EXIF attributes
'exif-compression-1' => '非圧縮',
'exif-compression-6' => 'JPEG圧縮',

'exif-unknowndate' => '不明な日付',

'exif-orientation-1' => '通常', # 0th row: top; 0th column: left
'exif-orientation-2' => '左右反転', # 0th row: top; 0th column: right
'exif-orientation-3' => '180°回転', # 0th row: bottom; 0th column: right
'exif-orientation-4' => '上下反転', # 0th row: bottom; 0th column: left
'exif-orientation-5' => '反時計回りに90°回転 上下反転', # 0th row: left; 0th column: top
'exif-orientation-6' => '時計回りに90°回転', # 0th row: right; 0th column: top
'exif-orientation-7' => '時計回りに90°回転 上下反転', # 0th row: right; 0th column: bottom
'exif-orientation-8' => '反時計回りに90°回転', # 0th row: left; 0th column: bottom

'exif-planarconfiguration-1' => '点順次フォーマット',
'exif-planarconfiguration-2' => '面順次フォーマット',

'exif-colorspace-ffff.h' => 'その他',

'exif-componentsconfiguration-0' => 'なし',

'exif-exposureprogram-0' => '未定義',
'exif-exposureprogram-1' => 'マニュアル',
'exif-exposureprogram-2' => 'ノーマルプログラム',
'exif-exposureprogram-3' => '露出優先',
'exif-exposureprogram-4' => 'シャッター速度優先',
'exif-exposureprogram-5' => 'クリエイティブプログラム',
'exif-exposureprogram-6' => 'アクションプログラム',
'exif-exposureprogram-7' => 'ポートレイトモード（近景）',
'exif-exposureprogram-8' => 'ランドスケープモード（遠景）',

'exif-subjectdistance-value' => '$1メートル',

'exif-meteringmode-0'   => '不明',
'exif-meteringmode-1'   => '平均',
'exif-meteringmode-2'   => '中央重点',
'exif-meteringmode-3'   => 'スポット',
'exif-meteringmode-4'   => 'マルチスポット',
'exif-meteringmode-5'   => '分割測光',
'exif-meteringmode-6'   => '部分測光',
'exif-meteringmode-255' => 'その他',

'exif-lightsource-0'   => '不明',
'exif-lightsource-1'   => '昼光',
'exif-lightsource-2'   => '蛍光灯',
'exif-lightsource-3'   => 'タングステン (白熱灯)',
'exif-lightsource-4'   => 'フラッシュ',
'exif-lightsource-9'   => '晴天',
'exif-lightsource-10'  => '曇天',
'exif-lightsource-11'  => '日陰',
'exif-lightsource-12'  => '昼光色蛍光灯 (D 5700 - 7100K)',
'exif-lightsource-13'  => '昼白色蛍光灯 (N 4600 - 5400K)',
'exif-lightsource-14'  => '白色蛍光灯 (W 3900 - 4500K)',
'exif-lightsource-15'  => '温白色蛍光灯 (WW 3200 - 3700K)',
'exif-lightsource-17'  => '標準光A',
'exif-lightsource-18'  => '標準光B',
'exif-lightsource-19'  => '標準光C',
'exif-lightsource-24'  => 'ISOスタジオタングステン',
'exif-lightsource-255' => 'その他',

# Flash modes
'exif-flash-fired-0'    => 'フラッシュが光りませんでした',
'exif-flash-fired-1'    => 'フラッシュが光りました',
'exif-flash-return-0'   => 'ストロボ反応検知機能がありません',
'exif-flash-return-2'   => 'ストロボ反応光が検知されませんでした',
'exif-flash-return-3'   => 'ストロボ反応光が検知されました',
'exif-flash-mode-1'     => '強制フラッシュ',
'exif-flash-mode-2'     => '強制フラッシュ禁止',
'exif-flash-mode-3'     => '自動モード',
'exif-flash-function-1' => 'フラッシュ機能がありません',
'exif-flash-redeye-1'   => '赤目防止モード',

'exif-focalplaneresolutionunit-2' => 'インチ',

'exif-sensingmethod-1' => '未定義',
'exif-sensingmethod-2' => '単板カラーセンサー',
'exif-sensingmethod-3' => '2板カラーセンサー',
'exif-sensingmethod-4' => '3板カラーセンサー',
'exif-sensingmethod-5' => '色順次カラーセンサー',
'exif-sensingmethod-7' => '3線リニアセンサー',
'exif-sensingmethod-8' => '色順次リニアセンサー',

'exif-filesource-3' => 'デジタルスチルカメラ',

'exif-scenetype-1' => '直接撮影された画像',

'exif-customrendered-0' => '通常',
'exif-customrendered-1' => 'カスタム',

'exif-exposuremode-0' => '自動',
'exif-exposuremode-1' => 'マニュアル',
'exif-exposuremode-2' => 'オートブラケット',

'exif-whitebalance-0' => '自動',
'exif-whitebalance-1' => 'マニュアル',

'exif-scenecapturetype-0' => '標準',
'exif-scenecapturetype-1' => '風景',
'exif-scenecapturetype-2' => '人物',
'exif-scenecapturetype-3' => '夜景',

'exif-gaincontrol-0' => 'なし',
'exif-gaincontrol-1' => '弱増感',
'exif-gaincontrol-2' => '強増感',
'exif-gaincontrol-3' => '弱減感',
'exif-gaincontrol-4' => '強減感',

'exif-contrast-0' => '標準',
'exif-contrast-1' => '軟調',
'exif-contrast-2' => '硬調',

'exif-saturation-0' => '標準',
'exif-saturation-1' => '低彩度',
'exif-saturation-2' => '高彩度',

'exif-sharpness-0' => '標準',
'exif-sharpness-1' => '弱',
'exif-sharpness-2' => '強',

'exif-subjectdistancerange-0' => '不明',
'exif-subjectdistancerange-1' => 'マクロ',
'exif-subjectdistancerange-2' => '近景',
'exif-subjectdistancerange-3' => '遠景',

# Pseudotags used for GPSLatitudeRef and GPSDestLatitudeRef
'exif-gpslatitude-n' => '北緯',
'exif-gpslatitude-s' => '南緯',

# Pseudotags used for GPSLongitudeRef and GPSDestLongitudeRef
'exif-gpslongitude-e' => '東経',
'exif-gpslongitude-w' => '西経',

'exif-gpsstatus-a' => '測位中',
'exif-gpsstatus-v' => '未測位',

'exif-gpsmeasuremode-2' => '2次元測位',
'exif-gpsmeasuremode-3' => '3次元測位',

# Pseudotags used for GPSSpeedRef and GPSDestDistanceRef
'exif-gpsspeed-k' => 'キロメートル毎時',
'exif-gpsspeed-m' => 'マイル毎時',
'exif-gpsspeed-n' => 'ノット',

# Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
'exif-gpsdirection-t' => '真方位',
'exif-gpsdirection-m' => '磁方位',

# External editor support
'edit-externally'      => '外部アプリケーションを使ってこのファイルを編集する',
'edit-externally-help' => '（詳しい情報は[http://www.mediawiki.org/wiki/Manual:External_editors 設定手順]をご覧ください）',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'すべて',
'imagelistall'     => 'すべて',
'watchlistall2'    => 'すべて',
'namespacesall'    => 'すべて',
'monthsall'        => 'すべて',

# E-mail address confirmation
'confirmemail'             => 'メールアドレスの確認',
'confirmemail_noemail'     => '[[Special:Preferences|個人設定]]で有効なメールアドレスが指定されていません。',
'confirmemail_text'        => '{{SITENAME}}では、メール機能を利用する前にメールアドレスの確認が必要です。
以下のボタンを押すとメールアドレスに確認メールが送られます。
メールには確認用コードを含むリンクが書かれています。
そのリンクをブラウザーで読み込んで、メールアドレスの正当性を確認してください。',
'confirmemail_pending'     => '確認メールは既に送信されています。
このアカウントを作成したばかりであれば、メールが届くまで数分ほど待たなければならないかもしれません。',
'confirmemail_send'        => '確認用コードを送信する',
'confirmemail_sent'        => '確認メールを送信しました。',
'confirmemail_oncreate'    => 'メールアドレスの正当性を確認するためのコードを含んだメールを送信しました。
この確認を行わなくてもログインはできますが、確認するまでメール通知の機能は無効化されます。',
'confirmemail_sendfailed'  => '{{SITENAME}}は確認メールを送信できませんでした。
メールアドレスに不正な文字が含まれていないかどうか確認してください。

メールサーバーからの返答：$1',
'confirmemail_invalid'     => '確認用コードが正しくありません。
このコードの有効期限が切れています。',
'confirmemail_needlogin'   => 'メールアドレスを確認するために$1が必要です。',
'confirmemail_success'     => 'メールアドレスは確認されました。
[[Special:UserLogin|ログイン]]してウィキを使用できます。',
'confirmemail_loggedin'    => 'メールアドレスは確認されました。',
'confirmemail_error'       => '確認情報を保存する際にエラーが発生しました。',
'confirmemail_subject'     => '{{SITENAME}} メールアドレスの確認',
'confirmemail_body'        => 'どなたか（IPアドレス $1 の使用者）がこのメールアドレスを
{{SITENAME}} のアカウント "$2" に登録しました。

このアカウントがあなたのものであるか確認してください。
あなたの登録したアカウントであるならば、{{SITENAME}}
のメール通知機能を有効にするために、以下のURLにアクセスしてください:

$3

もし {{SITENAME}} について身に覚えがない場合は、リンクを開かず
次のURLにアクセスしてメール登録を解除ください:

$5

確認用コードは $4 に期限切れになります。
-- 
{{SITENAME}}
{{SERVER}}/',
'confirmemail_invalidated' => 'メールアドレスの確認が中止されました',
'invalidateemail'          => 'メールアドレスの認証中止',

# Scary transclusion
'scarytranscludedisabled' => '[ウィキ間の参照読み込みは無効になっています]',
'scarytranscludefailed'   => '[$1に対してテンプレートの取得に失敗しました]',
'scarytranscludetoolong'  => '[URLが長すぎます]',

# Trackbacks
'trackbackbox'      => 'このページへのトラックバック：<br />
$1',
'trackbackremove'   => '（[$1 削除]）',
'trackbacklink'     => 'トラックバック',
'trackbackdeleteok' => 'トラックバックは正常に削除されました。',

# Delete conflict
'deletedwhileediting' => "'''警告：'''このページが、編集開始後に削除されました！",
'confirmrecreate'     => "[[User:$1|$1]]（[[User talk:$1|トーク]]）が、このページの編集開始後に、このページを、次の理由で削除しました。
: ''$2''
本当にこのままこのページを再作成して良いか確認してください。",
'recreate'            => '再作成する',

# action=purge
'confirm_purge_button' => 'はい',
'confirm-purge-top'    => 'ページのキャッシュを破棄します。よろしいですか？',
'confirm-purge-bottom' => 'ページのパージは、キャッシュを破棄し、強制的に最新の版を表示します。',

# Separators for various lists, etc.
'comma-separator' => '、',
'word-separator'  => '',

# Multipage image navigation
'imgmultipageprev' => '&larr;前ページ',
'imgmultipagenext' => '次ページ&rarr;',
'imgmultigo'       => '表示！',
'imgmultigoto'     => '$1へ行く',

# Table pager
'ascending_abbrev'         => '昇順',
'descending_abbrev'        => '降順',
'table_pager_next'         => '次のページ',
'table_pager_prev'         => '前のページ',
'table_pager_first'        => '最初のページ',
'table_pager_last'         => '最後のページ',
'table_pager_limit'        => '1ページに$1項目を表示',
'table_pager_limit_submit' => '実行',
'table_pager_empty'        => '結果なし',

# Auto-summaries
'autosumm-blank'   => 'ページの白紙化',
'autosumm-replace' => 'ページの置換「$1」',
'autoredircomment' => '[[$1]]への転送',
'autosumm-new'     => 'ページの作成：「$1」',

# Size units
'size-bytes'     => '$1バイト',
'size-kilobytes' => '$1キロバイト',
'size-megabytes' => '$1メガバイト',
'size-gigabytes' => '$1ギガバイト',

# Live preview
'livepreview-loading' => '読み込み中・・・',
'livepreview-ready'   => '読み込み中・・・完了！',
'livepreview-failed'  => 'ライブプレビューが失敗しました！
通常のプレビューを試してください。',
'livepreview-error'   => '接続に失敗しました：$1「$2」。
通常のプレビューを試してください。',

# Friendlier slave lag warnings
'lag-warn-normal' => 'この一覧には、$1秒より前の変更が表示されていない可能性があります。',
'lag-warn-high'   => 'データベースサーバー遅延のため、この一覧には、$1秒より前の変更が表示されていない可能性があります。',

# Watchlist editor
'watchlistedit-numitems'       => 'ウォッチリストには、$1のページ名が含まれています（トークページは除く）。',
'watchlistedit-noitems'        => 'ウォッチリストにはページ名が1つも含まれていません。',
'watchlistedit-normal-title'   => 'ウォッチリストの編集',
'watchlistedit-normal-legend'  => 'ウォッチリストからページ名を除去',
'watchlistedit-normal-explain' => 'ウォッチリストに入っているページ名が以下に表示されています。
ページ名を除去するには、横にあるボックスにチェックを入れ、「{{int:watchlistedit-normal-submit}}」をクリックしてください。
また、[[Special:Watchlist/raw|そのままの一覧で編集]]することもできます。',
'watchlistedit-normal-submit'  => 'ページ名の除去',
'watchlistedit-normal-done'    => 'ウォッチリストから$1のページ名を除去しました：',
'watchlistedit-raw-title'      => 'ウォッチリストをそのまま編集',
'watchlistedit-raw-legend'     => 'ウォッチリストをそのまま編集',
'watchlistedit-raw-explain'    => 'ウォッチリストに含まれるページ名が以下に表示されており、この一覧から追加や除去できます。
1行に1ページ名です。
完了したら、「{{int:Watchlistedit-raw-submit}}」をクリックしてください。
[[Special:Watchlist/edit|標準の編集ページ]]も利用できます。',
'watchlistedit-raw-titles'     => 'ページ名：',
'watchlistedit-raw-submit'     => 'ウォッチリストを更新',
'watchlistedit-raw-done'       => 'ウォッチリストを更新しました。',
'watchlistedit-raw-added'      => '$1のページ名が追加されました：',
'watchlistedit-raw-removed'    => '$1のページ名が除去されました：',

# Watchlist editing tools
'watchlisttools-view' => '関連する変更の表示',
'watchlisttools-edit' => 'ウォッチリストの表示と編集',
'watchlisttools-raw'  => 'ウォッチリストをそのまま編集',

# Iranian month names
'iranian-calendar-m1'  => 'ファルヴァルディーン',
'iranian-calendar-m2'  => 'オルディーベヘシュト',
'iranian-calendar-m3'  => 'ホルダード',
'iranian-calendar-m4'  => 'ティール',
'iranian-calendar-m5'  => 'モルダード',
'iranian-calendar-m6'  => 'シャハリーヴァル',
'iranian-calendar-m7'  => 'メフル',
'iranian-calendar-m8'  => 'アーバーン',
'iranian-calendar-m9'  => 'アーザル',
'iranian-calendar-m10' => 'デイ',
'iranian-calendar-m11' => 'バフマン',
'iranian-calendar-m12' => 'エスファンド',

# Hijri month names
'hijri-calendar-m1'  => 'ムハッラム',
'hijri-calendar-m2'  => 'サファル',
'hijri-calendar-m3'  => 'ラビーウ＝ル＝アウワル',
'hijri-calendar-m4'  => 'ラビーウッ＝サーニー',
'hijri-calendar-m5'  => 'ジュマーダー＝ル＝ウーラー',
'hijri-calendar-m6'  => 'ジュマーダーッ＝サーニー',
'hijri-calendar-m7'  => 'ラジャブ',
'hijri-calendar-m8'  => 'シャアバーン',
'hijri-calendar-m9'  => 'ラマダーン',
'hijri-calendar-m10' => 'シャウワール',
'hijri-calendar-m11' => 'ズー＝ル＝カアダ',
'hijri-calendar-m12' => 'ズー＝ル＝ヒッジャ',

# Hebrew month names
'hebrew-calendar-m1'      => 'ティシュリー',
'hebrew-calendar-m2'      => 'ヘシュヴァン',
'hebrew-calendar-m3'      => 'キスレーヴ',
'hebrew-calendar-m4'      => 'テベット',
'hebrew-calendar-m5'      => 'シュバット',
'hebrew-calendar-m6'      => 'アダル',
'hebrew-calendar-m6a'     => 'アダル・アレフ',
'hebrew-calendar-m6b'     => 'アダル・ベート',
'hebrew-calendar-m7'      => 'ニサン',
'hebrew-calendar-m8'      => 'イヤール',
'hebrew-calendar-m9'      => 'シバン',
'hebrew-calendar-m10'     => 'タムーズ',
'hebrew-calendar-m11'     => 'アブ',
'hebrew-calendar-m12'     => 'エルール',
'hebrew-calendar-m1-gen'  => 'ティシュリー',
'hebrew-calendar-m2-gen'  => 'ヘシュヴァン',
'hebrew-calendar-m3-gen'  => 'キスレーヴ',
'hebrew-calendar-m4-gen'  => 'テベット',
'hebrew-calendar-m5-gen'  => 'シュバット',
'hebrew-calendar-m6-gen'  => 'アダル',
'hebrew-calendar-m6a-gen' => 'アダル・アレフ',
'hebrew-calendar-m6b-gen' => 'アダル・ベート',
'hebrew-calendar-m7-gen'  => 'ニサン',
'hebrew-calendar-m8-gen'  => 'イヤール',
'hebrew-calendar-m9-gen'  => 'シバン',
'hebrew-calendar-m10-gen' => 'タムーズ',
'hebrew-calendar-m11-gen' => 'アブ',
'hebrew-calendar-m12-gen' => 'エルール',

# Core parser functions
'unknown_extension_tag' => '不明な拡張機能タグ「$1」です',
'duplicate-defaultsort' => "'''警告：'''既定の並び替えキー「$2」が、その前に書かれている既定の並び替えキー「$1」を上書きしています。",

# Special:Version
'version'                          => 'バージョン情報', # Not used as normal message but as header for the special page itself
'version-extensions'               => 'インストール済み拡張機能',
'version-specialpages'             => '特別ページ',
'version-parserhooks'              => '構文解析フック',
'version-variables'                => '変数',
'version-other'                    => 'その他',
'version-mediahandlers'            => 'メディアハンドラー',
'version-hooks'                    => 'フック',
'version-extension-functions'      => '拡張機能関数',
'version-parser-extensiontags'     => '構文解析拡張機能タグ',
'version-parser-function-hooks'    => '構文解析関数フック',
'version-skin-extension-functions' => '外装拡張機能関数',
'version-hook-name'                => 'フック名',
'version-hook-subscribedby'        => '使用個所',
'version-version'                  => 'バージョン',
'version-license'                  => 'ライセンス',
'version-software'                 => 'インストール済みソフトウェア',
'version-software-product'         => '製品',
'version-software-version'         => 'バージョン',

# Special:FilePath
'filepath'         => 'ファイルパス',
'filepath-page'    => 'ファイル：',
'filepath-submit'  => '取得',
'filepath-summary' => 'この特別ページは、ファイルへの完全なパスを返します。画像は最大解像度で表示され、他のファイルタイプは関連付けされたプログラムが直接起動します。

ファイル名は接頭辞「{{ns:file}}:」を付けずに入力してください。',

# Special:FileDuplicateSearch
'fileduplicatesearch'          => '重複ファイルの検索',
'fileduplicatesearch-summary'  => '重複ファイルを、ファイルのハッシュ値に基づいて検索します。

ファイル名は接頭辞「{{ns:file}}:」を付けずに入力してください。',
'fileduplicatesearch-legend'   => '重複の検索',
'fileduplicatesearch-filename' => 'ファイル名：',
'fileduplicatesearch-submit'   => '検索',
'fileduplicatesearch-info'     => '$1×$2ピクセル<br />ファイルサイズ：$3<br />MIMEタイプ：$4',
'fileduplicatesearch-result-1' => 'ファイル「$1」と重複するファイルはありません。',
'fileduplicatesearch-result-n' => 'ファイル「$1」は$2ファイルと重複しています。',

# Special:SpecialPages
'specialpages'                   => '特別ページ',
'specialpages-note'              => '----
*通常の特別ページ。
* <strong class="mw-specialpagerestricted">制限されている特別ページ。</strong>',
'specialpages-group-maintenance' => 'メンテナンス報告',
'specialpages-group-other'       => 'その他の特別ページ',
'specialpages-group-login'       => 'ログイン/利用者登録',
'specialpages-group-changes'     => '最近の更新と記録',
'specialpages-group-media'       => 'メディア情報とアップロード',
'specialpages-group-users'       => '利用者と権限',
'specialpages-group-highuse'     => 'よく利用されているページ',
'specialpages-group-pages'       => 'ページの一覧',
'specialpages-group-pagetools'   => 'ページツール',
'specialpages-group-wiki'        => 'ウィキに関する情報とツール',
'specialpages-group-redirects'   => '転送されている特別ページ',
'specialpages-group-spam'        => 'スパム対策ツール',

# Special:BlankPage
'blankpage'              => '白紙ページ',
'intentionallyblankpage' => 'このページは意図的に白紙にされています。',

# External image whitelist
'external_image_whitelist' => '  #この行はそのままにしておいてください<pre>
#この下に正規表現（//の間にくる記述）を置いてください
#外部の（ホットリンクされている）画像の URL と一致するか検査されます
#一致する場合は画像として、一致しない場合は画像へのリンクとして表示されます
#行の頭に # をつけるとコメントとして扱われます
#大文字と小文字は区別されません

#正規表現はすべてこの行の上に置いてください。この行を変更しないでください</pre>',

# Special:Tags
'tags'                    => '有効な変更タグ',
'tag-filter'              => '[[Special:Tags|タグ]]絞り込み：',
'tag-filter-submit'       => '絞り込み',
'tags-title'              => 'タグ',
'tags-intro'              => 'このページは、ソフトウェアが編集に対してつけるタグとその意味の一覧です。',
'tags-tag'                => 'タグ名',
'tags-display-header'     => '変更一覧に表示されるもの',
'tags-description-header' => '詳細な意味の説明',
'tags-hitcount-header'    => 'タグが付与された変更',
'tags-edit'               => '編集',
'tags-hitcount'           => '$1回の変更',

# Database error messages
'dberr-header'      => '問題発生中です',
'dberr-problems'    => '申し訳ありません！
サイトに技術的な問題が発生しています。',
'dberr-again'       => '数分間待った後、もう一度読み込んでください。',
'dberr-info'        => '（データベースサーバー：$1に接続できませんでした。）',
'dberr-usegoogle'   => '元に戻るまで、Googleを利用して検索することができます。',
'dberr-outofdate'   => 'それらが収集した内容は古い可能性があることに注意してください。',
'dberr-cachederror' => 'これは要求されたページのキャッシュされた複製で、古い可能性があります。',

);
