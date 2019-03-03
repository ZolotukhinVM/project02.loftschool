<?php

namespace App\Models;

use Faker\Factory as fake;

class ImportFaker extends \Core\Model
{
    public static function startImport()
    {
        $dbConnect = static::getDB();
        $result = "";
        if (isset($_GET["truncate"]) && (int)$_GET["truncate"] == 1) {
            $dbConnect->exec("TRUNCATE TABLE `users_tbl`");
            $result = "Table is clear! <a href='/import/index'> Import again?</a>";
        } else {
            $faker = fake::create();
            if ($dbConnect->query("SELECT COUNT(*) FROM `users_tbl`")->fetchColumn() == 0) {
                for ($i = 1; $i <= 10; $i++) {
                    $sql = "INSERT INTO `users_tbl` (`login`, `password`, `name`, `age`, `comment`, `photo`)
                        VALUES (:login, :password, :name, :age, :comment, :photo)";
                    $insertFaker = $dbConnect->prepare($sql);
                    $data = [
                        "login" => $faker->word,
                        "password" => $faker->password,
                        "name" => $faker->name,
                        "age" => $faker->numberBetween(5, 100),
                        "comment" => $faker->text,
                        "photo" => $faker->image("./uploads/", 250, 250, 'cats', false, true, 'Faker')
                    ];
                    $insertFaker->execute($data);
                }
                $result = "Fake data is load! <a href='import/index/?truncate=1'>Truncate table?</a>";
            }
        }
        return $result;
    }
}
