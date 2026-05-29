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

## Get all products in a spefic category by a spefic store

2319 is store id.

dishwashing-detergents is category slug.

```bash
curl 'https://apigateway.okala.com/api/unicorn/v2/products/store/2319?pC_Id=1471&slug=dishwashing-detergents' \
  -H 'accept: application/json, text/plain, */*' \
  -H 'accept-language: en-US,en;q=0.9,fa;q=0.8,it;q=0.7,tr;q=0.6' \
  -H 'advertising_id: null' \
  -H 'authorization: Bearer eyJhbGciOiJSUzI1NiIsImtpZCI6IjEzRjRFNUExQ0NGNUU4NjRBQTI3MzgyMkM3OENERTIxQTM4MkRBOENSUzI1NiIsInR5cCI6ImF0K2p3dCIsIng1dCI6IkVfVGxvY3oxNkdTcUp6Z2l4NHplSWFPQzJvdyJ9.eyJuYmYiOjE3ODAwOTAzMTYsImV4cCI6MTc4MDA5MjExNiwiaXNzIjoiaHR0cDovL2NlcmJlcnVzLm1lbWJlcnNoaXAiLCJjbGllbnRfaWQiOiJjdXN0b21lcl9jbGllbnRfaWQiLCJzdWIiOiIxMTE1NzA1MCIsImF1dGhfdGltZSI6MTc4MDA1MDIxNCwiaWRwIjoibG9jYWwiLCJ1c2VySWQiOiIxMTE1NzA1MCIsInVzZXJuYW1lIjoiMDkxMzQ5NTA3ODciLCJhbHRlcm5hdGl2ZUN1c3RvbWVySWQiOiIxMTE1NzA1MCIsInRlbmFudCI6Im9rYWxhIiwidG9rZW4taWQiOiJjMjc4OTcyNi0wNjhlLTQ3MDYtYTgwYi00ZDNjMzBmNzkxMzVfOWFjYTg2ODItMjA3YS00MjkwLTgxMzAtZjYxNWNiZGM2NWJiIiwiY2VyYmVydXNJZCI6ImMyNzg5NzI2LTA2OGUtNDcwNi1hODBiLTRkM2MzMGY3OTEzNSIsImp0aSI6IjIwRDEzN0QxODQyNTdDODNEMDMzNEYxRTYyRkQ3MzlBIiwiaWF0IjoxNzgwMDkwMzE2LCJzY29wZSI6WyJvZmZsaW5lX2FjY2VzcyJdLCJhbXIiOlsiY3VzdG9tZXJfZ3JhbnRfdHlwZSJdfQ.NVKYTJaGMN2VrKbalU4PneE40SE9fVZevzSfEXbieKqYq0BoTTSj2VADIhOhAfZmdpiR58IVsekMSprHoKQzIvlAC0qIu6KGnzsgvI_-bKWWTLirG1VMJfwXOipaBl9J2LNRXjY6guBaWRxUp9VguyWnaXuqfp2IXUyJuIFH2Rq2x9qbl8G86Pk4RsuMPtIRwS3joixayNZaj6ilrgwc6L0eu4Qr6UcMRB1e0lNrPjs8xSHYOh3dNEr8PkWUqPbFqkT2YwDSQY9_YflwP7h8q1J0uQAFAi1VXij2iLDF9Sbowe-1zcD3TUOgD3c6vgIbbJjDhzxgaD9vralEUKdw3Q' \
  -H 'idfa: null' \
  -H 'metrix_user_id: null' \
  -H 'origin: https://www.okala.com' \
  -H 'priority: u=1, i' \
  -H 'sec-ch-ua: "Chromium";v="148", "Google Chrome";v="148", "Not/A)Brand";v="99"' \
  -H 'sec-ch-ua-mobile: ?0' \
  -H 'sec-ch-ua-platform: "Windows"' \
  -H 'sec-fetch-dest: empty' \
  -H 'sec-fetch-mode: cors' \
  -H 'sec-fetch-site: same-site' \
  -H 'session-id: f008dd2f-a61d-4eee-b6c1-3c366781078a' \
  -H 'source: okala' \
  -H 'ui-version: 2.0' \
  -H 'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36' \
  -H 'x-correlation-id: 7077c90c-e851-40de-af24-48a568aac6b7' \
  -H 'x-skip-authorization: false' \
  -H 'x-user-unique-id: 32063c6e-098f-437f-8777-291d5d7c3786'
```

Response:

```json
{
    "data": [
        {
            "storeId": 2319,
            "maxOrderLimit": 10,
            "discountPercent": 0,
            "isShowDiscount": false,
            "quantity": 2,
            "hasQuantity": true,
            "id": 191673,
            "name": "مایع جلادهنده ماشین ظرفشویی هوم پلاس حجم 750 میلی لیتر",
            "price": 2300000.0,
            "okPrice": 2300000.0,
            "imageUrl": "https://asset.okala.com/unsigned/rs:fill/size:0:0/plain/s3://cdn/product/439433.png",
            "isBundle": false,
            "supplyStatus": 1,
            "docRankT": 10319560705019998,
            "masterProductId": 341299
        },
        {
            "storeId": 2319,
            "maxOrderLimit": 5,
            "discountPercent": 5,
            "isShowDiscount": true,
            "quantity": 18,
            "hasQuantity": true,
            "id": 196661,
            "name": "مايع ظرفشويي پریل مدل Ultra Cold Power لیمویی 1 لیتری",
            "price": 2277428.0,
            "okPrice": 2149000.0,
            "imageUrl": "https://asset.okala.com/unsigned/rs:fill/size:0:0/plain/s3://cdn/product/8fdef2d3-d9e9-4d8b-8f8e-24cd3bc8a131.jpg",
            "isBundle": false,
            "supplyStatus": 1,
            "docRankT": 10319560705018963,
            "masterProductId": 194586
        },
        {
            "storeId": 2319,
            "maxOrderLimit": 5,
            "discountPercent": 8,
            "isShowDiscount": true,
            "quantity": 10,
            "hasQuantity": true,
            "id": 196662,
            "name": "مایع ظرفشویی پریل مدل Ultra با رایحه لیمو 3.75 لیتری",
            "price": 7771723.0,
            "okPrice": 7149001.0,
            "imageUrl": "https://asset.okala.com/unsigned/rs:fill/size:0:0/plain/s3://cdn/product/974cd6a2-9ac4-4050-bbb1-b0d69bb2a00f.jpg",
            "isBundle": false,
            "supplyStatus": 1,
            "docRankT": 10319560705018962,
            "masterProductId": 194476
        },
        {
            "storeId": 2319,
            "maxOrderLimit": 5,
            "discountPercent": 5,
            "isShowDiscount": true,
            "quantity": 6,
            "hasQuantity": true,
            "id": 197474,
            "name": "مایع ظرفشویی اکتیو مدل Red Fruit and flower حجم 750 میلی لیتر",
            "price": 1286448.0,
            "okPrice": 1217000.0,
            "imageUrl": "https://asset.okala.com/unsigned/rs:fill/size:0:0/plain/s3://cdn/product/69ee0ada-eaa8-442e-9bbc-e89301ceba34.jpg",
            "isBundle": false,
            "supplyStatus": 1,
            "docRankT": 10319560705018958,
            "masterProductId": 195304
        },
...
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

Response:

```
{
    "data": [
        {
            "store": {
                "rank": 3,
                "storeId": 2319,
                "storeName": "دربند",
                "firstDeliveryTime": "امروز - 09 تا 10",
                "logo": "https://asset.okala.com/unsigned/rs:fill/size:0:0/plain/s3://cdn/logo/a77f7e22-fbb1-4bdb-b21b-77c82d062e01.png",
                "distance": 0.5272027734695012,
                "rate": 4.7,
                "hasOnDemandDelivery": false,
                "hasActiveOperatingHour": true,
                "isActive": true,
                "deliveryPrice": 0,
                "storeTypeId": 1,
                "deliveryMethods": [
                    {
                        "title": "Delivery",
                        "description": "ارسال با پیک"
                    },
                    {
                        "title": "Pickup",
                        "description": "تحویل حضوری"
                    }
                ]
            },
            "products": [
                {
                    "storeId": 2319,
                    "maxOrderLimit": 5,
                    "discountPercent": 20,
                    "isShowDiscount": true,
                    "quantity": 18,
                    "hasQuantity": true,
                    "id": 190062,
                    "name": "نوشیدنی انرژی زا فایر پاور 250 میلی لیتری",
                    "price": 900000.0,
                    "okPrice": 714960.0,
                    "imageUrl": "https://asset.okala.com/unsigned/rs:fill/size:0:0/plain/s3://cdn/product/5dcb1871-51b2-4fdb-af98-9d51144bcfcd.jpg",
                    "isBundle": false,
                    "supplyStatus": 1,
                    "masterProductId": 195125
                },
                {
                    "storeId": 2319,
                    "maxOrderLimit": 5,
                    "discountPercent": 2,
                    "isShowDiscount": true,
                    "quantity": 32,
                    "hasQuantity": true,
                    "id": 202676,
                    "name": "نوشیدنی انرژی زا میکس بری نایت کینگ 250 میلی لیتری",
                    "price": 800000.0,
                    "okPrice": 777000.0,
                    "imageUrl": "https://asset.okala.com/unsigned/rs:fill/size:0:0/plain/s3://cdn/product/7682941d-0062-42c3-9ad9-8b14ae8a8cec.jpg",
                    "isBundle": false,
                    "supplyStatus": 1,
                    "masterProductId": 280486
                },
                {
                    "storeId": 2319,
                    "maxOrderLimit": 5,
                    "discountPercent": 0,
                    "isShowDiscount": false,
                    "quantity": 51,
                    "hasQuantity": true,
                    "id": 200449,
                    "name": "نوشیدنی انرژی زا لایف استار 500 میلی لیتری",
                    "price": 1150000.0,
                    "okPrice": 1150000.0,
                    "imageUrl": "https://asset.okala.com/unsigned/rs:fill/size:0:0/plain/s3://cdn/product/912b18ef-4b94-478d-8a7d-4d3103745a91.jpg",
                    "isBundle": false,
                    "supplyStatus": 1,
                    "masterProductId": 195461
                },
                {
                    "storeId": 2319,
                    "maxOrderLimit": 5,
                    "discountPercent": 20,
                    "isShowDiscount": true,
                    "quantity": 43,
                    "hasQuantity": true,
                    "id": 184722,
                    "name": "نوشیدنی انرژی زا تی رکس 250 میلی لیتری ",
                    "price": 800000.0,
                    "okPrice": 640000.0,
                    "imageUrl": "https://asset.okala.com/unsigned/rs:fill/size:0:0/plain/s3://cdn/product/3bc73073-d5b3-44d5-a4ff-e51b581ff109.jpg",
                    "isBundle": false,
                    "supplyStatus": 1,
                    "masterProductId": 295102
                },
                {
                    "storeId": 2319,
                    "maxOrderLimit": 5,
                    "discountPercent": 20,
                    "isShowDiscount": true,
                    "quantity": 6,
                    "hasQuantity": true,
                    "id": 715842,
                    "name": "نوشیدنی انرژی زا فایر پاور 500 میلی لیتری",
                    "price": 1600000.0,
                    "okPrice": 1264960.0,
                    "imageUrl": "https://asset.okala.com/unsigned/rs:fill/size:0:0/plain/s3://cdn/product/3bda00c4-a5cb-4f24-9edc-9a30316436f5.jpg",
                    "isBundle": false,
                    "supplyStatus": 1,
                    "masterProductId": 195297
                },
                {
                    "storeId": 2319,
                    "maxOrderLimit": 5,
                    "discountPercent": 0,
                    "isShowDiscount": false,
                    "quantity": 22,
                    "hasQuantity": true,
                    "id": 207632,
                    "name": "نوشیدنی انرژی زا لایف استار 250 میلی لیتری",
                    "price": 800000.0,
                    "okPrice": 800000.0,
                    "imageUrl": "https://asset.okala.com/unsigned/rs:fill/size:0:0/plain/s3://cdn/product/d38df239-a98a-428c-a336-7ea337d9e1a5.jpg",
                    "isBundle": false,
                    "supplyStatus": 1,
                    "masterProductId": 194883
                },
                {
                    "storeId": 2319,
                    "maxOrderLimit": 5,
                    "discountPercent": 10,
                    "isShowDiscount": true,
                    "quantity": 4,
                    "hasQuantity": true,
                    "id": 184512,
                    "name": "نوشیدنی ویتامین سی شیشه 240 دالاس",
                    "price": 850000.0,
                    "okPrice": 764490.0,
                    "imageUrl": "https://asset.okala.com/unsigned/rs:fill/size:0:0/plain/s3://cdn/product/8a2799eb-0f42-4b2e-baf7-a6d97d73be51.jpg",
                    "isBundle": false,
                    "supplyStatus": 1,
                    "masterProductId": 319318
                },
                {
                    "storeId": 2319,
                    "maxOrderLimit": 5,
                    "discountPercent": 0,
                    "isShowDiscount": false,
                    "quantity": 3,
                    "hasQuantity": true,
                    "id": 201897,
                    "name": "نوشیدنی انرژی زا ویتامین سی پوتکا 240 میلی لیتری",
                    "price": 590000.0,
                    "okPrice": 590000.0,
                    "imageUrl": "https://asset.okala.com/unsigned/rs:fill/size:0:0/plain/s3://cdn/product/a30c76c6-9177-4b29-a5cf-bf6c8d3ad8e6.jpg",
                    "isBundle": false,
                    "supplyStatus": 1,
                    "masterProductId": 195201
                },
                {
                    "storeId": 2319,
                    "maxOrderLimit": 5,
                    "discountPercent": 0,
                    "isShowDiscount": false,
                    "quantity": 16,
                    "hasQuantity": true,
                    "id": 250024,
                    "name": "نوشیدنی انرژی زا بدون شکر لایف استار 250 میلی لیتری",
                    "price": 850000.0,
                    "okPrice": 850000.0,
                    "imageUrl": "https://asset.okala.com/unsigned/rs:fill/size:0:0/plain/s3://cdn/product/ed1249a4-1fc7-4930-8ce4-bc2ee0bb0cf8.jpg",
                    "isBundle": false,
                    "supplyStatus": 1,
                    "masterProductId": 269658
                },
                {
                    "storeId": 2319,
                    "maxOrderLimit": 5,
                    "discountPercent": 2,
                    "isShowDiscount": true,
                    "quantity": 39,
                    "hasQuantity": true,
                    "id": 201853,
                    "name": "نوشابه انرژی زا 250 سی سی وان دی",
                    "price": 899000.0,
                    "okPrice": 881020.0,
                    "imageUrl": "https://asset.okala.com/unsigned/rs:fill/size:0:0/plain/s3://cdn/product/c5e0b3dc-fa0a-4e9a-b763-4a1c4dd6532f.JPG",
                    "isBundle": false,
                    "supplyStatus": 1,
                    "masterProductId": 200904
                },
                {
                    "storeId": 2319,
                    "maxOrderLimit": 5,
                    "discountPercent": 13,
                    "isShowDiscount": true,
                    "quantity": 21,
                    "hasQuantity": true,
                    "id": 273332,
                    "name": "نوشیدنی انرژی زا کلاسیک ولکا استار 250 میلی لیتری",
                    "price": 800000.0,
                    "okPrice": 696000.0,
                    "imageUrl": "https://asset.okala.com/unsigned/rs:fill/size:0:0/plain/s3://cdn/product/b32134e9-6946-4e9e-a53a-672427315d05.jpg",
                    "isBundle": false,
                    "supplyStatus": 1,
                    "masterProductId": 229447
                },
                {
                    "storeId": 2319,
                    "maxOrderLimit": 5,
                    "discountPercent": 0,
                    "isShowDiscount": false,
                    "quantity": 19,
                    "hasQuantity": true,
                    "id": 150290,
                    "name": "نوشیدنی انرژی زا تی ان تی 250 میل لیتری",
                    "price": 1000000.0,
                    "okPrice": 1000000.0,
                    "imageUrl": "https://asset.okala.com/unsigned/rs:fill/size:0:0/plain/s3://cdn/product/40da4497-6189-46a3-9669-b1d004ea489e.jpg",
                    "isBundle": false,
                    "supplyStatus": 1,
                    "masterProductId": 297534
                }
            ],
            "suppliedCount": 12,
            "exactMatchCount": 0,
            "totalScore": 1.2383472846023984E+17,
            "personalizedCount": 0
        },
        {
            "store": {
                "rank": 11,
                "storeId": 53664,
                "storeName": "کیا دربندسری",
                "firstDeliveryTime": "امروز - 09 تا 10",
                "logo": "https://asset.okala.com/unsigned/rs:fill/size:0:0/plain/s3://cdn/logo/a77f7e22-fbb1-4bdb-b21b-77c82d062e01.png",
                "distance": 1.6217119926940406,
                "rate": 4.4,
                "hasOnDemandDelivery": false,
                "hasActiveOperatingHour": true,
                "isActive": true,
                "deliveryPrice": 0,
                "storeTypeId": 1,
                "deliveryMethods": [
                    {
                        "title": "Delivery",
                        "description": "ارسال با پیک"
                    }
                ]
            },
            "products": [
                {
                    "storeId": 53664,
                    "maxOrderLimit": 5,
                    "discountPercent": 2,
                    "isShowDiscount": true,
                    "quantity": 28,
                    "hasQuantity": true,
                    "id": 202676,
                    "name": "نوشیدنی انرژی زا میکس بری نایت کینگ 250 میلی لیتری",
                    "price": 800000.0,
                    "okPrice": 777000.0,
                    "imageUrl": "https://asset.okala.com/unsigned/rs:fill/size:0:0/plain/s3://cdn/product/7682941d-0062-42c3-9ad9-8b14ae8a8cec.jpg",
                    "isBundle": false,
                    "supplyStatus": 1,
                    "masterProductId": 280486
                },
                {
                    "storeId": 53664,
                    "maxOrderLimit": 5,
                    "discountPercent": 0,
                    "isShowDiscount": false,
                    "quantity": 5,
                    "hasQuantity": true,
                    "id": 200449,
                    "name": "نوشیدنی انرژی زا لایف استار 500 میلی لیتری",
                    "price": 1150000.0,
                    "okPrice": 1150000.0,
                    "imageUrl": "https://asset.okala.com/unsigned/rs:fill/size:0:0/plain/s3://cdn/product/912b18ef-4b94-478d-8a7d-4d3103745a91.jpg",
                    "isBundle": false,
                    "supplyStatus": 1,
                    "masterProductId": 195461
                },
                {
                    "storeId": 53664,
                    "maxOrderLimit": 5,
                    "discountPercent": 30,
                    "isShowDiscount": true,
                    "quantity": 17,
                    "hasQuantity": true,
                    "id": 184722,
                    "name": "نوشیدنی انرژی زا تی رکس 250 میلی لیتری ",
                    "price": 800000.0,
                    "okPrice": 559000.0,
                    "imageUrl": "https://asset.okala.com/unsigned/rs:fill/size:0:0/plain/s3://cdn/product/3bc73073-d5b3-44d5-a4ff-e51b581ff109.jpg",
                    "isBundle": false,
                    "supplyStatus": 1,
                    "masterProductId": 295102
                },
                {
                    "storeId": 53664,
                    "maxOrderLimit": 5,
                    "discountPercent": 2,
                    "isShowDiscount": true,
                    "quantity": 4,
                    "hasQuantity": true,
                    "id": 195678,
                    "name": "نوشابه انرژی زا گرویتی نایت کینگ 250 میلی لیتری ",
                    "price": 800000.0,
                    "okPrice": 777000.0,
                    "imageUrl": "https://asset.okala.com/unsigned/rs:fill/size:0:0/plain/s3://cdn/product/0f223112-f599-4b61-8c25-de068fad51b5.jpg",
                    "isBundle": false,
                    "supplyStatus": 1,
                    "masterProductId": 216400
                },
                {
                    "storeId": 53664,
                    "maxOrderLimit": 5,
                    "discountPercent": 9,
                    "isShowDiscount": true,
                    "quantity": 10,
                    "hasQuantity": true,
                    "id": 613368,
                    "name": "نوشیدنی انرژی زا بدون قند با طعم توت فرنگی دالاس 500 میلی لیتری",
                    "price": 1097000.0,
                    "okPrice": 990153.0,
                    "imageUrl": "https://asset.okala.com/unsigned/rs:fill/size:0:0/plain/s3://cdn/product/276d3e8e-1213-49dc-a3ac-6488ecdf9b9a.jpg",
                    "isBundle": false,
                    "supplyStatus": 1,
                    "masterProductId": 194782
                },
                {
                    "storeId": 53664,
                    "maxOrderLimit": 5,
                    "discountPercent": 2,
                    "isShowDiscount": true,
                    "quantity": 14,
                    "hasQuantity": true,
                    "id": 202678,
                    "name": "نوشیدنی انرژی زا بدون قند نایت کینگ 250 میلی لیتری",
                    "price": 800000.0,
                    "okPrice": 777000.0,
                    "imageUrl": "https://asset.okala.com/unsigned/rs:fill/size:0:0/plain/s3://cdn/product/7befe878-457c-4b5d-964c-c31b8c3122ac.jpg",
                    "isBundle": false,
                    "supplyStatus": 1,
                    "masterProductId": 294220
                },
                {
                    "storeId": 53664,
                    "maxOrderLimit": 5,
                    "discountPercent": 10,
                    "isShowDiscount": true,
                    "quantity": 3,
                    "hasQuantity": true,
                    "id": 184512,
                    "name": "نوشیدنی ویتامین سی شیشه 240 دالاس",
                    "price": 850000.0,
                    "okPrice": 764490.0,
                    "imageUrl": "https://asset.okala.com/unsigned/rs:fill/size:0:0/plain/s3://cdn/product/8a2799eb-0f42-4b2e-baf7-a6d97d73be51.jpg",
                    "isBundle": false,
                    "supplyStatus": 1,
                    "masterProductId": 319318
                },
                {
                    "storeId": 53664,
                    "maxOrderLimit": 5,
                    "discountPercent": 5,
                    "isShowDiscount": true,
                    "quantity": 14,
                    "hasQuantity": true,
                    "id": 201897,
                    "name": "نوشیدنی انرژی زا ویتامین سی پوتکا 240 میلی لیتری",
                    "price": 590000.0,
                    "okPrice": 559000.0,
                    "imageUrl": "https://asset.okala.com/unsigned/rs:fill/size:0:0/plain/s3://cdn/product/a30c76c6-9177-4b29-a5cf-bf6c8d3ad8e6.jpg",
                    "isBundle": false,
                    "supplyStatus": 1,
                    "masterProductId": 195201
                },
                {
                    "storeId": 53664,
                    "maxOrderLimit": 5,
                    "discountPercent": 10,
                    "isShowDiscount": true,
                    "quantity": 17,
                    "hasQuantity": true,
                    "id": 191918,
                    "name": "نوشیدنی انرژی زا اورجینال نایت کینگ 250 میلی لیتری",
                    "price": 800000.0,
                    "okPrice": 719000.0,
                    "imageUrl": "https://asset.okala.com/unsigned/rs:fill/size:0:0/plain/s3://cdn/product/12c17d04-e1fd-4cab-a5c6-f091fa6916c0.jpg",
                    "isBundle": false,
                    "supplyStatus": 1,
                    "masterProductId": 208711
                },
                {
                    "storeId": 53664,
                    "maxOrderLimit": 5,
                    "discountPercent": 2,
                    "isShowDiscount": true,
                    "quantity": 11,
                    "hasQuantity": true,
                    "id": 201853,
                    "name": "نوشابه انرژی زا 250 سی سی وان دی",
                    "price": 899000.0,
                    "okPrice": 881020.0,
                    "imageUrl": "https://asset.okala.com/unsigned/rs:fill/size:0:0/plain/s3://cdn/product/c5e0b3dc-fa0a-4e9a-b763-4a1c4dd6532f.JPG",
                    "isBundle": false,
                    "supplyStatus": 1,
                    "masterProductId": 200904
                },
                {
                    "storeId": 53664,
                    "maxOrderLimit": 20,
                    "discountPercent": 5,
                    "isShowDiscount": true,
                    "quantity": 18,
                    "hasQuantity": true,
                    "id": 703,
                    "name": "سینرژی بهنوش",
                    "price": 875000.0,
                    "okPrice": 831250.0,
                    "imageUrl": "https://asset.okala.com/unsigned/rs:fill/size:0:0/plain/s3://cdn/product/429576.png",
                    "isBundle": false,
                    "supplyStatus": 1,
                    "masterProductId": 246286
                },
                {
                    "storeId": 53664,
                    "maxOrderLimit": 5,
                    "discountPercent": 0,
                    "isShowDiscount": false,
                    "quantity": 23,
                    "hasQuantity": true,
                    "id": 150290,
                    "name": "نوشیدنی انرژی زا تی ان تی 250 میل لیتری",
                    "price": 1000000.0,
                    "okPrice": 1000000.0,
                    "imageUrl": "https://asset.okala.com/unsigned/rs:fill/size:0:0/plain/s3://cdn/product/40da4497-6189-46a3-9669-b1d004ea489e.jpg",
                    "isBundle": false,
                    "supplyStatus": 1,
                    "masterProductId": 297534
                }
            ],
            "suppliedCount": 12,
            "exactMatchCount": 0,
            "totalScore": 1.238347284602398E+17,
            "personalizedCount": 0
        },
        {
            "store": {
                "rank": 16,
                "storeId": 5989,
                "storeName": "گلابدره",
                "firstDeliveryTime": "امروز - 09 تا 10",
                "logo": "https://asset.okala.com/unsigned/rs:fill/size:0:0/plain/s3://cdn/logo/a77f7e22-fbb1-4bdb-b21b-77c82d062e01.png",
                "distance": 1.7158098498136884,
                "rate": 4,
                "hasOnDemandDelivery": false,
                "hasActiveOperatingHour": true,
                "isActive": true,
                "deliveryPrice": 0,
                "storeTypeId": 1,
                "deliveryMethods": [
                    {
                        "title": "Delivery",
                        "description": "ارسال با پیک"
                    },
                    {
                        "title": "Pickup",
                        "description": "تحویل حضوری"
                    }
                ]
            },
            "products": [
                {
                    "storeId": 5989,
                    "maxOrderLimit": 5,
                    "discountPercent": 20,
                    "isShowDiscount": true,
                    "quantity": 43,
                    "hasQuantity": true,
                    "id": 190062,
                    "name": "نوشیدنی انرژی زا فایر پاور 250 میلی لیتری",
                    "price": 900000.0,
                    "okPrice": 714960.0,
                    "imageUrl": "https://asset.okala.com/unsigned/rs:fill/size:0:0/plain/s3://cdn/product/5dcb1871-51b2-4fdb-af98-9d51144bcfcd.jpg",
                    "isBundle": false,
                    "supplyStatus": 1,
                    "masterProductId": 195125
                },
                {
                    "storeId": 5989,
                    "maxOrderLimit": 5,
                    "discountPercent": 2,
                    "isShowDiscount": true,
                    "quantity": 7,
                    "hasQuantity": true,
                    "id": 202676,
                    "name": "نوشیدنی انرژی زا میکس بری نایت کینگ 250 میلی لیتری",
                    "price": 800000.0,
                    "okPrice": 777000.0,
                    "imageUrl": "https://asset.okala.com/unsigned/rs:fill/size:0:0/plain/s3://cdn/product/7682941d-0062-42c3-9ad9-8b14ae8a8cec.jpg",
                    "isBundle": false,
                    "supplyStatus": 1,
                    "masterProductId": 280486
                },
                {
                    "storeId": 5989,
                    "maxOrderLimit": 5,
                    "discountPercent": 0,
                    "isShowDiscount": false,
                    "quantity": 11,
                    "hasQuantity": true,
                    "id": 200449,
                    "name": "نوشیدنی انرژی زا لایف استار 500 میلی لیتری",
                    "price": 1150000.0,
                    "okPrice": 1150000.0,
                    "imageUrl": "https://asset.okala.com/unsigned/rs:fill/size:0:0/plain/s3://cdn/product/912b18ef-4b94-478d-8a7d-4d3103745a91.jpg",
                    "isBundle": false,
                    "supplyStatus": 1,
                    "masterProductId": 195461
                },
                {
                    "storeId": 5989,
                    "maxOrderLimit": 5,
                    "discountPercent": 5,
                    "isShowDiscount": true,
                    "quantity": 11,
                    "hasQuantity": true,
                    "id": 184511,
                    "name": "نوشیدنی انرژی زا رکسوس 330 میلی لیتری",
                    "price": 449000.0,
                    "okPrice": 426550.0,
                    "imageUrl": "https://asset.okala.com/unsigned/rs:fill/size:0:0/plain/s3://cdn/product/38e6c637-42a5-422f-b96d-56a7ee5e0256.JPG",
                    "isBundle": false,
                    "supplyStatus": 1,
                    "masterProductId": 314582
                },
                {
                    "storeId": 5989,
                    "maxOrderLimit": 5,
                    "discountPercent": 20,
                    "isShowDiscount": true,
                    "quantity": 8,
                    "hasQuantity": true,
                    "id": 715842,
                    "name": "نوشیدنی انرژی زا فایر پاور 500 میلی لیتری",
                    "price": 1600000.0,
                    "okPrice": 1264960.0,
                    "imageUrl": "https://asset.okala.com/unsigned/rs:fill/size:0:0/plain/s3://cdn/product/3bda00c4-a5cb-4f24-9edc-9a30316436f5.jpg",
                    "isBundle": false,
                    "supplyStatus": 1,
                    "masterProductId": 195297
                },
                {
                    "storeId": 5989,
                    "maxOrderLimit": 5,
                    "discountPercent": 9,
                    "isShowDiscount": true,
                    "quantity": 6,
                    "hasQuantity": true,
                    "id": 613368,
                    "name": "نوشیدنی انرژی زا بدون قند با طعم توت فرنگی دالاس 500 میلی لیتری",
                    "price": 1097000.0,
                    "okPrice": 990153.0,
                    "imageUrl": "https://asset.okala.com/unsigned/rs:fill/size:0:0/plain/s3://cdn/product/276d3e8e-1213-49dc-a3ac-6488ecdf9b9a.jpg",
                    "isBundle": false,
                    "supplyStatus": 1,
                    "masterProductId": 194782
                },
                {
                    "storeId": 5989,
                    "maxOrderLimit": 5,
                    "discountPercent": 10,
                    "isShowDiscount": true,
                    "quantity": 9,
                    "hasQuantity": true,
                    "id": 184512,
                    "name": "نوشیدنی ویتامین سی شیشه 240 دالاس",
                    "price": 850000.0,
                    "okPrice": 764490.0,
                    "imageUrl": "https://asset.okala.com/unsigned/rs:fill/size:0:0/plain/s3://cdn/product/8a2799eb-0f42-4b2e-baf7-a6d97d73be51.jpg",
                    "isBundle": false,
                    "supplyStatus": 1,
                    "masterProductId": 319318
                },
                {
                    "storeId": 5989,
                    "maxOrderLimit": 5,
                    "discountPercent": 5,
                    "isShowDiscount": true,
                    "quantity": 17,
                    "hasQuantity": true,
                    "id": 201897,
                    "name": "نوشیدنی انرژی زا ویتامین سی پوتکا 240 میلی لیتری",
                    "price": 590000.0,
                    "okPrice": 559000.0,
                    "imageUrl": "https://asset.okala.com/unsigned/rs:fill/size:0:0/plain/s3://cdn/product/a30c76c6-9177-4b29-a5cf-bf6c8d3ad8e6.jpg",
                    "isBundle": false,
                    "supplyStatus": 1,
                    "masterProductId": 195201
                },
                {
                    "storeId": 5989,
                    "maxOrderLimit": 5,
                    "discountPercent": 10,
                    "isShowDiscount": true,
                    "quantity": 2,
                    "hasQuantity": true,
                    "id": 191918,
                    "name": "نوشیدنی انرژی زا اورجینال نایت کینگ 250 میلی لیتری",
                    "price": 800000.0,
                    "okPrice": 719000.0,
                    "imageUrl": "https://asset.okala.com/unsigned/rs:fill/size:0:0/plain/s3://cdn/product/12c17d04-e1fd-4cab-a5c6-f091fa6916c0.jpg",
                    "isBundle": false,
                    "supplyStatus": 1,
                    "masterProductId": 208711
                },
                {
                    "storeId": 5989,
                    "maxOrderLimit": 5,
                    "discountPercent": 2,
                    "isShowDiscount": true,
                    "quantity": 9,
                    "hasQuantity": true,
                    "id": 201853,
                    "name": "نوشابه انرژی زا 250 سی سی وان دی",
                    "price": 899000.0,
                    "okPrice": 881020.0,
                    "imageUrl": "https://asset.okala.com/unsigned/rs:fill/size:0:0/plain/s3://cdn/product/c5e0b3dc-fa0a-4e9a-b763-4a1c4dd6532f.JPG",
                    "isBundle": false,
                    "supplyStatus": 1,
                    "masterProductId": 200904
                },
                {
                    "storeId": 5989,
                    "maxOrderLimit": 5,
                    "discountPercent": 10,
                    "isShowDiscount": true,
                    "quantity": 181,
                    "hasQuantity": true,
                    "id": 190828,
                    "name": "نوشیدنی ليموناد زمزم 1 لیتری",
                    "price": 1040000.0,
                    "okPrice": 935000.0,
                    "imageUrl": "https://asset.okala.com/unsigned/rs:fill/size:0:0/plain/s3://cdn/product/284532b4-fead-474a-bbe3-2ef9e6f82347.jpg",
                    "isBundle": false,
                    "supplyStatus": 1,
                    "masterProductId": 194812
                },
                {
                    "storeId": 5989,
                    "maxOrderLimit": 6,
                    "discountPercent": 1,
                    "isShowDiscount": true,
                    "quantity": 60,
                    "hasQuantity": true,
                    "id": 8633,
                    "name": "آب آشامیدنی زمزم 1.5 لیتری",
                    "price": 350000.0,
                    "okPrice": 345000.0,
                    "imageUrl": "https://asset.okala.com/unsigned/rs:fill/size:0:0/plain/s3://cdn/product/9bc9fce5-547d-486a-86d9-a79a69594ef3.jpg",
                    "isBundle": false,
                    "supplyStatus": 1,
                    "masterProductId": 194551
                }
            ],
            "suppliedCount": 12,
            "exactMatchCount": 0,
            "totalScore": 1.238347284602362E+17,
            "personalizedCount": 0
        },
        {
            "store": {
                "rank": 8,
                "storeId": 7791,
                "storeName": "شمیرانات اندرزگو",
                "firstDeliveryTime": "امروز - 09 تا 10",
                "logo": "https://asset.okala.com/unsigned/rs:fill/size:0:0/plain/s3://cdn/logo/a77f7e22-fbb1-4bdb-b21b-77c82d062e01.png",
                "distance": 1.507182540488638,
                "rate": 4.5,
                "hasOnDemandDelivery": false,
                "hasActiveOperatingHour": true,
                "isActive": true,
                "deliveryPrice": 0,
                "storeTypeId": 1,
                "deliveryMethods": [
                    {
                        "title": "Delivery",
                        "description": "ارسال با پیک"
                    },
                    {
                        "title": "Pickup",
                        "description": "تحویل حضوری"
                    }
                ]
            },
            "products": [
                {
                    "storeId": 7791,
                    "maxOrderLimit": 5,
                    "discountPercent": 20,
                    "isShowDiscount": true,
                    "quantity": 22,
                    "hasQuantity": true,
                    "id": 190062,
                    "name": "نوشیدنی انرژی زا فایر پاور 250 میلی لیتری",
                    "price": 900000.0,
                    "okPrice": 714960.0,
                    "imageUrl": "https://asset.okala.com/unsigned/rs:fill/size:0:0/plain/s3://cdn/product/5dcb1871-51b2-4fdb-af98-9d51144bcfcd.jpg",
                    "isBundle": false,
                    "supplyStatus": 1,
                    "masterProductId": 195125
                },
                {
                    "storeId": 7791,
                    "maxOrderLimit": 5,
                    "discountPercent": 2,
                    "isShowDiscount": true,
                    "quantity": 14,
                    "hasQuantity": true,
                    "id": 202676,
                    "name": "نوشیدنی انرژی زا میکس بری نایت کینگ 250 میلی لیتری",
                    "price": 800000.0,
                    "okPrice": 777000.0,
                    "imageUrl": "https://asset.okala.com/unsigned/rs:fill/size:0:0/plain/s3://cdn/product/7682941d-0062-42c3-9ad9-8b14ae8a8cec.jpg",
                    "isBundle": false,
                    "supplyStatus": 1,
                    "masterProductId": 280486
                },
                {
                    "storeId": 7791,
                    "maxOrderLimit": 5,
                    "discountPercent": 0,
                    "isShowDiscount": false,
                    "quantity": 2,
                    "hasQuantity": true,
                    "id": 200449,
                    "name": "نوشیدنی انرژی زا لایف استار 500 میلی لیتری",
                    "price": 1150000.0,
                    "okPrice": 1150000.0,
                    "imageUrl": "https://asset.okala.com/unsigned/rs:fill/size:0:0/plain/s3://cdn/product/912b18ef-4b94-478d-8a7d-4d3103745a91.jpg",
                    "isBundle": false,
                    "supplyStatus": 1,
                    "masterProductId": 195461
                },
                {
                    "storeId": 7791,
                    "maxOrderLimit": 5,
                    "discountPercent": 20,
                    "isShowDiscount": true,
                    "quantity": 4,
                    "hasQuantity": true,
                    "id": 715842,
                    "name": "نوشیدنی انرژی زا فایر پاور 500 میلی لیتری",
                    "price": 1600000.0,
                    "okPrice": 1264960.0,
                    "imageUrl": "https://asset.okala.com/unsigned/rs:fill/size:0:0/plain/s3://cdn/product/3bda00c4-a5cb-4f24-9edc-9a30316436f5.jpg",
                    "isBundle": false,
                    "supplyStatus": 1,
                    "masterProductId": 195297
                },
                {
                    "storeId": 7791,
                    "maxOrderLimit": 5,
                    "discountPercent": 9,
                    "isShowDiscount": true,
                    "quantity": 2,
                    "hasQuantity": true,
                    "id": 613368,
                    "name": "نوشیدنی انرژی زا بدون قند با طعم توت فرنگی دالاس 500 میلی لیتری",
                    "price": 1097000.0,
                    "okPrice": 990153.0,
                    "imageUrl": "https://asset.okala.com/unsigned/rs:fill/size:0:0/plain/s3://cdn/product/276d3e8e-1213-49dc-a3ac-6488ecdf9b9a.jpg",
                    "isBundle": false,
                    "supplyStatus": 1,
                    "masterProductId": 194782
                },
                {
                    "storeId": 7791,
                    "maxOrderLimit": 5,
                    "discountPercent": 2,
                    "isShowDiscount": true,
                    "quantity": 29,
                    "hasQuantity": true,
                    "id": 202678,
                    "name": "نوشیدنی انرژی زا بدون قند نایت کینگ 250 میلی لیتری",
                    "price": 800000.0,
                    "okPrice": 777000.0,
                    "imageUrl": "https://asset.okala.com/unsigned/rs:fill/size:0:0/plain/s3://cdn/product/7befe878-457c-4b5d-964c-c31b8c3122ac.jpg",
                    "isBundle": false,
                    "supplyStatus": 1,
                    "masterProductId": 294220
                },
                {
                    "storeId": 7791,
                    "maxOrderLimit": 5,
                    "discountPercent": 0,
                    "isShowDiscount": false,
                    "quantity": 5,
                    "hasQuantity": true,
                    "id": 201897,
                    "name": "نوشیدنی انرژی زا ویتامین سی پوتکا 240 میلی لیتری",
                    "price": 590000.0,
                    "okPrice": 590000.0,
                    "imageUrl": "https://asset.okala.com/unsigned/rs:fill/size:0:0/plain/s3://cdn/product/a30c76c6-9177-4b29-a5cf-bf6c8d3ad8e6.jpg",
                    "isBundle": false,
                    "supplyStatus": 1,
                    "masterProductId": 195201
                },
                {
                    "storeId": 7791,
                    "maxOrderLimit": 5,
                    "discountPercent": 10,
                    "isShowDiscount": true,
                    "quantity": 12,
                    "hasQuantity": true,
                    "id": 191918,
                    "name": "نوشیدنی انرژی زا اورجینال نایت کینگ 250 میلی لیتری",
                    "price": 800000.0,
                    "okPrice": 719000.0,
                    "imageUrl": "https://asset.okala.com/unsigned/rs:fill/size:0:0/plain/s3://cdn/product/12c17d04-e1fd-4cab-a5c6-f091fa6916c0.jpg",
                    "isBundle": false,
                    "supplyStatus": 1,
                    "masterProductId": 208711
                },
                {
                    "storeId": 7791,
                    "maxOrderLimit": 5,
                    "discountPercent": 2,
                    "isShowDiscount": true,
                    "quantity": 26,
                    "hasQuantity": true,
                    "id": 201853,
                    "name": "نوشابه انرژی زا 250 سی سی وان دی",
                    "price": 899000.0,
                    "okPrice": 881020.0,
                    "imageUrl": "https://asset.okala.com/unsigned/rs:fill/size:0:0/plain/s3://cdn/product/c5e0b3dc-fa0a-4e9a-b763-4a1c4dd6532f.JPG",
                    "isBundle": false,
                    "supplyStatus": 1,
                    "masterProductId": 200904
                },
                {
                    "storeId": 7791,
                    "maxOrderLimit": 5,
                    "discountPercent": 13,
                    "isShowDiscount": true,
                    "quantity": 9,
                    "hasQuantity": true,
                    "id": 273332,
                    "name": "نوشیدنی انرژی زا کلاسیک ولکا استار 250 میلی لیتری",
                    "price": 800000.0,
                    "okPrice": 696000.0,
                    "imageUrl": "https://asset.okala.com/unsigned/rs:fill/size:0:0/plain/s3://cdn/product/b32134e9-6946-4e9e-a53a-672427315d05.jpg",
                    "isBundle": false,
                    "supplyStatus": 1,
                    "masterProductId": 229447
                },
                {
                    "storeId": 7791,
                    "maxOrderLimit": 5,
                    "discountPercent": 10,
                    "isShowDiscount": true,
                    "quantity": 299,
                    "hasQuantity": true,
                    "id": 190828,
                    "name": "نوشیدنی ليموناد زمزم 1 لیتری",
                    "price": 1040000.0,
                    "okPrice": 935000.0,
                    "imageUrl": "https://asset.okala.com/unsigned/rs:fill/size:0:0/plain/s3://cdn/product/284532b4-fead-474a-bbe3-2ef9e6f82347.jpg",
                    "isBundle": false,
                    "supplyStatus": 1,
                    "masterProductId": 194812
                },
                {
                    "storeId": 7791,
                    "maxOrderLimit": 5,
                    "discountPercent": 3,
                    "isShowDiscount": true,
                    "quantity": 255,
                    "hasQuantity": true,
                    "id": 428,
                    "name": "نوشابه کولا کوکاکولا 1.5 لیتری",
                    "price": 1110000.0,
                    "okPrice": 1076700.0,
                    "imageUrl": "https://asset.okala.com/unsigned/rs:fill/size:0:0/plain/s3://cdn/product/d31abd01-ab05-4f31-96fe-6061224b096e.jpg",
                    "isBundle": false,
                    "supplyStatus": 1,
                    "masterProductId": 195278
                }
            ],
            "suppliedCount": 12,
            "exactMatchCount": 0,
            "totalScore": 1.2383472846023618E+17,
            "personalizedCount": 0
        }
        ...
    ],
    "success": true,
    "tags": [
        {
            "key": "storeIds",
            "value": [
                2319,
                53664,
                5989,
                7791,
                2045,
                2318,
                9768,
                52313,
                53068,
                10988,
                53416,
                10817,
                11150,
                9170,
                51376,
                10872,
                53417,
                10411,
                7874,
                9268,
                8720,
                51440,
                2228
            ]
        }
    ],
    "errorMessage": null
}
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

Response:

```
{
    "id": 190062,
    "storeId": 2319,
    "storeName": "دربند",
    "name": "نوشیدنی انرژی زا فایر پاور 250 میلی لیتری",
    "okPrice": 714960.0,
    "price": 900000.0,
    "quantity": 18,
    "supplyStatus": 1,
    "isBundle": false,
    "isShowDiscount": true,
    "categoryName": "نوشابه",
    "categoryWebLink": "/category/soft-drinks",
    "description": "نوشیدنی انرژی زا فایر پاور 250 میلی لیتری",
    "brandName": "فایرپاور",
    "brandLatinName": "Firepower",
    "brandImage": "https://asset.okala.com/unsigned/rs:fill/size:0:0/plain/s3://cdn/brand/dcf68ccd-2daa-42eb-a4a7-296f92a3196a.jpg",
    "images": [
        "https://asset.okala.com/unsigned/rs:fill/size:0:0/plain/s3://cdn/product/5dcb1871-51b2-4fdb-af98-9d51144bcfcd.jpg"
    ],
    "discountPercent": 20,
    "storeTypeId": 1,
    "maxOrderLimit": 5
}
```
