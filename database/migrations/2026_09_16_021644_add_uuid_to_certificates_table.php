<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Adds the unguessable public identifier used by the digital certificate page.
     */
    public function up(): void
    {
        Schema::table('certificates', function (Blueprint $table) {
            $table->uuid()->nullable()->unique()->after('id');
        });

        DB::table('certificates')
            ->whereNull('uuid')
            ->orderBy('id')
            ->each(function (object $certificate): void {
                DB::table('certificates')
                    ->where('id', $certificate->id)
                    ->update(['uuid' => (string) Str::uuid()]);
            });

        Schema::table('certificates', function (Blueprint $table) {
            $table->uuid()->nullable(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('certificates', function (Blueprint $table) {
            $table->dropUnique(['uuid']);
            $table->dropColumn('uuid');
        });
    }
};
