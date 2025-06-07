<?php

namespace App\Http\Controllers;

use App\Models\ComponenJS;
use App\Models\ComponentJS;
use App\Models\User;
use Illuminate\Http\Request;

class ComponentJsController extends Controller
{
    public function index(string $hash)
    {

        $cekJS = ComponentJS::where('hash', $hash)->first();

        if(empty($cekJS))
        {

            $cekFile = public_path('src/components/'.$hash.'.js');

            if(file_exists($cekFile))
            {
                return response()->file($cekFile,[
                    'Content-Type' => 'application/javascript',
                    'Content-Disposition' => 'inline; filename="'.$hash.'.js"',
                ]);
            }

            abort(404);
        }

        $path = public_path($cekJS->path).'.js';

        if(!file_exists($path))
        {
            abort(404);
        }
        return response()->file($path,[
            'Content-Type' => 'application/javascript',
            'Content-Disposition' => 'inline; filename="'.$hash.'.js"',
        ]);

    }

    public function wigaConfig(Request $request)
    {

        // $filePath = public_path('src/components/wiga-config.js');

        // if(file_exists($filePath))
        // {
        //     return response()->file($filePath,[
        //         'Content-Type' => 'application/javascript',
        //         'Content-Disposition' => 'inline; filename="wiga.js"',
        //     ]);
        // }

        $wigaID = $request->cookie('wigaevents_id');

        if(!empty($wigaID))
        {
            $user = User::findToken($wigaID);
            if(empty($user))
            {
                $wigaID = null;
            }
        }
        $data = [
            'APP_NAME' => env('APP_NAME'),
            'APP_VERSION' => env('APP_AUTHOR_VERSION'),
            'APP_AUTHOR' => env('APP_AUTHOR'),  
            'DOMAIN' => $request->getHost(),
            'URL' => url('/'),
            'STORAGE_URL' => url('file:'),
            'API_URL' => env('API_URL'),
            'API_KEY_TYPE' => 'X-API-KEY',
            'WIGA_CDKEY' => 'wigaevents_id',
            'WIGA_ID' => $wigaID,
            'API_KEY' => trimBase64(base64_encode(env('API_KEY'))),
            'PUSHER_KEY' => env('PUSHER_APP_KEY','77bbe30c1b92213faedc'),
            'PUSHER_CLUSTER' => env('PUSHER_APP_CLUSTER','ap1'),
            'PUSHER_INSTANCE_ID' => env('PUSHER_INSTANCE_ID','eea641bc-9d97-4720-a9f0-4334f22220e3'),
        ];

        $content = 'let WIGA_CONFIG = "'.trimBase64(base64_encode(json_encode($data))).'";';

        return response($content)->header('Content-Type','application/javascript');


        // if (file_exists($filePath) && filesize($filePath) > 0) {

        //     return response()->file($filePath, [
        //         'Content-Type' => 'application/javascript',
        //     ]);

        // } else {

        //     file_put_contents($filePath, $content);

        //     return response()->file($filePath, [
        //         'Content-Type' => 'application/javascript',
        //     ]);

        // }

    }
}
