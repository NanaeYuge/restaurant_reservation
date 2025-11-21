<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasColumn('ratings','visited_at')) {
            Schema::table('ratings', function (Blueprint $table) {
                $table->date('visited_at')->nullable()->after('comment');
            });
            try { DB::statement('ALTER TABLE ratings DROP INDEX ratings_user_id_shop_id_unique'); } catch (\Throwable $e) {}
            try { DB::statement('DROP INDEX ratings_user_id_shop_id_unique ON ratings'); } catch (\Throwable $e) {}
            Schema::table('ratings', function (Blueprint $table) {
                $table->unique(['user_id','shop_id','visited_at']);
            });
        }
    }
    public function down(): void
    {
        if (Schema::hasColumn('ratings','visited_at')) {
            Schema::table('ratings', function (Blueprint $table) {
                $table->dropUnique(['user_id','shop_id','visited_at']);
                $table->dropColumn('visited_at');
            });
            try { Schema::table('ratings', function (Blueprint $table) { $table->unique(['user_id','shop_id']); }); } catch (\Throwable $e) {}
        }
    }
};
