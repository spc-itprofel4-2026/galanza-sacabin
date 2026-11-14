# From marks to code

## 1. Which file is the marking model, and why are the marks not written inside the model file?

The marking model is `app/PIM/helpdesk_ticket.marks.json`. The model file is `app/PIM/helpdesk_ticket.json`. The model file only says what the business needs: the entity `HelpdeskTicket` and its fields `ticketNumber`, `status`, `priority`, `concernText` and `transferReason`. A table name, a column length and a unique key depend on the platform (CodeIgniter 4 and MySQL), not on the business. If those values were written in the model file, it would no longer be platform independent. Keeping them in a separate file lets me mark the same model in different ways without editing it.

## 2. Which mark selected a rule, and which marks provided a value?

`unique` selected a rule. It goes on a field (`ticketNumber` in the first file) and decides whether the generator applies the rule that stops repeated values. `tableName` and `size` provided values. `tableName` goes on the entity `HelpdeskTicket` and gives the table name `helpdesk_tickets`. `size` goes on a text field (`status`, `priority`, `concernText`, `transferReason`) and gives the length of its VARCHAR column. Without them the generator uses its defaults: `helpdesk_ticket` plus `s` (a name made from the entity) and 255.

## 3. Trace table

| Mark | Target | Value | Where it shows in the generated code |
|------|--------|-------|--------------------------------------|
| tableName | HelpdeskTicket | helpdesk_tickets | The migration `app/Database/Migrations/2026-10-16-063624_CreateHelpdeskTicketTable.php`, the line with `createTable('helpdesk_tickets')`. Also the `$table` line in `app/Models/HelpdeskTicketModel.php`, and the name of `writable/generated/helpdesk_tickets.sql`. |
| unique | ticketNumber | true | The migration, the line with `addUniqueKey('ticket_number')`. In the SQL file, `UNIQUE` at the end of the `ticket_number` line. |
| size | status | 20 | The migration, the `status` line, `'constraint' => 20`. In the SQL file, `status VARCHAR(20)`. |
| size | priority | 10 | The migration, the `priority` line, `'constraint' => 10`. In the SQL file, `priority VARCHAR(10)`. |
| size | concernText | 255 | The migration, the `concern_text` line, `'constraint' => 255`. In the SQL file, `concern_text VARCHAR(255)`. |
| size | transferReason | 255 | The migration, the `transfer_reason` line, `'constraint' => 255`. In the SQL file, `transfer_reason VARCHAR(255)`. |

## 4. What changed in the output when the marking file changed?

The model file `helpdesk_ticket.json` stayed the same, but I generated SQL with a second marking file, `app/PIM/helpdesk_ticket.marksB.json`, for the Registrar's walk-in desk. The output file changed from `helpdesk_tickets.sql` to `registrar_tickets.sql`. The lengths changed: `status` from 20 to 12, `priority` from 10 to 6, and `concern_text` from 255 to 120. `UNIQUE` moved from `ticket_number` to `concern_text`. The columns, their order, the types and which one is nullable (`transfer_reason`) stayed the same, because they come from the model.

## 5. Which marks in Part 3 of the form match the marking file?

All six marks match: `tableName` on tickets, `unique` on the ticket identifier, and `size` on status, priority, concern text and transfer reason, with the same values (helpdesk_tickets, true, 20, 10, 255, 255). Three names are different. The form says `ticket_id`, `concern_text` and `transfer_reason`, but the generator only accepts field names made of letters, starting with a small letter, and it does not allow `id`. So the marking file uses `ticketNumber`, `concernText` and `transferReason`. The generator turns them into the columns `ticket_number`, `concern_text` and `transfer_reason`. I also had to fix the path of the marks file in `app/Commands/ModelGenerate.php` (the missing dot before `marks.json`) so that the normal command finds `helpdesk_ticket.marks.json`.