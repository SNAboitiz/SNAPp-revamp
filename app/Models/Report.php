<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'report_file_id',
        'data',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'data' => 'array',
        ];
    }

    /**
     * Get the report file that owns the report.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function reportFile()
    {
        return $this->belongsTo(ReportFile::class);
    }
}
