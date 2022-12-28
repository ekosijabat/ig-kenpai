# Itsavirus Assignment - Meal Delivery API



## Specification

- PHP (v 7.4.3)
- MySQL (v 8.0.25)
- Framework Lumen (v 8.3.1)


Inside Lumen framework, I use package : 
- tymon/jwt-auth (version dev-develop) : to create token for authorization
- darkaonline/swagger-lume (v 8) : to generate API documentation



## Installation

- Execute **git clone https://github.com/ekosijabat/itsavirus.git** from terminal.
- Go to **itsavirus** directory.
- Run command **docker-compose --env-file .env up --build** to build docker container. This command will create 3 container : itsavirus_lumen (for source code application), itsavirus_db (for database), and itsavirus_adminer_container (for mysql editor)
- After build docker success, head over to **http://localhost:8000/** and we can see output **Lumen (8.3.1) (Laravel Components ^8.0)**
- Open another terminal tab, and run command **docker exec -it itsavirus_lumen sh**. With this command, we will go to container terminal.
- Run **php artisan migrate** to generate all tables that will needed for application.
- After migration succeeded, head over to **http://localhost:8089**. It will show Adminer MySQL editor.
- Use credentials : server *db*, username *itsa* and password *meals* then click Login.
- All tables successfully created at **itsavirus** database.



## API Documentation

I use Swagger packages for API Documentation. I choose Swagger as my API Documentation, because it can test the API directly from the documentation.

For documentation, I separate to sub module :
- Import Data from JSON
- Reporting
- Searching
- Transaction
- Authentication

We can access the API documentation from **http://localhost:8000/api/docs**.



## API URL

We can access the API using Postman or Insomnia. URL of the API is **http://localhost:8000/api/[api_uri_name]**. Example : *http://localhost:8000/api/impt_restaurant*



## How to import the raw data to the backend

I made API to import the raw data to the backend.
Base on API documentation, all API inside **Import Data from JSON** section will process the raw data.


Step by step to import data (we must use this step for first time import) :
| Step | API                                     | Description                                                       | Tables                                 |
| -----|---------------------------------------- | ----------------------------------------------------------------- | ---------------------------------------|
| 1    | Import Restaurant List                  | collect all restaurant list                                       | restaurant                             |
| 2    | Import Restaurant Dishes                | collect all menus (dishes)                                        | restaurant_menu                        |
| 3    | Import Restaurant Schedule              | collect and mapping open and close hours each restaurant          | restaurant_schedule                    |
| 4    | Import Price for each Restaurant Dishes | collect and mapping all price dishes belonging to each restaurant | restaurant_price_menu                  |
| 5    | Import User                             | collect all users                                                 | users                                  |
| 6    | Import Transaction                      | collect and mapping all transaction                               | transaction_header, transaction_detail |
| 7    | Import User Balance Log                 | collect all purchases created by user                             | users_balance_log                      |
| 8    | Import Restaurant Balance Log           | collect all purchases received by restaurant                      | restaurant_balance_log                 |

### Note:
Please make sure to run **API Import Transaction** before execute *API Import User Balance Log* and *API Import Restaurant Balance Log*. Because both of this API depends on data from API Import Transaction.

### Technical:
- I put *restaurants.json* and *users.json* inside **database** directory.
- Technically, when we hit API to import data, it will get the data from JSON file, and then it will collect the data, and then will check the data exist or not in database. If exist, it will pass the data. Otherwise, if data exist but some value is different, it will update the data.



## Database structure

Every table consist of *created_at*, *created_by*, *updated_at*, *updated_by*, *deleted_at*, and *deleted_by*. I remove those field from this image, because I want to make the image readable.

![Optional Text](../main/public/image/erd.png)



## Response Code
| Name | Description                   | Example                 |
|------|-------------------------------| ------------------------|
| 200  | Success | <pre>{<br>  "code": 200,<br>  "success": true,<br>  "message": "Thank you. We receive and process your orders."<br>}</pre> |
| 401  | Time exceeded | <pre>{<br>  "code": 401,<br>  "success": false,<br>  "message": "Time exceeded. Please re-login."<br>}</pre> |
| 409  | Failed to execute the process | <pre>{<br>  "code": 409,<br>  "success": false,<br>  "message": "There is connection problem. Please try again later.",<br>  "error": "SQLSTATE[42S22]: Column not found: 1054 Unknown column 'test' in 'where clause' (SQL: select * from `restaurant` where `test` = abc limit 1)"<br>}</pre> |
| 422  | Validation | <pre>{<br>  "code": 422,<br>  "success": false,<br>  "message": "You can't check the data because you are not customer"<br>}</pre> |




Thank you.
