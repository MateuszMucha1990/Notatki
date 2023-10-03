<?php
declare(strict_types=1);

namespace App\Controller;


use App\Request;
use App\View;

use App\Exception\ConfigurationException;
use App\Exception\StorageException;
use App\Model\NoteModel;

abstract class AbstractController
{
    protected const DEFAULT_ACTION = "list";

    //WLASCIWOSCI Z TYPOWANIEM,
    private static array $configuration = [];
    protected NoteModel $database;
    protected Request $request;
    protected view $view;  //własciwosc mozemy typowac  nazwą klasy 'view'

    public static function initConfiguration(array $configuration): void
    {
        self::$configuration = $configuration; //zainicjowanie konfiguracji
    }



    //KONSTRUKTOR PRZYJMUJE ARGUMANTY I ZAPISUJE JE DO WLASCIWOSCI
    public function __construct(Request $request)
    {
        if (empty(self::$configuration['db'])) {
            throw new ConfigurationException('Configuration Error');
        }

        $this->database = new NoteModel(self::$configuration['db']);

        $this->request = $request;
        $this->view = new View(); //klasa widoku
    }


    //skupia sie na rozpoznaiu i wywolaniu metody ktora jest odpowiedzialna za dana logike akcji
    public function run(): void
    {
        try{
        $action = $this->action() . 'Action';    //"wywoluje?" createAction showAction listAction

        if (!method_exists($this, $action)) {
            $action = self::DEFAULT_ACTION . 'Action';
        }

        $this->$action();
        }catch(StorageException $e){
            $this->view->render(
                'error',
                ['message' => $e->getMessage()]
            );
        }

       
    }


    //PRZEKIEROWANIE GDY ERROR LUB STWORZONA NOTATKA
    protected function redirect(string $to, array $params): void
    {
        $location = $to;
        if (count($params)) {
            $queryParams = [];

            foreach ($params as $key => $value) {
                $queryParams[] = urlencode($key) . '=' . urlencode($value);
            }
            $queryParams = implode('&', $queryParams);
            $location .= '?' . $queryParams;
        }

        header("Location: $location");
        exit;
    }




    private function action(): string
    {
        //"pobierz dane z tego parametru (pasek url) (action) to zwroc nam self...   
        return $this->request->getParam('action', self::DEFAULT_ACTION);
    }
}
