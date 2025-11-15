<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;


use App\Models\Absen;
use App\Models\Pejabat;
use App\Models\Dinas_luar;
use App\Models\Dft_pegawai;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\DeteksiController;


class Uploadabsenmasuk implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $tahun, $bulan, $tanggal, $hari, $nip, $db, $namaBulan;
    protected $data,$file1,$foto,$file_b,$foto_b;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    
    public function __construct($sumber=[], $sumber2="", $sumber3="",$sumber4="",$sumber5="")
    {
        $this->data = $sumber;
        $this->file1 = $sumber2;
        $this->foto = $sumber3;
        $this->file_b = $sumber4;
        $this->foto_b = $sumber5;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $data = $this->data;
        $file1 = $this->file1;
        $foto = $this->foto;
        $file_b = $this->file_b;
        $foto_b = $this->foto_b;

        $data_foto = base64_decode($foto);
        $data_b = base64_decode($foto_b);

        $insert = Absen::create($data);
        $success = Storage::put($file1, $data_foto);
        $success_b = Storage::put($file_b, $data_b);

    }
}
