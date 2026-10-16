<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateHelpdeskTicketTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'requester_name' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => false],
            'subject' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => false],
            'description' => ['type' => 'TEXT', 'null' => false],
            'priority' => ['type' => 'INT', 'constraint' => 11, 'null' => false],
            'is_resolved' => ['type' => 'TINYINT', 'constraint' => 1, 'null' => true],
            'submitted_at' => ['type' => 'DATETIME', 'null' => false],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('helpdesk_tickets');
    }

    public function down()
    {
        $this->forge->dropTable('helpdesk_tickets');
    }
}
