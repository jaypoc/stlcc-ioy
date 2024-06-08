# St Louis Camera Club - Image of the Year (IOY) Page Template

This repo is meant to house the template PHP script for use by the webmaster of the St. Louis Camera Club, but feel free to take the script and modify it for your own use.

### Requirements

* The web server must have PHP support (Tested in PHP 8.3)
* Image Competition Manager by Visual Pursuits
* [ImageMagick](https://https://imagemagick.org/script/download.php)

### How does this work?

The script loads the tab-separated value (TSV) file into two arrays. The header gets it's own arraw and is used later, but the data is saved to an array  called ```$data```. Fields that have common values across all rows in the file can be accessed by pulling the first record such as:
```php
$data[0]["Competition Date"]
```
The data is also grouped by competition and award name:
```php
$grouped_data["Competition Title"]["Award Short Name"]
```
The grouped data can be looped through as follows. The first loop goes through each competition and then the two inner loops will loop through each of the award recipients in that level:

```php
foreach ($grouped_data as $competition => $awards) {
    print("Processing the ".$competition." competition.");
    foreach ($awards["1st"] as $award) {
        print("Awarded 1st place to ".$award["Author's Formal Name"]."<br/>");
    }
    print("Honorable Mentions went to:"."<br/>");
    foreach ($awards["HM"] as $award) {
        print($award["Author's Formal Name"]."<br/>");
    }
}
```
# Installation Instructions

### Step 1: Export your Images and Tab-delimited file from ICM

    1. Load all of the IOY competitions just as you would any normal competition.
    2. Go to the "Import Export" tab (Make sure all IOY competitions are shown)
    3. Click "Export" (at the bottom of the window)
    4. Export with these settings:
        * Award Level: 101 - HM
        * Minimum Score: 0
        * Output Folder: Set this to where you want to save the files
        * Columns: You can export them all, but at a minimum, the following are   required (Others will be ignored):
            1. Competition Title
            2. Award Short Name
            3. Competition Type
            4. Judges
            5. Title
            6. Author's Formal Name
            7. Exported File Name
        * Export copies of the image files: Checked
        * Export File Name Format: %Title%~%Author%

### Step 2: Reduce Image Sizes

You'll need to re-size all the images to a website thumbnail. This can be done with many different software packages, but I recommend using ImageMagick to update the entire folder of images at once (Click [here](https://https://imagemagick.org/script/download.php) to download it).

* Re-size all the images to fit with in a 300x300 frame. 

To do this with ImageMagick, you can simply run the following command from the folder with the images.

```
magick mogrify -geometry 300x300 *.jpg
```

### Step 3: Set Up index.php

1. Copy the **index.php** file into the folder with the images

2. Edit the top few lines of the file called Configuration:
    
    ```php
    $header_image = "https://www.url.to/header_image.jpg"
    $gallery_url = ""; 
    $filename = 'ICM_Export.txt';
    $delimiter = "\t";
    ```
|   |   |
|---|---|
|$header_image|The URL (full or relative) to the image to be used as the header |
|$gallery_url|An optional URL to a full-screen gallery. If left blank, the gallery link will not be shown.
|filename|The name of the tab-separated value text file exported from the Image Competition manager tool.|
|$delimiter| The character to use to separate fields in the file. By defauly, this should be a tab ```\t```.|


### Step 4: Upload all the files to the gallery's directory

Upload the following files to your album's directory:

* all the resized images 
* The tab-separated values text file from ICM
* The **index.php** file

If your webserver is configured for PHP properly, browsing to the webpage from your browser should show the IOY webpage.