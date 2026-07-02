<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\SupportMessage;
use App\Models\SupportTicket;
use Illuminate\Http\Request;

class LiveSupportController extends Controller
{
    public function start(Request $request) {
        $ticket = SupportTicket::create([
            'client_id' => $request->user()->id,
            'agent_id'  => null,
            'status'    => 'pending',
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Live support session started. Please wait for an agent.',
            'ticket'  => $ticket,
        ], 201);
    }
    public function mySessions(Request $request) {
        $tickets = SupportTicket::where('client_id', $request->user()->id)
            ->latest()
            ->get();

        return response()->json([
            'status' => 'success',
            'data'   => $tickets,
        ]);
    }
    public function messages(Request $request, $id) {
        $ticket = SupportTicket::where('id', $id)
            ->where(function ($q) use ($request) {
                $q->where('client_id', $request->user()->id)
                  ->orWhere('agent_id', $request->user()->id)
                  ->orWhere(fn($q2) => $q2->where(fn($q3) => $q3->whereHas('client', fn($q4) => $q4->where('role', '!=', 'admin'))));
            })
            ->firstOrFail();

        if ($request->user()->role === 'admin') {
            $ticket = SupportTicket::findOrFail($id);
        }

        $messages = SupportMessage::where('ticket_id', $id)
            ->with('sender:id,name,role')
            ->orderBy('created_at')
            ->get();

        return response()->json([
            'status'  => 'success',
            'ticket'  => $ticket,
            'messages' => $messages,
        ]);
    }
    public function sendMessage(Request $request, $id) {
        $request->validate([
            'message' => 'required|string',
        ]);

        $user = $request->user();

        $ticket = SupportTicket::findOrFail($id);

        if ($ticket->client_id !== $user->id && $ticket->agent_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        if ($ticket->status === 'closed') {
            return response()->json(['message' => 'This session is closed.'], 422);
        }

        $message = SupportMessage::create([
            'ticket_id' => $ticket->id,
            'sender_id' => $user->id,
            'message'   => $request->message,
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => $message->load('sender:id,name,role'),
        ]);
    }
    public function close(Request $request, $id) {
        $user   = $request->user();
        $ticket = SupportTicket::findOrFail($id);

        if ($ticket->client_id !== $user->id && $ticket->agent_id !== $user->id && $user->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        $ticket->update(['status' => 'closed']);

        return response()->json([
            'status'  => 'success',
            'message' => 'Session closed.',
        ]);
    }
    public function sessions(Request $request) {
        $status  = $request->query('status'); // optional filter: pending, active, closed
        $query   = SupportTicket::with('client:id,name,email', 'agent:id,name,email')->latest();

        if ($status) {
            $query->where('status', $status);
        }

        return response()->json([
            'status' => 'success',
            'data'   => $query->get(),
        ]);
    }
    public function join(Request $request, $id) {
        $ticket = SupportTicket::findOrFail($id);

        if ($ticket->status !== 'pending') {
            return response()->json(['message' => 'Session is not available to join.'], 422);
        }

        $ticket->update([
            'agent_id' => $request->user()->id,
            'status'   => 'active',
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'You have joined the session.',
            'ticket'  => $ticket->fresh(),
        ]);
    }
}
