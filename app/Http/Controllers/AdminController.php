<?php

namespace App\Http\Controllers;

use App\Http\Requests\IndexContactRequest;
use App\Models\Category;
use App\Models\Contact;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * 管理画面 お問い合わせ一覧・検索ページ
     */
    public function index(IndexContactRequest $request)
    {
        $query = Contact::with(['category', 'tags']);

        // キーワード検索（名前の姓・名、またはメールアドレスの部分一致）
        if ($request->filled('keyword')) {
            $keyword = $request->input('keyword');
            $query->where(function ($q) use ($keyword) {
                $q->where('first_name', 'like', "%{$keyword}%")
                  ->orWhere('last_name', 'like', "%{$keyword}%")
                  ->orWhere('email', 'like', "%{$keyword}%");
            });
        }

        // 性別検索（0以外の場合のみ絞り込む）
        $gender = $request->input('gender');
        if (!is_null($gender) && $gender !== '' && $gender !== '0') {
            $query->where('gender', $gender);
        }

        // カテゴリ検索
        if ($categoryId = $request->validated('category_id')) {
            $query->where('category_id', $categoryId);
        }

        // 日付検索
        if ($date = $request->validated('date')) {
            $query->whereDate('created_at', $date);
        }

        // 7件ごとにページネーション表示
        $contacts = $query->latest()->paginate(7)->withQueryString();

        // カテゴリ一覧
        $categories = Category::all();
        $tags = \App\Models\Tag::all();

        return view('admin.index', compact('contacts', 'categories', 'tags'));
    }

    /**
     * PG05-2:管理画面 お問い合わせ詳細ページ
     */
    public function show($id)
    {
        // Eager LoadingでN+1問題を避ける
        $contact = Contact::with(['category', 'tags'])->findOrFail($id);

        return view('admin.show', compact('contact'));
    }

    /**
     * お問い合わせデータの削除
     */
    public function destroy(Request $request, $id)
    {
        $contact = Contact::findOrFail($id);
        $contact->delete();

        return redirect()->route('admin.index')->with('success', 'お問い合わせを削除しました。');
    }
}