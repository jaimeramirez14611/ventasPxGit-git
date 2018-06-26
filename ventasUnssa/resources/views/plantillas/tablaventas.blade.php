<div class="container margen-top-tabla" id="quitar">

    <div class="row margen-top">
        <div class="col-lg-1"></div>
        <div class="col-lg-10">

                    <table id="tablapb" class="table text-center">
                     
                <thead>
                <tr>
                    <th>Correlativo</th>
                    <th>Fecha y hora</th>
                    <th>Total</th>
                    <th>Acciones</th>
            
                </tr>
                </thead>
                <tbody>
                @foreach($ventas as $dato)
                <tr href= "1" >  
                 
                    <td>{{$dato->id}}</td>
                    <td>{{$dato->created_at}}</td>
                    <td>$ {{$dato->total}}</td>
                    <td>
                        <button class="btn btn-outline-success  btn-xs" onclick="mostrarDetalle({{$dato->id}})">
                            <i class="fa fa-search"></i>
                        </button>
                        <button class="btn  btn-outline-danger btn-xs" onclick="deleteVenta({{$dato->id}})">
                            <i class="fa fa-remove"></i></button>
                    </td>
                    <td>
                        
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>
             
        </div>
        <div class="col-lg-1"></div>
    </div>
    <div class="container" >
        <div class="row">
             {{$ventas->render()}}
        </div>
    </div>

   
             
</div>