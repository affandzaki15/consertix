<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;

class ContactMessagesController extends Controller
{
    /**
     * Display a listing of contact messages
     */
    public function index()
    {
        $messages = ContactMessage::orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.messages.index', compact('messages'));
    }

    /**
     * Display a specific contact message
     */
    public function show($id)
    {
        $message = ContactMessage::findOrFail($id);
        return view('admin.messages.show', compact('message'));
    }

    /**
     * Delete a contact message
     */
    public function destroy($id)
    {
        try {
            $message = ContactMessage::findOrFail($id);
            $message->delete();
            return back()->with('success', 'Pesan berhasil dihapus');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus pesan: ' . $e->getMessage());
        }
    }
}
