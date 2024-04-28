<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExpenseModel extends Model
{
    use HasFactory;
    protected $table = 'expense';
    protected $fillable = [
        'title',
        'description',
        'total',
        'date',
        'user_id'
    ];


    // relation to table user
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // function to filter by user_id golbaly and get the user of expense
    protected static function booted(): void
    {
        static::addGlobalScope(function (EloquentBuilder $builder) {
            $builder->where('user_id', auth()->id());
            $builder->with('user');
        });
    }
}
