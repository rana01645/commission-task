# Commission task App

System requirements:
- The application is built using PHP 7.3
- Composer is required to install the dependencies

How to run the program:
1. Clone the repository
2. Run `composer install`
3. copy the .env.example file to .env and set the correct url for `CURRENCY_CONVERTER_API`
4. Run `php src/script.php assets/input.csv` to run the program
5. Run `composer run test` to run the tests
6. Complete automation test that takes the provided input in the task, processes it and checks the output: 
   `CommissionCalculatorTest.php`
