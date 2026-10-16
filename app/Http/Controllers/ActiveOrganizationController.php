<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ActiveOrganizationController extends Controller
{
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'organization_id' => [
                'required',
                'integer',
                'exists:organizations,id',
            ],
        ]);

        $organization = $request->user()
            ->organizations()
            ->whereKey($validated['organization_id'])
            ->firstOrFail();

        abort_unless(
            $organization->status === 'active',
            403,
            'La organización está desactivada.'
        );

        $request->session()->put(
            'active_organization_id',
            $organization->id
        );

        return to_route('organizations.index')
            ->with(
                'success',
                'Organización activa actualizada.'
            );
    }
}
