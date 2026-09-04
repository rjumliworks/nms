<?php

namespace App\Services\Profile;

use App\Models\User;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class SaveClass
{
    public function save($request){

        $user = User::find(\Auth::user()->id);
        $image = $request->file('image');
        $manager = new ImageManager(new Driver());

        // Read image
        $img = $manager->read($image);

        // Resize + convert to webp
        $img->cover(300, 300);
        $webp = $img->toWebp(80);

        $filename = $user->username.'.webp';
        $path = $image->storeAs('avatars', $filename, 'public');

        $user->profile->avatar = $path;
        $user->profile->save();

        return [
            'data' => [],
            'message' => 'Profile picture updated successfully.',
            'info' => "The user's profile image has been changed to the new photo."
        ];
    }

    public function update($request){
        $user = User::find(\Auth::user()->id);
        $profile = $user->profile;
        $profile->firstname = $request->firstname;
        $profile->middlename = $request->middlename;
        $profile->lastname = $request->lastname;
        $profile->mobile = $request->mobile;
        $profile->save();

        $data = User::with('profile')->find(\Auth::user()->id);
        return [
            'data' => $data,
            'message' => 'Profile updated successfully.',
            'info' => "Your profile information has been updated."
        ];
    }

    public function destroy($request)
    {
        if (!Auth::guard('web')->validate([
            'email' => $request->user()->email,
            'password' => $request->password,
        ])) {
            throw ValidationException::withMessages([
                'password' => __('auth.password'),
            ]);
        }
        $this->deleteOtherSessionRecords($request);
        return back(303);
    }

    protected function deleteOtherSessionRecords(Request $request)
    {
        if (config('session.driver') !== 'database') {
            return;
        }
        \DB::connection(config('session.connection'))->table(config('session.table', 'sessions'))
            ->where('user_id', $request->user()->getAuthIdentifier())
            ->where('id', '!=', $request->session()->getId())
            ->delete();
    }

}
