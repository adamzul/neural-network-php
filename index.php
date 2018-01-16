<!DOCTYPE html>
<html>
<head>
	<title>neural network</title>
	<link rel="stylesheet" type="text/css" href="bootstrap.min.css">
</head>
<body class="body">
<div class="container" style="margin-top:30px">
	<?php
	
	
	?>

	<form method="post" enctype="multipart/form-data">
	
	  <div class="input-group input-group-lg">
		  <span class="input-group-addon" id="sizing-addon1">jumlah looping</span>
		  <input type="text" class="form-control" placeholder="looping" aria-describedby="sizing-addon1" name="loop">
	</div>
	<br>
	<div class="input-group input-group-lg">
		  <span class="input-group-addon" id="sizing-addon1">miu</span>
		  <input type="text" class="form-control" placeholder="miu" aria-describedby="sizing-addon1" name="miu">
	</div>
	<br>
	  <span class="btn btn-default btn-file" >
        Browse <input type="file" id="fileToUpload" name="fileToUpload">
    	</span>
	  <br><br>
	  <input type="submit" name="submit" value="submit" class="btn btn-default">
	</form>
	<?php
	if(isset($_POST['submit']))
	{
	$file = basename($_FILES["fileToUpload"]["name"]);
	$dataTraining = getCsv($file);
	echo '<h3> <span class="label label-default">data </span></h3>';
	echo '<table class="table table-bordered">';
	echo '<tr>';
	for ($k=0; $k < count($dataTraining[0])-1; $k++) { 
		# code...
		echo '<th>';
		echo 'fitur '. ($k+1);
			echo '</th>';
	}
	echo '<th>target</th>';
	echo '</tr>';
	foreach ($dataTraining as $data) {
		# code...
		echo '<tr>';
		foreach ($data as $row) {
			# code...
			echo '<th>'.$row.'</th>';
		}
		echo '</tr>';
	}
	echo '</table>';
	$jumData = count($dataTraining);
	$jumFitur = count($dataTraining[0])-1;
	$beban = getBeban($jumFitur+1);
	echo '<br>';
	$miu = $_POST['miu'];
	$loop = 0;
	
	echo '<h3> <span class="label label-default">beban awal</span></h3>';
	echo '<table class="table table-bordered">';
	echo '<tr>';
		for ($k=0; $k < count($beban); $k++) { 
			# code...
			echo '<th>';
			echo 'beban '. ($k+1);
  			echo '</th>';
		}
		echo '</tr>';
		echo '<tr>';
		for ($k=0; $k < count($beban); $k++) { 
			# code...
			echo '<td>';
			echo $beban[$k];
  			echo '</td>';
		}
  		echo '</tr>';
	echo '</table>';
	do{
		$true = 0;
		echo '<h3> <span class="label label-default">epoc:'.($loop+1).'</span></h3>';
		echo '<table class="table table-bordered">';
		echo '<tr>';
			for ($k=0; $k < count($beban); $k++) { 
				# code...
				echo '<th>';
				echo 'beban '. ($k+1);
	  			echo '</th>';
			}
			echo '<th>summation</th>';
			echo '<th>output</th>';
			echo '<th>target</th>';
			echo '</tr>';
		for($h=0;$h<$jumData;$h++)
		{
			$summation=0;
			for($i=0;$i<$jumFitur+1;$i++)
			{
				if($i==0)
				{
					$summation = $summation +$beban[$i];
				}
				else
				{
					$summation = $summation + $dataTraining[$h][$i-1]*$beban[$i];
				}
			} 
			if($summation<=0)
			{
				$output = 0;
			}
			else
			{
				$output = 1;
			}
			if($output == $dataTraining[$h][$i-1])
			{
				$true++;
			}
			else
			{
				for ($j=0; $j < $jumFitur+1; $j++) { 
					# code...
					if($j==0)
					$beban[$j] = $beban[$j] + $miu*1*($dataTraining[$h][$i-1]-$output);
					else{
						$beban[$j] = $beban[$j] + $miu*$dataTraining[$h][$j-1]*($dataTraining[$h][$i-1]-$output);
						// echo 'tes'.$h.'<br>';
					}
				}
			}
			
			
			
			echo '<tr>';
			for ($k=0; $k < count($beban); $k++) { 
				# code...
				echo '<td>';
				echo $beban[$k];
	  			echo '</td>';
			}
			echo '<td>'. $summation.'</td>';
			echo '<td>'. $output.'</td>';
			echo '<td>'. $dataTraining[$h][$i-1].'</td>';
	  		echo '</tr>';
			
			// var_dump($beban);
			
		}
		echo '</table>';
		
		$loop++;
	}while($loop<$_POST['loop'] && $true!=count($dataTraining));
	}
	
?>
</div>
<?php
	function getCsv($name)
	{
		$array = array();
		if (($handle = fopen($name, "r")) !== FALSE) {
		    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
		    	array_push($array, $data);
		    }
		    return $array;
		}
	}

	function getBeban($jum)
	{
		$array = array();
		for($i=0;$i<$jum;$i++)
		{
			array_push($array,rand(-1000,1000)/1000);
		}
		return $array;
	}
	?>
</body>
</html>