<?php
require_once 'pdo.php';
require_once 'Thumbnail.php';
$api_key = "AIzaSyA64XyCijuNIzmYJD-o35835LSksvbKZN0"

?>
<!DOCTYPE html>
<html>
<head> 
	<title>Thumbnail Ranking</title>
	<link rel="stylesheet" href="index.css">
</head>
<body>
	<h1> Thumbnail Ranking Website<h1>
	<form method="post">
	    <input type="text" id="youtube_url" name="youtube_url" required>
	    <button type="submit" name="create">Add with YouTube URL</button>
	</form>
	<a class="analyze" href = "/analyze.php">Rate</a>
	<div>
		<?php $sql = "SELECT * FROM thumbnails ORDER BY ranking DESC";
		$stmt = $conn->prepare($sql);
		$stmt->execute();
		$thumbnails = $stmt->fetchAll(PDO::FETCH_ASSOC);
		foreach ($thumbnails as $thumbnail) {
			echo "<div class='thumbnail-container'>";
			echo "<div class='thumbnail-actions'>";
			echo "<span class='thumbnail-rating'>Ranking: " . $thumbnail['ranking'] . "</span>";
			echo "<span class='thumbnail-delete'><a class='delete' href = '/delete.php?id=" . $thumbnail['id'] . "'>Delete</a></span>";
			echo "</div>";
			echo "<img class='thumbnail-image' src = " . $thumbnail['url'] . ">";
			echo "<p class='thumbnail-title'>" . $thumbnail['title'] . "</p>";
			echo "</div>";
		}
		
		if (isset($_POST['youtube_url'])) {
		    $youtube_url = $_POST['youtube_url'];
			// get the youtube video id
		    parse_str(parse_url($youtube_url, PHP_URL_QUERY), $params);
		    $video_id = $params['v'];
			// get the thumbnail url and title
			$thumbnail_url = "https://img.youtube.com/vi/$video_id/maxresdefault.jpg";
			$api_url = "https://www.googleapis.com/youtube/v3/videos?id=$video_id&key=$api_key&part=snippet";
			$api_json = file_get_contents($api_url);
			$api_array = json_decode($api_json, true);
			$thumbnail_title = $api_array['items'][0]['snippet']['title'];
			// insert into the database
		    $thumbnail = new Thumbnail($thumbnail_url, $thumbnail_title, 2000);
			$thumbnail->create($conn);
		    // reload the page
		    header("Location: index.php");
		    return;
		}
		
		?>
		
	</div>
	
</body>
</html>
