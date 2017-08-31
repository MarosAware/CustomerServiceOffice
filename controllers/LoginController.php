<?php

require __DIR__ . '/../src/Template.php';

//Tutaj dodaj kod

$index = new Template(__DIR__ . '/../templates/index.tpl');

$content = new Template(__DIR__ . '/../templates/login.tpl');

$index->add('content', $content->parse());

echo $index->parse();