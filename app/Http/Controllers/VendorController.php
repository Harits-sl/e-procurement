<?php

namespace App\Http\Controllers;

use App\Http\Requests\VendorRequest;
use App\Http\Resources\VendorResource;
use App\Models\Vendor;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Validator;

class VendorController extends Controller
{
    public function register(VendorRequest $request)
    {
        // validator for vendor already register or not
        $validator = Validator::make($request->user()->toArray(), [
            'id' => 'unique:vendors,user_id',
        ]);
        if ($validator->fails()) {
            throw new HttpResponseException(response()->json([
                'success'   => false,
                'message'   => 'This user is already a vendor',
            ], 400));
        }

        // create vendor
        $vendor = Vendor::create([
            'user_id' => $request->user()->id,
            'vendor_name' => $request->vendor_name,
            'address' => $request->address,
            'contact' => $request->contact
        ]);

        $vendorResource =  new VendorResource(true, 'Vendor Register Success', $vendor);
        return $vendorResource->response()->setStatusCode(200);
    }
}
