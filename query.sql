-- Step 1: Select the database
USE kedatangan;

-- Step 2: Retrieve the list of all tables
SET @tables = NULL;
SELECT GROUP_CONCAT(table_name) INTO @tables
FROM information_schema.tables
WHERE table_schema = 'kedatangan';

-- Step 3: Prepare and execute a statement to get the CREATE TABLE statements
SET @tables = CONCAT('SHOW CREATE TABLE ', REPLACE(@tables, ',', '; SHOW CREATE TABLE '));
PREPARE stmt FROM @tables;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;
