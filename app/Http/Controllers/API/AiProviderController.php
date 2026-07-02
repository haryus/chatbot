<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\AiProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class AiProviderController extends Controller
{
    /** List all configured providers */
    public function index()
    {
        return response()->json([
            'status' => 'success',
            'data'   => AiProvider::latest()->get(),
        ]);
    }

    /** Add a new provider */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:100',
            'provider' => 'required|in:openrouter,openai,gemini',
            'api_key'  => 'required|string',
            'model'    => 'required|string',
            'base_url' => 'nullable|url',
        ]);

        $provider = AiProvider::create($validated);

        return response()->json(['status' => 'success', 'data' => $provider], 201);
    }

    /** Update a provider's configuration */
    public function update(Request $request, $id)
    {
        $provider  = AiProvider::findOrFail($id);

        $validated = $request->validate([
            'name'     => 'sometimes|string|max:100',
            'provider' => 'sometimes|in:openrouter,openai,gemini',
            'api_key'  => 'sometimes|string',
            'model'    => 'sometimes|string',
            'base_url' => 'nullable|url',
        ]);

        $provider->update($validated);
        Cache::forget('active_ai_provider');

        return response()->json(['status' => 'success', 'data' => $provider]);
    }

    /** Delete a provider (cannot delete the active one) */
    public function destroy($id)
    {
        $provider = AiProvider::findOrFail($id);

        if ($provider->is_active) {
            return response()->json(['message' => 'Cannot delete the active provider. Activate another first.'], 422);
        }

        $provider->delete();

        return response()->json(['status' => 'success', 'message' => 'Provider deleted.']);
    }

    /** Set a provider as the active one used by the application */
    public function activate($id)
    {
        AiProvider::where('is_active', true)->update(['is_active' => false]);
        AiProvider::findOrFail($id)->update(['is_active' => true]);
        Cache::forget('active_ai_provider');

        return response()->json([
            'status'  => 'success',
            'message' => 'Provider activated.',
            'data'    => AiProvider::findOrFail($id),
        ]);
    }
}
