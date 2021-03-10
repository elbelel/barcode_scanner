<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barcode extends Model
{
    use HasFactory;
    protected $fillable = [
        'full_name', 'email', 'state','street','phone','website','city','zip_code','profile','barcode'
      ];}
