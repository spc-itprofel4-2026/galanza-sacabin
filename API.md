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
