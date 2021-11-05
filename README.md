# Shop Cart Test Project

## INSTALLATION

1. Clone this repo
2. Run docker environment using command:

```
docker-compose up -d
```

3. Check DB IP host (symfony-ddd-test-project_db_1) using command:

```
docker inspect -f '{{.Name}} - {{range .NetworkSettings.Networks}}{{.IPAddress}}{{end}}' $(docker ps -aq)
```

Then enter correct IP address to `.env`, for example for IP `172.21.0.2`:

```
DATABASE_URL="mysql://someuser:somepass@172.20.0.2:3306/dockerizeme_db?serverVersion=8.0&charset=utf8"
```

4. Enter to the docker container using command:

```
docker exec -it symfony-ddd-test-project_web_1 bash
```

5. Install dependencies inside container using command:

```
composer install
```

6. Run doctrine migrations using commands:

```
bin/console doctrine:migrations:migrate
bin/console doctrine:fixtures:load
```

7. All endpoints can execute using following host:

```
http://localhost:8080
```

## PRODUCTS

### List products in catalog

* GET /api/products-catalog/list
* Optional parameter: `page` (default value: `1`) - number of page (started from `1`)
* Optional parameter: `per_page` (default value: `3`) - number of products on list

### Add product to catalog

* POST /api/products-catalog/create-product
* Required parameter: `product_name` - name of the product
* Required parameter:  `product_description` - description of the product
* Required parameter: `product_amount` - amount of product in shop
* Required parameter: `product_currency_code` - currency code of the product (possible value: `PLN`)

### Change product name

* PUT /api/products-catalog/change-product-name
* Required parameter: `product_uuid` - uuid of the product
* Required parameter: `product_name` - name of the product

### Change product price

* PUT /api/products-catalog/change-product-price
* Required parameter: `product_uuid` - uuid of the product
* Required parameter: `product_amount` - amount of product in shop
* Required parameter: `product_currency_code` - currency code of the product (possible value: `PLN`)

### Delete product from catalog

* DELETE /api/products-catalog/remove-product
* Required parameter: `product_uuid` - uuid of the product

## SHOP CARTS

### List products in catalog

* GET /api/shop-cart/list
* Optional parameter: `page` (default value: `1`) - number of page (started from `1`)
* Optional parameter: `per_page` (default value: `3`) - number of shop carts on list

### Add new empty shop cart

* POST /api/shop-cart/create-shop-cart

### Add product to shop cart

* PUT /api/shop-cart/add-to-shop-cart
* Required parameter: `shop_cart_uuid` - uuid of the shop cart
* Required parameter: `product_uuid` - uuid of the product
* Required parameter: `quantity` - quantity of the product

### Delete product from shop cart

* DELETE /api/shop-cart/remove-from-shop-cart
* Required parameter: `shop_cart_uuid` - uuid of the shop cart
* Required parameter: `product_uuid` - uuid of the product