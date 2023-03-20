## List of all categories
**Request Type:** GET

**Endpoint:** /categories

**Example response**
```json
{
  "status": "ok",
  "data": [
    {
      "id": 15,
      "name": "Shoes",
      "createdAt": null,
      "updatedAt": null
    },
    {
      "id": 18,
      "name": "Super cool shoes",
      "createdAt": null,
      "updatedAt": null
    },
    {
      "id": 19,
      "name": "My new category!",
      "createdAt": null,
      "updatedAt": null
    },
    {
      "id": 20,
      "name": "My new category!",
      "createdAt": null,
      "updatedAt": null
    }
  ]
}
```

## Category detail

**Request Type:** GET

**Endpoint:** /categories/{id}

**Example Response:**
```json
{
  "status": "ok",
  "data": {
    "id": 20,
    "name": "My new category!",
    "createdAt": null,
    "updatedAt": null,
    "parent": null,
    "products": [
      {
        "id": 19,
        "title": "My Title",
        "shortDesc": "My description",
        "createdAt": "2023-03-20T16:42:04+00:00",
        "updatedAt": null
      }
    ]
  }
}
```
*same response is provided as result of update / create / detail or geting list of categories*

## Create category

**Request Type:** POST

**Endpoint:** /categories

**Example payload:**
```json
{
  "id" : 1,
  "name" : "My updated category",
  "parent" : 1 // if provided category will be assigned as sub to this parent
}
```

## Delete category

**Request Type:** DELETE

**Endpoint:** /categories/{id}

**Response**
```json
{
    "status": "ok",
    "data": []
}
```

Category endpoint does not support association with product, even it's listing the association must happen on product endpoint
