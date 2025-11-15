<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Dbpegawai;

class Dft_pegawai extends Model
{
    use HasFactory;
    
    protected $guarded = [];

    public function dbpegawai()
    {
        return $this->belongsTo(Dbpegawai::class);
    }
}
