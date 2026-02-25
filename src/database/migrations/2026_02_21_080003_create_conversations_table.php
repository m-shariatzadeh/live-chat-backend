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
        Schema::create('conversations', function (Blueprint $table) {
            $table->id();

            $table->foreignId('visitor_id')->constrained()->cascadeOnDelete();
            $table->foreignId('session_id')->constrained('visitor_sessions')->cascadeOnDelete();

            $table->enum('status', ['waiting', 'active', 'resolved', 'closed'])
                ->default('waiting')
                ->index();

            $table->enum('priority', ['low', 'normal', 'high', 'vip'])
                ->default('normal')
                ->index();

            $table->string('subject')->nullable();

            $table->timestamp('started_at')->nullable();
            $table->timestamp('first_response_at')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamp('closed_at')->nullable();

            $table->unsignedBigInteger('first_admin_id')->nullable();

            $table->timestamp('last_message_at')->nullable();
            $table->unsignedBigInteger('last_message_id')->nullable();

            $table->tinyInteger('rating')->nullable();
            $table->text('feedback')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conversations');
    }
};
