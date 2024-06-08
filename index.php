<?php
/* Configuration */
    $header_image = ""; // If blank, no header image will be displayed
    $gallery_url = "";  // If blank, no link to a gallery will be displayed
    $filename = 'ICM_Export.txt';
    $delimiter = "\t";

/* Load File/Create Named Data Array */
    $data = []; $positions = [];
    $fp = fopen($filename, 'r');
    $linecounter = 0;
    while ( !feof($fp) )
    {
        $line = fgets($fp, 2048);
        $linecounter++;
        $linedata = str_getcsv($line, $delimiter);
        if ($linecounter == 1) {       
           foreach ($linedata as $key => $value) {
               $positions[$value] = $key;
           }
        } else if (sizeof($linedata) > 1){
            $newArray = [];
            foreach ($positions as $key => $value) {
                if (isset($linedata[$value])) {
                    $newArray[$key] = $linedata[$value];
                }
            }
            array_push($data, $newArray);
        }
    }                              
    fclose($fp);
  
/* Group by Competition and Award */
    $grouped_data = [];
    foreach ($data as $entry) {
        $competition = $entry['Competition Title'];
        $award = $entry["Award Short Name"];
        if (!isset($grouped_data[$competition])) {
            $grouped_data[$competition] = [];
        }
        if (!isset($grouped_data[$competition][$award])) {
            $grouped_data[$competition][$award] = [];
        }
        array_push($grouped_data[$competition][$award], $entry);
    }

/* Determine the year from the Competition Date */
    $year = substr($data[0]["Competition Date"],-4);
?>
<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta http-equiv="Content-Style-Type" content="text/css">
        <title>St Louis Camera Club <?=$year?> Image of the Year Results</title>
        <meta name="Author" content="SLCC">
        <meta name="Description" content="<?=$year?> SLCC Image Of The Year">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
        <style type="text/css">
            body { width: 95%;  
                font-family: "Roboto", sans-serif;
                font-weight: 400;
                font-style: normal;
                padding: 0 50px;
            }
            h1 { text-align: center; border:1px solid #666; border-radius: 10px; background: #ccc; color: #000; padding: 10px; }
            h1, h3 { font-size: 1.5em; }
            li { padding-top: 2px; margin-left: 10px; }
            td { padding: 5px 10px 5px; }
            .gallerya { 
                margin: auto;
                padding: 0;
                width="90%";
                background-color: LightGray;
                border: 2px solid silver;
                border-radius: 10px;
            }
            .gallerya td { padding: 0px; }
            .gallerya figure { 
                margin-inline-start: 1px; margin-inline-end: 1px; 
                FONT-SIZE: 0.8em;
                font-family: "Roboto", sans-serif;
                font-weight: 700;
                font-style: normal;
            }
            .firstplace {  font-size: 1.2em; font-weight: bold; }
        </style>
    </head>
    <body>
            
        <?php if ($header_image != "") { ?>
            <center><img src="<?=$header_image ?>"></center>
        <?php } ?>
        
        <h1><?=$year?> <?=$data[0]["Competition Type"]?></h1>
        
        <h3>Judges:</h3>
        <ul style="list-style:none;font-size:1.2em;" ;>
            <?=str_replace("Website Judges:","",$data[0]["Judges"]) ?>
        </ul>

        <?php if ($gallery_url == "") { ?>
        <p style="font-size:1.5em;text-align:center;margin-top:20px;"> View a Slideshow of the full size images <a
			href="<?=$gallery_url?>"> here</a>. </p>
        <?php } ?>

<?php foreach ($grouped_data as $competition => $awards) { ?>

    <div class="resultwords">
		<h3><?=$competition ?></h3>
		<table>
            <?php foreach ($awards["1st"] as $award) { ?>
                <tr class="firstplace"><td><?=$award["Award Short Name"] ?></td>
                <td><?=$award["Title"] ?></td>
                <td><?=$award["Author's Formal Name"] ?></td></tr>
            <?php } ?>
            <?php foreach ($awards["HM"] as $award) { ?>
                <tr><td><?=$award["Award Short Name"] ?></td>
                <td><?=$award["Title"] ?></td>
                <td><?=$award["Author's Formal Name"] ?></td></tr>
            <?php } ?>
		</table>
	</div>

    <div class="gallerya">
		<table style="margin:auto;">
			<tr>
                <?php foreach ($awards["1st"] as $award) { ?>
                    <td style="width:22%;padding:1px;">
                        <figure style="text-align:center;">
                            <img src="<?= $award['Exported File Name']?>" alt="<?=$award["Title"] ?>">
                            <figcaption><?=$award["Title"] ?><br><?=$award["Author's Formal Name"] ?></figcaption>
                        </figure>
                    </td>
                <?php } ?>
                <?php foreach ($awards["HM"] as $award) { ?>
				<td style="width:22%;padding:1px;">
					<figure style="text-align:center;">
                        <img src="<?= $award['Exported File Name']?>" alt="<?=$award["Title"] ?>">
                        <figcaption><?=$award["Title"] ?><br><?=$award["Author's Formal Name"] ?></figcaption>
                    </figure>
				</td>
                <?php } ?>
			</tr>
		</table>
	</div>

<?php } ?>

</html>


