<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210517095430 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(
            'INSERT INTO user (id, email, roles, password, api_token) VALUES (1, "kblropb@gmail.com", "{}", 1, "api_token")'
        );
        $this->addSql('INSERT INTO todo (id, user_id, name) VALUES (1, 1, "My first todo list")');
        $this->addSql(
            'INSERT INTO task (id, todo_id, name, is_done) 
                    VALUES 
                           (1, 1, "My first task", 0),
                           (2, 1, "My second task", 1),
                           (3, 1, "The third task", 0),
                           (4, 1, "Fourth", 0)
                    
                 '
        );
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DELETE FROM user WHERE 1=1');
    }
}
