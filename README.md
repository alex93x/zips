#Start project  

```
$ composer update
$ php artisan migrate
$ php artisan db:seed
```

#API
Get data by zip:  
GET: host/api/zips/by-zip?zip=00616  

Find Data by city name:  
GET: host/api/zips/by-city?city=Baja

Update data and insert new rows:  
POST: host/api/zips  
form-data: csv
