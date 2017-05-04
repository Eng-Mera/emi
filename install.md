

#Install aglio
npm install -g aglio

php artisan api:docs --output-file public/api-docs/docs.md
aglio -i public/api-docs/docs.md -o public/api-docs/index.html
