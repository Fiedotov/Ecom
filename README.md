# Discount Lots

https://discountlots.com/


# Local Development Setup

Local development uses [Laravel Sail](https://laravel.com/docs/9.x/sail) (a Docker based development environment developed and actively supported by Laravel).

1. Clone the repo - `git clone git@github.com:aaayersss/discount-lots.git`
2. Make a copy of the `.env.example` to `.env`
3. Install PHP dependencies - `composer install`
4. Start local development server - `./vendor/bin/sail up -d`
5. Generate application key - `./vendor/bin/sail artisan key:generate`
6. Install JavaScript dependencies - `yarn install`
7. Start local dev server - `yarn dev`
8. Application is available in browser - `http://127.0.0.1` or `http://localhost`

### Commands 

#### Run Migrations
`./vendor/bin/sail artisan migrate`

#### Pull Properties from Salesforce
`./vendor/bin/sail artisan salesforce:pull-properties`


### Deployment

Manual deployment steps include:

1. Login to Cloudways
2. Go to "Applications" > "Discount Lots Ecommerce"
3. Click on "Deployment Via GIT" tab
4. Click "deploy via Git" button
5. Click "Pull Button"
6. After deploy done - SSH to server
- `composer install --no-dev`
- `php artisan migrate --force`
7. In Cloudways - Click on "Application Settings" tab > "Supervisord Jobs"
8. Click restart icon for "Job_1"
9. Build assets locally
- `yarn build`
10. rsync assets to production (change `discount-lots` for whatever your named SSH host is)
- `rsync -avzh ./public/build/ discount-lots:/home/1010215.cloudwaysapps.com/dcsgygrwnw/public_html/public/build`