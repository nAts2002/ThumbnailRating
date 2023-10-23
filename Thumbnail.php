<?php
class Thumbnail {
    public $url;
    public $title;
    public $ranking;
    public function __construct($url, $title, $ranking) {
        $this->url = $url;
        $this->title = $title;
        $this->ranking = $ranking;
    }
    public function create($conn){
        $sql = "INSERT INTO thumbnails (url, title, ranking) VALUES (:url, :title, :ranking)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':url', $this->url);
        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':ranking', $this->ranking);
        $stmt->execute();
    }
    public function update($conn){
        $sql = "UPDATE thumbnails SET url = :url, title = :title, ranking = :ranking WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':url', $this->url);
        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':ranking', $this->ranking);
        $stmt->bindParam(':id', $this->id);
        $stmt->execute();
    }
    public function delete($conn){
        $sql = "DELETE FROM thumbnails WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $this->id);
        $stmt->execute();
    }

}
?>