<?php

namespace App\Console\Commands;

use App\Repositories\CallRepository;
use App\Repositories\SessionRepository;
use Illuminate\Console\Command;

class ClearExpiredData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'control:clear-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear expired session and calls';

    /**
     * @var CallRepository
     */
    protected $callRepository;

    /**
     * @var SessionRepository
     */
    protected $sessionRepository;

    /**
     * UpdateRates constructor.
     * @param SessionRepository $sessionRepository
     * @param CallRepository    $callRepository
     */
    public function __construct(SessionRepository $sessionRepository, CallRepository $callRepository)
    {
        parent::__construct();
        $this->sessionRepository = $sessionRepository;
        $this->callRepository = $callRepository;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     * @throws \Exception
     */
    public function handle()
    {
        $this->info('Starting delete');

        $this->sessionRepository->deleteAllExpired();
        $this->callRepository->deleteAllExpired();

        $this->info('Ending delete');
    }
}
