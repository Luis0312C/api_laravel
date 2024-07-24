<?php

namespace App\Http\Controllers;

use App\Models\Documento;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DocumentoController extends Controller
{
    public function index()
    {
        $documentos = Documento::getDocumentosSinSubcategoria();

        $data = [
            "documento_url" => $documentos->map(function ($documento) {
                return [
                    "titulo" => $documento->get_titulo(),
                    "documento_url" => $documento->get_url(),
                    "categoria_id" => $documento->get_categoria(),
                    "subcategoria_id" => $documento->get_subcategoria(),
                ];
            })
        ];

        return response()->json($data);
    }

    public function subcategoria()
    {
        $documentos = Documento::getDocumentosConSubcategoria();

        $data = [
            "documento_url" => $documentos->map(function ($documento) {
                return [
                    "titulo" => $documento->get_titulo(),
                    "documento_url" => $documento->get_url(),
                    "categoria_id" => $documento->get_categoria(),
                    "subcategoria_id" => $documento->get_subcategoria(),
                ];
            })
        ];

        return response()->json($data);
    }
}
