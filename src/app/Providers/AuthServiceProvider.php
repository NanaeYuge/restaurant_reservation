<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\Rating;
use App\Models\Reservation;
use App\Policies\RatingPolicy;
use App\Policies\ReservationPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Reservation::class => ReservationPolicy::class,
        Rating::class => RatingPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();

        Gate::define('access-owner', function ($user): bool {
            $role = $user->role ?? null;
            return $role === 'owner' || $role === 'admin';
        });

        Gate::define('access-admin', function ($user): bool {
            return ($user->role ?? null) === 'admin';
        });

        Gate::define('access-user', function ($user): bool {
            return in_array(($user->role ?? 'user'), ['user', 'owner', 'admin'], true);
        });

        Gate::before(function ($user, string $ability) {
            return ($user->role ?? null) === 'admin' ? true : null;
        });
    }
}
