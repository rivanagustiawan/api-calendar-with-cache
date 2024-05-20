API Calendar Holiday & Available date For husnavilla.com with cache file
==========================

## ENDPOINTS

```
GET /api/calendar?month=5
```

Response:

```
[
    "calendar": {
        "0": {
            "day": 29,
            "holiday": false,
            "isMonth": false,
            "isMissed": true
        },
        "1": {
            "day": 30,
            "holiday": false,
            "isMonth": false,
            "isMissed": true
        },
        "2": {
            "day": 31,
            "holiday": false,
            "isMonth": false,
            "isMissed": true
        },
        ...
    },
    "holiday": {
        {
            "holiday_date": "2024-02-29",
            "holiday_name": "Umanis Galungan",
            "is_national_holiday": false,
            "day": 29
        },
        {
            "holiday_date": "2024-02-28",
            "holiday_name": "Hari Raya Galungan",
            "is_national_holiday": false,
            "day": 28
        },
        ...
    }
]
```
