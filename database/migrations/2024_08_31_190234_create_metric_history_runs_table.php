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
            $table->float('accessibility_metric', 5, 2);
            $table->float('pwa_metric', 5, 2);
            $table->float('performance_metric', 5, 2);
            $table->float('seo_metric', 5, 2);
            $table->float('best_practices_metric', 5, 2);
            $table->tinyInteger('strategy_id');
            $table->timestamps();

            $table->foreign('strategy_id')->references('id')->on('strategies')->onDelete('cascade');
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
