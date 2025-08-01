<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UserAddress;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $addresses = $user->addresses()->orderBy('is_default', 'desc')->orderBy('created_at', 'asc')->get();
        
        return view('user.profile', compact('user', 'addresses'));
    }

    public function storeAddress(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address_line1' => 'required|string|max:255',
            'address_line2' => 'nullable|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:255',
            'type' => 'required|in:home,work,other',
            'is_default' => 'boolean'
        ]);

        $user = Auth::user();

        // If this is the first address or user wants it as default, set it as default
        if ($user->addresses()->count() === 0 || $request->is_default) {
            // Remove default from other addresses
            $user->addresses()->update(['is_default' => false]);
        }

        $address = UserAddress::create([
            'user_id' => $user->id,
            'name' => $request->name,
            'address_line1' => $request->address_line1,
            'address_line2' => $request->address_line2,
            'city' => $request->city,
            'state' => $request->state,
            'postal_code' => $request->postal_code,
            'country' => $request->country ?? 'India',
            'type' => $request->type,
            'is_default' => $request->is_default || $user->addresses()->count() === 0
        ]);

        return response()->json([
            'message' => 'Address saved successfully!',
            'address' => $address
        ]);
    }

    public function updateAddress(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address_line1' => 'required|string|max:255',
            'address_line2' => 'nullable|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:255',
            'type' => 'required|in:home,work,other',
            'is_default' => 'boolean'
        ]);

        $user = Auth::user();
        $address = $user->addresses()->findOrFail($id);

        // If setting as default, remove default from other addresses
        if ($request->is_default) {
            $user->addresses()->where('id', '!=', $id)->update(['is_default' => false]);
        }

        $address->update([
            'name' => $request->name,
            'address_line1' => $request->address_line1,
            'address_line2' => $request->address_line2,
            'city' => $request->city,
            'state' => $request->state,
            'postal_code' => $request->postal_code,
            'country' => $request->country ?? 'India',
            'type' => $request->type,
            'is_default' => $request->is_default
        ]);

        return response()->json([
            'message' => 'Address updated successfully!',
            'address' => $address
        ]);
    }

    public function deleteAddress($id)
    {
        $user = Auth::user();
        $address = $user->addresses()->findOrFail($id);

        // If deleting default address, set another as default
        if ($address->is_default) {
            $otherAddress = $user->addresses()->where('id', '!=', $id)->first();
            if ($otherAddress) {
                $otherAddress->update(['is_default' => true]);
            }
        }

        $address->delete();

        return response()->json([
            'message' => 'Address deleted successfully!'
        ]);
    }

    public function setDefaultAddress($id)
    {
        $user = Auth::user();
        $address = $user->addresses()->findOrFail($id);

        // Remove default from other addresses
        $user->addresses()->where('id', '!=', $id)->update(['is_default' => false]);
        
        // Set this address as default
        $address->update(['is_default' => true]);

        return response()->json([
            'message' => 'Default address updated successfully!'
        ]);
    }

    public function getAddresses()
    {
        $user = Auth::user();
        $addresses = $user->addresses()->orderBy('is_default', 'desc')->orderBy('created_at', 'asc')->get();
        
        return response()->json($addresses);
    }

    public function getDefaultAddress()
    {
        $user = Auth::user();
        $defaultAddress = $user->defaultAddress;
        
        return response()->json($defaultAddress);
    }
}
