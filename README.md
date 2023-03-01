# web-scraper
Web scraper console application for crawling https://wltest.dns-systems.net/ website's subscription packages.
Get data JSON formatted ordered descendant by annual price with the following info: title, description, price and discount (if applicable).

### Prerequisites
`symfony/panther` - library used for getting the HTML elements and query the DOM. In other cases used for functional tests.

### Run application
1. Clone repository `git clone git@github.com:andreicotaga/web-scraper.git`
2. In the root directory `web-scraper` run `docker-compose up -d`

### Run unit tests
1. Enter the container `docker exec -it php sh`
2. Execute `php bin/phpunit --testdox --coverage-text`

### Application composite
#### Commands
1. `bin/console app:scrape` - it scrapes the given website and adds data to DB
2. `bin/console app:fetch`  - it fetches the data in a JSON format and outputs it in console

