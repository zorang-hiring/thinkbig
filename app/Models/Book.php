<?php
declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $fillable = [
        'name',
        'publication_date',
        'publisher_id'
    ];

    protected $casts = [
        'publication_date' => 'date:Y-m-d' // datetime
    ];

    public function publisher(): Publisher
    {
        return $this->belongsTo(
            Publisher::class,
            'publisher_id'
        )->getResults();
    }

    public function authors(): BelongsToMany
    {
        return $this->belongsToMany(
            Author::class,
            'authors_has_books',
            'book_id',
            'author_id'
        );
    }
}
