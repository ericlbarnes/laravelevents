<?php

namespace App\Http\Controllers;

use App\Event;
use Illuminate\Http\Request;

class ManageEventsController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function index()
    {
        return view('manage.index', [
            'pending' => Event::pending(),
            'future' => Event::future()->get()
        ]);
    }

    public function edit($id)
    {
        return view('manage.edit', [
            'event' => Event::findOrFail($id),
        ]);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'required',
            'description' => 'required',
            'url' => 'required|url',
            'price' => 'max:255',
            'location' => 'required',
            'starts_at' => 'required',
            'approved' => 'required',
        ]);

        $event = Event::findOrFail($id);

        $event->update([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'url' => $validated['url'],
            'price' => $validated['price'],
            'location' => $validated['location'],
            'starts_at' => strtotime($validated['starts_at']),
            'approved' => $validated['approved'],
        ]);

        return back()->withMessage('Event updated.');
    }

    public function destroy($id)
    {
        $event = Event::findOrFail($id);
        $event->delete();
        return back()->withMessage('Event deleted.');
    }
}
