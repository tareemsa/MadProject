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
        Schema::table('podcasts', function (Blueprint $table) {
            if (!Schema::hasColumn('podcasts', 'channel_id')) {
                $table->foreignId('channel_id')->nullable()->constrained()->cascadeOnDelete();
            }
        
            if (!Schema::hasColumn('podcasts', 'publish_at')) {
                $table->timestamp('publish_at')->nullable()->comment('Scheduled publish time');
            }
        
            if (!Schema::hasColumn('podcasts', 'published_at')) {
                $table->timestamp('published_at')->nullable();
            }
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('podcasts', function (Blueprint $table) {
            //
        });
    }
};
