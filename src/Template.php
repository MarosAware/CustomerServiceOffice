<?php

class Template
{

    protected $template;
    protected $vars = [];

    public function __construct($file)
    {
        $this->template = $file;
    }

    public function add($key, $value)
    {
        $this->vars[$key] = $value;
    }

    public function parse()
    {
        if (!file_exists($this->template)) {
            return "Template does not exist ($this->template)";
        }
        $output = file_get_contents($this->template);

        foreach ($this->vars as $key => $value) {
            $tagToReplace = '{{'.$key.'}}';
            $output = str_replace($tagToReplace, $value, $output);
        }
        return $output;
    }

    static public function joinTemplates($templates)
    {
        $output = "";

        foreach ($templates as $template) {
            $content = (!$template instanceof Template) ? "Array element is not a Template object" : $template->parse();
            $output .= $content . "\n";
        }
        return $output;
    }
}