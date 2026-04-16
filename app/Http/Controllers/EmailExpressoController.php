<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class EmailExpressoController extends Controller
{

    public function index(){
        return view('email.index');
    }

    public function listarEmails(){
        try {
            return ExpressoAPI::listarEmails(request()->all());
        } catch (\Throwable $e) {
            return response([$e->getMessage(),500]);
        }
    }

    public function buscarEmails(){
        try {
            return ExpressoAPI::listarEmails(request()->all());
        } catch (\Throwable $e) {
            return response([$e->getMessage(),500]);
        }
    }

    public function deletarEmail(){
        try {
            return ExpressoAPI::deletarEmail(request()->all());
        } catch (\Throwable $e) {
            return response([$e->getMessage(),500]);
        }
    }
    public function moverEmail(){
        try {
            //return request()->all();
            return ExpressoAPI::moverEmail(request()->all());
        } catch (\Throwable $e) {
            return response([$e->getMessage(),500]);
        }
    }

    public function enviarEmails(Request $request){
        try {

            
            return ExpressoAPI::enviarEmail($request);
        } catch (\Throwable $e) {
            return response([$e->getMessage(),500]);
        }
    }

    public function baixarAnexos(){

        try {
            return response()->streamDownload(function () {
                echo ExpressoAPI::baixarAnexos(request()->all());
            }, request()->input('attachmentName'),['Content-Type' => '*']);

            //return request()->all();
            return ;
        } catch (\Throwable $e) {
            return response([$e->getMessage(),500]);
        }
    }
    
}
