<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use App\Services\TelcellService;
use Exception;
class CheckTelcellInvoices extends Command
{
    protected $signature = 'telcell:check-invoices';
    protected $description = 'Проверяет статусы всех неоплаченных счетов Telcell и обновляет их';

    protected TelcellService $telcellService;

    public function __construct(TelcellService $telcellService)
    {
        parent::__construct();
        $this->telcellService = $telcellService;
    }

    public function handle()
    {
        $this->info('Начинаем проверку статусов счетов Telcell...');

        $orders = Order::whereIn('invoice_status', ['NEW', 'CREATED', null])->get();

        if ($orders->isEmpty()) {
            $this->info('Нет счетов для проверки.');
            return 0;
        }

        foreach ($orders as $order) {
            $status = $this->telcellService->checkInvoiceStatus($order->id);
            $this->info("Заказ #{$order->id} - статус: {$status}");
        }

        $this->info('Проверка завершена.');
        return 0;
    }
}
