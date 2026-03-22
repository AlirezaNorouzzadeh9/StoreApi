<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Requests\Address\StoreRequest;
use App\Models\User\City;
use App\Http\Requests\Address\UpdateRequest;
use App\Http\Resources\User\AddressResource;
class AddressController extends Controller
{
    use ApiResponse;

    public function show(Request $request, $id)
    {
        $user = $request->user();
        $address = $user->addresses()->where('id', $id)->with(['province', 'city'])->first();
        if (!$address) {
            return $this->error('Address not found.', 404);
        }
        return $this->success(new AddressResource($address), 'Address retrieved successfully.', 200);
    }

    public function index(Request $request)
    {
        $user = $request->user();
        $addresses = $user->addresses()->with(['province', 'city'])->get();
        if($addresses->isEmpty()){
            return $this->error('No addresses found.', 404);
        }
        return $this->success(AddressResource::collection($addresses), 'Addresses retrieved successfully.', 200);
    }

    public function store(StoreRequest $request){
        $user = $request->user();
        $data = $request->validated();
        $city = City::where('id', $data['city_id'])->first();
        if ($city->province_id != $data['province_id']) {
            return $this->error('Invalid city or province.', 422);
        }
        $addressData = [
            'title' => $data['title'],
            'receiver_name' => $data['receiver_name'],
            'receiver_mobile' => $data['receiver_phone'],
            'province_id' => $data['province_id'],
            'city_id' => $data['city_id'],
            'address_description' => $data['address_description'],
            'postal_code' => $data['postal_code'],
            'plate_number' => $data['plate_number'] ?? null,
            'unit_number' => $data['unit_number'] ?? null,
            'is_default' => $data['is_default'] ?? false,
        ];

        if (isset($data['is_default']) && $data['is_default']) {
            $user->addresses()->update(['is_default' => false]);
        }

        $address = $user->addresses()->create($addressData)->load(['province', 'city']);

        return $this->success(new AddressResource($address), 'Address created successfully.', 201);
    }

    public function destroy(Request $request, $id)
    {
        $user = $request->user();
        $address = $user->addresses()->where('id', $id)->first();
        if (!$address) {
            return $this->error('Address not found.', 404);
        }
        $address->delete();
        return response()->json(['success' => true, 'message' => 'Address deleted successfully.'], 200);
    }

    public function update(UpdateRequest $request, $id)
    {
        $user = $request->user();
        $address = $user->addresses()->where('id', $id)->first();
        if (!$address) {
            return $this->error('Address not found.', 404);
        }
        $data = $request->validated();
        if (isset($data['city_id']) || isset($data['province_id'])) {
            $city = City::where('id', $data['city_id'])->first();
            if ($city->province_id != $data['province_id']) {
                return $this->error('Invalid city or province.', 422);
            }
        }
        if (isset($data['is_default']) && $data['is_default']) {
            $user->addresses()->update(['is_default' => false]);
        }
        $address->update($data);
        return $this->success(new AddressResource($address), 'Address updated successfully.', 200);
    }

    public function setDefault(Request $request, $id)
    {
        $user = $request->user();
        $address = $user->addresses()->where('id', $id)->first();
        if (!$address) {
            return $this->error('Address not found.', 404);
        }
        $user->addresses()->update(['is_default' => false]);
        $address->update(['is_default' => true]);
        return $this->success(null, 'Default address set successfully.', 200);
    }
}
