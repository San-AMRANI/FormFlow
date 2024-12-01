<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormResponseAnswer extends Model
{
    use HasFactory;

    protected $table = 'form_response_answers';
    
    protected $fillable = [
        'form_response_id',
        'form_field_id',
        'answer',
    ];

    // Define the relationship with form_response
    public function formResponse()
    {
        return $this->belongsTo(FormResponse::class, 'form_response_id');
    }

    // Define the relationship with form_field
    public function formField()
    {
        return $this->belongsTo(FormField::class, 'form_field_id');
    }
}
