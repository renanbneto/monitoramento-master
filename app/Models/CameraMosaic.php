<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CameraMosaic extends Model
{
    protected $fillable = [
        'user_id',
        'nome',
        'camera_ids',
    ];

    protected $casts = [
        'camera_ids' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return \Illuminate\Support\Collection<int, \App\Models\Camera>
     */
    public function camerasOrdenadas()
    {
        $ids = array_values(array_filter($this->camera_ids ?? []));
        if ($ids === []) {
            return collect();
        }

        $cams = Camera::whereIn('id', $ids)->get()->keyBy('id');

        return collect($ids)->map(function ($id) use ($cams) {
            return $cams->get($id);
        })->filter();
    }
}
