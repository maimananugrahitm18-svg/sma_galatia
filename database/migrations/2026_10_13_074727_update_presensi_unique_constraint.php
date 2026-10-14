<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Bersihkan duplikat presensi siswa pada tanggal yang sama, sisakan ID terbesar (terbaru)
        $duplicates = DB::table('presensi')
            ->select('tanggal', 'siswa_id', DB::raw('MAX(id) as max_id'))
            ->groupBy('tanggal', 'siswa_id')
            ->havingRaw('COUNT(*) > 1')
            ->get();

        foreach ($duplicates as $dup) {
            DB::table('presensi')
                ->where('tanggal', $dup->tanggal)
                ->where('siswa_id', $dup->siswa_id)
                ->where('id', '!=', $dup->max_id)
                ->delete();
        }

        Schema::table('presensi', function (Blueprint $table) {
            $table->unique(['tanggal', 'siswa_id'], 'presensi_unique_tanggal_siswa');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('presensi', function (Blueprint $table) {
            $table->dropUnique('presensi_unique_tanggal_siswa');
        });
    }
};
