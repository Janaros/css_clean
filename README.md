css_clean
=========

CSS Clean

Checks one or more CSS files for duplicate declarations. 
I needed i in an huge Progject with many developers...

Call:
$files = array('dummy1.css','dummy2.css'); // All CSS filenames
$css = new cssCheck($files);
echo "<pre>";
print_r($css->checkDouble());
