<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Form_m extends Model
{
    use HasFactory;

    protected $table = 'forms';
    
    protected $fillable = [
        'title', 
        'name',
        'description', 
        'status',
        'created_by', // Assuming created_by is the ID of the authenticated user
    ];
    // Define the relationship with form_fields
    public function formFields()
    {
        return $this->hasMany(FormField::class, 'form_id');
    }

    // Define the relationship with form_responses
    public function formResponses()
    {
        return $this->hasMany(FormResponse::class, 'form_id');
    }
}
