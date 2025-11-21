<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('shops', function (Blueprint $table) {
            if (! Schema::hasColumn('shops', 'summary')) {
                $table->text('summary')->nullable()->after('genre_id');
            }
        });

        if (Schema::hasColumn('shops', 'description')) {
            DB::statement('UPDATE shops SET summary = description WHERE summary IS NULL');
        }
    }

    public function down(): void
    {
        Schema::table('shops', function (Blueprint $table) {
            if (Schema::hasColumn('shops', 'summary')) {
                $table->dropColumn('summary');
            }
        });
    }
};
