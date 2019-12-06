<?php

namespace App\Http\Controllers;

use App\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UsersProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function updateUsersAddress(Request $request)
    {
        $this->validate($request, [
            'address' => 'required|max:80',
            'phone' => 'required|max:15'
        ]);

        $user = Auth::user();
        $user->update([
            $user->address = $request->address,
            $user->phone = $request->phone
        ]);

        return response()->json($user, 200);
    }

    public function getUserAccount()
    {
        $user = Auth::user();

        return response()->json($user, 200);
    }

    public function getLocations()
    {
        $locations = Location::all();

        return response()->json($locations, 200);
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $this->validate($request, [
            'update.name' => 'required|max:60',
            'update.phone' => 'required|numeric',
            'update.address' => 'required|max:100',
        ]);

        $user->update([
            $user->name = $request->update['name'],
            $user->phone = $request->update['phone'],
            $user->alt_phone = $request->update['alt_phone'],
            $user->address = $request->update['address'],
            $user->location_id = $request->update['location_id'],
        ]);

        return response()->json($user, 200);
    }

    public function confirmOldPswd(Request $request)
    {
        $user = Auth::user();
        $oldPswd = $user->password;
        $check = Hash::check($request->password, $oldPswd);

        return response()->json($check, 200);
    }

    public function updateUserPassword(Request $request)
    {
        $new = $request->password;

        $user = Auth::user();

        $this->validate($request, [
            'password' => 'required|min:4|max:30|confirmed',
            'password_confirmation' => 'required'
        ]);

        $user->update([
            $user->password = Hash::make($new)
        ]);

        return response()->json(['message' => 'Password changed successfully']);
    }
}
