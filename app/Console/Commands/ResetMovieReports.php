<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\MovieReport;
use Illuminate\Console\Command;

class ResetMovieReports extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'movie:views-reset {schedule}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset specified fields in movie_reports table';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $schedule = $this->argument('schedule');

        if ($schedule === 'dailyAt') {
            MovieReport::query()
                ->update([
                    'total_views_within_a_day' => 0,
                    'total_likes_within_a_day' => 0,
                    'current_date' => Carbon::now()
                ]);
        }

        if ($schedule === 'weekly') {
            MovieReport::query()
                ->update([
                    'total_views_within_a_week' => 0,
                    'total_likes_within_a_week' => 0,
                    'current_date' => Carbon::now()
                ]);
        } 
    }
}
