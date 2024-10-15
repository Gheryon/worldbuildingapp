<?php

namespace App\Http\Controllers;

use App\Models\articulo;
use App\Models\imagen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ImagenController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    $imagenes = imagen::all();
    return view('galeria.index', compact('imagenes'));
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create()
  {
    //
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request)
  {
    $request->validate([
      'nombre' => 'required|max:255',
      'imagen' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    ]);

    $imageFile = $request->file('imagen');
    $filename = time() . '.' . $imageFile->getClientOriginalExtension();

    // Save the image file to public storage
    $path = $imageFile->storeAs('imagenes', $filename, 'public');

    // Optionally, you can use Intervention Image to manipulate the image
    // $image = ImageFacade::make($imageFile)->resize(800, null, function ($constraint) {
    //     $constraint->aspectRatio();
    // })->save(public_path('storage/images/' . $filename));

    // Create a new image record using Eloquent
    imagen::create([
      'nombre' => basename($path),
      //'filename' => $filename
      'path'=> $path,
      'owner'=>$request->owner,
      'table_owner'=>$request->table_owner,
    ]);
    
    /*
    $imagen=new imagen();
    $imagen->nombre=$request->nombre;

    $imagen->save();*/

    // Redirect back to the same page with a success message
    //return back()->with('success', 'Image uploaded successfully!');
    return redirect()->route('galeria.index');
  }

  /**
   * Store a new resource from summernote in storage.
   */
  public function store_for_summernote($content, string $table_owner, int $id_owner)
  {
    $dom = new \DomDocument();
    $searchPage = mb_convert_encoding($content, 'HTML-ENTITIES', "UTF-8"); //necesario para mantener las tildes en el texto
    $dom->loadHtml($searchPage, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
    $imageFile = $dom->getElementsByTagName('img');
    
    foreach ($imageFile as $item => $image) {
      $data = $image->getAttribute('src');
      list($type, $data) = explode(';', $data);
      list(, $data)      = explode(',', $data);
      $imgeData = base64_decode($data);
      $image_name= time().rand(0, 1234567890).$item.'.png';
      $path = public_path() . "/storage/imagenes/" . $image_name;
      file_put_contents($path, $imgeData);
            
      $image->removeAttribute('src');
      $image->setAttribute('src', asset("/storage/imagenes/" . $image_name));
      
      $imagen=new imagen();
      $imagen->owner=$id_owner;
      $imagen->table_owner=$table_owner;
      $imagen->nombre=$image_name;
      $imagen->path=asset("/storage/imagenes/" . $image_name);
      $imagen->save();
    }

    $content = $dom->saveHTML();
    return $content;
  }

  /**
   * Update a resource from summernote in storage.
   */
  public function update_for_summernote($content, string $table_owner, int $id_owner=0)
  {
    $dom = new \DomDocument();
    libxml_use_internal_errors(true);
    $searchPage = mb_convert_encoding($content, 'HTML-ENTITIES', "UTF-8"); //necesario para mantener las tildes en el texto
    $dom->loadHtml($searchPage, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD | libxml_use_internal_errors(true));
    $imageFile = $dom->getElementsByTagName('img');
    
    //no se borran las imagenes antiguas en caso de existir, esto se hace desde otra función
    foreach ($imageFile as $item => $image) {
      $data = $image->getAttribute('src');
      if (strpos($data, ';') === false) {
        continue;
      }
      list($type, $data) = explode(';', $data);
      list(, $data)      = explode(',', $data);
      $imgeData = base64_decode($data);
      $image_name= time().rand(0, 1234567890).$item.'.png';
      $path = public_path() . "/storage/imagenes/" . $image_name;
      file_put_contents($path, $imgeData);
            
      $image->removeAttribute('src');
      $image->setAttribute('src', asset("/storage/imagenes/" . $image_name));
      
      $imagen=new imagen();
      $imagen->owner=$id_owner;
      $imagen->table_owner=$table_owner;
      $imagen->nombre=$image_name;
      $imagen->path=asset("/storage/imagenes/" . $image_name);
      $imagen->save();
    }

    $content = $dom->saveHTML();
    return $content;
  }

  /**
   * Elimina obtiene los id de los artículos de la tabla imágenes, analiza aquellos presentes y borra imágenes que no se usan en ninguno.
   */
  public function limpiar_imagenes(string $table_owner="a", int $id_owner=0)
  {
    //id_articulos contiene el id de los articulos que contienen alguna imagen
    $id_articulos = DB::table('imagenes')->select('owner')->where('table_owner', '=', 'articulos')->distinct()->get();
//var_dump($id_articulos);
    $imagenes = DB::table('imagenes')
                  ->select('id', 'path')
                  ->where('table_owner', '=', 'articulos')->get();

    foreach ($id_articulos as $id_articulo) {
    //var_dump($id_articulo->owner);
      $articulo = DB::table('articulosgenericos')
                  ->select('contenido')
                  ->where('id_articulo', '=', $id_articulo->owner)->get();
      //var_dump($articulo);

      $dom = new \DomDocument();
      libxml_use_internal_errors(true);
      $dom->loadHtml($articulo, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD | libxml_use_internal_errors(true));
      $imageFile = $dom->getElementsByTagName('img');
    
      foreach ($imageFile as $item => $image) {
        $data = $image->getAttribute('src');
        //echo $data;
        var_dump($imagenes);
        if($imagenes->contains('path', $data)){
          echo "encontrado";
        }
        
      }

    }
    foreach ($imagenes as $imagen) {
      //if (file_exists(public_path("/storage/imagenes/" . $imagen->nombre))) {
        //unlink(public_path("/storage/imagenes/" . $imagen->nombre));
        //Storage::delete(asset($imagen->nombre));
      //}
      //imagen::destroy($imagen->id);
    }
  }

  /**
   * Display the specified resource.
   */
  public function show(imagen $imagen)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(imagen $imagen)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, imagen $imagen)
  {
    //
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(imagen $imagen)
  {
    //
  }
}
