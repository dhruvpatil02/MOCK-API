# MOCK-API
API to preform CRUD operation to any endpoint


---

# CRUD API Project (PHP + MySQL)

## Overview

This project provides a simple CRUD (Create, Read, Update, Delete) API built with PHP and MySQL. The API allows applications to interact with any endpoint (e.g., `server.com/any/`). It supports sending JSON data via HTTP methods like `POST`, `GET`, `PUT`, and `DELETE` to perform CRUD operations on a general database structure. The data is stored as JSON in a column called `data`, with an `endpoint` column to specify which endpoint the data belongs to.

## Features

- **Create**: Add new data via the `POST` method. Data is stored in a `data` column as JSON.
- **Read**: Fetch data via the `GET` method based on the `endpoint` column.
- **Update**: Modify existing data via the `PUT` method for a specific endpoint.
- **Delete**: Remove data via the `DELETE` method based on the `endpoint`.

## Prerequisites

- PHP 7.4 or higher
- MySQL or MariaDB
- A web server (Apache/Nginx) with PHP support
- cURL or Postman for testing API

## Database Setup

1. **Create a Database**: Create a MySQL database named `crud_api`.
   ```sql
   CREATE DATABASE crud_api;
   ```

2. **Create a Table**: Create a table named `endpoints` with two columns: `endpoint` and `data`.
   ```sql
   CREATE TABLE endpoints (
       id INT AUTO_INCREMENT PRIMARY KEY,
       endpoint VARCHAR(255) NOT NULL,
       data JSON NOT NULL
   );
   ```

3. **Database Connection**: Modify the database connection settings in the `config.php` file.

   ```php
   <?php
   // config.php
   define('DB_SERVER', 'localhost');
   define('DB_USERNAME', 'root');
   define('DB_PASSWORD', '');
   define('DB_DATABASE', 'crud_api');
   
   // Create a connection
   $conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
   
   if ($conn->connect_error) {
       die("Connection failed: " . $conn->connect_error);
   }
   ?>
   ```

## API Endpoints

### 1. `POST /any/`
- **Description**: Create new data for a specific endpoint.
- **Request Body**: JSON object with the endpoint and data to store.
  
  Example:
  ```json
  {
    "endpoint": "/any/",
    "data": {
      "name": "John Doe",
      "email": "johndoe@example.com",
      "age": 30
    }
  }
  ```

- **Response**: Success message with stored data.

  Example:
  ```json
  {
    "message": "Data created successfully",
    "data": {
      "id": 1,
      "endpoint": "/any/",
      "data": {
        "name": "John Doe",
        "email": "johndoe@example.com",
        "age": 30
      }
    }
  }
  ```

### 2. `GET /any/`
- **Description**: Retrieve data for a specific endpoint.
- **Query Parameter**: `endpoint` - the endpoint for which you want to fetch data.
  
- **Response**: The stored data for the specified endpoint.

  Example:
  ```json
  {
    "id": 1,
    "endpoint": "/any/",
    "data": {
      "name": "John Doe",
      "email": "johndoe@example.com",
      "age": 30
    }
  }
  ```

### 3. `PUT /any/:id`
- **Description**: Update existing data for a specific endpoint.
- **Parameters**: 
  - `id`: The ID of the data item to update.
  
- **Request Body**: JSON object with updated data.

  Example:
  ```json
  {
    "data": {
      "name": "Johnathan Doe",
      "email": "johnathan@example.com",
      "age": 31
    }
  }
  ```

- **Response**: Success message with updated data.

  Example:
  ```json
  {
    "message": "Data updated successfully",
    "data": {
      "id": 1,
      "endpoint": "/any/",
      "data": {
        "name": "Johnathan Doe",
        "email": "johnathan@example.com",
        "age": 31
      }
    }
  }
  ```

### 4. `DELETE /any/:id`
- **Description**: Delete data for a specific endpoint.
- **Parameters**: 
  - `id`: The ID of the data item to delete.

- **Response**: Success message confirming deletion.

  Example:
  ```json
  {
    "message": "Data deleted successfully"
  }
  ```

## Installation and Setup

1. **Clone the repository**:
   ```bash
   git clone https://github.com/your-username/crud-api-php-mysql.git
   cd crud-api-php-mysql
   ```

2. **Set up the Database**: Follow the steps under the **Database Setup** section above to set up your database.

3. **Configure Database Connection**: Modify the `config.php` file to reflect your database settings (hostname, username, password, and database name).

4. **Place Files in Your Web Server Directory**:
   Move the project files to your web server directory (e.g., `/var/www/html/` for Apache or `/usr/share/nginx/html/` for Nginx).

5. **Test the API**: Use tools like Postman or cURL to test the endpoints.

## Example Usage with cURL

- **Create (POST)**:
  ```bash
  curl -X POST http://localhost/any/ -H "Content-Type: application/json" -d '{"endpoint":"/any/","data":{"name":"John Doe","email":"johndoe@example.com","age":30}}'
  ```

- **Read (GET)**:
  ```bash
  curl "http://localhost/any/?endpoint=/any/"
  ```

- **Update (PUT)**:
  ```bash
  curl -X PUT http://localhost/any/1 -H "Content-Type: application/json" -d '{"data":{"name":"Johnathan Doe","email":"johnathan@example.com","age":31}}'
  ```

- **Delete (DELETE)**:
  ```bash
  curl -X DELETE http://localhost/any/1
  ```



## Technologies Used

- **PHP**: Server-side scripting language for building the API.
- **MySQL**: Relational database to store and manage data.
- **cURL**: Command-line tool to interact with the API.
- **Postman**: Tool for testing HTTP requests (optional).




