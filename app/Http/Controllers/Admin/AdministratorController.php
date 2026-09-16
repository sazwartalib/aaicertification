<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreAdministratorRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

/**
 * Administrator accounts. Public sign-up does not exist: only a signed-in
 * administrator can create another one.
 */
class AdministratorController extends Controller
{
    public function index(Request $request): Response
    {
        $search = $request->string('search')->trim()->toString();

        return Inertia::render('Admin/Administrators/Index', [
            'filters' => ['search' => $search],
            'administrators' => User::query()
                ->when($search !== '', fn ($query) => $query->where(fn ($inner) => $inner
                    ->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")))
                ->orderBy('name')
                ->paginate(15)
                ->withQueryString()
                ->through(fn (User $user): array => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'isCurrentUser' => $user->is($request->user()),
                    'createdAt' => $user->created_at->toFormattedDateString(),
                    'deleteUrl' => route('admin.administrators.destroy', $user),
                ]),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Admin/Administrators/Form');
    }

    public function store(StoreAdministratorRequest $request): RedirectResponse
    {
        $administrator = User::create($request->validated());

        return redirect()
            ->route('admin.administrators.index')
            ->with('success', $administrator->name.' can now sign in to the admin console.');
    }

    public function destroy(Request $request, User $administrator): RedirectResponse
    {
        if ($administrator->is($request->user())) {
            return back()->with('error', 'You cannot remove your own account.');
        }

        if (User::query()->count() <= 1) {
            return back()->with('error', 'The last administrator account cannot be removed.');
        }

        $administrator->delete();

        return redirect()
            ->route('admin.administrators.index')
            ->with('success', 'Administrator removed.');
    }
}
