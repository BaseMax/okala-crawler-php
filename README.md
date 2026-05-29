# okala-crawler-php

PHP Language

Saving html pages in file as cache

Export all products in a single json file UTF-8 supported.

## Config

```
STORES = [
    "https://www.okala.com/store/2319",
    "https://www.okala.com/store/10458",
    "https://www.okala.com/store/9871",
    "https://www.okala.com/store/9652",
    "https://www.okala.com/store/2318",
    "https://www.okala.com/store/10381",
    "https://www.okala.com/store/9020",
    "https://www.okala.com/store/9768",
    "https://www.okala.com/store/8840",
    "https://www.okala.com/store/5989",
    "https://www.okala.com/store/10650",
    "https://www.okala.com/store/7500",
    "https://www.okala.com/store/8131",
    "https://www.okala.com/store/9867",
    "https://www.okala.com/store/7791",
    "https://www.okala.com/store/8729",
    "https://www.okala.com/store/9991",
    "https://www.okala.com/store/8662",
]
```

```
CATEGORIES = [
    ("kalabarg",           1467),
    ("refreshments",       1467),
    ("dairy-products",     1462),
    ("groceries",          1461),
    ("home-hygiene",       1471),
    ("beverages",          1465),
    ("spices",             1469),
    ("canned-ready-food",  1464),
    ("cosmetics-hygiene",  1472),
    ("proteins",           1463),
    ("breakfast-goods",    1466),
    ("home-stuff",         1473),
    ("baby-mother-care",   1474),
    ("fruits-vegetables",  1470),
    ("nuts-sweets",        1468),
    ("multiples",          1850),
]
```

## Get Products in a spefic category by any stores nearby me (Tehran)

For example one slug (category): beverages

Tehran lat: 35.805851

Tehran lon: 51.431311

```
curl 'https://apigateway.okala.com/api/unicorn/v2/products/nearby?slug=beverages&lat=35.805851&lon=51.431311' \
  -H 'X-Skip-Authorization: false' \
  -H 'sec-ch-ua-platform: "Windows"' \
  -H 'Authorization: Bearer eyJhbGciOiJSUzI1NiIsImtpZCI6IjEzRjRFNUExQ0NGNUU4NjRBQTI3MzgyMkM3OENERTIxQTM4MkRBOENSUzI1NiIsInR5cCI6ImF0K2p3dCIsIng1dCI6IkVfVGxvY3oxNkdTcUp6Z2l4NHplSWFPQzJvdyJ9.eyJuYmYiOjE3ODAwNzc0ODEsImV4cCI6MTc4MDA3OTI4MSwiaXNzIjoiaHR0cDovL2NlcmJlcnVzLm1lbWJlcnNoaXAiLCJjbGllbnRfaWQiOiJjdXN0b21lcl9jbGllbnRfaWQiLCJzdWIiOiIxMTE1NzA1MCIsImF1dGhfdGltZSI6MTc4MDA1MDIxNCwiaWRwIjoibG9jYWwiLCJ1c2VySWQiOiIxMTE1NzA1MCIsInVzZXJuYW1lIjoiMDkxMzQ5NTA3ODciLCJhbHRlcm5hdGl2ZUN1c3RvbWVySWQiOiIxMTE1NzA1MCIsInRlbmFudCI6Im9rYWxhIiwidG9rZW4taWQiOiJjMjc4OTcyNi0wNjhlLTQ3MDYtYTgwYi00ZDNjMzBmNzkxMzVfOWFjYTg2ODItMjA3YS00MjkwLTgxMzAtZjYxNWNiZGM2NWJiIiwiY2VyYmVydXNJZCI6ImMyNzg5NzI2LTA2OGUtNDcwNi1hODBiLTRkM2MzMGY3OTEzNSIsImp0aSI6IjI1MjU5REJDMUY1QUNDNjJEOUEyMDY3MjhBODZCMTUwIiwiaWF0IjoxNzgwMDc3NDgxLCJzY29wZSI6WyJvZmZsaW5lX2FjY2VzcyJdLCJhbXIiOlsiY3VzdG9tZXJfZ3JhbnRfdHlwZSJdfQ.jKeSTvC8aDzPV2GFfN2mM7qfGsaZX5PcJ6Aa1hAalyMZ7W3Gc7MJ7Ii9yXOSGiAem0pY7q5jfEQcAZgFm74puz_5W4a0A4S9qj4p6djHAhNm7YjxB-j8iQDV1NXwIdTTvQEsZXHT-1pZz-dGeoKsJlISJauY_7ck9Ya3loiS9uzxbxq6jmA4x4OmfczRhLlqn_7vz0vW8FyUtEm5FyL9iTG-ZqPsiA297pWS0uulCojyNxl94-inGDv_KzweaVsOoz9sqvhJgWIXCj2M5QvO2nnqYJxNkauE6FdMtnqWkt1xUYZxiChY7d92AgjYCXIrCRKkreV2WUVmY-h8l-gTaQ' \
  -H 'X-Correlation-Id: 52c919dc-9b20-46b0-ba60-29e71bdd47a6' \
  -H 'sec-ch-ua: "Chromium";v="148", "Google Chrome";v="148", "Not/A)Brand";v="99"' \
  -H 'X-User-Unique-Id: 32063c6e-098f-437f-8777-291d5d7c3786' \
  -H 'sec-ch-ua-mobile: ?0' \
  -H 'ui-version: 2.0' \
  -H 'Accept: application/json, text/plain, */*' \
  -H 'metrix_user_id: null' \
  -H 'session-id: 82e4a31c-2094-45db-abd6-dc828aac0eae' \
  -H 'advertising_id: null' \
  -H 'Referer;' \
  -H 'idfa: null' \
  -H 'source: okala' \
  -H 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36'
```

## Single Product

sId (store id): 2319

pId (product id): 190062

```bash
curl 'https://apigateway.okala.com/api/Unicorn/v1/catalog/pdp?sId=2319&pId=190062' \
  -H 'X-Skip-Authorization: false' \
  -H 'sec-ch-ua-platform: "Windows"' \
  -H 'Authorization: Bearer eyJhbGciOiJSUzI1NiIsImtpZCI6IjEzRjRFNUExQ0NGNUU4NjRBQTI3MzgyMkM3OENERTIxQTM4MkRBOENSUzI1NiIsInR5cCI6ImF0K2p3dCIsIng1dCI6IkVfVGxvY3oxNkdTcUp6Z2l4NHplSWFPQzJvdyJ9.eyJuYmYiOjE3ODAwNzQxMDEsImV4cCI6MTc4MDA3NTkwMSwiaXNzIjoiaHR0cDovL2NlcmJlcnVzLm1lbWJlcnNoaXAiLCJjbGllbnRfaWQiOiJjdXN0b21lcl9jbGllbnRfaWQiLCJzdWIiOiIxMTE1NzA1MCIsImF1dGhfdGltZSI6MTc4MDA1MDIxNCwiaWRwIjoibG9jYWwiLCJ1c2VySWQiOiIxMTE1NzA1MCIsInVzZXJuYW1lIjoiMDkxMzQ5NTA3ODciLCJhbHRlcm5hdGl2ZUN1c3RvbWVySWQiOiIxMTE1NzA1MCIsInRlbmFudCI6Im9rYWxhIiwidG9rZW4taWQiOiJjMjc4OTcyNi0wNjhlLTQ3MDYtYTgwYi00ZDNjMzBmNzkxMzVfOWFjYTg2ODItMjA3YS00MjkwLTgxMzAtZjYxNWNiZGM2NWJiIiwiY2VyYmVydXNJZCI6ImMyNzg5NzI2LTA2OGUtNDcwNi1hODBiLTRkM2MzMGY3OTEzNSIsImp0aSI6IkMzRTU4MDFGQTFEQUZGREZDNzgwNDA3MTQ0Nzc3QzMzIiwiaWF0IjoxNzgwMDc0MTAxLCJzY29wZSI6WyJvZmZsaW5lX2FjY2VzcyJdLCJhbXIiOlsiY3VzdG9tZXJfZ3JhbnRfdHlwZSJdfQ.E3hR7m-QJhiLQ8oBoBnOpqH6WU8LqlbpDyli0alsg7x1qjz4xhoVQcCSvY1uNslmdvI2sByxawWT2RnxNOy4Cbu0rkvFX1SE6SpjV95NoV7GSJELnDiS7Ot8YTdb161G68byJ9egdgBNC0L-uKgdqsYV2xepq8QduHVdWwckD7hx19JO3cmskK2VzynDxGHpu_nz-FhofD8c07p9WEhJl6vTwCbAlWlPIH4TRNJp2AP9EgvVfvValxsRBzwPSpsOiakVJpBrm2YRK88lhA1HSzjpAhQmnDrPLixT2fFF9PpbVoWbWU0aASpOAsgD7c976c9216Sihgp81mFx3xcYwg' \
  -H 'X-Correlation-Id: 7691ac61-a7cc-4b12-8040-e1f50c31f7ac' \
  -H 'sec-ch-ua: "Chromium";v="148", "Google Chrome";v="148", "Not/A)Brand";v="99"' \
  -H 'X-User-Unique-Id: 32063c6e-098f-437f-8777-291d5d7c3786' \
  -H 'sec-ch-ua-mobile: ?0' \
  -H 'ui-version: 2.0' \
  -H 'Accept: application/json, text/plain, */*' \
  -H 'metrix_user_id: null' \
  -H 'session-id: 82e4a31c-2094-45db-abd6-dc828aac0eae' \
  -H 'advertising_id: null' \
  -H 'Referer;' \
  -H 'idfa: null' \
  -H 'source: okala' \
  -H 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36'
```
