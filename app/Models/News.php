<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class News extends Model
{
    protected $fillable = ['title', 'subject', 'date'];

    protected $appends = ['content'];

    /**
     * Get HTML content from file storage
     */
    public function getContentAttribute(): string
    {
        // Only read file if we have an ID and file exists
        if (!$this->id) {
            return '';
        }

        $filename = "news/{$this->id}.html";

        try {
            return Storage::disk('local')->exists($filename)
                ? Storage::disk('local')->get($filename)
                : '';
        } catch (\Exception $e) {
            return '';
        }
    }

    /**
     * Save HTML content to file storage
     */
    public function setContentAttribute(string $value): void
    {
        if ($this->id) {
            $filename = "news/{$this->id}.html";
            Storage::disk('local')->put($filename, $value);
        }
    }

    /**
     * Helper method for bulk content operations
     */
    public function updateContent(string $content): bool
    {
        $this->content = $content;
        return $this->save();
    }

    /**
     * Delete associated content file when model is deleted
     */
    protected static function boot(): void
    {
        parent::boot();

        static::deleted(function (self $model) {
            if ($model->id) {
                $filename = "news/{$model->id}.html";
                if (Storage::disk('local')->exists($filename)) {
                    Storage::disk('local')->delete($filename);
                }
            }
        });
    }
}
