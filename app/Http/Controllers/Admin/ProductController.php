<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        return inertia('Admin/Products/Index');
    }

    public function abc()
    {
        return inertia('Admin/Products/Abc');
    }

    public function orders()
    {
        return inertia('Admin/Orders/Index');
    }

    public function packages()
    {
        return inertia('Admin/Packages/Index');
    }
}
