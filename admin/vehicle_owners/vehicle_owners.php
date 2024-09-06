<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require '../navbar/navbar.php'; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="../navbar/style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vehicle Owners</title>
    <style>
        
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }

        
        .container {
            margin-left: 450px; 
            padding: 20px;
        }

        
        .search-table-wrapper {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        
        .search-bar {
            width: 100%;
            text-align: center;
            margin-bottom: 10px;
        }

        input[type="text"] {
            width: 50%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }

        
        table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
            margin-top: 10px;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        th, td {
            padding: 12px 20px;
            text-align: left;
        }

        th {
            background-color: #f1f1f1;
            font-weight: bold;
            color: #333;
        }

        td {
            border-bottom: 1px solid #eaeaea;
        }

        .action {
            text-align: center;
        }

        .action-btn {
            background-color: #000;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 50%;
            cursor: pointer;
            display: inline-block;
            text-align: center;
            font-size: 16px;
        }

        .action-btn:hover {
            background-color: #555;
        }

        .table-row {
            border-bottom: 1px solid #eaeaea;
        }

        
        @media (max-width: 768px) {
            .container {
                margin-left: 0; 
                padding: 10px;
            }

            .search-bar input[type="text"] {
                width: 80%; 
                padding: 8px;
                font-size: 14px;
            }

            table {
                width: 100%; 
                font-size: 12px;
                margin-top: 15px;
                overflow-x: auto; 
                display: block; 
            }

            th, td {
                padding: 10px 12px; 
                word-wrap: break-word;
            }

            .action-btn {
                padding: 8px;
                font-size: 14px;
            }
        }

        
        @media (max-width: 480px) {
            .search-bar input[type="text"] {
                width: 90%; 
                padding: 7px;
            }

            table {
                font-size: 10px; 
            }

            th, td {
                padding: 8px 10px; 
            }
        }
    </style>
</head>
<body>


<div class="container">

   
    <div class="search-table-wrapper">
       
        <div class="search-bar">
            <input type="text" id="searchInput" placeholder="Search by name...">
        </div>

        
        <table id="ownerTable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>NAME</th>
                    <th>EMAIL</th>
                    <th>ADDRESS</th>
                    <th>CITY</th>
                    <th>PHONE</th>
                    <th>ACTION</th>
                </tr>
            </thead>
            <tbody>
            <?php
            
            $data = [
                [
                    'id' => '14',
                    'name' => 'Tharusha Hirushan',
                    'email' => 'tharushahirushan@gmail.com',
                    'address' => '1/8A, Siriniwasa Mawatha, Kalutara North.',
                    'city' => 'Kalutara',
                    'phone' => '0770203715'
                ],
               
            ];
            foreach ($data as $row): ?>
                <tr class="table-row">
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo $row['email']; ?></td>
                    <td><?php echo $row['address']; ?></td>
                    <td><?php echo $row['city']; ?></td>
                    <td><?php echo $row['phone']; ?></td>
                    <td class="action"><button class="action-btn">⚙️</button></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    
    const menuToggle = document.getElementById('menu-toggle');
    const sidebar = document.getElementById('sidebar');

    menuToggle.addEventListener('click', () => {
        sidebar.classList.toggle('active');
    });

    
    document.getElementById('searchInput').addEventListener('keyup', function () {
        const input = document.getElementById('searchInput').value.toLowerCase();
        const table = document.getElementById('ownerTable');
        const rows = table.getElementsByTagName('tr');

        for (let i = 1; i < rows.length; i++) {
            const nameCell = rows[i].getElementsByTagName('td')[1];
            const name = nameCell.textContent || nameCell.innerText;

            if (name.toLowerCase().indexOf(input) > -1) {
                rows[i].style.display = '';
            } else {
                rows[i].style.display = 'none';
            }
        }
    });
</script>
</body>
</html>
