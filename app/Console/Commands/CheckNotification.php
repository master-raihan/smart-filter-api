<?php

namespace App\Console\Commands;

use App\Contracts\Services\BillContract;
use App\Models\DailyReminder;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CheckNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    private $billService;
    protected $signature = 'notification:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(BillContract $billService)
    {
        parent::__construct();
        $this->billService = $billService;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try{
            $notifications = $this->billService->checkNotification();
            if($notifications){
                foreach ($notifications as $notification){
                    DailyReminder::create([
                        'user_id' => $notification->user_id,
                        'bill_id' => $notification->id,
                        'data' => json_encode([ 'payee' => $notification->payee, 'amount' => $notification->amount, 'due_date' =>  $notification->due_date])
                    ]);
                }
            }
        }catch (\Exception $exception){
            Log::error($exception->getMessage());
        }

    }
}
