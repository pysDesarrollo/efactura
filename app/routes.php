<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', function()
{
	return View::make('pages.inicio');
});

Route::get('getClienteByCedula', array('uses' => 'FacturaElectronicaController@getClienteByCedula'));

Route::get('getProductById', array('uses' => 'FacturaElectronicaController@getProductById'));

Route::get('getFacturaByNumero', array('uses' => 'FacturaElectronicaController@getFacturaByNumero'));

Route::get('getNC/{id}', array('uses' => 'NotaCreditoController@generaPDF'));

Route::get('getFC/{id}', array('uses' => 'FacturaElectronicaController@generaPDF'));

Route::get('getRT/{id}', array('uses' => 'RetencionesController@generaPDF'));

Route::get('login', array('uses' => 'HomeController@showLogin'));

Route::post('login', array('uses' => 'HomeController@doLogin'));

Route::get('reportes', array('uses' => 'HomeController@doReports'));

Route::group(array('before' => 'auth'), function(){
	Route::resource('emisor', 'EmisorController');
	Route::resource('cliente', 'ClienteController');
	Route::resource('catalogo', 'CatalogoController');
	Route::resource('productos', 'ProductoController');
	Route::resource('sucursales', 'SucursalController');
	Route::resource('directorio', 'DirectorioController');
	Route::resource('secuencia-documento', 'SecuenciaDocumentoController');
	Route::resource('factura-electronica', 'FacturaElectronicaController');
	Route::resource('nota-credito', 'NotaCreditoController');
	Route::resource('retenciones', 'RetencionesController');
	Route::get('factura-intereses',  array('uses' => 'FacturaElectronicaController@facturaIntereses'));
	Route::post('factura-intereses',  array('uses' => 'FacturaElectronicaController@facturaIntereses'));
	Route::get('genera-retenciones',  array('uses' => 'RetencionesController@create'));
	Route::post('genera-retenciones',  array('uses' => 'RetencionesController@create'));
});

Route::get('logout', array('uses' => 'HomeController@doLogout'));

/*Route::get('generar', function()
{
    $html = '<html><body>';
    $html.= '<p style="color:red">Generando un sencillo pdf ';
    $html.= 'de forma realmente sencilla.</p>';
    $html.= 'http://barcode.tec-it.com/barcode-generator.aspx?group=BCGroup_1D&barcode=Code128
    $html.= '</body></html>';
    return PDF::load($html, 'A4', 'portrait')->show();
});*/