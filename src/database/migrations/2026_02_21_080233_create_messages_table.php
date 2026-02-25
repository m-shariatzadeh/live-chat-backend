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
        Schema::create('messages', function (Blueprint $table) {
            $table->id();

            $table->foreignId('conversation_id')
                ->constrained()
                ->cascadeOnDelete()
                ->index();

            $table->enum('sender_type', ['visitor', 'agent', 'system'])->index();
            $table->unsignedBigInteger('sender_id')->nullable()->index();

            $table->enum('type', ['text', 'file', 'image', 'voice', 'system'])
                ->default('text');

            $table->longText('body')->nullable();
            $table->json('meta')->nullable();

            $table->unsignedBigInteger('reply_to')->nullable();

            $table->boolean('seen_by_visitor')->default(false)->index();
            $table->boolean('seen_by_agent')->default(false)->index();

            $table->timestamp('edited_at')->nullable();
            $table->timestamp('deleted_at')->nullable();

            $table->timestamps();

            $table->index(['conversation_id', 'id']); // id-based pagination
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
