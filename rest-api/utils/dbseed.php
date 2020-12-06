<?php

require 'bootstrap.php';

$statement = <<<EOS
    CREATE TABLE IF NOT EXISTS available_dogs (
        id INT NOT NULL AUTO_INCREMENT,
        name VARCHAR(100) NOT NULL,
        age INT DEFAULT 0,
        description VARCHAR(100) NOT NULL,
        weight INT DEFAULT 0,
        PRIMARY KEY (id)
    ) ENGINE=INNODB;
    
    INSERT INTO available_dogs
        (name, age, description, weight)
    VALUES
        ('Mora', 1, 'Dulce y cariñosa', 10),
        ('Simona', 2, 'Alegre y enérgica', 12),
        ('Thor', 3, 'Tímido y adorable', 8),
        ('Mecha', 3, 'Tímido y adorable', 10),
        ('Paco', 2, 'Tímido y adorable', 10),
        ('Mohana', 1, 'Tímido y adorable', 12),
        ('King', 3, 'Tímido y adorable', 7);
EOS;

try {
    $createTable = $dbConnection->exec($statement);
    echo "Success!\n";
} catch (\PDOException $e) {
    exit($e->getMessage());
}
