# Hairsaloon

## Build project

```bash
cd scripts

./build.sh
```

## API

1. Obtain schedule for station on given day

Request:

`GET api/stations/1/schedule/2020-01-10`

Response:
```json
{
    "schedule": [
        {
            "from": "00:00",
            "to": "00:29",
            "available": true
        },
        {
            "from": "00:30",
            "to": "00:59",
            "available": true
        },
        {
            "from": "01:00",
            "to": "01:29",
            "available": false
        },
        ...
    ]
}
```

2. Reserve one or more time slots for station on given day

Request:

`PUT api/stations/2/schedule/2020-01-10`

Response in case of error (empty in case of success):

```json
{
    "message": "Station is not available in selected time slot on given day"
}
```
