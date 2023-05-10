# client-funds
In order to run the application:
1. git clone git@github.com:LikaSKHSweeft/client-funds.git
2. cd client-funds
3. composer install
4. cp .env-example .env
5. Set RATES_API accordingly to exchanges api ( url must be provided fully from https://..)
6. php artisan serve
7. By default, it will run application on http://127.0.0.1:8000 in order to call operations api and analise csv file you should call
8. POST http://127.0.0.1:8000/api/process-operations and pass through body (in postman you can set formdata) **operations** parameter and value should be your csv file
9. In order to run feature test run: **vendor/bin/phpunit** 

Application is build with Laravel 10, PHP 8.1.
All configurations of fee percentages for both type of clients can be found in .env file with following default configurations:

DEPOSIT_PERCENTAGE=0.03
PR_CLIENTS_WITHDRAW_FEE=0.3
PR_CLIENTS_FREE_PER_WEEK_OP=3
BUSINESS_CLIENTS_WITHDRAW_FEE=0.5
PR_CLIENTS_FREE_AMOUNT_PER_WEEK_OP=1000

For differentiating client types and operation types Enums are used. Logic is written mainly in Services, Interfaces are used for clients.
Checking code can be started from controller: **ProcessClientOperationController**. 

In feature test, currency is considered to be same as provided in example, csv file for test is located in public folder, also I provided postmen collection in public folder if needed **funds.postman_collection.json**
