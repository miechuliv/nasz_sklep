<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Robert
 * Date: 18.06.13
 * Time: 15:06
 * To change this template use File | Settings | File Templates.
 */

class ModelToolCars extends Model{

    /*
     *  @returns tablica marek z modelami i typami
     */

    public function getAll()
    {
        $results = $this->db->query("SELECT * FROM make");

        $data=array();

        if($results->num_rows>0)
        {

             foreach($results->rows as $result)
             {

                 if(isset($result['make_id'])){


                  $models = $this->db->query("SELECT * FROM `model` WHERE make_id = '".(int)$result['make_id']."' ");
                  $model_data=array();

                  if($models->num_rows>0)
                  {

                      foreach($models->rows as $model)
                      {

                          if(isset($model['model_id'])){


                             $type_data=array();

                             $types = $this->db->query("SELECT * FROM `type` WHERE model_id = '".(int)$model['model_id']."' ");

                            if($types->num_rows>0)
                            {
                                foreach($types->rows as $type)
                                {
                                    if(isset($type['type_id'])){


                                    $type_data[]=array(
                                        'type_id' => $type['type_id'],
                                        'type_name' => $type['type_name'],
                                        'hp_min' => $type['hp_min'],
                                        'hp_max' => $type['hp_max'],
                                        'kw_start' => $type['kw_start'],
                                        'kw_end' => $type['kw_end'],
                                    );

                                    }
                                }
                            }

                          $model_data[]=array(
                              'model_id' => $model['model_id'],
                              'model_name' => $model['model_name'],
                              'year_start' => $model['year_start'],
                              'year_stop' => $model['year_stop'],
                              'types' => $type_data
                          );

                          }
                      }
                  }

                  $data[]=array(
                      'make_id' => $result['make_id'],
                      'make_name' => $result['make_name'],
                      'models' => $model_data,

                  );

                 }
             }
        }



        return $data;
    }

    public function productToCarInsert($data)
    {


        $this->db->query("INSERT INTO `product_to_car` SET product_id='".(int)$data['product_id']."', make_id='".(int)$data['make_id']."', model_id='".(int)$data['model_id']."', type_id='".(int)$data['type_id']."' ");

        return $this->db->getLastId();
    }

    public function deleteAllCarsById($product_id)
    {
        $this->db->query("DELETE FROM `product_to_car` WHERE product_id='".(int)$product_id."' ");
    }


    public function getAllCarsByProductId($product_id,$raw = false)
    {
        $results = $this->db->query("SELECT ptc.make_id as mkd, ptc.model_id as mdd, ptc.type_id as tpd, m.make_name as mkn, md.model_name as mdn, t.type_name as tpn FROM `product_to_car` ptc LEFT JOIN make m ON (ptc.make_id=m.make_id) LEFT JOIN model md ON (ptc.model_id=md.model_id) LEFT JOIN type t ON (ptc.type_id=t.type_id) WHERE product_id='".(int)$product_id."' ");
        $data=array();



        if($results->num_rows AND !$raw){

            foreach($results->rows as $row)
            {
                $data[]=array(
                    'ids' => $row['make_id'].'_'.$row['model_id'].'_'.$row['type_id'],
                    'name' => 'Marka: '.$row['make_name'].' Model: '.$row['model_name'].' Typ: '.$row['type_name'],
                );
            }


        }
        elseif($results->num_rows AND $raw)
        {
            foreach($results->rows as $row)
            {
                $data[]=array(
                    'make_id' => $row['mkd'],
                    'model_id' => $row['mdd'],
                    'type_id' => $row['tpd'],
                    'make_name' => $row['mkn'],
                    'model_name' => $row['mdn'],
                    'type_name' => $row['tpn'],
                );
            }
        }

        foreach($data as $key1 => $ar)
        {
             foreach($data as $key2 => $ar2)
             {
                  if(($ar == $ar2) AND $key1 != $key2)
                  {
                        unset($data[$key2]);
                  }
             }
        }

        return $data;
    }

    public function getMake() {



        $makes = $this->db->query("SELECT * FROM `make` ORDER BY make_name ASC");

        if($makes->num_rows>0)
        {
            $data=array();

            foreach ($makes->rows as $row) {
                $data[]=array(
                    'make_id' => $row['make_id'],
                    'make_name' => $row['make_name'],
                );

            }

            return $data;



        }

    }


    public function getModelbyMake($make_id,$year = false, $clean = false) {



        if($year AND $year!='' AND $year!='WSZYSTKIE ROCZNIKI'  AND $year!='Baujahr')
        {
            $year .= '-01-01';



            $models = $this->db->query("SELECT model_id,model_name,DATE_FORMAT(year_start, '%Y') as year1, DATE_FORMAT(year_stop, '%Y') as year2 FROM `model` WHERE make_id='".$make_id."' AND year_start <= '".$year."' AND year_stop >= '".$year."' ORDER BY model_name ASC, year1 ASC");

        }
        else
        {


            $models = $this->db->query("SELECT model_id,model_name,platform,DATE_FORMAT(year_start, '%Y') as year1, DATE_FORMAT(year_stop, '%Y') as year2 FROM `model` WHERE make_id='".$make_id."' AND year_start > '1995-01-01'  ORDER BY model_name ASC, year1 ASC");

        }

        if($models->num_rows>0)
        {
            $data=array();

            foreach ($models->rows as $row) {



                if($row['year2']!='0000'){
                    $year = '('.$row['year1'].' - '.$row['year2'].')';
                }
                else {

                    $year = '('.$row['year1'].' - )';
                }
                if(!$clean)
                {
                    $data[]=array(
                        'model_id' => $row['model_id'],
                        'model_name' => $row['model_name'].'<span style="float:right;"> '.$row['platform'].' </span><span style="float:right;"> '.$year.' </span>',
                    );
                }
                else
                {
                    $data[]=array(
                        'model_id' => $row['model_id'],
                        'model_name' => $row['model_name'],
                    );
                }


            }

            return $data;



        }

    }

    public function getTypebyModel($model_id) {



        $types = $this->db->query("SELECT * FROM `type` WHERE model_id='".$model_id."' ORDER BY type_name ASC");

        if($types->num_rows>0)
        {
            $data=array();

            foreach ($types->rows as $row) {
                $data[]=array(
                    'type_id' => $row['type_id'],
                    'type_name' => $row['type_name'].' - <span style="float:right;">'.$row['kw'].' kw '.$row['ccm'].' ccm '.$row['ps'].' ps  </span>',
                );

            }

            return $data;




        }

    }

    public function getOneMakeById($id)
    {
        $make = $this->db->query("SELECT * FROM `make` WHERE make_id='".$id."'  ");

        if($make->num_rows>0)
        {
            return $make->row['make_name'];
        }
    }

    public function getOneModelById($id,$clean = false)
    {
        $model = $this->db->query("SELECT model_id,model_name,DATE_FORMAT(year_start, '%Y') as year1, DATE_FORMAT(year_stop, '%Y') as year2 FROM `model` WHERE model_id='".$id."' ");

        if($model->num_rows>0)
        {
            if(!$clean)
            {
                return $model->row['model_name'].'  ('.$model->row['year1'].' - '.$model->row['year2'].')';
            }
            else
            {
                return $model->row['model_name'];
            }

        }
    }

    public function getOneTypeById($id)
    {
        $type = $this->db->query("SELECT * FROM `type` WHERE type_id='".$id."'  ");

        if($type->num_rows>0)
        {
            return $type->row['type_name'];
        }
    }

}