<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormResponse extends Model
{
    use HasFactory;

    protected $table = 'form_responses';

    protected $fillable = [
        'form_id', 
        'email', 
        'session_token', 
        'ip_address',
    ];

    // Define the relationship with form
    public function form()
    {
        return $this->belongsTo(Form_m::class, 'form_id');
    }

    // Define the relationship with form_response_answers
    public function formResponseAnswers()
    {
        return $this->hasMany(FormResponseAnswer::class, 'form_response_id');
    }
}
