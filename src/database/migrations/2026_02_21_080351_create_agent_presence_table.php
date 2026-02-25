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
        Schema::create('agent_presence', function (Blueprint $table) {
            $table->unsignedBigInteger('agent_id')->primary();

            $table->boolean('is_online')->default(false);
            $table->timestamp('last_seen_at')->nullable();

            $table->string('last_socket_id')->nullable();

            $table->unsignedBigInteger('current_conversation_id')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agent_presence');
    }
};
