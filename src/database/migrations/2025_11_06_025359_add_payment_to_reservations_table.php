<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            if (!Schema::hasColumn('reservations', 'payment_status')) {
                $table->string('payment_status')->nullable()->index();
            }
            if (!Schema::hasColumn('reservations', 'amount')) {
                $table->integer('amount')->nullable();
            }
            if (!Schema::hasColumn('reservations', 'stripe_session_id')) {
                $table->string('stripe_session_id')->nullable()->unique();
            }
            if (!Schema::hasColumn('reservations', 'stripe_payment_intent_id')) {
                $table->string('stripe_payment_intent_id')->nullable()->index();
            }
            if (!Schema::hasColumn('reservations', 'paid_at')) {
                $table->timestamp('paid_at')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            if (Schema::hasColumn('reservations', 'payment_status')) {
                $table->dropColumn('payment_status');
            }
            if (Schema::hasColumn('reservations', 'amount')) {
                $table->dropColumn('amount');
            }
            if (Schema::hasColumn('reservations', 'stripe_session_id')) {
                $table->dropColumn('stripe_session_id');
            }
            if (Schema::hasColumn('reservations', 'stripe_payment_intent_id')) {
                $table->dropColumn('stripe_payment_intent_id');
            }
            if (Schema::hasColumn('reservations', 'paid_at')) {
                $table->dropColumn('paid_at');
            }
        });
    }
};
