# Rese（リーズ） README

## 🚀 アプリケーション名
**Rese（リーズ）**
企業グループ運営の飲食店予約サービス（飲食店予約アプリの模擬案件）

## 🎯 作成した目的
外部の飲食店予約サービスは手数料が発生するため、
自社で予約サービスを保有し、コストを最適化することを目的としています。

- 初年度ユーザー数：10,000人を目標
- 設計〜コーディング〜テストまでを一人で担当

---

## 🌐 アプリケーションURL
（デプロイしていれば記載）

例）
- 開発環境: `http://localhost`
- ログイン情報: （必要であればここに記載）

---

## 🖥 使用技術

- 言語: PHP
- フレームワーク: Laravel 8.x
- データベース: MySQL
- 認証: Laravel Fortify / メール認証
- 決済: Stripe（Checkout）
- インフラ想定: AWS（EC2 / RDS / S3）
- その他: Docker（開発環境）

---

## 🧩 機能一覧

### 基本機能

- 会員登録
- ログイン / ログアウト
- ユーザー情報取得
- 飲食店一覧取得
- 飲食店詳細取得
- お気に入り登録 / 解除
- 予約作成
- 予約削除
- 検索機能
  - エリアで検索
  - ジャンルで検索
  - 店名で検索

### 追加実装機能（Advance）

- 予約変更機能（日時・人数をマイページから変更）
- 来店後の店舗評価（5段階評価 + コメント）
- レスポンシブデザイン（ブレイクポイント 768px）
- 権限管理
  - 利用者
  - 店舗代表者
  - 管理者
- 店舗代表者用管理画面
  - 店舗情報の作成・更新
  - 自店舗の予約情報一覧・詳細
- 管理者用画面
  - 店舗代表者アカウントの作成
- メール認証
- 管理画面からのお知らせメール送信
- リマインダーメール（予約当日の朝に自動送信）
- QRコード発行・照合（来店時に店舗側が確認）
- Stripe による決済
- 画像ストレージ（S3 利用を想定）

---

## 📄 画面一覧

| パス | 画面名 |
|------|--------|
| `/` | 飲食店一覧ページ |
| `/register` | 会員登録ページ |
| `/login` | ログインページ |
| `/thanks` | 会員登録サンクスページ |
| `/mypage` | マイページ（予約一覧・お気に入り一覧・予約変更など） |
| `/detail/{shop_id}` | 飲食店詳細ページ |
| `/done` | 予約完了ページ |

---

## 🔧 環境構築手順

```bash
git clone git@github.com:NanaeYuge/restaurant_reservation.git
cd restaurant_reservation

cd src
composer install
npm install

cp .env.example .env
php artisan key:generate

php artisan migrate --seed
npm run dev
php artisan serve
```

## 💳 決済機能（Stripe）


Stripe Checkout を利用してクレジットカード決済を行います。

- 実装箇所（一例）
  - `app/Http/Controllers/PaymentsController.php`
  - `routes/web.php`（`/checkout` など）

`.env` に以下のような Stripe のテストキーを設定して動作確認します。

```env
STRIPE_PUBLIC_KEY=pk_test_...
STRIPE_SECRET_KEY=sk_test_...
STRIPE_WEBHOOK_SECRET=whsec_...
```

※ 上の ```env ブロックの中の `pk_test_...` / `sk_test_...` / `whsec_...` は、実際に使っている Stripe テストキーに書き換えてください。

---

## README 用：テスト用ログイン情報セクション（Markdown）

実際に使っているメールアドレス・パスワードに書き換える前提で、テンプレを出すね👇

## 🔑 テスト用ログイン情報

動作確認用に、あらかじめ作成しているテストユーザーのログイン情報です。
（※ メールアドレス・パスワードは、実際のシーディング内容に合わせて書き換えてください）

### 管理者（Administrator）

| 役割 | メールアドレス | パスワード |
|------|----------------|------------|
| 管理者 | admin@example.com | password12345 |

### 店舗代表者（Shop Owner）

| 役割 | メールアドレス | パスワード |
|------|----------------|------------|
| 店舗代表者1 | owner@example.com | password12345 |
| 店舗代表者2(山田花子) | hanako@example.com | password |

### 一般ユーザー（Customer）

| 役割 | メールアドレス | パスワード |
|------|----------------|------------|
| 一般ユーザー1 | user1@example.com | password |
| 一般ユーザー2 | user2@example.com | password |

> 上記のアカウントは、Seeder などで作成したテストユーザーに対応させてください。
> 実際の動作確認では、各役割のユーザーでログインし、画面・機能の違いを確認できます。



