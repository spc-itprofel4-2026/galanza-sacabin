<?php

namespace App\Models;

use CodeIgniter\Model;

class HelpdeskTicketModel extends Model
{
    protected $table = 'helpdesk_tickets';
    protected $primaryKey = 'id';
    protected $allowedFields = ['ticket_number', 'status', 'priority', 'concern_text', 'transfer_reason'];
}
