<?php
namespace App\Models;
use App\Models\CRUD;

class Bouteille extends CRUD{
    protected $table = 'bouteille';
    protected $primaryKey = 'idBouteille';
    protected $fillable = ['title'];
}