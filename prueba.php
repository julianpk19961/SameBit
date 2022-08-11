<html>
    <head>
        <meta charset="UTF-8">
    </head>
    <body>

    <?php
    // #Conexión
    include('./config/config.php');

    //Sql to get data from diagnosis
    $Sql = 'SELECT codigo,observation  FROM diagnosis LIMIT 50';
    $Result = $conn->query($Sql);
    echo '<label for="Diagnosis"> Seleccione un diganostico';
    echo '<select name=diagnossis >Descripción</option>';
    while ($row = $Result->fetch_assoc()){
        echo "<option value=\"".$row['codigo']."\">".$row['codigo']." ".$row['observation']."</option>"; 
    }
    echo "</select>";
    $conn->close();

?>    </body>
</html>


