<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DownloadedQuotation extends Model
{
    use HasFactory;

    protected $fillable = [
        'quotation_id',
        'downloaded_by',
        'download_count',
        'download_ip',
        'downloaded_at',
        'file_path',
        'file_format',
        'remarks',
    ];

    protected $casts = [
        'downloaded_at' => 'datetime',
    ];

    // Relationships
    public function quotation()
    {
        return $this->belongsTo(Quotation::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'downloaded_by');
    }
}
