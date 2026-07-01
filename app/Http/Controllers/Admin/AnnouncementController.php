<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AnnouncementController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('search'));

        return view('admin.management.announcements.index', [
            'announcements' => Announcement::query()
                ->when($search !== '', function ($query) use ($search) {
                    $query->where(function ($query) use ($search) {
                        $query->where('headline', 'like', "%{$search}%")
                            ->orWhere('description', 'like', "%{$search}%")
                            ->orWhereDate('event_date', $search);
                    });
                })
                ->latest('event_date')
                ->paginate(10)
                ->withQueryString(),
            'search' => $search,
        ]);
    }

    public function create()
    {
        return view('admin.management.announcements.create', [
            'announcement' => new Announcement(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $this->validateAnnouncement($request);

        if ($request->hasFile('caption')) {
            $validated['caption_path'] = $request->file('caption')->store('announcements', 'public');
        }

        unset($validated['caption']);

        Announcement::create($validated);

        \App\Models\ActivityLog::log('create', 'Announcements', "Created announcement: " . $validated['headline']);

        return redirect()
            ->route('admin.announcements.index')
            ->with('success', 'Announcement created successfully.');
    }

    public function edit(Announcement $announcement)
    {
        return view('admin.management.announcements.edit', [
            'announcement' => $announcement,
        ]);
    }

    public function update(Request $request, Announcement $announcement)
    {
        $validated = $this->validateAnnouncement($request);

        if ($request->hasFile('caption')) {
            if ($announcement->caption_path) {
                Storage::disk('public')->delete($announcement->caption_path);
            }

            $validated['caption_path'] = $request->file('caption')->store('announcements', 'public');
        }

        unset($validated['caption']);

        $announcement->update($validated);

        \App\Models\ActivityLog::log('update', 'Announcements', "Updated announcement: " . $announcement->headline);

        return redirect()
            ->route('admin.announcements.index')
            ->with('success', 'Announcement updated successfully.');
    }

    public function destroy(Announcement $announcement)
    {
        $headline = $announcement->headline;

        if ($announcement->caption_path) {
            Storage::disk('public')->delete($announcement->caption_path);
        }

        $announcement->delete();

        \App\Models\ActivityLog::log('delete', 'Announcements', "Deleted announcement: " . $headline);

        return redirect()
            ->route('admin.announcements.index')
            ->with('success', 'Announcement deleted successfully.');
    }

    private function validateAnnouncement(Request $request): array
    {
        return $request->validate([
            'caption' => ['nullable', 'image', 'max:4096'],
            'event_date' => ['required', 'date'],
            'headline' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
        ]);
    }
}
