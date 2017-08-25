<?php

require '../src/Template.php';

//Tutaj dodaj kod

$index = new Template('../templates/index.tpl');

$content = new Template('../templates/login.tpl');

$index->add('content', $content->parse());

echo $index->parse();