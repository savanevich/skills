<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class GenerateTestData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $sql = <<<SQL
        DROP PROCEDURE IF EXISTS GenerateData;
        CREATE PROCEDURE GenerateData()
        BEGIN
            DECLARE i int DEFAULT 0;   
            
            SET @frontEndID = NULL;
            SELECT id INTO @frontEndID FROM categories WHERE LOWER(name)="frontend";
            IF @frontEndID IS NULL THEN
                INSERT INTO categories SET name="Frontend";
                SET @frontEndID = LAST_INSERT_ID();
            END IF;
            
            SET @angularID = NULL;
            SELECT id INTO @angularID FROM technologies WHERE LOWER(name)='angular';
            IF @angularID IS NULL THEN
                INSERT INTO technologies SET name="Angular", priority=7, category_id=@frontEndID;
                SET @angularID = LAST_INSERT_ID();
            END IF;
            
            SET @backboneID = NULL;
            SELECT id INTO @backboneID FROM technologies WHERE LOWER(name)='backbone';
            IF @backboneID IS NULL THEN
                INSERT INTO technologies SET name="Backbone", priority=7, category_id=@frontEndID;
                 SET @backboneID = LAST_INSERT_ID();
            END IF;
            
            WHILE i < 5000 DO
                SET @username = CONCAT('test',i);       
                IF NOT exists (SELECT id FROM users WHERE username = @username COLLATE utf8_unicode_ci) THEN
                    INSERT INTO users 
                    SET username=@username, 
                        email=CONCAT(@username,'gmail.com')
                    SET @userID = LAST_INSERT_ID();
                    SELECT FLOOR(RAND() * 10) + 1 INTO @level;          
                    INSERT INTO user_technology SET user_id = @userID, level = @level, technology_id=@backboneID;
                    SELECT FLOOR(RAND() * 10) + 1 INTO @level;          
                    INSERT INTO user_technology SET user_id = @userID, level = @level, technology_id=@angularID;
                END IF;
                
                SET i = i + 1;
            END WHILE;        
        END
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       $sql = "DROP PROCEDURE IF EXISTS GenerateData";
       DB::connection()->getPdo()->exec($sql);
    }
}
