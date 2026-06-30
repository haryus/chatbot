<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\KnowledgeArticle;
use Illuminate\Http\Request;

class KnowledgeArticleController extends Controller
{
    public function index()
    {
        return response()->json([
            'status' => 'success',
            'data' => KnowledgeArticle::latest()->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'project' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $article = KnowledgeArticle::create($validated);

        return response()->json([
            'status' => 'success',
            'data' => $article,
        ], 201);
    }

    public function show(KnowledgeArticle $knowledgeArticle)
    {
        return response()->json([
            'status' => 'success',
            'data' => $knowledgeArticle,
        ]);
    }

    public function update(Request $request, KnowledgeArticle $knowledgeArticle)
    {
        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'content' => 'sometimes|required|string',
            'project' => 'sometimes|required|string|max:255',
        ]);

        $knowledgeArticle->update($validated);

        return response()->json([
            'status' => 'success',
            'data' => $knowledgeArticle->fresh(),
        ]);
    }

    public function destroy(KnowledgeArticle $knowledgeArticle)
    {
        $knowledgeArticle->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Knowledge article deleted successfully',
        ]);
    }
}
