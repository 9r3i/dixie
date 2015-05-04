Ldb2 API (Application Programming Interface)
Version 2.5

-------------------------------------------------
i. introduction

Ldb2 is the custom portable database, this is the
2nd generation of Ldb, Ldb2 is faster than first
generation.

As version 2.5, Ldb2 is saver than before, added
new function called swrite for save write, this
is the sampler to next generation for creating
encription database inside Ldb2, while Ldb3 had
encripted but slow access, because of protection
and total security mode.

I wish Ldb2 can give you more contribute, because
this databse framework is open source (free).

-------------------------------------------------
1. connect into database

$my_db = 'my_database'; // database name
$ldb = new Ldb($my_db);

output: Ldb Object

-------------------------------------------------
2. show the last error

$ldb->error;

output: string of the last error

-------------------------------------------------
3. show the version of Ldb

$ldb->version;

output: string of the version

-------------------------------------------------
4. show current database tables

$ldb->show_tables();

output: array of tables

-------------------------------------------------
5. create a new table

$table = 'my_table'; // regExp [a-zA-Z0-9_]+
$ldb->create_table($table);

output: boolean -> true if success and false if
failed

-------------------------------------------------
6. delete a table

$table = 'my_table';
$ldb->delete_table($table);

output: boolean -> true if success and false if
failed

-------------------------------------------------
7. show database

$ldb->show_database();

output: array of database

-------------------------------------------------
8. insert data into a table

$table = 'my_table';
$data = array(
  'title'=>'Contoh Judul',
  'content'=>'Ini hanya uji coba untuk testing',
  'author'=>'Luthfie',
);
$ldb->insert($table,$data);

output: string of new cid of the latest data

-------------------------------------------------
9. update data table

$table = 'my_table';
$data = array(
  'content'=>'Isi content telah dirubah',
);
$location = 'author=Luthfie';
$ldb->update($table,$location,$data);

output: boolean -> true if success and false if
failed

-------------------------------------------------
10. delete data table

$table = 'my_table';
$location = 'title=Contoh Judul';
$ldb->delete($table,$location);

output: boolean -> true if success and false if
failed

-------------------------------------------------
11. select data table

$table = 'my_table';
$location = 'author=Luthfie&title=Contoh Judul';
$ldb->select($table,$location);

output: array of data table
it'd always be array rather success or not

-------------------------------------------------
12. search data table

$table = 'my_table';
$key = 'content=telah'; // regExp [a-zA-Z0-9\s]+
$ldb->search($table,$key,true);

output: array of result

-------------------------------------------------
13. check password

$table = 'my_users';
$location = 'username=luthfie';
$password = 'mypassword';
$ldb->valid_password($table,$location,$password);

output: boolean -> true if success and false if
failed

-------------------------------------------------
14. hash
*now this function is public as version 2.5

$string = 'my_password';
$ldb->hash($string);

output: string of hashed input

-------------------------------------------------
15. strip magic

$string = ''; // or array
$ldb->strip_magic($string);

output: string or array as input

-------------------------------------------------
16. spend time
*as version 2.5

$ldb->spend_time();

output: second digits of processed time (float 4)

-------------------------------------------------
17 testing time and speed
*as version 2.5

SELECT
0.0550 sec - 1667 lines
0.0500 sec - 3334 lines
0.4300 sec - 3334 lines (x10)

INSERT
   1 = 0.0200 sec
  10 = 0.1190 sec
 100 = 2.7542 sec - 912 lines
 100 = 3.1992 sec - 1012 lines

UPDATE
  1 = 0.0580 sec - 1667 lines
  1 = 0.0980 sec - 3334 lines
 10 = 0.3560 sec - 1667 lines
 10 = 0.7300 sec - 3334 lines
100 = 3.3832 sec - 1667 lines
100 = 7.9295 sec - 3334 lines

-------------------------------------------------
Created by Luthfie (c) 2014-2015
-------------------------------------------------