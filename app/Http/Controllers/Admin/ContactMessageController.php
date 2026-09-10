<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;

class ContactMessageController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', ContactMessage::class);

        $messages = ContactMessage::orderBy('is_read')->latest()->paginate(15);

        return view('admin.messages.index', compact('messages'));
    }

    public function show(ContactMessage $message)
    {
        $this->authorize('view', $message);

        if (! $message->is_read) {
            $message->update(['is_read' => true]);
        }

        return view('admin.messages.show', compact('message'));
    }

    public function update(ContactMessage $message)
    {
        $this->authorize('update', $message);

        $message->update(['is_read' => ! $message->is_read]);

        return back()->with('success', 'Status pesan diperbarui.');
    }

    public function destroy(ContactMessage $message)
    {
        $this->authorize('delete', $message);

        $message->delete();

        return redirect()->route('admin.messages.index')->with('success', 'Pesan berhasil dihapus.');
    }
}
