<?php 

/**
 * @author     2011 bitprofessional.com Ltd., Schulstr. 11, 56579 Bonefeld, Bitprofessional.com Developer <developer@bitprofessional.com>
 * @maintainer Sascha Klein <klein.sascha@bitprofessional.com>
 * @encoding   UTF-8 äöüßÖÄÜ
 * @linzenz    GNU Gerneral Public License (GPL)
 * @note       bitprofessional.com Ltd. übernimmt keine Garantie auf Funktionalität oder für ggf. entstehende Schäden durch Nutzung dieser Software.
 * @package    library
 * @subpackage db
 * @link       SVN: $HeadURL$
 * @version    SVN: $Id$
 * @phpVersion 5.3
 */

require_once 'Init/init.php';
$shorter = new Shorter();

$url = $_REQUEST['url'];
if (!empty($url)) :
    $redirectUrl = $shorter->getUrlByHash($url);
    if (empty($redirectUrl)) :
        header("HTTP/1.0 404 Not Found");
        $notFound = true;
    else :
        $newLocation = array_shift($redirectUrl);
    endif;
endif;
?>

<!DOCTYPE HTML>
<html>
    <head>
    	<title><?php echo $_SERVER['HTTP_HOST']; ?> URL-Shortener</title>
    	<meta name="description" content="URL-Shortener" />
		<meta name="keywords" content="URL, Shortener" />
		<meta name="author" content="bitprofessional.com Ltd." />
    	<meta charset="UTF-8" />
    	<link rel="stylesheet" type="text/css" href="styles/style.css" media="all" />
    	<!--[if lte IE 7]><link rel="stylesheet" type="text/css" href="styles/styleIE7.css" media="all" /><![endif]-->
		<!--[if lte IE 6]><link rel="stylesheet" type="text/css" href="styles/styleIE6.css" media="all" /><![endif]-->
    	<script type="text/javascript">
        	function redirect() {
                location.href='<?php echo $newLocation; ?>';
            }
    	</script>
        <!-- Google Analytics Code -->
    </head>
	<body>
		<?php if (isset($newLocation)) : ?>
		<p>You'll be redirected in 3 seconds. If redirect doesn't work, click <a class="redirectLink" href="<?php echo $newLocation; ?>">here</a>.</p>
    	<script type="text/javascript">
			window.setTimeout("redirect()", 3000); 
		</script>
		<?php else : ?>
		<div class="formarea">
			<p class="bitpro">bit<span class="bitpro-red">pro</span>fessional.<span class="bitpro-red">com</span></p>
			<?php
            if (isset($notFound) && $notFound) :
            ?>
                <p class="info-not-found">Your short URL can not be found. <a href="<?php echo str_replace('index.php', '', $_SERVER['SCRIPT_NAME']); ?>">Create a shorten URL</a></p>
            <?php
            else :
            ?>
            <p class="info">Please enter your URL below:</p>
            <?php
            $shortenerForm = new Form_ShortItForm();
            
            if (!empty($_REQUEST) && key_exists('shortUrl', $_REQUEST)) :
                $errors = $shortenerForm->validate($_REQUEST);
                if (!empty($errors)) :
            ?>
                    <ul class="error">
                        <?php 
                            foreach ($errors as $error) :
                        ?>
                        		<li><?php echo $error; ?></li>
                        <?php
                            endforeach;
                        ?>
                    </ul>
            <?php
                else : 
            ?>
            	<p class="result"><?php echo $_SERVER['HTTP_REFERER'] . $shorter->shortUrl($_REQUEST['shortUrl']); ?></p>
            <?php
                    mail('admin@bitprofessional.com', 'URL shorted', 'Someone has short the URL ' . $_REQUEST['shortUrl']);
                endif;
            endif;
                echo $shortenerForm->getForm();
            endif;
            ?>
		</div>
		<div class="footer"><a href="http://www.bitprofessional.com/impressum.html">Impressum</a> | <a href="http://www.bitprofessional.com/disclaimer.html">Disclaimer</a><?php /*| <a href="">Datenschutz</a>*/ ?></div>
		<?php endif; ?>
	</body>
</html>