<?php

namespace App\Models\Hakim;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\hasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;



class Program extends Model
{
    use HasFactory;
    public function faculty()
    {
        return $this->belongsTo(Faculty::class, 'faculty_id');
    }
    
   
}
