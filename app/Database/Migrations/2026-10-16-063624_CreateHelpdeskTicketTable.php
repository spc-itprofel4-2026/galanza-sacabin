<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateHelpdeskTicketTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'ticket_number' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => false],
            'status' => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => false],
            'priority' => ['type' => 'VARCHAR', 'constraint' => 10, 'null' => false],
            'concern_text' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => false],
            'transfer_reason' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
        ]);
        $this->forge->addKey('id', true);
      $this->forge->addUniqueKey('ticket_number');
        $this->forge->createTable('helpdesk_tickets');
    }

    public function down()
    {
        $this->forge->dropTable('helpdesk_tickets');
    }
}
