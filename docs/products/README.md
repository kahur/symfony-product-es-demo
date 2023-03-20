## Full text search
**Request Type:** POST

**Endpoint:** /products/find

**Example payloads:**

**Full text title search**
```json
{
    "filter": {
       "title" : "Title" // field name and value to search
    }
}
```

**Search by id**
```json
{
    "filter": {
       "id" : 19
    }
}
```
**Search by description**
```json
{
    "filter": {
       "shortDesc" : 19
    }
}
```

**Search by product detail**
```json
{
    "filter": {
       "details" : {
         "detailValue" : "some value",
         "detailName" : "long_description"
       }
    }
}
```
**example resopnse**
```json
{
    "status": "ok",
    "data": [
        {
            "id": 16,
            "title": "Snickers Shoes",
            "shortDesc": "This is short description of shoes",
            "categories": [
                {
                    "id": 15,
                    "name": "Shoes",
                    "createdAt": null,
                    "updatedAt": null
                }
            ],
            "files": [
                {
                    "id": 3,
                    "name": "87abac60484639de5d8085d8afb2f17d.png",
                    "type": "image/png",
                    "path": "/code/var/files/a882c545cf520d4d0ed9f780706b0c5d.png",
                    "metaData": []
                },
                {
                    "id": 4,
                    "name": "png-clipart-smiley-smiley.png",
                    "type": "image/png",
                    "path": "/code/var/files/4e106b0df095cf7404a7cd30594b6d72.png",
                    "metaData": []
                }
            ],
            "details": [
                {
                    "id": 16,
                    "detailName": "size",
                    "detailValue": "42",
                    "createdAt": null,
                    "updatedAt": null
                },
                {
                    "id": 17,
                    "detailName": "color",
                    "detailValue": "Black",
                    "createdAt": null,
                    "updatedAt": null
                }
            ],
            "createdAt": "2023-03-20T11:59:38+00:00",
            "updatedAt": null
        }
    ]
}
```
## Product detail

**Request Type:** GET

**Endpoint:** /products/{id}

**Example Response:**
```json
{
    "status": "ok",
    "data": {
        "id": 19,
        "title": "My Title",
        "shortDesc": "My description",
        "categories": [
            {
                "id": 20,
                "name": "My new category!",
                "createdAt": null,
                "updatedAt": null
            }
        ],
        "files": [
            {
                "id": 4,
                "name": "png-clipart-smiley-smiley.png",
                "type": "image/png",
                "path": "/code/var/files/4e106b0df095cf7404a7cd30594b6d72.png",
                "metaData": []
            }
        ],
        "details": [
            {
                "id": 26,
                "detailName": "my detail name",
                "detailValue": "my detail value",
                "createdAt": null,
                "updatedAt": null
            },
            {
                "id": 27,
                "detailName": "my detail name 1",
                "detailValue": "my detail value 1",
                "createdAt": null,
                "updatedAt": null
            }
        ],
        "createdAt": "2023-03-20T16:42:04+00:00",
        "updatedAt": null
    }
}
```
*same response is provided as result of update / create / detail or geting list of products*

## Create product

**Request Type:** POST

**Endpoint:** /products

**Example payload:**
```json
{
    "title": "My Title" ,
    "shortDesc": "My description",
    "categories": [
        {
            "name" : "My new category!"
        }
    ],
    "images": [4],
    "details": [
        {
            "detailName": "my detail name",
            "detailValue": "my detail value"
        },
        {
            "detailName": "my detail name 1",
            "detailValue": "my detail value 1"
        }
    ]
}
```
*categories, images and details also support only numbers for example:*

images:
```json
[
  {
    "id": 4,
    "name": "png-clipart-smiley-smiley.png",
    "type": "image/png",
    "path": "/code/var/files/4e106b0df095cf7404a7cd30594b6d72.png",
    "metaData": []
  }
]
```
*is also fully valid format and will be properly mapped to an existing image. In PATCH request this will also execute update on the entry*

*if only array of numbers is provided in categories, images or details fields it will be mapped to the existing item with given id*

categories:
```json
{
    "categories" : [1, 2]
}
```
*will be mapped to category with id 2 and id 1*


## Update product

**Request Type:** PATCH

**Endpoint:** /products

**Example payload:**
```json
{
    "id" : 19,
    "title": "My Title" ,
    "shortDesc": "My description",
    "categories": [
        16, // assign existing category to product
        {
            "id": 20,
            "name": "My new category!",
            "createdAt": null,
            "updatedAt": null
        }, // this is already assigned category created before ( if we remove the object or id category will be dropped from product )
        {
            "name" : "Create new category for product"
        } // this will create new category for product
    ],
    "images": [
      4 // existing image assigned if we remove this ID the image will be dropped for this product
    ],
    "details": [
          {
            "id": 26,
            "detailName": "my detail name",
            "detailValue": "my detail value",
            "createdAt": null,
            "updatedAt": null
          }, // this detail will remain unchanged if we remove the entry, the detail will be remove as well
          {
            "id": 27,
            "detailName": "Let's change this detail name",
            "detailValue": "Let's change also value",
            "createdAt": null,
            "updatedAt": null
          }, // this detail will be updated
          {
            "detailName": "adding new detail to existing product",
            "detailValue": "Awesome!",
            "createdAt": null,
            "updatedAt": null
          } // this will create new detail
    ]
}
```

## Delete product

**Request Type:** DELETE

**Endpoint:** /products/{id}

**Response**
```json
{
    "status": "ok",
    "data": []
}
```
