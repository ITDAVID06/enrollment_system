<?php

namespace App\Traits;

trait Renderable
{
    public function render($template, $data = [])
    {
        $this->initializeSession();

        $data['complete_name'] = $_SESSION['complete_name'] ?? null;
        $data['email'] = $_SESSION['email'] ?? null;
        $data['role'] = $_SESSION['role'] ?? null;

        global $mustache;
        $tpl = $mustache->loadTemplate($template);

        echo $tpl->render($data);
    }
}