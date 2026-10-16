<?php

namespace App\Models;

use CodeIgniter\Model;

class HelpdeskTicketModel extends Model
{
    protected $table = 'helpdesk_tickets';
    protected $primaryKey = 'id';
    protected $allowedFields = ['requester_name', 'subject', 'description', 'priority', 'is_resolved', 'submitted_at'];
}
