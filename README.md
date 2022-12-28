# Kenpai Test - IG API



## Spesifikasi

- PHP (v 8.1.3)
- MariaDB (v 10.10.2)
- Framework Laravel (v 9.19)


## Instalasi

1. Jalankan **git clone https://github.com/ekosijabat/ig-kenpai.git** dari terminal.
2. Masuk ke folder **ig-kenpai**.
3. Jalankan **composer-install**
4. Langkah selanjutnya, setting parameter **.env** sebagai berikut:
   * DB_CONNECTION=mysql
   * DB_HOST=localhost
   * DB_PORT=3306
   * DB_DATABASE=kenpai
   * DB_USERNAME=root
   * DB_PASSWORD=oncom
5. Setelah konfigurasi environment selesai, jalankan **php artisan migrate**
6. Proses migrasi tabel database telah selesai. Selanjutnya jalankan **php artisan serve** dan API siap digunakan


## API Dokumentasi
Untuk mengakses API, buka Postman dan import file json postman yang ada **[disini](https://raw.githubusercontent.com/ekosijabat/ig-kenpai/main/docs/IG%20API.postman_collection.json)**.
Dokumentasi postman juga tersedia **[disini](https://documenter.getpostman.com/view/7593695/2s8Z6x3Z6u#8a95fa2f-663a-4fee-8693-5b2af3878fbf)**


## ERD
![Optional Text](../main/docs/ERD.png)
Dokumen ERD dibuat menggunakan draw.io. Database design dapat didownload **[disini](https://raw.githubusercontent.com/ekosijabat/ig-kenpai/main/docs/ERD.drawio)**


## Pengerjaan
1. Pengerjaan menggunakan Laravel Passport
2. Controller menggunakan extend dari BaseController
3. Seluruh custom message dihandle pada folder lang/en/messages.php dan dipanggil menggunakan fungsi trans
4. Image yang dikirim ke API menggunakan base64_encode dan diproses menggunakan base64_decode di API


## Response Code
| Name | Description                   | Example                 |
|------|-------------------------------| ------------------------|
| 200  | Success | <pre>{<br>  "code": 200,<br>  "success": true,<br>  "message": "Thank you. We receive and process your orders."<br>}</pre> |
| 401  | Time exceeded | <pre>{<br>  "code": 401,<br>  "success": false,<br>  "message": "Time exceeded. Please re-login."<br>}</pre> |
| 409  | Failed to execute the process | <pre>{<br>  "code": 409,<br>  "success": false,<br>  "message": "There is connection problem. Please try again later.",<br>  "error": "SQLSTATE[42S22]: Column not found: 1054 Unknown column 'test' in 'where clause' (SQL: select * from `restaurant` where `test` = abc limit 1)"<br>}</pre> |
| 422  | Validation | <pre>{<br>  "code": 422,<br>  "success": false,<br>  "message": "You can't check the data because you are not customer"<br>}</pre> |



Thank you.
