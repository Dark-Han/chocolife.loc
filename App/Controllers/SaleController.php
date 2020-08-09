<?php

namespace App\Controllers;

use Core\Controller;
use App\Models\Sale;
use App\Factories\FileStaticFactory;

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
        //Иньекция зависимостей которая реализована 
        //в фреймворках с DI контейнером лучше ))
        $data = $this->model->store(FileStaticFactory::build('csv'));
        $this->response($data);
    }

    public function destroy()
    {
        $this->model->destroy();
        $this->response();
    }
}
