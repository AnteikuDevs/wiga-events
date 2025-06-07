<?php

use App\Models\ComponentJS;

function componentJS($path)
{

    $componentSrcPath = 'src/components/'.env('APP_VERSION');

    $fullpath = strip_slash($componentSrcPath.'/'.$path);

    $cekComponent = ComponentJS::where('path', $fullpath)->first();

    if(empty($cekComponent))
    {
        $component = ComponentJS::create([
            'hash' => str_random(10),
            'path' => $fullpath,
        ]);
        return $component->hash;
    }

    return $cekComponent->hash;

}

use Symfony\Component\Mime\MimeTypes;

function guessMimeTypeFromPath($url)
{
    $extension = pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION);

    $mimeTypes = new MimeTypes();
    $mimes = $mimeTypes->getMimeTypes($extension);

    return $mimes[0] ?? 'application/octet-stream';
}