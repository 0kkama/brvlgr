<?php
    
    $list = glob('/resources\/img\/cats\/*.jpg/');








    $title = 'Галерея';
    $content = template('gallery.view.php',
        [
            'title' => $title,
        ]
    );

