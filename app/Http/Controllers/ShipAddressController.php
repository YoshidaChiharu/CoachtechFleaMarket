<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;
use App\Http\Requests\AddressUpdateRequest;

class ShipAddressController extends Controller
{
    public function create(Request $request) {
        dd('配送先作成ページ表示');
    }

    public function store(Request $request) {
        dd('配送先の新規登録');
    }

    public function edit(Request $request)
    {
        $profile = $request->user()->profile;

        return Inertia::render('EditShipAddress', [
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

    public function destroy(Request $request) {
        dd('配送先の削除');
    }
}
