<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('divisions', function (Blueprint $table) {
            $table->unsignedInteger('quota')->default(5)->after('instansi');
        });

        $divisionIds = DB::table('positions')
            ->select('division_id')
            ->selectRaw('SUM(kuota) as total')
            ->groupBy('division_id')
            ->get();

        foreach ($divisionIds as $row) {
            DB::table('divisions')
                ->where('id', $row->division_id)
                ->update(['quota' => $row->total]);
        }
    }

    public function down(): void
    {
        Schema::table('divisions', function (Blueprint $table) {
            $table->dropColumn('quota');
        });
    }
};