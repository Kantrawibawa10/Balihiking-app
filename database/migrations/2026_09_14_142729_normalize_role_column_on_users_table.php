<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            ALTER TABLE users
            MODIFY COLUMN role VARCHAR(30)
            NOT NULL
            DEFAULT 'user'
        ");
    }

    public function down(): void
    {
        DB::statement("
            ALTER TABLE users
            MODIFY COLUMN role VARCHAR(30)
            NOT NULL
            DEFAULT 'user'
        ");
    }
};