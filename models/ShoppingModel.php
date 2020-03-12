<?php

defined('EXECG__') or die('<h1>404 - <strong>Not Found</strong></h1>');
require ('classes/Producto.php');
require ('classes/detalleVenta.php');

class ShoppingModel extends ModelBase {

    public function productosByCategoria($categoria, $orden, $pag) {
        $ordenamiento=array("nombreasc"=>"ORDER BY p.nombreproducto asc",
            "nombredesc"=>"ORDER BY p.nombreproducto desc",
            "precioasc"=>"ORDER BY p.precio asc",
            "preciodesc"=>"ORDER BY p.precio desc");
        $linecategory=$categoria=="TODAS"?"where p.estado='activo'":"where p.idcategoria=$categoria
            and p.estado='activo'";
        $limit = 16;
        $offset = ($pag - 1) * $limit;     
        
        $consulta = $this->db->executeQue("select * from productos p 
                $linecategory                
                and p.referencia<>'LICINS'");
        $total = $this->db->numRows($consulta);
        
        $consulta2 = $this->db->executeQue("select * 
                from productos p left join imagenes i on i.idimagen=p.idimagen
                $linecategory
                and p.referencia<>'LICINS'                
                {$ordenamiento[$orden]}
                LIMIT $limit OFFSET $offset");    
        $numproducto=1;
        while ($row = $this->db->arrayResult($consulta2)) {                                
            $productos[$numproducto] = array("id"=>$row['idproducto'], 
                                "nombre"=>$row['nombreproducto'], 
                                "precio"=>$row['precio'], 
                                "puntos"=>$row['puntos'],
                                "referencia"=>$row['referencia'], 
                                "unidad"=>$row['unidadmedida'], 
                                "iva"=>$row['iva'], 
                                "precioiva"=>(($row['precio']*$row['iva'])/100)+$row['precio'],
                                "imagen"=>$row['url']);
            $numproducto++;
        }   
        return $productos;
    }
    
    public function paginacionProductos($categoria, $orden, $pag) {
       $linecategory=$categoria=="TODAS"?"where p.estado='activo'":"where p.idcategoria=$categoria
            and p.estado='activo'";
        $limit = 16;
        $offset = ($pag - 1) * $limit;     
        
        $consulta = $this->db->executeQue("select * from productos p 
                $linecategory                
                and p.referencia<>'LICINS'");
        $total = $this->db->numRows($consulta);        
        $totalPag = ceil($total / $limit);     
        return array($totalPag,$total);
       
    }

    public function productosByFilter($filtro, $orden, $pag) {
        $ordenamiento=array("nombreasc"=>"ORDER BY p.nombreproducto asc",
            "nombredesc"=>"ORDER BY p.nombreproducto desc",
            "precioasc"=>"ORDER BY p.precio asc",
            "preciodesc"=>"ORDER BY p.precio desc");        
        $limit = 16;
        $offset = ($pag - 1) * $limit;     
        
        $consulta = $this->db->executeQue("select * from productos p 
                where p.estado='activo'
                and p.referencia<>'LICINS' 
                and p.nombreproducto LIKE '%$filtro%'");
        $total = $this->db->numRows($consulta);
        
        $consulta2 = $this->db->executeQue("select * 
                from productos p left join imagenes i on i.idimagen=p.idimagen
                where p.estado='activo'
                and p.referencia<>'LICINS' 
                and p.nombreproducto LIKE '%$filtro%'
                {$ordenamiento[$orden]}
                LIMIT $limit OFFSET $offset");    
        $numproducto=1;
        while ($row = $this->db->arrayResult($consulta2)) {                                
            $productos[$numproducto] = array("id"=>$row['idproducto'], 
                                "nombre"=>$row['nombreproducto'], 
                                "precio"=>$row['precio'], 
                                "puntos"=>$row['puntos'],
                                "referencia"=>$row['referencia'], 
                                "unidad"=>$row['unidadmedida'], 
                                "iva"=>$row['iva'], 
                                "precioiva"=>(($row['precio']*$row['iva'])/100)+$row['precio'],
                                "imagen"=>$row['url']);
            $numproducto++;
        }   
        return $productos;
    }
    
    public function paginacionProductosFilter($filtro, $orden, $pag) {      
        $limit = 16;
        $offset = ($pag - 1) * $limit;     
        
        $consulta = $this->db->executeQue("select * from productos p 
                where p.estado='activo'
                and p.referencia<>'LICINS' 
                and p.nombreproducto LIKE '%$filtro%'");
        $total = $this->db->numRows($consulta);        
        $totalPag = ceil($total / $limit);     
        return array($totalPag,$total);
       
    }        

    public function categorias() {        
        $consulta = $this->db->executeQue("select * from categoriasp order by nombrecategoria asc");           
        while ($row = $this->db->arrayResult($consulta)) {
            $categorias[$row['idcategoria']] = $row['nombrecategoria'];
        }       
        return $categorias;
    }   

    public function traerDetalles() {
        $_SESSION["itemsenordenuptodate"]=null;
        $consulta = $this->db->executeQue("select * from productos");
        while($row = $this->db->arrayResult($consulta)){           
            $productos[$row['idproducto']] = array("nombre"=>$row['nombreproducto'], 
                            "precio"=>$row['precio'], 
                            "puntos"=>$row['puntos'],
                            "referencia"=>$row['referencia'], 
                            "unidad"=>$row['unidadmedida'], 
                            "iva"=>$row['iva'], 
                            "precioiva"=>(($row['precio']*$row['iva'])/100)+$row['precio']);
        }            
        
        foreach($_SESSION["canasta"] as $key => $value){
            $idverify = strrev(urlencode(base64_encode($key)));
            $idid = sha1($key);
            $productos[$key]["cantidad"]=$value;
            $productos[$key]["verify"]=$idverify;
            $productos[$key]["dell"]=$idid;
            $items[$key]=$productos[$key];
        }
        $_SESSION["itemsenordenuptodate"]=$items;
        return $items;
    }
    
    public function traerTotales($detalles){
        $totaliva=0;
        $subtotal=0;
        $totalpuntos=0;
        $total=0;
        
        foreach($detalles as $value){                   
            $subtotal=$subtotal+($value["precio"]*$value["cantidad"]);      
            $totaliva=$totaliva+((($value["precio"]*$value["iva"])/100)*$value["cantidad"]); 
            $total=$total+($value["precioiva"]*$value["cantidad"]); 
            $totalpuntos=$totalpuntos+($value["puntos"]*$value["cantidad"]);      
        }
        return array("subtotal"=>$subtotal,
            "puntos"=>$totalpuntos,
            "iva"=>$totaliva,
            "total"=>$total);
    }
    
    public function actualizarTotales($detalles){
        $totaliva=0;
        $subtotal=0;
        $totalpuntos=0;
        $total=0;
        
        foreach($detalles as $value){                   
            $subtotal=$subtotal+($value["precio"]*$value["cantidad"]);      
            $totaliva=$totaliva+((($value["precio"]*$value["iva"])/100)*$value["cantidad"]); 
            $total=$total+($value["precioiva"]*$value["cantidad"]); 
            $totalpuntos=$totalpuntos+($value["puntos"]*$value["cantidad"]);      
        }
        return array("subtotal"=>$subtotal,
            "puntos"=>$totalpuntos,
            "iva"=>$totaliva,
            "total"=>$total);
    }
    
    public function traerInfoUsuario(){
        $usuario = unserialize($_SESSION['user']);        
        $consulta = $this->db->executeQue("select u.nombreusuario, u.cedula, u.email,
                (select sum(v.puntos_venta) from ventas v where v.idusuario={$usuario->getIdUser()} and v.idperiodo={$this->getCurrentPeriodo()}) as puntosperiodo,
                (select np.nombreperiodo from periodos np where np.idperiodo={$this->getCurrentPeriodo()}) as periodo
                from usuarios u where u.idusuario={$usuario->getIdUser()}");
        $row = $this->db->arrayResult($consulta);           
        $usuario= array("nombre"=>$row["nombreusuario"],
            "cedula"=>$row["cedula"],
            "email"=>$row["email"],
            "periodo"=>$row["periodo"],
            "puntos"=>$row["puntosperiodo"]);
        
        return $usuario;
    }  

    public function deleteItemShop(){
         if (isset($_POST["verify"])) {
            $idpro = base64_decode(urldecode(strrev($_POST["verify"])));
            unset($_SESSION['facturacompra'][$idpro]);
            if (!isset($_SESSION['facturacompra'][$idpro])) {
                unset($_SESSION["itemsenordenuptodate"][$idpro]);
                unset($_SESSION["canasta"][$idpro]);
                $totales = $this->actualizarTotales($_SESSION["itemsenordenuptodate"]);
                $respuesta['res'] = 'si';
                $respuesta['idrow'] = $idpro;
                $respuesta['iva'] = number_format($totales["iva"],0,",",".");
                $respuesta['subtotal'] = number_format($totales["subtotal"],0,",",".");
                $respuesta['total'] = number_format($totales["total"],0,",",".");
                $respuesta['totalpuntos'] = number_format($totales["puntos"],2,",",".");                                              
                echo json_encode($respuesta);
            } else {
                $respuesta['res'] = 'no';
                echo json_encode($respuesta);
            }
        }
    }
       
    public function crearFactura($idUser, $details, $totales) {
        $tipoenvio = $_GET['envio'];
        $fecha = date("Y-m-d");
        $puntoscompra = $totales["puntos"];
        $valorcompra = $totales["total"];
        $comprador = $idUser;        
        $detail = $details;
        $periodo=$this->getCurrentPeriodo();
        $idquery = "select nextval('ventas_idventa_seq'::regclass) limit 1";
        $consult = $this->db->executeQue($idquery);               
        $row = $this->db->arrayResult($consult);
        $idventa = $row['nextval'];          
        $_SESSION["pdfinfo"]["archivo"]="OrdenDePedido000". $idventa . time() . ".pdf";
        $url=TEMPORALES.DS.$_SESSION["pdfinfo"]["archivo"];
        $query = "insert into ventas values($idventa,$comprador,'$fecha','espera',$puntoscompra,$valorcompra,'$tipoenvio',$periodo,'$url')";
        $this->db->executeQue($query); 
        foreach ($detail as $key=>$detalle) {
            $cantidad = $detalle["cantidad"];
            $idprod = $key;
            $precio = $detalle["precioiva"];
            $puntos = $detalle["puntos"];
            //$puntos = $this->config->get('pointvalue');
            $query3 = "insert into detalleventas values(nextval('detalleventas_iddetalleventa_seq'::regclass),$idprod,$idventa,$cantidad,$precio,NULL,NULL,NULL,NULL,NULL,$puntos, NULL)";
            $this->db->executeQue($query3); 
        }                
        unset($_SESSION['canasta']);
        if ($idventa < 10) {
            $idventa = '0000' . $idventa;
        } else if ($idventa < 100 && $idventa >= 10) {
            $idventa = '000' . $idventa;
        } else if ($idventa < 1000 && $idventa >= 100) {
            $idventa = '00' . $idventa;
        } else if ($idventa < 10000 && $idventa >= 1000) {
            $idventa = '0' . $idventa;
        } else {
            $idventa = $idventa;
        }
        return $idventa;
    }

    public function getCurrentPeriodo() {
        $query = "select * from periodos where '" . date("Y-m-d") . "' BETWEEN fechainicio AND fechafin";
        $consulta = $this->db->executeQue($query);
        $idperiodo = 0;
        while ($row = $this->db->arrayResult($consulta)) {
            $idperiodo = $row['idperiodo'];
        }
        return $idperiodo;
    }

    public function getNameCurrentPeriodo() {
        $query = "select * from periodos where '" . date("Y-m-d") . "' BETWEEN fechainicio AND fechafin";
        $consulta = $this->db->executeQue($query);
        $nombreperiodo = '';
        while ($row = $this->db->arrayResult($consulta)) {
            $nombreperiodo = $row['nombreperiodo'];
        }
        return $nombreperiodo;
    }
    
    public function getMes() {
        $mes = date("m");
        if ($mes == 01) {
            return "Enero";
        } else if ($mes == 02) {
            return "Febrero";
        } else if ($mes == 03) {
            return "Marzo";
        } else if ($mes == 04) {
            return "Abril";
        } else if ($mes == 05) {
            return "Mayo";
        } else if ($mes == 06) {
            return "Junio";
        } else if ($mes == 07) {
            return "Julio";
        } else if ($mes == 08) {
            return "Agosto";
        } else if ($mes == 09) {
            return "Septiembre";
        } else if ($mes == 10) {
            return "Octubre";
        } else if ($mes == 11) {
            return "Noviembre";
        } else if ($mes == 12) {
            return "Diciembre";
        }
    }

    public function getEnvios() {
        $envios = array("Domicilio", "Punto de Venta");
        return $envios;
    }

    public function agregarItems($idProducto, $cantidadd) {
        session_start();
        $producto = null;
        $item = $idProducto;
        $cantidad = $cantidadd;
        $itemsEnCesta = $_SESSION['itemsEnCesta'];
        $consulta = $this->db->executeQue("select * from productos where idproducto=$item");
        $total = $this->db->numRows($consulta);
        if ($total > 0) {
            while ($row = $this->db->arrayResult($consulta)) {
                $producto = new Producto($row['idproducto'], $row['idcategoria'], $row['nombreproducto'],
                                $row['precio'], $row['puntos'], $row['referencia'], $row['iva'],
                                $row['stock'], null);
            }
        }
        if ($producto->getIdCategoria() == 14 || $producto->getIdCategoria() == 8) {
            $detalle = new Detalle($producto, $cantidad);
        } else {
            $detalle = new Detalle($producto, (int) $cantidad);
        }
        if (!isset($itemsEnCesta)) {
            $itemsEnCesta[$producto->getId()] = serialize($detalle);
        } else {
            foreach ($itemsEnCesta as $k => $v) {
                $encontrado = 0;
                if ($producto->getId() == $k) {
                    $detalle1 = unserialize($v);
                    if ($producto->getIdCategoria() == 14 || $producto->getIdCategoria() == 8) {
                        $detalle2 = new Detalle($producto, $cantidad + $detalle1->getCantidad());
                    } else {
                        $detalle2 = new Detalle($producto, ((int) $cantidad) + $detalle1->getCantidad());
                    }
                    $itemsEnCesta[$producto->getId()] = serialize($detalle2);
                    $encontrado = 1;
                }
                if ($encontrado == 0) {
                    $itemsEnCesta[$producto->getId()] = serialize($detalle);
                }
            }
        }
        $_SESSION['itemsEnCesta'] = $itemsEnCesta;
    }

    public function cambiafecha($fecha) {
        ereg("([0-9]{2,4})-([0-9]{1,2})-([0-9]{1,2})", $fecha, $mifecha);
        $lafecha = $mifecha[3] . "/" . $mifecha[2] . "/" . $mifecha[1];
        return $lafecha;
    }

    public function formatoNfactura($numerofactura) {
        if ($numerofactura < 10) {
            return "00000" . $numerofactura;
        } else if ($numerofactura < 100) {
            return "0000" . $numerofactura;
        } else if ($numerofactura < 1000) {
            return "000" . $numerofactura;
        } else if ($numerofactura < 10000) {
            return "00" . $numerofactura;
        } else if ($numerofactura < 100000) {
            return "0" . $numerofactura;
        } else if ($numerofactura < 1000000) {
            return $numerofactura;
        }
    }

    public function refrescarItem($valor, $productoitem) {
        session_start();
        $producto = null;
        $item = $productoitem;
        $cantidad = $valor;
        $itemsEnCesta = $_SESSION['itemsEnCesta'];
        $consulta = $this->db->executeQue("select * from productos where idproducto=$item");
        $total = $this->db->numRows($consulta);
        if ($total > 0) {
            while ($row = $this->db->arrayResult($consulta)) {
                $producto = new Producto($row['idproducto'], $row['idcategoria'], $row['nombreproducto'],
                                $row['precio'], $row['puntos'], $row['referencia'], $row['iva'],
                                $row['stock'], null);
            }
        }

        if ($producto->getIdCategoria() == 14 || $producto->getIdCategoria() == 8) {
            $detalle2 = new Detalle($producto, $cantidad);
        } else {
            $detalle2 = new Detalle($producto, ((int) $cantidad));
        }
        $itemsEnCesta[$producto->getId()] = serialize($detalle2);
        $encontrado = 1;


        $_SESSION['itemsEnCesta'] = $itemsEnCesta;
    }

    public function traerDetalle($idpro) {
        $detalle = null;
        if (!isset($_SESSION['itemsEnCesta'])) {
            return null;
        } else {
            $itemsEnCesta = $_SESSION['itemsEnCesta'];
            foreach ($itemsEnCesta as $k => $v) {
                if ($k == $idpro) {
                    $detalle = unserialize($v);
                }
            }
            return $detalle;
        }
    }

    public function createAndSavePdf($info) {
        $pdf = new PDFCONTRACT();
        $pdf->setInfo($this->config->get('nit'), $this->config->get('direccion'), $this->config->get('telefono'));
        $pdf->SetDisplayMode('real');
        $pdf->SetMargins(15, 0);
        $pdf->SetAuthor($this->config->get('company'));
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(50, 6, "Orden  de pedido No.", 'LTRB', 0, 'L', false);
        $pdf->Cell(130, 6, $info["numeroorden"], 'LTRB', 0, 'L', false);
        $pdf->Ln();
        $pdf->Cell(50, 6, "Fecha", 'LTRB', 0, 'L', false);
        $pdf->Cell(130, 6, $info["fecha"], 'LTRB', 0, 'L', false);
        $pdf->Ln();
        $pdf->Cell(50, 6, "Nombre del usuario", 'LTRB', 0, 'L', false);
        $pdf->Cell(130, 6, $info["nombre"], 'LTRB', 0, 'L', false);
        $pdf->Ln();
        $pdf->Cell(50, 6, "Codigo del usuario", 'LTRB', 0, 'L', false);
        $pdf->Cell(130, 6, $info["codigo"], 'LTRB', 0, 'L', false);
        $pdf->Ln(10);
        $header = array("Cantidad", "Producto", "Codigo", "Precio unitario", "Precio Total");
        $pdf->FancyTable($header, $info["detalle"]);           
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->Cell(100, 6, "", 0, 0, 'R', false);  
        $pdf->Cell(40, 6, "SUBTOTAL", 'LTRB', 0, 'R', false);  
        $pdf->SetFont('Arial', '', 14);
        $pdf->Cell(40, 6, number_format($info["subtotal"], 0, ",", "."), 'LTRB', 0, 'C', false);
        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->Cell(100, 6, "", 0, 0, 'R', false); 
        $pdf->Cell(40, 6, "IVA", 'LTRB', 0, 'R', false);    
        $pdf->SetFont('Arial', '', 14);
        $pdf->Cell(40, 6, number_format($info["iva"], 0, ",", "."), 'LTRB', 0, 'C', false);
        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->Cell(100, 6, "", 0, 0, 'R', false); 
        $pdf->Cell(40, 6,"TOTAL", 'LTRB', 0, 'R', false); 
        $pdf->SetFont('Arial', '', 14);
        $pdf->Cell(40, 6, number_format($info["iva"]+$info["subtotal"], 0, ",", "."), 'LTRB', 0, 'C', false);
        $pdf->Ln(20);  
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(60, 6, "Ficha de deposito", 'LTRB', 0, 'C', false);
        $pdf->Cell(40, 6, "Numero de cuenta", 'LTRB', 0, 'C', false);
        $pdf->Cell(40, 6, "Banco", 'LTRB', 0, 'C', false);
        $pdf->Cell(40, 6, "Beneficiario", 'LTRB', 0, 'C', false);
        $pdf->Ln();
        $pdf->Image(IMAGES . SL . 'bancobogota.gif', $pdf->GetX()+3, $pdf->GetY()+2, 0);  
        $pdf->Cell(60, 22, "", 'LTRB', 0, 'C', false);
        $pdf->Cell(40, 22, "422043612", 'LTRB', 0, 'C', false);
        $pdf->Cell(40, 22, "Banco de Bogota", 'LTRB', 0, 'C', false);
        $pdf->Cell(40, 22, "REDSOL S.A.S", 'LTRB', 0, 'C', false);
        $pdf->Ln();        
        $pdf->Output("tmp/".$_SESSION["pdfinfo"]["archivo"], 'F');        
    } 
}

?>