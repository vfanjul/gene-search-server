<?php
/* Error page */

function print_error($message, $genome, $gene)
{
	echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
	echo '<html xmlns="http://www.w3.org/1999/xhtml">';
	echo '<head>';
	echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
	echo '<meta name="author" content="Victor Fanjul Hevia" />';
	echo '<meta name="keywords" content="Universitat Oberta de Catalunya, Universitat de Barcelona, herramientas para la informatica, bioinformatics, master, genomics," />';
	echo '<meta name="description" content="Web degined by Victor Fanjul for Herramientas para la Informatica, Master de Bioinformatica y Bioestadistica, UOC & UB" />';
	echo '<meta name="robots" content="all" />';
	echo '<title>Know your Genes</title>';
	echo '<link rel="stylesheet" type="text/css" href="style.css" />';
	echo '</head>';
	echo '';
	echo '<body>';
	echo '<div id="header">';
	echo '<div id="topmenu">';
	echo '<div id="title">';
	echo '</div>';
	echo '<div id="mainmenu">';
	echo '<a href="index.html" id="index" title="Know your Genes"><span>Know your Genes</span></a>';
	echo '</div>';
	echo '<div id="leftmenu">';
	echo '<a href="https://www.uoc.edu" id="uoc" title="UOC" target="_blank"></a>';
	echo '<a href="https://www.ub.edu"  id="ub" title="UB" target="_blank"></a>';
	echo '</div>';
	echo '</div>';
	echo '</div>';
	echo '<div id="container">';
	echo '<div id="content">';
	echo '<h4>Your query</h4>';
	echo '<form action="server.php" method="post" enctype="multipart/form-data">';
	echo '<select name="genome" size="1">';
	if ($genome=="human")
	{
	echo '<option value="human" selected>Human (Homo sapiens)</option>';
	echo '<option value="mouse">Mouse (Mus musculus)</option>';
	echo '<option value="fish">Zebrafish (Danio rerio)</option>';
	echo '<option value="fly">Fruit fly (Drosophila melanogaster)</option>';
	}
	if ($genome=="mouse")
	{
	echo '<option value="human">Human (Homo sapiens)</option>';
	echo '<option value="mouse" selected>Mouse (Mus musculus)</option>';
	echo '<option value="fish">Zebrafish (Danio rerio)</option>';
	echo '<option value="fly">Fruit fly (Drosophila melanogaster)</option>';
	}
	if ($genome=="fish")
	{
	echo '<option value="human">Human (Homo sapiens)</option>';
	echo '<option value="mouse">Mouse (Mus musculus)</option>';
	echo '<option value="fish" selected>Zebrafish (Danio rerio)</option>';
	echo '<option value="fly">Fruit fly (Drosophila melanogaster)</option>';
	}
	if ($genome=="fly")
	{
	echo '<option value="human">Human (Homo sapiens)</option>';
	echo '<option value="mouse">Mouse (Mus musculus)</option>';
	echo '<option value="fish">Zebrafish (Danio rerio)</option>';
	echo '<option value="fly" selected>Fruit fly (Drosophila melanogaster)</option>';
	}
	echo '</select>';
	echo "<input type=\"text\" name=\"gene\" value=$gene>";
	echo '<input type="submit" name="submit" value="Submit query">';
	echo '</form>';
	echo '<br />';
	echo '<h4>Error</h4>';
	echo "<p>$message</p>";
	echo '</div>';
	echo '<div id="footer">';
	echo '<p><a href="http://validator.w3.org/check?uri=referer" target="_blank" title="XHTML Validation">XHTML</a> | <a href="mailto:vfanjul@uoc.edu?Subject=Error%20Web%Genes" title="Problems?">Problems with the web?</a></p>';
	echo '</div>';
	echo '</div>';
	echo '</body>';
	echo '</html>';

   exit();
}

/* Variables */

$genome = $_POST['genome'];
$gene = $_POST['gene'];

$times = getdate();
$date = $times["hours"].":".$times["minutes"].":".$times["seconds"]." --- ".$times["mday"]."-".$times["month"]."-".$times["year"];

/* Error control: blank field or special characters */
if (strlen($gene)==0 or $gene==" ")
{
  print_error("<p>Error: Gene is not defined</p>\n", $genome, $gene);
}
else
{
  $gene = str_replace(" ","_",$gene);
  $gene = str_replace("\\","_",$gene);
}


/* Database connection */

$servername = "localhost";
$username = "vfanjul";
$password = "uoc";
$database = "genomes";

if ($genome=='human')
{
$table = "hg38";
$species = "human";
}
else if ($genome=='mouse')
{
$table = "mm10";
$species = "mouse";
}
else if ($genome=='fish')
{
$table = "danRer10";
$species = "zebrafish";
}
else
{
$table = "dm6";
$species = "fruitfly";
}

/* Connection to the mysql server and database */
$db = mysqli_connect($servername,$username,$password) or print_error("The connection to the mysql system is not working", $genome, $gene);
mysqli_select_db($db,$database) or print_error("The database is not accessible", $genome, $gene);

$query = "SELECT name2,name,chrom,strand,txStart,txEnd,exonCount FROM $table WHERE name2 LIKE '%$gene%';";
$query2 = "SELECT Gene,gene_ontology.id,function FROM $genome JOIN gene_ontology ON $genome.id=gene_ontology.id WHERE Gene LIKE '%$gene%'";

$result = mysqli_query($db,$query);
$items = mysqli_affected_rows($db);
$result2 = mysqli_query($db,$query2);
$items2 = mysqli_affected_rows($db);

if ($items == 0)
{
   print_error("The gene $gene is not found in the $genome database. Please, try another gene or genome.", $genome, $gene);
}
else
{
/* Head of the resulting web page */
	echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
	echo '<html xmlns="http://www.w3.org/1999/xhtml">';
	echo '<head>';
	echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
	echo '<meta name="author" content="Victor Fanjul Hevia" />';
	echo '<meta name="keywords" content="Universitat Oberta de Catalunya, Universitat de Barcelona, herramientas para la informatica, bioinformatics, master, genomics," />';
	echo '<meta name="description" content="Web degined by Victor Fanjul for Herramientas para la Informatica, Master de Bioinformatica y Bioestadistica, UOC & UB" />';
	echo '<meta name="robots" content="all" />';
	echo '<title>Know your Genes</title>';
	echo '<link rel="stylesheet" type="text/css" href="style.css" />';
	echo '</head>';

/* Top part of the resulting web page */
	echo '<body>';
	echo '<div id="header">';
	echo '<div id="topmenu">';
	echo '<div id="title">';
	echo '</div>';
	echo '<div id="mainmenu">';
	echo '<a href="index.html" id="index" title="Know your Genes"><span>Know your Genes</span></a>';
	echo '</div>';
	echo '<div id="leftmenu">';
	echo '<a href="https://www.uoc.edu" id="uoc" title="UOC" target="_blank"></a>';
	echo '<a href="https://www.ub.edu"  id="ub" title="UB" target="_blank"></a>';
	echo '</div>';
	echo '</div>';
	echo '</div>';
	echo '<div id="container">';
	echo '<div id="content">';
	echo '<h4>Your query</h4>';
	echo '<form action="server.php" method="post" enctype="multipart/form-data">';
	echo '<select name="genome" size="1">';
	if ($genome=="human")
	{
	echo '<option value="human" selected>Human (Homo sapiens)</option>';
	echo '<option value="mouse">Mouse (Mus musculus)</option>';
	echo '<option value="fish">Zebrafish (Danio rerio)</option>';
	echo '<option value="fly">Fruit fly (Drosophila melanogaster)</option>';
	}
	if ($genome=="mouse")
	{
	echo '<option value="human">Human (Homo sapiens)</option>';
	echo '<option value="mouse" selected>Mouse (Mus musculus)</option>';
	echo '<option value="fish">Zebrafish (Danio rerio)</option>';
	echo '<option value="fly">Fruit fly (Drosophila melanogaster)</option>';
	}
	if ($genome=="fish")
	{
	echo '<option value="human">Human (Homo sapiens)</option>';
	echo '<option value="mouse">Mouse (Mus musculus)</option>';
	echo '<option value="fish" selected>Zebrafish (Danio rerio)</option>';
	echo '<option value="fly">Fruit fly (Drosophila melanogaster)</option>';
	}
	if ($genome=="fly")
	{
	echo '<option value="human">Human (Homo sapiens)</option>';
	echo '<option value="mouse">Mouse (Mus musculus)</option>';
	echo '<option value="fish">Zebrafish (Danio rerio)</option>';
	echo '<option value="fly" selected>Fruit fly (Drosophila melanogaster)</option>';
	}
	echo '</select>';
	echo "<input type=\"text\" name=\"gene\" value=$gene>";
	echo '<input type="submit" name="submit" value="Submit query">';
	echo '</form>';
	echo '<br />';

/* First query */
	echo "<h4>Gene transcripts</h4>\n";
	echo "<table>\n";
	echo "<tr><th>Gene</th><th>Transcript</th><th>Chromosome</th><th>Strand</th><th>Start</th><th>End</th><th>Exons</th><th>Genome Browser</th></tr>\n";
	for ($i=0;$i<$items;$i++)
	{
	$row = mysqli_fetch_array($result);

        $name2 = $row["name2"];
        $name = $row["name"];
        $chrom = $row["chrom"];
        $strand = $row["strand"];
        $txStart = $row["txStart"];
        $txEnd = $row["txEnd"];
        $exonCount = $row["exonCount"];

	echo "<tr><td>$name2</td><td>$name</td><td>$chrom</td><td>$strand</td><td>$txStart</td><td>$txEnd</td><td>$exonCount</td>\n";
	echo "<td><a href=\"http://genome.ucsc.edu/cgi-bin/hgTracks?org=$species&db=$table&singleSearch=knownCanonical&position=$name2\" target=\"_blank\" title=\"$name2\">Link</a></td></tr>\n";
	}
	echo "</table>\n";
	echo '<br />';
	echo '<br />';

/* Second query */
	echo "<h4>Gene Ontology Associations</h4>\n";
	echo "<table>\n";
	echo "<tr><th>Gene</th><th>Gene Ontology</th><th>Function</th><th>More info</th></tr>\n";
	for ($i=0;$i<$items2;$i++)
	{
	$row2 = mysqli_fetch_array($result2);

        $Gene = $row2["Gene"];
        $go = $row2["id"];
        $function = $row2["function"];

	echo "<tr><td>$Gene</td><td>$go</td><td>$function</td><td><a href=\"http://amigo.geneontology.org/amigo/term/$go\" target=\"_blank\" title=\"$go\">Link</a></td></tr>\n";
	}
	echo "</table>\n";
	echo '</div>';

/* Bottom part of the resulting web page */
	echo '<div id="footer">';
	echo '<p><a href="http://validator.w3.org/check?uri=referer" target="_blank" title="XHTML Validation">XHTML</a> | <a href="mailto:vfanjul@uoc.edu?Subject=Error%20Web%Genes" title="Problems?">Problems with the web?</a></p>';
	echo '</div>';
	echo '</div>';
	echo '</body>';
	echo '</html>';
}
?>
