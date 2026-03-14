<?php
try {
    $pdo = new PDO('mysql:host=localhost;dbname=ecommerce', 'root', '');
    $result = $pdo->query('DESC orders');
    $columns = [];
    foreach($result as $row) {
        $columns[] = $row['Field'];
    }
    
    if(in_array('delivery_type', $columns)) {
        echo "✓ Columna 'delivery_type' EXISTE en la tabla orders\n";
        // Mostrar todas las columnas
        echo "\nColumnas en tabla orders:\n";
        foreach($columns as $col) {
            echo "  - " . $col . "\n";
        }
    } else {
        echo "✗ Columna 'delivery_type' NO EXISTE\n";
        echo "\nColumnas actuales:\n";
        foreach($columns as $col) {
            echo "  - " . $col . "\n";
        }
    }
} catch(Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
