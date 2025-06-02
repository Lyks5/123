<?php

namespace App\Console\Commands;

use App\Models\Arrival;
use Illuminate\Console\Command;

class UpdateNewArrivalsStatus extends Command
{
    /**
     * Имя и сигнатура консольной команды
     *
     * @var string
     */
    protected $signature = 'arrivals:update-status';

    /**
     * Описание консольной команды
     *
     * @var string
     */
    protected $description = 'Обновляет статус новых поступлений';

    /**
     * Выполнение консольной команды
     */
    public function handle()
    {
        $this->info('Начало обновления статусов поступлений...');

        $arrivals = Arrival::query()
            ->where('created_at', '<=', now()->subDays(7))
            ->where('is_new', true)
            ->get();

        foreach ($arrivals as $arrival) {
            $arrival->update(['is_new' => false]);
            $this->info("Обновлен статус поступления ID: {$arrival->id}");
        }

        $this->info("Обновление завершено. Обработано поступлений: {$arrivals->count()}");
    }
}