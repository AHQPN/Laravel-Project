<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Chuyendi;
use App\Services\TripService;

class ReleasePendingSeats extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'seats:release';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Release expired pending seats for all trips';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Start releasing expired pending seats...');

        $service = app(TripService::class);
        $trips = Chuyendi::select('machuyendi')->get();
        $total = 0;

        foreach ($trips as $t) {
            $count = $service->releaseExpiredPendingSeats($t->machuyendi);
            if ($count) {
                $this->info("Released {$count} seats for {$t->machuyendi}");
                $total += $count;
            }
        }

        $this->info("Done. Total released: {$total}");
        return 0;
    }
}
