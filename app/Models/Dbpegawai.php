<?php

namespace App\Models;

use App\Models\Dft_pegawai;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Dbpegawai extends Model
{
    use HasFactory;
    public function dft_pegawai()
    {
        return $this->hasOne(dft_pegawai::class);
    }
}
