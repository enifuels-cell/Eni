<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    public function index(Request $request)
    {
        $query = Faq::query();
        $search = trim((string) $request->get('q')) ?: null;
        $category = $request->get('category');

        if ($search) {
            $query->search($search);
        }
        if ($category) {
            $query->where('category', $category);
        }

        $faqs = $query->orderBy('category')->orderBy('id')->get();

        return response()->json([
            'success' => true,
            'count' => $faqs->count(),
            'data' => $faqs->map(fn($f) => [
                'id' => $f->id,
                'question' => $f->question,
                'answer' => $f->answer,
                'category' => $f->category,
                'intent' => $f->intent,
            ])
        ]);
    }
}
