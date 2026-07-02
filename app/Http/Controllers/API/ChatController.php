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
        $conversations = Conversation::where('user_id', $request->user()->id)
            ->latest()
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $conversations
        ]);
    }
    public function messages(Request $request, $id) {
        $history = ChatHistory::where('user_id', $request->user()->id)
            ->where('conversation_id', $id)
            ->orderBy('created_at')
            ->get();

        $messages = [];

        foreach ($history as $chat) {

            $messages[] = [
                'role' => 'user',
                'content' => $chat->message,
                'created_at' => $chat->created_at,
            ];

            if ($chat->reply) {
                $messages[] = [
                    'role' => 'assistant',
                    'content' => $chat->reply,
                    'created_at' => $chat->updated_at,
                ];
            }
        }

        return response()->json([
            'status' => 'success',
            'conversation_id' => $id,
            'messages' => $messages,
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

        $context = "You are MBB AI Chatbot.

        Answer ONLY using the information below.
        If the question cannot be answered using the FAQ or Knowledge Base below, reply only with: 'This question is not related to our support topics.' Do not add any other text.

        FAQ:
        ".FAQ::all()->pluck('answer','question')."

        Knowledge Base:
        ".KnowledgeArticle::all()->pluck('content')->implode("\n");

        $reply = app('App\\Services\\AIService')->ask($question,$context);
        return response()->json([
            'reply'=>$reply
        ]);
        if ($request->filled('conversation_id')) {

            $conversation = Conversation::where('id', $request->conversation_id)
                ->where('user_id', auth()->id())
                ->firstOrFail();

        } else {

            // Create a new conversation
            $conversation = Conversation::create([
                'user_id' => auth()->id(),
                'title' => Str::limit($question, 40),
            ]);
        }

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

    public function getFAQs() {
        return response()->json([
            'status' => 'success',
            'data' => FAQ::latest()->get(),
        ]);
    }
}
