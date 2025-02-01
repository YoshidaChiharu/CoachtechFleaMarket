<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;
use App\Http\Requests\AddressUpdateRequest;
use App\Models\Address;

class ShipAddressController extends Controller
{
    public function create(Request $request) {
        return Inertia::render('RegisterShipAddress', [
            'itemId' => $request->item_id,
        ]);
    }

    public function store(AddressUpdateRequest $request) {
        try {
            Address::create([
                'user_id' => $request->user()->id,
                'name' => $request->name,
                'postcode' => $request->postcode,
                'address' => $request->address,
                'building' => $request->building,
            ]);
        } catch (\Exception $e) {
            Log::error($e);
        }

        return to_route('purchase', ['item_id' => $request->item_id, 'modalOpen' => true]);
    }

    public function edit(Request $request)
    {
        $address = Address::find($request->address_id);

        if ($address && $address->user_id === $request->user()->id) {
            return Inertia::render('EditShipAddress', [
                'address' => $address,
                'itemId' => $request->item_id,
            ]);
        }

        // $address_idが不正な値の場合
        return to_route('top');
    }

    public function update(AddressUpdateRequest $request) {
        try {
            $address = Address::find($request->address_id);

            if ($address && $address->user_id === $request->user()->id) {
                $param = [
                    'name' => $request->name,
                    'postcode' => $request->postcode,
                    'address' => $request->address,
                    'building' => $request->building,
                ];

                $address->update($param);
            }
        } catch (\Exception $e) {
            Log::error($e);
        }

        return to_route('purchase', ['item_id' => $request->item_id, 'modalOpen' => true]);
    }

    public function destroy(Request $request) {
        try {
            $address = Address::find($request->address_id);

            if ($address && $address->user_id === $request->user()->id) {
                $address->delete();
            }
        } catch (\Exception $e) {
            Log::error($e);
        }

        return to_route('purchase', ['item_id' => $request->item_id, 'modalOpen' => true]);
    }
}
