<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        /*
        |--------------------------------------------------------------------------
        | FIX STATUS TRAIL REPORT
        |--------------------------------------------------------------------------
        |
        | Sebelumnya kemungkinan menggunakan ENUM sehingga nilai baru seperti:
        |
        | aman
        | licin
        | berlumpur
        | longsor
        | pohon_tumbang
        | jalur_tertutup
        | jembatan_rusak
        | lainnya
        |
        | tidak dapat disimpan.
        |
        | Kita ubah menjadi VARCHAR agar lebih fleksibel.
        |
        */

        DB::statement("
            ALTER TABLE trail_reports
            MODIFY COLUMN status VARCHAR(50) NOT NULL
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        /*
        |--------------------------------------------------------------------------
        | ROLLBACK
        |--------------------------------------------------------------------------
        |
        | Jika rollback dilakukan, kita kembalikan ke ENUM
        | sesuai status yang saat ini digunakan aplikasi.
        |
        */

        DB::statement("
            ALTER TABLE trail_reports
            MODIFY COLUMN status ENUM(
                'aman',
                'licin',
                'berlumpur',
                'longsor',
                'pohon_tumbang',
                'jalur_tertutup',
                'jembatan_rusak',
                'lainnya'
            ) NOT NULL
        ");
    }
};