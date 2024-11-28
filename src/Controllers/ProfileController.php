<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class ProfileController extends BaseController
{
    // Default method for showing the dashboard
    public function showProfile(){
        $data = [
            'isProfile' => true,
        ];
        return $this->render('root', $data);
    }


}
