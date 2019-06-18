<!DOCTYPE html>
<!--PARTIE 1-->
<html>
<head><link rel="stylesheet" type="text/css" href="mystyle.css"></head>
<body>
<form action="video.php" method="post" enctype="multipart/form-data">
<label for="file">Cliquer sur "Parcourir" pour sélectionner votre fichier:</label>
<br />
<input type="file" name="photo" id="photo" size="20" />
<br />
<input type="submit" name="submit" value="Télécharger" />
<script src="clipboard.min.js"></script>
<?php
session_start();
// Vérifier si le formulaire a été soumis
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Vérifie si le fichier a été uploadé sans erreur.
    if(isset($_FILES["photo"]) && $_FILES["photo"]["error"] == 0){
        $allowed = array("jpg" => "image/jpg", "jpeg" => "image/jpeg", "gif" => "image/gif", "png" => "image/png" , "mp4" => "video/mp4");
        $filename = $_FILES["photo"]["name"];//nom du fichier
        $filetype = $_FILES["photo"]["type"];//type du fichier
        $filesize = $_FILES["photo"]["size"];//taille du fichier
        // Vérifie l'extension du fichier
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        if(!array_key_exists($ext, $allowed)) die("Erreur : Veuillez sélectionner un format de fichier valide.");
		
		//taille max 200 MO
		$maxsize = 200 * 1024 * 1024;
        if($filesize > $maxsize) die("Error: La taille du fichier est supérieure à la limite autorisée.");

        // Vérifie le type MIME du fichier
        if(in_array($filetype, $allowed)){
            // Vérifie si le fichier existe avant de le télécharger.
            if(file_exists("uploads/" . $_FILES["photo"]["name"])){
                echo $_FILES["photo"]["name"] . " existe déjà.";
            } else{
                move_uploaded_file($_FILES["photo"]["tmp_name"], "uploads/" . $_FILES["photo"]["name"]);
                echo "Votre fichier a été téléchargé avec succès.";
            } 
        } else{
            echo "Error: Il y a eu un problème de téléchargement de votre fichier. Veuillez réessayer."; 
        }
    } else{
        echo "";
    }
}
echo "<br />";
echo "<br />";
echo "<br />";
//PARTIE 2
$dir = "uploads/";//variable dossier
$a = scandir($dir);// variable arraylist (dossier)
$max = count($a);// variable qui retourne la valeur max de ton tableau
$chemin = "http://192.168.1.20/up/uploads/";//chemin de l'url
echo "<table>";
echo "<thead>
                <tr>
                    <th>Nom du fichier</th>
                    <th>Lien du fichier</th>
                    <th></th>
                    <th>Cliquer sur l'icône pour copier le lien</th>
                </tr>
      </thead>";
for($i =2;$i < $max;$i++)//création du tableau
{
	
	echo "<tr>";
	$y = $i - 1;//variable qui permet de numéroter les noms des fichiers
	$_SESSION['supp']=$a;
	$n = $i;
	echo "<td>".$y."-".$a[$i]."</td>";
	echo "<td><input value= $chemin$a[$i] id='myInput$i'></td>";
	echo "<td><a href='video.php?id=$i' > Supprimer </a></td>";//Bouton Supprimer
	echo "<td><button class='btn' data-clipboard-target='#myInput$i'>
	<img class=bonjour src='clippy.svg' alt='Copy to clipboard' >
	</button></td>";//Bouton Copier
	echo "<td><video width='320' height='240' controls>
	<source src='$chemin$a[$i]' type='video/mp4'>
	</video> </td> ";
  }
  
echo "</tr>";
echo "</table>";
echo "</form>";
//PARTIE 3
if (isset($_GET['id']))/*si on clique sur le bouton Supprimer*/{
	Supprimer();}
function Supprimer(){
				$filesupp = $_SESSION['supp'];
				$f = $_GET['id'];
				if(file_exists("uploads/" . $filesupp[$f]))/*Si le fichier en question existe*/{
                unlink ("./uploads/". $filesupp[$f]);
                header("Location: ".$_SERVER['PHP_SELF']);
                if(!file_exists("uploads/" . $filesupp[$f]))/*Si le fichier supprimer existe plus*/{
                }
                
            }else{
                echo "Aucun Fichier";
            } 
}
?> 
<script>
    var clipboard = new ClipboardJS('.btn');
    clipboard.on('success', function(e) {
        console.log(e);
    });
    clipboard.on('error', function(e) {
        console.log(e);
    });
</script>
</body>
</html>
