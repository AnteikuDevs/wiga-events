<?php

namespace App\Console\Commands;

use App\Models\ApiCredential;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class ApiKeyCreateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'api:key:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new api credential';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $appName = $this->ask('Name');

        if (empty($appName)) {
            $this->error('Name cannot be empty .');
            return;
        }

        $clientId = Str::uuid();
        $clientSecret = Str::random(40);

        $credential = ApiCredential::create([
            'name' => $appName,
            'client_id' => $clientId,
            'client_secret' => $clientSecret,
        ]);

        $this->info('API Credential Created (save this information):');
        $this->line('App Name     : ' . $credential->name);
        $this->line('Client ID    : ' . $credential->client_id);
        $this->line('Client Secret: ' . $credential->client_secret);
    }
}
