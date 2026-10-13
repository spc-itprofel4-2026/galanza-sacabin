<?php

namespace App\Controllers\Api\V1;

use App\Controllers\BaseController;
use App\Models\TicketModel;

class Tickets extends BaseController
{
    // Replace with the offices your classifier really routes to.
    private const OFFICES = ['IT Office', 'Registrar', 'Accounting', 'Guidance', 'Library'];

    // POST /api/v1/tickets
    public function create()
    {
        try {
            $body = $this->request->getJSON(true);
        } catch (\Throwable $e) {
            return $this->errorResponse(400, 'invalid_json', 'Request body must be valid JSON.');
        }

        if (! is_array($body)) {
            $body = [];
        }

        $subject = $body['subject'] ?? null;

        if (! is_string($subject) || trim($subject) === '' || mb_strlen($subject) > 200) {
            return $this->errorResponse(
                422,
                'invalid_subject',
                'subject must be text of 1 to 200 characters.',
                'subject'
            );
        }

        // A ticket can have several offices. An empty list means "not routed yet".
        $offices        = $body['offices'] ?? [];
        $officesAreValid = is_array($offices);

        if ($officesAreValid) {
            foreach ($offices as $office) {
                if (! is_string($office) || ! in_array($office, self::OFFICES, true)) {
                    $officesAreValid = false;
                    break;
                }
            }
        }

        if (! $officesAreValid) {
            return $this->errorResponse(
                422,
                'invalid_offices',
                'offices must be a list using only: ' . implode(', ', self::OFFICES) . '.',
                'offices'
            );
        }

        $offices = array_values(array_unique($offices));

        $model = new TicketModel();
        $id    = $model->insert([
            'subject'    => trim($subject),
            'offices'    => json_encode($offices),
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        return $this->response->setStatusCode(201)->setJSON([
            'status' => 201,
            'data'   => $this->formatTicket($model->find($id)),
        ]);
    }

    // GET /api/v1/tickets?limit=10&format=json
    public function index()
    {
        $limit  = $this->request->getGet('limit') ?? '10';
        $format = $this->request->getGet('format') ?? 'json';

        $limitIsValid = is_string($limit) && ctype_digit($limit)
            && (int) $limit >= 1 && (int) $limit <= 50;

        if (! $limitIsValid) {
            return $this->errorResponse(
                422,
                'invalid_limit',
                'limit must be a whole number from 1 to 50.',
                'limit'
            );
        }

        if (! in_array($format, ['json', 'xml'], true)) {
            return $this->errorResponse(
                422,
                'invalid_format',
                'format must be json or xml.',
                'format'
            );
        }

        $rows  = (new TicketModel())->orderBy('id', 'DESC')->findAll((int) $limit);
        $items = array_map(fn ($row) => $this->formatTicket($row), $rows);

        if ($format === 'xml') {
            return $this->xmlResponse($items);
        }

        return $this->response->setStatusCode(200)->setJSON([
            'status' => 200,
            'data'   => $items,
        ]);
    }

    // GET /api/v1/tickets/5
    public function show($id = null)
    {
        $row = (new TicketModel())->find($id);

        if ($row === null) {
            return $this->errorResponse(404, 'not_found', 'No ticket with that id.');
        }

        return $this->response->setStatusCode(200)->setJSON([
            'status' => 200,
            'data'   => $this->formatTicket($row),
        ]);
    }

    // Build the XML, check it against the schema, then send it.
    private function xmlResponse(array $items)
    {
        $dom = new \DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $root = $dom->createElement('ticketReport');
        $dom->appendChild($root);

        foreach ($items as $item) {
            $ticket = $dom->createElement('ticket');

            foreach (['id', 'subject', 'offices', 'createdAt'] as $field) {
                $node = $dom->createElement($field);

                if ($field === 'offices') {
                    foreach ($item['offices'] as $name) {
                        $office = $dom->createElement('office');
                        $office->appendChild($dom->createTextNode($name));
                        $node->appendChild($office);
                    }
                } else {
                    $node->appendChild($dom->createTextNode((string) $item[$field]));
                }

                $ticket->appendChild($node);
            }

            $root->appendChild($ticket);
        }

        libxml_use_internal_errors(true);
        $isValid = $dom->schemaValidate(APPPATH . 'Schemas/ticket-report.xsd');
        libxml_clear_errors();

        if (! $isValid) {
            return $this->errorResponse(500, 'invalid_xml', 'XML did not match the schema.');
        }

        return $this->response
            ->setStatusCode(200)
            ->setContentType('application/xml')
            ->setBody($dom->saveXML());
    }

    // Same error shape for every error. "field" is added when one input is to blame.
    private function errorResponse(int $status, string $code, string $message, ?string $field = null)
    {
        $error = [
            'code'    => $code,
            'message' => $message,
        ];

        if ($field !== null) {
            $error['field'] = $field;
        }

        return $this->response->setStatusCode($status)->setJSON([
            'status' => $status,
            'error'  => $error,
        ]);
    }

    // One shared shape for a ticket, used by every endpoint and by the XML.
    private function formatTicket(array $row): array
    {
        return [
            'id'        => (int) $row['id'],
            'subject'   => $row['subject'],
            'offices'   => json_decode($row['offices'] ?? '[]', true) ?: [],
            'createdAt' => date('Y-m-d\TH:i:s', strtotime($row['created_at'])),
        ];
    }
}