<?php

namespace App\Console\Commands;

use App\Models\TokenType;
use Illuminate\Console\Command;

class AddTokenType extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'add:tokentype {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Добавить тип токена';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $type = TokenType::create([
            'name' => $this->argument('name'),
        ]);

        $this->info("Тип токена создан: {$type->name}");
    }
}
