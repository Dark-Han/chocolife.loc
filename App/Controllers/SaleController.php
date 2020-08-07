<?php

namespace App\Controllers;

use Core\Controller;
use App\Models\Sale;

class SaleController extends Controller
{
    private $model;

    public function __construct()
    {
        $this->model = new Sale();
    }

    public function index()
    {
        $data = $this->model->getUrls();
        $this->response($data);
    }

    public function show($id)
    {
        $data = $this->model->find($id);
        $this->response($data);
    }

    public function store()
    {
        $data = $this->model->handleCsv();
        $this->response($data);
    }

    public function destroy()
    {
        $this->model->destroy();
        $this->response();
    }
}
