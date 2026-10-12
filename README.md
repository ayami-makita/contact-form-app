# profile-app-practice

## 概要
COACHTECH 教材 Tutorial 9-1「Laravel基礎ハンズオン」で作成した成果物です。
（**ここに、何を作ったかを1〜2行で書きましょう**）

## 使用技術
- PHP 8.x
- Laravel 10.x
- Blade テンプレート

## 学んだこと
- ルーティング→コントローラー→ビューの流れ
- コントローラからビューへのデータの受け渡し
- 

## 動作確認
1. クローン
```bash
git clone https://github.com/ayami-makita/profile-app-practice2
```

2. CDで移動
```bash
cd profile-app-practice2
```

3. sail起動・確認
```bash
./vendor/bin/sail up -d
```
sailを起動してください。
```bash
./vendor/bin/sail ps
```
コンテナの状態を確認し、すべてのコンテナがUp状態になっていればOKです。

4. アプリケーションキーを生成
```bash
./vendor/bin/sail artisan key:generate
```


5. 動作確認
[自己紹介ページ](http://localhost/profile)
→プロフィール画面が表示されれば、環境構築は成功です。