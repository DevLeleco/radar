<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('radar.cliente');
});

/********************************************* Con_Users*********************************************/
Route::get('/usuario','radar\Con_Usuario@form_usuario')->name('rt.usuario');
Route::post('/usuario/add','radar\Con_Usuario@add_usuario')->name('rt.add_usuario');
Route::post('/usuario/edit','radar\Con_Usuario@edit_usuario')->name('rt.edit_usuario');
Route::get('/usuario/edit/{id_usuario}','radar\Con_Usuario@ver_usuario')->name('rt.ver_usuario');
Route::get('/usuario/delete/{id_usuario}','radar\Con_Usuario@delete_usuario')->name('rt.delete_usuario');
Route::get('/usuario/reset/{id_usuario}','radar\Con_Usuario@reset_usuario')->name('rt.reset_usuario');
/********************************************* Con_Chamados *********************************************/
Route::get('/chamados','radar\Con_Chamados@form_chamados')->name('rt.chamados');
Route::post('/chamados/add','radar\Con_Chamados@add_chamados')->name('rt.add_chamados');
Route::post('/chamados/edit','radar\Con_Chamados@edit_chamados')->name('rt.edit_chamados');
Route::get('/chamados/edit/{id_chamados}','radar\Con_Chamados@ver_chamados')->name('rt.ver_chamados');
Route::get('/chamados/delete/{id_chamados}','radar\Con_Chamados@delete_chamados')->name('rt.delete_chamados');
Route::get('/chamados/historico','radar\Con_Chamados@historico_chamados')->name('rt.historico_chamados');
Route::get('/chamados/tec_chamados','radar\Con_Chamados@tec_chamados')->name('rt.tec_chamados');
Route::get('/chamados/view/{id_chamados}','radar\Con_Chamados@view_chamados')->name('rt.view_chamados');
Route::post('/chamados/close','radar\Con_Chamados@close_chamados')->name('rt.close_chamados');
/********************************************* Con_Cliente *********************************************/
Route::get('/cliente','radar\Con_Cliente@form_cliente')->name('rt.cliente');
Route::post('/cliente/add','radar\Con_Cliente@add_cliente')->name('rt.add_cliente');
Route::post('/cliente/edit','radar\Con_Cliente@edit_cliente')->name('rt.edit_cliente');
Route::get('/cliente/edit/{id_cliente}','radar\Con_Cliente@ver_cliente')->name('rt.ver_cliente');
Route::get('/cliente/delete/{id_cliente}','radar\Con_Cliente@delete_cliente')->name('rt.delete_cliente');

/********************************************* Con_Equipamento *********************************************/
Route::get('/equipamento','radar\Con_Equipamento@form_equipamento')->name('rt.equipamento');
Route::post('/equipamento/add','radar\Con_Equipamento@add_equipamento')->name('rt.add_equipamento');
Route::post('/equipamento/edit','radar\Con_Equipamento@edit_equipamento')->name('rt.edit_equipamento');
Route::post('/equipamento/edit/unidade','radar\Con_Equipamento@select_unidade')->name('rt.select_unidade'); // ajax 
Route::get('/equipamento/edit/{id_equipamento}','radar\Con_Equipamento@ver_equipamento')->name('rt.ver_equipamento');
Route::get('/equipamento/delete/{id_equipamento}','radar\Con_Equipamento@delete_equipamento')->name('rt.delete_equipamento');

/********************************************* Con_OS *********************************************/
Route::get('/os','radar\Con_OS@form_os')->name('rt.os');
Route::post('/os/add','radar\Con_OS@add_os')->name('rt.add_os');
Route::post('/os/edit','radar\Con_OS@edit_os')->name('rt.edit_os');
Route::get('/os/edit/{id_os}','radar\Con_OS@ver_os')->name('rt.ver_os');
Route::get('/os/delete/{id_os}','radar\Con_OS@delete_os')->name('rt.delete_os');

/********************************************* Con_Tecnico *********************************************/
Route::get('/tecnico','radar\Con_Tecnico@form_tecnico')->name('rt.tecnico');
Route::post('/tecnico/add','radar\Con_Tecnico@add_tecnico')->name('rt.add_tecnico');
Route::post('/tecnico/edit','radar\Con_Tecnico@edit_tecnico')->name('rt.edit_tecnico');
Route::get('/tecnico/edit/{id_tecnico}','radar\Con_Tecnico@ver_tecnico')->name('rt.ver_tecnico');
Route::get('/tecnico/delete/{id_tecnico}','radar\Con_Tecnico@delete_tecnico')->name('rt.delete_tecnico');

/********************************************* Con_Unidade *********************************************/
Route::get('/unidade','radar\Con_Unidade@form_unidade')->name('rt.unidade');
Route::post('/unidade/add','radar\Con_Unidade@add_unidade')->name('rt.add_unidade');
Route::post('/unidade/edit','radar\Con_Unidade@edit_unidade')->name('rt.edit_unidade');
Route::post('/unidade/edit/equipamento','radar\Con_Unidade@select_equipamento')->name('rt.select_equipamento'); // ajax 
Route::get('/unidade/edit/{id_unidade}','radar\Con_Unidade@ver_unidade')->name('rt.ver_unidade');
Route::get('/unidade/delete/{id_unidade}','radar\Con_Unidade@delete_unidade')->name('rt.delete_unidade');

/********************************************* Con_QR *********************************************/
Route::get('/qr','radar\Con_QR@form_qr')->name('rt.qr');
Route::post('/qr/view','radar\Con_QR@view_qr')->name('rt.view_qr');
