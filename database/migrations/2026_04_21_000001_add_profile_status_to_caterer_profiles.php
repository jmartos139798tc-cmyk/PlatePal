<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('caterer_profiles', function (Blueprint $table) {
            $table->enum('profile_status', ['incomplete', 'pending', 'approved', 'rejected'])
                ->default('incomplete')
                ->after('is_featured');
            $table->boolean('is_approved')->default(false)->after('profile_status');
        });
    }

    public function down(): void
    {
        Schema::table('caterer_profiles', function (Blueprint $table) {
            $table->dropColumn(['profile_status', 'is_approved']);
        });
    }
};
