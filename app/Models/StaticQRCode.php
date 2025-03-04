<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class StaticQRCode extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'filename',
        'content_type',
        'content',
        'foreground_color',
        'background_color',
        'logo_path',
        'has_logo',
        'logo_size',
        'gradient_start_color',
        'gradient_end_color',
        'use_gradient',
        'eye_color',
        'style',
        'precision',
        'size',
        'format',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
        'has_logo' => 'boolean',
        'logo_size' => 'integer',
        'use_gradient' => 'boolean',
        'size' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    public function scanLogs()
    {
        return $this->hasMany(ScanLog::class, 'qr_code_id')
                    ->where('qr_code_type', 'static');
    }
}
