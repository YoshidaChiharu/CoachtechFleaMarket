<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;
use App\Http\Requests\AddressUpdateRequest;
use App\Models\Address;

/**
 * 配送先の登録／編集ページ用コントローラークラス
 */
class ShipAddressController extends Controller
{
    /**
     * 配送先登録ページ表示
     *
     * @param Request $request
     * @return Response
     */
    public function create(Request $request): Response
    {
        return Inertia::render('RegisterShipAddress', [
            'itemId' => $request->item_id,
        ]);
    }

    /**
     * 配送先登録処理
     *
     * @param AddressUpdateRequest $request
     * @return RedirectResponse
     */
    public function store(AddressUpdateRequest $request): RedirectResponse
    {
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

    /**
     * 配送先編集ページ表示
     *
     * @param Request $request
     * @return Response|RedirectResponse
     */
    public function edit(Request $request): Response|RedirectResponse
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

    /**
     * 配送先編集処理
     *
     * @param AddressUpdateRequest $request
     * @return RedirectResponse
     */
    public function update(AddressUpdateRequest $request): RedirectResponse
    {
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

    /**
     * 配送先削除処理
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function destroy(Request $request): RedirectResponse
    {
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
