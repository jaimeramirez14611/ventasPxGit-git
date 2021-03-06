<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\View\Render;
use Validator;
use Illuminate\Validation\Rule;
use App\Venta;
use App\Ventadetalle;
use App\Producto;
class ventasController extends Controller
{
    function registroVentas(Request $request){

        $datos = $request->all();
        $messages = [
            'folder.numeric'        => '1',
            'impresiones.numeric'   => '2',
            'faster.numeric'        => '3',
            'anillados.numeric'     => '4',
            'pBond.numeric'         => '5',
            'otros.numeric'         => '6',
            'folder.max'            => '1',
            'impresiones.max'       => '2',
            'faster.max'            => '3',
            'anillados.max'         => '4',
            'pBond.max'             => '5',
            'otros.max'             => '6',
        ];

        $validator = Validator::make($datos, [
            'folder'            =>    'nullable | numeric | max:500',
            'impresiones'       =>    'nullable | numeric | max:500',
            'faster'            =>    'nullable | numeric | max:500',
            'anillados'         =>    'nullable | numeric | max:500',
            'pBond'             =>    'nullable | numeric | max:500',
            'otros'             =>    'nullable | numeric | max:500',
          ],  $messages );
         
        
              if ($validator->passes()) {
                // Guaardando el modelo
                /* Creando la venta */
                
                    $venta = new Venta();
                    $venta->save();
            

                    if(strlen($request->folder)>0){
                        $dtFolder = new VentaDetalle;
                        $dtFolder->cantidad = $request->folder;
                        $dtFolder->total =(($request->folder)*0.15);
                        $dtFolder->id_producto = 1;
                        $dtFolder->id_venta = $venta->id;
                        $venta->ventasdetalles()->save($dtFolder);
                    }

                    if(strlen($request->impresiones)>0){
                        $dtImp = new VentaDetalle;
                        $dtImp->cantidad = $request->impresiones;
                        $dtImp->total = (($request->impresiones)*0.1);
                        $dtImp->id_producto = 2;
                        $dtImp->id_venta = $venta->id;
                        $venta->ventasdetalles()->save($dtImp);
                    }
                      
                    if(strlen($request->faster)>0){
                        
                        $dtfaster = new VentaDetalle;
                        $dtfaster->cantidad = $request->faster;
                        $dtfaster->total = (($request->faster)*0.15);
                        $dtfaster->id_producto = 3;
                        $dtfaster->id_venta = $venta->id;
                        $venta->ventasdetalles()->save($dtfaster);
                   }    

                   if(strlen($request->anillados)>0){
                   
                    $dtanillado = new VentaDetalle;
                    $dtanillado->cantidad = $request->anillados;
                    $dtanillado->total = (($request->anillados) * 1);
                    $dtanillado->id_producto = 4;
                    $dtanillado->id_venta = $venta->id;
                    $venta->ventasdetalles()->save($dtanillado);
                   }    

                   if(strlen($request->pBond)>0){
                   
                    $dtpBond = new VentaDetalle;
                    $dtpBond->cantidad = $request->pBond;
                    $dtpBond->total = (($request->pBond) * 0.02);
                    $dtpBond->id_producto = 5;
                    $dtpBond->id_venta = $venta->id;
                    $venta->ventasdetalles()->save($dtpBond);
                   }    

                   
                   if(strlen($request->otros) > 0){
                   
                    $dtotros = new VentaDetalle;
                    $dtotros->cantidad = $request->otros;
                    $dtotros->total = ($request->otros) * 1;
                    $dtotros->id_producto = 6;
                    $dtotros->id_venta = $venta->id;
                    $venta->ventasdetalles()->save($dtotros);
                   } 

                 //listaVentas();
                return response()->json(['msj'=> "REGISTRO INSERTADO"]);

            
              }else{
                 return response()->json(['error'=>$validator->errors()->all()]);
              }      
    }

    function listaVentas(Request $request){
       /*Solo aparecen las ventas del día correspondiente */
       if(empty( $request->fecha1 and $request->fecha1)){
           return view('ventas.ventas');
      }else{
      //  $ventas =  Venta::whereDate('created_at', '>=', date('2018-06-1'))->whereDate('created_at', '<=', date('2018-06-30'))->paginate(2);
      
      //$ventas =  Venta::whereDate('created_at', '>=', date('2018-06-1'))->whereDate('created_at', '<=', date('2018-06-30'));
      //return view('plantillas.tablaventas', compact('ventas'));
     
            $rawVentas = DB::raw('SELECT id, created_at, total from ventas 
            where CAST(created_at AS DATE) >= "2018-06-1" and CAST(created_at AS DATE) <= "2018-06-30" 
            order by id');
            $ventas = DB::select($rawVentas);
            $cadena ='<div class="container margen-top-tabla" id="quitar">

            <div class="row margen-top">
                <div class="col-lg-1"></div>
                <div class="col-lg-10">
        
                            <table id="tablapb" class="table text-center">
                             
                        <thead>
                        <tr>
                            <th>Identificador</th> 
                            <th>Correlativo de Venta</th>
                            <th>Fecha y hora</th>
                            <th>Total</th>
                            <th>Acciones</th>
                    
                        </tr>
                        </thead>
                        <tbody>';

                        for ($i = 0; $i < count($ventas); $i++){
                 
                            $Contador = 1;
                            $indice   = $i+ 1;
                            $id = " ";
                            $row = " "; 
                             
                           foreach ($ventas[$i] as $dato ){
                              
                                if($i == $i){
            
                                        if($Contador == 1){
                                            //agrega nueva fila 
                                            
                                            $cadena .="  <tr id='". $indice."' href='". $indice."'> " ;
                                            $cadena .="<td>  ". $indice . "</td>" ;
                                            $id = $dato;
                                            $row = $indice;
                                            
                                        }
                                        
                                        //Concatenación  de varianle a cadena original
                                        
                                        $cadena .="  <td>  ". $dato . "</td>  ";

            
                                        if($Contador == 3){
                                            //cierra la fila agregada en contador = 0 
                                            $cadena .='<td> 
                                            <button class="btn btn-outline-success  btn-xs" onclick="mostrarDetalle('.$id.','.$row.')">
                                            <i class="fa fa-search"></i>
                                        </button>
                                        <button class="btn  btn-outline-danger btn-xs" onclick="deleteVenta('.$id.','.$row.')">
                                            <i class="fa fa-remove"></i></button> 
                                            </td>' ;
            
                                            //Reset a la variable contador  
                                            $Contador = 0;
                                           // $indice = 0;
                                           
                                        }
            
                                    //Contador es igual a lo que trae más 1 
                                    $Contador = $Contador + 1;
                                   
                                }
            
                            //end foreach    
                            }
                            //$indice = $indice + 1 ;
                        //end for    
                        }
                        $finalCadena ='</tr> </tbody>
                                    </table> 
                                    </div>
                                    <div class="col-lg-1"></div>
                                </div>
                                <div class="container" >
                                    <div class="row">
                                    </div>
                                </div>        
                            </div>';
                       
                         $cadenaHTML =  $cadena. $finalCadena;             
     //   return response()->json([$cadenaHTML]
           
                         return response()->json([$cadenaHTML]);
         //   return response()->json(['msj'=> $ventas]);
       }
       
      // return view('ventas.ventas', compact('ventas')); 
    }
   
    function deleteVenta(Request $request){
        $detalle =  Ventadetalle::where("id_venta","=", $request->valor);
        $detalle->delete();
        $venta = Venta::where("id","=", $request->valor); 
        $venta->delete();
        return response()->json(['msj'=> "Registro eliminado"]);
    }

    function detalleVenta(Request $request){
        $rawProductos = DB::raw("select (v.id_venta) as c_venta,(p.nombre) as p_nombre, (v.cantidad) as u_vendida, (p.precio) as pre_unitario  
        from ventadetalles v, productos p
        where id_venta =".$request->valor."  
        and v.id_producto = p.id
        ORDER BY p.nombre");
        try{
            $detalle = DB::select($rawProductos);
            $cadena ='<div class="container margen-top-tabla"> <div class="row margen-top">
                <div class="col-lg-1"></div> <div class="col-lg-10">
                 <table id="tabla" class="table text-center">
                <thead> <tr>
                            <th>Identificador</th>
                            <th>Fila</th>
                            <th>Producto</th>
                            <th>U/V</th>
                            <th>P/U</th>
                            <th id="th-action">Editar</th>
                            <th>Eliminar</th>
                        </tr></thead><tbody>
                        ';
           
            for ($i = 0; $i < count($detalle); $i++){
                 
                $Contador = 1;
                $indice   = $i+ 1; 
                 
                foreach ($detalle[$i] as $dato ){
                  
                    if($i == $i){

                            if($Contador == 1){
                                //agrega nueva fila 
                                $cadena .="  <tr id='". $indice."' href='". $indice."'> " ;
                               
                            }
                            
                            //Concatenación  de varianle a cadena original
                             if($Contador == 1){
                                $cadena .="  <td>  ". $dato . "</td>  ";
                                $cadena .="  <td>  ". $request->fila."</td>  ";
                             }else{
                                $cadena .="  <td>  ". $dato . "</td>  ";
                             }                          
                            

                            if($Contador == 4){
                                //cierra la fila agregada en contador = 0 
                                $cadena .=" <td>
                                <button id='btn-edit' class='btn btn-outline-success  btn-xs' onclick=''>
                                    <i class='fa fa-edit'></i>
                                </button> </td>
                                <td>
                                <button id='btn-delete' class='btn  btn-outline-danger btn-xs'>
                                    <i class='fa fa-remove'></i></button>
                            </td>  </tr> " ;

                                //Reset a la variable contador  
                                $Contador = 0;
                               // $indice = 0;
                               
                            }

                        //Contador es igual a lo que trae más 1 
                        $Contador = $Contador + 1;
                       
                    }

                //end foreach    
                }
                //$indice = $indice + 1 ;
            //end for    
            }

            $finalCadena = '</tbody> </table> </div> <div class="col-lg-1"></div> </div>
                           <div class="container" > <div class="row"> </div> </div> </div>'; 
             
            $cadenaHTML =  $cadena. $finalCadena;             
            return response()->json([$cadenaHTML]);
        } catch(\Illuminate\Database\QueryException $e){
            return response()->json(['msj'=> $e]);
        }
       
      //  return view('ventas.ventas')->with("ventas", $venta);
         
     }
    
     function deleteDetalle(Request $request){
        $raw = DB::raw('select id from productos where nombre = "'.$request->producto.'";'); 
        $Producto = DB::select($raw);
        $id_producto = 0;

       for ($i = 0; $i < count($Producto); $i++){
            foreach($Producto[$i] as $valor){
                $id_producto = $valor;
            }
        }
        $eliminarDetalle =  Ventadetalle::where("id_venta","=", $request->venta)->where("id_producto","=", $id_producto);
        $eliminarDetalle->delete();
        
        $rawVentaVrf = DB::raw("select total from ventas where id =".$request->venta.";");
        $venta = DB::select($rawVentaVrf);
       
        for ($i = 0; $i < count($venta); $i++){
            foreach($venta[$i] as $valor){
                $total = $valor;
            }
        }
        
        if($total == NULL){
            $rawValidate = DB::raw('UPDATE ventas set total = 0 WHERE id ='.$request->venta.'  and ISNULL(total) != 0  ;'); 
            $venta = DB::update($rawValidate);
        }else{ }

        return response()->json(['msj'=>"REGISTROS ELIMINADO"]);  
       
    }
    
   function buscarVentas(Request $request){
        
    }

    function updateVentas(Request $request){

        $datos = $request->all();

        $messages = [
            'CantidadProducto.numeric'        => 'Por favor ingrese datos númericos',
            'CantidadProducto.max'            => 'El máximo número que puede ingresar es 500',
        ];

        $validator = Validator::make($datos, [
            'CantidadProducto'            =>    'numeric | max:500',
          ],  $messages );
          if ($validator->passes()) {
              $rawProductos = DB::raw("update ventadetalles SET cantidad = ".$request->CantidadProducto." ,total = ( ".$request->CantidadProducto." * (select precio from productos where nombre = '".$request->Producto."')) where id_producto = (select id from productos where nombre = '".$request->Producto."') and id_venta = ".$request->Venta."");  
              $detalle = DB::update($rawProductos);
              return response()->json(['msj'=>"Datos actualizados correctamente"]); 
          }else{
            return response()->json(['error'=>$validator->errors()->all()]);
         }  
      
    }
}
 
    


