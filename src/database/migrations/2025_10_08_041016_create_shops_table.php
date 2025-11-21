<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration {
public function up(): void
{
Schema::create('shops', function (Blueprint $table) {
$table->id();
$table->foreignId('area_id')->constrained()->cascadeOnDelete();
$table->foreignId('genre_id')->constrained()->cascadeOnDelete();
$table->string('name', 100);
$table->text('overview')->nullable();
$table->string('image_url')->nullable();
$table->timestamps();
$table->string('stripe_payment_link')->nullable();
$table->string('stripe_account_id')->nullable();

});
}
public function down(): void { Schema::dropIfExists('shops'); }
};