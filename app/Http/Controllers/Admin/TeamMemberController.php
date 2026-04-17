<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TeamMember;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class TeamMemberController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Admin/TeamMembers', [
            'items' => TeamMember::query()
                ->orderBy('sort_order')
                ->paginate(10)
                ->withQueryString(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        TeamMember::create($this->validatedData($request));

        return back();
    }

    public function update(Request $request, TeamMember $teamMember): RedirectResponse
    {
        $oldPhoto = $teamMember->photo;

        $teamMember->update($this->validatedData($request));

        if ($request->hasFile('photo_file')) {
            $this->deleteStoredFile($oldPhoto);
        }

        return back();
    }

    public function destroy(TeamMember $teamMember): RedirectResponse
    {
        $this->deleteStoredFile($teamMember->photo);

        $teamMember->delete();

        return back();
    }

    /**
     * @return array<string, mixed>
     */
    private function validatedData(Request $request): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'role' => ['required', 'string', 'max:255'],
            'photo_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'description' => ['nullable', 'string'],
            'linkedin' => ['nullable', 'url', 'max:255'],
            'github' => ['nullable', 'url', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:65535'],
            'is_active' => ['boolean'],
        ]);

        $data['social_links'] = [
            'linkedin' => $data['linkedin'] ?? null,
            'github' => $data['github'] ?? null,
        ];
        unset($data['linkedin'], $data['github'], $data['photo_file']);

        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['is_active'] = (bool) ($data['is_active'] ?? false);

        if ($request->hasFile('photo_file')) {
            $data['photo'] = $this->publicStorageUrl(
                $request->file('photo_file')->store('team-members', 'public')
            );
        }

        return $data;
    }

    private function publicStorageUrl(string $path): string
    {
        return '/storage/'.$path;
    }

    private function deleteStoredFile(?string $path): void
    {
        if (! $path) {
            return;
        }

        $publicPath = parse_url($path, PHP_URL_PATH) ?: $path;

        if (! str_starts_with($publicPath, '/storage/')) {
            return;
        }

        Storage::disk('public')->delete(Str::after($publicPath, '/storage/'));
    }
}
