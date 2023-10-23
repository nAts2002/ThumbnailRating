<?php
require_once 'pdo.php';
require_once 'Thumbnail.php';

if (isset($_POST['choice'])) {
    $choice = $_POST['choice'];
    // get the ids of the two thumbnails
    $id1 = $_POST['id1'];
    $id2 = $_POST['id2'];
	$ranking1 = $_POST['ranking1'];
	$ranking2 = $_POST['ranking2'];
	$difference = abs($ranking1 - $ranking2);
	$change =  $difference * 0.1 + 10;
	echo $change;
    // update the ratings of the two thumbnails
    $sql = "UPDATE thumbnails SET ranking = ranking + :delta WHERE id = :id";
    $stmt = $conn->prepare($sql);
    if ($choice == 'left') {
        // increase the rating of the left thumbnail by 100
        $stmt->execute(array(':delta' => $change, ':id' => $id1));
        // decrease the rating of the right thumbnail by 100
        $stmt->execute(array(':delta' => -$change, ':id' => $id2));
    } else {
        // increase the rating of the right thumbnail by 100
        $stmt->execute(array(':delta' => $change, ':id' => $id2));
        // decrease the rating of the left thumbnail by 100
        $stmt->execute(array(':delta' => -$change, ':id' => $id1));
    }
    // reload the page
    header("Location: analyze.php");
    return;
}

?>
<!DOCTYPE html>
<html>
<head> 
	<title>Analyze</title>
	<style>
	    .thumbnail-image {
	        width: 300px;
	        height: 225px;
	    }
	    .thumbnail-title {
	        font-size: 18px;
	        font-weight: bold;
	        text-align: center;
	        width: 300px;
	    }
	    .thumbnail-pair {
	        display: flex;
	        justify-content: space-around;
	    }
	    .thumbnail-container {
	        display: flex;
	        flex-direction: column;
	        align-items: center;
	    }
		form {
			display: flex;
			justify-content: center;
		}

		button {
			width: 10vw;
			margin-left: 20rem;
			margin-right: 20rem;
		}
		h1 {
			text-align: center;
		}
		a{
			text-align: left;
			background-color: #4CAF50;
			color: white;
			text_decoration: none;
			padding: 5px;
			margin-left: 40px;
		}
		
	</style>
</head>
<body>
	<a href="index.php">Home</a>
	<h1> Which one would you choose<h1>
	<div class="thumbnail-pair">
		<?php 
		// get two random thumbnails from the database
		$sql = "SELECT * FROM thumbnails ORDER BY RAND() LIMIT 2";
		$stmt = $conn->prepare($sql);
		$stmt->execute();
		$thumbnails = $stmt->fetchAll(PDO::FETCH_ASSOC);
		foreach ($thumbnails as $thumbnail) {
			echo "<div class='thumbnail-container'>";
			echo "<img class='thumbnail-image' src = " . $thumbnail['url'] . ">";
			echo "<p class='thumbnail-title'>" . $thumbnail['title'] . "</p>";
			echo "</div>";
		}
		
		?>
		
	</div>
	
	<form method="post">
    <input type="hidden" name="id1" value="<?php echo $thumbnails[0]['id']; ?>">
    <input type="hidden" name="id2" value="<?php echo $thumbnails[1]['id']; ?>">
    <input type="hidden" name="ranking1" value="<?php echo $thumbnails[0]['ranking']; ?>">
    <input type="hidden" name="ranking2" value="<?php echo $thumbnails[1]['ranking']; ?>">
    <button type="submit" name="choice" value="left">Pick Left</button>
    <button type="submit" name="choice" value="right">Pick Right</button>
</form>

	
</body>
</html>
