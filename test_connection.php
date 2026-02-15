<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Connection Test</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            padding: 40px;
            max-width: 600px;
            width: 100%;
        }
        h1 {
            color: #333;
            margin-bottom: 30px;
            text-align: center;
            font-size: 28px;
        }
        .test-section {
            margin: 20px 0;
            padding: 20px;
            border-radius: 8px;
            border-left: 4px solid #ddd;
        }
        .success {
            background-color: #d4edda;
            border-color: #28a745;
            color: #155724;
        }
        .error {
            background-color: #f8d7da;
            border-color: #dc3545;
            color: #721c24;
        }
        .info {
            background-color: #d1ecf1;
            border-color: #17a2b8;
            color: #0c5460;
        }
        .test-title {
            font-weight: bold;
            font-size: 18px;
            margin-bottom: 10px;
        }
        .test-detail {
            margin: 5px 0;
            padding-left: 10px;
        }
        .icon {
            font-size: 24px;
            margin-right: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f8f9fa;
            font-weight: 600;
        }
        .btn-container {
            text-align: center;
            margin-top: 30px;
        }
        .btn {
            display: inline-block;
            padding: 12px 30px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            border-radius: 25px;
            font-weight: 600;
            transition: transform 0.2s;
        }
        .btn:hover {
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔍 Database Connection Test</h1>
        
        <?php
        require_once './config.php';
        
        // Test 1: PHP Configuration
        echo '<div class="test-section info">';
        echo '<div class="test-title">✅ PHP Configuration</div>';
        echo '<div class="test-detail">PHP Version: ' . phpversion() . '</div>';
        echo '<div class="test-detail">MySQL Extension: ' . (extension_loaded('mysqli') ? 'Loaded ✅' : 'Not Loaded ❌') . '</div>';
        echo '</div>';
        
        // Test 2: Database Configuration
        echo '<div class="test-section info">';
        echo '<div class="test-title">📋 Database Configuration</div>';
        echo '<div class="test-detail">Host: ' . DB_HOST . '</div>';
        echo '<div class="test-detail">Username: ' . DB_USER . '</div>';
        echo '<div class="test-detail">Database: ' . DB_NAME . '</div>';
        echo '<div class="test-detail">Password: ' . (DB_PASS === '' ? 'Empty (Default)' : 'Set') . '</div>';
        echo '</div>';
        
        // Test 3: Connection Test
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASS);
        
        if ($conn->connect_error) {
            echo '<div class="test-section error">';
            echo '<div class="test-title">❌ MySQL Connection Failed</div>';
            echo '<div class="test-detail">Error: ' . $conn->connect_error . '</div>';
            echo '<div class="test-detail">Error Code: ' . $conn->connect_errno . '</div>';
            echo '<div class="test-detail"><strong>Solution:</strong> Make sure MySQL is running in XAMPP Control Panel</div>';
            echo '</div>';
        } else {
            echo '<div class="test-section success">';
            echo '<div class="test-title">✅ MySQL Connection Successful</div>';
            echo '<div class="test-detail">Server Version: ' . $conn->server_info . '</div>';
            echo '</div>';
            
            // Test 4: Database Exists
            $db_check = $conn->select_db(DB_NAME);
            
            if (!$db_check) {
                echo '<div class="test-section error">';
                echo '<div class="test-title">❌ Database "' . DB_NAME . '" Does Not Exist</div>';
                echo '<div class="test-detail"><strong>Solution:</strong> Run setup.php or import database.sql</div>';
                echo '<div class="test-detail">Option 1: Visit <a href="setup.php">setup.php</a> to auto-create database</div>';
                echo '<div class="test-detail">Option 2: Import database.sql via phpMyAdmin</div>';
                echo '</div>';
            } else {
                echo '<div class="test-section success">';
                echo '<div class="test-title">✅ Database "' . DB_NAME . '" Found</div>';
                
                // Test 5: Check Tables
                $tables_query = "SHOW TABLES";
                $result = $conn->query($tables_query);
                
                if ($result && $result->num_rows > 0) {
                    echo '<div class="test-detail">Tables found: ' . $result->num_rows . '</div>';
                    echo '<table>';
                    echo '<tr><th>Table Name</th><th>Rows</th></tr>';
                    
                    while ($row = $result->fetch_array()) {
                        $table_name = $row[0];
                        $count_query = "SELECT COUNT(*) as count FROM `$table_name`";
                        $count_result = $conn->query($count_query);
                        $count_row = $count_result->fetch_assoc();
                        echo '<tr><td>' . $table_name . '</td><td>' . $count_row['count'] . '</td></tr>';
                    }
                    
                    echo '</table>';
                } else {
                    echo '<div class="test-detail">⚠️ No tables found in database</div>';
                    echo '<div class="test-detail"><strong>Solution:</strong> Run setup.php or import database.sql</div>';
                }
                
                echo '</div>';
                
                // Test 6: Sample Data Check
                $ngo_query = "SELECT COUNT(*) as count FROM ngos";
                $ngo_result = $conn->query($ngo_query);
                
                if ($ngo_result) {
                    $ngo_count = $ngo_result->fetch_assoc()['count'];
                    
                    if ($ngo_count > 0) {
                        echo '<div class="test-section success">';
                        echo '<div class="test-title">✅ Sample Data Loaded</div>';
                        echo '<div class="test-detail">NGOs: ' . $ngo_count . '</div>';
                        
                        $products_result = $conn->query("SELECT COUNT(*) as count FROM products");
                        $products_count = $products_result->fetch_assoc()['count'];
                        echo '<div class="test-detail">Products: ' . $products_count . '</div>';
                        
                        $users_result = $conn->query("SELECT COUNT(*) as count FROM users");
                        $users_count = $users_result->fetch_assoc()['count'];
                        echo '<div class="test-detail">Users: ' . $users_count . '</div>';
                        
                        echo '</div>';
                    } else {
                        echo '<div class="test-section info">';
                        echo '<div class="test-title">ℹ️ No Sample Data</div>';
                        echo '<div class="test-detail">Tables exist but no data. Run setup.php to add sample data.</div>';
                        echo '</div>';
                    }
                }
            }
            
            $conn->close();
        }
        ?>
        
        <div class="test-section info">
            <div class="test-title">🚀 Next Steps</div>
            <div class="test-detail">1. If database doesn't exist: Run <a href="setup.php">setup.php</a></div>
            <div class="test-detail">2. If all tests pass: Visit <a href="./index.php">index.php</a></div>
            <div class="test-detail">3. Admin login: admin@ngo.com / admin123</div>
        </div>
        
        <div class="btn-container">
            <a href="./index.php" class="btn">Go to Home Page</a>
            <a href="setup.php" class="btn" style="margin-left: 10px;">Run Setup</a>
        </div>
    </div>
</body>
</html>

