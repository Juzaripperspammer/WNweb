<?php
	// Formdan gelen verileri al
	$name = $_POST['name'] ?? '';
	$version = $_POST['version'] ?? '';
	$translator = $_POST['translator'] ?? '';
	$status = $_POST['status'] ?? '';
	$image = $_FILES['image'] ?? null;

	if ($name && $version && $translator && $status) {
		// Veritabanına bağlan
		$db = new PDO('odbc:Driver={Microsoft Access Driver (*.mdb)};Dbq=' . realpath('programlar.mdb') . ';Uid=;Pwd=;');

		// Veriyi veritabanına kaydet
		$stmt = $db->prepare("INSERT INTO programs (name, version, translator, status, image) VALUES (:name, :version, :translator, :status, :image)");
		$stmt->bindParam(':name', $name);
		$stmt->bindParam(':version', $version);
		$stmt->bindParam(':translator', $translator);
		$stmt->bindParam(':status', $status);
		if ($image) {
			$image_data = file_get_contents($image['tmp_name']);
			$stmt->bindParam(':image', $image_data, PDO::PARAM_LOB);
		} else {
			$stmt->bindValue(':image', null, PDO::PARAM_NULL);
		}
		$stmt->execute();

		// Başarılı bir şekilde kaydedildi mesajını göster
		echo '<p style="color: green">Program başarıyla kaydedildi.</p>';
	}
?>

<!-- Program ekleme formu -->
<form method="post" enctype="multipart/form-data">
	<label for="name">Program Adı:</label>
	<input type="text" name="name" required><br>

	<label for="version">Versiyon:</label>
	<input type="text" name="version" required><br>

	<label for="translator">Çevirmen:</label>
	<input type="text" name="translator" required><br>

	<label for="status">Durum:</label>
	<input type="text" name="status" required><br>

	<label for="image">Resim:</label>
	<input type="file" name="image"><br>

	<input type="submit" value="Ekle">
</form>

<!-- Programlar tablosunu listele -->
<?php
	// Veritabanına bağlan
	$db = new PDO('odbc:Driver={Microsoft Access Driver (*.mdb)};Dbq=' . realpath('programlar.mdb') . ';Uid=;Pwd=;');

	// Programları sorgula
	$stmt = $db->query("SELECT * FROM programs");
	$programs = $stmt->fetchAll(PDO::FETCH_ASSOC);

	if ($programs) {
		echo '<h2>Programlar:</h2>';
		echo '<table border="1">';