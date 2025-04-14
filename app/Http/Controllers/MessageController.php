<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function index()
    {
        return response()->json(Message::all());
    }

    public function show($id)
    {
        $message = Message::find($id);
        if (!$message) {
            return response()->json(['message' => 'Message not found'], 404);
        }
        return response()->json($message);
    }

    public function store(Request $request)
    {
        $request->validate([
            'to_number' => 'required|string|max:15',
            'from_number' => 'required|string|max:15',
            'message_text' => 'required|string',
            'sent_date' => 'required|date',
        ]);

        $message = Message::create([
            'to_number' => $request->to_number,
            'from_number' => $request->from_number,
            'message_text' => $request->message_text,
            'sent_date' => $request->sent_date,
        ]);

        return response()->json([
            'message' => 'Message created successfully!',
            'id' => $message->id,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $message = Message::find($id);
        if (!$message) {
            return response()->json(['message' => 'Message not found'], 404);
        }

        $request->validate([
            'to_number' => 'sometimes|string|max:15',
            'from_number' => 'sometimes|string|max:15',
            'message_text' => 'sometimes|string',
            'sent_date' => 'sometimes|date',
        ]);

        $message->update($request->only([
            'to_number', 'from_number', 'message_text', 'sent_date'
        ]));

        return response()->json([
            'message' => 'Message updated successfully!',
            'data' => $message,
        ]);
    }

    public function destroy($id)
    {
        $message = Message::find($id);
        if (!$message) {
            return response()->json(['message' => 'Message not found'], 404);
        }

        $message->delete();

        return response()->json(['message' => 'Message deleted successfully!']);
    }
}
