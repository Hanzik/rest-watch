# Rest-Watch (REST API)

We've got the world's most unique time keeping devices. Seriously, you want to have these! Join us and integrate our watch database into your very own e-shop for your pleasure.

### Features
  * Provides basic CRUD operations
  * Basic filtering
  * Pagination friendly
  * Accepts JSON payload (and can be easily extended further)
  * Returns JSON responses (and can be modified to talk in another language, like XML)
  * Swagger UI friendly (API internally documented using Swagger specifications) - more info about Swagger [here](http://swagger.com).  

### Requirements
  * PHP 7
  * Nette Framework
  * Doctrine2-compatible database (currently running on MySQL)

### API documentation
 
 ### GET /info
Returns basic info about the Rest-Watch service. You are probably never going to use this.

 ### GET /watches
List watches available in our shop. You can filter the results using one or more of the parameters below. If you ommit page and per_page parameters a default value will be used instead:

    GET /watches
    
    optional: ?title=:title
    optional: ?price=:price
    optional: ?description=:descritpion
    optional: ?page=:description (default=1)
    optional: ?per_page=:per_page (default=20)

**Request:**

    /watches?title=watch&page=3&per_page=2 

**Response:**

```json
{
    "items": [
        {
            "id": 11,
            "title": "Niet",
            "price": 21474,
            "description": "A watch with a water fountain picture",
            "fountain": "R0lGODlhAQABAIAAAAUEBAAAACwAAAAAAQABAAACAkQBADs="
        },
        {
            "id": 12,
            "title": "Watafak",
            "price": 980000,
            "description": "A watch with a water fountain picture",
            "fountain": "R0lGODlhAQABAIAAAAUEBAAAACwAAAAAAQABAAACAkQBADs="
        }
    ]
}
```



 ### GET /watches/{id}
Return watch of the given identifier or 404 if such watch does not exist in our system.

    GET /watches/{id}

**Request:**

    /watches/10

**Response:**

```json
{
    "id": 10,
    "title": "Apple",
    "price": 12345,
    "description": "A watch with a water fountain picture",
    "fountain": "R0lGODlhAQABAIAAAAUEBAAAACwAAAAAAQABAAACAkQBADs="
}
```


 ### POST /watches
 
Upload a watch into our system. Request payload (body) should contain a data in our system (currently JSON or XML is supported, contact our support if you would like to see love for some other format that you might be using).

    POST /watches
    
    required: title (name of the watch)
    required: price (price of the watch, should be an integer)
    required: description (description of the product)
    required: fountain (string representing image in base64 or object with string parameters color and height)
 
**Request:**

    /watches

```json
{
	"title": "Prim",
	"price": "250000",
	"description": "A watch with a water fountain picture",
	"fountain": {
		"color": "blue",
		"height": "20cm"
	}
}
```

Or alternatively:

```json
{
	"title": "Prim",
	"price": "250000",
	"description": "A watch with a water fountain picture",
	"fountain": "R0lGODlhAQABAIAAAAUEBAAAACwAAAAAAQABAAACAkQBADs="
}
```

**Response:**

Created object will be returned in the response.

```json
{
    "id": 16,
    "title": "Prim",
	"price": 250000,
	"description": "A watch with a water fountain picture",
	"fountain": "R0lGODlhAQABAIAAAAUEBAAAACwAAAAAAQABAAACAkQBADs="
}
```

 ### PUT /watches/{id}
 Works quite like the POST method. Allows you to update data on already existing entity. Returns 404 if you guess the id incorrectly, otherwise returns the updated resource. Again, don't forget to upload the new data in the payload.

    PUT /watches/{id}

**Request:**

    /watches/10

```json
{
	"title": "Updated primalex",
	"price": "300000",
	"description": "An updated description",
	"fountain": {
		"color": "somewhat blue",
		"height": "20cm"
	}
}
```

**Response:**
It doesn't get any more exciting than this:
```json
{
	"id": 10,
	"title": "Updated primalex",
	"price": 300000,
	"description": "An updated description",
	"fountain": {
		"color": "somewhat blue",
		"height": "20cm"
	}
}
```

 ### DELETE /watches/{id}
What more would you like to know? Delete deletes the watch you select. It will be super deleted so noone can ever find out what it was. You can trust us on this one, like, totally. Returns 204 on success which further proves that there is nothing left.

    DELETE /watches/{id}

**Request:**

    /watches/10

**Response:**

```json
```

*I told you it's empty*

# Conclusion

Should you have any issues or concerns, don't hesitate to contact us at our very real email *support@rest-watch.com* or create a GitHub issue so we can tell you that this is a completely intended behaviour or ignore your ticket and never reply to you altogether.

### Don't believe us? Believe our customers!
*"I had to divorce my husband, because he refused to give up his Apple Watch."* - **Joana Wazowski**  

*"They are almost like the real thing!"* - **Jimmy Tudeski**  

*"Who are you and how did you get into my house?!"* - **Neighbour Tod**  
