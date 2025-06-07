<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ApiCredential;
use Illuminate\Http\Request;

class CredentialController extends Controller
{
    public function getToken(Request $request)
    {

        $request->validate([
            'client_id' => 'required',
            'client_secret' => 'required',
        ]);

        $data = ApiCredential::where('client_id', $request->client_id)->where('client_secret', $request->client_secret)->first();
        if(empty($data))
        {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized. Invalid API credentials.'
            ], 403);
        }

        $data->access_key = str_random(100);
        $data->save();

        return response()->json([
            'status' => true,
            'data' => [
                'token' => $data->access_key
            ]
        ]);

    }
}
