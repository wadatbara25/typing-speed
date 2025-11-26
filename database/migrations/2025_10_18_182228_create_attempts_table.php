<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('attempts', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->cascadeOnDelete();
    $table->foreignId('lesson_id')->nullable()->constrained()->nullOnDelete();
    $table->float('wpm')->nullable();
    $table->float('raw_wpm')->nullable();
    $table->float('accuracy')->nullable();
    $table->json('errors_json')->nullable();
    $table->integer('duration_seconds')->nullable();
    $table->longText('typed_text')->nullable();
    $table->timestamp('started_at')->nullable();
    $table->timestamp('finished_at')->nullable();
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attempts');
    }
};
