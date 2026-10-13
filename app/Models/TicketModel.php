<?php

namespace App\Models;

use CodeIgniter\Model;

class TicketModel extends Model
{
    protected $table         = 'tickets';
    protected $allowedFields = ['subject', 'offices', 'created_at'];
}