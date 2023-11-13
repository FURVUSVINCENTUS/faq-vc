<?php
/* ========================================================
# File name	 	: admin.php
# Begin		 		: 2023.07.03
# Last Update	: 2023.10.29
#
# Description	: CRUD based on a json DB
#
# (c) Copyright :
#				  Vicente Crestani
#				  FVAJ
# 				www.fvaj.ch
#				  dev@fvaj.ch
======================================================== */

// _Parsedown_

include 'vendor/parsedown/Parsedown.php';

$Parsedown = new Parsedown();

// Identifier les éléments par leurs id

//$doc = new DOMDocument();

// lecture de l'output html du fichier php
//echo(file_get_contents('http://faq-vc.loc/admin.php'));
// $doc->LoadHTML(file_get_contents('http://faq-vc.loc/admin.php'));

//$section = $doc->getElementsByTagName('section');
//foreach ($section->attributes as $attr) {
//	$name = $attr->nodeName;
//	$value = $attr->nodeValue;
//	echo "Attribute '$name' :: '$value'<br/>";
//}

// _variables_

// nom de page par défaut (index)
if(isset($_POST['save']))
{
	$npage = $_POST['npage'];
}
else
{
	$npage = "index";
}
$title = "";		// titre de la page ( head html )
$keyword = ""; 	// mot cle ( head html )
$question = "";
//$content = array();	// liste contentant les modules d'une question
$setunset = 0; // status d'ajout de question 0:neutre 1:ajoute 2:supprime

// # SESSIONS
// if(isset($_SESSION['level'])) {
// 	$_level = $_SESSION['level'];
// 	$_level = $_SESSION;
// }
//  if(isset($_POST['logged'])) {
//  	session_start();
//  	$_SESSION["level"]=$_level;
//  }
// # LEVEL
 $_level = 0;
// if(isset($_POST['lvl_']))
// # utlisateurs
$admin = "admin";
$webm = "webm";
$user = "user";
// # passwords
$lvl_1 = "admin"; //adminjson
$lvl_2 = "webm"; //webmaster
$lvl_3 = "user"; //user
//$currentCss = file_get_contents($directory[0].$style, false);
$directory = array("./css/", "./img/", "../json/");
//print_r(scandir($directory[0]));
$style = "";
$currentCss = isset($_POST['style']) ? file_get_contents("./css/".$_POST['style']) : "\n\n\t\t\t no stylesheet selected yet";
if(isset($_POST['style']))
{
	$style = "" ? "style.css" : $_POST['style'];
	//print_r($directory[0]->)
	//$currentCss = file_get_contents("./css/".$style, false);
	//$currentCss = isset($_POST['style']) ? file_get_contents("./css/".$style) : "";
}
$label = "";		// Type de contenu (json) et affichage switch
$type = 0;		// cas par défaut (switch case - formulaire label à afficher)
$pos = 0;
$preform = "";		// chaîne wiki preformate (textarea)
$test = "";			// test json_decode
$content = array(); // liste des questions et de leurs modules
$class = "";		// Classe de contenu (css)
$path = "";			// chemin vers img
$alt = "";			// nom alternatif img
$size = "";			// taille img
//$img = 0;			// 1 == form import, 0 == neutre
$imgDir = "./img/";
$str_wiki = ""; // contenu wiki
$css = 0;			// 1 == form update css, 0 == neutre
$dir = "./img/";	// chemin du dossier img
$elem = "";			// element du tableau $tab
$list = "";			// boucles foreach
$img_select = "";	// image a effacer
$allwd_rn_up = array(
	'jpg',
	'jpeg',
	'png',
	'webp',
	'gif'
); 					//extentions acceptees pour rename et upload

// Fonction qui produit la page html
function makeHtml($npage, $title, $keyword, $content, $str_wiki, $class, $directory, $style)
{
	$page = fopen($npage.'.html', 'w');		// creation / ouverture
	$txt = '<!DOCTYPE html>
	<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="author" content="Vicente Crestani">
		<meta name="keyword" content="'.$keyword.'">
		<link rel="stylesheet" href="'.$directory[0].$style.'" type="text/css">
		<link rel="stylesheet" href="/css/style.css">
		<link rel="apple-touch-icon" sizes="180x180" href="/img/apple-touch-icon.png">
		<link rel="icon" type="image/png" sizes="32x32" href="/img/favicon.ico">
		<link rel="icon" type="image/png" sizes="16x16" href="/img/favicon.ico">
		<link rel="manifest" href="./img/site.webmanifest">
		<title>'.$title.'</title>
	</head>
	<body class="answ">
	<!--<body class="'.$class.'">-->
		<header>
			<nav class="answ">
				<h1>FAQ <a href="/admin.php" class="rtrn">&#8962;</a></h1>
			</nav>
		</header>
		<hr>
		<section class="main-nav">
		<!--<section class="'.$class.'">-->
		</section>
		<section class="content">';
		foreach ($content as $key => $value) {
			$txt .= '<h1>'.$value->question->txt.'</h1>';
		}
		$txt .= '<hr>
		</section>
		<footer>
			<nav class="footer">
				<a class="ftr" href="https://go.fvaj.ch">GO</a>
				<a class="ftr" href="https://edu.fvaj.ch">EDU</a>
				<a class="ftr" href="https://fvaj.ch">FVAJ</a>
				<a class="ftr" href="mailto:dev@fvaj.ch">BUGS?</a>
			</nav>
		</footer>
	</body>
	</html>';
	fwrite($page, $txt);	// enregistrement de $txt
	fclose($page);
}

//
if(isset($_FILES['file']))
{
	$tmpName = $_FILES['file']['tmp_name'];
	$name = $_FILES['file']['name'];
	$size = $_FILES['file']['size'];
	$error = $_FILES['file']['error'];

	move_uploaded_file($tmpName, $imgDir.$name);
}

	// if(isset($preform)){$content = $Parsedown->text(nl2br($preform));}
	/* $content = $Parsedown->text(nl2br($preform));
	echo $content;
	exit(); */

if(isset($_POST['type']))
{
	$type = $_POST['type'];
/* 	echo $type;
	exit(); */
}

if(isset($_POST['preform']))
{
	$preform = $_POST['preform'];
/* 	echo $preform;
	echo $Parsedown->text(nl2br($preform));
	exit(); */
}

if(isset($_POST['label']))
{
	$label = $_POST['label'];
	if($label == "img")
	{
		$class = $_POST['class'];
		$path = $_POST['img'];
		$alt = $_POST['alt'];
		$size = $_POST['size'];
		echo $class.", ".$path.", ".$alt.", ".$size;
	}
	elseif($label == "wiki")
	{
		$class = $_POST['class'];
		$preform = $_POST['preform'];
		echo $class.", ".$preform;
	}
	else
	{
		echo "Il semble y avoir une erreur.";
	}

}

// changement de page
if(isset($_POST['img']))
{
	$img = $_POST['img'];
/* 	echo $img;
	exit(); */
}

if(isset($_POST['css']))
{
	$css = $_POST['css'];
}

// sauvegarde feuille de style
if(isset($_POST['saveCss']))
{
	// fonction sauvegarde css
	$newCss = $_POST['newcss'];
	// $file = fopen($directory[0].$style, r+);
	file_put_contents($directory[0].$style, $newCss);
}

// Lecture du fichier json
if(file_exists($directory[2].$npage.'.json'))
{
//if(file_exists('json/index.json')){
	//$page = fopen('./json/'.$npage.'.json', 'r');
	$json = file_get_contents($directory[2].$npage.'.json');
	//$test = file_get_contents('./json/index.json');
	//var_dump(json_decode($test));
	$tab = json_decode($json);

//	echo $tab->title; // => avec json_decode($test, false)
//	echo $tab->keyword;
//	echo $tab->lvl_1;
//	echo $tab->lvl_2;
//	echo $tab->lvl_3;
//	var_dump($tab); // => avec json_decode($test, true)
//	exit();

	//var_dump($tab);
	//exit();
	foreach($tab as $key => $value)
	{
		$title = $tab->title;
		$keyword = $tab->keyword;
		$admin = $tab->admin;
		$webm = $tab->webm;
		$user = $tab->user;
		$lvl_1 = $tab->lvl_1;
		$lvl_2 = $tab->lvl_2;
		$lvl_3 = $tab->lvl_3;
		$content = $tab->body;
	}
	//echo("Il y a ".count($content[0]->question->content)." modules dans \"".$content[0]->question->txt."\".\n");
	//$modules = "";
	//for ($i = 0; $i < count($content[0]->question->content) ; $i++) {
	//	$modules .= $content[0]->question->content[$i]->type."\n";
	//}
	//foreach ($content[0]->question->content as $key => $value) {
	//	$modules .= $value->type."\n";
	//}
	//echo("Ces modules sont : ".$modules);
	makeHtml($npage, $title, $keyword, $content, $str_wiki, $class, $directory, $style);

}
//¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨_Create_¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨\\


	/* $add = array();
	$content = json_encode($add, JSON_PRETTY_PRINT); */
if(isset($_POST['question']))
{
	$pos = $_POST['pos'];
	$question = $_POST['question'];
	$content[$pos]->question->txt = $question;
	echo $pos." ".$question;
	//$question = "" ? $content[$pos]->question->txt : $_POST['question'];
}

if(isset($_POST['save']))
{
	$json = file_get_contents($directory[2].$npage.'.json');
	$tab = json_decode($json);

	$npage = isset($_POST['npage']) ? $_POST['npage'] : "index";
	$title = empty($_POST['title']) ? $title : $_POST['title'];
	$keyword = empty($_POST['keyword']) ? $keyword : $_POST['keyword'];
	$admin = isset($_POST['admin']) && empty($_POST['admin']) ? $title : md5($_POST['admin']);
	$webm = isset($_POST['webm']) && empty($_POST['webm']) ? $webm : md5($_POST['webm']);
	$user = isset($_POST['user']) && empty($_POST['user']) ? $user : md5($_POST['user']);
	$lvl_1 = isset($_POST['lvl_1']) && empty($_POST['lvl_1']) ? $title : md5($_POST['lvl_1']);
	$lvl_2 = isset($_POST['lvl_2']) && empty($_POST['lvl_2']) ? $title : md5($_POST['lvl_2']);
	$lvl_3 = isset($_POST['lvl_3']) && empty($_POST['lvl_3']) ? $title : md5($_POST['lvl_3']);
	$content = isset($_POST['content']) ? $_POST['content'] : $content;

	// écriture du fichier json
		//$page = fopen($directory[2].$npage.'.json', 'w');
		// $content = json_encode(array_values($modules));
		//$txt = '{
		//		"title": "'.$title.'",
		//		"keyword": "'.$keyword.'",
		//		"admin": "'.$admin.'",
		//		"webm": "'.$webm.'",
		//		"user": "'.$user.'",
		//		"lvl_1": "'.$lvl_1.'",
		//		"lvl_2": "'.$lvl_2.'",
		//		"lvl_3": "'.$lvl_3.'",
		//}';
		$tab->title = $title;
		$tab->keyword = $keyword;
		$tab->admin = $admin;
		$tab->webm = $webm;
		$tab->user = $user;
		$tab->lvl_1 = $lvl_1;
		$tab->lvl_2 = $lvl_2;
		$tab->lvl_3 = $lvl_3;
		$tab->body = $content;

		$json = json_encode($tab, JSON_PRETTY_PRINT);

		// fwrite($page, $json);	// enregistrement
		file_put_contents($directory[2].$npage.".json", $json);

		//fclose($page);			// fermeture
		if(file_exists($npage.'.html'))
		{
			unlink($npage.'.html');
		}
		makeHtml($npage, $title, $keyword, $content, $str_wiki, $class, $directory[0], $style);
}


/* 	echo $npage.", ".$title.", ".$keyword;
	exit(); */


/* if(isset($_POST['style'])) {
	if(file_exists($_POST['style'])) {
		$file = fopen($directory[0].$style, r+);
		$currentCss = file_get_contents($file);
		$newCss = $currentCss;
	}
} */

//¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨_Read_¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨\\

// _Boucles_foreach

	// faire un match à la place de ces fonctions
	// fonction qui sort les fichiers sous forme de datalist (remove)
function print_remove($dir)
{
	$list = "<label for='img_select'>Images</label>\n";
	$list .= "<select name='r-images' id='img_select' size='4'>\n";
	$tab = array_diff(scandir($dir), array(".", ".."));
	foreach ($tab as $elem)
	{
		$list .= "<option value='".$elem."'>".$elem."</option>\n";
	}
	$list .= "</select>\n";
/* 	$list .= "<input type='submit' value='Delete'>\n"; */
	return $list;
}

	// fonction qui sort les fichiers sous forme de datalist (rename)
function print_rename($dir)
{
	$list = "<label for='img_select'>Images</label>\n";
	$list .= "<select name='rn-images' id='img_select' size='4'>\n";
	$tab = scandir($dir);
	// $tab = array_preg_diff(scandir('somedir'), '/^\./', '/^\.');
	foreach ($tab as $elem)
	{
		$list .= "<option value='".$elem."'>".$elem."</option>\n";
	}
	$list .= "</select>\n";
/* 	$list .= "<input type='submit' value='Delete'>\n"; */
	return $list;
}

	// test pour visionner le dossier sélectionné (par défaut ./img/)

	/* $tab = scandir($dir);
	var_dump($tab);
	exit(); */

	// fonction qui sort les fichiers du dossier sous forme de datalist (change style)

function print_styles($directory)
{
	$list = "<label for='style'>Styles</label>\n";
	$list .= "<select name='style' id='style' size='4'>\n";
	$tab = array_diff(scandir($directory[0]), array(".", ".."));
	foreach ($tab as $key => $value) {
		$list .= "<option value='".$value."'>".$value."</option>\n";
	}
	$list .= "</select>\n";
/* 	$list .= "<input type='submit' value='Delete'>\n"; */
	echo $list;
}

class Question
{

	public function printQuestions($content)
	{
		$qlist = "";
		foreach ($content as $key => $value) {
				$qlist = "\n\t\t\t\t<p>".$value->question->txt."</p>";
				$qlist .= "\n\t\t\t\t<input type='hidden' name='type' value='7'>&#9998;</button>";
				$qlist .= "\n\t\t\t\t<button type='submit' name='pos' value='".$key."'>&#9998;</button>";
				$qlist .= "\n\t\t\t\t<button name='type' type='submit' id='label' value='3'>&plus;</button>";
				$qlist .= "\n\t\t\t\t<button name='type' type='submit' id='label' value='4'>&#128465;</button><br>";
				//$qlist .= "\n\t\t\t\t<button name='type' type='submit' id='label' value='1'>&#43; img</button>";
				//$qlist .= "\n\t\t\t\t<button name='type' type='submit' id='wiki' value='2'>&#43; wiki</button><br>\n";
				echo $qlist;
		}
	}

	public function makeQuestion($content)
	{
		$question = $_POST['question'];
		if(isset($_POST["setunset"])&& $_POST["setunset"]==1)
		{
			if(array_key_exists($_POST["question"], $content))
			{
				echo "<p>La question existe déjà!</p>";
			}
			elseif($_POST["question"]!="")
			{
				echo "<p>Veuillez remplir ce champ!</p>";
			}
			$pos = $_POST['pos'];
			$content[$pos] = $_POST['question'];
		}
	}
	// Fonciton qui affiche la / les elements de réponse à partir de l'objet $content
	public function showContent($content)
	{
		$pos = $_POST['pos'];
		$txt = "";
		$txt .= "<!--Debut du formulaire des modules-->";
		$txt .= "\n<form action='admin.php' method='post' enctype='multipart' class='main-nav'>";
		$txt .= "\n\t<fieldset>\n<legend>Content of ".$content[$pos]->question->txt."</legend>";
		$txt .= "\n\t<button name='type' type='submit' id='label' value='1'>&#43; img</button>";
		$txt .= "\n\t<button name='type' type='submit' id='wiki' value='2'>&#43; wiki</button>";
		$txt .= "\n\t<input type='hidden' name='type' value='0'>";
		$txt .= "\n\t<button type='submit'>Abort</button>";
		foreach ($content[$pos]->question->content as $key => $value)
		{
			//$txt .= "\n\t\t\t<h3>Module ".$key."</h3>";
			$i = 0;
			foreach ($value as $name => $str)
			{
				//$txt .= "\n\t\t\t<li>".$name.": ".$str."</li>";
				$txt .= "\n\t\t";
				$txt .= match ($name)
				{
					"type" => "<h4>".$key." &rarr; ".$str."</h4>",
					"class", "alt", "path", "size"  => '<label for="'.$key.'-'.$name.'-'.$i.'">'.$name.'</label>'."\n\t\t".'<input type="text" name="'.$key.'-'.$name.'-'.$i.'"></br>',
					"preform" => '<label for="'.$key.'-'.$name.'-'.$i.'">'.$name.'</label>'."\n\t\t".'<textarea name="'.$key.'-'.$name.'-'.$i.'"></textarea>',
				};
				$i++;
			}
		}
		$txt .= "\n\t</br></br><input type='submit' name='save-content' value='Save'>";
		$txt .= "\n\t</fieldset>\n</form>\n";
		$txt .= "<!--Fin du formulaire des modules-->\n";
		echo $txt;
	}
}



function deleteQuestion($content)
{
	if(isset($_POST["question"])&& $_POST["setunset"]== 2)
	{
		unset($content[$_POST["question"]]);
		header("admin.php");
	}
}



//¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨_Update_¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨\\

	// renommage de l'image sélectionnée
if(isset($_POST['newname']))
{
	if(isset($_POST['rn-images']))
	{
		$img_select = $_POST['rn-images'];
		if($img_select !== "") // ne prend pas les champs vides
		{
			$newname = $_POST['newname'];
			// $newname = filter_var($newname, FILTER_VALIDATE_REGEXP, array($options =))
			/* 	echo getcwd() . "\n";
			exit(); */
			rename($dir.$img_select, $dir.$newname);
		}
		else
		{
			echo "Champ vide!";
		}
	}
}

//¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨_Delete_¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨\\

	// effacement de l'image sélectionnee
if(isset($_POST['r-images']))
{
	$img_select = $_POST['images'];
/* 	echo $dir.$img_select." est efface";
	exit(); */
	unlink($dir.$img_select);
}


//¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨_Début du programme_¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨\\

// _Affichage des $_POST et des $_FILES._

/* 		var_dump($_POST);
		var_dump($_FILES); */
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="author" content="Vicente Crestani">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate"/>
    <meta http-equiv="Pragma" content="no-cache"/>
    <meta http-equiv="Expires" content="0"/>
	<link rel="stylesheet" href="/css/style.css">
	<link rel="apple-touch-icon" sizes="180x180" href="/favicon_io/apple-touch-icon.png">
	<link rel="icon" type="image/png" sizes="32x32" href="/favicon_io/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="16x16" href="/fvaicon_io/favicon-16x16.png">
	<link rel="manifest" href="/favicon_io/site.webmanifest">
	<title><?php echo "+ ".$title;?></title>
</head>
<body class="faq">

<?php // Switch case d'affichage conditionnel
			switch ($type)
			{
				case 0:
					//echo 'Ajoutez un module !';
					?>
					<form action="admin.php" method="post" enctype="multipart/form-data" class="main-nav">
						<fieldset>
							<legend>Image management</legend>
							<button type="submit" id="img" name="type" value="6">+ / - <img src="#" alt="   "></button>
							<button type="submit" id="img" name="type" value="6">Rename <img src="#" alt="   "></button>
						</fieldset>
					</form>
					<form action="admin.php" method="post" class="main-nav">
						<fieldset>
							<legend>Stylesheet management</legend>
							<button type="submit" id="css" name="type" value="5">Update CSS</button>
						</fieldset>
					</form>
					<!-- Formulaire markdown-->
					<form action="admin.php" name="modifcation" method="post" class="main-nav">
						<fieldset>
							<legend>Header</legend>
							<input type="text" id="npage" name="npage" placeholder="nom de la page" value="<?php echo $npage; ?>"><br>
							<input type="text" id="title" name="title" placeholder="titre de la page" value=" <?php echo $title; ?>"><br>
							<input type="text" id="keyword" name="keyword" placeholder="mots-clef" value=" <?php echo $keyword; ?>">
						</fieldset>
						<fieldset>
							<legend>User ID & Password</legend>
							<input type="text" name="admin" placeholder="ID_admin" title="<?php echo $lvl_1;?>"><br>
							<input type="text" id="lvl_1" name="lvl_1" placeholder="&#8231;&#8231;&#8231;&#8231;&#8231;&#8231;&#8231; admin"><br>
							<input type="txt" name="webm" placeholder="ID_webmaster" title="<?php echo $lvl_2;?>"><br>
							<input type="txt" id="lvl_2" name="lvl_2" placeholder="&#8231;&#8231;&#8231;&#8231;&#8231;&#8231;&#8231; webmaster"><br>
							<input type="txt" name="user" placeholder="ID_user" title="<?php echo $lvl_3;?>"><br>
							<input type="txt" id="lvl_3" name="lvl_3" placeholder="&#8231;&#8231;&#8231;&#8231;&#8231;&#8231;&#8231; user"><br>
						</fieldset>
						<!--Début contenu-->
						<fieldset>
							<legend>Content</legend>
								<?php
								$questions = new Question();
								$questions->printQuestions($content);
								echo "\n\n";
								 ?>
						</fieldset>
						<button type="submit" id="save" name="save">save</button>
						<a href="<?php echo $npage; ?>.html">Visit <?php echo $npage; ?></a>
					</form>
					<!--Fin du contenu-->

					<!--Fin markdown-->
						<?php
					break;
				case 1:
				// ajout d'un objet image
					?>
						<input name="class" type="text" id="class" placeholder="Nom de la classe" value="<?php echo $class;?>">
						<input name="path"type="text"  placeholder="path/url" value="<?php echo $path;?>">
						<input name="alt"type="text"  placeholder="alt text" value="<?php echo $alt;?>"><br>
						<label for="size">Size (between 10 and 100%)</label>
						<input name="size" type="range" id="size" min="10" max="100" step="5">
					<?php
					break;
				case 2:
				// ajout d'une question
					?>
						<input name="class" type="text" id="class" placeholder="Nom de la classe" value="<?php echo $class;?>"><br><br>
						<textarea name="preform" id="preform" autocomplete="on" cols="69" rows="10" value="<?php echo $preform;?>"><?php echo $preform;?></textarea><br>
					<?php
					break;
				case 3:
					// ajout d'une question si elle n'existe pas actuellement
					$questions = new Question();
					$questions->makeQuestion($content);
					?>
					<input type="text" id="question" name="question" placeholder="_Nouvelle question_" value="">
					<button name="setunset" type="submit" value="1">confirm</button>
					<button name="type" type="submit" id="label" value="0">abort</button><br>
					<?php
					break;
				case 4:
				// suppression d'une question
					// echo "";
					// echo "<button name="type" type="submit" id="label" value="0">abort</button>';
					deleteQuestion($content);
				break;
				case 5:
				// mise à jour de la fiche de style ?>
						<!--Début Formulaire stylesheet-->
						<form action="admin.php" method="post" enctype="multipart/form-data" class="main-nav">
							<fieldset>
								<legend>Select style</legend>
							 	<?php print_styles($directory); ?>

								<input type="hidden" value="5" name="type">
								<button type="submit" id="style" value="'.$style.'">Select stylesheet</button>
							</fieldset>
						</form>
						<form action="admin.php" method="post" enctype="multipart/form-data" class="main-nav">
							<fieldset>
								<legend>Update CSS</legend>
								<textarea name="newcss" id="newcss" cols="69" rows="10" value="'.$currentCss.'"><?php echo $currentCss; ?></textarea>
							</fieldset>
								<button type="submit" id="save" name="saveCss">Save</button>
							</form>
							<form class="main-nav" action="admin.php" method="post">
								<button type="submit" id="" name="css" value="0">Cancel</button>
							</form>
							<!--Fin formulaire stylesheet-->
					<?php
				break;
				case 6:
					// Téléverser une image && Changer la feuille de style
						?>
						<!-- Formulaire image-->
						<form action="admin.php" method="post" class="main-nav" enctype="multipart/form-data">
							<fieldset>
								<legend>Import image</legend>
								<input type="file" id="file" name="file" accept="image/png, image/jpeg, image/webp">
								<button type="submit">Import &#128444;</button>
							</fieldset>
						</form>
						<form action="admin.php" method="post" enctype="multipart/form-data" class="main-nav">
							<fieldset>
								<legend>Change dir</legend>
								<?php echo print_rename($dir); ?><br>
								<label for="rename">Directory</label>
								<input type="text" name="newname">
								<button type='submit' id="rename">Select &#128448;</button>
							</fieldset>
						</form>
						<form action="admin.php" method="post" class="main-nav">
							<fieldset>
								<legend>Remove image</legend>
								<?php echo print_remove($dir); ?>
								<button type='submit'>Delete &#128444;</button>
							</fieldset>
						</form>
						<form action="admin.php" method="post" enctype="multipart/form-data" class="main-nav">
							<fieldset>
								<legend>Rename image</legend>
								<?php echo print_rename($dir); ?><br>
								<label for="rename">Rename</label>
								<input type="text" name="newname">
								<button type='submit' id="rename">Rename &#128444;</button>
							</fieldset>
						</form>

						<form class="main-nav" action="admin.php" method="post">
							<button type="submit" id="img" name="type" value="0">Cancel</button>
						</form>
						<!--Fin du formulaire image-->
						<?php
						break;
					case 7:
						// Affiche le contenu de la question selectionnee
							$questions = new Question();
							$questions->showContent($content);
						break;
			}
		?>
		<?php
		// Sanity checks
		//print_r($content[0]);
		//echo ">>> >>>";
		//print_r($content[0]->question);
		//echo ">>> >>>";
		//print_r($content[0]->question->txt);
		 ?>
</body>
</html>
