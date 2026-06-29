<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FAQ;
use App\Models\KnowledgeArticle;
use App\Models\ChatHistory;
use App\Models\Conversation;
use Illuminate\Support\Str;


class ChatController extends Controller
{
    public function conversations(Request $request) {
        $conversations = ChatHistory::where('user_id', $request->user()->id)
            ->distinct('conversation_id')
            ->orderBy('created_at', 'desc')
            ->get(['conversation_id', 'created_at']);

        return response()->json([
            'status' => 'success',
            'data' => $conversations
        ]);
    }
    public function messages(Request $request, $id) {
        $messages = ChatHistory::where('user_id', $request->user()->id)
            ->where('conversation_id', $id)
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $messages
        ]);
    }

    public function deleteConversation(Request $request, $id) {
        $deleted = ChatHistory::where('user_id', $request->user()->id)
            ->where('conversation_id', $id)
            ->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Conversation deleted successfully',
            'deleted_count' => $deleted
        ]);
    }
    public function newConversation(Request $request) {
        $validated = $request->validate([
            'message' => 'required|string',
        ]);

        $conversationId = uniqid('conv_');

        $chatHistory = ChatHistory::create([
            'user_id' => $request->user()->id,
            'conversation_id' => $conversationId,
            'message' => $validated['message'],
            'reply' => null,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Conversation created successfully',
            'data' => $chatHistory
        ], 201);
    }

    public function send(Request $request) {
        $question = $request->message;

        $context = "You are MBB Customer Support.

        Answer ONLY using the information below.

        FAQ:
        ".FAQ::all()->pluck('answer','question')."

        Knowledge Base:
        ".KnowledgeArticle::all()->pluck('content')->implode("\n");

        $reply = app('App\\Services\\AIService')->ask($question,$context);
        $conversation = Conversation::create([
            'user_id' => auth()->id(),
            'title' => Str::limit($question, 40),
        ]);
        ChatHistory::create([
            'user_id'=>auth()->id(),
            'conversation_id' => $conversation->id,
            'message'=>$question,
            'reply'=>$reply
        ]);

        return response()->json([
            'reply'=>$reply
        ]);
    }
}
