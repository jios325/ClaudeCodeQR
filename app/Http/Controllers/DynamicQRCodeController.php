<?php

namespace App\Http\Controllers;

use App\Http\Requests\DynamicQRCodeStoreRequest;
use App\Http\Requests\DynamicQRCodeUpdateRequest;
use App\Http\Resources\DynamicQRCodeCollection;
use App\Http\Resources\DynamicQRCodeResource;
use App\Models\DynamicQRCode;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class DynamicQRCodeController extends Controller
{
    public function index(Request $request): DynamicQRCodeCollection
    {
        $dynamicQRCodes = DynamicQRCode::all();

        return new DynamicQRCodeCollection($dynamicQRCodes);
    }

    public function store(DynamicQRCodeStoreRequest $request): DynamicQRCodeResource
    {
        $dynamicQRCode = DynamicQRCode::create($request->validated());

        return new DynamicQRCodeResource($dynamicQRCode);
    }

    public function show(Request $request, DynamicQRCode $dynamicQRCode): DynamicQRCodeResource
    {
        return new DynamicQRCodeResource($dynamicQRCode);
    }

    public function update(DynamicQRCodeUpdateRequest $request, DynamicQRCode $dynamicQRCode): DynamicQRCodeResource
    {
        $dynamicQRCode->update($request->validated());

        return new DynamicQRCodeResource($dynamicQRCode);
    }

    public function destroy(Request $request, DynamicQRCode $dynamicQRCode): Response
    {
        $dynamicQRCode->delete();

        return response()->noContent();
    }
}
