<?php
declare(strict_types=1);

namespace App\Console\Commands;

use DB;
use Illuminate\Console\Command;
use Illuminate\Database\QueryException;

class DbWaitDatabase extends Command
{
    /**
     * Wait sleep time for db connection in seconds
     *
     * @var int
     */
    const WAIT_SLEEP_TIME = 2;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:wait';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Waits for database availability.';

    /**
     * Execute the console command.
     *
     * @param DB $db
     *
     * @return integer
     */
    public function handle(DB $db): int
    {
        for ($i = 0; $i < 60; $i += self::WAIT_SLEEP_TIME) {

            try {
                $db::select('SHOW TABLES');
                $this->info('Connection to the database is ok!');

                return 0;
            } catch (QueryException $e) {
                $this->comment('Trying to connect to the database seconds:' . $i);
                sleep(self::WAIT_SLEEP_TIME);

                continue;
            }

        }

        $this->error('Can not connect to the database');

        return 1;
    }
}
