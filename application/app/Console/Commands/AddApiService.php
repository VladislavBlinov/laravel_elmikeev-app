<?php

namespace App\Console\Commands;

use App\Models\ApiService;
use Illuminate\Console\Command;

class AddApiService extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'add:service {name} {base_url} {description?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Добавить сервис';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $service = ApiService::create([
            'name' => $this->argument('name'),
            'base_url' => $this->argument('base_url'),
            'description' => $this->argument('description') ?? null,
        ]);

        $this->info("Сервис создан: {$service->name}");
    }
}
