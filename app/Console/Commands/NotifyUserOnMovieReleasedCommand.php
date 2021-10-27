<?php

namespace App\Console\Commands;

use App\Models\ReleasedMovieNotifiedUser;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;

class NotifyUserOnMovieReleasedCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notify:user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Notify user on movie release';

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
     * Todo: get authenticated user
     *
     * @return int
     */
    public function handle()
    {
        $authenticatedUser = Auth::user();
        $authUserIpAddress = request()->ip();

        $isAuthUserIpAddressNotified = ReleasedMovieNotifiedUser::query()
            ->where('user_id', $authenticatedUser->id)
            ->get()
            ->filter(fn($notified) => $notified->ip_address === $authUserIpAddress)
            ->count();

        // if (! $isAuthUserIpAddressNotified) {
        //     $authenticatedUser->notify(
        //         new \App\Notifications\MovieReleaseExpoNotification(\App\Models\Movie::first())
        //     );
        // }
    }
}
