<?php
/**
 * Database Migration Runner
 * Applies pending migrations to the database
 */

declare(strict_types=1);

require_once __DIR__ . '/vendor/autoload.php';

use App\Core\Bootstrap;
use App\Core\Database;

try {
    // Load environment variables
    Bootstrap::loadEnvironment(__DIR__);
    
    // Get database configuration
    $dbConfig = require __DIR__ . '/config/database.php';
    
    // Get database connection
    $pdo = Database::getConnection($dbConfig);
    
    // Check if symposium_code column exists
    $result = $pdo->query("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME='symposiums' AND TABLE_SCHEMA='nexus_ems_db' AND COLUMN_NAME='symposium_code'");
    
    if (!$result || $result->rowCount() === 0) {
        echo "⚙️ Starting Symposium schema migration...\n\n";
        
        // Read and execute the migration file
        $migrationSql = file_get_contents(__DIR__ . '/database/schema/10_add_symposium_fields.sql');
        
        // Split by semicolon and execute each statement
        $statements = array_filter(array_map('trim', preg_split('/;(?=([^\'"`]*[\'"`][^\'"`]*[\'"`])*[^\'"`]*$)/', $migrationSql)));
        
        foreach ($statements as $statement) {
            if (trim($statement) && !preg_match('/^--/', trim($statement))) {
                echo "Executing: " . substr($statement, 0, 80) . "...\n";
                try {
                    $pdo->exec($statement);
                    echo "✓ Success\n\n";
                } catch (\PDOException $e) {
                    echo "⚠️ Warning: " . $e->getMessage() . "\n\n";
                }
            }
        }
        
        echo "✅ Database migration completed successfully!\n";
    } else {
        echo "✅ Symposium schema already migrated. All required fields present.\n";
    }
    
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}
