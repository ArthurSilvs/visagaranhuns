<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Cnae;
use App\Area;
use Illuminate\Support\Facades\Crypt;

class CnaeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // // Listagem de cnaes
        $cnaes = Cnae::where('areas_id','=',Crypt::decrypt($request->value))->orderBy('descricao', 'ASC')->paginate(50);
        return view('coordenador/cnaes_coordenador', ['cnaes' => $cnaes]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Redireciona para página de cadastro de cnae, junto das areas já cadastradas
        $areas = Area::all();
        return view('cnae.cadastro', ['areas' => $areas]);
    }

    public function busca()
    {
        $termo = $request->pesquisa;
        $cnae = Cnae::whereRaw('unaccent(nome) ilike unaccent(\'%' . $termo . '%\')')->select('id')->get();
        $usuarios = \App\User::whereRaw('unaccent(name) ilike unaccent(\'%' . $term. '%\') AND tipo like \'ESTABELECIMENTO\'')->select('id')->get();

        $cidade = (Session::has('cidade'))?Session::get('cidade'):'Garanhuns';

        $lista = \App\Estabelecimento::
            whereIn("modalidade_id", $categorias)
            ->where('status', 'Aprovado')
            ->orWhereIn("user_id", $usuarios)
            ->where('status', 'Aprovado')->get();

        $estabelecimentos = array();
        foreach($lista as $estabelecimento) {
            if($estabelecimento->endereco->cidade == $cidade) {
                $estabelecimentos[] = $estabelecimento;
            }
        }


       // DB::enableQueryLog();
        //if(!Session::has('cidade'))
        //    Session::put('cidade', 'Garanhuns');
        /*$estabelecimentos = \App\Estabelecimento::
            whereIn("modalidade_id", $categorias)
            ->where('status', 'Aprovado')
            ->orWhereIn("user_id", $usuarios)
            ->where('status', 'Aprovado')
            ->join('enderecos', function ($join) {
                $join->on('enderecos.id', '=', 'estabelecimentos.endereco_id')
                ->where('cidade', 'ilike', Session::get('cidade'));
            })
            ->get();*/
        //dd(DB::getQueryLog());
        return view("categoria.show")->with(['estabelecimentos' => $estabelecimentos]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = $request->validate([
            'codigo'    => 'required|string',
            'descricao' => 'required|string',
            'areas_id'  => 'required|integer',
        ]);

        $cnae = Cnae::create([
            'codigo'    => $request->codigo,
            'descricao' => $request->codigo,
            'areas_id'  => $request->codigo,
        ]);

        return view('coordenador.home_coordenador');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
