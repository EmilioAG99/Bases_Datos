<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>CheckOut</title>
</head>
<body>

<?php
session_start();
error_reporting(E_ALL); //DEBUG
ini_set('display_errors', 1);  //DEBUG
//header a index
$enlace = mysqli_connect("127.0.0.1:3308", "usuarioP", "Pass123!", "ventaboletos");
$identificador =  $_GET['id'];
$identificador = mysqli_real_escape_string($enlace,$identificador); //limpiando variable
$UsuarioActual;
if($identificador==10)
{
  header("Location: index.html");
}

if($identificador==2)
{
  $Usuario = $_GET['usuario'];
  $Usuario = mysqli_real_escape_string($enlace,$Usuario); 
  $Password = $_GET['password'];
  $Password = mysqli_real_escape_string($enlace,$Password); 
  $query7 = "select Email_c from cliente where Email_c ='$Usuario' and Contrasena='$Password';";
  $resultado_query3 = mysqli_query($enlace, $query7);
  $row_asociativo3 = mysqli_fetch_assoc($resultado_query3);
  $Usuario = $row_asociativo3['Email_c'];
  if($Usuario!=NULL)
  {
    $_SESSION["UsuarioS"] = $Usuario;
    $UsuarioActual = $_SESSION["UsuarioS"];
    header("Location: Pagina.html "); 
  }
  else
  {
    header("Location: index.html "); 
  }
  
}
if($identificador==3)
{
  $Usuario = $_GET['usuario'];
  $Usuario = mysqli_real_escape_string($enlace,$Usuario); 
  $Name = $_GET['name'];
  $Name = mysqli_real_escape_string($enlace,$Name); 
  $Genero = $_GET['genero'];
  $Genero = mysqli_real_escape_string($enlace,$Genero); 
  $Edad = $_GET['edad'];
  $Edad = mysqli_real_escape_string($enlace,$Edad); 
  $Password = $_GET['password'];
  $Password = mysqli_real_escape_string($enlace,$Password); 
  $query7 = "select Email_c from cliente where Email_c ='$Usuario';";
  $resultado_query3 = mysqli_query($enlace, $query7);
  $row_asociativo3 = mysqli_fetch_assoc($resultado_query3);
  $Verifica = $row_asociativo3['Email_c'];
  if($Verifica ==NULL)
  {
    $query9="insert into cliente (Email_c , Nombre, Edad, Genero,Contrasena) values('$Usuario','$Name',$Edad,'$Genero','$Password'); ";
    mysqli_query($enlace, $query9);
    $_SESSION["UsuarioS"] = $Usuario;
    $UsuarioActual = $_SESSION["UsuarioS"];
    header("Location: Pagina.html "); 
  }
  else
  {
    header("Location: index.html "); 
  }
  
}
if($identificador==9)
{
  $UsuarioActual= $_SESSION["UsuarioS"];
}

if($identificador==5) // para fechas de conciertos y encontrar disponibilidad 
{
$UsuarioActual= $_SESSION["UsuarioS"];
$Area = $_GET['area'];
$Fecha = $_GET['fecha'];
$Artista = $_GET['artista'];
if($Area == "General")
{
  $Precio = 250;
  $Cantidad = $_GET['cantidad'];
  $query8 = "select Lugares_disponibles_General, idConcierto from concierto where Artista='$Artista' and Fecha='$Fecha';";
  $resultado_query9 = mysqli_query($enlace, $query8);
  $row_asociativo3 = mysqli_fetch_assoc($resultado_query9);
  $lugaresdis = $row_asociativo3['Lugares_disponibles_General'];
  $idConcierto = $row_asociativo3['idConcierto'];
  $auxiliar = $lugaresdis-$Cantidad;
  if($auxiliar>=0)
  {
    for($i=0; $i<$Cantidad;$i++)
    {
      $query1 = "insert into boleto (idBoleto,Evento,Area,Precio) values (null,'$idConcierto','$Area',$Precio);"; //genera boleto
      mysqli_query($enlace, $query1) ;
      $query4 = "select idBoleto from boleto where idBoleto = (SELECT MAX(idBoleto) from boleto where evento = '$idConcierto');"; //obtienes ultimo boleto generado
      $resultado_query2 = mysqli_query($enlace, $query4);
      $row_asociativo1 = mysqli_fetch_assoc($resultado_query2);
      $boletoacomprar = $row_asociativo1['idBoleto'];
      $query2 = "insert into compra_boleto (idBoleto_C,Email) values($boletoacomprar,'$UsuarioActual');";
      mysqli_query($enlace, $query2) ;
      $lugaresdis-=1;
      $query6= "UPDATE `ventaboletos`.`concierto` SET `Lugares_disponibles_General` =  $lugaresdis WHERE (`idConcierto` = '$idConcierto');";
      mysqli_query($enlace, $query6) ;
      }
  }
  else
  {
    echo"ya no hay ese numero disponible";
  }
}
else
{
  $Precio = 500;
  $Cantidad = $_GET['cantidad'];
  $query8 = "select Lugares_VIP, idConcierto from concierto where Artista='$Artista' and Fecha='$Fecha';";
  $resultado_query9 = mysqli_query($enlace, $query8);
  $row_asociativo3 = mysqli_fetch_assoc($resultado_query9);
  $lugaresdis = $row_asociativo3['Lugares_VIP'];
  $idConcierto = $row_asociativo3['idConcierto'];
  $auxiliar = $lugaresdis-$Cantidad;
  if($auxiliar>=0)
  {
    for($i=0; $i<$Cantidad;$i++)
    {
      $query1 = "insert into boleto (idBoleto,Evento,Area,Precio) values (null,'$idConcierto','$Area',$Precio);"; //genera boleto
      mysqli_query($enlace, $query1) ;
      $query4 = "select idBoleto from boleto where idBoleto = (SELECT MAX(idBoleto) from boleto where evento = '$idConcierto');"; //obtienes ultimo boleto generado
      $resultado_query2 = mysqli_query($enlace, $query4);
      $row_asociativo1 = mysqli_fetch_assoc($resultado_query2);
      $boletoacomprar = $row_asociativo1['idBoleto'];
      $query2 = "insert into compra_boleto (idBoleto_C,Email) values($boletoacomprar,'$UsuarioActual');";
      mysqli_query($enlace, $query2) ;
      $lugaresdis-=1;
      $query6= "UPDATE `ventaboletos`.`concierto` SET `Lugares_VIP` =  $lugaresdis WHERE (`idConcierto` = '$idConcierto');";
      mysqli_query($enlace, $query6) ;
      }
  }
  else
  {
    echo"ya no hay ese numero disponible";
  }
}

}

?>
<form name="Regresar" action="Pagina.html">
      <button type="submit">Regresar a la página principal</button>
 </form>  
<label>Gracias por comprar con nosotros. <br> Tus boletos son los siguientes:</label>	
<table border="2">
<br>
<label>&nbsp;&nbsp;Evento</label>
<label>&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Area</label>
<label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; boleto</label>
<label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Fecha</label>

<?php	
if($identificador==5)
{
$query3 = "select idBoleto,Artista,Area,Fecha from boleto inner join compra_boleto on (idBoleto=idBoleto_c) inner join concierto on(idConcierto=Evento) where Email= '$UsuarioActual' and Evento='$idConcierto';";
$resultado_query5 = mysqli_query($enlace, $query3) ;
while($row_asociativo = mysqli_fetch_assoc($resultado_query5))
{
  echo "<tr>";
  echo "<td>".$row_asociativo["Artista"]."</td>";
  echo "<td>".$row_asociativo["Area"]."</td>";
  echo "<td>".$row_asociativo["idBoleto"]."</td>";
  echo "<td>".$row_asociativo["Fecha"]."</td>";
  echo "</tr>";
}  
}
elseif ($identificador ==9) {
  $query10= "select idBoleto,Artista,Area,Fecha from boleto inner join compra_boleto on (idBoleto=idBoleto_c) inner join concierto on(idConcierto=Evento) where Email= '$UsuarioActual';";
  $resultado_query10 = mysqli_query($enlace, $query10);
  while($row_asociativo = mysqli_fetch_assoc($resultado_query10))
{
  echo "<tr>";
  echo "<td>".$row_asociativo["Artista"]."</td>";
  echo "<td>".$row_asociativo["Area"]."</td>";
  echo "<td>".$row_asociativo["idBoleto"]."</td>";
  echo "<td>".$row_asociativo["Fecha"]."</td>";
  echo "</tr>";
} 
}
?>

</table>
</body>
</html>