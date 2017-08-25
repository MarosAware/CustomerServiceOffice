<?php

require '../src/Template.php';

//Tutaj dodaj kod

$index = new Template('../templates/index.tpl');

$content = new Template('../templates/client_content.tpl');

$index->add('content', $content->parse());

echo $index->parse();
