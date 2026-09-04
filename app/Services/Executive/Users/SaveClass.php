<?php

namespace App\Services\Executive\Users;

use App\Models\User;
use App\Http\Resources\Executive\UserResource;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Facades\Storage;

class SaveClass
{
    public function store($request){
        $data = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => bcrypt(str()->random(16)),
            'is_active' => 0,
            'must_change' => 1,
        ]);
        $data->profile()->create([
            'firstname' => $request->firstname,
            'middlename' => $request->middlename,
            'lastname' => $request->lastname,
            'mobile' => $request->mobile,
        ]);
        if ($request->role_id) {
            $data->myroles()->create([
                'role_id' => $request->role_id,
                'added_by' => \Auth::id(),
                'is_active' => 1,
            ]);
        }

        return [
            'data' => new UserResource($data->load('profile','myroles.role')),
            'message' => 'User creation was successful!',
            'info' => "You've successfully created an account for the user."
        ];
    }

    public function update($request){
        $data = User::with('profile')->where('id',$request->id)->first();
        $data->profile->update($request->only('firstname','middlename','lastname','mobile'));

        return [
            'data' => new UserResource($data->load('profile','myroles.role')),
            'message' => 'User update was successful!',
            'info' => "You've successfully updated the selected user."
        ];
    }

    public function avatar($request){
        $user = User::with('profile')->findOrFail($request->id);

        $manager = new ImageManager(new Driver());
        $encoded = $manager->read($request->file('image'))->cover(300, 300)->toWebp(80);

        $path = 'avatars/' . $user->username . '.webp';
        Storage::disk('public')->put($path, (string) $encoded);

        $user->profile->avatar = $path;
        $user->profile->save();

        return [
            'data' => new UserResource($user->load('profile','myroles.role')),
            'message' => 'Profile picture updated successfully.',
            'info' => "The user's profile image has been changed to the new photo."
        ];
    }
}
