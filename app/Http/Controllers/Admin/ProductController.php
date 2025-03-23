<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function dialogCrud(){
        return inertia('Admin/Products/dialog_crud');
    }
    public function drawerCrud(){
        return inertia('Admin/Products/drawer_crud');
    }

    public function account()
    {
        return inertia('Admin/Account/Index');
    }
}
