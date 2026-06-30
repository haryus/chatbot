<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\FAQ;
use Illuminate\Http\Request;

class FAQController extends Controller
{
    public function index()
    {
        return response()->json([
            'status' => 'success',
            'data' => FAQ::latest()->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'question' => 'required|string|max:255',
            'project' => 'required|string|max:255',
            'answer' => 'required|string',
        ]);

        $faq = FAQ::create($validated);

        return response()->json([
            'status' => 'success',
            'data' => $faq,
        ], 201);
    }

    public function show(FAQ $faq)
    {
        return response()->json([
            'status' => 'success',
            'data' => $faq,
        ]);
    }

    public function update(Request $request, FAQ $faq) {
        $validated = $request->validate([
            'question' => 'sometimes|required|string|max:255',
            'project' => 'sometimes|required|string|max:255',
            'answer' => 'sometimes|required|string',
        ]);

        $faq->update($validated);

        return response()->json([
            'status' => 'success',
            'data' => $faq->fresh(),
        ]);
    }

    public function destroy(FAQ $faq)
    {
        $faq->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'FAQ deleted successfully',
        ]);
    }
}
