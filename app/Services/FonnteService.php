<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\RequestException;

class FonnteService
{
    protected string $baseUrl = 'https://api.fonnte.com';
    protected string $token;

    /**
     * FonnteService constructor.
     */
    public function __construct()
    {
        $this->token = config('services.fonnte.token');
        if (!$this->token) {
            throw new \InvalidArgumentException('Fonnte token is not set in your configuration.');
        }
    }

    /**
     * Send a message using Fonnte API.
     *
     * @param array $data The payload for the API.
     * @return array The response from the API.
     */
    public static function send(array $data): array
    {
        $that = new self;

        $request = Http::withHeaders([
            'Authorization' => $that->token,
        ]);

        try {
            $response = $request->post("{$that->baseUrl}/send", $data);

            $response->throw();

            return $response->json();

        } catch (RequestException $e) {
            return [
                'status' => false,
                'message' => 'Failed to send message. Error: ' . $e->getMessage(),
            ];
        }
    }
}