<?php

namespace App\Console\Commands;

use App\Models\ApiCredential;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class ApiKeyCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'api:key';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Show API credentials';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $credentials = ApiCredential::all();

        if(count($credentials) == 0)
        {
            $this->line('');
            $this->error('No API credentials found.');
            return;
        }
        $this->line('');
        $this->info('API Credential:');
        $this->line('');

        foreach ($credentials as $credential) {
            $this->line('App Name     : ' . $credential->app_name);
            $this->line('Client ID    : ' . $credential->client_id);
            $this->line('Client Secret: ' . $credential->client_secret);
            $this->line('Access Key   : ' . $credential->access_key? : 'Not Generated');
            $this->line('Is Active    : ' . ($credential->is_active ? 'Active' : 'Inactive'));
            $this->line('Created At   : ' . $credential->created_at);
            $this->line('Updated At   : ' . $credential->updated_at);
            $this->line('');
        }
    }
}
