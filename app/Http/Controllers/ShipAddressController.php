<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;
use App\Http\Requests\AddressUpdateRequest;

class ShipAddressController extends Controller
{
    public function edit(Request $request)
    {
        $profile = $request->user()->profile;

        return Inertia::render('ShipAddress', [
            'profile' => $profile,
            'itemId' => $request->item_id,
        ]);
    }

    public function update(AddressUpdateRequest $request) {
        try {
            $profile = $request->user()->profile;

            $param = [
                'postcode' => $request->postcode,
                'address' => $request->address,
                'building' => $request->building,
            ];

            $profile->update($param);
        } catch (\Exception $e) {
            Log::error($e);
        }

        return to_route('purchase', $request->item_id);
    }
}
