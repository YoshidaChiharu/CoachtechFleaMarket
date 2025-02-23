<div id="top"></div>

## 目次

1. [アプリケーション概要](#アプリケーション概要)
2. [アプリケーションURL](#アプリケーションURL)
3. [機能一覧](#機能一覧)
4. [使用技術一覧](#使用技術一覧)
5. [テーブル設計](#テーブル設計)
6. [ER図](#ER図)
7. [環境構築手順](#環境構築手順)
8. [補足事項](#補足事項)

## アプリケーション概要

アプリケーション名 : coachtechフリマ<br>
概要：フリーマーケットアプリ<br>
![CoachtechFleaMarket_top](/CoachtechFleaMarket_top.png)

## アプリケーションURL

- 開発環境：[http://localhost/](http://localhost/)
    - phpMyAdmin：[http://localhost:8888/](http://localhost:8888/)
- 本番環境：[http://ec2-57-180-170-88.ap-northeast-1.compute.amazonaws.com](http://ec2-57-180-170-88.ap-northeast-1.compute.amazonaws.com)

## 機能一覧

- 会員登録（メール認証）
- ログイン
- ログアウト
- 商品(おすすめ)一覧取得
- 商品(お気に入り)一覧取得
- 商品検索（検索対象：商品名／商品説明文／カテゴリー／商品の状態）
- 商品詳細取得
- ユーザー情報取得
- ユーザー購入商品一覧取得
- ユーザ出品商品一覧取得
- プロフィール変更
- 商品お気に入り追加
- 商品お気に入り削除
- 商品コメント追加
- 商品コメント削除
- 商品の出品
- 商品の購入
- 支払い方法の変更（クレジットカード／コンビニ／銀行振込）
- 配送先の変更（プロフィール登録住所とは別の配送先を「登録／編集／削除」が可能）

↓ 以下管理画面機能
- ユーザー検索（検索対象：ID／ユーザー名／メールアドレス／登録日）
- ユーザー削除
- コメント検索（検索対象：ID／商品名／投稿者名／コメント本文／投稿日）
- コメント削除
- 利用者へ向けたお知らせメールの一斉送信

↓以下内部機能
- PHP Unit を使用した各ページの基本機能のテスト
- Circle CIを使用した「PHP Unit テスト」＆「AWS EC2インスタンスへのデプロイ」の自動化

## 使用技術一覧

| 言語・フレームワーク  | バージョン |
| --------------------- | ---------- |
| Laravel               | 11.27.2    |
| PHP                   | 8.3.7      |
| NGINX                 | 1.26.0     |
| MySQL                 | 8.0.37     |
| inertia.js            | 1.0.0      |
| Vue.js                | 3.5.13     |
| tailwindCSS           | 3.4.16     |

## テーブル設計

![CoachtechFleaMarket_tables](/CoachtechFleaMarket_tables.png)

## ER図

![er_CoachtechFleaMarket](/er_CoachtechFleaMarket.png)

## 環境構築手順

1. **Dockerテンプレートのリモートリポジトリをクローンする**
```
git clone git@github.com:YoshidaChiharu/docker-template-LEMP-Breeze.git
```
2. **ディレクトリを移動し、srcディレクトリを作成**
```
cd docker-template-LEMP-Breeze
```
```
mkdir src
```
3. **srcディレクトリのパーミッションを変更**
```
sudo chmod 777 src
```
4. **ディレクトリを移動し、本プロジェクトのリモートリポジトリをクローンする**
```
cd src
```
```
git clone git@github.com:YoshidaChiharu/CoachtechFleaMarket.git
```
5. **ディレクトリを移動し、Dockerコンテナを作成**
```
cd ..
```
```
docker-compose up -d --build
```
6. **`composer install` コマンドでパッケージをインストール**
```
docker-compose exec php-fpm bash
```
```
composer install
```
7. **`npm install` コマンドでパッケージをインストール**
```
npm install
```
8. **Vue.jsのビルド**
```
npm run build
```
9. **.envファイルを作成**
```
cp .env.local .env
```
10. **アプリケーションキーを生成**
```
php artisan key:generate
```
11. **シンボリックリンクの作成**
```
php artisan storage:link
```
12. **テーブル作成**
```
php artisan migrate
```
13. **ダミーデータ作成**
```
php artisan db:seed
```
14. **パーミッションを変更**
```
chmod -R 777 *
```
15. **Dockerコンテナを再起動**
```
exit
```
```
docker-compose restart
```

## 補足事項

- 動作確認用アカウント情報
    - 管理者アカウント\
        メールアドレス：admin@example.com\
        パスワード：password
    - 一般アカウント\
        メールアドレス：test@example.com\
        パスワード：password
- Stripeテスト用クレジットカード\
    [https://docs.stripe.com/testing?locale=ja-JP#cards](https://docs.stripe.com/testing?locale=ja-JP#cards)

<p align="right">(<a href="#top">トップへ</a>)</p>
