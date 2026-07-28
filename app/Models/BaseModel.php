<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

abstract class BaseModel extends Model
{
    /**
     * MWT Standard: Jangan allow mass assignment secara default jika tidak menggunakan
     * Form Request. Developer diwajibkan melakukan validasi manual melalui Controller
     * atau menggunakan Form Request.
     *
     * @var array
     */
    protected $guarded = [];
}
