<?php

return [
    'rules'=>[
        '/^\/Book$/'=>function(){
            return url_info('a/b/c');
        }
    ],
];