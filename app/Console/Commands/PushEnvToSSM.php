<?php

namespace App\Console\Commands;

use Aws\Ssm\SsmClient;
use Illuminate\Console\Command;

class PushEnvToSSM extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ssm:push-env {--path=my-app/env : The parameter path}';


    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Push environment variables to AWS parameter store';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $paramPath = $this->option('path');
        
        // Remove leading slash if present
        $paramPath = ltrim($paramPath, '/');
        
        $this->info("Using parameter path: /{$paramPath}");
        
        $ssm = new SsmClient([
            'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
            'version' => 'latest',
            'credentials' => [
                'key' => env('AWS_ACCESS_KEY_ID'),
                'secret' => env('AWS_SECRET_ACCESS_KEY'),
            ]
        ]);
        
        // Gather all environment variables into a single JSON object
        $envVars = [];
        foreach($_ENV as $key => $value) {
            if(!empty($value)) {
                $envVars[$key] = $value;
            }
        }
        
        if(empty($envVars)) {
            $this->error('No environment variables found to store.');
            return;
        }
        
        // Convert to JSON
        $jsonValue = json_encode($envVars, JSON_PRETTY_PRINT);
        
        // Store as a single parameter
        try {
            $ssm->putParameter([
                'Name' => "/{$paramPath}",
                'Value' => $jsonValue,
                'Type' => 'SecureString',
                'Overwrite' => true
            ]);
            $this->info("Successfully stored all environment variables at /{$paramPath}");
        } catch(\Exception $e) {
            $this->error("Failed to upload environment variables: " . $e->getMessage());
        }
    }
}