<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('metric_history_runs', function (Blueprint $table) {
            $table->id();
            $table->string('url');
            $table->float('accessibility_metric', 5, 2)->nullable();
            $table->float('pwa_metric', 5, 2)->nullable();
            $table->float('performance_metric', 5, 2)->nullable();
            $table->float('seo_metric', 5, 2)->nullable();
            $table->float('best_practices_metric', 5, 2)->nullable();
            $table->foreignId('strategy_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('metric_history_runs');
    }
};
