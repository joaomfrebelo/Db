# Rebelo\Db

Rebelo\Db is a library that extends zendframework/zend-db, 
this library is a shorthand of useful methods in Zendframework/Db,
works with all databases that Zendframework connects.
The Adapter, Select, Insert, Update and Delete class are extention of
the class with the same name in Zend\Db

## Example
Get all rows of a table in an array, with the fetch method
```php
    $config = [
            'driver'   => 'Pdo_Mysql',
            'username' => 'root',
            'password' => 'password',
            'charset'  => 'utf8',
            'hostname' => 'localhost',
            'database' => 'sakila'];
$adapter = new \Rebelo\Db\Adapter\Adapter($config);
$result  = $adapter->select()->from("actor")->fetch();
```

Get only the first row of a query with method fetchRow 
```php
$result  = $adapter->select("actor")->fetchRow();
```

Get only the first column of the first row with method fetchOne
```php
$result  = $adapter->select("actor")
            ->columns([
                new \Zend\Db\Sql\Expression("COUNT(*)")])
            ->fetchOne();
```
Get rows with limit
```php
$result  = $adapter->select("actor")
            ->limit(5)->offset(1)
            ->fetch();
```

Insert a row
```php
$adapter->insert("language")->values([
            "language_id" => "9",
            "name"        => "PT"
        ])->execute();
```
Update a row
```php
$adapter->update("language")->set([
                "name" => "Portuguese"])
            ->where([
                "language_id" => 9])
            ->execute();
```
Delete a row
```php
$adapter->delete("language")->where([
                "language_id" => 9])
            ->execute();
```
Transactions
```php
$adapter->beginTransaction();
$result = $adapter->select("film")->fetchOne();
$result2 = $adapter->select()->from("city")
            ->where([
                "city_id" => 288])
            ->fetchRow();
$adapter->commit();
```

## Install

Via Composer

```bash
$ composer require joaomfrebelo/Db
```


## License
MIT License
Copyright (c) 2019 João M F Rebelo

 Permission is hereby granted, free of charge, to any person obtaining a copy
 of this software and associated documentation files (the "Software"), to deal
 in the Software without restriction, including without limitation the rights
 to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 copies of the Software, and to permit persons to whom the Software is
 furnished to do so, subject to the following conditions:

 The above copyright notice and this permission notice shall be included in
 all copies or substantial portions of the Software.

 THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 THE SOFTWARE.