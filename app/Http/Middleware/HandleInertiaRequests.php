<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Inspiring;
use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        [$message, $author] = str(Inspiring::quotes()->random())->explode('-');

        return array_merge(parent::share($request), [
            ...parent::share($request),
            'name' => config('app.name'),
            'quote' => ['message' => trim($message), 'author' => trim($author)],
            'auth' => [
                'user' => $request->user(),
            ],

            'activeOrganization' => function () use ($request) {
                $user = $request->user();

                if (!$user) {
                    return null;
                }

                $organizationId = $request->session()
                    ->get('active_organization_id');

                if (!$organizationId) {
                    return null;
                }

                $organization = $user->organizations()
                    ->whereKey($organizationId)
                    ->where('organizations.status', 'active')
                    ->first();

                if (!$organization) {
                    return null;
                }

                return [
                    'id' => $organization->id,
                    'name' => $organization->name,
                    'role' => $organization->pivot->role,
                ];
            },

            'availableOrganizations' => function () use ($request) {
                $user = $request->user();

                if (!$user) {
                    return [];
                }

                return $user->organizations()
                    ->where('organizations.status', 'active')
                    ->orderBy('organizations.name')
                    ->get()
                    ->map(fn ($organization) => [
                        'id' => $organization->id,
                        'name' => $organization->name,
                        'role' => $organization->pivot->role,
                    ])
                    ->values()
                    ->all();
            },
        ]);

    }
}
