# Weather Service API, version 1

Every request needs the header `X-API-key`.

Base path: `/api/v1`

## POST /weather/refresh

**What it does:**
Gets the current temperature from the weather source and saves it.

**Success response:**
Returns the refreshed weather data and its status.

**Errors:**

* `400 Bad Request` — The request is invalid.
* `401 Unauthorized` — The API key is missing or invalid.
* `429 Too Many Requests` — Too many requests were sent.
* `500 Internal Server Error` — The weather service failed to refresh the data.
* `503 Service Unavailable` — The weather provider is temporarily unavailable.

## GET /weather/logs

**What it does:**
Returns saved readings, newest first.

**Options:**

* `limit` — Number of readings to return, from 1 to 50.
* `format` — Response format: `json` or `xml`.

Example:

`GET /api/v1/weather/logs?limit=10&format=json`

**Success response:**
Returns a list of saved weather readings.

Example response:

```json
{
  "logs": [
    {
      "id": 1,
      "city": "Iligan City",
      "temperatureC": 29.2,
      "fetchedAt": "2026-10-08T02:08:25"
    }
  ]
}
```

**Errors:**

* `400 Bad Request` — The query option is invalid.
* `401 Unauthorized` — The API key is missing or invalid.
* `500 Internal Server Error` — The server failed to retrieve the logs.

## GET /weather/logs/{id}

**What it does:**
Returns one saved reading.

**Success response:**
Returns `200 OK` when the requested log is found.

Example response:

```json
{
  "id": 1,
  "city": "Iligan City",
  "temperatureC": 29.2,
  "fetchedAt": "2026-10-08T02:08:25"
}
```

**Errors:**

* `400 Bad Request` — The log ID is invalid.
* `401 Unauthorized` — The API key is missing or invalid.
* `404 Not Found` — The requested weather log does not exist.
* `500 Internal Server Error` — The server failed to retrieve the log.

# Ticket Routing Service API, version 1

Every request needs the header `X-API-Key`.

Base path: `/api/v1`

Every error has the same shape. `field` appears only when one input is to blame:

```json
{"status": 422, "error": {"code": "invalid_format", "message": "format must be json or xml.", "field": "format"}}
```

A ticket can be routed to several offices, so `offices` is always a list.
The list is empty when the ticket is not routed yet.

## POST /tickets

**What it does:**
Saves a new ticket. The body is JSON with `subject` (required, 1 to 200 characters) and `offices` (optional list).

**Success response:** `201 Created` with the saved ticket.

```json
{
  "status": 201,
  "data": {
    "id": 12,
    "subject": "Cannot log in and need a grade correction",
    "offices": ["IT Office", "Registrar"],
    "createdAt": "2026-10-13T09:15:00"
  }
}
```

**Errors:**

* `400 invalid_json`: the body is not valid JSON.
* `401 unauthorized`: the API key is missing or wrong.
* `422 invalid_subject`: `subject` is missing, empty, or longer than 200 characters. `field` is `subject`.
* `422 invalid_offices`: `offices` is not a list of known office names. `field` is `offices`.

## GET /tickets

**What it does:**
Returns saved tickets, newest first.

**Options:**

* `limit`: a whole number from 1 to 50. Default 10.
* `format`: `json` or `xml`. Default `json`.

Example: `GET /api/v1/tickets?limit=10&format=json`

**Success response:** `200 OK`

```json
{
  "status": 200,
  "data": [
    {
      "id": 12,
      "subject": "Cannot log in and need a grade correction",
      "offices": ["IT Office", "Registrar"],
      "createdAt": "2026-10-13T09:15:00"
    }
  ]
}
```

With `format=xml` the same ticket looks like this:

```xml
<ticket>
  <id>12</id>
  <subject>Cannot log in and need a grade correction</subject>
  <offices>
    <office>IT Office</office>
    <office>Registrar</office>
  </offices>
  <createdAt>2026-10-13T09:15:00</createdAt>
</ticket>
```

**Errors:**

* `401 unauthorized`: the API key is missing or wrong.
* `422 invalid_limit`: `limit` is not a whole number from 1 to 50. `field` is `limit`.
* `422 invalid_format`: `format` is not `json` or `xml`. `field` is `format`.
* `500 invalid_xml`: the XML did not match the schema, so nothing was sent.

## GET /tickets/{id}

**What it does:**
Returns one saved ticket.

**Success response:** `200 OK`

```json
{
  "status": 200,
  "data": {
    "id": 12,
    "subject": "Cannot log in and need a grade correction",
    "offices": ["IT Office", "Registrar"],
    "createdAt": "2026-10-13T09:15:00"
  }
}
```

**Errors:**

* `401 unauthorized`: the API key is missing or wrong.
* `404 not_found`: no ticket has that id.