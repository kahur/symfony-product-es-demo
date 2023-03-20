## Full text search
**Request Type:** POST

**Endpoint:** /files

**POST DATA KEY**: file[]

Files API only support 2 request DELETE & POST multipart/form-data. To upload files we need to send POST request
to /files endpoint with key file containing list of uploading files. 

**Example response**
```json
{
    "status": "ok",
    "data": [
        {
            "id": 5,
            "name": "certificatebg.jpeg",
            "type": "image/jpeg",
            "path": "/code/var/files/41ee30325ca856f25bc490fc101a7457.jpeg",
            "metaData": [],
            "createdAt": "2023-03-20T17:22:17+00:00",
            "products": []
        }
    ]
}
```

To delete file send DELETE request to endpiont containing id /files/{id}