<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class ProfileController extends Controller
{
    public function index(User $user)
    {
        return Inertia::render('Profile/View', [
            'mustVerifyEmail' => $user instanceof MustVerifyEmail,
            'status' => session('status'),
            'success' => session('success'),
            'user' => new UserResource($user)
        ]);
    }
    /**
     * Display the user's profile form.
     */
    //    public function edit(Request $request): Response
    //    {
    //        return Inertia::render('Profile/Edit', [
    //            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
    //            'status' => session('status'),
    //        ]);
    //    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request)
    {
        $request->user()->fill($request->validated());

        if($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile', $request->user())->with('success', 'Your profile details were updated.');
//        return Redirect::route('profile.edit');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function updateImage(Request $request, User $user)
    {
        $data = $request->validate([
            'cover' => ['image', 'nullable', 'mimes:jpeg,jpg,png,webp'],
            'avatar' => ['image', 'nullable']
        ]);
        $user = $request->user();

        $success = '';

        /** @var UploadedFile $cover */
        $cover = $data['cover'] ?? null;
        $avatar = $data['avatar'] ?? null;
        if($cover) {
            $oldPath = $user->cover_path;
            $folderName = 'user-' . $user->id . '/cover';
            $path = Storage::disk('public')->put($folderName, $cover);
            //            $path = $cover->store($folderName, 'public');
            $user->update(['cover_path' => $path]);
            $success = 'Your cover image has been updated';
            if($oldPath) {
                Storage::disk('public')->delete($oldPath);
            }
        }

        else if($avatar) {
            $oldPath = $user->avatar_path;
            $folderName = 'user-' . $user->id . '/avatar';
            $path = Storage::disk('public')->put($folderName, $avatar);
            $user->update(['avatar_path' => $path]);
            $success = 'Your avatar image has been updated';

            if($oldPath) {
                Storage::disk('public')->delete($oldPath);
            }
        }

        //        session('success', 'Cover image has been updates');

        return back()->with('success', $success);
    }
}
