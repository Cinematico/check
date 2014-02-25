<?php
/* If you see this text, it means that your server is not running PHP, which means that you can’t run Cinematico. Ask your system administrator to install PHP. */
?>
<?php
  $is_ready = true;

  if (version_compare(PHP_VERSION, '5.3.6', '>=') != true)
    $is_ready = false;

  $have_rewrite = apache_is_module_loaded('mod_rewrite');
  if ($have_rewrite != true)
    $is_ready = false;

  if ( ! function_exists('preg_match'))
     $is_ready = false;

  if ( ! @preg_match('/^.$/u', 'ñ'))
    $is_ready = false;

  if ( ! @preg_match('/^\pL$/u', 'ñ'))
    $is_ready = false;

?>
<!doctype html>
<html>
    <head>
        <title>Cinematico Server Check</title>
        <link href="http://fonts.googleapis.com/css?family=Lato:300,400,700,400italic,700italic" rel="stylesheet" type="text/css">
        <style>
            body {
                background: #ffffff;
                font-family: 'Lato', sans-serif;
                font-size: 14px;
                line-height: 20px;
                color: #404040;
                text-rendering: optimizeLegibility;
                -webkit-font-smoothing: antialiased;
                -webkit-text-size-adjust: 100%;
                width: auto;
                height: auto;
                margin: 0;
                padding: 0;
            }
            
            #container {
                width: 520px;
                padding: 150px;
            }
            
            hr {
                border: none;
                background-color: #ebebeb;
                height: 1px;
                margin: 40px 0;
            }
            
            h2 {
                font-size: 34px;
                color: #202020;
                margin: 0 0 20px 0;
                padding: 0;
            }
            
            h3 {
                font-size: 16px;
                color: #202020;
                margin: 0 0 10px 0;
                padding: 0;
            }
            
            p {
                
                padding: 0;
            }
            
            ul {
                list-style: none;
                margin: 0 0 40px 0;
                padding: 0;
            }
            
            .pass {
                color: #60b86b;
            }
            
            .button {
                background-color: #60b86b;
                font-size: 14px;
                line-height: 20px;
                color: #ffffff;
                text-decoration: none;
                margin: 0 0 5px 0;
                padding: 8px 14px 10px;
                display: inline-block;
            }
        </style>
    </head>
    <body>
        <div id="container">
            <h2>Cinematico Server Check</h2>
            <?php if ($is_ready): ?>
            <p>Congrats! Your server seems worthy. You are ready for Cinematico.</p>
            <a class="button" href="http://cinemati.co">Get Cinematico</a>
            <?php else: ?>
            <?php if ($have_rewrite): ?>
            <p>It seems that your server may not be ready for Cinematico.</p>
            <?php else: ?>
            <p>Curious, it seems that your server may not be ready. We could not determine if Mod Rewrite was enabled or not.</p>
            <?php endif ?>
            <?php endif; ?>
            
            <hr>
            
            <h3>PHP Version</h3>
            <?php if (version_compare(PHP_VERSION, '5.3', '>=')): ?>
            <ul>
                <li class="pass">Pass: <?php echo PHP_VERSION ?></li>
            </ul>
            <?php else: $failed = TRUE ?>
            <ul>
                <li class="fail">Failed: <?php echo PHP_VERSION ?></li>
                <li class="note">Cinematico requires PHP 5.3 or newer.</li>
            </ul>
            <?php endif ?>
            
            <hr>
            
            <h3>PCRE UTF-8</h3>
            <?php if ( ! function_exists('preg_match')): $failed = TRUE ?>
            <ul>
                <li class="fail">Failed</li>
                <li class="fail">PCRE support is missing.</li>
                <li class="note">Ask your system administrator to add <a href="http://php.net/pcre" target="_blank">PCRE</a> support to your server.</li>
            </ul>
            <?php elseif ( ! @preg_match('/^.$/u', 'ñ')): $failed = TRUE ?>
            <ul>
                <li class="fail">Failed</li>
                <li class="fail">No UTF-8 support</li>
                <li class="note"><a href="http://php.net/pcre" target="_blank">PCRE</a> has not been compiled with UTF-8 support.</li>
            </ul>
            <?php elseif ( ! @preg_match('/^\pL$/u', 'ñ')): $failed = TRUE ?>
            <ul>
                <li class="fail">Failed</li>
                <li class="fail">No Unicode support</li>
                <li class="note"><a href="http://php.net/pcre" target="_blank">PCRE</a> has not been compiled with Unicode property support.</li>
            </ul>
            <?php else: ?>
            <ul>
                <li class="pass">Pass</li>
            </ul>
            <?php endif ?>  
            
            <hr>          

            <h3>Mod Rewrite</h3>
            <?php $have_module = apache_is_module_loaded('mod_rewrite'); ?>
            <?php if ($have_module): ?>
            <ul>
                <li class="pass">Pass</li>
            </ul>
            <?php elseif ($have_module == null): ?>
            <ul>
                <li class="unknown">Unknown</li>
                <li class="note">We could not determine if Mod Rewrite was enabled or not.</li>
            </ul>
            <?php else: ?>
            <ul>
                <li class="fail">Failed</li>
                <li class="note">Ask your system administrator to enable Mod Rewrite for your site.</li>
            </ul>
            <?php endif ?>
            
            <hr>

            <h3>cURL</h3>
            <?php $have_curl = function_exists('curl_version') ? true : false; ?>
            <?php if ($have_curl): ?>
            <ul>
                <li class="pass">Pass</li>
            </ul>
            <?php else: ?>
            <ul>
                <li class="fail">Failed</li>
                <li class="note">Ask your system administrator to enable cURL for your site</li>
            </ul>
            <?php endif ?>
            
            <hr>

            <h3>GD Library</h3>
            <?php $gd = gdversion(); ?>
            <?php if ($gd): ?>
            <ul>
                <li class="pass">Pass</li>
            </ul>
            <?php elseif ($gd == null): ?>
            <ul>
                <li class="unknown">Unknown</li>
                <li class="note">We could not determine if GD Library was enabled or not</li>
            </ul>
            <?php else: ?>
            <ul>
                <li class="fail">Missing</li>
                <li class="note">You will still be able to use Cinematico, you just won't be able to use any image manipulation features.</li>
            </ul>
            <?php endif ?>
        </div>
    </body>
</html>
<?php
    function gdversion() {
        $gd = gd_info();
        $ver = $gd['GD Version'];
        return $ver;
    }
    
    function apache_is_module_loaded($mod_name) {
        if (function_exists('apache_get_modules')) {
            $modules = apache_get_modules();
            return in_array($mod_name, $modules);
        } else {
            return null;
        }
    }
?>
