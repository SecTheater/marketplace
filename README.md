
[![Latest Version on Packagist](https://img.shields.io/packagist/v/sectheater/marketplace.svg?style=flat-square)](https://packagist.org/packages/sectheater/marketplace)
[![MadeWithLaravel.com shield](https://madewithlaravel.com/storage/repo-shields/1161-shield.svg)](https://madewithlaravel.com/p/marketplace/shield-link)
<!-- [![Total Downloads](https://img.shields.io/packagist/dt/sectheater/laravel-jarvis.svg?style=flat-square)](https://packagist.org/packages/sectheater/marketplace) -->

> the marketplace CheatSheet will be availble soon 


<hr>

## marketplace provides you the following :
### 1. Product & Product Variation System
Create simple products, and complex ones using variation system by attaching the product to a specific type.
### 2. Coupon System
  Generate , Validate , and purchase them while checkout easily.
### 3. Wishlist / Cart System
  - CRUD the wishlist/cart easily 
  - get the total/subtotal of the whole cart/wishlist and optionally after sale/coupons applied.
  - stock handling behind the scenes .
### 4. Category System
  - Attach product, type of products to a category.
  
### 5. Sale System
  - Setup sale for category, specific product, type of products.
  
### 6. Authorizing Users & Managing Roles.

## Installation Steps

### 1. Require the Package

After creating your new Laravel application you can include the marketplace package with the following command: 

```bash
composer require sectheater/marketplace:dev-master
```

### 2. Add the DB Credentials & APP_URL

Next make sure to create a new database and add your database credentials to your .env file:

```
DB_HOST=localhost
DB_DATABASE=homestead
DB_USERNAME=homestead
DB_PASSWORD=secret
```

You will also want to update your website URL inside of the `APP_URL` variable inside the .env file:

```
APP_URL=http://localhost:8000
```

### 3. Getting Your Environment Ready.

#### Just Run The following command.


```bash

 php artisan sectheater-market:install     
 
 ```

##### Command Interpretation
- The command just publishes the marketplace's config, migrations,helpers and seeder.

Notice : You may need to run the autoload composer command to reload the changes.

```
 composer dump-autoload -o 
```
Another Notice : Don't forget to delete the default users table migration, as marketplace ships with a default one as well.


### 4. Sample Usage
##### 4.1 Creating a Product with Variation.

```php
Product::generate([
  'user_id' => auth()->id(),
  'name' => 'laptop',
  'description' => 'Fancy laptop',
  'price' => 15000,
  'category' => 'electronics',
  'type' => ['name' => 'MacBook Pro', 'stock' => 20],
  'details' => ['color' => 'Silver', 'dummy-feature' => 'dummy-text']
);

```
##### 4.2 Filter products by criteria.

```php

Product::fetchByVariations(['locations' => 'U.K', 'variations' => ['size' => 'XL', 'color' => 'red'], 'categories' => 'clothes']);

```
- Add custom Criterion and use it while searching.

##### 4.3 get Cart total/subtotal.

Out of the blue, you may use a couple of methods to retrieve the total/subtotal
```php
Cart::subtotal();
Cart::total(); // returns total after applying tax.
Cart::total($coupons); // Collection of coupons passed, returns total after applying tax and coupons.
Cart::subtotalAfterCoupon($coupons);

```
Suprisingly every single method you can use for a cart, you can use for a wishlist ( since we can consider it as a virtual cart ) 

##### 4.4 get Cart item .

```php

Cart::item(1); // get the item with id of 1

Cart::item(1,  ['color' => 'blue', 'size' => 'L']); // get the item with the id of 1 and should have these attributes.

Cart::item(null, ['color' => 'blue','size' => 'L']); // get the current authenticated user's cart which has these attributes assuming that these attributes identical to the database record.

Cart::item(null, ['color' => 'blue','size' => 'L'] , 'or');  // get the current authenticated user's cart which has any of these attributes.

```

##### 4.4 Generate Coupons.
```php
Coupon::generate([
  'user_id' => auth()->id(),
  'active' => true,
  'percentage' => 10.5,
  'expires_at' => Carbon::now()->addWeeks(3)->format('Y-m-d H:i:s')
]);
```

##### 4.5 Validate Coupons.

```php
$coupon = Coupon::first(); // valid one
Coupon::validate($coupon); // returns true

```
##### 4.6 Deactivate Coupons.
```php
$coupon = Coupon::first(); 
Coupon::deactivate($coupon->id);
```
##### 4.7 Purchas Coupons.
```php
$coupon = Coupon::first();
Coupon::purchase($coupon); // Purchase the coupon for the current authenticated user.
Coupon::purchase($coupon, $anotherUser); // Purchase the coupon for the passed user.

```

##### 4.7 Purchased Coupons.
- It releases the invalid purchased coupons automatically.
```php
Coupon::purchased(); // returns the only valid purchased coupons.
```
##### 4.8 Apply  Specific Coupons.
- Assuming the user has a couple of coupons, he can designate which of them that can be applied on a specific product.

```php
Coupon::appliedCoupons($coupons); // returns a query builder.
```



For more, you can view the [docs](https://github.com/SecTheater/marketplace/wiki).
