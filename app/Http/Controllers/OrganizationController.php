<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class OrganizationController extends Controller
{
    public function index(Request $request): Response
    {
        $organizations = $request->user()
            ->organizations()
            ->select(
                'organizations.id',
                'organizations.name',
                'organizations.slug',
                'organizations.status'
            )
            ->orderBy('organizations.name')
            ->get()
            ->map(fn (Organization $organization) => [
                'id' => $organization->id,
                'name' => $organization->name,
                'slug' => $organization->slug,
                'status' => $organization->status,
                'role' => $organization->pivot->role,
            ]);

        return Inertia::render('organizations/Index', [
            'organizations' => $organizations,
        ]);
    }

    /**
     * @throws \Throwable
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'min:3', 'max:150'],
        ]);

        DB::transaction(function () use ($request, $validated) {
            $organization = Organization::create([
                'name' => $validated['name'],
                'slug' => (string) Str::uuid(),
                'status' => 'active',
            ]);

            $organization->users()->attach(
                $request->user()->id,
                ['role' => 'owner']
            );
        });

        return to_route('organizations.index')
            ->with('success', 'Organización creada correctamente.');
    }
}
