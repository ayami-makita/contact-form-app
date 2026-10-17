<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContactRequest;
use App\Models\Category;
use App\Models\Contact;
use App\Models\Tag;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    /**
     * PG01: お問い合わせフォーム入力ページを表示
     */
    public function index(Request $request)
    {
        if ($request->all()) {
            session()->flashInput($request->all());
        }

        $categories = Category::all();
        $tags = Tag::all();

        return view('contact.index', compact('categories', 'tags'));
    }

    /**
     * PG02: お問い合わせフォーム確認ページを表示
     */
    public function confirm(StoreContactRequest $request)
    {
        // 入力データをセッションに一時保存
        $request->flash();

        // バリデーションを通過したデータを取得
        $validated = $request->validated();

        $category = Category::find($validated['category_id']);

        // カテゴリ名やタグ名を画面に表示
        $tagIds = $validated['tag_ids'] ?? [];
        $tags = Tag::whereIn('id', $tagIds)->get();

        return view('contact.confirm', compact('validated', 'tags', 'category'));
    }

    /**
     * データベースに保存し、サンクページへリダイレクト
     */
    public function store(StoreContactRequest $request)
    {
        $data = $request->validated();

        // お問い合わせデータを保存
        $contactData = $data;
        $tagIds = $contactData['tag_ids'] ?? [];
        unset($contactData['tag_ids']);

        $contact = Contact::create($contactData);

        // 中間テーブル（contact_tag）にタグを紐付け
        if (!empty($tagIds)) {
            $contact->tags()->sync($tagIds);
        }

        return redirect('/thanks');
    }

    /**
     * PG03: サンクページを表示
     */
    public function thanks()
    {
        return view('contact.thanks');
    }
}