
# Github Search

[![Build Status](https://travis-ci.org/jvojak/github_search.svg?branch=master)](https://travis-ci.org/jvojak/github_search)


# Introduction 
API endpoint that uses social provider (such as GitHub) external API to provide word popularity based on positive and negative ratings of the word. Score is calculated using the formula below:

> score = positive / ( ( positive + negative ) * 10 )

# Install (Windows)
1. Clone github repository
2. Open: C:\Windows\System32\drivers\etc\hosts
3. Append to end of file:
	`
	127.0.0.1.		github.search
	`
4. Open: C:\xampp\apache\conf\extra\httpd-vhosts.conf
5. Append to the end of the file:
	```xml
	<VirtualHost *:80>
    ServerName github.test
    DocumentRoot "{PROJECT_ROOT_FOLDER}\public"
    SetEnv APPLICATION_ENV "development"
    <Directory "{PROJECT_ROOT_FOLDER}\public">
        DirectoryIndex index.php
        Order allow,deny
        Allow from all
    </Directory>
    </VirtualHost>
	```
6. Open cmd, position yourself into {PROJECT_ROOT_FOLDER} and run following commands:
```
	composer install
	php artisan migrate
```

7. Change .env variables:
```
	DB_CONNECTION=mysql
	DB_HOST=127.0.0.1
	DB_PORT=3306
	DB_DATABASE=db_github_search
	DB_USERNAME=root
	DB_PASSWORD=
```
8. Create database (`localhost/phpmyadmin`) or through cmd, name it `db_github_search`
9. Run migrations (go to {PROJECT_ROOT_FOLDER}:
```
php artisan migrate
```
10. Restart `xampp` service
11. At this point, check that you're able to access your project at `github.test` 

# API Usage

There are two versions of API ( v1 and v2 ) that return the same data in a similar manner, using OAuth2 authentication system:
## API_v1
List of implemented endpoints for v1 API with examples:

`
api/v1/{provider}/search
` -  This endpoint searches `{provider}` (social network) based on the given term (word). `{provider}`'s issues are searched with the given phrase `{term} rocks` (positive results) and `{term} sucks` (negative results). Calculation is based on formula: 

Currently, only Github provider is available
> score = positive / ( ( positive + negative ) * 10 )
### GET Parameters
| Name | Description | Required |
|--|--|--|
| term | Word that will be searched on the `{provider}`. This field is required.|x|
### Example 1
Using Postman:
 - Use endpoint, GET request `http://github.test/api/v1/github/search?term=facebook`
 - Setup authentication - choose OAuth2, create new token:

	Token name - OAuth2 Token
	Auth URL - `http://github.test/oauth/token`
	Client ID - the one that is generated in your database (table oauth_clients, with name 'client'
	Secret - client secret
	Grant Type - Client Credentials

- Expected output:
```json
{
    "term": "facebook",
    "score": "3.0804597701149428"
}
```
### Example 2 - Missing parameters
Using Postman:
 - Use endpoint, GET request `http://github.test/api/v1/github/search`
 - Setup authentication - choose OAuth2, create new token:

	Token name - OAuth2 Token
	Auth URL - `http://github.test/oauth/token`
	Client ID - the one that is generated in your database (table oauth_clients, with name 'client'
	Secret - client secret
	Grant Type - Client Credentials

- Expected output:
```json
{
    "term": null,
    "response": "Insufficient parameters!"
}
```

### Example 3 - Unsupported provider
Using Postman:
 - Use endpoint, GET request `http://github.test/api/v1/twitter?term=facebook`
 - Setup authentication - choose OAuth2, create new token:

	Token name - OAuth2 Token
	Auth URL - `http://github.test/oauth/token`
	Client ID - the one that is generated in your database (table oauth_clients, with name 'client'
	Secret - client secret
	Grant Type - Client Credentials

- Expected output:
```json
{
    "term": "facebook",
    "response": "Provider not yet supported!"
}
```

## API_v2
List of implemented endpoints for v1 API with examples:

`
api/v1/{provider}/search
` -  This endpoint searches `{provider}` (social network) based on the given term (word). `{provider}`'s issues are searched with the given phrase `{term} rocks` (positive results) and `{term} sucks` (negative results). Calculation is based on formula: 

Currently, only Github provider is available
> score = positive / ( ( positive + negative ) * 10 )
### GET Parameters
| Name | Description | Required |
|--|--|--|
| term | Word that will be searched on the `{provider}`. This field is required, and must be between 2 and 255 characters|x|
### Example 1
Using Postman:
 - Use endpoint, GET request `http://github.test/api/v2/github/search?term=facebook`
 - Setup authentication - choose OAuth2, create new token:

	Token name - OAuth2 Token
	Auth URL - `http://github.test/oauth/token`
	Client ID - the one that is generated in your database (table oauth_clients, with name 'client'
	Secret - client secret
	Grant Type - Client Credentials

- Expected output:
```json
{
    "data": {
        "id": 5,
        "term": "facebook",
        "provider": "github",
        "score": "3.0804597701149428",
        "created_at": "2018-11-04 16:30:29",
        "updated_at": "2018-11-04 16:30:29"
    }
}
```
### Example 2 - Missing parameters
Using Postman:
 - Use endpoint, GET request `http://github.test/api/v2/github/search`
 - Setup authentication - choose OAuth2, create new token:

	Token name - OAuth2 Token
	Auth URL - `http://github.test/oauth/token`
	Client ID - the one that is generated in your database (table oauth_clients, with name 'client'
	Secret - client secret
	Grant Type - Client Credentials

- Expected output:
```json
{
    "term": [
        "The term field is required."
    ]
}
```

### Example 3 - Unsupported provider
Using Postman:
 - Use endpoint, GET request `http://github.test/api/v2/twitter?term=facebook`
 - Setup authentication - choose OAuth2, create new token:

	Token name - OAuth2 Token
	Auth URL - `http://github.test/oauth/token`
	Client ID - the one that is generated in your database (table oauth_clients, with name 'client'
	Secret - client secret
	Grant Type - Client Credentials

- Expected output:
```json
{
    "term": "facebook",
    "response": "Provider not yet supported!"
}
```