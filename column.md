# コラム機能 実装ドキュメント

## 概要
算命学サテライトサイトにコラム記事の表示機能を実装。100の類似サイト展開に向けて、コラム機能の共通部分を外部API化し、中央集権型アーキテクチャを採用。

## アーキテクチャ概要

### 中央集権型システム
- **中央サーバー**: 共通処理とデータを一元管理（`uranai_common/`）
- **クライアントサイト**: APIを呼び出してコンテンツを表示
- **フォールバック機能**: API障害時もローカルデータで継続動作

### システム構成図
```
中央サーバー (uranai_common/)
├── API エンドポイント
├── データ管理
├── キャッシュ機能
└── マークダウン処理

↓ HTTP API ↓

クライアントサイト (webapp/)
├── API クライアント
├── フォールバック処理
└── テンプレート表示
```

## ファイル構成

### 1. 中央サーバー（`uranai_common/`）

#### `api/v1/index.php`
- **役割**: APIエントリーポイント
- **機能**: リクエストルーティング、レスポンス生成

#### `config/config.php`
- **役割**: 中央サーバー設定
- **内容**: DB設定、APIキー、キャッシュ設定

#### `lib/ColumnApiHandler.php`
- **役割**: APIリクエスト処理
- **機能**: エンドポイント分岐、認証、キャッシュ制御

#### `lib/ColumnDataManager.php`
- **役割**: データ管理
- **機能**: CSVデータ読み込み、フィルタリング、ソート

#### `lib/MarkdownParser.php`
- **役割**: マークダウン処理
- **機能**: Markdown→HTML変換

#### `lib/ApiResponse.php`
- **役割**: レスポンス統一
- **機能**: 成功/エラーレスポンス生成

#### `lib/CacheManager.php`
- **役割**: キャッシュ管理
- **機能**: ファイルベースキャッシュ、有効期限管理

#### `client/ColumnCentralizer.js`
- **役割**: JavaScript クライアントライブラリ
- **機能**: ブラウザ側API呼び出し、テンプレート処理

### 2. クライアントサイト（`webapp/`）

## 🔧 `/column/`でコラム表示するための必須作業チェックリスト

### **STEP 1: URLルーティング設定（最重要）**
#### `webapp/front_controller/parser/RewriteRequestUriParseClass.php`
**症状**: `/column/`にアクセスしても記事一覧が表示されない場合、まずここを確認

**必要な修正**: `prepareDefault()`メソッドに以下のcase文を追加
```php
case !empty($uri_param_list[0]) && $uri_param_list[0] == 'column' :
    if (!empty($uri_param_list[1])) {
        // /column/123/ の形式（記事詳細）
        $_GET['action'] = 'Column_detail';
        $_GET['id'] = $uri_param_list[1];
    } else {
        // /column/ の形式（記事一覧）
        $_GET['action'] = 'Column';
    }
    break;
```

**動作確認方法**:
1. `/column/` → `ColumnAction.php`が呼ばれるか
2. `/column/123/` → `Column_detailAction.php`が呼ばれ、`$_GET['id']='123'`が設定されるか

---

### **STEP 2: ファイル配置確認**

#### ✅ 必須ファイル一覧
- [ ] `webapp/lib/ColumnApiClient.php` - APIクライアント
- [ ] `webapp/controller/uranai_satellite/ColumnAction.php` - 記事一覧処理
- [ ] `webapp/controller/uranai_satellite/Column_detailAction.php` - 記事詳細処理
- [ ] `webapp/view/index/default_pc/column_list.html` - 一覧テンプレート
- [ ] `webapp/view/index/default_pc/column_detail.html` - 詳細テンプレート
- [ ] `column.csv` - プロジェクトルートに配置

---

### **STEP 3: 各コンポーネントの役割と設定**

#### `lib/ColumnApiClient.php`
- **役割**: PHPクライアントライブラリ
- **機能**: API呼び出し、フォールバック処理
- **設定項目**:
  - `api_endpoint`: 外部APIのURL（デフォルト: `https://uranai.flier.jp/uranai_common/api/v1`）
  - `site_id`: サイト識別子（デフォルト: `satellite_site`）
  - `cache_expiry`: キャッシュ有効期限（デフォルト: 3600秒）

#### `controller/uranai_satellite/ColumnAction.php`
- **役割**: コラム一覧ページの処理（更新）
- **URL**: `/column/`
- **主要機能**:
  - 外部APIからコラム一覧データを取得
  - API障害時はローカルCSVをフォールバック
  - ページネーション処理（1ページ50件）
  - テンプレート: `column_list`
- **デバッグログ**: `error_log()`でAPI呼び出し状況を記録

#### `controller/uranai_satellite/Column_detailAction.php`
- **役割**: コラム詳細ページの処理（更新）
- **URL**: `/column/{id}/`
- **主要機能**:
  - 外部APIからコラム詳細データを取得
  - API障害時はローカルCSVをフォールバック
  - マークダウン→HTML変換は中央サーバーで処理
  - テンプレート: `column_detail`
- **パラメータ**: `$_GET['id']`で記事IDを取得

#### `webapp/controller/uranai_satellite/TopAction.php`
- **役割**: トップページでのコラム最新4件表示
- **追加機能**:
  - `getLatestColumns()`: 最新4件取得メソッド
  - `formatPostDate()`: 日付フォーマット処理
  - `$disp_array['latest_columns']`: テンプレート変数に設定

---

### **STEP 4: トラブルシューティング**

#### 🚨 よくある問題と解決方法

**問題1**: `/column/`にアクセスしても404エラー
- **原因**: URLルーティング設定未完了
- **解決**: STEP 1の設定を確認・実装

**問題2**: ページは表示されるがコンテンツが空
- **原因1**: `column.csv`ファイルが存在しない、または形式不正
- **原因2**: 外部API接続失敗、かつフォールバック処理も失敗
- **解決**: 
  1. プロジェクトルートに`column.csv`があるか確認
  2. CSVの形式が正しいか確認（ヘッダー行含む）
  3. エラーログを確認

**問題3**: 記事詳細（`/column/123/`）が表示されない
- **原因**: URLルーティングで`$_GET['id']`が正しく設定されていない
- **解決**: STEP 1のルーティング設定を確認

**問題4**: 外部API接続エラー
- **原因**: API エンドポイントURL不正、またはネットワーク問題
- **解決**: 
  1. `ColumnApiClient.php`のエンドポイントURL確認
  2. フォールバック処理の動作確認
  3. エラーログでcURL エラー詳細を確認

---

### **STEP 5: 動作確認手順**

1. **基本動作確認**:
   - [ ] `/column/` - 記事一覧が表示される
   - [ ] `/column/160/` - 記事詳細が表示される（CSVの最初の記事ID）
   - [ ] `/` - トップページに最新記事4件が表示される

2. **API動作確認**:
   - [ ] 外部API接続成功時の動作
   - [ ] 外部API接続失敗時のフォールバック動作

3. **エラーログ確認**:
   - [ ] PHPエラーログでエラーが発生していないか
   - [ ] `ColumnAction::Execute called`等のデバッグログが出力されているか

### 2. ビューファイル（HTML/Smarty）

#### `webapp/view/index/default_pc/column_list.html`
- **役割**: コラム一覧ページのテンプレート
- **表示内容**:
  - ページタイトル
  - 記事総数表示
  - 記事一覧（タイトル、公開日、概要、キーワード）
  - ページネーション
  - CSS付き

#### `webapp/view/index/default_pc/column_detail.html`
- **役割**: コラム詳細ページのテンプレート
- **表示内容**:
  - 記事タイトル
  - 公開日
  - 記事本文（マークダウン→HTML変換済み）
  - 関連記事リンク

#### `webapp/view/index/default_pc/preview_rakuten.html`
- **役割**: トップページテンプレート（コラム表示機能追加）
- **追加箇所**: class="ow_new"とclass="ow_special"の間
- **表示内容**:
  - 最新コラム4件
  - 各記事の公開日、タイトル、概要、キーワード
  - コラム一覧へのリンク
  - 専用CSS付き

### 3. データファイル

#### `column.csv`
- **場所**: プロジェクトルート
- **構造**:
  ```
  id,title,seo_keywords,summary,content,post_date,created_date
  1,記事タイトル,キーワード1,記事概要,マークダウン本文,2024-01-01 10:00:00,2024-01-01 09:00:00
  ```
- **フィールド説明**:
  - `id`: 記事ID（ユニーク）
  - `title`: 記事タイトル
  - `seo_keywords`: SEOキーワード
  - `summary`: 記事概要
  - `content`: 記事本文（マークダウン形式）
  - `post_date`: 公開日時（空欄または未来日時は非表示）
  - `created_date`: 作成日時

## API仕様

### エンドポイント

#### 1. コラム一覧取得
- **URL**: `GET /uranai_common/api/v1/columns`
- **パラメータ**:
  - `site_id`: サイトID（必須）
  - `page`: ページ番号（オプション、デフォルト: 1）
  - `limit`: 1ページあたりの件数（オプション、デフォルト: 10）
  - `sort`: ソート順（オプション、デフォルト: 'post_date_desc'）

#### 2. コラム詳細取得
- **URL**: `GET /uranai_common/api/v1/columns/{id}`
- **パラメータ**:
  - `site_id`: サイトID（必須）

#### 3. 最新コラム取得
- **URL**: `GET /uranai_common/api/v1/columns/latest`
- **パラメータ**:
  - `site_id`: サイトID（必須）
  - `limit`: 取得件数（オプション、デフォルト: 4）

### レスポンス形式
```json
{
  "status": "success",
  "data": {
    // レスポンスデータ
  }
}
```

### エラーレスポンス
```json
{
  "status": "error", 
  "error": {
    "code": "ERROR_CODE",
    "message": "エラーメッセージ"
  }
}
```

## URL構造

### コラム関連URL
- **コラム一覧**: `/column/` または `/column/?page=2`
- **コラム詳細**: `/column/記事ID/` （例: `/column/1/`）
- **トップページ**: `/` （最新4件表示）


### API URL
- **API ベース**: `/uranai_common/api/v1/`
- **一覧取得**: `/uranai_common/api/v1/columns?site_id=xxx`
- **詳細取得**: `/uranai_common/api/v1/columns/1?site_id=xxx`
- **最新取得**: `/uranai_common/api/v1/columns/latest?site_id=xxx`

## 主要処理フロー

### 1. コラム一覧表示（API版）
1. `ColumnAction::Execute()` 実行
2. `ColumnApiClient::getColumnsList()` でAPI呼び出し
3. API成功時: レスポンスデータを使用
4. API失敗時: `getFallbackData()` でローカルCSV使用
5. ページネーション処理
6. テンプレートに渡してHTML出力

### 2. コラム詳細表示（API版）
1. `Column_detailAction::Execute()` 実行
2. URLパラメータから記事ID取得
3. `ColumnApiClient::getColumnDetail()` でAPI呼び出し
4. API成功時: 中央サーバーでHTML変換済みコンテンツ取得
5. API失敗時: `getFallbackData()` でローカル処理
6. テンプレートに渡してHTML出力

### 3. 中央サーバー処理フロー
1. `ColumnApiHandler::handleRequest()` でリクエスト受信
2. サイトID認証とパラメータバリデーション
3. `CacheManager` でキャッシュ確認
4. キャッシュ無し時: `ColumnDataManager` でデータ処理
5. `MarkdownParser` でHTML変換
6. `ApiResponse` で統一形式レスポンス生成

### 4. フォールバック処理フロー
1. API呼び出し失敗を検知
2. `ColumnApiClient::getFallbackData()` 実行
3. ローカルCSVからデータ読み込み
4. 既存の処理ロジックで表示データ生成
5. エラーログ出力

## CSS設計

### スタイル定義場所
- **コラム一覧ページ**: `column_list.html` 内の `<style>` タグ
- **トップページ**: `preview_rakuten.html` 内の `<style>` タグ

### 主要CSSクラス
- `.ow_column_list`: コラムセクション全体
- `.ow_column_list_title`: セクションタイトル
- `.ow_column_articles`: 記事リスト container
- `.ow_column_article_item`: 個別記事アイテム
- `.ow_column_article_title`: 記事タイトル
- `.ow_column_article_meta`: メタ情報（公開日）
- `.ow_column_article_summary`: 記事概要
- `.ow_column_article_keywords`: キーワード表示
- `.ow_column_read_more`: 続きを読むボタン
- `.ow_column_more_link_btn`: 一覧を見るボタン

## 機能仕様

### 公開制御
- `post_date` が空欄または未来日時の記事は非表示
- `date('Y-m-d H:i:s')` と比較して判定

### ソート順
- 投稿日時の降順（新しい記事が上）
- `usort()` で `post_date` フィールドを比較

### ページネーション
- 1ページあたり50件表示
- 総ページ数、現在ページ、記事総数を表示

### マークダウン変換
- 見出し（#, ##, ###）→ HTML の h1, h2, h3
- 段落の自動生成
- 改行の `<br>` 変換
- ```markdown ブロックの除去

### URL形式統一
- コラム詳細: `/column/ID/` 形式で統一
- 相対パス使用でベースURL非依存

## セキュリティ仕様

### API認証
- サイトIDによる認証
- 将来的にAPIキー認証を追加予定
- HTTPS通信の強制

### データ保護
- 入力値検証とサニタイズ
- HTMLエスケープ処理
- エラー情報の適切な制限

### アクセス制御
- `.htaccess` による設定ファイル保護
- ログファイルへの直接アクセス禁止
- キャッシュファイルへの直接アクセス禁止

## パフォーマンス最適化

### キャッシュ戦略
- ファイルベースキャッシュシステム
- デフォルト有効期限: 1時間
- 自動期限切れクリーンアップ

### API最適化
- レスポンス時間目標: 500ms未満
- 適切なHTTPステータスコード使用
- JSON圧縮対応

### フォールバック対応
- API障害時の自動切り替え
- ローカルデータでの継続動作
- エラーログによる問題追跡

## 開発・運用注意点


### 中央サーバー運用
- キャッシュディレクトリの書き込み権限設定
- ログファイルの定期ローテーション
- CSVファイルのバックアップ体制

### クライアントサイト設定
- API エンドポイント URL の正確な設定
- サイト ID の適切な設定
- フォールバック機能の動作確認

### CSVファイル管理
- 文字エンコーディング: UTF-8
- 改行コード: LF推奨
- カンマを含む場合はダブルクォートで囲む
- 記事ID の重複禁止

### モニタリング
- API レスポンス時間の監視
- エラーログの定期確認
- キャッシュ使用量の監視

## システム移行ガイド

### 既存サイトから中央API化への移行手順

1. **中央サーバー設置**
   - `uranai_common/` を適切なサーバーに配置
   - 必要なディレクトリ権限設定
   - `.htaccess` 設定確認

2. **クライアントサイト更新**
   - `webapp/lib/ColumnApiClient.php` を追加
   - コントローラーファイルを更新版に置換
   - API エンドポイント URL を設定

3. **動作確認**
   - API通信の確認
   - フォールバック機能の確認
   - パフォーマンステスト

4. **段階的展開**
   - 数サイトで先行テスト
   - 問題なければ全サイト展開

## 今後の拡張可能性

### 短期拡張案（中央API化活用）
- サイト別表示制御機能
- コンテンツ管理ダッシュボード
- アクセス統計機能
- A/Bテスト機能

### 中期拡張案
- データベース化（MySQL等）
- より高度なキャッシュシステム
- CDN活用による高速化
- 管理用WebUI

### 長期拡張案
- カテゴリ・タグ機能
- 全文検索機能
- 画像管理機能
- AIによる自動タグ付け
- レコメンデーション機能

### 技術的改善案
- GraphQL API対応
- リアルタイム更新機能
- マイクロサービス化
- クラウドネイティブ対応

## まとめ

この外部API化により、100のサテライトサイトでのコラム機能運用が以下のように改善されます：

- **運用効率向上**: 共通部分の修正を1回で全サイトに反映
- **パフォーマンス向上**: キャッシュ機能による高速化
- **可用性向上**: フォールバック機能による障害対応
- **拡張性確保**: 中央集権型アーキテクチャによる機能追加の容易さ